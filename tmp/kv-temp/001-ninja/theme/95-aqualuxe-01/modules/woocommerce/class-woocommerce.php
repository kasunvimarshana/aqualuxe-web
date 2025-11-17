<?php
/**
 * WooCommerce Integration for AquaLuxe.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The main WooCommerce integration class.
 */
class AquaLuxe_WooCommerce {

	/**
	 * Initialize the WooCommerce integration.
	 */
	public static function init() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_styles' ) );
		add_filter( 'woocommerce_locate_template', array( __CLASS__, 'locate_template' ), 10, 3 );
	}

	/**
	 * Enqueue WooCommerce specific styles.
	 */
	public static function enqueue_styles() {
		wp_enqueue_style(
			'aqualuxe-woocommerce',
			get_template_directory_uri() . '/modules/woocommerce/woocommerce.css',
			array(),
			'1.0.0'
		);
	}

	/**
	 * Locate template overrides.
	 *
	 * @param string $template      Template.
	 * @param string $template_name Template name.
	 * @param string $template_path Template path.
	 * @return string
	 */
	public static function locate_template( $template, $template_name, $template_path ) {
		$template_override = get_template_directory() . '/modules/woocommerce/templates/' . $template_name;

		if ( file_exists( $template_override ) ) {
			return $template_override;
		}

		return $template;
	}
}
