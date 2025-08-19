<?php
/**
 * Template Name: アクセス
 * Description: Access and location information page template
 * 
 * @package Washouen
 */

get_header(); ?>

<main class="main-content">
    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">当店への道案内</h1>
            <p class="page-subtitle">ACCESS</p>
        </div>
    </section>

    <section class="access-content">
        <div class="container">
            <!-- 福中店 -->
            <div class="store-access fukunaka-access">
                <h2 class="store-title">
                    <span class="store-label">福中店</span>
                    <span class="store-subtitle">活魚・一品料理</span>
                </h2>

                <div class="access-grid">
                    <div class="map-container">
                        <?php 
                        $fukunaka_map_url = get_theme_mod('fukunaka_map_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3281.7!2d133.88!3d34.66!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzTCsDM5JzM2LjAiTiAxMzPCsDUyJzQ4LjAiRQ!5e0!3m2!1sja!2sjp!4v1234567890');
                        $fukunaka_address = get_theme_mod('fukunaka_address', '〒670-0042 兵庫県姫路市米田町15-1 船場東ビル1F');
                        $fukunaka_phone   = get_theme_mod('fukunaka_phone', '079-222-5678');
                        $fukunaka_maps_link = get_theme_mod('fukunaka_maps_link');
                        if (!$fukunaka_maps_link) {
                            $fukunaka_maps_link = 'https://www.google.com/maps?q=' . rawurlencode($fukunaka_address);
                        }
                        $fukunaka_tel_link = 'tel:' . preg_replace('/[^0-9+]/', '', $fukunaka_phone);
                        ?>
                        <iframe 
                            src="<?php echo esc_url($fukunaka_map_url); ?>"
                            width="100%" 
                            height="400" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>

                    <div class="access-info">
                        <div class="info-item">
                            <h3><i class="fas fa-map-marker-alt"></i> 住所</h3>
                            <p><?php echo esc_html($fukunaka_address); ?></p>
                        </div>

                        <div class="info-item">
                            <h3><i class="fas fa-phone"></i> 電話番号</h3>
                            <p><?php echo get_theme_mod('fukunaka_phone', '079-222-5678'); ?></p>
                        </div>

                        <div class="info-item">
                            <h3><i class="fas fa-clock"></i> 営業時間</h3>
                            <p><?php echo nl2br(get_theme_mod('fukunaka_hours', "昼：11:30～14:00（L.O. 13:30）\n夜：17:00～22:00（L.O. 21:30）")); ?></p>
                        </div>

                        <div class="info-item">
                            <h3><i class="fas fa-calendar-alt"></i> 定休日</h3>
                            <p><?php echo get_theme_mod('fukunaka_closed', '月曜日（祝日の場合は翌日）'); ?></p>
                        </div>

                        <div class="info-item">
                            <h3><i class="fas fa-train"></i> アクセス</h3>
                            <ul class="access-list">
                                <li>JR姫路駅から徒歩12分</li>
                                <li>山陽姫路駅から徒歩8分</li>
                                <li>姫路バイパス「姫路南IC」から車で10分</li>
                            </ul>
                        </div>

                        <div class="info-item">
                            <h3><i class="fas fa-parking"></i> 駐車場</h3>
                            <p>店舗前に10台分の無料駐車場完備</p>
                        </div>

                        <div class="info-item">
                            <h3><i class="fas fa-credit-card"></i> お支払い方法</h3>
                            <p>現金、クレジットカード（VISA、Master、JCB、AMEX、Diners）、電子マネー各種</p>
                        </div>

                        <div class="access-actions">
                            <a class="btn btn-elegant" href="<?php echo esc_url($fukunaka_maps_link); ?>" target="_blank" rel="noopener">
                                <i class="fas fa-map"></i> Googleマップで開く
                            </a>
                            <a class="btn btn-outline-elegant" href="<?php echo esc_url($fukunaka_tel_link); ?>">
                                <i class="fas fa-phone"></i> 電話する
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 塩町店 -->
            <div class="store-access shiomachi-access">
                <h2 class="store-title">
                    <span class="store-label">塩町店</span>
                    <span class="store-subtitle">鮨</span>
                </h2>

                <div class="access-grid">
                    <div class="map-container">
                        <?php 
                        $shiomachi_map_url = get_theme_mod('shiomachi_map_url', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3281.7!2d133.89!3d34.67!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzTCsDQwJzEyLjAiTiAxMzPCsDUzJzI0LjAiRQ!5e0!3m2!1sja!2sjp!4v1234567890');
                        $shiomachi_address = get_theme_mod('shiomachi_address', '〒670-0904 兵庫県姫路市塩町177 アールビル1F');
                        $shiomachi_phone   = get_theme_mod('shiomachi_phone', '079-223-6879');
                        $shiomachi_maps_link = get_theme_mod('shiomachi_maps_link');
                        if (!$shiomachi_maps_link) {
                            $shiomachi_maps_link = 'https://www.google.com/maps?q=' . rawurlencode($shiomachi_address);
                        }
                        $shiomachi_tel_link = 'tel:' . preg_replace('/[^0-9+]/', '', $shiomachi_phone);
                        ?>
                        <iframe 
                            src="<?php echo esc_url($shiomachi_map_url); ?>"
                            width="100%" 
                            height="400" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>

                    <div class="access-info">
                        <div class="info-item">
                            <h3><i class="fas fa-map-marker-alt"></i> 住所</h3>
                            <p><?php echo get_theme_mod('shiomachi_address', '〒670-0904 兵庫県姫路市塩町177 アールビル1F'); ?></p>
                        </div>

                        <div class="info-item">
                            <h3><i class="fas fa-phone"></i> 電話番号</h3>
                            <p><?php echo get_theme_mod('shiomachi_phone', '079-223-6879'); ?></p>
                        </div>

                        <div class="info-item">
                            <h3><i class="fas fa-clock"></i> 営業時間</h3>
                            <p><?php echo nl2br(get_theme_mod('shiomachi_hours', "昼：11:30～14:00（L.O. 13:30）\n夜：17:00～22:00（L.O. 21:30）")); ?></p>
                        </div>

                        <div class="info-item">
                            <h3><i class="fas fa-calendar-alt"></i> 定休日</h3>
                            <p><?php echo get_theme_mod('shiomachi_closed', '火曜日（祝日の場合は翌日）'); ?></p>
                        </div>

                        <div class="info-item">
                            <h3><i class="fas fa-train"></i> アクセス</h3>
                            <ul class="access-list">
                                <li>JR姫路駅から徒歩15分</li>
                                <li>山陽姫路駅から徒歩10分</li>
                                <li>姫路バイパス「姫路南IC」から車で10分</li>
                            </ul>
                        </div>

                        <div class="info-item">
                            <h3><i class="fas fa-parking"></i> 駐車場</h3>
                            <p>店舗裏に8台分の無料駐車場完備</p>
                        </div>

                        <div class="info-item">
                            <h3><i class="fas fa-credit-card"></i> お支払い方法</h3>
                            <p>現金、クレジットカード（VISA、Master、JCB、AMEX、Diners）、電子マネー各種</p>
                        </div>

                        <div class="access-actions">
                            <a class="btn btn-elegant" href="<?php echo esc_url($shiomachi_maps_link); ?>" target="_blank" rel="noopener">
                                <i class="fas fa-map"></i> Googleマップで開く
                            </a>
                            <a class="btn btn-outline-elegant" href="<?php echo esc_url($shiomachi_tel_link); ?>">
                                <i class="fas fa-phone"></i> 電話する
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="transport-guide">
        <div class="container">
            <h2 class="section-title">交通のご案内</h2>
            
            <div class="transport-options">
                <div class="transport-item">
                    <h3><i class="fas fa-train"></i> 電車でお越しの方</h3>
                    <div class="transport-content">
                        <h4>福中店へ</h4>
                        <ul>
                            <li>JR姫路駅 南口から徒歩約12分</li>
                            <li>山陽姫路駅から徒歩約8分</li>
                        </ul>
                        <h4>塩町店へ</h4>
                        <ul>
                            <li>JR姫路駅 北口から徒歩約15分</li>
                            <li>山陽姫路駅から徒歩約10分</li>
                        </ul>
                    </div>
                </div>

                <div class="transport-item">
                    <h3><i class="fas fa-car"></i> お車でお越しの方</h3>
                    <div class="transport-content">
                        <h4>高速道路から</h4>
                        <ul>
                            <li>姫路バイパス「姫路南IC」から約10分</li>
                            <li>山陽自動車道「姫路東IC」から約15分</li>
                        </ul>
                        <h4>駐車場について</h4>
                        <p>両店舗とも無料駐車場を完備しております。満車の場合は近隣のコインパーキングをご案内いたします。</p>
                    </div>
                </div>

                <div class="transport-item">
                    <h3><i class="fas fa-plane"></i> 飛行機でお越しの方</h3>
                    <div class="transport-content">
                        <ul>
                            <li>神戸空港から三宮経由でJR姫路駅まで約90分</li>
                            <li>関西国際空港からJR姫路駅まで約120分</li>
                            <li>姫路駅から各店舗へは上記の交通手段をご利用ください</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="reservation-guide">
        <div class="container">
            <h2 class="section-title">ご予約・お問い合わせ</h2>
            
            <div class="reservation-content">
                <p class="lead-text">
                    ご来店の際は事前のご予約をおすすめしております。<br>
                    お電話にて承っておりますので、お気軽にお問い合わせください。
                </p>

                <div class="reservation-cards">
                    <div class="reservation-card">
                        <h3>福中店</h3>
                        <p class="phone-large">
                            <i class="fas fa-phone"></i>
                            <?php echo get_theme_mod('fukunaka_phone', '079-222-5678'); ?>
                        </p>
                        <p class="reception-hours">受付時間：10:00～21:00</p>
                    </div>

                    <div class="reservation-card">
                        <h3>塩町店</h3>
                        <p class="phone-large">
                            <i class="fas fa-phone"></i>
                            <?php echo get_theme_mod('shiomachi_phone', '079-223-6879'); ?>
                        </p>
                        <p class="reception-hours">受付時間：10:00～21:00</p>
                    </div>
                </div>

                <div class="reservation-notes">
                    <h3>ご予約に関するお願い</h3>
                    <ul>
                        <li>団体（8名様以上）でのご利用は、3日前までにご予約ください</li>
                        <li>キャンセルの場合は、前日までにご連絡をお願いいたします</li>
                        <li>アレルギーや食事制限がある場合は、予約時にお申し付けください</li>
                        <li>個室のご利用をご希望の場合は、予約時にお申し付けください（福中店のみ）</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
