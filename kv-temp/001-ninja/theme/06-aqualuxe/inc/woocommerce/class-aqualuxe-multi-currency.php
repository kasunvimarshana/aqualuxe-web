<?php
/**
 * AquaLuxe Multi-Currency Support
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Multi-Currency Class
 */
class AquaLuxe_Multi_Currency {
	/**
	 * Constructor
	 */
	public function __construct() {
		// Check if multi-currency is enabled
		if ( ! get_theme_mod( 'aqualuxe_multi_currency', false ) ) {
			return;
		}

		// Add currency switcher
		add_action( 'wp_head', array( $this, 'add_currency_switcher_styles' ) );
		add_action( 'woocommerce_before_shop_loop', array( $this, 'add_currency_switcher' ), 15 );
		add_action( 'woocommerce_before_single_product', array( $this, 'add_currency_switcher' ), 5 );
		add_action( 'woocommerce_before_cart', array( $this, 'add_currency_switcher' ), 5 );
		add_action( 'woocommerce_before_checkout_form', array( $this, 'add_currency_switcher' ), 5 );

		// Handle currency switching
		add_filter( 'woocommerce_currency', array( $this, 'change_currency' ) );
		add_filter( 'woocommerce_currency_symbol', array( $this, 'change_currency_symbol' ), 10, 2 );
		
		// Handle price conversion
		add_filter( 'woocommerce_product_get_price', array( $this, 'convert_price' ), 10, 2 );
		add_filter( 'woocommerce_product_get_regular_price', array( $this, 'convert_price' ), 10, 2 );
		add_filter( 'woocommerce_product_get_sale_price', array( $this, 'convert_price' ), 10, 2 );
		add_filter( 'woocommerce_product_variation_get_price', array( $this, 'convert_price' ), 10, 2 );
		add_filter( 'woocommerce_product_variation_get_regular_price', array( $this, 'convert_price' ), 10, 2 );
		add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'convert_price' ), 10, 2 );
		
		// Handle price display
		add_filter( 'woocommerce_get_price_html', array( $this, 'price_html' ), 10, 2 );
		
		// Handle AJAX currency switching
		add_action( 'wp_ajax_aqualuxe_switch_currency', array( $this, 'ajax_switch_currency' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_switch_currency', array( $this, 'ajax_switch_currency' ) );
		
		// Enqueue scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Add currency switcher styles
	 */
	public function add_currency_switcher_styles() {
		?>
		<style>
			.aqualuxe-currency-switcher {
				margin-bottom: 20px;
				display: inline-block;
				position: relative;
			}
			
			.aqualuxe-currency-switcher select {
				padding: 8px 12px;
				border: 1px solid #ddd;
				border-radius: 4px;
				background-color: #fff;
				font-size: 14px;
				font-weight: 500;
				color: #333;
				cursor: pointer;
				min-width: 120px;
				appearance: none;
				-webkit-appearance: none;
				-moz-appearance: none;
				background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="6" viewBox="0 0 12 6"><path fill="%23333" d="M0 0l6 6 6-6z"/></svg>');
				background-repeat: no-repeat;
				background-position: right 10px center;
				padding-right: 30px;
			}
			
			.aqualuxe-currency-switcher select:focus {
				outline: none;
				border-color: #999;
				box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
			}
			
			.aqualuxe-currency-switcher label {
				display: block;
				margin-bottom: 5px;
				font-weight: 500;
				font-size: 14px;
				color: #333;
			}
			
			.aqualuxe-currency-notice {
				margin-top: 5px;
				font-size: 12px;
				color: #666;
				font-style: italic;
			}
			
			.woocommerce-checkout .aqualuxe-currency-switcher {
				margin-bottom: 30px;
			}
			
			.woocommerce-cart .aqualuxe-currency-switcher {
				float: right;
				margin-bottom: 20px;
			}
			
			@media (max-width: 768px) {
				.woocommerce-cart .aqualuxe-currency-switcher {
					float: none;
					margin-bottom: 20px;
				}
			}
		</style>
		<?php
	}

	/**
	 * Add currency switcher
	 */
	public function add_currency_switcher() {
		$currencies = $this->get_available_currencies();
		$current_currency = $this->get_current_currency();
		$base_currency = get_option( 'woocommerce_currency' );
		
		?>
		<div class="aqualuxe-currency-switcher">
			<label for="aqualuxe-currency"><?php esc_html_e( 'Currency', 'aqualuxe' ); ?></label>
			<select name="aqualuxe-currency" id="aqualuxe-currency" data-nonce="<?php echo esc_attr( wp_create_nonce( 'aqualuxe-currency-nonce' ) ); ?>">
				<?php foreach ( $currencies as $code => $name ) : ?>
					<option value="<?php echo esc_attr( $code ); ?>" <?php selected( $current_currency, $code ); ?>>
						<?php echo esc_html( $name . ' (' . get_woocommerce_currency_symbol( $code ) . ')' ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<?php if ( $current_currency !== $base_currency ) : ?>
				<div class="aqualuxe-currency-notice">
					<?php 
					printf(
						/* translators: %s: currency code */
						esc_html__( 'Prices are approximate conversions from %s', 'aqualuxe' ),
						esc_html( $base_currency )
					); 
					?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Get available currencies
	 *
	 * @return array
	 */
	public function get_available_currencies() {
		$currencies = array();
		$wc_currencies = get_woocommerce_currencies();
		
		// Get enabled currencies from theme mod
		$enabled_currencies = get_theme_mod( 'aqualuxe_enabled_currencies', array( 'USD', 'EUR', 'GBP' ) );
		
		if ( ! is_array( $enabled_currencies ) ) {
			$enabled_currencies = array( 'USD', 'EUR', 'GBP' );
		}
		
		// Always include base currency
		$base_currency = get_option( 'woocommerce_currency' );
		if ( ! in_array( $base_currency, $enabled_currencies, true ) ) {
			$enabled_currencies[] = $base_currency;
		}
		
		foreach ( $enabled_currencies as $currency ) {
			if ( isset( $wc_currencies[ $currency ] ) ) {
				$currencies[ $currency ] = $wc_currencies[ $currency ];
			}
		}
		
		return $currencies;
	}

	/**
	 * Get current currency
	 *
	 * @return string
	 */
	public function get_current_currency() {
		$base_currency = get_option( 'woocommerce_currency' );
		
		if ( isset( $_COOKIE['aqualuxe_currency'] ) ) {
			$currency = sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_currency'] ) );
			
			// Verify currency is valid
			$available_currencies = $this->get_available_currencies();
			if ( isset( $available_currencies[ $currency ] ) ) {
				return $currency;
			}
		}
		
		return $base_currency;
	}

	/**
	 * Change currency
	 *
	 * @param string $currency Currency code.
	 * @return string
	 */
	public function change_currency( $currency ) {
		return $this->get_current_currency();
	}

	/**
	 * Change currency symbol
	 *
	 * @param string $symbol Currency symbol.
	 * @param string $currency Currency code.
	 * @return string
	 */
	public function change_currency_symbol( $symbol, $currency ) {
		$current_currency = $this->get_current_currency();
		
		if ( $currency !== $current_currency ) {
			return get_woocommerce_currency_symbol( $current_currency );
		}
		
		return $symbol;
	}

	/**
	 * Convert price
	 *
	 * @param float $price Price.
	 * @param object $product Product object.
	 * @return float
	 */
	public function convert_price( $price, $product ) {
		// Skip if price is empty
		if ( '' === $price || 0 === $price ) {
			return $price;
		}
		
		$base_currency = get_option( 'woocommerce_currency' );
		$current_currency = $this->get_current_currency();
		
		// Skip if currency is the same
		if ( $base_currency === $current_currency ) {
			return $price;
		}
		
		// Get exchange rate
		$exchange_rate = $this->get_exchange_rate( $current_currency );
		
		// Convert price
		$converted_price = $price * $exchange_rate;
		
		return $converted_price;
	}

	/**
	 * Get exchange rate
	 *
	 * @param string $currency Currency code.
	 * @return float
	 */
	public function get_exchange_rate( $currency ) {
		$base_currency = get_option( 'woocommerce_currency' );
		
		// Return 1 if currency is the same
		if ( $base_currency === $currency ) {
			return 1;
		}
		
		// Get exchange rate from theme mod
		$exchange_rate = get_theme_mod( 'aqualuxe_exchange_rate_' . $currency, 1 );
		
		return floatval( $exchange_rate );
	}

	/**
	 * Price HTML
	 *
	 * @param string $price_html Price HTML.
	 * @param object $product Product object.
	 * @return string
	 */
	public function price_html( $price_html, $product ) {
		$base_currency = get_option( 'woocommerce_currency' );
		$current_currency = $this->get_current_currency();
		
		// Skip if currency is the same
		if ( $base_currency === $current_currency ) {
			return $price_html;
		}
		
		// Add approximate price notice
		$price_html .= ' <small class="aqualuxe-approximate-price">(' . esc_html__( 'approx.', 'aqualuxe' ) . ')</small>';
		
		return $price_html;
	}

	/**
	 * AJAX switch currency
	 */
	public function ajax_switch_currency() {
		// Check nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-currency-nonce' ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ) );
		}
		
		// Get currency
		$currency = isset( $_POST['currency'] ) ? sanitize_text_field( wp_unslash( $_POST['currency'] ) ) : '';
		
		if ( ! $currency ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid currency', 'aqualuxe' ) ) );
		}
		
		// Verify currency is valid
		$available_currencies = $this->get_available_currencies();
		if ( ! isset( $available_currencies[ $currency ] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Invalid currency', 'aqualuxe' ) ) );
		}
		
		// Set cookie
		setcookie( 'aqualuxe_currency', $currency, time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
		
		wp_send_json_success( array( 'message' => esc_html__( 'Currency switched successfully', 'aqualuxe' ) ) );
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
		// Enqueue currency switcher script
		wp_enqueue_script(
			'aqualuxe-currency-switcher',
			AQUALUXE_URI . 'assets/js/currency-switcher.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
		
		// Localize script
		wp_localize_script(
			'aqualuxe-currency-switcher',
			'aqualuxeCurrency',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'i18n'    => array(
					'switching'   => esc_html__( 'Switching currency...', 'aqualuxe' ),
					'switchError' => esc_html__( 'Error switching currency', 'aqualuxe' ),
				),
			)
		);
	}
}

// Initialize the class
new AquaLuxe_Multi_Currency();