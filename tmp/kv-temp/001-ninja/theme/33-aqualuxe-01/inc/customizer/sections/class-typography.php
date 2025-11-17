<?php
/**
 * Typography Customizer Section
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

use AquaLuxe\Customizer\Controls\Typography_Control;
use AquaLuxe\Customizer\Controls\Heading_Control;
use AquaLuxe\Customizer\Controls\Divider_Control;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Typography Customizer Section Class
 */
class Typography {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'aqualuxe_customize_preview_js', array( $this, 'preview_js' ) );
	}

	/**
	 * Register customizer settings
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_settings( $wp_customize ) {
		// Add Typography section.
		$wp_customize->add_section(
			'aqualuxe_typography',
			array(
				'title'    => esc_html__( 'Typography', 'aqualuxe' ),
				'priority' => 40,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Global Typography Settings.
		$wp_customize->add_setting(
			'aqualuxe_typography_global_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_typography_global_heading',
				array(
					'label'    => esc_html__( 'Global Typography', 'aqualuxe' ),
					'section'  => 'aqualuxe_typography',
					'priority' => 10,
				)
			)
		);

		// Body Typography.
		$wp_customize->add_setting(
			'aqualuxe_body_typography',
			array(
				'default'           => array(
					'font-family'    => 'Inter',
					'variant'        => 'regular',
					'font-size'      => '16px',
					'line-height'    => '1.6',
					'letter-spacing' => '0',
					'text-transform' => 'none',
				),
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $this, 'sanitize_typography' ),
			)
		);

		$wp_customize->add_control(
			new Typography_Control(
				$wp_customize,
				'aqualuxe_body_typography',
				array(
					'label'       => esc_html__( 'Body Typography', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the typography of body text.', 'aqualuxe' ),
					'section'     => 'aqualuxe_typography',
					'priority'    => 20,
					'connect'     => 'aqualuxe_body_font_family',
					'variant'     => true,
					'font-size'   => true,
					'line-height' => true,
					'letter-spacing' => true,
					'text-transform' => true,
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_typography_headings_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_typography_headings_divider',
				array(
					'section'  => 'aqualuxe_typography',
					'priority' => 30,
				)
			)
		);

		// Headings Typography.
		$wp_customize->add_setting(
			'aqualuxe_typography_headings_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_typography_headings_heading',
				array(
					'label'    => esc_html__( 'Headings Typography', 'aqualuxe' ),
					'section'  => 'aqualuxe_typography',
					'priority' => 40,
				)
			)
		);

		// All Headings Typography.
		$wp_customize->add_setting(
			'aqualuxe_headings_typography',
			array(
				'default'           => array(
					'font-family'    => 'Montserrat',
					'variant'        => '700',
					'letter-spacing' => '0',
					'text-transform' => 'none',
				),
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $this, 'sanitize_typography' ),
			)
		);

		$wp_customize->add_control(
			new Typography_Control(
				$wp_customize,
				'aqualuxe_headings_typography',
				array(
					'label'       => esc_html__( 'All Headings', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the typography of all headings.', 'aqualuxe' ),
					'section'     => 'aqualuxe_typography',
					'priority'    => 50,
					'connect'     => 'aqualuxe_headings_font_family',
					'variant'     => true,
					'letter-spacing' => true,
					'text-transform' => true,
				)
			)
		);

		// Individual Heading Controls (H1-H6).
		$headings = array(
			'h1' => array(
				'label'    => esc_html__( 'Heading 1 (H1)', 'aqualuxe' ),
				'priority' => 60,
				'default'  => array(
					'font-size'   => '2.5rem',
					'line-height' => '1.2',
				),
			),
			'h2' => array(
				'label'    => esc_html__( 'Heading 2 (H2)', 'aqualuxe' ),
				'priority' => 70,
				'default'  => array(
					'font-size'   => '2rem',
					'line-height' => '1.3',
				),
			),
			'h3' => array(
				'label'    => esc_html__( 'Heading 3 (H3)', 'aqualuxe' ),
				'priority' => 80,
				'default'  => array(
					'font-size'   => '1.75rem',
					'line-height' => '1.4',
				),
			),
			'h4' => array(
				'label'    => esc_html__( 'Heading 4 (H4)', 'aqualuxe' ),
				'priority' => 90,
				'default'  => array(
					'font-size'   => '1.5rem',
					'line-height' => '1.4',
				),
			),
			'h5' => array(
				'label'    => esc_html__( 'Heading 5 (H5)', 'aqualuxe' ),
				'priority' => 100,
				'default'  => array(
					'font-size'   => '1.25rem',
					'line-height' => '1.5',
				),
			),
			'h6' => array(
				'label'    => esc_html__( 'Heading 6 (H6)', 'aqualuxe' ),
				'priority' => 110,
				'default'  => array(
					'font-size'   => '1rem',
					'line-height' => '1.5',
				),
			),
		);

		foreach ( $headings as $heading => $args ) {
			$wp_customize->add_setting(
				'aqualuxe_' . $heading . '_typography',
				array(
					'default'           => $args['default'],
					'transport'         => 'postMessage',
					'sanitize_callback' => array( $this, 'sanitize_typography' ),
				)
			);

			$wp_customize->add_control(
				new Typography_Control(
					$wp_customize,
					'aqualuxe_' . $heading . '_typography',
					array(
						'label'       => $args['label'],
						'section'     => 'aqualuxe_typography',
						'priority'    => $args['priority'],
						'font-size'   => true,
						'line-height' => true,
					)
				)
			);
		}

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_typography_special_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_typography_special_divider',
				array(
					'section'  => 'aqualuxe_typography',
					'priority' => 120,
				)
			)
		);

		// Special Elements Typography.
		$wp_customize->add_setting(
			'aqualuxe_typography_special_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_typography_special_heading',
				array(
					'label'    => esc_html__( 'Special Elements', 'aqualuxe' ),
					'section'  => 'aqualuxe_typography',
					'priority' => 130,
				)
			)
		);

		// Menu Typography.
		$wp_customize->add_setting(
			'aqualuxe_menu_typography',
			array(
				'default'           => array(
					'font-family'    => 'inherit',
					'variant'        => '500',
					'font-size'      => '16px',
					'letter-spacing' => '0',
					'text-transform' => 'none',
				),
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $this, 'sanitize_typography' ),
			)
		);

		$wp_customize->add_control(
			new Typography_Control(
				$wp_customize,
				'aqualuxe_menu_typography',
				array(
					'label'       => esc_html__( 'Main Menu', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the typography of the main navigation menu.', 'aqualuxe' ),
					'section'     => 'aqualuxe_typography',
					'priority'    => 140,
					'connect'     => 'aqualuxe_menu_font_family',
					'variant'     => true,
					'font-size'   => true,
					'letter-spacing' => true,
					'text-transform' => true,
				)
			)
		);

		// Button Typography.
		$wp_customize->add_setting(
			'aqualuxe_button_typography',
			array(
				'default'           => array(
					'font-family'    => 'inherit',
					'variant'        => '500',
					'font-size'      => '16px',
					'letter-spacing' => '0.5px',
					'text-transform' => 'none',
				),
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $this, 'sanitize_typography' ),
			)
		);

		$wp_customize->add_control(
			new Typography_Control(
				$wp_customize,
				'aqualuxe_button_typography',
				array(
					'label'       => esc_html__( 'Buttons', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the typography of buttons.', 'aqualuxe' ),
					'section'     => 'aqualuxe_typography',
					'priority'    => 150,
					'connect'     => 'aqualuxe_button_font_family',
					'variant'     => true,
					'font-size'   => true,
					'letter-spacing' => true,
					'text-transform' => true,
				)
			)
		);
	}

	/**
	 * Sanitize typography values
	 *
	 * @param array $input Typography values to sanitize.
	 * @return array Sanitized values.
	 */
	public function sanitize_typography( $input ) {
		if ( ! is_array( $input ) ) {
			return array();
		}

		$allowed_keys = array(
			'font-family',
			'variant',
			'font-size',
			'line-height',
			'letter-spacing',
			'text-transform',
		);

		$sanitized = array();

		foreach ( $input as $key => $value ) {
			if ( in_array( $key, $allowed_keys, true ) ) {
				switch ( $key ) {
					case 'font-family':
						$sanitized[ $key ] = sanitize_text_field( $value );
						break;
					case 'variant':
						$sanitized[ $key ] = sanitize_text_field( $value );
						break;
					case 'font-size':
						$sanitized[ $key ] = sanitize_text_field( $value );
						break;
					case 'line-height':
						$sanitized[ $key ] = is_numeric( $value ) ? $value : sanitize_text_field( $value );
						break;
					case 'letter-spacing':
						$sanitized[ $key ] = is_numeric( $value ) ? $value : sanitize_text_field( $value );
						break;
					case 'text-transform':
						$allowed_transforms = array( 'none', 'capitalize', 'uppercase', 'lowercase' );
						$sanitized[ $key ]  = in_array( $value, $allowed_transforms, true ) ? $value : 'none';
						break;
					default:
						$sanitized[ $key ] = sanitize_text_field( $value );
				}
			}
		}

		return $sanitized;
	}

	/**
	 * Enqueue frontend styles
	 */
	public function enqueue_styles() {
		// Get typography settings.
		$body_typography     = get_theme_mod( 'aqualuxe_body_typography', array() );
		$headings_typography = get_theme_mod( 'aqualuxe_headings_typography', array() );
		$h1_typography       = get_theme_mod( 'aqualuxe_h1_typography', array() );
		$h2_typography       = get_theme_mod( 'aqualuxe_h2_typography', array() );
		$h3_typography       = get_theme_mod( 'aqualuxe_h3_typography', array() );
		$h4_typography       = get_theme_mod( 'aqualuxe_h4_typography', array() );
		$h5_typography       = get_theme_mod( 'aqualuxe_h5_typography', array() );
		$h6_typography       = get_theme_mod( 'aqualuxe_h6_typography', array() );
		$menu_typography     = get_theme_mod( 'aqualuxe_menu_typography', array() );
		$button_typography   = get_theme_mod( 'aqualuxe_button_typography', array() );

		// Generate inline styles.
		$css = '';

		// Body typography.
		if ( ! empty( $body_typography ) ) {
			$css .= 'body {';
			if ( ! empty( $body_typography['font-family'] ) && 'inherit' !== $body_typography['font-family'] ) {
				$css .= 'font-family: ' . esc_attr( $body_typography['font-family'] ) . ';';
			}
			if ( ! empty( $body_typography['variant'] ) ) {
				$weight = str_replace( 'italic', '', $body_typography['variant'] );
				$weight = ( '' === $weight ) ? '400' : $weight;
				$css   .= 'font-weight: ' . esc_attr( $weight ) . ';';
				$css   .= 'font-style: ' . ( strpos( $body_typography['variant'], 'italic' ) !== false ? 'italic' : 'normal' ) . ';';
			}
			if ( ! empty( $body_typography['font-size'] ) ) {
				$css .= 'font-size: ' . esc_attr( $body_typography['font-size'] ) . ';';
			}
			if ( ! empty( $body_typography['line-height'] ) ) {
				$css .= 'line-height: ' . esc_attr( $body_typography['line-height'] ) . ';';
			}
			if ( ! empty( $body_typography['letter-spacing'] ) ) {
				$css .= 'letter-spacing: ' . esc_attr( $body_typography['letter-spacing'] ) . 'px;';
			}
			if ( ! empty( $body_typography['text-transform'] ) ) {
				$css .= 'text-transform: ' . esc_attr( $body_typography['text-transform'] ) . ';';
			}
			$css .= '}';
		}

		// Headings typography.
		if ( ! empty( $headings_typography ) ) {
			$css .= 'h1, h2, h3, h4, h5, h6 {';
			if ( ! empty( $headings_typography['font-family'] ) && 'inherit' !== $headings_typography['font-family'] ) {
				$css .= 'font-family: ' . esc_attr( $headings_typography['font-family'] ) . ';';
			}
			if ( ! empty( $headings_typography['variant'] ) ) {
				$weight = str_replace( 'italic', '', $headings_typography['variant'] );
				$weight = ( '' === $weight ) ? '400' : $weight;
				$css   .= 'font-weight: ' . esc_attr( $weight ) . ';';
				$css   .= 'font-style: ' . ( strpos( $headings_typography['variant'], 'italic' ) !== false ? 'italic' : 'normal' ) . ';';
			}
			if ( ! empty( $headings_typography['letter-spacing'] ) ) {
				$css .= 'letter-spacing: ' . esc_attr( $headings_typography['letter-spacing'] ) . 'px;';
			}
			if ( ! empty( $headings_typography['text-transform'] ) ) {
				$css .= 'text-transform: ' . esc_attr( $headings_typography['text-transform'] ) . ';';
			}
			$css .= '}';
		}

		// Individual heading styles.
		$headings = array(
			'h1' => $h1_typography,
			'h2' => $h2_typography,
			'h3' => $h3_typography,
			'h4' => $h4_typography,
			'h5' => $h5_typography,
			'h6' => $h6_typography,
		);

		foreach ( $headings as $tag => $typography ) {
			if ( ! empty( $typography ) ) {
				$css .= $tag . ' {';
				if ( ! empty( $typography['font-size'] ) ) {
					$css .= 'font-size: ' . esc_attr( $typography['font-size'] ) . ';';
				}
				if ( ! empty( $typography['line-height'] ) ) {
					$css .= 'line-height: ' . esc_attr( $typography['line-height'] ) . ';';
				}
				$css .= '}';
			}
		}

		// Menu typography.
		if ( ! empty( $menu_typography ) ) {
			$css .= '.main-navigation ul li a {';
			if ( ! empty( $menu_typography['font-family'] ) && 'inherit' !== $menu_typography['font-family'] ) {
				$css .= 'font-family: ' . esc_attr( $menu_typography['font-family'] ) . ';';
			}
			if ( ! empty( $menu_typography['variant'] ) ) {
				$weight = str_replace( 'italic', '', $menu_typography['variant'] );
				$weight = ( '' === $weight ) ? '400' : $weight;
				$css   .= 'font-weight: ' . esc_attr( $weight ) . ';';
				$css   .= 'font-style: ' . ( strpos( $menu_typography['variant'], 'italic' ) !== false ? 'italic' : 'normal' ) . ';';
			}
			if ( ! empty( $menu_typography['font-size'] ) ) {
				$css .= 'font-size: ' . esc_attr( $menu_typography['font-size'] ) . ';';
			}
			if ( ! empty( $menu_typography['letter-spacing'] ) ) {
				$css .= 'letter-spacing: ' . esc_attr( $menu_typography['letter-spacing'] ) . 'px;';
			}
			if ( ! empty( $menu_typography['text-transform'] ) ) {
				$css .= 'text-transform: ' . esc_attr( $menu_typography['text-transform'] ) . ';';
			}
			$css .= '}';
		}

		// Button typography.
		if ( ! empty( $button_typography ) ) {
			$css .= '.button, button, input[type="button"], input[type="reset"], input[type="submit"] {';
			if ( ! empty( $button_typography['font-family'] ) && 'inherit' !== $button_typography['font-family'] ) {
				$css .= 'font-family: ' . esc_attr( $button_typography['font-family'] ) . ';';
			}
			if ( ! empty( $button_typography['variant'] ) ) {
				$weight = str_replace( 'italic', '', $button_typography['variant'] );
				$weight = ( '' === $weight ) ? '400' : $weight;
				$css   .= 'font-weight: ' . esc_attr( $weight ) . ';';
				$css   .= 'font-style: ' . ( strpos( $button_typography['variant'], 'italic' ) !== false ? 'italic' : 'normal' ) . ';';
			}
			if ( ! empty( $button_typography['font-size'] ) ) {
				$css .= 'font-size: ' . esc_attr( $button_typography['font-size'] ) . ';';
			}
			if ( ! empty( $button_typography['letter-spacing'] ) ) {
				$css .= 'letter-spacing: ' . esc_attr( $button_typography['letter-spacing'] ) . 'px;';
			}
			if ( ! empty( $button_typography['text-transform'] ) ) {
				$css .= 'text-transform: ' . esc_attr( $button_typography['text-transform'] ) . ';';
			}
			$css .= '}';
		}

		// Add inline style.
		if ( ! empty( $css ) ) {
			wp_add_inline_style( 'aqualuxe-style', $css );
		}
	}

	/**
	 * Add preview JS
	 */
	public function preview_js() {
		wp_enqueue_script(
			'aqualuxe-typography-preview',
			get_template_directory_uri() . '/assets/js/admin/customizer-typography-preview.js',
			array( 'customize-preview', 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
	}
}

// Initialize the class.
new Typography();