<?php
/**
 * Assets Class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

/**
 * Assets Class
 * 
 * This class handles all asset loading for the theme.
 */
class Assets {
    /**
     * Instance of this class
     *
     * @var Assets
     */
    private static $instance = null;

    /**
     * Mix manifest data
     *
     * @var array
     */
    private $mix_manifest = [];

    /**
     * Get the singleton instance
     *
     * @return Assets
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
        $this->load_mix_manifest();
        $this->init_hooks();
    }

    /**
     * Load mix manifest
     *
     * @return void
     */
    private function load_mix_manifest() {
        $manifest_path = AQUALUXE_ASSETS_DIR . 'mix-manifest.json';
        
        if ( file_exists( $manifest_path ) ) {
            $this->mix_manifest = json_decode( file_get_contents( $manifest_path ), true );
        }
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_assets' ] );
    }

    /**
     * Get asset URL with versioning from mix-manifest.json
     *
     * @param string $path Asset path.
     * @return string
     */
    public function get_asset_url( $path ) {
        $path = '/' . ltrim( $path, '/' );
        
        if ( isset( $this->mix_manifest[ $path ] ) ) {
            return AQUALUXE_ASSETS_URI . ltrim( $this->mix_manifest[ $path ], '/' );
        }
        
        return AQUALUXE_ASSETS_URI . ltrim( $path, '/' );
    }

    /**
     * Enqueue styles
     *
     * @return void
     */
    public function enqueue_styles() {
        // Enqueue main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            $this->get_asset_url( 'css/main.css' ),
            [],
            AQUALUXE_VERSION
        );

        // Enqueue dark mode stylesheet if enabled
        if ( $this->is_dark_mode_enabled() ) {
            wp_enqueue_style(
                'aqualuxe-dark-mode',
                $this->get_asset_url( 'css/dark-mode.css' ),
                [ 'aqualuxe-style' ],
                AQUALUXE_VERSION
            );
        }

        // Enqueue WooCommerce styles if active
        if ( class_exists( 'WooCommerce' ) ) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                $this->get_asset_url( 'css/woocommerce.css' ),
                [ 'aqualuxe-style' ],
                AQUALUXE_VERSION
            );
        }

        // Allow modules to enqueue their styles
        do_action( 'aqualuxe_enqueue_styles' );
    }

    /**
     * Enqueue scripts
     *
     * @return void
     */
    public function enqueue_scripts() {
        // Enqueue main script
        wp_enqueue_script(
            'aqualuxe-script',
            $this->get_asset_url( 'js/main.js' ),
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );

        // Localize script
        wp_localize_script(
            'aqualuxe-script',
            'aqualuxeData',
            [
                'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
                'nonce'      => wp_create_nonce( 'aqualuxe-nonce' ),
                'themeUri'   => AQUALUXE_URI,
                'assetsUri'  => AQUALUXE_ASSETS_URI,
                'darkMode'   => $this->is_dark_mode_enabled(),
                'i18n'       => [
                    'addToCart'     => esc_html__( 'Add to Cart', 'aqualuxe' ),
                    'added'         => esc_html__( 'Added!', 'aqualuxe' ),
                    'adding'        => esc_html__( 'Adding...', 'aqualuxe' ),
                    'viewCart'      => esc_html__( 'View Cart', 'aqualuxe' ),
                    'checkout'      => esc_html__( 'Checkout', 'aqualuxe' ),
                    'error'         => esc_html__( 'Error', 'aqualuxe' ),
                    'close'         => esc_html__( 'Close', 'aqualuxe' ),
                    'darkMode'      => esc_html__( 'Dark Mode', 'aqualuxe' ),
                    'lightMode'     => esc_html__( 'Light Mode', 'aqualuxe' ),
                ],
            ]
        );

        // Enqueue dark mode script if enabled
        if ( $this->is_dark_mode_enabled() ) {
            wp_enqueue_script(
                'aqualuxe-dark-mode',
                $this->get_asset_url( 'js/dark-mode.js' ),
                [ 'aqualuxe-script' ],
                AQUALUXE_VERSION,
                true
            );
        }

        // Enqueue WooCommerce scripts if active
        if ( class_exists( 'WooCommerce' ) ) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                $this->get_asset_url( 'js/woocommerce.js' ),
                [ 'aqualuxe-script' ],
                AQUALUXE_VERSION,
                true
            );

            // Localize WooCommerce script
            wp_localize_script(
                'aqualuxe-woocommerce',
                'aqualuxeWooData',
                [
                    'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
                    'nonce'      => wp_create_nonce( 'aqualuxe-woocommerce-nonce' ),
                    'cartUrl'    => wc_get_cart_url(),
                    'checkoutUrl' => wc_get_checkout_url(),
                    'i18n'       => [
                        'addToCart'     => esc_html__( 'Add to Cart', 'aqualuxe' ),
                        'added'         => esc_html__( 'Added!', 'aqualuxe' ),
                        'adding'        => esc_html__( 'Adding...', 'aqualuxe' ),
                        'viewCart'      => esc_html__( 'View Cart', 'aqualuxe' ),
                        'checkout'      => esc_html__( 'Checkout', 'aqualuxe' ),
                        'error'         => esc_html__( 'Error', 'aqualuxe' ),
                        'close'         => esc_html__( 'Close', 'aqualuxe' ),
                    ],
                ]
            );
        }

        // Allow modules to enqueue their scripts
        do_action( 'aqualuxe_enqueue_scripts' );

        // Add comment reply script if needed
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }

    /**
     * Enqueue admin assets
     *
     * @return void
     */
    public function enqueue_admin_assets() {
        // Enqueue admin styles
        wp_enqueue_style(
            'aqualuxe-admin-style',
            $this->get_asset_url( 'css/admin.css' ),
            [],
            AQUALUXE_VERSION
        );

        // Enqueue admin script
        wp_enqueue_script(
            'aqualuxe-admin-script',
            $this->get_asset_url( 'js/admin.js' ),
            [ 'jquery' ],
            AQUALUXE_VERSION,
            true
        );

        // Localize admin script
        wp_localize_script(
            'aqualuxe-admin-script',
            'aqualuxeAdminData',
            [
                'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
                'nonce'      => wp_create_nonce( 'aqualuxe-admin-nonce' ),
                'themeUri'   => AQUALUXE_URI,
                'assetsUri'  => AQUALUXE_ASSETS_URI,
            ]
        );

        // Allow modules to enqueue their admin assets
        do_action( 'aqualuxe_enqueue_admin_assets' );
    }

    /**
     * Enqueue editor assets
     *
     * @return void
     */
    public function enqueue_editor_assets() {
        // Enqueue editor styles
        wp_enqueue_style(
            'aqualuxe-editor-style',
            $this->get_asset_url( 'css/editor.css' ),
            [],
            AQUALUXE_VERSION
        );

        // Enqueue editor script
        wp_enqueue_script(
            'aqualuxe-editor-script',
            $this->get_asset_url( 'js/editor.js' ),
            [ 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ],
            AQUALUXE_VERSION,
            true
        );

        // Allow modules to enqueue their editor assets
        do_action( 'aqualuxe_enqueue_editor_assets' );
    }

    /**
     * Check if dark mode is enabled
     *
     * @return boolean
     */
    private function is_dark_mode_enabled() {
        return apply_filters( 'aqualuxe_dark_mode_enabled', true );
    }
}