<?php
/**
 * 個別投稿ページテンプレート
 *
 * @package Washouen
 */

get_header(); ?>

<main class="single-post">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <?php
            $news_page_url = washouen_get_news_page_url();
            $guide_page_url = washouen_get_usage_guide_url();

            $related_lp_slug = trim((string) get_post_meta(get_the_ID(), 'related_lp_slug', true));
            $related_lp_text = trim((string) get_post_meta(get_the_ID(), 'related_lp_text', true));
            $related_lp_page = $related_lp_slug ? washouen_get_lp_page_by_slug($related_lp_slug) : null;
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('post-article'); ?>>

                <!-- 記事ヘッダー -->
                <header class="post-header">
                    <div class="post-meta">
                        <time class="post-date" datetime="<?php echo get_the_date('Y-m-d'); ?>">
                            <i class="fas fa-calendar-alt"></i>
                            <?php echo get_the_date('Y年n月j日'); ?>
                        </time>
                        <?php if (get_the_category()) : ?>
                            <span class="post-category">
                                <i class="fas fa-folder"></i>
                                <?php the_category(', '); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <h1 class="post-title"><?php the_title(); ?></h1>
                </header>

                <!-- アイキャッチ画像 -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('large', array('class' => 'post-thumbnail-img')); ?>
                    </div>
                <?php endif; ?>

                <!-- 記事本文 -->
                <div class="post-content">
                    <?php the_content(); ?>
                </div>

                <!-- タグ -->
                <?php if (has_tag()) : ?>
                    <footer class="post-footer">
                        <div class="post-tags">
                            <i class="fas fa-tags"></i>
                            <?php the_tags('', '', ''); ?>
                        </div>
                    </footer>
                <?php endif; ?>

            </article>

            <!-- 前後の記事ナビゲーション -->
            <nav class="post-navigation">
                <div class="nav-links">
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    ?>

                    <?php if ($prev_post) : ?>
                        <a href="<?php echo get_permalink($prev_post); ?>" class="nav-link nav-prev">
                            <span class="nav-label"><i class="fas fa-chevron-left"></i> 前の記事</span>
                            <span class="nav-title"><?php echo esc_html($prev_post->post_title); ?></span>
                        </a>
                    <?php else : ?>
                        <span class="nav-link nav-prev nav-empty"></span>
                    <?php endif; ?>

                    <?php if ($next_post) : ?>
                        <a href="<?php echo get_permalink($next_post); ?>" class="nav-link nav-next">
                            <span class="nav-label">次の記事 <i class="fas fa-chevron-right"></i></span>
                            <span class="nav-title"><?php echo esc_html($next_post->post_title); ?></span>
                        </a>
                    <?php else : ?>
                        <span class="nav-link nav-next nav-empty"></span>
                    <?php endif; ?>
                </div>
            </nav>

            <?php if ($related_lp_page || $guide_page_url) : ?>
                <section class="post-related-lp">
                    <h2 class="post-related-lp-title">関連するご利用案内</h2>
                    <div class="post-related-lp-links">
                        <?php if ($related_lp_page) : ?>
                            <a href="<?php echo esc_url(get_permalink($related_lp_page->ID)); ?>" class="btn btn-elegant">
                                <i class="fas fa-link"></i>
                                <?php echo esc_html($related_lp_text !== '' ? $related_lp_text : get_the_title($related_lp_page->ID)); ?>
                            </a>
                        <?php endif; ?>
                        <?php if ($guide_page_url) : ?>
                            <a href="<?php echo esc_url($guide_page_url); ?>" class="btn btn-japanese">
                                ご利用案内一覧を見る
                            </a>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- 一覧へ戻るボタン -->
            <div class="back-to-list">
                <a href="<?php echo esc_url($news_page_url); ?>" class="btn btn-elegant">
                    <i class="fas fa-arrow-left"></i> お知らせ一覧へ戻る
                </a>
            </div>

        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
