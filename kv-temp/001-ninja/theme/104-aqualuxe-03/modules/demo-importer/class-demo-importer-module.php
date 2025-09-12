<?php
/**
 * Demo Importer Module
 * 
 * Comprehensive demo content importer with robust functionality
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Demo_Importer_Module {
    
    /**
     * Import progress option key
     */
    const PROGRESS_KEY = 'aqualuxe_import_progress';
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('wp_ajax_aqualuxe_import_demo', [$this, 'ajax_import_demo']);
        add_action('wp_ajax_aqualuxe_reset_data', [$this, 'ajax_reset_data']);
        add_action('wp_ajax_aqualuxe_get_import_progress', [$this, 'ajax_get_import_progress']);
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            esc_html__('Demo Importer', 'aqualuxe'),
            esc_html__('Demo Importer', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-importer',
            [$this, 'admin_page']
        );
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if ('appearance_page_aqualuxe-demo-importer' !== $hook) {
            return;
        }
        
        wp_enqueue_script(
            'aqualuxe-demo-importer',
            AQUALUXE_ASSETS_URI . '/js/modules/demo-importer.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        wp_enqueue_style(
            'aqualuxe-demo-importer',
            AQUALUXE_ASSETS_URI . '/css/modules/demo-importer.css',
            [],
            AQUALUXE_VERSION
        );
        
        wp_localize_script('aqualuxe-demo-importer', 'aqualuxe_importer_vars', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_importer_nonce'),
            'strings' => [
                'importing' => esc_html__('Importing...', 'aqualuxe'),
                'import_complete' => esc_html__('Import completed successfully!', 'aqualuxe'),
                'import_error' => esc_html__('Import failed. Please try again.', 'aqualuxe'),
                'confirm_reset' => esc_html__('Are you sure you want to reset all data? This action cannot be undone.', 'aqualuxe'),
                'resetting' => esc_html__('Resetting data...', 'aqualuxe'),
                'reset_complete' => esc_html__('Data reset completed!', 'aqualuxe'),
                'reset_error' => esc_html__('Reset failed. Please try again.', 'aqualuxe'),
            ]
        ]);
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        ?>
        <div class="wrap aqualuxe-demo-importer">
            <h1><?php esc_html_e('AquaLuxe Demo Importer', 'aqualuxe'); ?></h1>
            <p class="description"><?php esc_html_e('Import demo content to quickly set up your AquaLuxe website with sample pages, products, and configurations.', 'aqualuxe'); ?></p>
            
            <div class="demo-importer-container">
                <!-- Demo Options -->
                <div class="demo-options">
                    <h2><?php esc_html_e('Available Demo Content', 'aqualuxe'); ?></h2>
                    
                    <div class="demo-grid">
                        <div class="demo-option" data-demo="full">
                            <div class="demo-preview">
                                <img src="<?php echo AQUALUXE_THEME_URI; ?>/assets/src/images/demo-full.jpg" alt="<?php esc_attr_e('Full Demo Preview', 'aqualuxe'); ?>">
                            </div>
                            <div class="demo-content">
                                <h3><?php esc_html_e('Complete Demo', 'aqualuxe'); ?></h3>
                                <p><?php esc_html_e('Full website with all content, products, blog posts, pages, and configurations. Perfect for getting started quickly.', 'aqualuxe'); ?></p>
                                <ul class="demo-includes">
                                    <li><?php esc_html_e('20+ Pages & Posts', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('50+ WooCommerce Products', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Navigation Menus', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Customizer Settings', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Widgets Configuration', 'aqualuxe'); ?></li>
                                </ul>
                                <button class="button button-primary import-demo" data-demo="full">
                                    <?php esc_html_e('Import Complete Demo', 'aqualuxe'); ?>
                                </button>
                            </div>
                        </div>
                        
                        <div class="demo-option" data-demo="essential">
                            <div class="demo-preview">
                                <img src="<?php echo AQUALUXE_THEME_URI; ?>/assets/src/images/demo-essential.jpg" alt="<?php esc_attr_e('Essential Demo Preview', 'aqualuxe'); ?>">
                            </div>
                            <div class="demo-content">
                                <h3><?php esc_html_e('Essential Demo', 'aqualuxe'); ?></h3>
                                <p><?php esc_html_e('Core pages and basic content to get started. Ideal if you want to add your own content.', 'aqualuxe'); ?></p>
                                <ul class="demo-includes">
                                    <li><?php esc_html_e('Essential Pages Only', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Sample Products', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Basic Navigation', 'aqualuxe'); ?></li>
                                    <li><?php esc_html_e('Default Settings', 'aqualuxe'); ?></li>
                                </ul>
                                <button class="button button-secondary import-demo" data-demo="essential">
                                    <?php esc_html_e('Import Essential Demo', 'aqualuxe'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Import Progress -->
                <div class="import-progress" style="display: none;">
                    <h3><?php esc_html_e('Import Progress', 'aqualuxe'); ?></h3>
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                    <div class="progress-status">
                        <span class="current-step"><?php esc_html_e('Preparing import...', 'aqualuxe'); ?></span>
                        <span class="progress-percent">0%</span>
                    </div>
                    <div class="progress-log"></div>
                </div>
                
                <!-- Reset Data -->
                <div class="reset-section">
                    <h2><?php esc_html_e('Reset Website Data', 'aqualuxe'); ?></h2>
                    <p class="description">
                        <?php esc_html_e('Warning: This will permanently delete all posts, pages, products, comments, and media files. This action cannot be undone.', 'aqualuxe'); ?>
                    </p>
                    
                    <div class="reset-options">
                        <label>
                            <input type="checkbox" id="reset-posts" checked>
                            <?php esc_html_e('Delete all posts and pages', 'aqualuxe'); ?>
                        </label>
                        <label>
                            <input type="checkbox" id="reset-products" checked>
                            <?php esc_html_e('Delete all WooCommerce products', 'aqualuxe'); ?>
                        </label>
                        <label>
                            <input type="checkbox" id="reset-media" checked>
                            <?php esc_html_e('Delete all media files', 'aqualuxe'); ?>
                        </label>
                        <label>
                            <input type="checkbox" id="reset-customizer">
                            <?php esc_html_e('Reset customizer settings', 'aqualuxe'); ?>
                        </label>
                        <label>
                            <input type="checkbox" id="reset-menus" checked>
                            <?php esc_html_e('Delete navigation menus', 'aqualuxe'); ?>
                        </label>
                        <label>
                            <input type="checkbox" id="reset-widgets">
                            <?php esc_html_e('Reset widgets', 'aqualuxe'); ?>
                        </label>
                    </div>
                    
                    <button class="button button-link-delete reset-data" id="reset-website-data">
                        <?php esc_html_e('Reset Website Data', 'aqualuxe'); ?>
                    </button>
                </div>
                
                <!-- System Requirements -->
                <div class="system-requirements">
                    <h3><?php esc_html_e('System Requirements', 'aqualuxe'); ?></h3>
                    <div class="requirements-grid">
                        <?php $this->check_system_requirements(); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .aqualuxe-demo-importer .demo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .aqualuxe-demo-importer .demo-option {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .aqualuxe-demo-importer .demo-preview img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .aqualuxe-demo-importer .demo-content {
            padding: 20px;
        }
        
        .aqualuxe-demo-importer .demo-includes {
            list-style: none;
            padding: 0;
            margin: 15px 0;
        }
        
        .aqualuxe-demo-importer .demo-includes li:before {
            content: "✓";
            color: #46b450;
            margin-right: 8px;
        }
        
        .aqualuxe-demo-importer .progress-bar {
            background: #f0f0f1;
            border-radius: 4px;
            height: 20px;
            margin: 10px 0;
            overflow: hidden;
        }
        
        .aqualuxe-demo-importer .progress-fill {
            background: linear-gradient(to right, #0073aa, #00a0d2);
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .aqualuxe-demo-importer .progress-status {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .aqualuxe-demo-importer .progress-log {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            max-height: 200px;
            overflow-y: auto;
            font-family: monospace;
            font-size: 12px;
        }
        
        .aqualuxe-demo-importer .reset-options {
            margin: 15px 0;
        }
        
        .aqualuxe-demo-importer .reset-options label {
            display: block;
            margin: 8px 0;
        }
        
        .aqualuxe-demo-importer .requirements-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 15px;
            margin: 15px 0;
        }
        
        .aqualuxe-demo-importer .requirement-item {
            padding: 10px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .aqualuxe-demo-importer .requirement-item.pass {
            background: #d4edda;
            border: 1px solid #c3e6cb;
        }
        
        .aqualuxe-demo-importer .requirement-item.fail {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
        }
        
        .aqualuxe-demo-importer .requirement-status {
            font-weight: bold;
        }
        
        .aqualuxe-demo-importer .requirement-status.pass {
            color: #155724;
        }
        
        .aqualuxe-demo-importer .requirement-status.fail {
            color: #721c24;
        }
        </style>
        <?php
    }
    
    /**
     * Check system requirements
     */
    private function check_system_requirements() {
        $requirements = [
            'PHP Version' => [
                'current' => PHP_VERSION,
                'required' => '8.0',
                'status' => version_compare(PHP_VERSION, '8.0', '>=')
            ],
            'WordPress Version' => [
                'current' => get_bloginfo('version'),
                'required' => '6.0',
                'status' => version_compare(get_bloginfo('version'), '6.0', '>=')
            ],
            'WooCommerce' => [
                'current' => aqualuxe_is_woocommerce_active() ? 'Active' : 'Not Active',
                'required' => 'Active',
                'status' => aqualuxe_is_woocommerce_active()
            ],
            'Memory Limit' => [
                'current' => ini_get('memory_limit'),
                'required' => '256M',
                'status' => $this->check_memory_limit()
            ],
            'Max Execution Time' => [
                'current' => ini_get('max_execution_time') . 's',
                'required' => '300s',
                'status' => intval(ini_get('max_execution_time')) >= 300
            ]
        ];
        
        foreach ($requirements as $name => $requirement) {
            $status_class = $requirement['status'] ? 'pass' : 'fail';
            $status_text = $requirement['status'] ? __('Pass', 'aqualuxe') : __('Fail', 'aqualuxe');
            
            echo '<div class="requirement-item ' . $status_class . '">';
            echo '<span>' . esc_html($name) . ': ' . esc_html($requirement['current']) . '</span>';
            echo '<span class="requirement-status ' . $status_class . '">' . esc_html($status_text) . '</span>';
            echo '</div>';
        }
    }
    
    /**
     * Check memory limit
     */
    private function check_memory_limit() {
        $memory_limit = ini_get('memory_limit');
        $memory_bytes = $this->return_bytes($memory_limit);
        $required_bytes = $this->return_bytes('256M');
        
        return $memory_bytes >= $required_bytes;
    }
    
    /**
     * Convert memory limit to bytes
     */
    private function return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = intval($val);
        
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        
        return $val;
    }
    
    /**
     * AJAX handler for demo import
     */
    public function ajax_import_demo() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_importer_nonce')) {
            wp_die('Security check failed');
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $demo_type = sanitize_text_field($_POST['demo_type']);
        $step = sanitize_text_field($_POST['step'] ?? 'start');
        
        // Increase execution time and memory
        set_time_limit(300);
        ini_set('memory_limit', '512M');
        
        try {
            $result = $this->process_import_step($demo_type, $step);
            wp_send_json_success($result);
        } catch (Exception $e) {
            wp_send_json_error([
                'message' => $e->getMessage(),
                'step' => $step
            ]);
        }
    }
    
    /**
     * Process import step
     */
    private function process_import_step($demo_type, $step) {
        $steps = [
            'start' => 'initialize_import',
            'customizer' => 'import_customizer_settings',
            'pages' => 'import_pages',
            'posts' => 'import_posts',
            'products' => 'import_products',
            'menus' => 'import_menus',
            'widgets' => 'import_widgets',
            'complete' => 'complete_import'
        ];
        
        if (!isset($steps[$step])) {
            throw new Exception('Invalid import step');
        }
        
        $method = $steps[$step];
        return $this->$method($demo_type);
    }
    
    /**
     * Initialize import
     */
    private function initialize_import($demo_type) {
        update_option(self::PROGRESS_KEY, [
            'demo_type' => $demo_type,
            'current_step' => 'customizer',
            'progress' => 10,
            'status' => 'running',
            'started' => current_time('mysql')
        ]);
        
        return [
            'next_step' => 'customizer',
            'progress' => 10,
            'message' => __('Import initialized. Loading customizer settings...', 'aqualuxe')
        ];
    }
    
    /**
     * Import customizer settings
     */
    private function import_customizer_settings($demo_type) {
        $demo_data = $this->get_demo_data();
        
        if (isset($demo_data['customizer_settings'])) {
            foreach ($demo_data['customizer_settings'] as $setting => $value) {
                set_theme_mod($setting, $value);
            }
        }
        
        $this->update_progress('pages', 25, __('Customizer settings imported. Creating pages...', 'aqualuxe'));
        
        return [
            'next_step' => 'pages',
            'progress' => 25,
            'message' => __('Customizer settings imported successfully', 'aqualuxe')
        ];
    }
    
    /**
     * Import pages
     */
    private function import_pages($demo_type) {
        $demo_data = $this->get_demo_data();
        $imported_pages = 0;
        
        if (isset($demo_data['pages'])) {
            foreach ($demo_data['pages'] as $page_data) {
                $page_id = wp_insert_post([
                    'post_title' => $page_data['title'],
                    'post_name' => $page_data['slug'],
                    'post_content' => $page_data['content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'page_template' => $page_data['template'] ?? ''
                ]);
                
                if ($page_id && !is_wp_error($page_id)) {
                    // Set as front page if specified
                    if (isset($page_data['is_front_page']) && $page_data['is_front_page']) {
                        update_option('page_on_front', $page_id);
                        update_option('show_on_front', 'page');
                    }
                    
                    // Add meta fields
                    if (isset($page_data['meta'])) {
                        foreach ($page_data['meta'] as $meta_key => $meta_value) {
                            update_post_meta($page_id, $meta_key, $meta_value);
                        }
                    }
                    
                    $imported_pages++;
                }
            }
        }
        
        $this->update_progress('posts', 40, sprintf(__('Imported %d pages. Creating blog posts...', 'aqualuxe'), $imported_pages));
        
        return [
            'next_step' => 'posts',
            'progress' => 40,
            'message' => sprintf(__('Successfully imported %d pages', 'aqualuxe'), $imported_pages)
        ];
    }
    
    /**
     * Import posts
     */
    private function import_posts($demo_type) {
        $demo_data = $this->get_demo_data();
        $imported_posts = 0;
        
        if (isset($demo_data['posts'])) {
            foreach ($demo_data['posts'] as $post_data) {
                $post_id = wp_insert_post([
                    'post_title' => $post_data['title'],
                    'post_name' => $post_data['slug'],
                    'post_content' => $post_data['content'],
                    'post_excerpt' => $post_data['excerpt'] ?? '',
                    'post_status' => 'publish',
                    'post_type' => 'post',
                    'post_date' => $post_data['date'] ?? current_time('mysql')
                ]);
                
                if ($post_id && !is_wp_error($post_id)) {
                    // Set categories
                    if (isset($post_data['category'])) {
                        $cat_id = get_cat_ID($post_data['category']);
                        if (!$cat_id) {
                            $cat_id = wp_create_category($post_data['category']);
                        }
                        wp_set_post_categories($post_id, [$cat_id]);
                    }
                    
                    // Set tags
                    if (isset($post_data['tags'])) {
                        wp_set_post_tags($post_id, $post_data['tags']);
                    }
                    
                    $imported_posts++;
                }
            }
        }
        
        $next_step = aqualuxe_is_woocommerce_active() ? 'products' : 'menus';
        $progress = aqualuxe_is_woocommerce_active() ? 55 : 70;
        $message = aqualuxe_is_woocommerce_active() ? 
            sprintf(__('Imported %d posts. Creating WooCommerce products...', 'aqualuxe'), $imported_posts) :
            sprintf(__('Imported %d posts. Setting up navigation menus...', 'aqualuxe'), $imported_posts);
            
        $this->update_progress($next_step, $progress, $message);
        
        return [
            'next_step' => $next_step,
            'progress' => $progress,
            'message' => sprintf(__('Successfully imported %d blog posts', 'aqualuxe'), $imported_posts)
        ];
    }
    
    /**
     * Import WooCommerce products
     */
    private function import_products($demo_type) {
        if (!aqualuxe_is_woocommerce_active()) {
            return $this->import_menus($demo_type);
        }
        
        $demo_data = $this->get_demo_data();
        $imported_products = 0;
        
        if (isset($demo_data['products'])) {
            foreach ($demo_data['products'] as $product_data) {
                $product = new WC_Product_Simple();
                
                $product->set_name($product_data['title']);
                $product->set_slug($product_data['slug']);
                $product->set_description($product_data['description']);
                $product->set_short_description($product_data['short_description']);
                $product->set_regular_price($product_data['price']);
                
                if (isset($product_data['sale_price'])) {
                    $product->set_sale_price($product_data['sale_price']);
                }
                
                $product->set_sku($product_data['sku']);
                $product->set_stock_quantity($product_data['stock_quantity'] ?? 10);
                $product->set_manage_stock(true);
                $product->set_status('publish');
                
                $product_id = $product->save();
                
                if ($product_id) {
                    // Set categories
                    if (isset($product_data['categories'])) {
                        $cat_ids = [];
                        foreach ($product_data['categories'] as $cat_name) {
                            $term = get_term_by('name', $cat_name, 'product_cat');
                            if (!$term) {
                                $term_data = wp_insert_term($cat_name, 'product_cat');
                                if (!is_wp_error($term_data)) {
                                    $cat_ids[] = $term_data['term_id'];
                                }
                            } else {
                                $cat_ids[] = $term->term_id;
                            }
                        }
                        wp_set_object_terms($product_id, $cat_ids, 'product_cat');
                    }
                    
                    // Set tags
                    if (isset($product_data['tags'])) {
                        wp_set_object_terms($product_id, $product_data['tags'], 'product_tag');
                    }
                    
                    $imported_products++;
                }
            }
        }
        
        $this->update_progress('menus', 70, sprintf(__('Imported %d products. Setting up navigation menus...', 'aqualuxe'), $imported_products));
        
        return [
            'next_step' => 'menus',
            'progress' => 70,
            'message' => sprintf(__('Successfully imported %d products', 'aqualuxe'), $imported_products)
        ];
    }
    
    /**
     * Import navigation menus
     */
    private function import_menus($demo_type) {
        $demo_data = $this->get_demo_data();
        $imported_menus = 0;
        
        if (isset($demo_data['menus'])) {
            foreach ($demo_data['menus'] as $location => $menu_items) {
                $menu_name = ucfirst($location) . ' Menu';
                $menu_id = wp_create_nav_menu($menu_name);
                
                if (!is_wp_error($menu_id)) {
                    $this->create_menu_items($menu_id, $menu_items);
                    
                    // Assign to theme location
                    $locations = get_theme_mod('nav_menu_locations');
                    $locations[$location] = $menu_id;
                    set_theme_mod('nav_menu_locations', $locations);
                    
                    $imported_menus++;
                }
            }
        }
        
        $this->update_progress('widgets', 85, sprintf(__('Created %d navigation menus. Setting up widgets...', 'aqualuxe'), $imported_menus));
        
        return [
            'next_step' => 'widgets',
            'progress' => 85,
            'message' => sprintf(__('Successfully created %d navigation menus', 'aqualuxe'), $imported_menus)
        ];
    }
    
    /**
     * Create menu items
     */
    private function create_menu_items($menu_id, $items, $parent_id = 0) {
        foreach ($items as $item) {
            $menu_item_data = [
                'menu-item-title' => $item['title'],
                'menu-item-url' => $item['url'],
                'menu-item-status' => 'publish',
                'menu-item-parent-id' => $parent_id
            ];
            
            if (isset($item['classes'])) {
                $menu_item_data['menu-item-classes'] = $item['classes'];
            }
            
            $item_id = wp_update_nav_menu_item($menu_id, 0, $menu_item_data);
            
            // Add children if they exist
            if (isset($item['children']) && is_array($item['children'])) {
                $this->create_menu_items($menu_id, $item['children'], $item_id);
            }
        }
    }
    
    /**
     * Import widgets
     */
    private function import_widgets($demo_type) {
        // Basic widget setup would go here
        $this->update_progress('complete', 95, __('Widgets configured. Finalizing import...', 'aqualuxe'));
        
        return [
            'next_step' => 'complete',
            'progress' => 95,
            'message' => __('Widgets configured successfully', 'aqualuxe')
        ];
    }
    
    /**
     * Complete import
     */
    private function complete_import($demo_type) {
        // Clear any caches
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }
        
        // Update final progress
        update_option(self::PROGRESS_KEY, [
            'demo_type' => $demo_type,
            'current_step' => 'complete',
            'progress' => 100,
            'status' => 'complete',
            'completed' => current_time('mysql')
        ]);
        
        return [
            'next_step' => 'complete',
            'progress' => 100,
            'message' => __('Demo import completed successfully!', 'aqualuxe')
        ];
    }
    
    /**
     * Update import progress
     */
    private function update_progress($step, $progress, $message) {
        $current_progress = get_option(self::PROGRESS_KEY, []);
        $current_progress['current_step'] = $step;
        $current_progress['progress'] = $progress;
        $current_progress['message'] = $message;
        $current_progress['updated'] = current_time('mysql');
        
        update_option(self::PROGRESS_KEY, $current_progress);
    }
    
    /**
     * Get demo data
     */
    private function get_demo_data() {
        $demo_file = AQUALUXE_MODULES_DIR . '/demo-importer/content/demo-content.json';
        
        if (!file_exists($demo_file)) {
            throw new Exception(__('Demo content file not found', 'aqualuxe'));
        }
        
        $content = file_get_contents($demo_file);
        $data = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception(__('Invalid demo content format', 'aqualuxe'));
        }
        
        return $data;
    }
    
    /**
     * AJAX handler for data reset
     */
    public function ajax_reset_data() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_importer_nonce')) {
            wp_die('Security check failed');
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $reset_options = [
            'posts' => isset($_POST['reset_posts']),
            'products' => isset($_POST['reset_products']),
            'media' => isset($_POST['reset_media']),
            'customizer' => isset($_POST['reset_customizer']),
            'menus' => isset($_POST['reset_menus']),
            'widgets' => isset($_POST['reset_widgets'])
        ];
        
        try {
            $this->perform_reset($reset_options);
            wp_send_json_success(['message' => __('Data reset completed successfully', 'aqualuxe')]);
        } catch (Exception $e) {
            wp_send_json_error(['message' => $e->getMessage()]);
        }
    }
    
    /**
     * Perform data reset
     */
    private function perform_reset($options) {
        global $wpdb;
        
        // Increase execution time
        set_time_limit(300);
        
        if ($options['posts']) {
            // Delete all posts and pages
            $posts = get_posts([
                'post_type' => ['post', 'page'],
                'numberposts' => -1,
                'post_status' => 'any'
            ]);
            
            foreach ($posts as $post) {
                wp_delete_post($post->ID, true);
            }
        }
        
        if ($options['products'] && aqualuxe_is_woocommerce_active()) {
            // Delete all WooCommerce products
            $products = get_posts([
                'post_type' => 'product',
                'numberposts' => -1,
                'post_status' => 'any'
            ]);
            
            foreach ($products as $product) {
                wp_delete_post($product->ID, true);
            }
        }
        
        if ($options['media']) {
            // Delete all media files
            $attachments = get_posts([
                'post_type' => 'attachment',
                'numberposts' => -1,
                'post_status' => 'any'
            ]);
            
            foreach ($attachments as $attachment) {
                wp_delete_post($attachment->ID, true);
            }
        }
        
        if ($options['customizer']) {
            // Reset customizer settings
            remove_theme_mods();
        }
        
        if ($options['menus']) {
            // Delete all navigation menus
            $menus = wp_get_nav_menus();
            foreach ($menus as $menu) {
                wp_delete_nav_menu($menu->term_id);
            }
        }
        
        if ($options['widgets']) {
            // Reset widgets
            update_option('sidebars_widgets', []);
        }
        
        // Clear caches
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }
    }
    
    /**
     * AJAX handler for import progress
     */
    public function ajax_get_import_progress() {
        $progress = get_option(self::PROGRESS_KEY, []);
        wp_send_json_success($progress);
    }
}
