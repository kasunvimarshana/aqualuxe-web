<?php
/**
 * Demo Content Class
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo content importer class
 */
class Demo_Content {
    
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
        // Ajax handlers
        add_action('wp_ajax_aqualuxe_import_demo', [$this, 'ajax_import_demo']);
        add_action('wp_ajax_aqualuxe_reset_demo', [$this, 'ajax_reset_demo']);
    }
    
    /**
     * Import demo content
     */
    public function import_demo($demo_type = 'basic') {
        // Increase execution time and memory limit
        set_time_limit(0);
        ini_set('memory_limit', '512M');
        
        $success = true;
        
        try {
            switch ($demo_type) {
                case 'basic':
                    $this->import_basic_demo();
                    break;
                    
                case 'business':
                    $this->import_business_demo();
                    break;
                    
                case 'ecommerce':
                    $this->import_ecommerce_demo();
                    break;
                    
                case 'portfolio':
                    $this->import_portfolio_demo();
                    break;
                    
                case 'blog':
                    $this->import_blog_demo();
                    break;
                    
                default:
                    $this->import_basic_demo();
            }
            
            // Set imported flag
            update_option('aqualuxe_demo_imported', $demo_type);
            update_option('aqualuxe_demo_imported_date', current_time('mysql'));
            
        } catch (Exception $e) {
            error_log('AquaLuxe Demo Import Error: ' . $e->getMessage());
            $success = false;
        }
        
        return $success;
    }
    
    /**
     * Import basic demo content
     */
    private function import_basic_demo() {
        // Import pages
        $this->import_basic_pages();
        
        // Import posts
        $this->import_basic_posts();
        
        // Import navigation menus
        $this->import_basic_menus();
        
        // Import customizer settings
        $this->import_basic_customizer_settings();
        
        // Import widgets
        $this->import_basic_widgets();
    }
    
    /**
     * Import business demo content
     */
    private function import_business_demo() {
        $this->import_basic_demo();
        
        // Additional business content
        $this->import_business_pages();
        $this->import_team_members();
        $this->import_testimonials();
        $this->import_services();
    }
    
    /**
     * Import ecommerce demo content
     */
    private function import_ecommerce_demo() {
        if (!class_exists('WooCommerce')) {
            throw new Exception('WooCommerce is required for ecommerce demo');
        }
        
        $this->import_basic_demo();
        
        // Import products
        $this->import_products();
        
        // Import product categories
        $this->import_product_categories();
        
        // Import shop pages
        $this->import_shop_pages();
    }
    
    /**
     * Import portfolio demo content
     */
    private function import_portfolio_demo() {
        $this->import_basic_demo();
        
        // Import portfolio items
        $this->import_portfolio_items();
        
        // Import portfolio categories
        $this->import_portfolio_categories();
    }
    
    /**
     * Import blog demo content
     */
    private function import_blog_demo() {
        $this->import_basic_demo();
        
        // Import additional blog posts
        $this->import_blog_posts();
        
        // Import categories
        $this->import_blog_categories();
        
        // Import tags
        $this->import_blog_tags();
    }
    
    /**
     * Import basic pages
     */
    private function import_basic_pages() {
        $pages = [
            [
                'title' => 'Home',
                'content' => $this->get_homepage_content(),
                'template' => 'page-home.php',
                'is_front_page' => true,
            ],
            [
                'title' => 'About Us',
                'content' => $this->get_about_content(),
                'slug' => 'about',
            ],
            [
                'title' => 'Contact',
                'content' => $this->get_contact_content(),
                'slug' => 'contact',
            ],
            [
                'title' => 'Privacy Policy',
                'content' => $this->get_privacy_content(),
                'slug' => 'privacy-policy',
            ],
            [
                'title' => 'Terms of Service',
                'content' => $this->get_terms_content(),
                'slug' => 'terms-of-service',
            ],
        ];
        
        foreach ($pages as $page_data) {
            $this->create_page($page_data);
        }
    }
    
    /**
     * Import basic posts
     */
    private function import_basic_posts() {
        $posts = [
            [
                'title' => 'Welcome to AquaLuxe',
                'content' => $this->get_welcome_post_content(),
                'excerpt' => 'Discover the power and elegance of AquaLuxe theme. Built for modern websites with performance and user experience in mind.',
                'featured_image' => 'welcome-post.jpg',
                'category' => 'General',
                'tags' => ['welcome', 'aqua luxe', 'theme'],
            ],
            [
                'title' => 'Getting Started with Your New Website',
                'content' => $this->get_getting_started_content(),
                'excerpt' => 'Learn how to customize your AquaLuxe website and make it truly yours with our comprehensive guide.',
                'featured_image' => 'getting-started.jpg',
                'category' => 'Tutorials',
                'tags' => ['tutorial', 'getting started', 'customization'],
            ],
            [
                'title' => 'The Power of Modern Web Design',
                'content' => $this->get_web_design_content(),
                'excerpt' => 'Explore the principles of modern web design and how AquaLuxe implements them for optimal user experience.',
                'featured_image' => 'web-design.jpg',
                'category' => 'Design',
                'tags' => ['web design', 'modern', 'user experience'],
            ],
        ];
        
        foreach ($posts as $post_data) {
            $this->create_post($post_data);
        }
    }
    
    /**
     * Import basic menus
     */
    private function import_basic_menus() {
        // Create primary menu
        $primary_menu_id = wp_create_nav_menu('Primary Menu');
        
        $menu_items = [
            ['title' => 'Home', 'url' => home_url('/')],
            ['title' => 'About', 'url' => home_url('/about/')],
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
        
        // Assign to theme location
        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $primary_menu_id;
        set_theme_mod('nav_menu_locations', $locations);
        
        // Create footer menu
        $footer_menu_id = wp_create_nav_menu('Footer Menu');
        
        $footer_items = [
            ['title' => 'Privacy Policy', 'url' => home_url('/privacy-policy/')],
            ['title' => 'Terms of Service', 'url' => home_url('/terms-of-service/')],
            ['title' => 'Support', 'url' => home_url('/contact/')],
        ];
        
        foreach ($footer_items as $item) {
            wp_update_nav_menu_item($footer_menu_id, 0, [
                'menu-item-title' => $item['title'],
                'menu-item-url' => $item['url'],
                'menu-item-status' => 'publish',
                'menu-item-type' => 'custom',
            ]);
        }
        
        $locations['footer'] = $footer_menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
    
    /**
     * Import basic customizer settings
     */
    private function import_basic_customizer_settings() {
        $settings = [
            'aqualuxe_logo' => '',
            'aqualuxe_site_description' => 'Experience luxury and performance with AquaLuxe',
            'aqualuxe_primary_color' => '#0ea5e9',
            'aqualuxe_secondary_color' => '#06b6d4',
            'aqualuxe_accent_color' => '#f59e0b',
            'aqualuxe_enable_dark_mode' => true,
            'aqualuxe_hero_title' => 'Welcome to AquaLuxe',
            'aqualuxe_hero_subtitle' => 'The perfect blend of elegance and functionality',
            'aqualuxe_hero_button_text' => 'Get Started',
            'aqualuxe_hero_button_url' => home_url('/about/'),
            'aqualuxe_footer_text' => '© 2024 AquaLuxe. All rights reserved.',
            'aqualuxe_social_facebook' => 'https://facebook.com',
            'aqualuxe_social_twitter' => 'https://twitter.com',
            'aqualuxe_social_instagram' => 'https://instagram.com',
            'aqualuxe_social_linkedin' => 'https://linkedin.com',
        ];
        
        foreach ($settings as $key => $value) {
            set_theme_mod($key, $value);
        }
    }
    
    /**
     * Import basic widgets
     */
    private function import_basic_widgets() {
        $widgets = [
            'sidebar-1' => [
                'search-1' => [
                    'title' => 'Search',
                ],
                'recent-posts-1' => [
                    'title' => 'Recent Posts',
                    'number' => 5,
                ],
                'categories-1' => [
                    'title' => 'Categories',
                    'count' => 1,
                ],
                'archives-1' => [
                    'title' => 'Archives',
                    'count' => 1,
                ],
            ],
            'footer-1' => [
                'text-1' => [
                    'title' => 'About AquaLuxe',
                    'text' => 'AquaLuxe is a premium WordPress theme designed for modern websites. Experience the perfect blend of elegance and functionality.',
                ],
            ],
            'footer-2' => [
                'nav_menu-1' => [
                    'title' => 'Quick Links',
                    'nav_menu' => 'Primary Menu',
                ],
            ],
            'footer-3' => [
                'text-2' => [
                    'title' => 'Contact Info',
                    'text' => 'Email: info@example.com<br>Phone: (555) 123-4567<br>Address: 123 Main St, City, State 12345',
                ],
            ],
        ];
        
        update_option('sidebars_widgets', $widgets);
    }
    
    /**
     * Import products (WooCommerce)
     */
    private function import_products() {
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        $products = [
            [
                'title' => 'Premium Water Bottle',
                'description' => 'High-quality stainless steel water bottle with temperature control technology.',
                'price' => 29.99,
                'sale_price' => 24.99,
                'sku' => 'AWB001',
                'category' => 'Bottles',
                'featured_image' => 'water-bottle.jpg',
                'gallery' => ['water-bottle-1.jpg', 'water-bottle-2.jpg'],
            ],
            [
                'title' => 'Luxury Spa Set',
                'description' => 'Complete spa experience with premium bath salts, oils, and accessories.',
                'price' => 89.99,
                'sku' => 'LSS001',
                'category' => 'Spa & Wellness',
                'featured_image' => 'spa-set.jpg',
            ],
            [
                'title' => 'Aqua Therapy Kit',
                'description' => 'Professional-grade aqua therapy tools for home wellness routines.',
                'price' => 149.99,
                'sku' => 'ATK001',
                'category' => 'Therapy',
                'featured_image' => 'therapy-kit.jpg',
            ],
        ];
        
        foreach ($products as $product_data) {
            $this->create_product($product_data);
        }
    }
    
    /**
     * Create page
     */
    private function create_page($page_data) {
        $post_id = wp_insert_post([
            'post_title' => $page_data['title'],
            'post_content' => $page_data['content'],
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => $page_data['slug'] ?? sanitize_title($page_data['title']),
        ]);
        
        if ($post_id && !is_wp_error($post_id)) {
            // Set page template
            if (isset($page_data['template'])) {
                update_post_meta($post_id, '_wp_page_template', $page_data['template']);
            }
            
            // Set as front page
            if (isset($page_data['is_front_page']) && $page_data['is_front_page']) {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $post_id);
            }
        }
        
        return $post_id;
    }
    
    /**
     * Create post
     */
    private function create_post($post_data) {
        // Create category if it doesn't exist
        $category_id = null;
        if (isset($post_data['category'])) {
            $category = get_term_by('name', $post_data['category'], 'category');
            if (!$category) {
                $category_result = wp_insert_term($post_data['category'], 'category');
                if (!is_wp_error($category_result)) {
                    $category_id = $category_result['term_id'];
                }
            } else {
                $category_id = $category->term_id;
            }
        }
        
        $post_id = wp_insert_post([
            'post_title' => $post_data['title'],
            'post_content' => $post_data['content'],
            'post_excerpt' => $post_data['excerpt'] ?? '',
            'post_status' => 'publish',
            'post_type' => 'post',
            'post_category' => $category_id ? [$category_id] : [],
        ]);
        
        if ($post_id && !is_wp_error($post_id)) {
            // Add tags
            if (isset($post_data['tags'])) {
                wp_set_post_tags($post_id, $post_data['tags']);
            }
            
            // Set featured image
            if (isset($post_data['featured_image'])) {
                $this->set_featured_image($post_id, $post_data['featured_image']);
            }
        }
        
        return $post_id;
    }
    
    /**
     * Create product (WooCommerce)
     */
    private function create_product($product_data) {
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        $product = new WC_Product_Simple();
        
        $product->set_name($product_data['title']);
        $product->set_description($product_data['description']);
        $product->set_short_description($product_data['description']);
        $product->set_regular_price($product_data['price']);
        
        if (isset($product_data['sale_price'])) {
            $product->set_sale_price($product_data['sale_price']);
        }
        
        if (isset($product_data['sku'])) {
            $product->set_sku($product_data['sku']);
        }
        
        $product->set_status('publish');
        $product->set_manage_stock(true);
        $product->set_stock_quantity(100);
        $product->set_stock_status('instock');
        
        $product_id = $product->save();
        
        if ($product_id) {
            // Set category
            if (isset($product_data['category'])) {
                $category = get_term_by('name', $product_data['category'], 'product_cat');
                if (!$category) {
                    $category_result = wp_insert_term($product_data['category'], 'product_cat');
                    if (!is_wp_error($category_result)) {
                        $category_id = $category_result['term_id'];
                        wp_set_object_terms($product_id, $category_id, 'product_cat');
                    }
                } else {
                    wp_set_object_terms($product_id, $category->term_id, 'product_cat');
                }
            }
            
            // Set featured image
            if (isset($product_data['featured_image'])) {
                $this->set_featured_image($product_id, $product_data['featured_image']);
            }
        }
        
        return $product_id;
    }
    
    /**
     * Set featured image
     */
    private function set_featured_image($post_id, $image_filename) {
        $image_path = AQUALUXE_THEME_DIR . '/assets/demo/images/' . $image_filename;
        
        if (file_exists($image_path)) {
            $upload_dir = wp_upload_dir();
            $new_file = $upload_dir['path'] . '/' . $image_filename;
            
            if (copy($image_path, $new_file)) {
                $file_type = wp_check_filetype($image_filename, null);
                
                $attachment = [
                    'post_mime_type' => $file_type['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', $image_filename),
                    'post_content' => '',
                    'post_status' => 'inherit',
                ];
                
                $attachment_id = wp_insert_attachment($attachment, $new_file, $post_id);
                
                if (!is_wp_error($attachment_id)) {
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    $attachment_data = wp_generate_attachment_metadata($attachment_id, $new_file);
                    wp_update_attachment_metadata($attachment_id, $attachment_data);
                    set_post_thumbnail($post_id, $attachment_id);
                }
            }
        }
    }
    
    /**
     * Reset demo content
     */
    public function reset_demo() {
        // Delete demo posts
        $demo_posts = get_posts([
            'post_type' => ['post', 'page', 'product'],
            'meta_key' => 'aqualuxe_demo_content',
            'meta_value' => '1',
            'numberposts' => -1,
        ]);
        
        foreach ($demo_posts as $post) {
            wp_delete_post($post->ID, true);
        }
        
        // Delete demo menus
        $menus = wp_get_nav_menus();
        foreach ($menus as $menu) {
            if (strpos($menu->name, 'Demo') !== false || in_array($menu->name, ['Primary Menu', 'Footer Menu'])) {
                wp_delete_nav_menu($menu->term_id);
            }
        }
        
        // Reset customizer settings
        $demo_settings = [
            'aqualuxe_hero_title',
            'aqualuxe_hero_subtitle',
            'aqualuxe_hero_button_text',
            'aqualuxe_hero_button_url',
        ];
        
        foreach ($demo_settings as $setting) {
            remove_theme_mod($setting);
        }
        
        // Reset widgets
        update_option('sidebars_widgets', []);
        
        // Remove demo flag
        delete_option('aqualuxe_demo_imported');
        delete_option('aqualuxe_demo_imported_date');
        
        return true;
    }
    
    /**
     * Ajax import demo
     */
    public function ajax_import_demo() {
        check_ajax_referer('aqualuxe_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Insufficient permissions', 'aqualuxe')]);
        }
        
        $demo_type = sanitize_text_field($_POST['demo_type'] ?? 'basic');
        
        $result = $this->import_demo($demo_type);
        
        if ($result) {
            wp_send_json_success(['message' => __('Demo content imported successfully', 'aqualuxe')]);
        } else {
            wp_send_json_error(['message' => __('Failed to import demo content', 'aqualuxe')]);
        }
    }
    
    /**
     * Ajax reset demo
     */
    public function ajax_reset_demo() {
        check_ajax_referer('aqualuxe_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Insufficient permissions', 'aqualuxe')]);
        }
        
        $result = $this->reset_demo();
        
        if ($result) {
            wp_send_json_success(['message' => __('Demo content reset successfully', 'aqualuxe')]);
        } else {
            wp_send_json_error(['message' => __('Failed to reset demo content', 'aqualuxe')]);
        }
    }
    
    /**
     * Content methods - return sample content
     */
    
    private function get_homepage_content() {
        return '
        <!-- Hero Section -->
        <section class="hero bg-gradient-to-r from-primary-500 to-secondary-500 text-white py-20">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-5xl font-bold mb-6">Welcome to AquaLuxe</h1>
                <p class="text-xl mb-8">Experience the perfect blend of elegance and functionality</p>
                <a href="/about/" class="btn btn-large btn-white">Get Started</a>
            </div>
        </section>
        
        <!-- Features Section -->
        <section class="features py-16 bg-gray-50 dark:bg-gray-900">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center mb-12">Why Choose AquaLuxe?</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="feature text-center">
                        <div class="feature-icon w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">User-Friendly</h3>
                        <p class="text-gray-600 dark:text-gray-400">Intuitive design that puts user experience first</p>
                    </div>
                    <div class="feature text-center">
                        <div class="feature-icon w-16 h-16 bg-secondary-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">Responsive</h3>
                        <p class="text-gray-600 dark:text-gray-400">Perfect on all devices, from mobile to desktop</p>
                    </div>
                    <div class="feature text-center">
                        <div class="feature-icon w-16 h-16 bg-accent-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">High Performance</h3>
                        <p class="text-gray-600 dark:text-gray-400">Optimized for speed and search engines</p>
                    </div>
                </div>
            </div>
        </section>
        ';
    }
    
    private function get_about_content() {
        return '
        <h1>About AquaLuxe</h1>
        
        <p>AquaLuxe represents the pinnacle of modern web design, combining aesthetic excellence with powerful functionality. Our theme is crafted for businesses and individuals who demand nothing less than perfection in their online presence.</p>
        
        <h2>Our Philosophy</h2>
        
        <p>We believe that great design should be accessible to everyone. That\'s why AquaLuxe comes with intuitive customization options, comprehensive documentation, and world-class support to help you create the website of your dreams.</p>
        
        <h2>Features That Matter</h2>
        
        <ul>
            <li>Responsive design that works on all devices</li>
            <li>SEO optimized for better search rankings</li>
            <li>Fast loading times for optimal user experience</li>
            <li>Customizable color schemes and layouts</li>
            <li>WooCommerce integration for e-commerce</li>
            <li>Multilingual support for global reach</li>
        </ul>
        
        <p>Join thousands of satisfied customers who have chosen AquaLuxe for their websites. Experience the difference that quality design and development can make.</p>
        ';
    }
    
    private function get_contact_content() {
        return '
        <h1>Contact Us</h1>
        
        <p>We\'d love to hear from you. Get in touch with us using the information below or fill out our contact form.</p>
        
        <div class="contact-info grid md:grid-cols-2 gap-8 my-8">
            <div>
                <h3>Get in Touch</h3>
                <p><strong>Email:</strong> info@example.com</p>
                <p><strong>Phone:</strong> (555) 123-4567</p>
                <p><strong>Address:</strong> 123 Main Street, City, State 12345</p>
            </div>
            <div>
                <h3>Business Hours</h3>
                <p><strong>Monday - Friday:</strong> 9:00 AM - 6:00 PM</p>
                <p><strong>Saturday:</strong> 10:00 AM - 4:00 PM</p>
                <p><strong>Sunday:</strong> Closed</p>
            </div>
        </div>
        
        <h3>Send us a Message</h3>
        
        [contact-form-7 id="1" title="Contact form 1"]
        ';
    }
    
    private function get_privacy_content() {
        return '
        <h1>Privacy Policy</h1>
        
        <p><em>Last updated: ' . date('F j, Y') . '</em></p>
        
        <h2>Information We Collect</h2>
        
        <p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support.</p>
        
        <h2>How We Use Your Information</h2>
        
        <p>We use the information we collect to provide, maintain, and improve our services, process transactions, and communicate with you.</p>
        
        <h2>Information Sharing</h2>
        
        <p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.</p>
        
        <h2>Data Security</h2>
        
        <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
        
        <h2>Contact Us</h2>
        
        <p>If you have any questions about this Privacy Policy, please contact us at privacy@example.com.</p>
        ';
    }
    
    private function get_terms_content() {
        return '
        <h1>Terms of Service</h1>
        
        <p><em>Last updated: ' . date('F j, Y') . '</em></p>
        
        <h2>Acceptance of Terms</h2>
        
        <p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p>
        
        <h2>Use License</h2>
        
        <p>Permission is granted to temporarily download one copy of the materials on this website for personal, non-commercial transitory viewing only.</p>
        
        <h2>Disclaimer</h2>
        
        <p>The materials on this website are provided on an \'as is\' basis. We make no warranties, expressed or implied, and hereby disclaim and negate all other warranties including without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>
        
        <h2>Limitations</h2>
        
        <p>In no event shall our company or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on this website.</p>
        
        <h2>Contact Information</h2>
        
        <p>If you have any questions about these Terms of Service, please contact us at legal@example.com.</p>
        ';
    }
    
    private function get_welcome_post_content() {
        return '
        <p>Welcome to your new AquaLuxe website! This is your first blog post, and it\'s here to help you get started with your new theme.</p>
        
        <h2>What Makes AquaLuxe Special?</h2>
        
        <p>AquaLuxe is more than just a beautiful theme – it\'s a complete solution for modern websites. Whether you\'re building a business site, an online store, or a personal blog, AquaLuxe provides all the tools you need to succeed.</p>
        
        <h3>Key Features:</h3>
        
        <ul>
            <li><strong>Responsive Design:</strong> Your site will look perfect on all devices</li>
            <li><strong>SEO Optimized:</strong> Built-in SEO features help improve your search rankings</li>
            <li><strong>Fast Performance:</strong> Optimized code ensures lightning-fast loading times</li>
            <li><strong>Easy Customization:</strong> Customize colors, fonts, and layouts with ease</li>
            <li><strong>WooCommerce Ready:</strong> Start selling online immediately</li>
        </ul>
        
        <h2>Getting Started</h2>
        
        <p>To customize your site, visit the WordPress Customizer or check out our comprehensive documentation. If you need help, our support team is always ready to assist you.</p>
        
        <p>Thank you for choosing AquaLuxe. We\'re excited to see what you\'ll create!</p>
        ';
    }
    
    private function get_getting_started_content() {
        return '
        <p>Congratulations on choosing AquaLuxe for your website! This guide will help you get the most out of your new theme.</p>
        
        <h2>Step 1: Customize Your Site</h2>
        
        <p>Start by visiting <strong>Appearance > Customize</strong> in your WordPress admin. Here you can:</p>
        
        <ul>
            <li>Upload your logo</li>
            <li>Choose your color scheme</li>
            <li>Set up your homepage layout</li>
            <li>Configure your navigation menus</li>
        </ul>
        
        <h2>Step 2: Create Your Content</h2>
        
        <p>Begin adding your own content by creating pages and posts. Don\'t forget to:</p>
        
        <ul>
            <li>Add featured images to your posts</li>
            <li>Create compelling page titles and descriptions</li>
            <li>Use the built-in page builder elements</li>
            <li>Optimize your content for search engines</li>
        </ul>
        
        <h2>Step 3: Configure Plugins</h2>
        
        <p>AquaLuxe works great with popular WordPress plugins. Consider installing:</p>
        
        <ul>
            <li>Contact Form 7 for contact forms</li>
            <li>Yoast SEO for advanced SEO features</li>
            <li>WooCommerce if you plan to sell products</li>
            <li>Jetpack for additional functionality</li>
        </ul>
        
        <h2>Need Help?</h2>
        
        <p>Check out our documentation or contact our support team if you have any questions. We\'re here to help you succeed!</p>
        ';
    }
    
    private function get_web_design_content() {
        return '
        <p>Modern web design has evolved dramatically over the past decade. Today\'s websites need to be fast, accessible, and beautiful across all devices.</p>
        
        <h2>The Principles of Modern Design</h2>
        
        <h3>1. Mobile-First Approach</h3>
        
        <p>With mobile traffic accounting for over 50% of web usage, designing for mobile devices first is no longer optional – it\'s essential.</p>
        
        <h3>2. Performance Optimization</h3>
        
        <p>Users expect websites to load in under 3 seconds. Performance optimization includes:</p>
        
        <ul>
            <li>Image optimization and lazy loading</li>
            <li>Minified CSS and JavaScript</li>
            <li>Efficient caching strategies</li>
            <li>Content Delivery Networks (CDNs)</li>
        </ul>
        
        <h3>3. Accessibility</h3>
        
        <p>Great design is inclusive design. Modern websites must be accessible to users with disabilities, following WCAG guidelines.</p>
        
        <h3>4. User Experience (UX)</h3>
        
        <p>Every design decision should prioritize the user experience. This includes:</p>
        
        <ul>
            <li>Intuitive navigation</li>
            <li>Clear call-to-action buttons</li>
            <li>Consistent design patterns</li>
            <li>Fast and efficient user flows</li>
        </ul>
        
        <h2>How AquaLuxe Implements These Principles</h2>
        
        <p>AquaLuxe is built with all these modern design principles in mind. From its mobile-first responsive design to its performance-optimized code, every aspect of the theme is crafted to deliver an exceptional user experience.</p>
        
        <p>Experience the future of web design with AquaLuxe.</p>
        ';
    }
}
