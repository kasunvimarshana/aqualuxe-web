<?php
/**
 * AquaLuxe Booking Resource Class
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * AquaLuxe Booking Resource Class
 */
class AquaLuxe_Booking_Resource {
    /**
     * Get resource by ID
     *
     * @param int $resource_id Resource ID
     * @return array|false Resource data or false on failure
     */
    public function get( $resource_id ) {
        // Check if resource exists
        $resource = get_post( $resource_id );
        if ( ! $resource || 'aqualuxe_resource' !== $resource->post_type ) {
            return false;
        }

        // Get resource meta data
        $capacity = get_post_meta( $resource_id, '_capacity', true );
        $availability = get_post_meta( $resource_id, '_availability', true );
        $special_availability = get_post_meta( $resource_id, '_special_availability', true );
        $breaks = get_post_meta( $resource_id, '_breaks', true );
        $options = get_post_meta( $resource_id, '_options', true );

        // Get resource categories
        $categories = wp_get_object_terms( $resource_id, 'aqualuxe_resource_cat', array( 'fields' => 'names' ) );

        // Return resource data
        return array(
            'id'                  => $resource_id,
            'title'               => $resource->post_title,
            'description'         => $resource->post_content,
            'excerpt'             => $resource->post_excerpt,
            'capacity'            => $capacity ? $capacity : 1,
            'availability'        => $availability,
            'special_availability' => $special_availability,
            'breaks'              => $breaks,
            'options'             => $options,
            'categories'          => $categories,
            'thumbnail'           => get_the_post_thumbnail_url( $resource_id, 'large' ),
            'permalink'           => get_permalink( $resource_id ),
        );
    }

    /**
     * Get all resources
     *
     * @param array $args Query arguments
     * @return array Resources
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
            'post_type'      => 'aqualuxe_resource',
            'post_status'    => 'publish',
            'posts_per_page' => $args['posts_per_page'],
            'orderby'        => $args['orderby'],
            'order'          => $args['order'],
        );

        // Add category filter
        if ( ! empty( $args['category'] ) ) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'aqualuxe_resource_cat',
                    'field'    => 'slug',
                    'terms'    => $args['category'],
                ),
            );
        }

        // Query resources
        $query = new WP_Query( $query_args );
        $resources = array();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $resource_id = get_the_ID();
                $resource = $this->get( $resource_id );
                if ( $resource ) {
                    $resources[] = $resource;
                }
            }
            wp_reset_postdata();
        }

        return $resources;
    }

    /**
     * Get resource categories
     *
     * @return array Categories
     */
    public function get_categories() {
        $categories = get_terms( array(
            'taxonomy'   => 'aqualuxe_resource_cat',
            'hide_empty' => true,
        ) );

        if ( is_wp_error( $categories ) ) {
            return array();
        }

        return $categories;
    }

    /**
     * Get resource options
     *
     * @param int $resource_id Resource ID
     * @return array Options
     */
    public function get_options( $resource_id ) {
        // Get resource options
        $options = get_post_meta( $resource_id, '_options', true );
        if ( empty( $options ) ) {
            return array();
        }

        return $options;
    }

    /**
     * Get resource availability
     *
     * @param int $resource_id Resource ID
     * @return array Availability
     */
    public function get_availability( $resource_id ) {
        // Get resource availability
        $availability = get_post_meta( $resource_id, '_availability', true );
        if ( empty( $availability ) ) {
            // Default availability
            $availability = array(
                '0' => array( 'enabled' => false ), // Sunday
                '1' => array( 'enabled' => true, 'start' => '09:00', 'end' => '17:00' ), // Monday
                '2' => array( 'enabled' => true, 'start' => '09:00', 'end' => '17:00' ), // Tuesday
                '3' => array( 'enabled' => true, 'start' => '09:00', 'end' => '17:00' ), // Wednesday
                '4' => array( 'enabled' => true, 'start' => '09:00', 'end' => '17:00' ), // Thursday
                '5' => array( 'enabled' => true, 'start' => '09:00', 'end' => '17:00' ), // Friday
                '6' => array( 'enabled' => false ), // Saturday
            );
        }

        return $availability;
    }

    /**
     * Get resource special availability
     *
     * @param int $resource_id Resource ID
     * @return array Special availability
     */
    public function get_special_availability( $resource_id ) {
        // Get resource special availability
        $special_availability = get_post_meta( $resource_id, '_special_availability', true );
        if ( empty( $special_availability ) ) {
            return array();
        }

        return $special_availability;
    }

    /**
     * Get resource breaks
     *
     * @param int $resource_id Resource ID
     * @return array Breaks
     */
    public function get_breaks( $resource_id ) {
        // Get resource breaks
        $breaks = get_post_meta( $resource_id, '_breaks', true );
        if ( empty( $breaks ) ) {
            return array();
        }

        return $breaks;
    }

    /**
     * Get resource availability for a date range
     *
     * @param int    $resource_id Resource ID
     * @param string $start_date  Start date in Y-m-d format
     * @param string $end_date    End date in Y-m-d format
     * @return array Availability data
     */
    public function get_availability_for_date_range( $resource_id, $start_date, $end_date ) {
        // Check if resource exists
        $resource = get_post( $resource_id );
        if ( ! $resource || 'aqualuxe_resource' !== $resource->post_type ) {
            return array();
        }

        // Check if dates are valid
        $availability_obj = new AquaLuxe_Booking_Availability();
        if ( ! $availability_obj->is_valid_date( $start_date ) || ! $availability_obj->is_valid_date( $end_date ) ) {
            return array();
        }

        // Get date range
        $start = new DateTime( $start_date );
        $end = new DateTime( $end_date );
        $interval = new DateInterval( 'P1D' );
        $date_range = new DatePeriod( $start, $interval, $end );

        // Get resource availability
        $availability = $this->get_availability( $resource_id );
        $special_availability = $this->get_special_availability( $resource_id );

        // Get availability for each date
        $result = array();
        
        foreach ( $date_range as $date ) {
            $date_str = $date->format( 'Y-m-d' );
            $day_of_week = $date->format( 'w' );
            
            // Check for special availability
            if ( ! empty( $special_availability ) && isset( $special_availability[ $date_str ] ) ) {
                if ( ! $special_availability[ $date_str ]['enabled'] ) {
                    $result[ $date_str ] = array(
                        'available' => false,
                        'hours'     => array(),
                    );
                    continue;
                }
                
                $result[ $date_str ] = array(
                    'available' => true,
                    'hours'     => array(
                        'start' => $special_availability[ $date_str ]['start'],
                        'end'   => $special_availability[ $date_str ]['end'],
                    ),
                );
                continue;
            }
            
            // Check regular availability
            if ( ! isset( $availability[ $day_of_week ] ) || ! $availability[ $day_of_week ]['enabled'] ) {
                $result[ $date_str ] = array(
                    'available' => false,
                    'hours'     => array(),
                );
                continue;
            }
            
            $result[ $date_str ] = array(
                'available' => true,
                'hours'     => array(
                    'start' => $availability[ $day_of_week ]['start'],
                    'end'   => $availability[ $day_of_week ]['end'],
                ),
            );
        }

        return $result;
    }

    /**
     * Create a new resource
     *
     * @param array $args Resource arguments
     * @return int|false Resource ID or false on failure
     */
    public function create( $args = array() ) {
        // Default arguments
        $defaults = array(
            'title'               => '',
            'description'         => '',
            'excerpt'             => '',
            'capacity'            => 1,
            'availability'        => array(),
            'special_availability' => array(),
            'breaks'              => array(),
            'options'             => array(),
            'categories'          => array(),
            'thumbnail_id'        => 0,
            'status'              => 'publish',
        );

        // Parse arguments
        $args = wp_parse_args( $args, $defaults );

        // Validate required fields
        if ( empty( $args['title'] ) ) {
            return false;
        }

        // Create resource post
        $resource_data = array(
            'post_title'    => $args['title'],
            'post_content'  => $args['description'],
            'post_excerpt'  => $args['excerpt'],
            'post_status'   => $args['status'],
            'post_type'     => 'aqualuxe_resource',
        );

        // Insert resource post
        $resource_id = wp_insert_post( $resource_data );

        if ( ! $resource_id ) {
            return false;
        }

        // Add resource meta data
        update_post_meta( $resource_id, '_capacity', $args['capacity'] );
        update_post_meta( $resource_id, '_availability', $args['availability'] );
        update_post_meta( $resource_id, '_special_availability', $args['special_availability'] );
        update_post_meta( $resource_id, '_breaks', $args['breaks'] );
        update_post_meta( $resource_id, '_options', $args['options'] );

        // Set thumbnail
        if ( ! empty( $args['thumbnail_id'] ) ) {
            set_post_thumbnail( $resource_id, $args['thumbnail_id'] );
        }

        // Set categories
        if ( ! empty( $args['categories'] ) ) {
            wp_set_object_terms( $resource_id, $args['categories'], 'aqualuxe_resource_cat' );
        }

        // Fire action
        do_action( 'aqualuxe_resource_created', $resource_id, $args );

        return $resource_id;
    }

    /**
     * Update a resource
     *
     * @param int   $resource_id Resource ID
     * @param array $args        Resource arguments
     * @return bool Success
     */
    public function update( $resource_id, $args = array() ) {
        // Check if resource exists
        $resource = get_post( $resource_id );
        if ( ! $resource || 'aqualuxe_resource' !== $resource->post_type ) {
            return false;
        }

        // Default arguments
        $defaults = array(
            'title'               => $resource->post_title,
            'description'         => $resource->post_content,
            'excerpt'             => $resource->post_excerpt,
            'capacity'            => get_post_meta( $resource_id, '_capacity', true ),
            'availability'        => get_post_meta( $resource_id, '_availability', true ),
            'special_availability' => get_post_meta( $resource_id, '_special_availability', true ),
            'breaks'              => get_post_meta( $resource_id, '_breaks', true ),
            'options'             => get_post_meta( $resource_id, '_options', true ),
            'categories'          => wp_get_object_terms( $resource_id, 'aqualuxe_resource_cat', array( 'fields' => 'slugs' ) ),
            'thumbnail_id'        => get_post_thumbnail_id( $resource_id ),
            'status'              => $resource->post_status,
        );

        // Parse arguments
        $args = wp_parse_args( $args, $defaults );

        // Update resource post
        $resource_data = array(
            'ID'           => $resource_id,
            'post_title'   => $args['title'],
            'post_content' => $args['description'],
            'post_excerpt' => $args['excerpt'],
            'post_status'  => $args['status'],
        );

        // Update resource post
        $updated = wp_update_post( $resource_data );

        if ( ! $updated ) {
            return false;
        }

        // Update resource meta data
        update_post_meta( $resource_id, '_capacity', $args['capacity'] );
        update_post_meta( $resource_id, '_availability', $args['availability'] );
        update_post_meta( $resource_id, '_special_availability', $args['special_availability'] );
        update_post_meta( $resource_id, '_breaks', $args['breaks'] );
        update_post_meta( $resource_id, '_options', $args['options'] );

        // Update thumbnail
        if ( ! empty( $args['thumbnail_id'] ) ) {
            set_post_thumbnail( $resource_id, $args['thumbnail_id'] );
        } else {
            delete_post_thumbnail( $resource_id );
        }

        // Update categories
        if ( ! empty( $args['categories'] ) ) {
            wp_set_object_terms( $resource_id, $args['categories'], 'aqualuxe_resource_cat' );
        } else {
            wp_set_object_terms( $resource_id, array(), 'aqualuxe_resource_cat' );
        }

        // Fire action
        do_action( 'aqualuxe_resource_updated', $resource_id, $args );

        return true;
    }

    /**
     * Delete a resource
     *
     * @param int $resource_id Resource ID
     * @return bool Success
     */
    public function delete( $resource_id ) {
        // Check if resource exists
        $resource = get_post( $resource_id );
        if ( ! $resource || 'aqualuxe_resource' !== $resource->post_type ) {
            return false;
        }

        // Delete resource post
        $deleted = wp_delete_post( $resource_id, true );

        if ( ! $deleted ) {
            return false;
        }

        // Fire action
        do_action( 'aqualuxe_resource_deleted', $resource_id );

        return true;
    }
}