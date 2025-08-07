
<?php
/**
 * AquaLuxe Template Hooks
 *
 * Action/filter hooks used for AquaLuxe theme functions/templates.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Header
 *
 * @see aqualuxe_header_top_bar()
 * @see aqualuxe_header_main()
 * @see aqualuxe_header_navigation()
 */
add_action('aqualuxe_header', 'aqualuxe_header_top_bar', 10);
add_action('aqualuxe_header', 'aqualuxe_header_main', 20);
add_action('aqualuxe_header', 'aqualuxe_header_navigation', 30);

/**
 * Footer
 *
 * @see aqualuxe_footer_widgets()
 * @see aqualuxe_footer_bottom()
 */
add_action('aqualuxe_footer', 'aqualuxe_footer_widgets', 10);
add_action('aqualuxe_footer', 'aqualuxe_footer_bottom', 20);

/**
 * Homepage
 *
 * @see aqualuxe_homepage_slider()
 * @see aqualuxe_homepage_features()
 * @see aqualuxe_homepage_products()
 * @see aqualuxe_homepage_categories()
 * @see aqualuxe_homepage_cta()
 * @see aqualuxe_homepage_testimonials()
 * @see aqualuxe_homepage_blog()
 * @see aqualuxe_homepage_brands()
 * @see aqualuxe_homepage_newsletter()
 */
add_action('aqualuxe_homepage', 'aqualuxe_homepage_slider', 10);
add_action('aqualuxe_homepage', 'aqualuxe_homepage_features', 20);
add_action('aqualuxe_homepage', 'aqualuxe_homepage_products', 30);
add_action('aqualuxe_homepage', 'aqualuxe_homepage_categories', 40);
add_action('aqualuxe_homepage', 'aqualuxe_homepage_cta', 50);
add_action('aqualuxe_homepage', 'aqualuxe_homepage_testimonials', 60);
add_action('aqualuxe_homepage', 'aqualuxe_homepage_blog', 70);
add_action('aqualuxe_homepage', 'aqualuxe_homepage_brands', 80);
add_action('aqualuxe_homepage', 'aqualuxe_homepage_newsletter', 90);

/**
 * Posts
 *
 * @see aqualuxe_post_header()
 * @see aqualuxe_post_meta()
 * @see aqualuxe_post_content()
 * @see aqualuxe_post_footer()
 * @see aqualuxe_post_navigation()
 * @see aqualuxe_post_author_bio()
 * @see aqualuxe_post_related()
 * @see aqualuxe_post_comments()
 */
add_action('aqualuxe_single_post', 'aqualuxe_post_header', 10);
add_action('aqualuxe_single_post', 'aqualuxe_post_meta', 20);
add_action('aqualuxe_single_post', 'aqualuxe_post_content', 30);
add_action('aqualuxe_single_post', 'aqualuxe_post_footer', 40);
add_action('aqualuxe_single_post', 'aqualuxe_post_navigation', 50);
add_action('aqualuxe_single_post', 'aqualuxe_post_author_bio', 60);
add_action('aqualuxe_single_post', 'aqualuxe_post_related', 70);
add_action('aqualuxe_single_post', 'aqualuxe_post_comments', 80);

/**
 * Pages
 *
 * @see aqualuxe_page_header()
 * @see aqualuxe_page_content()
 * @see aqualuxe_page_footer()
 * @see aqualuxe_page_comments()
 */
add_action('aqualuxe_page', 'aqualuxe_page_header', 10);
add_action('aqualuxe_page', 'aqualuxe_page_content', 20);
add_action('aqualuxe_page', 'aqualuxe_page_footer', 30);
add_action('aqualuxe_page', 'aqualuxe_page_comments', 40);

/**
 * Sidebar
 *
 * @see aqualuxe_get_sidebar()
 */
add_action('aqualuxe_sidebar', 'aqualuxe_get_sidebar', 10);

/**
 * WooCommerce
 */
if (class_exists('WooCommerce')) {
    /**
     * Product Loop
     *
     * @see aqualuxe_product_loop_start()
     * @see aqualuxe_product_loop_end()
     */
    add_action('woocommerce_before_shop_loop', 'aqualuxe_product_loop_start', 10);
    add_action('woocommerce_after_shop_loop', 'aqualuxe_product_loop_end', 10);
    
    /**
     * Product
     *
     * @see aqualuxe_product_wrapper_open()
     * @see aqualuxe_product_wrapper_close()
     * @see aqualuxe_product_labels()
     * @see aqualuxe_product_thumbnail()
     * @see aqualuxe_product_title()
     * @see aqualuxe_product_price()
     * @see aqualuxe_product_rating()
     * @see aqualuxe_product_excerpt()
     * @see aqualuxe_product_add_to_cart()
     * @see aqualuxe_product_quick_view()
     * @see aqualuxe_product_wishlist()
     * @see aqualuxe_product_compare()
     */
    add_action('woocommerce_before_shop_loop_item', 'aqualuxe_product_wrapper_open', 5);
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_product_wrapper_close', 50);
    add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_product_labels', 5);
    add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_product_thumbnail', 10);
    add_action('woocommerce_shop_loop_item_title', 'aqualuxe_product_title', 10);
    add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_product_price', 10);
    add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_product_rating', 15);
    add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_product_excerpt', 20);
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_product_add_to_cart', 10);
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_product_quick_view', 20);
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_product_wishlist', 30);
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_product_compare', 40);
    
    /**
     * Single Product
     *
     * @see aqualuxe_single_product_wrapper_open()
     * @see aqualuxe_single_product_wrapper_close()
     * @see aqualuxe_single_product_gallery()
     * @see aqualuxe_single_product_title()
     * @see aqualuxe_single_product_rating()
     * @see aqualuxe_single_product_price()
     * @see aqualuxe_single_product_excerpt()
     * @see aqualuxe_single_product_add_to_cart()
     * @see aqualuxe_single_product_meta()
     * @see aqualuxe_single_product_sharing()
     * @see aqualuxe_single_product_tabs()
     * @see aqualuxe_single_product_upsells()
     * @see aqualuxe_single_product_related()
     * @see aqualuxe_single_product_recently_viewed()
     */
    add_action('woocommerce_before_single_product', 'aqualuxe_single_product_wrapper_open', 5);
    add_action('woocommerce_after_single_product', 'aqualuxe_single_product_wrapper_close', 50);
    add_action('woocommerce_before_single_product_summary', 'aqualuxe_single_product_gallery', 20);
    add_action('woocommerce_single_product_summary', 'aqualuxe_single_product_title', 5);
    add_action('woocommerce_single_product_summary', 'aqualuxe_single_product_rating', 10);
    add_action('woocommerce_single_product_summary', 'aqualuxe_single_product_price', 15);
    add_action('woocommerce_single_product_summary', 'aqualuxe_single_product_excerpt', 20);
    add_action('woocommerce_single_product_summary', 'aqualuxe_single_product_add_to_cart', 30);
    add_action('woocommerce_single_product_summary', 'aqualuxe_single_product_meta', 40);
    add_action('woocommerce_single_product_summary', 'aqualuxe_single_product_sharing', 50);
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_single_product_tabs', 10);
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_single_product_upsells', 15);
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_single_product_related', 20);
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_single_product_recently_viewed', 25);
    
    /**
     * Cart
     *
     * @see aqualuxe_cart_wrapper_open()
     * @see aqualuxe_cart_wrapper_close()
     * @see aqualuxe_cart_cross_sells()
     */
    add_action('woocommerce_before_cart', 'aqualuxe_cart_wrapper_open', 5);
    add_action('woocommerce_after_cart', 'aqualuxe_cart_wrapper_close', 50);
    add_action('woocommerce_cart_collaterals', 'aqualuxe_cart_cross_sells', 5);
    
    /**
     * Checkout
     *
     * @see aqualuxe_checkout_wrapper_open()
     * @see aqualuxe_checkout_wrapper_close()
     */
    add_action('woocommerce_before_checkout_form', 'aqualuxe_checkout_wrapper_open', 5);
    add_action('woocommerce_after_checkout_form', 'aqualuxe_checkout_wrapper_close', 50);
    
    /**
     * Account
     *
     * @see aqualuxe_account_wrapper_open()
     * @see aqualuxe_account_wrapper_close()
     */
    add_action('woocommerce_before_account_navigation', 'aqualuxe_account_wrapper_open', 5);
    add_action('woocommerce_after_account_content', 'aqualuxe_account_wrapper_close', 50);
}
