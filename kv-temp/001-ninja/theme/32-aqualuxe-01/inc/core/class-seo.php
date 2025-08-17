<?php
/**
 * AquaLuxe SEO Optimizations
 *
 * @package AquaLuxe
 * @since 1.1.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SEO Class
 *
 * Handles SEO optimizations for the theme.
 *
 * @since 1.1.0
 */
class SEO {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// No initialization needed.
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Add schema.org markup.
		add_filter( 'language_attributes', array( $this, 'add_schema_markup' ) );
		add_action( 'wp_head', array( $this, 'add_json_ld_schema' ) );
		
		// Add Open Graph metadata.
		add_action( 'wp_head', array( $this, 'add_open_graph_tags' ) );
		
		// Add Twitter Card metadata.
		add_action( 'wp_head', array( $this, 'add_twitter_card_tags' ) );
		
		// Add canonical URL.
		add_action( 'wp_head', array( $this, 'add_canonical_url' ), 1 );
		
		// Add meta description.
		add_action( 'wp_head', array( $this, 'add_meta_description' ) );
		
		// Add meta robots.
		add_action( 'wp_head', array( $this, 'add_meta_robots' ) );
		
		// Add breadcrumb schema.
		add_action( 'aqualuxe_breadcrumbs_after', array( $this, 'add_breadcrumb_schema' ) );
		
		// Add product schema.
		if ( class_exists( 'WooCommerce' ) ) {
			add_action( 'woocommerce_after_single_product', array( $this, 'add_product_schema' ) );
		}
	}

	/**
	 * Add schema.org markup to HTML tag.
	 *
	 * @param string $output The language attributes.
	 * @return string
	 */
	public function add_schema_markup( $output ) {
		// Check if schema markup should be added.
		if ( ! get_theme_mod( 'aqualuxe_enable_schema_markup', true ) ) {
			return $output;
		}
		
		// Add schema.org markup.
		$output .= ' itemscope itemtype="https://schema.org/WebPage"';
		
		// Add specific schema types.
		if ( is_singular( 'post' ) ) {
			$output = str_replace( 'itemtype="https://schema.org/WebPage"', 'itemtype="https://schema.org/Article"', $output );
		} elseif ( is_author() ) {
			$output = str_replace( 'itemtype="https://schema.org/WebPage"', 'itemtype="https://schema.org/ProfilePage"', $output );
		} elseif ( is_search() ) {
			$output = str_replace( 'itemtype="https://schema.org/WebPage"', 'itemtype="https://schema.org/SearchResultsPage"', $output );
		} elseif ( class_exists( 'WooCommerce' ) ) {
			if ( is_shop() || is_product_category() || is_product_tag() ) {
				$output = str_replace( 'itemtype="https://schema.org/WebPage"', 'itemtype="https://schema.org/CollectionPage"', $output );
			} elseif ( is_product() ) {
				$output = str_replace( 'itemtype="https://schema.org/WebPage"', 'itemtype="https://schema.org/Product"', $output );
			}
		}
		
		return $output;
	}

	/**
	 * Add JSON-LD schema markup.
	 *
	 * @return void
	 */
	public function add_json_ld_schema() {
		// Check if JSON-LD schema should be added.
		if ( ! get_theme_mod( 'aqualuxe_enable_json_ld_schema', true ) ) {
			return;
		}
		
		// Get site schema.
		$schema = $this->get_site_schema();
		
		// Add page-specific schema.
		if ( is_singular( 'post' ) ) {
			$schema = array_merge( $schema, $this->get_article_schema() );
		} elseif ( is_author() ) {
			$schema = array_merge( $schema, $this->get_author_schema() );
		} elseif ( is_search() ) {
			$schema = array_merge( $schema, $this->get_search_schema() );
		}
		
		// Output schema.
		if ( ! empty( $schema ) ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
		}
	}

	/**
	 * Get site schema.
	 *
	 * @return array
	 */
	private function get_site_schema() {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'WebSite',
			'name'     => get_bloginfo( 'name' ),
			'url'      => home_url(),
		);
		
		// Add site description.
		$description = get_bloginfo( 'description' );
		if ( $description ) {
			$schema['description'] = $description;
		}
		
		// Add search action.
		$schema['potentialAction'] = array(
			'@type'       => 'SearchAction',
			'target'      => home_url( '?s={search_term_string}' ),
			'query-input' => 'required name=search_term_string',
		);
		
		// Add organization schema.
		$schema['publisher'] = array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
			'logo'  => $this->get_site_logo_url(),
		);
		
		return $schema;
	}

	/**
	 * Get article schema.
	 *
	 * @return array
	 */
	private function get_article_schema() {
		$post_id = get_the_ID();
		$schema  = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Article',
			'headline' => get_the_title( $post_id ),
			'url'      => get_permalink( $post_id ),
			'datePublished' => get_the_date( 'c', $post_id ),
			'dateModified'  => get_the_modified_date( 'c', $post_id ),
			'author'        => array(
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) ),
			),
			'publisher'     => array(
				'@type' => 'Organization',
				'name'  => get_bloginfo( 'name' ),
				'logo'  => array(
					'@type' => 'ImageObject',
					'url'   => $this->get_site_logo_url(),
				),
			),
		);
		
		// Add featured image.
		if ( has_post_thumbnail( $post_id ) ) {
			$image_id   = get_post_thumbnail_id( $post_id );
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
		
		// Add description.
		$excerpt = get_the_excerpt( $post_id );
		if ( $excerpt ) {
			$schema['description'] = $excerpt;
		}
		
		return $schema;
	}

	/**
	 * Get author schema.
	 *
	 * @return array
	 */
	private function get_author_schema() {
		$author_id = get_queried_object_id();
		$schema    = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Person',
			'name'     => get_the_author_meta( 'display_name', $author_id ),
			'url'      => get_author_posts_url( $author_id ),
		);
		
		// Add description.
		$description = get_the_author_meta( 'description', $author_id );
		if ( $description ) {
			$schema['description'] = $description;
		}
		
		return $schema;
	}

	/**
	 * Get search schema.
	 *
	 * @return array
	 */
	private function get_search_schema() {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'SearchResultsPage',
			'url'      => get_search_link(),
		);
		
		return $schema;
	}

	/**
	 * Add Open Graph tags.
	 *
	 * @return void
	 */
	public function add_open_graph_tags() {
		// Check if Open Graph tags should be added.
		if ( ! get_theme_mod( 'aqualuxe_enable_open_graph', true ) ) {
			return;
		}
		
		// Basic Open Graph tags.
		echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '" />' . "\n";
		echo '<meta property="og:type" content="' . esc_attr( $this->get_open_graph_type() ) . '" />' . "\n";
		echo '<meta property="og:title" content="' . esc_attr( $this->get_open_graph_title() ) . '" />' . "\n";
		echo '<meta property="og:description" content="' . esc_attr( $this->get_open_graph_description() ) . '" />' . "\n";
		echo '<meta property="og:url" content="' . esc_url( $this->get_current_url() ) . '" />' . "\n";
		echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
		
		// Image.
		$image = $this->get_open_graph_image();
		if ( $image ) {
			echo '<meta property="og:image" content="' . esc_url( $image['url'] ) . '" />' . "\n";
			
			if ( isset( $image['width'] ) && isset( $image['height'] ) ) {
				echo '<meta property="og:image:width" content="' . esc_attr( $image['width'] ) . '" />' . "\n";
				echo '<meta property="og:image:height" content="' . esc_attr( $image['height'] ) . '" />' . "\n";
			}
		}
		
		// Article-specific tags.
		if ( is_singular( 'post' ) ) {
			echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( 'c' ) ) . '" />' . "\n";
			echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( 'c' ) ) . '" />' . "\n";
			echo '<meta property="article:author" content="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" />' . "\n";
			
			// Categories.
			$categories = get_the_category();
			if ( $categories ) {
				foreach ( $categories as $category ) {
					echo '<meta property="article:section" content="' . esc_attr( $category->name ) . '" />' . "\n";
				}
			}
			
			// Tags.
			$tags = get_the_tags();
			if ( $tags ) {
				foreach ( $tags as $tag ) {
					echo '<meta property="article:tag" content="' . esc_attr( $tag->name ) . '" />' . "\n";
				}
			}
		}
	}

	/**
	 * Get Open Graph type.
	 *
	 * @return string
	 */
	private function get_open_graph_type() {
		if ( is_singular( 'post' ) ) {
			return 'article';
		} elseif ( is_author() ) {
			return 'profile';
		} elseif ( class_exists( 'WooCommerce' ) && is_product() ) {
			return 'product';
		}
		
		return 'website';
	}

	/**
	 * Get Open Graph title.
	 *
	 * @return string
	 */
	private function get_open_graph_title() {
		if ( is_home() ) {
			if ( get_option( 'page_for_posts', true ) ) {
				return get_the_title( get_option( 'page_for_posts' ) );
			}
			return get_bloginfo( 'name' );
		} elseif ( is_archive() ) {
			return get_the_archive_title();
		} elseif ( is_search() ) {
			/* translators: %s: search query */
			return sprintf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), get_search_query() );
		} elseif ( is_singular() ) {
			return get_the_title();
		}
		
		return get_bloginfo( 'name' );
	}

	/**
	 * Get Open Graph description.
	 *
	 * @return string
	 */
	private function get_open_graph_description() {
		if ( is_home() ) {
			return get_bloginfo( 'description' );
		} elseif ( is_singular() ) {
			$excerpt = get_the_excerpt();
			if ( $excerpt ) {
				return $excerpt;
			}
		}
		
		return get_bloginfo( 'description' );
	}

	/**
	 * Get Open Graph image.
	 *
	 * @return array|false
	 */
	private function get_open_graph_image() {
		if ( is_singular() && has_post_thumbnail() ) {
			$image_id   = get_post_thumbnail_id();
			$image_data = wp_get_attachment_image_src( $image_id, 'full' );
			
			if ( $image_data ) {
				return array(
					'url'    => $image_data[0],
					'width'  => $image_data[1],
					'height' => $image_data[2],
				);
			}
		}
		
		// Default image.
		$default_image = get_theme_mod( 'aqualuxe_default_og_image' );
		if ( $default_image ) {
			$image_data = wp_get_attachment_image_src( $default_image, 'full' );
			
			if ( $image_data ) {
				return array(
					'url'    => $image_data[0],
					'width'  => $image_data[1],
					'height' => $image_data[2],
				);
			}
		}
		
		// Site logo.
		$logo_url = $this->get_site_logo_url();
		if ( $logo_url ) {
			return array(
				'url' => $logo_url,
			);
		}
		
		return false;
	}

	/**
	 * Add Twitter Card tags.
	 *
	 * @return void
	 */
	public function add_twitter_card_tags() {
		// Check if Twitter Card tags should be added.
		if ( ! get_theme_mod( 'aqualuxe_enable_twitter_cards', true ) ) {
			return;
		}
		
		// Basic Twitter Card tags.
		echo '<meta name="twitter:card" content="' . esc_attr( $this->get_twitter_card_type() ) . '" />' . "\n";
		
		// Twitter username.
		$twitter_username = get_theme_mod( 'aqualuxe_twitter_username' );
		if ( $twitter_username ) {
			echo '<meta name="twitter:site" content="@' . esc_attr( $twitter_username ) . '" />' . "\n";
		}
		
		// Title and description.
		echo '<meta name="twitter:title" content="' . esc_attr( $this->get_open_graph_title() ) . '" />' . "\n";
		echo '<meta name="twitter:description" content="' . esc_attr( $this->get_open_graph_description() ) . '" />' . "\n";
		
		// Image.
		$image = $this->get_open_graph_image();
		if ( $image ) {
			echo '<meta name="twitter:image" content="' . esc_url( $image['url'] ) . '" />' . "\n";
		}
	}

	/**
	 * Get Twitter Card type.
	 *
	 * @return string
	 */
	private function get_twitter_card_type() {
		$image = $this->get_open_graph_image();
		
		if ( $image ) {
			return 'summary_large_image';
		}
		
		return 'summary';
	}

	/**
	 * Add canonical URL.
	 *
	 * @return void
	 */
	public function add_canonical_url() {
		// Check if canonical URL should be added.
		if ( ! get_theme_mod( 'aqualuxe_enable_canonical_url', true ) ) {
			return;
		}
		
		// Get canonical URL.
		$canonical = $this->get_canonical_url();
		
		if ( $canonical ) {
			echo '<link rel="canonical" href="' . esc_url( $canonical ) . '" />' . "\n";
		}
	}

	/**
	 * Get canonical URL.
	 *
	 * @return string
	 */
	private function get_canonical_url() {
		if ( is_singular() ) {
			return get_permalink();
		} elseif ( is_home() ) {
			return get_permalink( get_option( 'page_for_posts' ) );
		} elseif ( is_author() ) {
			return get_author_posts_url( get_queried_object_id() );
		} elseif ( is_category() || is_tag() || is_tax() ) {
			return get_term_link( get_queried_object() );
		} elseif ( is_post_type_archive() ) {
			return get_post_type_archive_link( get_post_type() );
		} elseif ( is_search() ) {
			return get_search_link();
		}
		
		return home_url( '/' );
	}

	/**
	 * Add meta description.
	 *
	 * @return void
	 */
	public function add_meta_description() {
		// Check if meta description should be added.
		if ( ! get_theme_mod( 'aqualuxe_enable_meta_description', true ) ) {
			return;
		}
		
		// Get meta description.
		$description = $this->get_meta_description();
		
		if ( $description ) {
			echo '<meta name="description" content="' . esc_attr( $description ) . '" />' . "\n";
		}
	}

	/**
	 * Get meta description.
	 *
	 * @return string
	 */
	private function get_meta_description() {
		if ( is_singular() ) {
			// Check for custom meta description.
			$custom_description = get_post_meta( get_the_ID(), '_aqualuxe_meta_description', true );
			if ( $custom_description ) {
				return $custom_description;
			}
			
			// Use excerpt.
			$excerpt = get_the_excerpt();
			if ( $excerpt ) {
				return $excerpt;
			}
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$term_description = term_description();
			if ( $term_description ) {
				return wp_strip_all_tags( $term_description );
			}
		} elseif ( is_author() ) {
			$author_description = get_the_author_meta( 'description', get_queried_object_id() );
			if ( $author_description ) {
				return wp_strip_all_tags( $author_description );
			}
		}
		
		return get_bloginfo( 'description' );
	}

	/**
	 * Add meta robots.
	 *
	 * @return void
	 */
	public function add_meta_robots() {
		// Check if meta robots should be added.
		if ( ! get_theme_mod( 'aqualuxe_enable_meta_robots', true ) ) {
			return;
		}
		
		// Get meta robots.
		$robots = $this->get_meta_robots();
		
		if ( $robots ) {
			echo '<meta name="robots" content="' . esc_attr( $robots ) . '" />' . "\n";
		}
	}

	/**
	 * Get meta robots.
	 *
	 * @return string
	 */
	private function get_meta_robots() {
		$robots = array();
		
		// Check if site is set to noindex.
		if ( ! get_option( 'blog_public' ) ) {
			$robots[] = 'noindex';
			$robots[] = 'nofollow';
			return implode( ',', $robots );
		}
		
		// Check for custom meta robots.
		if ( is_singular() ) {
			$custom_robots = get_post_meta( get_the_ID(), '_aqualuxe_meta_robots', true );
			if ( $custom_robots ) {
				return $custom_robots;
			}
		}
		
		// Default settings.
		if ( is_search() || is_404() ) {
			$robots[] = 'noindex';
		} else {
			$robots[] = 'index';
		}
		
		$robots[] = 'follow';
		
		// Add max-image-preview:large.
		$robots[] = 'max-image-preview:large';
		
		// Add max-snippet.
		$robots[] = 'max-snippet:-1';
		
		// Add max-video-preview.
		$robots[] = 'max-video-preview:-1';
		
		return implode( ',', $robots );
	}

	/**
	 * Add breadcrumb schema.
	 *
	 * @return void
	 */
	public function add_breadcrumb_schema() {
		// Check if breadcrumb schema should be added.
		if ( ! get_theme_mod( 'aqualuxe_enable_breadcrumb_schema', true ) ) {
			return;
		}
		
		// Get breadcrumb schema.
		$schema = $this->get_breadcrumb_schema();
		
		if ( $schema ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
		}
	}

	/**
	 * Get breadcrumb schema.
	 *
	 * @return array|false
	 */
	private function get_breadcrumb_schema() {
		// Get breadcrumbs.
		$breadcrumbs = apply_filters( 'aqualuxe_breadcrumbs', array() );
		
		if ( empty( $breadcrumbs ) ) {
			return false;
		}
		
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'BreadcrumbList',
			'itemListElement' => array(),
		);
		
		foreach ( $breadcrumbs as $position => $breadcrumb ) {
			$schema['itemListElement'][] = array(
				'@type'    => 'ListItem',
				'position' => $position + 1,
				'name'     => $breadcrumb['text'],
				'item'     => $breadcrumb['url'],
			);
		}
		
		return $schema;
	}

	/**
	 * Add product schema.
	 *
	 * @return void
	 */
	public function add_product_schema() {
		// Check if product schema should be added.
		if ( ! get_theme_mod( 'aqualuxe_enable_product_schema', true ) ) {
			return;
		}
		
		// Get product schema.
		$schema = $this->get_product_schema();
		
		if ( $schema ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';
		}
	}

	/**
	 * Get product schema.
	 *
	 * @return array|false
	 */
	private function get_product_schema() {
		if ( ! function_exists( 'wc_get_product' ) ) {
			return false;
		}
		
		$product = wc_get_product( get_the_ID() );
		
		if ( ! $product ) {
			return false;
		}
		
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Product',
			'name'     => $product->get_name(),
			'url'      => get_permalink( $product->get_id() ),
		);
		
		// Add description.
		$description = $product->get_short_description() ? $product->get_short_description() : $product->get_description();
		if ( $description ) {
			$schema['description'] = wp_strip_all_tags( $description );
		}
		
		// Add SKU.
		$sku = $product->get_sku();
		if ( $sku ) {
			$schema['sku'] = $sku;
		}
		
		// Add image.
		if ( $product->get_image_id() ) {
			$image_data = wp_get_attachment_image_src( $product->get_image_id(), 'full' );
			
			if ( $image_data ) {
				$schema['image'] = array(
					'@type'  => 'ImageObject',
					'url'    => $image_data[0],
					'width'  => $image_data[1],
					'height' => $image_data[2],
				);
			}
		}
		
		// Add brand.
		$brands = wp_get_post_terms( $product->get_id(), 'product_brand' );
		if ( $brands && ! is_wp_error( $brands ) ) {
			$schema['brand'] = array(
				'@type' => 'Brand',
				'name'  => $brands[0]->name,
			);
		}
		
		// Add offers.
		$schema['offers'] = array(
			'@type'         => 'Offer',
			'price'         => $product->get_price(),
			'priceCurrency' => get_woocommerce_currency(),
			'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
			'url'           => get_permalink( $product->get_id() ),
		);
		
		// Add review data.
		if ( $product->get_review_count() ) {
			$schema['aggregateRating'] = array(
				'@type'       => 'AggregateRating',
				'ratingValue' => $product->get_average_rating(),
				'reviewCount' => $product->get_review_count(),
			);
		}
		
		return $schema;
	}

	/**
	 * Get current URL.
	 *
	 * @return string
	 */
	private function get_current_url() {
		global $wp;
		return home_url( $wp->request );
	}

	/**
	 * Get site logo URL.
	 *
	 * @return string
	 */
	private function get_site_logo_url() {
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		
		if ( $custom_logo_id ) {
			$logo_data = wp_get_attachment_image_src( $custom_logo_id, 'full' );
			
			if ( $logo_data ) {
				return $logo_data[0];
			}
		}
		
		return '';
	}
}