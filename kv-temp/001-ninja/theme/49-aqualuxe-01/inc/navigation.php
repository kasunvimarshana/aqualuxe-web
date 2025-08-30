<?php
/**
 * Navigation related functions
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Register navigation menus
 */
function aqualuxe_register_menus() {
    register_nav_menus(
        array(
            'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
            'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
            'mobile'    => esc_html__( 'Mobile Menu', 'aqualuxe' ),
            'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
        )
    );
}
add_action( 'after_setup_theme', 'aqualuxe_register_menus' );

/**
 * Include custom walker classes
 */
require get_template_directory() . '/inc/classes/class-aqualuxe-walker-nav-menu.php';
require get_template_directory() . '/inc/classes/class-aqualuxe-walker-mobile-menu.php';

/**
 * Fallback for primary menu when no menu is assigned
 */
function aqualuxe_primary_menu_fallback() {
    if ( current_user_can( 'edit_theme_options' ) ) {
        echo '<ul class="primary-menu flex flex-wrap lg:flex-nowrap items-center">';
        echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '" class="menu-link block py-2 px-4 text-gray-800 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400 transition duration-200">' . esc_html__( 'Add a Primary Menu', 'aqualuxe' ) . '</a></li>';
        echo '</ul>';
    }
}

/**
 * Fallback for mobile menu when no menu is assigned
 */
function aqualuxe_mobile_menu_fallback() {
    if ( current_user_can( 'edit_theme_options' ) ) {
        echo '<ul class="mobile-menu-items space-y-2">';
        echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '" class="mobile-menu-link block py-3 text-gray-800 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400 transition duration-200">' . esc_html__( 'Add a Mobile Menu', 'aqualuxe' ) . '</a></li>';
        echo '</ul>';
    }
}

/**
 * Add a button to the mobile menu toggle for accessibility
 */
function aqualuxe_mobile_menu_toggle() {
    ?>
    <button id="mobile-menu-toggle" class="mobile-menu-toggle p-2 lg:hidden" aria-controls="mobile-menu" aria-expanded="false">
        <span class="sr-only"><?php esc_html_e( 'Toggle Mobile Menu', 'aqualuxe' ); ?></span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
    <?php
}

/**
 * Add a class to menu items with children
 *
 * @param array $classes The CSS classes applied to the menu item's <li> element.
 * @param WP_Post $item The current menu item.
 * @param stdClass $args An object of wp_nav_menu() arguments.
 * @param int $depth Depth of menu item.
 * @return array Modified CSS classes.
 */
function aqualuxe_nav_menu_css_class( $classes, $item, $args, $depth ) {
    if ( in_array( 'current-menu-item', $classes, true ) ) {
        $classes[] = 'active';
    }
    
    return $classes;
}
add_filter( 'nav_menu_css_class', 'aqualuxe_nav_menu_css_class', 10, 4 );

/**
 * Add JavaScript for mobile menu functionality
 */
function aqualuxe_mobile_menu_scripts() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        const closeMobileMenu = document.getElementById('close-mobile-menu');
        
        if (mobileMenuToggle && mobileMenu && mobileMenuOverlay && closeMobileMenu) {
            mobileMenuToggle.addEventListener('click', function() {
                mobileMenu.classList.toggle('translate-x-full');
                mobileMenuOverlay.classList.toggle('hidden');
                document.body.classList.toggle('overflow-hidden');
            });
            
            closeMobileMenu.addEventListener('click', function() {
                mobileMenu.classList.add('translate-x-full');
                mobileMenuOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
            
            mobileMenuOverlay.addEventListener('click', function() {
                mobileMenu.classList.add('translate-x-full');
                mobileMenuOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
            
            // Mobile submenu toggles
            const submenuToggles = document.querySelectorAll('.mobile-submenu-toggle');
            
            submenuToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function() {
                    const expanded = toggle.getAttribute('aria-expanded') === 'true' || false;
                    toggle.setAttribute('aria-expanded', !expanded);
                    
                    // Toggle the submenu visibility
                    const submenu = toggle.closest('li').querySelector('.sub-menu');
                    if (submenu) {
                        submenu.classList.toggle('hidden');
                        toggle.querySelector('svg').classList.toggle('rotate-180');
                    }
                });
            });
        }
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'aqualuxe_mobile_menu_scripts' );