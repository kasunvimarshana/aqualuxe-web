<?php
/**
 * Theme setup
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core\Setup;

/**
 * Theme setup class
 */
class Theme_Setup {
    /**
     * Constructor
     */
    public function __construct() {
        add_action('after_setup_theme', [$this, 'setup']);
        add_action('after_setup_theme', [$this, 'content_width'], 0);
        add_filter('body_class', [$this, 'body_classes']);
    }

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * @return void
     */
    public function setup() {
        // Make theme available for translation
        load_theme_textdomain('aqualuxe', AQUALUXE_DIR . 'languages');

        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');

        // Set post thumbnail size
        set_post_thumbnail_size(1200, 9999);

        // Add image sizes
        add_image_size('aqualuxe-featured', 1600, 900, true);
        add_image_size('aqualuxe-card', 600, 400, true);
        add_image_size('aqualuxe-thumbnail', 300, 300, true);

        // This theme uses wp_nav_menu() in multiple locations
        register_nav_menus([
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'social' => esc_html__('Social Links Menu', 'aqualuxe'),
        ]);

        // Switch default core markup for search form, comment form, and comments to output valid HTML5
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]);

        // Set up the WordPress core custom background feature
        add_theme_support('custom-background', [
            'default-color' => 'ffffff',
            'default-image' => '',
        ]);

        // Add theme support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');

        // Add support for core custom logo
        add_theme_support('custom-logo', [
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        ]);

        // Add support for editor styles
        add_theme_support('editor-styles');

        // Enqueue editor styles
        add_editor_style(aqualuxe_asset('css/editor.css'));

        // Add support for responsive embeds
        add_theme_support('responsive-embeds');

        // Add support for full and wide align images
        add_theme_support('align-wide');

        // Add support for custom line height controls
        add_theme_support('custom-line-height');

        // Add support for experimental link color control
        add_theme_support('experimental-link-color');

        // Add support for custom units
        add_theme_support('custom-units');

        // Add support for custom spacing
        add_theme_support('custom-spacing');

        // Add support for block styles
        add_theme_support('wp-block-styles');
    }

    /**
     * Set the content width in pixels, based on the theme's design and stylesheet.
     *
     * @global int $content_width
     * @return void
     */
    public function content_width() {
        $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
    }

    /**
     * Adds custom classes to the array of body classes.
     *
     * @param array $classes Classes for the body element.
     * @return array
     */
    public function body_classes($classes) {
        // Adds a class of hfeed to non-singular pages
        if (!is_singular()) {
            $classes[] = 'hfeed';
        }

        // Adds a class of no-sidebar when there is no sidebar present
        if (!is_active_sidebar('sidebar-1')) {
            $classes[] = 'no-sidebar';
        }

        return $classes;
    }
}