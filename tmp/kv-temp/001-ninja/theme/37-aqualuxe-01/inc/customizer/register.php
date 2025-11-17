<?php
/**
 * Customizer settings for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe Customizer Class
 */
class AquaLuxe_Customizer {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Register customizer settings.
		add_action( 'customize_register', array( $this, 'register_customizer_settings' ) );
		
		// Add customizer preview script.
		add_action( 'customize_preview_init', array( $this, 'customize_preview_js' ) );
		
		// Add customizer controls script.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_controls_js' ) );
		
		// Output customizer CSS.
		add_action( 'wp_head', array( $this, 'output_customizer_css' ) );
	}

	/**
	 * Register customizer settings
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	public function register_customizer_settings( $wp_customize ) {
		// Add theme options panel.
		$wp_customize->add_panel(
			'aqualuxe_theme_options',
			array(
				'title'       => esc_html__( 'Theme Options', 'aqualuxe' ),
				'description' => esc_html__( 'Customize the appearance and behavior of your theme.', 'aqualuxe' ),
				'priority'    => 130,
			)
		);

		// Add colors section.
		$wp_customize->add_section(
			'aqualuxe_colors',
			array(
				'title'       => esc_html__( 'Colors', 'aqualuxe' ),
				'description' => esc_html__( 'Customize the colors of your theme.', 'aqualuxe' ),
				'panel'       => 'aqualuxe_theme_options',
				'priority'    => 10,
			)
		);

		// Primary color.
		$wp_customize->add_setting(
			'primary_color',
			array(
				'default'           => '#00afcc',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'primary_color',
				array(
					'label'       => esc_html__( 'Primary Color', 'aqualuxe' ),
					'description' => esc_html__( 'Used for buttons, links, and primary accents.', 'aqualuxe' ),
					'section'     => 'aqualuxe_colors',
					'settings'    => 'primary_color',
				)
			)
		);

		// Secondary color.
		$wp_customize->add_setting(
			'secondary_color',
			array(
				'default'           => '#7f7faf',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'secondary_color',
				array(
					'label'       => esc_html__( 'Secondary Color', 'aqualuxe' ),
					'description' => esc_html__( 'Used for secondary elements and backgrounds.', 'aqualuxe' ),
					'section'     => 'aqualuxe_colors',
					'settings'    => 'secondary_color',
				)
			)
		);

		// Accent color.
		$wp_customize->add_setting(
			'accent_color',
			array(
				'default'           => '#eb7d23',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'accent_color',
				array(
					'label'       => esc_html__( 'Accent Color', 'aqualuxe' ),
					'description' => esc_html__( 'Used for call-to-action elements and highlights.', 'aqualuxe' ),
					'section'     => 'aqualuxe_colors',
					'settings'    => 'accent_color',
				)
			)
		);

		// Text color.
		$wp_customize->add_setting(
			'text_color',
			array(
				'default'           => '#333333',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'text_color',
				array(
					'label'       => esc_html__( 'Text Color', 'aqualuxe' ),
					'description' => esc_html__( 'Used for body text.', 'aqualuxe' ),
					'section'     => 'aqualuxe_colors',
					'settings'    => 'text_color',
				)
			)
		);

		// Background color.
		$wp_customize->add_setting(
			'background_color',
			array(
				'default'           => '#ffffff',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'background_color',
				array(
					'label'       => esc_html__( 'Background Color', 'aqualuxe' ),
					'description' => esc_html__( 'Used for the main background.', 'aqualuxe' ),
					'section'     => 'aqualuxe_colors',
					'settings'    => 'background_color',
				)
			)
		);

		// Add typography section.
		$wp_customize->add_section(
			'aqualuxe_typography',
			array(
				'title'       => esc_html__( 'Typography', 'aqualuxe' ),
				'description' => esc_html__( 'Customize the typography of your theme.', 'aqualuxe' ),
				'panel'       => 'aqualuxe_theme_options',
				'priority'    => 20,
			)
		);

		// Heading font.
		$wp_customize->add_setting(
			'heading_font',
			array(
				'default'           => 'Playfair Display',
				'sanitize_callback' => array( $this, 'sanitize_font' ),
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'heading_font',
			array(
				'label'       => esc_html__( 'Heading Font', 'aqualuxe' ),
				'description' => esc_html__( 'Select the font for headings.', 'aqualuxe' ),
				'section'     => 'aqualuxe_typography',
				'type'        => 'select',
				'choices'     => $this->get_font_choices(),
			)
		);

		// Body font.
		$wp_customize->add_setting(
			'body_font',
			array(
				'default'           => 'Montserrat',
				'sanitize_callback' => array( $this, 'sanitize_font' ),
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'body_font',
			array(
				'label'       => esc_html__( 'Body Font', 'aqualuxe' ),
				'description' => esc_html__( 'Select the font for body text.', 'aqualuxe' ),
				'section'     => 'aqualuxe_typography',
				'type'        => 'select',
				'choices'     => $this->get_font_choices(),
			)
		);

		// Font size base.
		$wp_customize->add_setting(
			'font_size_base',
			array(
				'default'           => '16',
				'sanitize_callback' => 'absint',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'font_size_base',
			array(
				'label'       => esc_html__( 'Base Font Size (px)', 'aqualuxe' ),
				'description' => esc_html__( 'Base font size for body text.', 'aqualuxe' ),
				'section'     => 'aqualuxe_typography',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 12,
					'max'  => 24,
					'step' => 1,
				),
			)
		);

		// Add layout section.
		$wp_customize->add_section(
			'aqualuxe_layout',
			array(
				'title'       => esc_html__( 'Layout', 'aqualuxe' ),
				'description' => esc_html__( 'Customize the layout of your theme.', 'aqualuxe' ),
				'panel'       => 'aqualuxe_theme_options',
				'priority'    => 30,
			)
		);

		// Container width.
		$wp_customize->add_setting(
			'container_width',
			array(
				'default'           => '1200',
				'sanitize_callback' => 'absint',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'container_width',
			array(
				'label'       => esc_html__( 'Container Width (px)', 'aqualuxe' ),
				'description' => esc_html__( 'Width of the main container.', 'aqualuxe' ),
				'section'     => 'aqualuxe_layout',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 960,
					'max'  => 1600,
					'step' => 10,
				),
			)
		);

		// Sidebar position.
		$wp_customize->add_setting(
			'sidebar_position',
			array(
				'default'           => 'right',
				'sanitize_callback' => array( $this, 'sanitize_sidebar_position' ),
			)
		);

		$wp_customize->add_control(
			'sidebar_position',
			array(
				'label'       => esc_html__( 'Sidebar Position', 'aqualuxe' ),
				'description' => esc_html__( 'Choose the position of the sidebar.', 'aqualuxe' ),
				'section'     => 'aqualuxe_layout',
				'type'        => 'select',
				'choices'     => array(
					'right' => esc_html__( 'Right', 'aqualuxe' ),
					'left'  => esc_html__( 'Left', 'aqualuxe' ),
					'none'  => esc_html__( 'None', 'aqualuxe' ),
				),
			)
		);

		// Add header section.
		$wp_customize->add_section(
			'aqualuxe_header',
			array(
				'title'       => esc_html__( 'Header', 'aqualuxe' ),
				'description' => esc_html__( 'Customize the header of your theme.', 'aqualuxe' ),
				'panel'       => 'aqualuxe_theme_options',
				'priority'    => 40,
			)
		);

		// Header layout.
		$wp_customize->add_setting(
			'header_layout',
			array(
				'default'           => 'default',
				'sanitize_callback' => array( $this, 'sanitize_header_layout' ),
			)
		);

		$wp_customize->add_control(
			'header_layout',
			array(
				'label'       => esc_html__( 'Header Layout', 'aqualuxe' ),
				'description' => esc_html__( 'Choose the layout of the header.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header',
				'type'        => 'select',
				'choices'     => array(
					'default'    => esc_html__( 'Default', 'aqualuxe' ),
					'centered'   => esc_html__( 'Centered', 'aqualuxe' ),
					'split'      => esc_html__( 'Split', 'aqualuxe' ),
					'transparent' => esc_html__( 'Transparent', 'aqualuxe' ),
				),
			)
		);

		// Sticky header.
		$wp_customize->add_setting(
			'sticky_header',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'sticky_header',
			array(
				'label'       => esc_html__( 'Sticky Header', 'aqualuxe' ),
				'description' => esc_html__( 'Enable sticky header on scroll.', 'aqualuxe' ),
				'section'     => 'aqualuxe_header',
				'type'        => 'checkbox',
			)
		);

		// Add footer section.
		$wp_customize->add_section(
			'aqualuxe_footer',
			array(
				'title'       => esc_html__( 'Footer', 'aqualuxe' ),
				'description' => esc_html__( 'Customize the footer of your theme.', 'aqualuxe' ),
				'panel'       => 'aqualuxe_theme_options',
				'priority'    => 50,
			)
		);

		// Footer columns.
		$wp_customize->add_setting(
			'footer_columns',
			array(
				'default'           => 4,
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			'footer_columns',
			array(
				'label'       => esc_html__( 'Footer Columns', 'aqualuxe' ),
				'description' => esc_html__( 'Number of widget columns in the footer.', 'aqualuxe' ),
				'section'     => 'aqualuxe_footer',
				'type'        => 'number',
				'input_attrs' => array(
					'min'  => 1,
					'max'  => 4,
					'step' => 1,
				),
			)
		);

		// Footer text.
		$wp_customize->add_setting(
			'footer_text',
			array(
				'default'           => sprintf( esc_html__( 'Copyright &copy; %s AquaLuxe. All rights reserved.', 'aqualuxe' ), date( 'Y' ) ),
				'sanitize_callback' => 'wp_kses_post',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			'footer_text',
			array(
				'label'       => esc_html__( 'Footer Text', 'aqualuxe' ),
				'description' => esc_html__( 'Text to display in the footer. Use {year} for dynamic year.', 'aqualuxe' ),
				'section'     => 'aqualuxe_footer',
				'type'        => 'textarea',
			)
		);

		// Add social media section.
		$wp_customize->add_section(
			'aqualuxe_social',
			array(
				'title'       => esc_html__( 'Social Media', 'aqualuxe' ),
				'description' => esc_html__( 'Add your social media profiles.', 'aqualuxe' ),
				'panel'       => 'aqualuxe_theme_options',
				'priority'    => 60,
			)
		);

		// Social media profiles.
		$social_platforms = array(
			'facebook'  => esc_html__( 'Facebook URL', 'aqualuxe' ),
			'twitter'   => esc_html__( 'Twitter URL', 'aqualuxe' ),
			'instagram' => esc_html__( 'Instagram URL', 'aqualuxe' ),
			'linkedin'  => esc_html__( 'LinkedIn URL', 'aqualuxe' ),
			'youtube'   => esc_html__( 'YouTube URL', 'aqualuxe' ),
			'pinterest' => esc_html__( 'Pinterest URL', 'aqualuxe' ),
		);

		foreach ( $social_platforms as $platform => $label ) {
			$wp_customize->add_setting(
				'social_' . $platform,
				array(
					'default'           => '',
					'sanitize_callback' => 'esc_url_raw',
				)
			);

			$wp_customize->add_control(
				'social_' . $platform,
				array(
					'label'       => $label,
					'section'     => 'aqualuxe_social',
					'type'        => 'url',
					'input_attrs' => array(
						'placeholder' => 'https://',
					),
				)
			);
		}

		// Twitter username (without @).
		$wp_customize->add_setting(
			'social_twitter_username',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'social_twitter_username',
			array(
				'label'       => esc_html__( 'Twitter Username', 'aqualuxe' ),
				'description' => esc_html__( 'Enter your Twitter username without the @ symbol.', 'aqualuxe' ),
				'section'     => 'aqualuxe_social',
				'type'        => 'text',
			)
		);

		// Add contact information section.
		$wp_customize->add_section(
			'aqualuxe_contact',
			array(
				'title'       => esc_html__( 'Contact Information', 'aqualuxe' ),
				'description' => esc_html__( 'Add your contact information.', 'aqualuxe' ),
				'panel'       => 'aqualuxe_theme_options',
				'priority'    => 70,
			)
		);

		// Contact phone.
		$wp_customize->add_setting(
			'contact_phone',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'contact_phone',
			array(
				'label'       => esc_html__( 'Phone Number', 'aqualuxe' ),
				'section'     => 'aqualuxe_contact',
				'type'        => 'text',
			)
		);

		// Contact email.
		$wp_customize->add_setting(
			'contact_email',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_email',
			)
		);

		$wp_customize->add_control(
			'contact_email',
			array(
				'label'       => esc_html__( 'Email Address', 'aqualuxe' ),
				'section'     => 'aqualuxe_contact',
				'type'        => 'email',
			)
		);

		// Contact address.
		$wp_customize->add_setting(
			'contact_address',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'contact_address',
			array(
				'label'       => esc_html__( 'Street Address', 'aqualuxe' ),
				'section'     => 'aqualuxe_contact',
				'type'        => 'text',
			)
		);

		// Contact city.
		$wp_customize->add_setting(
			'contact_city',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'contact_city',
			array(
				'label'       => esc_html__( 'City', 'aqualuxe' ),
				'section'     => 'aqualuxe_contact',
				'type'        => 'text',
			)
		);

		// Contact state.
		$wp_customize->add_setting(
			'contact_state',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'contact_state',
			array(
				'label'       => esc_html__( 'State/Province', 'aqualuxe' ),
				'section'     => 'aqualuxe_contact',
				'type'        => 'text',
			)
		);

		// Contact zip.
		$wp_customize->add_setting(
			'contact_zip',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'contact_zip',
			array(
				'label'       => esc_html__( 'ZIP/Postal Code', 'aqualuxe' ),
				'section'     => 'aqualuxe_contact',
				'type'        => 'text',
			)
		);

		// Contact country.
		$wp_customize->add_setting(
			'contact_country',
			array(
				'default'           => '',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_control(
			'contact_country',
			array(
				'label'       => esc_html__( 'Country', 'aqualuxe' ),
				'section'     => 'aqualuxe_contact',
				'type'        => 'text',
			)
		);

		// Add SEO section.
		$wp_customize->add_section(
			'aqualuxe_seo',
			array(
				'title'       => esc_html__( 'SEO', 'aqualuxe' ),
				'description' => esc_html__( 'Search engine optimization settings.', 'aqualuxe' ),
				'panel'       => 'aqualuxe_theme_options',
				'priority'    => 80,
			)
		);

		// Default OG image.
		$wp_customize->add_setting(
			'og_default_image',
			array(
				'default'           => '',
				'sanitize_callback' => 'absint',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				'og_default_image',
				array(
					'label'       => esc_html__( 'Default Social Image', 'aqualuxe' ),
					'description' => esc_html__( 'Image used for social sharing when no featured image is available.', 'aqualuxe' ),
					'section'     => 'aqualuxe_seo',
					'mime_type'   => 'image',
				)
			)
		);

		// Add WooCommerce section if WooCommerce is active.
		if ( class_exists( 'WooCommerce' ) ) {
			$wp_customize->add_section(
				'aqualuxe_woocommerce',
				array(
					'title'       => esc_html__( 'WooCommerce', 'aqualuxe' ),
					'description' => esc_html__( 'WooCommerce specific settings.', 'aqualuxe' ),
					'panel'       => 'aqualuxe_theme_options',
					'priority'    => 90,
				)
			);

			// Products per row.
			$wp_customize->add_setting(
				'woocommerce_columns',
				array(
					'default'           => 3,
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				'woocommerce_columns',
				array(
					'label'       => esc_html__( 'Products per row', 'aqualuxe' ),
					'description' => esc_html__( 'Number of products to display per row.', 'aqualuxe' ),
					'section'     => 'aqualuxe_woocommerce',
					'type'        => 'number',
					'input_attrs' => array(
						'min'  => 2,
						'max'  => 6,
						'step' => 1,
					),
				)
			);

			// Products per page.
			$wp_customize->add_setting(
				'woocommerce_products_per_page',
				array(
					'default'           => 12,
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				'woocommerce_products_per_page',
				array(
					'label'       => esc_html__( 'Products per page', 'aqualuxe' ),
					'description' => esc_html__( 'Number of products to display per page.', 'aqualuxe' ),
					'section'     => 'aqualuxe_woocommerce',
					'type'        => 'number',
					'input_attrs' => array(
						'min'  => 4,
						'max'  => 48,
						'step' => 4,
					),
				)
			);

			// Related products count.
			$wp_customize->add_setting(
				'woocommerce_related_products_count',
				array(
					'default'           => 4,
					'sanitize_callback' => 'absint',
				)
			);

			$wp_customize->add_control(
				'woocommerce_related_products_count',
				array(
					'label'       => esc_html__( 'Related products count', 'aqualuxe' ),
					'description' => esc_html__( 'Number of related products to display.', 'aqualuxe' ),
					'section'     => 'aqualuxe_woocommerce',
					'type'        => 'number',
					'input_attrs' => array(
						'min'  => 0,
						'max'  => 12,
						'step' => 1,
					),
				)
			);
		}

		// Add performance section.
		$wp_customize->add_section(
			'aqualuxe_performance',
			array(
				'title'       => esc_html__( 'Performance', 'aqualuxe' ),
				'description' => esc_html__( 'Performance optimization settings.', 'aqualuxe' ),
				'panel'       => 'aqualuxe_theme_options',
				'priority'    => 100,
			)
		);

		// Disable emojis.
		$wp_customize->add_setting(
			'disable_emojis',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'disable_emojis',
			array(
				'label'       => esc_html__( 'Disable Emojis', 'aqualuxe' ),
				'description' => esc_html__( 'Remove emoji scripts for better performance.', 'aqualuxe' ),
				'section'     => 'aqualuxe_performance',
				'type'        => 'checkbox',
			)
		);

		// Disable embeds.
		$wp_customize->add_setting(
			'disable_embeds',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'disable_embeds',
			array(
				'label'       => esc_html__( 'Disable Embeds', 'aqualuxe' ),
				'description' => esc_html__( 'Remove embed scripts for better performance.', 'aqualuxe' ),
				'section'     => 'aqualuxe_performance',
				'type'        => 'checkbox',
			)
		);

		// Lazy load images.
		$wp_customize->add_setting(
			'lazy_load_images',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'lazy_load_images',
			array(
				'label'       => esc_html__( 'Lazy Load Images', 'aqualuxe' ),
				'description' => esc_html__( 'Load images only when they enter the viewport.', 'aqualuxe' ),
				'section'     => 'aqualuxe_performance',
				'type'        => 'checkbox',
			)
		);

		// Add dark mode section.
		$wp_customize->add_section(
			'aqualuxe_dark_mode',
			array(
				'title'       => esc_html__( 'Dark Mode', 'aqualuxe' ),
				'description' => esc_html__( 'Dark mode settings.', 'aqualuxe' ),
				'panel'       => 'aqualuxe_theme_options',
				'priority'    => 110,
			)
		);

		// Enable dark mode.
		$wp_customize->add_setting(
			'enable_dark_mode',
			array(
				'default'           => true,
				'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
			)
		);

		$wp_customize->add_control(
			'enable_dark_mode',
			array(
				'label'       => esc_html__( 'Enable Dark Mode', 'aqualuxe' ),
				'description' => esc_html__( 'Allow users to switch to dark mode.', 'aqualuxe' ),
				'section'     => 'aqualuxe_dark_mode',
				'type'        => 'checkbox',
			)
		);

		// Dark mode default.
		$wp_customize->add_setting(
			'dark_mode_default',
			array(
				'default'           => 'system',
				'sanitize_callback' => array( $this, 'sanitize_dark_mode_default' ),
			)
		);

		$wp_customize->add_control(
			'dark_mode_default',
			array(
				'label'       => esc_html__( 'Default Mode', 'aqualuxe' ),
				'description' => esc_html__( 'Choose the default mode.', 'aqualuxe' ),
				'section'     => 'aqualuxe_dark_mode',
				'type'        => 'select',
				'choices'     => array(
					'light'  => esc_html__( 'Light', 'aqualuxe' ),
					'dark'   => esc_html__( 'Dark', 'aqualuxe' ),
					'system' => esc_html__( 'System', 'aqualuxe' ),
				),
			)
		);

		// Dark mode background color.
		$wp_customize->add_setting(
			'dark_mode_background',
			array(
				'default'           => '#121212',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dark_mode_background',
				array(
					'label'       => esc_html__( 'Dark Mode Background', 'aqualuxe' ),
					'description' => esc_html__( 'Background color for dark mode.', 'aqualuxe' ),
					'section'     => 'aqualuxe_dark_mode',
					'settings'    => 'dark_mode_background',
				)
			)
		);

		// Dark mode text color.
		$wp_customize->add_setting(
			'dark_mode_text',
			array(
				'default'           => '#e0e0e0',
				'sanitize_callback' => 'sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dark_mode_text',
				array(
					'label'       => esc_html__( 'Dark Mode Text', 'aqualuxe' ),
					'description' => esc_html__( 'Text color for dark mode.', 'aqualuxe' ),
					'section'     => 'aqualuxe_dark_mode',
					'settings'    => 'dark_mode_text',
				)
			)
		);
	}

	/**
	 * Enqueue customizer preview script
	 */
	public function customize_preview_js() {
		wp_enqueue_script(
			'aqualuxe-customizer-preview',
			AQUALUXE_URI . 'assets/dist/js/customizer.js',
			array( 'jquery', 'customize-preview' ),
			AQUALUXE_VERSION,
			true
		);
	}

	/**
	 * Enqueue customizer controls script
	 */
	public function customize_controls_js() {
		wp_enqueue_script(
			'aqualuxe-customizer-controls',
			AQUALUXE_URI . 'assets/dist/js/customizer-controls.js',
			array( 'jquery', 'customize-controls' ),
			AQUALUXE_VERSION,
			true
		);
	}

	/**
	 * Output customizer CSS
	 */
	public function output_customizer_css() {
		// Get customizer values.
		$primary_color = get_theme_mod( 'primary_color', '#00afcc' );
		$secondary_color = get_theme_mod( 'secondary_color', '#7f7faf' );
		$accent_color = get_theme_mod( 'accent_color', '#eb7d23' );
		$text_color = get_theme_mod( 'text_color', '#333333' );
		$background_color = get_theme_mod( 'background_color', '#ffffff' );
		$container_width = get_theme_mod( 'container_width', '1200' );
		$font_size_base = get_theme_mod( 'font_size_base', '16' );
		$heading_font = get_theme_mod( 'heading_font', 'Playfair Display' );
		$body_font = get_theme_mod( 'body_font', 'Montserrat' );
		$dark_mode_background = get_theme_mod( 'dark_mode_background', '#121212' );
		$dark_mode_text = get_theme_mod( 'dark_mode_text', '#e0e0e0' );
		?>
		<style type="text/css">
			:root {
				--color-primary: <?php echo esc_attr( $primary_color ); ?>;
				--color-secondary: <?php echo esc_attr( $secondary_color ); ?>;
				--color-accent: <?php echo esc_attr( $accent_color ); ?>;
				--color-text: <?php echo esc_attr( $text_color ); ?>;
				--color-background: <?php echo esc_attr( $background_color ); ?>;
				--font-heading: "<?php echo esc_attr( $heading_font ); ?>", serif;
				--font-body: "<?php echo esc_attr( $body_font ); ?>", sans-serif;
				--font-size-base: <?php echo esc_attr( $font_size_base ); ?>px;
				--container-width: <?php echo esc_attr( $container_width ); ?>px;
				--dark-mode-background: <?php echo esc_attr( $dark_mode_background ); ?>;
				--dark-mode-text: <?php echo esc_attr( $dark_mode_text ); ?>;
			}

			body {
				font-family: var(--font-body);
				font-size: var(--font-size-base);
				color: var(--color-text);
				background-color: var(--color-background);
			}

			h1, h2, h3, h4, h5, h6 {
				font-family: var(--font-heading);
			}

			a {
				color: var(--color-primary);
			}

			a:hover {
				color: var(--color-secondary);
			}

			.button, button, input[type="button"], input[type="reset"], input[type="submit"] {
				background-color: var(--color-primary);
				color: #ffffff;
			}

			.button:hover, button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover {
				background-color: var(--color-secondary);
			}

			.accent-color {
				color: var(--color-accent);
			}

			.container {
				max-width: var(--container-width);
			}

			/* Dark mode styles */
			.dark-mode {
				background-color: var(--dark-mode-background);
				color: var(--dark-mode-text);
			}

			.dark-mode a {
				color: var(--color-primary);
			}

			.dark-mode a:hover {
				color: var(--color-secondary);
			}

			@media (prefers-color-scheme: dark) {
				body.color-scheme-system {
					background-color: var(--dark-mode-background);
					color: var(--dark-mode-text);
				}

				body.color-scheme-system a {
					color: var(--color-primary);
				}

				body.color-scheme-system a:hover {
					color: var(--color-secondary);
				}
			}
		</style>
		<?php
	}

	/**
	 * Get font choices
	 *
	 * @return array Font choices.
	 */
	public function get_font_choices() {
		return array(
			'Arial'           => 'Arial',
			'Helvetica'       => 'Helvetica',
			'Georgia'         => 'Georgia',
			'Tahoma'          => 'Tahoma',
			'Verdana'         => 'Verdana',
			'Times New Roman' => 'Times New Roman',
			'Trebuchet MS'    => 'Trebuchet MS',
			'Montserrat'      => 'Montserrat',
			'Open Sans'       => 'Open Sans',
			'Roboto'          => 'Roboto',
			'Lato'            => 'Lato',
			'Oswald'          => 'Oswald',
			'Raleway'         => 'Raleway',
			'Playfair Display' => 'Playfair Display',
			'Merriweather'    => 'Merriweather',
			'Poppins'         => 'Poppins',
			'Nunito'          => 'Nunito',
			'Rubik'           => 'Rubik',
			'Work Sans'       => 'Work Sans',
			'Quicksand'       => 'Quicksand',
		);
	}

	/**
	 * Sanitize font
	 *
	 * @param string $input Font name.
	 * @return string
	 */
	public function sanitize_font( $input ) {
		$valid_fonts = $this->get_font_choices();
		
		if ( array_key_exists( $input, $valid_fonts ) ) {
			return $input;
		}
		
		return 'Montserrat';
	}

	/**
	 * Sanitize sidebar position
	 *
	 * @param string $input Sidebar position.
	 * @return string
	 */
	public function sanitize_sidebar_position( $input ) {
		$valid_positions = array( 'right', 'left', 'none' );
		
		if ( in_array( $input, $valid_positions, true ) ) {
			return $input;
		}
		
		return 'right';
	}

	/**
	 * Sanitize header layout
	 *
	 * @param string $input Header layout.
	 * @return string
	 */
	public function sanitize_header_layout( $input ) {
		$valid_layouts = array( 'default', 'centered', 'split', 'transparent' );
		
		if ( in_array( $input, $valid_layouts, true ) ) {
			return $input;
		}
		
		return 'default';
	}

	/**
	 * Sanitize dark mode default
	 *
	 * @param string $input Dark mode default.
	 * @return string
	 */
	public function sanitize_dark_mode_default( $input ) {
		$valid_defaults = array( 'light', 'dark', 'system' );
		
		if ( in_array( $input, $valid_defaults, true ) ) {
			return $input;
		}
		
		return 'system';
	}
}

/**
 * Sanitize checkbox
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox( $checked ) {
	return ( isset( $checked ) && true === $checked ) ? true : false;
}

// Initialize customizer.
new AquaLuxe_Customizer();