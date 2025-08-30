<?php
/**
 * Custom filters for the theme
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Add a custom class to the array of post classes.
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function aqualuxe_post_classes( $classes ) {
	// Add a class for the featured image.
	if ( has_post_thumbnail() ) {
		$classes[] = 'has-thumbnail';
	}

	return $classes;
}
add_filter( 'post_class', 'aqualuxe_post_classes' );

/**
 * Add a custom read more link to the excerpt.
 *
 * @param string $more The current more text.
 * @return string
 */
function aqualuxe_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
	if ( is_singular() && pings_open() ) {
		/* translators: %s: pingback URL */
		printf( esc_html__( '<link rel="pingback" href="%s">', 'aqualuxe' ), esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'aqualuxe_pingback_header' );

/**
 * Add custom classes to the body tag.
 *
 * @param array $classes Body classes.
 * @return array
 */
function aqualuxe_custom_body_classes( $classes ) {
	// Add class for dark mode.
	if ( get_theme_mod( 'aqualuxe_dark_mode', false ) ) {
		$classes[] = 'dark-mode';
	}

	// Add class for RTL support.
	if ( is_rtl() ) {
		$classes[] = 'rtl';
	}

	return $classes;
}
add_filter( 'body_class', 'aqualuxe_custom_body_classes' );

/**
 * Filter the comment form defaults.
 *
 * @param array $defaults The default comment form arguments.
 * @return array Returns the modified defaults.
 */
function aqualuxe_comment_form_defaults( $defaults ) {
	$defaults['comment_field'] = sprintf(
		'<div class="comment-form-comment mb-4">
			<label for="comment" class="block text-sm font-medium text-gray-700 mb-1">%s</label>
			<textarea id="comment" name="comment" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" rows="5" maxlength="65525" required="required"></textarea>
		</div>',
		esc_html__( 'Comment *', 'aqualuxe' )
	);

	$defaults['comment_notes_before'] = sprintf(
		'<p class="comment-notes mb-4 text-sm text-gray-600">%s</p>',
		esc_html__( 'Your email address will not be published. Required fields are marked *', 'aqualuxe' )
	);

	$defaults['fields']['author'] = sprintf(
		'<div class="comment-form-author mb-4">
			<label for="author" class="block text-sm font-medium text-gray-700 mb-1">%s</label>
			<input id="author" name="author" type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" value="%s" size="30" maxlength="245" required="required" />
		</div>',
		esc_html__( 'Name *', 'aqualuxe' ),
		esc_attr( $commenter['comment_author'] ?? '' )
	);

	$defaults['fields']['email'] = sprintf(
		'<div class="comment-form-email mb-4">
			<label for="email" class="block text-sm font-medium text-gray-700 mb-1">%s</label>
			<input id="email" name="email" type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" value="%s" size="30" maxlength="100" aria-describedby="email-notes" required="required" />
		</div>',
		esc_html__( 'Email *', 'aqualuxe' ),
		esc_attr( $commenter['comment_author_email'] ?? '' )
	);

	$defaults['fields']['url'] = sprintf(
		'<div class="comment-form-url mb-4">
			<label for="url" class="block text-sm font-medium text-gray-700 mb-1">%s</label>
			<input id="url" name="url" type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" value="%s" size="30" maxlength="200" />
		</div>',
		esc_html__( 'Website', 'aqualuxe' ),
		esc_attr( $commenter['comment_author_url'] ?? '' )
	);

	$defaults['submit_button'] = '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>';
	$defaults['submit_field'] = '<div class="form-submit">%1$s %2$s</div>';
	$defaults['class_submit'] = 'bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out';

	return $defaults;
}
add_filter( 'comment_form_defaults', 'aqualuxe_comment_form_defaults' );

/**
 * Filter the navigation menu items.
 *
 * @param array $items Menu items.
 * @param array $args Menu arguments.
 * @return array
 */
function aqualuxe_nav_menu_items( $items, $args ) {
	// Add custom menu items based on theme options.
	if ( 'primary' === $args->theme_location ) {
		// Add custom menu items here if needed.
	}

	return $items;
}
add_filter( 'wp_nav_menu_items', 'aqualuxe_nav_menu_items', 10, 2 );

/**
 * Filter the search form.
 *
 * @param string $form The search form HTML.
 * @return string
 */
function aqualuxe_search_form( $form ) {
	$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
		<label class="screen-reader-text">' . esc_html__( 'Search', 'aqualuxe' ) . '</label>
		<div class="flex">
			<input type="search" class="search-field w-full px-3 py-2 border border-gray-300 rounded-l-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" placeholder="' . esc_attr__( 'Search...', 'aqualuxe' ) . '" value="' . get_search_query() . '" name="s" />
			<button type="submit" class="search-submit bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded-r-md focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
				<span class="screen-reader-text">' . esc_html__( 'Submit Search', 'aqualuxe' ) . '</span>
				<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
			</button>
		</div>
	</form>';

	return $form;
}
add_filter( 'get_search_form', 'aqualuxe_search_form' );

/**
 * Filter the login URL.
 *
 * @param string $login_url The login URL.
 * @param string $redirect The redirect URL.
 * @param bool   $force_reauth Whether to force reauthorization.
 * @return string
 */
function aqualuxe_login_url( $login_url, $redirect, $force_reauth ) {
	// Customize login URL if needed.
	return $login_url;
}
add_filter( 'login_url', 'aqualuxe_login_url', 10, 3 );

/**
 * Filter the logout URL.
 *
 * @param string $logout_url The logout URL.
 * @param string $redirect The redirect URL.
 * @return string
 */
function aqualuxe_logout_url( $logout_url, $redirect ) {
	// Customize logout URL if needed.
	return $logout_url;
}
add_filter( 'logout_url', 'aqualuxe_logout_url', 10, 2 );

/**
 * Filter the account menu items.
 *
 * @param array $items Menu items.
 * @return array
 */
function aqualuxe_account_menu_items( $items ) {
	// Customize account menu items if needed.
	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'aqualuxe_account_menu_items' );

/**
 * Filter the account menu item classes.
 *
 * @param array  $classes Menu item classes.
 * @param string $endpoint Current endpoint.
 * @return array
 */
function aqualuxe_account_menu_item_classes( $classes, $endpoint ) {
	// Customize account menu item classes if needed.
	return $classes;
}
add_filter( 'woocommerce_account_menu_item_classes', 'aqualuxe_account_menu_item_classes', 10, 2 );

/**
 * Filter the dark mode toggle text.
 *
 * @param string $text The toggle text.
 * @return string
 */
function aqualuxe_dark_mode_toggle_text( $text ) {
	return esc_html__( 'Toggle Dark Mode', 'aqualuxe' );
}
add_filter( 'aqualuxe_dark_mode_toggle_text', 'aqualuxe_dark_mode_toggle_text' );