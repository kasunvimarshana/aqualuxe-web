<?php
namespace AquaLuxe\Module\DarkMode;

use AquaLuxe\Core\Modules;

final class Module {
	public static function init(): void {
		// Nothing extra for now; JS handles toggle. Provide body class fallback if LS not set.
		\add_filter( 'body_class', [ __CLASS__, 'body_class' ] );
	}

	public static function body_class( array $classes ): array {
		$classes[] = 'supports-dark-mode';
		return $classes;
	}
}
