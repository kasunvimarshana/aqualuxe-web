<?php
/**
 * Performance Class
 *
 * Handles performance optimizations for the theme.
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Service;

/**
 * Performance class.
 */
class Performance extends Service {

	/**
	 * Register service features.
	 */
	public function register() {
		// Only run performance optimizations if enabled
		if ( ! get_theme_mod( 'aqualuxe_enable_performance_optimizations', true ) ) {
			return;
		}

		// Script optimizations
		add_filter( 'script_loader_tag', array( $this, 'add_async_defer_attributes' ), 10, 3 );
		
		// Resource hints
		add_action( 'wp_head', array( $this, 'add_preconnect_hints' ), 1 );
		add_action( 'wp_head', array( $this, 'add_dns_prefetch' ), 1 );
		add_filter( 'wp_resource_hints', array( $this, 'resource_hints' ), 10, 2 );
		
		// Image optimizations
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_image_loading_attributes' ), 10, 3 );
		add_filter( 'the_content', array( $this, 'add_content_image_loading_attributes' ) );
		
		// Emoji removal if enabled
		if ( get_theme_mod( 'aqualuxe_disable_emojis', false ) ) {
			add_action( 'init', array( $this, 'disable_emojis' ) );
		}
		
		// Disable jQuery migrate if enabled
		if ( get_theme_mod( 'aqualuxe_disable_jquery_migrate', false ) ) {
			add_action( 'wp_default_scripts', array( $this, 'remove_jquery_migrate' ) );
		}
		
		// Remove query strings from static resources if enabled
		if ( get_theme_mod( 'aqualuxe_remove_query_strings', false ) ) {
			add_filter( 'script_loader_src', array( $this, 'remove_query_strings' ), 15 );
			add_filter( 'style_loader_src', array( $this, 'remove_query_strings' ), 15 );
		}
		
		// Limit post revisions if enabled
		if ( get_theme_mod( 'aqualuxe_limit_post_revisions', false ) ) {
			add_filter( 'wp_revisions_to_keep', array( $this, 'limit_post_revisions' ), 10, 2 );
		}
		
		// Disable heartbeat API if enabled
		if ( get_theme_mod( 'aqualuxe_disable_heartbeat', false ) ) {
			add_action( 'init', array( $this, 'disable_heartbeat' ), 1 );
		}
		
		// Disable XML-RPC if enabled
		if ( get_theme_mod( 'aqualuxe_disable_xmlrpc', false ) ) {
			add_filter( 'xmlrpc_enabled', '__return_false' );
			add_filter( 'wp_headers', array( $this, 'remove_x_pingback' ) );
		}
		
		// Optimize database if enabled
		if ( get_theme_mod( 'aqualuxe_optimize_database', false ) ) {
			add_action( 'wp_scheduled_delete', array( $this, 'optimize_database' ) );
		}
		
		// Add browser caching headers if enabled
		if ( get_theme_mod( 'aqualuxe_enable_browser_caching', false ) ) {
			add_action( 'send_headers', array( $this, 'add_browser_caching_headers' ) );
		}
	}

	/**
	 * Add async/defer attributes to script tags.
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @param string $src    The script source.
	 * @return string Modified script tag.
	 */
	public function add_async_defer_attributes( $tag, $handle, $src ) {
		// Scripts that should load with async attribute
		$async_scripts = apply_filters(
			'aqualuxe_async_scripts',
			array(
				'aqualuxe-navigation',
				'aqualuxe-skip-link-focus-fix',
				'aqualuxe-dark-mode',
				'aqualuxe-lazy-loading',
			)
		);

		// Scripts that should load with defer attribute
		$defer_scripts = apply_filters(
			'aqualuxe_defer_scripts',
			array(
				'aqualuxe-custom',
				'aqualuxe-woocommerce',
			)
		);

		// Add async attribute
		if ( in_array( $handle, $async_scripts, true ) ) {
			$tag = str_replace( ' src', ' async src', $tag );
		}

		// Add defer attribute
		if ( in_array( $handle, $defer_scripts, true ) ) {
			$tag = str_replace( ' src', ' defer src', $tag );
		}

		return $tag;
	}

	/**
	 * Add preconnect resource hints.
	 */
	public function add_preconnect_hints() {
		$preconnect_urls = apply_filters(
			'aqualuxe_preconnect_urls',
			array(
				'https://fonts.googleapis.com',
				'https://fonts.gstatic.com',
				'https://ajax.googleapis.com',
			)
		);

		foreach ( $preconnect_urls as $url ) {
			echo '<link rel="preconnect" href="' . esc_url( $url ) . '" crossorigin>' . "\n";
		}
	}

	/**
	 * Add DNS prefetch resource hints.
	 */
	public function add_dns_prefetch() {
		$dns_prefetch_urls = apply_filters(
			'aqualuxe_dns_prefetch_urls',
			array(
				'//s.w.org',
				'//fonts.googleapis.com',
				'//fonts.gstatic.com',
				'//ajax.googleapis.com',
				'//cdnjs.cloudflare.com',
			)
		);

		foreach ( $dns_prefetch_urls as $url ) {
			echo '<link rel="dns-prefetch" href="' . esc_url( $url ) . '">' . "\n";
		}
	}

	/**
	 * Add resource hints to wp_head.
	 *
	 * @param array  $urls          URLs to print for resource hints.
	 * @param string $relation_type The relation type the URLs are printed for.
	 * @return array URLs to print for resource hints.
	 */
	public function resource_hints( $urls, $relation_type ) {
		if ( 'preconnect' === $relation_type ) {
			$preconnect_urls = apply_filters(
				'aqualuxe_preconnect_resource_hints',
				array(
					'https://fonts.googleapis.com',
					'https://fonts.gstatic.com',
				)
			);

			foreach ( $preconnect_urls as $url ) {
				$urls[] = array(
					'href' => $url,
					'crossorigin',
				);
			}
		}

		if ( 'dns-prefetch' === $relation_type ) {
			$dns_prefetch_urls = apply_filters(
				'aqualuxe_dns_prefetch_resource_hints',
				array(
					'//s.w.org',
					'//fonts.googleapis.com',
					'//fonts.gstatic.com',
				)
			);

			$urls = array_merge( $urls, $dns_prefetch_urls );
		}

		if ( 'preload' === $relation_type ) {
			$preload_urls = apply_filters(
				'aqualuxe_preload_resource_hints',
				array(
					array(
						'href' => get_template_directory_uri() . '/assets/fonts/your-font.woff2',
						'as' => 'font',
						'type' => 'font/woff2',
						'crossorigin' => 'anonymous',
					),
				)
			);

			foreach ( $preload_urls as $preload ) {
				$urls[] = $preload;
			}
		}

		return $urls;
	}

	/**
	 * Add loading="lazy" attribute to images.
	 *
	 * @param array   $attr       Attributes for the image markup.
	 * @param WP_Post $attachment Image attachment post.
	 * @param string  $size       Requested image size.
	 * @return array Modified attributes.
	 */
	public function add_image_loading_attributes( $attr, $attachment, $size ) {
		// Skip lazy loading for specific image sizes
		$skip_sizes = apply_filters(
			'aqualuxe_skip_lazy_loading_sizes',
			array(
				'thumbnail',
				'aqualuxe-thumbnail',
			)
		);

		if ( in_array( $size, $skip_sizes, true ) ) {
			return $attr;
		}

		// Add loading attribute
		if ( ! isset( $attr['loading'] ) ) {
			$attr['loading'] = 'lazy';
		}

		// Add decoding attribute
		if ( ! isset( $attr['decoding'] ) ) {
			$attr['decoding'] = 'async';
		}

		return $attr;
	}

	/**
	 * Add loading="lazy" attribute to content images.
	 *
	 * @param string $content The content.
	 * @return string Modified content.
	 */
	public function add_content_image_loading_attributes( $content ) {
		if ( is_admin() || empty( $content ) ) {
			return $content;
		}

		// Add loading="lazy" to img tags that don't already have it
		$content = preg_replace( '/<img((?!loading=([\'"])lazy\2)[^>]*)>/i', '<img loading="lazy" decoding="async" $1>', $content );

		return $content;
	}

	/**
	 * Disable WordPress emojis.
	 */
	public function disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

		// Remove TinyMCE emoji plugin
		add_filter( 'tiny_mce_plugins', array( $this, 'disable_emojis_tinymce' ) );
		
		// Remove emoji DNS prefetch
		add_filter( 'emoji_svg_url', '__return_false' );
	}

	/**
	 * Filter function used to remove the TinyMCE emoji plugin.
	 *
	 * @param array $plugins TinyMCE plugins.
	 * @return array Filtered TinyMCE plugins.
	 */
	public function disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		}

		return array();
	}

	/**
	 * Remove jQuery migrate.
	 *
	 * @param \WP_Scripts $scripts WP_Scripts object.
	 */
	public function remove_jquery_migrate( $scripts ) {
		if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
			$script = $scripts->registered['jquery'];
			
			if ( $script->deps ) {
				$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
			}
		}
	}

	/**
	 * Remove query strings from static resources.
	 *
	 * @param string $src The source URL.
	 * @return string Modified URL.
	 */
	public function remove_query_strings( $src ) {
		if ( strpos( $src, '?ver=' ) || strpos( $src, '&ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}
		
		return $src;
	}

	/**
	 * Limit post revisions.
	 *
	 * @param int     $num  Number of revisions to keep.
	 * @param WP_Post $post The post object.
	 * @return int Modified number of revisions.
	 */
	public function limit_post_revisions( $num, $post ) {
		$revision_limit = get_theme_mod( 'aqualuxe_revision_limit', 5 );
		return $revision_limit;
	}

	/**
	 * Disable WordPress Heartbeat API.
	 */
	public function disable_heartbeat() {
		wp_deregister_script( 'heartbeat' );
	}

	/**
	 * Remove X-Pingback header.
	 *
	 * @param array $headers The headers.
	 * @return array Modified headers.
	 */
	public function remove_x_pingback( $headers ) {
		unset( $headers['X-Pingback'] );
		return $headers;
	}

	/**
	 * Optimize database.
	 */
	public function optimize_database() {
		global $wpdb;

		// Clean post revisions
		$wpdb->query( "DELETE FROM $wpdb->posts WHERE post_type = 'revision'" );
		
		// Clean auto drafts
		$wpdb->query( "DELETE FROM $wpdb->posts WHERE post_status = 'auto-draft'" );
		
		// Clean trashed posts
		$wpdb->query( "DELETE FROM $wpdb->posts WHERE post_status = 'trash'" );
		
		// Clean post orphan meta
		$wpdb->query( "DELETE pm FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts p ON pm.post_id = p.ID WHERE p.ID IS NULL" );
		
		// Clean orphaned comment meta
		$wpdb->query( "DELETE cm FROM $wpdb->commentmeta cm LEFT JOIN $wpdb->comments c ON cm.comment_id = c.comment_ID WHERE c.comment_ID IS NULL" );
		
		// Clean orphaned term meta
		$wpdb->query( "DELETE tm FROM $wpdb->termmeta tm LEFT JOIN $wpdb->terms t ON tm.term_id = t.term_id WHERE t.term_id IS NULL" );
		
		// Clean orphaned relationships
		$wpdb->query( "DELETE tr FROM $wpdb->term_relationships tr LEFT JOIN $wpdb->posts p ON tr.object_id = p.ID WHERE p.ID IS NULL" );
		
		// Optimize tables
		$tables = $wpdb->get_results( "SHOW TABLES LIKE '{$wpdb->prefix}%'" );
		foreach ( $tables as $table ) {
			$table_array = get_object_vars( $table );
			$table_name = array_shift( $table_array );
			$wpdb->query( "OPTIMIZE TABLE $table_name" );
		}
	}

	/**
	 * Add browser caching headers.
	 */
	public function add_browser_caching_headers() {
		$cache_control_header = 'public, max-age=31536000, s-maxage=31536000';
		
		// Set Cache-Control header
		header( 'Cache-Control: ' . $cache_control_header );
		
		// Set Expires header (1 year)
		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 31536000 ) . ' GMT' );
		
		// Set Pragma header
		header( 'Pragma: public' );
	}
}