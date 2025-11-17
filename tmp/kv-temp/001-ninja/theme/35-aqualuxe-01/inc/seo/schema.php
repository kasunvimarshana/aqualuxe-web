<?php
/**
 * Schema.org structured data implementation
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add schema.org structured data to the site.
 */
function aqualuxe_add_schema_markup() {
	// Don't output schema markup for search results and admin pages.
	if ( is_search() || is_admin() ) {
		return;
	}

	// Base schema for all pages.
	$schema = array(
		'@context' => 'https://schema.org',
	);

	// Organization schema.
	$organization_schema = aqualuxe_get_organization_schema();

	// WebSite schema.
	$website_schema = aqualuxe_get_website_schema();

	// Page-specific schema.
	if ( is_singular( 'post' ) ) {
		$schema = array_merge( $schema, aqualuxe_get_article_schema() );
	} elseif ( is_page() ) {
		$schema = array_merge( $schema, aqualuxe_get_webpage_schema() );
	} elseif ( is_home() || is_archive() || is_category() || is_tag() ) {
		$schema = array_merge( $schema, aqualuxe_get_blog_schema() );
	}

	// WooCommerce specific schema.
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_product() ) {
			$schema = array_merge( $schema, aqualuxe_get_product_schema() );
		} elseif ( is_shop() ) {
			$schema = array_merge( $schema, aqualuxe_get_shop_schema() );
		}
	}

	// Breadcrumb schema.
	if ( ! is_front_page() ) {
		$breadcrumb_schema = aqualuxe_get_breadcrumb_schema();
	}

	// Output the schema.
	$output = '<script type="application/ld+json">' . wp_json_encode( $organization_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>';
	$output .= '<script type="application/ld+json">' . wp_json_encode( $website_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>';
	$output .= '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>';

	if ( isset( $breadcrumb_schema ) ) {
		$output .= '<script type="application/ld+json">' . wp_json_encode( $breadcrumb_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>';
	}

	echo wp_kses( $output, array( 'script' => array( 'type' => array() ) ) );
}
add_action( 'wp_head', 'aqualuxe_add_schema_markup' );

/**
 * Get Organization schema.
 *
 * @return array Organization schema.
 */
function aqualuxe_get_organization_schema() {
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'@id'      => esc_url( home_url( '/#organization' ) ),
		'name'     => esc_html( get_bloginfo( 'name' ) ),
		'url'      => esc_url( home_url( '/' ) ),
	);

	// Add logo if available.
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id ) {
		$logo_image = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		if ( $logo_image ) {
			$schema['logo'] = array(
				'@type'  => 'ImageObject',
				'@id'    => esc_url( home_url( '/#logo' ) ),
				'url'    => esc_url( $logo_image[0] ),
				'width'  => absint( $logo_image[1] ),
				'height' => absint( $logo_image[2] ),
			);
		}
	}

	// Add social profiles if available.
	$social_profiles = aqualuxe_get_social_profiles();
	if ( ! empty( $social_profiles ) ) {
		$schema['sameAs'] = $social_profiles;
	}

	// Add contact information if available.
	$contact_info = aqualuxe_get_contact_info();
	if ( ! empty( $contact_info ) ) {
		if ( ! empty( $contact_info['phone'] ) ) {
			$schema['telephone'] = esc_html( $contact_info['phone'] );
		}
		if ( ! empty( $contact_info['email'] ) ) {
			$schema['email'] = esc_html( $contact_info['email'] );
		}
		if ( ! empty( $contact_info['address'] ) ) {
			$schema['address'] = array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => esc_html( $contact_info['address']['street'] ),
				'addressLocality' => esc_html( $contact_info['address']['city'] ),
				'addressRegion'   => esc_html( $contact_info['address']['state'] ),
				'postalCode'      => esc_html( $contact_info['address']['zip'] ),
				'addressCountry'  => esc_html( $contact_info['address']['country'] ),
			);
		}
	}

	return apply_filters( 'aqualuxe_organization_schema', $schema );
}

/**
 * Get WebSite schema.
 *
 * @return array WebSite schema.
 */
function aqualuxe_get_website_schema() {
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'WebSite',
		'@id'      => esc_url( home_url( '/#website' ) ),
		'url'      => esc_url( home_url( '/' ) ),
		'name'     => esc_html( get_bloginfo( 'name' ) ),
		'description' => esc_html( get_bloginfo( 'description' ) ),
		'publisher' => array(
			'@id' => esc_url( home_url( '/#organization' ) ),
		),
	);

	// Add search functionality.
	$schema['potentialAction'] = array(
		'@type'       => 'SearchAction',
		'target'      => esc_url( home_url( '/?s={search_term_string}' ) ),
		'query-input' => 'required name=search_term_string',
	);

	return apply_filters( 'aqualuxe_website_schema', $schema );
}

/**
 * Get Article schema for blog posts.
 *
 * @return array Article schema.
 */
function aqualuxe_get_article_schema() {
	global $post;

	$schema = array(
		'@type'            => 'Article',
		'@id'              => esc_url( get_permalink() . '#article' ),
		'isPartOf'         => array(
			'@id' => esc_url( home_url( '/#website' ) ),
		),
		'author'           => array(
			'@type' => 'Person',
			'name'  => esc_html( get_the_author_meta( 'display_name', $post->post_author ) ),
			'url'   => esc_url( get_author_posts_url( $post->post_author ) ),
		),
		'headline'         => esc_html( get_the_title() ),
		'datePublished'    => esc_html( get_the_date( 'c' ) ),
		'dateModified'     => esc_html( get_the_modified_date( 'c' ) ),
		'mainEntityOfPage' => array(
			'@type' => 'WebPage',
			'@id'   => esc_url( get_permalink() ),
		),
		'publisher'        => array(
			'@id' => esc_url( home_url( '/#organization' ) ),
		),
	);

	// Add featured image if available.
	if ( has_post_thumbnail() ) {
		$image_id  = get_post_thumbnail_id();
		$image_url = wp_get_attachment_image_src( $image_id, 'full' );

		if ( $image_url ) {
			$schema['image'] = array(
				'@type'  => 'ImageObject',
				'@id'    => esc_url( get_permalink() . '#primaryimage' ),
				'url'    => esc_url( $image_url[0] ),
				'width'  => absint( $image_url[1] ),
				'height' => absint( $image_url[2] ),
			);
		}
	}

	return apply_filters( 'aqualuxe_article_schema', $schema );
}

/**
 * Get WebPage schema for pages.
 *
 * @return array WebPage schema.
 */
function aqualuxe_get_webpage_schema() {
	$schema = array(
		'@type'      => 'WebPage',
		'@id'        => esc_url( get_permalink() . '#webpage' ),
		'url'        => esc_url( get_permalink() ),
		'name'       => esc_html( get_the_title() ),
		'isPartOf'   => array(
			'@id' => esc_url( home_url( '/#website' ) ),
		),
		'datePublished' => esc_html( get_the_date( 'c' ) ),
		'dateModified'  => esc_html( get_the_modified_date( 'c' ) ),
	);

	// Add featured image if available.
	if ( has_post_thumbnail() ) {
		$image_id  = get_post_thumbnail_id();
		$image_url = wp_get_attachment_image_src( $image_id, 'full' );

		if ( $image_url ) {
			$schema['primaryImageOfPage'] = array(
				'@type'  => 'ImageObject',
				'@id'    => esc_url( get_permalink() . '#primaryimage' ),
				'url'    => esc_url( $image_url[0] ),
				'width'  => absint( $image_url[1] ),
				'height' => absint( $image_url[2] ),
			);
		}
	}

	return apply_filters( 'aqualuxe_webpage_schema', $schema );
}

/**
 * Get Blog schema for blog pages.
 *
 * @return array Blog schema.
 */
function aqualuxe_get_blog_schema() {
	$schema = array(
		'@type'      => 'CollectionPage',
		'@id'        => esc_url( get_permalink() . '#collectionpage' ),
		'url'        => esc_url( get_permalink() ),
		'name'       => esc_html( wp_get_document_title() ),
		'isPartOf'   => array(
			'@id' => esc_url( home_url( '/#website' ) ),
		),
	);

	return apply_filters( 'aqualuxe_blog_schema', $schema );
}

/**
 * Get Product schema for WooCommerce products.
 *
 * @return array Product schema.
 */
function aqualuxe_get_product_schema() {
	if ( ! class_exists( 'WooCommerce' ) || ! is_product() ) {
		return array();
	}

	global $product;

	if ( ! is_object( $product ) ) {
		$product = wc_get_product( get_the_ID() );
	}

	if ( ! $product ) {
		return array();
	}

	$schema = array(
		'@type'       => 'Product',
		'@id'         => esc_url( get_permalink() . '#product' ),
		'name'        => esc_html( $product->get_name() ),
		'description' => wp_kses_post( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
		'url'         => esc_url( get_permalink() ),
		'sku'         => esc_html( $product->get_sku() ),
		'brand'       => array(
			'@type' => 'Brand',
			'name'  => esc_html( get_bloginfo( 'name' ) ),
		),
		'offers'      => array(
			'@type'           => 'Offer',
			'price'           => esc_html( $product->get_price() ),
			'priceCurrency'   => esc_html( get_woocommerce_currency() ),
			'availability'    => esc_html( $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' ),
			'url'             => esc_url( get_permalink() ),
			'priceValidUntil' => esc_html( date( 'Y-m-d', strtotime( '+1 year' ) ) ),
		),
	);

	// Add product image.
	if ( has_post_thumbnail() ) {
		$image_id  = get_post_thumbnail_id();
		$image_url = wp_get_attachment_image_src( $image_id, 'full' );

		if ( $image_url ) {
			$schema['image'] = array(
				'@type'  => 'ImageObject',
				'@id'    => esc_url( get_permalink() . '#primaryimage' ),
				'url'    => esc_url( $image_url[0] ),
				'width'  => absint( $image_url[1] ),
				'height' => absint( $image_url[2] ),
			);
		}
	}

	// Add product gallery images.
	$gallery_image_ids = $product->get_gallery_image_ids();
	if ( ! empty( $gallery_image_ids ) ) {
		$additional_images = array();
		foreach ( $gallery_image_ids as $gallery_image_id ) {
			$gallery_image_url = wp_get_attachment_image_src( $gallery_image_id, 'full' );
			if ( $gallery_image_url ) {
				$additional_images[] = esc_url( $gallery_image_url[0] );
			}
		}
		if ( ! empty( $additional_images ) ) {
			$schema['image'] = array_merge( array( $schema['image']['url'] ), $additional_images );
		}
	}

	// Add product ratings.
	if ( $product->get_rating_count() > 0 ) {
		$schema['aggregateRating'] = array(
			'@type'       => 'AggregateRating',
			'ratingValue' => esc_html( $product->get_average_rating() ),
			'reviewCount' => esc_html( $product->get_review_count() ),
		);
	}

	return apply_filters( 'aqualuxe_product_schema', $schema );
}

/**
 * Get Shop schema for WooCommerce shop page.
 *
 * @return array Shop schema.
 */
function aqualuxe_get_shop_schema() {
	if ( ! class_exists( 'WooCommerce' ) || ! is_shop() ) {
		return array();
	}

	$schema = array(
		'@type'      => 'CollectionPage',
		'@id'        => esc_url( get_permalink( wc_get_page_id( 'shop' ) ) . '#collectionpage' ),
		'url'        => esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ),
		'name'       => esc_html__( 'Shop', 'aqualuxe' ),
		'isPartOf'   => array(
			'@id' => esc_url( home_url( '/#website' ) ),
		),
	);

	return apply_filters( 'aqualuxe_shop_schema', $schema );
}

/**
 * Get Breadcrumb schema.
 *
 * @return array Breadcrumb schema.
 */
function aqualuxe_get_breadcrumb_schema() {
	$breadcrumbs = aqualuxe_get_breadcrumbs();

	if ( empty( $breadcrumbs ) ) {
		return array();
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'BreadcrumbList',
		'@id'      => esc_url( get_permalink() . '#breadcrumb' ),
		'itemListElement' => array(),
	);

	foreach ( $breadcrumbs as $index => $breadcrumb ) {
		$schema['itemListElement'][] = array(
			'@type'    => 'ListItem',
			'position' => $index + 1,
			'item'     => array(
				'@id'  => esc_url( $breadcrumb['url'] ),
				'name' => esc_html( $breadcrumb['label'] ),
			),
		);
	}

	return apply_filters( 'aqualuxe_breadcrumb_schema', $schema );
}

/**
 * Get breadcrumbs array.
 *
 * @return array Breadcrumbs.
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

	return apply_filters( 'aqualuxe_breadcrumbs', $breadcrumbs );
}

/**
 * Get social profiles.
 *
 * @return array Social profiles.
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
	
		return apply_filters( 'aqualuxe_social_profiles', $social_profiles );
	}
endif;

/**
 * Get contact information.
 *
 * @return array Contact information.
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

	return apply_filters( 'aqualuxe_contact_info', $contact_info );
}