<?php
/**
 * WooCommerce Compatibility Functions
 *
 * Centralized functions to handle WooCommerce compatibility and provide fallbacks
 * when WooCommerce is not active.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if WooCommerce is active
 *
 * @return bool True if WooCommerce is active, false otherwise
 */
function aqualuxe_is_woocommerce_active() {
	return class_exists( 'WooCommerce' );
}

/**
 * Safe way to check if current page is a WooCommerce page
 *
 * @return bool True if current page is a WooCommerce page, false otherwise
 */
function aqualuxe_is_woocommerce_page() {
	if ( ! aqualuxe_is_woocommerce_active() ) {
		return false;
	}
	
	return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
}

/**
 * Check if current page is a WooCommerce page by slug
 * Works even when WooCommerce is not active
 *
 * @return bool True if current page is a WooCommerce page, false otherwise
 */
function aqualuxe_is_woocommerce_page_by_slug() {
	global $post;
	
	if ( ! $post ) {
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
	if ( in_array( $post->post_name, $woocommerce_slugs ) ) {
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
	
	foreach ( $woocommerce_keywords as $keyword ) {
		if ( stripos( $post->post_title, $keyword ) !== false ) {
			return true;
		}
	}
	
	return false;
}

/**
 * Get cart URL safely (with fallback)
 *
 * @return string Cart URL or shop page URL as fallback
 */
function aqualuxe_get_cart_url() {
	if ( aqualuxe_is_woocommerce_active() ) {
		return wc_get_cart_url();
	}
	
	return get_permalink( get_option( 'page_for_posts' ) );
}

/**
 * Get account URL safely (with fallback)
 *
 * @return string Account URL or home URL as fallback
 */
function aqualuxe_get_account_url() {
	if ( aqualuxe_is_woocommerce_active() ) {
		return get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
	}
	
	return home_url( '/' );
}

/**
 * Get cart count safely (with fallback)
 *
 * @return int Cart count or 0 as fallback
 */
function aqualuxe_get_cart_count() {
	if ( aqualuxe_is_woocommerce_active() && WC()->cart ) {
		return WC()->cart->get_cart_contents_count();
	}
	
	return 0;
}

/**
 * Display cart icon with count
 *
 * @return void
 */
function aqualuxe_cart_icon() {
	?>
	<div class="cart-link">
		<a href="<?php echo esc_url( aqualuxe_get_cart_url() ); ?>" class="text-white hover:text-blue-200 relative">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
			</svg>
			<?php if ( aqualuxe_is_woocommerce_active() ) : ?>
				<span class="cart-count absolute -top-2 -right-2 bg-blue-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
					<?php echo esc_html( aqualuxe_get_cart_count() ); ?>
				</span>
			<?php endif; ?>
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
	?>
	<div class="account-links">
		<a href="<?php echo esc_url( aqualuxe_get_account_url() ); ?>" class="text-white hover:text-blue-200">
			<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
			</svg>
		</a>
	</div>
	<?php
}

/**
 * Check if analytics features are available
 * 
 * @return bool True if analytics features are available, false otherwise
 */
function aqualuxe_is_analytics_available() {
	// Check if WooCommerce is active since analytics depends on it
	return aqualuxe_is_woocommerce_active();
}

/**
 * Safely get WooCommerce product
 *
 * @param int $product_id Product ID
 * @return WC_Product|false Product object or false if WooCommerce is not active
 */
function aqualuxe_get_product( $product_id ) {
	if ( ! aqualuxe_is_woocommerce_active() || ! function_exists( 'wc_get_product' ) ) {
		return false;
	}
	return wc_get_product( $product_id );
}

/**
 * Safely get WooCommerce order
 *
 * @param int $order_id Order ID
 * @return WC_Order|false Order object or false if WooCommerce is not active
 */
function aqualuxe_get_order( $order_id ) {
	if ( ! aqualuxe_is_woocommerce_active() || ! function_exists( 'wc_get_order' ) ) {
		return false;
	}
	return wc_get_order( $order_id );
}

/**
 * Safely get WooCommerce orders
 *
 * @param array $args Query arguments
 * @return array|false Array of orders or false if WooCommerce is not active
 */
function aqualuxe_get_orders( $args ) {
	if ( ! aqualuxe_is_woocommerce_active() || ! function_exists( 'wc_get_orders' ) ) {
		return false;
	}
	return wc_get_orders( $args );
}

/**
 * Safely get WooCommerce rating HTML
 *
 * @param float $rating Rating
 * @param int $count Count of ratings
 * @return string Rating HTML or empty string if WooCommerce is not active
 */
function aqualuxe_get_rating_html( $rating, $count = 0 ) {
	if ( ! aqualuxe_is_woocommerce_active() || ! function_exists( 'wc_get_rating_html' ) ) {
		// Fallback rating HTML
		$html = '<div class="star-rating">';
		$html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%">';
		$html .= sprintf( esc_html__( 'Rated %s out of 5', 'aqualuxe' ), $rating );
		$html .= '</span>';
		$html .= '</div>';
		return $html;
	}
	return wc_get_rating_html( $rating, $count );
}

/**
 * Safely format price
 *
 * @param float $price Price to format
 * @return string Formatted price
 */
function aqualuxe_price( $price ) {
	if ( aqualuxe_is_woocommerce_active() && function_exists( 'wc_price' ) ) {
		return wc_price( $price );
	}
	
	// Basic fallback price formatting
	return '$' . number_format( $price, 2 );
}

/**
 * Safely get WooCommerce order statuses
 *
 * @return array Order statuses or default statuses if WooCommerce is not active
 */
function aqualuxe_get_order_statuses() {
    if (aqualuxe_is_woocommerce_active() && function_exists('wc_get_order_statuses')) {
        return wc_get_order_statuses();
    }
    
    // Default fallback statuses
    return array(
        'wc-pending'    => __('Pending payment', 'aqualuxe'),
        'wc-processing' => __('Processing', 'aqualuxe'),
        'wc-on-hold'    => __('On hold', 'aqualuxe'),
        'wc-completed'  => __('Completed', 'aqualuxe'),
        'wc-cancelled'  => __('Cancelled', 'aqualuxe'),
        'wc-refunded'   => __('Refunded', 'aqualuxe'),
        'wc-failed'     => __('Failed', 'aqualuxe')
    );
}

/**
 * Safely get WooCommerce order status name
 *
 * @param string $status Order status
 * @return string Order status name
 */
function aqualuxe_get_order_status_name($status) {
    if (aqualuxe_is_woocommerce_active() && function_exists('wc_get_order_status_name')) {
        return wc_get_order_status_name($status);
    }
    
    // Remove wc- prefix if it exists
    $status = 'wc-' === substr($status, 0, 3) ? substr($status, 3) : $status;
    
    // Map of status to readable name
    $statuses = array(
        'pending'    => __('Pending payment', 'aqualuxe'),
        'processing' => __('Processing', 'aqualuxe'),
        'on-hold'    => __('On hold', 'aqualuxe'),
        'completed'  => __('Completed', 'aqualuxe'),
        'cancelled'  => __('Cancelled', 'aqualuxe'),
        'refunded'   => __('Refunded', 'aqualuxe'),
        'failed'     => __('Failed', 'aqualuxe')
    );
    
    return isset($statuses[$status]) ? $statuses[$status] : $status;
}

/**
 * Safely prepare date response for REST API
 *
 * @param WC_DateTime|null $date Date object
 * @return string|null Formatted date or null
 */
function aqualuxe_rest_prepare_date_response($date) {
    if (aqualuxe_is_woocommerce_active() && function_exists('wc_rest_prepare_date_response')) {
        return wc_rest_prepare_date_response($date);
    }
    
    if (is_null($date)) {
        return null;
    }
    
    // Basic fallback for date formatting
    if (is_a($date, 'WC_DateTime') || is_a($date, 'DateTime')) {
        return $date->format('Y-m-d\TH:i:s');
    }
    
    return null;
}

/**
 * Safely get order notes
 *
 * @param array $args Query arguments
 * @return array Order notes or empty array if WooCommerce is not active
 */
function aqualuxe_get_order_notes($args) {
    if (aqualuxe_is_woocommerce_active() && function_exists('wc_get_order_notes')) {
        return wc_get_order_notes($args);
    }
    
    return array();
}

/**
 * Safely get order note
 *
 * @param int $note_id Note ID
 * @return object|false Order note or false if WooCommerce is not active
 */
function aqualuxe_get_order_note($note_id) {
    if (aqualuxe_is_woocommerce_active() && function_exists('wc_get_order_note')) {
        return wc_get_order_note($note_id);
    }
    
    return false;
}

/**
 * Safely create an order
 *
 * @param array $args Order arguments
 * @return WC_Order|false Order object or false if WooCommerce is not active
 */
function aqualuxe_create_order($args = array()) {
    if (aqualuxe_is_woocommerce_active() && function_exists('wc_create_order')) {
        return wc_create_order($args);
    }
    
    return false;
}

/**
 * Safely check if a review is from a verified owner
 *
 * @param int $comment_id Comment ID
 * @return bool True if review is from verified owner, false otherwise
 */
function aqualuxe_review_is_from_verified_owner($comment_id) {
    if (aqualuxe_is_woocommerce_active() && function_exists('wc_review_is_from_verified_owner')) {
        return wc_review_is_from_verified_owner($comment_id);
    }
    
    return false;
}

/**
 * Safely check if customer bought product
 *
 * @param string $customer_email Customer email
 * @param int $user_id User ID
 * @param int $product_id Product ID
 * @return bool True if customer bought product, false otherwise
 */
function aqualuxe_customer_bought_product($customer_email, $user_id, $product_id) {
    if (aqualuxe_is_woocommerce_active() && function_exists('wc_customer_bought_product')) {
        return wc_customer_bought_product($customer_email, $user_id, $product_id);
    }
    
    return false;
}

/**
 * Safely get related products
 *
 * @param int $product_id Product ID
 * @param int $limit Limit
 * @return array Related product IDs or empty array if WooCommerce is not active
 */
function aqualuxe_get_related_products($product_id, $limit = 5) {
    if (aqualuxe_is_woocommerce_active() && function_exists('wc_get_related_products')) {
        return wc_get_related_products($product_id, $limit);
    }
    
    return array();
}

/**
 * Safely get product IDs on sale
 *
 * @return array Product IDs on sale or empty array if WooCommerce is not active
 */
function aqualuxe_get_product_ids_on_sale() {
    if (aqualuxe_is_woocommerce_active() && function_exists('wc_get_product_ids_on_sale')) {
        return wc_get_product_ids_on_sale();
    }
    
    return array();
}

/**
 * Safely get object terms
 *
 * @param int $object_id Object ID
 * @param string $taxonomy Taxonomy
 * @return array Terms or empty array if WooCommerce is not active
 */
function aqualuxe_get_object_terms($object_id, $taxonomy) {
    if (aqualuxe_is_woocommerce_active() && function_exists('wc_get_object_terms')) {
        return wc_get_object_terms($object_id, $taxonomy);
    }
    
    // Fallback to WordPress function
    return wp_get_object_terms($object_id, $taxonomy);
}

/**
 * Safely get order coupon discount amount
 *
 * @param WC_Order $order Order object
 * @param string $coupon_code Coupon code
 * @return float Discount amount or 0 if WooCommerce is not active
 */
function aqualuxe_get_order_coupon_discount_amount($order, $coupon_code) {
    if (aqualuxe_is_woocommerce_active() && function_exists('wc_get_order_coupon_discount_amount')) {
        return wc_get_order_coupon_discount_amount($order, $coupon_code);
    }
    
    return 0;
}

/**
 * Safely get order coupon discount tax amount
 *
 * @param WC_Order $order Order object
 * @param string $coupon_code Coupon code
 * @return float Discount tax amount or 0 if WooCommerce is not active
 */
function aqualuxe_get_order_coupon_discount_tax_amount($order, $coupon_code) {
    if (aqualuxe_is_woocommerce_active() && function_exists('wc_get_order_coupon_discount_tax_amount')) {
        return wc_get_order_coupon_discount_tax_amount($order, $coupon_code);
    }
    
    return 0;
}