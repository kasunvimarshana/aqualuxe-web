<?php
/**
 * Events Calendar Functions
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get event by ID.
 *
 * @param int $event_id Event ID.
 * @return AquaLuxe_Event|false
 */
function aqualuxe_get_event($event_id) {
    $event = new AquaLuxe_Event($event_id);
    
    if ($event->id > 0) {
        return $event;
    }
    
    return false;
}

/**
 * Get events.
 *
 * @param array $args Query arguments.
 * @return array
 */
function aqualuxe_get_events($args = array()) {
    $events = array();
    
    $default_args = array(
        'post_type'      => 'aqualuxe_event',
        'posts_per_page' => 10,
        'post_status'    => 'publish',
    );
    
    $args = wp_parse_args($args, $default_args);
    
    $posts = get_posts($args);
    
    foreach ($posts as $post) {
        $events[] = new AquaLuxe_Event($post);
    }
    
    return $events;
}

/**
 * Get upcoming events.
 *
 * @param int $limit Number of events to get.
 * @param string $category Category slug.
 * @return array
 */
function aqualuxe_get_upcoming_events($limit = 5, $category = '') {
    $args = array(
        'posts_per_page' => $limit,
        'meta_key'       => '_event_start_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => array(
            array(
                'key'     => '_event_start_date',
                'value'   => date('Y-m-d'),
                'compare' => '>=',
                'type'    => 'DATE',
            ),
        ),
    );
    
    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'aqualuxe_event_category',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        );
    }
    
    return aqualuxe_get_events($args);
}

/**
 * Get past events.
 *
 * @param int $limit Number of events to get.
 * @param string $category Category slug.
 * @return array
 */
function aqualuxe_get_past_events($limit = 5, $category = '') {
    $args = array(
        'posts_per_page' => $limit,
        'meta_key'       => '_event_end_date',
        'orderby'        => 'meta_value',
        'order'          => 'DESC',
        'meta_query'     => array(
            array(
                'key'     => '_event_end_date',
                'value'   => date('Y-m-d'),
                'compare' => '<',
                'type'    => 'DATE',
            ),
        ),
    );
    
    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'aqualuxe_event_category',
                'field'    => 'slug',
                'terms'    => $category,
            ),
        );
    }
    
    return aqualuxe_get_events($args);
}

/**
 * Get featured events.
 *
 * @param int $limit Number of events to get.
 * @return array
 */
function aqualuxe_get_featured_events($limit = 5) {
    $args = array(
        'posts_per_page' => $limit,
        'meta_query'     => array(
            array(
                'key'     => '_event_featured',
                'value'   => '1',
                'compare' => '=',
            ),
            array(
                'key'     => '_event_start_date',
                'value'   => date('Y-m-d'),
                'compare' => '>=',
                'type'    => 'DATE',
            ),
        ),
    );
    
    return aqualuxe_get_events($args);
}

/**
 * Get events by category.
 *
 * @param string|int $category Category ID or slug.
 * @param int $limit Number of events to get.
 * @return array
 */
function aqualuxe_get_events_by_category($category, $limit = -1) {
    $args = array(
        'posts_per_page' => $limit,
        'tax_query'      => array(
            array(
                'taxonomy' => 'aqualuxe_event_category',
                'field'    => is_numeric($category) ? 'term_id' : 'slug',
                'terms'    => $category,
            ),
        ),
    );
    
    return aqualuxe_get_events($args);
}

/**
 * Get event category by ID or slug.
 *
 * @param int|string $category Category ID or slug.
 * @return AquaLuxe_Event_Category|false
 */
function aqualuxe_get_event_category($category) {
    if (is_numeric($category)) {
        $term = get_term($category, 'aqualuxe_event_category');
    } else {
        $term = get_term_by('slug', $category, 'aqualuxe_event_category');
    }
    
    if ($term && !is_wp_error($term)) {
        return new AquaLuxe_Event_Category($term);
    }
    
    return false;
}

/**
 * Get event categories.
 *
 * @param array $args Query arguments.
 * @return array
 */
function aqualuxe_get_event_categories($args = array()) {
    $categories = array();
    
    $default_args = array(
        'taxonomy'   => 'aqualuxe_event_category',
        'hide_empty' => false,
    );
    
    $args = wp_parse_args($args, $default_args);
    
    $terms = get_terms($args);
    
    if (!is_wp_error($terms)) {
        foreach ($terms as $term) {
            $categories[] = new AquaLuxe_Event_Category($term);
        }
    }
    
    return $categories;
}

/**
 * Get event ticket by ID.
 *
 * @param int $ticket_id Ticket ID.
 * @return AquaLuxe_Event_Ticket|false
 */
function aqualuxe_get_event_ticket($ticket_id) {
    $ticket = new AquaLuxe_Event_Ticket($ticket_id);
    
    if ($ticket->id > 0) {
        return $ticket;
    }
    
    return false;
}

/**
 * Get event tickets.
 *
 * @param int $event_id Event ID.
 * @return array
 */
function aqualuxe_get_event_tickets($event_id) {
    $tickets = array();
    
    $ticket_posts = get_posts(array(
        'post_type'      => 'aqualuxe_ticket',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'   => '_ticket_event_id',
                'value' => $event_id,
            ),
        ),
    ));
    
    foreach ($ticket_posts as $ticket_post) {
        $tickets[] = new AquaLuxe_Event_Ticket($ticket_post);
    }
    
    return $tickets;
}

/**
 * Get event calendar.
 *
 * @param string $view Calendar view type (month, week, day, list).
 * @param string $date Current date (YYYY-MM-DD).
 * @return AquaLuxe_Event_Calendar
 */
function aqualuxe_get_event_calendar($view = 'month', $date = '') {
    return new AquaLuxe_Event_Calendar($view, $date);
}

/**
 * Format event date range.
 *
 * @param AquaLuxe_Event $event Event object.
 * @param string $date_format Date format.
 * @param string $time_format Time format.
 * @return string
 */
function aqualuxe_format_event_date_range($event, $date_format = '', $time_format = '') {
    if (empty($date_format)) {
        $date_format = get_option('date_format');
    }
    
    if (empty($time_format)) {
        $time_format = get_option('time_format');
    }
    
    $start_date = $event->get_start_date($date_format);
    $end_date = $event->get_end_date($date_format);
    $start_time = $event->get_start_time($time_format);
    $end_time = $event->get_end_time($time_format);
    $all_day = $event->is_all_day();
    
    $output = '';
    
    if ($start_date === $end_date) {
        $output = $start_date;
        
        if (!$all_day) {
            $output .= ' ' . $start_time;
            
            if (!empty($end_time)) {
                $output .= ' - ' . $end_time;
            }
        } else {
            $output .= ' (' . __('All Day', 'aqualuxe') . ')';
        }
    } else {
        $output = $start_date;
        
        if (!$all_day) {
            $output .= ' ' . $start_time;
        }
        
        $output .= ' - ' . $end_date;
        
        if (!$all_day) {
            $output .= ' ' . $end_time;
        }
    }
    
    return $output;
}

/**
 * Check if event is happening today.
 *
 * @param AquaLuxe_Event $event Event object.
 * @return bool
 */
function aqualuxe_is_event_today($event) {
    $today = date('Y-m-d');
    $start_date = $event->get_data('start_date');
    $end_date = $event->get_data('end_date');
    
    return ($today >= $start_date && $today <= $end_date);
}

/**
 * Check if event is happening now.
 *
 * @param AquaLuxe_Event $event Event object.
 * @return bool
 */
function aqualuxe_is_event_now($event) {
    if (!aqualuxe_is_event_today($event)) {
        return false;
    }
    
    if ($event->is_all_day()) {
        return true;
    }
    
    $now = current_time('timestamp');
    $start_time = strtotime($event->get_data('start_date') . ' ' . $event->get_data('start_time'));
    $end_time = strtotime($event->get_data('end_date') . ' ' . $event->get_data('end_time'));
    
    return ($now >= $start_time && $now <= $end_time);
}

/**
 * Get event status.
 *
 * @param AquaLuxe_Event $event Event object.
 * @return string
 */
function aqualuxe_get_event_status($event) {
    $today = date('Y-m-d');
    $now = current_time('timestamp');
    $start_date = $event->get_data('start_date');
    $end_date = $event->get_data('end_date');
    $start_time = strtotime($start_date . ' ' . $event->get_data('start_time'));
    $end_time = strtotime($end_date . ' ' . $event->get_data('end_time'));
    
    if ($end_date < $today || ($end_date == $today && $now > $end_time)) {
        return 'past';
    } elseif ($start_date > $today) {
        return 'upcoming';
    } elseif ($start_date == $today && $now < $start_time) {
        return 'today';
    } else {
        return 'ongoing';
    }
}

/**
 * Get event status label.
 *
 * @param string $status Event status.
 * @return string
 */
function aqualuxe_get_event_status_label($status) {
    $labels = array(
        'past'     => __('Past', 'aqualuxe'),
        'upcoming' => __('Upcoming', 'aqualuxe'),
        'today'    => __('Today', 'aqualuxe'),
        'ongoing'  => __('Ongoing', 'aqualuxe'),
    );
    
    return isset($labels[$status]) ? $labels[$status] : '';
}

/**
 * Get event status CSS class.
 *
 * @param string $status Event status.
 * @return string
 */
function aqualuxe_get_event_status_class($status) {
    $classes = array(
        'past'     => 'event-past',
        'upcoming' => 'event-upcoming',
        'today'    => 'event-today',
        'ongoing'  => 'event-ongoing',
    );
    
    return isset($classes[$status]) ? $classes[$status] : '';
}

/**
 * Get event permalink.
 *
 * @param int|AquaLuxe_Event $event Event ID or object.
 * @return string
 */
function aqualuxe_get_event_permalink($event) {
    if (is_numeric($event)) {
        $event = aqualuxe_get_event($event);
    }
    
    if ($event) {
        return $event->get_permalink();
    }
    
    return '';
}

/**
 * Get event calendar permalink.
 *
 * @param string $view Calendar view type (month, week, day, list).
 * @param string $date Current date (YYYY-MM-DD).
 * @return string
 */
function aqualuxe_get_event_calendar_permalink($view = 'month', $date = '') {
    $url = home_url('/events/calendar/' . $view . '/');
    
    if (!empty($date)) {
        $url = add_query_arg('date', $date, $url);
    }
    
    return $url;
}

/**
 * Register event.
 *
 * @param array $data Event data.
 * @return int|WP_Error
 */
function aqualuxe_register_event($data) {
    $event = new AquaLuxe_Event();
    
    foreach ($data as $key => $value) {
        $event->set_data($key, $value);
    }
    
    $event_id = $event->save();
    
    if ($event_id) {
        return $event_id;
    }
    
    return new WP_Error('event_registration_failed', __('Failed to register event.', 'aqualuxe'));
}

/**
 * Purchase event ticket.
 *
 * @param int $ticket_id Ticket ID.
 * @param int $quantity Quantity.
 * @param array $attendee_data Attendee data.
 * @return int|WP_Error
 */
function aqualuxe_purchase_event_ticket($ticket_id, $quantity = 1, $attendee_data = array()) {
    $ticket = aqualuxe_get_event_ticket($ticket_id);
    
    if (!$ticket) {
        return new WP_Error('invalid_ticket', __('Invalid ticket.', 'aqualuxe'));
    }
    
    if (!$ticket->is_available()) {
        return new WP_Error('ticket_not_available', __('Ticket is not available.', 'aqualuxe'));
    }
    
    $quantity = absint($quantity);
    
    if ($quantity < $ticket->get_min_purchase()) {
        return new WP_Error(
            'min_purchase_not_met',
            sprintf(
                __('Minimum purchase quantity is %d.', 'aqualuxe'),
                $ticket->get_min_purchase()
            )
        );
    }
    
    if ($ticket->get_max_purchase() > 0 && $quantity > $ticket->get_max_purchase()) {
        return new WP_Error(
            'max_purchase_exceeded',
            sprintf(
                __('Maximum purchase quantity is %d.', 'aqualuxe'),
                $ticket->get_max_purchase()
            )
        );
    }
    
    $remaining = $ticket->get_remaining();
    
    if ($remaining > 0 && $quantity > $remaining) {
        return new WP_Error(
            'not_enough_tickets',
            sprintf(
                __('Only %d tickets remaining.', 'aqualuxe'),
                $remaining
            )
        );
    }
    
    // Create order
    $order_data = array(
        'post_title'  => sprintf(__('Ticket Order - %s', 'aqualuxe'), $ticket->get_title()),
        'post_status' => 'publish',
        'post_type'   => 'aqualuxe_ticket_order',
    );
    
    $order_id = wp_insert_post($order_data);
    
    if (is_wp_error($order_id)) {
        return $order_id;
    }
    
    // Save order meta
    update_post_meta($order_id, '_order_ticket_id', $ticket_id);
    update_post_meta($order_id, '_order_event_id', $ticket->get_event_id());
    update_post_meta($order_id, '_order_quantity', $quantity);
    update_post_meta($order_id, '_order_total', $quantity * $ticket->get_price(false));
    update_post_meta($order_id, '_order_attendee_data', $attendee_data);
    
    // Record ticket sale
    $ticket->record_sale($quantity);
    
    return $order_id;
}

/**
 * Get event attendees.
 *
 * @param int $event_id Event ID.
 * @return array
 */
function aqualuxe_get_event_attendees($event_id) {
    $attendees = array();
    
    $orders = get_posts(array(
        'post_type'      => 'aqualuxe_ticket_order',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'   => '_order_event_id',
                'value' => $event_id,
            ),
        ),
    ));
    
    foreach ($orders as $order) {
        $order_id = $order->ID;
        $quantity = get_post_meta($order_id, '_order_quantity', true);
        $attendee_data = get_post_meta($order_id, '_order_attendee_data', true);
        
        if (!empty($attendee_data)) {
            $attendees[] = array(
                'order_id'      => $order_id,
                'quantity'      => $quantity,
                'attendee_data' => $attendee_data,
            );
        }
    }
    
    return $attendees;
}

/**
 * Get event ticket sales.
 *
 * @param int $event_id Event ID.
 * @return array
 */
function aqualuxe_get_event_ticket_sales($event_id) {
    $sales = array();
    
    $tickets = aqualuxe_get_event_tickets($event_id);
    
    foreach ($tickets as $ticket) {
        $sales[$ticket->id] = array(
            'ticket'    => $ticket,
            'sold'      => $ticket->get_sold(),
            'remaining' => $ticket->get_remaining(),
            'capacity'  => $ticket->get_capacity(),
        );
    }
    
    return $sales;
}

/**
 * Get event calendar views.
 *
 * @return array
 */
function aqualuxe_get_event_calendar_views() {
    return array(
        'month' => __('Month', 'aqualuxe'),
        'week'  => __('Week', 'aqualuxe'),
        'day'   => __('Day', 'aqualuxe'),
        'list'  => __('List', 'aqualuxe'),
    );
}

/**
 * Get event recurrence patterns.
 *
 * @return array
 */
function aqualuxe_get_event_recurrence_patterns() {
    return array(
        'daily'   => __('Daily', 'aqualuxe'),
        'weekly'  => __('Weekly', 'aqualuxe'),
        'monthly' => __('Monthly', 'aqualuxe'),
        'yearly'  => __('Yearly', 'aqualuxe'),
        'custom'  => __('Custom', 'aqualuxe'),
    );
}

/**
 * Get event template part.
 *
 * @param string $slug Template slug.
 * @param string $name Template name.
 * @param array $args Template arguments.
 */
function aqualuxe_get_event_template_part($slug, $name = '', $args = array()) {
    $template = '';
    
    // Look in theme/aqualuxe/events/ folder
    if ($name) {
        $template = locate_template(array("aqualuxe/events/{$slug}-{$name}.php"));
    }
    
    if (!$template && $name) {
        $template = AQUALUXE_EVENTS_ABSPATH . "templates/{$slug}-{$name}.php";
    }
    
    if (!$template) {
        $template = locate_template(array("aqualuxe/events/{$slug}.php"));
    }
    
    if (!$template) {
        $template = AQUALUXE_EVENTS_ABSPATH . "templates/{$slug}.php";
    }
    
    // Allow 3rd party plugins to filter template file from their plugin.
    $template = apply_filters('aqualuxe_get_event_template_part', $template, $slug, $name);
    
    if ($template) {
        if (!empty($args) && is_array($args)) {
            extract($args);
        }
        
        include $template;
    }
}

/**
 * Add event to calendar.
 *
 * @param int|AquaLuxe_Event $event Event ID or object.
 * @param string $calendar_type Calendar type (google, ical, outlook).
 * @return string
 */
function aqualuxe_get_add_to_calendar_link($event, $calendar_type = 'google') {
    if (is_numeric($event)) {
        $event = aqualuxe_get_event($event);
    }
    
    if (!$event) {
        return '';
    }
    
    $title = urlencode($event->get_title());
    $description = urlencode($event->get_short_description());
    $location = urlencode($event->get_full_address());
    $start_date = $event->get_data('start_date');
    $end_date = $event->get_data('end_date');
    $start_time = $event->get_data('start_time');
    $end_time = $event->get_data('end_time');
    $all_day = $event->is_all_day();
    
    $start = $start_date . ($all_day ? '' : 'T' . $start_time);
    $end = $end_date . ($all_day ? '' : 'T' . $end_time);
    
    switch ($calendar_type) {
        case 'google':
            $url = 'https://calendar.google.com/calendar/render?action=TEMPLATE';
            $url .= '&text=' . $title;
            $url .= '&dates=' . str_replace(array('-', ':', ' '), '', $start) . '/' . str_replace(array('-', ':', ' '), '', $end);
            $url .= '&details=' . $description;
            $url .= '&location=' . $location;
            $url .= '&sprop=website:' . urlencode(get_site_url());
            break;
            
        case 'ical':
            $url = 'data:text/calendar;charset=utf8,';
            $url .= 'BEGIN:VCALENDAR%0A';
            $url .= 'VERSION:2.0%0A';
            $url .= 'BEGIN:VEVENT%0A';
            $url .= 'URL:' . urlencode(get_permalink($event->id)) . '%0A';
            $url .= 'DTSTART:' . str_replace(array('-', ':', ' '), '', $start) . '%0A';
            $url .= 'DTEND:' . str_replace(array('-', ':', ' '), '', $end) . '%0A';
            $url .= 'SUMMARY:' . $title . '%0A';
            $url .= 'DESCRIPTION:' . $description . '%0A';
            $url .= 'LOCATION:' . $location . '%0A';
            $url .= 'END:VEVENT%0A';
            $url .= 'END:VCALENDAR%0A';
            break;
            
        case 'outlook':
            $url = 'https://outlook.live.com/calendar/0/deeplink/compose?';
            $url .= 'subject=' . $title;
            $url .= '&startdt=' . urlencode($start);
            $url .= '&enddt=' . urlencode($end);
            $url .= '&body=' . $description;
            $url .= '&location=' . $location;
            $url .= '&allday=' . ($all_day ? 'true' : 'false');
            break;
            
        default:
            $url = '';
    }
    
    return $url;
}