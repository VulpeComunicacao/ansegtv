<?php
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
function get_excerpt_clean($excerpt_html, $limit = 550) {
    $text = strip_tags($excerpt_html);
    if (strlen($text) > $limit) {
        $text = substr($text, 0, $limit) . "...";
    }
    return $text;
}

$api_url = 'https://ansegtv.com.br/website/wp-json/wp/v2/posts?_embed&per_page=7'; // Busca 7 posts
$response = file_get_contents($api_url);
$posts = json_decode($response, true);

// Garante que $posts é um array mesmo em caso de erro
if (!is_array($posts)) {
    $posts = [];
}
?>

<section id="noticias" class="banner-sec">
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="title-header pull-left">NOTÍCIAS</h2>
                <a class="btn-warning btn-all-news mt-3 pull-right" href="./noticias/">Veja todas</a>
            </div>
        </div>
    
        <div class="row">
            <?php if (!empty($posts)): ?>
                <div class="col-md-6 top-slider">
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel"> 
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <?php for ($i = 0; $i < min(3, count($posts)); $i++): ?>
                                <li data-target="#carousel-example-generic" data-slide-to="<?php echo $i; ?>" class="<?php echo ($i === 0) ? 'active' : ''; ?>"></li>
                            <?php endfor; ?>
                        </ol>
                    
                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            <?php foreach (array_slice($posts, 0, 3) as $key => $post): 
                                $title = $post['title']['rendered'];
                                $link = './noticias/' . $post['slug'] . '/';
                                $date = format_date_br($post['date']);
                                $excerpt = get_excerpt_clean($post['excerpt']['rendered']);
                                $image_url = '';
                                if (isset($post['_embedded']['wp:featuredmedia'][0]['source_url'])) {
                                    $image_url = $post['_embedded']['wp:featuredmedia'][0]['source_url'];
                                } elseif (isset($post['yoast_head_json']['og_image'][0]['url'])) {
                                    $image_url = $post['yoast_head_json']['og_image'][0]['url'];
                                }
                                // Se a URL da imagem ainda estiver vazia, tente extrair do conteúdo HTML
                                if (empty($image_url) && isset($post['content']['rendered'])) {
                                    preg_match('/<img[^>]+src="([^"]+)"/i', $post['content']['rendered'], $matches);
                                    if (isset($matches[1])) {
                                        $image_url = $matches[1];
                                    }
                                }
                                // Padroniza o caminho da imagem para uploads/2025/06/
                                $image_url = preg_replace('/uploads\/\\d{4}\\/\\d{2}\\//', 'uploads/2025/06/', $image_url);
                            ?>
                                <div class="carousel-item <?php echo ($key === 0) ? 'active' : ''; ?>">
                                    <div class="news-block">
                                        <div class="news-media">
                                            <a href="<?php echo $link; ?>">
                                                <img class="img-fluid" src="<?php echo $image_url; ?>" alt="<?php echo $title; ?>">
                                            </a>
                                        </div>
                                        <div class="time-text">
                                            <strong><i class="fa fa-clock"></i><?php echo $date; ?></strong>
                                        </div>
                                        <div class="news-title">
                                            <h3 class=" title-large">
                                            <a href="<?php echo $link; ?>"><?php echo $title; ?></a>
                                            </h3>
                                        </div>
                                        <div class="news-des">
                                            <?php echo $excerpt; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            
                <div class="col-md-3"> <!-- Primeiro Bloco -->
                    <?php foreach (array_slice($posts, 3, 2) as $post): 
                        $title = $post['title']['rendered'];
                        $link = './noticias/' . $post['slug'] . '/';
                        $date = format_date_br($post['date']);
                        $image_url = '';
                        if (isset($post['_embedded']['wp:featuredmedia'][0]['source_url'])) {
                            $image_url = $post['_embedded']['wp:featuredmedia'][0]['source_url'];
                        } elseif (isset($post['yoast_head_json']['og_image'][0]['url'])) {
                            $image_url = $post['yoast_head_json']['og_image'][0]['url'];
                        }
                        // Se a URL da imagem ainda estiver vazia, tente extrair do conteúdo HTML
                        if (empty($image_url) && isset($post['content']['rendered'])) {
                            preg_match('/<img[^>]+src="([^"]+)"/i', $post['content']['rendered'], $matches);
                            if (isset($matches[1])) {
                                $image_url = $matches[1];
                            }
                        }
                        // Padroniza o caminho da imagem para uploads/2025/06/
                        $image_url = preg_replace('/uploads\/\\d{4}\\/\\d{2}\\//', 'uploads/2025/06/', $image_url);
                    ?>
                        <div class="card">
                            <a href="<?php echo $link; ?>">
                                <img class="img-fluid" src="<?php echo $image_url; ?>" alt="<?php echo $title; ?>">
                            </a>
                            <div class="card-body">
                                <div class="news-title">
                                    <h3 class=" title-small">
                                        <a href="<?php echo $link; ?>"><?php echo $title; ?></a>
                                    </h3>
                                </div>
                                <div class="time-text">
                                    <strong><?php echo $date; ?></strong>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-md-3"> <!-- Segundo Bloco -->
                    <?php foreach (array_slice($posts, 5, 2) as $post): 
                        $title = $post['title']['rendered'];
                        $link = './noticias/' . $post['slug'] . '/';
                        $date = format_date_br($post['date']);
                        $image_url = '';
                        if (isset($post['_embedded']['wp:featuredmedia'][0]['source_url'])) {
                            $image_url = $post['_embedded']['wp:featuredmedia'][0]['source_url'];
                        } elseif (isset($post['yoast_head_json']['og_image'][0]['url'])) {
                            $image_url = $post['yoast_head_json']['og_image'][0]['url'];
                        }
                        // Se a URL da imagem ainda estiver vazia, tente extrair do conteúdo HTML
                        if (empty($image_url) && isset($post['content']['rendered'])) {
                            preg_match('/<img[^>]+src="([^"]+)"/i', $post['content']['rendered'], $matches);
                            if (isset($matches[1])) {
                                $image_url = $matches[1];
                            }
                        }
                       // Ajusta dinamicamente o caminho da imagem conforme a data de publicação
                        if (isset($post['date'])) {
                            $timestamp = strtotime($post['date']);
                            $year  = date('Y', $timestamp);
                            $month = date('m', $timestamp);

                            $image_url = preg_replace(
                                '/uploads\/\d{4}\/\d{2}\//',
                                "uploads/{$year}/{$month}/",
                                $image_url
                            );
                        }
                    ?>
                        <div class="card">
                            <a href="<?php echo $link; ?>">
                                <img class="img-fluid" src="<?php echo $image_url; ?>" alt="<?php echo $title; ?>">
                            </a>
                            <div class="card-body">
                                <div class="news-title">
                                    <h3 class=" title-small">
                                        <a href="<?php echo $link; ?>"><?php echo $title; ?></a>
                                    </h3>
                                </div>
                                <div class="time-text">
                                    <strong><?php echo $date; ?></strong>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="col-12 text-center mt-5">
                    <p>Nenhuma notícia encontrada no momento.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
