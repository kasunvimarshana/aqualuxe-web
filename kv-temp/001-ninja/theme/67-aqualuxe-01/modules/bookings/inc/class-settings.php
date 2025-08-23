<?php
/**
 * Settings Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * Settings Class
 * 
 * This class handles module settings.
 */
class Settings {
    /**
     * Instance of this class
     *
     * @var Settings
     */
    private static $instance = null;

    /**
     * Settings
     *
     * @var array
     */
    private $settings;

    /**
     * Get the singleton instance
     *
     * @return Settings
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->settings = get_option( 'aqualuxe_bookings_settings', [] );
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Register settings
        add_action( 'admin_init', [ $this, 'register_settings' ] );
    }

    /**
     * Register settings
     *
     * @return void
     */
    public function register_settings() {
        // Register settings
        register_setting( 'aqualuxe_bookings', 'aqualuxe_bookings_settings' );

        // Add settings sections
        add_settings_section(
            'aqualuxe_bookings_general',
            esc_html__( 'General Settings', 'aqualuxe' ),
            [ $this, 'general_settings_section' ],
            'aqualuxe_bookings'
        );

        add_settings_section(
            'aqualuxe_bookings_availability',
            esc_html__( 'Availability Settings', 'aqualuxe' ),
            [ $this, 'availability_settings_section' ],
            'aqualuxe_bookings'
        );

        add_settings_section(
            'aqualuxe_bookings_notifications',
            esc_html__( 'Notification Settings', 'aqualuxe' ),
            [ $this, 'notification_settings_section' ],
            'aqualuxe_bookings'
        );

        add_settings_section(
            'aqualuxe_bookings_woocommerce',
            esc_html__( 'WooCommerce Settings', 'aqualuxe' ),
            [ $this, 'woocommerce_settings_section' ],
            'aqualuxe_bookings'
        );

        // Add settings fields
        add_settings_field(
            'booking_page',
            esc_html__( 'Booking Page', 'aqualuxe' ),
            [ $this, 'booking_page_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_general'
        );

        add_settings_field(
            'services_page',
            esc_html__( 'Services Page', 'aqualuxe' ),
            [ $this, 'services_page_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_general'
        );

        add_settings_field(
            'calendar_page',
            esc_html__( 'Calendar Page', 'aqualuxe' ),
            [ $this, 'calendar_page_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_general'
        );

        add_settings_field(
            'minimum_notice',
            esc_html__( 'Minimum Notice', 'aqualuxe' ),
            [ $this, 'minimum_notice_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_availability'
        );

        add_settings_field(
            'maximum_notice',
            esc_html__( 'Maximum Notice', 'aqualuxe' ),
            [ $this, 'maximum_notice_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_availability'
        );

        add_settings_field(
            'time_format',
            esc_html__( 'Time Format', 'aqualuxe' ),
            [ $this, 'time_format_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_availability'
        );

        add_settings_field(
            'date_format',
            esc_html__( 'Date Format', 'aqualuxe' ),
            [ $this, 'date_format_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_availability'
        );

        add_settings_field(
            'admin_notification',
            esc_html__( 'Admin Notification', 'aqualuxe' ),
            [ $this, 'admin_notification_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_notifications'
        );

        add_settings_field(
            'customer_notification',
            esc_html__( 'Customer Notification', 'aqualuxe' ),
            [ $this, 'customer_notification_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_notifications'
        );

        add_settings_field(
            'reminder_notification',
            esc_html__( 'Reminder Notification', 'aqualuxe' ),
            [ $this, 'reminder_notification_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_notifications'
        );

        add_settings_field(
            'woocommerce_integration',
            esc_html__( 'WooCommerce Integration', 'aqualuxe' ),
            [ $this, 'woocommerce_integration_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_woocommerce'
        );

        add_settings_field(
            'woocommerce_product_type',
            esc_html__( 'WooCommerce Product Type', 'aqualuxe' ),
            [ $this, 'woocommerce_product_type_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_woocommerce'
        );

        add_settings_field(
            'woocommerce_checkout',
            esc_html__( 'WooCommerce Checkout', 'aqualuxe' ),
            [ $this, 'woocommerce_checkout_field' ],
            'aqualuxe_bookings',
            'aqualuxe_bookings_woocommerce'
        );
    }

    /**
     * General settings section
     *
     * @return void
     */
    public function general_settings_section() {
        echo '<p>' . esc_html__( 'General settings for the bookings module.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Availability settings section
     *
     * @return void
     */
    public function availability_settings_section() {
        echo '<p>' . esc_html__( 'Settings for service availability and booking rules.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Notification settings section
     *
     * @return void
     */
    public function notification_settings_section() {
        echo '<p>' . esc_html__( 'Settings for booking notifications and reminders.', 'aqualuxe' ) . '</p>';
    }

    /**
     * WooCommerce settings section
     *
     * @return void
     */
    public function woocommerce_settings_section() {
        echo '<p>' . esc_html__( 'Settings for WooCommerce integration.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Booking page field
     *
     * @return void
     */
    public function booking_page_field() {
        $booking_page = isset( $this->settings['booking_page'] ) ? $this->settings['booking_page'] : '';

        wp_dropdown_pages(
            [
                'name'              => 'aqualuxe_bookings_settings[booking_page]',
                'selected'          => $booking_page,
                'show_option_none'  => esc_html__( 'Select a page', 'aqualuxe' ),
                'option_none_value' => '',
            ]
        );

        echo '<p class="description">' . esc_html__( 'Select the page where the booking form will be displayed.', 'aqualuxe' ) . '</p>';
        echo '<p class="description">' . esc_html__( 'Add the [aqualuxe_booking_form] shortcode to the selected page.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Services page field
     *
     * @return void
     */
    public function services_page_field() {
        $services_page = isset( $this->settings['services_page'] ) ? $this->settings['services_page'] : '';

        wp_dropdown_pages(
            [
                'name'              => 'aqualuxe_bookings_settings[services_page]',
                'selected'          => $services_page,
                'show_option_none'  => esc_html__( 'Select a page', 'aqualuxe' ),
                'option_none_value' => '',
            ]
        );

        echo '<p class="description">' . esc_html__( 'Select the page where the services will be displayed.', 'aqualuxe' ) . '</p>';
        echo '<p class="description">' . esc_html__( 'Add the [aqualuxe_services] shortcode to the selected page.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Calendar page field
     *
     * @return void
     */
    public function calendar_page_field() {
        $calendar_page = isset( $this->settings['calendar_page'] ) ? $this->settings['calendar_page'] : '';

        wp_dropdown_pages(
            [
                'name'              => 'aqualuxe_bookings_settings[calendar_page]',
                'selected'          => $calendar_page,
                'show_option_none'  => esc_html__( 'Select a page', 'aqualuxe' ),
                'option_none_value' => '',
            ]
        );

        echo '<p class="description">' . esc_html__( 'Select the page where the calendar will be displayed.', 'aqualuxe' ) . '</p>';
        echo '<p class="description">' . esc_html__( 'Add the [aqualuxe_calendar] shortcode to the selected page.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Minimum notice field
     *
     * @return void
     */
    public function minimum_notice_field() {
        $minimum_notice = isset( $this->settings['minimum_notice'] ) ? $this->settings['minimum_notice'] : 24;

        echo '<input type="number" name="aqualuxe_bookings_settings[minimum_notice]" value="' . esc_attr( $minimum_notice ) . '" class="small-text" /> ' . esc_html__( 'hours', 'aqualuxe' );
        echo '<p class="description">' . esc_html__( 'Minimum notice required for bookings (in hours).', 'aqualuxe' ) . '</p>';
    }

    /**
     * Maximum notice field
     *
     * @return void
     */
    public function maximum_notice_field() {
        $maximum_notice = isset( $this->settings['maximum_notice'] ) ? $this->settings['maximum_notice'] : 90;

        echo '<input type="number" name="aqualuxe_bookings_settings[maximum_notice]" value="' . esc_attr( $maximum_notice ) . '" class="small-text" /> ' . esc_html__( 'days', 'aqualuxe' );
        echo '<p class="description">' . esc_html__( 'Maximum notice allowed for bookings (in days).', 'aqualuxe' ) . '</p>';
    }

    /**
     * Time format field
     *
     * @return void
     */
    public function time_format_field() {
        $time_format = isset( $this->settings['time_format'] ) ? $this->settings['time_format'] : '12';

        echo '<select name="aqualuxe_bookings_settings[time_format]">';
        echo '<option value="12" ' . selected( $time_format, '12', false ) . '>' . esc_html__( '12-hour format (e.g., 1:00 PM)', 'aqualuxe' ) . '</option>';
        echo '<option value="24" ' . selected( $time_format, '24', false ) . '>' . esc_html__( '24-hour format (e.g., 13:00)', 'aqualuxe' ) . '</option>';
        echo '</select>';
        echo '<p class="description">' . esc_html__( 'Select the time format for displaying booking times.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Date format field
     *
     * @return void
     */
    public function date_format_field() {
        $date_format = isset( $this->settings['date_format'] ) ? $this->settings['date_format'] : 'mm/dd/yyyy';

        echo '<select name="aqualuxe_bookings_settings[date_format]">';
        echo '<option value="mm/dd/yyyy" ' . selected( $date_format, 'mm/dd/yyyy', false ) . '>' . esc_html__( 'MM/DD/YYYY (e.g., 12/31/2023)', 'aqualuxe' ) . '</option>';
        echo '<option value="dd/mm/yyyy" ' . selected( $date_format, 'dd/mm/yyyy', false ) . '>' . esc_html__( 'DD/MM/YYYY (e.g., 31/12/2023)', 'aqualuxe' ) . '</option>';
        echo '<option value="yyyy-mm-dd" ' . selected( $date_format, 'yyyy-mm-dd', false ) . '>' . esc_html__( 'YYYY-MM-DD (e.g., 2023-12-31)', 'aqualuxe' ) . '</option>';
        echo '</select>';
        echo '<p class="description">' . esc_html__( 'Select the date format for displaying booking dates.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Admin notification field
     *
     * @return void
     */
    public function admin_notification_field() {
        $admin_notification = isset( $this->settings['admin_notification'] ) ? $this->settings['admin_notification'] : true;

        echo '<input type="checkbox" name="aqualuxe_bookings_settings[admin_notification]" value="1" ' . checked( $admin_notification, true, false ) . ' />';
        echo '<p class="description">' . esc_html__( 'Send email notifications to admin when a new booking is made.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Customer notification field
     *
     * @return void
     */
    public function customer_notification_field() {
        $customer_notification = isset( $this->settings['customer_notification'] ) ? $this->settings['customer_notification'] : true;

        echo '<input type="checkbox" name="aqualuxe_bookings_settings[customer_notification]" value="1" ' . checked( $customer_notification, true, false ) . ' />';
        echo '<p class="description">' . esc_html__( 'Send email notifications to customers when a booking is made or updated.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Reminder notification field
     *
     * @return void
     */
    public function reminder_notification_field() {
        $reminder_notification = isset( $this->settings['reminder_notification'] ) ? $this->settings['reminder_notification'] : true;
        $reminder_time = isset( $this->settings['reminder_time'] ) ? $this->settings['reminder_time'] : 24;

        echo '<input type="checkbox" name="aqualuxe_bookings_settings[reminder_notification]" value="1" ' . checked( $reminder_notification, true, false ) . ' />';
        echo ' <input type="number" name="aqualuxe_bookings_settings[reminder_time]" value="' . esc_attr( $reminder_time ) . '" class="small-text" /> ' . esc_html__( 'hours before appointment', 'aqualuxe' );
        echo '<p class="description">' . esc_html__( 'Send reminder email notifications to customers before their appointment.', 'aqualuxe' ) . '</p>';
    }

    /**
     * WooCommerce integration field
     *
     * @return void
     */
    public function woocommerce_integration_field() {
        $woocommerce_integration = isset( $this->settings['woocommerce_integration'] ) ? $this->settings['woocommerce_integration'] : false;

        echo '<input type="checkbox" name="aqualuxe_bookings_settings[woocommerce_integration]" value="1" ' . checked( $woocommerce_integration, true, false ) . ' />';
        echo '<p class="description">' . esc_html__( 'Enable WooCommerce integration for bookings.', 'aqualuxe' ) . '</p>';
    }

    /**
     * WooCommerce product type field
     *
     * @return void
     */
    public function woocommerce_product_type_field() {
        $woocommerce_product_type = isset( $this->settings['woocommerce_product_type'] ) ? $this->settings['woocommerce_product_type'] : 'simple';

        echo '<select name="aqualuxe_bookings_settings[woocommerce_product_type]">';
        echo '<option value="simple" ' . selected( $woocommerce_product_type, 'simple', false ) . '>' . esc_html__( 'Simple Product', 'aqualuxe' ) . '</option>';
        echo '<option value="variable" ' . selected( $woocommerce_product_type, 'variable', false ) . '>' . esc_html__( 'Variable Product', 'aqualuxe' ) . '</option>';
        echo '</select>';
        echo '<p class="description">' . esc_html__( 'Select the WooCommerce product type for services.', 'aqualuxe' ) . '</p>';
    }

    /**
     * WooCommerce checkout field
     *
     * @return void
     */
    public function woocommerce_checkout_field() {
        $woocommerce_checkout = isset( $this->settings['woocommerce_checkout'] ) ? $this->settings['woocommerce_checkout'] : 'standard';

        echo '<select name="aqualuxe_bookings_settings[woocommerce_checkout]">';
        echo '<option value="standard" ' . selected( $woocommerce_checkout, 'standard', false ) . '>' . esc_html__( 'Standard Checkout', 'aqualuxe' ) . '</option>';
        echo '<option value="custom" ' . selected( $woocommerce_checkout, 'custom', false ) . '>' . esc_html__( 'Custom Booking Checkout', 'aqualuxe' ) . '</option>';
        echo '</select>';
        echo '<p class="description">' . esc_html__( 'Select the checkout process for bookings with WooCommerce.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Get setting
     *
     * @param string $key Setting key.
     * @param mixed  $default Default value.
     * @return mixed
     */
    public function get_setting( $key, $default = false ) {
        return isset( $this->settings[ $key ] ) ? $this->settings[ $key ] : $default;
    }

    /**
     * Get all settings
     *
     * @return array
     */
    public function get_settings() {
        return $this->settings;
    }

    /**
     * Update setting
     *
     * @param string $key Setting key.
     * @param mixed  $value Setting value.
     * @return void
     */
    public function update_setting( $key, $value ) {
        $this->settings[ $key ] = $value;
        update_option( 'aqualuxe_bookings_settings', $this->settings );
    }

    /**
     * Update settings
     *
     * @param array $settings Settings.
     * @return void
     */
    public function update_settings( $settings ) {
        $this->settings = $settings;
        update_option( 'aqualuxe_bookings_settings', $this->settings );
    }

    /**
     * Delete setting
     *
     * @param string $key Setting key.
     * @return void
     */
    public function delete_setting( $key ) {
        if ( isset( $this->settings[ $key ] ) ) {
            unset( $this->settings[ $key ] );
            update_option( 'aqualuxe_bookings_settings', $this->settings );
        }
    }

    /**
     * Reset settings
     *
     * @return void
     */
    public function reset_settings() {
        $this->settings = [];
        update_option( 'aqualuxe_bookings_settings', $this->settings );
    }

    /**
     * Get booking page URL
     *
     * @return string
     */
    public function get_booking_page_url() {
        $booking_page = $this->get_setting( 'booking_page' );
        return $booking_page ? get_permalink( $booking_page ) : '';
    }

    /**
     * Get services page URL
     *
     * @return string
     */
    public function get_services_page_url() {
        $services_page = $this->get_setting( 'services_page' );
        return $services_page ? get_permalink( $services_page ) : '';
    }

    /**
     * Get calendar page URL
     *
     * @return string
     */
    public function get_calendar_page_url() {
        $calendar_page = $this->get_setting( 'calendar_page' );
        return $calendar_page ? get_permalink( $calendar_page ) : '';
    }

    /**
     * Get minimum notice
     *
     * @return int
     */
    public function get_minimum_notice() {
        return $this->get_setting( 'minimum_notice', 24 );
    }

    /**
     * Get maximum notice
     *
     * @return int
     */
    public function get_maximum_notice() {
        return $this->get_setting( 'maximum_notice', 90 );
    }

    /**
     * Get time format
     *
     * @return string
     */
    public function get_time_format() {
        return $this->get_setting( 'time_format', '12' );
    }

    /**
     * Get date format
     *
     * @return string
     */
    public function get_date_format() {
        return $this->get_setting( 'date_format', 'mm/dd/yyyy' );
    }

    /**
     * Get admin notification
     *
     * @return bool
     */
    public function get_admin_notification() {
        return $this->get_setting( 'admin_notification', true );
    }

    /**
     * Get customer notification
     *
     * @return bool
     */
    public function get_customer_notification() {
        return $this->get_setting( 'customer_notification', true );
    }

    /**
     * Get reminder notification
     *
     * @return bool
     */
    public function get_reminder_notification() {
        return $this->get_setting( 'reminder_notification', true );
    }

    /**
     * Get reminder time
     *
     * @return int
     */
    public function get_reminder_time() {
        return $this->get_setting( 'reminder_time', 24 );
    }

    /**
     * Get WooCommerce integration
     *
     * @return bool
     */
    public function get_woocommerce_integration() {
        return $this->get_setting( 'woocommerce_integration', false );
    }

    /**
     * Get WooCommerce product type
     *
     * @return string
     */
    public function get_woocommerce_product_type() {
        return $this->get_setting( 'woocommerce_product_type', 'simple' );
    }

    /**
     * Get WooCommerce checkout
     *
     * @return string
     */
    public function get_woocommerce_checkout() {
        return $this->get_setting( 'woocommerce_checkout', 'standard' );
    }
}