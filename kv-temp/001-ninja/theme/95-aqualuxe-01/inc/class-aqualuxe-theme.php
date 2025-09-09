<?php
/**
 * Main theme class for AquaLuxe.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The main theme class.
 */
final class AquaLuxe_Theme {

	/**
	 * The single instance of the class.
	 *
	 * @var AquaLuxe_Theme
	 */
	private static $instance = null;

	/**
	 * Ensures only one instance of the class is loaded or can be instantiated.
	 *
	 * @return AquaLuxe_Theme - Main instance.
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->setup();
		}
		return self::$instance;
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning is forbidden.', 'aqualuxe' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing instances of this class is forbidden.', 'aqualuxe' ), '1.0.0' );
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
	}

	/**
	 * Theme setup.
	 */
	private function setup() {
		$this->includes();
		$this->add_hooks();
	}

	/**
	 * Include required files.
	 */
	private function includes() {
		require_once AQUALUXE_THEME_DIR . '/inc/core/class-theme-setup.php';
		require_once AQUALUXE_THEME_DIR . '/inc/core/class-asset-loader.php';
		require_once AQUALUXE_THEME_DIR . '/inc/core/class-custom-post-types.php';
		require_once AQUALUXE_THEME_DIR . '/inc/core/class-customizer.php';

        // Load modules.
        $this->load_modules();
	}

	/**
	 * Add theme hooks.
	 */
	private function add_hooks() {
		add_action( 'after_setup_theme', array( $this, 'load_textdomain' ) );
		add_action( 'after_setup_theme', array( 'AquaLuxe_Theme_Setup', 'init' ) );
		add_action( 'wp_enqueue_scripts', array( 'AquaLuxe_Asset_Loader', 'enqueue_assets' ) );
		add_action( 'init', array( 'AquaLuxe_Custom_Post_Types', 'register' ) );
		add_action( 'customize_register', array( 'AquaLuxe_Customizer', 'register' ) );
	}

    /**
     * Load all modules from the /modules directory.
     */
    private function load_modules() {
        $modules_path = AQUALUXE_THEME_DIR . '/modules';
        if ( ! is_dir( $modules_path ) ) {
            return;
        }

        $modules = array_diff( scandir( $modules_path ), array( '..', '.' ) );

        foreach ( $modules as $module ) {
            $module_path = $modules_path . '/' . $module;
            if ( is_dir( $module_path ) ) {
                $module_file = $module_path . '/class-' . str_replace( '_', '-', $module ) . '.php';
                if ( file_exists( $module_file ) ) {
                    require_once $module_file;
                    $class_name = 'AquaLuxe_' . str_replace( '-', '_', ucwords( $module, '-' ) );
                    if ( class_exists( $class_name ) && method_exists( $class_name, 'init' ) ) {
                        add_action( 'after_setup_theme', array( $class_name, 'init' ) );
                    }
                }
            }
        }
    }

	/**
	 * Load theme textdomain.
	 */
	public function load_textdomain() {
		load_theme_textdomain( 'aqualuxe', AQUALUXE_THEME_DIR . '/languages' );
	}
}
