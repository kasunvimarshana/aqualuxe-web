<?php
/**
 * AquaLuxe Cache Service
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
 * Class Cache_Service
 *
 * Handles caching operations with WordPress transients
 */
class Cache_Service {

	/**
	 * Cache prefix
	 *
	 * @var string
	 */
	protected $prefix;

	/**
	 * Default cache TTL (Time To Live)
	 *
	 * @var int
	 */
	protected $default_ttl;

	/**
	 * Constructor
	 *
	 * @param string $prefix Cache prefix
	 * @param int $default_ttl Default TTL in seconds
	 */
	public function __construct( string $prefix = 'aqualuxe_', int $default_ttl = 3600 ) {
		$this->prefix = $prefix;
		$this->default_ttl = $default_ttl;
	}

	/**
	 * Get cached value
	 *
	 * @param string $key Cache key
	 * @param mixed $default Default value if not found
	 * @return mixed Cached value or default
	 */
	public function get( string $key, $default = null ) {
		$value = \get_transient( $this->prefix . $key );
		return false !== $value ? $value : $default;
	}

	/**
	 * Set cached value
	 *
	 * @param string $key Cache key
	 * @param mixed $value Value to cache
	 * @param int|null $ttl Time to live in seconds
	 * @return bool Success status
	 */
	public function set( string $key, $value, int $ttl = null ): bool {
		$ttl = $ttl ?? $this->default_ttl;
		return \set_transient( $this->prefix . $key, $value, $ttl );
	}

	/**
	 * Remove cached value
	 *
	 * @param string $key Cache key
	 * @return bool Success status
	 */
	public function forget( string $key ): bool {
		return \delete_transient( $this->prefix . $key );
	}

	/**
	 * Remember value in cache (get or set)
	 *
	 * @param string $key Cache key
	 * @param callable $callback Callback to generate value
	 * @param int|null $ttl Time to live in seconds
	 * @return mixed Cached or generated value
	 */
	public function remember( string $key, callable $callback, int $ttl = null ) {
		$value = $this->get( $key );

		if ( null === $value ) {
			$value = $callback();
			$this->set( $key, $value, $ttl );
		}

		return $value;
	}

	/**
	 * Check if key exists in cache
	 *
	 * @param string $key Cache key
	 * @return bool True if exists
	 */
	public function has( string $key ): bool {
		return false !== \get_transient( $this->prefix . $key );
	}

	/**
	 * Clear all cache entries with prefix
	 *
	 * @return void
	 */
	public function flush(): void {
		global $wpdb;

		// Delete all transients with our prefix
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
				'_transient_' . $this->prefix . '%',
				'_transient_timeout_' . $this->prefix . '%'
			)
		);

		// Clear object cache if available
		if ( \function_exists( 'wp_cache_flush' ) ) {
			\wp_cache_flush();
		}
	}

	/**
	 * Get cache statistics
	 *
	 * @return array Cache statistics
	 */
	public function stats(): array {
		global $wpdb;

		$transient_count = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->options} WHERE option_name LIKE %s",
				'_transient_' . $this->prefix . '%'
			)
		);

		return [
			'prefix' => $this->prefix,
			'transient_count' => (int) $transient_count,
			'default_ttl' => $this->default_ttl,
		];
	}

	/**
	 * Cache multiple values at once
	 *
	 * @param array $items Key-value pairs to cache
	 * @param int|null $ttl Time to live in seconds
	 * @return array Results for each key
	 */
	public function set_many( array $items, int $ttl = null ): array {
		$results = [];

		foreach ( $items as $key => $value ) {
			$results[ $key ] = $this->set( $key, $value, $ttl );
		}

		return $results;
	}

	/**
	 * Get multiple cached values
	 *
	 * @param array $keys Cache keys
	 * @param mixed $default Default value for missing keys
	 * @return array Key-value pairs
	 */
	public function get_many( array $keys, $default = null ): array {
		$results = [];

		foreach ( $keys as $key ) {
			$results[ $key ] = $this->get( $key, $default );
		}

		return $results;
	}

	/**
	 * Forget multiple cache entries
	 *
	 * @param array $keys Cache keys to remove
	 * @return array Results for each key
	 */
	public function forget_many( array $keys ): array {
		$results = [];

		foreach ( $keys as $key ) {
			$results[ $key ] = $this->forget( $key );
		}

		return $results;
	}
}
