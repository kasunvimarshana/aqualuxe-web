<?php
/**
 * The template for displaying the footer
 *
 * @package AquaLuxe
 */
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container">
            <div class="footer-widgets">
                <div class="footer-widget-area">
                    <?php if (is_active_sidebar('aqualuxe-footer-1')) : ?>
                        <?php dynamic_sidebar('aqualuxe-footer-1'); ?>
                    <?php endif; ?>
                </div>
                
                <div class="footer-widget-area">
                    <?php if (is_active_sidebar('aqualuxe-footer-2')) : ?>
                        <?php dynamic_sidebar('aqualuxe-footer-2'); ?>
                    <?php endif; ?>
                </div>
                
                <div class="footer-widget-area">
                    <div class="widget">
                        <h4 class="widget-title"><?php _e('Contact Info', 'aqualuxe-child'); ?></h4>
                        <div class="contact-info">
                            <p><strong><?php _e('Email:', 'aqualuxe-child'); ?></strong> info@aqualuxe.com</p>
                            <p><strong><?php _e('Phone:', 'aqualuxe-child'); ?></strong> +1 (555) 123-4567</p>
                            <p><strong><?php _e('Address:', 'aqualuxe-child'); ?></strong> 123 Aquatic Street, Fish City, FC 12345</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="site-info">
                <div class="copyright">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'aqualuxe-child'); ?></p>
                </div>
                
                <?php if (has_nav_menu('footer')) : ?>
                    <nav class="footer-navigation">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'footer',
                            'menu_class' => 'footer-menu',
                            'depth' => 1,
                        ]);
                        ?>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
