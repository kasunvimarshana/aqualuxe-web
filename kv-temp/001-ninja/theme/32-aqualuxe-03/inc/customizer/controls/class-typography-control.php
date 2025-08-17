<?php
/**
 * AquaLuxe Typography Control
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
function aqualuxe_register_typography_control() {
	/**
	 * Typography Control Class
	 * 
	 * A custom control for typography settings that includes font family, weight, size, and line height.
	 */
	class AquaLuxe_Typography_Control extends WP_Customize_Control {
		/**
		 * Control type
		 *
		 * @var string
		 */
		public $type = 'aqualuxe-typography';

		/**
		 * Font families
		 *
		 * @var array
		 */
		public $font_families = array();

		/**
		 * Font weights
		 *
		 * @var array
		 */
		public $font_weights = array();

		/**
		 * Font sizes
		 *
		 * @var array
		 */
		public $font_sizes = array();

		/**
		 * Line heights
		 *
		 * @var array
		 */
		public $line_heights = array();

		/**
		 * Constructor
		 *
		 * @param WP_Customize_Manager $manager Customizer manager.
		 * @param string               $id      Control ID.
		 * @param array                $args    Control arguments.
		 */
		public function __construct( $manager, $id, $args = array() ) {
			parent::__construct( $manager, $id, $args );

			// Set default font families if not provided
			if ( empty( $this->font_families ) ) {
				$this->font_families = array(
					'Arial, sans-serif'                => 'Arial',
					'Helvetica, Arial, sans-serif'     => 'Helvetica',
					'Georgia, serif'                   => 'Georgia',
					'Tahoma, Geneva, sans-serif'       => 'Tahoma',
					'Verdana, Geneva, sans-serif'      => 'Verdana',
					'Montserrat, sans-serif'           => 'Montserrat',
					'Open Sans, sans-serif'            => 'Open Sans',
					'Roboto, sans-serif'               => 'Roboto',
					'Lato, sans-serif'                 => 'Lato',
					'Poppins, sans-serif'              => 'Poppins',
					'Playfair Display, serif'          => 'Playfair Display',
					'Merriweather, serif'              => 'Merriweather',
					'Source Sans Pro, sans-serif'      => 'Source Sans Pro',
					'PT Sans, sans-serif'              => 'PT Sans',
					'Nunito, sans-serif'               => 'Nunito',
				);
			}

			// Set default font weights if not provided
			if ( empty( $this->font_weights ) ) {
				$this->font_weights = array(
					'100' => 'Thin (100)',
					'200' => 'Extra Light (200)',
					'300' => 'Light (300)',
					'400' => 'Regular (400)',
					'500' => 'Medium (500)',
					'600' => 'Semi Bold (600)',
					'700' => 'Bold (700)',
					'800' => 'Extra Bold (800)',
					'900' => 'Black (900)',
				);
			}

			// Set default font sizes if not provided
			if ( empty( $this->font_sizes ) ) {
				$this->font_sizes = array(
					'12px' => '12px',
					'13px' => '13px',
					'14px' => '14px',
					'15px' => '15px',
					'16px' => '16px',
					'18px' => '18px',
					'20px' => '20px',
					'24px' => '24px',
					'28px' => '28px',
					'32px' => '32px',
					'36px' => '36px',
					'42px' => '42px',
					'48px' => '48px',
				);
			}

			// Set default line heights if not provided
			if ( empty( $this->line_heights ) ) {
				$this->line_heights = array(
					'1'    => '1',
					'1.2'  => '1.2',
					'1.4'  => '1.4',
					'1.6'  => '1.6',
					'1.8'  => '1.8',
					'2'    => '2',
				);
			}
		}

		/**
		 * Enqueue control related scripts/styles.
		 */
		public function enqueue() {
			wp_enqueue_script(
				'aqualuxe-typography-control',
				get_template_directory_uri() . '/assets/js/customizer/typography-control.js',
				array( 'jquery', 'customize-controls' ),
				AQUALUXE_VERSION,
				true
			);

			wp_enqueue_style(
				'aqualuxe-typography-control',
				get_template_directory_uri() . '/assets/css/customizer/typography-control.css',
				array(),
				AQUALUXE_VERSION
			);

			// Localize script with font families for preview
			wp_localize_script(
				'aqualuxe-typography-control',
				'aqualuxeTypographyControl',
				array(
					'fontFamilies' => $this->get_google_fonts(),
				)
			);
		}

		/**
		 * Get Google Fonts
		 *
		 * @return array Google fonts
		 */
		private function get_google_fonts() {
			$web_safe_fonts = array(
				'Arial, sans-serif',
				'Helvetica, Arial, sans-serif',
				'Georgia, serif',
				'Tahoma, Geneva, sans-serif',
				'Verdana, Geneva, sans-serif',
			);

			$google_fonts = array();

			foreach ( $this->font_families as $font_family => $font_label ) {
				if ( ! in_array( $font_family, $web_safe_fonts, true ) ) {
					$font_name = explode( ',', $font_family )[0];
					$google_fonts[ $font_family ] = $font_name;
				}
			}

			return $google_fonts;
		}

		/**
		 * Render the control's content.
		 */
		public function render_content() {
			$value = $this->value();
			$value_array = json_decode( $value, true );

			// Set default values if not set
			if ( ! is_array( $value_array ) ) {
				$value_array = array(
					'font-family' => '',
					'font-weight' => '',
					'font-size'   => '',
					'line-height' => '',
				);
			}

			$font_family = isset( $value_array['font-family'] ) ? $value_array['font-family'] : '';
			$font_weight = isset( $value_array['font-weight'] ) ? $value_array['font-weight'] : '';
			$font_size   = isset( $value_array['font-size'] ) ? $value_array['font-size'] : '';
			$line_height = isset( $value_array['line-height'] ) ? $value_array['line-height'] : '';
			?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>

			<div class="aqualuxe-typography-control">
				<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php $this->link(); ?> />

				<div class="aqualuxe-typography-font-family">
					<span class="aqualuxe-typography-label"><?php esc_html_e( 'Font Family', 'aqualuxe' ); ?></span>
					<select class="aqualuxe-typography-font-family-select" data-field="font-family">
						<option value=""><?php esc_html_e( 'Default', 'aqualuxe' ); ?></option>
						<?php foreach ( $this->font_families as $font_family_value => $font_family_label ) : ?>
							<option value="<?php echo esc_attr( $font_family_value ); ?>" <?php selected( $font_family, $font_family_value ); ?>><?php echo esc_html( $font_family_label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="aqualuxe-typography-font-weight">
					<span class="aqualuxe-typography-label"><?php esc_html_e( 'Font Weight', 'aqualuxe' ); ?></span>
					<select class="aqualuxe-typography-font-weight-select" data-field="font-weight">
						<option value=""><?php esc_html_e( 'Default', 'aqualuxe' ); ?></option>
						<?php foreach ( $this->font_weights as $font_weight_value => $font_weight_label ) : ?>
							<option value="<?php echo esc_attr( $font_weight_value ); ?>" <?php selected( $font_weight, $font_weight_value ); ?>><?php echo esc_html( $font_weight_label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="aqualuxe-typography-font-size">
					<span class="aqualuxe-typography-label"><?php esc_html_e( 'Font Size', 'aqualuxe' ); ?></span>
					<select class="aqualuxe-typography-font-size-select" data-field="font-size">
						<option value=""><?php esc_html_e( 'Default', 'aqualuxe' ); ?></option>
						<?php foreach ( $this->font_sizes as $font_size_value => $font_size_label ) : ?>
							<option value="<?php echo esc_attr( $font_size_value ); ?>" <?php selected( $font_size, $font_size_value ); ?>><?php echo esc_html( $font_size_label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="aqualuxe-typography-line-height">
					<span class="aqualuxe-typography-label"><?php esc_html_e( 'Line Height', 'aqualuxe' ); ?></span>
					<select class="aqualuxe-typography-line-height-select" data-field="line-height">
						<option value=""><?php esc_html_e( 'Default', 'aqualuxe' ); ?></option>
						<?php foreach ( $this->line_heights as $line_height_value => $line_height_label ) : ?>
							<option value="<?php echo esc_attr( $line_height_value ); ?>" <?php selected( $line_height, $line_height_value ); ?>><?php echo esc_html( $line_height_label ); ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div class="aqualuxe-typography-preview">
					<span class="aqualuxe-typography-label"><?php esc_html_e( 'Preview', 'aqualuxe' ); ?></span>
					<div class="aqualuxe-typography-preview-text" style="
						<?php if ( ! empty( $font_family ) ) : ?>
							font-family: <?php echo esc_attr( $font_family ); ?>;
						<?php endif; ?>
						<?php if ( ! empty( $font_weight ) ) : ?>
							font-weight: <?php echo esc_attr( $font_weight ); ?>;
						<?php endif; ?>
						<?php if ( ! empty( $font_size ) ) : ?>
							font-size: <?php echo esc_attr( $font_size ); ?>;
						<?php endif; ?>
						<?php if ( ! empty( $line_height ) ) : ?>
							line-height: <?php echo esc_attr( $line_height ); ?>;
						<?php endif; ?>
					">
						<?php esc_html_e( 'The quick brown fox jumps over the lazy dog.', 'aqualuxe' ); ?>
					</div>
				</div>
			</div>
			<?php
		}
	}
}
add_action( 'customize_register', 'aqualuxe_register_typography_control', 0 );