<?php
/**
 * Service Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * Service Class
 * 
 * This class handles service-related functionality.
 */
class Service {
    /**
     * Service ID
     *
     * @var int
     */
    private $id;

    /**
     * Service title
     *
     * @var string
     */
    private $title;

    /**
     * Service description
     *
     * @var string
     */
    private $description;

    /**
     * Service duration
     *
     * @var int
     */
    private $duration;

    /**
     * Buffer before
     *
     * @var int
     */
    private $buffer_before;

    /**
     * Buffer after
     *
     * @var int
     */
    private $buffer_after;

    /**
     * Service capacity
     *
     * @var int
     */
    private $capacity;

    /**
     * Service location
     *
     * @var string
     */
    private $location;

    /**
     * Service availability
     *
     * @var array
     */
    private $availability;

    /**
     * Service price
     *
     * @var float
     */
    private $price;

    /**
     * Service sale price
     *
     * @var float
     */
    private $sale_price;

    /**
     * Service deposit
     *
     * @var float
     */
    private $deposit;

    /**
     * Service deposit type
     *
     * @var string
     */
    private $deposit_type;

    /**
     * Service tax class
     *
     * @var string
     */
    private $tax_class;

    /**
     * WooCommerce product ID
     *
     * @var int
     */
    private $wc_product_id;

    /**
     * Constructor
     *
     * @param int $service_id Service ID.
     */
    public function __construct( $service_id = 0 ) {
        if ( $service_id > 0 ) {
            $this->id = $service_id;
            $this->load();
        }
    }

    /**
     * Load service data
     *
     * @return void
     */
    private function load() {
        // Check if service exists
        $service = get_post( $this->id );

        if ( ! $service || 'aqualuxe_service' !== $service->post_type ) {
            return;
        }

        // Load service data
        $this->title = $service->post_title;
        $this->description = $service->post_content;
        $this->duration = get_post_meta( $this->id, '_duration', true );
        $this->buffer_before = get_post_meta( $this->id, '_buffer_before', true );
        $this->buffer_after = get_post_meta( $this->id, '_buffer_after', true );
        $this->capacity = get_post_meta( $this->id, '_capacity', true );
        $this->location = get_post_meta( $this->id, '_location', true );
        $this->availability = get_post_meta( $this->id, '_availability', true );
        $this->price = get_post_meta( $this->id, '_price', true );
        $this->sale_price = get_post_meta( $this->id, '_sale_price', true );
        $this->deposit = get_post_meta( $this->id, '_deposit', true );
        $this->deposit_type = get_post_meta( $this->id, '_deposit_type', true );
        $this->tax_class = get_post_meta( $this->id, '_tax_class', true );
        $this->wc_product_id = get_post_meta( $this->id, '_wc_product_id', true );

        // Set default values
        if ( ! $this->duration ) {
            $this->duration = 60; // Default to 60 minutes
        }

        if ( ! $this->buffer_before ) {
            $this->buffer_before = 0;
        }

        if ( ! $this->buffer_after ) {
            $this->buffer_after = 0;
        }

        if ( ! $this->capacity ) {
            $this->capacity = 1;
        }

        if ( ! $this->availability ) {
            $this->availability = [
                'monday'    => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
                'tuesday'   => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
                'wednesday' => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
                'thursday'  => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
                'friday'    => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
                'saturday'  => [ 'enabled' => false, 'slots' => [] ],
                'sunday'    => [ 'enabled' => false, 'slots' => [] ],
            ];
        }

        if ( ! $this->deposit_type ) {
            $this->deposit_type = 'percentage';
        }

        if ( ! $this->tax_class ) {
            $this->tax_class = 'standard';
        }
    }

    /**
     * Create a new service
     *
     * @param array $data Service data.
     * @return int|WP_Error
     */
    public function create( $data ) {
        // Validate required fields
        if ( empty( $data['title'] ) ) {
            return new \WP_Error( 'missing_title', __( 'Service title is required', 'aqualuxe' ) );
        }

        // Set service data
        $this->title = sanitize_text_field( $data['title'] );
        $this->description = isset( $data['description'] ) ? wp_kses_post( $data['description'] ) : '';
        $this->duration = isset( $data['duration'] ) ? absint( $data['duration'] ) : 60;
        $this->buffer_before = isset( $data['buffer_before'] ) ? absint( $data['buffer_before'] ) : 0;
        $this->buffer_after = isset( $data['buffer_after'] ) ? absint( $data['buffer_after'] ) : 0;
        $this->capacity = isset( $data['capacity'] ) ? absint( $data['capacity'] ) : 1;
        $this->location = isset( $data['location'] ) ? sanitize_text_field( $data['location'] ) : '';
        $this->availability = isset( $data['availability'] ) ? $data['availability'] : [
            'monday'    => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
            'tuesday'   => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
            'wednesday' => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
            'thursday'  => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
            'friday'    => [ 'enabled' => true, 'slots' => [ [ 'start' => '09:00', 'end' => '17:00' ] ] ],
            'saturday'  => [ 'enabled' => false, 'slots' => [] ],
            'sunday'    => [ 'enabled' => false, 'slots' => [] ],
        ];
        $this->price = isset( $data['price'] ) ? floatval( $data['price'] ) : 0;
        $this->sale_price = isset( $data['sale_price'] ) ? floatval( $data['sale_price'] ) : '';
        $this->deposit = isset( $data['deposit'] ) ? floatval( $data['deposit'] ) : 0;
        $this->deposit_type = isset( $data['deposit_type'] ) ? sanitize_text_field( $data['deposit_type'] ) : 'percentage';
        $this->tax_class = isset( $data['tax_class'] ) ? sanitize_text_field( $data['tax_class'] ) : 'standard';

        // Create service post
        $service_id = wp_insert_post(
            [
                'post_title'   => $this->title,
                'post_content' => $this->description,
                'post_status'  => 'publish',
                'post_type'    => 'aqualuxe_service',
                'post_author'  => get_current_user_id(),
            ]
        );

        if ( is_wp_error( $service_id ) ) {
            return $service_id;
        }

        // Set service ID
        $this->id = $service_id;

        // Save service data
        $this->save();

        // Set service categories
        if ( isset( $data['categories'] ) && is_array( $data['categories'] ) ) {
            wp_set_object_terms( $this->id, $data['categories'], 'aqualuxe_service_category' );
        }

        // Create WooCommerce product if integration is enabled
        if ( class_exists( 'WooCommerce' ) ) {
            $settings = get_option( 'aqualuxe_bookings_settings', [] );
            $woocommerce_integration = isset( $settings['woocommerce_integration'] ) ? $settings['woocommerce_integration'] : false;
            
            if ( $woocommerce_integration ) {
                $this->create_woocommerce_product();
            }
        }

        return $this->id;
    }

    /**
     * Update service
     *
     * @param array $data Service data.
     * @return bool|WP_Error
     */
    public function update( $data ) {
        // Check if service exists
        if ( ! $this->id ) {
            return new \WP_Error( 'invalid_service', __( 'Invalid service', 'aqualuxe' ) );
        }

        // Update service data
        if ( isset( $data['title'] ) ) {
            $this->title = sanitize_text_field( $data['title'] );
        }

        if ( isset( $data['description'] ) ) {
            $this->description = wp_kses_post( $data['description'] );
        }

        if ( isset( $data['duration'] ) ) {
            $this->duration = absint( $data['duration'] );
        }

        if ( isset( $data['buffer_before'] ) ) {
            $this->buffer_before = absint( $data['buffer_before'] );
        }

        if ( isset( $data['buffer_after'] ) ) {
            $this->buffer_after = absint( $data['buffer_after'] );
        }

        if ( isset( $data['capacity'] ) ) {
            $this->capacity = absint( $data['capacity'] );
        }

        if ( isset( $data['location'] ) ) {
            $this->location = sanitize_text_field( $data['location'] );
        }

        if ( isset( $data['availability'] ) ) {
            $this->availability = $data['availability'];
        }

        if ( isset( $data['price'] ) ) {
            $this->price = floatval( $data['price'] );
        }

        if ( isset( $data['sale_price'] ) ) {
            $this->sale_price = floatval( $data['sale_price'] );
        }

        if ( isset( $data['deposit'] ) ) {
            $this->deposit = floatval( $data['deposit'] );
        }

        if ( isset( $data['deposit_type'] ) ) {
            $this->deposit_type = sanitize_text_field( $data['deposit_type'] );
        }

        if ( isset( $data['tax_class'] ) ) {
            $this->tax_class = sanitize_text_field( $data['tax_class'] );
        }

        // Update service post
        wp_update_post(
            [
                'ID'           => $this->id,
                'post_title'   => $this->title,
                'post_content' => $this->description,
            ]
        );

        // Save service data
        $this->save();

        // Update service categories
        if ( isset( $data['categories'] ) && is_array( $data['categories'] ) ) {
            wp_set_object_terms( $this->id, $data['categories'], 'aqualuxe_service_category' );
        }

        // Update WooCommerce product if integration is enabled
        if ( class_exists( 'WooCommerce' ) && $this->wc_product_id ) {
            $settings = get_option( 'aqualuxe_bookings_settings', [] );
            $woocommerce_integration = isset( $settings['woocommerce_integration'] ) ? $settings['woocommerce_integration'] : false;
            
            if ( $woocommerce_integration ) {
                $this->update_woocommerce_product();
            }
        }

        return true;
    }

    /**
     * Save service data
     *
     * @return void
     */
    private function save() {
        // Save service data
        update_post_meta( $this->id, '_duration', $this->duration );
        update_post_meta( $this->id, '_buffer_before', $this->buffer_before );
        update_post_meta( $this->id, '_buffer_after', $this->buffer_after );
        update_post_meta( $this->id, '_capacity', $this->capacity );
        update_post_meta( $this->id, '_location', $this->location );
        update_post_meta( $this->id, '_availability', $this->availability );
        update_post_meta( $this->id, '_price', $this->price );
        update_post_meta( $this->id, '_sale_price', $this->sale_price );
        update_post_meta( $this->id, '_deposit', $this->deposit );
        update_post_meta( $this->id, '_deposit_type', $this->deposit_type );
        update_post_meta( $this->id, '_tax_class', $this->tax_class );
    }

    /**
     * Delete service
     *
     * @return bool|WP_Error
     */
    public function delete() {
        // Check if service exists
        if ( ! $this->id ) {
            return new \WP_Error( 'invalid_service', __( 'Invalid service', 'aqualuxe' ) );
        }

        // Delete WooCommerce product if integration is enabled
        if ( class_exists( 'WooCommerce' ) && $this->wc_product_id ) {
            wp_delete_post( $this->wc_product_id, true );
        }

        // Delete service
        $result = wp_delete_post( $this->id, true );

        if ( ! $result ) {
            return new \WP_Error( 'delete_failed', __( 'Failed to delete service', 'aqualuxe' ) );
        }

        return true;
    }

    /**
     * Create WooCommerce product
     *
     * @return int|WP_Error
     */
    private function create_woocommerce_product() {
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return new \WP_Error( 'woocommerce_inactive', __( 'WooCommerce is not active', 'aqualuxe' ) );
        }

        // Get product type
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $product_type = isset( $settings['woocommerce_product_type'] ) ? $settings['woocommerce_product_type'] : 'simple';

        // Create product
        $product = new \WC_Product();
        $product->set_name( $this->title );
        $product->set_description( $this->description );
        $product->set_status( 'publish' );
        $product->set_catalog_visibility( 'visible' );
        $product->set_price( $this->price );
        $product->set_regular_price( $this->price );
        
        if ( $this->sale_price ) {
            $product->set_sale_price( $this->sale_price );
        }
        
        $product->set_tax_class( $this->tax_class );
        $product->set_sold_individually( true );
        $product->set_virtual( true );
        
        // Set product type
        if ( $product_type === 'variable' ) {
            $product = new \WC_Product_Variable();
        }
        
        // Save product
        $product_id = $product->save();
        
        // Set product meta
        update_post_meta( $product_id, '_aqualuxe_service_id', $this->id );
        update_post_meta( $product_id, '_aqualuxe_service_duration', $this->duration );
        update_post_meta( $product_id, '_aqualuxe_service_location', $this->location );
        
        // Set product image if service has featured image
        if ( has_post_thumbnail( $this->id ) ) {
            $thumbnail_id = get_post_thumbnail_id( $this->id );
            set_post_thumbnail( $product_id, $thumbnail_id );
        }
        
        // Save product ID to service
        $this->wc_product_id = $product_id;
        update_post_meta( $this->id, '_wc_product_id', $product_id );
        
        return $product_id;
    }

    /**
     * Update WooCommerce product
     *
     * @return bool|WP_Error
     */
    private function update_woocommerce_product() {
        // Check if WooCommerce is active
        if ( ! class_exists( 'WooCommerce' ) ) {
            return new \WP_Error( 'woocommerce_inactive', __( 'WooCommerce is not active', 'aqualuxe' ) );
        }

        // Check if product exists
        $product = wc_get_product( $this->wc_product_id );
        
        if ( ! $product ) {
            return $this->create_woocommerce_product();
        }

        // Update product
        $product->set_name( $this->title );
        $product->set_description( $this->description );
        $product->set_price( $this->price );
        $product->set_regular_price( $this->price );
        
        if ( $this->sale_price ) {
            $product->set_sale_price( $this->sale_price );
        } else {
            $product->set_sale_price( '' );
        }
        
        $product->set_tax_class( $this->tax_class );
        
        // Update product meta
        update_post_meta( $this->wc_product_id, '_aqualuxe_service_duration', $this->duration );
        update_post_meta( $this->wc_product_id, '_aqualuxe_service_location', $this->location );
        
        // Update product image if service has featured image
        if ( has_post_thumbnail( $this->id ) ) {
            $thumbnail_id = get_post_thumbnail_id( $this->id );
            set_post_thumbnail( $this->wc_product_id, $thumbnail_id );
        }
        
        // Save product
        $product->save();
        
        return true;
    }

    /**
     * Get available time slots for a specific date
     *
     * @param string $date Date in Y-m-d format.
     * @return array
     */
    public function get_available_time_slots( $date ) {
        // Check if date is valid
        if ( ! $this->is_valid_date( $date ) ) {
            return [];
        }

        // Get day of week
        $day_of_week = strtolower( date( 'l', strtotime( $date ) ) );

        // Check if the day is enabled
        if ( ! isset( $this->availability[ $day_of_week ] ) || ! $this->availability[ $day_of_week ]['enabled'] ) {
            return [];
        }

        // Check if there are slots for the day
        if ( empty( $this->availability[ $day_of_week ]['slots'] ) ) {
            return [];
        }

        // Get all time slots for the day
        $all_slots = [];
        
        foreach ( $this->availability[ $day_of_week ]['slots'] as $slot ) {
            $start_time = $slot['start'];
            $end_time = $slot['end'];
            
            $start_minutes = $this->time_to_minutes( $start_time );
            $end_minutes = $this->time_to_minutes( $end_time );
            
            // Generate time slots based on service duration and buffer times
            $total_duration = $this->duration + $this->buffer_before + $this->buffer_after;
            
            for ( $time = $start_minutes; $time <= $end_minutes - $this->duration; $time += $total_duration ) {
                $slot_time = $this->minutes_to_time( $time );
                $all_slots[] = $slot_time;
            }
        }

        // Get booked slots
        $booked_slots = $this->get_booked_slots( $date );
        
        // Remove booked slots
        $available_slots = array_diff( $all_slots, $booked_slots );
        
        // Remove slots in the past if date is today
        if ( $date === date( 'Y-m-d' ) ) {
            $current_time = date( 'H:i' );
            
            foreach ( $available_slots as $key => $slot ) {
                if ( $slot <= $current_time ) {
                    unset( $available_slots[ $key ] );
                }
            }
        }
        
        // Reset array keys
        $available_slots = array_values( $available_slots );
        
        return $available_slots;
    }

    /**
     * Get booked slots for a specific date
     *
     * @param string $date Date in Y-m-d format.
     * @return array
     */
    private function get_booked_slots( $date ) {
        // Get bookings for the date
        $args = [
            'post_type'      => 'aqualuxe_booking',
            'posts_per_page' => -1,
            'meta_query'     => [
                [
                    'key'   => '_service_id',
                    'value' => $this->id,
                ],
                [
                    'key'   => '_date',
                    'value' => $date,
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

        $bookings = new \WP_Query( $args );
        $booked_slots = [];

        if ( $bookings->have_posts() ) {
            while ( $bookings->have_posts() ) {
                $bookings->the_post();
                $booking_id = get_the_ID();
                $booking_time = get_post_meta( $booking_id, '_time', true );
                $booking_duration = get_post_meta( $booking_id, '_duration', true );
                
                if ( ! $booking_duration ) {
                    $booking_duration = $this->duration;
                }

                $booking_start_minutes = $this->time_to_minutes( $booking_time );
                $booking_end_minutes = $booking_start_minutes + $booking_duration;

                // Mark all slots that overlap with this booking as booked
                foreach ( $this->availability[ strtolower( date( 'l', strtotime( $date ) ) ) ]['slots'] as $slot ) {
                    $slot_start_minutes = $this->time_to_minutes( $slot['start'] );
                    $slot_end_minutes = $this->time_to_minutes( $slot['end'] );
                    
                    // Generate time slots based on service duration and buffer times
                    $total_duration = $this->duration + $this->buffer_before + $this->buffer_after;
                    
                    for ( $time = $slot_start_minutes; $time <= $slot_end_minutes - $this->duration; $time += $total_duration ) {
                        $slot_end_time = $time + $this->duration;
                        
                        // Check if slot overlaps with booking
                        if ( ( $time >= $booking_start_minutes && $time < $booking_end_minutes ) ||
                             ( $slot_end_time > $booking_start_minutes && $slot_end_time <= $booking_end_minutes ) ||
                             ( $time <= $booking_start_minutes && $slot_end_time >= $booking_end_minutes ) ) {
                            $booked_slots[] = $this->minutes_to_time( $time );
                        }
                    }
                }
            }
        }

        wp_reset_postdata();
        return $booked_slots;
    }

    /**
     * Check if the date is valid
     *
     * @param string $date Date in Y-m-d format.
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

        // Check if the date is in the future or today
        $today = date( 'Y-m-d' );
        if ( $date < $today ) {
            return false;
        }

        // Check maximum notice
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $maximum_notice = isset( $settings['maximum_notice'] ) ? $settings['maximum_notice'] : 90;
        $maximum_date = date( 'Y-m-d', strtotime( '+' . $maximum_notice . ' days' ) );
        
        if ( $date > $maximum_date ) {
            return false;
        }

        return true;
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
     * Convert minutes to time
     *
     * @param int $minutes Minutes.
     * @return string
     */
    private function minutes_to_time( $minutes ) {
        $hours = floor( $minutes / 60 );
        $mins = $minutes % 60;
        return sprintf( '%02d:%02d', $hours, $mins );
    }

    /**
     * Get service ID
     *
     * @return int
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Get service title
     *
     * @return string
     */
    public function get_title() {
        return $this->title;
    }

    /**
     * Get service description
     *
     * @return string
     */
    public function get_description() {
        return $this->description;
    }

    /**
     * Get service duration
     *
     * @return int
     */
    public function get_duration() {
        return $this->duration;
    }

    /**
     * Get buffer before
     *
     * @return int
     */
    public function get_buffer_before() {
        return $this->buffer_before;
    }

    /**
     * Get buffer after
     *
     * @return int
     */
    public function get_buffer_after() {
        return $this->buffer_after;
    }

    /**
     * Get service capacity
     *
     * @return int
     */
    public function get_capacity() {
        return $this->capacity;
    }

    /**
     * Get service location
     *
     * @return string
     */
    public function get_location() {
        return $this->location;
    }

    /**
     * Get service availability
     *
     * @return array
     */
    public function get_availability() {
        return $this->availability;
    }

    /**
     * Get service price
     *
     * @return float
     */
    public function get_price() {
        return $this->price;
    }

    /**
     * Get service sale price
     *
     * @return float
     */
    public function get_sale_price() {
        return $this->sale_price;
    }

    /**
     * Get service deposit
     *
     * @return float
     */
    public function get_deposit() {
        return $this->deposit;
    }

    /**
     * Get service deposit type
     *
     * @return string
     */
    public function get_deposit_type() {
        return $this->deposit_type;
    }

    /**
     * Get service tax class
     *
     * @return string
     */
    public function get_tax_class() {
        return $this->tax_class;
    }

    /**
     * Get WooCommerce product ID
     *
     * @return int
     */
    public function get_wc_product_id() {
        return $this->wc_product_id;
    }

    /**
     * Get service categories
     *
     * @return array
     */
    public function get_categories() {
        $categories = wp_get_post_terms( $this->id, 'aqualuxe_service_category', [ 'fields' => 'all' ] );
        return $categories;
    }

    /**
     * Get service URL
     *
     * @return string
     */
    public function get_url() {
        return get_permalink( $this->id );
    }

    /**
     * Get service edit URL
     *
     * @return string
     */
    public function get_edit_url() {
        return admin_url( 'post.php?post=' . $this->id . '&action=edit' );
    }

    /**
     * Get service thumbnail URL
     *
     * @param string $size Image size.
     * @return string
     */
    public function get_thumbnail_url( $size = 'thumbnail' ) {
        if ( has_post_thumbnail( $this->id ) ) {
            return get_the_post_thumbnail_url( $this->id, $size );
        }
        
        return '';
    }

    /**
     * Get service thumbnail
     *
     * @param string $size Image size.
     * @return string
     */
    public function get_thumbnail( $size = 'thumbnail' ) {
        if ( has_post_thumbnail( $this->id ) ) {
            return get_the_post_thumbnail( $this->id, $size );
        }
        
        return '';
    }

    /**
     * Get formatted duration
     *
     * @return string
     */
    public function get_formatted_duration() {
        if ( $this->duration < 60 ) {
            return sprintf( _n( '%d minute', '%d minutes', $this->duration, 'aqualuxe' ), $this->duration );
        } else {
            $hours = floor( $this->duration / 60 );
            $minutes = $this->duration % 60;
            
            if ( $minutes === 0 ) {
                return sprintf( _n( '%d hour', '%d hours', $hours, 'aqualuxe' ), $hours );
            } else {
                return sprintf( __( '%d hour %d minutes', 'aqualuxe' ), $hours, $minutes );
            }
        }
    }

    /**
     * Get formatted price
     *
     * @return string
     */
    public function get_formatted_price() {
        if ( $this->sale_price && $this->sale_price < $this->price ) {
            return '<del>' . wc_price( $this->price ) . '</del> <ins>' . wc_price( $this->sale_price ) . '</ins>';
        } else {
            return wc_price( $this->price );
        }
    }

    /**
     * Get current price
     *
     * @return float
     */
    public function get_current_price() {
        if ( $this->sale_price && $this->sale_price < $this->price ) {
            return $this->sale_price;
        } else {
            return $this->price;
        }
    }

    /**
     * Get deposit amount
     *
     * @return float
     */
    public function get_deposit_amount() {
        $price = $this->get_current_price();
        
        if ( $this->deposit_type === 'percentage' ) {
            return $price * ( $this->deposit / 100 );
        } else {
            return $this->deposit;
        }
    }

    /**
     * Get formatted deposit
     *
     * @return string
     */
    public function get_formatted_deposit() {
        if ( $this->deposit_type === 'percentage' ) {
            return $this->deposit . '%';
        } else {
            return wc_price( $this->deposit );
        }
    }

    /**
     * Check if service is available on a specific date
     *
     * @param string $date Date in Y-m-d format.
     * @return bool
     */
    public function is_available_on_date( $date ) {
        // Check if date is valid
        if ( ! $this->is_valid_date( $date ) ) {
            return false;
        }

        // Get day of week
        $day_of_week = strtolower( date( 'l', strtotime( $date ) ) );

        // Check if the day is enabled
        if ( ! isset( $this->availability[ $day_of_week ] ) || ! $this->availability[ $day_of_week ]['enabled'] ) {
            return false;
        }

        // Check if there are slots for the day
        if ( empty( $this->availability[ $day_of_week ]['slots'] ) ) {
            return false;
        }

        // Get available time slots
        $available_slots = $this->get_available_time_slots( $date );

        return ! empty( $available_slots );
    }

    /**
     * Get available dates for the next X days
     *
     * @param int $days Number of days to check.
     * @return array
     */
    public function get_available_dates( $days = 30 ) {
        $available_dates = [];
        $start_date = date( 'Y-m-d' );
        
        for ( $i = 0; $i < $days; $i++ ) {
            $date = date( 'Y-m-d', strtotime( $start_date . ' +' . $i . ' days' ) );
            
            if ( $this->is_available_on_date( $date ) ) {
                $available_dates[] = $date;
            }
        }
        
        return $available_dates;
    }

    /**
     * Get next available date
     *
     * @return string|bool
     */
    public function get_next_available_date() {
        $available_dates = $this->get_available_dates();
        
        if ( ! empty( $available_dates ) ) {
            return $available_dates[0];
        }
        
        return false;
    }

    /**
     * Get next available time slot
     *
     * @return string|bool
     */
    public function get_next_available_time_slot() {
        $next_date = $this->get_next_available_date();
        
        if ( $next_date ) {
            $available_slots = $this->get_available_time_slots( $next_date );
            
            if ( ! empty( $available_slots ) ) {
                return $available_slots[0];
            }
        }
        
        return false;
    }

    /**
     * Get booking URL
     *
     * @return string
     */
    public function get_booking_url() {
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $booking_page = isset( $settings['booking_page'] ) ? $settings['booking_page'] : '';
        
        if ( $booking_page ) {
            return add_query_arg( 'service_id', $this->id, get_permalink( $booking_page ) );
        }
        
        return '';
    }

    /**
     * Get WooCommerce product URL
     *
     * @return string
     */
    public function get_wc_product_url() {
        if ( $this->wc_product_id ) {
            return get_permalink( $this->wc_product_id );
        }
        
        return '';
    }
}