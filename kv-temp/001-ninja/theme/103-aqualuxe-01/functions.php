<?php
/**
 * AquaLuxe Theme Functions
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
 * Define theme constants
 */
define('AQUALUXE_VERSION', '1.0.0');
define('AQUALUXE_THEME_DIR', get_template_directory());
define('AQUALUXE_THEME_URI', get_template_directory_uri());
define('AQUALUXE_THEME_PATH', AQUALUXE_THEME_DIR . '/');
define('AQUALUXE_ASSETS_URI', AQUALUXE_THEME_URI . '/assets/dist/');
define('AQUALUXE_INCLUDES_PATH', AQUALUXE_THEME_PATH . 'inc/');
define('AQUALUXE_MODULES_PATH', AQUALUXE_THEME_PATH . 'modules/');
define('AQUALUXE_CORE_PATH', AQUALUXE_THEME_PATH . 'core/');

/**
 * Include core files
 */
require_once AQUALUXE_CORE_PATH . 'class-aqualuxe-theme.php';

/**
 * Include utilities and helpers
 */
require_once AQUALUXE_INCLUDES_PATH . 'class-aqualuxe-assets.php';
require_once AQUALUXE_INCLUDES_PATH . 'class-aqualuxe-security.php';
require_once AQUALUXE_INCLUDES_PATH . 'class-aqualuxe-performance.php';
require_once AQUALUXE_INCLUDES_PATH . 'class-aqualuxe-seo.php';

/**
 * Include admin functionality
 */
if (is_admin()) {
    require_once AQUALUXE_INCLUDES_PATH . 'admin/class-aqualuxe-admin.php';
    require_once AQUALUXE_INCLUDES_PATH . 'customizer/class-aqualuxe-customizer.php';
}

/**
 * Include custom post types and taxonomies
 */
require_once AQUALUXE_INCLUDES_PATH . 'post-types/class-aqualuxe-post-types.php';
require_once AQUALUXE_INCLUDES_PATH . 'taxonomies/class-aqualuxe-taxonomies.php';
require_once AQUALUXE_INCLUDES_PATH . 'meta-fields/class-aqualuxe-meta-fields.php';

/**
 * Include modules
 */
require_once AQUALUXE_MODULES_PATH . 'dark-mode/class-aqualuxe-dark-mode.php';
require_once AQUALUXE_MODULES_PATH . 'multilingual/class-aqualuxe-multilingual.php';
require_once AQUALUXE_MODULES_PATH . 'demo-importer/class-aqualuxe-demo-importer.php';

/**
 * Include WooCommerce integration if active
 */
if (class_exists('WooCommerce')) {
    require_once AQUALUXE_MODULES_PATH . 'woocommerce/class-aqualuxe-woocommerce.php';
}

/**
 * Initialize the theme
 */
function aqualuxe_init() {
    // Initialize core theme class
    AquaLuxe_Theme::get_instance();
    
    // Initialize assets management
    AquaLuxe_Assets::get_instance();
    
    // Initialize security features
    AquaLuxe_Security::get_instance();
    
    // Initialize performance optimizations
    AquaLuxe_Performance::get_instance();
    
    // Initialize SEO features
    AquaLuxe_SEO::get_instance();
    
    // Initialize custom post types
    AquaLuxe_Post_Types::get_instance();
    
    // Initialize taxonomies
    AquaLuxe_Taxonomies::get_instance();
    
    // Initialize meta fields
    AquaLuxe_Meta_Fields::get_instance();
    
    // Initialize modules
    AquaLuxe_Dark_Mode::get_instance();
    AquaLuxe_Multilingual::get_instance();
    AquaLuxe_Demo_Importer::get_instance();
    
    // Initialize admin if in admin area
    if (is_admin()) {
        AquaLuxe_Admin::get_instance();
        AquaLuxe_Customizer::get_instance();
    }
    
    // Initialize WooCommerce integration if active
    if (class_exists('WooCommerce')) {
        AquaLuxe_WooCommerce::get_instance();
    }
}
add_action('after_setup_theme', 'aqualuxe_init', 10);

/**
 * Theme setup function
 */
function aqualuxe_setup() {
    // Make theme available for translation
    load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . '/languages');
    
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');
    
    // Let WordPress manage the document title
    add_theme_support('title-tag');
    
    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');
    
    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');
    
    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');
    
    // Add support for core custom logo
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    
    // Add support for custom header
    add_theme_support('custom-header', array(
        'default-image'      => '',
        'width'              => 1920,
        'height'             => 1080,
        'flex-height'        => true,
        'flex-width'         => true,
        'uploads'            => true,
        'random-default'     => false,
        'header-text'        => false,
    ));
    
    // Add support for custom background
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ));
    
    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // Add support for post formats
    add_theme_support('post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'status',
        'audio',
        'chat',
    ));
    
    // Add support for editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/dist/css/editor.css');
    
    // Add support for full and wide align images
    add_theme_support('align-wide');
    
    // Add support for block styles
    add_theme_support('wp-block-styles');
    
    // Add support for Gutenberg editor color palette
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => esc_html__('Aqua Primary', 'aqualuxe'),
            'slug'  => 'aqua-primary',
            'color' => '#06b6d4',
        ),
        array(
            'name'  => esc_html__('Luxury Gold', 'aqualuxe'),
            'slug'  => 'luxury-gold',
            'color' => '#ffd700',
        ),
        array(
            'name'  => esc_html__('Ocean Deep', 'aqualuxe'),
            'slug'  => 'ocean-deep',
            'color' => '#1e3a8a',
        ),
        array(
            'name'  => esc_html__('Coral Accent', 'aqualuxe'),
            'slug'  => 'coral-accent',
            'color' => '#f43f5e',
        ),
    ));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary'   => esc_html__('Primary Menu', 'aqualuxe'),
        'secondary' => esc_html__('Secondary Menu', 'aqualuxe'),
        'footer'    => esc_html__('Footer Menu', 'aqualuxe'),
        'mobile'    => esc_html__('Mobile Menu', 'aqualuxe'),
    ));
    
    // Add custom image sizes
    add_image_size('aqualuxe-hero', 1920, 1080, true);
    add_image_size('aqualuxe-featured', 800, 600, true);
    add_image_size('aqualuxe-thumbnail', 400, 300, true);
    add_image_size('aqualuxe-gallery', 600, 400, true);
    add_image_size('aqualuxe-testimonial', 100, 100, true);
}
add_action('after_setup_theme', 'aqualuxe_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet
 */
function aqualuxe_content_width() {
    $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
}
add_action('after_setup_theme', 'aqualuxe_content_width', 0);

/**
 * Register widget areas
 */
function aqualuxe_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Primary Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-primary',
        'description'   => esc_html__('Add widgets here to appear in your primary sidebar.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => esc_html__('Footer Area 1', 'aqualuxe'),
        'id'            => 'footer-1',
        'description'   => esc_html__('Add widgets here to appear in your footer.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => esc_html__('Footer Area 2', 'aqualuxe'),
        'id'            => 'footer-2',
        'description'   => esc_html__('Add widgets here to appear in your footer.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => esc_html__('Footer Area 3', 'aqualuxe'),
        'id'            => 'footer-3',
        'description'   => esc_html__('Add widgets here to appear in your footer.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => esc_html__('Footer Area 4', 'aqualuxe'),
        'id'            => 'footer-4',
        'description'   => esc_html__('Add widgets here to appear in your footer.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'aqualuxe_widgets_init');

/**
 * Helper function to get theme option
 *
 * @param string $option_name Option name
 * @param mixed  $default     Default value
 * @return mixed Option value
 */
function aqualuxe_get_option($option_name, $default = null) {
    return get_theme_mod($option_name, $default);
}

/**
 * Helper function to check if dark mode is enabled
 *
 * @return bool
 */
function aqualuxe_is_dark_mode() {
    $dark_mode = get_theme_mod('aqualuxe_dark_mode', false);
    return apply_filters('aqualuxe_is_dark_mode', $dark_mode);
}

/**
 * Helper function to get current language
 *
 * @return string
 */
function aqualuxe_get_current_language() {
    // Check for WPML
    if (function_exists('icl_get_current_language')) {
        return icl_get_current_language();
    }
    
    // Check for Polylang
    if (function_exists('pll_current_language')) {
        return pll_current_language();
    }
    
    // Default to WordPress locale
    return get_locale();
}

/**
 * Helper function to check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Custom excerpt length
 */
function aqualuxe_excerpt_length($length) {
    if (is_admin()) {
        return $length;
    }
    
    return apply_filters('aqualuxe_excerpt_length', 25);
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length', 999);

/**
 * Custom excerpt more
 */
function aqualuxe_excerpt_more($more) {
    if (is_admin()) {
        return $more;
    }
    
    return apply_filters('aqualuxe_excerpt_more', '...');
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Body classes
 */
function aqualuxe_body_classes($classes) {
    // Add dark mode class
    if (aqualuxe_is_dark_mode()) {
        $classes[] = 'dark';
    }
    
    // Add WooCommerce class
    if (aqualuxe_is_woocommerce_active()) {
        $classes[] = 'woocommerce-active';
    }
    
    // Add page template class
    if (is_page_template()) {
        $template = get_page_template_slug();
        $classes[] = 'page-template-' . str_replace('.php', '', str_replace('/', '-', $template));
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add async/defer attributes to scripts
 */
function aqualuxe_script_loader_tag($tag, $handle, $src) {
    // Scripts to load with async
    $async_scripts = array(
        'aqualuxe-main',
        'aqualuxe-dark-mode',
    );
    
    // Scripts to load with defer
    $defer_scripts = array(
        'aqualuxe-modules',
    );
    
    if (in_array($handle, $async_scripts)) {
        return str_replace(' src', ' async src', $tag);
    }
    
    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_script_loader_tag', 10, 3);

/**
 * Remove unnecessary WordPress features for better security and performance
 */
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * Custom login logo
 */
function aqualuxe_login_logo() {
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
        ?>
        <style type="text/css">
            #login h1 a, .login h1 a {
                background-image: url(<?php echo esc_url($logo[0]); ?>);
                background-size: contain;
                background-repeat: no-repeat;
                background-position: center;
                height: 100px;
                width: 320px;
            }
        </style>
        <?php
    }
}
add_action('login_enqueue_scripts', 'aqualuxe_login_logo');

/**
 * Custom login logo URL
 */
function aqualuxe_login_logo_url() {
    return home_url();
}
add_filter('login_headerurl', 'aqualuxe_login_logo_url');

/**
 * Custom login logo URL title
 */
function aqualuxe_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter('login_headertitle', 'aqualuxe_login_logo_url_title');

/**
 * Disable file editing in admin
 */
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

/**
 * Security headers
 */
function aqualuxe_security_headers() {
    if (!is_admin()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }
}
add_action('send_headers', 'aqualuxe_security_headers');

/**
 * Enable theme updates (if premium)
 */
// Uncomment and configure if this becomes a premium theme
// require_once AQUALUXE_INCLUDES_PATH . 'class-aqualuxe-updater.php';