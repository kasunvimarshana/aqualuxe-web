<?php
/**
 * AquaLuxe Cache Service Provider
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

namespace AquaLuxe\Providers;

use AquaLuxe\Core\Abstract_Service_Provider;
use AquaLuxe\Services\Cache_Service;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Cache_Service_Provider
 *
 * Manages caching functionality
 */
class Cache_Service_Provider extends Abstract_Service_Provider {

	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected $provides = [
		'cache',
	];

	/**
	 * Register cache services
	 *
	 * @return void
	 */
	protected function register_services(): void {
		$this->singleton( 'cache', Cache_Service::class );
	}

	/**
	 * Boot cache services
	 *
	 * @return void
	 */
	protected function boot_services(): void {
		// Setup cache hooks
		add_action( 'save_post', [ $this, 'clear_post_cache' ] );
		add_action( 'comment_post', [ $this, 'clear_comment_cache' ] );
		add_action( 'wp_update_comment_count', [ $this, 'clear_comment_cache' ] );
	}

	/**
	 * Clear post-related cache
	 *
	 * @param int $post_id Post ID
	 * @return void
	 */
	public function clear_post_cache( int $post_id ): void {
		$cache_service = $this->resolve( 'cache' );
		$cache_service->forget( 'post_' . $post_id );
		$cache_service->forget( 'post_meta_' . $post_id );
	}

	/**
	 * Clear comment-related cache
	 *
	 * @param int $comment_id Comment ID
	 * @return void
	 */
	public function clear_comment_cache( int $comment_id ): void {
		$cache_service = $this->resolve( 'cache' );
		$cache_service->forget( 'comments_count' );
		$cache_service->forget( 'recent_comments' );
	}
}
