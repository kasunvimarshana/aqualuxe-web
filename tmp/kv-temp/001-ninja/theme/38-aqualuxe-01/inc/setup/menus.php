<?php
/**
 * Menu Setup Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register navigation menus
 * This is called in functions.php during 'after_setup_theme'
 */
function aqualuxe_register_menus() {
	// This is handled in functions.php
	// This function is for reference and documentation
}

/**
 * Add custom classes to menu items
 *
 * @param array $classes Current menu item classes.
 * @param object $item Current menu item.
 * @param object $args Menu arguments.
 * @return array Modified menu item classes.
 */
function aqualuxe_menu_item_classes( $classes, $item, $args ) {
	// Add default classes to all menu items
	if ( 'primary' === $args->theme_location ) {
		$classes[] = 'nav-item';
	}
	
	if ( 'footer' === $args->theme_location ) {
		$classes[] = 'footer-nav-item';
	}
	
	if ( 'social' === $args->theme_location ) {
		$classes[] = 'social-nav-item';
	}
	
	return $classes;
}
add_filter( 'nav_menu_css_class', 'aqualuxe_menu_item_classes', 10, 3 );

/**
 * Add custom classes to menu links
 *
 * @param array $atts Current link attributes.
 * @param object $item Current menu item.
 * @param object $args Menu arguments.
 * @return array Modified link attributes.
 */
function aqualuxe_menu_link_classes( $atts, $item, $args ) {
	// Add default classes to all menu links
	if ( 'primary' === $args->theme_location ) {
		$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' nav-link' : 'nav-link';
		
		// Add active class if current
		if ( in_array( 'current-menu-item', $item->classes, true ) ) {
			$atts['class'] .= ' active';
			$atts['aria-current'] = 'page';
		}
	}
	
	if ( 'footer' === $args->theme_location ) {
		$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' footer-nav-link' : 'footer-nav-link';
	}
	
	if ( 'social' === $args->theme_location ) {
		$atts['class'] = isset( $atts['class'] ) ? $atts['class'] . ' social-nav-link' : 'social-nav-link';
	}
	
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aqualuxe_menu_link_classes', 10, 3 );

/**
 * Add dropdown toggle button for mobile menu
 *
 * @param string $item_output Current item output.
 * @param object $item Current menu item.
 * @param int $depth Current depth.
 * @param object $args Menu arguments.
 * @return string Modified item output.
 */
function aqualuxe_dropdown_toggle( $item_output, $item, $depth, $args ) {
	if ( 'primary' === $args->theme_location && in_array( 'menu-item-has-children', $item->classes, true ) ) {
		$item_output .= '<button class="dropdown-toggle" aria-expanded="false">';
		$item_output .= '<span class="screen-reader-text">' . esc_html__( 'Toggle submenu for', 'aqualuxe' ) . ' ' . esc_html( $item->title ) . '</span>';
		$item_output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>';
		$item_output .= '</button>';
	}
	
	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'aqualuxe_dropdown_toggle', 10, 4 );

/**
 * Add custom menu walker for primary menu
 *
 * @param array $args Menu arguments.
 * @return array Modified menu arguments.
 */
function aqualuxe_nav_menu_args( $args ) {
	if ( 'primary' === $args['theme_location'] ) {
		$args['container'] = false;
		$args['menu_class'] = 'primary-menu';
		$args['items_wrap'] = '<ul id="%1$s" class="%2$s">%3$s</ul>';
	}
	
	return $args;
}
add_filter( 'wp_nav_menu_args', 'aqualuxe_nav_menu_args' );