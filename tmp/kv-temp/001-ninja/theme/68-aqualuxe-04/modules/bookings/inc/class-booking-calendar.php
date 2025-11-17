<?php
/**
 * Booking Calendar Class
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
 * Booking Calendar Class
 * 
 * This class handles the booking calendar functionality.
 */
class Booking_Calendar {
    /**
     * Instance of this class
     *
     * @var Booking_Calendar
     */
    private static $instance = null;

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Get the singleton instance
     *
     * @return Booking_Calendar
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
        // AJAX handlers
        add_action( 'wp_ajax_aqualuxe_get_calendar_events', [ $this, 'ajax_get_calendar_events' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_get_calendar_events', [ $this, 'ajax_get_calendar_events' ] );
    }

    /**
     * Ajax get calendar events
     *
     * @return void
     */
    public function ajax_get_calendar_events() {
        check_ajax_referer( 'aqualuxe_calendar', 'nonce' );

        $start_date = isset( $_POST['start'] ) ? sanitize_text_field( $_POST['start'] ) : '';
        $end_date = isset( $_POST['end'] ) ? sanitize_text_field( $_POST['end'] ) : '';
        $bookable_id = isset( $_POST['bookable_id'] ) ? intval( $_POST['bookable_id'] ) : 0;

        if ( ! $start_date || ! $end_date ) {
            wp_send_json_error( [ 'message' => __( 'Invalid date range.', 'aqualuxe' ) ] );
        }

        $args = [
            'post_type'      => 'aqualuxe_booking',
            'posts_per_page' => -1,
            'meta_query'     => [
                'relation' => 'AND',
                [
                    'key'     => '_aqualuxe_booking_date',
                    'value'   => [ $start_date, $end_date ],
                    'compare' => 'BETWEEN',
                    'type'    => 'DATE',
                ],
            ],
        ];

        if ( $bookable_id ) {
            $args['meta_query'][] = [
                'key'   => '_aqualuxe_booking_bookable_id',
                'value' => $bookable_id,
            ];
        }

        $bookings = get_posts( $args );
        $events = [];

        foreach ( $bookings as $booking ) {
            $booking_id = $booking->ID;
            $bookable_id = get_post_meta( $booking_id, '_aqualuxe_booking_bookable_id', true );
            $date = get_post_meta( $booking_id, '_aqualuxe_booking_date', true );
            $time = get_post_meta( $booking_id, '_aqualuxe_booking_time', true );
            $status = get_post_meta( $booking_id, '_aqualuxe_booking_status', true );
            if ( ! $status ) {
                $status = 'pending';
            }

            $customer_name = get_post_meta( $booking_id, '_aqualuxe_booking_customer_name', true );
            $customer_email = get_post_meta( $booking_id, '_aqualuxe_booking_customer_email', true );

            $bookable = get_post( $bookable_id );
            $bookable_title = $bookable ? $bookable->post_title : __( 'Unknown', 'aqualuxe' );

            // Get duration
            $duration = get_post_meta( $bookable_id, '_aqualuxe_bookable_duration', true );
            if ( ! $duration ) {
                $duration = 60; // Default to 60 minutes
            }

            // Calculate end time
            $start_datetime = $date . ' ' . $time;
            $end_datetime = date( 'Y-m-d H:i:s', strtotime( $start_datetime ) + $duration * 60 );

            // Set event color based on status
            $colors = [
                'pending'   => '#f39c12', // Orange
                'confirmed' => '#2ecc71', // Green
                'completed' => '#3498db', // Blue
                'cancelled' => '#e74c3c', // Red
            ];

            $color = isset( $colors[ $status ] ) ? $colors[ $status ] : '#95a5a6'; // Default gray

            $events[] = [
                'id'          => $booking_id,
                'title'       => $customer_name . ' - ' . $bookable_title,
                'start'       => $start_datetime,
                'end'         => $end_datetime,
                'color'       => $color,
                'textColor'   => '#ffffff',
                'url'         => admin_url( 'post.php?post=' . $booking_id . '&action=edit' ),
                'description' => sprintf(
                    __( 'Customer: %1$s<br>Email: %2$s<br>Service: %3$s<br>Status: %4$s', 'aqualuxe' ),
                    $customer_name,
                    $customer_email,
                    $bookable_title,
                    ucfirst( $status )
                ),
            ];
        }

        wp_send_json_success( $events );
    }

    /**
     * Render booking calendar
     *
     * @param array $atts
     * @return string
     */
    public function render( $atts = [] ) {
        $defaults = [
            'bookable_id' => 0,
            'view'        => 'month',
            'height'      => 'auto',
        ];

        $atts = wp_parse_args( $atts, $defaults );

        // Get bookable items for filter
        $bookable_items = get_posts( [
            'post_type'      => 'aqualuxe_bookable',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );

        // Enqueue FullCalendar scripts and styles
        wp_enqueue_style( 'fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css', [], '5.10.1' );
        wp_enqueue_script( 'fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js', [], '5.10.1', true );

        // Generate a unique ID for the calendar
        $calendar_id = 'aqualuxe-booking-calendar-' . uniqid();

        // Start output buffering
        ob_start();

        ?>
        <div class="aqualuxe-booking-calendar-wrapper">
            <?php if ( ! empty( $bookable_items ) ) : ?>
                <div class="aqualuxe-booking-calendar-filter">
                    <label for="aqualuxe-booking-calendar-filter-bookable"><?php esc_html_e( 'Filter by Service:', 'aqualuxe' ); ?></label>
                    <select id="aqualuxe-booking-calendar-filter-bookable">
                        <option value="0"><?php esc_html_e( 'All Services', 'aqualuxe' ); ?></option>
                        <?php foreach ( $bookable_items as $item ) : ?>
                            <option value="<?php echo esc_attr( $item->ID ); ?>" <?php selected( $atts['bookable_id'], $item->ID ); ?>><?php echo esc_html( $item->post_title ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="aqualuxe-booking-calendar-legend">
                <span class="aqualuxe-booking-calendar-legend-item" style="background-color: #f39c12;"></span> <?php esc_html_e( 'Pending', 'aqualuxe' ); ?>
                <span class="aqualuxe-booking-calendar-legend-item" style="background-color: #2ecc71;"></span> <?php esc_html_e( 'Confirmed', 'aqualuxe' ); ?>
                <span class="aqualuxe-booking-calendar-legend-item" style="background-color: #3498db;"></span> <?php esc_html_e( 'Completed', 'aqualuxe' ); ?>
                <span class="aqualuxe-booking-calendar-legend-item" style="background-color: #e74c3c;"></span> <?php esc_html_e( 'Cancelled', 'aqualuxe' ); ?>
            </div>

            <div id="<?php echo esc_attr( $calendar_id ); ?>" class="aqualuxe-booking-calendar"></div>
        </div>

        <script>
            jQuery(document).ready(function($) {
                var calendarEl = document.getElementById('<?php echo esc_js( $calendar_id ); ?>');
                var bookableId = $('#aqualuxe-booking-calendar-filter-bookable').val();

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: '<?php echo esc_js( $atts['view'] ); ?>',
                    height: '<?php echo esc_js( $atts['height'] ); ?>',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    navLinks: true,
                    editable: false,
                    selectable: false,
                    dayMaxEvents: true,
                    events: function(info, successCallback, failureCallback) {
                        $.ajax({
                            url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                            type: 'POST',
                            data: {
                                action: 'aqualuxe_get_calendar_events',
                                nonce: '<?php echo esc_js( wp_create_nonce( 'aqualuxe_calendar' ) ); ?>',
                                start: info.startStr,
                                end: info.endStr,
                                bookable_id: bookableId
                            },
                            success: function(response) {
                                if (response.success) {
                                    successCallback(response.data);
                                } else {
                                    failureCallback(response.data.message);
                                }
                            },
                            error: function() {
                                failureCallback('<?php esc_html_e( 'Error loading events.', 'aqualuxe' ); ?>');
                            }
                        });
                    },
                    eventDidMount: function(info) {
                        $(info.el).tooltip({
                            title: info.event.extendedProps.description,
                            html: true,
                            placement: 'top',
                            container: 'body'
                        });
                    }
                });

                calendar.render();

                // Filter events when bookable item changes
                $('#aqualuxe-booking-calendar-filter-bookable').on('change', function() {
                    bookableId = $(this).val();
                    calendar.refetchEvents();
                });
            });
        </script>
        <?php

        return ob_get_clean();
    }
}

// Initialize the calendar class
Booking_Calendar::get_instance();