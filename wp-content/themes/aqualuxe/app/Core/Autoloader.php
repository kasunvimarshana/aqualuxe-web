<?php
/**
 * Autoloader
 *
 * @package AquaLuxe
 */

namespace App\Core;

class Autoloader {
	public static function register() {
		spl_autoload_register( [ self::class, 'autoload' ] );
	}

	public static function autoload( $class ) {
		$prefix   = 'App\\';
		$base_dir = AQUALUXE_APP_DIR . '/';

		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}

		$relative_class = substr( $class, $len );
		$file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	}
}

Autoloader::register();
