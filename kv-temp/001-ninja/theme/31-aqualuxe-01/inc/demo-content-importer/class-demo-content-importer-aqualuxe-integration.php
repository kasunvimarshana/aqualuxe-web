<?php
/**
 * Demo Content Importer - AquaLuxe Theme Integration
 *
 * This file integrates the standalone Demo Content Importer with the AquaLuxe theme.
 *
 * @package DemoContentImporter
 * @version 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer - AquaLuxe Integration Class
 */
class Demo_Content_Importer_AquaLuxe_Integration {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Main Demo Content Importer instance.
     *
     * @var Demo_Content_Importer
     */
    protected $importer = null;

    /**
     * Constructor.
     */
    public function __construct() {
        // Get the main importer instance
        $this->importer = Demo_Content_Importer::get_instance();

        // Register hooks
        add_action('after_setup_theme', array($this, 'register_demo_packages'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
        add_filter('dci_demo_packages', array($this, 'filter_demo_packages'));
        add_filter('dci_import_options', array($this, 'filter_import_options'), 10, 2);
        add_action('dci_after_import', array($this, 'after_import_setup'), 10, 2);
    }

    /**
     * Get instance of this class.
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Register demo packages for AquaLuxe theme.
     */
    public function register_demo_packages() {
        // Only register if AquaLuxe theme is active
        if ('aqualuxe' !== get_template()) {
            return;
        }

        // Register the demo packages
        $this->register_main_demo();
        $this->register_shop_demo();
        $this->register_blog_demo();
    }

    /**
     * Register the main demo package.
     */
    private function register_main_demo() {
        $this->importer->register_demo_package(array(
            'id' => 'aqualuxe-main',
            'name' => 'AquaLuxe Main Demo',
            'description' => 'Complete demo content for the AquaLuxe theme with all features.',
            'screenshot' => plugin_dir_url(dirname(__FILE__)) . 'data/aqualuxe/screenshot.png',
            'preview_url' => 'https://aqualuxe.example.com',
            'config_file' => plugin_dir_path(dirname(__FILE__)) . 'data/aqualuxe/config.json',
        ));
    }

    /**
     * Register the shop-focused demo package.
     */
    private function register_shop_demo() {
        $this->importer->register_demo_package(array(
            'id' => 'aqualuxe-shop',
            'name' => 'AquaLuxe Shop Demo',
            'description' => 'E-commerce focused demo content for the AquaLuxe theme.',
            'screenshot' => plugin_dir_url(dirname(__FILE__)) . 'data/aqualuxe/screenshot.png',
            'preview_url' => 'https://shop.aqualuxe.example.com',
            'config_file' => plugin_dir_path(dirname(__FILE__)) . 'data/aqualuxe/config.json',
        ));
    }

    /**
     * Register the blog-focused demo package.
     */
    private function register_blog_demo() {
        $this->importer->register_demo_package(array(
            'id' => 'aqualuxe-blog',
            'name' => 'AquaLuxe Blog Demo',
            'description' => 'Blog-focused demo content for the AquaLuxe theme.',
            'screenshot' => plugin_dir_url(dirname(__FILE__)) . 'data/aqualuxe/screenshot.png',
            'preview_url' => 'https://blog.aqualuxe.example.com',
            'config_file' => plugin_dir_path(dirname(__FILE__)) . 'data/aqualuxe/config.json',
        ));
    }

    /**
     * Enqueue theme-specific assets.
     */
    public function enqueue_assets($hook) {
        // Only enqueue on the importer page
        if ('appearance_page_demo-content-importer' !== $hook) {
            return;
        }

        // Only enqueue if AquaLuxe theme is active
        if ('aqualuxe' !== get_template()) {
            return;
        }

        // Enqueue theme-specific styles
        wp_enqueue_style(
            'dci-aqualuxe-styles',
            get_template_directory_uri() . '/assets/css/admin/demo-importer.css',
            array('dci-admin-styles'),
            '1.0.0'
        );

        // Enqueue theme-specific scripts
        wp_enqueue_script(
            'dci-aqualuxe-scripts',
            get_template_directory_uri() . '/assets/js/admin/demo-importer.js',
            array('dci-admin-scripts'),
            '1.0.0',
            true
        );
    }

    /**
     * Filter demo packages based on theme.
     */
    public function filter_demo_packages($packages) {
        // Only show AquaLuxe demos if AquaLuxe theme is active
        if ('aqualuxe' !== get_template()) {
            foreach ($packages as $key => $package) {
                if (strpos($package['id'], 'aqualuxe-') === 0) {
                    unset($packages[$key]);
                }
            }
        }
        return $packages;
    }

    /**
     * Filter import options for AquaLuxe theme.
     */
    public function filter_import_options($options, $demo_id) {
        // Only modify options for AquaLuxe demos
        if (strpos($demo_id, 'aqualuxe-') !== 0) {
            return $options;
        }

        // Add theme-specific options
        $options['setup_menus'] = true;
        $options['setup_homepage'] = true;
        $options['setup_widgets'] = true;
        
        // Shop-specific options
        if ($demo_id === 'aqualuxe-shop') {
            $options['setup_woocommerce'] = true;
            $options['generate_products'] = true;
            $options['product_count'] = 20;
        }
        
        // Blog-specific options
        if ($demo_id === 'aqualuxe-blog') {
            $options['generate_posts'] = true;
            $options['post_count'] = 15;
        }
        
        return $options;
    }

    /**
     * Perform additional setup after import.
     */
    public function after_import_setup($demo_id, $import_options) {
        // Only run for AquaLuxe demos
        if (strpos($demo_id, 'aqualuxe-') !== 0) {
            return;
        }

        // Set up theme mods
        $this->setup_theme_mods($demo_id);
        
        // Set up WooCommerce if needed
        if (!empty($import_options['setup_woocommerce'])) {
            $this->setup_woocommerce();
        }
        
        // Regenerate thumbnails
        $this->regenerate_thumbnails();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Set up theme mods based on demo type.
     */
    private function setup_theme_mods($demo_id) {
        // Common theme mods
        set_theme_mod('aqualuxe_enable_dark_mode', true);
        set_theme_mod('aqualuxe_show_breadcrumbs', true);
        set_theme_mod('aqualuxe_enable_back_to_top', true);
        
        // Demo-specific theme mods
        switch ($demo_id) {
            case 'aqualuxe-shop':
                set_theme_mod('aqualuxe_product_columns', 4);
                set_theme_mod('aqualuxe_shop_sidebar', 'right');
                set_theme_mod('aqualuxe_enable_quick_view', true);
                set_theme_mod('aqualuxe_enable_wishlist', true);
                break;
                
            case 'aqualuxe-blog':
                set_theme_mod('aqualuxe_blog_layout', 'grid');
                set_theme_mod('aqualuxe_blog_sidebar', 'right');
                set_theme_mod('aqualuxe_show_post_author', true);
                set_theme_mod('aqualuxe_show_post_date', true);
                set_theme_mod('aqualuxe_show_post_categories', true);
                break;
                
            default:
                // Default theme mods
                set_theme_mod('aqualuxe_product_columns', 3);
                set_theme_mod('aqualuxe_shop_sidebar', 'left');
                set_theme_mod('aqualuxe_blog_layout', 'standard');
                set_theme_mod('aqualuxe_blog_sidebar', 'right');
                break;
        }
    }

    /**
     * Set up WooCommerce settings.
     */
    private function setup_woocommerce() {
        // Only run if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        // Update WooCommerce pages
        $pages = array(
            'shop' => 'Shop',
            'cart' => 'Cart',
            'checkout' => 'Checkout',
            'myaccount' => 'My Account',
        );
        
        foreach ($pages as $key => $title) {
            $page = get_page_by_title($title);
            if ($page) {
                update_option('woocommerce_' . $key . '_page_id', $page->ID);
            }
        }
        
        // Update WooCommerce options
        update_option('woocommerce_currency', 'USD');
        update_option('woocommerce_currency_pos', 'left');
        update_option('woocommerce_price_thousand_sep', ',');
        update_option('woocommerce_price_decimal_sep', '.');
        update_option('woocommerce_price_num_decimals', 2);
        
        // Update store address
        update_option('woocommerce_store_address', '123 Aquarium Street');
        update_option('woocommerce_store_city', 'Ocean City');
        update_option('woocommerce_default_country', 'US:CA');
        update_option('woocommerce_store_postcode', '90210');
    }

    /**
     * Regenerate thumbnails for imported images.
     */
    private function regenerate_thumbnails() {
        // This would typically use a background process
        // For now, we'll just add a notice to the user
        add_action('admin_notices', function() {
            ?>
            <div class="notice notice-info is-dismissible">
                <p><?php _e('For best results, please regenerate thumbnails using the <a href="https://wordpress.org/plugins/regenerate-thumbnails/">Regenerate Thumbnails</a> plugin.', 'demo-content-importer'); ?></p>
            </div>
            <?php
        });
    }
}

// Initialize the integration
Demo_Content_Importer_AquaLuxe_Integration::get_instance();