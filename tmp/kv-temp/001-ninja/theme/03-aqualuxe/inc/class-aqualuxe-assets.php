<?php
/**
 * AquaLuxe Assets Management
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Assets Management Class
 */
class AquaLuxe_Assets {
    /**
     * Constructor
     */
    public function __construct() {
        // Frontend assets
        add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        
        // Admin assets
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
        
        // Editor assets
        add_action( 'enqueue_block_editor_assets', array( $this, 'editor_assets' ) );
        
        // Add async/defer attributes to scripts
        add_filter( 'script_loader_tag', array( $this, 'script_loader_tag' ), 10, 2 );
        
        // Add preload for critical assets
        add_action( 'wp_head', array( $this, 'preload_assets' ), 1 );
        
        // Add critical CSS
        add_action( 'wp_head', array( $this, 'critical_css' ), 2 );
    }

    /**
     * Register all stylesheets
     */
    public function register_styles() {
        // Normalize CSS
        wp_register_style(
            'normalize',
            AQUALUXE_URI . 'assets/css/normalize.css',
            array(),
            '8.0.1'
        );
        
        // Main stylesheet
        wp_register_style(
            'aqualuxe-main',
            AQUALUXE_URI . 'assets/css/main.css',
            array( 'normalize' ),
            AQUALUXE_VERSION
        );
        
        // Responsive styles
        wp_register_style(
            'aqualuxe-responsive',
            AQUALUXE_URI . 'assets/css/responsive.css',
            array( 'aqualuxe-main' ),
            AQUALUXE_VERSION
        );
        
        // WooCommerce styles
        if ( class_exists( 'WooCommerce' ) ) {
            wp_register_style(
                'aqualuxe-woocommerce',
                AQUALUXE_URI . 'assets/css/woocommerce.css',
                array( 'aqualuxe-main' ),
                AQUALUXE_VERSION
            );
        }
    }

    /**
     * Register all scripts
     */
    public function register_scripts() {
        // Register vendor scripts
        wp_register_script(
            'slick-slider',
            AQUALUXE_URI . 'assets/js/vendor/slick.min.js',
            array( 'jquery' ),
            '1.8.1',
            true
        );
        
        wp_register_script(
            'isotope',
            AQUALUXE_URI . 'assets/js/vendor/isotope.pkgd.min.js',
            array( 'jquery' ),
            '3.0.6',
            true
        );
        
        // Main JavaScript
        wp_register_script(
            'aqualuxe-main',
            AQUALUXE_URI . 'assets/js/main.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
        
        // WooCommerce scripts
        if ( class_exists( 'WooCommerce' ) ) {
            wp_register_script(
                'aqualuxe-woocommerce',
                AQUALUXE_URI . 'assets/js/woocommerce.js',
                array( 'jquery', 'aqualuxe-main' ),
                AQUALUXE_VERSION,
                true
            );
            
            // AJAX add to cart script
            wp_register_script(
                'aqualuxe-ajax-add-to-cart',
                AQUALUXE_URI . 'assets/js/ajax-add-to-cart.js',
                array( 'jquery', 'wc-add-to-cart' ),
                AQUALUXE_VERSION,
                true
            );
            
            // Quick view script
            wp_register_script(
                'aqualuxe-quick-view',
                AQUALUXE_URI . 'assets/js/quick-view.js',
                array( 'jquery', 'wc-add-to-cart-variation' ),
                AQUALUXE_VERSION,
                true
            );
            
            // Wishlist script
            wp_register_script(
                'aqualuxe-wishlist',
                AQUALUXE_URI . 'assets/js/wishlist.js',
                array( 'jquery' ),
                AQUALUXE_VERSION,
                true
            );
        }
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets() {
        // Enqueue Google Fonts
        wp_enqueue_style(
            'aqualuxe-fonts',
            'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap',
            array(),
            AQUALUXE_VERSION
        );
        
        // Enqueue main stylesheet and responsive styles
        wp_enqueue_style( 'aqualuxe-main' );
        wp_enqueue_style( 'aqualuxe-responsive' );
        
        // Enqueue WooCommerce styles if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
            wp_enqueue_style( 'aqualuxe-woocommerce' );
        }
        
        // Enqueue main script
        wp_enqueue_script( 'aqualuxe-main' );
        
        // Localize script with AJAX URL and nonce
        wp_localize_script(
            'aqualuxe-main',
            'aqualuxeVars',
            array(
                'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
                'nonce'         => wp_create_nonce( 'aqualuxe-nonce' ),
                'isRtl'         => is_rtl(),
                'themeUri'      => AQUALUXE_URI,
                'stickyHeader'  => get_theme_mod( 'aqualuxe_sticky_header', true ),
                'mobileBreakpoint' => 992,
            )
        );
        
        // Enqueue comment reply script
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
        
        // Enqueue WooCommerce scripts if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
                wp_enqueue_script( 'aqualuxe-woocommerce' );
            }
            
            // AJAX add to cart
            if ( get_theme_mod( 'aqualuxe_ajax_add_to_cart', true ) ) {
                wp_enqueue_script( 'aqualuxe-ajax-add-to-cart' );
                
                wp_localize_script(
                    'aqualuxe-ajax-add-to-cart',
                    'aqualuxeAjaxCart',
                    array(
                        'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
                        'nonce'         => wp_create_nonce( 'aqualuxe-add-to-cart-nonce' ),
                        'i18n_added'    => esc_html__( 'Added to cart!', 'aqualuxe' ),
                        'i18n_error'    => esc_html__( 'Error adding to cart. Please try again.', 'aqualuxe' ),
                        'cart_url'      => wc_get_cart_url(),
                        'cart_redirect' => get_theme_mod( 'aqualuxe_cart_redirect', false ),
                    )
                );
            }
            
            // Quick view
            if ( get_theme_mod( 'aqualuxe_quick_view', true ) ) {
                wp_enqueue_script( 'aqualuxe-quick-view' );
                
                wp_localize_script(
                    'aqualuxe-quick-view',
                    'aqualuxeQuickView',
                    array(
                        'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
                        'nonce'         => wp_create_nonce( 'aqualuxe-quick-view-nonce' ),
                        'i18n_loading'  => esc_html__( 'Loading...', 'aqualuxe' ),
                        'i18n_error'    => esc_html__( 'Error loading product. Please try again.', 'aqualuxe' ),
                    )
                );
            }
            
            // Wishlist
            if ( get_theme_mod( 'aqualuxe_wishlist', true ) ) {
                wp_enqueue_script( 'aqualuxe-wishlist' );
                
                wp_localize_script(
                    'aqualuxe-wishlist',
                    'aqualuxeWishlist',
                    array(
                        'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
                        'nonce'         => wp_create_nonce( 'aqualuxe-wishlist-nonce' ),
                        'i18n_add'      => esc_html__( 'Add to Wishlist', 'aqualuxe' ),
                        'i18n_added'    => esc_html__( 'Added to Wishlist', 'aqualuxe' ),
                        'i18n_remove'   => esc_html__( 'Remove from Wishlist', 'aqualuxe' ),
                        'i18n_exists'   => esc_html__( 'Already in Wishlist', 'aqualuxe' ),
                    )
                );
            }
        }
    }

    /**
     * Enqueue admin styles
     */
    public function admin_styles( $hook ) {
        // Admin styles
        wp_enqueue_style(
            'aqualuxe-admin',
            AQUALUXE_URI . 'assets/css/admin.css',
            array(),
            AQUALUXE_VERSION
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function admin_scripts( $hook ) {
        // Admin scripts
        wp_enqueue_script(
            'aqualuxe-admin',
            AQUALUXE_URI . 'assets/js/admin.js',
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Enqueue block editor assets
     */
    public function editor_assets() {
        // Editor styles
        wp_enqueue_style(
            'aqualuxe-editor-style',
            AQUALUXE_URI . 'assets/css/editor-style.css',
            array(),
            AQUALUXE_VERSION
        );
        
        // Editor script
        wp_enqueue_script(
            'aqualuxe-editor-script',
            AQUALUXE_URI . 'assets/js/editor.js',
            array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Add async/defer attributes to scripts
     *
     * @param string $tag The script tag.
     * @param string $handle The script handle.
     * @return string
     */
    public function script_loader_tag( $tag, $handle ) {
        // Add async attribute to non-critical scripts
        $scripts_to_async = array( 'slick-slider', 'isotope' );
        
        if ( in_array( $handle, $scripts_to_async, true ) ) {
            return str_replace( ' src', ' async src', $tag );
        }
        
        return $tag;
    }

    /**
     * Preload critical assets
     */
    public function preload_assets() {
        // Preload fonts
        echo '<link rel="preload" href="' . esc_url( AQUALUXE_URI . 'assets/fonts/fontawesome-webfont.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
        
        // Preload critical CSS
        echo '<link rel="preload" href="' . esc_url( AQUALUXE_URI . 'assets/css/critical.css' ) . '" as="style">' . "\n";
        
        // Preload logo
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        if ( $custom_logo_id ) {
            $logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
            if ( $logo ) {
                echo '<link rel="preload" href="' . esc_url( $logo[0] ) . '" as="image">' . "\n";
            }
        }
    }

    /**
     * Add critical CSS inline
     */
    public function critical_css() {
        $critical_css_path = AQUALUXE_DIR . 'assets/css/critical.css';
        
        if ( file_exists( $critical_css_path ) ) {
            $critical_css = file_get_contents( $critical_css_path );
            if ( $critical_css ) {
                echo '<style id="aqualuxe-critical-css">' . $critical_css . '</style>' . "\n";
            }
        }
    }
}

// Initialize the class
new AquaLuxe_Assets();