<?php
/**
 * Template Name: ご利用案内
 * Description: 固定LPへの入口一覧ページ
 *
 * @package Washouen
 */

get_header();

if (have_posts()) {
    the_post();
}

$news_page_url = washouen_get_news_page_url();

// 公開済みLPページを一括取得（管理画面のメタボックスで設定可能）
$all_lp_pages = washouen_get_published_lp_pages();

$guide_cards = array();
foreach ($all_lp_pages as $lp_page) {
    $excerpt = trim((string) get_post_field('post_excerpt', $lp_page->ID));
    if ($excerpt === '') {
        $content = wp_strip_all_tags(strip_shortcodes((string) get_post_field('post_content', $lp_page->ID)));
        $excerpt = trim((string) wp_trim_words($content, 38, '...'));
    }
    if ($excerpt === '') {
        $excerpt = '詳細はこちらからご確認ください。';
    }

    $icon = get_post_meta($lp_page->ID, 'lp_icon', true);

    $guide_cards[] = array(
        'title'        => get_the_title($lp_page->ID),
        'description'  => $excerpt,
        'url'          => get_permalink($lp_page->ID),
        'thumbnail_id' => get_post_thumbnail_id($lp_page->ID),
        'icon'         => $icon !== '' ? $icon : 'fas fa-utensils',
    );
}
?>

<main class="usage-guide-page archive-page">
    <div class="container">
        <?php washouen_breadcrumbs(); ?>

        <header class="archive-header usage-guide-header">
            <h1 class="section-title">
                <span class="title-ja"><?php echo esc_html(get_the_title()); ?></span>
                <span class="title-en">GUIDE</span>
            </h1>
            <p class="usage-guide-lead">
                ご利用シーン別に、和招縁のご案内ページをまとめています。気になる内容からご覧ください。
            </p>
        </header>

        <?php if (trim((string) get_the_content()) !== '') : ?>
            <section class="usage-guide-content">
                <?php the_content(); ?>
            </section>
        <?php endif; ?>

        <section class="usage-guide-grid" aria-label="ご利用案内一覧">
            <?php foreach ($guide_cards as $card) : ?>
                <article class="usage-guide-card is-ready">
                    <a href="<?php echo esc_url($card['url']); ?>" class="usage-guide-card-link">
                        <div class="usage-guide-card-image">
                            <?php if (!empty($card['thumbnail_id'])) : ?>
                                <?php echo wp_get_attachment_image($card['thumbnail_id'], 'washouen-menu', false, array('class' => 'usage-guide-card-img')); ?>
                            <?php else : ?>
                                <div class="usage-guide-card-placeholder">
                                    <i class="<?php echo esc_attr($card['icon']); ?>" aria-hidden="true"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="usage-guide-card-body">
                            <h2 class="usage-guide-card-title"><?php echo esc_html($card['title']); ?></h2>
                            <p class="usage-guide-card-description"><?php echo esc_html($card['description']); ?></p>
                            <span class="usage-guide-card-more">詳しく見る <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </section>

        <section class="usage-guide-actions">
            <a href="<?php echo esc_url($news_page_url); ?>" class="btn btn-elegant">
                お知らせ一覧を見る
            </a>
            <a href="<?php echo esc_url(home_url('/access/')); ?>" class="btn btn-japanese">
                当店への道案内を見る
            </a>
        </section>
    </div>
</main>

<?php get_footer(); ?>
