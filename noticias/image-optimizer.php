<?php
/**
 * Sistema de Otimização de Imagens - Notícias ANSEGTV
 * Melhora performance com lazy loading, compressão e formatos otimizados
 */

class ImageOptimizer {
    private $default_width;
    private $default_height;
    private $quality;
    private $lazy_loading;
    
    public function __construct($width = 800, $height = 600, $quality = 85) {
        $this->default_width = $width;
        $this->default_height = $height;
        $this->quality = $quality;
        $this->lazy_loading = true;
    }
    
    /**
     * Otimiza URL de imagem com parâmetros
     */
    public function optimizeUrl($image_url, $width = null, $height = null, $quality = null) {
        if (empty($image_url)) {
            return '';
        }
        
        $width = $width ?: $this->default_width;
        $height = $height ?: $this->default_height;
        $quality = $quality ?: $this->quality;
        
        // Se é uma imagem do WordPress, adicionar parâmetros de redimensionamento
        if (strpos($image_url, 'wp-content/uploads/') !== false) {
            $parsed_url = parse_url($image_url);
            $path_parts = pathinfo($parsed_url['path']);
            
            // Verificar se já tem parâmetros de redimensionamento
            if (strpos($path_parts['filename'], '-') !== false) {
                return $image_url; // Já otimizada
            }
            
            // Adicionar dimensões ao nome do arquivo
            $new_filename = $path_parts['filename'] . '-' . $width . 'x' . $height;
            $new_url = str_replace($path_parts['filename'], $new_filename, $image_url);
            
            return $new_url;
        }
        
        return $image_url;
    }
    
    /**
     * Gera atributos para lazy loading
     */
    public function getLazyAttributes($image_url, $alt = '', $class = '') {
        $attributes = [];
        
        if ($this->lazy_loading) {
            $attributes['loading'] = 'lazy';
            $attributes['data-src'] = $image_url;
            $attributes['src'] = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1 1"%3E%3C/svg%3E'; // Placeholder
        } else {
            $attributes['src'] = $image_url;
        }
        
        if (!empty($alt)) {
            $attributes['alt'] = $alt;
        }
        
        if (!empty($class)) {
            $attributes['class'] = $class;
        }
        
        return $attributes;
    }
    
    /**
     * Gera tag img otimizada
     */
    public function generateImgTag($image_url, $alt = '', $class = 'img-fluid', $width = null, $height = null) {
        $optimized_url = $this->optimizeUrl($image_url, $width, $height);
        $attributes = $this->getLazyAttributes($optimized_url, $alt, $class);
        
        $html = '<img';
        foreach ($attributes as $attr => $value) {
            $html .= ' ' . $attr . '="' . htmlspecialchars($value) . '"';
        }
        $html .= '>';
        
        return $html;
    }
    
    /**
     * Gera picture tag com múltiplos formatos
     */
    public function generatePictureTag($image_url, $alt = '', $class = 'img-fluid', $sizes = '100vw') {
        if (empty($image_url)) {
            return '';
        }
        
        $attributes = $this->getLazyAttributes($image_url, $alt, $class);
        $attributes['sizes'] = $sizes;
        
        $html = '<picture>';
        
        // WebP (se suportado)
        $webp_url = $this->getWebPUrl($image_url);
        if ($webp_url) {
            $html .= '<source srcset="' . htmlspecialchars($webp_url) . '" type="image/webp">';
        }
        
        // Imagem original como fallback
        $html .= '<img';
        foreach ($attributes as $attr => $value) {
            $html .= ' ' . $attr . '="' . htmlspecialchars($value) . '"';
        }
        $html .= '>';
        
        $html .= '</picture>';
        
        return $html;
    }
    
    /**
     * Gera URL WebP se disponível
     */
    private function getWebPUrl($image_url) {
        // Incluir sistema de conversão se não estiver incluído
        if (!function_exists('getWebPUrl')) {
            require_once __DIR__ . '/image-converter.php';
        }
        
        return getWebPUrl($image_url);
    }
    
    /**
     * Gera srcset para imagens responsivas
     */
    public function generateSrcset($image_url, $sizes = []) {
        if (empty($image_url)) {
            return '';
        }
        
        $srcset = [];
        $default_sizes = [
            320 => 320,
            480 => 480,
            768 => 768,
            1024 => 1024,
            1200 => 1200
        ];
        
        $sizes = !empty($sizes) ? $sizes : $default_sizes;
        
        foreach ($sizes as $width) {
            $optimized_url = $this->optimizeUrl($image_url, $width);
            $srcset[] = $optimized_url . ' ' . $width . 'w';
        }
        
        return implode(', ', $srcset);
    }
    
    /**
     * Gera imagem responsiva completa
     */
    public function generateResponsiveImage($image_url, $alt = '', $class = 'img-fluid', $sizes = '(max-width: 768px) 100vw, 50vw') {
        if (empty($image_url)) {
            return '';
        }
        
        $srcset = $this->generateSrcset($image_url);
        $attributes = $this->getLazyAttributes($image_url, $alt, $class);
        $attributes['srcset'] = $srcset;
        $attributes['sizes'] = $sizes;
        
        $html = '<img';
        foreach ($attributes as $attr => $value) {
            $html .= ' ' . $attr . '="' . htmlspecialchars($value) . '"';
        }
        $html .= '>';
        
        return $html;
    }
    
    /**
     * Habilita/desabilita lazy loading
     */
    public function setLazyLoading($enabled) {
        $this->lazy_loading = $enabled;
    }
    
    /**
     * Define qualidade padrão
     */
    public function setQuality($quality) {
        $this->quality = max(1, min(100, $quality));
    }
    
    /**
     * Define dimensões padrão
     */
    public function setDefaultDimensions($width, $height) {
        $this->default_width = $width;
        $this->default_height = $height;
    }
    
    /**
     * Gera tag de imagem otimizada com WebP
     */
    public function generateOptimizedImg($src, $alt = '', $class = '') {
        // Verificar se existe versão WebP
        $webp_url = $this->getWebPUrl($src);
        
        if ($webp_url) {
            // Gerar tag com picture para suporte a WebP
            $picture_tag = '<picture>';
            $picture_tag .= '<source srcset="' . htmlspecialchars($webp_url, ENT_QUOTES, 'UTF-8') . '" type="image/webp">';
            $picture_tag .= '<img src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . '"';
            
            if (!empty($alt)) {
                $picture_tag .= ' alt="' . htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') . '"';
            }
            
            if (!empty($class)) {
                $picture_tag .= ' class="' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '"';
            }
            
            $picture_tag .= ' loading="lazy" decoding="async">';
            $picture_tag .= '</picture>';
            
            return $picture_tag;
        } else {
            // Fallback para imagem original
            $img_tag = '<img src="' . htmlspecialchars($src, ENT_QUOTES, 'UTF-8') . '"';
            
            if (!empty($alt)) {
                $img_tag .= ' alt="' . htmlspecialchars($alt, ENT_QUOTES, 'UTF-8') . '"';
            }
            
            if (!empty($class)) {
                $img_tag .= ' class="' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '"';
            }
            
            $img_tag .= ' loading="lazy" decoding="async">';
            
            return $img_tag;
        }
    }
}

// Função helper para obter instância do otimizador
function getImageOptimizer($width = 800, $height = 600, $quality = 85) {
    static $optimizer = null;
    if ($optimizer === null) {
        $optimizer = new ImageOptimizer($width, $height, $quality);
    }
    return $optimizer;
}

// Função helper para otimizar URL de imagem
function optimizeImageUrl($image_url, $width = null, $height = null) {
    $optimizer = getImageOptimizer();
    return $optimizer->optimizeUrl($image_url, $width, $height);
}

// Função helper para gerar tag img otimizada
function generateOptimizedImg($image_url, $alt = '', $class = 'img-fluid') {
    $optimizer = getImageOptimizer();
    return $optimizer->generateOptimizedImg($image_url, $alt, $class);
}

// Função helper para gerar imagem responsiva
function generateResponsiveImg($image_url, $alt = '', $class = 'img-fluid') {
    $optimizer = getImageOptimizer();
    return $optimizer->generateResponsiveImage($image_url, $alt, $class);
}
?> 