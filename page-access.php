<?php
/**
 * Template Name: アクセス
 * Description: Access and location information page template
 * 
 * @package Washouen
 */

get_header(); ?>

<main class="main-content">
    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">当店への道案内</h1>
            <p class="page-subtitle">ACCESS</p>
        </div>
    </section>

    <!-- 店舗紹介 -->
    <section class="store-introduction section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="title-ja">店舗のご紹介</span>
                    <span class="title-en">OUR STORES</span>
                </h2>
            </div>

            <!-- 福中店 -->
            <div class="store-detail">
                <div class="store-detail-content">
                    <div class="store-detail-text">
                        <h3>福中店</h3>
                        <p>
                            福中店は、活魚・一品料理をメインに提供しております。<br>
                            瀬戸内海の新鮮な活魚を水槽から直前に出し調理いたします。
                        </p>
                        <p>
                            お客様のお好みの調理法「生・焼・煮・揚・蒸・にぎり」にて、<br>
                            天然魚の本当の旨みを色々な形でご堪能いただけます。
                        </p>
                        <p>
                            1階のカウンター席、2階の座敷、3階の完全個室と、<br>
                            様々なシーンに対応できる空間をご用意しております。
                        </p>
                        <div class="store-detail-info">
                            <p><i class="fas fa-map-marker-alt"></i> <?php echo esc_html(get_theme_mod('fukunaka_address', '〒670-0042 兵庫県姫路市米田町15-1 船場東ビル1F')); ?></p>
                            <p><i class="fas fa-phone"></i> TEL: <?php echo esc_html(get_theme_mod('fukunaka_phone', '079-222-5678')); ?></p>
                            <a href="<?php echo home_url('/fukunaka-menu/'); ?>" class="btn btn-japanese">福中店メニューを見る</a>
                        </div>
                    </div>
                    <div class="store-detail-images">
                        <?php 
                        $fukunaka_raw = get_theme_mod('fukunaka_map_url', '');
                        $fukunaka_src = washouen_get_map_embed_src($fukunaka_raw);
                        // Fallback（念のため）
                        if (!$fukunaka_src) {
                            $fukunaka_src = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3274.9189066509725!2d134.6840093770815!3d34.8331313760934!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3554e1717b78f897%3A0xf43add4da66fbd2f!2z5ZKM5oub57iB56aP5Lit5bqX!5e0!3m2!1sja!2sjp!4v1755791309900!5m2!1sja!2sjp';
                        }
                        ?>
                        <div class="map-embed">
                            <iframe
                                src="<?php echo esc_url($fukunaka_src); ?>"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                allowfullscreen
                                aria-label="Google Maps"
                            ></iframe>
                        </div>
                        <?php 
                        $fukunaka_addr = get_theme_mod('fukunaka_address', '兵庫県姫路市米田町15-1');
                        $fukunaka_maps_link = 'https://maps.google.com/?q=' . rawurlencode($fukunaka_addr);
                        ?>
                        <div class="map-actions">
                            <a href="<?php echo esc_url($fukunaka_maps_link); ?>" target="_blank" rel="noopener" class="btn btn-elegant customize-unpreviewable">
                                <i class="fas fa-map"></i> Google Mapで確認する
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 塩町店 -->
            <div class="store-detail reverse">
                <div class="store-detail-content">
                    <div class="store-detail-text">
                        <h3>塩町店</h3>
                        <p>
                            鮨をメインにご提供しております。店主は瀬戸内海の離島（家島）で生まれ、<br>
                            島の豊かな海で獲れる新鮮な魚介に囲まれて育ちました。
                        </p>
                        <p>
                            その地元の恵みを最大限に活かし、店主のひと手間を加えた熟練の技で、<br>
                            素材の旨みを引き出した握りを一貫ずつ、真心を込めてお届けいたします。
                        </p>
                        
                        <div class="store-detail-info">
                            <p><i class="fas fa-map-marker-alt"></i> <?php echo esc_html(get_theme_mod('shiomachi_address', '〒670-0904 兵庫県姫路市塩町177 アールビル1F')); ?></p>
                            <p><i class="fas fa-phone"></i> TEL: <?php echo esc_html(get_theme_mod('shiomachi_phone', '079-223-6879')); ?></p>
                            <a href="<?php echo home_url('/shiomachi-menu/'); ?>" class="btn btn-japanese">塩町店メニューを見る</a>
                        </div>
                    </div>
                    <div class="store-detail-images">
                        <?php 
                        $shiomachi_raw = get_theme_mod('shiomachi_map_url', '');
                        $shiomachi_src = washouen_get_map_embed_src($shiomachi_raw);
                        if (!$shiomachi_src) {
                            $shiomachi_src = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6549.9611743424!2d134.68583184217326!3d34.83158060862831!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3554e0138af315ed%3A0x1e77ee41b6aee837!2z5ZKM5oub57iBIOWhqeeUuuW6lw!5e0!3m2!1sja!2sjp!4v1755791268802!5m2!1sja!2sjp';
                        }
                        ?>
                        <div class="map-embed">
                            <iframe
                                src="<?php echo esc_url($shiomachi_src); ?>"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                allowfullscreen
                                aria-label="Google Maps"
                            ></iframe>
                        </div>
                        <?php 
                        $shiomachi_addr = get_theme_mod('shiomachi_address', '兵庫県姫路市塩町177');
                        $shiomachi_maps_link = 'https://maps.google.com/?q=' . rawurlencode($shiomachi_addr);
                        ?>
                        <div class="map-actions">
                            <a href="<?php echo esc_url($shiomachi_maps_link); ?>" target="_blank" rel="noopener" class="btn btn-elegant customize-unpreviewable">
                                <i class="fas fa-map"></i> Google Mapで確認する
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta section">
        <div class="container">
            <div class="cta-content">
                <h2>心を込めた、本物の味をお届けします</h2>
                <p>
                    お客様のご希望やシーンに合わせて、最適な店舗をお選びください。<br>
                    スタッフ一同、心よりお待ちしております。
                </p>
                <div class="cta-buttons">
                    <a href="<?php echo home_url('/fukunaka-menu/'); ?>" class="btn btn-elegant">
                        <i class="fas fa-fish"></i> 福中店メニューを見る
                    </a>
                    <a href="<?php echo home_url('/shiomachi-menu/'); ?>" class="btn btn-elegant">
                        <i class="fas fa-utensils"></i> 塩町店メニューを見る
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
