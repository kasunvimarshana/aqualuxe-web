<?php
/**
 * Aqualuxe Heading Customizer Control
 *
 * @package Aqualuxe\Customizer\Controls
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

/**
 * Heading Control for the WordPress Customizer.
 */
class Aqualuxe_Heading_Control extends WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'aqualuxe-heading';

	/**
	 * Heading text.
	 *
	 * @var string
	 */
	public $label = '';

	/**
	 * Render the control's content.
	 */
	public function render_content() {
		if ( ! empty( $this->label ) ) {
			echo '<h2 class="aqualuxe-customizer-heading" style="margin: 20px 0 10px; font-size: 1.2em; font-weight: bold;">' . esc_html( $this->label ) . '</h2>';
		}
		if ( ! empty( $this->description ) ) {
			echo '<p class="description">' . esc_html( $this->description ) . '</p>';
		}
	}
}
