<?php
/**
 * AquaLuxe Custom Hooks
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Theme hooks
 */
class AquaLuxe_Hooks {
    /**
     * Hook points
     *
     * @var array
     */
    private static $hooks = [
        // Header hooks
        'aqualuxe_before_header',
        'aqualuxe_header_top',
        'aqualuxe_header_main',
        'aqualuxe_header_bottom',
        'aqualuxe_after_header',
        
        // Content hooks
        'aqualuxe_before_content',
        'aqualuxe_content_top',
        'aqualuxe_content_bottom',
        'aqualuxe_after_content',
        
        // Footer hooks
        'aqualuxe_before_footer',
        'aqualuxe_footer_top',
        'aqualuxe_footer_widgets',
        'aqualuxe_footer_bottom',
        'aqualuxe_after_footer',
        
        // Sidebar hooks
        'aqualuxe_before_sidebar',
        'aqualuxe_sidebar_top',
        'aqualuxe_sidebar_bottom',
        'aqualuxe_after_sidebar',
        
        // Post hooks
        'aqualuxe_before_post',
        'aqualuxe_post_top',
        'aqualuxe_post_content_before',
        'aqualuxe_post_content_after',
        'aqualuxe_post_bottom',
        'aqualuxe_after_post',
        
        // Page hooks
        'aqualuxe_before_page',
        'aqualuxe_page_top',
        'aqualuxe_page_content_before',
        'aqualuxe_page_content_after',
        'aqualuxe_page_bottom',
        'aqualuxe_after_page',
        
        // WooCommerce hooks
        'aqualuxe_before_shop',
        'aqualuxe_shop_top',
        'aqualuxe_shop_bottom',
        'aqualuxe_after_shop',
        'aqualuxe_before_product',
        'aqualuxe_product_top',
        'aqualuxe_product_bottom',
        'aqualuxe_after_product',
        'aqualuxe_before_cart',
        'aqualuxe_cart_top',
        'aqualuxe_cart_bottom',
        'aqualuxe_after_cart',
        'aqualuxe_before_checkout',
        'aqualuxe_checkout_top',
        'aqualuxe_checkout_bottom',
        'aqualuxe_after_checkout',
        'aqualuxe_before_account',
        'aqualuxe_account_top',
        'aqualuxe_account_bottom',
        'aqualuxe_after_account',
    ];

    /**
     * Initialize hooks
     */
    public static function init() {
        // Register hooks
        foreach ( self::$hooks as $hook ) {
            add_action( $hook, [ __CLASS__, 'hook_callback' ], 10, 1 );
        }
        
        // Add default hook callbacks
        self::add_default_callbacks();
    }

    /**
     * Hook callback
     *
     * @param mixed $arg Optional argument
     */
    public static function hook_callback( $arg = null ) {
        $hook = current_filter();
        do_action( "aqualuxe_{$hook}", $arg );
    }

    /**
     * Add default hook callbacks
     */
    private static function add_default_callbacks() {
        // Header hooks
        add_action( 'aqualuxe_header_top', 'aqualuxe_header_top_bar' );
        add_action( 'aqualuxe_header_main', 'aqualuxe_header_main' );
        add_action( 'aqualuxe_after_header', 'aqualuxe_page_header' );
        
        // Footer hooks
        add_action( 'aqualuxe_footer_widgets', 'aqualuxe_footer_widgets' );
        add_action( 'aqualuxe_footer_bottom', 'aqualuxe_footer_bottom' );
        
        // Post hooks
        add_action( 'aqualuxe_post_top', 'aqualuxe_post_thumbnail' );
        add_action( 'aqualuxe_post_top', 'aqualuxe_post_meta' );
        add_action( 'aqualuxe_post_bottom', 'aqualuxe_post_tags' );
        add_action( 'aqualuxe_after_post', 'aqualuxe_author_bio' );
        add_action( 'aqualuxe_after_post', 'aqualuxe_post_navigation' );
        add_action( 'aqualuxe_after_post', 'aqualuxe_related_posts' );
        add_action( 'aqualuxe_after_post', 'aqualuxe_comments' );
        
        // Page hooks
        add_action( 'aqualuxe_page_top', 'aqualuxe_post_thumbnail' );
        add_action( 'aqualuxe_after_page', 'aqualuxe_comments' );
    }

    /**
     * Get hooks
     *
     * @return array Hooks
     */
    public static function get_hooks() {
        return self::$hooks;
    }
}

// Initialize hooks
AquaLuxe_Hooks::init();

/**
 * Before header
 */
function aqualuxe_do_before_header() {
    do_action( 'aqualuxe_before_header' );
}

/**
 * Header top
 */
function aqualuxe_do_header_top() {
    do_action( 'aqualuxe_header_top' );
}

/**
 * Header main
 */
function aqualuxe_do_header_main() {
    do_action( 'aqualuxe_header_main' );
}

/**
 * Header bottom
 */
function aqualuxe_do_header_bottom() {
    do_action( 'aqualuxe_header_bottom' );
}

/**
 * After header
 */
function aqualuxe_do_after_header() {
    do_action( 'aqualuxe_after_header' );
}

/**
 * Before content
 */
function aqualuxe_do_before_content() {
    do_action( 'aqualuxe_before_content' );
}

/**
 * Content top
 */
function aqualuxe_do_content_top() {
    do_action( 'aqualuxe_content_top' );
}

/**
 * Content bottom
 */
function aqualuxe_do_content_bottom() {
    do_action( 'aqualuxe_content_bottom' );
}

/**
 * After content
 */
function aqualuxe_do_after_content() {
    do_action( 'aqualuxe_after_content' );
}

/**
 * Before footer
 */
function aqualuxe_do_before_footer() {
    do_action( 'aqualuxe_before_footer' );
}

/**
 * Footer top
 */
function aqualuxe_do_footer_top() {
    do_action( 'aqualuxe_footer_top' );
}

/**
 * Footer widgets
 */
function aqualuxe_do_footer_widgets() {
    do_action( 'aqualuxe_footer_widgets' );
}

/**
 * Footer bottom
 */
function aqualuxe_do_footer_bottom() {
    do_action( 'aqualuxe_footer_bottom' );
}

/**
 * After footer
 */
function aqualuxe_do_after_footer() {
    do_action( 'aqualuxe_after_footer' );
}

/**
 * Before sidebar
 */
function aqualuxe_do_before_sidebar() {
    do_action( 'aqualuxe_before_sidebar' );
}

/**
 * Sidebar top
 */
function aqualuxe_do_sidebar_top() {
    do_action( 'aqualuxe_sidebar_top' );
}

/**
 * Sidebar bottom
 */
function aqualuxe_do_sidebar_bottom() {
    do_action( 'aqualuxe_sidebar_bottom' );
}

/**
 * After sidebar
 */
function aqualuxe_do_after_sidebar() {
    do_action( 'aqualuxe_after_sidebar' );
}

/**
 * Before post
 */
function aqualuxe_do_before_post() {
    do_action( 'aqualuxe_before_post' );
}

/**
 * Post top
 */
function aqualuxe_do_post_top() {
    do_action( 'aqualuxe_post_top' );
}

/**
 * Post content before
 */
function aqualuxe_do_post_content_before() {
    do_action( 'aqualuxe_post_content_before' );
}

/**
 * Post content after
 */
function aqualuxe_do_post_content_after() {
    do_action( 'aqualuxe_post_content_after' );
}

/**
 * Post bottom
 */
function aqualuxe_do_post_bottom() {
    do_action( 'aqualuxe_post_bottom' );
}

/**
 * After post
 */
function aqualuxe_do_after_post() {
    do_action( 'aqualuxe_after_post' );
}

/**
 * Before page
 */
function aqualuxe_do_before_page() {
    do_action( 'aqualuxe_before_page' );
}

/**
 * Page top
 */
function aqualuxe_do_page_top() {
    do_action( 'aqualuxe_page_top' );
}

/**
 * Page content before
 */
function aqualuxe_do_page_content_before() {
    do_action( 'aqualuxe_page_content_before' );
}

/**
 * Page content after
 */
function aqualuxe_do_page_content_after() {
    do_action( 'aqualuxe_page_content_after' );
}

/**
 * Page bottom
 */
function aqualuxe_do_page_bottom() {
    do_action( 'aqualuxe_page_bottom' );
}

/**
 * After page
 */
function aqualuxe_do_after_page() {
    do_action( 'aqualuxe_after_page' );
}

/**
 * Before shop
 */
function aqualuxe_do_before_shop() {
    do_action( 'aqualuxe_before_shop' );
}

/**
 * Shop top
 */
function aqualuxe_do_shop_top() {
    do_action( 'aqualuxe_shop_top' );
}

/**
 * Shop bottom
 */
function aqualuxe_do_shop_bottom() {
    do_action( 'aqualuxe_shop_bottom' );
}

/**
 * After shop
 */
function aqualuxe_do_after_shop() {
    do_action( 'aqualuxe_after_shop' );
}

/**
 * Before product
 */
function aqualuxe_do_before_product() {
    do_action( 'aqualuxe_before_product' );
}

/**
 * Product top
 */
function aqualuxe_do_product_top() {
    do_action( 'aqualuxe_product_top' );
}

/**
 * Product bottom
 */
function aqualuxe_do_product_bottom() {
    do_action( 'aqualuxe_product_bottom' );
}

/**
 * After product
 */
function aqualuxe_do_after_product() {
    do_action( 'aqualuxe_after_product' );
}

/**
 * Before cart
 */
function aqualuxe_do_before_cart() {
    do_action( 'aqualuxe_before_cart' );
}

/**
 * Cart top
 */
function aqualuxe_do_cart_top() {
    do_action( 'aqualuxe_cart_top' );
}

/**
 * Cart bottom
 */
function aqualuxe_do_cart_bottom() {
    do_action( 'aqualuxe_cart_bottom' );
}

/**
 * After cart
 */
function aqualuxe_do_after_cart() {
    do_action( 'aqualuxe_after_cart' );
}

/**
 * Before checkout
 */
function aqualuxe_do_before_checkout() {
    do_action( 'aqualuxe_before_checkout' );
}

/**
 * Checkout top
 */
function aqualuxe_do_checkout_top() {
    do_action( 'aqualuxe_checkout_top' );
}

/**
 * Checkout bottom
 */
function aqualuxe_do_checkout_bottom() {
    do_action( 'aqualuxe_checkout_bottom' );
}

/**
 * After checkout
 */
function aqualuxe_do_after_checkout() {
    do_action( 'aqualuxe_after_checkout' );
}

/**
 * Before account
 */
function aqualuxe_do_before_account() {
    do_action( 'aqualuxe_before_account' );
}

/**
 * Account top
 */
function aqualuxe_do_account_top() {
    do_action( 'aqualuxe_account_top' );
}

/**
 * Account bottom
 */
function aqualuxe_do_account_bottom() {
    do_action( 'aqualuxe_account_bottom' );
}

/**
 * After account
 */
function aqualuxe_do_after_account() {
    do_action( 'aqualuxe_after_account' );
}