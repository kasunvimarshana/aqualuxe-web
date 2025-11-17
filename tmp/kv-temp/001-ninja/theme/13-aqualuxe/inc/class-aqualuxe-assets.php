<?php
/**
 * Assets management for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Assets Class
 */
class AquaLuxe_Assets {
    /**
     * Constructor
     */
    public function __construct() {
        // Enqueue styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Enqueue admin styles and scripts
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        
        // Add preconnect for Google Fonts (if needed)
        add_filter('wp_resource_hints', array($this, 'resource_hints'), 10, 2);
        
        // Add inline CSS for custom styles
        add_action('wp_enqueue_scripts', array($this, 'inline_styles'), 20);
        
        // Add editor styles
        add_action('after_setup_theme', array($this, 'editor_styles'));
    }

    /**
     * Enqueue styles for the front end.
     */
    public function enqueue_styles() {
        // Register Tailwind CSS
        wp_register_style(
            'tailwindcss',
            get_template_directory_uri() . '/assets/css/tailwind.min.css',
            array(),
            AQUALUXE_VERSION
        );

        // Register theme styles
        wp_register_style(
            'aqualuxe-style',
            get_template_directory_uri() . '/assets/css/style.css',
            array('tailwindcss'),
            AQUALUXE_VERSION
        );

        // Enqueue main stylesheet
        wp_enqueue_style('aqualuxe-style');

        // Add print CSS
        wp_enqueue_style(
            'aqualuxe-print-style',
            get_template_directory_uri() . '/assets/css/print.css',
            array('aqualuxe-style'),
            AQUALUXE_VERSION,
            'print'
        );
    }

    /**
     * Enqueue scripts for the front end.
     */
    public function enqueue_scripts() {
        // Register and enqueue navigation script
        wp_enqueue_script(
            'aqualuxe-navigation',
            get_template_directory_uri() . '/assets/js/navigation.js',
            array(),
            AQUALUXE_VERSION,
            true
        );

        // Register and enqueue dark mode script
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            get_template_directory_uri() . '/assets/js/dark-mode.js',
            array(),
            AQUALUXE_VERSION,
            true
        );

        // Register and enqueue main script
        wp_enqueue_script(
            'aqualuxe-script',
            get_template_directory_uri() . '/assets/js/main.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );

        // Localize script
        wp_localize_script(
            'aqualuxe-script',
            'aqualuxeSettings',
            array(
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'themeUrl' => get_template_directory_uri(),
                'siteUrl' => site_url(),
                'isRtl' => is_rtl(),
            )
        );

        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Enqueue scripts for the admin area.
     */
    public function admin_enqueue_scripts() {
        // Admin styles
        wp_enqueue_style(
            'aqualuxe-admin-style',
            get_template_directory_uri() . '/assets/css/admin.css',
            array(),
            AQUALUXE_VERSION
        );

        // Admin scripts
        wp_enqueue_script(
            'aqualuxe-admin-script',
            get_template_directory_uri() . '/assets/js/admin.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
    }

    /**
     * Add preconnect for Google Fonts.
     *
     * @param array  $urls           URLs to print for resource hints.
     * @param string $relation_type  The relation type the URLs are printed.
     * @return array $urls           URLs to print for resource hints.
     */
    public function resource_hints($urls, $relation_type) {
        if ('preconnect' === $relation_type) {
            // Add Google Fonts domain if needed
            // $urls[] = array(
            //     'href' => 'https://fonts.gstatic.com',
            //     'crossorigin',
            // );
        }

        return $urls;
    }

    /**
     * Add inline styles for custom options.
     */
    public function inline_styles() {
        // Get customizer options
        $primary_color = get_theme_mod('primary_color', '#0077b6');
        $secondary_color = get_theme_mod('secondary_color', '#00b4d8');
        $text_color = get_theme_mod('text_color', '#333333');
        $link_color = get_theme_mod('link_color', '#0077b6');
        
        // Create custom CSS
        $custom_css = "
            :root {
                --color-primary: {$primary_color};
                --color-primary-dark: {$this->darken_color($primary_color, 15)};
                --color-secondary: {$secondary_color};
                --color-text: {$text_color};
                --color-link: {$link_color};
            }
        ";
        
        // Add the inline style
        wp_add_inline_style('aqualuxe-style', $custom_css);
    }

    /**
     * Add editor styles.
     */
    public function editor_styles() {
        // Add editor styles
        add_theme_support('editor-styles');
        add_editor_style('assets/css/editor-style.css');
    }

    /**
     * Helper function to darken a color
     *
     * @param string $hex Hex color code.
     * @param int    $percent Percentage to darken.
     * @return string
     */
    private function darken_color($hex, $percent) {
        // Remove # if present
        $hex = ltrim($hex, '#');
        
        // Convert to RGB
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        
        // Darken
        $r = max(0, min(255, $r - ($r * ($percent / 100))));
        $g = max(0, min(255, $g - ($g * ($percent / 100))));
        $b = max(0, min(255, $b - ($b * ($percent / 100))));
        
        // Convert back to hex
        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}

// Initialize the class
new AquaLuxe_Assets();