<?php
/**
 * Template Name: 福中店メニュー
 * Description: Fukunaka store menu page template
 * 
 * @package Washouen
 */

get_header(); ?>

<main class="main-content">
    <section class="page-hero fukunaka-hero">
        <div class="container">
            <h1 class="page-title">福中店 お品書き</h1>
            <p class="page-subtitle">活魚・一品料理</p>
        </div>
    </section>

    <section class="menu-introduction">
        <div class="container">
            <div class="intro-content">
                <h2>瀬戸内の恵みを、多彩な調理法で</h2>
                <p class="lead-text">
                    毎朝市場から仕入れる新鮮な活魚を、お客様のご要望に合わせて調理いたします。<br>
                    お造り、焼き物、煮付け、唐揚げなど、素材の持ち味を最大限に活かした料理をお楽しみください。
                </p>
            </div>
        </div>
    </section>

    <?php
    // Get custom post type menu items
    $menu_categories = array(
        'sashimi' => array(
            'title' => 'お造り',
            'description' => '新鮮な魚介を職人の技で',
            'icon' => '🐟'
        ),
        'grilled' => array(
            'title' => '焼き物',
            'description' => '素材の旨みを凝縮',
            'icon' => '🔥'
        ),
        'simmered' => array(
            'title' => '煮付け',
            'description' => '出汁の効いた優しい味わい',
            'icon' => '🍲'
        ),
        'fried' => array(
            'title' => '揚げ物',
            'description' => 'サクッと香ばしく',
            'icon' => '🍤'
        ),
        'special' => array(
            'title' => '季節の特選料理',
            'description' => '旬の味覚をお楽しみください',
            'icon' => '🌸'
        )
    );

    foreach ($menu_categories as $category_slug => $category_info) :
        $args = array(
            'post_type' => 'fukunaka_menu',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'fukunaka_menu_category',
                    'field' => 'slug',
                    'terms' => $category_slug,
                ),
            ),
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );
        $menu_items = new WP_Query($args);

        if ($menu_items->have_posts()) : ?>
            <section class="menu-category" id="<?php echo esc_attr($category_slug); ?>">
                <div class="container">
                    <div class="category-header">
                        <span class="category-icon"><?php echo $category_info['icon']; ?></span>
                        <h2 class="category-title"><?php echo esc_html($category_info['title']); ?></h2>
                        <p class="category-description"><?php echo esc_html($category_info['description']); ?></p>
                    </div>

                    <div class="menu-grid">
                        <?php while ($menu_items->have_posts()) : $menu_items->the_post(); ?>
                            <div class="menu-item">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="menu-item-image">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="menu-item-content">
                                    <div class="menu-item-header">
                                        <h3 class="menu-item-name"><?php the_title(); ?></h3>
                                        <?php if (get_field('price')) : ?>
                                            <span class="menu-leader" aria-hidden="true"></span>
                                            <span class="menu-item-price">¥<?php the_field('price'); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="menu-item-meta">
                                        <?php if (get_field('is_seasonal') && get_field('is_seasonal') == true) : ?>
                                            <span class="menu-badge seasonal">季節限定</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (get_field('description')) : ?>
                                        <p class="menu-item-description"><?php the_field('description'); ?></p>
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

    <!-- Static menu items as fallback or examples -->
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

    <section class="reservation-cta">
        <div class="container">
            <h2>ご予約・お問い合わせ</h2>
            <p>お電話でのご予約を承っております</p>
            <div class="contact-info">
                <p class="phone-number">
                    <i class="fas fa-phone"></i>
                    <?php echo get_theme_mod('fukunaka_phone', '086-XXX-XXXX'); ?>
                </p>
                <p class="business-hours">
                    営業時間：<?php echo get_theme_mod('fukunaka_hours', '昼 11:30～14:00 / 夜 17:00～22:00'); ?>
                </p>
            </div>
            <a href="<?php echo home_url('/access/'); ?>" class="btn btn-outline-elegant">アクセス情報を見る</a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
