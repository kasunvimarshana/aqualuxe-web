<?php
/**
 * Assets Management Class
 *
 * Handles theme asset loading and management
 *
 * @package AquaLuxe\Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Assets
 *
 * Manages theme assets including CSS, JS, and fonts
 *
 * @since 1.0.0
 */
class Assets {

    /**
     * Asset manifest data
     *
     * @var array
     */
    private $manifest = array();

    /**
     * Initialize the assets manager
     *
     * @since 1.0.0
     */
    public function init() {
        $this->load_manifest();
        
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
        add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_assets' ) );
        add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
    }

    /**
     * Load asset manifest
     *
     * @since 1.0.0
     */
    private function load_manifest() {
        $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
        
        if ( file_exists( $manifest_path ) ) {
            $this->manifest = json_decode( file_get_contents( $manifest_path ), true );
        }
    }

    /**
     * Get versioned asset URL
     *
     * @since 1.0.0
     * @param string $asset Asset path
     * @return string
     */
    private function get_asset_url( $asset ) {
        $versioned_asset = $this->manifest[ $asset ] ?? $asset;
        return AQUALUXE_ASSETS_URI . $versioned_asset;
    }

    /**
     * Enqueue frontend assets
     *
     * @since 1.0.0
     */
    public function enqueue_frontend_assets() {
        // Main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            $this->get_asset_url( '/css/app.css' ),
            array(),
            AQUALUXE_VERSION
        );

        // Main JavaScript
        wp_enqueue_script(
            'aqualuxe-script',
            $this->get_asset_url( '/js/app.js' ),
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        // Localize script
        wp_localize_script(
            'aqualuxe-script',
            'aqualuxe_vars',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'aqualuxe_nonce' ),
                'theme_url' => get_template_directory_uri(),
                'is_rtl'   => is_rtl(),
                'is_singular' => is_singular(),
                'is_home'  => is_home(),
                'strings'  => array(
                    'loading' => esc_html__( 'Loading...', 'aqualuxe' ),
                    'error'   => esc_html__( 'Something went wrong. Please try again.', 'aqualuxe' ),
                ),
            )
        );

        // Comments script
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
    }

    /**
     * Enqueue admin assets
     *
     * @since 1.0.0
     */
    public function enqueue_admin_assets() {
        // Admin stylesheet
        wp_enqueue_style(
            'aqualuxe-admin-style',
            $this->get_asset_url( '/css/admin.css' ),
            array(),
            AQUALUXE_VERSION
        );

        // Admin JavaScript
        wp_enqueue_script(
            'aqualuxe-admin-script',
            $this->get_asset_url( '/js/admin.js' ),
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        // Localize admin script
        wp_localize_script(
            'aqualuxe-admin-script',
            'aqualuxe_admin',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'aqualuxe_admin_nonce' ),
                'strings'  => array(
                    'importing' => esc_html__( 'Importing demo content...', 'aqualuxe' ),
                    'success'   => esc_html__( 'Demo content imported successfully!', 'aqualuxe' ),
                    'error'     => esc_html__( 'Import failed. Please try again.', 'aqualuxe' ),
                ),
            )
        );
    }

    /**
     * Enqueue customizer assets
     *
     * @since 1.0.0
     */
    public function enqueue_customizer_assets() {
        wp_enqueue_script(
            'aqualuxe-customizer',
            $this->get_asset_url( '/js/customizer.js' ),
            array( 'customize-preview' ),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Enqueue editor assets
     *
     * @since 1.0.0
     */
    public function enqueue_editor_assets() {
        wp_enqueue_style(
            'aqualuxe-editor-style',
            $this->get_asset_url( '/css/editor.css' ),
            array(),
            AQUALUXE_VERSION
        );
    }

    /**
     * Add resource hints
     *
     * @since 1.0.0
     * @param array  $urls URLs to print for resource hints
     * @param string $relation_type The relation type the URLs are printed for
     * @return array
     */
    public function resource_hints( $urls, $relation_type ) {
        if ( 'preconnect' === $relation_type ) {
            $urls[] = array(
                'href' => 'https://fonts.gstatic.com',
                'crossorigin',
            );
        }

        return $urls;
    }

    /**
     * Add critical CSS
     *
     * @since 1.0.0
     */
    public function critical_css() {
        $critical_css = get_theme_mod( 'aqualuxe_critical_css', '' );
        
        if ( ! empty( $critical_css ) ) {
            echo '<style id="aqualuxe-critical-css">' . wp_strip_all_tags( $critical_css ) . '</style>' . "\n";
        }
    }

    /**
     * Optimize scripts
     *
     * @since 1.0.0
     * @param string $tag    Script tag
     * @param string $handle Script handle
     * @param string $src    Script source URL
     * @return string
     */
    public function optimize_scripts( $tag, $handle, $src ) {
        // Add defer attribute to theme scripts
        if ( strpos( $handle, 'aqualuxe-' ) === 0 && $handle !== 'aqualuxe-admin-script' ) {
            $tag = str_replace( '<script ', '<script defer ', $tag );
        }

        return $tag;
    }
}