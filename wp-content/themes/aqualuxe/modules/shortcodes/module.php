<?php
/**
 * Module: Shortcodes
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Module_Shortcodes class.
 */
class AquaLuxe_Module_Shortcodes {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_shortcode( 'aqualuxe_placeholder', array( $this, 'placeholder_shortcode' ) );
	}

	/**
	 * Placeholder shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function placeholder_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'content_id' => '',
			),
			$atts,
			'aqualuxe_placeholder'
		);

		$content = '';

		switch ( $atts['content_id'] ) {
			case 'home':
				$content = '<!-- Placeholder for home page content -->';
				break;
			case 'projects_gallery':
				$content = '<!-- Placeholder for projects gallery -->';
				break;
			case 'contact_form':
				$content = '<!-- Placeholder for contact form -->';
				break;
		}

		return $content;
	}
}
