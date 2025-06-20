<?php
/**
 * Funções de internacionalização do tema
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Carrega o texto do tema para tradução
 */
function ansegtv_i18n_setup() {
    load_theme_textdomain('ansegtv', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'ansegtv_i18n_setup');

/**
 * Adiciona suporte a RTL
 */
function ansegtv_rtl_support() {
    add_theme_support('rtl');
    add_theme_support('automatic-feed-links');
}
add_action('after_setup_theme', 'ansegtv_rtl_support');

/**
 * Adiciona suporte a diferentes formatos de data
 */
function ansegtv_date_formats() {
    return array(
        'default' => get_option('date_format'),
        'short' => 'd/m/Y',
        'long' => 'd \d\e F \d\e Y',
        'time' => get_option('time_format'),
        'datetime' => get_option('date_format') . ' ' . get_option('time_format')
    );
}

/**
 * Formata data de acordo com o locale
 */
function ansegtv_format_date($date, $format = 'default') {
    $formats = ansegtv_date_formats();
    $format = isset($formats[$format]) ? $formats[$format] : $formats['default'];
    
    return date_i18n($format, strtotime($date));
}

/**
 * Adiciona suporte a diferentes formatos de número
 */
function ansegtv_number_format($number, $decimals = 0) {
    return number_format_i18n($number, $decimals);
}

/**
 * Adiciona suporte a diferentes formatos de moeda
 */
function ansegtv_currency_format($amount, $currency = 'BRL') {
    $currencies = array(
        'BRL' => array(
            'symbol' => 'R$',
            'position' => 'before',
            'thousands_sep' => '.',
            'decimal_sep' => ','
        ),
        'USD' => array(
            'symbol' => '$',
            'position' => 'before',
            'thousands_sep' => ',',
            'decimal_sep' => '.'
        ),
        'EUR' => array(
            'symbol' => '€',
            'position' => 'after',
            'thousands_sep' => '.',
            'decimal_sep' => ','
        )
    );

    if (!isset($currencies[$currency])) {
        $currency = 'BRL';
    }

    $format = $currencies[$currency];
    $amount = number_format($amount, 2, $format['decimal_sep'], $format['thousands_sep']);

    return $format['position'] === 'before' 
        ? $format['symbol'] . ' ' . $amount 
        : $amount . ' ' . $format['symbol'];
}

/**
 * Adiciona suporte a diferentes formatos de telefone
 */
function ansegtv_phone_format($phone) {
    // Remove caracteres não numéricos
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Formata de acordo com o tamanho
    if (strlen($phone) === 11) {
        return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7);
    } elseif (strlen($phone) === 10) {
        return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6);
    }
    
    return $phone;
}

/**
 * Adiciona suporte a diferentes formatos de CEP
 */
function ansegtv_zip_format($zip) {
    // Remove caracteres não numéricos
    $zip = preg_replace('/[^0-9]/', '', $zip);
    
    // Formata CEP brasileiro
    if (strlen($zip) === 8) {
        return substr($zip, 0, 5) . '-' . substr($zip, 5);
    }
    
    return $zip;
}

/**
 * Adiciona suporte a diferentes formatos de CPF/CNPJ
 */
function ansegtv_document_format($document) {
    // Remove caracteres não numéricos
    $document = preg_replace('/[^0-9]/', '', $document);
    
    // Formata de acordo com o tamanho
    if (strlen($document) === 11) {
        return substr($document, 0, 3) . '.' . substr($document, 3, 3) . '.' . substr($document, 6, 3) . '-' . substr($document, 9);
    } elseif (strlen($document) === 14) {
        return substr($document, 0, 2) . '.' . substr($document, 2, 3) . '.' . substr($document, 5, 3) . '/' . substr($document, 8, 4) . '-' . substr($document, 12);
    }
    
    return $document;
}

/**
 * Adiciona suporte a diferentes formatos de horário
 */
function ansegtv_time_format($time, $format = 'default') {
    $formats = array(
        'default' => get_option('time_format'),
        '12h' => 'g:i A',
        '24h' => 'H:i'
    );
    
    $format = isset($formats[$format]) ? $formats[$format] : $formats['default'];
    
    return date_i18n($format, strtotime($time));
}

/**
 * Adiciona suporte a diferentes formatos de duração
 */
function ansegtv_duration_format($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $seconds = $seconds % 60;
    
    if ($hours > 0) {
        return sprintf(
            _n('%d hora', '%d horas', $hours, 'ansegtv') . ' ' . 
            _n('%d minuto', '%d minutos', $minutes, 'ansegtv'),
            $hours,
            $minutes
        );
    }
    
    return sprintf(
        _n('%d minuto', '%d minutos', $minutes, 'ansegtv') . ' ' . 
        _n('%d segundo', '%d segundos', $seconds, 'ansegtv'),
        $minutes,
        $seconds
    );
}

/**
 * Adiciona suporte a diferentes formatos de tamanho de arquivo
 */
function ansegtv_file_size_format($bytes) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, 2) . ' ' . $units[$pow];
}

/**
 * Adiciona suporte a diferentes formatos de distância
 */
function ansegtv_distance_format($meters) {
    if ($meters < 1000) {
        return sprintf(
            _n('%d metro', '%d metros', $meters, 'ansegtv'),
            $meters
        );
    }
    
    $kilometers = $meters / 1000;
    return sprintf(
        _n('%.1f quilômetro', '%.1f quilômetros', $kilometers, 'ansegtv'),
        $kilometers
    );
}

/**
 * Adiciona suporte a diferentes formatos de peso
 */
function ansegtv_weight_format($grams) {
    if ($grams < 1000) {
        return sprintf(
            _n('%d grama', '%d gramas', $grams, 'ansegtv'),
            $grams
        );
    }
    
    $kilograms = $grams / 1000;
    return sprintf(
        _n('%.1f quilograma', '%.1f quilogramas', $kilograms, 'ansegtv'),
        $kilograms
    );
} 