<?php
/**
 * Main theme class - implements Singleton pattern
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe_Theme class
 */
class AquaLuxe_Theme {
    
    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Theme
     */
    private static $instance = null;

    /**
     * Modules registry
     *
     * @var array
     */
    private $modules = array();

    /**
     * Get single instance
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
     */
    private function __construct() {
        $this->init_hooks();
        $this->load_modules();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_head', array( $this, 'add_meta_tags' ) );
        add_action( 'wp_head', array( $this, 'add_schema_org' ) );
        add_action( 'body_class', array( $this, 'body_classes' ) );
        add_filter( 'wp_nav_menu_args', array( $this, 'nav_menu_args' ) );
        
        // AJAX actions
        add_action( 'wp_ajax_quick_view_product', array( $this, 'ajax_quick_view_product' ) );
        add_action( 'wp_ajax_nopriv_quick_view_product', array( $this, 'ajax_quick_view_product' ) );
        add_action( 'wp_ajax_get_cart_count', array( $this, 'ajax_get_cart_count' ) );
        add_action( 'wp_ajax_nopriv_get_cart_count', array( $this, 'ajax_get_cart_count' ) );
        add_action( 'wp_ajax_toggle_wishlist', array( $this, 'ajax_toggle_wishlist' ) );
        add_action( 'wp_ajax_nopriv_toggle_wishlist', array( $this, 'ajax_toggle_wishlist' ) );
    }

    /**
     * Load modules
     */
    private function load_modules() {
        $modules_dir = AQUALUXE_THEME_DIR . '/modules';
        $modules = array(
            'multilingual',
            'dark-mode',
            'demo-importer',
            'custom-post-types',
            'woocommerce-extensions',
            'seo-optimization',
            'performance-optimization'
        );

        foreach ( $modules as $module ) {
            $module_file = $modules_dir . '/' . $module . '/class-' . $module . '.php';
            if ( file_exists( $module_file ) ) {
                require_once $module_file;
                $class_name = 'AquaLuxe_' . str_replace( '-', '_', $module );
                if ( class_exists( $class_name ) ) {
                    $this->modules[ $module ] = new $class_name();
                }
            }
        }
    }

    /**
     * Enqueue theme scripts and styles
     */
    public function enqueue_scripts() {
        // Get the theme version
        $theme_version = wp_get_theme()->get( 'Version' );
        
        // Load compiled assets if they exist
        $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
        
        if ( file_exists( $manifest_path ) ) {
            $manifest = json_decode( file_get_contents( $manifest_path ), true );
            
            // Enqueue compiled CSS
            if ( isset( $manifest['/css/app.css'] ) ) {
                wp_enqueue_style( 
                    'aqualuxe-style', 
                    AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/css/app.css'], 
                    array(), 
                    $theme_version 
                );
            }
            
            // Enqueue compiled JS
            if ( isset( $manifest['/js/app.js'] ) ) {
                wp_enqueue_script( 
                    'aqualuxe-script', 
                    AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/js/app.js'], 
                    array(), 
                    $theme_version, 
                    true 
                );
            }
        }

        // Localize script for AJAX
        wp_localize_script( 'aqualuxe-script', 'aqualuxe', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'aqualuxe_nonce' ),
            'strings' => array(
                'loading' => esc_html__( 'Loading...', 'aqualuxe' ),
                'error'   => esc_html__( 'An error occurred', 'aqualuxe' ),
            ),
        ) );
    }

    /**
     * Add meta tags for SEO and social sharing
     */
    public function add_meta_tags() {
        if ( is_singular() ) {
            $post_id = get_the_ID();
            $description = get_the_excerpt( $post_id );
            $image = get_the_post_thumbnail_url( $post_id, 'large' );
            
            echo '<meta property="og:title" content="' . esc_attr( get_the_title() ) . '">' . "\n";
            echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
            echo '<meta property="og:type" content="article">' . "\n";
            echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '">' . "\n";
            
            if ( $image ) {
                echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
            }
            
            echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
            echo '<meta name="twitter:title" content="' . esc_attr( get_the_title() ) . '">' . "\n";
            echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
            
            if ( $image ) {
                echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
            }
        }
    }

    /**
     * Add Schema.org structured data
     */
    public function add_schema_org() {
        if ( is_singular() ) {
            $schema = array(
                '@context' => 'https://schema.org',
                '@type'    => 'Article',
                'headline' => get_the_title(),
                'author'   => array(
                    '@type' => 'Person',
                    'name'  => get_the_author(),
                ),
                'datePublished' => get_the_date( 'c' ),
                'dateModified'  => get_the_modified_date( 'c' ),
            );

            if ( has_post_thumbnail() ) {
                $schema['image'] = get_the_post_thumbnail_url( get_the_ID(), 'large' );
            }

            echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
        }
    }

    /**
     * Add custom body classes
     */
    public function body_classes( $classes ) {
        // Add dark mode class based on user preference
        if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) && $_COOKIE['aqualuxe_dark_mode'] === 'true' ) {
            $classes[] = 'dark';
        }

        // Add WooCommerce status class
        if ( class_exists( 'WooCommerce' ) ) {
            $classes[] = 'woocommerce-active';
        } else {
            $classes[] = 'woocommerce-inactive';
        }

        return $classes;
    }

    /**
     * Modify navigation menu arguments
     */
    public function nav_menu_args( $args ) {
        if ( 'primary' === $args['theme_location'] ) {
            $args['walker'] = new AquaLuxe_Walker_Nav_Menu();
        }
        return $args;
    }

    /**
     * AJAX handler for quick view product
     */
    public function ajax_quick_view_product() {
        check_ajax_referer( 'aqualuxe_nonce', 'nonce' );

        $product_id = absint( $_GET['product_id'] );
        
        if ( ! $product_id || ! class_exists( 'WooCommerce' ) ) {
            wp_send_json_error( esc_html__( 'Invalid product', 'aqualuxe' ) );
        }

        $product = wc_get_product( $product_id );
        
        if ( ! $product ) {
            wp_send_json_error( esc_html__( 'Product not found', 'aqualuxe' ) );
        }

        ob_start();
        wc_get_template( 'quick-view/product-quick-view.php', array( 'product' => $product ) );
        $html = ob_get_clean();

        wp_send_json_success( array( 'html' => $html ) );
    }

    /**
     * AJAX handler for cart count
     */
    public function ajax_get_cart_count() {
        check_ajax_referer( 'aqualuxe_nonce', 'nonce' );

        $count = 0;
        if ( class_exists( 'WooCommerce' ) && WC()->cart ) {
            $count = WC()->cart->get_cart_contents_count();
        }

        wp_send_json_success( array( 'count' => $count ) );
    }

    /**
     * AJAX handler for wishlist toggle
     */
    public function ajax_toggle_wishlist() {
        check_ajax_referer( 'aqualuxe_nonce', 'nonce' );

        $product_id = absint( $_GET['product_id'] );
        $user_id = get_current_user_id();

        if ( ! $product_id ) {
            wp_send_json_error( esc_html__( 'Invalid product', 'aqualuxe' ) );
        }

        if ( ! $user_id ) {
            wp_send_json_error( esc_html__( 'Please log in to use wishlist', 'aqualuxe' ) );
        }

        $wishlist = get_user_meta( $user_id, '_aqualuxe_wishlist', true );
        if ( ! is_array( $wishlist ) ) {
            $wishlist = array();
        }

        $key = array_search( $product_id, $wishlist );
        
        if ( false !== $key ) {
            // Remove from wishlist
            unset( $wishlist[ $key ] );
            $message = esc_html__( 'Removed from wishlist', 'aqualuxe' );
        } else {
            // Add to wishlist
            $wishlist[] = $product_id;
            $message = esc_html__( 'Added to wishlist', 'aqualuxe' );
        }

        update_user_meta( $user_id, '_aqualuxe_wishlist', $wishlist );

        wp_send_json_success( array( 'message' => $message ) );
    }

    /**
     * Get module instance
     */
    public function get_module( $module_name ) {
        return isset( $this->modules[ $module_name ] ) ? $this->modules[ $module_name ] : null;
    }
}