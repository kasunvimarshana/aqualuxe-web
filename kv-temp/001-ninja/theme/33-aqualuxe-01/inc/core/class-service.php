<?php
/**
 * Service Base Class
 *
 * @package AquaLuxe
 * @subpackage Core
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Service Base Class
 *
 * This class serves as the base for all service classes.
 */
abstract class Service {

	/**
	 * Initialize the service
	 *
	 * @return void
	 */
	abstract public function initialize();

	/**
	 * Register hooks
	 *
	 * @return void
	 */
	abstract public function register_hooks();

	/**
	 * Sanitize checkbox
	 *
	 * @param bool $checked Whether the checkbox is checked.
	 * @return bool
	 */
	protected function sanitize_checkbox( $checked ) {
		return ( isset( $checked ) && true === $checked ) ? true : false;
	}

	/**
	 * Sanitize number
	 *
	 * @param int $number Number to sanitize.
	 * @param int $min Minimum value.
	 * @param int $max Maximum value.
	 * @return int
	 */
	protected function sanitize_number( $number, $min = 0, $max = 100 ) {
		return max( $min, min( $max, absint( $number ) ) );
	}

	/**
	 * Sanitize float
	 *
	 * @param float $number Number to sanitize.
	 * @param float $min Minimum value.
	 * @param float $max Maximum value.
	 * @return float
	 */
	protected function sanitize_float( $number, $min = 0, $max = 100 ) {
		return max( $min, min( $max, floatval( $number ) ) );
	}

	/**
	 * Sanitize select
	 *
	 * @param string $input Select value.
	 * @param array  $valid_values Valid values.
	 * @param string $default Default value.
	 * @return string
	 */
	protected function sanitize_select( $input, $valid_values, $default = '' ) {
		if ( in_array( $input, $valid_values, true ) ) {
			return $input;
		}
		return $default;
	}

	/**
	 * Sanitize html
	 *
	 * @param string $html HTML to sanitize.
	 * @return string
	 */
	protected function sanitize_html( $html ) {
		return wp_kses_post( $html );
	}

	/**
	 * Verify nonce
	 *
	 * @param string $nonce Nonce to verify.
	 * @param string $action Action name.
	 * @return bool
	 */
	protected function verify_nonce( $nonce, $action ) {
		if ( ! isset( $nonce ) ) {
			return false;
		}
		return wp_verify_nonce( $nonce, $action );
	}

	/**
	 * Check if user has capability
	 *
	 * @param string $capability Capability to check.
	 * @return bool
	 */
	protected function current_user_can( $capability ) {
		return current_user_can( $capability );
	}

	/**
	 * Check if AJAX request
	 *
	 * @return bool
	 */
	protected function is_ajax() {
		return wp_doing_ajax();
	}

	/**
	 * Check if REST request
	 *
	 * @return bool
	 */
	protected function is_rest() {
		return defined( 'REST_REQUEST' ) && REST_REQUEST;
	}

	/**
	 * Check if WooCommerce is active
	 *
	 * @return bool
	 */
	protected function is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Get theme option
	 *
	 * @param string $option Option name.
	 * @param mixed  $default Default value.
	 * @return mixed
	 */
	protected function get_theme_option( $option, $default = '' ) {
		return get_theme_mod( $option, $default );
	}
}