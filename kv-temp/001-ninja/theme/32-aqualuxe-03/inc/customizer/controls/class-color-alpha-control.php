<?php
/**
 * AquaLuxe Theme Customizer - Color Alpha Control
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AquaLuxe_Color_Alpha_Control' ) && class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Color Alpha Control
	 */
	class AquaLuxe_Color_Alpha_Control extends WP_Customize_Control {

		/**
		 * The type of control being rendered
		 *
		 * @var string
		 */
		public $type = 'aqualuxe-color-alpha';

		/**
		 * Add support for palettes to be passed in.
		 *
		 * @var array
		 */
		public $palette;

		/**
		 * Add support for showing the opacity value on the slider handle.
		 *
		 * @var bool
		 */
		public $show_opacity;

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @return void
		 */
		public function enqueue() {
			wp_enqueue_script(
				'wp-color-picker-alpha',
				get_template_directory_uri() . '/assets/js/customizer/wp-color-picker-alpha.js',
				array( 'wp-color-picker' ),
				AQUALUXE_VERSION,
				true
			);

			wp_enqueue_style(
				'aqualuxe-color-alpha-control',
				get_template_directory_uri() . '/assets/css/customizer/color-alpha-control.css',
				array( 'wp-color-picker' ),
				AQUALUXE_VERSION
			);
		}

		/**
		 * Render the control in the customizer
		 */
		public function render_content() {
			// Process the palette.
			if ( is_array( $this->palette ) ) {
				$palette = implode( '|', $this->palette );
			} else {
				// Default palette.
				$palette = '#000000|#ffffff|#dd3333|#dd9933|#eeee22|#81d742|#1e73be|#8224e3';
			}

			// Support passing show_opacity as string or boolean.
			$show_opacity = ( false === $this->show_opacity || 'false' === $this->show_opacity ) ? 'false' : 'true';
			?>
			<div class="aqualuxe-color-alpha-control">
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>

				<?php if ( ! empty( $this->description ) ) : ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>

				<div class="aqualuxe-color-alpha-wrapper">
					<input type="text" 
						class="aqualuxe-color-alpha-control-field" 
						data-palette="<?php echo esc_attr( $palette ); ?>" 
						data-show-opacity="<?php echo esc_attr( $show_opacity ); ?>" 
						data-default-color="<?php echo esc_attr( $this->settings['default']->default ); ?>" 
						<?php $this->link(); ?> 
						value="<?php echo esc_attr( $this->value() ); ?>" 
					/>
				</div>
			</div>
			<script>
				jQuery(document).ready(function($) {
					$('.aqualuxe-color-alpha-control-field').wpColorPicker({
						change: function(event, ui) {
							// Update the value and trigger the change event.
							$(this).val(ui.color.toString()).trigger('change');
						},
						clear: function() {
							// Update the value and trigger the change event.
							$(this).val('').trigger('change');
						}
					});
				});
			</script>
			<?php
		}
	}
}