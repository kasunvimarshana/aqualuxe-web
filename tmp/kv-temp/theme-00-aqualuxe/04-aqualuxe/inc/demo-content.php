<?php
/**
 * AquaLuxe Demo Content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register demo content
 */
function aqualuxe_register_demo_content() {
    // Check if One Click Demo Import plugin is active
    if (!class_exists('OCDI_Plugin')) {
        return;
    }
    
    // Register demo content
    OCDI\one_click_demo_import()->import_files[] = array(
        'import_file_name'             => 'AquaLuxe Demo',
        'categories'                    => array('WooCommerce'),
        'import_file_url'               => AQUALUXE_CHILD_THEME_URI . '/demo-content/demo-content.xml',
        'import_widget_file_url'        => AQUALUXE_CHILD_THEME_URI . '/demo-content/widgets.json',
        'import_customizer_file_url'    => AQUALUXE_CHILD_THEME_URI . '/demo-content/customizer.dat',
        'import_preview_image_url'      => AQUALUXE_CHILD_THEME_URI . '/screenshot.png',
        'preview_url'                   => 'https://demo.aqualuxe.com',
        'import_notice'                 => __('After you import this demo, you will have to setup the slider separately.', 'aqualuxe'),
        'local_import_file'             => AQUALUXE_CHILD_THEME_DIR . '/demo-content/demo-content.xml',
        'local_import_widget_file'      => AQUALUXE_CHILD_THEME_DIR . '/demo-content/widgets.json',
        'local_import_customizer_file'  => AQUALUXE_CHILD_THEME_DIR . '/demo-content/customizer.dat',
    );
}
add_action('ocdi/after_import', 'aqualuxe_register_demo_content');

/**
 * Create demo content directory
 */
function aqualuxe_create_demo_content_dir() {
    $demo_dir = AQUALUXE_CHILD_THEME_DIR . '/demo-content';
    
    if (!file_exists($demo_dir)) {
        mkdir($demo_dir, 0755, true);
    }
}
add_action('after_setup_theme', 'aqualuxe_create_demo_content_dir');

/**
 * Create sample products for demo
 */
function aqualuxe_create_sample_products() {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    // Sample product data
    $products = array(
        array(
            'name' => 'Premium Goldfish',
            'description' => 'Beautiful golden fish with flowing fins. Perfect for home aquariums.',
            'price' => '29.99',
            'sku' => 'GF001',
            'stock' => 50,
            'categories' => array('Fish'),
        ),
        array(
            'name' => 'Tropical Discus',
            'description' => 'Vibrant discus fish with striking colors. Requires experienced care.',
            'price' => '89.99',
            'sku' => 'DS001',
            'stock' => 20,
            'categories' => array('Fish'),
        ),
        array(
            'name' => 'Freshwater Shrimp',
            'description' => 'Active shrimp that help keep your tank clean. Great for beginners.',
            'price' => '9.99',
            'sku' => 'SH001',
            'stock' => 100,
            'categories' => array('Invertebrates'),
        ),
        array(
            'name' => 'Aquarium Starter Kit',
            'description' => 'Complete kit with tank, filter, heater, and lighting. Perfect for beginners.',
            'price' => '199.99',
            'sku' => 'KIT001',
            'stock' => 15,
            'categories' => array('Accessories'),
        ),
    );
    
    // Create product categories if they don't exist
    $categories = array('Fish', 'Invertebrates', 'Plants', 'Accessories');
    foreach ($categories as $category) {
        if (!get_term_by('name', $category, 'product_cat')) {
            wp_insert_term($category, 'product_cat');
        }
    }
    
    // Create products
    foreach ($products as $product_data) {
        // Check if product already exists
        $existing = get_page_by_title($product_data['name'], OBJECT, 'product');
        if ($existing) {
            continue;
        }
        
        // Create product
        $product = new WC_Product();
        $product->set_name($product_data['name']);
        $product->set_description($product_data['description']);
        $product->set_regular_price($product_data['price']);
        $product->set_sku($product_data['sku']);
        $product->set_stock_quantity($product_data['stock']);
        $product->set_manage_stock(true);
        $product->set_stock_status('instock');
        $product->set_catalog_visibility('visible');
        $product->set_status('publish');
        
        // Set categories
        $product_categories = array();
        foreach ($product_data['categories'] as $category) {
            $term = get_term_by('name', $category, 'product_cat');
            if ($term) {
                $product_categories[] = $term->term_id;
            }
        }
        $product->set_category_ids($product_categories);
        
        // Save product
        $product_id = $product->save();
        
        // Set product image (if exists)
        // This would typically involve uploading and setting a featured image
    }
}

/**
 * Create sample pages for demo
 */
function aqualuxe_create_sample_pages() {
    // Sample pages data
    $pages = array(
        array(
            'title' => 'About Us',
            'content' => '<h1>About AquaLuxe</h1><p>Welcome to AquaLuxe, your premier destination for premium ornamental fish and aquarium supplies.</p>',
            'template' => 'page-templates/about.php',
        ),
        array(
            'title' => 'Contact',
            'content' => '<h1>Contact Us</h1><p>Get in touch with our team for inquiries and support.</p>',
            'template' => 'page-templates/contact.php',
        ),
        array(
            'title' => 'FAQ',
            'content' => '<h1>Frequently Asked Questions</h1><p>Find answers to common questions about our products and services.</p>',
            'template' => 'page-templates/faq.php',
        ),
    );
    
    // Create pages
    foreach ($pages as $page_data) {
        // Check if page already exists
        $existing = get_page_by_title($page_data['title']);
        if ($existing) {
            continue;
        }
        
        // Create page
        $page_id = wp_insert_post(array(
            'post_title' => $page_data['title'],
            'post_content' => $page_data['content'],
            'post_status' => 'publish',
            'post_type' => 'page',
            'page_template' => isset($page_data['template']) ? $page_data['template'] : 'default',
        ));
    }
}

/**
 * Set up demo content after import
 */
function aqualuxe_setup_demo_content() {
    // Create sample products
    aqualuxe_create_sample_products();
    
    // Create sample pages
    aqualuxe_create_sample_pages();
    
    // Set front page
    $front_page = get_page_by_title('Shop');
    if ($front_page) {
        update_option('page_on_front', $front_page->ID);
        update_option('show_on_front', 'page');
    }
    
    // Set shop page
    $shop_page = get_page_by_title('Shop');
    if ($shop_page) {
        update_option('woocommerce_shop_page_id', $shop_page->ID);
    }
    
    // Set cart page
    $cart_page = get_page_by_title('Cart');
    if ($cart_page) {
        update_option('woocommerce_cart_page_id', $cart_page->ID);
    }
    
    // Set checkout page
    $checkout_page = get_page_by_title('Checkout');
    if ($checkout_page) {
        update_option('woocommerce_checkout_page_id', $checkout_page->ID);
    }
    
    // Set my account page
    $account_page = get_page_by_title('My Account');
    if ($account_page) {
        update_option('woocommerce_myaccount_page_id', $account_page->ID);
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('ocdi/after_import', 'aqualuxe_setup_demo_content');