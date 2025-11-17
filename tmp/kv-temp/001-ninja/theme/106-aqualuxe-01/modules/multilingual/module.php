<?php
/** Multilingual stub (hooks for translation readiness) */

declare( strict_types=1 );

namespace AquaLuxe\Modules\Multilingual;

use AquaLuxe\Core\Base_Module;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Module extends Base_Module {
	public function id(): string { return 'multilingual'; }
	public function register(): void {
		// Could integrate with Polylang / WPML detection for extended features.
		add_filter( 'locale', [ $this, 'maybe_switch_locale' ] );
	}
	public function boot(): void {}
	public function maybe_switch_locale( $locale ) {
		// Placeholder: allow ?lang=xx quick switching for demo only.
		if ( isset( $_GET['lang'] ) && preg_match( '/^[a-zA-Z_\-]+$/', (string) $_GET['lang'] ) ) { // phpcs:ignore
			return sanitize_text_field( (string) $_GET['lang'] );
		}
		return $locale;
	}
}
