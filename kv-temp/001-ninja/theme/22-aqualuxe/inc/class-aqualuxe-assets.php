<?php
/**
 * AquaLuxe Theme Assets
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_Assets Class
 * 
 * Handles the theme assets (CSS, JS, etc.)
 */
class AquaLuxe_Assets {
    /**
     * Constructor
     */
    public function __construct() {
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add preload for fonts
        add_action('wp_head', array($this, 'preload_fonts'), 1);
        
        // Add inline CSS for customizer options
        add_action('wp_enqueue_scripts', array($this, 'inline_styles'), 20);
        
        // Add async and defer attributes to scripts
        add_filter('script_loader_tag', array($this, 'script_loader_tag'), 10, 2);
    }

    /**
     * Enqueue styles
     */
    public function enqueue_styles() {
        // Enqueue Google Fonts
        wp_enqueue_style(
            'aqualuxe-fonts',
            $this->get_google_fonts_url(),
            array(),
            AQUALUXE_VERSION
        );
        
        // Main stylesheet
        wp_enqueue_style(
            'aqualuxe-style',
            AQUALUXE_URI . 'assets/css/main.css',
            array(),
            AQUALUXE_VERSION
        );
        
        // WooCommerce styles
        if (class_exists('WooCommerce')) {
            wp_enqueue_style(
                'aqualuxe-woocommerce',
                AQUALUXE_URI . 'assets/css/woocommerce.css',
                array('aqualuxe-style'),
                AQUALUXE_VERSION
            );
        }
        
        // RTL styles
        if (is_rtl()) {
            wp_enqueue_style(
                'aqualuxe-rtl',
                AQUALUXE_URI . 'assets/css/rtl.css',
                array('aqualuxe-style'),
                AQUALUXE_VERSION
            );
        }
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts() {
        // Navigation script
        wp_enqueue_script(
            'aqualuxe-navigation',
            AQUALUXE_URI . 'assets/js/navigation.js',
            array(),
            AQUALUXE_VERSION,
            true
        );
        
        // Skip link focus fix
        wp_enqueue_script(
            'aqualuxe-skip-link-focus-fix',
            AQUALUXE_URI . 'assets/js/skip-link-focus-fix.js',
            array(),
            AQUALUXE_VERSION,
            true
        );
        
        // Main script
        wp_enqueue_script(
            'aqualuxe-main',
            AQUALUXE_URI . 'assets/js/main.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        // Dark mode toggle
        wp_enqueue_script(
            'aqualuxe-dark-mode',
            AQUALUXE_URI . 'assets/js/dark-mode.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        // Localize script for dark mode
        wp_localize_script(
            'aqualuxe-dark-mode',
            'aqualuxeDarkMode',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce'   => wp_create_nonce('aqualuxe-dark-mode-nonce'),
            )
        );
        
        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
        
        // WooCommerce scripts
        if (class_exists('WooCommerce')) {
            wp_enqueue_script(
                'aqualuxe-woocommerce',
                AQUALUXE_URI . 'assets/js/woocommerce.js',
                array('jquery'),
                AQUALUXE_VERSION,
                true
            );
            
            // Quick view script
            if (is_shop() || is_product_category() || is_product_tag()) {
                wp_enqueue_script(
                    'aqualuxe-quick-view',
                    AQUALUXE_URI . 'assets/js/quick-view.js',
                    array('jquery', 'wc-add-to-cart-variation'),
                    AQUALUXE_VERSION,
                    true
                );
                
                wp_localize_script(
                    'aqualuxe-quick-view',
                    'aqualuxeQuickView',
                    array(
                        'ajaxurl' => admin_url('admin-ajax.php'),
                        'nonce'   => wp_create_nonce('aqualuxe-quick-view-nonce'),
                    )
                );
            }
        }
    }

    /**
     * Get Google Fonts URL
     * 
     * @return string Google Fonts URL
     */
    private function get_google_fonts_url() {
        $fonts_url = '';
        $fonts     = array();
        $subsets   = 'latin,latin-ext';

        /* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
        if ('off' !== _x('on', 'Montserrat font: on or off', 'aqualuxe')) {
            $fonts[] = 'Montserrat:400,500,600,700';
        }

        /* translators: If there are characters in your language that are not supported by Playfair Display, translate this to 'off'. Do not translate into your own language. */
        if ('off' !== _x('on', 'Playfair Display font: on or off', 'aqualuxe')) {
            $fonts[] = 'Playfair Display:400,500,600,700';
        }

        if ($fonts) {
            $fonts_url = add_query_arg(array(
                'family' => urlencode(implode('|', $fonts)),
                'subset' => urlencode($subsets),
                'display' => 'swap',
            ), 'https://fonts.googleapis.com/css');
        }

        return $fonts_url;
    }

    /**
     * Preload fonts
     */
    public function preload_fonts() {
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    }

    /**
     * Add inline styles for customizer options
     */
    public function inline_styles() {
        $primary_color = aqualuxe_get_option('primary_color', '#0077b6');
        $secondary_color = aqualuxe_get_option('secondary_color', '#00b4d8');
        
        $custom_css = "
            :root {
                --primary-color: {$primary_color};
                --secondary-color: {$secondary_color};
            }
        ";
        
        wp_add_inline_style('aqualuxe-style', $custom_css);
    }

    /**
     * Add async/defer attributes to scripts
     * 
     * @param string $tag    The script tag.
     * @param string $handle The script handle.
     * @return string Modified script tag.
     */
    public function script_loader_tag($tag, $handle) {
        // Add async attribute to specific scripts
        $async_scripts = array(
            'aqualuxe-navigation',
            'aqualuxe-skip-link-focus-fix',
        );
        
        if (in_array($handle, $async_scripts, true)) {
            return str_replace(' src', ' async src', $tag);
        }
        
        // Add defer attribute to specific scripts
        $defer_scripts = array(
            'aqualuxe-dark-mode',
        );
        
        if (in_array($handle, $defer_scripts, true)) {
            return str_replace(' src', ' defer src', $tag);
        }
        
        return $tag;
    }
}

// Initialize the class
new AquaLuxe_Assets();