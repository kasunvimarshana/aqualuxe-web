<?php
/**
 * Schema.org Implementation
 *
 * Adds structured data to the theme for better SEO.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Schema.org Implementation Class
 */
class AquaLuxe_Schema {

	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Get an instance of this class.
	 *
	 * @since 1.0.0
	 * @return AquaLuxe_Schema
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Add schema.org markup to the head.
		add_action( 'wp_head', array( $this, 'add_schema_markup' ) );
		
		// Add schema.org markup to products if WooCommerce is active.
		if ( class_exists( 'WooCommerce' ) ) {
			add_action( 'woocommerce_single_product_summary', array( $this, 'add_product_schema' ), 60 );
		}
	}

	/**
	 * Add schema.org markup to the head.
	 *
	 * @since 1.0.0
	 */
	public function add_schema_markup() {
		// Get the schema type based on the current page.
		$schema_type = $this->get_schema_type();
		
		// If no schema type is found, return.
		if ( ! $schema_type ) {
			return;
		}
		
		// Get the schema data.
		$schema_data = $this->get_schema_data( $schema_type );
		
		// If no schema data is found, return.
		if ( ! $schema_data ) {
			return;
		}
		
		// Output the schema.org markup.
		echo '<script type="application/ld+json">' . wp_json_encode( $schema_data ) . '</script>';
	}

	/**
	 * Get the schema type based on the current page.
	 *
	 * @since 1.0.0
	 * @return string|false The schema type or false if none is found.
	 */
	private function get_schema_type() {
		if ( is_front_page() || is_home() ) {
			return 'WebSite';
		} elseif ( is_singular( 'post' ) ) {
			return 'Article';
		} elseif ( is_singular( 'page' ) ) {
			return 'WebPage';
		} elseif ( is_singular( 'product' ) && class_exists( 'WooCommerce' ) ) {
			return 'Product';
		} elseif ( is_author() ) {
			return 'Person';
		} elseif ( is_search() ) {
			return 'SearchResultsPage';
		} elseif ( is_archive() ) {
			return 'CollectionPage';
		}
		
		return false;
	}

	/**
	 * Get the schema data based on the schema type.
	 *
	 * @since 1.0.0
	 * @param string $schema_type The schema type.
	 * @return array|false The schema data or false if none is found.
	 */
	private function get_schema_data( $schema_type ) {
		switch ( $schema_type ) {
			case 'WebSite':
				return $this->get_website_schema();
			case 'Article':
				return $this->get_article_schema();
			case 'WebPage':
				return $this->get_webpage_schema();
			case 'Product':
				return $this->get_product_schema();
			case 'Person':
				return $this->get_person_schema();
			case 'SearchResultsPage':
				return $this->get_search_results_schema();
			case 'CollectionPage':
				return $this->get_collection_page_schema();
			default:
				return false;
		}
	}

	/**
	 * Get the WebSite schema data.
	 *
	 * @since 1.0.0
	 * @return array The WebSite schema data.
	 */
	private function get_website_schema() {
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
		
		return $schema;
	}

	/**
	 * Get the Article schema data.
	 *
	 * @since 1.0.0
	 * @return array The Article schema data.
	 */
	private function get_article_schema() {
		global $post;
		
		// Get the featured image.
		$image_url = '';
		if ( has_post_thumbnail() ) {
			$image_id   = get_post_thumbnail_id();
			$image_data = wp_get_attachment_image_src( $image_id, 'full' );
			if ( $image_data ) {
				$image_url = $image_data[0];
			}
		}
		
		// Get the author data.
		$author_id   = $post->post_author;
		$author_name = get_the_author_meta( 'display_name', $author_id );
		$author_url  = get_author_posts_url( $author_id );
		
		$schema = array(
			'@context'         => 'https://schema.org',
			'@type'            => 'Article',
			'@id'              => esc_url( get_permalink() . '#article' ),
			'headline'         => esc_html( get_the_title() ),
			'description'      => esc_html( get_the_excerpt() ),
			'datePublished'    => esc_attr( get_the_date( 'c' ) ),
			'dateModified'     => esc_attr( get_the_modified_date( 'c' ) ),
			'author'           => array(
				'@type' => 'Person',
				'name'  => esc_html( $author_name ),
				'url'   => esc_url( $author_url ),
			),
			'publisher'        => array(
				'@type' => 'Organization',
				'name'  => esc_html( get_bloginfo( 'name' ) ),
				'logo'  => array(
					'@type' => 'ImageObject',
					'url'   => esc_url( $this->get_site_logo_url() ),
				),
			),
			'mainEntityOfPage' => array(
				'@type' => 'WebPage',
				'@id'   => esc_url( get_permalink() ),
			),
		);
		
		// Add the image if available.
		if ( $image_url ) {
			$schema['image'] = array(
				'@type' => 'ImageObject',
				'url'   => esc_url( $image_url ),
			);
		}
		
		return $schema;
	}

	/**
	 * Get the WebPage schema data.
	 *
	 * @since 1.0.0
	 * @return array The WebPage schema data.
	 */
	private function get_webpage_schema() {
		global $post;
		
		// Get the featured image.
		$image_url = '';
		if ( has_post_thumbnail() ) {
			$image_id   = get_post_thumbnail_id();
			$image_data = wp_get_attachment_image_src( $image_id, 'full' );
			if ( $image_data ) {
				$image_url = $image_data[0];
			}
		}
		
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
				'@type' => 'WebSite',
				'@id'   => esc_url( home_url( '/#website' ) ),
				'url'   => esc_url( home_url( '/' ) ),
				'name'  => esc_html( get_bloginfo( 'name' ) ),
			),
		);
		
		// Add the image if available.
		if ( $image_url ) {
			$schema['primaryImageOfPage'] = array(
				'@type' => 'ImageObject',
				'url'   => esc_url( $image_url ),
			);
		}
		
		return $schema;
	}

	/**
	 * Get the Product schema data.
	 *
	 * @since 1.0.0
	 * @return array|false The Product schema data or false if WooCommerce is not active.
	 */
	private function get_product_schema() {
		// If WooCommerce is not active, return false.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;
		}
		
		global $product;
		
		// If the product is not valid, return false.
		if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
			return false;
		}
		
		// Get the product data.
		$product_id   = $product->get_id();
		$product_name = $product->get_name();
		$product_desc = $product->get_short_description();
		$product_url  = get_permalink( $product_id );
		
		// Get the product image.
		$image_url = '';
		if ( has_post_thumbnail( $product_id ) ) {
			$image_id   = get_post_thumbnail_id( $product_id );
			$image_data = wp_get_attachment_image_src( $image_id, 'full' );
			if ( $image_data ) {
				$image_url = $image_data[0];
			}
		}
		
		// Get the product price.
		$price = $product->get_price();
		$currency = get_woocommerce_currency();
		
		// Get the product availability.
		$availability = $product->is_in_stock() ? 'InStock' : 'OutOfStock';
		
		$schema = array(
			'@context'    => 'https://schema.org',
			'@type'       => 'Product',
			'@id'         => esc_url( $product_url . '#product' ),
			'name'        => esc_html( $product_name ),
			'description' => esc_html( $product_desc ),
			'url'         => esc_url( $product_url ),
			'sku'         => esc_html( $product->get_sku() ),
			'offers'      => array(
				'@type'         => 'Offer',
				'price'         => esc_attr( $price ),
				'priceCurrency' => esc_attr( $currency ),
				'availability'  => 'https://schema.org/' . esc_attr( $availability ),
				'url'           => esc_url( $product_url ),
			),
		);
		
		// Add the image if available.
		if ( $image_url ) {
			$schema['image'] = esc_url( $image_url );
		}
		
		// Add the brand if available.
		$brands = wp_get_post_terms( $product_id, 'product_brand' );
		if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) {
			$schema['brand'] = array(
				'@type' => 'Brand',
				'name'  => esc_html( $brands[0]->name ),
			);
		}
		
		// Add the product ratings if available.
		if ( $product->get_rating_count() > 0 ) {
			$schema['aggregateRating'] = array(
				'@type'       => 'AggregateRating',
				'ratingValue' => esc_attr( $product->get_average_rating() ),
				'reviewCount' => esc_attr( $product->get_review_count() ),
			);
		}
		
		return $schema;
	}

	/**
	 * Add schema.org markup to products.
	 *
	 * @since 1.0.0
	 */
	public function add_product_schema() {
		// This is intentionally left empty as we're adding the schema in the head.
		// This method is here for future use if needed.
	}

	/**
	 * Get the Person schema data.
	 *
	 * @since 1.0.0
	 * @return array The Person schema data.
	 */
	private function get_person_schema() {
		$author_id   = get_query_var( 'author' );
		$author_name = get_the_author_meta( 'display_name', $author_id );
		$author_url  = get_author_posts_url( $author_id );
		$author_desc = get_the_author_meta( 'description', $author_id );
		
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Person',
			'@id'      => esc_url( $author_url . '#person' ),
			'name'     => esc_html( $author_name ),
			'url'      => esc_url( $author_url ),
		);
		
		// Add the description if available.
		if ( $author_desc ) {
			$schema['description'] = esc_html( $author_desc );
		}
		
		// Add the author image if available.
		$author_image = get_avatar_url( $author_id, array( 'size' => 96 ) );
		if ( $author_image ) {
			$schema['image'] = esc_url( $author_image );
		}
		
		return $schema;
	}

	/**
	 * Get the SearchResultsPage schema data.
	 *
	 * @since 1.0.0
	 * @return array The SearchResultsPage schema data.
	 */
	private function get_search_results_schema() {
		$search_query = get_search_query();
		
		$schema = array(
			'@context'      => 'https://schema.org',
			'@type'         => 'SearchResultsPage',
			'@id'           => esc_url( home_url( '/?s=' . urlencode( $search_query ) . '#search' ) ),
			'url'           => esc_url( home_url( '/?s=' . urlencode( $search_query ) ) ),
			'name'          => esc_html( sprintf( __( 'Search Results for: %s', 'aqualuxe' ), $search_query ) ),
			'isPartOf'      => array(
				'@type' => 'WebSite',
				'@id'   => esc_url( home_url( '/#website' ) ),
				'url'   => esc_url( home_url( '/' ) ),
				'name'  => esc_html( get_bloginfo( 'name' ) ),
			),
		);
		
		return $schema;
	}

	/**
	 * Get the CollectionPage schema data.
	 *
	 * @since 1.0.0
	 * @return array The CollectionPage schema data.
	 */
	private function get_collection_page_schema() {
		$title = '';
		
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_author() ) {
			$title = get_the_author();
		} elseif ( is_date() ) {
			if ( is_day() ) {
				$title = sprintf( __( 'Daily Archives: %s', 'aqualuxe' ), get_the_date() );
			} elseif ( is_month() ) {
				$title = sprintf( __( 'Monthly Archives: %s', 'aqualuxe' ), get_the_date( 'F Y' ) );
			} elseif ( is_year() ) {
				$title = sprintf( __( 'Yearly Archives: %s', 'aqualuxe' ), get_the_date( 'Y' ) );
			} else {
				$title = __( 'Archives', 'aqualuxe' );
			}
		}
		
		$schema = array(
			'@context'      => 'https://schema.org',
			'@type'         => 'CollectionPage',
			'@id'           => esc_url( get_permalink() . '#collection' ),
			'url'           => esc_url( get_permalink() ),
			'name'          => esc_html( $title ),
			'isPartOf'      => array(
				'@type' => 'WebSite',
				'@id'   => esc_url( home_url( '/#website' ) ),
				'url'   => esc_url( home_url( '/' ) ),
				'name'  => esc_html( get_bloginfo( 'name' ) ),
			),
		);
		
		return $schema;
	}

	/**
	 * Get the site logo URL.
	 *
	 * @since 1.0.0
	 * @return string The site logo URL.
	 */
	private function get_site_logo_url() {
		$logo_url = '';
		
		// Get the custom logo URL.
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		if ( $custom_logo_id ) {
			$logo_data = wp_get_attachment_image_src( $custom_logo_id, 'full' );
			if ( $logo_data ) {
				$logo_url = $logo_data[0];
			}
		}
		
		// If no custom logo is set, use a default logo.
		if ( ! $logo_url ) {
			$logo_url = get_template_directory_uri() . '/assets/images/logo.png';
		}
		
		return $logo_url;
	}
}

// Initialize the schema class.
AquaLuxe_Schema::get_instance();