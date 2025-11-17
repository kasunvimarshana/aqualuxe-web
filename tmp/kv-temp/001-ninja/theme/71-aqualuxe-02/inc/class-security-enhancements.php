<?php
/**
 * AquaLuxe Security Enhancements & Critical Fixes
 * 
 * This file contains critical security patches and performance enhancements
 * identified during the comprehensive code audit.
 * 
 * @package AquaLuxe
 * @since 1.0.1
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Security Enhancement Class
 * 
 * Provides additional security layers and fixes for identified vulnerabilities
 */
class AquaLuxe_Security_Enhancements {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_security_enhancements();
    }
    
    /**
     * Initialize security enhancements
     */
    private function init_security_enhancements() {
        // Enhanced AJAX nonce validation
        add_action('wp_ajax_aqualuxe_wishlist_add', [$this, 'validate_wishlist_ajax'], 1);
        add_action('wp_ajax_nopriv_aqualuxe_wishlist_add', [$this, 'validate_wishlist_ajax'], 1);
        add_action('wp_ajax_aqualuxe_wishlist_remove', [$this, 'validate_wishlist_ajax'], 1);
        add_action('wp_ajax_nopriv_aqualuxe_wishlist_remove', [$this, 'validate_wishlist_ajax'], 1);
        add_action('wp_ajax_aqualuxe_search', [$this, 'validate_search_ajax'], 1);
        add_action('wp_ajax_nopriv_aqualuxe_search', [$this, 'validate_search_ajax'], 1);
        add_action('wp_ajax_aqualuxe_dark_mode_toggle', [$this, 'validate_dark_mode_ajax'], 1);
        add_action('wp_ajax_nopriv_aqualuxe_dark_mode_toggle', [$this, 'validate_dark_mode_ajax'], 1);
        
        // Enhanced input sanitization filters
        add_filter('aqualuxe_sanitize_input', [$this, 'enhanced_input_sanitization'], 10, 2);
        
        // Rate limiting for AJAX requests
        add_action('wp_ajax_aqualuxe_rate_limit', [$this, 'rate_limit_ajax_requests'], 1);
        add_action('wp_ajax_nopriv_aqualuxe_rate_limit', [$this, 'rate_limit_ajax_requests'], 1);
        
        // Database query optimization
        add_action('init', [$this, 'optimize_database_queries']);
        
        // Enhanced error logging
        add_action('aqualuxe_log_security_event', [$this, 'log_security_event'], 10, 3);
    }
    
    /**
     * Enhanced AJAX nonce validation for wishlist operations
     */
    public function validate_wishlist_ajax() {
        // Enhanced nonce validation
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_wishlist_nonce')) {
            $this->log_security_event('invalid_nonce', 'Wishlist AJAX', $_SERVER['REMOTE_ADDR'] ?? 'unknown');
            wp_send_json_error([
                'message' => __('Security check failed.', 'aqualuxe'),
                'code' => 'invalid_nonce'
            ]);
        }
        
        // Rate limiting check
        if ($this->is_rate_limited('wishlist', 30, 60)) { // 30 requests per minute
            wp_send_json_error([
                'message' => __('Too many requests. Please try again later.', 'aqualuxe'),
                'code' => 'rate_limited'
            ]);
        }
        
        // Enhanced input validation
        $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
        if (!$post_id || !get_post($post_id) || get_post_status($post_id) !== 'publish') {
            wp_send_json_error([
                'message' => __('Invalid or unavailable item.', 'aqualuxe'),
                'code' => 'invalid_item'
            ]);
        }
        
        // Log successful validation
        $this->log_security_event('ajax_validated', 'Wishlist', $post_id);
    }
    
    /**
     * Enhanced AJAX validation for search operations
     */
    public function validate_search_ajax() {
        // Nonce validation
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_search_nonce')) {
            $this->log_security_event('invalid_nonce', 'Search AJAX', $_SERVER['REMOTE_ADDR'] ?? 'unknown');
            wp_send_json_error([
                'message' => __('Security check failed.', 'aqualuxe'),
                'code' => 'invalid_nonce'
            ]);
        }
        
        // Rate limiting (more restrictive for search)
        if ($this->is_rate_limited('search', 20, 60)) { // 20 searches per minute
            wp_send_json_error([
                'message' => __('Search rate limit exceeded.', 'aqualuxe'),
                'code' => 'rate_limited'
            ]);
        }
        
        // Enhanced query validation
        $query = sanitize_text_field($_POST['query'] ?? '');
        if (strlen($query) < 2 || strlen($query) > 100) {
            wp_send_json_error([
                'message' => __('Search query must be between 2 and 100 characters.', 'aqualuxe'),
                'code' => 'invalid_query_length'
            ]);
        }
        
        // Prevent potential XSS in search query
        if ($query !== strip_tags($query)) {
            $this->log_security_event('xss_attempt', 'Search', $query);
            wp_send_json_error([
                'message' => __('Invalid search query.', 'aqualuxe'),
                'code' => 'invalid_query_format'
            ]);
        }
    }
    
    /**
     * Enhanced AJAX validation for dark mode toggle
     */
    public function validate_dark_mode_ajax() {
        // Nonce validation
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_dark_mode_nonce')) {
            $this->log_security_event('invalid_nonce', 'Dark Mode AJAX', $_SERVER['REMOTE_ADDR'] ?? 'unknown');
            wp_send_json_error([
                'message' => __('Security check failed.', 'aqualuxe'),
                'code' => 'invalid_nonce'
            ]);
        }
        
        // Rate limiting
        if ($this->is_rate_limited('dark_mode', 10, 60)) { // 10 toggles per minute
            wp_send_json_error([
                'message' => __('Too many requests.', 'aqualuxe'),
                'code' => 'rate_limited'
            ]);
        }
    }
    
    /**
     * Enhanced input sanitization
     */
    public function enhanced_input_sanitization($value, $type = 'text') {
        switch ($type) {
            case 'email':
                return sanitize_email($value);
            case 'url':
                return esc_url_raw($value);
            case 'int':
                return absint($value);
            case 'float':
                return floatval($value);
            case 'html':
                return wp_kses_post($value);
            case 'textarea':
                return sanitize_textarea_field($value);
            case 'key':
                return sanitize_key($value);
            case 'title':
                return sanitize_title($value);
            case 'filename':
                return sanitize_file_name($value);
            case 'hex_color':
                return sanitize_hex_color($value);
            case 'text':
            default:
                return sanitize_text_field($value);
        }
    }
    
    /**
     * Rate limiting implementation
     */
    private function is_rate_limited($action, $limit, $window) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $user_id = get_current_user_id();
        
        // Create unique key for rate limiting
        $key = 'aqualuxe_rate_limit_' . $action . '_' . ($user_id ? $user_id : $ip);
        
        // Get current count
        $current = get_transient($key);
        
        if ($current === false) {
            // First request in window
            set_transient($key, 1, $window);
            return false;
        }
        
        if ($current >= $limit) {
            // Log rate limit exceeded
            $this->log_security_event('rate_limit_exceeded', $action, [
                'ip' => $ip,
                'user_id' => $user_id,
                'current_count' => $current,
                'limit' => $limit
            ]);
            return true;
        }
        
        // Increment counter
        set_transient($key, $current + 1, $window);
        return false;
    }
    
    /**
     * Database query optimization
     */
    public function optimize_database_queries() {
        global $wpdb;
        
        // Add indexes for wishlist table
        $wishlist_table = $wpdb->prefix . 'aqualuxe_wishlist';
        
        // Check if indexes exist and create if needed
        $indexes = $wpdb->get_results("SHOW INDEX FROM {$wishlist_table}");
        $existing_indexes = wp_list_pluck($indexes, 'Key_name');
        
        if (!in_array('idx_user_session', $existing_indexes)) {
            $wpdb->query("ALTER TABLE {$wishlist_table} ADD INDEX idx_user_session (user_id, session_id)");
        }
        
        if (!in_array('idx_post_id', $existing_indexes)) {
            $wpdb->query("ALTER TABLE {$wishlist_table} ADD INDEX idx_post_id (post_id)");
        }
        
        if (!in_array('idx_date_added', $existing_indexes)) {
            $wpdb->query("ALTER TABLE {$wishlist_table} ADD INDEX idx_date_added (date_added)");
        }
    }
    
    /**
     * Enhanced security event logging
     */
    public function log_security_event($event_type, $component, $details) {
        if (!get_theme_mod('aqualuxe_security_logging', true)) {
            return;
        }
        
        $log_entry = [
            'timestamp' => current_time('timestamp'),
            'event_type' => $event_type,
            'component' => $component,
            'details' => $details,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'user_id' => get_current_user_id(),
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
        ];
        
        // Store in option (with rotation to prevent bloat)
        $security_log = get_option('aqualuxe_security_log', []);
        $security_log[] = $log_entry;
        
        // Keep only last 1000 entries
        if (count($security_log) > 1000) {
            $security_log = array_slice($security_log, -1000);
        }
        
        update_option('aqualuxe_security_log', $security_log);
        
        // For critical events, also log to PHP error log
        if (in_array($event_type, ['xss_attempt', 'sql_injection_attempt', 'invalid_nonce'])) {
            error_log(sprintf(
                'AquaLuxe Security Alert: %s in %s - Details: %s - IP: %s',
                $event_type,
                $component,
                is_array($details) ? json_encode($details) : $details,
                $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ));
        }
    }
    
    /**
     * Enhanced database query validation
     */
    public static function validate_sql_query($sql, $params = []) {
        // Prevent common SQL injection patterns
        $dangerous_patterns = [
            '/union\s+select/i',
            '/insert\s+into/i',
            '/update\s+.*set/i',
            '/delete\s+from/i',
            '/drop\s+table/i',
            '/truncate\s+table/i',
            '/alter\s+table/i',
            '/create\s+table/i',
            '/--/',
            '/\/\*.*\*\//',
            '/;\s*$/'
        ];
        
        foreach ($dangerous_patterns as $pattern) {
            if (preg_match($pattern, $sql)) {
                self::get_instance()->log_security_event('sql_injection_attempt', 'Database', [
                    'sql' => $sql,
                    'params' => $params
                ]);
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Enhanced CSRF protection
     */
    public static function verify_request_origin() {
        $allowed_origins = [
            home_url(),
            admin_url(),
            site_url()
        ];
        
        $origin = $_SERVER['HTTP_ORIGIN'] ?? $_SERVER['HTTP_REFERER'] ?? '';
        
        if (empty($origin)) {
            return false;
        }
        
        foreach ($allowed_origins as $allowed) {
            if (strpos($origin, $allowed) === 0) {
                return true;
            }
        }
        
        self::get_instance()->log_security_event('csrf_attempt', 'Request Origin', $origin);
        return false;
    }
}

/**
 * Performance Enhancement Class
 * 
 * Optimizes database queries and reduces N+1 problems
 */
class AquaLuxe_Performance_Enhancements {
    
    private static $instance = null;
    private $query_cache = [];
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_performance_enhancements();
    }
    
    /**
     * Initialize performance enhancements
     */
    private function init_performance_enhancements() {
        // Cache frequently used queries
        add_action('init', [$this, 'setup_query_caching']);
        
        // Optimize meta queries
        add_filter('posts_pre_query', [$this, 'optimize_meta_queries'], 10, 2);
        
        // Batch process wishlist items
        add_filter('aqualuxe_get_wishlist_items', [$this, 'batch_wishlist_items'], 10, 3);
    }
    
    /**
     * Setup query caching
     */
    public function setup_query_caching() {
        // Enable object caching for custom queries
        wp_cache_add_global_groups(['aqualuxe_wishlist', 'aqualuxe_modules']);
    }
    
    /**
     * Optimize meta queries to prevent N+1 problems
     */
    public function optimize_meta_queries($posts, $query) {
        // Only optimize for our specific queries
        if (!isset($query->query_vars['aqualuxe_optimize'])) {
            return $posts;
        }
        
        // Pre-load meta data for posts to prevent N+1 queries
        if (is_array($posts) && !empty($posts)) {
            $post_ids = wp_list_pluck($posts, 'ID');
            update_meta_cache('post', $post_ids);
        }
        
        return $posts;
    }
    
    /**
     * Batch process wishlist items to reduce database calls
     */
    public function batch_wishlist_items($items, $user_id, $session_id) {
        if (empty($items)) {
            return $items;
        }
        
        // Extract post IDs
        $post_ids = wp_list_pluck($items, 'post_id');
        
        // Pre-load post data and meta
        $posts = get_posts([
            'post__in' => $post_ids,
            'post_type' => 'any',
            'post_status' => 'publish',
            'numberposts' => -1
        ]);
        
        // Pre-load meta data
        update_meta_cache('post', $post_ids);
        
        // If WooCommerce is active, pre-load product data
        if (class_exists('WooCommerce')) {
            foreach ($posts as $post) {
                if ($post->post_type === 'product') {
                    wc_get_product($post->ID); // This will cache the product
                }
            }
        }
        
        return $items;
    }
}

/**
 * Code Quality Enhancement Class
 * 
 * Provides better error handling and validation
 */
class AquaLuxe_Quality_Enhancements {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_quality_enhancements();
    }
    
    /**
     * Initialize quality enhancements
     */
    private function init_quality_enhancements() {
        // Enhanced error handling
        add_action('wp_ajax_aqualuxe_error_handler', [$this, 'handle_ajax_errors']);
        
        // Input validation filters
        add_filter('aqualuxe_validate_input', [$this, 'comprehensive_input_validation'], 10, 3);
        
        // Enhanced logging
        add_action('aqualuxe_log_error', [$this, 'enhanced_error_logging'], 10, 3);
    }
    
    /**
     * Enhanced AJAX error handling
     */
    public function handle_ajax_errors($error) {
        $response = [
            'success' => false,
            'data' => [
                'message' => __('An error occurred. Please try again.', 'aqualuxe'),
                'code' => 'unknown_error'
            ]
        ];
        
        // Log the error
        $this->enhanced_error_logging('ajax_error', $error, [
            'action' => $_POST['action'] ?? 'unknown',
            'user_id' => get_current_user_id(),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
        
        wp_send_json($response);
    }
    
    /**
     * Comprehensive input validation
     */
    public function comprehensive_input_validation($value, $type, $constraints = []) {
        $validation_result = [
            'valid' => true,
            'value' => $value,
            'errors' => []
        ];
        
        // Basic type validation
        switch ($type) {
            case 'email':
                if (!is_email($value)) {
                    $validation_result['valid'] = false;
                    $validation_result['errors'][] = 'invalid_email';
                }
                break;
                
            case 'url':
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    $validation_result['valid'] = false;
                    $validation_result['errors'][] = 'invalid_url';
                }
                break;
                
            case 'int':
                if (!is_numeric($value) || intval($value) != $value) {
                    $validation_result['valid'] = false;
                    $validation_result['errors'][] = 'invalid_integer';
                }
                break;
                
            case 'float':
                if (!is_numeric($value)) {
                    $validation_result['valid'] = false;
                    $validation_result['errors'][] = 'invalid_float';
                }
                break;
        }
        
        // Constraint validation
        if (isset($constraints['min_length']) && strlen($value) < $constraints['min_length']) {
            $validation_result['valid'] = false;
            $validation_result['errors'][] = 'min_length_failed';
        }
        
        if (isset($constraints['max_length']) && strlen($value) > $constraints['max_length']) {
            $validation_result['valid'] = false;
            $validation_result['errors'][] = 'max_length_failed';
        }
        
        if (isset($constraints['pattern']) && !preg_match($constraints['pattern'], $value)) {
            $validation_result['valid'] = false;
            $validation_result['errors'][] = 'pattern_failed';
        }
        
        return $validation_result;
    }
    
    /**
     * Enhanced error logging
     */
    public function enhanced_error_logging($error_type, $error_message, $context = []) {
        $log_entry = [
            'timestamp' => current_time('timestamp'),
            'error_type' => $error_type,
            'message' => $error_message,
            'context' => $context,
            'backtrace' => wp_debug_backtrace_summary()
        ];
        
        // Store in options
        $error_log = get_option('aqualuxe_error_log', []);
        $error_log[] = $log_entry;
        
        // Keep only last 500 entries
        if (count($error_log) > 500) {
            $error_log = array_slice($error_log, -500);
        }
        
        update_option('aqualuxe_error_log', $error_log);
        
        // Also log to PHP error log for critical errors
        if (in_array($error_type, ['fatal_error', 'database_error', 'security_error'])) {
            error_log(sprintf(
                'AquaLuxe Error [%s]: %s - Context: %s',
                $error_type,
                $error_message,
                json_encode($context)
            ));
        }
    }
}

// Initialize enhancements
add_action('after_setup_theme', function() {
    AquaLuxe_Security_Enhancements::get_instance();
    AquaLuxe_Performance_Enhancements::get_instance();
    AquaLuxe_Quality_Enhancements::get_instance();
}, 1);

/**
 * Emergency Security Functions
 * 
 * These functions can be called immediately if security issues are detected
 */

/**
 * Emergency disable AJAX if under attack
 */
function aqualuxe_emergency_disable_ajax() {
    remove_action('wp_ajax_aqualuxe_wishlist_add', 'aqualuxe_wishlist_add_ajax');
    remove_action('wp_ajax_nopriv_aqualuxe_wishlist_add', 'aqualuxe_wishlist_add_ajax');
    remove_action('wp_ajax_aqualuxe_wishlist_remove', 'aqualuxe_wishlist_remove_ajax');
    remove_action('wp_ajax_nopriv_aqualuxe_wishlist_remove', 'aqualuxe_wishlist_remove_ajax');
    remove_action('wp_ajax_aqualuxe_search', 'aqualuxe_search_ajax');
    remove_action('wp_ajax_nopriv_aqualuxe_search', 'aqualuxe_search_ajax');
    
    update_option('aqualuxe_ajax_disabled', true);
}

/**
 * Emergency security lockdown
 */
function aqualuxe_emergency_lockdown() {
    // Disable all AJAX
    aqualuxe_emergency_disable_ajax();
    
    // Clear all caches
    wp_cache_flush();
    
    // Log the lockdown
    AquaLuxe_Security_Enhancements::get_instance()->log_security_event(
        'emergency_lockdown', 
        'Security', 
        'Emergency lockdown activated'
    );
    
    update_option('aqualuxe_emergency_lockdown', true);
}

/**
 * Recovery function to restore normal operation
 */
function aqualuxe_restore_normal_operation() {
    delete_option('aqualuxe_ajax_disabled');
    delete_option('aqualuxe_emergency_lockdown');
    
    // Log recovery
    AquaLuxe_Security_Enhancements::get_instance()->log_security_event(
        'normal_operation_restored', 
        'Security', 
        'Normal operation restored'
    );
}
