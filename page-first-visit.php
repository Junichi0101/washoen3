<?php
/**
 * Template Name: 初めての方へ
 * Description: First visit introduction page template
 * 
 * @package Washouen
 */

get_header(); ?>

<main class="main-content">
    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">初めての方へ</h1>
            <p class="page-subtitle">FIRST VISIT</p>
        </div>
    </section>

    <!-- メインメッセージ -->
    <section class="welcome-message section">
        <div class="container">
            <div class="welcome-content">
                <h2 class="welcome-title">和招縁へようこそ</h2>
                <div class="welcome-text">
                    <p>
                        私たちの使命は、鮮度と真心を一貫に込めたお料理を通じて、<br>
                        皆さまに四季折々の豊かさを感じていただくことです。
                    </p>
                    <p>
                        瀬戸内海の恵みを中心に、その時期に最も美味しい旬の魚を厳選し、<br>
                        丁寧な仕事をほどこしてご提供しております。
                    </p>
                    <p>
                        一品一品に込める職人の技と愛情、そして心のこもったおもてなしで、<br>
                        お客様に「また来たい」と思っていただける店を目指しています。<br>
                        私たちは、お客様との信頼関係を大切にし、<br>
                        常連としてお付き合いいただける、温かな店を創り続けます。
                    </p>
                </div>
            </div>
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
                        <div class="image-grid">
                            <div class="image-placeholder">
                                <i class="fas fa-image"></i>
                                <p>福中店 外観</p>
                            </div>
                            <div class="image-placeholder">
                                <i class="fas fa-image"></i>
                                <p>福中店 カウンター</p>
                            </div>
                            <div class="image-placeholder">
                                <i class="fas fa-image"></i>
                                <p>福中店 個室</p>
                            </div>
                            <div class="image-placeholder">
                                <i class="fas fa-image"></i>
                                <p>福中店 料理</p>
                            </div>
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
                        <div class="image-grid">
                            <div class="image-placeholder">
                                <i class="fas fa-image"></i>
                                <p>塩町店 外観</p>
                            </div>
                            <div class="image-placeholder">
                                <i class="fas fa-image"></i>
                                <p>塩町店 カウンター</p>
                            </div>
                            <div class="image-placeholder">
                                <i class="fas fa-image"></i>
                                <p>塩町店 握り</p>
                            </div>
                            <div class="image-placeholder">
                                <i class="fas fa-image"></i>
                                <p>塩町店 料理</p>
                            </div>
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