<?php
/**
 * Helper functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if WooCommerce is active.
 *
 * @return bool True if WooCommerce is active, false otherwise.
 */
function aqualuxe_is_woocommerce_active() {
	return class_exists( 'WooCommerce' );
}

/**
 * Get the asset URL.
 *
 * @param string $path The asset path.
 * @return string The asset URL.
 */
function aqualuxe_get_asset_url( $path ) {
	return AQUALUXE_THEME_URI . 'assets/dist/' . $path;
}

/**
 * Get the asset version.
 *
 * @param string $path The asset path.
 * @return string The asset version.
 */
function aqualuxe_get_asset_version( $path ) {
	$manifest_path = AQUALUXE_THEME_DIR . 'assets/dist/mix-manifest.json';
	
	if ( file_exists( $manifest_path ) ) {
		$manifest = json_decode( file_get_contents( $manifest_path ), true );
		$path = '/' . ltrim( $path, '/' );
		
		if ( isset( $manifest[ $path ] ) ) {
			return str_replace( $path . '?id=', '', $manifest[ $path ] );
		}
	}
	
	return AQUALUXE_VERSION;
}

/**
 * Get the SVG icon.
 *
 * @param string $icon The icon name.
 * @return string The SVG icon.
 */
function aqualuxe_get_icon( $icon ) {
	$icons = array(
		'search' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
		'cart' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>',
		'user' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
		'heart' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>',
		'menu' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>',
		'close' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
		'chevron-down' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>',
		'chevron-up' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>',
		'chevron-left' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
		'chevron-right' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
		'arrow-up' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>',
		'arrow-down' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>',
		'arrow-left' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
		'arrow-right' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
		'phone' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>',
		'mail' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>',
		'map-pin' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
		'sun' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>',
		'moon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>',
		'info' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
		'alert' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
		'check' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>',
		'x' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
		'plus' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>',
		'minus' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line></svg>',
	);

	return isset( $icons[ $icon ] ) ? $icons[ $icon ] : '';
}

/**
 * Get the image URL.
 *
 * @param int    $attachment_id The attachment ID.
 * @param string $size The image size.
 * @return string The image URL.
 */
function aqualuxe_get_image_url( $attachment_id, $size = 'full' ) {
	if ( ! $attachment_id ) {
		return '';
	}

	$image = wp_get_attachment_image_src( $attachment_id, $size );
	return $image ? $image[0] : '';
}

/**
 * Get the image alt text.
 *
 * @param int $attachment_id The attachment ID.
 * @return string The image alt text.
 */
function aqualuxe_get_image_alt( $attachment_id ) {
	if ( ! $attachment_id ) {
		return '';
	}

	return get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
}

/**
 * Get the responsive image.
 *
 * @param int    $attachment_id The attachment ID.
 * @param string $size The image size.
 * @param array  $attr The image attributes.
 * @return string The responsive image.
 */
function aqualuxe_get_responsive_image( $attachment_id, $size = 'full', $attr = array() ) {
	if ( ! $attachment_id ) {
		return '';
	}

	return wp_get_attachment_image( $attachment_id, $size, false, $attr );
}

/**
 * Get the post thumbnail with responsive image.
 *
 * @param int    $post_id The post ID.
 * @param string $size The image size.
 * @param array  $attr The image attributes.
 * @return string The post thumbnail.
 */
function aqualuxe_get_post_thumbnail( $post_id = null, $size = 'full', $attr = array() ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	if ( ! has_post_thumbnail( $post_id ) ) {
		return '';
	}

	return get_the_post_thumbnail( $post_id, $size, $attr );
}

/**
 * Get the post excerpt.
 *
 * @param int  $post_id The post ID.
 * @param int  $length The excerpt length.
 * @param bool $more Whether to add the more link.
 * @return string The post excerpt.
 */
function aqualuxe_get_excerpt( $post_id = null, $length = 55, $more = false ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$post = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}

	if ( has_excerpt( $post_id ) ) {
		$excerpt = $post->post_excerpt;
	} else {
		$excerpt = $post->post_content;
		$excerpt = strip_shortcodes( $excerpt );
		$excerpt = excerpt_remove_blocks( $excerpt );
		$excerpt = wp_strip_all_tags( $excerpt );
		$excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
	}

	$excerpt = wp_trim_words( $excerpt, $length, '' );

	if ( $more ) {
		$excerpt .= ' <a href="' . esc_url( get_permalink( $post_id ) ) . '" class="more-link">' . esc_html__( 'Read More', 'aqualuxe' ) . '</a>';
	}

	return $excerpt;
}

/**
 * Get the post date.
 *
 * @param int    $post_id The post ID.
 * @param string $format The date format.
 * @return string The post date.
 */
function aqualuxe_get_post_date( $post_id = null, $format = '' ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	if ( ! $format ) {
		$format = get_option( 'date_format' );
	}

	return get_the_date( $format, $post_id );
}

/**
 * Get the post author.
 *
 * @param int $post_id The post ID.
 * @return string The post author.
 */
function aqualuxe_get_post_author( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$post = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}

	return get_the_author_meta( 'display_name', $post->post_author );
}

/**
 * Get the post categories.
 *
 * @param int $post_id The post ID.
 * @return array The post categories.
 */
function aqualuxe_get_post_categories( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$categories = get_the_category( $post_id );
	if ( ! $categories ) {
		return array();
	}

	return $categories;
}

/**
 * Get the post tags.
 *
 * @param int $post_id The post ID.
 * @return array The post tags.
 */
function aqualuxe_get_post_tags( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$tags = get_the_tags( $post_id );
	if ( ! $tags ) {
		return array();
	}

	return $tags;
}

/**
 * Get the post comments count.
 *
 * @param int $post_id The post ID.
 * @return int The post comments count.
 */
function aqualuxe_get_post_comments_count( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	return get_comments_number( $post_id );
}

/**
 * Get the post views count.
 *
 * @param int $post_id The post ID.
 * @return int The post views count.
 */
function aqualuxe_get_post_views( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$count = get_post_meta( $post_id, 'aqualuxe_post_views', true );
	return $count ? $count : 0;
}

/**
 * Set the post views count.
 *
 * @param int $post_id The post ID.
 * @return void
 */
function aqualuxe_set_post_views( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$count = aqualuxe_get_post_views( $post_id );
	$count++;

	update_post_meta( $post_id, 'aqualuxe_post_views', $count );
}

/**
 * Get the related posts.
 *
 * @param int   $post_id The post ID.
 * @param int   $count The number of related posts.
 * @param array $args Additional arguments.
 * @return array The related posts.
 */
function aqualuxe_get_related_posts( $post_id = null, $count = 3, $args = array() ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$categories = aqualuxe_get_post_categories( $post_id );
	if ( ! $categories ) {
		return array();
	}

	$category_ids = array();
	foreach ( $categories as $category ) {
		$category_ids[] = $category->term_id;
	}

	$default_args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $count,
		'post__not_in'   => array( $post_id ),
		'category__in'   => $category_ids,
		'orderby'        => 'rand',
	);

	$args = wp_parse_args( $args, $default_args );
	$query = new WP_Query( $args );

	return $query->posts;
}

/**
 * Get the pagination.
 *
 * @param array $args The pagination arguments.
 * @return string The pagination.
 */
function aqualuxe_get_pagination( $args = array() ) {
	$default_args = array(
		'mid_size'           => 2,
		'prev_text'          => esc_html__( 'Previous', 'aqualuxe' ),
		'next_text'          => esc_html__( 'Next', 'aqualuxe' ),
		'screen_reader_text' => esc_html__( 'Posts navigation', 'aqualuxe' ),
	);

	$args = wp_parse_args( $args, $default_args );
	return get_the_posts_pagination( $args );
}

/**
 * Get the breadcrumbs.
 *
 * @return array The breadcrumbs.
 */
function aqualuxe_get_breadcrumbs() {
	$breadcrumbs = array();

	// Home.
	$breadcrumbs[] = array(
		'label' => esc_html__( 'Home', 'aqualuxe' ),
		'url'   => esc_url( home_url( '/' ) ),
	);

	// Handle WooCommerce breadcrumbs.
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_shop() ) {
			$breadcrumbs[] = array(
				'label' => esc_html__( 'Shop', 'aqualuxe' ),
				'url'   => esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ),
			);
		} elseif ( is_product_category() || is_product_tag() ) {
			$breadcrumbs[] = array(
				'label' => esc_html__( 'Shop', 'aqualuxe' ),
				'url'   => esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ),
			);

			$term = get_queried_object();
			$breadcrumbs[] = array(
				'label' => esc_html( $term->name ),
				'url'   => esc_url( get_term_link( $term ) ),
			);
		} elseif ( is_product() ) {
			$breadcrumbs[] = array(
				'label' => esc_html__( 'Shop', 'aqualuxe' ),
				'url'   => esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ),
			);

			$terms = wp_get_post_terms( get_the_ID(), 'product_cat' );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				$term = $terms[0];
				$breadcrumbs[] = array(
					'label' => esc_html( $term->name ),
					'url'   => esc_url( get_term_link( $term ) ),
				);
			}

			$breadcrumbs[] = array(
				'label' => esc_html( get_the_title() ),
				'url'   => esc_url( get_permalink() ),
			);
		}
	} elseif ( is_singular( 'post' ) ) {
		// Blog post.
		$breadcrumbs[] = array(
			'label' => esc_html__( 'Blog', 'aqualuxe' ),
			'url'   => esc_url( get_permalink( get_option( 'page_for_posts' ) ) ),
		);

		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			$category = $categories[0];
			$breadcrumbs[] = array(
				'label' => esc_html( $category->name ),
				'url'   => esc_url( get_category_link( $category->term_id ) ),
			);
		}

		$breadcrumbs[] = array(
			'label' => esc_html( get_the_title() ),
			'url'   => esc_url( get_permalink() ),
		);
	} elseif ( is_page() ) {
		// Page.
		$ancestors = get_post_ancestors( get_the_ID() );
		if ( ! empty( $ancestors ) ) {
			$ancestors = array_reverse( $ancestors );
			foreach ( $ancestors as $ancestor ) {
				$breadcrumbs[] = array(
					'label' => esc_html( get_the_title( $ancestor ) ),
					'url'   => esc_url( get_permalink( $ancestor ) ),
				);
			}
		}

		$breadcrumbs[] = array(
			'label' => esc_html( get_the_title() ),
			'url'   => esc_url( get_permalink() ),
		);
	} elseif ( is_category() || is_tag() || is_tax() ) {
		// Category, tag, or custom taxonomy.
		$term = get_queried_object();
		$breadcrumbs[] = array(
			'label' => esc_html( $term->name ),
			'url'   => esc_url( get_term_link( $term ) ),
		);
	} elseif ( is_search() ) {
		// Search.
		$breadcrumbs[] = array(
			'label' => esc_html__( 'Search Results', 'aqualuxe' ),
			'url'   => esc_url( get_search_link() ),
		);
	} elseif ( is_404() ) {
		// 404.
		$breadcrumbs[] = array(
			'label' => esc_html__( 'Page Not Found', 'aqualuxe' ),
			'url'   => esc_url( home_url( '/404/' ) ),
		);
	}

	return $breadcrumbs;
}

/**
 * Get the social profiles.
 *
 * @return array The social profiles.
 */
if (! function_exists('aqualuxe_get_social_profiles')) :
	function aqualuxe_get_social_profiles() {
		$social_profiles = array();
	
		// Get social profiles from theme mods.
		$facebook  = get_theme_mod( 'aqualuxe_facebook_url' );
		$twitter   = get_theme_mod( 'aqualuxe_twitter_url' );
		$instagram = get_theme_mod( 'aqualuxe_instagram_url' );
		$linkedin  = get_theme_mod( 'aqualuxe_linkedin_url' );
		$youtube   = get_theme_mod( 'aqualuxe_youtube_url' );
		$pinterest = get_theme_mod( 'aqualuxe_pinterest_url' );
	
		if ( $facebook ) {
			$social_profiles[] = esc_url( $facebook );
		}
		if ( $twitter ) {
			$social_profiles[] = esc_url( $twitter );
		}
		if ( $instagram ) {
			$social_profiles[] = esc_url( $instagram );
		}
		if ( $linkedin ) {
			$social_profiles[] = esc_url( $linkedin );
		}
		if ( $youtube ) {
			$social_profiles[] = esc_url( $youtube );
		}
		if ( $pinterest ) {
			$social_profiles[] = esc_url( $pinterest );
		}
	
		return $social_profiles;
	}
endif;

/**
 * Get the contact information.
 *
 * @return array The contact information.
 */
function aqualuxe_get_contact_info() {
	$contact_info = array();

	// Get contact info from theme mods.
	$phone   = get_theme_mod( 'aqualuxe_phone' );
	$email   = get_theme_mod( 'aqualuxe_email' );
	$address = array(
		'street'  => get_theme_mod( 'aqualuxe_address_street' ),
		'city'    => get_theme_mod( 'aqualuxe_address_city' ),
		'state'   => get_theme_mod( 'aqualuxe_address_state' ),
		'zip'     => get_theme_mod( 'aqualuxe_address_zip' ),
		'country' => get_theme_mod( 'aqualuxe_address_country' ),
	);

	if ( $phone ) {
		$contact_info['phone'] = $phone;
	}
	if ( $email ) {
		$contact_info['email'] = $email;
	}
	if ( $address['street'] && $address['city'] && $address['state'] ) {
		$contact_info['address'] = $address;
	}

	return $contact_info;
}

/**
 * Get the theme option.
 *
 * @param string $option The option name.
 * @param mixed  $default The default value.
 * @return mixed The option value.
 */
function aqualuxe_get_option( $option, $default = '' ) {
	return get_theme_mod( 'aqualuxe_' . $option, $default );
}

/**
 * Get the theme color.
 *
 * @param string $color The color name.
 * @param string $default The default color.
 * @return string The color value.
 */
function aqualuxe_get_color( $color, $default = '' ) {
	return get_theme_mod( 'aqualuxe_' . $color . '_color', $default );
}

/**
 * Get the theme font family.
 *
 * @param string $font The font name.
 * @param string $default The default font family.
 * @return string The font family.
 */
function aqualuxe_get_font_family( $font, $default = '' ) {
	return get_theme_mod( 'aqualuxe_' . $font . '_font_family', $default );
}

/**
 * Get the theme font size.
 *
 * @param string $size The size name.
 * @param string $default The default font size.
 * @return string The font size.
 */
function aqualuxe_get_font_size( $size, $default = '' ) {
	return get_theme_mod( 'aqualuxe_' . $size . '_font_size', $default );
}

/**
 * Get the theme line height.
 *
 * @param string $height The height name.
 * @param string $default The default line height.
 * @return string The line height.
 */
function aqualuxe_get_line_height( $height, $default = '' ) {
	return get_theme_mod( 'aqualuxe_' . $height . '_line_height', $default );
}

/**
 * Get the theme container width.
 *
 * @param string $default The default container width.
 * @return string The container width.
 */
function aqualuxe_get_container_width( $default = '1200' ) {
	return get_theme_mod( 'aqualuxe_container_width', $default );
}

/**
 * Get the theme header layout.
 *
 * @param string $default The default header layout.
 * @return string The header layout.
 */
function aqualuxe_get_header_layout( $default = 'default' ) {
	return get_theme_mod( 'aqualuxe_header_layout', $default );
}

/**
 * Get the theme footer layout.
 *
 * @param string $default The default footer layout.
 * @return string The footer layout.
 */
function aqualuxe_get_footer_layout( $default = '4-columns' ) {
	return get_theme_mod( 'aqualuxe_footer_layout', $default );
}

/**
 * Get the theme blog layout.
 *
 * @param string $default The default blog layout.
 * @return string The blog layout.
 */
function aqualuxe_get_blog_layout( $default = 'grid' ) {
	return get_theme_mod( 'aqualuxe_blog_layout', $default );
}

/**
 * Get the theme blog sidebar.
 *
 * @param string $default The default blog sidebar.
 * @return string The blog sidebar.
 */
function aqualuxe_get_blog_sidebar( $default = 'right' ) {
	return get_theme_mod( 'aqualuxe_blog_sidebar', $default );
}

/**
 * Get the theme shop layout.
 *
 * @param string $default The default shop layout.
 * @return string The shop layout.
 */
function aqualuxe_get_shop_layout( $default = 'grid' ) {
	return get_theme_mod( 'aqualuxe_shop_layout', $default );
}

/**
 * Get the theme shop sidebar.
 *
 * @param string $default The default shop sidebar.
 * @return string The shop sidebar.
 */
function aqualuxe_get_shop_sidebar( $default = 'right' ) {
	return get_theme_mod( 'aqualuxe_shop_sidebar', $default );
}

/**
 * Get the theme products per page.
 *
 * @param string $default The default products per page.
 * @return string The products per page.
 */
function aqualuxe_get_products_per_page( $default = '12' ) {
	return get_theme_mod( 'aqualuxe_products_per_page', $default );
}

/**
 * Get the theme products per row.
 *
 * @param string $default The default products per row.
 * @return string The products per row.
 */
function aqualuxe_get_products_per_row( $default = '4' ) {
	return get_theme_mod( 'aqualuxe_products_per_row', $default );
}

/**
 * Get the theme related products count.
 *
 * @param string $default The default related products count.
 * @return string The related products count.
 */
function aqualuxe_get_related_products_count( $default = '4' ) {
	return get_theme_mod( 'aqualuxe_related_products_count', $default );
}

/**
 * Get the theme upsell products count.
 *
 * @param string $default The default upsell products count.
 * @return string The upsell products count.
 */
function aqualuxe_get_upsell_products_count( $default = '4' ) {
	return get_theme_mod( 'aqualuxe_upsell_products_count', $default );
}

/**
 * Get the theme cross-sell products count.
 *
 * @param string $default The default cross-sell products count.
 * @return string The cross-sell products count.
 */
function aqualuxe_get_cross_sell_products_count( $default = '4' ) {
	return get_theme_mod( 'aqualuxe_cross_sell_products_count', $default );
}

/**
 * Get the theme product tabs style.
 *
 * @param string $default The default product tabs style.
 * @return string The product tabs style.
 */
function aqualuxe_get_product_tabs_style( $default = 'default' ) {
	return get_theme_mod( 'aqualuxe_product_tabs', $default );
}

/**
 * Get the theme fallback message.
 *
 * @param string $default The default fallback message.
 * @return string The fallback message.
 */
function aqualuxe_get_fallback_message( $default = '' ) {
	if ( ! $default ) {
		$default = esc_html__( 'Our shop is currently being updated. Please check back soon.', 'aqualuxe' );
	}
	return get_theme_mod( 'aqualuxe_fallback_message', $default );
}

/**
 * Get the theme fallback shop page.
 *
 * @param string $default The default fallback shop page.
 * @return string The fallback shop page.
 */
function aqualuxe_get_fallback_shop_page( $default = '0' ) {
	return get_theme_mod( 'aqualuxe_fallback_shop_page', $default );
}