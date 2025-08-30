<?php
/**
 * Main AquaLuxe Theme Class
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('AquaLuxe')) :

    /**
     * Main AquaLuxe Class
     */
    final class AquaLuxe
    {
        /**
         * AquaLuxe version.
         *
         * @var string
         */
        public $version = '1.0.0';

        /**
         * The single instance of the class.
         *
         * @var AquaLuxe
         */
        protected static $_instance = null;

        /**
         * Main AquaLuxe Instance.
         *
         * Ensures only one instance of AquaLuxe is loaded or can be loaded.
         *
         * @return AquaLuxe - Main instance.
         */
        public static function instance()
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Cloning is forbidden.
         */
        public function __clone()
        {
            _doing_it_wrong(__FUNCTION__, __('Cloning is forbidden.', 'aqualuxe'), $this->version);
        }

        /**
         * Unserializing instances of this class is forbidden.
         */
        public function __wakeup()
        {
            _doing_it_wrong(__FUNCTION__, __('Unserializing instances of this class is forbidden.', 'aqualuxe'), $this->version);
        }

        /**
         * Auto-load in-accessible properties on demand.
         *
         * @param mixed $key Key name.
         * @return mixed
         */
        public function __get($key)
        {
            if (in_array($key, array('demo_importer'), true)) {
                return $this->$key;
            }
        }

        /**
         * AquaLuxe Constructor.
         */
        public function __construct()
        {
            $this->define_constants();
            $this->includes();
            $this->init_hooks();
        }

        /**
         * Define WC Constants.
         */
        private function define_constants()
        {
            $this->define('AQUALUXE_VERSION', $this->version);
            $this->define('AQUALUXE_TEMPLATE_PATH', get_stylesheet_directory() . '/templates/');
        }

        /**
         * Define constant if not already set.
         *
         * @param string      $name  Constant name.
         * @param string|bool $value Constant value.
         */
        private function define($name, $value)
        {
            if (!defined($name)) {
                define($name, $value);
            }
        }

        /**
         * Include required core files used in admin and on the frontend.
         */
        public function includes()
        {
            // Core functions
            require_once get_stylesheet_directory() . '/inc/template-hooks.php';
            require_once get_stylesheet_directory() . '/inc/template-functions.php';
            require_once get_stylesheet_directory() . '/inc/customizer.php';
            
            // Performance and accessibility
            require_once get_stylesheet_directory() . '/inc/accessibility.php';
            require_once get_stylesheet_directory() . '/inc/performance.php';
            
            // Demo content importer
            if (is_admin()) {
                require_once get_stylesheet_directory() . '/inc/class-aqualuxe-demo-importer.php';
                $this->demo_importer = new AquaLuxe_Demo_Importer();
            }
        }

        /**
         * Hook into actions and filters.
         */
        private function init_hooks()
        {
            add_action('init', array($this, 'init'), 0);
            add_action('widgets_init', array($this, 'widgets_init'));
        }

        /**
         * Init AquaLuxe when WordPress Initialises.
         */
        public function init()
        {
            // Set up localisation
            $this->load_plugin_textdomain();
        }

        /**
         * Load Localisation files.
         */
        public function load_plugin_textdomain()
        {
            load_theme_textdomain('aqualuxe', get_stylesheet_directory() . '/languages');
        }

        /**
         * Register widget areas.
         */
        public function widgets_init()
        {
            register_sidebar(array(
                'name'          => __('Main Sidebar', 'aqualuxe'),
                'id'            => 'sidebar-1',
                'description'   => __('Add widgets here to appear in your sidebar.', 'aqualuxe'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ));
        }
    }

endif;

/**
 * Returns the main instance of AquaLuxe.
 *
 * @return AquaLuxe
 */
function aqualuxe()
{
    return AquaLuxe::instance();
}

// Global for backwards compatibility.
$GLOBALS['aqualuxe'] = aqualuxe();