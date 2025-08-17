<?php
/**
 * AquaLuxe Schema Markup
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.2.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Schema Class
 *
 * Handles schema.org markup for the theme.
 *
 * @since 1.2.0
 */
class Schema {

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
		// Skip if schema markup is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_schema_markup', true ) ) {
			return;
		}
		
		// Skip if an SEO plugin is active.
		if ( $this->is_seo_plugin_active() ) {
			return;
		}
		
		// Add schema markup to HTML tag.
		add_filter( 'language_attributes', array( $this, 'add_schema_attribute' ) );
		
		// Add JSON-LD schema markup.
		add_action( 'wp_footer', array( $this, 'add_json_ld_schema' ) );
		
		// Add schema markup for specific content types.
		add_action( 'wp_footer', array( $this, 'add_webpage_schema' ) );
		add_action( 'wp_footer', array( $this, 'add_article_schema' ) );
		add_action( 'wp_footer', array( $this, 'add_breadcrumb_schema' ) );
		
		// Add WooCommerce schema markup.
		if ( class_exists( 'WooCommerce' ) ) {
			add_action( 'wp_footer', array( $this, 'add_product_schema' ) );
			add_action( 'wp_footer', array( $this, 'add_review_schema' ) );
		}
		
		// Add schema markup for comments.
		add_filter( 'comment_text', array( $this, 'add_comment_schema' ), 99, 2 );
		
		// Add schema markup for author pages.
		add_action( 'wp_footer', array( $this, 'add_author_schema' ) );
		
		// Add schema markup for search results.
		add_action( 'wp_footer', array( $this, 'add_search_schema' ) );
		
		// Add schema markup for organization.
		add_action( 'wp_footer', array( $this, 'add_organization_schema' ) );
		
		// Add schema markup for local business.
		add_action( 'wp_footer', array( $this, 'add_local_business_schema' ) );
	}

	/**
	 * Add schema attribute to HTML tag.
	 *
	 * @param string $output The language attributes.
	 * @return string
	 */
	public function add_schema_attribute( $output ) {
		return $output . ' itemscope itemtype="https://schema.org/WebPage"';
	}

	/**
	 * Add JSON-LD schema markup.
	 *
	 * @return void
	 */
	public function add_json_ld_schema() {
		// Add WebSite schema.
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
			'target'      => home_url( '/?s={search_term_string}' ),
			'query-input' => 'required name=search_term_string',
		);
		
		// Output schema.
		$this->output_schema( $schema );
	}

	/**
	 * Add WebPage schema.
	 *
	 * @return void
	 */
	public function add_webpage_schema() {
		// Skip on non-singular pages.
		if ( ! is_singular() ) {
			return;
		}
		
		// Skip on articles and products.
		if ( is_singular( 'post' ) || ( class_exists( 'WooCommerce' ) && is_product() ) ) {
			return;
		}
		
		$post = get_post();
		
		// Basic WebPage schema.
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'WebPage',
			'name'     => get_the_title(),
			'url'      => get_permalink(),
		);
		
		// Add description.
		$excerpt = get_the_excerpt( $post );
		if ( $excerpt ) {
			$schema['description'] = $excerpt;
		}
		
		// Add last reviewed date.
		$schema['lastReviewed'] = get_the_modified_date( 'c' );
		
		// Add main entity.
		$schema['mainEntity'] = array(
			'@type' => 'WebPage',
			'name'  => get_the_title(),
		);
		
		// Add primary image.
		if ( has_post_thumbnail() ) {
			$image_id   = get_post_thumbnail_id();
			$image_data = wp_get_attachment_image_src( $image_id, 'full' );
			
			if ( $image_data ) {
				$schema['primaryImageOfPage'] = array(
					'@type'  => 'ImageObject',
					'url'    => $image_data[0],
					'width'  => $image_data[1],
					'height' => $image_data[2],
				);
			}
		}
		
		// Output schema.
		$this->output_schema( $schema );
	}

	/**
	 * Add Article schema.
	 *
	 * @return void
	 */
	public function add_article_schema() {
		// Skip if not a single post.
		if ( ! is_singular( 'post' ) ) {
			return;
		}
		
		$post = get_post();
		
		// Basic Article schema.
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Article',
			'headline' => get_the_title(),
			'url'      => get_permalink(),
			'datePublished' => get_the_date( 'c' ),
			'dateModified'  => get_the_modified_date( 'c' ),
			'author'        => array(
				'@type' => 'Person',
				'name'  => get_the_author_meta( 'display_name' ),
				'url'   => get_author_posts_url( $post->post_author ),
			),
			'publisher'     => $this->get_publisher_schema(),
		);
		
		// Add description.
		$excerpt = get_the_excerpt( $post );
		if ( $excerpt ) {
			$schema['description'] = $excerpt;
		}
		
		// Add featured image.
		if ( has_post_thumbnail() ) {
			$image_id   = get_post_thumbnail_id();
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
		
		// Add categories.
		$categories = get_the_category();
		if ( $categories ) {
			$schema['articleSection'] = $categories[0]->name;
		}
		
		// Add tags.
		$tags = get_the_tags();
		if ( $tags ) {
			$schema['keywords'] = wp_list_pluck( $tags, 'name' );
		}
		
		// Add word count.
		$word_count = str_word_count( strip_tags( $post->post_content ) );
		$schema['wordCount'] = $word_count;
		
		// Output schema.
		$this->output_schema( $schema );
	}

	/**
	 * Add Breadcrumb schema.
	 *
	 * @return void
	 */
	public function add_breadcrumb_schema() {
		// Skip on front page.
		if ( is_front_page() ) {
			return;
		}
		
		// Get breadcrumbs.
		$breadcrumbs = apply_filters( 'aqualuxe_breadcrumbs', array() );
		
		if ( empty( $breadcrumbs ) ) {
			return;
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
		
		// Output schema.
		$this->output_schema( $schema );
	}

	/**
	 * Add Product schema.
	 *
	 * @return void
	 */
	public function add_product_schema() {
		// Skip if not a product page.
		if ( ! class_exists( 'WooCommerce' ) || ! is_product() ) {
			return;
		}
		
		$product = wc_get_product( get_the_ID() );
		
		if ( ! $product ) {
			return;
		}
		
		// Basic Product schema.
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Product',
			'name'     => $product->get_name(),
			'url'      => get_permalink(),
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
		
		// Add MPN.
		$mpn = get_post_meta( $product->get_id(), '_aqualuxe_product_mpn', true );
		if ( $mpn ) {
			$schema['mpn'] = $mpn;
		}
		
		// Add GTIN.
		$gtin = get_post_meta( $product->get_id(), '_aqualuxe_product_gtin', true );
		if ( $gtin ) {
			$schema['gtin'] = $gtin;
		}
		
		// Add brand.
		$brands = wp_get_post_terms( $product->get_id(), 'product_brand' );
		if ( $brands && ! is_wp_error( $brands ) ) {
			$schema['brand'] = array(
				'@type' => 'Brand',
				'name'  => $brands[0]->name,
			);
		} else {
			// Use site name as brand if no product brand is set.
			$schema['brand'] = array(
				'@type' => 'Brand',
				'name'  => get_bloginfo( 'name' ),
			);
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
		
		// Add offers.
		$schema['offers'] = array(
			'@type'         => 'Offer',
			'price'         => $product->get_price(),
			'priceCurrency' => get_woocommerce_currency(),
			'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
			'url'           => get_permalink(),
			'priceValidUntil' => date( 'c', strtotime( '+1 year' ) ),
		);
		
		// Add review data.
		if ( $product->get_review_count() ) {
			$schema['aggregateRating'] = array(
				'@type'       => 'AggregateRating',
				'ratingValue' => $product->get_average_rating(),
				'reviewCount' => $product->get_review_count(),
				'bestRating'  => '5',
				'worstRating' => '1',
			);
		}
		
		// Output schema.
		$this->output_schema( $schema );
	}

	/**
	 * Add Review schema.
	 *
	 * @return void
	 */
	public function add_review_schema() {
		// Skip if not a product page.
		if ( ! class_exists( 'WooCommerce' ) || ! is_product() ) {
			return;
		}
		
		$product = wc_get_product( get_the_ID() );
		
		if ( ! $product || ! $product->get_review_count() ) {
			return;
		}
		
		// Get reviews.
		$args = array(
			'post_id' => $product->get_id(),
			'status'  => 'approve',
			'type'    => 'review',
			'number'  => 5,
		);
		
		$reviews = get_comments( $args );
		
		if ( empty( $reviews ) ) {
			return;
		}
		
		foreach ( $reviews as $review ) {
			$rating = get_comment_meta( $review->comment_ID, 'rating', true );
			
			if ( ! $rating ) {
				continue;
			}
			
			$schema = array(
				'@context' => 'https://schema.org',
				'@type'    => 'Review',
				'reviewRating' => array(
					'@type'       => 'Rating',
					'ratingValue' => $rating,
					'bestRating'  => '5',
					'worstRating' => '1',
				),
				'author' => array(
					'@type' => 'Person',
					'name'  => $review->comment_author,
				),
				'datePublished' => date( 'c', strtotime( $review->comment_date ) ),
				'reviewBody'    => $review->comment_content,
				'itemReviewed'  => array(
					'@type' => 'Product',
					'name'  => $product->get_name(),
					'url'   => get_permalink( $product->get_id() ),
				),
			);
			
			// Output schema.
			$this->output_schema( $schema );
		}
	}

	/**
	 * Add Comment schema.
	 *
	 * @param string $comment_text Comment text.
	 * @param object $comment      Comment object.
	 * @return string
	 */
	public function add_comment_schema( $comment_text, $comment ) {
		// Skip if comment is not approved.
		if ( '1' !== $comment->comment_approved ) {
			return $comment_text;
		}
		
		// Skip if comment is a review.
		if ( 'review' === get_comment_type( $comment ) ) {
			return $comment_text;
		}
		
		// Add schema attributes.
		$schema_attributes = ' itemprop="text"';
		
		// Add schema to comment text.
		$comment_text = '<div itemscope itemtype="https://schema.org/Comment">' .
			'<meta itemprop="datePublished" content="' . esc_attr( date( 'c', strtotime( $comment->comment_date ) ) ) . '">' .
			'<div itemprop="author" itemscope itemtype="https://schema.org/Person">' .
			'<meta itemprop="name" content="' . esc_attr( $comment->comment_author ) . '">' .
			'</div>' .
			'<div' . $schema_attributes . '>' . $comment_text . '</div>' .
			'</div>';
		
		return $comment_text;
	}

	/**
	 * Add Author schema.
	 *
	 * @return void
	 */
	public function add_author_schema() {
		// Skip if not an author page.
		if ( ! is_author() ) {
			return;
		}
		
		$author_id = get_queried_object_id();
		
		// Basic Person schema.
		$schema = array(
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
		
		// Add email.
		$email = get_the_author_meta( 'user_email', $author_id );
		if ( $email && get_the_author_meta( 'show_email', $author_id ) ) {
			$schema['email'] = $email;
		}
		
		// Add social profiles.
		$social_profiles = array();
		
		$facebook = get_the_author_meta( 'facebook', $author_id );
		if ( $facebook ) {
			$social_profiles[] = $facebook;
		}
		
		$twitter = get_the_author_meta( 'twitter', $author_id );
		if ( $twitter ) {
			$social_profiles[] = 'https://twitter.com/' . str_replace( '@', '', $twitter );
		}
		
		$linkedin = get_the_author_meta( 'linkedin', $author_id );
		if ( $linkedin ) {
			$social_profiles[] = $linkedin;
		}
		
		$instagram = get_the_author_meta( 'instagram', $author_id );
		if ( $instagram ) {
			$social_profiles[] = 'https://instagram.com/' . str_replace( '@', '', $instagram );
		}
		
		if ( ! empty( $social_profiles ) ) {
			$schema['sameAs'] = $social_profiles;
		}
		
		// Output schema.
		$this->output_schema( $schema );
	}

	/**
	 * Add Search schema.
	 *
	 * @return void
	 */
	public function add_search_schema() {
		// Skip if not a search page.
		if ( ! is_search() ) {
			return;
		}
		
		// Basic SearchResultsPage schema.
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'SearchResultsPage',
			'url'      => get_search_link(),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => home_url( '/?s={search_term_string}' ),
				'query-input' => 'required name=search_term_string',
			),
		);
		
		// Output schema.
		$this->output_schema( $schema );
	}

	/**
	 * Add Organization schema.
	 *
	 * @return void
	 */
	public function add_organization_schema() {
		// Skip if not front page.
		if ( ! is_front_page() ) {
			return;
		}
		
		// Skip if local business schema is enabled.
		if ( get_theme_mod( 'aqualuxe_enable_local_business_schema', false ) ) {
			return;
		}
		
		// Basic Organization schema.
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => 'Organization',
			'name'     => get_bloginfo( 'name' ),
			'url'      => home_url(),
		);
		
		// Add logo.
		$logo_url = $this->get_site_logo_url();
		if ( $logo_url ) {
			$schema['logo'] = $logo_url;
		}
		
		// Add contact information.
		$contact_info = array(
			'@type' => 'ContactPoint',
			'contactType' => 'customer service',
		);
		
		$phone = get_theme_mod( 'aqualuxe_organization_phone' );
		if ( $phone ) {
			$contact_info['telephone'] = $phone;
		}
		
		$email = get_theme_mod( 'aqualuxe_organization_email' );
		if ( $email ) {
			$contact_info['email'] = $email;
		}
		
		$schema['contactPoint'] = $contact_info;
		
		// Add social profiles.
		$social_profiles = array();
		
		$facebook = get_theme_mod( 'aqualuxe_social_facebook' );
		if ( $facebook ) {
			$social_profiles[] = $facebook;
		}
		
		$twitter = get_theme_mod( 'aqualuxe_social_twitter' );
		if ( $twitter ) {
			$social_profiles[] = $twitter;
		}
		
		$instagram = get_theme_mod( 'aqualuxe_social_instagram' );
		if ( $instagram ) {
			$social_profiles[] = $instagram;
		}
		
		$linkedin = get_theme_mod( 'aqualuxe_social_linkedin' );
		if ( $linkedin ) {
			$social_profiles[] = $linkedin;
		}
		
		$youtube = get_theme_mod( 'aqualuxe_social_youtube' );
		if ( $youtube ) {
			$social_profiles[] = $youtube;
		}
		
		if ( ! empty( $social_profiles ) ) {
			$schema['sameAs'] = $social_profiles;
		}
		
		// Output schema.
		$this->output_schema( $schema );
	}

	/**
	 * Add Local Business schema.
	 *
	 * @return void
	 */
	public function add_local_business_schema() {
		// Skip if not front page.
		if ( ! is_front_page() ) {
			return;
		}
		
		// Skip if local business schema is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_local_business_schema', false ) ) {
			return;
		}
		
		// Get business type.
		$business_type = get_theme_mod( 'aqualuxe_business_type', 'LocalBusiness' );
		
		// Basic LocalBusiness schema.
		$schema = array(
			'@context' => 'https://schema.org',
			'@type'    => $business_type,
			'name'     => get_bloginfo( 'name' ),
			'url'      => home_url(),
		);
		
		// Add description.
		$description = get_bloginfo( 'description' );
		if ( $description ) {
			$schema['description'] = $description;
		}
		
		// Add logo.
		$logo_url = $this->get_site_logo_url();
		if ( $logo_url ) {
			$schema['image'] = $logo_url;
			$schema['logo']  = $logo_url;
		}
		
		// Add address.
		$street = get_theme_mod( 'aqualuxe_business_street' );
		$city   = get_theme_mod( 'aqualuxe_business_city' );
		$state  = get_theme_mod( 'aqualuxe_business_state' );
		$zip    = get_theme_mod( 'aqualuxe_business_zip' );
		$country = get_theme_mod( 'aqualuxe_business_country' );
		
		if ( $street && $city && $state && $zip && $country ) {
			$schema['address'] = array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => $street,
				'addressLocality' => $city,
				'addressRegion'   => $state,
				'postalCode'      => $zip,
				'addressCountry'  => $country,
			);
		}
		
		// Add geo coordinates.
		$latitude  = get_theme_mod( 'aqualuxe_business_latitude' );
		$longitude = get_theme_mod( 'aqualuxe_business_longitude' );
		
		if ( $latitude && $longitude ) {
			$schema['geo'] = array(
				'@type'     => 'GeoCoordinates',
				'latitude'  => $latitude,
				'longitude' => $longitude,
			);
		}
		
		// Add contact information.
		$phone = get_theme_mod( 'aqualuxe_business_phone' );
		if ( $phone ) {
			$schema['telephone'] = $phone;
		}
		
		$email = get_theme_mod( 'aqualuxe_business_email' );
		if ( $email ) {
			$schema['email'] = $email;
		}
		
		// Add price range.
		$price_range = get_theme_mod( 'aqualuxe_business_price_range' );
		if ( $price_range ) {
			$schema['priceRange'] = $price_range;
		}
		
		// Add opening hours.
		$opening_hours = $this->get_opening_hours();
		if ( ! empty( $opening_hours ) ) {
			$schema['openingHoursSpecification'] = $opening_hours;
		}
		
		// Add social profiles.
		$social_profiles = array();
		
		$facebook = get_theme_mod( 'aqualuxe_social_facebook' );
		if ( $facebook ) {
			$social_profiles[] = $facebook;
		}
		
		$twitter = get_theme_mod( 'aqualuxe_social_twitter' );
		if ( $twitter ) {
			$social_profiles[] = $twitter;
		}
		
		$instagram = get_theme_mod( 'aqualuxe_social_instagram' );
		if ( $instagram ) {
			$social_profiles[] = $instagram;
		}
		
		$linkedin = get_theme_mod( 'aqualuxe_social_linkedin' );
		if ( $linkedin ) {
			$social_profiles[] = $linkedin;
		}
		
		$youtube = get_theme_mod( 'aqualuxe_social_youtube' );
		if ( $youtube ) {
			$social_profiles[] = $youtube;
		}
		
		if ( ! empty( $social_profiles ) ) {
			$schema['sameAs'] = $social_profiles;
		}
		
		// Output schema.
		$this->output_schema( $schema );
	}

	/**
	 * Get publisher schema.
	 *
	 * @return array
	 */
	private function get_publisher_schema() {
		$publisher = array(
			'@type' => 'Organization',
			'name'  => get_bloginfo( 'name' ),
		);
		
		// Add logo.
		$logo_url = $this->get_site_logo_url();
		if ( $logo_url ) {
			$publisher['logo'] = array(
				'@type' => 'ImageObject',
				'url'   => $logo_url,
			);
		}
		
		return $publisher;
	}

	/**
	 * Get opening hours.
	 *
	 * @return array
	 */
	private function get_opening_hours() {
		$days = array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' );
		$opening_hours = array();
		
		foreach ( $days as $day ) {
			$day_lower = strtolower( $day );
			$opens  = get_theme_mod( "aqualuxe_business_{$day_lower}_opens" );
			$closes = get_theme_mod( "aqualuxe_business_{$day_lower}_closes" );
			
			if ( $opens && $closes ) {
				$opening_hours[] = array(
					'@type'     => 'OpeningHoursSpecification',
					'dayOfWeek' => $day,
					'opens'     => $opens,
					'closes'    => $closes,
				);
			}
		}
		
		return $opening_hours;
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

	/**
	 * Output schema markup.
	 *
	 * @param array $schema Schema data.
	 * @return void
	 */
	private function output_schema( $schema ) {
		if ( ! empty( $schema ) ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
		}
	}

	/**
	 * Check if an SEO plugin is active.
	 *
	 * @return bool
	 */
	private function is_seo_plugin_active() {
		// Check for Yoast SEO.
		if ( defined( 'WPSEO_VERSION' ) ) {
			return true;
		}
		
		// Check for All in One SEO Pack.
		if ( defined( 'AIOSEOP_VERSION' ) ) {
			return true;
		}
		
		// Check for Rank Math.
		if ( defined( 'RANK_MATH_VERSION' ) ) {
			return true;
		}
		
		// Check for SEOPress.
		if ( defined( 'SEOPRESS_VERSION' ) ) {
			return true;
		}
		
		return false;
	}
}