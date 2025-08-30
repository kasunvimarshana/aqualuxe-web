<?php
/**
 * Theme Constants
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Theme color constants
define( 'AQUALUXE_COLOR_PRIMARY', '#0891b2' );   // Cyan-600
define( 'AQUALUXE_COLOR_SECONDARY', '#6366f1' ); // Indigo-500
define( 'AQUALUXE_COLOR_ACCENT', '#8b5cf6' );    // Violet-500
define( 'AQUALUXE_COLOR_DARK', '#0f172a' );      // Slate-900
define( 'AQUALUXE_COLOR_LIGHT', '#f8fafc' );     // Slate-50

// Social media URLs - can be overridden in Customizer
define( 'AQUALUXE_SOCIAL_FACEBOOK', '' );
define( 'AQUALUXE_SOCIAL_TWITTER', '' );
define( 'AQUALUXE_SOCIAL_INSTAGRAM', '' );
define( 'AQUALUXE_SOCIAL_YOUTUBE', '' );
define( 'AQUALUXE_SOCIAL_LINKEDIN', '' );

// Contact information - can be overridden in Customizer
define( 'AQUALUXE_CONTACT_EMAIL', 'info@example.com' );
define( 'AQUALUXE_CONTACT_PHONE', '+1 (555) 123-4567' );
define( 'AQUALUXE_CONTACT_ADDRESS', '123 Ocean Avenue, Seaside City, SC 12345' );

// WooCommerce specific constants
if ( class_exists( 'WooCommerce' ) ) {
	define( 'AQUALUXE_PRODUCTS_PER_PAGE', 12 );
	define( 'AQUALUXE_RELATED_PRODUCTS', 4 );
	define( 'AQUALUXE_UPSELL_PRODUCTS', 4 );
	define( 'AQUALUXE_CROSS_SELL_PRODUCTS', 4 );
}