<?php
/**
 * アーカイブページテンプレート
 *
 * @package Washouen
 */

get_header(); ?>

<main class="archive-page">
    <div class="container">

        <!-- アーカイブヘッダー -->
        <header class="archive-header">
            <h1 class="archive-title">
                <?php
                if (is_category()) {
                    single_cat_title();
                } elseif (is_tag()) {
                    single_tag_title();
                } elseif (is_date()) {
                    if (is_year()) {
                        echo get_the_date('Y年');
                    } elseif (is_month()) {
                        echo get_the_date('Y年n月');
                    } elseif (is_day()) {
                        echo get_the_date('Y年n月j日');
                    }
                } elseif (is_author()) {
                    the_author();
                } else {
                    echo 'お知らせ一覧';
                }
                ?>
            </h1>
            <?php if (is_category() && category_description()) : ?>
                <p class="archive-description"><?php echo category_description(); ?></p>
            <?php endif; ?>
        </header>

        <!-- 記事一覧 -->
        <div class="archive-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <article class="news-card">
                        <a href="<?php the_permalink(); ?>" class="news-card-link">
                            <div class="news-card-image">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium_large', array('class' => 'news-card-img')); ?>
                                <?php else : ?>
                                    <div class="news-card-placeholder">
                                        <i class="fas fa-newspaper"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="news-card-content">
                                <time class="news-card-date" datetime="<?php echo get_the_date('Y-m-d'); ?>">
                                    <?php echo get_the_date('Y.m.d'); ?>
                                </time>
                                <h2 class="news-card-title"><?php the_title(); ?></h2>
                                <p class="news-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 40, '...'); ?></p>
                            </div>
                        </a>
                    </article>
                <?php endwhile; ?>
            <?php else : ?>
                <p class="archive-empty">記事が見つかりませんでした。</p>
            <?php endif; ?>
        </div>

        <!-- ページネーション -->
        <?php if (have_posts()) : ?>
            <div class="pagination">
                <?php
                echo paginate_links(array(
                    'prev_text' => '<i class="fas fa-chevron-left"></i>',
                    'next_text' => '<i class="fas fa-chevron-right"></i>',
                ));
                ?>
            </div>
        <?php endif; ?>

        <!-- ホームへ戻る -->
        <div class="back-to-list">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-elegant">
                <i class="fas fa-home"></i> ホームへ戻る
            </a>
        </div>

    </div>
</main>

<?php get_footer(); ?>
