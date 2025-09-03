<?php
namespace Aqualuxe\API;

defined('ABSPATH') || exit;

/**
 * Registers REST API routes for the theme.
 */
class Routes {
    private string $namespace = 'aqlx/v1';

    public function register(): void {
        \register_rest_route( $this->namespace, '/ping', [
            'methods'             => \WP_REST_Server::READABLE,
            'permission_callback' => '__return_true',
            'callback'            => function () {
                return new \WP_REST_Response( [ 'pong' => true, 'time' => time() ], 200 );
            },
        ] );

        \register_rest_route( $this->namespace, '/listings', [
            'methods'             => \WP_REST_Server::READABLE,
            'permission_callback' => '__return_true',
            'callback'            => [ $this, 'get_listings' ],
            'args'                => [
                'page'     => [ 'type' => 'integer', 'default' => 1 ],
                'per_page' => [ 'type' => 'integer', 'default' => 10 ],
                's'        => [ 'type' => 'string', 'default' => '' ],
            ],
        ] );
    }

    /**
     * Simple listings endpoint (supports progressive enhancement).
     */
    public function get_listings( \WP_REST_Request $request ): \WP_REST_Response {
        $page     = max( 1, (int) $request->get_param( 'page' ) );
        $per_page = max( 1, min( 50, (int) $request->get_param( 'per_page' ) ) );
        $search   = (string) $request->get_param( 's' );

        $query = new \WP_Query([
            'post_type'      => [ 'listing', 'post' ],
            'posts_per_page' => $per_page,
            'paged'          => $page,
            's'              => $search,
            'no_found_rows'  => false,
        ]);

        $items = [];
        foreach ( $query->posts as $post ) {
            $items[] = [
                'id'    => $post->ID,
                'type'  => $post->post_type,
                'title' => get_the_title( $post ),
                'link'  => get_permalink( $post ),
            ];
        }

        return new \WP_REST_Response([
            'items'      => $items,
            'total'      => (int) $query->found_posts,
            'totalPages' => (int) $query->max_num_pages,
            'page'       => $page,
            'perPage'    => $per_page,
        ], 200 );
    }
}
