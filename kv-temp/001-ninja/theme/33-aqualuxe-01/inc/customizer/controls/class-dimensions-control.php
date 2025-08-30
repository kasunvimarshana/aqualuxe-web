<?php
/**
 * Dimensions Control
 *
 * Customizer control for dimension values (width, height, padding, margin, etc.).
 *
 * @package AquaLuxe
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

/**
 * Dimensions Control Class
 */
class AquaLuxe_Dimensions_Control extends WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'aqualuxe-dimensions';

	/**
	 * Dimension fields
	 *
	 * @var array
	 */
	public $fields = array(
		'top'    => true,
		'right'  => true,
		'bottom' => true,
		'left'   => true,
	);

	/**
	 * Field labels
	 *
	 * @var array
	 */
	public $field_labels = array(
		'top'    => 'Top',
		'right'  => 'Right',
		'bottom' => 'Bottom',
		'left'   => 'Left',
	);

	/**
	 * Units
	 *
	 * @var array
	 */
	public $units = array(
		'px'  => 'px',
		'em'  => 'em',
		'rem' => 'rem',
		'%'   => '%',
	);

	/**
	 * Default unit
	 *
	 * @var string
	 */
	public $default_unit = 'px';

	/**
	 * Default values
	 *
	 * @var array
	 */
	public $default = array(
		'top'    => '',
		'right'  => '',
		'bottom' => '',
		'left'   => '',
		'unit'   => 'px',
		'linked' => false,
	);

	/**
	 * Allow linking values
	 *
	 * @var bool
	 */
	public $allow_linking = true;

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
			'aqualuxe-dimensions-control',
			get_template_directory_uri() . '/assets/css/admin/customizer-controls.css',
			array(),
			AQUALUXE_VERSION
		);

		wp_enqueue_script(
			'aqualuxe-dimensions-control',
			get_template_directory_uri() . '/assets/js/admin/customizer-controls.js',
			array( 'jquery', 'customize-base' ),
			AQUALUXE_VERSION,
			true
		);
	}

	/**
	 * Render control content.
	 */
	public function render_content() {
		$value = $this->value();
		$values = json_decode( $value, true );
		
		// Set default values if empty.
		if ( empty( $values ) || ! is_array( $values ) ) {
			$values = $this->default;
		} else {
			// Ensure all keys exist.
			$values = wp_parse_args( $values, $this->default );
		}

		$linked = isset( $values['linked'] ) ? $values['linked'] : false;
		$unit = isset( $values['unit'] ) ? $values['unit'] : $this->default_unit;
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

		<div class="aqualuxe-dimensions-control" data-id="<?php echo esc_attr( $this->id ); ?>">
			<div class="aqualuxe-dimensions-header">
				<?php if ( ! empty( $this->units ) && is_array( $this->units ) ) : ?>
					<div class="aqualuxe-dimensions-units">
						<select class="aqualuxe-dimensions-unit-select">
							<?php foreach ( $this->units as $unit_value => $unit_label ) : ?>
								<option value="<?php echo esc_attr( $unit_value ); ?>" <?php selected( $unit, $unit_value ); ?>>
									<?php echo esc_html( $unit_label ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>

				<?php if ( $this->allow_linking ) : ?>
					<div class="aqualuxe-dimensions-link">
						<button type="button" class="aqualuxe-dimensions-link-button <?php echo $linked ? 'linked' : ''; ?>" title="<?php esc_attr_e( 'Link values', 'aqualuxe' ); ?>">
							<span class="dashicons dashicons-admin-links"></span>
							<span class="screen-reader-text"><?php esc_html_e( 'Link values', 'aqualuxe' ); ?></span>
						</button>
					</div>
				<?php endif; ?>
			</div>

			<div class="aqualuxe-dimensions-inputs">
				<?php foreach ( $this->fields as $field => $enabled ) : ?>
					<?php if ( $enabled ) : ?>
						<div class="aqualuxe-dimensions-field aqualuxe-dimensions-<?php echo esc_attr( $field ); ?>">
							<input
								type="number"
								class="aqualuxe-dimensions-input"
								data-field="<?php echo esc_attr( $field ); ?>"
								value="<?php echo esc_attr( isset( $values[ $field ] ) ? $values[ $field ] : '' ); ?>"
								step="any"
							/>
							<label class="aqualuxe-dimensions-label"><?php echo esc_html( isset( $this->field_labels[ $field ] ) ? $this->field_labels[ $field ] : ucfirst( $field ) ); ?></label>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

			<?php if ( ! empty( $this->default ) ) : ?>
				<button type="button" class="aqualuxe-dimensions-reset" title="<?php esc_attr_e( 'Reset to default', 'aqualuxe' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
						<path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
					</svg>
				</button>
			<?php endif; ?>

			<input
				type="hidden"
				id="<?php echo esc_attr( $this->id ); ?>"
				name="<?php echo esc_attr( $this->id ); ?>"
				value="<?php echo esc_attr( $value ); ?>"
				class="aqualuxe-dimensions-value"
				<?php $this->link(); ?>
			/>
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
		$json['fields'] = $this->fields;
		$json['field_labels'] = $this->field_labels;
		$json['units'] = $this->units;
		$json['default_unit'] = $this->default_unit;
		$json['default'] = $this->default;
		$json['allow_linking'] = $this->allow_linking;
		$json['tooltip'] = $this->tooltip;
		return $json;
	}
}