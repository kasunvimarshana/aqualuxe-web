<?php
/**
 * Navigation menu setup
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register navigation menus.
 */
function aqualuxe_register_nav_menus() {
	register_nav_menus(
		array(
			'primary'   => esc_html__( 'Primary Menu', 'aqualuxe' ),
			'secondary' => esc_html__( 'Secondary Menu', 'aqualuxe' ),
			'footer'    => esc_html__( 'Footer Menu', 'aqualuxe' ),
			'social'    => esc_html__( 'Social Menu', 'aqualuxe' ),
		)
	);
}
add_action( 'after_setup_theme', 'aqualuxe_register_nav_menus' );

/**
 * Add custom classes to the menu items.
 *
 * @param array $classes The CSS classes that are applied to the menu item's <li> element.
 * @param object $item The current menu item.
 * @param array $args An array of wp_nav_menu() arguments.
 * @return array Modified CSS classes.
 */
function aqualuxe_nav_menu_css_class( $classes, $item, $args ) {
	// Add class for menu items with children.
	if ( in_array( 'menu-item-has-children', $classes, true ) ) {
		$classes[] = 'has-dropdown';
	}

	return $classes;
}
add_filter( 'nav_menu_css_class', 'aqualuxe_nav_menu_css_class', 10, 3 );

/**
 * Add custom attributes to the menu items.
 *
 * @param array $atts The HTML attributes applied to the menu item's <a> element.
 * @param object $item The current menu item.
 * @param array $args An array of wp_nav_menu() arguments.
 * @param int $depth Depth of menu item.
 * @return array Modified HTML attributes.
 */
function aqualuxe_nav_menu_link_attributes( $atts, $item, $args, $depth ) {
	// Add aria-expanded attribute for menu items with children.
	if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
		$atts['aria-expanded'] = 'false';
	}

	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'aqualuxe_nav_menu_link_attributes', 10, 4 );

/**
 * Add dropdown toggle button to menu items with children.
 *
 * @param string $item_output The menu item's starting HTML output.
 * @param object $item Menu item data object.
 * @param int $depth Depth of menu item.
 * @param array $args An array of wp_nav_menu() arguments.
 * @return string Modified menu item's starting HTML output.
 */
function aqualuxe_nav_menu_item_output( $item_output, $item, $depth, $args ) {
	// Add dropdown toggle button for menu items with children.
	if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
		$item_output .= '<button class="dropdown-toggle" aria-expanded="false" aria-label="' . esc_attr__( 'Expand child menu', 'aqualuxe' ) . '">';
		$item_output .= '<span class="dropdown-toggle-icon">';
		$item_output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>';
		$item_output .= '</span>';
		$item_output .= '</button>';
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'aqualuxe_nav_menu_item_output', 10, 4 );

/**
 * Add custom menu walker for the primary menu.
 *
 * @param array $args An array of wp_nav_menu() arguments.
 * @return array Modified wp_nav_menu() arguments.
 */
function aqualuxe_nav_menu_args( $args ) {
	if ( 'primary' === $args['theme_location'] ) {
		$args['container'] = false;
		$args['menu_class'] = 'primary-menu';
		$args['items_wrap'] = '<ul id="%1$s" class="%2$s">%3$s</ul>';
	}

	if ( 'secondary' === $args['theme_location'] ) {
		$args['container'] = false;
		$args['menu_class'] = 'secondary-menu';
		$args['items_wrap'] = '<ul id="%1$s" class="%2$s">%3$s</ul>';
	}

	if ( 'footer' === $args['theme_location'] ) {
		$args['container'] = false;
		$args['menu_class'] = 'footer-menu';
		$args['items_wrap'] = '<ul id="%1$s" class="%2$s">%3$s</ul>';
		$args['depth'] = 1;
	}

	if ( 'social' === $args['theme_location'] ) {
		$args['container'] = false;
		$args['menu_class'] = 'social-menu';
		$args['items_wrap'] = '<ul id="%1$s" class="%2$s">%3$s</ul>';
		$args['link_before'] = '<span class="screen-reader-text">';
		$args['link_after'] = '</span>';
		$args['depth'] = 1;
	}

	return $args;
}
add_filter( 'wp_nav_menu_args', 'aqualuxe_nav_menu_args' );

/**
 * Add social icons to social menu.
 *
 * @param string $items The HTML list content for the menu items.
 * @param object $args An object containing wp_nav_menu() arguments.
 * @return string Modified HTML list content for the menu items.
 */
function aqualuxe_nav_menu_social_icons( $items, $args ) {
	// Add social icons to social menu.
	if ( 'social' === $args->theme_location ) {
		$social_icons = array(
			'facebook.com'   => 'facebook',
			'twitter.com'    => 'twitter',
			'instagram.com'  => 'instagram',
			'linkedin.com'   => 'linkedin',
			'youtube.com'    => 'youtube',
			'pinterest.com'  => 'pinterest',
			'github.com'     => 'github',
			'dribbble.com'   => 'dribbble',
			'behance.net'    => 'behance',
			'medium.com'     => 'medium',
			'reddit.com'     => 'reddit',
			'tumblr.com'     => 'tumblr',
			'vimeo.com'      => 'vimeo',
			'flickr.com'     => 'flickr',
			'spotify.com'    => 'spotify',
			'soundcloud.com' => 'soundcloud',
			'twitch.tv'      => 'twitch',
			'discord.com'    => 'discord',
			'slack.com'      => 'slack',
			'telegram.org'   => 'telegram',
			'whatsapp.com'   => 'whatsapp',
			'tiktok.com'     => 'tiktok',
		);

		// Add social icons.
		foreach ( $social_icons as $url => $icon ) {
			$items = preg_replace(
				'/<a([^>]+)href="[^"]*' . $url . '[^"]*"([^>]+)>/',
				'<a$1href="$2" class="social-icon social-icon-' . $icon . '"$3>',
				$items
			);
		}
	}

	return $items;
}
add_filter( 'wp_nav_menu_items', 'aqualuxe_nav_menu_social_icons', 10, 2 );