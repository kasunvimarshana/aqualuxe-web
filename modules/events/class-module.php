<?php
/**
 * Events Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Events;

use AquaLuxe\Core\Abstracts\Abstract_Module;

defined('ABSPATH') || exit;

/**
 * Events Module Class
 */
class Module extends Abstract_Module {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Events';

    /**
     * Instance
     *
     * @var Module
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Module
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize module
     */
    public function init() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_aqualuxe_register_event', array($this, 'handle_event_registration'));
        add_action('wp_ajax_nopriv_aqualuxe_register_event', array($this, 'handle_event_registration'));
        add_shortcode('aqualuxe_events', array($this, 'events_shortcode'));
        add_shortcode('aqualuxe_event_calendar', array($this, 'event_calendar_shortcode'));
        add_shortcode('aqualuxe_event_registration', array($this, 'event_registration_shortcode'));

        // Create tickets table
        add_action('init', array($this, 'create_tickets_table'));
    }

    /**
     * Create event tickets table
     */
    public function create_tickets_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'aqualuxe_event_tickets';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            event_id int(11) NOT NULL,
            ticket_type varchar(100) NOT NULL,
            attendee_name varchar(255) NOT NULL,
            attendee_email varchar(255) NOT NULL,
            attendee_phone varchar(50),
            ticket_code varchar(50) UNIQUE,
            purchase_date datetime DEFAULT CURRENT_TIMESTAMP,
            ticket_price decimal(10,2) DEFAULT 0.00,
            payment_status varchar(20) DEFAULT 'pending',
            attendance_status varchar(20) DEFAULT 'registered',
            special_requirements text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY event_id (event_id),
            KEY ticket_code (ticket_code),
            KEY attendee_email (attendee_email)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Register events post type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Events', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Event', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Events', 'Admin Menu text', 'aqualuxe'),
            'name_admin_bar'        => _x('Event', 'Add New on Toolbar', 'aqualuxe'),
            'add_new'               => __('Add New', 'aqualuxe'),
            'add_new_item'          => __('Add New Event', 'aqualuxe'),
            'new_item'              => __('New Event', 'aqualuxe'),
            'edit_item'             => __('Edit Event', 'aqualuxe'),
            'view_item'             => __('View Event', 'aqualuxe'),
            'all_items'             => __('All Events', 'aqualuxe'),
            'search_items'          => __('Search Events', 'aqualuxe'),
            'not_found'             => __('No events found.', 'aqualuxe'),
            'not_found_in_trash'    => __('No events found in Trash.', 'aqualuxe'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'events'),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => 25,
            'menu_icon'          => 'dashicons-calendar',
            'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'custom-fields'),
        );

        register_post_type('aqualuxe_event', $args);
    }

    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Event Categories
        $category_labels = array(
            'name'              => _x('Event Categories', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Event Category', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Categories', 'aqualuxe'),
            'all_items'         => __('All Categories', 'aqualuxe'),
            'parent_item'       => __('Parent Category', 'aqualuxe'),
            'parent_item_colon' => __('Parent Category:', 'aqualuxe'),
            'edit_item'         => __('Edit Category', 'aqualuxe'),
            'update_item'       => __('Update Category', 'aqualuxe'),
            'add_new_item'      => __('Add New Category', 'aqualuxe'),
            'new_item_name'     => __('New Category Name', 'aqualuxe'),
            'menu_name'         => __('Categories', 'aqualuxe'),
        );

        register_taxonomy('aqualuxe_event_category', array('aqualuxe_event'), array(
            'hierarchical'      => true,
            'labels'            => $category_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'event-category'),
        ));

        // Event Tags
        $tag_labels = array(
            'name'              => _x('Event Tags', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Event Tag', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Tags', 'aqualuxe'),
            'all_items'         => __('All Tags', 'aqualuxe'),
            'edit_item'         => __('Edit Tag', 'aqualuxe'),
            'update_item'       => __('Update Tag', 'aqualuxe'),
            'add_new_item'      => __('Add New Tag', 'aqualuxe'),
            'new_item_name'     => __('New Tag Name', 'aqualuxe'),
            'menu_name'         => __('Tags', 'aqualuxe'),
        );

        register_taxonomy('aqualuxe_event_tag', array('aqualuxe_event'), array(
            'hierarchical'      => false,
            'labels'            => $tag_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'event-tag'),
        ));
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'aqualuxe_event_details',
            __('Event Details', 'aqualuxe'),
            array($this, 'event_details_meta_box'),
            'aqualuxe_event',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe_event_tickets',
            __('Ticketing & Pricing', 'aqualuxe'),
            array($this, 'event_tickets_meta_box'),
            'aqualuxe_event',
            'side',
            'default'
        );

        add_meta_box(
            'aqualuxe_event_location',
            __('Location & Venue', 'aqualuxe'),
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
        wp_nonce_field('aqualuxe_event_nonce', 'aqualuxe_event_nonce');
        
        $start_date = get_post_meta($post->ID, '_event_start_date', true);
        $end_date = get_post_meta($post->ID, '_event_end_date', true);
        $start_time = get_post_meta($post->ID, '_event_start_time', true);
        $end_time = get_post_meta($post->ID, '_event_end_time', true);
        $capacity = get_post_meta($post->ID, '_event_capacity', true);
        $organizer = get_post_meta($post->ID, '_event_organizer', true);
        $status = get_post_meta($post->ID, '_event_status', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="event_start_date"><?php _e('Start Date', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="date" name="event_start_date" id="event_start_date" value="<?php echo esc_attr($start_date); ?>" required />
                </td>
            </tr>
            <tr>
                <th><label for="event_end_date"><?php _e('End Date', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="date" name="event_end_date" id="event_end_date" value="<?php echo esc_attr($end_date); ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="event_start_time"><?php _e('Start Time', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="time" name="event_start_time" id="event_start_time" value="<?php echo esc_attr($start_time); ?>" required />
                </td>
            </tr>
            <tr>
                <th><label for="event_end_time"><?php _e('End Time', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="time" name="event_end_time" id="event_end_time" value="<?php echo esc_attr($end_time); ?>" />
                </td>
            </tr>
            <tr>
                <th><label for="event_capacity"><?php _e('Capacity', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="number" name="event_capacity" id="event_capacity" value="<?php echo esc_attr($capacity); ?>" min="1" />
                    <p class="description"><?php _e('Maximum number of attendees (leave empty for unlimited)', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="event_organizer"><?php _e('Organizer', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="text" name="event_organizer" id="event_organizer" value="<?php echo esc_attr($organizer); ?>" style="width: 100%;" />
                </td>
            </tr>
            <tr>
                <th><label for="event_status"><?php _e('Status', 'aqualuxe'); ?></label></th>
                <td>
                    <select name="event_status" id="event_status">
                        <option value="upcoming" <?php selected($status, 'upcoming'); ?>><?php _e('Upcoming', 'aqualuxe'); ?></option>
                        <option value="ongoing" <?php selected($status, 'ongoing'); ?>><?php _e('Ongoing', 'aqualuxe'); ?></option>
                        <option value="completed" <?php selected($status, 'completed'); ?>><?php _e('Completed', 'aqualuxe'); ?></option>
                        <option value="cancelled" <?php selected($status, 'cancelled'); ?>><?php _e('Cancelled', 'aqualuxe'); ?></option>
                        <option value="postponed" <?php selected($status, 'postponed'); ?>><?php _e('Postponed', 'aqualuxe'); ?></option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Event tickets meta box
     */
    public function event_tickets_meta_box($post) {
        $ticket_price = get_post_meta($post->ID, '_event_ticket_price', true);
        $early_bird_price = get_post_meta($post->ID, '_event_early_bird_price', true);
        $early_bird_deadline = get_post_meta($post->ID, '_event_early_bird_deadline', true);
        $ticket_types = get_post_meta($post->ID, '_event_ticket_types', true);
        $registration_deadline = get_post_meta($post->ID, '_event_registration_deadline', true);
        ?>
        <p>
            <label for="event_ticket_price"><?php _e('Regular Price', 'aqualuxe'); ?></label>
            <input type="number" name="event_ticket_price" id="event_ticket_price" value="<?php echo esc_attr($ticket_price); ?>" step="0.01" min="0" style="width: 100%;" />
        </p>
        <p>
            <label for="event_early_bird_price"><?php _e('Early Bird Price', 'aqualuxe'); ?></label>
            <input type="number" name="event_early_bird_price" id="event_early_bird_price" value="<?php echo esc_attr($early_bird_price); ?>" step="0.01" min="0" style="width: 100%;" />
        </p>
        <p>
            <label for="event_early_bird_deadline"><?php _e('Early Bird Deadline', 'aqualuxe'); ?></label>
            <input type="date" name="event_early_bird_deadline" id="event_early_bird_deadline" value="<?php echo esc_attr($early_bird_deadline); ?>" style="width: 100%;" />
        </p>
        <p>
            <label for="event_registration_deadline"><?php _e('Registration Deadline', 'aqualuxe'); ?></label>
            <input type="date" name="event_registration_deadline" id="event_registration_deadline" value="<?php echo esc_attr($registration_deadline); ?>" style="width: 100%;" />
        </p>
        <p>
            <label for="event_ticket_types"><?php _e('Ticket Types', 'aqualuxe'); ?></label>
            <textarea name="event_ticket_types" id="event_ticket_types" rows="4" style="width: 100%;"><?php echo esc_textarea($ticket_types); ?></textarea>
            <span class="description"><?php _e('One ticket type per line (Type:Price)', 'aqualuxe'); ?></span>
        </p>
        <?php
    }

    /**
     * Event location meta box
     */
    public function event_location_meta_box($post) {
        $venue_name = get_post_meta($post->ID, '_event_venue_name', true);
        $venue_address = get_post_meta($post->ID, '_event_venue_address', true);
        $venue_city = get_post_meta($post->ID, '_event_venue_city', true);
        $venue_state = get_post_meta($post->ID, '_event_venue_state', true);
        $venue_country = get_post_meta($post->ID, '_event_venue_country', true);
        $venue_map_url = get_post_meta($post->ID, '_event_venue_map_url', true);
        ?>
        <p>
            <label for="event_venue_name"><?php _e('Venue Name', 'aqualuxe'); ?></label>
            <input type="text" name="event_venue_name" id="event_venue_name" value="<?php echo esc_attr($venue_name); ?>" style="width: 100%;" />
        </p>
        <p>
            <label for="event_venue_address"><?php _e('Address', 'aqualuxe'); ?></label>
            <input type="text" name="event_venue_address" id="event_venue_address" value="<?php echo esc_attr($venue_address); ?>" style="width: 100%;" />
        </p>
        <p>
            <label for="event_venue_city"><?php _e('City', 'aqualuxe'); ?></label>
            <input type="text" name="event_venue_city" id="event_venue_city" value="<?php echo esc_attr($venue_city); ?>" style="width: 100%;" />
        </p>
        <p>
            <label for="event_venue_state"><?php _e('State/Province', 'aqualuxe'); ?></label>
            <input type="text" name="event_venue_state" id="event_venue_state" value="<?php echo esc_attr($venue_state); ?>" style="width: 100%;" />
        </p>
        <p>
            <label for="event_venue_country"><?php _e('Country', 'aqualuxe'); ?></label>
            <input type="text" name="event_venue_country" id="event_venue_country" value="<?php echo esc_attr($venue_country); ?>" style="width: 100%;" />
        </p>
        <p>
            <label for="event_venue_map_url"><?php _e('Map URL', 'aqualuxe'); ?></label>
            <input type="url" name="event_venue_map_url" id="event_venue_map_url" value="<?php echo esc_attr($venue_map_url); ?>" style="width: 100%;" />
        </p>
        <?php
    }

    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['aqualuxe_event_nonce']) || !wp_verify_nonce($_POST['aqualuxe_event_nonce'], 'aqualuxe_event_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = array(
            'event_start_date',
            'event_end_date',
            'event_start_time',
            'event_end_time',
            'event_capacity',
            'event_organizer',
            'event_status',
            'event_ticket_price',
            'event_early_bird_price',
            'event_early_bird_deadline',
            'event_registration_deadline',
            'event_ticket_types',
            'event_venue_name',
            'event_venue_address',
            'event_venue_city',
            'event_venue_state',
            'event_venue_country',
            'event_venue_map_url'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        if (is_singular('aqualuxe_event') || is_post_type_archive('aqualuxe_event') || is_page()) {
            wp_enqueue_script('aqualuxe-events', $this->get_url() . '/assets/events.js', array('jquery'), '1.0.0', true);
            wp_enqueue_style('aqualuxe-events', $this->get_url() . '/assets/events.css', array(), '1.0.0');
            
            wp_localize_script('aqualuxe-events', 'aqualuxe_events', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_events_nonce'),
                'currency_symbol' => get_woocommerce_currency_symbol(),
                'date_format' => get_option('date_format'),
                'time_format' => get_option('time_format'),
            ));
        }
    }

    /**
     * Handle event registration
     */
    public function handle_event_registration() {
        check_ajax_referer('aqualuxe_events_nonce', 'nonce');

        $event_id = intval($_POST['event_id']);
        $ticket_type = sanitize_text_field($_POST['ticket_type']);
        $attendee_name = sanitize_text_field($_POST['attendee_name']);
        $attendee_email = sanitize_email($_POST['attendee_email']);
        $attendee_phone = sanitize_text_field($_POST['attendee_phone']);
        $special_requirements = sanitize_textarea_field($_POST['special_requirements']);

        // Validate required fields
        if (!$event_id || !$attendee_name || !$attendee_email) {
            wp_send_json_error('Please fill in all required fields');
        }

        // Check event capacity
        $capacity = get_post_meta($event_id, '_event_capacity', true);
        if ($capacity && $this->get_registered_count($event_id) >= $capacity) {
            wp_send_json_error('Event is full');
        }

        // Generate unique ticket code
        $ticket_code = 'AL-' . strtoupper(uniqid());

        // Calculate ticket price
        $ticket_price = $this->calculate_ticket_price($event_id, $ticket_type);

        // Insert ticket record
        global $wpdb;
        $table_name = $wpdb->prefix . 'aqualuxe_event_tickets';

        $result = $wpdb->insert(
            $table_name,
            array(
                'event_id' => $event_id,
                'ticket_type' => $ticket_type,
                'attendee_name' => $attendee_name,
                'attendee_email' => $attendee_email,
                'attendee_phone' => $attendee_phone,
                'ticket_code' => $ticket_code,
                'ticket_price' => $ticket_price,
                'special_requirements' => $special_requirements,
            ),
            array('%d', '%s', '%s', '%s', '%s', '%s', '%f', '%s')
        );

        if ($result) {
            // Send confirmation email
            $this->send_ticket_confirmation($wpdb->insert_id);
            wp_send_json_success(array(
                'message' => 'Registration successful',
                'ticket_code' => $ticket_code
            ));
        } else {
            wp_send_json_error('Registration failed');
        }
    }

    /**
     * Get registered attendee count for an event
     */
    private function get_registered_count($event_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aqualuxe_event_tickets';

        return $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE event_id = %d AND payment_status != 'refunded'",
            $event_id
        ));
    }

    /**
     * Calculate ticket price based on type and timing
     */
    private function calculate_ticket_price($event_id, $ticket_type) {
        $regular_price = get_post_meta($event_id, '_event_ticket_price', true);
        $early_bird_price = get_post_meta($event_id, '_event_early_bird_price', true);
        $early_bird_deadline = get_post_meta($event_id, '_event_early_bird_deadline', true);

        // Check if early bird pricing applies
        if ($early_bird_price && $early_bird_deadline && strtotime($early_bird_deadline) > time()) {
            return $early_bird_price;
        }

        // Check for custom ticket types
        $ticket_types = get_post_meta($event_id, '_event_ticket_types', true);
        if ($ticket_types) {
            $types = explode("\n", $ticket_types);
            foreach ($types as $type) {
                if (strpos($type, ':') !== false) {
                    list($type_name, $type_price) = explode(':', $type, 2);
                    if (trim($type_name) === $ticket_type) {
                        return floatval(trim($type_price));
                    }
                }
            }
        }

        return $regular_price ? $regular_price : 0;
    }

    /**
     * Send ticket confirmation email
     */
    private function send_ticket_confirmation($ticket_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aqualuxe_event_tickets';

        $ticket = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d",
            $ticket_id
        ));

        if (!$ticket) {
            return;
        }

        $event = get_post($ticket->event_id);
        $event_date = get_post_meta($ticket->event_id, '_event_start_date', true);
        $event_time = get_post_meta($ticket->event_id, '_event_start_time', true);

        $subject = sprintf(__('Event Ticket Confirmation - %s', 'aqualuxe'), $event->post_title);
        
        $message = sprintf(
            __('Dear %s,\n\nYour registration for "%s" has been confirmed.\n\nEvent Details:\nDate: %s\nTime: %s\nTicket Code: %s\n\nThank you for registering!', 'aqualuxe'),
            $ticket->attendee_name,
            $event->post_title,
            $event_date,
            $event_time,
            $ticket->ticket_code
        );

        wp_mail($ticket->attendee_email, $subject, $message);
    }

    /**
     * Events shortcode
     */
    public function events_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 6,
            'category' => '',
            'status' => 'upcoming',
            'orderby' => 'meta_value',
            'meta_key' => '_event_start_date',
            'order' => 'ASC'
        ), $atts);

        $args = array(
            'post_type' => 'aqualuxe_event',
            'posts_per_page' => intval($atts['limit']),
            'post_status' => 'publish',
            'orderby' => $atts['orderby'],
            'order' => $atts['order']
        );

        if ($atts['meta_key']) {
            $args['meta_key'] = $atts['meta_key'];
        }

        if (!empty($atts['category'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'aqualuxe_event_category',
                'field' => 'slug',
                'terms' => $atts['category']
            );
        }

        if (!empty($atts['status'])) {
            $args['meta_query'][] = array(
                'key' => '_event_status',
                'value' => $atts['status'],
                'compare' => '='
            );
        }

        $events = new \WP_Query($args);
        
        ob_start();
        if ($events->have_posts()) {
            echo '<div class="aqualuxe-events-grid">';
            while ($events->have_posts()) {
                $events->the_post();
                $this->load_template('event-card', array('post_id' => get_the_ID()));
            }
            echo '</div>';
            wp_reset_postdata();
        }
        return ob_get_clean();
    }

    /**
     * Event calendar shortcode
     */
    public function event_calendar_shortcode($atts) {
        $atts = shortcode_atts(array(
            'view' => 'month'
        ), $atts);

        ob_start();
        $this->load_template('event-calendar', array('view' => $atts['view']));
        return ob_get_clean();
    }

    /**
     * Event registration shortcode
     */
    public function event_registration_shortcode($atts) {
        $atts = shortcode_atts(array(
            'event_id' => 0
        ), $atts);

        if (!$atts['event_id']) {
            return '';
        }

        ob_start();
        $this->load_template('event-registration-form', array('event_id' => $atts['event_id']));
        return ob_get_clean();
    }
}