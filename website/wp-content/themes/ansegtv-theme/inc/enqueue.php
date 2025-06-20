<?php
/**
 * Enfileirar scripts e estilos
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Enfileirar scripts e estilos
 */
function ansegtv_scripts() {
    // Estilos
    wp_enqueue_style('ansegtv-style', get_stylesheet_uri(), array(), ANSEGTV_THEME_VERSION);
    wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', array(), '4.3.1');
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), '4.7.0');
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap', array(), null);

    // Scripts
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js', array('jquery'), '4.3.1', true);
    wp_enqueue_script('ansegtv-navigation', ANSEGTV_THEME_URI . '/assets/js/navigation.js', array(), ANSEGTV_THEME_VERSION, true);
    wp_enqueue_script('ansegtv-skip-link-focus-fix', ANSEGTV_THEME_URI . '/assets/js/skip-link-focus-fix.js', array(), ANSEGTV_THEME_VERSION, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

/**
 * Adicionar atributos async/defer aos scripts
 */
function ansegtv_script_loader_tag($tag, $handle, $src) {
    if ('google-fonts' === $handle) {
        return str_replace("rel='stylesheet'", "rel='stylesheet' media='print' onload=\"this.media='all'\"", $tag);
    }
    return $tag;
}
add_filter('style_loader_tag', 'ansegtv_script_loader_tag', 10, 3);

/**
 * Remover versão do WordPress dos scripts e estilos
 */
function ansegtv_remove_version($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'ansegtv_remove_version', 9999);
add_filter('script_loader_src', 'ansegtv_remove_version', 9999);

/**
 * Adicionar suporte a SVG
 */
function ansegtv_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'ansegtv_mime_types');

/**
 * Adicionar suporte a WebP
 */
function ansegtv_webp_mime_types($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'ansegtv_webp_mime_types');

/**
 * Adicionar suporte a WebP no editor de mídia
 */
function ansegtv_webp_upload_mimes($existing_mimes) {
    $existing_mimes['webp'] = 'image/webp';
    return $existing_mimes;
}
add_filter('mime_types', 'ansegtv_webp_upload_mimes'); 