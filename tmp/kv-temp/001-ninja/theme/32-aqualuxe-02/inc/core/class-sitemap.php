<?php
/**
 * AquaLuxe XML Sitemap
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
 * Sitemap Class
 *
 * Handles XML sitemap generation for the theme.
 *
 * @since 1.2.0
 */
class Sitemap {

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
		// Skip if XML sitemap is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_xml_sitemap', true ) ) {
			return;
		}
		
		// Skip if Yoast SEO, All in One SEO, Rank Math, or SEOPress is active.
		if ( $this->is_seo_plugin_active() ) {
			return;
		}
		
		// Add sitemap rewrite rules.
		add_action( 'init', array( $this, 'add_sitemap_rewrite_rules' ) );
		
		// Add query vars.
		add_filter( 'query_vars', array( $this, 'add_sitemap_query_vars' ) );
		
		// Generate sitemap.
		add_action( 'template_redirect', array( $this, 'generate_sitemap' ) );
		
		// Add sitemap URL to robots.txt.
		add_filter( 'robots_txt', array( $this, 'add_sitemap_to_robots_txt' ), 10, 2 );
		
		// Ping search engines when sitemap is updated.
		add_action( 'publish_post', array( $this, 'ping_search_engines' ) );
		add_action( 'publish_page', array( $this, 'ping_search_engines' ) );
		add_action( 'created_term', array( $this, 'ping_search_engines' ) );
	}

	/**
	 * Add sitemap rewrite rules.
	 *
	 * @return void
	 */
	public function add_sitemap_rewrite_rules() {
		add_rewrite_rule( '^sitemap\.xml$', 'index.php?aqualuxe_sitemap=index', 'top' );
		add_rewrite_rule( '^sitemap-([^/]+)\.xml$', 'index.php?aqualuxe_sitemap=$matches[1]', 'top' );
	}

	/**
	 * Add sitemap query vars.
	 *
	 * @param array $vars Query vars.
	 * @return array
	 */
	public function add_sitemap_query_vars( $vars ) {
		$vars[] = 'aqualuxe_sitemap';
		return $vars;
	}

	/**
	 * Generate sitemap.
	 *
	 * @return void
	 */
	public function generate_sitemap() {
		$sitemap_type = get_query_var( 'aqualuxe_sitemap' );
		
		if ( empty( $sitemap_type ) ) {
			return;
		}
		
		// Set content type.
		header( 'Content-Type: application/xml; charset=UTF-8' );
		
		// Disable caching.
		header( 'Cache-Control: no-cache, no-store, must-revalidate' );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );
		
		// Generate sitemap based on type.
		if ( 'index' === $sitemap_type ) {
			$this->generate_sitemap_index();
		} elseif ( 'post' === $sitemap_type || 'page' === $sitemap_type || post_type_exists( $sitemap_type ) ) {
			$this->generate_post_type_sitemap( $sitemap_type );
		} elseif ( 'category' === $sitemap_type || 'post_tag' === $sitemap_type || taxonomy_exists( $sitemap_type ) ) {
			$this->generate_taxonomy_sitemap( $sitemap_type );
		} else {
			// Invalid sitemap type.
			status_header( 404 );
			echo '<?xml version="1.0" encoding="UTF-8"?>';
			echo '<error>Invalid sitemap type</error>';
		}
		
		exit;
	}

	/**
	 * Generate sitemap index.
	 *
	 * @return void
	 */
	private function generate_sitemap_index() {
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		
		// Add post types.
		$post_types = $this->get_public_post_types();
		foreach ( $post_types as $post_type ) {
			$last_modified = $this->get_last_modified_date( $post_type );
			
			echo '<sitemap>';
			echo '<loc>' . esc_url( home_url( '/sitemap-' . $post_type . '.xml' ) ) . '</loc>';
			echo '<lastmod>' . esc_xml( $last_modified ) . '</lastmod>';
			echo '</sitemap>';
		}
		
		// Add taxonomies.
		$taxonomies = $this->get_public_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {
			$last_modified = $this->get_last_modified_term_date( $taxonomy );
			
			echo '<sitemap>';
			echo '<loc>' . esc_url( home_url( '/sitemap-' . $taxonomy . '.xml' ) ) . '</loc>';
			echo '<lastmod>' . esc_xml( $last_modified ) . '</lastmod>';
			echo '</sitemap>';
		}
		
		echo '</sitemapindex>';
	}

	/**
	 * Generate post type sitemap.
	 *
	 * @param string $post_type Post type.
	 * @return void
	 */
	private function generate_post_type_sitemap( $post_type ) {
		// Get posts.
		$posts = $this->get_posts_for_sitemap( $post_type );
		
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
		
		foreach ( $posts as $post ) {
			// Skip if post is set to noindex.
			if ( $this->is_post_excluded( $post->ID ) ) {
				continue;
			}
			
			echo '<url>';
			echo '<loc>' . esc_url( get_permalink( $post ) ) . '</loc>';
			echo '<lastmod>' . esc_xml( get_the_modified_date( 'c', $post ) ) . '</lastmod>';
			echo '<changefreq>' . esc_xml( $this->get_post_change_frequency( $post ) ) . '</changefreq>';
			echo '<priority>' . esc_xml( $this->get_post_priority( $post ) ) . '</priority>';
			
			// Add featured image.
			if ( has_post_thumbnail( $post ) ) {
				$image_id = get_post_thumbnail_id( $post );
				$image_url = wp_get_attachment_image_url( $image_id, 'full' );
				
				echo '<image:image>';
				echo '<image:loc>' . esc_url( $image_url ) . '</image:loc>';
				echo '<image:title>' . esc_xml( get_the_title( $post ) ) . '</image:title>';
				
				// Add alt text if available.
				$alt_text = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
				if ( $alt_text ) {
					echo '<image:caption>' . esc_xml( $alt_text ) . '</image:caption>';
				}
				
				echo '</image:image>';
			}
			
			// Add additional images from content.
			$content_images = $this->get_content_images( $post->post_content );
			foreach ( $content_images as $image ) {
				echo '<image:image>';
				echo '<image:loc>' . esc_url( $image['url'] ) . '</image:loc>';
				
				if ( ! empty( $image['title'] ) ) {
					echo '<image:title>' . esc_xml( $image['title'] ) . '</image:title>';
				}
				
				if ( ! empty( $image['alt'] ) ) {
					echo '<image:caption>' . esc_xml( $image['alt'] ) . '</image:caption>';
				}
				
				echo '</image:image>';
			}
			
			echo '</url>';
		}
		
		echo '</urlset>';
	}

	/**
	 * Generate taxonomy sitemap.
	 *
	 * @param string $taxonomy Taxonomy.
	 * @return void
	 */
	private function generate_taxonomy_sitemap( $taxonomy ) {
		// Get terms.
		$terms = $this->get_terms_for_sitemap( $taxonomy );
		
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		
		foreach ( $terms as $term ) {
			// Skip if term is set to noindex.
			if ( $this->is_term_excluded( $term->term_id, $taxonomy ) ) {
				continue;
			}
			
			echo '<url>';
			echo '<loc>' . esc_url( get_term_link( $term ) ) . '</loc>';
			echo '<changefreq>weekly</changefreq>';
			echo '<priority>0.6</priority>';
			echo '</url>';
		}
		
		echo '</urlset>';
	}

	/**
	 * Add sitemap URL to robots.txt.
	 *
	 * @param string $output Robots.txt output.
	 * @param bool   $public Whether the site is public.
	 * @return string
	 */
	public function add_sitemap_to_robots_txt( $output, $public ) {
		if ( $public ) {
			$output .= "\n# XML Sitemap\n";
			$output .= 'Sitemap: ' . esc_url( home_url( '/sitemap.xml' ) ) . "\n";
		}
		
		return $output;
	}

	/**
	 * Ping search engines when sitemap is updated.
	 *
	 * @return void
	 */
	public function ping_search_engines() {
		// Skip if site is not public.
		if ( '0' === get_option( 'blog_public' ) ) {
			return;
		}
		
		// Skip if pinging is disabled.
		if ( ! get_theme_mod( 'aqualuxe_ping_search_engines', true ) ) {
			return;
		}
		
		// Get sitemap URL.
		$sitemap_url = home_url( '/sitemap.xml' );
		
		// Ping Google.
		wp_remote_get( 'https://www.google.com/ping?sitemap=' . urlencode( $sitemap_url ), array( 'blocking' => false ) );
		
		// Ping Bing.
		wp_remote_get( 'https://www.bing.com/ping?sitemap=' . urlencode( $sitemap_url ), array( 'blocking' => false ) );
	}

	/**
	 * Get public post types.
	 *
	 * @return array
	 */
	private function get_public_post_types() {
		$post_types = get_post_types( array( 'public' => true ) );
		
		// Remove attachment post type.
		if ( isset( $post_types['attachment'] ) ) {
			unset( $post_types['attachment'] );
		}
		
		return apply_filters( 'aqualuxe_sitemap_post_types', $post_types );
	}

	/**
	 * Get public taxonomies.
	 *
	 * @return array
	 */
	private function get_public_taxonomies() {
		$taxonomies = get_taxonomies( array( 'public' => true ) );
		
		// Remove post_format taxonomy.
		if ( isset( $taxonomies['post_format'] ) ) {
			unset( $taxonomies['post_format'] );
		}
		
		return apply_filters( 'aqualuxe_sitemap_taxonomies', $taxonomies );
	}

	/**
	 * Get posts for sitemap.
	 *
	 * @param string $post_type Post type.
	 * @return array
	 */
	private function get_posts_for_sitemap( $post_type ) {
		$args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => 1000,
			'orderby'        => 'modified',
			'order'          => 'DESC',
		);
		
		return get_posts( $args );
	}

	/**
	 * Get terms for sitemap.
	 *
	 * @param string $taxonomy Taxonomy.
	 * @return array
	 */
	private function get_terms_for_sitemap( $taxonomy ) {
		$args = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
			'number'     => 1000,
		);
		
		return get_terms( $args );
	}

	/**
	 * Get last modified date for post type.
	 *
	 * @param string $post_type Post type.
	 * @return string
	 */
	private function get_last_modified_date( $post_type ) {
		$args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'orderby'        => 'modified',
			'order'          => 'DESC',
		);
		
		$posts = get_posts( $args );
		
		if ( ! empty( $posts ) ) {
			return get_the_modified_date( 'c', $posts[0] );
		}
		
		return date( 'c', strtotime( 'now' ) );
	}

	/**
	 * Get last modified date for taxonomy.
	 *
	 * @param string $taxonomy Taxonomy.
	 * @return string
	 */
	private function get_last_modified_term_date( $taxonomy ) {
		$terms = get_terms( array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => true,
			'number'     => 1,
		) );
		
		if ( ! empty( $terms ) ) {
			$term_id = $terms[0]->term_id;
			
			$args = array(
				'post_type'      => 'any',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
				'orderby'        => 'modified',
				'order'          => 'DESC',
				'tax_query'      => array(
					array(
						'taxonomy' => $taxonomy,
						'terms'    => $term_id,
					),
				),
			);
			
			$posts = get_posts( $args );
			
			if ( ! empty( $posts ) ) {
				return get_the_modified_date( 'c', $posts[0] );
			}
		}
		
		return date( 'c', strtotime( 'now' ) );
	}

	/**
	 * Get post change frequency.
	 *
	 * @param WP_Post $post Post object.
	 * @return string
	 */
	private function get_post_change_frequency( $post ) {
		$post_type = $post->post_type;
		
		if ( 'page' === $post_type ) {
			return 'monthly';
		}
		
		$published_date = strtotime( $post->post_date );
		$modified_date  = strtotime( $post->post_modified );
		$current_time   = current_time( 'timestamp' );
		
		// If post was modified in the last month.
		if ( $modified_date > strtotime( '-1 month', $current_time ) ) {
			return 'daily';
		}
		
		// If post was modified in the last 3 months.
		if ( $modified_date > strtotime( '-3 months', $current_time ) ) {
			return 'weekly';
		}
		
		// If post was modified in the last year.
		if ( $modified_date > strtotime( '-1 year', $current_time ) ) {
			return 'monthly';
		}
		
		return 'yearly';
	}

	/**
	 * Get post priority.
	 *
	 * @param WP_Post $post Post object.
	 * @return string
	 */
	private function get_post_priority( $post ) {
		$post_type = $post->post_type;
		
		if ( 'page' === $post_type ) {
			// Home page gets highest priority.
			if ( get_option( 'page_on_front' ) === $post->ID ) {
				return '1.0';
			}
			
			return '0.8';
		}
		
		// Calculate priority based on comment count and age.
		$comment_count = get_comment_count( $post->ID );
		$comment_count = $comment_count['approved'];
		
		$published_date = strtotime( $post->post_date );
		$current_time   = current_time( 'timestamp' );
		$age_in_days    = floor( ( $current_time - $published_date ) / DAY_IN_SECONDS );
		
		// Newer posts get higher priority.
		if ( $age_in_days < 30 ) {
			$priority = 0.8;
		} elseif ( $age_in_days < 90 ) {
			$priority = 0.6;
		} elseif ( $age_in_days < 365 ) {
			$priority = 0.4;
		} else {
			$priority = 0.2;
		}
		
		// Posts with more comments get higher priority.
		if ( $comment_count > 10 ) {
			$priority += 0.1;
		}
		
		// Cap priority at 0.9 for posts.
		$priority = min( 0.9, $priority );
		
		return number_format( $priority, 1 );
	}

	/**
	 * Check if post is excluded from sitemap.
	 *
	 * @param int $post_id Post ID.
	 * @return bool
	 */
	private function is_post_excluded( $post_id ) {
		// Check if post is set to noindex.
		$noindex = get_post_meta( $post_id, '_aqualuxe_seo_noindex', true );
		if ( 'on' === $noindex ) {
			return true;
		}
		
		// Check if post is in excluded category.
		$excluded_categories = get_theme_mod( 'aqualuxe_sitemap_exclude_categories', array() );
		if ( ! empty( $excluded_categories ) ) {
			$categories = get_the_category( $post_id );
			foreach ( $categories as $category ) {
				if ( in_array( $category->term_id, $excluded_categories, true ) ) {
					return true;
				}
			}
		}
		
		return false;
	}

	/**
	 * Check if term is excluded from sitemap.
	 *
	 * @param int    $term_id  Term ID.
	 * @param string $taxonomy Taxonomy.
	 * @return bool
	 */
	private function is_term_excluded( $term_id, $taxonomy ) {
		// Check if term is set to noindex.
		$noindex = get_term_meta( $term_id, '_aqualuxe_seo_noindex', true );
		if ( 'on' === $noindex ) {
			return true;
		}
		
		// Check if term is in excluded categories.
		if ( 'category' === $taxonomy ) {
			$excluded_categories = get_theme_mod( 'aqualuxe_sitemap_exclude_categories', array() );
			if ( in_array( $term_id, $excluded_categories, true ) ) {
				return true;
			}
		}
		
		return false;
	}

	/**
	 * Get images from content.
	 *
	 * @param string $content Post content.
	 * @return array
	 */
	private function get_content_images( $content ) {
		$images = array();
		
		// Check if content has images.
		if ( preg_match_all( '/<img [^>]+>/', $content, $matches ) ) {
			foreach ( $matches[0] as $img ) {
				// Get image URL.
				if ( preg_match( '/src=[\'"]([^\'"]+)[\'"]/', $img, $src_match ) ) {
					$image = array(
						'url' => $src_match[1],
					);
					
					// Get image title.
					if ( preg_match( '/title=[\'"]([^\'"]+)[\'"]/', $img, $title_match ) ) {
						$image['title'] = $title_match[1];
					}
					
					// Get image alt text.
					if ( preg_match( '/alt=[\'"]([^\'"]+)[\'"]/', $img, $alt_match ) ) {
						$image['alt'] = $alt_match[1];
					}
					
					$images[] = $image;
				}
			}
		}
		
		return $images;
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