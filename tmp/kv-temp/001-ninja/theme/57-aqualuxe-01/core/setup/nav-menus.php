<?php
/**
 * Register navigation menus
 *
 * @package AquaLuxe
 */

/**
 * Register navigation menus
 */
function aqualuxe_register_menus() {
    register_nav_menus(
        array(
            'primary-menu' => esc_html__('Primary Menu', 'aqualuxe'),
            'footer-menu' => esc_html__('Footer Menu', 'aqualuxe'),
            'top-bar-menu' => esc_html__('Top Bar Menu', 'aqualuxe'),
            'mobile-menu' => esc_html__('Mobile Menu', 'aqualuxe'),
            'account-menu' => esc_html__('Account Menu', 'aqualuxe'),
            'wholesale-menu' => esc_html__('Wholesale Menu', 'aqualuxe'),
        )
    );
}
add_action('after_setup_theme', 'aqualuxe_register_menus');

/**
 * Add custom classes to menu items
 *
 * @param array $classes The CSS classes that are applied to the menu item's <li> element.
 * @param WP_Post $item The current menu item.
 * @param stdClass $args An object of wp_nav_menu() arguments.
 * @return array Modified classes array.
 */
function aqualuxe_menu_item_classes($classes, $item, $args) {
    // Add default classes to all menu items
    if ($args->theme_location === 'primary-menu') {
        $classes[] = 'nav-item';
    }

    if ($args->theme_location === 'footer-menu') {
        $classes[] = 'footer-nav-item';
    }

    // Add active class to current menu item
    if (in_array('current-menu-item', $classes)) {
        $classes[] = 'active';
    }

    return $classes;
}
add_filter('nav_menu_css_class', 'aqualuxe_menu_item_classes', 10, 3);

/**
 * Add custom classes to menu links
 *
 * @param array $atts The HTML attributes applied to the menu item's <a> element.
 * @param WP_Post $item The current menu item.
 * @param stdClass $args An object of wp_nav_menu() arguments.
 * @return array Modified attributes array.
 */
function aqualuxe_menu_link_classes($atts, $item, $args) {
    // Add default classes to all menu links
    if ($args->theme_location === 'primary-menu') {
        $atts['class'] = isset($atts['class']) ? $atts['class'] . ' nav-link' : 'nav-link';
    }

    if ($args->theme_location === 'footer-menu') {
        $atts['class'] = isset($atts['class']) ? $atts['class'] . ' footer-nav-link' : 'footer-nav-link';
    }

    return $atts;
}
add_filter('nav_menu_link_attributes', 'aqualuxe_menu_link_classes', 10, 3);

/**
 * Add dropdown toggle button for mobile menu
 *
 * @param string $item_output The menu item's starting HTML output.
 * @param WP_Post $item Menu item data object.
 * @param int $depth Depth of menu item.
 * @param stdClass $args An object of wp_nav_menu() arguments.
 * @return string Modified menu item output.
 */
function aqualuxe_add_dropdown_toggle($item_output, $item, $depth, $args) {
    if ($args->theme_location === 'primary-menu' || $args->theme_location === 'mobile-menu') {
        if (in_array('menu-item-has-children', $item->classes)) {
            $item_output .= '<button class="dropdown-toggle" aria-expanded="false">';
            $item_output .= '<span class="screen-reader-text">' . esc_html__('Toggle submenu', 'aqualuxe') . '</span>';
            $item_output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>';
            $item_output .= '</button>';
        }
    }
    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'aqualuxe_add_dropdown_toggle', 10, 4);