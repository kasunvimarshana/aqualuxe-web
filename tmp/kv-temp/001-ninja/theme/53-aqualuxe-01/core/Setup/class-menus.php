<?php
/**
 * Menus setup
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core\Setup;

/**
 * Menus setup class
 */
class Menus {
    /**
     * Constructor
     */
    public function __construct() {
        add_action('after_setup_theme', [$this, 'register_menus']);
        add_filter('nav_menu_css_class', [$this, 'menu_item_classes'], 10, 4);
        add_filter('nav_menu_link_attributes', [$this, 'menu_link_attributes'], 10, 4);
    }

    /**
     * Register menus
     *
     * @return void
     */
    public function register_menus() {
        register_nav_menus([
            'primary' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer' => esc_html__('Footer Menu', 'aqualuxe'),
            'social' => esc_html__('Social Links Menu', 'aqualuxe'),
            'mobile' => esc_html__('Mobile Menu', 'aqualuxe'),
            'account' => esc_html__('Account Menu', 'aqualuxe'),
        ]);
    }

    /**
     * Add custom classes to menu items
     *
     * @param array $classes Menu item classes
     * @param WP_Post $item Menu item object
     * @param stdClass $args Menu arguments
     * @param int $depth Menu item depth
     * @return array
     */
    public function menu_item_classes($classes, $item, $args, $depth) {
        // Add custom classes based on menu location
        if ('primary' === $args->theme_location) {
            $classes[] = 'nav-item';
            
            // Add active class
            if (in_array('current-menu-item', $classes, true)) {
                $classes[] = 'active';
            }
        }

        return $classes;
    }

    /**
     * Add custom attributes to menu links
     *
     * @param array $atts Menu link attributes
     * @param WP_Post $item Menu item object
     * @param stdClass $args Menu arguments
     * @param int $depth Menu item depth
     * @return array
     */
    public function menu_link_attributes($atts, $item, $args, $depth) {
        // Add custom attributes based on menu location
        if ('primary' === $args->theme_location) {
            $atts['class'] = isset($atts['class']) ? $atts['class'] . ' nav-link' : 'nav-link';
        }

        return $atts;
    }
}