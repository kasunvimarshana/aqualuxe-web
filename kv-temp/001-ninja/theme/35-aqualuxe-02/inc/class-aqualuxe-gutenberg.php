<?php
/**
 * AquaLuxe Gutenberg Compatibility
 *
 * This class handles the theme's compatibility with the Gutenberg block editor
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe_Gutenberg class
 */
class AquaLuxe_Gutenberg {

	/**
	 * Instance of this class
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Get instance of this class
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		// Register hooks.
		add_action( 'after_setup_theme', array( $this, 'theme_setup' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor_assets' ) );
		add_action( 'init', array( $this, 'register_block_patterns' ) );
		add_action( 'init', array( $this, 'register_block_styles' ) );
		add_filter( 'block_categories_all', array( $this, 'register_block_categories' ), 10, 2 );
	}

	/**
	 * Theme setup for Gutenberg
	 */
	public function theme_setup() {
		// Add support for editor styles.
		add_theme_support( 'editor-styles' );
		
		// Enqueue editor styles.
		add_editor_style( 'dist/css/editor-style.css' );
		
		// Add support for wide alignments.
		add_theme_support( 'align-wide' );
		
		// Add support for responsive embeds.
		add_theme_support( 'responsive-embeds' );
		
		// Add support for custom line height.
		add_theme_support( 'custom-line-height' );
		
		// Add support for experimental link color control.
		add_theme_support( 'experimental-link-color' );
		
		// Add support for custom units.
		add_theme_support( 'custom-units' );
		
		// Add support for custom spacing.
		add_theme_support( 'custom-spacing' );
		
		// Define theme color palette.
		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => esc_html__( 'Primary', 'aqualuxe' ),
					'slug'  => 'primary',
					'color' => '#0073aa',
				),
				array(
					'name'  => esc_html__( 'Secondary', 'aqualuxe' ),
					'slug'  => 'secondary',
					'color' => '#23282d',
				),
				array(
					'name'  => esc_html__( 'Accent', 'aqualuxe' ),
					'slug'  => 'accent',
					'color' => '#00a0d2',
				),
				array(
					'name'  => esc_html__( 'Light', 'aqualuxe' ),
					'slug'  => 'light',
					'color' => '#f8f9fa',
				),
				array(
					'name'  => esc_html__( 'Dark', 'aqualuxe' ),
					'slug'  => 'dark',
					'color' => '#212529',
				),
				array(
					'name'  => esc_html__( 'White', 'aqualuxe' ),
					'slug'  => 'white',
					'color' => '#ffffff',
				),
				array(
					'name'  => esc_html__( 'Black', 'aqualuxe' ),
					'slug'  => 'black',
					'color' => '#000000',
				),
			)
		);
		
		// Define theme font sizes.
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => esc_html__( 'Small', 'aqualuxe' ),
					'shortName' => esc_html__( 'S', 'aqualuxe' ),
					'size'      => 14,
					'slug'      => 'small',
				),
				array(
					'name'      => esc_html__( 'Normal', 'aqualuxe' ),
					'shortName' => esc_html__( 'M', 'aqualuxe' ),
					'size'      => 16,
					'slug'      => 'normal',
				),
				array(
					'name'      => esc_html__( 'Large', 'aqualuxe' ),
					'shortName' => esc_html__( 'L', 'aqualuxe' ),
					'size'      => 20,
					'slug'      => 'large',
				),
				array(
					'name'      => esc_html__( 'Huge', 'aqualuxe' ),
					'shortName' => esc_html__( 'XL', 'aqualuxe' ),
					'size'      => 24,
					'slug'      => 'huge',
				),
			)
		);
		
		// Add support for custom gradients.
		add_theme_support(
			'editor-gradient-presets',
			array(
				array(
					'name'     => esc_html__( 'Primary to Accent', 'aqualuxe' ),
					'gradient' => 'linear-gradient(135deg, #0073aa 0%, #00a0d2 100%)',
					'slug'     => 'primary-to-accent',
				),
				array(
					'name'     => esc_html__( 'Light to Dark', 'aqualuxe' ),
					'gradient' => 'linear-gradient(135deg, #f8f9fa 0%, #212529 100%)',
					'slug'     => 'light-to-dark',
				),
				array(
					'name'     => esc_html__( 'Ocean Blue', 'aqualuxe' ),
					'gradient' => 'linear-gradient(135deg, #1a73e8 0%, #00c6ff 100%)',
					'slug'     => 'ocean-blue',
				),
			)
		);
	}

	/**
	 * Enqueue block editor assets
	 */
	public function block_editor_assets() {
		$theme_version = wp_get_theme()->get( 'Version' );
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		// Enqueue block editor script.
		wp_enqueue_script(
			'aqualuxe-block-editor',
			get_template_directory_uri() . '/dist/js/block-editor' . $suffix . '.js',
			array( 'wp-blocks', 'wp-dom', 'wp-edit-post' ),
			$theme_version,
			true
		);
		
		// Add inline script with theme settings.
		$theme_settings = array(
			'themeColors'    => $this->get_theme_colors(),
			'fontSizes'      => $this->get_theme_font_sizes(),
			'customSettings' => $this->get_theme_custom_settings(),
		);
		
		wp_localize_script( 'aqualuxe-block-editor', 'aqualuxeEditorSettings', $theme_settings );
	}

	/**
	 * Get theme colors for editor
	 *
	 * @return array Theme colors.
	 */
	private function get_theme_colors() {
		return array(
			'primary'   => get_theme_mod( 'aqualuxe_primary_color', '#0073aa' ),
			'secondary' => get_theme_mod( 'aqualuxe_secondary_color', '#23282d' ),
			'accent'    => get_theme_mod( 'aqualuxe_accent_color', '#00a0d2' ),
			'light'     => get_theme_mod( 'aqualuxe_light_color', '#f8f9fa' ),
			'dark'      => get_theme_mod( 'aqualuxe_dark_color', '#212529' ),
		);
	}

	/**
	 * Get theme font sizes for editor
	 *
	 * @return array Theme font sizes.
	 */
	private function get_theme_font_sizes() {
		return array(
			'small'  => get_theme_mod( 'aqualuxe_small_font_size', 14 ),
			'normal' => get_theme_mod( 'aqualuxe_normal_font_size', 16 ),
			'large'  => get_theme_mod( 'aqualuxe_large_font_size', 20 ),
			'huge'   => get_theme_mod( 'aqualuxe_huge_font_size', 24 ),
		);
	}

	/**
	 * Get theme custom settings for editor
	 *
	 * @return array Theme custom settings.
	 */
	private function get_theme_custom_settings() {
		return array(
			'containerWidth' => get_theme_mod( 'aqualuxe_container_width', 1140 ),
			'sidebarWidth'   => get_theme_mod( 'aqualuxe_sidebar_width', 300 ),
			'gridGap'        => get_theme_mod( 'aqualuxe_grid_gap', 30 ),
		);
	}

	/**
	 * Register block patterns
	 */
	public function register_block_patterns() {
		// Check if block patterns are supported.
		if ( ! function_exists( 'register_block_pattern_category' ) || ! function_exists( 'register_block_pattern' ) ) {
			return;
		}
		
		// Register block pattern category.
		register_block_pattern_category(
			'aqualuxe',
			array( 'label' => esc_html__( 'AquaLuxe', 'aqualuxe' ) )
		);
		
		// Register hero section pattern.
		register_block_pattern(
			'aqualuxe/hero-section',
			array(
				'title'       => esc_html__( 'Hero Section', 'aqualuxe' ),
				'description' => esc_html__( 'A hero section with heading, text, and button', 'aqualuxe' ),
				'categories'  => array( 'aqualuxe' ),
				'content'     => $this->get_pattern_content( 'hero-section' ),
			)
		);
		
		// Register features section pattern.
		register_block_pattern(
			'aqualuxe/features-section',
			array(
				'title'       => esc_html__( 'Features Section', 'aqualuxe' ),
				'description' => esc_html__( 'A section displaying features in columns', 'aqualuxe' ),
				'categories'  => array( 'aqualuxe' ),
				'content'     => $this->get_pattern_content( 'features-section' ),
			)
		);
		
		// Register testimonials section pattern.
		register_block_pattern(
			'aqualuxe/testimonials-section',
			array(
				'title'       => esc_html__( 'Testimonials Section', 'aqualuxe' ),
				'description' => esc_html__( 'A section displaying customer testimonials', 'aqualuxe' ),
				'categories'  => array( 'aqualuxe' ),
				'content'     => $this->get_pattern_content( 'testimonials-section' ),
			)
		);
		
		// Register call to action pattern.
		register_block_pattern(
			'aqualuxe/call-to-action',
			array(
				'title'       => esc_html__( 'Call to Action', 'aqualuxe' ),
				'description' => esc_html__( 'A call to action section with heading and button', 'aqualuxe' ),
				'categories'  => array( 'aqualuxe' ),
				'content'     => $this->get_pattern_content( 'call-to-action' ),
			)
		);
		
		// Register pricing table pattern.
		register_block_pattern(
			'aqualuxe/pricing-table',
			array(
				'title'       => esc_html__( 'Pricing Table', 'aqualuxe' ),
				'description' => esc_html__( 'A pricing table with multiple columns', 'aqualuxe' ),
				'categories'  => array( 'aqualuxe' ),
				'content'     => $this->get_pattern_content( 'pricing-table' ),
			)
		);
	}

	/**
	 * Get pattern content from file
	 *
	 * @param string $pattern_name Pattern name.
	 * @return string Pattern content.
	 */
	private function get_pattern_content( $pattern_name ) {
		$pattern_file = get_template_directory() . '/inc/patterns/' . $pattern_name . '.php';
		
		if ( file_exists( $pattern_file ) ) {
			ob_start();
			include $pattern_file;
			return ob_get_clean();
		}
		
		return '';
	}

	/**
	 * Register block styles
	 */
	public function register_block_styles() {
		// Check if block styles are supported.
		if ( ! function_exists( 'register_block_style' ) ) {
			return;
		}
		
		// Register button styles.
		register_block_style(
			'core/button',
			array(
				'name'         => 'aqualuxe-outline',
				'label'        => esc_html__( 'Outline', 'aqualuxe' ),
				'style_handle' => 'aqualuxe-block-styles',
			)
		);
		
		register_block_style(
			'core/button',
			array(
				'name'         => 'aqualuxe-rounded',
				'label'        => esc_html__( 'Rounded', 'aqualuxe' ),
				'style_handle' => 'aqualuxe-block-styles',
			)
		);
		
		// Register image styles.
		register_block_style(
			'core/image',
			array(
				'name'         => 'aqualuxe-rounded',
				'label'        => esc_html__( 'Rounded', 'aqualuxe' ),
				'style_handle' => 'aqualuxe-block-styles',
			)
		);
		
		register_block_style(
			'core/image',
			array(
				'name'         => 'aqualuxe-shadow',
				'label'        => esc_html__( 'Shadow', 'aqualuxe' ),
				'style_handle' => 'aqualuxe-block-styles',
			)
		);
		
		// Register group styles.
		register_block_style(
			'core/group',
			array(
				'name'         => 'aqualuxe-card',
				'label'        => esc_html__( 'Card', 'aqualuxe' ),
				'style_handle' => 'aqualuxe-block-styles',
			)
		);
		
		register_block_style(
			'core/group',
			array(
				'name'         => 'aqualuxe-border',
				'label'        => esc_html__( 'Border', 'aqualuxe' ),
				'style_handle' => 'aqualuxe-block-styles',
			)
		);
		
		// Register quote styles.
		register_block_style(
			'core/quote',
			array(
				'name'         => 'aqualuxe-fancy',
				'label'        => esc_html__( 'Fancy', 'aqualuxe' ),
				'style_handle' => 'aqualuxe-block-styles',
			)
		);
		
		// Register separator styles.
		register_block_style(
			'core/separator',
			array(
				'name'         => 'aqualuxe-fancy',
				'label'        => esc_html__( 'Fancy', 'aqualuxe' ),
				'style_handle' => 'aqualuxe-block-styles',
			)
		);
	}

	/**
	 * Register block categories
	 *
	 * @param array  $categories Block categories.
	 * @param object $post       Post object.
	 * @return array Modified block categories.
	 */
	public function register_block_categories( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'aqualuxe',
					'title' => esc_html__( 'AquaLuxe', 'aqualuxe' ),
					'icon'  => null,
				),
			)
		);
	}
}

// Initialize the class.
AquaLuxe_Gutenberg::get_instance();