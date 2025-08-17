<?php
/**
 * Schema.org markup implementation
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe Schema Class
 */
class AquaLuxe_Schema {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add schema markup to head.
		add_action( 'wp_head', array( $this, 'add_schema_markup' ) );
		
		// Add Open Graph tags.
		add_action( 'wp_head', array( $this, 'add_open_graph_tags' ) );
		
		// Add Twitter Card tags.
		add_action( 'wp_head', array( $this, 'add_twitter_card_tags' ) );
	}

	/**
	 * Add schema markup to head
	 */
	public function add_schema_markup() {
		// Get the appropriate schema for the current page.
		$schema = $this->get_schema();
		
		if ( ! empty( $schema ) ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . '</script>';
		}
	}

	/**
	 * Get schema for the current page
	 *
	 * @return array Schema data.
	 */
	public function get_schema() {
		// Default schema.
		$schema = array();
		
		// Website schema (always included).
		$schema['@context'] = 'https://schema.org';
		$schema['@graph'] = array();
		
		// Add website schema.
		$schema['@graph'][] = $this->get_website_schema();
		
		// Add organization schema.
		$schema['@graph'][] = $this->get_organization_schema();
		
		// Add page-specific schema.
		if ( is_front_page() || is_home() ) {
			// Homepage schema.
			$schema['@graph'][] = $this->get_homepage_schema();
		} elseif ( is_singular( 'post' ) ) {
			// Blog post schema.
			$schema['@graph'][] = $this->get_post_schema();
		} elseif ( is_singular( 'page' ) ) {
			// Page schema.
			$schema['@graph'][] = $this->get_page_schema();
		} elseif ( is_archive() || is_search() ) {
			// Archive or search schema.
			$schema['@graph'][] = $this->get_archive_schema();
		}
		
		// Add breadcrumb schema.
		if ( ! is_front_page() ) {
			$schema['@graph'][] = $this->get_breadcrumb_schema();
		}
		
		// Add WooCommerce product schema.
		if ( class_exists( 'WooCommerce' ) && is_product() ) {
			$schema['@graph'][] = $this->get_product_schema();
		}
		
		// Allow filtering of schema.
		return apply_filters( 'aqualuxe_schema', $schema );
	}

	/**
	 * Get website schema
	 *
	 * @return array Schema data.
	 */
	public function get_website_schema() {
		$schema = array(
			'@type'       => 'WebSite',
			'@id'         => home_url( '/#website' ),
			'url'         => home_url( '/' ),
			'name'        => get_bloginfo( 'name' ),
			'description' => get_bloginfo( 'description' ),
			'publisher'   => array(
				'@id' => home_url( '/#organization' ),
			),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => array(
					'@type'       => 'EntryPoint',
					'urlTemplate' => home_url( '/?s={search_term_string}' ),
				),
				'query-input' => 'required name=search_term_string',
			),
		);
		
		return apply_filters( 'aqualuxe_schema_website', $schema );
	}

	/**
	 * Get organization schema
	 *
	 * @return array Schema data.
	 */
	public function get_organization_schema() {
		$schema = array(
			'@type'       => 'Organization',
			'@id'         => home_url( '/#organization' ),
			'name'        => get_bloginfo( 'name' ),
			'url'         => home_url( '/' ),
			'description' => get_bloginfo( 'description' ),
		);
		
		// Add logo if available.
		$logo_id = get_theme_mod( 'custom_logo' );
		if ( $logo_id ) {
			$logo_image = wp_get_attachment_image_src( $logo_id, 'full' );
			if ( $logo_image ) {
				$schema['logo'] = array(
					'@type'   => 'ImageObject',
					'@id'     => home_url( '/#logo' ),
					'url'     => $logo_image[0],
					'width'   => $logo_image[1],
					'height'  => $logo_image[2],
					'caption' => get_bloginfo( 'name' ),
				);
				$schema['image'] = array(
					'@id' => home_url( '/#logo' ),
				);
			}
		}
		
		// Add social profiles if available.
		$social_profiles = array(
			'facebook'  => get_theme_mod( 'social_facebook' ),
			'twitter'   => get_theme_mod( 'social_twitter' ),
			'instagram' => get_theme_mod( 'social_instagram' ),
			'linkedin'  => get_theme_mod( 'social_linkedin' ),
			'youtube'   => get_theme_mod( 'social_youtube' ),
			'pinterest' => get_theme_mod( 'social_pinterest' ),
		);
		
		$sameAs = array();
		foreach ( $social_profiles as $profile ) {
			if ( ! empty( $profile ) ) {
				$sameAs[] = esc_url( $profile );
			}
		}
		
		if ( ! empty( $sameAs ) ) {
			$schema['sameAs'] = $sameAs;
		}
		
		// Add contact information if available.
		$contact_phone = get_theme_mod( 'contact_phone' );
		$contact_email = get_theme_mod( 'contact_email' );
		$contact_address = get_theme_mod( 'contact_address' );
		
		if ( ! empty( $contact_phone ) || ! empty( $contact_email ) || ! empty( $contact_address ) ) {
			$schema['contactPoint'] = array(
				'@type'       => 'ContactPoint',
				'contactType' => 'customer support',
			);
			
			if ( ! empty( $contact_phone ) ) {
				$schema['contactPoint']['telephone'] = $contact_phone;
			}
			
			if ( ! empty( $contact_email ) ) {
				$schema['contactPoint']['email'] = $contact_email;
			}
			
			if ( ! empty( $contact_address ) ) {
				$schema['address'] = array(
					'@type'           => 'PostalAddress',
					'streetAddress'   => $contact_address,
					'addressLocality' => get_theme_mod( 'contact_city', '' ),
					'addressRegion'   => get_theme_mod( 'contact_state', '' ),
					'postalCode'      => get_theme_mod( 'contact_zip', '' ),
					'addressCountry'  => get_theme_mod( 'contact_country', '' ),
				);
			}
		}
		
		return apply_filters( 'aqualuxe_schema_organization', $schema );
	}

	/**
	 * Get homepage schema
	 *
	 * @return array Schema data.
	 */
	public function get_homepage_schema() {
		$schema = array(
			'@type'    => 'WebPage',
			'@id'      => home_url( '/#webpage' ),
			'url'      => home_url( '/' ),
			'name'     => get_bloginfo( 'name' ),
			'isPartOf' => array(
				'@id' => home_url( '/#website' ),
			),
			'about'    => array(
				'@id' => home_url( '/#organization' ),
			),
			'description' => get_bloginfo( 'description' ),
			'inLanguage'  => get_bloginfo( 'language' ),
		);
		
		return apply_filters( 'aqualuxe_schema_homepage', $schema );
	}

	/**
	 * Get post schema
	 *
	 * @return array Schema data.
	 */
	public function get_post_schema() {
		$post_id = get_the_ID();
		$post = get_post( $post_id );
		
		$schema = array(
			'@type'         => 'BlogPosting',
			'@id'           => get_permalink( $post_id ) . '#article',
			'headline'      => get_the_title( $post_id ),
			'description'   => get_the_excerpt( $post_id ),
			'datePublished' => get_the_date( 'c', $post_id ),
			'dateModified'  => get_the_modified_date( 'c', $post_id ),
			'author'        => array(
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name', $post->post_author ),
			),
			'publisher'     => array(
				'@id' => home_url( '/#organization' ),
			),
			'isPartOf'      => array(
				'@id' => get_permalink( $post_id ) . '#webpage',
			),
			'mainEntityOfPage' => array(
				'@type' => 'WebPage',
				'@id'   => get_permalink( $post_id ) . '#webpage',
			),
			'inLanguage'    => get_bloginfo( 'language' ),
		);
		
		// Add featured image if available.
		if ( has_post_thumbnail( $post_id ) ) {
			$image_id = get_post_thumbnail_id( $post_id );
			$image_url = wp_get_attachment_image_src( $image_id, 'full' );
			
			if ( $image_url ) {
				$schema['image'] = array(
					'@type'  => 'ImageObject',
					'@id'    => get_permalink( $post_id ) . '#primaryimage',
					'url'    => $image_url[0],
					'width'  => $image_url[1],
					'height' => $image_url[2],
				);
			}
		}
		
		// Add webpage schema.
		$webpage_schema = array(
			'@type'         => 'WebPage',
			'@id'           => get_permalink( $post_id ) . '#webpage',
			'url'           => get_permalink( $post_id ),
			'name'          => get_the_title( $post_id ),
			'isPartOf'      => array(
				'@id' => home_url( '/#website' ),
			),
			'primaryImageOfPage' => array(
				'@id' => get_permalink( $post_id ) . '#primaryimage',
			),
			'datePublished' => get_the_date( 'c', $post_id ),
			'dateModified'  => get_the_modified_date( 'c', $post_id ),
			'description'   => get_the_excerpt( $post_id ),
			'breadcrumb'    => array(
				'@id' => get_permalink( $post_id ) . '#breadcrumb',
			),
			'inLanguage'    => get_bloginfo( 'language' ),
			'potentialAction' => array(
				array(
					'@type'  => 'ReadAction',
					'target' => array(
						get_permalink( $post_id ),
					),
				),
			),
		);
		
		return apply_filters( 'aqualuxe_schema_post', $schema, $post );
	}

	/**
	 * Get page schema
	 *
	 * @return array Schema data.
	 */
	public function get_page_schema() {
		$post_id = get_the_ID();
		
		$schema = array(
			'@type'         => 'WebPage',
			'@id'           => get_permalink( $post_id ) . '#webpage',
			'url'           => get_permalink( $post_id ),
			'name'          => get_the_title( $post_id ),
			'isPartOf'      => array(
				'@id' => home_url( '/#website' ),
			),
			'datePublished' => get_the_date( 'c', $post_id ),
			'dateModified'  => get_the_modified_date( 'c', $post_id ),
			'description'   => get_the_excerpt( $post_id ),
			'breadcrumb'    => array(
				'@id' => get_permalink( $post_id ) . '#breadcrumb',
			),
			'inLanguage'    => get_bloginfo( 'language' ),
		);
		
		// Add featured image if available.
		if ( has_post_thumbnail( $post_id ) ) {
			$image_id = get_post_thumbnail_id( $post_id );
			$image_url = wp_get_attachment_image_src( $image_id, 'full' );
			
			if ( $image_url ) {
				$schema['primaryImageOfPage'] = array(
					'@type'  => 'ImageObject',
					'@id'    => get_permalink( $post_id ) . '#primaryimage',
					'url'    => $image_url[0],
					'width'  => $image_url[1],
					'height' => $image_url[2],
				);
			}
		}
		
		return apply_filters( 'aqualuxe_schema_page', $schema, get_post( $post_id ) );
	}

	/**
	 * Get archive schema
	 *
	 * @return array Schema data.
	 */
	public function get_archive_schema() {
		$schema = array(
			'@type'         => 'CollectionPage',
			'@id'           => get_permalink() . '#webpage',
			'url'           => get_permalink(),
			'name'          => wp_get_document_title(),
			'isPartOf'      => array(
				'@id' => home_url( '/#website' ),
			),
			'breadcrumb'    => array(
				'@id' => get_permalink() . '#breadcrumb',
			),
			'inLanguage'    => get_bloginfo( 'language' ),
		);
		
		return apply_filters( 'aqualuxe_schema_archive', $schema );
	}

	/**
	 * Get breadcrumb schema
	 *
	 * @return array Schema data.
	 */
	public function get_breadcrumb_schema() {
		$breadcrumbs = $this->get_breadcrumbs();
		
		$schema = array(
			'@type'           => 'BreadcrumbList',
			'@id'             => get_permalink() . '#breadcrumb',
			'itemListElement' => array(),
		);
		
		foreach ( $breadcrumbs as $index => $breadcrumb ) {
			$schema['itemListElement'][] = array(
				'@type'    => 'ListItem',
				'position' => $index + 1,
				'item'     => array(
					'@type' => 'WebPage',
					'@id'   => $breadcrumb['url'],
					'url'   => $breadcrumb['url'],
					'name'  => $breadcrumb['label'],
				),
			);
		}
		
		return apply_filters( 'aqualuxe_schema_breadcrumb', $schema );
	}

	/**
	 * Get product schema
	 *
	 * @return array Schema data.
	 */
	public function get_product_schema() {
		if ( ! class_exists( 'WooCommerce' ) || ! is_product() ) {
			return array();
		}
		
		$product_id = get_the_ID();
		$product = wc_get_product( $product_id );
		
		if ( ! $product ) {
			return array();
		}
		
		$schema = array(
			'@type'       => 'Product',
			'@id'         => get_permalink( $product_id ) . '#product',
			'name'        => $product->get_name(),
			'description' => wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
			'sku'         => $product->get_sku(),
			'brand'       => array(
				'@type' => 'Brand',
				'name'  => get_bloginfo( 'name' ),
			),
		);
		
		// Add product image.
		if ( $product->get_image_id() ) {
			$image_url = wp_get_attachment_image_src( $product->get_image_id(), 'full' );
			if ( $image_url ) {
				$schema['image'] = $image_url[0];
			}
		}
		
		// Add product offers.
		$schema['offers'] = array(
			'@type'           => 'Offer',
			'price'           => $product->get_price(),
			'priceCurrency'   => get_woocommerce_currency(),
			'priceValidUntil' => date( 'Y-m-d', strtotime( '+1 year' ) ),
			'availability'    => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
			'url'             => get_permalink( $product_id ),
		);
		
		// Add product reviews.
		if ( $product->get_review_count() > 0 ) {
			$schema['aggregateRating'] = array(
				'@type'       => 'AggregateRating',
				'ratingValue' => $product->get_average_rating(),
				'reviewCount' => $product->get_review_count(),
			);
		}
		
		return apply_filters( 'aqualuxe_schema_product', $schema, get_post( $product_id ) );
	}

	/**
	 * Get breadcrumbs
	 *
	 * @return array Breadcrumbs.
	 */
	public function get_breadcrumbs() {
		$breadcrumbs = array(
			array(
				'label' => esc_html__( 'Home', 'aqualuxe' ),
				'url'   => home_url( '/' ),
			),
		);
		
		if ( is_category() || is_single() ) {
			$category = get_the_category();
			if ( ! empty( $category ) ) {
				$breadcrumbs[] = array(
					'label' => $category[0]->name,
					'url'   => get_category_link( $category[0]->term_id ),
				);
			}
			
			if ( is_single() ) {
				$breadcrumbs[] = array(
					'label' => get_the_title(),
					'url'   => get_permalink(),
				);
			}
		} elseif ( is_page() ) {
			$breadcrumbs[] = array(
				'label' => get_the_title(),
				'url'   => get_permalink(),
			);
		} elseif ( is_search() ) {
			$breadcrumbs[] = array(
				'label' => sprintf( esc_html__( 'Search results for: %s', 'aqualuxe' ), get_search_query() ),
				'url'   => get_search_link(),
			);
		} elseif ( is_tag() ) {
			$breadcrumbs[] = array(
				'label' => sprintf( esc_html__( 'Tag: %s', 'aqualuxe' ), single_tag_title( '', false ) ),
				'url'   => get_tag_link( get_queried_object_id() ),
			);
		} elseif ( is_author() ) {
			$breadcrumbs[] = array(
				'label' => sprintf( esc_html__( 'Author: %s', 'aqualuxe' ), get_the_author() ),
				'url'   => get_author_posts_url( get_the_author_meta( 'ID' ) ),
			);
		} elseif ( is_year() ) {
			$breadcrumbs[] = array(
				'label' => get_the_date( 'Y' ),
				'url'   => get_year_link( get_the_date( 'Y' ) ),
			);
		} elseif ( is_month() ) {
			$breadcrumbs[] = array(
				'label' => get_the_date( 'F Y' ),
				'url'   => get_month_link( get_the_date( 'Y' ), get_the_date( 'm' ) ),
			);
		} elseif ( is_day() ) {
			$breadcrumbs[] = array(
				'label' => get_the_date(),
				'url'   => get_day_link( get_the_date( 'Y' ), get_the_date( 'm' ), get_the_date( 'd' ) ),
			);
		} elseif ( is_tax() ) {
			$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			if ( $term ) {
				$breadcrumbs[] = array(
					'label' => $term->name,
					'url'   => get_term_link( $term ),
				);
			}
		} elseif ( is_post_type_archive() ) {
			$post_type = get_post_type_object( get_post_type() );
			if ( $post_type ) {
				$breadcrumbs[] = array(
					'label' => $post_type->labels->name,
					'url'   => get_post_type_archive_link( get_post_type() ),
				);
			}
		}
		
		return apply_filters( 'aqualuxe_breadcrumbs', $breadcrumbs );
	}

	/**
	 * Add Open Graph tags
	 */
	public function add_open_graph_tags() {
		// Basic Open Graph tags.
		echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '" />' . "\n";
		echo '<meta property="og:type" content="' . esc_attr( $this->get_og_type() ) . '" />' . "\n";
		echo '<meta property="og:title" content="' . esc_attr( $this->get_og_title() ) . '" />' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( $this->get_og_description() ) . '" />' . "\n";
		echo '<meta property="og:url" content="' . esc_url( $this->get_og_url() ) . '" />' . "\n";
		echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
		
		// Image tag.
		$og_image = $this->get_og_image();
		if ( $og_image ) {
			echo '<meta property="og:image" content="' . esc_url( $og_image['url'] ) . '" />' . "\n";
			echo '<meta property="og:image:width" content="' . esc_attr( $og_image['width'] ) . '" />' . "\n";
			echo '<meta property="og:image:height" content="' . esc_attr( $og_image['height'] ) . '" />' . "\n";
		}
		
		// Article tags.
		if ( is_singular( 'post' ) ) {
			echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( 'c' ) ) . '" />' . "\n";
			echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( 'c' ) ) . '" />' . "\n";
			
			// Article tags.
			$tags = get_the_tags();
			if ( $tags ) {
				foreach ( $tags as $tag ) {
					echo '<meta property="article:tag" content="' . esc_attr( $tag->name ) . '" />' . "\n";
				}
			}
			
			// Article categories.
			$categories = get_the_category();
			if ( $categories ) {
				foreach ( $categories as $category ) {
					echo '<meta property="article:section" content="' . esc_attr( $category->name ) . '" />' . "\n";
				}
			}
		}
		
		// Product tags.
		if ( class_exists( 'WooCommerce' ) && is_product() ) {
			$product = wc_get_product( get_the_ID() );
			if ( $product ) {
				echo '<meta property="product:price:amount" content="' . esc_attr( $product->get_price() ) . '" />' . "\n";
				echo '<meta property="product:price:currency" content="' . esc_attr( get_woocommerce_currency() ) . '" />' . "\n";
				echo '<meta property="product:availability" content="' . esc_attr( $product->is_in_stock() ? 'instock' : 'oos' ) . '" />' . "\n";
			}
		}
	}

	/**
	 * Add Twitter Card tags
	 */
	public function add_twitter_card_tags() {
		// Basic Twitter Card tags.
		echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
		echo '<meta name="twitter:title" content="' . esc_attr( $this->get_og_title() ) . '" />' . "\n";
		echo '<meta name="twitter:description" content="' . esc_attr( $this->get_og_description() ) . '" />' . "\n";
		
		// Twitter site username.
		$twitter_username = get_theme_mod( 'social_twitter_username' );
		if ( $twitter_username ) {
			echo '<meta name="twitter:site" content="@' . esc_attr( $twitter_username ) . '" />' . "\n";
		}
		
		// Image tag.
		$og_image = $this->get_og_image();
		if ( $og_image ) {
			echo '<meta name="twitter:image" content="' . esc_url( $og_image['url'] ) . '" />' . "\n";
		}
	}

	/**
	 * Get Open Graph type
	 *
	 * @return string Open Graph type.
	 */
	public function get_og_type() {
		if ( is_front_page() || is_home() ) {
			return 'website';
		} elseif ( is_singular( 'post' ) ) {
			return 'article';
		} elseif ( class_exists( 'WooCommerce' ) && is_product() ) {
			return 'product';
		} else {
			return 'object';
		}
	}

	/**
	 * Get Open Graph title
	 *
	 * @return string Open Graph title.
	 */
	public function get_og_title() {
		if ( is_front_page() ) {
			return get_bloginfo( 'name' );
		} elseif ( is_home() ) {
			return get_the_title( get_option( 'page_for_posts' ) );
		} elseif ( is_singular() ) {
			return get_the_title();
		} elseif ( is_tax() || is_category() || is_tag() ) {
			return single_term_title( '', false );
		} elseif ( is_search() ) {
			return sprintf( esc_html__( 'Search results for: %s', 'aqualuxe' ), get_search_query() );
		} elseif ( is_author() ) {
			return sprintf( esc_html__( 'Author: %s', 'aqualuxe' ), get_the_author() );
		} elseif ( is_post_type_archive() ) {
			return post_type_archive_title( '', false );
		} elseif ( is_archive() ) {
			return get_the_archive_title();
		}
		
		return wp_get_document_title();
	}

	/**
	 * Get Open Graph description
	 *
	 * @return string Open Graph description.
	 */
	public function get_og_description() {
		if ( is_front_page() || is_home() ) {
			return get_bloginfo( 'description' );
		} elseif ( is_singular() ) {
			$post_excerpt = get_the_excerpt();
			if ( ! empty( $post_excerpt ) ) {
				return $post_excerpt;
			}
			
			// Fallback to post content.
			$post_content = get_post_field( 'post_content', get_the_ID() );
			$post_content = strip_shortcodes( $post_content );
			$post_content = wp_strip_all_tags( $post_content );
			
			return wp_trim_words( $post_content, 55, '...' );
		} elseif ( is_tax() || is_category() || is_tag() ) {
			return term_description();
		} elseif ( is_search() ) {
			return sprintf( esc_html__( 'Search results for: %s', 'aqualuxe' ), get_search_query() );
		} elseif ( is_author() ) {
			return get_the_author_meta( 'description' );
		}
		
		return get_bloginfo( 'description' );
	}

	/**
	 * Get Open Graph URL
	 *
	 * @return string Open Graph URL.
	 */
	public function get_og_url() {
		if ( is_front_page() ) {
			return home_url( '/' );
		} elseif ( is_home() ) {
			return get_permalink( get_option( 'page_for_posts' ) );
		} elseif ( is_singular() ) {
			return get_permalink();
		} elseif ( is_tax() || is_category() || is_tag() ) {
			return get_term_link( get_queried_object() );
		} elseif ( is_search() ) {
			return get_search_link();
		} elseif ( is_author() ) {
			return get_author_posts_url( get_the_author_meta( 'ID' ) );
		} elseif ( is_post_type_archive() ) {
			return get_post_type_archive_link( get_post_type() );
		} elseif ( is_archive() ) {
			return get_permalink();
		}
		
		return home_url( '/' );
	}

	/**
	 * Get Open Graph image
	 *
	 * @return array|false Open Graph image data or false if not available.
	 */
	public function get_og_image() {
		$image = false;
		
		if ( is_singular() && has_post_thumbnail() ) {
			$image_id = get_post_thumbnail_id();
			$image_url = wp_get_attachment_image_src( $image_id, 'full' );
			
			if ( $image_url ) {
				$image = array(
					'url'    => $image_url[0],
					'width'  => $image_url[1],
					'height' => $image_url[2],
				);
			}
		} elseif ( is_tax() || is_category() || is_tag() ) {
			// Try to get term meta image if available.
			$term_id = get_queried_object_id();
			$term_image_id = get_term_meta( $term_id, 'thumbnail_id', true );
			
			if ( $term_image_id ) {
				$image_url = wp_get_attachment_image_src( $term_image_id, 'full' );
				
				if ( $image_url ) {
					$image = array(
						'url'    => $image_url[0],
						'width'  => $image_url[1],
						'height' => $image_url[2],
					);
				}
			}
		}
		
		// Fallback to site logo.
		if ( ! $image ) {
			$logo_id = get_theme_mod( 'custom_logo' );
			
			if ( $logo_id ) {
				$image_url = wp_get_attachment_image_src( $logo_id, 'full' );
				
				if ( $image_url ) {
					$image = array(
						'url'    => $image_url[0],
						'width'  => $image_url[1],
						'height' => $image_url[2],
					);
				}
			}
		}
		
		// Fallback to default image.
		if ( ! $image ) {
			$default_image = get_theme_mod( 'og_default_image' );
			
			if ( $default_image ) {
				$image_url = wp_get_attachment_image_src( $default_image, 'full' );
				
				if ( $image_url ) {
					$image = array(
						'url'    => $image_url[0],
						'width'  => $image_url[1],
						'height' => $image_url[2],
					);
				}
			}
		}
		
		return $image;
	}
}

// Initialize schema.
new AquaLuxe_Schema();