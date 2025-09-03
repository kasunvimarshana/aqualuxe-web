<?php
namespace Aqualuxe\Modules\Security;

defined('ABSPATH') || exit;

final class SecurityModule {
    public static function register(): void {
        \add_filter( 'the_generator', '__return_empty_string' );
        \add_filter( 'rest_endpoints', [ __CLASS__, 'limit_rest_endpoints' ] );
        \add_action( 'init', [ __CLASS__, 'disable_xmlrpc' ] );
        \add_filter( 'xmlrpc_enabled', '__return_false' );
        \add_filter( 'comment_text', [ __CLASS__, 'sanitize_comment' ], 10, 2 );
    }

    public static function limit_rest_endpoints( array $endpoints ): array {
        // Example: Unset users endpoint for non-admins.
        if ( ! current_user_can( 'list_users' ) ) {
            unset( $endpoints['/wp/v2/users'] );
            unset( $endpoints['/wp/v2/users/(?P<id>[\\d]+)'] );
        }
        return $endpoints;
    }

    public static function disable_xmlrpc(): void {
        // No-op placeholder for further hardening.
    }

    public static function sanitize_comment( string $comment_text, $comment ): string {
        return wp_kses_post( $comment_text );
    }
}
