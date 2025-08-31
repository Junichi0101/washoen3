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
    wp_enqueue_style('washouen-main', get_template_directory_uri() . '/css/style.css', array('washouen-theme'), '1.0.0');
    
    // レスポンシブスタイルシート
    wp_enqueue_style('washouen-responsive', get_template_directory_uri() . '/css/responsive.css', array('washouen-main'), '1.0.0');
    
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

// メニュー編集機能を読み込む
require_once get_template_directory() . '/inc/menu-editor.php';

// サンプルデータ生成機能を読み込む
require_once get_template_directory() . '/inc/menu-sample-data.php';

// インポート/エクスポート機能を読み込む
require_once get_template_directory() . '/inc/menu-import-export.php';

// カスタム投稿タイプ: メニュー
function washouen_custom_post_types() {
    // 福中店メニュー
    register_post_type('fukunaka_menu', array(
        'labels' => array(
            'name' => __('福中店メニュー', 'washouen'),
            'singular_name' => __('福中店メニュー項目', 'washouen'),
            'add_new' => __('新規追加', 'washouen'),
            'add_new_item' => __('新規メニュー項目を追加', 'washouen'),
            'edit_item' => __('メニュー項目を編集', 'washouen'),
            'featured_image' => __('メニュー画像', 'washouen'),
            'set_featured_image' => __('メニュー画像を設定', 'washouen'),
            'remove_featured_image' => __('メニュー画像を削除', 'washouen'),
            'use_featured_image' => __('メニュー画像として使用', 'washouen'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'thumbnail', 'page-attributes'),
        'menu_icon' => 'dashicons-food',
        'rewrite' => array('slug' => 'fukunaka-menu-items'),
    ));
    
    // 塩町店メニュー
    register_post_type('shiomachi_menu', array(
        'labels' => array(
            'name' => __('塩町店メニュー', 'washouen'),
            'singular_name' => __('塩町店メニュー項目', 'washouen'),
            'add_new' => __('新規追加', 'washouen'),
            'add_new_item' => __('新規メニュー項目を追加', 'washouen'),
            'edit_item' => __('メニュー項目を編集', 'washouen'),
            'featured_image' => __('メニュー画像', 'washouen'),
            'set_featured_image' => __('メニュー画像を設定', 'washouen'),
            'remove_featured_image' => __('メニュー画像を削除', 'washouen'),
            'use_featured_image' => __('メニュー画像として使用', 'washouen'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'thumbnail', 'page-attributes'),
        'menu_icon' => 'dashicons-food',
        'rewrite' => array('slug' => 'shiomachi-menu-items'),
    ));
}
add_action('init', 'washouen_custom_post_types');

// カスタムタクソノミー: メニューカテゴリー
function washouen_custom_taxonomies() {
    // 福中店メニューカテゴリー
    register_taxonomy('fukunaka_category', 'fukunaka_menu', array(
        'labels' => array(
            'name' => __('メニューカテゴリー', 'washouen'),
            'singular_name' => __('カテゴリー', 'washouen'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'fukunaka-category'),
    ));
    
    // 塩町店メニューカテゴリー
    register_taxonomy('shiomachi_category', 'shiomachi_menu', array(
        'labels' => array(
            'name' => __('メニューカテゴリー', 'washouen'),
            'singular_name' => __('カテゴリー', 'washouen'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'shiomachi-category'),
    ));
}
add_action('init', 'washouen_custom_taxonomies');

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

// カスタマイザー設定
function washouen_customize_register($wp_customize) {
    // ホーム設定セクション
    $wp_customize->add_section('home_settings', array(
        'title'    => __('ホーム設定', 'washouen'),
        'priority' => 25,
        'description' => __('ホームに表示する画像を設定します。', 'washouen'),
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
    // 店舗情報セクション
    $wp_customize->add_section('washouen_store_info', array(
        'title' => __('店舗情報', 'washouen'),
        'priority' => 30,
    ));
    
    // 福中店設定
    // 電話番号
    $wp_customize->add_setting('fukunaka_phone', array(
        'default' => '086-XXX-XXXX',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('fukunaka_phone', array(
        'label' => __('福中店電話番号', 'washouen'),
        'section' => 'washouen_store_info',
        'type' => 'text',
    ));
    
    // 住所
    $wp_customize->add_setting('fukunaka_address', array(
        'default' => '〒670-0042 兵庫県姫路市米田町15-1 船場東ビル1F',
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
}
add_action('customize_register', 'washouen_customize_register');

// ページテンプレートの選択肢を追加
function washouen_page_templates($templates) {
    $templates['page-first-visit.php'] = '初めての方へ';
    $templates['page-fukunaka-menu.php'] = '福中店メニュー';
    $templates['page-shiomachi-menu.php'] = '塩町店メニュー';
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
