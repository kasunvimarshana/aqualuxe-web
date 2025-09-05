<?php
namespace AquaLuxe\Modules\Multilingual;

use AquaLuxe\Core\Contracts\Module as ModuleContract;

class Module implements ModuleContract {
    public function boot(): void {
        \add_filter( 'locale', [ $this, 'determine_locale' ] );
        \add_action( 'init', [ $this, 'register_textdomain' ] );
    }

    public function determine_locale( $locale ) {
        // Future: read from user preference or subdomain. Keep default for now.
        return $locale;
    }

    public function register_textdomain(): void {
        if ( \function_exists( 'load_theme_textdomain' ) && \function_exists( 'get_template_directory' ) ) {
            \call_user_func( 'load_theme_textdomain', 'aqualuxe', \call_user_func( 'get_template_directory' ) . '/languages' );
        }
    }
}
