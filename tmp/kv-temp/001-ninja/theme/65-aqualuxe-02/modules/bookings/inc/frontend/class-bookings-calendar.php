<?php
/**
 * Bookings Calendar
 *
 * Handles calendar functionality for the bookings module.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Bookings Calendar Class
 */
class AquaLuxe_Bookings_Calendar {
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize hooks
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add shortcode
        add_shortcode('aqualuxe_booking_calendar', array($this, 'calendar_shortcode'));
        
        // Add AJAX handlers
        add_action('wp_ajax_get_calendar_events', array($this, 'get_calendar_events'));
        add_action('wp_ajax_nopriv_get_calendar_events', array($this, 'get_calendar_events'));
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        // Register styles
        wp_register_style(
            'fullcalendar',
            AQUALUXE_BOOKINGS_URL . 'assets/vendor/fullcalendar/main.min.css',
            array(),
            '5.10.1'
        );
        
        wp_register_style(
            'aqualuxe-bookings-calendar',
            AQUALUXE_BOOKINGS_URL . 'assets/dist/css/calendar.css',
            array('fullcalendar'),
            AQUALUXE_BOOKINGS_VERSION
        );
        
        // Register scripts
        wp_register_script(
            'fullcalendar',
            AQUALUXE_BOOKINGS_URL . 'assets/vendor/fullcalendar/main.min.js',
            array('jquery'),
            '5.10.1',
            true
        );
        
        wp_register_script(
            'aqualuxe-bookings-calendar',
            AQUALUXE_BOOKINGS_URL . 'assets/dist/js/calendar.js',
            array('jquery', 'fullcalendar'),
            AQUALUXE_BOOKINGS_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-bookings-calendar', 'aqualuxe_bookings_calendar_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-bookings'),
            'i18n' => array(
                'today' => __('Today', 'aqualuxe'),
                'month' => __('Month', 'aqualuxe'),
                'week' => __('Week', 'aqualuxe'),
                'day' => __('Day', 'aqualuxe'),
                'list' => __('List', 'aqualuxe'),
                'all_day' => __('All Day', 'aqualuxe'),
                'event_details' => __('Event Details', 'aqualuxe'),
                'booking_details' => __('Booking Details', 'aqualuxe'),
                'service' => __('Service', 'aqualuxe'),
                'customer' => __('Customer', 'aqualuxe'),
                'date' => __('Date', 'aqualuxe'),
                'time' => __('Time', 'aqualuxe'),
                'status' => __('Status', 'aqualuxe'),
                'total' => __('Total', 'aqualuxe'),
                'view_booking' => __('View Booking', 'aqualuxe'),
                'book_now' => __('Book Now', 'aqualuxe'),
                'available' => __('Available', 'aqualuxe'),
                'unavailable' => __('Unavailable', 'aqualuxe'),
                'fully_booked' => __('Fully Booked', 'aqualuxe'),
                'select_date' => __('Select Date', 'aqualuxe'),
                'select_time' => __('Select Time', 'aqualuxe'),
                'monday' => __('Monday', 'aqualuxe'),
                'tuesday' => __('Tuesday', 'aqualuxe'),
                'wednesday' => __('Wednesday', 'aqualuxe'),
                'thursday' => __('Thursday', 'aqualuxe'),
                'friday' => __('Friday', 'aqualuxe'),
                'saturday' => __('Saturday', 'aqualuxe'),
                'sunday' => __('Sunday', 'aqualuxe'),
                'january' => __('January', 'aqualuxe'),
                'february' => __('February', 'aqualuxe'),
                'march' => __('March', 'aqualuxe'),
                'april' => __('April', 'aqualuxe'),
                'may' => __('May', 'aqualuxe'),
                'june' => __('June', 'aqualuxe'),
                'july' => __('July', 'aqualuxe'),
                'august' => __('August', 'aqualuxe'),
                'september' => __('September', 'aqualuxe'),
                'october' => __('October', 'aqualuxe'),
                'november' => __('November', 'aqualuxe'),
                'december' => __('December', 'aqualuxe'),
            ),
            'settings' => array(
                'calendar_first_day' => get_option('aqualuxe_bookings_calendar_first_day', 0),
                'color_scheme' => get_option('aqualuxe_bookings_color_scheme', '#0073aa'),
                'calendar_style' => get_option('aqualuxe_bookings_calendar_style', 'default'),
            ),
        ));
        
        // Enqueue if shortcode is used
        global $post;
        
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'aqualuxe_booking_calendar')) {
            wp_enqueue_style('aqualuxe-bookings-calendar');
            wp_enqueue_script('aqualuxe-bookings-calendar');
        }
    }

    /**
     * Calendar shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function calendar_shortcode($atts) {
        $atts = shortcode_atts(array(
            'service_id' => 0,
            'show_title' => 'yes',
            'show_legend' => 'yes',
            'view' => 'month',
            'height' => 'auto',
            'events_limit' => 10,
            'show_weekends' => 'yes',
            'show_all_day' => 'yes',
            'show_time' => 'yes',
            'show_booking_button' => 'yes',
            'booking_button_text' => __('Book Now', 'aqualuxe'),
        ), $atts, 'aqualuxe_booking_calendar');

        // Get service ID
        $service_id = absint($atts['service_id']);
        
        // If no service ID is provided, check if we're on a service page
        if (empty($service_id) && is_singular('bookable_service')) {
            $service_id = get_the_ID();
        }

        // Enqueue required scripts and styles
        wp_enqueue_style('aqualuxe-bookings-calendar');
        wp_enqueue_script('aqualuxe-bookings-calendar');

        // Start output buffering
        ob_start();

        // Get template
        aqualuxe_bookings_get_template('booking-calendar.php', array(
            'service_id' => $service_id,
            'show_title' => 'yes' === $atts['show_title'],
            'show_legend' => 'yes' === $atts['show_legend'],
            'view' => $atts['view'],
            'height' => $atts['height'],
            'events_limit' => absint($atts['events_limit']),
            'show_weekends' => 'yes' === $atts['show_weekends'],
            'show_all_day' => 'yes' === $atts['show_all_day'],
            'show_time' => 'yes' === $atts['show_time'],
            'show_booking_button' => 'yes' === $atts['show_booking_button'],
            'booking_button_text' => $atts['booking_button_text'],
        ));

        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Get calendar events
     */
    public function get_calendar_events() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-bookings')) {
            wp_send_json_error(array('message' => __('Invalid security token', 'aqualuxe')));
        }

        // Get date range
        $start = isset($_POST['start']) ? sanitize_text_field($_POST['start']) : '';
        $end = isset($_POST['end']) ? sanitize_text_field($_POST['end']) : '';
        $service_id = isset($_POST['service_id']) ? absint($_POST['service_id']) : 0;
        
        if (empty($start) || empty($end)) {
            wp_send_json_error(array('message' => __('Invalid date range', 'aqualuxe')));
        }

        // Get events
        $events = $this->get_events($start, $end, $service_id);

        wp_send_json_success(array(
            'events' => $events,
        ));
    }

    /**
     * Get events
     *
     * @param string $start Start date
     * @param string $end End date
     * @param int $service_id Service ID
     * @return array Events
     */
    public function get_events($start, $end, $service_id = 0) {
        $events = array();
        
        // Get bookings
        if (is_user_logged_in() && current_user_can('manage_options')) {
            // Admin can see all bookings
            $bookings = $this->get_bookings($start, $end, $service_id);
            
            // Add bookings to events
            foreach ($bookings as $booking) {
                $events[] = $this->format_booking_event($booking);
            }
        } else {
            // Regular users can only see availability
            $availability = $this->get_availability($start, $end, $service_id);
            
            // Add availability to events
            foreach ($availability as $date => $slots) {
                $events[] = $this->format_availability_event($date, $slots, $service_id);
            }
        }
        
        return $events;
    }

    /**
     * Get bookings
     *
     * @param string $start Start date
     * @param string $end End date
     * @param int $service_id Service ID
     * @return array Bookings
     */
    private function get_bookings($start, $end, $service_id = 0) {
        $bookings_data = new AquaLuxe_Bookings_Data();
        $args = array(
            'date_from' => $start,
            'date_to' => $end,
        );
        
        if (!empty($service_id)) {
            $args['service_id'] = $service_id;
        }
        
        return $bookings_data->get_bookings($args);
    }

    /**
     * Get availability
     *
     * @param string $start Start date
     * @param string $end End date
     * @param int $service_id Service ID
     * @return array Availability
     */
    private function get_availability($start, $end, $service_id) {
        $availability = array();
        
        // Convert dates to timestamps
        $start_timestamp = strtotime($start);
        $end_timestamp = strtotime($end);
        
        // Loop through each day in the range
        for ($day = $start_timestamp; $day <= $end_timestamp; $day += 86400) {
            $date = date('Y-m-d', $day);
            
            // Skip dates in the past
            if ($day < strtotime('today')) {
                continue;
            }
            
            // Get available time slots for this date
            $ajax = new AquaLuxe_Bookings_AJAX();
            $time_slots = $ajax->get_service_available_times($service_id, $date);
            
            // Add to availability
            $availability[$date] = $time_slots;
        }
        
        return $availability;
    }

    /**
     * Format booking event
     *
     * @param array $booking Booking data
     * @return array Event data
     */
    private function format_booking_event($booking) {
        // Set color based on status
        $color = '#3788d8'; // Default blue
        
        switch ($booking['status']) {
            case 'aqualuxe-pending':
                $color = '#f8dda7'; // Yellow
                break;
            case 'aqualuxe-confirmed':
                $color = '#c6e1c6'; // Green
                break;
            case 'aqualuxe-completed':
                $color = '#c8d7e1'; // Blue
                break;
            case 'aqualuxe-cancelled':
                $color = '#eba3a3'; // Red
                break;
        }
        
        return array(
            'id' => $booking['id'],
            'title' => $booking['customer_name'] . ' - ' . $booking['service_name'],
            'start' => $booking['start_date'],
            'end' => $booking['end_date'],
            'allDay' => (bool) $booking['all_day'],
            'backgroundColor' => $color,
            'borderColor' => $color,
            'textColor' => '#ffffff',
            'url' => admin_url('post.php?post=' . $booking['id'] . '&action=edit'),
            'extendedProps' => array(
                'booking_id' => $booking['booking_id'],
                'service_id' => $booking['service_id'],
                'service_name' => $booking['service_name'],
                'customer_name' => $booking['customer_name'],
                'customer_email' => $booking['customer_email'],
                'customer_phone' => $booking['customer_phone'],
                'status' => $booking['status'],
                'quantity' => $booking['quantity'],
                'total' => $booking['total'],
                'type' => 'booking',
            ),
        );
    }

    /**
     * Format availability event
     *
     * @param string $date Date
     * @param array $slots Time slots
     * @param int $service_id Service ID
     * @return array Event data
     */
    private function format_availability_event($date, $slots, $service_id) {
        // Count available slots
        $available_slots = count($slots);
        
        // Set color based on availability
        $color = '#c6e1c6'; // Green (available)
        $text_color = '#000000';
        
        if ($available_slots === 0) {
            $color = '#eba3a3'; // Red (unavailable)
            $text_color = '#ffffff';
        }
        
        // Get service name
        $service_name = get_the_title($service_id);
        
        // Format title
        $title = $available_slots > 0 ? sprintf(__('%d Available Slots', 'aqualuxe'), $available_slots) : __('Fully Booked', 'aqualuxe');
        
        // Get booking page URL
        $booking_page_id = get_option('aqualuxe_bookings_page_id');
        $booking_url = $booking_page_id ? add_query_arg(array('service_id' => $service_id, 'date' => $date), get_permalink($booking_page_id)) : '';
        
        return array(
            'id' => 'availability_' . $date,
            'title' => $title,
            'start' => $date,
            'allDay' => true,
            'backgroundColor' => $color,
            'borderColor' => $color,
            'textColor' => $text_color,
            'url' => $available_slots > 0 ? $booking_url : '',
            'extendedProps' => array(
                'service_id' => $service_id,
                'service_name' => $service_name,
                'available_slots' => $available_slots,
                'slots' => $slots,
                'type' => 'availability',
            ),
        );
    }

    /**
     * Generate calendar HTML
     *
     * @param array $args Calendar arguments
     * @return string Calendar HTML
     */
    public function generate_calendar_html($args = array()) {
        $defaults = array(
            'service_id' => 0,
            'show_title' => true,
            'show_legend' => true,
            'view' => 'month',
            'height' => 'auto',
            'events_limit' => 10,
            'show_weekends' => true,
            'show_all_day' => true,
            'show_time' => true,
            'show_booking_button' => true,
            'booking_button_text' => __('Book Now', 'aqualuxe'),
        );
        
        $args = wp_parse_args($args, $defaults);
        
        // Get service data
        $service_id = $args['service_id'];
        $service_name = $service_id ? get_the_title($service_id) : '';
        
        // Start output buffering
        ob_start();
        
        // Calendar container
        echo '<div class="aqualuxe-bookings-calendar-container">';
        
        // Title
        if ($args['show_title'] && $service_name) {
            echo '<h2 class="aqualuxe-bookings-calendar-title">' . sprintf(__('Availability Calendar: %s', 'aqualuxe'), $service_name) . '</h2>';
        }
        
        // Calendar
        echo '<div class="aqualuxe-bookings-calendar" data-service-id="' . esc_attr($service_id) . '" data-view="' . esc_attr($args['view']) . '" data-height="' . esc_attr($args['height']) . '" data-events-limit="' . esc_attr($args['events_limit']) . '" data-show-weekends="' . esc_attr($args['show_weekends'] ? 'true' : 'false') . '" data-show-all-day="' . esc_attr($args['show_all_day'] ? 'true' : 'false') . '" data-show-time="' . esc_attr($args['show_time'] ? 'true' : 'false') . '"></div>';
        
        // Legend
        if ($args['show_legend']) {
            echo '<div class="aqualuxe-bookings-calendar-legend">';
            echo '<ul>';
            echo '<li><span class="legend-color" style="background-color: #c6e1c6;"></span>' . __('Available', 'aqualuxe') . '</li>';
            echo '<li><span class="legend-color" style="background-color: #eba3a3;"></span>' . __('Fully Booked', 'aqualuxe') . '</li>';
            echo '</ul>';
            echo '</div>';
        }
        
        echo '</div>';
        
        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Generate mini calendar HTML
     *
     * @param array $args Calendar arguments
     * @return string Calendar HTML
     */
    public function generate_mini_calendar_html($args = array()) {
        $defaults = array(
            'service_id' => 0,
            'show_title' => false,
            'show_legend' => false,
            'months' => 1,
        );
        
        $args = wp_parse_args($args, $defaults);
        
        // Get service data
        $service_id = $args['service_id'];
        $service_name = $service_id ? get_the_title($service_id) : '';
        
        // Get current month and year
        $month = date('n');
        $year = date('Y');
        
        // Start output buffering
        ob_start();
        
        // Calendar container
        echo '<div class="aqualuxe-bookings-mini-calendar-container">';
        
        // Title
        if ($args['show_title'] && $service_name) {
            echo '<h3 class="aqualuxe-bookings-mini-calendar-title">' . sprintf(__('Availability: %s', 'aqualuxe'), $service_name) . '</h3>';
        }
        
        // Generate calendars
        for ($i = 0; $i < $args['months']; $i++) {
            $current_month = ($month + $i) % 12;
            $current_month = $current_month === 0 ? 12 : $current_month;
            $current_year = $year + floor(($month + $i - 1) / 12);
            
            echo $this->generate_month_calendar($current_month, $current_year, $service_id);
        }
        
        // Legend
        if ($args['show_legend']) {
            echo '<div class="aqualuxe-bookings-mini-calendar-legend">';
            echo '<ul>';
            echo '<li><span class="legend-color" style="background-color: #c6e1c6;"></span>' . __('Available', 'aqualuxe') . '</li>';
            echo '<li><span class="legend-color" style="background-color: #eba3a3;"></span>' . __('Fully Booked', 'aqualuxe') . '</li>';
            echo '<li><span class="legend-color" style="background-color: #f8dda7;"></span>' . __('Partially Booked', 'aqualuxe') . '</li>';
            echo '</ul>';
            echo '</div>';
        }
        
        echo '</div>';
        
        // Return the buffered content
        return ob_get_clean();
    }

    /**
     * Generate month calendar
     *
     * @param int $month Month (1-12)
     * @param int $year Year
     * @param int $service_id Service ID
     * @return string Calendar HTML
     */
    private function generate_month_calendar($month, $year, $service_id) {
        // Get first and last day of month
        $first_day = strtotime($year . '-' . $month . '-01');
        $last_day = strtotime(date('Y-m-t', $first_day));
        
        // Get day of week for first day (0 = Sunday, 6 = Saturday)
        $first_day_of_week = date('w', $first_day);
        
        // Get calendar first day setting (0 = Sunday, 1 = Monday)
        $calendar_first_day = get_option('aqualuxe_bookings_calendar_first_day', 0);
        
        // Adjust first day of week
        $first_day_of_week = ($first_day_of_week - $calendar_first_day + 7) % 7;
        
        // Get available dates
        $ajax = new AquaLuxe_Bookings_AJAX();
        $available_dates = $ajax->get_service_available_dates($service_id, $first_day, $last_day);
        
        // Get month name
        $month_name = date_i18n('F', $first_day);
        
        // Get day names
        $day_names = array();
        
        for ($i = 0; $i < 7; $i++) {
            $day_names[] = date_i18n('D', strtotime('+' . (($i + $calendar_first_day) % 7) . ' days', strtotime('Sunday')));
        }
        
        // Start output buffering
        ob_start();
        
        // Calendar container
        echo '<div class="aqualuxe-bookings-mini-calendar">';
        
        // Month header
        echo '<div class="aqualuxe-bookings-mini-calendar-header">';
        echo '<h4>' . $month_name . ' ' . $year . '</h4>';
        echo '</div>';
        
        // Calendar table
        echo '<table class="aqualuxe-bookings-mini-calendar-table">';
        
        // Day names
        echo '<tr>';
        foreach ($day_names as $day_name) {
            echo '<th>' . $day_name . '</th>';
        }
        echo '</tr>';
        
        // Calendar days
        $day_count = 1;
        $total_days = date('t', $first_day);
        
        // Start with empty cells for days before the first day of the month
        echo '<tr>';
        for ($i = 0; $i < $first_day_of_week; $i++) {
            echo '<td class="empty"></td>';
        }
        
        // Fill in the days of the month
        for ($i = $first_day_of_week; $i < 7; $i++) {
            $date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $day_count));
            $is_available = in_array($date, $available_dates);
            $is_past = strtotime($date) < strtotime('today');
            $class = $is_past ? 'past' : ($is_available ? 'available' : 'unavailable');
            
            echo '<td class="' . $class . '">';
            
            if ($is_available && !$is_past) {
                $booking_page_id = get_option('aqualuxe_bookings_page_id');
                $booking_url = $booking_page_id ? add_query_arg(array('service_id' => $service_id, 'date' => $date), get_permalink($booking_page_id)) : '';
                
                echo '<a href="' . esc_url($booking_url) . '">' . $day_count . '</a>';
            } else {
                echo $day_count;
            }
            
            echo '</td>';
            
            $day_count++;
        }
        echo '</tr>';
        
        // Continue with the rest of the days
        while ($day_count <= $total_days) {
            echo '<tr>';
            
            for ($i = 0; $i < 7; $i++) {
                if ($day_count <= $total_days) {
                    $date = date('Y-m-d', strtotime($year . '-' . $month . '-' . $day_count));
                    $is_available = in_array($date, $available_dates);
                    $is_past = strtotime($date) < strtotime('today');
                    $class = $is_past ? 'past' : ($is_available ? 'available' : 'unavailable');
                    
                    echo '<td class="' . $class . '">';
                    
                    if ($is_available && !$is_past) {
                        $booking_page_id = get_option('aqualuxe_bookings_page_id');
                        $booking_url = $booking_page_id ? add_query_arg(array('service_id' => $service_id, 'date' => $date), get_permalink($booking_page_id)) : '';
                        
                        echo '<a href="' . esc_url($booking_url) . '">' . $day_count . '</a>';
                    } else {
                        echo $day_count;
                    }
                    
                    echo '</td>';
                    
                    $day_count++;
                } else {
                    echo '<td class="empty"></td>';
                }
            }
            
            echo '</tr>';
        }
        
        echo '</table>';
        echo '</div>';
        
        // Return the buffered content
        return ob_get_clean();
    }
}