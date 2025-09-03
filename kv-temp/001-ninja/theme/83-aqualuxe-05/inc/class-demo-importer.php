<?php
/**
 * AquaLuxe Demo Content Importer
 * 
 * Comprehensive demo content setup for showcasing the theme's capabilities
 * including WooCommerce products, pages, posts, and customizer settings.
 */

class AquaLuxe_Demo_Importer {
    
    private $demo_data_path;
    private $imported_posts = [];
    private $imported_terms = [];
    private $imported_attachments = [];
    
    public function __construct() {
        $this->demo_data_path = get_template_directory() . '/inc/demo-content/';
        
        // Add admin hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_ajax_aqualuxe_import_demo', array($this, 'ajax_import_demo'));
        add_action('wp_ajax_aqualuxe_reset_demo', array($this, 'ajax_reset_demo'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    public function add_admin_menu() {
        add_theme_page(
            __('Demo Content Importer', 'aqualuxe'),
            __('Demo Importer', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-importer',
            array($this, 'admin_page')
        );
    }
    
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'appearance_page_aqualuxe-demo-importer') return;
        
        wp_enqueue_script(
            'aqualuxe-demo-importer',
            get_template_directory_uri() . '/assets/dist/js/admin/demo-importer.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script('aqualuxe-demo-importer', 'aqualuxe_demo', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_demo_nonce'),
            'strings' => array(
                'importing' => __('Importing demo content...', 'aqualuxe'),
                'success' => __('Demo content imported successfully!', 'aqualuxe'),
                'error' => __('Error importing demo content.', 'aqualuxe'),
                'resetting' => __('Resetting content...', 'aqualuxe'),
                'reset_success' => __('Demo content reset successfully!', 'aqualuxe'),
                'reset_error' => __('Error resetting demo content.', 'aqualuxe'),
            )
        ));
        
        wp_enqueue_style(
            'aqualuxe-demo-importer',
            get_template_directory_uri() . '/assets/dist/css/admin/demo-importer.css',
            array(),
            AQUALUXE_VERSION
        );
    }
    
    public function admin_page() {
        ?>
        <div class="wrap aqualuxe-demo-importer">
            <h1><?php _e('AquaLuxe Demo Content Importer', 'aqualuxe'); ?></h1>
            
            <div class="demo-importer-content">
                <div class="demo-preview">
                    <h2><?php _e('Preview Demo Content', 'aqualuxe'); ?></h2>
                    <p><?php _e('This will import comprehensive demo content to showcase all AquaLuxe theme features:', 'aqualuxe'); ?></p>
                    
                    <div class="demo-features">
                        <div class="feature-grid">
                            <div class="feature-item">
                                <h3>🏠 Homepage</h3>
                                <p>Interactive fish tank hero, service sections, testimonials</p>
                            </div>
                            <div class="feature-item">
                                <h3>🛒 Shop Pages</h3>
                                <p>Aquarium products, fish food, equipment, accessories</p>
                            </div>
                            <div class="feature-item">
                                <h3>📝 Blog Content</h3>
                                <p>Aquarium care guides, fish species articles, maintenance tips</p>
                            </div>
                            <div class="feature-item">
                                <h3>📄 Service Pages</h3>
                                <p>Aquarium setup, maintenance services, consultation</p>
                            </div>
                            <div class="feature-item">
                                <h3>👥 About/Team</h3>
                                <p>Company story, team member profiles, expertise</p>
                            </div>
                            <div class="feature-item">
                                <h3>📞 Contact</h3>
                                <p>Contact forms, location maps, business hours</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="demo-actions">
                    <div class="action-card">
                        <h3><?php _e('Import Demo Content', 'aqualuxe'); ?></h3>
                        <p><?php _e('Import full demo content including pages, posts, products, and theme settings.', 'aqualuxe'); ?></p>
                        
                        <div class="import-options">
                            <label>
                                <input type="checkbox" id="import-content" checked>
                                <?php _e('Import Pages & Posts', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" id="import-products" checked>
                                <?php _e('Import WooCommerce Products', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" id="import-customizer" checked>
                                <?php _e('Import Theme Settings', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" id="import-widgets" checked>
                                <?php _e('Import Widgets', 'aqualuxe'); ?>
                            </label>
                        </div>
                        
                        <button id="import-demo" class="button button-primary button-hero">
                            <?php _e('Import Demo Content', 'aqualuxe'); ?>
                        </button>
                    </div>
                    
                    <div class="action-card">
                        <h3><?php _e('Reset Content', 'aqualuxe'); ?></h3>
                        <p><?php _e('Remove all demo content and restore default settings.', 'aqualuxe'); ?></p>
                        
                        <button id="reset-demo" class="button button-secondary">
                            <?php _e('Reset Demo Content', 'aqualuxe'); ?>
                        </button>
                    </div>
                </div>
                
                <div class="demo-progress" style="display: none;">
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                    <div class="progress-text"></div>
                </div>
            </div>
        </div>
        <?php
    }
    
    public function ajax_import_demo() {
        check_ajax_referer('aqualuxe_demo_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions.', 'aqualuxe'));
        }
        
        $import_content = isset($_POST['import_content']) && $_POST['import_content'] === 'true';
        $import_products = isset($_POST['import_products']) && $_POST['import_products'] === 'true';
        $import_customizer = isset($_POST['import_customizer']) && $_POST['import_customizer'] === 'true';
        $import_widgets = isset($_POST['import_widgets']) && $_POST['import_widgets'] === 'true';
        
        try {
            $results = array();
            
            if ($import_content) {
                $results['content'] = $this->import_content();
            }
            
            if ($import_products && class_exists('WooCommerce')) {
                $results['products'] = $this->import_products();
            }
            
            if ($import_customizer) {
                $results['customizer'] = $this->import_customizer_settings();
            }
            
            if ($import_widgets) {
                $results['widgets'] = $this->import_widgets();
            }
            
            // Set up menus
            $this->setup_menus();
            
            // Set homepage
            $this->set_homepage();
            
            wp_send_json_success($results);
            
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }
    
    public function ajax_reset_demo() {
        check_ajax_referer('aqualuxe_demo_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions.', 'aqualuxe'));
        }
        
        try {
            $this->reset_demo_content();
            wp_send_json_success(__('Demo content reset successfully.', 'aqualuxe'));
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }
    
    private function import_content() {
        $pages = $this->get_demo_pages();
        $posts = $this->get_demo_posts();
        
        $imported = array();
        
        // Import pages
        foreach ($pages as $page_data) {
            $page_id = $this->create_page($page_data);
            if ($page_id) {
                $imported['pages'][] = $page_id;
            }
        }
        
        // Import posts
        foreach ($posts as $post_data) {
            $post_id = $this->create_post($post_data);
            if ($post_id) {
                $imported['posts'][] = $post_id;
            }
        }
        
        return $imported;
    }
    
    private function get_demo_pages() {
        return array(
            array(
                'post_title' => 'Home',
                'post_content' => $this->get_homepage_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
                'page_template' => 'page-aqualuxe-home.php',
                'meta' => array(
                    '_aqualuxe_enable_fish_tank' => true,
                    '_aqualuxe_hero_title' => 'Welcome to AquaLuxe',
                    '_aqualuxe_hero_subtitle' => 'Bringing elegance to aquatic life – globally',
                )
            ),
            array(
                'post_title' => 'About Us',
                'post_content' => $this->get_about_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
            ),
            array(
                'post_title' => 'Services',
                'post_content' => $this->get_services_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
            ),
            array(
                'post_title' => 'Contact',
                'post_content' => $this->get_contact_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
            ),
            array(
                'post_title' => 'Blog',
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'page',
            ),
        );
    }
    
    private function get_demo_posts() {
        return array(
            array(
                'post_title' => 'Essential Guide to Saltwater Aquarium Setup',
                'post_content' => $this->get_saltwater_guide_content(),
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_category' => array('Aquarium Care'),
                'meta' => array(
                    '_featured_image' => 'saltwater-aquarium.jpg'
                )
            ),
            array(
                'post_title' => 'Top 10 Beginner-Friendly Fish Species',
                'post_content' => $this->get_beginner_fish_content(),
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_category' => array('Fish Species'),
                'meta' => array(
                    '_featured_image' => 'beginner-fish.jpg'
                )
            ),
            array(
                'post_title' => 'Aquarium Maintenance Schedule: Monthly Checklist',
                'post_content' => $this->get_maintenance_content(),
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_category' => array('Maintenance'),
                'meta' => array(
                    '_featured_image' => 'aquarium-maintenance.jpg'
                )
            ),
        );
    }
    
    private function import_products() {
        if (!class_exists('WooCommerce')) {
            return false;
        }
        
        $products = $this->get_demo_products();
        $imported = array();
        
        foreach ($products as $product_data) {
            $product_id = $this->create_product($product_data);
            if ($product_id) {
                $imported[] = $product_id;
            }
        }
        
        return $imported;
    }
    
    private function get_demo_products() {
        return array(
            array(
                'name' => 'AquaLuxe Premium Fish Tank - 50 Gallon',
                'type' => 'simple',
                'regular_price' => '299.99',
                'description' => 'Premium glass aquarium with LED lighting system and advanced filtration.',
                'short_description' => 'Perfect starter tank for tropical fish enthusiasts.',
                'categories' => array('Aquariums'),
                'images' => array('premium-tank-50.jpg'),
                'featured' => true,
                'stock_quantity' => 15,
                'weight' => '45',
                'dimensions' => array('length' => '36', 'width' => '18', 'height' => '16'),
                'attributes' => array(
                    'capacity' => '50 Gallon',
                    'material' => 'Premium Glass',
                    'lighting' => 'LED Included'
                )
            ),
            array(
                'name' => 'Tropical Fish Food Premium Flakes',
                'type' => 'variable',
                'description' => 'Nutritionally balanced flake food for tropical fish.',
                'short_description' => 'High-quality nutrition for vibrant, healthy fish.',
                'categories' => array('Fish Food'),
                'images' => array('fish-food-flakes.jpg'),
                'variations' => array(
                    array('size' => 'Small (100g)', 'price' => '12.99'),
                    array('size' => 'Medium (250g)', 'price' => '24.99'),
                    array('size' => 'Large (500g)', 'price' => '39.99'),
                )
            ),
            array(
                'name' => 'Advanced Filtration System',
                'type' => 'simple',
                'regular_price' => '149.99',
                'description' => 'Multi-stage filtration system for crystal clear water.',
                'short_description' => 'Keep your aquarium water pristine and healthy.',
                'categories' => array('Filtration'),
                'images' => array('filtration-system.jpg'),
                'stock_quantity' => 8,
            ),
        );
    }
    
    private function create_page($page_data) {
        $page_id = wp_insert_post(array(
            'post_title' => $page_data['post_title'],
            'post_content' => $page_data['post_content'],
            'post_status' => $page_data['post_status'],
            'post_type' => $page_data['post_type'],
            'post_author' => 1,
        ));
        
        if ($page_id && !is_wp_error($page_id)) {
            // Set page template
            if (isset($page_data['page_template'])) {
                update_post_meta($page_id, '_wp_page_template', $page_data['page_template']);
            }
            
            // Set custom meta
            if (isset($page_data['meta'])) {
                foreach ($page_data['meta'] as $key => $value) {
                    update_post_meta($page_id, $key, $value);
                }
            }
            
            $this->imported_posts[] = $page_id;
            return $page_id;
        }
        
        return false;
    }
    
    private function create_post($post_data) {
        $post_id = wp_insert_post(array(
            'post_title' => $post_data['post_title'],
            'post_content' => $post_data['post_content'],
            'post_status' => $post_data['post_status'],
            'post_type' => $post_data['post_type'],
            'post_author' => 1,
        ));
        
        if ($post_id && !is_wp_error($post_id)) {
            // Set categories
            if (isset($post_data['post_category'])) {
                $categories = array();
                foreach ($post_data['post_category'] as $cat_name) {
                    $cat = get_category_by_slug(sanitize_title($cat_name));
                    if (!$cat) {
                        $cat_id = wp_create_category($cat_name);
                        $categories[] = $cat_id;
                    } else {
                        $categories[] = $cat->term_id;
                    }
                }
                wp_set_post_categories($post_id, $categories);
            }
            
            // Set custom meta
            if (isset($post_data['meta'])) {
                foreach ($post_data['meta'] as $key => $value) {
                    update_post_meta($post_id, $key, $value);
                }
            }
            
            $this->imported_posts[] = $post_id;
            return $post_id;
        }
        
        return false;
    }
    
    private function setup_menus() {
        // Create primary menu
        $menu_name = 'Primary Menu';
        $menu_exists = wp_get_nav_menu_object($menu_name);
        
        if (!$menu_exists) {
            $menu_id = wp_create_nav_menu($menu_name);
            
            // Add menu items
            $home_page = get_page_by_title('Home');
            $about_page = get_page_by_title('About Us');
            $services_page = get_page_by_title('Services');
            $blog_page = get_page_by_title('Blog');
            $contact_page = get_page_by_title('Contact');
            
            if ($home_page) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => 'Home',
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $home_page->ID,
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish'
                ));
            }
            
            if ($about_page) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => 'About',
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $about_page->ID,
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish'
                ));
            }
            
            if ($services_page) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => 'Services',
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $services_page->ID,
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish'
                ));
            }
            
            // Add shop page if WooCommerce is active
            if (class_exists('WooCommerce')) {
                $shop_page_id = get_option('woocommerce_shop_page_id');
                if ($shop_page_id) {
                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title' => 'Shop',
                        'menu-item-object' => 'page',
                        'menu-item-object-id' => $shop_page_id,
                        'menu-item-type' => 'post_type',
                        'menu-item-status' => 'publish'
                    ));
                }
            }
            
            if ($blog_page) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => 'Blog',
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $blog_page->ID,
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish'
                ));
            }
            
            if ($contact_page) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => 'Contact',
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $contact_page->ID,
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish'
                ));
            }
            
            // Assign menu to location
            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }
    
    private function set_homepage() {
        $home_page = get_page_by_title('Home');
        $blog_page = get_page_by_title('Blog');
        
        if ($home_page) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $home_page->ID);
            
            if ($blog_page) {
                update_option('page_for_posts', $blog_page->ID);
            }
        }
    }
    
    private function reset_demo_content() {
        // Remove imported posts
        foreach ($this->imported_posts as $post_id) {
            wp_delete_post($post_id, true);
        }
        
        // Remove imported terms
        foreach ($this->imported_terms as $term_id) {
            wp_delete_term($term_id, 'category');
        }
        
        // Remove imported attachments
        foreach ($this->imported_attachments as $attachment_id) {
            wp_delete_attachment($attachment_id, true);
        }
        
        // Reset homepage settings
        update_option('show_on_front', 'posts');
        delete_option('page_on_front');
        delete_option('page_for_posts');
        
        // Reset theme mods
        remove_theme_mods();
        
        // Clear imported data arrays
        $this->imported_posts = [];
        $this->imported_terms = [];
        $this->imported_attachments = [];
    }
    
    // Content generation methods
    private function get_homepage_content() {
        return '
        <!-- Fish Tank Hero Section -->
        <section class="hero-fishtank">
            <div class="fishtank" id="fish-tank-hero"></div>
            <div class="hero-content">
                <h1>Welcome to AquaLuxe</h1>
                <p>Bringing elegance to aquatic life – globally</p>
                <div class="cta-buttons">
                    <a href="/services/" class="btn btn-primary">Our Services</a>
                    <a href="/shop/" class="btn btn-secondary">Shop Now</a>
                </div>
            </div>
        </section>
        
        <!-- Services Section -->
        <section class="services-section py-20">
            <div class="container">
                <h2 class="section-title">Our Premium Services</h2>
                <div class="services-grid">
                    <div class="service-card">
                        <h3>Aquarium Setup</h3>
                        <p>Professional installation and setup of your dream aquarium system.</p>
                    </div>
                    <div class="service-card">
                        <h3>Maintenance</h3>
                        <p>Regular cleaning and maintenance to keep your aquarium thriving.</p>
                    </div>
                    <div class="service-card">
                        <h3>Consultation</h3>
                        <p>Expert advice on fish selection, equipment, and aquascaping.</p>
                    </div>
                </div>
            </div>
        </section>
        ';
    }
    
    private function get_about_content() {
        return '
        <h1>About AquaLuxe</h1>
        <p>For over 15 years, AquaLuxe has been the premier destination for aquarium enthusiasts worldwide. Our passion for aquatic life drives everything we do, from providing the highest quality equipment to offering expert guidance and support.</p>
        
        <h2>Our Mission</h2>
        <p>To bring elegance and beauty to aquatic life while supporting both hobbyists and professionals in creating stunning underwater environments.</p>
        
        <h2>Our Team</h2>
        <p>Our team consists of marine biologists, aquarium specialists, and passionate hobbyists who understand the intricacies of aquatic ecosystems.</p>
        ';
    }
    
    private function get_services_content() {
        return '
        <h1>Our Services</h1>
        
        <h2>Aquarium Setup & Installation</h2>
        <p>Complete aquarium setup services including tank installation, filtration systems, lighting, and initial ecosystem establishment.</p>
        
        <h2>Regular Maintenance</h2>
        <p>Comprehensive maintenance programs to ensure your aquarium remains healthy and beautiful year-round.</p>
        
        <h2>Equipment & Consultation</h2>
        <p>Expert advice on equipment selection, fish compatibility, and aquascaping design.</p>
        ';
    }
    
    private function get_contact_content() {
        return '
        <h1>Contact Us</h1>
        
        <div class="contact-info">
            <h2>Get in Touch</h2>
            <p>Phone: (555) 123-4567</p>
            <p>Email: info@aqualuxe.com</p>
            <p>Address: 123 Aquarium Way, Marine City, MC 12345</p>
        </div>
        
        <div class="contact-form">
            [contact-form-7 id="1" title="Contact form 1"]
        </div>
        ';
    }
    
    private function get_saltwater_guide_content() {
        return '
        <p>Setting up a saltwater aquarium is an exciting journey that requires careful planning and attention to detail. This comprehensive guide will walk you through everything you need to know.</p>
        
        <h2>Essential Equipment</h2>
        <ul>
            <li>High-quality glass tank (minimum 30 gallons for beginners)</li>
            <li>Protein skimmer for waste removal</li>
            <li>Live rock for biological filtration</li>
            <li>Proper lighting system</li>
            <li>Heater and thermometer</li>
        </ul>
        
        <h2>Water Parameters</h2>
        <p>Maintaining proper water chemistry is crucial for saltwater aquarium success...</p>
        ';
    }
    
    private function get_beginner_fish_content() {
        return '
        <p>Choosing the right fish for your first aquarium is crucial for success. Here are our top 10 recommendations for beginner-friendly species.</p>
        
        <h2>1. Clownfish</h2>
        <p>Hardy, colorful, and peaceful. Perfect for reef tanks.</p>
        
        <h2>2. Blue Tang</h2>
        <p>Beautiful and relatively easy to care for with proper tank size.</p>
        
        <h2>3. Cardinalfish</h2>
        <p>Peaceful schooling fish that adapt well to aquarium life.</p>
        ';
    }
    
    private function get_maintenance_content() {
        return '
        <p>Regular maintenance is the key to a thriving aquarium. Follow this monthly checklist to keep your aquatic environment healthy.</p>
        
        <h2>Weekly Tasks</h2>
        <ul>
            <li>Test water parameters (pH, ammonia, nitrites, nitrates)</li>
            <li>Clean glass surfaces</li>
            <li>Check equipment function</li>
        </ul>
        
        <h2>Monthly Tasks</h2>
        <ul>
            <li>25% water change</li>
            <li>Clean filter media</li>
            <li>Trim plants and remove debris</li>
        </ul>
        ';
    }
}

// Initialize the demo importer
new AquaLuxe_Demo_Importer();
