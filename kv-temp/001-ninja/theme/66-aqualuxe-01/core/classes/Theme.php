<?php
/**
 * Main Theme Class
 *
 * @package AquaLuxe
 */

namespace AquaLuxe;

/**
 * Main Theme Class
 * 
 * This class is responsible for initializing the theme and loading all required components.
 * It follows the singleton pattern to ensure only one instance is created.
 */
class Theme {
    /**
     * Instance of this class
     *
     * @var Theme
     */
    private static $instance = null;

    /**
     * Active modules
     *
     * @var array
     */
    private $active_modules = [];

    /**
     * Get the singleton instance
     *
     * @return Theme
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
        $this->load_core_files();
        $this->init_hooks();
        $this->load_modules();
    }

    /**
     * Load core files
     */
    private function load_core_files() {
        // Load helpers
        require_once AQUALUXE_CORE_DIR . 'helpers/template.php';
        require_once AQUALUXE_CORE_DIR . 'helpers/assets.php';
        
        // Load hooks
        require_once AQUALUXE_CORE_DIR . 'hooks/actions.php';
        require_once AQUALUXE_CORE_DIR . 'hooks/filters.php';
        
        // Load core functionality
        require_once AQUALUXE_INC_DIR . 'setup.php';
        require_once AQUALUXE_INC_DIR . 'template-functions.php';
        require_once AQUALUXE_INC_DIR . 'template-tags.php';
        require_once AQUALUXE_INC_DIR . 'customizer.php';
        
        // Load WooCommerce compatibility file if WooCommerce is active
        if ($this->is_woocommerce_active()) {
            require_once AQUALUXE_INC_DIR . 'woocommerce.php';
        }
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // After setup theme
        add_action('after_setup_theme', [$this, 'setup_theme']);
        
        // Register widget area
        add_action('widgets_init', [$this, 'widgets_init']);
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        
        // Admin scripts and styles
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
    }

    /**
     * Setup theme
     */
    public function setup_theme() {
        // Make theme available for translation
        load_theme_textdomain('aqualuxe', AQUALUXE_DIR . 'languages');

        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');

        // Register menus
        register_nav_menus([
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'social' => esc_html__('Social Links Menu', 'aqualuxe'),
        ]);

        // Switch default core markup to output valid HTML5
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]);

        // Set up the WordPress core custom background feature
        add_theme_support('custom-background', [
            'default-color' => 'ffffff',
            'default-image' => '',
        ]);

        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');

        // Add support for custom logo
        add_theme_support('custom-logo', [
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        ]);

        // Add support for full and wide align images
        add_theme_support('align-wide');

        // Add support for responsive embeds
        add_theme_support('responsive-embeds');

        // Add support for editor styles
        add_theme_support('editor-styles');

        // Add support for block styles
        add_theme_support('wp-block-styles');
    }

    /**
     * Register widget area
     */
    public function widgets_init() {
        register_sidebar([
            'name'          => esc_html__('Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ]);

        register_sidebar([
            'name'          => esc_html__('Footer 1', 'aqualuxe'),
            'id'            => 'footer-1',
            'description'   => esc_html__('Add footer widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ]);

        register_sidebar([
            'name'          => esc_html__('Footer 2', 'aqualuxe'),
            'id'            => 'footer-2',
            'description'   => esc_html__('Add footer widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ]);

        register_sidebar([
            'name'          => esc_html__('Footer 3', 'aqualuxe'),
            'id'            => 'footer-3',
            'description'   => esc_html__('Add footer widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ]);

        register_sidebar([
            'name'          => esc_html__('Footer 4', 'aqualuxe'),
            'id'            => 'footer-4',
            'description'   => esc_html__('Add footer widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ]);
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Get the mix manifest
        $mix_manifest = $this->get_mix_manifest();
        
        // Enqueue main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            isset($mix_manifest['/css/main.css']) ? AQUALUXE_DIST_URI . 'css/' . ltrim($mix_manifest['/css/main.css'], '/') : AQUALUXE_DIST_URI . 'css/main.css',
            [],
            AQUALUXE_VERSION
        );

        // Enqueue main script
        wp_enqueue_script(
            'aqualuxe-script',
            isset($mix_manifest['/js/main.js']) ? AQUALUXE_DIST_URI . 'js/' . ltrim($mix_manifest['/js/main.js'], '/') : AQUALUXE_DIST_URI . 'js/main.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );

        // Add theme settings to JS
        wp_localize_script('aqualuxe-script', 'aqualuxeSettings', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'themeUri' => AQUALUXE_URI,
            'nonce' => wp_create_nonce('aqualuxe-nonce'),
        ]);

        // Add comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function admin_scripts() {
        // Get the mix manifest
        $mix_manifest = $this->get_mix_manifest();
        
        // Enqueue admin stylesheet
        wp_enqueue_style(
            'aqualuxe-admin-style',
            isset($mix_manifest['/css/admin.css']) ? AQUALUXE_DIST_URI . 'css/' . ltrim($mix_manifest['/css/admin.css'], '/') : AQUALUXE_DIST_URI . 'css/admin.css',
            [],
            AQUALUXE_VERSION
        );

        // Enqueue admin script
        wp_enqueue_script(
            'aqualuxe-admin-script',
            isset($mix_manifest['/js/admin.js']) ? AQUALUXE_DIST_URI . 'js/' . ltrim($mix_manifest['/js/admin.js'], '/') : AQUALUXE_DIST_URI . 'js/admin.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Get mix manifest
     *
     * @return array
     */
    private function get_mix_manifest() {
        $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
        
        if (file_exists($manifest_path)) {
            return json_decode(file_get_contents($manifest_path), true);
        }
        
        return [];
    }

    /**
     * Check if WooCommerce is active
     *
     * @return bool
     */
    public function is_woocommerce_active() {
        return class_exists('WooCommerce');
    }

    /**
     * Load modules
     */
    private function load_modules() {
        // Get active modules from options
        $active_modules = get_option('aqualuxe_active_modules', [
            'multilingual' => true,
            'dark-mode' => true,
        ]);
        
        // Load module base class
        require_once AQUALUXE_CORE_DIR . 'classes/Module.php';
        
        // Loop through active modules and load them
        foreach ($active_modules as $module => $active) {
            if (!$active) {
                continue;
            }
            
            $module_file = AQUALUXE_MODULES_DIR . $module . '/module.php';
            
            if (file_exists($module_file)) {
                require_once $module_file;
                
                // Initialize module
                $class_name = '\\AquaLuxe\\Modules\\' . $this->module_to_class_name($module) . '\\Module';
                
                if (class_exists($class_name)) {
                    $this->active_modules[$module] = new $class_name();
                }
            }
        }
    }

    /**
     * Convert module name to class name
     *
     * @param string $module
     * @return string
     */
    private function module_to_class_name($module) {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $module)));
    }

    /**
     * Get active modules
     *
     * @return array
     */
    public function get_active_modules() {
        return $this->active_modules;
    }

    /**
     * Check if a module is active
     *
     * @param string $module
     * @return bool
     */
    public function is_module_active($module) {
        return isset($this->active_modules[$module]);
    }
}