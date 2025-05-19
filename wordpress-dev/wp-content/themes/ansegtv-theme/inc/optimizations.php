<?php
/**
 * Funções de otimização do tema
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Remove scripts e estilos desnecessários
 */
function ansegtv_dequeue_scripts() {
    // Remove emoji script
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');

    // Remove oEmbed
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');

    // Remove RSD link
    remove_action('wp_head', 'rsd_link');

    // Remove wlwmanifest link
    remove_action('wp_head', 'wlwmanifest_link');

    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');

    // Remove versão do WordPress
    remove_action('wp_head', 'wp_generator');
}
add_action('init', 'ansegtv_dequeue_scripts');

/**
 * Otimiza carregamento de scripts
 */
function ansegtv_optimize_scripts() {
    // Adiciona atributos de carregamento assíncrono/defer
    add_filter('script_loader_tag', function($tag, $handle) {
        if (is_admin()) {
            return $tag;
        }

        // Scripts que podem ser carregados de forma assíncrona
        $async_scripts = array(
            'ansegtv-navigation',
            'ansegtv-skip-link-focus-fix',
            'ansegtv-accessibility'
        );

        // Scripts que podem ser carregados com defer
        $defer_scripts = array(
            'jquery',
            'bootstrap'
        );

        if (in_array($handle, $async_scripts)) {
            return str_replace(' src', ' async src', $tag);
        }

        if (in_array($handle, $defer_scripts)) {
            return str_replace(' src', ' defer src', $tag);
        }

        return $tag;
    }, 10, 2);
}
add_action('wp_enqueue_scripts', 'ansegtv_optimize_scripts', 999);

/**
 * Otimiza carregamento de estilos
 */
function ansegtv_optimize_styles() {
    // Adiciona preload para fontes críticas
    add_action('wp_head', function() {
        ?>
        <link rel="preload" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/your-main-font.woff2" as="font" type="font/woff2" crossorigin>
        <?php
    }, 1);

    // Adiciona atributos de carregamento para estilos não críticos
    add_filter('style_loader_tag', function($tag, $handle) {
        if (is_admin()) {
            return $tag;
        }

        // Estilos que podem ser carregados com media="print" onload
        $print_styles = array(
            'bootstrap',
            'font-awesome'
        );

        if (in_array($handle, $print_styles)) {
            return str_replace("rel='stylesheet'", "rel='stylesheet' media='print' onload=\"this.media='all'\"", $tag);
        }

        return $tag;
    }, 10, 2);
}
add_action('wp_enqueue_scripts', 'ansegtv_optimize_styles', 999);

/**
 * Otimiza imagens
 */
function ansegtv_optimize_images() {
    // Adiciona lazy loading para imagens
    add_filter('wp_get_attachment_image_attributes', function($attr) {
        $attr['loading'] = 'lazy';
        return $attr;
    });

    // Adiciona srcset para imagens responsivas
    add_theme_support('post-thumbnails');
    add_image_size('ansegtv-featured', 1200, 600, true);
    add_image_size('ansegtv-thumbnail', 300, 200, true);
}
add_action('after_setup_theme', 'ansegtv_optimize_images');

/**
 * Otimiza consultas ao banco de dados
 */
function ansegtv_optimize_queries($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Limita o número de posts por página
        if (is_home() || is_archive()) {
            $query->set('posts_per_page', 12);
        }

        // Otimiza consultas de taxonomia
        if (is_tax() || is_category() || is_tag()) {
            $query->set('no_found_rows', true);
        }
    }
}
add_action('pre_get_posts', 'ansegtv_optimize_queries');

/**
 * Adiciona cache headers
 */
function ansegtv_add_cache_headers() {
    if (!is_admin()) {
        header('Cache-Control: public, max-age=31536000');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        header('Vary: Accept-Encoding');
    }
}
add_action('send_headers', 'ansegtv_add_cache_headers');

/**
 * Minifica HTML
 */
function ansegtv_minify_html($html) {
    if (!is_admin()) {
        $html = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $html);
        $html = preg_replace('/\s{2,}/', ' ', $html);
        $html = preg_replace('/\s*([{}|:;,])\s*/', '$1', $html);
        $html = preg_replace('/;}/', '}', $html);
        $html = preg_replace('/\s+/', ' ', $html);
        $html = preg_replace('/>\s+</', '><', $html);
        $html = preg_replace('/\s+>/', '>', $html);
        $html = preg_replace('/<\s+/', '<', $html);
    }
    return $html;
}
add_filter('final_output', 'ansegtv_minify_html'); 