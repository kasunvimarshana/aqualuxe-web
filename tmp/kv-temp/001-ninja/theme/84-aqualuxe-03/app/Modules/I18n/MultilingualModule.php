<?php
namespace Aqualuxe\Modules\I18n;

defined('ABSPATH') || exit;

final class MultilingualModule {
    public static function register(): void {
        \add_action( 'init', [ __CLASS__, 'bootstrap' ] );
    }

    public static function bootstrap(): void {
        // Integrate with Polylang/WPML if present, otherwise keep WP native.
        \do_action( 'aqlx/i18n/init' );
    }
}
