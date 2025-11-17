<?php
/**
 * AquaLuxe Theme Core Class
 *
 * @package AquaLuxe
 * @since 1.2.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Theme Class
 *
 * This class serves as the core of the theme, implementing a service container
 * for dependency injection and managing the theme's components.
 *
 * @since 1.2.0
 */
class Theme {

	/**
	 * The single instance of this class.
	 *
	 * @var Theme
	 */
	private static $instance = null;

	/**
	 * Container for services.
	 *
	 * @var array
	 */
	private $services = array();

	/**
	 * Container for service instances.
	 *
	 * @var array
	 */
	private $instances = array();

	/**
	 * Get the single instance of this class.
	 *
	 * @return Theme
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		// Private constructor to enforce singleton pattern.
	}

	/**
	 * Initialize the theme.
	 *
	 * @return void
	 */
	public function initialize() {
		// Constants are now defined in functions.php to avoid duplication
		$this->register_default_services();
		$this->load_services();
		$this->register_hooks();
	}

	/**
	 * Register default services.
	 *
	 * @return void
	 */
	private function register_default_services() {
		// Core services.
		$this->register_service( 'setup', '\\AquaLuxe\\Core\\Setup' );
		$this->register_service( 'assets', '\\AquaLuxe\\Core\\Assets' );
		$this->register_service( 'customizer', '\\AquaLuxe\\Customizer\\Customizer' );
		$this->register_service( 'widgets', '\\AquaLuxe\\Widgets\\Widgets_Manager' );
		$this->register_service( 'template', '\\AquaLuxe\\Core\\Template' );
		
		// Performance services.
		$this->register_service( 'performance', '\\AquaLuxe\\Core\\Performance' );
		$this->register_service( 'critical_css', '\\AquaLuxe\\Core\\Critical_CSS' );
		$this->register_service( 'resource_hints', '\\AquaLuxe\\Core\\Resource_Hints' );
		$this->register_service( 'lazy_loading', '\\AquaLuxe\\Core\\Lazy_Loading' );
		$this->register_service( 'webp_support', '\\AquaLuxe\\Core\\WebP_Support' );
		$this->register_service( 'browser_caching', '\\AquaLuxe\\Core\\Browser_Caching' );
		$this->register_service( 'minification', '\\AquaLuxe\\Core\\Minification' );
		$this->register_service( 'script_loading', '\\AquaLuxe\\Core\\Script_Loading' );
		
		// Other core services.
		$this->register_service( 'seo', '\\AquaLuxe\\Core\\SEO' );
		$this->register_service( 'schema', '\\AquaLuxe\\Core\\Schema' );
		$this->register_service( 'sitemap', '\\AquaLuxe\\Core\\Sitemap' );
		$this->register_service( 'breadcrumbs', '\\AquaLuxe\\Core\\Breadcrumbs' );
		$this->register_service( 'accessibility', '\\AquaLuxe\\Core\\Accessibility' );
		
		// WooCommerce services (conditionally loaded).
		if ( $this->is_woocommerce_active() ) {
			$this->register_service( 'woocommerce', '\\AquaLuxe\\WooCommerce\\WooCommerce' );
			$this->register_service( 'wc_template', '\\AquaLuxe\\WooCommerce\\Template' );
			$this->register_service( 'wc_product', '\\AquaLuxe\\WooCommerce\\Product' );
			$this->register_service( 'wc_cart', '\\AquaLuxe\\WooCommerce\\Cart' );
			$this->register_service( 'wc_checkout', '\\AquaLuxe\\WooCommerce\\Checkout' );
			$this->register_service( 'wc_account', '\\AquaLuxe\\WooCommerce\\Account' );
			$this->register_service( 'wc_wishlist', '\\AquaLuxe\\WooCommerce\\Wishlist' );
			$this->register_service( 'wc_quickview', '\\AquaLuxe\\WooCommerce\\QuickView' );
			$this->register_service( 'wc_currency', '\\AquaLuxe\\WooCommerce\\MultiCurrency' );
			$this->register_service( 'wc_shipping', '\\AquaLuxe\\WooCommerce\\InternationalShipping' );
		}

		// Demo importer.
		$this->register_service( 'demo_importer', '\\AquaLuxe\\Demo\\Importer' );
	}

	/**
	 * Register a service.
	 *
	 * @param string $name Service name.
	 * @param string $class Service class.
	 * @param array  $dependencies Optional. Service dependencies.
	 * @return void
	 */
	public function register_service( $name, $class, $dependencies = array() ) {
		$this->services[ $name ] = array(
			'class'       => $class,
			'dependencies' => $dependencies,
		);
	}

	/**
	 * Get a service.
	 *
	 * @param string $name Service name.
	 * @return object|null Service instance or null if not found.
	 */
	public function get_service( $name ) {
		if ( ! isset( $this->instances[ $name ] ) ) {
			$this->load_service( $name );
		}
		return isset( $this->instances[ $name ] ) ? $this->instances[ $name ] : null;
	}

	/**
	 * Load a service.
	 *
	 * @param string $name Service name.
	 * @return void
	 */
	private function load_service( $name ) {
		if ( ! isset( $this->services[ $name ] ) ) {
			return;
		}

		$service = $this->services[ $name ];
		$dependencies = array();

		foreach ( $service['dependencies'] as $dependency ) {
			$dependencies[] = $this->get_service( $dependency );
		}

		$class = $service['class'];
		$this->instances[ $name ] = new $class( ...$dependencies );

		if ( method_exists( $this->instances[ $name ], 'initialize' ) ) {
			$this->instances[ $name ]->initialize();
		}
	}

	/**
	 * Load all registered services.
	 *
	 * @return void
	 */
	private function load_services() {
		foreach ( array_keys( $this->services ) as $name ) {
			$this->load_service( $name );
		}
	}

	/**
	 * Register hooks for all services.
	 *
	 * @return void
	 */
	private function register_hooks() {
		foreach ( $this->instances as $instance ) {
			if ( method_exists( $instance, 'register_hooks' ) ) {
				$instance->register_hooks();
			}
		}
	}

	/**
	 * Check if WooCommerce is active.
	 *
	 * @return bool
	 */
	public function is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}

	/**
	 * Prevent cloning.
	 *
	 * @return void
	 */
	private function __clone() {
		// Prevent cloning of the singleton.
	}

	/**
	 * Prevent unserializing.
	 *
	 * @return void
	 */
	public function __wakeup() {
		// Prevent unserializing of the singleton.
	}
}