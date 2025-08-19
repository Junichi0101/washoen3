<?php
/**
 * ページセットアップスクリプト
 * WordPress管理画面の「外観」→「テーマエディター」から実行するか、
 * 一時的にfunctions.phpに追加して実行してください。
 */

// WordPressの初期化を確認
if (!defined('ABSPATH')) {
    // WordPressのルートディレクトリを探して読み込む
    $wp_config_path = dirname(__FILE__);
    for ($i = 0; $i < 5; $i++) {
        if (file_exists($wp_config_path . '/wp-config.php')) {
            require_once($wp_config_path . '/wp-config.php');
            break;
        }
        $wp_config_path = dirname($wp_config_path);
    }
}

function setup_washouen_pages() {
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

    $created_pages = array();
    $updated_pages = array();

    foreach ($pages as $page_data) {
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
                $created_pages[] = $page_data['title'] . ' (ID: ' . $page_id . ')';
            }
        } else {
            // 既存ページのテンプレートを更新
            update_post_meta($page->ID, '_wp_page_template', $page_data['template']);
            $updated_pages[] = $page_data['title'] . ' (ID: ' . $page->ID . ')';
        }
    }

    // パーマリンクを更新
    flush_rewrite_rules();

    // 結果を表示
    echo "<div style='padding: 20px; margin: 20px; border: 1px solid #ddd;'>";
    echo "<h2>ページセットアップ完了</h2>";
    
    if (!empty($created_pages)) {
        echo "<h3>作成されたページ:</h3>";
        echo "<ul>";
        foreach ($created_pages as $page) {
            echo "<li>" . $page . "</li>";
        }
        echo "</ul>";
    }
    
    if (!empty($updated_pages)) {
        echo "<h3>更新されたページ:</h3>";
        echo "<ul>";
        foreach ($updated_pages as $page) {
            echo "<li>" . $page . "</li>";
        }
        echo "</ul>";
    }
    
    echo "<p><strong>次の手順:</strong></p>";
    echo "<ol>";
    echo "<li>WordPress管理画面 → 固定ページ で作成されたページを確認</li>";
    echo "<li>各ページの「ページ属性」でテンプレートが正しく選択されているか確認</li>";
    echo "<li>フロントエンドでページが正しく表示されるか確認</li>";
    echo "</ol>";
    
    echo "<p><strong>ページURL:</strong></p>";
    echo "<ul>";
    foreach ($pages as $page_data) {
        $url = home_url('/' . $page_data['slug'] . '/');
        echo "<li><a href='" . $url . "' target='_blank'>" . $page_data['title'] . "</a> - " . $url . "</li>";
    }
    echo "</ul>";
    
    echo "<p><strong>注意:</strong> WordPressがサブディレクトリ(/my_blog/)にインストールされている場合、URLは以下のようになります:</p>";
    echo "<ul>";
    echo "<li>初めての方へ: <code>http://localhost/my_blog/first-visit/</code></li>";
    echo "<li>福中店メニュー: <code>http://localhost/my_blog/fukunaka-menu/</code></li>";
    echo "<li>塩町店メニュー: <code>http://localhost/my_blog/shiomachi-menu/</code></li>";
    echo "<li>アクセス: <code>http://localhost/my_blog/access/</code></li>";
    echo "</ul>";
    echo "</div>";
}

// 管理画面からアクセスされた場合のみ実行
if (is_admin() && current_user_can('manage_options')) {
    // URLパラメータで実行を制御
    if (isset($_GET['setup_pages']) && $_GET['setup_pages'] === 'true') {
        add_action('admin_notices', function() {
            setup_washouen_pages();
        });
    }
    
    // 管理画面にセットアップボタンを追加
    add_action('admin_menu', function() {
        add_theme_page(
            'ページセットアップ',
            'ページセットアップ',
            'manage_options',
            'washouen-setup',
            function() {
                echo "<div class='wrap'>";
                echo "<h1>和招縁 ページセットアップ</h1>";
                echo "<p>必要なページ（初めての方へ、福中店メニュー、塩町店メニュー、アクセス）を作成します。</p>";
                echo "<a href='" . admin_url('themes.php?page=washouen-setup&setup_pages=true') . "' class='button button-primary'>ページを作成/更新する</a>";
                echo "</div>";
            }
        );
    });
}
?>