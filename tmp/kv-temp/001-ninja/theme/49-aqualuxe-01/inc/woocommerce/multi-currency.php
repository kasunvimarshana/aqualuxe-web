<?php
/**
 * WooCommerce Multi-Currency functionality
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Multi-Currency class
 */
class AquaLuxe_Multi_Currency {

	/**
	 * Available currencies
	 *
	 * @var array
	 */
	private $currencies = array();

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
		// Set default currencies
		$this->currencies = array(
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
			'JPY' => array(
				'name'   => esc_html__( 'Japanese Yen', 'aqualuxe' ),
				'symbol' => '¥',
				'rate'   => 110,
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
		);

		// Allow filtering of currencies
		$this->currencies = apply_filters( 'aqualuxe_currencies', $this->currencies );

		// Set current currency
		$this->set_current_currency();

		// Add hooks
		$this->add_hooks();
	}

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		// Skip if WPML or WooCommerce Currency Switcher is active
		if ( $this->is_wpml_multi_currency_active() || $this->is_woocommerce_currency_switcher_active() ) {
			return;
		}

		// Filter currency
		add_filter( 'woocommerce_currency', array( $this, 'get_woocommerce_currency' ) );

		// Filter currency symbol
		add_filter( 'woocommerce_currency_symbol', array( $this, 'get_woocommerce_currency_symbol' ), 10, 2 );

		// Filter product price
		add_filter( 'woocommerce_product_get_price', array( $this, 'convert_price' ), 10, 2 );
		add_filter( 'woocommerce_product_get_regular_price', array( $this, 'convert_price' ), 10, 2 );
		add_filter( 'woocommerce_product_get_sale_price', array( $this, 'convert_price' ), 10, 2 );
		add_filter( 'woocommerce_product_variation_get_price', array( $this, 'convert_price' ), 10, 2 );
		add_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'convert_price' ), 10, 2 );
		add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'convert_price' ), 10, 2 );

		// Filter shipping cost
		add_filter( 'woocommerce_package_rates', array( $this, 'convert_shipping_rates' ), 10, 2 );

		// Add currency switcher to header
		add_action( 'aqualuxe_header_extras', array( $this, 'currency_switcher' ), 20 );

		// Add AJAX handler for currency switching
		add_action( 'wp_ajax_aqualuxe_switch_currency', array( $this, 'ajax_switch_currency' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_switch_currency', array( $this, 'ajax_switch_currency' ) );

		// Add currency switcher script
		add_action( 'wp_footer', array( $this, 'currency_switcher_script' ) );
	}

	/**
	 * Set current currency
	 */
	public function set_current_currency() {
		// Get default currency
		$default_currency = get_option( 'woocommerce_currency' );

		// Get currency from cookie
		$cookie_currency = isset( $_COOKIE['aqualuxe_currency'] ) ? sanitize_text_field( $_COOKIE['aqualuxe_currency'] ) : '';

		// Get currency from URL
		$url_currency = isset( $_GET['currency'] ) ? sanitize_text_field( $_GET['currency'] ) : '';

		// Set current currency
		if ( $url_currency && array_key_exists( $url_currency, $this->currencies ) ) {
			$this->current_currency = $url_currency;
		} elseif ( $cookie_currency && array_key_exists( $cookie_currency, $this->currencies ) ) {
			$this->current_currency = $cookie_currency;
		} else {
			$this->current_currency = $default_currency;
		}
	}

	/**
	 * Get WooCommerce currency
	 *
	 * @param string $currency Currency code
	 * @return string
	 */
	public function get_woocommerce_currency( $currency ) {
		return $this->current_currency;
	}

	/**
	 * Get WooCommerce currency symbol
	 *
	 * @param string $symbol Currency symbol
	 * @param string $currency Currency code
	 * @return string
	 */
	public function get_woocommerce_currency_symbol( $symbol, $currency ) {
		if ( isset( $this->currencies[ $this->current_currency ]['symbol'] ) ) {
			return $this->currencies[ $this->current_currency ]['symbol'];
		}

		return $symbol;
	}

	/**
	 * Convert price
	 *
	 * @param float $price Price
	 * @param object $product Product object
	 * @return float
	 */
	public function convert_price( $price, $product ) {
		if ( empty( $price ) ) {
			return $price;
		}

		// Get default currency
		$default_currency = get_option( 'woocommerce_currency' );

		// Skip if current currency is the same as default currency
		if ( $this->current_currency === $default_currency ) {
			return $price;
		}

		// Get exchange rate
		$rate = $this->get_exchange_rate( $default_currency, $this->current_currency );

		// Convert price
		$converted_price = $price * $rate;

		return $converted_price;
	}

	/**
	 * Convert shipping rates
	 *
	 * @param array $rates Shipping rates
	 * @param array $package Shipping package
	 * @return array
	 */
	public function convert_shipping_rates( $rates, $package ) {
		// Get default currency
		$default_currency = get_option( 'woocommerce_currency' );

		// Skip if current currency is the same as default currency
		if ( $this->current_currency === $default_currency ) {
			return $rates;
		}

		// Get exchange rate
		$rate = $this->get_exchange_rate( $default_currency, $this->current_currency );

		// Convert shipping rates
		foreach ( $rates as $key => $shipping_rate ) {
			$rates[ $key ]->cost = $shipping_rate->cost * $rate;
			$taxes = array();

			foreach ( $shipping_rate->taxes as $tax_id => $tax ) {
				$taxes[ $tax_id ] = $tax * $rate;
			}

			$rates[ $key ]->taxes = $taxes;
		}

		return $rates;
	}

	/**
	 * Get exchange rate
	 *
	 * @param string $from_currency From currency
	 * @param string $to_currency To currency
	 * @return float
	 */
	public function get_exchange_rate( $from_currency, $to_currency ) {
		// Get from currency rate
		$from_rate = isset( $this->currencies[ $from_currency ]['rate'] ) ? $this->currencies[ $from_currency ]['rate'] : 1;

		// Get to currency rate
		$to_rate = isset( $this->currencies[ $to_currency ]['rate'] ) ? $this->currencies[ $to_currency ]['rate'] : 1;

		// Calculate exchange rate
		$rate = $to_rate / $from_rate;

		return $rate;
	}

	/**
	 * Currency switcher
	 */
	public function currency_switcher() {
		// Skip if WPML or WooCommerce Currency Switcher is active
		if ( $this->is_wpml_multi_currency_active() || $this->is_woocommerce_currency_switcher_active() ) {
			return;
		}

		// Skip if there's only one currency
		if ( count( $this->currencies ) <= 1 ) {
			return;
		}

		echo '<div class="currency-switcher">';
		echo '<div class="current-currency">';
		echo '<span class="currency-symbol">' . esc_html( $this->currencies[ $this->current_currency ]['symbol'] ) . '</span>';
		echo '<span class="currency-code">' . esc_html( $this->current_currency ) . '</span>';
		echo '<svg class="icon icon-chevron-down" aria-hidden="true" focusable="false"><use xlink:href="#icon-chevron-down"></use></svg>';
		echo '</div>';
		echo '<ul class="currency-list">';

		foreach ( $this->currencies as $code => $currency ) {
			$class = $code === $this->current_currency ? 'active' : '';
			echo '<li class="' . esc_attr( $class ) . '">';
			echo '<a href="#" data-currency="' . esc_attr( $code ) . '">';
			echo '<span class="currency-symbol">' . esc_html( $currency['symbol'] ) . '</span>';
			echo '<span class="currency-code">' . esc_html( $code ) . '</span>';
			echo '<span class="currency-name">' . esc_html( $currency['name'] ) . '</span>';
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
		// Check nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'aqualuxe-woocommerce-nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ) );
		}

		// Check currency
		if ( ! isset( $_POST['currency'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid currency', 'aqualuxe' ) ) );
		}

		$currency = sanitize_text_field( $_POST['currency'] );

		// Check if currency exists
		if ( ! array_key_exists( $currency, $this->currencies ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid currency', 'aqualuxe' ) ) );
		}

		// Set cookie
		$expiry = time() + ( 30 * DAY_IN_SECONDS );
		setcookie( 'aqualuxe_currency', $currency, $expiry, COOKIEPATH, COOKIE_DOMAIN );

		wp_send_json_success( array(
			'currency' => $currency,
			'symbol'   => $this->currencies[ $currency ]['symbol'],
			'name'     => $this->currencies[ $currency ]['name'],
		) );
	}

	/**
	 * Currency switcher script
	 */
	public function currency_switcher_script() {
		// Skip if WPML or WooCommerce Currency Switcher is active
		if ( $this->is_wpml_multi_currency_active() || $this->is_woocommerce_currency_switcher_active() ) {
			return;
		}

		// Skip if there's only one currency
		if ( count( $this->currencies ) <= 1 ) {
			return;
		}

		?>
		<script>
			(function($) {
				$(document).ready(function() {
					// Toggle currency list
					$('.currency-switcher .current-currency').on('click', function(e) {
						e.preventDefault();
						$('.currency-switcher .currency-list').toggleClass('active');
					});

					// Close currency list when clicking outside
					$(document).on('click', function(e) {
						if (!$(e.target).closest('.currency-switcher').length) {
							$('.currency-switcher .currency-list').removeClass('active');
						}
					});

					// Switch currency
					$('.currency-switcher .currency-list a').on('click', function(e) {
						e.preventDefault();
						
						var currency = $(this).data('currency');
						
						// Show loading
						$('body').addClass('currency-switching');
						
						// Switch currency via AJAX
						$.ajax({
							url: aqualuxeWooCommerce.ajaxUrl,
							type: 'POST',
							data: {
								action: 'aqualuxe_switch_currency',
								currency: currency,
								nonce: aqualuxeWooCommerce.nonce
							},
							success: function(response) {
								if (response.success) {
									// Reload page
									window.location.reload();
								} else {
									// Hide loading
									$('body').removeClass('currency-switching');
									
									// Show error
									console.error(response.data.message);
								}
							},
							error: function() {
								// Hide loading
								$('body').removeClass('currency-switching');
								
								// Show error
								console.error('Error switching currency');
							}
						});
					});
				});
			})(jQuery);
		</script>
		<?php
	}

	/**
	 * Check if WPML Multi-Currency is active
	 *
	 * @return bool
	 */
	public function is_wpml_multi_currency_active() {
		return class_exists( 'woocommerce_wpml' ) && class_exists( 'WCML_Multi_Currency' );
	}

	/**
	 * Check if WooCommerce Currency Switcher is active
	 *
	 * @return bool
	 */
	public function is_woocommerce_currency_switcher_active() {
		return class_exists( 'WOOCS' );
	}

	/**
	 * Get available currencies
	 *
	 * @return array
	 */
	public function get_currencies() {
		return $this->currencies;
	}

	/**
	 * Get current currency
	 *
	 * @return string
	 */
	public function get_current_currency() {
		return $this->current_currency;
	}
}

// Initialize multi-currency
$aqualuxe_multi_currency = new AquaLuxe_Multi_Currency();

/**
 * Get currency switcher
 */
function aqualuxe_currency_switcher() {
	global $aqualuxe_multi_currency;

	if ( $aqualuxe_multi_currency ) {
		$aqualuxe_multi_currency->currency_switcher();
	}
}

/**
 * Get current currency
 *
 * @return string
 */
function aqualuxe_get_current_currency() {
	global $aqualuxe_multi_currency;

	if ( $aqualuxe_multi_currency ) {
		return $aqualuxe_multi_currency->get_current_currency();
	}

	return get_woocommerce_currency();
}

/**
 * Get currency symbol
 *
 * @param string $currency Currency code
 * @return string
 */
function aqualuxe_get_currency_symbol( $currency = '' ) {
	if ( empty( $currency ) ) {
		$currency = aqualuxe_get_current_currency();
	}

	return get_woocommerce_currency_symbol( $currency );
}

/**
 * Convert price
 *
 * @param float $price Price
 * @param string $from_currency From currency
 * @param string $to_currency To currency
 * @return float
 */
function aqualuxe_convert_price( $price, $from_currency = '', $to_currency = '' ) {
	global $aqualuxe_multi_currency;

	if ( ! $aqualuxe_multi_currency ) {
		return $price;
	}

	if ( empty( $from_currency ) ) {
		$from_currency = get_option( 'woocommerce_currency' );
	}

	if ( empty( $to_currency ) ) {
		$to_currency = $aqualuxe_multi_currency->get_current_currency();
	}

	// Skip if currencies are the same
	if ( $from_currency === $to_currency ) {
		return $price;
	}

	// Get exchange rate
	$rate = $aqualuxe_multi_currency->get_exchange_rate( $from_currency, $to_currency );

	// Convert price
	$converted_price = $price * $rate;

	return $converted_price;
}

/**
 * Format price
 *
 * @param float $price Price
 * @param string $currency Currency code
 * @return string
 */
function aqualuxe_format_price( $price, $currency = '' ) {
	if ( empty( $currency ) ) {
		$currency = aqualuxe_get_current_currency();
	}

	return wc_price( $price, array( 'currency' => $currency ) );
}