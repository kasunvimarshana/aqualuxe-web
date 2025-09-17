<?php
/**
 * Bookings Module
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Bookings;

use AquaLuxe\Core\Abstracts\Abstract_Module;

defined('ABSPATH') || exit;

/**
 * Bookings Module Class
 */
class Module extends Abstract_Module {

    /**
     * Module name
     *
     * @var string
     */
    protected $name = 'Bookings';

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
        add_action('wp_ajax_aqualuxe_create_booking', array($this, 'handle_booking'));
        add_action('wp_ajax_nopriv_aqualuxe_create_booking', array($this, 'handle_booking'));
        add_action('wp_ajax_aqualuxe_get_available_slots', array($this, 'get_available_slots'));
        add_action('wp_ajax_nopriv_aqualuxe_get_available_slots', array($this, 'get_available_slots'));
        add_shortcode('aqualuxe_booking_calendar', array($this, 'booking_calendar_shortcode'));
        add_shortcode('aqualuxe_booking_form', array($this, 'booking_form_shortcode'));
        
        // Add custom database table for bookings
        add_action('init', array($this, 'create_bookings_table'));
    }

    /**
     * Create bookings database table
     */
    public function create_bookings_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'aqualuxe_bookings';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id int(11) NOT NULL AUTO_INCREMENT,
            service_id int(11) NOT NULL,
            user_id int(11) NOT NULL,
            booking_date date NOT NULL,
            booking_time time NOT NULL,
            duration int(11) DEFAULT 60,
            status varchar(20) DEFAULT 'pending',
            customer_name varchar(255) NOT NULL,
            customer_email varchar(255) NOT NULL,
            customer_phone varchar(50),
            notes text,
            total_cost decimal(10,2) DEFAULT 0.00,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY service_id (service_id),
            KEY user_id (user_id),
            KEY booking_date (booking_date),
            KEY status (status)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Register bookings post type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Bookings', 'Post type general name', 'aqualuxe'),
            'singular_name'         => _x('Booking', 'Post type singular name', 'aqualuxe'),
            'menu_name'             => _x('Bookings', 'Admin Menu text', 'aqualuxe'),
            'name_admin_bar'        => _x('Booking', 'Add New on Toolbar', 'aqualuxe'),
            'add_new'               => __('Add New', 'aqualuxe'),
            'add_new_item'          => __('Add New Booking', 'aqualuxe'),
            'new_item'              => __('New Booking', 'aqualuxe'),
            'edit_item'             => __('Edit Booking', 'aqualuxe'),
            'view_item'             => __('View Booking', 'aqualuxe'),
            'all_items'             => __('All Bookings', 'aqualuxe'),
            'search_items'          => __('Search Bookings', 'aqualuxe'),
            'not_found'             => __('No bookings found.', 'aqualuxe'),
            'not_found_in_trash'    => __('No bookings found in Trash.', 'aqualuxe'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'query_var'          => true,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 30,
            'menu_icon'          => 'dashicons-calendar-alt',
            'supports'           => array('title', 'custom-fields'),
        );

        register_post_type('aqualuxe_booking', $args);
    }

    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Booking Status
        $status_labels = array(
            'name'              => _x('Booking Status', 'taxonomy general name', 'aqualuxe'),
            'singular_name'     => _x('Status', 'taxonomy singular name', 'aqualuxe'),
            'search_items'      => __('Search Statuses', 'aqualuxe'),
            'all_items'         => __('All Statuses', 'aqualuxe'),
            'edit_item'         => __('Edit Status', 'aqualuxe'),
            'update_item'       => __('Update Status', 'aqualuxe'),
            'add_new_item'      => __('Add New Status', 'aqualuxe'),
            'new_item_name'     => __('New Status Name', 'aqualuxe'),
            'menu_name'         => __('Status', 'aqualuxe'),
        );

        register_taxonomy('aqualuxe_booking_status', array('aqualuxe_booking'), array(
            'hierarchical'      => false,
            'labels'            => $status_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'booking-status'),
        ));
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'aqualuxe_booking_details',
            __('Booking Details', 'aqualuxe'),
            array($this, 'booking_details_meta_box'),
            'aqualuxe_booking',
            'normal',
            'high'
        );

        add_meta_box(
            'aqualuxe_booking_customer',
            __('Customer Information', 'aqualuxe'),
            array($this, 'booking_customer_meta_box'),
            'aqualuxe_booking',
            'side',
            'default'
        );
    }

    /**
     * Booking details meta box
     */
    public function booking_details_meta_box($post) {
        wp_nonce_field('aqualuxe_booking_nonce', 'aqualuxe_booking_nonce');
        
        $service_id = get_post_meta($post->ID, '_booking_service_id', true);
        $booking_date = get_post_meta($post->ID, '_booking_date', true);
        $booking_time = get_post_meta($post->ID, '_booking_time', true);
        $duration = get_post_meta($post->ID, '_booking_duration', true);
        $notes = get_post_meta($post->ID, '_booking_notes', true);
        
        // Get available services
        $services = get_posts(array(
            'post_type' => 'aqualuxe_service',
            'numberposts' => -1,
            'post_status' => 'publish'
        ));
        ?>
        <table class="form-table">
            <tr>
                <th><label for="booking_service_id"><?php _e('Service', 'aqualuxe'); ?></label></th>
                <td>
                    <select name="booking_service_id" id="booking_service_id" required>
                        <option value=""><?php _e('Select Service', 'aqualuxe'); ?></option>
                        <?php foreach ($services as $service) : ?>
                            <option value="<?php echo $service->ID; ?>" <?php selected($service_id, $service->ID); ?>>
                                <?php echo esc_html($service->post_title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="booking_date"><?php _e('Date', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="date" name="booking_date" id="booking_date" value="<?php echo esc_attr($booking_date); ?>" required />
                </td>
            </tr>
            <tr>
                <th><label for="booking_time"><?php _e('Time', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="time" name="booking_time" id="booking_time" value="<?php echo esc_attr($booking_time); ?>" required />
                </td>
            </tr>
            <tr>
                <th><label for="booking_duration"><?php _e('Duration (minutes)', 'aqualuxe'); ?></label></th>
                <td>
                    <select name="booking_duration" id="booking_duration">
                        <option value="30" <?php selected($duration, 30); ?>>30 minutes</option>
                        <option value="60" <?php selected($duration, 60); ?>>1 hour</option>
                        <option value="90" <?php selected($duration, 90); ?>>1.5 hours</option>
                        <option value="120" <?php selected($duration, 120); ?>>2 hours</option>
                        <option value="180" <?php selected($duration, 180); ?>>3 hours</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="booking_notes"><?php _e('Notes', 'aqualuxe'); ?></label></th>
                <td>
                    <textarea name="booking_notes" id="booking_notes" rows="4" cols="50"><?php echo esc_textarea($notes); ?></textarea>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Booking customer meta box
     */
    public function booking_customer_meta_box($post) {
        $customer_name = get_post_meta($post->ID, '_booking_customer_name', true);
        $customer_email = get_post_meta($post->ID, '_booking_customer_email', true);
        $customer_phone = get_post_meta($post->ID, '_booking_customer_phone', true);
        $total_cost = get_post_meta($post->ID, '_booking_total_cost', true);
        ?>
        <p>
            <label for="booking_customer_name"><?php _e('Name', 'aqualuxe'); ?></label>
            <input type="text" name="booking_customer_name" id="booking_customer_name" value="<?php echo esc_attr($customer_name); ?>" style="width: 100%;" required />
        </p>
        <p>
            <label for="booking_customer_email"><?php _e('Email', 'aqualuxe'); ?></label>
            <input type="email" name="booking_customer_email" id="booking_customer_email" value="<?php echo esc_attr($customer_email); ?>" style="width: 100%;" required />
        </p>
        <p>
            <label for="booking_customer_phone"><?php _e('Phone', 'aqualuxe'); ?></label>
            <input type="tel" name="booking_customer_phone" id="booking_customer_phone" value="<?php echo esc_attr($customer_phone); ?>" style="width: 100%;" />
        </p>
        <p>
            <label for="booking_total_cost"><?php _e('Total Cost', 'aqualuxe'); ?></label>
            <input type="number" name="booking_total_cost" id="booking_total_cost" value="<?php echo esc_attr($total_cost); ?>" step="0.01" min="0" style="width: 100%;" />
        </p>
        <?php
    }

    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['aqualuxe_booking_nonce']) || !wp_verify_nonce($_POST['aqualuxe_booking_nonce'], 'aqualuxe_booking_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = array(
            'booking_service_id',
            'booking_date',
            'booking_time',
            'booking_duration',
            'booking_notes',
            'booking_customer_name',
            'booking_customer_email',
            'booking_customer_phone',
            'booking_total_cost'
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }

        // Also update the custom table
        $this->update_booking_in_table($post_id);
    }

    /**
     * Update booking in custom table
     */
    private function update_booking_in_table($post_id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_bookings';
        
        $data = array(
            'service_id' => get_post_meta($post_id, '_booking_service_id', true),
            'booking_date' => get_post_meta($post_id, '_booking_date', true),
            'booking_time' => get_post_meta($post_id, '_booking_time', true),
            'duration' => get_post_meta($post_id, '_booking_duration', true),
            'customer_name' => get_post_meta($post_id, '_booking_customer_name', true),
            'customer_email' => get_post_meta($post_id, '_booking_customer_email', true),
            'customer_phone' => get_post_meta($post_id, '_booking_customer_phone', true),
            'notes' => get_post_meta($post_id, '_booking_notes', true),
            'total_cost' => get_post_meta($post_id, '_booking_total_cost', true),
        );

        // Check if record exists
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table_name WHERE id = %d",
            $post_id
        ));

        if ($existing) {
            $wpdb->update($table_name, $data, array('id' => $post_id));
        } else {
            $data['id'] = $post_id;
            $wpdb->insert($table_name, $data);
        }
    }

    /**
     * Enqueue assets
     */
    public function enqueue_assets() {
        if (is_page() || is_singular('aqualuxe_service')) {
            wp_enqueue_script('aqualuxe-bookings', $this->get_url() . '/assets/bookings.js', array('jquery'), '1.0.0', true);
            wp_enqueue_style('aqualuxe-bookings', $this->get_url() . '/assets/bookings.css', array(), '1.0.0');
            
            wp_localize_script('aqualuxe-bookings', 'aqualuxe_bookings', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe_bookings_nonce'),
                'currency_symbol' => get_woocommerce_currency_symbol(),
                'date_format' => get_option('date_format'),
                'time_format' => get_option('time_format'),
            ));
        }
    }

    /**
     * Handle booking creation
     */
    public function handle_booking() {
        check_ajax_referer('aqualuxe_bookings_nonce', 'nonce');

        $service_id = intval($_POST['service_id']);
        $booking_date = sanitize_text_field($_POST['booking_date']);
        $booking_time = sanitize_text_field($_POST['booking_time']);
        $customer_name = sanitize_text_field($_POST['customer_name']);
        $customer_email = sanitize_email($_POST['customer_email']);
        $customer_phone = sanitize_text_field($_POST['customer_phone']);
        $notes = sanitize_textarea_field($_POST['notes']);

        // Validate required fields
        if (!$service_id || !$booking_date || !$booking_time || !$customer_name || !$customer_email) {
            wp_send_json_error('Please fill in all required fields');
        }

        // Check if slot is available
        if (!$this->is_slot_available($service_id, $booking_date, $booking_time)) {
            wp_send_json_error('Selected time slot is not available');
        }

        // Create booking post
        $booking_data = array(
            'post_type' => 'aqualuxe_booking',
            'post_title' => sprintf('%s - %s %s', $customer_name, $booking_date, $booking_time),
            'post_status' => 'publish',
            'meta_input' => array(
                '_booking_service_id' => $service_id,
                '_booking_date' => $booking_date,
                '_booking_time' => $booking_time,
                '_booking_customer_name' => $customer_name,
                '_booking_customer_email' => $customer_email,
                '_booking_customer_phone' => $customer_phone,
                '_booking_notes' => $notes,
                '_booking_duration' => 60, // Default 1 hour
            )
        );

        $booking_id = wp_insert_post($booking_data);

        if ($booking_id) {
            // Send confirmation email
            $this->send_booking_confirmation($booking_id);
            wp_send_json_success('Booking created successfully');
        } else {
            wp_send_json_error('Failed to create booking');
        }
    }

    /**
     * Get available time slots
     */
    public function get_available_slots() {
        check_ajax_referer('aqualuxe_bookings_nonce', 'nonce');

        $service_id = intval($_POST['service_id']);
        $date = sanitize_text_field($_POST['date']);

        if (!$service_id || !$date) {
            wp_send_json_error('Invalid parameters');
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'aqualuxe_bookings';

        // Get booked slots for the date
        $booked_slots = $wpdb->get_col($wpdb->prepare(
            "SELECT booking_time FROM $table_name WHERE service_id = %d AND booking_date = %s AND status != 'cancelled'",
            $service_id,
            $date
        ));

        // Generate available slots (9 AM to 6 PM, hourly)
        $available_slots = array();
        for ($hour = 9; $hour <= 18; $hour++) {
            $time_slot = sprintf('%02d:00', $hour);
            if (!in_array($time_slot, $booked_slots)) {
                $available_slots[] = $time_slot;
            }
        }

        wp_send_json_success($available_slots);
    }

    /**
     * Check if a time slot is available
     */
    private function is_slot_available($service_id, $date, $time) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aqualuxe_bookings';

        $count = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE service_id = %d AND booking_date = %s AND booking_time = %s AND status != 'cancelled'",
            $service_id,
            $date,
            $time
        ));

        return $count == 0;
    }

    /**
     * Send booking confirmation email
     */
    private function send_booking_confirmation($booking_id) {
        $customer_email = get_post_meta($booking_id, '_booking_customer_email', true);
        $customer_name = get_post_meta($booking_id, '_booking_customer_name', true);
        $booking_date = get_post_meta($booking_id, '_booking_date', true);
        $booking_time = get_post_meta($booking_id, '_booking_time', true);

        $subject = sprintf(__('Booking Confirmation - %s', 'aqualuxe'), get_bloginfo('name'));
        
        $message = sprintf(
            __('Dear %s,\n\nYour booking has been confirmed for %s at %s.\n\nThank you for choosing AquaLuxe!', 'aqualuxe'),
            $customer_name,
            $booking_date,
            $booking_time
        );

        wp_mail($customer_email, $subject, $message);
    }

    /**
     * Booking calendar shortcode
     */
    public function booking_calendar_shortcode($atts) {
        $atts = shortcode_atts(array(
            'service_id' => 0
        ), $atts);

        ob_start();
        $this->load_template('booking-calendar', array('service_id' => $atts['service_id']));
        return ob_get_clean();
    }

    /**
     * Booking form shortcode
     */
    public function booking_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'service_id' => 0
        ), $atts);

        ob_start();
        $this->load_template('booking-form', array('service_id' => $atts['service_id']));
        return ob_get_clean();
    }
}