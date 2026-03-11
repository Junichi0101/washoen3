<?php
/**
 * Template Name: ご利用案内LP
 * Description: ご利用案内配下の固定LP共通テンプレート
 *
 * @package Washouen
 */

get_header();
?>

<main class="lp-page">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <?php
            $guide_page_url = washouen_get_usage_guide_url();
            $news_page_url = washouen_get_news_page_url();

            $lead = trim((string) get_post_meta(get_the_ID(), 'lp_lead', true));
            if ($lead === '') {
                $lead = trim((string) get_the_excerpt());
            }
            if ($lead === '') {
                $lead = 'ご利用シーンに合わせて、和招縁の魅力とご予約につながる情報をまとめています。';
            }

            // 対象店舗
            $target_store = get_post_meta(get_the_ID(), 'lp_target_store', true);
            if ($target_store === '') {
                $target_store = 'both';
            }

            // Primary CTA: 管理者が設定した場合のみ表示
            $primary_cta_label = trim((string) get_post_meta(get_the_ID(), 'lp_primary_cta_label', true));
            $primary_cta_url   = trim((string) get_post_meta(get_the_ID(), 'lp_primary_cta_url', true));

            // Secondary CTA: 未設定時は対象店舗の電話予約をデフォルト
            $secondary_cta_label = trim((string) get_post_meta(get_the_ID(), 'lp_secondary_cta_label', true));
            $secondary_cta_url   = trim((string) get_post_meta(get_the_ID(), 'lp_secondary_cta_url', true));

            if ($secondary_cta_label === '' && $secondary_cta_url === '') {
                if ($target_store === 'shiomachi') {
                    $phone = get_theme_mod('shiomachi_phone', '079-223-6879');
                    $secondary_cta_label = '塩町店へ電話予約する';
                } else {
                    $phone = get_theme_mod('fukunaka_phone', '079-284-5355');
                    $secondary_cta_label = '福中店へ電話予約する';
                }
                $secondary_cta_url = 'tel:' . preg_replace('/[^0-9]/', '', (string) $phone);
            }

            $points_raw = (string) get_post_meta(get_the_ID(), 'lp_points', true);
            $points = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $points_raw)));
            ?>

            <header class="lp-page-header">
                <p class="lp-page-kicker">ご利用案内</p>
                <h1 class="lp-page-title"><?php the_title(); ?></h1>
                <p class="lp-page-lead"><?php echo esc_html($lead); ?></p>
                <?php if ($primary_cta_label !== '' || $secondary_cta_label !== '') : ?>
                    <div class="lp-page-cta">
                        <?php if ($primary_cta_label !== '') : ?>
                            <a href="<?php echo esc_url($primary_cta_url); ?>" class="btn btn-elegant"><?php echo esc_html($primary_cta_label); ?></a>
                        <?php endif; ?>
                        <?php if ($secondary_cta_label !== '') : ?>
                            <a href="<?php echo esc_url($secondary_cta_url); ?>" class="btn btn-japanese"><?php echo esc_html($secondary_cta_label); ?></a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </header>

            <?php if (!empty($points)) : ?>
                <section class="lp-page-points">
                    <h2 class="lp-page-section-title">ご案内ポイント</h2>
                    <ul class="lp-page-points-list">
                        <?php foreach ($points as $point) : ?>
                            <li><?php echo esc_html($point); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endif; ?>

            <section class="lp-page-content">
                <?php if (trim((string) get_the_content()) !== '') : ?>
                    <?php the_content(); ?>
                <?php else : ?>
                    <p class="lp-page-placeholder">
                        このページに「導入文」「見出し」「CTA補足」を追加して、予約導線に育ててください。
                    </p>
                <?php endif; ?>
            </section>

            <nav class="lp-page-links" aria-label="関連ページリンク">
                <a href="<?php echo esc_url($guide_page_url); ?>" class="lp-page-links-item">
                    ご利用案内一覧へ <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
                <a href="<?php echo esc_url($news_page_url); ?>" class="lp-page-links-item">
                    お知らせ一覧へ <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </nav>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
