<?php
/**
 * AquaLuxe Divider Control
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
function aqualuxe_register_divider_control() {
	/**
	 * Divider Control Class
	 * 
	 * A custom control for displaying dividers in the customizer.
	 */
	class AquaLuxe_Divider_Control extends WP_Customize_Control {
		/**
		 * Control type
		 *
		 * @var string
		 */
		public $type = 'aqualuxe-divider';

		/**
		 * Render the control's content.
		 */
		public function render_content() {
			?>
			<div class="aqualuxe-divider-control">
				<hr>
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="aqualuxe-divider-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $this->description ) ) : ?>
					<span class="aqualuxe-divider-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>
			</div>
			<?php
		}

		/**
		 * Enqueue control related scripts/styles.
		 */
		public function enqueue() {
			wp_enqueue_style(
				'aqualuxe-divider-control',
				get_template_directory_uri() . '/assets/css/customizer/divider-control.css',
				array(),
				AQUALUXE_VERSION
			);
		}
	}
}
add_action( 'customize_register', 'aqualuxe_register_divider_control', 0 );