<?php
/**
 * SEO meta tags
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add Open Graph and Twitter Card meta tags.
 */
function aqualuxe_add_meta_tags() {
	// Don't add meta tags for admin pages.
	if ( is_admin() ) {
		return;
	}

	// Get the current post/page ID.
	$id = get_queried_object_id();

	// Default values.
	$title = get_bloginfo( 'name' );
	$description = get_bloginfo( 'description' );
	$url = home_url( '/' );
	$image = '';
	$type = 'website';

	// Get the site logo.
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id ) {
		$image = wp_get_attachment_image_url( $custom_logo_id, 'full' );
	}

	// Override defaults for specific pages.
	if ( is_singular() ) {
		$title = get_the_title( $id );
		$url = get_permalink( $id );
		$type = 'article';

		// Get the post excerpt.
		$post = get_post( $id );
		if ( $post ) {
			$description = aqualuxe_get_excerpt( $post );
		}

		// Get the post thumbnail.
		if ( has_post_thumbnail( $id ) ) {
			$image = get_the_post_thumbnail_url( $id, 'full' );
		}
	} elseif ( is_archive() ) {
		$title = get_the_archive_title();
		$description = get_the_archive_description();
		$url = get_post_type_archive_link( get_post_type() );
	} elseif ( is_search() ) {
		/* translators: %s: search query. */
		$title = sprintf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), get_search_query() );
		$url = get_search_link();
	}

	// Add Open Graph meta tags.
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $type ) . '" />' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";

	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '" />' . "\n";
	}

	// Add Twitter Card meta tags.
	echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '" />' . "\n";

	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '" />' . "\n";
	}

	// Add Twitter site username if available.
	$twitter_username = get_theme_mod( 'aqualuxe_twitter_username' );
	if ( $twitter_username ) {
		echo '<meta name="twitter:site" content="@' . esc_attr( $twitter_username ) . '" />' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_add_meta_tags' );

/**
 * Get the excerpt for a post.
 *
 * @param WP_Post $post The post object.
 * @return string The excerpt.
 */
function aqualuxe_get_excerpt( $post ) {
	if ( ! $post ) {
		return '';
	}

	$excerpt = '';

	// Get the excerpt from the post.
	if ( ! empty( $post->post_excerpt ) ) {
		$excerpt = $post->post_excerpt;
	} else {
		// Get the excerpt from the content.
		$excerpt = strip_shortcodes( $post->post_content );
		$excerpt = wp_strip_all_tags( $excerpt );
		$excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
		$excerpt = mb_substr( $excerpt, 0, 160 );
		$excerpt = preg_replace( '/\s+/', ' ', $excerpt );
		$excerpt = trim( $excerpt );
	}

	return $excerpt;
}

/**
 * Add canonical URL.
 */
function aqualuxe_add_canonical_url() {
	// Don't add canonical URL for admin pages.
	if ( is_admin() ) {
		return;
	}

	// Get the current URL.
	$url = '';

	if ( is_singular() ) {
		$url = get_permalink();
	} elseif ( is_home() ) {
		$url = get_permalink( get_option( 'page_for_posts' ) );
	} elseif ( is_archive() ) {
		$url = get_post_type_archive_link( get_post_type() );
	} elseif ( is_search() ) {
		$url = get_search_link();
	} else {
		$url = home_url( '/' );
	}

	// Add canonical URL.
	echo '<link rel="canonical" href="' . esc_url( $url ) . '" />' . "\n";
}
add_action( 'wp_head', 'aqualuxe_add_canonical_url' );

/**
 * Add meta description.
 */
function aqualuxe_add_meta_description() {
	// Don't add meta description for admin pages.
	if ( is_admin() ) {
		return;
	}

	// Get the current post/page ID.
	$id = get_queried_object_id();

	// Default description.
	$description = get_bloginfo( 'description' );

	// Override default description for specific pages.
	if ( is_singular() ) {
		// Get the post excerpt.
		$post = get_post( $id );
		if ( $post ) {
			$description = aqualuxe_get_excerpt( $post );
		}
	} elseif ( is_archive() ) {
		$description = get_the_archive_description();
	}

	// Add meta description.
	echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
}
add_action( 'wp_head', 'aqualuxe_add_meta_description' );

/**
 * Add meta robots.
 */
function aqualuxe_add_meta_robots() {
	// Don't add meta robots for admin pages.
	if ( is_admin() ) {
		return;
	}

	// Default robots.
	$robots = 'index, follow';

	// Override default robots for specific pages.
	if ( is_search() || is_404() ) {
		$robots = 'noindex, nofollow';
	}

	// Add meta robots.
	echo '<meta name="robots" content="' . esc_attr( $robots ) . '" />' . "\n";
}
add_action( 'wp_head', 'aqualuxe_add_meta_robots' );

/**
 * Add meta viewport.
 */
function aqualuxe_add_meta_viewport() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";
}
add_action( 'wp_head', 'aqualuxe_add_meta_viewport' );

/**
 * Add theme color meta tag.
 */
function aqualuxe_add_theme_color() {
	$primary_color = get_theme_mod( 'aqualuxe_primary_color', '#0891b2' );
	echo '<meta name="theme-color" content="' . esc_attr( $primary_color ) . '" />' . "\n";
}
add_action( 'wp_head', 'aqualuxe_add_theme_color' );

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'aqualuxe-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'aqualuxe_resource_hints', 10, 2 );

/**
 * Add preload for critical assets.
 */
function aqualuxe_preload_assets() {
	// Preload critical CSS.
	if ( get_theme_mod( 'aqualuxe_preload_critical_css', true ) ) {
		echo '<link rel="preload" href="' . esc_url( get_template_directory_uri() . '/assets/dist/css/critical.css' ) . '" as="style" />' . "\n";
	}

	// Preload web fonts.
	echo '<link rel="preload" href="https://fonts.gstatic.com" crossorigin />' . "\n";
}
add_action( 'wp_head', 'aqualuxe_preload_assets', 1 );