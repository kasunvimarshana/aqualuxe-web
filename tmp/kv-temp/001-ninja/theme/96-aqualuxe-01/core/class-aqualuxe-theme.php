<?php
/**
 * AquaLuxe Theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Theme class.
 */
final class AquaLuxe_Theme {

	/**
	 * Instance.
	 *
	 * @access private
	 * @static
	 *
	 * @var AquaLuxe_Theme
	 */
	private static $_instance = null;

	/**
	 * Module Manager.
	 *
	 * @access public
	 *
	 * @var AquaLuxe_Module_Manager
	 */
	public $module_manager;

	/**
	 * Ensure only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return AquaLuxe_Theme An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Clone.
	 *
	 * Disable class cloning.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'aqualuxe' ), '1.0.0' );
	}

	/**
	 * Wakeup.
	 *
	 * Disable unserializing the class.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'aqualuxe' ), '1.0.0' );
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->includes();
		$this->init();
	}

	/**
	 * Include files.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function includes() {
		require_once get_template_directory() . '/core/class-aqualuxe-autoloader.php';
		AquaLuxe_Autoloader::run();

		require_once get_template_directory() . '/core/class-aqualuxe-module-manager.php';
	}

	/**
	 * Initialize.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function init() {
		$this->module_manager = new AquaLuxe_Module_Manager();

		// Add theme support.
		add_action( 'after_setup_theme', [ $this, 'theme_support' ] );

		// Register widgets.
		add_action( 'widgets_init', [ $this, 'register_widgets' ] );

		// Enqueue scripts and styles.
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Theme support.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function theme_support() {
		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			[
				'primary' => esc_html__( 'Primary Menu', 'aqualuxe' ),
			]
		);
	}

	/**
	 * Register widgets.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_widgets() {
		// Register widgets.
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_scripts() {
		$asset_file = get_template_directory() . '/assets/dist/mix-manifest.json';
		$asset      = file_exists( $asset_file ) ? json_decode( file_get_contents( $asset_file ), true ) : [];

		$css_path = isset( $asset['/css/app.css'] ) ? $asset['/css/app.css'] : '/css/app.css';
		$js_path  = isset( $asset['/js/app.js'] ) ? $asset['/js/app.js'] : '/js/app.js';

		wp_enqueue_style( 'aqualuxe-style', get_template_directory_uri() . '/assets/dist' . $css_path, [], AQUALUXE_VERSION );
		wp_enqueue_script( 'aqualuxe-script', get_template_directory_uri() . '/assets/dist' . $js_path, [], AQUALUXE_VERSION, true );
	}
}

AquaLuxe_Theme::instance();
