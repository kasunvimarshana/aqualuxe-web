<?php
/**
 * WooCommerce Fallback Templates
 *
 * Functions to handle fallback templates when WooCommerce is not active
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if current page is a WooCommerce page by slug
 *
 * @return bool True if current page is a WooCommerce page, false otherwise
 */
function aqualuxe_is_woocommerce_page_by_slug() {
    global $post;
    
    if (!$post) {
        return false;
    }
    
    // Common WooCommerce page slugs
    $woocommerce_slugs = array(
        'shop',
        'cart',
        'checkout',
        'my-account',
        'product',
        'products',
        'store'
    );
    
    // Check if current page slug matches any WooCommerce slug
    if (in_array($post->post_name, $woocommerce_slugs)) {
        return true;
    }
    
    // Check if page title contains WooCommerce keywords
    $woocommerce_keywords = array(
        'shop',
        'cart',
        'checkout',
        'account',
        'store',
        'products'
    );
    
    foreach ($woocommerce_keywords as $keyword) {
        if (stripos($post->post_title, $keyword) !== false) {
            return true;
        }
    }
    
    return false;
}

/**
 * Load appropriate fallback template based on page type
 *
 * @return void
 */
function aqualuxe_load_woocommerce_fallback_template() {
    // Only load fallback if WooCommerce is not active
    if (function_exists('aqualuxe_is_woocommerce_active') && aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $post;
    
    if (!$post) {
        return;
    }
    
    // Determine which fallback template to load
    $template_path = '';
    
    if ($post->post_name === 'shop' || $post->post_title === 'Shop') {
        $template_path = 'template-parts/woocommerce/fallback-shop.php';
    } elseif ($post->post_name === 'cart' || $post->post_title === 'Cart') {
        $template_path = 'template-parts/woocommerce/fallback-cart.php';
    } elseif ($post->post_name === 'checkout' || $post->post_title === 'Checkout') {
        $template_path = 'template-parts/woocommerce/fallback-checkout.php';
    } elseif ($post->post_name === 'my-account' || $post->post_title === 'My Account') {
        $template_path = 'template-parts/woocommerce/fallback-account.php';
    } elseif ($post->post_type === 'product') {
        $template_path = 'template-parts/woocommerce/fallback-product.php';
    } elseif (aqualuxe_is_woocommerce_page_by_slug()) {
        $template_path = 'template-parts/woocommerce/fallback-shop.php';
    }
    
    // Load the fallback template if found
    if (!empty($template_path) && file_exists(get_template_directory() . '/' . $template_path)) {
        include(get_template_directory() . '/' . $template_path);
        exit; // Stop further execution
    }
}

/**
 * Hook into template_redirect to check for WooCommerce pages
 */
function aqualuxe_check_woocommerce_pages() {
    // Only check if WooCommerce is not active
    if (function_exists('aqualuxe_is_woocommerce_active') && !aqualuxe_is_woocommerce_active()) {
        // Check if current page is a WooCommerce page
        if (aqualuxe_is_woocommerce_page_by_slug()) {
            add_action('template_include', 'aqualuxe_woocommerce_fallback_template_include');
        }
    }
}
add_action('template_redirect', 'aqualuxe_check_woocommerce_pages');

/**
 * Include fallback template
 *
 * @param string $template Original template path
 * @return string Modified template path
 */
function aqualuxe_woocommerce_fallback_template_include($template) {
    // Get the template name
    $template_name = basename($template);
    
    // Create a custom template path
    $custom_template = get_template_directory() . '/page.php';
    
    // Check if the custom template exists
    if (file_exists($custom_template)) {
        // Set a flag to load fallback content
        set_query_var('load_woocommerce_fallback', true);
        return $custom_template;
    }
    
    return $template;
}

/**
 * Display fallback content in page template
 */
function aqualuxe_display_woocommerce_fallback() {
    // Check if we should load fallback content
    if (get_query_var('load_woocommerce_fallback', false)) {
        aqualuxe_load_woocommerce_fallback_template();
    }
}
add_action('aqualuxe_before_page_content', 'aqualuxe_display_woocommerce_fallback');