<?php
/**
 * Header Customizer Section
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Sections;

use AquaLuxe\Customizer\Controls\Radio_Image_Control;
use AquaLuxe\Customizer\Controls\Slider_Control;
use AquaLuxe\Customizer\Controls\Dimensions_Control;
use AquaLuxe\Customizer\Controls\Heading_Control;
use AquaLuxe\Customizer\Controls\Divider_Control;
use AquaLuxe\Customizer\Controls\Toggle_Control;
use AquaLuxe\Customizer\Controls\Color_Alpha_Control;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Header Customizer Section Class
 */
class Header {

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
		// Add Header section.
		$wp_customize->add_section(
			'aqualuxe_header',
			array(
				'title'    => esc_html__( 'Header', 'aqualuxe' ),
				'priority' => 50,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Header Layout.
		$wp_customize->add_setting(
			'aqualuxe_header_layout_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_header_layout_heading',
				array(
					'label'    => esc_html__( 'Header Layout', 'aqualuxe' ),
					'section'  => 'aqualuxe_header',
					'priority' => 10,
				)
			)
		);

		// Header Style.
		$wp_customize->add_setting(
			'aqualuxe_header_style',
			array(
				'default'           => 'standard',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_header_style',
				array(
					'label'    => esc_html__( 'Header Style', 'aqualuxe' ),
					'section'  => 'aqualuxe_header',
					'priority' => 20,
					'choices'  => array(
						'standard'   => array(
							'label' => esc_html__( 'Standard', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/header-standard.svg',
						),
						'centered'   => array(
							'label' => esc_html__( 'Centered', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/header-centered.svg',
						),
						'split'      => array(
							'label' => esc_html__( 'Split', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/header-split.svg',
						),
						'transparent' => array(
							'label' => esc_html__( 'Transparent', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/header-transparent.svg',
						),
					),
				)
			)
		);

		// Header Width.
		$wp_customize->add_setting(
			'aqualuxe_header_width',
			array(
				'default'           => 'contained',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_header_width',
				array(
					'label'    => esc_html__( 'Header Width', 'aqualuxe' ),
					'section'  => 'aqualuxe_header',
					'priority' => 30,
					'choices'  => array(
						'contained' => array(
							'label' => esc_html__( 'Contained', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/header-contained.svg',
						),
						'full'      => array(
							'label' => esc_html__( 'Full Width', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/header-full.svg',
						),
					),
				)
			)
		);

		// Header Height.
		$wp_customize->add_setting(
			'aqualuxe_header_height',
			array(
				'default'           => 80,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_header_height',
				array(
					'label'       => esc_html__( 'Header Height (px)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the height of the header.', 'aqualuxe' ),
					'section'     => 'aqualuxe_header',
					'priority'    => 40,
					'input_attrs' => array(
						'min'  => 50,
						'max'  => 200,
						'step' => 1,
					),
				)
			)
		);

		// Sticky Header.
		$wp_customize->add_setting(
			'aqualuxe_sticky_header',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_sticky_header',
				array(
					'label'       => esc_html__( 'Sticky Header', 'aqualuxe' ),
					'description' => esc_html__( 'Enable sticky header that stays at the top when scrolling.', 'aqualuxe' ),
					'section'     => 'aqualuxe_header',
					'priority'    => 50,
				)
			)
		);

		// Sticky Header Style.
		$wp_customize->add_setting(
			'aqualuxe_sticky_header_style',
			array(
				'default'           => 'slide',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
				'active_callback'   => array( $this, 'is_sticky_header_enabled' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_sticky_header_style',
			array(
				'label'    => esc_html__( 'Sticky Header Style', 'aqualuxe' ),
				'section'  => 'aqualuxe_header',
				'type'     => 'select',
				'priority' => 60,
				'choices'  => array(
					'fade'  => esc_html__( 'Fade In', 'aqualuxe' ),
					'slide' => esc_html__( 'Slide Down', 'aqualuxe' ),
					'fixed' => esc_html__( 'Fixed', 'aqualuxe' ),
				),
				'active_callback' => array( $this, 'is_sticky_header_enabled' ),
			)
		);

		// Sticky Header Height.
		$wp_customize->add_setting(
			'aqualuxe_sticky_header_height',
			array(
				'default'           => 60,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
				'active_callback'   => array( $this, 'is_sticky_header_enabled' ),
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_sticky_header_height',
				array(
					'label'       => esc_html__( 'Sticky Header Height (px)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the height of the sticky header.', 'aqualuxe' ),
					'section'     => 'aqualuxe_header',
					'priority'    => 70,
					'input_attrs' => array(
						'min'  => 40,
						'max'  => 150,
						'step' => 1,
					),
					'active_callback' => array( $this, 'is_sticky_header_enabled' ),
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_header_logo_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_header_logo_divider',
				array(
					'section'  => 'aqualuxe_header',
					'priority' => 80,
				)
			)
		);

		// Logo Settings.
		$wp_customize->add_setting(
			'aqualuxe_header_logo_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_header_logo_heading',
				array(
					'label'    => esc_html__( 'Logo Settings', 'aqualuxe' ),
					'section'  => 'aqualuxe_header',
					'priority' => 90,
				)
			)
		);

		// Logo Max Width.
		$wp_customize->add_setting(
			'aqualuxe_logo_max_width',
			array(
				'default'           => 200,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_logo_max_width',
				array(
					'label'       => esc_html__( 'Logo Max Width (px)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the maximum width of the logo.', 'aqualuxe' ),
					'section'     => 'aqualuxe_header',
					'priority'    => 100,
					'input_attrs' => array(
						'min'  => 50,
						'max'  => 500,
						'step' => 1,
					),
				)
			)
		);

		// Logo Max Height.
		$wp_customize->add_setting(
			'aqualuxe_logo_max_height',
			array(
				'default'           => 60,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_logo_max_height',
				array(
					'label'       => esc_html__( 'Logo Max Height (px)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the maximum height of the logo.', 'aqualuxe' ),
					'section'     => 'aqualuxe_header',
					'priority'    => 110,
					'input_attrs' => array(
						'min'  => 20,
						'max'  => 200,
						'step' => 1,
					),
				)
			)
		);

		// Mobile Logo.
		$wp_customize->add_setting(
			'aqualuxe_mobile_logo',
			array(
				'default'           => '',
				'sanitize_callback' => 'esc_url_raw',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Image_Control(
				$wp_customize,
				'aqualuxe_mobile_logo',
				array(
					'label'       => esc_html__( 'Mobile Logo', 'aqualuxe' ),
					'description' => esc_html__( 'Upload a different logo for mobile devices.', 'aqualuxe' ),
					'section'     => 'aqualuxe_header',
					'priority'    => 120,
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_header_navigation_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_header_navigation_divider',
				array(
					'section'  => 'aqualuxe_header',
					'priority' => 130,
				)
			)
		);

		// Navigation Settings.
		$wp_customize->add_setting(
			'aqualuxe_header_navigation_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_header_navigation_heading',
				array(
					'label'    => esc_html__( 'Navigation Settings', 'aqualuxe' ),
					'section'  => 'aqualuxe_header',
					'priority' => 140,
				)
			)
		);

		// Menu Item Spacing.
		$wp_customize->add_setting(
			'aqualuxe_menu_item_spacing',
			array(
				'default'           => 20,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_menu_item_spacing',
				array(
					'label'       => esc_html__( 'Menu Item Spacing (px)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the spacing between menu items.', 'aqualuxe' ),
					'section'     => 'aqualuxe_header',
					'priority'    => 150,
					'input_attrs' => array(
						'min'  => 10,
						'max'  => 50,
						'step' => 1,
					),
				)
			)
		);

		// Dropdown Width.
		$wp_customize->add_setting(
			'aqualuxe_dropdown_width',
			array(
				'default'           => 220,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_dropdown_width',
				array(
					'label'       => esc_html__( 'Dropdown Width (px)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the width of dropdown menus.', 'aqualuxe' ),
					'section'     => 'aqualuxe_header',
					'priority'    => 160,
					'input_attrs' => array(
						'min'  => 150,
						'max'  => 400,
						'step' => 1,
					),
				)
			)
		);

		// Mobile Menu Style.
		$wp_customize->add_setting(
			'aqualuxe_mobile_menu_style',
			array(
				'default'           => 'dropdown',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_mobile_menu_style',
			array(
				'label'    => esc_html__( 'Mobile Menu Style', 'aqualuxe' ),
				'section'  => 'aqualuxe_header',
				'type'     => 'select',
				'priority' => 170,
				'choices'  => array(
					'dropdown' => esc_html__( 'Dropdown', 'aqualuxe' ),
					'offcanvas' => esc_html__( 'Off-Canvas', 'aqualuxe' ),
					'fullscreen' => esc_html__( 'Fullscreen', 'aqualuxe' ),
				),
			)
		);

		// Mobile Menu Breakpoint.
		$wp_customize->add_setting(
			'aqualuxe_mobile_menu_breakpoint',
			array(
				'default'           => 768,
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_mobile_menu_breakpoint',
				array(
					'label'       => esc_html__( 'Mobile Menu Breakpoint (px)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls when the mobile menu appears.', 'aqualuxe' ),
					'section'     => 'aqualuxe_header',
					'priority'    => 180,
					'input_attrs' => array(
						'min'  => 480,
						'max'  => 1200,
						'step' => 1,
					),
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_header_elements_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_header_elements_divider',
				array(
					'section'  => 'aqualuxe_header',
					'priority' => 190,
				)
			)
		);

		// Header Elements.
		$wp_customize->add_setting(
			'aqualuxe_header_elements_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_header_elements_heading',
				array(
					'label'    => esc_html__( 'Header Elements', 'aqualuxe' ),
					'section'  => 'aqualuxe_header',
					'priority' => 200,
				)
			)
		);

		// Show Search.
		$wp_customize->add_setting(
			'aqualuxe_header_search',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_header_search',
				array(
					'label'       => esc_html__( 'Show Search', 'aqualuxe' ),
					'description' => esc_html__( 'Display search icon in the header.', 'aqualuxe' ),
					'section'     => 'aqualuxe_header',
					'priority'    => 210,
				)
			)
		);

		// Show Cart.
		$wp_customize->add_setting(
			'aqualuxe_header_cart',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_header_cart',
				array(
					'label'       => esc_html__( 'Show Cart', 'aqualuxe' ),
					'description' => esc_html__( 'Display cart icon in the header (requires WooCommerce).', 'aqualuxe' ),
					'section'     => 'aqualuxe_header',
					'priority'    => 220,
				)
			)
		);

		// Show Account.
		$wp_customize->add_setting(
			'aqualuxe_header_account',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_header_account',
				array(
					'label'       => esc_html__( 'Show Account', 'aqualuxe' ),
					'description' => esc_html__( 'Display account icon in the header.', 'aqualuxe' ),
					'section'     => 'aqualuxe_header',
					'priority'    => 230,
				)
			)
		);

		// Show Wishlist.
		$wp_customize->add_setting(
			'aqualuxe_header_wishlist',
			array(
				'default'           => false,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_header_wishlist',
				array(
					'label'       => esc_html__( 'Show Wishlist', 'aqualuxe' ),
					'description' => esc_html__( 'Display wishlist icon in the header (requires WooCommerce).', 'aqualuxe' ),
					'section'     => 'aqualuxe_header',
					'priority'    => 240,
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_header_colors_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_header_colors_divider',
				array(
					'section'  => 'aqualuxe_header',
					'priority' => 250,
				)
			)
		);

		// Header Colors.
		$wp_customize->add_setting(
			'aqualuxe_header_colors_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_header_colors_heading',
				array(
					'label'    => esc_html__( 'Header Colors', 'aqualuxe' ),
					'section'  => 'aqualuxe_header',
					'priority' => 260,
				)
			)
		);

		// Header Background.
		$wp_customize->add_setting(
			'aqualuxe_header_background',
			array(
				'default'           => '#ffffff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new Color_Alpha_Control(
				$wp_customize,
				'aqualuxe_header_background',
				array(
					'label'    => esc_html__( 'Header Background', 'aqualuxe' ),
					'section'  => 'aqualuxe_header',
					'priority' => 270,
				)
			)
		);

		// Header Text Color.
		$wp_customize->add_setting(
			'aqualuxe_header_text_color',
			array(
				'default'           => '#333333',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_header_text_color',
				array(
					'label'    => esc_html__( 'Header Text Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_header',
					'priority' => 280,
				)
			)
		);

		// Menu Link Color.
		$wp_customize->add_setting(
			'aqualuxe_menu_link_color',
			array(
				'default'           => '#333333',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_menu_link_color',
				array(
					'label'    => esc_html__( 'Menu Link Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_header',
					'priority' => 290,
				)
			)
		);

		// Menu Link Hover Color.
		$wp_customize->add_setting(
			'aqualuxe_menu_link_hover_color',
			array(
				'default'           => '#0073aa',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_menu_link_hover_color',
				array(
					'label'    => esc_html__( 'Menu Link Hover Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_header',
					'priority' => 300,
				)
			)
		);

		// Dropdown Background.
		$wp_customize->add_setting(
			'aqualuxe_dropdown_background',
			array(
				'default'           => '#ffffff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new Color_Alpha_Control(
				$wp_customize,
				'aqualuxe_dropdown_background',
				array(
					'label'    => esc_html__( 'Dropdown Background', 'aqualuxe' ),
					'section'  => 'aqualuxe_header',
					'priority' => 310,
				)
			)
		);

		// Dropdown Link Color.
		$wp_customize->add_setting(
			'aqualuxe_dropdown_link_color',
			array(
				'default'           => '#333333',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_dropdown_link_color',
				array(
					'label'    => esc_html__( 'Dropdown Link Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_header',
					'priority' => 320,
				)
			)
		);

		// Dropdown Link Hover Color.
		$wp_customize->add_setting(
			'aqualuxe_dropdown_link_hover_color',
			array(
				'default'           => '#0073aa',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_dropdown_link_hover_color',
				array(
					'label'    => esc_html__( 'Dropdown Link Hover Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_header',
					'priority' => 330,
				)
			)
		);
	}

	/**
	 * Check if sticky header is enabled
	 *
	 * @return bool
	 */
	public function is_sticky_header_enabled() {
		return get_theme_mod( 'aqualuxe_sticky_header', true );
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
		// Get header settings.
		$header_style      = get_theme_mod( 'aqualuxe_header_style', 'standard' );
		$header_width      = get_theme_mod( 'aqualuxe_header_width', 'contained' );
		$header_height     = get_theme_mod( 'aqualuxe_header_height', 80 );
		$sticky_header     = get_theme_mod( 'aqualuxe_sticky_header', true );
		$sticky_height     = get_theme_mod( 'aqualuxe_sticky_header_height', 60 );
		$logo_max_width    = get_theme_mod( 'aqualuxe_logo_max_width', 200 );
		$logo_max_height   = get_theme_mod( 'aqualuxe_logo_max_height', 60 );
		$menu_item_spacing = get_theme_mod( 'aqualuxe_menu_item_spacing', 20 );
		$dropdown_width    = get_theme_mod( 'aqualuxe_dropdown_width', 220 );
		$header_bg         = get_theme_mod( 'aqualuxe_header_background', '#ffffff' );
		$header_text       = get_theme_mod( 'aqualuxe_header_text_color', '#333333' );
		$menu_link         = get_theme_mod( 'aqualuxe_menu_link_color', '#333333' );
		$menu_link_hover   = get_theme_mod( 'aqualuxe_menu_link_hover_color', '#0073aa' );
		$dropdown_bg       = get_theme_mod( 'aqualuxe_dropdown_background', '#ffffff' );
		$dropdown_link     = get_theme_mod( 'aqualuxe_dropdown_link_color', '#333333' );
		$dropdown_hover    = get_theme_mod( 'aqualuxe_dropdown_link_hover_color', '#0073aa' );

		// Generate inline styles.
		$css = '';

		// Header height.
		$css .= '.site-header {';
		$css .= 'height: ' . absint( $header_height ) . 'px;';
		$css .= '}';

		// Header background.
		$css .= '.site-header {';
		$css .= 'background-color: ' . esc_attr( $header_bg ) . ';';
		$css .= 'color: ' . esc_attr( $header_text ) . ';';
		$css .= '}';

		// Header width.
		if ( 'full' === $header_width ) {
			$css .= '.site-header .header-inner {';
			$css .= 'max-width: 100%;';
			$css .= 'padding-left: 30px;';
			$css .= 'padding-right: 30px;';
			$css .= '}';
		}

		// Logo dimensions.
		$css .= '.site-branding img {';
		$css .= 'max-width: ' . absint( $logo_max_width ) . 'px;';
		$css .= 'max-height: ' . absint( $logo_max_height ) . 'px;';
		$css .= '}';

		// Menu item spacing.
		$css .= '.main-navigation ul li {';
		$css .= 'margin-right: ' . absint( $menu_item_spacing ) . 'px;';
		$css .= '}';

		$css .= '.main-navigation ul li:last-child {';
		$css .= 'margin-right: 0;';
		$css .= '}';

		// Menu colors.
		$css .= '.main-navigation ul li a {';
		$css .= 'color: ' . esc_attr( $menu_link ) . ';';
		$css .= '}';

		$css .= '.main-navigation ul li a:hover, .main-navigation ul li.current-menu-item > a {';
		$css .= 'color: ' . esc_attr( $menu_link_hover ) . ';';
		$css .= '}';

		// Dropdown styles.
		$css .= '.main-navigation ul ul {';
		$css .= 'width: ' . absint( $dropdown_width ) . 'px;';
		$css .= 'background-color: ' . esc_attr( $dropdown_bg ) . ';';
		$css .= '}';

		$css .= '.main-navigation ul ul li a {';
		$css .= 'color: ' . esc_attr( $dropdown_link ) . ';';
		$css .= '}';

		$css .= '.main-navigation ul ul li a:hover, .main-navigation ul ul li.current-menu-item > a {';
		$css .= 'color: ' . esc_attr( $dropdown_hover ) . ';';
		$css .= '}';

		// Sticky header.
		if ( $sticky_header ) {
			$css .= '.site-header.sticky {';
			$css .= 'position: fixed;';
			$css .= 'top: 0;';
			$css .= 'left: 0;';
			$css .= 'width: 100%;';
			$css .= 'z-index: 999;';
			$css .= 'height: ' . absint( $sticky_height ) . 'px;';
			$css .= 'box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);';
			$css .= '}';

			$css .= '.site-header.sticky .site-branding img {';
			$css .= 'max-height: ' . ( absint( $sticky_height ) - 20 ) . 'px;';
			$css .= '}';

			$css .= 'body.admin-bar .site-header.sticky {';
			$css .= 'top: 32px;';
			$css .= '}';

			$css .= '@media screen and (max-width: 782px) {';
			$css .= 'body.admin-bar .site-header.sticky {';
			$css .= 'top: 46px;';
			$css .= '}';
			$css .= '}';
		}

		// Transparent header.
		if ( 'transparent' === $header_style ) {
			$css .= '.home .site-header:not(.sticky) {';
			$css .= 'position: absolute;';
			$css .= 'top: 0;';
			$css .= 'left: 0;';
			$css .= 'width: 100%;';
			$css .= 'z-index: 999;';
			$css .= 'background-color: transparent;';
			$css .= '}';

			$css .= '.home .site-header:not(.sticky) .main-navigation ul li a {';
			$css .= 'color: #ffffff;';
			$css .= '}';

			$css .= '.home .site-header:not(.sticky) .site-title a, .home .site-header:not(.sticky) .site-description {';
			$css .= 'color: #ffffff;';
			$css .= '}';

			$css .= '.home .site-header:not(.sticky) .main-navigation ul li a:hover, .home .site-header:not(.sticky) .main-navigation ul li.current-menu-item > a {';
			$css .= 'color: rgba(255, 255, 255, 0.8);';
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
			'aqualuxe-header-preview',
			get_template_directory_uri() . '/assets/js/admin/customizer-header-preview.js',
			array( 'customize-preview', 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
	}
}

// Initialize the class.
new Header();