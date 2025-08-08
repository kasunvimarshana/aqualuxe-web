<?php
/**
 * Theme Setup - Luxury Ornamental Fish Theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('aqualuxe_theme_setup')) {
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * @since 1.0.0
     */
    function aqualuxe_theme_setup() {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         */
        load_child_theme_textdomain('aqualuxe', get_stylesheet_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'primary' => __('Primary Menu', 'aqualuxe'),
            'footer'  => __('Footer Menu', 'aqualuxe'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
            'navigation-widgets',
        ));

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        // Add support for WooCommerce.
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        // Add support for responsive embedded content.
        add_theme_support('responsive-embeds');

        // Add support for custom logo.
        add_theme_support('custom-logo', array(
            'height'      => 100,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
            'header-text' => array('site-title', 'site-description'),
        ));

        // Add support for custom background.
        add_theme_support('custom-background', apply_filters('aqualuxe_custom_background_args', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        )));

        // Add support for custom header.
        add_theme_support('custom-header', apply_filters('aqualuxe_custom_header_args', array(
            'default-image'          => '',
            'default-text-color'     => '000000',
            'width'                  => 1920,
            'height'                 => 400,
            'flex-height'            => true,
            'flex-width'             => true,
            'wp-head-callback'       => 'aqualuxe_header_style',
            'admin-head-callback'    => '',
            'admin-preview-callback' => '',
        )));

        // Add support for post formats.
        add_theme_support('post-formats', array(
            'aside',
            'gallery',
            'link',
            'image',
            'quote',
            'status',
            'video',
            'audio',
            'chat',
        ));

        // Add support for starter content.
        add_theme_support('starter-content', array(
            'widgets' => array(
                // Place widgets in the sidebar
                'sidebar-1' => array(
                    'text_business_info',
                    'search',
                    'recent-posts',
                    'recent-comments',
                    'categories',
                    'meta',
                ),
            ),
            'nav_menus' => array(
                'primary' => array(
                    'name' => __('Primary Menu', 'aqualuxe'),
                ),
                'footer' => array(
                    'name' => __('Footer Menu', 'aqualuxe'),
                ),
            ),
            'posts' => array(
                'home',
                'about',
                'contact',
                'blog',
            ),
        ));
    }
}
add_action('after_setup_theme', 'aqualuxe_theme_setup');

if (!function_exists('aqualuxe_content_width')) {
    /**
     * Set the content width in pixels, based on the theme's design and stylesheet.
     *
     * Priority 0 to make it available to lower priority callbacks.
     *
     * @global int $content_width
     */
    function aqualuxe_content_width() {
        // This variable is intended to be overruled from themes.
        $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
    }
}
add_action('after_setup_theme', 'aqualuxe_content_width', 0);

if (!function_exists('aqualuxe_header_style')) {
    /**
     * Styles the header image and text displayed on the blog.
     *
     * @see aqualuxe_custom_header_setup().
     */
    function aqualuxe_header_style() {
        $header_text_color = get_header_textcolor();

        /*
         * If no custom options for text are set, let's bail.
         * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support('custom-header').
         */
        if (get_theme_support('custom-header', 'default-text-color') === $header_text_color) {
            return;
        }

        // If we get this far, we have custom styles. Let's do this.
        ?>
        <style type="text/css">
        <?php
        // Has the text been hidden?
        if (!display_header_text()) :
        ?>
            .site-title,
            .site-description {
                position: absolute;
                clip: rect(1px, 1px, 1px, 1px);
            }
        <?php
            // If the user has set a custom color for the text use that.
            else :
        ?>
            .site-title a,
            .site-description {
                color: #<?php echo esc_attr($header_text_color); ?>;
            }
        <?php endif; ?>
        </style>
        <?php
    }
}

if (!function_exists('aqualuxe_widgets_init')) {
    /**
     * Register widget area.
     *
     * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
     */
    function aqualuxe_widgets_init() {
        register_sidebar(array(
            'name'          => __('Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'description'   => __('Add widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
        
        register_sidebar(array(
            'name'          => __('Footer Widget Area 1', 'aqualuxe'),
            'id'            => 'footer-1',
            'description'   => __('Add widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
        
        register_sidebar(array(
            'name'          => __('Footer Widget Area 2', 'aqualuxe'),
            'id'            => 'footer-2',
            'description'   => __('Add widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
        
        register_sidebar(array(
            'name'          => __('Footer Widget Area 3', 'aqualuxe'),
            'id'            => 'footer-3',
            'description'   => __('Add widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
        
        register_sidebar(array(
            'name'          => __('Footer Widget Area 4', 'aqualuxe'),
            'id'            => 'footer-4',
            'description'   => __('Add widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));
    }
}
add_action('widgets_init', 'aqualuxe_widgets_init');