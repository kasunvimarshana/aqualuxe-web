<?php
/**
 * Bookings Admin
 *
 * Handles admin functionality for the bookings module.
 *
 * @package AquaLuxe
 * @subpackage Bookings
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Bookings Admin Class
 */
class AquaLuxe_Bookings_Admin {
    /**
     * Constructor
     */
    public function __construct() {
        // Initialize hooks
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        add_action('admin_notices', array($this, 'admin_notices'));
        add_action('admin_init', array($this, 'admin_init'));
        
        // Add dashboard widgets
        add_action('wp_dashboard_setup', array($this, 'add_dashboard_widgets'));
        
        // Add admin bar menu
        add_action('admin_bar_menu', array($this, 'admin_bar_menu'), 50);
        
        // Add action links
        add_filter('plugin_action_links_' . plugin_basename(AQUALUXE_BOOKINGS_PATH . 'bookings.php'), array($this, 'plugin_action_links'));
        
        // Add row actions
        add_filter('post_row_actions', array($this, 'row_actions'), 10, 2);
        
        // Add admin footer text
        add_filter('admin_footer_text', array($this, 'admin_footer_text'), 10, 1);
    }

    /**
     * Admin init
     */
    public function admin_init() {
        // Register settings
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_page_id');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_confirmation_page_id');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_calendar_first_day');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_time_format');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_buffer_time');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_min_booking_time');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_max_booking_time');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_booking_confirmation');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_enable_google_calendar');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_google_calendar_id');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_google_calendar_api_key');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_notification_emails');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_admin_notification_email');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_customer_notification_email');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_business_hours');
        register_setting('aqualuxe_bookings_settings', 'aqualuxe_bookings_cancellation_policy');
        
        // Add settings sections
        add_settings_section(
            'aqualuxe_bookings_general_section',
            __('General Settings', 'aqualuxe'),
            array($this, 'general_settings_section_callback'),
            'aqualuxe_bookings_settings'
        );
        
        add_settings_section(
            'aqualuxe_bookings_calendar_section',
            __('Calendar Settings', 'aqualuxe'),
            array($this, 'calendar_settings_section_callback'),
            'aqualuxe_bookings_settings'
        );
        
        add_settings_section(
            'aqualuxe_bookings_notification_section',
            __('Notification Settings', 'aqualuxe'),
            array($this, 'notification_settings_section_callback'),
            'aqualuxe_bookings_settings'
        );
        
        add_settings_section(
            'aqualuxe_bookings_integration_section',
            __('Integration Settings', 'aqualuxe'),
            array($this, 'integration_settings_section_callback'),
            'aqualuxe_bookings_settings'
        );
        
        // Add settings fields
        add_settings_field(
            'aqualuxe_bookings_page_id',
            __('Booking Page', 'aqualuxe'),
            array($this, 'page_id_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_general_section',
            array(
                'label_for' => 'aqualuxe_bookings_page_id',
                'description' => __('Select the page where the booking form will be displayed.', 'aqualuxe'),
            )
        );
        
        add_settings_field(
            'aqualuxe_bookings_confirmation_page_id',
            __('Confirmation Page', 'aqualuxe'),
            array($this, 'page_id_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_general_section',
            array(
                'label_for' => 'aqualuxe_bookings_confirmation_page_id',
                'description' => __('Select the page where the booking confirmation will be displayed.', 'aqualuxe'),
            )
        );
        
        add_settings_field(
            'aqualuxe_bookings_booking_confirmation',
            __('Booking Confirmation', 'aqualuxe'),
            array($this, 'booking_confirmation_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_general_section',
            array(
                'label_for' => 'aqualuxe_bookings_booking_confirmation',
                'description' => __('Select how bookings should be confirmed.', 'aqualuxe'),
            )
        );
        
        add_settings_field(
            'aqualuxe_bookings_cancellation_policy',
            __('Cancellation Policy', 'aqualuxe'),
            array($this, 'cancellation_policy_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_general_section',
            array(
                'label_for' => 'aqualuxe_bookings_cancellation_policy',
                'description' => __('Enter your cancellation policy. This will be displayed on the booking form and included in confirmation emails.', 'aqualuxe'),
            )
        );
        
        add_settings_field(
            'aqualuxe_bookings_calendar_first_day',
            __('First Day of Week', 'aqualuxe'),
            array($this, 'calendar_first_day_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_calendar_section',
            array(
                'label_for' => 'aqualuxe_bookings_calendar_first_day',
                'description' => __('Select the first day of the week for the calendar.', 'aqualuxe'),
            )
        );
        
        add_settings_field(
            'aqualuxe_bookings_time_format',
            __('Time Format', 'aqualuxe'),
            array($this, 'time_format_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_calendar_section',
            array(
                'label_for' => 'aqualuxe_bookings_time_format',
                'description' => __('Select the time format for the booking form.', 'aqualuxe'),
            )
        );
        
        add_settings_field(
            'aqualuxe_bookings_buffer_time',
            __('Buffer Time', 'aqualuxe'),
            array($this, 'buffer_time_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_calendar_section',
            array(
                'label_for' => 'aqualuxe_bookings_buffer_time',
                'description' => __('Enter the buffer time in minutes between bookings.', 'aqualuxe'),
            )
        );
        
        add_settings_field(
            'aqualuxe_bookings_min_booking_time',
            __('Minimum Booking Duration', 'aqualuxe'),
            array($this, 'min_booking_time_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_calendar_section',
            array(
                'label_for' => 'aqualuxe_bookings_min_booking_time',
                'description' => __('Enter the minimum booking duration in minutes.', 'aqualuxe'),
            )
        );
        
        add_settings_field(
            'aqualuxe_bookings_max_booking_time',
            __('Maximum Booking Duration', 'aqualuxe'),
            array($this, 'max_booking_time_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_calendar_section',
            array(
                'label_for' => 'aqualuxe_bookings_max_booking_time',
                'description' => __('Enter the maximum booking duration in minutes.', 'aqualuxe'),
            )
        );
        
        add_settings_field(
            'aqualuxe_bookings_business_hours',
            __('Business Hours', 'aqualuxe'),
            array($this, 'business_hours_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_calendar_section',
            array(
                'label_for' => 'aqualuxe_bookings_business_hours',
                'description' => __('Set your business hours for each day of the week.', 'aqualuxe'),
            )
        );
        
        add_settings_field(
            'aqualuxe_bookings_notification_emails',
            __('Enable Notification Emails', 'aqualuxe'),
            array($this, 'notification_emails_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_notification_section',
            array(
                'label_for' => 'aqualuxe_bookings_notification_emails',
                'description' => __('Enable or disable notification emails.', 'aqualuxe'),
            )
        );
        
        add_settings_field(
            'aqualuxe_bookings_admin_notification_email',
            __('Admin Notification Email', 'aqualuxe'),
            array($this, 'admin_notification_email_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_notification_section',
            array(
                'label_for' => 'aqualuxe_bookings_admin_notification_email',
                'description' => __('Enter the email address to receive admin notifications.', 'aqualuxe'),
            )
        );
        
        add_settings_field(
            'aqualuxe_bookings_customer_notification_email',
            __('Customer Notification Email', 'aqualuxe'),
            array($this, 'customer_notification_email_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_notification_section',
            array(
                'label_for' => 'aqualuxe_bookings_customer_notification_email',
                'description' => __('Enable or disable customer notification emails.', 'aqualuxe'),
            )
        );
        
        add_settings_field(
            'aqualuxe_bookings_enable_google_calendar',
            __('Enable Google Calendar Integration', 'aqualuxe'),
            array($this, 'enable_google_calendar_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_integration_section',
            array(
                'label_for' => 'aqualuxe_bookings_enable_google_calendar',
                'description' => __('Enable or disable Google Calendar integration.', 'aqualuxe'),
            )
        );
        
        add_settings_field(
            'aqualuxe_bookings_google_calendar_id',
            __('Google Calendar ID', 'aqualuxe'),
            array($this, 'google_calendar_id_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_integration_section',
            array(
                'label_for' => 'aqualuxe_bookings_google_calendar_id',
                'description' => __('Enter your Google Calendar ID.', 'aqualuxe'),
            )
        );
        
        add_settings_field(
            'aqualuxe_bookings_google_calendar_api_key',
            __('Google Calendar API Key', 'aqualuxe'),
            array($this, 'google_calendar_api_key_field_callback'),
            'aqualuxe_bookings_settings',
            'aqualuxe_bookings_integration_section',
            array(
                'label_for' => 'aqualuxe_bookings_google_calendar_api_key',
                'description' => __('Enter your Google Calendar API key.', 'aqualuxe'),
            )
        );
    }

    /**
     * Add admin menu
     */
    public function admin_menu() {
        // Add main menu
        add_menu_page(
            __('Bookings', 'aqualuxe'),
            __('Bookings', 'aqualuxe'),
            'manage_options',
            'aqualuxe-bookings',
            array($this, 'bookings_page'),
            'dashicons-calendar-alt',
            30
        );
        
        // Add submenu pages
        add_submenu_page(
            'aqualuxe-bookings',
            __('Bookings', 'aqualuxe'),
            __('All Bookings', 'aqualuxe'),
            'manage_options',
            'aqualuxe-bookings',
            array($this, 'bookings_page')
        );
        
        add_submenu_page(
            'aqualuxe-bookings',
            __('Add New Booking', 'aqualuxe'),
            __('Add New', 'aqualuxe'),
            'manage_options',
            'post-new.php?post_type=booking'
        );
        
        add_submenu_page(
            'aqualuxe-bookings',
            __('Calendar', 'aqualuxe'),
            __('Calendar', 'aqualuxe'),
            'manage_options',
            'aqualuxe-bookings-calendar',
            array($this, 'calendar_page')
        );
        
        add_submenu_page(
            'aqualuxe-bookings',
            __('Services', 'aqualuxe'),
            __('Services', 'aqualuxe'),
            'manage_options',
            'edit.php?post_type=bookable_service'
        );
        
        add_submenu_page(
            'aqualuxe-bookings',
            __('Add New Service', 'aqualuxe'),
            __('Add New Service', 'aqualuxe'),
            'manage_options',
            'post-new.php?post_type=bookable_service'
        );
        
        add_submenu_page(
            'aqualuxe-bookings',
            __('Categories', 'aqualuxe'),
            __('Categories', 'aqualuxe'),
            'manage_options',
            'edit-tags.php?taxonomy=service_category&post_type=bookable_service'
        );
        
        add_submenu_page(
            'aqualuxe-bookings',
            __('Tags', 'aqualuxe'),
            __('Tags', 'aqualuxe'),
            'manage_options',
            'edit-tags.php?taxonomy=service_tag&post_type=bookable_service'
        );
        
        add_submenu_page(
            'aqualuxe-bookings',
            __('Settings', 'aqualuxe'),
            __('Settings', 'aqualuxe'),
            'manage_options',
            'aqualuxe-bookings-settings',
            array($this, 'settings_page')
        );
        
        add_submenu_page(
            'aqualuxe-bookings',
            __('Reports', 'aqualuxe'),
            __('Reports', 'aqualuxe'),
            'manage_options',
            'aqualuxe-bookings-reports',
            array($this, 'reports_page')
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function admin_scripts($hook) {
        // Get current screen
        $screen = get_current_screen();
        
        // Only load on bookings pages
        if (strpos($hook, 'aqualuxe-bookings') === false && $screen->post_type !== 'booking' && $screen->post_type !== 'bookable_service') {
            return;
        }
        
        // Enqueue styles
        wp_enqueue_style('aqualuxe-bookings-admin');
        
        // Enqueue scripts
        wp_enqueue_script('aqualuxe-bookings-admin');
        
        // Add calendar page scripts
        if ($hook === 'bookings_page_aqualuxe-bookings-calendar') {
            wp_enqueue_style('fullcalendar', AQUALUXE_BOOKINGS_URL . 'assets/vendor/fullcalendar/main.min.css', array(), '5.10.1');
            wp_enqueue_script('fullcalendar', AQUALUXE_BOOKINGS_URL . 'assets/vendor/fullcalendar/main.min.js', array('jquery'), '5.10.1', true);
            
            wp_localize_script('aqualuxe-bookings-admin', 'aqualuxe_bookings_calendar', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe-bookings-admin'),
                'i18n' => array(
                    'today' => __('Today', 'aqualuxe'),
                    'month' => __('Month', 'aqualuxe'),
                    'week' => __('Week', 'aqualuxe'),
                    'day' => __('Day', 'aqualuxe'),
                    'list' => __('List', 'aqualuxe'),
                    'all_day' => __('All Day', 'aqualuxe'),
                    'event_details' => __('Event Details', 'aqualuxe'),
                    'booking_details' => __('Booking Details', 'aqualuxe'),
                    'service' => __('Service', 'aqualuxe'),
                    'customer' => __('Customer', 'aqualuxe'),
                    'date' => __('Date', 'aqualuxe'),
                    'time' => __('Time', 'aqualuxe'),
                    'status' => __('Status', 'aqualuxe'),
                    'total' => __('Total', 'aqualuxe'),
                    'view_booking' => __('View Booking', 'aqualuxe'),
                    'edit_booking' => __('Edit Booking', 'aqualuxe'),
                    'delete_booking' => __('Delete Booking', 'aqualuxe'),
                    'confirm_delete' => __('Are you sure you want to delete this booking?', 'aqualuxe'),
                    'add_availability_rule' => __('Add Availability Rule', 'aqualuxe'),
                    'edit_availability_rule' => __('Edit Availability Rule', 'aqualuxe'),
                    'delete_availability_rule' => __('Delete Availability Rule', 'aqualuxe'),
                    'confirm_delete_rule' => __('Are you sure you want to delete this availability rule?', 'aqualuxe'),
                    'save' => __('Save', 'aqualuxe'),
                    'cancel' => __('Cancel', 'aqualuxe'),
                    'delete' => __('Delete', 'aqualuxe'),
                    'service_label' => __('Service', 'aqualuxe'),
                    'start_date_label' => __('Start Date', 'aqualuxe'),
                    'end_date_label' => __('End Date', 'aqualuxe'),
                    'bookable_label' => __('Bookable', 'aqualuxe'),
                    'yes' => __('Yes', 'aqualuxe'),
                    'no' => __('No', 'aqualuxe'),
                ),
            ));
        }
        
        // Add reports page scripts
        if ($hook === 'bookings_page_aqualuxe-bookings-reports') {
            wp_enqueue_style('chart-js', AQUALUXE_BOOKINGS_URL . 'assets/vendor/chart.js/chart.min.css', array(), '3.7.0');
            wp_enqueue_script('chart-js', AQUALUXE_BOOKINGS_URL . 'assets/vendor/chart.js/chart.min.js', array('jquery'), '3.7.0', true);
            
            wp_localize_script('aqualuxe-bookings-admin', 'aqualuxe_bookings_reports', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe-bookings-admin'),
                'i18n' => array(
                    'bookings' => __('Bookings', 'aqualuxe'),
                    'revenue' => __('Revenue', 'aqualuxe'),
                    'services' => __('Services', 'aqualuxe'),
                    'customers' => __('Customers', 'aqualuxe'),
                ),
            ));
        }
    }

    /**
     * Admin notices
     */
    public function admin_notices() {
        // Check if booking page is set
        $booking_page_id = get_option('aqualuxe_bookings_page_id');
        
        if (empty($booking_page_id) && isset($_GET['page']) && $_GET['page'] === 'aqualuxe-bookings-settings') {
            echo '<div class="notice notice-warning is-dismissible"><p>';
            echo sprintf(
                /* translators: %s: settings page URL */
                __('Please set up a booking page in the <a href="%s">settings</a>.', 'aqualuxe'),
                admin_url('admin.php?page=aqualuxe-bookings-settings')
            );
            echo '</p></div>';
        }
        
        // Check if confirmation page is set
        $confirmation_page_id = get_option('aqualuxe_bookings_confirmation_page_id');
        
        if (empty($confirmation_page_id) && isset($_GET['page']) && $_GET['page'] === 'aqualuxe-bookings-settings') {
            echo '<div class="notice notice-warning is-dismissible"><p>';
            echo sprintf(
                /* translators: %s: settings page URL */
                __('Please set up a confirmation page in the <a href="%s">settings</a>.', 'aqualuxe'),
                admin_url('admin.php?page=aqualuxe-bookings-settings')
            );
            echo '</p></div>';
        }
    }

    /**
     * Add dashboard widgets
     */
    public function add_dashboard_widgets() {
        // Only add for users who can manage options
        if (!current_user_can('manage_options')) {
            return;
        }
        
        wp_add_dashboard_widget(
            'aqualuxe_bookings_dashboard_widget',
            __('Recent Bookings', 'aqualuxe'),
            array($this, 'dashboard_widget_callback')
        );
    }

    /**
     * Dashboard widget callback
     */
    public function dashboard_widget_callback() {
        // Get recent bookings
        $bookings_data = new AquaLuxe_Bookings_Data();
        $bookings = $bookings_data->get_bookings(array(
            'limit' => 5,
            'orderby' => 'date',
            'order' => 'DESC',
        ));
        
        // Display bookings
        if (!empty($bookings)) {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead><tr>';
            echo '<th>' . __('Booking ID', 'aqualuxe') . '</th>';
            echo '<th>' . __('Customer', 'aqualuxe') . '</th>';
            echo '<th>' . __('Service', 'aqualuxe') . '</th>';
            echo '<th>' . __('Date', 'aqualuxe') . '</th>';
            echo '<th>' . __('Status', 'aqualuxe') . '</th>';
            echo '</tr></thead>';
            echo '<tbody>';
            
            foreach ($bookings as $booking) {
                echo '<tr>';
                echo '<td><a href="' . admin_url('post.php?post=' . $booking['id'] . '&action=edit') . '">' . $booking['booking_id'] . '</a></td>';
                echo '<td>' . esc_html($booking['customer_name']) . '</td>';
                echo '<td>' . esc_html($booking['service_name']) . '</td>';
                echo '<td>' . date_i18n(get_option('date_format'), strtotime($booking['start_date'])) . '</td>';
                echo '<td>' . $this->get_status_label($booking['status']) . '</td>';
                echo '</tr>';
            }
            
            echo '</tbody></table>';
            
            echo '<p class="aqualuxe-bookings-dashboard-widget-footer">';
            echo '<a href="' . admin_url('admin.php?page=aqualuxe-bookings') . '">' . __('View All Bookings', 'aqualuxe') . '</a>';
            echo '</p>';
        } else {
            echo '<p>' . __('No bookings found.', 'aqualuxe') . '</p>';
        }
    }

    /**
     * Add admin bar menu
     *
     * @param WP_Admin_Bar $wp_admin_bar Admin bar object
     */
    public function admin_bar_menu($wp_admin_bar) {
        // Only add for users who can manage options
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Add main menu
        $wp_admin_bar->add_node(array(
            'id' => 'aqualuxe-bookings',
            'title' => __('Bookings', 'aqualuxe'),
            'href' => admin_url('admin.php?page=aqualuxe-bookings'),
        ));
        
        // Add submenu items
        $wp_admin_bar->add_node(array(
            'parent' => 'aqualuxe-bookings',
            'id' => 'aqualuxe-bookings-all',
            'title' => __('All Bookings', 'aqualuxe'),
            'href' => admin_url('admin.php?page=aqualuxe-bookings'),
        ));
        
        $wp_admin_bar->add_node(array(
            'parent' => 'aqualuxe-bookings',
            'id' => 'aqualuxe-bookings-new',
            'title' => __('Add New Booking', 'aqualuxe'),
            'href' => admin_url('post-new.php?post_type=booking'),
        ));
        
        $wp_admin_bar->add_node(array(
            'parent' => 'aqualuxe-bookings',
            'id' => 'aqualuxe-bookings-calendar',
            'title' => __('Calendar', 'aqualuxe'),
            'href' => admin_url('admin.php?page=aqualuxe-bookings-calendar'),
        ));
        
        $wp_admin_bar->add_node(array(
            'parent' => 'aqualuxe-bookings',
            'id' => 'aqualuxe-bookings-services',
            'title' => __('Services', 'aqualuxe'),
            'href' => admin_url('edit.php?post_type=bookable_service'),
        ));
        
        $wp_admin_bar->add_node(array(
            'parent' => 'aqualuxe-bookings',
            'id' => 'aqualuxe-bookings-settings',
            'title' => __('Settings', 'aqualuxe'),
            'href' => admin_url('admin.php?page=aqualuxe-bookings-settings'),
        ));
    }

    /**
     * Add plugin action links
     *
     * @param array $links Action links
     * @return array Modified action links
     */
    public function plugin_action_links($links) {
        $plugin_links = array(
            '<a href="' . admin_url('admin.php?page=aqualuxe-bookings-settings') . '">' . __('Settings', 'aqualuxe') . '</a>',
        );
        
        return array_merge($plugin_links, $links);
    }

    /**
     * Add row actions
     *
     * @param array $actions Row actions
     * @param WP_Post $post Post object
     * @return array Modified row actions
     */
    public function row_actions($actions, $post) {
        if ($post->post_type === 'booking') {
            // Add view calendar action
            $actions['view_calendar'] = '<a href="' . admin_url('admin.php?page=aqualuxe-bookings-calendar&booking_id=' . $post->ID) . '">' . __('View in Calendar', 'aqualuxe') . '</a>';
        }
        
        return $actions;
    }

    /**
     * Admin footer text
     *
     * @param string $text Footer text
     * @return string Modified footer text
     */
    public function admin_footer_text($text) {
        // Get current screen
        $screen = get_current_screen();
        
        // Only modify on bookings pages
        if (strpos($screen->id, 'aqualuxe-bookings') !== false || $screen->post_type === 'booking' || $screen->post_type === 'bookable_service') {
            $text = sprintf(
                /* translators: %s: AquaLuxe */
                __('Thank you for using %s Bookings!', 'aqualuxe'),
                'AquaLuxe'
            );
        }
        
        return $text;
    }

    /**
     * Bookings page
     */
    public function bookings_page() {
        // Check if user has permission
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'aqualuxe'));
        }
        
        // Include template
        include AQUALUXE_BOOKINGS_PATH . 'templates/admin/bookings.php';
    }

    /**
     * Calendar page
     */
    public function calendar_page() {
        // Check if user has permission
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'aqualuxe'));
        }
        
        // Include template
        include AQUALUXE_BOOKINGS_PATH . 'templates/admin/calendar.php';
    }

    /**
     * Settings page
     */
    public function settings_page() {
        // Check if user has permission
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'aqualuxe'));
        }
        
        // Include template
        include AQUALUXE_BOOKINGS_PATH . 'templates/admin/settings.php';
    }

    /**
     * Reports page
     */
    public function reports_page() {
        // Check if user has permission
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'aqualuxe'));
        }
        
        // Include template
        include AQUALUXE_BOOKINGS_PATH . 'templates/admin/reports.php';
    }

    /**
     * General settings section callback
     */
    public function general_settings_section_callback() {
        echo '<p>' . __('Configure general settings for the bookings module.', 'aqualuxe') . '</p>';
    }

    /**
     * Calendar settings section callback
     */
    public function calendar_settings_section_callback() {
        echo '<p>' . __('Configure calendar settings for the bookings module.', 'aqualuxe') . '</p>';
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
     * Page ID field callback
     *
     * @param array $args Field arguments
     */
    public function page_id_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, 0);
        
        wp_dropdown_pages(array(
            'name' => $option_name,
            'id' => $option_name,
            'selected' => $value,
            'show_option_none' => __('Select a page', 'aqualuxe'),
        ));
        
        echo '<p class="description">' . $args['description'] . '</p>';
    }

    /**
     * Booking confirmation field callback
     *
     * @param array $args Field arguments
     */
    public function booking_confirmation_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, 'payment');
        
        echo '<select name="' . $option_name . '" id="' . $option_name . '">';
        echo '<option value="manual" ' . selected($value, 'manual', false) . '>' . __('Manual (Admin must confirm)', 'aqualuxe') . '</option>';
        echo '<option value="automatic" ' . selected($value, 'automatic', false) . '>' . __('Automatic (Confirmed immediately)', 'aqualuxe') . '</option>';
        echo '<option value="payment" ' . selected($value, 'payment', false) . '>' . __('Payment (Confirmed after payment)', 'aqualuxe') . '</option>';
        echo '</select>';
        
        echo '<p class="description">' . $args['description'] . '</p>';
    }

    /**
     * Cancellation policy field callback
     *
     * @param array $args Field arguments
     */
    public function cancellation_policy_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, '');
        
        echo '<textarea name="' . $option_name . '" id="' . $option_name . '" rows="5" cols="50" class="large-text">' . esc_textarea($value) . '</textarea>';
        echo '<p class="description">' . $args['description'] . '</p>';
    }

    /**
     * Calendar first day field callback
     *
     * @param array $args Field arguments
     */
    public function calendar_first_day_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, 0);
        
        echo '<select name="' . $option_name . '" id="' . $option_name . '">';
        echo '<option value="0" ' . selected($value, 0, false) . '>' . __('Sunday', 'aqualuxe') . '</option>';
        echo '<option value="1" ' . selected($value, 1, false) . '>' . __('Monday', 'aqualuxe') . '</option>';
        echo '<option value="2" ' . selected($value, 2, false) . '>' . __('Tuesday', 'aqualuxe') . '</option>';
        echo '<option value="3" ' . selected($value, 3, false) . '>' . __('Wednesday', 'aqualuxe') . '</option>';
        echo '<option value="4" ' . selected($value, 4, false) . '>' . __('Thursday', 'aqualuxe') . '</option>';
        echo '<option value="5" ' . selected($value, 5, false) . '>' . __('Friday', 'aqualuxe') . '</option>';
        echo '<option value="6" ' . selected($value, 6, false) . '>' . __('Saturday', 'aqualuxe') . '</option>';
        echo '</select>';
        
        echo '<p class="description">' . $args['description'] . '</p>';
    }

    /**
     * Time format field callback
     *
     * @param array $args Field arguments
     */
    public function time_format_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, '12h');
        
        echo '<select name="' . $option_name . '" id="' . $option_name . '">';
        echo '<option value="12h" ' . selected($value, '12h', false) . '>' . __('12-hour (AM/PM)', 'aqualuxe') . '</option>';
        echo '<option value="24h" ' . selected($value, '24h', false) . '>' . __('24-hour', 'aqualuxe') . '</option>';
        echo '</select>';
        
        echo '<p class="description">' . $args['description'] . '</p>';
    }

    /**
     * Buffer time field callback
     *
     * @param array $args Field arguments
     */
    public function buffer_time_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, 30);
        
        echo '<input type="number" name="' . $option_name . '" id="' . $option_name . '" value="' . esc_attr($value) . '" min="0" step="5" class="small-text" /> ' . __('minutes', 'aqualuxe');
        echo '<p class="description">' . $args['description'] . '</p>';
    }

    /**
     * Minimum booking time field callback
     *
     * @param array $args Field arguments
     */
    public function min_booking_time_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, 60);
        
        echo '<input type="number" name="' . $option_name . '" id="' . $option_name . '" value="' . esc_attr($value) . '" min="0" step="5" class="small-text" /> ' . __('minutes', 'aqualuxe');
        echo '<p class="description">' . $args['description'] . '</p>';
    }

    /**
     * Maximum booking time field callback
     *
     * @param array $args Field arguments
     */
    public function max_booking_time_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, 480);
        
        echo '<input type="number" name="' . $option_name . '" id="' . $option_name . '" value="' . esc_attr($value) . '" min="0" step="5" class="small-text" /> ' . __('minutes', 'aqualuxe');
        echo '<p class="description">' . $args['description'] . '</p>';
    }

    /**
     * Business hours field callback
     *
     * @param array $args Field arguments
     */
    public function business_hours_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, array(
            0 => array('open' => '', 'close' => ''), // Sunday (closed)
            1 => array('open' => '09:00', 'close' => '17:00'), // Monday
            2 => array('open' => '09:00', 'close' => '17:00'), // Tuesday
            3 => array('open' => '09:00', 'close' => '17:00'), // Wednesday
            4 => array('open' => '09:00', 'close' => '17:00'), // Thursday
            5 => array('open' => '09:00', 'close' => '17:00'), // Friday
            6 => array('open' => '', 'close' => ''), // Saturday (closed)
        ));
        
        $days = array(
            0 => __('Sunday', 'aqualuxe'),
            1 => __('Monday', 'aqualuxe'),
            2 => __('Tuesday', 'aqualuxe'),
            3 => __('Wednesday', 'aqualuxe'),
            4 => __('Thursday', 'aqualuxe'),
            5 => __('Friday', 'aqualuxe'),
            6 => __('Saturday', 'aqualuxe'),
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
        echo '<p class="description">' . $args['description'] . '</p>';
        echo '<p class="description">' . __('Leave both fields empty to mark the day as closed.', 'aqualuxe') . '</p>';
    }

    /**
     * Notification emails field callback
     *
     * @param array $args Field arguments
     */
    public function notification_emails_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, 'yes');
        
        echo '<select name="' . $option_name . '" id="' . $option_name . '">';
        echo '<option value="yes" ' . selected($value, 'yes', false) . '>' . __('Yes', 'aqualuxe') . '</option>';
        echo '<option value="no" ' . selected($value, 'no', false) . '>' . __('No', 'aqualuxe') . '</option>';
        echo '</select>';
        
        echo '<p class="description">' . $args['description'] . '</p>';
    }

    /**
     * Admin notification email field callback
     *
     * @param array $args Field arguments
     */
    public function admin_notification_email_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, get_option('admin_email'));
        
        echo '<input type="email" name="' . $option_name . '" id="' . $option_name . '" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . $args['description'] . '</p>';
    }

    /**
     * Customer notification email field callback
     *
     * @param array $args Field arguments
     */
    public function customer_notification_email_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, 'yes');
        
        echo '<select name="' . $option_name . '" id="' . $option_name . '">';
        echo '<option value="yes" ' . selected($value, 'yes', false) . '>' . __('Yes', 'aqualuxe') . '</option>';
        echo '<option value="no" ' . selected($value, 'no', false) . '>' . __('No', 'aqualuxe') . '</option>';
        echo '</select>';
        
        echo '<p class="description">' . $args['description'] . '</p>';
    }

    /**
     * Enable Google Calendar field callback
     *
     * @param array $args Field arguments
     */
    public function enable_google_calendar_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, 'no');
        
        echo '<select name="' . $option_name . '" id="' . $option_name . '">';
        echo '<option value="yes" ' . selected($value, 'yes', false) . '>' . __('Yes', 'aqualuxe') . '</option>';
        echo '<option value="no" ' . selected($value, 'no', false) . '>' . __('No', 'aqualuxe') . '</option>';
        echo '</select>';
        
        echo '<p class="description">' . $args['description'] . '</p>';
    }

    /**
     * Google Calendar ID field callback
     *
     * @param array $args Field arguments
     */
    public function google_calendar_id_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, '');
        
        echo '<input type="text" name="' . $option_name . '" id="' . $option_name . '" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . $args['description'] . '</p>';
    }

    /**
     * Google Calendar API key field callback
     *
     * @param array $args Field arguments
     */
    public function google_calendar_api_key_field_callback($args) {
        $option_name = $args['label_for'];
        $value = get_option($option_name, '');
        
        echo '<input type="text" name="' . $option_name . '" id="' . $option_name . '" value="' . esc_attr($value) . '" class="regular-text" />';
        echo '<p class="description">' . $args['description'] . '</p>';
    }

    /**
     * Get status label
     *
     * @param string $status Status
     * @return string Status label
     */
    public function get_status_label($status) {
        switch ($status) {
            case 'aqualuxe-pending':
                return '<span class="booking-status booking-status-pending">' . __('Pending', 'aqualuxe') . '</span>';
            case 'aqualuxe-confirmed':
                return '<span class="booking-status booking-status-confirmed">' . __('Confirmed', 'aqualuxe') . '</span>';
            case 'aqualuxe-completed':
                return '<span class="booking-status booking-status-completed">' . __('Completed', 'aqualuxe') . '</span>';
            case 'aqualuxe-cancelled':
                return '<span class="booking-status booking-status-cancelled">' . __('Cancelled', 'aqualuxe') . '</span>';
            default:
                return '<span class="booking-status">' . ucfirst($status) . '</span>';
        }
    }
}