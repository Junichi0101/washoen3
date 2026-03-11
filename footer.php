    <!-- フッター -->
    <footer class="footer">
        <div class="container">
            <div class="footer-upper">
                <!-- 店舗情報 -->
                <div class="footer-stores">
                    <div class="footer-store">
                        <h4>福中店</h4>
                        <?php
                            $fukunaka_phone_footer = get_theme_mod('fukunaka_phone', '079-284-5355');
                            $fukunaka_tel_footer = preg_replace('/[^0-9]/', '', $fukunaka_phone_footer);
                        ?>
                        <p class="store-tel">
                            <a href="tel:<?php echo esc_attr($fukunaka_tel_footer); ?>" class="tel-link">
                                <i class="fas fa-phone"></i>
                                TEL: <?php echo esc_html($fukunaka_phone_footer); ?>
                            </a>
                        </p>
                        <p class="store-address">
                            <?php echo esc_html(get_theme_mod('fukunaka_address', '〒670-0017 兵庫県姫路市福中町78')); ?>
                        </p>
                        <p class="store-hours-inline">
                            <i class="fas fa-clock"></i>
                            営業時間：18:00 ~ 1:00(L.O 0:30)
                        </p>
                    </div>
                    <div class="footer-store">
                        <h4>塩町店</h4>
                        <?php
                            $shiomachi_phone_footer = get_theme_mod('shiomachi_phone', '079-223-6879');
                            $shiomachi_tel_footer = preg_replace('/[^0-9]/', '', $shiomachi_phone_footer);
                        ?>
                        <p class="store-tel">
                            <a href="tel:<?php echo esc_attr($shiomachi_tel_footer); ?>" class="tel-link">
                                <i class="fas fa-phone"></i>
                                TEL: <?php echo esc_html($shiomachi_phone_footer); ?>
                            </a>
                        </p>
                        <p class="store-address">
                            <?php echo esc_html(get_theme_mod('shiomachi_address', '〒670-0904 兵庫県姫路市塩町177 アールビル1F')); ?>
                        </p>
                        <p class="store-hours-inline">
                            <i class="fas fa-clock"></i>
                            営業時間：18:00 ~ 2:00(L.O 1:30)
                        </p>
                    </div>
                </div>
                <p class="footer-notice">※ネタ、シャリが無くなり次第閉店になることがございますので、ご了承ください。</p>
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

    <!-- フローティング予約ボタン -->
    <div class="floating-reservation">
        <button class="floating-reservation-btn" id="floatingReservationBtn" aria-label="予約する">
            <i class="fas fa-phone-alt"></i>
            <span>ご予約</span>
        </button>
        <div class="floating-reservation-menu" id="floatingReservationMenu">
            <div class="floating-reservation-header">
                <h3>ご予約</h3>
                <button class="close-btn" id="closeReservationMenu" aria-label="閉じる">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="floating-reservation-options">
                <?php
                    $fukunaka_phone = get_theme_mod('fukunaka_phone', '079-284-5355');
                    $shiomachi_phone = get_theme_mod('shiomachi_phone', '079-223-6879');
                    $fukunaka_tel = preg_replace('/[^0-9]/', '', $fukunaka_phone);
                    $shiomachi_tel = preg_replace('/[^0-9]/', '', $shiomachi_phone);
                ?>
                <a href="tel:<?php echo esc_attr($fukunaka_tel); ?>" class="reservation-option">
                    <div class="reservation-option-store">福中店</div>
                    <div class="reservation-option-phone">
                        <i class="fas fa-phone"></i>
                        <?php echo esc_html($fukunaka_phone); ?>
                    </div>
                </a>
                <a href="tel:<?php echo esc_attr($shiomachi_tel); ?>" class="reservation-option">
                    <div class="reservation-option-store">塩町店</div>
                    <div class="reservation-option-phone">
                        <i class="fas fa-phone"></i>
                        <?php echo esc_html($shiomachi_phone); ?>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
    // フローティング予約ボタンの制御
    (function() {
        const floatingBtn = document.getElementById('floatingReservationBtn');
        const floatingMenu = document.getElementById('floatingReservationMenu');
        const closeBtn = document.getElementById('closeReservationMenu');

        if (floatingBtn && floatingMenu) {
            floatingBtn.addEventListener('click', function() {
                floatingMenu.classList.toggle('active');
                floatingBtn.classList.toggle('active');
            });

            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    floatingMenu.classList.remove('active');
                    floatingBtn.classList.remove('active');
                });
            }

            // メニュー外をクリックしたら閉じる
            document.addEventListener('click', function(e) {
                if (!floatingBtn.contains(e.target) && !floatingMenu.contains(e.target)) {
                    floatingMenu.classList.remove('active');
                    floatingBtn.classList.remove('active');
                }
            });
        }
    })();
    </script>

    <?php wp_footer(); ?>
</body>
</html>

<?php
// フッターメニューのフォールバック
function washouen_footer_menu() {
    ?>
    <ul class="footer-menu">
        <li><a href="<?php echo esc_url(home_url('/')); ?>">ホーム</a></li>
        <li><a href="<?php echo esc_url(home_url('/first-visit/')); ?>">ご挨拶</a></li>
        <li><a href="<?php echo esc_url(home_url('/fukunaka-menu/')); ?>">福中店 お品書き</a></li>
        <li><a href="<?php echo esc_url(home_url('/shiomachi-menu/')); ?>">塩町店 お品書き</a></li>
        <li><a href="<?php echo esc_url(home_url('/access/')); ?>">当店への道案内</a></li>
    </ul>
    <?php
}
?>