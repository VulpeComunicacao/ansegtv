<?php
/**
 * Template para a seção hero da página inicial
 */

$hero_title = get_theme_mod('hero_title', 'Bem-vindo à ANSEGTV');
$hero_subtitle = get_theme_mod('hero_subtitle', 'Sua fonte de notícias e entretenimento');
$hero_button_text = get_theme_mod('hero_button_text', 'Assista Agora');
$hero_button_url = get_theme_mod('hero_button_url', '#');
$hero_background = get_theme_mod('hero_background', get_template_directory_uri() . '/assets/img/hero-bg.jpg');
?>

<section class="hero-section" style="background-image: url('<?php echo esc_url($hero_background); ?>');">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title animate-on-scroll"><?php echo esc_html($hero_title); ?></h1>
            <p class="hero-subtitle animate-on-scroll delay-200"><?php echo esc_html($hero_subtitle); ?></p>
            <a href="<?php echo esc_url($hero_button_url); ?>" class="btn btn-primary btn-lg animate-on-scroll delay-400">
                <?php echo esc_html($hero_button_text); ?>
            </a>
        </div>
    </div>
</section> 