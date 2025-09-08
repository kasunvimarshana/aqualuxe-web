<?php
/**
 * AquaLuxe Theme Setup
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main AquaLuxe Theme Setup Class.
 *
 * @class AquaLuxe_Theme_Setup
 */
final class AquaLuxe_Theme_Setup {

	/**
	 * The single instance of the class.
	 *
	 * @var AquaLuxe_Theme_Setup
	 */
	protected static $instance = null;

	/**
	 * Main AquaLuxe_Theme_Setup Instance.
	 *
	 * Ensures only one instance of AquaLuxe_Theme_Setup is loaded or can be loaded.
	 *
	 * @static
	 * @return AquaLuxe_Theme_Setup - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * AquaLuxe_Theme_Setup Constructor.
	 */
	private function __construct() {
		// Define constants.
		$this->define_constants();
	}

	/**
	 * Define AquaLuxe Constants.
	 */
	private function define_constants() {
		// AQUALUXE_VERSION is already defined in functions.php
		// AQUALUXE_THEME_DIR is already defined in functions.php
		// AQUALUXE_THEME_URI is already defined in functions.php
	}

	/**
	 * Initialize the theme.
	 */
	public function init() {
		// Add theme support.
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );

		// Register menus.
		add_action( 'init', array( $this, 'register_menus' ) );

		// Register widget area.
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );

		// Enqueue scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Load core components.
		$this->load_core_components();

		// Load modules.
		$this->load_modules();
	}

	/**
	 * Add theme support.
	 */
	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
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

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'aqualuxe_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Add support for wide alignment.
		add_theme_support( 'align-wide' );

		// Make theme available for translation.
		load_theme_textdomain( 'aqualuxe', AQUALUXE_THEME_DIR . '/languages' );
	}

	/**
	 * Register navigation menus.
	 */
	public function register_menus() {
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', 'aqualuxe' ),
			)
		);
	}

	/**
	 * Register widget area.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
	 */
	public function widgets_init() {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
				'id'            => 'sidebar-1',
				'description'   => esc_html__( 'Add widgets here.', 'aqualuxe' ),
				'before_widget' => '<section id="%1$s" class="widget %2$s bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="widget-title text-xl font-bold text-gray-900 dark:text-white mb-4">',
				'after_title'   => '</h2>',
			)
		);
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer 1', 'aqualuxe' ),
				'id'            => 'footer-1',
				'description'   => esc_html__( 'Add widgets here for the first footer column.', 'aqualuxe' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="font-bold text-lg mb-4 text-gray-900 dark:text-white">',
				'after_title'   => '</h4>',
			)
		);
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer 2', 'aqualuxe' ),
				'id'            => 'footer-2',
				'description'   => esc_html__( 'Add widgets here for the second footer column.', 'aqualuxe' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="font-bold text-lg mb-4 text-gray-900 dark:text-white">',
				'after_title'   => '</h4>',
			)
		);
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts() {
		// Enqueue main stylesheet.
		wp_enqueue_style( 'aqualuxe-style', get_stylesheet_uri(), array(), AQUALUXE_VERSION );

		// Enqueue other assets from webpack mix manifest if it exists.
		$manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
		if ( file_exists( $manifest_path ) ) {
			$manifest = json_decode( file_get_contents( $manifest_path ), true );
			if ( isset( $manifest['/js/app.js'] ) ) {
				wp_enqueue_script( 'aqualuxe-app', AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/js/app.js'], array(), null, true );
			}
			if ( isset( $manifest['/css/app.css'] ) ) {
				wp_enqueue_style( 'aqualuxe-app', AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/css/app.css'], array(), null );
			}
		}

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Load core components.
	 */
	private function load_core_components() {
		// Include files from the 'inc' directory.
		$inc_dir = AQUALUXE_THEME_DIR . '/inc/';
		require_once $inc_dir . 'template-tags.php';
		require_once $inc_dir . 'template-functions.php';
		require_once $inc_dir . 'customizer.php';
		require_once $inc_dir . 'woocommerce.php';
	}

	/**
	 * Load modules.
	 *
	 * Each module is a self-contained feature.
	 */
	private function load_modules() {
		$modules_dir = AQUALUXE_THEME_DIR . '/modules/';

		// Example of loading a module.
		// This can be expanded to dynamically load all modules in the directory
		// or load them based on a configuration.
		if ( file_exists( $modules_dir . 'custom-post-types/custom-post-types.php' ) ) {
		 	require_once $modules_dir . 'custom-post-types/custom-post-types.php';
		}
		if ( file_exists( $modules_dir . 'dark-mode/dark-mode.php' ) ) {
			require_once $modules_dir . 'dark-mode/dark-mode.php';
		}
		if ( file_exists( $modules_dir . 'demo-importer/demo-importer.php' ) ) {
			require_once $modules_dir . 'demo-importer/demo-importer.php';
		}
		if ( file_exists( $modules_dir . 'mega-menu/mega-menu.php' ) ) {
			require_once $modules_dir . 'mega-menu/mega-menu.php';
		}
		if ( file_exists( $modules_dir . 'slide-in-panel/slide-in-panel.php' ) ) {
			require_once $modules_dir . 'slide-in-panel/slide-in-panel.php';
		}
		if ( file_exists( $modules_dir . 'advanced-search/advanced-search.php' ) ) {
			require_once $modules_dir . 'advanced-search/advanced-search.php';
		}
		if ( file_exists( $modules_dir . 'social-media/social-media.php' ) ) {
			require_once $modules_dir . 'social-media/social-media.php';
		}
	}
}
