<?php
/**
 * Assets Management Class
 *
 * Handles enqueuing of scripts and styles using webpack mix manifest
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe Assets Class
 */
class AquaLuxe_Assets {

    /**
     * Mix manifest
     *
     * @var array
     */
    private $mix_manifest = null;

    /**
     * Constructor
     */
    public function __construct() {
        $this->load_mix_manifest();
    }

    /**
     * Load mix manifest file
     */
    private function load_mix_manifest() {
        $manifest_path = AQUALUXE_ASSETS_DIR . 'dist/mix-manifest.json';
        
        if ( file_exists( $manifest_path ) ) {
            $this->mix_manifest = json_decode( file_get_contents( $manifest_path ), true );
        }
    }

    /**
     * Get versioned asset path
     *
     * @param string $path
     * @return string
     */
    private function get_mix_asset( $path ) {
        if ( $this->mix_manifest && isset( $this->mix_manifest[ $path ] ) ) {
            return $this->mix_manifest[ $path ];
        }
        
        return $path;
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Main CSS
        wp_enqueue_style(
            'aqualuxe-main',
            AQUALUXE_ASSETS_URL . 'dist' . $this->get_mix_asset( '/css/main.css' ),
            array(),
            AQUALUXE_VERSION
        );

        // WooCommerce CSS (if WooCommerce is active)
        if ( class_exists( 'WooCommerce' ) ) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URL . 'dist' . $this->get_mix_asset( '/css/woocommerce.css' ),
                array( 'aqualuxe-main' ),
                AQUALUXE_VERSION
            );
        }

        // Main JavaScript
        wp_enqueue_script(
            'aqualuxe-main',
            AQUALUXE_ASSETS_URL . 'dist' . $this->get_mix_asset( '/js/main.js' ),
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        // Navigation JavaScript
        wp_enqueue_script(
            'aqualuxe-navigation',
            AQUALUXE_ASSETS_URL . 'dist' . $this->get_mix_asset( '/js/navigation.js' ),
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        // Dark mode JavaScript
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            AQUALUXE_ASSETS_URL . 'dist' . $this->get_mix_asset( '/js/dark-mode.js' ),
            array(),
            AQUALUXE_VERSION,
            true
        );

        // WooCommerce JavaScript (if WooCommerce is active)
        if ( class_exists( 'WooCommerce' ) ) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URL . 'dist' . $this->get_mix_asset( '/js/woocommerce.js' ),
                array( 'jquery', 'aqualuxe-main' ),
                AQUALUXE_VERSION,
                true
            );

            // Quick view modal
            wp_enqueue_script(
                'aqualuxe-quick-view',
                AQUALUXE_ASSETS_URL . 'dist' . $this->get_mix_asset( '/js/quick-view.js' ),
                array( 'jquery', 'aqualuxe-woocommerce' ),
                AQUALUXE_VERSION,
                true
            );

            // Wishlist functionality
            wp_enqueue_script(
                'aqualuxe-wishlist',
                AQUALUXE_ASSETS_URL . 'dist' . $this->get_mix_asset( '/js/wishlist.js' ),
                array( 'jquery', 'aqualuxe-main' ),
                AQUALUXE_VERSION,
                true
            );
        }

        // Slider/Carousel JavaScript
        wp_enqueue_script(
            'aqualuxe-slider',
            AQUALUXE_ASSETS_URL . 'dist' . $this->get_mix_asset( '/js/slider.js' ),
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        // Localize scripts with data
        wp_localize_script( 'aqualuxe-main', 'aqualuxe', array(
            'ajax_url'    => admin_url( 'admin-ajax.php' ),
            'nonce'       => wp_create_nonce( 'aqualuxe_nonce' ),
            'theme_url'   => AQUALUXE_THEME_URL,
            'assets_url'  => AQUALUXE_ASSETS_URL,
            'is_admin'    => is_admin(),
            'is_mobile'   => wp_is_mobile(),
            'strings'     => array(
                'loading'        => esc_html__( 'Loading...', 'aqualuxe' ),
                'error'          => esc_html__( 'An error occurred', 'aqualuxe' ),
                'try_again'      => esc_html__( 'Please try again', 'aqualuxe' ),
                'added_to_cart'  => esc_html__( 'Added to cart', 'aqualuxe' ),
                'view_cart'      => esc_html__( 'View Cart', 'aqualuxe' ),
                'continue_shopping' => esc_html__( 'Continue Shopping', 'aqualuxe' ),
            ),
        ) );

        // WooCommerce specific localization
        if ( class_exists( 'WooCommerce' ) ) {
            wp_localize_script( 'aqualuxe-woocommerce', 'aqualuxe_wc', array(
                'cart_url'        => wc_get_cart_url(),
                'checkout_url'    => wc_get_checkout_url(),
                'shop_url'        => wc_get_page_permalink( 'shop' ),
                'currency_symbol' => get_woocommerce_currency_symbol(),
                'price_decimals'  => wc_get_price_decimals(),
                'strings'         => array(
                    'select_options'     => esc_html__( 'Select options', 'aqualuxe' ),
                    'read_more'          => esc_html__( 'Read more', 'aqualuxe' ),
                    'quick_view'         => esc_html__( 'Quick View', 'aqualuxe' ),
                    'add_to_wishlist'    => esc_html__( 'Add to Wishlist', 'aqualuxe' ),
                    'remove_from_wishlist' => esc_html__( 'Remove from Wishlist', 'aqualuxe' ),
                    'compare'            => esc_html__( 'Compare', 'aqualuxe' ),
                ),
            ) );
        }

        // Add inline styles for custom properties
        $this->add_inline_styles();
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets() {
        // Admin CSS
        wp_enqueue_style(
            'aqualuxe-admin',
            AQUALUXE_ASSETS_URL . 'dist' . $this->get_mix_asset( '/css/admin.css' ),
            array(),
            AQUALUXE_VERSION
        );

        // Admin JavaScript
        wp_enqueue_script(
            'aqualuxe-admin',
            AQUALUXE_ASSETS_URL . 'dist' . $this->get_mix_asset( '/js/admin.js' ),
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        // Customizer JavaScript (only on customizer page)
        if ( is_customize_preview() ) {
            wp_enqueue_script(
                'aqualuxe-customizer',
                AQUALUXE_ASSETS_URL . 'dist' . $this->get_mix_asset( '/js/customizer.js' ),
                array( 'jquery', 'customize-preview' ),
                AQUALUXE_VERSION,
                true
            );
        }

        // Localize admin scripts
        wp_localize_script( 'aqualuxe-admin', 'aqualuxe_admin', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'aqualuxe_admin_nonce' ),
            'strings'  => array(
                'confirm_delete' => esc_html__( 'Are you sure you want to delete this?', 'aqualuxe' ),
                'saving'         => esc_html__( 'Saving...', 'aqualuxe' ),
                'saved'          => esc_html__( 'Saved!', 'aqualuxe' ),
                'error'          => esc_html__( 'Error occurred', 'aqualuxe' ),
            ),
        ) );
    }

    /**
     * Add inline styles for CSS custom properties
     */
    private function add_inline_styles() {
        $custom_css = '';

        // Get theme colors from customizer
        $primary_color = get_theme_mod( 'primary_color', '#14b8a6' );
        $secondary_color = get_theme_mod( 'secondary_color', '#0f766e' );
        $accent_color = get_theme_mod( 'accent_color', '#eec25a' );

        // Build CSS custom properties
        $custom_css .= ':root {';
        $custom_css .= '--color-primary: ' . esc_attr( $primary_color ) . ';';
        $custom_css .= '--color-secondary: ' . esc_attr( $secondary_color ) . ';';
        $custom_css .= '--color-accent: ' . esc_attr( $accent_color ) . ';';
        $custom_css .= '}';

        // Dark mode colors
        if ( get_theme_mod( 'dark_mode_enabled', false ) ) {
            $custom_css .= '.dark {';
            $custom_css .= '--color-primary: ' . esc_attr( get_theme_mod( 'dark_primary_color', '#14b8a6' ) ) . ';';
            $custom_css .= '--color-secondary: ' . esc_attr( get_theme_mod( 'dark_secondary_color', '#0f766e' ) ) . ';';
            $custom_css .= '--color-accent: ' . esc_attr( get_theme_mod( 'dark_accent_color', '#eec25a' ) ) . ';';
            $custom_css .= '}';
        }

        // Add the inline styles
        wp_add_inline_style( 'aqualuxe-main', $custom_css );
    }

    /**
     * Preload critical assets
     */
    public function preload_assets() {
        // Preload main CSS
        echo '<link rel="preload" href="' . esc_url( AQUALUXE_ASSETS_URL . 'dist' . $this->get_mix_asset( '/css/main.css' ) ) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        
        // Preload main JavaScript
        echo '<link rel="preload" href="' . esc_url( AQUALUXE_ASSETS_URL . 'dist' . $this->get_mix_asset( '/js/main.js' ) ) . '" as="script">' . "\n";
    }

    /**
     * Add resource hints
     */
    public function resource_hints() {
        // DNS prefetch for external domains
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
        
        // Preconnect for critical resources
        echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    }

    /**
     * Get asset URL with proper versioning
     *
     * @param string $path
     * @param string $type
     * @return string
     */
    public function get_asset_url( $path, $type = 'dist' ) {
        if ( $type === 'src' ) {
            return AQUALUXE_ASSETS_URL . 'src/' . ltrim( $path, '/' );
        }
        
        return AQUALUXE_ASSETS_URL . 'dist' . $this->get_mix_asset( '/' . ltrim( $path, '/' ) );
    }
}