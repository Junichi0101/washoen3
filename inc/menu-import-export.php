<?php
/**
 * メニューインポート/エクスポート機能
 * 
 * @package Washouen
 * @since 1.0.0
 */

// 管理画面にインポート/エクスポートメニューを追加
function washouen_add_import_export_menu() {
    add_submenu_page(
        'edit.php?post_type=fukunaka_menu',
        'インポート/エクスポート',
        'インポート/エクスポート',
        'manage_options',
        'fukunaka-import-export',
        'washouen_fukunaka_import_export_page'
    );
    
    add_submenu_page(
        'edit.php?post_type=shiomachi_menu',
        'インポート/エクスポート',
        'インポート/エクスポート',
        'manage_options',
        'shiomachi-import-export',
        'washouen_shiomachi_import_export_page'
    );
}
add_action('admin_menu', 'washouen_add_import_export_menu');

// 福中店インポート/エクスポートページ
function washouen_fukunaka_import_export_page() {
    washouen_import_export_page_content('fukunaka_menu', '福中店');
}

// 塩町店インポート/エクスポートページ
function washouen_shiomachi_import_export_page() {
    washouen_import_export_page_content('shiomachi_menu', '塩町店');
}

// インポート/エクスポートページの共通コンテンツ
function washouen_import_export_page_content($post_type, $store_name) {
    // エクスポート処理
    if (isset($_POST['export']) && wp_verify_nonce($_POST['export_nonce'], 'export_menu_' . $post_type)) {
        washouen_export_menu_data($post_type, $store_name);
        exit;
    }
    
    // インポート処理
    $import_message = '';
    if (isset($_POST['import']) && wp_verify_nonce($_POST['import_nonce'], 'import_menu_' . $post_type)) {
        $result = washouen_import_menu_data($post_type);
        if ($result['success']) {
            $import_message = '<div class="notice notice-success"><p>' . $result['message'] . '</p></div>';
        } else {
            $import_message = '<div class="notice notice-error"><p>' . $result['message'] . '</p></div>';
        }
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html($store_name); ?>メニュー - インポート/エクスポート</h1>
        
        <?php echo $import_message; ?>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>エクスポート</h2>
            <p>現在の<?php echo esc_html($store_name); ?>メニューデータをJSON形式でエクスポートします。</p>
            <form method="post">
                <?php wp_nonce_field('export_menu_' . $post_type, 'export_nonce'); ?>
                <p class="submit">
                    <input type="submit" name="export" class="button-primary" value="メニューデータをエクスポート">
                </p>
            </form>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>インポート</h2>
            <p>JSON形式のメニューデータをインポートします。既存のデータは削除されません。</p>
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('import_menu_' . $post_type, 'import_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="import_file">インポートファイル</label></th>
                        <td>
                            <input type="file" name="import_file" id="import_file" accept=".json" required>
                            <p class="description">エクスポートしたJSONファイルを選択してください。</p>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="import" class="button-primary" value="メニューデータをインポート">
                </p>
            </form>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>使い方</h2>
            <ol>
                <li><strong>エクスポート:</strong> 現在のメニューデータをバックアップまたは他のサイトに移行する際に使用します。</li>
                <li><strong>インポート:</strong> 以前エクスポートしたデータや、他のサイトからのデータを読み込みます。</li>
                <li>インポートは既存のデータに追加されます。重複を避けたい場合は、事前に既存データを削除してください。</li>
            </ol>
        </div>
    </div>
    <?php
}

// エクスポート処理
function washouen_export_menu_data($post_type, $store_name) {
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );
    
    $menus = get_posts($args);
    $export_data = array(
        'version' => '1.0',
        'post_type' => $post_type,
        'store_name' => $store_name,
        'export_date' => current_time('mysql'),
        'menus' => array()
    );
    
    foreach ($menus as $menu) {
        $menu_data = array(
            'title' => $menu->post_title,
            'content' => $menu->post_content,
            'menu_order' => $menu->menu_order,
            'meta' => array(
                'price' => get_post_meta($menu->ID, '_menu_price', true),
                'description' => get_post_meta($menu->ID, '_menu_description', true),
                'category' => get_post_meta($menu->ID, '_menu_category', true)
            )
        );
        
        // 福中店特有のフィールド
        if ($post_type == 'fukunaka_menu') {
            $menu_data['meta']['is_seasonal'] = get_post_meta($menu->ID, '_menu_is_seasonal', true);
        }
        
        // 塩町店特有のフィールド
        if ($post_type == 'shiomachi_menu') {
            $menu_data['meta']['origin'] = get_post_meta($menu->ID, '_menu_origin', true);
        }
        
        // カテゴリー
        $taxonomy = $post_type == 'fukunaka_menu' ? 'fukunaka_category' : 'shiomachi_category';
        $terms = wp_get_post_terms($menu->ID, $taxonomy, array('fields' => 'slugs'));
        if (!empty($terms)) {
            $menu_data['category_terms'] = $terms;
        }
        
        // アイキャッチ画像
        if (has_post_thumbnail($menu->ID)) {
            $thumbnail_id = get_post_thumbnail_id($menu->ID);
            $thumbnail_url = wp_get_attachment_url($thumbnail_id);
            if ($thumbnail_url) {
                $menu_data['thumbnail_url'] = $thumbnail_url;
            }
        }
        
        $export_data['menus'][] = $menu_data;
    }
    
    // JSONとして出力
    $filename = sanitize_file_name($store_name . '_menu_' . date('Y-m-d') . '.json');
    
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    echo json_encode($export_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

// インポート処理
function washouen_import_menu_data($post_type) {
    if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
        return array('success' => false, 'message' => 'ファイルのアップロードに失敗しました。');
    }
    
    $file_content = file_get_contents($_FILES['import_file']['tmp_name']);
    $import_data = json_decode($file_content, true);
    
    if (!$import_data || !isset($import_data['menus'])) {
        return array('success' => false, 'message' => '無効なファイル形式です。');
    }
    
    if ($import_data['post_type'] !== $post_type) {
        return array('success' => false, 'message' => '異なる店舗のデータです。正しいファイルを選択してください。');
    }
    
    $imported_count = 0;
    $taxonomy = $post_type == 'fukunaka_menu' ? 'fukunaka_category' : 'shiomachi_category';
    
    foreach ($import_data['menus'] as $menu_data) {
        $post_id = wp_insert_post(array(
            'post_title' => $menu_data['title'],
            'post_content' => isset($menu_data['content']) ? $menu_data['content'] : '',
            'post_type' => $post_type,
            'post_status' => 'publish',
            'menu_order' => isset($menu_data['menu_order']) ? $menu_data['menu_order'] : 0
        ));
        
        if ($post_id) {
            // メタデータの保存
            if (isset($menu_data['meta'])) {
                foreach ($menu_data['meta'] as $key => $value) {
                    update_post_meta($post_id, '_menu_' . $key, $value);
                }
            }
            
            // カテゴリーの設定
            if (isset($menu_data['category_terms']) && is_array($menu_data['category_terms'])) {
                wp_set_object_terms($post_id, $menu_data['category_terms'], $taxonomy);
            } elseif (isset($menu_data['meta']['category'])) {
                wp_set_object_terms($post_id, $menu_data['meta']['category'], $taxonomy);
            }
            
            // アイキャッチ画像の処理（URLが提供されている場合）
            if (isset($menu_data['thumbnail_url'])) {
                // 画像のダウンロードと添付は複雑なため、ここでは省略
                // 実装する場合は media_sideload_image() 関数を使用
            }
            
            $imported_count++;
        }
    }
    
    return array(
        'success' => true,
        'message' => $imported_count . '件のメニューをインポートしました。'
    );
}