<?php
/**
 * Template Name: 塩町店 お品書き
 * Description: Shiomachi store menu page template
 *
 * @package Washouen
 */

get_header(); ?>

<main class="main-content shiomachi-menu-page">
    <!-- ページヘッダー -->
    <?php
    // ヘッダー背景画像の取得
    $header_bg_id = get_theme_mod('shiomachi_menu_bg_image', 0);
    $header_bg_url = $header_bg_id ? wp_get_attachment_image_url($header_bg_id, 'full') : '';
    $header_bg_style = $header_bg_url ? ' style="background-image: url(' . esc_url($header_bg_url) . ');"' : '';
    ?>
    <section class="page-header shiomachi-menu-header"<?php echo $header_bg_style; ?>>
        <div class="page-header-overlay"></div>
        <div class="page-header-content">
            <h1 class="page-title">塩町店 お品書き</h1>
            <p class="page-subtitle">SHIOMACHI MENU</p>
        </div>
    </section>

    <!-- メインメッセージ -->
    <section class="welcome-message section">
        <div class="container">
            <div class="welcome-content">
                <h2 class="welcome-title">家島育ちの店主が紡ぐ、瀬戸内・島の鮨</h2>
                <div class="welcome-text">
                    <p>
                        瀬戸内海の島・家島で育った店主が、島の恵みと熟練の技を大切に、一貫一貫、心を込めて鮨を握っております。
                    </p>
                    <p>
                        気取らず、しかし本格的。そんな“島の鮨”の魅力を、塩町にてゆっくりとご堪能ください。
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
        $image_id = get_theme_mod('shiomachi_menu_gallery_' . $i, 0);
        if ($image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'full');
            $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
            if ($image_url) {
                $gallery_images[] = array(
                    'url' => $image_url,
                    'alt' => $image_alt ? $image_alt : '塩町店の料理'
                );
            }
        }
    }

    if (!empty($gallery_images)) :
        $slider_interval = get_theme_mod('shiomachi_menu_slider_interval', 4.0);
    ?>
        <section class="menu-gallery-slider shiomachi-slider">
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
                if (typeof window.shiomachiSliderSettings === 'undefined') {
                    window.shiomachiSliderSettings = {};
                }
                window.shiomachiSliderSettings.interval = <?php echo floatval($slider_interval) * 1000; ?>;
            </script>
        </section>
    <?php endif; ?>

    <?php
    // タクソノミーからカテゴリーを動的に取得
    $terms = get_terms(array(
        'taxonomy' => 'shiomachi_category',
        'hide_empty' => false,
    ));

    // デフォルトカテゴリーのメタ情報（アイコンと説明）
    $default_category_meta = array(
        'nigiri' => array(
            'icon' => '🍣',
            'description' => '職人が丁寧に握る逸品'
        ),
        'gunkan' => array(
            'icon' => '🍱',
            'description' => '海苔の香りと共に'
        ),
        'chirashi' => array(
            'icon' => '🍜',
            'description' => '彩り豊かな海の幸'
        ),
        'omakase' => array(
            'icon' => '⭐',
            'description' => '季節の味覚を堪能'
        ),
        'side' => array(
            'icon' => '🥢',
            'description' => '鮨と楽しむ逸品'
        ),
        'drink' => array(
            'icon' => '🍶',
            'description' => '鮨と楽しむ厳選された日本酒'
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
        $args = array(
            'post_type' => 'shiomachi_menu',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'shiomachi_category',
                    'field' => 'slug',
                    'terms' => $category_slug,
                ),
            ),
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );
        $menu_items = new WP_Query($args);

        if ($menu_items->have_posts()) : ?>
            <section class="menu-category sushi-category" id="<?php echo esc_attr($category_slug); ?>">
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
                                        <?php the_post_thumbnail('medium', array('loading' => 'lazy', 'alt' => get_the_title())); ?>
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
                                    <div class="menu-card-meta">
                                        <?php 
                                        $origin = get_post_meta(get_the_ID(), '_menu_origin', true);
                                        if ($origin) : ?>
                                            <span class="menu-badge origin"><?php echo esc_html($origin); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php 
                                    $description = get_post_meta(get_the_ID(), '_menu_description', true);
                                    if ($description) : ?>
                                        <p class="menu-card-description"><?php echo esc_html($description); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        <?php endif;
        wp_reset_postdata();
    endforeach; ?>

    <section class="menu-notice">
        <div class="container">
            <div class="notice-content">
                <h3>ご案内</h3>
                <ul>
                    <li>仕入れ状況により、ネタが変更になる場合がございます</li>
                    <li>アレルギーをお持ちの方は、事前にお申し付けください</li>
                    <li>カウンター席は予約制となっております</li>
                </ul>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
