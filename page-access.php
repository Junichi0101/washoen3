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
                        $fukunaka_map_url = get_theme_mod('fukunaka_map_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3281.7!2d133.88!3d34.66!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzTCsDM5JzM2LjAiTiAxMzPCsDUyJzQ4LjAiRQ!5e0!3m2!1sja!2sjp!4v1234567890');
                        ?>
                        <div class="store-map">
                            <iframe 
                                src="<?php echo esc_url($fukunaka_map_url); ?>"
                                width="100%" 
                                height="400" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                title="福中店の位置"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
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
                            塩町店は、本格江戸前鮨をメインに提供しております。<br>
                            厳選された旬の魚介を、熟練の職人が一貫一貫丁寧に握ります。
                        </p>
                        <p>
                            赤酢を使った伝統のシャリ、有明海の一番摘み海苔、<br>
                            静岡産の本山葵など、素材の一つ一つにこだわっております。
                        </p>
                        <p>
                            カウンター席では職人との会話を楽しみながら、<br>
                            テーブル席ではゆったりとした時間をお過ごしいただけます。
                        </p>
                        <div class="store-detail-info">
                            <p><i class="fas fa-map-marker-alt"></i> <?php echo esc_html(get_theme_mod('shiomachi_address', '〒670-0904 兵庫県姫路市塩町177 アールビル1F')); ?></p>
                            <p><i class="fas fa-phone"></i> TEL: <?php echo esc_html(get_theme_mod('shiomachi_phone', '079-223-6879')); ?></p>
                            <a href="<?php echo home_url('/shiomachi-menu/'); ?>" class="btn btn-japanese">塩町店メニューを見る</a>
                        </div>
                    </div>
                    <div class="store-detail-images">
                        <?php 
                        $shiomachi_map_url = get_theme_mod('shiomachi_map_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3281.7!2d133.89!3d34.67!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzTCsDQwJzEyLjAiTiAxMzPCsDUzJzI0LjAiRQ!5e0!3m2!1sja!2sjp!4v1234567890');
                        ?>
                        <div class="store-map">
                            <iframe 
                                src="<?php echo esc_url($shiomachi_map_url); ?>"
                                width="100%" 
                                height="400" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                title="塩町店の位置"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
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