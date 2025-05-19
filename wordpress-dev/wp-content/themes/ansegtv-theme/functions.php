<?php
/**
 * ANSEGTV Theme functions and definitions
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Definir constantes do tema
define('ANSEGTV_THEME_DIR', get_template_directory());
define('ANSEGTV_THEME_URI', get_template_directory_uri());
define('ANSEGTV_THEME_VERSION', '1.0.0');

// Incluir arquivos de configuração
require_once ANSEGTV_THEME_DIR . '/inc/setup.php';
require_once ANSEGTV_THEME_DIR . '/inc/enqueue.php';
require_once ANSEGTV_THEME_DIR . '/inc/customizer.php';
require_once ANSEGTV_THEME_DIR . '/inc/template-functions.php';

/**
 * Inclui arquivos adicionais
 */
// require get_template_directory() . '/inc/template-tags.php'; // Comentado pois o arquivo não foi encontrado no servidor.
require get_template_directory() . '/inc/accessibility.php';
require get_template_directory() . '/inc/class-ansegtv-walker-nav-menu.php';
require get_template_directory() . '/inc/optimizations.php';
require get_template_directory() . '/inc/security.php';
require get_template_directory() . '/inc/i18n.php';

// Configuração do tema
function ansegtv_setup() {
    // Adicionar suporte a recursos do WordPress
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Registrar menus
    register_nav_menus(array(
        'primary' => esc_html__('Menu Principal', 'ansegtv'),
        'footer' => esc_html__('Menu Rodapé', 'ansegtv'),
    ));

    // Adiciona suporte a personalização
    add_theme_support('customize-selective-refresh-widgets');

    // Adiciona suporte a logo personalizada
    add_theme_support(
        'custom-logo',
        array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        )
    );
}
add_action('after_setup_theme', 'ansegtv_setup');

// Registrar sidebars
function ansegtv_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar Principal', 'ansegtv'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Adicione widgets aqui.', 'ansegtv'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'ansegtv_widgets_init');

/**
 * Implementa recursos de acessibilidade
 */
function ansegtv_accessibility() {
    // Adiciona suporte a ARIA labels
    add_filter('nav_menu_link_attributes', 'ansegtv_aria_labels', 10, 3);
    
    // Adiciona suporte a skip links
    add_action('wp_body_open', 'ansegtv_skip_link');
    
    // Adiciona suporte a alto contraste
    add_action('wp_head', 'ansegtv_high_contrast_mode');
    
    // Adiciona suporte a aria-current
    add_filter('nav_menu_css_class', 'ansegtv_aria_current', 10, 2);
    
    // Adiciona suporte a aria-expanded
    add_filter('walker_nav_menu_start_el', 'ansegtv_aria_expanded', 10, 4);
    
    // Adiciona suporte a aria-live
    add_filter('the_content', 'ansegtv_aria_live');
    
    // Adiciona suporte a aria-controls
    add_filter('the_content', 'ansegtv_aria_controls');
    
    // Adiciona suporte a aria-describedby
    add_filter('the_content', 'ansegtv_aria_describedby');
}
add_action('after_setup_theme', 'ansegtv_accessibility'); 