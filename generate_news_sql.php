<?php

// Configurações do WordPress
define('WP_PREFIX', 'wp_'); // Prefixo das tabelas do WordPress
define('WP_UPLOADS_BASE_URL', 'https://ansegtv.com.br/website/wp-content/uploads/'); // Base URL para uploads do WordPress

// O arquivo de saída SQL não é mais necessário para esta abordagem
// $output_file = 'import_news_script.sql';
$news_base_path = __DIR__ . '/noticias/'; // Assuming the script is run from the root directory

$sql_statements = []; // Esta variável não será mais usada para SQL, mas pode ser removida posteriormente

// Função para formatar a data para o formato do WordPress
function format_date_for_wordpress($date_str) {
    // Expected format: DD/MM/YYYY - HHhMM, DD/MM/YYYY - HH:MM, DD/MM/YYYY - HHh, or DD/MM/YYYY
    // Target format: YYYY-MM-DD HH:MM:SS

    // Try to match with time component
    if (preg_match('/(\d{2})\/(\d{2})\/(\d{4}) - (\d{1,2})(?:[h:](\d{1,2}))?/', $date_str, $matches)) {
        $day = $matches[1];
        $month = $matches[2];
        $year = $matches[3];
        $hour = str_pad($matches[4], 2, '0', STR_PAD_LEFT);
        $minute = isset($matches[5]) ? str_pad($matches[5], 2, '0', STR_PAD_LEFT) : '00';
        return "$year-$month-$day $hour:$minute:00";
    }
    // Try to match without time component (just DD/MM/YYYY)
    if (preg_match('/(\d{2})\/(\d{2})\/(\d{4})/', $date_str, $matches)) {
        $day = $matches[1];
        $month = $matches[2];
        $year = $matches[3];
        return "$year-$month-$day 00:00:00"; // Default time to 00:00:00
    }
    return '0000-00-00 00:00:00';
}

// Função para extrair o primeiro parágrafo do conteúdo HTML
function get_first_paragraph($html_content) {
    // Tenta encontrar o conteúdo dentro de uma tag <p>
    if (preg_match('/<p>(.*?)<\/p>/is', $html_content, $matches)) {
        $first_p_content = trim($matches[1]);
        // Remove outras tags HTML dentro do parágrafo, mantendo apenas o texto
        return strip_tags($first_p_content);
    }
    // Se não houver <p> tags, tenta pegar o primeiro bloco de texto antes de um <br> ou nova linha dupla
    $clean_content = strip_tags($html_content);
    $lines = explode("\n\n", $clean_content); // Divide por quebras de linha duplas
    return trim($lines[0]);
}

// Get all subdirectories (news articles)
$news_dirs = glob($news_base_path . '*/', GLOB_ONLYDIR);

echo "Encontradas " . count($news_dirs) . " pastas de notícias.\n";

foreach ($news_dirs as $news_dir) {
    $index_file = $news_dir . 'index.php';

    if (file_exists($index_file)) {
        $html_content = file_get_contents($index_file);

        // Check if it's a redirect page
        if (preg_match('/<meta http-equiv="refresh"/i', $html_content)) {
            echo "\n--- Pulando notícia (redirecionamento): " . basename($news_dir) . " ---\n";
            continue; // Skip this directory
        }

        $title = '';
        $date_str = '';
        $excerpt = '';
        $post_content = '';
        $image_url = '';
        $slug = basename($news_dir); // The directory name is the slug

        // Extract Title from <h1>
        if (preg_match('/<h1>(.*?)<\/h1>/s', $html_content, $matches)) {
            $title = trim($matches[1]);
        } else {
            // Fallback to <title> tag if <h1> not found
            if (preg_match('/<title>(.*?) \| ANSEGTV<\/title>/s', $html_content, $matches)) {
                $title = trim($matches[1]);
            }
        }

        // Extract Date
        if (preg_match('/<span class="mx-2 date"><i class="fa fa-clock-o"><\/i>\s*([^<]+?)\s*<\/span>/s', $html_content, $matches)) {
            $date_str = trim($matches[1]);
        }
        $post_date = format_date_for_wordpress($date_str);
        $post_date_iso = substr($post_date, 0, 10); // Format YYYY-MM-DD

        // Extract Excerpt from meta description or og:description
        if (preg_match('/<meta[^>]+name="description"[^>]+content="(.*?)"/s', $html_content, $matches)) {
            $excerpt = trim($matches[1]);
        } elseif (preg_match('/<meta property="og:description" content="(.*?)"/s', $html_content, $matches)) {
            $excerpt = trim($matches[1]);
        }

        // Extract main content block
        if (preg_match('/<div class="col-12 col-md-10 offset-md-1(?:\\s+content-news)?">(.*?)<\/div>/s', $html_content, $matches)) {
            $raw_post_content = trim($matches[1]);

            // Replace relative image paths (e.g., ../../img/uploads/) with WordPress uploads base URL
            $post_content = preg_replace('/src="(\.\.\/\.\.\/img\/uploads\/(\d{4})\/(\d{2})\/(.*?))"/i', 'src="' . WP_UPLOADS_BASE_URL . '2025/06/' . '$4"', $raw_post_content);
        }

        // Extract Image URL from OpenGraph meta tag for potential featured image
        if (preg_match('/<meta property="og:image" content="(.*?)"/s', $html_content, $matches)) {
            $image_url = trim($matches[1]);
            // Padroniza a URL da imagem de destaque para a data 2025/06
            if (!empty($image_url)) {
                $filename = basename($image_url); // Pega apenas o nome do arquivo da imagem
                $image_url = WP_UPLOADS_BASE_URL . '2025/06/' . $filename;
            }
        }

        echo "\n--- Processando notícia: " . $slug . " ---\n";
        echo "Título: " . $title . "\n";
        echo "Data Original: " . $date_str . "\n";
        echo "Data Formatada: " . $post_date . "\n";
        echo "Resumo (Excerpt): " . (empty($excerpt) ? 'NÃO ENCONTRADO' : substr($excerpt, 0, 50) . '...') . "\n";
        echo "Conteúdo (Tamanho): " . strlen($post_content) . " bytes" . "\n";
        echo "Imagem URL: " . (empty($image_url) ? 'NÃO ENCONTRADA' : $image_url) . "\n";

        // Variáveis para as novas tags de SEO
        $escaped_title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
        $first_paragraph_content = get_first_paragraph($post_content); // Use post_content here, as it's the cleaned content
        $escaped_first_paragraph = htmlspecialchars($first_paragraph_content, ENT_QUOTES, 'UTF-8');
        $escaped_image_url = htmlspecialchars($image_url, ENT_QUOTES, 'UTF-8');
        $escaped_slug = htmlspecialchars($slug, ENT_QUOTES, 'UTF-8');

        // Injetar tags de SEO no HTML
        $html_content = preg_replace('/<title>(.*?)<\/title>/s', '<title>' . $escaped_title . ' | ANSEGTV</title>', $html_content);
        $html_content = preg_replace('/<meta[^>]+name="description"[^>]+content="(.*?)"[^>]*>/s', '<meta name="description" content="' . $escaped_first_paragraph . '" />', $html_content);
        $html_content = preg_replace('/<meta property="og:title" content="(.*?)"[^>]*>/s', '<meta property="og:title" content="%%title%%">', $html_content);
        $html_content = preg_replace('/<meta property="og:description" content="(.*?)"[^>]*>/s', '<meta property="og:description" content="' . $escaped_first_paragraph . '">', $html_content);
        $html_content = preg_replace('/<meta property="og:image" content="(.*?)"[^>]*>/s', '<meta property="og:image" content="' . $escaped_image_url . '">', $html_content);
        // Remove o meta name="image" property="og:image" se existir para evitar duplicação
        $html_content = preg_replace('/<meta name="image" property="og:image"[^>]*>/s', '', $html_content);
        $html_content = preg_replace('/<link rel="canonical" href="(.*?)"[^>]*>/s', '<link rel="canonical" href="https://ansegtv.com.br/noticias/' . $escaped_slug . '" />', $html_content);
        $html_content = preg_replace('/<meta property="og:url" content="(.*?)"[^>]*>/s', '<meta property="og:url" content="https://ansegtv.com.br/noticias/' . $escaped_slug . '">', $html_content);
        $html_content = preg_replace('/<meta property="article:published_time" content="(.*?)"[^>]*>/s', '<meta property="article:published_time" content="' . $post_date_iso . '">', $html_content);

        file_put_contents($index_file, $html_content);

        echo "Arquivo " . basename($index_file) . " atualizado com sucesso.\n";
    }
}

echo "Processamento de notícias concluído.\n";

?> 