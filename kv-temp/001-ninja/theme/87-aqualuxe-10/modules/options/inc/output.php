<?php
/**
 * Handles generating dynamic CSS from theme options.
 *
 * @package Aqualuxe
 * @subpackage Modules/Options
 */

namespace Aqualuxe\Modules\Options;

use Aqualuxe\Aqualuxe;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates the dynamic CSS.
 *
 * @return string The generated CSS.
 */
function generate_css(): string {
	$css = '';
	/** @var \Aqualuxe\Core\Services\OptionsService $options_service */
	$options_service = Aqualuxe::get_container()->get( 'options' );

	$primary_color = $options_service->get( 'primary_color', '#0073aa' );

	if ( $primary_color !== '#0073aa' ) {
		$css .= ":root { --aqualuxe-primary-color: {$primary_color}; }";
		// Example of how to use this variable in other CSS files:
		// .some-element { color: var(--aqualuxe-primary-color); }
	}

	return $css;
}

/**
 * Adds the dynamic CSS to the site head.
 */
function output_css(): void {
	$css = generate_css();
	if ( ! empty( $css ) ) {
		echo '<style type="text/css" id="aqualuxe-dynamic-css">' . wp_strip_all_tags( $css ) . '</style>';
	}
}
add_action( 'wp_head', __NAMESPACE__ . '\output_css' );
