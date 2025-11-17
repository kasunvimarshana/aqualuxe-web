<?php
/**
 * General Customizer Section
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

use AquaLuxe\Customizer\Controls\Heading_Control;
use AquaLuxe\Customizer\Controls\Divider_Control;
use AquaLuxe\Customizer\Controls\Toggle_Control;
use AquaLuxe\Customizer\Controls\Slider_Control;
use AquaLuxe\Customizer\Controls\Color_Alpha_Control;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * General Customizer Section Class
 */
class General {

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
		// Add General section.
		$wp_customize->add_section(
			'aqualuxe_general',
			array(
				'title'    => esc_html__( 'General', 'aqualuxe' ),
				'priority' => 20,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Site Identity Settings.
		$wp_customize->add_setting(
			'aqualuxe_general_site_identity_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_general_site_identity_heading',
				array(
					'label'    => esc_html__( 'Site Identity', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 10,
				)
			)
		);

		// Site Title Color.
		$wp_customize->add_setting(
			'aqualuxe_site_title_color',
			array(
				'default'           => '#000000',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_site_title_color',
				array(
					'label'    => esc_html__( 'Site Title Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 20,
				)
			)
		);

		// Site Tagline Color.
		$wp_customize->add_setting(
			'aqualuxe_site_tagline_color',
			array(
				'default'           => '#666666',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_site_tagline_color',
				array(
					'label'    => esc_html__( 'Site Tagline Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 30,
				)
			)
		);

		// Show Site Title.
		$wp_customize->add_setting(
			'aqualuxe_show_site_title',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_show_site_title',
				array(
					'label'       => esc_html__( 'Show Site Title', 'aqualuxe' ),
					'description' => esc_html__( 'Display the site title in the header.', 'aqualuxe' ),
					'section'     => 'aqualuxe_general',
					'priority'    => 40,
				)
			)
		);

		// Show Site Tagline.
		$wp_customize->add_setting(
			'aqualuxe_show_site_tagline',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_show_site_tagline',
				array(
					'label'       => esc_html__( 'Show Site Tagline', 'aqualuxe' ),
					'description' => esc_html__( 'Display the site tagline in the header.', 'aqualuxe' ),
					'section'     => 'aqualuxe_general',
					'priority'    => 50,
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_general_colors_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_general_colors_divider',
				array(
					'section'  => 'aqualuxe_general',
					'priority' => 60,
				)
			)
		);

		// Color Settings.
		$wp_customize->add_setting(
			'aqualuxe_general_colors_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_general_colors_heading',
				array(
					'label'    => esc_html__( 'Colors', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 70,
				)
			)
		);

		// Primary Color.
		$wp_customize->add_setting(
			'aqualuxe_primary_color',
			array(
				'default'           => '#0073aa',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_primary_color',
				array(
					'label'    => esc_html__( 'Primary Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 80,
				)
			)
		);

		// Secondary Color.
		$wp_customize->add_setting(
			'aqualuxe_secondary_color',
			array(
				'default'           => '#6c757d',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_secondary_color',
				array(
					'label'    => esc_html__( 'Secondary Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 90,
				)
			)
		);

		// Accent Color.
		$wp_customize->add_setting(
			'aqualuxe_accent_color',
			array(
				'default'           => '#e91e63',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_accent_color',
				array(
					'label'    => esc_html__( 'Accent Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 100,
				)
			)
		);

		// Text Color.
		$wp_customize->add_setting(
			'aqualuxe_text_color',
			array(
				'default'           => '#333333',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_text_color',
				array(
					'label'    => esc_html__( 'Text Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 110,
				)
			)
		);

		// Heading Color.
		$wp_customize->add_setting(
			'aqualuxe_heading_color',
			array(
				'default'           => '#222222',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_heading_color',
				array(
					'label'    => esc_html__( 'Heading Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 120,
				)
			)
		);

		// Link Color.
		$wp_customize->add_setting(
			'aqualuxe_link_color',
			array(
				'default'           => '#0073aa',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_link_color',
				array(
					'label'    => esc_html__( 'Link Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 130,
				)
			)
		);

		// Link Hover Color.
		$wp_customize->add_setting(
			'aqualuxe_link_hover_color',
			array(
				'default'           => '#00a0d2',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_link_hover_color',
				array(
					'label'    => esc_html__( 'Link Hover Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 140,
				)
			)
		);

		// Button Background.
		$wp_customize->add_setting(
			'aqualuxe_button_background',
			array(
				'default'           => '#0073aa',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new Color_Alpha_Control(
				$wp_customize,
				'aqualuxe_button_background',
				array(
					'label'    => esc_html__( 'Button Background', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 150,
				)
			)
		);

		// Button Text Color.
		$wp_customize->add_setting(
			'aqualuxe_button_text_color',
			array(
				'default'           => '#ffffff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_button_text_color',
				array(
					'label'    => esc_html__( 'Button Text Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 160,
				)
			)
		);

		// Button Hover Background.
		$wp_customize->add_setting(
			'aqualuxe_button_hover_background',
			array(
				'default'           => '#00a0d2',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new Color_Alpha_Control(
				$wp_customize,
				'aqualuxe_button_hover_background',
				array(
					'label'    => esc_html__( 'Button Hover Background', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 170,
				)
			)
		);

		// Button Hover Text Color.
		$wp_customize->add_setting(
			'aqualuxe_button_hover_text_color',
			array(
				'default'           => '#ffffff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_button_hover_text_color',
				array(
					'label'    => esc_html__( 'Button Hover Text Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 180,
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_general_background_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_general_background_divider',
				array(
					'section'  => 'aqualuxe_general',
					'priority' => 190,
				)
			)
		);

		// Background Settings.
		$wp_customize->add_setting(
			'aqualuxe_general_background_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_general_background_heading',
				array(
					'label'    => esc_html__( 'Background', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 200,
				)
			)
		);

		// Body Background Color.
		$wp_customize->add_setting(
			'aqualuxe_body_background_color',
			array(
				'default'           => '#ffffff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new Color_Alpha_Control(
				$wp_customize,
				'aqualuxe_body_background_color',
				array(
					'label'    => esc_html__( 'Body Background Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 210,
				)
			)
		);

		// Content Background Color.
		$wp_customize->add_setting(
			'aqualuxe_content_background_color',
			array(
				'default'           => '#ffffff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new Color_Alpha_Control(
				$wp_customize,
				'aqualuxe_content_background_color',
				array(
					'label'    => esc_html__( 'Content Background Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 220,
				)
			)
		);

		// Boxed Layout Background.
		$wp_customize->add_setting(
			'aqualuxe_boxed_background',
			array(
				'default'           => '#f5f5f5',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new Color_Alpha_Control(
				$wp_customize,
				'aqualuxe_boxed_background',
				array(
					'label'       => esc_html__( 'Boxed Layout Background', 'aqualuxe' ),
					'description' => esc_html__( 'Background color for boxed layout.', 'aqualuxe' ),
					'section'     => 'aqualuxe_general',
					'priority'    => 230,
				)
			)
		);

		// Background Image.
		$wp_customize->add_setting(
			'aqualuxe_background_image',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Image_Control(
				$wp_customize,
				'aqualuxe_background_image',
				array(
					'label'    => esc_html__( 'Background Image', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 240,
				)
			)
		);

		// Background Repeat.
		$wp_customize->add_setting(
			'aqualuxe_background_repeat',
			array(
				'default'           => 'repeat',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_background_repeat',
			array(
				'label'    => esc_html__( 'Background Repeat', 'aqualuxe' ),
				'section'  => 'aqualuxe_general',
				'type'     => 'select',
				'priority' => 250,
				'choices'  => array(
					'no-repeat' => esc_html__( 'No Repeat', 'aqualuxe' ),
					'repeat'    => esc_html__( 'Repeat', 'aqualuxe' ),
					'repeat-x'  => esc_html__( 'Repeat Horizontally', 'aqualuxe' ),
					'repeat-y'  => esc_html__( 'Repeat Vertically', 'aqualuxe' ),
				),
				'active_callback' => array( $this, 'has_background_image' ),
			)
		);

		// Background Position.
		$wp_customize->add_setting(
			'aqualuxe_background_position',
			array(
				'default'           => 'center',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_background_position',
			array(
				'label'    => esc_html__( 'Background Position', 'aqualuxe' ),
				'section'  => 'aqualuxe_general',
				'type'     => 'select',
				'priority' => 260,
				'choices'  => array(
					'left top'      => esc_html__( 'Left Top', 'aqualuxe' ),
					'left center'   => esc_html__( 'Left Center', 'aqualuxe' ),
					'left bottom'   => esc_html__( 'Left Bottom', 'aqualuxe' ),
					'center top'    => esc_html__( 'Center Top', 'aqualuxe' ),
					'center'        => esc_html__( 'Center', 'aqualuxe' ),
					'center bottom' => esc_html__( 'Center Bottom', 'aqualuxe' ),
					'right top'     => esc_html__( 'Right Top', 'aqualuxe' ),
					'right center'  => esc_html__( 'Right Center', 'aqualuxe' ),
					'right bottom'  => esc_html__( 'Right Bottom', 'aqualuxe' ),
				),
				'active_callback' => array( $this, 'has_background_image' ),
			)
		);

		// Background Attachment.
		$wp_customize->add_setting(
			'aqualuxe_background_attachment',
			array(
				'default'           => 'scroll',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_background_attachment',
			array(
				'label'    => esc_html__( 'Background Attachment', 'aqualuxe' ),
				'section'  => 'aqualuxe_general',
				'type'     => 'select',
				'priority' => 270,
				'choices'  => array(
					'scroll' => esc_html__( 'Scroll', 'aqualuxe' ),
					'fixed'  => esc_html__( 'Fixed', 'aqualuxe' ),
				),
				'active_callback' => array( $this, 'has_background_image' ),
			)
		);

		// Background Size.
		$wp_customize->add_setting(
			'aqualuxe_background_size',
			array(
				'default'           => 'auto',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_background_size',
			array(
				'label'    => esc_html__( 'Background Size', 'aqualuxe' ),
				'section'  => 'aqualuxe_general',
				'type'     => 'select',
				'priority' => 280,
				'choices'  => array(
					'auto'    => esc_html__( 'Auto', 'aqualuxe' ),
					'cover'   => esc_html__( 'Cover', 'aqualuxe' ),
					'contain' => esc_html__( 'Contain', 'aqualuxe' ),
				),
				'active_callback' => array( $this, 'has_background_image' ),
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_general_buttons_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_general_buttons_divider',
				array(
					'section'  => 'aqualuxe_general',
					'priority' => 290,
				)
			)
		);

		// Button Settings.
		$wp_customize->add_setting(
			'aqualuxe_general_buttons_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_general_buttons_heading',
				array(
					'label'    => esc_html__( 'Buttons', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 300,
				)
			)
		);

		// Button Style.
		$wp_customize->add_setting(
			'aqualuxe_button_style',
			array(
				'default'           => 'rounded',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_button_style',
			array(
				'label'    => esc_html__( 'Button Style', 'aqualuxe' ),
				'section'  => 'aqualuxe_general',
				'type'     => 'select',
				'priority' => 310,
				'choices'  => array(
					'default' => esc_html__( 'Default', 'aqualuxe' ),
					'rounded' => esc_html__( 'Rounded', 'aqualuxe' ),
					'pill'    => esc_html__( 'Pill', 'aqualuxe' ),
					'square'  => esc_html__( 'Square', 'aqualuxe' ),
				),
			)
		);

		// Button Border Width.
		$wp_customize->add_setting(
			'aqualuxe_button_border_width',
			array(
				'default'           => 0,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_button_border_width',
				array(
					'label'       => esc_html__( 'Button Border Width (px)', 'aqualuxe' ),
					'section'     => 'aqualuxe_general',
					'priority'    => 320,
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					),
				)
			)
		);

		// Button Border Color.
		$wp_customize->add_setting(
			'aqualuxe_button_border_color',
			array(
				'default'           => '#0073aa',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_button_border_color',
				array(
					'label'           => esc_html__( 'Button Border Color', 'aqualuxe' ),
					'section'         => 'aqualuxe_general',
					'priority'        => 330,
					'active_callback' => array( $this, 'has_button_border' ),
				)
			)
		);

		// Button Hover Border Color.
		$wp_customize->add_setting(
			'aqualuxe_button_hover_border_color',
			array(
				'default'           => '#00a0d2',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_button_hover_border_color',
				array(
					'label'           => esc_html__( 'Button Hover Border Color', 'aqualuxe' ),
					'section'         => 'aqualuxe_general',
					'priority'        => 340,
					'active_callback' => array( $this, 'has_button_border' ),
				)
			)
		);

		// Button Padding.
		$wp_customize->add_setting(
			'aqualuxe_button_padding',
			array(
				'default'           => 'medium',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_button_padding',
			array(
				'label'    => esc_html__( 'Button Padding', 'aqualuxe' ),
				'section'  => 'aqualuxe_general',
				'type'     => 'select',
				'priority' => 350,
				'choices'  => array(
					'small'  => esc_html__( 'Small', 'aqualuxe' ),
					'medium' => esc_html__( 'Medium', 'aqualuxe' ),
					'large'  => esc_html__( 'Large', 'aqualuxe' ),
				),
			)
		);

		// Button Text Transform.
		$wp_customize->add_setting(
			'aqualuxe_button_text_transform',
			array(
				'default'           => 'none',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_button_text_transform',
			array(
				'label'    => esc_html__( 'Button Text Transform', 'aqualuxe' ),
				'section'  => 'aqualuxe_general',
				'type'     => 'select',
				'priority' => 360,
				'choices'  => array(
					'none'       => esc_html__( 'None', 'aqualuxe' ),
					'capitalize' => esc_html__( 'Capitalize', 'aqualuxe' ),
					'uppercase'  => esc_html__( 'Uppercase', 'aqualuxe' ),
					'lowercase'  => esc_html__( 'Lowercase', 'aqualuxe' ),
				),
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_general_forms_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_general_forms_divider',
				array(
					'section'  => 'aqualuxe_general',
					'priority' => 370,
				)
			)
		);

		// Form Settings.
		$wp_customize->add_setting(
			'aqualuxe_general_forms_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_general_forms_heading',
				array(
					'label'    => esc_html__( 'Forms', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 380,
				)
			)
		);

		// Form Style.
		$wp_customize->add_setting(
			'aqualuxe_form_style',
			array(
				'default'           => 'default',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_form_style',
			array(
				'label'    => esc_html__( 'Form Style', 'aqualuxe' ),
				'section'  => 'aqualuxe_general',
				'type'     => 'select',
				'priority' => 390,
				'choices'  => array(
					'default' => esc_html__( 'Default', 'aqualuxe' ),
					'modern'  => esc_html__( 'Modern', 'aqualuxe' ),
					'flat'    => esc_html__( 'Flat', 'aqualuxe' ),
					'outline' => esc_html__( 'Outline', 'aqualuxe' ),
				),
			)
		);

		// Input Border Radius.
		$wp_customize->add_setting(
			'aqualuxe_input_border_radius',
			array(
				'default'           => 3,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_input_border_radius',
				array(
					'label'       => esc_html__( 'Input Border Radius (px)', 'aqualuxe' ),
					'section'     => 'aqualuxe_general',
					'priority'    => 400,
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 20,
						'step' => 1,
					),
				)
			)
		);

		// Input Background Color.
		$wp_customize->add_setting(
			'aqualuxe_input_background_color',
			array(
				'default'           => '#ffffff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new Color_Alpha_Control(
				$wp_customize,
				'aqualuxe_input_background_color',
				array(
					'label'    => esc_html__( 'Input Background Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 410,
				)
			)
		);

		// Input Text Color.
		$wp_customize->add_setting(
			'aqualuxe_input_text_color',
			array(
				'default'           => '#333333',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_input_text_color',
				array(
					'label'    => esc_html__( 'Input Text Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 420,
				)
			)
		);

		// Input Border Color.
		$wp_customize->add_setting(
			'aqualuxe_input_border_color',
			array(
				'default'           => '#dddddd',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_input_border_color',
				array(
					'label'    => esc_html__( 'Input Border Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 430,
				)
			)
		);

		// Input Focus Border Color.
		$wp_customize->add_setting(
			'aqualuxe_input_focus_border_color',
			array(
				'default'           => '#0073aa',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_input_focus_border_color',
				array(
					'label'    => esc_html__( 'Input Focus Border Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 440,
				)
			)
		);

		// Label Color.
		$wp_customize->add_setting(
			'aqualuxe_label_color',
			array(
				'default'           => '#333333',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_label_color',
				array(
					'label'    => esc_html__( 'Label Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_general',
					'priority' => 450,
				)
			)
		);
	}

	/**
	 * Check if background image is set
	 *
	 * @return bool
	 */
	public function has_background_image() {
		return ! empty( get_theme_mod( 'aqualuxe_background_image', '' ) );
	}

	/**
	 * Check if button border is set
	 *
	 * @return bool
	 */
	public function has_button_border() {
		return get_theme_mod( 'aqualuxe_button_border_width', 0 ) > 0;
	}

	/**
	 * Sanitize choices
	 *
	 * @param string $input Value to sanitize.
	 * @param object $setting Setting object.
	 * @return string Sanitized value.
	 */
	public function sanitize_choices( $input, $setting ) {
		// Get the list of possible choices.
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// Return input if valid or return default.
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}

	/**
	 * Enqueue frontend styles
	 */
	public function enqueue_styles() {
		// Get general settings.
		$site_title_color     = get_theme_mod( 'aqualuxe_site_title_color', '#000000' );
		$site_tagline_color   = get_theme_mod( 'aqualuxe_site_tagline_color', '#666666' );
		$primary_color        = get_theme_mod( 'aqualuxe_primary_color', '#0073aa' );
		$secondary_color      = get_theme_mod( 'aqualuxe_secondary_color', '#6c757d' );
		$accent_color         = get_theme_mod( 'aqualuxe_accent_color', '#e91e63' );
		$text_color           = get_theme_mod( 'aqualuxe_text_color', '#333333' );
		$heading_color        = get_theme_mod( 'aqualuxe_heading_color', '#222222' );
		$link_color           = get_theme_mod( 'aqualuxe_link_color', '#0073aa' );
		$link_hover_color     = get_theme_mod( 'aqualuxe_link_hover_color', '#00a0d2' );
		$button_bg            = get_theme_mod( 'aqualuxe_button_background', '#0073aa' );
		$button_text          = get_theme_mod( 'aqualuxe_button_text_color', '#ffffff' );
		$button_hover_bg      = get_theme_mod( 'aqualuxe_button_hover_background', '#00a0d2' );
		$button_hover_text    = get_theme_mod( 'aqualuxe_button_hover_text_color', '#ffffff' );
		$body_bg              = get_theme_mod( 'aqualuxe_body_background_color', '#ffffff' );
		$content_bg           = get_theme_mod( 'aqualuxe_content_background_color', '#ffffff' );
		$boxed_bg             = get_theme_mod( 'aqualuxe_boxed_background', '#f5f5f5' );
		$background_image     = get_theme_mod( 'aqualuxe_background_image', '' );
		$background_repeat    = get_theme_mod( 'aqualuxe_background_repeat', 'repeat' );
		$background_position  = get_theme_mod( 'aqualuxe_background_position', 'center' );
		$background_attachment = get_theme_mod( 'aqualuxe_background_attachment', 'scroll' );
		$background_size      = get_theme_mod( 'aqualuxe_background_size', 'auto' );
		$button_style         = get_theme_mod( 'aqualuxe_button_style', 'rounded' );
		$button_border_width  = get_theme_mod( 'aqualuxe_button_border_width', 0 );
		$button_border_color  = get_theme_mod( 'aqualuxe_button_border_color', '#0073aa' );
		$button_hover_border  = get_theme_mod( 'aqualuxe_button_hover_border_color', '#00a0d2' );
		$button_padding       = get_theme_mod( 'aqualuxe_button_padding', 'medium' );
		$button_text_transform = get_theme_mod( 'aqualuxe_button_text_transform', 'none' );
		$form_style           = get_theme_mod( 'aqualuxe_form_style', 'default' );
		$input_border_radius  = get_theme_mod( 'aqualuxe_input_border_radius', 3 );
		$input_bg             = get_theme_mod( 'aqualuxe_input_background_color', '#ffffff' );
		$input_text           = get_theme_mod( 'aqualuxe_input_text_color', '#333333' );
		$input_border         = get_theme_mod( 'aqualuxe_input_border_color', '#dddddd' );
		$input_focus_border   = get_theme_mod( 'aqualuxe_input_focus_border_color', '#0073aa' );
		$label_color          = get_theme_mod( 'aqualuxe_label_color', '#333333' );

		// Generate inline styles.
		$css = '';

		// Site identity.
		$css .= '.site-title a {';
		$css .= 'color: ' . esc_attr( $site_title_color ) . ';';
		$css .= '}';

		$css .= '.site-description {';
		$css .= 'color: ' . esc_attr( $site_tagline_color ) . ';';
		$css .= '}';

		// Colors.
		$css .= 'body {';
		$css .= 'color: ' . esc_attr( $text_color ) . ';';
		$css .= 'background-color: ' . esc_attr( $body_bg ) . ';';
		
		if ( ! empty( $background_image ) ) {
			$css .= 'background-image: url(' . esc_url( $background_image ) . ');';
			$css .= 'background-repeat: ' . esc_attr( $background_repeat ) . ';';
			$css .= 'background-position: ' . esc_attr( $background_position ) . ';';
			$css .= 'background-attachment: ' . esc_attr( $background_attachment ) . ';';
			$css .= 'background-size: ' . esc_attr( $background_size ) . ';';
		}
		
		$css .= '}';

		$css .= '#primary {';
		$css .= 'background-color: ' . esc_attr( $content_bg ) . ';';
		$css .= '}';

		$css .= 'body.boxed-layout {';
		$css .= 'background-color: ' . esc_attr( $boxed_bg ) . ';';
		$css .= '}';

		$css .= 'h1, h2, h3, h4, h5, h6 {';
		$css .= 'color: ' . esc_attr( $heading_color ) . ';';
		$css .= '}';

		$css .= 'a {';
		$css .= 'color: ' . esc_attr( $link_color ) . ';';
		$css .= '}';

		$css .= 'a:hover, a:focus {';
		$css .= 'color: ' . esc_attr( $link_hover_color ) . ';';
		$css .= '}';

		// Buttons.
		$css .= '.button, button, input[type="button"], input[type="reset"], input[type="submit"] {';
		$css .= 'background-color: ' . esc_attr( $button_bg ) . ';';
		$css .= 'color: ' . esc_attr( $button_text ) . ';';
		$css .= 'text-transform: ' . esc_attr( $button_text_transform ) . ';';
		
		// Button border.
		if ( $button_border_width > 0 ) {
			$css .= 'border: ' . absint( $button_border_width ) . 'px solid ' . esc_attr( $button_border_color ) . ';';
		} else {
			$css .= 'border: none;';
		}
		
		// Button style.
		switch ( $button_style ) {
			case 'rounded':
				$css .= 'border-radius: 4px;';
				break;
			case 'pill':
				$css .= 'border-radius: 50px;';
				break;
			case 'square':
				$css .= 'border-radius: 0;';
				break;
			default:
				$css .= 'border-radius: 3px;';
		}
		
		// Button padding.
		switch ( $button_padding ) {
			case 'small':
				$css .= 'padding: 5px 15px;';
				break;
			case 'medium':
				$css .= 'padding: 10px 20px;';
				break;
			case 'large':
				$css .= 'padding: 15px 30px;';
				break;
		}
		
		$css .= '}';

		$css .= '.button:hover, button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover {';
		$css .= 'background-color: ' . esc_attr( $button_hover_bg ) . ';';
		$css .= 'color: ' . esc_attr( $button_hover_text ) . ';';
		
		if ( $button_border_width > 0 ) {
			$css .= 'border-color: ' . esc_attr( $button_hover_border ) . ';';
		}
		
		$css .= '}';

		// Forms.
		$css .= 'input[type="text"], input[type="email"], input[type="url"], input[type="password"], input[type="search"], input[type="number"], input[type="tel"], input[type="range"], input[type="date"], input[type="month"], input[type="week"], input[type="time"], input[type="datetime"], input[type="datetime-local"], input[type="color"], textarea, select {';
		$css .= 'background-color: ' . esc_attr( $input_bg ) . ';';
		$css .= 'color: ' . esc_attr( $input_text ) . ';';
		$css .= 'border-radius: ' . absint( $input_border_radius ) . 'px;';
		
		// Form style.
		switch ( $form_style ) {
			case 'modern':
				$css .= 'border: none;';
				$css .= 'box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);';
				break;
			case 'flat':
				$css .= 'border: none;';
				$css .= 'background-color: #f5f5f5;';
				break;
			case 'outline':
				$css .= 'border: 2px solid ' . esc_attr( $input_border ) . ';';
				$css .= 'background-color: transparent;';
				break;
			default:
				$css .= 'border: 1px solid ' . esc_attr( $input_border ) . ';';
		}
		
		$css .= '}';

		$css .= 'input[type="text"]:focus, input[type="email"]:focus, input[type="url"]:focus, input[type="password"]:focus, input[type="search"]:focus, input[type="number"]:focus, input[type="tel"]:focus, input[type="range"]:focus, input[type="date"]:focus, input[type="month"]:focus, input[type="week"]:focus, input[type="time"]:focus, input[type="datetime"]:focus, input[type="datetime-local"]:focus, input[type="color"]:focus, textarea:focus, select:focus {';
		
		// Form focus style.
		switch ( $form_style ) {
			case 'modern':
				$css .= 'box-shadow: 0 3px 5px rgba(0, 0, 0, 0.2);';
				break;
			case 'flat':
				$css .= 'background-color: #eeeeee;';
				break;
			case 'outline':
				$css .= 'border-color: ' . esc_attr( $input_focus_border ) . ';';
				break;
			default:
				$css .= 'border-color: ' . esc_attr( $input_focus_border ) . ';';
		}
		
		$css .= '}';

		$css .= 'label {';
		$css .= 'color: ' . esc_attr( $label_color ) . ';';
		$css .= '}';

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
			'aqualuxe-general-preview',
			get_template_directory_uri() . '/assets/js/admin/customizer-general-preview.js',
			array( 'customize-preview', 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
	}
}

// Initialize the class.
new General();