<?php
/**
 * Bookings Settings
 *
 * Handles settings for the bookings module.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Bookings Settings Class
 */
class AquaLuxe_Bookings_Settings {
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize hooks
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_menu', array($this, 'add_settings_page'), 20);
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        
        // Add settings link to plugins page
        add_filter('plugin_action_links_' . plugin_basename(AQUALUXE_BOOKINGS_PATH . 'bookings.php'), array($this, 'add_settings_link'));
    }

    /**
     * Register settings
     */
    public function register_settings() {
        // Register settings sections
        add_settings_section(
            'aqualuxe_bookings_general_settings',
            __('General Settings', 'aqualuxe'),
            array($this, 'general_settings_section_callback'),
            'aqualuxe_bookings_settings'
        );
        
        add_settings_section(
            'aqualuxe_bookings_display_settings',
            __('Display Settings', 'aqualuxe'),
            array($this, 'display_settings_section_callback'),
            'aqualuxe_bookings_settings'
        );
        
        add_settings_section(
            'aqualuxe_bookings_notification_settings',
            __('Notification Settings', 'aqualuxe'),
            array($this, 'notification_settings_section_callback'),
            'aqualuxe_bookings_settings'
        );
        
        add_settings_section(
            'aqualuxe_bookings_integration_settings',
            __('Integration Settings', 'aqualuxe'),
            array($this, 'integration_settings_section_callback'),
            'aqualuxe_bookings_settings'
        );
        
        // Register general settings fields
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_page_id');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_confirmation_page_id');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_booking_confirmation');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_cancellation_policy');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_terms_page_id');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_require_account');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_allow_guest_bookings');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_minimum_notice');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_maximum_advance');
        
        // Register display settings fields
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_calendar_first_day');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_time_format');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_buffer_time');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_min_booking_time');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_max_booking_time');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_business_hours');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_color_scheme');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_form_style');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_calendar_style');
        
        // Register notification settings fields
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_notification_emails');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_admin_notification_email');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_customer_notification_email');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_admin_email_subject');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_admin_email_template');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_customer_email_subject');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_customer_email_template');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_reminder_emails');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_reminder_time');
        
        // Register integration settings fields
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_enable_google_calendar');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_google_calendar_id');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_google_calendar_api_key');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_enable_ical');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_ical_feed_url');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_enable_woocommerce');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_product_id');
    }

    /**
     * Add settings page
     */
    public function add_settings_page() {
        add_submenu_page(
            'aqualuxe-bookings',
            __('Bookings Settings', 'aqualuxe'),
            __('Settings', 'aqualuxe'),
            'manage_options',
            'aqualuxe-bookings-settings',
            array($this, 'settings_page')
        );
    }

    /**
     * Enqueue admin scripts
     *
     * @param string $hook Current admin page
     */
    public function admin_scripts($hook) {
        if ($hook !== 'bookings_page_aqualuxe-bookings-settings') {
            return;
        }
        
        // Enqueue color picker
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        // Enqueue settings script
        wp_enqueue_script(
            'aqualuxe-bookings-settings',
            AQUALUXE_BOOKINGS_URL . 'assets/js/admin-settings.js',
            array('jquery', 'wp-color-picker'),
            AQUALUXE_BOOKINGS_VERSION,
            true
        );
    }

    /**
     * Add settings link to plugins page
     *
     * @param array $links Plugin action links
     * @return array Modified plugin action links
     */
    public function add_settings_link($links) {
        $settings_link = '<a href="' . admin_url('admin.php?page=aqualuxe-bookings-settings') . '">' . __('Settings', 'aqualuxe') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     * Settings page
     */
    public function settings_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Get current tab
        $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'general';
        
        // Define tabs
        $tabs = array(
            'general' => __('General', 'aqualuxe'),
            'display' => __('Display', 'aqualuxe'),
            'notifications' => __('Notifications', 'aqualuxe'),
            'integrations' => __('Integrations', 'aqualuxe'),
        );
        
        // Include settings template
        include AQUALUXE_BOOKINGS_PATH . 'templates/admin/settings.php';
    }

    /**
     * General settings section callback
     */
    public function general_settings_section_callback() {
        echo '<p>' . __('Configure general settings for the bookings module.', 'aqualuxe') . '</p>';
    }

    /**
     * Display settings section callback
     */
    public function display_settings_section_callback() {
        echo '<p>' . __('Configure display settings for the bookings module.', 'aqualuxe') . '</p>';
    }

    /**
     * Notification settings section callback
     */
    public function notification_settings_section_callback() {
        echo '<p>' . __('Configure notification settings for the bookings module.', 'aqualuxe') . '</p>';
    }

    /**
     * Integration settings section callback
     */
    public function integration_settings_section_callback() {
        echo '<p>' . __('Configure integration settings for the bookings module.', 'aqualuxe') . '</p>';
    }

    /**
     * Get general settings fields
     *
     * @return array General settings fields
     */
    public function get_general_settings_fields() {
        return array(
            array(
                'name' => 'aqualuxe_bookings_page_id',
                'label' => __('Booking Page', 'aqualuxe'),
                'desc' => __('Select the page where the booking form will be displayed.', 'aqualuxe'),
                'type' => 'page',
                'default' => '',
            ),
            array(
                'name' => 'aqualuxe_bookings_confirmation_page_id',
                'label' => __('Confirmation Page', 'aqualuxe'),
                'desc' => __('Select the page where the booking confirmation will be displayed.', 'aqualuxe'),
                'type' => 'page',
                'default' => '',
            ),
            array(
                'name' => 'aqualuxe_bookings_booking_confirmation',
                'label' => __('Booking Confirmation', 'aqualuxe'),
                'desc' => __('Select how bookings should be confirmed.', 'aqualuxe'),
                'type' => 'select',
                'options' => array(
                    'manual' => __('Manual (Admin must confirm)', 'aqualuxe'),
                    'automatic' => __('Automatic (Confirmed immediately)', 'aqualuxe'),
                    'payment' => __('Payment (Confirmed after payment)', 'aqualuxe'),
                ),
                'default' => 'payment',
            ),
            array(
                'name' => 'aqualuxe_bookings_cancellation_policy',
                'label' => __('Cancellation Policy', 'aqualuxe'),
                'desc' => __('Enter your cancellation policy. This will be displayed on the booking form and included in confirmation emails.', 'aqualuxe'),
                'type' => 'textarea',
                'default' => '',
            ),
            array(
                'name' => 'aqualuxe_bookings_terms_page_id',
                'label' => __('Terms & Conditions Page', 'aqualuxe'),
                'desc' => __('Select the page containing your terms and conditions.', 'aqualuxe'),
                'type' => 'page',
                'default' => '',
            ),
            array(
                'name' => 'aqualuxe_bookings_require_account',
                'label' => __('Require Account', 'aqualuxe'),
                'desc' => __('Require users to be logged in to make a booking.', 'aqualuxe'),
                'type' => 'checkbox',
                'default' => 'no',
            ),
            array(
                'name' => 'aqualuxe_bookings_allow_guest_bookings',
                'label' => __('Allow Guest Bookings', 'aqualuxe'),
                'desc' => __('Allow users to make bookings without creating an account.', 'aqualuxe'),
                'type' => 'checkbox',
                'default' => 'yes',
            ),
            array(
                'name' => 'aqualuxe_bookings_minimum_notice',
                'label' => __('Minimum Notice', 'aqualuxe'),
                'desc' => __('Minimum notice required for bookings (in hours).', 'aqualuxe'),
                'type' => 'number',
                'default' => '1',
                'min' => '0',
                'step' => '1',
            ),
            array(
                'name' => 'aqualuxe_bookings_maximum_advance',
                'label' => __('Maximum Advance', 'aqualuxe'),
                'desc' => __('Maximum advance time for bookings (in days).', 'aqualuxe'),
                'type' => 'number',
                'default' => '90',
                'min' => '1',
                'step' => '1',
            ),
        );
    }

    /**
     * Get display settings fields
     *
     * @return array Display settings fields
     */
    public function get_display_settings_fields() {
        return array(
            array(
                'name' => 'aqualuxe_bookings_calendar_first_day',
                'label' => __('First Day of Week', 'aqualuxe'),
                'desc' => __('Select the first day of the week for the calendar.', 'aqualuxe'),
                'type' => 'select',
                'options' => array(
                    '0' => __('Sunday', 'aqualuxe'),
                    '1' => __('Monday', 'aqualuxe'),
                    '2' => __('Tuesday', 'aqualuxe'),
                    '3' => __('Wednesday', 'aqualuxe'),
                    '4' => __('Thursday', 'aqualuxe'),
                    '5' => __('Friday', 'aqualuxe'),
                    '6' => __('Saturday', 'aqualuxe'),
                ),
                'default' => '0',
            ),
            array(
                'name' => 'aqualuxe_bookings_time_format',
                'label' => __('Time Format', 'aqualuxe'),
                'desc' => __('Select the time format for the booking form.', 'aqualuxe'),
                'type' => 'select',
                'options' => array(
                    '12h' => __('12-hour (AM/PM)', 'aqualuxe'),
                    '24h' => __('24-hour', 'aqualuxe'),
                ),
                'default' => '12h',
            ),
            array(
                'name' => 'aqualuxe_bookings_buffer_time',
                'label' => __('Buffer Time', 'aqualuxe'),
                'desc' => __('Enter the buffer time in minutes between bookings.', 'aqualuxe'),
                'type' => 'number',
                'default' => '30',
                'min' => '0',
                'step' => '5',
            ),
            array(
                'name' => 'aqualuxe_bookings_min_booking_time',
                'label' => __('Minimum Booking Duration', 'aqualuxe'),
                'desc' => __('Enter the minimum booking duration in minutes.', 'aqualuxe'),
                'type' => 'number',
                'default' => '60',
                'min' => '5',
                'step' => '5',
            ),
            array(
                'name' => 'aqualuxe_bookings_max_booking_time',
                'label' => __('Maximum Booking Duration', 'aqualuxe'),
                'desc' => __('Enter the maximum booking duration in minutes.', 'aqualuxe'),
                'type' => 'number',
                'default' => '480',
                'min' => '5',
                'step' => '5',
            ),
            array(
                'name' => 'aqualuxe_bookings_business_hours',
                'label' => __('Business Hours', 'aqualuxe'),
                'desc' => __('Set your business hours for each day of the week.', 'aqualuxe'),
                'type' => 'business_hours',
                'default' => array(
                    '0' => array('open' => '', 'close' => ''), // Sunday (closed)
                    '1' => array('open' => '09:00', 'close' => '17:00'), // Monday
                    '2' => array('open' => '09:00', 'close' => '17:00'), // Tuesday
                    '3' => array('open' => '09:00', 'close' => '17:00'), // Wednesday
                    '4' => array('open' => '09:00', 'close' => '17:00'), // Thursday
                    '5' => array('open' => '09:00', 'close' => '17:00'), // Friday
                    '6' => array('open' => '', 'close' => ''), // Saturday (closed)
                ),
            ),
            array(
                'name' => 'aqualuxe_bookings_color_scheme',
                'label' => __('Color Scheme', 'aqualuxe'),
                'desc' => __('Select the color scheme for the booking form.', 'aqualuxe'),
                'type' => 'color',
                'default' => '#0073aa',
            ),
            array(
                'name' => 'aqualuxe_bookings_form_style',
                'label' => __('Form Style', 'aqualuxe'),
                'desc' => __('Select the style for the booking form.', 'aqualuxe'),
                'type' => 'select',
                'options' => array(
                    'default' => __('Default', 'aqualuxe'),
                    'compact' => __('Compact', 'aqualuxe'),
                    'modern' => __('Modern', 'aqualuxe'),
                ),
                'default' => 'default',
            ),
            array(
                'name' => 'aqualuxe_bookings_calendar_style',
                'label' => __('Calendar Style', 'aqualuxe'),
                'desc' => __('Select the style for the booking calendar.', 'aqualuxe'),
                'type' => 'select',
                'options' => array(
                    'default' => __('Default', 'aqualuxe'),
                    'minimal' => __('Minimal', 'aqualuxe'),
                    'modern' => __('Modern', 'aqualuxe'),
                ),
                'default' => 'default',
            ),
        );
    }

    /**
     * Get notification settings fields
     *
     * @return array Notification settings fields
     */
    public function get_notification_settings_fields() {
        return array(
            array(
                'name' => 'aqualuxe_bookings_notification_emails',
                'label' => __('Enable Notification Emails', 'aqualuxe'),
                'desc' => __('Enable or disable notification emails.', 'aqualuxe'),
                'type' => 'select',
                'options' => array(
                    'yes' => __('Yes', 'aqualuxe'),
                    'no' => __('No', 'aqualuxe'),
                ),
                'default' => 'yes',
            ),
            array(
                'name' => 'aqualuxe_bookings_admin_notification_email',
                'label' => __('Admin Notification Email', 'aqualuxe'),
                'desc' => __('Enter the email address to receive admin notifications.', 'aqualuxe'),
                'type' => 'email',
                'default' => get_option('admin_email'),
            ),
            array(
                'name' => 'aqualuxe_bookings_customer_notification_email',
                'label' => __('Customer Notification Email', 'aqualuxe'),
                'desc' => __('Enable or disable customer notification emails.', 'aqualuxe'),
                'type' => 'select',
                'options' => array(
                    'yes' => __('Yes', 'aqualuxe'),
                    'no' => __('No', 'aqualuxe'),
                ),
                'default' => 'yes',
            ),
            array(
                'name' => 'aqualuxe_bookings_admin_email_subject',
                'label' => __('Admin Email Subject', 'aqualuxe'),
                'desc' => __('Enter the subject for admin notification emails.', 'aqualuxe'),
                'type' => 'text',
                'default' => __('New Booking: {booking_id}', 'aqualuxe'),
            ),
            array(
                'name' => 'aqualuxe_bookings_admin_email_template',
                'label' => __('Admin Email Template', 'aqualuxe'),
                'desc' => __('Enter the template for admin notification emails. You can use the following placeholders: {booking_id}, {service_name}, {booking_date}, {booking_time}, {customer_name}, {customer_email}, {customer_phone}, {booking_status}, {booking_total}.', 'aqualuxe'),
                'type' => 'textarea',
                'default' => __("A new booking has been made on your website.\n\nBooking ID: {booking_id}\nService: {service_name}\nDate: {booking_date}\nTime: {booking_time}\nQuantity: {booking_quantity}\nTotal: {booking_total}\n\nCustomer Details:\nName: {customer_name}\nEmail: {customer_email}\nPhone: {customer_phone}\n\nView Booking: {booking_url}", 'aqualuxe'),
            ),
            array(
                'name' => 'aqualuxe_bookings_customer_email_subject',
                'label' => __('Customer Email Subject', 'aqualuxe'),
                'desc' => __('Enter the subject for customer notification emails.', 'aqualuxe'),
                'type' => 'text',
                'default' => __('Your Booking Confirmation: {booking_id}', 'aqualuxe'),
            ),
            array(
                'name' => 'aqualuxe_bookings_customer_email_template',
                'label' => __('Customer Email Template', 'aqualuxe'),
                'desc' => __('Enter the template for customer notification emails. You can use the following placeholders: {booking_id}, {service_name}, {booking_date}, {booking_time}, {customer_name}, {customer_email}, {customer_phone}, {booking_status}, {booking_total}.', 'aqualuxe'),
                'type' => 'textarea',
                'default' => __("Thank you for your booking with {site_name}.\n\nBooking ID: {booking_id}\nService: {service_name}\nDate: {booking_date}\nTime: {booking_time}\nQuantity: {booking_quantity}\nTotal: {booking_total}\n\n{status_message}\n\nIf you have any questions, please contact us at {admin_email}.\n\nThank you for choosing us!\n{site_name}", 'aqualuxe'),
            ),
            array(
                'name' => 'aqualuxe_bookings_reminder_emails',
                'label' => __('Enable Reminder Emails', 'aqualuxe'),
                'desc' => __('Enable or disable reminder emails.', 'aqualuxe'),
                'type' => 'select',
                'options' => array(
                    'yes' => __('Yes', 'aqualuxe'),
                    'no' => __('No', 'aqualuxe'),
                ),
                'default' => 'yes',
            ),
            array(
                'name' => 'aqualuxe_bookings_reminder_time',
                'label' => __('Reminder Time', 'aqualuxe'),
                'desc' => __('Enter the time in hours before the booking to send the reminder email.', 'aqualuxe'),
                'type' => 'number',
                'default' => '24',
                'min' => '1',
                'step' => '1',
            ),
        );
    }

    /**
     * Get integration settings fields
     *
     * @return array Integration settings fields
     */
    public function get_integration_settings_fields() {
        return array(
            array(
                'name' => 'aqualuxe_bookings_enable_google_calendar',
                'label' => __('Enable Google Calendar Integration', 'aqualuxe'),
                'desc' => __('Enable or disable Google Calendar integration.', 'aqualuxe'),
                'type' => 'select',
                'options' => array(
                    'yes' => __('Yes', 'aqualuxe'),
                    'no' => __('No', 'aqualuxe'),
                ),
                'default' => 'no',
            ),
            array(
                'name' => 'aqualuxe_bookings_google_calendar_id',
                'label' => __('Google Calendar ID', 'aqualuxe'),
                'desc' => __('Enter your Google Calendar ID.', 'aqualuxe'),
                'type' => 'text',
                'default' => '',
            ),
            array(
                'name' => 'aqualuxe_bookings_google_calendar_api_key',
                'label' => __('Google Calendar API Key', 'aqualuxe'),
                'desc' => __('Enter your Google Calendar API key.', 'aqualuxe'),
                'type' => 'text',
                'default' => '',
            ),
            array(
                'name' => 'aqualuxe_bookings_enable_ical',
                'label' => __('Enable iCal Integration', 'aqualuxe'),
                'desc' => __('Enable or disable iCal integration.', 'aqualuxe'),
                'type' => 'select',
                'options' => array(
                    'yes' => __('Yes', 'aqualuxe'),
                    'no' => __('No', 'aqualuxe'),
                ),
                'default' => 'no',
            ),
            array(
                'name' => 'aqualuxe_bookings_ical_feed_url',
                'label' => __('iCal Feed URL', 'aqualuxe'),
                'desc' => __('Enter the URL of your iCal feed.', 'aqualuxe'),
                'type' => 'text',
                'default' => '',
            ),
            array(
                'name' => 'aqualuxe_bookings_enable_woocommerce',
                'label' => __('Enable WooCommerce Integration', 'aqualuxe'),
                'desc' => __('Enable or disable WooCommerce integration.', 'aqualuxe'),
                'type' => 'select',
                'options' => array(
                    'yes' => __('Yes', 'aqualuxe'),
                    'no' => __('No', 'aqualuxe'),
                ),
                'default' => 'yes',
            ),
            array(
                'name' => 'aqualuxe_bookings_product_id',
                'label' => __('WooCommerce Product ID', 'aqualuxe'),
                'desc' => __('Enter the ID of the WooCommerce product to use for bookings.', 'aqualuxe'),
                'type' => 'number',
                'default' => '',
                'min' => '1',
                'step' => '1',
            ),
        );
    }

    /**
     * Render field
     *
     * @param array $field Field data
     */
    public function render_field($field) {
        $option_name = $field['name'];
        $value = get_option($option_name, $field['default']);
        
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="' . $option_name . '" id="' . $option_name . '" value="' . esc_attr($value) . '" class="regular-text" />';
                break;
                
            case 'email':
                echo '<input type="email" name="' . $option_name . '" id="' . $option_name . '" value="' . esc_attr($value) . '" class="regular-text" />';
                break;
                
            case 'number':
                echo '<input type="number" name="' . $option_name . '" id="' . $option_name . '" value="' . esc_attr($value) . '" min="' . $field['min'] . '" step="' . $field['step'] . '" class="small-text" />';
                break;
                
            case 'textarea':
                echo '<textarea name="' . $option_name . '" id="' . $option_name . '" rows="5" cols="50" class="large-text">' . esc_textarea($value) . '</textarea>';
                break;
                
            case 'checkbox':
                echo '<input type="checkbox" name="' . $option_name . '" id="' . $option_name . '" value="yes" ' . checked($value, 'yes', false) . ' />';
                break;
                
            case 'select':
                echo '<select name="' . $option_name . '" id="' . $option_name . '">';
                foreach ($field['options'] as $key => $label) {
                    echo '<option value="' . $key . '" ' . selected($value, $key, false) . '>' . $label . '</option>';
                }
                echo '</select>';
                break;
                
            case 'page':
                wp_dropdown_pages(array(
                    'name' => $option_name,
                    'id' => $option_name,
                    'selected' => $value,
                    'show_option_none' => __('Select a page', 'aqualuxe'),
                ));
                break;
                
            case 'color':
                echo '<input type="text" name="' . $option_name . '" id="' . $option_name . '" value="' . esc_attr($value) . '" class="color-picker" data-default-color="' . $field['default'] . '" />';
                break;
                
            case 'business_hours':
                $days = array(
                    '0' => __('Sunday', 'aqualuxe'),
                    '1' => __('Monday', 'aqualuxe'),
                    '2' => __('Tuesday', 'aqualuxe'),
                    '3' => __('Wednesday', 'aqualuxe'),
                    '4' => __('Thursday', 'aqualuxe'),
                    '5' => __('Friday', 'aqualuxe'),
                    '6' => __('Saturday', 'aqualuxe'),
                );
                
                echo '<table class="form-table business-hours-table">';
                echo '<tr><th>' . __('Day', 'aqualuxe') . '</th><th>' . __('Opening Time', 'aqualuxe') . '</th><th>' . __('Closing Time', 'aqualuxe') . '</th></tr>';
                
                foreach ($days as $day_num => $day_name) {
                    $open = isset($value[$day_num]['open']) ? $value[$day_num]['open'] : '';
                    $close = isset($value[$day_num]['close']) ? $value[$day_num]['close'] : '';
                    
                    echo '<tr>';
                    echo '<td>' . $day_name . '</td>';
                    echo '<td><input type="time" name="' . $option_name . '[' . $day_num . '][open]" value="' . esc_attr($open) . '" /></td>';
                    echo '<td><input type="time" name="' . $option_name . '[' . $day_num . '][close]" value="' . esc_attr($close) . '" /></td>';
                    echo '</tr>';
                }
                
                echo '</table>';
                echo '<p class="description">' . __('Leave both fields empty to mark the day as closed.', 'aqualuxe') . '</p>';
                break;
        }
        
        if (isset($field['desc'])) {
            echo '<p class="description">' . $field['desc'] . '</p>';
        }
    }
}