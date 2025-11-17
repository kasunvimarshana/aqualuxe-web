<?php
/**
 * Subscriptions Module
 *
 * Provides integration with WooCommerce Subscriptions.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if WooCommerce Subscriptions is active.
 *
 * @return bool
 */
function aqualuxe_is_subscriptions_active() {
    return class_exists( 'WC_Subscriptions' );
}

/**
 * Custom functions and hooks for WooCommerce Subscriptions.
 */
if ( aqualuxe_is_subscriptions_active() ) {

    /**
     * Enqueue custom styles for the subscriptions module.
     */
    function aqualuxe_subscriptions_styles() {
        // Example: wp_enqueue_style( 'aqualuxe-subscriptions', AQUALUXE_THEME_URI . 'modules/subscriptions/assets/css/subscriptions.css' );
    }
    add_action( 'wp_enqueue_scripts', 'aqualuxe_subscriptions_styles' );

    /**
     * Add custom templates for subscriptions.
     *
     * You can override WooCommerce Subscriptions templates by placing them in:
     * /wp-content/themes/aqualuxe/woocommerce/emails/
     * /wp-content/themes/aqualuxe/woocommerce/myaccount/
     */

}

/**
 * Shortcode to display a pricing table for subscription products.
 *
 * Usage: [aqualuxe_subscription_pricing]
 */
function aqualuxe_subscription_pricing_shortcode() {
    ob_start();
    get_template_part( 'modules/subscriptions/templates/pricing-table' );
    return ob_get_clean();
}
add_shortcode( 'aqualuxe_subscription_pricing', 'aqualuxe_subscription_pricing_shortcode' );
