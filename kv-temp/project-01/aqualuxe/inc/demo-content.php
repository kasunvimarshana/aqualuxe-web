<?php
/**
 * Demo Content Import Functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Demo_Content {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_ajax_aqualuxe_import_demo', array($this, 'import_demo_content'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            __('AquaLuxe Demo Import', 'aqualuxe'),
            __('Demo Import', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-import',
            array($this, 'demo_import_page')
        );
    }
    
    /**
     * Demo import page
     */
    public function demo_import_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('AquaLuxe Demo Content Import', 'aqualuxe'); ?></h1>
            <div class="notice notice-info">
                <p><?php echo esc_html__('This will import demo content including products, pages, and customizer settings. This action cannot be undone easily.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="demo-import-section">
                <h2><?php echo esc_html__('Import Demo Content', 'aqualuxe'); ?></h2>
                <p><?php echo esc_html__('Click the button below to import demo content for your AquaLuxe ornamental fish store.', 'aqualuxe'); ?></p>
                
                <button id="import-demo-btn" class="button button-primary button-large">
                    <?php echo esc_html__('Import Demo Content', 'aqualuxe'); ?>
                </button>
                
                <div id="import-progress" style="display: none;">
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                    <p id="import-status"><?php echo esc_html__('Starting import...', 'aqualuxe'); ?></p>
                </div>
            </div>
        </div>
        
        <style>
            .demo-import-section {
                background: #fff;
                padding: 20px;
                border: 1px solid #ccd0d4;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
                margin-top: 20px;
            }
            .progress-bar {
                width: 100%;
                height: 20px;
                background-color: #f1f1f1;
                border-radius: 10px;
                overflow: hidden;
                margin: 20px 0;
            }
            .progress-fill {
                height: 100%;
                background-color: #0073aa;
                width: 0%;
                transition: width 0.3s ease;
            }
        </style>
        
        <script>
            jQuery(document).ready(function($) {
                $('#import-demo-btn').on('click', function() {
                    var button = $(this);
                    button.prop('disabled', true);
                    $('#import-progress').show();
                    
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_import_demo',
                            nonce: '<?php echo wp_create_nonce('aqualuxe_demo_import'); ?>'
                        },
                        success: function(response) {
                            if (response.success) {
                                $('.progress-fill').css('width', '100%');
                                $('#import-status').text('<?php echo esc_html__('Demo content imported successfully!', 'aqualuxe'); ?>');
                            } else {
                                $('#import-status').text('<?php echo esc_html__('Import failed: ', 'aqualuxe'); ?>' + response.data);
                            }
                        },
                        error: function() {
                            $('#import-status').text('<?php echo esc_html__('Import failed due to server error.', 'aqualuxe'); ?>');
                        },
                        complete: function() {
                            button.prop('disabled', false);
                        }
                    });
                });
            });
        </script>
        <?php
    }
    
    /**
     * Import demo content via AJAX
     */
    public function import_demo_content() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_demo_import')) {
            wp_send_json_error(__('Security check failed', 'aqualuxe'));
        }
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Insufficient permissions', 'aqualuxe'));
        }
        
        try {
            // Import demo pages
            $this->import_pages();
            
            // Import demo products
            $this->import_products();
            
            // Import demo categories
            $this->import_categories();
            
            // Set up menus
            $this->setup_menus();
            
            // Configure customizer settings
            $this->configure_customizer();
            
            // Configure WooCommerce settings
            $this->configure_woocommerce();
            
            wp_send_json_success(__('Demo content imported successfully!', 'aqualuxe'));
            
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }
    
    /**
     * Import demo pages
     */
    private function import_pages() {
        $pages = array(
            array(
                'post_title' => 'Home',
                'post_content' => $this->get_home_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_slug' => 'home'
            ),
            array(
                'post_title' => 'About Us',
                'post_content' => $this->get_about_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_slug' => 'about'
            ),
            array(
                'post_title' => 'Contact Us',
                'post_content' => $this->get_contact_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_slug' => 'contact'
            ),
            array(
                'post_title' => 'Fish Care Guide',
                'post_content' => $this->get_care_guide_content(),
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_slug' => 'fish-care-guide'
            )
        );
        
        foreach ($pages as $page_data) {
            // Check if page already exists
            $existing_page = get_page_by_path($page_data['post_slug']);
            if (!$existing_page) {
                $page_id = wp_insert_post($page_data);
                
                if (is_wp_error($page_id)) {
                    throw new Exception('Failed to create page: ' . $page_data['post_title']);
                }
                
                // Set home page as front page
                if ($page_data['post_slug'] === 'home') {
                    update_option('page_on_front', $page_id);
                    update_option('show_on_front', 'page');
                }
            }
        }
    }
    
    /**
     * Import demo products
     */
    private function import_products() {
        $products = $this->get_demo_products_data();
        
        foreach ($products as $product_data) {
            // Check if product already exists
            $existing_product = wc_get_product_id_by_sku($product_data['sku']);
            if (!$existing_product) {
                $product = new WC_Product_Simple();
                
                $product->set_name($product_data['name']);
                $product->set_description($product_data['description']);
                $product->set_short_description($product_data['short_description']);
                $product->set_sku($product_data['sku']);
                $product->set_price($product_data['price']);
                $product->set_regular_price($product_data['price']);
                $product->set_manage_stock(true);
                $product->set_stock_quantity($product_data['stock']);
                $product->set_stock_status('instock');
                $product->set_catalog_visibility('visible');
                $product->set_status('publish');
                
                // Set custom meta
                if (isset($product_data['fish_origin'])) {
                    $product->update_meta_data('_fish_origin', $product_data['fish_origin']);
                }
                if (isset($product_data['fish_size'])) {
                    $product->update_meta_data('_fish_size', $product_data['fish_size']);
                }
                if (isset($product_data['care_level'])) {
                    $product->update_meta_data('_care_level', $product_data['care_level']);
                }
                
                $product_id = $product->save();
                
                if (is_wp_error($product_id)) {
                    throw new Exception('Failed to create product: ' . $product_data['name']);
                }
                
                // Set product categories
                if (isset($product_data['categories'])) {
                    wp_set_object_terms($product_id, $product_data['categories'], 'product_cat');
                }
            }
        }
    }
    
    /**
     * Import demo categories
     */
    private function import_categories() {
        $categories = array(
            array(
                'name' => 'Tropical Fish',
                'slug' => 'tropical-fish',
                'description' => 'Beautiful tropical fish for warm water aquariums'
            ),
            array(
                'name' => 'Goldfish',
                'slug' => 'goldfish',
                'description' => 'Classic goldfish varieties for cold water tanks'
            ),
            array(
                'name' => 'Betta Fish',
                'slug' => 'betta-fish',
                'description' => 'Stunning betta fish with vibrant colors'
            ),
            array(
                'name' => 'Marine Fish',
                'slug' => 'marine-fish',
                'description' => 'Saltwater fish for marine aquariums'
            ),
            array(
                'name' => 'Aquarium Supplies',
                'slug' => 'aquarium-supplies',
                'description' => 'Everything you need for your aquarium'
            )
        );
        
        foreach ($categories as $cat_data) {
            // Check if category exists
            $existing_term = term_exists($cat_data['slug'], 'product_cat');
            if (!$existing_term) {
                wp_insert_term(
                    $cat_data['name'],
                    'product_cat',
                    array(
                        'slug' => $cat_data['slug'],
                        'description' => $cat_data['description']
                    )
                );
            }
        }
    }
    
    /**
     * Setup demo menus
     */
    private function setup_menus() {
        // Create primary menu
        $menu_name = 'AquaLuxe Main Menu';
        $menu_exists = wp_get_nav_menu_object($menu_name);
        
        if (!$menu_exists) {
            $menu_id = wp_create_nav_menu($menu_name);
            
            // Add menu items
            $menu_items = array(
                array(
                    'title' => 'Home',
                    'url' => home_url('/'),
                    'menu-item-status' => 'publish'
                ),
                array(
                    'title' => 'Shop',
                    'url' => wc_get_page_permalink('shop'),
                    'menu-item-status' => 'publish'
                ),
                array(
                    'title' => 'About',
                    'url' => home_url('/about/'),
                    'menu-item-status' => 'publish'
                ),
                array(
                    'title' => 'Fish Care Guide',
                    'url' => home_url('/fish-care-guide/'),
                    'menu-item-status' => 'publish'
                ),
                array(
                    'title' => 'Contact',
                    'url' => home_url('/contact/'),
                    'menu-item-status' => 'publish'
                )
            );
            
            foreach ($menu_items as $item) {
                wp_update_nav_menu_item($menu_id, 0, $item);
            }
            
            // Assign menu to location
            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }
    
    /**
     * Configure customizer settings
     */
    private function configure_customizer() {
        set_theme_mod('aqualuxe_primary_color', '#0066cc');
        set_theme_mod('aqualuxe_secondary_color', '#00ccaa');
        set_theme_mod('aqualuxe_hero_title', 'Premium Ornamental Fish Collection');
        set_theme_mod('aqualuxe_hero_subtitle', 'Discover the beauty and elegance of premium ornamental fish for your aquarium');
        set_theme_mod('aqualuxe_hero_button_text', 'Shop Now');
        set_theme_mod('aqualuxe_hero_button_url', wc_get_page_permalink('shop'));
    }
    
    /**
     * Configure WooCommerce settings
     */
    private function configure_woocommerce() {
        // Set up WooCommerce pages
        $shop_page = get_page_by_path('shop');
        $cart_page = get_page_by_path('cart');
        $checkout_page = get_page_by_path('checkout');
        $account_page = get_page_by_path('my-account');
        
        if ($shop_page) {
            update_option('woocommerce_shop_page_id', $shop_page->ID);
        }
        if ($cart_page) {
            update_option('woocommerce_cart_page_id', $cart_page->ID);
        }
        if ($checkout_page) {
            update_option('woocommerce_checkout_page_id', $checkout_page->ID);
        }
        if ($account_page) {
            update_option('woocommerce_myaccount_page_id', $account_page->ID);
        }
        
        // Set currency and other settings
        update_option('woocommerce_currency', 'USD');
        update_option('woocommerce_price_thousand_sep', ',');
        update_option('woocommerce_price_decimal_sep', '.');
        update_option('woocommerce_price_num_decimals', 2);
        update_option('woocommerce_dimension_unit', 'cm');
        update_option('woocommerce_weight_unit', 'g');
        
        // Enable reviews
        update_option('woocommerce_enable_reviews', 'yes');
        update_option('woocommerce_review_rating_verification_required', 'no');
    }
    
    /**
     * Get home page content
     */
    private function get_home_content() {
        return '
            <h2>Welcome to AquaLuxe</h2>
            <p>Your premier destination for ornamental fish and aquarium supplies. We specialize in providing high-quality, healthy fish that will bring beauty and tranquility to your home or office aquarium.</p>
            
            <h3>Featured Categories</h3>
            <div class="featured-categories">
                <div class="category-item">
                    <h4>Tropical Fish</h4>
                    <p>Vibrant tropical species from around the world</p>
                </div>
                <div class="category-item">
                    <h4>Marine Fish</h4>
                    <p>Stunning saltwater fish for marine enthusiasts</p>
                </div>
                <div class="category-item">
                    <h4>Goldfish Varieties</h4>
                    <p>Classic and rare goldfish breeds</p>
                </div>
            </div>
        ';
    }
    
    /**
     * Get about page content
     */
    private function get_about_content() {
        return '
            <h2>About AquaLuxe</h2>
            <p>AquaLuxe has been a trusted name in the ornamental fish industry for over 15 years. We are passionate about providing aquarium enthusiasts with the finest selection of healthy, beautiful fish and the expert guidance needed to create thriving aquatic environments.</p>
            
            <h3>Our Mission</h3>
            <p>To bring the beauty and serenity of aquatic life into every home, while promoting responsible fishkeeping practices and environmental conservation.</p>
            
            <h3>Why Choose AquaLuxe?</h3>
            <ul>
                <li>Premium quality fish from certified breeders</li>
                <li>Expert care and quarantine procedures</li>
                <li>Comprehensive fish care guidance</li>
                <li>Satisfaction guarantee on all livestock</li>
                <li>Fast and secure shipping worldwide</li>
            </ul>
        ';
    }
    
    /**
     * Get contact page content
     */
    private function get_contact_content() {
        return '
            <h2>Contact AquaLuxe</h2>
            <p>We\'re here to help you create the perfect aquarium. Get in touch with our expert team for personalized advice and support.</p>
            
            <div class="contact-info">
                <div class="contact-item">
                    <h4>Phone</h4>
                    <p>+1 (555) 123-FISH</p>
                </div>
                <div class="contact-item">
                    <h4>Email</h4>
                    <p>info@aqualuxe.com</p>
                </div>
                <div class="contact-item">
                    <h4>Address</h4>
                    <p>123 Aquarium Lane<br>Fish City, FC 12345</p>
                </div>
                <div class="contact-item">
                    <h4>Store Hours</h4>
                    <p>Monday - Friday: 9AM - 8PM<br>Saturday: 9AM - 6PM<br>Sunday: 11AM - 5PM</p>
                </div>
            </div>
        ';
    }
    
    /**
     * Get care guide content
     */
    private function get_care_guide_content() {
        return '
            <h2>Fish Care Guide</h2>
            <p>Proper care is essential for healthy, happy fish. Follow our comprehensive guide to ensure your aquatic pets thrive.</p>
            
            <h3>Water Quality</h3>
            <p>Maintaining proper water parameters is crucial for fish health. Regular testing and water changes are essential.</p>
            
            <h3>Feeding Guidelines</h3>
            <p>Feed your fish the right amount and type of food for their species. Overfeeding is one of the most common mistakes.</p>
            
            <h3>Tank Setup</h3>
            <p>Create an environment that mimics your fish\'s natural habitat with appropriate decorations, plants, and hiding spots.</p>
            
            <h3>Common Health Issues</h3>
            <p>Learn to recognize signs of illness and know when to seek professional help for your fish.</p>
        ';
    }
    
    /**
     * Get demo products data
     */
    private function get_demo_products_data() {
        return array(
            array(
                'name' => 'Premium Angelfish',
                'description' => 'Beautiful angelfish with stunning coloration and graceful fins. Perfect centerpiece for community aquariums.',
                'short_description' => 'Elegant angelfish for community tanks',
                'sku' => 'ANGEL-001',
                'price' => '24.99',
                'stock' => 15,
                'fish_origin' => 'South America',
                'fish_size' => '15',
                'care_level' => 'intermediate',
                'categories' => array('tropical-fish')
            ),
            array(
                'name' => 'Neon Tetra School (6 fish)',
                'description' => 'Vibrant neon tetras that create stunning schools. These peaceful fish add brilliant color to any freshwater aquarium.',
                'short_description' => 'Colorful schooling fish',
                'sku' => 'NEON-006',
                'price' => '18.99',
                'stock' => 25,
                'fish_origin' => 'South America',
                'fish_size' => '4',
                'care_level' => 'beginner',
                'categories' => array('tropical-fish')
            ),
            array(
                'name' => 'Betta Splendens Male',
                'description' => 'Magnificent male betta with flowing fins and brilliant colors. Each fish is unique and hand-selected for quality.',
                'short_description' => 'Stunning male betta fish',
                'sku' => 'BETTA-M01',
                'price' => '12.99',
                'stock' => 30,
                'fish_origin' => 'Thailand',
                'fish_size' => '6',
                'care_level' => 'beginner',
                'categories' => array('betta-fish')
            ),
            array(
                'name' => 'Oranda Goldfish',
                'description' => 'Premium oranda goldfish with distinctive head growth (wen). These fancy goldfish are perfect for cold water aquariums.',
                'short_description' => 'Fancy goldfish with unique head cap',
                'sku' => 'GOLD-OR01',
                'price' => '32.99',
                'stock' => 12,
                'fish_origin' => 'China',
                'fish_size' => '20',
                'care_level' => 'intermediate',
                'categories' => array('goldfish')
            ),
            array(
                'name' => 'Clownfish Pair',
                'description' => 'Captive-bred clownfish pair, perfect for marine reef aquariums. These hardy fish are ideal for saltwater beginners.',
                'short_description' => 'Marine clownfish breeding pair',
                'sku' => 'CLOWN-P01',
                'price' => '89.99',
                'stock' => 8,
                'fish_origin' => 'Captive Bred',
                'fish_size' => '8',
                'care_level' => 'beginner',
                'categories' => array('marine-fish')
            ),
            array(
                'name' => 'Premium Fish Food Mix',
                'description' => 'High-quality fish food blend with essential nutrients for optimal health and color enhancement.',
                'short_description' => 'Nutritious fish food blend',
                'sku' => 'FOOD-MIX01',
                'price' => '15.99',
                'stock' => 50,
                'categories' => array('aquarium-supplies')
            )
        );
    }
}

// Initialize demo content import
new AquaLuxe_Demo_Content();