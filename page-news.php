<?php
/**
 * Template Name: お知らせ一覧
 *
 * お知らせ一覧用のページテンプレート
 *
 * @package Washouen
 */

get_header();

// ページネーション用の現在ページ取得
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

// カスタムクエリで投稿を取得
$news_query = new WP_Query(array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 9,
    'paged' => $paged,
));
?>

<main class="archive-page">
    <div class="container">

        <!-- ヘッダー -->
        <header class="archive-header">
            <div class="section-title">
                <span class="title-ja">お知らせ一覧</span>
                <span class="title-en">NEWS</span>
            </div>
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
