<?php
/**
 * AquaLuxe functions and definitions
 *
 * This file contains the main functionality for the AquaLuxe theme.
 * It sets up theme defaults, registers support for various WordPress features,
 * and includes necessary files for theme operation.
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @author NinjaTech AI
 * @link https://aqualuxetheme.com
 */

// Define theme version
if ( ! defined( 'AQUALUXE_VERSION' ) ) {
    define( 'AQUALUXE_VERSION', '1.0.0' );
}

// Define theme directory path and URI
if ( ! defined( 'AQUALUXE_DIR' ) ) {
    define( 'AQUALUXE_DIR', trailingslashit( get_template_directory() ) );
}

if ( ! defined( 'AQUALUXE_URI' ) ) {
    define( 'AQUALUXE_URI', trailingslashit( get_template_directory_uri() ) );
}

/**
 * Main AquaLuxe Theme Class
 *
 * This class handles the main functionality of the theme.
 * It follows a singleton pattern to ensure only one instance exists.
 */
final class AquaLuxe_Theme {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Theme
     */
    private static $instance;

    /**
     * Get the singleton instance of this class
     *
     * @return AquaLuxe_Theme
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     *
     * Sets up theme defaults and registers support for various WordPress features.
     */
    private function __construct() {
        // Add theme support
        add_action( 'after_setup_theme', array( $this, 'setup' ) );
        
        // Register widget areas
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
        
        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        
        // Include required files
        $this->includes();
        
        // Initialize hooks
        $this->init_hooks();
    }

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * This function is hooked into the after_setup_theme hook.
     */
    public function setup() {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         */
        load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . 'languages' );

        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support( 'title-tag' );

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support( 'post-thumbnails' );

        // Register navigation menus
        register_nav_menus(
            array(
                'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
                'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
                'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
                'mobile'    => esc_html__( 'Mobile Menu', 'aqualuxe' ),
            )
        );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            )
        );

        // Set up the WordPress core custom background feature.
        add_theme_support(
            'custom-background',
            apply_filters(
                'aqualuxe_custom_background_args',
                array(
                    'default-color' => 'ffffff',
                    'default-image' => '',
                )
            )
        );

        // Add theme support for selective refresh for widgets.
        add_theme_support( 'customize-selective-refresh-widgets' );

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support(
            'custom-logo',
            array(
                'height'      => 250,
                'width'       => 250,
                'flex-width'  => true,
                'flex-height' => true,
            )
        );

        // Add support for editor styles
        add_theme_support( 'editor-styles' );

        // Add support for responsive embeds
        add_theme_support( 'responsive-embeds' );

        // Add support for full and wide align images
        add_theme_support( 'align-wide' );

        // Add support for custom color palette
        add_theme_support(
            'editor-color-palette',
            array(
                array(
                    'name'  => esc_html__( 'Primary', 'aqualuxe' ),
                    'slug'  => 'primary',
                    'color' => '#0ea5e9',
                ),
                array(
                    'name'  => esc_html__( 'Secondary', 'aqualuxe' ),
                    'slug'  => 'secondary',
                    'color' => '#0369a1',
                ),
                array(
                    'name'  => esc_html__( 'Accent', 'aqualuxe' ),
                    'slug'  => 'accent',
                    'color' => '#10b981',
                ),
                array(
                    'name'  => esc_html__( 'Dark', 'aqualuxe' ),
                    'slug'  => 'dark',
                    'color' => '#1f2937',
                ),
                array(
                    'name'  => esc_html__( 'Light', 'aqualuxe' ),
                    'slug'  => 'light',
                    'color' => '#f9fafb',
                ),
            )
        );

        // Add image sizes
        add_image_size( 'aqualuxe-featured', 1200, 600, true );
        add_image_size( 'aqualuxe-blog', 800, 450, true );
        add_image_size( 'aqualuxe-thumbnail', 400, 300, true );

        // WooCommerce support
        $this->woocommerce_support();
    }

    /**
     * Register widget areas.
     *
     * This function is hooked into the widgets_init action.
     */
    public function widgets_init() {
        register_sidebar(
            array(
                'name'          => esc_html__( 'Sidebar', 'aqualuxe' ),
                'id'            => 'sidebar-1',
                'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        // Footer widget areas
        for ( $i = 1; $i <= 4; $i++ ) {
            register_sidebar(
                array(
                    'name'          => sprintf( esc_html__( 'Footer %d', 'aqualuxe' ), $i ),
                    'id'            => 'footer-' . $i,
                    'description'   => sprintf( esc_html__( 'Add widgets here to appear in footer column %d.', 'aqualuxe' ), $i ),
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<h3 class="widget-title">',
                    'after_title'   => '</h3>',
                )
            );
        }

        // Shop sidebar
        if ( class_exists( 'WooCommerce' ) ) {
            register_sidebar(
                array(
                    'name'          => esc_html__( 'Shop Sidebar', 'aqualuxe' ),
                    'id'            => 'sidebar-shop',
                    'description'   => esc_html__( 'Add widgets here to appear in your shop sidebar.', 'aqualuxe' ),
                    'before_widget' => '<section id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<h2 class="widget-title">',
                    'after_title'   => '</h2>',
                )
            );

            // Shop filters
            register_sidebar(
                array(
                    'name'          => esc_html__( 'Shop Filters', 'aqualuxe' ),
                    'id'            => 'sidebar-shop-filters',
                    'description'   => esc_html__( 'Add widgets here to appear in your shop filters area.', 'aqualuxe' ),
                    'before_widget' => '<section id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<h2 class="widget-title">',
                    'after_title'   => '</h2>',
                )
            );
        }
    }

    /**
     * Enqueue scripts and styles.
     *
     * This function is hooked into the wp_enqueue_scripts action.
     */
    public function enqueue_scripts() {
        // Enqueue Google Fonts
        wp_enqueue_style(
            'aqualuxe-fonts',
            'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap',
            array(),
            AQUALUXE_VERSION
        );

        // Enqueue main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            get_stylesheet_uri(),
            array(),
            AQUALUXE_VERSION
        );

        // Enqueue theme stylesheet
        wp_enqueue_style(
            'aqualuxe-main',
            AQUALUXE_URI . 'assets/dist/css/main.css',
            array(),
            AQUALUXE_VERSION
        );

        // Enqueue theme script
        wp_enqueue_script(
            'aqualuxe-main',
            AQUALUXE_URI . 'assets/dist/js/main.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        // Enqueue dark mode script
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            AQUALUXE_URI . 'assets/dist/js/dark-mode.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        // Localize script
        wp_localize_script(
            'aqualuxe-main',
            'aqualuxeVars',
            array(
                'ajaxurl'        => admin_url( 'admin-ajax.php' ),
                'nonce'          => wp_create_nonce( 'aqualuxe-nonce' ),
                'view_cart_text' => esc_html__( 'View Cart', 'aqualuxe' ),
                'is_rtl'         => is_rtl(),
            )
        );

        // Add comment reply script
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }

        // WooCommerce specific scripts
        if ( class_exists( 'WooCommerce' ) ) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_URI . 'assets/dist/js/woocommerce.js',
                array( 'jquery' ),
                AQUALUXE_VERSION,
                true
            );
        }
    }

    /**
     * Include required files.
     *
     * This function includes all necessary files for the theme.
     */
    private function includes() {
        // Core functionality
        require_once AQUALUXE_DIR . 'inc/core/theme-setup.php';
        require_once AQUALUXE_DIR . 'inc/core/assets.php';
        require_once AQUALUXE_DIR . 'inc/core/template-functions.php';
        require_once AQUALUXE_DIR . 'inc/core/template-tags.php';
        require_once AQUALUXE_DIR . 'inc/core/class-aqualuxe-walker-nav-menu.php';

        // Customizer
        require_once AQUALUXE_DIR . 'inc/customizer/customizer.php';

        // Widgets
        require_once AQUALUXE_DIR . 'inc/widgets/class-aqualuxe-recent-posts-widget.php';
        require_once AQUALUXE_DIR . 'inc/widgets/class-aqualuxe-social-widget.php';

        // Compatibility files
        require_once AQUALUXE_DIR . 'inc/compatibility/wpml.php';
        require_once AQUALUXE_DIR . 'inc/compatibility/multicurrency.php';
        require_once AQUALUXE_DIR . 'inc/compatibility/multivendor.php';

        // Performance optimization
        require_once AQUALUXE_DIR . 'inc/performance.php';

        // WooCommerce
        if ( class_exists( 'WooCommerce' ) ) {
            require_once AQUALUXE_DIR . 'inc/woocommerce/woocommerce.php';
            require_once AQUALUXE_DIR . 'inc/woocommerce/template-hooks.php';
            require_once AQUALUXE_DIR . 'inc/woocommerce/template-functions.php';
            require_once AQUALUXE_DIR . 'inc/woocommerce/class-aqualuxe-woocommerce.php';
        }
    }

    /**
     * Initialize hooks.
     *
     * This function sets up various hooks for the theme.
     */
    private function init_hooks() {
        // Add body classes
        add_filter( 'body_class', array( $this, 'body_classes' ) );

        // Add page slug to body class
        add_filter( 'body_class', array( $this, 'add_slug_to_body_class' ) );

        // Add custom image sizes to media library
        add_filter( 'image_size_names_choose', array( $this, 'custom_image_sizes' ) );

        // Add excerpt support for pages
        add_post_type_support( 'page', 'excerpt' );

        // Disable Gutenberg on certain templates
        add_filter( 'use_block_editor_for_post', array( $this, 'disable_gutenberg_on_templates' ), 10, 2 );

        // Add custom classes to menu items
        add_filter( 'nav_menu_css_class', array( $this, 'add_menu_item_classes' ), 10, 4 );

        // Add custom classes to menu links
        add_filter( 'nav_menu_link_attributes', array( $this, 'add_menu_link_classes' ), 10, 4 );

        // Add custom classes to menu list items
        add_filter( 'nav_menu_submenu_css_class', array( $this, 'add_submenu_classes' ), 10, 3 );
    }

    /**
     * Adds custom classes to the array of body classes.
     *
     * @param array $classes Classes for the body element.
     * @return array
     */
    public function body_classes( $classes ) {
        // Add a class if there is a custom header image
        if ( has_header_image() ) {
            $classes[] = 'has-header-image';
        }

        // Add a class if sidebar is active
        if ( is_active_sidebar( 'sidebar-1' ) && ! is_page_template( 'templates/full-width.php' ) ) {
            $classes[] = 'has-sidebar';
            
            // Add sidebar position class
            $sidebar_position = get_theme_mod( 'aqualuxe_sidebar_position', 'right' );
            $classes[] = 'sidebar-' . $sidebar_position;
        } else {
            $classes[] = 'no-sidebar';
        }

        // Add a class for the header layout
        $header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
        $classes[] = 'header-' . $header_layout;

        // Add a class for the footer layout
        $footer_layout = get_theme_mod( 'aqualuxe_footer_layout', '4-columns' );
        $classes[] = 'footer-' . $footer_layout;

        // Add a class for the blog layout
        if ( is_home() || is_archive() || is_search() ) {
            $blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );
            $classes[] = 'blog-' . $blog_layout;
        }

        // Add a class for dark mode
        $default_color_scheme = get_theme_mod( 'aqualuxe_default_color_scheme', 'light' );
        if ( 'dark' === $default_color_scheme ) {
            $classes[] = 'dark-mode';
        }

        return $classes;
    }

    /**
     * Add page slug to body class.
     *
     * @param array $classes Classes for the body element.
     * @return array
     */
    public function add_slug_to_body_class( $classes ) {
        global $post;
        
        if ( isset( $post ) ) {
            $classes[] = 'page-' . $post->post_name;
        }
        
        return $classes;
    }

    /**
     * Add custom image sizes to media library.
     *
     * @param array $sizes Image sizes.
     * @return array
     */
    public function custom_image_sizes( $sizes ) {
        return array_merge(
            $sizes,
            array(
                'aqualuxe-featured'  => esc_html__( 'Featured Image', 'aqualuxe' ),
                'aqualuxe-blog'      => esc_html__( 'Blog Image', 'aqualuxe' ),
                'aqualuxe-thumbnail' => esc_html__( 'Thumbnail', 'aqualuxe' ),
            )
        );
    }

    /**
     * Disable Gutenberg on certain templates.
     *
     * @param bool    $can_edit Whether to use the block editor.
     * @param WP_Post $post     The post being checked.
     * @return bool
     */
    public function disable_gutenberg_on_templates( $can_edit, $post ) {
        // Get the template
        $template = get_page_template_slug( $post->ID );
        
        // List of templates to disable Gutenberg
        $disable_gutenberg_templates = array(
            'templates/landing-page.php',
            'templates/elementor-canvas.php',
        );
        
        if ( in_array( $template, $disable_gutenberg_templates, true ) ) {
            return false;
        }
        
        return $can_edit;
    }

    /**
     * Add custom classes to menu items.
     *
     * @param array    $classes The CSS classes that are applied to the menu item's <li> element.
     * @param WP_Post  $item    The current menu item.
     * @param stdClass $args    An object of wp_nav_menu() arguments.
     * @param int      $depth   Depth of menu item.
     * @return array
     */
    public function add_menu_item_classes( $classes, $item, $args, $depth ) {
        // Add custom classes based on menu location
        if ( 'primary' === $args->theme_location ) {
            $classes[] = 'nav-item';
            
            // Add class for items with children
            if ( in_array( 'menu-item-has-children', $classes, true ) ) {
                $classes[] = 'dropdown';
            }
        }
        
        return $classes;
    }

    /**
     * Add custom classes to menu links.
     *
     * @param array    $atts   The HTML attributes applied to the menu item's <a> element.
     * @param WP_Post  $item   The current menu item.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     * @param int      $depth  Depth of menu item.
     * @return array
     */
    public function add_menu_link_classes( $atts, $item, $args, $depth ) {
        // Add custom classes based on menu location
        if ( 'primary' === $args->theme_location ) {
            $atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' nav-link' : 'nav-link';
            
            // Add class for items with children
            if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
                $atts['class'] .= ' dropdown-toggle';
                $atts['data-toggle'] = 'dropdown';
                $atts['aria-haspopup'] = 'true';
                $atts['aria-expanded'] = 'false';
            }
        }
        
        return $atts;
    }

    /**
     * Add custom classes to submenu.
     *
     * @param array    $classes The CSS classes that are applied to the menu <ul> element.
     * @param stdClass $args    An object of wp_nav_menu() arguments.
     * @param int      $depth   Depth of menu item.
     * @return array
     */
    public function add_submenu_classes( $classes, $args, $depth ) {
        // Add custom classes based on menu location
        if ( 'primary' === $args->theme_location ) {
            $classes[] = 'dropdown-menu';
        }
        
        return $classes;
    }

    /**
     * Add WooCommerce support.
     */
    private function woocommerce_support() {
        if ( class_exists( 'WooCommerce' ) ) {
            // Add theme support for WooCommerce
            add_theme_support( 'woocommerce' );
            
            // Add support for WooCommerce features
            add_theme_support( 'wc-product-gallery-zoom' );
            add_theme_support( 'wc-product-gallery-lightbox' );
            add_theme_support( 'wc-product-gallery-slider' );
        }
    }
}

// Initialize the theme
AquaLuxe_Theme::get_instance();