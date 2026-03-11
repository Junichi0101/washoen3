<?php
/**
 * フロントページテンプレート
 *
 * @package Washouen
 */

get_header(); ?>

<!-- メインビジュアル -->
<?php
    // ヒーローメッセージのアニメーション設定を取得
    $animation_duration = floatval(get_theme_mod('hero_animation_duration', 1.2));
    $animation_interval = floatval(get_theme_mod('hero_animation_interval', 1.0));
    $initial_delay = 0.5;

    // ヒーローメッセージテキストを取得
    $hero_texts = array(
        get_theme_mod('hero_text_1', '数ある店舗の中から「和招縁」にご関心頂き誠にありがとうございます。'),
        get_theme_mod('hero_text_2', '安全で身体に良い物を吟味し、四季「旬」を大切に、'),
        get_theme_mod('hero_text_3', 'お客様に和みながら美味しく料理を食べて頂きたい。'),
        get_theme_mod('hero_text_4', 'そんな思いを込めて和招縁を開業いたしました。')
    );

    // 有効なテキスト数をカウント（空でないテキストのみ）
    $valid_texts = array_filter($hero_texts, function($text) {
        return !empty(trim($text));
    });
    $valid_text_count = count($valid_texts);

    // テキストアニメーション完了時間を計算
    // 最後のテキストの開始時間 + アニメーション実行時間
    $last_text_start = $initial_delay + (($valid_text_count - 1) * $animation_interval);
    $text_complete_time = $last_text_start + $animation_duration;

    // ボタンと背景の表示開始時間（テキスト完了後に少し余裕を持たせる）
    $buttons_delay = $text_complete_time + 0.3;
    $bg_delay = $text_complete_time;

    $home_hero_id = absint(get_theme_mod('home_hero_image', 0));
?>
<section class="hero" id="top">
    <div class="hero-visual">
        <?php if ($home_hero_id) : ?>
            <div class="hero-bg" style="animation-delay: <?php echo esc_attr($bg_delay); ?>s;">
                <?php echo wp_get_attachment_image($home_hero_id, 'home-hero', false, array('class' => 'hero-bg-img')); ?>
            </div>
        <?php endif; ?>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h2 class="hero-title">
                <span class="hero-title-main">和招縁</span>
                <span class="hero-title-sub"></span>
            </h2>
            <div class="hero-message">
                <p>
                    <?php
                    foreach ($hero_texts as $index => $text) :
                        if (empty(trim($text))) continue; // 空のテキストはスキップ
                        $delay = $initial_delay + ($index * $animation_interval);
                    ?>
                    <span class="hero-text-line fade-in-text" style="animation-delay: <?php echo esc_attr($delay); ?>s; animation-duration: <?php echo esc_attr($animation_duration); ?>s;"><?php echo esc_html($text); ?></span><?php if ($index < count($hero_texts) - 1) : ?><br><br><?php endif; ?>
                    <?php endforeach; ?>
                </p>
            </div>
            <div class="hero-buttons" style="animation-delay: <?php echo esc_attr($buttons_delay); ?>s;">
                <a href="<?php echo esc_url(home_url('/first-visit/')); ?>" class="btn btn-japanese">ご挨拶</a>
                <a href="<?php echo esc_url(home_url('/fukunaka-menu/')); ?>" class="btn btn-japanese">福中店 お品書き</a>
                <a href="<?php echo esc_url(home_url('/shiomachi-menu/')); ?>" class="btn btn-japanese">塩町店 お品書き</a>
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
                    <h3 class="store-name">姫路福中店 和食</h3>
                    <p class="store-description">
                        活魚・一品料理をメインに提供しております。瀬戸内海の荒波にもまれ育った活魚を水槽から直前に出し調理いたします。お客様のお好みの調理法「生・焼・煮・揚・蒸・にぎり」にて天然魚の本当の旨みを色々な形でご堪能下さい。
                    </p>
                    <div class="store-features">
                        <ul class="floor-list">
                            <li><i class="fas fa-chair"></i><span class="floor-name">1階カウンター</span><span class="floor-capacity">（6席）</span></li>
                            <li><i class="fas fa-rug"></i><span class="floor-name">2階座敷</span><span class="floor-capacity">（6名×2部屋）</span></li>
                            <li><i class="fas fa-door-closed"></i><span class="floor-name">3階完全個室</span><span class="floor-capacity">（12席）</span></li>
                        </ul>
                    </div>
                    <div class="store-contact">
                        <?php $fukunaka_phone = get_theme_mod('fukunaka_phone', '079-284-5355'); ?>
                        <div class="store-reservation-info">
                            <p class="reservation-label"><i class="fas fa-phone-alt"></i> ご予約・お問い合わせ</p>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $fukunaka_phone)); ?>" class="store-phone-number"><?php echo esc_html($fukunaka_phone); ?></a>
                        </div>
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
                    <h3 class="store-name">姫路塩町店 寿司</h3>
                    <p class="store-description">
                        瀬戸内の恵みと家島生まれの店主が贈る真心の寿司。敷居を低く、気取らない鮨の魅力を、ゆったりとした空間でご堪能ください。
                    </p>
                    <div class="store-features">
                        <ul class="floor-list">
                            <li><i class="fas fa-chair"></i><span class="floor-name">カウンター</span><span class="floor-capacity">（8席）</span></li>
                            <li><i class="fa-solid fa-couch"></i><span class="floor-name">掘りごたつ・テーブル</span><span class="floor-capacity">（12席）</span></li>
                        </ul>
                    </div>
                    <div class="store-contact">
                        <?php $shiomachi_phone = get_theme_mod('shiomachi_phone', '079-223-6879'); ?>
                        <div class="store-reservation-info">
                            <p class="reservation-label"><i class="fas fa-phone-alt"></i> ご予約・お問い合わせ</p>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $shiomachi_phone)); ?>" class="store-phone-number"><?php echo esc_html($shiomachi_phone); ?></a>
                        </div>
                        <a href="<?php echo esc_url(home_url('/shiomachi-menu/')); ?>" class="menu-link">メニューを見る <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- お知らせ -->
<section class="news section" id="news">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <span class="title-ja">お知らせ</span>
                <span class="title-en">NEWS</span>
            </h2>
        </div>
        <div class="news-grid">
            <?php
            $news_query = new WP_Query(array(
                'post_type' => 'post',
                'posts_per_page' => 3,
                'post_status' => 'publish',
            ));

            if ($news_query->have_posts()) :
                while ($news_query->have_posts()) : $news_query->the_post();
            ?>
                <article class="news-card">
                    <a href="<?php the_permalink(); ?>" class="news-card-link">
                        <div class="news-card-image">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium_large', array('class' => 'news-card-img')); ?>
                            <?php else : ?>
                                <div class="news-card-placeholder">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="news-card-content">
                            <time class="news-card-date" datetime="<?php echo get_the_date('Y-m-d'); ?>">
                                <?php echo get_the_date('Y.m.d'); ?>
                            </time>
                            <h3 class="news-card-title"><?php the_title(); ?></h3>
                            <p class="news-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 40, '...'); ?></p>
                        </div>
                    </a>
                </article>
            <?php
                endwhile;
                wp_reset_postdata();
            else :
            ?>
                <p class="news-empty">現在お知らせはありません。</p>
            <?php endif; ?>
        </div>
        <?php if ($news_query->have_posts()) : ?>
            <div class="news-more">
                <a href="<?php echo esc_url(washouen_get_news_page_url()); ?>" class="menu-link">
                    お知らせ一覧 <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- 御料理 -->
<section class="gallery section" id="gallery">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <span class="title-ja">御料理</span>
                <span class="title-en">GALLERY</span>
            </h2>
        </div>
        <div class="gallery-slider-wrapper" data-interval="<?php echo esc_attr(floatval(get_theme_mod('gallery_slider_interval', 4.0))); ?>">
            <div class="gallery-slider">
                <?php
                    // 全店舗の画像を統合（最大8枚）- 画像IDとスケール値をセットで管理
                    $all_gallery_items = array(
                        array(
                            'id' => absint(get_theme_mod('home_gallery_fukunaka_1', 0)),
                            'scale' => floatval(get_theme_mod('home_gallery_fukunaka_1_scale', 1.0)),
                            'placeholder' => '福中店 1'
                        ),
                        array(
                            'id' => absint(get_theme_mod('home_gallery_fukunaka_2', 0)),
                            'scale' => floatval(get_theme_mod('home_gallery_fukunaka_2_scale', 1.0)),
                            'placeholder' => '福中店 2'
                        ),
                        array(
                            'id' => absint(get_theme_mod('home_gallery_fukunaka_3', 0)),
                            'scale' => floatval(get_theme_mod('home_gallery_fukunaka_3_scale', 1.0)),
                            'placeholder' => '福中店 3'
                        ),
                        array(
                            'id' => absint(get_theme_mod('home_gallery_fukunaka_4', 0)),
                            'scale' => floatval(get_theme_mod('home_gallery_fukunaka_4_scale', 1.0)),
                            'placeholder' => '福中店 4'
                        ),
                        array(
                            'id' => absint(get_theme_mod('home_gallery_shiomachi_1', 0)),
                            'scale' => floatval(get_theme_mod('home_gallery_shiomachi_1_scale', 1.0)),
                            'placeholder' => '塩町店 1'
                        ),
                        array(
                            'id' => absint(get_theme_mod('home_gallery_shiomachi_2', 0)),
                            'scale' => floatval(get_theme_mod('home_gallery_shiomachi_2_scale', 1.0)),
                            'placeholder' => '塩町店 2'
                        ),
                        array(
                            'id' => absint(get_theme_mod('home_gallery_shiomachi_3', 0)),
                            'scale' => floatval(get_theme_mod('home_gallery_shiomachi_3_scale', 1.0)),
                            'placeholder' => '塩町店 3'
                        ),
                        array(
                            'id' => absint(get_theme_mod('home_gallery_shiomachi_4', 0)),
                            'scale' => floatval(get_theme_mod('home_gallery_shiomachi_4_scale', 1.0)),
                            'placeholder' => '塩町店 4'
                        ),
                    );

                    // 有効な画像のみをフィルタリング
                    $valid_images = array();
                    foreach ($all_gallery_items as $item) {
                        if ($item['id']) {
                            $valid_images[] = $item;
                        }
                    }

                    // 画像が存在する場合のみスライダーを表示
                    if (!empty($valid_images)) :
                        foreach ($valid_images as $img_data) :
                            $scale_value = floatval($img_data['scale']);
                            $scale_style = (abs($scale_value - 1.0) > 0.01) ? '--img-scale: ' . $scale_value . ';' : '';
                ?>
                    <div class="gallery-slide"<?php echo $scale_style ? ' style="' . esc_attr($scale_style) . '"' : ''; ?>>
                        <?php echo wp_get_attachment_image($img_data['id'], 'full', false, array('class' => 'gallery-slide-img')); ?>
                    </div>
                <?php
                        endforeach;
                    else :
                        // 画像がない場合のプレースホルダー（最低6枚）
                        $placeholders = array('店舗写真 1', '店舗写真 2', '店舗写真 3', '店舗写真 4', '店舗写真 5', '店舗写真 6');
                        for ($i = 0; $i < 6; $i++) :
                ?>
                    <div class="gallery-slide">
                        <div class="image-placeholder">
                            <i class="fas fa-image"></i>
                            <p><?php echo esc_html($placeholders[$i]); ?></p>
                        </div>
                    </div>
                <?php
                        endfor;
                    endif;
                ?>
            </div>
            <div class="gallery-dots"></div>
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
                <h3>姫路福中店 和食</h3>
                <p>旬の活魚と季節の一品を、想いを込めてご用意しました。</p>
                <a href="<?php echo esc_url(home_url('/fukunaka-menu/')); ?>" class="menu-link">
                    メニューを見る <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="menu-store-card">
                <h3>姫路塩町店 寿司</h3>
                <p>瀬戸内の恵みと家島生まれの店主が握る、真心の鮨。</p>
                <a href="<?php echo esc_url(home_url('/shiomachi-menu/')); ?>" class="menu-link">
                    メニューを見る <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- テイクアウト・出前 -->
<section class="takeout section" id="takeout">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <span class="title-ja">テイクアウト・出前</span>
                <span class="title-en">TAKEOUT & DELIVERY</span>
            </h2>
        </div>
        <div class="takeout-content">
            <div class="takeout-info">
                <div class="takeout-notice">
                    <h3><i class="fas fa-motorcycle"></i> 出前について</h3>
                    <ul>
                        <li>出前範囲：魚町・塩町の繁華街のみ</li>
                        <li>事前にお電話にてご対応可能です（多忙時は対応できない場合がございます）</li>
                    </ul>
                </div>
                <div class="takeout-notice">
                    <h3><i class="fas fa-shopping-bag"></i> テイクアウトについて</h3>
                    <p>各店舗でテイクアウトメニューをご用意しております。詳しくは下記PDFメニューをご覧ください。</p>
                </div>
            </div>
            <div class="takeout-menus">
                <?php
                $fukunaka_takeout_pdf_id = absint(get_theme_mod('fukunaka_takeout_pdf', 0));
                $shiomachi_takeout_pdf_id = absint(get_theme_mod('shiomachi_takeout_pdf', 0));
                $fukunaka_takeout_pdf_url = $fukunaka_takeout_pdf_id ? wp_get_attachment_url($fukunaka_takeout_pdf_id) : '';
                $shiomachi_takeout_pdf_url = $shiomachi_takeout_pdf_id ? wp_get_attachment_url($shiomachi_takeout_pdf_id) : '';
                ?>
                <div class="takeout-menu-card">
                    <h4>福中店 テイクアウトメニュー</h4>
                    <?php if ($fukunaka_takeout_pdf_url) : ?>
                        <a href="<?php echo esc_url($fukunaka_takeout_pdf_url); ?>" target="_blank" rel="noopener" class="btn btn-elegant">
                            <i class="fas fa-file-pdf"></i> PDFメニューを見る
                        </a>
                    <?php else : ?>
                        <p class="no-pdf">準備中</p>
                    <?php endif; ?>
                    <p class="takeout-contact">お問い合わせ：<?php echo esc_html(get_theme_mod('fukunaka_phone', '079-284-5355')); ?></p>
                </div>
                <div class="takeout-menu-card">
                    <h4>塩町店 テイクアウトメニュー</h4>
                    <?php if ($shiomachi_takeout_pdf_url) : ?>
                        <a href="<?php echo esc_url($shiomachi_takeout_pdf_url); ?>" target="_blank" rel="noopener" class="btn btn-elegant">
                            <i class="fas fa-file-pdf"></i> PDFメニューを見る
                        </a>
                    <?php else : ?>
                        <p class="no-pdf">準備中</p>
                    <?php endif; ?>
                    <p class="takeout-contact">お問い合わせ：<?php echo esc_html(get_theme_mod('shiomachi_phone', '079-223-6879')); ?></p>
                </div>
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
                        〒670-0017<br>
                        兵庫県姫路市福中町７８
                    </p>
                    <p class="hours">
                        <i class="fas fa-clock"></i>
                        営業時間：18:00〜1:00（L.O. 0:30）
                    </p>
                    <p class="phone">
                        <i class="fas fa-phone"></i>
                        TEL: <?php echo esc_html(get_theme_mod('fukunaka_phone', '079-284-5355')); ?>
                    </p>
                    <div class="store-map">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d409.3645983297917!2d134.68632179727203!3d34.83318467623548!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3554e1717b78f897%3A0xf43add4da66fbd2f!2z5ZKM5oub57iB56aP5Lit5bqX!5e0!3m2!1sja!2sjp!4v1763206350675!5m2!1sja!2sjp"
                            width="100%"
                            height="200"
                            style="border:0;border-radius:8px;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <a href="https://maps.google.com/?q=兵庫県姫路市福中町７８" target="_blank" rel="noopener" class="btn btn-elegant">
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
                    <p class="hours">
                        <i class="fas fa-clock"></i>
                        営業時間：18:00〜2:00（L.O. 1:30）
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
            <a href="<?php echo esc_url(home_url('/access/')); ?>" class="menu-link">詳しい道案内を見る</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
