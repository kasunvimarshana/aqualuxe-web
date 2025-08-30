<?php
/**
 * Event Calendar Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Events
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Events;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Event Calendar Class
 * 
 * This class handles the event calendar functionality.
 */
class Event_Calendar {
    /**
     * Instance of this class
     *
     * @var Event_Calendar
     */
    private static $instance = null;

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Get the singleton instance
     *
     * @return Event_Calendar
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // AJAX handlers
        add_action( 'wp_ajax_aqualuxe_get_calendar_events', [ $this, 'ajax_get_calendar_events' ] );
        add_action( 'wp_ajax_nopriv_aqualuxe_get_calendar_events', [ $this, 'ajax_get_calendar_events' ] );
    }

    /**
     * Ajax get calendar events
     *
     * @return void
     */
    public function ajax_get_calendar_events() {
        check_ajax_referer( 'aqualuxe_calendar', 'nonce' );

        $start_date = isset( $_POST['start'] ) ? sanitize_text_field( $_POST['start'] ) : '';
        $end_date = isset( $_POST['end'] ) ? sanitize_text_field( $_POST['end'] ) : '';
        $category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';
        $venue_id = isset( $_POST['venue_id'] ) ? intval( $_POST['venue_id'] ) : 0;

        if ( ! $start_date || ! $end_date ) {
            wp_send_json_error( [ 'message' => __( 'Invalid date range.', 'aqualuxe' ) ] );
        }

        $events = Event::get_events( [
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'category'   => $category,
            'venue_id'   => $venue_id,
        ] );

        $calendar_events = [];

        foreach ( $events as $event ) {
            $event_data = $event->get_data();

            // Set event color based on category
            $color = $this->get_category_color( $event );

            $calendar_events[] = [
                'id'          => $event_data['id'],
                'title'       => $event_data['title'],
                'start'       => $event_data['start_date'],
                'end'         => $event_data['end_date'],
                'url'         => $event_data['permalink'],
                'color'       => $color,
                'textColor'   => '#ffffff',
                'description' => wp_trim_words( wp_strip_all_tags( $event_data['description'] ), 30 ),
                'location'    => $event_data['venue_name'] ? $event_data['venue_name'] : '',
                'allDay'      => $this->is_all_day_event( $event_data['start_date'], $event_data['end_date'] ),
            ];
        }

        wp_send_json_success( $calendar_events );
    }

    /**
     * Get category color
     *
     * @param Event $event
     * @return string
     */
    private function get_category_color( $event ) {
        $categories = $event->get_categories();
        
        if ( ! $categories || is_wp_error( $categories ) ) {
            return '#3788d8'; // Default blue
        }
        
        $category = reset( $categories );
        $color = get_term_meta( $category->term_id, '_aqualuxe_category_color', true );
        
        if ( ! $color ) {
            // Default colors based on category slug
            $colors = [
                'workshop'    => '#2ecc71', // Green
                'competition' => '#e74c3c', // Red
                'exhibition'  => '#f39c12', // Orange
                'conference'  => '#9b59b6', // Purple
                'seminar'     => '#3498db', // Blue
                'meeting'     => '#1abc9c', // Teal
                'social'      => '#e67e22', // Orange
                'other'       => '#95a5a6', // Gray
            ];
            
            $color = isset( $colors[ $category->slug ] ) ? $colors[ $category->slug ] : '#3788d8';
        }
        
        return $color;
    }

    /**
     * Check if event is all day
     *
     * @param string $start_date
     * @param string $end_date
     * @return boolean
     */
    private function is_all_day_event( $start_date, $end_date ) {
        if ( ! $start_date ) {
            return false;
        }
        
        // Check if start time is midnight (00:00:00)
        $start_time = date( 'H:i:s', strtotime( $start_date ) );
        
        if ( '00:00:00' !== $start_time ) {
            return false;
        }
        
        // If no end date, it's a single day event
        if ( ! $end_date ) {
            return true;
        }
        
        // Check if end time is 23:59:59
        $end_time = date( 'H:i:s', strtotime( $end_date ) );
        
        if ( '23:59:59' !== $end_time ) {
            return false;
        }
        
        return true;
    }

    /**
     * Render calendar
     *
     * @param array $atts
     * @return string
     */
    public function render( $atts = [] ) {
        $defaults = [
            'view'        => 'month',
            'category'    => '',
            'venue_id'    => 0,
            'height'      => 'auto',
            'events_per_page' => 10,
        ];

        $atts = wp_parse_args( $atts, $defaults );

        // Get categories for filter
        $categories = get_terms( [
            'taxonomy'   => 'event_category',
            'hide_empty' => true,
        ] );

        // Get venues for filter
        $venues = get_posts( [
            'post_type'      => 'aqualuxe_venue',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );

        // Enqueue FullCalendar scripts and styles
        wp_enqueue_style( 'fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css', [], '5.10.1' );
        wp_enqueue_script( 'fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js', [], '5.10.1', true );

        // Generate a unique ID for the calendar
        $calendar_id = 'aqualuxe-event-calendar-' . uniqid();

        // Start output buffering
        ob_start();

        ?>
        <div class="aqualuxe-event-calendar-wrapper">
            <div class="aqualuxe-event-calendar-filters">
                <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
                    <div class="aqualuxe-event-calendar-filter">
                        <label for="aqualuxe-event-calendar-filter-category"><?php esc_html_e( 'Filter by Category:', 'aqualuxe' ); ?></label>
                        <select id="aqualuxe-event-calendar-filter-category">
                            <option value=""><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
                            <?php foreach ( $categories as $category ) : ?>
                                <option value="<?php echo esc_attr( $category->slug ); ?>" <?php selected( $atts['category'], $category->slug ); ?>><?php echo esc_html( $category->name ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <?php if ( ! empty( $venues ) ) : ?>
                    <div class="aqualuxe-event-calendar-filter">
                        <label for="aqualuxe-event-calendar-filter-venue"><?php esc_html_e( 'Filter by Venue:', 'aqualuxe' ); ?></label>
                        <select id="aqualuxe-event-calendar-filter-venue">
                            <option value="0"><?php esc_html_e( 'All Venues', 'aqualuxe' ); ?></option>
                            <?php foreach ( $venues as $venue ) : ?>
                                <option value="<?php echo esc_attr( $venue->ID ); ?>" <?php selected( $atts['venue_id'], $venue->ID ); ?>><?php echo esc_html( $venue->post_title ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>

            <div id="<?php echo esc_attr( $calendar_id ); ?>" class="aqualuxe-event-calendar"></div>

            <div class="aqualuxe-event-list-wrapper">
                <h3><?php esc_html_e( 'Upcoming Events', 'aqualuxe' ); ?></h3>
                <div id="aqualuxe-event-list" class="aqualuxe-event-list">
                    <?php echo $this->render_event_list( $atts ); ?>
                </div>
            </div>
        </div>

        <script>
            jQuery(document).ready(function($) {
                var calendarEl = document.getElementById('<?php echo esc_js( $calendar_id ); ?>');
                var categoryFilter = $('#aqualuxe-event-calendar-filter-category');
                var venueFilter = $('#aqualuxe-event-calendar-filter-venue');

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: '<?php echo esc_js( $atts['view'] ); ?>',
                    height: '<?php echo esc_js( $atts['height'] ); ?>',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                    },
                    navLinks: true,
                    editable: false,
                    dayMaxEvents: true,
                    events: function(info, successCallback, failureCallback) {
                        $.ajax({
                            url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                            type: 'POST',
                            data: {
                                action: 'aqualuxe_get_calendar_events',
                                nonce: '<?php echo esc_js( wp_create_nonce( 'aqualuxe_calendar' ) ); ?>',
                                start: info.startStr,
                                end: info.endStr,
                                category: categoryFilter.val(),
                                venue_id: venueFilter.val()
                            },
                            success: function(response) {
                                if (response.success) {
                                    successCallback(response.data);
                                } else {
                                    failureCallback(response.data.message);
                                }
                            },
                            error: function() {
                                failureCallback('<?php esc_html_e( 'Error loading events.', 'aqualuxe' ); ?>');
                            }
                        });
                    },
                    eventDidMount: function(info) {
                        // Add tooltip if tippy.js is available
                        if (typeof tippy !== 'undefined') {
                            tippy(info.el, {
                                content: '<strong>' + info.event.title + '</strong><br>' +
                                         (info.event.extendedProps.location ? info.event.extendedProps.location + '<br>' : '') +
                                         info.event.extendedProps.description,
                                allowHTML: true,
                                placement: 'top',
                                arrow: true,
                                theme: 'light'
                            });
                        }
                    },
                    eventClick: function(info) {
                        if (info.event.url) {
                            info.jsEvent.preventDefault();
                            window.location.href = info.event.url;
                        }
                    }
                });

                calendar.render();

                // Handle filter changes
                categoryFilter.add(venueFilter).on('change', function() {
                    calendar.refetchEvents();
                    
                    // Update event list
                    $.ajax({
                        url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_get_event_list',
                            nonce: '<?php echo esc_js( wp_create_nonce( 'aqualuxe_event_list' ) ); ?>',
                            category: categoryFilter.val(),
                            venue_id: venueFilter.val(),
                            events_per_page: <?php echo esc_js( $atts['events_per_page'] ); ?>
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#aqualuxe-event-list').html(response.data);
                            }
                        }
                    });
                });
            });
        </script>
        <?php

        return ob_get_clean();
    }

    /**
     * Render event list
     *
     * @param array $atts
     * @return string
     */
    public function render_event_list( $atts = [] ) {
        $defaults = [
            'category'        => '',
            'venue_id'        => 0,
            'events_per_page' => 10,
            'paged'           => 1,
        ];

        $atts = wp_parse_args( $atts, $defaults );

        $events = Event::get_upcoming_events( $atts['events_per_page'] );

        if ( empty( $events ) ) {
            return '<p class="aqualuxe-no-events">' . __( 'No upcoming events found.', 'aqualuxe' ) . '</p>';
        }

        ob_start();

        ?>
        <div class="aqualuxe-event-list">
            <?php foreach ( $events as $event ) : ?>
                <?php $event_data = $event->get_data(); ?>
                <div class="aqualuxe-event-item">
                    <div class="aqualuxe-event-date">
                        <span class="aqualuxe-event-day"><?php echo esc_html( $event->get_start_date( 'd' ) ); ?></span>
                        <span class="aqualuxe-event-month"><?php echo esc_html( $event->get_start_date( 'M' ) ); ?></span>
                    </div>
                    <div class="aqualuxe-event-details">
                        <h4 class="aqualuxe-event-title">
                            <a href="<?php echo esc_url( $event_data['permalink'] ); ?>"><?php echo esc_html( $event_data['title'] ); ?></a>
                        </h4>
                        <div class="aqualuxe-event-meta">
                            <span class="aqualuxe-event-time">
                                <i class="fas fa-clock"></i>
                                <?php echo esc_html( $event->get_start_date( get_option( 'time_format' ) ) ); ?>
                            </span>
                            <?php if ( $event_data['venue_name'] ) : ?>
                                <span class="aqualuxe-event-venue">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo esc_html( $event_data['venue_name'] ); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="aqualuxe-event-excerpt">
                            <?php echo wp_trim_words( wp_strip_all_tags( $event_data['description'] ), 20 ); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php

        return ob_get_clean();
    }

    /**
     * Render mini calendar
     *
     * @param array $atts
     * @return string
     */
    public function render_mini_calendar( $atts = [] ) {
        $defaults = [
            'category' => '',
            'venue_id' => 0,
        ];

        $atts = wp_parse_args( $atts, $defaults );

        // Generate a unique ID for the calendar
        $calendar_id = 'aqualuxe-mini-calendar-' . uniqid();

        // Start output buffering
        ob_start();

        ?>
        <div class="aqualuxe-mini-calendar-wrapper">
            <div id="<?php echo esc_attr( $calendar_id ); ?>" class="aqualuxe-mini-calendar"></div>
        </div>

        <script>
            jQuery(document).ready(function($) {
                var calendarEl = document.getElementById('<?php echo esc_js( $calendar_id ); ?>');

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next',
                        center: 'title',
                        right: ''
                    },
                    height: 'auto',
                    navLinks: true,
                    editable: false,
                    dayMaxEvents: 1,
                    events: function(info, successCallback, failureCallback) {
                        $.ajax({
                            url: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
                            type: 'POST',
                            data: {
                                action: 'aqualuxe_get_calendar_events',
                                nonce: '<?php echo esc_js( wp_create_nonce( 'aqualuxe_calendar' ) ); ?>',
                                start: info.startStr,
                                end: info.endStr,
                                category: '<?php echo esc_js( $atts['category'] ); ?>',
                                venue_id: <?php echo esc_js( $atts['venue_id'] ); ?>
                            },
                            success: function(response) {
                                if (response.success) {
                                    successCallback(response.data);
                                } else {
                                    failureCallback(response.data.message);
                                }
                            },
                            error: function() {
                                failureCallback('<?php esc_html_e( 'Error loading events.', 'aqualuxe' ); ?>');
                            }
                        });
                    },
                    eventDidMount: function(info) {
                        // Add tooltip if tippy.js is available
                        if (typeof tippy !== 'undefined') {
                            tippy(info.el, {
                                content: info.event.title,
                                placement: 'top',
                                arrow: true,
                                theme: 'light'
                            });
                        }
                    },
                    eventClick: function(info) {
                        if (info.event.url) {
                            info.jsEvent.preventDefault();
                            window.location.href = info.event.url;
                        }
                    }
                });

                calendar.render();
            });
        </script>
        <?php

        return ob_get_clean();
    }

    /**
     * Get events for a specific date
     *
     * @param string $date
     * @param array $args
     * @return array
     */
    public function get_events_for_date( $date, $args = [] ) {
        $defaults = [
            'category' => '',
            'venue_id' => 0,
            'limit'    => -1,
        ];

        $args = wp_parse_args( $args, $defaults );

        // Format date to Y-m-d
        $date = date( 'Y-m-d', strtotime( $date ) );

        // Get start and end of day
        $start_date = $date . ' 00:00:00';
        $end_date = $date . ' 23:59:59';

        return Event::get_events( [
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'category'   => $args['category'],
            'venue_id'   => $args['venue_id'],
            'limit'      => $args['limit'],
        ] );
    }

    /**
     * Get events for a specific month
     *
     * @param string $month
     * @param array $args
     * @return array
     */
    public function get_events_for_month( $month, $args = [] ) {
        $defaults = [
            'category' => '',
            'venue_id' => 0,
            'limit'    => -1,
        ];

        $args = wp_parse_args( $args, $defaults );

        // Format month to Y-m
        $month = date( 'Y-m', strtotime( $month ) );

        // Get start and end of month
        $start_date = $month . '-01 00:00:00';
        $end_date = date( 'Y-m-t 23:59:59', strtotime( $start_date ) );

        return Event::get_events( [
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'category'   => $args['category'],
            'venue_id'   => $args['venue_id'],
            'limit'      => $args['limit'],
        ] );
    }

    /**
     * Get events for a specific week
     *
     * @param string $week
     * @param array $args
     * @return array
     */
    public function get_events_for_week( $week, $args = [] ) {
        $defaults = [
            'category' => '',
            'venue_id' => 0,
            'limit'    => -1,
        ];

        $args = wp_parse_args( $args, $defaults );

        // Get start and end of week
        $start_date = date( 'Y-m-d 00:00:00', strtotime( $week ) );
        $end_date = date( 'Y-m-d 23:59:59', strtotime( '+6 days', strtotime( $start_date ) ) );

        return Event::get_events( [
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'category'   => $args['category'],
            'venue_id'   => $args['venue_id'],
            'limit'      => $args['limit'],
        ] );
    }
}

// Initialize the calendar class
Event_Calendar::get_instance();