<?php
/**
 * AquaLuxe Theme - Caching Mechanisms
 *
 * This file contains functions to enhance caching for better performance.
 *
 * @package AquaLuxe
 * @subpackage Performance
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class AquaLuxe_Caching
 */
class AquaLuxe_Caching {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Initialize caching mechanisms.
		$this->init();
	}

	/**
	 * Initialize caching mechanisms
	 */
	public function init() {
		// Set browser caching headers.
		add_action( 'wp_headers', array( $this, 'set_cache_headers' ) );

		// Add browser caching to .htaccess if using Apache.
		add_action( 'admin_init', array( $this, 'add_htaccess_caching' ) );

		// Implement fragment caching for expensive operations.
		add_action( 'init', array( $this, 'setup_fragment_caching' ) );

		// Optimize transients usage.
		add_action( 'init', array( $this, 'setup_transients' ) );

		// Add support for page caching plugins.
		add_action( 'init', array( $this, 'setup_page_cache_support' ) );

		// Implement object caching for database queries.
		add_action( 'init', array( $this, 'setup_object_caching' ) );
	}

	/**
	 * Set cache headers for browser caching
	 *
	 * @param array $headers Current headers.
	 * @return array Modified headers.
	 */
	public function set_cache_headers( $headers ) {
		// Don't set cache headers for logged in users.
		if ( is_user_logged_in() ) {
			return $headers;
		}

		// Don't cache admin, login, or cart/checkout pages.
		if ( is_admin() || is_user_logged_in() || is_cart() || is_checkout() || is_account_page() ) {
			$headers['Cache-Control'] = 'no-store, no-cache, must-revalidate, max-age=0';
			$headers['Pragma'] = 'no-cache';
			$headers['Expires'] = 'Wed, 11 Jan 1984 05:00:00 GMT';
			return $headers;
		}

		// Set cache headers based on content type.
		$uri = $_SERVER['REQUEST_URI'];
		$file_ext = pathinfo( $uri, PATHINFO_EXTENSION );

		// Cache static assets longer.
		if ( in_array( $file_ext, array( 'css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'ico', 'svg', 'woff', 'woff2', 'ttf', 'eot' ), true ) ) {
			$headers['Cache-Control'] = 'public, max-age=31536000'; // 1 year.
			$headers['Expires'] = gmdate( 'D, d M Y H:i:s', time() + 31536000 ) . ' GMT';
		} else {
			// Cache HTML pages for a shorter time.
			$headers['Cache-Control'] = 'public, max-age=3600'; // 1 hour.
			$headers['Expires'] = gmdate( 'D, d M Y H:i:s', time() + 3600 ) . ' GMT';
		}

		return $headers;
	}

	/**
	 * Add browser caching rules to .htaccess
	 */
	public function add_htaccess_caching() {
		// Only run if using Apache.
		if ( ! function_exists( 'get_home_path' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		// Check if .htaccess is writable.
		$htaccess_file = get_home_path() . '.htaccess';
		if ( ! is_writable( $htaccess_file ) ) {
			return;
		}

		// Get current .htaccess content.
		$htaccess_content = file_get_contents( $htaccess_file );

		// Check if caching rules already exist.
		if ( strpos( $htaccess_content, '# BEGIN AquaLuxe Browser Caching' ) !== false ) {
			return;
		}

		// Caching rules.
		$caching_rules = "
# BEGIN AquaLuxe Browser Caching
<IfModule mod_expires.c>
	ExpiresActive On
	ExpiresByType image/jpg &quot;access plus 1 year&quot;
	ExpiresByType image/jpeg &quot;access plus 1 year&quot;
	ExpiresByType image/gif &quot;access plus 1 year&quot;
	ExpiresByType image/png &quot;access plus 1 year&quot;
	ExpiresByType image/svg+xml &quot;access plus 1 year&quot;
	ExpiresByType image/webp &quot;access plus 1 year&quot;
	ExpiresByType image/x-icon &quot;access plus 1 year&quot;
	ExpiresByType video/mp4 &quot;access plus 1 year&quot;
	ExpiresByType video/webm &quot;access plus 1 year&quot;
	ExpiresByType text/css &quot;access plus 1 year&quot;
	ExpiresByType text/javascript &quot;access plus 1 year&quot;
	ExpiresByType application/javascript &quot;access plus 1 year&quot;
	ExpiresByType application/x-javascript &quot;access plus 1 year&quot;
	ExpiresByType application/x-font-ttf &quot;access plus 1 year&quot;
	ExpiresByType application/x-font-woff &quot;access plus 1 year&quot;
	ExpiresByType application/font-woff &quot;access plus 1 year&quot;
	ExpiresByType application/font-woff2 &quot;access plus 1 year&quot;
	ExpiresByType font/woff &quot;access plus 1 year&quot;
	ExpiresByType font/woff2 &quot;access plus 1 year&quot;
	ExpiresByType application/vnd.ms-fontobject &quot;access plus 1 year&quot;
</IfModule>

<IfModule mod_deflate.c>
	AddOutputFilterByType DEFLATE text/plain
	AddOutputFilterByType DEFLATE text/html
	AddOutputFilterByType DEFLATE text/xml
	AddOutputFilterByType DEFLATE text/css
	AddOutputFilterByType DEFLATE text/javascript
	AddOutputFilterByType DEFLATE application/xml
	AddOutputFilterByType DEFLATE application/xhtml+xml
	AddOutputFilterByType DEFLATE application/rss+xml
	AddOutputFilterByType DEFLATE application/javascript
	AddOutputFilterByType DEFLATE application/x-javascript
	AddOutputFilterByType DEFLATE application/x-font-ttf
	AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
	AddOutputFilterByType DEFLATE font/opentype font/ttf font/woff font/woff2
	AddOutputFilterByType DEFLATE image/svg+xml
</IfModule>

<IfModule mod_headers.c>
	<FilesMatch &quot;\.(ico|jpg|jpeg|png|gif|svg|webp|css|js|woff|woff2|ttf|eot)$&quot;>
		Header set Cache-Control &quot;max-age=31536000, public&quot;
	</FilesMatch>
</IfModule>
# END AquaLuxe Browser Caching
";

		// Add caching rules to .htaccess.
		$htaccess_content = $caching_rules . $htaccess_content;
		file_put_contents( $htaccess_file, $htaccess_content );
	}

	/**
	 * Setup fragment caching for expensive operations
	 */
	public function setup_fragment_caching() {
		// Add fragment caching for expensive template parts.
		add_filter( 'aqualuxe_fragment_cache_enabled', '__return_true' );
	}

	/**
	 * Get cached fragment or generate it
	 *
	 * @param string   $key Cache key.
	 * @param callable $callback Function to generate content.
	 * @param int      $expiration Cache expiration in seconds.
	 * @return string Cached or fresh content.
	 */
	public static function get_fragment_cache( $key, $callback, $expiration = 3600 ) {
		// Skip caching for logged in users.
		if ( is_user_logged_in() ) {
			return call_user_func( $callback );
		}

		// Check if fragment caching is enabled.
		if ( ! apply_filters( 'aqualuxe_fragment_cache_enabled', false ) ) {
			return call_user_func( $callback );
		}

		// Try to get cached fragment.
		$cache_key = 'aqualuxe_fragment_' . md5( $key );
		$cached = get_transient( $cache_key );

		if ( false !== $cached ) {
			return $cached;
		}

		// Generate fresh content.
		$content = call_user_func( $callback );

		// Cache the content.
		set_transient( $cache_key, $content, $expiration );

		return $content;
	}

	/**
	 * Setup transients for better performance
	 */
	public function setup_transients() {
		// Add hooks to clear specific transients when content changes.
		add_action( 'save_post', array( $this, 'clear_related_transients' ), 10, 3 );
		add_action( 'woocommerce_update_product', array( $this, 'clear_product_transients' ) );
		add_action( 'woocommerce_new_product', array( $this, 'clear_product_transients' ) );
		add_action( 'woocommerce_delete_product', array( $this, 'clear_product_transients' ) );
	}

	/**
	 * Clear transients related to a post
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post Post object.
	 * @param bool    $update Whether this is an update.
	 */
	public function clear_related_transients( $post_id, $post, $update ) {
		// Skip auto-saves and revisions.
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Clear homepage fragments if this is a featured post.
		if ( has_term( 'featured', 'post_tag', $post_id ) ) {
			delete_transient( 'aqualuxe_fragment_' . md5( 'home_featured_posts' ) );
		}

		// Clear category fragments if this is a post.
		if ( 'post' === $post->post_type ) {
			$categories = get_the_category( $post_id );
			foreach ( $categories as $category ) {
				delete_transient( 'aqualuxe_fragment_' . md5( 'category_posts_' . $category->term_id ) );
			}
		}

		// Clear related posts fragments.
		delete_transient( 'aqualuxe_fragment_' . md5( 'related_posts_' . $post_id ) );
	}

	/**
	 * Clear product-related transients
	 *
	 * @param int $product_id Product ID.
	 */
	public function clear_product_transients( $product_id ) {
		// Clear product category fragments.
		$terms = get_the_terms( $product_id, 'product_cat' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				delete_transient( 'aqualuxe_fragment_' . md5( 'product_category_' . $term->term_id ) );
			}
		}

		// Clear featured products fragment.
		delete_transient( 'aqualuxe_fragment_' . md5( 'featured_products' ) );

		// Clear sale products fragment.
		delete_transient( 'aqualuxe_fragment_' . md5( 'sale_products' ) );

		// Clear related products fragment.
		delete_transient( 'aqualuxe_fragment_' . md5( 'related_products_' . $product_id ) );
	}

	/**
	 * Setup page cache support
	 */
	public function setup_page_cache_support() {
		// Add support for WP Super Cache.
		if ( function_exists( 'wp_cache_clear_cache' ) ) {
			add_action( 'save_post', 'wp_cache_clear_cache' );
		}

		// Add support for W3 Total Cache.
		if ( function_exists( 'w3tc_flush_all' ) ) {
			add_action( 'save_post', 'w3tc_flush_all' );
		}

		// Add support for WP Rocket.
		if ( function_exists( 'rocket_clean_domain' ) ) {
			add_action( 'save_post', 'rocket_clean_domain' );
		}

		// Clear cache when updating theme options.
		add_action( 'customize_save_after', array( $this, 'clear_all_caches' ) );
		add_action( 'after_switch_theme', array( $this, 'clear_all_caches' ) );
	}

	/**
	 * Clear all caches
	 */
	public function clear_all_caches() {
		// Clear WordPress object cache.
		wp_cache_flush();

		// Clear all transients.
		$this->clear_all_transients();

		// Clear page caches from popular plugins.
		if ( function_exists( 'wp_cache_clear_cache' ) ) {
			wp_cache_clear_cache();
		}

		if ( function_exists( 'w3tc_flush_all' ) ) {
			w3tc_flush_all();
		}

		if ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
		}
	}

	/**
	 * Clear all transients
	 */
	private function clear_all_transients() {
		global $wpdb;

		// Delete all transients from options table.
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '%_transient_aqualuxe_%'" );
		$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '%_transient_timeout_aqualuxe_%'" );
	}

	/**
	 * Setup object caching for database queries
	 */
	public function setup_object_caching() {
		// Add object caching for expensive queries.
		add_action( 'pre_get_posts', array( $this, 'cache_main_queries' ) );
	}

	/**
	 * Cache main queries
	 *
	 * @param WP_Query $query The WordPress query object.
	 */
	public function cache_main_queries( $query ) {
		// Only cache main queries.
		if ( ! $query->is_main_query() ) {
			return;
		}

		// Don't cache admin queries.
		if ( is_admin() ) {
			return;
		}

		// Don't cache for logged in users.
		if ( is_user_logged_in() ) {
			return;
		}

		// Set query caching.
		$query->set( 'cache_results', true );
		$query->set( 'update_post_meta_cache', true );
		$query->set( 'update_post_term_cache', true );
	}
}

// Initialize the caching class.
new AquaLuxe_Caching();

/**
 * Helper function to get cached fragment
 *
 * @param string   $key Cache key.
 * @param callable $callback Function to generate content.
 * @param int      $expiration Cache expiration in seconds.
 * @return string Cached or fresh content.
 */
function aqualuxe_get_fragment_cache( $key, $callback, $expiration = 3600 ) {
	return AquaLuxe_Caching::get_fragment_cache( $key, $callback, $expiration );
}