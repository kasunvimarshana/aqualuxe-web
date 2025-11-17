<?php
/**
 * Advanced Demo Content Importer
 *
 * Comprehensive demo content importer with ACID transactions, rollback capabilities,
 * progressive enhancement, and batch processing
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
 * Class AdvancedDemoImporter
 *
 * Handles importing demo content with enterprise-grade features
 */
class AdvancedDemoImporter {
    
    /**
     * Single instance of the class
     *
     * @var AdvancedDemoImporter
     */
    private static $instance = null;

    /**
     * Import session ID
     *
     * @var string
     */
    private $session_id;

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
     * Batch size for processing
     *
     * @var int
     */
    private $batch_size = 50;

    /**
     * Database transaction status
     *
     * @var bool
     */
    private $transaction_active = false;

    /**
     * Error log
     *
     * @var array
     */
    private $errors = array();

    /**
     * Get instance
     *
     * @return AdvancedDemoImporter
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
        $this->session_id = uniqid('aqualuxe_import_', true);
        $this->init_hooks();
        $this->load_config();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('wp_ajax_aqualuxe_demo_import', array($this, 'handle_ajax_import'));
        add_action('wp_ajax_aqualuxe_demo_rollback', array($this, 'handle_ajax_rollback'));
        add_action('wp_ajax_aqualuxe_demo_export', array($this, 'handle_ajax_export'));
        add_action('wp_ajax_aqualuxe_demo_status', array($this, 'handle_ajax_status'));
        
        // Cleanup hooks
        add_action('wp_scheduled_delete', array($this, 'cleanup_old_sessions'));
        add_action('shutdown', array($this, 'cleanup_on_shutdown'));
    }

    /**
     * Load import configuration
     */
    private function load_config() {
        $this->config = array(
            'content_types' => array(
                'posts' => array(
                    'name' => __('Blog Posts', 'aqualuxe'),
                    'count' => 25,
                    'enabled' => true,
                    'dependencies' => array('media'),
                ),
                'pages' => array(
                    'name' => __('Pages', 'aqualuxe'),
                    'count' => 12,
                    'enabled' => true,
                    'dependencies' => array('media'),
                ),
                'services' => array(
                    'name' => __('Professional Services', 'aqualuxe'),
                    'count' => 15,
                    'enabled' => true,
                    'dependencies' => array('media'),
                ),
                'events' => array(
                    'name' => __('Events & Workshops', 'aqualuxe'),
                    'count' => 10,
                    'enabled' => true,
                    'dependencies' => array('media', 'services'),
                ),
                'auctions' => array(
                    'name' => __('Auction Items', 'aqualuxe'),
                    'count' => 8,
                    'enabled' => true,
                    'dependencies' => array('media'),
                ),
                'products' => array(
                    'name' => __('WooCommerce Products', 'aqualuxe'),
                    'count' => 50,
                    'enabled' => class_exists('WooCommerce'),
                    'dependencies' => array('media', 'product_categories'),
                ),
                'testimonials' => array(
                    'name' => __('Customer Testimonials', 'aqualuxe'),
                    'count' => 20,
                    'enabled' => true,
                    'dependencies' => array('media'),
                ),
                'team' => array(
                    'name' => __('Team Members', 'aqualuxe'),
                    'count' => 8,
                    'enabled' => true,
                    'dependencies' => array('media'),
                ),
                'media' => array(
                    'name' => __('Media Files', 'aqualuxe'),
                    'count' => 100,
                    'enabled' => true,
                    'dependencies' => array(),
                ),
                'menus' => array(
                    'name' => __('Navigation Menus', 'aqualuxe'),
                    'count' => 3,
                    'enabled' => true,
                    'dependencies' => array('pages', 'products'),
                ),
                'widgets' => array(
                    'name' => __('Sidebar Widgets', 'aqualuxe'),
                    'count' => 15,
                    'enabled' => true,
                    'dependencies' => array(),
                ),
                'customizer' => array(
                    'name' => __('Theme Customizer Settings', 'aqualuxe'),
                    'count' => 1,
                    'enabled' => true,
                    'dependencies' => array('media'),
                ),
                'sample_data' => array(
                    'name' => __('Sample Business Data', 'aqualuxe'),
                    'count' => 1,
                    'enabled' => true,
                    'dependencies' => array('posts', 'pages', 'products'),
                ),
            ),
            'batch_processing' => true,
            'transaction_support' => true,
            'rollback_support' => true,
            'progress_tracking' => true,
            'conflict_resolution' => 'merge', // merge, overwrite, skip
            'media_handling' => 'download', // download, placeholder, skip
        );
    }

    /**
     * Handle AJAX import request
     */
    public function handle_ajax_import() {
        // Verify nonce and permissions
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_demo_import') || !current_user_can('manage_options')) {
            wp_send_json_error(__('Security check failed', 'aqualuxe'));
        }

        $import_type = sanitize_text_field($_POST['import_type'] ?? 'full');
        $selected_types = array_map('sanitize_text_field', $_POST['content_types'] ?? array());
        $options = array(
            'batch_size' => intval($_POST['batch_size'] ?? $this->batch_size),
            'conflict_resolution' => sanitize_text_field($_POST['conflict_resolution'] ?? 'merge'),
            'media_handling' => sanitize_text_field($_POST['media_handling'] ?? 'download'),
        );

        try {
            $this->start_import($import_type, $selected_types, $options);
            wp_send_json_success(array(
                'message' => __('Import started successfully', 'aqualuxe'),
                'session_id' => $this->session_id,
            ));
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    /**
     * Start the import process
     */
    private function start_import($import_type, $selected_types, $options) {
        // Initialize session
        $this->init_session($import_type, $selected_types, $options);
        
        // Start database transaction
        $this->start_transaction();
        
        try {
            // Process imports based on dependency order
            $ordered_types = $this->resolve_dependencies($selected_types);
            
            foreach ($ordered_types as $content_type) {
                $this->process_content_type($content_type);
            }
            
            // Commit transaction
            $this->commit_transaction();
            
            // Update final status
            $this->update_progress(100, __('Import completed successfully', 'aqualuxe'));
            
        } catch (Exception $e) {
            // Rollback on error
            $this->rollback_transaction();
            $this->log_error($e->getMessage());
            throw $e;
        }
    }

    /**
     * Initialize import session
     */
    private function init_session($import_type, $selected_types, $options) {
        $this->progress = array(
            'session_id' => $this->session_id,
            'start_time' => current_time('timestamp'),
            'import_type' => $import_type,
            'selected_types' => $selected_types,
            'options' => $options,
            'status' => 'initializing',
            'current_step' => 0,
            'total_steps' => count($selected_types),
            'percentage' => 0,
            'message' => __('Initializing import...', 'aqualuxe'),
            'items_processed' => 0,
            'items_total' => $this->calculate_total_items($selected_types),
        );

        // Save session to database
        update_option('aqualuxe_import_session_' . $this->session_id, $this->progress, false);
    }

    /**
     * Calculate total items to process
     */
    private function calculate_total_items($selected_types) {
        $total = 0;
        foreach ($selected_types as $type) {
            if (isset($this->config['content_types'][$type])) {
                $total += $this->config['content_types'][$type]['count'];
            }
        }
        return $total;
    }

    /**
     * Resolve content type dependencies
     */
    private function resolve_dependencies($selected_types) {
        $ordered = array();
        $processed = array();
        
        // Process dependencies recursively
        foreach ($selected_types as $type) {
            $this->add_with_dependencies($type, $ordered, $processed, $selected_types);
        }
        
        return array_unique($ordered);
    }

    /**
     * Add content type with its dependencies
     */
    private function add_with_dependencies($type, &$ordered, &$processed, $selected_types) {
        if (in_array($type, $processed)) {
            return;
        }
        
        if (!isset($this->config['content_types'][$type])) {
            return;
        }
        
        $config = $this->config['content_types'][$type];
        
        // Process dependencies first
        if (!empty($config['dependencies'])) {
            foreach ($config['dependencies'] as $dependency) {
                if (in_array($dependency, $selected_types) || $dependency === 'media') {
                    $this->add_with_dependencies($dependency, $ordered, $processed, $selected_types);
                }
            }
        }
        
        $ordered[] = $type;
        $processed[] = $type;
    }

    /**
     * Process specific content type
     */
    private function process_content_type($content_type) {
        $this->update_progress(
            null, 
            sprintf(__('Processing %s...', 'aqualuxe'), $this->config['content_types'][$content_type]['name'])
        );

        switch ($content_type) {
            case 'media':
                $this->import_media();
                break;
            case 'posts':
                $this->import_posts();
                break;
            case 'pages':
                $this->import_pages();
                break;
            case 'services':
                $this->import_services();
                break;
            case 'events':
                $this->import_events();
                break;
            case 'auctions':
                $this->import_auctions();
                break;
            case 'products':
                $this->import_products();
                break;
            case 'testimonials':
                $this->import_testimonials();
                break;
            case 'team':
                $this->import_team();
                break;
            case 'menus':
                $this->import_menus();
                break;
            case 'widgets':
                $this->import_widgets();
                break;
            case 'customizer':
                $this->import_customizer_settings();
                break;
            case 'sample_data':
                $this->import_sample_data();
                break;
        }

        $this->progress['current_step']++;
        $this->update_progress();
    }

    /**
     * Import media files
     */
    private function import_media() {
        $media_items = $this->get_demo_media_list();
        $imported = 0;
        
        foreach ($media_items as $batch) {
            foreach ($batch as $media_item) {
                $attachment_id = $this->import_media_item($media_item);
                if ($attachment_id) {
                    $this->track_imported_item('media', $attachment_id);
                    $imported++;
                }
                
                if ($imported % 10 === 0) {
                    $this->update_progress(null, sprintf(__('Imported %d media files...', 'aqualuxe'), $imported));
                }
            }
        }
    }

    /**
     * Import blog posts
     */
    private function import_posts() {
        $posts_data = $this->generate_demo_posts();
        
        foreach ($posts_data as $post_data) {
            $post_id = wp_insert_post($post_data);
            
            if ($post_id && !is_wp_error($post_id)) {
                $this->track_imported_item('post', $post_id);
                
                // Add meta fields
                $this->add_post_meta($post_id, 'post');
                
                // Add featured image
                $this->assign_featured_image($post_id);
            }
        }
    }

    /**
     * Import pages
     */
    private function import_pages() {
        $pages_data = $this->generate_demo_pages();
        
        foreach ($pages_data as $page_data) {
            $page_id = wp_insert_post($page_data);
            
            if ($page_id && !is_wp_error($page_id)) {
                $this->track_imported_item('page', $page_id);
                
                // Add meta fields
                $this->add_post_meta($page_id, 'page');
                
                // Add featured image
                $this->assign_featured_image($page_id);
                
                // Set page templates
                $this->assign_page_template($page_id, $page_data);
            }
        }
    }

    /**
     * Import services
     */
    private function import_services() {
        $services_data = $this->generate_demo_services();
        
        foreach ($services_data as $service_data) {
            $service_id = wp_insert_post($service_data);
            
            if ($service_id && !is_wp_error($service_id)) {
                $this->track_imported_item('aqualuxe_service', $service_id);
                
                // Add service-specific meta
                $this->add_service_meta($service_id);
                
                // Add featured image
                $this->assign_featured_image($service_id);
            }
        }
    }

    /**
     * Import WooCommerce products
     */
    private function import_products() {
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        $products_data = $this->generate_demo_products();
        
        foreach ($products_data as $product_data) {
            $product = new WC_Product_Simple();
            
            $product->set_name($product_data['name']);
            $product->set_description($product_data['description']);
            $product->set_short_description($product_data['short_description']);
            $product->set_price($product_data['price']);
            $product->set_regular_price($product_data['regular_price']);
            $product->set_sku($product_data['sku']);
            $product->set_stock_quantity($product_data['stock']);
            $product->set_manage_stock(true);
            $product->set_status('publish');
            
            $product_id = $product->save();
            
            if ($product_id) {
                $this->track_imported_item('product', $product_id);
                
                // Add product categories
                $this->assign_product_categories($product_id, $product_data);
                
                // Add product gallery
                $this->assign_product_gallery($product_id);
                
                // Add custom meta for wholesale pricing
                $this->add_wholesale_pricing($product_id, $product_data);
            }
        }
    }

    /**
     * Generate demo posts data
     */
    private function generate_demo_posts() {
        $posts = array();
        $aquatic_topics = array(
            'Ultimate Guide to Freshwater Aquarium Setup',
            'Top 10 Exotic Fish Species for Advanced Aquarists',
            'Maintaining Water Quality: pH, Temperature, and More',
            'Aquascaping Techniques for Stunning Underwater Landscapes',
            'Breeding Tropical Fish: A Complete Guide',
            'Common Aquarium Diseases and Treatment Methods',
            'LED vs Traditional Lighting for Aquariums',
            'Creating a Biotope Aquarium: Natural Ecosystems',
            'Saltwater vs Freshwater: Which is Right for You?',
            'Advanced Filtration Systems for Large Aquariums',
            'Feeding Your Fish: Nutrition and Best Practices',
            'Aquarium Plant Care and Propagation',
            'Setting Up a Quarantine Tank',
            'Water Change Schedules and Techniques',
            'Choosing the Right Substrate for Your Aquarium',
            'CO2 Systems for Planted Aquariums',
            'Understanding the Nitrogen Cycle',
            'Aquarium Heater Safety and Selection',
            'Building a Custom Aquarium Stand',
            'Seasonal Aquarium Maintenance Checklist',
            'Troubleshooting Common Aquarium Problems',
            'The Art of Aquatic Photography',
            'Sustainable Aquarium Practices',
            'Creating Multi-Species Community Tanks',
            'Emergency Aquarium Care During Power Outages'
        );
        
        for ($i = 0; $i < min(25, count($aquatic_topics)); $i++) {
            $posts[] = array(
                'post_title' => $aquatic_topics[$i],
                'post_content' => $this->generate_aquatic_content($aquatic_topics[$i]),
                'post_excerpt' => $this->generate_excerpt($aquatic_topics[$i]),
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_author' => 1,
                'post_date' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 180) . ' days')),
                'comment_status' => 'open',
            );
        }
        
        return $posts;
    }

    /**
     * Generate demo pages data
     */
    private function generate_demo_pages() {
        return array(
            array(
                'post_title' => 'Home',
                'post_content' => $this->generate_homepage_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'menu_order' => 1,
                'template' => 'page-home.php',
            ),
            array(
                'post_title' => 'About Us',
                'post_content' => $this->generate_about_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'menu_order' => 2,
            ),
            array(
                'post_title' => 'Our Services',
                'post_content' => $this->generate_services_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'menu_order' => 3,
                'template' => 'page-services.php',
            ),
            array(
                'post_title' => 'Shop',
                'post_content' => '[products limit="12" columns="4"]',
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'menu_order' => 4,
            ),
            array(
                'post_title' => 'Auctions',
                'post_content' => '[auction_listing limit="8" status="active"]',
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'menu_order' => 5,
            ),
            array(
                'post_title' => 'Wholesale',
                'post_content' => $this->generate_wholesale_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'menu_order' => 6,
            ),
            array(
                'post_title' => 'Franchise Opportunities',
                'post_content' => '[franchise_application_form title="Join the AquaLuxe Family"]',
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'menu_order' => 7,
            ),
            array(
                'post_title' => 'Trade-Ins',
                'post_content' => '[trade_form title="Trade Your Aquatic Items"]',
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'menu_order' => 8,
            ),
            array(
                'post_title' => 'Contact Us',
                'post_content' => $this->generate_contact_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'menu_order' => 9,
                'template' => 'page-contact.php',
            ),
            array(
                'post_title' => 'Privacy Policy',
                'post_content' => $this->generate_privacy_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'menu_order' => 10,
            ),
            array(
                'post_title' => 'Terms & Conditions',
                'post_content' => $this->generate_terms_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'menu_order' => 11,
            ),
            array(
                'post_title' => 'Partner Portal',
                'post_content' => '[partner_portal]',
                'post_status' => 'private',
                'post_type' => 'page',
                'post_author' => 1,
                'menu_order' => 12,
            ),
        );
    }

    /**
     * Generate aquatic-themed content
     */
    private function generate_aquatic_content($title) {
        $content_templates = array(
            'introduction' => 'Welcome to the fascinating world of aquatic life! In this comprehensive guide, we\'ll explore everything you need to know about %s.',
            'section1' => 'Understanding the fundamentals is crucial for any aquarist. Whether you\'re a beginner or experienced hobbyist, these insights will enhance your aquatic journey.',
            'section2' => 'The key to success lies in attention to detail and consistent care. From water parameters to feeding schedules, every aspect matters.',
            'section3' => 'Our team of marine biologists and aquarium specialists has compiled these best practices based on years of research and hands-on experience.',
            'conclusion' => 'Remember, every aquarium is unique, and what works for one setup may need adjustment for another. Stay curious, keep learning, and enjoy the rewarding experience of aquatic stewardship.',
        );
        
        $content = sprintf($content_templates['introduction'], strtolower($title)) . "\n\n";
        $content .= $content_templates['section1'] . "\n\n";
        $content .= "## Key Points to Remember\n\n";
        $content .= "- Regular monitoring and maintenance\n";
        $content .= "- Quality equipment and supplies\n";  
        $content .= "- Proper research before making changes\n";
        $content .= "- Patience and consistency\n\n";
        $content .= $content_templates['section2'] . "\n\n";
        $content .= "## Expert Tips\n\n";
        $content .= $content_templates['section3'] . "\n\n";
        $content .= $content_templates['conclusion'];
        
        return $content;
    }

    /**
     * Generate homepage content
     */
    private function generate_homepage_content() {
        return '
        <div class="hero-section">
            <h1>Bringing Elegance to Aquatic Life – Globally</h1>
            <p>Discover premium aquatic solutions, from rare species to cutting-edge equipment. Your trusted partner in creating stunning underwater ecosystems.</p>
            <a href="/shop" class="btn btn-primary">Explore Our Collection</a>
        </div>

        <div class="featured-products">
            <h2>Featured Products</h2>
            [products limit="8" columns="4" featured="true"]
        </div>

        <div class="services-overview">
            <h2>Professional Services</h2>
            <p>From custom aquarium design to ongoing maintenance, our certified specialists provide comprehensive aquatic solutions.</p>
            [aqualuxe_services limit="6"]
        </div>

        <div class="testimonials-section">
            <h2>What Our Customers Say</h2>
            [aqualuxe_testimonials limit="3"]
        </div>
        ';
    }

    /**
     * Update import progress
     */
    private function update_progress($percentage = null, $message = null) {
        if ($percentage !== null) {
            $this->progress['percentage'] = $percentage;
        } else {
            // Calculate based on current step
            $this->progress['percentage'] = round(($this->progress['current_step'] / $this->progress['total_steps']) * 100);
        }
        
        if ($message !== null) {
            $this->progress['message'] = $message;
        }
        
        $this->progress['last_updated'] = current_time('timestamp');
        
        // Save to database
        update_option('aqualuxe_import_session_' . $this->session_id, $this->progress, false);
    }

    /**
     * Track imported item for rollback
     */
    private function track_imported_item($type, $id) {
        if (!isset($this->imported_items[$type])) {
            $this->imported_items[$type] = array();
        }
        
        $this->imported_items[$type][] = $id;
        
        // Save to database
        update_option('aqualuxe_imported_items_' . $this->session_id, $this->imported_items, false);
    }

    /**
     * Start database transaction
     */
    private function start_transaction() {
        global $wpdb;
        $wpdb->query('START TRANSACTION');
        $this->transaction_active = true;
    }

    /**
     * Commit database transaction
     */
    private function commit_transaction() {
        global $wpdb;
        if ($this->transaction_active) {
            $wpdb->query('COMMIT');
            $this->transaction_active = false;
        }
    }

    /**
     * Rollback database transaction
     */
    private function rollback_transaction() {
        global $wpdb;
        if ($this->transaction_active) {
            $wpdb->query('ROLLBACK');
            $this->transaction_active = false;
        }
    }

    /**
     * Handle AJAX rollback request
     */
    public function handle_ajax_rollback() {
        // Verify nonce and permissions
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_demo_rollback') || !current_user_can('manage_options')) {
            wp_send_json_error(__('Security check failed', 'aqualuxe'));
        }

        $session_id = sanitize_text_field($_POST['session_id'] ?? '');
        
        try {
            $this->perform_rollback($session_id);
            wp_send_json_success(array(
                'message' => __('Rollback completed successfully', 'aqualuxe'),
            ));
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    /**
     * Perform rollback of imported content
     */
    private function perform_rollback($session_id) {
        $imported_items = get_option('aqualuxe_imported_items_' . $session_id, array());
        
        if (empty($imported_items)) {
            throw new Exception(__('No import session found for rollback', 'aqualuxe'));
        }
        
        // Start transaction for rollback
        $this->start_transaction();
        
        try {
            // Remove items in reverse order
            $rollback_order = array('post', 'page', 'aqualuxe_service', 'aqualuxe_event', 'aqualuxe_auction', 'product', 'aqualuxe_testimonial', 'aqualuxe_team', 'media');
            
            foreach ($rollback_order as $type) {
                if (isset($imported_items[$type])) {
                    foreach (array_reverse($imported_items[$type]) as $item_id) {
                        if ($type === 'media') {
                            wp_delete_attachment($item_id, true);
                        } else {
                            wp_delete_post($item_id, true);
                        }
                    }
                }
            }
            
            // Clean up options
            delete_option('aqualuxe_import_session_' . $session_id);
            delete_option('aqualuxe_imported_items_' . $session_id);
            
            $this->commit_transaction();
            
        } catch (Exception $e) {
            $this->rollback_transaction();
            throw $e;
        }
    }

    /**
     * Log error
     */
    private function log_error($message) {
        $this->errors[] = array(
            'message' => $message,
            'timestamp' => current_time('timestamp'),
        );
        
        // Save errors to database
        update_option('aqualuxe_import_errors_' . $this->session_id, $this->errors, false);
    }

    /**
     * Cleanup old sessions
     */
    public function cleanup_old_sessions() {
        global $wpdb;
        
        // Remove sessions older than 24 hours
        $old_sessions = $wpdb->get_results(
            "SELECT option_name FROM {$wpdb->options} 
             WHERE option_name LIKE 'aqualuxe_import_session_%' 
             AND option_value LIKE '%\"start_time\":%' 
             AND UNIX_TIMESTAMP() - CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(option_value, '\"start_time\":', -1), ',', 1) AS UNSIGNED) > 86400"
        );
        
        foreach ($old_sessions as $session) {
            $session_id = str_replace('aqualuxe_import_session_', '', $session->option_name);
            delete_option('aqualuxe_import_session_' . $session_id);
            delete_option('aqualuxe_imported_items_' . $session_id);
            delete_option('aqualuxe_import_errors_' . $session_id);
        }
    }

    /**
     * Cleanup on shutdown
     */
    public function cleanup_on_shutdown() {
        // Ensure transaction is closed
        if ($this->transaction_active) {
            $this->rollback_transaction();
        }
    }

    /**
     * Handle AJAX status request
     */
    public function handle_ajax_status() {
        $session_id = sanitize_text_field($_GET['session_id'] ?? '');
        $progress = get_option('aqualuxe_import_session_' . $session_id, array());
        
        if (empty($progress)) {
            wp_send_json_error(__('Import session not found', 'aqualuxe'));
        }
        
        wp_send_json_success($progress);
    }

    /**
     * Get demo media list
     */
    private function get_demo_media_list() {
        // This would normally contain URLs to copyright-free images
        // For demo purposes, we'll use placeholder service
        $media_items = array();
        
        for ($i = 1; $i <= 100; $i++) {
            $media_items[] = array(
                'url' => 'https://picsum.photos/800/600?random=' . $i,
                'title' => 'Demo Image ' . $i,
                'alt' => 'Demo aquatic image ' . $i,
                'caption' => 'Beautiful aquatic scene showcasing marine life',
            );
        }
        
        return array_chunk($media_items, $this->batch_size);
    }

    /**
     * Import single media item
     */
    private function import_media_item($media_item) {
        $upload_dir = wp_upload_dir();
        
        // Download image
        $image_data = wp_remote_get($media_item['url']);
        
        if (is_wp_error($image_data)) {
            return false;
        }
        
        $image_content = wp_remote_retrieve_body($image_data);
        $filename = 'demo-image-' . uniqid() . '.jpg';
        
        // Save to uploads directory
        $file_path = $upload_dir['path'] . '/' . $filename;
        file_put_contents($file_path, $image_content);
        
        // Create attachment
        $attachment = array(
            'guid'           => $upload_dir['url'] . '/' . basename($filename),
            'post_mime_type' => 'image/jpeg',
            'post_title'     => $media_item['title'],
            'post_content'   => '',
            'post_status'    => 'inherit'
        );
        
        $attachment_id = wp_insert_attachment($attachment, $file_path);
        
        if ($attachment_id) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
            wp_update_attachment_metadata($attachment_id, $attachment_data);
            
            // Add alt text
            update_post_meta($attachment_id, '_wp_attachment_image_alt', $media_item['alt']);
        }
        
        return $attachment_id;
    }

    /**
     * Generate sample services data (placeholder implementation)
     */
    private function generate_demo_services() {
        // This is a placeholder - implement based on services module structure
        return array();
    }

    /**
     * Generate sample products data (placeholder implementation)
     */
    private function generate_demo_products() {
        // This is a placeholder - implement based on WooCommerce requirements
        return array();
    }

    /**
     * Additional helper methods would be implemented here
     * for generating other content types, handling conflicts,
     * and managing the import process
     */
}

// Initialize the advanced demo importer
AdvancedDemoImporter::get_instance();