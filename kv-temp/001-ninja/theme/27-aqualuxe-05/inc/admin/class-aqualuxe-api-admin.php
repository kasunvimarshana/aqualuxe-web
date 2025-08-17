<?php
/**
 * AquaLuxe API Admin
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API_Admin Class
 *
 * Handles admin interface for API settings
 */
class AquaLuxe_API_Admin {

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
        $this->define_hooks();
    }

    /**
     * Get the instance of this class.
     *
     * @return AquaLuxe_API_Admin A single instance of this class.
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Define the hooks for the admin functionality.
     *
     * @return void
     */
    private function define_hooks() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Add admin scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add AJAX handlers
        add_action('wp_ajax_aqualuxe_api_test_connection', array($this, 'test_connection'));
        add_action('wp_ajax_aqualuxe_api_clear_logs', array($this, 'clear_logs'));
        add_action('wp_ajax_aqualuxe_api_regenerate_keys', array($this, 'regenerate_api_keys'));
    }

    /**
     * Add admin menu items.
     *
     * @return void
     */
    public function add_admin_menu() {
        // Add main menu item
        add_menu_page(
            __('AquaLuxe API', 'aqualuxe'),
            __('AquaLuxe API', 'aqualuxe'),
            'manage_options',
            'aqualuxe-api',
            array($this, 'display_dashboard_page'),
            'dashicons-smartphone',
            30
        );
        
        // Add submenu items
        add_submenu_page(
            'aqualuxe-api',
            __('Dashboard', 'aqualuxe'),
            __('Dashboard', 'aqualuxe'),
            'manage_options',
            'aqualuxe-api',
            array($this, 'display_dashboard_page')
        );
        
        add_submenu_page(
            'aqualuxe-api',
            __('App Settings', 'aqualuxe'),
            __('App Settings', 'aqualuxe'),
            'manage_options',
            'aqualuxe-api-settings',
            array($this, 'display_settings_page')
        );
        
        add_submenu_page(
            'aqualuxe-api',
            __('Push Notifications', 'aqualuxe'),
            __('Push Notifications', 'aqualuxe'),
            'manage_options',
            'aqualuxe-api-notifications',
            array($this, 'display_notifications_page')
        );
        
        add_submenu_page(
            'aqualuxe-api',
            __('Sync Management', 'aqualuxe'),
            __('Sync Management', 'aqualuxe'),
            'manage_options',
            'aqualuxe-api-sync',
            array($this, 'display_sync_page')
        );
        
        add_submenu_page(
            'aqualuxe-api',
            __('API Logs', 'aqualuxe'),
            __('API Logs', 'aqualuxe'),
            'manage_options',
            'aqualuxe-api-logs',
            array($this, 'display_logs_page')
        );
        
        add_submenu_page(
            'aqualuxe-api',
            __('Documentation', 'aqualuxe'),
            __('Documentation', 'aqualuxe'),
            'manage_options',
            'aqualuxe-api-docs',
            array($this, 'display_docs_page')
        );
    }

    /**
     * Register settings.
     *
     * @return void
     */
    public function register_settings() {
        // General settings
        register_setting('aqualuxe_api_general', 'aqualuxe_api_enabled');
        register_setting('aqualuxe_api_general', 'aqualuxe_api_allowed_origins');
        register_setting('aqualuxe_api_general', 'aqualuxe_api_rate_limit');
        register_setting('aqualuxe_api_general', 'aqualuxe_api_token_expiration');
        
        // Push notification settings
        register_setting('aqualuxe_api_notifications', 'aqualuxe_push_enabled');
        register_setting('aqualuxe_api_notifications', 'aqualuxe_firebase_server_key');
        register_setting('aqualuxe_api_notifications', 'aqualuxe_firebase_sender_id');
        register_setting('aqualuxe_api_notifications', 'aqualuxe_push_new_order');
        register_setting('aqualuxe_api_notifications', 'aqualuxe_push_order_status');
        register_setting('aqualuxe_api_notifications', 'aqualuxe_push_auction_bid');
        register_setting('aqualuxe_api_notifications', 'aqualuxe_push_auction_end');
        register_setting('aqualuxe_api_notifications', 'aqualuxe_push_trade_in_status');
        
        // Sync settings
        register_setting('aqualuxe_api_sync', 'aqualuxe_sync_interval');
        register_setting('aqualuxe_api_sync', 'aqualuxe_conflict_resolution');
        register_setting('aqualuxe_api_sync', 'aqualuxe_sync_products');
        register_setting('aqualuxe_api_sync', 'aqualuxe_sync_products_batch');
        register_setting('aqualuxe_api_sync', 'aqualuxe_sync_categories');
        register_setting('aqualuxe_api_sync', 'aqualuxe_sync_categories_batch');
        register_setting('aqualuxe_api_sync', 'aqualuxe_sync_orders');
        register_setting('aqualuxe_api_sync', 'aqualuxe_sync_orders_batch');
        register_setting('aqualuxe_api_sync', 'aqualuxe_sync_customers');
        register_setting('aqualuxe_api_sync', 'aqualuxe_sync_customers_batch');
        register_setting('aqualuxe_api_sync', 'aqualuxe_sync_auctions');
        register_setting('aqualuxe_api_sync', 'aqualuxe_sync_auctions_batch');
        register_setting('aqualuxe_api_sync', 'aqualuxe_sync_trade_ins');
        register_setting('aqualuxe_api_sync', 'aqualuxe_sync_trade_ins_batch');
        register_setting('aqualuxe_api_sync', 'aqualuxe_background_sync');
        register_setting('aqualuxe_api_sync', 'aqualuxe_sync_wifi_only');
        register_setting('aqualuxe_api_sync', 'aqualuxe_sync_battery_level');
    }

    /**
     * Enqueue admin scripts and styles.
     *
     * @param string $hook Current admin page.
     * @return void
     */
    public function enqueue_scripts($hook) {
        // Only load on plugin pages
        if (strpos($hook, 'aqualuxe-api') === false) {
            return;
        }
        
        // Enqueue styles
        wp_enqueue_style('aqualuxe-api-admin', plugin_dir_url(__FILE__) . 'css/aqualuxe-api-admin.css', array(), '1.0.0');
        
        // Enqueue scripts
        wp_enqueue_script('aqualuxe-api-admin', plugin_dir_url(__FILE__) . 'js/aqualuxe-api-admin.js', array('jquery'), '1.0.0', true);
        
        // Localize script
        wp_localize_script('aqualuxe-api-admin', 'aqualuxe_api', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_api_nonce'),
        ));
    }

    /**
     * Display dashboard page.
     *
     * @return void
     */
    public function display_dashboard_page() {
        // Get API stats
        $api_enabled = get_option('aqualuxe_api_enabled', true);
        $total_requests = $this->get_total_api_requests();
        $active_users = $this->get_active_api_users();
        $device_count = $this->get_registered_device_count();
        
        // Get recent logs
        $recent_logs = $this->get_recent_api_logs(5);
        
        // Include template
        include plugin_dir_path(__FILE__) . 'templates/dashboard.php';
    }

    /**
     * Display settings page.
     *
     * @return void
     */
    public function display_settings_page() {
        // Get current settings
        $api_enabled = get_option('aqualuxe_api_enabled', true);
        $allowed_origins = get_option('aqualuxe_api_allowed_origins', array('*'));
        $rate_limit = get_option('aqualuxe_api_rate_limit', 60);
        $token_expiration = get_option('aqualuxe_api_token_expiration', 7);
        
        // Get API keys
        $api_keys = $this->get_api_keys();
        
        // Include template
        include plugin_dir_path(__FILE__) . 'templates/settings.php';
    }

    /**
     * Display notifications page.
     *
     * @return void
     */
    public function display_notifications_page() {
        // Get current settings
        $push_enabled = get_option('aqualuxe_push_enabled', false);
        $firebase_server_key = get_option('aqualuxe_firebase_server_key', '');
        $firebase_sender_id = get_option('aqualuxe_firebase_sender_id', '');
        $push_new_order = get_option('aqualuxe_push_new_order', true);
        $push_order_status = get_option('aqualuxe_push_order_status', true);
        $push_auction_bid = get_option('aqualuxe_push_auction_bid', true);
        $push_auction_end = get_option('aqualuxe_push_auction_end', true);
        $push_trade_in_status = get_option('aqualuxe_push_trade_in_status', true);
        
        // Get device stats
        $device_count = $this->get_registered_device_count();
        $device_platforms = $this->get_device_platforms();
        
        // Include template
        include plugin_dir_path(__FILE__) . 'templates/notifications.php';
    }

    /**
     * Display sync page.
     *
     * @return void
     */
    public function display_sync_page() {
        // Get current settings
        $sync_interval = get_option('aqualuxe_sync_interval', 15);
        $conflict_resolution = get_option('aqualuxe_conflict_resolution', 'server');
        $sync_products = get_option('aqualuxe_sync_products', true);
        $sync_products_batch = get_option('aqualuxe_sync_products_batch', 50);
        $sync_categories = get_option('aqualuxe_sync_categories', true);
        $sync_categories_batch = get_option('aqualuxe_sync_categories_batch', 100);
        $sync_orders = get_option('aqualuxe_sync_orders', true);
        $sync_orders_batch = get_option('aqualuxe_sync_orders_batch', 20);
        $sync_customers = get_option('aqualuxe_sync_customers', true);
        $sync_customers_batch = get_option('aqualuxe_sync_customers_batch', 50);
        $sync_auctions = get_option('aqualuxe_sync_auctions', true);
        $sync_auctions_batch = get_option('aqualuxe_sync_auctions_batch', 20);
        $sync_trade_ins = get_option('aqualuxe_sync_trade_ins', true);
        $sync_trade_ins_batch = get_option('aqualuxe_sync_trade_ins_batch', 20);
        $background_sync = get_option('aqualuxe_background_sync', true);
        $sync_wifi_only = get_option('aqualuxe_sync_wifi_only', false);
        $sync_battery_level = get_option('aqualuxe_sync_battery_level', 20);
        
        // Get sync stats
        $sync_stats = $this->get_sync_stats();
        
        // Include template
        include plugin_dir_path(__FILE__) . 'templates/sync.php';
    }

    /**
     * Display logs page.
     *
     * @return void
     */
    public function display_logs_page() {
        // Get logs
        $logs = $this->get_api_logs();
        
        // Include template
        include plugin_dir_path(__FILE__) . 'templates/logs.php';
    }

    /**
     * Display documentation page.
     *
     * @return void
     */
    public function display_docs_page() {
        // Include template
        include plugin_dir_path(__FILE__) . 'templates/docs.php';
    }

    /**
     * Test API connection.
     *
     * @return void
     */
    public function test_connection() {
        // Check nonce
        check_ajax_referer('aqualuxe_api_nonce', 'nonce');
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'aqualuxe')));
        }
        
        // Test connection
        $result = array(
            'success' => true,
            'message' => __('API connection successful!', 'aqualuxe'),
            'data' => array(
                'api_version' => AquaLuxe_API::get_instance()->get_version(),
                'api_namespace' => AquaLuxe_API::get_instance()->get_namespace(),
                'server_time' => current_time('mysql'),
            ),
        );
        
        wp_send_json_success($result);
    }

    /**
     * Clear API logs.
     *
     * @return void
     */
    public function clear_logs() {
        // Check nonce
        check_ajax_referer('aqualuxe_api_nonce', 'nonce');
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'aqualuxe')));
        }
        
        global $wpdb;
        
        // Clear logs
        $table_name = $wpdb->prefix . 'aqualuxe_api_logs';
        $wpdb->query("TRUNCATE TABLE $table_name");
        
        wp_send_json_success(array('message' => __('API logs cleared successfully!', 'aqualuxe')));
    }

    /**
     * Regenerate API keys.
     *
     * @return void
     */
    public function regenerate_api_keys() {
        // Check nonce
        check_ajax_referer('aqualuxe_api_nonce', 'nonce');
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('You do not have permission to perform this action.', 'aqualuxe')));
        }
        
        global $wpdb;
        
        // Delete existing default API key
        $table_name = $wpdb->prefix . 'aqualuxe_api_keys';
        $wpdb->delete(
            $table_name,
            array('description' => 'Default API Key'),
            array('%s')
        );
        
        // Generate new API key
        $consumer_key = 'ck_' . wc_rand_hash();
        $consumer_secret = 'cs_' . wc_rand_hash();
        
        // Get admin user
        $admin_user = get_user_by('login', 'admin');
        
        if (!$admin_user) {
            // Get any admin user
            $admin_users = get_users(array(
                'role' => 'administrator',
                'number' => 1,
            ));
            
            if (empty($admin_users)) {
                wp_send_json_error(array('message' => __('No admin user found.', 'aqualuxe')));
                return;
            }
            
            $admin_user = $admin_users[0];
        }
        
        // Insert API key
        $wpdb->insert(
            $table_name,
            array(
                'user_id' => $admin_user->ID,
                'description' => 'Default API Key',
                'permissions' => 'read_write',
                'consumer_key' => wc_api_hash($consumer_key),
                'consumer_secret' => $consumer_secret,
                'truncated_key' => substr($consumer_key, -7),
                'date_created' => current_time('mysql'),
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            )
        );
        
        // Store API key in option for reference
        update_option('aqualuxe_default_api_key', $consumer_key);
        update_option('aqualuxe_default_api_secret', $consumer_secret);
        
        wp_send_json_success(array(
            'message' => __('API keys regenerated successfully!', 'aqualuxe'),
            'key' => $consumer_key,
            'secret' => $consumer_secret,
        ));
    }

    /**
     * Get total API requests.
     *
     * @return int
     */
    private function get_total_api_requests() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_api_logs';
        
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        
        return $count ? intval($count) : 0;
    }

    /**
     * Get active API users.
     *
     * @return int
     */
    private function get_active_api_users() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_api_logs';
        
        $count = $wpdb->get_var("SELECT COUNT(DISTINCT user_id) FROM $table_name WHERE user_id > 0");
        
        return $count ? intval($count) : 0;
    }

    /**
     * Get registered device count.
     *
     * @return int
     */
    private function get_registered_device_count() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_device_tokens';
        
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        
        return $count ? intval($count) : 0;
    }

    /**
     * Get device platforms.
     *
     * @return array
     */
    private function get_device_platforms() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_device_tokens';
        
        $results = $wpdb->get_results("SELECT device_type, COUNT(*) as count FROM $table_name GROUP BY device_type");
        
        $platforms = array();
        
        foreach ($results as $result) {
            $platforms[$result->device_type] = $result->count;
        }
        
        return $platforms;
    }

    /**
     * Get recent API logs.
     *
     * @param int $limit Number of logs to get.
     * @return array
     */
    private function get_recent_api_logs($limit = 5) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_api_logs';
        
        $logs = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name ORDER BY timestamp DESC LIMIT %d",
                $limit
            )
        );
        
        return $logs;
    }

    /**
     * Get API logs.
     *
     * @param int $page Page number.
     * @param int $per_page Items per page.
     * @return array
     */
    private function get_api_logs($page = 1, $per_page = 20) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_api_logs';
        
        $offset = ($page - 1) * $per_page;
        
        $logs = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name ORDER BY timestamp DESC LIMIT %d OFFSET %d",
                $per_page,
                $offset
            )
        );
        
        $total = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        
        return array(
            'logs' => $logs,
            'total' => $total,
            'pages' => ceil($total / $per_page),
            'page' => $page,
        );
    }

    /**
     * Get API keys.
     *
     * @return array
     */
    private function get_api_keys() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_api_keys';
        
        $keys = $wpdb->get_results("SELECT * FROM $table_name");
        
        $default_key = get_option('aqualuxe_default_api_key', '');
        $default_secret = get_option('aqualuxe_default_api_secret', '');
        
        return array(
            'keys' => $keys,
            'default_key' => $default_key,
            'default_secret' => $default_secret,
        );
    }

    /**
     * Get sync stats.
     *
     * @return array
     */
    private function get_sync_stats() {
        // Get entity counts
        $product_count = $this->get_entity_count('product');
        $category_count = $this->get_entity_count('product_cat');
        $order_count = $this->get_entity_count('shop_order');
        $customer_count = $this->get_customer_count();
        $auction_count = $this->get_entity_count('auction');
        $trade_in_count = $this->get_entity_count('trade_in_request');
        
        // Get sync counts
        $synced_products = $this->get_synced_count('product');
        $synced_categories = $this->get_synced_count('product_cat', 'term');
        $synced_orders = $this->get_synced_count('shop_order');
        $synced_customers = $this->get_synced_count('user', 'user');
        $synced_auctions = $this->get_synced_count('auction');
        $synced_trade_ins = $this->get_synced_count('trade_in_request');
        
        return array(
            'entities' => array(
                'products' => array(
                    'total' => $product_count,
                    'synced' => $synced_products,
                    'percent' => $product_count > 0 ? round(($synced_products / $product_count) * 100) : 0,
                ),
                'categories' => array(
                    'total' => $category_count,
                    'synced' => $synced_categories,
                    'percent' => $category_count > 0 ? round(($synced_categories / $category_count) * 100) : 0,
                ),
                'orders' => array(
                    'total' => $order_count,
                    'synced' => $synced_orders,
                    'percent' => $order_count > 0 ? round(($synced_orders / $order_count) * 100) : 0,
                ),
                'customers' => array(
                    'total' => $customer_count,
                    'synced' => $synced_customers,
                    'percent' => $customer_count > 0 ? round(($synced_customers / $customer_count) * 100) : 0,
                ),
                'auctions' => array(
                    'total' => $auction_count,
                    'synced' => $synced_auctions,
                    'percent' => $auction_count > 0 ? round(($synced_auctions / $auction_count) * 100) : 0,
                ),
                'trade_ins' => array(
                    'total' => $trade_in_count,
                    'synced' => $synced_trade_ins,
                    'percent' => $trade_in_count > 0 ? round(($synced_trade_ins / $trade_in_count) * 100) : 0,
                ),
            ),
            'last_sync' => get_option('aqualuxe_last_full_sync', ''),
        );
    }

    /**
     * Get entity count.
     *
     * @param string $post_type Post type.
     * @return int
     */
    private function get_entity_count($post_type) {
        $count_posts = wp_count_posts($post_type);
        
        if ($post_type === 'shop_order') {
            // For orders, only count processing, completed, and on-hold
            $count = 0;
            
            if (isset($count_posts->processing)) {
                $count += $count_posts->processing;
            }
            
            if (isset($count_posts->completed)) {
                $count += $count_posts->completed;
            }
            
            if (isset($count_posts->{'on-hold'})) {
                $count += $count_posts->{'on-hold'};
            }
            
            return $count;
        } else {
            // For other post types, count published
            return isset($count_posts->publish) ? $count_posts->publish : 0;
        }
    }

    /**
     * Get customer count.
     *
     * @return int
     */
    private function get_customer_count() {
        $customer_query = new WP_User_Query(array(
            'role' => 'customer',
            'count_total' => true,
        ));
        
        return $customer_query->get_total();
    }

    /**
     * Get synced count.
     *
     * @param string $type Entity type.
     * @param string $meta_type Meta type (post, term, user).
     * @return int
     */
    private function get_synced_count($type, $meta_type = 'post') {
        global $wpdb;
        
        switch ($meta_type) {
            case 'post':
                $table_name = $wpdb->postmeta;
                $id_column = 'post_id';
                $type_column = 'post_type';
                $type_table = $wpdb->posts;
                break;
            case 'term':
                $table_name = $wpdb->termmeta;
                $id_column = 'term_id';
                $type_column = 'taxonomy';
                $type_table = $wpdb->term_taxonomy;
                break;
            case 'user':
                $table_name = $wpdb->usermeta;
                $id_column = 'user_id';
                $type_column = 'meta_key';
                $type_table = $wpdb->usermeta;
                break;
            default:
                return 0;
        }
        
        if ($meta_type === 'user') {
            // For users, count those with _sync_version meta
            $count = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(DISTINCT $id_column) FROM $table_name WHERE meta_key = %s",
                    '_sync_version'
                )
            );
        } else {
            // For posts and terms, count those with _sync_version meta and matching type
            $count = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(DISTINCT m.$id_column) FROM $table_name m
                    INNER JOIN $type_table t ON m.$id_column = t.$id_column
                    WHERE m.meta_key = %s AND t.$type_column = %s",
                    '_sync_version',
                    $type
                )
            );
        }
        
        return $count ? intval($count) : 0;
    }
}

// Initialize the admin class
AquaLuxe_API_Admin::get_instance();