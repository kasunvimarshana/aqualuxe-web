<?php
/**
 * Custom Navigation Walker
 *
 * Custom walker class to implement Tailwind CSS navigation menus
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Navigation Walker Class
 *
 * Custom walker for WordPress navigation menus with Tailwind CSS styling
 */
class AquaLuxe_Walker_Nav_Menu extends Walker_Nav_Menu {

    /**
     * Starts the list before the elements are added.
     *
     * @param string   $output Used to append additional content.
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);

        // Default class for sub-menus
        $classes = array('sub-menu');
        
        if ($depth === 0) {
            $classes[] = 'absolute top-full left-0 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-2 min-w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200';
        } else {
            $classes[] = 'absolute top-0 left-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg py-2 min-w-48 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200';
        }

        $class_names = join(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= "{$n}{$indent}<ul{$class_names}>{$n}";
    }

    /**
     * Ends the list after the elements are added.
     *
     * @param string   $output Used to append additional content.
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function end_lvl(&$output, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat($t, $depth);
        $output .= "$indent</ul>{$n}";
    }

    /**
     * Starts the element output.
     *
     * @param string   $output Used to append additional content.
     * @param WP_Post  $item   Menu item data object.
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     * @param int      $id     Current item ID.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ($depth) ? str_repeat($t, $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Default classes based on depth
        if ($depth === 0) {
            $classes[] = 'relative group';
        } else {
            $classes[] = 'group';
        }

        // Add current and ancestor classes
        if (in_array('current-menu-item', $classes)) {
            $classes[] = 'current';
        }
        if (in_array('current-menu-ancestor', $classes)) {
            $classes[] = 'ancestor';
        }
        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'has-children';
        }

        /**
         * Filters the arguments for a single nav menu item.
         *
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param WP_Post  $item  Menu item data object.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $args = apply_filters('nav_menu_item_args', $args, $item, $depth);

        /**
         * Filters the CSS class(es) applied to a menu item's list item element.
         *
         * @param string[] $classes Array of the CSS classes applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        // Link classes based on depth
        $link_classes = array();
        if ($depth === 0) {
            $link_classes[] = 'block px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200 font-medium';
            if (in_array('current-menu-item', $classes)) {
                $link_classes[] = 'text-primary-600 dark:text-primary-400';
            }
        } else {
            $link_classes[] = 'block px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200';
            if (in_array('current-menu-item', $classes)) {
                $link_classes[] = 'text-primary-600 dark:text-primary-400 bg-gray-50 dark:bg-gray-700';
            }
        }

        $link_class_names = join(' ', $link_classes);
        $link_class_names = $link_class_names ? ' class="' . esc_attr($link_class_names) . '"' : '';

        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . $link_class_names . '>';
        $item_output .= isset($args->link_before) ? $args->link_before : '';
        $item_output .= apply_filters('the_title', $item->title, $item->ID);
        $item_output .= isset($args->link_after) ? $args->link_after : '';

        // Add dropdown indicator for parent items
        if (in_array('menu-item-has-children', $classes)) {
            $item_output .= '<svg class="w-4 h-4 ml-1 inline-block transform group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>';
        }

        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';

        /**
         * Filters a menu item's starting output.
         *
         * @param string   $item_output The menu item's starting HTML output.
         * @param WP_Post  $item        Menu item data object.
         * @param int      $depth       Depth of menu item. Used for padding.
         * @param stdClass $args        An object of wp_nav_menu() arguments.
         */
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    /**
     * Ends the element output.
     *
     * @param string   $output Used to append additional content.
     * @param WP_Post  $item   Page data object. Not used.
     * @param int      $depth  Depth of page. Not Used.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $output .= "</li>{$n}";
    }
}

/**
 * WP Bootstrap Navwalker compatibility class
 * 
 * Provides fallback for themes that reference WP_Bootstrap_Navwalker
 */
class WP_Bootstrap_Navwalker extends AquaLuxe_Walker_Nav_Menu {
    // This class extends our custom walker for compatibility
}