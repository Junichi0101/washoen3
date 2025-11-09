/**
 * 管理画面でのメニュー並び替え機能
 */
jQuery(document).ready(function($) {
    // メニュー一覧ページでのみ実行
    if ($('body').hasClass('post-type-fukunaka_menu') || $('body').hasClass('post-type-shiomachi_menu')) {
        // テーブルにソート可能クラスを追加
        $('#the-list').sortable({
            items: 'tr',
            cursor: 'move',
            axis: 'y',
            handle: '.column-title',
            scrollSensitivity: 40,
            helper: function(e, ui) {
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                ui.css('left', '0');
                return ui;
            },
            start: function(event, ui) {
                ui.item.css('background-color', '#f6f6f6');
            },
            stop: function(event, ui) {
                ui.item.removeAttr('style');
                
                var order = $('#the-list').sortable('toArray');
                
                // Ajax で並び順を保存
                $.post(ajaxurl, {
                    action: 'update_menu_order',
                    order: order,
                    nonce: washouen_admin.nonce
                }, function(response) {
                    if (response.success) {
                        // 成功メッセージを表示
                        var message = $('<div class="notice notice-success is-dismissible"><p>並び順を更新しました。</p></div>');
                        $('.wrap h1').after(message);
                        
                        // 3秒後に自動的に消す
                        setTimeout(function() {
                            message.fadeOut(function() {
                                $(this).remove();
                            });
                        }, 3000);
                    }
                });
            }
        });
        
        // ドラッグ可能であることを示すスタイルを追加
        $('.column-title').css('cursor', 'move').attr('title', 'ドラッグして並び替え');
        
        // ヘルプテキストを追加
        if ($('.tablenav.top').length && !$('#sort-help').length) {
            $('.tablenav.top').before(
                '<div id="sort-help" class="notice notice-info" style="margin: 10px 0;">' +
                '<p><strong>ヒント:</strong> タイトル列をドラッグ&ドロップして、メニューの表示順序を変更できます。</p>' +
                '</div>'
            );
        }
    }
});