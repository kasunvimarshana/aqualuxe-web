<?php
/**
 * Demo Content Importer Class
 *
 * @package AquaLuxe\Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Comprehensive demo content importer with flush/reset capabilities
 */
class AquaLuxe_Demo_Importer {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_ajax_aqualuxe_import_demo', array($this, 'handle_import_demo'));
        add_action('wp_ajax_aqualuxe_flush_content', array($this, 'handle_flush_content'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            __('Demo Content Importer', 'aqualuxe'),
            __('Demo Content', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-importer',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'appearance_page_aqualuxe-demo-importer') {
            return;
        }
        
        wp_enqueue_script(
            'aqualuxe-demo-importer',
            aqualuxe_asset('js/demo-importer.js'),
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script('aqualuxe-demo-importer', 'aqualuxeDemo', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_demo_nonce'),
            'strings' => array(
                'importing' => __('Importing...', 'aqualuxe'),
                'flushing' => __('Flushing content...', 'aqualuxe'),
                'success' => __('Import completed successfully!', 'aqualuxe'),
                'error' => __('An error occurred during import.', 'aqualuxe'),
                'confirm_flush' => __('Are you sure you want to delete all content? This action cannot be undone.', 'aqualuxe'),
            )
        ));
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        ?>
        <div class="wrap aqualuxe-demo-importer">
            <h1><?php _e('AquaLuxe Demo Content Importer', 'aqualuxe'); ?></h1>
            
            <div class="demo-importer-content">
                <div class="demo-options">
                    <h2><?php _e('Import Demo Content', 'aqualuxe'); ?></h2>
                    <p><?php _e('Import realistic aquatic business demo content including products, services, pages, and media.', 'aqualuxe'); ?></p>
                    
                    <div class="import-options">
                        <label>
                            <input type="checkbox" id="import-pages" checked> 
                            <?php _e('Import Pages (Home, About, Services, Contact, FAQ, Legal)', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" id="import-posts" checked> 
                            <?php _e('Import Blog Posts (Aquarium care guides, tips)', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" id="import-products" checked> 
                            <?php _e('Import WooCommerce Products (Fish, Plants, Equipment)', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" id="import-services" checked> 
                            <?php _e('Import Services (Design, Maintenance, Consultation)', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" id="import-events" checked> 
                            <?php _e('Import Events (Workshops, Exhibitions)', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" id="import-testimonials" checked> 
                            <?php _e('Import Testimonials', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" id="import-media" checked> 
                            <?php _e('Import Media (Placeholder images)', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" id="import-menus" checked> 
                            <?php _e('Setup Navigation Menus', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" id="import-customizer" checked> 
                            <?php _e('Configure Theme Settings', 'aqualuxe'); ?>
                        </label>
                    </div>
                    
                    <button id="import-demo-btn" class="button button-primary button-hero">
                        <?php _e('Import Demo Content', 'aqualuxe'); ?>
                    </button>
                    
                    <div class="import-progress" style="display: none;">
                        <div class="progress-bar">
                            <div class="progress-fill"></div>
                        </div>
                        <div class="progress-text"></div>
                    </div>
                </div>
                
                <div class="flush-options">
                    <h2><?php _e('Reset Content', 'aqualuxe'); ?></h2>
                    <p><?php _e('Completely remove all content and reset the site to a clean state.', 'aqualuxe'); ?></p>
                    
                    <div class="flush-options-list">
                        <label>
                            <input type="checkbox" id="flush-posts"> 
                            <?php _e('Delete All Posts', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" id="flush-pages"> 
                            <?php _e('Delete All Pages', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" id="flush-products"> 
                            <?php _e('Delete All Products', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" id="flush-custom-posts"> 
                            <?php _e('Delete Custom Post Types', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" id="flush-media"> 
                            <?php _e('Delete Media Files', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" id="flush-menus"> 
                            <?php _e('Delete Menus', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" id="flush-customizer"> 
                            <?php _e('Reset Customizer Settings', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" id="flush-widgets"> 
                            <?php _e('Clear Widgets', 'aqualuxe'); ?>
                        </label>
                    </div>
                    
                    <button id="flush-content-btn" class="button button-secondary">
                        <?php _e('Flush All Content', 'aqualuxe'); ?>
                    </button>
                </div>
            </div>
            
            <div class="demo-preview">
                <h2><?php _e('Preview Demo Content', 'aqualuxe'); ?></h2>
                <div class="preview-grid">
                    <div class="preview-item">
                        <h3><?php _e('Homepage', 'aqualuxe'); ?></h3>
                        <p><?php _e('Hero section with aquatic imagery, featured products, testimonials, and newsletter signup.', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="preview-item">
                        <h3><?php _e('Products', 'aqualuxe'); ?></h3>
                        <p><?php _e('Rare fish species, aquatic plants, premium equipment, and care supplies with detailed descriptions.', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="preview-item">
                        <h3><?php _e('Services', 'aqualuxe'); ?></h3>
                        <p><?php _e('Aquarium design, maintenance, consultation, and professional installation services.', 'aqualuxe'); ?></p>
                    </div>
                    
                    <div class="preview-item">
                        <h3><?php _e('Blog Content', 'aqualuxe'); ?></h3>
                        <p><?php _e('Aquarium care guides, aquascaping tips, industry news, and breeding information.', 'aqualuxe'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .aqualuxe-demo-importer {
            max-width: 1200px;
        }
        
        .demo-importer-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin: 2rem 0;
        }
        
        .demo-options, .flush-options {
            background: white;
            padding: 2rem;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        
        .import-options label, .flush-options-list label {
            display: block;
            margin: 0.5rem 0;
            font-weight: 500;
        }
        
        .import-options input, .flush-options-list input {
            margin-right: 0.5rem;
        }
        
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
            margin: 1rem 0;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #0ea5e9, #06b6d4);
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .preview-item {
            background: #f9f9f9;
            padding: 1rem;
            border-radius: 6px;
            border-left: 4px solid #0ea5e9;
        }
        
        .preview-item h3 {
            margin: 0 0 0.5rem 0;
            color: #0ea5e9;
        }
        
        .button-hero {
            padding: 1rem 2rem !important;
            font-size: 16px !important;
        }
        </style>
        <?php
    }
    
    /**
     * Handle demo import AJAX request
     */
    public function handle_import_demo() {
        check_ajax_referer('aqualuxe_demo_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions.', 'aqualuxe'));
        }
        
        $import_options = $_POST['import_options'] ?? array();
        $total_steps = count($import_options);
        $completed_steps = 0;
        
        try {
            // Import pages
            if (in_array('pages', $import_options)) {
                $this->import_pages();
                $completed_steps++;
                $this->send_progress_update($completed_steps, $total_steps, __('Pages imported', 'aqualuxe'));
            }
            
            // Import posts
            if (in_array('posts', $import_options)) {
                $this->import_posts();
                $completed_steps++;
                $this->send_progress_update($completed_steps, $total_steps, __('Blog posts imported', 'aqualuxe'));
            }
            
            // Import products
            if (in_array('products', $import_options)) {
                $this->import_products();
                $completed_steps++;
                $this->send_progress_update($completed_steps, $total_steps, __('Products imported', 'aqualuxe'));
            }
            
            // Import services
            if (in_array('services', $import_options)) {
                $this->import_services();
                $completed_steps++;
                $this->send_progress_update($completed_steps, $total_steps, __('Services imported', 'aqualuxe'));
            }
            
            // Import events
            if (in_array('events', $import_options)) {
                $this->import_events();
                $completed_steps++;
                $this->send_progress_update($completed_steps, $total_steps, __('Events imported', 'aqualuxe'));
            }
            
            // Import testimonials
            if (in_array('testimonials', $import_options)) {
                $this->import_testimonials();
                $completed_steps++;
                $this->send_progress_update($completed_steps, $total_steps, __('Testimonials imported', 'aqualuxe'));
            }
            
            // Import media
            if (in_array('media', $import_options)) {
                $this->import_media();
                $completed_steps++;
                $this->send_progress_update($completed_steps, $total_steps, __('Media imported', 'aqualuxe'));
            }
            
            // Setup menus
            if (in_array('menus', $import_options)) {
                $this->setup_menus();
                $completed_steps++;
                $this->send_progress_update($completed_steps, $total_steps, __('Menus configured', 'aqualuxe'));
            }
            
            // Configure customizer
            if (in_array('customizer', $import_options)) {
                $this->configure_customizer();
                $completed_steps++;
                $this->send_progress_update($completed_steps, $total_steps, __('Theme settings configured', 'aqualuxe'));
            }
            
            wp_send_json_success(array(
                'message' => __('Demo content imported successfully!', 'aqualuxe'),
                'redirect' => home_url()
            ));
            
        } catch (Exception $e) {
            wp_send_json_error(array(
                'message' => __('Import failed: ', 'aqualuxe') . $e->getMessage()
            ));
        }
    }
    
    /**
     * Handle content flush AJAX request
     */
    public function handle_flush_content() {
        check_ajax_referer('aqualuxe_demo_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions.', 'aqualuxe'));
        }
        
        $flush_options = $_POST['flush_options'] ?? array();
        
        try {
            if (in_array('posts', $flush_options)) {
                $this->flush_posts();
            }
            
            if (in_array('pages', $flush_options)) {
                $this->flush_pages();
            }
            
            if (in_array('products', $flush_options)) {
                $this->flush_products();
            }
            
            if (in_array('custom-posts', $flush_options)) {
                $this->flush_custom_posts();
            }
            
            if (in_array('media', $flush_options)) {
                $this->flush_media();
            }
            
            if (in_array('menus', $flush_options)) {
                $this->flush_menus();
            }
            
            if (in_array('customizer', $flush_options)) {
                $this->flush_customizer();
            }
            
            if (in_array('widgets', $flush_options)) {
                $this->flush_widgets();
            }
            
            wp_send_json_success(array(
                'message' => __('Content flushed successfully!', 'aqualuxe')
            ));
            
        } catch (Exception $e) {
            wp_send_json_error(array(
                'message' => __('Flush failed: ', 'aqualuxe') . $e->getMessage()
            ));
        }
    }
    
    /**
     * Send progress update
     */
    private function send_progress_update($completed, $total, $message) {
        $progress = round(($completed / $total) * 100);
        
        wp_send_json(array(
            'progress' => $progress,
            'message' => $message,
            'completed' => $completed,
            'total' => $total
        ));
    }
    
    /**
     * Import pages
     */
    private function import_pages() {
        $pages = array(
            array(
                'title' => 'Homepage',
                'content' => $this->get_homepage_content(),
                'template' => 'page-home.php',
                'is_front_page' => true
            ),
            array(
                'title' => 'About Us',
                'content' => $this->get_about_content(),
                'slug' => 'about'
            ),
            array(
                'title' => 'Our Services',
                'content' => $this->get_services_content(),
                'slug' => 'services'
            ),
            array(
                'title' => 'Contact Us',
                'content' => $this->get_contact_content(),
                'slug' => 'contact'
            ),
            array(
                'title' => 'FAQ',
                'content' => $this->get_faq_content(),
                'slug' => 'faq'
            ),
            array(
                'title' => 'Privacy Policy',
                'content' => $this->get_privacy_content(),
                'slug' => 'privacy-policy'
            ),
            array(
                'title' => 'Terms & Conditions',
                'content' => $this->get_terms_content(),
                'slug' => 'terms-conditions'
            )
        );
        
        foreach ($pages as $page_data) {
            $page_id = wp_insert_post(array(
                'post_title' => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $page_data['slug'] ?? sanitize_title($page_data['title'])
            ));
            
            if ($page_id && !is_wp_error($page_id)) {
                if (isset($page_data['template'])) {
                    update_post_meta($page_id, '_wp_page_template', $page_data['template']);
                }
                
                if (isset($page_data['is_front_page']) && $page_data['is_front_page']) {
                    update_option('show_on_front', 'page');
                    update_option('page_on_front', $page_id);
                }
            }
        }
    }
    
    /**
     * Import blog posts
     */
    private function import_posts() {
        $posts = array(
            array(
                'title' => 'Essential Guide to Aquarium Water Chemistry',
                'content' => $this->get_sample_post_content('water-chemistry'),
                'category' => 'Aquarium Care',
                'tags' => array('water chemistry', 'pH', 'ammonia', 'nitrates', 'aquarium maintenance')
            ),
            array(
                'title' => 'Creating a Stunning Freshwater Aquascape',
                'content' => $this->get_sample_post_content('aquascaping'),
                'category' => 'Aquascaping',
                'tags' => array('aquascaping', 'planted tank', 'design', 'freshwater', 'aquatic plants')
            ),
            array(
                'title' => 'Breeding Tropical Fish: A Beginner\'s Guide',
                'content' => $this->get_sample_post_content('breeding'),
                'category' => 'Fish Breeding',
                'tags' => array('fish breeding', 'tropical fish', 'spawning', 'fry care', 'reproduction')
            ),
            array(
                'title' => 'Marine Tank Setup: From Beginner to Expert',
                'content' => $this->get_sample_post_content('marine-setup'),
                'category' => 'Marine Aquariums',
                'tags' => array('marine tank', 'saltwater', 'reef tank', 'coral', 'marine fish')
            ),
            array(
                'title' => 'Common Aquarium Diseases and Prevention',
                'content' => $this->get_sample_post_content('fish-health'),
                'category' => 'Fish Health',
                'tags' => array('fish diseases', 'aquarium health', 'prevention', 'treatment', 'quarantine')
            )
        );
        
        foreach ($posts as $post_data) {
            // Create category if it doesn't exist
            $category_id = $this->get_or_create_category($post_data['category']);
            
            $post_id = wp_insert_post(array(
                'post_title' => $post_data['title'],
                'post_content' => $post_data['content'],
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_category' => array($category_id)
            ));
            
            if ($post_id && !is_wp_error($post_id)) {
                // Add tags
                wp_set_post_tags($post_id, $post_data['tags']);
                
                // Set featured image placeholder
                $this->set_placeholder_featured_image($post_id);
            }
        }
    }
    
    /**
     * Import WooCommerce products
     */
    private function import_products() {
        if (!aqualuxe_is_woocommerce_active()) {
            return;
        }
        
        $products = array(
            array(
                'name' => 'Neon Tetra School (10 Fish)',
                'description' => 'Vibrant neon tetras perfect for community tanks. These peaceful schooling fish add brilliant color and movement to any aquarium.',
                'price' => 24.99,
                'category' => 'Freshwater Fish',
                'stock' => 15,
                'type' => 'simple',
                'meta' => array(
                    '_care_level' => 'Easy',
                    '_water_type' => 'Freshwater',
                    '_origin' => 'South America',
                    '_adult_size' => '1.5 inches',
                    '_temperament' => 'Peaceful'
                )
            ),
            array(
                'name' => 'Premium Java Fern',
                'description' => 'Hardy aquatic plant perfect for beginners. Adds natural beauty and provides hiding spots for fish.',
                'price' => 12.99,
                'category' => 'Aquatic Plants',
                'stock' => 25,
                'type' => 'simple',
                'meta' => array(
                    '_care_level' => 'Easy',
                    '_lighting' => 'Low to Medium',
                    '_placement' => 'Mid to Background',
                    '_growth_rate' => 'Slow'
                )
            ),
            array(
                'name' => 'Professional Aquarium Filter System',
                'description' => 'High-performance canister filter suitable for tanks up to 100 gallons. Includes biological, mechanical, and chemical filtration.',
                'price' => 159.99,
                'category' => 'Equipment',
                'stock' => 8,
                'type' => 'simple',
                'meta' => array(
                    '_tank_size' => 'Up to 100 gallons',
                    '_filter_type' => 'Canister',
                    '_flow_rate' => '400 GPH',
                    '_warranty' => '2 Years'
                )
            ),
            array(
                'name' => 'Discus Fish - German Blue',
                'description' => 'Premium German Blue Discus. Known as the "King of the Aquarium" for their stunning appearance and graceful swimming.',
                'price' => 89.99,
                'category' => 'Freshwater Fish',
                'stock' => 5,
                'type' => 'simple',
                'meta' => array(
                    '_care_level' => 'Advanced',
                    '_water_type' => 'Freshwater',
                    '_origin' => 'Germany (Bred)',
                    '_adult_size' => '6-8 inches',
                    '_temperament' => 'Peaceful'
                )
            )
        );
        
        foreach ($products as $product_data) {
            $product = new WC_Product_Simple();
            
            $product->set_name($product_data['name']);
            $product->set_description($product_data['description']);
            $product->set_regular_price($product_data['price']);
            $product->set_stock_quantity($product_data['stock']);
            $product->set_manage_stock(true);
            $product->set_stock_status('instock');
            $product->set_status('publish');
            
            $product_id = $product->save();
            
            if ($product_id) {
                // Set product category
                $category_id = $this->get_or_create_product_category($product_data['category']);
                wp_set_object_terms($product_id, $category_id, 'product_cat');
                
                // Add custom meta fields
                foreach ($product_data['meta'] as $key => $value) {
                    update_post_meta($product_id, $key, $value);
                }
                
                // Set placeholder product image
                $this->set_placeholder_product_image($product_id);
            }
        }
    }
    
    /**
     * Import services
     */
    private function import_services() {
        $services = array(
            array(
                'title' => 'Custom Aquarium Design',
                'content' => 'Professional aquarium design services tailored to your space and preferences. From concept to completion, we create stunning aquatic environments.',
                'price' => '$200 - $2000',
                'duration' => '2-4 weeks',
                'category' => 'Design Services'
            ),
            array(
                'title' => 'Aquarium Maintenance',
                'content' => 'Regular maintenance services to keep your aquarium healthy and beautiful. Includes water testing, cleaning, and fish health monitoring.',
                'price' => '$50 - $150/visit',
                'duration' => '1-2 hours',
                'category' => 'Maintenance Services'
            ),
            array(
                'title' => 'Fish Breeding Consultation',
                'content' => 'Expert consultation on fish breeding techniques, setup requirements, and ongoing support for successful breeding programs.',
                'price' => '$100/hour',
                'duration' => '1 hour minimum',
                'category' => 'Consultation Services'
            )
        );
        
        foreach ($services as $service_data) {
            $service_id = wp_insert_post(array(
                'post_title' => $service_data['title'],
                'post_content' => $service_data['content'],
                'post_status' => 'publish',
                'post_type' => 'aqualuxe_service'
            ));
            
            if ($service_id && !is_wp_error($service_id)) {
                update_post_meta($service_id, '_service_price', $service_data['price']);
                update_post_meta($service_id, '_service_duration', $service_data['duration']);
                
                // Set service category
                $category_id = $this->get_or_create_service_category($service_data['category']);
                wp_set_object_terms($service_id, $category_id, 'aqualuxe_service_category');
            }
        }
    }
    
    /**
     * Import events
     */
    private function import_events() {
        $events = array(
            array(
                'title' => 'Aquascaping Workshop',
                'content' => 'Learn the art of aquascaping from professional designers. Hands-on workshop covering design principles, plant selection, and maintenance.',
                'date' => date('Y-m-d', strtotime('+2 weeks')),
                'price' => '$75',
                'capacity' => '20 participants'
            ),
            array(
                'title' => 'Tropical Fish Exhibition',
                'content' => 'Annual exhibition featuring rare and exotic tropical fish from around the world. Meet breeders and see stunning display tanks.',
                'date' => date('Y-m-d', strtotime('+1 month')),
                'price' => 'Free',
                'capacity' => '500 visitors'
            )
        );
        
        foreach ($events as $event_data) {
            $event_id = wp_insert_post(array(
                'post_title' => $event_data['title'],
                'post_content' => $event_data['content'],
                'post_status' => 'publish',
                'post_type' => 'aqualuxe_event'
            ));
            
            if ($event_id && !is_wp_error($event_id)) {
                update_post_meta($event_id, '_event_date', $event_data['date']);
                update_post_meta($event_id, '_event_price', $event_data['price']);
                update_post_meta($event_id, '_event_capacity', $event_data['capacity']);
            }
        }
    }
    
    /**
     * Import testimonials
     */
    private function import_testimonials() {
        $testimonials = array(
            array(
                'title' => 'Sarah Johnson',
                'content' => 'AquaLuxe transformed my living room with a stunning 200-gallon reef tank. The design is breathtaking and the fish are healthy and vibrant.',
                'rating' => 5,
                'location' => 'New York, NY'
            ),
            array(
                'title' => 'Michael Chen',
                'content' => 'Their maintenance service is exceptional. My aquarium has never looked better, and I have peace of mind knowing the experts are taking care of everything.',
                'rating' => 5,
                'location' => 'Los Angeles, CA'
            ),
            array(
                'title' => 'Emily Rodriguez',
                'content' => 'The breeding consultation helped me successfully breed my discus fish. The expert advice was invaluable and the results speak for themselves.',
                'rating' => 5,
                'location' => 'Miami, FL'
            )
        );
        
        foreach ($testimonials as $testimonial_data) {
            $testimonial_id = wp_insert_post(array(
                'post_title' => $testimonial_data['title'],
                'post_content' => $testimonial_data['content'],
                'post_status' => 'publish',
                'post_type' => 'aqualuxe_testimonial'
            ));
            
            if ($testimonial_id && !is_wp_error($testimonial_id)) {
                update_post_meta($testimonial_id, '_testimonial_rating', $testimonial_data['rating']);
                update_post_meta($testimonial_id, '_testimonial_location', $testimonial_data['location']);
            }
        }
    }
    
    /**
     * Import placeholder media
     */
    private function import_media() {
        // Create placeholder images using placeholder services
        $placeholders = array(
            array('width' => 1920, 'height' => 1080, 'title' => 'Hero Background'),
            array('width' => 800, 'height' => 600, 'title' => 'Aquarium Tank'),
            array('width' => 400, 'height' => 400, 'title' => 'Tropical Fish'),
            array('width' => 600, 'height' => 400, 'title' => 'Aquatic Plants'),
            array('width' => 300, 'height' => 300, 'title' => 'Equipment'),
        );
        
        foreach ($placeholders as $placeholder) {
            $this->create_placeholder_image($placeholder['width'], $placeholder['height'], $placeholder['title']);
        }
    }
    
    /**
     * Setup navigation menus
     */
    private function setup_menus() {
        // Create primary menu
        $primary_menu_id = wp_create_nav_menu('Primary Menu');
        
        if ($primary_menu_id && !is_wp_error($primary_menu_id)) {
            // Add menu items
            $menu_items = array(
                array('title' => 'Home', 'url' => home_url()),
                array('title' => 'About', 'url' => home_url('/about')),
                array('title' => 'Services', 'url' => home_url('/services')),
                array('title' => 'Shop', 'url' => home_url('/shop')),
                array('title' => 'Blog', 'url' => home_url('/blog')),
                array('title' => 'Contact', 'url' => home_url('/contact'))
            );
            
            foreach ($menu_items as $item) {
                wp_update_nav_menu_item($primary_menu_id, 0, array(
                    'menu-item-title' => $item['title'],
                    'menu-item-url' => $item['url'],
                    'menu-item-status' => 'publish'
                ));
            }
            
            // Assign menu to theme location
            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary'] = $primary_menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }
    
    /**
     * Configure customizer settings
     */
    private function configure_customizer() {
        // Set theme options
        set_theme_mod('primary_color', '#0ea5e9');
        set_theme_mod('secondary_color', '#64748b');
        set_theme_mod('accent_color', '#14b8a6');
        set_theme_mod('enable_dark_mode', true);
        set_theme_mod('hero_title', 'Bringing Elegance to Aquatic Life');
        set_theme_mod('hero_subtitle', 'Premium aquariums, expert care, and stunning aquascapes for discerning enthusiasts worldwide.');
        set_theme_mod('company_phone', '+1 (555) 123-4567');
        set_theme_mod('company_email', 'info@aqualuxe.com');
        set_theme_mod('social_facebook_url', 'https://facebook.com/aqualuxe');
        set_theme_mod('social_instagram_url', 'https://instagram.com/aqualuxe');
        set_theme_mod('social_youtube_url', 'https://youtube.com/aqualuxe');
    }
    
    // Additional helper methods for content generation, flushing, etc.
    // ... (implementation continues with flush methods, content generators, etc.)
    
    /**
     * Get homepage content
     */
    private function get_homepage_content() {
        return '<!-- wp:cover {"url":"#","id":123,"dimRatio":30,"customOverlayColor":"#0ea5e9","contentPosition":"center center","isDark":false,"align":"full","className":"hero-section"} -->
<div class="wp-block-cover alignfull is-light hero-section"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-30 has-background-dim" style="background-color:#0ea5e9"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"4rem","lineHeight":"1.2"}},"textColor":"white"} -->
<h1 class="has-text-align-center has-white-color has-text-color" style="font-size:4rem;line-height:1.2">Bringing Elegance to Aquatic Life</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"1.25rem"}},"textColor":"white"} -->
<p class="has-text-align-center has-white-color has-text-color" style="font-size:1.25rem">Premium aquariums, expert care, and stunning aquascapes for discerning enthusiasts worldwide.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"accent","style":{"spacing":{"padding":{"top":"1rem","right":"2rem","bottom":"1rem","left":"2rem"}}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-accent-background-color has-background" style="padding-top:1rem;padding-right:2rem;padding-bottom:1rem;padding-left:2rem">Explore Our Services</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->';
    }
    
    /**
     * Flush all posts
     */
    private function flush_posts() {
        $posts = get_posts(array(
            'post_type' => 'post',
            'numberposts' => -1,
            'post_status' => 'any'
        ));
        
        foreach ($posts as $post) {
            wp_delete_post($post->ID, true);
        }
    }
    
    // Additional helper methods...
    private function get_or_create_category($category_name) {
        $category = get_category_by_slug(sanitize_title($category_name));
        if ($category) {
            return $category->term_id;
        }
        
        $category_id = wp_create_category($category_name);
        return $category_id;
    }
    
    private function set_placeholder_featured_image($post_id) {
        // Implementation for setting placeholder images
        return true;
    }
    
    // Continue with other helper methods...
}

// Initialize demo importer
new AquaLuxe_Demo_Importer();