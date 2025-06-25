<?php
/**
 * Sistema de Conversão de Imagens - ANSEGTV
 * Converte automaticamente imagens para WebP para melhor performance
 */

class ImageConverter {
    private $source_dir;
    private $webp_dir;
    private $quality;
    private $supported_formats;
    
    public function __construct($quality = 85) {
        $this->quality = $quality;
        $this->supported_formats = ['jpg', 'jpeg', 'png', 'gif'];
        
        // Diretórios base do WordPress
        $this->source_dir = __DIR__ . '/../website/wp-content/uploads/';
        $this->webp_dir = __DIR__ . '/../website/wp-content/uploads/webp/';
        
        // Criar diretório WebP se não existir
        if (!is_dir($this->webp_dir)) {
            mkdir($this->webp_dir, 0755, true);
        }
    }
    
    /**
     * Verifica se o servidor suporta WebP
     */
    public function isWebPSupported() {
        return function_exists('imagewebp') && function_exists('imagecreatefromjpeg');
    }
    
    /**
     * Converte uma imagem para WebP
     */
    public function convertToWebP($source_path, $destination_path = null) {
        if (!$this->isWebPSupported()) {
            return false;
        }
        
        $extension = strtolower(pathinfo($source_path, PATHINFO_EXTENSION));
        
        if (!in_array($extension, $this->supported_formats)) {
            return false;
        }
        
        // Se não especificado, criar caminho de destino
        if ($destination_path === null) {
            $filename = pathinfo($source_path, PATHINFO_FILENAME);
            $relative_path = str_replace($this->source_dir, '', $source_path);
            $webp_path = $this->webp_dir . pathinfo($relative_path, PATHINFO_DIRNAME) . '/' . $filename . '.webp';
            
            // Criar diretório se não existir
            $webp_dir = dirname($webp_path);
            if (!is_dir($webp_dir)) {
                mkdir($webp_dir, 0755, true);
            }
            
            $destination_path = $webp_path;
        }
        
        // Carregar imagem baseada no formato
        $image = null;
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($source_path);
                break;
            case 'png':
                $image = imagecreatefrompng($source_path);
                // Preservar transparência para PNG
                imagepalettetotruecolor($image);
                imagealphablending($image, true);
                imagesavealpha($image, true);
                break;
            case 'gif':
                $image = imagecreatefromgif($source_path);
                break;
        }
        
        if ($image === false) {
            return false;
        }
        
        // Converter para WebP
        $result = imagewebp($image, $destination_path, $this->quality);
        
        // Liberar memória
        imagedestroy($image);
        
        return $result;
    }
    
    /**
     * Converte todas as imagens de um diretório
     */
    public function convertDirectory($directory = null) {
        if ($directory === null) {
            $directory = $this->source_dir;
        }
        
        $converted = 0;
        $errors = 0;
        $skipped = 0;
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $extension = strtolower($file->getExtension());
                
                if (in_array($extension, $this->supported_formats)) {
                    $source_path = $file->getPathname();
                    $filename = $file->getBasename('.' . $extension);
                    $relative_path = str_replace($this->source_dir, '', $source_path);
                    $webp_path = $this->webp_dir . pathinfo($relative_path, PATHINFO_DIRNAME) . '/' . $filename . '.webp';
                    
                    // Verificar se WebP já existe e é mais recente
                    if (file_exists($webp_path) && filemtime($webp_path) >= filemtime($source_path)) {
                        $skipped++;
                        continue;
                    }
                    
                    // Criar diretório se não existir
                    $webp_dir = dirname($webp_path);
                    if (!is_dir($webp_dir)) {
                        mkdir($webp_dir, 0755, true);
                    }
                    
                    if ($this->convertToWebP($source_path, $webp_path)) {
                        $converted++;
                    } else {
                        $errors++;
                    }
                }
            }
        }
        
        return [
            'converted' => $converted,
            'errors' => $errors,
            'skipped' => $skipped
        ];
    }
    
    /**
     * Converte imagens específicas da API do WordPress
     */
    public function convertFromAPI($api_url = null) {
        if ($api_url === null) {
            $api_url = 'https://ansegtv.com.br/website/wp-json/wp/v2/posts?_embed&per_page=100';
        }
        
        $context = stream_context_create([
            'http' => [
                'header' => 'Accept: application/json',
                'timeout' => 30
            ]
        ]);
        
        $response = @file_get_contents($api_url, false, $context);
        
        if ($response === false) {
            return ['error' => 'Não foi possível acessar a API'];
        }
        
        $posts = json_decode($response, true);
        $images = [];
        $converted = 0;
        $errors = 0;
        
        foreach ($posts as $post) {
            // Imagem destacada
            if (isset($post['_embedded']['wp:featuredmedia'][0]['source_url'])) {
                $images[] = $post['_embedded']['wp:featuredmedia'][0]['source_url'];
            }
            
            // Imagens no conteúdo
            if (isset($post['content']['rendered'])) {
                preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $post['content']['rendered'], $matches);
                if (isset($matches[1])) {
                    $images = array_merge($images, $matches[1]);
                }
            }
        }
        
        $images = array_unique($images);
        
        foreach ($images as $image_url) {
            // Converter URL para caminho local
            $local_path = $this->urlToLocalPath($image_url);
            
            if ($local_path && file_exists($local_path)) {
                if ($this->convertToWebP($local_path)) {
                    $converted++;
                } else {
                    $errors++;
                }
            }
        }
        
        return [
            'converted' => $converted,
            'errors' => $errors,
            'total_images' => count($images)
        ];
    }
    
    /**
     * Converte URL do WordPress para caminho local
     */
    private function urlToLocalPath($url) {
        $base_url = 'https://ansegtv.com.br/website/wp-content/uploads/';
        
        if (strpos($url, $base_url) === 0) {
            $relative_path = str_replace($base_url, '', $url);
            return $this->source_dir . $relative_path;
        }
        
        return false;
    }
    
    /**
     * Verifica se existe versão WebP de uma imagem
     */
    public function hasWebPVersion($image_url) {
        $local_path = $this->urlToLocalPath($image_url);
        
        if (!$local_path) {
            return false;
        }
        
        $filename = pathinfo($local_path, PATHINFO_FILENAME);
        $relative_path = str_replace($this->source_dir, '', $local_path);
        $webp_path = $this->webp_dir . pathinfo($relative_path, PATHINFO_DIRNAME) . '/' . $filename . '.webp';
        
        return file_exists($webp_path);
    }
    
    /**
     * Obtém URL WebP de uma imagem
     */
    public function getWebPUrl($image_url) {
        if (!$this->hasWebPVersion($image_url)) {
            return null;
        }
        
        $local_path = $this->urlToLocalPath($image_url);
        $filename = pathinfo($local_path, PATHINFO_FILENAME);
        $relative_path = str_replace($this->source_dir, '', $local_path);
        $webp_path = $this->webp_dir . pathinfo($relative_path, PATHINFO_DIRNAME) . '/' . $filename . '.webp';
        
        // Converter caminho local para URL
        $webp_url = str_replace($this->source_dir, 'https://ansegtv.com.br/website/wp-content/uploads/', $webp_path);
        $webp_url = str_replace('/webp/', '/webp/', $webp_url);
        
        return $webp_url;
    }
    
    /**
     * Define qualidade da conversão
     */
    public function setQuality($quality) {
        $this->quality = max(1, min(100, $quality));
    }
    
    /**
     * Limpa arquivos WebP antigos
     */
    public function cleanOldWebP() {
        $deleted = 0;
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->webp_dir, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'webp') {
                $webp_path = $file->getPathname();
                $original_path = $this->webpToOriginalPath($webp_path);
                
                // Se o arquivo original não existe, deletar WebP
                if (!file_exists($original_path)) {
                    unlink($webp_path);
                    $deleted++;
                }
            }
        }
        
        return $deleted;
    }
    
    /**
     * Converte caminho WebP para caminho original
     */
    private function webpToOriginalPath($webp_path) {
        $relative_path = str_replace($this->webp_dir, '', $webp_path);
        $filename = pathinfo($relative_path, PATHINFO_FILENAME);
        $dir = dirname($relative_path);
        
        // Tentar diferentes extensões
        foreach ($this->supported_formats as $ext) {
            $original_path = $this->source_dir . $dir . '/' . $filename . '.' . $ext;
            if (file_exists($original_path)) {
                return $original_path;
            }
        }
        
        return false;
    }
}

// Função helper para obter instância do conversor
function getImageConverter($quality = 85) {
    static $converter = null;
    if ($converter === null) {
        $converter = new ImageConverter($quality);
    }
    return $converter;
}

// Função helper para obter URL WebP
function getWebPUrl($image_url) {
    $converter = getImageConverter();
    return $converter->getWebPUrl($image_url);
}

// Função helper para verificar se existe WebP
function hasWebPVersion($image_url) {
    $converter = getImageConverter();
    return $converter->hasWebPVersion($image_url);
}
?> 