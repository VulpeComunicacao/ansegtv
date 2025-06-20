<?php
/**
 * O template principal para exibir posts
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        if (have_posts()) :
            if (is_home() && !is_front_page()) :
                ?>
                <header>
                    <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                </header>
                <?php
            endif;

            /* Iniciar o loop */
            while (have_posts()) :
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php
                        if (is_singular()) :
                            the_title('<h1 class="entry-title">', '</h1>');
                        else :
                            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                        endif;

                        if ('post' === get_post_type()) :
                            ?>
                            <div class="entry-meta">
                                <span class="posted-on">
                                    <?php echo get_the_date(); ?>
                                </span>
                                <span class="byline">
                                    <?php echo get_the_author(); ?>
                                </span>
                            </div><!-- .entry-meta -->
                        <?php endif; ?>
                    </header><!-- .entry-header -->

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php
                        if (is_singular()) :
                            the_content();
                        else :
                            the_excerpt();
                            ?>
                            <a href="<?php the_permalink(); ?>" class="read-more">
                                <?php esc_html_e('Leia mais', 'ansegtv'); ?>
                            </a>
                        <?php endif; ?>
                    </div><!-- .entry-content -->

                    <footer class="entry-footer">
                        <?php
                        $categories_list = get_the_category_list(esc_html__(', ', 'ansegtv'));
                        if ($categories_list) {
                            printf('<span class="cat-links">' . esc_html__('Categorias: %1$s', 'ansegtv') . '</span>', $categories_list);
                        }

                        $tags_list = get_the_tag_list('', esc_html_x(', ', 'lista de tags', 'ansegtv'));
                        if ($tags_list) {
                            printf('<span class="tags-links">' . esc_html__('Tags: %1$s', 'ansegtv') . '</span>', $tags_list);
                        }
                        ?>
                    </footer><!-- .entry-footer -->
                </article><!-- #post-<?php the_ID(); ?> -->
                <?php
            endwhile;

            the_posts_navigation(array(
                'prev_text' => esc_html__('Posts anteriores', 'ansegtv'),
                'next_text' => esc_html__('Próximos posts', 'ansegtv'),
            ));

        else :
            ?>
            <p><?php esc_html_e('Nenhum conteúdo encontrado.', 'ansegtv'); ?></p>
            <?php
        endif;
        ?>
    </div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer(); 