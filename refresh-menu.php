<?php
/**
 * メニュー表示修正スクリプト
 * ブラウザで直接アクセスして実行
 * URL: http://localhost/my_blog/wp-content/themes/washoen3/refresh-menu.php
 */

// WordPressの環境を読み込み
require_once(__DIR__ . '/../../../wp-load.php');

// 管理者権限チェック
if (!current_user_can('manage_options')) {
    die('<h1>エラー</h1><p>管理者権限が必要です。WordPressにログインしてください。</p>');
}

echo '<html><head><meta charset="UTF-8"><title>メニュー表示修正</title></head><body>';
echo '<h1>メニュー表示修正スクリプト</h1>';

echo '<h2>実行中...</h2>';

// カスタム投稿タイプを再登録
if (function_exists('washouen_custom_post_types')) {
    washouen_custom_post_types();
    echo '<p>✅ カスタム投稿タイプを再登録しました</p>';
}

if (function_exists('washouen_custom_taxonomies')) {
    washouen_custom_taxonomies();
    echo '<p>✅ カスタムタクソノミーを再登録しました</p>';
}

// デフォルトカテゴリーを作成
if (function_exists('washouen_create_default_categories')) {
    washouen_create_default_categories();
    echo '<p>✅ デフォルトカテゴリーを作成しました</p>';
}

// パーマリンクをフラッシュ
flush_rewrite_rules();
echo '<p>✅ パーマリンクをフラッシュしました</p>';

echo '<h2>完了</h2>';
echo '<p style="color: green;"><strong>メニュー表示の修正が完了しました！</strong></p>';

echo '<h3>次のステップ</h3>';
echo '<ol>';
echo '<li>WordPress管理画面をリロードしてください</li>';
echo '<li>左側メニューに「福中店 お品書き」と「塩町店 お品書き」が表示されているか確認してください</li>';
echo '<li>表示されていない場合は、一度ログアウトして再ログインしてください</li>';
echo '</ol>';

echo '<h3>管理画面へのリンク</h3>';
echo '<ul>';
echo '<li><a href="' . admin_url() . '" target="_blank">WordPress管理画面</a></li>';
echo '<li><a href="' . admin_url('edit.php?post_type=fukunaka_menu') . '" target="_blank">福中店 お品書き一覧</a></li>';
echo '<li><a href="' . admin_url('edit.php?post_type=shiomachi_menu') . '" target="_blank">塩町店 お品書き一覧</a></li>';
echo '</ul>';

echo '<p><strong>注意:</strong> このファイル(refresh-menu.php)は実行後に削除してください。</p>';
echo '</body></html>';
?>
