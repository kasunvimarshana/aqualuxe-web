<?php
/**
 * Enterprise WordPress Theme Functions
 * 
 * Enterprise-grade WordPress theme with modular, multitenant, multivendor architecture
 * Integrates with AquaLuxe aquatic solutions platform
 * 
 * @package Enterprise_Theme
 * @version 2.0.0
 * @author Enterprise Development Team
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Enterprise Theme Constants
 * Configuration for enterprise WordPress architecture
 */
define('ENTERPRISE_THEME_VERSION', '2.0.0');
define('ENTERPRISE_THEME_PATH', get_template_directory());
define('ENTERPRISE_THEME_URL', get_template_directory_uri());
define('ENTERPRISE_THEME_TEXTDOMAIN', 'enterprise-theme');

/**
 * Initialize Enterprise Services
 * Load our comprehensive enterprise architecture
 */
function enterprise_theme_init_services() {
    // Load core enterprise services
    $enterprise_services = array(
        'inc/class-enterprise-theme-config.php',
        'inc/class-enterprise-theme-orchestrator.php',
        'inc/class-enterprise-theme-hook-manager.php',
        'inc/class-enterprise-theme-event-dispatcher.php',
        'inc/class-enterprise-theme-cache-service.php',
        'inc/class-enterprise-theme-security-service.php',
        'inc/class-enterprise-theme-database-service.php',
        'inc/class-enterprise-theme-tenant-service.php',
        'inc/class-enterprise-theme-vendor-service.php',
        'inc/class-enterprise-theme-language-service.php',
        'inc/class-enterprise-theme-currency-service.php',
    );
    
    foreach ($enterprise_services as $service) {
        $service_path = ENTERPRISE_THEME_PATH . '/' . $service;
        if (file_exists($service_path)) {
            require_once $service_path;
        }
    }
    
    // Initialize the enterprise orchestrator
    if (class_exists('Enterprise_Theme_Orchestrator')) {
        Enterprise_Theme_Orchestrator::get_instance();
    }
}

// Initialize enterprise services first
add_action('init', 'enterprise_theme_init_services', 1);

/**
 * Enterprise Theme Setup
 */
function enterprise_theme_setup() {
    // Load text domain
    load_theme_textdomain(ENTERPRISE_THEME_TEXTDOMAIN, ENTERPRISE_THEME_PATH . '/languages');
    
    // Add theme support
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_theme_support('html5', array(
        'search-form',
        'comment-form', 
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    
    // Custom logo support
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    
    // Navigation menus
    register_nav_menus(array(
        'primary'   => esc_html__('Primary Menu', ENTERPRISE_THEME_TEXTDOMAIN),
        'secondary' => esc_html__('Secondary Menu', ENTERPRISE_THEME_TEXTDOMAIN),
        'footer'    => esc_html__('Footer Menu', ENTERPRISE_THEME_TEXTDOMAIN),
        'vendor'    => esc_html__('Vendor Menu', ENTERPRISE_THEME_TEXTDOMAIN),
    ));
    
    // Image sizes
    add_image_size('enterprise-featured', 1200, 600, true);
    add_image_size('enterprise-card', 400, 250, true);
    add_image_size('enterprise-thumbnail', 150, 150, true);
}
add_action('after_setup_theme', 'enterprise_theme_setup');

/**
 * Enterprise Scripts and Styles
 */
function enterprise_theme_scripts() {
    // Main theme stylesheet
    wp_enqueue_style(
        'enterprise-theme-style',
        get_stylesheet_uri(),
        array(),
        ENTERPRISE_THEME_VERSION
    );
    
    // Main theme JavaScript
    wp_enqueue_script(
        'enterprise-theme-js',
        ENTERPRISE_THEME_URL . '/assets/js/theme.js',
        array('jquery'),
        ENTERPRISE_THEME_VERSION,
        true
    );
    
    // Localize script for AJAX
    wp_localize_script('enterprise-theme-js', 'enterpriseTheme', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('enterprise_theme_nonce'),
        'strings' => array(
            'loading' => esc_html__('Loading...', ENTERPRISE_THEME_TEXTDOMAIN),
            'error'   => esc_html__('Error occurred', ENTERPRISE_THEME_TEXTDOMAIN),
        )
    ));
}
add_action('wp_enqueue_scripts', 'enterprise_theme_scripts');

/**
 * Enterprise Helper Functions
 */

/**
 * Get the current tenant
 */
function enterprise_get_current_tenant() {
    if (class_exists('Enterprise_Theme_Tenant_Service')) {
        return Enterprise_Theme_Tenant_Service::get_instance()->get_current_tenant();
    }
    return null;
}

/**
 * Format price with current currency
 */
function enterprise_format_price($amount, $currency_code = null) {
    if (class_exists('Enterprise_Theme_Currency_Service')) {
        return Enterprise_Theme_Currency_Service::get_instance()->format_price($amount, $currency_code);
    }
    return '$' . number_format($amount, 2);
}

/**
 * Translate string with current language
 */
function enterprise_translate($text, $language_code = null) {
    if (class_exists('Enterprise_Theme_Language_Service')) {
        return Enterprise_Theme_Language_Service::get_instance()->translate($text, $language_code);
    }
    return $text;
}

/**
 * Check if user is vendor
 */
function enterprise_is_vendor($user_id = null) {
    if (class_exists('Enterprise_Theme_Vendor_Service')) {
        return Enterprise_Theme_Vendor_Service::get_instance()->is_vendor($user_id);
    }
    return false;
}

// Legacy AquaLuxe Theme integration follows below...

/**
 * AquaLuxe Enterprise Theme Functions
 * 
 * Premium ornamental aquatic solutions platform with enterprise WordPress architecture
 * Specialized for wholesale, retail, export, and trading of ornamental fish and aquatic products
 * 
 * @package AquaLuxe_Enterprise
 * @version 1.0.0
 * @author AquaLuxe Development Team
 * @since 1.0.0
 */

/**
 * Theme Constants
 * Centralized configuration for AquaLuxe business operations
 */
define('AQUALUXE_THEME_VERSION', '1.0.0');
define('AQUALUXE_THEME_PATH', get_template_directory());
define('AQUALUXE_THEME_URL', get_template_directory_uri());
define('AQUALUXE_THEME_ASSETS_URL', AQUALUXE_THEME_URL . '/assets');
define('AQUALUXE_THEME_INC_PATH', AQUALUXE_THEME_PATH . '/inc');
define('AQUALUXE_THEME_COMPONENTS_PATH', AQUALUXE_THEME_PATH . '/components');
define('AQUALUXE_THEME_TEMPLATES_PATH', AQUALUXE_THEME_PATH . '/templates');
define('AQUALUXE_THEME_TEXTDOMAIN', 'aqualuxe-enterprise');
define('AQUALUXE_MIN_PHP_VERSION', '8.0');
define('AQUALUXE_MIN_WP_VERSION', '6.0');

/**
 * PHP Version Check
 * Ensure minimum PHP version for modern aquatic platform development
 */
if (version_compare(PHP_VERSION, AQUALUXE_MIN_PHP_VERSION, '<')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>';
        printf(
            esc_html__('AquaLuxe Enterprise Theme requires PHP version %s or higher. You are running version %s.', AQUALUXE_THEME_TEXTDOMAIN),
            AQUALUXE_MIN_PHP_VERSION,
            PHP_VERSION
        );
        echo '</p></div>';
    });
    return;
}

/**
 * WordPress Version Check
 */
if (version_compare(get_bloginfo('version'), AQUALUXE_MIN_WP_VERSION, '<')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>';
        printf(
            esc_html__('AquaLuxe Enterprise Theme requires WordPress version %s or higher. You are running version %s.', AQUALUXE_THEME_TEXTDOMAIN),
            AQUALUXE_MIN_WP_VERSION,
            get_bloginfo('version')
        );
        echo '</p></div>';
    });
    return;
}

/**
 * Include AquaLuxe Configuration
 * Load business model and configuration classes
 */
require_once AQUALUXE_THEME_INC_PATH . '/aqualuxe-config.php';

/**
 * Include WooCommerce Integration
 * E-commerce functionality for aquatic products
 */
if (class_exists('WooCommerce')) {
    require_once AQUALUXE_THEME_INC_PATH . '/woocommerce-integration.php';
}

/**
 * Autoloader for Theme Classes
 * Implements PSR-4 autoloading standard adapted for AquaLuxe
 */
spl_autoload_register(function ($class_name) {
    // Only load classes with our namespace prefix
    if (strpos($class_name, 'AquaLuxe\\') !== 0) {
        return;
    }
    
    // Convert namespace to file path
    $class_file = str_replace('AquaLuxe\\', '', $class_name);
    $class_file = str_replace('\\', DIRECTORY_SEPARATOR, $class_file);
    $class_file = AQUALUXE_THEME_INC_PATH . '/classes/' . $class_file . '.php';
    
    if (file_exists($class_file)) {
        require_once $class_file;
    }
});

/**
 * Include Core Files
 * Modular architecture for AquaLuxe aquatic business platform
 */
$core_files = [
    // Core functionality
    'inc/functions/helpers.php',              // Helper functions
    'inc/functions/security.php',             // Security hardening
    'inc/functions/performance.php',          // Performance optimization
    'inc/functions/accessibility.php',        // Accessibility features
    'inc/functions/seo.php',                 // SEO optimization
    
    // AquaLuxe specific setup
    'inc/setup/theme-setup.php',            // Basic theme setup
    'inc/setup/assets.php',                 // Asset management
    'inc/setup/menus.php',                  // Navigation menus
    'inc/setup/sidebars.php',               // Widget areas
    'inc/setup/post-types.php',             // Custom post types for fish/products
    'inc/setup/taxonomies.php',             // Custom taxonomies for categorization
    
    // Features
    'inc/features/multitenancy.php',        // Multi-tenant support
    'inc/features/multivendor.php',         // Multi-vendor system
    'inc/features/multilingual.php',        // Multilingual support
    'inc/features/multicurrency.php',       // Multi-currency support
    'inc/features/themes.php',              // Multi-theme system
    'inc/features/user-roles.php',          // User role management
    'inc/features/api-integration.php',     // API integrations
    
    
    // Components
    'inc/components/component-loader.php',   // Component system
    'inc/components/shortcodes.php',        // Shortcode system
    
    // Admin
    'inc/admin/theme-options.php',          // Theme customization
    'inc/admin/dashboard.php',              // Custom dashboard
    'inc/admin/meta-boxes.php',             // Custom meta boxes
    
    // Ajax handlers
    'inc/ajax/handlers.php',                // AJAX request handlers
];

foreach ($core_files as $file) {
    $file_path = AQUALUXE_THEME_PATH . '/' . $file;
    if (file_exists($file_path)) {
        require_once $file_path;
    }
}

/**
 * AquaLuxe Theme Initialization Class
 * Main orchestrator for aquatic business platform
 */
class AquaLuxe_Enterprise_Theme {
    
    /**
     * Theme instance
     * Implements Singleton pattern for global access
     * 
     * @var AquaLuxe_Enterprise_Theme
     */
    private static $instance = null;
    
    /**
     * Module instances
     * 
     * @var array
     */
    private $modules = [];
    
    /**
     * AquaLuxe Configuration instance
     * 
     * @var AquaLuxe_Config
     */
    private $config = null;
    
    /**
     * Get theme instance
     * 
     * @return AquaLuxe_Enterprise_Theme
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     * Initialize AquaLuxe theme components
     */
    private function __construct() {
        $this->init_config();
        $this->init_hooks();
        $this->load_modules();
    }
    
    /**
     * Initialize AquaLuxe configuration
     * 
     * @return void
     */
    private function init_config() {
        if (class_exists('AquaLuxe_Config')) {
            $this->config = new AquaLuxe_Config();
        }
    }
    
    /**
     * Get AquaLuxe configuration instance
     * 
     * @return AquaLuxe_Config|null
     */
    public function get_config() {
        return $this->config;
    }
    
    /**
     * Initialize WordPress hooks
     * 
     * @return void
     */
    private function init_hooks() {
        add_action('after_setup_theme', [$this, 'setup_theme']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        add_action('init', [$this, 'init_modules']);
        add_action('widgets_init', [$this, 'register_sidebars']);
        
        // AquaLuxe specific hooks
        add_action('init', [$this, 'register_aquatic_post_types']);
        add_action('init', [$this, 'register_aquatic_taxonomies']);
        
        // Security hooks
        add_action('init', [$this, 'enhance_security']);
        
        // Performance hooks
        add_action('init', [$this, 'optimize_performance']);
        
        // SEO hooks
        add_action('wp_head', [$this, 'add_seo_meta']);
        
        // Accessibility hooks
        add_action('wp_head', [$this, 'add_accessibility_features']);
        
        // AJAX hooks
        add_action('wp_ajax_aqualuxe_ajax_handler', [$this, 'handle_ajax_request']);
        add_action('wp_ajax_nopriv_aqualuxe_ajax_handler', [$this, 'handle_ajax_request']);
    }
    
    /**
     * Load theme modules
     * Implements modular architecture for AquaLuxe business operations
     * 
     * @return void
     */
    private function load_modules() {
        $modules = [
            'security'      => 'AquaLuxe\\Security\\Security_Manager',
            'performance'   => 'AquaLuxe\\Performance\\Performance_Manager',
            'export'        => 'AquaLuxe\\Export\\Export_Manager',
            'wholesale'     => 'AquaLuxe\\Wholesale\\Wholesale_Manager',
            'multilingual'  => 'KV_Enterprise\\Multilingual\\Language_Manager',
            'multicurrency' => 'KV_Enterprise\\Multicurrency\\Currency_Manager',
            'themes'        => 'KV_Enterprise\\Themes\\Theme_Manager',
            'user_roles'    => 'KV_Enterprise\\Users\\Role_Manager',
            'api'           => 'KV_Enterprise\\API\\API_Manager',
            'seo'           => 'KV_Enterprise\\SEO\\SEO_Manager',
            'accessibility' => 'KV_Enterprise\\Accessibility\\Accessibility_Manager',
        ];
        
        foreach ($modules as $key => $class) {
            if (class_exists($class)) {
                $this->modules[$key] = new $class();
            }
        }
        
        /**
         * Filter to allow custom AquaLuxe modules
         * 
         * @param array $modules Array of module instances
         */
        $this->modules = apply_filters('aqualuxe_enterprise_modules', $this->modules);
    }
    
    /**
     * Get module instance
     * 
     * @param string $module_name Module name
     * @return object|null Module instance or null if not found
     */
    public function get_module($module_name) {
        return isset($this->modules[$module_name]) ? $this->modules[$module_name] : null;
    }
    
    /**
     * Theme setup
     * Configure AquaLuxe theme support and features
     * 
     * @return void
     */
    public function setup_theme() {
        // Make theme available for translation
        load_theme_textdomain(AQUALUXE_THEME_TEXTDOMAIN, AQUALUXE_THEME_PATH . '/languages');
        
        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');
        
        // Let WordPress manage the document title
        add_theme_support('title-tag');
        
        // Enable support for Post Thumbnails
        add_theme_support('post-thumbnails');
        
        // Add support for responsive embedded content
        add_theme_support('responsive-embeds');
        
        // Add support for editor styles
        add_theme_support('editor-styles');
        
        // Add support for full and wide align images
        add_theme_support('align-wide');
        
        // Add support for custom line height controls
        add_theme_support('custom-line-height');
        
        // Add support for custom units
        add_theme_support('custom-units');
        
        // Add support for custom spacing
        add_theme_support('custom-spacing');
        
        // Add support for custom logo
        add_theme_support('custom-logo', [
            'height'      => 100,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
        ]);
        
        // Add support for custom background
        add_theme_support('custom-background', [
            'default-color' => 'ffffff',
            'default-image' => '',
        ]);
        
        // Add support for HTML5 markup
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]);
        
        // Add support for post formats
        add_theme_support('post-formats', [
            'aside',
            'image',
            'video',
            'quote',
            'link',
            'gallery',
            'status',
            'audio',
            'chat'
        ]);
        
        // Add support for block styles
        add_theme_support('wp-block-styles');
        
        // Add support for editor color palette
        add_theme_support('editor-color-palette', [
            [
                'name'  => esc_html__('Primary', KV_THEME_TEXTDOMAIN),
                'slug'  => 'primary',
                'color' => '#007cba',
            ],
            [
                'name'  => esc_html__('Secondary', KV_THEME_TEXTDOMAIN),
                'slug'  => 'secondary',
                'color' => '#50575e',
            ],
            [
                'name'  => esc_html__('Accent', KV_THEME_TEXTDOMAIN),
                'slug'  => 'accent',
                'color' => '#e74c3c',
            ],
        ]);
        
        // Set up default content width
        if (!isset($content_width)) {
            $content_width = 800;
        }
        
        /**
         * Hook after theme setup
         * Allows modules and plugins to extend AquaLuxe theme setup
         */
        do_action('aqualuxe_enterprise_after_setup_theme');
    }
    
    /**
     * Enqueue frontend scripts and styles
     * Implements performance optimization and modularity for AquaLuxe
     * 
     * @return void
     */
    public function enqueue_scripts() {
        // Main stylesheet
        wp_enqueue_style(
            'aqualuxe-enterprise-style',
            get_stylesheet_uri(),
            [],
            AQUALUXE_THEME_VERSION
        );
        
        // AquaLuxe custom CSS
        wp_enqueue_style(
            'aqualuxe-custom',
            AQUALUXE_THEME_ASSETS_URL . '/css/aqualuxe.css',
            ['aqualuxe-enterprise-style'],
            AQUALUXE_THEME_VERSION
        );
        
        // Custom CSS for theme variations
        $custom_css = $this->get_custom_css();
        if (!empty($custom_css)) {
            wp_add_inline_style('aqualuxe-enterprise-style', $custom_css);
        }
        
        // Main JavaScript file
        wp_enqueue_script(
            'aqualuxe-enterprise-main',
            AQUALUXE_THEME_ASSETS_URL . '/js/main.js',
            ['jquery'],
            AQUALUXE_THEME_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('aqualuxe-enterprise-main', 'aqualuxeEnterprise', [
            'ajaxUrl'     => admin_url('admin-ajax.php'),
            'nonce'       => wp_create_nonce('aqualuxe_enterprise_nonce'),
            'textDomain'  => AQUALUXE_THEME_TEXTDOMAIN,
            'isRTL'       => is_rtl(),
            'businessModel' => $this->config ? $this->config->get_business_models() : [],
            'exportCountries' => $this->config ? $this->config->get_export_countries() : [],
            'breakpoints' => [
                'sm' => 576,
                'md' => 768,
                'lg' => 992,
                'xl' => 1200,
                '2xl' => 1400,
            ],
        ]);
        
        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
        
        /**
         * Hook after scripts enqueue
         * Allows modules to enqueue additional scripts
         */
        do_action('aqualuxe_enterprise_enqueue_scripts');
    }
    
    /**
     * Enqueue admin scripts and styles
     * 
     * @param string $hook_suffix Current admin page
     * @return void
     */
    public function enqueue_admin_scripts($hook_suffix) {
        // Admin stylesheet
        wp_enqueue_style(
            'aqualuxe-enterprise-admin',
            AQUALUXE_THEME_ASSETS_URL . '/css/admin.css',
            [],
            AQUALUXE_THEME_VERSION
        );
        
        // Admin JavaScript
        wp_enqueue_script(
            'aqualuxe-enterprise-admin',
            AQUALUXE_THEME_ASSETS_URL . '/js/admin.js',
            ['jquery'],
            AQUALUXE_THEME_VERSION,
            true
        );
        
        /**
         * Hook after admin scripts enqueue
         */
        do_action('aqualuxe_enterprise_enqueue_admin_scripts', $hook_suffix);
    }
    
    /**
     * Initialize modules
     * Called on WordPress 'init' hook
     * 
     * @return void
     */
    public function init_modules() {
        foreach ($this->modules as $module) {
            if (method_exists($module, 'init')) {
                $module->init();
            }
        }
        
        /**
         * Hook after modules initialization
         */
        do_action('aqualuxe_enterprise_modules_initialized', $this->modules);
    }
    
    /**
     * Register sidebars
     * 
     * @return void
     */
    public function register_sidebars() {
        // Primary sidebar
        register_sidebar([
            'name'          => esc_html__('Primary Sidebar', AQUALUXE_THEME_TEXTDOMAIN),
            'id'            => 'sidebar-primary',
            'description'   => esc_html__('Main sidebar that appears on the right.', AQUALUXE_THEME_TEXTDOMAIN),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);
        
        // Footer widgets
        for ($i = 1; $i <= 4; $i++) {
            register_sidebar([
                'name'          => sprintf(esc_html__('Footer %d', KV_THEME_TEXTDOMAIN), $i),
                'id'            => "footer-{$i}",
                'description'   => sprintf(esc_html__('Footer widget area %d.', KV_THEME_TEXTDOMAIN), $i),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]);
        }
        
        /**
         * Hook after sidebars registration
         */
        do_action('kv_enterprise_sidebars_registered');
    }
    
    /**
     * Enhance security
     * 
     * @return void
     */
    public function enhance_security() {
        $security_manager = $this->get_module('security');
        if ($security_manager) {
            $security_manager->apply_security_measures();
        }
    }
    
    /**
     * Optimize performance
     * 
     * @return void
     */
    public function optimize_performance() {
        $performance_manager = $this->get_module('performance');
        if ($performance_manager) {
            $performance_manager->apply_optimizations();
        }
    }
    
    /**
     * Add SEO meta tags
     * 
     * @return void
     */
    public function add_seo_meta() {
        $seo_manager = $this->get_module('seo');
        if ($seo_manager) {
            $seo_manager->render_meta_tags();
        }
    }
    
    /**
     * Add accessibility features
     * 
     * @return void
     */
    public function add_accessibility_features() {
        $accessibility_manager = $this->get_module('accessibility');
        if ($accessibility_manager) {
            $accessibility_manager->render_accessibility_features();
        }
    }
    
    /**
     * Handle AJAX requests
     * Centralized AJAX handler with security and validation
     * 
     * @return void
     */
    public function handle_ajax_request() {
        // Verify nonce for security
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'aqualuxe_enterprise_nonce')) {
            wp_die('Security check failed', 'Security Error', ['response' => 403]);
        }
        
        // Get action from request
        $action = sanitize_text_field($_POST['aqualuxe_action'] ?? '');
        
        if (empty($action)) {
            wp_send_json_error('No action specified');
        }
        
        // Route to appropriate handler
        $response = apply_filters("aqualuxe_enterprise_ajax_{$action}", null, $_POST);
        
        if (null === $response) {
            wp_send_json_error('Unknown action');
        }
        
        wp_send_json_success($response);
    }
    
    /**
     * Register aquatic-specific post types
     * 
     * @return void
     */
    public function register_aquatic_post_types() {
        if (!$this->config) {
            return;
        }
        
        // Fish Species Post Type
        register_post_type('fish_species', [
            'labels' => [
                'name' => __('Fish Species', AQUALUXE_THEME_TEXTDOMAIN),
                'singular_name' => __('Fish Species', AQUALUXE_THEME_TEXTDOMAIN),
                'add_new' => __('Add New Species', AQUALUXE_THEME_TEXTDOMAIN),
                'add_new_item' => __('Add New Fish Species', AQUALUXE_THEME_TEXTDOMAIN),
                'edit_item' => __('Edit Fish Species', AQUALUXE_THEME_TEXTDOMAIN),
                'new_item' => __('New Fish Species', AQUALUXE_THEME_TEXTDOMAIN),
                'view_item' => __('View Fish Species', AQUALUXE_THEME_TEXTDOMAIN),
                'search_items' => __('Search Fish Species', AQUALUXE_THEME_TEXTDOMAIN),
                'not_found' => __('No fish species found', AQUALUXE_THEME_TEXTDOMAIN),
                'not_found_in_trash' => __('No fish species found in trash', AQUALUXE_THEME_TEXTDOMAIN),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-admin-site-alt',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'rewrite' => ['slug' => 'fish-species'],
            'show_in_rest' => true,
        ]);
        
        // Aquatic Services Post Type
        register_post_type('aquatic_service', [
            'labels' => [
                'name' => __('Aquatic Services', AQUALUXE_THEME_TEXTDOMAIN),
                'singular_name' => __('Service', AQUALUXE_THEME_TEXTDOMAIN),
                'add_new' => __('Add New Service', AQUALUXE_THEME_TEXTDOMAIN),
                'add_new_item' => __('Add New Aquatic Service', AQUALUXE_THEME_TEXTDOMAIN),
                'edit_item' => __('Edit Service', AQUALUXE_THEME_TEXTDOMAIN),
                'new_item' => __('New Service', AQUALUXE_THEME_TEXTDOMAIN),
                'view_item' => __('View Service', AQUALUXE_THEME_TEXTDOMAIN),
                'search_items' => __('Search Services', AQUALUXE_THEME_TEXTDOMAIN),
                'not_found' => __('No services found', AQUALUXE_THEME_TEXTDOMAIN),
                'not_found_in_trash' => __('No services found in trash', AQUALUXE_THEME_TEXTDOMAIN),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-admin-tools',
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
            'rewrite' => ['slug' => 'services'],
            'show_in_rest' => true,
        ]);
        
        // Export Documentation Post Type
        register_post_type('export_doc', [
            'labels' => [
                'name' => __('Export Documentation', AQUALUXE_THEME_TEXTDOMAIN),
                'singular_name' => __('Export Document', AQUALUXE_THEME_TEXTDOMAIN),
                'add_new' => __('Add New Document', AQUALUXE_THEME_TEXTDOMAIN),
                'add_new_item' => __('Add New Export Document', AQUALUXE_THEME_TEXTDOMAIN),
                'edit_item' => __('Edit Document', AQUALUXE_THEME_TEXTDOMAIN),
                'new_item' => __('New Document', AQUALUXE_THEME_TEXTDOMAIN),
                'view_item' => __('View Document', AQUALUXE_THEME_TEXTDOMAIN),
                'search_items' => __('Search Documents', AQUALUXE_THEME_TEXTDOMAIN),
                'not_found' => __('No documents found', AQUALUXE_THEME_TEXTDOMAIN),
                'not_found_in_trash' => __('No documents found in trash', AQUALUXE_THEME_TEXTDOMAIN),
            ],
            'public' => true,
            'has_archive' => true,
            'menu_icon' => 'dashicons-media-document',
            'supports' => ['title', 'editor', 'thumbnail'],
            'rewrite' => ['slug' => 'export-docs'],
            'show_in_rest' => true,
        ]);
    }
    
    /**
     * Register aquatic-specific taxonomies
     * 
     * @return void
     */
    public function register_aquatic_taxonomies() {
        if (!$this->config) {
            return;
        }
        
        // Water Type Taxonomy
        register_taxonomy('water_type', ['fish_species', 'product'], [
            'labels' => [
                'name' => __('Water Types', AQUALUXE_THEME_TEXTDOMAIN),
                'singular_name' => __('Water Type', AQUALUXE_THEME_TEXTDOMAIN),
                'search_items' => __('Search Water Types', AQUALUXE_THEME_TEXTDOMAIN),
                'all_items' => __('All Water Types', AQUALUXE_THEME_TEXTDOMAIN),
                'edit_item' => __('Edit Water Type', AQUALUXE_THEME_TEXTDOMAIN),
                'update_item' => __('Update Water Type', AQUALUXE_THEME_TEXTDOMAIN),
                'add_new_item' => __('Add New Water Type', AQUALUXE_THEME_TEXTDOMAIN),
                'new_item_name' => __('New Water Type Name', AQUALUXE_THEME_TEXTDOMAIN),
                'menu_name' => __('Water Types', AQUALUXE_THEME_TEXTDOMAIN),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'water-type'],
            'show_in_rest' => true,
        ]);
        
        // Care Level Taxonomy
        register_taxonomy('care_level', ['fish_species'], [
            'labels' => [
                'name' => __('Care Levels', AQUALUXE_THEME_TEXTDOMAIN),
                'singular_name' => __('Care Level', AQUALUXE_THEME_TEXTDOMAIN),
                'search_items' => __('Search Care Levels', AQUALUXE_THEME_TEXTDOMAIN),
                'all_items' => __('All Care Levels', AQUALUXE_THEME_TEXTDOMAIN),
                'edit_item' => __('Edit Care Level', AQUALUXE_THEME_TEXTDOMAIN),
                'update_item' => __('Update Care Level', AQUALUXE_THEME_TEXTDOMAIN),
                'add_new_item' => __('Add New Care Level', AQUALUXE_THEME_TEXTDOMAIN),
                'new_item_name' => __('New Care Level Name', AQUALUXE_THEME_TEXTDOMAIN),
                'menu_name' => __('Care Levels', AQUALUXE_THEME_TEXTDOMAIN),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'care-level'],
            'show_in_rest' => true,
        ]);
        
        // Export Countries Taxonomy
        register_taxonomy('export_country', ['export_doc', 'product'], [
            'labels' => [
                'name' => __('Export Countries', AQUALUXE_THEME_TEXTDOMAIN),
                'singular_name' => __('Export Country', AQUALUXE_THEME_TEXTDOMAIN),
                'search_items' => __('Search Countries', AQUALUXE_THEME_TEXTDOMAIN),
                'all_items' => __('All Countries', AQUALUXE_THEME_TEXTDOMAIN),
                'edit_item' => __('Edit Country', AQUALUXE_THEME_TEXTDOMAIN),
                'update_item' => __('Update Country', AQUALUXE_THEME_TEXTDOMAIN),
                'add_new_item' => __('Add New Country', AQUALUXE_THEME_TEXTDOMAIN),
                'new_item_name' => __('New Country Name', AQUALUXE_THEME_TEXTDOMAIN),
                'menu_name' => __('Export Countries', AQUALUXE_THEME_TEXTDOMAIN),
            ],
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'export-country'],
            'show_in_rest' => true,
        ]);
    }
    
    /**
     * Get custom CSS for theme variations
     * 
     * @return string Custom CSS
     */
    private function get_custom_css() {
        $theme_manager = $this->get_module('themes');
        if ($theme_manager) {
            return $theme_manager->get_current_theme_css();
        }
        return '';
    }
}

/**
 * Initialize AquaLuxe theme
 * 
 * @return AquaLuxe_Enterprise_Theme
 */
function aqualuxe_enterprise_theme() {
    return AquaLuxe_Enterprise_Theme::get_instance();
}

/**
 * Get AquaLuxe configuration
 * 
 * @return AquaLuxe_Config|null
 */
function aqualuxe_get_config() {
    return aqualuxe_enterprise_theme()->get_config();
}

// Start the AquaLuxe theme
aqualuxe_enterprise_theme();
