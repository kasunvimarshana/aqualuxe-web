<?php
/**
 * AquaLuxe Core Theme Class
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since   1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main AquaLuxe Theme Class
 *
 * @class AquaLuxe_Theme
 */
class AquaLuxe_Theme {

    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Theme
     */
    protected static $_instance = null;

    /**
     * Version
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * Theme options
     *
     * @var array
     */
    public $options = array();

    /**
     * Modules
     *
     * @var array
     */
    public $modules = array();

    /**
     * Main AquaLuxe_Theme Instance
     *
     * Ensures only one instance of AquaLuxe_Theme is loaded or can be loaded.
     *
     * @static
     * @return AquaLuxe_Theme - Main instance
     */
    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
        $this->load_options();
    }

    /**
     * Hook into actions and filters
     */
    private function init_hooks() {
        add_action('init', array($this, 'init'), 0);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_head', array($this, 'add_meta_tags'));
        add_action('wp_head', array($this, 'add_schema_markup'));
        add_action('wp_footer', array($this, 'add_footer_scripts'));
        
        // Admin hooks
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('customize_register', array($this, 'customize_register'));
        
        // Template hooks
        add_action('aqualuxe_header', array($this, 'render_header'));
        add_action('aqualuxe_footer', array($this, 'render_footer'));
        add_action('aqualuxe_sidebar', array($this, 'render_sidebar'));
        
        // Content hooks
        add_filter('the_content', array($this, 'filter_content'));
        add_filter('wp_nav_menu_args', array($this, 'nav_menu_args'));
        
        // Security hooks
        add_filter('wp_headers', array($this, 'security_headers'));
        
        // Performance hooks
        add_action('wp_head', array($this, 'preload_resources'), 1);
        add_filter('style_loader_tag', array($this, 'add_critical_css_inline'), 10, 4);
    }

    /**
     * Initialize theme
     */
    public function init() {
        // Set up theme features
        $this->setup_theme_features();
        
        // Initialize modules
        $this->init_modules();
        
        // Set up custom post types and taxonomies
        $this->setup_custom_content();
        
        // Set up image sizes
        $this->setup_image_sizes();
        
        // Load textdomain
        $this->load_textdomain();
        
        // Fire init action
        do_action('aqualuxe_init');
    }

    /**
     * Load theme options
     */
    private function load_options() {
        $this->options = get_option('aqualuxe_options', array());
        
        // Default options
        $defaults = array(
            'logo'              => '',
            'dark_mode'         => false,
            'maintenance_mode'  => false,
            'modules'           => array(
                'multilingual'  => true,
                'dark_mode'     => true,
                'woocommerce'   => true,
                'seo'           => true,
                'performance'   => true,
                'security'      => true,
            ),
        );
        
        $this->options = wp_parse_args($this->options, $defaults);
    }

    /**
     * Setup theme features
     */
    private function setup_theme_features() {
        // Theme supports
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('automatic-feed-links');
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ));
        add_theme_support('custom-logo');
        add_theme_support('custom-header');
        add_theme_support('custom-background');
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('responsive-embeds');
        add_theme_support('align-wide');
        add_theme_support('wp-block-styles');
        add_theme_support('editor-styles');
        
        // WooCommerce support
        if (class_exists('WooCommerce')) {
            add_theme_support('woocommerce');
            add_theme_support('wc-product-gallery-zoom');
            add_theme_support('wc-product-gallery-lightbox');
            add_theme_support('wc-product-gallery-slider');
        }
    }

    /**
     * Initialize modules
     */
    private function init_modules() {
        $modules = array(
            'multilingual'    => 'AquaLuxe_Multilingual',
            'dark_mode'       => 'AquaLuxe_Dark_Mode',
            'demo_importer'   => 'AquaLuxe_Demo_Importer',
            'woocommerce'     => 'AquaLuxe_WooCommerce',
            'subscriptions'   => 'AquaLuxe_Subscriptions',
            'bookings'        => 'AquaLuxe_Bookings',
            'events'          => 'AquaLuxe_Events',
            'auctions'        => 'AquaLuxe_Auctions',
            'wholesale'       => 'AquaLuxe_Wholesale',
            'franchise'       => 'AquaLuxe_Franchise',
            'affiliates'      => 'AquaLuxe_Affiliates',
            'services'        => 'AquaLuxe_Services',
        );
        
        foreach ($modules as $module => $class) {
            if (isset($this->options['modules'][$module]) && $this->options['modules'][$module]) {
                if (class_exists($class)) {
                    $this->modules[$module] = $class::get_instance();
                }
            }
        }
    }

    /**
     * Setup custom content types
     */
    private function setup_custom_content() {
        // This will be handled by dedicated classes
        do_action('aqualuxe_setup_custom_content');
    }

    /**
     * Setup image sizes
     */
    private function setup_image_sizes() {
        add_image_size('aqualuxe-hero', 1920, 1080, true);
        add_image_size('aqualuxe-featured', 800, 600, true);
        add_image_size('aqualuxe-thumbnail', 400, 300, true);
        add_image_size('aqualuxe-gallery', 600, 400, true);
        add_image_size('aqualuxe-product', 600, 600, true);
        add_image_size('aqualuxe-testimonial', 150, 150, true);
    }

    /**
     * Load textdomain
     */
    private function load_textdomain() {
        load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . '/languages');
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Get mix manifest for cache busting
        $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
        $manifest = array();
        
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
        }
        
        // Main stylesheet
        $css_file = isset($manifest['/css/app.css']) ? $manifest['/css/app.css'] : '/css/app.css';
        wp_enqueue_style(
            'aqualuxe-main',
            AQUALUXE_ASSETS_URI . ltrim($css_file, '/'),
            array(),
            $this->version
        );
        
        // WooCommerce styles if active
        if (aqualuxe_is_woocommerce_active() && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
            $wc_css_file = isset($manifest['/css/woocommerce.css']) ? $manifest['/css/woocommerce.css'] : '/css/woocommerce.css';
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . ltrim($wc_css_file, '/'),
                array('aqualuxe-main'),
                $this->version
            );
        }
        
        // Main JavaScript
        $js_file = isset($manifest['/js/app.js']) ? $manifest['/js/app.js'] : '/js/app.js';
        wp_enqueue_script(
            'aqualuxe-main',
            AQUALUXE_ASSETS_URI . ltrim($js_file, '/'),
            array('jquery'),
            $this->version,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-main', 'aqualuxe', array(
            'ajaxurl'   => admin_url('admin-ajax.php'),
            'nonce'     => wp_create_nonce('aqualuxe_nonce'),
            'theme_uri' => AQUALUXE_THEME_URI,
            'is_rtl'    => is_rtl(),
            'language'  => aqualuxe_get_current_language(),
            'dark_mode' => aqualuxe_is_dark_mode(),
        ));
        
        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Admin enqueue scripts
     */
    public function admin_enqueue_scripts($hook) {
        $admin_css_file = '/css/admin.css';
        wp_enqueue_style(
            'aqualuxe-admin',
            AQUALUXE_ASSETS_URI . ltrim($admin_css_file, '/'),
            array(),
            $this->version
        );
        
        $admin_js_file = '/js/admin.js';
        wp_enqueue_script(
            'aqualuxe-admin',
            AQUALUXE_ASSETS_URI . ltrim($admin_js_file, '/'),
            array('jquery'),
            $this->version,
            true
        );
        
        wp_localize_script('aqualuxe-admin', 'aqualuxe_admin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('aqualuxe_admin_nonce'),
        ));
    }

    /**
     * Add meta tags
     */
    public function add_meta_tags() {
        // Viewport meta tag
        echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">' . "\n";
        
        // Theme color for mobile browsers
        echo '<meta name="theme-color" content="#06b6d4">' . "\n";
        
        // Apple touch icon
        if (function_exists('get_site_icon_url') && get_site_icon_url()) {
            echo '<link rel="apple-touch-icon" href="' . esc_url(get_site_icon_url(180)) . '">' . "\n";
        }
        
        // Preconnect to external domains for performance
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        
        // DNS prefetch for external domains
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
    }

    /**
     * Add schema markup
     */
    public function add_schema_markup() {
        if (is_home() || is_front_page()) {
            $schema = array(
                '@context' => 'https://schema.org',
                '@type'    => 'WebSite',
                'name'     => get_bloginfo('name'),
                'url'      => home_url(),
                'potentialAction' => array(
                    '@type'       => 'SearchAction',
                    'target'      => home_url('/?s={search_term_string}'),
                    'query-input' => 'required name=search_term_string',
                ),
            );
            
            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
        }
    }

    /**
     * Add footer scripts
     */
    public function add_footer_scripts() {
        // Google Analytics or other tracking scripts can be added here
        $tracking_code = get_theme_mod('aqualuxe_tracking_code', '');
        if (!empty($tracking_code)) {
            echo $tracking_code;
        }
    }

    /**
     * Customize register
     */
    public function customize_register($wp_customize) {
        // This will be handled by the customizer class
        do_action('aqualuxe_customize_register', $wp_customize);
    }

    /**
     * Render header
     */
    public function render_header() {
        get_template_part('templates/components/header');
    }

    /**
     * Render footer
     */
    public function render_footer() {
        get_template_part('templates/components/footer');
    }

    /**
     * Render sidebar
     */
    public function render_sidebar() {
        get_template_part('templates/components/sidebar');
    }

    /**
     * Filter content
     */
    public function filter_content($content) {
        // Add lazy loading to images
        if (!is_admin() && !is_feed()) {
            $content = preg_replace('/<img(.*?)src=/i', '<img$1loading="lazy" src=', $content);
        }
        
        return $content;
    }

    /**
     * Navigation menu args
     */
    public function nav_menu_args($args) {
        // Add ARIA labels for accessibility
        if (!isset($args['container_aria_label']) && isset($args['theme_location'])) {
            switch ($args['theme_location']) {
                case 'primary':
                    $args['container_aria_label'] = esc_html__('Primary navigation', 'aqualuxe');
                    break;
                case 'secondary':
                    $args['container_aria_label'] = esc_html__('Secondary navigation', 'aqualuxe');
                    break;
                case 'footer':
                    $args['container_aria_label'] = esc_html__('Footer navigation', 'aqualuxe');
                    break;
            }
        }
        
        return $args;
    }

    /**
     * Security headers
     */
    public function security_headers($headers) {
        $headers['X-Content-Type-Options'] = 'nosniff';
        $headers['X-Frame-Options'] = 'SAMEORIGIN';
        $headers['X-XSS-Protection'] = '1; mode=block';
        $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
        
        return $headers;
    }

    /**
     * Preload resources
     */
    public function preload_resources() {
        // Preload critical fonts
        echo '<link rel="preload" href="' . AQUALUXE_ASSETS_URI . 'fonts/inter.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
        
        // Preload critical CSS
        echo '<link rel="preload" href="' . AQUALUXE_ASSETS_URI . 'css/app.css" as="style">' . "\n";
    }

    /**
     * Add critical CSS inline
     */
    public function add_critical_css_inline($html, $handle, $href, $media) {
        if ($handle === 'aqualuxe-main' && $media === 'all') {
            // Critical CSS path
            $critical_css_path = AQUALUXE_THEME_DIR . '/assets/dist/css/critical.css';
            
            if (file_exists($critical_css_path)) {
                $critical_css = file_get_contents($critical_css_path);
                if ($critical_css) {
                    echo '<style>' . $critical_css . '</style>' . "\n";
                    
                    // Load full CSS asynchronously
                    $html = str_replace('media="all"', 'media="print" onload="this.media=\'all\'"', $html);
                }
            }
        }
        
        return $html;
    }

    /**
     * Get theme option
     */
    public function get_option($key, $default = null) {
        return isset($this->options[$key]) ? $this->options[$key] : $default;
    }

    /**
     * Set theme option
     */
    public function set_option($key, $value) {
        $this->options[$key] = $value;
        update_option('aqualuxe_options', $this->options);
    }

    /**
     * Check if module is active
     */
    public function is_module_active($module) {
        return isset($this->modules[$module]);
    }

    /**
     * Get module instance
     */
    public function get_module($module) {
        return isset($this->modules[$module]) ? $this->modules[$module] : null;
    }
}