<?php
/**
 * Funções auxiliares do tema
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Exibir a data do post formatada
 */
function ansegtv_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date()),
        esc_attr(get_the_modified_date(DATE_W3C)),
        esc_html(get_the_modified_date())
    );

    $posted_on = sprintf(
        /* translators: %s: post date. */
        esc_html_x('Publicado em %s', 'post date', 'ansegtv'),
        '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Exibir o autor do post
 */
function ansegtv_posted_by() {
    $byline = sprintf(
        /* translators: %s: post author. */
        esc_html_x('por %s', 'post author', 'ansegtv'),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );

    echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Exibir categorias do post
 */
function ansegtv_post_categories() {
    $categories_list = get_the_category_list(esc_html__(', ', 'ansegtv'));
    if ($categories_list) {
        printf('<span class="cat-links">' . esc_html__('Categorias: %1$s', 'ansegtv') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}

/**
 * Exibir tags do post
 */
function ansegtv_post_tags() {
    $tags_list = get_the_tag_list('', esc_html_x(', ', 'lista de tags', 'ansegtv'));
    if ($tags_list) {
        printf('<span class="tags-links">' . esc_html__('Tags: %1$s', 'ansegtv') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}

/**
 * Exibir link de edição do post
 */
function ansegtv_edit_link() {
    edit_post_link(
        sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __('Editar <span class="screen-reader-text">%s</span>', 'ansegtv'),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            wp_kses_post(get_the_title())
        ),
        '<span class="edit-link">',
        '</span>'
    );
}

/**
 * Exibir link "Leia mais"
 */
function ansegtv_read_more_link() {
    return '<a class="read-more" href="' . esc_url(get_permalink()) . '">' . esc_html__('Leia mais', 'ansegtv') . '</a>';
}
add_filter('the_content_more_link', 'ansegtv_read_more_link');

/**
 * Exibir breadcrumbs
 */
function ansegtv_breadcrumbs() {
    if (is_front_page()) {
        return;
    }

    echo '<nav class="breadcrumb" aria-label="breadcrumb">';
    echo '<ol class="breadcrumb">';
    echo '<li class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Início', 'ansegtv') . '</a></li>';

    if (is_category() || is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            echo '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a></li>';
        }
        if (is_single()) {
            echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
        }
    } elseif (is_page()) {
        echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
    } elseif (is_search()) {
        echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('Resultados da busca', 'ansegtv') . '</li>';
    } elseif (is_404()) {
        echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('Página não encontrada', 'ansegtv') . '</li>';
    }

    echo '</ol>';
    echo '</nav>';
}

/**
 * Exibir redes sociais
 */
function ansegtv_social_media_links() {
    $social_media = array(
        'facebook'  => get_theme_mod('ansegtv_facebook_url'),
        'twitter'   => get_theme_mod('ansegtv_twitter_url'),
        'instagram' => get_theme_mod('ansegtv_instagram_url'),
        'linkedin'  => get_theme_mod('ansegtv_linkedin_url'),
    );

    $output = '<div class="social-media-links">';
    foreach ($social_media as $platform => $url) {
        if (!empty($url)) {
            $output .= '<a href="' . esc_url($url) . '" class="social-media-link ' . esc_attr($platform) . '" target="_blank" rel="noopener noreferrer">';
            $output .= '<i class="fa fa-' . esc_attr($platform) . '"></i>';
            $output .= '<span class="screen-reader-text">' . esc_html(ucfirst($platform)) . '</span>';
            $output .= '</a>';
        }
    }
    $output .= '</div>';

    echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Exibir informações de contato
 */
function ansegtv_contact_info() {
    $address = get_theme_mod('ansegtv_address');
    $phone   = get_theme_mod('ansegtv_phone');
    $email   = get_theme_mod('ansegtv_email');

    if (!empty($address) || !empty($phone) || !empty($email)) {
        echo '<div class="contact-info">';
        
        if (!empty($address)) {
            echo '<div class="contact-info-item address">';
            echo '<i class="fa fa-map-marker"></i>';
            echo '<span>' . esc_html($address) . '</span>';
            echo '</div>';
        }

        if (!empty($phone)) {
            echo '<div class="contact-info-item phone">';
            echo '<i class="fa fa-phone"></i>';
            echo '<a href="tel:' . esc_attr($phone) . '">' . esc_html($phone) . '</a>';
            echo '</div>';
        }

        if (!empty($email)) {
            echo '<div class="contact-info-item email">';
            echo '<i class="fa fa-envelope"></i>';
            echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
            echo '</div>';
        }

        echo '</div>';
    }
} 