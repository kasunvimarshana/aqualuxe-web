<?php
/**
 * Schema.org JSON-LD implementation
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add schema.org JSON-LD to the head
 */
function aqualuxe_add_schema_markup() {
	// Don't output schema markup for search results and admin pages.
	if ( is_search() || is_admin() ) {
		return;
	}

	// Get the appropriate schema markup based on the current page.
	$schema = aqualuxe_get_schema_markup();

	if ( $schema ) {
		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_add_schema_markup' );

/**
 * Get the appropriate schema markup based on the current page
 *
 * @return array|bool Schema markup data or false if none.
 */
function aqualuxe_get_schema_markup() {
	// Default schema.
	$schema = [
		'@context' => 'https://schema.org',
	];

	// Organization schema (always included).
	$organization_schema = aqualuxe_get_organization_schema();
	
	// Website schema.
	$website_schema = aqualuxe_get_website_schema();
	
	// Page-specific schema.
	if ( is_singular( 'post' ) ) {
		$schema = array_merge( $schema, aqualuxe_get_article_schema() );
	} elseif ( is_page() ) {
		$schema = array_merge( $schema, aqualuxe_get_webpage_schema() );
	} elseif ( is_archive() || is_home() ) {
		$schema = array_merge( $schema, aqualuxe_get_collectionpage_schema() );
	} elseif ( class_exists( 'WooCommerce' ) && is_product() ) {
		$schema = array_merge( $schema, aqualuxe_get_product_schema() );
	} elseif ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
		$schema = array_merge( $schema, aqualuxe_get_itemlist_schema() );
	}
	
	// Breadcrumb schema.
	if ( ! is_front_page() ) {
		$breadcrumb_schema = aqualuxe_get_breadcrumb_schema();
	}
	
	// Combine all schemas into a @graph.
	$graph = [];
	
	if ( ! empty( $organization_schema ) ) {
		$graph[] = $organization_schema;
	}
	
	if ( ! empty( $website_schema ) ) {
		$graph[] = $website_schema;
	}
	
	if ( ! empty( $schema ) && isset( $schema['@type'] ) ) {
		$graph[] = $schema;
	}
	
	if ( ! empty( $breadcrumb_schema ) ) {
		$graph[] = $breadcrumb_schema;
	}
	
	// If we have multiple items, use @graph.
	if ( count( $graph ) > 1 ) {
		return [
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		];
	} elseif ( count( $graph ) === 1 ) {
		// If we only have one item, return it directly.
		return array_merge( [ '@context' => 'https://schema.org' ], $graph[0] );
	}
	
	return false;
}

/**
 * Get Organization schema
 *
 * @return array Organization schema data.
 */
function aqualuxe_get_organization_schema() {
	$schema = [
		'@type' => 'Organization',
		'@id'   => trailingslashit( home_url() ) . '#organization',
		'name'  => get_bloginfo( 'name' ),
		'url'   => home_url(),
	];
	
	// Add logo if available.
	$logo_id = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$logo_image = wp_get_attachment_image_src( $logo_id, 'full' );
		if ( $logo_image ) {
			$schema['logo'] = [
				'@type' => 'ImageObject',
				'url'   => $logo_image[0],
				'width' => $logo_image[1],
				'height' => $logo_image[2],
			];
			$schema['image'] = $logo_image[0];
		}
	}
	
	// Add social profiles if available.
	$social_profiles = [];
	
	// Facebook.
	$facebook = get_theme_mod( 'aqualuxe_facebook_url' );
	if ( $facebook ) {
		$social_profiles[] = esc_url( $facebook );
	}
	
	// Twitter.
	$twitter = get_theme_mod( 'aqualuxe_twitter_url' );
	if ( $twitter ) {
		$social_profiles[] = esc_url( $twitter );
	}
	
	// Instagram.
	$instagram = get_theme_mod( 'aqualuxe_instagram_url' );
	if ( $instagram ) {
		$social_profiles[] = esc_url( $instagram );
	}
	
	// LinkedIn.
	$linkedin = get_theme_mod( 'aqualuxe_linkedin_url' );
	if ( $linkedin ) {
		$social_profiles[] = esc_url( $linkedin );
	}
	
	// YouTube.
	$youtube = get_theme_mod( 'aqualuxe_youtube_url' );
	if ( $youtube ) {
		$social_profiles[] = esc_url( $youtube );
	}
	
	if ( ! empty( $social_profiles ) ) {
		$schema['sameAs'] = $social_profiles;
	}
	
	// Add contact information if available.
	$contact_info = [];
	
	// Phone.
	$phone = get_theme_mod( 'aqualuxe_phone' );
	if ( $phone ) {
		$contact_info['telephone'] = esc_html( $phone );
	}
	
	// Email.
	$email = get_theme_mod( 'aqualuxe_email' );
	if ( $email ) {
		$contact_info['email'] = esc_html( $email );
	}
	
	// Address.
	$address_street = get_theme_mod( 'aqualuxe_address_street' );
	$address_city = get_theme_mod( 'aqualuxe_address_city' );
	$address_state = get_theme_mod( 'aqualuxe_address_state' );
	$address_zip = get_theme_mod( 'aqualuxe_address_zip' );
	$address_country = get_theme_mod( 'aqualuxe_address_country' );
	
	if ( $address_street && $address_city && $address_state && $address_zip && $address_country ) {
		$contact_info['address'] = [
			'@type'           => 'PostalAddress',
			'streetAddress'   => esc_html( $address_street ),
			'addressLocality' => esc_html( $address_city ),
			'addressRegion'   => esc_html( $address_state ),
			'postalCode'      => esc_html( $address_zip ),
			'addressCountry'  => esc_html( $address_country ),
		];
	}
	
	if ( ! empty( $contact_info ) ) {
		$schema = array_merge( $schema, $contact_info );
	}
	
	// Allow filtering of the organization schema.
	return apply_filters( 'aqualuxe_organization_schema', $schema );
}

/**
 * Get Website schema
 *
 * @return array Website schema data.
 */
function aqualuxe_get_website_schema() {
	$schema = [
		'@type'    => 'WebSite',
		'@id'      => trailingslashit( home_url() ) . '#website',
		'url'      => home_url(),
		'name'     => get_bloginfo( 'name' ),
		'publisher' => [
			'@id' => trailingslashit( home_url() ) . '#organization',
		],
		'description' => get_bloginfo( 'description' ),
	];
	
	// Add search functionality.
	$schema['potentialAction'] = [
		'@type'       => 'SearchAction',
		'target'      => home_url( '?s={search_term_string}' ),
		'query-input' => 'required name=search_term_string',
	];
	
	// Allow filtering of the website schema.
	return apply_filters( 'aqualuxe_website_schema', $schema );
}

/**
 * Get Article schema for single posts
 *
 * @return array Article schema data.
 */
function aqualuxe_get_article_schema() {
	global $post;
	
	$schema = [
		'@type'         => 'Article',
		'@id'           => get_permalink() . '#article',
		'headline'      => get_the_title(),
		'url'           => get_permalink(),
		'datePublished' => get_the_date( 'c' ),
		'dateModified'  => get_the_modified_date( 'c' ),
		'author'        => [
			'@type' => 'Person',
			'name'  => get_the_author(),
			'url'   => get_author_posts_url( get_the_author_meta( 'ID' ) ),
		],
		'publisher'     => [
			'@id' => trailingslashit( home_url() ) . '#organization',
		],
		'mainEntityOfPage' => [
			'@type' => 'WebPage',
			'@id'   => get_permalink(),
		],
	];
	
	// Add featured image if available.
	if ( has_post_thumbnail() ) {
		$image_id = get_post_thumbnail_id();
		$image_url = wp_get_attachment_image_src( $image_id, 'full' );
		
		if ( $image_url ) {
			$schema['image'] = [
				'@type'  => 'ImageObject',
				'url'    => $image_url[0],
				'width'  => $image_url[1],
				'height' => $image_url[2],
			];
		}
	}
	
	// Add article body.
	$schema['articleBody'] = wp_strip_all_tags( get_the_content() );
	
	// Allow filtering of the article schema.
	return apply_filters( 'aqualuxe_article_schema', $schema );
}

/**
 * Get WebPage schema for pages
 *
 * @return array WebPage schema data.
 */
function aqualuxe_get_webpage_schema() {
	$schema = [
		'@type'      => 'WebPage',
		'@id'        => get_permalink() . '#webpage',
		'url'        => get_permalink(),
		'name'       => get_the_title(),
		'isPartOf'   => [
			'@id' => trailingslashit( home_url() ) . '#website',
		],
		'datePublished' => get_the_date( 'c' ),
		'dateModified'  => get_the_modified_date( 'c' ),
	];
	
	// Add featured image if available.
	if ( has_post_thumbnail() ) {
		$image_id = get_post_thumbnail_id();
		$image_url = wp_get_attachment_image_src( $image_id, 'full' );
		
		if ( $image_url ) {
			$schema['primaryImageOfPage'] = [
				'@type'  => 'ImageObject',
				'url'    => $image_url[0],
				'width'  => $image_url[1],
				'height' => $image_url[2],
			];
		}
	}
	
	// Allow filtering of the webpage schema.
	return apply_filters( 'aqualuxe_webpage_schema', $schema );
}

/**
 * Get CollectionPage schema for archives
 *
 * @return array CollectionPage schema data.
 */
function aqualuxe_get_collectionpage_schema() {
	$schema = [
		'@type'      => 'CollectionPage',
		'@id'        => get_permalink() . '#collectionpage',
		'url'        => get_permalink(),
		'name'       => wp_get_document_title(),
		'isPartOf'   => [
			'@id' => trailingslashit( home_url() ) . '#website',
		],
	];
	
	// Allow filtering of the collectionpage schema.
	return apply_filters( 'aqualuxe_collectionpage_schema', $schema );
}

/**
 * Get Product schema for WooCommerce products
 *
 * @return array Product schema data.
 */
function aqualuxe_get_product_schema() {
	if ( ! class_exists( 'WooCommerce' ) || ! is_product() ) {
		return [];
	}
	
	global $product;
	
	if ( ! is_object( $product ) ) {
		$product = wc_get_product( get_the_ID() );
	}
	
	if ( ! $product ) {
		return [];
	}
	
	$schema = [
		'@type'       => 'Product',
		'@id'         => get_permalink() . '#product',
		'name'        => $product->get_name(),
		'url'         => get_permalink(),
		'description' => wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
	];
	
	// Add product image.
	if ( $product->get_image_id() ) {
		$image_id = $product->get_image_id();
		$image_url = wp_get_attachment_image_src( $image_id, 'full' );
		
		if ( $image_url ) {
			$schema['image'] = [
				'@type'  => 'ImageObject',
				'url'    => $image_url[0],
				'width'  => $image_url[1],
				'height' => $image_url[2],
			];
		}
	}
	
	// Add SKU.
	if ( $product->get_sku() ) {
		$schema['sku'] = $product->get_sku();
	}
	
	// Add brand if available.
	$brand_taxonomy = 'pa_brand'; // Adjust this to your brand attribute taxonomy.
	if ( taxonomy_exists( $brand_taxonomy ) ) {
		$brands = wc_get_product_terms( $product->get_id(), $brand_taxonomy, [ 'fields' => 'names' ] );
		if ( ! empty( $brands ) ) {
			$schema['brand'] = [
				'@type' => 'Brand',
				'name'  => $brands[0],
			];
		}
	}
	
	// Add offers.
	$schema['offers'] = [
		'@type'         => 'Offer',
		'price'         => $product->get_price(),
		'priceCurrency' => get_woocommerce_currency(),
		'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
		'url'           => get_permalink(),
		'priceValidUntil' => date( 'Y-12-31', time() + YEAR_IN_SECONDS ), // Valid until the end of the current year.
	];
	
	// Add reviews if available.
	if ( $product->get_review_count() ) {
		$schema['aggregateRating'] = [
			'@type'       => 'AggregateRating',
			'ratingValue' => $product->get_average_rating(),
			'reviewCount' => $product->get_review_count(),
		];
		
		// Add individual reviews.
		$comments = get_comments( [
			'post_id' => $product->get_id(),
			'status'  => 'approve',
			'type'    => 'review',
			'number'  => 5, // Limit to 5 reviews.
		] );
		
		if ( $comments ) {
			$schema['review'] = [];
			
			foreach ( $comments as $comment ) {
				$rating = get_comment_meta( $comment->comment_ID, 'rating', true );
				
				if ( $rating ) {
					$schema['review'][] = [
						'@type'         => 'Review',
						'reviewRating'  => [
							'@type'       => 'Rating',
							'ratingValue' => $rating,
						],
						'author'        => [
							'@type' => 'Person',
							'name'  => $comment->comment_author,
						],
						'reviewBody'    => $comment->comment_content,
						'datePublished' => date( 'c', strtotime( $comment->comment_date ) ),
					];
				}
			}
		}
	}
	
	// Allow filtering of the product schema.
	return apply_filters( 'aqualuxe_product_schema', $schema, $product );
}

/**
 * Get ItemList schema for WooCommerce product archives
 *
 * @return array ItemList schema data.
 */
function aqualuxe_get_itemlist_schema() {
	if ( ! class_exists( 'WooCommerce' ) || ! ( is_shop() || is_product_category() || is_product_tag() ) ) {
		return [];
	}
	
	global $wp_query;
	
	$schema = [
		'@type'      => 'ItemList',
		'@id'        => get_permalink() . '#itemlist',
		'url'        => get_permalink(),
		'name'       => wp_get_document_title(),
		'itemListElement' => [],
	];
	
	// Add products to the list.
	$position = 1;
	
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			global $product;
			
			if ( ! is_object( $product ) ) {
				$product = wc_get_product( get_the_ID() );
			}
			
			if ( $product ) {
				$schema['itemListElement'][] = [
					'@type'    => 'ListItem',
					'position' => $position,
					'item'     => [
						'@type' => 'Product',
						'name'  => $product->get_name(),
						'url'   => get_permalink(),
					],
				];
				
				$position++;
			}
		}
		
		// Reset the query.
		wp_reset_postdata();
	}
	
	// Allow filtering of the itemlist schema.
	return apply_filters( 'aqualuxe_itemlist_schema', $schema );
}

/**
 * Get BreadcrumbList schema
 *
 * @return array BreadcrumbList schema data.
 */
function aqualuxe_get_breadcrumb_schema() {
	// Don't output breadcrumb schema on the front page.
	if ( is_front_page() ) {
		return [];
	}
	
	$breadcrumbs = [];
	$position = 1;
	
	// Add home page.
	$breadcrumbs[] = [
		'@type'    => 'ListItem',
		'position' => $position,
		'item'     => [
			'@id'  => home_url(),
			'name' => __( 'Home', 'aqualuxe' ),
		],
	];
	
	$position++;
	
	// Add category/archive pages for posts.
	if ( is_singular( 'post' ) ) {
		$categories = get_the_category();
		
		if ( ! empty( $categories ) ) {
			$category = $categories[0];
			
			$breadcrumbs[] = [
				'@type'    => 'ListItem',
				'position' => $position,
				'item'     => [
					'@id'  => get_category_link( $category->term_id ),
					'name' => $category->name,
				],
			];
			
			$position++;
		}
	}
	
	// Add parent pages for hierarchical post types.
	if ( is_page() && ! is_front_page() ) {
		$ancestors = get_post_ancestors( get_the_ID() );
		
		if ( ! empty( $ancestors ) ) {
			$ancestors = array_reverse( $ancestors );
			
			foreach ( $ancestors as $ancestor_id ) {
				$breadcrumbs[] = [
					'@type'    => 'ListItem',
					'position' => $position,
					'item'     => [
						'@id'  => get_permalink( $ancestor_id ),
						'name' => get_the_title( $ancestor_id ),
					],
				];
				
				$position++;
			}
		}
	}
	
	// Add WooCommerce shop page.
	if ( class_exists( 'WooCommerce' ) && ( is_product() || is_product_category() || is_product_tag() ) ) {
		$shop_page_id = wc_get_page_id( 'shop' );
		
		if ( $shop_page_id > 0 ) {
			$breadcrumbs[] = [
				'@type'    => 'ListItem',
				'position' => $position,
				'item'     => [
					'@id'  => get_permalink( $shop_page_id ),
					'name' => get_the_title( $shop_page_id ),
				],
			];
			
			$position++;
		}
	}
	
	// Add WooCommerce product category.
	if ( class_exists( 'WooCommerce' ) && is_product() ) {
		$product_categories = get_the_terms( get_the_ID(), 'product_cat' );
		
		if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
			$product_category = $product_categories[0];
			
			$breadcrumbs[] = [
				'@type'    => 'ListItem',
				'position' => $position,
				'item'     => [
					'@id'  => get_term_link( $product_category ),
					'name' => $product_category->name,
				],
			];
			
			$position++;
		}
	}
	
	// Add current page.
	if ( is_singular() ) {
		$breadcrumbs[] = [
			'@type'    => 'ListItem',
			'position' => $position,
			'item'     => [
				'@id'  => get_permalink(),
				'name' => get_the_title(),
			],
		];
	} elseif ( is_archive() ) {
		$breadcrumbs[] = [
			'@type'    => 'ListItem',
			'position' => $position,
			'item'     => [
				'@id'  => get_permalink(),
				'name' => wp_get_document_title(),
			],
		];
	}
	
	$schema = [
		'@type'           => 'BreadcrumbList',
		'@id'             => get_permalink() . '#breadcrumb',
		'itemListElement' => $breadcrumbs,
	];
	
	// Allow filtering of the breadcrumb schema.
	return apply_filters( 'aqualuxe_breadcrumb_schema', $schema );
}