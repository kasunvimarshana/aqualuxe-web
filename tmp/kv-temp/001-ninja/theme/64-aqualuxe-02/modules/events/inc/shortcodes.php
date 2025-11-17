<?php
/**
 * Events Calendar Shortcodes
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Events Calendar shortcode.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_events_calendar_shortcode($atts) {
    $atts = shortcode_atts(array(
        'view' => 'month',
        'date' => '',
        'category' => '',
    ), $atts, 'aqualuxe_events_calendar');
    
    // Enqueue styles and scripts
    wp_enqueue_style('aqualuxe-events');
    wp_enqueue_script('aqualuxe-events');
    
    $view = in_array($atts['view'], array('month', 'week', 'day', 'list')) ? $atts['view'] : 'month';
    $date = !empty($atts['date']) ? $atts['date'] : date('Y-m-d');
    
    $calendar = aqualuxe_get_event_calendar($view, $date);
    
    ob_start();
    
    // Calendar navigation
    echo '<div class="aqualuxe-events-calendar-nav">';
    echo '<div class="aqualuxe-events-calendar-title">' . esc_html($calendar->get_calendar_title()) . '</div>';
    echo '<div class="aqualuxe-events-calendar-actions">';
    
    // View switcher
    echo '<div class="aqualuxe-events-view-switcher">';
    foreach (aqualuxe_get_event_calendar_views() as $view_key => $view_label) {
        $active_class = $view === $view_key ? ' active' : '';
        $view_url = add_query_arg(array(
            'view' => $view_key,
            'date' => $date,
        ));
        echo '<a href="' . esc_url($view_url) . '" class="aqualuxe-events-view-' . esc_attr($view_key) . $active_class . '">' . esc_html($view_label) . '</a>';
    }
    echo '</div>';
    
    // Navigation
    echo '<div class="aqualuxe-events-calendar-navigation">';
    $prev_url = add_query_arg(array(
        'view' => $view,
        'date' => $calendar->get_prev_date(),
    ));
    $next_url = add_query_arg(array(
        'view' => $view,
        'date' => $calendar->get_next_date(),
    ));
    $today_url = add_query_arg(array(
        'view' => $view,
        'date' => date('Y-m-d'),
    ));
    
    echo '<a href="' . esc_url($prev_url) . '" class="aqualuxe-events-nav-prev">' . esc_html__('Previous', 'aqualuxe') . '</a>';
    echo '<a href="' . esc_url($today_url) . '" class="aqualuxe-events-nav-today">' . esc_html__('Today', 'aqualuxe') . '</a>';
    echo '<a href="' . esc_url($next_url) . '" class="aqualuxe-events-nav-next">' . esc_html__('Next', 'aqualuxe') . '</a>';
    echo '</div>';
    
    echo '</div>';
    echo '</div>';
    
    // Calendar content
    echo '<div class="aqualuxe-events-calendar-content">';
    
    switch ($view) {
        case 'month':
            aqualuxe_get_event_template_part('calendar', 'month', array('calendar' => $calendar));
            break;
            
        case 'week':
            aqualuxe_get_event_template_part('calendar', 'week', array('calendar' => $calendar));
            break;
            
        case 'day':
            aqualuxe_get_event_template_part('calendar', 'day', array('calendar' => $calendar));
            break;
            
        case 'list':
            aqualuxe_get_event_template_part('calendar', 'list', array('calendar' => $calendar));
            break;
    }
    
    echo '</div>';
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_events_calendar', 'aqualuxe_events_calendar_shortcode');

/**
 * Events List shortcode.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_events_list_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 5,
        'category' => '',
        'type' => 'upcoming', // upcoming, past, featured
        'layout' => 'list', // list, grid
    ), $atts, 'aqualuxe_events_list');
    
    // Enqueue styles and scripts
    wp_enqueue_style('aqualuxe-events');
    wp_enqueue_script('aqualuxe-events');
    
    $limit = absint($atts['limit']);
    $category = sanitize_text_field($atts['category']);
    $type = in_array($atts['type'], array('upcoming', 'past', 'featured')) ? $atts['type'] : 'upcoming';
    $layout = in_array($atts['layout'], array('list', 'grid')) ? $atts['layout'] : 'list';
    
    // Get events
    switch ($type) {
        case 'upcoming':
            $events = aqualuxe_get_upcoming_events($limit, $category);
            break;
            
        case 'past':
            $events = aqualuxe_get_past_events($limit, $category);
            break;
            
        case 'featured':
            $events = aqualuxe_get_featured_events($limit);
            break;
            
        default:
            $events = array();
    }
    
    ob_start();
    
    if (!empty($events)) {
        echo '<div class="aqualuxe-events-list aqualuxe-events-layout-' . esc_attr($layout) . '">';
        
        foreach ($events as $event) {
            aqualuxe_get_event_template_part('content', 'event-' . $layout, array('event' => $event));
        }
        
        echo '</div>';
    } else {
        echo '<div class="aqualuxe-events-list-empty">';
        echo '<p>' . esc_html__('No events found.', 'aqualuxe') . '</p>';
        echo '</div>';
    }
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_events_list', 'aqualuxe_events_list_shortcode');

/**
 * Event Tickets shortcode.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_event_tickets_shortcode($atts) {
    $atts = shortcode_atts(array(
        'event_id' => 0,
    ), $atts, 'aqualuxe_event_tickets');
    
    // Enqueue styles and scripts
    wp_enqueue_style('aqualuxe-events');
    wp_enqueue_script('aqualuxe-events');
    
    $event_id = absint($atts['event_id']);
    
    if (!$event_id && is_singular('aqualuxe_event')) {
        $event_id = get_the_ID();
    }
    
    if (!$event_id) {
        return '';
    }
    
    $event = aqualuxe_get_event($event_id);
    
    if (!$event) {
        return '';
    }
    
    $tickets = aqualuxe_get_event_tickets($event_id);
    
    ob_start();
    
    if (!empty($tickets)) {
        echo '<div class="aqualuxe-event-tickets">';
        echo '<h3>' . esc_html__('Tickets', 'aqualuxe') . '</h3>';
        
        echo '<div class="aqualuxe-event-tickets-list">';
        
        foreach ($tickets as $ticket) {
            aqualuxe_get_event_template_part('content', 'ticket', array('ticket' => $ticket));
        }
        
        echo '</div>';
        
        // Ticket purchase form
        if ($event->has_tickets_available()) {
            aqualuxe_get_event_template_part('ticket', 'form', array('event' => $event, 'tickets' => $tickets));
        } else {
            echo '<div class="aqualuxe-event-tickets-unavailable">';
            echo '<p>' . esc_html__('Tickets are not available for this event.', 'aqualuxe') . '</p>';
            echo '</div>';
        }
        
        echo '</div>';
    }
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_event_tickets', 'aqualuxe_event_tickets_shortcode');

/**
 * Event Categories shortcode.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_event_categories_shortcode($atts) {
    $atts = shortcode_atts(array(
        'layout' => 'list', // list, grid
        'parent' => 0,
        'hide_empty' => false,
    ), $atts, 'aqualuxe_event_categories');
    
    // Enqueue styles and scripts
    wp_enqueue_style('aqualuxe-events');
    wp_enqueue_script('aqualuxe-events');
    
    $layout = in_array($atts['layout'], array('list', 'grid')) ? $atts['layout'] : 'list';
    $parent = absint($atts['parent']);
    $hide_empty = filter_var($atts['hide_empty'], FILTER_VALIDATE_BOOLEAN);
    
    $categories = aqualuxe_get_event_categories(array(
        'parent'     => $parent,
        'hide_empty' => $hide_empty,
    ));
    
    ob_start();
    
    if (!empty($categories)) {
        echo '<div class="aqualuxe-event-categories aqualuxe-event-categories-layout-' . esc_attr($layout) . '">';
        
        foreach ($categories as $category) {
            aqualuxe_get_event_template_part('content', 'category-' . $layout, array('category' => $category));
        }
        
        echo '</div>';
    } else {
        echo '<div class="aqualuxe-event-categories-empty">';
        echo '<p>' . esc_html__('No event categories found.', 'aqualuxe') . '</p>';
        echo '</div>';
    }
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_event_categories', 'aqualuxe_event_categories_shortcode');

/**
 * Event Search shortcode.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_event_search_shortcode($atts) {
    $atts = shortcode_atts(array(
        'placeholder' => __('Search events...', 'aqualuxe'),
        'button_text' => __('Search', 'aqualuxe'),
        'show_filters' => true,
    ), $atts, 'aqualuxe_event_search');
    
    // Enqueue styles and scripts
    wp_enqueue_style('aqualuxe-events');
    wp_enqueue_script('aqualuxe-events');
    
    $placeholder = sanitize_text_field($atts['placeholder']);
    $button_text = sanitize_text_field($atts['button_text']);
    $show_filters = filter_var($atts['show_filters'], FILTER_VALIDATE_BOOLEAN);
    
    $categories = aqualuxe_get_event_categories();
    
    ob_start();
    
    echo '<div class="aqualuxe-event-search">';
    echo '<form action="' . esc_url(get_post_type_archive_link('aqualuxe_event')) . '" method="get" class="aqualuxe-event-search-form">';
    
    echo '<div class="aqualuxe-event-search-input">';
    echo '<input type="text" name="event_search" placeholder="' . esc_attr($placeholder) . '" value="' . esc_attr(get_query_var('event_search')) . '" />';
    echo '<button type="submit">' . esc_html($button_text) . '</button>';
    echo '</div>';
    
    if ($show_filters) {
        echo '<div class="aqualuxe-event-search-filters">';
        
        // Date filter
        echo '<div class="aqualuxe-event-search-filter aqualuxe-event-search-date">';
        echo '<label>' . esc_html__('Date', 'aqualuxe') . '</label>';
        echo '<input type="date" name="event_date" value="' . esc_attr(get_query_var('event_date')) . '" />';
        echo '</div>';
        
        // Category filter
        if (!empty($categories)) {
            echo '<div class="aqualuxe-event-search-filter aqualuxe-event-search-category">';
            echo '<label>' . esc_html__('Category', 'aqualuxe') . '</label>';
            echo '<select name="event_category">';
            echo '<option value="">' . esc_html__('All Categories', 'aqualuxe') . '</option>';
            
            foreach ($categories as $category) {
                $selected = get_query_var('event_category') == $category->get_slug() ? ' selected' : '';
                echo '<option value="' . esc_attr($category->get_slug()) . '"' . $selected . '>' . esc_html($category->get_name()) . '</option>';
            }
            
            echo '</select>';
            echo '</div>';
        }
        
        echo '</div>';
    }
    
    echo '</form>';
    echo '</div>';
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_event_search', 'aqualuxe_event_search_shortcode');

/**
 * Single Event shortcode.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_single_event_shortcode($atts) {
    $atts = shortcode_atts(array(
        'event_id' => 0,
        'show_tickets' => true,
    ), $atts, 'aqualuxe_single_event');
    
    // Enqueue styles and scripts
    wp_enqueue_style('aqualuxe-events');
    wp_enqueue_script('aqualuxe-events');
    
    $event_id = absint($atts['event_id']);
    $show_tickets = filter_var($atts['show_tickets'], FILTER_VALIDATE_BOOLEAN);
    
    if (!$event_id && is_singular('aqualuxe_event')) {
        $event_id = get_the_ID();
    }
    
    if (!$event_id) {
        return '';
    }
    
    $event = aqualuxe_get_event($event_id);
    
    if (!$event) {
        return '';
    }
    
    ob_start();
    
    aqualuxe_get_event_template_part('content', 'single-event', array('event' => $event));
    
    if ($show_tickets) {
        echo do_shortcode('[aqualuxe_event_tickets event_id="' . $event_id . '"]');
    }
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_single_event', 'aqualuxe_single_event_shortcode');

/**
 * Event Countdown shortcode.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_event_countdown_shortcode($atts) {
    $atts = shortcode_atts(array(
        'event_id' => 0,
        'show_labels' => true,
    ), $atts, 'aqualuxe_event_countdown');
    
    // Enqueue styles and scripts
    wp_enqueue_style('aqualuxe-events');
    wp_enqueue_script('aqualuxe-events');
    
    $event_id = absint($atts['event_id']);
    $show_labels = filter_var($atts['show_labels'], FILTER_VALIDATE_BOOLEAN);
    
    if (!$event_id && is_singular('aqualuxe_event')) {
        $event_id = get_the_ID();
    }
    
    if (!$event_id) {
        return '';
    }
    
    $event = aqualuxe_get_event($event_id);
    
    if (!$event) {
        return '';
    }
    
    $start_date = $event->get_data('start_date');
    $start_time = $event->get_data('start_time');
    
    if (empty($start_date)) {
        return '';
    }
    
    $start_datetime = $start_date . ' ' . $start_time;
    $start_timestamp = strtotime($start_datetime);
    
    if ($start_timestamp < time()) {
        return '';
    }
    
    $unique_id = 'aqualuxe-event-countdown-' . $event_id;
    
    ob_start();
    
    echo '<div id="' . esc_attr($unique_id) . '" class="aqualuxe-event-countdown" data-countdown="' . esc_attr($start_datetime) . '">';
    echo '<div class="aqualuxe-event-countdown-days">';
    echo '<span class="aqualuxe-event-countdown-value">00</span>';
    if ($show_labels) {
        echo '<span class="aqualuxe-event-countdown-label">' . esc_html__('Days', 'aqualuxe') . '</span>';
    }
    echo '</div>';
    
    echo '<div class="aqualuxe-event-countdown-hours">';
    echo '<span class="aqualuxe-event-countdown-value">00</span>';
    if ($show_labels) {
        echo '<span class="aqualuxe-event-countdown-label">' . esc_html__('Hours', 'aqualuxe') . '</span>';
    }
    echo '</div>';
    
    echo '<div class="aqualuxe-event-countdown-minutes">';
    echo '<span class="aqualuxe-event-countdown-value">00</span>';
    if ($show_labels) {
        echo '<span class="aqualuxe-event-countdown-label">' . esc_html__('Minutes', 'aqualuxe') . '</span>';
    }
    echo '</div>';
    
    echo '<div class="aqualuxe-event-countdown-seconds">';
    echo '<span class="aqualuxe-event-countdown-value">00</span>';
    if ($show_labels) {
        echo '<span class="aqualuxe-event-countdown-label">' . esc_html__('Seconds', 'aqualuxe') . '</span>';
    }
    echo '</div>';
    echo '</div>';
    
    echo '<script>
        jQuery(document).ready(function($) {
            var countDownDate = new Date("' . esc_js($start_datetime) . '").getTime();
            
            var countdownTimer = setInterval(function() {
                var now = new Date().getTime();
                var distance = countDownDate - now;
                
                if (distance < 0) {
                    clearInterval(countdownTimer);
                    $("#' . esc_js($unique_id) . '").html("' . esc_js(__('Event has started', 'aqualuxe')) . '");
                    return;
                }
                
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                $("#' . esc_js($unique_id) . ' .aqualuxe-event-countdown-days .aqualuxe-event-countdown-value").text(days < 10 ? "0" + days : days);
                $("#' . esc_js($unique_id) . ' .aqualuxe-event-countdown-hours .aqualuxe-event-countdown-value").text(hours < 10 ? "0" + hours : hours);
                $("#' . esc_js($unique_id) . ' .aqualuxe-event-countdown-minutes .aqualuxe-event-countdown-value").text(minutes < 10 ? "0" + minutes : minutes);
                $("#' . esc_js($unique_id) . ' .aqualuxe-event-countdown-seconds .aqualuxe-event-countdown-value").text(seconds < 10 ? "0" + seconds : seconds);
            }, 1000);
        });
    </script>';
    
    return ob_get_clean();
}
add_shortcode('aqualuxe_event_countdown', 'aqualuxe_event_countdown_shortcode');