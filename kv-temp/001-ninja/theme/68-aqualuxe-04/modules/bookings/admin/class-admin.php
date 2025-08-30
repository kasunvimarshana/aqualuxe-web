<?php
/**
 * Bookings Admin Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings\Admin
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Bookings\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Bookings Admin Class
 * 
 * This class handles the admin functionality for the bookings module.
 */
class Admin {
    /**
     * Instance of this class
     *
     * @var Admin
     */
    private static $instance = null;

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Get the singleton instance
     *
     * @return Admin
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
        // Add admin columns
        add_filter( 'manage_aqualuxe_booking_posts_columns', [ $this, 'add_booking_columns' ] );
        add_action( 'manage_aqualuxe_booking_posts_custom_column', [ $this, 'render_booking_columns' ], 10, 2 );
        add_filter( 'manage_edit-aqualuxe_booking_sortable_columns', [ $this, 'sortable_booking_columns' ] );

        add_filter( 'manage_aqualuxe_bookable_posts_columns', [ $this, 'add_bookable_columns' ] );
        add_action( 'manage_aqualuxe_bookable_posts_custom_column', [ $this, 'render_bookable_columns' ], 10, 2 );
        add_filter( 'manage_edit-aqualuxe_bookable_sortable_columns', [ $this, 'sortable_bookable_columns' ] );

        // Add admin filters
        add_action( 'restrict_manage_posts', [ $this, 'add_admin_filters' ] );
        add_filter( 'parse_query', [ $this, 'filter_bookings_by_status' ] );

        // Add admin actions
        add_filter( 'post_row_actions', [ $this, 'add_booking_row_actions' ], 10, 2 );

        // Add admin notices
        add_action( 'admin_notices', [ $this, 'display_admin_notices' ] );

        // Add admin ajax handlers
        add_action( 'wp_ajax_aqualuxe_update_booking_status', [ $this, 'ajax_update_booking_status' ] );
        add_action( 'wp_ajax_aqualuxe_get_booking_details', [ $this, 'ajax_get_booking_details' ] );
        add_action( 'wp_ajax_aqualuxe_get_bookable_availability', [ $this, 'ajax_get_bookable_availability' ] );
    }

    /**
     * Add booking columns
     *
     * @param array $columns
     * @return array
     */
    public function add_booking_columns( $columns ) {
        $new_columns = [];

        foreach ( $columns as $key => $value ) {
            if ( $key === 'title' ) {
                $new_columns[ $key ] = $value;
                $new_columns['booking_id'] = __( 'Booking ID', 'aqualuxe' );
                $new_columns['bookable_item'] = __( 'Bookable Item', 'aqualuxe' );
                $new_columns['booking_date'] = __( 'Date', 'aqualuxe' );
                $new_columns['booking_time'] = __( 'Time', 'aqualuxe' );
                $new_columns['customer'] = __( 'Customer', 'aqualuxe' );
                $new_columns['status'] = __( 'Status', 'aqualuxe' );
            } elseif ( $key === 'date' ) {
                $new_columns['created'] = __( 'Created', 'aqualuxe' );
            } else {
                $new_columns[ $key ] = $value;
            }
        }

        return $new_columns;
    }

    /**
     * Render booking columns
     *
     * @param string $column
     * @param int $post_id
     * @return void
     */
    public function render_booking_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'booking_id':
                echo esc_html( $post_id );
                break;

            case 'bookable_item':
                $bookable_id = get_post_meta( $post_id, '_aqualuxe_booking_bookable_id', true );
                if ( $bookable_id ) {
                    $bookable = get_post( $bookable_id );
                    if ( $bookable ) {
                        echo '<a href="' . esc_url( get_edit_post_link( $bookable_id ) ) . '">' . esc_html( $bookable->post_title ) . '</a>';
                    } else {
                        echo esc_html__( 'Unknown', 'aqualuxe' );
                    }
                } else {
                    echo esc_html__( 'Not set', 'aqualuxe' );
                }
                break;

            case 'booking_date':
                $date = get_post_meta( $post_id, '_aqualuxe_booking_date', true );
                if ( $date ) {
                    echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $date ) ) );
                } else {
                    echo esc_html__( 'Not set', 'aqualuxe' );
                }
                break;

            case 'booking_time':
                $time = get_post_meta( $post_id, '_aqualuxe_booking_time', true );
                if ( $time ) {
                    echo esc_html( date_i18n( get_option( 'time_format' ), strtotime( $time ) ) );
                } else {
                    echo esc_html__( 'Not set', 'aqualuxe' );
                }
                break;

            case 'customer':
                $customer_name = get_post_meta( $post_id, '_aqualuxe_booking_customer_name', true );
                $customer_email = get_post_meta( $post_id, '_aqualuxe_booking_customer_email', true );
                if ( $customer_name ) {
                    echo esc_html( $customer_name );
                    if ( $customer_email ) {
                        echo '<br><a href="mailto:' . esc_attr( $customer_email ) . '">' . esc_html( $customer_email ) . '</a>';
                    }
                } else {
                    echo esc_html__( 'Not set', 'aqualuxe' );
                }
                break;

            case 'status':
                $status = get_post_meta( $post_id, '_aqualuxe_booking_status', true );
                if ( ! $status ) {
                    $status = 'pending';
                }
                $status_labels = [
                    'pending'   => __( 'Pending', 'aqualuxe' ),
                    'confirmed' => __( 'Confirmed', 'aqualuxe' ),
                    'completed' => __( 'Completed', 'aqualuxe' ),
                    'cancelled' => __( 'Cancelled', 'aqualuxe' ),
                ];
                $status_classes = [
                    'pending'   => 'status-pending',
                    'confirmed' => 'status-confirmed',
                    'completed' => 'status-completed',
                    'cancelled' => 'status-cancelled',
                ];
                echo '<span class="booking-status ' . esc_attr( $status_classes[ $status ] ) . '">' . esc_html( $status_labels[ $status ] ) . '</span>';
                break;

            case 'created':
                echo esc_html( get_the_date( '', $post_id ) );
                break;
        }
    }

    /**
     * Sortable booking columns
     *
     * @param array $columns
     * @return array
     */
    public function sortable_booking_columns( $columns ) {
        $columns['booking_date'] = 'booking_date';
        $columns['status'] = 'status';
        $columns['created'] = 'date';
        return $columns;
    }

    /**
     * Add bookable columns
     *
     * @param array $columns
     * @return array
     */
    public function add_bookable_columns( $columns ) {
        $new_columns = [];

        foreach ( $columns as $key => $value ) {
            if ( $key === 'title' ) {
                $new_columns[ $key ] = $value;
                $new_columns['duration'] = __( 'Duration', 'aqualuxe' );
                $new_columns['capacity'] = __( 'Capacity', 'aqualuxe' );
                $new_columns['price'] = __( 'Price', 'aqualuxe' );
                $new_columns['availability'] = __( 'Availability', 'aqualuxe' );
            } else {
                $new_columns[ $key ] = $value;
            }
        }

        return $new_columns;
    }

    /**
     * Render bookable columns
     *
     * @param string $column
     * @param int $post_id
     * @return void
     */
    public function render_bookable_columns( $column, $post_id ) {
        switch ( $column ) {
            case 'duration':
                $duration = get_post_meta( $post_id, '_aqualuxe_bookable_duration', true );
                if ( $duration ) {
                    echo esc_html( $duration ) . ' ' . esc_html__( 'minutes', 'aqualuxe' );
                } else {
                    echo esc_html__( 'Not set', 'aqualuxe' );
                }
                break;

            case 'capacity':
                $capacity = get_post_meta( $post_id, '_aqualuxe_bookable_capacity', true );
                if ( $capacity ) {
                    echo esc_html( $capacity );
                } else {
                    echo esc_html__( 'Not set', 'aqualuxe' );
                }
                break;

            case 'price':
                $price = get_post_meta( $post_id, '_aqualuxe_bookable_price', true );
                if ( $price ) {
                    echo esc_html( wc_price( $price ) );
                    $special_price = get_post_meta( $post_id, '_aqualuxe_bookable_special_price', true );
                    if ( $special_price ) {
                        echo '<br><span class="special-price">' . esc_html( wc_price( $special_price ) ) . '</span>';
                    }
                } else {
                    echo esc_html__( 'Not set', 'aqualuxe' );
                }
                break;

            case 'availability':
                $availability = get_post_meta( $post_id, '_aqualuxe_bookable_availability', true );
                if ( is_array( $availability ) ) {
                    $days = [];
                    foreach ( $availability as $day => $data ) {
                        if ( isset( $data['enabled'] ) && $data['enabled'] ) {
                            $days[] = ucfirst( $day );
                        }
                    }
                    if ( count( $days ) > 0 ) {
                        echo esc_html( implode( ', ', $days ) );
                    } else {
                        echo esc_html__( 'Not available', 'aqualuxe' );
                    }
                } else {
                    echo esc_html__( 'Not set', 'aqualuxe' );
                }
                break;
        }
    }

    /**
     * Sortable bookable columns
     *
     * @param array $columns
     * @return array
     */
    public function sortable_bookable_columns( $columns ) {
        $columns['duration'] = 'duration';
        $columns['capacity'] = 'capacity';
        $columns['price'] = 'price';
        return $columns;
    }

    /**
     * Add admin filters
     *
     * @param string $post_type
     * @return void
     */
    public function add_admin_filters( $post_type ) {
        if ( 'aqualuxe_booking' !== $post_type ) {
            return;
        }

        // Filter by status
        $status = isset( $_GET['booking_status'] ) ? sanitize_text_field( $_GET['booking_status'] ) : '';
        $status_options = [
            ''          => __( 'All Statuses', 'aqualuxe' ),
            'pending'   => __( 'Pending', 'aqualuxe' ),
            'confirmed' => __( 'Confirmed', 'aqualuxe' ),
            'completed' => __( 'Completed', 'aqualuxe' ),
            'cancelled' => __( 'Cancelled', 'aqualuxe' ),
        ];

        echo '<select name="booking_status">';
        foreach ( $status_options as $value => $label ) {
            echo '<option value="' . esc_attr( $value ) . '" ' . selected( $status, $value, false ) . '>' . esc_html( $label ) . '</option>';
        }
        echo '</select>';

        // Filter by date
        $date = isset( $_GET['booking_date'] ) ? sanitize_text_field( $_GET['booking_date'] ) : '';
        echo '<input type="date" name="booking_date" value="' . esc_attr( $date ) . '" placeholder="' . esc_attr__( 'Filter by date', 'aqualuxe' ) . '" />';
    }

    /**
     * Filter bookings by status
     *
     * @param \WP_Query $query
     * @return void
     */
    public function filter_bookings_by_status( $query ) {
        global $pagenow;

        if ( ! is_admin() || 'edit.php' !== $pagenow || ! $query->is_main_query() || 'aqualuxe_booking' !== $query->get( 'post_type' ) ) {
            return;
        }

        // Filter by status
        if ( isset( $_GET['booking_status'] ) && ! empty( $_GET['booking_status'] ) ) {
            $meta_query = $query->get( 'meta_query' );
            if ( ! is_array( $meta_query ) ) {
                $meta_query = [];
            }
            $meta_query[] = [
                'key'     => '_aqualuxe_booking_status',
                'value'   => sanitize_text_field( $_GET['booking_status'] ),
                'compare' => '=',
            ];
            $query->set( 'meta_query', $meta_query );
        }

        // Filter by date
        if ( isset( $_GET['booking_date'] ) && ! empty( $_GET['booking_date'] ) ) {
            $meta_query = $query->get( 'meta_query' );
            if ( ! is_array( $meta_query ) ) {
                $meta_query = [];
            }
            $meta_query[] = [
                'key'     => '_aqualuxe_booking_date',
                'value'   => sanitize_text_field( $_GET['booking_date'] ),
                'compare' => '=',
            ];
            $query->set( 'meta_query', $meta_query );
        }
    }

    /**
     * Add booking row actions
     *
     * @param array $actions
     * @param \WP_Post $post
     * @return array
     */
    public function add_booking_row_actions( $actions, $post ) {
        if ( 'aqualuxe_booking' === $post->post_type ) {
            $status = get_post_meta( $post->ID, '_aqualuxe_booking_status', true );
            if ( ! $status ) {
                $status = 'pending';
            }

            if ( 'pending' === $status ) {
                $actions['confirm'] = sprintf(
                    '<a href="%s" class="confirm-booking" data-booking-id="%d">%s</a>',
                    wp_nonce_url( admin_url( 'admin-ajax.php?action=aqualuxe_update_booking_status&booking_id=' . $post->ID . '&status=confirmed' ), 'aqualuxe_update_booking_status' ),
                    $post->ID,
                    __( 'Confirm', 'aqualuxe' )
                );
            }

            if ( 'pending' === $status || 'confirmed' === $status ) {
                $actions['complete'] = sprintf(
                    '<a href="%s" class="complete-booking" data-booking-id="%d">%s</a>',
                    wp_nonce_url( admin_url( 'admin-ajax.php?action=aqualuxe_update_booking_status&booking_id=' . $post->ID . '&status=completed' ), 'aqualuxe_update_booking_status' ),
                    $post->ID,
                    __( 'Complete', 'aqualuxe' )
                );

                $actions['cancel'] = sprintf(
                    '<a href="%s" class="cancel-booking" data-booking-id="%d">%s</a>',
                    wp_nonce_url( admin_url( 'admin-ajax.php?action=aqualuxe_update_booking_status&booking_id=' . $post->ID . '&status=cancelled' ), 'aqualuxe_update_booking_status' ),
                    $post->ID,
                    __( 'Cancel', 'aqualuxe' )
                );
            }

            $actions['view'] = sprintf(
                '<a href="%s" class="view-booking" data-booking-id="%d">%s</a>',
                wp_nonce_url( admin_url( 'admin-ajax.php?action=aqualuxe_get_booking_details&booking_id=' . $post->ID ), 'aqualuxe_get_booking_details' ),
                $post->ID,
                __( 'View Details', 'aqualuxe' )
            );
        }

        return $actions;
    }

    /**
     * Display admin notices
     *
     * @return void
     */
    public function display_admin_notices() {
        if ( isset( $_GET['booking_updated'] ) && '1' === $_GET['booking_updated'] ) {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Booking updated successfully.', 'aqualuxe' ) . '</p></div>';
        }

        if ( isset( $_GET['booking_error'] ) && '1' === $_GET['booking_error'] ) {
            echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__( 'An error occurred while updating the booking.', 'aqualuxe' ) . '</p></div>';
        }
    }

    /**
     * Ajax update booking status
     *
     * @return void
     */
    public function ajax_update_booking_status() {
        check_ajax_referer( 'aqualuxe_update_booking_status', '_wpnonce' );

        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( [ 'message' => __( 'You do not have permission to update bookings.', 'aqualuxe' ) ] );
        }

        $booking_id = isset( $_REQUEST['booking_id'] ) ? intval( $_REQUEST['booking_id'] ) : 0;
        $status = isset( $_REQUEST['status'] ) ? sanitize_text_field( $_REQUEST['status'] ) : '';

        if ( ! $booking_id || ! $status ) {
            wp_send_json_error( [ 'message' => __( 'Invalid booking ID or status.', 'aqualuxe' ) ] );
        }

        $valid_statuses = [ 'pending', 'confirmed', 'completed', 'cancelled' ];
        if ( ! in_array( $status, $valid_statuses, true ) ) {
            wp_send_json_error( [ 'message' => __( 'Invalid status.', 'aqualuxe' ) ] );
        }

        $result = update_post_meta( $booking_id, '_aqualuxe_booking_status', $status );

        if ( $result ) {
            // Trigger status change action
            do_action( 'aqualuxe_booking_status_changed', $booking_id, $status );

            wp_send_json_success( [
                'message' => __( 'Booking status updated successfully.', 'aqualuxe' ),
                'status'  => $status,
            ] );
        } else {
            wp_send_json_error( [ 'message' => __( 'Failed to update booking status.', 'aqualuxe' ) ] );
        }
    }

    /**
     * Ajax get booking details
     *
     * @return void
     */
    public function ajax_get_booking_details() {
        check_ajax_referer( 'aqualuxe_get_booking_details', '_wpnonce' );

        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( [ 'message' => __( 'You do not have permission to view booking details.', 'aqualuxe' ) ] );
        }

        $booking_id = isset( $_REQUEST['booking_id'] ) ? intval( $_REQUEST['booking_id'] ) : 0;

        if ( ! $booking_id ) {
            wp_send_json_error( [ 'message' => __( 'Invalid booking ID.', 'aqualuxe' ) ] );
        }

        $booking = get_post( $booking_id );

        if ( ! $booking || 'aqualuxe_booking' !== $booking->post_type ) {
            wp_send_json_error( [ 'message' => __( 'Booking not found.', 'aqualuxe' ) ] );
        }

        $bookable_id = get_post_meta( $booking_id, '_aqualuxe_booking_bookable_id', true );
        $bookable = $bookable_id ? get_post( $bookable_id ) : null;

        $date = get_post_meta( $booking_id, '_aqualuxe_booking_date', true );
        $time = get_post_meta( $booking_id, '_aqualuxe_booking_time', true );
        $status = get_post_meta( $booking_id, '_aqualuxe_booking_status', true );
        if ( ! $status ) {
            $status = 'pending';
        }

        $customer_name = get_post_meta( $booking_id, '_aqualuxe_booking_customer_name', true );
        $customer_email = get_post_meta( $booking_id, '_aqualuxe_booking_customer_email', true );
        $customer_phone = get_post_meta( $booking_id, '_aqualuxe_booking_customer_phone', true );
        $customer_notes = get_post_meta( $booking_id, '_aqualuxe_booking_customer_notes', true );

        $status_labels = [
            'pending'   => __( 'Pending', 'aqualuxe' ),
            'confirmed' => __( 'Confirmed', 'aqualuxe' ),
            'completed' => __( 'Completed', 'aqualuxe' ),
            'cancelled' => __( 'Cancelled', 'aqualuxe' ),
        ];

        $data = [
            'id'             => $booking_id,
            'title'          => $booking->post_title,
            'bookable'       => $bookable ? [
                'id'    => $bookable->ID,
                'title' => $bookable->post_title,
            ] : null,
            'date'           => $date ? date_i18n( get_option( 'date_format' ), strtotime( $date ) ) : '',
            'time'           => $time ? date_i18n( get_option( 'time_format' ), strtotime( $time ) ) : '',
            'status'         => $status,
            'status_label'   => isset( $status_labels[ $status ] ) ? $status_labels[ $status ] : $status,
            'customer'       => [
                'name'  => $customer_name,
                'email' => $customer_email,
                'phone' => $customer_phone,
                'notes' => $customer_notes,
            ],
            'created'        => get_the_date( '', $booking_id ),
            'edit_url'       => get_edit_post_link( $booking_id, 'raw' ),
        ];

        wp_send_json_success( $data );
    }

    /**
     * Ajax get bookable availability
     *
     * @return void
     */
    public function ajax_get_bookable_availability() {
        check_ajax_referer( 'aqualuxe_get_bookable_availability', '_wpnonce' );

        $bookable_id = isset( $_REQUEST['bookable_id'] ) ? intval( $_REQUEST['bookable_id'] ) : 0;
        $date = isset( $_REQUEST['date'] ) ? sanitize_text_field( $_REQUEST['date'] ) : '';

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

        // Mark booked slots
        $booked_slots = [];
        foreach ( $existing_bookings as $booking ) {
            $booking_time = get_post_meta( $booking->ID, '_aqualuxe_booking_time', true );
            if ( $booking_time ) {
                $booked_slots[] = date( 'H:i', strtotime( $booking_time ) );
            }
        }

        // Prepare available slots
        $available_slots = [];
        foreach ( $time_slots as $slot ) {
            $available_slots[] = [
                'time'      => $slot,
                'formatted' => date_i18n( get_option( 'time_format' ), strtotime( $slot ) ),
                'available' => ! in_array( $slot, $booked_slots, true ),
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
}

// Initialize the admin class
Admin::get_instance();