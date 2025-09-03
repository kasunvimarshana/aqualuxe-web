<?php
/**
 * KV Enterprise Theme Functions
 * 
 * Main theme setup file implementing enterprise-grade WordPress theme architecture
 * following SOLID principles, DRY, KISS, YAGNI, and separation of concerns.
 * 
 * @package KV_Enterprise
 * @version 1.0.0
 * @author Enterprise Development Team
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

/**
 * Theme Constants
 * Centralized configuration for maintainability
 */
define('KV_THEME_VERSION', '1.0.0');
define('KV_THEME_PATH', get_template_directory());
define('KV_THEME_URL', get_template_directory_uri());
define('KV_THEME_ASSETS_URL', KV_THEME_URL . '/assets');
define('KV_THEME_INC_PATH', KV_THEME_PATH . '/inc');
define('KV_THEME_COMPONENTS_PATH', KV_THEME_PATH . '/components');
define('KV_THEME_TEMPLATES_PATH', KV_THEME_PATH . '/templates');
define('KV_THEME_TEXTDOMAIN', 'kv-enterprise');
define('KV_MIN_PHP_VERSION', '8.0');
define('KV_MIN_WP_VERSION', '6.0');

/**
 * PHP Version Check
 * Ensure minimum PHP version for modern development practices
 */
if (version_compare(PHP_VERSION, KV_MIN_PHP_VERSION, '<')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>';
        printf(
            esc_html__('KV Enterprise Theme requires PHP version %s or higher. You are running version %s.', KV_THEME_TEXTDOMAIN),
            KV_MIN_PHP_VERSION,
            PHP_VERSION
        );
        echo '</p></div>';
    });
    return;
}

/**
 * WordPress Version Check
 */
if (version_compare(get_bloginfo('version'), KV_MIN_WP_VERSION, '<')) {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p>';
        printf(
            esc_html__('KV Enterprise Theme requires WordPress version %s or higher. You are running version %s.', KV_THEME_TEXTDOMAIN),
            KV_MIN_WP_VERSION,
            get_bloginfo('version')
        );
        echo '</p></div>';
    });
    return;
}

/**
 * Autoloader for Theme Classes
 * Implements PSR-4 autoloading standard
 */
spl_autoload_register(function ($class_name) {
    // Only load classes with our namespace prefix
    if (strpos($class_name, 'KV_Enterprise\\') !== 0) {
        return;
    }
    
    // Convert namespace to file path
    $class_file = str_replace('KV_Enterprise\\', '', $class_name);
    $class_file = str_replace('\\', DIRECTORY_SEPARATOR, $class_file);
    $class_file = KV_THEME_INC_PATH . '/classes/' . $class_file . '.php';
    
    if (file_exists($class_file)) {
        require_once $class_file;
    }
});

/**
 * Include Core Files
 * Modular architecture for better organization
 */
$core_files = [
    // Core functionality
    'inc/functions/helpers.php',              // Helper functions
    'inc/functions/security.php',             // Security hardening
    'inc/functions/performance.php',          // Performance optimization
    'inc/functions/accessibility.php',        // Accessibility features
    'inc/functions/seo.php',                 // SEO optimization
    
    // Theme setup
    'inc/setup/theme-setup.php',            // Basic theme setup
    'inc/setup/assets.php',                 // Asset management
    'inc/setup/menus.php',                  // Navigation menus
    'inc/setup/sidebars.php',               // Widget areas
    'inc/setup/post-types.php',             // Custom post types
    'inc/setup/taxonomies.php',             // Custom taxonomies
    
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
    $file_path = KV_THEME_PATH . '/' . $file;
    if (file_exists($file_path)) {
        require_once $file_path;
    }
}

/**
 * Theme Initialization Class
 * Main orchestrator following Single Responsibility Principle
 */
class KV_Enterprise_Theme {
    
    /**
     * Theme instance
     * Implements Singleton pattern for global access
     * 
     * @var KV_Enterprise_Theme
     */
    private static $instance = null;
    
    /**
     * Module instances
     * 
     * @var array
     */
    private $modules = [];
    
    /**
     * Get theme instance
     * 
     * @return KV_Enterprise_Theme
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     * Initialize theme components
     */
    private function __construct() {
        $this->init_hooks();
        $this->load_modules();
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
        
        // Security hooks
        add_action('init', [$this, 'enhance_security']);
        
        // Performance hooks
        add_action('init', [$this, 'optimize_performance']);
        
        // SEO hooks
        add_action('wp_head', [$this, 'add_seo_meta']);
        
        // Accessibility hooks
        add_action('wp_head', [$this, 'add_accessibility_features']);
        
        // AJAX hooks
        add_action('wp_ajax_kv_ajax_handler', [$this, 'handle_ajax_request']);
        add_action('wp_ajax_nopriv_kv_ajax_handler', [$this, 'handle_ajax_request']);
    }
    
    /**
     * Load theme modules
     * Implements modular architecture for extensibility
     * 
     * @return void
     */
    private function load_modules() {
        $modules = [
            'security'      => 'KV_Enterprise\\Security\\Security_Manager',
            'performance'   => 'KV_Enterprise\\Performance\\Performance_Manager',
            'multitenancy'  => 'KV_Enterprise\\Multitenancy\\Tenant_Manager',
            'multivendor'   => 'KV_Enterprise\\Multivendor\\Vendor_Manager',
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
         * Filter to allow custom modules
         * 
         * @param array $modules Array of module instances
         */
        $this->modules = apply_filters('kv_enterprise_modules', $this->modules);
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
     * Configure theme support and features
     * 
     * @return void
     */
    public function setup_theme() {
        // Make theme available for translation
        load_theme_textdomain(KV_THEME_TEXTDOMAIN, KV_THEME_PATH . '/languages');
        
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
         * Allows modules and plugins to extend theme setup
         */
        do_action('kv_enterprise_after_setup_theme');
    }
    
    /**
     * Enqueue frontend scripts and styles
     * Implements performance optimization and modularity
     * 
     * @return void
     */
    public function enqueue_scripts() {
        // Main stylesheet
        wp_enqueue_style(
            'kv-enterprise-style',
            get_stylesheet_uri(),
            [],
            KV_THEME_VERSION
        );
        
        // Custom CSS for theme variations
        $custom_css = $this->get_custom_css();
        if (!empty($custom_css)) {
            wp_add_inline_style('kv-enterprise-style', $custom_css);
        }
        
        // Main JavaScript file
        wp_enqueue_script(
            'kv-enterprise-main',
            KV_THEME_ASSETS_URL . '/js/main.js',
            ['jquery'],
            KV_THEME_VERSION,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('kv-enterprise-main', 'kvEnterprise', [
            'ajaxUrl'     => admin_url('admin-ajax.php'),
            'nonce'       => wp_create_nonce('kv_enterprise_nonce'),
            'textDomain'  => KV_THEME_TEXTDOMAIN,
            'isRTL'       => is_rtl(),
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
        do_action('kv_enterprise_enqueue_scripts');
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
            'kv-enterprise-admin',
            KV_THEME_ASSETS_URL . '/css/admin.css',
            [],
            KV_THEME_VERSION
        );
        
        // Admin JavaScript
        wp_enqueue_script(
            'kv-enterprise-admin',
            KV_THEME_ASSETS_URL . '/js/admin.js',
            ['jquery'],
            KV_THEME_VERSION,
            true
        );
        
        /**
         * Hook after admin scripts enqueue
         */
        do_action('kv_enterprise_enqueue_admin_scripts', $hook_suffix);
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
        do_action('kv_enterprise_modules_initialized', $this->modules);
    }
    
    /**
     * Register sidebars
     * 
     * @return void
     */
    public function register_sidebars() {
        // Primary sidebar
        register_sidebar([
            'name'          => esc_html__('Primary Sidebar', KV_THEME_TEXTDOMAIN),
            'id'            => 'sidebar-primary',
            'description'   => esc_html__('Main sidebar that appears on the right.', KV_THEME_TEXTDOMAIN),
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
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'kv_enterprise_nonce')) {
            wp_die('Security check failed', 'Security Error', ['response' => 403]);
        }
        
        // Get action from request
        $action = sanitize_text_field($_POST['kv_action'] ?? '');
        
        if (empty($action)) {
            wp_send_json_error('No action specified');
        }
        
        // Route to appropriate handler
        $response = apply_filters("kv_enterprise_ajax_{$action}", null, $_POST);
        
        if (null === $response) {
            wp_send_json_error('Unknown action');
        }
        
        wp_send_json_success($response);
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
 * Initialize the theme
 * 
 * @return KV_Enterprise_Theme
 */
function kv_enterprise_theme() {
    return KV_Enterprise_Theme::get_instance();
}

// Start the theme
kv_enterprise_theme();
