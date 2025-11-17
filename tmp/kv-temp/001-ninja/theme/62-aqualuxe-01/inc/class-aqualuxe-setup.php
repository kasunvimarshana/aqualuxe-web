<?php
/**
 * AquaLuxe Theme Setup Class
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Theme Setup Class
 */
class AquaLuxe_Setup {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Setup
     */
    private static $instance;

    /**
     * Main setup instance
     *
     * @return AquaLuxe_Setup
     */
    public static function instance() {
        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AquaLuxe_Setup ) ) {
            self::$instance = new AquaLuxe_Setup();
            self::$instance->init_hooks();
        }
        return self::$instance;
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Theme setup
        add_action( 'after_setup_theme', array( $this, 'setup' ) );
        
        // Register widget areas
        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
        
        // Register nav menus
        add_action( 'after_setup_theme', array( $this, 'register_nav_menus' ) );
        
        // Content width
        add_action( 'after_setup_theme', array( $this, 'content_width' ), 0 );
    }

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * @return void
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

        // Set up custom image sizes
        add_image_size( 'aqualuxe-featured', 1200, 600, true );
        add_image_size( 'aqualuxe-product', 600, 600, true );
        add_image_size( 'aqualuxe-thumbnail', 300, 300, true );

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

        // Add support for full and wide align images.
        add_theme_support( 'align-wide' );

        // Add support for responsive embeds.
        add_theme_support( 'responsive-embeds' );

        // Add support for editor styles.
        add_theme_support( 'editor-styles' );

        // Add support for block styles.
        add_theme_support( 'wp-block-styles' );

        // Add custom editor font sizes.
        add_theme_support(
            'editor-font-sizes',
            array(
                array(
                    'name'      => __( 'Small', 'aqualuxe' ),
                    'shortName' => __( 'S', 'aqualuxe' ),
                    'size'      => 14,
                    'slug'      => 'small',
                ),
                array(
                    'name'      => __( 'Normal', 'aqualuxe' ),
                    'shortName' => __( 'M', 'aqualuxe' ),
                    'size'      => 16,
                    'slug'      => 'normal',
                ),
                array(
                    'name'      => __( 'Large', 'aqualuxe' ),
                    'shortName' => __( 'L', 'aqualuxe' ),
                    'size'      => 20,
                    'slug'      => 'large',
                ),
                array(
                    'name'      => __( 'Huge', 'aqualuxe' ),
                    'shortName' => __( 'XL', 'aqualuxe' ),
                    'size'      => 24,
                    'slug'      => 'huge',
                ),
            )
        );

        // Add support for custom color scheme.
        add_theme_support(
            'editor-color-palette',
            array(
                array(
                    'name'  => __( 'Primary', 'aqualuxe' ),
                    'slug'  => 'primary',
                    'color' => '#0073aa',
                ),
                array(
                    'name'  => __( 'Secondary', 'aqualuxe' ),
                    'slug'  => 'secondary',
                    'color' => '#23282d',
                ),
                array(
                    'name'  => __( 'Accent', 'aqualuxe' ),
                    'slug'  => 'accent',
                    'color' => '#00a0d2',
                ),
                array(
                    'name'  => __( 'Highlight', 'aqualuxe' ),
                    'slug'  => 'highlight',
                    'color' => '#f0c808',
                ),
                array(
                    'name'  => __( 'White', 'aqualuxe' ),
                    'slug'  => 'white',
                    'color' => '#ffffff',
                ),
                array(
                    'name'  => __( 'Light Gray', 'aqualuxe' ),
                    'slug'  => 'light-gray',
                    'color' => '#f5f5f5',
                ),
                array(
                    'name'  => __( 'Medium Gray', 'aqualuxe' ),
                    'slug'  => 'medium-gray',
                    'color' => '#999999',
                ),
                array(
                    'name'  => __( 'Dark Gray', 'aqualuxe' ),
                    'slug'  => 'dark-gray',
                    'color' => '#333333',
                ),
                array(
                    'name'  => __( 'Black', 'aqualuxe' ),
                    'slug'  => 'black',
                    'color' => '#000000',
                ),
            )
        );
    }

    /**
     * Set the content width in pixels, based on the theme's design and stylesheet.
     *
     * Priority 0 to make it available to lower priority callbacks.
     *
     * @global int $content_width
     */
    public function content_width() {
        // This variable is intended to be overruled from themes.
        // Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
        // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
        $GLOBALS['content_width'] = apply_filters( 'aqualuxe_content_width', 1200 );
    }

    /**
     * Register widget areas.
     *
     * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
     */
    public function widgets_init() {
        register_sidebar(
            array(
                'name'          => __( 'Sidebar', 'aqualuxe' ),
                'id'            => 'sidebar-1',
                'description'   => __( 'Add widgets here to appear in your sidebar.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => __( 'Footer 1', 'aqualuxe' ),
                'id'            => 'footer-1',
                'description'   => __( 'Add widgets here to appear in footer column 1.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => __( 'Footer 2', 'aqualuxe' ),
                'id'            => 'footer-2',
                'description'   => __( 'Add widgets here to appear in footer column 2.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => __( 'Footer 3', 'aqualuxe' ),
                'id'            => 'footer-3',
                'description'   => __( 'Add widgets here to appear in footer column 3.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        register_sidebar(
            array(
                'name'          => __( 'Footer 4', 'aqualuxe' ),
                'id'            => 'footer-4',
                'description'   => __( 'Add widgets here to appear in footer column 4.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            )
        );

        // WooCommerce Shop Sidebar
        if ( class_exists( 'WooCommerce' ) ) {
            register_sidebar(
                array(
                    'name'          => __( 'Shop Sidebar', 'aqualuxe' ),
                    'id'            => 'shop-sidebar',
                    'description'   => __( 'Add widgets here to appear in shop sidebar.', 'aqualuxe' ),
                    'before_widget' => '<section id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<h2 class="widget-title">',
                    'after_title'   => '</h2>',
                )
            );
        }
    }

    /**
     * Register navigation menus
     *
     * @return void
     */
    public function register_nav_menus() {
        register_nav_menus(
            array(
                'primary'   => __( 'Primary Menu', 'aqualuxe' ),
                'secondary' => __( 'Secondary Menu', 'aqualuxe' ),
                'footer'    => __( 'Footer Menu', 'aqualuxe' ),
                'social'    => __( 'Social Menu', 'aqualuxe' ),
            )
        );

        // Register WooCommerce specific menus if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            register_nav_menus(
                array(
                    'shop'      => __( 'Shop Menu', 'aqualuxe' ),
                    'categories' => __( 'Categories Menu', 'aqualuxe' ),
                )
            );
        }
    }
}