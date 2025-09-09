<?php
/**
 * Multi-currency Integration for AquaLuxe.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The main multi-currency integration class.
 */
class AquaLuxe_Multi_Currency {

	/**
	 * Initialize the multi-currency integration.
	 */
	public static function init() {
		// We will use the "WooCommerce Currency Switcher" plugin for this.
		if ( ! class_exists( 'WOOCS' ) ) {
			return;
		}

		add_action( 'aqualuxe_header_top', array( __CLASS__, 'add_currency_switcher' ) );
	}

	/**
	 * Add a currency switcher to the header.
	 */
	public static function add_currency_switcher() {
		echo '<div class="aqualuxe-currency-switcher">';
		echo do_shortcode( '[woocs]' );
		echo '</div>';
	}
}
