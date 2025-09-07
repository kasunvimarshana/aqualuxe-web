<?php
/**
 * The main theme class for AquaLuxe.
 *
 * This class encapsulates the core functionality of the theme,
 * following the Singleton pattern to ensure a single instance.
 *
 * @package AquaLuxe
 * @since   1.1.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! \defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Final AquaLuxe_Theme Class.
 */
final class AquaLuxe_Theme {

	/**
	 * The single instance of the class.
	 *
	 * @var AquaLuxe_Theme|null
	 */
	private static ?AquaLuxe_Theme $instance = null;

	/**
	 * Main Theme Instance.
	 *
	 * Ensures only one instance of the theme class is loaded or can be loaded.
	 *
	 * @static
	 * @return AquaLuxe_Theme - Main instance.
	 */
	public static function instance(): AquaLuxe_Theme {
		if ( \is_null( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->setup_constants();
			self::$instance->autoload();
			self::$instance->init();
		}
		return self::$instance;
	}

	/**
	 * Cloning is forbidden.
	 */
	public function __clone() {
		\_doing_it_wrong( __FUNCTION__, \esc_html__( 'Cloning is forbidden.', 'aqualuxe' ), '1.1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 */
	public function __wakeup() {
		\_doing_it_wrong( __FUNCTION__, \esc_html__( 'Unserializing instances of this class is forbidden.', 'aqualuxe' ), '1.1.0' );
	}

	/**
	 * Private constructor to prevent direct instantiation.
	 */
	private function __construct() {}

	/**
	 * Setup theme constants.
	 */
	private function setup_constants(): void {
		\define( 'AQUALUXE_VERSION', '1.1.0' );
		\define( 'AQUALUXE_THEME_DIR', \trailingslashit( \get_template_directory() ) );
		\define( 'AQUALUXE_THEME_URI', \trailingslashit( \esc_url( \get_template_directory_uri() ) ) );
		\define( 'AQUALUXE_CORE_DIR', AQUALUXE_THEME_DIR . 'core/' );
	}

	/**
	 * Autoload classes.
	 */
	private function autoload(): void {
		require_once AQUALUXE_CORE_DIR . 'class_autoloader.php';
		$autoloader = new \AquaLuxe\Core\Autoloader();
		$autoloader->set_base_dir( AQUALUXE_THEME_DIR );
		$autoloader->register();
	}

	/**
	 * Initialize the theme.
	 */
	private function init(): void {
		\add_action( 'after_switch_theme', [ $this, 'theme_activation' ] );
		new \AquaLuxe\Core\Core_Loader();
	}

	/**
	 * Theme activation hooks.
	 */
	public function theme_activation(): void {
		\AquaLuxe\Core\Wholesale::add_roles();
		\flush_rewrite_rules();
	}
}
