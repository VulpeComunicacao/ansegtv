<?php
/**
 * O template para exibir posts individuais
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

                    <div class="entry-meta">
                        <span class="posted-on">
                            <i class="fa fa-calendar"></i>
                            <?php echo get_the_date(); ?>
                        </span>
                        <span class="byline">
                            <i class="fa fa-user"></i>
                            <?php echo get_the_author(); ?>
                        </span>
                        <?php if (has_category()) : ?>
                            <span class="cat-links">
                                <i class="fa fa-folder"></i>
                                <?php the_category(', '); ?>
                            </span>
                        <?php endif; ?>
                    </div><!-- .entry-meta -->
                </header><!-- .entry-header -->

                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Páginas:', 'ansegtv'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div><!-- .entry-content -->

                <footer class="entry-footer">
                    <?php
                    $tags_list = get_the_tag_list('', esc_html_x(', ', 'lista de tags', 'ansegtv'));
                    if ($tags_list) {
                        printf('<span class="tags-links"><i class="fa fa-tags"></i> ' . esc_html__('Tags: %1$s', 'ansegtv') . '</span>', $tags_list);
                    }
                    ?>
                </footer><!-- .entry-footer -->
            </article><!-- #post-<?php the_ID(); ?> -->

            <?php
            // Se os comentários estão abertos ou temos pelo menos um comentário, carregue o template de comentários.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;

            // Navegação de posts anteriores/próximos
            the_post_navigation(array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__('Anterior:', 'ansegtv') . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__('Próximo:', 'ansegtv') . '</span> <span class="nav-title">%title</span>',
            ));

        endwhile; // End of the loop.
        ?>
    </div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer(); 