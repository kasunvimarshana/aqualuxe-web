<?php
/**
 * AquaLuxe Booking Service Class
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * AquaLuxe Booking Service Class
 */
class AquaLuxe_Booking_Service {
    /**
     * Get service by ID
     *
     * @param int $service_id Service ID
     * @return array|false Service data or false on failure
     */
    public function get( $service_id ) {
        // Check if service exists
        $service = get_post( $service_id );
        if ( ! $service || 'aqualuxe_service' !== $service->post_type ) {
            return false;
        }

        // Get service meta data
        $duration = get_post_meta( $service_id, '_duration', true );
        $price = get_post_meta( $service_id, '_price', true );
        $capacity = get_post_meta( $service_id, '_capacity', true );
        $operating_hours = get_post_meta( $service_id, '_operating_hours', true );
        $special_hours = get_post_meta( $service_id, '_special_hours', true );
        $breaks = get_post_meta( $service_id, '_breaks', true );
        $resources = get_post_meta( $service_id, '_resources', true );
        $options = get_post_meta( $service_id, '_options', true );

        // Get service categories
        $categories = wp_get_object_terms( $service_id, 'aqualuxe_service_cat', array( 'fields' => 'names' ) );

        // Return service data
        return array(
            'id'              => $service_id,
            'title'           => $service->post_title,
            'description'     => $service->post_content,
            'excerpt'         => $service->post_excerpt,
            'duration'        => $duration ? $duration : 60,
            'price'           => $price ? $price : 0,
            'capacity'        => $capacity ? $capacity : 1,
            'operating_hours' => $operating_hours,
            'special_hours'   => $special_hours,
            'breaks'          => $breaks,
            'resources'       => $resources,
            'options'         => $options,
            'categories'      => $categories,
            'thumbnail'       => get_the_post_thumbnail_url( $service_id, 'large' ),
            'permalink'       => get_permalink( $service_id ),
        );
    }

    /**
     * Get all services
     *
     * @param array $args Query arguments
     * @return array Services
     */
    public function get_all( $args = array() ) {
        // Default arguments
        $defaults = array(
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
            'category'       => '',
        );

        // Parse arguments
        $args = wp_parse_args( $args, $defaults );

        // Query arguments
        $query_args = array(
            'post_type'      => 'aqualuxe_service',
            'post_status'    => 'publish',
            'posts_per_page' => $args['posts_per_page'],
            'orderby'        => $args['orderby'],
            'order'          => $args['order'],
        );

        // Add category filter
        if ( ! empty( $args['category'] ) ) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'aqualuxe_service_cat',
                    'field'    => 'slug',
                    'terms'    => $args['category'],
                ),
            );
        }

        // Query services
        $query = new WP_Query( $query_args );
        $services = array();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $service_id = get_the_ID();
                $service = $this->get( $service_id );
                if ( $service ) {
                    $services[] = $service;
                }
            }
            wp_reset_postdata();
        }

        return $services;
    }

    /**
     * Get service categories
     *
     * @return array Categories
     */
    public function get_categories() {
        $categories = get_terms( array(
            'taxonomy'   => 'aqualuxe_service_cat',
            'hide_empty' => true,
        ) );

        if ( is_wp_error( $categories ) ) {
            return array();
        }

        return $categories;
    }

    /**
     * Get service resources
     *
     * @param int $service_id Service ID
     * @return array Resources
     */
    public function get_resources( $service_id ) {
        // Get service resources
        $resource_ids = get_post_meta( $service_id, '_resources', true );
        if ( empty( $resource_ids ) ) {
            return array();
        }

        // Get resource data
        $resources = array();
        $resource_obj = new AquaLuxe_Booking_Resource();

        foreach ( $resource_ids as $resource_id ) {
            $resource = $resource_obj->get( $resource_id );
            if ( $resource ) {
                $resources[] = $resource;
            }
        }

        return $resources;
    }

    /**
     * Get service options
     *
     * @param int $service_id Service ID
     * @return array Options
     */
    public function get_options( $service_id ) {
        // Get service options
        $options = get_post_meta( $service_id, '_options', true );
        if ( empty( $options ) ) {
            return array();
        }

        return $options;
    }

    /**
     * Get service operating hours
     *
     * @param int $service_id Service ID
     * @return array Operating hours
     */
    public function get_operating_hours( $service_id ) {
        // Get service operating hours
        $operating_hours = get_post_meta( $service_id, '_operating_hours', true );
        if ( empty( $operating_hours ) ) {
            // Default operating hours
            $operating_hours = array(
                '0' => array( 'enabled' => false ), // Sunday
                '1' => array( 'enabled' => true, 'start' => '09:00', 'end' => '17:00' ), // Monday
                '2' => array( 'enabled' => true, 'start' => '09:00', 'end' => '17:00' ), // Tuesday
                '3' => array( 'enabled' => true, 'start' => '09:00', 'end' => '17:00' ), // Wednesday
                '4' => array( 'enabled' => true, 'start' => '09:00', 'end' => '17:00' ), // Thursday
                '5' => array( 'enabled' => true, 'start' => '09:00', 'end' => '17:00' ), // Friday
                '6' => array( 'enabled' => false ), // Saturday
            );
        }

        return $operating_hours;
    }

    /**
     * Get service special hours
     *
     * @param int $service_id Service ID
     * @return array Special hours
     */
    public function get_special_hours( $service_id ) {
        // Get service special hours
        $special_hours = get_post_meta( $service_id, '_special_hours', true );
        if ( empty( $special_hours ) ) {
            return array();
        }

        return $special_hours;
    }

    /**
     * Get service breaks
     *
     * @param int $service_id Service ID
     * @return array Breaks
     */
    public function get_breaks( $service_id ) {
        // Get service breaks
        $breaks = get_post_meta( $service_id, '_breaks', true );
        if ( empty( $breaks ) ) {
            return array();
        }

        return $breaks;
    }

    /**
     * Get service availability for a date range
     *
     * @param int    $service_id  Service ID
     * @param string $start_date  Start date in Y-m-d format
     * @param string $end_date    End date in Y-m-d format
     * @param int    $resource_id Resource ID (optional)
     * @return array Availability data
     */
    public function get_availability( $service_id, $start_date, $end_date, $resource_id = 0 ) {
        // Check if service exists
        $service = get_post( $service_id );
        if ( ! $service || 'aqualuxe_service' !== $service->post_type ) {
            return array();
        }

        // Check if dates are valid
        $availability = new AquaLuxe_Booking_Availability();
        if ( ! $availability->is_valid_date( $start_date ) || ! $availability->is_valid_date( $end_date ) ) {
            return array();
        }

        // Get date range
        $start = new DateTime( $start_date );
        $end = new DateTime( $end_date );
        $interval = new DateInterval( 'P1D' );
        $date_range = new DatePeriod( $start, $interval, $end );

        // Get availability for each date
        $result = array();
        
        foreach ( $date_range as $date ) {
            $date_str = $date->format( 'Y-m-d' );
            $available_slots = $availability->get_available_slots( $service_id, $resource_id, $date_str );
            
            $result[ $date_str ] = array(
                'available' => ! empty( $available_slots ),
                'slots'     => $available_slots,
            );
        }

        return $result;
    }

    /**
     * Create a new service
     *
     * @param array $args Service arguments
     * @return int|false Service ID or false on failure
     */
    public function create( $args = array() ) {
        // Default arguments
        $defaults = array(
            'title'           => '',
            'description'     => '',
            'excerpt'         => '',
            'duration'        => 60,
            'price'           => 0,
            'capacity'        => 1,
            'operating_hours' => array(),
            'special_hours'   => array(),
            'breaks'          => array(),
            'resources'       => array(),
            'options'         => array(),
            'categories'      => array(),
            'thumbnail_id'    => 0,
            'status'          => 'publish',
        );

        // Parse arguments
        $args = wp_parse_args( $args, $defaults );

        // Validate required fields
        if ( empty( $args['title'] ) ) {
            return false;
        }

        // Create service post
        $service_data = array(
            'post_title'    => $args['title'],
            'post_content'  => $args['description'],
            'post_excerpt'  => $args['excerpt'],
            'post_status'   => $args['status'],
            'post_type'     => 'aqualuxe_service',
        );

        // Insert service post
        $service_id = wp_insert_post( $service_data );

        if ( ! $service_id ) {
            return false;
        }

        // Add service meta data
        update_post_meta( $service_id, '_duration', $args['duration'] );
        update_post_meta( $service_id, '_price', $args['price'] );
        update_post_meta( $service_id, '_capacity', $args['capacity'] );
        update_post_meta( $service_id, '_operating_hours', $args['operating_hours'] );
        update_post_meta( $service_id, '_special_hours', $args['special_hours'] );
        update_post_meta( $service_id, '_breaks', $args['breaks'] );
        update_post_meta( $service_id, '_resources', $args['resources'] );
        update_post_meta( $service_id, '_options', $args['options'] );

        // Set thumbnail
        if ( ! empty( $args['thumbnail_id'] ) ) {
            set_post_thumbnail( $service_id, $args['thumbnail_id'] );
        }

        // Set categories
        if ( ! empty( $args['categories'] ) ) {
            wp_set_object_terms( $service_id, $args['categories'], 'aqualuxe_service_cat' );
        }

        // Fire action
        do_action( 'aqualuxe_service_created', $service_id, $args );

        return $service_id;
    }

    /**
     * Update a service
     *
     * @param int   $service_id Service ID
     * @param array $args       Service arguments
     * @return bool Success
     */
    public function update( $service_id, $args = array() ) {
        // Check if service exists
        $service = get_post( $service_id );
        if ( ! $service || 'aqualuxe_service' !== $service->post_type ) {
            return false;
        }

        // Default arguments
        $defaults = array(
            'title'           => $service->post_title,
            'description'     => $service->post_content,
            'excerpt'         => $service->post_excerpt,
            'duration'        => get_post_meta( $service_id, '_duration', true ),
            'price'           => get_post_meta( $service_id, '_price', true ),
            'capacity'        => get_post_meta( $service_id, '_capacity', true ),
            'operating_hours' => get_post_meta( $service_id, '_operating_hours', true ),
            'special_hours'   => get_post_meta( $service_id, '_special_hours', true ),
            'breaks'          => get_post_meta( $service_id, '_breaks', true ),
            'resources'       => get_post_meta( $service_id, '_resources', true ),
            'options'         => get_post_meta( $service_id, '_options', true ),
            'categories'      => wp_get_object_terms( $service_id, 'aqualuxe_service_cat', array( 'fields' => 'slugs' ) ),
            'thumbnail_id'    => get_post_thumbnail_id( $service_id ),
            'status'          => $service->post_status,
        );

        // Parse arguments
        $args = wp_parse_args( $args, $defaults );

        // Update service post
        $service_data = array(
            'ID'           => $service_id,
            'post_title'   => $args['title'],
            'post_content' => $args['description'],
            'post_excerpt' => $args['excerpt'],
            'post_status'  => $args['status'],
        );

        // Update service post
        $updated = wp_update_post( $service_data );

        if ( ! $updated ) {
            return false;
        }

        // Update service meta data
        update_post_meta( $service_id, '_duration', $args['duration'] );
        update_post_meta( $service_id, '_price', $args['price'] );
        update_post_meta( $service_id, '_capacity', $args['capacity'] );
        update_post_meta( $service_id, '_operating_hours', $args['operating_hours'] );
        update_post_meta( $service_id, '_special_hours', $args['special_hours'] );
        update_post_meta( $service_id, '_breaks', $args['breaks'] );
        update_post_meta( $service_id, '_resources', $args['resources'] );
        update_post_meta( $service_id, '_options', $args['options'] );

        // Update thumbnail
        if ( ! empty( $args['thumbnail_id'] ) ) {
            set_post_thumbnail( $service_id, $args['thumbnail_id'] );
        } else {
            delete_post_thumbnail( $service_id );
        }

        // Update categories
        if ( ! empty( $args['categories'] ) ) {
            wp_set_object_terms( $service_id, $args['categories'], 'aqualuxe_service_cat' );
        } else {
            wp_set_object_terms( $service_id, array(), 'aqualuxe_service_cat' );
        }

        // Fire action
        do_action( 'aqualuxe_service_updated', $service_id, $args );

        return true;
    }

    /**
     * Delete a service
     *
     * @param int $service_id Service ID
     * @return bool Success
     */
    public function delete( $service_id ) {
        // Check if service exists
        $service = get_post( $service_id );
        if ( ! $service || 'aqualuxe_service' !== $service->post_type ) {
            return false;
        }

        // Delete service post
        $deleted = wp_delete_post( $service_id, true );

        if ( ! $deleted ) {
            return false;
        }

        // Fire action
        do_action( 'aqualuxe_service_deleted', $service_id );

        return true;
    }
}