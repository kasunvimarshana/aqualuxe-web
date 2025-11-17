<?php
namespace Aqualuxe\Modules\Performance;

defined('ABSPATH') || exit;

final class PerformanceModule {
    public static function register(): void {
        \add_filter( 'style_loader_src', [ __CLASS__, 'remove_query_strings' ], 15 );
        \add_filter( 'script_loader_src', [ __CLASS__, 'remove_query_strings' ], 15 );
        \add_filter( 'script_loader_tag', [ __CLASS__, 'defer_scripts' ], 15, 3 );
        \add_filter( 'wp_lazy_loading_enabled', '__return_true' );
        \add_filter( 'wp_sitemaps_enabled', '__return_true' );
    }

    public static function remove_query_strings( string $src ): string {
        $parts = explode( '?', $src );
        return $parts[0];
    }

    public static function defer_scripts( string $tag, string $handle, string $src ): string {
        $exclude = [ 'jquery-core', 'jquery', 'aqualuxe-vendor' ];
        if ( in_array( $handle, $exclude, true ) ) {
            return $tag;
        }
        if ( str_contains( $tag, ' defer' ) || str_contains( $tag, ' type="module"' ) ) {
            return $tag;
        }
        return str_replace( '<script ', '<script defer ', $tag );
    }
}
