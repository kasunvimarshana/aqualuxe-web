<?php
namespace AquaLuxe\Module\Multilingual;

final class Module {
	public static function init(): void {
		// Minimal multilingual readiness: load text domain and expose lang attr.
		\add_filter( 'language_attributes', [ __CLASS__, 'lang' ] );
	}

	public static function lang( string $output ): string {
		// Ensure lang attribute is present; WP sets it, but we keep a hook for custom logic.
		return $output;
	}
}
