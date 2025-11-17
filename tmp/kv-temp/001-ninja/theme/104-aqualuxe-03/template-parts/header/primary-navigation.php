<?php
/**
 * Template part for displaying primary navigation
 *
 * @package AquaLuxe
 */

?>

<nav id="primary-navigation" class="primary-navigation hidden lg:flex items-center space-x-8" role="navigation" aria-label="<?php esc_attr_e('Primary Menu', 'aqualuxe'); ?>">
    <?php
    wp_nav_menu(array(
        'theme_location' => 'primary',
        'menu_id'        => 'primary-menu',
        'menu_class'     => 'primary-menu flex items-center space-x-6',
        'container'      => false,
        'depth'          => 3,
        'walker'         => new AquaLuxe_Walker_Nav_Menu(),
        'fallback_cb'    => 'aqualuxe_primary_menu_fallback',
    ));
    ?>
    
    <!-- Search Toggle -->
    <button 
        id="search-toggle" 
        class="search-toggle p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" 
        aria-label="<?php esc_attr_e('Toggle search', 'aqualuxe'); ?>"
        aria-expanded="false"
        aria-controls="search-modal"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </button>
    
    <?php if (aqualuxe_is_woocommerce_active()) : ?>
        <!-- WooCommerce Account & Cart -->
        <div class="flex items-center space-x-4">
            <!-- Account -->
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" 
               class="account-link p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200"
               aria-label="<?php esc_attr_e('My Account', 'aqualuxe'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </a>
            
            <!-- Cart -->
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" 
               class="cart-link relative p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200"
               aria-label="<?php esc_attr_e('Shopping cart', 'aqualuxe'); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5L21 21"></path>
                </svg>
                
                <?php if (WC()->cart && WC()->cart->get_cart_contents_count() > 0) : ?>
                    <span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        <?php echo esc_html(WC()->cart->get_cart_contents_count()); ?>
                    </span>
                <?php endif; ?>
            </a>
        </div>
    <?php endif; ?>
</nav>