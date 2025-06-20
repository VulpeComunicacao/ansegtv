<?php
/**
 * Template para a seção de vídeos da página inicial
 */

$videos_title = get_theme_mod('videos_title', 'Vídeos em Destaque');
$videos_category = get_theme_mod('videos_category', 'videos');
$videos_count = get_theme_mod('videos_count', 4);
$videos = new WP_Query(array(
    'post_type' => 'post',
    'category_name' => $videos_category,
    'posts_per_page' => $videos_count,
    'post_status' => 'publish'
));
?>

<section class="videos-section">
    <div class="container">
        <h2 class="section-title animate-on-scroll"><?php echo esc_html($videos_title); ?></h2>
        
        <div class="row">
            <?php
            if ($videos->have_posts()) :
                while ($videos->have_posts()) : $videos->the_post();
                    $video_url = get_post_meta(get_the_ID(), 'video_url', true);
                    ?>
                    <div class="col-md-6 col-lg-3">
                        <article class="video-item animate-on-scroll">
                            <div class="video-thumbnail">
                                <?php if (has_post_thumbnail()) : ?>
                                    <?php the_post_thumbnail('medium'); ?>
                                <?php endif; ?>
                                
                                <?php if ($video_url) : ?>
                                    <a href="<?php echo esc_url($video_url); ?>" class="video-play-btn" data-fancybox>
                                        <i class="fa fa-play"></i>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <div class="video-content">
                                <h3 class="video-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>

                                <div class="video-meta">
                                    <span class="posted-on">
                                        <i class="fa fa-calendar"></i>
                                        <?php echo get_the_date(); ?>
                                    </span>
                                    <span class="views">
                                        <i class="fa fa-eye"></i>
                                        <?php echo get_post_meta(get_the_ID(), 'video_views', true) ?: '0'; ?>
                                    </span>
                                </div>
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
            <a href="<?php echo esc_url(get_category_link(get_cat_ID($videos_category))); ?>" class="btn btn-outline-primary">
                <?php esc_html_e('Ver todos os vídeos', 'ansegtv'); ?>
            </a>
        </div>
    </div>
</section> 