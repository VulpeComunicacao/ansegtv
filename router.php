<?php

// Incluir sistema de redirecionamentos
require_once __DIR__ . '/noticias/redirects.php';

$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$query = parse_url($request_uri, PHP_URL_QUERY);

// Verificar se precisa de redirecionamento 301
$redirects = getNewsRedirects();
if ($redirects->needsRedirect($request_uri)) {
    // Validar se o redirecionamento é válido antes de executar
    if ($redirects->validateRedirect($request_uri)) {
        $redirects->redirect($request_uri);
    }
}

// Se a requisição for para /noticias/ ou /noticias/algum-slug/
if (strpos($path, '/noticias/') === 0) {
    // Analisa a string de consulta e preenche $_GET
    if ($query) {
        parse_str($query, $_GET);
    } else {
        $_GET = []; // Garante que esteja vazio se não houver consulta
    }
    
    // Verifica se é uma notícia individual (tem slug)
    $path_parts = explode('/', trim($path, '/'));
    
    if (count($path_parts) > 1 && $path_parts[1] !== '') {
        // É uma notícia individual - definir o slug
        $_GET['post_slug'] = $path_parts[1];
    }
    
    require __DIR__ . '/noticias/index.php';
    exit;
}

// Se o arquivo solicitado existir (para CSS, JS, Imagens, etc.)
if (file_exists(__DIR__ . $path)) {
    return false; // Permite que o servidor embutido sirva o arquivo diretamente
}

// Para as outras páginas estáticas (index.php na raiz, etc.)
// Se você tem um index.php na raiz que serve o restante do site estático
if ($path === '/' || $path === '/index.php') {
    require __DIR__ . '/index.php';
    exit;
}

// Fallback para 404 (opcional)
http_response_code(404);
echo "404 - Página Não Encontrada";

?> 