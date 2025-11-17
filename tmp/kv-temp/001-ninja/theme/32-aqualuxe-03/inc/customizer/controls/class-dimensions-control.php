<?php
/**
 * AquaLuxe Theme Customizer - Dimensions Control
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AquaLuxe_Dimensions_Control' ) && class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Dimensions Control
	 */
	class AquaLuxe_Dimensions_Control extends WP_Customize_Control {

		/**
		 * The type of control being rendered
		 *
		 * @var string
		 */
		public $type = 'aqualuxe-dimensions';

		/**
		 * The dimensions to show
		 *
		 * @var array
		 */
		public $dimensions = array( 'top', 'right', 'bottom', 'left' );

		/**
		 * The unit type
		 *
		 * @var string
		 */
		public $unit = 'px';

		/**
		 * Available units
		 *
		 * @var array
		 */
		public $units = array( 'px', 'em', 'rem', '%' );

		/**
		 * Allow linking of values
		 *
		 * @var bool
		 */
		public $link = true;

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
			if ( isset( $args['dimensions'] ) ) {
				$this->dimensions = $args['dimensions'];
			}
			if ( isset( $args['unit'] ) ) {
				$this->unit = $args['unit'];
			}
			if ( isset( $args['units'] ) ) {
				$this->units = $args['units'];
			}
			if ( isset( $args['link'] ) ) {
				$this->link = $args['link'];
			}
		}

		/**
		 * Enqueue control related scripts/styles.
		 *
		 * @return void
		 */
		public function enqueue() {
			wp_enqueue_script(
				'aqualuxe-dimensions-control',
				get_template_directory_uri() . '/assets/js/customizer/dimensions-control.js',
				array( 'jquery', 'customize-base' ),
				AQUALUXE_VERSION,
				true
			);

			wp_enqueue_style(
				'aqualuxe-dimensions-control',
				get_template_directory_uri() . '/assets/css/customizer/dimensions-control.css',
				array(),
				AQUALUXE_VERSION
			);
		}

		/**
		 * Render the control in the customizer
		 */
		public function render_content() {
			$values = $this->value();
			$values = ! is_array( $values ) ? json_decode( $values, true ) : $values;
			$values = wp_parse_args(
				$values,
				array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
					'unit'   => $this->unit,
					'linked' => false,
				)
			);
			?>
			<div class="aqualuxe-dimensions-control">
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>

				<?php if ( ! empty( $this->description ) ) : ?>
					<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>

				<div class="aqualuxe-dimensions-wrapper">
					<div class="aqualuxe-dimensions-inputs">
						<?php foreach ( $this->dimensions as $dimension ) : ?>
							<div class="aqualuxe-dimension-input">
								<input type="number" 
									class="aqualuxe-dimension-field" 
									data-dimension="<?php echo esc_attr( $dimension ); ?>" 
									value="<?php echo esc_attr( $values[ $dimension ] ); ?>" 
								/>
								<span class="aqualuxe-dimension-label"><?php echo esc_html( ucfirst( $dimension ) ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>

					<?php if ( count( $this->units ) > 1 ) : ?>
						<div class="aqualuxe-dimensions-unit">
							<select class="aqualuxe-dimensions-unit-select">
								<?php foreach ( $this->units as $unit_value ) : ?>
									<option value="<?php echo esc_attr( $unit_value ); ?>" <?php selected( $values['unit'], $unit_value ); ?>>
										<?php echo esc_html( $unit_value ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php else : ?>
						<div class="aqualuxe-dimensions-unit">
							<span class="aqualuxe-dimensions-unit-text"><?php echo esc_html( $this->unit ); ?></span>
							<input type="hidden" class="aqualuxe-dimensions-unit-select" value="<?php echo esc_attr( $this->unit ); ?>" />
						</div>
					<?php endif; ?>

					<?php if ( $this->link ) : ?>
						<div class="aqualuxe-dimensions-link">
							<button type="button" class="aqualuxe-dimensions-link-button <?php echo $values['linked'] ? 'linked' : ''; ?>" title="<?php esc_attr_e( 'Link values', 'aqualuxe' ); ?>">
								<span class="dashicons dashicons-admin-links"></span>
							</button>
						</div>
					<?php endif; ?>
				</div>

				<input type="hidden" 
					id="<?php echo esc_attr( $this->id ); ?>" 
					name="<?php echo esc_attr( $this->id ); ?>" 
					value="<?php echo esc_attr( json_encode( $values ) ); ?>" 
					<?php $this->link(); ?> 
				/>
			</div>
			<script>
				jQuery(document).ready(function($) {
					// Initialize the dimensions control
					var $control = $('.aqualuxe-dimensions-control');
					var $input = $control.find('input[type="hidden"]');
					var $dimensionInputs = $control.find('.aqualuxe-dimension-field');
					var $unitSelect = $control.find('.aqualuxe-dimensions-unit-select');
					var $linkButton = $control.find('.aqualuxe-dimensions-link-button');
					
					// Update the hidden input when a dimension input changes
					$dimensionInputs.on('input change', function() {
						var values = JSON.parse($input.val());
						values[$(this).data('dimension')] = $(this).val();
						$input.val(JSON.stringify(values)).trigger('change');
					});

					// Update the hidden input when the unit changes
					$unitSelect.on('change', function() {
						var values = JSON.parse($input.val());
						values.unit = $(this).val();
						$input.val(JSON.stringify(values)).trigger('change');
					});

					// Toggle linked state
					$linkButton.on('click', function() {
						var values = JSON.parse($input.val());
						values.linked = !values.linked;
						$(this).toggleClass('linked', values.linked);
						$input.val(JSON.stringify(values)).trigger('change');
					});

					// Handle linked inputs
					$dimensionInputs.on('input', function() {
						var values = JSON.parse($input.val());
						if (values.linked) {
							var value = $(this).val();
							$dimensionInputs.not(this).val(value);
							$dimensionInputs.each(function() {
								values[$(this).data('dimension')] = value;
							});
							$input.val(JSON.stringify(values)).trigger('change');
						}
					});
				});
			</script>
			<?php
		}
	}
}