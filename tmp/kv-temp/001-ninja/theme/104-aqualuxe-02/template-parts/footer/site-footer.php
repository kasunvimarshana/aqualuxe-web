<?php
/**
 * Template part for displaying site footer
 *
 * @package AquaLuxe
 */

?>

<div class="site-footer-bottom bg-gray-900 dark:bg-black py-6">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row justify-between items-center space-y-4 lg:space-y-0">
            
            <!-- Copyright -->
            <div class="copyright text-center lg:text-left">
                <p class="text-gray-400 text-sm">
                    <?php
                    /* translators: 1: Current year, 2: Site name */
                    printf(
                        esc_html__('&copy; %1$s %2$s. All rights reserved.', 'aqualuxe'),
                        date('Y'),
                        get_bloginfo('name')
                    );
                    ?>
                </p>
            </div>
            
            <!-- Footer Navigation -->
            <div class="footer-navigation">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'menu_class'     => 'footer-menu flex flex-wrap justify-center lg:justify-end space-x-6',
                    'container'      => false,
                    'depth'          => 1,
                    'fallback_cb'    => 'aqualuxe_footer_menu_fallback',
                ));
                ?>
            </div>
            
            <!-- Theme Credit -->
            <div class="theme-credit text-center lg:text-right">
                <p class="text-gray-500 text-xs">
                    <?php
                    /* translators: %s: Theme name */
                    printf(
                        esc_html__('Powered by %s', 'aqualuxe'),
                        '<a href="https://github.com/kasunvimarshana/aqualuxe" class="text-primary-400 hover:text-primary-300 transition-colors duration-200" target="_blank" rel="noopener">AquaLuxe</a>'
                    );
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>