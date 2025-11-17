<?php
/**
 * AquaLuxe Analytics
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_Analytics Class
 *
 * Main class for the analytics dashboard functionality
 */
class AquaLuxe_Analytics {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * The version of this plugin.
     *
     * @var string
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        $this->version = AQUALUXE_VERSION;
        $this->load_dependencies();
        $this->define_hooks();
    }

    /**
     * Get the instance of this class.
     *
     * @return AquaLuxe_Analytics A single instance of this class.
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * @return void
     */
    private function load_dependencies() {
        // Include the data processing class
        require_once plugin_dir_path(dirname(__FILE__)) . 'analytics/class-aqualuxe-analytics-data.php';
        
        // Include the reports class
        require_once plugin_dir_path(dirname(__FILE__)) . 'analytics/class-aqualuxe-analytics-reports.php';
        
        // Include the dashboard class
        require_once plugin_dir_path(dirname(__FILE__)) . 'analytics/class-aqualuxe-analytics-dashboard.php';
        
        // Include the admin class
        require_once plugin_dir_path(dirname(__FILE__)) . 'analytics/class-aqualuxe-analytics-admin.php';
    }

    /**
     * Define the hooks for the analytics functionality.
     *
     * @return void
     */
    private function define_hooks() {
        // Register activation hook
        register_activation_hook(__FILE__, array($this, 'activate'));
        
        // Register deactivation hook
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // Register REST API endpoints
        add_action('rest_api_init', array($this, 'register_rest_routes'));
        
        // Schedule data aggregation
        add_action('wp', array($this, 'schedule_events'));
        
        // Hook into WooCommerce order status changes
        add_action('woocommerce_order_status_changed', array($this, 'process_order_status_change'), 10, 4);
        
        // Hook into subscription events
        add_action('aqualuxe_subscription_created', array($this, 'process_subscription_created'), 10, 1);
        add_action('aqualuxe_subscription_renewed', array($this, 'process_subscription_renewed'), 10, 2);
        add_action('aqualuxe_subscription_cancelled', array($this, 'process_subscription_cancelled'), 10, 1);
    }

    /**
     * Runs on plugin activation.
     *
     * @return void
     */
    public function activate() {
        // Create database tables
        $this->create_tables();
        
        // Schedule initial data aggregation
        wp_schedule_single_event(time() + 60, 'aqualuxe_analytics_aggregate_data');
    }

    /**
     * Runs on plugin deactivation.
     *
     * @return void
     */
    public function deactivate() {
        // Clear scheduled events
        wp_clear_scheduled_hook('aqualuxe_analytics_daily_aggregate');
        wp_clear_scheduled_hook('aqualuxe_analytics_weekly_aggregate');
        wp_clear_scheduled_hook('aqualuxe_analytics_monthly_aggregate');
    }

    /**
     * Create database tables for analytics.
     *
     * @return void
     */
    private function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Analytics data table
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $sql = "CREATE TABLE $table_name (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            date_created datetime NOT NULL,
            data_type varchar(50) NOT NULL,
            data_key varchar(100) NOT NULL,
            data_value decimal(19,4) NOT NULL DEFAULT 0,
            data_count int(11) NOT NULL DEFAULT 0,
            data_meta longtext,
            PRIMARY KEY (id),
            KEY date_created (date_created),
            KEY data_type (data_type),
            KEY data_key (data_key)
        ) $charset_collate;";
        
        // Analytics settings table
        $table_name_settings = $wpdb->prefix . 'aqualuxe_analytics_settings';
        
        $sql .= "CREATE TABLE $table_name_settings (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_id bigint(20) unsigned NOT NULL,
            setting_key varchar(100) NOT NULL,
            setting_value longtext,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY setting_key (setting_key)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Add admin menu items for analytics.
     *
     * @return void
     */
    public function add_admin_menu() {
        // Add main analytics menu
        add_menu_page(
            __('Analytics Dashboard', 'aqualuxe'),
            __('Analytics', 'aqualuxe'),
            'manage_options',
            'aqualuxe-analytics',
            array($this, 'display_dashboard_page'),
            'dashicons-chart-area',
            30
        );
        
        // Add submenu pages
        add_submenu_page(
            'aqualuxe-analytics',
            __('Dashboard', 'aqualuxe'),
            __('Dashboard', 'aqualuxe'),
            'manage_options',
            'aqualuxe-analytics',
            array($this, 'display_dashboard_page')
        );
        
        add_submenu_page(
            'aqualuxe-analytics',
            __('Sales Reports', 'aqualuxe'),
            __('Sales', 'aqualuxe'),
            'manage_options',
            'aqualuxe-analytics-sales',
            array($this, 'display_sales_page')
        );
        
        add_submenu_page(
            'aqualuxe-analytics',
            __('Inventory Reports', 'aqualuxe'),
            __('Inventory', 'aqualuxe'),
            'manage_options',
            'aqualuxe-analytics-inventory',
            array($this, 'display_inventory_page')
        );
        
        add_submenu_page(
            'aqualuxe-analytics',
            __('Customer Reports', 'aqualuxe'),
            __('Customers', 'aqualuxe'),
            'manage_options',
            'aqualuxe-analytics-customers',
            array($this, 'display_customers_page')
        );
        
        add_submenu_page(
            'aqualuxe-analytics',
            __('Subscription Reports', 'aqualuxe'),
            __('Subscriptions', 'aqualuxe'),
            'manage_options',
            'aqualuxe-analytics-subscriptions',
            array($this, 'display_subscriptions_page')
        );
        
        add_submenu_page(
            'aqualuxe-analytics',
            __('Analytics Settings', 'aqualuxe'),
            __('Settings', 'aqualuxe'),
            'manage_options',
            'aqualuxe-analytics-settings',
            array($this, 'display_settings_page')
        );
    }

    /**
     * Enqueue admin scripts and styles.
     *
     * @param string $hook The current admin page.
     * @return void
     */
    public function enqueue_admin_assets($hook) {
        // Only load on analytics pages
        if (strpos($hook, 'aqualuxe-analytics') === false) {
            return;
        }
        
        // Enqueue Chart.js
        wp_enqueue_script(
            'chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js',
            array(),
            '3.7.1',
            true
        );
        
        // Enqueue Date Range Picker
        wp_enqueue_script(
            'daterangepicker',
            'https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js',
            array('jquery', 'moment'),
            '3.1.0',
            true
        );
        
        wp_enqueue_style(
            'daterangepicker-css',
            'https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css',
            array(),
            '3.1.0'
        );
        
        // Enqueue Moment.js
        wp_enqueue_script(
            'moment',
            'https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js',
            array(),
            '2.29.1',
            true
        );
        
        // Enqueue our custom scripts
        wp_enqueue_script(
            'aqualuxe-analytics-dashboard',
            get_template_directory_uri() . '/assets/js/analytics/analytics-dashboard.js',
            array('jquery', 'chartjs', 'moment', 'daterangepicker'),
            $this->version,
            true
        );
        
        wp_enqueue_script(
            'aqualuxe-analytics-charts',
            get_template_directory_uri() . '/assets/js/analytics/analytics-charts.js',
            array('jquery', 'chartjs'),
            $this->version,
            true
        );
        
        wp_enqueue_script(
            'aqualuxe-analytics-filters',
            get_template_directory_uri() . '/assets/js/analytics/analytics-filters.js',
            array('jquery', 'daterangepicker'),
            $this->version,
            true
        );
        
        // Enqueue our custom styles
        wp_enqueue_style(
            'aqualuxe-analytics-dashboard',
            get_template_directory_uri() . '/assets/css/analytics/analytics-dashboard.css',
            array(),
            $this->version
        );
        
        // Localize script with data
        wp_localize_script(
            'aqualuxe-analytics-dashboard',
            'aqualuxeAnalytics',
            array(
                'apiUrl' => rest_url('aqualuxe/v1/analytics/'),
                'nonce' => wp_create_nonce('wp_rest'),
                'currency' => get_woocommerce_currency_symbol(),
                'dateFormat' => get_option('date_format'),
                'startOfWeek' => get_option('start_of_week'),
            )
        );
    }

    /**
     * Register REST API routes.
     *
     * @return void
     */
    public function register_rest_routes() {
        // Register dashboard endpoint
        register_rest_route(
            'aqualuxe/v1',
            '/analytics/dashboard',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_dashboard_data'),
                'permission_callback' => array($this, 'check_permissions'),
            )
        );
        
        // Register sales endpoint
        register_rest_route(
            'aqualuxe/v1',
            '/analytics/sales',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_sales_data'),
                'permission_callback' => array($this, 'check_permissions'),
            )
        );
        
        // Register inventory endpoint
        register_rest_route(
            'aqualuxe/v1',
            '/analytics/inventory',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_inventory_data'),
                'permission_callback' => array($this, 'check_permissions'),
            )
        );
        
        // Register customers endpoint
        register_rest_route(
            'aqualuxe/v1',
            '/analytics/customers',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_customers_data'),
                'permission_callback' => array($this, 'check_permissions'),
            )
        );
        
        // Register subscriptions endpoint
        register_rest_route(
            'aqualuxe/v1',
            '/analytics/subscriptions',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'get_subscriptions_data'),
                'permission_callback' => array($this, 'check_permissions'),
            )
        );
    }

    /**
     * Check if user has permission to access analytics.
     *
     * @param WP_REST_Request $request The request object.
     * @return bool
     */
    public function check_permissions($request) {
        return current_user_can('manage_options');
    }

    /**
     * Schedule recurring events for data aggregation.
     *
     * @return void
     */
    public function schedule_events() {
        if (!wp_next_scheduled('aqualuxe_analytics_daily_aggregate')) {
            wp_schedule_event(strtotime('tomorrow midnight'), 'daily', 'aqualuxe_analytics_daily_aggregate');
        }
        
        if (!wp_next_scheduled('aqualuxe_analytics_weekly_aggregate')) {
            wp_schedule_event(strtotime('next monday midnight'), 'weekly', 'aqualuxe_analytics_weekly_aggregate');
        }
        
        if (!wp_next_scheduled('aqualuxe_analytics_monthly_aggregate')) {
            wp_schedule_event(strtotime('first day of next month midnight'), 'monthly', 'aqualuxe_analytics_monthly_aggregate');
        }
    }

    /**
     * Process order status changes for analytics.
     *
     * @param int $order_id The order ID.
     * @param string $old_status The old order status.
     * @param string $new_status The new order status.
     * @param WC_Order $order The order object.
     * @return void
     */
    public function process_order_status_change($order_id, $old_status, $new_status, $order) {
        // Only process completed orders
        if ($new_status !== 'completed') {
            return;
        }
        
        // Get order data
        $order_total = $order->get_total();
        $order_date = $order->get_date_created()->format('Y-m-d H:i:s');
        $customer_id = $order->get_customer_id();
        
        // Store sales data
        $this->store_analytics_data('sales', 'total', $order_total, 1, array(
            'order_id' => $order_id,
            'customer_id' => $customer_id,
            'date' => $order_date,
        ));
        
        // Process order items
        foreach ($order->get_items() as $item) {
            $product_id = $item->get_product_id();
            $variation_id = $item->get_variation_id();
            $quantity = $item->get_quantity();
            $total = $item->get_total();
            
            // Store product sales data
            $this->store_analytics_data('sales', 'product_' . $product_id, $total, $quantity, array(
                'order_id' => $order_id,
                'product_id' => $product_id,
                'variation_id' => $variation_id,
                'date' => $order_date,
            ));
            
            // Update inventory data
            $this->store_analytics_data('inventory', 'product_' . $product_id, 0, $quantity, array(
                'order_id' => $order_id,
                'product_id' => $product_id,
                'variation_id' => $variation_id,
                'date' => $order_date,
                'action' => 'sale',
            ));
        }
        
        // Store customer data
        $this->store_analytics_data('customers', 'purchase_' . $customer_id, $order_total, 1, array(
            'order_id' => $order_id,
            'customer_id' => $customer_id,
            'date' => $order_date,
        ));
    }

    /**
     * Process subscription creation for analytics.
     *
     * @param int $subscription_id The subscription ID.
     * @return void
     */
    public function process_subscription_created($subscription_id) {
        $customer_id = get_post_meta($subscription_id, '_customer_id', true);
        $frequency = get_post_meta($subscription_id, '_frequency', true);
        $initial_order_id = get_post_meta($subscription_id, '_original_order_id', true);
        
        // Store subscription data
        $this->store_analytics_data('subscriptions', 'new', 1, 1, array(
            'subscription_id' => $subscription_id,
            'customer_id' => $customer_id,
            'frequency' => $frequency,
            'initial_order_id' => $initial_order_id,
            'date' => current_time('mysql'),
        ));
    }

    /**
     * Process subscription renewal for analytics.
     *
     * @param int $subscription_id The subscription ID.
     * @param int $order_id The renewal order ID.
     * @return void
     */
    public function process_subscription_renewed($subscription_id, $order_id) {
        $order = wc_get_order($order_id);
        if (!$order) {
            return;
        }
        
        $order_total = $order->get_total();
        $customer_id = get_post_meta($subscription_id, '_customer_id', true);
        
        // Store subscription renewal data
        $this->store_analytics_data('subscriptions', 'renewal', $order_total, 1, array(
            'subscription_id' => $subscription_id,
            'order_id' => $order_id,
            'customer_id' => $customer_id,
            'date' => current_time('mysql'),
        ));
    }

    /**
     * Process subscription cancellation for analytics.
     *
     * @param int $subscription_id The subscription ID.
     * @return void
     */
    public function process_subscription_cancelled($subscription_id) {
        $customer_id = get_post_meta($subscription_id, '_customer_id', true);
        
        // Store subscription cancellation data
        $this->store_analytics_data('subscriptions', 'cancelled', 1, 1, array(
            'subscription_id' => $subscription_id,
            'customer_id' => $customer_id,
            'date' => current_time('mysql'),
        ));
    }

    /**
     * Store analytics data in the database.
     *
     * @param string $data_type The type of data (sales, inventory, customers, subscriptions).
     * @param string $data_key The specific data key.
     * @param float $data_value The value to store.
     * @param int $data_count The count to store.
     * @param array $data_meta Additional metadata.
     * @return bool|int The ID of the inserted row, or false on failure.
     */
    public function store_analytics_data($data_type, $data_key, $data_value, $data_count, $data_meta = array()) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'date_created' => current_time('mysql'),
                'data_type' => $data_type,
                'data_key' => $data_key,
                'data_value' => $data_value,
                'data_count' => $data_count,
                'data_meta' => maybe_serialize($data_meta),
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%f',
                '%d',
                '%s',
            )
        );
        
        if ($result) {
            return $wpdb->insert_id;
        }
        
        return false;
    }

    /**
     * Get dashboard data for the REST API.
     *
     * @param WP_REST_Request $request The request object.
     * @return WP_REST_Response
     */
    public function get_dashboard_data($request) {
        $analytics_dashboard = new AquaLuxe_Analytics_Dashboard();
        $data = $analytics_dashboard->get_dashboard_data($request->get_params());
        
        return rest_ensure_response($data);
    }

    /**
     * Get sales data for the REST API.
     *
     * @param WP_REST_Request $request The request object.
     * @return WP_REST_Response
     */
    public function get_sales_data($request) {
        $analytics_reports = new AquaLuxe_Analytics_Reports();
        $data = $analytics_reports->get_sales_data($request->get_params());
        
        return rest_ensure_response($data);
    }

    /**
     * Get inventory data for the REST API.
     *
     * @param WP_REST_Request $request The request object.
     * @return WP_REST_Response
     */
    public function get_inventory_data($request) {
        $analytics_reports = new AquaLuxe_Analytics_Reports();
        $data = $analytics_reports->get_inventory_data($request->get_params());
        
        return rest_ensure_response($data);
    }

    /**
     * Get customers data for the REST API.
     *
     * @param WP_REST_Request $request The request object.
     * @return WP_REST_Response
     */
    public function get_customers_data($request) {
        $analytics_reports = new AquaLuxe_Analytics_Reports();
        $data = $analytics_reports->get_customers_data($request->get_params());
        
        return rest_ensure_response($data);
    }

    /**
     * Get subscriptions data for the REST API.
     *
     * @param WP_REST_Request $request The request object.
     * @return WP_REST_Response
     */
    public function get_subscriptions_data($request) {
        $analytics_reports = new AquaLuxe_Analytics_Reports();
        $data = $analytics_reports->get_subscriptions_data($request->get_params());
        
        return rest_ensure_response($data);
    }

    /**
     * Display the dashboard page.
     *
     * @return void
     */
    public function display_dashboard_page() {
        include get_template_directory() . '/templates/analytics/dashboard.php';
    }

    /**
     * Display the sales page.
     *
     * @return void
     */
    public function display_sales_page() {
        include get_template_directory() . '/templates/analytics/sales-report.php';
    }

    /**
     * Display the inventory page.
     *
     * @return void
     */
    public function display_inventory_page() {
        include get_template_directory() . '/templates/analytics/inventory-report.php';
    }

    /**
     * Display the customers page.
     *
     * @return void
     */
    public function display_customers_page() {
        include get_template_directory() . '/templates/analytics/customer-report.php';
    }

    /**
     * Display the subscriptions page.
     *
     * @return void
     */
    public function display_subscriptions_page() {
        include get_template_directory() . '/templates/analytics/subscription-report.php';
    }

    /**
     * Display the settings page.
     *
     * @return void
     */
    public function display_settings_page() {
        include get_template_directory() . '/templates/analytics/settings.php';
    }
}

// Initialize the analytics system
AquaLuxe_Analytics::get_instance();