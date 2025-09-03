<?php
namespace Aqualuxe\Modules\UI;

defined('ABSPATH') || exit;

final class DarkModeModule {
    public static function register(): void {
        \add_action( 'wp_body_open', [ __CLASS__, 'render_toggle' ] );
        \add_filter( 'body_class', [ __CLASS__, 'persisted_class' ] );
    }

    public static function render_toggle(): void {
        echo '<button type="button" class="aqlx-dark-toggle" aria-pressed="false" data-theme-toggle style="position:fixed;right:1rem;bottom:1rem;z-index:50">🌙</button>';
    }

    public static function persisted_class( array $classes ): array {
        // Class applied by JS; server-side default
        $classes[] = 'skin-default';
        return $classes;
    }
}
