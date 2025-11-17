<?php
/**
 * AquaLuxe Theme Customizer - Slider Control
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AquaLuxe_Slider_Control' ) && class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Slider Control
	 */
	class AquaLuxe_Slider_Control extends WP_Customize_Control {

		/**
		 * The type of control being rendered
		 *
		 * @var string
		 */
		public $type = 'aqualuxe-slider';

		/**
		 * Minimum value
		 *
		 * @var integer
		 */
		public $min = 0;

		/**
		 * Maximum value
		 *
		 * @var integer
		 */
		public $max = 100;

		/**
		 * Step size
		 *
		 * @var integer
		 */
		public $step = 1;

		/**
		 * Unit type
		 *
		 * @var string
		 */
		public $unit = '';

		/**
		 * Constructor
		 *
		 * @param WP_Customize_Manager $manager Customizer manager.
		 * @param string               $id      Control ID.
		 * @param array                $args    Control arguments.
		 */
		public function __construct( $manager, $id, $args = array() ) {
			parent::__construct( $manager, $id, $args );

			// Set our custom control properties.
			if ( isset( $args['min'] ) ) {
				$this->min = $args['min'];
			}
			if ( isset( $args['max'] ) ) {
				$this->max = $args['max'];
			}
			if ( isset( $args['step'] ) ) {
				$this->step = $args['step'];
			}
			if ( isset( $args['unit'] ) ) {
				$this->unit = $args['unit'];
			}
		}

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @return void
		 */
		public function enqueue() {
			wp_enqueue_script(
				'aqualuxe-slider-control',
				get_template_directory_uri() . '/assets/js/customizer/slider-control.js',
				array( 'jquery', 'customize-base' ),
				AQUALUXE_VERSION,
				true
			);

			wp_enqueue_style(
				'aqualuxe-slider-control',
				get_template_directory_uri() . '/assets/css/customizer/slider-control.css',
				array(),
				AQUALUXE_VERSION
			);
		}

		/**
		 * Render the control in the customizer
		 */
		public function render_content() {
			?>
			<div class="aqualuxe-slider-control">
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>

				<?php if ( ! empty( $this->description ) ) : ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>

				<div class="aqualuxe-slider-control-wrapper">
					<input type="range" class="aqualuxe-slider" 
						min="<?php echo esc_attr( $this->min ); ?>" 
						max="<?php echo esc_attr( $this->max ); ?>" 
						step="<?php echo esc_attr( $this->step ); ?>" 
						value="<?php echo esc_attr( $this->value() ); ?>" 
						<?php $this->link(); ?> 
					/>
					<div class="aqualuxe-slider-value">
						<input type="number" class="aqualuxe-slider-input" 
							min="<?php echo esc_attr( $this->min ); ?>" 
							max="<?php echo esc_attr( $this->max ); ?>" 
							step="<?php echo esc_attr( $this->step ); ?>" 
							value="<?php echo esc_attr( $this->value() ); ?>" 
						/>
						<?php if ( ! empty( $this->unit ) ) : ?>
							<span class="aqualuxe-slider-unit"><?php echo esc_html( $this->unit ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<script>
				jQuery(document).ready(function($) {
					// Update the number input when the range slider changes
					$('.aqualuxe-slider').on('input change', function() {
						$(this).closest('.aqualuxe-slider-control-wrapper').find('.aqualuxe-slider-input').val($(this).val());
					});

					// Update the range slider when the number input changes
					$('.aqualuxe-slider-input').on('input change', function() {
						$(this).closest('.aqualuxe-slider-control-wrapper').find('.aqualuxe-slider').val($(this).val()).trigger('change');
					});
				});
			</script>
			<?php
		}
	}
}