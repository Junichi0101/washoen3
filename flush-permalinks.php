<?php
/**
 * パーマリンクフラッシュスクリプト
 * ブラウザで直接アクセスして実行
 * URL: http://localhost/my_blog/wp-content/themes/washoen2/flush-permalinks.php
 */

// WordPressの環境を読み込み
require_once('../../../../../wp-load.php');

// 管理者権限チェック
if (!current_user_can('manage_options')) {
    die('管理者権限が必要です。WordPressにログインしてください。');
}

echo "<h1>パーマリンクフラッシュ</h1>";

echo "<h2>実行前の状況</h2>";
echo "<p>問題: カスタム投稿タイプと固定ページのスラッグが競合していました</p>";
echo "<ul>";
echo "<li>カスタム投稿タイプ 'fukunaka_menu' のスラッグ: fukunaka-menu → fukunaka-menu-items に変更</li>";
echo "<li>カスタム投稿タイプ 'shiomachi_menu' のスラッグ: shiomachi-menu → shiomachi-menu-items に変更</li>";
echo "<li>固定ページ 'fukunaka-menu' と 'shiomachi-menu' が正常に表示されるようになります</li>";
echo "</ul>";

echo "<h2>パーマリンクフラッシュ実行中...</h2>";

// カスタム投稿タイプを再登録（functions.phpの関数を呼び出し）
if (function_exists('washouen_custom_post_types')) {
    washouen_custom_post_types();
    echo "<p>✅ カスタム投稿タイプを再登録しました</p>";
}

if (function_exists('washouen_custom_taxonomies')) {
    washouen_custom_taxonomies();
    echo "<p>✅ カスタムタクソノミーを再登録しました</p>";
}

// パーマリンクをフラッシュ
flush_rewrite_rules();
echo "<p>✅ パーマリンクをフラッシュしました</p>";

echo "<h2>完了</h2>";
echo "<p style='color: green;'><strong>パーマリンクフラッシュが完了しました！</strong></p>";

echo "<h3>テストしてください</h3>";
echo "<ul>";
echo "<li><a href='" . home_url('/first-visit/') . "' target='_blank'>初めての方へ</a></li>";
echo "<li><a href='" . home_url('/fukunaka-menu/') . "' target='_blank'>福中店メニュー</a></li>";
echo "<li><a href='" . home_url('/shiomachi-menu/') . "' target='_blank'>塩町店メニュー</a></li>";
echo "<li><a href='" . home_url('/access/') . "' target='_blank'>アクセス</a></li>";
echo "</ul>";

echo "<h3>カスタム投稿タイプのアーカイブ（参考）</h3>";
echo "<ul>";
echo "<li><a href='" . home_url('/fukunaka-menu-items/') . "' target='_blank'>福中店メニュー項目アーカイブ</a></li>";
echo "<li><a href='" . home_url('/shiomachi-menu-items/') . "' target='_blank'>塩町店メニュー項目アーカイブ</a></li>";
echo "</ul>";

echo "<p><strong>注意:</strong> このファイル(flush-permalinks.php)は実行後に削除してください。</p>";
?>