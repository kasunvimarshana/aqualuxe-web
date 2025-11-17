<?php
namespace AquaLuxe\Modules\WooFallback;

use AquaLuxe\Core\Contracts\Module as ModuleContract;

class Module implements ModuleContract {
    public function boot(): void {
        if ( class_exists( '\\WooCommerce' ) ) {
            return; // No fallback needed.
        }
        \add_action( 'init', [ $this, 'register_pages' ] );
        \add_filter( 'the_content', [ $this, 'inject_fallback' ] );
    }

    public function register_pages(): void {
        // Could register a "Shop" page if not exists. Keep lightweight.
    }

    public function inject_fallback( $content ) {
        $is_page = \function_exists( 'is_page' ) ? \call_user_func( 'is_page' ) : false;
        $post    = \function_exists( 'get_post' ) ? \call_user_func( 'get_post' ) : null;
        $slug    = $post ? \call_user_func( 'get_post_field', 'post_name', $post ) : '';
        if ( $is_page && $slug === 'shop' ) {
            $content .= '<div class="notice notice-info">' . esc_html__( 'E-commerce features are unavailable. Please activate WooCommerce.', 'aqualuxe' ) . '</div>';
        }
        return $content;
    }
}
