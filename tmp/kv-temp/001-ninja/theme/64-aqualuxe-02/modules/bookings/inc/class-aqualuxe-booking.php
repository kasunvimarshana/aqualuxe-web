<?php
/**
 * AquaLuxe Booking Class
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * AquaLuxe Booking Class
 */
class AquaLuxe_Booking {
    /**
     * Create a new booking
     *
     * @param array $args Booking arguments
     * @return int|false Booking ID or false on failure
     */
    public function create( $args = array() ) {
        // Default arguments
        $defaults = array(
            'service_id'     => 0,
            'resource_id'    => 0,
            'date'           => '',
            'time'           => '',
            'customer_name'  => '',
            'customer_email' => '',
            'customer_phone' => '',
            'notes'          => '',
            'status'         => 'pending',
            'order_id'       => 0,
        );

        // Parse arguments
        $args = wp_parse_args( $args, $defaults );

        // Validate required fields
        if ( empty( $args['service_id'] ) || empty( $args['date'] ) || empty( $args['time'] ) || empty( $args['customer_name'] ) || empty( $args['customer_email'] ) ) {
            return false;
        }

        // Check if service exists
        $service = get_post( $args['service_id'] );
        if ( ! $service || 'aqualuxe_service' !== $service->post_type ) {
            return false;
        }

        // Check if resource exists (if provided)
        if ( ! empty( $args['resource_id'] ) ) {
            $resource = get_post( $args['resource_id'] );
            if ( ! $resource || 'aqualuxe_resource' !== $resource->post_type ) {
                return false;
            }
        }

        // Check availability
        $availability = new AquaLuxe_Booking_Availability();
        if ( ! $availability->is_available( $args['service_id'], $args['resource_id'], $args['date'], $args['time'] ) ) {
            return false;
        }

        // Create booking post
        $booking_data = array(
            'post_title'    => sprintf( __( 'Booking for %s on %s at %s', 'aqualuxe' ), $args['customer_name'], $args['date'], $args['time'] ),
            'post_content'  => $args['notes'],
            'post_status'   => 'publish',
            'post_type'     => 'aqualuxe_booking',
        );

        // Insert booking post
        $booking_id = wp_insert_post( $booking_data );

        if ( ! $booking_id ) {
            return false;
        }

        // Add booking meta data
        update_post_meta( $booking_id, '_service_id', $args['service_id'] );
        update_post_meta( $booking_id, '_resource_id', $args['resource_id'] );
        update_post_meta( $booking_id, '_date', $args['date'] );
        update_post_meta( $booking_id, '_time', $args['time'] );
        update_post_meta( $booking_id, '_customer_name', $args['customer_name'] );
        update_post_meta( $booking_id, '_customer_email', $args['customer_email'] );
        update_post_meta( $booking_id, '_customer_phone', $args['customer_phone'] );
        update_post_meta( $booking_id, '_order_id', $args['order_id'] );

        // Calculate end time
        $service_duration = get_post_meta( $args['service_id'], '_duration', true );
        if ( ! $service_duration ) {
            $service_duration = 60; // Default to 60 minutes
        }

        $start_time = strtotime( $args['date'] . ' ' . $args['time'] );
        $end_time = strtotime( '+' . $service_duration . ' minutes', $start_time );
        $end_time_formatted = date( 'H:i', $end_time );

        update_post_meta( $booking_id, '_end_time', $end_time_formatted );
        update_post_meta( $booking_id, '_duration', $service_duration );

        // Set booking status
        wp_set_object_terms( $booking_id, $args['status'], 'aqualuxe_booking_status' );

        // Add user ID if logged in
        if ( is_user_logged_in() ) {
            update_post_meta( $booking_id, '_user_id', get_current_user_id() );
        }

        // Generate unique booking reference
        $booking_reference = $this->generate_booking_reference( $booking_id );
        update_post_meta( $booking_id, '_booking_reference', $booking_reference );

        // Add price
        $price = get_post_meta( $args['service_id'], '_price', true );
        update_post_meta( $booking_id, '_price', $price );

        // Add created date
        update_post_meta( $booking_id, '_created', current_time( 'mysql' ) );

        // Add to Google Calendar if enabled
        if ( get_option( 'aqualuxe_bookings_google_calendar_integration', false ) ) {
            $this->add_to_google_calendar( $booking_id );
        }

        // Fire action
        do_action( 'aqualuxe_booking_created', $booking_id, $args );

        return $booking_id;
    }

    /**
     * Update booking status
     *
     * @param int    $booking_id Booking ID
     * @param string $status     Booking status
     * @return bool Success
     */
    public function update_status( $booking_id, $status ) {
        // Check if booking exists
        $booking = get_post( $booking_id );
        if ( ! $booking || 'aqualuxe_booking' !== $booking->post_type ) {
            return false;
        }

        // Check if status is valid
        $valid_statuses = array( 'pending', 'confirmed', 'completed', 'cancelled', 'no-show' );
        if ( ! in_array( $status, $valid_statuses, true ) ) {
            return false;
        }

        // Update booking status
        wp_set_object_terms( $booking_id, $status, 'aqualuxe_booking_status' );

        // Update modified date
        update_post_meta( $booking_id, '_modified', current_time( 'mysql' ) );

        // Fire action
        do_action( 'aqualuxe_booking_status_updated', $booking_id, $status );

        return true;
    }

    /**
     * Get booking by ID
     *
     * @param int $booking_id Booking ID
     * @return array|false Booking data or false on failure
     */
    public function get( $booking_id ) {
        // Check if booking exists
        $booking = get_post( $booking_id );
        if ( ! $booking || 'aqualuxe_booking' !== $booking->post_type ) {
            return false;
        }

        // Get booking meta data
        $service_id = get_post_meta( $booking_id, '_service_id', true );
        $resource_id = get_post_meta( $booking_id, '_resource_id', true );
        $date = get_post_meta( $booking_id, '_date', true );
        $time = get_post_meta( $booking_id, '_time', true );
        $end_time = get_post_meta( $booking_id, '_end_time', true );
        $duration = get_post_meta( $booking_id, '_duration', true );
        $customer_name = get_post_meta( $booking_id, '_customer_name', true );
        $customer_email = get_post_meta( $booking_id, '_customer_email', true );
        $customer_phone = get_post_meta( $booking_id, '_customer_phone', true );
        $user_id = get_post_meta( $booking_id, '_user_id', true );
        $order_id = get_post_meta( $booking_id, '_order_id', true );
        $booking_reference = get_post_meta( $booking_id, '_booking_reference', true );
        $price = get_post_meta( $booking_id, '_price', true );
        $created = get_post_meta( $booking_id, '_created', true );
        $modified = get_post_meta( $booking_id, '_modified', true );

        // Get booking status
        $status_terms = wp_get_object_terms( $booking_id, 'aqualuxe_booking_status' );
        $status = ! empty( $status_terms ) ? $status_terms[0]->slug : 'pending';

        // Get service and resource data
        $service = get_post( $service_id );
        $service_name = $service ? $service->post_title : '';

        $resource = $resource_id ? get_post( $resource_id ) : null;
        $resource_name = $resource ? $resource->post_title : '';

        // Return booking data
        return array(
            'id'              => $booking_id,
            'service_id'      => $service_id,
            'service_name'    => $service_name,
            'resource_id'     => $resource_id,
            'resource_name'   => $resource_name,
            'date'            => $date,
            'time'            => $time,
            'end_time'        => $end_time,
            'duration'        => $duration,
            'customer_name'   => $customer_name,
            'customer_email'  => $customer_email,
            'customer_phone'  => $customer_phone,
            'user_id'         => $user_id,
            'order_id'        => $order_id,
            'booking_reference' => $booking_reference,
            'price'           => $price,
            'status'          => $status,
            'notes'           => $booking->post_content,
            'created'         => $created,
            'modified'        => $modified,
        );
    }

    /**
     * Get bookings by date
     *
     * @param string $date Date in Y-m-d format
     * @return array Bookings
     */
    public function get_by_date( $date ) {
        // Query bookings
        $args = array(
            'post_type'      => 'aqualuxe_booking',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_date',
                    'value'   => $date,
                    'compare' => '=',
                ),
            ),
        );

        $query = new WP_Query( $args );
        $bookings = array();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $booking_id = get_the_ID();
                $booking = $this->get( $booking_id );
                if ( $booking ) {
                    $bookings[] = $booking;
                }
            }
            wp_reset_postdata();
        }

        return $bookings;
    }

    /**
     * Get bookings by service
     *
     * @param int $service_id Service ID
     * @return array Bookings
     */
    public function get_by_service( $service_id ) {
        // Query bookings
        $args = array(
            'post_type'      => 'aqualuxe_booking',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_service_id',
                    'value'   => $service_id,
                    'compare' => '=',
                ),
            ),
        );

        $query = new WP_Query( $args );
        $bookings = array();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $booking_id = get_the_ID();
                $booking = $this->get( $booking_id );
                if ( $booking ) {
                    $bookings[] = $booking;
                }
            }
            wp_reset_postdata();
        }

        return $bookings;
    }

    /**
     * Get bookings by resource
     *
     * @param int $resource_id Resource ID
     * @return array Bookings
     */
    public function get_by_resource( $resource_id ) {
        // Query bookings
        $args = array(
            'post_type'      => 'aqualuxe_booking',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_resource_id',
                    'value'   => $resource_id,
                    'compare' => '=',
                ),
            ),
        );

        $query = new WP_Query( $args );
        $bookings = array();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $booking_id = get_the_ID();
                $booking = $this->get( $booking_id );
                if ( $booking ) {
                    $bookings[] = $booking;
                }
            }
            wp_reset_postdata();
        }

        return $bookings;
    }

    /**
     * Get bookings by user
     *
     * @param int $user_id User ID
     * @return array Bookings
     */
    public function get_by_user( $user_id ) {
        // Query bookings
        $args = array(
            'post_type'      => 'aqualuxe_booking',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_user_id',
                    'value'   => $user_id,
                    'compare' => '=',
                ),
            ),
        );

        $query = new WP_Query( $args );
        $bookings = array();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $booking_id = get_the_ID();
                $booking = $this->get( $booking_id );
                if ( $booking ) {
                    $bookings[] = $booking;
                }
            }
            wp_reset_postdata();
        }

        return $bookings;
    }

    /**
     * Get bookings by email
     *
     * @param string $email Email address
     * @return array Bookings
     */
    public function get_by_email( $email ) {
        // Query bookings
        $args = array(
            'post_type'      => 'aqualuxe_booking',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_customer_email',
                    'value'   => $email,
                    'compare' => '=',
                ),
            ),
        );

        $query = new WP_Query( $args );
        $bookings = array();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $booking_id = get_the_ID();
                $booking = $this->get( $booking_id );
                if ( $booking ) {
                    $bookings[] = $booking;
                }
            }
            wp_reset_postdata();
        }

        return $bookings;
    }

    /**
     * Get bookings by status
     *
     * @param string $status Booking status
     * @return array Bookings
     */
    public function get_by_status( $status ) {
        // Query bookings
        $args = array(
            'post_type'      => 'aqualuxe_booking',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'aqualuxe_booking_status',
                    'field'    => 'slug',
                    'terms'    => $status,
                ),
            ),
        );

        $query = new WP_Query( $args );
        $bookings = array();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $booking_id = get_the_ID();
                $booking = $this->get( $booking_id );
                if ( $booking ) {
                    $bookings[] = $booking;
                }
            }
            wp_reset_postdata();
        }

        return $bookings;
    }

    /**
     * Get bookings by date range
     *
     * @param string $start_date Start date in Y-m-d format
     * @param string $end_date   End date in Y-m-d format
     * @return array Bookings
     */
    public function get_by_date_range( $start_date, $end_date ) {
        // Query bookings
        $args = array(
            'post_type'      => 'aqualuxe_booking',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_date',
                    'value'   => array( $start_date, $end_date ),
                    'compare' => 'BETWEEN',
                    'type'    => 'DATE',
                ),
            ),
        );

        $query = new WP_Query( $args );
        $bookings = array();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $booking_id = get_the_ID();
                $booking = $this->get( $booking_id );
                if ( $booking ) {
                    $bookings[] = $booking;
                }
            }
            wp_reset_postdata();
        }

        return $bookings;
    }

    /**
     * Get upcoming bookings
     *
     * @param int $limit Number of bookings to get
     * @return array Bookings
     */
    public function get_upcoming( $limit = 10 ) {
        // Get current date
        $current_date = date( 'Y-m-d' );

        // Query bookings
        $args = array(
            'post_type'      => 'aqualuxe_booking',
            'post_status'    => 'publish',
            'posts_per_page' => $limit,
            'meta_query'     => array(
                array(
                    'key'     => '_date',
                    'value'   => $current_date,
                    'compare' => '>=',
                    'type'    => 'DATE',
                ),
            ),
            'meta_key'       => '_date',
            'orderby'        => 'meta_value',
            'order'          => 'ASC',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'aqualuxe_booking_status',
                    'field'    => 'slug',
                    'terms'    => array( 'pending', 'confirmed' ),
                ),
            ),
        );

        $query = new WP_Query( $args );
        $bookings = array();

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $booking_id = get_the_ID();
                $booking = $this->get( $booking_id );
                if ( $booking ) {
                    $bookings[] = $booking;
                }
            }
            wp_reset_postdata();
        }

        return $bookings;
    }

    /**
     * Send confirmation email
     *
     * @param int $booking_id Booking ID
     * @return bool Success
     */
    public function send_confirmation_email( $booking_id ) {
        // Get booking data
        $booking = $this->get( $booking_id );
        if ( ! $booking ) {
            return false;
        }

        // Get email template
        $template = get_option( 'aqualuxe_bookings_confirmation_email_template', '' );
        if ( empty( $template ) ) {
            $template = $this->get_default_confirmation_email_template();
        }

        // Replace placeholders
        $template = $this->replace_email_placeholders( $template, $booking );

        // Send email
        $subject = sprintf( __( 'Booking Confirmation: %s', 'aqualuxe' ), $booking['booking_reference'] );
        $headers = array( 'Content-Type: text/html; charset=UTF-8' );

        $sent = wp_mail( $booking['customer_email'], $subject, $template, $headers );

        // Log email sent
        if ( $sent ) {
            update_post_meta( $booking_id, '_confirmation_email_sent', current_time( 'mysql' ) );
        }

        return $sent;
    }

    /**
     * Send cancellation email
     *
     * @param int $booking_id Booking ID
     * @return bool Success
     */
    public function send_cancellation_email( $booking_id ) {
        // Get booking data
        $booking = $this->get( $booking_id );
        if ( ! $booking ) {
            return false;
        }

        // Get email template
        $template = get_option( 'aqualuxe_bookings_cancellation_email_template', '' );
        if ( empty( $template ) ) {
            $template = $this->get_default_cancellation_email_template();
        }

        // Replace placeholders
        $template = $this->replace_email_placeholders( $template, $booking );

        // Send email
        $subject = sprintf( __( 'Booking Cancellation: %s', 'aqualuxe' ), $booking['booking_reference'] );
        $headers = array( 'Content-Type: text/html; charset=UTF-8' );

        $sent = wp_mail( $booking['customer_email'], $subject, $template, $headers );

        // Log email sent
        if ( $sent ) {
            update_post_meta( $booking_id, '_cancellation_email_sent', current_time( 'mysql' ) );
        }

        return $sent;
    }

    /**
     * Send reminder email
     *
     * @param int $booking_id Booking ID
     * @return bool Success
     */
    public function send_reminder_email( $booking_id ) {
        // Get booking data
        $booking = $this->get( $booking_id );
        if ( ! $booking ) {
            return false;
        }

        // Get email template
        $template = get_option( 'aqualuxe_bookings_reminder_email_template', '' );
        if ( empty( $template ) ) {
            $template = $this->get_default_reminder_email_template();
        }

        // Replace placeholders
        $template = $this->replace_email_placeholders( $template, $booking );

        // Send email
        $subject = sprintf( __( 'Booking Reminder: %s', 'aqualuxe' ), $booking['booking_reference'] );
        $headers = array( 'Content-Type: text/html; charset=UTF-8' );

        $sent = wp_mail( $booking['customer_email'], $subject, $template, $headers );

        // Log email sent
        if ( $sent ) {
            update_post_meta( $booking_id, '_reminder_email_sent', current_time( 'mysql' ) );
        }

        return $sent;
    }

    /**
     * Replace email placeholders
     *
     * @param string $template Email template
     * @param array  $booking  Booking data
     * @return string Email content
     */
    private function replace_email_placeholders( $template, $booking ) {
        // Get site info
        $site_name = get_bloginfo( 'name' );
        $site_url = get_bloginfo( 'url' );

        // Format date and time
        $date_format = get_option( 'date_format' );
        $time_format = get_option( 'time_format' );
        $formatted_date = date_i18n( $date_format, strtotime( $booking['date'] ) );
        $formatted_time = date_i18n( $time_format, strtotime( $booking['time'] ) );
        $formatted_end_time = date_i18n( $time_format, strtotime( $booking['end_time'] ) );

        // Replace placeholders
        $placeholders = array(
            '{site_name}'        => $site_name,
            '{site_url}'         => $site_url,
            '{booking_id}'       => $booking['id'],
            '{booking_reference}' => $booking['booking_reference'],
            '{service_name}'     => $booking['service_name'],
            '{resource_name}'    => $booking['resource_name'],
            '{date}'             => $formatted_date,
            '{time}'             => $formatted_time,
            '{end_time}'         => $formatted_end_time,
            '{duration}'         => $booking['duration'],
            '{customer_name}'    => $booking['customer_name'],
            '{customer_email}'   => $booking['customer_email'],
            '{customer_phone}'   => $booking['customer_phone'],
            '{price}'            => wc_price( $booking['price'] ),
            '{status}'           => ucfirst( $booking['status'] ),
            '{notes}'            => $booking['notes'],
        );

        return str_replace( array_keys( $placeholders ), array_values( $placeholders ), $template );
    }

    /**
     * Get default confirmation email template
     *
     * @return string Email template
     */
    private function get_default_confirmation_email_template() {
        $template = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Booking Confirmation</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                h1 {
                    color: #0073aa;
                    border-bottom: 1px solid #eee;
                    padding-bottom: 10px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                table, th, td {
                    border: 1px solid #ddd;
                }
                th, td {
                    padding: 10px;
                    text-align: left;
                }
                th {
                    background-color: #f5f5f5;
                }
                .footer {
                    margin-top: 30px;
                    padding-top: 10px;
                    border-top: 1px solid #eee;
                    font-size: 12px;
                    color: #777;
                }
            </style>
        </head>
        <body>
            <h1>Booking Confirmation</h1>
            <p>Dear {customer_name},</p>
            <p>Thank you for your booking with {site_name}. Your booking has been confirmed.</p>
            
            <h2>Booking Details</h2>
            <table>
                <tr>
                    <th>Booking Reference</th>
                    <td>{booking_reference}</td>
                </tr>
                <tr>
                    <th>Service</th>
                    <td>{service_name}</td>
                </tr>
                <tr>
                    <th>Resource</th>
                    <td>{resource_name}</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{date}</td>
                </tr>
                <tr>
                    <th>Time</th>
                    <td>{time} - {end_time}</td>
                </tr>
                <tr>
                    <th>Duration</th>
                    <td>{duration} minutes</td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td>{price}</td>
                </tr>
            </table>
            
            <p>If you need to cancel or reschedule your booking, please contact us as soon as possible.</p>
            
            <p>We look forward to seeing you!</p>
            
            <p>Best regards,<br>
            {site_name} Team</p>
            
            <div class="footer">
                <p>This email was sent from {site_name} ({site_url}).</p>
            </div>
        </body>
        </html>
        ';

        return $template;
    }

    /**
     * Get default cancellation email template
     *
     * @return string Email template
     */
    private function get_default_cancellation_email_template() {
        $template = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Booking Cancellation</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                h1 {
                    color: #d63638;
                    border-bottom: 1px solid #eee;
                    padding-bottom: 10px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                table, th, td {
                    border: 1px solid #ddd;
                }
                th, td {
                    padding: 10px;
                    text-align: left;
                }
                th {
                    background-color: #f5f5f5;
                }
                .footer {
                    margin-top: 30px;
                    padding-top: 10px;
                    border-top: 1px solid #eee;
                    font-size: 12px;
                    color: #777;
                }
            </style>
        </head>
        <body>
            <h1>Booking Cancellation</h1>
            <p>Dear {customer_name},</p>
            <p>Your booking with {site_name} has been cancelled.</p>
            
            <h2>Booking Details</h2>
            <table>
                <tr>
                    <th>Booking Reference</th>
                    <td>{booking_reference}</td>
                </tr>
                <tr>
                    <th>Service</th>
                    <td>{service_name}</td>
                </tr>
                <tr>
                    <th>Resource</th>
                    <td>{resource_name}</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{date}</td>
                </tr>
                <tr>
                    <th>Time</th>
                    <td>{time} - {end_time}</td>
                </tr>
            </table>
            
            <p>If you would like to make a new booking, please visit our website or contact us.</p>
            
            <p>Best regards,<br>
            {site_name} Team</p>
            
            <div class="footer">
                <p>This email was sent from {site_name} ({site_url}).</p>
            </div>
        </body>
        </html>
        ';

        return $template;
    }

    /**
     * Get default reminder email template
     *
     * @return string Email template
     */
    private function get_default_reminder_email_template() {
        $template = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Booking Reminder</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                h1 {
                    color: #0073aa;
                    border-bottom: 1px solid #eee;
                    padding-bottom: 10px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                table, th, td {
                    border: 1px solid #ddd;
                }
                th, td {
                    padding: 10px;
                    text-align: left;
                }
                th {
                    background-color: #f5f5f5;
                }
                .footer {
                    margin-top: 30px;
                    padding-top: 10px;
                    border-top: 1px solid #eee;
                    font-size: 12px;
                    color: #777;
                }
            </style>
        </head>
        <body>
            <h1>Booking Reminder</h1>
            <p>Dear {customer_name},</p>
            <p>This is a friendly reminder about your upcoming booking with {site_name}.</p>
            
            <h2>Booking Details</h2>
            <table>
                <tr>
                    <th>Booking Reference</th>
                    <td>{booking_reference}</td>
                </tr>
                <tr>
                    <th>Service</th>
                    <td>{service_name}</td>
                </tr>
                <tr>
                    <th>Resource</th>
                    <td>{resource_name}</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{date}</td>
                </tr>
                <tr>
                    <th>Time</th>
                    <td>{time} - {end_time}</td>
                </tr>
                <tr>
                    <th>Duration</th>
                    <td>{duration} minutes</td>
                </tr>
            </table>
            
            <p>If you need to cancel or reschedule your booking, please contact us as soon as possible.</p>
            
            <p>We look forward to seeing you!</p>
            
            <p>Best regards,<br>
            {site_name} Team</p>
            
            <div class="footer">
                <p>This email was sent from {site_name} ({site_url}).</p>
            </div>
        </body>
        </html>
        ';

        return $template;
    }

    /**
     * Generate booking reference
     *
     * @param int $booking_id Booking ID
     * @return string Booking reference
     */
    private function generate_booking_reference( $booking_id ) {
        // Generate reference
        $prefix = apply_filters( 'aqualuxe_booking_reference_prefix', 'BK' );
        $reference = $prefix . '-' . date( 'Ymd' ) . '-' . str_pad( $booking_id, 4, '0', STR_PAD_LEFT );

        return $reference;
    }

    /**
     * Add booking to Google Calendar
     *
     * @param int $booking_id Booking ID
     * @return bool Success
     */
    private function add_to_google_calendar( $booking_id ) {
        // Check if Google Calendar integration is enabled
        if ( ! get_option( 'aqualuxe_bookings_google_calendar_integration', false ) ) {
            return false;
        }

        // Get booking data
        $booking = $this->get( $booking_id );
        if ( ! $booking ) {
            return false;
        }

        // TODO: Implement Google Calendar integration

        return true;
    }
}