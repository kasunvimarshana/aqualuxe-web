<?php
/**
 * Slider Control
 *
 * Customizer control for numeric values with a slider interface.
 *
 * @package AquaLuxe
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

/**
 * Slider Control Class
 */
class AquaLuxe_Slider_Control extends WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'aqualuxe-slider';

	/**
	 * Minimum value
	 *
	 * @var int
	 */
	public $min = 0;

	/**
	 * Maximum value
	 *
	 * @var int
	 */
	public $max = 100;

	/**
	 * Step size
	 *
	 * @var int
	 */
	public $step = 1;

	/**
	 * Unit label
	 *
	 * @var string
	 */
	public $unit = '';

	/**
	 * Reset value
	 *
	 * @var int
	 */
	public $default = 0;

	/**
	 * Tooltip text
	 *
	 * @var string
	 */
	public $tooltip = '';

	/**
	 * Enqueue control scripts and styles.
	 */
	public function enqueue() {
		wp_enqueue_style(
			'aqualuxe-slider-control',
			get_template_directory_uri() . '/assets/css/admin/customizer-controls.css',
			array(),
			AQUALUXE_VERSION
		);

		wp_enqueue_script(
			'aqualuxe-slider-control',
			get_template_directory_uri() . '/assets/js/admin/customizer-controls.js',
			array( 'jquery', 'customize-base', 'jquery-ui-slider' ),
			AQUALUXE_VERSION,
			true
		);
	}

	/**
	 * Render control content.
	 */
	public function render_content() {
		$input_id = '_customize-input-' . $this->id;
		$input_attrs = '';
		
		foreach ( $this->input_attrs as $attr => $value ) {
			$input_attrs .= $attr . '="' . esc_attr( $value ) . '" ';
		}
		?>
		<?php if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php endif; ?>

		<?php if ( ! empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
		<?php endif; ?>

		<?php if ( ! empty( $this->tooltip ) ) : ?>
			<div class="aqualuxe-tooltip">
				<span class="aqualuxe-tooltip-icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
						<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-4h2v2h-2zm0-10h2v8h-2z"/>
					</svg>
				</span>
				<span class="aqualuxe-tooltip-content"><?php echo wp_kses_post( $this->tooltip ); ?></span>
			</div>
		<?php endif; ?>

		<div class="aqualuxe-slider-control">
			<div class="aqualuxe-slider-wrapper">
				<div class="aqualuxe-slider" 
					data-min="<?php echo esc_attr( $this->min ); ?>" 
					data-max="<?php echo esc_attr( $this->max ); ?>" 
					data-step="<?php echo esc_attr( $this->step ); ?>" 
					data-default="<?php echo esc_attr( $this->default ); ?>"
					data-value="<?php echo esc_attr( $this->value() ); ?>">
				</div>
				<div class="aqualuxe-slider-input-wrapper">
					<input
						id="<?php echo esc_attr( $input_id ); ?>"
						type="number"
						class="aqualuxe-slider-input"
						value="<?php echo esc_attr( $this->value() ); ?>"
						min="<?php echo esc_attr( $this->min ); ?>"
						max="<?php echo esc_attr( $this->max ); ?>"
						step="<?php echo esc_attr( $this->step ); ?>"
						<?php echo $input_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php $this->link(); ?>
					/>
					<?php if ( ! empty( $this->unit ) ) : ?>
						<span class="aqualuxe-slider-unit"><?php echo esc_html( $this->unit ); ?></span>
					<?php endif; ?>
				</div>
			</div>
			<?php if ( ! empty( $this->default ) ) : ?>
				<button type="button" class="aqualuxe-slider-reset" title="<?php esc_attr_e( 'Reset to default', 'aqualuxe' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
						<path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
					</svg>
				</button>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * JSON data for the control.
	 *
	 * @return array Control data.
	 */
	public function json() {
		$json = parent::json();
		$json['min'] = $this->min;
		$json['max'] = $this->max;
		$json['step'] = $this->step;
		$json['unit'] = $this->unit;
		$json['default'] = $this->default;
		$json['tooltip'] = $this->tooltip;
		return $json;
	}
}