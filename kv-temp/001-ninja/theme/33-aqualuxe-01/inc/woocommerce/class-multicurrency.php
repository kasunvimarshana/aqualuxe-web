<?php
/**
 * WooCommerce Multi-Currency Class
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
 * Multi-Currency Class
 *
 * Handles multi-currency functionality for WooCommerce.
 */
class MultiCurrency {

	/**
	 * Available currencies
	 *
	 * @var array
	 */
	private $currencies = array();

	/**
	 * Default currency
	 *
	 * @var string
	 */
	private $default_currency = '';

	/**
	 * Current currency
	 *
	 * @var string
	 */
	private $current_currency = '';

	/**
	 * Constructor
	 */
	public function __construct() {
		// Set default currency.
		$this->default_currency = get_woocommerce_currency();
		
		// Set available currencies.
		$this->currencies = $this->get_available_currencies();
		
		// Set current currency.
		$this->current_currency = $this->get_current_currency();
		
		// Filter currency.
		add_filter( 'woocommerce_currency', array( $this, 'change_currency' ) );
		
		// Filter currency symbol.
		add_filter( 'woocommerce_currency_symbol', array( $this, 'change_currency_symbol' ), 10, 2 );
		
		// Filter product price.
		add_filter( 'woocommerce_product_get_price', array( $this, 'change_product_price' ), 10, 2 );
		add_filter( 'woocommerce_product_get_regular_price', array( $this, 'change_product_price' ), 10, 2 );
		add_filter( 'woocommerce_product_get_sale_price', array( $this, 'change_product_price' ), 10, 2 );
		
		// Filter variation price.
		add_filter( 'woocommerce_product_variation_get_price', array( $this, 'change_product_price' ), 10, 2 );
		add_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'change_product_price' ), 10, 2 );
		add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'change_product_price' ), 10, 2 );
		
		// Filter shipping cost.
		add_filter( 'woocommerce_package_rates', array( $this, 'change_shipping_cost' ), 10, 2 );
		
		// Add currency switcher to header.
		add_action( 'aqualuxe_header_icons', array( $this, 'currency_switcher' ), 20 );
		
		// Add currency switcher to footer.
		add_action( 'aqualuxe_footer_widgets', array( $this, 'currency_switcher_footer' ), 20 );
		
		// AJAX handlers.
		add_action( 'wp_ajax_aqualuxe_switch_currency', array( $this, 'ajax_switch_currency' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_switch_currency', array( $this, 'ajax_switch_currency' ) );
		
		// Enqueue scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		// Add currency meta box to products.
		add_action( 'add_meta_boxes', array( $this, 'add_currency_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_currency_meta_box' ) );
	}

	/**
	 * Get available currencies
	 *
	 * @return array Available currencies.
	 */
	private function get_available_currencies() {
		// Default currencies.
		$default_currencies = array(
			'USD' => array(
				'name'   => esc_html__( 'US Dollar', 'aqualuxe' ),
				'symbol' => '$',
				'rate'   => 1,
			),
			'EUR' => array(
				'name'   => esc_html__( 'Euro', 'aqualuxe' ),
				'symbol' => '€',
				'rate'   => 0.85,
			),
			'GBP' => array(
				'name'   => esc_html__( 'British Pound', 'aqualuxe' ),
				'symbol' => '£',
				'rate'   => 0.75,
			),
			'CAD' => array(
				'name'   => esc_html__( 'Canadian Dollar', 'aqualuxe' ),
				'symbol' => 'CA$',
				'rate'   => 1.25,
			),
			'AUD' => array(
				'name'   => esc_html__( 'Australian Dollar', 'aqualuxe' ),
				'symbol' => 'A$',
				'rate'   => 1.35,
			),
			'JPY' => array(
				'name'   => esc_html__( 'Japanese Yen', 'aqualuxe' ),
				'symbol' => '¥',
				'rate'   => 110,
			),
		);
		
		// Get currencies from options.
		$saved_currencies = get_option( 'aqualuxe_currencies', array() );
		
		// Merge default currencies with saved currencies.
		$currencies = array_merge( $default_currencies, $saved_currencies );
		
		// Make sure default currency is included.
		if ( ! isset( $currencies[ $this->default_currency ] ) ) {
			$currencies[ $this->default_currency ] = array(
				'name'   => $this->default_currency,
				'symbol' => get_woocommerce_currency_symbol( $this->default_currency ),
				'rate'   => 1,
			);
		}
		
		return $currencies;
	}

	/**
	 * Get current currency
	 *
	 * @return string Current currency.
	 */
	private function get_current_currency() {
		// Check if currency is set in session.
		if ( isset( WC()->session ) ) {
			$currency = WC()->session->get( 'aqualuxe_currency' );
			
			if ( $currency && isset( $this->currencies[ $currency ] ) ) {
				return $currency;
			}
		}
		
		// Check if currency is set in cookie.
		if ( isset( $_COOKIE['aqualuxe_currency'] ) ) {
			$currency = sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_currency'] ) );
			
			if ( isset( $this->currencies[ $currency ] ) ) {
				return $currency;
			}
		}
		
		// Default to base currency.
		return $this->default_currency;
	}

	/**
	 * Change currency
	 *
	 * @param string $currency Currency code.
	 * @return string Modified currency code.
	 */
	public function change_currency( $currency ) {
		// Don't change currency in admin.
		if ( is_admin() && ! wp_doing_ajax() ) {
			return $currency;
		}
		
		return $this->current_currency;
	}

	/**
	 * Change currency symbol
	 *
	 * @param string $symbol Currency symbol.
	 * @param string $currency Currency code.
	 * @return string Modified currency symbol.
	 */
	public function change_currency_symbol( $symbol, $currency ) {
		// Don't change symbol in admin.
		if ( is_admin() && ! wp_doing_ajax() ) {
			return $symbol;
		}
		
		// Check if currency is in our list.
		if ( isset( $this->currencies[ $currency ] ) && isset( $this->currencies[ $currency ]['symbol'] ) ) {
			return $this->currencies[ $currency ]['symbol'];
		}
		
		return $symbol;
	}

	/**
	 * Change product price
	 *
	 * @param string $price Product price.
	 * @param object $product Product object.
	 * @return string Modified product price.
	 */
	public function change_product_price( $price, $product ) {
		// Don't change price in admin.
		if ( is_admin() && ! wp_doing_ajax() ) {
			return $price;
		}
		
		// If price is empty, return it.
		if ( '' === $price ) {
			return $price;
		}
		
		// If current currency is default currency, return price.
		if ( $this->current_currency === $this->default_currency ) {
			return $price;
		}
		
		// Check if product has custom price for current currency.
		$product_id = $product->get_id();
		$custom_price = get_post_meta( $product_id, '_price_' . $this->current_currency, true );
		
		if ( '' !== $custom_price ) {
			return $custom_price;
		}
		
		// Convert price using exchange rate.
		$rate = $this->currencies[ $this->current_currency ]['rate'];
		$converted_price = $price * $rate;
		
		// Round price.
		$converted_price = round( $converted_price, wc_get_price_decimals() );
		
		return $converted_price;
	}

	/**
	 * Change shipping cost
	 *
	 * @param array $rates Shipping rates.
	 * @param array $package Shipping package.
	 * @return array Modified shipping rates.
	 */
	public function change_shipping_cost( $rates, $package ) {
		// Don't change shipping cost in admin.
		if ( is_admin() && ! wp_doing_ajax() ) {
			return $rates;
		}
		
		// If current currency is default currency, return rates.
		if ( $this->current_currency === $this->default_currency ) {
			return $rates;
		}
		
		// Get exchange rate.
		$rate = $this->currencies[ $this->current_currency ]['rate'];
		
		// Convert shipping costs.
		foreach ( $rates as $rate_id => $shipping_rate ) {
			$cost = $shipping_rate->get_cost();
			$converted_cost = $cost * $rate;
			$shipping_rate->set_cost( $converted_cost );
			
			// Convert taxes.
			$taxes = $shipping_rate->get_taxes();
			$converted_taxes = array();
			
			foreach ( $taxes as $tax_id => $tax ) {
				$converted_taxes[ $tax_id ] = $tax * $rate;
			}
			
			$shipping_rate->set_taxes( $converted_taxes );
		}
		
		return $rates;
	}

	/**
	 * Currency switcher
	 */
	public function currency_switcher() {
		// Only show if we have more than one currency.
		if ( count( $this->currencies ) <= 1 ) {
			return;
		}
		
		echo '<div class="aqualuxe-currency-switcher">';
		echo '<a href="#" class="aqualuxe-currency-switcher-toggle">';
		echo '<span class="aqualuxe-currency-switcher-current">' . esc_html( $this->current_currency ) . '</span>';
		echo '<i class="fas fa-chevron-down"></i>';
		echo '</a>';
		
		echo '<div class="aqualuxe-currency-switcher-dropdown">';
		echo '<ul>';
		
		foreach ( $this->currencies as $code => $currency ) {
			$class = $code === $this->current_currency ? 'active' : '';
			
			echo '<li class="' . esc_attr( $class ) . '">';
			echo '<a href="#" data-currency="' . esc_attr( $code ) . '">';
			echo '<span class="aqualuxe-currency-code">' . esc_html( $code ) . '</span>';
			echo '<span class="aqualuxe-currency-name">' . esc_html( $currency['name'] ) . '</span>';
			echo '</a>';
			echo '</li>';
		}
		
		echo '</ul>';
		echo '</div>';
		echo '</div>';
	}

	/**
	 * Currency switcher footer
	 */
	public function currency_switcher_footer() {
		// Only show if we have more than one currency.
		if ( count( $this->currencies ) <= 1 ) {
			return;
		}
		
		echo '<div class="aqualuxe-footer-currency-switcher">';
		echo '<h4>' . esc_html__( 'Currency', 'aqualuxe' ) . '</h4>';
		echo '<ul>';
		
		foreach ( $this->currencies as $code => $currency ) {
			$class = $code === $this->current_currency ? 'active' : '';
			
			echo '<li class="' . esc_attr( $class ) . '">';
			echo '<a href="#" data-currency="' . esc_attr( $code ) . '">';
			echo '<span class="aqualuxe-currency-symbol">' . esc_html( $currency['symbol'] ) . '</span>';
			echo '<span class="aqualuxe-currency-code">' . esc_html( $code ) . '</span>';
			echo '</a>';
			echo '</li>';
		}
		
		echo '</ul>';
		echo '</div>';
	}

	/**
	 * AJAX switch currency
	 */
	public function ajax_switch_currency() {
		// Check nonce.
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_currency_nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Security check failed.', 'aqualuxe' ) ) );
		}
		
		// Get currency.
		$currency = isset( $_POST['currency'] ) ? sanitize_text_field( wp_unslash( $_POST['currency'] ) ) : '';
		
		if ( ! $currency || ! isset( $this->currencies[ $currency ] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid currency.', 'aqualuxe' ) ) );
		}
		
		// Set currency in session.
		if ( isset( WC()->session ) ) {
			WC()->session->set( 'aqualuxe_currency', $currency );
		}
		
		// Set currency in cookie.
		setcookie( 'aqualuxe_currency', $currency, time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		
		wp_send_json_success( array(
			'message'  => esc_html__( 'Currency switched successfully.', 'aqualuxe' ),
			'currency' => $currency,
			'symbol'   => $this->currencies[ $currency ]['symbol'],
		) );
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		// Only enqueue if we have more than one currency.
		if ( count( $this->currencies ) <= 1 ) {
			return;
		}
		
		wp_enqueue_script(
			'aqualuxe-multi-currency',
			get_template_directory_uri() . '/assets/js/multi-currency.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
		
		wp_localize_script(
			'aqualuxe-multi-currency',
			'aqualuxeCurrency',
			array(
				'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'aqualuxe_currency_nonce' ),
				'current'   => $this->current_currency,
				'switching' => esc_html__( 'Switching currency...', 'aqualuxe' ),
				'success'   => esc_html__( 'Currency switched successfully.', 'aqualuxe' ),
				'error'     => esc_html__( 'Error switching currency.', 'aqualuxe' ),
			)
		);
	}

	/**
	 * Add currency meta box to products
	 */
	public function add_currency_meta_box() {
		// Only add if we have more than one currency.
		if ( count( $this->currencies ) <= 1 ) {
			return;
		}
		
		add_meta_box(
			'aqualuxe_currency_meta_box',
			esc_html__( 'Currency Prices', 'aqualuxe' ),
			array( $this, 'render_currency_meta_box' ),
			'product',
			'normal',
			'default'
		);
	}

	/**
	 * Render currency meta box
	 *
	 * @param object $post Post object.
	 */
	public function render_currency_meta_box( $post ) {
		// Add nonce for security.
		wp_nonce_field( 'aqualuxe_currency_meta_box', 'aqualuxe_currency_meta_box_nonce' );
		
		// Get product.
		$product = wc_get_product( $post->ID );
		
		// Get regular price.
		$regular_price = $product->get_regular_price();
		
		// Get sale price.
		$sale_price = $product->get_sale_price();
		
		echo '<table class="form-table">';
		echo '<tr>';
		echo '<th>' . esc_html__( 'Currency', 'aqualuxe' ) . '</th>';
		echo '<th>' . esc_html__( 'Regular Price', 'aqualuxe' ) . '</th>';
		echo '<th>' . esc_html__( 'Sale Price', 'aqualuxe' ) . '</th>';
		echo '</tr>';
		
		// Default currency.
		echo '<tr>';
		echo '<td>' . esc_html( $this->default_currency ) . ' (' . esc_html__( 'Default', 'aqualuxe' ) . ')</td>';
		echo '<td>' . esc_html( $regular_price ) . '</td>';
		echo '<td>' . esc_html( $sale_price ) . '</td>';
		echo '</tr>';
		
		// Other currencies.
		foreach ( $this->currencies as $code => $currency ) {
			// Skip default currency.
			if ( $code === $this->default_currency ) {
				continue;
			}
			
			// Get custom prices.
			$custom_regular_price = get_post_meta( $post->ID, '_regular_price_' . $code, true );
			$custom_sale_price = get_post_meta( $post->ID, '_sale_price_' . $code, true );
			
			// Calculate converted prices.
			$converted_regular_price = $regular_price * $currency['rate'];
			$converted_sale_price = $sale_price * $currency['rate'];
			
			// Round prices.
			$converted_regular_price = round( $converted_regular_price, wc_get_price_decimals() );
			$converted_sale_price = round( $converted_sale_price, wc_get_price_decimals() );
			
			echo '<tr>';
			echo '<td>' . esc_html( $code ) . ' (' . esc_html( $currency['name'] ) . ')</td>';
			echo '<td>';
			echo '<input type="text" name="aqualuxe_regular_price_' . esc_attr( $code ) . '" value="' . esc_attr( $custom_regular_price ) . '" class="short wc_input_price" placeholder="' . esc_attr( $converted_regular_price ) . '" />';
			echo '</td>';
			echo '<td>';
			echo '<input type="text" name="aqualuxe_sale_price_' . esc_attr( $code ) . '" value="' . esc_attr( $custom_sale_price ) . '" class="short wc_input_price" placeholder="' . esc_attr( $converted_sale_price ) . '" />';
			echo '</td>';
			echo '</tr>';
		}
		
		echo '</table>';
		
		echo '<p class="description">' . esc_html__( 'Enter custom prices for each currency. Leave empty to use automatic conversion based on exchange rates.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Save currency meta box
	 *
	 * @param int $post_id Post ID.
	 */
	public function save_currency_meta_box( $post_id ) {
		// Check if nonce is set.
		if ( ! isset( $_POST['aqualuxe_currency_meta_box_nonce'] ) ) {
			return;
		}
		
		// Verify nonce.
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aqualuxe_currency_meta_box_nonce'] ) ), 'aqualuxe_currency_meta_box' ) ) {
			return;
		}
		
		// Check if autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		// Check permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		
		// Save custom prices for each currency.
		foreach ( $this->currencies as $code => $currency ) {
			// Skip default currency.
			if ( $code === $this->default_currency ) {
				continue;
			}
			
			// Regular price.
			$regular_price_key = 'aqualuxe_regular_price_' . $code;
			if ( isset( $_POST[ $regular_price_key ] ) ) {
				$regular_price = sanitize_text_field( wp_unslash( $_POST[ $regular_price_key ] ) );
				update_post_meta( $post_id, '_regular_price_' . $code, $regular_price );
			}
			
			// Sale price.
			$sale_price_key = 'aqualuxe_sale_price_' . $code;
			if ( isset( $_POST[ $sale_price_key ] ) ) {
				$sale_price = sanitize_text_field( wp_unslash( $_POST[ $sale_price_key ] ) );
				update_post_meta( $post_id, '_sale_price_' . $code, $sale_price );
			}
			
			// Calculate price.
			$regular_price = get_post_meta( $post_id, '_regular_price_' . $code, true );
			$sale_price = get_post_meta( $post_id, '_sale_price_' . $code, true );
			
			// If sale price is set and less than regular price, use it as price.
			if ( '' !== $sale_price && '' !== $regular_price && $sale_price < $regular_price ) {
				$price = $sale_price;
			} else {
				$price = $regular_price;
			}
			
			update_post_meta( $post_id, '_price_' . $code, $price );
		}
	}
}

// Initialize the class.
new MultiCurrency();