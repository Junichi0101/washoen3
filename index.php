<?php
/**
 * メインテンプレートファイル
 * 
 * @package Washouen
 */

get_header(); ?>

<?php if (is_front_page()) : ?>
    
    <!-- メインビジュアル -->
    <section class="hero" id="top">
        <div class="hero-visual">
            <?php $home_hero_id = absint(get_theme_mod('home_hero_image', 0)); ?>
            <?php if ($home_hero_id) : ?>
                <div class="hero-bg">
                    <?php echo wp_get_attachment_image($home_hero_id, 'home-hero', false, array('class' => 'hero-bg-img')); ?>
                </div>
            <?php endif; ?>
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h2 class="hero-title">
                    <span class="hero-title-main">和招縁の思い</span>
                    <span class="hero-title-sub">心を込めた、本物の味をお届けします</span>
                </h2>
                <div class="hero-message">
                    <p>
                        数ある店舗の中から「和招縁」にご関心頂き誠にありがとうございます。<br>
                        安全で身体に良い物を吟味し、四季「旬」を大切に、<br>
                        お客様に和みながら美味しく料理を食べて頂きたい。<br>
                        和招縁でお会いする方々に素敵なご縁が出来る場所であってほしい。<br>
                        そんな思いを込めて和招縁を開業いたしました。
                    </p>
                </div>
                <div class="hero-buttons">
                    <a href="<?php echo esc_url(home_url('/first-visit/')); ?>" class="btn btn-elegant">初めての方へ</a>
                    <a href="<?php echo esc_url(home_url('/fukunaka-menu/')); ?>" class="btn btn-japanese">福中店メニュー</a>
                    <a href="<?php echo esc_url(home_url('/shiomachi-menu/')); ?>" class="btn btn-japanese">塩町店メニュー</a>
                </div>
            </div>
        </div>
    </section>

    <!-- 二店舗のご案内 -->
    <section class="stores section" id="stores">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="title-ja">二店舗のご案内</span>
                    <span class="title-en">OUR STORES</span>
                </h2>
            </div>
            <div class="stores-grid">
                <!-- 福中店 -->
                <div class="store-card">
                    <?php 
                        $home_fukunaka_id = absint(get_theme_mod('home_fukunaka_image', 0));
                        $fukunaka_page = get_page_by_path('fukunaka-store');
                    ?>
                    <div class="store-image">
                        <?php if ($home_fukunaka_id) : ?>
                            <?php echo wp_get_attachment_image($home_fukunaka_id, 'home-card', false, array('class' => 'store-image-img')); ?>
                        <?php elseif ($fukunaka_page && has_post_thumbnail($fukunaka_page)) : ?>
                            <?php echo get_the_post_thumbnail($fukunaka_page, 'washouen-featured'); ?>
                        <?php else : ?>
                            <div class="image-placeholder">
                                <i class="fas fa-store" aria-hidden="true"></i>
                                <p>福中店外観</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="store-content">
                        <h3 class="store-name">福中店</h3>
                        <p class="store-description">
                            活魚・一品料理をメインに提供しております。瀬戸内海の荒波にもまれ育った活魚を水槽から直前に出し調理いたします。お客様のお好みの調理法「生・焼・煮・揚・蒸・にぎり」にて天然魚の本当の旨みを色々な形でご堪能下さい。
                        </p>
                        <div class="store-features">
                            <ul>
                                <li><i class="fas fa-chair"></i> 2階（6名用座敷）2部屋</li>
                                <li><i class="fas fa-door-open"></i> 3階完全個室(テーブル席12名)</li>
                                <li><i class="fas fa-utensils"></i> 1階カウンター（6席）</li>
                            </ul>
                        </div>
                        <div class="store-contact">
                            <p><i class="fas fa-phone"></i> お電話: <?php echo esc_html(get_theme_mod('fukunaka_phone', '079-222-5678')); ?></p>
                            <a href="<?php echo esc_url(home_url('/fukunaka-menu/')); ?>" class="menu-link">メニューを見る <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>

                <!-- 塩町店 -->
                <div class="store-card">
                    <?php 
                        $home_shiomachi_id = absint(get_theme_mod('home_shiomachi_image', 0));
                        $shiomachi_page = get_page_by_path('shiomachi-store');
                    ?>
                    <div class="store-image">
                        <?php if ($home_shiomachi_id) : ?>
                            <?php echo wp_get_attachment_image($home_shiomachi_id, 'home-card', false, array('class' => 'store-image-img')); ?>
                        <?php elseif ($shiomachi_page && has_post_thumbnail($shiomachi_page)) : ?>
                            <?php echo get_the_post_thumbnail($shiomachi_page, 'washouen-featured'); ?>
                        <?php else : ?>
                            <div class="image-placeholder">
                                <i class="fas fa-store" aria-hidden="true"></i>
                                <p>塩町店外観</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="store-content">
                        <h3 class="store-name">塩町店</h3>
                        <p class="store-description">
                            鮨をメインに提供しております。店主は瀬戸内海の離島(家島)で生まれ、島の豊かな海で獲れる新鮮な魚介で育ちました。その自慢の地元の恵みを活かした本物のおいしさと店主のひと手間加えた熟練の技が光る握りを真心こめてご提供いたします。
                        </p>
                        <div class="store-features">
                            <ul>
                                <li><i class="fas fa-chair"></i> カウンター（8席用意）</li>
                                <li><i class="fas fa-couch"></i> 掘りごたつ・テーブル完備（12席）</li>
                            </ul>
                        </div>
                        <div class="store-contact">
                            <p><i class="fas fa-phone"></i> お電話: <?php echo esc_html(get_theme_mod('shiomachi_phone', '079-223-6879')); ?></p>
                            <a href="<?php echo esc_url(home_url('/shiomachi-menu/')); ?>" class="menu-link">メニューを見る <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 店舗ギャラリー -->
    <section class="gallery section" id="gallery">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="title-ja">店舗ギャラリー</span>
                    <span class="title-en">GALLERY</span>
                </h2>
            </div>
            <div class="gallery-wrapper">
                <!-- 福中店ギャラリー -->
                <div class="gallery-store">
                    <h3 class="gallery-store-title">福中店</h3>
                    <div class="gallery-grid">
                        <?php 
                            $fukunaka_gallery_ids = array(
                                absint(get_theme_mod('home_gallery_fukunaka_1', 0)),
                                absint(get_theme_mod('home_gallery_fukunaka_2', 0)),
                                absint(get_theme_mod('home_gallery_fukunaka_3', 0)),
                                absint(get_theme_mod('home_gallery_fukunaka_4', 0)),
                            );
                            $fukunaka_placeholders = array('福中店 カウンター', '福中店 個室', '福中店 活魚水槽', '福中店 料理');
                            foreach ($fukunaka_gallery_ids as $idx => $img_id) :
                        ?>
                            <div class="gallery-item">
                                <?php if ($img_id) : ?>
                                    <?php echo wp_get_attachment_image($img_id, 'washouen-gallery'); ?>
                                <?php else : ?>
                                    <div class="image-placeholder">
                                        <i class="fas fa-image"></i>
                                        <p><?php echo esc_html($fukunaka_placeholders[$idx]); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 塩町店ギャラリー -->
                <div class="gallery-store">
                    <h3 class="gallery-store-title">塩町店</h3>
                    <div class="gallery-grid">
                        <?php 
                            $shiomachi_gallery_ids = array(
                                absint(get_theme_mod('home_gallery_shiomachi_1', 0)),
                                absint(get_theme_mod('home_gallery_shiomachi_2', 0)),
                                absint(get_theme_mod('home_gallery_shiomachi_3', 0)),
                                absint(get_theme_mod('home_gallery_shiomachi_4', 0)),
                            );
                            $shiomachi_placeholders = array('塩町店 カウンター', '塩町店 座敷', '塩町店 握り', '塩町店 料理');
                            foreach ($shiomachi_gallery_ids as $idx => $img_id) :
                        ?>
                            <div class="gallery-item">
                                <?php if ($img_id) : ?>
                                    <?php echo wp_get_attachment_image($img_id, 'washouen-gallery'); ?>
                                <?php else : ?>
                                    <div class="image-placeholder">
                                        <i class="fas fa-image"></i>
                                        <p><?php echo esc_html($shiomachi_placeholders[$idx]); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- お品書き -->
    <section class="menu-preview section" id="menu">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="title-ja">お品書き</span>
                    <span class="title-en">MENU</span>
                </h2>
            </div>
            <div class="menu-stores">
                <div class="menu-store-card">
                    <div class="menu-store-icon">
                        <i class="fas fa-fish"></i>
                    </div>
                    <h3>福中店 - 魚料理</h3>
                    <p>新鮮な魚介を使用した多彩な料理をご提供しております。</p>
                    <a href="<?php echo esc_url(home_url('/fukunaka-menu/')); ?>" class="menu-link">
                        メニューを見る <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="menu-store-card">
                    <div class="menu-store-icon">
                        <i class="fas fa-fish-fins"></i>
                    </div>
                    <h3>塩町店 - 寿司</h3>
                    <p>職人の技術が光る、本格的な寿司をお楽しみください。</p>
                    <a href="<?php echo esc_url(home_url('/shiomachi-menu/')); ?>" class="menu-link">
                        メニューを見る <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- アクセス -->
    <section class="access section" id="access">
        <?php $home_access_bg_id = absint(get_theme_mod('home_access_bg_image', 0)); ?>
        <?php if ($home_access_bg_id) : ?>
            <div class="section-bg">
                <?php echo wp_get_attachment_image($home_access_bg_id, 'home-hero', false, array('class' => 'section-bg-img')); ?>
            </div>
        <?php endif; ?>
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="title-ja">アクセス</span>
                    <span class="title-en">ACCESS</span>
                </h2>
            </div>
            <div class="access-grid">
                <!-- 福中店 -->
                <div class="access-card">
                    <h3>和招縁 福中店</h3>
                    <div class="access-info">
                        <p class="address">
                            <i class="fas fa-map-marker-alt"></i>
                            〒670-0042<br>
                            兵庫県姫路市米田町15-1 船場東ビル1F
                        </p>
                        <p class="phone">
                            <i class="fas fa-phone"></i>
                            TEL: <?php echo esc_html(get_theme_mod('fukunaka_phone', '079-222-5678')); ?>
                        </p>
                        <div class="store-map">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3274.9189066509725!2d134.6840093770815!3d34.8331313760934!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3554e1717b78f897%3A0xf43add4da66fbd2f!2z5ZKM5oub57iB56aP5Lit5bqX!5e0!3m2!1sja!2sjp!4v1755791309900!5m2!1sja!2sjp"
                                width="100%" 
                                height="200" 
                                style="border:0;border-radius:8px;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <a href="https://maps.google.com/?q=兵庫県姫路市米田町15-1" target="_blank" rel="noopener" class="btn btn-elegant">
                            <i class="fas fa-map"></i> Google Mapで見る
                        </a>
                    </div>
                </div>

                <!-- 塩町店 -->
                <div class="access-card">
                    <h3>和招縁 塩町店</h3>
                    <div class="access-info">
                        <p class="address">
                            <i class="fas fa-map-marker-alt"></i>
                            〒670-0904<br>
                            兵庫県姫路市塩町177 アールビル 1F
                        </p>
                        <p class="phone">
                            <i class="fas fa-phone"></i>
                            TEL: <?php echo esc_html(get_theme_mod('shiomachi_phone', '079-223-6879')); ?>
                        </p>
                        <div class="store-map">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6549.9611743424!2d134.68583184217326!3d34.83158060862831!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3554e0138af315ed%3A0x1e77ee41b6aee837!2z5ZKM5oub57iBIOWhqeeUuuW6lw!5e0!3m2!1sja!2sjp!4v1755791268802!5m2!1sja!2sjp"
                                width="100%" 
                                height="200" 
                                style="border:0;border-radius:8px;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <a href="https://maps.google.com/?q=兵庫県姫路市塩町177" target="_blank" rel="noopener" class="btn btn-elegant">
                            <i class="fas fa-map"></i> Google Mapで見る
                        </a>
                    </div>
                </div>
            </div>
            <div class="access-detail-link">
                <a href="<?php echo esc_url(home_url('/access/')); ?>" class="btn btn-minimal">詳しい道案内を見る</a>
            </div>
        </div>
    </section>

<?php else : ?>
    
    <!-- 通常のブログ一覧 -->
    <div class="container">
        <div class="content-area">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>
                        </header>
                        <div class="entry-content">
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
                
                <?php the_posts_navigation(); ?>
                
            <?php else : ?>
                <p><?php _e('記事が見つかりませんでした。', 'washouen'); ?></p>
            <?php endif; ?>
        </div>
    </div>

<?php endif; ?>

<?php get_footer(); ?>
