<?php
/**
 * Events Module
 * 
 * Handles events and ticketing functionality
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Events_Module {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', [$this, 'init']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_aqualuxe_register_event', [$this, 'ajax_register_event']);
        add_action('wp_ajax_nopriv_aqualuxe_register_event', [$this, 'ajax_register_event']);
        add_shortcode('aqualuxe_events', [$this, 'events_shortcode']);
        add_shortcode('aqualuxe_events_calendar', [$this, 'calendar_shortcode']);
    }
    
    /**
     * Initialize module
     */
    public function init() {
        $this->register_post_types();
        $this->register_taxonomies();
    }
    
    /**
     * Register custom post types
     */
    private function register_post_types() {
        // Events
        register_post_type('aqualuxe_event', [
            'labels' => [
                'name' => esc_html__('Events', 'aqualuxe'),
                'singular_name' => esc_html__('Event', 'aqualuxe'),
                'add_new' => esc_html__('Add New Event', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Event', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Event', 'aqualuxe'),
                'new_item' => esc_html__('New Event', 'aqualuxe'),
                'view_item' => esc_html__('View Event', 'aqualuxe'),
                'search_items' => esc_html__('Search Events', 'aqualuxe'),
                'not_found' => esc_html__('No events found', 'aqualuxe'),
                'not_found_in_trash' => esc_html__('No events found in trash', 'aqualuxe'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'events'],
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 24,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'comments']
        ]);
        
        // Event registrations
        register_post_type('aqualuxe_event_reg', [
            'labels' => [
                'name' => esc_html__('Event Registrations', 'aqualuxe'),
                'singular_name' => esc_html__('Registration', 'aqualuxe'),
                'view_item' => esc_html__('View Registration', 'aqualuxe'),
                'search_items' => esc_html__('Search Registrations', 'aqualuxe'),
                'not_found' => esc_html__('No registrations found', 'aqualuxe'),
            ],
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=aqualuxe_event',
            'query_var' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'supports' => ['title', 'custom-fields']
        ]);
    }
    
    /**
     * Register taxonomies
     */
    private function register_taxonomies() {
        // Event categories
        register_taxonomy('event_category', 'aqualuxe_event', [
            'labels' => [
                'name' => esc_html__('Event Categories', 'aqualuxe'),
                'singular_name' => esc_html__('Event Category', 'aqualuxe'),
                'search_items' => esc_html__('Search Event Categories', 'aqualuxe'),
                'all_items' => esc_html__('All Event Categories', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Event Category', 'aqualuxe'),
                'update_item' => esc_html__('Update Event Category', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Event Category', 'aqualuxe'),
                'new_item_name' => esc_html__('New Event Category Name', 'aqualuxe'),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'event-category'],
        ]);
        
        // Event tags
        register_taxonomy('event_tag', 'aqualuxe_event', [
            'labels' => [
                'name' => esc_html__('Event Tags', 'aqualuxe'),
                'singular_name' => esc_html__('Event Tag', 'aqualuxe'),
                'search_items' => esc_html__('Search Event Tags', 'aqualuxe'),
                'all_items' => esc_html__('All Event Tags', 'aqualuxe'),
                'edit_item' => esc_html__('Edit Event Tag', 'aqualuxe'),
                'update_item' => esc_html__('Update Event Tag', 'aqualuxe'),
                'add_new_item' => esc_html__('Add New Event Tag', 'aqualuxe'),
                'new_item_name' => esc_html__('New Event Tag Name', 'aqualuxe'),
            ],
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_rest' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'event-tag'],
        ]);
    }
    
    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        if (is_singular('aqualuxe_event') || is_post_type_archive('aqualuxe_event')) {
            wp_enqueue_style(
                'aqualuxe-events',
                AQUALUXE_ASSETS_URI . '/css/modules/events.css',
                ['aqualuxe-style'],
                AQUALUXE_VERSION
            );
            
            wp_enqueue_script(
                'aqualuxe-events',
                AQUALUXE_ASSETS_URI . '/js/modules/events.js',
                ['jquery'],
                AQUALUXE_VERSION,
                true
            );
            
            wp_localize_script('aqualuxe-events', 'aqualuxe_events_vars', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_events_nonce'),
                'strings' => [
                    'registration_success' => esc_html__('Your registration has been submitted successfully!', 'aqualuxe'),
                    'registration_error' => esc_html__('There was an error submitting your registration. Please try again.', 'aqualuxe'),
                    'event_full' => esc_html__('Sorry, this event is fully booked.', 'aqualuxe'),
                ]
            ]);
        }
    }
    
    /**
     * Events shortcode
     */
    public function events_shortcode($atts) {
        $atts = shortcode_atts([
            'limit' => 6,
            'category' => '',
            'upcoming' => true,
            'featured' => false,
            'columns' => 3,
            'show_excerpt' => true,
            'show_date' => true,
            'show_location' => true,
            'show_price' => true
        ], $atts, 'aqualuxe_events');
        
        $args = [
            'post_type' => 'aqualuxe_event',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish',
            'orderby' => 'meta_value',
            'meta_key' => 'event_start_date',
            'order' => 'ASC'
        ];
        
        if ($atts['upcoming']) {
            $args['meta_query'][] = [
                'key' => 'event_start_date',
                'value' => date('Y-m-d'),
                'compare' => '>='
            ];
        }
        
        if ($atts['featured']) {
            $args['meta_query'][] = [
                'key' => 'event_featured',
                'value' => '1',
                'compare' => '='
            ];
        }
        
        if ($atts['category']) {
            $args['tax_query'][] = [
                'taxonomy' => 'event_category',
                'field' => 'slug',
                'terms' => explode(',', $atts['category'])
            ];
        }
        
        $events = new WP_Query($args);
        
        if (!$events->have_posts()) {
            return '<p>' . esc_html__('No events found.', 'aqualuxe') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="aqualuxe-events-grid grid grid-cols-1 md:grid-cols-<?php echo esc_attr($atts['columns']); ?> gap-8">
            <?php while ($events->have_posts()) : $events->the_post(); ?>
                <?php $this->render_event_card($atts); ?>
            <?php endwhile; ?>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
    
    /**
     * Calendar shortcode
     */
    public function calendar_shortcode($atts) {
        $atts = shortcode_atts([
            'month' => date('m'),
            'year' => date('Y')
        ], $atts, 'aqualuxe_events_calendar');
        
        $events = $this->get_events_for_month($atts['year'], $atts['month']);
        
        ob_start();
        ?>
        <div class="events-calendar">
            <div class="calendar-header flex justify-between items-center mb-6">
                <button class="prev-month btn btn-outline-primary" data-month="<?php echo esc_attr($atts['month']); ?>" data-year="<?php echo esc_attr($atts['year']); ?>">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <?php echo date('F Y', mktime(0, 0, 0, $atts['month'], 1, $atts['year'])); ?>
                </h3>
                <button class="next-month btn btn-outline-primary" data-month="<?php echo esc_attr($atts['month']); ?>" data-year="<?php echo esc_attr($atts['year']); ?>">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <div class="calendar-grid grid grid-cols-7 gap-1 bg-gray-100 dark:bg-gray-800 p-4 rounded-lg">
                <!-- Day headers -->
                <div class="calendar-day-header text-center p-2 font-semibold text-gray-700 dark:text-gray-300">Sun</div>
                <div class="calendar-day-header text-center p-2 font-semibold text-gray-700 dark:text-gray-300">Mon</div>
                <div class="calendar-day-header text-center p-2 font-semibold text-gray-700 dark:text-gray-300">Tue</div>
                <div class="calendar-day-header text-center p-2 font-semibold text-gray-700 dark:text-gray-300">Wed</div>
                <div class="calendar-day-header text-center p-2 font-semibold text-gray-700 dark:text-gray-300">Thu</div>
                <div class="calendar-day-header text-center p-2 font-semibold text-gray-700 dark:text-gray-300">Fri</div>
                <div class="calendar-day-header text-center p-2 font-semibold text-gray-700 dark:text-gray-300">Sat</div>
                
                <?php echo $this->render_calendar_days($atts['year'], $atts['month'], $events); ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Render event card
     */
    private function render_event_card($atts) {
        $event_id = get_the_ID();
        $start_date = get_post_meta($event_id, 'event_start_date', true);
        $end_date = get_post_meta($event_id, 'event_end_date', true);
        $start_time = get_post_meta($event_id, 'event_start_time', true);
        $location = get_post_meta($event_id, 'event_location', true);
        $price = get_post_meta($event_id, 'event_price', true);
        $capacity = get_post_meta($event_id, 'event_capacity', true);
        $registered = $this->get_registration_count($event_id);
        ?>
        <div class="event-card bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <?php if (has_post_thumbnail()) : ?>
                <div class="event-image relative">
                    <?php the_post_thumbnail('aqualuxe-featured', ['class' => 'w-full h-48 object-cover']); ?>
                    <?php if ($this->is_event_featured($event_id)) : ?>
                        <div class="event-badge absolute top-3 left-3 bg-accent-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            <?php esc_html_e('Featured', 'aqualuxe'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="event-content p-6">
                <?php if ($atts['show_date'] && $start_date) : ?>
                    <div class="event-date flex items-center text-primary-600 dark:text-primary-400 text-sm font-medium mb-3">
                        <i class="fas fa-calendar mr-2"></i>
                        <?php echo date('M j, Y', strtotime($start_date)); ?>
                        <?php if ($start_time) : ?>
                            <?php echo ' at ' . date('g:i A', strtotime($start_time)); ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <h3 class="event-title text-xl font-semibold text-gray-900 dark:text-white mb-3">
                    <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors">
                        <?php the_title(); ?>
                    </a>
                </h3>
                
                <?php if ($atts['show_location'] && $location) : ?>
                    <div class="event-location flex items-center text-gray-600 dark:text-gray-400 text-sm mb-3">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        <?php echo esc_html($location); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($atts['show_excerpt'] && has_excerpt()) : ?>
                    <div class="event-excerpt text-gray-600 dark:text-gray-400 mb-4">
                        <?php the_excerpt(); ?>
                    </div>
                <?php endif; ?>
                
                <div class="event-meta flex justify-between items-center mb-4">
                    <?php if ($atts['show_price'] && $price) : ?>
                        <div class="event-price text-lg font-semibold text-gray-900 dark:text-white">
                            <?php echo esc_html($price); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($capacity) : ?>
                        <div class="event-capacity text-sm text-gray-600 dark:text-gray-400">
                            <?php printf(esc_html__('%1$d / %2$d registered', 'aqualuxe'), $registered, $capacity); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="event-actions flex gap-3">
                    <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary flex-1 text-center">
                        <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                    </a>
                    
                    <?php if ($this->can_register($event_id)) : ?>
                        <button class="btn btn-primary register-event-btn" 
                                data-event-id="<?php echo $event_id; ?>" 
                                data-event-title="<?php echo esc_attr(get_the_title()); ?>">
                            <?php esc_html_e('Register', 'aqualuxe'); ?>
                        </button>
                    <?php else : ?>
                        <button class="btn btn-secondary disabled" disabled>
                            <?php esc_html_e('Full', 'aqualuxe'); ?>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * AJAX register for event
     */
    public function ajax_register_event() {
        check_ajax_referer('aqualuxe_events_nonce', 'nonce');
        
        $event_id = intval($_POST['event_id']);
        $attendee_name = sanitize_text_field($_POST['attendee_name']);
        $attendee_email = sanitize_email($_POST['attendee_email']);
        $attendee_phone = sanitize_text_field($_POST['attendee_phone']);
        $tickets = intval($_POST['tickets']) ?: 1;
        
        // Validate required fields
        if (!$event_id || !$attendee_name || !$attendee_email) {
            wp_send_json_error('Required fields are missing');
        }
        
        // Check capacity
        if (!$this->can_register($event_id, $tickets)) {
            wp_send_json_error('Event is full or insufficient spots available');
        }
        
        // Create registration
        $registration_id = wp_insert_post([
            'post_type' => 'aqualuxe_event_reg',
            'post_title' => sprintf('Registration for %s - %s', get_the_title($event_id), $attendee_name),
            'post_status' => 'publish',
            'meta_input' => [
                'event_id' => $event_id,
                'attendee_name' => $attendee_name,
                'attendee_email' => $attendee_email,
                'attendee_phone' => $attendee_phone,
                'tickets' => $tickets,
                'registration_date' => current_time('mysql'),
                'registration_status' => 'confirmed'
            ]
        ]);
        
        if ($registration_id && !is_wp_error($registration_id)) {
            // Send confirmation email
            $this->send_registration_confirmation($registration_id);
            wp_send_json_success(['message' => 'Registration successful!']);
        } else {
            wp_send_json_error('Failed to create registration');
        }
    }
    
    /**
     * Get events for specific month
     */
    private function get_events_for_month($year, $month) {
        $start_date = sprintf('%04d-%02d-01', $year, $month);
        $end_date = sprintf('%04d-%02d-%02d', $year, $month, date('t', mktime(0, 0, 0, $month, 1, $year)));
        
        $events = get_posts([
            'post_type' => 'aqualuxe_event',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => 'event_start_date',
                    'value' => [$start_date, $end_date],
                    'compare' => 'BETWEEN',
                    'type' => 'DATE'
                ]
            ]
        ]);
        
        $events_by_date = [];
        foreach ($events as $event) {
            $date = get_post_meta($event->ID, 'event_start_date', true);
            if (!isset($events_by_date[$date])) {
                $events_by_date[$date] = [];
            }
            $events_by_date[$date][] = $event;
        }
        
        return $events_by_date;
    }
    
    /**
     * Render calendar days
     */
    private function render_calendar_days($year, $month, $events) {
        $first_day = mktime(0, 0, 0, $month, 1, $year);
        $days_in_month = date('t', $first_day);
        $day_of_week = date('w', $first_day);
        
        $output = '';
        
        // Empty cells for days before month starts
        for ($i = 0; $i < $day_of_week; $i++) {
            $output .= '<div class="calendar-day empty"></div>';
        }
        
        // Days of the month
        for ($day = 1; $day <= $days_in_month; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $is_today = ($date === date('Y-m-d'));
            $has_events = isset($events[$date]);
            
            $classes = ['calendar-day', 'text-center', 'p-2', 'min-h-16', 'bg-white', 'dark:bg-gray-700', 'rounded'];
            if ($is_today) $classes[] = 'bg-primary-100 dark:bg-primary-900';
            if ($has_events) $classes[] = 'has-events';
            
            $output .= '<div class="' . implode(' ', $classes) . '">';
            $output .= '<div class="day-number font-semibold text-gray-900 dark:text-white">' . $day . '</div>';
            
            if ($has_events) {
                $output .= '<div class="events-list">';
                foreach ($events[$date] as $event) {
                    $output .= '<div class="event-item text-xs text-primary-600 dark:text-primary-400 truncate">';
                    $output .= esc_html($event->post_title);
                    $output .= '</div>';
                }
                $output .= '</div>';
            }
            
            $output .= '</div>';
        }
        
        return $output;
    }
    
    /**
     * Check if user can register for event
     */
    private function can_register($event_id, $tickets = 1) {
        $capacity = get_post_meta($event_id, 'event_capacity', true);
        if (!$capacity) return true; // No capacity limit
        
        $registered = $this->get_registration_count($event_id);
        return ($registered + $tickets) <= $capacity;
    }
    
    /**
     * Get registration count for event
     */
    private function get_registration_count($event_id) {
        global $wpdb;
        
        $count = $wpdb->get_var($wpdb->prepare("
            SELECT SUM(CAST(pm.meta_value AS UNSIGNED))
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
            INNER JOIN {$wpdb->postmeta} pm2 ON p.ID = pm2.post_id
            WHERE p.post_type = 'aqualuxe_event_reg'
            AND p.post_status = 'publish'
            AND pm.meta_key = 'tickets'
            AND pm2.meta_key = 'event_id'
            AND pm2.meta_value = %d
        ", $event_id));
        
        return intval($count);
    }
    
    /**
     * Check if event is featured
     */
    private function is_event_featured($event_id) {
        return get_post_meta($event_id, 'event_featured', true) === '1';
    }
    
    /**
     * Send registration confirmation email
     */
    private function send_registration_confirmation($registration_id) {
        $attendee_email = get_post_meta($registration_id, 'attendee_email', true);
        $attendee_name = get_post_meta($registration_id, 'attendee_name', true);
        $event_id = get_post_meta($registration_id, 'event_id', true);
        $event_title = get_the_title($event_id);
        $tickets = get_post_meta($registration_id, 'tickets', true);
        
        $subject = 'Event Registration Confirmation - ' . $event_title;
        $message = "
            Dear {$attendee_name},
            
            Thank you for registering for {$event_title}.
            
            Registration Details:
            - Event: {$event_title}
            - Tickets: {$tickets}
            - Registration ID: #{$registration_id}
            
            We look forward to seeing you at the event!
            
            Best regards,
            AquaLuxe Team
        ";
        
        wp_mail($attendee_email, $subject, $message);
    }
}