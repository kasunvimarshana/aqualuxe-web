<?php
/**
 * Setup Class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

/**
 * Setup Class
 * 
 * This class handles theme setup and configuration.
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
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Register image sizes
        add_action( 'after_setup_theme', [ $this, 'register_image_sizes' ] );
        
        // Register sidebars
        add_action( 'widgets_init', [ $this, 'register_sidebars' ] );
        
        // Add body classes
        add_filter( 'body_class', [ $this, 'body_classes' ] );
        
        // Add pingback header
        add_action( 'wp_head', [ $this, 'pingback_header' ] );
        
        // Add schema markup
        add_filter( 'language_attributes', [ $this, 'add_schema_markup' ] );
        
        // Add Open Graph meta tags
        add_action( 'wp_head', [ $this, 'add_open_graph_tags' ] );
    }

    /**
     * Register image sizes
     *
     * @return void
     */
    public function register_image_sizes() {
        // Hero image size
        add_image_size( 'aqualuxe-hero', 1920, 800, true );
        
        // Featured image size
        add_image_size( 'aqualuxe-featured', 800, 600, true );
        
        // Product thumbnail size
        add_image_size( 'aqualuxe-product-thumb', 400, 400, true );
        
        // Blog thumbnail size
        add_image_size( 'aqualuxe-blog-thumb', 600, 400, true );
        
        // Service thumbnail size
        add_image_size( 'aqualuxe-service-thumb', 500, 300, true );
        
        // Team member image size
        add_image_size( 'aqualuxe-team', 300, 300, true );
        
        // Gallery image size
        add_image_size( 'aqualuxe-gallery', 600, 600, true );
    }

    /**
     * Register sidebars
     *
     * @return void
     */
    public function register_sidebars() {
        // Main sidebar is registered in Theme class
        // Additional sidebars can be registered here
        
        // Home sidebar
        register_sidebar(
            [
                'name'          => esc_html__( 'Home Sidebar', 'aqualuxe' ),
                'id'            => 'home-sidebar',
                'description'   => esc_html__( 'Add widgets here to appear on the homepage.', 'aqualuxe' ),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h2 class="widget-title">',
                'after_title'   => '</h2>',
            ]
        );
        
        // Top bar widget area
        register_sidebar(
            [
                'name'          => esc_html__( 'Top Bar', 'aqualuxe' ),
                'id'            => 'top-bar',
                'description'   => esc_html__( 'Add widgets here to appear in the top bar.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2 class="widget-title screen-reader-text">',
                'after_title'   => '</h2>',
            ]
        );
        
        // Header widget area
        register_sidebar(
            [
                'name'          => esc_html__( 'Header', 'aqualuxe' ),
                'id'            => 'header',
                'description'   => esc_html__( 'Add widgets here to appear in the header.', 'aqualuxe' ),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h2 class="widget-title screen-reader-text">',
                'after_title'   => '</h2>',
            ]
        );
        
        // WooCommerce product page sidebar
        if ( class_exists( 'WooCommerce' ) ) {
            register_sidebar(
                [
                    'name'          => esc_html__( 'Product Sidebar', 'aqualuxe' ),
                    'id'            => 'product-sidebar',
                    'description'   => esc_html__( 'Add widgets here to appear on product pages.', 'aqualuxe' ),
                    'before_widget' => '<section id="%1$s" class="widget %2$s">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<h2 class="widget-title">',
                    'after_title'   => '</h2>',
                ]
            );
        }
    }

    /**
     * Add custom body classes
     *
     * @param array $classes Body classes.
     * @return array
     */
    public function body_classes( $classes ) {
        // Add a class if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            $classes[] = 'woocommerce-active';
        } else {
            $classes[] = 'woocommerce-inactive';
        }
        
        // Add a class for the dark mode
        if ( $this->is_dark_mode() ) {
            $classes[] = 'dark-mode';
        }
        
        // Add a class for the header layout
        $header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
        $classes[] = 'header-layout-' . $header_layout;
        
        // Add a class for the footer layout
        $footer_layout = get_theme_mod( 'aqualuxe_footer_layout', 'default' );
        $classes[] = 'footer-layout-' . $footer_layout;
        
        // Add a class for the sidebar layout
        if ( is_active_sidebar( 'sidebar-1' ) ) {
            $classes[] = 'has-sidebar';
        } else {
            $classes[] = 'no-sidebar';
        }
        
        // Add a class for the page layout
        if ( is_page() ) {
            $page_layout = get_post_meta( get_the_ID(), '_aqualuxe_page_layout', true );
            if ( ! empty( $page_layout ) ) {
                $classes[] = 'page-layout-' . $page_layout;
            }
        }
        
        return $classes;
    }

    /**
     * Add pingback header
     *
     * @return void
     */
    public function pingback_header() {
        if ( is_singular() && pings_open() ) {
            printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
        }
    }

    /**
     * Add schema markup
     *
     * @param string $language_attributes Language attributes.
     * @return string
     */
    public function add_schema_markup( $language_attributes ) {
        $schema = 'http://schema.org/';
        
        // Determine the schema type
        if ( is_singular( 'product' ) ) {
            $type = 'Product';
        } elseif ( is_singular( 'post' ) ) {
            $type = 'Article';
        } elseif ( is_author() ) {
            $type = 'ProfilePage';
        } elseif ( is_search() ) {
            $type = 'SearchResultsPage';
        } else {
            $type = 'WebPage';
        }
        
        $schema_type = apply_filters( 'aqualuxe_schema_type', $type );
        
        return $language_attributes . ' itemscope itemtype="' . esc_attr( $schema . $schema_type ) . '"';
    }

    /**
     * Add Open Graph meta tags
     *
     * @return void
     */
    public function add_open_graph_tags() {
        // Only add Open Graph tags if Yoast SEO is not active
        if ( class_exists( 'WPSEO_Options' ) ) {
            return;
        }
        
        global $post;
        
        if ( is_singular() && $post ) {
            // Get the featured image
            $image = '';
            if ( has_post_thumbnail( $post->ID ) ) {
                $img_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
                $image = $img_src[0];
            }
            
            // Get the excerpt
            $excerpt = '';
            if ( has_excerpt( $post->ID ) ) {
                $excerpt = strip_tags( get_the_excerpt() );
            } else {
                $excerpt = strip_tags( wp_trim_words( $post->post_content, 55, '' ) );
            }
            
            // Output Open Graph tags
            echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '" />' . "\n";
            echo '<meta property="og:type" content="article" />' . "\n";
            echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '" />' . "\n";
            echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
            
            if ( ! empty( $image ) ) {
                echo '<meta property="og:image" content="' . esc_url( $image ) . '" />' . "\n";
            }
            
            if ( ! empty( $excerpt ) ) {
                echo '<meta property="og:description" content="' . esc_attr( $excerpt ) . '" />' . "\n";
            }
        }
    }

    /**
     * Check if dark mode is enabled
     *
     * @return boolean
     */
    private function is_dark_mode() {
        // Check user preference from cookie
        if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) ) {
            return $_COOKIE['aqualuxe_dark_mode'] === 'true';
        }
        
        // Check theme default
        return get_theme_mod( 'aqualuxe_dark_mode_default', false );
    }
}