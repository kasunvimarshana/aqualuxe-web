<?php
/**
 * Calendar Class
 *
 * @package AquaLuxe\Modules\Events
 */

namespace AquaLuxe\Modules\Events;

/**
 * Calendar Class
 */
class Calendar {
    /**
     * Calendar settings
     *
     * @var array
     */
    private $settings = [];

    /**
     * Constructor
     */
    public function __construct() {
        // Get module instance
        $theme = \AquaLuxe\Theme::get_instance();
        $module = $theme->get_active_modules()['events'] ?? null;
        
        if ($module) {
            $this->settings = [
                'first_day' => $module->get_setting('calendar_first_day', 0),
                'date_format' => $module->get_setting('date_format', 'F j, Y'),
                'time_format' => $module->get_setting('time_format', 'g:i a'),
            ];
        } else {
            $this->settings = [
                'first_day' => 0,
                'date_format' => 'F j, Y',
                'time_format' => 'g:i a',
            ];
        }
    }

    /**
     * Render calendar
     *
     * @param array $args
     */
    public function render($args = []) {
        $defaults = [
            'id' => 'aqualuxe-calendar-' . uniqid(),
            'class' => 'aqualuxe-calendar',
            'show_title' => true,
            'title' => __('Event Calendar', 'aqualuxe'),
            'months' => 1,
            'start_date' => date('Y-m-01'),
            'category' => '',
            'featured' => false,
            'past_events' => false,
            'compact' => false,
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        // Get events
        $events = $this->get_events_for_calendar($args);
        
        // Prepare calendar data
        $calendar_data = [
            'events' => $this->prepare_events_for_js($events),
            'firstDay' => $this->settings['first_day'],
            'dateFormat' => $this->settings['date_format'],
            'timeFormat' => $this->settings['time_format'],
            'months' => $args['months'],
            'startDate' => $args['start_date'],
            'compact' => $args['compact'],
        ];
        
        // Output calendar
        ?>
        <div id="<?php echo esc_attr($args['id']); ?>" class="<?php echo esc_attr($args['class']); ?>" data-calendar="<?php echo esc_attr(json_encode($calendar_data)); ?>">
            <?php if ($args['show_title']) : ?>
                <h3 class="aqualuxe-calendar__title"><?php echo esc_html($args['title']); ?></h3>
            <?php endif; ?>
            
            <div class="aqualuxe-calendar__container"></div>
            
            <div class="aqualuxe-calendar__legend">
                <div class="aqualuxe-calendar__legend-item">
                    <span class="aqualuxe-calendar__legend-color aqualuxe-calendar__legend-color--event"></span>
                    <span class="aqualuxe-calendar__legend-label"><?php esc_html_e('Event', 'aqualuxe'); ?></span>
                </div>
                <div class="aqualuxe-calendar__legend-item">
                    <span class="aqualuxe-calendar__legend-color aqualuxe-calendar__legend-color--featured"></span>
                    <span class="aqualuxe-calendar__legend-label"><?php esc_html_e('Featured Event', 'aqualuxe'); ?></span>
                </div>
                <div class="aqualuxe-calendar__legend-item">
                    <span class="aqualuxe-calendar__legend-color aqualuxe-calendar__legend-color--today"></span>
                    <span class="aqualuxe-calendar__legend-label"><?php esc_html_e('Today', 'aqualuxe'); ?></span>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Get events for calendar
     *
     * @param array $args
     * @return array
     */
    private function get_events_for_calendar($args) {
        // Calculate date range
        $start_date = date('Y-m-01', strtotime($args['start_date']));
        $end_date = date('Y-m-t', strtotime($args['start_date'] . ' +' . ($args['months'] - 1) . ' months'));
        
        // Prepare query args
        $query_args = [
            'post_type' => 'aqualuxe_event',
            'posts_per_page' => -1,
            'meta_key' => '_aqualuxe_event_start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'relation' => 'OR',
                    [
                        'key' => '_aqualuxe_event_start_date',
                        'value' => [$start_date, $end_date],
                        'compare' => 'BETWEEN',
                        'type' => 'DATE',
                    ],
                    [
                        'key' => '_aqualuxe_event_end_date',
                        'value' => [$start_date, $end_date],
                        'compare' => 'BETWEEN',
                        'type' => 'DATE',
                    ],
                    [
                        'relation' => 'AND',
                        [
                            'key' => '_aqualuxe_event_start_date',
                            'value' => $start_date,
                            'compare' => '<=',
                            'type' => 'DATE',
                        ],
                        [
                            'key' => '_aqualuxe_event_end_date',
                            'value' => $end_date,
                            'compare' => '>=',
                            'type' => 'DATE',
                        ],
                    ],
                ],
            ],
        ];
        
        // Add category filter
        if (!empty($args['category'])) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'aqualuxe_event_category',
                'field' => 'slug',
                'terms' => $args['category'],
            ];
        }
        
        // Add featured filter
        if ($args['featured']) {
            $query_args['meta_query'][] = [
                'key' => '_aqualuxe_event_featured',
                'value' => '1',
                'compare' => '=',
            ];
        }
        
        // Add past events filter
        if (!$args['past_events']) {
            $query_args['meta_query'][] = [
                'key' => '_aqualuxe_event_end_date',
                'value' => date('Y-m-d'),
                'compare' => '>=',
                'type' => 'DATE',
            ];
        }
        
        // Get events
        $query = new \WP_Query($query_args);
        $events = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $events[] = new Event(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        return $events;
    }

    /**
     * Prepare events for JS
     *
     * @param array $events
     * @return array
     */
    private function prepare_events_for_js($events) {
        $prepared_events = [];
        
        foreach ($events as $event) {
            $prepared_events[] = [
                'id' => $event->get_id(),
                'title' => $event->get_title(),
                'start' => $event->get_start_date(),
                'end' => $event->get_end_date(),
                'url' => $event->get_permalink(),
                'featured' => $event->get_featured(),
                'image' => $event->get_image_url('thumbnail'),
                'excerpt' => $event->get_excerpt(),
                'venue' => $event->get_venue_data(),
                'startTime' => $event->get_start_time(),
                'endTime' => $event->get_end_time(),
                'formattedStart' => $event->get_formatted_start_date(),
                'formattedEnd' => $event->get_formatted_end_date(),
                'formattedStartTime' => $event->get_formatted_start_time(),
                'formattedEndTime' => $event->get_formatted_end_time(),
            ];
        }
        
        return $prepared_events;
    }

    /**
     * Get events by date
     *
     * @param string $date
     * @param array $args
     * @return array
     */
    public function get_events_by_date($date, $args = []) {
        $defaults = [
            'category' => '',
            'featured' => false,
            'limit' => -1,
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        // Prepare query args
        $query_args = [
            'post_type' => 'aqualuxe_event',
            'posts_per_page' => $args['limit'],
            'meta_key' => '_aqualuxe_event_start_time',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => '_aqualuxe_event_start_date',
                    'value' => $date,
                    'compare' => '=',
                    'type' => 'DATE',
                ],
                [
                    'relation' => 'AND',
                    [
                        'key' => '_aqualuxe_event_start_date',
                        'value' => $date,
                        'compare' => '<=',
                        'type' => 'DATE',
                    ],
                    [
                        'key' => '_aqualuxe_event_end_date',
                        'value' => $date,
                        'compare' => '>=',
                        'type' => 'DATE',
                    ],
                ],
            ],
        ];
        
        // Add category filter
        if (!empty($args['category'])) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'aqualuxe_event_category',
                'field' => 'slug',
                'terms' => $args['category'],
            ];
        }
        
        // Add featured filter
        if ($args['featured']) {
            $query_args['meta_query'][] = [
                'key' => '_aqualuxe_event_featured',
                'value' => '1',
                'compare' => '=',
            ];
        }
        
        // Get events
        $query = new \WP_Query($query_args);
        $events = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $events[] = new Event(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        return $events;
    }

    /**
     * Get events by month
     *
     * @param string $year_month
     * @param array $args
     * @return array
     */
    public function get_events_by_month($year_month, $args = []) {
        $defaults = [
            'category' => '',
            'featured' => false,
            'limit' => -1,
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        // Calculate date range
        $start_date = date('Y-m-01', strtotime($year_month));
        $end_date = date('Y-m-t', strtotime($year_month));
        
        // Prepare query args
        $query_args = [
            'post_type' => 'aqualuxe_event',
            'posts_per_page' => $args['limit'],
            'meta_key' => '_aqualuxe_event_start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => '_aqualuxe_event_start_date',
                    'value' => [$start_date, $end_date],
                    'compare' => 'BETWEEN',
                    'type' => 'DATE',
                ],
                [
                    'key' => '_aqualuxe_event_end_date',
                    'value' => [$start_date, $end_date],
                    'compare' => 'BETWEEN',
                    'type' => 'DATE',
                ],
                [
                    'relation' => 'AND',
                    [
                        'key' => '_aqualuxe_event_start_date',
                        'value' => $start_date,
                        'compare' => '<=',
                        'type' => 'DATE',
                    ],
                    [
                        'key' => '_aqualuxe_event_end_date',
                        'value' => $end_date,
                        'compare' => '>=',
                        'type' => 'DATE',
                    ],
                ],
            ],
        ];
        
        // Add category filter
        if (!empty($args['category'])) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'aqualuxe_event_category',
                'field' => 'slug',
                'terms' => $args['category'],
            ];
        }
        
        // Add featured filter
        if ($args['featured']) {
            $query_args['meta_query'][] = [
                'key' => '_aqualuxe_event_featured',
                'value' => '1',
                'compare' => '=',
            ];
        }
        
        // Get events
        $query = new \WP_Query($query_args);
        $events = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $events[] = new Event(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        return $events;
    }

    /**
     * Get upcoming events
     *
     * @param array $args
     * @return array
     */
    public function get_upcoming_events($args = []) {
        $defaults = [
            'category' => '',
            'featured' => false,
            'limit' => 5,
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        // Prepare query args
        $query_args = [
            'post_type' => 'aqualuxe_event',
            'posts_per_page' => $args['limit'],
            'meta_key' => '_aqualuxe_event_start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => [
                [
                    'key' => '_aqualuxe_event_start_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE',
                ],
            ],
        ];
        
        // Add category filter
        if (!empty($args['category'])) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'aqualuxe_event_category',
                'field' => 'slug',
                'terms' => $args['category'],
            ];
        }
        
        // Add featured filter
        if ($args['featured']) {
            $query_args['meta_query'][] = [
                'key' => '_aqualuxe_event_featured',
                'value' => '1',
                'compare' => '=',
            ];
        }
        
        // Get events
        $query = new \WP_Query($query_args);
        $events = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $events[] = new Event(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        return $events;
    }

    /**
     * Get past events
     *
     * @param array $args
     * @return array
     */
    public function get_past_events($args = []) {
        $defaults = [
            'category' => '',
            'featured' => false,
            'limit' => 5,
        ];
        
        $args = wp_parse_args($args, $defaults);
        
        // Prepare query args
        $query_args = [
            'post_type' => 'aqualuxe_event',
            'posts_per_page' => $args['limit'],
            'meta_key' => '_aqualuxe_event_end_date',
            'orderby' => 'meta_value',
            'order' => 'DESC',
            'meta_query' => [
                [
                    'key' => '_aqualuxe_event_end_date',
                    'value' => date('Y-m-d'),
                    'compare' => '<',
                    'type' => 'DATE',
                ],
            ],
        ];
        
        // Add category filter
        if (!empty($args['category'])) {
            $query_args['tax_query'][] = [
                'taxonomy' => 'aqualuxe_event_category',
                'field' => 'slug',
                'terms' => $args['category'],
            ];
        }
        
        // Add featured filter
        if ($args['featured']) {
            $query_args['meta_query'][] = [
                'key' => '_aqualuxe_event_featured',
                'value' => '1',
                'compare' => '=',
            ];
        }
        
        // Get events
        $query = new \WP_Query($query_args);
        $events = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $events[] = new Event(get_the_ID());
            }
            wp_reset_postdata();
        }
        
        return $events;
    }

    /**
     * Get featured events
     *
     * @param array $args
     * @return array
     */
    public function get_featured_events($args = []) {
        $defaults = [
            'category' => '',
            'limit' => 5,
            'past_events' => false,
        ];
        
        $args = wp_parse_args($args, $defaults);
        $args['featured'] = true;
        
        if ($args['past_events']) {
            return $this->get_past_events($args);
        } else {
            return $this->get_upcoming_events($args);
        }
    }

    /**
     * Set setting
     *
     * @param string $key
     * @param mixed $value
     */
    public function set_setting($key, $value) {
        $this->settings[$key] = $value;
    }

    /**
     * Get setting
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get_setting($key, $default = null) {
        return isset($this->settings[$key]) ? $this->settings[$key] : $default;
    }
}