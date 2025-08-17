<?php
/**
 * Jetpack Compatibility File
 *
 * @link https://jetpack.com/
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Jetpack setup function.
 *
 * See: https://jetpack.com/support/infinite-scroll/
 * See: https://jetpack.com/support/responsive-videos/
 * See: https://jetpack.com/support/content-options/
 */
function aqualuxe_jetpack_setup() {
	// Add theme support for Infinite Scroll.
	add_theme_support(
		'infinite-scroll',
		array(
			'container'      => 'main',
			'render'         => 'aqualuxe_infinite_scroll_render',
			'footer'         => 'page',
			'posts_per_page' => get_option( 'posts_per_page' ),
		)
	);

	// Add theme support for Responsive Videos.
	add_theme_support( 'jetpack-responsive-videos' );

	// Add theme support for Content Options.
	add_theme_support(
		'jetpack-content-options',
		array(
			'post-details'    => array(
				'stylesheet' => 'aqualuxe-style',
				'date'       => '.posted-on',
				'categories' => '.cat-links',
				'tags'       => '.tags-links',
				'author'     => '.byline',
				'comment'    => '.comments-link',
			),
			'featured-images' => array(
				'archive'    => true,
				'post'       => true,
				'page'       => true,
			),
		)
	);
}
add_action( 'after_setup_theme', 'aqualuxe_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function aqualuxe_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		if ( is_search() ) :
			get_template_part( 'template-parts/content', 'search' );
		else :
			get_template_part( 'template-parts/content', get_post_type() );
		endif;
	}
}

/**
 * Disable auto-loading of related posts in Jetpack.
 */
function aqualuxe_jetpack_remove_related_posts() {
	if ( class_exists( 'Jetpack_RelatedPosts' ) ) {
		$jprp = Jetpack_RelatedPosts::init();
		$callback = array( $jprp, 'filter_add_target_to_dom' );
		remove_filter( 'the_content', $callback, 40 );
	}
}
add_action( 'wp', 'aqualuxe_jetpack_remove_related_posts' );

/**
 * Custom function to check if Jetpack is active.
 */
function aqualuxe_is_jetpack_active() {
	return class_exists( 'Jetpack' );
}

/**
 * Custom function to check if a specific Jetpack module is active.
 *
 * @param string $module The Jetpack module to check.
 * @return bool Whether the module is active.
 */
function aqualuxe_is_jetpack_module_active( $module ) {
	return aqualuxe_is_jetpack_active() && Jetpack::is_module_active( $module );
}