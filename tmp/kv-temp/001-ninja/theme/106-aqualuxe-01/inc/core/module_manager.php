<?php
/**
 * Module Manager - registers & boots feature modules.
 *
 * @package AquaLuxe\Core
 */

declare( strict_types=1 );

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) { exit; }

interface Module_Interface {
	public function id(): string;
	public function enabled(): bool;
	public function register(): void; // Register hooks (no side effects like DB writes).
	public function boot(): void;     // Execute runtime logic after all modules registered.
}

abstract class Base_Module implements Module_Interface {
	public function enabled(): bool { return true; }
	protected function is_woocommerce_active(): bool { return class_exists( 'WooCommerce' ); }
	protected function register_post_type( string $post_type, array $args ): void { register_post_type( $post_type, $args ); }
	protected function register_taxonomy( string $taxonomy, array $object_type, array $args ): void { register_taxonomy( $taxonomy, $object_type, $args ); }
}

final class Module_Manager {
	private static ?Module_Manager $instance = null;
	/** @var array<string, Module_Interface> */
	private array $modules = [];

	private function __construct() {}

	public static function instance(): Module_Manager {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function register_core(): void {
		// Core-provided modules could be added here later.
	}

	public function discover_and_register(): void {
		$dirs = glob( AQUALUXE_PATH . 'modules/*', GLOB_ONLYDIR ) ?: [];
		foreach ( $dirs as $dir ) {
			$module_file = $dir . '/module.php';
			if ( file_exists( $module_file ) ) {
				require_once $module_file;
				$basename = basename( $dir );
				$class    = '\\AquaLuxe\\Modules\\' . str_replace( ' ', '_', ucwords( str_replace( [ '-', '_' ], ' ', $basename ) ) ) . '\\Module';
				if ( class_exists( $class ) ) {
					$instance = new $class();
					if ( $instance instanceof Module_Interface && $instance->enabled() ) {
						$this->modules[ $instance->id() ] = $instance;
						$instance->register();
					}
				}
			}
		}
	}

	public function boot(): void {
		foreach ( $this->modules as $module ) {
			$module->boot();
		}
	}

	/** For tests / debugging. */
	public function all(): array { return $this->modules; }
}
