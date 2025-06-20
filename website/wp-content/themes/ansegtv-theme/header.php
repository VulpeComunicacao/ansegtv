<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Pular para o conteúdo', 'ansegtv'); ?></a>

    <header id="masthead" class="site-header" role="banner">
        <div class="container">
            <div class="site-branding">
                <?php
                if (has_custom_logo()) :
                    the_custom_logo();
                else :
                ?>
                    <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                    <?php
                    $ansegtv_description = get_bloginfo('description', 'display');
                    if ($ansegtv_description || is_customize_preview()) :
                        ?>
                        <p class="site-description">
                            <?php echo $ansegtv_description; ?>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            </div><!-- .site-branding -->

            <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Menu Principal', 'ansegtv'); ?>">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="screen-reader-text"><?php esc_html_e('Menu', 'ansegtv'); ?></span>
                    <span class="menu-icon"></span>
                </button>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_id' => 'primary-menu',
                        'container' => false,
                        'menu_class' => 'nav-menu',
                        'fallback_cb' => false,
                        'items_wrap' => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>',
                        'walker' => new ANSEGTV_Walker_Nav_Menu(),
                    )
                );
                ?>
            </nav><!-- #site-navigation -->

            <div class="header-actions">
                <button class="high-contrast-toggle" aria-label="<?php esc_attr_e('Alternar modo de alto contraste', 'ansegtv'); ?>" aria-pressed="false">
                    <span class="screen-reader-text"><?php esc_html_e('Alto Contraste', 'ansegtv'); ?></span>
                    <span class="contrast-icon"></span>
                </button>

                <button class="font-size-toggle" aria-label="<?php esc_attr_e('Alternar tamanho da fonte', 'ansegtv'); ?>" aria-pressed="false">
                    <span class="screen-reader-text"><?php esc_html_e('Tamanho da Fonte', 'ansegtv'); ?></span>
                    <span class="font-size-icon"></span>
                </button>

                <button class="search-toggle" aria-label="<?php esc_attr_e('Abrir busca', 'ansegtv'); ?>" aria-expanded="false" aria-controls="search-form">
                    <span class="screen-reader-text"><?php esc_html_e('Buscar', 'ansegtv'); ?></span>
                    <span class="search-icon"></span>
                </button>
            </div>

            <div id="search-form" class="search-form" role="search" aria-label="<?php esc_attr_e('Formulário de busca', 'ansegtv'); ?>" aria-hidden="true">
                <?php get_search_form(); ?>
            </div>
        </div>
    </header><!-- #masthead --> 