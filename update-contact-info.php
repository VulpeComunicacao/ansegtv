<?php
/**
 * Script para Atualizar Informações de Contato - ANSEGTV
 * Substitui todos os footers hardcoded pelo sistema centralizado
 */

echo "=== Atualizando Informações de Contato - ANSEGTV ===\n\n";

// Padrão para encontrar os footers hardcoded
$old_footer_pattern = '/<section id="footer" class="container">\s*<div class="row">\s*<div class="col-12 mb-3 contato-institucional text-center">\s*<p><strong>Endereço:<\/strong>.*?<\/p>\s*<p><strong>E-mail:<\/strong>.*?<\/p>\s*<\/div>\s*<\/div>\s*<\/section>/s';

// Novo footer usando include
$new_footer = '    <!-- Footer Section -->
    <?php
    // Incluir sistema centralizado de informações de contato
    require_once \'../../includes/contact-info.php\';
    
    // Renderizar footer de contato
    render_contact_footer();
    ?>';

// Função para atualizar um arquivo
function updateFile($file_path, $old_pattern, $new_content) {
    if (!file_exists($file_path)) {
        return false;
    }
    
    $content = file_get_contents($file_path);
    $original_content = $content;
    
    // Substituir o footer
    $content = preg_replace($old_pattern, $new_content, $content);
    
    // Se houve mudança, salvar o arquivo
    if ($content !== $original_content) {
        file_put_contents($file_path, $content);
        return true;
    }
    
    return false;
}

// Função para ajustar o caminho do include baseado na profundidade do arquivo
function getIncludePath($file_path) {
    $depth = substr_count($file_path, '/') - 1; // -1 para a raiz
    
    if ($depth <= 1) {
        return 'includes/contact-info.php';
    } else {
        $relative_path = str_repeat('../', $depth - 1) . 'includes/contact-info.php';
        return $relative_path;
    }
}

// Lista de arquivos para atualizar
$files_to_update = [
    // Páginas principais
    'estrutura/index.php',
    'ansegtv/index.php', 
    'mapa/index.php',
    'indice-inflacao/index.php',
    'parcerias/index.php',
    'politica-de-privacidade/index.php',
    'contato/index.php'
];

// Atualizar páginas principais
echo "Atualizando páginas principais...\n";
foreach ($files_to_update as $file) {
    if (file_exists($file)) {
        $include_path = getIncludePath($file);
        $new_footer_main = '    <!-- Footer Section -->
    <?php
    // Incluir sistema centralizado de informações de contato
    require_once \'' . $include_path . '\';
    
    // Renderizar footer de contato
    render_contact_footer();
    ?>';
        
        if (updateFile($file, $old_footer_pattern, $new_footer_main)) {
            echo "✅ Atualizado: $file\n";
        } else {
            echo "⚠️  Sem alterações: $file\n";
        }
    } else {
        echo "❌ Arquivo não encontrado: $file\n";
    }
}

// Atualizar páginas de notícias
echo "\nAtualizando páginas de notícias...\n";
$news_dirs = glob('noticias/*/', GLOB_ONLYDIR);
$updated_count = 0;

foreach ($news_dirs as $news_dir) {
    $index_file = $news_dir . 'index.php';
    
    if (file_exists($index_file)) {
        // Para notícias, sempre usar ../../includes/
        $new_footer_news = '    <!-- Footer Section -->
    <?php
    // Incluir sistema centralizado de informações de contato
    require_once \'../../includes/contact-info.php\';
    
    // Renderizar footer de contato
    render_contact_footer();
    ?>';
        
        if (updateFile($index_file, $old_footer_pattern, $new_footer_news)) {
            echo "✅ Atualizado: $index_file\n";
            $updated_count++;
        } else {
            echo "⚠️  Sem alterações: $index_file\n";
        }
    }
}

echo "\n=== Resumo da Atualização ===\n";
echo "Páginas principais processadas: " . count($files_to_update) . "\n";
echo "Páginas de notícias atualizadas: $updated_count\n";
echo "Total de arquivos processados: " . (count($files_to_update) + count($news_dirs)) . "\n\n";

echo "✅ Atualização concluída!\n";
echo "📝 Agora todas as páginas usam o sistema centralizado de informações de contato.\n";
echo "🔧 Para alterar o endereço, edite apenas o arquivo: includes/contact-info.php\n";
?>
