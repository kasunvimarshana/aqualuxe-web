<?php
/**
 * Color Alpha Control
 *
 * Customizer control for color selection with alpha transparency.
 *
 * @package AquaLuxe
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

/**
 * Color Alpha Control Class
 */
class AquaLuxe_Color_Alpha_Control extends WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'aqualuxe-color-alpha';

	/**
	 * Default color
	 *
	 * @var string
	 */
	public $default = '';

	/**
	 * Tooltip text
	 *
	 * @var string
	 */
	public $tooltip = '';

	/**
	 * Color palette
	 *
	 * @var array
	 */
	public $palette = true;

	/**
	 * Show opacity
	 *
	 * @var bool
	 */
	public $show_opacity = true;

	/**
	 * Enqueue control scripts and styles.
	 */
	public function enqueue() {
		wp_enqueue_style(
			'wp-color-picker'
		);

		wp_enqueue_style(
			'aqualuxe-color-alpha-control',
			get_template_directory_uri() . '/assets/css/admin/customizer-controls.css',
			array( 'wp-color-picker' ),
			AQUALUXE_VERSION
		);

		wp_enqueue_script(
			'wp-color-picker-alpha',
			get_template_directory_uri() . '/assets/js/admin/wp-color-picker-alpha.min.js',
			array( 'wp-color-picker' ),
			AQUALUXE_VERSION,
			true
		);

		wp_enqueue_script(
			'aqualuxe-color-alpha-control',
			get_template_directory_uri() . '/assets/js/admin/customizer-controls.js',
			array( 'jquery', 'customize-base', 'wp-color-picker-alpha' ),
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

		// Process the palette.
		if ( is_array( $this->palette ) ) {
			$palette = implode( '|', $this->palette );
		} else {
			// Default to true.
			$palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';
		}

		// Support passing show_opacity as string or boolean.
		$show_opacity = ( 'true' === $this->show_opacity || true === $this->show_opacity ) ? 'true' : 'false';
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

		<div class="aqualuxe-color-alpha-control">
			<div class="aqualuxe-color-alpha-wrapper">
				<input
					id="<?php echo esc_attr( $input_id ); ?>"
					type="text"
					class="aqualuxe-color-alpha-input"
					value="<?php echo esc_attr( $this->value() ); ?>"
					data-default-color="<?php echo esc_attr( $this->default ); ?>"
					data-palette="<?php echo esc_attr( $palette ); ?>"
					data-show-opacity="<?php echo esc_attr( $show_opacity ); ?>"
					<?php echo $input_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php $this->link(); ?>
				/>
			</div>
			<?php if ( ! empty( $this->default ) ) : ?>
				<button type="button" class="aqualuxe-color-alpha-reset" title="<?php esc_attr_e( 'Reset to default', 'aqualuxe' ); ?>">
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
		$json['default'] = $this->default;
		$json['tooltip'] = $this->tooltip;
		$json['show_opacity'] = $this->show_opacity;
		$json['palette'] = $this->palette;
		return $json;
	}
}