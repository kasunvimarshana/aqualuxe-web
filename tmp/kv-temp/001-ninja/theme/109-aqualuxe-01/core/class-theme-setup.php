<?php
/**
 * Theme Setup Class
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

defined('ABSPATH') || exit;

/**
 * Theme Setup Class
 */
class Theme_Setup {

    /**
     * Instance
     *
     * @var Theme_Setup
     */
    private static $instance = null;

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
        
        // Enqueue editor styles
        add_editor_style('assets/dist/css/app.css');
        
        // Register navigation menus
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer'  => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile'  => esc_html__('Mobile Menu', 'aqualuxe'),
        ));
        
        // Set content width
        $GLOBALS['content_width'] = 1200;
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        $manifest = $this->get_manifest();
        $version = AQUALUXE_VERSION;
        
        // Theme styles
        if (isset($manifest['/css/app.css'])) {
            wp_enqueue_style(
                'aqualuxe-style',
                get_theme_file_uri('assets/dist' . $manifest['/css/app.css']),
                array(),
                $version
            );
        }
        
        // WooCommerce styles
        if (class_exists('WooCommerce') && isset($manifest['/css/woocommerce.css'])) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                get_theme_file_uri('assets/dist' . $manifest['/css/woocommerce.css']),
                array('aqualuxe-style'),
                $version
            );
        }
        
        // Theme scripts
        if (isset($manifest['/js/app.js'])) {
            wp_enqueue_script(
                'aqualuxe-script',
                get_theme_file_uri('assets/dist' . $manifest['/js/app.js']),
                array(),
                $version,
                true
            );
        }
        
        // Localize script
        wp_localize_script('aqualuxe-script', 'aqualuxe_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('aqualuxe_ajax_nonce'),
        ));
        
        // Comments script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        $manifest = $this->get_manifest();
        $version = AQUALUXE_VERSION;
        
        // Admin styles
        if (isset($manifest['/css/admin.css'])) {
            wp_enqueue_style(
                'aqualuxe-admin-style',
                get_theme_file_uri('assets/dist' . $manifest['/css/admin.css']),
                array(),
                $version
            );
        }
        
        // Admin scripts
        if (isset($manifest['/js/admin.js'])) {
            wp_enqueue_script(
                'aqualuxe-admin-script',
                get_theme_file_uri('assets/dist' . $manifest['/js/admin.js']),
                array('jquery'),
                $version,
                true
            );
        }
        
        // Localize admin script
        wp_localize_script('aqualuxe-admin-script', 'aqualuxe_admin', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('aqualuxe_admin_nonce'),
        ));
    }

    /**
     * Initialize theme
     */
    public function init() {
        // Load text domain
        load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');
        
        // Initialize modules
        $this->init_modules();
    }

    /**
     * Initialize modules
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
        
        // Feature modules (if enabled in customizer)
        if (get_theme_mod('aqualuxe_enable_subscriptions', true) && class_exists('\\AquaLuxe\\Modules\\Subscriptions\\Module')) {
            \AquaLuxe\Modules\Subscriptions\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_bookings', true) && class_exists('\\AquaLuxe\\Modules\\Bookings\\Module')) {
            \AquaLuxe\Modules\Bookings\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_events', true) && class_exists('\\AquaLuxe\\Modules\\Events\\Module')) {
            \AquaLuxe\Modules\Events\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_auctions', true) && class_exists('\\AquaLuxe\\Modules\\Auctions\\Module')) {
            \AquaLuxe\Modules\Auctions\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_wholesale', true) && class_exists('\\AquaLuxe\\Modules\\Wholesale\\Module')) {
            \AquaLuxe\Modules\Wholesale\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_franchise', true) && class_exists('\\AquaLuxe\\Modules\\Franchise\\Module')) {
            \AquaLuxe\Modules\Franchise\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_rd', true) && class_exists('\\AquaLuxe\\Modules\\RD\\Module')) {
            \AquaLuxe\Modules\RD\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_affiliates', true) && class_exists('\\AquaLuxe\\Modules\\Affiliates\\Module')) {
            \AquaLuxe\Modules\Affiliates\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_services', true) && class_exists('\\AquaLuxe\\Modules\\Services\\Module')) {
            \AquaLuxe\Modules\Services\Module::get_instance();
        }
        
        // Additional feature modules
        if (get_theme_mod('aqualuxe_enable_subscriptions', true) && class_exists('\\AquaLuxe\\Modules\\Subscriptions\\Module')) {
            \AquaLuxe\Modules\Subscriptions\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_bookings', true) && class_exists('\\AquaLuxe\\Modules\\Bookings\\Module')) {
            \AquaLuxe\Modules\Bookings\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_events', true) && class_exists('\\AquaLuxe\\Modules\\Events\\Module')) {
            \AquaLuxe\Modules\Events\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_auctions', true) && class_exists('\\AquaLuxe\\Modules\\Auctions\\Module')) {
            \AquaLuxe\Modules\Auctions\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_wholesale', true) && class_exists('\\AquaLuxe\\Modules\\Wholesale\\Module')) {
            \AquaLuxe\Modules\Wholesale\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_franchise', true) && class_exists('\\AquaLuxe\\Modules\\Franchise\\Module')) {
            \AquaLuxe\Modules\Franchise\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_rd', true) && class_exists('\\AquaLuxe\\Modules\\RD\\Module')) {
            \AquaLuxe\Modules\RD\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_affiliates', true) && class_exists('\\AquaLuxe\\Modules\\Affiliates\\Module')) {
            \AquaLuxe\Modules\Affiliates\Module::get_instance();
        }
        
        if (get_theme_mod('aqualuxe_enable_multivendor', true) && class_exists('\\AquaLuxe\\Modules\\Multivendor\\Module')) {
            \AquaLuxe\Modules\Multivendor\Module::get_instance();
        }
    }

    /**
     * Get webpack manifest
     *
     * @return array
     */
    private function get_manifest() {
        static $manifest = null;
        
        if (null === $manifest) {
            $manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
            
            if (file_exists($manifest_path)) {
                $manifest = json_decode(file_get_contents($manifest_path), true);
            } else {
                $manifest = array();
            }
        }
        
        return $manifest;
    }
}