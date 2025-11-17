<?php
/**
 * The template for displaying the footer
 *
 * @package AquaLuxe
 * @version 1.0.0
 */
?>

    <footer id="colophon" class="site-footer" role="contentinfo">
        <div class="container">
            
            <?php if (is_active_sidebar('footer-widgets')) : ?>
                <div class="footer-widgets">
                    <?php dynamic_sidebar('footer-widgets'); ?>
                </div>
            <?php endif; ?>
            
            <div class="footer-bottom">
                <div class="site-info">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', AquaLuxeTheme::TEXT_DOMAIN); ?></p>
                    <p><?php _e('Luxury ornamental fish for discerning aquarists worldwide.', AquaLuxeTheme::TEXT_DOMAIN); ?></p>
                </div>
                
                <?php aqualuxe_social_links(); ?>
                
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer',
                    'menu_class' => 'footer-menu',
                    'container' => 'nav',
                    'container_class' => 'footer-navigation',
                    'fallback_cb' => false,
                ]);
                ?>
            </div>
            
        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
