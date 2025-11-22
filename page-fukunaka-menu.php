<?php
/**
 * Template Name: 福中店 お品書き
 * Description: Fukunaka store menu page template
 *
 * @package Washouen
 */

get_header(); ?>

<main class="main-content fukunaka-menu-page">
    <!-- ページヘッダー -->
    <?php
    // ヘッダー背景画像の取得
    $header_bg_id = get_theme_mod('fukunaka_menu_bg_image', 0);
    $header_bg_url = $header_bg_id ? wp_get_attachment_image_url($header_bg_id, 'full') : '';
    $header_bg_style = $header_bg_url ? ' style="background-image: url(' . esc_url($header_bg_url) . ');"' : '';
    ?>
    <section class="page-header fukunaka-menu-header"<?php echo $header_bg_style; ?>>
        <div class="page-header-overlay"></div>
        <div class="page-header-content">
            <h1 class="page-title">福中店 お品書き</h1>
            <p class="page-subtitle">FUKUNAKA MENU</p>
        </div>
    </section>

    <!-- メインメッセージ -->
    <section class="welcome-message section">
        <div class="container">
            <div class="welcome-content">
                <h2 class="welcome-title">瀬戸内の恵みを、多彩な調理法で</h2>
                <div class="welcome-text">
                    <p>
                        水槽から直前に出した新鮮な活魚を、お客様のご要望に合わせて調理いたします。<br>
                        お造り、焼き物、煮付け、唐揚げなど、素材の持ち味を最大限に活かした料理をお楽しみください。
                    </p>
                    <p>
                        福中店では<br>
                        「生・焼・煮・揚・蒸・にぎり」の6つの調理法で、<br>
                        魚の本来の旨みを様々な形でご堪能いただけます。
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 料理画像ギャラリースライダー -->
    <?php
    // ギャラリー画像を取得
    $gallery_images = array();
    for ($i = 1; $i <= 6; $i++) {
        $image_id = get_theme_mod('fukunaka_menu_gallery_' . $i, 0);
        if ($image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'full');
            $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
            if ($image_url) {
                $gallery_images[] = array(
                    'url' => $image_url,
                    'alt' => $image_alt ? $image_alt : '福中店の料理'
                );
            }
        }
    }

    if (!empty($gallery_images)) :
        $slider_interval = get_theme_mod('fukunaka_menu_slider_interval', 4.0);
    ?>
        <section class="menu-gallery-slider">
            <div class="slider-container">
                <?php foreach ($gallery_images as $index => $image) : ?>
                    <div class="slider-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo esc_url($image['url']); ?>"
                             alt="<?php echo esc_attr($image['alt']); ?>"
                             loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>">
                    </div>
                <?php endforeach; ?>

                <?php if (count($gallery_images) > 1) : ?>
                    <div class="slider-dots">
                        <?php foreach ($gallery_images as $index => $image) : ?>
                            <button class="slider-dot <?php echo $index === 0 ? 'active' : ''; ?>"
                                    data-slide="<?php echo $index; ?>"
                                    aria-label="画像<?php echo $index + 1; ?>へ"></button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <script>
                // スライダーの初期化（インライン設定）
                if (typeof window.menuSliderSettings === 'undefined') {
                    window.menuSliderSettings = {};
                }
                window.menuSliderSettings.interval = <?php echo floatval($slider_interval) * 1000; ?>;
            </script>
        </section>
    <?php endif; ?>

    <?php
    // タクソノミーからカテゴリーを動的に取得
    $terms = get_terms(array(
        'taxonomy' => 'fukunaka_category',
        'hide_empty' => false,
    ));

    // デフォルトカテゴリーのメタ情報（アイコンと説明）
    $default_category_meta = array(
        'course' => array(
            'icon' => '🍱',
            'description' => '旬の食材を活かした本格会席'
        ),
        'sashimi' => array(
            'icon' => '🐟',
            'description' => '新鮮な魚介を職人の技で'
        ),
        'grilled' => array(
            'icon' => '🔥',
            'description' => '素材の旨みを凝縮'
        ),
        'simmered' => array(
            'icon' => '🍲',
            'description' => '出汁の効いた優しい味わい'
        ),
        'fried' => array(
            'icon' => '🍤',
            'description' => 'サクッと香ばしく'
        ),
        'special' => array(
            'icon' => '🌸',
            'description' => '旬の味覚をお楽しみください'
        ),
        'drink' => array(
            'icon' => '🍶',
            'description' => '料理に合う厳選されたお飲み物'
        )
    );

    // タクソノミーからカテゴリー情報を構築
    $menu_categories = array();
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            // 管理画面で設定した説明文を優先的に使用、なければデフォルトを使用
            $custom_description = !empty($term->description) ? $term->description : '';
            $default_description = isset($default_category_meta[$term->slug]['description']) ? $default_category_meta[$term->slug]['description'] : '';

            $menu_categories[$term->slug] = array(
                'title' => $term->name,
                'description' => !empty($custom_description) ? $custom_description : $default_description,
                'icon' => isset($default_category_meta[$term->slug]['icon']) ? $default_category_meta[$term->slug]['icon'] : '📋'
            );
        }
    }

    foreach ($menu_categories as $category_slug => $category_info) :
        // すべてのカテゴリーを表示
        $is_default_hidden = false;

        $args = array(
            'post_type' => 'fukunaka_menu',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'fukunaka_category',
                    'field' => 'slug',
                    'terms' => $category_slug,
                ),
            ),
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );
        $menu_items = new WP_Query($args);

        if ($menu_items->have_posts()) : ?>
            <section class="menu-category<?php echo $is_default_hidden ? ' menu-category-hidden' : ''; ?>" id="<?php echo esc_attr($category_slug); ?>">
                <div class="container">
                    <div class="category-header">
                        <span class="category-icon"><?php echo $category_info['icon']; ?></span>
                        <h2 class="category-title"><?php echo esc_html($category_info['title']); ?></h2>
                        <p class="category-description"><?php echo esc_html($category_info['description']); ?></p>
                    </div>

                    <div class="menu-grid">
                        <?php while ($menu_items->have_posts()) : $menu_items->the_post(); ?>
                            <div class="menu-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="menu-card-image">
                                        <?php the_post_thumbnail('large', array('loading' => 'lazy', 'alt' => get_the_title())); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="menu-card-content">
                                    <div class="menu-item-header">
                                    <h3 class="menu-card-title"><?php the_title(); ?></h3>
                                        <?php
                                        $price = get_post_meta(get_the_ID(), '_menu_price', true);
                                        if ($price) : ?>
                                            <span class="menu-leader" aria-hidden="true"></span>
                                            <span class="menu-item-price"><?php echo ($price === '時価') ? $price : '¥' . esc_html($price); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php
                                    $description = get_post_meta(get_the_ID(), '_menu_description', true);
                                    if ($description) : ?>
                                        <p class="menu-card-description"><?php echo esc_html($description); ?></p>
                                    <?php endif; ?>
                                    <div class="menu-card-meta">
                                        <?php
                                        $is_seasonal = get_post_meta(get_the_ID(), '_menu_is_seasonal', true);
                                        if ($is_seasonal == '1') : ?>
                                            <span class="menu-badge seasonal">季節限定</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        <?php endif;
        wp_reset_postdata();
    endforeach; ?>

    <!-- Static menu items as fallback or examples -->
    <?php /*
    <section class="menu-category" id="static-menu">
        <div class="container">
            <div class="category-header">
                <h2 class="category-title">本日のおすすめ</h2>
                <p class="category-description">市場直送の新鮮な魚介類</p>
            </div>

            <div class="menu-grid">
                <div class="menu-item">
                    <div class="menu-item-content">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">瀬戸内産 天然鯛</h3>
                            <span class="menu-leader" aria-hidden="true"></span>
                            <span class="menu-item-price">時価</span>
                        </div>
                        <p class="menu-item-description">お造り、焼き物、煮付けからお選びいただけます</p>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="menu-item-content">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">活きアワビ</h3>
                            <span class="menu-leader" aria-hidden="true"></span>
                            <span class="menu-item-price">時価</span>
                        </div>
                        <p class="menu-item-description">踊り焼き、またはお造りで</p>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="menu-item-content">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">天然ヒラメ</h3>
                            <span class="menu-leader" aria-hidden="true"></span>
                            <span class="menu-item-price">時価</span>
                        </div>
                        <p class="menu-item-description">薄造り、または昆布締め</p>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="menu-item-content">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">車海老</h3>
                            <span class="menu-leader" aria-hidden="true"></span>
                            <span class="menu-item-price">時価</span>
                        </div>
                        <p class="menu-item-description">活き造り、塩焼き、天ぷら</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="menu-category">
        <div class="container">
            <div class="category-header">
                <h2 class="category-title">お飲み物</h2>
                <p class="category-description">料理に合う厳選された日本酒・焼酎</p>
            </div>

            <div class="menu-grid">
                <div class="menu-item">
                    <div class="menu-item-content">
                        <h3 class="menu-item-name">地酒各種</h3>
                        <p class="menu-item-description">兵庫の地酒を中心に取り揃えております</p>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="menu-item-content">
                        <h3 class="menu-item-name">プレミアム焼酎</h3>
                        <p class="menu-item-description">芋、麦、米焼酎各種</p>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="menu-item-content">
                        <h3 class="menu-item-name">ビール・ソフトドリンク</h3>
                        <p class="menu-item-description">各種ご用意しております</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    */ ?>

    <section class="menu-notice">
        <div class="container">
            <div class="notice-content">
                <h3>ご案内</h3>
                <ul>
                    <li>仕入れ状況により、メニュー内容が変更になる場合がございます</li>
                    <li>アレルギーをお持ちの方は、事前にスタッフまでお申し付けください</li>
                    <li>コース料理も承っております。詳しくはお問い合わせください</li>
                </ul>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
