<?php
/**
 * O template para exibir resultados de busca
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>
            <header class="page-header">
                <h1 class="page-title">
                    <?php
                    /* translators: %s: termo de busca */
                    printf(esc_html__('Resultados da busca para: %s', 'ansegtv'), '<span>' . get_search_query() . '</span>');
                    ?>
                </h1>
            </header><!-- .page-header -->

            <div class="row">
                <?php
                /* Iniciar o loop */
                while (have_posts()) :
                    the_post();
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>

                            <header class="entry-header">
                                <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>

                                <div class="entry-meta">
                                    <span class="posted-on">
                                        <i class="fa fa-calendar"></i>
                                        <?php echo get_the_date(); ?>
                                    </span>
                                    <span class="byline">
                                        <i class="fa fa-user"></i>
                                        <?php echo get_the_author(); ?>
                                    </span>
                                </div><!-- .entry-meta -->
                            </header><!-- .entry-header -->

                            <div class="entry-content">
                                <?php the_excerpt(); ?>
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    <?php esc_html_e('Leia mais', 'ansegtv'); ?>
                                </a>
                            </div><!-- .entry-content -->
                        </article><!-- #post-<?php the_ID(); ?> -->
                    </div>
                <?php endwhile; ?>
            </div>

            <?php
            the_posts_pagination(array(
                'prev_text' => '<i class="fa fa-angle-left"></i> ' . esc_html__('Anterior', 'ansegtv'),
                'next_text' => esc_html__('Próximo', 'ansegtv') . ' <i class="fa fa-angle-right"></i>',
            ));

        else :
            ?>
            <div class="no-results">
                <h2><?php esc_html_e('Nada encontrado', 'ansegtv'); ?></h2>
                <p><?php esc_html_e('Desculpe, mas nada corresponde aos seus termos de busca. Por favor, tente novamente com algumas palavras-chave diferentes.', 'ansegtv'); ?></p>
                <?php get_search_form(); ?>
            </div>
            <?php
        endif;
        ?>
    </div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer(); 