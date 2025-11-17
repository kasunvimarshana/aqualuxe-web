<?php
/**
 * Template part for displaying primary navigation
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<nav class="main-navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'aqualuxe'); ?>" role="navigation">
    <div class="hidden lg:block">
        <?php
        if (has_nav_menu('primary')) {
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'menu_class'     => 'primary-menu flex items-center space-x-8',
                'container'      => false,
                'depth'          => 2,
                'walker'         => new AquaLuxe_Walker_Nav_Menu(),
            ));
        }
        ?>
    </div>
    
    <!-- Mobile menu button -->
    <div class="lg:hidden">
        <button type="button" 
                class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 transition-colors"
                aria-controls="mobile-menu" 
                aria-expanded="false"
                aria-label="<?php esc_attr_e('Toggle navigation menu', 'aqualuxe'); ?>">
            <span class="sr-only"><?php esc_html_e('Open main menu', 'aqualuxe'); ?></span>
            <!-- Hamburger icon -->
            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <!-- Close icon (hidden by default) -->
            <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</nav>

<!-- Mobile navigation menu -->
<div class="mobile-menu lg:hidden hidden" id="mobile-menu">
    <div class="px-2 pt-2 pb-3 space-y-1 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
        <?php
        if (has_nav_menu('mobile')) {
            wp_nav_menu(array(
                'theme_location' => 'mobile',
                'menu_id'        => 'mobile-menu-list',
                'menu_class'     => 'mobile-menu-list space-y-2',
                'container'      => false,
                'depth'          => 2,
                'walker'         => new AquaLuxe_Walker_Nav_Menu(),
            ));
        } elseif (has_nav_menu('primary')) {
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id'        => 'mobile-menu-list',
                'menu_class'     => 'mobile-menu-list space-y-2',
                'container'      => false,
                'depth'          => 2,
                'walker'         => new AquaLuxe_Walker_Nav_Menu(),
            ));
        }
        ?>
    </div>
</div>