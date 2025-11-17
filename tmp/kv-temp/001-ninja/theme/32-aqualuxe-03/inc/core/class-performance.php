<?php
/**
 * AquaLuxe Performance Optimizations
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.1.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Performance Class
 *
 * Handles performance optimizations for the theme.
 * This class serves as a central hub for all performance-related features.
 *
 * @since 1.1.0
 */
class Performance {

	/**
	 * Critical CSS instance.
	 *
	 * @var Critical_CSS
	 */
	private $critical_css;

	/**
	 * Resource Hints instance.
	 *
	 * @var Resource_Hints
	 */
	private $resource_hints;

	/**
	 * Lazy Loading instance.
	 *
	 * @var Lazy_Loading
	 */
	private $lazy_loading;

	/**
	 * WebP Support instance.
	 *
	 * @var WebP_Support
	 */
	private $webp_support;

	/**
	 * Browser Caching instance.
	 *
	 * @var Browser_Caching
	 */
	private $browser_caching;

	/**
	 * Minification instance.
	 *
	 * @var Minification
	 */
	private $minification;

	/**
	 * Script Loading instance.
	 *
	 * @var Script_Loading
	 */
	private $script_loading;

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// Initialize performance modules.
		$this->init_modules();
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Register hooks for performance modules.
		$this->register_module_hooks();

		// Legacy performance hooks.
		$this->register_legacy_hooks();

		// Add admin menu.
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	/**
	 * Initialize performance modules.
	 *
	 * @return void
	 */
	private function init_modules() {
		// Initialize Critical CSS.
		$this->critical_css = new Critical_CSS();
		$this->critical_css->initialize();

		// Initialize Resource Hints.
		$this->resource_hints = new Resource_Hints();
		$this->resource_hints->initialize();

		// Initialize Lazy Loading.
		$this->lazy_loading = new Lazy_Loading();
		$this->lazy_loading->initialize();

		// Initialize WebP Support.
		$this->webp_support = new WebP_Support();
		$this->webp_support->initialize();

		// Initialize Browser Caching.
		$this->browser_caching = new Browser_Caching();
		$this->browser_caching->initialize();

		// Initialize Minification.
		$this->minification = new Minification();
		$this->minification->initialize();

		// Initialize Script Loading.
		$this->script_loading = new Script_Loading();
		$this->script_loading->initialize();
	}

	/**
	 * Register hooks for performance modules.
	 *
	 * @return void
	 */
	private function register_module_hooks() {
		// Register hooks for Critical CSS.
		$this->critical_css->register_hooks();

		// Register hooks for Resource Hints.
		$this->resource_hints->register_hooks();

		// Register hooks for Lazy Loading.
		$this->lazy_loading->register_hooks();

		// Register hooks for WebP Support.
		$this->webp_support->register_hooks();

		// Register hooks for Browser Caching.
		$this->browser_caching->register_hooks();

		// Register hooks for Minification.
		$this->minification->register_hooks();

		// Register hooks for Script Loading.
		$this->script_loading->register_hooks();
	}

	/**
	 * Register legacy performance hooks.
	 *
	 * @return void
	 */
	private function register_legacy_hooks() {
		// Disable emoji.
		add_action( 'init', array( $this, 'disable_emoji' ) );
		
		// Remove query strings from static resources.
		add_filter( 'script_loader_src', array( $this, 'remove_script_version' ), 15, 1 );
		add_filter( 'style_loader_src', array( $this, 'remove_script_version' ), 15, 1 );
		
		// Add WebP support.
		add_filter( 'upload_mimes', array( $this, 'add_webp_mime_type' ) );
		
		// Optimize database queries.
		add_action( 'wp_dashboard_setup', array( $this, 'remove_dashboard_widgets' ) );
		
		// Optimize heartbeat API.
		add_filter( 'heartbeat_settings', array( $this, 'optimize_heartbeat' ) );
		
		// Optimize WP-JSON.
		add_filter( 'rest_authentication_errors', array( $this, 'restrict_rest_api' ) );

		// Disable unnecessary features.
		$this->disable_unnecessary_features();
	}

	/**
	 * Add admin menu for performance.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_menu_page(
			__( 'Performance', 'aqualuxe' ),
			__( 'Performance', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-performance',
			array( $this, 'admin_page' ),
			'dashicons-performance',
			70
		);

		// Add submenu pages.
		add_submenu_page(
			'aqualuxe-performance',
			__( 'Performance Overview', 'aqualuxe' ),
			__( 'Overview', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-performance',
			array( $this, 'admin_page' )
		);
	}

	/**
	 * Admin page for performance.
	 *
	 * @return void
	 */
	public function admin_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Performance', 'aqualuxe' ); ?></h1>
			<p><?php esc_html_e( 'Optimize your website\'s performance with the following features.', 'aqualuxe' ); ?></p>
			
			<div class="aqualuxe-performance-grid">
				<div class="aqualuxe-performance-card">
					<h2><?php esc_html_e( 'Critical CSS', 'aqualuxe' ); ?></h2>
					<p><?php esc_html_e( 'Critical CSS is the minimal CSS needed to render the above-the-fold content of a page. It helps improve page load performance by inlining critical CSS in the head and deferring the loading of non-critical CSS.', 'aqualuxe' ); ?></p>
					<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-critical-css' ) ); ?>" class="button"><?php esc_html_e( 'Configure', 'aqualuxe' ); ?></a></p>
				</div>
				
				<div class="aqualuxe-performance-card">
					<h2><?php esc_html_e( 'Resource Hints', 'aqualuxe' ); ?></h2>
					<p><?php esc_html_e( 'Resource hints help improve page load performance by telling the browser which resources it should connect to or fetch in advance.', 'aqualuxe' ); ?></p>
					<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-resource-hints' ) ); ?>" class="button"><?php esc_html_e( 'Configure', 'aqualuxe' ); ?></a></p>
				</div>
				
				<div class="aqualuxe-performance-card">
					<h2><?php esc_html_e( 'Lazy Loading', 'aqualuxe' ); ?></h2>
					<p><?php esc_html_e( 'Lazy loading defers the loading of images and iframes until they are needed, which can significantly improve page load performance.', 'aqualuxe' ); ?></p>
					<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-lazy-loading' ) ); ?>" class="button"><?php esc_html_e( 'Configure', 'aqualuxe' ); ?></a></p>
				</div>
				
				<div class="aqualuxe-performance-card">
					<h2><?php esc_html_e( 'WebP Support', 'aqualuxe' ); ?></h2>
					<p><?php esc_html_e( 'WebP is a modern image format that provides superior lossless and lossy compression for images on the web. Using WebP, webmasters and web developers can create smaller, richer images that make the web faster.', 'aqualuxe' ); ?></p>
					<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-webp-support' ) ); ?>" class="button"><?php esc_html_e( 'Configure', 'aqualuxe' ); ?></a></p>
				</div>
				
				<div class="aqualuxe-performance-card">
					<h2><?php esc_html_e( 'Browser Caching', 'aqualuxe' ); ?></h2>
					<p><?php esc_html_e( 'Browser caching allows browsers to store static resources locally, reducing the need for repeated downloads and improving page load performance.', 'aqualuxe' ); ?></p>
					<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-browser-caching' ) ); ?>" class="button"><?php esc_html_e( 'Configure', 'aqualuxe' ); ?></a></p>
				</div>
				
				<div class="aqualuxe-performance-card">
					<h2><?php esc_html_e( 'Minification', 'aqualuxe' ); ?></h2>
					<p><?php esc_html_e( 'Minification reduces the size of CSS and JavaScript files by removing whitespace, comments, and other unnecessary characters. Combining files reduces the number of HTTP requests, which can improve page load performance.', 'aqualuxe' ); ?></p>
					<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-minification' ) ); ?>" class="button"><?php esc_html_e( 'Configure', 'aqualuxe' ); ?></a></p>
				</div>
				
				<div class="aqualuxe-performance-card">
					<h2><?php esc_html_e( 'Script Loading', 'aqualuxe' ); ?></h2>
					<p><?php esc_html_e( 'Optimize script loading by adding async and defer attributes to scripts. This can improve page load performance by allowing scripts to load asynchronously or after the page has loaded.', 'aqualuxe' ); ?></p>
					<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-script-loading' ) ); ?>" class="button"><?php esc_html_e( 'Configure', 'aqualuxe' ); ?></a></p>
				</div>
				
				<div class="aqualuxe-performance-card">
					<h2><?php esc_html_e( 'Other Optimizations', 'aqualuxe' ); ?></h2>
					<p><?php esc_html_e( 'Configure other performance optimizations such as disabling emoji, removing query strings, and optimizing the WordPress dashboard.', 'aqualuxe' ); ?></p>
					<p><a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=aqualuxe_performance' ) ); ?>" class="button"><?php esc_html_e( 'Configure', 'aqualuxe' ); ?></a></p>
				</div>
			</div>
			
			<style>
				.aqualuxe-performance-grid {
					display: grid;
					grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
					grid-gap: 20px;
					margin-top: 20px;
				}
				
				.aqualuxe-performance-card {
					background-color: #fff;
					border: 1px solid #ccd0d4;
					border-radius: 4px;
					padding: 20px;
					box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
				}
				
				.aqualuxe-performance-card h2 {
					margin-top: 0;
					margin-bottom: 10px;
					font-size: 18px;
				}
				
				.aqualuxe-performance-card p:last-child {
					margin-bottom: 0;
				}
			</style>
		</div>
		<?php
	}

	/**
	 * Disable WordPress emoji.
	 *
	 * @return void
	 */
	public function disable_emoji() {
		// Check if emoji should be disabled.
		if ( ! get_theme_mod( 'aqualuxe_disable_emoji', true ) ) {
			return;
		}
		
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		
		// Remove TinyMCE emoji plugin.
		add_filter( 'tiny_mce_plugins', function( $plugins ) {
			if ( is_array( $plugins ) ) {
				return array_diff( $plugins, array( 'wpemoji' ) );
			}
			return array();
		} );
		
		// Remove emoji from DNS prefetch.
		add_filter( 'wp_resource_hints', function( $urls, $relation_type ) {
			if ( 'dns-prefetch' === $relation_type ) {
				$emoji_url = 'https://s.w.org/images/core/emoji/';
				foreach ( $urls as $key => $url ) {
					if ( strpos( $url, $emoji_url ) !== false ) {
						unset( $urls[ $key ] );
					}
				}
			}
			return $urls;
		}, 10, 2 );
	}

	/**
	 * Remove query strings from static resources.
	 *
	 * @param string $src The source URL.
	 * @return string
	 */
	public function remove_script_version( $src ) {
		// Check if query strings should be removed.
		if ( ! get_theme_mod( 'aqualuxe_remove_query_strings', true ) ) {
			return $src;
		}
		
		// Don't remove query strings from admin or login pages.
		if ( is_admin() || strpos( $src, 'wp-login.php' ) !== false ) {
			return $src;
		}
		
		// Don't remove version from theme assets for cache busting.
		if ( strpos( $src, AQUALUXE_URI ) !== false ) {
			return $src;
		}
		
		// Remove query strings.
		if ( strpos( $src, '?' ) !== false ) {
			$parts = explode( '?', $src );
			$src = $parts[0];
		}
		
		return $src;
	}

	/**
	 * Disable unnecessary features.
	 *
	 * @return void
	 */
	private function disable_unnecessary_features() {
		// Check if unnecessary features should be disabled.
		if ( ! get_theme_mod( 'aqualuxe_disable_unnecessary_features', true ) ) {
			return;
		}
		
		// Disable XML-RPC.
		add_filter( 'xmlrpc_enabled', '__return_false' );
		
		// Remove XML-RPC headers.
		add_filter( 'wp_headers', function( $headers ) {
			unset( $headers['X-Pingback'] );
			return $headers;
		} );
		
		// Disable pingbacks.
		add_filter( 'xmlrpc_methods', function( $methods ) {
			unset( $methods['pingback.ping'] );
			return $methods;
		} );
		
		// Disable self-pingbacks.
		add_action( 'pre_ping', function( &$links ) {
			$home = get_option( 'home' );
			foreach ( $links as $l => $link ) {
				if ( strpos( $link, $home ) === 0 ) {
					unset( $links[ $l ] );
				}
			}
		} );
		
		// Disable RSS feeds.
		if ( get_theme_mod( 'aqualuxe_disable_rss_feeds', false ) ) {
			add_action( 'do_feed', array( $this, 'disable_feed' ), 1 );
			add_action( 'do_feed_rdf', array( $this, 'disable_feed' ), 1 );
			add_action( 'do_feed_rss', array( $this, 'disable_feed' ), 1 );
			add_action( 'do_feed_rss2', array( $this, 'disable_feed' ), 1 );
			add_action( 'do_feed_atom', array( $this, 'disable_feed' ), 1 );
			
			// Remove feed links from header.
			remove_action( 'wp_head', 'feed_links', 2 );
			remove_action( 'wp_head', 'feed_links_extra', 3 );
		}
		
		// Disable oEmbed.
		if ( get_theme_mod( 'aqualuxe_disable_oembed', false ) ) {
			add_action( 'init', function() {
				remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
				remove_action( 'wp_head', 'wp_oembed_add_host_js' );
				remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
				add_filter( 'embed_oembed_discover', '__return_false' );
				remove_action( 'rest_api_init', 'wp_oembed_register_route' );
				
				// Remove oEmbed REST API endpoint.
				add_filter( 'rest_endpoints', function( $endpoints ) {
					if ( isset( $endpoints['/oembed/1.0/embed'] ) ) {
						unset( $endpoints['/oembed/1.0/embed'] );
					}
					return $endpoints;
				} );
			} );
		}
		
		// Disable jQuery Migrate.
		if ( get_theme_mod( 'aqualuxe_disable_jquery_migrate', false ) ) {
			add_action( 'wp_default_scripts', function( $scripts ) {
				if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
					$script = $scripts->registered['jquery'];
					
					if ( $script->deps ) {
						$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
					}
				}
			} );
		}
	}

	/**
	 * Disable feed.
	 *
	 * @return void
	 */
	public function disable_feed() {
		wp_die(
			sprintf(
				/* translators: %s: Site URL */
				esc_html__( 'RSS feeds are disabled. Please visit the %s homepage instead.', 'aqualuxe' ),
				'<a href="' . esc_url( home_url() ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>'
			),
			esc_html__( 'RSS Feeds Disabled', 'aqualuxe' ),
			array( 'response' => 410 )
		);
	}

	/**
	 * Add WebP MIME type.
	 *
	 * @param array $mimes Allowed MIME types.
	 * @return array
	 */
	public function add_webp_mime_type( $mimes ) {
		$mimes['webp'] = 'image/webp';
		return $mimes;
	}

	/**
	 * Remove dashboard widgets.
	 *
	 * @return void
	 */
	public function remove_dashboard_widgets() {
		// Check if dashboard widgets should be removed.
		if ( ! get_theme_mod( 'aqualuxe_optimize_dashboard', true ) ) {
			return;
		}
		
		// Remove dashboard widgets.
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	}

	/**
	 * Optimize heartbeat API.
	 *
	 * @param array $settings Heartbeat settings.
	 * @return array
	 */
	public function optimize_heartbeat( $settings ) {
		// Check if heartbeat should be optimized.
		if ( ! get_theme_mod( 'aqualuxe_optimize_heartbeat', true ) ) {
			return $settings;
		}
		
		// Optimize heartbeat.
		$settings['interval'] = 60; // Seconds.
		
		return $settings;
	}

	/**
	 * Restrict REST API for unauthenticated users.
	 *
	 * @param mixed $result The result of the authentication.
	 * @return mixed
	 */
	public function restrict_rest_api( $result ) {
		// Check if REST API should be restricted.
		if ( ! get_theme_mod( 'aqualuxe_restrict_rest_api', false ) ) {
			return $result;
		}
		
		// If a previous authentication check was applied, pass that result along.
		if ( true === $result || is_wp_error( $result ) ) {
			return $result;
		}
		
		// No authentication has been performed yet.
		// Return an error if user is not logged in.
		if ( ! is_user_logged_in() ) {
			return new \WP_Error(
				'rest_not_logged_in',
				esc_html__( 'You are not currently logged in.', 'aqualuxe' ),
				array( 'status' => 401 )
			);
		}
		
		// Our custom authentication check passed.
		return $result;
	}
}