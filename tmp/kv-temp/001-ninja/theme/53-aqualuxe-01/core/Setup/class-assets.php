<?php
/**
 * Assets setup
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core\Setup;

/**
 * Assets setup class
 */
class Assets {
    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        add_action('enqueue_block_editor_assets', [$this, 'block_editor_assets']);
        add_filter('script_loader_tag', [$this, 'script_loader_tag'], 10, 3);
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
            aqualuxe_asset('css/style.css'),
            [],
            AQUALUXE_VERSION
        );

        // Add inline styles for custom properties from Customizer
        wp_add_inline_style('aqualuxe-style', $this->get_custom_properties_css());
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
            aqualuxe_asset('js/app.js'),
            [],
            AQUALUXE_VERSION,
            true
        );

        // Localize script
        wp_localize_script(
            'aqualuxe-script',
            'aqualuxeData',
            [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe-nonce'),
                'homeUrl' => home_url('/'),
                'isLoggedIn' => is_user_logged_in(),
                'i18n' => [
                    'searchPlaceholder' => esc_html__('Search...', 'aqualuxe'),
                    'menuToggle' => esc_html__('Toggle Menu', 'aqualuxe'),
                    'closeMenu' => esc_html__('Close Menu', 'aqualuxe'),
                    'darkModeToggle' => esc_html__('Toggle Dark Mode', 'aqualuxe'),
                    'darkModeOn' => esc_html__('Dark Mode On', 'aqualuxe'),
                    'darkModeOff' => esc_html__('Dark Mode Off', 'aqualuxe'),
                ],
            ]
        );

        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Enqueue admin scripts
     *
     * @return void
     */
    public function admin_enqueue_scripts() {
        // Enqueue admin stylesheet
        wp_enqueue_style(
            'aqualuxe-admin-style',
            aqualuxe_asset('css/admin.css'),
            [],
            AQUALUXE_VERSION
        );

        // Enqueue admin script
        wp_enqueue_script(
            'aqualuxe-admin-script',
            aqualuxe_asset('js/admin.js'),
            ['jquery'],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Enqueue block editor assets
     *
     * @return void
     */
    public function block_editor_assets() {
        // Enqueue editor script
        wp_enqueue_script(
            'aqualuxe-editor-script',
            aqualuxe_asset('js/editor.js'),
            ['wp-blocks', 'wp-dom-ready', 'wp-edit-post'],
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Add attributes to script tags
     *
     * @param string $tag The script tag.
     * @param string $handle The script handle.
     * @param string $src The script src.
     * @return string
     */
    public function script_loader_tag($tag, $handle, $src) {
        // Add module type to main script
        if ('aqualuxe-script' === $handle) {
            $tag = '<script type="module" src="' . esc_url($src) . '"></script>';
        }

        return $tag;
    }

    /**
     * Get custom properties CSS from Customizer
     *
     * @return string
     */
    private function get_custom_properties_css() {
        $css = ':root {';

        // Primary color
        $primary_color = get_theme_mod('primary_color', '#0ea5e9');
        $css .= '--color-primary: ' . $primary_color . ';';

        // Secondary color
        $secondary_color = get_theme_mod('secondary_color', '#1e293b');
        $css .= '--color-secondary: ' . $secondary_color . ';';

        // Accent color
        $accent_color = get_theme_mod('accent_color', '#f59e0b');
        $css .= '--color-accent: ' . $accent_color . ';';

        // Body font
        $body_font = get_theme_mod('body_font', 'Inter');
        $css .= '--font-body: ' . $body_font . ', sans-serif;';

        // Heading font
        $heading_font = get_theme_mod('heading_font', 'Playfair Display');
        $css .= '--font-heading: ' . $heading_font . ', serif;';

        $css .= '}';

        return $css;
    }
}