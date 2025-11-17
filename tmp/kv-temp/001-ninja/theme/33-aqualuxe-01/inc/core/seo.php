<?php
/**
 * SEO Functions
 *
 * Functions for handling SEO optimizations throughout the theme.
 *
 * @package AquaLuxe
 */

/**
 * Add meta tags to head.
 */
function aqualuxe_meta_tags() {
	// Skip if an SEO plugin is active.
	if ( aqualuxe_is_seo_plugin_active() ) {
		return;
	}

	// Add viewport meta tag.
	echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";
	
	// Add mobile theme color.
	$theme_color = get_theme_mod( 'aqualuxe_theme_color', '#0073aa' );
	echo '<meta name="theme-color" content="' . esc_attr( $theme_color ) . '">' . "\n";
	
	// Add mobile app capable meta tag.
	echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
	echo '<meta name="mobile-web-app-capable" content="yes">' . "\n";
}
add_action( 'wp_head', 'aqualuxe_meta_tags', 1 );

/**
 * Add Open Graph tags.
 */
function aqualuxe_open_graph_tags() {
	// Skip if an SEO plugin is active.
	if ( aqualuxe_is_seo_plugin_active() ) {
		return;
	}

	global $post;

	// Default values.
	$og_type = 'website';
	$og_title = get_bloginfo( 'name' );
	$og_description = get_bloginfo( 'description' );
	$og_url = home_url( '/' );
	$og_image = get_theme_mod( 'aqualuxe_default_og_image', '' );
	$og_site_name = get_bloginfo( 'name' );

	// Customize values based on current page.
	if ( is_singular() && ! is_front_page() ) {
		$og_type = 'article';
		$og_title = get_the_title();
		$og_url = get_permalink();
		
		// Get excerpt or content for description.
		$og_description = $post->post_excerpt;
		if ( empty( $og_description ) ) {
			$og_description = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '...' );
		}
		
		// Get featured image.
		if ( has_post_thumbnail() ) {
			$og_image = get_the_post_thumbnail_url( $post, 'large' );
		}
	} elseif ( is_archive() || is_search() ) {
		$og_title = get_the_archive_title();
		$og_url = get_pagenum_link();
		
		if ( is_category() || is_tag() || is_tax() ) {
			$term = get_queried_object();
			if ( ! empty( $term->description ) ) {
				$og_description = wp_trim_words( $term->description, 30, '...' );
			}
		}
	}

	// Output Open Graph tags.
	echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $og_description ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $og_url ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( $og_site_name ) . '">' . "\n";
	
	if ( ! empty( $og_image ) ) {
		echo '<meta property="og:image" content="' . esc_url( $og_image ) . '">' . "\n";
		echo '<meta property="og:image:secure_url" content="' . esc_url( str_replace( 'http://', 'https://', $og_image ) ) . '">' . "\n";
		
		// Get image dimensions if possible.
		$image_id = attachment_url_to_postid( $og_image );
		if ( $image_id ) {
			$image_data = wp_get_attachment_image_src( $image_id, 'large' );
			if ( $image_data ) {
				echo '<meta property="og:image:width" content="' . esc_attr( $image_data[1] ) . '">' . "\n";
				echo '<meta property="og:image:height" content="' . esc_attr( $image_data[2] ) . '">' . "\n";
			}
		}
	}

	// Add article specific tags.
	if ( 'article' === $og_type && is_singular() ) {
		echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( 'c' ) ) . '">' . "\n";
		echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( 'c' ) ) . '">' . "\n";
		
		// Add author.
		$author_id = get_post_field( 'post_author', $post->ID );
		$author_name = get_the_author_meta( 'display_name', $author_id );
		echo '<meta property="article:author" content="' . esc_attr( $author_name ) . '">' . "\n";
		
		// Add categories and tags.
		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				echo '<meta property="article:section" content="' . esc_attr( $category->name ) . '">' . "\n";
			}
		}
		
		$tags = get_the_tags();
		if ( ! empty( $tags ) ) {
			foreach ( $tags as $tag ) {
				echo '<meta property="article:tag" content="' . esc_attr( $tag->name ) . '">' . "\n";
			}
		}
	}

	// Add publisher info for articles.
	$publisher_name = get_bloginfo( 'name' );
	$publisher_logo = get_theme_mod( 'aqualuxe_publisher_logo', '' );
	
	if ( ! empty( $publisher_logo ) ) {
		echo '<meta property="article:publisher" content="' . esc_attr( $publisher_name ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_open_graph_tags', 5 );

/**
 * Add Twitter Card tags.
 */
function aqualuxe_twitter_card_tags() {
	// Skip if an SEO plugin is active.
	if ( aqualuxe_is_seo_plugin_active() ) {
		return;
	}

	global $post;

	// Default values.
	$twitter_card = 'summary_large_image';
	$twitter_title = get_bloginfo( 'name' );
	$twitter_description = get_bloginfo( 'description' );
	$twitter_image = get_theme_mod( 'aqualuxe_default_twitter_image', '' );
	$twitter_site = get_theme_mod( 'aqualuxe_twitter_username', '' );

	// Customize values based on current page.
	if ( is_singular() && ! is_front_page() ) {
		$twitter_title = get_the_title();
		
		// Get excerpt or content for description.
		$twitter_description = $post->post_excerpt;
		if ( empty( $twitter_description ) ) {
			$twitter_description = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '...' );
		}
		
		// Get featured image.
		if ( has_post_thumbnail() ) {
			$twitter_image = get_the_post_thumbnail_url( $post, 'large' );
		}
	} elseif ( is_archive() || is_search() ) {
		$twitter_title = get_the_archive_title();
		
		if ( is_category() || is_tag() || is_tax() ) {
			$term = get_queried_object();
			if ( ! empty( $term->description ) ) {
				$twitter_description = wp_trim_words( $term->description, 30, '...' );
			}
		}
	}

	// Output Twitter Card tags.
	echo '<meta name="twitter:card" content="' . esc_attr( $twitter_card ) . '">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $twitter_title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $twitter_description ) . '">' . "\n";
	
	if ( ! empty( $twitter_image ) ) {
		echo '<meta name="twitter:image" content="' . esc_url( $twitter_image ) . '">' . "\n";
	}
	
	if ( ! empty( $twitter_site ) ) {
		echo '<meta name="twitter:site" content="@' . esc_attr( $twitter_site ) . '">' . "\n";
	}

	// Add creator tag for single posts.
	if ( is_singular() ) {
		$author_id = get_post_field( 'post_author', $post->ID );
		$twitter_creator = get_the_author_meta( 'twitter', $author_id );
		
		if ( ! empty( $twitter_creator ) ) {
			echo '<meta name="twitter:creator" content="@' . esc_attr( $twitter_creator ) . '">' . "\n";
		}
	}
}
add_action( 'wp_head', 'aqualuxe_twitter_card_tags', 5 );

/**
 * Add JSON-LD structured data.
 */
function aqualuxe_json_ld_structured_data() {
	// Skip if an SEO plugin is active.
	if ( aqualuxe_is_seo_plugin_active() ) {
		return;
	}

	global $post;

	// Website schema.
	$website_schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'WebSite',
		'url'      => home_url( '/' ),
		'name'     => get_bloginfo( 'name' ),
		'description' => get_bloginfo( 'description' ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => home_url( '/?s={search_term_string}' ),
			'query-input' => 'required name=search_term_string',
		),
	);

	// Organization schema.
	$organization_schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'url'      => home_url( '/' ),
		'name'     => get_bloginfo( 'name' ),
		'logo'     => get_theme_mod( 'aqualuxe_publisher_logo', '' ),
	);

	// Add social profiles to organization.
	$social_profiles = aqualuxe_get_social_profiles();
	if ( ! empty( $social_profiles ) ) {
		$organization_schema['sameAs'] = $social_profiles;
	}

	// Add contact info if available.
	$contact_address = get_theme_mod( 'aqualuxe_contact_address', '' );
	$contact_phone = get_theme_mod( 'aqualuxe_contact_phone', '' );
	$contact_email = get_theme_mod( 'aqualuxe_contact_email', '' );
	
	if ( ! empty( $contact_address ) || ! empty( $contact_phone ) || ! empty( $contact_email ) ) {
		$organization_schema['contactPoint'] = array(
			'@type' => 'ContactPoint',
			'contactType' => 'customer service',
		);
		
		if ( ! empty( $contact_phone ) ) {
			$organization_schema['contactPoint']['telephone'] = $contact_phone;
		}
		
		if ( ! empty( $contact_email ) ) {
			$organization_schema['contactPoint']['email'] = $contact_email;
		}
	}

	// Page/post specific schema.
	if ( is_singular() && ! is_front_page() ) {
		// Article schema for posts.
		if ( 'post' === get_post_type() ) {
			$article_schema = array(
				'@context' => 'https://schema.org',
				'@type'    => 'Article',
				'mainEntityOfPage' => array(
					'@type' => 'WebPage',
					'@id'   => get_permalink(),
				),
				'headline' => get_the_title(),
				'datePublished' => get_the_date( 'c' ),
				'dateModified'  => get_the_modified_date( 'c' ),
				'author' => array(
					'@type' => 'Person',
					'name'  => get_the_author(),
				),
				'publisher' => array(
					'@type' => 'Organization',
					'name'  => get_bloginfo( 'name' ),
					'logo'  => array(
						'@type'  => 'ImageObject',
						'url'    => get_theme_mod( 'aqualuxe_publisher_logo', '' ),
					),
				),
			);

			// Add featured image if available.
			if ( has_post_thumbnail() ) {
				$image_id = get_post_thumbnail_id();
				$image_data = wp_get_attachment_image_src( $image_id, 'full' );
				
				if ( $image_data ) {
					$article_schema['image'] = array(
						'@type'  => 'ImageObject',
						'url'    => $image_data[0],
						'width'  => $image_data[1],
						'height' => $image_data[2],
					);
				}
			}

			// Output article schema.
			echo '<script type="application/ld+json">' . wp_json_encode( $article_schema ) . '</script>' . "\n";
		}

		// Product schema for WooCommerce products.
		if ( class_exists( 'WooCommerce' ) && 'product' === get_post_type() ) {
			$product = wc_get_product( $post->ID );
			
			if ( $product ) {
				$product_schema = array(
					'@context' => 'https://schema.org',
					'@type'    => 'Product',
					'name'     => $product->get_name(),
					'description' => $product->get_short_description() ? $product->get_short_description() : $product->get_description(),
					'sku'      => $product->get_sku(),
					'offers'   => array(
						'@type'  => 'Offer',
						'price'  => $product->get_price(),
						'priceCurrency' => get_woocommerce_currency(),
						'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
						'url'    => get_permalink(),
					),
				);

				// Add product image.
				if ( has_post_thumbnail() ) {
					$image_id = get_post_thumbnail_id();
					$image_data = wp_get_attachment_image_src( $image_id, 'full' );
					
					if ( $image_data ) {
						$product_schema['image'] = $image_data[0];
					}
				}

				// Add product rating.
				if ( $product->get_rating_count() > 0 ) {
					$product_schema['aggregateRating'] = array(
						'@type'       => 'AggregateRating',
						'ratingValue' => $product->get_average_rating(),
						'reviewCount' => $product->get_review_count(),
					);
				}

				// Output product schema.
				echo '<script type="application/ld+json">' . wp_json_encode( $product_schema ) . '</script>' . "\n";
			}
		}
	}

	// Output website schema.
	echo '<script type="application/ld+json">' . wp_json_encode( $website_schema ) . '</script>' . "\n";
	
	// Output organization schema.
	echo '<script type="application/ld+json">' . wp_json_encode( $organization_schema ) . '</script>' . "\n";

	// BreadcrumbList schema.
	if ( ! is_front_page() && function_exists( 'aqualuxe_breadcrumbs' ) ) {
		$breadcrumb_schema = aqualuxe_generate_breadcrumb_schema();
		
		if ( ! empty( $breadcrumb_schema ) ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $breadcrumb_schema ) . '</script>' . "\n";
		}
	}
}
add_action( 'wp_footer', 'aqualuxe_json_ld_structured_data', 10 );

/**
 * Generate breadcrumb schema.
 *
 * @return array Breadcrumb schema.
 */
function aqualuxe_generate_breadcrumb_schema() {
	$breadcrumbs = array();
	$position = 1;

	// Add home.
	$breadcrumbs[] = array(
		'@type'    => 'ListItem',
		'position' => $position,
		'item'     => array(
			'@id'  => home_url( '/' ),
			'name' => __( 'Home', 'aqualuxe' ),
		),
	);

	// Build breadcrumbs based on current page.
	if ( is_category() || is_single() ) {
		if ( is_category() ) {
			$category = get_category( get_query_var( 'cat' ) );
			
			if ( $category->parent != 0 ) {
				$parent_categories = array();
				$parent_id = $category->parent;
				
				while ( $parent_id ) {
					$position++;
					$parent_category = get_term( $parent_id, 'category' );
					$parent_categories[] = array(
						'@type'    => 'ListItem',
						'position' => $position,
						'item'     => array(
							'@id'  => get_category_link( $parent_category->term_id ),
							'name' => $parent_category->name,
						),
					);
					$parent_id = $parent_category->parent;
				}
				
				$breadcrumbs = array_merge( $breadcrumbs, array_reverse( $parent_categories ) );
			}
			
			$position++;
			$breadcrumbs[] = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'item'     => array(
					'@id'  => get_category_link( $category->term_id ),
					'name' => $category->name,
				),
			);
		} elseif ( is_single() ) {
			if ( 'post' === get_post_type() ) {
				$categories = get_the_category();
				
				if ( ! empty( $categories ) ) {
					$category = $categories[0];
					
					if ( $category->parent != 0 ) {
						$parent_categories = array();
						$parent_id = $category->parent;
						
						while ( $parent_id ) {
							$position++;
							$parent_category = get_term( $parent_id, 'category' );
							$parent_categories[] = array(
								'@type'    => 'ListItem',
								'position' => $position,
								'item'     => array(
									'@id'  => get_category_link( $parent_category->term_id ),
									'name' => $parent_category->name,
								),
							);
							$parent_id = $parent_category->parent;
						}
						
						$breadcrumbs = array_merge( $breadcrumbs, array_reverse( $parent_categories ) );
					}
					
					$position++;
					$breadcrumbs[] = array(
						'@type'    => 'ListItem',
						'position' => $position,
						'item'     => array(
							'@id'  => get_category_link( $category->term_id ),
							'name' => $category->name,
						),
					);
				}
			} elseif ( 'product' === get_post_type() && class_exists( 'WooCommerce' ) ) {
				$position++;
				$breadcrumbs[] = array(
					'@type'    => 'ListItem',
					'position' => $position,
					'item'     => array(
						'@id'  => get_permalink( wc_get_page_id( 'shop' ) ),
						'name' => __( 'Shop', 'aqualuxe' ),
					),
				);
				
				$product_categories = get_the_terms( get_the_ID(), 'product_cat' );
				
				if ( ! empty( $product_categories ) ) {
					$product_category = $product_categories[0];
					
					if ( $product_category->parent != 0 ) {
						$parent_categories = array();
						$parent_id = $product_category->parent;
						
						while ( $parent_id ) {
							$position++;
							$parent_category = get_term( $parent_id, 'product_cat' );
							$parent_categories[] = array(
								'@type'    => 'ListItem',
								'position' => $position,
								'item'     => array(
									'@id'  => get_term_link( $parent_category ),
									'name' => $parent_category->name,
								),
							);
							$parent_id = $parent_category->parent;
						}
						
						$breadcrumbs = array_merge( $breadcrumbs, array_reverse( $parent_categories ) );
					}
					
					$position++;
					$breadcrumbs[] = array(
						'@type'    => 'ListItem',
						'position' => $position,
						'item'     => array(
							'@id'  => get_term_link( $product_category ),
							'name' => $product_category->name,
						),
					);
				}
			} else {
				$post_type = get_post_type_object( get_post_type() );
				$archive_link = get_post_type_archive_link( $post_type->name );
				
				if ( $archive_link ) {
					$position++;
					$breadcrumbs[] = array(
						'@type'    => 'ListItem',
						'position' => $position,
						'item'     => array(
							'@id'  => $archive_link,
							'name' => $post_type->labels->name,
						),
					);
				}
			}
			
			$position++;
			$breadcrumbs[] = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'item'     => array(
					'@id'  => get_permalink(),
					'name' => get_the_title(),
				),
			);
		}
	} elseif ( is_page() ) {
		if ( get_post()->post_parent ) {
			$parent_pages = array();
			$parent_id = get_post()->post_parent;
			
			while ( $parent_id ) {
				$position++;
				$parent_page = get_post( $parent_id );
				$parent_pages[] = array(
					'@type'    => 'ListItem',
					'position' => $position,
					'item'     => array(
						'@id'  => get_permalink( $parent_page->ID ),
						'name' => get_the_title( $parent_page->ID ),
					),
				);
				$parent_id = $parent_page->post_parent;
			}
			
			$breadcrumbs = array_merge( $breadcrumbs, array_reverse( $parent_pages ) );
		}
		
		$position++;
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'item'     => array(
				'@id'  => get_permalink(),
				'name' => get_the_title(),
			),
		);
	} elseif ( is_tag() ) {
		$position++;
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'item'     => array(
				'@id'  => get_tag_link( get_queried_object_id() ),
				'name' => single_tag_title( '', false ),
			),
		);
	} elseif ( is_author() ) {
		$position++;
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'item'     => array(
				'@id'  => get_author_posts_url( get_the_author_meta( 'ID' ) ),
				'name' => get_the_author(),
			),
		);
	} elseif ( is_search() ) {
		$position++;
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'item'     => array(
				'@id'  => get_search_link(),
				'name' => sprintf( __( 'Search results for "%s"', 'aqualuxe' ), get_search_query() ),
			),
		);
	} elseif ( is_404() ) {
		$position++;
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'item'     => array(
				'@id'  => home_url( '?s=' ),
				'name' => __( 'Error 404', 'aqualuxe' ),
			),
		);
	}

	// WooCommerce specific breadcrumbs.
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_shop() ) {
			$position++;
			$breadcrumbs[] = array(
				'@type'    => 'ListItem',
				'position' => $position,
				'item'     => array(
					'@id'  => get_permalink( wc_get_page_id( 'shop' ) ),
					'name' => __( 'Shop', 'aqualuxe' ),
				),
			);
		}
	}

	// Return schema.
	return array(
		'@context' => 'https://schema.org',
		'@type'    => 'BreadcrumbList',
		'itemListElement' => $breadcrumbs,
	);
}

/**
 * Modify title separator.
 *
 * @param string $sep Title separator.
 * @return string Modified title separator.
 */
function aqualuxe_title_separator( $sep ) {
	$separator = get_theme_mod( 'aqualuxe_title_separator', '|' );
	return $separator;
}
add_filter( 'document_title_separator', 'aqualuxe_title_separator' );

/**
 * Modify title parts.
 *
 * @param array $title Title parts.
 * @return array Modified title parts.
 */
function aqualuxe_title_parts( $title ) {
	// Skip if an SEO plugin is active.
	if ( aqualuxe_is_seo_plugin_active() ) {
		return $title;
	}

	// Add site name to end of title if enabled.
	if ( ! get_theme_mod( 'aqualuxe_add_site_name_to_title', true ) ) {
		unset( $title['site'] );
	}

	// Add tagline to title if enabled.
	if ( get_theme_mod( 'aqualuxe_add_tagline_to_title', false ) && is_front_page() ) {
		$title['tagline'] = get_bloginfo( 'description' );
	}

	return $title;
}
add_filter( 'document_title_parts', 'aqualuxe_title_parts' );

/**
 * Add rel attributes to links.
 *
 * @param string $content Post content.
 * @return string Modified content.
 */
function aqualuxe_add_rel_attributes( $content ) {
	// Add rel="noopener noreferrer" to external links.
	$content = preg_replace_callback(
		'/<a[^>]+href=([\'"])(?:https?:)?\/\/(?!(?:www\.)?' . preg_quote( parse_url( home_url(), PHP_URL_HOST ), '/' ) . ')[^"\']+\1[^>]*>/i',
		function( $matches ) {
			if ( strpos( $matches[0], 'rel=' ) === false ) {
				return str_replace( '<a ', '<a rel="noopener noreferrer" ', $matches[0] );
			} else {
				return preg_replace(
					'/rel=([\'"])([^\'"]+)\1/i',
					'rel=$1$2 noopener noreferrer$1',
					$matches[0]
				);
			}
		},
		$content
	);

	return $content;
}
add_filter( 'the_content', 'aqualuxe_add_rel_attributes' );

/**
 * Add missing alt text to images.
 *
 * @param string $content Post content.
 * @return string Modified content.
 */
function aqualuxe_add_missing_alt_text( $content ) {
	// Add alt attribute to images that don't have one.
	$content = preg_replace_callback(
		'/<img[^>]*(?:(?!alt=)[^>])*>/i',
		function( $matches ) {
			if ( strpos( $matches[0], 'alt=' ) === false ) {
				$post_title = get_the_title();
				return str_replace( '<img ', '<img alt="' . esc_attr( $post_title ) . '" ', $matches[0] );
			}
			return $matches[0];
		},
		$content
	);

	return $content;
}
add_filter( 'the_content', 'aqualuxe_add_missing_alt_text' );

/**
 * Add canonical URL.
 */
function aqualuxe_canonical_url() {
	// Skip if an SEO plugin is active.
	if ( aqualuxe_is_seo_plugin_active() ) {
		return;
	}

	$canonical_url = '';

	if ( is_singular() ) {
		$canonical_url = get_permalink();
	} elseif ( is_home() || is_front_page() ) {
		$canonical_url = home_url( '/' );
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
	} elseif ( is_search() ) {
		$canonical_url = get_search_link();
	} elseif ( is_post_type_archive() ) {
		$canonical_url = get_post_type_archive_link( get_post_type() );
	}

	if ( ! empty( $canonical_url ) ) {
		// Handle pagination.
		if ( get_query_var( 'paged' ) > 1 ) {
			$canonical_url = get_pagenum_link( get_query_var( 'paged' ) );
		}

		echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_canonical_url', 10 );

/**
 * Add robots meta.
 */
function aqualuxe_robots_meta() {
	// Skip if an SEO plugin is active.
	if ( aqualuxe_is_seo_plugin_active() ) {
		return;
	}

	$robots = array();

	// Default settings.
	if ( get_theme_mod( 'aqualuxe_enable_index', true ) ) {
		$robots[] = 'index';
	} else {
		$robots[] = 'noindex';
	}

	if ( get_theme_mod( 'aqualuxe_enable_follow', true ) ) {
		$robots[] = 'follow';
	} else {
		$robots[] = 'nofollow';
	}

	// Page-specific settings.
	if ( is_search() || is_404() ) {
		$robots = array( 'noindex', 'follow' );
	}

	if ( is_paged() && get_theme_mod( 'aqualuxe_noindex_paginated', false ) ) {
		$robots = array( 'noindex', 'follow' );
	}

	// Single post/page settings.
	if ( is_singular() ) {
		$robots_meta = get_post_meta( get_the_ID(), 'aqualuxe_robots', true );
		
		if ( ! empty( $robots_meta ) ) {
			$robots = explode( ',', $robots_meta );
		}
	}

	// Output robots meta.
	if ( ! empty( $robots ) ) {
		echo '<meta name="robots" content="' . esc_attr( implode( ', ', $robots ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_robots_meta', 1 );

/**
 * Add meta description.
 */
function aqualuxe_meta_description() {
	// Skip if an SEO plugin is active.
	if ( aqualuxe_is_seo_plugin_active() ) {
		return;
	}

	$description = '';

	if ( is_singular() ) {
		// Check for custom meta description.
		$description = get_post_meta( get_the_ID(), 'aqualuxe_meta_description', true );
		
		// If no custom description, use excerpt or content.
		if ( empty( $description ) ) {
			global $post;
			
			if ( ! empty( $post->post_excerpt ) ) {
				$description = $post->post_excerpt;
			} else {
				$description = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '...' );
			}
		}
	} elseif ( is_home() || is_front_page() ) {
		$description = get_bloginfo( 'description' );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		
		if ( ! empty( $term->description ) ) {
			$description = wp_trim_words( $term->description, 30, '...' );
		}
	} elseif ( is_author() ) {
		$description = get_the_author_meta( 'description', get_queried_object_id() );
	}

	if ( ! empty( $description ) ) {
		echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_meta_description', 1 );

/**
 * Add meta keywords.
 */
function aqualuxe_meta_keywords() {
	// Skip if an SEO plugin is active or if meta keywords are disabled.
	if ( aqualuxe_is_seo_plugin_active() || ! get_theme_mod( 'aqualuxe_enable_meta_keywords', false ) ) {
		return;
	}

	$keywords = '';

	if ( is_singular() ) {
		// Check for custom meta keywords.
		$keywords = get_post_meta( get_the_ID(), 'aqualuxe_meta_keywords', true );
		
		// If no custom keywords, use categories and tags.
		if ( empty( $keywords ) && 'post' === get_post_type() ) {
			$keywords_array = array();
			
			// Add categories.
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				foreach ( $categories as $category ) {
					$keywords_array[] = $category->name;
				}
			}
			
			// Add tags.
			$tags = get_the_tags();
			if ( ! empty( $tags ) ) {
				foreach ( $tags as $tag ) {
					$keywords_array[] = $tag->name;
				}
			}
			
			$keywords = implode( ', ', $keywords_array );
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term = get_queried_object();
		$keywords = $term->name;
	}

	if ( ! empty( $keywords ) ) {
		echo '<meta name="keywords" content="' . esc_attr( $keywords ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_meta_keywords', 1 );

/**
 * Add next/prev links for pagination.
 */
function aqualuxe_next_prev_links() {
	// Skip if an SEO plugin is active.
	if ( aqualuxe_is_seo_plugin_active() ) {
		return;
	}

	global $paged;

	if ( get_previous_posts_link() ) {
		echo '<link rel="prev" href="' . esc_url( get_pagenum_link( $paged - 1 ) ) . '">' . "\n";
	}

	if ( get_next_posts_link() ) {
		echo '<link rel="next" href="' . esc_url( get_pagenum_link( $paged + 1 ) ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_next_prev_links', 1 );

/**
 * Add hreflang links for multilingual sites.
 */
function aqualuxe_hreflang_links() {
	// Skip if an SEO plugin is active or if hreflang is disabled.
	if ( aqualuxe_is_seo_plugin_active() || ! get_theme_mod( 'aqualuxe_enable_hreflang', false ) ) {
		return;
	}

	// Check if WPML or Polylang is active.
	if ( function_exists( 'icl_get_languages' ) ) {
		// WPML.
		$languages = icl_get_languages( 'skip_missing=0' );
		
		if ( ! empty( $languages ) ) {
			foreach ( $languages as $language ) {
				echo '<link rel="alternate" hreflang="' . esc_attr( $language['language_code'] ) . '" href="' . esc_url( $language['url'] ) . '">' . "\n";
			}
		}
	} elseif ( function_exists( 'pll_the_languages' ) ) {
		// Polylang.
		$languages = pll_the_languages( array( 'raw' => 1 ) );
		
		if ( ! empty( $languages ) ) {
			foreach ( $languages as $language ) {
				echo '<link rel="alternate" hreflang="' . esc_attr( $language['slug'] ) . '" href="' . esc_url( $language['url'] ) . '">' . "\n";
			}
		}
	}
}
add_action( 'wp_head', 'aqualuxe_hreflang_links', 1 );

/**
 * Get social profiles.
 *
 * @return array Social profiles.
 */
function aqualuxe_get_social_profiles() {
	$social_profiles = array();
	
	$facebook = get_theme_mod( 'aqualuxe_facebook_url', '' );
	if ( ! empty( $facebook ) ) {
		$social_profiles[] = $facebook;
	}
	
	$twitter = get_theme_mod( 'aqualuxe_twitter_url', '' );
	if ( ! empty( $twitter ) ) {
		$social_profiles[] = $twitter;
	}
	
	$instagram = get_theme_mod( 'aqualuxe_instagram_url', '' );
	if ( ! empty( $instagram ) ) {
		$social_profiles[] = $instagram;
	}
	
	$linkedin = get_theme_mod( 'aqualuxe_linkedin_url', '' );
	if ( ! empty( $linkedin ) ) {
		$social_profiles[] = $linkedin;
	}
	
	$youtube = get_theme_mod( 'aqualuxe_youtube_url', '' );
	if ( ! empty( $youtube ) ) {
		$social_profiles[] = $youtube;
	}
	
	$pinterest = get_theme_mod( 'aqualuxe_pinterest_url', '' );
	if ( ! empty( $pinterest ) ) {
		$social_profiles[] = $pinterest;
	}
	
	return $social_profiles;
}

/**
 * Check if an SEO plugin is active.
 *
 * @return bool True if an SEO plugin is active, false otherwise.
 */
function aqualuxe_is_seo_plugin_active() {
	$seo_plugins = array(
		'wordpress-seo/wp-seo.php',                // Yoast SEO.
		'wordpress-seo-premium/wp-seo-premium.php', // Yoast SEO Premium.
		'all-in-one-seo-pack/all_in_one_seo_pack.php', // All in One SEO Pack.
		'seo-by-rank-math/rank-math.php',          // Rank Math SEO.
		'seo-ultimate/seo-ultimate.php',           // SEO Ultimate.
		'the-seo-framework/the-seo-framework.php', // The SEO Framework.
	);

	foreach ( $seo_plugins as $plugin ) {
		if ( is_plugin_active( $plugin ) ) {
			return true;
		}
	}

	return false;
}