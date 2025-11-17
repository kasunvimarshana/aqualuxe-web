<?php
/**
 * AquaLuxe Testimonials Module
 * Modular reviews/testimonials feature
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class AquaLuxe_Testimonials {
    public static function init() {
        add_action( 'init', [ __CLASS__, 'register_cpt' ] );
        add_filter( 'template_include', [ __CLASS__, 'template_loader' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
    }

    public static function register_cpt() {
        $labels = [
            'name' => __( 'Testimonials', 'aqualuxe' ),
            'singular_name' => __( 'Testimonial', 'aqualuxe' ),
            'add_new' => __( 'Add New', 'aqualuxe' ),
            'add_new_item' => __( 'Add New Testimonial', 'aqualuxe' ),
            'edit_item' => __( 'Edit Testimonial', 'aqualuxe' ),
            'new_item' => __( 'New Testimonial', 'aqualuxe' ),
            'view_item' => __( 'View Testimonial', 'aqualuxe' ),
            'search_items' => __( 'Search Testimonials', 'aqualuxe' ),
            'not_found' => __( 'No testimonials found', 'aqualuxe' ),
            'not_found_in_trash' => __( 'No testimonials found in Trash', 'aqualuxe' ),
        ];
        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => [ 'slug' => 'testimonials' ],
            'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
            'show_in_rest' => true,
        ];
        register_post_type( 'aqualuxe_testimonial', $args );
    }

    public static function template_loader( $template ) {
        if ( is_singular( 'aqualuxe_testimonial' ) ) {
            $custom = locate_template( 'modules/testimonials/single-testimonial.php' );
            if ( $custom ) return $custom;
        }
        if ( is_post_type_archive( 'aqualuxe_testimonial' ) ) {
            $custom = locate_template( 'modules/testimonials/archive-testimonials.php' );
            if ( $custom ) return $custom;
        }
        return $template;
    }

    public static function enqueue_assets() {
        if ( is_singular( 'aqualuxe_testimonial' ) || is_post_type_archive( 'aqualuxe_testimonial' ) ) {
            wp_enqueue_style( 'aqualuxe-testimonials', get_template_directory_uri() . '/modules/testimonials/assets/testimonials.css', [], '1.0.0' );
            wp_enqueue_script( 'aqualuxe-testimonials', get_template_directory_uri() . '/modules/testimonials/assets/testimonials.js', [ 'jquery' ], '1.0.0', true );
        }
    }
}

AquaLuxe_Testimonials::init();
