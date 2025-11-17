<?php
/**
 * Theme Bootstrap Class
 *
 * The main bootstrap class that initializes the entire AquaLuxe theme architecture.
 * This class follows the Single Responsibility Principle and manages the theme initialization process.
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 2.0.0
 * @author Kasun Vimarshana <kasunv.com@gmail.com>
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Autoloader;
use AquaLuxe\Core\Interfaces\Singleton_Interface;
use AquaLuxe\Core\Traits\Singleton_Trait;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bootstrap Class
 *
 * Singleton class responsible for initializing the entire theme architecture.
 * This class orchestrates the loading of all core components and modules.
 *
 * @since 2.0.0
 * @implements Singleton_Interface
 */
final class Bootstrap implements Singleton_Interface {

	use Singleton_Trait;

	/**
	 * The autoloader instance.
	 *
	 * @since 2.0.0
	 * @var Autoloader
	 */
	private Autoloader $autoloader;

	/**
	 * Theme configuration.
	 *
	 * @since 2.0.0
	 * @var array
	 */
	private array $config;

	/**
	 * Loaded modules registry.
	 *
	 * @since 2.0.0
	 * @var array<string, object>
	 */
	private array $modules = [];

	/**
	 * Core components registry.
	 *
	 * @since 2.0.0
	 * @var array<string, object>
	 */
	private array $core_components = [];

	/**
	 * Theme initialization status.
	 *
	 * @since 2.0.0
	 * @var bool
	 */
	private bool $initialized = false;

	/**
	 * Constructor - Initialize the theme bootstrap process.
	 *
	 * Private constructor to enforce singleton pattern.
	 * Initializes autoloader and begins the theme setup process.
	 *
	 * @since 2.0.0
	 */
	private function __construct() {
		$this->setup_autoloader();
		$this->load_configuration();
		$this->init_theme();
	}

	/**
	 * Setup the PSR-4 autoloader.
	 *
	 * Configures and registers the autoloader for the theme's namespace structure.
	 * This enables automatic loading of classes following PSR-4 conventions.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function setup_autoloader(): void {
		// Initialize autoloader
		$this->autoloader = new Autoloader();

		// Register theme namespace
		$this->autoloader->add_namespace( 'AquaLuxe\\', AQUALUXE_INC_DIR );

		// Register modules namespace
		$this->autoloader->add_namespace( 'AquaLuxe\\Modules\\', AQUALUXE_MODULES_DIR );

		// Register the autoloader
		$this->autoloader->register();
	}

	/**
	 * Load theme configuration.
	 *
	 * Loads and merges theme configuration from multiple sources:
	 * - Default configuration from constants
	 * - User-defined configuration from options
	 * - Filter-modified configuration
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function load_configuration(): void {
		// Start with default configuration
		$this->config = AQUALUXE_CONFIG;

		// Allow configuration to be modified via filters
		$this->config = apply_filters( 'aqualuxe_theme_config', $this->config );

		// Merge with stored options
		$stored_config = get_option( 'aqualuxe_theme_config', [] );
		$this->config = wp_parse_args( $stored_config, $this->config );
	}

	/**
	 * Initialize the theme.
	 *
	 * Main theme initialization method that orchestrates the loading of:
	 * - Core components
	 * - Modules
	 * - WordPress hooks
	 *
	 * @since 2.0.0
	 * @return void
	 */
	private function init_theme(): void {
		if ( $this->initialized ) {
			return;
		}

		// Hook into WordPress initialization
		add_action( 'after_setup_theme', [ $this, 'setup_theme_support' ], 5 );
		add_action( 'init', [ $this, 'load_core_components' ], 5 );
		add_action( 'init', [ $this, 'load_modules' ], 10 );
		add_action( 'wp_loaded', [ $this, 'theme_loaded' ], 10 );

		// Set initialization flag
		$this->initialized = true;

		// Fire action to allow extensions
		do_action( 'aqualuxe_theme_initialized', $this );
	}

	/**
	 * Setup WordPress theme support features.
	 *
	 * Configures WordPress theme support features such as:
	 * - Post thumbnails
	 * - HTML5 support
	 * - Custom logos
	 * - Navigation menus
	 * - Editor styles
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function setup_theme_support(): void {
		// Load text domain for translations
		load_theme_textdomain( AQUALUXE_TEXT_DOMAIN, AQUALUXE_LANGUAGES_DIR );

		// Add default posts and comments RSS feed links to head
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails
		add_theme_support( 'post-thumbnails' );

		// Switch default core markup to output valid HTML5
		add_theme_support( 'html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		] );

		// Add support for custom logo
		add_theme_support( 'custom-logo', [
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		] );

		// Add theme support for selective refresh for widgets
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for Block Editor features
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );

		// Add editor styles
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/dist/css/editor-style.css' );

		// Register navigation menus
		register_nav_menus( [
			'primary'   => esc_html__( 'Primary Menu', AQUALUXE_TEXT_DOMAIN ),
			'footer'    => esc_html__( 'Footer Menu', AQUALUXE_TEXT_DOMAIN ),
			'mobile'    => esc_html__( 'Mobile Menu', AQUALUXE_TEXT_DOMAIN ),
			'utility'   => esc_html__( 'Utility Menu', AQUALUXE_TEXT_DOMAIN ),
		] );

		// WooCommerce support (if enabled and available)
		if ( $this->is_module_enabled( 'woocommerce' ) && class_exists( 'WooCommerce' ) ) {
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
		}

		// Fire action for additional theme setup
		do_action( 'aqualuxe_after_setup_theme', $this );
	}

	/**
	 * Load core theme components.
	 *
	 * Initializes all core theme components in the correct order:
	 * - Assets manager
	 * - Template engine
	 * - Customizer
	 * - Security
	 * - Performance
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function load_core_components(): void {
		$core_components = [
			'assets'      => 'AquaLuxe\\Core\\Assets',
			'template'    => 'AquaLuxe\\Core\\Template_Engine',
			'customizer'  => 'AquaLuxe\\Core\\Customizer',
			'security'    => 'AquaLuxe\\Core\\Security',
			'performance' => 'AquaLuxe\\Core\\Performance',
		];

		foreach ( $core_components as $component_key => $component_class ) {
			if ( $this->is_core_component_enabled( $component_key ) ) {
				try {
					if ( class_exists( $component_class ) ) {
						// Use singleton instance method for core components
						$this->core_components[ $component_key ] = call_user_func( [ $component_class, 'instance' ] );
					}
				} catch ( \Exception $e ) {
					// Log error in development mode
					if ( AQUALUXE_DEV_MODE ) {
						error_log( "AquaLuxe: Failed to load core component {$component_key}: " . $e->getMessage() );
					}
				}
			}
		}

		// Fire action after core components are loaded
		do_action( 'aqualuxe_core_components_loaded', $this->core_components, $this );
	}

	/**
	 * Load theme modules.
	 *
	 * Dynamically loads all enabled theme modules based on configuration.
	 * Modules are loaded in dependency order to ensure proper initialization.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function load_modules(): void {
		$enabled_modules = $this->get_enabled_modules();

		// Sort modules by priority/dependencies
		$enabled_modules = $this->sort_modules_by_dependencies( $enabled_modules );

		foreach ( $enabled_modules as $module_key ) {
			$this->load_module( $module_key );
		}

		// Fire action after all modules are loaded
		do_action( 'aqualuxe_modules_loaded', $this->modules, $this );
	}

	/**
	 * Load a single module.
	 *
	 * @since 2.0.0
	 * @param string $module_key The module key/identifier.
	 * @return bool True on success, false on failure.
	 */
	private function load_module( string $module_key ): bool {
		$module_class = $this->get_module_class_name( $module_key );
		
		try {
			if ( class_exists( $module_class ) ) {
				$this->modules[ $module_key ] = new $module_class( $this );
				return true;
			}
		} catch ( \Exception $e ) {
			// Log error in development mode
			if ( AQUALUXE_DEV_MODE ) {
				error_log( "AquaLuxe: Failed to load module {$module_key}: " . $e->getMessage() );
			}
		}

		return false;
	}

	/**
	 * Theme loaded callback.
	 *
	 * Called when WordPress has finished loading all plugins and theme components.
	 * Performs final initialization tasks.
	 *
	 * @since 2.0.0
	 * @return void
	 */
	public function theme_loaded(): void {
		// Fire action indicating theme is fully loaded
		do_action( 'aqualuxe_theme_loaded', $this );
	}

	/**
	 * Get enabled modules from configuration.
	 *
	 * @since 2.0.0
	 * @return array<string> List of enabled module keys.
	 */
	private function get_enabled_modules(): array {
		$modules_config = $this->config['modules'] ?? [];
		return array_keys( array_filter( $modules_config ) );
	}

	/**
	 * Sort modules by dependencies.
	 *
	 * @since 2.0.0
	 * @param array $modules List of module keys.
	 * @return array<string> Sorted module keys.
	 */
	private function sort_modules_by_dependencies( array $modules ): array {
		// Define dependency order (modules that should load first)
		$priority_order = [
			'assets',
			'customizer',
			'template_engine',
			'security',
			'performance',
			'seo',
			'schema',
			'accessibility',
			'woocommerce',
			'custom_post_types',
			'demo_importer',
			// Business modules load later
			'multivendor',
			'multicurrency',
			'classified_ads',
			'bookings',
			'events',
			'subscriptions',
			'franchise',
			'wholesale',
			'auctions',
			'affiliate',
			'professional_services',
		];

		// Sort modules based on priority order
		$sorted_modules = [];
		
		// Add modules in priority order
		foreach ( $priority_order as $priority_module ) {
			if ( in_array( $priority_module, $modules, true ) ) {
				$sorted_modules[] = $priority_module;
			}
		}

		// Add any remaining modules not in priority list
		foreach ( $modules as $module ) {
			if ( ! in_array( $module, $sorted_modules, true ) ) {
				$sorted_modules[] = $module;
			}
		}

		return $sorted_modules;
	}

	/**
	 * Get module class name from module key.
	 *
	 * @since 2.0.0
	 * @param string $module_key The module key.
	 * @return string The fully qualified class name.
	 */
	private function get_module_class_name( string $module_key ): string {
		// Convert snake_case to PascalCase
		$class_name = str_replace( ' ', '', ucwords( str_replace( '_', ' ', $module_key ) ) );
		return "AquaLuxe\\Modules\\{$class_name}\\Module";
	}

	/**
	 * Check if a module is enabled.
	 *
	 * @since 2.0.0
	 * @param string $module_key The module key.
	 * @return bool True if enabled, false otherwise.
	 */
	public function is_module_enabled( string $module_key ): bool {
		return ! empty( $this->config['modules'][ $module_key ] );
	}

	/**
	 * Check if a core component is enabled.
	 *
	 * @since 2.0.0
	 * @param string $component_key The component key.
	 * @return bool True if enabled, false otherwise.
	 */
	private function is_core_component_enabled( string $component_key ): bool {
		return ! empty( $this->config['modules'][ $component_key ] );
	}

	/**
	 * Get a loaded module instance.
	 *
	 * @since 2.0.0
	 * @param string $module_key The module key.
	 * @return object|null The module instance or null if not loaded.
	 */
	public function get_module( string $module_key ): ?object {
		return $this->modules[ $module_key ] ?? null;
	}

	/**
	 * Get a loaded core component instance.
	 *
	 * @since 2.0.0
	 * @param string $component_key The component key.
	 * @return object|null The component instance or null if not loaded.
	 */
	public function get_core_component( string $component_key ): ?object {
		return $this->core_components[ $component_key ] ?? null;
	}

	/**
	 * Get all loaded modules.
	 *
	 * @since 2.0.0
	 * @return array<string, object> Array of loaded modules.
	 */
	public function get_modules(): array {
		return $this->modules;
	}

	/**
	 * Get all loaded core components.
	 *
	 * @since 2.0.0
	 * @return array<string, object> Array of loaded core components.
	 */
	public function get_core_components(): array {
		return $this->core_components;
	}

	/**
	 * Get theme configuration.
	 *
	 * @since 2.0.0
	 * @param string|null $key Optional. Configuration key to retrieve.
	 * @return mixed The configuration value or entire config if no key specified.
	 */
	public function get_config( string $key = null ) {
		if ( null === $key ) {
			return $this->config;
		}

		return $this->config[ $key ] ?? null;
	}

	/**
	 * Update theme configuration.
	 *
	 * @since 2.0.0
	 * @param string $key The configuration key.
	 * @param mixed  $value The configuration value.
	 * @return void
	 */
	public function set_config( string $key, $value ): void {
		$this->config[ $key ] = $value;

		// Update stored configuration
		update_option( 'aqualuxe_theme_config', $this->config );
	}

	/**
	 * Get the autoloader instance.
	 *
	 * @since 2.0.0
	 * @return Autoloader The autoloader instance.
	 */
	public function get_autoloader(): Autoloader {
		return $this->autoloader;
	}
}
