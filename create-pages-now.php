<?php
/**
 * 緊急ページ作成スクリプト
 * ブラウザで直接アクセスして実行
 * URL: http://localhost/my_blog/wp-content/themes/washoen2/create-pages-now.php
 */

// WordPressの環境を読み込み
require_once('../../../../../wp-load.php');

// 管理者権限チェック
if (!current_user_can('manage_options')) {
    die('管理者権限が必要です。WordPressにログインしてください。');
}

echo "<h1>和招縁 ページ作成スクリプト</h1>";

// 作成するページの設定
$pages = array(
    array(
        'title' => '初めての方へ',
        'slug' => 'first-visit',
        'template' => 'page-first-visit.php',
        'content' => '初めての方への案内ページです。和招縁の2つの店舗について詳しくご紹介いたします。'
    ),
    array(
        'title' => '福中店メニュー',
        'slug' => 'fukunaka-menu',
        'template' => 'page-fukunaka-menu.php',
        'content' => '福中店のメニューページです。新鮮な活魚と一品料理をお楽しみください。'
    ),
    array(
        'title' => '塩町店メニュー',
        'slug' => 'shiomachi-menu',
        'template' => 'page-shiomachi-menu.php',
        'content' => '塩町店のメニューページです。本格的な江戸前鮨をお楽しみください。'
    ),
    array(
        'title' => 'アクセス',
        'slug' => 'access',
        'template' => 'page-access.php',
        'content' => '福中店・塩町店へのアクセス情報をご案内いたします。'
    )
);

echo "<h2>実行結果</h2>";

foreach ($pages as $page_data) {
    echo "<h3>" . $page_data['title'] . "</h3>";
    
    // ページが既に存在するかチェック
    $page = get_page_by_path($page_data['slug']);
    
    if (!$page) {
        // ページを作成
        $page_id = wp_insert_post(array(
            'post_title' => $page_data['title'],
            'post_name' => $page_data['slug'],
            'post_content' => $page_data['content'],
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_author' => 1,
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'menu_order' => 0
        ));

        if ($page_id && !is_wp_error($page_id)) {
            // ページテンプレートを設定
            update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            echo "<p style='color: green;'>✅ ページを作成しました (ID: $page_id)</p>";
            echo "<p>URL: <a href='" . get_permalink($page_id) . "' target='_blank'>" . get_permalink($page_id) . "</a></p>";
        } else {
            echo "<p style='color: red;'>❌ ページの作成に失敗しました</p>";
            if (is_wp_error($page_id)) {
                echo "<p>エラー: " . $page_id->get_error_message() . "</p>";
            }
        }
    } else {
        // 既存ページのテンプレートを更新
        $current_template = get_post_meta($page->ID, '_wp_page_template', true);
        if ($current_template !== $page_data['template']) {
            update_post_meta($page->ID, '_wp_page_template', $page_data['template']);
            echo "<p style='color: blue;'>🔄 既存ページのテンプレートを更新しました (ID: {$page->ID})</p>";
        } else {
            echo "<p style='color: orange;'>ℹ️ ページは既に存在し、正しいテンプレートが設定されています (ID: {$page->ID})</p>";
        }
        echo "<p>URL: <a href='" . get_permalink($page->ID) . "' target='_blank'>" . get_permalink($page->ID) . "</a></p>";
    }
}

// パーマリンクを更新
flush_rewrite_rules();
echo "<h3>パーマリンクを更新しました</h3>";

echo "<h2>次のステップ</h2>";
echo "<ol>";
echo "<li>WordPress管理画面 → 固定ページ で作成されたページを確認</li>";
echo "<li>各ページにアクセスして正しく表示されるか確認</li>";
echo "<li>このファイル(create-pages-now.php)は削除してください</li>";
echo "</ol>";

echo "<h2>ページリンク</h2>";
echo "<ul>";
foreach ($pages as $page_data) {
    $page = get_page_by_path($page_data['slug']);
    if ($page) {
        $url = get_permalink($page->ID);
        echo "<li><a href='$url' target='_blank'>{$page_data['title']}</a></li>";
    }
}
echo "</ul>";
?>