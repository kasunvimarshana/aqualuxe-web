<?php
/** Simple Service Container (extensible) */

declare( strict_types=1 );

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Service_Container {
	private static ?Service_Container $instance = null;
	/** @var array<string, callable|object> */
	private array $bindings = [];
	/** @var array<string, mixed> */
	private array $resolved = [];

	public static function instance(): Service_Container {
		return self::$instance ??= new self();
	}

	public function set( string $id, $concrete ): void { $this->bindings[ $id ] = $concrete; }

	public function get( string $id ) {
		if ( isset( $this->resolved[ $id ] ) ) { return $this->resolved[ $id ]; }
		if ( ! isset( $this->bindings[ $id ] ) ) {
			throw new \RuntimeException( 'Service not found: ' . $id );
		}
		$concrete = $this->bindings[ $id ];
		$object   = is_callable( $concrete ) ? $concrete( $this ) : $concrete;
		return $this->resolved[ $id ] = $object;
	}
}
