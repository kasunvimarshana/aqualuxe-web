<?php
/**
 * AquaLuxe Application Core
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Contracts\Service_Provider_Interface;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Application
 *
 * Main application class implementing Facade Pattern for framework management
 */
class Application {

	/**
	 * Application instance (singleton)
	 *
	 * @var Application|null
	 */
	private static $instance = null;

	/**
	 * Container instance
	 *
	 * @var Container
	 */
	protected $container;

	/**
	 * Registered service providers
	 *
	 * @var array
	 */
	protected $providers = [];

	/**
	 * Booted service providers
	 *
	 * @var array
	 */
	protected $booted_providers = [];

	/**
	 * Application configuration
	 *
	 * @var array
	 */
	protected $config = [];

	/**
	 * Whether the application has been booted
	 *
	 * @var bool
	 */
	protected $booted = false;

	/**
	 * Application version
	 *
	 * @var string
	 */
	const VERSION = '1.2.0';

	/**
	 * Private constructor for singleton pattern
	 */
	private function __construct() {
		$this->container = Container::get_instance();
		$this->bind_core_services();
	}

	/**
	 * Get application instance (singleton)
	 *
	 * @return Application
	 */
	public static function get_instance(): Application {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Initialize the application
	 *
	 * @param array $config Application configuration
	 * @return Application
	 */
	public function initialize( array $config = [] ): Application {
		$this->config = array_merge( $this->get_default_config(), $config );
		
		// Register core providers
		$this->register_core_providers();
		
		return $this;
	}

	/**
	 * Boot the application
	 *
	 * @return void
	 */
	public function boot(): void {
		if ( $this->booted ) {
			return;
		}

		// Boot all registered providers
		foreach ( $this->providers as $provider ) {
			$this->boot_provider( $provider );
		}

		$this->booted = true;

		/**
		 * Application booted hook
		 *
		 * @since 1.2.0
		 */
		do_action( 'aqualuxe_application_booted', $this );
	}

	/**
	 * Register a service provider
	 *
	 * @param string|Service_Provider_Interface $provider Provider class or instance
	 * @return Service_Provider_Interface
	 */
	public function register( $provider ): Service_Provider_Interface {
		if ( is_string( $provider ) ) {
			$provider = new $provider();
		}

		if ( ! $provider instanceof Service_Provider_Interface ) {
			throw new \InvalidArgumentException( 'Provider must implement Service_Provider_Interface' );
		}

		$provider_class = get_class( $provider );

		// Avoid duplicate registration
		if ( isset( $this->providers[ $provider_class ] ) ) {
			return $this->providers[ $provider_class ];
		}

		// Register the provider
		$provider->register( $this->container );
		$this->providers[ $provider_class ] = $provider;

		// Boot immediately if application is already booted
		if ( $this->booted ) {
			$this->boot_provider( $provider );
		}

		return $provider;
	}

	/**
	 * Boot a service provider
	 *
	 * @param Service_Provider_Interface $provider
	 * @return void
	 */
	protected function boot_provider( Service_Provider_Interface $provider ): void {
		$provider_class = get_class( $provider );

		if ( isset( $this->booted_providers[ $provider_class ] ) ) {
			return;
		}

		$provider->boot( $this->container );
		$this->booted_providers[ $provider_class ] = true;
	}

	/**
	 * Get container instance
	 *
	 * @return Container
	 */
	public function container(): Container {
		return $this->container;
	}

	/**
	 * Resolve service from container
	 *
	 * @param string $abstract Service identifier
	 * @return mixed
	 */
	public function resolve( string $abstract ) {
		return $this->container->resolve( $abstract );
	}

	/**
	 * Get configuration value
	 *
	 * @param string $key Configuration key (dot notation supported)
	 * @param mixed $default Default value
	 * @return mixed
	 */
	public function config( string $key, $default = null ) {
		$keys = explode( '.', $key );
		$value = $this->config;

		foreach ( $keys as $k ) {
			if ( ! is_array( $value ) || ! isset( $value[ $k ] ) ) {
				return $default;
			}
			$value = $value[ $k ];
		}

		return $value;
	}

	/**
	 * Set configuration value
	 *
	 * @param string $key Configuration key
	 * @param mixed $value Configuration value
	 * @return void
	 */
	public function set_config( string $key, $value ): void {
		$keys = explode( '.', $key );
		$config = &$this->config;

		foreach ( $keys as $k ) {
			if ( ! is_array( $config ) ) {
				$config = [];
			}
			$config = &$config[ $k ];
		}

		$config = $value;
	}

	/**
	 * Get application version
	 *
	 * @return string
	 */
	public function version(): string {
		return self::VERSION;
	}

	/**
	 * Check if application is running in debug mode
	 *
	 * @return bool
	 */
	public function is_debug(): bool {
		return $this->config( 'debug', defined( 'WP_DEBUG' ) && WP_DEBUG );
	}

	/**
	 * Get default configuration
	 *
	 * @return array
	 */
	protected function get_default_config(): array {
		return [
			'debug' => defined( 'WP_DEBUG' ) && WP_DEBUG,
			'locale' => get_locale(),
			'timezone' => wp_timezone_string(),
			'theme_dir' => get_template_directory(),
			'theme_url' => get_template_directory_uri(),
			'child_theme_dir' => get_stylesheet_directory(),
			'child_theme_url' => get_stylesheet_directory_uri(),
			'multisite' => is_multisite(),
			'cache' => [
				'enabled' => ! $this->is_debug(),
				'prefix' => 'aqualuxe_',
				'ttl' => 3600,
			],
			'assets' => [
				'version' => self::VERSION,
				'minify' => ! $this->is_debug(),
			],
		];
	}

	/**
	 * Bind core services to container
	 *
	 * @return void
	 */
	protected function bind_core_services(): void {
		// Bind application instance
		$this->container->singleton( 'app', function() {
			return $this;
		} );

		// Bind container instance
		$this->container->singleton( 'container', function() {
			return $this->container;
		} );
	}

	/**
	 * Register core service providers
	 *
	 * @return void
	 */
	protected function register_core_providers(): void {
		$core_providers = [
			'AquaLuxe\\Providers\\Asset_Service_Provider',
			'AquaLuxe\\Providers\\Theme_Service_Provider',
			'AquaLuxe\\Providers\\Hook_Service_Provider',
			'AquaLuxe\\Providers\\Cache_Service_Provider',
			'AquaLuxe\\Providers\\Config_Service_Provider',
		];

		foreach ( $core_providers as $provider ) {
			if ( class_exists( $provider ) ) {
				$this->register( $provider );
			}
		}
	}

	/**
	 * Prevent cloning
	 */
	private function __clone() {}

	/**
	 * Prevent unserialization
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize singleton' );
	}
}
