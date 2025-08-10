<?php
/**
 * AquaLuxe Theme Setup
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_Setup Class
 * 
 * Handles the theme setup and initialization
 */
class AquaLuxe_Setup {
    /**
     * Constructor
     */
    public function __construct() {
        // Theme setup actions
        add_action('after_setup_theme', array($this, 'content_width'), 0);
        
        // Register theme features
        add_action('after_setup_theme', array($this, 'add_editor_styles'));
        
        // Add body classes
        add_filter('body_class', array($this, 'body_classes'));
        
        // Add pingback header
        add_action('wp_head', array($this, 'pingback_header'));
    }

    /**
     * Set the content width in pixels
     */
    public function content_width() {
        $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
    }

    /**
     * Add editor styles
     */
    public function add_editor_styles() {
        add_theme_support('editor-styles');
        add_editor_style('assets/css/editor-style.css');
    }

    /**
     * Adds custom classes to the array of body classes.
     *
     * @param array $classes Classes for the body element.
     * @return array
     */
    public function body_classes($classes) {
        // Add a class if there is a custom header
        if (has_header_image()) {
            $classes[] = 'has-header-image';
        }

        // Add a class if sidebar is used
        if (is_active_sidebar('sidebar-1') && !is_page_template('templates/template-full-width.php')) {
            $classes[] = 'has-sidebar';
        } else {
            $classes[] = 'no-sidebar';
        }

        // Add a class for the color scheme
        $color_scheme = aqualuxe_get_option('color_scheme', 'light');
        $classes[] = 'color-scheme-' . $color_scheme;

        // Add a class for the header layout
        $header_layout = aqualuxe_get_option('header_layout', 'default');
        $classes[] = 'header-layout-' . $header_layout;

        return $classes;
    }

    /**
     * Add a pingback url auto-discovery header for singularly identifiable articles.
     */
    public function pingback_header() {
        if (is_singular() && pings_open()) {
            echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
        }
    }
}

// Initialize the class
new AquaLuxe_Setup();