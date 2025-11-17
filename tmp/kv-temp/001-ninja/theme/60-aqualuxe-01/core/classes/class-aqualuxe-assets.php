<?php
/**
 * AquaLuxe Theme Assets
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Theme Assets Class
 */
class AquaLuxe_Assets {
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        add_action('enqueue_block_editor_assets', [$this, 'block_editor_assets']);
        add_filter('script_loader_tag', [$this, 'add_async_defer_attributes'], 10, 2);
    }

    /**
     * Get asset file
     *
     * @param string $filename Filename to get from the manifest
     * @return string Asset file path
     */
    private function get_asset_file($filename) {
        $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
        
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
            
            if (isset($manifest['/' . $filename])) {
                return $manifest['/' . $filename];
            }
        }
        
        return $filename;
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles() {
        // Enqueue main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            AQUALUXE_ASSETS_URI . 'css/' . $this->get_asset_file('css/main.css'),
            [],
            AQUALUXE_VERSION
        );
        
        // Enqueue WooCommerce styles if WooCommerce is active
        if (aqualuxe_is_woocommerce_active()) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . 'css/' . $this->get_asset_file('css/woocommerce.css'),
                ['aqualuxe-style'],
                AQUALUXE_VERSION
            );
        }
        
        // Enqueue module styles
        $this->enqueue_module_styles();
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        // Enqueue jQuery
        wp_enqueue_script('jquery');
        
        // Enqueue main script
        wp_enqueue_script(
            'aqualuxe-script',
            AQUALUXE_ASSETS_URI . 'js/' . $this->get_asset_file('js/main.js'),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('aqualuxe-script', 'aqualuxeData', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('aqualuxe-nonce'),
            'homeUrl' => home_url('/'),
            'isRtl'   => is_rtl(),
        ]);
        
        // Enqueue comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
        
        // Enqueue WooCommerce scripts if WooCommerce is active
        if (aqualuxe_is_woocommerce_active() && is_woocommerce()) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_ASSETS_URI . 'js/' . $this->get_asset_file('js/woocommerce.js'),
                ['jquery', 'aqualuxe-script'],
                AQUALUXE_VERSION,
                true
            );
        }
        
        // Enqueue module scripts
        $this->enqueue_module_scripts();
    }

    /**
     * Enqueue admin scripts
     */
    public function admin_enqueue_scripts() {
        wp_enqueue_style(
            'aqualuxe-admin-style',
            AQUALUXE_ASSETS_URI . 'css/' . $this->get_asset_file('css/admin.css'),
            [],
            AQUALUXE_VERSION
        );
        
        wp_enqueue_script(
            'aqualuxe-admin-script',
            AQUALUXE_ASSETS_URI . 'js/' . $this->get_asset_file('js/admin.js'),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Enqueue block editor assets
     */
    public function block_editor_assets() {
        wp_enqueue_style(
            'aqualuxe-editor-style',
            AQUALUXE_ASSETS_URI . 'css/' . $this->get_asset_file('css/editor-style.css'),
            [],
            AQUALUXE_VERSION
        );
        
        wp_enqueue_script(
            'aqualuxe-editor-script',
            AQUALUXE_ASSETS_URI . 'js/' . $this->get_asset_file('js/editor.js'),
            ['wp-blocks', 'wp-dom-ready', 'wp-edit-post'],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Enqueue module styles
     */
    private function enqueue_module_styles() {
        $modules = aqualuxe()->modules;
        $module_status = aqualuxe()->module_status;
        
        foreach ($modules as $module_id => $module) {
            if (isset($module_status[$module_id]) && $module_status[$module_id] === true) {
                $style_file = 'css/modules/' . $module_id . '.css';
                $style_path = AQUALUXE_DIR . 'assets/dist/' . $style_file;
                
                if (file_exists($style_path)) {
                    wp_enqueue_style(
                        'aqualuxe-' . $module_id,
                        AQUALUXE_ASSETS_URI . $this->get_asset_file($style_file),
                        ['aqualuxe-style'],
                        AQUALUXE_VERSION
                    );
                }
            }
        }
    }

    /**
     * Enqueue module scripts
     */
    private function enqueue_module_scripts() {
        $modules = aqualuxe()->modules;
        $module_status = aqualuxe()->module_status;
        
        foreach ($modules as $module_id => $module) {
            if (isset($module_status[$module_id]) && $module_status[$module_id] === true) {
                $script_file = 'js/modules/' . $module_id . '.js';
                $script_path = AQUALUXE_DIR . 'assets/dist/' . $script_file;
                
                if (file_exists($script_path)) {
                    wp_enqueue_script(
                        'aqualuxe-' . $module_id,
                        AQUALUXE_ASSETS_URI . $this->get_asset_file($script_file),
                        ['jquery', 'aqualuxe-script'],
                        AQUALUXE_VERSION,
                        true
                    );
                }
            }
        }
    }

    /**
     * Add async/defer attributes to scripts
     *
     * @param string $tag    Script tag
     * @param string $handle Script handle
     * @return string Modified script tag
     */
    public function add_async_defer_attributes($tag, $handle) {
        // Add async/defer attributes to specific scripts
        $async_scripts = [
            // Add script handles here
        ];
        
        $defer_scripts = [
            'aqualuxe-script',
            'aqualuxe-woocommerce',
        ];
        
        if (in_array($handle, $async_scripts, true)) {
            return str_replace(' src', ' async src', $tag);
        }
        
        if (in_array($handle, $defer_scripts, true)) {
            return str_replace(' src', ' defer src', $tag);
        }
        
        return $tag;
    }
}