<?php
/**
 * Schema.org markup implementation
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * AquaLuxe Schema Class
 */
class AquaLuxe_Schema {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add schema markup to HTML tag
		add_filter( 'language_attributes', array( $this, 'html_schema_markup' ) );

		// Add schema markup to the body
		add_filter( 'aqualuxe_body_attributes', array( $this, 'body_schema_markup' ) );

		// Add schema markup to the header
		add_action( 'wp_head', array( $this, 'head_schema_markup' ) );

		// Add schema markup to the content
		add_filter( 'the_content', array( $this, 'content_schema_markup' ) );

		// Add schema markup to the comment
		add_filter( 'comment_text', array( $this, 'comment_schema_markup' ) );

		// Add schema markup to the product
		if ( class_exists( 'WooCommerce' ) ) {
			add_action( 'woocommerce_before_main_content', array( $this, 'product_schema_markup' ), 99 );
		}
	}

	/**
	 * Add schema markup to HTML tag
	 *
	 * @param string $output The output.
	 * @return string
	 */
	public function html_schema_markup( $output ) {
		// Get schema type
		$schema = $this->get_schema_type();

		if ( ! empty( $schema ) ) {
			$output .= ' itemscope itemtype="https://schema.org/' . esc_attr( $schema ) . '"';
		}

		return $output;
	}

	/**
	 * Add schema markup to the body
	 *
	 * @param array $attributes The body attributes.
	 * @return array
	 */
	public function body_schema_markup( $attributes ) {
		// Get schema type
		$schema = $this->get_schema_type();

		if ( ! empty( $schema ) ) {
			$attributes['itemscope'] = '';
			$attributes['itemtype']  = 'https://schema.org/' . esc_attr( $schema );
		}

		return $attributes;
	}

	/**
	 * Add schema markup to the head
	 */
	public function head_schema_markup() {
		// Get schema type
		$schema = $this->get_schema_type();

		if ( empty( $schema ) ) {
			return;
		}

		// Website schema
		$this->website_schema();

		// Organization schema
		$this->organization_schema();

		// Breadcrumb schema
		if ( ! is_front_page() && function_exists( 'yoast_breadcrumb' ) ) {
			$this->breadcrumb_schema();
		}

		// Page specific schema
		if ( is_singular( 'post' ) ) {
			$this->article_schema();
		} elseif ( is_singular( 'page' ) ) {
			$this->webpage_schema();
		} elseif ( is_singular( 'product' ) && class_exists( 'WooCommerce' ) ) {
			$this->product_schema();
		}
	}

	/**
	 * Add schema markup to the content
	 *
	 * @param string $content The content.
	 * @return string
	 */
	public function content_schema_markup( $content ) {
		if ( is_singular( 'post' ) ) {
			$content = '<div itemprop="articleBody">' . $content . '</div>';
		} elseif ( is_singular( 'page' ) ) {
			$content = '<div itemprop="mainContentOfPage">' . $content . '</div>';
		}

		return $content;
	}

	/**
	 * Add schema markup to the comment
	 *
	 * @param string $comment_text The comment text.
	 * @return string
	 */
	public function comment_schema_markup( $comment_text ) {
		if ( is_singular() ) {
			$comment_text = '<div itemprop="comment" itemscope itemtype="https://schema.org/Comment"><div itemprop="text">' . $comment_text . '</div></div>';
		}

		return $comment_text;
	}

	/**
	 * Add schema markup to the product
	 */
	public function product_schema_markup() {
		if ( ! is_singular( 'product' ) ) {
			return;
		}

		// Remove default WooCommerce schema
		remove_action( 'wp_footer', array( WC()->structured_data, 'output_structured_data' ), 10 );
	}

	/**
	 * Get schema type
	 *
	 * @return string
	 */
	private function get_schema_type() {
		// Default schema
		$schema = 'WebPage';

		// Check if front page
		if ( is_front_page() ) {
			$schema = 'WebSite';
		} elseif ( is_author() ) {
			$schema = 'ProfilePage';
		} elseif ( is_search() ) {
			$schema = 'SearchResultsPage';
		} elseif ( is_singular( 'post' ) ) {
			$schema = 'Article';
		} elseif ( is_singular( 'page' ) ) {
			$schema = 'WebPage';
		} elseif ( is_singular( 'product' ) && class_exists( 'WooCommerce' ) ) {
			$schema = 'Product';
		} elseif ( is_archive() ) {
			$schema = 'CollectionPage';
		}

		return apply_filters( 'aqualuxe_schema_type', $schema );
	}

	/**
	 * Website schema
	 */
	private function website_schema() {
		$schema = array(
			'@context'      => 'https://schema.org',
			'@type'         => 'WebSite',
			'@id'           => esc_url( home_url( '/#website' ) ),
			'url'           => esc_url( home_url( '/' ) ),
			'name'          => esc_html( get_bloginfo( 'name' ) ),
			'description'   => esc_html( get_bloginfo( 'description' ) ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => esc_url( home_url( '/?s={search_term_string}' ) ),
				'query-input' => 'required name=search_term_string',
			),
		);

		$this->output_schema( $schema );
	}

	/**
	 * Organization schema
	 */
	private function organization_schema() {
		$schema = array(
			'@context'      => 'https://schema.org',
			'@type'         => 'Organization',
			'@id'           => esc_url( home_url( '/#organization' ) ),
			'url'           => esc_url( home_url( '/' ) ),
			'name'          => esc_html( get_bloginfo( 'name' ) ),
			'description'   => esc_html( get_bloginfo( 'description' ) ),
		);

		// Add logo
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		if ( $custom_logo_id ) {
			$logo_image = wp_get_attachment_image_src( $custom_logo_id, 'full' );
			if ( $logo_image ) {
				$schema['logo'] = array(
					'@type'       => 'ImageObject',
					'@id'         => esc_url( home_url( '/#logo' ) ),
					'url'         => esc_url( $logo_image[0] ),
					'width'       => absint( $logo_image[1] ),
					'height'      => absint( $logo_image[2] ),
					'caption'     => esc_html( get_bloginfo( 'name' ) ),
				);
				$schema['image'] = array(
					'@id' => esc_url( home_url( '/#logo' ) ),
				);
			}
		}

		// Add social profiles
		$social_profiles = array();
		$social_networks = array( 'facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'pinterest' );
		foreach ( $social_networks as $network ) {
			$url = get_theme_mod( 'aqualuxe_social_' . $network );
			if ( ! empty( $url ) ) {
				$social_profiles[] = esc_url( $url );
			}
		}

		if ( ! empty( $social_profiles ) ) {
			$schema['sameAs'] = $social_profiles;
		}

		// Add contact information
		$schema['contactPoint'] = array(
			'@type'             => 'ContactPoint',
			'telephone'         => esc_html( get_theme_mod( 'aqualuxe_contact_phone', '' ) ),
			'email'             => esc_html( get_theme_mod( 'aqualuxe_contact_email', '' ) ),
			'contactType'       => 'customer service',
			'availableLanguage' => array( 'English' ),
		);

		$this->output_schema( $schema );
	}

	/**
	 * Breadcrumb schema
	 */
	private function breadcrumb_schema() {
		// Get breadcrumb from Yoast SEO
		$breadcrumbs = $this->get_yoast_breadcrumbs();

		if ( empty( $breadcrumbs ) ) {
			return;
		}

		$schema = array(
			'@context'      => 'https://schema.org',
			'@type'         => 'BreadcrumbList',
			'@id'           => esc_url( get_permalink() . '#breadcrumb' ),
			'itemListElement' => array(),
		);

		foreach ( $breadcrumbs as $index => $breadcrumb ) {
			$schema['itemListElement'][] = array(
				'@type'    => 'ListItem',
				'position' => $index + 1,
				'item'     => array(
					'@id'  => esc_url( $breadcrumb['url'] ),
					'name' => esc_html( $breadcrumb['text'] ),
				),
			);
		}

		$this->output_schema( $schema );
	}

	/**
	 * Article schema
	 */
	private function article_schema() {
		global $post;

		$schema = array(
			'@context'      => 'https://schema.org',
			'@type'         => 'Article',
			'@id'           => esc_url( get_permalink() . '#article' ),
			'headline'      => esc_html( get_the_title() ),
			'description'   => esc_html( get_the_excerpt() ),
			'datePublished' => esc_attr( get_the_date( 'c' ) ),
			'dateModified'  => esc_attr( get_the_modified_date( 'c' ) ),
			'author'        => array(
				'@type' => 'Person',
				'name'  => esc_html( get_the_author() ),
				'url'   => esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			),
			'publisher'     => array(
				'@id' => esc_url( home_url( '/#organization' ) ),
			),
			'mainEntityOfPage' => array(
				'@type' => 'WebPage',
				'@id'   => esc_url( get_permalink() ),
			),
		);

		// Add featured image
		if ( has_post_thumbnail() ) {
			$image_id   = get_post_thumbnail_id();
			$image_data = wp_get_attachment_image_src( $image_id, 'full' );

			if ( $image_data ) {
				$schema['image'] = array(
					'@type'  => 'ImageObject',
					'@id'    => esc_url( get_permalink() . '#primaryimage' ),
					'url'    => esc_url( $image_data[0] ),
					'width'  => absint( $image_data[1] ),
					'height' => absint( $image_data[2] ),
				);
			}
		}

		// Add article section and keywords
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

		$this->output_schema( $schema );
	}

	/**
	 * WebPage schema
	 */
	private function webpage_schema() {
		$schema = array(
			'@context'      => 'https://schema.org',
			'@type'         => 'WebPage',
			'@id'           => esc_url( get_permalink() . '#webpage' ),
			'url'           => esc_url( get_permalink() ),
			'name'          => esc_html( get_the_title() ),
			'description'   => esc_html( get_the_excerpt() ),
			'datePublished' => esc_attr( get_the_date( 'c' ) ),
			'dateModified'  => esc_attr( get_the_modified_date( 'c' ) ),
			'isPartOf'      => array(
				'@id' => esc_url( home_url( '/#website' ) ),
			),
			'inLanguage'    => esc_attr( get_bloginfo( 'language' ) ),
		);

		// Add featured image
		if ( has_post_thumbnail() ) {
			$image_id   = get_post_thumbnail_id();
			$image_data = wp_get_attachment_image_src( $image_id, 'full' );

			if ( $image_data ) {
				$schema['primaryImageOfPage'] = array(
					'@type'  => 'ImageObject',
					'@id'    => esc_url( get_permalink() . '#primaryimage' ),
					'url'    => esc_url( $image_data[0] ),
					'width'  => absint( $image_data[1] ),
					'height' => absint( $image_data[2] ),
				);
			}
		}

		$this->output_schema( $schema );
	}

	/**
	 * Product schema
	 */
	private function product_schema() {
		global $product;

		if ( ! is_object( $product ) ) {
			$product = wc_get_product( get_the_ID() );
		}

		if ( ! $product ) {
			return;
		}

		$schema = array(
			'@context'      => 'https://schema.org',
			'@type'         => 'Product',
			'@id'           => esc_url( get_permalink() . '#product' ),
			'name'          => esc_html( $product->get_name() ),
			'description'   => wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
			'url'           => esc_url( get_permalink() ),
			'sku'           => esc_html( $product->get_sku() ),
			'brand'         => array(
				'@type' => 'Brand',
				'name'  => esc_html( get_bloginfo( 'name' ) ),
			),
		);

		// Add product image
		if ( has_post_thumbnail() ) {
			$image_id   = get_post_thumbnail_id();
			$image_data = wp_get_attachment_image_src( $image_id, 'full' );

			if ( $image_data ) {
				$schema['image'] = array(
					'@type'  => 'ImageObject',
					'@id'    => esc_url( get_permalink() . '#primaryimage' ),
					'url'    => esc_url( $image_data[0] ),
					'width'  => absint( $image_data[1] ),
					'height' => absint( $image_data[2] ),
				);
			}
		}

		// Add product gallery
		$gallery_ids = $product->get_gallery_image_ids();
		if ( ! empty( $gallery_ids ) ) {
			$schema['additionalProperty'] = array(
				'@type'     => 'PropertyValue',
				'name'      => 'image',
				'value'     => array(),
			);

			foreach ( $gallery_ids as $gallery_id ) {
				$gallery_data = wp_get_attachment_image_src( $gallery_id, 'full' );
				if ( $gallery_data ) {
					$schema['additionalProperty']['value'][] = esc_url( $gallery_data[0] );
				}
			}
		}

		// Add product price
		if ( $product->get_price() ) {
			$schema['offers'] = array(
				'@type'           => 'Offer',
				'price'           => wc_format_decimal( $product->get_price(), wc_get_price_decimals() ),
				'priceCurrency'   => get_woocommerce_currency(),
				'priceValidUntil' => date( 'c', strtotime( '+1 year' ) ),
				'availability'    => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
				'url'             => esc_url( get_permalink() ),
			);

			// Add regular and sale price
			if ( $product->is_on_sale() && $product->get_regular_price() ) {
				$schema['offers']['priceSpecification'] = array(
					'@type'         => 'PriceSpecification',
					'price'         => wc_format_decimal( $product->get_regular_price(), wc_get_price_decimals() ),
					'priceCurrency' => get_woocommerce_currency(),
				);
			}
		}

		// Add product reviews
		if ( $product->get_review_count() ) {
			$schema['aggregateRating'] = array(
				'@type'       => 'AggregateRating',
				'ratingValue' => wc_format_decimal( $product->get_average_rating(), 1 ),
				'reviewCount' => intval( $product->get_review_count() ),
				'bestRating'  => '5',
				'worstRating' => '1',
			);
		}

		// Add product categories
		$categories = get_the_terms( $product->get_id(), 'product_cat' );
		if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
			$schema['category'] = esc_html( $categories[0]->name );
		}

		$this->output_schema( $schema );
	}

	/**
	 * Output schema
	 *
	 * @param array $schema The schema data.
	 */
	private function output_schema( $schema ) {
		if ( empty( $schema ) ) {
			return;
		}

		echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
	}

	/**
	 * Get Yoast breadcrumbs
	 *
	 * @return array
	 */
	private function get_yoast_breadcrumbs() {
		$breadcrumbs = array();

		if ( function_exists( 'yoast_breadcrumb' ) ) {
			$yoast_breadcrumbs = yoast_breadcrumb( '', '', false );
			$dom = new DOMDocument();
			@$dom->loadHTML( $yoast_breadcrumbs );
			$xpath = new DOMXPath( $dom );
			$links = $xpath->query( '//a' );

			if ( $links->length > 0 ) {
				foreach ( $links as $link ) {
					$breadcrumbs[] = array(
						'url'  => $link->getAttribute( 'href' ),
						'text' => $link->nodeValue,
					);
				}

				// Add current page
				$current_page = $xpath->query( '//span[@class="breadcrumb_last"]' );
				if ( $current_page->length > 0 ) {
					$breadcrumbs[] = array(
						'url'  => get_permalink(),
						'text' => $current_page->item( 0 )->nodeValue,
					);
				}
			}
		}

		return $breadcrumbs;
	}
}

// Initialize the schema class
new AquaLuxe_Schema();

/**
 * Get HTML attributes for the <body> element
 *
 * @return string
 */
function aqualuxe_get_body_attributes() {
	$attributes = array();

	// Add class for dark mode
	if ( aqualuxe_is_dark_mode() ) {
		$attributes['class'] = 'dark';
	}

	// Filter body attributes
	$attributes = apply_filters( 'aqualuxe_body_attributes', $attributes );

	// Build attributes string
	$attributes_string = '';
	foreach ( $attributes as $name => $value ) {
		$attributes_string .= ' ' . $name;
		if ( ! empty( $value ) ) {
			$attributes_string .= '="' . esc_attr( $value ) . '"';
		}
	}

	return $attributes_string;
}

/**
 * Get HTML classes for the <html> element
 *
 * @return string
 */
function aqualuxe_get_html_classes() {
	$classes = array();

	// Add class for dark mode
	if ( aqualuxe_is_dark_mode() ) {
		$classes[] = 'dark';
	}

	// Filter HTML classes
	$classes = apply_filters( 'aqualuxe_html_classes', $classes );

	return implode( ' ', array_unique( $classes ) );
}