<?php
/**
 * Demo Content - Luxury Ornamental Fish Theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('aqualuxe_import_demo_content')) {
    /**
     * Import demo content for the theme.
     *
     * @since 1.0.0
     */
    function aqualuxe_import_demo_content() {
        // Check if demo content has already been imported
        if (get_option('aqualuxe_demo_content_imported')) {
            return;
        }
        
        // Import pages
        aqualuxe_import_demo_pages();
        
        // Import posts
        aqualuxe_import_demo_posts();
        
        // Import products
        aqualuxe_import_demo_products();
        
        // Import widgets
        aqualuxe_import_demo_widgets();
        
        // Import menus
        aqualuxe_import_demo_menus();
        
        // Set homepage and posts page
        aqualuxe_set_demo_front_page();
        
        // Set theme options
        aqualuxe_set_demo_theme_options();
        
        // Mark demo content as imported
        update_option('aqualuxe_demo_content_imported', true);
    }
}

if (!function_exists('aqualuxe_import_demo_pages')) {
    /**
     * Import demo pages.
     *
     * @since 1.0.0
     */
    function aqualuxe_import_demo_pages() {
        $pages = array(
            array(
                'title' => 'Home',
                'content' => '[aqualuxe_homepage]',
                'template' => 'template-homepage.php'
            ),
            array(
                'title' => 'About Us',
                'content' => 'Welcome to AquaLuxe, your premier destination for luxury ornamental fish. We specialize in providing the finest quality fish for discerning collectors and enthusiasts.',
                'template' => 'page.php'
            ),
            array(
                'title' => 'Contact',
                'content' => '[contact-form-7 id="123" title="Contact form 1"]',
                'template' => 'page.php'
            ),
            array(
                'title' => 'Blog',
                'content' => '',
                'template' => 'index.php'
            ),
            array(
                'title' => 'Shop',
                'content' => '[woocommerce_shop]',
                'template' => 'page.php'
            )
        );
        
        foreach ($pages as $page_data) {
            $page_id = wp_insert_post(array(
                'post_title' => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'page_template' => $page_data['template']
            ));
            
            if (!is_wp_error($page_id)) {
                update_post_meta($page_id, '_wp_page_template', $page_data['template']);
            }
        }
    }
}

if (!function_exists('aqualuxe_import_demo_posts')) {
    /**
     * Import demo posts.
     *
     * @since 1.0.0
     */
    function aqualuxe_import_demo_posts() {
        $posts = array(
            array(
                'title' => 'The Art of Aquarium Design',
                'content' => 'Creating a beautiful aquarium is an art form that requires careful planning and attention to detail. In this post, we explore the principles of aquarium design and how to create a stunning underwater landscape.',
                'excerpt' => 'Learn the principles of aquarium design and how to create a stunning underwater landscape.'
            ),
            array(
                'title' => 'Caring for Your New Fish',
                'content' => 'Bringing a new fish home is exciting, but it also comes with responsibilities. Proper care is essential for the health and well-being of your aquatic pets. Here are some tips to help you get started.',
                'excerpt' => 'Essential tips for caring for your new aquatic pets.'
            ),
            array(
                'title' => 'Rare Fish Species Available Now',
                'content' => 'We\'re excited to announce the arrival of several rare fish species in our collection. These beautiful specimens are available for a limited time, so don\'t miss your chance to add them to your collection.',
                'excerpt' => 'Limited availability of rare fish species in our collection.'
            )
        );
        
        foreach ($posts as $post_data) {
            wp_insert_post(array(
                'post_title' => $post_data['title'],
                'post_content' => $post_data['content'],
                'post_excerpt' => $post_data['excerpt'],
                'post_status' => 'publish',
                'post_type' => 'post'
            ));
        }
    }
}

if (!function_exists('aqualuxe_import_demo_products')) {
    /**
     * Import demo products.
     *
     * @since 1.0.0
     */
    function aqualuxe_import_demo_products() {
        if (!class_exists('WC_Product')) {
            return;
        }
        
        $products = array(
            array(
                'name' => 'Premium Discus Fish',
                'description' => 'Beautiful, healthy Discus fish with vibrant colors and perfect markings. These fish are bred in our facility and are guaranteed to be disease-free.',
                'price' => 49.99,
                'sku' => 'DISC-001',
                'stock' => 10,
                'category' => 'Discus'
            ),
            array(
                'name' => 'Rare Angelfish',
                'description' => 'Exquisite Angelfish with unique color patterns. These fish are selectively bred for their beauty and are perfect for any premium aquarium.',
                'price' => 39.99,
                'sku' => 'ANGEL-001',
                'stock' => 15,
                'category' => 'Angelfish'
            ),
            array(
                'name' => 'Premium Goldfish',
                'description' => 'High-quality Goldfish with flowing fins and vibrant colors. These fish are perfect for both beginners and experienced aquarists.',
                'price' => 19.99,
                'sku' => 'GOLD-001',
                'stock' => 20,
                'category' => 'Goldfish'
            )
        );
        
        foreach ($products as $product_data) {
            $product = new WC_Product();
            $product->set_name($product_data['name']);
            $product->set_description($product_data['description']);
            $product->set_price($product_data['price']);
            $product->set_regular_price($product_data['price']);
            $product->set_sku($product_data['sku']);
            $product->set_stock_quantity($product_data['stock']);
            $product->set_manage_stock(true);
            $product->set_stock_status('instock');
            
            // Set category
            $category = wp_insert_term($product_data['category'], 'product_cat');
            if (!is_wp_error($category)) {
                $product->set_category_ids(array($category['term_id']));
            }
            
            $product->save();
        }
    }
}

if (!function_exists('aqualuxe_import_demo_widgets')) {
    /**
     * Import demo widgets.
     *
     * @since 1.0.0
     */
    function aqualuxe_import_demo_widgets() {
        $widgets = array(
            'sidebar-1' => array(
                array(
                    'type' => 'text',
                    'title' => 'About AquaLuxe',
                    'content' => 'We are passionate about providing the finest quality ornamental fish to collectors and enthusiasts around the world.'
                ),
                array(
                    'type' => 'search',
                    'title' => 'Search'
                ),
                array(
                    'type' => 'recent-posts',
                    'title' => 'Recent Posts'
                )
            ),
            'footer-1' => array(
                array(
                    'type' => 'text',
                    'title' => 'Contact Info',
                    'content' => '123 Aquarium Street, Fishville, FV 12345<br>Phone: (123) 456-7890<br>Email: info@aqualuxe.com'
                )
            ),
            'footer-2' => array(
                array(
                    'type' => 'nav_menu',
                    'title' => 'Quick Links',
                    'menu' => 'footer'
                )
            ),
            'footer-3' => array(
                array(
                    'type' => 'text',
                    'title' => 'Business Hours',
                    'content' => 'Monday-Friday: 9am-6pm<br>Saturday: 10am-4pm<br>Sunday: Closed'
                )
            )
        );
        
        foreach ($widgets as $sidebar => $sidebar_widgets) {
            foreach ($sidebar_widgets as $widget_data) {
                $widget_id = $widget_data['type'];
                $widget_options = get_option('widget_' . $widget_id, array());
                $widget_number = count($widget_options) + 1;
                
                $widget_options[$widget_number] = array(
                    'title' => $widget_data['title']
                );
                
                if (isset($widget_data['content'])) {
                    $widget_options[$widget_number]['text'] = $widget_data['content'];
                }
                
                update_option('widget_' . $widget_id, $widget_options);
                
                // Add widget to sidebar
                $sidebars_widgets = get_option('sidebars_widgets', array());
                $sidebars_widgets[$sidebar][] = $widget_id . '-' . $widget_number;
                update_option('sidebars_widgets', $sidebars_widgets);
            }
        }
    }
}

if (!function_exists('aqualuxe_import_demo_menus')) {
    /**
     * Import demo menus.
     *
     * @since 1.0.0
     */
    function aqualuxe_import_demo_menus() {
        $menus = array(
            'primary' => array(
                'Home' => '/',
                'Shop' => '/shop/',
                'About Us' => '/about-us/',
                'Blog' => '/blog/',
                'Contact' => '/contact/'
            ),
            'footer' => array(
                'Privacy Policy' => '/privacy-policy/',
                'Terms of Service' => '/terms-of-service/',
                'FAQ' => '/faq/'
            )
        );
        
        foreach ($menus as $menu_name => $menu_items) {
            // Create menu
            $menu_id = wp_create_nav_menu($menu_name);
            
            if (!is_wp_error($menu_id)) {
                // Add menu items
                foreach ($menu_items as $title => $url) {
                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title' => $title,
                        'menu-item-url' => $url,
                        'menu-item-status' => 'publish'
                    ));
                }
                
                // Assign menu to location
                $locations = get_theme_mod('nav_menu_locations');
                $locations[$menu_name] = $menu_id;
                set_theme_mod('nav_menu_locations', $locations);
            }
        }
    }
}

if (!function_exists('aqualuxe_set_demo_front_page')) {
    /**
     * Set the demo front page.
     *
     * @since 1.0.0
     */
    function aqualuxe_set_demo_front_page() {
        // Get the Home page
        $home_page = get_page_by_title('Home');
        
        if ($home_page) {
            // Set front page
            update_option('page_on_front', $home_page->ID);
            update_option('show_on_front', 'page');
        }
    }
}

if (!function_exists('aqualuxe_set_demo_theme_options')) {
    /**
     * Set demo theme options.
     *
     * @since 1.0.0
     */
    function aqualuxe_set_demo_theme_options() {
        // Set theme options
        set_theme_mod('aqualuxe_sticky_header', true);
        set_theme_mod('aqualuxe_quick_view', true);
        set_theme_mod('aqualuxe_product_hover_effect', true);
        set_theme_mod('aqualuxe_copyright_text', 'Copyright &copy; [year] [sitename]. All rights reserved.');
        set_theme_mod('aqualuxe_primary_color', '#00a896');
        set_theme_mod('aqualuxe_secondary_color', '#025951');
        set_theme_mod('aqualuxe_accent_color', '#f0c808');
    }
}

if (!function_exists('aqualuxe_add_demo_content_menu')) {
    /**
     * Add demo content import menu to admin.
     *
     * @since 1.0.0
     */
    function aqualuxe_add_demo_content_menu() {
        add_theme_page(
            __('Import Demo Content', 'aqualuxe'),
            __('Import Demo Content', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-content',
            'aqualuxe_demo_content_page'
        );
    }
}
add_action('admin_menu', 'aqualuxe_add_demo_content_menu');

if (!function_exists('aqualuxe_demo_content_page')) {
    /**
     * Display demo content import page.
     *
     * @since 1.0.0
     */
    function aqualuxe_demo_content_page() {
        if (isset($_POST['import_demo_content'])) {
            aqualuxe_import_demo_content();
            echo '<div class="notice notice-success"><p>' . __('Demo content imported successfully!', 'aqualuxe') . '</p></div>';
        }
        
        ?>
        <div class="wrap">
            <h1><?php _e('Import Demo Content', 'aqualuxe'); ?></h1>
            <p><?php _e('This will import demo content including pages, posts, products, widgets, and menus.', 'aqualuxe'); ?></p>
            <form method="post">
                <?php wp_nonce_field('import_demo_content', 'import_demo_content_nonce'); ?>
                <input type="submit" name="import_demo_content" class="button button-primary" value="<?php _e('Import Demo Content', 'aqualuxe'); ?>">
            </form>
        </div>
        <?php
    }
}