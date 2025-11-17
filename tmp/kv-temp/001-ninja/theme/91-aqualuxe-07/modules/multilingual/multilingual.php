<?php
/**
 * Multilingual Module
 *
 * Provides multilingual support, primarily for Polylang.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add language switcher to the primary navigation.
 *
 * @param string $items The HTML list content for the menu items.
 * @param object $args  An object containing wp_nav_menu() arguments.
 * @return string The modified HTML list content.
 */
function aqualuxe_add_language_switcher( $items, $args ) {
    if ( function_exists( 'pll_the_languages' ) && $args->theme_location == 'primary' ) {
        $items .= '<li class="menu-item menu-item-language-switcher">' . pll_the_languages( array( 'display_names_as' => 'name', 'hide_if_no_translation' => 1, 'echo' => 0 ) ) . '</li>';
    }
    return $items;
}
add_filter( 'wp_nav_menu_items', 'aqualuxe_add_language_switcher', 10, 2 );

/**
 * Register strings for translation in Polylang.
 */
function aqualuxe_polylang_register_strings() {
    if ( function_exists( 'pll_register_string' ) ) {
        // Example of registering a theme option string
        // pll_register_string('Theme Options', 'Footer Text', 'aqualuxe');
    }
}
add_action( 'after_setup_theme', 'aqualuxe_polylang_register_strings' );
