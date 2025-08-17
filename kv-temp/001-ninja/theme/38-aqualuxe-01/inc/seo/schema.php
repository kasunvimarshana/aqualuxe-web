<?php
/**
 * Schema.org JSON-LD Implementation
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add JSON-LD schema to the head
 */
function aqualuxe_add_schema() {
	// Get the appropriate schema based on the current page
	$schema = aqualuxe_get_schema();
	
	if ( ! empty( $schema ) ) {
		echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
	}
}
add_action( 'wp_head', 'aqualuxe_add_schema' );

/**
 * Get the appropriate schema based on the current page
 *
 * @return array Schema data.
 */
function aqualuxe_get_schema() {
	$schema = array();
	
	// Always add WebSite schema
	$schema = array_merge( $schema, aqualuxe_get_website_schema() );
	
	// Add Organization schema
	$schema = array_merge( $schema, aqualuxe_get_organization_schema() );
	
	// Add page-specific schema
	if ( is_singular( 'post' ) ) {
		$schema = array_merge( $schema, aqualuxe_get_article_schema() );
	} elseif ( is_page() ) {
		$schema = array_merge( $schema, aqualuxe_get_webpage_schema() );
	} elseif ( is_archive() || is_home() ) {
		$schema = array_merge( $schema, aqualuxe_get_collectionpage_schema() );
	}
	
	// Add breadcrumb schema
	if ( ! is_front_page() ) {
		$schema = array_merge( $schema, aqualuxe_get_breadcrumb_schema() );
	}
	
	// Add WooCommerce product schema if applicable
	if ( class_exists( 'WooCommerce' ) && is_product() ) {
		$schema = array_merge( $schema, aqualuxe_get_product_schema() );
	}
	
	/**
	 * Filter the schema data
	 *
	 * @param array $schema Schema data.
	 */
	return apply_filters( 'aqualuxe_schema', $schema );
}

/**
 * Get WebSite schema
 *
 * @return array WebSite schema data.
 */
function aqualuxe_get_website_schema() {
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'WebSite',
		'@id'      => esc_url( home_url( '/#website' ) ),
		'url'      => esc_url( home_url( '/' ) ),
		'name'     => esc_html( get_bloginfo( 'name' ) ),
		'description' => esc_html( get_bloginfo( 'description' ) ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => esc_url( home_url( '/?s={search_term_string}' ) ),
			'query-input' => 'required name=search_term_string',
		),
	);
	
	return array( $schema );
}

/**
 * Get Organization schema
 *
 * @return array Organization schema data.
 */
function aqualuxe_get_organization_schema() {
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'@id'      => esc_url( home_url( '/#organization' ) ),
		'url'      => esc_url( home_url( '/' ) ),
		'name'     => esc_html( get_bloginfo( 'name' ) ),
	);
	
	// Add logo if available
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
			$schema['image'] = array(
				'@id' => esc_url( home_url( '/#logo' ) ),
			);
		}
	}
	
	// Add social profiles if available
	$social_profiles = array();
	
	if ( defined( 'AQUALUXE_SOCIAL_FACEBOOK' ) && AQUALUXE_SOCIAL_FACEBOOK ) {
		$social_profiles[] = AQUALUXE_SOCIAL_FACEBOOK;
	}
	
	if ( defined( 'AQUALUXE_SOCIAL_TWITTER' ) && AQUALUXE_SOCIAL_TWITTER ) {
		$social_profiles[] = AQUALUXE_SOCIAL_TWITTER;
	}
	
	if ( defined( 'AQUALUXE_SOCIAL_INSTAGRAM' ) && AQUALUXE_SOCIAL_INSTAGRAM ) {
		$social_profiles[] = AQUALUXE_SOCIAL_INSTAGRAM;
	}
	
	if ( defined( 'AQUALUXE_SOCIAL_YOUTUBE' ) && AQUALUXE_SOCIAL_YOUTUBE ) {
		$social_profiles[] = AQUALUXE_SOCIAL_YOUTUBE;
	}
	
	if ( defined( 'AQUALUXE_SOCIAL_LINKEDIN' ) && AQUALUXE_SOCIAL_LINKEDIN ) {
		$social_profiles[] = AQUALUXE_SOCIAL_LINKEDIN;
	}
	
	if ( ! empty( $social_profiles ) ) {
		$schema['sameAs'] = $social_profiles;
	}
	
	// Add contact information if available
	if ( defined( 'AQUALUXE_CONTACT_EMAIL' ) && AQUALUXE_CONTACT_EMAIL ) {
		$schema['email'] = AQUALUXE_CONTACT_EMAIL;
	}
	
	if ( defined( 'AQUALUXE_CONTACT_PHONE' ) && AQUALUXE_CONTACT_PHONE ) {
		$schema['telephone'] = AQUALUXE_CONTACT_PHONE;
	}
	
	if ( defined( 'AQUALUXE_CONTACT_ADDRESS' ) && AQUALUXE_CONTACT_ADDRESS ) {
		$schema['address'] = array(
			'@type' => 'PostalAddress',
			'streetAddress' => AQUALUXE_CONTACT_ADDRESS,
		);
	}
	
	return array( $schema );
}

/**
 * Get Article schema for blog posts
 *
 * @return array Article schema data.
 */
function aqualuxe_get_article_schema() {
	global $post;
	
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Article',
		'@id'      => esc_url( get_permalink() . '#article' ),
		'headline' => esc_html( get_the_title() ),
		'url'      => esc_url( get_permalink() ),
		'datePublished' => esc_attr( get_the_date( 'c' ) ),
		'dateModified'  => esc_attr( get_the_modified_date( 'c' ) ),
		'author'   => array(
			'@type' => 'Person',
			'name'  => esc_html( get_the_author() ),
			'url'   => esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		),
		'publisher' => array(
			'@id' => esc_url( home_url( '/#organization' ) ),
		),
		'mainEntityOfPage' => array(
			'@type' => 'WebPage',
			'@id'   => esc_url( get_permalink() ),
		),
	);
	
	// Add featured image if available
	if ( has_post_thumbnail() ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		if ( $image ) {
			$schema['image'] = array(
				'@type'  => 'ImageObject',
				'@id'    => esc_url( get_permalink() . '#primaryimage' ),
				'url'    => esc_url( $image[0] ),
				'width'  => absint( $image[1] ),
				'height' => absint( $image[2] ),
			);
		}
	}
	
	// Add article body
	$schema['articleBody'] = wp_strip_all_tags( get_the_content() );
	
	// Add article section and keywords if available
	$categories = get_the_category();
	if ( ! empty( $categories ) ) {
		$schema['articleSection'] = esc_html( $categories[0]->name );
	}
	
	$tags = get_the_tags();
	if ( ! empty( $tags ) ) {
		$keywords = array();
		foreach ( $tags as $tag ) {
			$keywords[] = $tag->name;
		}
		$schema['keywords'] = implode( ', ', $keywords );
	}
	
	return array( $schema );
}

/**
 * Get WebPage schema for pages
 *
 * @return array WebPage schema data.
 */
function aqualuxe_get_webpage_schema() {
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'WebPage',
		'@id'      => esc_url( get_permalink() . '#webpage' ),
		'url'      => esc_url( get_permalink() ),
		'name'     => esc_html( get_the_title() ),
		'isPartOf' => array(
			'@id' => esc_url( home_url( '/#website' ) ),
		),
		'datePublished' => esc_attr( get_the_date( 'c' ) ),
		'dateModified'  => esc_attr( get_the_modified_date( 'c' ) ),
	);
	
	// Add featured image if available
	if ( has_post_thumbnail() ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		if ( $image ) {
			$schema['primaryImageOfPage'] = array(
				'@type'  => 'ImageObject',
				'@id'    => esc_url( get_permalink() . '#primaryimage' ),
				'url'    => esc_url( $image[0] ),
				'width'  => absint( $image[1] ),
				'height' => absint( $image[2] ),
			);
		}
	}
	
	return array( $schema );
}

/**
 * Get CollectionPage schema for archives
 *
 * @return array CollectionPage schema data.
 */
function aqualuxe_get_collectionpage_schema() {
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'CollectionPage',
		'@id'      => esc_url( get_permalink() . '#collectionpage' ),
		'url'      => esc_url( get_permalink() ),
		'name'     => esc_html( wp_get_document_title() ),
		'isPartOf' => array(
			'@id' => esc_url( home_url( '/#website' ) ),
		),
	);
	
	return array( $schema );
}

/**
 * Get BreadcrumbList schema
 *
 * @return array BreadcrumbList schema data.
 */
function aqualuxe_get_breadcrumb_schema() {
	$breadcrumbs = array();
	$position    = 1;
	
	// Add home page
	$breadcrumbs[] = array(
		'@type'    => 'ListItem',
		'position' => $position,
		'item'     => array(
			'@id'  => esc_url( home_url( '/' ) ),
			'name' => esc_html__( 'Home', 'aqualuxe' ),
		),
	);
	
	// Add category if applicable
	if ( is_category() ) {
		$category = get_queried_object();
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => ++$position,
			'item'     => array(
				'@id'  => esc_url( get_category_link( $category->term_id ) ),
				'name' => esc_html( $category->name ),
			),
		);
	} elseif ( is_singular( 'post' ) ) {
		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			$category = $categories[0];
			$breadcrumbs[] = array(
				'@type'    => 'ListItem',
				'position' => ++$position,
				'item'     => array(
					'@id'  => esc_url( get_category_link( $category->term_id ) ),
					'name' => esc_html( $category->name ),
				),
			);
		}
		
		// Add current post
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => ++$position,
			'item'     => array(
				'@id'  => esc_url( get_permalink() ),
				'name' => esc_html( get_the_title() ),
			),
		);
	} elseif ( is_page() ) {
		// Add parent pages if applicable
		$ancestors = get_post_ancestors( get_the_ID() );
		if ( ! empty( $ancestors ) ) {
			$ancestors = array_reverse( $ancestors );
			foreach ( $ancestors as $ancestor ) {
				$breadcrumbs[] = array(
					'@type'    => 'ListItem',
					'position' => ++$position,
					'item'     => array(
						'@id'  => esc_url( get_permalink( $ancestor ) ),
						'name' => esc_html( get_the_title( $ancestor ) ),
					),
				);
			}
		}
		
		// Add current page
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => ++$position,
			'item'     => array(
				'@id'  => esc_url( get_permalink() ),
				'name' => esc_html( get_the_title() ),
			),
		);
	} elseif ( is_tag() ) {
		$tag = get_queried_object();
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => ++$position,
			'item'     => array(
				'@id'  => esc_url( get_tag_link( $tag->term_id ) ),
				'name' => esc_html( $tag->name ),
			),
		);
	} elseif ( is_author() ) {
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => ++$position,
			'item'     => array(
				'@id'  => esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				'name' => esc_html( get_the_author() ),
			),
		);
	} elseif ( is_year() ) {
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => ++$position,
			'item'     => array(
				'@id'  => esc_url( get_year_link( get_the_time( 'Y' ) ) ),
				'name' => esc_html( get_the_time( 'Y' ) ),
			),
		);
	} elseif ( is_month() ) {
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => ++$position,
			'item'     => array(
				'@id'  => esc_url( get_year_link( get_the_time( 'Y' ) ) ),
				'name' => esc_html( get_the_time( 'Y' ) ),
			),
		);
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => ++$position,
			'item'     => array(
				'@id'  => esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ),
				'name' => esc_html( get_the_time( 'F' ) ),
			),
		);
	} elseif ( is_day() ) {
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => ++$position,
			'item'     => array(
				'@id'  => esc_url( get_year_link( get_the_time( 'Y' ) ) ),
				'name' => esc_html( get_the_time( 'Y' ) ),
			),
		);
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => ++$position,
			'item'     => array(
				'@id'  => esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ),
				'name' => esc_html( get_the_time( 'F' ) ),
			),
		);
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => ++$position,
			'item'     => array(
				'@id'  => esc_url( get_day_link( get_the_time( 'Y' ), get_the_time( 'm' ), get_the_time( 'd' ) ) ),
				'name' => esc_html( get_the_time( 'd' ) ),
			),
		);
	} elseif ( is_search() ) {
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => ++$position,
			'item'     => array(
				'@id'  => esc_url( get_search_link() ),
				'name' => esc_html__( 'Search Results', 'aqualuxe' ),
			),
		);
	} elseif ( is_404() ) {
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => ++$position,
			'item'     => array(
				'@id'  => esc_url( home_url( '/404/' ) ),
				'name' => esc_html__( 'Page Not Found', 'aqualuxe' ),
			),
		);
	}
	
	// WooCommerce breadcrumbs
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_shop() ) {
			$breadcrumbs[] = array(
				'@type'    => 'ListItem',
				'position' => ++$position,
				'item'     => array(
					'@id'  => esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ),
					'name' => esc_html__( 'Shop', 'aqualuxe' ),
				),
			);
		} elseif ( is_product_category() ) {
			$breadcrumbs[] = array(
				'@type'    => 'ListItem',
				'position' => ++$position,
				'item'     => array(
					'@id'  => esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ),
					'name' => esc_html__( 'Shop', 'aqualuxe' ),
				),
			);
			
			$category = get_queried_object();
			$breadcrumbs[] = array(
				'@type'    => 'ListItem',
				'position' => ++$position,
				'item'     => array(
					'@id'  => esc_url( get_term_link( $category ) ),
					'name' => esc_html( $category->name ),
				),
			);
		} elseif ( is_product() ) {
			$breadcrumbs[] = array(
				'@type'    => 'ListItem',
				'position' => ++$position,
				'item'     => array(
					'@id'  => esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ),
					'name' => esc_html__( 'Shop', 'aqualuxe' ),
				),
			);
			
			$terms = wc_get_product_terms(
				get_the_ID(),
				'product_cat',
				array(
					'orderby' => 'parent',
					'order'   => 'DESC',
				)
			);
			
			if ( ! empty( $terms ) ) {
				$term = $terms[0];
				$breadcrumbs[] = array(
					'@type'    => 'ListItem',
					'position' => ++$position,
					'item'     => array(
						'@id'  => esc_url( get_term_link( $term ) ),
						'name' => esc_html( $term->name ),
					),
				);
			}
			
			$breadcrumbs[] = array(
				'@type'    => 'ListItem',
				'position' => ++$position,
				'item'     => array(
					'@id'  => esc_url( get_permalink() ),
					'name' => esc_html( get_the_title() ),
				),
			);
		}
	}
	
	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'BreadcrumbList',
		'@id'      => esc_url( get_permalink() . '#breadcrumb' ),
		'itemListElement' => $breadcrumbs,
	);
	
	return array( $schema );
}

/**
 * Get Product schema for WooCommerce products
 *
 * @return array Product schema data.
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
		'@context' => 'https://schema.org',
		'@type'    => 'Product',
		'@id'      => esc_url( get_permalink() . '#product' ),
		'name'     => esc_html( $product->get_name() ),
		'url'      => esc_url( get_permalink() ),
		'description' => wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
	);
	
	// Add product image
	if ( $product->get_image_id() ) {
		$image = wp_get_attachment_image_src( $product->get_image_id(), 'full' );
		if ( $image ) {
			$schema['image'] = array(
				'@type'  => 'ImageObject',
				'@id'    => esc_url( get_permalink() . '#productimage' ),
				'url'    => esc_url( $image[0] ),
				'width'  => absint( $image[1] ),
				'height' => absint( $image[2] ),
			);
		}
	}
	
	// Add product brand if available
	$brands = wp_get_post_terms( $product->get_id(), 'product_brand' );
	if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) {
		$schema['brand'] = array(
			'@type' => 'Brand',
			'name'  => esc_html( $brands[0]->name ),
		);
	}
	
	// Add SKU if available
	if ( $product->get_sku() ) {
		$schema['sku'] = esc_html( $product->get_sku() );
	}
	
	// Add MPN if available (using a custom field)
	$mpn = get_post_meta( $product->get_id(), '_mpn', true );
	if ( $mpn ) {
		$schema['mpn'] = esc_html( $mpn );
	}
	
	// Add product availability
	$schema['offers'] = array(
		'@type'         => 'Offer',
		'url'           => esc_url( get_permalink() ),
		'price'         => wc_format_decimal( $product->get_price(), wc_get_price_decimals() ),
		'priceCurrency' => get_woocommerce_currency(),
		'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
		'priceValidUntil' => date( 'Y-12-31', time() + YEAR_IN_SECONDS ),
	);
	
	// Add seller information
	$schema['offers']['seller'] = array(
		'@type' => 'Organization',
		'name'  => esc_html( get_bloginfo( 'name' ) ),
		'url'   => esc_url( home_url( '/' ) ),
	);
	
	// Add product rating if available
	if ( $product->get_rating_count() > 0 ) {
		$schema['aggregateRating'] = array(
			'@type'       => 'AggregateRating',
			'ratingValue' => wc_format_decimal( $product->get_average_rating(), 1 ),
			'reviewCount' => intval( $product->get_review_count() ),
		);
	}
	
	return array( $schema );
}

/**
 * Add Open Graph meta tags
 */
function aqualuxe_add_opengraph_meta() {
	// Default values
	$og_type        = 'website';
	$og_title       = esc_html( get_bloginfo( 'name' ) );
	$og_description = esc_html( get_bloginfo( 'description' ) );
	$og_url         = esc_url( home_url( '/' ) );
	$og_image       = '';
	
	// Get custom logo as default image
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	if ( $custom_logo_id ) {
		$logo_image = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		if ( $logo_image ) {
			$og_image = esc_url( $logo_image[0] );
		}
	}
	
	// Adjust values based on current page
	if ( is_singular() ) {
		$og_type  = 'article';
		$og_title = esc_html( get_the_title() );
		$og_url   = esc_url( get_permalink() );
		
		// Get excerpt or content for description
		$content = get_the_excerpt();
		if ( empty( $content ) ) {
			$content = get_the_content();
		}
		$og_description = wp_strip_all_tags( $content );
		$og_description = preg_replace( '/\s+/', ' ', $og_description );
		$og_description = substr( $og_description, 0, 300 );
		
		// Get featured image
		if ( has_post_thumbnail() ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			if ( $image ) {
				$og_image = esc_url( $image[0] );
			}
		}
	} elseif ( is_archive() || is_home() ) {
		$og_title = esc_html( wp_get_document_title() );
		$og_url   = esc_url( get_permalink() );
	}
	
	// Output Open Graph meta tags
	echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '" />' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '" />' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $og_description ) . '" />' . "\n";
	echo '<meta property="og:url" content="' . esc_attr( $og_url ) . '" />' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
	
	if ( $og_image ) {
		echo '<meta property="og:image" content="' . esc_attr( $og_image ) . '" />' . "\n";
	}
	
	// Output Twitter Card meta tags
	echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $og_title ) . '" />' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $og_description ) . '" />' . "\n";
	
	if ( $og_image ) {
		echo '<meta name="twitter:image" content="' . esc_attr( $og_image ) . '" />' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_add_opengraph_meta' );