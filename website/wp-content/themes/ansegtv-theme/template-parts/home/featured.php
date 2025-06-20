<?php
/**
 * Template para a seção de destaques da página inicial
 */

$featured_title = get_theme_mod('featured_title', 'Destaques');
$featured_category = get_theme_mod('featured_category', 'destaques');
$featured_posts = get_posts(array(
    'category_name' => $featured_category,
    'posts_per_page' => 3,
    'post_status' => 'publish'
));
?>

<section class="featured-section">
    <div class="container">
        <h2 class="section-title animate-on-scroll"><?php echo esc_html($featured_title); ?></h2>
        
        <div class="row">
            <?php
            if ($featured_posts) :
                foreach ($featured_posts as $index => $post) :
                    setup_postdata($post);
                    ?>
                    <div class="col-md-4">
                        <article class="featured-item animate-on-scroll delay-<?php echo ($index + 1) * 200; ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="featured-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('large'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <div class="featured-content">
                                <h3 class="featured-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <div class="featured-meta">
                                    <span class="posted-on">
                                        <i class="fa fa-calendar"></i>
                                        <?php echo get_the_date(); ?>
                                    </span>
                                    <span class="comments-link">
                                        <i class="fa fa-comments"></i>
                                        <?php comments_number('0', '1', '%'); ?>
                                    </span>
                                </div>

                                <div class="featured-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>

                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    <?php esc_html_e('Leia mais', 'ansegtv'); ?>
                                </a>
                            </div>
                        </article>
                    </div>
                    <?php
                endforeach;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section> 