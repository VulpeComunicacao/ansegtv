<?php
/**
 * O template para exibir páginas de erro 404 (não encontrado)
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <section class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('Oops! Página não encontrada.', 'ansegtv'); ?></h1>
            </header><!-- .page-header -->

            <div class="page-content">
                <p><?php esc_html_e('Parece que nada foi encontrado neste local. Talvez tente uma busca?', 'ansegtv'); ?></p>

                <?php get_search_form(); ?>

                <div class="error-404-suggestions">
                    <h2><?php esc_html_e('Você pode estar procurando por:', 'ansegtv'); ?></h2>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h3><?php esc_html_e('Páginas Populares', 'ansegtv'); ?></h3>
                            <ul>
                                <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Página Inicial', 'ansegtv'); ?></a></li>
                                <li><a href="<?php echo esc_url(home_url('/sobre/')); ?>"><?php esc_html_e('Sobre', 'ansegtv'); ?></a></li>
                                <li><a href="<?php echo esc_url(home_url('/contato/')); ?>"><?php esc_html_e('Contato', 'ansegtv'); ?></a></li>
                            </ul>
                        </div>

                        <div class="col-md-6">
                            <h3><?php esc_html_e('Categorias', 'ansegtv'); ?></h3>
                            <ul>
                                <?php
                                wp_list_categories(array(
                                    'orderby'    => 'count',
                                    'order'      => 'DESC',
                                    'show_count' => 1,
                                    'title_li'   => '',
                                    'number'     => 5,
                                ));
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div><!-- .page-content -->
        </section><!-- .error-404 -->
    </div>
</main><!-- #main -->

<?php
get_footer(); 