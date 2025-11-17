<?php
/**
 * Booking Availability Class
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
 * Booking Availability Class
 * 
 * This class handles the booking availability functionality.
 */
class Booking_Availability {
    /**
     * Instance of this class
     *
     * @var Booking_Availability
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
     * @return Booking_Availability
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
        add_action( 'wp_ajax_aqualuxe_check_availability', [ $this, 'ajax_check_availability' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_check_availability', [ $this, 'ajax_check_availability' ] );
    }

    /**
     * Ajax check availability
     *
     * @return void
     */
    public function ajax_check_availability() {
        check_ajax_referer( 'aqualuxe_booking_form', 'nonce' );

        $bookable_id = isset( $_POST['bookable_id'] ) ? intval( $_POST['bookable_id'] ) : 0;
        $date = isset( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : '';
        $time = isset( $_POST['time'] ) ? sanitize_text_field( $_POST['time'] ) : '';

        if ( ! $bookable_id || ! $date ) {
            wp_send_json_error( [ 'message' => __( 'Invalid bookable ID or date.', 'aqualuxe' ) ] );
        }

        $availability = $this->check_availability( $bookable_id, $date, $time );

        if ( is_wp_error( $availability ) ) {
            wp_send_json_error( [ 'message' => $availability->get_error_message() ] );
        }

        wp_send_json_success( $availability );
    }

    /**
     * Check availability
     *
     * @param int $bookable_id
     * @param string $date
     * @param string $time
     * @return array|WP_Error
     */
    public function check_availability( $bookable_id, $date, $time = '' ) {
        // Check if the bookable item exists
        $bookable = get_post( $bookable_id );
        if ( ! $bookable || 'aqualuxe_bookable' !== $bookable->post_type ) {
            return new \WP_Error( 'invalid_bookable', __( 'Invalid bookable item.', 'aqualuxe' ) );
        }

        // Check if the date is valid
        if ( ! $date || strtotime( $date ) < strtotime( date( 'Y-m-d' ) ) ) {
            return new \WP_Error( 'invalid_date', __( 'Invalid date.', 'aqualuxe' ) );
        }

        // Get availability for the day
        $day_of_week = strtolower( date( 'l', strtotime( $date ) ) );
        $availability = get_post_meta( $bookable_id, '_aqualuxe_bookable_availability', true );

        if ( ! is_array( $availability ) || ! isset( $availability[ $day_of_week ] ) || ! isset( $availability[ $day_of_week ]['enabled'] ) || ! $availability[ $day_of_week ]['enabled'] ) {
            return new \WP_Error( 'not_available', __( 'Not available on this day.', 'aqualuxe' ) );
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

        // If a specific time is provided, check if it's available
        if ( $time ) {
            $time = date( 'H:i', strtotime( $time ) );
            $is_available = false;
            $slot_info = null;

            foreach ( $available_slots as $slot ) {
                if ( $slot['time'] === $time ) {
                    $is_available = $slot['available'];
                    $slot_info = $slot;
                    break;
                }
            }

            if ( ! $slot_info ) {
                return new \WP_Error( 'invalid_time', __( 'Invalid time.', 'aqualuxe' ) );
            }

            if ( ! $is_available ) {
                return new \WP_Error( 'not_available', __( 'The selected time slot is not available.', 'aqualuxe' ) );
            }

            return [
                'date'            => date_i18n( get_option( 'date_format' ), strtotime( $date ) ),
                'day_of_week'     => $day_of_week,
                'time'            => $time,
                'formatted_time'  => date_i18n( get_option( 'time_format' ), strtotime( $time ) ),
                'available'       => true,
                'count'           => $slot_info['count'],
                'capacity'        => $slot_info['capacity'],
                'duration'        => $duration,
            ];
        }

        return [
            'date'            => date_i18n( get_option( 'date_format' ), strtotime( $date ) ),
            'day_of_week'     => $day_of_week,
            'start_time'      => $start_time,
            'end_time'        => $end_time,
            'duration'        => $duration,
            'available_slots' => $available_slots,
        ];
    }

    /**
     * Get available dates
     *
     * @param int $bookable_id
     * @param string $start_date
     * @param string $end_date
     * @return array
     */
    public function get_available_dates( $bookable_id, $start_date = '', $end_date = '' ) {
        if ( ! $start_date ) {
            $start_date = date( 'Y-m-d' );
        }

        if ( ! $end_date ) {
            $end_date = date( 'Y-m-d', strtotime( '+30 days' ) );
        }

        // Check if the bookable item exists
        $bookable = get_post( $bookable_id );
        if ( ! $bookable || 'aqualuxe_bookable' !== $bookable->post_type ) {
            return [];
        }

        // Get availability
        $availability = get_post_meta( $bookable_id, '_aqualuxe_bookable_availability', true );
        if ( ! is_array( $availability ) ) {
            return [];
        }

        // Get available days of the week
        $available_days = [];
        foreach ( $availability as $day => $data ) {
            if ( isset( $data['enabled'] ) && $data['enabled'] ) {
                $available_days[] = $day;
            }
        }

        if ( empty( $available_days ) ) {
            return [];
        }

        // Generate dates
        $dates = [];
        $current_date = strtotime( $start_date );
        $end_date_timestamp = strtotime( $end_date );

        while ( $current_date <= $end_date_timestamp ) {
            $day_of_week = strtolower( date( 'l', $current_date ) );
            if ( in_array( $day_of_week, $available_days, true ) ) {
                $date = date( 'Y-m-d', $current_date );
                $dates[] = [
                    'date'        => $date,
                    'formatted'   => date_i18n( get_option( 'date_format' ), $current_date ),
                    'day_of_week' => $day_of_week,
                ];
            }
            $current_date = strtotime( '+1 day', $current_date );
        }

        return $dates;
    }

    /**
     * Check if a specific date and time is available
     *
     * @param int $bookable_id
     * @param string $date
     * @param string $time
     * @return boolean
     */
    public function is_available( $bookable_id, $date, $time ) {
        $availability = $this->check_availability( $bookable_id, $date, $time );
        return ! is_wp_error( $availability );
    }

    /**
     * Render availability calendar
     *
     * @param array $atts
     * @return string
     */
    public function render_calendar( $atts = [] ) {
        $defaults = [
            'bookable_id' => 0,
            'months'      => 1,
        ];

        $atts = wp_parse_args( $atts, $defaults );

        if ( ! $atts['bookable_id'] ) {
            return '<p>' . esc_html__( 'Please specify a bookable item.', 'aqualuxe' ) . '</p>';
        }

        $bookable = get_post( $atts['bookable_id'] );
        if ( ! $bookable || 'aqualuxe_bookable' !== $bookable->post_type ) {
            return '<p>' . esc_html__( 'Invalid bookable item.', 'aqualuxe' ) . '</p>';
        }

        // Get availability
        $availability = get_post_meta( $atts['bookable_id'], '_aqualuxe_bookable_availability', true );
        if ( ! is_array( $availability ) ) {
            return '<p>' . esc_html__( 'No availability information found.', 'aqualuxe' ) . '</p>';
        }

        // Get available days of the week
        $available_days = [];
        foreach ( $availability as $day => $data ) {
            if ( isset( $data['enabled'] ) && $data['enabled'] ) {
                $available_days[] = $day;
            }
        }

        if ( empty( $available_days ) ) {
            return '<p>' . esc_html__( 'No available days found.', 'aqualuxe' ) . '</p>';
        }

        // Generate calendar
        $months = intval( $atts['months'] );
        if ( $months < 1 ) {
            $months = 1;
        } elseif ( $months > 12 ) {
            $months = 12;
        }

        $current_month = date( 'Y-m' );
        $calendars = [];

        for ( $i = 0; $i < $months; $i++ ) {
            $month = date( 'Y-m', strtotime( $current_month . ' +' . $i . ' months' ) );
            $calendars[] = $this->generate_month_calendar( $atts['bookable_id'], $month, $available_days );
        }

        // Start output buffering
        ob_start();

        ?>
        <div class="aqualuxe-booking-availability-calendar-wrapper">
            <div class="aqualuxe-booking-availability-calendar-legend">
                <span class="aqualuxe-booking-availability-calendar-legend-item available"></span> <?php esc_html_e( 'Available', 'aqualuxe' ); ?>
                <span class="aqualuxe-booking-availability-calendar-legend-item unavailable"></span> <?php esc_html_e( 'Unavailable', 'aqualuxe' ); ?>
            </div>

            <div class="aqualuxe-booking-availability-calendars">
                <?php foreach ( $calendars as $calendar ) : ?>
                    <?php echo $calendar; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * Generate month calendar
     *
     * @param int $bookable_id
     * @param string $month
     * @param array $available_days
     * @return string
     */
    private function generate_month_calendar( $bookable_id, $month, $available_days ) {
        $month_start = date( 'Y-m-01', strtotime( $month ) );
        $month_end = date( 'Y-m-t', strtotime( $month ) );
        $first_day_of_week = intval( get_option( 'start_of_week', 0 ) ); // 0 = Sunday, 1 = Monday, etc.

        $month_name = date_i18n( 'F Y', strtotime( $month ) );
        $days_in_month = date( 't', strtotime( $month ) );
        $first_day_of_month = date( 'w', strtotime( $month_start ) );

        // Adjust first day of month based on first day of week setting
        $first_day_of_month = ( $first_day_of_month - $first_day_of_week + 7 ) % 7;

        // Get day names
        $day_names = [];
        for ( $i = 0; $i < 7; $i++ ) {
            $day_index = ( $first_day_of_week + $i ) % 7;
            $day_names[] = date_i18n( 'D', strtotime( 'Sunday +' . $day_index . ' days' ) );
        }

        // Start output buffering
        ob_start();

        ?>
        <div class="aqualuxe-booking-availability-calendar">
            <div class="aqualuxe-booking-availability-calendar-header">
                <h3><?php echo esc_html( $month_name ); ?></h3>
            </div>

            <table class="aqualuxe-booking-availability-calendar-table">
                <thead>
                    <tr>
                        <?php foreach ( $day_names as $day_name ) : ?>
                            <th><?php echo esc_html( $day_name ); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php
                        // Add empty cells for days before the first day of the month
                        for ( $i = 0; $i < $first_day_of_month; $i++ ) {
                            echo '<td class="aqualuxe-booking-availability-calendar-day empty"></td>';
                        }

                        // Add cells for each day of the month
                        for ( $day = 1; $day <= $days_in_month; $day++ ) {
                            $date = date( 'Y-m-d', strtotime( $month . '-' . $day ) );
                            $day_of_week = strtolower( date( 'l', strtotime( $date ) ) );
                            $is_available = in_array( $day_of_week, $available_days, true );
                            $is_past = strtotime( $date ) < strtotime( date( 'Y-m-d' ) );
                            $class = 'aqualuxe-booking-availability-calendar-day';
                            $class .= $is_available && ! $is_past ? ' available' : ' unavailable';
                            $class .= $is_past ? ' past' : '';

                            echo '<td class="' . esc_attr( $class ) . '" data-date="' . esc_attr( $date ) . '">';
                            echo '<span class="aqualuxe-booking-availability-calendar-day-number">' . esc_html( $day ) . '</span>';
                            echo '</td>';

                            // Start a new row if this is the last day of the week
                            if ( ( $first_day_of_month + $day ) % 7 === 0 ) {
                                echo '</tr><tr>';
                            }
                        }

                        // Add empty cells for days after the last day of the month
                        $cells_to_add = 7 - ( ( $first_day_of_month + $days_in_month ) % 7 );
                        if ( $cells_to_add < 7 ) {
                            for ( $i = 0; $i < $cells_to_add; $i++ ) {
                                echo '<td class="aqualuxe-booking-availability-calendar-day empty"></td>';
                            }
                        }
                        ?>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php

        return ob_get_clean();
    }
}

// Initialize the availability class
Booking_Availability::get_instance();