<?php
/**
 * Helper Functions
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

/**
 * Get services
 *
 * @param array $args Query arguments.
 * @return array
 */
function aqualuxe_get_services( $args = [] ) {
    // Default arguments
    $default_args = [
        'post_type'      => 'aqualuxe_service',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ];
    
    // Merge arguments
    $args = wp_parse_args( $args, $default_args );
    
    // Get services
    $services_query = new WP_Query( $args );
    $services = [];
    
    if ( $services_query->have_posts() ) {
        while ( $services_query->have_posts() ) {
            $services_query->the_post();
            $service_id = get_the_ID();
            $services[] = new AquaLuxe\Modules\Bookings\Service( $service_id );
        }
    }
    
    wp_reset_postdata();
    
    return $services;
}

/**
 * Get service
 *
 * @param int $service_id Service ID.
 * @return AquaLuxe\Modules\Bookings\Service
 */
function aqualuxe_get_service( $service_id ) {
    return new AquaLuxe\Modules\Bookings\Service( $service_id );
}

/**
 * Get bookings
 *
 * @param array $args Query arguments.
 * @return array
 */
function aqualuxe_get_bookings( $args = [] ) {
    // Default arguments
    $default_args = [
        'post_type'      => 'aqualuxe_booking',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    
    // Merge arguments
    $args = wp_parse_args( $args, $default_args );
    
    // Get bookings
    $bookings_query = new WP_Query( $args );
    $bookings = [];
    
    if ( $bookings_query->have_posts() ) {
        while ( $bookings_query->have_posts() ) {
            $bookings_query->the_post();
            $booking_id = get_the_ID();
            $bookings[] = new AquaLuxe\Modules\Bookings\Booking( $booking_id );
        }
    }
    
    wp_reset_postdata();
    
    return $bookings;
}

/**
 * Get booking
 *
 * @param int $booking_id Booking ID.
 * @return AquaLuxe\Modules\Bookings\Booking
 */
function aqualuxe_get_booking( $booking_id ) {
    return new AquaLuxe\Modules\Bookings\Booking( $booking_id );
}

/**
 * Create booking
 *
 * @param array $data Booking data.
 * @return int|WP_Error
 */
function aqualuxe_create_booking( $data ) {
    $booking = new AquaLuxe\Modules\Bookings\Booking();
    return $booking->create( $data );
}

/**
 * Update booking
 *
 * @param int   $booking_id Booking ID.
 * @param array $data Booking data.
 * @return bool|WP_Error
 */
function aqualuxe_update_booking( $booking_id, $data ) {
    $booking = new AquaLuxe\Modules\Bookings\Booking( $booking_id );
    return $booking->update( $data );
}

/**
 * Delete booking
 *
 * @param int $booking_id Booking ID.
 * @return bool|WP_Error
 */
function aqualuxe_delete_booking( $booking_id ) {
    $booking = new AquaLuxe\Modules\Bookings\Booking( $booking_id );
    return $booking->delete();
}

/**
 * Get service availability
 *
 * @param int    $service_id Service ID.
 * @param string $date Date in Y-m-d format.
 * @return array
 */
function aqualuxe_get_service_availability( $service_id, $date = '' ) {
    $availability = new AquaLuxe\Modules\Bookings\Availability( $service_id, $date );
    return $availability->get_available_time_slots();
}

/**
 * Check if service is available on date
 *
 * @param int    $service_id Service ID.
 * @param string $date Date in Y-m-d format.
 * @return bool
 */
function aqualuxe_is_service_available( $service_id, $date ) {
    $service = new AquaLuxe\Modules\Bookings\Service( $service_id );
    return $service->is_available_on_date( $date );
}

/**
 * Get service available dates
 *
 * @param int $service_id Service ID.
 * @param int $days Number of days to check.
 * @return array
 */
function aqualuxe_get_service_available_dates( $service_id, $days = 30 ) {
    $service = new AquaLuxe\Modules\Bookings\Service( $service_id );
    return $service->get_available_dates( $days );
}

/**
 * Get service next available date
 *
 * @param int $service_id Service ID.
 * @return string|bool
 */
function aqualuxe_get_service_next_available_date( $service_id ) {
    $service = new AquaLuxe\Modules\Bookings\Service( $service_id );
    return $service->get_next_available_date();
}

/**
 * Get service next available time slot
 *
 * @param int $service_id Service ID.
 * @return string|bool
 */
function aqualuxe_get_service_next_available_time_slot( $service_id ) {
    $service = new AquaLuxe\Modules\Bookings\Service( $service_id );
    return $service->get_next_available_time_slot();
}

/**
 * Get calendar
 *
 * @param int $service_id Service ID.
 * @param int $month Month.
 * @param int $year Year.
 * @return string
 */
function aqualuxe_get_calendar( $service_id = 0, $month = 0, $year = 0 ) {
    $calendar = new AquaLuxe\Modules\Bookings\Calendar( $service_id, $month, $year );
    return $calendar->generate();
}

/**
 * Get bookings for date
 *
 * @param string $date Date in Y-m-d format.
 * @param int    $service_id Service ID.
 * @return array
 */
function aqualuxe_get_bookings_for_date( $date, $service_id = 0 ) {
    $args = [
        'post_type'      => 'aqualuxe_booking',
        'posts_per_page' => -1,
        'meta_query'     => [
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
    
    // Add service filter if specified
    if ( $service_id ) {
        $args['meta_query'][] = [
            'key'   => '_service_id',
            'value' => $service_id,
        ];
    }
    
    return aqualuxe_get_bookings( $args );
}

/**
 * Get bookings for customer
 *
 * @param string $customer_email Customer email.
 * @return array
 */
function aqualuxe_get_bookings_for_customer( $customer_email ) {
    $args = [
        'post_type'      => 'aqualuxe_booking',
        'posts_per_page' => -1,
        'meta_query'     => [
            [
                'key'   => '_customer_email',
                'value' => $customer_email,
            ],
        ],
    ];
    
    return aqualuxe_get_bookings( $args );
}

/**
 * Get bookings by status
 *
 * @param string $status Booking status.
 * @return array
 */
function aqualuxe_get_bookings_by_status( $status ) {
    $args = [
        'post_type'      => 'aqualuxe_booking',
        'posts_per_page' => -1,
        'tax_query'      => [
            [
                'taxonomy' => 'aqualuxe_booking_status',
                'field'    => 'slug',
                'terms'    => $status,
            ],
        ],
    ];
    
    return aqualuxe_get_bookings( $args );
}

/**
 * Get upcoming bookings
 *
 * @param int $limit Number of bookings to get.
 * @return array
 */
function aqualuxe_get_upcoming_bookings( $limit = 10 ) {
    $args = [
        'post_type'      => 'aqualuxe_booking',
        'posts_per_page' => $limit,
        'meta_key'       => '_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => [
            [
                'key'     => '_date',
                'value'   => date( 'Y-m-d' ),
                'compare' => '>=',
                'type'    => 'DATE',
            ],
        ],
        'tax_query'      => [
            [
                'taxonomy' => 'aqualuxe_booking_status',
                'field'    => 'slug',
                'terms'    => [ 'confirmed' ],
            ],
        ],
    ];
    
    return aqualuxe_get_bookings( $args );
}

/**
 * Get booking status
 *
 * @param int $booking_id Booking ID.
 * @return string
 */
function aqualuxe_get_booking_status( $booking_id ) {
    $booking = new AquaLuxe\Modules\Bookings\Booking( $booking_id );
    return $booking->get_status();
}

/**
 * Update booking status
 *
 * @param int    $booking_id Booking ID.
 * @param string $status Booking status.
 * @return bool|WP_Error
 */
function aqualuxe_update_booking_status( $booking_id, $status ) {
    return aqualuxe_update_booking( $booking_id, [ 'status' => $status ] );
}

/**
 * Get booking URL
 *
 * @param int $booking_id Booking ID.
 * @return string
 */
function aqualuxe_get_booking_url( $booking_id ) {
    $booking = new AquaLuxe\Modules\Bookings\Booking( $booking_id );
    return $booking->get_url();
}

/**
 * Get service URL
 *
 * @param int $service_id Service ID.
 * @return string
 */
function aqualuxe_get_service_url( $service_id ) {
    $service = new AquaLuxe\Modules\Bookings\Service( $service_id );
    return $service->get_url();
}

/**
 * Get booking form URL
 *
 * @param int    $service_id Service ID.
 * @param string $date Date in Y-m-d format.
 * @param string $time Time in HH:MM format.
 * @return string
 */
function aqualuxe_get_booking_form_url( $service_id, $date = '', $time = '' ) {
    $settings = AquaLuxe\Modules\Bookings\Settings::get_instance();
    $booking_page_url = $settings->get_booking_page_url();
    
    if ( ! $booking_page_url ) {
        return '';
    }
    
    $url = add_query_arg( 'service_id', $service_id, $booking_page_url );
    
    if ( $date ) {
        $url = add_query_arg( 'date', $date, $url );
    }
    
    if ( $time ) {
        $url = add_query_arg( 'time', $time, $url );
    }
    
    return $url;
}

/**
 * Format date
 *
 * @param string $date Date in Y-m-d format.
 * @return string
 */
function aqualuxe_format_date( $date ) {
    $settings = AquaLuxe\Modules\Bookings\Settings::get_instance();
    $date_format = $settings->get_date_format();
    
    $date_obj = new DateTime( $date );
    
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
 * Format time
 *
 * @param string $time Time in HH:MM format.
 * @return string
 */
function aqualuxe_format_time( $time ) {
    $settings = AquaLuxe\Modules\Bookings\Settings::get_instance();
    $time_format = $settings->get_time_format();
    
    $time_obj = new DateTime( '2000-01-01 ' . $time );
    
    if ( $time_format === '12' ) {
        return $time_obj->format( 'g:i A' );
    } else {
        return $time_obj->format( 'H:i' );
    }
}

/**
 * Format duration
 *
 * @param int $duration Duration in minutes.
 * @return string
 */
function aqualuxe_format_duration( $duration ) {
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
 * Get service categories
 *
 * @param array $args Query arguments.
 * @return array
 */
function aqualuxe_get_service_categories( $args = [] ) {
    // Default arguments
    $default_args = [
        'taxonomy'   => 'aqualuxe_service_category',
        'hide_empty' => false,
    ];
    
    // Merge arguments
    $args = wp_parse_args( $args, $default_args );
    
    // Get categories
    return get_terms( $args );
}

/**
 * Get booking statuses
 *
 * @param array $args Query arguments.
 * @return array
 */
function aqualuxe_get_booking_statuses( $args = [] ) {
    // Default arguments
    $default_args = [
        'taxonomy'   => 'aqualuxe_booking_status',
        'hide_empty' => false,
    ];
    
    // Merge arguments
    $args = wp_parse_args( $args, $default_args );
    
    // Get statuses
    return get_terms( $args );
}

/**
 * Get booking count by status
 *
 * @param string $status Booking status.
 * @return int
 */
function aqualuxe_get_booking_count_by_status( $status ) {
    $args = [
        'post_type'      => 'aqualuxe_booking',
        'posts_per_page' => -1,
        'tax_query'      => [
            [
                'taxonomy' => 'aqualuxe_booking_status',
                'field'    => 'slug',
                'terms'    => $status,
            ],
        ],
    ];
    
    $bookings = new WP_Query( $args );
    return $bookings->found_posts;
}

/**
 * Get total bookings
 *
 * @return int
 */
function aqualuxe_get_total_bookings() {
    $count_posts = wp_count_posts( 'aqualuxe_booking' );
    return $count_posts->publish;
}

/**
 * Get total services
 *
 * @return int
 */
function aqualuxe_get_total_services() {
    $count_posts = wp_count_posts( 'aqualuxe_service' );
    return $count_posts->publish;
}

/**
 * Get booking settings
 *
 * @return array
 */
function aqualuxe_get_booking_settings() {
    $settings = AquaLuxe\Modules\Bookings\Settings::get_instance();
    return $settings->get_settings();
}

/**
 * Get booking setting
 *
 * @param string $key Setting key.
 * @param mixed  $default Default value.
 * @return mixed
 */
function aqualuxe_get_booking_setting( $key, $default = false ) {
    $settings = AquaLuxe\Modules\Bookings\Settings::get_instance();
    return $settings->get_setting( $key, $default );
}

/**
 * Update booking setting
 *
 * @param string $key Setting key.
 * @param mixed  $value Setting value.
 * @return void
 */
function aqualuxe_update_booking_setting( $key, $value ) {
    $settings = AquaLuxe\Modules\Bookings\Settings::get_instance();
    $settings->update_setting( $key, $value );
}

/**
 * Update booking settings
 *
 * @param array $settings Settings.
 * @return void
 */
function aqualuxe_update_booking_settings( $settings ) {
    $settings_instance = AquaLuxe\Modules\Bookings\Settings::get_instance();
    $settings_instance->update_settings( $settings );
}

/**
 * Reset booking settings
 *
 * @return void
 */
function aqualuxe_reset_booking_settings() {
    $settings = AquaLuxe\Modules\Bookings\Settings::get_instance();
    $settings->reset_settings();
}

/**
 * Get booking page URL
 *
 * @return string
 */
function aqualuxe_get_booking_page_url() {
    $settings = AquaLuxe\Modules\Bookings\Settings::get_instance();
    return $settings->get_booking_page_url();
}

/**
 * Get services page URL
 *
 * @return string
 */
function aqualuxe_get_services_page_url() {
    $settings = AquaLuxe\Modules\Bookings\Settings::get_instance();
    return $settings->get_services_page_url();
}

/**
 * Get calendar page URL
 *
 * @return string
 */
function aqualuxe_get_calendar_page_url() {
    $settings = AquaLuxe\Modules\Bookings\Settings::get_instance();
    return $settings->get_calendar_page_url();
}

/**
 * Send booking notification
 *
 * @param int    $booking_id Booking ID.
 * @param string $type Notification type.
 * @return void
 */
function aqualuxe_send_booking_notification( $booking_id, $type ) {
    $notifications = AquaLuxe\Modules\Bookings\Notifications::get_instance();
    
    switch ( $type ) {
        case 'new':
            do_action( 'aqualuxe_booking_created', $booking_id );
            break;
            
        case 'status_update':
            $booking = new AquaLuxe\Modules\Bookings\Booking( $booking_id );
            do_action( 'aqualuxe_booking_status_updated', $booking_id, $booking->get_status() );
            break;
            
        case 'cancelled':
            do_action( 'aqualuxe_booking_cancelled', $booking_id );
            break;
            
        case 'reminder':
            do_action( 'aqualuxe_booking_reminder', $booking_id );
            break;
    }
}

/**
 * Schedule booking reminder
 *
 * @param int $booking_id Booking ID.
 * @return void
 */
function aqualuxe_schedule_booking_reminder( $booking_id ) {
    $notifications = AquaLuxe\Modules\Bookings\Notifications::get_instance();
    $notifications->schedule_reminder( $booking_id );
}

/**
 * Cancel booking reminder
 *
 * @param int $booking_id Booking ID.
 * @return void
 */
function aqualuxe_cancel_booking_reminder( $booking_id ) {
    $notifications = AquaLuxe\Modules\Bookings\Notifications::get_instance();
    $notifications->cancel_reminder( $booking_id );
}

/**
 * Get booking form
 *
 * @param array $args Arguments.
 * @return string
 */
function aqualuxe_get_booking_form( $args = [] ) {
    $shortcodes = AquaLuxe\Modules\Bookings\Shortcodes::get_instance();
    return $shortcodes->booking_form_shortcode( $args );
}

/**
 * Get services list
 *
 * @param array $args Arguments.
 * @return string
 */
function aqualuxe_get_services_list( $args = [] ) {
    $shortcodes = AquaLuxe\Modules\Bookings\Shortcodes::get_instance();
    return $shortcodes->services_shortcode( $args );
}

/**
 * Get availability
 *
 * @param array $args Arguments.
 * @return string
 */
function aqualuxe_get_availability_list( $args = [] ) {
    $shortcodes = AquaLuxe\Modules\Bookings\Shortcodes::get_instance();
    return $shortcodes->availability_shortcode( $args );
}