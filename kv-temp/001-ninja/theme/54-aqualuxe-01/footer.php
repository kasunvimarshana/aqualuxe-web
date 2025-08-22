<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

    </div><!-- #content -->

    <?php
    // Get footer layout
    $footer_layout = get_theme_mod('aqualuxe_footer_layout', 'four-column');
    $footer_copyright = get_theme_mod('aqualuxe_footer_copyright', sprintf(
        /* translators: %1$s: Current year, %2$s: Site name */
        esc_html__('© %1$s %2$s. All rights reserved.', 'aqualuxe'),
        date('Y'),
        get_bloginfo('name')
    ));
    ?>

    <footer id="colophon" class="site-footer bg-gray-100 dark:bg-gray-800 py-12">
        <div class="container mx-auto px-4">
            <?php
            // Load footer layout
            get_template_part('templates/footer/footer', $footer_layout);
            ?>

            <div class="site-info border-t border-gray-200 dark:border-gray-700 mt-8 pt-8 text-center">
                <?php echo wp_kses_post($footer_copyright); ?>
                
                <div class="footer-links mt-4">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'menu_id' => 'footer-menu',
                        'container' => false,
                        'menu_class' => 'flex flex-wrap justify-center',
                        'depth' => 1,
                        'fallback_cb' => false,
                    ]);
                    ?>
                </div>
            </div><!-- .site-info -->
        </div>
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php
// Back to top button
?>
<button id="back-to-top" class="back-to-top fixed bottom-8 right-8 z-50 bg-primary-600 text-white p-3 rounded-full shadow-lg opacity-0 transition-opacity duration-300">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
    </svg>
    <span class="sr-only"><?php esc_html_e('Back to top', 'aqualuxe'); ?></span>
</button>

<?php
// WooCommerce cart drawer
if (aqualuxe_is_woocommerce_active()) {
    get_template_part('templates/woocommerce/cart-drawer');
}

// Dark mode toggle
if (get_theme_mod('aqualuxe_enable_dark_mode', true)) {
    get_template_part('templates/dark-mode-toggle');
}

// Mobile menu
get_template_part('templates/mobile-menu');

wp_footer();
?>

</body>
</html>