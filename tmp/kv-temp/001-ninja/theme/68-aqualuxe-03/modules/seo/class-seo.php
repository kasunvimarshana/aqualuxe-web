<?php
/**
 * AquaLuxe SEO/Performance Module
 * Modular SEO and performance features
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class AquaLuxe_SEO {
    public static function init() {
        add_action( 'wp_head', [ __CLASS__, 'output_schema' ] );
        add_action( 'wp_head', [ __CLASS__, 'output_open_graph' ] );
        add_filter( 'wp_get_attachment_image_attributes', [ __CLASS__, 'add_lazy_loading' ], 10, 2 );
        // add_action( 'init', [ __CLASS__, 'register_sitemap' ] );
    }

    public static function output_schema() {
        // Output basic schema.org markup (stub)
        echo '<script type="application/ld+json">{"@context":"https://schema.org"}</script>';
    }

    public static function output_open_graph() {
        // Output Open Graph meta tags (stub)
        echo '<meta property="og:title" content="'.esc_attr(get_bloginfo('name')).'" />';
    }

    public static function add_lazy_loading( $attr, $attachment ) {
        $attr['loading'] = 'lazy';
        return $attr;
    }

    // public static function register_sitemap() {
    //     // Placeholder for sitemap logic
    // }
}

AquaLuxe_SEO::init();
