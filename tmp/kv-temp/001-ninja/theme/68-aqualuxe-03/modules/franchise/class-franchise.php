<?php
/**
 * AquaLuxe Franchise/Licensing Module
 * Modular franchise and licensing features
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class AquaLuxe_Franchise {
    public static function init() {
        add_action( 'init', [ __CLASS__, 'register_cpt' ] );
        add_filter( 'template_include', [ __CLASS__, 'template_loader' ] );
        // add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
    }

    public static function register_cpt() {
        $labels = [
            'name' => __( 'Franchise Locations', 'aqualuxe' ),
            'singular_name' => __( 'Franchise Location', 'aqualuxe' ),
            'add_new' => __( 'Add New', 'aqualuxe' ),
            'add_new_item' => __( 'Add New Franchise Location', 'aqualuxe' ),
            'edit_item' => __( 'Edit Franchise Location', 'aqualuxe' ),
            'new_item' => __( 'New Franchise Location', 'aqualuxe' ),
            'view_item' => __( 'View Franchise Location', 'aqualuxe' ),
            'search_items' => __( 'Search Franchise Locations', 'aqualuxe' ),
            'not_found' => __( 'No franchise locations found', 'aqualuxe' ),
            'not_found_in_trash' => __( 'No franchise locations found in Trash', 'aqualuxe' ),
        ];
        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => [ 'slug' => 'franchise' ],
            'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
            'show_in_rest' => true,
        ];
        register_post_type( 'aqualuxe_franchise', $args );
    }

    public static function template_loader( $template ) {
        if ( is_singular( 'aqualuxe_franchise' ) ) {
            $custom = locate_template( 'modules/franchise/single-franchise.php' );
            if ( $custom ) return $custom;
        }
        if ( is_post_type_archive( 'aqualuxe_franchise' ) ) {
            $custom = locate_template( 'modules/franchise/archive-franchise.php' );
            if ( $custom ) return $custom;
        }
        return $template;
    }
}

AquaLuxe_Franchise::init();
