<?php
/**
 * ANSEGTV Theme Customizer
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Adicionar configurações e controles ao Customizer
 */
function ansegtv_customize_register($wp_customize) {
    // Seção de Cores
    $wp_customize->add_section('ansegtv_colors', array(
        'title'    => __('Cores', 'ansegtv'),
        'priority' => 30,
    ));

    // Cor Primária
    $wp_customize->add_setting('ansegtv_primary_color', array(
        'default'           => '#007bff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'ansegtv_primary_color', array(
        'label'    => __('Cor Primária', 'ansegtv'),
        'section'  => 'ansegtv_colors',
        'settings' => 'ansegtv_primary_color',
    )));

    // Cor Secundária
    $wp_customize->add_setting('ansegtv_secondary_color', array(
        'default'           => '#6c757d',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'ansegtv_secondary_color', array(
        'label'    => __('Cor Secundária', 'ansegtv'),
        'section'  => 'ansegtv_colors',
        'settings' => 'ansegtv_secondary_color',
    )));

    // Seção de Redes Sociais
    $wp_customize->add_section('ansegtv_social_media', array(
        'title'    => __('Redes Sociais', 'ansegtv'),
        'priority' => 40,
    ));

    // Facebook
    $wp_customize->add_setting('ansegtv_facebook_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('ansegtv_facebook_url', array(
        'label'    => __('URL do Facebook', 'ansegtv'),
        'section'  => 'ansegtv_social_media',
        'type'     => 'url',
    ));

    // Twitter
    $wp_customize->add_setting('ansegtv_twitter_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('ansegtv_twitter_url', array(
        'label'    => __('URL do Twitter', 'ansegtv'),
        'section'  => 'ansegtv_social_media',
        'type'     => 'url',
    ));

    // Instagram
    $wp_customize->add_setting('ansegtv_instagram_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('ansegtv_instagram_url', array(
        'label'    => __('URL do Instagram', 'ansegtv'),
        'section'  => 'ansegtv_social_media',
        'type'     => 'url',
    ));

    // LinkedIn
    $wp_customize->add_setting('ansegtv_linkedin_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('ansegtv_linkedin_url', array(
        'label'    => __('URL do LinkedIn', 'ansegtv'),
        'section'  => 'ansegtv_social_media',
        'type'     => 'url',
    ));

    // Seção de Informações de Contato
    $wp_customize->add_section('ansegtv_contact_info', array(
        'title'    => __('Informações de Contato', 'ansegtv'),
        'priority' => 50,
    ));

    // Endereço
    $wp_customize->add_setting('ansegtv_address', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('ansegtv_address', array(
        'label'    => __('Endereço', 'ansegtv'),
        'section'  => 'ansegtv_contact_info',
        'type'     => 'textarea',
    ));

    // Telefone
    $wp_customize->add_setting('ansegtv_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('ansegtv_phone', array(
        'label'    => __('Telefone', 'ansegtv'),
        'section'  => 'ansegtv_contact_info',
        'type'     => 'text',
    ));

    // Email
    $wp_customize->add_setting('ansegtv_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ));

    $wp_customize->add_control('ansegtv_email', array(
        'label'    => __('Email', 'ansegtv'),
        'section'  => 'ansegtv_contact_info',
        'type'     => 'email',
    ));
}
add_action('customize_register', 'ansegtv_customize_register');

/**
 * Renderizar CSS personalizado no front-end
 */
function ansegtv_customizer_css() {
    ?>
    <style type="text/css">
        :root {
            --primary-color: <?php echo esc_attr(get_theme_mod('ansegtv_primary_color', '#007bff')); ?>;
            --secondary-color: <?php echo esc_attr(get_theme_mod('ansegtv_secondary_color', '#6c757d')); ?>;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        a {
            color: var(--primary-color);
        }

        a:hover {
            color: var(--secondary-color);
        }
    </style>
    <?php
}
add_action('wp_head', 'ansegtv_customizer_css'); 