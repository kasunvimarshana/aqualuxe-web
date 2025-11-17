<?php
/**
 * AquaLuxe Theme - Database Optimization
 *
 * This file contains functions to optimize database queries for better performance.
 *
 * @package AquaLuxe
 * @subpackage Performance
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class AquaLuxe_Database_Optimization
 */
class AquaLuxe_Database_Optimization {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Initialize database optimizations.
		$this->init();
	}

	/**
	 * Initialize database optimizations
	 */
	public function init() {
		// Optimize WordPress core queries.
		add_action( 'pre_get_posts', array( $this, 'optimize_queries' ), 5 );
		
		// Optimize WooCommerce queries.
		add_filter( 'woocommerce_product_query_tax_query', array( $this, 'optimize_product_tax_query' ), 10, 2 );
		
		// Disable post revisions to reduce database size.
		$this->limit_post_revisions();
		
		// Optimize database tables.
		add_action( 'wp_scheduled_auto_draft_delete', array( $this, 'optimize_database_tables' ) );
		
		// Schedule database optimization.
		$this->schedule_optimization();
		
		// Disable unnecessary queries.
		$this->disable_unnecessary_queries();
	}

	/**
	 * Optimize WordPress queries
	 *
	 * @param WP_Query $query The WordPress query object.
	 */
	public function optimize_queries( $query ) {
		// Only optimize frontend queries.
		if ( is_admin() ) {
			return;
		}

		// Optimize main query.
		if ( $query->is_main_query() ) {
			// Disable unnecessary meta queries.
			$query->set( 'update_post_meta_cache', true );
			$query->set( 'update_post_term_cache', true );
			
			// Optimize homepage query.
			if ( $query->is_home() || $query->is_front_page() ) {
				// Limit fields for homepage.
				$query->set( 'fields', 'ids' );
				add_filter( 'posts_fields', array( $this, 'optimize_home_fields' ), 10, 2 );
				
				// Only get necessary post meta.
				add_filter( 'update_post_metadata_cache', array( $this, 'optimize_home_meta' ), 10, 2 );
			}
			
			// Optimize archive queries.
			if ( $query->is_archive() ) {
				// Limit fields for archives.
				add_filter( 'posts_fields', array( $this, 'optimize_archive_fields' ), 10, 2 );
			}
			
			// Optimize search queries.
			if ( $query->is_search() ) {
				// Exclude post types from search.
				$post_types = get_post_types( array( 'public' => true ) );
				unset( $post_types['attachment'] );
				unset( $post_types['revision'] );
				unset( $post_types['nav_menu_item'] );
				$query->set( 'post_type', array_values( $post_types ) );
				
				// Optimize search query.
				add_filter( 'posts_search', array( $this, 'optimize_search_query' ), 10, 2 );
			}
		}
	}

	/**
	 * Optimize homepage query fields
	 *
	 * @param string   $fields The fields clause of the query.
	 * @param WP_Query $query The WordPress query object.
	 * @return string Modified fields clause.
	 */
	public function optimize_home_fields( $fields, $query ) {
		global $wpdb;
		
		// Only optimize main query.
		if ( ! $query->is_main_query() || ! ( $query->is_home() || $query->is_front_page() ) ) {
			return $fields;
		}
		
		// Only select necessary fields.
		return "{$wpdb->posts}.ID, {$wpdb->posts}.post_title, {$wpdb->posts}.post_excerpt, {$wpdb->posts}.post_date, {$wpdb->posts}.post_author, {$wpdb->posts}.post_type, {$wpdb->posts}.post_name";
	}

	/**
	 * Optimize homepage meta queries
	 *
	 * @param bool  $check Whether to update the meta cache.
	 * @param array $post_ids Array of post IDs.
	 * @return bool|array Whether to update the meta cache or the filtered post IDs.
	 */
	public function optimize_home_meta( $check, $post_ids ) {
		// Only get meta for specific keys.
		$meta_keys = array(
			'_thumbnail_id',
			'_featured_image',
			'_post_views',
		);
		
		// Get only necessary meta.
		global $wpdb;
		$meta = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE post_id IN (" . implode( ',', array_map( 'intval', $post_ids ) ) . ") AND meta_key IN (" . implode( ',', array_fill( 0, count( $meta_keys ), '%s' ) ) . ")",
				$meta_keys
			),
			ARRAY_A
		);
		
		// Format meta.
		$meta_cache = array();
		foreach ( $meta as $row ) {
			$meta_cache[ $row['post_id'] ][ $row['meta_key'] ][] = $row['meta_value'];
		}
		
		// Update cache.
		foreach ( $post_ids as $post_id ) {
			if ( isset( $meta_cache[ $post_id ] ) ) {
				wp_cache_set( $post_id, $meta_cache[ $post_id ], 'post_meta' );
			}
		}
		
		// Skip default update.
		return false;
	}

	/**
	 * Optimize archive query fields
	 *
	 * @param string   $fields The fields clause of the query.
	 * @param WP_Query $query The WordPress query object.
	 * @return string Modified fields clause.
	 */
	public function optimize_archive_fields( $fields, $query ) {
		global $wpdb;
		
		// Only optimize main query.
		if ( ! $query->is_main_query() || ! $query->is_archive() ) {
			return $fields;
		}
		
		// Only select necessary fields.
		return "{$wpdb->posts}.ID, {$wpdb->posts}.post_title, {$wpdb->posts}.post_excerpt, {$wpdb->posts}.post_date, {$wpdb->posts}.post_author, {$wpdb->posts}.post_type, {$wpdb->posts}.post_name";
	}

	/**
	 * Optimize search query
	 *
	 * @param string   $search The search clause of the query.
	 * @param WP_Query $query The WordPress query object.
	 * @return string Modified search clause.
	 */
	public function optimize_search_query( $search, $query ) {
		global $wpdb;
		
		// Only optimize main search query.
		if ( ! $query->is_main_query() || ! $query->is_search() ) {
			return $search;
		}
		
		$search_term = $query->get( 's' );
		if ( empty( $search_term ) ) {
			return $search;
		}
		
		// Create optimized search query.
		$search = '';
		$search_terms = explode( ' ', $search_term );
		
		foreach ( $search_terms as $term ) {
			$term = esc_sql( $wpdb->esc_like( $term ) );
			$search .= " AND (
				{$wpdb->posts}.post_title LIKE '%{$term}%' 
				OR {$wpdb->posts}.post_excerpt LIKE '%{$term}%' 
				OR {$wpdb->posts}.post_content LIKE '%{$term}%'
			)";
		}
		
		return $search;
	}

	/**
	 * Optimize WooCommerce product tax query
	 *
	 * @param array    $tax_query Tax query array.
	 * @param WP_Query $query The WordPress query object.
	 * @return array Modified tax query.
	 */
	public function optimize_product_tax_query( $tax_query, $query ) {
		// Add index hint for taxonomy queries.
		add_filter( 'posts_clauses', array( $this, 'add_tax_query_index_hint' ), 10, 2 );
		
		return $tax_query;
	}

	/**
	 * Add index hint for taxonomy queries
	 *
	 * @param array    $clauses Query clauses.
	 * @param WP_Query $query The WordPress query object.
	 * @return array Modified query clauses.
	 */
	public function add_tax_query_index_hint( $clauses, $query ) {
		global $wpdb;
		
		// Only add hint for product queries.
		if ( 'product' !== $query->get( 'post_type' ) ) {
			return $clauses;
		}
		
		// Add index hint.
		$clauses['join'] = preg_replace(
			"/JOIN {$wpdb->term_relationships} ON/",
			"JOIN {$wpdb->term_relationships} USE INDEX (PRIMARY) ON",
			$clauses['join']
		);
		
		return $clauses;
	}

	/**
	 * Limit post revisions
	 */
	public function limit_post_revisions() {
		// Limit post revisions to 3.
		if ( ! defined( 'WP_POST_REVISIONS' ) ) {
			define( 'WP_POST_REVISIONS', 3 );
		}
		
		// Auto-cleanup old revisions.
		add_action( 'wp_scheduled_delete', array( $this, 'delete_old_revisions' ) );
	}

	/**
	 * Delete old revisions
	 */
	public function delete_old_revisions() {
		global $wpdb;
		
		// Delete revisions older than 30 days.
		$days = 30;
		$date = date( 'Y-m-d', strtotime( "-{$days} days" ) );
		
		$revisions = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT ID FROM $wpdb->posts WHERE post_type = 'revision' AND post_date < %s",
				$date
			)
		);
		
		if ( $revisions ) {
			foreach ( $revisions as $revision_id ) {
				wp_delete_post_revision( $revision_id );
			}
		}
	}

	/**
	 * Optimize database tables
	 */
	public function optimize_database_tables() {
		global $wpdb;
		
		// Get all tables.
		$tables = $wpdb->get_results( "SHOW TABLES", ARRAY_N );
		
		if ( $tables ) {
			foreach ( $tables as $table ) {
				$table_name = $table[0];
				
				// Only optimize WordPress tables.
				if ( strpos( $table_name, $wpdb->prefix ) === 0 ) {
					$wpdb->query( "OPTIMIZE TABLE {$table_name}" );
				}
			}
		}
	}

	/**
	 * Schedule database optimization
	 */
	public function schedule_optimization() {
		// Schedule weekly optimization.
		if ( ! wp_next_scheduled( 'aqualuxe_optimize_database' ) ) {
			wp_schedule_event( time(), 'weekly', 'aqualuxe_optimize_database' );
		}
		
		// Add optimization action.
		add_action( 'aqualuxe_optimize_database', array( $this, 'run_scheduled_optimization' ) );
	}

	/**
	 * Run scheduled optimization
	 */
	public function run_scheduled_optimization() {
		// Delete old revisions.
		$this->delete_old_revisions();
		
		// Optimize database tables.
		$this->optimize_database_tables();
		
		// Delete transients.
		$this->delete_expired_transients();
	}

	/**
	 * Delete expired transients
	 */
	public function delete_expired_transients() {
		global $wpdb;
		
		// Delete expired transients.
		$wpdb->query(
			$wpdb->prepare(
				"DELETE a, b FROM {$wpdb->options} a, {$wpdb->options} b
				WHERE a.option_name LIKE %s
				AND a.option_name NOT LIKE %s
				AND b.option_name = CONCAT('_transient_timeout_', SUBSTRING(a.option_name, 12))
				AND b.option_value < %d",
				$wpdb->esc_like( '_transient_' ) . '%',
				$wpdb->esc_like( '_transient_timeout_' ) . '%',
				time()
			)
		);
	}

	/**
	 * Disable unnecessary queries
	 */
	public function disable_unnecessary_queries() {
		// Disable emoji support.
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		add_filter( 'emoji_svg_url', '__return_false' );
		
		// Disable XML-RPC.
		add_filter( 'xmlrpc_enabled', '__return_false' );
		
		// Disable pingbacks.
		add_filter( 'wp_headers', array( $this, 'disable_pingbacks' ) );
		
		// Disable REST API for non-authenticated users if not needed.
		if ( apply_filters( 'aqualuxe_disable_rest_api', false ) ) {
			add_filter( 'rest_authentication_errors', array( $this, 'disable_rest_api' ) );
		}
		
		// Disable oEmbed discovery.
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	}

	/**
	 * Disable pingbacks
	 *
	 * @param array $headers HTTP headers.
	 * @return array Modified headers.
	 */
	public function disable_pingbacks( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}

	/**
	 * Disable REST API for non-authenticated users
	 *
	 * @param WP_Error|null|bool $result Error from authentication, null if authentication method wasn't used, or boolean if authentication succeeded.
	 * @return WP_Error|null|bool Authentication result or error.
	 */
	public function disable_rest_api( $result ) {
		if ( ! is_user_logged_in() ) {
			return new WP_Error(
				'rest_api_disabled',
				__( 'REST API is disabled for non-authenticated users.', 'aqualuxe' ),
				array( 'status' => rest_authorization_required_code() )
			);
		}
		
		return $result;
	}
}

// Initialize the database optimization class.
new AquaLuxe_Database_Optimization();