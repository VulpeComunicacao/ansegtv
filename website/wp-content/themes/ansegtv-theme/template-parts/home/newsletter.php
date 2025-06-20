<?php
/**
 * Template para a seção de newsletter da página inicial
 */

$newsletter_title = get_theme_mod('newsletter_title', 'Receba Nossas Atualizações');
$newsletter_subtitle = get_theme_mod('newsletter_subtitle', 'Inscreva-se para receber as últimas notícias e atualizações diretamente no seu e-mail.');
$newsletter_shortcode = get_theme_mod('newsletter_shortcode', '[contact-form-7 id="123" title="Newsletter"]');
?>

<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content text-center animate-on-scroll">
            <h2 class="newsletter-title"><?php echo esc_html($newsletter_title); ?></h2>
            <p class="newsletter-subtitle"><?php echo esc_html($newsletter_subtitle); ?></p>
            
            <div class="newsletter-form">
                <?php echo do_shortcode($newsletter_shortcode); ?>
            </div>
            
            <div class="newsletter-privacy">
                <p>
                    <?php esc_html_e('Ao se inscrever, você concorda com nossa', 'ansegtv'); ?>
                    <a href="<?php echo esc_url(get_privacy_policy_url()); ?>">
                        <?php esc_html_e('Política de Privacidade', 'ansegtv'); ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
</section> 