<?php
/**
 * Padrões de blocos personalizados
 */

if (!function_exists('ansegtv_register_block_patterns')) :
    function ansegtv_register_block_patterns() {
        if (function_exists('register_block_pattern')) {
            // Hero Section
            register_block_pattern(
                'ansegtv/hero-section',
                array(
                    'title' => __('Seção Hero', 'ansegtv'),
                    'description' => __('Uma seção hero com título, subtítulo e botão.', 'ansegtv'),
                    'categories' => array('header'),
                    'content' => '<!-- wp:group {"className":"hero-section","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group hero-section">
                        <!-- wp:cover {"url":"' . get_template_directory_uri() . '/assets/img/hero-bg.jpg","dimRatio":50,"minHeight":600,"className":"hero-cover"} -->
                        <div class="wp-block-cover hero-cover" style="min-height:600px">
                            <span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span>
                            <img class="wp-block-cover__image-background" alt="" src="' . get_template_directory_uri() . '/assets/img/hero-bg.jpg" data-object-fit="cover"/>
                            <div class="wp-block-cover__inner-container">
                                <!-- wp:heading {"textAlign":"center","level":1,"textColor":"white"} -->
                                <h1 class="wp-block-heading has-text-align-center has-white-color has-text-color">Bem-vindo à ANSEGTV</h1>
                                <!-- /wp:heading -->

                                <!-- wp:paragraph {"align":"center","textColor":"white"} -->
                                <p class="has-text-align-center has-white-color has-text-color">Sua fonte de notícias e entretenimento</p>
                                <!-- /wp:paragraph -->

                                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                                <div class="wp-block-buttons">
                                    <!-- wp:button {"backgroundColor":"primary","textColor":"white"} -->
                                    <div class="wp-block-button">
                                        <a class="wp-block-button__link has-white-color has-primary-background-color has-text-color has-background wp-element-button">Assista Agora</a>
                                    </div>
                                    <!-- /wp:button -->
                                </div>
                                <!-- /wp:buttons -->
                            </div>
                        </div>
                        <!-- /wp:cover -->
                    </div>
                    <!-- /wp:group -->'
                )
            );

            // Featured Section
            register_block_pattern(
                'ansegtv/featured-section',
                array(
                    'title' => __('Seção de Destaques', 'ansegtv'),
                    'description' => __('Uma seção com posts em destaque.', 'ansegtv'),
                    'categories' => array('query'),
                    'content' => '<!-- wp:group {"className":"featured-section","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group featured-section">
                        <!-- wp:heading {"textAlign":"center"} -->
                        <h2 class="wp-block-heading has-text-align-center">Destaques</h2>
                        <!-- /wp:heading -->

                        <!-- wp:query {"queryId":1,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","sticky":"","inherit":true},"displayLayout":{"type":"flex","columns":3}} -->
                        <div class="wp-block-query">
                            <!-- wp:post-template -->
                            <!-- wp:post-featured-image {"isLink":true} /-->

                            <!-- wp:post-title {"isLink":true} /-->

                            <!-- wp:post-excerpt /-->

                            <!-- wp:post-date /-->
                            <!-- /wp:post-template -->
                        </div>
                        <!-- /wp:query -->
                    </div>
                    <!-- /wp:group -->'
                )
            );

            // Newsletter Section
            register_block_pattern(
                'ansegtv/newsletter-section',
                array(
                    'title' => __('Seção de Newsletter', 'ansegtv'),
                    'description' => __('Uma seção para inscrição na newsletter.', 'ansegtv'),
                    'categories' => array('call-to-action'),
                    'content' => '<!-- wp:group {"className":"newsletter-section","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group newsletter-section">
                        <!-- wp:heading {"textAlign":"center"} -->
                        <h2 class="wp-block-heading has-text-align-center">Receba Nossas Atualizações</h2>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"align":"center"} -->
                        <p class="has-text-align-center">Inscreva-se para receber as últimas notícias e atualizações diretamente no seu e-mail.</p>
                        <!-- /wp:paragraph -->

                        <!-- wp:shortcode -->
                        [contact-form-7 id="123" title="Newsletter"]
                        <!-- /wp:shortcode -->
                    </div>
                    <!-- /wp:group -->'
                )
            );

            // Team Section
            register_block_pattern(
                'ansegtv/team-section',
                array(
                    'title' => __('Seção da Equipe', 'ansegtv'),
                    'description' => __('Uma seção para exibir membros da equipe.', 'ansegtv'),
                    'categories' => array('team'),
                    'content' => '<!-- wp:group {"className":"team-section","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group team-section">
                        <!-- wp:heading {"textAlign":"center"} -->
                        <h2 class="wp-block-heading has-text-align-center">Nossa Equipe</h2>
                        <!-- /wp:heading -->

                        <!-- wp:query {"queryId":2,"query":{"perPage":4,"pages":0,"offset":0,"postType":"team","order":"asc","orderBy":"menu_order","author":"","search":"","sticky":"","inherit":false},"displayLayout":{"type":"flex","columns":4}} -->
                        <div class="wp-block-query">
                            <!-- wp:post-template -->
                            <!-- wp:post-featured-image {"isLink":true} /-->

                            <!-- wp:post-title {"isLink":true} /-->

                            <!-- wp:post-excerpt /-->
                            <!-- /wp:post-template -->
                        </div>
                        <!-- /wp:query -->
                    </div>
                    <!-- /wp:group -->'
                )
            );
        }
    }
endif;
add_action('init', 'ansegtv_register_block_patterns'); 