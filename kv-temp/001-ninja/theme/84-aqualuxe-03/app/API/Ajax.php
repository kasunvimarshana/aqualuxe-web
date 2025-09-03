<?php
namespace Aqualuxe\API;

defined('ABSPATH') || exit;

class Ajax {
    public static function handle(): void {
        \check_ajax_referer( 'aqlx_ajax', 'nonce' );

        $action = isset( $_POST['subaction'] ) ? sanitize_key( (string) $_POST['subaction'] ) : '';
        switch ( $action ) {
            case 'search_listings':
                self::search_listings();
                break;
            default:
                \wp_send_json_error( [ 'message' => __( 'Invalid action.', 'aqualuxe' ) ], 400 );
        }
    }

    private static function search_listings(): void {
        $s        = isset( $_POST['s'] ) ? sanitize_text_field( wp_unslash( (string) $_POST['s'] ) ) : '';
        $page     = isset( $_POST['page'] ) ? max( 1, (int) $_POST['page'] ) : 1;
        $per_page = isset( $_POST['per_page'] ) ? max( 1, min( 50, (int) $_POST['per_page'] ) ) : 10;

        $q = new \WP_Query([
            'post_type'      => [ 'listing' ],
            's'              => $s,
            'paged'          => $page,
            'posts_per_page' => $per_page,
            'no_found_rows'  => true,
        ]);

        $items = [];
        foreach ( $q->posts as $post ) {
            $items[] = [
                'id'    => $post->ID,
                'title' => get_the_title( $post ),
                'link'  => get_permalink( $post ),
            ];
        }

        \wp_send_json_success( [ 'items' => $items ] );
    }
}
