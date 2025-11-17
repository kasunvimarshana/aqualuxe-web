<?php
/**
 * SEO Meta Tags
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add Open Graph and Twitter Card meta tags to the head
 */
function aqualuxe_add_meta_tags() {
	// Don't output meta tags on admin pages.
	if ( is_admin() ) {
		return;
	}

	// Get meta tags.
	$meta_tags = aqualuxe_get_meta_tags();

	// Output meta tags.
	foreach ( $meta_tags as $property => $content ) {
		if ( ! empty( $content ) ) {
			echo '<meta property="' . esc_attr( $property ) . '" content="' . esc_attr( $content ) . '" />' . "\n";
		}
	}
}
add_action( 'wp_head', 'aqualuxe_add_meta_tags', 1 );

/**
 * Get meta tags for the current page
 *
 * @return array Meta tags.
 */
function aqualuxe_get_meta_tags() {
	$meta_tags = [];

	// Basic Open Graph tags.
	$meta_tags['og:locale'] = get_locale();
	$meta_tags['og:site_name'] = get_bloginfo( 'name' );
	$meta_tags['og:type'] = 'website';

	// Twitter Card tags.
	$meta_tags['twitter:card'] = 'summary_large_image';
	
	// Twitter username.
	$twitter_username = get_theme_mod( 'aqualuxe_twitter_username' );
	if ( $twitter_username ) {
		$meta_tags['twitter:site'] = '@' . sanitize_text_field( $twitter_username );
	}

	// Page-specific tags.
	if ( is_singular() ) {
		// Title.
		$meta_tags['og:title'] = get_the_title();
		$meta_tags['twitter:title'] = get_the_title();

		// URL.
		$meta_tags['og:url'] = get_permalink();

		// Description.
		$description = '';
		
		if ( has_excerpt() ) {
			$description = get_the_excerpt();
		} else {
			$description = wp_trim_words( get_the_content(), 55, '...' );
		}
		
		if ( $description ) {
			$meta_tags['og:description'] = $description;
			$meta_tags['twitter:description'] = $description;
		}

		// Image.
		if ( has_post_thumbnail() ) {
			$image_id = get_post_thumbnail_id();
			$image_url = wp_get_attachment_image_src( $image_id, 'large' );
			
			if ( $image_url ) {
				$meta_tags['og:image'] = $image_url[0];
				$meta_tags['og:image:width'] = $image_url[1];
				$meta_tags['og:image:height'] = $image_url[2];
				$meta_tags['twitter:image'] = $image_url[0];
			}
		}

		// Article tags for posts.
		if ( is_singular( 'post' ) ) {
			$meta_tags['og:type'] = 'article';
			$meta_tags['article:published_time'] = get_the_date( 'c' );
			$meta_tags['article:modified_time'] = get_the_modified_date( 'c' );
			
			// Author.
			$meta_tags['article:author'] = get_author_posts_url( get_the_author_meta( 'ID' ) );
			
			// Categories.
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				foreach ( $categories as $category ) {
					$meta_tags['article:section'] = $category->name;
					break; // Only use the first category.
				}
			}
			
			// Tags.
			$tags = get_the_tags();
			if ( ! empty( $tags ) ) {
				foreach ( $tags as $tag ) {
					$meta_tags['article:tag'] = $tag->name;
				}
			}
		}

		// Product tags for WooCommerce products.
		if ( class_exists( 'WooCommerce' ) && is_product() ) {
			global $product;
			
			if ( ! is_object( $product ) ) {
				$product = wc_get_product( get_the_ID() );
			}
			
			if ( $product ) {
				$meta_tags['og:type'] = 'product';
				$meta_tags['product:price:amount'] = $product->get_price();
				$meta_tags['product:price:currency'] = get_woocommerce_currency();
				
				if ( $product->is_in_stock() ) {
					$meta_tags['product:availability'] = 'in stock';
				} else {
					$meta_tags['product:availability'] = 'out of stock';
				}
				
				// Product brand if available.
				$brand_taxonomy = 'pa_brand'; // Adjust this to your brand attribute taxonomy.
				if ( taxonomy_exists( $brand_taxonomy ) ) {
					$brands = wc_get_product_terms( $product->get_id(), $brand_taxonomy, [ 'fields' => 'names' ] );
					if ( ! empty( $brands ) ) {
						$meta_tags['product:brand'] = $brands[0];
					}
				}
			}
		}
	} elseif ( is_archive() || is_home() ) {
		// Archive pages.
		$meta_tags['og:title'] = wp_get_document_title();
		$meta_tags['twitter:title'] = wp_get_document_title();
		$meta_tags['og:url'] = get_permalink();
		
		// Description.
		if ( is_category() || is_tag() || is_tax() ) {
			$description = term_description();
			if ( $description ) {
				$meta_tags['og:description'] = wp_strip_all_tags( $description );
				$meta_tags['twitter:description'] = wp_strip_all_tags( $description );
			}
		} elseif ( is_author() ) {
			$description = get_the_author_meta( 'description' );
			if ( $description ) {
				$meta_tags['og:description'] = wp_strip_all_tags( $description );
				$meta_tags['twitter:description'] = wp_strip_all_tags( $description );
			}
		} else {
			$meta_tags['og:description'] = get_bloginfo( 'description' );
			$meta_tags['twitter:description'] = get_bloginfo( 'description' );
		}
	} else {
		// Default fallback for other pages.
		$meta_tags['og:title'] = wp_get_document_title();
		$meta_tags['twitter:title'] = wp_get_document_title();
		$meta_tags['og:url'] = home_url( $_SERVER['REQUEST_URI'] );
		$meta_tags['og:description'] = get_bloginfo( 'description' );
		$meta_tags['twitter:description'] = get_bloginfo( 'description' );
	}

	// Default image fallback if no featured image is set.
	if ( empty( $meta_tags['og:image'] ) ) {
		$default_image_id = get_theme_mod( 'aqualuxe_default_og_image' );
		
		if ( $default_image_id ) {
			$default_image_url = wp_get_attachment_image_src( $default_image_id, 'large' );
			
			if ( $default_image_url ) {
				$meta_tags['og:image'] = $default_image_url[0];
				$meta_tags['og:image:width'] = $default_image_url[1];
				$meta_tags['og:image:height'] = $default_image_url[2];
				$meta_tags['twitter:image'] = $default_image_url[0];
			}
		} else {
			// Use the site logo as a fallback.
			$logo_id = get_theme_mod( 'custom_logo' );
			
			if ( $logo_id ) {
				$logo_url = wp_get_attachment_image_src( $logo_id, 'full' );
				
				if ( $logo_url ) {
					$meta_tags['og:image'] = $logo_url[0];
					$meta_tags['og:image:width'] = $logo_url[1];
					$meta_tags['og:image:height'] = $logo_url[2];
					$meta_tags['twitter:image'] = $logo_url[0];
				}
			}
		}
	}

	// Allow filtering of meta tags.
	return apply_filters( 'aqualuxe_meta_tags', $meta_tags );
}

/**
 * Add canonical URL to the head
 */
function aqualuxe_add_canonical_url() {
	// Don't output canonical URL on admin pages.
	if ( is_admin() ) {
		return;
	}

	// Get canonical URL.
	$canonical_url = aqualuxe_get_canonical_url();

	// Output canonical URL.
	if ( $canonical_url ) {
		echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '" />' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_add_canonical_url' );

/**
 * Get canonical URL for the current page
 *
 * @return string Canonical URL.
 */
function aqualuxe_get_canonical_url() {
	$canonical_url = '';

	if ( is_singular() ) {
		$canonical_url = get_permalink();
	} elseif ( is_home() ) {
		$canonical_url = get_permalink( get_option( 'page_for_posts' ) );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$canonical_url = get_term_link( get_queried_object() );
	} elseif ( is_author() ) {
		$canonical_url = get_author_posts_url( get_queried_object_id() );
	} elseif ( is_year() ) {
		$canonical_url = get_year_link( get_query_var( 'year' ) );
	} elseif ( is_month() ) {
		$canonical_url = get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) );
	} elseif ( is_day() ) {
		$canonical_url = get_day_link( get_query_var( 'year' ), get_query_var( 'monthnum' ), get_query_var( 'day' ) );
	} elseif ( is_post_type_archive() ) {
		$canonical_url = get_post_type_archive_link( get_post_type() );
	} else {
		$canonical_url = home_url( $_SERVER['REQUEST_URI'] );
	}

	// Handle pagination.
	global $wp_query;
	$paged = get_query_var( 'paged' );
	
	if ( $paged > 1 ) {
		$canonical_url = get_pagenum_link( $paged );
	}

	// Allow filtering of canonical URL.
	return apply_filters( 'aqualuxe_canonical_url', $canonical_url );
}

/**
 * Add meta description to the head
 */
function aqualuxe_add_meta_description() {
	// Don't output meta description on admin pages.
	if ( is_admin() ) {
		return;
	}

	// Get meta description.
	$description = aqualuxe_get_meta_description();

	// Output meta description.
	if ( $description ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_add_meta_description' );

/**
 * Get meta description for the current page
 *
 * @return string Meta description.
 */
function aqualuxe_get_meta_description() {
	$description = '';

	if ( is_singular() ) {
		if ( has_excerpt() ) {
			$description = get_the_excerpt();
		} else {
			$description = wp_trim_words( get_the_content(), 55, '...' );
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$description = term_description();
		if ( $description ) {
			$description = wp_strip_all_tags( $description );
		}
	} elseif ( is_author() ) {
		$description = get_the_author_meta( 'description' );
	} else {
		$description = get_bloginfo( 'description' );
	}

	// Trim description to 160 characters.
	if ( $description ) {
		$description = wp_trim_words( $description, 30, '...' );
	}

	// Allow filtering of meta description.
	return apply_filters( 'aqualuxe_meta_description', $description );
}