<?php
/**
 * Booking Form Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Bookings;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Booking Form Class
 * 
 * This class handles the booking form functionality.
 */
class Booking_Form {
    /**
     * Instance of this class
     *
     * @var Booking_Form
     */
    private static $instance = null;

    /**
     * Form errors
     *
     * @var array
     */
    private $errors = [];

    /**
     * Form data
     *
     * @var array
     */
    private $data = [];

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Get the singleton instance
     *
     * @return Booking_Form
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Process form submission
        add_action( 'init', [ $this, 'process_form' ] );

        // AJAX handlers
        add_action( 'wp_ajax_aqualuxe_get_available_times', [ $this, 'ajax_get_available_times' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_get_available_times', [ $this, 'ajax_get_available_times' ] );

        add_action( 'wp_ajax_aqualuxe_validate_booking_form', [ $this, 'ajax_validate_booking_form' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_validate_booking_form', [ $this, 'ajax_validate_booking_form' ] );
    }

    /**
     * Process form submission
     *
     * @return void
     */
    public function process_form() {
        if ( ! isset( $_POST['aqualuxe_booking_form_nonce'] ) || ! wp_verify_nonce( $_POST['aqualuxe_booking_form_nonce'], 'aqualuxe_booking_form' ) ) {
            return;
        }

        // Get form data
        $this->data = [
            'bookable_id'    => isset( $_POST['bookable_id'] ) ? intval( $_POST['bookable_id'] ) : 0,
            'date'           => isset( $_POST['booking_date'] ) ? sanitize_text_field( $_POST['booking_date'] ) : '',
            'time'           => isset( $_POST['booking_time'] ) ? sanitize_text_field( $_POST['booking_time'] ) : '',
            'customer_name'  => isset( $_POST['customer_name'] ) ? sanitize_text_field( $_POST['customer_name'] ) : '',
            'customer_email' => isset( $_POST['customer_email'] ) ? sanitize_email( $_POST['customer_email'] ) : '',
            'customer_phone' => isset( $_POST['customer_phone'] ) ? sanitize_text_field( $_POST['customer_phone'] ) : '',
            'customer_notes' => isset( $_POST['customer_notes'] ) ? sanitize_textarea_field( $_POST['customer_notes'] ) : '',
        ];

        // Validate form data
        $this->validate_form();

        // If there are errors, store them in a session and redirect back to the form
        if ( ! empty( $this->errors ) ) {
            $this->store_errors();
            $this->store_data();
            wp_redirect( wp_get_referer() );
            exit;
        }

        // Create the booking
        $booking = Booking::create( $this->data );

        if ( is_wp_error( $booking ) ) {
            $this->errors['general'] = $booking->get_error_message();
            $this->store_errors();
            $this->store_data();
            wp_redirect( wp_get_referer() );
            exit;
        }

        // Send notifications
        $this->send_notifications( $booking );

        // Redirect to the confirmation page
        $settings = get_option( 'aqualuxe_booking_settings', [] );
        $confirmation_page = isset( $settings['confirmation_page'] ) ? $settings['confirmation_page'] : 0;

        if ( $confirmation_page ) {
            $redirect_url = add_query_arg( 'booking_id', $booking->get_id(), get_permalink( $confirmation_page ) );
        } else {
            $redirect_url = add_query_arg( 'booking_id', $booking->get_id(), wp_get_referer() );
        }

        wp_redirect( $redirect_url );
        exit;
    }

    /**
     * Validate form data
     *
     * @return void
     */
    private function validate_form() {
        // Check required fields
        $required_fields = [
            'bookable_id'    => __( 'Please select a bookable item.', 'aqualuxe' ),
            'date'           => __( 'Please select a date.', 'aqualuxe' ),
            'time'           => __( 'Please select a time.', 'aqualuxe' ),
            'customer_name'  => __( 'Please enter your name.', 'aqualuxe' ),
            'customer_email' => __( 'Please enter your email address.', 'aqualuxe' ),
        ];

        foreach ( $required_fields as $field => $message ) {
            if ( empty( $this->data[ $field ] ) ) {
                $this->errors[ $field ] = $message;
            }
        }

        // Validate email
        if ( ! empty( $this->data['customer_email'] ) && ! is_email( $this->data['customer_email'] ) ) {
            $this->errors['customer_email'] = __( 'Please enter a valid email address.', 'aqualuxe' );
        }

        // Check if the booking is available
        if ( ! empty( $this->data['bookable_id'] ) && ! empty( $this->data['date'] ) && ! empty( $this->data['time'] ) ) {
            if ( ! Booking::is_available( $this->data['bookable_id'], $this->data['date'], $this->data['time'] ) ) {
                $this->errors['time'] = __( 'The selected time slot is not available.', 'aqualuxe' );
            }
        }
    }

    /**
     * Store errors in a session
     *
     * @return void
     */
    private function store_errors() {
        if ( ! session_id() ) {
            session_start();
        }
        $_SESSION['aqualuxe_booking_form_errors'] = $this->errors;
    }

    /**
     * Store form data in a session
     *
     * @return void
     */
    private function store_data() {
        if ( ! session_id() ) {
            session_start();
        }
        $_SESSION['aqualuxe_booking_form_data'] = $this->data;
    }

    /**
     * Get errors from session
     *
     * @return array
     */
    public function get_errors() {
        if ( ! session_id() ) {
            session_start();
        }
        $errors = isset( $_SESSION['aqualuxe_booking_form_errors'] ) ? $_SESSION['aqualuxe_booking_form_errors'] : [];
        unset( $_SESSION['aqualuxe_booking_form_errors'] );
        return $errors;
    }

    /**
     * Get form data from session
     *
     * @return array
     */
    public function get_data() {
        if ( ! session_id() ) {
            session_start();
        }
        $data = isset( $_SESSION['aqualuxe_booking_form_data'] ) ? $_SESSION['aqualuxe_booking_form_data'] : [];
        unset( $_SESSION['aqualuxe_booking_form_data'] );
        return $data;
    }

    /**
     * Send notifications
     *
     * @param Booking $booking
     * @return void
     */
    private function send_notifications( $booking ) {
        $settings = get_option( 'aqualuxe_booking_settings', [] );

        // Send admin notification
        if ( isset( $settings['admin_notification'] ) && $settings['admin_notification'] ) {
            $this->send_admin_notification( $booking );
        }

        // Send customer notification
        if ( isset( $settings['customer_notification'] ) && $settings['customer_notification'] ) {
            $this->send_customer_notification( $booking );
        }
    }

    /**
     * Send admin notification
     *
     * @param Booking $booking
     * @return void
     */
    private function send_admin_notification( $booking ) {
        $to = get_option( 'admin_email' );
        $subject = sprintf( __( 'New Booking: #%s', 'aqualuxe' ), $booking->get_id() );

        $message = sprintf( __( 'A new booking has been made on your website.', 'aqualuxe' ) ) . "\n\n";
        $message .= sprintf( __( 'Booking ID: #%s', 'aqualuxe' ), $booking->get_id() ) . "\n";
        $message .= sprintf( __( 'Bookable Item: %s', 'aqualuxe' ), $booking->get_bookable_title() ) . "\n";
        $message .= sprintf( __( 'Date: %s', 'aqualuxe' ), $booking->get_date( get_option( 'date_format' ) ) ) . "\n";
        $message .= sprintf( __( 'Time: %s', 'aqualuxe' ), $booking->get_time( get_option( 'time_format' ) ) ) . "\n";
        $message .= sprintf( __( 'Status: %s', 'aqualuxe' ), $booking->get_status_label() ) . "\n\n";
        $message .= sprintf( __( 'Customer Name: %s', 'aqualuxe' ), $booking->get_customer_name() ) . "\n";
        $message .= sprintf( __( 'Customer Email: %s', 'aqualuxe' ), $booking->get_customer_email() ) . "\n";
        $message .= sprintf( __( 'Customer Phone: %s', 'aqualuxe' ), $booking->get_customer_phone() ) . "\n";
        $message .= sprintf( __( 'Customer Notes: %s', 'aqualuxe' ), $booking->get_customer_notes() ) . "\n\n";
        $message .= sprintf( __( 'View Booking: %s', 'aqualuxe' ), admin_url( 'post.php?post=' . $booking->get_id() . '&action=edit' ) ) . "\n";

        $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];

        wp_mail( $to, $subject, $message, $headers );
    }

    /**
     * Send customer notification
     *
     * @param Booking $booking
     * @return void
     */
    private function send_customer_notification( $booking ) {
        $to = $booking->get_customer_email();
        $subject = sprintf( __( 'Your Booking: #%s', 'aqualuxe' ), $booking->get_id() );

        $message = sprintf( __( 'Thank you for your booking.', 'aqualuxe' ) ) . "\n\n";
        $message .= sprintf( __( 'Booking ID: #%s', 'aqualuxe' ), $booking->get_id() ) . "\n";
        $message .= sprintf( __( 'Bookable Item: %s', 'aqualuxe' ), $booking->get_bookable_title() ) . "\n";
        $message .= sprintf( __( 'Date: %s', 'aqualuxe' ), $booking->get_date( get_option( 'date_format' ) ) ) . "\n";
        $message .= sprintf( __( 'Time: %s', 'aqualuxe' ), $booking->get_time( get_option( 'time_format' ) ) ) . "\n";
        $message .= sprintf( __( 'Status: %s', 'aqualuxe' ), $booking->get_status_label() ) . "\n\n";
        $message .= sprintf( __( 'Your Name: %s', 'aqualuxe' ), $booking->get_customer_name() ) . "\n";
        $message .= sprintf( __( 'Your Email: %s', 'aqualuxe' ), $booking->get_customer_email() ) . "\n";
        $message .= sprintf( __( 'Your Phone: %s', 'aqualuxe' ), $booking->get_customer_phone() ) . "\n";
        $message .= sprintf( __( 'Your Notes: %s', 'aqualuxe' ), $booking->get_customer_notes() ) . "\n\n";
        $message .= sprintf( __( 'Thank you for choosing our services.', 'aqualuxe' ) ) . "\n";
        $message .= get_bloginfo( 'name' ) . "\n";

        $headers = [ 'Content-Type: text/plain; charset=UTF-8' ];

        wp_mail( $to, $subject, $message, $headers );
    }

    /**
     * Ajax get available times
     *
     * @return void
     */
    public function ajax_get_available_times() {
        check_ajax_referer( 'aqualuxe_booking_form', 'nonce' );

        $bookable_id = isset( $_POST['bookable_id'] ) ? intval( $_POST['bookable_id'] ) : 0;
        $date = isset( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : '';

        if ( ! $bookable_id || ! $date ) {
            wp_send_json_error( [ 'message' => __( 'Invalid bookable ID or date.', 'aqualuxe' ) ] );
        }

        $bookable = get_post( $bookable_id );

        if ( ! $bookable || 'aqualuxe_bookable' !== $bookable->post_type ) {
            wp_send_json_error( [ 'message' => __( 'Bookable item not found.', 'aqualuxe' ) ] );
        }

        // Get availability for the day
        $day_of_week = strtolower( date( 'l', strtotime( $date ) ) );
        $availability = get_post_meta( $bookable_id, '_aqualuxe_bookable_availability', true );

        if ( ! is_array( $availability ) || ! isset( $availability[ $day_of_week ] ) || ! isset( $availability[ $day_of_week ]['enabled'] ) || ! $availability[ $day_of_week ]['enabled'] ) {
            wp_send_json_error( [ 'message' => __( 'Not available on this day.', 'aqualuxe' ) ] );
        }

        $start_time = isset( $availability[ $day_of_week ]['start'] ) ? $availability[ $day_of_week ]['start'] : '09:00';
        $end_time = isset( $availability[ $day_of_week ]['end'] ) ? $availability[ $day_of_week ]['end'] : '17:00';

        // Get duration
        $duration = get_post_meta( $bookable_id, '_aqualuxe_bookable_duration', true );
        if ( ! $duration ) {
            $duration = 60; // Default to 60 minutes
        }

        // Generate time slots
        $time_slots = [];
        $current_time = strtotime( $start_time );
        $end_time_timestamp = strtotime( $end_time );

        while ( $current_time < $end_time_timestamp ) {
            $time_slots[] = date( 'H:i', $current_time );
            $current_time += $duration * 60; // Convert duration to seconds
        }

        // Get existing bookings for this date and bookable item
        $existing_bookings = get_posts( [
            'post_type'      => 'aqualuxe_booking',
            'posts_per_page' => -1,
            'meta_query'     => [
                'relation' => 'AND',
                [
                    'key'   => '_aqualuxe_booking_bookable_id',
                    'value' => $bookable_id,
                ],
                [
                    'key'   => '_aqualuxe_booking_date',
                    'value' => $date,
                ],
                [
                    'key'     => '_aqualuxe_booking_status',
                    'value'   => [ 'pending', 'confirmed' ],
                    'compare' => 'IN',
                ],
            ],
        ] );

        // Get capacity
        $capacity = get_post_meta( $bookable_id, '_aqualuxe_bookable_capacity', true );
        if ( ! $capacity ) {
            $capacity = 1; // Default to 1
        }

        // Count bookings per time slot
        $bookings_count = [];
        foreach ( $existing_bookings as $booking ) {
            $booking_time = get_post_meta( $booking->ID, '_aqualuxe_booking_time', true );
            if ( $booking_time ) {
                $booking_time = date( 'H:i', strtotime( $booking_time ) );
                if ( ! isset( $bookings_count[ $booking_time ] ) ) {
                    $bookings_count[ $booking_time ] = 0;
                }
                $bookings_count[ $booking_time ]++;
            }
        }

        // Prepare available slots
        $available_slots = [];
        foreach ( $time_slots as $slot ) {
            $count = isset( $bookings_count[ $slot ] ) ? $bookings_count[ $slot ] : 0;
            $available_slots[] = [
                'time'      => $slot,
                'formatted' => date_i18n( get_option( 'time_format' ), strtotime( $slot ) ),
                'available' => $count < $capacity,
                'count'     => $count,
                'capacity'  => $capacity,
            ];
        }

        wp_send_json_success( [
            'date'            => date_i18n( get_option( 'date_format' ), strtotime( $date ) ),
            'day_of_week'     => $day_of_week,
            'start_time'      => $start_time,
            'end_time'        => $end_time,
            'duration'        => $duration,
            'available_slots' => $available_slots,
        ] );
    }

    /**
     * Ajax validate booking form
     *
     * @return void
     */
    public function ajax_validate_booking_form() {
        check_ajax_referer( 'aqualuxe_booking_form', 'nonce' );

        // Get form data
        $this->data = [
            'bookable_id'    => isset( $_POST['bookable_id'] ) ? intval( $_POST['bookable_id'] ) : 0,
            'date'           => isset( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : '',
            'time'           => isset( $_POST['time'] ) ? sanitize_text_field( $_POST['time'] ) : '',
            'customer_name'  => isset( $_POST['customer_name'] ) ? sanitize_text_field( $_POST['customer_name'] ) : '',
            'customer_email' => isset( $_POST['customer_email'] ) ? sanitize_email( $_POST['customer_email'] ) : '',
            'customer_phone' => isset( $_POST['customer_phone'] ) ? sanitize_text_field( $_POST['customer_phone'] ) : '',
            'customer_notes' => isset( $_POST['customer_notes'] ) ? sanitize_textarea_field( $_POST['customer_notes'] ) : '',
        ];

        // Validate form data
        $this->validate_form();

        if ( ! empty( $this->errors ) ) {
            wp_send_json_error( [ 'errors' => $this->errors ] );
        } else {
            wp_send_json_success( [ 'message' => __( 'Form is valid.', 'aqualuxe' ) ] );
        }
    }

    /**
     * Render booking form
     *
     * @param array $atts
     * @return string
     */
    public function render( $atts = [] ) {
        $defaults = [
            'bookable_id' => 0,
            'date'        => '',
            'time'        => '',
        ];

        $atts = wp_parse_args( $atts, $defaults );

        // Get errors and form data
        $errors = $this->get_errors();
        $data = $this->get_data();

        // Merge data with attributes
        $data = wp_parse_args( $data, $atts );

        // Get bookable items
        $bookable_items = get_posts( [
            'post_type'      => 'aqualuxe_bookable',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );

        // Start output buffering
        ob_start();

        // Check if there's a booking confirmation
        if ( isset( $_GET['booking_id'] ) ) {
            $booking_id = intval( $_GET['booking_id'] );
            $booking = Booking::get( $booking_id );

            if ( $booking ) {
                $this->render_booking_confirmation( $booking );
                return ob_get_clean();
            }
        }

        // Render the form
        ?>
        <div class="aqualuxe-booking-form-wrapper">
            <?php if ( ! empty( $errors['general'] ) ) : ?>
                <div class="aqualuxe-booking-form-error"><?php echo esc_html( $errors['general'] ); ?></div>
            <?php endif; ?>

            <form class="aqualuxe-booking-form" method="post" action="">
                <?php wp_nonce_field( 'aqualuxe_booking_form', 'aqualuxe_booking_form_nonce' ); ?>

                <div class="aqualuxe-booking-form-section">
                    <h3><?php esc_html_e( 'Select Service', 'aqualuxe' ); ?></h3>
                    <div class="aqualuxe-booking-form-field <?php echo ! empty( $errors['bookable_id'] ) ? 'has-error' : ''; ?>">
                        <label for="bookable_id"><?php esc_html_e( 'Service', 'aqualuxe' ); ?></label>
                        <select id="bookable_id" name="bookable_id" required>
                            <option value=""><?php esc_html_e( 'Select a service', 'aqualuxe' ); ?></option>
                            <?php foreach ( $bookable_items as $item ) : ?>
                                <option value="<?php echo esc_attr( $item->ID ); ?>" <?php selected( $data['bookable_id'], $item->ID ); ?>><?php echo esc_html( $item->post_title ); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ( ! empty( $errors['bookable_id'] ) ) : ?>
                            <div class="aqualuxe-booking-form-error"><?php echo esc_html( $errors['bookable_id'] ); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="aqualuxe-booking-form-section">
                    <h3><?php esc_html_e( 'Select Date & Time', 'aqualuxe' ); ?></h3>
                    <div class="aqualuxe-booking-form-field <?php echo ! empty( $errors['date'] ) ? 'has-error' : ''; ?>">
                        <label for="booking_date"><?php esc_html_e( 'Date', 'aqualuxe' ); ?></label>
                        <input type="date" id="booking_date" name="booking_date" value="<?php echo esc_attr( $data['date'] ); ?>" min="<?php echo esc_attr( date( 'Y-m-d' ) ); ?>" required>
                        <?php if ( ! empty( $errors['date'] ) ) : ?>
                            <div class="aqualuxe-booking-form-error"><?php echo esc_html( $errors['date'] ); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="aqualuxe-booking-form-field <?php echo ! empty( $errors['time'] ) ? 'has-error' : ''; ?>">
                        <label for="booking_time"><?php esc_html_e( 'Time', 'aqualuxe' ); ?></label>
                        <div class="aqualuxe-booking-time-slots">
                            <p class="aqualuxe-booking-time-message"><?php esc_html_e( 'Please select a service and date first.', 'aqualuxe' ); ?></p>
                        </div>
                        <input type="hidden" id="booking_time" name="booking_time" value="<?php echo esc_attr( $data['time'] ); ?>" required>
                        <?php if ( ! empty( $errors['time'] ) ) : ?>
                            <div class="aqualuxe-booking-form-error"><?php echo esc_html( $errors['time'] ); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="aqualuxe-booking-form-section">
                    <h3><?php esc_html_e( 'Your Information', 'aqualuxe' ); ?></h3>
                    <div class="aqualuxe-booking-form-field <?php echo ! empty( $errors['customer_name'] ) ? 'has-error' : ''; ?>">
                        <label for="customer_name"><?php esc_html_e( 'Name', 'aqualuxe' ); ?></label>
                        <input type="text" id="customer_name" name="customer_name" value="<?php echo esc_attr( isset( $data['customer_name'] ) ? $data['customer_name'] : '' ); ?>" required>
                        <?php if ( ! empty( $errors['customer_name'] ) ) : ?>
                            <div class="aqualuxe-booking-form-error"><?php echo esc_html( $errors['customer_name'] ); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="aqualuxe-booking-form-field <?php echo ! empty( $errors['customer_email'] ) ? 'has-error' : ''; ?>">
                        <label for="customer_email"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></label>
                        <input type="email" id="customer_email" name="customer_email" value="<?php echo esc_attr( isset( $data['customer_email'] ) ? $data['customer_email'] : '' ); ?>" required>
                        <?php if ( ! empty( $errors['customer_email'] ) ) : ?>
                            <div class="aqualuxe-booking-form-error"><?php echo esc_html( $errors['customer_email'] ); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="aqualuxe-booking-form-field <?php echo ! empty( $errors['customer_phone'] ) ? 'has-error' : ''; ?>">
                        <label for="customer_phone"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label>
                        <input type="tel" id="customer_phone" name="customer_phone" value="<?php echo esc_attr( isset( $data['customer_phone'] ) ? $data['customer_phone'] : '' ); ?>">
                        <?php if ( ! empty( $errors['customer_phone'] ) ) : ?>
                            <div class="aqualuxe-booking-form-error"><?php echo esc_html( $errors['customer_phone'] ); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="aqualuxe-booking-form-field <?php echo ! empty( $errors['customer_notes'] ) ? 'has-error' : ''; ?>">
                        <label for="customer_notes"><?php esc_html_e( 'Notes', 'aqualuxe' ); ?></label>
                        <textarea id="customer_notes" name="customer_notes" rows="4"><?php echo esc_textarea( isset( $data['customer_notes'] ) ? $data['customer_notes'] : '' ); ?></textarea>
                        <?php if ( ! empty( $errors['customer_notes'] ) ) : ?>
                            <div class="aqualuxe-booking-form-error"><?php echo esc_html( $errors['customer_notes'] ); ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="aqualuxe-booking-form-submit">
                    <button type="submit" class="aqualuxe-booking-form-button"><?php esc_html_e( 'Book Now', 'aqualuxe' ); ?></button>
                </div>
            </form>
        </div>

        <script>
            jQuery(document).ready(function($) {
                // Initialize variables
                var bookableId = $('#bookable_id').val();
                var bookingDate = $('#booking_date').val();
                var bookingTime = $('#booking_time').val();

                // Function to load available time slots
                function loadTimeSlots() {
                    bookableId = $('#bookable_id').val();
                    bookingDate = $('#booking_date').val();

                    if (!bookableId || !bookingDate) {
                        $('.aqualuxe-booking-time-slots').html('<p class="aqualuxe-booking-time-message"><?php esc_html_e( 'Please select a service and date first.', 'aqualuxe' ); ?></p>');
                        return;
                    }

                    $('.aqualuxe-booking-time-slots').html('<p class="aqualuxe-booking-time-message"><?php esc_html_e( 'Loading available times...', 'aqualuxe' ); ?></p>');

                    $.ajax({
                        url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_get_available_times',
                            nonce: '<?php echo esc_js( wp_create_nonce( 'aqualuxe_booking_form' ) ); ?>',
                            bookable_id: bookableId,
                            date: bookingDate
                        },
                        success: function(response) {
                            if (response.success) {
                                var slots = response.data.available_slots;
                                var html = '';

                                if (slots.length === 0) {
                                    html = '<p class="aqualuxe-booking-time-message"><?php esc_html_e( 'No available time slots for this date.', 'aqualuxe' ); ?></p>';
                                } else {
                                    html = '<div class="aqualuxe-booking-time-grid">';
                                    for (var i = 0; i < slots.length; i++) {
                                        var slot = slots[i];
                                        var classes = 'aqualuxe-booking-time-slot';
                                        if (!slot.available) {
                                            classes += ' unavailable';
                                        }
                                        if (bookingTime === slot.time) {
                                            classes += ' selected';
                                        }
                                        html += '<div class="' + classes + '" data-time="' + slot.time + '">' + slot.formatted + '</div>';
                                    }
                                    html += '</div>';
                                }

                                $('.aqualuxe-booking-time-slots').html(html);

                                // Add click event to time slots
                                $('.aqualuxe-booking-time-slot').on('click', function() {
                                    if ($(this).hasClass('unavailable')) {
                                        return;
                                    }

                                    $('.aqualuxe-booking-time-slot').removeClass('selected');
                                    $(this).addClass('selected');
                                    $('#booking_time').val($(this).data('time'));
                                });
                            } else {
                                $('.aqualuxe-booking-time-slots').html('<p class="aqualuxe-booking-time-message">' + response.data.message + '</p>');
                            }
                        },
                        error: function() {
                            $('.aqualuxe-booking-time-slots').html('<p class="aqualuxe-booking-time-message"><?php esc_html_e( 'Error loading time slots. Please try again.', 'aqualuxe' ); ?></p>');
                        }
                    });
                }

                // Load time slots when service or date changes
                $('#bookable_id, #booking_date').on('change', function() {
                    loadTimeSlots();
                });

                // Load time slots on page load if service and date are selected
                if (bookableId && bookingDate) {
                    loadTimeSlots();
                }

                // Form validation
                $('.aqualuxe-booking-form').on('submit', function(e) {
                    var valid = true;
                    var errorMessages = {};

                    // Check required fields
                    if (!$('#bookable_id').val()) {
                        errorMessages.bookable_id = '<?php esc_html_e( 'Please select a service.', 'aqualuxe' ); ?>';
                        valid = false;
                    }

                    if (!$('#booking_date').val()) {
                        errorMessages.date = '<?php esc_html_e( 'Please select a date.', 'aqualuxe' ); ?>';
                        valid = false;
                    }

                    if (!$('#booking_time').val()) {
                        errorMessages.time = '<?php esc_html_e( 'Please select a time.', 'aqualuxe' ); ?>';
                        valid = false;
                    }

                    if (!$('#customer_name').val()) {
                        errorMessages.customer_name = '<?php esc_html_e( 'Please enter your name.', 'aqualuxe' ); ?>';
                        valid = false;
                    }

                    if (!$('#customer_email').val()) {
                        errorMessages.customer_email = '<?php esc_html_e( 'Please enter your email address.', 'aqualuxe' ); ?>';
                        valid = false;
                    }

                    // If form is not valid, prevent submission
                    if (!valid) {
                        e.preventDefault();
                        
                        // Display error messages
                        for (var field in errorMessages) {
                            var $field = $('#' + field);
                            $field.closest('.aqualuxe-booking-form-field').addClass('has-error');
                            $field.closest('.aqualuxe-booking-form-field').find('.aqualuxe-booking-form-error').remove();
                            $field.closest('.aqualuxe-booking-form-field').append('<div class="aqualuxe-booking-form-error">' + errorMessages[field] + '</div>');
                        }

                        return;
                    }

                    // Validate form via AJAX
                    $.ajax({
                        url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_validate_booking_form',
                            nonce: '<?php echo esc_js( wp_create_nonce( 'aqualuxe_booking_form' ) ); ?>',
                            bookable_id: $('#bookable_id').val(),
                            date: $('#booking_date').val(),
                            time: $('#booking_time').val(),
                            customer_name: $('#customer_name').val(),
                            customer_email: $('#customer_email').val(),
                            customer_phone: $('#customer_phone').val(),
                            customer_notes: $('#customer_notes').val()
                        },
                        success: function(response) {
                            if (!response.success) {
                                e.preventDefault();
                                
                                // Display error messages
                                for (var field in response.data.errors) {
                                    var $field = $('#' + field);
                                    $field.closest('.aqualuxe-booking-form-field').addClass('has-error');
                                    $field.closest('.aqualuxe-booking-form-field').find('.aqualuxe-booking-form-error').remove();
                                    $field.closest('.aqualuxe-booking-form-field').append('<div class="aqualuxe-booking-form-error">' + response.data.errors[field] + '</div>');
                                }
                            }
                        },
                        error: function() {
                            e.preventDefault();
                            alert('<?php esc_html_e( 'Error validating form. Please try again.', 'aqualuxe' ); ?>');
                        }
                    });
                });
            });
        </script>
        <?php

        return ob_get_clean();
    }

    /**
     * Render booking confirmation
     *
     * @param Booking $booking
     * @return void
     */
    private function render_booking_confirmation( $booking ) {
        ?>
        <div class="aqualuxe-booking-confirmation">
            <h2><?php esc_html_e( 'Booking Confirmation', 'aqualuxe' ); ?></h2>
            <p><?php esc_html_e( 'Thank you for your booking. Your booking details are below.', 'aqualuxe' ); ?></p>

            <div class="aqualuxe-booking-details">
                <div class="aqualuxe-booking-detail">
                    <span class="aqualuxe-booking-detail-label"><?php esc_html_e( 'Booking ID:', 'aqualuxe' ); ?></span>
                    <span class="aqualuxe-booking-detail-value">#<?php echo esc_html( $booking->get_id() ); ?></span>
                </div>

                <div class="aqualuxe-booking-detail">
                    <span class="aqualuxe-booking-detail-label"><?php esc_html_e( 'Service:', 'aqualuxe' ); ?></span>
                    <span class="aqualuxe-booking-detail-value"><?php echo esc_html( $booking->get_bookable_title() ); ?></span>
                </div>

                <div class="aqualuxe-booking-detail">
                    <span class="aqualuxe-booking-detail-label"><?php esc_html_e( 'Date:', 'aqualuxe' ); ?></span>
                    <span class="aqualuxe-booking-detail-value"><?php echo esc_html( $booking->get_date( get_option( 'date_format' ) ) ); ?></span>
                </div>

                <div class="aqualuxe-booking-detail">
                    <span class="aqualuxe-booking-detail-label"><?php esc_html_e( 'Time:', 'aqualuxe' ); ?></span>
                    <span class="aqualuxe-booking-detail-value"><?php echo esc_html( $booking->get_time( get_option( 'time_format' ) ) ); ?></span>
                </div>

                <div class="aqualuxe-booking-detail">
                    <span class="aqualuxe-booking-detail-label"><?php esc_html_e( 'Status:', 'aqualuxe' ); ?></span>
                    <span class="aqualuxe-booking-detail-value aqualuxe-booking-status-<?php echo esc_attr( $booking->get_status() ); ?>"><?php echo esc_html( $booking->get_status_label() ); ?></span>
                </div>

                <div class="aqualuxe-booking-detail">
                    <span class="aqualuxe-booking-detail-label"><?php esc_html_e( 'Name:', 'aqualuxe' ); ?></span>
                    <span class="aqualuxe-booking-detail-value"><?php echo esc_html( $booking->get_customer_name() ); ?></span>
                </div>

                <div class="aqualuxe-booking-detail">
                    <span class="aqualuxe-booking-detail-label"><?php esc_html_e( 'Email:', 'aqualuxe' ); ?></span>
                    <span class="aqualuxe-booking-detail-value"><?php echo esc_html( $booking->get_customer_email() ); ?></span>
                </div>

                <?php if ( $booking->get_customer_phone() ) : ?>
                    <div class="aqualuxe-booking-detail">
                        <span class="aqualuxe-booking-detail-label"><?php esc_html_e( 'Phone:', 'aqualuxe' ); ?></span>
                        <span class="aqualuxe-booking-detail-value"><?php echo esc_html( $booking->get_customer_phone() ); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ( $booking->get_customer_notes() ) : ?>
                    <div class="aqualuxe-booking-detail">
                        <span class="aqualuxe-booking-detail-label"><?php esc_html_e( 'Notes:', 'aqualuxe' ); ?></span>
                        <span class="aqualuxe-booking-detail-value"><?php echo esc_html( $booking->get_customer_notes() ); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <p><?php esc_html_e( 'A confirmation email has been sent to your email address.', 'aqualuxe' ); ?></p>
        </div>
        <?php
    }
}

// Initialize the form class
Booking_Form::get_instance();