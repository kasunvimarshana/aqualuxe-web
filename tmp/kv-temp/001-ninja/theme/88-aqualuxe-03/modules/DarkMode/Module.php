<?php
namespace AquaLuxe\Modules\DarkMode;

use AquaLuxe\Core\Contracts\Module as ModuleContract;

class Module implements ModuleContract {
    public function boot(): void {
        \add_filter( 'body_class', [ $this, 'body_class' ] );
        \add_action( 'wp_footer', [ $this, 'toggle_markup' ] );
    }

    public function body_class( array $classes ): array {
        // Server-side prefers no default; client toggles via JS using prefers-color-scheme.
        return $classes;
    }

    public function toggle_markup(): void {
        echo '<button class="aqlx-dark-toggle" aria-pressed="false" aria-label="Toggle dark mode">🌓</button>';
    }
}
