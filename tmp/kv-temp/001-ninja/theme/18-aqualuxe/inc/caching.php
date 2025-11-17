<?php
/**
 * Caching functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Caching Class
 * 
 * Implements caching strategies for better performance
 */
class AquaLuxe_Caching {
    /**
     * Constructor
     */
    public function __construct() {
        // Add browser caching headers
        add_action('send_headers', [$this, 'add_browser_caching_headers']);
        
        // Add page caching
        add_action('template_redirect', [$this, 'setup_page_cache'], 5);
        
        // Add asset optimization
        add_action('wp_enqueue_scripts', [$this, 'optimize_asset_loading'], 999);
        
        // Add cache control for REST API
        add_action('rest_api_init', [$this, 'add_rest_cache_headers']);
        
        // Add cache settings to admin
        add_action('admin_init', [$this, 'register_cache_settings']);
        
        // Add cache purging on content updates
        add_action('save_post', [$this, 'purge_page_cache']);
        add_action('deleted_post', [$this, 'purge_page_cache']);
        add_action('comment_post', [$this, 'purge_page_cache']);
        add_action('wp_update_nav_menu', [$this, 'purge_full_cache']);
        add_action('update_option_sidebars_widgets', [$this, 'purge_full_cache']);
        add_action('customize_save_after', [$this, 'purge_full_cache']);
        
        // Add cache management to admin bar
        add_action('admin_bar_menu', [$this, 'add_cache_admin_bar_menu'], 100);
        
        // Handle cache purge requests
        add_action('admin_init', [$this, 'handle_cache_purge_requests']);
    }

    /**
     * Add browser caching headers
     */
    public function add_browser_caching_headers() {
        // Check if browser caching is enabled
        if (get_option('aqualuxe_enable_browser_caching', 'yes') !== 'yes') {
            return;
        }
        
        // Don't add headers for logged-in users
        if (is_user_logged_in()) {
            return;
        }
        
        // Get file type from URL
        $url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $file_extension = pathinfo($url_path, PATHINFO_EXTENSION);
        
        // Set cache time based on file type
        $cache_time = 0;
        
        switch ($file_extension) {
            case 'css':
            case 'js':
            case 'svg':
                $cache_time = WEEK_IN_SECONDS;
                break;
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'webp':
            case 'ico':
                $cache_time = MONTH_IN_SECONDS;
                break;
            case 'woff':
            case 'woff2':
            case 'ttf':
            case 'eot':
            case 'otf':
                $cache_time = YEAR_IN_SECONDS;
                break;
            default:
                // For HTML and other content
                if (empty($file_extension) || $file_extension === 'html' || $file_extension === 'php') {
                    $cache_time = DAY_IN_SECONDS;
                } else {
                    $cache_time = WEEK_IN_SECONDS;
                }
                break;
        }
        
        // Set cache headers if cache time is greater than 0
        if ($cache_time > 0) {
            header('Cache-Control: public, max-age=' . $cache_time);
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cache_time) . ' GMT');
        }
    }

    /**
     * Setup page cache
     */
    public function setup_page_cache() {
        // Check if page caching is enabled
        if (get_option('aqualuxe_enable_page_caching', 'yes') !== 'yes') {
            return;
        }
        
        // Don't cache for logged-in users
        if (is_user_logged_in()) {
            return;
        }
        
        // Don't cache for specific pages
        if (is_cart() || is_checkout() || is_account_page()) {
            return;
        }
        
        // Don't cache search results
        if (is_search()) {
            return;
        }
        
        // Don't cache if there are query parameters
        if (!empty($_GET)) {
            return;
        }
        
        // Start output buffering
        ob_start([$this, 'process_page_cache']);
    }

    /**
     * Process page cache
     * 
     * @param string $buffer Page content
     * @return string Processed content
     */
    public function process_page_cache($buffer) {
        // Check if buffer is empty
        if (empty($buffer)) {
            return $buffer;
        }
        
        // Get cache directory
        $cache_dir = $this->get_cache_directory();
        
        // Create cache directory if it doesn't exist
        if (!file_exists($cache_dir)) {
            wp_mkdir_p($cache_dir);
        }
        
        // Generate cache file path
        $cache_file = $this->get_cache_file_path();
        
        // Save cache file
        file_put_contents($cache_file, $buffer);
        
        return $buffer;
    }

    /**
     * Optimize asset loading
     */
    public function optimize_asset_loading() {
        // Check if asset optimization is enabled
        if (get_option('aqualuxe_enable_asset_optimization', 'yes') !== 'yes') {
            return;
        }
        
        // Add defer attribute to scripts
        add_filter('script_loader_tag', [$this, 'add_defer_attribute'], 10, 2);
        
        // Add preload for critical assets
        $this->add_preload_for_critical_assets();
        
        // Remove query strings from static resources
        if (get_option('aqualuxe_remove_query_strings', 'yes') === 'yes') {
            add_filter('style_loader_src', [$this, 'remove_query_strings'], 10, 2);
            add_filter('script_loader_src', [$this, 'remove_query_strings'], 10, 2);
        }
    }

    /**
     * Add defer attribute to scripts
     * 
     * @param string $tag Script tag
     * @param string $handle Script handle
     * @return string Modified script tag
     */
    public function add_defer_attribute($tag, $handle) {
        // List of scripts to defer
        $defer_scripts = [
            'jquery-migrate',
            'comment-reply',
            'aqualuxe-scripts',
        ];
        
        // Skip if script is not in the list
        if (!in_array($handle, $defer_scripts)) {
            return $tag;
        }
        
        // Add defer attribute
        if (strpos($tag, 'defer') === false) {
            $tag = str_replace(' src', ' defer src', $tag);
        }
        
        return $tag;
    }

    /**
     * Add preload for critical assets
     */
    public function add_preload_for_critical_assets() {
        // Preload main stylesheet
        echo '<link rel="preload" href="' . esc_url(get_stylesheet_uri()) . '" as="style">' . "\n";
        
        // Preload logo
        $logo_id = get_theme_mod('custom_logo');
        if ($logo_id) {
            $logo_url = wp_get_attachment_image_url($logo_id, 'full');
            if ($logo_url) {
                echo '<link rel="preload" href="' . esc_url($logo_url) . '" as="image">' . "\n";
            }
        }
        
        // Preload fonts
        $fonts = [
            get_theme_mod('aqualuxe_primary_font', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap'),
        ];
        
        foreach ($fonts as $font) {
            if (!empty($font)) {
                echo '<link rel="preload" href="' . esc_url($font) . '" as="font" crossorigin>' . "\n";
            }
        }
    }

    /**
     * Remove query strings from static resources
     * 
     * @param string $src Resource URL
     * @param string $handle Resource handle
     * @return string Modified URL
     */
    public function remove_query_strings($src, $handle) {
        // Skip for admin
        if (is_admin()) {
            return $src;
        }
        
        // Remove query string
        $src = preg_replace('/\?ver=([^&]*)/', '', $src);
        
        return $src;
    }

    /**
     * Add REST API cache headers
     */
    public function add_rest_cache_headers() {
        // Add filter to set cache headers for REST API responses
        add_filter('rest_post_dispatch', [$this, 'set_rest_cache_headers'], 10, 3);
    }

    /**
     * Set cache headers for REST API responses
     * 
     * @param WP_REST_Response $response Response object
     * @param WP_REST_Server $server Server instance
     * @param WP_REST_Request $request Request object
     * @return WP_REST_Response Modified response
     */
    public function set_rest_cache_headers($response, $server, $request) {
        // Check if REST API caching is enabled
        if (get_option('aqualuxe_enable_rest_caching', 'yes') !== 'yes') {
            return $response;
        }
        
        // Skip for authenticated requests
        if (is_user_logged_in()) {
            return $response;
        }
        
        // Get route
        $route = $request->get_route();
        
        // Set cache time based on route
        $cache_time = 0;
        
        if (strpos($route, '/wp/v2/posts') === 0 || strpos($route, '/wp/v2/pages') === 0) {
            // Posts and pages
            $cache_time = HOUR_IN_SECONDS;
        } elseif (strpos($route, '/wp/v2/media') === 0) {
            // Media
            $cache_time = DAY_IN_SECONDS;
        } elseif (strpos($route, '/wp/v2/categories') === 0 || strpos($route, '/wp/v2/tags') === 0) {
            // Taxonomies
            $cache_time = DAY_IN_SECONDS;
        } elseif (strpos($route, '/aqualuxe/v1/') === 0) {
            // Custom endpoints
            $cache_time = HOUR_IN_SECONDS / 2;
        }
        
        // Set cache headers if cache time is greater than 0
        if ($cache_time > 0) {
            $response->header('Cache-Control', 'public, max-age=' . $cache_time);
            $response->header('Expires', gmdate('D, d M Y H:i:s', time() + $cache_time) . ' GMT');
        }
        
        return $response;
    }

    /**
     * Register cache settings
     */
    public function register_cache_settings() {
        // Add settings section
        add_settings_section(
            'aqualuxe_cache_section',
            __('AquaLuxe Caching', 'aqualuxe'),
            [$this, 'cache_section_callback'],
            'general'
        );
        
        // Add settings fields
        add_settings_field(
            'aqualuxe_enable_browser_caching',
            __('Enable Browser Caching', 'aqualuxe'),
            [$this, 'enable_browser_caching_callback'],
            'general',
            'aqualuxe_cache_section'
        );
        
        add_settings_field(
            'aqualuxe_enable_page_caching',
            __('Enable Page Caching', 'aqualuxe'),
            [$this, 'enable_page_caching_callback'],
            'general',
            'aqualuxe_cache_section'
        );
        
        add_settings_field(
            'aqualuxe_enable_asset_optimization',
            __('Enable Asset Optimization', 'aqualuxe'),
            [$this, 'enable_asset_optimization_callback'],
            'general',
            'aqualuxe_cache_section'
        );
        
        add_settings_field(
            'aqualuxe_remove_query_strings',
            __('Remove Query Strings', 'aqualuxe'),
            [$this, 'remove_query_strings_callback'],
            'general',
            'aqualuxe_cache_section'
        );
        
        add_settings_field(
            'aqualuxe_enable_rest_caching',
            __('Enable REST API Caching', 'aqualuxe'),
            [$this, 'enable_rest_caching_callback'],
            'general',
            'aqualuxe_cache_section'
        );
        
        // Register settings
        register_setting('general', 'aqualuxe_enable_browser_caching');
        register_setting('general', 'aqualuxe_enable_page_caching');
        register_setting('general', 'aqualuxe_enable_asset_optimization');
        register_setting('general', 'aqualuxe_remove_query_strings');
        register_setting('general', 'aqualuxe_enable_rest_caching');
    }

    /**
     * Cache section callback
     */
    public function cache_section_callback() {
        echo '<p>' . __('Configure caching settings for the AquaLuxe theme.', 'aqualuxe') . '</p>';
    }

    /**
     * Enable browser caching callback
     */
    public function enable_browser_caching_callback() {
        $value = get_option('aqualuxe_enable_browser_caching', 'yes');
        echo '<select name="aqualuxe_enable_browser_caching">
            <option value="yes" ' . selected($value, 'yes', false) . '>' . __('Yes', 'aqualuxe') . '</option>
            <option value="no" ' . selected($value, 'no', false) . '>' . __('No', 'aqualuxe') . '</option>
        </select>';
        echo '<p class="description">' . __('Add browser caching headers to improve load times for returning visitors.', 'aqualuxe') . '</p>';
    }

    /**
     * Enable page caching callback
     */
    public function enable_page_caching_callback() {
        $value = get_option('aqualuxe_enable_page_caching', 'yes');
        echo '<select name="aqualuxe_enable_page_caching">
            <option value="yes" ' . selected($value, 'yes', false) . '>' . __('Yes', 'aqualuxe') . '</option>
            <option value="no" ' . selected($value, 'no', false) . '>' . __('No', 'aqualuxe') . '</option>
        </select>';
        echo '<p class="description">' . __('Cache pages to improve load times.', 'aqualuxe') . '</p>';
    }

    /**
     * Enable asset optimization callback
     */
    public function enable_asset_optimization_callback() {
        $value = get_option('aqualuxe_enable_asset_optimization', 'yes');
        echo '<select name="aqualuxe_enable_asset_optimization">
            <option value="yes" ' . selected($value, 'yes', false) . '>' . __('Yes', 'aqualuxe') . '</option>
            <option value="no" ' . selected($value, 'no', false) . '>' . __('No', 'aqualuxe') . '</option>
        </select>';
        echo '<p class="description">' . __('Optimize asset loading for better performance.', 'aqualuxe') . '</p>';
    }

    /**
     * Remove query strings callback
     */
    public function remove_query_strings_callback() {
        $value = get_option('aqualuxe_remove_query_strings', 'yes');
        echo '<select name="aqualuxe_remove_query_strings">
            <option value="yes" ' . selected($value, 'yes', false) . '>' . __('Yes', 'aqualuxe') . '</option>
            <option value="no" ' . selected($value, 'no', false) . '>' . __('No', 'aqualuxe') . '</option>
        </select>';
        echo '<p class="description">' . __('Remove query strings from static resources for better caching.', 'aqualuxe') . '</p>';
    }

    /**
     * Enable REST API caching callback
     */
    public function enable_rest_caching_callback() {
        $value = get_option('aqualuxe_enable_rest_caching', 'yes');
        echo '<select name="aqualuxe_enable_rest_caching">
            <option value="yes" ' . selected($value, 'yes', false) . '>' . __('Yes', 'aqualuxe') . '</option>
            <option value="no" ' . selected($value, 'no', false) . '>' . __('No', 'aqualuxe') . '</option>
        </select>';
        echo '<p class="description">' . __('Add cache headers to REST API responses for better performance.', 'aqualuxe') . '</p>';
    }

    /**
     * Purge page cache
     * 
     * @param int $post_id Post ID
     */
    public function purge_page_cache($post_id) {
        // Skip for autosaves
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Skip for revisions
        if (wp_is_post_revision($post_id)) {
            return;
        }
        
        // Get post
        $post = get_post($post_id);
        if (!$post) {
            return;
        }
        
        // Get cache file path
        $cache_file = $this->get_cache_file_path(get_permalink($post_id));
        
        // Delete cache file if it exists
        if (file_exists($cache_file)) {
            unlink($cache_file);
        }
        
        // Also purge homepage cache
        $home_cache_file = $this->get_cache_file_path(home_url());
        if (file_exists($home_cache_file)) {
            unlink($home_cache_file);
        }
    }

    /**
     * Purge full cache
     */
    public function purge_full_cache() {
        // Get cache directory
        $cache_dir = $this->get_cache_directory();
        
        // Check if cache directory exists
        if (!file_exists($cache_dir)) {
            return;
        }
        
        // Get all cache files
        $files = glob($cache_dir . '/*.html');
        
        // Delete all cache files
        foreach ($files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Add cache management to admin bar
     * 
     * @param WP_Admin_Bar $admin_bar Admin bar object
     */
    public function add_cache_admin_bar_menu($admin_bar) {
        // Only show for administrators
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Add main menu item
        $admin_bar->add_menu([
            'id' => 'aqualuxe-cache',
            'title' => __('Cache', 'aqualuxe'),
            'href' => '#',
        ]);
        
        // Add submenu items
        $admin_bar->add_menu([
            'parent' => 'aqualuxe-cache',
            'id' => 'aqualuxe-purge-page-cache',
            'title' => __('Purge Current Page Cache', 'aqualuxe'),
            'href' => add_query_arg([
                'aqualuxe_cache_action' => 'purge_page',
                'aqualuxe_cache_nonce' => wp_create_nonce('aqualuxe_purge_cache'),
                'aqualuxe_cache_redirect' => urlencode($_SERVER['REQUEST_URI']),
            ], admin_url()),
        ]);
        
        $admin_bar->add_menu([
            'parent' => 'aqualuxe-cache',
            'id' => 'aqualuxe-purge-full-cache',
            'title' => __('Purge All Cache', 'aqualuxe'),
            'href' => add_query_arg([
                'aqualuxe_cache_action' => 'purge_all',
                'aqualuxe_cache_nonce' => wp_create_nonce('aqualuxe_purge_cache'),
                'aqualuxe_cache_redirect' => urlencode($_SERVER['REQUEST_URI']),
            ], admin_url()),
        ]);
    }

    /**
     * Handle cache purge requests
     */
    public function handle_cache_purge_requests() {
        // Check if this is a cache purge request
        if (!isset($_GET['aqualuxe_cache_action']) || !isset($_GET['aqualuxe_cache_nonce'])) {
            return;
        }
        
        // Verify nonce
        if (!wp_verify_nonce($_GET['aqualuxe_cache_nonce'], 'aqualuxe_purge_cache')) {
            wp_die(__('Security check failed.', 'aqualuxe'));
        }
        
        // Check user capability
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have permission to purge cache.', 'aqualuxe'));
        }
        
        // Get action
        $action = $_GET['aqualuxe_cache_action'];
        
        // Perform action
        if ($action === 'purge_page') {
            // Get current page URL
            $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : home_url();
            
            // Get cache file path
            $cache_file = $this->get_cache_file_path($url);
            
            // Delete cache file if it exists
            if (file_exists($cache_file)) {
                unlink($cache_file);
            }
            
            // Add success message
            add_settings_error(
                'aqualuxe_cache',
                'aqualuxe_cache_purged',
                __('Page cache purged successfully.', 'aqualuxe'),
                'updated'
            );
        } elseif ($action === 'purge_all') {
            // Purge all cache
            $this->purge_full_cache();
            
            // Add success message
            add_settings_error(
                'aqualuxe_cache',
                'aqualuxe_cache_purged',
                __('All cache purged successfully.', 'aqualuxe'),
                'updated'
            );
        }
        
        // Redirect back
        if (isset($_GET['aqualuxe_cache_redirect'])) {
            wp_redirect(urldecode($_GET['aqualuxe_cache_redirect']));
            exit;
        }
    }

    /**
     * Get cache directory
     * 
     * @return string Cache directory path
     */
    private function get_cache_directory() {
        $upload_dir = wp_upload_dir();
        return $upload_dir['basedir'] . '/aqualuxe-cache';
    }

    /**
     * Get cache file path
     * 
     * @param string $url URL to cache
     * @return string Cache file path
     */
    private function get_cache_file_path($url = '') {
        // Get current URL if not provided
        if (empty($url)) {
            $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }
        
        // Generate cache key
        $cache_key = md5($url);
        
        // Get cache directory
        $cache_dir = $this->get_cache_directory();
        
        // Return cache file path
        return $cache_dir . '/' . $cache_key . '.html';
    }
}

// Initialize the caching class
new AquaLuxe_Caching();