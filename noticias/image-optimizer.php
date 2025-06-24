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
        $path_parts = pathinfo($image_url);
        
        // Verificar se WebP existe
        $webp_url = $path_parts['dirname'] . '/' . $path_parts['filename'] . '.webp';
        
        // Em produção, você pode verificar se o arquivo existe
        // Por enquanto, retornamos a URL WebP
        return $webp_url;
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
    return $optimizer->generateImgTag($image_url, $alt, $class);
}

// Função helper para gerar imagem responsiva
function generateResponsiveImg($image_url, $alt = '', $class = 'img-fluid') {
    $optimizer = getImageOptimizer();
    return $optimizer->generateResponsiveImage($image_url, $alt, $class);
}
?> 