<?php
/**
 * Radio Image Control
 *
 * Customizer control for selecting options with image previews.
 *
 * @package AquaLuxe
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

/**
 * Radio Image Control Class
 */
class AquaLuxe_Radio_Image_Control extends WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'aqualuxe-radio-image';

	/**
	 * Image width
	 *
	 * @var int
	 */
	public $image_width = 80;

	/**
	 * Image height
	 *
	 * @var int
	 */
	public $image_height = 60;

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
			'aqualuxe-radio-image-control',
			get_template_directory_uri() . '/assets/css/admin/customizer-controls.css',
			array(),
			AQUALUXE_VERSION
		);

		wp_enqueue_script(
			'aqualuxe-radio-image-control',
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
		if ( empty( $this->choices ) ) {
			return;
		}

		$name = '_customize-radio-' . $this->id;
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

		<div class="aqualuxe-radio-image-control">
			<div class="aqualuxe-radio-image-choices">
				<?php foreach ( $this->choices as $value => $args ) : ?>
					<?php
					$label = isset( $args['label'] ) ? $args['label'] : '';
					$image = isset( $args['image'] ) ? $args['image'] : '';
					?>
					<label class="aqualuxe-radio-image-choice <?php echo esc_attr( $value === $this->value() ? 'aqualuxe-radio-image-selected' : '' ); ?>">
						<input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); ?> <?php checked( $this->value(), $value ); ?> />
						<div class="aqualuxe-radio-image-preview">
							<?php if ( $image ) : ?>
								<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $label ); ?>" width="<?php echo esc_attr( $this->image_width ); ?>" height="<?php echo esc_attr( $this->image_height ); ?>" />
							<?php else : ?>
								<div class="aqualuxe-radio-image-placeholder" style="width: <?php echo esc_attr( $this->image_width ); ?>px; height: <?php echo esc_attr( $this->image_height ); ?>px;">
									<?php echo esc_html( $label ); ?>
								</div>
							<?php endif; ?>
						</div>
						<?php if ( $label ) : ?>
							<span class="aqualuxe-radio-image-label"><?php echo esc_html( $label ); ?></span>
						<?php endif; ?>
					</label>
				<?php endforeach; ?>
			</div>
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
		$json['tooltip'] = $this->tooltip;
		$json['image_width'] = $this->image_width;
		$json['image_height'] = $this->image_height;
		return $json;
	}
}