<?php
/**
 * Script para Otimizar Imagens - ANSEGTV
 * Gera versões otimizadas das imagens existentes
 */

require_once __DIR__ . '/image-optimizer.php';

class ImageProcessor {
    private $api_url;
    private $optimizer;
    
    public function __construct() {
        $this->api_url = 'https://ansegtv.com.br/website/wp-json/wp/v2/posts?_embed&per_page=100';
        $this->optimizer = getImageOptimizer();
    }
    
    /**
     * Obtém todas as imagens dos posts
     */
    public function getAllImages() {
        $context = stream_context_create([
            'http' => [
                'header' => 'Accept: application/json',
                'timeout' => 30
            ]
        ]);
        
        $response = @file_get_contents($this->api_url, false, $context);
        
        if ($response === false) {
            return [];
        }
        
        $posts = json_decode($response, true);
        $images = [];
        
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
        
        return array_unique($images);
    }
    
    /**
     * Gera URLs otimizadas para uma imagem
     */
    public function generateOptimizedUrls($image_url) {
        $sizes = [320, 480, 768, 1024, 1200];
        $optimized_urls = [];
        
        foreach ($sizes as $size) {
            $optimized_urls[$size] = $this->optimizer->optimizeUrl($image_url, $size, null);
        }
        
        return $optimized_urls;
    }
    
    /**
     * Processa todas as imagens
     */
    public function processAllImages() {
        $images = $this->getAllImages();
        $results = [];
        
        echo "Processando " . count($images) . " imagens...\n";
        
        foreach ($images as $image_url) {
            $optimized_urls = $this->generateOptimizedUrls($image_url);
            $results[$image_url] = $optimized_urls;
            
            echo "✓ " . basename($image_url) . " processada\n";
        }
        
        return $results;
    }
    
    /**
     * Gera relatório de otimização
     */
    public function generateReport($results) {
        $report = [];
        $total_images = count($results);
        $total_optimized = 0;
        
        foreach ($results as $original => $optimized) {
            $total_optimized += count($optimized);
        }
        
        $report['total_images'] = $total_images;
        $report['total_optimized_versions'] = $total_optimized;
        $report['average_versions_per_image'] = $total_images > 0 ? round($total_optimized / $total_images, 2) : 0;
        
        return $report;
    }
}

// Executar se chamado diretamente
if (php_sapi_name() === 'cli' || isset($_GET['run'])) {
    $processor = new ImageProcessor();
    $results = $processor->processAllImages();
    $report = $processor->generateReport($results);
    
    echo "\n=== RELATÓRIO DE OTIMIZAÇÃO ===\n";
    echo "Total de imagens: " . $report['total_images'] . "\n";
    echo "Versões otimizadas geradas: " . $report['total_optimized_versions'] . "\n";
    echo "Média de versões por imagem: " . $report['average_versions_per_image'] . "\n";
    echo "===============================\n";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Otimizar Imagens - ANSEGTV</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Otimizar Imagens</h1>
        <p class="text-muted">ANSEGTV - Sistema de Notícias</p>
        
        <div class="card">
            <div class="card-header">
                <h5>Processamento de Imagens</h5>
            </div>
            <div class="card-body">
                <p>Este script processa todas as imagens dos posts e gera URLs otimizadas para diferentes tamanhos.</p>
                
                <a href="?run=1" class="btn btn-primary">Executar Otimização</a>
                
                <?php if (isset($_GET['run'])): ?>
                    <div class="mt-3">
                        <h6>Resultados:</h6>
                        <pre><?php
                            $processor = new ImageProcessor();
                            $results = $processor->processAllImages();
                            $report = $processor->generateReport($results);
                            
                            echo "Total de imagens: " . $report['total_images'] . "\n";
                            echo "Versões otimizadas: " . $report['total_optimized_versions'] . "\n";
                            echo "Média por imagem: " . $report['average_versions_per_image'] . "\n";
                        ?></pre>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-3">
            <a href="/noticias/" class="btn btn-secondary">Voltar para Notícias</a>
        </div>
    </div>
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html> 