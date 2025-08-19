    <!-- フッター -->
    <footer class="footer">
        <div class="container">
            <div class="footer-upper">
                <!-- 店舗情報 -->
                <div class="footer-stores">
                    <div class="footer-store">
                        <h4>福中店</h4>
                        <p class="store-tel">
                            <i class="fas fa-phone"></i> 
                            TEL: <?php echo esc_html(get_theme_mod('fukunaka_phone', '079-284-5355')); ?>
                        </p>
                        <p class="store-address">
                            <?php echo esc_html(get_theme_mod('fukunaka_address', '〒670-0017 兵庫県姫路市福中町78')); ?>
                        </p>
                    </div>
                    <div class="footer-store">
                        <h4>塩町店</h4>
                        <p class="store-tel">
                            <i class="fas fa-phone"></i> 
                            TEL: <?php echo esc_html(get_theme_mod('shiomachi_phone', '079-223-6879')); ?>
                        </p>
                        <p class="store-address">
                            <?php echo esc_html(get_theme_mod('shiomachi_address', '〒670-0904 兵庫県姫路市塩町177 アールビル1F')); ?>
                        </p>
                    </div>
                </div>

                <!-- 営業時間 -->
                <div class="footer-hours">
                    <h4>営業時間</h4>
                    <div class="store-hours">
                        <h5>福中店</h5>
                        <p><?php echo nl2br(esc_html(get_theme_mod('fukunaka_hours', "昼：11:30～14:00\n夜：17:00～22:00"))); ?></p>
                        <p class="holiday">定休日：<?php echo esc_html(get_theme_mod('fukunaka_closed', '月曜日')); ?></p>
                    </div>
                    <div class="store-hours">
                        <h5>塩町店</h5>
                        <p><?php echo nl2br(esc_html(get_theme_mod('shiomachi_hours', "昼：11:30～14:00\n夜：17:00～22:00"))); ?></p>
                        <p class="holiday">定休日：<?php echo esc_html(get_theme_mod('shiomachi_closed', '火曜日')); ?></p>
                    </div>
                </div>
            </div>

            <!-- メニューリンク -->
            <div class="footer-menu-wrapper">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_class' => 'footer-menu',
                    'container' => false,
                    'depth' => 1,
                    'fallback_cb' => 'washouen_footer_menu',
                ));
                ?>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>

<?php
// フッターメニューのフォールバック
function washouen_footer_menu() {
    ?>
    <ul class="footer-menu">
        <li><a href="<?php echo esc_url(home_url('/')); ?>">ホーム</a></li>
        <li><a href="<?php echo esc_url(home_url('/first-visit/')); ?>">初めての方へ</a></li>
        <li><a href="<?php echo esc_url(home_url('/fukunaka-menu/')); ?>">福中店メニュー</a></li>
        <li><a href="<?php echo esc_url(home_url('/shiomachi-menu/')); ?>">塩町店メニュー</a></li>
        <li><a href="<?php echo esc_url(home_url('/access/')); ?>">当店への道案内</a></li>
    </ul>
    <?php
}
?>