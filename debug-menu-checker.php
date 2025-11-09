<?php
/**
 * メニュー表示デバッグスクリプト
 * URL: http://localhost/my_blog/wp-content/themes/washoen3/debug-menu.php
 */

// WordPressの環境を読み込み
require_once(__DIR__ . '/../../../wp-load.php');

// 管理者権限チェック
if (!current_user_can('manage_options')) {
    die('<h1>エラー</h1><p>管理者権限が必要です。WordPressにログインしてください。</p>');
}

echo '<html><head><meta charset="UTF-8"><title>メニュー表示デバッグ</title>';
echo '<style>
    body { font-family: sans-serif; padding: 20px; }
    h1 { color: #333; }
    h2 { color: #666; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
    .success { color: green; }
    .error { color: red; }
    .info { background: #f0f0f0; padding: 10px; margin: 10px 0; }
    table { border-collapse: collapse; width: 100%; margin: 10px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background: #f9f9f9; }
</style></head><body>';

echo '<h1>福中店メニュー表示デバッグ</h1>';

// 1. カスタム投稿タイプの確認
echo '<h2>1. カスタム投稿タイプの登録状況</h2>';
$post_types = get_post_types(array('name' => 'fukunaka_menu'), 'objects');
if (!empty($post_types)) {
    echo '<p class="success">✅ fukunaka_menu は登録されています</p>';
} else {
    echo '<p class="error">❌ fukunaka_menu が登録されていません</p>';
}

// 2. タクソノミーの確認
echo '<h2>2. タクソノミーの登録状況</h2>';
$taxonomies = get_taxonomies(array('name' => 'fukunaka_category'), 'objects');
if (!empty($taxonomies)) {
    echo '<p class="success">✅ fukunaka_category は登録されています</p>';
} else {
    echo '<p class="error">❌ fukunaka_category が登録されていません</p>';
}

// 3. カテゴリーの一覧
echo '<h2>3. 登録されているカテゴリー</h2>';
$terms = get_terms(array(
    'taxonomy' => 'fukunaka_category',
    'hide_empty' => false,
));

if (!empty($terms) && !is_wp_error($terms)) {
    echo '<table>';
    echo '<tr><th>ID</th><th>名前</th><th>スラッグ</th><th>説明</th><th>投稿数</th></tr>';
    foreach ($terms as $term) {
        echo '<tr>';
        echo '<td>' . $term->term_id . '</td>';
        echo '<td>' . esc_html($term->name) . '</td>';
        echo '<td>' . esc_html($term->slug) . '</td>';
        echo '<td>' . esc_html($term->description) . '</td>';
        echo '<td>' . $term->count . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p class="error">❌ カテゴリーが登録されていません</p>';
    echo '<p>デフォルトカテゴリーを作成する必要があります。</p>';
}

// 4. メニュー項目の一覧
echo '<h2>4. メニュー項目の一覧</h2>';
$menu_items = get_posts(array(
    'post_type' => 'fukunaka_menu',
    'posts_per_page' => -1,
    'post_status' => array('publish', 'draft', 'pending'),
));

if (!empty($menu_items)) {
    echo '<p class="success">✅ ' . count($menu_items) . '件のメニュー項目があります</p>';
    echo '<table>';
    echo '<tr><th>ID</th><th>タイトル</th><th>ステータス</th><th>カテゴリー</th></tr>';

    foreach ($menu_items as $item) {
        $categories = wp_get_post_terms($item->ID, 'fukunaka_category');
        $cat_names = array();
        if (!empty($categories)) {
            foreach ($categories as $cat) {
                $cat_names[] = $cat->name;
            }
        }

        echo '<tr>';
        echo '<td>' . $item->ID . '</td>';
        echo '<td>' . esc_html($item->post_title) . '</td>';
        echo '<td>' . $item->post_status . '</td>';
        echo '<td>' . (!empty($cat_names) ? implode(', ', $cat_names) : '<span class="error">未設定</span>') . '</td>';
        echo '</tr>';
    }
    echo '</table>';
} else {
    echo '<p class="error">❌ メニュー項目がありません</p>';
}

// 5. カテゴリー別のメニュー項目数
echo '<h2>5. カテゴリー別メニュー項目数</h2>';
if (!empty($terms) && !is_wp_error($terms)) {
    echo '<table>';
    echo '<tr><th>カテゴリー</th><th>スラッグ</th><th>メニュー項目数</th><th>メニュー項目</th></tr>';

    foreach ($terms as $term) {
        $items = get_posts(array(
            'post_type' => 'fukunaka_menu',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'tax_query' => array(
                array(
                    'taxonomy' => 'fukunaka_category',
                    'field' => 'slug',
                    'terms' => $term->slug,
                ),
            ),
        ));

        $item_titles = array();
        foreach ($items as $item) {
            $item_titles[] = $item->post_title;
        }

        echo '<tr>';
        echo '<td>' . esc_html($term->name) . '</td>';
        echo '<td>' . esc_html($term->slug) . '</td>';
        echo '<td>' . count($items) . '</td>';
        echo '<td>' . (!empty($item_titles) ? implode(', ', $item_titles) : '-') . '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

// 6. 推奨アクション
echo '<h2>6. 推奨アクション</h2>';
echo '<ol>';
echo '<li><a href="' . admin_url('edit.php?post_type=fukunaka_menu') . '" target="_blank">メニュー項目一覧を確認</a></li>';
echo '<li><a href="' . admin_url('edit-tags.php?taxonomy=fukunaka_category&post_type=fukunaka_menu') . '" target="_blank">メニューカテゴリー一覧を確認</a></li>';
echo '<li><a href="' . home_url('/fukunaka-menu/') . '" target="_blank">福中店お品書きページを表示</a></li>';
echo '<li><a href="refresh-menu.php" target="_blank">refresh-menu.phpを実行</a></li>';
echo '</ol>';

echo '</body></html>';
?>
