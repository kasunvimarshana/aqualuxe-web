<?php
/**
 * WooCommerce Helper Functions
 *
 * Functions to help with WooCommerce integration and provide fallbacks when WooCommerce is not active.
 *
 * @package AquaLuxe
 */

/**
 * Check if WooCommerce is active
 *
 * @return bool True if WooCommerce is active, false otherwise
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Safe way to check if current page is a WooCommerce page
 *
 * @return bool True if current page is a WooCommerce page, false otherwise
 */
function aqualuxe_is_woocommerce_page() {
    if (!aqualuxe_is_woocommerce_active()) {
        return false;
    }
    
    return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
}

/**
 * Get cart URL safely (with fallback)
 *
 * @return string Cart URL or shop page URL as fallback
 */
function aqualuxe_get_cart_url() {
    if (aqualuxe_is_woocommerce_active()) {
        return wc_get_cart_url();
    }
    
    return get_permalink(get_option('page_for_posts'));
}

/**
 * Get account URL safely (with fallback)
 *
 * @return string Account URL or home URL as fallback
 */
function aqualuxe_get_account_url() {
    if (aqualuxe_is_woocommerce_active()) {
        return get_permalink(get_option('woocommerce_myaccount_page_id'));
    }
    
    return home_url('/');
}

/**
 * Get cart count safely (with fallback)
 *
 * @return int Cart count or 0 as fallback
 */
function aqualuxe_get_cart_count() {
    if (aqualuxe_is_woocommerce_active()) {
        return WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    }
    
    return 0;
}

/**
 * Display cart icon with count
 *
 * @return void
 */
function aqualuxe_cart_icon() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    ?>
    <div class="cart-link">
        <a href="<?php echo esc_url(aqualuxe_get_cart_url()); ?>" class="text-white hover:text-blue-200 relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="cart-count absolute -top-2 -right-2 bg-blue-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                <?php echo esc_html(aqualuxe_get_cart_count()); ?>
            </span>
        </a>
    </div>
    <?php
}

/**
 * Display account icon
 *
 * @return void
 */
function aqualuxe_account_icon() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    ?>
    <div class="account-links">
        <a href="<?php echo esc_url(aqualuxe_get_account_url()); ?>" class="text-white hover:text-blue-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </a>
    </div>
    <?php
}