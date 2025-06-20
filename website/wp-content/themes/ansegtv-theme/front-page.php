<?php
/**
 * Template para a página inicial
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Seção Hero
    if (get_theme_mod('show_hero_section', true)) :
        get_template_part('template-parts/home/hero');
    endif;

    // Seção de Destaques
    if (get_theme_mod('show_featured_section', true)) :
        get_template_part('template-parts/home/featured');
    endif;

    // Seção de Últimas Notícias
    if (get_theme_mod('show_latest_news', true)) :
        get_template_part('template-parts/home/latest-news');
    endif;

    // Seção de Vídeos
    if (get_theme_mod('show_videos_section', true)) :
        get_template_part('template-parts/home/videos');
    endif;

    // Seção de Programação
    if (get_theme_mod('show_schedule_section', true)) :
        get_template_part('template-parts/home/schedule');
    endif;

    // Seção de Newsletter
    if (get_theme_mod('show_newsletter_section', true)) :
        get_template_part('template-parts/home/newsletter');
    endif;
    ?>
</main><!-- #main -->

<?php
get_footer(); 