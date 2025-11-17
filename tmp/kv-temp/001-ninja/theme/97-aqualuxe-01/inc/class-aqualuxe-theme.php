<?php
/**
 * The main theme class.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The core theme class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this theme as well as the current
 * version of the theme.
 *
 * @since      1.0.0
 * @package    AquaLuxe
 * @author     Kasun Vimarshana <kasunv.com@gmail.com>
 */
class AquaLuxe_Theme {

	/**
	 * The theme version.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the theme.
	 */
	protected $version;

	/**
	 * The theme name.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $theme_name    The name of the theme.
	 */
	protected $theme_name;

	/**
	 * The assets handler.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      AquaLuxe_Assets    $assets    The assets handler.
	 */
	protected $assets;

	/**
	 * Define the core functionality of the theme.
	 *
	 * Set the theme name and the theme version that can be used throughout the theme.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$theme_data   = wp_get_theme();
		$this->version = $theme_data->get( 'Version' );
		$this->theme_name = $theme_data->get( 'Name' );

		$this->load_dependencies();
		$this->define_hooks();
	}

	/**
	 * Define all hooks for the theme.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function define_hooks() {
		$this->assets = new AquaLuxe_Assets();
		add_action( 'after_setup_theme', array( $this, 'theme_setup' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		// Load modules
		$this->load_modules();
	}

	/**
	 * Load the required dependencies for this theme.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		require_once get_template_directory() . '/inc/class-aqualuxe-assets.php';
		require_once get_template_directory() . '/inc/custom-post-types.php';
		require_once get_template_directory() . '/inc/customizer.php';
		require_once get_template_directory() . '/inc/woocommerce.php';
		require_once get_template_directory() . '/inc/demo-importer.php';

		// Register CPTs and Taxonomies
		$cpt = new AquaLuxe_Custom_Post_Types();
		$cpt->register();

		// Register Customizer options
		$customizer = new AquaLuxe_Customizer();
		$customizer->register();

		// WooCommerce integration
		if ( class_exists( 'WooCommerce' ) ) {
			$woocommerce = new AquaLuxe_WooCommerce();
			$woocommerce->register();
		}
	}

	/**
	 * Load all modules from the /modules/ directory.
	 *
	 * @since 1.0.0
	 */
	private function load_modules() {
		$modules_path = get_template_directory() . '/modules';
		if ( ! is_dir( $modules_path ) ) {
			return;
		}

		$modules = scandir( $modules_path );
		foreach ( $modules as $module ) {
			if ( '.' === $module || '..' === $module ) {
				continue;
			}
			$module_file = $modules_path . '/' . $module . '/' . $module . '.php';
			if ( file_exists( $module_file ) ) {
				require_once $module_file;
			}
		}
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since 1.0.0
	 */
	public function theme_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'aqualuxe', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'aqualuxe' ),
				'footer'  => esc_html__( 'Footer Menu', 'aqualuxe' ),
			)
		);

		// Switch default core markup for search form, comment form, and comments to output valid HTML5.
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for WooCommerce.
		add_theme_support( 'woocommerce' );

        // Add support for WooCommerce gallery zoom, lightbox and slider.
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		// Enqueue main stylesheet.
		wp_enqueue_style( 'aqualuxe-style', get_stylesheet_uri(), array(), $this->version );

		// Enqueue compiled assets.
		wp_enqueue_style( 'aqualuxe-app', $this->assets->get_asset_path( '/css/app.css' ), array(), $this->version );
		wp_enqueue_script( 'aqualuxe-app', $this->assets->get_asset_path( '/js/app.js' ), array('jquery'), $this->version, true );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 * *
	 * @since    1.0.0
	 */
	public function run() {
		// All actions and filters are added in the constructor.
	}
}
