<?php
namespace Aqualuxe\Modules\Access;

defined('ABSPATH') || exit;

final class AccessModule {
    public static function register(): void {
        \add_action( 'init', [ __CLASS__, 'register_roles' ] );
    }

    public static function register_roles(): void {
        // Multivendor roles (example minimal caps; refine per business needs).
        if ( ! get_role( 'vendor' ) ) {
            \add_role( 'vendor', __( 'Vendor', 'aqualuxe' ), [
                'read'                   => true,
                'edit_posts'             => true,
                'upload_files'           => true,
                'publish_posts'          => true,
                'delete_posts'           => true,
            ] );
        }

        if ( ! get_role( 'moderator' ) ) {
            \add_role( 'moderator', __( 'Moderator', 'aqualuxe' ), [
                'read'           => true,
                'edit_others_posts' => true,
                'edit_posts'     => true,
                'delete_posts'   => true,
                'publish_posts'  => true,
            ] );
        }

        // Granular caps for custom post types added in CPTModule
        $roles = [ 'administrator', 'moderator' ];
        foreach ( $roles as $role_key ) {
            $role = get_role( $role_key );
            if ( $role ) {
                foreach ( [ 'listing', 'store' ] as $type ) {
                    foreach ( [ 'edit', 'read', 'delete' ] as $verb ) {
                        $role->add_cap( sprintf( '%s_%s', $verb, $type ) );
                    }
                    foreach ( [ 'edit_others', 'publish', 'read_private', 'delete_private', 'delete_published', 'delete_others', 'edit_private', 'edit_published' ] as $v ) {
                        $role->add_cap( sprintf( '%s_%ss', $v, $type ) );
                    }
                }
            }
        }
    }
}
