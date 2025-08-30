<?php
/**
 * AquaLuxe Analytics Wrapper
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_Analytics_Wrapper Class
 *
 * Wrapper class for analytics functionality to ensure compatibility with or without WooCommerce
 */
class AquaLuxe_Analytics_Wrapper {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        // Hook into WordPress
        add_action('admin_menu', array($this, 'add_analytics_menu'), 20);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Get instance of this class.
     *
     * @return AquaLuxe_Analytics_Wrapper
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Add analytics menu item
     */
    public function add_analytics_menu() {
        add_menu_page(
            __('Analytics', 'aqualuxe'),
            __('Analytics', 'aqualuxe'),
            'manage_options',
            'aqualuxe-analytics',
            array($this, 'render_analytics_page'),
            'dashicons-chart-bar',
            30
        );
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts($hook) {
        if ('toplevel_page_aqualuxe-analytics' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'aqualuxe-analytics-dashboard',
            AQUALUXE_URI . 'assets/css/analytics/analytics-dashboard.css',
            array(),
            AQUALUXE_VERSION
        );

        // Only load analytics scripts if WooCommerce is active
        if (function_exists('aqualuxe_is_analytics_available') && aqualuxe_is_analytics_available()) {
            wp_enqueue_script(
                'aqualuxe-analytics-charts',
                AQUALUXE_URI . 'assets/js/analytics/analytics-charts.js',
                array('jquery'),
                AQUALUXE_VERSION,
                true
            );

            wp_enqueue_script(
                'aqualuxe-analytics-dashboard',
                AQUALUXE_URI . 'assets/js/analytics/analytics-dashboard.js',
                array('jquery', 'aqualuxe-analytics-charts'),
                AQUALUXE_VERSION,
                true
            );

            wp_enqueue_script(
                'aqualuxe-analytics-filters',
                AQUALUXE_URI . 'assets/js/analytics/analytics-filters.js',
                array('jquery', 'aqualuxe-analytics-dashboard'),
                AQUALUXE_VERSION,
                true
            );

            wp_localize_script('aqualuxe-analytics-dashboard', 'aqualuxeAnalytics', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe-analytics-nonce'),
            ));
        }
    }

    /**
     * Render analytics page
     */
    public function render_analytics_page() {
        // Check if WooCommerce is active
        if (function_exists('aqualuxe_is_analytics_available') && aqualuxe_is_analytics_available()) {
            // Load the full analytics dashboard if WooCommerce is active
            if (class_exists('AquaLuxe_Analytics_Dashboard')) {
                $dashboard = new AquaLuxe_Analytics_Dashboard();
                $dashboard->render_dashboard();
            } else {
                $this->render_analytics_fallback();
            }
        } else {
            // Load the fallback template if WooCommerce is not active
            $this->render_analytics_fallback();
        }
    }

    /**
     * Render analytics fallback
     */
    private function render_analytics_fallback() {
        // Include the fallback template
        $fallback_template = AQUALUXE_DIR . 'template-parts/analytics/fallback-dashboard.php';
        
        if (file_exists($fallback_template)) {
            include $fallback_template;
        } else {
            // Basic fallback if template doesn't exist
            echo '<div class="wrap">';
            echo '<h1>' . esc_html__('Analytics Dashboard', 'aqualuxe') . '</h1>';
            echo '<div class="notice notice-warning"><p>' . esc_html__('The analytics dashboard requires WooCommerce to be active.', 'aqualuxe') . '</p></div>';
            echo '</div>';
        }
    }
}

// Initialize the analytics wrapper
AquaLuxe_Analytics_Wrapper::get_instance();