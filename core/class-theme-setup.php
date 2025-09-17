<?php
/**
 * Theme Setup Class - Clean Architecture Implementation
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

defined('ABSPATH') || exit;

/**
 * Theme Setup Class
 * 
 * Implements SOLID principles and Clean Architecture patterns
 */
class Theme_Setup {

    /**
     * Instance
     *
     * @var Theme_Setup
     */
    private static $instance = null;

    /**
     * Service container
     *
     * @var \AquaLuxe_Service_Container
     */
    private $container;

    /**
     * Get instance
     *
     * @return Theme_Setup
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
        $this->init_clean_architecture();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('after_setup_theme', array($this, 'setup_theme'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('init', array($this, 'init'));
    }

    /**
     * Initialize Clean Architecture layers
     */
    private function init_clean_architecture() {
        // Initialize dependency injection container
        $this->init_dependency_injection();
        
        // Load core includes following Clean Architecture
        $this->load_core_includes();
        
        // Initialize modules
        $this->init_modules();
        
        // Apply Clean Architecture hooks
        do_action('aqualuxe_init_domain_layer');
        do_action('aqualuxe_init_application_layer');  
        do_action('aqualuxe_init_infrastructure_layer');
        do_action('aqualuxe_init_presentation_layer');
    }

    /**
     * Initialize dependency injection
     */
    private function init_dependency_injection() {
        if (!class_exists('AquaLuxe_Service_Container')) {
            require_once AQUALUXE_THEME_DIR . '/core/class-service-container.php';
        }
        
        $this->container = \AquaLuxe_Service_Container::get_instance();
        
        // Register core services
        $this->container->register('cache', 'WP_Object_Cache');
        $this->container->register('database', 'wpdb');
        
        do_action('aqualuxe_register_services', $this->container);
    }

    /**
     * Theme setup
     */
    public function setup_theme() {
        // Enable post thumbnails
        add_theme_support('post-thumbnails');
        
        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');
        
        // Let WordPress manage the document title
        add_theme_support('title-tag');
        
        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');
        
        // Add support for core custom logo
        add_theme_support('custom-logo', array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        ));
        
        // HTML5 support
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ));
        
        // Custom background
        add_theme_support('custom-background', array(
            'default-color' => 'ffffff',
        ));
        
        // Wide and full alignment
        add_theme_support('align-wide');
        
        // Add support for editor styles
        add_theme_support('editor-styles');
        add_editor_style('assets/dist/css/app.css');
        
        // Add support for responsive embeds
        add_theme_support('responsive-embeds');
        
        // Add support for block styles
        add_theme_support('wp-block-styles');
        
        // Custom editor color palette
        add_theme_support('editor-color-palette', array(
            array(
                'name'  => esc_html__('Primary Blue', 'aqualuxe'),
                'slug'  => 'primary',
                'color' => '#0ea5e9',
            ),
            array(
                'name'  => esc_html__('Secondary Green', 'aqualuxe'),
                'slug'  => 'secondary',
                'color' => '#10b981',
            ),
            array(
                'name'  => esc_html__('Dark Gray', 'aqualuxe'),
                'slug'  => 'dark',
                'color' => '#1f2937',
            ),
            array(
                'name'  => esc_html__('Light Gray', 'aqualuxe'),
                'slug'  => 'light',
                'color' => '#f9fafb',
            ),
        ));
        
        // Custom font sizes
        add_theme_support('editor-font-sizes', array(
            array(
                'name' => esc_html__('Small', 'aqualuxe'),
                'size' => 14,
                'slug' => 'small'
            ),
            array(
                'name' => esc_html__('Normal', 'aqualuxe'),
                'size' => 16,
                'slug' => 'normal'
            ),
            array(
                'name' => esc_html__('Large', 'aqualuxe'),
                'size' => 24,
                'slug' => 'large'
            ),
            array(
                'name' => esc_html__('Extra Large', 'aqualuxe'),
                'size' => 32,
                'slug' => 'extra-large'
            ),
        ));
        
        // WooCommerce support
        if (aqualuxe_is_woocommerce_activated()) {
            add_theme_support('woocommerce');
            add_theme_support('wc-product-gallery-zoom');
            add_theme_support('wc-product-gallery-lightbox');
            add_theme_support('wc-product-gallery-slider');
        }
        
        // Register navigation menus
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer'  => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile'  => esc_html__('Mobile Menu', 'aqualuxe'),
        ));
        
        // Set content width
        global $content_width;
        if (!isset($content_width)) {
            $content_width = 1200;
        }
        
        // Load text domain
        load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . '/languages');
    }

    /**
     * Initialize WordPress
     */
    public function init() {
        // Additional initialization can be added here
        do_action('aqualuxe_init');
    }

    /**
     * Enqueue front-end scripts and styles
     */
    public function enqueue_scripts() {
        $manifest = $this->get_webpack_manifest();
        
        // Main stylesheet
        if (isset($manifest['/css/app.css'])) {
            wp_enqueue_style(
                'aqualuxe-main',
                AQUALUXE_ASSETS_URL . $manifest['/css/app.css'],
                array(),
                null // Version handled by manifest
            );
        }
        
        // WooCommerce styles
        if (aqualuxe_is_woocommerce_activated() && isset($manifest['/css/woocommerce.css'])) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URL . $manifest['/css/woocommerce.css'],
                array('aqualuxe-main'),
                null
            );
        }
        
        // Accessibility styles
        if (isset($manifest['/css/accessibility.css'])) {
            wp_enqueue_style(
                'aqualuxe-accessibility',
                AQUALUXE_ASSETS_URL . $manifest['/css/accessibility.css'],
                array('aqualuxe-main'),
                null
            );
        }
        
        // Main JavaScript
        if (isset($manifest['/js/app.js'])) {
            wp_enqueue_script(
                'aqualuxe-main',
                AQUALUXE_ASSETS_URL . $manifest['/js/app.js'],
                array('jquery'),
                null,
                true
            );
        }
        
        // Accessibility JavaScript
        if (isset($manifest['/js/accessibility.js'])) {
            wp_enqueue_script(
                'aqualuxe-accessibility',
                AQUALUXE_ASSETS_URL . $manifest['/js/accessibility.js'],
                array('aqualuxe-main'),
                null,
                true
            );
        }
        
        // Localize scripts
        wp_localize_script('aqualuxe-main', 'aqualuxe_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('aqualuxe_nonce'),
            'strings'  => array(
                'loading' => esc_html__('Loading...', 'aqualuxe'),
                'error'   => esc_html__('An error occurred. Please try again.', 'aqualuxe'),
            ),
        ));
        
        // Comments script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
        
        // Add inline styles for custom colors
        $this->add_inline_styles();
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        $manifest = $this->get_webpack_manifest();
        
        // Admin styles
        if (isset($manifest['/css/admin.css'])) {
            wp_enqueue_style(
                'aqualuxe-admin',
                AQUALUXE_ASSETS_URL . $manifest['/css/admin.css'],
                array(),
                null
            );
        }
        
        // Admin JavaScript
        if (isset($manifest['/js/admin.js'])) {
            wp_enqueue_script(
                'aqualuxe-admin',
                AQUALUXE_ASSETS_URL . $manifest['/js/admin.js'],
                array('jquery'),
                null,
                true
            );
            
            // Localize admin script
            wp_localize_script('aqualuxe-admin', 'aqualuxe_admin', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('aqualuxe_admin_nonce'),
            ));
        }
    }

    /**
     * Add inline styles for customization
     */
    private function add_inline_styles() {
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#0ea5e9');
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#10b981');
        
        $custom_css = "
            :root {
                --aqualuxe-primary: {$primary_color};
                --aqualuxe-secondary: {$secondary_color};
            }
        ";
        
        wp_add_inline_style('aqualuxe-main', $custom_css);
    }

    /**
     * Get webpack manifest for cache busting
     *
     * @return array
     */
    private function get_webpack_manifest() {
        static $manifest = null;
        
        if ($manifest === null) {
            $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
            if (file_exists($manifest_path)) {
                $manifest = json_decode(file_get_contents($manifest_path), true);
            } else {
                $manifest = array();
            }
        }
        
        return $manifest;
    }

    /**
     * Load core includes following Clean Architecture
     */
    private function load_core_includes() {
        $includes = array(
            'customizer.php',
            'template-functions.php',
            'template-hooks.php',
            'custom-post-types.php',
            'custom-taxonomies.php',
            'meta-fields.php',
            'admin/admin-init.php',
        );

        // Load WooCommerce integration only if WooCommerce is active
        if (aqualuxe_is_woocommerce_activated()) {
            $includes[] = 'woocommerce/wc-integration.php';
        }

        foreach ($includes as $file) {
            $path = AQUALUXE_INCLUDES_DIR . '/' . $file;
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }

    /**
     * Initialize modules following modular architecture
     */
    private function init_modules() {
        // Core modules - always enabled
        if (class_exists('\\AquaLuxe\\Modules\\Multilingual\\Module')) {
            \AquaLuxe\Modules\Multilingual\Module::get_instance();
        }
        
        if (class_exists('\\AquaLuxe\\Modules\\Dark_Mode\\Module')) {
            \AquaLuxe\Modules\Dark_Mode\Module::get_instance();
        }
        
        if (class_exists('\\AquaLuxe\\Modules\\Performance\\Module')) {
            \AquaLuxe\Modules\Performance\Module::get_instance();
        }
        
        if (class_exists('\\AquaLuxe\\Modules\\Security\\Module')) {
            \AquaLuxe\Modules\Security\Module::get_instance();
        }
        
        if (class_exists('\\AquaLuxe\\Modules\\SEO\\Module')) {
            \AquaLuxe\Modules\SEO\Module::get_instance();
        }
        
        // Feature modules (configurable via customizer)
        $feature_modules = array(
            'subscriptions' => '\\AquaLuxe\\Modules\\Subscriptions\\Module',
            'bookings' => '\\AquaLuxe\\Modules\\Bookings\\Module',
            'events' => '\\AquaLuxe\\Modules\\Events\\Module',
            'auctions' => '\\AquaLuxe\\Modules\\Auctions\\Module',
            'wholesale' => '\\AquaLuxe\\Modules\\Wholesale\\Module',
            'franchise' => '\\AquaLuxe\\Modules\\Franchise\\Module',
            'rd' => '\\AquaLuxe\\Modules\\RD\\Module',
            'affiliates' => '\\AquaLuxe\\Modules\\Affiliates\\Module',
            'services' => '\\AquaLuxe\\Modules\\Services\\Module',
            'multivendor' => '\\AquaLuxe\\Modules\\Multivendor\\Module',
        );
        
        foreach ($feature_modules as $module_key => $module_class) {
            if (get_theme_mod('aqualuxe_enable_' . $module_key, true) && class_exists($module_class)) {
                $module_class::get_instance();
            }
        }
        
        do_action('aqualuxe_modules_loaded');
    }

    /**
     * Get service container
     *
     * @return \AquaLuxe_Service_Container
     */
    public function get_container() {
        return $this->container;
    }
}