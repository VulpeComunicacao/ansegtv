<!doctype html>
<html lang="pt-BR">

        <?php
        // Incluir sistema de cache
        require_once __DIR__ . '/cache.php';
        
        // Incluir sistema de otimização de imagens
        require_once __DIR__ . '/image-optimizer.php';
        
        // Configurações do WordPress para padronização de imagens
        define('WP_UPLOADS_BASE_URL', 'https://ansegtv.com.br/website/wp-content/uploads/');

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

        $request_uri = $_SERVER['REQUEST_URI'];
        $path_parts = explode('/', trim($request_uri, '/'));

        $is_single_post = false;
        $post_slug = '';

        // Inicializa variáveis SEO para evitar 'Undefined variable' warnings
        $seo_title = 'ANSEGTV - Associação Nacional de Segurança e Transporte de Valores';
        $seo_first_paragraph = 'ANSEGTV é a Associação Nacional de Segurança e Transporte de Valores. Uma entidade sem fins lucrativos estrutura para representar os interesses das empresas do segmento.';
        $seo_image_url = 'https://ansegtv.com.br/img/open-graph-ansegtv2.png';
        $seo_post_date_iso = date('Y-m-d'); // Data atual como fallback
        $seo_permalink = 'https://ansegtv.com.br' . htmlspecialchars($request_uri, ENT_QUOTES, 'UTF-8');
        $post = null; // Inicializa $post como null

        // Verifica se o slug foi passado pelo router.php
        if (isset($_GET['post_slug']) && !empty($_GET['post_slug'])) {
            $is_single_post = true;
            $post_slug = $_GET['post_slug'];
        }
        // Fallback: Verifica se o último segmento da URL é um slug de notícia (e não 'noticias' em si)
        elseif (count($path_parts) > 1 && $path_parts[count($path_parts) - 1] !== 'noticias' && $path_parts[count($path_parts) - 1] !== '') {
            $is_single_post = true;
            $post_slug = $path_parts[count($path_parts) - 1];
        }

        if ($is_single_post) {
            // Usar cache para notícias individuais (cache por 30 minutos)
            $cache = getNewsCache(1800);
            $api_url = 'https://ansegtv.com.br/website/wp-json/wp/v2/posts?slug=' . $post_slug . '&_embed';
            $posts = $cache->getFromAPI($api_url, 'single_post', ['slug' => $post_slug]);
            $post = !empty($posts) ? $posts[0] : null; // Pega o primeiro (e único) post

            if ($post) {
                // --- Extrair dados para SEO dinâmico ---
                $seo_title = htmlspecialchars($post['title']['rendered'], ENT_QUOTES, 'UTF-8');
                $seo_first_paragraph = htmlspecialchars(get_first_paragraph($post['content']['rendered']), ENT_QUOTES, 'UTF-8');

                $seo_image_url = '';
                if (isset($post['_embedded']['wp:featuredmedia'][0]) && isset($post['_embedded']['wp:featuredmedia'][0]['source_url'])) {
                    $seo_image_url = $post['_embedded']['wp:featuredmedia'][0]['source_url'];
                } elseif (isset($post['yoast_head_json']['og_image'][0]) && isset($post['yoast_head_json']['og_image'][0]['url'])) {
                    $seo_image_url = $post['yoast_head_json']['og_image'][0]['url'];
                }
                // Se a URL da imagem ainda estiver vazia, tente extrair do conteúdo HTML
                if (empty($seo_image_url) && isset($post['content']['rendered'])) {
                    preg_match('/<img[^>]+src="([^"]+)"/i', $post['content']['rendered'], $matches);
                    if (isset($matches[1])) {
                        $seo_image_url = $matches[1];
                    }
                }
                // Padroniza o caminho da imagem para uploads/2025/06/
                if (!empty($seo_image_url)) {
                    $filename = basename($seo_image_url);
                    $seo_image_url = WP_UPLOADS_BASE_URL . '2025/06/' . $filename;
                }
                $seo_image_url = htmlspecialchars($seo_image_url, ENT_QUOTES, 'UTF-8');

                $seo_post_date = format_date_for_wordpress($post['date']);
                $seo_post_date_iso = substr($seo_post_date, 0, 10); // Formato YYYY-MM-DD
                $seo_permalink = 'https://ansegtv.com.br/noticias/' . htmlspecialchars($post_slug, ENT_QUOTES, 'UTF-8') . '/';
            }
        } else {
            $posts_per_page = 24; // Número de posts por página na carga inicial e a cada 'load more'
            $current_page = 1; // Sempre começa na página 1 para o carregamento inicial

            // Usar cache para lista de notícias (cache por 15 minutos)
            $cache = getNewsCache(900);
            $api_url = 'https://ansegtv.com.br/website/wp-json/wp/v2/posts?_embed&per_page=' . $posts_per_page . '&page=' . $current_page;
            $posts = $cache->getFromAPI($api_url, 'posts_list', ['per_page' => $posts_per_page, 'page' => $current_page]);
            
            $total_posts = 0;
            $total_pages = 0;

            // Sempre fazer uma requisição para obter os headers de paginação
            $context = stream_context_create([
                'http' => [
                    'header' => 'Accept: application/json'
                ]
            ]);
            
            $response = @file_get_contents($api_url, false, $context);
            if ($response !== false) {
                // Se não temos posts do cache, usar a resposta da API
                if ($posts === null) {
                    $posts = json_decode($response, true);
                }
                
                // Extrair X-WP-Total e X-WP-TotalPages dos cabeçalhos
                if (isset($http_response_header)) {
                    foreach ($http_response_header as $header) {
                        if (preg_match('/X-WP-Total:\s*(\d+)/i', $header, $matches)) {
                            $total_posts = (int)$matches[1];
                        }
                        if (preg_match('/X-WP-TotalPages:\s*(\d+)/i', $header, $matches)) {
                            $total_pages = (int)$matches[1];
                        }
                    }
                }
            } else {
                if ($posts === null) {
                    $posts = [];
                }
            }
        }

        // Verifica se a requisição foi bem-sucedida e se há posts
        if (is_array($posts) && !empty($posts)) {
            // Function to format date to Brazilian Portuguese
            function format_date_br($date_string) {
                $date = new DateTime($date_string);
                
                // Usando IntlDateFormatter para PHP 8.1+ (recomendado)
                if (class_exists('IntlDateFormatter')) {
                    $formatter = new IntlDateFormatter(
                        'pt_BR', // Locale
                        IntlDateFormatter::LONG, // Tipo de data (ex: "6 de junho de 2024")
                        IntlDateFormatter::NONE, // Tipo de hora (nenhuma hora)
                        'America/Sao_Paulo', // Fuso horário (opcional, mas boa prática)
                        IntlDateFormatter::GREGORIAN, // Calendário (opcional)
                        'dd \'de\' MMMM \'de\' yyyy' // Padrão personalizado
                    );
                    return $formatter->format($date);
                } else {
                    // Fallback para versões mais antigas do PHP ou se a extensão intl não estiver disponível
                    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf8', 'portuguese');
                    return strftime('%d de %B de %Y', $date->getTimestamp());
                }
            }

            // Function to format date and time to Brazilian Portuguese (dd/mm/yyyy - HHhMM)
            function format_datetime_br($date_string) {
                $date = new DateTime($date_string);
                return $date->format('d/m/Y - H\hi');
            }

            // Function to strip HTML tags and limit characters
               function get_excerpt_clean($excerpt_html, $limit = 150) {
                    $text = strip_tags($excerpt_html);
                    if (strlen($text) > $limit) {
                        $text = substr($text, 0, $limit) . "...";
                    }
                    return $text;
                }
        } else {
            $posts = []; // Garante que $posts é um array mesmo em caso de erro
        }
        ?>

        <!-- Head Section -->
        <?php if ($is_single_post && $post): ?>
        <head>
            <title><?php echo $seo_title; ?> | ANSEGTV</title>
            <meta charset="utf-8">
            <link rel="canonical" href="<?php echo $seo_permalink; ?>" />
            <meta name="description" content="<?php echo $seo_first_paragraph; ?>" />
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="robots" content="index, follow">
            <meta name="google-site-verification" content="fr91pley1eSdpCFMb-YbDS8l9GqIjBnP8ZsXShUXsRg" />
            <!-- OpenGraph TAGS -->
            <meta property="og:type" content="article">
            <meta property="og:title" content="%%title%%">
            <meta property="og:description" content="<?php echo $seo_first_paragraph; ?>">
            <meta property="og:site_name" content="ANSEGTV">
            <meta property="og:url" content="<?php echo $seo_permalink; ?>">
            <meta property="og:image" content="<?php echo $seo_image_url; ?>">
            <meta property="article:published_time" content="<?php echo $seo_post_date_iso; ?>">
            <meta property="article:author" content="https://ansegtv.com.br/">

            <script type="application/ld+json">
            {
              "@context": "https://schema.org",
              "@type": "NewsArticle",
              "headline": "<?php echo $seo_title; ?>",
              "image": [
                "<?php echo $seo_image_url; ?>"
              ],
              "datePublished": "<?php echo $seo_post_date_iso; ?>T00:00:00Z",
              "dateModified": "<?php echo $seo_post_date_iso; ?>T00:00:00Z",
              "author": {
                "@type": "Person",
                "name": "<?php echo $author_name; ?>"
              },
              "publisher": {
                "@type": "Organization",
                "name": "ANSEGTV",
                "logo": {
                  "@type": "ImageObject",
                  "url": "https://ansegtv.com.br/img/logo-header.png"
                }
              },
              "description": "<?php echo $seo_first_paragraph; ?>"
            }
            </script>

            <!-- Bootstrap CSS -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
            <!-- Custom CSS -->
            <link rel="stylesheet" href="../../css/style.css">

            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-RP1BVPHJHD"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());

              gtag('config', 'G-RP1BVPHJHD');
            </script>
            
          </head>
        <?php else: ?>
            <?php include dirname(__DIR__) . '/includes/head.php';?>
        <?php endif; ?>

    <body>

        <!-- Navigation Section -->
        <?php include dirname(__DIR__) . '/includes/navbar.php';?>

        <!-- News Section -->
        <section class="blog-me pb-60" id="blog">
         <div class="container">
            <?php if ($is_single_post && $post): ?>
                <div class="row justify-content-center">
                  <div class="col-12 col-md-10 offset-md-1 title-news text-center">
                     <div class="single-post-wrapper my-4 p-4 bg-white rounded">
                           <h1 class="mb-3"><?php echo $post['title']['rendered']; ?></h1>
                           <div class="meta mb-3 text-muted">
                              <?php
                              $author_name = isset($post['_embedded']['author'][0]['name']) ? $post['_embedded']['author'][0]['name'] : 'AnsegTV'; // Fallback para 'AnsegTV'
                              $formatted_datetime = format_datetime_br($post['date']);

                              $tags_array = [];
                              if (isset($post['_embedded']['wp:term'])) {
                                  foreach ($post['_embedded']['wp:term'] as $terms_taxonomy) {
                                      foreach ($terms_taxonomy as $term) {
                                          if ($term['taxonomy'] === 'post_tag') {
                                              $tags_array[] = $term['name'];
                                          }
                                      }
                                  }
                              }
                              $tags_string = !empty($tags_array) ? implode(', ', $tags_array) : '';
                              ?>
                              <p class="post-infos">
                                 <span class="mx-2 author"><i class="fa fa-user"></i>  <?php echo $author_name; ?></span>
                                 | <span class="mx-2 date"><i class="fa fa-clock-o"></i><?php echo $formatted_datetime; ?></span>
                                 <?php if (!empty($tags_string)): ?>
                                    | <span class="mx-2 category"><i class="fa fa-tag"></i> <?php echo $tags_string; ?></span>
                                 <?php endif; ?>
                              </p>
                           </div>
                           <hr class="divider">
                     </div>
                  </div>
               </div>
            <?php
               $content_html = $post['content']['rendered'];
               // Adiciona a classe img-fluid e otimiza todas as tags <img> dentro do conteúdo
               $content_html = preg_replace_callback('/<img([^>]+)>/i', function($matches) {
                   $img_tag = $matches[0];
                   $img_attrs = $matches[1];
                   
                   // Extrair src da imagem
                   if (preg_match('/src=["\']([^"\']+)["\']/i', $img_attrs, $src_matches)) {
                       $src = $src_matches[1];
                       $alt = '';
                       
                       // Extrair alt se existir
                       if (preg_match('/alt=["\']([^"\']+)["\']/i', $img_attrs, $alt_matches)) {
                           $alt = $alt_matches[1];
                       }
                       
                       // Gerar nova tag otimizada
                       return generateOptimizedImg($src, $alt, 'img-fluid');
                   }
                   
                   // Se não conseguir extrair src, apenas adicionar classe
                   return str_replace('<img', '<img class="img-fluid"', $img_tag);
               }, $content_html);
            ?>
            <div class="row justify-content-center">
                  <div class="col-12 col-md-10 offset-md-1 content-news">
                     <div class="single-content lead">
                        <?php echo $content_html; ?>
                     </div>
                  </div>
                  <div class="col text-center mt-4">
                     <a href="/noticias/" class="voltar">Voltar para Notícias</a>
                  </div>
            </div>

            <?php else: // Lista de posts ou nenhum post encontrado ?>
                <div class="estrutura-title">
                   <h2 >Notícias</h2>
                </div>
                <div class="row mb-50" id="news-container"> <!-- Added ID for JS -->

                   <?php if (!empty($posts)): ?>
                       <?php foreach ($posts as $post): ?>
                           <?php
                           $title = $post['title']['rendered'];
                           $excerpt = get_excerpt_clean($post['excerpt']['rendered'], 150);
                           $link = $post['link']; // Link original do WordPress
                           $date = format_date_br($post['date']);
                           $image_url = '';
                           // Prioriza wp:featuredmedia, fallback para og_image
                           if (isset($post['_embedded']['wp:featuredmedia'][0]) && isset($post['_embedded']['wp:featuredmedia'][0]['source_url'])) {
                               $image_url = $post['_embedded']['wp:featuredmedia'][0]['source_url'];
                           } elseif (isset($post['yoast_head_json']['og_image'][0]) && isset($post['yoast_head_json']['og_image'][0]['url'])) {
                               $image_url = $post['yoast_head_json']['og_image'][0]['url'];
                           }

                           // Padroniza o caminho da imagem para uploads/2025/06/
                           if (!empty($image_url)) {
                            $filename = basename($image_url);
                            $image_url = WP_UPLOADS_BASE_URL . '2025/06/' . $filename;
                            }

                           // Ajusta o link para o slug localmente
                           $local_link = '/noticias/' . $post['slug'] . '/';
                           ?>
                           <div class="col-xl-3 col-md-4 col-sm-6 mb-20">
                               <div class="single-blog">
                                   <div class="blog-img">
                                       <a href="<?php echo $local_link; ?>">
                                           <img src="<?php echo $image_url; ?>" alt="<?php echo $title; ?>" loading="lazy" class="img-fluid">
                                       </a>
                                   </div>
                                   <div class="blog-content">
                                       <div class="blog-title">
                                           <h2 class="title-large"><a href="<?php echo $local_link; ?>"><?php echo $title; ?></a></h2>
                                           <div class="meta">
                                               <ul>
                                                   <li><?php echo $date; ?></li>
                                               </ul>
                                           </div>
                                       </div>
                                       <p><?php echo $excerpt; ?></p>
                                       <a href="<?php echo $local_link; ?>" class="box_btn">leia mais</a>
                                   </div>
                               </div>
                           </div>
                       <?php endforeach; ?>
                   <?php else: ?>
                       <div class="col-12">
                           <p>Nenhuma notícia encontrada no momento.</p>
                       </div>
                   <?php endif; ?>

                </div>
            <?php endif; ?>
             
            <!-- Load More Button and JavaScript -->
            <?php if (isset($total_pages) && $total_pages > 1): ?>
                <div class="row">
                    <div class="col-12 text-center mt-4">
                        <button id="load-more-btn" class="btn-warning btn-all-news">Carregar Mais Notícias</button>
                    </div>
                </div>
            <?php else: ?>
                <!-- Debug temporário -->
                <div class="row">
                    <div class="col-12 text-center mt-4">
                        <small class="text-muted">
                            Debug: total_pages = <?php echo isset($total_pages) ? $total_pages : 'não definido'; ?>, 
                            total_posts = <?php echo isset($total_posts) ? $total_posts : 'não definido'; ?>
                        </small>
                    </div>
                </div>
            <?php endif; ?>

         </div>
      </section>

    <!-- Inclua jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Popper.js para Bootstrap (requer jQuery) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <!-- Bootstrap JS (requer Popper.js e jQuery) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <script>
        jQuery(document).ready(function($) {
            let currentPage = 1;
            const postsPerPage = <?php echo isset($posts_per_page) ? $posts_per_page : 24; ?>;
            let totalPages = <?php echo isset($total_pages) ? $total_pages : 1; ?>;
            const newsContainer = $('#news-container');
            const loadMoreBtn = $('#load-more-btn');

            // Debug temporário
            console.log('Posts per page:', postsPerPage);
            console.log('Total pages:', totalPages);
            console.log('Load more button exists:', loadMoreBtn.length > 0);

            // Cache local para evitar requisições duplicadas
            const requestCache = {};

            loadMoreBtn.on('click', function() {
                currentPage++;
                if (currentPage <= totalPages) {
                    const apiUrl = `https://ansegtv.com.br/website/wp-json/wp/v2/posts?_embed&per_page=${postsPerPage}&page=${currentPage}`;
                    
                    // Verificar cache local primeiro
                    if (requestCache[apiUrl]) {
                        appendPosts(requestCache[apiUrl]);
                        return;
                    }

                    $.ajax({
                        url: apiUrl,
                        method: 'GET',
                        beforeSend: function() {
                            loadMoreBtn.text('Carregando...').prop('disabled', true);
                        },
                        success: function(data) {
                            // Salvar no cache local
                            requestCache[apiUrl] = data;
                            
                            if (data.length > 0) {
                                appendPosts(data);
                            } else {
                                loadMoreBtn.text('Todas as notícias carregadas.').prop('disabled', true);
                            }
                        },
                        complete: function(xhr) {
                            // Update totalPages from headers for subsequent calls
                            const totalPostsHeader = xhr.getResponseHeader('X-WP-Total');
                            const totalPagesHeader = xhr.getResponseHeader('X-WP-TotalPages');
                            if (totalPagesHeader) {
                                totalPages = parseInt(totalPagesHeader);
                            }
                            
                            if (currentPage >= totalPages) {
                                loadMoreBtn.text('Todas as notícias carregadas.').prop('disabled', true);
                            } else {
                                loadMoreBtn.text('Carregar Mais Notícias').prop('disabled', false);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Erro ao carregar mais notícias:", status, error);
                            loadMoreBtn.text('Erro ao Carregar Notícias').prop('disabled', true);
                        }
                    });
                } else {
                    loadMoreBtn.text('Todas as notícias carregadas.').prop('disabled', true);
                }
            });

            // Função para adicionar posts ao container
            function appendPosts(data) {
                let newPostsHtml = '';
                data.forEach(function(post) {
                    let title = post.title.rendered;
                    let excerpt = post.excerpt.rendered.replace(/<[^>]*>/g, ''); // Strip HTML from excerpt
                    if (excerpt.length > 150) {
                        excerpt = excerpt.substring(0, 150) + '...';
                    }
                    let local_link = `/noticias/${post.slug}/`;
                    let imageUrl = '';
                    if (post._embedded && post._embedded['wp:featuredmedia'] && post._embedded['wp:featuredmedia'][0] && post._embedded['wp:featuredmedia'][0].source_url) {
                        imageUrl = post._embedded['wp:featuredmedia'][0].source_url;
                    } else if (post.yoast_head_json && post.yoast_head_json.og_image && post.yoast_head_json.og_image[0] && post.yoast_head_json.og_image[0].url) {
                        imageUrl = post.yoast_head_json.og_image[0].url;
                    }

                    // Padroniza a URL da imagem para uploads/2025/06/
                    if (imageUrl) {
                        const filename = imageUrl.substring(imageUrl.lastIndexOf('/') + 1);
                        imageUrl = `https://ansegtv.com.br/website/wp-content/uploads/2025/06/${filename}`;
                    }

                    let date = new Date(post.date).toLocaleDateString('pt-BR', { day: '2-digit', month: 'long', year: 'numeric' });

                    newPostsHtml += `
                        <div class="col-xl-3 col-md-4 col-sm-6 mb-20">
                            <div class="single-blog">
                                <div class="blog-img">
                                    <a href="${local_link}">
                                        <img src="${imageUrl}" alt="${title}" loading="lazy" class="img-fluid">
                                    </a>
                                </div>
                                <div class="blog-content">
                                    <div class="blog-title">
                                        <h2 class="title-large"><a href="${local_link}">${title}</a></h2>
                                        <div class="meta">
                                            <ul>
                                                <li>${date}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <p>${excerpt}</p>
                                    <a href="${local_link}" class="box_btn">leia mais</a>
                                </div>
                            </div>
                        </div>
                    `;
                });
                newsContainer.append(newPostsHtml);
            }

            // Movido para dentro do document.ready
            $('.carousel').carousel({
                interval: 5000
            });

            // Movido para dentro do document.ready
            $('ul.nav li.dropdown').hover(function() {
                $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(300);
            }, function() {
                $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(300);
            });
        });
    </script>
  <script src="//tag.goadopt.io/injector.js?website_code=98bfaecd-beda-4252-b173-f964b7a9a092" class="adopt-injector"></script>
  <?php include_once dirname(__DIR__) . '/includes/footer.php'; ?>
 </body>
</html> 