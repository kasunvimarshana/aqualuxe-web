<?php
/**
 * AquaLuxe Booking Shortcodes
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Booking form shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
function aqualuxe_booking_form_shortcode( $atts ) {
    // Extract attributes
    $atts = shortcode_atts( array(
        'service_id'  => 0,
        'resource_id' => 0,
        'title'       => __( 'Book Now', 'aqualuxe' ),
        'description' => '',
        'show_title'  => 'yes',
        'show_description' => 'yes',
        'layout'      => 'default',
        'class'       => '',
    ), $atts );
    
    // Convert string values to appropriate types
    $atts['service_id'] = absint( $atts['service_id'] );
    $atts['resource_id'] = absint( $atts['resource_id'] );
    $atts['show_title'] = $atts['show_title'] === 'yes';
    $atts['show_description'] = $atts['show_description'] === 'yes';
    
    // Start output buffering
    ob_start();
    
    // Include template
    include AQUALUXE_DIR . 'modules/bookings/templates/booking-form.php';
    
    // Return output
    return ob_get_clean();
}

/**
 * Booking calendar shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
function aqualuxe_booking_calendar_shortcode( $atts ) {
    // Extract attributes
    $atts = shortcode_atts( array(
        'service_id'  => 0,
        'resource_id' => 0,
        'view'        => 'month',
        'title'       => __( 'Booking Calendar', 'aqualuxe' ),
        'description' => '',
        'show_title'  => 'yes',
        'show_description' => 'yes',
        'show_navigation' => 'yes',
        'show_filters' => 'yes',
        'class'       => '',
    ), $atts );
    
    // Convert string values to appropriate types
    $atts['service_id'] = absint( $atts['service_id'] );
    $atts['resource_id'] = absint( $atts['resource_id'] );
    $atts['show_title'] = $atts['show_title'] === 'yes';
    $atts['show_description'] = $atts['show_description'] === 'yes';
    $atts['show_navigation'] = $atts['show_navigation'] === 'yes';
    $atts['show_filters'] = $atts['show_filters'] === 'yes';
    
    // Get current view
    $view = isset( $_GET['view'] ) ? sanitize_text_field( $_GET['view'] ) : $atts['view'];
    
    // Get current date
    $year = isset( $_GET['year'] ) ? absint( $_GET['year'] ) : date( 'Y' );
    $month = isset( $_GET['month'] ) ? absint( $_GET['month'] ) : date( 'n' );
    $day = isset( $_GET['day'] ) ? absint( $_GET['day'] ) : date( 'j' );
    
    // Get current service
    $service_id = isset( $_GET['service_id'] ) ? absint( $_GET['service_id'] ) : $atts['service_id'];
    
    // Get current resource
    $resource_id = isset( $_GET['resource_id'] ) ? absint( $_GET['resource_id'] ) : $atts['resource_id'];
    
    // Get services
    $services = aqualuxe_get_services();
    
    // Get resources
    $resources = aqualuxe_get_resources();
    
    // Get bookings
    $bookings = aqualuxe_get_bookings_for_calendar( $view, $year, $month, $day, $service_id, $resource_id );
    
    // Get calendar data
    $calendar_data = aqualuxe_get_calendar_data( $view, $year, $month, $day );
    
    // Get navigation links
    $navigation_links = aqualuxe_get_calendar_navigation_links( $view, $year, $month, $day );
    
    // Get view links
    $view_links = aqualuxe_get_calendar_view_links( $view, $year, $month, $day );
    
    // Start output buffering
    ob_start();
    
    // Include template
    include AQUALUXE_DIR . 'modules/bookings/templates/booking-calendar.php';
    
    // Return output
    return ob_get_clean();
}

/**
 * Services shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
function aqualuxe_services_shortcode( $atts ) {
    // Extract attributes
    $atts = shortcode_atts( array(
        'category'    => '',
        'limit'       => -1,
        'orderby'     => 'title',
        'order'       => 'ASC',
        'layout'      => 'grid',
        'columns'     => 3,
        'show_image'  => 'yes',
        'show_price'  => 'yes',
        'show_excerpt' => 'yes',
        'show_button' => 'yes',
        'button_text' => __( 'Book Now', 'aqualuxe' ),
        'title'       => __( 'Our Services', 'aqualuxe' ),
        'description' => '',
        'show_title'  => 'yes',
        'show_description' => 'yes',
        'class'       => '',
    ), $atts );
    
    // Convert string values to appropriate types
    $atts['limit'] = intval( $atts['limit'] );
    $atts['columns'] = absint( $atts['columns'] );
    $atts['show_image'] = $atts['show_image'] === 'yes';
    $atts['show_price'] = $atts['show_price'] === 'yes';
    $atts['show_excerpt'] = $atts['show_excerpt'] === 'yes';
    $atts['show_button'] = $atts['show_button'] === 'yes';
    $atts['show_title'] = $atts['show_title'] === 'yes';
    $atts['show_description'] = $atts['show_description'] === 'yes';
    
    // Get services
    $services = aqualuxe_get_services( array(
        'category'      => $atts['category'],
        'posts_per_page' => $atts['limit'],
        'orderby'       => $atts['orderby'],
        'order'         => $atts['order'],
    ) );
    
    // Start output buffering
    ob_start();
    
    // Include template
    include AQUALUXE_DIR . 'modules/bookings/templates/services.php';
    
    // Return output
    return ob_get_clean();
}

/**
 * Resources shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
function aqualuxe_resources_shortcode( $atts ) {
    // Extract attributes
    $atts = shortcode_atts( array(
        'category'    => '',
        'limit'       => -1,
        'orderby'     => 'title',
        'order'       => 'ASC',
        'layout'      => 'grid',
        'columns'     => 3,
        'show_image'  => 'yes',
        'show_excerpt' => 'yes',
        'show_button' => 'yes',
        'button_text' => __( 'View Details', 'aqualuxe' ),
        'title'       => __( 'Our Resources', 'aqualuxe' ),
        'description' => '',
        'show_title'  => 'yes',
        'show_description' => 'yes',
        'class'       => '',
    ), $atts );
    
    // Convert string values to appropriate types
    $atts['limit'] = intval( $atts['limit'] );
    $atts['columns'] = absint( $atts['columns'] );
    $atts['show_image'] = $atts['show_image'] === 'yes';
    $atts['show_excerpt'] = $atts['show_excerpt'] === 'yes';
    $atts['show_button'] = $atts['show_button'] === 'yes';
    $atts['show_title'] = $atts['show_title'] === 'yes';
    $atts['show_description'] = $atts['show_description'] === 'yes';
    
    // Get resources
    $resources = aqualuxe_get_resources( array(
        'category'      => $atts['category'],
        'posts_per_page' => $atts['limit'],
        'orderby'       => $atts['orderby'],
        'order'         => $atts['order'],
    ) );
    
    // Start output buffering
    ob_start();
    
    // Include template
    include AQUALUXE_DIR . 'modules/bookings/templates/resources.php';
    
    // Return output
    return ob_get_clean();
}

/**
 * My bookings shortcode
 *
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
function aqualuxe_my_bookings_shortcode( $atts ) {
    // Extract attributes
    $atts = shortcode_atts( array(
        'limit'       => 10,
        'show_status' => 'yes',
        'show_service' => 'yes',
        'show_resource' => 'yes',
        'show_date'   => 'yes',
        'show_time'   => 'yes',
        'show_actions' => 'yes',
        'title'       => __( 'My Bookings', 'aqualuxe' ),
        'description' => '',
        'show_title'  => 'yes',
        'show_description' => 'yes',
        'class'       => '',
    ), $atts );
    
    // Convert string values to appropriate types
    $atts['limit'] = intval( $atts['limit'] );
    $atts['show_status'] = $atts['show_status'] === 'yes';
    $atts['show_service'] = $atts['show_service'] === 'yes';
    $atts['show_resource'] = $atts['show_resource'] === 'yes';
    $atts['show_date'] = $atts['show_date'] === 'yes';
    $atts['show_time'] = $atts['show_time'] === 'yes';
    $atts['show_actions'] = $atts['show_actions'] === 'yes';
    $atts['show_title'] = $atts['show_title'] === 'yes';
    $atts['show_description'] = $atts['show_description'] === 'yes';
    
    // Get bookings
    $bookings = array();
    
    if ( is_user_logged_in() ) {
        $bookings = aqualuxe_get_bookings_by_user( get_current_user_id() );
    } elseif ( isset( $_COOKIE['aqualuxe_booking_email'] ) ) {
        $email = sanitize_email( $_COOKIE['aqualuxe_booking_email'] );
        $bookings = aqualuxe_get_bookings_by_email( $email );
    }
    
    // Limit bookings
    if ( $atts['limit'] > 0 && count( $bookings ) > $atts['limit'] ) {
        $bookings = array_slice( $bookings, 0, $atts['limit'] );
    }
    
    // Start output buffering
    ob_start();
    
    // Include template
    include AQUALUXE_DIR . 'modules/bookings/templates/my-bookings.php';
    
    // Return output
    return ob_get_clean();
}