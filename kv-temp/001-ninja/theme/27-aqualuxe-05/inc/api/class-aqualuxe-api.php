<?php
/**
 * AquaLuxe API
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_API Class
 *
 * Main class for the API functionality
 */
class AquaLuxe_API {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * The version of this API.
     *
     * @var string
     */
    private $version;

    /**
     * The namespace for this API.
     *
     * @var string
     */
    private $namespace;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        $this->version = '1';
        $this->namespace = 'aqualuxe/v' . $this->version;
        $this->load_dependencies();
        $this->define_hooks();
    }

    /**
     * Get the instance of this class.
     *
     * @return AquaLuxe_API A single instance of this class.
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
        // Include the authentication class
        require_once plugin_dir_path(dirname(__FILE__)) . 'api/class-aqualuxe-api-authentication.php';
        
        // Include the base controller class
        require_once plugin_dir_path(dirname(__FILE__)) . 'api/class-aqualuxe-api-controller.php';
        
        // Include controller classes
        require_once plugin_dir_path(dirname(__FILE__)) . 'api/controllers/class-aqualuxe-api-products-controller.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'api/controllers/class-aqualuxe-api-orders-controller.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'api/controllers/class-aqualuxe-api-users-controller.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'api/controllers/class-aqualuxe-api-subscriptions-controller.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'api/controllers/class-aqualuxe-api-care-guides-controller.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'api/controllers/class-aqualuxe-api-water-calculator-controller.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'api/controllers/class-aqualuxe-api-compatibility-checker-controller.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'api/controllers/class-aqualuxe-api-auctions-controller.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'api/controllers/class-aqualuxe-api-trade-ins-controller.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'api/controllers/class-aqualuxe-api-sync-controller.php';
        
        // Include notification classes
        require_once plugin_dir_path(dirname(__FILE__)) . 'api/notifications/class-aqualuxe-api-notifications.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'api/notifications/class-aqualuxe-api-push-service.php';
    }

    /**
     * Define the hooks for the API functionality.
     *
     * @return void
     */
    private function define_hooks() {
        // Register REST API routes
        add_action('rest_api_init', array($this, 'register_routes'));
        
        // Add CORS headers
        add_action('rest_api_init', array($this, 'add_cors_headers'));
        
        // Register activation hook
        register_activation_hook(__FILE__, array($this, 'activate'));
        
        // Register deactivation hook
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }

    /**
     * Runs on plugin activation.
     *
     * @return void
     */
    public function activate() {
        // Create database tables
        $this->create_tables();
        
        // Create default API keys
        $this->create_default_api_keys();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Runs on plugin deactivation.
     *
     * @return void
     */
    public function deactivate() {
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Create database tables for API.
     *
     * @return void
     */
    private function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // API keys table
        $table_name = $wpdb->prefix . 'aqualuxe_api_keys';
        
        $sql = "CREATE TABLE $table_name (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_id bigint(20) unsigned NOT NULL,
            description varchar(200) NOT NULL,
            permissions varchar(10) NOT NULL,
            consumer_key char(64) NOT NULL,
            consumer_secret char(43) NOT NULL,
            nonces longtext NULL,
            truncated_key char(7) NOT NULL,
            last_access datetime NULL,
            date_created datetime NOT NULL,
            date_expires datetime NULL,
            PRIMARY KEY (id),
            KEY consumer_key (consumer_key),
            KEY consumer_secret (consumer_secret)
        ) $charset_collate;";
        
        // API logs table
        $table_name_logs = $wpdb->prefix . 'aqualuxe_api_logs';
        
        $sql .= "CREATE TABLE $table_name_logs (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            timestamp datetime NOT NULL,
            user_id bigint(20) unsigned NULL,
            api_key_id bigint(20) unsigned NULL,
            endpoint varchar(255) NOT NULL,
            method varchar(10) NOT NULL,
            request_data longtext NULL,
            response_code smallint(4) NOT NULL,
            response_data longtext NULL,
            ip_address varchar(45) NOT NULL,
            execution_time float NOT NULL,
            PRIMARY KEY (id),
            KEY timestamp (timestamp),
            KEY user_id (user_id),
            KEY api_key_id (api_key_id)
        ) $charset_collate;";
        
        // Device tokens table
        $table_name_tokens = $wpdb->prefix . 'aqualuxe_device_tokens';
        
        $sql .= "CREATE TABLE $table_name_tokens (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_id bigint(20) unsigned NOT NULL,
            device_token varchar(255) NOT NULL,
            device_type varchar(20) NOT NULL,
            device_name varchar(100) NULL,
            app_version varchar(20) NULL,
            os_version varchar(20) NULL,
            date_created datetime NOT NULL,
            date_updated datetime NOT NULL,
            last_active datetime NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY device_token (device_token),
            KEY user_id (user_id)
        ) $charset_collate;";
        
        // Notifications table
        $table_name_notifications = $wpdb->prefix . 'aqualuxe_notifications';
        
        $sql .= "CREATE TABLE $table_name_notifications (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            user_id bigint(20) unsigned NOT NULL,
            type varchar(50) NOT NULL,
            title varchar(255) NOT NULL,
            message text NOT NULL,
            data longtext NULL,
            is_read tinyint(1) NOT NULL DEFAULT 0,
            date_created datetime NOT NULL,
            date_read datetime NULL,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            KEY type (type),
            KEY is_read (is_read)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Create default API keys.
     *
     * @return void
     */
    private function create_default_api_keys() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_api_keys';
        
        // Check if default API key exists
        $key_exists = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE description = %s",
                'Default API Key'
            )
        );
        
        if ($key_exists) {
            return;
        }
        
        // Get admin user
        $admin_user = get_user_by('login', 'admin');
        
        if (!$admin_user) {
            // Get any admin user
            $admin_users = get_users(array(
                'role' => 'administrator',
                'number' => 1,
            ));
            
            if (empty($admin_users)) {
                return;
            }
            
            $admin_user = $admin_users[0];
        }
        
        // Generate API key
        $consumer_key = 'ck_' . wc_rand_hash();
        $consumer_secret = 'cs_' . wc_rand_hash();
        
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
    }

    /**
     * Register REST API routes.
     *
     * @return void
     */
    public function register_routes() {
        // Authentication routes
        $authentication = new AquaLuxe_API_Authentication();
        $authentication->register_routes();
        
        // Products routes
        $products_controller = new AquaLuxe_API_Products_Controller();
        $products_controller->register_routes();
        
        // Orders routes
        $orders_controller = new AquaLuxe_API_Orders_Controller();
        $orders_controller->register_routes();
        
        // Users routes
        $users_controller = new AquaLuxe_API_Users_Controller();
        $users_controller->register_routes();
        
        // Subscriptions routes
        $subscriptions_controller = new AquaLuxe_API_Subscriptions_Controller();
        $subscriptions_controller->register_routes();
        
        // Care guides routes
        $care_guides_controller = new AquaLuxe_API_Care_Guides_Controller();
        $care_guides_controller->register_routes();
        
        // Water calculator routes
        $water_calculator_controller = new AquaLuxe_API_Water_Calculator_Controller();
        $water_calculator_controller->register_routes();
        
        // Compatibility checker routes
        $compatibility_checker_controller = new AquaLuxe_API_Compatibility_Checker_Controller();
        $compatibility_checker_controller->register_routes();
        
        // Auctions routes
        $auctions_controller = new AquaLuxe_API_Auctions_Controller();
        $auctions_controller->register_routes();
        
        // Trade-ins routes
        $trade_ins_controller = new AquaLuxe_API_Trade_Ins_Controller();
        $trade_ins_controller->register_routes();
        
        // Sync routes
        $sync_controller = new AquaLuxe_API_Sync_Controller();
        $sync_controller->register_routes();
        
        // Notifications routes
        $notifications_controller = new AquaLuxe_API_Notifications();
        $notifications_controller->register_routes();
    }

    /**
     * Add CORS headers to API responses.
     *
     * @return void
     */
    public function add_cors_headers() {
        // Get allowed origins from settings
        $allowed_origins = get_option('aqualuxe_api_allowed_origins', array('*'));
        
        // Convert to string for header
        if (is_array($allowed_origins)) {
            $allowed_origins = implode(', ', $allowed_origins);
        }
        
        // Add headers
        add_filter('rest_pre_serve_request', function($served, $result, $request, $server) use ($allowed_origins) {
            header('Access-Control-Allow-Origin: ' . $allowed_origins);
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Allow-Headers: Authorization, Content-Type, X-WP-Nonce, X-API-Key');
            header('Access-Control-Expose-Headers: X-WP-Total, X-WP-TotalPages, X-Rate-Limit-Limit, X-Rate-Limit-Remaining, X-Rate-Limit-Reset');
            
            return $served;
        }, 10, 4);
    }

    /**
     * Log API request.
     *
     * @param WP_REST_Request $request The request object.
     * @param WP_REST_Response $response The response object.
     * @param float $execution_time The execution time in seconds.
     * @return void
     */
    public function log_request($request, $response, $execution_time) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_api_logs';
        
        // Get user ID from request
        $user_id = get_current_user_id();
        
        // Get API key ID from request
        $api_key_id = null;
        
        if (isset($request['api_key'])) {
            $api_key = $request['api_key'];
            
            $api_key_id = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT id FROM {$wpdb->prefix}aqualuxe_api_keys WHERE consumer_key = %s",
                    $api_key
                )
            );
        }
        
        // Get endpoint from request
        $endpoint = $request->get_route();
        
        // Get method from request
        $method = $request->get_method();
        
        // Get request data
        $request_data = json_encode($request->get_params());
        
        // Get response code
        $response_code = $response->get_status();
        
        // Get response data
        $response_data = json_encode($response->get_data());
        
        // Get IP address
        $ip_address = $_SERVER['REMOTE_ADDR'];
        
        // Insert log entry
        $wpdb->insert(
            $table_name,
            array(
                'timestamp' => current_time('mysql'),
                'user_id' => $user_id,
                'api_key_id' => $api_key_id,
                'endpoint' => $endpoint,
                'method' => $method,
                'request_data' => $request_data,
                'response_code' => $response_code,
                'response_data' => $response_data,
                'ip_address' => $ip_address,
                'execution_time' => $execution_time,
            ),
            array(
                '%s',
                '%d',
                '%d',
                '%s',
                '%s',
                '%s',
                '%d',
                '%s',
                '%s',
                '%f',
            )
        );
    }

    /**
     * Get API namespace.
     *
     * @return string
     */
    public function get_namespace() {
        return $this->namespace;
    }

    /**
     * Get API version.
     *
     * @return string
     */
    public function get_version() {
        return $this->version;
    }
}

// Initialize the API
AquaLuxe_API::get_instance();