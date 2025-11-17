<?php
/**
 * AquaLuxe WooCommerce MultiCurrency Class
 *
 * @package AquaLuxe
 * @subpackage WooCommerce
 * @since 1.1.0
 */

namespace AquaLuxe\WooCommerce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce MultiCurrency Class
 *
 * Handles WooCommerce multi-currency functionality.
 *
 * @since 1.1.0
 */
class MultiCurrency {

	/**
	 * Available currencies.
	 *
	 * @var array
	 */
	private $currencies = array();

	/**
	 * Current currency.
	 *
	 * @var string
	 */
	private $current_currency = '';

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Check if multi-currency is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_multi_currency', false ) ) {
			return;
		}

		// Set currencies.
		$this->set_currencies();

		// Set current currency.
		$this->set_current_currency();
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Check if multi-currency is enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_multi_currency', false ) ) {
			return;
		}

		// Currency hooks.
		add_filter( 'woocommerce_currency', array( $this, 'get_currency' ) );
		add_filter( 'woocommerce_currency_symbol', array( $this, 'get_currency_symbol' ) );
		add_filter( 'woocommerce_price_format', array( $this, 'get_price_format' ), 10, 2 );
		
		// Price hooks.
		add_filter( 'woocommerce_product_get_price', array( $this, 'get_product_price' ), 10, 2 );
		add_filter( 'woocommerce_product_get_regular_price', array( $this, 'get_product_price' ), 10, 2 );
		add_filter( 'woocommerce_product_get_sale_price', array( $this, 'get_product_price' ), 10, 2 );
		add_filter( 'woocommerce_product_variation_get_price', array( $this, 'get_product_price' ), 10, 2 );
		add_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'get_product_price' ), 10, 2 );
		add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'get_product_price' ), 10, 2 );
		
		// Currency switcher.
		add_action( 'wp_footer', array( $this, 'currency_switcher' ) );
		add_action( 'wp_ajax_aqualuxe_switch_currency', array( $this, 'switch_currency_ajax' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_switch_currency', array( $this, 'switch_currency_ajax' ) );
		
		// Currency widget.
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
		
		// Currency shortcode.
		add_shortcode( 'aqualuxe_currency_switcher', array( $this, 'currency_switcher_shortcode' ) );
	}

	/**
	 * Set currencies.
	 *
	 * @return void
	 */
	private function set_currencies() {
		// Get base currency.
		$base_currency = get_woocommerce_currency();
		
		// Get additional currencies.
		$additional_currencies = get_theme_mod( 'aqualuxe_multi_currency_currencies', array() );
		
		// Set base currency.
		$this->currencies[ $base_currency ] = array(
			'rate'   => 1,
			'symbol' => get_woocommerce_currency_symbol( $base_currency ),
			'format' => get_woocommerce_price_format(),
		);
		
		// Set additional currencies.
		if ( ! empty( $additional_currencies ) ) {
			foreach ( $additional_currencies as $currency ) {
				$this->currencies[ $currency['code'] ] = array(
					'rate'   => floatval( $currency['rate'] ),
					'symbol' => get_woocommerce_currency_symbol( $currency['code'] ),
					'format' => $this->get_currency_format( $currency['code'] ),
				);
			}
		}
	}

	/**
	 * Set current currency.
	 *
	 * @return void
	 */
	private function set_current_currency() {
		// Get base currency.
		$base_currency = get_woocommerce_currency();
		
		// Get currency from cookie.
		$cookie_currency = isset( $_COOKIE['aqualuxe_currency'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_currency'] ) ) : '';
		
		// Check if currency is valid.
		if ( ! empty( $cookie_currency ) && isset( $this->currencies[ $cookie_currency ] ) ) {
			$this->current_currency = $cookie_currency;
		} else {
			$this->current_currency = $base_currency;
		}
	}

	/**
	 * Get currency.
	 *
	 * @param string $currency Currency.
	 * @return string
	 */
	public function get_currency( $currency ) {
		return $this->current_currency;
	}

	/**
	 * Get currency symbol.
	 *
	 * @param string $symbol Currency symbol.
	 * @return string
	 */
	public function get_currency_symbol( $symbol ) {
		return $this->currencies[ $this->current_currency ]['symbol'];
	}

	/**
	 * Get price format.
	 *
	 * @param string $format Price format.
	 * @param string $currency_pos Currency position.
	 * @return string
	 */
	public function get_price_format( $format, $currency_pos ) {
		return $this->currencies[ $this->current_currency ]['format'];
	}

	/**
	 * Get product price.
	 *
	 * @param float      $price Product price.
	 * @param WC_Product $product Product object.
	 * @return float
	 */
	public function get_product_price( $price, $product ) {
		// Check if price is empty.
		if ( empty( $price ) ) {
			return $price;
		}
		
		// Get base currency.
		$base_currency = get_woocommerce_currency();
		
		// Check if current currency is base currency.
		if ( $this->current_currency === $base_currency ) {
			return $price;
		}
		
		// Get currency rate.
		$rate = $this->currencies[ $this->current_currency ]['rate'];
		
		// Convert price.
		$price = $price * $rate;
		
		return $price;
	}

	/**
	 * Currency switcher.
	 *
	 * @return void
	 */
	public function currency_switcher() {
		// Check if currency switcher is enabled.
		if ( ! get_theme_mod( 'aqualuxe_multi_currency_switcher', true ) ) {
			return;
		}
		
		// Get currency switcher style.
		$switcher_style = get_theme_mod( 'aqualuxe_multi_currency_switcher_style', 'dropdown' );
		
		// Get currency switcher position.
		$switcher_position = get_theme_mod( 'aqualuxe_multi_currency_switcher_position', 'bottom-right' );
		
		// Output currency switcher.
		echo '<div class="currency-switcher style-' . esc_attr( $switcher_style ) . ' position-' . esc_attr( $switcher_position ) . '">';
		
		if ( 'dropdown' === $switcher_style ) {
			// Output dropdown.
			echo '<div class="currency-dropdown">';
			echo '<div class="currency-current">';
			echo '<span class="currency-symbol">' . esc_html( $this->currencies[ $this->current_currency ]['symbol'] ) . '</span>';
			echo '<span class="currency-code">' . esc_html( $this->current_currency ) . '</span>';
			echo '<span class="currency-arrow"><i class="fas fa-chevron-down"></i></span>';
			echo '</div>';
			echo '<div class="currency-options">';
			
			foreach ( $this->currencies as $code => $currency ) {
				echo '<div class="currency-option' . ( $code === $this->current_currency ? ' active' : '' ) . '" data-currency="' . esc_attr( $code ) . '">';
				echo '<span class="currency-symbol">' . esc_html( $currency['symbol'] ) . '</span>';
				echo '<span class="currency-code">' . esc_html( $code ) . '</span>';
				echo '</div>';
			}
			
			echo '</div>';
			echo '</div>';
		} else {
			// Output buttons.
			echo '<div class="currency-buttons">';
			
			foreach ( $this->currencies as $code => $currency ) {
				echo '<div class="currency-button' . ( $code === $this->current_currency ? ' active' : '' ) . '" data-currency="' . esc_attr( $code ) . '">';
				echo '<span class="currency-symbol">' . esc_html( $currency['symbol'] ) . '</span>';
				echo '<span class="currency-code">' . esc_html( $code ) . '</span>';
				echo '</div>';
			}
			
			echo '</div>';
		}
		
		echo '</div>';
	}

	/**
	 * Switch currency AJAX.
	 *
	 * @return void
	 */
	public function switch_currency_ajax() {
		// Check nonce.
		if ( ! check_ajax_referer( 'aqualuxe-woocommerce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce.', 'aqualuxe' ) ) );
		}
		
		// Check currency.
		if ( ! isset( $_POST['currency'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid currency.', 'aqualuxe' ) ) );
		}
		
		// Get currency.
		$currency = sanitize_text_field( wp_unslash( $_POST['currency'] ) );
		
		// Check if currency is valid.
		if ( ! isset( $this->currencies[ $currency ] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid currency.', 'aqualuxe' ) ) );
		}
		
		// Set cookie.
		setcookie( 'aqualuxe_currency', $currency, time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		
		wp_send_json_success( array( 'message' => esc_html__( 'Currency switched.', 'aqualuxe' ) ) );
	}

	/**
	 * Register widget.
	 *
	 * @return void
	 */
	public function register_widget() {
		register_widget( 'AquaLuxe\\Widgets\\Currency_Switcher_Widget' );
	}

	/**
	 * Currency switcher shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function currency_switcher_shortcode( $atts ) {
		// Parse attributes.
		$atts = shortcode_atts(
			array(
				'style' => 'dropdown',
			),
			$atts,
			'aqualuxe_currency_switcher'
		);
		
		// Start output buffering.
		ob_start();
		
		// Output currency switcher.
		echo '<div class="currency-switcher style-' . esc_attr( $atts['style'] ) . '">';
		
		if ( 'dropdown' === $atts['style'] ) {
			// Output dropdown.
			echo '<div class="currency-dropdown">';
			echo '<div class="currency-current">';
			echo '<span class="currency-symbol">' . esc_html( $this->currencies[ $this->current_currency ]['symbol'] ) . '</span>';
			echo '<span class="currency-code">' . esc_html( $this->current_currency ) . '</span>';
			echo '<span class="currency-arrow"><i class="fas fa-chevron-down"></i></span>';
			echo '</div>';
			echo '<div class="currency-options">';
			
			foreach ( $this->currencies as $code => $currency ) {
				echo '<div class="currency-option' . ( $code === $this->current_currency ? ' active' : '' ) . '" data-currency="' . esc_attr( $code ) . '">';
				echo '<span class="currency-symbol">' . esc_html( $currency['symbol'] ) . '</span>';
				echo '<span class="currency-code">' . esc_html( $code ) . '</span>';
				echo '</div>';
			}
			
			echo '</div>';
			echo '</div>';
		} else {
			// Output buttons.
			echo '<div class="currency-buttons">';
			
			foreach ( $this->currencies as $code => $currency ) {
				echo '<div class="currency-button' . ( $code === $this->current_currency ? ' active' : '' ) . '" data-currency="' . esc_attr( $code ) . '">';
				echo '<span class="currency-symbol">' . esc_html( $currency['symbol'] ) . '</span>';
				echo '<span class="currency-code">' . esc_html( $code ) . '</span>';
				echo '</div>';
			}
			
			echo '</div>';
		}
		
		echo '</div>';
		
		// Return output.
		return ob_get_clean();
	}

	/**
	 * Get currency format.
	 *
	 * @param string $currency Currency.
	 * @return string
	 */
	private function get_currency_format( $currency ) {
		// Get currency position.
		$position = get_theme_mod( 'aqualuxe_multi_currency_' . $currency . '_position', 'left' );
		
		// Get currency format.
		switch ( $position ) {
			case 'left':
				$format = '%1$s%2$s';
				break;
			case 'right':
				$format = '%2$s%1$s';
				break;
			case 'left_space':
				$format = '%1$s&nbsp;%2$s';
				break;
			case 'right_space':
				$format = '%2$s&nbsp;%1$s';
				break;
			default:
				$format = '%1$s%2$s';
				break;
		}
		
		return $format;
	}
}