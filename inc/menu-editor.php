<?php
/**
 * メニュー編集機能
 * 
 * @package Washouen
 * @since 1.0.0
 */

// メニューアイテムのカスタムフィールドを追加
function washouen_add_menu_meta_boxes() {
    // 福中店お品書き用メタボックス
    add_meta_box(
        'fukunaka_menu_details',
        '福中店 お品書き詳細',
        'washouen_fukunaka_menu_meta_box',
        'fukunaka_menu',
        'normal',
        'high'
    );

    // 塩町店お品書き用メタボックス
    add_meta_box(
        'shiomachi_menu_details',
        '塩町店 お品書き詳細',
        'washouen_shiomachi_menu_meta_box',
        'shiomachi_menu',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'washouen_add_menu_meta_boxes');

// 福中店お品書きメタボックスの内容
function washouen_fukunaka_menu_meta_box($post) {
    wp_nonce_field('washouen_save_menu_meta', 'washouen_menu_nonce');
    
    $price = get_post_meta($post->ID, '_menu_price', true);
    $description = get_post_meta($post->ID, '_menu_description', true);
    $is_seasonal = get_post_meta($post->ID, '_menu_is_seasonal', true);
    $category = get_post_meta($post->ID, '_menu_category', true);
    ?>
    <style>
        .menu-meta-field {
            margin-bottom: 20px;
        }
        .menu-meta-field label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .menu-meta-field input[type="text"],
        .menu-meta-field input[type="number"],
        .menu-meta-field textarea,
        .menu-meta-field select {
            width: 100%;
            max-width: 500px;
        }
        .menu-meta-field textarea {
            height: 100px;
        }
        .menu-meta-checkbox {
            margin-right: 5px;
        }
        .menu-help {
            color: #666;
            font-style: italic;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
    
    <div class="menu-meta-field">
        <label for="menu_price">価格</label>
        <input type="text" id="menu_price" name="menu_price" value="<?php echo esc_attr($price); ?>" placeholder="例: 1,200 または 時価">
        <p class="menu-help">価格を入力してください。「時価」と入力することも可能です。</p>
    </div>
    
    <div class="menu-meta-field">
        <label for="menu_description">説明</label>
        <textarea id="menu_description" name="menu_description" placeholder="料理の説明を入力してください"><?php echo esc_textarea($description); ?></textarea>
        <p class="menu-help">料理の特徴や調理法などを記載してください。</p>
    </div>
    
    <div class="menu-meta-field">
        <label for="menu_category">カテゴリー</label>
        <select id="menu_category" name="menu_category">
            <option value="">カテゴリーを選択</option>
            <?php
            // 福中店のカテゴリータクソノミーから動的に取得
            $terms = get_terms(array(
                'taxonomy' => 'fukunaka_category',
                'hide_empty' => false,
            ));

            // デフォルトカテゴリーの定義（タクソノミーに登録されていない場合のフォールバック）
            $default_categories = array(
                'course' => 'コース料理',
                'sashimi' => 'お造り',
                'grilled' => '焼き物',
                'simmered' => '煮付け',
                'fried' => '揚げ物',
                'special' => '季節の特選料理',
                'drink' => 'お飲み物'
            );

            // タクソノミーからカテゴリーを表示
            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    echo '<option value="' . esc_attr($term->slug) . '" ' . selected($category, $term->slug, false) . '>' . esc_html($term->name) . '</option>';
                }
            } else {
                // タクソノミーが空の場合はデフォルトカテゴリーを表示
                foreach ($default_categories as $slug => $name) {
                    echo '<option value="' . esc_attr($slug) . '" ' . selected($category, $slug, false) . '>' . esc_html($name) . '</option>';
                }
            }
            ?>
        </select>
        <p class="menu-help">カテゴリーは「福中店 お品書き」→「メニューカテゴリー」から追加・編集できます。</p>
    </div>
    
    <div class="menu-meta-field">
        <label>
            <input type="checkbox" class="menu-meta-checkbox" id="menu_is_seasonal" name="menu_is_seasonal" value="1" <?php checked($is_seasonal, '1'); ?>>
            季節限定メニュー
        </label>
        <p class="menu-help">季節限定の場合はチェックしてください。</p>
    </div>
    <?php
}

// 塩町店お品書きメタボックスの内容
function washouen_shiomachi_menu_meta_box($post) {
    wp_nonce_field('washouen_save_menu_meta', 'washouen_menu_nonce');
    
    $price = get_post_meta($post->ID, '_menu_price', true);
    $description = get_post_meta($post->ID, '_menu_description', true);
    $origin = get_post_meta($post->ID, '_menu_origin', true);
    $category = get_post_meta($post->ID, '_menu_category', true);
    ?>
    <style>
        .menu-meta-field {
            margin-bottom: 20px;
        }
        .menu-meta-field label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .menu-meta-field input[type="text"],
        .menu-meta-field input[type="number"],
        .menu-meta-field textarea,
        .menu-meta-field select {
            width: 100%;
            max-width: 500px;
        }
        .menu-meta-field textarea {
            height: 100px;
        }
        .menu-help {
            color: #666;
            font-style: italic;
            font-size: 13px;
            margin-top: 5px;
        }
    </style>
    
    <div class="menu-meta-field">
        <label for="menu_price">価格</label>
        <input type="text" id="menu_price" name="menu_price" value="<?php echo esc_attr($price); ?>" placeholder="例: 800 または 時価">
        <p class="menu-help">価格を入力してください。「時価」と入力することも可能です。</p>
    </div>
    
    <div class="menu-meta-field">
        <label for="menu_description">説明</label>
        <textarea id="menu_description" name="menu_description" placeholder="ネタの説明を入力してください"><?php echo esc_textarea($description); ?></textarea>
        <p class="menu-help">ネタの特徴や産地などを記載してください。</p>
    </div>
    
    <div class="menu-meta-field">
        <label for="menu_origin">産地</label>
        <input type="text" id="menu_origin" name="menu_origin" value="<?php echo esc_attr($origin); ?>" placeholder="例: 北海道産">
        <p class="menu-help">産地を入力してください（任意）。</p>
    </div>
    
    <div class="menu-meta-field">
        <label for="menu_category">カテゴリー</label>
        <select id="menu_category" name="menu_category">
            <option value="">カテゴリーを選択</option>
            <?php
            // 塩町店のカテゴリータクソノミーから動的に取得
            $terms = get_terms(array(
                'taxonomy' => 'shiomachi_category',
                'hide_empty' => false,
            ));

            // デフォルトカテゴリーの定義（タクソノミーに登録されていない場合のフォールバック）
            $default_categories = array(
                'nigiri' => '握り',
                'gunkan' => '軍艦・巻物',
                'chirashi' => 'ちらし・丼',
                'omakase' => 'おまかせコース',
                'side' => '一品料理',
                'drink' => 'お飲み物'
            );

            // タクソノミーからカテゴリーを表示
            if (!empty($terms) && !is_wp_error($terms)) {
                foreach ($terms as $term) {
                    echo '<option value="' . esc_attr($term->slug) . '" ' . selected($category, $term->slug, false) . '>' . esc_html($term->name) . '</option>';
                }
            } else {
                // タクソノミーが空の場合はデフォルトカテゴリーを表示
                foreach ($default_categories as $slug => $name) {
                    echo '<option value="' . esc_attr($slug) . '" ' . selected($category, $slug, false) . '>' . esc_html($name) . '</option>';
                }
            }
            ?>
        </select>
        <p class="menu-help">カテゴリーは「塩町店 お品書き」→「メニューカテゴリー」から追加・編集できます。</p>
    </div>
    <?php
}

// メタボックスのデータを保存
function washouen_save_menu_meta($post_id) {
    // nonce検証
    if (!isset($_POST['washouen_menu_nonce']) || !wp_verify_nonce($_POST['washouen_menu_nonce'], 'washouen_save_menu_meta')) {
        return;
    }
    
    // 自動保存の場合は処理しない
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // 権限チェック
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // データの保存
    if (isset($_POST['menu_price'])) {
        update_post_meta($post_id, '_menu_price', sanitize_text_field($_POST['menu_price']));
    }
    
    if (isset($_POST['menu_description'])) {
        update_post_meta($post_id, '_menu_description', sanitize_textarea_field($_POST['menu_description']));
    }
    
    if (isset($_POST['menu_category'])) {
        update_post_meta($post_id, '_menu_category', sanitize_text_field($_POST['menu_category']));
        
        // カテゴリータクソノミーも更新
        $post_type = get_post_type($post_id);
        if ($post_type == 'fukunaka_menu') {
            wp_set_object_terms($post_id, $_POST['menu_category'], 'fukunaka_category');
        } elseif ($post_type == 'shiomachi_menu') {
            wp_set_object_terms($post_id, $_POST['menu_category'], 'shiomachi_category');
        }
    }
    
    // 福中店用フィールド
    $is_seasonal = isset($_POST['menu_is_seasonal']) ? '1' : '0';
    update_post_meta($post_id, '_menu_is_seasonal', $is_seasonal);
    
    // 塩町店用フィールド
    if (isset($_POST['menu_origin'])) {
        update_post_meta($post_id, '_menu_origin', sanitize_text_field($_POST['menu_origin']));
    }
}
add_action('save_post_fukunaka_menu', 'washouen_save_menu_meta');
add_action('save_post_shiomachi_menu', 'washouen_save_menu_meta');

// 管理画面の一覧にカスタムカラムを追加
function washouen_add_menu_columns($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key == 'title') {
            $new_columns['price'] = '価格';
            $new_columns['category'] = 'カテゴリー';
        }
    }
    return $new_columns;
}
add_filter('manage_fukunaka_menu_posts_columns', 'washouen_add_menu_columns');
add_filter('manage_shiomachi_menu_posts_columns', 'washouen_add_menu_columns');

// カスタムカラムにデータを表示
function washouen_show_menu_columns($column, $post_id) {
    switch ($column) {
        case 'price':
            $price = get_post_meta($post_id, '_menu_price', true);
            echo $price ? '¥' . esc_html($price) : '—';
            break;
        case 'category':
            $category = get_post_meta($post_id, '_menu_category', true);
            $categories = array(
                'course' => 'コース料理',
                'sashimi' => 'お造り',
                'grilled' => '焼き物',
                'simmered' => '煮付け',
                'fried' => '揚げ物',
                'special' => '季節の特選料理',
                'nigiri' => '握り',
                'gunkan' => '軍艦・巻物',
                'chirashi' => 'ちらし・丼',
                'omakase' => 'おまかせコース',
                'side' => '一品料理',
                'drink' => 'お飲み物'
            );
            echo isset($categories[$category]) ? esc_html($categories[$category]) : '—';
            break;
    }
}
add_action('manage_fukunaka_menu_posts_custom_column', 'washouen_show_menu_columns', 10, 2);
add_action('manage_shiomachi_menu_posts_custom_column', 'washouen_show_menu_columns', 10, 2);

// ソート可能にする
function washouen_sortable_menu_columns($columns) {
    $columns['price'] = 'price';
    $columns['category'] = 'category';
    return $columns;
}
add_filter('manage_edit-fukunaka_menu_sortable_columns', 'washouen_sortable_menu_columns');
add_filter('manage_edit-shiomachi_menu_sortable_columns', 'washouen_sortable_menu_columns');

// カスタムカラムのソート処理
function washouen_menu_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    
    if ($query->get('orderby') == 'price') {
        $query->set('meta_key', '_menu_price');
        $query->set('orderby', 'meta_value_num');
    }
    
    if ($query->get('orderby') == 'category') {
        $query->set('meta_key', '_menu_category');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_posts', 'washouen_menu_orderby');

// 管理画面でソート用JavaScriptを読み込む
function washouen_admin_menu_scripts($hook) {
    global $post_type;
    
    if (($post_type == 'fukunaka_menu' || $post_type == 'shiomachi_menu') && $hook == 'edit.php') {
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script(
            'washouen-admin-menu-sort',
            get_template_directory_uri() . '/js/admin-menu-sort.js',
            array('jquery', 'jquery-ui-sortable'),
            '1.0.0',
            true
        );
        
        wp_localize_script('washouen-admin-menu-sort', 'washouen_admin', array(
            'nonce' => wp_create_nonce('update_menu_order')
        ));
    }
}
add_action('admin_enqueue_scripts', 'washouen_admin_menu_scripts');

// Ajaxでメニューの並び順を更新
function washouen_update_menu_order() {
    // nonce検証
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'update_menu_order')) {
        wp_die('Security check failed');
    }
    
    // 権限チェック
    if (!current_user_can('edit_posts')) {
        wp_die('Permission denied');
    }
    
    $order = $_POST['order'];
    
    if (is_array($order)) {
        foreach ($order as $position => $post_id) {
            // post-123 形式からIDを抽出
            $post_id = str_replace('post-', '', $post_id);
            
            // menu_orderを更新
            wp_update_post(array(
                'ID' => intval($post_id),
                'menu_order' => $position
            ));
        }
        
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}
add_action('wp_ajax_update_menu_order', 'washouen_update_menu_order');

// メニュー一覧のデフォルトソートをmenu_orderに設定
function washouen_menu_default_order($query) {
    if (is_admin() && $query->is_main_query()) {
        $post_type = $query->get('post_type');
        
        if ($post_type == 'fukunaka_menu' || $post_type == 'shiomachi_menu') {
            // orderbyが指定されていない場合のみ
            if (!$query->get('orderby')) {
                $query->set('orderby', 'menu_order');
                $query->set('order', 'ASC');
            }
        }
    }
}
add_action('pre_get_posts', 'washouen_menu_default_order', 20);