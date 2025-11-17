<?php
/**
 * Event Calendar Class
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe_Event_Calendar class.
 */
class AquaLuxe_Event_Calendar {

    /**
     * Calendar view type.
     *
     * @var string
     */
    protected $view = 'month';

    /**
     * Current date.
     *
     * @var string
     */
    protected $current_date = '';

    /**
     * Start date.
     *
     * @var string
     */
    protected $start_date = '';

    /**
     * End date.
     *
     * @var string
     */
    protected $end_date = '';

    /**
     * Events.
     *
     * @var array
     */
    protected $events = array();

    /**
     * Categories.
     *
     * @var array
     */
    protected $categories = array();

    /**
     * Constructor.
     *
     * @param string $view Calendar view type (month, week, day, list).
     * @param string $date Current date (YYYY-MM-DD).
     */
    public function __construct($view = 'month', $date = '') {
        $this->view = in_array($view, array('month', 'week', 'day', 'list')) ? $view : 'month';
        $this->current_date = !empty($date) ? $date : date('Y-m-d');
        
        $this->set_date_range();
        $this->load_events();
        $this->load_categories();
    }

    /**
     * Set date range based on view type.
     */
    protected function set_date_range() {
        $current_timestamp = strtotime($this->current_date);
        
        switch ($this->view) {
            case 'month':
                $this->start_date = date('Y-m-01', $current_timestamp);
                $this->end_date = date('Y-m-t', $current_timestamp);
                break;
                
            case 'week':
                $day_of_week = date('w', $current_timestamp);
                $week_start = $current_timestamp - ($day_of_week * 86400);
                $week_end = $week_start + (6 * 86400);
                
                $this->start_date = date('Y-m-d', $week_start);
                $this->end_date = date('Y-m-d', $week_end);
                break;
                
            case 'day':
                $this->start_date = $this->current_date;
                $this->end_date = $this->current_date;
                break;
                
            case 'list':
                $this->start_date = $this->current_date;
                $this->end_date = date('Y-m-d', strtotime($this->current_date . ' +30 days'));
                break;
        }
    }

    /**
     * Load events for the current date range.
     */
    protected function load_events() {
        $args = array(
            'post_type'      => 'aqualuxe_event',
            'posts_per_page' => -1,
            'meta_query'     => array(
                'relation' => 'OR',
                // Events starting within the date range
                array(
                    'key'     => '_event_start_date',
                    'value'   => array($this->start_date, $this->end_date),
                    'compare' => 'BETWEEN',
                    'type'    => 'DATE',
                ),
                // Events ending within the date range
                array(
                    'key'     => '_event_end_date',
                    'value'   => array($this->start_date, $this->end_date),
                    'compare' => 'BETWEEN',
                    'type'    => 'DATE',
                ),
                // Events spanning the date range
                array(
                    'relation' => 'AND',
                    array(
                        'key'     => '_event_start_date',
                        'value'   => $this->start_date,
                        'compare' => '<=',
                        'type'    => 'DATE',
                    ),
                    array(
                        'key'     => '_event_end_date',
                        'value'   => $this->end_date,
                        'compare' => '>=',
                        'type'    => 'DATE',
                    ),
                ),
            ),
        );
        
        $posts = get_posts($args);
        
        foreach ($posts as $post) {
            $event = new AquaLuxe_Event($post);
            $this->events[] = $event;
        }
        
        // Sort events by start date and time
        usort($this->events, function($a, $b) {
            $a_start = $a->get_data('start_date') . ' ' . $a->get_data('start_time');
            $b_start = $b->get_data('start_date') . ' ' . $b->get_data('start_time');
            
            return strtotime($a_start) - strtotime($b_start);
        });
    }

    /**
     * Load event categories.
     */
    protected function load_categories() {
        $terms = get_terms(array(
            'taxonomy'   => 'aqualuxe_event_category',
            'hide_empty' => false,
        ));
        
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $this->categories[] = new AquaLuxe_Event_Category($term);
            }
        }
    }

    /**
     * Get calendar view.
     *
     * @return string
     */
    public function get_view() {
        return $this->view;
    }

    /**
     * Set calendar view.
     *
     * @param string $view Calendar view type (month, week, day, list).
     */
    public function set_view($view) {
        if (in_array($view, array('month', 'week', 'day', 'list'))) {
            $this->view = $view;
            $this->set_date_range();
            $this->load_events();
        }
    }

    /**
     * Get current date.
     *
     * @param string $format Date format.
     * @return string
     */
    public function get_current_date($format = 'Y-m-d') {
        return date($format, strtotime($this->current_date));
    }

    /**
     * Set current date.
     *
     * @param string $date Current date (YYYY-MM-DD).
     */
    public function set_current_date($date) {
        $this->current_date = $date;
        $this->set_date_range();
        $this->load_events();
    }

    /**
     * Get start date.
     *
     * @param string $format Date format.
     * @return string
     */
    public function get_start_date($format = 'Y-m-d') {
        return date($format, strtotime($this->start_date));
    }

    /**
     * Get end date.
     *
     * @param string $format Date format.
     * @return string
     */
    public function get_end_date($format = 'Y-m-d') {
        return date($format, strtotime($this->end_date));
    }

    /**
     * Get events.
     *
     * @return array
     */
    public function get_events() {
        return $this->events;
    }

    /**
     * Get events for a specific date.
     *
     * @param string $date Date (YYYY-MM-DD).
     * @return array
     */
    public function get_events_for_date($date) {
        $date_events = array();
        
        foreach ($this->events as $event) {
            $start_date = $event->get_data('start_date');
            $end_date = $event->get_data('end_date');
            
            if (
                ($date >= $start_date && $date <= $end_date) ||
                $date === $start_date
            ) {
                $date_events[] = $event;
            }
        }
        
        return $date_events;
    }

    /**
     * Get categories.
     *
     * @return array
     */
    public function get_categories() {
        return $this->categories;
    }

    /**
     * Get previous date.
     *
     * @return string
     */
    public function get_prev_date() {
        $current_timestamp = strtotime($this->current_date);
        
        switch ($this->view) {
            case 'month':
                return date('Y-m-d', strtotime('-1 month', $current_timestamp));
                
            case 'week':
                return date('Y-m-d', strtotime('-1 week', $current_timestamp));
                
            case 'day':
                return date('Y-m-d', strtotime('-1 day', $current_timestamp));
                
            case 'list':
                return date('Y-m-d', strtotime('-30 days', $current_timestamp));
        }
        
        return '';
    }

    /**
     * Get next date.
     *
     * @return string
     */
    public function get_next_date() {
        $current_timestamp = strtotime($this->current_date);
        
        switch ($this->view) {
            case 'month':
                return date('Y-m-d', strtotime('+1 month', $current_timestamp));
                
            case 'week':
                return date('Y-m-d', strtotime('+1 week', $current_timestamp));
                
            case 'day':
                return date('Y-m-d', strtotime('+1 day', $current_timestamp));
                
            case 'list':
                return date('Y-m-d', strtotime('+30 days', $current_timestamp));
        }
        
        return '';
    }

    /**
     * Get calendar title.
     *
     * @return string
     */
    public function get_calendar_title() {
        $current_timestamp = strtotime($this->current_date);
        
        switch ($this->view) {
            case 'month':
                return date_i18n('F Y', $current_timestamp);
                
            case 'week':
                $week_start = strtotime($this->start_date);
                $week_end = strtotime($this->end_date);
                
                if (date('m', $week_start) === date('m', $week_end)) {
                    return date_i18n('F j', $week_start) . ' - ' . date_i18n('j, Y', $week_end);
                } else {
                    return date_i18n('F j', $week_start) . ' - ' . date_i18n('F j, Y', $week_end);
                }
                
            case 'day':
                return date_i18n('F j, Y', $current_timestamp);
                
            case 'list':
                $list_start = strtotime($this->start_date);
                $list_end = strtotime($this->end_date);
                
                if (date('m', $list_start) === date('m', $list_end)) {
                    return date_i18n('F j', $list_start) . ' - ' . date_i18n('j, Y', $list_end);
                } else {
                    return date_i18n('F j', $list_start) . ' - ' . date_i18n('F j, Y', $list_end);
                }
        }
        
        return '';
    }

    /**
     * Get month calendar data.
     *
     * @return array
     */
    public function get_month_calendar_data() {
        $month_start = strtotime($this->start_date);
        $month_end = strtotime($this->end_date);
        
        $start_day_of_week = date('w', $month_start);
        $total_days = date('t', $month_start);
        
        $calendar_data = array();
        
        // Add empty cells for days before the start of the month
        for ($i = 0; $i < $start_day_of_week; $i++) {
            $calendar_data[] = array(
                'day' => '',
                'date' => '',
                'events' => array(),
                'is_current_month' => false,
            );
        }
        
        // Add days of the month
        for ($day = 1; $day <= $total_days; $day++) {
            $date = date('Y-m-d', strtotime($this->start_date . ' +' . ($day - 1) . ' days'));
            $events = $this->get_events_for_date($date);
            
            $calendar_data[] = array(
                'day' => $day,
                'date' => $date,
                'events' => $events,
                'is_current_month' => true,
            );
        }
        
        // Add empty cells for days after the end of the month
        $end_day_of_week = date('w', $month_end);
        $remaining_days = 6 - $end_day_of_week;
        
        for ($i = 0; $i < $remaining_days; $i++) {
            $calendar_data[] = array(
                'day' => '',
                'date' => '',
                'events' => array(),
                'is_current_month' => false,
            );
        }
        
        return $calendar_data;
    }

    /**
     * Get week calendar data.
     *
     * @return array
     */
    public function get_week_calendar_data() {
        $calendar_data = array();
        
        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', strtotime($this->start_date . ' +' . $i . ' days'));
            $events = $this->get_events_for_date($date);
            
            $calendar_data[] = array(
                'day' => date_i18n('j', strtotime($date)),
                'date' => $date,
                'day_of_week' => date_i18n('l', strtotime($date)),
                'events' => $events,
            );
        }
        
        return $calendar_data;
    }

    /**
     * Get day calendar data.
     *
     * @return array
     */
    public function get_day_calendar_data() {
        $events = $this->get_events_for_date($this->current_date);
        
        return array(
            'date' => $this->current_date,
            'day_of_week' => date_i18n('l', strtotime($this->current_date)),
            'events' => $events,
        );
    }

    /**
     * Get list calendar data.
     *
     * @return array
     */
    public function get_list_calendar_data() {
        $calendar_data = array();
        $current_date = '';
        
        foreach ($this->events as $event) {
            $event_date = $event->get_data('start_date');
            
            if ($event_date !== $current_date) {
                $current_date = $event_date;
                $calendar_data[$current_date] = array(
                    'date' => $current_date,
                    'day_of_week' => date_i18n('l', strtotime($current_date)),
                    'events' => array(),
                );
            }
            
            $calendar_data[$current_date]['events'][] = $event;
        }
        
        return $calendar_data;
    }

    /**
     * Render calendar.
     *
     * @return string
     */
    public function render() {
        ob_start();
        
        // Include the appropriate template based on view
        switch ($this->view) {
            case 'month':
                include AQUALUXE_EVENTS_ABSPATH . 'templates/calendar-month.php';
                break;
                
            case 'week':
                include AQUALUXE_EVENTS_ABSPATH . 'templates/calendar-week.php';
                break;
                
            case 'day':
                include AQUALUXE_EVENTS_ABSPATH . 'templates/calendar-day.php';
                break;
                
            case 'list':
                include AQUALUXE_EVENTS_ABSPATH . 'templates/calendar-list.php';
                break;
        }
        
        return ob_get_clean();
    }
}