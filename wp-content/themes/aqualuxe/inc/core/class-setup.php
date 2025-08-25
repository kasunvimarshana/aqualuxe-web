<?php
/**
 * Theme Setup Class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Theme Setup Class
 * 
 * This class is responsible for setting up theme features and functionality.
 */
class Setup {
    /**
     * Instance of this class
     *
     * @var Setup
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return Setup
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->setup_hooks();
    }

    /**
     * Setup hooks
     *
     * @return void
     */
    private function setup_hooks() {
        add_action( 'after_setup_theme', [ $this, 'setup_theme' ] );
        add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
        add_action( 'init', [ $this, 'register_menus' ] );
        add_action( 'init', [ $this, 'register_post_types' ] );
        add_action( 'init', [ $this, 'register_taxonomies' ] );
        add_filter( 'body_class', [ $this, 'body_classes' ] );
    }

    /**
     * Setup theme features and functionality
     *
     * @return void
     */
    public function setup_theme() {
        // Make theme available for translation
    // Translation loading handled in functions.php at 'init' only. No early loading here.

        // Add default posts and comments RSS feed links to head
        add_theme_support( 'automatic-feed-links' );

        // Let WordPress manage the document title
        add_theme_support( 'title-tag' );

        // Enable support for Post Thumbnails on posts and pages
        add_theme_support( 'post-thumbnails' );

        // Set default thumbnail size
        set_post_thumbnail_size( 1200, 675, true );

        // Add custom image sizes
        add_image_size( 'aqualuxe-featured', 1600, 900, true );
        add_image_size( 'aqualuxe-card', 600, 400, true );
        add_image_size( 'aqualuxe-thumbnail', 300, 200, true );

        // Switch default core markup to output valid HTML5
        add_theme_support(
            'html5',
            [
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
                'style',
                'script',
            ]
        );

        // Add theme support for selective refresh for widgets
        add_theme_support( 'customize-selective-refresh-widgets' );

        // Add support for editor styles
        add_theme_support( 'editor-styles' );

        // Add support for responsive embeds
        add_theme_support( 'responsive-embeds' );

        // Add support for full and wide align images
        add_theme_support( 'align-wide' );

        // Add support for custom logo
        add_theme_support(
            'custom-logo',
            [
                'height'      => 250,
                'width'       => 250,
                'flex-width'  => true,
                'flex-height' => true,
            ]
        );

        // Add support for custom backgrounds
        add_theme_support(
            'custom-background',
            [
                'default-color' => 'ffffff',
                'default-image' => '',
            ]
        );

        // Add support for custom header
        add_theme_support(
            'custom-header',
            [
                'default-image'      => '',
                'width'              => 1920,
                'height'             => 500,
                'flex-height'        => true,
                'flex-width'         => true,
                'default-text-color' => '000000',
                'header-text'        => true,
                'uploads'            => true,
            ]
        );

        // Add support for block templates
        add_theme_support( 'block-templates' );

        // Add support for block styles
        add_theme_support( 'wp-block-styles' );

        // Add support for editor color palette
        add_theme_support(
            'editor-color-palette',
            [
                [
                    'name'  => __( 'Primary', 'aqualuxe' ),
                    'slug'  => 'primary',
                    'color' => '#0073aa',
                ],
                [
                    'name'  => __( 'Secondary', 'aqualuxe' ),
                    'slug'  => 'secondary',
                    'color' => '#23282d',
                ],
                [
                    'name'  => __( 'Accent', 'aqualuxe' ),
                    'slug'  => 'accent',
                    'color' => '#00a0d2',
                ],
                [
                    'name'  => __( 'Dark', 'aqualuxe' ),
                    'slug'  => 'dark',
                    'color' => '#121212',
                ],
                [
                    'name'  => __( 'Light', 'aqualuxe' ),
                    'slug'  => 'light',
                    'color' => '#f8f9fa',
                ],
                [
                    'name'  => __( 'White', 'aqualuxe' ),
                    'slug'  => 'white',
                    'color' => '#ffffff',
                ],
            ]
        );

        // Add support for editor font sizes
        add_theme_support(
            'editor-font-sizes',
            [
                [
                    'name' => __( 'Small', 'aqualuxe' ),
                    'size' => 14,
                    'slug' => 'small',
                ],
                [
                    'name' => __( 'Normal', 'aqualuxe' ),
                    'size' => 16,
                    'slug' => 'normal',
                ],
                [
                    'name' => __( 'Medium', 'aqualuxe' ),
                    'size' => 20,
                    'slug' => 'medium',
                ],
                [
                    'name' => __( 'Large', 'aqualuxe' ),
                    'size' => 24,
                    'slug' => 'large',
                ],
                [
                    'name' => __( 'Huge', 'aqualuxe' ),
                    'size' => 32,
                    'slug' => 'huge',
                ],
            ]
        );
    }

    /**
     * Register widget areas
     *
     * @return void
     */
    public function register_sidebars() {
        register_sidebar(
            [
                'name'          => __( 'Main Sidebar', 'aqualuxe' ),
                'id'            => 'sidebar-1',
                'description'   => __( 'Add widgets here to appear in your sidebar.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ]
        );

        register_sidebar(
            [
                'name'          => __( 'Footer 1', 'aqualuxe' ),
                'id'            => 'footer-1',
                'description'   => __( 'Add widgets here to appear in the first footer column.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="widget-title">',
                'after_title'   => '</h4>',
            ]
        );

        register_sidebar(
            [
                'name'          => __( 'Footer 2', 'aqualuxe' ),
                'id'            => 'footer-2',
                'description'   => __( 'Add widgets here to appear in the second footer column.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="widget-title">',
                'after_title'   => '</h4>',
            ]
        );

        register_sidebar(
            [
                'name'          => __( 'Footer 3', 'aqualuxe' ),
                'id'            => 'footer-3',
                'description'   => __( 'Add widgets here to appear in the third footer column.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="widget-title">',
                'after_title'   => '</h4>',
            ]
        );

        register_sidebar(
            [
                'name'          => __( 'Footer 4', 'aqualuxe' ),
                'id'            => 'footer-4',
                'description'   => __( 'Add widgets here to appear in the fourth footer column.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="widget-title">',
                'after_title'   => '</h4>',
            ]
        );

        // Register WooCommerce sidebar if WooCommerce is active
        if ( \AquaLuxe\Core\Theme::get_instance()->is_woocommerce_active() ) {
            register_sidebar(
                [
                    'name'          => __( 'Shop Sidebar', 'aqualuxe' ),
                    'id'            => 'shop-sidebar',
                    'description'   => __( 'Add widgets here to appear in your shop sidebar.', 'aqualuxe' ),
                    'before_widget' => '<div id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</div>',
                    'before_title'  => '<h3 class="widget-title">',
                    'after_title'   => '</h3>',
                ]
            );
        }
    }

    /**
     * Register navigation menus
     *
     * @return void
     */
    public function register_menus() {
        register_nav_menus(
            [
                'primary'   => __( 'Primary Menu', 'aqualuxe' ),
                'secondary' => __( 'Secondary Menu', 'aqualuxe' ),
                'footer'    => __( 'Footer Menu', 'aqualuxe' ),
                'social'    => __( 'Social Menu', 'aqualuxe' ),
            ]
        );
    }

    /**
     * Register custom post types
     *
     * @return void
     */
    public function register_post_types() {
        // Register testimonials post type
        register_post_type(
            'testimonial',
            [
                'labels'             => [
                    'name'               => __( 'Testimonials', 'aqualuxe' ),
                    'singular_name'      => __( 'Testimonial', 'aqualuxe' ),
                    'add_new'            => __( 'Add New', 'aqualuxe' ),
                    'add_new_item'       => __( 'Add New Testimonial', 'aqualuxe' ),
                    'edit_item'          => __( 'Edit Testimonial', 'aqualuxe' ),
                    'new_item'           => __( 'New Testimonial', 'aqualuxe' ),
                    'view_item'          => __( 'View Testimonial', 'aqualuxe' ),
                    'search_items'       => __( 'Search Testimonials', 'aqualuxe' ),
                    'not_found'          => __( 'No testimonials found', 'aqualuxe' ),
                    'not_found_in_trash' => __( 'No testimonials found in Trash', 'aqualuxe' ),
                ],
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => [ 'slug' => 'testimonial' ],
                'capability_type'    => 'post',
                'has_archive'        => false,
                'hierarchical'       => false,
                'menu_position'      => 20,
                'menu_icon'          => 'dashicons-format-quote',
                'supports'           => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
                'show_in_rest'       => true,
            ]
        );

        // Register services post type
        register_post_type(
            'service',
            [
                'labels'             => [
                    'name'               => __( 'Services', 'aqualuxe' ),
                    'singular_name'      => __( 'Service', 'aqualuxe' ),
                    'add_new'            => __( 'Add New', 'aqualuxe' ),
                    'add_new_item'       => __( 'Add New Service', 'aqualuxe' ),
                    'edit_item'          => __( 'Edit Service', 'aqualuxe' ),
                    'new_item'           => __( 'New Service', 'aqualuxe' ),
                    'view_item'          => __( 'View Service', 'aqualuxe' ),
                    'search_items'       => __( 'Search Services', 'aqualuxe' ),
                    'not_found'          => __( 'No services found', 'aqualuxe' ),
                    'not_found_in_trash' => __( 'No services found in Trash', 'aqualuxe' ),
                ],
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => [ 'slug' => 'service' ],
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => 21,
                'menu_icon'          => 'dashicons-admin-tools',
                'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
                'show_in_rest'       => true,
            ]
        );

        // Register team members post type
        register_post_type(
            'team',
            [
                'labels'             => [
                    'name'               => __( 'Team', 'aqualuxe' ),
                    'singular_name'      => __( 'Team Member', 'aqualuxe' ),
                    'add_new'            => __( 'Add New', 'aqualuxe' ),
                    'add_new_item'       => __( 'Add New Team Member', 'aqualuxe' ),
                    'edit_item'          => __( 'Edit Team Member', 'aqualuxe' ),
                    'new_item'           => __( 'New Team Member', 'aqualuxe' ),
                    'view_item'          => __( 'View Team Member', 'aqualuxe' ),
                    'search_items'       => __( 'Search Team Members', 'aqualuxe' ),
                    'not_found'          => __( 'No team members found', 'aqualuxe' ),
                    'not_found_in_trash' => __( 'No team members found in Trash', 'aqualuxe' ),
                ],
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => [ 'slug' => 'team' ],
                'capability_type'    => 'post',
                'has_archive'        => false,
                'hierarchical'       => false,
                'menu_position'      => 22,
                'menu_icon'          => 'dashicons-groups',
                'supports'           => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
                'show_in_rest'       => true,
            ]
        );

        // Register FAQ post type
        register_post_type(
            'faq',
            [
                'labels'             => [
                    'name'               => __( 'FAQs', 'aqualuxe' ),
                    'singular_name'      => __( 'FAQ', 'aqualuxe' ),
                    'add_new'            => __( 'Add New', 'aqualuxe' ),
                    'add_new_item'       => __( 'Add New FAQ', 'aqualuxe' ),
                    'edit_item'          => __( 'Edit FAQ', 'aqualuxe' ),
                    'new_item'           => __( 'New FAQ', 'aqualuxe' ),
                    'view_item'          => __( 'View FAQ', 'aqualuxe' ),
                    'search_items'       => __( 'Search FAQs', 'aqualuxe' ),
                    'not_found'          => __( 'No FAQs found', 'aqualuxe' ),
                    'not_found_in_trash' => __( 'No FAQs found in Trash', 'aqualuxe' ),
                ],
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => [ 'slug' => 'faq' ],
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => 23,
                'menu_icon'          => 'dashicons-editor-help',
                'supports'           => [ 'title', 'editor', 'custom-fields' ],
                'show_in_rest'       => true,
            ]
        );
    }

    /**
     * Register custom taxonomies
     *
     * @return void
     */
    public function register_taxonomies() {
        // Register service categories
        register_taxonomy(
            'service_category',
            [ 'service' ],
            [
                'labels'            => [
                    'name'              => __( 'Service Categories', 'aqualuxe' ),
                    'singular_name'     => __( 'Service Category', 'aqualuxe' ),
                    'search_items'      => __( 'Search Service Categories', 'aqualuxe' ),
                    'all_items'         => __( 'All Service Categories', 'aqualuxe' ),
                    'parent_item'       => __( 'Parent Service Category', 'aqualuxe' ),
                    'parent_item_colon' => __( 'Parent Service Category:', 'aqualuxe' ),
                    'edit_item'         => __( 'Edit Service Category', 'aqualuxe' ),
                    'update_item'       => __( 'Update Service Category', 'aqualuxe' ),
                    'add_new_item'      => __( 'Add New Service Category', 'aqualuxe' ),
                    'new_item_name'     => __( 'New Service Category Name', 'aqualuxe' ),
                    'menu_name'         => __( 'Categories', 'aqualuxe' ),
                ],
                'hierarchical'      => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'service-category' ],
                'show_in_rest'      => true,
            ]
        );

        // Register FAQ categories
        register_taxonomy(
            'faq_category',
            [ 'faq' ],
            [
                'labels'            => [
                    'name'              => __( 'FAQ Categories', 'aqualuxe' ),
                    'singular_name'     => __( 'FAQ Category', 'aqualuxe' ),
                    'search_items'      => __( 'Search FAQ Categories', 'aqualuxe' ),
                    'all_items'         => __( 'All FAQ Categories', 'aqualuxe' ),
                    'parent_item'       => __( 'Parent FAQ Category', 'aqualuxe' ),
                    'parent_item_colon' => __( 'Parent FAQ Category:', 'aqualuxe' ),
                    'edit_item'         => __( 'Edit FAQ Category', 'aqualuxe' ),
                    'update_item'       => __( 'Update FAQ Category', 'aqualuxe' ),
                    'add_new_item'      => __( 'Add New FAQ Category', 'aqualuxe' ),
                    'new_item_name'     => __( 'New FAQ Category Name', 'aqualuxe' ),
                    'menu_name'         => __( 'Categories', 'aqualuxe' ),
                ],
                'hierarchical'      => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'faq-category' ],
                'show_in_rest'      => true,
            ]
        );

        // Register team member departments
        register_taxonomy(
            'team_department',
            [ 'team' ],
            [
                'labels'            => [
                    'name'              => __( 'Departments', 'aqualuxe' ),
                    'singular_name'     => __( 'Department', 'aqualuxe' ),
                    'search_items'      => __( 'Search Departments', 'aqualuxe' ),
                    'all_items'         => __( 'All Departments', 'aqualuxe' ),
                    'parent_item'       => __( 'Parent Department', 'aqualuxe' ),
                    'parent_item_colon' => __( 'Parent Department:', 'aqualuxe' ),
                    'edit_item'         => __( 'Edit Department', 'aqualuxe' ),
                    'update_item'       => __( 'Update Department', 'aqualuxe' ),
                    'add_new_item'      => __( 'Add New Department', 'aqualuxe' ),
                    'new_item_name'     => __( 'New Department Name', 'aqualuxe' ),
                    'menu_name'         => __( 'Departments', 'aqualuxe' ),
                ],
                'hierarchical'      => true,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'department' ],
                'show_in_rest'      => true,
            ]
        );
    }

    /**
     * Add custom classes to the body tag
     *
     * @param array $classes Body classes
     * @return array
     */
    public function body_classes( $classes ) {
        // Add a class if WooCommerce is active
        if ( \AquaLuxe\Core\Theme::get_instance()->is_woocommerce_active() ) {
            $classes[] = 'woocommerce-active';
        } else {
            $classes[] = 'woocommerce-inactive';
        }

        // Add a class for the dark mode
        $dark_mode = get_theme_mod( 'aqualuxe_dark_mode', false );
        if ( $dark_mode ) {
            $classes[] = 'dark-mode';
        }

        // Add a class for the layout
        $layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );
        $classes[] = 'layout-' . $layout;

        return $classes;
    }
}