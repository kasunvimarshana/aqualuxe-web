<?php
/**
 * Asset Manager Service
 * 
 * Handles asset enqueueing, optimization, and cache busting
 * following SOLID principles and WordPress best practices.
 *
 * @package AquaLuxe
 * @subpackage Services
 * @since 1.0.0
 * @author AquaLuxe Development Team
 */

namespace AquaLuxe\Services;

use AquaLuxe\Core\Base_Service;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Asset Manager Service Class
 *
 * Responsible for:
 * - Asset enqueueing with proper dependency management
 * - Cache busting using mix-manifest.json
 * - Conditional asset loading based on context
 * - Asset optimization and compression
 * - Critical CSS inlining
 *
 * @since 1.0.0
 */
class Asset_Manager extends Base_Service {

    /**
     * Mix manifest data
     *
     * @var array
     */
    private $manifest = array();

    /**
     * Asset base path
     *
     * @var string
     */
    private $asset_path;

    /**
     * Asset base URL
     *
     * @var string
     */
    private $asset_url;

    /**
     * Initialize the service.
     *
     * @return void
     */
    public function init(): void {
        $this->asset_path = AQUALUXE_ASSETS_DIR . '/dist';
        $this->asset_url = AQUALUXE_ASSETS_URI . '/dist';
        
        $this->load_manifest();
        $this->setup_hooks();
    }

    /**
     * Setup WordPress hooks.
     *
     * @return void
     */
    private function setup_hooks(): void {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ), 10 );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ), 10 );
        add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_assets' ), 10 );
        add_action( 'wp_head', array( $this, 'add_critical_css' ), 1 );
        add_action( 'wp_head', array( $this, 'add_preload_hints' ), 2 );
        
        // Optimize asset loading
        add_filter( 'script_loader_tag', array( $this, 'add_async_defer_attributes' ), 10, 3 );
        add_filter( 'style_loader_tag', array( $this, 'add_preload_for_styles' ), 10, 4 );
    }

    /**
     * Load mix manifest for cache busting.
     *
     * @return void
     */
    private function load_manifest(): void {
        $manifest_file = $this->asset_path . '/mix-manifest.json';
        
        if ( file_exists( $manifest_file ) ) {
            $this->manifest = json_decode( file_get_contents( $manifest_file ), true );
        }
    }

    /**
     * Get versioned asset URL.
     *
     * @param string $asset Asset path relative to dist folder.
     * @return string Versioned asset URL.
     */
    public function get_asset_url( string $asset ): string {
        // Ensure asset starts with /
        if ( substr( $asset, 0, 1 ) !== '/' ) {
            $asset = '/' . $asset;
        }

        // Get versioned path from manifest
        $versioned_path = isset( $this->manifest[ $asset ] ) ? $this->manifest[ $asset ] : $asset;
        
        return $this->asset_url . $versioned_path;
    }

    /**
     * Enqueue frontend assets.
     *
     * @return void
     */
    public function enqueue_frontend_assets(): void {
        // Main stylesheet
        wp_enqueue_style(
            'aqualuxe-main',
            $this->get_asset_url( '/css/main.css' ),
            array(),
            AQUALUXE_VERSION,
            'all'
        );

        // Main JavaScript
        wp_enqueue_script(
            'aqualuxe-main',
            $this->get_asset_url( '/js/main.js' ),
            array( 'jquery' ),
            AQUALUXE_VERSION,
            true
        );

        // Localize script with data
        wp_localize_script( 'aqualuxe-main', 'aqualuxe', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'aqualuxe_nonce' ),
            'theme_url' => AQUALUXE_THEME_URI,
            'is_customizer' => is_customize_preview(),
            'is_rtl' => is_rtl(),
            'locale' => get_locale(),
            'debug' => defined( 'WP_DEBUG' ) && WP_DEBUG,
        ) );

        // Conditional assets
        $this->enqueue_conditional_assets();
    }

    /**
     * Enqueue conditional assets based on context.
     *
     * @return void
     */
    private function enqueue_conditional_assets(): void {
        // WooCommerce assets
        if ( $this->is_woocommerce_context() ) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                $this->get_asset_url( '/css/woocommerce.css' ),
                array( 'aqualuxe-main' ),
                AQUALUXE_VERSION
            );

            wp_enqueue_script(
                'aqualuxe-woocommerce',
                $this->get_asset_url( '/js/woocommerce.js' ),
                array( 'aqualuxe-main', 'jquery' ),
                AQUALUXE_VERSION,
                true
            );
        }

        // Contact form assets (if Contact Form 7 is active)
        if ( function_exists( 'wpcf7_enqueue_scripts' ) && is_page_template( 'templates/contact.php' ) ) {
            wp_enqueue_style( 'contact-form-7' );
            wp_enqueue_script( 'contact-form-7' );
        }

        // Archive-specific assets
        if ( is_archive() || is_home() ) {
            wp_enqueue_script(
                'aqualuxe-archive',
                $this->get_asset_url( '/js/archive.js' ),
                array( 'aqualuxe-main' ),
                AQUALUXE_VERSION,
                true
            );
        }
    }

    /**
     * Enqueue admin assets.
     *
     * @param string $hook Current admin page hook.
     * @return void
     */
    public function enqueue_admin_assets( string $hook ): void {
        // Only load on theme-related admin pages
        if ( ! $this->is_theme_admin_page( $hook ) ) {
            return;
        }

        wp_enqueue_style(
            'aqualuxe-admin',
            $this->get_asset_url( '/css/admin.css' ),
            array(),
            AQUALUXE_VERSION
        );

        wp_enqueue_script(
            'aqualuxe-admin',
            $this->get_asset_url( '/js/admin.js' ),
            array( 'jquery', 'wp-color-picker' ),
            AQUALUXE_VERSION,
            true
        );

        // Localize admin script
        wp_localize_script( 'aqualuxe-admin', 'aqualuxe_admin', array(
            'nonce' => wp_create_nonce( 'aqualuxe_admin_nonce' ),
            'strings' => array(
                'confirm_reset' => esc_html__( 'Are you sure you want to reset all settings?', 'aqualuxe' ),
                'saving' => esc_html__( 'Saving...', 'aqualuxe' ),
                'saved' => esc_html__( 'Saved!', 'aqualuxe' ),
            ),
        ) );
    }

    /**
     * Enqueue customizer assets.
     *
     * @return void
     */
    public function enqueue_customizer_assets(): void {
        wp_enqueue_script(
            'aqualuxe-customizer',
            $this->get_asset_url( '/js/customizer.js' ),
            array( 'jquery', 'customize-preview' ),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Add critical CSS inline.
     *
     * @return void
     */
    public function add_critical_css(): void {
        $critical_css = $this->get_critical_css();
        
        if ( $critical_css ) {
            echo '<style id="aqualuxe-critical-css">' . $critical_css . '</style>' . "\n";
        }
    }

    /**
     * Add preload hints for performance.
     *
     * @return void
     */
    public function add_preload_hints(): void {
        // Preload main CSS
        echo '<link rel="preload" href="' . esc_url( $this->get_asset_url( '/css/main.css' ) ) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        
        // Preload main JS
        echo '<link rel="preload" href="' . esc_url( $this->get_asset_url( '/js/main.js' ) ) . '" as="script">' . "\n";
        
        // Preload fonts
        $this->preload_fonts();
    }

    /**
     * Add async/defer attributes to scripts.
     *
     * @param string $tag The script tag.
     * @param string $handle The script handle.
     * @param string $src The script source URL.
     * @return string Modified script tag.
     */
    public function add_async_defer_attributes( string $tag, string $handle, string $src ): string {
        // Scripts that should be loaded asynchronously
        $async_scripts = array( 'aqualuxe-archive', 'aqualuxe-woocommerce' );
        
        // Scripts that should be deferred
        $defer_scripts = array( 'aqualuxe-main' );

        if ( in_array( $handle, $async_scripts, true ) ) {
            $tag = str_replace( ' src', ' async src', $tag );
        } elseif ( in_array( $handle, $defer_scripts, true ) ) {
            $tag = str_replace( ' src', ' defer src', $tag );
        }

        return $tag;
    }

    /**
     * Add preload for stylesheets.
     *
     * @param string $html The link tag for the enqueued style.
     * @param string $handle The style's registered handle.
     * @param string $href The stylesheet's source URL.
     * @param string $media The stylesheet's media attribute.
     * @return string Modified link tag.
     */
    public function add_preload_for_styles( string $html, string $handle, string $href, string $media ): string {
        // Only preload non-critical stylesheets
        $preload_styles = array( 'aqualuxe-woocommerce' );
        
        if ( in_array( $handle, $preload_styles, true ) ) {
            $html = '<link rel="preload" href="' . $href . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
        }

        return $html;
    }

    /**
     * Get critical CSS for above-the-fold content.
     *
     * @return string Critical CSS content.
     */
    private function get_critical_css(): string {
        $critical_css_file = $this->asset_path . '/css/critical.css';
        
        if ( file_exists( $critical_css_file ) ) {
            return file_get_contents( $critical_css_file );
        }

        // Return basic critical CSS if file doesn't exist
        return '
            html{line-height:1.15;-webkit-text-size-adjust:100%}
            body{margin:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif}
            .sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
        ';
    }

    /**
     * Preload web fonts.
     *
     * @return void
     */
    private function preload_fonts(): void {
        $fonts = array(
            'fonts/inter-var.woff2',
            'fonts/playfair-display-var.woff2',
        );

        foreach ( $fonts as $font ) {
            $font_url = $this->get_asset_url( '/' . $font );
            if ( $this->asset_exists( $font ) ) {
                echo '<link rel="preload" href="' . esc_url( $font_url ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
            }
        }
    }

    /**
     * Check if an asset exists.
     *
     * @param string $asset Asset path relative to dist folder.
     * @return bool True if asset exists.
     */
    private function asset_exists( string $asset ): bool {
        return file_exists( $this->asset_path . '/' . ltrim( $asset, '/' ) );
    }

    /**
     * Check if current context is WooCommerce related.
     *
     * @return bool True if WooCommerce context.
     */
    private function is_woocommerce_context(): bool {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return false;
        }

        return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
    }

    /**
     * Check if current admin page is theme-related.
     *
     * @param string $hook Current admin page hook.
     * @return bool True if theme-related admin page.
     */
    private function is_theme_admin_page( string $hook ): bool {
        $theme_pages = array(
            'themes.php',
            'customize.php',
            'nav-menus.php',
            'widgets.php',
        );

        return in_array( $hook, $theme_pages, true ) || strpos( $hook, 'aqualuxe' ) !== false;
    }

    /**
     * Get service name for dependency injection.
     *
     * @return string Service name.
     */
    public function get_service_name(): string {
        return 'asset_manager';
    }
}