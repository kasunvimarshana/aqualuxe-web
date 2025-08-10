<?php
/**
 * AquaLuxe functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package AquaLuxe
 */

// Define theme version
define('AQUALUXE_VERSION', '1.0.0');

// Define theme directory path and URI
define('AQUALUXE_DIR', get_template_directory());
define('AQUALUXE_URI', get_template_directory_uri());

/**
 * AquaLuxe Theme Class
 * 
 * Main theme class that initializes the theme functionality
 */
final class AquaLuxe {
    /**
     * Instance of the class
     *
     * @var AquaLuxe
     */
    private static $instance = null;

    /**
     * Get instance of the class
     *
     * @return AquaLuxe
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
        // Load required files
        $this->load_files();

        // Setup theme
        add_action('after_setup_theme', array($this, 'setup'));

        // Register widget areas
        add_action('widgets_init', array($this, 'widgets_init'));

        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Add admin scripts
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));

        // Add editor styles
        add_action('admin_init', array($this, 'add_editor_styles'));

        // Register customizer settings
        add_action('customize_register', array($this, 'customize_register'));

        // Add theme supports
        $this->add_theme_supports();

        // Initialize WooCommerce support
        $this->init_woocommerce();
    }

    /**
     * Load required files
     */
    private function load_files() {
        // Load autoloader
        require_once AQUALUXE_DIR . '/inc/classes/class-aqualuxe-autoloader.php';

        // Load helper functions
        require_once AQUALUXE_DIR . '/inc/helpers.php';

        // Load template functions
        require_once AQUALUXE_DIR . '/inc/template-functions.php';

        // Load template tags
        require_once AQUALUXE_DIR . '/inc/template-tags.php';

        // Load customizer
        require_once AQUALUXE_DIR . '/inc/customizer/customizer.php';

        // Load WooCommerce functions
        if (class_exists('WooCommerce')) {
            require_once AQUALUXE_DIR . '/inc/woocommerce.php';
        }

        // Load multilingual support
        require_once AQUALUXE_DIR . '/inc/multilingual.php';

        // Load dark mode
        require_once AQUALUXE_DIR . '/inc/dark-mode.php';

        // Load demo importer
        require_once AQUALUXE_DIR . '/inc/demo-importer.php';
        
        // Load admin page
        require_once AQUALUXE_DIR . '/inc/admin/admin-page.php';
        
        // Load schema.org markup
        require_once AQUALUXE_DIR . '/inc/schema.php';
        
        // Load Open Graph metadata
        require_once AQUALUXE_DIR . '/inc/open-graph.php';
        
        // Load lazy loading
        require_once AQUALUXE_DIR . '/inc/lazy-loading.php';
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
        add_image_size('aqualuxe-product-thumbnail', 300, 300, true);
        add_image_size('aqualuxe-blog', 800, 450, true);

        // Register nav menus
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
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
        add_theme_support('custom-background', apply_filters('aqualuxe_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

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

        // Add support for custom line height controls
        add_theme_support('custom-line-height');

        // Add support for experimental link color control
        add_theme_support('experimental-link-color');

        // Add support for custom spacing
        add_theme_support('custom-spacing');

        // Add support for custom units
        add_theme_support('custom-units');

        // Add support for editor styles
        add_theme_support('editor-styles');

        // Enqueue editor styles
        add_editor_style('assets/css/editor-style.css');
    }

    /**
     * Add theme supports
     */
    private function add_theme_supports() {
        // Add excerpt support for pages
        add_post_type_support('page', 'excerpt');
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
        $footer_widget_areas = get_theme_mod('aqualuxe_footer_columns', 4);

        for ($i = 1; $i <= $footer_widget_areas; $i++) {
            register_sidebar(array(
                'name' => sprintf(esc_html__('Footer %d', 'aqualuxe'), $i),
                'id' => 'footer-' . $i,
                'description' => esc_html__('Add widgets here to appear in the footer.', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            ));
        }

        // Register shop sidebar
        register_sidebar(array(
            'name' => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id' => 'shop-sidebar',
            'description' => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));
        
        // Register shop filters sidebar
        register_sidebar(array(
            'name' => esc_html__('Shop Filters', 'aqualuxe'),
            'id' => 'shop-filters',
            'description' => esc_html__('Add widgets here to appear in the shop filters area.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="filter-widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h4 class="filter-widget-title">',
            'after_title' => '</h4>',
        ));
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Enqueue Google Fonts
        $body_font = get_theme_mod('aqualuxe_body_font', 'Montserrat');
        $heading_font = get_theme_mod('aqualuxe_heading_font', 'Playfair Display');

        $google_fonts_url = 'https://fonts.googleapis.com/css2?family=' . 
                            str_replace(' ', '+', $body_font) . ':wght@400;500;600;700&family=' . 
                            str_replace(' ', '+', $heading_font) . ':wght@400;500;600;700&display=swap';

        wp_enqueue_style('aqualuxe-google-fonts', $google_fonts_url, array(), null);

        // Enqueue main stylesheet
        wp_enqueue_style('aqualuxe-style', AQUALUXE_URI . '/assets/css/main.css', array(), AQUALUXE_VERSION);

        // Enqueue theme script
        wp_enqueue_script('aqualuxe-script', AQUALUXE_URI . '/assets/js/main.js', array('jquery'), AQUALUXE_VERSION, true);

        // Localize script
        wp_localize_script('aqualuxe-script', 'aqualuxe_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe-nonce'),
            'is_rtl' => is_rtl(),
            'is_user_logged_in' => is_user_logged_in(),
        ));

        // Enqueue WooCommerce script if WooCommerce is active
        if (class_exists('WooCommerce')) {
            wp_enqueue_script('aqualuxe-woocommerce', AQUALUXE_URI . '/assets/js/woocommerce.js', array('jquery'), AQUALUXE_VERSION, true);
        }

        // Enqueue comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        // Add inline CSS for customizer options
        $this->add_inline_styles();
    }

    /**
     * Add inline styles for customizer options
     */
    private function add_inline_styles() {
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#0ea5e9');
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#8b5cf6');
        $accent_color = get_theme_mod('aqualuxe_accent_color', '#eab308');
        $text_color = get_theme_mod('aqualuxe_text_color', '#1f2937');
        $background_color = get_theme_mod('aqualuxe_background_color', '#ffffff');
        $container_width = get_theme_mod('aqualuxe_container_width', '1280');
        $button_radius = get_theme_mod('aqualuxe_button_radius', '8');
        $card_radius = get_theme_mod('aqualuxe_card_radius', '12');

        $custom_css = "
            :root {
                --color-primary: {$primary_color};
                --color-secondary: {$secondary_color};
                --color-accent: {$accent_color};
                --color-text: {$text_color};
                --color-background: {$background_color};
                --container-width: {$container_width}px;
                --button-radius: {$button_radius}px;
                --card-radius: {$card_radius}px;
                --font-body: '{$body_font}', sans-serif;
                --font-heading: '{$heading_font}', serif;
            }
        ";

        wp_add_inline_style('aqualuxe-style', $custom_css);
    }

    /**
     * Enqueue admin scripts
     */
    public function admin_scripts($hook) {
        // Load admin styles and scripts only on theme pages
        if (strpos($hook, 'aqualuxe') !== false || $hook === 'appearance_page_aqualuxe-theme') {
            wp_enqueue_style('aqualuxe-admin-style', AQUALUXE_URI . '/assets/css/admin.css', array(), AQUALUXE_VERSION);
            wp_enqueue_script('aqualuxe-admin-script', AQUALUXE_URI . '/assets/js/admin.js', array('jquery'), AQUALUXE_VERSION, true);
            
            // Localize admin script
            wp_localize_script('aqualuxe-admin-script', 'aqualuxeAdmin', array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe-admin-nonce'),
                'installing' => esc_html__('Installing...', 'aqualuxe'),
                'activate' => esc_html__('Activate', 'aqualuxe'),
                'activating' => esc_html__('Activating...', 'aqualuxe'),
                'activated' => esc_html__('Activated', 'aqualuxe'),
                'install' => esc_html__('Install', 'aqualuxe'),
                'installed' => esc_html__('Installed', 'aqualuxe'),
                'confirmImport' => esc_html__('Are you sure you want to import this demo? This will overwrite your current settings.', 'aqualuxe'),
                'importing' => esc_html__('Importing...', 'aqualuxe'),
                'importingContent' => esc_html__('content...', 'aqualuxe'),
                'importingWidgets' => esc_html__('widgets...', 'aqualuxe'),
                'importingCustomizer' => esc_html__('customizer settings...', 'aqualuxe'),
                'settingUpPages' => esc_html__('setting up pages...', 'aqualuxe'),
                'enterPurchaseCode' => esc_html__('Please enter your purchase code.', 'aqualuxe'),
                'activationError' => esc_html__('An error occurred during activation. Please try again.', 'aqualuxe'),
            ));
        }
    }

    /**
     * Add editor styles
     */
    public function add_editor_styles() {
        add_editor_style('assets/css/editor-style.css');
    }

    /**
     * Register customizer settings
     */
    public function customize_register($wp_customize) {
        // Load customizer classes
        $customizer = new AquaLuxe_Customizer($wp_customize);
        $customizer->register();
    }

    /**
     * Initialize WooCommerce support
     */
    private function init_woocommerce() {
        if (!class_exists('WooCommerce')) {
            return;
        }

        // Add theme support for WooCommerce
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        // Remove default WooCommerce styles
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
    }
}

// Initialize the theme
AquaLuxe::get_instance();

/**
 * Create the Autoloader class
 */
if (!class_exists('AquaLuxe_Autoloader')) {
    /**
     * Autoloader class
     */
    class AquaLuxe_Autoloader {
        /**
         * Register autoloader
         */
        public static function register() {
            spl_autoload_register(array(self::class, 'autoload'));
        }

        /**
         * Autoload function
         *
         * @param string $class Class name
         */
        public static function autoload($class) {
            // Check if class has AquaLuxe_ prefix
            if (strpos($class, 'AquaLuxe_') !== 0) {
                return;
            }

            // Convert class name to file path
            $class_name = str_replace('AquaLuxe_', '', $class);
            $class_name = str_replace('_', '-', $class_name);
            $class_name = strtolower($class_name);

            // Build file path
            $file_path = AQUALUXE_DIR . '/inc/classes/class-' . $class_name . '.php';

            // Load file if it exists
            if (file_exists($file_path)) {
                require_once $file_path;
            }
        }
    }

    // Register autoloader
    AquaLuxe_Autoloader::register();
}