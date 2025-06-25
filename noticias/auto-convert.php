<?php
/**
 * Script de Conversão Automática - ANSEGTV
 * Executa conversão de imagens em background
 */

require_once __DIR__ . '/image-converter.php';

// Configurar timeout para execução longa
set_time_limit(300); // 5 minutos
ini_set('memory_limit', '512M');

// Log de execução
$log_file = __DIR__ . '/logs/conversion.log';
$log_dir = dirname($log_file);

if (!is_dir($log_dir)) {
    mkdir($log_dir, 0755, true);
}

function writeLog($message) {
    global $log_file;
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] $message\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

writeLog("Iniciando conversão automática de imagens");

try {
    $converter = getImageConverter(85); // Qualidade 85%
    
    if (!$converter->isWebPSupported()) {
        writeLog("ERRO: Servidor não suporta conversão WebP");
        exit(1);
    }
    
    // Converter imagens da API
    writeLog("Convertendo imagens da API...");
    $api_result = $converter->convertFromAPI();
    
    if (isset($api_result['error'])) {
        writeLog("ERRO na conversão da API: " . $api_result['error']);
    } else {
        writeLog("API: " . $api_result['converted'] . " convertidas, " . $api_result['errors'] . " erros");
    }
    
    // Limpar WebP antigos
    writeLog("Limpando WebP antigos...");
    $deleted = $converter->cleanOldWebP();
    writeLog("$deleted arquivos WebP antigos removidos");
    
    writeLog("Conversão automática concluída com sucesso");
    
} catch (Exception $e) {
    writeLog("ERRO: " . $e->getMessage());
    exit(1);
}
?> 