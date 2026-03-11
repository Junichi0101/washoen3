<?php
/**
 * 投稿一覧ページテンプレート（お知らせ一覧）
 *
 * @package Washouen
 */

get_header();

global $wp_query;
$news_query = $wp_query;
$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$posts_page_id = (int) get_option('page_for_posts');
$archive_title = $posts_page_id > 0 ? get_the_title($posts_page_id) : 'お知らせ一覧';
?>

<main class="archive-page">
    <div class="container">

        <!-- ヘッダー -->
        <header class="archive-header">
            <h1 class="section-title">
                <span class="title-ja"><?php echo esc_html($archive_title); ?></span>
                <span class="title-en">NEWS</span>
            </h1>
        </header>

        <!-- 記事一覧（リスト形式） -->
        <div class="news-list">
            <?php if ($news_query->have_posts()) : ?>
                <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
                    <article class="news-list-item">
                        <a href="<?php the_permalink(); ?>" class="news-list-link">
                            <div class="news-list-thumbnail">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('thumbnail', array('class' => 'news-list-img')); ?>
                                <?php else : ?>
                                    <div class="news-list-placeholder">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="news-list-content">
                                <time class="news-list-date" datetime="<?php echo get_the_date('Y-m-d'); ?>">
                                    <?php echo get_the_date('Y.m.d'); ?>
                                </time>
                                <h2 class="news-list-title"><?php the_title(); ?></h2>
                                <p class="news-list-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 60, '...'); ?></p>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <p class="news-list-empty">現在お知らせはありません。</p>
            <?php endif; ?>
        </div>

        <!-- ページネーション -->
        <?php if ($news_query->max_num_pages > 1) : ?>
            <div class="pagination">
                <?php
                echo paginate_links(array(
                    'total' => $news_query->max_num_pages,
                    'current' => $paged,
                    'prev_text' => '<i class="fas fa-chevron-left"></i>',
                    'next_text' => '<i class="fas fa-chevron-right"></i>',
                ));
                ?>
            </div>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

        <!-- ホームへ戻る -->
        <div class="back-to-list">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-elegant">
                <i class="fas fa-home"></i> ホームへ戻る
            </a>
        </div>

    </div>
</main>

<?php get_footer(); ?>
