<?php
/**
 * Template hooks for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Header
 *
 * @see aqualuxe_header_top()
 * @see aqualuxe_header_main()
 * @see aqualuxe_header_bottom()
 */
add_action('aqualuxe_header', 'aqualuxe_header_top', 10);
add_action('aqualuxe_header', 'aqualuxe_header_main', 20);
add_action('aqualuxe_header', 'aqualuxe_header_bottom', 30);

/**
 * Header Top
 *
 * @see aqualuxe_header_top_left()
 * @see aqualuxe_header_top_right()
 */
add_action('aqualuxe_header_top', 'aqualuxe_header_top_left', 10);
add_action('aqualuxe_header_top', 'aqualuxe_header_top_right', 20);

/**
 * Header Top Left
 *
 * @see aqualuxe_header_contact_info()
 */
add_action('aqualuxe_header_top_left', 'aqualuxe_header_contact_info', 10);

/**
 * Header Top Right
 *
 * @see aqualuxe_header_language_switcher()
 * @see aqualuxe_header_currency_switcher()
 * @see aqualuxe_header_dark_mode_toggle()
 * @see aqualuxe_header_secondary_menu()
 */
add_action('aqualuxe_header_top_right', 'aqualuxe_header_language_switcher', 10);
add_action('aqualuxe_header_top_right', 'aqualuxe_header_currency_switcher', 20);
add_action('aqualuxe_header_top_right', 'aqualuxe_header_dark_mode_toggle', 30);
add_action('aqualuxe_header_top_right', 'aqualuxe_header_secondary_menu', 40);

/**
 * Header Main
 *
 * @see aqualuxe_header_logo()
 * @see aqualuxe_header_navigation()
 * @see aqualuxe_header_actions()
 */
add_action('aqualuxe_header_main', 'aqualuxe_header_logo', 10);
add_action('aqualuxe_header_main', 'aqualuxe_header_navigation', 20);
add_action('aqualuxe_header_main', 'aqualuxe_header_actions', 30);

/**
 * Header Actions
 *
 * @see aqualuxe_header_search()
 * @see aqualuxe_header_account()
 * @see aqualuxe_header_wishlist()
 * @see aqualuxe_header_cart()
 */
add_action('aqualuxe_header_actions', 'aqualuxe_header_search', 10);
add_action('aqualuxe_header_actions', 'aqualuxe_header_account', 20);
add_action('aqualuxe_header_actions', 'aqualuxe_header_wishlist', 30);
add_action('aqualuxe_header_actions', 'aqualuxe_header_cart', 40);

/**
 * Header Bottom
 *
 * @see aqualuxe_header_categories_menu()
 */
add_action('aqualuxe_header_bottom', 'aqualuxe_header_categories_menu', 10);

/**
 * Footer
 *
 * @see aqualuxe_footer_widgets()
 * @see aqualuxe_footer_main()
 * @see aqualuxe_footer_bottom()
 */
add_action('aqualuxe_footer', 'aqualuxe_footer_widgets', 10);
add_action('aqualuxe_footer', 'aqualuxe_footer_main', 20);
add_action('aqualuxe_footer', 'aqualuxe_footer_bottom', 30);

/**
 * Footer Widgets
 *
 * @see aqualuxe_footer_widgets_column_1()
 * @see aqualuxe_footer_widgets_column_2()
 * @see aqualuxe_footer_widgets_column_3()
 * @see aqualuxe_footer_widgets_column_4()
 */
add_action('aqualuxe_footer_widgets', 'aqualuxe_footer_widgets_column_1', 10);
add_action('aqualuxe_footer_widgets', 'aqualuxe_footer_widgets_column_2', 20);
add_action('aqualuxe_footer_widgets', 'aqualuxe_footer_widgets_column_3', 30);
add_action('aqualuxe_footer_widgets', 'aqualuxe_footer_widgets_column_4', 40);

/**
 * Footer Main
 *
 * @see aqualuxe_footer_logo()
 * @see aqualuxe_footer_menu()
 * @see aqualuxe_footer_social()
 */
add_action('aqualuxe_footer_main', 'aqualuxe_footer_logo', 10);
add_action('aqualuxe_footer_main', 'aqualuxe_footer_menu', 20);
add_action('aqualuxe_footer_main', 'aqualuxe_footer_social', 30);

/**
 * Footer Bottom
 *
 * @see aqualuxe_footer_copyright()
 * @see aqualuxe_footer_payment_icons()
 */
add_action('aqualuxe_footer_bottom', 'aqualuxe_footer_copyright', 10);
add_action('aqualuxe_footer_bottom', 'aqualuxe_footer_payment_icons', 20);

/**
 * Content
 *
 * @see aqualuxe_page_header()
 * @see aqualuxe_breadcrumb()
 */
add_action('aqualuxe_content_top', 'aqualuxe_page_header', 10);
add_action('aqualuxe_content_top', 'aqualuxe_breadcrumb', 20);

/**
 * Homepage
 *
 * @see aqualuxe_homepage_hero()
 * @see aqualuxe_homepage_featured_products()
 * @see aqualuxe_homepage_categories()
 * @see aqualuxe_homepage_services()
 * @see aqualuxe_homepage_testimonials()
 * @see aqualuxe_homepage_blog()
 * @see aqualuxe_homepage_newsletter()
 */
add_action('aqualuxe_homepage', 'aqualuxe_homepage_hero', 10);
add_action('aqualuxe_homepage', 'aqualuxe_homepage_featured_products', 20);
add_action('aqualuxe_homepage', 'aqualuxe_homepage_categories', 30);
add_action('aqualuxe_homepage', 'aqualuxe_homepage_services', 40);
add_action('aqualuxe_homepage', 'aqualuxe_homepage_testimonials', 50);
add_action('aqualuxe_homepage', 'aqualuxe_homepage_blog', 60);
add_action('aqualuxe_homepage', 'aqualuxe_homepage_newsletter', 70);

/**
 * Single Post
 *
 * @see aqualuxe_post_thumbnail()
 * @see aqualuxe_post_meta()
 * @see aqualuxe_post_content()
 * @see aqualuxe_post_tags()
 * @see aqualuxe_post_author_bio()
 * @see aqualuxe_post_navigation()
 * @see aqualuxe_post_related()
 * @see aqualuxe_post_comments()
 */
add_action('aqualuxe_single_post', 'aqualuxe_post_thumbnail', 10);
add_action('aqualuxe_single_post', 'aqualuxe_post_meta', 20);
add_action('aqualuxe_single_post', 'aqualuxe_post_content', 30);
add_action('aqualuxe_single_post', 'aqualuxe_post_tags', 40);
add_action('aqualuxe_single_post', 'aqualuxe_post_author_bio', 50);
add_action('aqualuxe_single_post', 'aqualuxe_post_navigation', 60);
add_action('aqualuxe_single_post', 'aqualuxe_post_related', 70);
add_action('aqualuxe_single_post', 'aqualuxe_post_comments', 80);

/**
 * Page
 *
 * @see aqualuxe_page_content()
 * @see aqualuxe_page_comments()
 */
add_action('aqualuxe_page', 'aqualuxe_page_content', 10);
add_action('aqualuxe_page', 'aqualuxe_page_comments', 20);

/**
 * Sidebar
 *
 * @see aqualuxe_get_sidebar()
 */
add_action('aqualuxe_sidebar', 'aqualuxe_get_sidebar', 10);

/**
 * WooCommerce
 */
if (aqualuxe_is_woocommerce_active()) {
    /**
     * Product Loop Item
     *
     * @see aqualuxe_template_loop_product_thumbnail()
     * @see aqualuxe_template_loop_product_title()
     * @see aqualuxe_template_loop_product_price()
     * @see aqualuxe_template_loop_product_rating()
     * @see aqualuxe_template_loop_product_actions()
     */
    add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_template_loop_product_thumbnail', 10);
    add_action('woocommerce_shop_loop_item_title', 'aqualuxe_template_loop_product_title', 10);
    add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_template_loop_product_price', 10);
    add_action('woocommerce_after_shop_loop_item_title', 'aqualuxe_template_loop_product_rating', 20);
    add_action('woocommerce_after_shop_loop_item', 'aqualuxe_template_loop_product_actions', 10);

    /**
     * Single Product
     *
     * @see aqualuxe_template_single_product_gallery()
     * @see aqualuxe_template_single_product_summary()
     * @see aqualuxe_template_single_product_tabs()
     * @see aqualuxe_template_single_product_related()
     * @see aqualuxe_template_single_product_upsells()
     */
    add_action('woocommerce_before_single_product_summary', 'aqualuxe_template_single_product_gallery', 10);
    add_action('woocommerce_single_product_summary', 'aqualuxe_template_single_product_summary', 10);
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_template_single_product_tabs', 10);
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_template_single_product_related', 20);
    add_action('woocommerce_after_single_product_summary', 'aqualuxe_template_single_product_upsells', 30);

    /**
     * Cart
     *
     * @see aqualuxe_template_cart_cross_sells()
     */
    add_action('woocommerce_cart_collaterals', 'aqualuxe_template_cart_cross_sells', 10);

    /**
     * Checkout
     *
     * @see aqualuxe_template_checkout_login_form()
     * @see aqualuxe_template_checkout_coupon_form()
     */
    add_action('woocommerce_before_checkout_form', 'aqualuxe_template_checkout_login_form', 10);
    add_action('woocommerce_before_checkout_form', 'aqualuxe_template_checkout_coupon_form', 20);

    /**
     * Account
     *
     * @see aqualuxe_template_account_dashboard()
     * @see aqualuxe_template_account_orders()
     * @see aqualuxe_template_account_downloads()
     * @see aqualuxe_template_account_addresses()
     * @see aqualuxe_template_account_payment_methods()
     * @see aqualuxe_template_account_edit_account()
     */
    add_action('woocommerce_account_dashboard', 'aqualuxe_template_account_dashboard', 10);
    add_action('woocommerce_account_orders_endpoint', 'aqualuxe_template_account_orders', 10);
    add_action('woocommerce_account_downloads_endpoint', 'aqualuxe_template_account_downloads', 10);
    add_action('woocommerce_account_edit-address_endpoint', 'aqualuxe_template_account_addresses', 10);
    add_action('woocommerce_account_payment-methods_endpoint', 'aqualuxe_template_account_payment_methods', 10);
    add_action('woocommerce_account_edit-account_endpoint', 'aqualuxe_template_account_edit_account', 10);
}