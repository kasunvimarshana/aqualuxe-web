<?php
/**
 * AquaLuxe Auctions/Trade-ins Module
 * Modular auctions and trade-in features
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class AquaLuxe_Auctions {
    public static function init() {
        add_action( 'init', [ __CLASS__, 'register_cpt' ] );
        add_filter( 'template_include', [ __CLASS__, 'template_loader' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
    }

    public static function register_cpt() {
        $labels = [
            'name' => __( 'Auctions', 'aqualuxe' ),
            'singular_name' => __( 'Auction Item', 'aqualuxe' ),
            'add_new' => __( 'Add New', 'aqualuxe' ),
            'add_new_item' => __( 'Add New Auction Item', 'aqualuxe' ),
            'edit_item' => __( 'Edit Auction Item', 'aqualuxe' ),
            'new_item' => __( 'New Auction Item', 'aqualuxe' ),
            'view_item' => __( 'View Auction Item', 'aqualuxe' ),
            'search_items' => __( 'Search Auctions', 'aqualuxe' ),
            'not_found' => __( 'No auction items found', 'aqualuxe' ),
            'not_found_in_trash' => __( 'No auction items found in Trash', 'aqualuxe' ),
        ];
        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => [ 'slug' => 'auctions' ],
            'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
            'show_in_rest' => true,
        ];
        register_post_type( 'aqualuxe_auction', $args );
    }

    public static function template_loader( $template ) {
        if ( is_singular( 'aqualuxe_auction' ) ) {
            $custom = locate_template( 'modules/auctions/single-auction.php' );
            if ( $custom ) return $custom;
        }
        if ( is_post_type_archive( 'aqualuxe_auction' ) ) {
            $custom = locate_template( 'modules/auctions/archive-auctions.php' );
            if ( $custom ) return $custom;
        }
        return $template;
    }

    public static function enqueue_assets() {
        if ( is_singular( 'aqualuxe_auction' ) || is_post_type_archive( 'aqualuxe_auction' ) ) {
            wp_enqueue_style( 'aqualuxe-auctions', get_template_directory_uri() . '/modules/auctions/assets/auctions.css', [], '1.0.0' );
            wp_enqueue_script( 'aqualuxe-auctions', get_template_directory_uri() . '/modules/auctions/assets/auctions.js', [ 'jquery' ], '1.0.0', true );
        }
    }
}

AquaLuxe_Auctions::init();
