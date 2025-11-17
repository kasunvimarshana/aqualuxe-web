<?php
/**
 * Demo Content Importer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Demo_Importer {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('wp_ajax_aqualuxe_import_demo', [$this, 'ajax_import_demo']);
        add_action('wp_ajax_aqualuxe_reset_data', [$this, 'ajax_reset_data']);
        add_action('wp_ajax_aqualuxe_get_import_progress', [$this, 'ajax_get_import_progress']);
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            esc_html__('Demo Import', 'aqualuxe'),
            esc_html__('Demo Import', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-import',
            [$this, 'admin_page']
        );
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('AquaLuxe Demo Import', 'aqualuxe'); ?></h1>
            
            <div class="aqualuxe-demo-import">
                <div class="import-sections">
                    
                    <!-- Quick Import -->
                    <div class="import-section">
                        <h2><?php esc_html_e('Quick Import', 'aqualuxe'); ?></h2>
                        <p><?php esc_html_e('Import complete demo content with one click. This includes pages, posts, products, media, and theme settings.', 'aqualuxe'); ?></p>
                        
                        <div class="import-options">
                            <label>
                                <input type="checkbox" name="import_content" checked>
                                <?php esc_html_e('Import Content (Pages, Posts, Services)', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="import_products" checked>
                                <?php esc_html_e('Import WooCommerce Products', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="import_media" checked>
                                <?php esc_html_e('Import Media Files', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="import_settings" checked>
                                <?php esc_html_e('Import Theme Settings', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="import_menus" checked>
                                <?php esc_html_e('Import Navigation Menus', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="import_widgets" checked>
                                <?php esc_html_e('Import Widgets', 'aqualuxe'); ?>
                            </label>
                        </div>
                        
                        <button type="button" class="button button-primary button-large" id="import-demo">
                            <?php esc_html_e('Import Demo Content', 'aqualuxe'); ?>
                        </button>
                    </div>
                    
                    <!-- Reset Data -->
                    <div class="import-section reset-section">
                        <h2><?php esc_html_e('Reset Data', 'aqualuxe'); ?></h2>
                        <p class="description"><?php esc_html_e('Warning: This will permanently delete all content and reset your site to a clean state.', 'aqualuxe'); ?></p>
                        
                        <div class="reset-options">
                            <label>
                                <input type="checkbox" name="reset_posts">
                                <?php esc_html_e('Delete all posts and pages', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="reset_products">
                                <?php esc_html_e('Delete all products', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="reset_media">
                                <?php esc_html_e('Delete all media files', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="reset_settings">
                                <?php esc_html_e('Reset theme settings', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="reset_menus">
                                <?php esc_html_e('Delete navigation menus', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="reset_widgets">
                                <?php esc_html_e('Reset widgets', 'aqualuxe'); ?>
                            </label>
                        </div>
                        
                        <button type="button" class="button button-secondary" id="reset-data">
                            <?php esc_html_e('Reset Data', 'aqualuxe'); ?>
                        </button>
                    </div>
                    
                </div>
                
                <!-- Progress -->
                <div class="import-progress" id="import-progress" style="display: none;">
                    <h3><?php esc_html_e('Import Progress', 'aqualuxe'); ?></h3>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 0%"></div>
                    </div>
                    <div class="progress-text">
                        <span class="current-step"></span>
                        <span class="progress-percentage">0%</span>
                    </div>
                    <div class="progress-log"></div>
                </div>
                
            </div>
        </div>
        
        <style>
        .aqualuxe-demo-import {
            max-width: 800px;
        }
        
        .import-section {
            background: #fff;
            padding: 20px;
            margin: 20px 0;
            border: 1px solid #ccd0d4;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        
        .import-section h2 {
            margin-top: 0;
        }
        
        .import-options label,
        .reset-options label {
            display: block;
            margin: 10px 0;
        }
        
        .import-options input,
        .reset-options input {
            margin-right: 8px;
        }
        
        .reset-section {
            border-color: #dc3232;
        }
        
        .reset-section h2 {
            color: #dc3232;
        }
        
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #f1f1f1;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        
        .progress-fill {
            height: 100%;
            background: #00a32a;
            transition: width 0.3s ease;
        }
        
        .progress-text {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
        }
        
        .progress-log {
            background: #f8f9fa;
            border: 1px solid #e1e5e9;
            padding: 10px;
            max-height: 200px;
            overflow-y: auto;
            font-family: monospace;
            font-size: 12px;
            margin-top: 10px;
        }
        
        .progress-log .log-entry {
            margin: 2px 0;
        }
        
        .progress-log .log-success {
            color: #00a32a;
        }
        
        .progress-log .log-error {
            color: #dc3232;
        }
        
        .progress-log .log-info {
            color: #0073aa;
        }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            var importInProgress = false;
            
            $('#import-demo').on('click', function() {
                if (importInProgress) return;
                
                var options = {};
                $('.import-options input:checked').each(function() {
                    options[$(this).attr('name')] = true;
                });
                
                startImport(options);
            });
            
            $('#reset-data').on('click', function() {
                if (importInProgress) return;
                
                if (!confirm(aqualuxe_admin.strings.confirm_reset)) {
                    return;
                }
                
                var options = {};
                $('.reset-options input:checked').each(function() {
                    options[$(this).attr('name')] = true;
                });
                
                startReset(options);
            });
            
            function startImport(options) {
                importInProgress = true;
                $('#import-progress').show();
                updateProgress(0, 'Starting import...');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_import_demo',
                        nonce: aqualuxe_admin.nonce,
                        options: options
                    },
                    success: function(response) {
                        if (response.success) {
                            pollProgress();
                        } else {
                            logMessage('Import failed: ' + response.data, 'error');
                            importInProgress = false;
                        }
                    },
                    error: function() {
                        logMessage('Import failed: Server error', 'error');
                        importInProgress = false;
                    }
                });
            }
            
            function startReset(options) {
                importInProgress = true;
                $('#import-progress').show();
                updateProgress(0, 'Starting reset...');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_reset_data',
                        nonce: aqualuxe_admin.nonce,
                        options: options
                    },
                    success: function(response) {
                        if (response.success) {
                            updateProgress(100, 'Reset completed');
                            logMessage('Data reset completed successfully', 'success');
                        } else {
                            logMessage('Reset failed: ' + response.data, 'error');
                        }
                        importInProgress = false;
                    },
                    error: function() {
                        logMessage('Reset failed: Server error', 'error');
                        importInProgress = false;
                    }
                });
            }
            
            function pollProgress() {
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_get_import_progress',
                        nonce: aqualuxe_admin.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            var data = response.data;
                            updateProgress(data.progress, data.step);
                            
                            if (data.messages) {
                                data.messages.forEach(function(message) {
                                    logMessage(message.text, message.type);
                                });
                            }
                            
                            if (data.progress < 100) {
                                setTimeout(pollProgress, 1000);
                            } else {
                                logMessage('Import completed successfully!', 'success');
                                importInProgress = false;
                            }
                        } else {
                            logMessage('Failed to get progress', 'error');
                            importInProgress = false;
                        }
                    },
                    error: function() {
                        setTimeout(pollProgress, 2000);
                    }
                });
            }
            
            function updateProgress(percentage, step) {
                $('.progress-fill').css('width', percentage + '%');
                $('.progress-percentage').text(percentage + '%');
                $('.current-step').text(step);
            }
            
            function logMessage(message, type) {
                type = type || 'info';
                var logClass = 'log-' + type;
                var timestamp = new Date().toLocaleTimeString();
                
                $('.progress-log').append(
                    '<div class="log-entry ' + logClass + '">[' + timestamp + '] ' + message + '</div>'
                );
                
                $('.progress-log').scrollTop($('.progress-log')[0].scrollHeight);
            }
        });
        </script>
        <?php
    }
    
    /**
     * AJAX import demo
     */
    public function ajax_import_demo() {
        check_ajax_referer('aqualuxe_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'aqualuxe'));
        }
        
        $options = isset($_POST['options']) ? $_POST['options'] : [];
        
        // Start background import process
        $this->start_import_process($options);
        
        wp_send_json_success(['message' => 'Import started']);
    }
    
    /**
     * AJAX reset data
     */
    public function ajax_reset_data() {
        check_ajax_referer('aqualuxe_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'aqualuxe'));
        }
        
        $options = isset($_POST['options']) ? $_POST['options'] : [];
        
        try {
            $this->reset_site_data($options);
            wp_send_json_success(['message' => 'Reset completed']);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }
    
    /**
     * AJAX get import progress
     */
    public function ajax_get_import_progress() {
        check_ajax_referer('aqualuxe_admin_nonce', 'nonce');
        
        $progress = get_transient('aqualuxe_import_progress');
        
        if ($progress === false) {
            $progress = [
                'progress' => 0,
                'step' => 'Not started',
                'messages' => []
            ];
        }
        
        wp_send_json_success($progress);
    }
    
    /**
     * Start import process
     */
    private function start_import_process($options) {
        // Clear previous progress
        delete_transient('aqualuxe_import_progress');
        
        // Set initial progress
        $this->update_progress(0, 'Initializing import...');
        
        // Import content in background
        wp_schedule_single_event(time(), 'aqualuxe_run_import', [$options]);
        
        if (!wp_next_scheduled('aqualuxe_run_import')) {
            add_action('aqualuxe_run_import', [$this, 'run_import_process']);
            $this->run_import_process($options);
        }
    }
    
    /**
     * Run import process
     */
    public function run_import_process($options) {
        try {
            $total_steps = count(array_filter($options));
            $current_step = 0;
            
            // Import content
            if (!empty($options['import_content'])) {
                $current_step++;
                $progress = ($current_step / $total_steps) * 25;
                $this->update_progress($progress, 'Importing content...');
                $this->import_content();
            }
            
            // Import products
            if (!empty($options['import_products'])) {
                $current_step++;
                $progress = ($current_step / $total_steps) * 50;
                $this->update_progress($progress, 'Importing products...');
                $this->import_products();
            }
            
            // Import media
            if (!empty($options['import_media'])) {
                $current_step++;
                $progress = ($current_step / $total_steps) * 70;
                $this->update_progress($progress, 'Importing media...');
                $this->import_media();
            }
            
            // Import settings
            if (!empty($options['import_settings'])) {
                $current_step++;
                $progress = ($current_step / $total_steps) * 85;
                $this->update_progress($progress, 'Importing settings...');
                $this->import_settings();
            }
            
            // Import menus
            if (!empty($options['import_menus'])) {
                $current_step++;
                $progress = ($current_step / $total_steps) * 95;
                $this->update_progress($progress, 'Importing menus...');
                $this->import_menus();
            }
            
            // Import widgets
            if (!empty($options['import_widgets'])) {
                $current_step++;
                $progress = 100;
                $this->update_progress($progress, 'Importing widgets...');
                $this->import_widgets();
            }
            
            $this->update_progress(100, 'Import completed!', 'Import completed successfully!', 'success');
            
        } catch (Exception $e) {
            $this->update_progress(0, 'Import failed', $e->getMessage(), 'error');
        }
    }
    
    /**
     * Update progress
     */
    private function update_progress($progress, $step, $message = '', $type = 'info') {
        $data = get_transient('aqualuxe_import_progress') ?: [
            'progress' => 0,
            'step' => '',
            'messages' => []
        ];
        
        $data['progress'] = $progress;
        $data['step'] = $step;
        
        if ($message) {
            $data['messages'][] = [
                'text' => $message,
                'type' => $type,
                'time' => time()
            ];
            
            // Keep only last 50 messages
            $data['messages'] = array_slice($data['messages'], -50);
        }
        
        set_transient('aqualuxe_import_progress', $data, HOUR_IN_SECONDS);
    }
    
    /**
     * Import content
     */
    private function import_content() {
        $this->log_message('Starting content import...');
        
        // Import pages
        $this->import_pages();
        
        // Import posts
        $this->import_posts();
        
        // Import services
        $this->import_services();
        
        // Import events
        $this->import_events();
        
        // Import portfolio
        $this->import_portfolio();
        
        // Import testimonials
        $this->import_testimonials();
        
        $this->log_message('Content import completed', 'success');
    }
    
    /**
     * Import pages
     */
    private function import_pages() {
        $pages = [
            [
                'title' => 'Home',
                'content' => $this->get_home_content(),
                'template' => 'page-home.php',
                'is_front_page' => true,
            ],
            [
                'title' => 'About Us',
                'content' => $this->get_about_content(),
                'slug' => 'about',
            ],
            [
                'title' => 'Services',
                'content' => $this->get_services_content(),
                'slug' => 'services',
            ],
            [
                'title' => 'Shop',
                'content' => $this->get_shop_content(),
                'slug' => 'shop',
                'is_shop_page' => true,
            ],
            [
                'title' => 'Contact Us',
                'content' => $this->get_contact_content(),
                'slug' => 'contact',
            ],
            [
                'title' => 'Privacy Policy',
                'content' => $this->get_privacy_content(),
                'slug' => 'privacy-policy',
            ],
            [
                'title' => 'Terms & Conditions',
                'content' => $this->get_terms_content(),
                'slug' => 'terms-conditions',
            ],
            [
                'title' => 'Shipping & Returns',
                'content' => $this->get_shipping_content(),
                'slug' => 'shipping-returns',
            ],
            [
                'title' => 'FAQ',
                'content' => $this->get_faq_content(),
                'slug' => 'faq',
            ],
        ];
        
        foreach ($pages as $page_data) {
            $this->create_page($page_data);
        }
    }
    
    /**
     * Create page
     */
    private function create_page($page_data) {
        $page_id = wp_insert_post([
            'post_title' => $page_data['title'],
            'post_content' => $page_data['content'],
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => isset($page_data['slug']) ? $page_data['slug'] : sanitize_title($page_data['title']),
        ]);
        
        if ($page_id && !is_wp_error($page_id)) {
            // Set as front page
            if (!empty($page_data['is_front_page'])) {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $page_id);
            }
            
            // Set as shop page
            if (!empty($page_data['is_shop_page']) && class_exists('WooCommerce')) {
                update_option('woocommerce_shop_page_id', $page_id);
            }
            
            // Set page template
            if (!empty($page_data['template'])) {
                update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            }
            
            $this->log_message("Created page: {$page_data['title']}", 'success');
        } else {
            $this->log_message("Failed to create page: {$page_data['title']}", 'error');
        }
        
        return $page_id;
    }
    
    /**
     * Import products
     */
    private function import_products() {
        if (!class_exists('WooCommerce')) {
            $this->log_message('WooCommerce not active, skipping products', 'info');
            return;
        }
        
        $this->log_message('Starting products import...');
        
        // Create product categories
        $this->create_product_categories();
        
        // Create products
        $products = $this->get_sample_products();
        
        foreach ($products as $product_data) {
            $this->create_product($product_data);
        }
        
        $this->log_message('Products import completed', 'success');
    }
    
    /**
     * Create product categories
     */
    private function create_product_categories() {
        $categories = [
            'Fish' => [
                'Freshwater Fish',
                'Marine Fish',
                'Rare Fish',
                'Tropical Fish',
            ],
            'Plants' => [
                'Aquatic Plants',
                'Mosses',
                'Floating Plants',
                'Background Plants',
            ],
            'Equipment' => [
                'Filters',
                'Lighting',
                'Heaters',
                'Air Pumps',
            ],
            'Supplies' => [
                'Fish Food',
                'Water Conditioners',
                'Decorations',
                'Substrate',
            ],
        ];
        
        foreach ($categories as $parent => $children) {
            $parent_term = wp_insert_term($parent, 'product_cat');
            
            if (!is_wp_error($parent_term)) {
                $parent_id = $parent_term['term_id'];
                
                foreach ($children as $child) {
                    wp_insert_term($child, 'product_cat', ['parent' => $parent_id]);
                }
            }
        }
    }
    
    /**
     * Get sample products
     */
    private function get_sample_products() {
        return [
            [
                'name' => 'Premium Betta Fish',
                'description' => 'Beautiful, healthy Betta fish with vibrant colors. Perfect for beginners and experienced aquarists alike.',
                'price' => 24.99,
                'category' => 'Freshwater Fish',
                'featured' => true,
                'stock' => 15,
                'images' => ['betta-fish.jpg'],
            ],
            [
                'name' => 'LED Aquarium Light',
                'description' => 'Energy-efficient LED lighting system perfect for planted aquariums. Full spectrum lighting promotes healthy plant growth.',
                'price' => 89.99,
                'category' => 'Lighting',
                'stock' => 8,
                'images' => ['led-light.jpg'],
            ],
            [
                'name' => 'Aquascaping Plant Bundle',
                'description' => 'Complete bundle of aquatic plants for creating stunning aquascapes. Includes foreground, midground, and background plants.',
                'price' => 34.99,
                'category' => 'Aquatic Plants',
                'featured' => true,
                'stock' => 12,
                'images' => ['plant-bundle.jpg'],
            ],
            [
                'name' => 'Premium Fish Food',
                'description' => 'High-quality nutrition for tropical fish. Contains essential vitamins and minerals for optimal health and vibrant colors.',
                'price' => 14.99,
                'category' => 'Fish Food',
                'stock' => 25,
                'images' => ['fish-food.jpg'],
            ],
            [
                'name' => 'Crystal Clear Water Conditioner',
                'description' => 'Advanced water conditioner that removes chlorine, chloramines, and heavy metals while adding beneficial bacteria.',
                'price' => 18.99,
                'category' => 'Water Conditioners',
                'stock' => 20,
                'images' => ['water-conditioner.jpg'],
            ],
        ];
    }
    
    /**
     * Create product
     */
    private function create_product($product_data) {
        $product = new WC_Product_Simple();
        
        $product->set_name($product_data['name']);
        $product->set_description($product_data['description']);
        $product->set_short_description(wp_trim_words($product_data['description'], 20));
        $product->set_regular_price($product_data['price']);
        $product->set_stock_quantity($product_data['stock']);
        $product->set_manage_stock(true);
        $product->set_stock_status('instock');
        $product->set_featured(!empty($product_data['featured']));
        
        $product_id = $product->save();
        
        if ($product_id) {
            // Set category
            if (!empty($product_data['category'])) {
                $term = get_term_by('name', $product_data['category'], 'product_cat');
                if ($term) {
                    wp_set_object_terms($product_id, $term->term_id, 'product_cat');
                }
            }
            
            $this->log_message("Created product: {$product_data['name']}", 'success');
        } else {
            $this->log_message("Failed to create product: {$product_data['name']}", 'error');
        }
    }
    
    /**
     * Import media
     */
    private function import_media() {
        $this->log_message('Starting media import...');
        
        // Create placeholder images for demo content
        $this->create_placeholder_images();
        
        $this->log_message('Media import completed', 'success');
    }
    
    /**
     * Create placeholder images
     */
    private function create_placeholder_images() {
        $images = [
            'hero-aquarium.jpg' => [1920, 1080, 'Hero aquarium image'],
            'betta-fish.jpg' => [800, 600, 'Beautiful Betta fish'],
            'led-light.jpg' => [800, 600, 'LED aquarium light'],
            'plant-bundle.jpg' => [800, 600, 'Aquatic plant bundle'],
            'fish-food.jpg' => [800, 600, 'Premium fish food'],
            'water-conditioner.jpg' => [800, 600, 'Water conditioner'],
        ];
        
        foreach ($images as $filename => $data) {
            $this->create_placeholder_image($filename, $data[0], $data[1], $data[2]);
        }
    }
    
    /**
     * Create placeholder image
     */
    private function create_placeholder_image($filename, $width, $height, $alt_text) {
        // Create a simple colored rectangle as placeholder
        $image = imagecreate($width, $height);
        $bg_color = imagecolorallocate($image, 20, 184, 166); // Aqua color
        $text_color = imagecolorallocate($image, 255, 255, 255);
        
        // Add text
        $text = $alt_text;
        $font_size = min($width / 20, $height / 20);
        
        if (function_exists('imagettftext')) {
            // Use default font if available
            imagestring($image, 5, $width / 2 - 50, $height / 2 - 10, $text, $text_color);
        }
        
        // Save image
        $upload_dir = wp_upload_dir();
        $file_path = $upload_dir['path'] . '/' . $filename;
        
        imagejpeg($image, $file_path, 90);
        imagedestroy($image);
        
        // Create attachment
        $attachment = [
            'guid' => $upload_dir['url'] . '/' . $filename,
            'post_mime_type' => 'image/jpeg',
            'post_title' => $alt_text,
            'post_content' => '',
            'post_status' => 'inherit'
        ];
        
        $attach_id = wp_insert_attachment($attachment, $file_path);
        
        if ($attach_id) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
            wp_update_attachment_metadata($attach_id, $attach_data);
            
            update_post_meta($attach_id, '_wp_attachment_image_alt', $alt_text);
        }
        
        return $attach_id;
    }
    
    /**
     * Import settings
     */
    private function import_settings() {
        $this->log_message('Starting settings import...');
        
        // Theme customizer settings
        $customizer_settings = [
            'aqualuxe_primary_color' => '#14b8a6',
            'aqualuxe_secondary_color' => '#a855f7',
            'aqualuxe_accent_color' => '#0ea5e9',
            'aqualuxe_hero_title' => 'Bringing elegance to aquatic life',
            'aqualuxe_hero_subtitle' => 'Discover premium fish, plants, and aquarium equipment for enthusiasts worldwide',
            'aqualuxe_footer_text' => '© 2024 AquaLuxe. All rights reserved.',
        ];
        
        foreach ($customizer_settings as $setting => $value) {
            set_theme_mod($setting, $value);
        }
        
        // General WordPress settings
        update_option('blogname', 'AquaLuxe');
        update_option('blogdescription', 'Premium Aquatic Solutions');
        update_option('start_of_week', 1);
        update_option('timezone_string', 'America/New_York');
        
        $this->log_message('Settings import completed', 'success');
    }
    
    /**
     * Import menus
     */
    private function import_menus() {
        $this->log_message('Starting menus import...');
        
        // Create primary menu
        $primary_menu_id = wp_create_nav_menu('Primary Menu');
        
        if (!is_wp_error($primary_menu_id)) {
            // Add menu items
            $menu_items = [
                ['title' => 'Home', 'url' => home_url('/')],
                ['title' => 'About', 'url' => home_url('/about/')],
                ['title' => 'Services', 'url' => home_url('/services/')],
                ['title' => 'Shop', 'url' => home_url('/shop/')],
                ['title' => 'Blog', 'url' => home_url('/blog/')],
                ['title' => 'Contact', 'url' => home_url('/contact/')],
            ];
            
            foreach ($menu_items as $item) {
                wp_update_nav_menu_item($primary_menu_id, 0, [
                    'menu-item-title' => $item['title'],
                    'menu-item-url' => $item['url'],
                    'menu-item-status' => 'publish',
                    'menu-item-type' => 'custom',
                ]);
            }
            
            // Assign to primary location
            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary'] = $primary_menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
        
        $this->log_message('Menus import completed', 'success');
    }
    
    /**
     * Import widgets
     */
    private function import_widgets() {
        $this->log_message('Starting widgets import...');
        
        // Sidebar widgets
        $sidebar_widgets = [
            'search' => ['title' => 'Search'],
            'recent-posts' => ['title' => 'Recent Posts', 'number' => 5],
            'categories' => ['title' => 'Categories'],
        ];
        
        update_option('widget_search', [2 => $sidebar_widgets['search']]);
        update_option('widget_recent-posts', [2 => $sidebar_widgets['recent-posts']]);
        update_option('widget_categories', [2 => $sidebar_widgets['categories']]);
        
        // Footer widgets
        $footer_widgets = [
            'text' => [
                2 => [
                    'title' => 'About AquaLuxe',
                    'text' => 'We are passionate about bringing elegance to aquatic life through premium fish, plants, and equipment.',
                ],
            ],
        ];
        
        update_option('widget_text', $footer_widgets['text']);
        
        // Assign widgets to sidebars
        $sidebars_widgets = [
            'sidebar-1' => ['search-2', 'recent-posts-2', 'categories-2'],
            'footer-1' => ['text-2'],
        ];
        
        update_option('sidebars_widgets', $sidebars_widgets);
        
        $this->log_message('Widgets import completed', 'success');
    }
    
    /**
     * Import posts
     */
    private function import_posts() {
        $posts = [
            [
                'title' => 'Ultimate Guide to Aquascaping',
                'content' => $this->get_sample_post_content('aquascaping'),
                'category' => 'Aquascaping',
                'featured' => true,
            ],
            [
                'title' => 'Caring for Tropical Fish',
                'content' => $this->get_sample_post_content('fish-care'),
                'category' => 'Fish Care',
            ],
            [
                'title' => 'Setting Up Your First Aquarium',
                'content' => $this->get_sample_post_content('beginner-guide'),
                'category' => 'Beginner Guides',
            ],
        ];
        
        foreach ($posts as $post_data) {
            $this->create_post($post_data);
        }
    }
    
    /**
     * Import services
     */
    private function import_services() {
        $services = [
            [
                'title' => 'Aquarium Design & Installation',
                'content' => $this->get_service_content('design'),
                'price' => '$299',
                'duration' => '2-4 hours',
            ],
            [
                'title' => 'Maintenance Services',
                'content' => $this->get_service_content('maintenance'),
                'price' => '$89',
                'duration' => '1 hour',
            ],
            [
                'title' => 'Fish Health Consultation',
                'content' => $this->get_service_content('consultation'),
                'price' => '$49',
                'duration' => '30 minutes',
            ],
        ];
        
        foreach ($services as $service_data) {
            $this->create_service($service_data);
        }
    }
    
    /**
     * Import events
     */
    private function import_events() {
        $events = [
            [
                'title' => 'Aquascaping Workshop',
                'content' => $this->get_event_content('workshop'),
                'date' => date('Y-m-d', strtotime('+1 month')),
                'price' => '$79',
            ],
            [
                'title' => 'Fish Photography Session',
                'content' => $this->get_event_content('photography'),
                'date' => date('Y-m-d', strtotime('+2 weeks')),
                'price' => '$129',
            ],
        ];
        
        foreach ($events as $event_data) {
            $this->create_event($event_data);
        }
    }
    
    /**
     * Import portfolio
     */
    private function import_portfolio() {
        $portfolio_items = [
            [
                'title' => 'Luxury Hotel Aquarium',
                'content' => $this->get_portfolio_content('hotel'),
                'category' => 'Commercial',
            ],
            [
                'title' => 'Home Reef Tank',
                'content' => $this->get_portfolio_content('home'),
                'category' => 'Residential',
            ],
        ];
        
        foreach ($portfolio_items as $item_data) {
            $this->create_portfolio_item($item_data);
        }
    }
    
    /**
     * Import testimonials
     */
    private function import_testimonials() {
        $testimonials = [
            [
                'title' => 'Sarah Johnson',
                'content' => '"AquaLuxe transformed my living room with a stunning aquarium. The fish are healthy and the design is breathtaking!"',
                'rating' => 5,
                'location' => 'New York, NY',
            ],
            [
                'title' => 'Michael Chen',
                'content' => '"Excellent service and high-quality fish. My aquascaping project exceeded all expectations."',
                'rating' => 5,
                'location' => 'Los Angeles, CA',
            ],
        ];
        
        foreach ($testimonials as $testimonial_data) {
            $this->create_testimonial($testimonial_data);
        }
    }
    
    /**
     * Create post
     */
    private function create_post($post_data) {
        $post_id = wp_insert_post([
            'post_title' => $post_data['title'],
            'post_content' => $post_data['content'],
            'post_status' => 'publish',
            'post_type' => 'post',
        ]);
        
        if ($post_id && !is_wp_error($post_id)) {
            // Set category
            if (!empty($post_data['category'])) {
                $category_id = wp_create_category($post_data['category']);
                wp_set_post_categories($post_id, [$category_id]);
            }
            
            $this->log_message("Created post: {$post_data['title']}", 'success');
        }
    }
    
    /**
     * Create service
     */
    private function create_service($service_data) {
        $service_id = wp_insert_post([
            'post_title' => $service_data['title'],
            'post_content' => $service_data['content'],
            'post_status' => 'publish',
            'post_type' => 'aql_service',
        ]);
        
        if ($service_id && !is_wp_error($service_id)) {
            update_post_meta($service_id, '_service_price', $service_data['price']);
            update_post_meta($service_id, '_service_duration', $service_data['duration']);
            
            $this->log_message("Created service: {$service_data['title']}", 'success');
        }
    }
    
    /**
     * Create event
     */
    private function create_event($event_data) {
        $event_id = wp_insert_post([
            'post_title' => $event_data['title'],
            'post_content' => $event_data['content'],
            'post_status' => 'publish',
            'post_type' => 'aql_event',
        ]);
        
        if ($event_id && !is_wp_error($event_id)) {
            update_post_meta($event_id, '_event_date', $event_data['date']);
            update_post_meta($event_id, '_event_price', $event_data['price']);
            
            $this->log_message("Created event: {$event_data['title']}", 'success');
        }
    }
    
    /**
     * Create portfolio item
     */
    private function create_portfolio_item($item_data) {
        $item_id = wp_insert_post([
            'post_title' => $item_data['title'],
            'post_content' => $item_data['content'],
            'post_status' => 'publish',
            'post_type' => 'aql_portfolio',
        ]);
        
        if ($item_id && !is_wp_error($item_id)) {
            // Set category
            if (!empty($item_data['category'])) {
                $term = wp_insert_term($item_data['category'], 'portfolio_category');
                if (!is_wp_error($term)) {
                    wp_set_object_terms($item_id, $term['term_id'], 'portfolio_category');
                }
            }
            
            $this->log_message("Created portfolio item: {$item_data['title']}", 'success');
        }
    }
    
    /**
     * Create testimonial
     */
    private function create_testimonial($testimonial_data) {
        $testimonial_id = wp_insert_post([
            'post_title' => $testimonial_data['title'],
            'post_content' => $testimonial_data['content'],
            'post_status' => 'publish',
            'post_type' => 'aql_testimonial',
        ]);
        
        if ($testimonial_id && !is_wp_error($testimonial_id)) {
            update_post_meta($testimonial_id, '_testimonial_rating', $testimonial_data['rating']);
            update_post_meta($testimonial_id, '_testimonial_location', $testimonial_data['location']);
            
            $this->log_message("Created testimonial: {$testimonial_data['title']}", 'success');
        }
    }
    
    /**
     * Reset site data
     */
    private function reset_site_data($options) {
        global $wpdb;
        
        if (!empty($options['reset_posts'])) {
            // Delete posts and pages
            $posts = get_posts([
                'post_type' => ['post', 'page', 'aql_service', 'aql_event', 'aql_portfolio', 'aql_testimonial'],
                'numberposts' => -1,
                'post_status' => 'any',
            ]);
            
            foreach ($posts as $post) {
                wp_delete_post($post->ID, true);
            }
        }
        
        if (!empty($options['reset_products']) && class_exists('WooCommerce')) {
            // Delete products
            $products = get_posts([
                'post_type' => 'product',
                'numberposts' => -1,
                'post_status' => 'any',
            ]);
            
            foreach ($products as $product) {
                wp_delete_post($product->ID, true);
            }
            
            // Delete product categories
            $terms = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
            foreach ($terms as $term) {
                wp_delete_term($term->term_id, 'product_cat');
            }
        }
        
        if (!empty($options['reset_media'])) {
            // Delete media files
            $attachments = get_posts([
                'post_type' => 'attachment',
                'numberposts' => -1,
                'post_status' => 'any',
            ]);
            
            foreach ($attachments as $attachment) {
                wp_delete_attachment($attachment->ID, true);
            }
        }
        
        if (!empty($options['reset_settings'])) {
            // Reset theme mods
            remove_theme_mods();
        }
        
        if (!empty($options['reset_menus'])) {
            // Delete navigation menus
            $menus = wp_get_nav_menus();
            foreach ($menus as $menu) {
                wp_delete_nav_menu($menu->term_id);
            }
        }
        
        if (!empty($options['reset_widgets'])) {
            // Reset widgets
            update_option('sidebars_widgets', []);
        }
    }
    
    /**
     * Log message
     */
    private function log_message($message, $type = 'info') {
        $this->update_progress(null, null, $message, $type);
    }
    
    /**
     * Get content templates
     */
    private function get_home_content() {
        return '<!-- wp:group {"className":"hero-section"} -->
<div class="wp-block-group hero-section">
    <h1>Bringing elegance to aquatic life – globally</h1>
    <p>Discover premium fish, plants, and aquarium equipment for enthusiasts worldwide</p>
    <a href="/shop" class="button">Shop Now</a>
</div>
<!-- /wp:group -->

<!-- wp:group {"className":"featured-products"} -->
<div class="wp-block-group featured-products">
    <h2>Featured Products</h2>
    [featured_products limit="4"]
</div>
<!-- /wp:group -->';
    }
    
    private function get_about_content() {
        return '<h2>About AquaLuxe</h2>
<p>At AquaLuxe, we are passionate about bringing elegance to aquatic life through premium fish, plants, and equipment. Our mission is to provide aquarium enthusiasts with the highest quality products and services to create stunning underwater environments.</p>

<h3>Our Story</h3>
<p>Founded by aquarium enthusiasts, AquaLuxe has grown from a small local business to a trusted international supplier of premium aquatic products. We work directly with breeders and manufacturers to ensure the health and quality of our livestock and equipment.</p>

<h3>Our Values</h3>
<ul>
<li><strong>Quality:</strong> We source only the finest fish and equipment</li>
<li><strong>Sustainability:</strong> We promote responsible aquaculture practices</li>
<li><strong>Service:</strong> Our experts are here to help you succeed</li>
<li><strong>Innovation:</strong> We embrace new technologies and techniques</li>
</ul>';
    }
    
    private function get_services_content() {
        return '<h2>Our Services</h2>
<p>Professional aquarium services for homes, businesses, and institutions.</p>

[services_grid]';
    }
    
    private function get_shop_content() {
        return '<h2>Premium Aquatic Products</h2>
<p>Browse our extensive collection of fish, plants, equipment, and supplies.</p>

[product_categories]';
    }
    
    private function get_contact_content() {
        return '<h2>Contact Us</h2>
<p>Get in touch with our aquatic experts for personalized advice and support.</p>

<div class="contact-info">
    <h3>Store Location</h3>
    <p>123 Aquarium Lane<br>
    Ocean City, OC 12345<br>
    Phone: (555) 123-4567<br>
    Email: info@aqualuxe.com</p>
</div>

[contact-form-7]';
    }
    
    private function get_privacy_content() {
        return '<h2>Privacy Policy</h2>
<p>Last updated: ' . date('F j, Y') . '</p>

<h3>Information We Collect</h3>
<p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support.</p>

<h3>How We Use Your Information</h3>
<p>We use the information we collect to provide, maintain, and improve our services, process transactions, and communicate with you.</p>

<h3>Information Sharing</h3>
<p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.</p>';
    }
    
    private function get_terms_content() {
        return '<h2>Terms & Conditions</h2>
<p>Last updated: ' . date('F j, Y') . '</p>

<h3>Acceptance of Terms</h3>
<p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p>

<h3>Products and Services</h3>
<p>All products and services are subject to availability. We reserve the right to discontinue any product or service at any time.</p>

<h3>Pricing and Payment</h3>
<p>All prices are in USD and are subject to change without notice. Payment is due at the time of purchase.</p>';
    }
    
    private function get_shipping_content() {
        return '<h2>Shipping & Returns</h2>

<h3>Shipping Information</h3>
<p>We offer worldwide shipping for our products. Live fish shipments are sent via overnight delivery to ensure arrival in optimal condition.</p>

<h3>Live Arrival Guarantee</h3>
<p>We guarantee live arrival of all fish. If any fish arrive deceased, please contact us within 2 hours of delivery with photos for a full refund or replacement.</p>

<h3>Return Policy</h3>
<p>Non-living items may be returned within 30 days of purchase in original condition. Live fish and plants cannot be returned due to biosecurity regulations.</p>';
    }
    
    private function get_faq_content() {
        return '<h2>Frequently Asked Questions</h2>

<h3>Do you ship internationally?</h3>
<p>Yes, we ship to most countries worldwide. International shipments require special permits and may take 5-10 business days.</p>

<h3>How do you ensure fish health during shipping?</h3>
<p>All fish are quarantined for 14 days before shipping. We use insulated boxes with oxygen and temperature control for safe transport.</p>

<h3>What if I need help setting up my aquarium?</h3>
<p>Our expert team offers consultation services and can help with aquarium design, setup, and maintenance. Contact us to schedule a consultation.</p>

<h3>Do you offer bulk pricing for retailers?</h3>
<p>Yes, we offer wholesale pricing for qualified retailers. Please contact us for more information about our wholesale program.</p>';
    }
    
    private function get_sample_post_content($type) {
        switch ($type) {
            case 'aquascaping':
                return '<h2>Creating Beautiful Underwater Landscapes</h2>
<p>Aquascaping is the art of creating beautiful underwater landscapes in aquariums. This comprehensive guide will teach you the fundamentals of aquascaping design and technique.</p>

<h3>Basic Design Principles</h3>
<p>The key to successful aquascaping lies in understanding basic design principles such as the rule of thirds, focal points, and natural proportions.</p>

<h3>Plant Selection</h3>
<p>Choose plants that complement each other in terms of color, texture, and growth patterns. Consider foreground, midground, and background plants for depth.</p>';
                
            case 'fish-care':
                return '<h2>Essential Fish Care Tips</h2>
<p>Proper fish care is essential for maintaining healthy, vibrant aquatic pets. This guide covers the basics of tropical fish care.</p>

<h3>Water Quality</h3>
<p>Maintaining proper water parameters is crucial for fish health. Regular testing and water changes are essential.</p>

<h3>Feeding</h3>
<p>Feed your fish a varied diet appropriate for their species. Overfeeding is one of the most common mistakes in fishkeeping.</p>';
                
            default:
                return '<h2>Setting Up Your First Aquarium</h2>
<p>Starting your first aquarium can be exciting but overwhelming. This guide will help you set up a successful aquarium from the beginning.</p>

<h3>Choosing the Right Tank</h3>
<p>Select an appropriately sized tank for your space and intended fish. Larger tanks are generally easier to maintain.</p>

<h3>Essential Equipment</h3>
<p>You\'ll need a filter, heater, lighting, and testing supplies to maintain a healthy aquatic environment.</p>';
        }
    }
    
    private function get_service_content($type) {
        switch ($type) {
            case 'design':
                return 'Professional aquarium design and installation services for homes and businesses. Our experts will create a custom aquatic environment tailored to your space and preferences.';
                
            case 'maintenance':
                return 'Regular maintenance services to keep your aquarium clean, healthy, and beautiful. Includes water testing, cleaning, and fish health checks.';
                
            default:
                return 'Expert consultation on fish health, aquarium problems, and general aquatic care. Get personalized advice from our experienced team.';
        }
    }
    
    private function get_event_content($type) {
        switch ($type) {
            case 'workshop':
                return 'Learn the art of aquascaping in this hands-on workshop. Create your own underwater landscape with guidance from our experts.';
                
            default:
                return 'Capture stunning photos of your aquatic pets with professional photography techniques. Learn lighting, composition, and editing skills.';
        }
    }
    
    private function get_portfolio_content($type) {
        switch ($type) {
            case 'hotel':
                return 'A stunning 500-gallon reef aquarium installed in a luxury hotel lobby. Features live coral, tropical fish, and advanced filtration systems.';
                
            default:
                return 'Custom home aquarium featuring a beautiful coral reef ecosystem with rare fish species and precision lighting.';
        }
    }
}

// Initialize
new AquaLuxe_Demo_Importer();
