<?php
/**
 * Funções de acessibilidade
 */

if (!function_exists('ansegtv_skip_link')) :
    /**
     * Adiciona link de skip para o conteúdo principal
     */
    function ansegtv_skip_link() {
        ?>
        <a class="skip-link screen-reader-text" href="#primary">
            <?php esc_html_e('Pular para o conteúdo', 'ansegtv'); ?>
        </a>
        <?php
    }
endif;
add_action('wp_body_open', 'ansegtv_skip_link');

if (!function_exists('ansegtv_aria_labels')) :
    /**
     * Adiciona labels ARIA aos elementos
     */
    function ansegtv_aria_labels($content) {
        // Adiciona aria-label aos links de menu
        $content = str_replace(
            '<a href="',
            '<a aria-label="' . esc_attr__('Ir para', 'ansegtv') . ' " href="',
            $content
        );

        // Adiciona aria-label aos botões de formulário
        $content = str_replace(
            '<button type="submit"',
            '<button type="submit" aria-label="' . esc_attr__('Enviar formulário', 'ansegtv') . '"',
            $content
        );

        return $content;
    }
endif;
add_filter('the_content', 'ansegtv_aria_labels');

if (!function_exists('ansegtv_high_contrast_mode')) :
    /**
     * Adiciona suporte a modo de alto contraste
     */
    function ansegtv_high_contrast_mode() {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Verifica preferência do usuário
                const prefersContrast = window.matchMedia('(prefers-contrast: more)');
                
                // Adiciona classe se preferir alto contraste
                if (prefersContrast.matches) {
                    document.body.classList.add('high-contrast');
                }

                // Monitora mudanças na preferência
                prefersContrast.addEventListener('change', function(e) {
                    if (e.matches) {
                        document.body.classList.add('high-contrast');
                    } else {
                        document.body.classList.remove('high-contrast');
                    }
                });
            });
        </script>
        <?php
    }
endif;
add_action('wp_head', 'ansegtv_high_contrast_mode');

if (!function_exists('ansegtv_aria_current')) :
    /**
     * Adiciona atributo aria-current aos links ativos
     */
    function ansegtv_aria_current($classes, $item) {
        if (in_array('current-menu-item', $classes)) {
            $classes[] = 'aria-current="page"';
        }
        return $classes;
    }
endif;
add_filter('nav_menu_css_class', 'ansegtv_aria_current', 10, 2);

if (!function_exists('ansegtv_aria_expanded')) :
    /**
     * Adiciona atributo aria-expanded aos menus dropdown
     */
    function ansegtv_aria_expanded($item_output, $item, $depth, $args) {
        if (in_array('menu-item-has-children', $item->classes)) {
            $item_output = str_replace(
                '<a',
                '<a aria-expanded="false" aria-haspopup="true"',
                $item_output
            );
        }
        return $item_output;
    }
endif;
add_filter('walker_nav_menu_start_el', 'ansegtv_aria_expanded', 10, 4);

if (!function_exists('ansegtv_aria_live')) :
    /**
     * Adiciona atributo aria-live às regiões dinâmicas
     */
    function ansegtv_aria_live($content) {
        // Adiciona aria-live aos resultados de busca
        $content = str_replace(
            '<div class="search-results">',
            '<div class="search-results" aria-live="polite">',
            $content
        );

        // Adiciona aria-live aos comentários
        $content = str_replace(
            '<div class="comments-area">',
            '<div class="comments-area" aria-live="polite">',
            $content
        );

        return $content;
    }
endif;
add_filter('the_content', 'ansegtv_aria_live');

if (!function_exists('ansegtv_aria_controls')) :
    /**
     * Adiciona atributo aria-controls aos elementos controlados
     */
    function ansegtv_aria_controls($content) {
        // Adiciona aria-controls aos botões de menu mobile
        $content = str_replace(
            '<button class="menu-toggle"',
            '<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"',
            $content
        );

        // Adiciona aria-controls aos botões de accordion
        $content = str_replace(
            '<button class="accordion-toggle"',
            '<button class="accordion-toggle" aria-controls="accordion-content" aria-expanded="false"',
            $content
        );

        return $content;
    }
endif;
add_filter('the_content', 'ansegtv_aria_controls');

if (!function_exists('ansegtv_aria_describedby')) :
    /**
     * Adiciona atributo aria-describedby aos elementos com descrição
     */
    function ansegtv_aria_describedby($content) {
        // Adiciona aria-describedby aos campos de formulário
        $content = str_replace(
            '<input',
            '<input aria-describedby="field-description"',
            $content
        );

        // Adiciona aria-describedby aos botões
        $content = str_replace(
            '<button',
            '<button aria-describedby="button-description"',
            $content
        );

        return $content;
    }
endif;
add_filter('the_content', 'ansegtv_aria_describedby'); 