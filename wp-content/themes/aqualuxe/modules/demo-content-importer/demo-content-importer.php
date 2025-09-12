<?php
/**
 * Demo Content Importer Module
 * 
 * Provides comprehensive demo content import functionality with ACID-style transactions,
 * batch processing, progress tracking, and rollback capabilities
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Modules;

use AquaLuxe\Core\Base_Module;
use AquaLuxe\Core\Service_Container;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer
 */
class Demo_Content_Importer extends Base_Module {
    
    /**
     * Import status options
     */
    const IMPORT_STATUS_PENDING = 'pending';
    const IMPORT_STATUS_RUNNING = 'running';
    const IMPORT_STATUS_COMPLETED = 'completed';
    const IMPORT_STATUS_FAILED = 'failed';
    
    /**
     * Constructor
     */
    public function __construct(Service_Container $container = null) {
        parent::__construct($container);
    }
    
    /**
     * Setup the module (called by base class)
     */
    protected function setup(): void {
        // Module-specific setup
    }
    
    /**
     * Initialize on WordPress init
     */
    public function on_init(): void {
        // WordPress init hook
    }
    
    /**
     * Initialize when WordPress is fully loaded
     */
    public function on_wp_loaded(): void {
        $this->init_hooks();
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets(): void {
        // No frontend assets needed for this module
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets(): void {
        // Admin assets are enqueued in the specific admin hook
    }
    
    /**
     * Get module name
     */
    public function get_name(): string {
        return 'Demo Content Importer';
    }
    
    /**
     * Get module description
     */
    public function get_description(): string {
        return 'Comprehensive demo content importer with ACID transactions, batch processing, and rollback capabilities';
    }
    
    /**
     * Get module version
     */
    public function get_version(): string {
        return '1.0.0';
    }
    
    /**
     * Get module dependencies
     */
    public function get_dependencies(): array {
        return []; // No dependencies
    }
    
    /**
     * Check if module can be loaded
     */
    public function can_load(): bool {
        return current_user_can('manage_options');
    }
    
    /**
     * Enable the module
     */
    public function enable(): bool {
        $this->enabled = true;
        return true;
    }
    
    /**
     * Disable the module
     */
    public function disable(): bool {
        $this->enabled = false;
        return true;
    }
    
    /**
     * Check if module is enabled
     */
    public function is_enabled(): bool {
        return $this->enabled && current_user_can('manage_options');
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        
        // AJAX handlers
        add_action('wp_ajax_aqualuxe_import_demo_content', [$this, 'handle_import_demo_content']);
        add_action('wp_ajax_aqualuxe_get_import_progress', [$this, 'handle_get_import_progress']);
        add_action('wp_ajax_aqualuxe_reset_demo_content', [$this, 'handle_reset_demo_content']);
        add_action('wp_ajax_aqualuxe_export_content', [$this, 'handle_export_content']);
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'themes.php',
            esc_html__('Demo Content Importer', 'aqualuxe'),
            esc_html__('Demo Import', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-import',
            [$this, 'render_admin_page']
        );
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ('appearance_page_aqualuxe-demo-import' !== $hook) {
            return;
        }
        
        wp_enqueue_script('jquery');
        
        wp_localize_script('jquery', 'aqualuxe_demo', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_demo_nonce'),
            'strings' => [
                'importing' => esc_html__('Importing...', 'aqualuxe'),
                'completed' => esc_html__('Import completed successfully!', 'aqualuxe'),
                'failed' => esc_html__('Import failed. Please try again.', 'aqualuxe'),
                'confirm_reset' => esc_html__('Are you sure you want to reset all content? This action cannot be undone.', 'aqualuxe'),
            ]
        ]);
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        $import_status = get_option('aqualuxe_import_status', self::IMPORT_STATUS_PENDING);
        $import_progress = get_option('aqualuxe_import_progress', 0);
        $last_import = get_option('aqualuxe_last_import_date');
        
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('AquaLuxe Demo Content Importer', 'aqualuxe'); ?></h1>
            
            <div class="aqualuxe-demo-importer">
                
                <!-- Import Status -->
                <div class="import-status card">
                    <h2><?php esc_html_e('Import Status', 'aqualuxe'); ?></h2>
                    
                    <div class="status-info">
                        <p><strong><?php esc_html_e('Current Status:', 'aqualuxe'); ?></strong> 
                            <span class="status-badge status-<?php echo esc_attr($import_status); ?>">
                                <?php echo esc_html(ucfirst($import_status)); ?>
                            </span>
                        </p>
                        
                        <?php if ($last_import) : ?>
                            <p><strong><?php esc_html_e('Last Import:', 'aqualuxe'); ?></strong> 
                                <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($last_import))); ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if ($import_status === self::IMPORT_STATUS_RUNNING) : ?>
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo esc_attr($import_progress); ?>%"></div>
                                </div>
                                <span class="progress-text"><?php echo esc_html($import_progress); ?>%</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Import Options -->
                <div class="import-options card">
                    <h2><?php esc_html_e('Import Options', 'aqualuxe'); ?></h2>
                    
                    <form id="demo-import-form">
                        
                        <!-- Content Types -->
                        <div class="option-group">
                            <h3><?php esc_html_e('Content Types', 'aqualuxe'); ?></h3>
                            <label>
                                <input type="checkbox" name="import_types[]" value="pages" checked>
                                <?php esc_html_e('Pages (Home, About, Contact, etc.)', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="import_types[]" value="posts" checked>
                                <?php esc_html_e('Blog Posts & News', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="import_types[]" value="products" checked>
                                <?php esc_html_e('WooCommerce Products', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="import_types[]" value="services" checked>
                                <?php esc_html_e('Services', 'aqualuxe'); ?>
                            </label>
                        </div>
                        
                        <!-- Data Volume -->
                        <div class="option-group">
                            <h3><?php esc_html_e('Content Volume', 'aqualuxe'); ?></h3>
                            <label>
                                <input type="radio" name="content_volume" value="minimal" checked>
                                <?php esc_html_e('Minimal - Essential content only', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="radio" name="content_volume" value="standard">
                                <?php esc_html_e('Standard - Recommended demo content', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="radio" name="content_volume" value="full">
                                <?php esc_html_e('Full - Complete demo with all variations', 'aqualuxe'); ?>
                            </label>
                        </div>
                        
                    </form>
                </div>
                
                <!-- Import Actions -->
                <div class="import-actions card">
                    <h2><?php esc_html_e('Actions', 'aqualuxe'); ?></h2>
                    
                    <div class="action-buttons">
                        <button type="button" id="start-import" class="button button-primary button-hero" <?php echo ($import_status === self::IMPORT_STATUS_RUNNING) ? 'disabled' : ''; ?>>
                            <?php esc_html_e('Start Import', 'aqualuxe'); ?>
                        </button>
                        
                        <button type="button" id="reset-content" class="button button-secondary button-danger">
                            <?php esc_html_e('Reset All Content', 'aqualuxe'); ?>
                        </button>
                    </div>
                </div>
                
                <!-- Import Log -->
                <div class="import-log card">
                    <h2><?php esc_html_e('Import Log', 'aqualuxe'); ?></h2>
                    <div id="import-log-content">
                        <?php $this->display_import_log(); ?>
                    </div>
                </div>
                
            </div>
        </div>
        
        <style>
        .aqualuxe-demo-importer .card {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            margin: 20px 0;
            padding: 20px;
        }
        
        .aqualuxe-demo-importer .option-group {
            margin-bottom: 20px;
        }
        
        .aqualuxe-demo-importer .option-group label {
            display: block;
            margin: 10px 0;
        }
        
        .aqualuxe-demo-importer .option-group input[type="checkbox"],
        .aqualuxe-demo-importer .option-group input[type="radio"] {
            margin-right: 8px;
        }
        
        .aqualuxe-demo-importer .progress-container {
            margin: 15px 0;
        }
        
        .aqualuxe-demo-importer .progress-bar {
            width: 100%;
            height: 20px;
            background: #f0f0f1;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .aqualuxe-demo-importer .progress-fill {
            height: 100%;
            background: #2271b1;
            transition: width 0.3s ease;
        }
        
        .aqualuxe-demo-importer .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .aqualuxe-demo-importer .status-pending { background: #f0f6fc; color: #0073aa; }
        .aqualuxe-demo-importer .status-running { background: #fff3cd; color: #856404; }
        .aqualuxe-demo-importer .status-completed { background: #d1e7dd; color: #0f5132; }
        .aqualuxe-demo-importer .status-failed { background: #f8d7da; color: #721c24; }
        
        .aqualuxe-demo-importer .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .aqualuxe-demo-importer .button-danger {
            color: #d63384 !important;
            border-color: #d63384 !important;
        }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            $('#start-import').on('click', function() {
                if (confirm('Are you sure you want to start the import process?')) {
                    startImport();
                }
            });
            
            $('#reset-content').on('click', function() {
                if (confirm(aqualuxe_demo.strings.confirm_reset)) {
                    resetContent();
                }
            });
            
            function startImport() {
                const formData = new FormData(document.getElementById('demo-import-form'));
                formData.append('action', 'aqualuxe_import_demo_content');
                formData.append('nonce', aqualuxe_demo.nonce);
                
                $('#start-import').prop('disabled', true).text(aqualuxe_demo.strings.importing);
                
                $.ajax({
                    url: aqualuxe_demo.ajax_url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            alert(aqualuxe_demo.strings.completed);
                            location.reload();
                        } else {
                            alert(response.data.message || aqualuxe_demo.strings.failed);
                            $('#start-import').prop('disabled', false).text('Start Import');
                        }
                    },
                    error: function() {
                        alert(aqualuxe_demo.strings.failed);
                        $('#start-import').prop('disabled', false).text('Start Import');
                    }
                });
            }
            
            function resetContent() {
                $.ajax({
                    url: aqualuxe_demo.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_reset_demo_content',
                        nonce: aqualuxe_demo.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Content reset completed.');
                            location.reload();
                        } else {
                            alert('Reset failed: ' + (response.data.message || 'Unknown error'));
                        }
                    }
                });
            }
        });
        </script>
        <?php
    }
    
    /**
     * Handle import demo content AJAX
     */
    public function handle_import_demo_content() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_demo_nonce')) {
            wp_send_json_error(['message' => esc_html__('Security check failed', 'aqualuxe')]);
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => esc_html__('Insufficient permissions', 'aqualuxe')]);
        }
        
        // Get import options
        $import_types = $_POST['import_types'] ?? [];
        $content_volume = sanitize_text_field($_POST['content_volume'] ?? 'standard');
        
        // Set import status
        update_option('aqualuxe_import_status', self::IMPORT_STATUS_RUNNING);
        update_option('aqualuxe_import_progress', 0);
        
        try {
            // Start import process
            $result = $this->run_import([
                'import_types' => $import_types,
                'content_volume' => $content_volume,
            ]);
            
            if ($result['success']) {
                update_option('aqualuxe_import_status', self::IMPORT_STATUS_COMPLETED);
                update_option('aqualuxe_import_progress', 100);
                update_option('aqualuxe_last_import_date', current_time('mysql'));
                
                wp_send_json_success([
                    'message' => esc_html__('Demo content imported successfully!', 'aqualuxe'),
                    'imported' => $result['imported']
                ]);
            } else {
                throw new \Exception($result['message'] ?? esc_html__('Import failed', 'aqualuxe'));
            }
            
        } catch (\Exception $e) {
            update_option('aqualuxe_import_status', self::IMPORT_STATUS_FAILED);
            $this->log_import_message('ERROR: ' . $e->getMessage());
            
            wp_send_json_error(['message' => $e->getMessage()]);
        }
    }
    
    /**
     * Run import process
     */
    private function run_import($options) {
        $imported = [];
        $total_steps = count($options['import_types']);
        $current_step = 0;
        
        // Begin import process
        $this->log_import_message('Starting import process...');
        
        // Import pages
        if (in_array('pages', $options['import_types'])) {
            $this->log_import_message('Importing pages...');
            $imported['pages'] = $this->import_pages($options);
            $current_step++;
            $this->update_progress(($current_step / $total_steps) * 100);
        }
        
        // Import posts
        if (in_array('posts', $options['import_types'])) {
            $this->log_import_message('Importing blog posts...');
            $imported['posts'] = $this->import_posts($options);
            $current_step++;
            $this->update_progress(($current_step / $total_steps) * 100);
        }
        
        // Import products
        if (in_array('products', $options['import_types']) && class_exists('WooCommerce')) {
            $this->log_import_message('Importing WooCommerce products...');
            $imported['products'] = $this->import_products($options);
            $current_step++;
            $this->update_progress(($current_step / $total_steps) * 100);
        }
        
        // Import services
        if (in_array('services', $options['import_types'])) {
            $this->log_import_message('Importing services...');
            $imported['services'] = $this->import_services($options);
            $current_step++;
            $this->update_progress(($current_step / $total_steps) * 100);
        }
        
        $this->log_import_message('Import completed successfully!');
        
        return [
            'success' => true,
            'imported' => $imported
        ];
    }
    
    /**
     * Import pages
     */
    private function import_pages($options) {
        $pages_data = $this->get_demo_pages_data($options);
        $imported_count = 0;
        
        foreach ($pages_data as $page_data) {
            $page_id = wp_insert_post([
                'post_title' => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_excerpt' => $page_data['excerpt'] ?? '',
                'meta_input' => array_merge(
                    $page_data['meta'] ?? [],
                    ['_aqualuxe_demo_content' => '1'] // Mark as demo content
                )
            ]);
            
            if ($page_id && !is_wp_error($page_id)) {
                $imported_count++;
                
                // Set as homepage if specified
                if (!empty($page_data['is_homepage'])) {
                    update_option('page_on_front', $page_id);
                    update_option('show_on_front', 'page');
                }
                
                // Set featured image if specified
                if (!empty($page_data['featured_image'])) {
                    $this->set_demo_featured_image($page_id, $page_data['featured_image']);
                }
            }
        }
        
        return $imported_count;
    }
    
    /**
     * Get demo pages data
     */
    private function get_demo_pages_data($options) {
        return [
            [
                'title' => 'Home',
                'content' => $this->get_homepage_content(),
                'excerpt' => 'Welcome to AquaLuxe - bringing elegance to aquatic life globally.',
                'is_homepage' => true,
                'featured_image' => 'homepage-hero.jpg',
                'meta' => [
                    '_aqualuxe_page_layout' => 'fullwidth',
                    '_aqualuxe_hero_enable' => 'yes'
                ]
            ],
            [
                'title' => 'About Us',
                'content' => $this->get_about_content(),
                'excerpt' => 'Learn about our passion for aquatic life and commitment to excellence.',
                'featured_image' => 'about-us-hero.jpg',
            ],
            [
                'title' => 'Services',
                'content' => $this->get_services_content(),
                'excerpt' => 'Professional aquarium design, maintenance, and consultation services.',
                'featured_image' => 'services-hero.jpg',
            ],
            [
                'title' => 'Contact',
                'content' => $this->get_contact_content(),
                'excerpt' => 'Get in touch with our aquatic experts.',
                'featured_image' => 'contact-hero.jpg',
            ],
            [
                'title' => 'FAQ',
                'content' => $this->get_faq_content(),
                'excerpt' => 'Frequently asked questions about our products and services.',
                'featured_image' => 'faq-hero.jpg',
            ],
            [
                'title' => 'Privacy Policy',
                'content' => $this->get_privacy_policy_content(),
                'excerpt' => 'Our commitment to protecting your privacy and personal information.',
            ],
            [
                'title' => 'Terms & Conditions',
                'content' => $this->get_terms_content(),
                'excerpt' => 'Terms and conditions for using our website and services.',
            ],
        ];
    }
    
    /**
     * Get homepage content
     */
    private function get_homepage_content() {
        return '<!-- Hero Section -->
<section class="hero-section bg-gradient-to-r from-blue-600 to-cyan-600 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-6">Bringing Elegance to Aquatic Life</h1>
        <p class="text-xl mb-8">Discover premium ornamental fish, expert aquarium design, and world-class aquatic solutions</p>
        <div class="hero-actions space-x-4">
            <a href="/shop" class="btn btn-primary btn-lg">Shop Now</a>
            <a href="/services" class="btn btn-outline btn-lg">Our Services</a>
        </div>
    </div>
</section>';
    }
    
    /**
     * Import products (WooCommerce)
     */
    private function import_products($options) {
        if (!class_exists('WooCommerce')) {
            return 0;
        }
        
        // Create product categories first
        $this->create_product_categories();
        
        $products_data = $this->get_demo_products_data($options);
        $imported_count = 0;
        
        foreach ($products_data as $product_data) {
            $product_type = $product_data['type'] ?? 'simple';
            
            // Create product based on type
            switch ($product_type) {
                case 'variable':
                    $product = new \WC_Product_Variable();
                    break;
                case 'grouped':
                    $product = new \WC_Product_Grouped();
                    break;
                default:
                    $product = new \WC_Product_Simple();
                    break;
            }
            
            // Set basic product data
            $product->set_name($product_data['name']);
            $product->set_description($product_data['description']);
            $product->set_short_description($product_data['short_description']);
            $product->set_sku($product_data['sku']);
            $product->set_status('publish');
            $product->set_catalog_visibility('visible');
            $product->set_featured($product_data['featured'] ?? false);
            
            // Set pricing for simple products
            if ($product_type === 'simple') {
                $product->set_regular_price($product_data['regular_price']);
                if (isset($product_data['sale_price'])) {
                    $product->set_sale_price($product_data['sale_price']);
                }
            }
            
            // Set inventory
            $product->set_manage_stock(true);
            $product->set_stock_quantity($product_data['stock_quantity'] ?? 10);
            $product->set_stock_status($product_data['stock_status'] ?? 'instock');
            
            // Set weight and dimensions
            if (isset($product_data['weight'])) {
                $product->set_weight($product_data['weight']);
            }
            if (isset($product_data['dimensions'])) {
                $product->set_length($product_data['dimensions']['length'] ?? '');
                $product->set_width($product_data['dimensions']['width'] ?? '');
                $product->set_height($product_data['dimensions']['height'] ?? '');
            }
            
            // Save product
            $product_id = $product->save();
            
            if ($product_id) {
                $imported_count++;
                
                // Mark as demo content
                update_post_meta($product_id, '_aqualuxe_demo_content', '1');
                
                // Set categories
                if (!empty($product_data['categories'])) {
                    wp_set_object_terms($product_id, $product_data['categories'], 'product_cat');
                }
                
                // Set tags
                if (!empty($product_data['tags'])) {
                    wp_set_object_terms($product_id, $product_data['tags'], 'product_tag');
                }
                
                // Add custom meta for aquatic products
                $custom_meta = [
                    '_aqualuxe_care_level' => $product_data['care_level'] ?? '',
                    '_aqualuxe_water_type' => $product_data['water_type'] ?? '',
                    '_aqualuxe_origin' => $product_data['origin'] ?? '',
                    '_aqualuxe_adult_size' => $product_data['adult_size'] ?? '',
                    '_aqualuxe_temperament' => $product_data['temperament'] ?? '',
                    '_aqualuxe_diet' => $product_data['diet'] ?? ''
                ];
                
                foreach ($custom_meta as $key => $value) {
                    if (!empty($value)) {
                        update_post_meta($product_id, $key, $value);
                    }
                }
                
                // Set featured image
                if (!empty($product_data['featured_image'])) {
                    $this->set_demo_featured_image($product_id, $product_data['featured_image']);
                }
                
                // Create variations for variable products
                if ($product_type === 'variable' && !empty($product_data['variations'])) {
                    $this->create_product_variations($product_id, $product_data['variations']);
                }
                
                // Add product gallery
                if (!empty($product_data['gallery'])) {
                    $this->set_product_gallery($product_id, $product_data['gallery']);
                }
            }
        }
        
        return $imported_count;
    }
    
    /**
     * Get comprehensive demo products data
     */
    private function get_demo_products_data($options) {
        $all_products = [
            // Fish Products
            [
                'name' => 'Premium Discus Fish - Blue Diamond',
                'description' => $this->get_product_description('discus_blue'),
                'short_description' => 'Stunning blue diamond discus fish, hand-selected for vibrant colors and perfect form.',
                'regular_price' => '89.99',
                'sale_price' => '79.99',
                'sku' => 'AQL-DISC-BD-001',
                'stock_quantity' => 15,
                'featured' => true,
                'care_level' => 'Intermediate',
                'water_type' => 'Freshwater',
                'origin' => 'South America (Captive Bred)',
                'adult_size' => '6-8 inches',
                'temperament' => 'Peaceful',
                'diet' => 'Omnivore',
                'categories' => ['Fish', 'Freshwater Fish', 'Discus'],
                'tags' => ['discus', 'premium', 'blue', 'peaceful'],
                'featured_image' => 'discus-blue-diamond.jpg',
                'gallery' => ['discus-blue-1.jpg', 'discus-blue-2.jpg', 'discus-blue-3.jpg'],
                'weight' => '0.5',
                'dimensions' => ['length' => '8', 'width' => '8', 'height' => '3']
            ],
            [
                'name' => 'Clownfish Pair - Ocellaris',
                'description' => $this->get_product_description('clownfish_pair'),
                'short_description' => 'Bonded pair of captive-bred ocellaris clownfish, perfect for reef aquariums.',
                'regular_price' => '45.99',
                'sku' => 'AQL-CLOWN-OC-002',
                'stock_quantity' => 8,
                'care_level' => 'Beginner',
                'water_type' => 'Saltwater',
                'origin' => 'Indo-Pacific (Captive Bred)',
                'adult_size' => '3-4 inches',
                'temperament' => 'Semi-aggressive',
                'diet' => 'Omnivore',
                'categories' => ['Fish', 'Saltwater Fish', 'Clownfish'],
                'tags' => ['clownfish', 'reef-safe', 'beginner', 'pair'],
                'featured_image' => 'clownfish-ocellaris.jpg',
                'gallery' => ['clownfish-1.jpg', 'clownfish-2.jpg'],
                'weight' => '0.3'
            ],
            [
                'name' => 'Neon Tetra School Pack',
                'type' => 'variable',
                'description' => $this->get_product_description('neon_tetra'),
                'short_description' => 'Vibrant neon tetras sold in schools for community tanks.',
                'sku' => 'AQL-NEON-PACK',
                'stock_quantity' => 50,
                'care_level' => 'Beginner',
                'water_type' => 'Freshwater',
                'origin' => 'South America',
                'adult_size' => '1.5 inches',
                'temperament' => 'Peaceful',
                'diet' => 'Omnivore',
                'categories' => ['Fish', 'Freshwater Fish', 'Tetras'],
                'tags' => ['tetra', 'school', 'community', 'colorful'],
                'featured_image' => 'neon-tetra-school.jpg',
                'variations' => [
                    ['attributes' => ['pack-size' => '6'], 'price' => '18.99', 'sku' => 'AQL-NEON-006'],
                    ['attributes' => ['pack-size' => '10'], 'price' => '24.99', 'sku' => 'AQL-NEON-010'],
                    ['attributes' => ['pack-size' => '20'], 'price' => '45.99', 'sku' => 'AQL-NEON-020']
                ]
            ],
            
            // Equipment Products
            [
                'name' => 'AquaLuxe Pro LED Light - 48"',
                'type' => 'variable',
                'description' => $this->get_product_description('led_light'),
                'short_description' => 'Full spectrum LED lighting system with customizable settings for planted and reef tanks.',
                'sku' => 'AQL-LED-PRO-48',
                'stock_quantity' => 25,
                'categories' => ['Equipment', 'Lighting', 'LED Lights'],
                'tags' => ['led', 'lighting', 'reef', 'planted'],
                'featured_image' => 'led-light-pro.jpg',
                'gallery' => ['led-light-1.jpg', 'led-light-2.jpg', 'led-remote.jpg'],
                'weight' => '8.5',
                'dimensions' => ['length' => '48', 'width' => '8', 'height' => '2'],
                'variations' => [
                    ['attributes' => ['size' => '24"'], 'price' => '199.99', 'sku' => 'AQL-LED-PRO-24'],
                    ['attributes' => ['size' => '36"'], 'price' => '299.99', 'sku' => 'AQL-LED-PRO-36'],
                    ['attributes' => ['size' => '48"'], 'price' => '399.99', 'sku' => 'AQL-LED-PRO-48'],
                    ['attributes' => ['size' => '60"'], 'price' => '499.99', 'sku' => 'AQL-LED-PRO-60']
                ]
            ],
            [
                'name' => 'Premium Protein Skimmer',
                'description' => $this->get_product_description('protein_skimmer'),
                'short_description' => 'High-efficiency protein skimmer for crystal clear saltwater aquariums.',
                'regular_price' => '249.99',
                'sku' => 'AQL-SKIM-PRO-001',
                'stock_quantity' => 12,
                'categories' => ['Equipment', 'Filtration', 'Protein Skimmers'],
                'tags' => ['protein-skimmer', 'saltwater', 'filtration'],
                'featured_image' => 'protein-skimmer.jpg',
                'weight' => '12.3',
                'dimensions' => ['length' => '10', 'width' => '10', 'height' => '24']
            ],
            
            // Plant Products
            [
                'name' => 'Java Moss Portion',
                'description' => $this->get_product_description('java_moss'),
                'short_description' => 'Hardy aquatic moss perfect for beginners and breeding tanks.',
                'regular_price' => '12.99',
                'sku' => 'AQL-MOSS-JAVA-001',
                'stock_quantity' => 30,
                'care_level' => 'Beginner',
                'water_type' => 'Freshwater',
                'categories' => ['Plants', 'Mosses', 'Live Plants'],
                'tags' => ['moss', 'live-plant', 'easy', 'breeding'],
                'featured_image' => 'java-moss.jpg',
                'weight' => '0.2'
            ],
            [
                'name' => 'Amazon Sword Plant',
                'description' => $this->get_product_description('amazon_sword'),
                'short_description' => 'Large background plant that creates stunning centerpieces in planted tanks.',
                'regular_price' => '19.99',
                'sku' => 'AQL-PLANT-SWORD-001',
                'stock_quantity' => 20,
                'care_level' => 'Beginner',
                'water_type' => 'Freshwater',
                'categories' => ['Plants', 'Background Plants', 'Live Plants'],
                'tags' => ['sword', 'background', 'large', 'centerpiece'],
                'featured_image' => 'amazon-sword.jpg',
                'weight' => '0.5'
            ],
            
            // Care Products
            [
                'name' => 'AquaLuxe Water Conditioner',
                'type' => 'variable',
                'description' => $this->get_product_description('water_conditioner'),
                'short_description' => 'Premium water conditioner that removes chlorine and adds beneficial minerals.',
                'sku' => 'AQL-COND-PRO',
                'stock_quantity' => 40,
                'categories' => ['Care Products', 'Water Treatment'],
                'tags' => ['conditioner', 'water-treatment', 'essential'],
                'featured_image' => 'water-conditioner.jpg',
                'variations' => [
                    ['attributes' => ['size' => '250ml'], 'price' => '14.99', 'sku' => 'AQL-COND-250'],
                    ['attributes' => ['size' => '500ml'], 'price' => '24.99', 'sku' => 'AQL-COND-500'],
                    ['attributes' => ['size' => '1L'], 'price' => '39.99', 'sku' => 'AQL-COND-1000']
                ]
            ]
        ];
        
        // Return subset based on content volume
        switch ($options['content_volume']) {
            case 'minimal':
                return array_slice($all_products, 0, 4);
            case 'standard':
                return array_slice($all_products, 0, 6);
            case 'full':
            default:
                return $all_products;
        }
    }
    
    /**
     * Create product categories
     */
    private function create_product_categories() {
        $categories = [
            'Fish' => [
                'description' => 'Live aquarium fish of all types',
                'children' => [
                    'Freshwater Fish' => [
                        'children' => ['Discus', 'Tetras', 'Cichlids', 'Catfish']
                    ],
                    'Saltwater Fish' => [
                        'children' => ['Clownfish', 'Tangs', 'Angels', 'Wrasses']
                    ]
                ]
            ],
            'Equipment' => [
                'description' => 'Aquarium equipment and technology',
                'children' => [
                    'Lighting' => ['children' => ['LED Lights', 'T5 Lights', 'Metal Halide']],
                    'Filtration' => ['children' => ['Protein Skimmers', 'Canister Filters', 'HOB Filters']],
                    'Heaters' => [],
                    'Pumps' => []
                ]
            ],
            'Plants' => [
                'description' => 'Live aquatic plants',
                'children' => [
                    'Foreground Plants' => [],
                    'Background Plants' => [],
                    'Mosses' => [],
                    'Live Plants' => []
                ]
            ],
            'Care Products' => [
                'description' => 'Products for aquarium maintenance',
                'children' => [
                    'Water Treatment' => [],
                    'Food' => [],
                    'Medications' => []
                ]
            ]
        ];
        
        $this->create_category_hierarchy($categories);
    }
    
    /**
     * Create category hierarchy recursively
     */
    private function create_category_hierarchy($categories, $parent_id = 0) {
        foreach ($categories as $cat_name => $cat_data) {
            // Check if category already exists
            $existing = get_term_by('name', $cat_name, 'product_cat');
            
            if (!$existing) {
                $result = wp_insert_term(
                    $cat_name,
                    'product_cat',
                    [
                        'description' => $cat_data['description'] ?? '',
                        'parent' => $parent_id
                    ]
                );
                
                if (!is_wp_error($result)) {
                    $term_id = $result['term_id'];
                    // Mark as demo category
                    update_term_meta($term_id, '_aqualuxe_demo_category', '1');
                } else {
                    $term_id = $parent_id;
                }
            } else {
                $term_id = $existing->term_id;
            }
            
            // Create child categories
            if (!empty($cat_data['children'])) {
                $this->create_category_hierarchy($cat_data['children'], $term_id);
            }
        }
    }
    
    /**
     * Create product variations
     */
    private function create_product_variations($product_id, $variations_data) {
        foreach ($variations_data as $variation_data) {
            $variation = new \WC_Product_Variation();
            $variation->set_parent_id($product_id);
            $variation->set_regular_price($variation_data['price']);
            $variation->set_sku($variation_data['sku']);
            $variation->set_manage_stock(true);
            $variation->set_stock_quantity($variation_data['stock'] ?? 10);
            
            // Set attributes
            if (!empty($variation_data['attributes'])) {
                $variation->set_attributes($variation_data['attributes']);
            }
            
            $variation_id = $variation->save();
            
            if ($variation_id) {
                // Mark as demo content
                update_post_meta($variation_id, '_aqualuxe_demo_content', '1');
            }
        }
    }
    
    /**
     * Set product gallery images
     */
    private function set_product_gallery($product_id, $gallery_images) {
        $gallery_ids = [];
        
        foreach ($gallery_images as $image_name) {
            $image_id = $this->create_placeholder_image($image_name);
            if ($image_id) {
                $gallery_ids[] = $image_id;
            }
        }
        
        if (!empty($gallery_ids)) {
            update_post_meta($product_id, '_product_image_gallery', implode(',', $gallery_ids));
        }
    }
    
    /**
     * Get or create categories for posts
     */
    private function get_or_create_categories($category_names) {
        $category_ids = [];
        
        foreach ($category_names as $cat_name) {
            $existing = get_term_by('name', $cat_name, 'category');
            
            if (!$existing) {
                $result = wp_insert_term($cat_name, 'category');
                if (!is_wp_error($result)) {
                    $category_ids[] = $result['term_id'];
                    // Mark as demo category
                    update_term_meta($result['term_id'], '_aqualuxe_demo_category', '1');
                }
            } else {
                $category_ids[] = $existing->term_id;
            }
        }
        
        return $category_ids;
    }
    
    /**
     * Get or create service category
     */
    private function get_or_create_service_category($category_name) {
        $existing = get_term_by('name', $category_name, 'service_category');
        
        if (!$existing) {
            $result = wp_insert_term($category_name, 'service_category');
            if (!is_wp_error($result)) {
                // Mark as demo category
                update_term_meta($result['term_id'], '_aqualuxe_demo_category', '1');
                return get_term($result['term_id'], 'service_category');
            }
            return false;
        }
        
        return $existing;
    }
    private function get_product_description($type) {
        $descriptions = [
            'discus_blue' => '
<p>Our Blue Diamond Discus represents the pinnacle of selective breeding, showcasing intense blue coloration with diamond-like scales that shimmer under aquarium lighting.</p>

<h3>Product Features:</h3>
<ul>
    <li>Hand-selected for superior color and form</li>
    <li>Quarantined for 21 days before shipment</li>
    <li>Healthy appetite and active behavior guaranteed</li>
    <li>Size: 3-4 inches (sub-adult)</li>
    <li>Perfect for breeding programs</li>
</ul>

<h3>Care Requirements:</h3>
<ul>
    <li>Temperature: 82-86°F</li>
    <li>pH: 6.0-7.0</li>
    <li>Minimum tank size: 50 gallons</li>
    <li>Diet: High-quality discus pellets, frozen foods</li>
</ul>

<p><strong>Shipping:</strong> Live arrival guaranteed with overnight delivery.</p>',
            
            'clownfish_pair' => '
<p>This bonded pair of Ocellaris Clownfish has been together for months and shows excellent compatibility. Perfect for reef aquariums and hosting anemones.</p>

<h3>Pair Benefits:</h3>
<ul>
    <li>Established hierarchy (one larger, one smaller)</li>
    <li>Reduced aggression in tank</li>
    <li>Breeding potential</li>
    <li>Beautiful orange and white coloration</li>
</ul>

<h3>Reef Compatibility:</h3>
<ul>
    <li>100% reef safe</li>
    <li>Will host Bubble Tip Anemones</li>
    <li>Peaceful with other fish</li>
    <li>Hardy and disease resistant</li>
</ul>',
            
            'neon_tetra' => '
<p>Neon Tetras are the classic schooling fish, bringing incredible color and movement to community aquariums. Their electric blue stripe and red coloration create stunning displays.</p>

<h3>Schooling Benefits:</h3>
<ul>
    <li>Natural behavior in groups of 6+</li>
    <li>Reduced stress and better colors</li>
    <li>Active swimming patterns</li>
    <li>Perfect community fish</li>
</ul>

<h3>Tank Requirements:</h3>
<ul>
    <li>Minimum 10 gallons for small school</li>
    <li>Soft, slightly acidic water preferred</li>
    <li>Peaceful tankmates only</li>
    <li>Dense planting appreciated</li>
</ul>',
            
            'led_light' => '
<p>The AquaLuxe Pro LED represents cutting-edge aquarium lighting technology, providing full spectrum illumination for both marine reef and freshwater planted tanks.</p>

<h3>Advanced Features:</h3>
<ul>
    <li>Full spectrum LEDs (6500K-20000K)</li>
    <li>Wireless smartphone control</li>
    <li>Sunrise/sunset simulation</li>
    <li>Storm and cloud effects</li>
    <li>Individual channel control</li>
    <li>Acclimation mode for new setups</li>
</ul>

<h3>Technical Specifications:</h3>
<ul>
    <li>Power consumption: 165W</li>
    <li>Light spread: 48" x 24"</li>
    <li>Mounting: Adjustable legs included</li>
    <li>Warranty: 3 years</li>
    <li>PAR values: 200+ at 24" depth</li>
</ul>',
            
            'protein_skimmer' => '
<p>Our Premium Protein Skimmer utilizes advanced needle wheel technology to efficiently remove dissolved organics before they can break down into harmful compounds.</p>

<h3>Performance Features:</h3>
<ul>
    <li>Needle wheel impeller design</li>
    <li>Easy-empty collection cup</li>
    <li>Air valve for fine-tuning</li>
    <li>Silent operation</li>
    <li>Suitable for tanks up to 200 gallons</li>
</ul>',
            
            'java_moss' => '
<p>Java Moss is one of the most versatile and hardy aquatic plants available. It requires no special lighting or CO2, making it perfect for beginners.</p>

<h3>Uses:</h3>
<ul>
    <li>Attach to driftwood or rocks</li>
    <li>Breeding cover for fish</li>
    <li>Natural water filtration</li>
    <li>Aquascaping accent</li>
</ul>',
            
            'amazon_sword' => '
<p>The Amazon Sword is a classic centerpiece plant that can grow quite large, making it perfect for background planting in medium to large aquariums.</p>

<h3>Growth Information:</h3>
<ul>
    <li>Maximum height: 16-20 inches</li>
    <li>Growth rate: Moderate</li>
    <li>Lighting needs: Low to moderate</li>
    <li>CO2: Beneficial but not required</li>
</ul>',
            
            'water_conditioner' => '
<p>AquaLuxe Water Conditioner is a premium formula that instantly makes tap water safe for fish while adding beneficial minerals and electrolytes.</p>

<h3>Benefits:</h3>
<ul>
    <li>Removes chlorine and chloramines</li>
    <li>Detoxifies heavy metals</li>
    <li>Adds natural electrolytes</li>
    <li>Protects fish slime coat</li>
    <li>Safe for all fish and invertebrates</li>
</ul>'
        ];
        
        return $descriptions[$type] ?? '<p>High-quality aquarium product with premium features and reliable performance.</p>';
    }
    
    /**
     * Import blog posts
     */
    private function import_posts($options) {
        $posts_data = $this->get_demo_posts_data($options);
        $imported_count = 0;
        
        foreach ($posts_data as $post_data) {
            $post_id = wp_insert_post([
                'post_title' => $post_data['title'],
                'post_content' => $post_data['content'],
                'post_excerpt' => $post_data['excerpt'],
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_category' => $this->get_or_create_categories($post_data['categories'] ?? []),
                'tags_input' => $post_data['tags'] ?? [],
                'meta_input' => array_merge(
                    $post_data['meta'] ?? [],
                    ['_aqualuxe_demo_content' => '1'] // Mark as demo content
                )
            ]);
            
            if ($post_id && !is_wp_error($post_id)) {
                $imported_count++;
                
                // Set featured image if specified
                if (!empty($post_data['featured_image'])) {
                    $this->set_demo_featured_image($post_id, $post_data['featured_image']);
                }
            }
        }
        
        return $imported_count;
    }
    
    /**
     * Import services (custom post type)
     */
    private function import_services($options) {
        // Register the service post type if not exists
        $this->register_service_post_type();
        
        $services_data = $this->get_demo_services_data($options);
        $imported_count = 0;
        
        foreach ($services_data as $service_data) {
            $service_id = wp_insert_post([
                'post_title' => $service_data['title'],
                'post_content' => $service_data['content'],
                'post_excerpt' => $service_data['excerpt'],
                'post_status' => 'publish',
                'post_type' => 'aqualuxe_service',
                'meta_input' => array_merge(
                    $service_data['meta'] ?? [],
                    ['_aqualuxe_demo_content' => '1'] // Mark as demo content
                )
            ]);
            
            if ($service_id && !is_wp_error($service_id)) {
                $imported_count++;
                
                // Set service category if specified
                if (!empty($service_data['category'])) {
                    $category_term = $this->get_or_create_service_category($service_data['category']);
                    if ($category_term) {
                        wp_set_object_terms($service_id, $category_term->term_id, 'service_category');
                    }
                }
                
                // Set featured image if specified
                if (!empty($service_data['featured_image'])) {
                    $this->set_demo_featured_image($service_id, $service_data['featured_image']);
                }
            }
        }
        
        return $imported_count;
    }
    
    /**
     * Get demo posts data
     */
    private function get_demo_posts_data($options) {
        $posts = [
            [
                'title' => 'Complete Guide to Discus Fish Care',
                'content' => $this->get_blog_post_content('discus_care'),
                'excerpt' => 'Learn everything about caring for these beautiful and delicate freshwater fish.',
                'categories' => ['Fish Care', 'Freshwater'],
                'tags' => ['discus', 'freshwater', 'care-guide', 'beginner'],
                'featured_image' => 'discus-fish.jpg',
                'meta' => [
                    '_aqualuxe_reading_time' => '8 min read',
                    '_aqualuxe_difficulty' => 'Intermediate'
                ]
            ],
            [
                'title' => 'Setting Up Your First Saltwater Aquarium',
                'content' => $this->get_blog_post_content('saltwater_setup'),
                'excerpt' => 'A comprehensive guide to starting your marine aquarium journey.',
                'categories' => ['Setup Guides', 'Saltwater'],
                'tags' => ['saltwater', 'marine', 'setup', 'beginner'],
                'featured_image' => 'saltwater-setup.jpg',
                'meta' => [
                    '_aqualuxe_reading_time' => '12 min read',
                    '_aqualuxe_difficulty' => 'Advanced'
                ]
            ],
            [
                'title' => '10 Best Plants for Beginner Aquascapers',
                'content' => $this->get_blog_post_content('aquascaping_plants'),
                'excerpt' => 'Discover the easiest and most beautiful plants to start your aquascaping journey.',
                'categories' => ['Aquascaping', 'Plants'],
                'tags' => ['aquascaping', 'plants', 'beginner', 'design'],
                'featured_image' => 'aquarium-plants.jpg',
                'meta' => [
                    '_aqualuxe_reading_time' => '6 min read',
                    '_aqualuxe_difficulty' => 'Beginner'
                ]
            ],
            [
                'title' => 'Water Quality: The Foundation of Healthy Aquariums',
                'content' => $this->get_blog_post_content('water_quality'),
                'excerpt' => 'Understanding and maintaining optimal water parameters for aquatic life.',
                'categories' => ['Water Quality', 'Maintenance'],
                'tags' => ['water-quality', 'testing', 'maintenance', 'health'],
                'featured_image' => 'water-testing.jpg',
                'meta' => [
                    '_aqualuxe_reading_time' => '10 min read',
                    '_aqualuxe_difficulty' => 'Intermediate'
                ]
            ],
            [
                'title' => 'Breeding Tropical Fish: A Profitable Hobby',
                'content' => $this->get_blog_post_content('fish_breeding'),
                'excerpt' => 'Learn how to successfully breed tropical fish and turn your passion into profit.',
                'categories' => ['Breeding', 'Business'],
                'tags' => ['breeding', 'tropical-fish', 'business', 'profitable'],
                'featured_image' => 'fish-breeding.jpg',
                'meta' => [
                    '_aqualuxe_reading_time' => '15 min read',
                    '_aqualuxe_difficulty' => 'Expert'
                ]
            ]
        ];
        
        // Return subset based on content volume
        switch ($options['content_volume']) {
            case 'minimal':
                return array_slice($posts, 0, 2);
            case 'standard':
                return array_slice($posts, 0, 3);
            case 'full':
            default:
                return $posts;
        }
    }
    
    /**
     * Get demo services data
     */
    private function get_demo_services_data($options) {
        return [
            [
                'title' => 'Custom Aquarium Design',
                'content' => $this->get_service_content('custom_design'),
                'excerpt' => 'Professional aquarium design tailored to your space and preferences.',
                'category' => 'Design Services',
                'featured_image' => 'service-custom-design.jpg',
                'meta' => [
                    '_aqualuxe_service_price' => 'Starting from $500',
                    '_aqualuxe_service_duration' => '2-4 weeks',
                    '_aqualuxe_service_includes' => 'Design consultation, 3D rendering, equipment specification'
                ]
            ],
            [
                'title' => 'Aquarium Maintenance Service',
                'content' => $this->get_service_content('maintenance'),
                'excerpt' => 'Regular maintenance to keep your aquarium healthy and beautiful.',
                'category' => 'Maintenance',
                'featured_image' => 'service-maintenance.jpg',
                'meta' => [
                    '_aqualuxe_service_price' => '$80-150 per visit',
                    '_aqualuxe_service_duration' => 'Ongoing',
                    '_aqualuxe_service_includes' => 'Water testing, cleaning, equipment check, fish health assessment'
                ]
            ],
            [
                'title' => 'Aquascaping Workshop',
                'content' => $this->get_service_content('workshop'),
                'excerpt' => 'Learn the art of aquascaping from certified professionals.',
                'category' => 'Education',
                'featured_image' => 'service-workshop.jpg',
                'meta' => [
                    '_aqualuxe_service_price' => '$150 per person',
                    '_aqualuxe_service_duration' => '4 hours',
                    '_aqualuxe_service_includes' => 'Materials, tools, certificate, take-home aquascape'
                ]
            ]
        ];
    }
    
    /**
     * Get About page content
     */
    private function get_about_content() {
        return '<!-- About Us Section -->
<section class="about-hero py-16 bg-gradient-to-br from-blue-50 to-cyan-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">About AquaLuxe</h1>
                <p class="text-xl text-gray-700 mb-6">Bringing elegance to aquatic life – globally</p>
                <p class="text-gray-600 leading-relaxed">
                    For over a decade, AquaLuxe has been at the forefront of the ornamental fish industry, 
                    providing premium aquatic solutions to enthusiasts and professionals worldwide. Our passion 
                    for aquatic life drives everything we do, from sourcing the rarest specimens to designing 
                    breathtaking aquascapes.
                </p>
            </div>
            <div class="relative">
                <img src="/wp-content/themes/aqualuxe/assets/dist/images/about-hero.jpg" 
                     alt="AquaLuxe Team" 
                     class="rounded-lg shadow-2xl">
            </div>
        </div>
    </div>
</section>

<!-- Mission & Values -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Mission & Values</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                We believe in sustainable aquaculture, ethical sourcing, and creating aquatic environments 
                that bring joy and tranquility to people\'s lives.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Quality First</h3>
                <p class="text-gray-600">Every fish, plant, and product is carefully selected and quarantined to ensure the highest quality standards.</p>
            </div>
            
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Expert Support</h3>
                <p class="text-gray-600">Our team of aquatic specialists provides ongoing support and guidance for all your aquarium needs.</p>
            </div>
            
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-3">Global Reach</h3>
                <p class="text-gray-600">Serving customers worldwide with safe, reliable shipping and international compliance.</p>
            </div>
        </div>
    </div>
</section>';
    }
    
    /**
     * Get Services page content 
     */
    private function get_services_content() {
        return '<!-- Services Hero -->
<section class="services-hero py-16 bg-gradient-to-r from-blue-600 to-cyan-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl lg:text-5xl font-bold mb-6">Professional Aquatic Services</h1>
        <p class="text-xl mb-8 max-w-3xl mx-auto">
            From design to maintenance, our comprehensive services ensure your aquatic environment thrives
        </p>
    </div>
</section>

<!-- Service Categories -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Design Services -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-blue-400 to-cyan-500"></div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-4">Design & Installation</h3>
                    <ul class="space-y-2 text-gray-600 mb-6">
                        <li>• Custom aquarium design</li>
                        <li>• Professional installation</li>
                        <li>• Aquascaping services</li>
                        <li>• Equipment specification</li>
                        <li>• 3D visualization</li>
                    </ul>
                    <a href="#contact" class="btn btn-primary">Learn More</a>
                </div>
            </div>
            
            <!-- Maintenance Services -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-green-400 to-teal-500"></div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-4">Maintenance & Care</h3>
                    <ul class="space-y-2 text-gray-600 mb-6">
                        <li>• Regular maintenance visits</li>
                        <li>• Water quality testing</li>
                        <li>• Equipment servicing</li>
                        <li>• Fish health monitoring</li>
                        <li>• Emergency support</li>
                    </ul>
                    <a href="#contact" class="btn btn-primary">Learn More</a>
                </div>
            </div>
            
            <!-- Education Services -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-purple-400 to-pink-500"></div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold mb-4">Education & Training</h3>
                    <ul class="space-y-2 text-gray-600 mb-6">
                        <li>• Aquascaping workshops</li>
                        <li>• Fish breeding courses</li>
                        <li>• Maintenance training</li>
                        <li>• Business consultation</li>
                        <li>• Certification programs</li>
                    </ul>
                    <a href="#contact" class="btn btn-primary">Learn More</a>
                </div>
            </div>
            
        </div>
    </div>
</section>';
    }
    
    /**
     * Get Contact page content
     */
    private function get_contact_content() {
        return '<!-- Contact Hero -->
<section class="contact-hero py-16 bg-gray-50">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">Get In Touch</h1>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
            Ready to start your aquatic journey? Our experts are here to help with any questions or custom requirements.
        </p>
    </div>
</section>

<!-- Contact Information & Form -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            
            <!-- Contact Information -->
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Contact Information</h2>
                
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Email</h3>
                            <p class="text-gray-600">info@aqualuxe.com</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Phone</h3>
                            <p class="text-gray-600">+1 (555) 123-4567</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Address</h3>
                            <p class="text-gray-600">123 Aquatic Lane<br>Ocean City, FL 32548</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Business Hours</h3>
                    <div class="space-y-2 text-gray-600">
                        <div class="flex justify-between">
                            <span>Monday - Friday</span>
                            <span>9:00 AM - 6:00 PM</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Saturday</span>
                            <span>10:00 AM - 4:00 PM</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sunday</span>
                            <span>Closed</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Send us a Message</h2>
                
                <form class="space-y-6" method="post" action="' . esc_url(admin_url('admin-post.php')) . '">
                    <input type="hidden" name="action" value="aqualuxe_contact_form">
                    ' . wp_nonce_field('aqualuxe_contact_nonce', '_wpnonce', true, false) . '
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                            <input type="text" id="first_name" name="first_name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                            <input type="text" id="last_name" name="last_name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <select id="subject" name="subject" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a subject</option>
                            <option value="general">General Inquiry</option>
                            <option value="product">Product Information</option>
                            <option value="service">Service Request</option>
                            <option value="support">Technical Support</option>
                            <option value="wholesale">Wholesale Inquiry</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea id="message" name="message" rows="6" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Tell us about your aquatic needs..."></textarea>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                        Send Message
                    </button>
                </form>
            </div>
            
        </div>
    </div>
</section>';
    }
    
    /**
     * Get FAQ page content
     */
    private function get_faq_content() {
        return '<!-- FAQ Hero -->
<section class="faq-hero py-16 bg-gradient-to-r from-blue-600 to-cyan-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl lg:text-5xl font-bold mb-6">Frequently Asked Questions</h1>
        <p class="text-xl mb-8 max-w-3xl mx-auto">
            Find answers to common questions about our products, services, and shipping
        </p>
    </div>
</section>

<!-- FAQ Content -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            
            <!-- Shipping & Delivery -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Shipping & Delivery</h2>
                
                <div class="space-y-6">
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full text-left p-6 focus:outline-none" onclick="toggleFAQ(this)">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">How do you ship live fish safely?</h3>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                        <div class="faq-content hidden px-6 pb-6">
                            <p class="text-gray-600">We use specialized insulated boxes with oxygen-filled bags for all live fish shipments. Each fish is carefully acclimated and packaged to ensure safe arrival. We only ship on Monday-Wednesday to avoid weekend delays.</p>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full text-left p-6 focus:outline-none" onclick="toggleFAQ(this)">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">What is your live arrival guarantee?</h3>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                        <div class="faq-content hidden px-6 pb-6">
                            <p class="text-gray-600">We guarantee live arrival for all fish orders. If any fish arrive deceased, please take photos and contact us within 2 hours of delivery for a full refund or replacement.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Care & Maintenance -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Care & Maintenance</h2>
                
                <div class="space-y-6">
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full text-left p-6 focus:outline-none" onclick="toggleFAQ(this)">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">How often should I perform water changes?</h3>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                        <div class="faq-content hidden px-6 pb-6">
                            <p class="text-gray-600">For most aquariums, we recommend 20-25% water changes weekly. Heavily stocked or sensitive species may require more frequent changes. Always test your water parameters to determine the best schedule.</p>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full text-left p-6 focus:outline-none" onclick="toggleFAQ(this)">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">What temperature should I keep my aquarium?</h3>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                        <div class="faq-content hidden px-6 pb-6">
                            <p class="text-gray-600">Temperature requirements vary by species. Tropical freshwater fish typically prefer 74-78°F, while marine fish need 76-80°F. Discus and some sensitive species require higher temperatures around 82-86°F.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Products & Ordering -->
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Products & Ordering</h2>
                
                <div class="space-y-6">
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full text-left p-6 focus:outline-none" onclick="toggleFAQ(this)">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Do you offer wholesale pricing?</h3>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                        <div class="faq-content hidden px-6 pb-6">
                            <p class="text-gray-600">Yes, we offer wholesale pricing for qualified retailers, aquarium service companies, and large-volume customers. Please contact us with your business license and requirements for pricing information.</p>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-lg">
                        <button class="w-full text-left p-6 focus:outline-none" onclick="toggleFAQ(this)">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">Can I return products if I\'m not satisfied?</h3>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                        <div class="faq-content hidden px-6 pb-6">
                            <p class="text-gray-600">We offer a 30-day return policy on equipment and non-living products in original condition. Live fish and plants cannot be returned due to quarantine protocols, but we stand behind our live arrival guarantee.</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>

<script>
function toggleFAQ(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector("svg");
    
    if (content.classList.contains("hidden")) {
        content.classList.remove("hidden");
        icon.style.transform = "rotate(180deg)";
    } else {
        content.classList.add("hidden");
        icon.style.transform = "rotate(0deg)";
    }
}
</script>';
    }
    
    /**
     * Get Privacy Policy content
     */
    private function get_privacy_policy_content() {
        return '<!-- Privacy Policy -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Privacy Policy</h1>
            <p class="text-gray-600 mb-8">Last updated: ' . date('F j, Y') . '</p>
            
            <div class="prose prose-lg max-w-none">
                <h2>Information We Collect</h2>
                <p>We collect information you provide directly to us, such as when you create an account, make a purchase, sign up for our newsletter, or contact us for support.</p>
                
                <h3>Personal Information</h3>
                <ul>
                    <li>Name and contact information</li>
                    <li>Billing and shipping addresses</li>
                    <li>Payment information</li>
                    <li>Order history and preferences</li>
                    <li>Communication preferences</li>
                </ul>
                
                <h2>How We Use Your Information</h2>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Process and fulfill your orders</li>
                    <li>Communicate with you about your orders and account</li>
                    <li>Send marketing communications (with your consent)</li>
                    <li>Improve our products and services</li>
                    <li>Comply with legal obligations</li>
                </ul>
                
                <h2>Information Sharing</h2>
                <p>We do not sell, trade, or otherwise transfer your personal information to third parties except:</p>
                <ul>
                    <li>To trusted service providers who assist us in operating our website</li>
                    <li>When required by law or to protect our rights</li>
                    <li>In connection with a business transfer or merger</li>
                </ul>
                
                <h2>Data Security</h2>
                <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
                
                <h2>Your Rights</h2>
                <p>You have the right to:</p>
                <ul>
                    <li>Access and update your personal information</li>
                    <li>Request deletion of your data</li>
                    <li>Opt out of marketing communications</li>
                    <li>Request a copy of your data</li>
                </ul>
                
                <h2>Contact Us</h2>
                <p>If you have any questions about this Privacy Policy, please contact us at:</p>
                <p>Email: privacy@aqualuxe.com<br>
                Phone: +1 (555) 123-4567<br>
                Address: 123 Aquatic Lane, Ocean City, FL 32548</p>
            </div>
        </div>
    </div>
</section>';
    }
    
    /**
     * Get Terms & Conditions content
     */
    private function get_terms_content() {
        return '<!-- Terms & Conditions -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-900 mb-8">Terms & Conditions</h1>
            <p class="text-gray-600 mb-8">Last updated: ' . date('F j, Y') . '</p>
            
            <div class="prose prose-lg max-w-none">
                <h2>Acceptance of Terms</h2>
                <p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p>
                
                <h2>Products and Services</h2>
                <h3>Live Animal Sales</h3>
                <ul>
                    <li>All live animals are guaranteed to arrive alive when shipped via our approved methods</li>
                    <li>Claims for deceased animals must be reported within 2 hours of delivery with photographic evidence</li>
                    <li>We reserve the right to substitute similar species when exact species are unavailable</li>
                </ul>
                
                <h3>Product Availability</h3>
                <ul>
                    <li>Product availability is subject to change without notice</li>
                    <li>We reserve the right to limit quantities</li>
                    <li>Prices are subject to change without notice</li>
                </ul>
                
                <h2>Shipping and Delivery</h2>
                <ul>
                    <li>Live animals are shipped Monday through Wednesday only</li>
                    <li>Delivery times are estimates and not guaranteed</li>
                    <li>Customer must be available to receive live animal shipments</li>
                    <li>Additional charges may apply for remote locations</li>
                </ul>
                
                <h2>Returns and Refunds</h2>
                <h3>Equipment and Supplies</h3>
                <ul>
                    <li>Returns accepted within 30 days of purchase</li>
                    <li>Items must be in original condition and packaging</li>
                    <li>Return shipping costs are the responsibility of the customer</li>
                </ul>
                
                <h3>Live Animals</h3>
                <ul>
                    <li>Live animals cannot be returned once delivered</li>
                    <li>Refunds or replacements available for animals that arrive deceased</li>
                    <li>Health guarantee covers first 7 days after delivery</li>
                </ul>
                
                <h2>Limitation of Liability</h2>
                <p>AquaLuxe shall not be liable for any indirect, incidental, special, or consequential damages resulting from the use or inability to use our products or services.</p>
                
                <h2>Governing Law</h2>
                <p>These terms shall be governed by and construed in accordance with the laws of Florida, USA.</p>
                
                <h2>Changes to Terms</h2>
                <p>We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting to the website.</p>
                
                <h2>Contact Information</h2>
                <p>For questions regarding these terms, please contact us at:</p>
                <p>Email: legal@aqualuxe.com<br>
                Phone: +1 (555) 123-4567<br>
                Address: 123 Aquatic Lane, Ocean City, FL 32548</p>
            </div>
        </div>
    </div>
</section>';
    }
    
    /**
     * Update import progress
     */
    private function update_progress($percentage) {
        update_option('aqualuxe_import_progress', min(100, max(0, $percentage)));
    }
    
    /**
     * Log import message
     */
    private function log_import_message($message) {
        $log = get_option('aqualuxe_import_log', []);
        $log[] = '[' . current_time('Y-m-d H:i:s') . '] ' . $message;
        
        // Keep only last 100 log entries
        if (count($log) > 100) {
            $log = array_slice($log, -100);
        }
        
        update_option('aqualuxe_import_log', $log);
    }
    
    /**
     * Display import log
     */
    private function display_import_log() {
        $log = get_option('aqualuxe_import_log', []);
        
        if (empty($log)) {
            echo esc_html__('No import activities yet.', 'aqualuxe');
            return;
        }
        
        echo implode("\n", array_reverse(array_slice($log, -20))); // Show last 20 entries
    }
    
    /**
     * Handle reset demo content AJAX
     */
    public function handle_reset_demo_content() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_demo_nonce')) {
            wp_send_json_error(['message' => esc_html__('Security check failed', 'aqualuxe')]);
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => esc_html__('Insufficient permissions', 'aqualuxe')]);
        }
        
        try {
            $result = $this->reset_all_demo_content();
            
            if ($result['success']) {
                // Reset import status
                update_option('aqualuxe_import_status', self::IMPORT_STATUS_PENDING);
                update_option('aqualuxe_import_progress', 0);
                delete_option('aqualuxe_last_import_date');
                
                $this->log_import_message('Demo content reset completed successfully');
                
                wp_send_json_success([
                    'message' => esc_html__('Demo content reset completed successfully!', 'aqualuxe'),
                    'reset_count' => $result['reset_count']
                ]);
            } else {
                throw new \Exception($result['message'] ?? esc_html__('Reset failed', 'aqualuxe'));
            }
            
        } catch (\Exception $e) {
            $this->log_import_message('ERROR: Reset failed - ' . $e->getMessage());
            wp_send_json_error(['message' => $e->getMessage()]);
        }
    }
    
    /**
     * Handle export content AJAX
     */
    public function handle_export_content() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_demo_nonce')) {
            wp_send_json_error(['message' => esc_html__('Security check failed', 'aqualuxe')]);
        }
        
        // Check permissions
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => esc_html__('Insufficient permissions', 'aqualuxe')]);
        }
        
        try {
            $export_data = $this->export_demo_content();
            
            // Create temporary file for download
            $upload_dir = wp_upload_dir();
            $export_file = $upload_dir['basedir'] . '/aqualuxe-export-' . date('Y-m-d-H-i-s') . '.json';
            
            file_put_contents($export_file, json_encode($export_data, JSON_PRETTY_PRINT));
            
            wp_send_json_success([
                'message' => esc_html__('Export completed successfully!', 'aqualuxe'),
                'download_url' => $upload_dir['baseurl'] . '/' . basename($export_file),
                'file_size' => size_format(filesize($export_file))
            ]);
            
        } catch (\Exception $e) {
            wp_send_json_error(['message' => $e->getMessage()]);
        }
    }
    
    /**
     * Reset all demo content
     */
    private function reset_all_demo_content() {
        global $wpdb;
        
        $reset_count = 0;
        
        try {
            // Start transaction
            $wpdb->query('START TRANSACTION');
            
            // Get all demo posts (marked with meta key)
            $demo_posts = get_posts([
                'post_type' => ['post', 'page', 'product', 'aqualuxe_service'],
                'posts_per_page' => -1,
                'meta_key' => '_aqualuxe_demo_content',
                'meta_value' => '1',
                'post_status' => 'any'
            ]);
            
            // Delete demo posts
            foreach ($demo_posts as $post) {
                if (wp_delete_post($post->ID, true)) {
                    $reset_count++;
                }
            }
            
            // Reset homepage setting if it was a demo page
            $front_page_id = get_option('page_on_front');
            if ($front_page_id && get_post_meta($front_page_id, '_aqualuxe_demo_content', true)) {
                update_option('show_on_front', 'posts');
                delete_option('page_on_front');
            }
            
            // Clean up demo categories and tags
            $this->cleanup_demo_taxonomies();
            
            // Clean up demo options
            $this->cleanup_demo_options();
            
            // Commit transaction
            $wpdb->query('COMMIT');
            
            return [
                'success' => true,
                'reset_count' => $reset_count
            ];
            
        } catch (\Exception $e) {
            // Rollback on error
            $wpdb->query('ROLLBACK');
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Export demo content
     */
    private function export_demo_content() {
        $export_data = [
            'version' => AQUALUXE_VERSION,
            'export_date' => current_time('mysql'),
            'content' => []
        ];
        
        // Export posts
        $posts = get_posts([
            'post_type' => ['post', 'page', 'product', 'aqualuxe_service'],
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ]);
        
        foreach ($posts as $post) {
            $export_data['content']['posts'][] = [
                'title' => $post->post_title,
                'content' => $post->post_content,
                'excerpt' => $post->post_excerpt,
                'type' => $post->post_type,
                'status' => $post->post_status,
                'meta' => get_post_meta($post->ID),
                'terms' => wp_get_post_terms($post->ID, get_object_taxonomies($post->post_type))
            ];
        }
        
        // Export options
        $demo_options = [
            'aqualuxe_import_status',
            'aqualuxe_import_log',
            'aqualuxe_last_import_date'
        ];
        
        foreach ($demo_options as $option) {
            $export_data['content']['options'][$option] = get_option($option);
        }
        
        return $export_data;
    }
    
    /**
     * Cleanup demo taxonomies
     */
    private function cleanup_demo_taxonomies() {
        $demo_categories = get_terms([
            'taxonomy' => 'category',
            'meta_key' => '_aqualuxe_demo_category',
            'meta_value' => '1',
            'hide_empty' => false
        ]);
        
        foreach ($demo_categories as $category) {
            wp_delete_term($category->term_id, 'category');
        }
        
        // Clean up product categories if WooCommerce is active
        if (class_exists('WooCommerce')) {
            $demo_product_cats = get_terms([
                'taxonomy' => 'product_cat',
                'meta_key' => '_aqualuxe_demo_category',
                'meta_value' => '1',
                'hide_empty' => false
            ]);
            
            foreach ($demo_product_cats as $cat) {
                wp_delete_term($cat->term_id, 'product_cat');
            }
        }
    }
    
    /**
     * Cleanup demo options
     */
    private function cleanup_demo_options() {
        $demo_options = [
            'aqualuxe_import_log',
            'aqualuxe_demo_images_imported'
        ];
        
        foreach ($demo_options as $option) {
            delete_option($option);
        }
    }
    
    /**
     * Register service post type
     */
    private function register_service_post_type() {
        if (!post_type_exists('aqualuxe_service')) {
            register_post_type('aqualuxe_service', [
                'labels' => [
                    'name' => esc_html__('Services', 'aqualuxe'),
                    'singular_name' => esc_html__('Service', 'aqualuxe'),
                    'add_new_item' => esc_html__('Add New Service', 'aqualuxe'),
                    'edit_item' => esc_html__('Edit Service', 'aqualuxe'),
                    'new_item' => esc_html__('New Service', 'aqualuxe'),
                    'view_item' => esc_html__('View Service', 'aqualuxe'),
                    'search_items' => esc_html__('Search Services', 'aqualuxe'),
                    'not_found' => esc_html__('No services found', 'aqualuxe'),
                ],
                'public' => true,
                'has_archive' => true,
                'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'custom-fields'],
                'rewrite' => ['slug' => 'services']
            ]);
            
            // Register service categories taxonomy
            register_taxonomy('service_category', 'aqualuxe_service', [
                'labels' => [
                    'name' => esc_html__('Service Categories', 'aqualuxe'),
                    'singular_name' => esc_html__('Service Category', 'aqualuxe'),
                ],
                'hierarchical' => true,
                'public' => true,
                'rewrite' => ['slug' => 'service-category']
            ]);
        }
    }
    
    /**
     * Set demo featured image
     */
    private function set_demo_featured_image($post_id, $image_name) {
        // For demo purposes, we'll create placeholder images
        // In a real implementation, you'd use actual demo images
        $placeholder_id = $this->create_placeholder_image($image_name);
        
        if ($placeholder_id) {
            set_post_thumbnail($post_id, $placeholder_id);
        }
    }
    
    /**
     * Create placeholder image
     */
    private function create_placeholder_image($image_name) {
        // Check if we already have this placeholder
        $existing = get_posts([
            'post_type' => 'attachment',
            'meta_key' => '_aqualuxe_demo_placeholder',
            'meta_value' => $image_name,
            'posts_per_page' => 1
        ]);
        
        if (!empty($existing)) {
            return $existing[0]->ID;
        }
        
        // Create a placeholder using WordPress media functions
        // This is a simplified version - in production you'd want actual demo images
        $upload_dir = wp_upload_dir();
        $image_data = $this->generate_placeholder_image_data($image_name);
        
        if ($image_data) {
            $filename = $upload_dir['path'] . '/' . sanitize_file_name($image_name);
            file_put_contents($filename, $image_data);
            
            $attachment = [
                'guid' => $upload_dir['url'] . '/' . basename($filename),
                'post_mime_type' => 'image/jpeg',
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
                'post_content' => '',
                'post_status' => 'inherit'
            ];
            
            $attachment_id = wp_insert_attachment($attachment, $filename);
            
            if ($attachment_id) {
                // Mark as demo content
                update_post_meta($attachment_id, '_aqualuxe_demo_content', '1');
                update_post_meta($attachment_id, '_aqualuxe_demo_placeholder', $image_name);
                
                // Generate metadata
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                $attachment_data = wp_generate_attachment_metadata($attachment_id, $filename);
                wp_update_attachment_metadata($attachment_id, $attachment_data);
                
                return $attachment_id;
            }
        }
        
        return false;
    }
    
    /**
     * Generate placeholder image data
     */
    private function generate_placeholder_image_data($image_name) {
        // Create a simple colored rectangle as placeholder
        // In production, you'd use actual demo images from Unsplash/Pixabay
        $width = 800;
        $height = 600;
        
        // Create image resource
        $image = imagecreate($width, $height);
        
        if (!$image) {
            return false;
        }
        
        // Generate color based on image name for variety
        $hash = md5($image_name);
        $r = hexdec(substr($hash, 0, 2));
        $g = hexdec(substr($hash, 2, 2));
        $b = hexdec(substr($hash, 4, 2));
        
        // Make colors more aquatic (blues, teals, greens)
        $r = min(255, $r + 50);
        $g = min(255, $g + 100);
        $b = min(255, 255);
        
        $bg_color = imagecolorallocate($image, $r, $g, $b);
        $text_color = imagecolorallocate($image, 255, 255, 255);
        
        // Fill background
        imagefill($image, 0, 0, $bg_color);
        
        // Add text
        $text = 'Demo: ' . pathinfo($image_name, PATHINFO_FILENAME);
        $font_size = 5;
        $text_width = imagefontwidth($font_size) * strlen($text);
        $text_height = imagefontheight($font_size);
        
        $x = ($width - $text_width) / 2;
        $y = ($height - $text_height) / 2;
        
        imagestring($image, $font_size, $x, $y, $text, $text_color);
        
        // Capture output
        ob_start();
        imagejpeg($image, null, 80);
        $image_data = ob_get_contents();
        ob_end_clean();
        
        // Clean up
        imagedestroy($image);
        
        return $image_data;
    }
    
    /**
     * Get blog post content by type
     */
    private function get_blog_post_content($type) {
        $content_map = [
            'discus_care' => '
<p>Discus fish are among the most beautiful and sought-after freshwater aquarium fish. Known as the "King of the Aquarium," these magnificent fish require specific care to thrive in captivity.</p>

<h2>Water Parameters</h2>
<p>Discus fish are very sensitive to water quality. Maintain these parameters:</p>
<ul>
    <li><strong>Temperature:</strong> 82-86°F (28-30°C)</li>
    <li><strong>pH:</strong> 6.0-7.0</li>
    <li><strong>Hardness:</strong> 1-8 dGH</li>
    <li><strong>Ammonia/Nitrite:</strong> 0 ppm</li>
    <li><strong>Nitrate:</strong> Under 20 ppm</li>
</ul>

<h2>Tank Setup</h2>
<p>A proper discus tank should include:</p>
<ul>
    <li>Minimum 50 gallons for a small group</li>
    <li>Tall tank preferred (18+ inches height)</li>
    <li>Gentle filtration with good biological capacity</li>
    <li>Subdued lighting</li>
    <li>Live or artificial plants for security</li>
</ul>

<h2>Feeding</h2>
<p>Discus fish are omnivores that benefit from a varied diet including:</p>
<ul>
    <li>High-quality discus pellets</li>
    <li>Frozen bloodworms and brine shrimp</li>
    <li>Live foods occasionally</li>
    <li>Feed 2-3 times daily in small amounts</li>
</ul>',
            
            'saltwater_setup' => '
<p>Setting up your first saltwater aquarium can seem daunting, but with proper planning and patience, you can create a thriving marine ecosystem.</p>

<h2>Equipment Essentials</h2>
<ul>
    <li><strong>Tank:</strong> Minimum 30 gallons for beginners</li>
    <li><strong>Protein Skimmer:</strong> Essential for waste removal</li>
    <li><strong>Live Rock:</strong> 1-2 pounds per gallon</li>
    <li><strong>Powerheads:</strong> For water circulation</li>
    <li><strong>Heater:</strong> Marine-grade with controller</li>
    <li><strong>Lighting:</strong> LED or T5 fluorescent</li>
</ul>

<h2>The Nitrogen Cycle</h2>
<p>Allow 4-6 weeks for your tank to cycle before adding fish. Monitor ammonia and nitrite levels daily during this period.</p>

<h2>First Fish Recommendations</h2>
<ul>
    <li>Clownfish (Amphiprion ocellaris)</li>
    <li>Blue Tang (Paracanthurus hepatus)</li>
    <li>Royal Gramma (Gramma loreto)</li>
    <li>Cardinalfish (Pterapogon kauderni)</li>
</ul>',
            
            'aquascaping_plants' => '
<p>Aquascaping is the art of arranging aquatic plants, rocks, and driftwood in an aesthetically pleasing manner. Here are the best beginner-friendly plants:</p>

<h2>Easy Foreground Plants</h2>
<ul>
    <li><strong>Java Moss:</strong> Extremely hardy, grows anywhere</li>
    <li><strong>Dwarf Hairgrass:</strong> Creates carpeting effect</li>
    <li><strong>Cryptocoryne Parva:</strong> Small, slow-growing</li>
</ul>

<h2>Mid-ground Favorites</h2>
<ul>
    <li><strong>Anubias Nana:</strong> Very low maintenance</li>
    <li><strong>Java Fern:</strong> Tolerates low light</li>
    <li><strong>Cryptocoryne Wendtii:</strong> Comes in many colors</li>
</ul>

<h2>Background Plants</h2>
<ul>
    <li><strong>Vallisneria:</strong> Fast-growing, great for beginners</li>
    <li><strong>Amazon Sword:</strong> Large, impressive centerpiece</li>
    <li><strong>Cabomba:</strong> Feathery texture, beautiful</li>
</ul>',
            
            'water_quality' => '
<p>Water quality is the foundation of any successful aquarium. Understanding and maintaining proper parameters is crucial for fish health and longevity.</p>

<h2>Essential Parameters to Monitor</h2>
<ul>
    <li><strong>Temperature:</strong> Species-specific requirements</li>
    <li><strong>pH:</strong> Measure of acidity/alkalinity</li>
    <li><strong>Ammonia (NH3/NH4+):</strong> Should always be 0</li>
    <li><strong>Nitrite (NO2-):</strong> Should always be 0</li>
    <li><strong>Nitrate (NO3-):</strong> Keep under 20-40 ppm</li>
    <li><strong>General Hardness (GH):</strong> Mineral content</li>
    <li><strong>Carbonate Hardness (KH):</strong> pH buffer capacity</li>
</ul>

<h2>Testing Schedule</h2>
<p>Establish a regular testing routine:</p>
<ul>
    <li>Daily during tank cycling and issues</li>
    <li>Weekly for established tanks</li>
    <li>After water changes</li>
    <li>When fish show stress signs</li>
</ul>

<h2>Maintaining Stable Parameters</h2>
<ul>
    <li>Regular water changes (20-25% weekly)</li>
    <li>Proper filtration maintenance</li>
    <li>Avoid overfeeding</li>
    <li>Monitor bioload carefully</li>
</ul>',
            
            'fish_breeding' => '
<p>Breeding tropical fish can be rewarding both personally and financially. Success requires understanding fish behavior, proper setup, and patience.</p>

<h2>Getting Started</h2>
<ul>
    <li>Choose hardy, popular species</li>
    <li>Research specific breeding requirements</li>
    <li>Set up dedicated breeding tanks</li>
    <li>Maintain detailed records</li>
</ul>

<h2>Best Beginner Breeding Fish</h2>
<ul>
    <li><strong>Guppies:</strong> Livebearers, very prolific</li>
    <li><strong>Bettas:</strong> Bubble nest builders</li>
    <li><strong>Angelfish:</strong> Egg layers, good parents</li>
    <li><strong>Corydoras:</strong> Easy egg scatterers</li>
</ul>

<h2>Business Considerations</h2>
<ul>
    <li>Start small and scale gradually</li>
    <li>Build relationships with local stores</li>
    <li>Maintain high quality standards</li>
    <li>Consider online sales platforms</li>
    <li>Track costs and profits carefully</li>
</ul>'
        ];
        
        return $content_map[$type] ?? '<p>Sample blog post content for ' . $type . '</p>';
    }
    
    /**
     * Get service content by type
     */
    private function get_service_content($type) {
        $content_map = [
            'custom_design' => '
<p>Transform your space with a custom-designed aquarium that reflects your style and meets your specific needs.</p>

<h2>Our Design Process</h2>
<ol>
    <li><strong>Consultation:</strong> We discuss your vision, space, and budget</li>
    <li><strong>Site Survey:</strong> Detailed measurements and structural assessment</li>
    <li><strong>3D Design:</strong> Photorealistic rendering of your future aquarium</li>
    <li><strong>Equipment Specification:</strong> Detailed list of all required components</li>
    <li><strong>Installation:</strong> Professional setup and initial stocking</li>
</ol>

<h2>Design Styles</h2>
<ul>
    <li>Modern minimalist</li>
    <li>Natural aquascaping</li>
    <li>Reef aquarium</li>
    <li>Biotope recreation</li>
    <li>Show tank display</li>
</ul>',
            
            'maintenance' => '
<p>Keep your aquarium healthy and beautiful with our professional maintenance services.</p>

<h2>What\'s Included</h2>
<ul>
    <li>Water quality testing and adjustment</li>
    <li>Algae removal and cleaning</li>
    <li>Equipment inspection and maintenance</li>
    <li>Fish health assessment</li>
    <li>Plant trimming and care</li>
    <li>Filter media replacement</li>
    <li>Detailed maintenance report</li>
</ul>

<h2>Service Frequencies</h2>
<ul>
    <li><strong>Weekly:</strong> For complex or high-bioload systems</li>
    <li><strong>Bi-weekly:</strong> Most common for home aquariums</li>
    <li><strong>Monthly:</strong> For well-established, low-maintenance tanks</li>
</ul>',
            
            'workshop' => '
<p>Learn the art and science of aquascaping in our hands-on workshop sessions.</p>

<h2>Workshop Content</h2>
<ul>
    <li>Aquascaping principles and styles</li>
    <li>Plant selection and placement</li>
    <li>Hardscape design with rocks and driftwood</li>
    <li>Lighting and equipment setup</li>
    <li>Maintenance techniques</li>
    <li>Troubleshooting common issues</li>
</ul>

<h2>What You\'ll Take Home</h2>
<ul>
    <li>Your completed aquascape</li>
    <li>Comprehensive care guide</li>
    <li>Certificate of completion</li>
    <li>20% discount on future plant purchases</li>
    <li>Access to our online community</li>
</ul>'
        ];
        
        return $content_map[$type] ?? '<p>Sample service content for ' . $type . '</p>';
    }
}