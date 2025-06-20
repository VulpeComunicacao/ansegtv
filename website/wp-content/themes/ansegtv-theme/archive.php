<?php
/**
 * O template para exibir arquivos
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>
            <header class="page-header">
                <?php
                the_archive_title('<h1 class="page-title">', '</h1>');
                the_archive_description('<div class="archive-description">', '</div>');
                ?>
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
            <p><?php esc_html_e('Nenhum conteúdo encontrado.', 'ansegtv'); ?></p>
            <?php
        endif;
        ?>
    </div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer(); 