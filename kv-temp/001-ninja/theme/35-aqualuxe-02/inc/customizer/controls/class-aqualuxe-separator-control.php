<?php
/**
 * Aqualuxe Separator Customizer Control
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
 * Separator Control for the WordPress Customizer.
 */
class Aqualuxe_Separator_Control extends WP_Customize_Control {
	/**
	 * Control type.
	 *
	 * @var string
	 */
	public $type = 'aqualuxe-separator';

	/**
	 * Render the control's content.
	 */
	public function render_content() {
		echo '<hr class="aqualuxe-customizer-separator" style="margin: 20px 0; border: none; border-top: 1px solid #e5e5e5;">';
	}
}
