<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Adds custom classes to the array of body classes.
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

	// Add a class if dark mode is enabled.
	if ( get_theme_mod( 'aqualuxe_dark_mode_default', false ) ) {
		$classes[] = 'dark-mode';
	}

	// Add a class for the sidebar position.
	$sidebar_position = get_theme_mod( 'aqualuxe_sidebar_position', 'right' );
	$classes[] = 'sidebar-' . $sidebar_position;

	// Add a class for the header layout.
	$header_layout = get_theme_mod( 'aqualuxe_header_layout', 'default' );
	$classes[] = 'header-' . $header_layout;

	// Add a class for the footer layout.
	$footer_layout = get_theme_mod( 'aqualuxe_footer_layout', 'default' );
	$classes[] = 'footer-' . $footer_layout;

	// Add a class if WooCommerce is active.
	if ( class_exists( 'WooCommerce' ) ) {
		$classes[] = 'woocommerce-active';
	}

	return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'aqualuxe_pingback_header' );

/**
 * Adds custom classes to the array of post classes.
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function aqualuxe_post_classes( $classes ) {
	// Add a class for featured posts.
	if ( is_sticky() ) {
		$classes[] = 'featured-post';
	}

	// Add a class for posts with thumbnails.
	if ( has_post_thumbnail() ) {
		$classes[] = 'has-thumbnail';
	}

	return $classes;
}
add_filter( 'post_class', 'aqualuxe_post_classes' );

/**
 * Changes the excerpt length.
 *
 * @param int $length Excerpt length.
 * @return int Modified excerpt length.
 */
function aqualuxe_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length' );

/**
 * Changes the excerpt more string.
 *
 * @param string $more The excerpt more string.
 * @return string
 */
function aqualuxe_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Implement custom excerpt function.
 *
 * @param int $limit Word limit for excerpt.
 * @return string
 */
function aqualuxe_custom_excerpt( $limit = 20 ) {
	$excerpt = get_the_excerpt();
	$excerpt = explode( ' ', $excerpt, $limit + 1 );
	
	if ( count( $excerpt ) > $limit ) {
		array_pop( $excerpt );
		$excerpt = implode( ' ', $excerpt ) . '&hellip;';
	} else {
		$excerpt = implode( ' ', $excerpt );
	}
	
	$excerpt = preg_replace( '`[[^]]*]`', '', $excerpt );
	
	return $excerpt;
}

/**
 * Add schema markup to the body element.
 *
 * @param array $attr Array of attributes.
 * @return array
 */
function aqualuxe_body_schema( $attr ) {
	$schema = 'https://schema.org/';
	
	// Check what type of page we are on.
	if ( is_home() || is_archive() || is_attachment() || is_tax() || is_single() ) {
		$type = 'Blog';
	} elseif ( is_search() ) {
		$type = 'SearchResultsPage';
	} else {
		$type = 'WebPage';
	}
	
	// Set up the attributes.
	$attr['itemscope'] = '';
	$attr['itemtype']  = $schema . $type;
	
	return $attr;
}
add_filter( 'aqualuxe_body_attributes', 'aqualuxe_body_schema' );

/**
 * Display the breadcrumbs.
 */
function aqualuxe_breadcrumbs() {
	// Check if breadcrumbs are enabled.
	$breadcrumbs_enabled = get_theme_mod( 'aqualuxe_breadcrumbs_enable', true );
	
	if ( ! $breadcrumbs_enabled ) {
		return;
	}
	
	// Check if we're on the front page.
	if ( is_front_page() ) {
		return;
	}
	
	// Set up the breadcrumbs.
	$breadcrumbs = array();
	
	// Add the home link.
	$breadcrumbs[] = array(
		'title' => esc_html__( 'Home', 'aqualuxe' ),
		'url'   => home_url( '/' ),
	);
	
	// Add the appropriate breadcrumbs based on the page type.
	if ( is_home() ) {
		// Blog page.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Blog', 'aqualuxe' ),
			'url'   => '',
		);
	} elseif ( is_category() ) {
		// Category archive.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Category', 'aqualuxe' ),
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => single_cat_title( '', false ),
			'url'   => '',
		);
	} elseif ( is_tag() ) {
		// Tag archive.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Tag', 'aqualuxe' ),
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => single_tag_title( '', false ),
			'url'   => '',
		);
	} elseif ( is_author() ) {
		// Author archive.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Author', 'aqualuxe' ),
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => get_the_author(),
			'url'   => '',
		);
	} elseif ( is_year() ) {
		// Year archive.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Year', 'aqualuxe' ),
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => get_the_date( 'Y' ),
			'url'   => '',
		);
	} elseif ( is_month() ) {
		// Month archive.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Month', 'aqualuxe' ),
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => get_the_date( 'F Y' ),
			'url'   => '',
		);
	} elseif ( is_day() ) {
		// Day archive.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Day', 'aqualuxe' ),
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => get_the_date(),
			'url'   => '',
		);
	} elseif ( is_tax() ) {
		// Custom taxonomy archive.
		$taxonomy = get_queried_object()->taxonomy;
		$term     = get_queried_object()->name;
		
		$breadcrumbs[] = array(
			'title' => $taxonomy,
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => $term,
			'url'   => '',
		);
	} elseif ( is_search() ) {
		// Search results.
		$breadcrumbs[] = array(
			'title' => esc_html__( 'Search', 'aqualuxe' ),
			'url'   => '',
		);
		$breadcrumbs[] = array(
			'title' => get_search_query(),
			'url'   => '',
		);
	} elseif ( is_404() ) {
		// 404 page.
		$breadcrumbs[] = array(
			'title' => esc_html__( '404', 'aqualuxe' ),
			'url'   => '',
		);
	} elseif ( is_singular( 'post' ) ) {
		// Single post.
		$categories = get_the_category();
		
		if ( ! empty( $categories ) ) {
			$category = $categories[0];
			
			$breadcrumbs[] = array(
				'title' => $category->name,
				'url'   => get_category_link( $category->term_id ),
			);
		}
		
		$breadcrumbs[] = array(
			'title' => get_the_title(),
			'url'   => '',
		);
	} elseif ( is_singular( 'page' ) ) {
		// Single page.
		$ancestors = get_post_ancestors( get_the_ID() );
		
		if ( ! empty( $ancestors ) ) {
			$ancestors = array_reverse( $ancestors );
			
			foreach ( $ancestors as $ancestor ) {
				$breadcrumbs[] = array(
					'title' => get_the_title( $ancestor ),
					'url'   => get_permalink( $ancestor ),
				);
			}
		}
		
		$breadcrumbs[] = array(
			'title' => get_the_title(),
			'url'   => '',
		);
	} elseif ( is_singular() ) {
		// Custom post type.
		$post_type = get_post_type_object( get_post_type() );
		
		$breadcrumbs[] = array(
			'title' => $post_type->labels->name,
			'url'   => get_post_type_archive_link( get_post_type() ),
		);
		
		$breadcrumbs[] = array(
			'title' => get_the_title(),
			'url'   => '',
		);
	}
	
	// Output the breadcrumbs.
	echo '<div class="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">';
	
	foreach ( $breadcrumbs as $key => $breadcrumb ) {
		echo '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
		
		if ( ! empty( $breadcrumb['url'] ) ) {
			echo '<a href="' . esc_url( $breadcrumb['url'] ) . '" itemprop="item">';
			echo '<span itemprop="name">' . esc_html( $breadcrumb['title'] ) . '</span>';
			echo '</a>';
		} else {
			echo '<span itemprop="name">' . esc_html( $breadcrumb['title'] ) . '</span>';
		}
		
		echo '<meta itemprop="position" content="' . esc_attr( $key + 1 ) . '" />';
		echo '</span>';
		
		if ( $key < count( $breadcrumbs ) - 1 ) {
			echo '<span class="breadcrumb-separator">/</span>';
		}
	}
	
	echo '</div>';
}

/**
 * Display pagination.
 */
function aqualuxe_pagination() {
	$pagination_type = get_theme_mod( 'aqualuxe_pagination_type', 'numbered' );
	
	if ( 'numbered' === $pagination_type ) {
		// Numbered pagination.
		the_posts_pagination( array(
			'mid_size'  => 2,
			'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg><span class="screen-reader-text">' . esc_html__( 'Previous page', 'aqualuxe' ) . '</span>',
			'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'aqualuxe' ) . '</span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
		) );
	} else {
		// Previous/next pagination.
		the_posts_navigation( array(
			'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg> ' . esc_html__( 'Older posts', 'aqualuxe' ),
			'next_text' => esc_html__( 'Newer posts', 'aqualuxe' ) . ' <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
		) );
	}
}

/**
 * Display post navigation.
 */
function aqualuxe_post_navigation() {
	the_post_navigation( array(
		'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous Post', 'aqualuxe' ) . '</span><span class="nav-title">%title</span>',
		'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next Post', 'aqualuxe' ) . '</span><span class="nav-title">%title</span>',
	) );
}

/**
 * Display social icons.
 */
function aqualuxe_social_icons() {
	// Get social media links from theme options.
	$facebook  = get_theme_mod( 'aqualuxe_facebook_link' );
	$twitter   = get_theme_mod( 'aqualuxe_twitter_link' );
	$instagram = get_theme_mod( 'aqualuxe_instagram_link' );
	$linkedin  = get_theme_mod( 'aqualuxe_linkedin_link' );
	$youtube   = get_theme_mod( 'aqualuxe_youtube_link' );
	$pinterest = get_theme_mod( 'aqualuxe_pinterest_link' );
	
	// Check if any social media links are set.
	if ( ! $facebook && ! $twitter && ! $instagram && ! $linkedin && ! $youtube && ! $pinterest ) {
		return;
	}
	
	// Output the social icons.
	echo '<div class="social-icons">';
	
	if ( $facebook ) {
		echo '<a href="' . esc_url( $facebook ) . '" target="_blank" rel="noopener noreferrer" class="social-icon facebook" aria-label="' . esc_attr__( 'Facebook', 'aqualuxe' ) . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>';
		echo '</a>';
	}
	
	if ( $twitter ) {
		echo '<a href="' . esc_url( $twitter ) . '" target="_blank" rel="noopener noreferrer" class="social-icon twitter" aria-label="' . esc_attr__( 'Twitter', 'aqualuxe' ) . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>';
		echo '</a>';
	}
	
	if ( $instagram ) {
		echo '<a href="' . esc_url( $instagram ) . '" target="_blank" rel="noopener noreferrer" class="social-icon instagram" aria-label="' . esc_attr__( 'Instagram', 'aqualuxe' ) . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>';
		echo '</a>';
	}
	
	if ( $linkedin ) {
		echo '<a href="' . esc_url( $linkedin ) . '" target="_blank" rel="noopener noreferrer" class="social-icon linkedin" aria-label="' . esc_attr__( 'LinkedIn', 'aqualuxe' ) . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>';
		echo '</a>';
	}
	
	if ( $youtube ) {
		echo '<a href="' . esc_url( $youtube ) . '" target="_blank" rel="noopener noreferrer" class="social-icon youtube" aria-label="' . esc_attr__( 'YouTube', 'aqualuxe' ) . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>';
		echo '</a>';
	}
	
	if ( $pinterest ) {
		echo '<a href="' . esc_url( $pinterest ) . '" target="_blank" rel="noopener noreferrer" class="social-icon pinterest" aria-label="' . esc_attr__( 'Pinterest', 'aqualuxe' ) . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 12a4 4 0 1 0 8 0 4 4 0 0 0-8 0z"></path><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="M20 12h2"></path><path d="M2 12h2"></path><path d="M17.5 6.5l1.4-1.4"></path><path d="M5.1 17.9l1.4-1.4"></path><path d="M17.5 17.5l1.4 1.4"></path><path d="M5.1 6.1l1.4 1.4"></path></svg>';
		echo '</a>';
	}
	
	echo '</div>';
}

/**
 * Display related posts.
 */
function aqualuxe_related_posts() {
	// Check if related posts are enabled.
	$related_posts_enabled = get_theme_mod( 'aqualuxe_related_posts_enable', true );
	
	if ( ! $related_posts_enabled ) {
		return;
	}
	
	// Get the current post ID.
	$post_id = get_the_ID();
	
	// Get the categories of the current post.
	$categories = get_the_category( $post_id );
	
	// If there are no categories, return.
	if ( empty( $categories ) ) {
		return;
	}
	
	// Get the IDs of the categories.
	$category_ids = array();
	
	foreach ( $categories as $category ) {
		$category_ids[] = $category->term_id;
	}
	
	// Set up the query arguments.
	$args = array(
		'category__in'        => $category_ids,
		'post__not_in'        => array( $post_id ),
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => 1,
	);
	
	// Get the related posts.
	$related_query = new WP_Query( $args );
	
	// If there are no related posts, return.
	if ( ! $related_query->have_posts() ) {
		return;
	}
	
	// Output the related posts.
	echo '<div class="related-posts">';
	echo '<h2 class="related-posts-title">' . esc_html__( 'Related Posts', 'aqualuxe' ) . '</h2>';
	echo '<div class="related-posts-grid">';
	
	while ( $related_query->have_posts() ) {
		$related_query->the_post();
		
		echo '<article class="related-post">';
		
		if ( has_post_thumbnail() ) {
			echo '<div class="related-post-thumbnail">';
			echo '<a href="' . esc_url( get_permalink() ) . '">';
			the_post_thumbnail( 'aqualuxe-card' );
			echo '</a>';
			echo '</div>';
		}
		
		echo '<div class="related-post-content">';
		echo '<h3 class="related-post-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h3>';
		echo '<div class="related-post-meta">';
		echo '<span class="related-post-date">' . esc_html( get_the_date() ) . '</span>';
		echo '</div>';
		echo '</div>';
		
		echo '</article>';
	}
	
	echo '</div>';
	echo '</div>';
	
	// Reset the post data.
	wp_reset_postdata();
}

/**
 * Display author bio.
 */
function aqualuxe_author_bio() {
	// Check if author bio is enabled.
	$author_bio_enabled = get_theme_mod( 'aqualuxe_author_bio_enable', true );
	
	if ( ! $author_bio_enabled ) {
		return;
	}
	
	// Get the author ID.
	$author_id = get_the_author_meta( 'ID' );
	
	// Get the author bio.
	$author_bio = get_the_author_meta( 'description', $author_id );
	
	// If there is no author bio, return.
	if ( empty( $author_bio ) ) {
		return;
	}
	
	// Output the author bio.
	echo '<div class="author-bio">';
	echo '<div class="author-avatar">';
	echo get_avatar( $author_id, 100 );
	echo '</div>';
	echo '<div class="author-content">';
	echo '<h3 class="author-name">' . esc_html__( 'About', 'aqualuxe' ) . ' ' . esc_html( get_the_author_meta( 'display_name', $author_id ) ) . '</h3>';
	echo '<div class="author-description">' . wp_kses_post( $author_bio ) . '</div>';
	echo '<a href="' . esc_url( get_author_posts_url( $author_id ) ) . '" class="author-link">' . esc_html__( 'View all posts by', 'aqualuxe' ) . ' ' . esc_html( get_the_author_meta( 'display_name', $author_id ) ) . '</a>';
	echo '</div>';
	echo '</div>';
}

/**
 * Display dark mode toggle.
 */
function aqualuxe_dark_mode_toggle() {
	// Check if dark mode is enabled.
	$dark_mode_enabled = get_theme_mod( 'aqualuxe_dark_mode_enable', true );
	
	if ( ! $dark_mode_enabled ) {
		return;
	}
	
	// Output the dark mode toggle.
	echo '<button class="dark-mode-toggle" aria-label="' . esc_attr__( 'Toggle dark mode', 'aqualuxe' ) . '" aria-pressed="false">';
	echo '<span class="dark-mode-toggle-icon light">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>';
	echo '</span>';
	echo '<span class="dark-mode-toggle-icon dark">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>';
	echo '</span>';
	echo '</button>';
}

/**
 * Add Open Graph meta tags.
 */
function aqualuxe_add_opengraph_tags() {
	// Check if Open Graph tags are enabled.
	$opengraph_enabled = get_theme_mod( 'aqualuxe_opengraph_enable', true );
	
	if ( ! $opengraph_enabled ) {
		return;
	}
	
	// Get the current page URL.
	$url = esc_url( get_permalink() );
	
	// Get the site name.
	$site_name = esc_attr( get_bloginfo( 'name' ) );
	
	// Get the page title.
	$title = esc_attr( wp_get_document_title() );
	
	// Get the page description.
	$description = esc_attr( get_bloginfo( 'description' ) );
	
	if ( is_singular() ) {
		// If it's a singular page, use the excerpt as the description.
		$excerpt = get_the_excerpt();
		
		if ( ! empty( $excerpt ) ) {
			$description = esc_attr( wp_strip_all_tags( $excerpt ) );
		}
	}
	
	// Get the featured image.
	$image = '';
	
	if ( is_singular() && has_post_thumbnail() ) {
		$image_id = get_post_thumbnail_id();
		$image_data = wp_get_attachment_image_src( $image_id, 'large' );
		
		if ( $image_data ) {
			$image = esc_url( $image_data[0] );
		}
	}
	
	// Output the Open Graph tags.
	echo '<meta property="og:url" content="' . $url . '" />' . "\n";
	echo '<meta property="og:type" content="website" />' . "\n";
	echo '<meta property="og:title" content="' . $title . '" />' . "\n";
	echo '<meta property="og:description" content="' . $description . '" />' . "\n";
	echo '<meta property="og:site_name" content="' . $site_name . '" />' . "\n";
	
	if ( ! empty( $image ) ) {
		echo '<meta property="og:image" content="' . $image . '" />' . "\n";
	}
	
	// Add Twitter Card tags.
	echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
	echo '<meta name="twitter:title" content="' . $title . '" />' . "\n";
	echo '<meta name="twitter:description" content="' . $description . '" />' . "\n";
	
	if ( ! empty( $image ) ) {
		echo '<meta name="twitter:image" content="' . $image . '" />' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_add_opengraph_tags', 5 );