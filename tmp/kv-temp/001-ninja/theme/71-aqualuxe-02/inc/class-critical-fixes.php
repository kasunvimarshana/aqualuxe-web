<?php
/**
 * AquaLuxe Critical Bug Fixes
 * 
 * This file contains fixes for critical bugs and issues identified
 * during the comprehensive code audit.
 * 
 * @package AquaLuxe
 * @version 1.0.1
 * @since 1.0.1
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Critical Bug Fixes Class
 * 
 * Addresses identified critical issues and ensures proper functionality
 */
class AquaLuxe_Critical_Fixes {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->apply_critical_fixes();
    }
    
    /**
     * Apply all critical fixes
     */
    private function apply_critical_fixes() {
        // Fix missing dependencies
        add_action('after_setup_theme', [$this, 'ensure_dependencies'], 5);
        
        // Fix AJAX handlers
        add_action('wp_ajax_aqualuxe_wishlist_add', [$this, 'fixed_wishlist_add_ajax']);
        add_action('wp_ajax_nopriv_aqualuxe_wishlist_add', [$this, 'fixed_wishlist_add_ajax']);
        add_action('wp_ajax_aqualuxe_wishlist_remove', [$this, 'fixed_wishlist_remove_ajax']);
        add_action('wp_ajax_nopriv_aqualuxe_wishlist_remove', [$this, 'fixed_wishlist_remove_ajax']);
        add_action('wp_ajax_aqualuxe_search', [$this, 'fixed_search_ajax']);
        add_action('wp_ajax_nopriv_aqualuxe_search', [$this, 'fixed_search_ajax']);
        
        // Fix header output issues
        add_action('get_header', [$this, 'fix_header_output']);
        
        // Fix missing nonce generation
        add_action('wp_enqueue_scripts', [$this, 'ensure_nonces'], 20);
        
        // Fix database table creation
        add_action('after_switch_theme', [$this, 'fix_database_tables']);
        
        // Fix missing translations
        add_action('init', [$this, 'ensure_translations']);
    }
    
    /**
     * Ensure all required dependencies are loaded
     */
    public function ensure_dependencies() {
        // Ensure required classes exist
        $required_classes = [
            'AquaLuxe_Theme',
            'AquaLuxe_Asset_Manager', 
            'AquaLuxe_WooCommerce_Integration',
            'AquaLuxe_Security',
            'AquaLuxe_Performance',
            'AquaLuxe_SEO',
            'AquaLuxe_Multilingual',
            'AquaLuxe_Admin',
            'AquaLuxe_Demo_Content',
            'AquaLuxe_Module_Manager'
        ];
        
        foreach ($required_classes as $class) {
            if (!class_exists($class)) {
                $file_path = AQUALUXE_THEME_DIR . '/inc/class-' . strtolower(str_replace('_', '-', str_replace('AquaLuxe_', '', $class))) . '.php';
                if (file_exists($file_path)) {
                    require_once $file_path;
                }
            }
        }
        
        // Ensure module classes exist
        $module_classes = [
            'AquaLuxe_Module_Dark_Mode',
            'AquaLuxe_Module_Wishlist', 
            'AquaLuxe_Module_Search'
        ];
        
        foreach ($module_classes as $class) {
            if (!class_exists($class)) {
                $module_name = str_replace('AquaLuxe_Module_', '', $class);
                $file_path = AQUALUXE_THEME_DIR . '/modules/class-module-' . strtolower(str_replace('_', '-', $module_name)) . '.php';
                if (file_exists($file_path)) {
                    require_once $file_path;
                }
            }
        }
    }
    
    /**
     * Fixed wishlist add AJAX handler
     */
    public function fixed_wishlist_add_ajax() {
        // Enhanced security validation
        if (!$this->validate_ajax_request('aqualuxe_wishlist_nonce')) {
            wp_send_json_error([
                'message' => __('Security validation failed.', 'aqualuxe'),
                'code' => 'security_failed'
            ]);
        }
        
        // Validate and sanitize input
        $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
        
        if (!$post_id) {
            wp_send_json_error([
                'message' => __('Invalid item ID.', 'aqualuxe'),
                'code' => 'invalid_id'
            ]);
        }
        
        // Verify post exists and is published
        $post = get_post($post_id);
        if (!$post || $post->post_status !== 'publish') {
            wp_send_json_error([
                'message' => __('Item not found or not available.', 'aqualuxe'),
                'code' => 'item_not_found'
            ]);
        }
        
        // Get wishlist module instance
        if (class_exists('AquaLuxe_Module_Wishlist')) {
            $wishlist = AquaLuxe_Module_Wishlist::get_instance();
            
            if ($wishlist && method_exists($wishlist, 'add_to_wishlist')) {
                $result = $wishlist->add_to_wishlist($post_id);
                
                if ($result) {
                    wp_send_json_success([
                        'message' => __('Added to wishlist successfully!', 'aqualuxe'),
                        'post_id' => $post_id,
                        'count' => $wishlist->get_wishlist_count()
                    ]);
                } else {
                    wp_send_json_error([
                        'message' => __('Failed to add to wishlist.', 'aqualuxe'),
                        'code' => 'add_failed'
                    ]);
                }
            } else {
                wp_send_json_error([
                    'message' => __('Wishlist functionality not available.', 'aqualuxe'),
                    'code' => 'feature_unavailable'
                ]);
            }
        } else {
            wp_send_json_error([
                'message' => __('Wishlist module not loaded.', 'aqualuxe'),
                'code' => 'module_not_loaded'
            ]);
        }
    }
    
    /**
     * Fixed wishlist remove AJAX handler
     */
    public function fixed_wishlist_remove_ajax() {
        // Enhanced security validation
        if (!$this->validate_ajax_request('aqualuxe_wishlist_nonce')) {
            wp_send_json_error([
                'message' => __('Security validation failed.', 'aqualuxe'),
                'code' => 'security_failed'
            ]);
        }
        
        // Validate and sanitize input
        $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
        
        if (!$post_id) {
            wp_send_json_error([
                'message' => __('Invalid item ID.', 'aqualuxe'),
                'code' => 'invalid_id'
            ]);
        }
        
        // Get wishlist module instance
        if (class_exists('AquaLuxe_Module_Wishlist')) {
            $wishlist = AquaLuxe_Module_Wishlist::get_instance();
            
            if ($wishlist && method_exists($wishlist, 'remove_from_wishlist')) {
                $result = $wishlist->remove_from_wishlist($post_id);
                
                if ($result) {
                    wp_send_json_success([
                        'message' => __('Removed from wishlist successfully!', 'aqualuxe'),
                        'post_id' => $post_id,
                        'count' => $wishlist->get_wishlist_count()
                    ]);
                } else {
                    wp_send_json_error([
                        'message' => __('Failed to remove from wishlist.', 'aqualuxe'),
                        'code' => 'remove_failed'
                    ]);
                }
            } else {
                wp_send_json_error([
                    'message' => __('Wishlist functionality not available.', 'aqualuxe'),
                    'code' => 'feature_unavailable'
                ]);
            }
        } else {
            wp_send_json_error([
                'message' => __('Wishlist module not loaded.', 'aqualuxe'),
                'code' => 'module_not_loaded'
            ]);
        }
    }
    
    /**
     * Fixed search AJAX handler
     */
    public function fixed_search_ajax() {
        // Enhanced security validation
        if (!$this->validate_ajax_request('aqualuxe_search_nonce')) {
            wp_send_json_error([
                'message' => __('Security validation failed.', 'aqualuxe'),
                'code' => 'security_failed'
            ]);
        }
        
        // Validate and sanitize input
        $query = isset($_POST['query']) ? sanitize_text_field($_POST['query']) : '';
        $post_types = isset($_POST['post_types']) ? array_map('sanitize_text_field', $_POST['post_types']) : ['post', 'page'];
        $max_results = isset($_POST['max_results']) ? absint($_POST['max_results']) : 8;
        
        // Validate query length
        if (strlen($query) < 2) {
            wp_send_json_error([
                'message' => __('Search query must be at least 2 characters.', 'aqualuxe'),
                'code' => 'query_too_short'
            ]);
        }
        
        if (strlen($query) > 100) {
            wp_send_json_error([
                'message' => __('Search query too long.', 'aqualuxe'),
                'code' => 'query_too_long'
            ]);
        }
        
        // Perform search
        $search_args = [
            's' => $query,
            'post_type' => $post_types,
            'posts_per_page' => min($max_results, 20), // Cap at 20 results
            'post_status' => 'publish',
            'no_found_rows' => true, // Performance optimization
            'update_post_meta_cache' => false, // Performance optimization
        ];
        
        $search_query = new WP_Query($search_args);
        $results = [];
        
        if ($search_query->have_posts()) {
            while ($search_query->have_posts()) {
                $search_query->the_post();
                
                $results[] = [
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'excerpt' => wp_trim_words(get_the_excerpt(), 20),
                    'permalink' => get_permalink(),
                    'post_type' => get_post_type(),
                    'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: ''
                ];
            }
            
            wp_reset_postdata();
        }
        
        wp_send_json_success([
            'results' => $results,
            'count' => count($results),
            'query' => $query
        ]);
    }
    
    /**
     * Enhanced AJAX request validation
     */
    private function validate_ajax_request($nonce_action) {
        // Check if nonce is provided
        if (!isset($_POST['nonce'])) {
            return false;
        }
        
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], $nonce_action)) {
            return false;
        }
        
        // Check if request is AJAX
        if (!wp_doing_ajax()) {
            return false;
        }
        
        // Optional: Check referer
        if (!wp_get_referer()) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Fix header output issues
     */
    public function fix_header_output() {
        // Ensure no output before headers
        if (!headers_sent()) {
            // Set proper content type
            header('Content-Type: text/html; charset=' . get_option('blog_charset'));
            
            // Security headers
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: SAMEORIGIN');
            header('X-XSS-Protection: 1; mode=block');
        }
    }
    
    /**
     * Ensure all required nonces are available
     */
    public function ensure_nonces() {
        // Create nonces for AJAX requests
        wp_localize_script('aqualuxe-main', 'aqualuxe_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'wishlist_nonce' => wp_create_nonce('aqualuxe_wishlist_nonce'),
            'search_nonce' => wp_create_nonce('aqualuxe_search_nonce'),
            'dark_mode_nonce' => wp_create_nonce('aqualuxe_dark_mode_nonce'),
            'general_nonce' => wp_create_nonce('aqualuxe_general_nonce'),
        ]);
    }
    
    /**
     * Fix database table creation issues
     */
    public function fix_database_tables() {
        global $wpdb;
        
        // Create wishlist table if it doesn't exist
        $wishlist_table = $wpdb->prefix . 'aqualuxe_wishlist';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '{$wishlist_table}'") != $wishlist_table) {
            $charset_collate = $wpdb->get_charset_collate();
            
            $sql = "CREATE TABLE {$wishlist_table} (
                id bigint(20) NOT NULL AUTO_INCREMENT,
                user_id bigint(20) DEFAULT NULL,
                session_id varchar(255) DEFAULT NULL,
                post_id bigint(20) NOT NULL,
                date_added datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY idx_user_session (user_id, session_id),
                KEY idx_post_id (post_id),
                KEY idx_date_added (date_added),
                UNIQUE KEY unique_wishlist_item (user_id, session_id, post_id)
            ) {$charset_collate};";
            
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
            
            // Log table creation
            if ($wpdb->get_var("SHOW TABLES LIKE '{$wishlist_table}'") == $wishlist_table) {
                update_option('aqualuxe_wishlist_table_created', true);
            }
        }
    }
    
    /**
     * Ensure translations are loaded
     */
    public function ensure_translations() {
        // Load theme textdomain
        $loaded = load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . '/languages');
        
        if (!$loaded) {
            // Fallback: try to load from theme directory
            load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');
        }
    }
    
    /**
     * Fix common PHP errors
     */
    public static function fix_php_errors() {
        // Set error reporting for production
        if (!WP_DEBUG) {
            error_reporting(0);
            ini_set('display_errors', 0);
        }
        
        // Increase memory limit if needed
        if (function_exists('ini_get')) {
            $memory_limit = ini_get('memory_limit');
            if ($memory_limit && intval($memory_limit) < 256) {
                ini_set('memory_limit', '256M');
            }
        }
        
        // Set max execution time
        if (function_exists('set_time_limit')) {
            set_time_limit(60);
        }
    }
    
    /**
     * Emergency cleanup function
     */
    public static function emergency_cleanup() {
        // Clear all caches
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }
        
        // Clear transients
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_site_transient_%'");
        
        // Clear theme options if corrupted
        if (get_option('aqualuxe_emergency_reset')) {
            delete_option('aqualuxe_theme_options');
            delete_option('aqualuxe_modules_config');
            delete_option('aqualuxe_emergency_reset');
        }
    }
    
    /**
     * Database integrity check
     */
    public function check_database_integrity() {
        global $wpdb;
        
        $issues = [];
        
        // Check if wishlist table exists and has correct structure
        $wishlist_table = $wpdb->prefix . 'aqualuxe_wishlist';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '{$wishlist_table}'") != $wishlist_table) {
            $issues[] = 'missing_wishlist_table';
        } else {
            // Check table structure
            $columns = $wpdb->get_results("SHOW COLUMNS FROM {$wishlist_table}");
            $column_names = wp_list_pluck($columns, 'Field');
            
            $required_columns = ['id', 'user_id', 'session_id', 'post_id', 'date_added'];
            $missing_columns = array_diff($required_columns, $column_names);
            
            if (!empty($missing_columns)) {
                $issues[] = 'missing_wishlist_columns: ' . implode(', ', $missing_columns);
            }
        }
        
        // Check for orphaned data
        $orphaned_count = $wpdb->get_var("
            SELECT COUNT(*) 
            FROM {$wishlist_table} w 
            LEFT JOIN {$wpdb->posts} p ON w.post_id = p.ID 
            WHERE p.ID IS NULL
        ");
        
        if ($orphaned_count > 0) {
            $issues[] = "orphaned_wishlist_items: {$orphaned_count}";
        }
        
        // Store issues for review
        if (!empty($issues)) {
            update_option('aqualuxe_database_issues', $issues);
        } else {
            delete_option('aqualuxe_database_issues');
        }
        
        return empty($issues);
    }
}

/**
 * JavaScript Error Handler
 * 
 * Provides client-side error handling and reporting
 */
class AquaLuxe_JS_Error_Handler {
    
    public static function init() {
        add_action('wp_footer', [__CLASS__, 'add_error_handler']);
    }
    
    public static function add_error_handler() {
        ?>
        <script>
        // Enhanced JavaScript error handling
        window.aqualuxeErrorHandler = {
            errors: [],
            
            init: function() {
                // Global error handler
                window.addEventListener('error', this.logError.bind(this));
                window.addEventListener('unhandledrejection', this.logPromiseRejection.bind(this));
                
                // AJAX error handler
                if (typeof jQuery !== 'undefined') {
                    jQuery(document).ajaxError(this.logAjaxError.bind(this));
                }
            },
            
            logError: function(event) {
                const error = {
                    type: 'javascript_error',
                    message: event.message,
                    filename: event.filename,
                    lineno: event.lineno,
                    colno: event.colno,
                    stack: event.error ? event.error.stack : null,
                    timestamp: new Date().toISOString(),
                    url: window.location.href
                };
                
                this.errors.push(error);
                this.reportError(error);
            },
            
            logPromiseRejection: function(event) {
                const error = {
                    type: 'promise_rejection',
                    message: event.reason.toString(),
                    stack: event.reason.stack || null,
                    timestamp: new Date().toISOString(),
                    url: window.location.href
                };
                
                this.errors.push(error);
                this.reportError(error);
            },
            
            logAjaxError: function(event, jqXHR, ajaxSettings, thrownError) {
                const error = {
                    type: 'ajax_error',
                    message: thrownError,
                    status: jqXHR.status,
                    statusText: jqXHR.statusText,
                    url: ajaxSettings.url,
                    timestamp: new Date().toISOString()
                };
                
                this.errors.push(error);
                this.reportError(error);
            },
            
            reportError: function(error) {
                // Only report in production if critical
                if (console && console.error) {
                    console.error('AquaLuxe Error:', error);
                }
                
                // Send to server for logging (rate limited)
                if (this.shouldReport(error)) {
                    this.sendToServer(error);
                }
            },
            
            shouldReport: function(error) {
                // Rate limiting: max 5 errors per session
                if (this.errors.length > 5) {
                    return false;
                }
                
                // Don't report certain types of errors
                const ignoredMessages = [
                    'Script error.',
                    'Non-Error promise rejection captured',
                    'ResizeObserver loop limit exceeded'
                ];
                
                return !ignoredMessages.some(msg => error.message.includes(msg));
            },
            
            sendToServer: function(error) {
                if (typeof fetch !== 'undefined') {
                    fetch(aqualuxe_ajax.ajax_url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            action: 'aqualuxe_log_js_error',
                            nonce: aqualuxe_ajax.general_nonce,
                            error: JSON.stringify(error)
                        })
                    }).catch(function(err) {
                        // Silently fail to prevent error loops
                    });
                }
            }
        };
        
        // Initialize error handler
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                window.aqualuxeErrorHandler.init();
            });
        } else {
            window.aqualuxeErrorHandler.init();
        }
        </script>
        <?php
    }
}

// Initialize critical fixes
add_action('init', function() {
    AquaLuxe_Critical_Fixes::get_instance();
    AquaLuxe_JS_Error_Handler::init();
    
    // Apply PHP error fixes
    AquaLuxe_Critical_Fixes::fix_php_errors();
}, 1);

// Emergency cleanup hook
add_action('wp_ajax_aqualuxe_emergency_cleanup', function() {
    if (current_user_can('manage_options')) {
        AquaLuxe_Critical_Fixes::emergency_cleanup();
        wp_send_json_success(['message' => 'Emergency cleanup completed']);
    } else {
        wp_send_json_error(['message' => 'Insufficient permissions']);
    }
});

// AJAX handler for JavaScript errors
add_action('wp_ajax_aqualuxe_log_js_error', function() {
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_general_nonce')) {
        wp_die('Security check failed');
    }
    
    $error = json_decode(stripslashes($_POST['error']), true);
    if ($error) {
        error_log('AquaLuxe JS Error: ' . json_encode($error));
    }
    
    wp_die();
});

add_action('wp_ajax_nopriv_aqualuxe_log_js_error', function() {
    // Allow non-logged-in users to report errors
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_general_nonce')) {
        wp_die('Security check failed');
    }
    
    $error = json_decode(stripslashes($_POST['error']), true);
    if ($error) {
        error_log('AquaLuxe JS Error (Guest): ' . json_encode($error));
    }
    
    wp_die();
});
