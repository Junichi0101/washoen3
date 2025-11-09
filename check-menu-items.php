<?php
/**
 * メニュー項目確認スクリプト
 * URL: http://localhost/my_blog/wordpress/wp-content/themes/washoen3/check-menu-items.php
 */

// WordPressの環境を読み込み
require_once(__DIR__ . '/../../../wp-load.php');

// 管理者権限チェック
if (!current_user_can('manage_options')) {
    die('<h1>エラー</h1><p>管理者権限が必要です。WordPressにログインしてください。</p>');
}

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メニュー項目確認</title>
    <style>
        body { font-family: sans-serif; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 3px solid #0073aa; padding-bottom: 10px; }
        h2 { color: #666; border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-top: 30px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        table { border-collapse: collapse; width: 100%; margin: 15px 0; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #0073aa; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .info-box { background: #e7f3ff; border-left: 4px solid #0073aa; padding: 15px; margin: 15px 0; }
        .code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
    </style>
</head>
<body>
<div class="container">
    <h1>🔍 福中店メニュー項目確認</h1>

    <?php
    // 1. カスタム投稿タイプの確認
    echo '<h2>1. カスタム投稿タイプ (fukunaka_menu)</h2>';
    $post_type_exists = post_type_exists('fukunaka_menu');
    if ($post_type_exists) {
        echo '<p class="success">✅ カスタム投稿タイプ "fukunaka_menu" は登録されています</p>';
    } else {
        echo '<p class="error">❌ カスタム投稿タイプ "fukunaka_menu" が登録されていません</p>';
    }

    // 2. タクソノミーの確認
    echo '<h2>2. タクソノミー (fukunaka_category)</h2>';
    $taxonomy_exists = taxonomy_exists('fukunaka_category');
    if ($taxonomy_exists) {
        echo '<p class="success">✅ タクソノミー "fukunaka_category" は登録されています</p>';
    } else {
        echo '<p class="error">❌ タクソノミー "fukunaka_category" が登録されていません</p>';
    }

    // 3. カテゴリーの確認
    echo '<h2>3. 登録されているカテゴリー</h2>';
    $terms = get_terms(array(
        'taxonomy' => 'fukunaka_category',
        'hide_empty' => false,
    ));

    if (!empty($terms) && !is_wp_error($terms)) {
        echo '<p class="success">✅ ' . count($terms) . '個のカテゴリーが登録されています</p>';
        echo '<table>';
        echo '<tr><th>ID</th><th>名前</th><th>スラッグ</th><th>投稿数</th></tr>';
        foreach ($terms as $term) {
            echo '<tr>';
            echo '<td>' . $term->term_id . '</td>';
            echo '<td>' . esc_html($term->name) . '</td>';
            echo '<td><span class="code">' . esc_html($term->slug) . '</span></td>';
            echo '<td>' . $term->count . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p class="error">❌ カテゴリーが登録されていません</p>';
        echo '<div class="info-box">カテゴリーを作成するには、WordPress管理画面で「福中店 お品書き」→「メニューカテゴリー」から追加してください。</div>';
    }

    // 4. メニュー項目の確認
    echo '<h2>4. メニュー項目一覧</h2>';
    $menu_items = get_posts(array(
        'post_type' => 'fukunaka_menu',
        'posts_per_page' => -1,
        'post_status' => array('publish', 'draft', 'pending', 'private'),
        'orderby' => 'ID',
        'order' => 'DESC'
    ));

    if (!empty($menu_items)) {
        echo '<p class="success">✅ ' . count($menu_items) . '件のメニュー項目があります</p>';
        echo '<table>';
        echo '<tr><th>ID</th><th>タイトル</th><th>ステータス</th><th>カテゴリー</th><th>作成日</th></tr>';

        foreach ($menu_items as $item) {
            $categories = wp_get_post_terms($item->ID, 'fukunaka_category');
            $cat_names = array();
            if (!empty($categories) && !is_wp_error($categories)) {
                foreach ($categories as $cat) {
                    $cat_names[] = $cat->name;
                }
            }

            echo '<tr>';
            echo '<td>' . $item->ID . '</td>';
            echo '<td>' . esc_html($item->post_title) . '</td>';
            echo '<td><span class="code">' . $item->post_status . '</span></td>';
            echo '<td>' . (!empty($cat_names) ? implode(', ', $cat_names) : '<span class="warning">未設定</span>') . '</td>';
            echo '<td>' . $item->post_date . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p class="error">❌ メニュー項目がありません</p>';
        echo '<div class="info-box">
            メニュー項目を作成するには、WordPress管理画面で「福中店 お品書き」→「新規追加」から追加してください。
        </div>';
    }

    // 5. WP_Queryのテスト
    echo '<h2>5. ページで使用されるクエリのテスト</h2>';
    if (!empty($terms) && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            $args = array(
                'post_type' => 'fukunaka_menu',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'fukunaka_category',
                        'field' => 'slug',
                        'terms' => $term->slug,
                    ),
                ),
                'orderby' => 'menu_order',
                'order' => 'ASC'
            );
            $query = new WP_Query($args);

            if ($query->have_posts()) {
                echo '<p class="success">✅ カテゴリー「' . esc_html($term->name) . '」: ' . $query->post_count . '件のメニュー項目が見つかりました</p>';
                echo '<ul>';
                while ($query->have_posts()) {
                    $query->the_post();
                    echo '<li>' . get_the_title() . ' (ID: ' . get_the_ID() . ', status: ' . get_post_status() . ')</li>';
                }
                echo '</ul>';
                wp_reset_postdata();
            } else {
                echo '<p class="warning">⚠️ カテゴリー「' . esc_html($term->name) . '」: メニュー項目が見つかりませんでした</p>';
            }
        }
    }

    // 6. ページテンプレートの確認
    echo '<h2>6. ページ設定の確認</h2>';
    $page = get_page_by_path('fukunaka-menu');
    if ($page) {
        $template = get_post_meta($page->ID, '_wp_page_template', true);
        echo '<p class="success">✅ 「福中店 お品書き」ページが存在します (ID: ' . $page->ID . ')</p>';
        echo '<p>使用テンプレート: <span class="code">' . ($template ? $template : 'デフォルト') . '</span></p>';
        echo '<p>ページステータス: <span class="code">' . $page->post_status . '</span></p>';
        echo '<p>ページURL: <a href="' . get_permalink($page->ID) . '" target="_blank">' . get_permalink($page->ID) . '</a></p>';
    } else {
        echo '<p class="error">❌ 「福中店 お品書き」ページが見つかりません</p>';
    }

    // 7. 推奨アクション
    echo '<h2>7. 推奨アクション</h2>';
    echo '<ol>';
    echo '<li><a href="' . admin_url('edit.php?post_type=fukunaka_menu') . '" target="_blank">メニュー項目一覧を確認</a></li>';
    echo '<li><a href="' . admin_url('post-new.php?post_type=fukunaka_menu') . '" target="_blank">新しいメニュー項目を追加</a></li>';
    echo '<li><a href="' . admin_url('edit-tags.php?taxonomy=fukunaka_category&post_type=fukunaka_menu') . '" target="_blank">メニューカテゴリーを管理</a></li>';
    if ($page) {
        echo '<li><a href="' . get_permalink($page->ID) . '" target="_blank">福中店お品書きページを表示</a></li>';
    }
    echo '</ol>';
    ?>
</div>
</body>
</html>
