<?php
/**
 * Advanced Filtering Module Loader
 *
 * @package AquaLuxe\Modules\Filtering
 */
namespace AquaLuxe\Modules\Filtering;

if ( ! defined( 'ABSPATH' ) ) exit;

class Loader {
    public static function init() {
        // Register hooks for advanced filtering
        add_action( 'init', [ __CLASS__, 'register_query_vars' ] );
        add_action( 'pre_get_posts', [ __CLASS__, 'apply_filters' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
    }

    public static function register_query_vars() {
        // Register custom query vars for filtering
        add_filter( 'query_vars', function( $vars ) {
            $vars[] = 'filter_price_min';
            $vars[] = 'filter_price_max';
            $vars[] = 'filter_rating';
            $vars[] = 'filter_attributes';
            return $vars;
        });
    }

    public static function apply_filters( $query ) {
        if ( is_admin() || ! $query->is_main_query() ) return;
        if ( is_shop() || is_product_category() || is_product_tag() ) {
            // Price filter
            $min = get_query_var( 'filter_price_min' );
            $max = get_query_var( 'filter_price_max' );
            if ( $min || $max ) {
                $meta_query = $query->get( 'meta_query' );
                $price_filter = [
                    'key' => '_price',
                    'value' => array_filter([ $min, $max ]),
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC',
                ];
                $meta_query[] = $price_filter;
                $query->set( 'meta_query', $meta_query );
            }
            // Rating filter
            $rating = get_query_var( 'filter_rating' );
            if ( $rating ) {
                $meta_query = $query->get( 'meta_query' );
                $meta_query[] = [
                    'key' => '_wc_average_rating',
                    'value' => $rating,
                    'compare' => '>=',
                    'type' => 'NUMERIC',
                ];
                $query->set( 'meta_query', $meta_query );
            }
            // Attribute filter (e.g., color, size)
            $attributes = get_query_var( 'filter_attributes' );
            if ( $attributes ) {
                // Parse and add taxonomy queries as needed
                // ...
            }
        }
    }

    public static function enqueue_assets() {
        // Enqueue JS/CSS for filtering UI
        wp_enqueue_script(
            'aqualuxe-filtering',
            AQUALUXE_URI . 'assets/dist/js/filtering.js',
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );
        wp_localize_script( 'aqualuxe-filtering', 'aqualuxe_filtering', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
        ] );
        wp_enqueue_style(
            'aqualuxe-filtering',
            AQUALUXE_URI . 'assets/dist/css/filtering.css',
            [],
            AQUALUXE_VERSION
        );
    }
}

Loader::init();
