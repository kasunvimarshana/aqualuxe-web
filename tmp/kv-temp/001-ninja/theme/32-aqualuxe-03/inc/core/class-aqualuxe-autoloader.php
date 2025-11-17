<?php
/**
 * AquaLuxe Autoloader
 *
 * @package AquaLuxe
 * @since 1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe Autoloader Class
 *
 * Implements PSR-4 autoloading standard for better code organization and modularity.
 * This allows us to use namespaces and autoload classes without manually requiring files.
 *
 * @since 1.1.0
 */
class AquaLuxe_Autoloader {

	/**
	 * Namespace prefix for all theme classes.
	 *
	 * @var string
	 */
	private $namespace_prefix = 'AquaLuxe\\';

	/**
	 * Base directory for the namespace prefix.
	 *
	 * @var string
	 */
	private $base_dir;

	/**
	 * Class to file mapping for special cases.
	 *
	 * @var array
	 */
	private $class_map = [];

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->base_dir = AQUALUXE_DIR . '/inc/';
		$this->init_class_map();
		spl_autoload_register( array( $this, 'autoload' ) );
	}

	/**
	 * Initialize class map for special cases.
	 *
	 * @return void
	 */
	private function init_class_map() {
		// Map specific classes to their file paths when they don't follow the standard pattern.
		$this->class_map = [
			'AquaLuxe\\Core\\Theme' => $this->base_dir . 'core/class-aqualuxe-theme.php',
			'AquaLuxe\\Core\\Setup' => $this->base_dir . 'core/class-setup.php',
			'AquaLuxe\\Core\\Assets' => $this->base_dir . 'core/class-assets.php',
			'AquaLuxe\\Core\\Template' => $this->base_dir . 'core/class-template.php',
			'AquaLuxe\\Core\\Performance' => $this->base_dir . 'core/class-performance.php',
			'AquaLuxe\\Core\\SEO' => $this->base_dir . 'core/class-seo.php',
			'AquaLuxe\\Core\\Accessibility' => $this->base_dir . 'core/class-accessibility.php',
			'AquaLuxe\\Customizer\\Customizer' => $this->base_dir . 'customizer/class-customizer.php',
			'AquaLuxe\\Widgets\\Widgets_Manager' => $this->base_dir . 'widgets/class-widgets-manager.php',
			'AquaLuxe\\Demo\\Importer' => $this->base_dir . 'demo/class-importer.php',
			'AquaLuxe\\WooCommerce\\WooCommerce' => $this->base_dir . 'woocommerce/class-woocommerce.php',
		];
	}

	/**
	 * Autoload function for registering with spl_autoload_register.
	 *
	 * @param string $class The fully-qualified class name.
	 * @return void
	 */
	public function autoload( $class ) {
		// Check if class is in the class map.
		if ( isset( $this->class_map[ $class ] ) ) {
			if ( file_exists( $this->class_map[ $class ] ) ) {
				require_once $this->class_map[ $class ];
				return;
			}
		}

		// Does the class use the namespace prefix?
		$len = strlen( $this->namespace_prefix );
		if ( strncmp( $this->namespace_prefix, $class, $len ) !== 0 ) {
			// No, move to the next registered autoloader.
			return;
		}

		// Get the relative class name.
		$relative_class = substr( $class, $len );

		// Replace namespace separators with directory separators.
		$path_parts = explode('\\', $relative_class);
		
		// Convert class name to file name (e.g., MyClass -> class-my-class.php)
		$class_name = array_pop($path_parts);
		$file_name = 'class-' . strtolower(str_replace('_', '-', $class_name)) . '.php';
		
		// Build the file path
		$path = strtolower(implode('/', $path_parts));
		$file = $this->base_dir . $path . ($path ? '/' : '') . $file_name;

		// If the file exists, require it.
		if ( file_exists( $file ) ) {
			require_once $file;
			return;
		}

		// Try alternative path without 'class-' prefix
		$alt_file = $this->base_dir . $path . ($path ? '/' : '') . strtolower(str_replace('_', '-', $class_name)) . '.php';
		if ( file_exists( $alt_file ) ) {
			require_once $alt_file;
			return;
		}
	}

	/**
	 * Register the autoloader.
	 *
	 * @return void
	 */
	public static function register() {
		new self();
	}
}

// Register the autoloader.
AquaLuxe_Autoloader::register();