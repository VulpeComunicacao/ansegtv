<?php
/**
 * Template Name: Página de Contato
 * Template Post Type: page
 */

get_header();
?>

<main id="primary" class="site-main contact-page">
    <?php
    while (have_posts()) :
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            </header>

            <div class="entry-content">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        the_content();

                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Páginas:', 'ansegtv'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>

                    <div class="col-md-6">
                        <div class="contact-info">
                            <?php if (get_theme_mod('contact_address')) : ?>
                                <div class="contact-item">
                                    <i class="fa fa-map-marker"></i>
                                    <h3><?php esc_html_e('Endereço', 'ansegtv'); ?></h3>
                                    <p><?php echo esc_html(get_theme_mod('contact_address')); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if (get_theme_mod('contact_phone')) : ?>
                                <div class="contact-item">
                                    <i class="fa fa-phone"></i>
                                    <h3><?php esc_html_e('Telefone', 'ansegtv'); ?></h3>
                                    <p><?php echo esc_html(get_theme_mod('contact_phone')); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if (get_theme_mod('contact_email')) : ?>
                                <div class="contact-item">
                                    <i class="fa fa-envelope"></i>
                                    <h3><?php esc_html_e('E-mail', 'ansegtv'); ?></h3>
                                    <p><?php echo esc_html(get_theme_mod('contact_email')); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if (get_theme_mod('contact_hours')) : ?>
                                <div class="contact-item">
                                    <i class="fa fa-clock-o"></i>
                                    <h3><?php esc_html_e('Horário de Funcionamento', 'ansegtv'); ?></h3>
                                    <p><?php echo esc_html(get_theme_mod('contact_hours')); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (get_theme_mod('contact_map')) : ?>
                            <div class="contact-map">
                                <?php echo wp_kses_post(get_theme_mod('contact_map')); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="contact-form">
                    <?php echo do_shortcode(get_theme_mod('contact_form_shortcode', '[contact-form-7 id="123" title="Formulário de Contato"]')); ?>
                </div>
            </div>
        </article>
        <?php
    endwhile;
    ?>
</main>

<?php
get_footer(); 