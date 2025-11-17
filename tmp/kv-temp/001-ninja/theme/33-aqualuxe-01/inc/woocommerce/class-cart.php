<?php
/**
 * WooCommerce Cart Class
 *
 * @package AquaLuxe
 * @subpackage WooCommerce
 */

namespace AquaLuxe\WooCommerce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cart Class
 *
 * Handles cart customization for WooCommerce.
 */
class Cart {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Cart page hooks.
		add_action( 'woocommerce_before_cart', array( $this, 'cart_wrapper_start' ) );
		add_action( 'woocommerce_after_cart', array( $this, 'cart_wrapper_end' ) );
		
		// Cart fragments for AJAX updates.
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_fragments' ) );
		
		// Cross-sells display.
		add_filter( 'woocommerce_cross_sells_columns', array( $this, 'cross_sells_columns' ) );
		add_filter( 'woocommerce_cross_sells_total', array( $this, 'cross_sells_total' ) );
		
		// Cart table modifications.
		add_filter( 'woocommerce_cart_item_thumbnail', array( $this, 'cart_item_thumbnail' ), 10, 3 );
		add_action( 'woocommerce_after_cart_item_name', array( $this, 'add_cart_item_meta' ), 10, 2 );
		
		// Empty cart message.
		add_filter( 'woocommerce_empty_cart_message', array( $this, 'custom_empty_cart_message' ) );
		
		// Cart actions.
		add_action( 'woocommerce_cart_actions', array( $this, 'add_cart_extra_actions' ) );
		
		// Mini cart.
		add_action( 'aqualuxe_before_mini_cart', array( $this, 'mini_cart_header' ) );
		add_action( 'aqualuxe_after_mini_cart', array( $this, 'mini_cart_footer' ) );
		
		// Cart totals.
		add_action( 'woocommerce_cart_totals_before_order_total', array( $this, 'maybe_add_shipping_notice' ) );
		
		// Cart coupon form.
		add_filter( 'woocommerce_coupon_message', array( $this, 'custom_coupon_message' ), 10, 3 );
	}

	/**
	 * Cart wrapper start
	 */
	public function cart_wrapper_start() {
		$cart_layout = get_theme_mod( 'aqualuxe_cart_layout', 'standard' );
		echo '<div class="aqualuxe-cart-wrapper cart-layout-' . esc_attr( $cart_layout ) . '">';
	}

	/**
	 * Cart wrapper end
	 */
	public function cart_wrapper_end() {
		echo '</div><!-- .aqualuxe-cart-wrapper -->';
	}

	/**
	 * Cart fragments
	 *
	 * @param array $fragments Cart fragments.
	 * @return array Modified cart fragments.
	 */
	public function cart_fragments( $fragments ) {
		// Cart count fragment.
		ob_start();
		?>
		<span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
		<?php
		$fragments['.cart-count'] = ob_get_clean();

		// Cart subtotal fragment.
		ob_start();
		?>
		<span class="cart-subtotal"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
		<?php
		$fragments['.cart-subtotal'] = ob_get_clean();

		// Mini cart fragment.
		ob_start();
		woocommerce_mini_cart();
		$fragments['div.widget_shopping_cart_content'] = ob_get_clean();

		return $fragments;
	}

	/**
	 * Cross sells columns
	 *
	 * @param int $columns Number of columns.
	 * @return int Modified number of columns.
	 */
	public function cross_sells_columns( $columns ) {
		return 4;
	}

	/**
	 * Cross sells total
	 *
	 * @param int $total Number of cross-sells to display.
	 * @return int Modified number of cross-sells.
	 */
	public function cross_sells_total( $total ) {
		return 4;
	}

	/**
	 * Cart item thumbnail
	 *
	 * @param string $thumbnail Product thumbnail HTML.
	 * @param array  $cart_item Cart item data.
	 * @param string $cart_item_key Cart item key.
	 * @return string Modified product thumbnail HTML.
	 */
	public function cart_item_thumbnail( $thumbnail, $cart_item, $cart_item_key ) {
		// Add a wrapper around the thumbnail for styling.
		return '<div class="aqualuxe-cart-item-thumbnail">' . $thumbnail . '</div>';
	}

	/**
	 * Add cart item meta
	 *
	 * @param array  $cart_item Cart item data.
	 * @param string $cart_item_key Cart item key.
	 */
	public function add_cart_item_meta( $cart_item, $cart_item_key ) {
		$product = $cart_item['data'];

		// Display SKU if available.
		if ( $product->get_sku() ) {
			echo '<div class="aqualuxe-cart-item-sku">' . esc_html__( 'SKU:', 'aqualuxe' ) . ' ' . esc_html( $product->get_sku() ) . '</div>';
		}

		// Display stock status.
		if ( $product->is_in_stock() ) {
			echo '<div class="aqualuxe-cart-item-stock in-stock">' . esc_html__( 'In stock', 'aqualuxe' ) . '</div>';
		} else {
			echo '<div class="aqualuxe-cart-item-stock out-of-stock">' . esc_html__( 'Out of stock', 'aqualuxe' ) . '</div>';
		}
	}

	/**
	 * Custom empty cart message
	 *
	 * @param string $message Empty cart message.
	 * @return string Modified empty cart message.
	 */
	public function custom_empty_cart_message( $message ) {
		$message = '<div class="aqualuxe-empty-cart">';
		$message .= '<div class="aqualuxe-empty-cart-icon"><i class="fas fa-shopping-cart"></i></div>';
		$message .= '<p>' . esc_html__( 'Your cart is currently empty.', 'aqualuxe' ) . '</p>';
		$message .= '<a class="button" href="' . esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ) . '">';
		$message .= esc_html__( 'Return to shop', 'aqualuxe' );
		$message .= '</a>';
		$message .= '</div>';

		return $message;
	}

	/**
	 * Add cart extra actions
	 */
	public function add_cart_extra_actions() {
		// Add "Continue Shopping" button.
		echo '<a href="' . esc_url( apply_filters( 'woocommerce_continue_shopping_redirect', wc_get_page_permalink( 'shop' ) ) ) . '" class="button continue-shopping">';
		echo esc_html__( 'Continue Shopping', 'aqualuxe' );
		echo '</a>';
	}

	/**
	 * Mini cart header
	 */
	public function mini_cart_header() {
		echo '<div class="aqualuxe-mini-cart-header">';
		echo '<h4>' . esc_html__( 'Shopping Cart', 'aqualuxe' ) . '</h4>';
		echo '<span class="aqualuxe-mini-cart-close">&times;</span>';
		echo '</div>';
	}

	/**
	 * Mini cart footer
	 */
	public function mini_cart_footer() {
		// Add "View Cart" button if not already present.
		if ( ! WC()->cart->is_empty() ) {
			echo '<div class="aqualuxe-mini-cart-footer">';
			echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="button view-cart">';
			echo esc_html__( 'View Cart', 'aqualuxe' );
			echo '</a>';
			echo '</div>';
		}
	}

	/**
	 * Maybe add shipping notice
	 */
	public function maybe_add_shipping_notice() {
		// Get free shipping threshold.
		$threshold = 100; // Default value, can be made customizable.
		$current = WC()->cart->get_subtotal();
		
		// If cart subtotal is less than threshold, show notice.
		if ( $current < $threshold ) {
			$remaining = $threshold - $current;
			echo '<tr class="aqualuxe-shipping-notice">';
			echo '<th colspan="2">';
			echo '<div class="aqualuxe-free-shipping-notice">';
			
			/* translators: %s: Remaining amount for free shipping */
			echo sprintf( esc_html__( 'Add %s more to get free shipping!', 'aqualuxe' ), wc_price( $remaining ) );
			
			// Add progress bar.
			$percentage = ( $current / $threshold ) * 100;
			echo '<div class="aqualuxe-shipping-progress">';
			echo '<div class="aqualuxe-shipping-progress-bar" style="width:' . esc_attr( $percentage ) . '%"></div>';
			echo '</div>';
			
			echo '</div>';
			echo '</th>';
			echo '</tr>';
		}
	}

	/**
	 * Custom coupon message
	 *
	 * @param string $message Coupon message.
	 * @param string $msg_code Message code.
	 * @param object $coupon Coupon object.
	 * @return string Modified coupon message.
	 */
	public function custom_coupon_message( $message, $msg_code, $coupon ) {
		// Enhance coupon applied message.
		if ( $msg_code === WC_Coupon::WC_COUPON_SUCCESS ) {
			$message = sprintf(
				/* translators: %s: Coupon code */
				esc_html__( 'Coupon code "%s" applied successfully. Your discount has been added!', 'aqualuxe' ),
				esc_html( $coupon->get_code() )
			);
		}
		
		return $message;
	}
}

// Initialize the class.
new Cart();