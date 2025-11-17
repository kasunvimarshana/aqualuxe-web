<?php
/**
 * AquaLuxe Assets Class
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe Assets Class
 */
class AquaLuxe_Assets {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_Assets
     */
    private static $instance;

    /**
     * Main assets instance
     *
     * @return AquaLuxe_Assets
     */
    public static function instance() {
        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof AquaLuxe_Assets ) ) {
            self::$instance = new AquaLuxe_Assets();
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
        // Register and enqueue styles and scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        
        // Register and enqueue admin styles and scripts
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        
        // Add preload links
        add_action( 'wp_head', array( $this, 'preload_assets' ), 1 );
        
        // Add async and defer attributes to scripts
        add_filter( 'script_loader_tag', array( $this, 'add_script_attributes' ), 10, 3 );
    }

    /**
     * Get asset path
     *
     * @param string $path Asset path
     * @return string
     */
    public function get_asset_path( $path ) {
        return AQUALUXE_ASSETS_URI . $path;
    }

    /**
     * Get asset version
     *
     * @param string $path Asset path
     * @return string
     */
    public function get_asset_version( $path ) {
        static $manifest = null;
        
        if ( null === $manifest ) {
            $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
            
            if ( file_exists( $manifest_path ) ) {
                $manifest = json_decode( file_get_contents( $manifest_path ), true );
            } else {
                $manifest = array();
            }
        }
        
        $path = '/' . ltrim( $path, '/' );
        
        if ( isset( $manifest[ $path ] ) ) {
            return str_replace( $path . '?id=', '', $manifest[ $path ] );
        }
        
        return AQUALUXE_VERSION;
    }

    /**
     * Register styles
     *
     * @return void
     */
    public function register_styles() {
        // Register main stylesheet
        wp_register_style(
            'aqualuxe-style',
            $this->get_asset_path( 'css/main.css' ),
            array(),
            $this->get_asset_version( 'css/main.css' )
        );
        
        // Register WooCommerce styles if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            wp_register_style(
                'aqualuxe-woocommerce',
                $this->get_asset_path( 'css/woocommerce.css' ),
                array( 'aqualuxe-style' ),
                $this->get_asset_version( 'css/woocommerce.css' )
            );
        }
    }

    /**
     * Register scripts
     *
     * @return void
     */
    public function register_scripts() {
        // Register main script
        wp_register_script(
            'aqualuxe-script',
            $this->get_asset_path( 'js/main.js' ),
            array( 'jquery' ),
            $this->get_asset_version( 'js/main.js' ),
            true
        );
        
        // Register WooCommerce scripts if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) ) {
            wp_register_script(
                'aqualuxe-woocommerce',
                $this->get_asset_path( 'js/woocommerce.js' ),
                array( 'jquery', 'aqualuxe-script' ),
                $this->get_asset_version( 'js/woocommerce.js' ),
                true
            );
        }
        
        // Register comment reply script
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }

    /**
     * Enqueue styles
     *
     * @return void
     */
    public function enqueue_styles() {
        // Enqueue main stylesheet
        wp_enqueue_style( 'aqualuxe-style' );
        
        // Enqueue WooCommerce styles if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
            wp_enqueue_style( 'aqualuxe-woocommerce' );
        }
    }

    /**
     * Enqueue scripts
     *
     * @return void
     */
    public function enqueue_scripts() {
        // Enqueue main script
        wp_enqueue_script( 'aqualuxe-script' );
        
        // Localize script
        wp_localize_script(
            'aqualuxe-script',
            'aqualuxeData',
            array(
                'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
                'ajaxNonce'  => wp_create_nonce( 'aqualuxe-ajax-nonce' ),
                'siteUrl'    => site_url(),
                'themeUrl'   => AQUALUXE_URI,
                'assetsUrl'  => AQUALUXE_ASSETS_URI,
                'isRtl'      => is_rtl(),
                'isLoggedIn' => is_user_logged_in(),
                'darkMode'   => aqualuxe_is_dark_mode(),
            )
        );
        
        // Enqueue WooCommerce scripts if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
            wp_enqueue_script( 'aqualuxe-woocommerce' );
            
            // Localize WooCommerce script
            wp_localize_script(
                'aqualuxe-woocommerce',
                'aqualuxeWooData',
                array(
                    'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
                    'ajaxNonce'     => wp_create_nonce( 'aqualuxe-woo-ajax-nonce' ),
                    'isCart'        => is_cart(),
                    'isCheckout'    => is_checkout(),
                    'isAccount'     => is_account_page(),
                    'isProduct'     => is_product(),
                    'isShop'        => is_shop(),
                    'cartUrl'       => wc_get_cart_url(),
                    'checkoutUrl'   => wc_get_checkout_url(),
                    'accountUrl'    => wc_get_account_endpoint_url( 'dashboard' ),
                    'shopUrl'       => get_permalink( wc_get_page_id( 'shop' ) ),
                    'currency'      => get_woocommerce_currency_symbol(),
                    'currencyPos'   => get_option( 'woocommerce_currency_pos' ),
                    'priceDecimal'  => wc_get_price_decimal_separator(),
                    'priceThousand' => wc_get_price_thousand_separator(),
                    'priceDecimals' => wc_get_price_decimals(),
                )
            );
        }
    }

    /**
     * Register admin styles
     *
     * @return void
     */
    public function register_admin_styles() {
        // Register admin stylesheet
        wp_register_style(
            'aqualuxe-admin-style',
            $this->get_asset_path( 'css/admin.css' ),
            array(),
            $this->get_asset_version( 'css/admin.css' )
        );
    }

    /**
     * Register admin scripts
     *
     * @return void
     */
    public function register_admin_scripts() {
        // Register admin script
        wp_register_script(
            'aqualuxe-admin-script',
            $this->get_asset_path( 'js/admin.js' ),
            array( 'jquery' ),
            $this->get_asset_version( 'js/admin.js' ),
            true
        );
    }

    /**
     * Enqueue admin styles
     *
     * @return void
     */
    public function enqueue_admin_styles() {
        // Enqueue admin stylesheet
        wp_enqueue_style( 'aqualuxe-admin-style' );
    }

    /**
     * Enqueue admin scripts
     *
     * @return void
     */
    public function enqueue_admin_scripts() {
        // Enqueue admin script
        wp_enqueue_script( 'aqualuxe-admin-script' );
        
        // Localize admin script
        wp_localize_script(
            'aqualuxe-admin-script',
            'aqualuxeAdminData',
            array(
                'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
                'ajaxNonce' => wp_create_nonce( 'aqualuxe-admin-ajax-nonce' ),
                'themeUrl'  => AQUALUXE_URI,
                'assetsUrl' => AQUALUXE_ASSETS_URI,
            )
        );
    }

    /**
     * Preload assets
     *
     * @return void
     */
    public function preload_assets() {
        // Preload main stylesheet
        echo '<link rel="preload" href="' . esc_url( $this->get_asset_path( 'css/main.css' ) ) . '" as="style">';
        
        // Preload main script
        echo '<link rel="preload" href="' . esc_url( $this->get_asset_path( 'js/main.js' ) ) . '" as="script">';
        
        // Preload WooCommerce assets if WooCommerce is active
        if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
            echo '<link rel="preload" href="' . esc_url( $this->get_asset_path( 'css/woocommerce.css' ) ) . '" as="style">';
            echo '<link rel="preload" href="' . esc_url( $this->get_asset_path( 'js/woocommerce.js' ) ) . '" as="script">';
        }
    }

    /**
     * Add async and defer attributes to scripts
     *
     * @param string $tag    Script tag
     * @param string $handle Script handle
     * @param string $src    Script source
     * @return string
     */
    public function add_script_attributes( $tag, $handle, $src ) {
        // Add async attribute to scripts
        $async_scripts = array(
            'aqualuxe-script',
        );
        
        if ( in_array( $handle, $async_scripts, true ) ) {
            return str_replace( ' src', ' async src', $tag );
        }
        
        // Add defer attribute to scripts
        $defer_scripts = array(
            'aqualuxe-woocommerce',
        );
        
        if ( in_array( $handle, $defer_scripts, true ) ) {
            return str_replace( ' src', ' defer src', $tag );
        }
        
        return $tag;
    }
}