<?php
/**
 * Script para limpar cache de notícias
 * Pode ser executado via cron job ou manualmente
 */

require_once __DIR__ . '/cache.php';

// Limpar cache expirado
$cache = getNewsCache();
$cache->cleanExpired();

echo "Cache limpo com sucesso!\n";
echo "Data/Hora: " . date('Y-m-d H:i:s') . "\n";
?> 