<?php
/**
 * Script para Conversão de Imagens - ANSEGTV
 * Converte imagens para WebP automaticamente
 */

require_once __DIR__ . '/image-converter.php';

// Verificar se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $converter = getImageConverter();
    
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'convert_api':
                $result = $converter->convertFromAPI();
                $message = "Conversão concluída!";
                break;
                
            case 'convert_directory':
                $result = $converter->convertDirectory();
                $message = "Conversão de diretório concluída!";
                break;
                
            case 'clean_old':
                $deleted = $converter->cleanOldWebP();
                $message = "Limpeza concluída! $deleted arquivos WebP antigos removidos.";
                break;
        }
    }
}

$converter = getImageConverter();
$webp_supported = $converter->isWebPSupported();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversão de Imagens - ANSEGTV</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Conversão de Imagens para WebP</h1>
        <p class="text-muted">ANSEGTV - Sistema de Notícias</p>
        
        <?php if (!$webp_supported): ?>
            <div class="alert alert-warning">
                <strong>Atenção:</strong> O servidor não suporta conversão WebP. 
                Verifique se as extensões GD e WebP estão habilitadas no PHP.
            </div>
        <?php endif; ?>
        
        <?php if (isset($message)): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($result)): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Resultados da Conversão</h5>
                </div>
                <div class="card-body">
                    <?php if (isset($result['error'])): ?>
                        <div class="alert alert-danger"><?php echo $result['error']; ?></div>
                    <?php else: ?>
                        <ul class="list-unstyled">
                            <?php if (isset($result['converted'])): ?>
                                <li><strong>Convertidas:</strong> <?php echo $result['converted']; ?></li>
                            <?php endif; ?>
                            <?php if (isset($result['errors'])): ?>
                                <li><strong>Erros:</strong> <?php echo $result['errors']; ?></li>
                            <?php endif; ?>
                            <?php if (isset($result['skipped'])): ?>
                                <li><strong>Puladas:</strong> <?php echo $result['skipped']; ?></li>
                            <?php endif; ?>
                            <?php if (isset($result['total_images'])): ?>
                                <li><strong>Total de imagens:</strong> <?php echo $result['total_images']; ?></li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Converter da API</h5>
                    </div>
                    <div class="card-body">
                        <p>Converte imagens dos posts do WordPress para WebP.</p>
                        <form method="POST">
                            <input type="hidden" name="action" value="convert_api">
                            <button type="submit" class="btn btn-primary" <?php echo !$webp_supported ? 'disabled' : ''; ?>>
                                Converter Imagens da API
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Converter Diretório</h5>
                    </div>
                    <div class="card-body">
                        <p>Converte todas as imagens do diretório uploads para WebP.</p>
                        <form method="POST">
                            <input type="hidden" name="action" value="convert_directory">
                            <button type="submit" class="btn btn-success" <?php echo !$webp_supported ? 'disabled' : ''; ?>>
                                Converter Todo Diretório
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Limpeza</h5>
                    </div>
                    <div class="card-body">
                        <p>Remove arquivos WebP de imagens que não existem mais.</p>
                        <form method="POST">
                            <input type="hidden" name="action" value="clean_old">
                            <button type="submit" class="btn btn-warning">
                                Limpar WebP Antigos
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-4">
            <h5>Informações do Sistema</h5>
            <ul>
                <li><strong>Suporte WebP:</strong> <?php echo $webp_supported ? '✅ Sim' : '❌ Não'; ?></li>
                <li><strong>Qualidade:</strong> 85% (configurável)</li>
                <li><strong>Formatos suportados:</strong> JPG, JPEG, PNG, GIF</li>
                <li><strong>Diretório WebP:</strong> /website/wp-content/uploads/webp/</li>
            </ul>
        </div>
        
        <div class="mt-4">
            <h5>Como funciona:</h5>
            <ol>
                <li><strong>Conversão automática:</strong> Imagens são convertidas para WebP mantendo qualidade</li>
                <li><strong>Fallback inteligente:</strong> Se WebP não for suportado, usa formato original</li>
                <li><strong>Cache otimizado:</strong> WebP é servido apenas para navegadores que suportam</li>
                <li><strong>Redução de tamanho:</strong> WebP é 25-35% menor que JPEG/PNG</li>
            </ol>
        </div>
        
        <div class="mt-3">
            <a href="/noticias/" class="btn btn-secondary">Voltar para Notícias</a>
        </div>
    </div>
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html> 