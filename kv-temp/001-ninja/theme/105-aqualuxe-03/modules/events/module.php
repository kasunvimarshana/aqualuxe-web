<?php
/**
 * Events & Ticketing Module
 *
 * Handles events, workshops, tours, and ticketing functionality
 *
 * @package AquaLuxe\Modules\Events
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Events Module Class
 */
class AquaLuxe_Events_Module {
    
    /**
     * Initialize the module
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Register AJAX handlers
        aqualuxe_secure_ajax_handler('book_event', array($this, 'ajax_book_event'), false, true);
        aqualuxe_secure_ajax_handler('cancel_event_booking', array($this, 'ajax_cancel_event_booking'), true, true);
        aqualuxe_secure_ajax_handler('get_event_calendar', array($this, 'ajax_get_event_calendar'), false, false);
        
        // Admin hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('add_meta_boxes', array($this, 'add_event_meta_boxes'));
        add_action('save_post', array($this, 'save_event_meta'));
        
        // Email notifications
        add_action('aqualuxe_event_booked', array($this, 'send_event_confirmation'), 10, 1);
        add_action('aqualuxe_event_cancelled', array($this, 'send_cancellation_email'), 10, 1);
        add_action('aqualuxe_event_reminder', array($this, 'send_event_reminder'), 10, 1);
        
        // Shortcodes
        add_shortcode('aqualuxe_events_calendar', array($this, 'events_calendar_shortcode'));
        add_shortcode('aqualuxe_event_booking', array($this, 'event_booking_shortcode'));
        add_shortcode('aqualuxe_upcoming_events', array($this, 'upcoming_events_shortcode'));
        
        // Schedule hooks
        add_action('aqualuxe_send_event_reminders', array($this, 'send_event_reminders'));
        add_action('aqualuxe_cleanup_past_events', array($this, 'cleanup_past_events'));
    }

    /**
     * Initialize module
     */
    public function init() {
        $this->register_post_types();
        $this->register_taxonomies();
        $this->create_tables();
        $this->schedule_tasks();
    }

    /**
     * Register post types
     */
    private function register_post_types() {
        register_post_type('aqualuxe_event', array(
            'labels' => array(
                'name' => __('Events', 'aqualuxe'),
                'singular_name' => __('Event', 'aqualuxe'),
                'menu_name' => __('Events', 'aqualuxe'),
                'add_new' => __('Add New Event', 'aqualuxe'),
                'add_new_item' => __('Add New Event', 'aqualuxe'),
                'edit_item' => __('Edit Event', 'aqualuxe'),
                'new_item' => __('New Event', 'aqualuxe'),
                'view_item' => __('View Event', 'aqualuxe'),
                'search_items' => __('Search Events', 'aqualuxe'),
                'not_found' => __('No events found', 'aqualuxe'),
                'not_found_in_trash' => __('No events found in trash', 'aqualuxe'),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'events'),
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'menu_icon' => 'dashicons-calendar-alt',
            'show_in_rest' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
        ));
    }

    /**
     * Register taxonomies
     */
    private function register_taxonomies() {
        register_taxonomy('event_category', 'aqualuxe_event', array(
            'labels' => array(
                'name' => __('Event Categories', 'aqualuxe'),
                'singular_name' => __('Event Category', 'aqualuxe'),
                'menu_name' => __('Categories', 'aqualuxe'),
            ),
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'event-category'),
        ));

        register_taxonomy('event_tag', 'aqualuxe_event', array(
            'labels' => array(
                'name' => __('Event Tags', 'aqualuxe'),
                'singular_name' => __('Event Tag', 'aqualuxe'),
                'menu_name' => __('Tags', 'aqualuxe'),
            ),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'event-tag'),
        ));
    }

    /**
     * Create database tables
     */
    private function create_tables() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'aqualuxe_event_bookings';
        
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            event_id bigint(20) NOT NULL,
            user_id bigint(20) DEFAULT NULL,
            customer_name varchar(100) NOT NULL,
            customer_email varchar(100) NOT NULL,
            customer_phone varchar(20) DEFAULT '',
            tickets_quantity int(11) DEFAULT 1,
            ticket_type varchar(50) DEFAULT 'general',
            total_amount decimal(10,2) DEFAULT 0.00,
            booking_status varchar(20) DEFAULT 'confirmed',
            payment_status varchar(20) DEFAULT 'pending',
            payment_method varchar(50) DEFAULT '',
            transaction_id varchar(100) DEFAULT '',
            booking_hash varchar(32) NOT NULL,
            notes text DEFAULT '',
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY event_id (event_id),
            KEY user_id (user_id),
            KEY booking_status (booking_status),
            KEY payment_status (payment_status),
            UNIQUE KEY booking_hash (booking_hash)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // Create ticket types table
        $ticket_types_table = $wpdb->prefix . 'aqualuxe_event_ticket_types';
        
        $ticket_types_sql = "CREATE TABLE $ticket_types_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            event_id bigint(20) NOT NULL,
            name varchar(100) NOT NULL,
            description text DEFAULT '',
            price decimal(10,2) NOT NULL DEFAULT 0.00,
            quantity_available int(11) DEFAULT -1,
            quantity_sold int(11) DEFAULT 0,
            sale_start datetime DEFAULT NULL,
            sale_end datetime DEFAULT NULL,
            is_active tinyint(1) DEFAULT 1,
            sort_order int(11) DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY event_id (event_id),
            KEY is_active (is_active)
        ) $charset_collate;";

        dbDelta($ticket_types_sql);
    }

    /**
     * Add event meta boxes
     */
    public function add_event_meta_boxes() {
        add_meta_box(
            'aqualuxe-event-details',
            __('Event Details', 'aqualuxe'),
            array($this, 'event_details_meta_box'),
            'aqualuxe_event',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe-event-tickets',
            __('Ticket Settings', 'aqualuxe'),
            array($this, 'event_tickets_meta_box'),
            'aqualuxe_event',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe-event-location',
            __('Event Location', 'aqualuxe'),
            array($this, 'event_location_meta_box'),
            'aqualuxe_event',
            'side',
            'default'
        );
    }

    /**
     * Event details meta box
     */
    public function event_details_meta_box($post) {
        wp_nonce_field(basename(__FILE__), 'aqualuxe_event_details_nonce');
        
        $start_date = get_post_meta($post->ID, '_aqualuxe_start_date', true);
        $end_date = get_post_meta($post->ID, '_aqualuxe_end_date', true);
        $start_time = get_post_meta($post->ID, '_aqualuxe_start_time', true);
        $end_time = get_post_meta($post->ID, '_aqualuxe_end_time', true);
        $max_attendees = get_post_meta($post->ID, '_aqualuxe_max_attendees', true);
        $event_type = get_post_meta($post->ID, '_aqualuxe_event_type', true);
        $featured = get_post_meta($post->ID, '_aqualuxe_featured', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="aqualuxe_start_date"><?php _e('Start Date', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="date" id="aqualuxe_start_date" name="aqualuxe_start_date" value="<?php echo esc_attr($start_date); ?>" required>
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_end_date"><?php _e('End Date', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="date" id="aqualuxe_end_date" name="aqualuxe_end_date" value="<?php echo esc_attr($end_date); ?>">
                    <p class="description"><?php _e('Leave empty for single-day events', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_start_time"><?php _e('Start Time', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="time" id="aqualuxe_start_time" name="aqualuxe_start_time" value="<?php echo esc_attr($start_time); ?>" required>
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_end_time"><?php _e('End Time', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="time" id="aqualuxe_end_time" name="aqualuxe_end_time" value="<?php echo esc_attr($end_time); ?>">
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_max_attendees"><?php _e('Maximum Attendees', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="number" id="aqualuxe_max_attendees" name="aqualuxe_max_attendees" value="<?php echo esc_attr($max_attendees); ?>" min="1">
                    <p class="description"><?php _e('Leave empty for unlimited capacity', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_event_type"><?php _e('Event Type', 'aqualuxe'); ?></label></th>
                <td>
                    <select id="aqualuxe_event_type" name="aqualuxe_event_type">
                        <option value="workshop" <?php selected($event_type, 'workshop'); ?>><?php _e('Workshop', 'aqualuxe'); ?></option>
                        <option value="tour" <?php selected($event_type, 'tour'); ?>><?php _e('Facility Tour', 'aqualuxe'); ?></option>
                        <option value="seminar" <?php selected($event_type, 'seminar'); ?>><?php _e('Seminar', 'aqualuxe'); ?></option>
                        <option value="exhibition" <?php selected($event_type, 'exhibition'); ?>><?php _e('Exhibition', 'aqualuxe'); ?></option>
                        <option value="conference" <?php selected($event_type, 'conference'); ?>><?php _e('Conference', 'aqualuxe'); ?></option>
                        <option value="networking" <?php selected($event_type, 'networking'); ?>><?php _e('Networking Event', 'aqualuxe'); ?></option>
                        <option value="other" <?php selected($event_type, 'other'); ?>><?php _e('Other', 'aqualuxe'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_featured"><?php _e('Featured Event', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="checkbox" id="aqualuxe_featured" name="aqualuxe_featured" value="1" <?php checked($featured, '1'); ?>>
                    <label for="aqualuxe_featured"><?php _e('Mark as featured event', 'aqualuxe'); ?></label>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Event tickets meta box
     */
    public function event_tickets_meta_box($post) {
        wp_nonce_field(basename(__FILE__), 'aqualuxe_event_tickets_nonce');
        
        $ticketing_enabled = get_post_meta($post->ID, '_aqualuxe_ticketing_enabled', true);
        $is_free = get_post_meta($post->ID, '_aqualuxe_is_free', true);
        $default_price = get_post_meta($post->ID, '_aqualuxe_default_price', true);
        $registration_required = get_post_meta($post->ID, '_aqualuxe_registration_required', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="aqualuxe_ticketing_enabled"><?php _e('Enable Ticketing', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="checkbox" id="aqualuxe_ticketing_enabled" name="aqualuxe_ticketing_enabled" value="1" <?php checked($ticketing_enabled, '1'); ?>>
                    <label for="aqualuxe_ticketing_enabled"><?php _e('Enable online ticket sales', 'aqualuxe'); ?></label>
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_is_free"><?php _e('Free Event', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="checkbox" id="aqualuxe_is_free" name="aqualuxe_is_free" value="1" <?php checked($is_free, '1'); ?>>
                    <label for="aqualuxe_is_free"><?php _e('This is a free event', 'aqualuxe'); ?></label>
                </td>
            </tr>
            <tr class="ticket-price-row" data-toggle-free>
                <th><label for="aqualuxe_default_price"><?php _e('Default Ticket Price', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="number" id="aqualuxe_default_price" name="aqualuxe_default_price" value="<?php echo esc_attr($default_price); ?>" step="0.01" min="0">
                    <p class="description"><?php _e('Price for general admission tickets', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_registration_required"><?php _e('Registration Required', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="checkbox" id="aqualuxe_registration_required" name="aqualuxe_registration_required" value="1" <?php checked($registration_required, '1'); ?>>
                    <label for="aqualuxe_registration_required"><?php _e('Require registration even for free events', 'aqualuxe'); ?></label>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Event location meta box
     */
    public function event_location_meta_box($post) {
        wp_nonce_field(basename(__FILE__), 'aqualuxe_event_location_nonce');
        
        $location_type = get_post_meta($post->ID, '_aqualuxe_location_type', true) ?: 'physical';
        $venue_name = get_post_meta($post->ID, '_aqualuxe_venue_name', true);
        $venue_address = get_post_meta($post->ID, '_aqualuxe_venue_address', true);
        $online_link = get_post_meta($post->ID, '_aqualuxe_online_link', true);
        $online_platform = get_post_meta($post->ID, '_aqualuxe_online_platform', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="aqualuxe_location_type"><?php _e('Location Type', 'aqualuxe'); ?></label></th>
                <td>
                    <select id="aqualuxe_location_type" name="aqualuxe_location_type">
                        <option value="physical" <?php selected($location_type, 'physical'); ?>><?php _e('Physical Location', 'aqualuxe'); ?></option>
                        <option value="online" <?php selected($location_type, 'online'); ?>><?php _e('Online Event', 'aqualuxe'); ?></option>
                        <option value="hybrid" <?php selected($location_type, 'hybrid'); ?>><?php _e('Hybrid (Physical + Online)', 'aqualuxe'); ?></option>
                    </select>
                </td>
            </tr>
            <tr class="physical-location" data-show-for="physical,hybrid">
                <th><label for="aqualuxe_venue_name"><?php _e('Venue Name', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="text" id="aqualuxe_venue_name" name="aqualuxe_venue_name" value="<?php echo esc_attr($venue_name); ?>" class="regular-text">
                </td>
            </tr>
            <tr class="physical-location" data-show-for="physical,hybrid">
                <th><label for="aqualuxe_venue_address"><?php _e('Venue Address', 'aqualuxe'); ?></label></th>
                <td>
                    <textarea id="aqualuxe_venue_address" name="aqualuxe_venue_address" rows="3" class="regular-text"><?php echo esc_textarea($venue_address); ?></textarea>
                </td>
            </tr>
            <tr class="online-location" data-show-for="online,hybrid">
                <th><label for="aqualuxe_online_platform"><?php _e('Online Platform', 'aqualuxe'); ?></label></th>
                <td>
                    <select id="aqualuxe_online_platform" name="aqualuxe_online_platform">
                        <option value="zoom" <?php selected($online_platform, 'zoom'); ?>><?php _e('Zoom', 'aqualuxe'); ?></option>
                        <option value="teams" <?php selected($online_platform, 'teams'); ?>><?php _e('Microsoft Teams', 'aqualuxe'); ?></option>
                        <option value="meet" <?php selected($online_platform, 'meet'); ?>><?php _e('Google Meet', 'aqualuxe'); ?></option>
                        <option value="webex" <?php selected($online_platform, 'webex'); ?>><?php _e('Webex', 'aqualuxe'); ?></option>
                        <option value="other" <?php selected($online_platform, 'other'); ?>><?php _e('Other', 'aqualuxe'); ?></option>
                    </select>
                </td>
            </tr>
            <tr class="online-location" data-show-for="online,hybrid">
                <th><label for="aqualuxe_online_link"><?php _e('Meeting Link', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="url" id="aqualuxe_online_link" name="aqualuxe_online_link" value="<?php echo esc_attr($online_link); ?>" class="regular-text">
                    <p class="description"><?php _e('This will be sent to registered attendees', 'aqualuxe'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save event meta
     */
    public function save_event_meta($post_id) {
        // Verify nonces
        $nonces = array(
            'aqualuxe_event_details_nonce',
            'aqualuxe_event_tickets_nonce',
            'aqualuxe_event_location_nonce'
        );
        
        $valid_nonce = false;
        foreach ($nonces as $nonce_name) {
            if (isset($_POST[$nonce_name]) && wp_verify_nonce($_POST[$nonce_name], basename(__FILE__))) {
                $valid_nonce = true;
                break;
            }
        }
        
        if (!$valid_nonce) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (get_post_type($post_id) !== 'aqualuxe_event') {
            return;
        }

        $fields = array(
            // Event details
            'aqualuxe_start_date' => 'date',
            'aqualuxe_end_date' => 'date',
            'aqualuxe_start_time' => 'time',
            'aqualuxe_end_time' => 'time',
            'aqualuxe_max_attendees' => 'integer',
            'aqualuxe_event_type' => 'text',
            'aqualuxe_featured' => 'boolean',
            
            // Ticket settings
            'aqualuxe_ticketing_enabled' => 'boolean',
            'aqualuxe_is_free' => 'boolean',
            'aqualuxe_default_price' => 'float',
            'aqualuxe_registration_required' => 'boolean',
            
            // Location
            'aqualuxe_location_type' => 'text',
            'aqualuxe_venue_name' => 'text',
            'aqualuxe_venue_address' => 'textarea',
            'aqualuxe_online_link' => 'url',
            'aqualuxe_online_platform' => 'text'
        );

        foreach ($fields as $field => $type) {
            $value = isset($_POST[$field]) ? $_POST[$field] : '';
            
            switch ($type) {
                case 'boolean':
                    $value = $value ? '1' : '0';
                    break;
                case 'integer':
                    $value = intval($value);
                    break;
                case 'float':
                    $value = floatval($value);
                    break;
                case 'date':
                case 'time':
                    $value = sanitize_text_field($value);
                    break;
                case 'url':
                    $value = esc_url_raw($value);
                    break;
                case 'textarea':
                    $value = sanitize_textarea_field($value);
                    break;
                default:
                    $value = sanitize_text_field($value);
            }
            
            update_post_meta($post_id, '_' . $field, $value);
        }
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts($hook) {
        global $post_type;
        
        // Only enqueue on event edit screens
        if ($post_type === 'aqualuxe_event' && in_array($hook, array('post.php', 'post-new.php'))) {
            wp_enqueue_script(
                'aqualuxe-event-admin',
                AQUALUXE_ASSETS_URI . '/js/admin/event-admin.js',
                array('jquery'),
                AQUALUXE_VERSION,
                true
            );
        }
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        if ($this->should_load_event_scripts()) {
            wp_enqueue_script(
                'aqualuxe-events',
                AQUALUXE_ASSETS_URI . '/js/modules/events.js',
                array('jquery'),
                AQUALUXE_VERSION,
                true
            );

            wp_localize_script('aqualuxe-events', 'aqualuxe_events', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => aqualuxe_create_nonce('events'),
                'strings' => array(
                    'booking_success' => __('Event booking successful!', 'aqualuxe'),
                    'booking_error' => __('Error booking event. Please try again.', 'aqualuxe'),
                    'loading' => __('Loading...', 'aqualuxe'),
                    'confirm_cancel' => __('Are you sure you want to cancel this booking?', 'aqualuxe'),
                    'select_tickets' => __('Please select number of tickets', 'aqualuxe'),
                )
            ));
        }
    }

    /**
     * Check if event scripts should be loaded
     */
    private function should_load_event_scripts() {
        global $post;
        
        return (
            is_singular('aqualuxe_event') ||
            is_post_type_archive('aqualuxe_event') ||
            is_tax(array('event_category', 'event_tag')) ||
            is_page_template('templates/page-events.php') ||
            (is_a($post, 'WP_Post') && 
             (has_shortcode($post->post_content, 'aqualuxe_events_calendar') ||
              has_shortcode($post->post_content, 'aqualuxe_event_booking') ||
              has_shortcode($post->post_content, 'aqualuxe_upcoming_events')))
        );
    }

    /**
     * Book event via AJAX
     */
    public function ajax_book_event($data) {
        // Validate required fields
        $required_fields = array('event_id', 'customer_name', 'customer_email', 'tickets_quantity');
        
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                wp_send_json_error(__('Missing required field: ' . $field, 'aqualuxe'));
            }
        }

        $event_id = intval($data['event_id']);
        $event = get_post($event_id);
        
        if (!$event || $event->post_type !== 'aqualuxe_event') {
            wp_send_json_error(__('Invalid event', 'aqualuxe'));
        }

        // Check if ticketing is enabled
        $ticketing_enabled = get_post_meta($event_id, '_aqualuxe_ticketing_enabled', true);
        if (!$ticketing_enabled) {
            wp_send_json_error(__('Online booking is not available for this event', 'aqualuxe'));
        }

        // Check availability
        $tickets_quantity = intval($data['tickets_quantity']);
        if (!$this->check_event_availability($event_id, $tickets_quantity)) {
            wp_send_json_error(__('Not enough tickets available', 'aqualuxe'));
        }

        // Calculate total amount
        $is_free = get_post_meta($event_id, '_aqualuxe_is_free', true);
        $default_price = $is_free ? 0 : floatval(get_post_meta($event_id, '_aqualuxe_default_price', true));
        $total_amount = $default_price * $tickets_quantity;

        // Create booking
        $booking_id = $this->create_event_booking(array(
            'event_id' => $event_id,
            'customer_name' => sanitize_text_field($data['customer_name']),
            'customer_email' => sanitize_email($data['customer_email']),
            'customer_phone' => sanitize_text_field($data['customer_phone'] ?? ''),
            'tickets_quantity' => $tickets_quantity,
            'ticket_type' => sanitize_text_field($data['ticket_type'] ?? 'general'),
            'total_amount' => $total_amount,
            'notes' => sanitize_textarea_field($data['notes'] ?? ''),
            'user_id' => get_current_user_id() ?: null
        ));

        if ($booking_id) {
            do_action('aqualuxe_event_booked', $booking_id);
            
            wp_send_json_success(array(
                'booking_id' => $booking_id,
                'message' => __('Event booking successful!', 'aqualuxe'),
                'redirect' => home_url('/event-confirmation/?booking=' . $this->get_event_booking_hash($booking_id))
            ));
        } else {
            wp_send_json_error(__('Failed to create booking. Please try again.', 'aqualuxe'));
        }
    }

    /**
     * Cancel event booking via AJAX
     */
    public function ajax_cancel_event_booking($data) {
        if (empty($data['booking_id'])) {
            wp_send_json_error(__('Missing booking ID', 'aqualuxe'));
        }

        $booking_id = intval($data['booking_id']);
        $booking = $this->get_event_booking($booking_id);

        if (!$booking) {
            wp_send_json_error(__('Booking not found', 'aqualuxe'));
        }

        // Check permissions
        if (!current_user_can('manage_options') && 
            $booking->user_id != get_current_user_id()) {
            wp_send_json_error(__('Insufficient permissions', 'aqualuxe'));
        }

        if ($this->cancel_event_booking($booking_id)) {
            do_action('aqualuxe_event_cancelled', $booking_id);
            
            wp_send_json_success(array(
                'message' => __('Event booking cancelled successfully', 'aqualuxe')
            ));
        } else {
            wp_send_json_error(__('Failed to cancel booking', 'aqualuxe'));
        }
    }

    /**
     * Get event calendar data via AJAX
     */
    public function ajax_get_event_calendar($data) {
        $month = isset($data['month']) ? intval($data['month']) : date('n');
        $year = isset($data['year']) ? intval($data['year']) : date('Y');
        
        $events = $this->get_events_for_month($month, $year);
        
        wp_send_json_success(array(
            'events' => $events,
            'month' => $month,
            'year' => $year
        ));
    }

    /**
     * Create event booking
     */
    private function create_event_booking($data) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'aqualuxe_event_bookings';
        
        $booking_data = array(
            'event_id' => $data['event_id'],
            'user_id' => $data['user_id'],
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'],
            'tickets_quantity' => $data['tickets_quantity'],
            'ticket_type' => $data['ticket_type'],
            'total_amount' => $data['total_amount'],
            'notes' => $data['notes'],
            'booking_hash' => md5(uniqid($data['customer_email'], true)),
            'booking_status' => 'confirmed',
            'payment_status' => $data['total_amount'] > 0 ? 'pending' : 'completed'
        );

        $result = $wpdb->insert($table_name, $booking_data);
        
        return $result ? $wpdb->insert_id : false;
    }

    /**
     * Check event availability
     */
    private function check_event_availability($event_id, $requested_tickets) {
        global $wpdb;
        
        $max_attendees = get_post_meta($event_id, '_aqualuxe_max_attendees', true);
        
        if (!$max_attendees) {
            return true; // Unlimited capacity
        }
        
        $table_name = $wpdb->prefix . 'aqualuxe_event_bookings';
        $booked_tickets = $wpdb->get_var($wpdb->prepare(
            "SELECT COALESCE(SUM(tickets_quantity), 0) 
             FROM $table_name 
             WHERE event_id = %d AND booking_status = 'confirmed'",
            $event_id
        ));
        
        return ($booked_tickets + $requested_tickets) <= $max_attendees;
    }

    /**
     * Get event booking
     */
    private function get_event_booking($booking_id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_event_bookings';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d",
            $booking_id
        ));
    }

    /**
     * Cancel event booking
     */
    private function cancel_event_booking($booking_id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_event_bookings';
        
        $result = $wpdb->update(
            $table_name,
            array('booking_status' => 'cancelled'),
            array('id' => $booking_id),
            array('%s'),
            array('%d')
        );

        return $result !== false;
    }

    /**
     * Get event booking hash
     */
    private function get_event_booking_hash($booking_id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_event_bookings';
        
        return $wpdb->get_var($wpdb->prepare(
            "SELECT booking_hash FROM $table_name WHERE id = %d",
            $booking_id
        ));
    }

    /**
     * Get events for a specific month
     */
    private function get_events_for_month($month, $year) {
        $start_date = sprintf('%04d-%02d-01', $year, $month);
        $end_date = date('Y-m-t', strtotime($start_date));
        
        $args = array(
            'post_type' => 'aqualuxe_event',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_aqualuxe_start_date',
                    'value' => array($start_date, $end_date),
                    'compare' => 'BETWEEN',
                    'type' => 'DATE'
                )
            ),
            'meta_key' => '_aqualuxe_start_date',
            'orderby' => 'meta_value',
            'order' => 'ASC'
        );
        
        $events = get_posts($args);
        $calendar_events = array();
        
        foreach ($events as $event) {
            $start_date = get_post_meta($event->ID, '_aqualuxe_start_date', true);
            $start_time = get_post_meta($event->ID, '_aqualuxe_start_time', true);
            $venue_name = get_post_meta($event->ID, '_aqualuxe_venue_name', true);
            
            $calendar_events[] = array(
                'id' => $event->ID,
                'title' => $event->post_title,
                'date' => $start_date,
                'time' => $start_time,
                'venue' => $venue_name,
                'url' => get_permalink($event->ID)
            );
        }
        
        return $calendar_events;
    }

    /**
     * Send event confirmation email
     */
    public function send_event_confirmation($booking_id) {
        $booking = $this->get_event_booking($booking_id);
        if (!$booking) {
            return;
        }

        $event = get_post($booking->event_id);
        $subject = sprintf(__('[%s] Event Booking Confirmation - %s', 'aqualuxe'), get_bloginfo('name'), $event->post_title);
        
        $message = $this->get_event_email_template('event_confirmation', array(
            'booking' => $booking,
            'event' => $event
        ));

        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        wp_mail($booking->customer_email, $subject, $message, $headers);
        
        // Send copy to admin
        $admin_email = get_option('admin_email');
        wp_mail($admin_email, '[Admin] ' . $subject, $message, $headers);
    }

    /**
     * Send cancellation email
     */
    public function send_cancellation_email($booking_id) {
        $booking = $this->get_event_booking($booking_id);
        if (!$booking) {
            return;
        }

        $event = get_post($booking->event_id);
        $subject = sprintf(__('[%s] Event Booking Cancelled - %s', 'aqualuxe'), get_bloginfo('name'), $event->post_title);
        
        $message = $this->get_event_email_template('event_cancellation', array(
            'booking' => $booking,
            'event' => $event
        ));

        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        wp_mail($booking->customer_email, $subject, $message, $headers);
    }

    /**
     * Send event reminder
     */
    public function send_event_reminder($booking_id) {
        $booking = $this->get_event_booking($booking_id);
        if (!$booking) {
            return;
        }

        $event = get_post($booking->event_id);
        $subject = sprintf(__('[%s] Event Reminder - %s', 'aqualuxe'), get_bloginfo('name'), $event->post_title);
        
        $message = $this->get_event_email_template('event_reminder', array(
            'booking' => $booking,
            'event' => $event
        ));

        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        wp_mail($booking->customer_email, $subject, $message, $headers);
    }

    /**
     * Get event email template
     */
    private function get_event_email_template($template, $data) {
        $template_path = get_template_directory() . '/templates/emails/' . $template . '.php';
        
        if (file_exists($template_path)) {
            ob_start();
            // Set variables for template use
            foreach ($data as $key => $value) {
                $$key = $value;
            }
            include $template_path;
            return ob_get_clean();
        }
        
        // Fallback to simple text email
        return $this->get_simple_event_email_template($template, $data);
    }

    /**
     * Get simple event email template fallback
     */
    private function get_simple_event_email_template($template, $data) {
        $booking = $data['booking'];
        $event = $data['event'];
        
        $start_date = get_post_meta($event->ID, '_aqualuxe_start_date', true);
        $start_time = get_post_meta($event->ID, '_aqualuxe_start_time', true);
        $venue_name = get_post_meta($event->ID, '_aqualuxe_venue_name', true);
        
        if ($template === 'event_confirmation') {
            return sprintf(
                __("Dear %s,\n\nYour booking for %s has been confirmed.\n\nEvent Details:\nDate: %s\nTime: %s\nVenue: %s\nTickets: %d\n\nThank you for registering with AquaLuxe!", 'aqualuxe'),
                $booking->customer_name,
                $event->post_title,
                date_i18n(get_option('date_format'), strtotime($start_date)),
                $start_time ? date_i18n(get_option('time_format'), strtotime($start_time)) : '',
                $venue_name,
                $booking->tickets_quantity
            );
        } elseif ($template === 'event_cancellation') {
            return sprintf(
                __("Dear %s,\n\nYour booking for %s has been cancelled.\n\nEvent Details:\nDate: %s\nTime: %s\nTickets: %d\n\nIf you have any questions, please contact us.", 'aqualuxe'),
                $booking->customer_name,
                $event->post_title,
                date_i18n(get_option('date_format'), strtotime($start_date)),
                $start_time ? date_i18n(get_option('time_format'), strtotime($start_time)) : '',
                $booking->tickets_quantity
            );
        } elseif ($template === 'event_reminder') {
            return sprintf(
                __("Dear %s,\n\nThis is a reminder that %s is coming up soon!\n\nEvent Details:\nDate: %s\nTime: %s\nVenue: %s\nTickets: %d\n\nWe look forward to seeing you there!", 'aqualuxe'),
                $booking->customer_name,
                $event->post_title,
                date_i18n(get_option('date_format'), strtotime($start_date)),
                $start_time ? date_i18n(get_option('time_format'), strtotime($start_time)) : '',
                $venue_name,
                $booking->tickets_quantity
            );
        }
        
        return '';
    }

    /**
     * Events calendar shortcode
     */
    public function events_calendar_shortcode($atts) {
        $atts = shortcode_atts(array(
            'view' => 'month',
            'categories' => '',
            'featured_only' => 'false'
        ), $atts);

        ob_start();
        $template_path = get_template_directory() . '/templates/shortcodes/events-calendar.php';
        
        if (file_exists($template_path)) {
            // Set variables for template use
            foreach ($atts as $key => $value) {
                $$key = $value;
            }
            include $template_path;
        } else {
            echo '<div class="aqualuxe-events-calendar">';
            echo '<p>' . __('Events calendar template not found.', 'aqualuxe') . '</p>';
            echo '</div>';
        }
        
        return ob_get_clean();
    }

    /**
     * Event booking shortcode
     */
    public function event_booking_shortcode($atts) {
        $atts = shortcode_atts(array(
            'event_id' => 0
        ), $atts);

        ob_start();
        $template_path = get_template_directory() . '/templates/shortcodes/event-booking.php';
        
        if (file_exists($template_path)) {
            // Set variables for template use
            foreach ($atts as $key => $value) {
                $$key = $value;
            }
            include $template_path;
        } else {
            echo '<div class="aqualuxe-event-booking">';
            echo '<p>' . __('Event booking template not found.', 'aqualuxe') . '</p>';
            echo '</div>';
        }
        
        return ob_get_clean();
    }

    /**
     * Upcoming events shortcode
     */
    public function upcoming_events_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 5,
            'categories' => '',
            'featured_only' => 'false'
        ), $atts);

        ob_start();
        $template_path = get_template_directory() . '/templates/shortcodes/upcoming-events.php';
        
        if (file_exists($template_path)) {
            // Set variables for template use
            foreach ($atts as $key => $value) {
                $$key = $value;
            }
            include $template_path;
        } else {
            echo '<div class="aqualuxe-upcoming-events">';
            echo '<p>' . __('Upcoming events template not found.', 'aqualuxe') . '</p>';
            echo '</div>';
        }
        
        return ob_get_clean();
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=aqualuxe_event',
            __('Event Bookings', 'aqualuxe'),
            __('Bookings', 'aqualuxe'),
            'manage_options',
            'aqualuxe-event-bookings',
            array($this, 'admin_event_bookings_page')
        );
    }

    /**
     * Admin event bookings page
     */
    public function admin_event_bookings_page() {
        $template_path = get_template_directory() . '/templates/admin/event-bookings.php';
        
        if (file_exists($template_path)) {
            include $template_path;
        } else {
            echo '<div class="wrap">';
            echo '<h1>' . __('Event Bookings', 'aqualuxe') . '</h1>';
            echo '<p>' . __('Admin event bookings template not found.', 'aqualuxe') . '</p>';
            echo '</div>';
        }
    }

    /**
     * Schedule tasks
     */
    private function schedule_tasks() {
        // Schedule event reminders
        if (!wp_next_scheduled('aqualuxe_send_event_reminders')) {
            wp_schedule_event(time(), 'daily', 'aqualuxe_send_event_reminders');
        }
        
        // Schedule cleanup of past events
        if (!wp_next_scheduled('aqualuxe_cleanup_past_events')) {
            wp_schedule_event(time(), 'weekly', 'aqualuxe_cleanup_past_events');
        }
    }

    /**
     * Send event reminders
     */
    public function send_event_reminders() {
        global $wpdb;
        
        // Send reminders 24 hours before event
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        
        $table_name = $wpdb->prefix . 'aqualuxe_event_bookings';
        $events_table = $wpdb->prefix . 'posts';
        
        $bookings = $wpdb->get_results($wpdb->prepare(
            "SELECT eb.*, pm.meta_value as start_date 
             FROM $table_name eb
             INNER JOIN {$wpdb->postmeta} pm ON eb.event_id = pm.post_id
             WHERE eb.booking_status = 'confirmed'
             AND pm.meta_key = '_aqualuxe_start_date'
             AND pm.meta_value = %s",
            $tomorrow
        ));
        
        foreach ($bookings as $booking) {
            do_action('aqualuxe_event_reminder', $booking->id);
        }
    }

    /**
     * Cleanup past events
     */
    public function cleanup_past_events() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_event_bookings';
        
        // Delete cancelled bookings older than 30 days
        $wpdb->query($wpdb->prepare(
            "DELETE FROM $table_name 
             WHERE booking_status = 'cancelled' 
             AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)"
        ));
    }
}

// Initialize the module
new AquaLuxe_Events_Module();

/**
 * Helper functions
 */
function aqualuxe_get_events_calendar($view = 'month') {
    return do_shortcode('[aqualuxe_events_calendar view="' . esc_attr($view) . '"]');
}

function aqualuxe_get_event_booking_form($event_id = 0) {
    return do_shortcode('[aqualuxe_event_booking event_id="' . intval($event_id) . '"]');
}

function aqualuxe_get_upcoming_events($limit = 5) {
    return do_shortcode('[aqualuxe_upcoming_events limit="' . intval($limit) . '"]');
}