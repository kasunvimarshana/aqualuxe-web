<?php
/**
 * Booking Class
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
 * Booking Class
 * 
 * This class represents a single booking.
 */
class Booking {
    /**
     * Booking ID
     *
     * @var int
     */
    private $id;

    /**
     * Bookable item ID
     *
     * @var int
     */
    private $bookable_id;

    /**
     * Booking date
     *
     * @var string
     */
    private $date;

    /**
     * Booking time
     *
     * @var string
     */
    private $time;

    /**
     * Booking status
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
     * Customer notes
     *
     * @var string
     */
    private $customer_notes;

    /**
     * Constructor
     *
     * @param int $booking_id
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
     * @return boolean
     */
    public function load() {
        if ( ! $this->id ) {
            return false;
        }

        $booking = get_post( $this->id );

        if ( ! $booking || 'aqualuxe_booking' !== $booking->post_type ) {
            return false;
        }

        $this->bookable_id = get_post_meta( $this->id, '_aqualuxe_booking_bookable_id', true );
        $this->date = get_post_meta( $this->id, '_aqualuxe_booking_date', true );
        $this->time = get_post_meta( $this->id, '_aqualuxe_booking_time', true );
        $this->status = get_post_meta( $this->id, '_aqualuxe_booking_status', true );
        if ( ! $this->status ) {
            $this->status = 'pending';
        }

        $this->customer_name = get_post_meta( $this->id, '_aqualuxe_booking_customer_name', true );
        $this->customer_email = get_post_meta( $this->id, '_aqualuxe_booking_customer_email', true );
        $this->customer_phone = get_post_meta( $this->id, '_aqualuxe_booking_customer_phone', true );
        $this->customer_notes = get_post_meta( $this->id, '_aqualuxe_booking_customer_notes', true );

        return true;
    }

    /**
     * Save booking data
     *
     * @return boolean
     */
    public function save() {
        $post_data = [
            'post_title'   => sprintf( __( 'Booking #%s', 'aqualuxe' ), $this->id ? $this->id : '{{id}}' ),
            'post_content' => '',
            'post_status'  => 'publish',
            'post_type'    => 'aqualuxe_booking',
        ];

        if ( $this->id ) {
            $post_data['ID'] = $this->id;
            $result = wp_update_post( $post_data );
        } else {
            $result = wp_insert_post( $post_data );
            if ( $result && ! is_wp_error( $result ) ) {
                $this->id = $result;
                // Update the title with the actual ID
                wp_update_post( [
                    'ID'         => $this->id,
                    'post_title' => sprintf( __( 'Booking #%s', 'aqualuxe' ), $this->id ),
                ] );
            }
        }

        if ( ! $result || is_wp_error( $result ) ) {
            return false;
        }

        // Save meta data
        update_post_meta( $this->id, '_aqualuxe_booking_bookable_id', $this->bookable_id );
        update_post_meta( $this->id, '_aqualuxe_booking_date', $this->date );
        update_post_meta( $this->id, '_aqualuxe_booking_time', $this->time );
        update_post_meta( $this->id, '_aqualuxe_booking_status', $this->status );
        update_post_meta( $this->id, '_aqualuxe_booking_customer_name', $this->customer_name );
        update_post_meta( $this->id, '_aqualuxe_booking_customer_email', $this->customer_email );
        update_post_meta( $this->id, '_aqualuxe_booking_customer_phone', $this->customer_phone );
        update_post_meta( $this->id, '_aqualuxe_booking_customer_notes', $this->customer_notes );

        // Trigger action
        do_action( 'aqualuxe_booking_saved', $this->id, $this );

        return true;
    }

    /**
     * Delete booking
     *
     * @return boolean
     */
    public function delete() {
        if ( ! $this->id ) {
            return false;
        }

        // Trigger action before deletion
        do_action( 'aqualuxe_booking_before_delete', $this->id, $this );

        $result = wp_delete_post( $this->id, true );

        if ( $result ) {
            // Trigger action after deletion
            do_action( 'aqualuxe_booking_deleted', $this->id );
            return true;
        }

        return false;
    }

    /**
     * Update booking status
     *
     * @param string $status
     * @return boolean
     */
    public function update_status( $status ) {
        $valid_statuses = [ 'pending', 'confirmed', 'completed', 'cancelled' ];
        if ( ! in_array( $status, $valid_statuses, true ) ) {
            return false;
        }

        $old_status = $this->status;
        $this->status = $status;

        $result = update_post_meta( $this->id, '_aqualuxe_booking_status', $status );

        if ( $result ) {
            // Trigger status change action
            do_action( 'aqualuxe_booking_status_changed', $this->id, $status, $old_status, $this );
            return true;
        }

        return false;
    }

    /**
     * Check if booking is available
     *
     * @param int $bookable_id
     * @param string $date
     * @param string $time
     * @return boolean
     */
    public static function is_available( $bookable_id, $date, $time ) {
        // Check if the bookable item exists
        $bookable = get_post( $bookable_id );
        if ( ! $bookable || 'aqualuxe_bookable' !== $bookable->post_type ) {
            return false;
        }

        // Check if the date is valid
        if ( ! $date || strtotime( $date ) < strtotime( date( 'Y-m-d' ) ) ) {
            return false;
        }

        // Check if the time is valid
        if ( ! $time ) {
            return false;
        }

        // Check if the bookable item is available on this day
        $day_of_week = strtolower( date( 'l', strtotime( $date ) ) );
        $availability = get_post_meta( $bookable_id, '_aqualuxe_bookable_availability', true );

        if ( ! is_array( $availability ) || ! isset( $availability[ $day_of_week ] ) || ! isset( $availability[ $day_of_week ]['enabled'] ) || ! $availability[ $day_of_week ]['enabled'] ) {
            return false;
        }

        // Check if the time is within the available hours
        $start_time = isset( $availability[ $day_of_week ]['start'] ) ? $availability[ $day_of_week ]['start'] : '09:00';
        $end_time = isset( $availability[ $day_of_week ]['end'] ) ? $availability[ $day_of_week ]['end'] : '17:00';

        $booking_time = strtotime( $time );
        $start_time_timestamp = strtotime( $start_time );
        $end_time_timestamp = strtotime( $end_time );

        if ( $booking_time < $start_time_timestamp || $booking_time >= $end_time_timestamp ) {
            return false;
        }

        // Check if there's already a booking for this time slot
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
                    'key'   => '_aqualuxe_booking_time',
                    'value' => $time,
                ],
                [
                    'key'     => '_aqualuxe_booking_status',
                    'value'   => [ 'pending', 'confirmed' ],
                    'compare' => 'IN',
                ],
            ],
        ] );

        if ( ! empty( $existing_bookings ) ) {
            // Check if the bookable item has enough capacity
            $capacity = get_post_meta( $bookable_id, '_aqualuxe_bookable_capacity', true );
            if ( ! $capacity || count( $existing_bookings ) >= $capacity ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Create a new booking
     *
     * @param array $data
     * @return Booking|WP_Error
     */
    public static function create( $data ) {
        // Validate required fields
        $required_fields = [ 'bookable_id', 'date', 'time', 'customer_name', 'customer_email' ];
        foreach ( $required_fields as $field ) {
            if ( ! isset( $data[ $field ] ) || empty( $data[ $field ] ) ) {
                return new \WP_Error( 'missing_field', sprintf( __( 'Missing required field: %s', 'aqualuxe' ), $field ) );
            }
        }

        // Check if the booking is available
        if ( ! self::is_available( $data['bookable_id'], $data['date'], $data['time'] ) ) {
            return new \WP_Error( 'not_available', __( 'The selected time slot is not available.', 'aqualuxe' ) );
        }

        // Create the booking
        $booking = new self();
        $booking->bookable_id = $data['bookable_id'];
        $booking->date = $data['date'];
        $booking->time = $data['time'];
        $booking->status = isset( $data['status'] ) ? $data['status'] : 'pending';
        $booking->customer_name = $data['customer_name'];
        $booking->customer_email = $data['customer_email'];
        $booking->customer_phone = isset( $data['customer_phone'] ) ? $data['customer_phone'] : '';
        $booking->customer_notes = isset( $data['customer_notes'] ) ? $data['customer_notes'] : '';

        // Save the booking
        if ( ! $booking->save() ) {
            return new \WP_Error( 'save_failed', __( 'Failed to save the booking.', 'aqualuxe' ) );
        }

        return $booking;
    }

    /**
     * Get booking by ID
     *
     * @param int $booking_id
     * @return Booking|false
     */
    public static function get( $booking_id ) {
        $booking = new self( $booking_id );
        return $booking->id ? $booking : false;
    }

    /**
     * Get bookings
     *
     * @param array $args
     * @return array
     */
    public static function get_bookings( $args = [] ) {
        $defaults = [
            'bookable_id' => 0,
            'date'        => '',
            'status'      => '',
            'customer'    => '',
            'orderby'     => 'date',
            'order'       => 'DESC',
            'limit'       => -1,
            'offset'      => 0,
        ];

        $args = wp_parse_args( $args, $defaults );

        $query_args = [
            'post_type'      => 'aqualuxe_booking',
            'posts_per_page' => $args['limit'],
            'offset'         => $args['offset'],
            'orderby'        => 'meta_value',
            'meta_key'       => '_aqualuxe_booking_date',
            'order'          => $args['order'],
        ];

        $meta_query = [];

        if ( $args['bookable_id'] ) {
            $meta_query[] = [
                'key'   => '_aqualuxe_booking_bookable_id',
                'value' => $args['bookable_id'],
            ];
        }

        if ( $args['date'] ) {
            $meta_query[] = [
                'key'   => '_aqualuxe_booking_date',
                'value' => $args['date'],
            ];
        }

        if ( $args['status'] ) {
            $meta_query[] = [
                'key'   => '_aqualuxe_booking_status',
                'value' => $args['status'],
            ];
        }

        if ( $args['customer'] ) {
            $meta_query[] = [
                'relation' => 'OR',
                [
                    'key'     => '_aqualuxe_booking_customer_name',
                    'value'   => $args['customer'],
                    'compare' => 'LIKE',
                ],
                [
                    'key'     => '_aqualuxe_booking_customer_email',
                    'value'   => $args['customer'],
                    'compare' => 'LIKE',
                ],
            ];
        }

        if ( ! empty( $meta_query ) ) {
            $query_args['meta_query'] = $meta_query;
        }

        $posts = get_posts( $query_args );
        $bookings = [];

        foreach ( $posts as $post ) {
            $bookings[] = new self( $post->ID );
        }

        return $bookings;
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
     * Get bookable item ID
     *
     * @return int
     */
    public function get_bookable_id() {
        return $this->bookable_id;
    }

    /**
     * Set bookable item ID
     *
     * @param int $bookable_id
     * @return void
     */
    public function set_bookable_id( $bookable_id ) {
        $this->bookable_id = $bookable_id;
    }

    /**
     * Get booking date
     *
     * @param string $format
     * @return string
     */
    public function get_date( $format = '' ) {
        if ( ! $format ) {
            return $this->date;
        }
        return date_i18n( $format, strtotime( $this->date ) );
    }

    /**
     * Set booking date
     *
     * @param string $date
     * @return void
     */
    public function set_date( $date ) {
        $this->date = $date;
    }

    /**
     * Get booking time
     *
     * @param string $format
     * @return string
     */
    public function get_time( $format = '' ) {
        if ( ! $format ) {
            return $this->time;
        }
        return date_i18n( $format, strtotime( $this->time ) );
    }

    /**
     * Set booking time
     *
     * @param string $time
     * @return void
     */
    public function set_time( $time ) {
        $this->time = $time;
    }

    /**
     * Get booking status
     *
     * @return string
     */
    public function get_status() {
        return $this->status;
    }

    /**
     * Get booking status label
     *
     * @return string
     */
    public function get_status_label() {
        $status_labels = [
            'pending'   => __( 'Pending', 'aqualuxe' ),
            'confirmed' => __( 'Confirmed', 'aqualuxe' ),
            'completed' => __( 'Completed', 'aqualuxe' ),
            'cancelled' => __( 'Cancelled', 'aqualuxe' ),
        ];

        return isset( $status_labels[ $this->status ] ) ? $status_labels[ $this->status ] : $this->status;
    }

    /**
     * Set booking status
     *
     * @param string $status
     * @return void
     */
    public function set_status( $status ) {
        $this->status = $status;
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
     * Set customer name
     *
     * @param string $name
     * @return void
     */
    public function set_customer_name( $name ) {
        $this->customer_name = $name;
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
     * Set customer email
     *
     * @param string $email
     * @return void
     */
    public function set_customer_email( $email ) {
        $this->customer_email = $email;
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
     * Set customer phone
     *
     * @param string $phone
     * @return void
     */
    public function set_customer_phone( $phone ) {
        $this->customer_phone = $phone;
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
     * Set customer notes
     *
     * @param string $notes
     * @return void
     */
    public function set_customer_notes( $notes ) {
        $this->customer_notes = $notes;
    }

    /**
     * Get bookable item
     *
     * @return WP_Post|null
     */
    public function get_bookable() {
        if ( ! $this->bookable_id ) {
            return null;
        }
        return get_post( $this->bookable_id );
    }

    /**
     * Get bookable item title
     *
     * @return string
     */
    public function get_bookable_title() {
        $bookable = $this->get_bookable();
        return $bookable ? $bookable->post_title : '';
    }

    /**
     * Get booking price
     *
     * @return float
     */
    public function get_price() {
        if ( ! $this->bookable_id ) {
            return 0;
        }

        $price = get_post_meta( $this->bookable_id, '_aqualuxe_bookable_price', true );
        if ( ! $price ) {
            return 0;
        }

        // Check if there's a special price
        $special_price = get_post_meta( $this->bookable_id, '_aqualuxe_bookable_special_price', true );
        if ( $special_price ) {
            $special_price_start = get_post_meta( $this->bookable_id, '_aqualuxe_bookable_special_price_start', true );
            $special_price_end = get_post_meta( $this->bookable_id, '_aqualuxe_bookable_special_price_end', true );

            if ( $special_price_start && $special_price_end ) {
                $booking_date = strtotime( $this->date );
                $start_date = strtotime( $special_price_start );
                $end_date = strtotime( $special_price_end );

                if ( $booking_date >= $start_date && $booking_date <= $end_date ) {
                    return (float) $special_price;
                }
            }
        }

        return (float) $price;
    }

    /**
     * Get booking duration
     *
     * @return int
     */
    public function get_duration() {
        if ( ! $this->bookable_id ) {
            return 0;
        }

        $duration = get_post_meta( $this->bookable_id, '_aqualuxe_bookable_duration', true );
        return $duration ? (int) $duration : 0;
    }

    /**
     * Get booking location
     *
     * @return string
     */
    public function get_location() {
        if ( ! $this->bookable_id ) {
            return '';
        }

        $location = get_post_meta( $this->bookable_id, '_aqualuxe_bookable_location', true );
        return $location ? $location : '';
    }

    /**
     * Get booking data
     *
     * @return array
     */
    public function get_data() {
        return [
            'id'             => $this->id,
            'bookable_id'    => $this->bookable_id,
            'bookable_title' => $this->get_bookable_title(),
            'date'           => $this->date,
            'time'           => $this->time,
            'status'         => $this->status,
            'status_label'   => $this->get_status_label(),
            'customer_name'  => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'customer_notes' => $this->customer_notes,
            'price'          => $this->get_price(),
            'duration'       => $this->get_duration(),
            'location'       => $this->get_location(),
        ];
    }
}