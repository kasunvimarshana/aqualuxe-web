<?php

/**
 * Demo Content System
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Add demo content import functionality
add_action('admin_menu', 'aqualuxe_demo_content_menu');
if (!function_exists('aqualuxe_demo_content_menu')) {
    /**
     * Add demo content menu to admin
     *
     * @since 1.0.0
     */
    function aqualuxe_demo_content_menu()
    {
        add_theme_page(
            esc_html__('Import Demo Content', 'aqualuxe'),
            esc_html__('Import Demo Content', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-content',
            'aqualuxe_demo_content_page'
        );
    }
}

if (!function_exists('aqualuxe_demo_content_page')) {
    /**
     * Demo content import page
     *
     * @since 1.0.0
     */
    function aqualuxe_demo_content_page()
    {
?>
        <div class="wrap">
            <h1><?php esc_html_e('Import Demo Content', 'aqualuxe'); ?></h1>

            <?php if (isset($_POST['import_demo_content'])) : ?>
                <?php
                // Verify nonce
                if (!wp_verify_nonce($_POST['aqualuxe_demo_nonce'], 'import_demo_content')) {
                    wp_die(esc_html__('Nonce verification failed', 'aqualuxe'));
                }

                // Check user capabilities
                if (!current_user_can('manage_options')) {
                    wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'aqualuxe'));
                }

                // Import demo content
                $result = aqualuxe_import_demo_content();

                if ($result === true) {
                    echo '<div class="notice notice-success"><p>' . esc_html__('Demo content imported successfully!', 'aqualuxe') . '</p></div>';
                } else {
                    echo '<div class="notice notice-error"><p>' . esc_html__('Error importing demo content: ', 'aqualuxe') . esc_html($result) . '</p></div>';
                }
                ?>
            <?php endif; ?>

            <p><?php esc_html_e('This will import demo content including pages, posts, products, and menu items to help you get started with your AquaLuxe theme.', 'aqualuxe'); ?></p>

            <form method="post" action="">
                <?php wp_nonce_field('import_demo_content', 'aqualuxe_demo_nonce'); ?>
                <?php submit_button(esc_html__('Import Demo Content', 'aqualuxe'), 'primary', 'import_demo_content'); ?>
            </form>
        </div>
<?php
    }
}

if (!function_exists('aqualuxe_import_demo_content')) {
    /**
     * Import demo content
     *
     * @since 1.0.0
     */
    function aqualuxe_import_demo_content()
    {
        try {
            // Import pages
            aqualuxe_import_demo_pages();

            // Import posts
            aqualuxe_import_demo_posts();

            // Import products (if WooCommerce is active)
            if (class_exists('WooCommerce')) {
                aqualuxe_import_demo_products();
            }

            // Import menu
            aqualuxe_import_demo_menu();

            // Set front page
            aqualuxe_set_demo_front_page();

            // Import widgets
            aqualuxe_import_demo_widgets();

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}

if (!function_exists('aqualuxe_import_demo_pages')) {
    /**
     * Import demo pages
     *
     * @since 1.0.0
     */
    function aqualuxe_import_demo_pages()
    {
        $pages = array(
            array(
                'title' => 'Home',
                'content' => '<!-- wp:heading {"level":1} --><h1>Welcome to AquaLuxe</h1><!-- /wp:heading -->
<!-- wp:paragraph --><p>Premium ornamental fish for discerning collectors.</p><!-- /wp:paragraph -->',
                'template' => 'template-homepage.php'
            ),
            array(
                'title' => 'About Us',
                'content' => '<!-- wp:heading --><h2>Our Story</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>Learn about our passion for ornamental fish.</p><!-- /wp:paragraph -->'
            ),
            array(
                'title' => 'Contact',
                'content' => '<!-- wp:heading --><h2>Get in Touch</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>We\'d love to hear from you.</p><!-- /wp:paragraph -->'
            )
        );

        foreach ($pages as $page_data) {
            // Check if page already exists
            $existing_page = get_page_by_path(sanitize_title($page_data['title']));
            if (!$existing_page) {
                $page_id = wp_insert_post(array(
                    'post_title' => $page_data['title'],
                    'post_content' => $page_data['content'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_author' => get_current_user_id()
                ));

                // Set template if specified
                if (isset($page_data['template'])) {
                    update_post_meta($page_id, '_wp_page_template', $page_data['template']);
                }
            }
        }
    }
}

if (!function_exists('aqualuxe_import_demo_posts')) {
    /**
     * Import demo posts
     *
     * @since 1.0.0
     */
    function aqualuxe_import_demo_posts()
    {
        $posts = array(
            array(
                'title' => 'Caring for Your New Fish',
                'content' => '<!-- wp:paragraph --><p>Tips for keeping your ornamental fish healthy and happy.</p><!-- /wp:paragraph -->',
                'excerpt' => 'Essential care tips for ornamental fish'
            ),
            array(
                'title' => 'The Beauty of Discus Fish',
                'content' => '<!-- wp:paragraph --><p>Discover the stunning world of discus fish.</p><!-- /wp:paragraph -->',
                'excerpt' => 'Learn about these beautiful fish'
            )
        );

        foreach ($posts as $post_data) {
            // Check if post already exists
            $existing_post = get_page_by_path(sanitize_title($post_data['title']), OBJECT, 'post');
            if (!$existing_post) {
                wp_insert_post(array(
                    'post_title' => $post_data['title'],
                    'post_content' => $post_data['content'],
                    'post_excerpt' => $post_data['excerpt'],
                    'post_status' => 'publish',
                    'post_type' => 'post',
                    'post_author' => get_current_user_id()
                ));
            }
        }
    }
}

if (!function_exists('aqualuxe_import_demo_products')) {
    /**
     * Import demo products
     *
     * @since 1.0.0
     */
    function aqualuxe_import_demo_products()
    {
        // This is a simplified version - in a real implementation,
        // you would import actual product data

        $products = array(
            array(
                'name' => 'Premium Discus Fish',
                'price' => 49.99,
                'description' => 'Beautiful, healthy discus fish from our premium breeding program.'
            ),
            array(
                'name' => 'Rare Angelfish',
                'price' => 34.99,
                'description' => 'Rare color variants of angelfish, perfect for collectors.'
            )
        );

        foreach ($products as $product_data) {
            // Check if product already exists
            $existing_product = get_page_by_path(sanitize_title($product_data['name']), OBJECT, 'product');
            if (!$existing_product) {
                $product = new WC_Product();
                $product->set_name($product_data['name']);
                $product->set_price($product_data['price']);
                $product->set_regular_price($product_data['price']);
                $product->set_description($product_data['description']);
                $product->set_short_description($product_data['description']);
                $product->set_status('publish');
                $product->save();
            }
        }
    }
}

if (!function_exists('aqualuxe_import_demo_menu')) {
    /**
     * Import demo menu
     *
     * @since 1.0.0
     */
    function aqualuxe_import_demo_menu()
    {
        // Create menu
        $menu_name = 'Main Menu';
        $menu_exists = wp_get_nav_menu_object($menu_name);

        if (!$menu_exists) {
            $menu_id = wp_create_nav_menu($menu_name);

            /*
			// Get pages
            $pages = get_pages();
            
            foreach ($pages as $page) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => $page->post_title,
                    'menu-item-object-id' => $page->ID,
                    'menu-item-object' => 'page',
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish'
                ));
            }
			*/

            // Get pages using WP_Query instead of get_pages()
            $pages_query = new WP_Query(array(
                'post_type' => 'page',
                'post_status' => 'publish',
                'posts_per_page' => -1
            ));

            if ($pages_query->have_posts()) {
                while ($pages_query->have_posts()) {
                    $pages_query->the_post();
                    wp_update_nav_menu_item($menu_id, 0, array(
                        'menu-item-title' => get_the_title(),
                        'menu-item-object-id' => get_the_ID(),
                        'menu-item-object' => 'page',
                        'menu-item-type' => 'post_type',
                        'menu-item-status' => 'publish'
                    ));
                }
                wp_reset_postdata();
            }

            // Assign menu to location
            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }
}

if (!function_exists('aqualuxe_set_demo_front_page')) {
    /**
     * Set demo front page
     *
     * @since 1.0.0
     */
    function aqualuxe_set_demo_front_page()
    {
        /*
		$home_page = get_page_by_title('Home');
        if ($home_page) {
            update_option('page_on_front', $home_page->ID);
            update_option('show_on_front', 'page');
        }
		*/

        // Use WP_Query instead of get_page_by_title()
        $home_page_query = new WP_Query(array(
            'post_type' => 'page',
            'title' => 'Home',
            'posts_per_page' => 1
        ));

        if ($home_page_query->have_posts()) {
            $home_page_query->the_post();
            $home_page_id = get_the_ID();
            update_option('page_on_front', $home_page_id);
            update_option('show_on_front', 'page');
            wp_reset_postdata();
        }
    }
}

if (!function_exists('aqualuxe_import_demo_widgets')) {
    /**
     * Import demo widgets
     *
     * @since 1.0.0
     */
    function aqualuxe_import_demo_widgets()
    {
        // This is a simplified version - in a real implementation,
        // you would import actual widget data

        $widgets = array(
            'sidebar-1' => array(
                'search' => array(
                    'title' => 'Search'
                ),
                'recent-posts' => array(
                    'title' => 'Recent Posts',
                    'number' => 5
                )
            )
        );

        // Update widget options
        foreach ($widgets as $sidebar_id => $sidebar_widgets) {
            $widget_data = array();
            $widget_index = 0;

            foreach ($sidebar_widgets as $widget_type => $widget_options) {
                $widget_data[] = $widget_type . '-' . $widget_index;
                $widget_options['_multiwidget'] = 1;
                update_option('widget_' . $widget_type, array($widget_index => $widget_options));
                $widget_index++;
            }

            // Assign widgets to sidebar
            update_option('sidebars_widgets', array($sidebar_id => $widget_data));
        }
    }
}
