<?php
/**
 * AquaLuxe Booking Functions
 *
 * Helper functions for the bookings module.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Get booking by ID
 *
 * @param int $booking_id Booking ID
 * @return array|false Booking data or false on failure
 */
function aqualuxe_get_booking( $booking_id ) {
    $booking = new AquaLuxe_Booking();
    return $booking->get( $booking_id );
}

/**
 * Get bookings by date
 *
 * @param string $date Date in Y-m-d format
 * @return array Bookings
 */
function aqualuxe_get_bookings_by_date( $date ) {
    $booking = new AquaLuxe_Booking();
    return $booking->get_by_date( $date );
}

/**
 * Get bookings by service
 *
 * @param int $service_id Service ID
 * @return array Bookings
 */
function aqualuxe_get_bookings_by_service( $service_id ) {
    $booking = new AquaLuxe_Booking();
    return $booking->get_by_service( $service_id );
}

/**
 * Get bookings by resource
 *
 * @param int $resource_id Resource ID
 * @return array Bookings
 */
function aqualuxe_get_bookings_by_resource( $resource_id ) {
    $booking = new AquaLuxe_Booking();
    return $booking->get_by_resource( $resource_id );
}

/**
 * Get bookings by user
 *
 * @param int $user_id User ID
 * @return array Bookings
 */
function aqualuxe_get_bookings_by_user( $user_id ) {
    $booking = new AquaLuxe_Booking();
    return $booking->get_by_user( $user_id );
}

/**
 * Get bookings by email
 *
 * @param string $email Email address
 * @return array Bookings
 */
function aqualuxe_get_bookings_by_email( $email ) {
    $booking = new AquaLuxe_Booking();
    return $booking->get_by_email( $email );
}

/**
 * Get bookings by status
 *
 * @param string $status Booking status
 * @return array Bookings
 */
function aqualuxe_get_bookings_by_status( $status ) {
    $booking = new AquaLuxe_Booking();
    return $booking->get_by_status( $status );
}

/**
 * Get bookings by date range
 *
 * @param string $start_date Start date in Y-m-d format
 * @param string $end_date   End date in Y-m-d format
 * @return array Bookings
 */
function aqualuxe_get_bookings_by_date_range( $start_date, $end_date ) {
    $booking = new AquaLuxe_Booking();
    return $booking->get_by_date_range( $start_date, $end_date );
}

/**
 * Get upcoming bookings
 *
 * @param int $limit Number of bookings to get
 * @return array Bookings
 */
function aqualuxe_get_upcoming_bookings( $limit = 10 ) {
    $booking = new AquaLuxe_Booking();
    return $booking->get_upcoming( $limit );
}

/**
 * Create a booking
 *
 * @param array $args Booking arguments
 * @return int|false Booking ID or false on failure
 */
function aqualuxe_create_booking( $args ) {
    $booking = new AquaLuxe_Booking();
    return $booking->create( $args );
}

/**
 * Update booking status
 *
 * @param int    $booking_id Booking ID
 * @param string $status     Booking status
 * @return bool Success
 */
function aqualuxe_update_booking_status( $booking_id, $status ) {
    $booking = new AquaLuxe_Booking();
    return $booking->update_status( $booking_id, $status );
}

/**
 * Send booking confirmation email
 *
 * @param int $booking_id Booking ID
 * @return bool Success
 */
function aqualuxe_send_booking_confirmation_email( $booking_id ) {
    $booking = new AquaLuxe_Booking();
    return $booking->send_confirmation_email( $booking_id );
}

/**
 * Send booking cancellation email
 *
 * @param int $booking_id Booking ID
 * @return bool Success
 */
function aqualuxe_send_booking_cancellation_email( $booking_id ) {
    $booking = new AquaLuxe_Booking();
    return $booking->send_cancellation_email( $booking_id );
}

/**
 * Send booking reminder email
 *
 * @param int $booking_id Booking ID
 * @return bool Success
 */
function aqualuxe_send_booking_reminder_email( $booking_id ) {
    $booking = new AquaLuxe_Booking();
    return $booking->send_reminder_email( $booking_id );
}

/**
 * Get service by ID
 *
 * @param int $service_id Service ID
 * @return array|false Service data or false on failure
 */
function aqualuxe_get_service( $service_id ) {
    $service = new AquaLuxe_Booking_Service();
    return $service->get( $service_id );
}

/**
 * Get all services
 *
 * @param array $args Query arguments
 * @return array Services
 */
function aqualuxe_get_services( $args = array() ) {
    $service = new AquaLuxe_Booking_Service();
    return $service->get_all( $args );
}

/**
 * Get service categories
 *
 * @return array Categories
 */
function aqualuxe_get_service_categories() {
    $service = new AquaLuxe_Booking_Service();
    return $service->get_categories();
}

/**
 * Get service resources
 *
 * @param int $service_id Service ID
 * @return array Resources
 */
function aqualuxe_get_service_resources( $service_id ) {
    $service = new AquaLuxe_Booking_Service();
    return $service->get_resources( $service_id );
}

/**
 * Get service options
 *
 * @param int $service_id Service ID
 * @return array Options
 */
function aqualuxe_get_service_options( $service_id ) {
    $service = new AquaLuxe_Booking_Service();
    return $service->get_options( $service_id );
}

/**
 * Get service operating hours
 *
 * @param int $service_id Service ID
 * @return array Operating hours
 */
function aqualuxe_get_service_operating_hours( $service_id ) {
    $service = new AquaLuxe_Booking_Service();
    return $service->get_operating_hours( $service_id );
}

/**
 * Get service special hours
 *
 * @param int $service_id Service ID
 * @return array Special hours
 */
function aqualuxe_get_service_special_hours( $service_id ) {
    $service = new AquaLuxe_Booking_Service();
    return $service->get_special_hours( $service_id );
}

/**
 * Get service breaks
 *
 * @param int $service_id Service ID
 * @return array Breaks
 */
function aqualuxe_get_service_breaks( $service_id ) {
    $service = new AquaLuxe_Booking_Service();
    return $service->get_breaks( $service_id );
}

/**
 * Get service availability for a date range
 *
 * @param int    $service_id  Service ID
 * @param string $start_date  Start date in Y-m-d format
 * @param string $end_date    End date in Y-m-d format
 * @param int    $resource_id Resource ID (optional)
 * @return array Availability data
 */
function aqualuxe_get_service_availability( $service_id, $start_date, $end_date, $resource_id = 0 ) {
    $service = new AquaLuxe_Booking_Service();
    return $service->get_availability( $service_id, $start_date, $end_date, $resource_id );
}

/**
 * Create a service
 *
 * @param array $args Service arguments
 * @return int|false Service ID or false on failure
 */
function aqualuxe_create_service( $args ) {
    $service = new AquaLuxe_Booking_Service();
    return $service->create( $args );
}

/**
 * Update a service
 *
 * @param int   $service_id Service ID
 * @param array $args       Service arguments
 * @return bool Success
 */
function aqualuxe_update_service( $service_id, $args ) {
    $service = new AquaLuxe_Booking_Service();
    return $service->update( $service_id, $args );
}

/**
 * Delete a service
 *
 * @param int $service_id Service ID
 * @return bool Success
 */
function aqualuxe_delete_service( $service_id ) {
    $service = new AquaLuxe_Booking_Service();
    return $service->delete( $service_id );
}

/**
 * Get resource by ID
 *
 * @param int $resource_id Resource ID
 * @return array|false Resource data or false on failure
 */
function aqualuxe_get_resource( $resource_id ) {
    $resource = new AquaLuxe_Booking_Resource();
    return $resource->get( $resource_id );
}

/**
 * Get all resources
 *
 * @param array $args Query arguments
 * @return array Resources
 */
function aqualuxe_get_resources( $args = array() ) {
    $resource = new AquaLuxe_Booking_Resource();
    return $resource->get_all( $args );
}

/**
 * Get resource categories
 *
 * @return array Categories
 */
function aqualuxe_get_resource_categories() {
    $resource = new AquaLuxe_Booking_Resource();
    return $resource->get_categories();
}

/**
 * Get resource options
 *
 * @param int $resource_id Resource ID
 * @return array Options
 */
function aqualuxe_get_resource_options( $resource_id ) {
    $resource = new AquaLuxe_Booking_Resource();
    return $resource->get_options( $resource_id );
}

/**
 * Get resource availability
 *
 * @param int $resource_id Resource ID
 * @return array Availability
 */
function aqualuxe_get_resource_availability( $resource_id ) {
    $resource = new AquaLuxe_Booking_Resource();
    return $resource->get_availability( $resource_id );
}

/**
 * Get resource special availability
 *
 * @param int $resource_id Resource ID
 * @return array Special availability
 */
function aqualuxe_get_resource_special_availability( $resource_id ) {
    $resource = new AquaLuxe_Booking_Resource();
    return $resource->get_special_availability( $resource_id );
}

/**
 * Get resource breaks
 *
 * @param int $resource_id Resource ID
 * @return array Breaks
 */
function aqualuxe_get_resource_breaks( $resource_id ) {
    $resource = new AquaLuxe_Booking_Resource();
    return $resource->get_breaks( $resource_id );
}

/**
 * Get resource availability for a date range
 *
 * @param int    $resource_id Resource ID
 * @param string $start_date  Start date in Y-m-d format
 * @param string $end_date    End date in Y-m-d format
 * @return array Availability data
 */
function aqualuxe_get_resource_availability_for_date_range( $resource_id, $start_date, $end_date ) {
    $resource = new AquaLuxe_Booking_Resource();
    return $resource->get_availability_for_date_range( $resource_id, $start_date, $end_date );
}

/**
 * Create a resource
 *
 * @param array $args Resource arguments
 * @return int|false Resource ID or false on failure
 */
function aqualuxe_create_resource( $args ) {
    $resource = new AquaLuxe_Booking_Resource();
    return $resource->create( $args );
}

/**
 * Update a resource
 *
 * @param int   $resource_id Resource ID
 * @param array $args        Resource arguments
 * @return bool Success
 */
function aqualuxe_update_resource( $resource_id, $args ) {
    $resource = new AquaLuxe_Booking_Resource();
    return $resource->update( $resource_id, $args );
}

/**
 * Delete a resource
 *
 * @param int $resource_id Resource ID
 * @return bool Success
 */
function aqualuxe_delete_resource( $resource_id ) {
    $resource = new AquaLuxe_Booking_Resource();
    return $resource->delete( $resource_id );
}

/**
 * Check if a time slot is available
 *
 * @param int    $service_id  Service ID
 * @param int    $resource_id Resource ID (optional)
 * @param string $date        Date in Y-m-d format
 * @param string $time        Time in H:i format
 * @return bool Is available
 */
function aqualuxe_is_time_slot_available( $service_id, $resource_id, $date, $time ) {
    $availability = new AquaLuxe_Booking_Availability();
    return $availability->is_available( $service_id, $resource_id, $date, $time );
}

/**
 * Get available time slots for a date
 *
 * @param int    $service_id  Service ID
 * @param int    $resource_id Resource ID (optional)
 * @param string $date        Date in Y-m-d format
 * @return array Available time slots
 */
function aqualuxe_get_available_time_slots( $service_id, $resource_id, $date ) {
    $availability = new AquaLuxe_Booking_Availability();
    return $availability->get_available_slots( $service_id, $resource_id, $date );
}

/**
 * Format date
 *
 * @param string $date Date in Y-m-d format
 * @return string Formatted date
 */
function aqualuxe_format_date( $date ) {
    return date_i18n( get_option( 'date_format' ), strtotime( $date ) );
}

/**
 * Format time
 *
 * @param string $time Time in H:i format
 * @return string Formatted time
 */
function aqualuxe_format_time( $time ) {
    return date_i18n( get_option( 'time_format' ), strtotime( '2000-01-01 ' . $time ) );
}

/**
 * Format date and time
 *
 * @param string $date Date in Y-m-d format
 * @param string $time Time in H:i format
 * @return string Formatted date and time
 */
function aqualuxe_format_date_time( $date, $time ) {
    return date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $date . ' ' . $time ) );
}

/**
 * Format price
 *
 * @param float $price Price
 * @return string Formatted price
 */
function aqualuxe_format_price( $price ) {
    if ( function_exists( 'wc_price' ) ) {
        return wc_price( $price );
    }
    
    return number_format_i18n( $price, 2 );
}

/**
 * Get booking status label
 *
 * @param string $status Booking status
 * @return string Status label
 */
function aqualuxe_get_booking_status_label( $status ) {
    $statuses = array(
        'pending'   => __( 'Pending', 'aqualuxe' ),
        'confirmed' => __( 'Confirmed', 'aqualuxe' ),
        'completed' => __( 'Completed', 'aqualuxe' ),
        'cancelled' => __( 'Cancelled', 'aqualuxe' ),
        'no-show'   => __( 'No Show', 'aqualuxe' ),
    );
    
    return isset( $statuses[ $status ] ) ? $statuses[ $status ] : $status;
}

/**
 * Get booking status color
 *
 * @param string $status Booking status
 * @return string Status color
 */
function aqualuxe_get_booking_status_color( $status ) {
    $colors = array(
        'pending'   => '#f0ad4e', // Orange
        'confirmed' => '#5bc0de', // Blue
        'completed' => '#5cb85c', // Green
        'cancelled' => '#d9534f', // Red
        'no-show'   => '#777777', // Gray
    );
    
    return isset( $colors[ $status ] ) ? $colors[ $status ] : '#777777';
}

/**
 * Get day name
 *
 * @param int $day_of_week Day of week (0-6)
 * @return string Day name
 */
function aqualuxe_get_day_name( $day_of_week ) {
    $days = array(
        0 => __( 'Sunday', 'aqualuxe' ),
        1 => __( 'Monday', 'aqualuxe' ),
        2 => __( 'Tuesday', 'aqualuxe' ),
        3 => __( 'Wednesday', 'aqualuxe' ),
        4 => __( 'Thursday', 'aqualuxe' ),
        5 => __( 'Friday', 'aqualuxe' ),
        6 => __( 'Saturday', 'aqualuxe' ),
    );
    
    return isset( $days[ $day_of_week ] ) ? $days[ $day_of_week ] : '';
}

/**
 * Get month name
 *
 * @param int $month Month (1-12)
 * @return string Month name
 */
function aqualuxe_get_month_name( $month ) {
    return date_i18n( 'F', strtotime( "2000-$month-01" ) );
}

/**
 * Get calendar data
 *
 * @param string $view  View type (month, week, day, list)
 * @param int    $year  Year
 * @param int    $month Month
 * @param int    $day   Day
 * @return array Calendar data
 */
function aqualuxe_get_calendar_data( $view, $year, $month, $day ) {
    $calendar = new AquaLuxe_Booking_Calendar();
    
    switch ( $view ) {
        case 'month':
            return $calendar->get_month_calendar_data( $year, $month );
            
        case 'week':
            return $calendar->get_week_calendar_data( $year, $month, $day );
            
        case 'day':
            return $calendar->get_day_calendar_data( $year, $month, $day );
            
        case 'list':
            return $calendar->get_list_calendar_data( $year, $month, $day );
            
        default:
            return $calendar->get_month_calendar_data( $year, $month );
    }
}

/**
 * Get bookings for calendar
 *
 * @param string $view        View type (month, week, day, list)
 * @param int    $year        Year
 * @param int    $month       Month
 * @param int    $day         Day
 * @param int    $service_id  Service ID (optional)
 * @param int    $resource_id Resource ID (optional)
 * @return array Bookings
 */
function aqualuxe_get_bookings_for_calendar( $view, $year, $month, $day, $service_id = 0, $resource_id = 0 ) {
    $calendar = new AquaLuxe_Booking_Calendar();
    return $calendar->get_bookings_for_view( $view, $year, $month, $day, $service_id, $resource_id );
}

/**
 * Get bookings for date
 *
 * @param string $date        Date in Y-m-d format
 * @param int    $service_id  Service ID (optional)
 * @param int    $resource_id Resource ID (optional)
 * @return array Bookings
 */
function aqualuxe_get_bookings_for_date( $date, $service_id = 0, $resource_id = 0 ) {
    $calendar = new AquaLuxe_Booking_Calendar();
    return $calendar->get_bookings_for_date( $date, $service_id, $resource_id );
}

/**
 * Get bookings for hour
 *
 * @param string $date        Date in Y-m-d format
 * @param int    $hour        Hour (0-23)
 * @param int    $service_id  Service ID (optional)
 * @param int    $resource_id Resource ID (optional)
 * @return array Bookings
 */
function aqualuxe_get_bookings_for_hour( $date, $hour, $service_id = 0, $resource_id = 0 ) {
    $calendar = new AquaLuxe_Booking_Calendar();
    return $calendar->get_bookings_for_hour( $date, $hour, $service_id, $resource_id );
}

/**
 * Get calendar navigation links
 *
 * @param string $view  View type (month, week, day, list)
 * @param int    $year  Year
 * @param int    $month Month
 * @param int    $day   Day
 * @return array Navigation links
 */
function aqualuxe_get_calendar_navigation_links( $view, $year, $month, $day ) {
    $calendar = new AquaLuxe_Booking_Calendar();
    return $calendar->get_navigation_links( $view, $year, $month, $day );
}

/**
 * Get calendar view links
 *
 * @param string $current_view Current view
 * @param int    $year         Year
 * @param int    $month        Month
 * @param int    $day          Day
 * @return array View links
 */
function aqualuxe_get_calendar_view_links( $current_view, $year, $month, $day ) {
    $calendar = new AquaLuxe_Booking_Calendar();
    return $calendar->get_view_links( $current_view, $year, $month, $day );
}