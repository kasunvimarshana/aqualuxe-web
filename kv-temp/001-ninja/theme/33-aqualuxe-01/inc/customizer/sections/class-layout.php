<?php
/**
 * Layout Customizer Section
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

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Layout Customizer Section Class
 */
class Layout {

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
		// Add Layout section.
		$wp_customize->add_section(
			'aqualuxe_layout',
			array(
				'title'    => esc_html__( 'Layout', 'aqualuxe' ),
				'priority' => 30,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Global Layout Settings.
		$wp_customize->add_setting(
			'aqualuxe_layout_global_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_layout_global_heading',
				array(
					'label'    => esc_html__( 'Global Layout', 'aqualuxe' ),
					'section'  => 'aqualuxe_layout',
					'priority' => 10,
				)
			)
		);

		// Site Layout.
		$wp_customize->add_setting(
			'aqualuxe_site_layout',
			array(
				'default'           => 'wide',
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_site_layout',
				array(
					'label'    => esc_html__( 'Site Layout', 'aqualuxe' ),
					'section'  => 'aqualuxe_layout',
					'priority' => 20,
					'choices'  => array(
						'wide'  => array(
							'label' => esc_html__( 'Wide', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/layout-wide.svg',
						),
						'boxed' => array(
							'label' => esc_html__( 'Boxed', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/layout-boxed.svg',
						),
					),
				)
			)
		);

		// Container Width.
		$wp_customize->add_setting(
			'aqualuxe_container_width',
			array(
				'default'           => 1200,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_container_width',
				array(
					'label'       => esc_html__( 'Container Width (px)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the overall width of the site container.', 'aqualuxe' ),
					'section'     => 'aqualuxe_layout',
					'priority'    => 30,
					'input_attrs' => array(
						'min'  => 960,
						'max'  => 1920,
						'step' => 10,
					),
				)
			)
		);

		// Content Width.
		$wp_customize->add_setting(
			'aqualuxe_content_width',
			array(
				'default'           => 70,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_content_width',
				array(
					'label'       => esc_html__( 'Content Width (%)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the width of the content area when sidebar is present.', 'aqualuxe' ),
					'section'     => 'aqualuxe_layout',
					'priority'    => 40,
					'input_attrs' => array(
						'min'  => 50,
						'max'  => 85,
						'step' => 1,
					),
				)
			)
		);

		// Sidebar Width.
		$wp_customize->add_setting(
			'aqualuxe_sidebar_width',
			array(
				'default'           => 30,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_sidebar_width',
				array(
					'label'       => esc_html__( 'Sidebar Width (%)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the width of the sidebar.', 'aqualuxe' ),
					'section'     => 'aqualuxe_layout',
					'priority'    => 50,
					'input_attrs' => array(
						'min'  => 15,
						'max'  => 50,
						'step' => 1,
					),
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_layout_padding_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_layout_padding_divider',
				array(
					'section'  => 'aqualuxe_layout',
					'priority' => 60,
				)
			)
		);

		// Padding & Margins.
		$wp_customize->add_setting(
			'aqualuxe_layout_padding_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_layout_padding_heading',
				array(
					'label'    => esc_html__( 'Padding & Margins', 'aqualuxe' ),
					'section'  => 'aqualuxe_layout',
					'priority' => 70,
				)
			)
		);

		// Container Padding.
		$wp_customize->add_setting(
			'aqualuxe_container_padding',
			array(
				'default'           => array(
					'top'    => '0',
					'right'  => '15',
					'bottom' => '0',
					'left'   => '15',
				),
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $this, 'sanitize_dimensions' ),
			)
		);

		$wp_customize->add_control(
			new Dimensions_Control(
				$wp_customize,
				'aqualuxe_container_padding',
				array(
					'label'       => esc_html__( 'Container Padding (px)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the padding of the main container.', 'aqualuxe' ),
					'section'     => 'aqualuxe_layout',
					'priority'    => 80,
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'dimensions'  => array(
						'top'    => esc_html__( 'Top', 'aqualuxe' ),
						'right'  => esc_html__( 'Right', 'aqualuxe' ),
						'bottom' => esc_html__( 'Bottom', 'aqualuxe' ),
						'left'   => esc_html__( 'Left', 'aqualuxe' ),
					),
				)
			)
		);

		// Content Padding.
		$wp_customize->add_setting(
			'aqualuxe_content_padding',
			array(
				'default'           => array(
					'top'    => '40',
					'right'  => '0',
					'bottom' => '40',
					'left'   => '0',
				),
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $this, 'sanitize_dimensions' ),
			)
		);

		$wp_customize->add_control(
			new Dimensions_Control(
				$wp_customize,
				'aqualuxe_content_padding',
				array(
					'label'       => esc_html__( 'Content Padding (px)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the padding of the content area.', 'aqualuxe' ),
					'section'     => 'aqualuxe_layout',
					'priority'    => 90,
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
					'dimensions'  => array(
						'top'    => esc_html__( 'Top', 'aqualuxe' ),
						'right'  => esc_html__( 'Right', 'aqualuxe' ),
						'bottom' => esc_html__( 'Bottom', 'aqualuxe' ),
						'left'   => esc_html__( 'Left', 'aqualuxe' ),
					),
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_layout_content_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_layout_content_divider',
				array(
					'section'  => 'aqualuxe_layout',
					'priority' => 100,
				)
			)
		);

		// Content Layout Settings.
		$wp_customize->add_setting(
			'aqualuxe_layout_content_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_layout_content_heading',
				array(
					'label'    => esc_html__( 'Content Layout', 'aqualuxe' ),
					'section'  => 'aqualuxe_layout',
					'priority' => 110,
				)
			)
		);

		// Default Page Layout.
		$wp_customize->add_setting(
			'aqualuxe_page_layout',
			array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_page_layout',
				array(
					'label'    => esc_html__( 'Default Page Layout', 'aqualuxe' ),
					'section'  => 'aqualuxe_layout',
					'priority' => 120,
					'choices'  => array(
						'right-sidebar' => array(
							'label' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-right.svg',
						),
						'left-sidebar'  => array(
							'label' => esc_html__( 'Left Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-left.svg',
						),
						'no-sidebar'    => array(
							'label' => esc_html__( 'No Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-none.svg',
						),
					),
				)
			)
		);

		// Default Post Layout.
		$wp_customize->add_setting(
			'aqualuxe_post_layout',
			array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_post_layout',
				array(
					'label'    => esc_html__( 'Default Post Layout', 'aqualuxe' ),
					'section'  => 'aqualuxe_layout',
					'priority' => 130,
					'choices'  => array(
						'right-sidebar' => array(
							'label' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-right.svg',
						),
						'left-sidebar'  => array(
							'label' => esc_html__( 'Left Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-left.svg',
						),
						'no-sidebar'    => array(
							'label' => esc_html__( 'No Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-none.svg',
						),
					),
				)
			)
		);

		// Archive Layout.
		$wp_customize->add_setting(
			'aqualuxe_archive_layout',
			array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_archive_layout',
				array(
					'label'    => esc_html__( 'Archive Layout', 'aqualuxe' ),
					'section'  => 'aqualuxe_layout',
					'priority' => 140,
					'choices'  => array(
						'right-sidebar' => array(
							'label' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-right.svg',
						),
						'left-sidebar'  => array(
							'label' => esc_html__( 'Left Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-left.svg',
						),
						'no-sidebar'    => array(
							'label' => esc_html__( 'No Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-none.svg',
						),
					),
				)
			)
		);

		// Search Layout.
		$wp_customize->add_setting(
			'aqualuxe_search_layout',
			array(
				'default'           => 'right-sidebar',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_search_layout',
				array(
					'label'    => esc_html__( 'Search Results Layout', 'aqualuxe' ),
					'section'  => 'aqualuxe_layout',
					'priority' => 150,
					'choices'  => array(
						'right-sidebar' => array(
							'label' => esc_html__( 'Right Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-right.svg',
						),
						'left-sidebar'  => array(
							'label' => esc_html__( 'Left Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-left.svg',
						),
						'no-sidebar'    => array(
							'label' => esc_html__( 'No Sidebar', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/sidebar-none.svg',
						),
					),
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_layout_responsive_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_layout_responsive_divider',
				array(
					'section'  => 'aqualuxe_layout',
					'priority' => 160,
				)
			)
		);

		// Responsive Settings.
		$wp_customize->add_setting(
			'aqualuxe_layout_responsive_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_layout_responsive_heading',
				array(
					'label'    => esc_html__( 'Responsive Settings', 'aqualuxe' ),
					'section'  => 'aqualuxe_layout',
					'priority' => 170,
				)
			)
		);

		// Tablet Breakpoint.
		$wp_customize->add_setting(
			'aqualuxe_tablet_breakpoint',
			array(
				'default'           => 768,
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_tablet_breakpoint',
				array(
					'label'       => esc_html__( 'Tablet Breakpoint (px)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the breakpoint for tablet devices.', 'aqualuxe' ),
					'section'     => 'aqualuxe_layout',
					'priority'    => 180,
					'input_attrs' => array(
						'min'  => 600,
						'max'  => 1024,
						'step' => 1,
					),
				)
			)
		);

		// Mobile Breakpoint.
		$wp_customize->add_setting(
			'aqualuxe_mobile_breakpoint',
			array(
				'default'           => 480,
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new Slider_Control(
				$wp_customize,
				'aqualuxe_mobile_breakpoint',
				array(
					'label'       => esc_html__( 'Mobile Breakpoint (px)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the breakpoint for mobile devices.', 'aqualuxe' ),
					'section'     => 'aqualuxe_layout',
					'priority'    => 190,
					'input_attrs' => array(
						'min'  => 320,
						'max'  => 600,
						'step' => 1,
					),
				)
			)
		);

		// Responsive Sidebar.
		$wp_customize->add_setting(
			'aqualuxe_responsive_sidebar',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_responsive_sidebar',
				array(
					'label'       => esc_html__( 'Responsive Sidebar', 'aqualuxe' ),
					'description' => esc_html__( 'Move sidebar below content on mobile devices.', 'aqualuxe' ),
					'section'     => 'aqualuxe_layout',
					'priority'    => 200,
				)
			)
		);
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
	 * Sanitize dimensions
	 *
	 * @param array $input Value to sanitize.
	 * @return array Sanitized value.
	 */
	public function sanitize_dimensions( $input ) {
		if ( ! is_array( $input ) ) {
			return array();
		}

		$allowed_dimensions = array( 'top', 'right', 'bottom', 'left' );
		$sanitized          = array();

		foreach ( $input as $dimension => $value ) {
			if ( in_array( $dimension, $allowed_dimensions, true ) ) {
				$sanitized[ $dimension ] = absint( $value );
			}
		}

		return $sanitized;
	}

	/**
	 * Enqueue frontend styles
	 */
	public function enqueue_styles() {
		// Get layout settings.
		$site_layout      = get_theme_mod( 'aqualuxe_site_layout', 'wide' );
		$container_width  = get_theme_mod( 'aqualuxe_container_width', 1200 );
		$content_width    = get_theme_mod( 'aqualuxe_content_width', 70 );
		$sidebar_width    = get_theme_mod( 'aqualuxe_sidebar_width', 30 );
		$container_padding = get_theme_mod(
			'aqualuxe_container_padding',
			array(
				'top'    => '0',
				'right'  => '15',
				'bottom' => '0',
				'left'   => '15',
			)
		);
		$content_padding  = get_theme_mod(
			'aqualuxe_content_padding',
			array(
				'top'    => '40',
				'right'  => '0',
				'bottom' => '40',
				'left'   => '0',
			)
		);

		// Generate inline styles.
		$css = '';

		// Container width.
		$css .= '.container {';
		$css .= 'max-width: ' . absint( $container_width ) . 'px;';
		$css .= '}';

		// Container padding.
		if ( ! empty( $container_padding ) ) {
			$css .= '.container {';
			$css .= 'padding-top: ' . absint( $container_padding['top'] ) . 'px;';
			$css .= 'padding-right: ' . absint( $container_padding['right'] ) . 'px;';
			$css .= 'padding-bottom: ' . absint( $container_padding['bottom'] ) . 'px;';
			$css .= 'padding-left: ' . absint( $container_padding['left'] ) . 'px;';
			$css .= '}';
		}

		// Content padding.
		if ( ! empty( $content_padding ) ) {
			$css .= '#primary {';
			$css .= 'padding-top: ' . absint( $content_padding['top'] ) . 'px;';
			$css .= 'padding-right: ' . absint( $content_padding['right'] ) . 'px;';
			$css .= 'padding-bottom: ' . absint( $content_padding['bottom'] ) . 'px;';
			$css .= 'padding-left: ' . absint( $content_padding['left'] ) . 'px;';
			$css .= '}';
		}

		// Content and sidebar widths.
		$css .= '.content-area {';
		$css .= 'width: ' . absint( $content_width ) . '%;';
		$css .= '}';

		$css .= '.widget-area {';
		$css .= 'width: ' . absint( $sidebar_width ) . '%;';
		$css .= '}';

		// Boxed layout.
		if ( 'boxed' === $site_layout ) {
			$css .= 'body.boxed-layout {';
			$css .= 'background-color: #f5f5f5;';
			$css .= '}';

			$css .= 'body.boxed-layout #page {';
			$css .= 'max-width: ' . absint( $container_width ) . 'px;';
			$css .= 'margin: 0 auto;';
			$css .= 'background-color: #ffffff;';
			$css .= 'box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);';
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
			'aqualuxe-layout-preview',
			get_template_directory_uri() . '/assets/js/admin/customizer-layout-preview.js',
			array( 'customize-preview', 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
	}
}

// Initialize the class.
new Layout();