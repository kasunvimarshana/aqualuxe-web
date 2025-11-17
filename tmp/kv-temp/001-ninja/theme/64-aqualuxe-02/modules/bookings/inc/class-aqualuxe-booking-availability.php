<?php
/**
 * AquaLuxe Booking Availability Class
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * AquaLuxe Booking Availability Class
 */
class AquaLuxe_Booking_Availability {
    /**
     * Check if a time slot is available
     *
     * @param int    $service_id  Service ID
     * @param int    $resource_id Resource ID (optional)
     * @param string $date        Date in Y-m-d format
     * @param string $time        Time in H:i format
     * @return bool Is available
     */
    public function is_available( $service_id, $resource_id, $date, $time ) {
        // Check if service exists
        $service = get_post( $service_id );
        if ( ! $service || 'aqualuxe_service' !== $service->post_type ) {
            return false;
        }

        // Check if resource exists (if provided)
        if ( ! empty( $resource_id ) ) {
            $resource = get_post( $resource_id );
            if ( ! $resource || 'aqualuxe_resource' !== $resource->post_type ) {
                return false;
            }
        }

        // Check if date is valid
        if ( ! $this->is_valid_date( $date ) ) {
            return false;
        }

        // Check if time is valid
        if ( ! $this->is_valid_time( $time ) ) {
            return false;
        }

        // Check if date is in the past
        $current_date = date( 'Y-m-d' );
        $current_time = date( 'H:i' );
        
        if ( $date < $current_date ) {
            return false;
        }
        
        if ( $date === $current_date && $time < $current_time ) {
            return false;
        }

        // Check if date is within allowed range
        $min_days_advance = get_option( 'aqualuxe_bookings_min_days_advance', 0 );
        $max_days_advance = get_option( 'aqualuxe_bookings_max_days_advance', 90 );
        
        $min_date = date( 'Y-m-d', strtotime( '+' . $min_days_advance . ' days' ) );
        $max_date = date( 'Y-m-d', strtotime( '+' . $max_days_advance . ' days' ) );
        
        if ( $date < $min_date ) {
            return false;
        }
        
        if ( $date > $max_date ) {
            return false;
        }

        // Check service availability
        if ( ! $this->is_service_available( $service_id, $date, $time ) ) {
            return false;
        }

        // Check resource availability (if provided)
        if ( ! empty( $resource_id ) && ! $this->is_resource_available( $resource_id, $date, $time ) ) {
            return false;
        }

        // Check existing bookings
        if ( ! $this->check_existing_bookings( $service_id, $resource_id, $date, $time ) ) {
            return false;
        }

        return true;
    }

    /**
     * Get available time slots for a date
     *
     * @param int    $service_id  Service ID
     * @param int    $resource_id Resource ID (optional)
     * @param string $date        Date in Y-m-d format
     * @return array Available time slots
     */
    public function get_available_slots( $service_id, $resource_id, $date ) {
        // Check if service exists
        $service = get_post( $service_id );
        if ( ! $service || 'aqualuxe_service' !== $service->post_type ) {
            return array();
        }

        // Check if resource exists (if provided)
        if ( ! empty( $resource_id ) ) {
            $resource = get_post( $resource_id );
            if ( ! $resource || 'aqualuxe_resource' !== $resource->post_type ) {
                return array();
            }
        }

        // Check if date is valid
        if ( ! $this->is_valid_date( $date ) ) {
            return array();
        }

        // Get service operating hours
        $operating_hours = $this->get_service_operating_hours( $service_id, $date );
        if ( empty( $operating_hours ) ) {
            return array();
        }

        // Get service duration
        $service_duration = get_post_meta( $service_id, '_duration', true );
        if ( ! $service_duration ) {
            $service_duration = 60; // Default to 60 minutes
        }

        // Get time slot duration
        $time_slot_duration = get_option( 'aqualuxe_bookings_time_slot_duration', 30 );
        if ( ! $time_slot_duration ) {
            $time_slot_duration = 30; // Default to 30 minutes
        }

        // Get buffer times
        $buffer_before = get_option( 'aqualuxe_bookings_buffer_before', 0 );
        $buffer_after = get_option( 'aqualuxe_bookings_buffer_after', 0 );

        // Calculate total duration including buffers
        $total_duration = $service_duration + $buffer_before + $buffer_after;

        // Get existing bookings
        $existing_bookings = $this->get_existing_bookings( $service_id, $resource_id, $date );

        // Generate time slots
        $available_slots = array();
        
        foreach ( $operating_hours as $hours ) {
            $start_time = strtotime( $date . ' ' . $hours['start'] );
            $end_time = strtotime( $date . ' ' . $hours['end'] );
            
            // Adjust start time for buffer before
            $start_time = strtotime( '+' . $buffer_before . ' minutes', $start_time );
            
            // Adjust end time for service duration and buffer after
            $end_time = strtotime( '-' . ($service_duration + $buffer_after) . ' minutes', $end_time );
            
            // Generate slots
            $current_time = $start_time;
            
            while ( $current_time <= $end_time ) {
                $slot_time = date( 'H:i', $current_time );
                
                // Check if slot is available
                if ( $this->is_available( $service_id, $resource_id, $date, $slot_time ) ) {
                    $available_slots[] = array(
                        'time'  => $slot_time,
                        'label' => date_i18n( get_option( 'time_format' ), $current_time ),
                    );
                }
                
                // Move to next slot
                $current_time = strtotime( '+' . $time_slot_duration . ' minutes', $current_time );
            }
        }

        return $available_slots;
    }

    /**
     * Check if a date is valid
     *
     * @param string $date Date in Y-m-d format
     * @return bool Is valid
     */
    private function is_valid_date( $date ) {
        // Check format
        if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
            return false;
        }

        // Check if date is valid
        $year = substr( $date, 0, 4 );
        $month = substr( $date, 5, 2 );
        $day = substr( $date, 8, 2 );
        
        return checkdate( $month, $day, $year );
    }

    /**
     * Check if a time is valid
     *
     * @param string $time Time in H:i format
     * @return bool Is valid
     */
    private function is_valid_time( $time ) {
        // Check format
        if ( ! preg_match( '/^\d{2}:\d{2}$/', $time ) ) {
            return false;
        }

        // Check if time is valid
        $hour = substr( $time, 0, 2 );
        $minute = substr( $time, 3, 2 );
        
        return $hour >= 0 && $hour <= 23 && $minute >= 0 && $minute <= 59;
    }

    /**
     * Check if a service is available on a date and time
     *
     * @param int    $service_id Service ID
     * @param string $date       Date in Y-m-d format
     * @param string $time       Time in H:i format
     * @return bool Is available
     */
    private function is_service_available( $service_id, $date, $time ) {
        // Get service operating hours
        $operating_hours = $this->get_service_operating_hours( $service_id, $date );
        if ( empty( $operating_hours ) ) {
            return false;
        }

        // Check if time is within operating hours
        $time_timestamp = strtotime( $date . ' ' . $time );
        
        foreach ( $operating_hours as $hours ) {
            $start_timestamp = strtotime( $date . ' ' . $hours['start'] );
            $end_timestamp = strtotime( $date . ' ' . $hours['end'] );
            
            if ( $time_timestamp >= $start_timestamp && $time_timestamp <= $end_timestamp ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a resource is available on a date and time
     *
     * @param int    $resource_id Resource ID
     * @param string $date        Date in Y-m-d format
     * @param string $time        Time in H:i format
     * @return bool Is available
     */
    private function is_resource_available( $resource_id, $date, $time ) {
        // Get resource availability
        $availability = get_post_meta( $resource_id, '_availability', true );
        if ( empty( $availability ) ) {
            // Default to always available
            return true;
        }

        // Get day of week
        $day_of_week = date( 'w', strtotime( $date ) );

        // Check if resource is available on this day
        if ( ! isset( $availability[ $day_of_week ] ) || ! $availability[ $day_of_week ]['enabled'] ) {
            return false;
        }

        // Check if time is within available hours
        $time_timestamp = strtotime( $date . ' ' . $time );
        $start_timestamp = strtotime( $date . ' ' . $availability[ $day_of_week ]['start'] );
        $end_timestamp = strtotime( $date . ' ' . $availability[ $day_of_week ]['end'] );
        
        return $time_timestamp >= $start_timestamp && $time_timestamp <= $end_timestamp;
    }

    /**
     * Check existing bookings for conflicts
     *
     * @param int    $service_id  Service ID
     * @param int    $resource_id Resource ID (optional)
     * @param string $date        Date in Y-m-d format
     * @param string $time        Time in H:i format
     * @return bool Is available
     */
    private function check_existing_bookings( $service_id, $resource_id, $date, $time ) {
        // Get service duration
        $service_duration = get_post_meta( $service_id, '_duration', true );
        if ( ! $service_duration ) {
            $service_duration = 60; // Default to 60 minutes
        }

        // Get buffer times
        $buffer_before = get_option( 'aqualuxe_bookings_buffer_before', 0 );
        $buffer_after = get_option( 'aqualuxe_bookings_buffer_after', 0 );

        // Calculate booking start and end times
        $booking_start = strtotime( $date . ' ' . $time );
        $booking_end = strtotime( '+' . $service_duration . ' minutes', $booking_start );

        // Adjust for buffers
        $booking_start_with_buffer = strtotime( '-' . $buffer_before . ' minutes', $booking_start );
        $booking_end_with_buffer = strtotime( '+' . $buffer_after . ' minutes', $booking_end );

        // Get existing bookings
        $existing_bookings = $this->get_existing_bookings( $service_id, $resource_id, $date );

        // Check for conflicts
        foreach ( $existing_bookings as $booking ) {
            $existing_start = strtotime( $date . ' ' . $booking['time'] );
            $existing_end = strtotime( '+' . $booking['duration'] . ' minutes', $existing_start );

            // Check if bookings overlap
            if ( $booking_start_with_buffer < $existing_end && $booking_end_with_buffer > $existing_start ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get existing bookings for a service and resource on a date
     *
     * @param int    $service_id  Service ID
     * @param int    $resource_id Resource ID (optional)
     * @param string $date        Date in Y-m-d format
     * @return array Existing bookings
     */
    private function get_existing_bookings( $service_id, $resource_id, $date ) {
        // Query bookings
        $args = array(
            'post_type'      => 'aqualuxe_booking',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'     => '_date',
                    'value'   => $date,
                    'compare' => '=',
                ),
                array(
                    'key'     => '_service_id',
                    'value'   => $service_id,
                    'compare' => '=',
                ),
            ),
            'tax_query'      => array(
                array(
                    'taxonomy' => 'aqualuxe_booking_status',
                    'field'    => 'slug',
                    'terms'    => array( 'pending', 'confirmed' ),
                ),
            ),
        );

        // Add resource filter if provided
        if ( ! empty( $resource_id ) ) {
            $args['meta_query'][] = array(
                'key'     => '_resource_id',
                'value'   => $resource_id,
                'compare' => '=',
            );
        }

        $query = new WP_Query( $args );
        $bookings = array();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $booking_id = get_the_ID();
                
                $bookings[] = array(
                    'id'       => $booking_id,
                    'time'     => get_post_meta( $booking_id, '_time', true ),
                    'duration' => get_post_meta( $booking_id, '_duration', true ),
                );
            }
            wp_reset_postdata();
        }

        return $bookings;
    }

    /**
     * Get service operating hours for a date
     *
     * @param int    $service_id Service ID
     * @param string $date       Date in Y-m-d format
     * @return array Operating hours
     */
    private function get_service_operating_hours( $service_id, $date ) {
        // Get day of week
        $day_of_week = date( 'w', strtotime( $date ) );

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

        // Check if service is available on this day
        if ( ! isset( $operating_hours[ $day_of_week ] ) || ! $operating_hours[ $day_of_week ]['enabled'] ) {
            return array();
        }

        // Check for special hours
        $special_hours = get_post_meta( $service_id, '_special_hours', true );
        if ( ! empty( $special_hours ) && isset( $special_hours[ $date ] ) ) {
            if ( ! $special_hours[ $date ]['enabled'] ) {
                return array();
            }
            
            return array(
                array(
                    'start' => $special_hours[ $date ]['start'],
                    'end'   => $special_hours[ $date ]['end'],
                ),
            );
        }

        // Check for breaks
        $breaks = get_post_meta( $service_id, '_breaks', true );
        if ( ! empty( $breaks ) && isset( $breaks[ $day_of_week ] ) && ! empty( $breaks[ $day_of_week ] ) ) {
            // Sort breaks by start time
            usort( $breaks[ $day_of_week ], function( $a, $b ) {
                return strtotime( $a['start'] ) - strtotime( $b['start'] );
            } );
            
            // Split operating hours by breaks
            $result = array();
            $start = $operating_hours[ $day_of_week ]['start'];
            
            foreach ( $breaks[ $day_of_week ] as $break ) {
                if ( $start < $break['start'] ) {
                    $result[] = array(
                        'start' => $start,
                        'end'   => $break['start'],
                    );
                }
                
                $start = $break['end'];
            }
            
            if ( $start < $operating_hours[ $day_of_week ]['end'] ) {
                $result[] = array(
                    'start' => $start,
                    'end'   => $operating_hours[ $day_of_week ]['end'],
                );
            }
            
            return $result;
        }

        // Return regular operating hours
        return array(
            array(
                'start' => $operating_hours[ $day_of_week ]['start'],
                'end'   => $operating_hours[ $day_of_week ]['end'],
            ),
        );
    }
}