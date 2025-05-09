<?php
/**
 * Configurações adicionais do tema
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Configurações do tema
 */
function ansegtv_setup_theme() {
    // Adicionar suporte a recursos do WordPress
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');
    add_theme_support('automatic-feed-links');

    // Registrar menus
    register_nav_menus(array(
        'primary' => esc_html__('Menu Principal', 'ansegtv'),
        'footer'  => esc_html__('Menu Rodapé', 'ansegtv'),
    ));

    // Registrar sidebars
    register_sidebar(array(
        'name'          => esc_html__('Sidebar Principal', 'ansegtv'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Adicione widgets aqui.', 'ansegtv'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Rodapé 1', 'ansegtv'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Adicione widgets aqui.', 'ansegtv'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Rodapé 2', 'ansegtv'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Adicione widgets aqui.', 'ansegtv'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Rodapé 3', 'ansegtv'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Adicione widgets aqui.', 'ansegtv'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('after_setup_theme', 'ansegtv_setup_theme');

/**
 * Definir o tamanho máximo do conteúdo
 */
function ansegtv_content_width() {
    $GLOBALS['content_width'] = apply_filters('ansegtv_content_width', 1200);
}
add_action('after_setup_theme', 'ansegtv_content_width', 0);

/**
 * Registrar tamanhos de imagem personalizados
 */
function ansegtv_custom_image_sizes() {
    add_image_size('ansegtv-featured', 1200, 600, true);
    add_image_size('ansegtv-thumbnail', 400, 300, true);
}
add_action('after_setup_theme', 'ansegtv_custom_image_sizes');

/**
 * Adicionar classes ao body
 */
function ansegtv_body_classes($classes) {
    // Adiciona uma classe se não houver sidebar
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    // Adiciona uma classe se houver uma imagem de destaque personalizada
    if (is_singular() && has_post_thumbnail()) {
        $classes[] = 'has-featured-image';
    }

    return $classes;
}
add_filter('body_class', 'ansegtv_body_classes');

/**
 * Adicionar classes aos posts
 */
function ansegtv_post_classes($classes) {
    if (is_singular()) {
        $classes[] = 'single-post';
    }

    return $classes;
}
add_filter('post_class', 'ansegtv_post_classes'); 