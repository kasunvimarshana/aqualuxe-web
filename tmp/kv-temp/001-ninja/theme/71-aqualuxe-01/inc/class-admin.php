<?php
/**
 * Admin Interface Class
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Admin interface class
 */
class Admin {
    
    /**
     * Instance of this class
     */
    private static $instance = null;
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Only load admin features in admin area
        if (!is_admin()) {
            return;
        }
        
        // Admin menu
        add_action('admin_menu', [$this, 'add_admin_menu']);
        
        // Admin scripts and styles
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        
        // AJAX handlers
        add_action('wp_ajax_aqualuxe_admin_action', [$this, 'handle_admin_ajax']);
        
        // Meta boxes
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
        
        // Admin notices
        add_action('admin_notices', [$this, 'show_admin_notices']);
        
        // Admin columns
        add_filter('manage_posts_columns', [$this, 'add_admin_columns']);
        add_action('manage_posts_custom_column', [$this, 'display_admin_columns'], 10, 2);
        
        // Dashboard widgets
        add_action('wp_dashboard_setup', [$this, 'add_dashboard_widgets']);
        
        // Admin footer
        add_filter('admin_footer_text', [$this, 'admin_footer_text']);
        
        // Welcome screen
        add_action('admin_init', [$this, 'maybe_show_welcome_screen']);
        
        // Theme activation
        add_action('after_switch_theme', [$this, 'theme_activation']);
        
        // Admin bar customization
        add_action('admin_bar_menu', [$this, 'customize_admin_bar'], 999);
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        // Main theme page
        add_theme_page(
            __('AquaLuxe Theme', 'aqualuxe'),
            __('AquaLuxe', 'aqualuxe'),
            'manage_options',
            'aqualuxe-theme',
            [$this, 'theme_dashboard_page']
        );
        
        // Sub-pages
        add_submenu_page(
            'aqualuxe-theme',
            __('Theme Settings', 'aqualuxe'),
            __('Settings', 'aqualuxe'),
            'manage_options',
            'aqualuxe-settings',
            [$this, 'settings_page']
        );
        
        add_submenu_page(
            'aqualuxe-theme',
            __('Performance', 'aqualuxe'),
            __('Performance', 'aqualuxe'),
            'manage_options',
            'aqualuxe-performance',
            [$this, 'performance_page']
        );
        
        add_submenu_page(
            'aqualuxe-theme',
            __('Security', 'aqualuxe'),
            __('Security', 'aqualuxe'),
            'manage_options',
            'aqualuxe-security',
            [$this, 'security_page']
        );
        
        add_submenu_page(
            'aqualuxe-theme',
            __('SEO', 'aqualuxe'),
            __('SEO', 'aqualuxe'),
            'manage_options',
            'aqualuxe-seo',
            [$this, 'seo_page']
        );
        
        add_submenu_page(
            'aqualuxe-theme',
            __('Demo Content', 'aqualuxe'),
            __('Demo Content', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo',
            [$this, 'demo_content_page']
        );
        
        add_submenu_page(
            'aqualuxe-theme',
            __('System Info', 'aqualuxe'),
            __('System Info', 'aqualuxe'),
            'manage_options',
            'aqualuxe-system',
            [$this, 'system_info_page']
        );
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Only load on theme pages
        if (strpos($hook, 'aqualuxe') === false) {
            return;
        }
        
        $asset_manager = Asset_Manager::get_instance();
        
        // Admin CSS
        wp_enqueue_style(
            'aqualuxe-admin',
            AQUALUXE_THEME_URL . '/assets/admin/css/admin.css',
            [],
            AQUALUXE_VERSION
        );
        
        // Admin JS
        wp_enqueue_script(
            'aqualuxe-admin',
            AQUALUXE_THEME_URL . '/assets/admin/js/admin.js',
            ['jquery', 'wp-util'],
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-admin', 'aqualuxeAdmin', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_admin_nonce'),
            'strings' => [
                'saving' => __('Saving...', 'aqualuxe'),
                'saved' => __('Saved!', 'aqualuxe'),
                'error' => __('Error occurred', 'aqualuxe'),
                'confirm' => __('Are you sure?', 'aqualuxe'),
                'processing' => __('Processing...', 'aqualuxe'),
            ]
        ]);
        
        // WordPress color picker
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        // WordPress media
        wp_enqueue_media();
    }
    
    /**
     * Handle admin AJAX requests
     */
    public function handle_admin_ajax() {
        check_ajax_referer('aqualuxe_admin_nonce', 'nonce');
        
        $action_type = sanitize_text_field($_POST['action_type'] ?? '');
        
        switch ($action_type) {
            case 'save_settings':
                $this->ajax_save_settings();
                break;
                
            case 'clear_cache':
                $this->ajax_clear_cache();
                break;
                
            case 'import_demo':
                $this->ajax_import_demo();
                break;
                
            case 'export_settings':
                $this->ajax_export_settings();
                break;
                
            case 'import_settings':
                $this->ajax_import_settings();
                break;
                
            default:
                wp_send_json_error(['message' => __('Invalid action', 'aqualuxe')]);
        }
    }
    
    /**
     * Ajax save settings
     */
    private function ajax_save_settings() {
        $settings = $_POST['settings'] ?? [];
        
        foreach ($settings as $key => $value) {
            $key = sanitize_text_field($key);
            $value = sanitize_text_field($value);
            set_theme_mod($key, $value);
        }
        
        wp_send_json_success(['message' => __('Settings saved successfully', 'aqualuxe')]);
    }
    
    /**
     * Ajax clear cache
     */
    private function ajax_clear_cache() {
        // Clear various caches
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }
        
        if (function_exists('w3tc_flush_all')) {
            w3tc_flush_all();
        }
        
        if (function_exists('wp_rocket_clean_domain')) {
            wp_rocket_clean_domain();
        }
        
        if (function_exists('sg_cachepress_purge_cache')) {
            sg_cachepress_purge_cache();
        }
        
        // Clear theme cache
        delete_transient('aqualuxe_cache');
        
        wp_send_json_success(['message' => __('Cache cleared successfully', 'aqualuxe')]);
    }
    
    /**
     * Ajax import demo content
     */
    private function ajax_import_demo() {
        $demo_type = sanitize_text_field($_POST['demo_type'] ?? 'basic');
        
        $demo_content = Demo_Content::get_instance();
        $result = $demo_content->import_demo($demo_type);
        
        if ($result) {
            wp_send_json_success(['message' => __('Demo content imported successfully', 'aqualuxe')]);
        } else {
            wp_send_json_error(['message' => __('Failed to import demo content', 'aqualuxe')]);
        }
    }
    
    /**
     * Ajax export settings
     */
    private function ajax_export_settings() {
        $settings = [];
        
        // Get theme mods
        $theme_mods = get_theme_mods();
        if ($theme_mods) {
            $settings['theme_mods'] = $theme_mods;
        }
        
        // Get customizer settings
        $settings['customizer'] = get_option('theme_mods_aqualuxe', []);
        
        // Get widgets
        $settings['widgets'] = get_option('sidebars_widgets', []);
        
        wp_send_json_success([
            'settings' => $settings,
            'filename' => 'aqualuxe-settings-' . date('Y-m-d-H-i-s') . '.json'
        ]);
    }
    
    /**
     * Ajax import settings
     */
    private function ajax_import_settings() {
        if (!isset($_FILES['settings_file'])) {
            wp_send_json_error(['message' => __('No file uploaded', 'aqualuxe')]);
        }
        
        $file = $_FILES['settings_file'];
        $content = file_get_contents($file['tmp_name']);
        $settings = json_decode($content, true);
        
        if (!$settings) {
            wp_send_json_error(['message' => __('Invalid settings file', 'aqualuxe')]);
        }
        
        // Import theme mods
        if (isset($settings['theme_mods'])) {
            foreach ($settings['theme_mods'] as $key => $value) {
                set_theme_mod($key, $value);
            }
        }
        
        // Import customizer settings
        if (isset($settings['customizer'])) {
            update_option('theme_mods_aqualuxe', $settings['customizer']);
        }
        
        // Import widgets
        if (isset($settings['widgets'])) {
            update_option('sidebars_widgets', $settings['widgets']);
        }
        
        wp_send_json_success(['message' => __('Settings imported successfully', 'aqualuxe')]);
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        // SEO meta box
        add_meta_box(
            'aqualuxe_seo',
            __('SEO Settings', 'aqualuxe'),
            [$this, 'seo_meta_box'],
            ['post', 'page', 'product']
        );
        
        // Page options meta box
        add_meta_box(
            'aqualuxe_page_options',
            __('Page Options', 'aqualuxe'),
            [$this, 'page_options_meta_box'],
            ['page']
        );
        
        // Product options meta box (WooCommerce)
        if (class_exists('WooCommerce')) {
            add_meta_box(
                'aqualuxe_product_options',
                __('Product Options', 'aqualuxe'),
                [$this, 'product_options_meta_box'],
                ['product']
            );
        }
    }
    
    /**
     * SEO meta box
     */
    public function seo_meta_box($post) {
        wp_nonce_field('aqualuxe_seo_nonce', 'aqualuxe_seo_nonce');
        
        $meta_title = get_post_meta($post->ID, 'aqualuxe_meta_title', true);
        $meta_description = get_post_meta($post->ID, 'aqualuxe_meta_description', true);
        $meta_keywords = get_post_meta($post->ID, 'aqualuxe_meta_keywords', true);
        $meta_robots = get_post_meta($post->ID, 'aqualuxe_meta_robots', true);
        
        echo '<table class="form-table">';
        
        echo '<tr>';
        echo '<th><label for="aqualuxe_meta_title">' . __('Meta Title', 'aqualuxe') . '</label></th>';
        echo '<td><input type="text" id="aqualuxe_meta_title" name="aqualuxe_meta_title" value="' . esc_attr($meta_title) . '" class="regular-text" /></td>';
        echo '</tr>';
        
        echo '<tr>';
        echo '<th><label for="aqualuxe_meta_description">' . __('Meta Description', 'aqualuxe') . '</label></th>';
        echo '<td><textarea id="aqualuxe_meta_description" name="aqualuxe_meta_description" rows="3" class="regular-text">' . esc_textarea($meta_description) . '</textarea></td>';
        echo '</tr>';
        
        echo '<tr>';
        echo '<th><label for="aqualuxe_meta_keywords">' . __('Meta Keywords', 'aqualuxe') . '</label></th>';
        echo '<td><input type="text" id="aqualuxe_meta_keywords" name="aqualuxe_meta_keywords" value="' . esc_attr($meta_keywords) . '" class="regular-text" /></td>';
        echo '</tr>';
        
        echo '<tr>';
        echo '<th><label for="aqualuxe_meta_robots">' . __('Meta Robots', 'aqualuxe') . '</label></th>';
        echo '<td>';
        echo '<select id="aqualuxe_meta_robots" name="aqualuxe_meta_robots">';
        echo '<option value="">' . __('Default', 'aqualuxe') . '</option>';
        echo '<option value="noindex,nofollow"' . selected($meta_robots, 'noindex,nofollow', false) . '>' . __('No Index, No Follow', 'aqualuxe') . '</option>';
        echo '<option value="noindex,follow"' . selected($meta_robots, 'noindex,follow', false) . '>' . __('No Index, Follow', 'aqualuxe') . '</option>';
        echo '<option value="index,nofollow"' . selected($meta_robots, 'index,nofollow', false) . '>' . __('Index, No Follow', 'aqualuxe') . '</option>';
        echo '</select>';
        echo '</td>';
        echo '</tr>';
        
        echo '</table>';
    }
    
    /**
     * Page options meta box
     */
    public function page_options_meta_box($post) {
        wp_nonce_field('aqualuxe_page_options_nonce', 'aqualuxe_page_options_nonce');
        
        $hide_title = get_post_meta($post->ID, 'aqualuxe_hide_title', true);
        $custom_header = get_post_meta($post->ID, 'aqualuxe_custom_header', true);
        $custom_footer = get_post_meta($post->ID, 'aqualuxe_custom_footer', true);
        
        echo '<table class="form-table">';
        
        echo '<tr>';
        echo '<th>' . __('Page Title', 'aqualuxe') . '</th>';
        echo '<td>';
        echo '<label><input type="checkbox" name="aqualuxe_hide_title" value="1"' . checked($hide_title, 1, false) . ' /> ' . __('Hide page title', 'aqualuxe') . '</label>';
        echo '</td>';
        echo '</tr>';
        
        echo '<tr>';
        echo '<th>' . __('Custom Header', 'aqualuxe') . '</th>';
        echo '<td>';
        echo '<label><input type="checkbox" name="aqualuxe_custom_header" value="1"' . checked($custom_header, 1, false) . ' /> ' . __('Use custom header for this page', 'aqualuxe') . '</label>';
        echo '</td>';
        echo '</tr>';
        
        echo '<tr>';
        echo '<th>' . __('Custom Footer', 'aqualuxe') . '</th>';
        echo '<td>';
        echo '<label><input type="checkbox" name="aqualuxe_custom_footer" value="1"' . checked($custom_footer, 1, false) . ' /> ' . __('Use custom footer for this page', 'aqualuxe') . '</label>';
        echo '</td>';
        echo '</tr>';
        
        echo '</table>';
    }
    
    /**
     * Product options meta box
     */
    public function product_options_meta_box($post) {
        wp_nonce_field('aqualuxe_product_options_nonce', 'aqualuxe_product_options_nonce');
        
        $product_badge = get_post_meta($post->ID, 'aqualuxe_product_badge', true);
        $product_brand = get_post_meta($post->ID, 'aqualuxe_product_brand', true);
        $product_video = get_post_meta($post->ID, 'aqualuxe_product_video', true);
        
        echo '<table class="form-table">';
        
        echo '<tr>';
        echo '<th><label for="aqualuxe_product_badge">' . __('Product Badge', 'aqualuxe') . '</label></th>';
        echo '<td><input type="text" id="aqualuxe_product_badge" name="aqualuxe_product_badge" value="' . esc_attr($product_badge) . '" class="regular-text" /></td>';
        echo '</tr>';
        
        echo '<tr>';
        echo '<th><label for="aqualuxe_product_brand">' . __('Product Brand', 'aqualuxe') . '</label></th>';
        echo '<td><input type="text" id="aqualuxe_product_brand" name="aqualuxe_product_brand" value="' . esc_attr($product_brand) . '" class="regular-text" /></td>';
        echo '</tr>';
        
        echo '<tr>';
        echo '<th><label for="aqualuxe_product_video">' . __('Product Video URL', 'aqualuxe') . '</label></th>';
        echo '<td><input type="url" id="aqualuxe_product_video" name="aqualuxe_product_video" value="' . esc_url($product_video) . '" class="regular-text" /></td>';
        echo '</tr>';
        
        echo '</table>';
    }
    
    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save SEO meta
        if (isset($_POST['aqualuxe_seo_nonce']) && wp_verify_nonce($_POST['aqualuxe_seo_nonce'], 'aqualuxe_seo_nonce')) {
            $fields = ['aqualuxe_meta_title', 'aqualuxe_meta_description', 'aqualuxe_meta_keywords', 'aqualuxe_meta_robots'];
            
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
                }
            }
        }
        
        // Save page options
        if (isset($_POST['aqualuxe_page_options_nonce']) && wp_verify_nonce($_POST['aqualuxe_page_options_nonce'], 'aqualuxe_page_options_nonce')) {
            $checkboxes = ['aqualuxe_hide_title', 'aqualuxe_custom_header', 'aqualuxe_custom_footer'];
            
            foreach ($checkboxes as $checkbox) {
                $value = isset($_POST[$checkbox]) ? 1 : 0;
                update_post_meta($post_id, $checkbox, $value);
            }
        }
        
        // Save product options
        if (isset($_POST['aqualuxe_product_options_nonce']) && wp_verify_nonce($_POST['aqualuxe_product_options_nonce'], 'aqualuxe_product_options_nonce')) {
            $fields = ['aqualuxe_product_badge', 'aqualuxe_product_brand', 'aqualuxe_product_video'];
            
            foreach ($fields as $field) {
                if (isset($_POST[$field])) {
                    update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
                }
            }
        }
    }
    
    /**
     * Show admin notices
     */
    public function show_admin_notices() {
        // Welcome notice for new installations
        if (get_option('aqualuxe_show_welcome', true)) {
            echo '<div class="notice notice-info is-dismissible">';
            echo '<p><strong>' . __('Welcome to AquaLuxe!', 'aqualuxe') . '</strong></p>';
            echo '<p>' . __('Thank you for choosing AquaLuxe. To get started, please visit the theme settings page.', 'aqualuxe') . '</p>';
            echo '<p><a href="' . admin_url('themes.php?page=aqualuxe-theme') . '" class="button button-primary">' . __('Get Started', 'aqualuxe') . '</a></p>';
            echo '</div>';
        }
        
        // Performance notices
        $this->show_performance_notices();
        
        // Security notices
        $this->show_security_notices();
    }
    
    /**
     * Show performance notices
     */
    private function show_performance_notices() {
        $performance = Performance::get_instance();
        $report = $performance->generate_performance_report();
        
        if ($report['score'] < 70) {
            echo '<div class="notice notice-warning">';
            echo '<p><strong>' . __('Performance Notice', 'aqualuxe') . '</strong></p>';
            echo '<p>' . __('Your site performance score is below 70. Consider optimizing your site.', 'aqualuxe') . '</p>';
            echo '<p><a href="' . admin_url('themes.php?page=aqualuxe-performance') . '" class="button">' . __('View Performance Report', 'aqualuxe') . '</a></p>';
            echo '</div>';
        }
    }
    
    /**
     * Show security notices
     */
    private function show_security_notices() {
        $security = Security::get_instance();
        $status = $security->get_security_status();
        
        $issues = [];
        if (!$status['file_editing_disabled']) {
            $issues[] = __('File editing is not disabled', 'aqualuxe');
        }
        if (!$status['uploads_protected']) {
            $issues[] = __('Uploads directory is not protected', 'aqualuxe');
        }
        
        if (!empty($issues)) {
            echo '<div class="notice notice-warning">';
            echo '<p><strong>' . __('Security Notice', 'aqualuxe') . '</strong></p>';
            echo '<p>' . implode(', ', $issues) . '</p>';
            echo '<p><a href="' . admin_url('themes.php?page=aqualuxe-security') . '" class="button">' . __('Fix Security Issues', 'aqualuxe') . '</a></p>';
            echo '</div>';
        }
    }
    
    /**
     * Add admin columns
     */
    public function add_admin_columns($columns) {
        $columns['aqualuxe_seo'] = __('SEO Score', 'aqualuxe');
        return $columns;
    }
    
    /**
     * Display admin columns
     */
    public function display_admin_columns($column, $post_id) {
        if ($column === 'aqualuxe_seo') {
            $score = $this->calculate_seo_score($post_id);
            $color = $score >= 80 ? 'green' : ($score >= 60 ? 'orange' : 'red');
            echo '<span style="color: ' . $color . '; font-weight: bold;">' . $score . '%</span>';
        }
    }
    
    /**
     * Calculate SEO score for post
     */
    private function calculate_seo_score($post_id) {
        $score = 100;
        
        // Check meta title
        $meta_title = get_post_meta($post_id, 'aqualuxe_meta_title', true);
        if (!$meta_title) {
            $score -= 20;
        }
        
        // Check meta description
        $meta_description = get_post_meta($post_id, 'aqualuxe_meta_description', true);
        if (!$meta_description) {
            $score -= 20;
        }
        
        // Check featured image
        if (!has_post_thumbnail($post_id)) {
            $score -= 15;
        }
        
        // Check content length
        $content = get_post_field('post_content', $post_id);
        if (str_word_count(strip_tags($content)) < 300) {
            $score -= 15;
        }
        
        // Check excerpt
        $excerpt = get_post_field('post_excerpt', $post_id);
        if (!$excerpt) {
            $score -= 10;
        }
        
        return max(0, $score);
    }
    
    /**
     * Add dashboard widgets
     */
    public function add_dashboard_widgets() {
        wp_add_dashboard_widget(
            'aqualuxe_performance_widget',
            __('AquaLuxe Performance', 'aqualuxe'),
            [$this, 'performance_dashboard_widget']
        );
        
        wp_add_dashboard_widget(
            'aqualuxe_security_widget',
            __('AquaLuxe Security', 'aqualuxe'),
            [$this, 'security_dashboard_widget']
        );
    }
    
    /**
     * Performance dashboard widget
     */
    public function performance_dashboard_widget() {
        $performance = Performance::get_instance();
        $metrics = $performance->get_performance_metrics();
        
        echo '<div class="aqualuxe-dashboard-widget">';
        echo '<h4>' . __('Performance Metrics', 'aqualuxe') . '</h4>';
        echo '<ul>';
        echo '<li>' . __('Page Load Time:', 'aqualuxe') . ' ' . ($metrics['page_load_time'] ?? 'N/A') . 'ms</li>';
        echo '<li>' . __('Memory Usage:', 'aqualuxe') . ' ' . ($metrics['memory_usage'] ?? 'N/A') . 'MB</li>';
        echo '<li>' . __('Database Queries:', 'aqualuxe') . ' ' . ($metrics['db_queries'] ?? 'N/A') . '</li>';
        echo '</ul>';
        echo '<p><a href="' . admin_url('themes.php?page=aqualuxe-performance') . '" class="button button-primary">' . __('View Full Report', 'aqualuxe') . '</a></p>';
        echo '</div>';
    }
    
    /**
     * Security dashboard widget
     */
    public function security_dashboard_widget() {
        $security = Security::get_instance();
        $logs = $security->get_security_logs(5);
        
        echo '<div class="aqualuxe-dashboard-widget">';
        echo '<h4>' . __('Recent Security Events', 'aqualuxe') . '</h4>';
        
        if (empty($logs)) {
            echo '<p>' . __('No security events recorded.', 'aqualuxe') . '</p>';
        } else {
            echo '<ul>';
            foreach ($logs as $log) {
                echo '<li>';
                echo '<strong>' . esc_html($log['event_type']) . '</strong> - ';
                echo esc_html($log['timestamp']);
                echo '</li>';
            }
            echo '</ul>';
        }
        
        echo '<p><a href="' . admin_url('themes.php?page=aqualuxe-security') . '" class="button button-primary">' . __('View Security Settings', 'aqualuxe') . '</a></p>';
        echo '</div>';
    }
    
    /**
     * Admin footer text
     */
    public function admin_footer_text($text) {
        $screen = get_current_screen();
        
        if (strpos($screen->id, 'aqualuxe') !== false) {
            $text = sprintf(
                __('Thank you for using <strong>AquaLuxe</strong>! Version %s', 'aqualuxe'),
                AQUALUXE_VERSION
            );
        }
        
        return $text;
    }
    
    /**
     * Maybe show welcome screen
     */
    public function maybe_show_welcome_screen() {
        if (get_option('aqualuxe_show_welcome_screen', false)) {
            delete_option('aqualuxe_show_welcome_screen');
            wp_redirect(admin_url('themes.php?page=aqualuxe-theme&welcome=1'));
            exit;
        }
    }
    
    /**
     * Theme activation
     */
    public function theme_activation() {
        // Set flag to show welcome screen
        update_option('aqualuxe_show_welcome_screen', true);
        
        // Set default theme options
        $this->set_default_options();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Set default theme options
     */
    private function set_default_options() {
        $defaults = [
            'aqualuxe_logo' => '',
            'aqualuxe_site_description' => get_bloginfo('description'),
            'aqualuxe_primary_color' => '#0ea5e9',
            'aqualuxe_secondary_color' => '#06b6d4',
            'aqualuxe_enable_dark_mode' => true,
            'aqualuxe_enable_search' => true,
            'aqualuxe_enable_wishlist' => true,
            'aqualuxe_posts_per_page' => 10,
        ];
        
        foreach ($defaults as $key => $value) {
            if (get_theme_mod($key) === false) {
                set_theme_mod($key, $value);
            }
        }
    }
    
    /**
     * Customize admin bar
     */
    public function customize_admin_bar($wp_admin_bar) {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $wp_admin_bar->add_node([
            'id' => 'aqualuxe-menu',
            'title' => __('AquaLuxe', 'aqualuxe'),
            'href' => admin_url('themes.php?page=aqualuxe-theme'),
        ]);
        
        $wp_admin_bar->add_node([
            'id' => 'aqualuxe-settings',
            'parent' => 'aqualuxe-menu',
            'title' => __('Settings', 'aqualuxe'),
            'href' => admin_url('themes.php?page=aqualuxe-settings'),
        ]);
        
        $wp_admin_bar->add_node([
            'id' => 'aqualuxe-performance',
            'parent' => 'aqualuxe-menu',
            'title' => __('Performance', 'aqualuxe'),
            'href' => admin_url('themes.php?page=aqualuxe-performance'),
        ]);
        
        $wp_admin_bar->add_node([
            'id' => 'aqualuxe-security',
            'parent' => 'aqualuxe-menu',
            'title' => __('Security', 'aqualuxe'),
            'href' => admin_url('themes.php?page=aqualuxe-security'),
        ]);
    }
    
    /**
     * Page methods - these will be implemented in separate files
     */
    
    public function theme_dashboard_page() {
        include AQUALUXE_THEME_DIR . '/admin/pages/dashboard.php';
    }
    
    public function settings_page() {
        include AQUALUXE_THEME_DIR . '/admin/pages/settings.php';
    }
    
    public function performance_page() {
        include AQUALUXE_THEME_DIR . '/admin/pages/performance.php';
    }
    
    public function security_page() {
        include AQUALUXE_THEME_DIR . '/admin/pages/security.php';
    }
    
    public function seo_page() {
        include AQUALUXE_THEME_DIR . '/admin/pages/seo.php';
    }
    
    public function demo_content_page() {
        include AQUALUXE_THEME_DIR . '/admin/pages/demo-content.php';
    }
    
    public function system_info_page() {
        include AQUALUXE_THEME_DIR . '/admin/pages/system-info.php';
    }
}
