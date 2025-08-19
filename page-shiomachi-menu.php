<?php
/**
 * Template Name: 塩町店メニュー
 * Description: Shiomachi store menu page template
 * 
 * @package Washouen
 */

get_header(); ?>

<main class="main-content">
    <section class="page-hero shiomachi-hero">
        <div class="container">
            <h1 class="page-title">塩町店 お品書き</h1>
            <p class="page-subtitle">鮨</p>
        </div>
    </section>

    <section class="menu-introduction">
        <div class="container">
            <div class="intro-content">
                <h2>伝統の技が光る、本格江戸前鮨</h2>
                <p class="lead-text">
                    厳選された旬の魚介を、熟練の職人が一貫一貫丁寧に握ります。<br>
                    赤酢を使った伝統のシャリと、こだわりの海苔が織りなす至福の味わいをお楽しみください。
                </p>
            </div>
        </div>
    </section>

    <?php
    // Get custom post type menu items for sushi
    $sushi_categories = array(
        'nigiri' => array(
            'title' => '握り',
            'description' => '職人が丁寧に握る逸品',
            'icon' => '🍣'
        ),
        'gunkan' => array(
            'title' => '軍艦・巻物',
            'description' => '海苔の香りと共に',
            'icon' => '🍱'
        ),
        'chirashi' => array(
            'title' => 'ちらし・丼',
            'description' => '彩り豊かな海の幸',
            'icon' => '🍜'
        ),
        'omakase' => array(
            'title' => 'おまかせコース',
            'description' => '季節の味覚を堪能',
            'icon' => '⭐'
        ),
        'side' => array(
            'title' => '一品料理',
            'description' => '鮨と楽しむ逸品',
            'icon' => '🥢'
        )
    );

    foreach ($sushi_categories as $category_slug => $category_info) :
        $args = array(
            'post_type' => 'shiomachi_menu',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'shiomachi_menu_category',
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

                    <div class="menu-grid sushi-grid">
                        <?php while ($menu_items->have_posts()) : $menu_items->the_post(); ?>
                            <div class="menu-item sushi-item">
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
                                        <?php if (get_field('origin')) : ?>
                                            <span class="menu-badge origin"><?php the_field('origin'); ?></span>
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

    <!-- Static menu items as examples -->
    <section class="menu-category sushi-category">
        <div class="container">
            <div class="category-header">
                <span class="category-icon">🍣</span>
                <h2 class="category-title">本日のおすすめ握り</h2>
                <p class="category-description">旬の素材を職人の技で</p>
            </div>

            <div class="menu-grid sushi-grid">
                <div class="menu-item sushi-item">
                    <div class="menu-item-content">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">大トロ</h3>
                            <span class="menu-leader" aria-hidden="true"></span>
                            <span class="menu-item-price">時価</span>
                        </div>
                        <p class="menu-item-description">本マグロの最上級部位</p>
                    </div>
                </div>
                <div class="menu-item sushi-item">
                    <div class="menu-item-content">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">中トロ</h3>
                            <span class="menu-leader" aria-hidden="true"></span>
                            <span class="menu-item-price">時価</span>
                        </div>
                        <p class="menu-item-description">程よい脂のりの本マグロ</p>
                    </div>
                </div>
                <div class="menu-item sushi-item">
                    <div class="menu-item-content">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">赤身</h3>
                            <span class="menu-leader" aria-hidden="true"></span>
                            <span class="menu-item-price">時価</span>
                        </div>
                        <p class="menu-item-description">マグロ本来の旨み</p>
                    </div>
                </div>
                <div class="menu-item sushi-item">
                    <div class="menu-item-content">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">コハダ</h3>
                            <span class="menu-leader" aria-hidden="true"></span>
                            <span class="menu-item-price">時価</span>
                        </div>
                        <p class="menu-item-description">江戸前の伝統、〆物の逸品</p>
                    </div>
                </div>
                <div class="menu-item sushi-item">
                    <div class="menu-item-content">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">車海老</h3>
                            <span class="menu-leader" aria-hidden="true"></span>
                            <span class="menu-item-price">時価</span>
                        </div>
                        <p class="menu-item-description">活き〆の新鮮な海老</p>
                    </div>
                </div>
                <div class="menu-item sushi-item">
                    <div class="menu-item-content">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">雲丹</h3>
                            <span class="menu-leader" aria-hidden="true"></span>
                            <span class="menu-item-price">時価</span>
                        </div>
                        <p class="menu-item-description">濃厚な甘みの北海道産</p>
                    </div>
                </div>
                <div class="menu-item sushi-item">
                    <div class="menu-item-content">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">穴子</h3>
                            <span class="menu-leader" aria-hidden="true"></span>
                            <span class="menu-item-price">時価</span>
                        </div>
                        <p class="menu-item-description">ふっくら煮上げた江戸前穴子</p>
                    </div>
                </div>
                <div class="menu-item sushi-item">
                    <div class="menu-item-content">
                        <div class="menu-item-header">
                            <h3 class="menu-item-name">いくら</h3>
                            <span class="menu-leader" aria-hidden="true"></span>
                            <span class="menu-item-price">時価</span>
                        </div>
                        <p class="menu-item-description">北海道産の醤油漬け</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="menu-category sushi-category">
        <div class="container">
            <div class="category-header">
                <span class="category-icon">⭐</span>
                <h2 class="category-title">おまかせコース</h2>
                <p class="category-description">季節の味覚をコースで堪能</p>
            </div>

            <div class="course-menu">
                <div class="course-item">
                    <h3 class="course-name">松コース</h3>
                    <p class="course-description">
                        前菜3種、お造り、焼き物、握り10貫、椀物、デザート
                    </p>
                    <p class="course-detail">
                        その日最高の素材を使用した特別コース
                    </p>
                </div>
                <div class="course-item">
                    <h3 class="course-name">竹コース</h3>
                    <p class="course-description">
                        前菜2種、お造り、握り8貫、椀物、デザート
                    </p>
                    <p class="course-detail">
                        バランスの取れた人気のコース
                    </p>
                </div>
                <div class="course-item">
                    <h3 class="course-name">梅コース</h3>
                    <p class="course-description">
                        前菜、握り6貫、巻物、椀物
                    </p>
                    <p class="course-detail">
                        お気軽にお楽しみいただけるコース
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="menu-category">
        <div class="container">
            <div class="category-header">
                <span class="category-icon">🍶</span>
                <h2 class="category-title">お飲み物</h2>
                <p class="category-description">鮨と楽しむ厳選された日本酒</p>
            </div>

            <div class="menu-grid">
                <div class="menu-item">
                    <div class="menu-item-content">
                        <h3 class="menu-item-name">日本酒各種</h3>
                        <p class="menu-item-description">全国から厳選した銘酒を取り揃えております</p>
                    </div>
                </div>
                <div class="menu-item">
                    <div class="menu-item-content">
                        <h3 class="menu-item-name">ワイン</h3>
                        <p class="menu-item-description">鮨に合うワインをソムリエが選定</p>
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

    <section class="reservation-cta">
        <div class="container">
            <h2>ご予約・お問い合わせ</h2>
            <p>カウンター席は予約をおすすめしております</p>
            <div class="contact-info">
                <p class="phone-number">
                    <i class="fas fa-phone"></i>
                    <?php echo get_theme_mod('shiomachi_phone', '079-223-6879'); ?>
                </p>
                <p class="business-hours">
                    営業時間：<?php echo get_theme_mod('shiomachi_hours', '昼 11:30～14:00 / 夜 17:00～22:00'); ?>
                </p>
            </div>
            <a href="<?php echo home_url('/access/'); ?>" class="btn btn-outline-elegant">アクセス情報を見る</a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
