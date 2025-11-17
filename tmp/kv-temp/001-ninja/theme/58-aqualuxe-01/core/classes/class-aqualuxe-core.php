<?php
/**
 * AquaLuxe Core Class
 *
 * This class initializes the theme core functionality
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('AquaLuxe_Core')) {

    /**
     * Main AquaLuxe Core Class
     */
    class AquaLuxe_Core {

        /**
         * Instance of this class
         *
         * @var object
         */
        private static $instance = null;

        /**
         * Theme options
         *
         * @var array
         */
        public $options = array();

        /**
         * Active modules
         *
         * @var array
         */
        public $active_modules = array();

        /**
         * WooCommerce active status
         *
         * @var bool
         */
        public $woocommerce_active = false;

        /**
         * Main AquaLuxe_Core Instance
         *
         * Ensures only one instance of AquaLuxe_Core is loaded or can be loaded.
         *
         * @return AquaLuxe_Core - Main instance
         */
        public static function get_instance() {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Constructor
         */
        public function __construct() {
            // Check if WooCommerce is active
            $this->woocommerce_active = class_exists('WooCommerce');
            
            // Load theme options
            $this->load_options();
            
            // Load active modules
            $this->load_active_modules();
        }

        /**
         * Initialize the theme
         */
        public function init() {
            // Set up theme defaults and register support
            add_action('after_setup_theme', array($this, 'setup'));
            
            // Register widget areas
            add_action('widgets_init', array($this, 'widgets_init'));
            
            // Enqueue scripts and styles
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            
            // Admin scripts and styles
            add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
            
            // Initialize modules
            $this->init_modules();
        }

        /**
         * Load theme options
         */
        private function load_options() {
            $defaults = array(
                'primary_color' => '#0077b6',
                'secondary_color' => '#00b4d8',
                'accent_color' => '#90e0ef',
                'text_color' => '#333333',
                'heading_color' => '#222222',
                'background_color' => '#ffffff',
                'font_family' => 'Montserrat, sans-serif',
                'heading_font' => 'Playfair Display, serif',
                'container_width' => '1280px',
                'sidebar_position' => 'right',
                'enable_dark_mode' => true,
                'logo_height' => '60',
                'footer_columns' => 4,
                'copyright_text' => '© ' . date('Y') . ' AquaLuxe. All rights reserved.',
            );
            
            $this->options = wp_parse_args(get_option('aqualuxe_options', array()), $defaults);
        }

        /**
         * Load active modules
         */
        private function load_active_modules() {
            $default_modules = array(
                'dark-mode' => true,
                'multilingual' => true,
                'subscriptions' => false,
                'bookings' => false,
                'events' => false,
                'wholesale' => false,
                'auctions' => false,
                'services' => true,
                'franchise' => false,
                'sustainability' => false,
                'affiliates' => false,
            );
            
            $this->active_modules = wp_parse_args(get_option('aqualuxe_active_modules', array()), $default_modules);
        }

        /**
         * Initialize active modules
         */
        private function init_modules() {
            $module_loader = new AquaLuxe_Module_Loader();
            $module_loader->init($this->active_modules);
        }

        /**
         * Sets up theme defaults and registers support for various WordPress features.
         */
        public function setup() {
            // Make theme available for translation
            load_theme_textdomain('aqualuxe', AQUALUXE_DIR . '/languages');

            // Add default posts and comments RSS feed links to head
            add_theme_support('automatic-feed-links');

            // Let WordPress manage the document title
            add_theme_support('title-tag');

            // Enable support for Post Thumbnails on posts and pages
            add_theme_support('post-thumbnails');

            // Set post thumbnail size
            set_post_thumbnail_size(1200, 9999);

            // Add custom image sizes
            add_image_size('aqualuxe-featured', 1600, 900, true);
            add_image_size('aqualuxe-product', 600, 600, true);
            add_image_size('aqualuxe-thumbnail', 400, 400, true);

            // Register nav menus
            register_nav_menus(array(
                'primary' => esc_html__('Primary Menu', 'aqualuxe'),
                'footer' => esc_html__('Footer Menu', 'aqualuxe'),
                'social' => esc_html__('Social Links Menu', 'aqualuxe'),
            ));

            // Switch default core markup to output valid HTML5
            add_theme_support('html5', array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ));

            // Set up the WordPress core custom background feature
            add_theme_support('custom-background', array(
                'default-color' => 'ffffff',
                'default-image' => '',
            ));

            // Add theme support for selective refresh for widgets
            add_theme_support('customize-selective-refresh-widgets');

            // Add support for custom logo
            add_theme_support('custom-logo', array(
                'height' => 250,
                'width' => 250,
                'flex-width' => true,
                'flex-height' => true,
            ));

            // Add support for full and wide align images
            add_theme_support('align-wide');

            // Add support for responsive embeds
            add_theme_support('responsive-embeds');

            // Add support for editor styles
            add_theme_support('editor-styles');

            // Enqueue editor styles
            add_editor_style('assets/dist/css/editor-style.css');

            // Add support for block templates
            add_theme_support('block-templates');
            
            // WooCommerce support
            if ($this->woocommerce_active) {
                $this->setup_woocommerce_support();
            }
        }

        /**
         * Set up WooCommerce specific features
         */
        private function setup_woocommerce_support() {
            // Add theme support for WooCommerce
            add_theme_support('woocommerce');
            
            // Add support for WooCommerce product gallery features
            add_theme_support('wc-product-gallery-zoom');
            add_theme_support('wc-product-gallery-lightbox');
            add_theme_support('wc-product-gallery-slider');
            
            // Declare WooCommerce support
            add_theme_support('woocommerce', array(
                'thumbnail_image_width' => 400,
                'single_image_width' => 800,
                'product_grid' => array(
                    'default_rows' => 3,
                    'min_rows' => 1,
                    'max_rows' => 8,
                    'default_columns' => 4,
                    'min_columns' => 1,
                    'max_columns' => 6,
                ),
            ));
        }

        /**
         * Register widget areas
         */
        public function widgets_init() {
            register_sidebar(array(
                'name' => esc_html__('Sidebar', 'aqualuxe'),
                'id' => 'sidebar-1',
                'description' => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget' => '</section>',
                'before_title' => '<h2 class="widget-title">',
                'after_title' => '</h2>',
            ));

            // Register footer widget areas
            $footer_columns = $this->options['footer_columns'];
            for ($i = 1; $i <= $footer_columns; $i++) {
                register_sidebar(array(
                    'name' => sprintf(esc_html__('Footer %d', 'aqualuxe'), $i),
                    'id' => 'footer-' . $i,
                    'description' => sprintf(esc_html__('Add widgets here to appear in footer column %d.', 'aqualuxe'), $i),
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget' => '</div>',
                    'before_title' => '<h3 class="widget-title">',
                    'after_title' => '</h3>',
                ));
            }

            // WooCommerce shop sidebar
            if ($this->woocommerce_active) {
                register_sidebar(array(
                    'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
                    'id' => 'shop-sidebar',
                    'description' => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
                    'before_widget' => '<section id="%1$s" class="widget %2$s">',
                    'after_widget' => '</section>',
                    'before_title' => '<h2 class="widget-title">',
                    'after_title' => '</h2>',
                ));
            }
        }

        /**
         * Enqueue scripts and styles
         */
        public function enqueue_scripts() {
            // Get the asset manifest
            $manifest_path = AQUALUXE_DIR . '/assets/dist/mix-manifest.json';
            $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : array();
            
            // Main CSS
            $main_css = isset($manifest['/css/main.css']) ? $manifest['/css/main.css'] : '/css/main.css';
            wp_enqueue_style('aqualuxe-style', AQUALUXE_ASSETS_URI . $main_css, array(), AQUALUXE_VERSION);
            
            // Tailwind CSS
            $tailwind_css = isset($manifest['/css/tailwind.css']) ? $manifest['/css/tailwind.css'] : '/css/tailwind.css';
            wp_enqueue_style('aqualuxe-tailwind', AQUALUXE_ASSETS_URI . $tailwind_css, array(), AQUALUXE_VERSION);
            
            // Main JavaScript
            $main_js = isset($manifest['/js/main.js']) ? $manifest['/js/main.js'] : '/js/main.js';
            wp_enqueue_script('aqualuxe-script', AQUALUXE_ASSETS_URI . $main_js, array('jquery'), AQUALUXE_VERSION, true);
            
            // Localize script
            wp_localize_script('aqualuxe-script', 'aqualuxeData', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'themeUri' => AQUALUXE_URI,
                'nonce' => wp_create_nonce('aqualuxe-nonce'),
                'isWooCommerceActive' => $this->woocommerce_active,
            ));
            
            // Comment reply script
            if (is_singular() && comments_open() && get_option('thread_comments')) {
                wp_enqueue_script('comment-reply');
            }
            
            // WooCommerce specific styles and scripts
            if ($this->woocommerce_active && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
                $woocommerce_css = isset($manifest['/css/woocommerce.css']) ? $manifest['/css/woocommerce.css'] : '/css/woocommerce.css';
                wp_enqueue_style('aqualuxe-woocommerce', AQUALUXE_ASSETS_URI . $woocommerce_css, array('aqualuxe-style'), AQUALUXE_VERSION);
                
                $woocommerce_js = isset($manifest['/js/woocommerce.js']) ? $manifest['/js/woocommerce.js'] : '/js/woocommerce.js';
                wp_enqueue_script('aqualuxe-woocommerce', AQUALUXE_ASSETS_URI . $woocommerce_js, array('jquery'), AQUALUXE_VERSION, true);
            }
            
            // Enqueue module assets
            $this->enqueue_module_assets();
            
            // Add custom CSS variables
            $this->add_custom_css_variables();
        }

        /**
         * Enqueue admin scripts and styles
         */
        public function admin_scripts() {
            // Get the asset manifest
            $manifest_path = AQUALUXE_DIR . '/assets/dist/mix-manifest.json';
            $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : array();
            
            // Admin CSS
            $admin_css = isset($manifest['/css/admin.css']) ? $manifest['/css/admin.css'] : '/css/admin.css';
            wp_enqueue_style('aqualuxe-admin', AQUALUXE_ASSETS_URI . $admin_css, array(), AQUALUXE_VERSION);
            
            // Admin JavaScript
            $admin_js = isset($manifest['/js/admin.js']) ? $manifest['/js/admin.js'] : '/js/admin.js';
            wp_enqueue_script('aqualuxe-admin', AQUALUXE_ASSETS_URI . $admin_js, array('jquery'), AQUALUXE_VERSION, true);
            
            // Localize admin script
            wp_localize_script('aqualuxe-admin', 'aqualuxeAdminData', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'themeUri' => AQUALUXE_URI,
                'nonce' => wp_create_nonce('aqualuxe-admin-nonce'),
            ));
        }

        /**
         * Enqueue module assets
         */
        private function enqueue_module_assets() {
            // Get the asset manifest
            $manifest_path = AQUALUXE_DIR . '/assets/dist/mix-manifest.json';
            $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : array();
            
            // Loop through active modules and enqueue their assets
            foreach ($this->active_modules as $module => $active) {
                if ($active) {
                    // Module CSS
                    $module_css_path = "/css/modules/{$module}.css";
                    $module_css = isset($manifest[$module_css_path]) ? $manifest[$module_css_path] : $module_css_path;
                    if (file_exists(AQUALUXE_DIR . '/assets/dist' . $module_css)) {
                        wp_enqueue_style("aqualuxe-{$module}", AQUALUXE_ASSETS_URI . $module_css, array('aqualuxe-style'), AQUALUXE_VERSION);
                    }
                    
                    // Module JavaScript
                    $module_js_path = "/js/modules/{$module}.js";
                    $module_js = isset($manifest[$module_js_path]) ? $manifest[$module_js_path] : $module_js_path;
                    if (file_exists(AQUALUXE_DIR . '/assets/dist' . $module_js)) {
                        wp_enqueue_script("aqualuxe-{$module}", AQUALUXE_ASSETS_URI . $module_js, array('aqualuxe-script'), AQUALUXE_VERSION, true);
                    }
                }
            }
        }

        /**
         * Add custom CSS variables
         */
        private function add_custom_css_variables() {
            $css_vars = array(
                '--color-primary' => $this->options['primary_color'],
                '--color-primary-light' => $this->adjust_brightness($this->options['primary_color'], 20),
                '--color-primary-dark' => $this->adjust_brightness($this->options['primary_color'], -20),
                '--color-secondary' => $this->options['secondary_color'],
                '--color-secondary-light' => $this->adjust_brightness($this->options['secondary_color'], 20),
                '--color-secondary-dark' => $this->adjust_brightness($this->options['secondary_color'], -20),
                '--color-accent' => $this->options['accent_color'],
                '--color-accent-light' => $this->adjust_brightness($this->options['accent_color'], 20),
                '--color-accent-dark' => $this->adjust_brightness($this->options['accent_color'], -20),
                '--color-text' => $this->options['text_color'],
                '--color-heading' => $this->options['heading_color'],
                '--color-background' => $this->options['background_color'],
                '--color-dark-primary' => '#90e0ef',
                '--color-dark-secondary' => '#00b4d8',
                '--color-dark-accent' => '#0077b6',
                '--color-dark-background' => '#121212',
                '--color-dark-text' => '#e0e0e0',
                '--color-dark-heading' => '#ffffff',
                '--color-success' => '#4CAF50',
                '--color-warning' => '#FFC107',
                '--color-error' => '#F44336',
                '--color-info' => '#2196F3',
                '--font-family' => $this->options['font_family'],
                '--heading-font' => $this->options['heading_font'],
                '--container-width' => $this->options['container_width'],
            );
            
            $custom_css = ':root {';
            foreach ($css_vars as $var => $value) {
                $custom_css .= $var . ': ' . $value . ';';
            }
            $custom_css .= '}';
            
            wp_add_inline_style('aqualuxe-style', $custom_css);
        }

        /**
         * Adjust color brightness
         *
         * @param string $hex Hex color code
         * @param int $steps Steps to adjust brightness (positive for lighter, negative for darker)
         * @return string Adjusted hex color
         */
        private function adjust_brightness($hex, $steps) {
            // Remove # if present
            $hex = ltrim($hex, '#');
            
            // Convert to RGB
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            
            // Adjust brightness
            $r = max(0, min(255, $r + $steps));
            $g = max(0, min(255, $g + $steps));
            $b = max(0, min(255, $b + $steps));
            
            // Convert back to hex
            return sprintf('#%02x%02x%02x', $r, $g, $b);
        }
    }
}