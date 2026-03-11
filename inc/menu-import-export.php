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

// エクスポート処理を早期に実行（admin_initフック）
function washouen_handle_menu_export() {
    // 管理画面以外は無視
    if (!is_admin()) {
        return;
    }

    // 権限チェック
    if (!current_user_can('manage_options')) {
        return;
    }

    // エクスポートリクエストかチェック
    $is_json_export = isset($_POST['export']);
    $is_csv_export = isset($_POST['export_csv']);

    if (!$is_json_export && !$is_csv_export) {
        return;
    }

    // どちらの店舗かを判定
    $page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';

    if ($page === 'fukunaka-import-export') {
        $post_type = 'fukunaka_menu';
        $store_name = '福中店';
    } elseif ($page === 'shiomachi-import-export') {
        $post_type = 'shiomachi_menu';
        $store_name = '塩町店';
    } else {
        return;
    }

    // Nonceチェック
    if (!isset($_POST['export_nonce']) || !wp_verify_nonce($_POST['export_nonce'], 'export_menu_' . $post_type)) {
        return;
    }

    // カテゴリーフィルター（複数選択対応）
    $category_slugs = array();
    if (isset($_POST['export_categories']) && is_array($_POST['export_categories'])) {
        $category_slugs = array_map('sanitize_text_field', $_POST['export_categories']);
    }

    // エクスポート実行
    if ($is_csv_export) {
        washouen_export_menu_csv($post_type, $store_name, $category_slugs);
    } else {
        washouen_export_menu_data($post_type, $store_name, $category_slugs);
    }
    exit;
}
add_action('admin_init', 'washouen_handle_menu_export');

// インポート処理を早期に実行（admin_initフック）
function washouen_handle_menu_import() {
    // 管理画面以外は無視
    if (!is_admin()) {
        return;
    }

    // 権限チェック
    if (!current_user_can('manage_options')) {
        return;
    }

    // インポートリクエストかチェック
    if (!isset($_POST['import'])) {
        return;
    }

    // どちらの店舗かを判定
    $page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';

    if ($page === 'fukunaka-import-export') {
        $post_type = 'fukunaka_menu';
    } elseif ($page === 'shiomachi-import-export') {
        $post_type = 'shiomachi_menu';
    } else {
        return;
    }

    // Nonceチェック
    if (!isset($_POST['import_nonce']) || !wp_verify_nonce($_POST['import_nonce'], 'import_menu_' . $post_type)) {
        return;
    }

    // インポートモードを取得
    $import_mode = isset($_POST['import_mode']) ? sanitize_text_field($_POST['import_mode']) : 'add';

    // インポート実行
    $result = washouen_import_menu_data($post_type, $import_mode);

    // 結果をトランジェントに保存してリダイレクト
    set_transient('washouen_import_result_' . $post_type, $result, 60);

    // リダイレクト（結果を表示するため）
    $redirect_url = admin_url('edit.php?post_type=' . $post_type . '&page=' . $page . '&imported=1');
    wp_safe_redirect($redirect_url);
    exit;
}
add_action('admin_init', 'washouen_handle_menu_import');

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
    // インポート結果をトランジェントから取得
    $import_message = '';
    if (isset($_GET['imported'])) {
        $result = get_transient('washouen_import_result_' . $post_type);
        if ($result) {
            delete_transient('washouen_import_result_' . $post_type);
            if ($result['success']) {
                $import_message = '<div class="notice notice-success"><p>' . esc_html($result['message']) . '</p></div>';
            } else {
                $import_message = '<div class="notice notice-error"><p>' . esc_html($result['message']) . '</p></div>';
            }
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
                <table class="form-table">
                    <tr>
                        <th scope="row">カテゴリー</th>
                        <td>
                            <?php
                            $taxonomy = $post_type == 'fukunaka_menu' ? 'fukunaka_category' : 'shiomachi_category';
                            $categories = get_terms(array(
                                'taxonomy' => $taxonomy,
                                'hide_empty' => false,
                                'orderby' => 'term_order',
                                'order' => 'ASC'
                            ));
                            ?>
                            <fieldset>
                                <label style="display: block; margin-bottom: 8px;">
                                    <input type="checkbox" id="export_all_categories" checked>
                                    <strong>すべてのカテゴリー</strong>
                                </label>
                                <div id="category_checkboxes" style="margin-left: 20px; display: none;">
                                    <?php if (!is_wp_error($categories) && !empty($categories)) : ?>
                                        <?php foreach ($categories as $category) : ?>
                                            <label style="display: block; margin-bottom: 5px;">
                                                <input type="checkbox" name="export_categories[]" value="<?php echo esc_attr($category->slug); ?>" class="category-checkbox">
                                                <?php echo esc_html($category->name); ?>
                                                <span style="color: #666;">(<?php echo esc_html($category->count); ?>件)</span>
                                            </label>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </fieldset>
                            <p class="description">エクスポートするカテゴリーを選択してください。</p>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input type="submit" name="export" class="button-primary" value="JSON形式でエクスポート">
                    <input type="submit" name="export_csv" class="button-secondary" value="CSV形式でエクスポート" style="margin-left: 10px;">
                </p>
            </form>
            <script>
            (function() {
                var allCheck = document.getElementById('export_all_categories');
                var categoryDiv = document.getElementById('category_checkboxes');
                var categoryBoxes = document.querySelectorAll('.category-checkbox');

                allCheck.addEventListener('change', function() {
                    if (this.checked) {
                        categoryDiv.style.display = 'none';
                        categoryBoxes.forEach(function(box) {
                            box.checked = false;
                        });
                    } else {
                        categoryDiv.style.display = 'block';
                    }
                });

                categoryBoxes.forEach(function(box) {
                    box.addEventListener('change', function() {
                        var anyChecked = Array.from(categoryBoxes).some(function(b) { return b.checked; });
                        if (anyChecked) {
                            allCheck.checked = false;
                        }
                    });
                });
            })();
            </script>
        </div>
        
        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2>インポート</h2>
            <p>JSON形式またはCSV形式のメニューデータをインポートします。既存のデータは削除されません。</p>
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('import_menu_' . $post_type, 'import_nonce'); ?>
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="import_file">インポートファイル</label></th>
                        <td>
                            <input type="file" name="import_file" id="import_file" accept=".json,.csv" required>
                            <p class="description">エクスポートしたJSON形式またはCSV形式のファイルを選択してください。ファイル形式は自動判定されます。</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">インポートモード</th>
                        <td>
                            <fieldset>
                                <label style="display: block; margin-bottom: 8px;">
                                    <input type="radio" name="import_mode" value="add" checked>
                                    <strong>追加モード</strong> - 既存データはそのまま、新しいメニューを追加
                                </label>
                                <label style="display: block;">
                                    <input type="radio" name="import_mode" value="update">
                                    <strong>上書きモード</strong> - 同じタイトルのメニューがあれば更新、なければ追加
                                </label>
                            </fieldset>
                            <p class="description">上書きモードは同じタイトルのメニューを更新します。重複を防ぎたい場合に便利です。</p>
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
function washouen_export_menu_data($post_type, $store_name, $category_slugs = array()) {
    $taxonomy = $post_type == 'fukunaka_menu' ? 'fukunaka_category' : 'shiomachi_category';

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );

    // カテゴリーフィルターが指定されている場合（複数対応）
    $category_names = array();
    if (!empty($category_slugs) && is_array($category_slugs)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => $category_slugs
            )
        );
        // カテゴリー名を取得
        foreach ($category_slugs as $slug) {
            $term = get_term_by('slug', $slug, $taxonomy);
            if ($term) {
                $category_names[] = $term->name;
            }
        }
    }

    $menus = get_posts($args);
    $export_data = array(
        'version' => '1.0',
        'post_type' => $post_type,
        'store_name' => $store_name,
        'category_slugs' => $category_slugs,
        'category_names' => $category_names,
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

        // 共通フィールド：季節限定タグ
        $seasonal_type = get_post_meta($menu->ID, '_menu_seasonal_type', true);
        if ($seasonal_type) {
            $menu_data['meta']['seasonal_type'] = $seasonal_type;
        }
        // 後方互換性のため is_seasonal も保持
        $menu_data['meta']['is_seasonal'] = get_post_meta($menu->ID, '_menu_is_seasonal', true);

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
    if (!empty($category_names)) {
        // 複数カテゴリの場合は「_」で連結、長すぎる場合は件数表示
        if (count($category_names) <= 2) {
            $category_str = implode('_', $category_names);
        } else {
            $category_str = $category_names[0] . '_他' . (count($category_names) - 1) . '件';
        }
        $filename = sanitize_file_name($store_name . '_' . $category_str . '_' . date('Y-m-d') . '.json');
    } else {
        $filename = sanitize_file_name($store_name . '_menu_' . date('Y-m-d') . '.json');
    }
    
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    echo json_encode($export_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}

// インポート処理
function washouen_import_menu_data($post_type, $import_mode = 'add') {
    if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
        return array('success' => false, 'message' => 'ファイルのアップロードに失敗しました。');
    }

    // ファイル拡張子を取得して形式を判定
    $file_name = $_FILES['import_file']['name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if ($file_ext === 'csv') {
        // CSVインポート
        return washouen_import_menu_csv($post_type, $import_mode);
    } elseif ($file_ext === 'json') {
        // JSONインポート（既存の処理）
        return washouen_import_menu_json($post_type, $import_mode);
    } else {
        return array('success' => false, 'message' => 'サポートされていないファイル形式です。JSON形式またはCSV形式のファイルを選択してください。');
    }
}

// JSONインポート処理（既存の処理を分離）
function washouen_import_menu_json($post_type, $import_mode = 'add') {
    $file_content = file_get_contents($_FILES['import_file']['tmp_name']);
    $import_data = json_decode($file_content, true);

    if (!$import_data || !isset($import_data['menus'])) {
        return array('success' => false, 'message' => '無効なJSON形式です。');
    }

    if ($import_data['post_type'] !== $post_type) {
        return array('success' => false, 'message' => '異なる店舗のデータです。正しいファイルを選択してください。');
    }
    
    $imported_count = 0;
    $updated_count = 0;
    $taxonomy = $post_type == 'fukunaka_menu' ? 'fukunaka_category' : 'shiomachi_category';

    foreach ($import_data['menus'] as $menu_data) {
        $post_id = null;

        // 上書きモードの場合、既存の投稿を検索
        if ($import_mode === 'update') {
            $existing_posts = get_posts(array(
                'post_type' => $post_type,
                'title' => $menu_data['title'],
                'posts_per_page' => 1,
                'fields' => 'ids'
            ));

            if (!empty($existing_posts)) {
                $post_id = $existing_posts[0];
                // 既存の投稿を更新
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_title' => $menu_data['title'],
                    'post_content' => isset($menu_data['content']) ? $menu_data['content'] : '',
                    'menu_order' => isset($menu_data['menu_order']) ? $menu_data['menu_order'] : 0
                ));
                $updated_count++;
            }
        }

        // 新規投稿を作成（追加モード、または上書きモードで既存が見つからない場合）
        if (!$post_id) {
            $post_id = wp_insert_post(array(
                'post_title' => $menu_data['title'],
                'post_content' => isset($menu_data['content']) ? $menu_data['content'] : '',
                'post_type' => $post_type,
                'post_status' => 'publish',
                'menu_order' => isset($menu_data['menu_order']) ? $menu_data['menu_order'] : 0
            ));
        }
        
        if ($post_id) {
            // メタデータの保存
            if (isset($menu_data['meta'])) {
                foreach ($menu_data['meta'] as $key => $value) {
                    update_post_meta($post_id, '_menu_' . $key, $value);
                }

                // 後方互換性: is_seasonalのみでseasonal_typeがない場合
                if (!isset($menu_data['meta']['seasonal_type']) && isset($menu_data['meta']['is_seasonal']) && $menu_data['meta']['is_seasonal'] == '1') {
                    update_post_meta($post_id, '_menu_seasonal_type', 'seasonal');
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
    
    $message = '';
    if ($import_mode === 'update' && $updated_count > 0) {
        $message = $imported_count . '件のメニューをインポートしました（新規: ' . ($imported_count - $updated_count) . '件、更新: ' . $updated_count . '件）。';
    } else {
        $message = $imported_count . '件のメニューをインポートしました。';
    }

    return array(
        'success' => true,
        'message' => $message
    );
}

// CSVエクスポート処理
function washouen_export_menu_csv($post_type, $store_name, $category_slugs = array()) {
    $taxonomy = $post_type == 'fukunaka_menu' ? 'fukunaka_category' : 'shiomachi_category';

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC'
    );

    // カテゴリーフィルター
    $category_names = array();
    if (!empty($category_slugs) && is_array($category_slugs)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => $taxonomy,
                'field' => 'slug',
                'terms' => $category_slugs
            )
        );
        foreach ($category_slugs as $slug) {
            $term = get_term_by('slug', $slug, $taxonomy);
            if ($term) {
                $category_names[] = $term->name;
            }
        }
    }

    $menus = get_posts($args);

    // ファイル名を生成
    if (!empty($category_names)) {
        if (count($category_names) <= 2) {
            $category_str = implode('_', $category_names);
        } else {
            $category_str = $category_names[0] . '_他' . (count($category_names) - 1) . '件';
        }
        $filename = sanitize_file_name($store_name . '_' . $category_str . '_' . date('Y-m-d') . '.csv');
    } else {
        $filename = sanitize_file_name($store_name . '_menu_' . date('Y-m-d') . '.csv');
    }

    // CSVヘッダーを設定
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // BOM（UTF-8）を出力（Excelで正しく開くため）
    echo "\xEF\xBB\xBF";

    // 出力バッファを開く
    $output = fopen('php://output', 'w');

    // ヘッダー行を書き込み
    $headers = array('タイトル', '価格', '説明', 'カテゴリー', '季節限定タグ', '表示順序');
    if ($post_type == 'shiomachi_menu') {
        $headers[] = '産地';
    }
    fputcsv($output, $headers);

    // データ行を書き込み
    foreach ($menus as $menu) {
        $price = get_post_meta($menu->ID, '_menu_price', true);
        $description = get_post_meta($menu->ID, '_menu_description', true);
        $category = get_post_meta($menu->ID, '_menu_category', true);
        $seasonal_type = get_post_meta($menu->ID, '_menu_seasonal_type', true);

        // 季節限定タグを日本語に変換
        $seasonal_label = '';
        switch ($seasonal_type) {
            case 'seasonal':
                $seasonal_label = '季節限定';
                break;
            case 'summer':
                $seasonal_label = '夏季限定';
                break;
            case 'winter':
                $seasonal_label = '冬季限定';
                break;
        }

        $row = array(
            $menu->post_title,
            $price,
            $description,
            $category,
            $seasonal_label,
            $menu->menu_order
        );

        if ($post_type == 'shiomachi_menu') {
            $origin = get_post_meta($menu->ID, '_menu_origin', true);
            $row[] = $origin;
        }

        fputcsv($output, $row);
    }

    fclose($output);
}

// CSVインポート処理
function washouen_import_menu_csv($post_type, $import_mode = 'add') {
    $file_path = $_FILES['import_file']['tmp_name'];

    // ファイルを開く
    $handle = fopen($file_path, 'r');
    if (!$handle) {
        return array('success' => false, 'message' => 'CSVファイルを開けませんでした。');
    }

    $imported_count = 0;
    $updated_count = 0;
    $taxonomy = $post_type == 'fukunaka_menu' ? 'fukunaka_category' : 'shiomachi_category';

    // BOMをスキップ
    $bom = fread($handle, 3);
    if ($bom !== "\xEF\xBB\xBF") {
        rewind($handle);
    }

    // ヘッダー行を読み込んで列のインデックスを取得
    $headers = fgetcsv($handle);
    if (!$headers) {
        fclose($handle);
        return array('success' => false, 'message' => 'CSVファイルが空です。');
    }

    // ヘッダーから列のインデックスを取得
    $column_map = array();
    foreach ($headers as $index => $header) {
        $column_map[trim($header)] = $index;
    }

    // 必須列のチェック
    $required_columns = array('タイトル');
    foreach ($required_columns as $col) {
        if (!isset($column_map[$col])) {
            fclose($handle);
            return array('success' => false, 'message' => '必須列「' . $col . '」が見つかりません。');
        }
    }

    // データ行を処理
    $line_number = 1; // ヘッダー行の次から
    while (($row = fgetcsv($handle)) !== false) {
        $line_number++;

        // 空行をスキップ
        if (empty(array_filter($row))) {
            continue;
        }

        // タイトルが空の場合はスキップ
        $title = isset($column_map['タイトル']) && isset($row[$column_map['タイトル']]) ? trim($row[$column_map['タイトル']]) : '';
        if (empty($title)) {
            continue;
        }

        // データを取得
        $price = isset($column_map['価格']) && isset($row[$column_map['価格']]) ? trim($row[$column_map['価格']]) : '';
        $description = isset($column_map['説明']) && isset($row[$column_map['説明']]) ? trim($row[$column_map['説明']]) : '';
        $category = isset($column_map['カテゴリー']) && isset($row[$column_map['カテゴリー']]) ? trim($row[$column_map['カテゴリー']]) : '';
        $seasonal_label = isset($column_map['季節限定タグ']) && isset($row[$column_map['季節限定タグ']]) ? trim($row[$column_map['季節限定タグ']]) : '';
        $menu_order = isset($column_map['表示順序']) && isset($row[$column_map['表示順序']]) ? intval($row[$column_map['表示順序']]) : 0;
        $origin = '';
        if ($post_type == 'shiomachi_menu' && isset($column_map['産地']) && isset($row[$column_map['産地']])) {
            $origin = trim($row[$column_map['産地']]);
        }

        // 季節限定タグを内部コードに変換
        $seasonal_type = '';
        switch ($seasonal_label) {
            case '季節限定':
                $seasonal_type = 'seasonal';
                break;
            case '夏季限定':
                $seasonal_type = 'summer';
                break;
            case '冬季限定':
                $seasonal_type = 'winter';
                break;
        }

        $post_id = null;

        // 上書きモードの場合、既存の投稿を検索
        if ($import_mode === 'update') {
            $existing_posts = get_posts(array(
                'post_type' => $post_type,
                'title' => $title,
                'posts_per_page' => 1,
                'fields' => 'ids'
            ));

            if (!empty($existing_posts)) {
                $post_id = $existing_posts[0];
                // 既存の投稿を更新
                wp_update_post(array(
                    'ID' => $post_id,
                    'post_title' => $title,
                    'menu_order' => $menu_order
                ));
                $updated_count++;
            }
        }

        // 新規投稿を作成
        if (!$post_id) {
            $post_id = wp_insert_post(array(
                'post_title' => $title,
                'post_type' => $post_type,
                'post_status' => 'publish',
                'menu_order' => $menu_order
            ));
        }

        if ($post_id && !is_wp_error($post_id)) {
            // メタデータを保存（空の値でも更新して既存データをクリアできるようにする）
            update_post_meta($post_id, '_menu_price', $price);
            update_post_meta($post_id, '_menu_description', $description);

            if (!empty($category)) {
                update_post_meta($post_id, '_menu_category', $category);
                wp_set_object_terms($post_id, $category, $taxonomy);
            }

            // 季節限定タグ
            if (!empty($seasonal_type)) {
                update_post_meta($post_id, '_menu_seasonal_type', $seasonal_type);
                update_post_meta($post_id, '_menu_is_seasonal', '1');
            } else {
                update_post_meta($post_id, '_menu_seasonal_type', '');
                update_post_meta($post_id, '_menu_is_seasonal', '0');
            }

            // 産地（塩町店のみ）
            if ($post_type == 'shiomachi_menu') {
                update_post_meta($post_id, '_menu_origin', $origin);
            }

            $imported_count++;
        }
    }

    fclose($handle);

    $message = '';
    if ($import_mode === 'update' && $updated_count > 0) {
        $message = $imported_count . '件のメニューをインポートしました（新規: ' . ($imported_count - $updated_count) . '件、更新: ' . $updated_count . '件）。';
    } else {
        $message = $imported_count . '件のメニューをインポートしました。';
    }

    return array(
        'success' => true,
        'message' => $message
    );
}