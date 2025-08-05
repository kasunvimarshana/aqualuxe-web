<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer" role="contentinfo">
        
        <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
            <div class="footer-widgets">
                <div class="container">
                    <div class="footer-widgets-inner">
                        
                        <?php for ($i = 1; $i <= 4; $i++) : ?>
                            <?php if (is_active_sidebar('footer-' . $i)) : ?>
                                <div class="footer-widget-area footer-widget-<?php echo esc_attr($i); ?>">
                                    <?php dynamic_sidebar('footer-' . $i); ?>
                                </div>
                            <?php endif; ?>
                        <?php endfor; ?>
                        
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="site-info">
            <div class="container">
                <div class="site-info-inner">
                    
                    <div class="copyright">
                        <p>&copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. 
                           <?php esc_html_e('All rights reserved.', 'aqualuxe'); ?>
                        </p>
                    </div>
                    
                    <?php if (has_nav_menu('footer')) : ?>
                        <nav class="footer-navigation" role="navigation">
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'footer',
                                'menu_id'        => 'footer-menu',
                                'container'      => false,
                                'depth'          => 1,
                            ));
                            ?>
                        </nav>
                    <?php endif; ?>
                    
                    <div class="theme-credit">
                        <p><?php printf(esc_html__('Theme: %1$s by %2$s', 'aqualuxe'), 'AquaLuxe', '<a href="https://github.com/kasunvimarshana">Kasun Vimarshana</a>'); ?></p>
                    </div>
                    
                </div>
            </div>
        </div>
        
    </footer><!-- #colophon -->
    
</div><!-- #page -->

<!-- Back to Top Button -->
<button id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'aqualuxe'); ?>">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="18,15 12,9 6,15"></polyline>
    </svg>
</button>

<?php wp_footer(); ?>

</body>
</html>