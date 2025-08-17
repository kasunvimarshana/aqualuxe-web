<?php
/**
 * Theme Hooks
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme Hook Points
 *
 * This file defines all the hook points used throughout the theme.
 * These hooks allow for modular customization without modifying core files.
 */

/**
 * Header Hooks
 */

/**
 * Before header hook
 * 
 * @since 1.0.0
 */
function aqualuxe_before_header() {
	/**
	 * Hook: aqualuxe_before_header
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_before_header' );
}

/**
 * Header hook
 * 
 * @since 1.0.0
 */
function aqualuxe_header() {
	/**
	 * Hook: aqualuxe_header
	 *
	 * @hooked aqualuxe_header_content - 10
	 */
	do_action( 'aqualuxe_header' );
}

/**
 * After header hook
 * 
 * @since 1.0.0
 */
function aqualuxe_after_header() {
	/**
	 * Hook: aqualuxe_after_header
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_after_header' );
}

/**
 * Header content hook
 * 
 * @since 1.0.0
 */
function aqualuxe_header_content() {
	get_template_part( 'templates/parts/header/navigation' );
}
add_action( 'aqualuxe_header', 'aqualuxe_header_content' );

/**
 * Footer Hooks
 */

/**
 * Before footer hook
 * 
 * @since 1.0.0
 */
function aqualuxe_before_footer() {
	/**
	 * Hook: aqualuxe_before_footer
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_before_footer' );
}

/**
 * Footer hook
 * 
 * @since 1.0.0
 */
function aqualuxe_footer() {
	/**
	 * Hook: aqualuxe_footer
	 *
	 * @hooked aqualuxe_footer_widgets - 10
	 * @hooked aqualuxe_footer_copyright - 20
	 */
	do_action( 'aqualuxe_footer' );
}

/**
 * After footer hook
 * 
 * @since 1.0.0
 */
function aqualuxe_after_footer() {
	/**
	 * Hook: aqualuxe_after_footer
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_after_footer' );
}

/**
 * Footer widgets hook
 * 
 * @since 1.0.0
 */
function aqualuxe_footer_widgets() {
	get_template_part( 'templates/parts/footer/widgets' );
}
add_action( 'aqualuxe_footer', 'aqualuxe_footer_widgets', 10 );

/**
 * Footer copyright hook
 * 
 * @since 1.0.0
 */
function aqualuxe_footer_copyright() {
	get_template_part( 'templates/parts/footer/copyright' );
}
add_action( 'aqualuxe_footer', 'aqualuxe_footer_copyright', 20 );

/**
 * Content Hooks
 */

/**
 * Before content hook
 * 
 * @since 1.0.0
 */
function aqualuxe_before_content() {
	/**
	 * Hook: aqualuxe_before_content
	 *
	 * @hooked aqualuxe_page_header - 10
	 */
	do_action( 'aqualuxe_before_content' );
}

/**
 * Content hook
 * 
 * @since 1.0.0
 */
function aqualuxe_content() {
	/**
	 * Hook: aqualuxe_content
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_content' );
}

/**
 * After content hook
 * 
 * @since 1.0.0
 */
function aqualuxe_after_content() {
	/**
	 * Hook: aqualuxe_after_content
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_after_content' );
}

/**
 * Page header hook
 * 
 * @since 1.0.0
 */
function aqualuxe_page_header() {
	if ( is_front_page() ) {
		return;
	}
	
	if ( is_singular() ) {
		get_template_part( 'templates/parts/content/page-header' );
	} elseif ( is_archive() || is_search() ) {
		get_template_part( 'templates/parts/content/archive-header' );
	}
}
add_action( 'aqualuxe_before_content', 'aqualuxe_page_header', 10 );

/**
 * Entry Hooks
 */

/**
 * Before entry hook
 * 
 * @since 1.0.0
 */
function aqualuxe_before_entry() {
	/**
	 * Hook: aqualuxe_before_entry
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_before_entry' );
}

/**
 * Entry header hook
 * 
 * @since 1.0.0
 */
function aqualuxe_entry_header() {
	/**
	 * Hook: aqualuxe_entry_header
	 *
	 * @hooked aqualuxe_post_thumbnail - 10
	 * @hooked aqualuxe_post_title - 20
	 * @hooked aqualuxe_post_meta - 30
	 */
	do_action( 'aqualuxe_entry_header' );
}

/**
 * Entry content hook
 * 
 * @since 1.0.0
 */
function aqualuxe_entry_content() {
	/**
	 * Hook: aqualuxe_entry_content
	 *
	 * @hooked aqualuxe_post_content - 10
	 */
	do_action( 'aqualuxe_entry_content' );
}

/**
 * Entry footer hook
 * 
 * @since 1.0.0
 */
function aqualuxe_entry_footer() {
	/**
	 * Hook: aqualuxe_entry_footer
	 *
	 * @hooked aqualuxe_post_tags - 10
	 * @hooked aqualuxe_post_navigation - 20
	 * @hooked aqualuxe_post_author - 30
	 * @hooked aqualuxe_related_posts - 40
	 */
	do_action( 'aqualuxe_entry_footer' );
}

/**
 * After entry hook
 * 
 * @since 1.0.0
 */
function aqualuxe_after_entry() {
	/**
	 * Hook: aqualuxe_after_entry
	 *
	 * @hooked aqualuxe_post_comments - 10
	 */
	do_action( 'aqualuxe_after_entry' );
}

/**
 * Post thumbnail hook
 * 
 * @since 1.0.0
 */
function aqualuxe_post_thumbnail() {
	if ( ! has_post_thumbnail() || ! get_theme_mod( 'aqualuxe_show_featured_image', true ) ) {
		return;
	}
	
	echo '<div class="post-thumbnail">';
	the_post_thumbnail( 'aqualuxe-featured', array(
		'class' => 'w-full h-auto rounded',
		'alt'   => get_the_title(),
	) );
	echo '</div>';
}
add_action( 'aqualuxe_entry_header', 'aqualuxe_post_thumbnail', 10 );

/**
 * Post title hook
 * 
 * @since 1.0.0
 */
function aqualuxe_post_title() {
	if ( is_singular() ) {
		the_title( '<h1 class="entry-title">', '</h1>' );
	} else {
		the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
	}
}
add_action( 'aqualuxe_entry_header', 'aqualuxe_post_title', 20 );

/**
 * Post meta hook
 * 
 * @since 1.0.0
 */
function aqualuxe_post_meta() {
	if ( ! get_theme_mod( 'aqualuxe_show_post_meta', true ) ) {
		return;
	}
	
	echo '<div class="entry-meta">';
	
	// Author
	echo '<span class="author">';
	echo esc_html__( 'By ', 'aqualuxe' );
	echo '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>';
	echo '</span>';
	
	// Date
	echo '<span class="date">';
	echo esc_html__( 'On ', 'aqualuxe' );
	echo '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
	echo '</span>';
	
	// Categories
	$categories = get_the_category();
	if ( ! empty( $categories ) ) {
		echo '<span class="categories">';
		echo esc_html__( 'In ', 'aqualuxe' );
		$category_links = array();
		foreach ( $categories as $category ) {
			$category_links[] = '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
		}
		echo implode( ', ', $category_links );
		echo '</span>';
	}
	
	echo '</div>';
}
add_action( 'aqualuxe_entry_header', 'aqualuxe_post_meta', 30 );

/**
 * Post content hook
 * 
 * @since 1.0.0
 */
function aqualuxe_post_content() {
	if ( is_singular() ) {
		echo '<div class="entry-content">';
		the_content();
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
			'after'  => '</div>',
		) );
		echo '</div>';
	} else {
		echo '<div class="entry-summary">';
		the_excerpt();
		echo '<a href="' . esc_url( get_permalink() ) . '" class="read-more">' . esc_html__( 'Read More', 'aqualuxe' ) . '</a>';
		echo '</div>';
	}
}
add_action( 'aqualuxe_entry_content', 'aqualuxe_post_content', 10 );

/**
 * Post tags hook
 * 
 * @since 1.0.0
 */
function aqualuxe_post_tags() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	
	$tags = get_the_tags();
	if ( ! $tags ) {
		return;
	}
	
	echo '<div class="entry-tags">';
	echo '<span class="tags-title">' . esc_html__( 'Tags: ', 'aqualuxe' ) . '</span>';
	$tag_links = array();
	foreach ( $tags as $tag ) {
		$tag_links[] = '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a>';
	}
	echo implode( ', ', $tag_links );
	echo '</div>';
}
add_action( 'aqualuxe_entry_footer', 'aqualuxe_post_tags', 10 );

/**
 * Post navigation hook
 * 
 * @since 1.0.0
 */
function aqualuxe_post_navigation() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	
	the_post_navigation( array(
		'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
		'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
	) );
}
add_action( 'aqualuxe_entry_footer', 'aqualuxe_post_navigation', 20 );

/**
 * Post author hook
 * 
 * @since 1.0.0
 */
function aqualuxe_post_author() {
	if ( ! is_singular( 'post' ) || ! get_theme_mod( 'aqualuxe_show_author_bio', true ) ) {
		return;
	}
	
	$author_id = get_the_author_meta( 'ID' );
	$author_bio = get_the_author_meta( 'description' );
	
	if ( ! $author_bio ) {
		return;
	}
	
	echo '<div class="author-bio">';
	echo '<div class="author-avatar">';
	echo get_avatar( $author_id, 80 );
	echo '</div>';
	echo '<div class="author-content">';
	echo '<h3 class="author-title">' . esc_html__( 'About', 'aqualuxe' ) . ' ' . esc_html( get_the_author() ) . '</h3>';
	echo '<div class="author-description">' . wp_kses_post( $author_bio ) . '</div>';
	echo '<a class="author-link" href="' . esc_url( get_author_posts_url( $author_id ) ) . '">' . esc_html__( 'View all posts by', 'aqualuxe' ) . ' ' . esc_html( get_the_author() ) . '</a>';
	echo '</div>';
	echo '</div>';
}
add_action( 'aqualuxe_entry_footer', 'aqualuxe_post_author', 30 );

/**
 * Related posts hook
 * 
 * @since 1.0.0
 */
function aqualuxe_related_posts() {
	if ( ! is_singular( 'post' ) || ! get_theme_mod( 'aqualuxe_show_related_posts', true ) ) {
		return;
	}
	
	$categories = get_the_category();
	if ( empty( $categories ) ) {
		return;
	}
	
	$category_ids = array();
	foreach ( $categories as $category ) {
		$category_ids[] = $category->term_id;
	}
	
	$args = array(
		'category__in'        => $category_ids,
		'post__not_in'        => array( get_the_ID() ),
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => 1,
	);
	
	$related_query = new WP_Query( $args );
	
	if ( ! $related_query->have_posts() ) {
		return;
	}
	
	echo '<div class="related-posts">';
	echo '<h3 class="related-title">' . esc_html__( 'Related Posts', 'aqualuxe' ) . '</h3>';
	echo '<div class="related-posts-grid">';
	
	while ( $related_query->have_posts() ) {
		$related_query->the_post();
		
		echo '<article class="related-post">';
		if ( has_post_thumbnail() ) {
			echo '<a href="' . esc_url( get_permalink() ) . '" class="related-thumbnail">';
			the_post_thumbnail( 'aqualuxe-card' );
			echo '</a>';
		}
		echo '<h4 class="related-post-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h4>';
		echo '<div class="related-post-meta">' . esc_html( get_the_date() ) . '</div>';
		echo '</article>';
	}
	
	echo '</div>';
	echo '</div>';
	
	wp_reset_postdata();
}
add_action( 'aqualuxe_entry_footer', 'aqualuxe_related_posts', 40 );

/**
 * Post comments hook
 * 
 * @since 1.0.0
 */
function aqualuxe_post_comments() {
	if ( ! is_singular() ) {
		return;
	}
	
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}
add_action( 'aqualuxe_after_entry', 'aqualuxe_post_comments', 10 );

/**
 * Sidebar Hooks
 */

/**
 * Before sidebar hook
 * 
 * @since 1.0.0
 */
function aqualuxe_before_sidebar() {
	/**
	 * Hook: aqualuxe_before_sidebar
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_before_sidebar' );
}

/**
 * Sidebar hook
 * 
 * @since 1.0.0
 */
function aqualuxe_sidebar() {
	/**
	 * Hook: aqualuxe_sidebar
	 *
	 * @hooked aqualuxe_get_sidebar - 10
	 */
	do_action( 'aqualuxe_sidebar' );
}

/**
 * After sidebar hook
 * 
 * @since 1.0.0
 */
function aqualuxe_after_sidebar() {
	/**
	 * Hook: aqualuxe_after_sidebar
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_after_sidebar' );
}

/**
 * Get sidebar hook
 * 
 * @since 1.0.0
 */
function aqualuxe_get_sidebar() {
	get_sidebar();
}
add_action( 'aqualuxe_sidebar', 'aqualuxe_get_sidebar', 10 );

/**
 * WooCommerce Hooks
 */
if ( class_exists( 'WooCommerce' ) ) {
	
	/**
	 * Before shop hook
	 * 
	 * @since 1.0.0
	 */
	function aqualuxe_before_shop() {
		/**
		 * Hook: aqualuxe_before_shop
		 *
		 * @hooked none - 10
		 */
		do_action( 'aqualuxe_before_shop' );
	}
	
	/**
	 * Shop hook
	 * 
	 * @since 1.0.0
	 */
	function aqualuxe_shop() {
		/**
		 * Hook: aqualuxe_shop
		 *
		 * @hooked none - 10
		 */
		do_action( 'aqualuxe_shop' );
	}
	
	/**
	 * After shop hook
	 * 
	 * @since 1.0.0
	 */
	function aqualuxe_after_shop() {
		/**
		 * Hook: aqualuxe_after_shop
		 *
		 * @hooked none - 10
		 */
		do_action( 'aqualuxe_after_shop' );
	}
	
	/**
	 * Before product hook
	 * 
	 * @since 1.0.0
	 */
	function aqualuxe_before_product() {
		/**
		 * Hook: aqualuxe_before_product
		 *
		 * @hooked none - 10
		 */
		do_action( 'aqualuxe_before_product' );
	}
	
	/**
	 * Product hook
	 * 
	 * @since 1.0.0
	 */
	function aqualuxe_product() {
		/**
		 * Hook: aqualuxe_product
		 *
		 * @hooked none - 10
		 */
		do_action( 'aqualuxe_product' );
	}
	
	/**
	 * After product hook
	 * 
	 * @since 1.0.0
	 */
	function aqualuxe_after_product() {
		/**
		 * Hook: aqualuxe_after_product
		 *
		 * @hooked none - 10
		 */
		do_action( 'aqualuxe_after_product' );
	}
	
	/**
	 * Quick view hook
	 * 
	 * @since 1.0.0
	 */
	function aqualuxe_quick_view() {
		/**
		 * Hook: aqualuxe_quick_view
		 *
		 * @hooked aqualuxe_quick_view_content - 10
		 */
		do_action( 'aqualuxe_quick_view' );
	}
	
	/**
	 * Quick view content hook
	 * 
	 * @since 1.0.0
	 */
	function aqualuxe_quick_view_content() {
		if ( ! get_theme_mod( 'aqualuxe_show_quick_view', true ) ) {
			return;
		}
		
		// Quick view content will be loaded via AJAX
	}
	add_action( 'aqualuxe_quick_view', 'aqualuxe_quick_view_content', 10 );
	
	/**
	 * Add to wishlist hook
	 * 
	 * @since 1.0.0
	 */
	function aqualuxe_add_to_wishlist() {
		/**
		 * Hook: aqualuxe_add_to_wishlist
		 *
		 * @hooked aqualuxe_wishlist_button - 10
		 */
		do_action( 'aqualuxe_add_to_wishlist' );
	}
	
	/**
	 * Wishlist button hook
	 * 
	 * @since 1.0.0
	 */
	function aqualuxe_wishlist_button() {
		if ( ! get_theme_mod( 'aqualuxe_show_wishlist', true ) ) {
			return;
		}
		
		// Wishlist button implementation
		echo '<button class="wishlist-button" data-product-id="' . esc_attr( get_the_ID() ) . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" /></svg>';
		echo '<span class="screen-reader-text">' . esc_html__( 'Add to Wishlist', 'aqualuxe' ) . '</span>';
		echo '</button>';
	}
	add_action( 'aqualuxe_add_to_wishlist', 'aqualuxe_wishlist_button', 10 );
}

/**
 * Theme Hooks
 */

/**
 * Before site hook
 * 
 * @since 1.0.0
 */
function aqualuxe_before_site() {
	/**
	 * Hook: aqualuxe_before_site
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_before_site' );
}

/**
 * After site hook
 * 
 * @since 1.0.0
 */
function aqualuxe_after_site() {
	/**
	 * Hook: aqualuxe_after_site
	 *
	 * @hooked aqualuxe_back_to_top - 10
	 */
	do_action( 'aqualuxe_after_site' );
}

/**
 * Back to top hook
 * 
 * @since 1.0.0
 */
function aqualuxe_back_to_top() {
	echo '<a href="#page" class="back-to-top" aria-label="' . esc_attr__( 'Back to top', 'aqualuxe' ) . '">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M11.47 7.72a.75.75 0 011.06 0l7.5 7.5a.75.75 0 11-1.06 1.06L12 9.31l-6.97 6.97a.75.75 0 01-1.06-1.06l7.5-7.5z" clip-rule="evenodd" /></svg>';
	echo '</a>';
}
add_action( 'aqualuxe_after_site', 'aqualuxe_back_to_top', 10 );

/**
 * Head hooks
 */

/**
 * Before head hook
 * 
 * @since 1.0.0
 */
function aqualuxe_before_head() {
	/**
	 * Hook: aqualuxe_before_head
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_before_head' );
}

/**
 * Head hook
 * 
 * @since 1.0.0
 */
function aqualuxe_head() {
	/**
	 * Hook: aqualuxe_head
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_head' );
}

/**
 * After head hook
 * 
 * @since 1.0.0
 */
function aqualuxe_after_head() {
	/**
	 * Hook: aqualuxe_after_head
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_after_head' );
}

/**
 * Body hooks
 */

/**
 * Before body hook
 * 
 * @since 1.0.0
 */
function aqualuxe_before_body() {
	/**
	 * Hook: aqualuxe_before_body
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_before_body' );
}

/**
 * Body hook
 * 
 * @since 1.0.0
 */
function aqualuxe_body() {
	/**
	 * Hook: aqualuxe_body
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_body' );
}

/**
 * After body hook
 * 
 * @since 1.0.0
 */
function aqualuxe_after_body() {
	/**
	 * Hook: aqualuxe_after_body
	 *
	 * @hooked none - 10
	 */
	do_action( 'aqualuxe_after_body' );
}

/**
 * Skip link hook
 * 
 * @since 1.0.0
 */
function aqualuxe_skip_link() {
	echo '<a class="skip-link screen-reader-text" href="#content">' . esc_html__( 'Skip to content', 'aqualuxe' ) . '</a>';
}
add_action( 'aqualuxe_before_header', 'aqualuxe_skip_link', 5 );

/**
 * Filter hooks
 */

/**
 * Body class filter
 *
 * @param array $classes Body classes.
 * @return array Modified body classes.
 */
function aqualuxe_body_class_filter( $classes ) {
	// Add a class if WooCommerce is active
	if ( class_exists( 'WooCommerce' ) ) {
		$classes[] = 'woocommerce-active';
	} else {
		$classes[] = 'woocommerce-inactive';
	}
	
	// Add a class for the color scheme
	$color_scheme = get_theme_mod( 'aqualuxe_color_scheme', 'light' );
	$classes[] = 'color-scheme-' . sanitize_html_class( $color_scheme );
	
	// Add a class for the layout
	$layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );
	$classes[] = 'layout-' . sanitize_html_class( $layout );
	
	// Add a class if we're on a single post or page
	if ( is_singular() && ! is_front_page() ) {
		$classes[] = 'singular';
	}
	
	return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_class_filter' );

/**
 * Post class filter
 *
 * @param array $classes Post classes.
 * @return array Modified post classes.
 */
function aqualuxe_post_class_filter( $classes ) {
	// Add a class for the post format
	$post_format = get_post_format() ? get_post_format() : 'standard';
	$classes[] = 'format-' . sanitize_html_class( $post_format );
	
	return $classes;
}
add_filter( 'post_class', 'aqualuxe_post_class_filter' );

/**
 * Excerpt length filter
 *
 * @param int $length Excerpt length.
 * @return int Modified excerpt length.
 */
function aqualuxe_excerpt_length_filter( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length_filter' );

/**
 * Excerpt more filter
 *
 * @param string $more Excerpt more string.
 * @return string Modified excerpt more string.
 */
function aqualuxe_excerpt_more_filter( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more_filter' );

/**
 * Comment form fields filter
 *
 * @param array $fields Comment form fields.
 * @return array Modified comment form fields.
 */
function aqualuxe_comment_form_fields_filter( $fields ) {
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$aria_req  = ( $req ? " aria-required='true'" : '' );
	
	$fields['author'] = '<p class="comment-form-author">' .
		'<label for="author">' . esc_html__( 'Name', 'aqualuxe' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . ' />' .
		'</p>';
	
	$fields['email'] = '<p class="comment-form-email">' .
		'<label for="email">' . esc_html__( 'Email', 'aqualuxe' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
		'<input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . ' />' .
		'</p>';
	
	$fields['url'] = '<p class="comment-form-url">' .
		'<label for="url">' . esc_html__( 'Website', 'aqualuxe' ) . '</label> ' .
		'<input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" />' .
		'</p>';
	
	return $fields;
}
add_filter( 'comment_form_default_fields', 'aqualuxe_comment_form_fields_filter' );

/**
 * Comment form defaults filter
 *
 * @param array $defaults Comment form defaults.
 * @return array Modified comment form defaults.
 */
function aqualuxe_comment_form_defaults_filter( $defaults ) {
	$defaults['comment_field'] = '<p class="comment-form-comment">' .
		'<label for="comment">' . esc_html__( 'Comment', 'aqualuxe' ) . ' <span class="required">*</span></label> ' .
		'<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" aria-required="true" required="required"></textarea>' .
		'</p>';
	
	$defaults['title_reply'] = esc_html__( 'Leave a Comment', 'aqualuxe' );
	$defaults['title_reply_to'] = esc_html__( 'Leave a Reply to %s', 'aqualuxe' );
	$defaults['cancel_reply_link'] = esc_html__( 'Cancel Reply', 'aqualuxe' );
	$defaults['label_submit'] = esc_html__( 'Post Comment', 'aqualuxe' );
	
	return $defaults;
}
add_filter( 'comment_form_defaults', 'aqualuxe_comment_form_defaults_filter' );

/**
 * WooCommerce hooks
 */
if ( class_exists( 'WooCommerce' ) ) {
	
	/**
	 * WooCommerce products per page filter
	 *
	 * @param int $products_per_page Products per page.
	 * @return int Modified products per page.
	 */
	function aqualuxe_products_per_page_filter( $products_per_page ) {
		return get_theme_mod( 'aqualuxe_products_per_page', 12 );
	}
	add_filter( 'loop_shop_per_page', 'aqualuxe_products_per_page_filter', 20 );
	
	/**
	 * WooCommerce related products args filter
	 *
	 * @param array $args Related products args.
	 * @return array Modified related products args.
	 */
	function aqualuxe_related_products_args_filter( $args ) {
		$args['posts_per_page'] = get_theme_mod( 'aqualuxe_related_products', 4 );
		$args['columns'] = get_theme_mod( 'aqualuxe_product_columns', 4 );
		
		return $args;
	}
	add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_related_products_args_filter' );
	
	/**
	 * WooCommerce product columns filter
	 *
	 * @param int $columns Product columns.
	 * @return int Modified product columns.
	 */
	function aqualuxe_product_columns_filter( $columns ) {
		return get_theme_mod( 'aqualuxe_product_columns', 4 );
	}
	add_filter( 'loop_shop_columns', 'aqualuxe_product_columns_filter' );
}