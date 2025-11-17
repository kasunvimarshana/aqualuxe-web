<?php
/**
 * Conditional tag functions
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Check if the current page is a WooCommerce page
 *
 * @return bool True if the current page is a WooCommerce page
 */
function aqualuxe_is_woocommerce_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    return (
        is_woocommerce() ||
        is_cart() ||
        is_checkout() ||
        is_account_page() ||
        is_wc_endpoint_url()
    );
}

/**
 * Check if the current page is the homepage
 *
 * @return bool True if the current page is the homepage
 */
function aqualuxe_is_homepage() {
    return is_front_page() && is_home();
}

/**
 * Check if the current page is the static front page
 *
 * @return bool True if the current page is the static front page
 */
function aqualuxe_is_static_front_page() {
    return is_front_page() && ! is_home();
}

/**
 * Check if the current page is the blog page
 *
 * @return bool True if the current page is the blog page
 */
function aqualuxe_is_blog_page() {
    return ! is_front_page() && is_home();
}

/**
 * Check if the current page should display the page title
 *
 * @return bool True if the page title should be displayed
 */
function aqualuxe_display_page_title() {
    // Don't show on the homepage
    if ( aqualuxe_is_homepage() || aqualuxe_is_static_front_page() ) {
        return false;
    }
    
    // Always show on archives and search results
    if ( is_archive() || is_search() ) {
        return true;
    }
    
    // Check for WooCommerce pages
    if ( aqualuxe_is_woocommerce_active() ) {
        if ( is_shop() || is_product_category() || is_product_tag() ) {
            return true;
        }
        
        // Don't show on single product pages
        if ( is_product() ) {
            return false;
        }
    }
    
    // For other pages, check if the page has a featured image
    if ( is_page() && has_post_thumbnail() ) {
        return false;
    }
    
    // Default to true
    return true;
}

/**
 * Check if the current page should display the sidebar
 *
 * @return bool True if the sidebar should be displayed
 */
function aqualuxe_display_sidebar() {
    // Don't show on the homepage
    if ( aqualuxe_is_homepage() || aqualuxe_is_static_front_page() ) {
        return false;
    }
    
    // Don't show on full-width page template
    if ( is_page_template( 'templates/full-width.php' ) ) {
        return false;
    }
    
    // Check for WooCommerce pages
    if ( aqualuxe_is_woocommerce_active() ) {
        // Show on shop and product archive pages
        if ( is_shop() || is_product_category() || is_product_tag() ) {
            return true;
        }
        
        // Don't show on single product, cart, checkout, and account pages
        if ( is_product() || is_cart() || is_checkout() || is_account_page() ) {
            return false;
        }
    }
    
    // Show on blog and archive pages
    if ( is_home() || is_archive() || is_search() ) {
        return true;
    }
    
    // Show on single posts
    if ( is_singular( 'post' ) ) {
        return true;
    }
    
    // Default to false
    return false;
}

/**
 * Check if the current page should display the breadcrumbs
 *
 * @return bool True if the breadcrumbs should be displayed
 */
function aqualuxe_display_breadcrumbs() {
    // Don't show on the homepage
    if ( aqualuxe_is_homepage() || aqualuxe_is_static_front_page() ) {
        return false;
    }
    
    // Show on all other pages
    return true;
}

/**
 * Check if the current page is a product archive
 *
 * @return bool True if the current page is a product archive
 */
function aqualuxe_is_product_archive() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    return is_shop() || is_product_category() || is_product_tag();
}

/**
 * Check if the current page is a blog archive
 *
 * @return bool True if the current page is a blog archive
 */
function aqualuxe_is_blog_archive() {
    return is_home() || is_category() || is_tag() || is_author() || is_date();
}

/**
 * Check if the current page has a hero section
 *
 * @return bool True if the current page has a hero section
 */
function aqualuxe_has_hero() {
    // Show on homepage
    if ( aqualuxe_is_homepage() || aqualuxe_is_static_front_page() ) {
        return true;
    }
    
    // Show on pages with featured images
    if ( is_page() && has_post_thumbnail() ) {
        return true;
    }
    
    // Default to false
    return false;
}

/**
 * Check if the current page should display related products
 *
 * @return bool True if related products should be displayed
 */
function aqualuxe_display_related_products() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    return is_product();
}

/**
 * Check if the current page should display upsells
 *
 * @return bool True if upsells should be displayed
 */
function aqualuxe_display_upsells() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    return is_product();
}

/**
 * Check if the current page should display cross-sells
 *
 * @return bool True if cross-sells should be displayed
 */
function aqualuxe_display_cross_sells() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    return is_cart();
}

/**
 * Check if the current page is a checkout page
 *
 * @return bool True if the current page is a checkout page
 */
function aqualuxe_is_checkout_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    return is_checkout() || is_wc_endpoint_url( 'order-received' );
}

/**
 * Check if the current page is a cart page
 *
 * @return bool True if the current page is a cart page
 */
function aqualuxe_is_cart_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    return is_cart();
}

/**
 * Check if the current page is an account page
 *
 * @return bool True if the current page is an account page
 */
function aqualuxe_is_account_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    return is_account_page();
}

/**
 * Check if the current page is a product page
 *
 * @return bool True if the current page is a product page
 */
function aqualuxe_is_product_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    return is_product();
}

/**
 * Check if the current page is a shop page
 *
 * @return bool True if the current page is a shop page
 */
function aqualuxe_is_shop_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    return is_shop();
}

/**
 * Check if the current page is a product category page
 *
 * @return bool True if the current page is a product category page
 */
function aqualuxe_is_product_category_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    return is_product_category();
}

/**
 * Check if the current page is a product tag page
 *
 * @return bool True if the current page is a product tag page
 */
function aqualuxe_is_product_tag_page() {
    if ( ! aqualuxe_is_woocommerce_active() ) {
        return false;
    }
    
    return is_product_tag();
}