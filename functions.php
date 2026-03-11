<?php
/**
 * 和招縁テーマの関数とセットアップ
 * 
 * @package Washouen
 * @since 1.0.0
 */

// テーマのセットアップ
function washouen_setup() {
    // タイトルタグのサポート
    add_theme_support('title-tag');
    
    // カスタムロゴ
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // アイキャッチ画像のサポート
    add_theme_support('post-thumbnails');
    add_image_size('washouen-featured', 1200, 600, true);
    add_image_size('washouen-gallery', 400, 400, true);
    add_image_size('washouen-menu', 600, 400, true);
    // ホーム用の画像サイズ（推奨サイズに合わせる）
    add_image_size('home-hero', 1920, 1080, true);
    add_image_size('home-card', 1200, 800, true);
    // ホーム メニューアイコン画像サイズ（240x85）
    add_image_size('home-menu-icon', 240, 85, true);
    
    // カスタムメニューの登録
    register_nav_menus(array(
        'primary' => __('メインメニュー', 'washouen'),
        'footer'  => __('フッターメニュー', 'washouen'),
    ));
    
    // HTML5マークアップのサポート
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // カスタム背景
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
    ));
    
    // エディタースタイル
    add_theme_support('editor-styles');
    add_editor_style('css/editor-style.css');
    
    // ブロックエディターのワイド配置
    add_theme_support('align-wide');
    
    // レスポンシブ埋め込み
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'washouen_setup');

// スタイルシートとスクリプトの読み込み
function washouen_scripts() {
    // Google Fonts
    wp_enqueue_style('washouen-google-fonts', 'https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@300;400;500;700&family=Noto+Sans+JP:wght@300;400;500;700&display=swap', array(), null);
    
    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css', array(), '6.4.0');
    
    // テーマのスタイルシート（style.css - テーマ情報含む）
    wp_enqueue_style('washouen-theme', get_stylesheet_uri(), array('washouen-google-fonts', 'font-awesome'), wp_get_theme()->get('Version'));
    
    // メインスタイルシート
    wp_enqueue_style('washouen-main', get_template_directory_uri() . '/css/style.css', array('washouen-theme'), '1.0.8');

    // レスポンシブスタイルシート
    wp_enqueue_style('washouen-responsive', get_template_directory_uri() . '/css/responsive.css', array('washouen-main'), '1.0.8');
    
    // JavaScriptファイル（jQueryに依存しない）
    wp_enqueue_script('washouen-main', get_template_directory_uri() . '/js/main.js', array(), '1.0.0', true);
    
    // JavaScriptにローカライズデータを渡す
    wp_localize_script('washouen-main', 'washouen_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('washouen_nonce'),
    ));
    
    // コメント返信スクリプト
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'washouen_scripts');

// ==========================================
// お知らせ・ご利用案内・LP 共通ヘルパー
// ==========================================

/**
 * 投稿一覧（お知らせ一覧）のURLを返す
 */
function washouen_get_news_page_url() {
    $posts_page_id = (int) get_option('page_for_posts');
    if ($posts_page_id > 0) {
        $posts_page_url = get_permalink($posts_page_id);
        if ($posts_page_url) {
            return $posts_page_url;
        }
    }

    $news_page = get_page_by_path('news', OBJECT, 'page');
    if ($news_page instanceof WP_Post && $news_page->post_status === 'publish') {
        return get_permalink($news_page->ID);
    }

    return home_url('/news/');
}

/**
 * ヘッダードロップダウン用の最新記事を返す
 */
function washouen_get_recent_news_posts($limit = 3) {
    $limit = max(1, (int) $limit);

    return get_posts(array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
        'ignore_sticky_posts' => true,
    ));
}

/**
 * メニュー項目がお知らせリンクかどうかを判定する
 */
function washouen_is_news_menu_item($item) {
    if (!is_object($item)) {
        return false;
    }

    $item_path = wp_parse_url((string) $item->url, PHP_URL_PATH);
    $news_path = wp_parse_url(washouen_get_news_page_url(), PHP_URL_PATH);

    $item_path = untrailingslashit('/' . ltrim((string) $item_path, '/'));
    $news_path = untrailingslashit('/' . ltrim((string) $news_path, '/'));

    if ($item_path !== '/' && $item_path === $news_path) {
        return true;
    }

    return trim(wp_strip_all_tags((string) $item->title)) === 'お知らせ';
}

/**
 * お知らせドロップダウンのHTMLを返す
 */
function washouen_get_news_dropdown_markup($limit = 3) {
    static $markup_cache = array();

    $limit = max(1, (int) $limit);

    if (isset($markup_cache[$limit])) {
        return $markup_cache[$limit];
    }

    $news_posts = washouen_get_recent_news_posts($limit);
    $news_page_url = washouen_get_news_page_url();

    ob_start();
    ?>
    <div class="news-dropdown" aria-label="最新のお知らせ">
        <div class="news-dropdown-list">
            <?php if (!empty($news_posts)) : ?>
                <?php foreach ($news_posts as $news_post) : ?>
                    <article class="news-dropdown-item">
                        <a href="<?php echo esc_url(get_permalink($news_post)); ?>" class="news-dropdown-link">
                            <span class="news-dropdown-thumb">
                                <?php if (has_post_thumbnail($news_post)) : ?>
                                    <?php echo get_the_post_thumbnail($news_post, 'thumbnail', array('class' => 'news-dropdown-image', 'loading' => 'lazy')); ?>
                                <?php else : ?>
                                    <span class="news-dropdown-placeholder" aria-hidden="true">
                                        <i class="fas fa-newspaper"></i>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <span class="news-dropdown-title"><?php echo esc_html(get_the_title($news_post)); ?></span>
                        </a>
                    </article>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="news-dropdown-empty">現在お知らせはありません。</p>
            <?php endif; ?>
        </div>
        <a href="<?php echo esc_url($news_page_url); ?>" class="news-dropdown-more">お知らせ一覧へ</a>
    </div>
    <?php

    $markup_cache[$limit] = trim(ob_get_clean());

    return $markup_cache[$limit];
}

/**
 * 通常のWordPressメニューでも「お知らせ」に最新記事ドロップダウンを付与する
 */
function washouen_add_news_dropdown_menu_classes($classes, $item, $args, $depth) {
    if ($depth !== 0 || empty($args->theme_location) || $args->theme_location !== 'primary') {
        return $classes;
    }

    if (!washouen_is_news_menu_item($item)) {
        return $classes;
    }

    $classes[] = 'menu-item-news';
    $classes[] = 'menu-item-has-news-dropdown';

    return array_unique($classes);
}
add_filter('nav_menu_css_class', 'washouen_add_news_dropdown_menu_classes', 10, 4);

/**
 * 通常のWordPressメニューでも「お知らせ」に最新記事ドロップダウンを差し込む
 */
function washouen_add_news_dropdown_menu_markup($item_output, $item, $depth, $args) {
    if ($depth !== 0 || empty($args->theme_location) || $args->theme_location !== 'primary') {
        return $item_output;
    }

    if (!washouen_is_news_menu_item($item)) {
        return $item_output;
    }

    return $item_output . washouen_get_news_dropdown_markup(3);
}
add_filter('walker_nav_menu_start_el', 'washouen_add_news_dropdown_menu_markup', 10, 4);

// ==========================================
// ご利用案内ドロップダウン
// ==========================================

/**
 * メニュー項目がご利用案内リンクかどうかを判定する
 */
function washouen_is_guide_menu_item($item) {
    if (!is_object($item)) {
        return false;
    }

    $item_path  = wp_parse_url((string) $item->url, PHP_URL_PATH);
    $guide_path = wp_parse_url(washouen_get_usage_guide_url(), PHP_URL_PATH);

    $item_path  = untrailingslashit('/' . ltrim((string) $item_path, '/'));
    $guide_path = untrailingslashit('/' . ltrim((string) $guide_path, '/'));

    if ($item_path !== '/' && $item_path === $guide_path) {
        return true;
    }

    return trim(wp_strip_all_tags((string) $item->title)) === 'ご利用案内';
}

/**
 * ご利用案内ドロップダウンのHTMLを返す
 */
function washouen_get_guide_dropdown_markup() {
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $lp_pages  = washouen_get_published_lp_pages();
    $guide_url = washouen_get_usage_guide_url();

    ob_start();
    ?>
    <div class="news-dropdown" aria-label="ご利用案内">
        <div class="news-dropdown-list">
            <?php if (!empty($lp_pages)) : ?>
                <?php foreach ($lp_pages as $lp_page) : ?>
                    <article class="news-dropdown-item">
                        <a href="<?php echo esc_url(get_permalink($lp_page)); ?>" class="news-dropdown-link">
                            <span class="news-dropdown-thumb">
                                <?php if (has_post_thumbnail($lp_page)) : ?>
                                    <?php echo get_the_post_thumbnail($lp_page, 'thumbnail', array('class' => 'news-dropdown-image', 'loading' => 'lazy')); ?>
                                <?php else : ?>
                                    <?php
                                    $icon = get_post_meta($lp_page->ID, 'lp_icon', true);
                                    $icon = $icon !== '' ? $icon : 'fas fa-utensils';
                                    ?>
                                    <span class="news-dropdown-placeholder" aria-hidden="true">
                                        <i class="<?php echo esc_attr($icon); ?>"></i>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <span class="news-dropdown-title"><?php echo esc_html(get_the_title($lp_page)); ?></span>
                        </a>
                    </article>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="news-dropdown-empty">現在ご利用案内はありません。</p>
            <?php endif; ?>
        </div>
        <a href="<?php echo esc_url($guide_url); ?>" class="news-dropdown-more">ご利用案内一覧へ</a>
    </div>
    <?php

    $cache = trim(ob_get_clean());
    return $cache;
}

/**
 * WordPressメニューで「ご利用案内」にドロップダウンクラスを付与する
 */
function washouen_add_guide_dropdown_menu_classes($classes, $item, $args, $depth) {
    if ($depth !== 0 || empty($args->theme_location) || $args->theme_location !== 'primary') {
        return $classes;
    }

    if (!washouen_is_guide_menu_item($item)) {
        return $classes;
    }

    $classes[] = 'menu-item-has-guide-dropdown';

    return array_unique($classes);
}
add_filter('nav_menu_css_class', 'washouen_add_guide_dropdown_menu_classes', 10, 4);

/**
 * WordPressメニューで「ご利用案内」にドロップダウンHTMLを差し込む
 */
function washouen_add_guide_dropdown_menu_markup($item_output, $item, $depth, $args) {
    if ($depth !== 0 || empty($args->theme_location) || $args->theme_location !== 'primary') {
        return $item_output;
    }

    if (!washouen_is_guide_menu_item($item)) {
        return $item_output;
    }

    return $item_output . washouen_get_guide_dropdown_markup();
}
add_filter('walker_nav_menu_start_el', 'washouen_add_guide_dropdown_menu_markup', 10, 4);

/**
 * ご利用案内ページIDを返す
 */
function washouen_get_usage_guide_page_id() {
    $candidate_slugs = array(
        'guide',
    );

    foreach ($candidate_slugs as $slug) {
        $page = get_page_by_path($slug, OBJECT, 'page');
        if ($page instanceof WP_Post && $page->post_status === 'publish') {
            return (int) $page->ID;
        }
    }

    $guide_query = new WP_Query(array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-usage-guide.php',
        'no_found_rows' => true,
    ));

    if (!empty($guide_query->posts)) {
        return (int) $guide_query->posts[0];
    }

    return 0;
}

/**
 * ご利用案内ページURLを返す
 */
function washouen_get_usage_guide_url() {
    $guide_page_id = washouen_get_usage_guide_page_id();
    if ($guide_page_id > 0) {
        $guide_page_url = get_permalink($guide_page_id);
        if ($guide_page_url) {
            return $guide_page_url;
        }
    }

    return home_url('/guide/');
}

/**
 * スラッグでLPページ（公開済み固定ページ）を取得
 */
function washouen_get_lp_page_by_slug($slug) {
    $slug = sanitize_title($slug);
    if (empty($slug)) {
        return null;
    }

    $lp_page = get_page_by_path($slug, OBJECT, 'page');
    if (!($lp_page instanceof WP_Post) || $lp_page->post_status !== 'publish') {
        return null;
    }

    return $lp_page;
}

/**
 * LPテンプレートの公開済み固定ページ一覧を取得
 */
function washouen_get_published_lp_pages($exclude_ids = array()) {
    $exclude_ids = array_filter(array_map('intval', (array) $exclude_ids));

    $lp_query = new WP_Query(array(
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => array(
            'menu_order' => 'ASC',
            'title'      => 'ASC',
        ),
        'post__not_in'   => $exclude_ids,
        'meta_query'     => array(
            'relation' => 'AND',
            array(
                'key'   => '_wp_page_template',
                'value' => 'page-lp.php',
            ),
            array(
                'key'   => '_washouen_is_lp',
                'value' => '1',
            ),
        ),
        'no_found_rows'  => true,
    ));

    return $lp_query->posts;
}

/**
 * LPテンプレート選択時に自動的にフラグをセット
 */
function washouen_sync_lp_flag($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (get_post_type($post_id) !== 'page') {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $template = get_post_meta($post_id, '_wp_page_template', true);
    if ($template === 'page-lp.php') {
        update_post_meta($post_id, '_washouen_is_lp', '1');
    } else {
        delete_post_meta($post_id, '_washouen_is_lp');
    }
}
add_action('save_post_page', 'washouen_sync_lp_flag');

/**
 * 既存LPページへのフラグ一括マイグレーション（初回のみ）
 */
function washouen_migrate_lp_flags() {
    if (get_option('washouen_lp_flag_migrated')) {
        return;
    }

    $pages = get_pages(array(
        'meta_key'   => '_wp_page_template',
        'meta_value' => 'page-lp.php',
    ));
    if ($pages) {
        foreach ($pages as $page) {
            update_post_meta($page->ID, '_washouen_is_lp', '1');
        }
    }
    update_option('washouen_lp_flag_migrated', '1');
}
add_action('admin_init', 'washouen_migrate_lp_flags');

/**
 * 投稿本文から固定LPへリンクしやすくするショートコード
 * 例: [washouen_lp_link slug="himeji-fugu-course" label="姫路で河豚鍋を食べるなら"]
 */
function washouen_lp_link_shortcode($atts) {
    $atts = shortcode_atts(array(
        'slug' => '',
        'label' => '',
        'class' => '',
    ), $atts, 'washouen_lp_link');

    $lp_page = washouen_get_lp_page_by_slug($atts['slug']);
    if (!$lp_page) {
        return '';
    }

    $label = trim($atts['label']);
    if ($label === '') {
        $label = get_the_title($lp_page->ID);
    }

    $class_names = array('lp-inline-link');
    if (!empty($atts['class'])) {
        $class_names[] = sanitize_html_class($atts['class']);
    }

    return sprintf(
        '<a href="%1$s" class="%2$s">%3$s</a>',
        esc_url(get_permalink($lp_page->ID)),
        esc_attr(implode(' ', $class_names)),
        esc_html($label)
    );
}
add_shortcode('washouen_lp_link', 'washouen_lp_link_shortcode');

// ==========================================
// SEO・ファビコン関連のメタタグ
// ==========================================

// Open Graphとその他のメタタグを追加
function washouen_add_seo_meta_tags() {
    $site_icon_url = get_site_icon_url();
    $site_icon_id = get_option('site_icon');

    if ($site_icon_url && $site_icon_id) {
        // 検索エンジン用の各種サイズのfavicon（Google推奨）
        echo '<link rel="icon" type="image/png" sizes="192x192" href="' . esc_url(wp_get_attachment_image_url($site_icon_id, array(192, 192))) . '">' . "\n";
        echo '<link rel="icon" type="image/png" sizes="512x512" href="' . esc_url(wp_get_attachment_image_url($site_icon_id, array(512, 512))) . '">' . "\n";

        // Open Graph用の画像（Facebook、Twitter、検索エンジンなど）
        // 大きいサイズを指定（検索結果での表示品質向上）
        $og_image_url = wp_get_attachment_image_url($site_icon_id, 'full');
        echo '<meta property="og:image" content="' . esc_url($og_image_url) . '">' . "\n";
        echo '<meta property="og:image:width" content="512">' . "\n";
        echo '<meta property="og:image:height" content="512">' . "\n";
        echo '<meta property="og:image:type" content="image/png">' . "\n";

        // Twitter Card用
        echo '<meta name="twitter:card" content="summary">' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($og_image_url) . '">' . "\n";

        // Microsoft用（Edge、Bingなど）
        echo '<meta name="msapplication-TileImage" content="' . esc_url($og_image_url) . '">' . "\n";
        echo '<meta name="msapplication-TileColor" content="#8b7355">' . "\n";

        // テーマカラー（モバイルブラウザ用）
        echo '<meta name="theme-color" content="#8b7355">' . "\n";
    }

    // サイト情報
    $site_name = get_bloginfo('name');
    $site_url = home_url('/');

    if (is_front_page()) {
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta property="og:url" content="' . esc_url($site_url) . '">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($site_name) . '">' . "\n";
    } else {
        echo '<meta property="og:type" content="article">' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
        echo '<meta property="og:title" content="' . esc_attr(wp_get_document_title()) . '">' . "\n";
    }
    echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '">' . "\n";
    echo '<meta property="og:locale" content="ja_JP">' . "\n";

    // メタディスクリプション
    $meta_description = washouen_get_meta_description();
    if ($meta_description) {
        echo '<meta name="description" content="' . esc_attr($meta_description) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($meta_description) . '">' . "\n";
    }

    // 構造化データ（JSON-LD）- Google検索結果用
    if ($site_icon_url && $site_icon_id) {
        $logo_url = wp_get_attachment_image_url($site_icon_id, 'full');

        // 基本的な組織情報
        $schema_org = array(
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $site_name,
            'url' => $site_url,
            'logo' => $logo_url
        );
        echo '<script type="application/ld+json">' . wp_json_encode($schema_org, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";

        // 飲食店向け構造化データ（LocalBusiness/Restaurant）
        washouen_add_restaurant_schema();
    }

    // ご利用案内ページの構造化データ
    if (is_page_template('page-usage-guide.php')) {
        washouen_add_usage_guide_schema();
    }
}
add_action('wp_head', 'washouen_add_seo_meta_tags', 5);

// 飲食店向け構造化データを出力
function washouen_add_restaurant_schema() {
    // 福中店の構造化データ
    $fukunaka_schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Restaurant',
        '@id' => home_url('/#fukunaka'),
        'name' => '和招縁 福中店',
        'description' => '姫路市の活魚・一品料理の店。瀬戸内海の新鮮な活魚を水槽から直前に調理。カウンター席、座敷、完全個室完備。',
        'image' => get_theme_mod('home_fukunaka_image', 0) ? wp_get_attachment_image_url(get_theme_mod('home_fukunaka_image', 0), 'large') : '',
        'servesCuisine' => '日本料理',
        'priceRange' => '¥¥¥',
        'address' => array(
            '@type' => 'PostalAddress',
            'streetAddress' => '福中町78',
            'addressLocality' => '姫路市',
            'addressRegion' => '兵庫県',
            'postalCode' => '670-0017',
            'addressCountry' => 'JP'
        ),
        'geo' => array(
            '@type' => 'GeoCoordinates',
            'latitude' => 34.83318,
            'longitude' => 134.68632
        ),
        'telephone' => get_theme_mod('fukunaka_phone', '079-284-5355'),
        'openingHoursSpecification' => array(
            array(
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => array('Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
                'opens' => '18:00',
                'closes' => '01:00'
            )
        ),
        'url' => home_url('/fukunaka-menu/'),
        'hasMenu' => home_url('/fukunaka-menu/'),
        'acceptsReservations' => 'True'
    );

    // 塩町店の構造化データ
    $shiomachi_schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Restaurant',
        '@id' => home_url('/#shiomachi'),
        'name' => '和招縁 塩町店',
        'description' => '姫路市の寿司店。瀬戸内の恵みと家島生まれの店主が贈る真心の寿司。カウンター席、掘りごたつ・テーブル席完備。',
        'image' => get_theme_mod('home_shiomachi_image', 0) ? wp_get_attachment_image_url(get_theme_mod('home_shiomachi_image', 0), 'large') : '',
        'servesCuisine' => '寿司',
        'priceRange' => '¥¥¥',
        'address' => array(
            '@type' => 'PostalAddress',
            'streetAddress' => '塩町177 アールビル1F',
            'addressLocality' => '姫路市',
            'addressRegion' => '兵庫県',
            'postalCode' => '670-0904',
            'addressCountry' => 'JP'
        ),
        'geo' => array(
            '@type' => 'GeoCoordinates',
            'latitude' => 34.83158,
            'longitude' => 134.68583
        ),
        'telephone' => get_theme_mod('shiomachi_phone', '079-223-6879'),
        'openingHoursSpecification' => array(
            array(
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => array('Monday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
                'opens' => '18:00',
                'closes' => '02:00'
            )
        ),
        'url' => home_url('/shiomachi-menu/'),
        'hasMenu' => home_url('/shiomachi-menu/'),
        'acceptsReservations' => 'True'
    );

    echo '<script type="application/ld+json">' . wp_json_encode($fukunaka_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    echo '<script type="application/ld+json">' . wp_json_encode($shiomachi_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}

// ご利用案内ページの構造化データを出力
function washouen_add_usage_guide_schema() {
    $lp_pages = washouen_get_published_lp_pages();
    $list_items = array();

    foreach ($lp_pages as $index => $page) {
        $list_items[] = array(
            '@type'    => 'ListItem',
            'position' => $index + 1,
            'url'      => get_permalink($page->ID),
            'name'     => get_the_title($page->ID),
        );
    }

    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'CollectionPage',
        'name'        => get_the_title() . ' | ' . get_bloginfo('name'),
        'description' => '和招縁のご利用案内。シーン別のご案内ページをまとめています。',
        'url'         => get_permalink(),
        'mainEntity'  => array(
            '@type'           => 'ItemList',
            'itemListElement' => $list_items,
        ),
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}

// メタディスクリプションを生成
function washouen_get_meta_description() {
    $description = '';

    if (is_front_page()) {
        $description = '姫路市の和食・寿司店「和招縁」公式サイト。福中店では瀬戸内海の新鮮な活魚・一品料理を、塩町店では家島生まれの店主が握る真心の寿司をご提供。完全個室・座敷完備。ご予約承ります。';
    } elseif (is_page('first-visit') || is_page_template('page-first-visit.php')) {
        $description = '和招縁のご挨拶ページ。安全で身体に良い物を吟味し、四季「旬」を大切に、お客様に和みながら美味しく料理を食べて頂きたいという思いを込めて開業いたしました。';
    } elseif (is_page('fukunaka-menu') || is_page_template('page-fukunaka-menu.php')) {
        $description = '和招縁 福中店のお品書き。瀬戸内海の活魚を水槽から直前に調理。お造り、焼き物、煮付け、揚げ物、季節の特選料理など、天然魚の本当の旨みを様々な調理法でご堪能いただけます。';
    } elseif (is_page('shiomachi-menu') || is_page_template('page-shiomachi-menu.php')) {
        $description = '和招縁 塩町店のお品書き。家島生まれの店主が握る寿司。握り、軍艦・巻物、ちらし・丼、おまかせコースなど、瀬戸内の恵みを真心込めてお届けします。';
    } elseif (is_page('access') || is_page_template('page-access.php')) {
        $description = '和招縁 福中店・塩町店への道案内。姫路駅からのアクセス、営業時間、電話番号、地図など店舗情報を掲載。お車でのご来店も可能です。';
    } elseif (is_page_template('page-usage-guide.php')) {
        $description = '和招縁のご利用案内。寿司・和食・接待・深夜営業など、ご利用シーン別にご案内ページをまとめています。姫路の和食・寿司店 和招縁の各サービスをご覧ください。';
    } elseif (is_singular()) {
        // 個別投稿・ページ
        $post = get_queried_object();
        if (!empty($post->post_excerpt)) {
            $description = wp_strip_all_tags($post->post_excerpt);
        } else {
            $description = wp_trim_words(wp_strip_all_tags($post->post_content), 55, '...');
        }
    }

    return $description;
}

// ==========================================
// パンくずリスト（構造化データ付き）
// ==========================================

function washouen_breadcrumbs() {
    if (is_front_page()) {
        return; // トップページでは表示しない
    }

    $items = array();
    $items[] = array(
        'name' => 'ホーム',
        'url' => home_url('/')
    );

    if (is_page()) {
        $post = get_queried_object();
        if ($post->post_parent) {
            $ancestors = array_reverse(get_post_ancestors($post->ID));
            foreach ($ancestors as $ancestor) {
                $items[] = array(
                    'name' => get_the_title($ancestor),
                    'url' => get_permalink($ancestor)
                );
            }
        }
        $items[] = array(
            'name' => get_the_title(),
            'url' => get_permalink()
        );
    } elseif (is_single()) {
        $categories = get_the_category();
        if ($categories) {
            $items[] = array(
                'name' => $categories[0]->name,
                'url' => get_category_link($categories[0]->term_id)
            );
        }
        $items[] = array(
            'name' => get_the_title(),
            'url' => get_permalink()
        );
    } elseif (is_category()) {
        $items[] = array(
            'name' => single_cat_title('', false),
            'url' => get_category_link(get_queried_object_id())
        );
    }

    // 構造化データ
    $schema_items = array();
    foreach ($items as $index => $item) {
        $schema_items[] = array(
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $item['name'],
            'item' => $item['url']
        );
    }

    $breadcrumb_schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $schema_items
    );

    echo '<script type="application/ld+json">' . wp_json_encode($breadcrumb_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";

    // HTML出力
    echo '<nav class="breadcrumbs" aria-label="パンくずリスト"><ol>';
    foreach ($items as $index => $item) {
        $is_last = ($index === count($items) - 1);
        echo '<li>';
        if ($is_last) {
            echo '<span aria-current="page">' . esc_html($item['name']) . '</span>';
        } else {
            echo '<a href="' . esc_url($item['url']) . '">' . esc_html($item['name']) . '</a>';
        }
        echo '</li>';
    }
    echo '</ol></nav>';
}

// ==========================================
// XMLサイトマップ生成
// ==========================================

function washouen_generate_sitemap() {
    header('Content-Type: text/xml; charset=UTF-8');

    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    // ホームページ
    echo '  <url>' . "\n";
    echo '    <loc>' . esc_url(home_url('/')) . '</loc>' . "\n";
    echo '    <changefreq>daily</changefreq>' . "\n";
    echo '    <priority>1.0</priority>' . "\n";
    echo '  </url>' . "\n";

    // 固定ページ
    $pages = get_pages(array('sort_column' => 'post_date', 'sort_order' => 'DESC'));
    foreach ($pages as $page) {
        echo '  <url>' . "\n";
        echo '    <loc>' . esc_url(get_permalink($page->ID)) . '</loc>' . "\n";
        echo '    <lastmod>' . esc_html(get_the_modified_date('Y-m-d', $page->ID)) . '</lastmod>' . "\n";
        echo '    <changefreq>weekly</changefreq>' . "\n";
        echo '    <priority>0.8</priority>' . "\n";
        echo '    </url>' . "\n";
    }

    // 投稿
    $posts = get_posts(array('numberposts' => -1, 'post_status' => 'publish'));
    foreach ($posts as $post) {
        echo '  <url>' . "\n";
        echo '    <loc>' . esc_url(get_permalink($post->ID)) . '</loc>' . "\n";
        echo '    <lastmod>' . esc_html(get_the_modified_date('Y-m-d', $post->ID)) . '</lastmod>' . "\n";
        echo '    <changefreq>monthly</changefreq>' . "\n";
        echo '    <priority>0.6</priority>' . "\n";
        echo '  </url>' . "\n";
    }

    echo '</urlset>';
    exit;
}

// サイトマップURLの設定
function washouen_sitemap_rewrite() {
    add_rewrite_rule('^sitemap\.xml$', 'index.php?washouen_sitemap=1', 'top');
}
add_action('init', 'washouen_sitemap_rewrite');

function washouen_sitemap_query_vars($vars) {
    $vars[] = 'washouen_sitemap';
    return $vars;
}
add_filter('query_vars', 'washouen_sitemap_query_vars');

function washouen_sitemap_template() {
    if (get_query_var('washouen_sitemap')) {
        washouen_generate_sitemap();
    }
}
add_action('template_redirect', 'washouen_sitemap_template');

// ==========================================
// 画像最適化
// ==========================================

// すべての画像に遅延読み込みを追加（ヒーロー画像以外）
add_filter('wp_get_attachment_image_attributes', 'washouen_add_lazy_loading', 10, 3);
function washouen_add_lazy_loading($attr, $attachment, $size) {
    // ヒーロー画像（home-hero）は遅延読み込みしない
    if ($size === 'home-hero' || (isset($attr['fetchpriority']) && $attr['fetchpriority'] === 'high')) {
        $attr['loading'] = 'eager';
    } elseif (!isset($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }
    return $attr;
}

// WebP形式への自動変換を有効化
add_filter('wp_generate_attachment_metadata', 'washouen_create_webp_versions', 10, 2);
function washouen_create_webp_versions($metadata, $attachment_id) {
    $file = get_attached_file($attachment_id);

    // JPG、JPEG、PNGファイルのみ処理
    if (preg_match('/\.(jpg|jpeg|png)$/i', $file)) {
        $image = wp_get_image_editor($file);

        if (!is_wp_error($image)) {
            // WebPファイルパスを生成
            $webp_file = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $file);

            // WebP形式で保存
            $saved = $image->save($webp_file, 'image/webp');

            // すべてのサイズについてもWebP版を作成
            if (isset($metadata['sizes']) && is_array($metadata['sizes'])) {
                $upload_dir = wp_upload_dir();
                $base_dir = dirname($file);

                foreach ($metadata['sizes'] as $size => $size_data) {
                    $size_file = $base_dir . '/' . $size_data['file'];

                    if (file_exists($size_file)) {
                        $size_image = wp_get_image_editor($size_file);

                        if (!is_wp_error($size_image)) {
                            $size_webp_file = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $size_file);
                            $size_image->save($size_webp_file, 'image/webp');
                        }
                    }
                }
            }
        }
    }

    return $metadata;
}

// レスポンシブ画像の最適化: srcset と sizes 属性を強化
add_filter('wp_calculate_image_srcset', 'washouen_optimize_srcset', 10, 5);
function washouen_optimize_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {
    // WebP版が存在する場合は、そちらを優先
    foreach ($sources as $width => $source) {
        $webp_url = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $source['url']);
        $webp_path = str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $webp_url);

        if (file_exists($webp_path)) {
            $sources[$width]['url'] = $webp_url;
        }
    }

    return $sources;
}

// <picture>タグを使ってWebPを優先的に読み込む
// EWWW Image OptimizerのWebP配信機能を使用する場合は、この関数をコメントアウト
// add_filter('wp_get_attachment_image', 'washouen_webp_picture_tag', 10, 5);
function washouen_webp_picture_tag($html, $attachment_id, $size, $icon, $attr) {
    // 画像URLを取得
    $image = wp_get_attachment_image_src($attachment_id, $size);
    if (!$image) {
        return $html;
    }

    $image_url = $image[0];
    $upload_dir = wp_upload_dir();

    // EWWW Image Optimizer形式: example.jpg.webp
    $webp_url = $image_url . '.webp';
    $webp_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $webp_url);

    // WebPファイルが存在しない場合、標準形式も試す: example.webp
    if (!file_exists($webp_path)) {
        $webp_url = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $image_url);
        $webp_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $webp_url);
    }

    // WebPファイルが存在する場合のみ<picture>タグに変換
    if (file_exists($webp_path)) {
        // 既存の<img>タグから属性を抽出
        preg_match('/<img([^>]+)>/i', $html, $matches);
        $img_attributes = isset($matches[1]) ? $matches[1] : '';

        // <picture>タグを生成
        $picture_html = '<picture>';
        // data-srcsetとsrcsetの両方を設定（遅延読み込み対応）
        $picture_html .= '<source type="image/webp" srcset="' . esc_url($webp_url) . '" data-srcset="' . esc_url($webp_url) . '">';
        $picture_html .= '<img' . $img_attributes . '>';
        $picture_html .= '</picture>';

        return $picture_html;
    }

    return $html;
}

// メニュー編集機能を読み込む
require_once get_template_directory() . '/inc/menu-editor.php';

// サンプルデータ生成機能を読み込む
require_once get_template_directory() . '/inc/menu-sample-data.php';

// インポート/エクスポート機能を読み込む
require_once get_template_directory() . '/inc/menu-import-export.php';

// カテゴリー順序管理機能を読み込む
require_once get_template_directory() . '/inc/category-order.php';

// カスタム投稿タイプ: メニュー
function washouen_custom_post_types() {
    // 福中店 お品書き
    register_post_type('fukunaka_menu', array(
        'labels' => array(
            'name' => __('福中店 お品書き', 'washouen'),
            'singular_name' => __('福中店 お品書き項目', 'washouen'),
            'add_new' => __('新規追加', 'washouen'),
            'add_new_item' => __('新規メニュー項目を追加', 'washouen'),
            'edit_item' => __('メニュー項目を編集', 'washouen'),
            'all_items' => __('お品書き一覧', 'washouen'),
            'featured_image' => __('メニュー画像', 'washouen'),
            'set_featured_image' => __('メニュー画像を設定', 'washouen'),
            'remove_featured_image' => __('メニュー画像を削除', 'washouen'),
            'use_featured_image' => __('メニュー画像として使用', 'washouen'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'has_archive' => true,
        'supports' => array('title', 'thumbnail', 'page-attributes'),
        'menu_icon' => 'dashicons-food',
        'menu_position' => 20,
        'rewrite' => array('slug' => 'fukunaka-menu-items'),
        'capability_type' => 'post',
    ));

    // 塩町店 お品書き
    register_post_type('shiomachi_menu', array(
        'labels' => array(
            'name' => __('塩町店 お品書き', 'washouen'),
            'singular_name' => __('塩町店 お品書き項目', 'washouen'),
            'add_new' => __('新規追加', 'washouen'),
            'add_new_item' => __('新規メニュー項目を追加', 'washouen'),
            'edit_item' => __('メニュー項目を編集', 'washouen'),
            'all_items' => __('お品書き一覧', 'washouen'),
            'featured_image' => __('メニュー画像', 'washouen'),
            'set_featured_image' => __('メニュー画像を設定', 'washouen'),
            'remove_featured_image' => __('メニュー画像を削除', 'washouen'),
            'use_featured_image' => __('メニュー画像として使用', 'washouen'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'has_archive' => true,
        'supports' => array('title', 'thumbnail', 'page-attributes'),
        'menu_icon' => 'dashicons-food',
        'menu_position' => 21,
        'rewrite' => array('slug' => 'shiomachi-menu-items'),
        'capability_type' => 'post',
    ));
}
add_action('init', 'washouen_custom_post_types');

// カスタムタクソノミー: メニューカテゴリー
function washouen_custom_taxonomies() {
    // 福中店 お品書きカテゴリー
    register_taxonomy('fukunaka_category', 'fukunaka_menu', array(
        'labels' => array(
            'name' => __('メニューカテゴリー', 'washouen'),
            'singular_name' => __('カテゴリー', 'washouen'),
            'search_items' => __('カテゴリーを検索', 'washouen'),
            'all_items' => __('すべてのカテゴリー', 'washouen'),
            'parent_item' => __('親カテゴリー', 'washouen'),
            'parent_item_colon' => __('親カテゴリー:', 'washouen'),
            'edit_item' => __('カテゴリーを編集', 'washouen'),
            'update_item' => __('カテゴリーを更新', 'washouen'),
            'add_new_item' => __('新規カテゴリーを追加', 'washouen'),
            'new_item_name' => __('新しいカテゴリー名', 'washouen'),
            'menu_name' => __('メニューカテゴリー', 'washouen'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'fukunaka-category'),
    ));

    // 塩町店 お品書きカテゴリー
    register_taxonomy('shiomachi_category', 'shiomachi_menu', array(
        'labels' => array(
            'name' => __('メニューカテゴリー', 'washouen'),
            'singular_name' => __('カテゴリー', 'washouen'),
            'search_items' => __('カテゴリーを検索', 'washouen'),
            'all_items' => __('すべてのカテゴリー', 'washouen'),
            'parent_item' => __('親カテゴリー', 'washouen'),
            'parent_item_colon' => __('親カテゴリー:', 'washouen'),
            'edit_item' => __('カテゴリーを編集', 'washouen'),
            'update_item' => __('カテゴリーを更新', 'washouen'),
            'add_new_item' => __('新規カテゴリーを追加', 'washouen'),
            'new_item_name' => __('新しいカテゴリー名', 'washouen'),
            'menu_name' => __('メニューカテゴリー', 'washouen'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'shiomachi-category'),
    ));
}
add_action('init', 'washouen_custom_taxonomies');

// デフォルトカテゴリーを作成
function washouen_create_default_categories() {
    // 福中店のデフォルトカテゴリー
    $fukunaka_categories = array(
        'course' => 'コース料理',
        'sashimi' => 'お造り',
        'grilled' => '焼き物',
        'simmered' => '煮付け',
        'fried' => '揚げ物',
        'special' => '季節の特選料理',
        'drink' => 'お飲み物'
    );

    foreach ($fukunaka_categories as $slug => $name) {
        if (!term_exists($slug, 'fukunaka_category')) {
            wp_insert_term($name, 'fukunaka_category', array('slug' => $slug));
        }
    }

    // 塩町店のデフォルトカテゴリー
    $shiomachi_categories = array(
        'nigiri' => '握り',
        'gunkan' => '軍艦・巻物',
        'chirashi' => 'ちらし・丼',
        'omakase' => 'おまかせコース',
        'side' => '一品料理',
        'drink' => 'お飲み物'
    );

    foreach ($shiomachi_categories as $slug => $name) {
        if (!term_exists($slug, 'shiomachi_category')) {
            wp_insert_term($name, 'shiomachi_category', array('slug' => $slug));
        }
    }
}
add_action('init', 'washouen_create_default_categories', 20);

// ウィジェットエリアの登録
function washouen_widgets_init() {
    register_sidebar(array(
        'name' => __('サイドバー', 'washouen'),
        'id' => 'sidebar-1',
        'description' => __('サイドバーに表示されるウィジェット', 'washouen'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'washouen_widgets_init');

// 小数点を含む数値のサニタイズ関数
function washouen_sanitize_float($value, $min = 1.0, $max = 10.0) {
    $value = floatval($value);
    if ($value < $min) {
        $value = $min;
    }
    if ($value > $max) {
        $value = $max;
    }
    return round($value, 1); // 小数点第1位まで
}

// カスタマイザー設定
function washouen_customize_register($wp_customize) {
    // ホーム設定セクション
    $wp_customize->add_section('home_settings', array(
        'title'    => __('ホーム設定', 'washouen'),
        'priority' => 25,
        'description' => __('ホームに表示する画像を設定します。', 'washouen'),
    ));

    // ヒーローメッセージ設定セクション
    $wp_customize->add_section('hero_message_settings', array(
        'title'       => __('ヒーローメッセージ設定', 'washouen'),
        'priority'    => 24,
        'description' => __('トップページのメインビジュアルに表示されるメッセージとアニメーション速度を設定します。', 'washouen'),
    ));

    // アニメーション実行時間（デフォルト1.2秒）
    $wp_customize->add_setting('hero_animation_duration', array(
        'default'           => 1.2,
        'sanitize_callback' => 'washouen_sanitize_float',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    $wp_customize->add_control('hero_animation_duration', array(
        'label'       => __('アニメーション実行時間（秒）', 'washouen'),
        'description' => __('各テキストが表示される時の動きの速さ（1.0〜5.0秒）', 'washouen'),
        'section'     => 'hero_message_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1.0,
            'max'  => 5.0,
            'step' => 0.1,
        ),
    ));

    // 各テキスト間の表示間隔（デフォルト1.0秒）
    $wp_customize->add_setting('hero_animation_interval', array(
        'default'           => 1.0,
        'sanitize_callback' => function($value) {
            return washouen_sanitize_float($value, 0.5, 3.0);
        },
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    $wp_customize->add_control('hero_animation_interval', array(
        'label'       => __('テキスト表示間隔（秒）', 'washouen'),
        'description' => __('次のテキストが表示されるまでの待ち時間（0.5〜3.0秒）', 'washouen'),
        'section'     => 'hero_message_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0.5,
            'max'  => 3.0,
            'step' => 0.1,
        ),
    ));

    // ヒーローテキスト1
    $wp_customize->add_setting('hero_text_1', array(
        'default'           => '数ある店舗の中から「和招縁」にご関心頂き誠にありがとうございます。',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    $wp_customize->add_control('hero_text_1', array(
        'label'       => __('メッセージ 1行目', 'washouen'),
        'section'     => 'hero_message_settings',
        'type'        => 'textarea',
    ));

    // ヒーローテキスト2
    $wp_customize->add_setting('hero_text_2', array(
        'default'           => '安全で身体に良い物を吟味し、四季「旬」を大切に、',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    $wp_customize->add_control('hero_text_2', array(
        'label'       => __('メッセージ 2行目', 'washouen'),
        'section'     => 'hero_message_settings',
        'type'        => 'textarea',
    ));

    // ヒーローテキスト3
    $wp_customize->add_setting('hero_text_3', array(
        'default'           => 'お客様に和みながら美味しく料理を食べて頂きたい。',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    $wp_customize->add_control('hero_text_3', array(
        'label'       => __('メッセージ 3行目', 'washouen'),
        'section'     => 'hero_message_settings',
        'type'        => 'textarea',
    ));

    // ヒーローテキスト4
    $wp_customize->add_setting('hero_text_4', array(
        'default'           => 'そんな思いを込めて和招縁を開業いたしました。',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    $wp_customize->add_control('hero_text_4', array(
        'label'       => __('メッセージ 4行目', 'washouen'),
        'section'     => 'hero_message_settings',
        'type'        => 'textarea',
    ));

    // ヒーロー画像（推奨: 1920x1080）
    $wp_customize->add_setting('home_hero_image', array(
        'default'           => 0,
        'sanitize_callback' => 'absint', // 添付IDを保持
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    if (class_exists('WP_Customize_Media_Control')) {
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'home_hero_image', array(
            'label'       => __('ヒーロー画像', 'washouen'),
            'description' => __('推奨サイズ: 1920×1080（16:9）', 'washouen'),
            'section'     => 'home_settings',
            'mime_type'   => 'image',
        )));
    }

    // 店舗カード画像（福中店）推奨: 1200x800
    $wp_customize->add_setting('home_fukunaka_image', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    if (class_exists('WP_Customize_Media_Control')) {
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'home_fukunaka_image', array(
            'label'       => __('店舗カード画像（福中店）', 'washouen'),
            'description' => __('推奨サイズ: 1200×800', 'washouen'),
            'section'     => 'home_settings',
            'mime_type'   => 'image',
        )));
    }

    // 店舗カード画像（塩町店）推奨: 1200x800
    $wp_customize->add_setting('home_shiomachi_image', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    if (class_exists('WP_Customize_Media_Control')) {
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'home_shiomachi_image', array(
            'label'       => __('店舗カード画像（塩町店）', 'washouen'),
            'description' => __('推奨サイズ: 1200×800', 'washouen'),
            'section'     => 'home_settings',
            'mime_type'   => 'image',
        )));
    }

    // アクセス背景（任意）推奨: 1920x1080
    $wp_customize->add_setting('home_access_bg_image', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    if (class_exists('WP_Customize_Media_Control')) {
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'home_access_bg_image', array(
            'label'       => __('アクセス背景（任意）', 'washouen'),
            'description' => __('推奨サイズ: 1920×1080', 'washouen'),
            'section'     => 'home_settings',
            'mime_type'   => 'image',
        )));
    }

    // ホームギャラリー設定（店舗ごとに4枚ずつ）
    $wp_customize->add_section('home_gallery_settings', array(
        'title'       => __('ホームギャラリー', 'washouen'),
        'priority'    => 26,
        'description' => __('トップページの「御料理」に表示する画像を設定できます。推奨サイズ: 400×400（正方形）', 'washouen'),
    ));

    // ギャラリースライダーの切替秒数
    $wp_customize->add_setting('gallery_slider_interval', array(
        'default'           => 4.0,
        'sanitize_callback' => 'washouen_sanitize_float',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    $wp_customize->add_control('gallery_slider_interval', array(
        'label'       => __('スライダー切替時間（秒）', 'washouen'),
        'description' => __('画像が自動で切り替わる秒数を設定します（小数点第1位まで対応）', 'washouen'),
        'section'     => 'home_gallery_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1.0,
            'max'  => 10.0,
            'step' => 0.1,
        ),
    ));

    // 福中店ギャラリー（1〜4）- 画像と倍率をセットで
    for ($i = 1; $i <= 4; $i++) {
        // 画像設定
        $setting_id = 'home_gallery_fukunaka_' . $i;
        $wp_customize->add_setting($setting_id, array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
            'type'              => 'theme_mod',
        ));
        if (class_exists('WP_Customize_Media_Control')) {
            $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, $setting_id, array(
                'label'       => sprintf(__('福中店 ギャラリー画像 %d', 'washouen'), $i),
                'description' => __('推奨サイズ: 400×400（正方形）', 'washouen'),
                'section'     => 'home_gallery_settings',
                'mime_type'   => 'image',
            )));
        }

        // 倍率設定
        $scale_setting_id = 'home_gallery_fukunaka_' . $i . '_scale';
        $wp_customize->add_setting($scale_setting_id, array(
            'default'           => 1.0,
            'sanitize_callback' => function($value) {
                return washouen_sanitize_float($value, 0.5, 5.0);
            },
            'transport'         => 'refresh',
            'type'              => 'theme_mod',
        ));
        $wp_customize->add_control($scale_setting_id, array(
            'label'       => sprintf(__('福中店 画像 %d の倍率', 'washouen'), $i),
            'description' => __('0.5〜5.0（1.0が標準）', 'washouen'),
            'section'     => 'home_gallery_settings',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0.5,
                'max'  => 5.0,
                'step' => 0.1,
            ),
        ));
    }

    // ご挨拶 ページ用画像設定
    $wp_customize->add_section('first_visit_settings', array(
        'title'       => __('ご挨拶', 'washouen'),
        'priority'    => 27,
        'description' => __('「ご挨拶」ページに表示する画像を設定します。', 'washouen'),
    ));

    // ご挨拶ページ背景画像（推奨: 1920x1080）
    $wp_customize->add_setting('greeting_hero_image', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    if (class_exists('WP_Customize_Media_Control')) {
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'greeting_hero_image', array(
            'label'       => __('ご挨拶 背景画像', 'washouen'),
            'description' => __('推奨サイズ: 1920×1080（16:9）', 'washouen'),
            'section'     => 'first_visit_settings',
            'mime_type'   => 'image',
        )));
    }

    // 福中店（外観・カウンター・個室・料理）
    $first_visit_fukunaka_labels = array('外観', 'カウンター', '個室', '料理');
    for ($i = 1; $i <= 4; $i++) {
        $setting_id = 'first_visit_fukunaka_' . $i;
        $wp_customize->add_setting($setting_id, array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
            'type'              => 'theme_mod',
        ));
        if (class_exists('WP_Customize_Media_Control')) {
            $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, $setting_id, array(
                'label'       => sprintf(__('福中店 %s', 'washouen'), $first_visit_fukunaka_labels[$i - 1]),
                'description' => __('推奨サイズ: 400×400（正方形）', 'washouen'),
                'section'     => 'first_visit_settings',
                'mime_type'   => 'image',
            )));
        }
    }

    // 塩町店（外観・カウンター・鮨・一品料理）
    $first_visit_shiomachi_labels = array('外観', 'カウンター', '鮨', '一品料理');
    for ($i = 1; $i <= 4; $i++) {
        $setting_id = 'first_visit_shiomachi_' . $i;
        $wp_customize->add_setting($setting_id, array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
            'type'              => 'theme_mod',
        ));
        if (class_exists('WP_Customize_Media_Control')) {
            $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, $setting_id, array(
                'label'       => sprintf(__('塩町店 %s', 'washouen'), $first_visit_shiomachi_labels[$i - 1]),
                'description' => __('推奨サイズ: 400×400（正方形）', 'washouen'),
                'section'     => 'first_visit_settings',
                'mime_type'   => 'image',
            )));
        }
    }

    // 塩町店ギャラリー（1〜4）- 画像と倍率をセットで
    for ($i = 1; $i <= 4; $i++) {
        // 画像設定
        $setting_id = 'home_gallery_shiomachi_' . $i;
        $wp_customize->add_setting($setting_id, array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
            'type'              => 'theme_mod',
        ));
        if (class_exists('WP_Customize_Media_Control')) {
            $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, $setting_id, array(
                'label'       => sprintf(__('塩町店 ギャラリー画像 %d', 'washouen'), $i),
                'description' => __('推奨サイズ: 400×400（正方形）', 'washouen'),
                'section'     => 'home_gallery_settings',
                'mime_type'   => 'image',
            )));
        }

        // 倍率設定
        $scale_setting_id = 'home_gallery_shiomachi_' . $i . '_scale';
        $wp_customize->add_setting($scale_setting_id, array(
            'default'           => 1.0,
            'sanitize_callback' => function($value) {
                return washouen_sanitize_float($value, 0.5, 5.0);
            },
            'transport'         => 'refresh',
            'type'              => 'theme_mod',
        ));
        $wp_customize->add_control($scale_setting_id, array(
            'label'       => sprintf(__('塩町店 画像 %d の倍率', 'washouen'), $i),
            'description' => __('0.5〜5.0（1.0が標準）', 'washouen'),
            'section'     => 'home_gallery_settings',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 0.5,
                'max'  => 5.0,
                'step' => 0.1,
            ),
        ));
    }
    // 店舗情報セクション
    $wp_customize->add_section('washouen_store_info', array(
        'title' => __('店舗情報', 'washouen'),
        'priority' => 30,
    ));
    
    // 福中店設定
    // 電話番号
    $wp_customize->add_setting('fukunaka_phone', array(
        'default' => '079-284-5355',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('fukunaka_phone', array(
        'label' => __('福中店電話番号', 'washouen'),
        'section' => 'washouen_store_info',
        'type' => 'text',
    ));
    
    // 住所
    $wp_customize->add_setting('fukunaka_address', array(
        'default' => '〒670-0017 兵庫県姫路市福中町78',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('fukunaka_address', array(
        'label' => __('福中店住所', 'washouen'),
        'section' => 'washouen_store_info',
        'type' => 'text',
    ));
    
    // 営業時間
    $wp_customize->add_setting('fukunaka_hours', array(
        'default' => "昼：11:30～14:00（L.O. 13:30）\n夜：17:00～22:00（L.O. 21:30）",
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('fukunaka_hours', array(
        'label' => __('福中店営業時間', 'washouen'),
        'section' => 'washouen_store_info',
        'type' => 'textarea',
    ));
    
    // 定休日
    $wp_customize->add_setting('fukunaka_closed', array(
        'default' => '月曜日（祝日の場合は翌日）',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('fukunaka_closed', array(
        'label' => __('福中店定休日', 'washouen'),
        'section' => 'washouen_store_info',
        'type' => 'text',
    ));
    
    // 塩町店設定
    // 電話番号
    $wp_customize->add_setting('shiomachi_phone', array(
        'default' => '079-223-6879',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('shiomachi_phone', array(
        'label' => __('塩町店電話番号', 'washouen'),
        'section' => 'washouen_store_info',
        'type' => 'text',
    ));
    
    // 住所
    $wp_customize->add_setting('shiomachi_address', array(
        'default' => '〒670-0904 兵庫県姫路市塩町177 アールビル1F',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('shiomachi_address', array(
        'label' => __('塩町店住所', 'washouen'),
        'section' => 'washouen_store_info',
        'type' => 'text',
    ));
    
    // 営業時間
    $wp_customize->add_setting('shiomachi_hours', array(
        'default' => "昼：11:30～14:00（L.O. 13:30）\n夜：17:00～22:00（L.O. 21:30）",
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('shiomachi_hours', array(
        'label' => __('塩町店営業時間', 'washouen'),
        'section' => 'washouen_store_info',
        'type' => 'textarea',
    ));
    
    // 定休日
    $wp_customize->add_setting('shiomachi_closed', array(
        'default' => '火曜日（祝日の場合は翌日）',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('shiomachi_closed', array(
        'label' => __('塩町店定休日', 'washouen'),
        'section' => 'washouen_store_info',
        'type' => 'text',
    ));
    
    // Google Maps設定
    $wp_customize->add_setting('fukunaka_map_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('fukunaka_map_url', array(
        'label' => __('福中店 Google Maps埋め込みURL', 'washouen'),
        'description' => __('Google MapsのiFrame埋め込みURLを入力してください', 'washouen'),
        'section' => 'washouen_store_info',
        'type' => 'url',
    ));
    
    $wp_customize->add_setting('shiomachi_map_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('shiomachi_map_url', array(
        'label' => __('塩町店 Google Maps埋め込みURL', 'washouen'),
        'description' => __('Google MapsのiFrame埋め込みURLを入力してください', 'washouen'),
        'section' => 'washouen_store_info',
        'type' => 'url',
    ));

    // テイクアウトPDF設定セクション
    $wp_customize->add_section('takeout_settings', array(
        'title'       => __('テイクアウト・出前設定', 'washouen'),
        'priority'    => 31,
        'description' => __('テイクアウトメニューのPDFを設定します。', 'washouen'),
    ));

    // 福中店テイクアウトPDF（メディアセレクター）
    $wp_customize->add_setting('fukunaka_takeout_pdf', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    if (class_exists('WP_Customize_Media_Control')) {
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'fukunaka_takeout_pdf', array(
            'label'       => __('福中店 テイクアウトメニューPDF', 'washouen'),
            'description' => __('メディアライブラリからPDFを選択してください', 'washouen'),
            'section'     => 'takeout_settings',
            'mime_type'   => 'application/pdf',
        )));
    }

    // 塩町店テイクアウトPDF（メディアセレクター）
    $wp_customize->add_setting('shiomachi_takeout_pdf', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    if (class_exists('WP_Customize_Media_Control')) {
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'shiomachi_takeout_pdf', array(
            'label'       => __('塩町店 テイクアウトメニューPDF', 'washouen'),
            'description' => __('メディアライブラリからPDFを選択してください', 'washouen'),
            'section'     => 'takeout_settings',
            'mime_type'   => 'application/pdf',
        )));
    }

    // 道案内ページ設定セクション
    $wp_customize->add_section('access_page_settings', array(
        'title'       => __('道案内ページ設定', 'washouen'),
        'priority'    => 32,
        'description' => __('道案内ページに表示する店舗外観写真を設定します。', 'washouen'),
    ));

    // 福中店外観写真
    $wp_customize->add_setting('fukunaka_exterior_image', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    if (class_exists('WP_Customize_Media_Control')) {
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'fukunaka_exterior_image', array(
            'label'       => __('福中店 外観写真', 'washouen'),
            'description' => __('推奨サイズ: 800×600', 'washouen'),
            'section'     => 'access_page_settings',
            'mime_type'   => 'image',
        )));
    }

    // 塩町店外観写真
    $wp_customize->add_setting('shiomachi_exterior_image', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    if (class_exists('WP_Customize_Media_Control')) {
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'shiomachi_exterior_image', array(
            'label'       => __('塩町店 外観写真', 'washouen'),
            'description' => __('推奨サイズ: 800×600', 'washouen'),
            'section'     => 'access_page_settings',
            'mime_type'   => 'image',
        )));
    }

    // ヘッダー設定
    $wp_customize->add_section('header_settings', array(
        'title'       => __('ヘッダー設定', 'washouen'),
        'priority'    => 27,
        'description' => __('ヘッダーの表示設定', 'washouen'),
    ));

    // ヘッダー表示オンオフ
    $wp_customize->add_setting('header_display', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    $wp_customize->add_control('header_display', array(
        'label'       => __('ヘッダーを表示', 'washouen'),
        'description' => __('チェックを外すとヘッダー全体が非表示になります（ハンバーガーメニューは常に表示されます）', 'washouen'),
        'section'     => 'header_settings',
        'type'        => 'checkbox',
    ));

    // ダークモード用ロゴ
    $wp_customize->add_setting('dark_mode_logo', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    if (class_exists('WP_Customize_Media_Control')) {
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'dark_mode_logo', array(
            'label'       => __('ダークモード用ロゴ', 'washouen'),
            'description' => __('ダークモード時に表示するロゴ画像を選択してください。設定しない場合はデフォルトロゴが使用されます。', 'washouen'),
            'section'     => 'title_tagline',
            'mime_type'   => 'image',
            'priority'    => 9,
        )));
    }

    // 福中店お品書き設定
    $wp_customize->add_section('fukunaka_menu_settings', array(
        'title'       => __('福中店お品書き設定', 'washouen'),
        'priority'    => 28,
        'description' => __('福中店お品書きページの背景画像とギャラリースライダーの設定', 'washouen'),
    ));

    // ヘッダー背景画像
    $wp_customize->add_setting('fukunaka_menu_bg_image', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    if (class_exists('WP_Customize_Media_Control')) {
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'fukunaka_menu_bg_image', array(
            'label'       => __('ヘッダー背景画像', 'washouen'),
            'description' => __('「福中店 お品書き」ヘッダー部分の背景画像（推奨: 1920×800）', 'washouen'),
            'section'     => 'fukunaka_menu_settings',
            'mime_type'   => 'image',
        )));
    }

    // スライダー切替時間
    $wp_customize->add_setting('fukunaka_menu_slider_interval', array(
        'default'           => 4.0,
        'sanitize_callback' => 'washouen_sanitize_float',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    $wp_customize->add_control('fukunaka_menu_slider_interval', array(
        'label'       => __('スライダー切替時間（秒）', 'washouen'),
        'description' => __('画像が自動で切り替わる秒数（1.0〜10.0）', 'washouen'),
        'section'     => 'fukunaka_menu_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1.0,
            'max'  => 10.0,
            'step' => 0.1,
        ),
    ));

    // ギャラリー画像（最大6枚）
    for ($i = 1; $i <= 6; $i++) {
        $setting_id = 'fukunaka_menu_gallery_' . $i;
        $wp_customize->add_setting($setting_id, array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
            'type'              => 'theme_mod',
        ));
        if (class_exists('WP_Customize_Media_Control')) {
            $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, $setting_id, array(
                'label'       => sprintf(__('料理画像 %d', 'washouen'), $i),
                'description' => __('推奨サイズ: 1920×800（横長）', 'washouen'),
                'section'     => 'fukunaka_menu_settings',
                'mime_type'   => 'image',
            )));
        }
    }

    // 塩町店お品書き設定
    $wp_customize->add_section('shiomachi_menu_settings', array(
        'title'       => __('塩町店お品書き設定', 'washouen'),
        'priority'    => 29,
        'description' => __('塩町店お品書きページの背景画像とギャラリースライダーの設定', 'washouen'),
    ));

    // ヘッダー背景画像
    $wp_customize->add_setting('shiomachi_menu_bg_image', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    if (class_exists('WP_Customize_Media_Control')) {
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'shiomachi_menu_bg_image', array(
            'label'       => __('ヘッダー背景画像', 'washouen'),
            'description' => __('「塩町店 お品書き」ヘッダー部分の背景画像（推奨: 1920×800）', 'washouen'),
            'section'     => 'shiomachi_menu_settings',
            'mime_type'   => 'image',
        )));
    }

    // スライダー切替時間
    $wp_customize->add_setting('shiomachi_menu_slider_interval', array(
        'default'           => 4.0,
        'sanitize_callback' => 'washouen_sanitize_float',
        'transport'         => 'refresh',
        'type'              => 'theme_mod',
    ));
    $wp_customize->add_control('shiomachi_menu_slider_interval', array(
        'label'       => __('スライダー切替時間（秒）', 'washouen'),
        'description' => __('画像が自動で切り替わる秒数（1.0〜10.0）', 'washouen'),
        'section'     => 'shiomachi_menu_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1.0,
            'max'  => 10.0,
            'step' => 0.1,
        ),
    ));

    // ギャラリー画像（最大6枚）
    for ($i = 1; $i <= 6; $i++) {
        $setting_id = 'shiomachi_menu_gallery_' . $i;
        $wp_customize->add_setting($setting_id, array(
            'default'           => 0,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
            'type'              => 'theme_mod',
        ));
        if (class_exists('WP_Customize_Media_Control')) {
            $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, $setting_id, array(
                'label'       => sprintf(__('料理画像 %d', 'washouen'), $i),
                'description' => __('推奨サイズ: 1920×800（横長）', 'washouen'),
                'section'     => 'shiomachi_menu_settings',
                'mime_type'   => 'image',
            )));
        }
    }
}
add_action('customize_register', 'washouen_customize_register');

// ページテンプレートの選択肢を追加
function washouen_page_templates($templates) {
    $templates['page-first-visit.php'] = 'ご挨拶';
    $templates['page-fukunaka-menu.php'] = '福中店 お品書き';
    $templates['page-shiomachi-menu.php'] = '塩町店 お品書き';
    $templates['page-access.php'] = 'アクセス';
    return $templates;
}
add_filter('theme_page_templates', 'washouen_page_templates');

// 管理画面のスタイル
function washouen_admin_style() {
    echo '<style>
        .dashicons-food:before {
            content: "\f306";
        }
    </style>';
}
add_action('admin_head', 'washouen_admin_style');

/**
 * Extract a safe Google Maps embed src from Customizer value.
 * - Accepts either a full <iframe ...>...</iframe> string or a plain URL.
 * - Only allows hosts: www.google.com, maps.google.com and path starting with /maps/embed
 * - Forces https scheme.
 *
 * @param string $raw Input value from Customizer.
 * @return string Sanitized embed src URL or empty string on failure.
 */
function washouen_get_map_embed_src($raw) {
    $raw = is_string($raw) ? trim($raw) : '';
    if ($raw === '') return '';

    // If full iframe is provided, extract src
    if (stripos($raw, '<iframe') !== false) {
        if (preg_match('/<iframe[^>]*\s+src\s*=\s*["\']([^"\']+)["\']/i', $raw, $m)) {
            $raw = $m[1];
        } else {
            return '';
        }
    }

    // Basic URL cleanup
    $url = esc_url_raw($raw);
    if (!$url) return '';

    $parts = wp_parse_url($url);
    if (!$parts || empty($parts['host']) || empty($parts['path'])) return '';

    $host = strtolower($parts['host']);
    $path = $parts['path'];
    $allowed_hosts = array('www.google.com', 'maps.google.com');
    if (!in_array($host, $allowed_hosts, true)) return '';
    if (strpos($path, '/maps/embed') !== 0) return '';

    // Ensure https
    $scheme = 'https';
    $query  = isset($parts['query']) && $parts['query'] !== '' ? ('?' . $parts['query']) : '';
    $final  = $scheme . '://' . $host . $path . $query;

    return esc_url($final);
}

// ==========================================
// LP固定ページ用メタボックス
// ==========================================

/**
 * LP固定ページ用メタボックスを追加
 */
function washouen_add_lp_meta_boxes() {
    add_meta_box(
        'washouen_lp_details',
        'ご利用案内LP設定',
        'washouen_lp_meta_box_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'washouen_add_lp_meta_boxes');

function washouen_lp_meta_box_callback($post) {
    $template = get_post_meta($post->ID, '_wp_page_template', true);
    if ($template !== 'page-lp.php') {
        echo '<p>このメタボックスは「ご利用案内LP」テンプレート選択時のみ有効です。</p>';
        return;
    }

    wp_nonce_field('washouen_save_lp_meta', 'washouen_lp_nonce');

    $lp_guide_order         = get_post_meta($post->ID, 'lp_guide_order', true);
    $lp_target_store        = get_post_meta($post->ID, 'lp_target_store', true);
    $lp_icon                = get_post_meta($post->ID, 'lp_icon', true);
    $lp_lead                = get_post_meta($post->ID, 'lp_lead', true);
    $lp_primary_cta_label   = get_post_meta($post->ID, 'lp_primary_cta_label', true);
    $lp_primary_cta_url     = get_post_meta($post->ID, 'lp_primary_cta_url', true);
    $lp_secondary_cta_label = get_post_meta($post->ID, 'lp_secondary_cta_label', true);
    $lp_secondary_cta_url   = get_post_meta($post->ID, 'lp_secondary_cta_url', true);
    $lp_points              = get_post_meta($post->ID, 'lp_points', true);
    ?>
    <style>
        .lp-meta-field { margin-bottom: 20px; }
        .lp-meta-field label { display: block; font-weight: bold; margin-bottom: 5px; }
        .lp-meta-field input[type="text"],
        .lp-meta-field input[type="number"],
        .lp-meta-field input[type="url"],
        .lp-meta-field textarea { width: 100%; max-width: 500px; }
        .lp-meta-field textarea { height: 100px; }
        .lp-meta-help { color: #666; font-style: italic; font-size: 13px; margin-top: 5px; }
    </style>

    <div class="lp-meta-field">
        <label for="lp_guide_order">表示順序</label>
        <input type="number" id="lp_guide_order" name="lp_guide_order" value="<?php echo esc_attr($lp_guide_order); ?>" min="0" step="1" style="max-width: 100px;">
        <p class="lp-meta-help">ご利用案内ページでのカード表示順（小さい数字ほど上に表示）</p>
    </div>

    <div class="lp-meta-field">
        <label for="lp_target_store">対象店舗</label>
        <select id="lp_target_store" name="lp_target_store" style="max-width: 200px;">
            <option value="both" <?php selected($lp_target_store, 'both'); ?>>両店共通</option>
            <option value="fukunaka" <?php selected($lp_target_store, 'fukunaka'); ?>>福中店</option>
            <option value="shiomachi" <?php selected($lp_target_store, 'shiomachi'); ?>>塩町店</option>
        </select>
        <p class="lp-meta-help">CTA未設定時のデフォルト電話番号に影響します</p>
    </div>

    <div class="lp-meta-field">
        <label for="lp_icon">アイコン（Font Awesome クラス）</label>
        <input type="text" id="lp_icon" name="lp_icon" value="<?php echo esc_attr($lp_icon); ?>" placeholder="fas fa-utensils">
        <p class="lp-meta-help">アイキャッチ画像未設定時に表示するアイコン。例: fas fa-fish, fas fa-wine-glass</p>
    </div>

    <div class="lp-meta-field">
        <label for="lp_lead">リード文</label>
        <textarea id="lp_lead" name="lp_lead"><?php echo esc_textarea($lp_lead); ?></textarea>
    </div>

    <div class="lp-meta-field">
        <label for="lp_primary_cta_label">メインボタン テキスト</label>
        <input type="text" id="lp_primary_cta_label" name="lp_primary_cta_label" value="<?php echo esc_attr($lp_primary_cta_label); ?>" placeholder="例: 当店への道案内を見る">
        <p class="lp-meta-help">空欄の場合はメインボタンを非表示にします</p>
    </div>

    <div class="lp-meta-field">
        <label for="lp_primary_cta_url">メインボタン URL</label>
        <input type="url" id="lp_primary_cta_url" name="lp_primary_cta_url" value="<?php echo esc_attr($lp_primary_cta_url); ?>">
    </div>

    <div class="lp-meta-field">
        <label for="lp_secondary_cta_label">サブボタン テキスト</label>
        <input type="text" id="lp_secondary_cta_label" name="lp_secondary_cta_label" value="<?php echo esc_attr($lp_secondary_cta_label); ?>">
        <p class="lp-meta-help">空欄の場合は対象店舗の電話予約ボタンを自動表示します</p>
    </div>

    <div class="lp-meta-field">
        <label for="lp_secondary_cta_url">サブボタン URL</label>
        <input type="url" id="lp_secondary_cta_url" name="lp_secondary_cta_url" value="<?php echo esc_attr($lp_secondary_cta_url); ?>">
    </div>

    <div class="lp-meta-field">
        <label for="lp_points">ご案内ポイント（1行1項目）</label>
        <textarea id="lp_points" name="lp_points"><?php echo esc_textarea($lp_points); ?></textarea>
        <p class="lp-meta-help">改行区切りで入力。LPページ内の「ご案内ポイント」セクションに表示されます。</p>
    </div>
    <?php
}

/**
 * LP固定ページメタデータを保存
 */
function washouen_save_lp_meta($post_id) {
    if (!isset($_POST['washouen_lp_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['washouen_lp_nonce'], 'washouen_save_lp_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $text_fields = array(
        'lp_guide_order',
        'lp_target_store',
        'lp_icon',
        'lp_lead',
        'lp_primary_cta_label',
        'lp_secondary_cta_label',
    );
    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }

    $url_fields = array('lp_primary_cta_url', 'lp_secondary_cta_url');
    foreach ($url_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, esc_url_raw($_POST[$field]));
        }
    }

    if (isset($_POST['lp_points'])) {
        update_post_meta($post_id, 'lp_points', sanitize_textarea_field($_POST['lp_points']));
    }
}
add_action('save_post_page', 'washouen_save_lp_meta');
