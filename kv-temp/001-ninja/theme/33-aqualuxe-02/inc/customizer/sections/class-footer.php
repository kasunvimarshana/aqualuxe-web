<?php
/**
 * Footer Customizer Section
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
 * Footer Customizer Section Class
 */
class Footer {

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
		// Add Footer section.
		$wp_customize->add_section(
			'aqualuxe_footer',
			array(
				'title'    => esc_html__( 'Footer', 'aqualuxe' ),
				'priority' => 60,
				'panel'    => 'aqualuxe_theme_options',
			)
		);

		// Footer Layout.
		$wp_customize->add_setting(
			'aqualuxe_footer_layout_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_footer_layout_heading',
				array(
					'label'    => esc_html__( 'Footer Layout', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 10,
				)
			)
		);

		// Footer Widget Layout.
		$wp_customize->add_setting(
			'aqualuxe_footer_widget_layout',
			array(
				'default'           => '4',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_footer_widget_layout',
				array(
					'label'    => esc_html__( 'Footer Widget Layout', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 20,
					'choices'  => array(
						'0' => array(
							'label' => esc_html__( 'No Widgets', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/footer-none.svg',
						),
						'1' => array(
							'label' => esc_html__( '1 Column', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/footer-1.svg',
						),
						'2' => array(
							'label' => esc_html__( '2 Columns', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/footer-2.svg',
						),
						'3' => array(
							'label' => esc_html__( '3 Columns', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/footer-3.svg',
						),
						'4' => array(
							'label' => esc_html__( '4 Columns', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/footer-4.svg',
						),
						'5' => array(
							'label' => esc_html__( '5 Columns', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/footer-5.svg',
						),
						'6' => array(
							'label' => esc_html__( '6 Columns', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/footer-6.svg',
						),
						'custom' => array(
							'label' => esc_html__( 'Custom Layout', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/footer-custom.svg',
						),
					),
				)
			)
		);

		// Custom Footer Layout.
		$wp_customize->add_setting(
			'aqualuxe_footer_custom_layout',
			array(
				'default'           => '3+3+3+3',
				'sanitize_callback' => 'sanitize_text_field',
				'active_callback'   => array( $this, 'is_custom_footer_layout' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_footer_custom_layout',
			array(
				'label'       => esc_html__( 'Custom Footer Layout', 'aqualuxe' ),
				'description' => esc_html__( 'Enter column widths separated by + sign (e.g., 3+3+3+3 or 6+6 or 4+4+4). Total should be 12.', 'aqualuxe' ),
				'section'     => 'aqualuxe_footer',
				'type'        => 'text',
				'priority'    => 30,
				'active_callback' => array( $this, 'is_custom_footer_layout' ),
			)
		);

		// Footer Width.
		$wp_customize->add_setting(
			'aqualuxe_footer_width',
			array(
				'default'           => 'contained',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_footer_width',
				array(
					'label'    => esc_html__( 'Footer Width', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 40,
					'choices'  => array(
						'contained' => array(
							'label' => esc_html__( 'Contained', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/footer-contained.svg',
						),
						'full'      => array(
							'label' => esc_html__( 'Full Width', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/footer-full.svg',
						),
					),
				)
			)
		);

		// Footer Padding.
		$wp_customize->add_setting(
			'aqualuxe_footer_padding',
			array(
				'default'           => array(
					'top'    => '60',
					'right'  => '0',
					'bottom' => '60',
					'left'   => '0',
				),
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $this, 'sanitize_dimensions' ),
			)
		);

		$wp_customize->add_control(
			new Dimensions_Control(
				$wp_customize,
				'aqualuxe_footer_padding',
				array(
					'label'       => esc_html__( 'Footer Padding (px)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the padding of the footer widget area.', 'aqualuxe' ),
					'section'     => 'aqualuxe_footer',
					'priority'    => 50,
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 200,
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
			'aqualuxe_footer_bottom_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_footer_bottom_divider',
				array(
					'section'  => 'aqualuxe_footer',
					'priority' => 60,
				)
			)
		);

		// Bottom Bar Settings.
		$wp_customize->add_setting(
			'aqualuxe_footer_bottom_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_footer_bottom_heading',
				array(
					'label'    => esc_html__( 'Bottom Bar', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 70,
				)
			)
		);

		// Show Bottom Bar.
		$wp_customize->add_setting(
			'aqualuxe_footer_bottom_bar',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_footer_bottom_bar',
				array(
					'label'       => esc_html__( 'Show Bottom Bar', 'aqualuxe' ),
					'description' => esc_html__( 'Display the footer bottom bar with copyright text.', 'aqualuxe' ),
					'section'     => 'aqualuxe_footer',
					'priority'    => 80,
				)
			)
		);

		// Bottom Bar Layout.
		$wp_customize->add_setting(
			'aqualuxe_footer_bottom_layout',
			array(
				'default'           => 'centered',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
				'active_callback'   => array( $this, 'is_bottom_bar_enabled' ),
			)
		);

		$wp_customize->add_control(
			new Radio_Image_Control(
				$wp_customize,
				'aqualuxe_footer_bottom_layout',
				array(
					'label'    => esc_html__( 'Bottom Bar Layout', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 90,
					'choices'  => array(
						'centered' => array(
							'label' => esc_html__( 'Centered', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/footer-bottom-centered.svg',
						),
						'two-cols' => array(
							'label' => esc_html__( 'Two Columns', 'aqualuxe' ),
							'image' => get_template_directory_uri() . '/assets/images/admin/footer-bottom-two-cols.svg',
						),
					),
					'active_callback' => array( $this, 'is_bottom_bar_enabled' ),
				)
			)
		);

		// Copyright Text.
		$wp_customize->add_setting(
			'aqualuxe_footer_copyright',
			array(
				'default'           => sprintf(
					/* translators: %1$s: Current year, %2$s: Site name */
					esc_html__( '&copy; %1$s %2$s. All Rights Reserved.', 'aqualuxe' ),
					date_i18n( 'Y' ),
					get_bloginfo( 'name' )
				),
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
				'active_callback'   => array( $this, 'is_bottom_bar_enabled' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_footer_copyright',
			array(
				'label'       => esc_html__( 'Copyright Text', 'aqualuxe' ),
				'description' => esc_html__( 'HTML is allowed. {year} will be replaced with the current year.', 'aqualuxe' ),
				'section'     => 'aqualuxe_footer',
				'type'        => 'textarea',
				'priority'    => 100,
				'active_callback' => array( $this, 'is_bottom_bar_enabled' ),
			)
		);

		// Bottom Bar Padding.
		$wp_customize->add_setting(
			'aqualuxe_footer_bottom_padding',
			array(
				'default'           => array(
					'top'    => '20',
					'right'  => '0',
					'bottom' => '20',
					'left'   => '0',
				),
				'transport'         => 'postMessage',
				'sanitize_callback' => array( $this, 'sanitize_dimensions' ),
				'active_callback'   => array( $this, 'is_bottom_bar_enabled' ),
			)
		);

		$wp_customize->add_control(
			new Dimensions_Control(
				$wp_customize,
				'aqualuxe_footer_bottom_padding',
				array(
					'label'       => esc_html__( 'Bottom Bar Padding (px)', 'aqualuxe' ),
					'description' => esc_html__( 'Controls the padding of the footer bottom bar.', 'aqualuxe' ),
					'section'     => 'aqualuxe_footer',
					'priority'    => 110,
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
					'active_callback' => array( $this, 'is_bottom_bar_enabled' ),
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_footer_colors_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_footer_colors_divider',
				array(
					'section'  => 'aqualuxe_footer',
					'priority' => 120,
				)
			)
		);

		// Footer Colors.
		$wp_customize->add_setting(
			'aqualuxe_footer_colors_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_footer_colors_heading',
				array(
					'label'    => esc_html__( 'Footer Colors', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 130,
				)
			)
		);

		// Footer Background.
		$wp_customize->add_setting(
			'aqualuxe_footer_background',
			array(
				'default'           => '#222222',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new Color_Alpha_Control(
				$wp_customize,
				'aqualuxe_footer_background',
				array(
					'label'    => esc_html__( 'Footer Background', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 140,
				)
			)
		);

		// Footer Text Color.
		$wp_customize->add_setting(
			'aqualuxe_footer_text_color',
			array(
				'default'           => '#ffffff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_footer_text_color',
				array(
					'label'    => esc_html__( 'Footer Text Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 150,
				)
			)
		);

		// Footer Link Color.
		$wp_customize->add_setting(
			'aqualuxe_footer_link_color',
			array(
				'default'           => '#ffffff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_footer_link_color',
				array(
					'label'    => esc_html__( 'Footer Link Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 160,
				)
			)
		);

		// Footer Link Hover Color.
		$wp_customize->add_setting(
			'aqualuxe_footer_link_hover_color',
			array(
				'default'           => '#0073aa',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_footer_link_hover_color',
				array(
					'label'    => esc_html__( 'Footer Link Hover Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 170,
				)
			)
		);

		// Widget Title Color.
		$wp_customize->add_setting(
			'aqualuxe_footer_widget_title_color',
			array(
				'default'           => '#ffffff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_footer_widget_title_color',
				array(
					'label'    => esc_html__( 'Widget Title Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 180,
				)
			)
		);

		// Bottom Bar Background.
		$wp_customize->add_setting(
			'aqualuxe_footer_bottom_background',
			array(
				'default'           => '#111111',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'active_callback'   => array( $this, 'is_bottom_bar_enabled' ),
			)
		);

		$wp_customize->add_control(
			new Color_Alpha_Control(
				$wp_customize,
				'aqualuxe_footer_bottom_background',
				array(
					'label'    => esc_html__( 'Bottom Bar Background', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 190,
					'active_callback' => array( $this, 'is_bottom_bar_enabled' ),
				)
			)
		);

		// Bottom Bar Text Color.
		$wp_customize->add_setting(
			'aqualuxe_footer_bottom_text_color',
			array(
				'default'           => '#ffffff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'active_callback'   => array( $this, 'is_bottom_bar_enabled' ),
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_footer_bottom_text_color',
				array(
					'label'    => esc_html__( 'Bottom Bar Text Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 200,
					'active_callback' => array( $this, 'is_bottom_bar_enabled' ),
				)
			)
		);

		// Bottom Bar Link Color.
		$wp_customize->add_setting(
			'aqualuxe_footer_bottom_link_color',
			array(
				'default'           => '#ffffff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'active_callback'   => array( $this, 'is_bottom_bar_enabled' ),
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_footer_bottom_link_color',
				array(
					'label'    => esc_html__( 'Bottom Bar Link Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 210,
					'active_callback' => array( $this, 'is_bottom_bar_enabled' ),
				)
			)
		);

		// Bottom Bar Link Hover Color.
		$wp_customize->add_setting(
			'aqualuxe_footer_bottom_link_hover_color',
			array(
				'default'           => '#0073aa',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'active_callback'   => array( $this, 'is_bottom_bar_enabled' ),
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_footer_bottom_link_hover_color',
				array(
					'label'    => esc_html__( 'Bottom Bar Link Hover Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 220,
					'active_callback' => array( $this, 'is_bottom_bar_enabled' ),
				)
			)
		);

		// Divider.
		$wp_customize->add_setting(
			'aqualuxe_footer_scroll_divider',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Divider_Control(
				$wp_customize,
				'aqualuxe_footer_scroll_divider',
				array(
					'section'  => 'aqualuxe_footer',
					'priority' => 230,
				)
			)
		);

		// Scroll to Top.
		$wp_customize->add_setting(
			'aqualuxe_footer_scroll_heading',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			new Heading_Control(
				$wp_customize,
				'aqualuxe_footer_scroll_heading',
				array(
					'label'    => esc_html__( 'Scroll to Top', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 240,
				)
			)
		);

		// Show Scroll to Top.
		$wp_customize->add_setting(
			'aqualuxe_scroll_to_top',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			new Toggle_Control(
				$wp_customize,
				'aqualuxe_scroll_to_top',
				array(
					'label'       => esc_html__( 'Show Scroll to Top', 'aqualuxe' ),
					'description' => esc_html__( 'Display a button to scroll back to the top of the page.', 'aqualuxe' ),
					'section'     => 'aqualuxe_footer',
					'priority'    => 250,
				)
			)
		);

		// Scroll to Top Position.
		$wp_customize->add_setting(
			'aqualuxe_scroll_to_top_position',
			array(
				'default'           => 'right',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
				'active_callback'   => array( $this, 'is_scroll_to_top_enabled' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_scroll_to_top_position',
			array(
				'label'    => esc_html__( 'Button Position', 'aqualuxe' ),
				'section'  => 'aqualuxe_footer',
				'type'     => 'select',
				'priority' => 260,
				'choices'  => array(
					'right' => esc_html__( 'Right', 'aqualuxe' ),
					'left'  => esc_html__( 'Left', 'aqualuxe' ),
				),
				'active_callback' => array( $this, 'is_scroll_to_top_enabled' ),
			)
		);

		// Scroll to Top Style.
		$wp_customize->add_setting(
			'aqualuxe_scroll_to_top_style',
			array(
				'default'           => 'circle',
				'sanitize_callback' => array( $this, 'sanitize_choices' ),
				'active_callback'   => array( $this, 'is_scroll_to_top_enabled' ),
			)
		);

		$wp_customize->add_control(
			'aqualuxe_scroll_to_top_style',
			array(
				'label'    => esc_html__( 'Button Style', 'aqualuxe' ),
				'section'  => 'aqualuxe_footer',
				'type'     => 'select',
				'priority' => 270,
				'choices'  => array(
					'circle'    => esc_html__( 'Circle', 'aqualuxe' ),
					'rounded'   => esc_html__( 'Rounded', 'aqualuxe' ),
					'square'    => esc_html__( 'Square', 'aqualuxe' ),
				),
				'active_callback' => array( $this, 'is_scroll_to_top_enabled' ),
			)
		);

		// Scroll to Top Background.
		$wp_customize->add_setting(
			'aqualuxe_scroll_to_top_background',
			array(
				'default'           => '#0073aa',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'active_callback'   => array( $this, 'is_scroll_to_top_enabled' ),
			)
		);

		$wp_customize->add_control(
			new Color_Alpha_Control(
				$wp_customize,
				'aqualuxe_scroll_to_top_background',
				array(
					'label'    => esc_html__( 'Button Background', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 280,
					'active_callback' => array( $this, 'is_scroll_to_top_enabled' ),
				)
			)
		);

		// Scroll to Top Icon Color.
		$wp_customize->add_setting(
			'aqualuxe_scroll_to_top_color',
			array(
				'default'           => '#ffffff',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_hex_color',
				'active_callback'   => array( $this, 'is_scroll_to_top_enabled' ),
			)
		);

		$wp_customize->add_control(
			new \WP_Customize_Color_Control(
				$wp_customize,
				'aqualuxe_scroll_to_top_color',
				array(
					'label'    => esc_html__( 'Button Icon Color', 'aqualuxe' ),
					'section'  => 'aqualuxe_footer',
					'priority' => 290,
					'active_callback' => array( $this, 'is_scroll_to_top_enabled' ),
				)
			)
		);
	}

	/**
	 * Check if custom footer layout is selected
	 *
	 * @return bool
	 */
	public function is_custom_footer_layout() {
		return 'custom' === get_theme_mod( 'aqualuxe_footer_widget_layout', '4' );
	}

	/**
	 * Check if bottom bar is enabled
	 *
	 * @return bool
	 */
	public function is_bottom_bar_enabled() {
		return get_theme_mod( 'aqualuxe_footer_bottom_bar', true );
	}

	/**
	 * Check if scroll to top is enabled
	 *
	 * @return bool
	 */
	public function is_scroll_to_top_enabled() {
		return get_theme_mod( 'aqualuxe_scroll_to_top', true );
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
		// Get footer settings.
		$footer_bg          = get_theme_mod( 'aqualuxe_footer_background', '#222222' );
		$footer_text        = get_theme_mod( 'aqualuxe_footer_text_color', '#ffffff' );
		$footer_link        = get_theme_mod( 'aqualuxe_footer_link_color', '#ffffff' );
		$footer_link_hover  = get_theme_mod( 'aqualuxe_footer_link_hover_color', '#0073aa' );
		$widget_title       = get_theme_mod( 'aqualuxe_footer_widget_title_color', '#ffffff' );
		$footer_width       = get_theme_mod( 'aqualuxe_footer_width', 'contained' );
		$footer_padding     = get_theme_mod(
			'aqualuxe_footer_padding',
			array(
				'top'    => '60',
				'right'  => '0',
				'bottom' => '60',
				'left'   => '0',
			)
		);
		$bottom_bar         = get_theme_mod( 'aqualuxe_footer_bottom_bar', true );
		$bottom_bg          = get_theme_mod( 'aqualuxe_footer_bottom_background', '#111111' );
		$bottom_text        = get_theme_mod( 'aqualuxe_footer_bottom_text_color', '#ffffff' );
		$bottom_link        = get_theme_mod( 'aqualuxe_footer_bottom_link_color', '#ffffff' );
		$bottom_link_hover  = get_theme_mod( 'aqualuxe_footer_bottom_link_hover_color', '#0073aa' );
		$bottom_padding     = get_theme_mod(
			'aqualuxe_footer_bottom_padding',
			array(
				'top'    => '20',
				'right'  => '0',
				'bottom' => '20',
				'left'   => '0',
			)
		);
		$scroll_to_top      = get_theme_mod( 'aqualuxe_scroll_to_top', true );
		$scroll_position    = get_theme_mod( 'aqualuxe_scroll_to_top_position', 'right' );
		$scroll_style       = get_theme_mod( 'aqualuxe_scroll_to_top_style', 'circle' );
		$scroll_bg          = get_theme_mod( 'aqualuxe_scroll_to_top_background', '#0073aa' );
		$scroll_color       = get_theme_mod( 'aqualuxe_scroll_to_top_color', '#ffffff' );

		// Generate inline styles.
		$css = '';

		// Footer background and colors.
		$css .= '.site-footer {';
		$css .= 'background-color: ' . esc_attr( $footer_bg ) . ';';
		$css .= 'color: ' . esc_attr( $footer_text ) . ';';
		$css .= '}';

		// Footer padding.
		if ( ! empty( $footer_padding ) ) {
			$css .= '.footer-widgets {';
			$css .= 'padding-top: ' . absint( $footer_padding['top'] ) . 'px;';
			$css .= 'padding-right: ' . absint( $footer_padding['right'] ) . 'px;';
			$css .= 'padding-bottom: ' . absint( $footer_padding['bottom'] ) . 'px;';
			$css .= 'padding-left: ' . absint( $footer_padding['left'] ) . 'px;';
			$css .= '}';
		}

		// Footer width.
		if ( 'full' === $footer_width ) {
			$css .= '.footer-widgets .footer-inner {';
			$css .= 'max-width: 100%;';
			$css .= 'padding-left: 30px;';
			$css .= 'padding-right: 30px;';
			$css .= '}';
		}

		// Footer links.
		$css .= '.site-footer a {';
		$css .= 'color: ' . esc_attr( $footer_link ) . ';';
		$css .= '}';

		$css .= '.site-footer a:hover {';
		$css .= 'color: ' . esc_attr( $footer_link_hover ) . ';';
		$css .= '}';

		// Widget titles.
		$css .= '.footer-widgets .widget-title {';
		$css .= 'color: ' . esc_attr( $widget_title ) . ';';
		$css .= '}';

		// Bottom bar.
		if ( $bottom_bar ) {
			$css .= '.site-info {';
			$css .= 'background-color: ' . esc_attr( $bottom_bg ) . ';';
			$css .= 'color: ' . esc_attr( $bottom_text ) . ';';
			$css .= '}';

			// Bottom bar padding.
			if ( ! empty( $bottom_padding ) ) {
				$css .= '.site-info {';
				$css .= 'padding-top: ' . absint( $bottom_padding['top'] ) . 'px;';
				$css .= 'padding-right: ' . absint( $bottom_padding['right'] ) . 'px;';
				$css .= 'padding-bottom: ' . absint( $bottom_padding['bottom'] ) . 'px;';
				$css .= 'padding-left: ' . absint( $bottom_padding['left'] ) . 'px;';
				$css .= '}';
			}

			// Bottom bar links.
			$css .= '.site-info a {';
			$css .= 'color: ' . esc_attr( $bottom_link ) . ';';
			$css .= '}';

			$css .= '.site-info a:hover {';
			$css .= 'color: ' . esc_attr( $bottom_link_hover ) . ';';
			$css .= '}';
		}

		// Scroll to top.
		if ( $scroll_to_top ) {
			$css .= '.scroll-to-top {';
			$css .= 'background-color: ' . esc_attr( $scroll_bg ) . ';';
			$css .= 'color: ' . esc_attr( $scroll_color ) . ';';
			$css .= $scroll_position . ': 20px;';
			
			// Button style.
			if ( 'circle' === $scroll_style ) {
				$css .= 'border-radius: 50%;';
			} elseif ( 'rounded' === $scroll_style ) {
				$css .= 'border-radius: 5px;';
			} else {
				$css .= 'border-radius: 0;';
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
			'aqualuxe-footer-preview',
			get_template_directory_uri() . '/assets/js/admin/customizer-footer-preview.js',
			array( 'customize-preview', 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
	}
}

// Initialize the class.
new Footer();