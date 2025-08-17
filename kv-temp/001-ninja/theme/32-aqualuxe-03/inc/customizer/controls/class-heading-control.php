<?php
/**
 * AquaLuxe Heading Control
 *
 * @package AquaLuxe
 * @subpackage Customizer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register the custom control class
 */
function aqualuxe_register_heading_control() {
	/**
	 * Heading Control Class
	 * 
	 * A custom control for displaying headings in the customizer.
	 */
	class AquaLuxe_Heading_Control extends WP_Customize_Control {
		/**
		 * Control type
		 *
		 * @var string
		 */
		public $type = 'aqualuxe-heading';

		/**
		 * Render the control's content.
		 */
		public function render_content() {
			?>
			<div class="aqualuxe-heading-control">
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="aqualuxe-heading-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $this->description ) ) : ?>
					<span class="aqualuxe-heading-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>
			</div>
			<?php
		}

		/**
		 * Enqueue control related scripts/styles.
		 */
		public function enqueue() {
			wp_enqueue_style(
				'aqualuxe-heading-control',
				get_template_directory_uri() . '/assets/css/customizer/heading-control.css',
				array(),
				AQUALUXE_VERSION
			);
		}
	}
}
add_action( 'customize_register', 'aqualuxe_register_heading_control', 0 );