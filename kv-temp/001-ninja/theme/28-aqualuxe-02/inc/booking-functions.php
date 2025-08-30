<?php
/**
 * Booking functionality for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Initialize booking functionality
 */
function aqualuxe_booking_init() {
    // Register booking post type if not already registered
    if ( ! post_type_exists( 'aqualuxe_booking' ) ) {
        // The post type is likely registered elsewhere, but we'll add a safety check
        // This is just a placeholder function to prevent fatal errors
    }
}
add_action( 'init', 'aqualuxe_booking_init' );

/**
 * Get booking status options
 *
 * @return array Array of booking status options
 */
function aqualuxe_get_booking_statuses() {
    return apply_filters( 'aqualuxe_booking_statuses', array(
        'pending'    => __( 'Pending', 'aqualuxe' ),
        'confirmed'  => __( 'Confirmed', 'aqualuxe' ),
        'completed'  => __( 'Completed', 'aqualuxe' ),
        'cancelled'  => __( 'Cancelled', 'aqualuxe' ),
        'no-show'    => __( 'No Show', 'aqualuxe' ),
    ) );
}

/**
 * Get booking status label
 *
 * @param string $status Booking status key
 * @return string Booking status label
 */
function aqualuxe_get_booking_status_label( $status ) {
    $statuses = aqualuxe_get_booking_statuses();
    return isset( $statuses[$status] ) ? $statuses[$status] : __( 'Unknown', 'aqualuxe' );
}

/**
 * Get available time slots
 *
 * @param string $date Date in Y-m-d format
 * @return array Array of available time slots
 */
function aqualuxe_get_available_time_slots( $date = '' ) {
    // Default time slots (9 AM to 5 PM, hourly)
    $default_slots = array(
        '09:00' => __( '9:00 AM', 'aqualuxe' ),
        '10:00' => __( '10:00 AM', 'aqualuxe' ),
        '11:00' => __( '11:00 AM', 'aqualuxe' ),
        '12:00' => __( '12:00 PM', 'aqualuxe' ),
        '13:00' => __( '1:00 PM', 'aqualuxe' ),
        '14:00' => __( '2:00 PM', 'aqualuxe' ),
        '15:00' => __( '3:00 PM', 'aqualuxe' ),
        '16:00' => __( '4:00 PM', 'aqualuxe' ),
    );
    
    // Allow filtering of time slots
    return apply_filters( 'aqualuxe_available_time_slots', $default_slots, $date );
}

/**
 * Check if a time slot is available
 *
 * @param string $date Date in Y-m-d format
 * @param string $time Time in H:i format
 * @return bool True if available, false if not
 */
function aqualuxe_is_time_slot_available( $date, $time ) {
    // This is a placeholder function
    // In a real implementation, this would check against existing bookings
    return true;
}

/**
 * Create a new booking
 *
 * @param array $booking_data Booking data
 * @return int|WP_Error Booking ID on success, WP_Error on failure
 */
function aqualuxe_create_booking( $booking_data ) {
    // Required fields
    $required_fields = array( 'name', 'email', 'date', 'time' );
    
    foreach ( $required_fields as $field ) {
        if ( empty( $booking_data[$field] ) ) {
            return new WP_Error( 'missing_field', sprintf( __( 'Missing required field: %s', 'aqualuxe' ), $field ) );
        }
    }
    
    // Create post
    $post_data = array(
        'post_title'    => sprintf( __( 'Booking: %s', 'aqualuxe' ), $booking_data['name'] ),
        'post_content'  => isset( $booking_data['notes'] ) ? $booking_data['notes'] : '',
        'post_status'   => 'publish',
        'post_type'     => 'aqualuxe_booking',
        'meta_input'    => array(
            '_booking_name'    => sanitize_text_field( $booking_data['name'] ),
            '_booking_email'   => sanitize_email( $booking_data['email'] ),
            '_booking_phone'   => isset( $booking_data['phone'] ) ? sanitize_text_field( $booking_data['phone'] ) : '',
            '_booking_date'    => sanitize_text_field( $booking_data['date'] ),
            '_booking_time'    => sanitize_text_field( $booking_data['time'] ),
            '_booking_status'  => 'pending',
        ),
    );
    
    // Insert post
    $booking_id = wp_insert_post( $post_data );
    
    if ( is_wp_error( $booking_id ) ) {
        return $booking_id;
    }
    
    // Send notification emails
    aqualuxe_send_booking_notification_emails( $booking_id );
    
    return $booking_id;
}

/**
 * Send booking notification emails
 *
 * @param int $booking_id Booking ID
 */
function aqualuxe_send_booking_notification_emails( $booking_id ) {
    // This is a placeholder function
    // In a real implementation, this would send emails to admin and customer
}

/**
 * Update booking status
 *
 * @param int $booking_id Booking ID
 * @param string $status New status
 * @return bool True on success, false on failure
 */
function aqualuxe_update_booking_status( $booking_id, $status ) {
    // Validate status
    $valid_statuses = array_keys( aqualuxe_get_booking_statuses() );
    
    if ( ! in_array( $status, $valid_statuses ) ) {
        return false;
    }
    
    // Update status
    $result = update_post_meta( $booking_id, '_booking_status', $status );
    
    // Trigger actions
    if ( $result ) {
        do_action( 'aqualuxe_booking_status_updated', $booking_id, $status );
    }
    
    return (bool) $result;
}

/**
 * Get booking details
 *
 * @param int $booking_id Booking ID
 * @return array|false Booking details or false if not found
 */
function aqualuxe_get_booking( $booking_id ) {
    $booking = get_post( $booking_id );
    
    if ( ! $booking || 'aqualuxe_booking' !== $booking->post_type ) {
        return false;
    }
    
    return array(
        'id'        => $booking->ID,
        'name'      => get_post_meta( $booking->ID, '_booking_name', true ),
        'email'     => get_post_meta( $booking->ID, '_booking_email', true ),
        'phone'     => get_post_meta( $booking->ID, '_booking_phone', true ),
        'date'      => get_post_meta( $booking->ID, '_booking_date', true ),
        'time'      => get_post_meta( $booking->ID, '_booking_time', true ),
        'status'    => get_post_meta( $booking->ID, '_booking_status', true ),
        'notes'     => $booking->post_content,
        'created'   => $booking->post_date,
    );
}

/**
 * Get bookings
 *
 * @param array $args Query arguments
 * @return array Array of bookings
 */
function aqualuxe_get_bookings( $args = array() ) {
    $defaults = array(
        'status'    => '',
        'date'      => '',
        'limit'     => 10,
        'offset'    => 0,
        'orderby'   => 'date',
        'order'     => 'DESC',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $query_args = array(
        'post_type'      => 'aqualuxe_booking',
        'posts_per_page' => $args['limit'],
        'offset'         => $args['offset'],
        'orderby'        => $args['orderby'],
        'order'          => $args['order'],
    );
    
    // Add meta query for status
    if ( ! empty( $args['status'] ) ) {
        $query_args['meta_query'][] = array(
            'key'     => '_booking_status',
            'value'   => $args['status'],
            'compare' => '=',
        );
    }
    
    // Add meta query for date
    if ( ! empty( $args['date'] ) ) {
        $query_args['meta_query'][] = array(
            'key'     => '_booking_date',
            'value'   => $args['date'],
            'compare' => '=',
        );
    }
    
    $bookings_query = new WP_Query( $query_args );
    $bookings = array();
    
    if ( $bookings_query->have_posts() ) {
        while ( $bookings_query->have_posts() ) {
            $bookings_query->the_post();
            $bookings[] = aqualuxe_get_booking( get_the_ID() );
        }
        wp_reset_postdata();
    }
    
    return $bookings;
}

/**
 * Register booking shortcodes
 */
function aqualuxe_register_booking_shortcodes() {
    add_shortcode( 'aqualuxe_booking_form', 'aqualuxe_booking_form_shortcode' );
}
add_action( 'init', 'aqualuxe_register_booking_shortcodes' );

/**
 * Booking form shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
function aqualuxe_booking_form_shortcode( $atts ) {
    $atts = shortcode_atts( array(
        'title' => __( 'Book an Appointment', 'aqualuxe' ),
    ), $atts, 'aqualuxe_booking_form' );
    
    ob_start();
    
    // Check if form was submitted
    if ( isset( $_POST['aqualuxe_booking_submit'] ) ) {
        // Process form submission
        // This is just a placeholder - in a real implementation, this would validate and save the booking
        echo '<div class="aqualuxe-booking-message success">' . __( 'Thank you for your booking request. We will contact you shortly to confirm your appointment.', 'aqualuxe' ) . '</div>';
    } else {
        // Display form
        ?>
        <div class="aqualuxe-booking-form-container">
            <h2><?php echo esc_html( $atts['title'] ); ?></h2>
            
            <form class="aqualuxe-booking-form" method="post">
                <div class="form-row">
                    <label for="booking_name"><?php esc_html_e( 'Your Name', 'aqualuxe' ); ?> <span class="required">*</span></label>
                    <input type="text" name="booking_name" id="booking_name" required>
                </div>
                
                <div class="form-row">
                    <label for="booking_email"><?php esc_html_e( 'Email Address', 'aqualuxe' ); ?> <span class="required">*</span></label>
                    <input type="email" name="booking_email" id="booking_email" required>
                </div>
                
                <div class="form-row">
                    <label for="booking_phone"><?php esc_html_e( 'Phone Number', 'aqualuxe' ); ?></label>
                    <input type="tel" name="booking_phone" id="booking_phone">
                </div>
                
                <div class="form-row">
                    <label for="booking_date"><?php esc_html_e( 'Preferred Date', 'aqualuxe' ); ?> <span class="required">*</span></label>
                    <input type="date" name="booking_date" id="booking_date" required>
                </div>
                
                <div class="form-row">
                    <label for="booking_time"><?php esc_html_e( 'Preferred Time', 'aqualuxe' ); ?> <span class="required">*</span></label>
                    <select name="booking_time" id="booking_time" required>
                        <option value=""><?php esc_html_e( 'Select a time', 'aqualuxe' ); ?></option>
                        <?php foreach ( aqualuxe_get_available_time_slots() as $value => $label ) : ?>
                            <option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-row">
                    <label for="booking_notes"><?php esc_html_e( 'Notes', 'aqualuxe' ); ?></label>
                    <textarea name="booking_notes" id="booking_notes" rows="4"></textarea>
                </div>
                
                <div class="form-row">
                    <button type="submit" name="aqualuxe_booking_submit" class="aqualuxe-button"><?php esc_html_e( 'Submit Booking', 'aqualuxe' ); ?></button>
                </div>
            </form>
        </div>
        <?php
    }
    
    return ob_get_clean();
}