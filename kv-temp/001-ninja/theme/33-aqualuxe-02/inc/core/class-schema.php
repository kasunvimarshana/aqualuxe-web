<?php
/**
 * Schema Class
 *
 * Handles structured data schema implementation for the theme.
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Service;

/**
 * Schema class.
 */
class Schema extends Service {

	/**
	 * Register service features.
	 */
	public function register() {
		// Only run schema if enabled
		if ( ! get_theme_mod( 'aqualuxe_enable_schema', true ) ) {
			return;
		}

		// Add JSON-LD structured data
		add_action( 'wp_footer', array( $this, 'add_json_ld_structured_data' ), 10 );
		
		// Add schema filters
		add_filter( 'aqualuxe_schema_website', array( $this, 'website_schema' ) );
		add_filter( 'aqualuxe_schema_organization', array( $this, 'organization_schema' ) );
		add_filter( 'aqualuxe_schema_article', array( $this, 'article_schema' ) );
		add_filter( 'aqualuxe_schema_product', array( $this, 'product_schema' ) );
		add_filter( 'aqualuxe_schema_breadcrumb', array( $this, 'breadcrumb_schema' ) );
		add_filter( 'aqualuxe_schema_person', array( $this, 'person_schema' ) );
		add_filter( 'aqualuxe_schema_local_business', array( $this, 'local_business_schema' ) );
		add_filter( 'aqualuxe_schema_faq', array( $this, 'faq_schema' ) );
		add_filter( 'aqualuxe_schema_how_to', array( $this, 'how_to_schema' ) );
		add_filter( 'aqualuxe_schema_review', array( $this, 'review_schema' ) );
		add_filter( 'aqualuxe_schema_event', array( $this, 'event_schema' ) );
	}

	/**
	 * Add JSON-LD structured data.
	 */
	public function add_json_ld_structured_data() {
		// Skip if an SEO plugin is active
		if ( $this->is_seo_plugin_active() ) {
			return;
		}

		// Website schema
		$website_schema = apply_filters( 'aqualuxe_schema_website', array() );
		if ( ! empty( $website_schema ) ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $website_schema ) . '</script>' . "\n";
		}

		// Organization schema
		$organization_schema = apply_filters( 'aqualuxe_schema_organization', array() );
		if ( ! empty( $organization_schema ) ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $organization_schema ) . '</script>' . "\n";
		}

		// Page/post specific schema
		if ( is_singular() && ! is_front_page() ) {
			// Article schema for posts
			if ( 'post' === get_post_type() ) {
				$article_schema = apply_filters( 'aqualuxe_schema_article', array() );
				if ( ! empty( $article_schema ) ) {
					echo '<script type="application/ld+json">' . wp_json_encode( $article_schema ) . '</script>' . "\n";
				}
			}

			// Product schema for WooCommerce products
			if ( class_exists( 'WooCommerce' ) && 'product' === get_post_type() ) {
				$product_schema = apply_filters( 'aqualuxe_schema_product', array() );
				if ( ! empty( $product_schema ) ) {
					echo '<script type="application/ld+json">' . wp_json_encode( $product_schema ) . '</script>' . "\n";
				}
			}

			// Person schema for author pages
			if ( is_author() ) {
				$person_schema = apply_filters( 'aqualuxe_schema_person', array() );
				if ( ! empty( $person_schema ) ) {
					echo '<script type="application/ld+json">' . wp_json_encode( $person_schema ) . '</script>' . "\n";
				}
			}

			// FAQ schema for FAQ pages
			if ( is_page_template( 'templates/template-faq.php' ) || has_shortcode( get_post()->post_content, 'aqualuxe_faq' ) ) {
				$faq_schema = apply_filters( 'aqualuxe_schema_faq', array() );
				if ( ! empty( $faq_schema ) ) {
					echo '<script type="application/ld+json">' . wp_json_encode( $faq_schema ) . '</script>' . "\n";
				}
			}

			// How-to schema for how-to pages
			if ( is_page_template( 'templates/template-how-to.php' ) || has_shortcode( get_post()->post_content, 'aqualuxe_how_to' ) ) {
				$how_to_schema = apply_filters( 'aqualuxe_schema_how_to', array() );
				if ( ! empty( $how_to_schema ) ) {
					echo '<script type="application/ld+json">' . wp_json_encode( $how_to_schema ) . '</script>' . "\n";
				}
			}

			// Review schema for review pages
			if ( is_page_template( 'templates/template-review.php' ) || has_shortcode( get_post()->post_content, 'aqualuxe_review' ) ) {
				$review_schema = apply_filters( 'aqualuxe_schema_review', array() );
				if ( ! empty( $review_schema ) ) {
					echo '<script type="application/ld+json">' . wp_json_encode( $review_schema ) . '</script>' . "\n";
				}
			}

			// Event schema for event pages
			if ( is_page_template( 'templates/template-event.php' ) || has_shortcode( get_post()->post_content, 'aqualuxe_event' ) ) {
				$event_schema = apply_filters( 'aqualuxe_schema_event', array() );
				if ( ! empty( $event_schema ) ) {
					echo '<script type="application/ld+json">' . wp_json_encode( $event_schema ) . '</script>' . "\n";
				}
			}
		}

		// Local business schema
		if ( get_theme_mod( 'aqualuxe_enable_local_business_schema', false ) ) {
			$local_business_schema = apply_filters( 'aqualuxe_schema_local_business', array() );
			if ( ! empty( $local_business_schema ) ) {
				echo '<script type="application/ld+json">' . wp_json_encode( $local_business_schema ) . '</script>' . "\n";
			}
		}

		// BreadcrumbList schema
		if ( ! is_front_page() && function_exists( 'aqualuxe_breadcrumbs' ) ) {
			$breadcrumb_schema = apply_filters( 'aqualuxe_schema_breadcrumb', array() );
			if ( ! empty( $breadcrumb_schema ) ) {
				echo '<script type="application/ld+json">' . wp_json_encode( $breadcrumb_schema ) . '</script>' . "\n";
			}
		}
	}

	/**
	 * Website schema.
	 *
	 * @param array $schema Schema data.
	 * @return array Modified schema data.
	 */
	public function website_schema( $schema ) {
		$schema = array(
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

		// Add alternate name if available
		$alternate_name = get_theme_mod( 'aqualuxe_site_alternate_name', '' );
		if ( ! empty( $alternate_name ) ) {
			$schema['alternateName'] = $alternate_name;
		}

		return $schema;
	}

	/**
	 * Organization schema.
	 *
	 * @param array $schema Schema data.
	 * @return array Modified schema data.
	 */
	public function organization_schema( $schema ) {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Organization',
			'url'      => home_url( '/' ),
			'name'     => get_bloginfo( 'name' ),
		);

		// Add logo if available
		$logo = get_theme_mod( 'aqualuxe_publisher_logo', '' );
		if ( ! empty( $logo ) ) {
			$schema['logo'] = array(
				'@type' => 'ImageObject',
				'url'   => $logo,
			);
		}

		// Add social profiles
		$social_profiles = $this->get_social_profiles();
		if ( ! empty( $social_profiles ) ) {
			$schema['sameAs'] = $social_profiles;
		}

		// Add contact info if available
		$contact_address = get_theme_mod( 'aqualuxe_contact_address', '' );
		$contact_phone = get_theme_mod( 'aqualuxe_contact_phone', '' );
		$contact_email = get_theme_mod( 'aqualuxe_contact_email', '' );
		
		if ( ! empty( $contact_address ) || ! empty( $contact_phone ) || ! empty( $contact_email ) ) {
			$schema['contactPoint'] = array(
				'@type' => 'ContactPoint',
				'contactType' => 'customer service',
			);
			
			if ( ! empty( $contact_phone ) ) {
				$schema['contactPoint']['telephone'] = $contact_phone;
			}
			
			if ( ! empty( $contact_email ) ) {
				$schema['contactPoint']['email'] = $contact_email;
			}
		}

		// Add founder if available
		$founder_name = get_theme_mod( 'aqualuxe_founder_name', '' );
		if ( ! empty( $founder_name ) ) {
			$schema['founder'] = array(
				'@type' => 'Person',
				'name'  => $founder_name,
			);
		}

		// Add founding date if available
		$founding_date = get_theme_mod( 'aqualuxe_founding_date', '' );
		if ( ! empty( $founding_date ) ) {
			$schema['foundingDate'] = $founding_date;
		}

		return $schema;
	}

	/**
	 * Article schema.
	 *
	 * @param array $schema Schema data.
	 * @return array Modified schema data.
	 */
	public function article_schema( $schema ) {
		global $post;

		$schema = array(
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
				'url'   => get_author_posts_url( get_the_author_meta( 'ID' ) ),
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

		// Add featured image if available
		if ( has_post_thumbnail() ) {
			$image_id = get_post_thumbnail_id();
			$image_data = wp_get_attachment_image_src( $image_id, 'full' );
			
			if ( $image_data ) {
				$schema['image'] = array(
					'@type'  => 'ImageObject',
					'url'    => $image_data[0],
					'width'  => $image_data[1],
					'height' => $image_data[2],
				);
			}
		}

		// Add description
		$description = $post->post_excerpt;
		if ( empty( $description ) ) {
			$description = wp_trim_words( wp_strip_all_tags( $post->post_content ), 30, '...' );
		}
		$schema['description'] = $description;

		// Add article section and keywords
		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			$schema['articleSection'] = $categories[0]->name;
		}

		$tags = get_the_tags();
		if ( ! empty( $tags ) ) {
			$keywords = array();
			foreach ( $tags as $tag ) {
				$keywords[] = $tag->name;
			}
			$schema['keywords'] = implode( ', ', $keywords );
		}

		// Add word count
		$schema['wordCount'] = str_word_count( strip_tags( $post->post_content ) );

		return $schema;
	}

	/**
	 * Product schema.
	 *
	 * @param array $schema Schema data.
	 * @return array Modified schema data.
	 */
	public function product_schema( $schema ) {
		global $post;

		$product = wc_get_product( $post->ID );
		
		if ( ! $product ) {
			return $schema;
		}

		$schema = array(
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
				'priceValidUntil' => date( 'Y-12-31', strtotime( '+1 year' ) ),
			),
		);

		// Add product image
		if ( has_post_thumbnail() ) {
			$image_id = get_post_thumbnail_id();
			$image_data = wp_get_attachment_image_src( $image_id, 'full' );
			
			if ( $image_data ) {
				$schema['image'] = $image_data[0];
			}
		}

		// Add product brand
		$brands = get_the_terms( $post->ID, 'product_brand' );
		if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) {
			$schema['brand'] = array(
				'@type' => 'Brand',
				'name'  => $brands[0]->name,
			);
		}

		// Add product rating
		if ( $product->get_rating_count() > 0 ) {
			$schema['aggregateRating'] = array(
				'@type'       => 'AggregateRating',
				'ratingValue' => $product->get_average_rating(),
				'reviewCount' => $product->get_review_count(),
				'bestRating'  => '5',
				'worstRating' => '1',
			);
		}

		// Add product reviews
		$reviews = get_comments( array(
			'post_id' => $post->ID,
			'status'  => 'approve',
			'type'    => 'review',
		) );

		if ( ! empty( $reviews ) ) {
			$schema_reviews = array();
			
			foreach ( $reviews as $review ) {
				$rating = get_comment_meta( $review->comment_ID, 'rating', true );
				
				if ( ! empty( $rating ) ) {
					$schema_reviews[] = array(
						'@type'         => 'Review',
						'reviewRating'  => array(
							'@type'       => 'Rating',
							'ratingValue' => $rating,
							'bestRating'  => '5',
							'worstRating' => '1',
						),
						'author'        => array(
							'@type' => 'Person',
							'name'  => $review->comment_author,
						),
						'reviewBody'    => $review->comment_content,
						'datePublished' => get_comment_date( 'c', $review->comment_ID ),
					);
				}
			}
			
			if ( ! empty( $schema_reviews ) ) {
				$schema['review'] = $schema_reviews;
			}
		}

		return $schema;
	}

	/**
	 * Breadcrumb schema.
	 *
	 * @param array $schema Schema data.
	 * @return array Modified schema data.
	 */
	public function breadcrumb_schema( $schema ) {
		$breadcrumbs = array();
		$position = 1;

		// Add home
		$breadcrumbs[] = array(
			'@type'    => 'ListItem',
			'position' => $position,
			'item'     => array(
				'@id'  => home_url( '/' ),
				'name' => __( 'Home', 'aqualuxe' ),
			),
		);

		// Build breadcrumbs based on current page
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

		// WooCommerce specific breadcrumbs
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

		// Return schema
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'BreadcrumbList',
			'itemListElement' => $breadcrumbs,
		);

		return $schema;
	}

	/**
	 * Person schema.
	 *
	 * @param array $schema Schema data.
	 * @return array Modified schema data.
	 */
	public function person_schema( $schema ) {
		$author_id = get_queried_object_id();
		
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Person',
			'name'     => get_the_author_meta( 'display_name', $author_id ),
			'url'      => get_author_posts_url( $author_id ),
		);

		// Add description
		$description = get_the_author_meta( 'description', $author_id );
		if ( ! empty( $description ) ) {
			$schema['description'] = $description;
		}

		// Add image
		$avatar_url = get_avatar_url( $author_id, array( 'size' => 96 ) );
		if ( $avatar_url ) {
			$schema['image'] = $avatar_url;
		}

		// Add social profiles
		$social_profiles = array();
		
		$website = get_the_author_meta( 'url', $author_id );
		if ( ! empty( $website ) ) {
			$social_profiles[] = $website;
		}
		
		$twitter = get_the_author_meta( 'twitter', $author_id );
		if ( ! empty( $twitter ) ) {
			$social_profiles[] = 'https://twitter.com/' . $twitter;
		}
		
		$facebook = get_the_author_meta( 'facebook', $author_id );
		if ( ! empty( $facebook ) ) {
			$social_profiles[] = $facebook;
		}
		
		$linkedin = get_the_author_meta( 'linkedin', $author_id );
		if ( ! empty( $linkedin ) ) {
			$social_profiles[] = $linkedin;
		}
		
		$instagram = get_the_author_meta( 'instagram', $author_id );
		if ( ! empty( $instagram ) ) {
			$social_profiles[] = 'https://instagram.com/' . $instagram;
		}
		
		if ( ! empty( $social_profiles ) ) {
			$schema['sameAs'] = $social_profiles;
		}

		// Add job title
		$job_title = get_the_author_meta( 'job_title', $author_id );
		if ( ! empty( $job_title ) ) {
			$schema['jobTitle'] = $job_title;
		}

		return $schema;
	}

	/**
	 * Local business schema.
	 *
	 * @param array $schema Schema data.
	 * @return array Modified schema data.
	 */
	public function local_business_schema( $schema ) {
		$business_type = get_theme_mod( 'aqualuxe_business_type', 'LocalBusiness' );
		$business_name = get_theme_mod( 'aqualuxe_business_name', get_bloginfo( 'name' ) );
		$business_description = get_theme_mod( 'aqualuxe_business_description', get_bloginfo( 'description' ) );
		$business_logo = get_theme_mod( 'aqualuxe_business_logo', get_theme_mod( 'aqualuxe_publisher_logo', '' ) );
		$business_image = get_theme_mod( 'aqualuxe_business_image', '' );
		$business_telephone = get_theme_mod( 'aqualuxe_contact_phone', '' );
		$business_email = get_theme_mod( 'aqualuxe_contact_email', '' );
		$business_address = get_theme_mod( 'aqualuxe_contact_address', '' );
		$business_price_range = get_theme_mod( 'aqualuxe_business_price_range', '$$' );
		$business_opening_hours = get_theme_mod( 'aqualuxe_business_opening_hours', '' );
		
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => $business_type,
			'name'     => $business_name,
			'url'      => home_url( '/' ),
		);

		// Add description
		if ( ! empty( $business_description ) ) {
			$schema['description'] = $business_description;
		}

		// Add logo
		if ( ! empty( $business_logo ) ) {
			$schema['logo'] = $business_logo;
		}

		// Add image
		if ( ! empty( $business_image ) ) {
			$schema['image'] = $business_image;
		}

		// Add telephone
		if ( ! empty( $business_telephone ) ) {
			$schema['telephone'] = $business_telephone;
		}

		// Add email
		if ( ! empty( $business_email ) ) {
			$schema['email'] = $business_email;
		}

		// Add address
		if ( ! empty( $business_address ) ) {
			// Parse address
			$address_parts = explode( ',', $business_address );
			
			$schema['address'] = array(
				'@type' => 'PostalAddress',
				'streetAddress' => isset( $address_parts[0] ) ? trim( $address_parts[0] ) : '',
				'addressLocality' => isset( $address_parts[1] ) ? trim( $address_parts[1] ) : '',
				'addressRegion' => isset( $address_parts[2] ) ? trim( $address_parts[2] ) : '',
				'postalCode' => isset( $address_parts[3] ) ? trim( $address_parts[3] ) : '',
				'addressCountry' => isset( $address_parts[4] ) ? trim( $address_parts[4] ) : '',
			);
		}

		// Add price range
		if ( ! empty( $business_price_range ) ) {
			$schema['priceRange'] = $business_price_range;
		}

		// Add opening hours
		if ( ! empty( $business_opening_hours ) ) {
			$opening_hours = explode( "\n", $business_opening_hours );
			$schema['openingHoursSpecification'] = array();
			
			foreach ( $opening_hours as $hours ) {
				$parts = explode( ':', $hours, 2 );
				
				if ( count( $parts ) === 2 ) {
					$days = trim( $parts[0] );
					$hours = trim( $parts[1] );
					
					$days_array = explode( ',', $days );
					$hours_parts = explode( '-', $hours );
					
					if ( count( $hours_parts ) === 2 ) {
						$opens = trim( $hours_parts[0] );
						$closes = trim( $hours_parts[1] );
						
						$schema['openingHoursSpecification'][] = array(
							'@type' => 'OpeningHoursSpecification',
							'dayOfWeek' => $days_array,
							'opens' => $opens,
							'closes' => $closes,
						);
					}
				}
			}
		}

		// Add social profiles
		$social_profiles = $this->get_social_profiles();
		if ( ! empty( $social_profiles ) ) {
			$schema['sameAs'] = $social_profiles;
		}

		return $schema;
	}

	/**
	 * FAQ schema.
	 *
	 * @param array $schema Schema data.
	 * @return array Modified schema data.
	 */
	public function faq_schema( $schema ) {
		global $post;

		// Get FAQ items from post meta
		$faq_items = get_post_meta( $post->ID, 'aqualuxe_faq_items', true );
		
		if ( empty( $faq_items ) && has_shortcode( $post->post_content, 'aqualuxe_faq' ) ) {
			// Extract FAQ items from shortcode
			$pattern = get_shortcode_regex( array( 'aqualuxe_faq_item' ) );
			preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches );
			
			if ( ! empty( $matches[3] ) ) {
				$faq_items = array();
				
				foreach ( $matches[3] as $index => $attr_str ) {
					$attrs = shortcode_parse_atts( $attr_str );
					$content = $matches[5][ $index ];
					
					if ( isset( $attrs['question'] ) && ! empty( $content ) ) {
						$faq_items[] = array(
							'question' => $attrs['question'],
							'answer'   => $content,
						);
					}
				}
			}
		}

		if ( ! empty( $faq_items ) ) {
			$schema = array(
				'@context' => 'https://schema.org',
				'@type'    => 'FAQPage',
				'mainEntity' => array(),
			);
			
			foreach ( $faq_items as $item ) {
				$schema['mainEntity'][] = array(
					'@type' => 'Question',
					'name'  => $item['question'],
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $item['answer'],
					),
				);
			}
		}

		return $schema;
	}

	/**
	 * How-to schema.
	 *
	 * @param array $schema Schema data.
	 * @return array Modified schema data.
	 */
	public function how_to_schema( $schema ) {
		global $post;

		// Get how-to data from post meta
		$how_to_name = get_post_meta( $post->ID, 'aqualuxe_how_to_name', true );
		$how_to_description = get_post_meta( $post->ID, 'aqualuxe_how_to_description', true );
		$how_to_total_time = get_post_meta( $post->ID, 'aqualuxe_how_to_total_time', true );
		$how_to_supplies = get_post_meta( $post->ID, 'aqualuxe_how_to_supplies', true );
		$how_to_tools = get_post_meta( $post->ID, 'aqualuxe_how_to_tools', true );
		$how_to_steps = get_post_meta( $post->ID, 'aqualuxe_how_to_steps', true );
		
		if ( empty( $how_to_name ) ) {
			$how_to_name = get_the_title();
		}
		
		if ( empty( $how_to_description ) ) {
			$how_to_description = get_the_excerpt();
		}

		if ( ! empty( $how_to_name ) && ! empty( $how_to_steps ) ) {
			$schema = array(
				'@context' => 'https://schema.org',
				'@type'    => 'HowTo',
				'name'     => $how_to_name,
				'description' => $how_to_description,
			);

			// Add total time
			if ( ! empty( $how_to_total_time ) ) {
				$schema['totalTime'] = $how_to_total_time;
			}

			// Add supplies
			if ( ! empty( $how_to_supplies ) ) {
				$supplies = explode( "\n", $how_to_supplies );
				$schema['supply'] = array();
				
				foreach ( $supplies as $supply ) {
					$schema['supply'][] = array(
						'@type' => 'HowToSupply',
						'name'  => trim( $supply ),
					);
				}
			}

			// Add tools
			if ( ! empty( $how_to_tools ) ) {
				$tools = explode( "\n", $how_to_tools );
				$schema['tool'] = array();
				
				foreach ( $tools as $tool ) {
					$schema['tool'][] = array(
						'@type' => 'HowToTool',
						'name'  => trim( $tool ),
					);
				}
			}

			// Add steps
			if ( ! empty( $how_to_steps ) ) {
				$schema['step'] = array();
				
				foreach ( $how_to_steps as $index => $step ) {
					$step_schema = array(
						'@type' => 'HowToStep',
						'position' => $index + 1,
						'name' => isset( $step['title'] ) ? $step['title'] : '',
						'text' => isset( $step['description'] ) ? $step['description'] : '',
					);
					
					if ( isset( $step['image'] ) && ! empty( $step['image'] ) ) {
						$step_schema['image'] = $step['image'];
					}
					
					$schema['step'][] = $step_schema;
				}
			}

			// Add image
			if ( has_post_thumbnail() ) {
				$schema['image'] = get_the_post_thumbnail_url( $post->ID, 'full' );
			}
		}

		return $schema;
	}

	/**
	 * Review schema.
	 *
	 * @param array $schema Schema data.
	 * @return array Modified schema data.
	 */
	public function review_schema( $schema ) {
		global $post;

		// Get review data from post meta
		$review_item_name = get_post_meta( $post->ID, 'aqualuxe_review_item_name', true );
		$review_item_type = get_post_meta( $post->ID, 'aqualuxe_review_item_type', true );
		$review_rating = get_post_meta( $post->ID, 'aqualuxe_review_rating', true );
		$review_author = get_post_meta( $post->ID, 'aqualuxe_review_author', true );
		$review_summary = get_post_meta( $post->ID, 'aqualuxe_review_summary', true );
		
		if ( empty( $review_item_name ) ) {
			$review_item_name = get_the_title();
		}
		
		if ( empty( $review_item_type ) ) {
			$review_item_type = 'Product';
		}
		
		if ( empty( $review_author ) ) {
			$review_author = get_the_author();
		}
		
		if ( empty( $review_summary ) ) {
			$review_summary = get_the_excerpt();
		}

		if ( ! empty( $review_item_name ) && ! empty( $review_rating ) ) {
			$schema = array(
				'@context' => 'https://schema.org',
				'@type'    => 'Review',
				'itemReviewed' => array(
					'@type' => $review_item_type,
					'name'  => $review_item_name,
				),
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $review_rating,
					'bestRating'  => '5',
					'worstRating' => '1',
				),
				'author' => array(
					'@type' => 'Person',
					'name'  => $review_author,
				),
				'reviewBody' => $review_summary,
				'datePublished' => get_the_date( 'c' ),
			);

			// Add image
			if ( has_post_thumbnail() ) {
				$schema['itemReviewed']['image'] = get_the_post_thumbnail_url( $post->ID, 'full' );
			}
		}

		return $schema;
	}

	/**
	 * Event schema.
	 *
	 * @param array $schema Schema data.
	 * @return array Modified schema data.
	 */
	public function event_schema( $schema ) {
		global $post;

		// Get event data from post meta
		$event_name = get_post_meta( $post->ID, 'aqualuxe_event_name', true );
		$event_description = get_post_meta( $post->ID, 'aqualuxe_event_description', true );
		$event_start_date = get_post_meta( $post->ID, 'aqualuxe_event_start_date', true );
		$event_end_date = get_post_meta( $post->ID, 'aqualuxe_event_end_date', true );
		$event_location_name = get_post_meta( $post->ID, 'aqualuxe_event_location_name', true );
		$event_location_address = get_post_meta( $post->ID, 'aqualuxe_event_location_address', true );
		$event_offers_price = get_post_meta( $post->ID, 'aqualuxe_event_offers_price', true );
		$event_offers_currency = get_post_meta( $post->ID, 'aqualuxe_event_offers_currency', true );
		$event_offers_url = get_post_meta( $post->ID, 'aqualuxe_event_offers_url', true );
		$event_performer = get_post_meta( $post->ID, 'aqualuxe_event_performer', true );
		$event_organizer = get_post_meta( $post->ID, 'aqualuxe_event_organizer', true );
		
		if ( empty( $event_name ) ) {
			$event_name = get_the_title();
		}
		
		if ( empty( $event_description ) ) {
			$event_description = get_the_excerpt();
		}

		if ( ! empty( $event_name ) && ! empty( $event_start_date ) ) {
			$schema = array(
				'@context' => 'https://schema.org',
				'@type'    => 'Event',
				'name'     => $event_name,
				'description' => $event_description,
				'startDate' => $event_start_date,
			);

			// Add end date
			if ( ! empty( $event_end_date ) ) {
				$schema['endDate'] = $event_end_date;
			}

			// Add location
			if ( ! empty( $event_location_name ) || ! empty( $event_location_address ) ) {
				$schema['location'] = array(
					'@type' => 'Place',
					'name'  => $event_location_name,
				);
				
				if ( ! empty( $event_location_address ) ) {
					// Parse address
					$address_parts = explode( ',', $event_location_address );
					
					$schema['location']['address'] = array(
						'@type' => 'PostalAddress',
						'streetAddress' => isset( $address_parts[0] ) ? trim( $address_parts[0] ) : '',
						'addressLocality' => isset( $address_parts[1] ) ? trim( $address_parts[1] ) : '',
						'addressRegion' => isset( $address_parts[2] ) ? trim( $address_parts[2] ) : '',
						'postalCode' => isset( $address_parts[3] ) ? trim( $address_parts[3] ) : '',
						'addressCountry' => isset( $address_parts[4] ) ? trim( $address_parts[4] ) : '',
					);
				}
			}

			// Add offers
			if ( ! empty( $event_offers_price ) ) {
				$schema['offers'] = array(
					'@type' => 'Offer',
					'price' => $event_offers_price,
					'priceCurrency' => ! empty( $event_offers_currency ) ? $event_offers_currency : 'USD',
				);
				
				if ( ! empty( $event_offers_url ) ) {
					$schema['offers']['url'] = $event_offers_url;
				}
			}

			// Add performer
			if ( ! empty( $event_performer ) ) {
				$schema['performer'] = array(
					'@type' => 'Person',
					'name'  => $event_performer,
				);
			}

			// Add organizer
			if ( ! empty( $event_organizer ) ) {
				$schema['organizer'] = array(
					'@type' => 'Organization',
					'name'  => $event_organizer,
				);
			}

			// Add image
			if ( has_post_thumbnail() ) {
				$schema['image'] = get_the_post_thumbnail_url( $post->ID, 'full' );
			}

			// Add URL
			$schema['url'] = get_permalink();
		}

		return $schema;
	}

	/**
	 * Get social profiles.
	 *
	 * @return array Social profiles.
	 */
	private function get_social_profiles() {
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
	private function is_seo_plugin_active() {
		$seo_plugins = array(
			'wordpress-seo/wp-seo.php',                // Yoast SEO
			'wordpress-seo-premium/wp-seo-premium.php', // Yoast SEO Premium
			'all-in-one-seo-pack/all_in_one_seo_pack.php', // All in One SEO Pack
			'seo-by-rank-math/rank-math.php',          // Rank Math SEO
			'seo-ultimate/seo-ultimate.php',           // SEO Ultimate
			'the-seo-framework/the-seo-framework.php', // The SEO Framework
		);

		foreach ( $seo_plugins as $plugin ) {
			if ( is_plugin_active( $plugin ) ) {
				return true;
			}
		}

		return false;
	}
}