<?php
namespace Aqualuxe\Modules\Content;

defined('ABSPATH') || exit;

final class CPTModule {
    public static function register(): void {
        \add_action( 'init', [ __CLASS__, 'cpts' ] );
        \add_action( 'init', [ __CLASS__, 'taxonomies' ] );
    }

    public static function cpts(): void {
        // Listings CPT for classifieds.
        \register_post_type( 'listing', [
            'labels' => [
                'name'          => __( 'Listings', 'aqualuxe' ),
                'singular_name' => __( 'Listing', 'aqualuxe' ),
            ],
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-index-card',
            'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'custom-fields' ],
            'rewrite'      => [ 'slug' => 'listings' ],
            'show_in_rest' => true,
            'map_meta_cap' => true,
            'capability_type' => [ 'listing', 'listings' ],
        ] );

        // Stores CPT for multivendor.
        \register_post_type( 'store', [
            'labels' => [
                'name'          => __( 'Stores', 'aqualuxe' ),
                'singular_name' => __( 'Store', 'aqualuxe' ),
            ],
            'public'       => true,
            'has_archive'  => true,
            'menu_icon'    => 'dashicons-store',
            'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'custom-fields' ],
            'rewrite'      => [ 'slug' => 'stores' ],
            'show_in_rest' => true,
            'map_meta_cap' => true,
            'capability_type' => [ 'store', 'stores' ],
        ] );
    }

    public static function taxonomies(): void {
        \register_taxonomy( 'listing_category', [ 'listing' ], [
            'labels' => [
                'name'          => __( 'Listing Categories', 'aqualuxe' ),
                'singular_name' => __( 'Listing Category', 'aqualuxe' ),
            ],
            'public'       => true,
            'hierarchical' => true,
            'rewrite'      => [ 'slug' => 'listing-category' ],
            'show_in_rest' => true,
        ] );
    }
}
