<?php
/**
 * Custom Walker for Navigation Menus
 *
 * Extends the default WordPress walker to provide better markup
 * with accessibility features and Tailwind CSS classes.
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom Walker for Main Navigation
 */
class AquaLuxe_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    /**
     * Start Level - Opening wrapper for sub-menus
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        
        if ($depth === 0) {
            // First level dropdown
            $output .= "\n$indent<div class=\"absolute top-full left-0 min-w-48 bg-white dark:bg-gray-800 shadow-lg rounded-md border border-gray-200 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50\">\n";
            $output .= "$indent\t<ul class=\"py-2\" role=\"menu\">\n";
        } else {
            // Nested dropdowns
            $output .= "\n$indent<div class=\"absolute top-0 left-full min-w-48 bg-white dark:bg-gray-800 shadow-lg rounded-md border border-gray-200 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50\">\n";
            $output .= "$indent\t<ul class=\"py-2\" role=\"menu\">\n";
        }
    }
    
    /**
     * End Level - Closing wrapper for sub-menus
     */
    public function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent\t</ul>\n";
        $output .= "$indent</div>\n";
    }
    
    /**
     * Start Element - Opening wrapper for menu items
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Check if item has children
        $has_children = in_array('menu-item-has-children', $classes);
        
        // Build class string
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        
        if ($depth === 0) {
            // Top level items
            $li_classes = 'relative group';
            if ($has_children) {
                $li_classes .= ' has-dropdown';
            }
        } else {
            // Dropdown items
            $li_classes = 'relative group';
            if ($has_children) {
                $li_classes .= ' has-submenu';
            }
        }
        
        $class_names = $class_names ? ' class="' . esc_attr($li_classes . ' ' . $class_names) . '"' : ' class="' . esc_attr($li_classes) . '"';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        // Link attributes
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        // Build link classes based on depth
        if ($depth === 0) {
            $link_classes = 'inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200';
            if ($has_children) {
                $attributes .= ' aria-haspopup="true" aria-expanded="false"';
            }
        } else {
            $link_classes = 'block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200';
            $attributes .= ' role="menuitem"';
        }
        
        $attributes .= ' class="' . esc_attr($link_classes) . '"';
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes .'>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        
        // Add dropdown arrow for parent items
        if ($has_children && $depth === 0) {
            $item_output .= '<svg class="ml-1 w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
        } elseif ($has_children && $depth > 0) {
            $item_output .= '<svg class="ml-auto w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
        }
        
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    /**
     * End Element - Closing wrapper for menu items
     */
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}

/**
 * Custom Walker for Mobile Navigation
 */
class AquaLuxe_Walker_Mobile_Nav_Menu extends Walker_Nav_Menu {
    
    /**
     * Start Level - Opening wrapper for sub-menus
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"mobile-submenu pl-4 mt-2 space-y-1 border-l-2 border-gray-200 dark:border-gray-600\">\n";
    }
    
    /**
     * End Level - Closing wrapper for sub-menus
     */
    public function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    
    /**
     * Start Element - Opening wrapper for menu items
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Check if item has children
        $has_children = in_array('menu-item-has-children', $classes);
        
        // Build class string
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        // Link attributes
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        // Mobile link classes
        $link_classes = 'flex items-center justify-between w-full py-3 px-4 text-base font-medium text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200 rounded-md';
        
        if ($has_children) {
            $attributes .= ' aria-haspopup="true" aria-expanded="false" data-mobile-dropdown-toggle';
        }
        
        $attributes .= ' class="' . esc_attr($link_classes) . '"';
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes .'>';
        $item_output .= '<span>' . (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '') . '</span>';
        
        // Add dropdown arrow for parent items
        if ($has_children) {
            $item_output .= '<svg class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>';
        }
        
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    /**
     * End Element - Closing wrapper for menu items
     */
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}
