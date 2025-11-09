<?php
/**
 * Template Name: 塩町店 お品書き
 * Description: Shiomachi store menu page template
 *
 * @package Washouen
 */

get_header(); ?>

<main class="main-content">
    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">塩町店 お品書き</h1>
            <p class="page-subtitle">SHIOMACHI MENU</p>
        </div>
    </section>

    <!-- メインメッセージ -->
    <section class="welcome-message section">
        <div class="container">
            <div class="welcome-content">
                <h2 class="welcome-title">伝統の技が光る、本格江戸前鮨</h2>
                <div class="welcome-text">
                    <p>
                        厳選された旬の魚介を、熟練の職人が一貫一貫丁寧に握ります。<br>
                        赤酢を使った伝統のシャリと、こだわりの海苔が織りなす至福の味わいをお楽しみください。
                    </p>
                    <p>
                        塩町店では、築地・豊洲市場から毎日仕入れる新鮮な魚介を使用し、<br>
                        職人の確かな技術で美味しい鮨をご提供いたします。
                    </p>
                </div>
            </div>
        </div>
    </section>

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

    <section class="sushi-philosophy">
        <div class="container">
            <h2>鮨へのこだわり</h2>
            <div class="philosophy-grid">
                <div class="philosophy-item">
                    <h3>シャリ</h3>
                    <p>赤酢を使用した伝統の味。人肌の温度で提供</p>
                </div>
                <div class="philosophy-item">
                    <h3>ネタ</h3>
                    <p>築地・豊洲市場から毎日仕入れる新鮮な魚介</p>
                </div>
                <div class="philosophy-item">
                    <h3>海苔</h3>
                    <p>有明海産の一番摘み海苔を使用</p>
                </div>
                <div class="philosophy-item">
                    <h3>山葵</h3>
                    <p>静岡産の本山葵をその都度おろして提供</p>
                </div>
            </div>
        </div>
    </section>

    <section class="menu-notice">
        <div class="container">
            <div class="notice-content">
                <h3>ご案内</h3>
                <ul>
                    <li>仕入れ状況により、ネタが変更になる場合がございます</li>
                    <li>アレルギーをお持ちの方は、事前にお申し付けください</li>
                    <li>カウンター席は予約制となっております</li>
                    <li>お子様用の握りセットもご用意できます</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="reservation-cta section">
        <div class="container">
            <div class="cta-content">
                <h2>塩町店で、本格江戸前鮨をご堪能ください</h2>
                <p>
                    熟練の職人が握る、伝統の味わいをお楽しみいただけます。<br>
                    カウンター席では職人との会話も魅力の一つです。
                </p>
                <div class="contact-info">
                    <p class="phone-number">
                        <i class="fas fa-phone"></i>
                        <?php echo get_theme_mod('shiomachi_phone', '079-223-6879'); ?>
                    </p>
                    <p class="business-hours">
                        営業時間：<?php echo get_theme_mod('shiomachi_hours', '昼 11:30～14:00 / 夜 17:00～22:00'); ?>
                    </p>
                </div>
                <div class="cta-buttons">
                    <a href="<?php echo home_url('/access/'); ?>" class="btn btn-elegant">
                        <i class="fas fa-map-marker-alt"></i> アクセス情報を見る
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
