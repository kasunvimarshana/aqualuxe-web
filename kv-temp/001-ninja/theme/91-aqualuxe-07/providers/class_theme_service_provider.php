<?php
/**
 * AquaLuxe Theme Service Provider
 *
 * @package AquaLuxe
 * @since   1.2.0
 */

namespace AquaLuxe\Providers;

use AquaLuxe\Core\Abstract_Service_Provider;
use AquaLuxe\Services\Theme_Service;
use AquaLuxe\Services\Customizer_Service;
use AquaLuxe\Services\Post_Type_Service;
use AquaLuxe\Services\Taxonomy_Service;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Theme_Service_Provider
 *
 * Registers theme-related services following Single Responsibility Principle
 */
class Theme_Service_Provider extends Abstract_Service_Provider {

	/**
	 * Services provided by this provider
	 *
	 * @var array
	 */
	protected $provides = [
		'theme',
		'customizer',
		'post_types',
		'taxonomies',
	];

	/**
	 * Register theme services
	 *
	 * @return void
	 */
	protected function register_services(): void {
		// Theme service
		$this->singleton( 'theme', Theme_Service::class );

		// Customizer service
		$this->singleton( 'customizer', Customizer_Service::class );

		// Post type service
		$this->singleton( 'post_types', Post_Type_Service::class );

		// Taxonomy service
		$this->singleton( 'taxonomies', Taxonomy_Service::class );
	}

	/**
	 * Boot theme services
	 *
	 * @return void
	 */
	protected function boot_services(): void {
		// Initialize theme support
		add_action( 'after_setup_theme', [ $this, 'setup_theme_support' ] );
		
		// Initialize customizer
		add_action( 'customize_register', function( $wp_customize ) {
			$this->resolve( 'customizer' )->register( $wp_customize );
		} );

		// Initialize post types and taxonomies
		add_action( 'init', [ $this, 'register_post_types_and_taxonomies' ] );

		// Setup theme features
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_theme_assets' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
	}

	/**
	 * Setup theme support features
	 *
	 * @return void
	 */
	public function setup_theme_support(): void {
		$theme_service = $this->resolve( 'theme' );
		$theme_service->setup_theme_support();
	}

	/**
	 * Register custom post types and taxonomies
	 *
	 * @return void
	 */
	public function register_post_types_and_taxonomies(): void {
		$post_type_service = $this->resolve( 'post_types' );
		$taxonomy_service = $this->resolve( 'taxonomies' );

		$post_type_service->register_post_types();
		$taxonomy_service->register_taxonomies();
	}

	/**
	 * Enqueue theme assets
	 *
	 * @return void
	 */
	public function enqueue_theme_assets(): void {
		$theme_service = $this->resolve( 'theme' );
		$theme_service->enqueue_frontend_assets();
	}

	/**
	 * Enqueue admin assets
	 *
	 * @return void
	 */
	public function enqueue_admin_assets(): void {
		$theme_service = $this->resolve( 'theme' );
		$theme_service->enqueue_admin_assets();
	}
}
