<?php
/**
 * Typography Control
 *
 * Customizer control for typography settings (font family, size, weight, etc.).
 *
 * @package AquaLuxe
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

/**
 * Typography Control Class
 */
class AquaLuxe_Typography_Control extends WP_Customize_Control {

	/**
	 * Control type
	 *
	 * @var string
	 */
	public $type = 'aqualuxe-typography';

	/**
	 * Typography fields
	 *
	 * @var array
	 */
	public $fields = array(
		'font-family'    => true,
		'font-size'      => true,
		'font-weight'    => true,
		'line-height'    => true,
		'letter-spacing' => true,
		'text-transform' => true,
		'color'          => true,
	);

	/**
	 * Default values
	 *
	 * @var array
	 */
	public $default = array(
		'font-family'    => '',
		'font-size'      => '',
		'font-weight'    => '',
		'line-height'    => '',
		'letter-spacing' => '',
		'text-transform' => '',
		'color'          => '',
	);

	/**
	 * Available font families
	 *
	 * @var array
	 */
	public $font_families = array();

	/**
	 * Available font weights
	 *
	 * @var array
	 */
	public $font_weights = array(
		''       => 'Default',
		'100'    => 'Thin 100',
		'200'    => 'Extra Light 200',
		'300'    => 'Light 300',
		'400'    => 'Regular 400',
		'500'    => 'Medium 500',
		'600'    => 'Semi Bold 600',
		'700'    => 'Bold 700',
		'800'    => 'Extra Bold 800',
		'900'    => 'Black 900',
		'normal' => 'Normal',
		'bold'   => 'Bold',
	);

	/**
	 * Available text transforms
	 *
	 * @var array
	 */
	public $text_transforms = array(
		''           => 'Default',
		'none'       => 'None',
		'capitalize' => 'Capitalize',
		'uppercase'  => 'Uppercase',
		'lowercase'  => 'Lowercase',
	);

	/**
	 * Tooltip text
	 *
	 * @var string
	 */
	public $tooltip = '';

	/**
	 * Constructor.
	 *
	 * @param WP_Customize_Manager $manager Customizer manager.
	 * @param string               $id      Control ID.
	 * @param array                $args    Control arguments.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		// Set default font families.
		$this->font_families = array(
			'' => 'Default',
			'Arial, sans-serif'                                  => 'Arial',
			'Helvetica, Arial, sans-serif'                       => 'Helvetica',
			'Georgia, serif'                                     => 'Georgia',
			'Times New Roman, serif'                             => 'Times New Roman',
			'Courier New, monospace'                             => 'Courier New',
			'Verdana, sans-serif'                                => 'Verdana',
			'Tahoma, sans-serif'                                 => 'Tahoma',
			'Trebuchet MS, sans-serif'                           => 'Trebuchet MS',
			'Impact, sans-serif'                                 => 'Impact',
			'Comic Sans MS, cursive'                             => 'Comic Sans MS',
			'Lucida Sans Unicode, Lucida Grande, sans-serif'     => 'Lucida Sans',
			'Palatino Linotype, Book Antiqua, Palatino, serif'   => 'Palatino',
			'system-ui, -apple-system, BlinkMacSystemFont, sans-serif' => 'System UI',
		);

		// Add Google Fonts if available.
		$google_fonts = $this->get_google_fonts();
		if ( ! empty( $google_fonts ) && is_array( $google_fonts ) ) {
			$this->font_families = array_merge( $this->font_families, $google_fonts );
		}
	}

	/**
	 * Enqueue control scripts and styles.
	 */
	public function enqueue() {
		wp_enqueue_style(
			'wp-color-picker'
		);

		wp_enqueue_style(
			'aqualuxe-typography-control',
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
			'aqualuxe-typography-control',
			get_template_directory_uri() . '/assets/js/admin/customizer-controls.js',
			array( 'jquery', 'customize-base', 'wp-color-picker-alpha' ),
			AQUALUXE_VERSION,
			true
		);

		// Add Google Fonts preview.
		$google_fonts = array_diff_key( $this->font_families, array(
			'' => '',
			'Arial, sans-serif' => '',
			'Helvetica, Arial, sans-serif' => '',
			'Georgia, serif' => '',
			'Times New Roman, serif' => '',
			'Courier New, monospace' => '',
			'Verdana, sans-serif' => '',
			'Tahoma, sans-serif' => '',
			'Trebuchet MS, sans-serif' => '',
			'Impact, sans-serif' => '',
			'Comic Sans MS, cursive' => '',
			'Lucida Sans Unicode, Lucida Grande, sans-serif' => '',
			'Palatino Linotype, Book Antiqua, Palatino, serif' => '',
			'system-ui, -apple-system, BlinkMacSystemFont, sans-serif' => '',
		) );

		if ( ! empty( $google_fonts ) ) {
			$google_fonts_url = 'https://fonts.googleapis.com/css?family=';
			$fonts = array();

			foreach ( $google_fonts as $font_family => $font_label ) {
				$font_family = explode( ',', $font_family )[0];
				$fonts[] = str_replace( ' ', '+', $font_family ) . ':100,200,300,400,500,600,700,800,900';
			}

			$google_fonts_url .= implode( '|', $fonts );
			$google_fonts_url .= '&display=swap';

			wp_enqueue_style(
				'aqualuxe-google-fonts',
				$google_fonts_url,
				array(),
				AQUALUXE_VERSION
			);
		}
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

		<div class="aqualuxe-typography-control" data-id="<?php echo esc_attr( $this->id ); ?>">
			<?php if ( $this->fields['font-family'] ) : ?>
				<div class="aqualuxe-typography-field aqualuxe-typography-font-family">
					<label class="aqualuxe-typography-label"><?php esc_html_e( 'Font Family', 'aqualuxe' ); ?></label>
					<select class="aqualuxe-typography-input" data-field="font-family">
						<?php foreach ( $this->font_families as $font_family => $font_label ) : ?>
							<option value="<?php echo esc_attr( $font_family ); ?>" <?php selected( $values['font-family'], $font_family ); ?> style="font-family: <?php echo esc_attr( $font_family ); ?>;">
								<?php echo esc_html( $font_label ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>

			<?php if ( $this->fields['font-size'] ) : ?>
				<div class="aqualuxe-typography-field aqualuxe-typography-font-size">
					<label class="aqualuxe-typography-label"><?php esc_html_e( 'Font Size', 'aqualuxe' ); ?></label>
					<div class="aqualuxe-typography-input-wrapper">
						<input
							type="text"
							class="aqualuxe-typography-input"
							data-field="font-size"
							value="<?php echo esc_attr( $values['font-size'] ); ?>"
							placeholder="<?php esc_attr_e( 'e.g. 16px', 'aqualuxe' ); ?>"
						/>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $this->fields['font-weight'] ) : ?>
				<div class="aqualuxe-typography-field aqualuxe-typography-font-weight">
					<label class="aqualuxe-typography-label"><?php esc_html_e( 'Font Weight', 'aqualuxe' ); ?></label>
					<select class="aqualuxe-typography-input" data-field="font-weight">
						<?php foreach ( $this->font_weights as $weight => $label ) : ?>
							<option value="<?php echo esc_attr( $weight ); ?>" <?php selected( $values['font-weight'], $weight ); ?>>
								<?php echo esc_html( $label ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>

			<?php if ( $this->fields['line-height'] ) : ?>
				<div class="aqualuxe-typography-field aqualuxe-typography-line-height">
					<label class="aqualuxe-typography-label"><?php esc_html_e( 'Line Height', 'aqualuxe' ); ?></label>
					<div class="aqualuxe-typography-input-wrapper">
						<input
							type="text"
							class="aqualuxe-typography-input"
							data-field="line-height"
							value="<?php echo esc_attr( $values['line-height'] ); ?>"
							placeholder="<?php esc_attr_e( 'e.g. 1.5', 'aqualuxe' ); ?>"
						/>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $this->fields['letter-spacing'] ) : ?>
				<div class="aqualuxe-typography-field aqualuxe-typography-letter-spacing">
					<label class="aqualuxe-typography-label"><?php esc_html_e( 'Letter Spacing', 'aqualuxe' ); ?></label>
					<div class="aqualuxe-typography-input-wrapper">
						<input
							type="text"
							class="aqualuxe-typography-input"
							data-field="letter-spacing"
							value="<?php echo esc_attr( $values['letter-spacing'] ); ?>"
							placeholder="<?php esc_attr_e( 'e.g. 0.5px', 'aqualuxe' ); ?>"
						/>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $this->fields['text-transform'] ) : ?>
				<div class="aqualuxe-typography-field aqualuxe-typography-text-transform">
					<label class="aqualuxe-typography-label"><?php esc_html_e( 'Text Transform', 'aqualuxe' ); ?></label>
					<select class="aqualuxe-typography-input" data-field="text-transform">
						<?php foreach ( $this->text_transforms as $transform => $label ) : ?>
							<option value="<?php echo esc_attr( $transform ); ?>" <?php selected( $values['text-transform'], $transform ); ?>>
								<?php echo esc_html( $label ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			<?php endif; ?>

			<?php if ( $this->fields['color'] ) : ?>
				<div class="aqualuxe-typography-field aqualuxe-typography-color">
					<label class="aqualuxe-typography-label"><?php esc_html_e( 'Color', 'aqualuxe' ); ?></label>
					<div class="aqualuxe-typography-input-wrapper">
						<input
							type="text"
							class="aqualuxe-typography-color-input"
							data-field="color"
							value="<?php echo esc_attr( $values['color'] ); ?>"
							data-default-color=""
							data-alpha-enabled="true"
						/>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $this->default ) ) : ?>
				<button type="button" class="aqualuxe-typography-reset" title="<?php esc_attr_e( 'Reset to default', 'aqualuxe' ); ?>">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
						<path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
					</svg>
				</button>
			<?php endif; ?>

			<div class="aqualuxe-typography-preview">
				<label class="aqualuxe-typography-preview-label"><?php esc_html_e( 'Preview', 'aqualuxe' ); ?></label>
				<div class="aqualuxe-typography-preview-content" style="
					<?php if ( ! empty( $values['font-family'] ) ) : ?>
						font-family: <?php echo esc_attr( $values['font-family'] ); ?>;
					<?php endif; ?>
					<?php if ( ! empty( $values['font-size'] ) ) : ?>
						font-size: <?php echo esc_attr( $values['font-size'] ); ?>;
					<?php endif; ?>
					<?php if ( ! empty( $values['font-weight'] ) ) : ?>
						font-weight: <?php echo esc_attr( $values['font-weight'] ); ?>;
					<?php endif; ?>
					<?php if ( ! empty( $values['line-height'] ) ) : ?>
						line-height: <?php echo esc_attr( $values['line-height'] ); ?>;
					<?php endif; ?>
					<?php if ( ! empty( $values['letter-spacing'] ) ) : ?>
						letter-spacing: <?php echo esc_attr( $values['letter-spacing'] ); ?>;
					<?php endif; ?>
					<?php if ( ! empty( $values['text-transform'] ) ) : ?>
						text-transform: <?php echo esc_attr( $values['text-transform'] ); ?>;
					<?php endif; ?>
					<?php if ( ! empty( $values['color'] ) ) : ?>
						color: <?php echo esc_attr( $values['color'] ); ?>;
					<?php endif; ?>
				">
					<?php esc_html_e( 'The quick brown fox jumps over the lazy dog.', 'aqualuxe' ); ?>
				</div>
			</div>

			<input
				type="hidden"
				id="<?php echo esc_attr( $this->id ); ?>"
				name="<?php echo esc_attr( $this->id ); ?>"
				value="<?php echo esc_attr( $value ); ?>"
				class="aqualuxe-typography-value"
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
		$json['default'] = $this->default;
		$json['font_families'] = $this->font_families;
		$json['font_weights'] = $this->font_weights;
		$json['text_transforms'] = $this->text_transforms;
		$json['tooltip'] = $this->tooltip;
		return $json;
	}

	/**
	 * Get Google Fonts.
	 *
	 * @return array Google Fonts.
	 */
	private function get_google_fonts() {
		// Return empty array if Google Fonts are not enabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_google_fonts', true ) ) {
			return array();
		}

		// Get Google Fonts from transient.
		$google_fonts = get_transient( 'aqualuxe_google_fonts' );

		// If transient doesn't exist, get Google Fonts from file.
		if ( false === $google_fonts ) {
			$google_fonts_file = get_template_directory() . '/assets/data/google-fonts.json';

			if ( file_exists( $google_fonts_file ) ) {
				$google_fonts_json = file_get_contents( $google_fonts_file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
				$google_fonts_data = json_decode( $google_fonts_json, true );

				if ( ! empty( $google_fonts_data ) && is_array( $google_fonts_data ) ) {
					$google_fonts = array();

					foreach ( $google_fonts_data as $font ) {
						if ( isset( $font['family'] ) ) {
							$google_fonts[ $font['family'] . ', ' . $font['category'] ] = $font['family'];
						}
					}

					// Set transient for 1 week.
					set_transient( 'aqualuxe_google_fonts', $google_fonts, WEEK_IN_SECONDS );
				}
			}
		}

		return is_array( $google_fonts ) ? $google_fonts : array();
	}
}