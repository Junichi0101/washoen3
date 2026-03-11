<?php
/**
 * Template Name: 塩町店 お品書き
 * Description: Shiomachi store menu page template
 *
 * @package Washouen
 */

get_header(); ?>

<main class="main-content shiomachi-menu-page">
    <!-- ページヘッダー -->
    <?php
    // ヘッダー背景画像の取得
    $header_bg_id = get_theme_mod('shiomachi_menu_bg_image', 0);
    $header_bg_url = $header_bg_id ? wp_get_attachment_image_url($header_bg_id, 'full') : '';
    $header_bg_style = $header_bg_url ? ' style="background-image: url(' . esc_url($header_bg_url) . ');"' : '';
    ?>
    <section class="page-header shiomachi-menu-header"<?php echo $header_bg_style; ?>>
        <div class="page-header-overlay"></div>
        <div class="page-header-content">
            <h1 class="page-title">塩町店 お品書き</h1>
            <p class="page-subtitle">SHIOMACHI MENU</p>
        </div>
    </section>

    <!-- メインメッセージ -->
    <section class="welcome-message section">
        <div class="container">
            <div class="welcome-content">
                <h2 class="welcome-title">気取らない鮨の魅力を、ゆっくりと</h2>
                <div class="welcome-text">
                    <p>
                        敷居を低く、肩肘張らずに本格的な鮨を楽しんでいただきたい。
                    </p>
                    <p>
                        瀬戸内の恵みを活かした握りを、落ち着いた空間でゆっくりとご堪能ください。
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 料理画像ギャラリースライダー -->
    <?php
    // ギャラリー画像を取得
    $gallery_images = array();
    for ($i = 1; $i <= 6; $i++) {
        $image_id = get_theme_mod('shiomachi_menu_gallery_' . $i, 0);
        if ($image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'full');
            $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
            if ($image_url) {
                $gallery_images[] = array(
                    'url' => $image_url,
                    'alt' => $image_alt ? $image_alt : '塩町店の料理'
                );
            }
        }
    }

    if (!empty($gallery_images)) :
        $slider_interval = get_theme_mod('shiomachi_menu_slider_interval', 4.0);
    ?>
        <section class="menu-gallery-slider shiomachi-slider">
            <div class="slider-container">
                <?php foreach ($gallery_images as $index => $image) : ?>
                    <div class="slider-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo esc_url($image['url']); ?>"
                             alt="<?php echo esc_attr($image['alt']); ?>"
                             loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>">
                    </div>
                <?php endforeach; ?>

                <?php if (count($gallery_images) > 1) : ?>
                    <div class="slider-dots">
                        <?php foreach ($gallery_images as $index => $image) : ?>
                            <button class="slider-dot <?php echo $index === 0 ? 'active' : ''; ?>"
                                    data-slide="<?php echo $index; ?>"
                                    aria-label="画像<?php echo $index + 1; ?>へ"></button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <script>
                // スライダーの初期化（インライン設定）
                if (typeof window.shiomachiSliderSettings === 'undefined') {
                    window.shiomachiSliderSettings = {};
                }
                window.shiomachiSliderSettings.interval = <?php echo floatval($slider_interval) * 1000; ?>;
            </script>
        </section>
    <?php endif; ?>

    <?php
    // タクソノミーからカテゴリーを動的に取得
    $terms = get_terms(array(
        'taxonomy' => 'shiomachi_category',
        'hide_empty' => false,
    ));

    // カテゴリーを順序でソート
    $terms = washouen_sort_terms_by_order($terms);

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

        if ($menu_items->have_posts()) :
            // メニューアイテムをグループ別に整理
            $grouped_items = array();
            $ungrouped_items = array();

            while ($menu_items->have_posts()) : $menu_items->the_post();
                $group_id = get_post_meta(get_the_ID(), '_menu_group_id', true);
                $item_data = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'price' => get_post_meta(get_the_ID(), '_menu_price', true),
                    'description' => get_post_meta(get_the_ID(), '_menu_description', true),
                    'origin' => get_post_meta(get_the_ID(), '_menu_origin', true),
                    'seasonal_type' => get_post_meta(get_the_ID(), '_menu_seasonal_type', true),
                    'is_seasonal' => get_post_meta(get_the_ID(), '_menu_is_seasonal', true),
                    'is_group_image' => get_post_meta(get_the_ID(), '_menu_is_group_image', true),
                    'has_thumbnail' => has_post_thumbnail(),
                    'thumbnail' => has_post_thumbnail() ? get_the_post_thumbnail(get_the_ID(), 'medium', array('loading' => 'lazy', 'alt' => get_the_title())) : '',
                    'menu_order' => get_post_field('menu_order', get_the_ID())
                );

                if (!empty($group_id)) {
                    if (!isset($grouped_items[$group_id])) {
                        $grouped_items[$group_id] = array(
                            'items' => array(),
                            'min_order' => $item_data['menu_order']
                        );
                    }
                    $grouped_items[$group_id]['items'][] = $item_data;
                    if ($item_data['menu_order'] < $grouped_items[$group_id]['min_order']) {
                        $grouped_items[$group_id]['min_order'] = $item_data['menu_order'];
                    }
                } else {
                    $ungrouped_items[] = $item_data;
                }
            endwhile;

            // グループと個別アイテムを表示順でマージ
            $display_items = array();

            foreach ($grouped_items as $group_id => $group_data) {
                $display_items[] = array(
                    'type' => 'group',
                    'group_id' => $group_id,
                    'items' => $group_data['items'],
                    'order' => $group_data['min_order']
                );
            }

            foreach ($ungrouped_items as $item) {
                $display_items[] = array(
                    'type' => 'single',
                    'item' => $item,
                    'order' => $item['menu_order']
                );
            }

            // 表示順でソート
            usort($display_items, function($a, $b) {
                return $a['order'] - $b['order'];
            });
            ?>
            <section class="menu-category sushi-category" id="<?php echo esc_attr($category_slug); ?>">
                <div class="container">
                    <div class="category-header">
                        <span class="category-icon"><?php echo $category_info['icon']; ?></span>
                        <h2 class="category-title"><?php echo esc_html($category_info['title']); ?></h2>
                        <p class="category-description"><?php echo wp_kses_post($category_info['description']); ?></p>
                    </div>

                    <div class="menu-grid" data-nosnippet>
                        <?php foreach ($display_items as $display_item) :
                            if ($display_item['type'] === 'group') :
                                // グループカード
                                $group_items = $display_item['items'];
                                // グループ内でmenu_orderでソート
                                usort($group_items, function($a, $b) {
                                    return $a['menu_order'] - $b['menu_order'];
                                });

                                // 代表画像を取得
                                $group_image = '';
                                foreach ($group_items as $g_item) {
                                    if ($g_item['is_group_image'] == '1' && $g_item['has_thumbnail']) {
                                        $group_image = $g_item['thumbnail'];
                                        break;
                                    }
                                }
                                // 代表画像がなければ最初の画像を使用
                                if (empty($group_image)) {
                                    foreach ($group_items as $g_item) {
                                        if ($g_item['has_thumbnail']) {
                                            $group_image = $g_item['thumbnail'];
                                            break;
                                        }
                                    }
                                }
                                ?>
                                <div class="menu-card menu-card-group">
                                    <?php if (!empty($group_image)) : ?>
                                        <div class="menu-card-image">
                                            <?php echo $group_image; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="menu-card-content menu-group-content">
                                        <?php foreach ($group_items as $g_item) :
                                            // 季節限定タグの後方互換性
                                            $seasonal_type = $g_item['seasonal_type'];
                                            if (empty($seasonal_type) && $g_item['is_seasonal'] == '1') {
                                                $seasonal_type = 'seasonal';
                                            }
                                            ?>
                                            <div class="menu-group-item">
                                                <div class="menu-item-header">
                                                    <h3 class="menu-card-title"><?php echo esc_html($g_item['title']); ?></h3>
                                                    <?php if ($g_item['price']) : ?>
                                                        <span class="menu-leader" aria-hidden="true"></span>
                                                        <span class="menu-item-price"><?php echo ($g_item['price'] === '時価') ? $g_item['price'] : '¥' . esc_html($g_item['price']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if (!empty($seasonal_type) || !empty($g_item['origin'])) : ?>
                                                    <div class="menu-card-meta">
                                                        <?php if ($seasonal_type == 'seasonal') : ?>
                                                            <span class="menu-badge seasonal">季節限定</span>
                                                        <?php elseif ($seasonal_type == 'summer') : ?>
                                                            <span class="menu-badge summer">夏季限定</span>
                                                        <?php elseif ($seasonal_type == 'winter') : ?>
                                                            <span class="menu-badge winter">冬季限定</span>
                                                        <?php endif; ?>
                                                        <?php if ($g_item['origin']) : ?>
                                                            <span class="menu-badge origin"><?php echo esc_html($g_item['origin']); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($g_item['description']) : ?>
                                                    <p class="menu-card-description"><?php echo esc_html($g_item['description']); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else :
                                // 個別カード（従来通り）
                                $item = $display_item['item'];
                                $seasonal_type = $item['seasonal_type'];
                                if (empty($seasonal_type) && $item['is_seasonal'] == '1') {
                                    $seasonal_type = 'seasonal';
                                }
                                $card_classes = 'menu-card';
                                if (!$item['has_thumbnail']) {
                                    $card_classes .= ' menu-card-no-image';
                                }
                                ?>
                                <div class="<?php echo esc_attr($card_classes); ?>">
                                    <?php if ($item['has_thumbnail']) : ?>
                                        <div class="menu-card-image">
                                            <?php echo $item['thumbnail']; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="menu-card-content">
                                        <div class="menu-item-header">
                                            <h3 class="menu-card-title"><?php echo esc_html($item['title']); ?></h3>
                                            <?php if ($item['price']) : ?>
                                                <span class="menu-leader" aria-hidden="true"></span>
                                                <span class="menu-item-price"><?php echo ($item['price'] === '時価') ? $item['price'] : '¥' . esc_html($item['price']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($seasonal_type) || !empty($item['origin'])) : ?>
                                            <div class="menu-card-meta">
                                                <?php if ($seasonal_type == 'seasonal') : ?>
                                                    <span class="menu-badge seasonal">季節限定</span>
                                                <?php elseif ($seasonal_type == 'summer') : ?>
                                                    <span class="menu-badge summer">夏季限定</span>
                                                <?php elseif ($seasonal_type == 'winter') : ?>
                                                    <span class="menu-badge winter">冬季限定</span>
                                                <?php endif; ?>
                                                <?php if ($item['origin']) : ?>
                                                    <span class="menu-badge origin"><?php echo esc_html($item['origin']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($item['description']) : ?>
                                            <p class="menu-card-description"><?php echo esc_html($item['description']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif;
                        endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif;
        wp_reset_postdata();
    endforeach; ?>

    <section class="menu-notice">
        <div class="container">
            <div class="notice-content">
                <h3>ご案内</h3>
                <ul>
                    <li>表示価格はすべて税込みです</li>
                    <li>仕入れ状況により、ネタが変更になる場合がございます</li>
                    <li>アレルギーをお持ちの方は、事前にお申し付けください</li>
                </ul>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
