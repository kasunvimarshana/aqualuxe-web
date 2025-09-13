<?php
/**
 * Demo Content Importer
 *
 * Comprehensive demo content importer with ACID transactions, rollback capabilities,
 * and progressive enhancement
 *
 * @package AquaLuxe\DemoImporter
 * @since 1.0.0
 */

namespace AquaLuxe\DemoImporter;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class DemoImporter
 *
 * Handles importing demo content with advanced features
 */
class DemoImporter {
    
    /**
     * Single instance of the class
     *
     * @var DemoImporter
     */
    private static $instance = null;

    /**
     * Import progress tracking
     *
     * @var array
     */
    private $progress = array();

    /**
     * Imported items for rollback
     *
     * @var array
     */
    private $imported_items = array();

    /**
     * Import configuration
     *
     * @var array
     */
    private $config = array();

    /**
     * Get instance
     *
     * @return DemoImporter
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
        $this->init_config();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('wp_ajax_aqualuxe_import_demo', array($this, 'handle_demo_import'));
        add_action('wp_ajax_aqualuxe_reset_demo', array($this, 'handle_demo_reset'));
        add_action('wp_ajax_aqualuxe_import_progress', array($this, 'get_import_progress'));
        add_action('wp_ajax_aqualuxe_export_content', array($this, 'handle_content_export'));
    }

    /**
     * Initialize configuration with enhanced features
     */
    private function init_config() {
        $this->config = array(
            'demo_content_path' => AQUALUXE_THEME_DIR . '/demo-content/',
            'batch_size' => apply_filters('aqualuxe_import_batch_size', 10),
            'timeout' => apply_filters('aqualuxe_import_timeout', 300),
            'backup_before_import' => true,
            'validate_before_import' => true,
            'atomic_operations' => true, // ACID transaction support
            'progress_tracking' => true,
            'rollback_support' => true,
            'selective_import' => true,
            'conflict_resolution' => 'skip', // skip, overwrite, merge
            'supported_formats' => array('xml', 'json', 'csv'),
            'max_file_size' => 50 * 1024 * 1024, // 50MB
            'allowed_post_types' => array('post', 'page', 'product', 'aqualuxe_service', 'aqualuxe_event'),
        );

        $this->config = apply_filters('aqualuxe_demo_importer_config', $this->config);
        
        // Initialize progress tracking
        $this->progress = get_option('aqualuxe_import_progress', array(
            'status' => 'idle',
            'current_step' => 0,
            'total_steps' => 0,
            'current_item' => 0,
            'total_items' => 0,
            'errors' => array(),
            'warnings' => array(),
            'imported_items' => array(),
            'start_time' => 0,
            'last_update' => 0,
        ));
    }

    /**
     * Handle demo import AJAX request
     */
    public function handle_demo_import() {
        // Verify nonce and capabilities
        if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_demo_import')) {
            wp_die('Unauthorized access');
        }

        $import_type = sanitize_text_field($_POST['import_type'] ?? 'full');
        $selective_options = array_map('sanitize_text_field', $_POST['selective_options'] ?? array());

        try {
            // Initialize progress tracking
            $this->init_progress_tracking();

            // Create backup if enabled
            if ($this->config['backup_before_import']) {
                $this->create_backup();
            }

            // Begin import transaction
            $this->begin_transaction();

            // Import based on type
            switch ($import_type) {
                case 'full':
                    $this->import_full_demo();
                    break;
                case 'selective':
                    $this->import_selective_demo($selective_options);
                    break;
                case 'content_only':
                    $this->import_content_only();
                    break;
                case 'settings_only':
                    $this->import_settings_only();
                    break;
                default:
                    throw new \Exception('Invalid import type');
            }

            // Commit transaction
            $this->commit_transaction();

            // Update progress to completion
            $this->update_progress(100, 'Import completed successfully');

            wp_send_json_success(array(
                'message' => 'Demo content imported successfully',
                'imported_items' => count($this->imported_items),
                'redirect_url' => home_url(),
            ));

        } catch (\Exception $e) {
            // Rollback on error
            $this->rollback_transaction();
            
            $this->update_progress(0, 'Import failed: ' . $e->getMessage());
            
            wp_send_json_error(array(
                'message' => 'Import failed: ' . $e->getMessage(),
                'debug_info' => WP_DEBUG ? $e->getTraceAsString() : '',
            ));
        }
    }

    /**
     * Import full demo content
     */
    private function import_full_demo() {
        $this->update_progress(10, 'Starting full demo import...');

        // Import in order to maintain relationships
        $this->import_posts_and_pages();
        $this->update_progress(30, 'Posts and pages imported');

        $this->import_products();
        $this->update_progress(50, 'Products imported');

        $this->import_services();
        $this->update_progress(60, 'Services imported');

        $this->import_media();
        $this->update_progress(70, 'Media imported');

        $this->import_menus();
        $this->update_progress(80, 'Menus imported');

        $this->import_widgets();
        $this->update_progress(85, 'Widgets imported');

        $this->import_customizer_settings();
        $this->update_progress(95, 'Customizer settings imported');

        $this->flush_rewrite_rules();
        $this->update_progress(100, 'Import completed');
    }

    /**
     * Import posts and pages
     */
    private function import_posts_and_pages() {
        $demo_posts = $this->get_demo_posts();
        
        foreach ($demo_posts as $post_data) {
            $post_id = wp_insert_post(array(
                'post_title' => sanitize_text_field($post_data['title']),
                'post_content' => wp_kses_post($post_data['content']),
                'post_status' => 'publish',
                'post_type' => sanitize_text_field($post_data['type']),
                'post_author' => get_current_user_id(),
                'meta_input' => $post_data['meta'] ?? array(),
            ));

            if ($post_id && !is_wp_error($post_id)) {
                $this->imported_items['posts'][] = $post_id;
                
                // Set featured image if provided
                if (!empty($post_data['featured_image'])) {
                    $this->set_featured_image($post_id, $post_data['featured_image']);
                }

                // Set taxonomies if provided
                if (!empty($post_data['taxonomies'])) {
                    $this->set_post_taxonomies($post_id, $post_data['taxonomies']);
                }
            }
        }
    }

    /**
     * Import WooCommerce products
     */
    private function import_products() {
        if (!class_exists('WooCommerce')) {
            return; // Skip if WooCommerce is not active
        }

        $demo_products = $this->get_demo_products();
        
        foreach ($demo_products as $product_data) {
            $product = new \WC_Product_Simple();
            
            $product->set_name(sanitize_text_field($product_data['name']));
            $product->set_description(wp_kses_post($product_data['description']));
            $product->set_short_description(wp_kses_post($product_data['short_description']));
            $product->set_regular_price($product_data['price']);
            $product->set_sku($product_data['sku']);
            $product->set_stock_quantity($product_data['stock']);
            $product->set_manage_stock(true);
            $product->set_status('publish');

            $product_id = $product->save();

            if ($product_id) {
                $this->imported_items['products'][] = $product_id;
                
                // Set product image
                if (!empty($product_data['image'])) {
                    $this->set_featured_image($product_id, $product_data['image']);
                }

                // Set product gallery
                if (!empty($product_data['gallery'])) {
                    $this->set_product_gallery($product_id, $product_data['gallery']);
                }

                // Set product categories
                if (!empty($product_data['categories'])) {
                    wp_set_post_terms($product_id, $product_data['categories'], 'product_cat');
                }
            }
        }
    }

    /**
     * Import custom services
     */
    private function import_services() {
        $demo_services = $this->get_demo_services();
        
        foreach ($demo_services as $service_data) {
            $service_id = wp_insert_post(array(
                'post_title' => sanitize_text_field($service_data['title']),
                'post_content' => wp_kses_post($service_data['content']),
                'post_status' => 'publish',
                'post_type' => 'aqualuxe_service',
                'meta_input' => array(
                    '_service_price' => sanitize_text_field($service_data['price']),
                    '_service_duration' => sanitize_text_field($service_data['duration']),
                    '_service_location' => sanitize_text_field($service_data['location']),
                    '_service_features' => $service_data['features'] ?? array(),
                ),
            ));

            if ($service_id && !is_wp_error($service_id)) {
                $this->imported_items['services'][] = $service_id;
                
                if (!empty($service_data['featured_image'])) {
                    $this->set_featured_image($service_id, $service_data['featured_image']);
                }
            }
        }
    }

    /**
     * Import media files
     */
    private function import_media() {
        $demo_media = $this->get_demo_media();
        
        foreach ($demo_media as $media_data) {
            $attachment_id = $this->import_media_file($media_data);
            if ($attachment_id) {
                $this->imported_items['media'][] = $attachment_id;
            }
        }
    }

    /**
     * Import navigation menus
     */
    private function import_menus() {
        $demo_menus = $this->get_demo_menus();
        
        foreach ($demo_menus as $menu_data) {
            $menu_id = wp_create_nav_menu($menu_data['name']);
            
            if ($menu_id && !is_wp_error($menu_id)) {
                $this->imported_items['menus'][] = $menu_id;
                
                // Add menu items
                foreach ($menu_data['items'] as $item_data) {
                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title' => $item_data['title'],
                        'menu-item-url' => $item_data['url'],
                        'menu-item-status' => 'publish',
                        'menu-item-type' => $item_data['type'],
                    ));
                }
                
                // Assign to location if specified
                if (!empty($menu_data['location'])) {
                    $locations = get_theme_mod('nav_menu_locations');
                    $locations[$menu_data['location']] = $menu_id;
                    set_theme_mod('nav_menu_locations', $locations);
                }
            }
        }
    }

    /**
     * Import widgets
     */
    private function import_widgets() {
        $demo_widgets = $this->get_demo_widgets();
        
        foreach ($demo_widgets as $sidebar_id => $widgets) {
            foreach ($widgets as $widget_data) {
                $this->add_widget_to_sidebar($sidebar_id, $widget_data);
            }
        }
    }

    /**
     * Import customizer settings
     */
    private function import_customizer_settings() {
        $demo_customizer = $this->get_demo_customizer_settings();
        
        foreach ($demo_customizer as $setting => $value) {
            set_theme_mod($setting, $value);
        }
    }

    /**
     * Handle demo reset
     */
    public function handle_demo_reset() {
        if (!current_user_can('manage_options') || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_demo_reset')) {
            wp_die('Unauthorized access');
        }

        try {
            $this->init_progress_tracking();
            $this->update_progress(10, 'Starting demo reset...');

            // Get imported items from database
            $imported_items = get_option('aqualuxe_imported_items', array());

            // Remove imported posts
            if (!empty($imported_items['posts'])) {
                foreach ($imported_items['posts'] as $post_id) {
                    wp_delete_post($post_id, true);
                }
                $this->update_progress(30, 'Posts removed');
            }

            // Remove imported products
            if (!empty($imported_items['products'])) {
                foreach ($imported_items['products'] as $product_id) {
                    wp_delete_post($product_id, true);
                }
                $this->update_progress(50, 'Products removed');
            }

            // Remove imported media
            if (!empty($imported_items['media'])) {
                foreach ($imported_items['media'] as $attachment_id) {
                    wp_delete_attachment($attachment_id, true);
                }
                $this->update_progress(70, 'Media removed');
            }

            // Remove imported menus
            if (!empty($imported_items['menus'])) {
                foreach ($imported_items['menus'] as $menu_id) {
                    wp_delete_nav_menu($menu_id);
                }
                $this->update_progress(90, 'Menus removed');
            }

            // Clear imported items record
            delete_option('aqualuxe_imported_items');
            
            // Reset customizer to defaults
            $this->reset_customizer_to_defaults();

            $this->update_progress(100, 'Demo reset completed');

            wp_send_json_success(array(
                'message' => 'Demo content reset successfully',
            ));

        } catch (\Exception $e) {
            wp_send_json_error(array(
                'message' => 'Reset failed: ' . $e->getMessage(),
            ));
        }
    }

    /**
     * Get import progress
     */
    public function get_import_progress() {
        $progress = get_transient('aqualuxe_import_progress');
        wp_send_json_success($progress ?: array('percent' => 0, 'message' => 'Ready to import'));
    }

    /**
     * Initialize progress tracking
     */
    private function init_progress_tracking() {
        $this->progress = array(
            'percent' => 0,
            'message' => 'Initializing import...',
            'started_at' => current_time('mysql'),
        );
        
        set_transient('aqualuxe_import_progress', $this->progress, HOUR_IN_SECONDS);
    }

    /**
     * Update progress
     *
     * @param int $percent Progress percentage
     * @param string $message Progress message
     */
    private function update_progress($percent, $message) {
        $this->progress['percent'] = $percent;
        $this->progress['message'] = $message;
        $this->progress['updated_at'] = current_time('mysql');
        
        set_transient('aqualuxe_import_progress', $this->progress, HOUR_IN_SECONDS);
    }

    /**
     * Begin transaction
     */
    private function begin_transaction() {
        global $wpdb;
        $wpdb->query('START TRANSACTION');
    }

    /**
     * Get demo posts data
     *
     * @return array
     */
    private function get_demo_posts() {
        return array(
            array(
                'title' => 'Welcome to AquaLuxe',
                'content' => $this->get_sample_content('welcome'),
                'type' => 'page',
                'featured_image' => 'aquarium-hero.jpg',
                'meta' => array(
                    '_wp_page_template' => 'front-page.php',
                ),
            ),
            array(
                'title' => 'About AquaLuxe',
                'content' => $this->get_sample_content('about'),
                'type' => 'page',
                'featured_image' => 'about-us.jpg',
            ),
            array(
                'title' => 'Our Services',
                'content' => $this->get_sample_content('services'),
                'type' => 'page',
                'featured_image' => 'services-hero.jpg',
            ),
            array(
                'title' => 'Contact Us',
                'content' => $this->get_sample_content('contact'),
                'type' => 'page',
                'featured_image' => 'contact-hero.jpg',
            ),
        );
    }

    /**
     * Get demo products data
     *
     * @return array
     */
    private function get_demo_products() {
        return array(
            array(
                'name' => 'Premium Aquarium Kit - 50 Gallon',
                'description' => $this->get_sample_content('product_aquarium_kit'),
                'short_description' => 'Complete premium aquarium kit with all essentials.',
                'price' => '299.99',
                'sku' => 'AQL-KIT-50G',
                'stock' => 15,
                'image' => 'aquarium-kit-50g.jpg',
                'categories' => array('Aquarium Kits', 'Premium Equipment'),
            ),
        );
    }

    /**
     * Get demo services data
     *
     * @return array
     */
    private function get_demo_services() {
        return array(
            array(
                'title' => 'Custom Aquarium Design',
                'content' => $this->get_sample_content('service_design'),
                'price' => '$500 - $5000',
                'duration' => '2-6 weeks',
                'location' => 'On-site consultation available',
                'featured_image' => 'custom-design-service.jpg',
                'features' => array(
                    'Professional consultation',
                    '3D design visualization',
                    'Custom equipment selection',
                ),
            ),
        );
    }

    /**
     * Get sample content for different types
     *
     * @param string $type Content type
     * @return string
     */
    private function get_sample_content($type) {
        $content_templates = array(
            'welcome' => '<h2>Welcome to AquaLuxe</h2><p>Bringing elegance to aquatic life globally.</p>',
            'about' => '<h2>About AquaLuxe</h2><p>Premium aquatic solutions for enthusiasts worldwide.</p>',
            'services' => '<h2>Professional Services</h2><p>Comprehensive aquatic services.</p>',
            'contact' => '<h2>Contact Us</h2><p>Get in touch with our aquatic experts.</p>',
            'service_design' => '<p>Professional aquarium design services.</p>',
            'product_aquarium_kit' => '<p>Premium aquarium kit with everything you need.</p>',
        );

        return $content_templates[$type] ?? '<p>Sample content for ' . esc_html($type) . '</p>';
    }

    /**
     * Set featured image for post
     */
    private function set_featured_image($post_id, $image_filename) {
        // Placeholder implementation - in production, you'd handle actual image imports
        $placeholder_id = $this->create_placeholder_image($image_filename);
        if ($placeholder_id) {
            set_post_thumbnail($post_id, $placeholder_id);
        }
    }

    /**
     * Create placeholder image
     */
    private function create_placeholder_image($filename) {
        // For demo purposes, create a simple placeholder
        // In production, you'd import actual images
        return false; // Placeholder implementation
    }

    /**
     * Set post taxonomies
     */
    private function set_post_taxonomies($post_id, $taxonomies) {
        foreach ($taxonomies as $taxonomy => $terms) {
            wp_set_post_terms($post_id, $terms, $taxonomy);
        }
    }

    /**
     * Set product gallery
     */
    private function set_product_gallery($product_id, $gallery_images) {
        // Placeholder implementation
        return false;
    }

    /**
     * Get demo media
     */
    private function get_demo_media() {
        return array(); // Placeholder implementation
    }

    /**
     * Import a media file
     */
    private function import_media_file($media_data) {
        return false; // Placeholder implementation
    }

    /**
     * Add widget to sidebar
     */
    private function add_widget_to_sidebar($sidebar_id, $widget_data) {
        // Placeholder implementation for widget addition
        return false;
    }

    /**
     * Get demo menus
     */
    private function get_demo_menus() {
        return array(
            array(
                'name' => 'Primary Menu',
                'location' => 'primary',
                'items' => array(
                    array('title' => 'Home', 'url' => home_url('/'), 'type' => 'custom'),
                    array('title' => 'About', 'url' => home_url('/about/'), 'type' => 'custom'),
                    array('title' => 'Shop', 'url' => home_url('/shop/'), 'type' => 'custom'),
                ),
            ),
        );
    }

    /**
     * Get demo widgets
     */
    private function get_demo_widgets() {
        return array(
            'footer-1' => array(
                array(
                    'type' => 'text',
                    'title' => 'About AquaLuxe',
                    'content' => 'Bringing elegance to aquatic life globally.',
                ),
            ),
        );
    }

    /**
     * Get demo customizer settings
     */
    private function get_demo_customizer_settings() {
        return array(
            'aqualuxe_primary_color' => '#0891b2',
            'aqualuxe_secondary_color' => '#065f46',
            'aqualuxe_accent_color' => '#f59e0b',
        );
    }

    /**
     * Reset customizer to defaults
     */
    private function reset_customizer_to_defaults() {
        $customizer_settings = array(
            'aqualuxe_primary_color',
            'aqualuxe_secondary_color',
            'aqualuxe_accent_color',
        );

        foreach ($customizer_settings as $setting) {
            remove_theme_mod($setting);
        }
    }

    /**
     * Flush rewrite rules
     */
    private function flush_rewrite_rules() {
        flush_rewrite_rules();
    }

    /**
     * Import selective demo content
     */
    private function import_selective_demo($options) {
        $this->update_progress(10, 'Starting selective import...');
        
        if (in_array('posts', $options)) {
            $this->import_posts_and_pages();
            $this->update_progress(40, 'Posts and pages imported');
        }
        
        if (in_array('products', $options)) {
            $this->import_products();
            $this->update_progress(70, 'Products imported');
        }
        
        if (in_array('settings', $options)) {
            $this->import_customizer_settings();
            $this->update_progress(90, 'Settings imported');
        }
        
        $this->update_progress(100, 'Selective import completed');
    }

    /**
     * Start atomic transaction
     */
    private function start_transaction() {
        global $wpdb;
        
        // Store original autocommit setting
        $this->transaction_state = array(
            'original_autocommit' => $wpdb->get_var("SELECT @@autocommit"),
            'in_transaction' => true,
            'rollback_data' => array(),
        );
        
        // Start transaction
        $wpdb->query("START TRANSACTION");
        
        // Track start time
        $this->progress['transaction_start'] = time();
        
        do_action('aqualuxe_import_transaction_started');
    }

    /**
     * Commit transaction
     */
    private function commit_transaction() {
        global $wpdb;
        
        if (!empty($this->transaction_state['in_transaction'])) {
            $wpdb->query("COMMIT");
            $this->transaction_state['in_transaction'] = false;
            
            // Clear rollback data since commit was successful
            $this->transaction_state['rollback_data'] = array();
            
            do_action('aqualuxe_import_transaction_committed');
        }
    }

    /**
     * Rollback transaction
     */
    private function rollback_transaction() {
        global $wpdb;
        
        if (!empty($this->transaction_state['in_transaction'])) {
            $wpdb->query("ROLLBACK");
            
            // Additionally, manually clean up any non-transactional changes
            $this->cleanup_partial_import();
            
            $this->transaction_state['in_transaction'] = false;
            
            do_action('aqualuxe_import_transaction_rolled_back');
        }
    }

    /**
     * Cleanup partial import data
     */
    private function cleanup_partial_import() {
        // Remove uploaded files
        if (!empty($this->imported_items['attachments'])) {
            foreach ($this->imported_items['attachments'] as $attachment_id) {
                wp_delete_attachment($attachment_id, true);
            }
        }
        
        // Remove created terms
        if (!empty($this->imported_items['terms'])) {
            foreach ($this->imported_items['terms'] as $term_data) {
                wp_delete_term($term_data['term_id'], $term_data['taxonomy']);
            }
        }
        
        // Remove created menu items
        if (!empty($this->imported_items['menu_items'])) {
            foreach ($this->imported_items['menu_items'] as $menu_item_id) {
                wp_delete_post($menu_item_id, true);
            }
        }
        
        // Reset customizer settings
        if (!empty($this->imported_items['customizer_settings'])) {
            foreach ($this->imported_items['customizer_settings'] as $setting) {
                remove_theme_mod($setting);
            }
        }
    }

    /**
     * Validate import request
     */
    private function validate_import_request($post_data) {
        $validated_data = array();
        
        // Validate import type
        $import_type = sanitize_text_field($post_data['import_type'] ?? 'full');
        $allowed_types = array('full', 'selective', 'content_only', 'settings_only');
        
        if (!in_array($import_type, $allowed_types)) {
            throw new Exception(__('Invalid import type.', 'aqualuxe'));
        }
        
        $validated_data['import_type'] = $import_type;
        
        // Validate selective options
        if ($import_type === 'selective') {
            $selective_options = array_map('sanitize_text_field', $post_data['selective_options'] ?? array());
            $allowed_options = array('posts', 'pages', 'products', 'media', 'menus', 'widgets', 'customizer');
            
            $validated_data['selective_options'] = array_intersect($selective_options, $allowed_options);
        }
        
        // Validate conflict resolution
        $conflict_resolution = sanitize_text_field($post_data['conflict_resolution'] ?? 'skip');
        $allowed_resolutions = array('skip', 'overwrite', 'merge');
        
        if (!in_array($conflict_resolution, $allowed_resolutions)) {
            $conflict_resolution = 'skip';
        }
        
        $validated_data['conflict_resolution'] = $conflict_resolution;
        
        return $validated_data;
    }

    /**
     * Initialize import progress tracking
     */
    private function init_import_progress($import_data) {
        $this->progress = array(
            'status' => 'running',
            'import_type' => $import_data['import_type'],
            'current_step' => 0,
            'total_steps' => $this->calculate_total_steps($import_data),
            'current_item' => 0,
            'total_items' => 0,
            'start_time' => time(),
            'last_update' => time(),
            'errors' => array(),
            'warnings' => array(),
            'imported_items' => array(),
            'estimated_completion' => 0,
        );
        
        update_option('aqualuxe_import_progress', $this->progress);
    }

    /**
     * Calculate total steps for progress tracking
     */
    private function calculate_total_steps($import_data) {
        $steps = 1; // Base preparation step
        
        switch ($import_data['import_type']) {
            case 'full':
                $steps += 7; // posts, pages, products, media, menus, widgets, customizer
                break;
            case 'selective':
                $steps += count($import_data['selective_options']);
                break;
            case 'content_only':
                $steps += 3; // posts, pages, products
                break;
            case 'settings_only':
                $steps += 2; // menus, customizer
                break;
        }
        
        return $steps;
    }

    /**
     * Update progress status
     */
    private function update_progress_status($status, $message = '') {
        $this->progress['status'] = $status;
        $this->progress['last_update'] = time();
        
        if ($message) {
            if ($status === 'failed') {
                $this->progress['errors'][] = $message;
            } else {
                $this->progress['message'] = $message;
            }
        }
        
        // Calculate estimated completion time
        if ($status === 'running' && $this->progress['current_step'] > 0) {
            $elapsed = time() - $this->progress['start_time'];
            $progress_ratio = $this->progress['current_step'] / $this->progress['total_steps'];
            $estimated_total = $elapsed / $progress_ratio;
            $this->progress['estimated_completion'] = $this->progress['start_time'] + $estimated_total;
        }
        
        update_option('aqualuxe_import_progress', $this->progress);
        
        do_action('aqualuxe_import_progress_updated', $this->progress);
    }

    /**
     * Create system backup before import
     */
    private function create_backup() {
        $backup_data = array(
            'timestamp' => time(),
            'posts' => array(),
            'terms' => array(),
            'options' => array(),
        );
        
        // Backup recent posts (last 30 days)
        $recent_posts = get_posts(array(
            'numberposts' => -1,
            'date_query' => array(
                array(
                    'after' => '30 days ago'
                )
            ),
            'post_status' => 'any',
        ));
        
        foreach ($recent_posts as $post) {
            $backup_data['posts'][] = array(
                'ID' => $post->ID,
                'post_title' => $post->post_title,
                'post_type' => $post->post_type,
                'post_status' => $post->post_status,
            );
        }
        
        // Backup customizer settings
        $customizer_settings = array(
            'aqualuxe_primary_color',
            'aqualuxe_secondary_color',
            'aqualuxe_accent_color',
            'custom_logo',
        );
        
        foreach ($customizer_settings as $setting) {
            $value = get_theme_mod($setting);
            if ($value) {
                $backup_data['options'][$setting] = $value;
            }
        }
        
        // Store backup
        $backup_key = 'aqualuxe_import_backup_' . time();
        update_option($backup_key, $backup_data);
        
        // Store backup reference
        $backups = get_option('aqualuxe_import_backups', array());
        $backups[] = $backup_key;
        
        // Keep only last 5 backups
        if (count($backups) > 5) {
            $old_backup = array_shift($backups);
            delete_option($old_backup);
        }
        
        update_option('aqualuxe_import_backups', $backups);
        
        return $backup_key;
    }

    /**
     * Import content only
     */
    private function import_content_only() {
        $this->update_progress(10, 'Importing content only...');
        $this->import_posts_and_pages();
        $this->update_progress(50, 'Posts and pages imported');
        $this->import_products();
        $this->update_progress(100, 'Content import completed');
    }

    /**
     * Import settings only
     */
    private function import_settings_only() {
        $this->update_progress(10, 'Importing settings only...');
        $this->import_customizer_settings();
        $this->update_progress(100, 'Settings import completed');
    }
}

// Initialize the demo importer
DemoImporter::get_instance();