<?php
/**
 * Service Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Services\Inc
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Services\Inc;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Service Class
 * 
 * This class handles individual service functionality.
 */
class Service {
    /**
     * Service ID
     *
     * @var int
     */
    private $id;

    /**
     * Service data
     *
     * @var array
     */
    private $data;

    /**
     * Constructor
     *
     * @param int $service_id
     */
    public function __construct( $service_id = 0 ) {
        if ( $service_id > 0 ) {
            $this->id = $service_id;
            $this->load();
        }
    }

    /**
     * Load service data
     *
     * @return void
     */
    private function load() {
        $post = get_post( $this->id );

    if ( ! $post || 'aqlx_service' !== $post->post_type ) {
            return;
        }

        $this->data = [
            'id'          => $post->ID,
            'title'       => $post->post_title,
            'description' => $post->post_content,
            'excerpt'     => $post->post_excerpt,
            'date'        => $post->post_date,
            'modified'    => $post->post_modified,
            'status'      => $post->post_status,
            'slug'        => $post->post_name,
            'permalink'   => get_permalink( $post->ID ),
            'thumbnail'   => get_the_post_thumbnail_url( $post->ID, 'large' ),
            'categories'  => wp_get_post_terms( $post->ID, 'service_category', [ 'fields' => 'all' ] ),
            'tags'        => wp_get_post_terms( $post->ID, 'service_tag', [ 'fields' => 'all' ] ),
            'meta'        => $this->get_service_meta( $post->ID ),
        ];
    }

    /**
     * Get service meta
     *
     * @param int $post_id
     * @return array
     */
    private function get_service_meta( $post_id ) {
        return [
            'price'       => get_post_meta( $post_id, '_aqualuxe_service_price', true ),
            'price_type'  => get_post_meta( $post_id, '_aqualuxe_service_price_type', true ),
            'sale_price'  => get_post_meta( $post_id, '_aqualuxe_service_sale_price', true ),
            'duration'    => get_post_meta( $post_id, '_aqualuxe_service_duration', true ),
            'location'    => get_post_meta( $post_id, '_aqualuxe_service_location', true ),
            'features'    => $this->get_service_features( $post_id ),
            'bookable'    => get_post_meta( $post_id, '_aqualuxe_service_bookable', true ),
            'capacity'    => get_post_meta( $post_id, '_aqualuxe_service_capacity', true ),
        ];
    }

    /**
     * Get service features
     *
     * @param int $post_id
     * @return array
     */
    private function get_service_features( $post_id ) {
        $features = get_post_meta( $post_id, '_aqualuxe_service_features', true );
        if ( ! $features ) {
            return [];
        }

        return array_map( 'trim', explode( "\n", $features ) );
    }

    /**
     * Get service ID
     *
     * @return int
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Get service title
     *
     * @return string
     */
    public function get_title() {
        return isset( $this->data['title'] ) ? $this->data['title'] : '';
    }

    /**
     * Get service description
     *
     * @return string
     */
    public function get_description() {
        return isset( $this->data['description'] ) ? $this->data['description'] : '';
    }

    /**
     * Get service excerpt
     *
     * @return string
     */
    public function get_excerpt() {
        return isset( $this->data['excerpt'] ) ? $this->data['excerpt'] : '';
    }

    /**
     * Get service permalink
     *
     * @return string
     */
    public function get_permalink() {
        return isset( $this->data['permalink'] ) ? $this->data['permalink'] : '';
    }

    /**
     * Get service thumbnail
     *
     * @param string $size
     * @return string
     */
    public function get_thumbnail( $size = 'large' ) {
        if ( 'large' === $size && isset( $this->data['thumbnail'] ) ) {
            return $this->data['thumbnail'];
        }

        return get_the_post_thumbnail_url( $this->id, $size );
    }

    /**
     * Get service categories
     *
     * @return array
     */
    public function get_categories() {
        return isset( $this->data['categories'] ) ? $this->data['categories'] : [];
    }

    /**
     * Get service tags
     *
     * @return array
     */
    public function get_tags() {
        return isset( $this->data['tags'] ) ? $this->data['tags'] : [];
    }

    /**
     * Get service price
     *
     * @param bool $formatted
     * @return string|float
     */
    public function get_price( $formatted = false ) {
        $price = isset( $this->data['meta']['price'] ) ? $this->data['meta']['price'] : '';
        
        if ( ! $formatted || ! $price ) {
            return $price;
        }

        return $this->format_price( $price );
    }

    /**
     * Get service price type
     *
     * @return string
     */
    public function get_price_type() {
        return isset( $this->data['meta']['price_type'] ) ? $this->data['meta']['price_type'] : 'fixed';
    }

    /**
     * Get service sale price
     *
     * @param bool $formatted
     * @return string|float
     */
    public function get_sale_price( $formatted = false ) {
        $sale_price = isset( $this->data['meta']['sale_price'] ) ? $this->data['meta']['sale_price'] : '';
        
        if ( ! $formatted || ! $sale_price ) {
            return $sale_price;
        }

        return $this->format_price( $sale_price );
    }

    /**
     * Get service duration
     *
     * @param bool $formatted
     * @return string|int
     */
    public function get_duration( $formatted = false ) {
        $duration = isset( $this->data['meta']['duration'] ) ? $this->data['meta']['duration'] : '';
        
        if ( ! $formatted || ! $duration ) {
            return $duration;
        }

        return $this->format_duration( $duration );
    }

    /**
     * Get service location
     *
     * @return string
     */
    public function get_location() {
        return isset( $this->data['meta']['location'] ) ? $this->data['meta']['location'] : '';
    }

    /**
     * Get service features
     *
     * @return array
     */
    public function get_features() {
        return isset( $this->data['meta']['features'] ) ? $this->data['meta']['features'] : [];
    }

    /**
     * Check if service is bookable
     *
     * @return bool
     */
    public function is_bookable() {
        return isset( $this->data['meta']['bookable'] ) && 'yes' === $this->data['meta']['bookable'];
    }

    /**
     * Get service capacity
     *
     * @return int
     */
    public function get_capacity() {
        return isset( $this->data['meta']['capacity'] ) ? (int) $this->data['meta']['capacity'] : 1;
    }

    /**
     * Format price
     *
     * @param float $price
     * @return string
     */
    private function format_price( $price ) {
        return '$' . number_format( (float) $price, 2 );
    }

    /**
     * Format duration
     *
     * @param int $duration
     * @return string
     */
    private function format_duration( $duration ) {
        $hours = floor( $duration / 60 );
        $minutes = $duration % 60;

        if ( $hours > 0 && $minutes > 0 ) {
            return sprintf( __( '%d hours %d minutes', 'aqualuxe' ), $hours, $minutes );
        } elseif ( $hours > 0 ) {
            return sprintf( _n( '%d hour', '%d hours', $hours, 'aqualuxe' ), $hours );
        } else {
            return sprintf( _n( '%d minute', '%d minutes', $minutes, 'aqualuxe' ), $minutes );
        }
    }

    /**
     * Get formatted price with type
     *
     * @return string
     */
    public function get_formatted_price_with_type() {
        $price = $this->get_price( true );
        $price_type = $this->get_price_type();
        $sale_price = $this->get_sale_price( true );

        if ( 'quote' === $price_type ) {
            return __( 'Quote Only', 'aqualuxe' );
        }

        $output = $price;

        if ( 'starting' === $price_type ) {
            $output = sprintf( __( 'From %s', 'aqualuxe' ), $price );
        } elseif ( 'hourly' === $price_type ) {
            $output = sprintf( __( '%s per hour', 'aqualuxe' ), $price );
        }

        if ( $sale_price ) {
            $output = sprintf( '<del>%s</del> <ins>%s</ins>', $output, $sale_price );
        }

        return $output;
    }

    /**
     * Get service data
     *
     * @return array
     */
    public function get_data() {
        return $this->data;
    }

    /**
     * Get related services
     *
     * @param int $limit
     * @return array
     */
    public function get_related_services( $limit = 3 ) {
        $categories = wp_get_post_terms( $this->id, 'service_category', [ 'fields' => 'ids' ] );
        
        if ( empty( $categories ) ) {
            return [];
        }

        $args = [
            'post_type'      => 'aqlx_service',
            'posts_per_page' => $limit,
            'post__not_in'   => [ $this->id ],
            'tax_query'      => [
                [
                    'taxonomy' => 'service_category',
                    'field'    => 'term_id',
                    'terms'    => $categories,
                ],
            ],
        ];

        $related_posts = get_posts( $args );
        $related_services = [];

        foreach ( $related_posts as $post ) {
            $related_services[] = new Service( $post->ID );
        }

        return $related_services;
    }

    /**
     * Get service packages containing this service
     *
     * @return array
     */
    public function get_packages() {
        $args = [
            'post_type'      => 'aqlx_service_pkg',
            'posts_per_page' => -1,
            'meta_query'     => [
                [
                    'key'     => '_aqualuxe_service_package_services',
                    'value'   => $this->id,
                    'compare' => 'LIKE',
                ],
            ],
        ];

        return get_posts( $args );
    }

    /**
     * Check if service has a WooCommerce product
     *
     * @return bool
     */
    public function has_product() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return false;
        }

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => 1,
            'meta_query'     => [
                [
                    'key'   => '_is_aqualuxe_service',
                    'value' => 'yes',
                ],
                [
                    'key'   => '_aqualuxe_service_id',
                    'value' => $this->id,
                ],
            ],
        ];

        $products = get_posts( $args );

        return ! empty( $products );
    }

    /**
     * Get WooCommerce product ID
     *
     * @return int
     */
    public function get_product_id() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return 0;
        }

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => 1,
            'meta_query'     => [
                [
                    'key'   => '_is_aqualuxe_service',
                    'value' => 'yes',
                ],
                [
                    'key'   => '_aqualuxe_service_id',
                    'value' => $this->id,
                ],
            ],
        ];

        $products = get_posts( $args );

        if ( empty( $products ) ) {
            return 0;
        }

        return $products[0]->ID;
    }

    /**
     * Get booking URL
     *
     * @return string
     */
    public function get_booking_url() {
        if ( ! $this->is_bookable() ) {
            return '';
        }

        // If WooCommerce is active and service has a product, use add to cart URL
        if ( class_exists( 'WooCommerce' ) && $this->has_product() ) {
            $product_id = $this->get_product_id();
            if ( $product_id ) {
                return add_query_arg( 'add-to-cart', $product_id, wc_get_cart_url() );
            }
        }

        // If Bookings module is active, use booking page URL
        if ( class_exists( 'AquaLuxe\Modules\Bookings\Bookings' ) ) {
            $settings = get_option( 'aqualuxe_booking_settings', [] );
            $booking_page = isset( $settings['booking_page'] ) ? $settings['booking_page'] : '';
            
            if ( $booking_page ) {
                return add_query_arg( 'service_id', $this->id, get_permalink( $booking_page ) );
            }
        }

        // Fallback to service permalink
        return $this->get_permalink();
    }
}