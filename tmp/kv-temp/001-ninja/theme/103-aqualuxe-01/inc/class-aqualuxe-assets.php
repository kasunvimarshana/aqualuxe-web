<?php
/**
 * AquaLuxe Assets Management Class
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since   1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Assets Management Class
 *
 * @class AquaLuxe_Assets
 */
class AquaLuxe_Assets {

    /**
     * Single instance of the class
     *
     * @var AquaLuxe_Assets
     */
    protected static $_instance = null;

    /**
     * Mix manifest data
     *
     * @var array
     */
    private $manifest = array();

    /**
     * Main AquaLuxe_Assets Instance
     *
     * @static
     * @return AquaLuxe_Assets - Main instance
     */
    public static function get_instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->load_manifest();
        $this->init_hooks();
    }

    /**
     * Hook into actions and filters
     */
    private function init_hooks() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('customize_preview_init', array($this, 'enqueue_customizer_preview_assets'));
        add_action('customize_controls_enqueue_scripts', array($this, 'enqueue_customizer_controls_assets'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_editor_assets'));
        
        // Asset optimization
        add_filter('style_loader_tag', array($this, 'add_preload_for_css'), 10, 4);
        add_filter('script_loader_tag', array($this, 'add_async_defer_attributes'), 10, 3);
        add_action('wp_head', array($this, 'add_resource_hints'), 2);
        add_action('wp_head', array($this, 'add_critical_css'), 3);
        
        // Remove unnecessary assets
        add_action('wp_enqueue_scripts', array($this, 'dequeue_unnecessary_assets'), 100);
        add_action('wp_print_styles', array($this, 'dequeue_block_library_css'), 100);
    }

    /**
     * Load mix manifest for cache busting
     */
    private function load_manifest() {
        $manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
        
        if (file_exists($manifest_path)) {
            $manifest_content = file_get_contents($manifest_path);
            $this->manifest = json_decode($manifest_content, true);
        }
    }

    /**
     * Get asset URL with cache busting
     *
     * @param string $asset Asset path
     * @return string Asset URL
     */
    public function get_asset_url($asset) {
        // Remove leading slash if present
        $asset = ltrim($asset, '/');
        
        // Check if we have a versioned file in manifest
        $manifest_key = '/' . $asset;
        
        if (isset($this->manifest[$manifest_key])) {
            $versioned_asset = ltrim($this->manifest[$manifest_key], '/');
            return AQUALUXE_ASSETS_URI . $versioned_asset;
        }
        
        // Fallback to original asset with theme version
        return AQUALUXE_ASSETS_URI . $asset . '?v=' . AQUALUXE_VERSION;
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Main stylesheet
        wp_enqueue_style(
            'aqualuxe-main',
            $this->get_asset_url('css/app.css'),
            array(),
            null,
            'all'
        );

        // WooCommerce styles
        if (aqualuxe_is_woocommerce_active() && $this->is_woocommerce_page()) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                $this->get_asset_url('css/woocommerce.css'),
                array('aqualuxe-main'),
                null,
                'all'
            );
        }

        // Main JavaScript
        wp_enqueue_script(
            'aqualuxe-main',
            $this->get_asset_url('js/app.js'),
            array('jquery'),
            null,
            true
        );

        // Dark mode module
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            $this->get_asset_url('js/modules/dark-mode.js'),
            array('aqualuxe-main'),
            null,
            true
        );

        // WooCommerce JavaScript
        if (aqualuxe_is_woocommerce_active() && $this->is_woocommerce_page()) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                $this->get_asset_url('js/modules/woocommerce.js'),
                array('aqualuxe-main'),
                null,
                true
            );
        }

        // Localize main script
        wp_localize_script('aqualuxe-main', 'aqualuxe', array(
            'ajaxurl'      => admin_url('admin-ajax.php'),
            'nonce'        => wp_create_nonce('aqualuxe_nonce'),
            'theme_uri'    => AQUALUXE_THEME_URI,
            'assets_uri'   => AQUALUXE_ASSETS_URI,
            'is_rtl'       => is_rtl(),
            'language'     => aqualuxe_get_current_language(),
            'dark_mode'    => aqualuxe_is_dark_mode(),
            'woocommerce'  => aqualuxe_is_woocommerce_active(),
            'strings'      => array(
                'loading'       => esc_html__('Loading...', 'aqualuxe'),
                'error'         => esc_html__('An error occurred. Please try again.', 'aqualuxe'),
                'success'       => esc_html__('Success!', 'aqualuxe'),
                'confirm'       => esc_html__('Are you sure?', 'aqualuxe'),
                'close'         => esc_html__('Close', 'aqualuxe'),
                'prev'          => esc_html__('Previous', 'aqualuxe'),
                'next'          => esc_html__('Next', 'aqualuxe'),
            ),
        ));

        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }

        // Load additional scripts based on page type
        if (is_front_page()) {
            wp_enqueue_script(
                'aqualuxe-hero',
                $this->get_asset_url('js/components/hero.js'),
                array('aqualuxe-main'),
                null,
                true
            );
        }

        if (is_page_template('templates/contact.php')) {
            wp_enqueue_script(
                'aqualuxe-contact',
                $this->get_asset_url('js/components/contact.js'),
                array('aqualuxe-main'),
                null,
                true
            );
        }
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Admin stylesheet
        wp_enqueue_style(
            'aqualuxe-admin',
            $this->get_asset_url('css/admin.css'),
            array(),
            null,
            'all'
        );

        // Admin JavaScript
        wp_enqueue_script(
            'aqualuxe-admin',
            $this->get_asset_url('js/admin.js'),
            array('jquery'),
            null,
            true
        );

        // Demo importer on specific pages
        if (in_array($hook, array('appearance_page_aqualuxe-demo-importer', 'toplevel_page_aqualuxe-options'))) {
            wp_enqueue_script(
                'aqualuxe-demo-importer',
                $this->get_asset_url('js/modules/demo-importer.js'),
                array('jquery'),
                null,
                true
            );
        }

        // Localize admin script
        wp_localize_script('aqualuxe-admin', 'aqualuxe_admin', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('aqualuxe_admin_nonce'),
            'strings' => array(
                'saving'        => esc_html__('Saving...', 'aqualuxe'),
                'saved'         => esc_html__('Saved!', 'aqualuxe'),
                'error'         => esc_html__('Error occurred', 'aqualuxe'),
                'confirm_reset' => esc_html__('Are you sure you want to reset all settings?', 'aqualuxe'),
            ),
        ));
    }

    /**
     * Enqueue customizer preview assets
     */
    public function enqueue_customizer_preview_assets() {
        wp_enqueue_script(
            'aqualuxe-customizer-preview',
            $this->get_asset_url('js/customizer-preview.js'),
            array('customize-preview'),
            null,
            true
        );
    }

    /**
     * Enqueue customizer controls assets
     */
    public function enqueue_customizer_controls_assets() {
        wp_enqueue_style(
            'aqualuxe-customizer-controls',
            $this->get_asset_url('css/customizer-controls.css'),
            array(),
            null,
            'all'
        );

        wp_enqueue_script(
            'aqualuxe-customizer-controls',
            $this->get_asset_url('js/customizer-controls.js'),
            array('customize-controls'),
            null,
            true
        );
    }

    /**
     * Enqueue block editor assets
     */
    public function enqueue_editor_assets() {
        wp_enqueue_style(
            'aqualuxe-editor',
            $this->get_asset_url('css/editor.css'),
            array(),
            null,
            'all'
        );

        wp_enqueue_script(
            'aqualuxe-editor',
            $this->get_asset_url('js/editor.js'),
            array('wp-blocks', 'wp-i18n', 'wp-element'),
            null,
            true
        );
    }

    /**
     * Add preload for critical CSS
     */
    public function add_preload_for_css($html, $handle, $href, $media) {
        if ($handle === 'aqualuxe-main') {
            $html = '<link rel="preload" href="' . esc_url($href) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n" . $html;
        }
        
        return $html;
    }

    /**
     * Add async/defer attributes to scripts
     */
    public function add_async_defer_attributes($tag, $handle, $src) {
        // Scripts to load asynchronously
        $async_scripts = array(
            'aqualuxe-dark-mode',
            'aqualuxe-hero',
        );

        // Scripts to defer
        $defer_scripts = array(
            'aqualuxe-woocommerce',
            'aqualuxe-contact',
        );

        if (in_array($handle, $async_scripts)) {
            return str_replace(' src', ' async src', $tag);
        }

        if (in_array($handle, $defer_scripts)) {
            return str_replace(' src', ' defer src', $tag);
        }

        return $tag;
    }

    /**
     * Add resource hints
     */
    public function add_resource_hints() {
        // Preconnect to external domains
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        
        // DNS prefetch
        echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
        echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
        
        // Preload critical fonts
        echo '<link rel="preload" href="' . AQUALUXE_ASSETS_URI . 'fonts/inter-v12-latin-regular.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
        echo '<link rel="preload" href="' . AQUALUXE_ASSETS_URI . 'fonts/playfair-display-v22-latin-regular.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
    }

    /**
     * Add critical CSS inline
     */
    public function add_critical_css() {
        $critical_css_path = AQUALUXE_THEME_DIR . '/assets/dist/css/critical.css';
        
        if (file_exists($critical_css_path)) {
            $critical_css = file_get_contents($critical_css_path);
            
            if ($critical_css) {
                echo '<style id="aqualuxe-critical-css">' . "\n";
                echo $critical_css;
                echo "\n" . '</style>' . "\n";
            }
        }
    }

    /**
     * Remove unnecessary assets
     */
    public function dequeue_unnecessary_assets() {
        // Remove WordPress default styles that we don't need
        if (!is_admin()) {
            wp_dequeue_style('wp-block-library-theme');
            wp_dequeue_style('classic-theme-styles');
            wp_dequeue_style('global-styles');
        }

        // Remove jQuery Migrate if not needed
        if (!is_admin()) {
            wp_deregister_script('jquery');
            wp_register_script('jquery', includes_url('/js/jquery/jquery.min.js'), false, null, true);
            wp_enqueue_script('jquery');
        }

        // Remove emoji scripts if disabled
        if (!get_theme_mod('aqualuxe_enable_emojis', false)) {
            remove_action('wp_head', 'print_emoji_detection_script', 7);
            remove_action('wp_print_styles', 'print_emoji_styles');
            remove_action('admin_print_scripts', 'print_emoji_detection_script');
            remove_action('admin_print_styles', 'print_emoji_styles');
        }
    }

    /**
     * Conditionally remove block library CSS
     */
    public function dequeue_block_library_css() {
        if (!is_admin() && !has_blocks()) {
            wp_dequeue_style('wp-block-library');
        }
    }

    /**
     * Check if current page is WooCommerce related
     */
    private function is_woocommerce_page() {
        if (!aqualuxe_is_woocommerce_active()) {
            return false;
        }

        return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
    }

    /**
     * Get inline CSS for customizer settings
     */
    public function get_customizer_css() {
        $css = '';
        
        // Primary color
        $primary_color = get_theme_mod('aqualuxe_primary_color', '#06b6d4');
        if ($primary_color !== '#06b6d4') {
            $css .= ':root { --color-aqua-primary: ' . esc_attr($primary_color) . '; }';
        }
        
        // Secondary color
        $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#ffd700');
        if ($secondary_color !== '#ffd700') {
            $css .= ':root { --color-luxury-gold: ' . esc_attr($secondary_color) . '; }';
        }
        
        // Typography
        $heading_font = get_theme_mod('aqualuxe_heading_font', 'Playfair Display');
        if ($heading_font !== 'Playfair Display') {
            $css .= ':root { --font-heading: "' . esc_attr($heading_font) . '", Georgia, serif; }';
        }
        
        $body_font = get_theme_mod('aqualuxe_body_font', 'Inter');
        if ($body_font !== 'Inter') {
            $css .= ':root { --font-primary: "' . esc_attr($body_font) . '", system-ui, sans-serif; }';
        }
        
        return $css;
    }

    /**
     * Output customizer CSS
     */
    public function output_customizer_css() {
        $css = $this->get_customizer_css();
        
        if (!empty($css)) {
            echo '<style id="aqualuxe-customizer-css">' . $css . '</style>' . "\n";
        }
    }
}