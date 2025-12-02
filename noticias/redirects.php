<?php
/**
 * Sistema de Redirecionamentos 301 - Notícias ANSEGTV
 * Mapeia URLs antigas para novas URLs de notícias
 */

class NewsRedirects {
    private $redirects_map;
    
    public function __construct() {
        // Mapeamento de URLs antigas para novas
        // Formato: 'url_antiga' => 'slug_novo'
        $this->redirects_map = [
            // Notícias removidas - redirecionamento para home
            '/noticias/esquadra-deixa-mercado-de-transporte-de-valores/' => 'home',
            'noticias/secretario-nacional-de-seguranca-publica-recebe-diretoria-da-ansegtv' => 'home',
            'noticias/ansegtv-se-reune-com-novo-coordenador-geral-na-pf' => 'home',
            'noticias/diretoria-da-ansegtv-e-recebida-pelo-novo-coordenador-da-cgcsp' => 'home',
            'noticias/combate-ao-crime-e-tema-de-encontro-entre-ansegtv-e-governo-federal' => 'home',
            'noticias/ansegtv-faz-visita-institucional-a-cnt' => 'home',
            'noticias/encontro-reune-delegados-de-policia-em-sp' => 'home',
            'noticias/diretores-da-ansegtv-visitam-novo-delegado-da-delesp-sp' => 'home',
            'noticias/ansegtv-faz-balanco-do-ano-em-reuniao' => 'home',
            'noticias/ansegtv-se-reune-com-ministerio-da-justica-em-brasilia' => 'home',
            'noticias/em-live-associados-debatem-desafios-da-pandemia' => 'home',
            'noticias/reuniao-com-secretario-da-receita-discute-questoes-tributarias' => 'home',
            'noticias/policia-federal-elabora-manual-de-prevencao-a-lavagem-de-dinheiro' => 'home',
            'noticias/ansegtv-participa-de-audiencia-publica-no-senado-sobre-seguranca-publica' => 'home',
            'noticias/ansegtv-e-criada-para-incentivar-livre-concorrencia-no-setor' => 'home',
            
            // Exemplos de URLs antigas (substitua pelos seus casos reais)
            'noticias/2019/materia-antiga.html' => 'materia-antiga',
            'noticias/2020/outra-materia-antiga.php' => 'outra-materia-antiga',
            'noticias/2021/materia-com-data.html' => 'materia-com-data',
            
            // Adicione mais mapeamentos conforme necessário
            // 'caminho/antigo/arquivo.html' => 'slug-novo',
        ];
    }
    
    /**
     * Verifica se a URL atual precisa de redirecionamento
     */
    public function needsRedirect($request_uri) {
        $path = parse_url($request_uri, PHP_URL_PATH);
        $path = trim($path, '/');
        
        return isset($this->redirects_map[$path]);
    }
    
    /**
     * Obtém a nova URL para redirecionamento
     */
    public function getNewUrl($request_uri) {
        $path = parse_url($request_uri, PHP_URL_PATH);
        $path = trim($path, '/');
        
        if (isset($this->redirects_map[$path])) {
            $new_slug = $this->redirects_map[$path];
            
            // Se for 'home', redirecionar para a raiz do site
            if ($new_slug === 'home') {
                return '/';
            }
            
            return '/noticias/' . $new_slug . '/';
        }
        
        return null;
    }
    
    /**
     * Executa o redirecionamento 301
     */
    public function redirect($request_uri) {
        $new_url = $this->getNewUrl($request_uri);
        
        if ($new_url) {
            // Log do redirecionamento para analytics
            error_log("301 Redirect: " . $request_uri . " -> " . $new_url);
            
            // Redirecionamento 301 (permanente)
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: " . $new_url);
            exit();
        }
    }
    
    /**
     * Verifica se o slug existe no WordPress antes de redirecionar
     */
    public function validateRedirect($request_uri) {
        $new_url = $this->getNewUrl($request_uri);

        // Se o destino for a home ("/"), considerar sempre válido
        if ($new_url === '/') {
            return true;
        }

        if ($new_url) {
            $slug = $this->redirects_map[trim(parse_url($request_uri, PHP_URL_PATH), '/')];

            // Verificar se o slug existe na API do WordPress
            $api_url = 'https://ansegtv.com.br/website/wp-json/wp/v2/posts?slug=' . $slug . '&_embed';
            $context = stream_context_create([
                'http' => [
                    'header' => 'Accept: application/json',
                    'timeout' => 10
                ]
            ]);

            $response = @file_get_contents($api_url, false, $context);

            if ($response !== false) {
                $posts = json_decode($response, true);
                return !empty($posts); // Retorna true se o post existe
            }
        }

        return false;
    }
    
    /**
     * Adiciona novo mapeamento de redirecionamento
     */
    public function addRedirect($old_url, $new_slug) {
        $this->redirects_map[$old_url] = $new_slug;
    }
    
    /**
     * Remove mapeamento de redirecionamento
     */
    public function removeRedirect($old_url) {
        if (isset($this->redirects_map[$old_url])) {
            unset($this->redirects_map[$old_url]);
        }
    }
    
    /**
     * Lista todos os redirecionamentos ativos
     */
    public function getAllRedirects() {
        return $this->redirects_map;
    }
}

// Função helper para obter instância dos redirecionamentos
function getNewsRedirects() {
    static $redirects = null;
    if ($redirects === null) {
        $redirects = new NewsRedirects();
    }
    return $redirects;
}
?> 