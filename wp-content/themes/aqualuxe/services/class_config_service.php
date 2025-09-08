<?php
/**
 * AquaLuxe Configuration Service
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

namespace AquaLuxe\Services;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Config_Service
 *
 * Manages application configuration with support for environment-specific configs
 */
class Config_Service {

	/**
	 * Configuration data
	 *
	 * @var array
	 */
	protected $config = [];

	/**
	 * Configuration file paths
	 *
	 * @var array
	 */
	protected $config_paths = [];

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->setup_config_paths();
	}

	/**
	 * Load all configuration files
	 *
	 * @return void
	 */
	public function load_configurations(): void {
		foreach ( $this->config_paths as $key => $path ) {
			if ( file_exists( $path ) ) {
				$config_data = include $path;
				if ( is_array( $config_data ) ) {
					$this->config[ $key ] = $config_data;
				}
			}
		}

		// Apply filters for customization
		$this->config = apply_filters( 'aqualuxe_config', $this->config );
	}

	/**
	 * Get configuration value
	 *
	 * @param string $key Configuration key (dot notation supported)
	 * @param mixed $default Default value
	 * @return mixed
	 */
	public function get( string $key, $default = null ) {
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
	 * @param string $key Configuration key (dot notation supported)
	 * @param mixed $value Configuration value
	 * @return void
	 */
	public function set( string $key, $value ): void {
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
	 * Check if configuration key exists
	 *
	 * @param string $key Configuration key
	 * @return bool
	 */
	public function has( string $key ): bool {
		$keys = explode( '.', $key );
		$value = $this->config;

		foreach ( $keys as $k ) {
			if ( ! is_array( $value ) || ! isset( $value[ $k ] ) ) {
				return false;
			}
			$value = $value[ $k ];
		}

		return true;
	}

	/**
	 * Get all configuration data
	 *
	 * @return array
	 */
	public function all(): array {
		return $this->config;
	}

	/**
	 * Setup configuration file paths
	 *
	 * @return void
	 */
	protected function setup_config_paths(): void {
		$theme_dir = get_template_directory();
		$child_theme_dir = get_stylesheet_directory();

		$this->config_paths = [
			'app' => $theme_dir . '/config/app.php',
			'theme' => $theme_dir . '/config/theme.php',
			'assets' => $theme_dir . '/config/assets.php',
			'modules' => $theme_dir . '/config/modules.php',
			'multisite' => $theme_dir . '/config/multisite.php',
			'woocommerce' => $theme_dir . '/config/woocommerce.php',
		];

		// Child theme configurations override parent theme
		if ( $child_theme_dir !== $theme_dir ) {
			$child_configs = [
				'child_theme' => $child_theme_dir . '/config/theme.php',
				'child_assets' => $child_theme_dir . '/config/assets.php',
			];

			foreach ( $child_configs as $key => $path ) {
				if ( file_exists( $path ) ) {
					$this->config_paths[ $key ] = $path;
				}
			}
		}

		// Environment-specific configurations
		$environment = $this->get_environment();
		$env_config_path = $theme_dir . "/config/{$environment}.php";
		
		if ( file_exists( $env_config_path ) ) {
			$this->config_paths[ $environment ] = $env_config_path;
		}
	}

	/**
	 * Get current environment
	 *
	 * @return string
	 */
	protected function get_environment(): string {
		if ( defined( 'WP_ENVIRONMENT_TYPE' ) && constant( 'WP_ENVIRONMENT_TYPE' ) ) {
			return constant( 'WP_ENVIRONMENT_TYPE' );
		}

		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			return 'development';
		}

		return 'production';
	}
}
