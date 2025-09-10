<?php
namespace AquaLuxe\Core;

/**
 * Module manager — allows enabling/disabling feature modules.
 */
final class Modules {
	private static array $defaults = [
		'dark_mode'    => true,
		'multilingual' => true,
		'woocommerce'  => true,
		'importer'     => true,
	];

	public static function init(): void {
		\add_action( 'after_setup_theme', [ __CLASS__, 'load_modules' ], 20 );
	}

	public static function is_enabled( string $module ): bool {
		$mods = self::get_modules();
		return $mods[ $module ] ?? false;
	}

	public static function load_modules(): void {
		$base = AQUALUXE_PATH . 'modules/';
		foreach ( self::get_modules() as $module => $enabled ) {
			if ( ! $enabled ) {
				continue;
			}
			$file = $base . $module . '/module.php';
			if ( file_exists( $file ) ) {
				require_once $file;
				$ns  = 'AquaLuxe\\Module\\' . str_replace( ' ', '', ucwords( str_replace( '_', ' ', $module ) ) );
				$cls = $ns . '\\Module';
				if ( class_exists( $cls ) && method_exists( $cls, 'init' ) ) {
					$cls::init();
				}
			}
		}
	}

	public static function get_modules(): array {
		$saved = (array) \get_option( 'aqualuxe_modules', [] );
		$mods  = array_merge( self::$defaults, $saved );
		/** Allow child themes/plugins to filter enabled modules. */
		return (array) \apply_filters( 'aqualuxe_modules', $mods );
	}
}
