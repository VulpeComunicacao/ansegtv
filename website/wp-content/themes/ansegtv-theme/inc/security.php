<?php
/**
 * Funções de segurança do tema
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Adiciona headers de segurança
 */
function ansegtv_security_headers() {
    // Previne clickjacking
    header('X-Frame-Options: SAMEORIGIN');
    
    // Ativa proteção XSS no navegador
    header('X-XSS-Protection: 1; mode=block');
    
    // Previne MIME-type sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Configura política de segurança de conteúdo
    header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' *.googleapis.com *.gstatic.com *.google.com *.google-analytics.com *.doubleclick.net data: https:; img-src 'self' data: https: http:; media-src 'self' https: http:;");
    
    // Configura política de referrer
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Configura política de permissões
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}
add_action('send_headers', 'ansegtv_security_headers');

/**
 * Remove informações sensíveis do WordPress
 */
function ansegtv_remove_sensitive_info() {
    // Remove versão do WordPress
    remove_action('wp_head', 'wp_generator');
    
    // Remove versão do tema e plugins
    add_filter('style_loader_src', 'ansegtv_remove_version_strings');
    add_filter('script_loader_src', 'ansegtv_remove_version_strings');
    
    // Remove informações de versão do feed
    add_filter('the_generator', '__return_empty_string');
}
add_action('init', 'ansegtv_remove_sensitive_info');

/**
 * Remove strings de versão de URLs
 */
function ansegtv_remove_version_strings($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

/**
 * Sanitiza dados de entrada
 */
function ansegtv_sanitize_input($data) {
    if (is_array($data)) {
        return array_map('ansegtv_sanitize_input', $data);
    }
    return sanitize_text_field($data);
}

/**
 * Valida e sanitiza dados de formulário
 */
function ansegtv_validate_form_data($data) {
    $sanitized_data = array();
    
    foreach ($data as $key => $value) {
        switch ($key) {
            case 'email':
                $sanitized_data[$key] = sanitize_email($value);
                break;
            case 'url':
                $sanitized_data[$key] = esc_url_raw($value);
                break;
            case 'textarea':
                $sanitized_data[$key] = sanitize_textarea_field($value);
                break;
            default:
                $sanitized_data[$key] = sanitize_text_field($value);
        }
    }
    
    return $sanitized_data;
}

/**
 * Protege contra ataques de força bruta
 */
function ansegtv_limit_login_attempts($user, $username, $password) {
    if (!session_id()) {
        session_start();
    }
    
    $attempts = isset($_SESSION['login_attempts']) ? $_SESSION['login_attempts'] : 0;
    $last_attempt = isset($_SESSION['last_attempt']) ? $_SESSION['last_attempt'] : 0;
    
    // Limpa tentativas após 30 minutos
    if (time() - $last_attempt > 1800) {
        $attempts = 0;
    }
    
    if ($attempts >= 5) {
        return new WP_Error('too_many_attempts', __('Muitas tentativas de login. Por favor, tente novamente mais tarde.', 'ansegtv'));
    }
    
    $_SESSION['login_attempts'] = $attempts + 1;
    $_SESSION['last_attempt'] = time();
    
    return $user;
}
add_filter('authenticate', 'ansegtv_limit_login_attempts', 30, 3);

/**
 * Desabilita edição de arquivos no admin
 */
function ansegtv_disable_file_edit() {
    if (!defined('DISALLOW_FILE_EDIT')) {
        define('DISALLOW_FILE_EDIT', true);
    }
}
add_action('admin_init', 'ansegtv_disable_file_edit');

/**
 * Protege contra ataques XSS em comentários
 */
function ansegtv_comment_xss_protection($commentdata) {
    $commentdata['comment_content'] = wp_kses_post($commentdata['comment_content']);
    $commentdata['comment_author'] = sanitize_text_field($commentdata['comment_author']);
    $commentdata['comment_author_email'] = sanitize_email($commentdata['comment_author_email']);
    $commentdata['comment_author_url'] = esc_url_raw($commentdata['comment_author_url']);
    
    return $commentdata;
}
add_filter('preprocess_comment', 'ansegtv_comment_xss_protection');

/**
 * Adiciona nonce para formulários
 */
function ansegtv_add_nonce() {
    wp_nonce_field('ansegtv_nonce', 'ansegtv_nonce');
}
add_action('comment_form', 'ansegtv_add_nonce');

/**
 * Verifica nonce em formulários
 */
function ansegtv_verify_nonce() {
    if (!isset($_POST['ansegtv_nonce']) || !wp_verify_nonce($_POST['ansegtv_nonce'], 'ansegtv_nonce')) {
        wp_die(__('Ação não autorizada.', 'ansegtv'));
    }
}
add_action('pre_comment_on_post', 'ansegtv_verify_nonce');

/**
 * Desabilita XML-RPC
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Desabilita pingback
 */
function ansegtv_disable_pingback($methods) {
    unset($methods['pingback.ping']);
    unset($methods['pingback.extensions.getPingbacks']);
    return $methods;
}
add_filter('xmlrpc_methods', 'ansegtv_disable_pingback');

/**
 * Adiciona proteção contra hotlinking
 */
function ansegtv_prevent_hotlinking() {
    if (!is_admin()) {
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $allowed_domains = array(
            'ansegtv.com.br',
            'www.ansegtv.com.br'
        );
        
        $is_allowed = false;
        foreach ($allowed_domains as $domain) {
            if (strpos($referer, $domain) !== false) {
                $is_allowed = true;
                break;
            }
        }
        
        if (!$is_allowed && !empty($referer)) {
            header('HTTP/1.0 403 Forbidden');
            exit;
        }
    }
}
add_action('template_redirect', 'ansegtv_prevent_hotlinking'); 