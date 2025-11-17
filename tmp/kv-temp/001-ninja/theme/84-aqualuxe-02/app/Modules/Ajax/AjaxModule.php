<?php
namespace Aqualuxe\Modules\Ajax;

defined('ABSPATH') || exit;

final class AjaxModule {
    public static function register(): void {
        // Nothing now; AJAX endpoints are defined in API\Ajax and functions.php hooks.
        // This module exists to allow toggling AJAX-related behaviors via filters.
        \add_filter( 'aqlx/modules/enabled', [ __CLASS__, 'maybe_disable' ] );
    }

    public static function maybe_disable( array $modules ): array {
        // Example: allow disabling via constant in environments not supporting AJAX.
        if ( defined( 'AQUALUXE_DISABLE_AJAX' ) && AQUALUXE_DISABLE_AJAX ) {
            // Keep the module list unchanged as endpoints already wired, but could remove if needed.
        }
        return $modules;
    }
}
