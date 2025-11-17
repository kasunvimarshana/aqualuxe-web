<?php
/**
 * Application Core
 *
 * @package AquaLuxe
 */

namespace App\Core;

class Application {
	protected $providers = [];

	public function registerProviders( array $providers ) {
		foreach ( $providers as $provider ) {
			$this->providers[] = new $provider( $this );
		}
	}

	public function boot() {
		foreach ( $this->providers as $provider ) {
			if ( method_exists( $provider, 'register' ) ) {
				$provider->register();
			}
		}
		foreach ( $this->providers as $provider ) {
			if ( method_exists( $provider, 'boot' ) ) {
				$provider->boot();
			}
		}
	}
}
