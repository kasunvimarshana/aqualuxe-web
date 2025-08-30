<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.1.0
 */

namespace AquaLuxe\Customizer;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Class
 *
 * Handles theme customization options.
 *
 * @since 1.1.0
 */
class Customizer {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// Load customizer sections.
		$this->load_sections();
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'customize_register', array( $this, 'register' ) );
		add_action( 'customize_preview_init', array( $this, 'preview_js' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_js' ) );
		add_action( 'wp_head', array( $this, 'output_custom_css' ), 100 );
		add_action( 'customize_save_after', array( $this, 'generate_custom_css_file' ) );
	}

	/**
	 * Load customizer sections.
	 *
	 * @return void
	 */
	private function load_sections() {
		// Load section classes.
		require_once AQUALUXE_DIR . '/inc/customizer/sections/class-general.php';
		require_once AQUALUXE_DIR . '/inc/customizer/sections/class-header.php';
		require_once AQUALUXE_DIR . '/inc/customizer/sections/class-footer.php';
		require_once AQUALUXE_DIR . '/inc/customizer/sections/class-typography.php';
		require_once AQUALUXE_DIR . '/inc/customizer/sections/class-colors.php';
		require_once AQUALUXE_DIR . '/inc/customizer/sections/class-layout.php';
		require_once AQUALUXE_DIR . '/inc/customizer/sections/class-blog.php';
		require_once AQUALUXE_DIR . '/inc/customizer/sections/class-woocommerce.php';
		require_once AQUALUXE_DIR . '/inc/customizer/sections/class-performance.php';
		require_once AQUALUXE_DIR . '/inc/customizer/sections/class-social-media.php';
		require_once AQUALUXE_DIR . '/inc/customizer/sections/class-advanced.php';
	}

	/**
	 * Register customizer options.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @return void
	 */
	public function register( $wp_customize ) {
		// Register custom controls.
		$this->register_custom_controls( $wp_customize );
		
		// Register sections.
		$this->register_sections( $wp_customize );
		
		// Register selective refresh.
		$this->register_selective_refresh( $wp_customize );
		
		// Register custom sections.
		$this->register_custom_sections( $wp_customize );
	}

	/**
	 * Register custom controls.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @return void
	 */
	private function register_custom_controls( $wp_customize ) {
		// Load custom control classes.
		require_once AQUALUXE_DIR . '/inc/customizer/controls/class-toggle-control.php';
		require_once AQUALUXE_DIR . '/inc/customizer/controls/class-slider-control.php';
		require_once AQUALUXE_DIR . '/inc/customizer/controls/class-sortable-control.php';
		require_once AQUALUXE_DIR . '/inc/customizer/controls/class-radio-image-control.php';
		require_once AQUALUXE_DIR . '/inc/customizer/controls/class-typography-control.php';
		require_once AQUALUXE_DIR . '/inc/customizer/controls/class-color-alpha-control.php';
		require_once AQUALUXE_DIR . '/inc/customizer/controls/class-dimensions-control.php';
		require_once AQUALUXE_DIR . '/inc/customizer/controls/class-heading-control.php';
		require_once AQUALUXE_DIR . '/inc/customizer/controls/class-divider-control.php';
		
		// Register custom controls.
		$wp_customize->register_control_type( 'AquaLuxe\\Customizer\\Controls\\Toggle_Control' );
		$wp_customize->register_control_type( 'AquaLuxe\\Customizer\\Controls\\Slider_Control' );
		$wp_customize->register_control_type( 'AquaLuxe\\Customizer\\Controls\\Sortable_Control' );
		$wp_customize->register_control_type( 'AquaLuxe\\Customizer\\Controls\\Radio_Image_Control' );
		$wp_customize->register_control_type( 'AquaLuxe\\Customizer\\Controls\\Typography_Control' );
		$wp_customize->register_control_type( 'AquaLuxe\\Customizer\\Controls\\Color_Alpha_Control' );
		$wp_customize->register_control_type( 'AquaLuxe\\Customizer\\Controls\\Dimensions_Control' );
		$wp_customize->register_control_type( 'AquaLuxe\\Customizer\\Controls\\Heading_Control' );
		$wp_customize->register_control_type( 'AquaLuxe\\Customizer\\Controls\\Divider_Control' );
	}

	/**
	 * Register sections.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @return void
	 */
	private function register_sections( $wp_customize ) {
		// Initialize section classes.
		$general     = new Sections\General( $wp_customize );
		$header      = new Sections\Header( $wp_customize );
		$footer      = new Sections\Footer( $wp_customize );
		$typography  = new Sections\Typography( $wp_customize );
		$colors      = new Sections\Colors( $wp_customize );
		$layout      = new Sections\Layout( $wp_customize );
		$blog        = new Sections\Blog( $wp_customize );
		$woocommerce = new Sections\WooCommerce( $wp_customize );
		$performance = new Sections\Performance( $wp_customize );
		$social      = new Sections\Social_Media( $wp_customize );
		$advanced    = new Sections\Advanced( $wp_customize );
		
		// Register sections.
		$general->register();
		$header->register();
		$footer->register();
		$typography->register();
		$colors->register();
		$layout->register();
		$blog->register();
		// WooCommerce class already initialized with $wp_customize parameter
		// Performance class now initialized with $wp_customize parameter
		// Social_Media class already initialized with $wp_customize parameter
		// Advanced class already initialized with $wp_customize parameter
	}

	/**
	 * Register selective refresh.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @return void
	 */
	private function register_selective_refresh( $wp_customize ) {
		// Site title and description.
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => function() {
					return get_bloginfo( 'name', 'display' );
				},
			)
		);
		
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => function() {
					return get_bloginfo( 'description', 'display' );
				},
			)
		);
		
		// Header elements.
		$wp_customize->selective_refresh->add_partial(
			'aqualuxe_header_layout',
			array(
				'selector'        => '.site-header',
				'render_callback' => function() {
					get_template_part( 'template-parts/header/header' );
				},
			)
		);
		
		// Footer elements.
		$wp_customize->selective_refresh->add_partial(
			'aqualuxe_footer_layout',
			array(
				'selector'        => '.site-footer',
				'render_callback' => function() {
					get_template_part( 'template-parts/footer/footer' );
				},
			)
		);
		
		// Copyright text.
		$wp_customize->selective_refresh->add_partial(
			'aqualuxe_copyright_text',
			array(
				'selector'        => '.site-info',
				'render_callback' => function() {
					get_template_part( 'template-parts/footer/site-info' );
				},
			)
		);
	}

	/**
	 * Register custom sections.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @return void
	 */
	private function register_custom_sections( $wp_customize ) {
		// Load custom section classes.
		require_once AQUALUXE_DIR . '/inc/customizer/sections/class-pro-section.php';
		
		// Register custom sections.
		$wp_customize->register_section_type( 'AquaLuxe\\Customizer\\Sections\\Pro_Section' );
		
		// Add pro section.
		$wp_customize->add_section(
			new Sections\Pro_Section(
				$wp_customize,
				'aqualuxe_pro',
				array(
					'title'       => esc_html__( 'AquaLuxe Pro', 'aqualuxe' ),
					'pro_text'    => esc_html__( 'Upgrade to Pro', 'aqualuxe' ),
					'pro_url'     => 'https://aqualuxe.example.com/pro',
					'priority'    => 0,
				)
			)
		);
	}

	/**
	 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
	 *
	 * @return void
	 */
	public function preview_js() {
		wp_enqueue_script(
			'aqualuxe-customizer-preview',
			AQUALUXE_URI . '/assets/js/customizer-preview.js',
			array( 'customize-preview' ),
			AQUALUXE_VERSION,
			true
		);
		
		// Localize script.
		wp_localize_script(
			'aqualuxe-customizer-preview',
			'aqualuxeCustomizerPreview',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'aqualuxe-customizer-preview' ),
			)
		);
	}

	/**
	 * Binds JS handlers for Customizer controls.
	 *
	 * @return void
	 */
	public function controls_js() {
		wp_enqueue_script(
			'aqualuxe-customizer-controls',
			AQUALUXE_URI . '/assets/js/customizer-controls.js',
			array( 'customize-controls', 'jquery', 'wp-color-picker' ),
			AQUALUXE_VERSION,
			true
		);
		
		// Localize script.
		wp_localize_script(
			'aqualuxe-customizer-controls',
			'aqualuxeCustomizerControls',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'aqualuxe-customizer-controls' ),
			)
		);
		
		// Enqueue styles.
		wp_enqueue_style(
			'aqualuxe-customizer-controls',
			AQUALUXE_URI . '/assets/css/customizer-controls.css',
			array( 'wp-color-picker' ),
			AQUALUXE_VERSION
		);
	}

	/**
	 * Output custom CSS.
	 *
	 * @return void
	 */
	public function output_custom_css() {
		// Check if custom CSS file exists.
		$upload_dir = wp_upload_dir();
		$css_file   = trailingslashit( $upload_dir['basedir'] ) . 'aqualuxe/custom-styles.css';
		
		if ( file_exists( $css_file ) ) {
			// Output link to custom CSS file.
			$css_url = trailingslashit( $upload_dir['baseurl'] ) . 'aqualuxe/custom-styles.css';
			echo '<link rel="stylesheet" id="aqualuxe-custom-styles" href="' . esc_url( $css_url ) . '?ver=' . esc_attr( AQUALUXE_VERSION ) . '" media="all">' . "\n";
		} else {
			// Generate and output custom CSS.
			$css = $this->generate_custom_css();
			
			if ( $css ) {
				echo '<style id="aqualuxe-custom-styles">' . wp_strip_all_tags( $css ) . '</style>' . "\n";
			}
		}
	}

	/**
	 * Generate custom CSS file.
	 *
	 * @return void
	 */
	public function generate_custom_css_file() {
		// Generate custom CSS.
		$css = $this->generate_custom_css();
		
		if ( ! $css ) {
			return;
		}
		
		// Create upload directory if it doesn't exist.
		$upload_dir = wp_upload_dir();
		$dir        = trailingslashit( $upload_dir['basedir'] ) . 'aqualuxe';
		
		if ( ! file_exists( $dir ) ) {
			wp_mkdir_p( $dir );
		}
		
		// Create index.php file.
		if ( ! file_exists( trailingslashit( $dir ) . 'index.php' ) ) {
			$index_file = fopen( trailingslashit( $dir ) . 'index.php', 'w' );
			fwrite( $index_file, '<?php // Silence is golden.' );
			fclose( $index_file );
		}
		
		// Create custom CSS file.
		$css_file = fopen( trailingslashit( $dir ) . 'custom-styles.css', 'w' );
		fwrite( $css_file, $css );
		fclose( $css_file );
	}

	/**
	 * Generate custom CSS.
	 *
	 * @return string
	 */
	private function generate_custom_css() {
		$css = '';
		
		// Colors.
		$primary_color   = get_theme_mod( 'aqualuxe_primary_color', '#0ea5e9' );
		$secondary_color = get_theme_mod( 'aqualuxe_secondary_color', '#14b8a6' );
		$accent_color    = get_theme_mod( 'aqualuxe_accent_color', '#f97316' );
		$text_color      = get_theme_mod( 'aqualuxe_text_color', '#374151' );
		$heading_color   = get_theme_mod( 'aqualuxe_heading_color', '#1f2937' );
		$link_color      = get_theme_mod( 'aqualuxe_link_color', '#0ea5e9' );
		$link_hover_color = get_theme_mod( 'aqualuxe_link_hover_color', '#0369a1' );
		$background_color = get_theme_mod( 'aqualuxe_background_color', '#ffffff' );
		
		// Typography.
		$body_font_family = get_theme_mod( 'aqualuxe_body_font_family', 'Inter' );
		$body_font_size   = get_theme_mod( 'aqualuxe_body_font_size', '16px' );
		$body_line_height = get_theme_mod( 'aqualuxe_body_line_height', '1.6' );
		$heading_font_family = get_theme_mod( 'aqualuxe_heading_font_family', 'Playfair Display' );
		
		// Layout.
		$container_width = get_theme_mod( 'aqualuxe_container_width', '1200px' );
		$sidebar_width   = get_theme_mod( 'aqualuxe_sidebar_width', '300px' );
		
		// Header.
		$header_background = get_theme_mod( 'aqualuxe_header_background', '#ffffff' );
		$header_text_color = get_theme_mod( 'aqualuxe_header_text_color', '#374151' );
		
		// Footer.
		$footer_background = get_theme_mod( 'aqualuxe_footer_background', '#1f2937' );
		$footer_text_color = get_theme_mod( 'aqualuxe_footer_text_color', '#f3f4f6' );
		
		// Generate CSS.
		$css .= ':root {';
		$css .= '--primary-color: ' . $primary_color . ';';
		$css .= '--secondary-color: ' . $secondary_color . ';';
		$css .= '--accent-color: ' . $accent_color . ';';
		$css .= '--text-color: ' . $text_color . ';';
		$css .= '--heading-color: ' . $heading_color . ';';
		$css .= '--link-color: ' . $link_color . ';';
		$css .= '--link-hover-color: ' . $link_hover_color . ';';
		$css .= '--background-color: ' . $background_color . ';';
		$css .= '--body-font-family: "' . $body_font_family . '", sans-serif;';
		$css .= '--body-font-size: ' . $body_font_size . ';';
		$css .= '--body-line-height: ' . $body_line_height . ';';
		$css .= '--heading-font-family: "' . $heading_font_family . '", serif;';
		$css .= '--container-width: ' . $container_width . ';';
		$css .= '--sidebar-width: ' . $sidebar_width . ';';
		$css .= '--header-background: ' . $header_background . ';';
		$css .= '--header-text-color: ' . $header_text_color . ';';
		$css .= '--footer-background: ' . $footer_background . ';';
		$css .= '--footer-text-color: ' . $footer_text_color . ';';
		$css .= '}';
		
		// Dark mode colors.
		$dark_primary_color   = get_theme_mod( 'aqualuxe_dark_primary_color', '#38bdf8' );
		$dark_secondary_color = get_theme_mod( 'aqualuxe_dark_secondary_color', '#2dd4bf' );
		$dark_accent_color    = get_theme_mod( 'aqualuxe_dark_accent_color', '#fb923c' );
		$dark_text_color      = get_theme_mod( 'aqualuxe_dark_text_color', '#f3f4f6' );
		$dark_heading_color   = get_theme_mod( 'aqualuxe_dark_heading_color', '#f9fafb' );
		$dark_link_color      = get_theme_mod( 'aqualuxe_dark_link_color', '#38bdf8' );
		$dark_link_hover_color = get_theme_mod( 'aqualuxe_dark_link_hover_color', '#7dd3fc' );
		$dark_background_color = get_theme_mod( 'aqualuxe_dark_background_color', '#111827' );
		
		$css .= '.dark {';
		$css .= '--primary-color: ' . $dark_primary_color . ';';
		$css .= '--secondary-color: ' . $dark_secondary_color . ';';
		$css .= '--accent-color: ' . $dark_accent_color . ';';
		$css .= '--text-color: ' . $dark_text_color . ';';
		$css .= '--heading-color: ' . $dark_heading_color . ';';
		$css .= '--link-color: ' . $dark_link_color . ';';
		$css .= '--link-hover-color: ' . $dark_link_hover_color . ';';
		$css .= '--background-color: ' . $dark_background_color . ';';
		$css .= '--header-background: ' . get_theme_mod( 'aqualuxe_dark_header_background', '#111827' ) . ';';
		$css .= '--header-text-color: ' . get_theme_mod( 'aqualuxe_dark_header_text_color', '#f3f4f6' ) . ';';
		$css .= '--footer-background: ' . get_theme_mod( 'aqualuxe_dark_footer_background', '#0f172a' ) . ';';
		$css .= '--footer-text-color: ' . get_theme_mod( 'aqualuxe_dark_footer_text_color', '#f3f4f6' ) . ';';
		$css .= '}';
		
		// Basic styles.
		$css .= 'body {';
		$css .= 'font-family: var(--body-font-family);';
		$css .= 'font-size: var(--body-font-size);';
		$css .= 'line-height: var(--body-line-height);';
		$css .= 'color: var(--text-color);';
		$css .= 'background-color: var(--background-color);';
		$css .= '}';
		
		$css .= 'h1, h2, h3, h4, h5, h6 {';
		$css .= 'font-family: var(--heading-font-family);';
		$css .= 'color: var(--heading-color);';
		$css .= '}';
		
		$css .= 'a {';
		$css .= 'color: var(--link-color);';
		$css .= '}';
		
		$css .= 'a:hover, a:focus {';
		$css .= 'color: var(--link-hover-color);';
		$css .= '}';
		
		$css .= '.container {';
		$css .= 'max-width: var(--container-width);';
		$css .= '}';
		
		$css .= '.site-header {';
		$css .= 'background-color: var(--header-background);';
		$css .= 'color: var(--header-text-color);';
		$css .= '}';
		
		$css .= '.site-footer {';
		$css .= 'background-color: var(--footer-background);';
		$css .= 'color: var(--footer-text-color);';
		$css .= '}';
		
		// Add custom CSS.
		$custom_css = get_theme_mod( 'aqualuxe_custom_css' );
		if ( $custom_css ) {
			$css .= $custom_css;
		}
		
		return $css;
	}
}