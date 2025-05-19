<?php
/**
 * Template Name: Largura Total
 * Template Post Type: page
 */

get_header();
?>

<main id="primary" class="site-main full-width">
    <?php
    while (have_posts()) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            </header>

            <div class="entry-content">
                <?php
                the_content();

                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Páginas:', 'ansegtv'),
                    'after'  => '</div>',
                ));
                ?>
            </div>
        </article>
        <?php
    endwhile;
    ?>
</main>

<?php
get_footer(); 