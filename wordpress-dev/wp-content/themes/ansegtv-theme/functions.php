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
    ));

    // Registrar menus
    register_nav_menus(array(
        'primary' => esc_html__('Menu Principal', 'ansegtv'),
        'footer' => esc_html__('Menu Rodapé', 'ansegtv'),
    ));
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

// Enfileirar scripts e estilos
function ansegtv_scripts() {
    // Estilos
    wp_enqueue_style('ansegtv-style', get_stylesheet_uri(), array(), ANSEGTV_THEME_VERSION);
    wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    
    // Scripts
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js', array('jquery'), '4.3.1', true);
    wp_enqueue_script('ansegtv-navigation', ANSEGTV_THEME_URI . '/assets/js/navigation.js', array(), ANSEGTV_THEME_VERSION, true);
    wp_enqueue_script('ansegtv-skip-link-focus-fix', ANSEGTV_THEME_URI . '/assets/js/skip-link-focus-fix.js', array(), ANSEGTV_THEME_VERSION, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'ansegtv_scripts'); 