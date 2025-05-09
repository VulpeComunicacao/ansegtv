    <footer id="colophon" class="site-footer">
        <div class="container">
            <div class="footer-widgets">
                <div class="row">
                    <div class="col-md-4">
                        <?php if (is_active_sidebar('footer-1')) : ?>
                            <div class="footer-widget-area">
                                <?php dynamic_sidebar('footer-1'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <?php if (is_active_sidebar('footer-2')) : ?>
                            <div class="footer-widget-area">
                                <?php dynamic_sidebar('footer-2'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4">
                        <?php if (is_active_sidebar('footer-3')) : ?>
                            <div class="footer-widget-area">
                                <?php dynamic_sidebar('footer-3'); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <nav class="footer-navigation">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'container_class' => 'footer-menu-container',
                    'menu_class'     => 'footer-menu',
                    'depth'          => 1,
                ));
                ?>
            </nav>

            <div class="site-info">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('Todos os direitos reservados.', 'ansegtv'); ?></p>
            </div><!-- .site-info -->
        </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html> 