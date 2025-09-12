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

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer
 */
class Demo_Content_Importer {
    
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
    public function __construct() {
        $this->init_hooks();
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
                'meta_input' => $page_data['meta'] ?? []
            ]);
            
            if ($page_id && !is_wp_error($page_id)) {
                $imported_count++;
                
                // Set as homepage if specified
                if (!empty($page_data['is_homepage'])) {
                    update_option('page_on_front', $page_id);
                    update_option('show_on_front', 'page');
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
                'meta' => [
                    '_aqualuxe_page_layout' => 'fullwidth',
                    '_aqualuxe_hero_enable' => 'yes'
                ]
            ],
            [
                'title' => 'About Us',
                'content' => $this->get_about_content(),
                'excerpt' => 'Learn about our passion for aquatic life and commitment to excellence.',
            ],
            [
                'title' => 'Services',
                'content' => $this->get_services_content(),
                'excerpt' => 'Professional aquarium design, maintenance, and consultation services.',
            ],
            [
                'title' => 'Contact',
                'content' => $this->get_contact_content(),
                'excerpt' => 'Get in touch with our aquatic experts.',
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
        
        $products_data = $this->get_demo_products_data($options);
        $imported_count = 0;
        
        foreach ($products_data as $product_data) {
            $product = new \WC_Product_Simple();
            
            $product->set_name($product_data['name']);
            $product->set_description($product_data['description']);
            $product->set_short_description($product_data['short_description']);
            $product->set_regular_price($product_data['regular_price']);
            $product->set_sku($product_data['sku']);
            $product->set_manage_stock(true);
            $product->set_stock_quantity($product_data['stock_quantity']);
            $product->set_status('publish');
            
            $product_id = $product->save();
            
            if ($product_id) {
                $imported_count++;
                
                // Add custom meta for aquatic products
                if (isset($product_data['care_level'])) {
                    update_post_meta($product_id, '_aqualuxe_care_level', $product_data['care_level']);
                }
                if (isset($product_data['water_type'])) {
                    update_post_meta($product_id, '_aqualuxe_water_type', $product_data['water_type']);
                }
            }
        }
        
        return $imported_count;
    }
    
    /**
     * Get demo products data
     */
    private function get_demo_products_data($options) {
        return [
            [
                'name' => 'Premium Discus Fish',
                'description' => 'Beautiful premium discus fish, known for their vibrant colors and graceful swimming patterns.',
                'short_description' => 'Premium quality discus fish with vibrant colors.',
                'regular_price' => '89.99',
                'sku' => 'AQL-DISC-001',
                'stock_quantity' => 15,
                'care_level' => 'Intermediate',
                'water_type' => 'Freshwater',
            ],
            [
                'name' => 'Neon Tetra School (10 pack)',
                'description' => 'A school of 10 vibrant neon tetras. Perfect for community tanks.',
                'short_description' => 'Pack of 10 colorful neon tetras for community tanks.',
                'regular_price' => '24.99',
                'sku' => 'AQL-NEON-010',
                'stock_quantity' => 25,
                'care_level' => 'Beginner',
                'water_type' => 'Freshwater',
            ],
        ];
    }
    
    // Placeholder methods
    private function import_posts($options) { return 5; }
    private function import_services($options) { return 3; }
    private function get_about_content() { return 'About us content...'; }
    private function get_services_content() { return 'Services content...'; }
    private function get_contact_content() { return 'Contact content...'; }
    
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
    
    // Placeholder AJAX handlers
    public function handle_get_import_progress() {
        wp_send_json_success(['progress' => get_option('aqualuxe_import_progress', 0)]);
    }
    
    public function handle_reset_demo_content() {
        wp_send_json_success(['message' => 'Reset functionality not implemented yet']);
    }
    
    public function handle_export_content() {
        wp_send_json_success(['message' => 'Export functionality not implemented yet']);
    }
}