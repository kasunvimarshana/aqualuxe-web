<?php
/**
 * AquaLuxe Booking Calendar Class
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * AquaLuxe Booking Calendar Class
 */
class AquaLuxe_Booking_Calendar {
    /**
     * Render calendar page
     */
    public function render() {
        // Check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        // Get current view
        $view = isset( $_GET['view'] ) ? sanitize_text_field( $_GET['view'] ) : 'month';
        
        // Get current date
        $year = isset( $_GET['year'] ) ? absint( $_GET['year'] ) : date( 'Y' );
        $month = isset( $_GET['month'] ) ? absint( $_GET['month'] ) : date( 'n' );
        $day = isset( $_GET['day'] ) ? absint( $_GET['day'] ) : date( 'j' );
        
        // Get current service
        $service_id = isset( $_GET['service_id'] ) ? absint( $_GET['service_id'] ) : 0;
        
        // Get current resource
        $resource_id = isset( $_GET['resource_id'] ) ? absint( $_GET['resource_id'] ) : 0;
        
        // Get services
        $service_obj = new AquaLuxe_Booking_Service();
        $services = $service_obj->get_all();
        
        // Get resources
        $resource_obj = new AquaLuxe_Booking_Resource();
        $resources = $resource_obj->get_all();
        
        // Get bookings
        $bookings = $this->get_bookings_for_view( $view, $year, $month, $day, $service_id, $resource_id );
        
        // Include calendar template
        include AQUALUXE_DIR . 'modules/bookings/templates/admin/calendar.php';
    }

    /**
     * Get bookings for view
     *
     * @param string $view        View type (month, week, day, list)
     * @param int    $year        Year
     * @param int    $month       Month
     * @param int    $day         Day
     * @param int    $service_id  Service ID (optional)
     * @param int    $resource_id Resource ID (optional)
     * @return array Bookings
     */
    public function get_bookings_for_view( $view, $year, $month, $day, $service_id = 0, $resource_id = 0 ) {
        // Get date range for view
        $date_range = $this->get_date_range_for_view( $view, $year, $month, $day );
        
        // Query arguments
        $args = array(
            'post_type'      => 'aqualuxe_booking',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'     => '_date',
                    'value'   => array( $date_range['start'], $date_range['end'] ),
                    'compare' => 'BETWEEN',
                    'type'    => 'DATE',
                ),
            ),
        );
        
        // Add service filter
        if ( $service_id ) {
            $args['meta_query'][] = array(
                'key'     => '_service_id',
                'value'   => $service_id,
                'compare' => '=',
            );
        }
        
        // Add resource filter
        if ( $resource_id ) {
            $args['meta_query'][] = array(
                'key'     => '_resource_id',
                'value'   => $resource_id,
                'compare' => '=',
            );
        }
        
        // Query bookings
        $query = new WP_Query( $args );
        $bookings = array();
        
        if ( $query->have_posts() ) {
            $booking_obj = new AquaLuxe_Booking();
            
            while ( $query->have_posts() ) {
                $query->the_post();
                $booking_id = get_the_ID();
                $booking = $booking_obj->get( $booking_id );
                
                if ( $booking ) {
                    $bookings[] = $booking;
                }
            }
            
            wp_reset_postdata();
        }
        
        return $bookings;
    }

    /**
     * Get date range for view
     *
     * @param string $view  View type (month, week, day, list)
     * @param int    $year  Year
     * @param int    $month Month
     * @param int    $day   Day
     * @return array Date range (start, end)
     */
    private function get_date_range_for_view( $view, $year, $month, $day ) {
        switch ( $view ) {
            case 'month':
                // Get first and last day of month
                $start_date = date( 'Y-m-d', strtotime( "$year-$month-01" ) );
                $end_date = date( 'Y-m-t', strtotime( "$year-$month-01" ) );
                break;
                
            case 'week':
                // Get first and last day of week
                $date = new DateTime( "$year-$month-$day" );
                $day_of_week = $date->format( 'w' );
                $start_date = date( 'Y-m-d', strtotime( "-$day_of_week days", $date->getTimestamp() ) );
                $end_date = date( 'Y-m-d', strtotime( '+6 days', strtotime( $start_date ) ) );
                break;
                
            case 'day':
                // Get current day
                $start_date = date( 'Y-m-d', strtotime( "$year-$month-$day" ) );
                $end_date = $start_date;
                break;
                
            case 'list':
                // Get date range for list view (default to 30 days)
                $start_date = date( 'Y-m-d', strtotime( "$year-$month-$day" ) );
                $end_date = date( 'Y-m-d', strtotime( '+30 days', strtotime( $start_date ) ) );
                break;
                
            default:
                // Default to current month
                $start_date = date( 'Y-m-d', strtotime( "$year-$month-01" ) );
                $end_date = date( 'Y-m-t', strtotime( "$year-$month-01" ) );
                break;
        }
        
        return array(
            'start' => $start_date,
            'end'   => $end_date,
        );
    }

    /**
     * Get month calendar data
     *
     * @param int $year  Year
     * @param int $month Month
     * @return array Calendar data
     */
    public function get_month_calendar_data( $year, $month ) {
        // Get first day of month
        $first_day = date( 'Y-m-d', strtotime( "$year-$month-01" ) );
        
        // Get last day of month
        $last_day = date( 'Y-m-t', strtotime( "$year-$month-01" ) );
        
        // Get day of week for first day (0 = Sunday, 6 = Saturday)
        $first_day_of_week = date( 'w', strtotime( $first_day ) );
        
        // Get number of days in month
        $days_in_month = date( 't', strtotime( "$year-$month-01" ) );
        
        // Get previous month info
        $prev_month = $month - 1;
        $prev_year = $year;
        
        if ( $prev_month < 1 ) {
            $prev_month = 12;
            $prev_year--;
        }
        
        $days_in_prev_month = date( 't', strtotime( "$prev_year-$prev_month-01" ) );
        
        // Get next month info
        $next_month = $month + 1;
        $next_year = $year;
        
        if ( $next_month > 12 ) {
            $next_month = 1;
            $next_year++;
        }
        
        // Build calendar data
        $calendar = array(
            'year'          => $year,
            'month'         => $month,
            'month_name'    => date_i18n( 'F', strtotime( "$year-$month-01" ) ),
            'days_in_month' => $days_in_month,
            'first_day'     => $first_day,
            'last_day'      => $last_day,
            'weeks'         => array(),
        );
        
        // Build weeks
        $day_count = 1;
        $current_week = 0;
        
        // Add days from previous month
        if ( $first_day_of_week > 0 ) {
            $calendar['weeks'][ $current_week ] = array();
            
            for ( $i = $first_day_of_week - 1; $i >= 0; $i-- ) {
                $day = $days_in_prev_month - $i;
                
                $calendar['weeks'][ $current_week ][] = array(
                    'day'       => $day,
                    'month'     => $prev_month,
                    'year'      => $prev_year,
                    'date'      => date( 'Y-m-d', strtotime( "$prev_year-$prev_month-$day" ) ),
                    'is_current_month' => false,
                    'is_today'  => false,
                );
            }
        } else {
            $calendar['weeks'][ $current_week ] = array();
        }
        
        // Add days from current month
        $today = date( 'Y-m-d' );
        
        for ( $day = 1; $day <= $days_in_month; $day++ ) {
            $date = date( 'Y-m-d', strtotime( "$year-$month-$day" ) );
            $day_of_week = date( 'w', strtotime( $date ) );
            
            if ( $day_of_week == 0 && $day > 1 ) {
                $current_week++;
                $calendar['weeks'][ $current_week ] = array();
            }
            
            $calendar['weeks'][ $current_week ][] = array(
                'day'       => $day,
                'month'     => $month,
                'year'      => $year,
                'date'      => $date,
                'is_current_month' => true,
                'is_today'  => $date === $today,
            );
        }
        
        // Add days from next month
        $last_week = end( $calendar['weeks'] );
        $days_to_add = 7 - count( $last_week );
        
        if ( $days_to_add > 0 ) {
            for ( $day = 1; $day <= $days_to_add; $day++ ) {
                $calendar['weeks'][ $current_week ][] = array(
                    'day'       => $day,
                    'month'     => $next_month,
                    'year'      => $next_year,
                    'date'      => date( 'Y-m-d', strtotime( "$next_year-$next_month-$day" ) ),
                    'is_current_month' => false,
                    'is_today'  => false,
                );
            }
        }
        
        return $calendar;
    }

    /**
     * Get week calendar data
     *
     * @param int $year  Year
     * @param int $month Month
     * @param int $day   Day
     * @return array Calendar data
     */
    public function get_week_calendar_data( $year, $month, $day ) {
        // Get date object
        $date = new DateTime( "$year-$month-$day" );
        
        // Get day of week (0 = Sunday, 6 = Saturday)
        $day_of_week = $date->format( 'w' );
        
        // Get first day of week
        $first_day = clone $date;
        $first_day->modify( "-$day_of_week days" );
        
        // Build calendar data
        $calendar = array(
            'year'      => $year,
            'month'     => $month,
            'day'       => $day,
            'days'      => array(),
        );
        
        // Build days
        $current_date = clone $first_day;
        $today = new DateTime();
        
        for ( $i = 0; $i < 7; $i++ ) {
            $calendar['days'][] = array(
                'day'       => $current_date->format( 'j' ),
                'month'     => $current_date->format( 'n' ),
                'year'      => $current_date->format( 'Y' ),
                'date'      => $current_date->format( 'Y-m-d' ),
                'day_name'  => $current_date->format( 'l' ),
                'is_today'  => $current_date->format( 'Y-m-d' ) === $today->format( 'Y-m-d' ),
            );
            
            $current_date->modify( '+1 day' );
        }
        
        return $calendar;
    }

    /**
     * Get day calendar data
     *
     * @param int $year  Year
     * @param int $month Month
     * @param int $day   Day
     * @return array Calendar data
     */
    public function get_day_calendar_data( $year, $month, $day ) {
        // Get date object
        $date = new DateTime( "$year-$month-$day" );
        
        // Build calendar data
        $calendar = array(
            'year'      => $year,
            'month'     => $month,
            'day'       => $day,
            'date'      => $date->format( 'Y-m-d' ),
            'day_name'  => $date->format( 'l' ),
            'is_today'  => $date->format( 'Y-m-d' ) === date( 'Y-m-d' ),
            'hours'     => array(),
        );
        
        // Build hours
        $start_hour = 8; // 8 AM
        $end_hour = 20; // 8 PM
        
        for ( $hour = $start_hour; $hour <= $end_hour; $hour++ ) {
            $calendar['hours'][] = array(
                'hour'      => $hour,
                'hour_12'   => $hour > 12 ? $hour - 12 : $hour,
                'meridiem'  => $hour >= 12 ? 'PM' : 'AM',
                'time'      => sprintf( '%02d:00', $hour ),
                'time_12'   => sprintf( '%d:00 %s', $hour > 12 ? $hour - 12 : $hour, $hour >= 12 ? 'PM' : 'AM' ),
            );
        }
        
        return $calendar;
    }

    /**
     * Get list calendar data
     *
     * @param int $year  Year
     * @param int $month Month
     * @param int $day   Day
     * @param int $days  Number of days to show
     * @return array Calendar data
     */
    public function get_list_calendar_data( $year, $month, $day, $days = 30 ) {
        // Get date object
        $date = new DateTime( "$year-$month-$day" );
        
        // Build calendar data
        $calendar = array(
            'year'      => $year,
            'month'     => $month,
            'day'       => $day,
            'days'      => array(),
        );
        
        // Build days
        $current_date = clone $date;
        $today = new DateTime();
        
        for ( $i = 0; $i < $days; $i++ ) {
            $calendar['days'][] = array(
                'day'       => $current_date->format( 'j' ),
                'month'     => $current_date->format( 'n' ),
                'year'      => $current_date->format( 'Y' ),
                'date'      => $current_date->format( 'Y-m-d' ),
                'day_name'  => $current_date->format( 'l' ),
                'is_today'  => $current_date->format( 'Y-m-d' ) === $today->format( 'Y-m-d' ),
            );
            
            $current_date->modify( '+1 day' );
        }
        
        return $calendar;
    }

    /**
     * Get bookings for date
     *
     * @param string $date        Date in Y-m-d format
     * @param int    $service_id  Service ID (optional)
     * @param int    $resource_id Resource ID (optional)
     * @return array Bookings
     */
    public function get_bookings_for_date( $date, $service_id = 0, $resource_id = 0 ) {
        // Query arguments
        $args = array(
            'post_type'      => 'aqualuxe_booking',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => array(
                'relation' => 'AND',
                array(
                    'key'     => '_date',
                    'value'   => $date,
                    'compare' => '=',
                ),
            ),
        );
        
        // Add service filter
        if ( $service_id ) {
            $args['meta_query'][] = array(
                'key'     => '_service_id',
                'value'   => $service_id,
                'compare' => '=',
            );
        }
        
        // Add resource filter
        if ( $resource_id ) {
            $args['meta_query'][] = array(
                'key'     => '_resource_id',
                'value'   => $resource_id,
                'compare' => '=',
            );
        }
        
        // Query bookings
        $query = new WP_Query( $args );
        $bookings = array();
        
        if ( $query->have_posts() ) {
            $booking_obj = new AquaLuxe_Booking();
            
            while ( $query->have_posts() ) {
                $query->the_post();
                $booking_id = get_the_ID();
                $booking = $booking_obj->get( $booking_id );
                
                if ( $booking ) {
                    $bookings[] = $booking;
                }
            }
            
            wp_reset_postdata();
        }
        
        return $bookings;
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
    public function get_bookings_for_hour( $date, $hour, $service_id = 0, $resource_id = 0 ) {
        // Get all bookings for date
        $bookings = $this->get_bookings_for_date( $date, $service_id, $resource_id );
        
        // Filter bookings for hour
        $hour_bookings = array();
        
        foreach ( $bookings as $booking ) {
            $booking_hour = (int) substr( $booking['time'], 0, 2 );
            
            if ( $booking_hour === $hour ) {
                $hour_bookings[] = $booking;
            }
        }
        
        return $hour_bookings;
    }

    /**
     * Get navigation links
     *
     * @param string $view  View type (month, week, day, list)
     * @param int    $year  Year
     * @param int    $month Month
     * @param int    $day   Day
     * @return array Navigation links
     */
    public function get_navigation_links( $view, $year, $month, $day ) {
        $links = array();
        
        // Current URL parameters
        $params = array(
            'page'        => 'aqualuxe-booking-calendar',
            'view'        => $view,
            'service_id'  => isset( $_GET['service_id'] ) ? absint( $_GET['service_id'] ) : 0,
            'resource_id' => isset( $_GET['resource_id'] ) ? absint( $_GET['resource_id'] ) : 0,
        );
        
        switch ( $view ) {
            case 'month':
                // Previous month
                $prev_month = $month - 1;
                $prev_year = $year;
                
                if ( $prev_month < 1 ) {
                    $prev_month = 12;
                    $prev_year--;
                }
                
                $links['prev'] = add_query_arg( array(
                    'year'  => $prev_year,
                    'month' => $prev_month,
                ), admin_url( 'admin.php' ) );
                
                // Next month
                $next_month = $month + 1;
                $next_year = $year;
                
                if ( $next_month > 12 ) {
                    $next_month = 1;
                    $next_year++;
                }
                
                $links['next'] = add_query_arg( array(
                    'year'  => $next_year,
                    'month' => $next_month,
                ), admin_url( 'admin.php' ) );
                
                // Today
                $today = getdate();
                
                $links['today'] = add_query_arg( array(
                    'year'  => $today['year'],
                    'month' => $today['mon'],
                    'day'   => $today['mday'],
                ), admin_url( 'admin.php' ) );
                break;
                
            case 'week':
                // Previous week
                $prev_date = date( 'Y-m-d', strtotime( "-7 days", strtotime( "$year-$month-$day" ) ) );
                $prev_parts = explode( '-', $prev_date );
                
                $links['prev'] = add_query_arg( array(
                    'year'  => $prev_parts[0],
                    'month' => $prev_parts[1],
                    'day'   => $prev_parts[2],
                ), admin_url( 'admin.php' ) );
                
                // Next week
                $next_date = date( 'Y-m-d', strtotime( "+7 days", strtotime( "$year-$month-$day" ) ) );
                $next_parts = explode( '-', $next_date );
                
                $links['next'] = add_query_arg( array(
                    'year'  => $next_parts[0],
                    'month' => $next_parts[1],
                    'day'   => $next_parts[2],
                ), admin_url( 'admin.php' ) );
                
                // Today
                $today = getdate();
                
                $links['today'] = add_query_arg( array(
                    'year'  => $today['year'],
                    'month' => $today['mon'],
                    'day'   => $today['mday'],
                ), admin_url( 'admin.php' ) );
                break;
                
            case 'day':
                // Previous day
                $prev_date = date( 'Y-m-d', strtotime( "-1 day", strtotime( "$year-$month-$day" ) ) );
                $prev_parts = explode( '-', $prev_date );
                
                $links['prev'] = add_query_arg( array(
                    'year'  => $prev_parts[0],
                    'month' => $prev_parts[1],
                    'day'   => $prev_parts[2],
                ), admin_url( 'admin.php' ) );
                
                // Next day
                $next_date = date( 'Y-m-d', strtotime( "+1 day", strtotime( "$year-$month-$day" ) ) );
                $next_parts = explode( '-', $next_date );
                
                $links['next'] = add_query_arg( array(
                    'year'  => $next_parts[0],
                    'month' => $next_parts[1],
                    'day'   => $next_parts[2],
                ), admin_url( 'admin.php' ) );
                
                // Today
                $today = getdate();
                
                $links['today'] = add_query_arg( array(
                    'year'  => $today['year'],
                    'month' => $today['mon'],
                    'day'   => $today['mday'],
                ), admin_url( 'admin.php' ) );
                break;
                
            case 'list':
                // Previous period
                $prev_date = date( 'Y-m-d', strtotime( "-30 days", strtotime( "$year-$month-$day" ) ) );
                $prev_parts = explode( '-', $prev_date );
                
                $links['prev'] = add_query_arg( array(
                    'year'  => $prev_parts[0],
                    'month' => $prev_parts[1],
                    'day'   => $prev_parts[2],
                ), admin_url( 'admin.php' ) );
                
                // Next period
                $next_date = date( 'Y-m-d', strtotime( "+30 days", strtotime( "$year-$month-$day" ) ) );
                $next_parts = explode( '-', $next_date );
                
                $links['next'] = add_query_arg( array(
                    'year'  => $next_parts[0],
                    'month' => $next_parts[1],
                    'day'   => $next_parts[2],
                ), admin_url( 'admin.php' ) );
                
                // Today
                $today = getdate();
                
                $links['today'] = add_query_arg( array(
                    'year'  => $today['year'],
                    'month' => $today['mon'],
                    'day'   => $today['mday'],
                ), admin_url( 'admin.php' ) );
                break;
        }
        
        // Add common parameters to all links
        foreach ( $links as &$link ) {
            $link = add_query_arg( $params, $link );
        }
        
        return $links;
    }

    /**
     * Get view links
     *
     * @param string $current_view Current view
     * @param int    $year         Year
     * @param int    $month        Month
     * @param int    $day          Day
     * @return array View links
     */
    public function get_view_links( $current_view, $year, $month, $day ) {
        $views = array(
            'month' => __( 'Month', 'aqualuxe' ),
            'week'  => __( 'Week', 'aqualuxe' ),
            'day'   => __( 'Day', 'aqualuxe' ),
            'list'  => __( 'List', 'aqualuxe' ),
        );
        
        $links = array();
        
        // Current URL parameters
        $params = array(
            'page'        => 'aqualuxe-booking-calendar',
            'year'        => $year,
            'month'       => $month,
            'day'         => $day,
            'service_id'  => isset( $_GET['service_id'] ) ? absint( $_GET['service_id'] ) : 0,
            'resource_id' => isset( $_GET['resource_id'] ) ? absint( $_GET['resource_id'] ) : 0,
        );
        
        foreach ( $views as $view => $label ) {
            $links[ $view ] = array(
                'url'     => add_query_arg( array( 'view' => $view ), admin_url( 'admin.php' ) ),
                'label'   => $label,
                'current' => $view === $current_view,
            );
            
            // Add common parameters
            $links[ $view ]['url'] = add_query_arg( $params, $links[ $view ]['url'] );
        }
        
        return $links;
    }
}