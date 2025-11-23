<?php
/**
 * カテゴリー順序管理機能
 *
 * @package Washouen
 * @since 1.0.0
 */

// カテゴリー編集画面に順序フィールドを追加
function washouen_add_category_order_field($term) {
    $term_id = $term->term_id;
    $order = get_term_meta($term_id, 'category_order', true);
    $order = $order ? $order : 0;
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="category_order">表示順序</label>
        </th>
        <td>
            <input type="number" name="category_order" id="category_order" value="<?php echo esc_attr($order); ?>" min="0" step="1">
            <p class="description">数字が小さいほど上に表示されます。同じ数字の場合は、カテゴリー名順になります。</p>
        </td>
    </tr>
    <?php
}
add_action('fukunaka_category_edit_form_fields', 'washouen_add_category_order_field');
add_action('shiomachi_category_edit_form_fields', 'washouen_add_category_order_field');

// カテゴリー新規追加画面に順序フィールドを追加
function washouen_add_category_order_field_new($taxonomy) {
    ?>
    <div class="form-field">
        <label for="category_order">表示順序</label>
        <input type="number" name="category_order" id="category_order" value="0" min="0" step="1">
        <p class="description">数字が小さいほど上に表示されます。同じ数字の場合は、カテゴリー名順になります。</p>
    </div>
    <?php
}
add_action('fukunaka_category_add_form_fields', 'washouen_add_category_order_field_new');
add_action('shiomachi_category_add_form_fields', 'washouen_add_category_order_field_new');

// カテゴリー順序を保存
function washouen_save_category_order($term_id) {
    if (isset($_POST['category_order'])) {
        $order = intval($_POST['category_order']);
        update_term_meta($term_id, 'category_order', $order);
    }
}
add_action('created_fukunaka_category', 'washouen_save_category_order');
add_action('edited_fukunaka_category', 'washouen_save_category_order');
add_action('created_shiomachi_category', 'washouen_save_category_order');
add_action('edited_shiomachi_category', 'washouen_save_category_order');

// カテゴリー一覧に順序カラムを追加
function washouen_add_category_order_column($columns) {
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key == 'name') {
            $new_columns['order'] = '表示順序';
        }
    }
    return $new_columns;
}
add_filter('manage_edit-fukunaka_category_columns', 'washouen_add_category_order_column');
add_filter('manage_edit-shiomachi_category_columns', 'washouen_add_category_order_column');

// カテゴリー一覧に順序を表示
function washouen_show_category_order_column($content, $column_name, $term_id) {
    if ($column_name == 'order') {
        $order = get_term_meta($term_id, 'category_order', true);
        $content = $order ? $order : '0';
    }
    return $content;
}
add_filter('manage_fukunaka_category_custom_column', 'washouen_show_category_order_column', 10, 3);
add_filter('manage_shiomachi_category_custom_column', 'washouen_show_category_order_column', 10, 3);

// カテゴリー一覧を順序でソート可能にする
function washouen_sortable_category_columns($columns) {
    $columns['order'] = 'order';
    return $columns;
}
add_filter('manage_edit-fukunaka_category_sortable_columns', 'washouen_sortable_category_columns');
add_filter('manage_edit-shiomachi_category_sortable_columns', 'washouen_sortable_category_columns');

// カテゴリーのデフォルト順序を設定
function washouen_set_default_category_order($term_id, $tt_id, $taxonomy) {
    // 新規作成時のみ、順序が設定されていない場合にデフォルト値を設定
    $order = get_term_meta($term_id, 'category_order', true);
    if ($order === '' || $order === false) {
        // デフォルトの順序マッピング
        $term = get_term($term_id, $taxonomy);
        $default_orders = array(
            // 福中店
            'course' => 10,
            'sashimi' => 20,
            'grilled' => 30,
            'simmered' => 40,
            'fried' => 50,
            'special' => 60,
            'drink' => 70,
            // 塩町店
            'nigiri' => 10,
            'gunkan' => 20,
            'chirashi' => 30,
            'omakase' => 40,
            'side' => 50,
        );

        if (isset($default_orders[$term->slug])) {
            update_term_meta($term_id, 'category_order', $default_orders[$term->slug]);
        } else {
            update_term_meta($term_id, 'category_order', 100);
        }
    }
}
add_action('created_fukunaka_category', 'washouen_set_default_category_order', 10, 3);
add_action('created_shiomachi_category', 'washouen_set_default_category_order', 10, 3);

// カテゴリーを順序でソートする関数
function washouen_sort_terms_by_order($terms) {
    if (empty($terms) || is_wp_error($terms)) {
        return $terms;
    }

    // 各タームの順序を取得
    foreach ($terms as $term) {
        $order = get_term_meta($term->term_id, 'category_order', true);
        $term->order = $order !== '' ? intval($order) : 999;
    }

    // 順序でソート
    usort($terms, function($a, $b) {
        if ($a->order == $b->order) {
            return strcmp($a->name, $b->name);
        }
        return $a->order - $b->order;
    });

    return $terms;
}
