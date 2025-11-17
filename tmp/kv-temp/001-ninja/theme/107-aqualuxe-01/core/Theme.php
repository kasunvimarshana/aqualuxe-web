<?php
/**
 * Main Theme Class
 *
 * Central theme orchestrator that follows SOLID principles and implements
 * a clean architecture pattern. Manages all theme components and modules.
 *
 * @package AquaLuxe\Core
 * @version 1.0.0
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Interfaces\ThemeInterface;
use AquaLuxe\Core\Services\AssetManager;
use AquaLuxe\Core\Services\HookManager;
use AquaLuxe\Core\Services\ModuleManager;

/**
 * Main Theme Class
 */
class Theme implements ThemeInterface {
    
    /**
     * Theme instance
     *
     * @var Theme
     */
    private static $instance = null;
    
    /**
     * Asset manager instance
     *
     * @var AssetManager
     */
    private $asset_manager;
    
    /**
     * Hook manager instance
     *
     * @var HookManager
     */
    private $hook_manager;
    
    /**
     * Module manager instance
     *
     * @var ModuleManager
     */
    private $module_manager;
    
    /**
     * WooCommerce integration instance
     *
     * @var WooCommerceIntegration
     */
    private $woocommerce_integration;
    
    /**
     * Theme configuration
     *
     * @var array
     */
    private $config = [];
    
    /**
     * Initialization status
     *
     * @var bool
     */
    private $initialized = false;

    /**
     * Private constructor for singleton pattern
     */
    private function __construct() {
        $this->load_config();
        $this->setup_managers();
    }

    /**
     * Get theme instance (Singleton)
     *
     * @return Theme
     */
    public static function getInstance(): Theme {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize theme
     *
     * @return void
     */
    public function init(): void {
        if ($this->initialized) {
            return;
        }

        // Setup WordPress theme support
        $this->setup_theme_support();
        
        // Register hooks
        $this->register_hooks();
        
        // Initialize managers
        $this->asset_manager->init();
        $this->hook_manager->init();
        $this->module_manager->init();
        $this->woocommerce_integration->init();
        
        // Load theme components
        $this->load_components();
        
        $this->initialized = true;
        
        // Fire action for other components
        do_action('aqualuxe_theme_initialized', $this);
    }

    /**
     * Load theme configuration
     *
     * @return void
     */
    private function load_config(): void {
        $config_file = AQUALUXE_PATH . '/config/theme.php';
        
        if (file_exists($config_file)) {
            $this->config = require $config_file;
        } else {
            // Default configuration
            $this->config = [
                'theme_support' => [
                    'title-tag',
                    'post-thumbnails',
                    'custom-logo',
                    'custom-header',
                    'custom-background',
                    'customize-selective-refresh-widgets',
                    'html5' => [
                        'search-form',
                        'comment-form', 
                        'comment-list',
                        'gallery',
                        'caption',
                        'style',
                        'script',
                    ],
                    'post-formats' => [
                        'aside',
                        'gallery',
                        'link',
                        'image',
                        'quote',
                        'status',
                        'video',
                        'audio',
                        'chat',
                    ],
                    'woocommerce',
                    'wc-product-gallery-zoom',
                    'wc-product-gallery-lightbox',
                    'wc-product-gallery-slider',
                ],
                'image_sizes' => [
                    'aqualuxe-featured' => [800, 450, true],
                    'aqualuxe-gallery' => [400, 300, true],
                    'aqualuxe-card' => [350, 250, true],
                    'aqualuxe-hero' => [1920, 800, true],
                ],
                'nav_menus' => [
                    'primary' => __('Primary Menu', 'aqualuxe'),
                    'footer' => __('Footer Menu', 'aqualuxe'),
                    'mobile' => __('Mobile Menu', 'aqualuxe'),
                ],
                'widgets' => [
                    'sidebar-primary',
                    'sidebar-shop',
                    'footer-1',
                    'footer-2', 
                    'footer-3',
                    'footer-4',
                ],
                'modules' => [
                    'multilingual' => true,
                    'dark-mode' => true,
                    'subscriptions' => true,
                    'bookings' => true,
                    'events' => true,
                    'auctions' => true,
                    'wholesale' => true,
                    'franchise' => true,
                    'sustainability' => true,
                    'affiliate' => true,
                    'services' => true,
                    'multivendor' => true,
                ],
            ];
        }
    }

    /**
     * Setup service managers
     *
     * @return void
     */
    private function setup_managers(): void {
        $this->asset_manager = new AssetManager();
        $this->hook_manager = new HookManager();
        $this->module_manager = new ModuleManager($this->config['modules'] ?? []);
        $this->woocommerce_integration = new Services\WooCommerceIntegration();
    }

    /**
     * Setup WordPress theme support
     *
     * @return void
     */
    private function setup_theme_support(): void {
        foreach ($this->config['theme_support'] as $feature => $options) {
            if (is_numeric($feature)) {
                add_theme_support($options);
            } else {
                add_theme_support($feature, $options);
            }
        }

        // Add custom image sizes
        foreach ($this->config['image_sizes'] as $name => $size) {
            add_image_size($name, $size[0], $size[1], $size[2] ?? false);
        }

        // Register navigation menus
        register_nav_menus($this->config['nav_menus']);
        
        // Set content width
        if (!isset($GLOBALS['content_width'])) {
            $GLOBALS['content_width'] = 1200;
        }
    }

    /**
     * Register theme hooks
     *
     * @return void
     */
    private function register_hooks(): void {
        // Widget areas
        add_action('widgets_init', [$this, 'register_widget_areas']);
        
        // Customizer
        add_action('customize_register', [$this, 'customize_register']);
        
        // Admin enhancements
        add_action('admin_init', [$this, 'admin_init']);
        
        // Frontend initialization
        add_action('wp', [$this, 'frontend_init']);
        
        // Head cleanup
        add_action('init', [$this, 'head_cleanup']);
    }

    /**
     * Load theme components
     *
     * @return void
     */
    private function load_components(): void {
        $components = [
            '/inc/customizer/Customizer.php',
            '/inc/admin/AdminEnhancements.php',
            '/inc/post-types/PostTypeManager.php',
            '/inc/taxonomies/TaxonomyManager.php',
            '/inc/widgets/WidgetManager.php',
            '/inc/shortcodes/ShortcodeManager.php',
        ];

        foreach ($components as $component) {
            $file = AQUALUXE_PATH . $component;
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }

    /**
     * Register widget areas
     *
     * @return void
     */
    public function register_widget_areas(): void {
        $widget_areas = [
            'sidebar-primary' => [
                'name' => __('Primary Sidebar', 'aqualuxe'),
                'description' => __('Main sidebar for blog and pages', 'aqualuxe'),
            ],
            'sidebar-shop' => [
                'name' => __('Shop Sidebar', 'aqualuxe'),
                'description' => __('Sidebar for WooCommerce shop pages', 'aqualuxe'),
            ],
            'footer-1' => [
                'name' => __('Footer Column 1', 'aqualuxe'),
                'description' => __('First footer widget area', 'aqualuxe'),
            ],
            'footer-2' => [
                'name' => __('Footer Column 2', 'aqualuxe'),
                'description' => __('Second footer widget area', 'aqualuxe'),
            ],
            'footer-3' => [
                'name' => __('Footer Column 3', 'aqualuxe'),
                'description' => __('Third footer widget area', 'aqualuxe'),
            ],
            'footer-4' => [
                'name' => __('Footer Column 4', 'aqualuxe'),
                'description' => __('Fourth footer widget area', 'aqualuxe'),
            ],
        ];

        foreach ($widget_areas as $id => $args) {
            register_sidebar(array_merge([
                'id' => $id,
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ], $args));
        }
    }

    /**
     * Customizer registration
     *
     * @param \WP_Customize_Manager $wp_customize
     * @return void
     */
    public function customize_register($wp_customize): void {
        // Will be handled by Customizer class
        if (class_exists('AquaLuxe\\Inc\\Customizer\\Customizer')) {
            $customizer = new \AquaLuxe\Inc\Customizer\Customizer($wp_customize);
            $customizer->register();
        }
    }

    /**
     * Admin initialization
     *
     * @return void
     */
    public function admin_init(): void {
        // Admin enhancements will be handled by AdminEnhancements class
    }

    /**
     * Frontend initialization
     *
     * @return void
     */
    public function frontend_init(): void {
        // Frontend-specific initialization
    }

    /**
     * Clean up WordPress head
     *
     * @return void
     */
    public function head_cleanup(): void {
        // Remove unnecessary WordPress head elements
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    }

    /**
     * Get asset manager
     *
     * @return AssetManager
     */
    public function getAssetManager(): AssetManager {
        return $this->asset_manager;
    }

    /**
     * Get hook manager
     *
     * @return HookManager
     */
    public function getHookManager(): HookManager {
        return $this->hook_manager;
    }

    /**
     * Get module manager
     *
     * @return ModuleManager
     */
    public function getModuleManager(): ModuleManager {
        return $this->module_manager;
    }

    /**
     * Get WooCommerce integration
     *
     * @return WooCommerceIntegration
     */
    public function getWooCommerceIntegration(): Services\WooCommerceIntegration {
        return $this->woocommerce_integration;
    }

    /**
     * Get theme configuration
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getConfig($key = null, $default = null) {
        if ($key === null) {
            return $this->config;
        }
        
        return $this->config[$key] ?? $default;
    }

    /**
     * Check if theme is initialized
     *
     * @return bool
     */
    public function isInitialized(): bool {
        return $this->initialized;
    }

    /**
     * Prevent cloning
     */
    private function __clone() {}

    /**
     * Prevent unserialization
     */
    private function __wakeup() {}
}