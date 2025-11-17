<?php
/**
 * The footer template
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */
?>

    </div><!-- #content -->
    
    <footer id="colophon" class="site-footer bg-primary-900 text-white">
        <div class="container mx-auto px-4 py-12">
            
            <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
                <div class="footer-widgets grid gap-8 md:grid-cols-2 lg:grid-cols-4 mb-8">
                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                        <?php if (is_active_sidebar('footer-' . $i)) : ?>
                            <div class="footer-widget-area">
                                <?php dynamic_sidebar('footer-' . $i); ?>
                            </div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
            
            <div class="footer-bottom border-t border-primary-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                    
                    <div class="footer-info">
                        <p class="text-sm text-primary-300">
                            &copy; <?php echo date('Y'); ?> 
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-white transition-colors">
                                <?php bloginfo('name'); ?>
                            </a>
                            <?php esc_html_e('- Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?>
                        </p>
                    </div>
                    
                    <?php if (has_nav_menu('footer')) : ?>
                        <nav class="footer-navigation" aria-label="<?php esc_attr_e('Footer Menu', 'aqualuxe'); ?>">
                            <?php
                            wp_nav_menu([
                                'theme_location' => 'footer',
                                'menu_class'     => 'footer-menu flex space-x-6 text-sm',
                                'container'      => false,
                                'depth'          => 1,
                            ]);
                            ?>
                        </nav>
                    <?php endif; ?>
                    
                </div>
            </div>
            
        </div>
        
        <div class="back-to-top">
            <button id="back-to-top" class="fixed bottom-4 right-4 bg-accent-500 hover:bg-accent-600 text-white p-3 rounded-full shadow-lg transition-all transform translate-y-16 opacity-0" aria-label="<?php esc_attr_e('Back to top', 'aqualuxe'); ?>">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                </svg>
            </button>
        </div>
    </footer>
    
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>