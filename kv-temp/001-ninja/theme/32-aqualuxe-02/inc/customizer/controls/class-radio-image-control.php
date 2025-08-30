<?php
/**
 * AquaLuxe Theme Customizer - Radio Image Control
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AquaLuxe_Radio_Image_Control' ) && class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Radio Image Control
	 */
	class AquaLuxe_Radio_Image_Control extends WP_Customize_Control {

		/**
		 * The type of control being rendered
		 *
		 * @var string
		 */
		public $type = 'aqualuxe-radio-image';

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @return void
		 */
		public function enqueue() {
			wp_enqueue_style(
				'aqualuxe-radio-image-control',
				get_template_directory_uri() . '/assets/css/customizer/radio-image-control.css',
				array(),
				AQUALUXE_VERSION
			);
		}

		/**
		 * Render the control in the customizer
		 */
		public function render_content() {
			if ( empty( $this->choices ) ) {
				return;
			}
			?>
			<div class="aqualuxe-radio-image-control">
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>

				<?php if ( ! empty( $this->description ) ) : ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>

				<div class="aqualuxe-radio-image-buttons">
					<?php foreach ( $this->choices as $value => $args ) : ?>
						<?php
						$label = isset( $args['label'] ) ? $args['label'] : '';
						$image = isset( $args['image'] ) ? $args['image'] : '';
						?>
						<label class="aqualuxe-radio-image-label">
							<input type="radio" 
								name="<?php echo esc_attr( $this->id ); ?>" 
								value="<?php echo esc_attr( $value ); ?>" 
								<?php $this->link(); ?> 
								<?php checked( $this->value(), $value ); ?> 
							/>
							<span class="aqualuxe-radio-image-item">
								<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $label ); ?>" />
								<span class="aqualuxe-radio-image-title"><?php echo esc_html( $label ); ?></span>
							</span>
						</label>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		}
	}
}