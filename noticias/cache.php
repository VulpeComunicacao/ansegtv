<?php
/**
 * Sistema de Cache para Notícias ANSEGTV
 * Otimiza performance reduzindo chamadas à API WordPress
 */

class NewsCache {
    private $cache_dir;
    private $cache_duration;
    
    public function __construct($duration = 3600) {
        $this->cache_dir = __DIR__ . '/cache/';
        $this->cache_duration = $duration;
        
        // Cria o diretório de cache se não existir
        if (!is_dir($this->cache_dir)) {
            mkdir($this->cache_dir, 0755, true);
        }
    }
    
    /**
     * Gera chave única para o cache
     */
    private function getCacheKey($endpoint, $params = []) {
        $key = $endpoint;
        if (!empty($params)) {
            $key .= '_' . md5(serialize($params));
        }
        return $key;
    }
    
    /**
     * Obtém arquivo de cache
     */
    private function getCacheFile($key) {
        return $this->cache_dir . $key . '.json';
    }
    
    /**
     * Verifica se o cache é válido
     */
    public function isValid($key) {
        $cache_file = $this->getCacheFile($key);
        
        if (!file_exists($cache_file)) {
            return false;
        }
        
        $file_time = filemtime($cache_file);
        $current_time = time();
        
        return ($current_time - $file_time) < $this->cache_duration;
    }
    
    /**
     * Obtém dados do cache
     */
    public function get($key) {
        if (!$this->isValid($key)) {
            return null;
        }
        
        $cache_file = $this->getCacheFile($key);
        $data = file_get_contents($cache_file);
        
        if ($data === false) {
            return null;
        }
        
        return json_decode($data, true);
    }
    
    /**
     * Salva dados no cache
     */
    public function set($key, $data) {
        $cache_file = $this->getCacheFile($key);
        $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
        
        return file_put_contents($cache_file, $json_data) !== false;
    }
    
    /**
     * Limpa cache expirado
     */
    public function cleanExpired() {
        $files = glob($this->cache_dir . '*.json');
        $current_time = time();
        
        foreach ($files as $file) {
            $file_time = filemtime($file);
            if (($current_time - $file_time) > $this->cache_duration) {
                unlink($file);
            }
        }
    }
    
    /**
     * Limpa todo o cache
     */
    public function clear() {
        $files = glob($this->cache_dir . '*.json');
        foreach ($files as $file) {
            unlink($file);
        }
    }
    
    /**
     * Obtém dados da API com cache
     */
    public function getFromAPI($url, $endpoint = 'posts', $params = []) {
        $key = $this->getCacheKey($endpoint, $params);
        
        // Tenta obter do cache primeiro
        $cached_data = $this->get($key);
        if ($cached_data !== null) {
            return $cached_data;
        }
        
        // Se não há cache válido, busca da API
        $context = stream_context_create([
            'http' => [
                'header' => 'Accept: application/json',
                'timeout' => 30
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response === false) {
            error_log("API WordPress indisponível: " . $url);
            return null;
        }
        
        $data = json_decode($response, true);
        
        // Salva no cache se a requisição foi bem-sucedida
        if ($data !== null) {
            $this->set($key, $data);
        }
        
        return $data;
    }
}

// Função helper para obter instância do cache
function getNewsCache($duration = 3600) {
    static $cache = null;
    if ($cache === null) {
        $cache = new NewsCache($duration);
    }
    return $cache;
}
?> 