<?php
/**
 * AquaLuxe Demo Importer
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Demo_Importer {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_post_aqualuxe_import_demo', array($this, 'import_demo_content'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            __('AquaLuxe Demo Import', 'aqualuxe'),
            __('Import Demo Data', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-import',
            array($this, 'demo_import_page')
        );
    }
    
    /**
     * Demo import page
     */
    public function demo_import_page() {
        if (isset($_POST['import_demo'])) {
            $this->import_demo_content();
            echo '<div class="notice notice-success"><p>' . __('Demo data imported successfully!', 'aqualuxe') . '</p></div>';
        }
        
        ?>
        <div class="wrap">
            <h1><?php _e('AquaLuxe Demo Import', 'aqualuxe'); ?></h1>
            <p><?php _e('Import demo content to set up your site with sample data.', 'aqualuxe'); ?></p>
            
            <form method="post">
                <?php wp_nonce_field('aqualuxe_demo_import', 'aqualuxe_demo_import_nonce'); ?>
                <input type="hidden" name="import_demo" value="1">
                <input type="submit" class="button button-primary" value="<?php _e('Import Demo Data', 'aqualuxe'); ?>">
            </form>
        </div>
        <?php
    }
    
    /**
     * Import demo content
     */
    public function import_demo_content() {
        // Check nonce
        if (!wp_verify_nonce($_POST['aqualuxe_demo_import_nonce'], 'aqualuxe_demo_import')) {
            wp_die('Security check failed');
        }
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        // Import sample products
        $this->import_sample_products();
        
        // Import sample pages
        $this->import_sample_pages();
        
        // Import sample widgets
        $this->import_sample_widgets();
    }
    
    /**
     * Import sample products
     */
    private function import_sample_products() {
        if (!class_exists('WC_Product')) {
            return;
        }
        
        // Sample product data
        $products = array(
            array(
                'name' => 'Premium Goldfish',
                'description' => 'Beautiful golden variety with long flowing fins. Perfect for home aquariums.',
                'price' => '29.99',
                'sku' => 'GF-001',
                'type' => 'simple'
            ),
            array(
                'name' => 'Ornate Angelfish',
                'description' => 'Striking black and silver patterned angelfish. Known for their graceful swimming.',
                'price' => '45.99',
                'sku' => 'AF-002',
                'type' => 'simple'
            ),
            array(
                'name' => 'Royal Discus',
                'description' => 'High-grade discus fish with vibrant colors. A prized addition to any aquarium.',
                'price' => '89.99',
                'sku' => 'DS-003',
                'type' => 'simple'
            )
        );
        
        foreach ($products as $product_data) {
            $product = new WC_Product();
            $product->set_name($product_data['name']);
            $product->set_description($product_data['description']);
            $product->set_regular_price($product_data['price']);
            $product->set_sku($product_data['sku']);
            $product->set_status('publish');
            $product->save();
        }
    }
    
    /**
     * Import sample pages
     */
    private function import_sample_pages() {
        // Sample page data
        $pages = array(
            array(
                'title' => 'About Our Fish',
                'content' => '<h2>Premium Ornamental Fish</h2><p>We specialize in breeding and selling the finest ornamental fish for aquariums.</p>'
            ),
            array(
                'title' => 'Care Guide',
                'content' => '<h2>Fish Care Instructions</h2><p>Proper care instructions for maintaining healthy aquarium fish.</p>'
            ),
            array(
                'title' => 'Contact Us',
                'content' => '<h2>Get in Touch</h2><p>Contact information and inquiry form.</p>'
            )
        );
        
        foreach ($pages as $page_data) {
            $page = array(
                'post_title' => $page_data['title'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
            );
            
            wp_insert_post($page);
        }
    }
    
    /**
     * Import sample widgets
     */
    private function import_sample_widgets() {
        // Sample widget data
        $widgets = array(
            'widget_search' => array(
                1 => array(
                    'title' => 'Search'
                )
            ),
            'widget_text' => array(
                1 => array(
                    'title' => 'About AquaLuxe',
                    'text' => 'We provide the finest ornamental fish for aquarium enthusiasts.'
                )
            )
        );
        
        foreach ($widgets as $widget_type => $widget_data) {
            update_option($widget_type, $widget_data);
        }
        
        // Assign widgets to sidebars
        $sidebars = array(
            'sidebar-1' => array('search-1', 'text-1')
        );
        
        update_option('sidebars_widgets', $sidebars);
    }
}