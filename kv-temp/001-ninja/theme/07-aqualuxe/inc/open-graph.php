<?php
/**
 * Open Graph metadata implementation
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * AquaLuxe Open Graph Class
 */
class AquaLuxe_Open_Graph {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add Open Graph meta tags to the head
		add_action( 'wp_head', array( $this, 'add_open_graph_tags' ), 5 );
		
		// Add Twitter Card meta tags to the head
		add_action( 'wp_head', array( $this, 'add_twitter_card_tags' ), 6 );
	}

	/**
	 * Add Open Graph meta tags
	 */
	public function add_open_graph_tags() {
		// Skip if Yoast SEO is active and has Open Graph enabled
		if ( $this->is_yoast_seo_active() && $this->is_yoast_og_enabled() ) {
			return;
		}

		// Skip if Rank Math is active and has Open Graph enabled
		if ( $this->is_rank_math_active() && $this->is_rank_math_og_enabled() ) {
			return;
		}

		// Basic Open Graph tags
		$this->add_basic_og_tags();

		// Page specific Open Graph tags
		if ( is_singular() ) {
			$this->add_singular_og_tags();
		} elseif ( is_archive() || is_home() ) {
			$this->add_archive_og_tags();
		}
	}

	/**
	 * Add Twitter Card meta tags
	 */
	public function add_twitter_card_tags() {
		// Skip if Yoast SEO is active and has Twitter Card enabled
		if ( $this->is_yoast_seo_active() && $this->is_yoast_twitter_enabled() ) {
			return;
		}

		// Skip if Rank Math is active and has Twitter Card enabled
		if ( $this->is_rank_math_active() && $this->is_rank_math_twitter_enabled() ) {
			return;
		}

		// Twitter Card type
		$card_type = 'summary_large_image';
		
		// Twitter username
		$twitter_username = get_theme_mod( 'aqualuxe_social_twitter_username', '' );
		if ( ! empty( $twitter_username ) ) {
			$twitter_username = str_replace( '@', '', $twitter_username );
			$twitter_username = str_replace( 'https://twitter.com/', '', $twitter_username );
			$twitter_username = str_replace( 'http://twitter.com/', '', $twitter_username );
			$twitter_username = str_replace( 'twitter.com/', '', $twitter_username );
		}

		// Output Twitter Card tags
		echo '<meta name="twitter:card" content="' . esc_attr( $card_type ) . '" />' . "\n";
		
		if ( ! empty( $twitter_username ) ) {
			echo '<meta name="twitter:site" content="@' . esc_attr( $twitter_username ) . '" />' . "\n";
			echo '<meta name="twitter:creator" content="@' . esc_attr( $twitter_username ) . '" />' . "\n";
		}
	}

	/**
	 * Add basic Open Graph tags
	 */
	private function add_basic_og_tags() {
		// Site name
		echo '<meta property="og:site_name" content="' . esc_attr( get_bloginfo( 'name' ) ) . '" />' . "\n";
		
		// Locale
		$locale = get_locale();
		if ( ! empty( $locale ) ) {
			echo '<meta property="og:locale" content="' . esc_attr( $locale ) . '" />' . "\n";
		}
	}

	/**
	 * Add singular Open Graph tags
	 */
	private function add_singular_og_tags() {
		global $post;

		// Type
		$type = 'article';
		if ( is_page() ) {
			$type = 'website';
		} elseif ( is_singular( 'product' ) && class_exists( 'WooCommerce' ) ) {
			$type = 'product';
		}
		echo '<meta property="og:type" content="' . esc_attr( $type ) . '" />' . "\n";

		// Title
		$title = wp_strip_all_tags( get_the_title() );
		echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";

		// URL
		$url = get_permalink();
		echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";

		// Description
		$description = '';
		if ( has_excerpt() ) {
			$description = wp_strip_all_tags( get_the_excerpt() );
		} else {
			$description = wp_strip_all_tags( wp_trim_words( $post->post_content, 55, '...' ) );
		}
		if ( ! empty( $description ) ) {
			echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
		}

		// Image
		if ( has_post_thumbnail() ) {
			$image_id   = get_post_thumbnail_id();
			$image_data = wp_get_attachment_image_src( $image_id, 'full' );
			
			if ( $image_data ) {
				echo '<meta property="og:image" content="' . esc_url( $image_data[0] ) . '" />' . "\n";
				echo '<meta property="og:image:width" content="' . esc_attr( $image_data[1] ) . '" />' . "\n";
				echo '<meta property="og:image:height" content="' . esc_attr( $image_data[2] ) . '" />' . "\n";
				
				// Image alt text
				$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
				if ( ! empty( $image_alt ) ) {
					echo '<meta property="og:image:alt" content="' . esc_attr( $image_alt ) . '" />' . "\n";
				}
			}
		} else {
			// Default image
			$default_image = get_theme_mod( 'aqualuxe_default_og_image', '' );
			if ( ! empty( $default_image ) ) {
				echo '<meta property="og:image" content="' . esc_url( $default_image ) . '" />' . "\n";
			}
		}

		// Article specific tags
		if ( 'article' === $type ) {
			// Published time
			echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( 'c' ) ) . '" />' . "\n";
			
			// Modified time
			echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( 'c' ) ) . '" />' . "\n";
			
			// Author
			echo '<meta property="article:author" content="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" />' . "\n";
			
			// Categories
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				foreach ( $categories as $category ) {
					echo '<meta property="article:section" content="' . esc_attr( $category->name ) . '" />' . "\n";
				}
			}
			
			// Tags
			$tags = get_the_tags();
			if ( ! empty( $tags ) ) {
				foreach ( $tags as $tag ) {
					echo '<meta property="article:tag" content="' . esc_attr( $tag->name ) . '" />' . "\n";
				}
			}
		}

		// Product specific tags
		if ( 'product' === $type && class_exists( 'WooCommerce' ) ) {
			$product = wc_get_product( get_the_ID() );
			if ( $product ) {
				// Price
				if ( $product->get_price() ) {
					echo '<meta property="product:price:amount" content="' . esc_attr( $product->get_price() ) . '" />' . "\n";
					echo '<meta property="product:price:currency" content="' . esc_attr( get_woocommerce_currency() ) . '" />' . "\n";
				}
				
				// Availability
				$availability = $product->is_in_stock() ? 'instock' : 'oos';
				echo '<meta property="product:availability" content="' . esc_attr( $availability ) . '" />' . "\n";
				
				// Brand
				$brands = get_the_terms( $product->get_id(), 'product_brand' );
				if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) {
					echo '<meta property="product:brand" content="' . esc_attr( $brands[0]->name ) . '" />' . "\n";
				}
			}
		}
	}

	/**
	 * Add archive Open Graph tags
	 */
	private function add_archive_og_tags() {
		// Type
		echo '<meta property="og:type" content="website" />' . "\n";

		// Title
		if ( is_home() ) {
			$title = wp_strip_all_tags( get_the_title( get_option( 'page_for_posts' ) ) );
			if ( empty( $title ) ) {
				$title = esc_html__( 'Blog', 'aqualuxe' );
			}
		} elseif ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = get_the_author();
		} elseif ( is_year() ) {
			$title = get_the_date( 'Y' );
		} elseif ( is_month() ) {
			$title = get_the_date( 'F Y' );
		} elseif ( is_day() ) {
			$title = get_the_date();
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		} else {
			$title = get_bloginfo( 'name' );
		}
		echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";

		// URL
		$url = '';
		if ( is_home() ) {
			$url = get_permalink( get_option( 'page_for_posts' ) );
		} else {
			$url = get_pagenum_link( 1 );
		}
		echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";

		// Description
		$description = '';
		if ( is_category() || is_tag() || is_tax() ) {
			$term_description = term_description();
			if ( ! empty( $term_description ) ) {
				$description = wp_strip_all_tags( $term_description );
			}
		} elseif ( is_author() ) {
			$description = get_the_author_meta( 'description' );
		} elseif ( is_home() && get_option( 'page_for_posts' ) ) {
			$post = get_post( get_option( 'page_for_posts' ) );
			if ( $post ) {
				$description = wp_strip_all_tags( $post->post_excerpt );
				if ( empty( $description ) ) {
					$description = wp_strip_all_tags( wp_trim_words( $post->post_content, 55, '...' ) );
				}
			}
		}
		
		if ( empty( $description ) ) {
			$description = get_bloginfo( 'description' );
		}
		
		if ( ! empty( $description ) ) {
			echo '<meta property="og:description" content="' . esc_attr( $description ) . '" />' . "\n";
		}

		// Image
		$image_url = '';
		
		if ( is_category() || is_tag() || is_tax() ) {
			// Try to get term meta image
			$term_id = get_queried_object_id();
			$term_meta_image = get_term_meta( $term_id, 'thumbnail_id', true );
			if ( ! empty( $term_meta_image ) ) {
				$image_data = wp_get_attachment_image_src( $term_meta_image, 'full' );
				if ( $image_data ) {
					$image_url = $image_data[0];
				}
			}
		} elseif ( is_author() ) {
			// Get author avatar
			$author_id = get_the_author_meta( 'ID' );
			$avatar_url = get_avatar_url( $author_id, array( 'size' => 512 ) );
			if ( $avatar_url ) {
				$image_url = $avatar_url;
			}
		} elseif ( is_home() && get_option( 'page_for_posts' ) ) {
			// Get blog page featured image
			$blog_page_id = get_option( 'page_for_posts' );
			if ( has_post_thumbnail( $blog_page_id ) ) {
				$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $blog_page_id ), 'full' );
				if ( $image_data ) {
					$image_url = $image_data[0];
				}
			}
		}
		
		// Use default image if no image found
		if ( empty( $image_url ) ) {
			$default_image = get_theme_mod( 'aqualuxe_default_og_image', '' );
			if ( ! empty( $default_image ) ) {
				$image_url = $default_image;
			}
		}
		
		if ( ! empty( $image_url ) ) {
			echo '<meta property="og:image" content="' . esc_url( $image_url ) . '" />' . "\n";
		}
	}

	/**
	 * Check if Yoast SEO is active
	 *
	 * @return bool
	 */
	private function is_yoast_seo_active() {
		return defined( 'WPSEO_VERSION' );
	}

	/**
	 * Check if Yoast SEO Open Graph is enabled
	 *
	 * @return bool
	 */
	private function is_yoast_og_enabled() {
		if ( ! $this->is_yoast_seo_active() ) {
			return false;
		}

		$options = get_option( 'wpseo_social' );
		return isset( $options['opengraph'] ) && $options['opengraph'];
	}

	/**
	 * Check if Yoast SEO Twitter Card is enabled
	 *
	 * @return bool
	 */
	private function is_yoast_twitter_enabled() {
		if ( ! $this->is_yoast_seo_active() ) {
			return false;
		}

		$options = get_option( 'wpseo_social' );
		return isset( $options['twitter'] ) && $options['twitter'];
	}

	/**
	 * Check if Rank Math is active
	 *
	 * @return bool
	 */
	private function is_rank_math_active() {
		return class_exists( 'RankMath' );
	}

	/**
	 * Check if Rank Math Open Graph is enabled
	 *
	 * @return bool
	 */
	private function is_rank_math_og_enabled() {
		if ( ! $this->is_rank_math_active() ) {
			return false;
		}

		$options = get_option( 'rank-math-options-titles' );
		return isset( $options['open_graph_meta'] ) && $options['open_graph_meta'];
	}

	/**
	 * Check if Rank Math Twitter Card is enabled
	 *
	 * @return bool
	 */
	private function is_rank_math_twitter_enabled() {
		if ( ! $this->is_rank_math_active() ) {
			return false;
		}

		$options = get_option( 'rank-math-options-titles' );
		return isset( $options['twitter_card_meta'] ) && $options['twitter_card_meta'];
	}
}

// Initialize the Open Graph class
new AquaLuxe_Open_Graph();

/**
 * Add Open Graph settings to the customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_open_graph_customizer_settings( $wp_customize ) {
	// Add Open Graph section
	$wp_customize->add_section( 'aqualuxe_open_graph_settings', array(
		'title'    => esc_html__( 'Open Graph & Social', 'aqualuxe' ),
		'priority' => 150,
		'panel'    => 'aqualuxe_advanced_settings',
	) );

	// Default Open Graph image
	$wp_customize->add_setting( 'aqualuxe_default_og_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'aqualuxe_default_og_image', array(
		'label'       => esc_html__( 'Default Social Image', 'aqualuxe' ),
		'description' => esc_html__( 'This image will be used for social sharing when no featured image is set.', 'aqualuxe' ),
		'section'     => 'aqualuxe_open_graph_settings',
		'settings'    => 'aqualuxe_default_og_image',
		'priority'    => 10,
	) ) );

	// Twitter username
	$wp_customize->add_setting( 'aqualuxe_social_twitter_username', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'aqualuxe_social_twitter_username', array(
		'label'       => esc_html__( 'Twitter Username', 'aqualuxe' ),
		'description' => esc_html__( 'Enter your Twitter username (without @).', 'aqualuxe' ),
		'section'     => 'aqualuxe_open_graph_settings',
		'type'        => 'text',
		'priority'    => 20,
	) );

	// Facebook App ID
	$wp_customize->add_setting( 'aqualuxe_facebook_app_id', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'aqualuxe_facebook_app_id', array(
		'label'       => esc_html__( 'Facebook App ID', 'aqualuxe' ),
		'description' => esc_html__( 'Enter your Facebook App ID for domain verification.', 'aqualuxe' ),
		'section'     => 'aqualuxe_open_graph_settings',
		'type'        => 'text',
		'priority'    => 30,
	) );
}
add_action( 'customize_register', 'aqualuxe_open_graph_customizer_settings' );