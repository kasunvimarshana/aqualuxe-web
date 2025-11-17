<?php
/**
 * Bookings & Scheduling Module
 *
 * Handles service bookings, appointments, and scheduling functionality
 *
 * @package AquaLuxe\Modules\Bookings
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Bookings Module Class
 */
class AquaLuxe_Bookings_Module {
    
    /**
     * Initialize the module
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Register AJAX handlers
        aqualuxe_secure_ajax_handler('create_booking', array($this, 'ajax_create_booking'), false, true);
        aqualuxe_secure_ajax_handler('get_available_slots', array($this, 'ajax_get_available_slots'), false, true);
        aqualuxe_secure_ajax_handler('cancel_booking', array($this, 'ajax_cancel_booking'), true, true);
        
        // Admin hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        
        // Email notifications
        add_action('aqualuxe_booking_created', array($this, 'send_booking_confirmation'), 10, 1);
        add_action('aqualuxe_booking_cancelled', array($this, 'send_cancellation_email'), 10, 1);
        
        // Shortcodes
        add_shortcode('aqualuxe_booking_form', array($this, 'booking_form_shortcode'));
        add_shortcode('aqualuxe_my_bookings', array($this, 'my_bookings_shortcode'));
    }

    /**
     * Initialize module
     */
    public function init() {
        $this->create_tables();
        $this->register_post_types();
        $this->schedule_cleanup();
    }

    /**
     * Create database tables
     */
    private function create_tables() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'aqualuxe_bookings';
        
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) DEFAULT NULL,
            service_id bigint(20) NOT NULL,
            booking_date datetime NOT NULL,
            duration int(11) DEFAULT 60,
            status varchar(20) DEFAULT 'pending',
            customer_name varchar(100) NOT NULL,
            customer_email varchar(100) NOT NULL,
            customer_phone varchar(20) DEFAULT '',
            notes text DEFAULT '',
            booking_hash varchar(32) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY service_id (service_id),
            KEY booking_date (booking_date),
            KEY status (status),
            UNIQUE KEY booking_hash (booking_hash)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // Create availability table
        $availability_table = $wpdb->prefix . 'aqualuxe_availability';
        
        $availability_sql = "CREATE TABLE $availability_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            service_id bigint(20) NOT NULL,
            day_of_week tinyint(1) NOT NULL,
            start_time time NOT NULL,
            end_time time NOT NULL,
            is_available tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY service_id (service_id),
            KEY day_of_week (day_of_week)
        ) $charset_collate;";

        dbDelta($availability_sql);
    }

    /**
     * Register custom post types
     */
    private function register_post_types() {
        // Service post type is already registered in custom-post-types.php
        // Add booking-specific meta boxes
        add_action('add_meta_boxes', array($this, 'add_service_meta_boxes'));
        add_action('save_post', array($this, 'save_service_meta'));
    }

    /**
     * Add service meta boxes
     */
    public function add_service_meta_boxes() {
        add_meta_box(
            'aqualuxe-service-booking',
            __('Booking Settings', 'aqualuxe'),
            array($this, 'service_booking_meta_box'),
            'aqualuxe_service',
            'normal',
            'high'
        );
    }

    /**
     * Service booking meta box
     */
    public function service_booking_meta_box($post) {
        wp_nonce_field(basename(__FILE__), 'aqualuxe_service_booking_nonce');
        
        $bookable = get_post_meta($post->ID, '_aqualuxe_bookable', true);
        $duration = get_post_meta($post->ID, '_aqualuxe_duration', true) ?: 60;
        $price = get_post_meta($post->ID, '_aqualuxe_price', true);
        $advance_booking = get_post_meta($post->ID, '_aqualuxe_advance_booking', true) ?: 24;
        $max_advance = get_post_meta($post->ID, '_aqualuxe_max_advance', true) ?: 30;
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="aqualuxe_bookable"><?php _e('Bookable Service', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="checkbox" id="aqualuxe_bookable" name="aqualuxe_bookable" value="1" <?php checked($bookable, '1'); ?>>
                    <label for="aqualuxe_bookable"><?php _e('Enable online booking for this service', 'aqualuxe'); ?></label>
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_duration"><?php _e('Duration (minutes)', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="number" id="aqualuxe_duration" name="aqualuxe_duration" value="<?php echo esc_attr($duration); ?>" min="15" max="480" step="15">
                    <p class="description"><?php _e('Default duration for this service in minutes', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_price"><?php _e('Price', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="number" id="aqualuxe_price" name="aqualuxe_price" value="<?php echo esc_attr($price); ?>" step="0.01" min="0">
                    <p class="description"><?php _e('Service price (leave empty for quote-based services)', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_advance_booking"><?php _e('Minimum Advance Booking (hours)', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="number" id="aqualuxe_advance_booking" name="aqualuxe_advance_booking" value="<?php echo esc_attr($advance_booking); ?>" min="1" max="168">
                    <p class="description"><?php _e('Minimum hours in advance required for booking', 'aqualuxe'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="aqualuxe_max_advance"><?php _e('Maximum Advance Booking (days)', 'aqualuxe'); ?></label></th>
                <td>
                    <input type="number" id="aqualuxe_max_advance" name="aqualuxe_max_advance" value="<?php echo esc_attr($max_advance); ?>" min="1" max="365">
                    <p class="description"><?php _e('Maximum days in advance bookings can be made', 'aqualuxe'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save service meta
     */
    public function save_service_meta($post_id) {
        if (!isset($_POST['aqualuxe_service_booking_nonce']) || 
            !wp_verify_nonce($_POST['aqualuxe_service_booking_nonce'], basename(__FILE__))) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if (get_post_type($post_id) !== 'aqualuxe_service') {
            return;
        }

        $fields = array(
            'aqualuxe_bookable' => 'boolean',
            'aqualuxe_duration' => 'integer',
            'aqualuxe_price' => 'float',
            'aqualuxe_advance_booking' => 'integer',
            'aqualuxe_max_advance' => 'integer'
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
                default:
                    $value = sanitize_text_field($value);
            }
            
            update_post_meta($post_id, '_' . $field, $value);
        }
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        if ($this->should_load_booking_scripts()) {
            wp_enqueue_script(
                'aqualuxe-bookings',
                AQUALUXE_ASSETS_URI . '/js/modules/bookings.js',
                array('jquery'),
                AQUALUXE_VERSION,
                true
            );

            wp_localize_script('aqualuxe-bookings', 'aqualuxe_bookings', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => aqualuxe_create_nonce('bookings'),
                'strings' => array(
                    'select_date' => __('Please select a date', 'aqualuxe'),
                    'select_time' => __('Please select a time slot', 'aqualuxe'),
                    'booking_success' => __('Booking created successfully!', 'aqualuxe'),
                    'booking_error' => __('Error creating booking. Please try again.', 'aqualuxe'),
                    'loading' => __('Loading...', 'aqualuxe'),
                    'confirm_cancel' => __('Are you sure you want to cancel this booking?', 'aqualuxe'),
                )
            ));

            // Enqueue date picker styles
            wp_enqueue_style('jquery-ui-datepicker');
            wp_enqueue_script('jquery-ui-datepicker');
        }
    }

    /**
     * Check if booking scripts should be loaded
     */
    private function should_load_booking_scripts() {
        global $post;
        
        return (
            is_page_template('templates/page-bookings.php') ||
            is_singular('aqualuxe_service') ||
            (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'aqualuxe_booking_form')) ||
            (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'aqualuxe_my_bookings'))
        );
    }

    /**
     * Create booking via AJAX
     */
    public function ajax_create_booking($data) {
        // Validate required fields
        $required_fields = array('service_id', 'booking_date', 'customer_name', 'customer_email');
        
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                wp_send_json_error(__('Missing required field: ' . $field, 'aqualuxe'));
            }
        }

        // Validate service exists and is bookable
        $service_id = intval($data['service_id']);
        $service = get_post($service_id);
        
        if (!$service || $service->post_type !== 'aqualuxe_service') {
            wp_send_json_error(__('Invalid service', 'aqualuxe'));
        }

        $is_bookable = get_post_meta($service_id, '_aqualuxe_bookable', true);
        if (!$is_bookable) {
            wp_send_json_error(__('This service is not available for online booking', 'aqualuxe'));
        }

        // Validate booking date
        $booking_date = sanitize_text_field($data['booking_date']);
        $booking_datetime = DateTime::createFromFormat('Y-m-d H:i', $booking_date);
        
        if (!$booking_datetime) {
            wp_send_json_error(__('Invalid booking date format', 'aqualuxe'));
        }

        // Check availability
        if (!$this->is_slot_available($service_id, $booking_datetime)) {
            wp_send_json_error(__('Selected time slot is not available', 'aqualuxe'));
        }

        // Create booking
        $booking_id = $this->create_booking(array(
            'service_id' => $service_id,
            'booking_date' => $booking_datetime->format('Y-m-d H:i:s'),
            'customer_name' => sanitize_text_field($data['customer_name']),
            'customer_email' => sanitize_email($data['customer_email']),
            'customer_phone' => sanitize_text_field($data['customer_phone'] ?? ''),
            'notes' => sanitize_textarea_field($data['notes'] ?? ''),
            'user_id' => get_current_user_id() ?: null
        ));

        if ($booking_id) {
            do_action('aqualuxe_booking_created', $booking_id);
            
            wp_send_json_success(array(
                'booking_id' => $booking_id,
                'message' => __('Booking created successfully!', 'aqualuxe'),
                'redirect' => home_url('/booking-confirmation/?booking=' . $this->get_booking_hash($booking_id))
            ));
        } else {
            wp_send_json_error(__('Failed to create booking. Please try again.', 'aqualuxe'));
        }
    }

    /**
     * Get available slots via AJAX
     */
    public function ajax_get_available_slots($data) {
        if (empty($data['service_id']) || empty($data['date'])) {
            wp_send_json_error(__('Missing required parameters', 'aqualuxe'));
        }

        $service_id = intval($data['service_id']);
        $date = sanitize_text_field($data['date']);
        
        $slots = $this->get_available_slots($service_id, $date);
        
        wp_send_json_success(array('slots' => $slots));
    }

    /**
     * Cancel booking via AJAX
     */
    public function ajax_cancel_booking($data) {
        if (empty($data['booking_id'])) {
            wp_send_json_error(__('Missing booking ID', 'aqualuxe'));
        }

        $booking_id = intval($data['booking_id']);
        $booking = $this->get_booking($booking_id);

        if (!$booking) {
            wp_send_json_error(__('Booking not found', 'aqualuxe'));
        }

        // Check permissions
        if (!current_user_can('manage_options') && 
            $booking->user_id != get_current_user_id()) {
            wp_send_json_error(__('Insufficient permissions', 'aqualuxe'));
        }

        if ($this->cancel_booking($booking_id)) {
            do_action('aqualuxe_booking_cancelled', $booking_id);
            
            wp_send_json_success(array(
                'message' => __('Booking cancelled successfully', 'aqualuxe')
            ));
        } else {
            wp_send_json_error(__('Failed to cancel booking', 'aqualuxe'));
        }
    }

    /**
     * Create a new booking
     */
    private function create_booking($data) {
        global $wpdb;

        $table_name = $wpdb->prefix . 'aqualuxe_bookings';
        
        $booking_data = array(
            'user_id' => $data['user_id'],
            'service_id' => $data['service_id'],
            'booking_date' => $data['booking_date'],
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'],
            'customer_phone' => $data['customer_phone'],
            'notes' => $data['notes'],
            'booking_hash' => md5(uniqid($data['customer_email'], true)),
            'status' => 'pending'
        );

        $duration = get_post_meta($data['service_id'], '_aqualuxe_duration', true);
        if ($duration) {
            $booking_data['duration'] = intval($duration);
        }

        $result = $wpdb->insert($table_name, $booking_data);
        
        return $result ? $wpdb->insert_id : false;
    }

    /**
     * Get available time slots for a service on a specific date
     */
    private function get_available_slots($service_id, $date) {
        global $wpdb;

        $date_obj = DateTime::createFromFormat('Y-m-d', $date);
        if (!$date_obj) {
            return array();
        }

        $day_of_week = $date_obj->format('w'); // 0 = Sunday
        
        // Get availability for this day
        $availability_table = $wpdb->prefix . 'aqualuxe_availability';
        $availability = $wpdb->get_results($wpdb->prepare(
            "SELECT start_time, end_time FROM $availability_table 
             WHERE service_id = %d AND day_of_week = %d AND is_available = 1",
            $service_id, $day_of_week
        ));

        if (empty($availability)) {
            // Default business hours if no specific availability set
            $availability = array((object) array(
                'start_time' => '09:00:00',
                'end_time' => '17:00:00'
            ));
        }

        $duration = get_post_meta($service_id, '_aqualuxe_duration', true) ?: 60;
        $advance_booking = get_post_meta($service_id, '_aqualuxe_advance_booking', true) ?: 24;
        
        $slots = array();
        $now = new DateTime();
        $min_booking_time = clone $now;
        $min_booking_time->add(new DateInterval('PT' . $advance_booking . 'H'));

        foreach ($availability as $period) {
            $start = DateTime::createFromFormat('Y-m-d H:i:s', $date . ' ' . $period->start_time);
            $end = DateTime::createFromFormat('Y-m-d H:i:s', $date . ' ' . $period->end_time);
            
            while ($start->getTimestamp() + ($duration * 60) <= $end->getTimestamp()) {
                $slot_end = clone $start;
                $slot_end->add(new DateInterval('PT' . $duration . 'M'));
                
                // Check if slot is in the future with minimum advance time
                if ($start >= $min_booking_time) {
                    // Check if slot is available (not already booked)
                    if ($this->is_slot_available($service_id, $start)) {
                        $slots[] = array(
                            'time' => $start->format('H:i'),
                            'datetime' => $start->format('Y-m-d H:i'),
                            'formatted' => $start->format('g:i A')
                        );
                    }
                }
                
                $start->add(new DateInterval('PT30M')); // 30-minute intervals
            }
        }

        return $slots;
    }

    /**
     * Check if a time slot is available
     */
    private function is_slot_available($service_id, $datetime) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_bookings';
        $duration = get_post_meta($service_id, '_aqualuxe_duration', true) ?: 60;
        
        $start_time = $datetime->format('Y-m-d H:i:s');
        $end_time = clone $datetime;
        $end_time->add(new DateInterval('PT' . $duration . 'M'));
        $end_time = $end_time->format('Y-m-d H:i:s');

        $conflicts = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name 
             WHERE service_id = %d 
             AND status NOT IN ('cancelled', 'rejected')
             AND (
                 (booking_date < %s AND DATE_ADD(booking_date, INTERVAL duration MINUTE) > %s) OR
                 (booking_date < %s AND DATE_ADD(booking_date, INTERVAL duration MINUTE) > %s) OR
                 (booking_date >= %s AND booking_date < %s)
             )",
            $service_id, $start_time, $start_time, $end_time, $end_time, $start_time, $end_time
        ));

        return $conflicts == 0;
    }

    /**
     * Get booking by ID
     */
    private function get_booking($booking_id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_bookings';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE id = %d",
            $booking_id
        ));
    }

    /**
     * Cancel booking
     */
    private function cancel_booking($booking_id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_bookings';
        
        $result = $wpdb->update(
            $table_name,
            array('status' => 'cancelled'),
            array('id' => $booking_id),
            array('%s'),
            array('%d')
        );

        return $result !== false;
    }

    /**
     * Get booking hash
     */
    private function get_booking_hash($booking_id) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_bookings';
        
        return $wpdb->get_var($wpdb->prepare(
            "SELECT booking_hash FROM $table_name WHERE id = %d",
            $booking_id
        ));
    }

    /**
     * Send booking confirmation email
     */
    public function send_booking_confirmation($booking_id) {
        $booking = $this->get_booking($booking_id);
        if (!$booking) {
            return;
        }

        $service = get_post($booking->service_id);
        $subject = sprintf(__('[%s] Booking Confirmation - %s', 'aqualuxe'), get_bloginfo('name'), $service->post_title);
        
        $message = $this->get_email_template('booking_confirmation', array(
            'booking' => $booking,
            'service' => $service
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
        $booking = $this->get_booking($booking_id);
        if (!$booking) {
            return;
        }

        $service = get_post($booking->service_id);
        $subject = sprintf(__('[%s] Booking Cancelled - %s', 'aqualuxe'), get_bloginfo('name'), $service->post_title);
        
        $message = $this->get_email_template('booking_cancellation', array(
            'booking' => $booking,
            'service' => $service
        ));

        $headers = array('Content-Type: text/html; charset=UTF-8');
        
        wp_mail($booking->customer_email, $subject, $message, $headers);
    }

    /**
     * Get email template
     */
    private function get_email_template($template, $data) {
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
        return $this->get_simple_email_template($template, $data);
    }

    /**
     * Get simple email template fallback
     */
    private function get_simple_email_template($template, $data) {
        $booking = $data['booking'];
        $service = $data['service'];
        
        if ($template === 'booking_confirmation') {
            return sprintf(
                __("Dear %s,\n\nYour booking for %s has been confirmed.\n\nDate: %s\nTime: %s\nDuration: %d minutes\n\nThank you for choosing AquaLuxe!", 'aqualuxe'),
                $booking->customer_name,
                $service->post_title,
                date_i18n(get_option('date_format'), strtotime($booking->booking_date)),
                date_i18n(get_option('time_format'), strtotime($booking->booking_date)),
                $booking->duration
            );
        } elseif ($template === 'booking_cancellation') {
            return sprintf(
                __("Dear %s,\n\nYour booking for %s has been cancelled.\n\nDate: %s\nTime: %s\n\nIf you have any questions, please contact us.", 'aqualuxe'),
                $booking->customer_name,
                $service->post_title,
                date_i18n(get_option('date_format'), strtotime($booking->booking_date)),
                date_i18n(get_option('time_format'), strtotime($booking->booking_date))
            );
        }
        
        return '';
    }

    /**
     * Booking form shortcode
     */
    public function booking_form_shortcode($atts) {
        $atts = shortcode_atts(array(
            'service_id' => 0,
            'show_services' => 'true'
        ), $atts);

        ob_start();
        $template_path = get_template_directory() . '/templates/shortcodes/booking-form.php';
        
        if (file_exists($template_path)) {
            // Set variables for template use
            foreach ($atts as $key => $value) {
                $$key = $value;
            }
            include $template_path;
        } else {
            echo '<div class="aqualuxe-booking-form">';
            echo '<p>' . __('Booking form template not found.', 'aqualuxe') . '</p>';
            echo '</div>';
        }
        
        return ob_get_clean();
    }

    /**
     * My bookings shortcode
     */
    public function my_bookings_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 10
        ), $atts);

        ob_start();
        $template_path = get_template_directory() . '/templates/shortcodes/my-bookings.php';
        
        if (file_exists($template_path)) {
            // Set variables for template use
            foreach ($atts as $key => $value) {
                $$key = $value;
            }
            include $template_path;
        } else {
            echo '<div class="aqualuxe-my-bookings">';
            echo '<p>' . __('My bookings template not found.', 'aqualuxe') . '</p>';
            echo '</div>';
        }
        
        return ob_get_clean();
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=aqualuxe_service',
            __('Bookings', 'aqualuxe'),
            __('Bookings', 'aqualuxe'),
            'manage_options',
            'aqualuxe-bookings',
            array($this, 'admin_bookings_page')
        );
    }

    /**
     * Admin bookings page
     */
    public function admin_bookings_page() {
        $template_path = get_template_directory() . '/templates/admin/bookings.php';
        
        if (file_exists($template_path)) {
            include $template_path;
        } else {
            echo '<div class="wrap">';
            echo '<h1>' . __('Bookings', 'aqualuxe') . '</h1>';
            echo '<p>' . __('Admin bookings template not found.', 'aqualuxe') . '</p>';
            echo '</div>';
        }
    }

    /**
     * Schedule cleanup of old bookings
     */
    private function schedule_cleanup() {
        if (!wp_next_scheduled('aqualuxe_cleanup_old_bookings')) {
            wp_schedule_event(time(), 'daily', 'aqualuxe_cleanup_old_bookings');
        }
    }

    /**
     * Admin init
     */
    public function admin_init() {
        add_action('aqualuxe_cleanup_old_bookings', array($this, 'cleanup_old_bookings'));
    }

    /**
     * Cleanup old bookings
     */
    public function cleanup_old_bookings() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_bookings';
        
        // Delete cancelled bookings older than 30 days
        $wpdb->query($wpdb->prepare(
            "DELETE FROM $table_name 
             WHERE status = 'cancelled' 
             AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)"
        ));
        
        // Delete completed bookings older than 1 year
        $wpdb->query($wpdb->prepare(
            "DELETE FROM $table_name 
             WHERE status = 'completed' 
             AND booking_date < DATE_SUB(NOW(), INTERVAL 1 YEAR)"
        ));
    }
}

// Initialize the module
new AquaLuxe_Bookings_Module();

/**
 * Helper functions
 */
function aqualuxe_get_booking_form($service_id = 0) {
    return do_shortcode('[aqualuxe_booking_form service_id="' . intval($service_id) . '"]');
}

function aqualuxe_get_my_bookings($limit = 10) {
    return do_shortcode('[aqualuxe_my_bookings limit="' . intval($limit) . '"]');
}