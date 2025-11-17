<?php
/**
 * Template part for displaying mobile navigation
 *
 * @package AquaLuxe
 */

?>

<div class="mobile-navigation lg:hidden flex items-center space-x-4">
    <!-- Dark Mode Toggle (Mobile) -->
    <?php aqualuxe_get_template_part('components/dark-mode-toggle'); ?>
    
    <!-- Language Switcher (Mobile) -->
    <?php if (function_exists('pll_the_languages')) : ?>
        <div class="language-switcher">
            <?php 
            pll_the_languages(array(
                'dropdown' => 1,
                'show_names' => 0,
                'show_flags' => 1,
                'hide_current' => 0,
                'raw' => 0,
            )); 
            ?>
        </div>
    <?php endif; ?>
    
    <?php if (aqualuxe_is_woocommerce_active()) : ?>
        <!-- Cart (Mobile) -->
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" 
           class="cart-link relative p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200"
           aria-label="<?php esc_attr_e('Shopping cart', 'aqualuxe'); ?>">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5L21 21"></path>
            </svg>
            
            <?php if (WC()->cart && WC()->cart->get_cart_contents_count() > 0) : ?>
                <span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                    <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
                </span>
            <?php endif; ?>
        </a>
    <?php endif; ?>
    
    <!-- Mobile Menu Toggle -->
    <button 
        id="mobile-menu-toggle" 
        class="mobile-menu-toggle p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" 
        aria-label="<?php esc_attr_e('Toggle mobile menu', 'aqualuxe'); ?>"
        aria-expanded="false"
        aria-controls="mobile-menu"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>
</div>

<!-- Mobile Menu Overlay -->
<div 
    id="mobile-menu-overlay" 
    class="mobile-menu-overlay fixed inset-0 bg-black bg-opacity-50 z-40 hidden transition-opacity duration-300"
    aria-hidden="true"
></div>

<!-- Mobile Menu Panel -->
<nav 
    id="mobile-menu" 
    class="mobile-menu fixed top-0 right-0 h-full w-80 max-w-full bg-white dark:bg-gray-900 z-50 transform translate-x-full transition-transform duration-300 ease-in-out shadow-xl"
    role="navigation"
    aria-label="<?php esc_attr_e('Mobile Menu', 'aqualuxe'); ?>"
    aria-hidden="true"
>
    <div class="mobile-menu-header flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
            <?php esc_html_e('Menu', 'aqualuxe'); ?>
        </h2>
        <button 
            id="mobile-menu-close" 
            class="mobile-menu-close p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors duration-200"
            aria-label="<?php esc_attr_e('Close menu', 'aqualuxe'); ?>"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    
    <div class="mobile-menu-body p-4 overflow-y-auto">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'mobile',
            'menu_id'        => 'mobile-menu-list',
            'menu_class'     => 'mobile-menu-list space-y-2',
            'container'      => false,
            'depth'          => 3,
            'walker'         => new AquaLuxe_Walker_Mobile_Nav_Menu(),
            'fallback_cb'    => 'aqualuxe_mobile_menu_fallback',
        ));
        ?>
        
        <?php if (aqualuxe_is_woocommerce_active()) : ?>
            <div class="mobile-wc-links mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-y-4">
                <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" 
                   class="flex items-center text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <?php esc_html_e('My Account', 'aqualuxe'); ?>
                </a>
                
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                   class="flex items-center text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <?php esc_html_e('Shop', 'aqualuxe'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>