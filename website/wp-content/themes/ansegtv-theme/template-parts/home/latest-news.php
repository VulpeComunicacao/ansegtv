<?php
/**
 * Template para a seção de últimas notícias da página inicial
 */

$latest_news_title = get_theme_mod('latest_news_title', 'Últimas Notícias');
$latest_news_count = get_theme_mod('latest_news_count', 6);
$latest_news = new WP_Query(array(
    'post_type' => 'post',
    'posts_per_page' => $latest_news_count,
    'post_status' => 'publish'
));
?>

<section class="latest-news-section">
    <div class="container">
        <h2 class="section-title animate-on-scroll"><?php echo esc_html($latest_news_title); ?></h2>
        
        <div class="row">
            <?php
            if ($latest_news->have_posts()) :
                while ($latest_news->have_posts()) : $latest_news->the_post();
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <article class="news-item animate-on-scroll">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="news-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="news-content">
                                <div class="news-meta">
                                    <span class="posted-on">
                                        <i class="fa fa-calendar"></i>
                                        <?php echo get_the_date(); ?>
                                    </span>
                                    <span class="category">
                                        <i class="fa fa-folder"></i>
                                        <?php the_category(', '); ?>
                                    </span>
                                </div>

                                <h3 class="news-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>

                                <div class="news-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>

                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    <?php esc_html_e('Leia mais', 'ansegtv'); ?>
                                </a>
                            </div>
                        </article>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

        <div class="text-center mt-4">
            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-outline-primary">
                <?php esc_html_e('Ver todas as notícias', 'ansegtv'); ?>
            </a>
        </div>
    </div>
</section> 