<?php
/**
 * AquaLuxe Theme Functions
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @author Kasun Vimarshana
 * @link https://github.com/kasunvimarshana
 * @license GPL-2.0+
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
define('AQUALUXE_THEME_ASSETS', AQUALUXE_THEME_URI . '/assets');

/**
 * Theme Setup Class
 */
class AquaLuxe_Theme_Setup
{
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'setup']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        add_action('widgets_init', [$this, 'register_sidebars']);
        add_action('init', [$this, 'register_nav_menus']);
        add_action('customize_register', [$this, 'customize_register']);
        add_filter('body_class', [$this, 'body_classes']);
        add_filter('wp_title', [$this, 'wp_title'], 10, 2);
        
        // WooCommerce specific hooks
        add_action('after_setup_theme', [$this, 'woocommerce_setup']);
        add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        
        // Security enhancements
        $this->init_security();
        
        // Performance optimizations
        $this->init_performance();
    }

    /**
     * Theme setup
     */
    public function setup()
    {
        // Make theme available for translation
        load_theme_textdomain('aqualuxe', AQUALUXE_THEME_DIR . '/languages');

        // Add default posts and comments RSS feed links
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails
        add_theme_support('post-thumbnails');
        
        // Add custom image sizes
        add_image_size('aqualuxe-hero', 1920, 800, true);
        add_image_size('aqualuxe-product-large', 800, 600, true);
        add_image_size('aqualuxe-product-medium', 400, 300, true);
        add_image_size('aqualuxe-product-small', 200, 150, true);
        add_image_size('aqualuxe-gallery', 600, 400, true);

        // Switch default core markup to HTML5
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        ]);

        // Set up the WordPress core custom background feature
        add_theme_support('custom-background', [
            'default-color' => 'ffffff',
            'default-image' => '',
        ]);

        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');

        // Add support for core custom logo
        add_theme_support('custom-logo', [
            'height'      => 60,
            'width'       => 200,
            'flex-width'  => true,
            'flex-height' => true,
        ]);

        // Add support for editor styles
        add_theme_support('editor-styles');
        add_editor_style('assets/css/editor-style.css');

        // Add support for responsive embedded content
        add_theme_support('responsive-embeds');

        // Add support for custom header
        add_theme_support('custom-header', [
            'default-image'      => AQUALUXE_THEME_ASSETS . '/images/header-default.jpg',
            'width'              => 1920,
            'height'             => 800,
            'flex-height'        => true,
            'video'              => true,
            'wp-head-callback'   => [$this, 'header_style'],
        ]);

        // Content width
        if (!isset($content_width)) {
            $content_width = 1200;
        }
    }

    /**
     * WooCommerce setup
     */
    public function woocommerce_setup()
    {
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 300,
            'single_image_width'    => 600,
            'product_grid'          => [
                'default_rows'    => 3,
                'min_rows'        => 1,
                'default_columns' => 4,
                'min_columns'     => 1,
                'max_columns'     => 6,
            ],
        ]);

        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts()
    {
        // jQuery (already included in WordPress)
        wp_enqueue_script('jquery');

        // Main theme script
        wp_enqueue_script(
            'aqualuxe-main',
            AQUALUXE_THEME_ASSETS . '/js/main.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );

        // WooCommerce scripts
        if (class_exists('WooCommerce')) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_THEME_ASSETS . '/js/woocommerce.js',
                ['jquery', 'wc-add-to-cart-variation'],
                AQUALUXE_VERSION,
                true
            );
        }

        // Navigation script for mobile
        wp_enqueue_script(
            'aqualuxe-navigation',
            AQUALUXE_THEME_ASSETS . '/js/navigation.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );

        // Customizer preview script
        if (is_customize_preview()) {
            wp_enqueue_script(
                'aqualuxe-customizer',
                AQUALUXE_THEME_ASSETS . '/js/customizer.js',
                ['jquery', 'customize-preview'],
                AQUALUXE_VERSION,
                true
            );
        }

        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        // Localize script for AJAX
        wp_localize_script('aqualuxe-main', 'aqualuxe_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('aqualuxe_nonce'),
            'strings'  => [
                'loading'       => __('Loading...', 'aqualuxe'),
                'error'         => __('An error occurred. Please try again.', 'aqualuxe'),
                'added_to_cart' => __('Product added to cart!', 'aqualuxe'),
                'view_cart'     => __('View Cart', 'aqualuxe'),
            ],
        ]);
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles()
    {
        // Main theme stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            get_stylesheet_uri(),
            [],
            AQUALUXE_VERSION
        );

        // Main CSS file
        wp_enqueue_style(
            'aqualuxe-main',
            AQUALUXE_THEME_ASSETS . '/css/main.css',
            ['aqualuxe-style'],
            AQUALUXE_VERSION
        );

        // Responsive CSS
        wp_enqueue_style(
            'aqualuxe-responsive',
            AQUALUXE_THEME_ASSETS . '/css/responsive.css',
            ['aqualuxe-main'],
            AQUALUXE_VERSION
        );

        // WooCommerce CSS
        if (class_exists('WooCommerce')) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                AQUALUXE_THEME_ASSETS . '/css/woocommerce.css',
                ['aqualuxe-main'],
                AQUALUXE_VERSION
            );
        }

        // Google Fonts
        wp_enqueue_style(
            'aqualuxe-fonts',
            $this->get_google_fonts_url(),
            [],
            AQUALUXE_VERSION
        );

        // Print styles
        wp_enqueue_style(
            'aqualuxe-print',
            AQUALUXE_THEME_ASSETS . '/css/print.css',
            ['aqualuxe-main'],
            AQUALUXE_VERSION,
            'print'
        );
    }

    /**
     * Admin enqueue scripts
     */
    public function admin_enqueue_scripts($hook)
    {
        // Admin styles
        wp_enqueue_style(
            'aqualuxe-admin',
            AQUALUXE_THEME_ASSETS . '/css/admin.css',
            [],
            AQUALUXE_VERSION
        );

        // Admin scripts
        wp_enqueue_script(
            'aqualuxe-admin',
            AQUALUXE_THEME_ASSETS . '/js/admin.js',
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );

        // Customizer scripts
        if ('customize.php' === $hook) {
            wp_enqueue_script(
                'aqualuxe-customizer-controls',
                AQUALUXE_THEME_ASSETS . '/js/customizer-controls.js',
                ['jquery', 'customize-controls'],
                AQUALUXE_VERSION,
                true
            );
        }
    }

    /**
     * Get Google Fonts URL
     */
    private function get_google_fonts_url()
    {
        $fonts_url = '';
        $fonts     = [];
        $subsets   = 'latin,latin-ext';

        // Inter font
        if ('off' !== _x('on', 'Inter font: on or off', 'aqualuxe')) {
            $fonts[] = 'Inter:300,400,500,600,700';
        }

        // Playfair Display font
        if ('off' !== _x('on', 'Playfair Display font: on or off', 'aqualuxe')) {
            $fonts[] = 'Playfair+Display:400,700,900';
        }

        if ($fonts) {
            $fonts_url = add_query_arg([
                'family' => implode('|', $fonts),
                'subset' => urlencode($subsets),
                'display' => 'swap',
            ], 'https://fonts.googleapis.com/css');
        }

        return esc_url_raw($fonts_url);
    }

    /**
     * Register navigation menus
     */
    public function register_nav_menus()
    {
        register_nav_menus([
            'primary'   => esc_html__('Primary Menu', 'aqualuxe'),
            'footer'    => esc_html__('Footer Menu', 'aqualuxe'),
            'mobile'    => esc_html__('Mobile Menu', 'aqualuxe'),
            'social'    => esc_html__('Social Links Menu', 'aqualuxe'),
        ]);
    }

    /**
     * Register sidebars
     */
    public function register_sidebars()
    {
        // Main sidebar
        register_sidebar([
            'name'          => esc_html__('Main Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);

        // Shop sidebar
        if (class_exists('WooCommerce')) {
            register_sidebar([
                'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
                'id'            => 'sidebar-shop',
                'description'   => esc_html__('Add shop widgets here.', 'aqualuxe'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]);
        }

        // Footer widgets
        for ($i = 1; $i <= 4; $i++) {
            register_sidebar([
                'name'          => sprintf(esc_html__('Footer Widget %d', 'aqualuxe'), $i),
                'id'            => 'footer-' . $i,
                'description'   => sprintf(esc_html__('Add footer widgets here (Column %d).', 'aqualuxe'), $i),
                'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]);
        }

        // Homepage widgets
        register_sidebar([
            'name'          => esc_html__('Homepage Hero', 'aqualuxe'),
            'id'            => 'homepage-hero',
            'description'   => esc_html__('Add widgets for homepage hero section.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="hero-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="hero-title">',
            'after_title'   => '</h2>',
        ]);

        register_sidebar([
            'name'          => esc_html__('Homepage Features', 'aqualuxe'),
            'id'            => 'homepage-features',
            'description'   => esc_html__('Add widgets for homepage features section.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="feature-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="feature-title">',
            'after_title'   => '</h3>',
        ]);
    }

    /**
     * Customizer additions
     */
    public function customize_register($wp_customize)
    {
        // Include customizer class
        require_once AQUALUXE_THEME_DIR . '/inc/class-customizer.php';
        new AquaLuxe_Customizer($wp_customize);
    }

    /**
     * Add custom classes to body
     */
    public function body_classes($classes)
    {
        // Adds a class of hfeed to non-singular pages
        if (!is_singular()) {
            $classes[] = 'hfeed';
        }

        // Adds a class of no-sidebar when there is no sidebar present
        if (!is_active_sidebar('sidebar-1')) {
            $classes[] = 'no-sidebar';
        }

        // WooCommerce specific classes
        if (class_exists('WooCommerce')) {
            if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
                $classes[] = 'woocommerce-page';
            }
        }

        // Mobile detection
        if (wp_is_mobile()) {
            $classes[] = 'mobile-device';
        }

        // RTL support
        if (is_rtl()) {
            $classes[] = 'rtl';
        }

        return $classes;
    }

    /**
     * Custom title tag
     */
    public function wp_title($title, $sep)
    {
        if (is_feed()) {
            return $title;
        }

        global $page, $paged;

        // Add the blog name
        $title .= get_bloginfo('name', 'display');

        // Add the blog description for the home/front page
        $site_description = get_bloginfo('description', 'display');
        if ($site_description && (is_home() || is_front_page())) {
            $title .= " $sep $site_description";
        }

        // Add a page number if necessary
        if (($paged >= 2 || $page >= 2) && !is_404()) {
            $title .= " $sep " . sprintf(esc_html__('Page %s', 'aqualuxe'), max($paged, $page));
        }

        return $title;
    }

    /**
     * Custom header style
     */
    public function header_style()
    {
        $header_text_color = get_header_textcolor();
        
        if (!display_header_text()) {
            echo '<style type="text/css">.site-title, .site-description { position: absolute; clip: rect(1px, 1px, 1px, 1px); }</style>';
        } else {
            echo '<style type="text/css">.site-title a, .site-description { color: #' . esc_attr($header_text_color) . '; }</style>';
        }
    }

    /**
     * Security enhancements
     */
    private function init_security()
    {
        // Remove WordPress version from head
        remove_action('wp_head', 'wp_generator');
        
        // Remove RSD link
        remove_action('wp_head', 'rsd_link');
        
        // Remove Windows Live Writer manifest link
        remove_action('wp_head', 'wlwmanifest_link');
        
        // Remove shortlink
        remove_action('wp_head', 'wp_shortlink_wp_head');
        
        // Disable XML-RPC
        add_filter('xmlrpc_enabled', '__return_false');
        
        // Remove XML-RPC pingback
        add_filter('wp_headers', function($headers) {
            if (isset($headers['X-Pingback'])) {
                unset($headers['X-Pingback']);
            }
            return $headers;
        });

        // Security headers
        add_action('send_headers', function() {
            if (!is_admin()) {
                header('X-Content-Type-Options: nosniff');
                header('X-Frame-Options: SAMEORIGIN');
                header('X-XSS-Protection: 1; mode=block');
                header('Referrer-Policy: strict-origin-when-cross-origin');
            }
        });

        // Disable file editing
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
    }

    /**
     * Performance optimizations
     */
    private function init_performance()
    {
        // Remove query strings from static resources
        add_filter('script_loader_src', [$this, 'remove_query_strings'], 15, 1);
        add_filter('style_loader_src', [$this, 'remove_query_strings'], 15, 1);

        // Defer non-critical CSS
        add_filter('style_loader_tag', [$this, 'defer_non_critical_css'], 10, 4);

        // Optimize images
        add_filter('wp_image_editors', [$this, 'wp_image_editors']);
        add_filter('jpeg_quality', [$this, 'jpeg_quality']);

        // Lazy load images
        add_filter('wp_get_attachment_image_attributes', [$this, 'add_lazy_loading'], 10, 3);

        // Remove emoji scripts
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

        // Remove block library CSS if not using Gutenberg
        if (!is_admin()) {
            add_action('wp_enqueue_scripts', function() {
                wp_dequeue_style('wp-block-library');
                wp_dequeue_style('wp-block-library-theme');
            }, 100);
        }
    }

    /**
     * Remove query strings from static resources
     */
    public function remove_query_strings($src)
    {
        $output = preg_split("/(&ver|\?ver)/", $src);
        return $output[0];
    }

    /**
     * Defer non-critical CSS
     */
    public function defer_non_critical_css($tag, $handle, $href, $media)
    {
        // List of non-critical CSS handles
        $defer_handles = ['aqualuxe-print'];

        if (in_array($handle, $defer_handles)) {
            return str_replace('rel=\'stylesheet\'', 'rel=\'preload\' as=\'style\' onload="this.onload=null;this.rel=\'stylesheet\'"', $tag);
        }

        return $tag;
    }

    /**
     * Optimize image editors
     */
    public function wp_image_editors($editors)
    {
        return ['WP_Image_Editor_Imagick', 'WP_Image_Editor_GD'];
    }

    /**
     * Set JPEG quality
     */
    public function jpeg_quality($quality)
    {
        return 85;
    }

    /**
     * Add lazy loading to images
     */
    public function add_lazy_loading($attr, $attachment, $size)
    {
        if (!is_admin() && !is_feed()) {
            $attr['loading'] = 'lazy';
        }
        return $attr;
    }
}

// Initialize theme
new AquaLuxe_Theme_Setup();

/**
 * Include additional files
 */
$includes = [
    '/inc/template-functions.php',
    '/inc/woocommerce-functions.php',
    '/inc/customizer-functions.php',
    '/inc/security.php',
    '/inc/performance.php',
    '/inc/class-enqueue.php',
    '/inc/class-woocommerce.php',
    '/inc/class-walker-nav-menu.php',
    '/inc/class-demo-content.php',
];

foreach ($includes as $file) {
    if (file_exists(AQUALUXE_THEME_DIR . $file)) {
        require_once AQUALUXE_THEME_DIR . $file;
    }
}

/**
 * Load custom widgets
 */
function aqualuxe_load_widgets()
{
    $widgets = [
        'class-featured-products.php',
        'class-testimonials.php',
        'class-newsletter.php',
        'class-social-links.php',
    ];

    foreach ($widgets as $widget) {
        $file = AQUALUXE_THEME_DIR . '/widgets/' . $widget;
        if (file_exists($file)) {
            require_once $file;
        }
    }
}
add_action('widgets_init', 'aqualuxe_load_widgets');

/**
 * Load shortcodes
 */
function aqualuxe_load_shortcodes()
{
    $shortcodes = [
        'class-product-grid.php',
        'class-testimonials.php',
        'class-hero-banner.php',
        'class-contact-form.php',
    ];

    foreach ($shortcodes as $shortcode) {
        $file = AQUALUXE_THEME_DIR . '/shortcodes/' . $shortcode;
        if (file_exists($file)) {
            require_once $file;
        }
    }
}
add_action('init', 'aqualuxe_load_shortcodes');

/**
 * Theme activation hook
 */
function aqualuxe_theme_activation()
{
    // Flush rewrite rules
    flush_rewrite_rules();

    // Set default customizer options
    $defaults = [
        'aqualuxe_primary_color'   => '#3182ce',
        'aqualuxe_secondary_color' => '#667eea',
        'aqualuxe_accent_color'    => '#38b2ac',
        'aqualuxe_enable_lazyload' => true,
        'aqualuxe_enable_minify'   => true,
    ];

    foreach ($defaults as $option => $value) {
        if (!get_option($option)) {
            update_option($option, $value);
        }
    }
}
add_action('after_switch_theme', 'aqualuxe_theme_activation');

/**
 * Theme deactivation hook
 */
function aqualuxe_theme_deactivation()
{
    // Clean up any temporary data
    flush_rewrite_rules();
}
add_action('switch_theme', 'aqualuxe_theme_deactivation');

/**
 * AJAX handlers
 */
function aqualuxe_ajax_load_products()
{
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_nonce')) {
        wp_die('Security check failed');
    }

    $page = intval($_POST['page']);
    $posts_per_page = intval($_POST['posts_per_page']) ?: 12;
    
    $args = [
        'post_type'      => 'product',
        'posts_per_page' => $posts_per_page,
        'paged'          => $page,
        'post_status'    => 'publish',
    ];

    $products = new WP_Query($args);
    
    ob_start();
    
    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
    }
    
    $output = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success([
        'html' => $output,
        'has_more' => $products->max_num_pages > $page,
    ]);
}
add_action('wp_ajax_aqualuxe_load_products', 'aqualuxe_ajax_load_products');
add_action('wp_ajax_nopriv_aqualuxe_load_products', 'aqualuxe_ajax_load_products');

/**
 * Custom post types for testimonials
 */
function aqualuxe_register_post_types()
{
    // Testimonials post type
    register_post_type('testimonial', [
        'labels' => [
            'name'               => _x('Testimonials', 'post type general name', 'aqualuxe'),
            'singular_name'      => _x('Testimonial', 'post type singular name', 'aqualuxe'),
            'menu_name'          => _x('Testimonials', 'admin menu', 'aqualuxe'),
            'add_new'            => _x('Add New', 'testimonial', 'aqualuxe'),
            'add_new_item'       => __('Add New Testimonial', 'aqualuxe'),
            'new_item'           => __('New Testimonial', 'aqualuxe'),
            'edit_item'          => __('Edit Testimonial', 'aqualuxe'),
            'view_item'          => __('View Testimonial', 'aqualuxe'),
            'all_items'          => __('All Testimonials', 'aqualuxe'),
            'search_items'       => __('Search Testimonials', 'aqualuxe'),
            'parent_item_colon'  => __('Parent Testimonials:', 'aqualuxe'),
            'not_found'          => __('No testimonials found.', 'aqualuxe'),
            'not_found_in_trash' => __('No testimonials found in Trash.', 'aqualuxe'),
        ],
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => ['slug' => 'testimonials'],
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'menu_icon'          => 'dashicons-format-quote',
        'supports'           => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest'       => true,
    ]);
}
add_action('init', 'aqualuxe_register_post_types');

/**
 * Demo content installer
 */
function aqualuxe_install_demo_content()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $demo_installer = new AquaLuxe_Demo_Content();
    $demo_installer->install();
}

/**
 * Admin notice for demo content
 */
function aqualuxe_admin_notice_demo_content()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $demo_installed = get_option('aqualuxe_demo_installed');
    
    if (!$demo_installed) {
        ?>

        <div class="notice notice-info is-dismissible">
            <p>
                <?php esc_html_e('Welcome to AquaLuxe! Would you like to install demo content to get started quickly?', 'aqualuxe'); ?>
                <a href="<?php echo esc_url(admin_url('themes.php?page=aqualuxe-demo-import')); ?>" class="button button-primary" style="margin-left: 10px;">
                    <?php esc_html_e('Install Demo Content', 'aqualuxe'); ?>
                </a>
            </p>
        </div>
        <?php
    }

}
add_action('admin_notices', 'aqualuxe_admin_notice_demo_content');

/\*\*

- Custom CSS output
  \*/
  function aqualuxe_custom_css_output()
  {
  $primary_color = get_theme_mod('aqualuxe_primary_color', '#3182ce');
  $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#667eea');
  $accent_color = get_theme_mod('aqualuxe_accent_color', '#38b2ac');

      $custom_css = "
          :root {
              --aqualuxe-primary: {$primary_color};
              --aqualuxe-secondary: {$secondary_color};
              --aqualuxe-accent: {$accent_color};
          }
      ";

      wp_add_inline_style('aqualuxe-style', $custom_css);

  }
  add_action('wp_enqueue_scripts', 'aqualuxe_custom_css_output');

/\*\*

- Breadcrumbs function
  \*/
  if (!function_exists('aqualuxe_breadcrumbs')) {
  function aqualuxe_breadcrumbs()
  {
  if (is_front_page()) {
  return;
  }

          $breadcrumbs = '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb navigation', 'aqualuxe') . '">';
          $breadcrumbs .= '<ol class="breadcrumb-list">';
          $breadcrumbs .= '<li class="breadcrumb-item"><a href="' . esc_url(home_url()) . '">' . esc_html__('Home', 'aqualuxe') . '</a></li>';

          if (is_category() || is_single()) {
              $category = get_the_category();
              if ($category) {
                  foreach ($category as $cat) {
                      $breadcrumbs .= '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($cat->term_id)) . '">' . esc_html($cat->name) . '</a></li>';
                  }
              }
              if (is_single()) {
                  $breadcrumbs .= '<li class="breadcrumb-item active">' . esc_html(get_the_title()) . '</li>';
              }
          } elseif (is_page()) {
              if (wp_get_post_parent_id(get_the_ID())) {
                  $parent_id = wp_get_post_parent_id(get_the_ID());
                  $breadcrumbs .= '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink($parent_id)) . '">' . esc_html(get_the_title($parent_id)) . '</a></li>';
              }
              $breadcrumbs .= '<li class="breadcrumb-item active">' . esc_html(get_the_title()) . '</li>';
          } elseif (is_tag()) {
              $breadcrumbs .= '<li class="breadcrumb-item active">' . esc_html__('Tag: ', 'aqualuxe') . single_tag_title('', false) . '</li>';
          } elseif (is_author()) {
              $breadcrumbs .= '<li class="breadcrumb-item active">' . esc_html__('Author: ', 'aqualuxe') . get_the_author() . '</li>';
          } elseif (is_404()) {
              $breadcrumbs .= '<li class="breadcrumb-item active">' . esc_html__('Page Not Found', 'aqualuxe') . '</li>';
          }

          $breadcrumbs .= '</ol>';
          $breadcrumbs .= '</nav>';

          echo $breadcrumbs;
      }

  }

/\*\*

- Get theme option with default fallback
  \*/
  if (!function_exists('aqualuxe_get_option')) {
  function aqualuxe_get_option($option, $default = '')
    {
        return get_theme_mod($option, $default);
  }
  }

/\*\*

- Check if WooCommerce is active
  \*/
  if (!function_exists('aqualuxe_is_woocommerce_active')) {
  function aqualuxe_is_woocommerce_active()
  {
  return class_exists('WooCommerce');
  }
  }

/\*\*

- Get social media icons
  \*/
  if (!function_exists('aqualuxe_get_social_icons')) {
  function aqualuxe_get_social_icons()
  {
  $social_links = [
  'facebook' => get_theme_mod('aqualuxe_facebook_url', ''),
  'twitter' => get_theme_mod('aqualuxe_twitter_url', ''),
  'instagram' => get_theme_mod('aqualuxe_instagram_url', ''),
  'youtube' => get_theme_mod('aqualuxe_youtube_url', ''),
  'linkedin' => get_theme_mod('aqualuxe_linkedin_url', ''),
  ];

          $output = '<div class="social-icons">';

          foreach ($social_links as $platform => $url) {
              if (!empty($url)) {
                  $output .= sprintf(
                      '<a href="%s" class="social-icon social-icon-%s" target="_blank" rel="noopener noreferrer" aria-label="%s">
                          <i class="fab fa-%s" aria-hidden="true"></i>
                      </a>',
                      esc_url($url),
                      esc_attr($platform),
                      esc_attr(ucfirst($platform)),
                      esc_attr($platform)
                  );
              }
          }

          $output .= '</div>';

          return $output;
      }

  }

/\*\*

- Theme update checker (for premium themes)
  \*/
  if (!function_exists('aqualuxe_check_for_updates')) {
  function aqualuxe_check_for_updates()
  {
  $license_key = get_option('aqualuxe_license_key');

          if (!$license_key) {
              return;
          }

          $remote_version = wp_remote_get('https://api.yoursite.com/check-version?license=' . $license_key);

          if (!is_wp_error($remote_version)) {
              $version_data = wp_remote_retrieve_body($remote_version);
              $version_data = json_decode($version_data, true);

              if (version_compare(AQUALUXE_VERSION, $version_data['version'], '<')) {
                  add_action('admin_notices', function() use ($version_data) {
                      echo '<div class="notice notice-warning"><p>';
                      printf(
                          esc_html__('A new version (%s) of AquaLuxe theme is available. %s', 'aqualuxe'),
                          esc_html($version_data['version']),
                          '<a href="' . esc_url($version_data['download_url']) . '">' . esc_html__('Download Update', 'aqualuxe') . '</a>'
                      );
                      echo '</p></div>';
                  });
              }
          }
      }

  }
  add_action('admin_init', 'aqualuxe_check_for_updates');

// EOF
