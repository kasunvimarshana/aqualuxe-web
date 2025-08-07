<?php
/**
 * AquaLuxe Child Theme Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_template_directory());
define('AQUALUXE_CHILD_THEME_DIR', get_stylesheet_directory());
define('AQUALUXE_THEME_URI', get_template_directory_uri());
define('AQUALUXE_CHILD_THEME_URI', get_stylesheet_directory_uri());

// Include required files
require_once AQUALUXE_CHILD_THEME_DIR . '/inc/theme-setup.php';
require_once AQUALUXE_CHILD_THEME_DIR . '/inc/enqueue-scripts.php';
require_once AQUALUXE_CHILD_THEME_DIR . '/inc/customizer.php';
require_once AQUALUXE_CHILD_THEME_DIR . '/inc/woocommerce.php';
require_once AQUALUXE_CHILD_THEME_DIR . '/inc/seo-optimizations.php';
require_once AQUALUXE_CHILD_THEME_DIR . '/inc/accessibility.php';
require_once AQUALUXE_CHILD_THEME_DIR . '/inc/demo-content.php';
require_once AQUALUXE_CHILD_THEME_DIR . '/inc/widgets.php';

/**
 * AquaLuxe Theme Class
 * 
 * Main class for initializing theme functionality
 */
class AquaLuxe_Theme {
    
    /**
     * Instance of the class
     * 
     * @var AquaLuxe_Theme
     */
    private static $instance;
    
    /**
     * Get instance of the class
     * 
     * @return AquaLuxe_Theme
     */
    public static function get_instance() {
        if (!isset(self::$instance)) {
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
        add_action('after_setup_theme', array($this, 'theme_setup'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('customize_register', array($this, 'customize_register'));
        add_action('init', array($this, 'register_widgets'));
    }
    
    /**
     * Theme setup
     */
    public function theme_setup() {
        // Load text domain
        load_child_theme_textdomain('aqualuxe', AQUALUXE_CHILD_THEME_DIR . '/languages');
        
        // Add theme support
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
        add_theme_support('customize-selective-refresh-widgets');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        aqualuxe_enqueue_scripts();
    }
    
    /**
     * Register customizer settings
     */
    public function customize_register($wp_customize) {
        aqualuxe_customize_register($wp_customize);
    }
    
    /**
     * Register custom widgets
     */
    public function register_widgets() {
        // Widgets are registered in the widgets file
    }
}

/**
 * Initialize the theme
 */
function aqualuxe_theme() {
    return AquaLuxe_Theme::get_instance();
}

// Start the theme
aqualuxe_theme();