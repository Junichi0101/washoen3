<?php
/**
 * Template Name: ご挨拶
 * Description: Greeting page template with image-focused layout
 *
 * @package Washouen
 */

get_header(); ?>

<main class="main-content">
    <!-- ヒーローセクション（背景画像＋簡潔なメッセージ） -->
    <section class="greeting-hero">
        <?php
        $greeting_hero_id = get_theme_mod('greeting_hero_image', 0);
        $greeting_hero_url = '';
        if ($greeting_hero_id) {
            $greeting_hero_url = wp_get_attachment_image_url($greeting_hero_id, 'full');
        }
        ?>
        <div class="greeting-hero-bg" <?php if ($greeting_hero_url) echo 'style="background-image: url(' . esc_url($greeting_hero_url) . ');"'; ?>></div>
        <div class="greeting-hero-overlay"></div>
        <div class="greeting-hero-content">
            <h1 class="greeting-hero-title">和招縁へようこそ</h1>
            <p class="greeting-hero-text">
                鮮度と真心を一貫に込めたお料理を通じて、皆さまに四季折々の豊かさを感じていただく。<br>
                瀬戸内海の恵みと職人の技が織りなす、心のこもったおもてなしをお届けします。
            </p>
        </div>
    </section>

    <!-- 店舗ギャラリースライダー -->
    <section class="greeting-gallery section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="title-ja">店舗のご紹介</span>
                    <span class="title-en">OUR STORES</span>
                </h2>
            </div>

            <div class="greeting-slider-wrapper" data-interval="<?php echo esc_attr(floatval(get_theme_mod('gallery_slider_interval', 4.0))); ?>">
                <div class="greeting-slider">
                    <?php
                    // 福中店の画像データ
                    $fukunaka_images = array();
                    $fukunaka_labels = array('外観', 'カウンター', '個室', '料理');
                    for ($i = 1; $i <= 4; $i++) {
                        $img_id = get_theme_mod('first_visit_fukunaka_' . $i, 0);
                        if ($img_id) {
                            $fukunaka_images[] = array(
                                'id' => $img_id,
                                'store' => '福中店',
                                'label' => $fukunaka_labels[$i - 1],
                                'phone' => get_theme_mod('fukunaka_phone', '079-284-5355'),
                                'address' => get_theme_mod('fukunaka_address', '〒670-0017 兵庫県姫路市福中町78'),
                                'description' => '活魚・一品料理',
                            );
                        }
                    }

                    // 塩町店の画像データ
                    $shiomachi_images = array();
                    $shiomachi_labels = array('外観', 'カウンター', '握り', '料理');
                    for ($i = 1; $i <= 4; $i++) {
                        $img_id = get_theme_mod('first_visit_shiomachi_' . $i, 0);
                        if ($img_id) {
                            $shiomachi_images[] = array(
                                'id' => $img_id,
                                'store' => '塩町店',
                                'label' => $shiomachi_labels[$i - 1],
                                'phone' => get_theme_mod('shiomachi_phone', '079-223-6879'),
                                'address' => get_theme_mod('shiomachi_address', '〒670-0904 兵庫県姫路市塩町177 アールビル1F'),
                                'description' => '本格江戸前鮨',
                            );
                        }
                    }

                    // 全画像を統合
                    $all_images = array_merge($fukunaka_images, $shiomachi_images);

                    // 画像が存在する場合
                    if (!empty($all_images)) :
                        foreach ($all_images as $img_data) :
                    ?>
                        <div class="greeting-slide">
                            <?php echo wp_get_attachment_image($img_data['id'], 'full', false, array('class' => 'greeting-slide-img')); ?>
                            <div class="greeting-slide-caption">
                                <h3 class="caption-store-name"><?php echo esc_html($img_data['store']); ?><span class="caption-label"> - <?php echo esc_html($img_data['label']); ?></span></h3>
                                <p class="caption-description"><?php echo esc_html($img_data['description']); ?></p>
                                <p class="caption-info">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo esc_html($img_data['address']); ?><br>
                                    <i class="fas fa-phone"></i> TEL: <?php echo esc_html($img_data['phone']); ?>
                                </p>
                            </div>
                        </div>
                    <?php
                        endforeach;
                    else :
                        // プレースホルダー
                        for ($i = 0; $i < 6; $i++) :
                    ?>
                        <div class="greeting-slide">
                            <div class="image-placeholder">
                                <i class="fas fa-image"></i>
                                <p>店舗画像 <?php echo $i + 1; ?></p>
                            </div>
                        </div>
                    <?php
                        endfor;
                    endif;
                    ?>
                </div>
                <div class="greeting-slider-dots"></div>
            </div>
        </div>
    </section>

    <!-- CTA（簡潔版） -->
    <section class="greeting-cta section">
        <div class="container">
            <div class="greeting-cta-content">
                <h2>お客様のシーンに合わせて、最適な店舗をお選びください</h2>
                <div class="greeting-cta-buttons">
                    <a href="<?php echo esc_url(home_url('/fukunaka-menu/')); ?>" class="btn btn-elegant">
                        <i class="fas fa-fire-burner"></i> 福中店 お品書きを見る
                    </a>
                    <a href="<?php echo esc_url(home_url('/shiomachi-menu/')); ?>" class="btn btn-elegant">
                        <i class="fas fa-fish"></i> 塩町店 お品書きを見る
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
