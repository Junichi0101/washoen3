<?php
/**
 * Template Name: 福中店メニュー
 * Description: Fukunaka store menu page template
 * 
 * @package Washouen
 */

get_header(); ?>

<main class="main-content">
    <!-- ページヘッダー -->
    <section class="page-header">
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
                        毎朝市場から仕入れる新鮮な活魚を、お客様のご要望に合わせて調理いたします。<br>
                        お造り、焼き物、煮付け、唐揚げなど、素材の持ち味を最大限に活かした料理をお楽しみください。
                    </p>
                    <p>
                        福中店では「生・焼・煮・揚・蒸・にぎり」の6つの調理法で、<br>
                        天然魚の本当の旨みを色々な形でご堪能いただけます。
                    </p>
                </div>
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
            <section class="menu-category" id="<?php echo esc_attr($category_slug); ?>">
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
                                    <h3 class="menu-card-title"><?php the_title(); ?></h3>
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

    <!-- CTA -->
    <section class="cta section">
        <div class="container">
            <div class="cta-content">
                <h2>福中店で、本格的な日本料理をお楽しみください</h2>
                <p>
                    新鮮な活魚と職人の技で、心を込めたお料理をご提供いたします。<br>
                    ご予約・お問い合わせはお気軽にどうぞ。
                </p>
                <div class="contact-info" style="background: rgba(255, 255, 255, 0.2); padding: var(--spacing-lg); border-radius: 8px; margin: var(--spacing-lg) auto; max-width: 500px;">
                    <p class="phone-number" style="font-size: 1.5rem; font-weight: 500; margin-bottom: var(--spacing-sm); display: flex; align-items: center; justify-content: center; gap: var(--spacing-sm); color: var(--bg-white);">
                        <i class="fas fa-phone"></i>
                        <?php echo get_theme_mod('fukunaka_phone', '079-222-5678'); ?>
                    </p>
                    <p class="business-hours" style="color: rgba(255, 255, 255, 0.9);">
                        営業時間：<?php echo get_theme_mod('fukunaka_hours', '昼 11:30～14:00 / 夜 17:00～22:00'); ?>
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
