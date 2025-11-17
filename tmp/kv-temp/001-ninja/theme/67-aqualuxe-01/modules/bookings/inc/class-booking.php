<?php
/**
 * Booking Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * Booking Class
 * 
 * This class handles individual booking operations.
 */
class Booking {
    /**
     * Booking ID
     *
     * @var int
     */
    private $id;

    /**
     * Service ID
     *
     * @var int
     */
    private $service_id;

    /**
     * Date
     *
     * @var string
     */
    private $date;

    /**
     * Time
     *
     * @var string
     */
    private $time;

    /**
     * Duration
     *
     * @var int
     */
    private $duration;

    /**
     * Status
     *
     * @var string
     */
    private $status;

    /**
     * Customer name
     *
     * @var string
     */
    private $customer_name;

    /**
     * Customer email
     *
     * @var string
     */
    private $customer_email;

    /**
     * Customer phone
     *
     * @var string
     */
    private $customer_phone;

    /**
     * Customer address
     *
     * @var string
     */
    private $customer_address;

    /**
     * Customer city
     *
     * @var string
     */
    private $customer_city;

    /**
     * Customer state
     *
     * @var string
     */
    private $customer_state;

    /**
     * Customer zip
     *
     * @var string
     */
    private $customer_zip;

    /**
     * Customer country
     *
     * @var string
     */
    private $customer_country;

    /**
     * Customer notes
     *
     * @var string
     */
    private $customer_notes;

    /**
     * Booking notes
     *
     * @var string
     */
    private $booking_notes;

    /**
     * Constructor
     *
     * @param int $booking_id Booking ID.
     */
    public function __construct( $booking_id = 0 ) {
        if ( $booking_id > 0 ) {
            $this->id = $booking_id;
            $this->load();
        }
    }

    /**
     * Load booking data
     *
     * @return void
     */
    private function load() {
        // Check if booking exists
        $booking = get_post( $this->id );

        if ( ! $booking || 'aqualuxe_booking' !== $booking->post_type ) {
            return;
        }

        // Load booking data
        $this->service_id = get_post_meta( $this->id, '_service_id', true );
        $this->date = get_post_meta( $this->id, '_date', true );
        $this->time = get_post_meta( $this->id, '_time', true );
        $this->duration = get_post_meta( $this->id, '_duration', true );
        
        // Get status
        $status = wp_get_post_terms( $this->id, 'aqualuxe_booking_status', [ 'fields' => 'slugs' ] );
        $this->status = ! empty( $status ) ? $status[0] : 'pending';
        
        // Load customer data
        $this->customer_name = get_post_meta( $this->id, '_customer_name', true );
        $this->customer_email = get_post_meta( $this->id, '_customer_email', true );
        $this->customer_phone = get_post_meta( $this->id, '_customer_phone', true );
        $this->customer_address = get_post_meta( $this->id, '_customer_address', true );
        $this->customer_city = get_post_meta( $this->id, '_customer_city', true );
        $this->customer_state = get_post_meta( $this->id, '_customer_state', true );
        $this->customer_zip = get_post_meta( $this->id, '_customer_zip', true );
        $this->customer_country = get_post_meta( $this->id, '_customer_country', true );
        $this->customer_notes = get_post_meta( $this->id, '_customer_notes', true );
        
        // Load booking notes
        $this->booking_notes = get_post_meta( $this->id, '_booking_notes', true );
    }

    /**
     * Create a new booking
     *
     * @param array $data Booking data.
     * @return int|WP_Error
     */
    public function create( $data ) {
        // Validate required fields
        if ( empty( $data['service_id'] ) || empty( $data['date'] ) || empty( $data['time'] ) || 
             empty( $data['customer_name'] ) || empty( $data['customer_email'] ) ) {
            return new \WP_Error( 'missing_required_fields', __( 'Missing required fields', 'aqualuxe' ) );
        }

        // Set booking data
        $this->service_id = absint( $data['service_id'] );
        $this->date = sanitize_text_field( $data['date'] );
        $this->time = sanitize_text_field( $data['time'] );
        $this->duration = ! empty( $data['duration'] ) ? absint( $data['duration'] ) : $this->get_service_duration();
        $this->status = ! empty( $data['status'] ) ? sanitize_text_field( $data['status'] ) : 'pending';
        
        // Set customer data
        $this->customer_name = sanitize_text_field( $data['customer_name'] );
        $this->customer_email = sanitize_email( $data['customer_email'] );
        $this->customer_phone = ! empty( $data['customer_phone'] ) ? sanitize_text_field( $data['customer_phone'] ) : '';
        $this->customer_address = ! empty( $data['customer_address'] ) ? sanitize_text_field( $data['customer_address'] ) : '';
        $this->customer_city = ! empty( $data['customer_city'] ) ? sanitize_text_field( $data['customer_city'] ) : '';
        $this->customer_state = ! empty( $data['customer_state'] ) ? sanitize_text_field( $data['customer_state'] ) : '';
        $this->customer_zip = ! empty( $data['customer_zip'] ) ? sanitize_text_field( $data['customer_zip'] ) : '';
        $this->customer_country = ! empty( $data['customer_country'] ) ? sanitize_text_field( $data['customer_country'] ) : '';
        $this->customer_notes = ! empty( $data['customer_notes'] ) ? sanitize_textarea_field( $data['customer_notes'] ) : '';
        
        // Set booking notes
        $this->booking_notes = ! empty( $data['booking_notes'] ) ? sanitize_textarea_field( $data['booking_notes'] ) : '';

        // Check if the service exists
        $service = get_post( $this->service_id );
        if ( ! $service || 'aqualuxe_service' !== $service->post_type ) {
            return new \WP_Error( 'invalid_service', __( 'Invalid service', 'aqualuxe' ) );
        }

        // Check if the date is valid
        if ( ! $this->is_valid_date( $this->date ) ) {
            return new \WP_Error( 'invalid_date', __( 'Invalid date', 'aqualuxe' ) );
        }

        // Check if the time is valid
        if ( ! $this->is_valid_time( $this->time ) ) {
            return new \WP_Error( 'invalid_time', __( 'Invalid time', 'aqualuxe' ) );
        }

        // Check if the service is available at the requested date and time
        if ( ! $this->is_service_available() ) {
            return new \WP_Error( 'service_unavailable', __( 'Service is not available at the requested date and time', 'aqualuxe' ) );
        }

        // Create booking post
        $booking_id = wp_insert_post(
            [
                'post_title'   => sprintf( __( 'Booking for %s on %s at %s', 'aqualuxe' ), $this->customer_name, $this->date, $this->time ),
                'post_status'  => 'publish',
                'post_type'    => 'aqualuxe_booking',
                'post_author'  => get_current_user_id(),
            ]
        );

        if ( is_wp_error( $booking_id ) ) {
            return $booking_id;
        }

        // Set booking ID
        $this->id = $booking_id;

        // Save booking data
        $this->save();

        // Set booking status
        wp_set_object_terms( $this->id, $this->status, 'aqualuxe_booking_status' );

        // Send notifications
        $this->send_notifications( 'new' );

        return $this->id;
    }

    /**
     * Update booking
     *
     * @param array $data Booking data.
     * @return bool|WP_Error
     */
    public function update( $data ) {
        // Check if booking exists
        if ( ! $this->id ) {
            return new \WP_Error( 'invalid_booking', __( 'Invalid booking', 'aqualuxe' ) );
        }

        // Update booking data
        if ( isset( $data['service_id'] ) ) {
            $this->service_id = absint( $data['service_id'] );
        }

        if ( isset( $data['date'] ) ) {
            $this->date = sanitize_text_field( $data['date'] );
        }

        if ( isset( $data['time'] ) ) {
            $this->time = sanitize_text_field( $data['time'] );
        }

        if ( isset( $data['duration'] ) ) {
            $this->duration = absint( $data['duration'] );
        }

        if ( isset( $data['status'] ) ) {
            $this->status = sanitize_text_field( $data['status'] );
        }

        // Update customer data
        if ( isset( $data['customer_name'] ) ) {
            $this->customer_name = sanitize_text_field( $data['customer_name'] );
        }

        if ( isset( $data['customer_email'] ) ) {
            $this->customer_email = sanitize_email( $data['customer_email'] );
        }

        if ( isset( $data['customer_phone'] ) ) {
            $this->customer_phone = sanitize_text_field( $data['customer_phone'] );
        }

        if ( isset( $data['customer_address'] ) ) {
            $this->customer_address = sanitize_text_field( $data['customer_address'] );
        }

        if ( isset( $data['customer_city'] ) ) {
            $this->customer_city = sanitize_text_field( $data['customer_city'] );
        }

        if ( isset( $data['customer_state'] ) ) {
            $this->customer_state = sanitize_text_field( $data['customer_state'] );
        }

        if ( isset( $data['customer_zip'] ) ) {
            $this->customer_zip = sanitize_text_field( $data['customer_zip'] );
        }

        if ( isset( $data['customer_country'] ) ) {
            $this->customer_country = sanitize_text_field( $data['customer_country'] );
        }

        if ( isset( $data['customer_notes'] ) ) {
            $this->customer_notes = sanitize_textarea_field( $data['customer_notes'] );
        }

        // Update booking notes
        if ( isset( $data['booking_notes'] ) ) {
            $this->booking_notes = sanitize_textarea_field( $data['booking_notes'] );
        }

        // Check if the service exists
        if ( $this->service_id ) {
            $service = get_post( $this->service_id );
            if ( ! $service || 'aqualuxe_service' !== $service->post_type ) {
                return new \WP_Error( 'invalid_service', __( 'Invalid service', 'aqualuxe' ) );
            }
        }

        // Check if the date is valid
        if ( $this->date && ! $this->is_valid_date( $this->date ) ) {
            return new \WP_Error( 'invalid_date', __( 'Invalid date', 'aqualuxe' ) );
        }

        // Check if the time is valid
        if ( $this->time && ! $this->is_valid_time( $this->time ) ) {
            return new \WP_Error( 'invalid_time', __( 'Invalid time', 'aqualuxe' ) );
        }

        // Check if the service is available at the requested date and time
        if ( $this->service_id && $this->date && $this->time && ! $this->is_service_available() ) {
            return new \WP_Error( 'service_unavailable', __( 'Service is not available at the requested date and time', 'aqualuxe' ) );
        }

        // Update booking post title
        wp_update_post(
            [
                'ID'         => $this->id,
                'post_title' => sprintf( __( 'Booking for %s on %s at %s', 'aqualuxe' ), $this->customer_name, $this->date, $this->time ),
            ]
        );

        // Save booking data
        $this->save();

        // Update booking status
        if ( isset( $data['status'] ) ) {
            wp_set_object_terms( $this->id, $this->status, 'aqualuxe_booking_status' );
            
            // Send status update notification
            $this->send_notifications( 'status_update' );
        }

        return true;
    }

    /**
     * Save booking data
     *
     * @return void
     */
    private function save() {
        // Save booking data
        update_post_meta( $this->id, '_service_id', $this->service_id );
        update_post_meta( $this->id, '_date', $this->date );
        update_post_meta( $this->id, '_time', $this->time );
        update_post_meta( $this->id, '_duration', $this->duration );
        
        // Save customer data
        update_post_meta( $this->id, '_customer_name', $this->customer_name );
        update_post_meta( $this->id, '_customer_email', $this->customer_email );
        update_post_meta( $this->id, '_customer_phone', $this->customer_phone );
        update_post_meta( $this->id, '_customer_address', $this->customer_address );
        update_post_meta( $this->id, '_customer_city', $this->customer_city );
        update_post_meta( $this->id, '_customer_state', $this->customer_state );
        update_post_meta( $this->id, '_customer_zip', $this->customer_zip );
        update_post_meta( $this->id, '_customer_country', $this->customer_country );
        update_post_meta( $this->id, '_customer_notes', $this->customer_notes );
        
        // Save booking notes
        update_post_meta( $this->id, '_booking_notes', $this->booking_notes );
    }

    /**
     * Delete booking
     *
     * @return bool|WP_Error
     */
    public function delete() {
        // Check if booking exists
        if ( ! $this->id ) {
            return new \WP_Error( 'invalid_booking', __( 'Invalid booking', 'aqualuxe' ) );
        }

        // Send cancellation notification
        $this->send_notifications( 'cancelled' );

        // Delete booking
        $result = wp_delete_post( $this->id, true );

        if ( ! $result ) {
            return new \WP_Error( 'delete_failed', __( 'Failed to delete booking', 'aqualuxe' ) );
        }

        return true;
    }

    /**
     * Check if the date is valid
     *
     * @param string $date Date.
     * @return bool
     */
    private function is_valid_date( $date ) {
        // Check if the date is in the correct format (YYYY-MM-DD)
        if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $date ) ) {
            return false;
        }

        // Check if the date is valid
        $date_parts = explode( '-', $date );
        if ( ! checkdate( $date_parts[1], $date_parts[2], $date_parts[0] ) ) {
            return false;
        }

        // Check if the date is in the future
        $today = date( 'Y-m-d' );
        if ( $date < $today ) {
            return false;
        }

        // Check minimum notice
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $minimum_notice = isset( $settings['minimum_notice'] ) ? $settings['minimum_notice'] : 24;
        
        $booking_datetime = strtotime( $date . ' ' . $this->time );
        $minimum_datetime = strtotime( '+' . $minimum_notice . ' hours' );
        
        if ( $booking_datetime < $minimum_datetime ) {
            return false;
        }

        // Check maximum notice
        $maximum_notice = isset( $settings['maximum_notice'] ) ? $settings['maximum_notice'] : 90;
        $maximum_datetime = strtotime( '+' . $maximum_notice . ' days' );
        
        if ( $booking_datetime > $maximum_datetime ) {
            return false;
        }

        return true;
    }

    /**
     * Check if the time is valid
     *
     * @param string $time Time.
     * @return bool
     */
    private function is_valid_time( $time ) {
        // Check if the time is in the correct format (HH:MM)
        if ( ! preg_match( '/^\d{2}:\d{2}$/', $time ) ) {
            return false;
        }

        // Check if the time is valid
        $time_parts = explode( ':', $time );
        $hour = intval( $time_parts[0] );
        $minute = intval( $time_parts[1] );
        
        if ( $hour < 0 || $hour > 23 || $minute < 0 || $minute > 59 ) {
            return false;
        }

        return true;
    }

    /**
     * Check if the service is available at the requested date and time
     *
     * @return bool
     */
    private function is_service_available() {
        // Get service availability
        $availability = get_post_meta( $this->service_id, '_availability', true );
        
        if ( empty( $availability ) ) {
            return false;
        }

        // Get day of week
        $day_of_week = strtolower( date( 'l', strtotime( $this->date ) ) );

        // Check if the day is enabled
        if ( ! isset( $availability[ $day_of_week ] ) || ! $availability[ $day_of_week ]['enabled'] ) {
            return false;
        }

        // Check if there are slots for the day
        if ( empty( $availability[ $day_of_week ]['slots'] ) ) {
            return false;
        }

        // Check if the time is within any of the slots
        $time_minutes = $this->time_to_minutes( $this->time );
        $duration = $this->duration ? $this->duration : $this->get_service_duration();
        $end_time_minutes = $time_minutes + $duration;

        foreach ( $availability[ $day_of_week ]['slots'] as $slot ) {
            $slot_start_minutes = $this->time_to_minutes( $slot['start'] );
            $slot_end_minutes = $this->time_to_minutes( $slot['end'] );

            if ( $time_minutes >= $slot_start_minutes && $end_time_minutes <= $slot_end_minutes ) {
                // Check if there are any overlapping bookings
                if ( ! $this->has_overlapping_bookings() ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if there are any overlapping bookings
     *
     * @return bool
     */
    private function has_overlapping_bookings() {
        // Get booking start and end times
        $start_time = $this->time_to_minutes( $this->time );
        $duration = $this->duration ? $this->duration : $this->get_service_duration();
        $end_time = $start_time + $duration;

        // Get existing bookings for the same service and date
        $args = [
            'post_type'      => 'aqualuxe_booking',
            'posts_per_page' => -1,
            'meta_query'     => [
                [
                    'key'   => '_service_id',
                    'value' => $this->service_id,
                ],
                [
                    'key'   => '_date',
                    'value' => $this->date,
                ],
            ],
            'tax_query'      => [
                [
                    'taxonomy' => 'aqualuxe_booking_status',
                    'field'    => 'slug',
                    'terms'    => [ 'pending', 'confirmed' ],
                ],
            ],
        ];

        // Exclude current booking if updating
        if ( $this->id ) {
            $args['post__not_in'] = [ $this->id ];
        }

        $bookings = new \WP_Query( $args );

        if ( $bookings->have_posts() ) {
            while ( $bookings->have_posts() ) {
                $bookings->the_post();
                $booking_id = get_the_ID();
                $booking_time = get_post_meta( $booking_id, '_time', true );
                $booking_duration = get_post_meta( $booking_id, '_duration', true );
                
                if ( ! $booking_duration ) {
                    $booking_service_id = get_post_meta( $booking_id, '_service_id', true );
                    $booking_duration = get_post_meta( $booking_service_id, '_duration', true );
                }

                $booking_start_time = $this->time_to_minutes( $booking_time );
                $booking_end_time = $booking_start_time + $booking_duration;

                // Check if bookings overlap
                if ( ( $start_time >= $booking_start_time && $start_time < $booking_end_time ) ||
                     ( $end_time > $booking_start_time && $end_time <= $booking_end_time ) ||
                     ( $start_time <= $booking_start_time && $end_time >= $booking_end_time ) ) {
                    wp_reset_postdata();
                    return true;
                }
            }
        }

        wp_reset_postdata();
        return false;
    }

    /**
     * Convert time to minutes
     *
     * @param string $time Time in HH:MM format.
     * @return int
     */
    private function time_to_minutes( $time ) {
        $time_parts = explode( ':', $time );
        return ( intval( $time_parts[0] ) * 60 ) + intval( $time_parts[1] );
    }

    /**
     * Get service duration
     *
     * @return int
     */
    private function get_service_duration() {
        $duration = get_post_meta( $this->service_id, '_duration', true );
        return $duration ? intval( $duration ) : 60; // Default to 60 minutes
    }

    /**
     * Send notifications
     *
     * @param string $type Notification type.
     * @return void
     */
    private function send_notifications( $type ) {
        // Get settings
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $admin_notification = isset( $settings['admin_notification'] ) ? $settings['admin_notification'] : true;
        $customer_notification = isset( $settings['customer_notification'] ) ? $settings['customer_notification'] : true;

        // Send admin notification
        if ( $admin_notification ) {
            $this->send_admin_notification( $type );
        }

        // Send customer notification
        if ( $customer_notification ) {
            $this->send_customer_notification( $type );
        }
    }

    /**
     * Send admin notification
     *
     * @param string $type Notification type.
     * @return void
     */
    private function send_admin_notification( $type ) {
        // Get admin email
        $admin_email = get_option( 'admin_email' );

        // Get service name
        $service_name = get_the_title( $this->service_id );

        // Set subject and message based on notification type
        switch ( $type ) {
            case 'new':
                $subject = sprintf( __( 'New Booking: %s', 'aqualuxe' ), $service_name );
                $message = sprintf(
                    __( "A new booking has been made:\n\nService: %s\nDate: %s\nTime: %s\nCustomer: %s\nEmail: %s\nPhone: %s\n\nView Booking: %s", 'aqualuxe' ),
                    $service_name,
                    $this->date,
                    $this->time,
                    $this->customer_name,
                    $this->customer_email,
                    $this->customer_phone,
                    admin_url( 'post.php?post=' . $this->id . '&action=edit' )
                );
                break;

            case 'status_update':
                $subject = sprintf( __( 'Booking Status Updated: %s', 'aqualuxe' ), $service_name );
                $message = sprintf(
                    __( "A booking status has been updated:\n\nService: %s\nDate: %s\nTime: %s\nCustomer: %s\nEmail: %s\nPhone: %s\nStatus: %s\n\nView Booking: %s", 'aqualuxe' ),
                    $service_name,
                    $this->date,
                    $this->time,
                    $this->customer_name,
                    $this->customer_email,
                    $this->customer_phone,
                    ucfirst( $this->status ),
                    admin_url( 'post.php?post=' . $this->id . '&action=edit' )
                );
                break;

            case 'cancelled':
                $subject = sprintf( __( 'Booking Cancelled: %s', 'aqualuxe' ), $service_name );
                $message = sprintf(
                    __( "A booking has been cancelled:\n\nService: %s\nDate: %s\nTime: %s\nCustomer: %s\nEmail: %s\nPhone: %s", 'aqualuxe' ),
                    $service_name,
                    $this->date,
                    $this->time,
                    $this->customer_name,
                    $this->customer_email,
                    $this->customer_phone
                );
                break;

            default:
                return;
        }

        // Send email
        wp_mail( $admin_email, $subject, $message );
    }

    /**
     * Send customer notification
     *
     * @param string $type Notification type.
     * @return void
     */
    private function send_customer_notification( $type ) {
        // Get service name
        $service_name = get_the_title( $this->service_id );

        // Get site name
        $site_name = get_bloginfo( 'name' );

        // Set subject and message based on notification type
        switch ( $type ) {
            case 'new':
                $subject = sprintf( __( '%s: Your Booking Confirmation', 'aqualuxe' ), $site_name );
                $message = sprintf(
                    __( "Dear %s,\n\nThank you for booking with us. Your booking details are as follows:\n\nService: %s\nDate: %s\nTime: %s\n\nWe look forward to seeing you!\n\nBest regards,\n%s", 'aqualuxe' ),
                    $this->customer_name,
                    $service_name,
                    $this->date,
                    $this->time,
                    $site_name
                );
                break;

            case 'status_update':
                $subject = sprintf( __( '%s: Your Booking Status Update', 'aqualuxe' ), $site_name );
                $message = sprintf(
                    __( "Dear %s,\n\nYour booking status has been updated to: %s\n\nBooking details:\nService: %s\nDate: %s\nTime: %s\n\nBest regards,\n%s", 'aqualuxe' ),
                    $this->customer_name,
                    ucfirst( $this->status ),
                    $service_name,
                    $this->date,
                    $this->time,
                    $site_name
                );
                break;

            case 'cancelled':
                $subject = sprintf( __( '%s: Your Booking Cancellation', 'aqualuxe' ), $site_name );
                $message = sprintf(
                    __( "Dear %s,\n\nYour booking has been cancelled:\n\nService: %s\nDate: %s\nTime: %s\n\nIf you did not request this cancellation, please contact us.\n\nBest regards,\n%s", 'aqualuxe' ),
                    $this->customer_name,
                    $service_name,
                    $this->date,
                    $this->time,
                    $site_name
                );
                break;

            default:
                return;
        }

        // Send email
        wp_mail( $this->customer_email, $subject, $message );
    }

    /**
     * Get booking ID
     *
     * @return int
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Get service ID
     *
     * @return int
     */
    public function get_service_id() {
        return $this->service_id;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function get_date() {
        return $this->date;
    }

    /**
     * Get time
     *
     * @return string
     */
    public function get_time() {
        return $this->time;
    }

    /**
     * Get duration
     *
     * @return int
     */
    public function get_duration() {
        return $this->duration;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function get_status() {
        return $this->status;
    }

    /**
     * Get customer name
     *
     * @return string
     */
    public function get_customer_name() {
        return $this->customer_name;
    }

    /**
     * Get customer email
     *
     * @return string
     */
    public function get_customer_email() {
        return $this->customer_email;
    }

    /**
     * Get customer phone
     *
     * @return string
     */
    public function get_customer_phone() {
        return $this->customer_phone;
    }

    /**
     * Get customer address
     *
     * @return string
     */
    public function get_customer_address() {
        return $this->customer_address;
    }

    /**
     * Get customer city
     *
     * @return string
     */
    public function get_customer_city() {
        return $this->customer_city;
    }

    /**
     * Get customer state
     *
     * @return string
     */
    public function get_customer_state() {
        return $this->customer_state;
    }

    /**
     * Get customer zip
     *
     * @return string
     */
    public function get_customer_zip() {
        return $this->customer_zip;
    }

    /**
     * Get customer country
     *
     * @return string
     */
    public function get_customer_country() {
        return $this->customer_country;
    }

    /**
     * Get customer notes
     *
     * @return string
     */
    public function get_customer_notes() {
        return $this->customer_notes;
    }

    /**
     * Get booking notes
     *
     * @return string
     */
    public function get_booking_notes() {
        return $this->booking_notes;
    }

    /**
     * Get service name
     *
     * @return string
     */
    public function get_service_name() {
        return get_the_title( $this->service_id );
    }

    /**
     * Get formatted date
     *
     * @return string
     */
    public function get_formatted_date() {
        // Get date format
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $date_format = isset( $settings['date_format'] ) ? $settings['date_format'] : 'mm/dd/yyyy';

        // Format date
        $date_obj = new \DateTime( $this->date );
        
        switch ( $date_format ) {
            case 'dd/mm/yyyy':
                return $date_obj->format( 'd/m/Y' );
            case 'yyyy-mm-dd':
                return $date_obj->format( 'Y-m-d' );
            case 'mm/dd/yyyy':
            default:
                return $date_obj->format( 'm/d/Y' );
        }
    }

    /**
     * Get formatted time
     *
     * @return string
     */
    public function get_formatted_time() {
        // Get time format
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $time_format = isset( $settings['time_format'] ) ? $settings['time_format'] : '12';

        // Format time
        $time_obj = new \DateTime( $this->date . ' ' . $this->time );
        
        if ( $time_format === '12' ) {
            return $time_obj->format( 'g:i A' );
        } else {
            return $time_obj->format( 'H:i' );
        }
    }

    /**
     * Get formatted status
     *
     * @return string
     */
    public function get_formatted_status() {
        return ucfirst( $this->status );
    }

    /**
     * Get formatted duration
     *
     * @return string
     */
    public function get_formatted_duration() {
        $duration = $this->duration ? $this->duration : $this->get_service_duration();
        
        if ( $duration < 60 ) {
            return sprintf( _n( '%d minute', '%d minutes', $duration, 'aqualuxe' ), $duration );
        } else {
            $hours = floor( $duration / 60 );
            $minutes = $duration % 60;
            
            if ( $minutes === 0 ) {
                return sprintf( _n( '%d hour', '%d hours', $hours, 'aqualuxe' ), $hours );
            } else {
                return sprintf( __( '%d hour %d minutes', 'aqualuxe' ), $hours, $minutes );
            }
        }
    }

    /**
     * Get booking URL
     *
     * @return string
     */
    public function get_url() {
        return admin_url( 'post.php?post=' . $this->id . '&action=edit' );
    }
}