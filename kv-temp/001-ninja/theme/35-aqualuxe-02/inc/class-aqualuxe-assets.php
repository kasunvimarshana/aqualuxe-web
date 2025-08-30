<?php
/**
 * AquaLuxe Assets Management
 *
 * This class handles the optimization and loading of theme assets
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe_Assets class
 */
class AquaLuxe_Assets {

	/**
	 * Instance of this class
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Get instance of this class
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		// Register hooks.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ), 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 10 );
		add_action( 'wp_head', array( $this, 'add_critical_css' ), 1 );
		add_filter( 'script_loader_tag', array( $this, 'add_async_defer_attributes' ), 10, 2 );
		add_action( 'wp_footer', array( $this, 'load_non_critical_css' ), 10 );
		add_filter( 'wp_resource_hints', array( $this, 'add_preconnect_hints' ), 10, 2 );
		
		// Add lazy loading to images.
		add_filter( 'the_content', array( $this, 'add_lazy_loading_to_images' ) );
		add_filter( 'post_thumbnail_html', array( $this, 'add_lazy_loading_to_images' ) );
	}

	/**
	 * Register all theme assets
	 */
	public function register_assets() {
		$theme_version = wp_get_theme()->get( 'Version' );
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		// Register styles.
		wp_register_style(
			'aqualuxe-main',
			get_template_directory_uri() . '/dist/css/main' . $suffix . '.css',
			array(),
			$theme_version
		);
		
		wp_register_style(
			'aqualuxe-woocommerce',
			get_template_directory_uri() . '/dist/css/woocommerce' . $suffix . '.css',
			array( 'aqualuxe-main' ),
			$theme_version
		);
		
		// Register scripts.
		wp_register_script(
			'aqualuxe-navigation',
			get_template_directory_uri() . '/dist/js/navigation' . $suffix . '.js',
			array( 'jquery' ),
			$theme_version,
			true
		);
		
		wp_register_script(
			'aqualuxe-alpine',
			get_template_directory_uri() . '/dist/js/alpine' . $suffix . '.js',
			array(),
			$theme_version,
			true
		);
		
		wp_register_script(
			'aqualuxe-main',
			get_template_directory_uri() . '/dist/js/main' . $suffix . '.js',
			array( 'jquery', 'aqualuxe-alpine' ),
			$theme_version,
			true
		);
		
		wp_register_script(
			'aqualuxe-woocommerce',
			get_template_directory_uri() . '/dist/js/woocommerce' . $suffix . '.js',
			array( 'jquery', 'aqualuxe-main' ),
			$theme_version,
			true
		);
	}

	/**
	 * Enqueue theme assets
	 */
	public function enqueue_assets() {
		// Enqueue main stylesheet.
		wp_enqueue_style( 'aqualuxe-main' );
		
		// Enqueue WooCommerce styles if active.
		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_style( 'aqualuxe-woocommerce' );
		}
		
		// Enqueue scripts.
		wp_enqueue_script( 'aqualuxe-navigation' );
		wp_enqueue_script( 'aqualuxe-main' );
		
		// Enqueue WooCommerce scripts if active.
		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_script( 'aqualuxe-woocommerce' );
		}
		
		// Add comment-reply script if needed.
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		
		// Localize script with theme data.
		wp_localize_script(
			'aqualuxe-main',
			'aqualuxeData',
			array(
				'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
				'homeUrl'    => esc_url( home_url( '/' ) ),
				'themeUrl'   => get_template_directory_uri(),
				'nonce'      => wp_create_nonce( 'aqualuxe-nonce' ),
				'isRtl'      => is_rtl(),
				'isMobile'   => wp_is_mobile(),
				'hasWooCommerce' => class_exists( 'WooCommerce' ),
			)
		);
	}

	/**
	 * Add critical CSS to head
	 */
	public function add_critical_css() {
		$critical_css_path = get_template_directory() . '/dist/css/critical.css';
		
		if ( file_exists( $critical_css_path ) ) {
			echo '<style id="aqualuxe-critical-css">';
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			echo file_get_contents( $critical_css_path );
			echo '</style>';
		}
	}

	/**
	 * Load non-critical CSS asynchronously
	 */
	public function load_non_critical_css() {
		// Only proceed if we're not in admin and not in customizer preview.
		if ( is_admin() || is_customize_preview() ) {
			return;
		}
		
		// Get all enqueued styles.
		global $wp_styles;
		
		// Skip if no styles are enqueued.
		if ( empty( $wp_styles->queue ) ) {
			return;
		}
		
		echo '<noscript id="aqualuxe-noscript-styles">';
		
		foreach ( $wp_styles->queue as $handle ) {
			$style = $wp_styles->registered[ $handle ];
			
			// Skip if this is an inline style.
			if ( ! isset( $style->src ) || empty( $style->src ) ) {
				continue;
			}
			
			// Skip critical CSS which is already loaded.
			if ( 'aqualuxe-critical' === $handle ) {
				continue;
			}
			
			$href = $style->src;
			
			// Add version if it exists.
			if ( ! empty( $style->ver ) ) {
				$href = add_query_arg( 'ver', $style->ver, $href );
			}
			
			// Print the style tag.
			echo '<link rel="stylesheet" id="' . esc_attr( $handle ) . '-css" href="' . esc_url( $href ) . '" type="text/css" media="all" />';
		}
		
		echo '</noscript>';
		
		// Add preload for main stylesheet.
		echo '<link rel="preload" href="' . esc_url( wp_styles()->registered['aqualuxe-main']->src ) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
	}

	/**
	 * Add async/defer attributes to scripts
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @return string Modified script tag.
	 */
	public function add_async_defer_attributes( $tag, $handle ) {
		// Add async to Alpine.js.
		if ( 'aqualuxe-alpine' === $handle ) {
			return str_replace( ' src', ' async src', $tag );
		}
		
		// Add defer to navigation script.
		if ( 'aqualuxe-navigation' === $handle ) {
			return str_replace( ' src', ' defer src', $tag );
		}
		
		return $tag;
	}

	/**
	 * Add preconnect for external resources
	 *
	 * @param array  $hints          Hints array.
	 * @param string $relation_type  The relation type.
	 * @return array Modified hints array.
	 */
	public function add_preconnect_hints( $hints, $relation_type ) {
		if ( 'preconnect' === $relation_type ) {
			// Add Google Fonts if used.
			if ( $this->is_using_google_fonts() ) {
				$hints[] = 'https://fonts.googleapis.com';
				$hints[] = 'https://fonts.gstatic.com';
			}
			
			// Add other external resources as needed.
		}
		
		return $hints;
	}

	/**
	 * Check if theme is using Google Fonts
	 *
	 * @return boolean True if using Google Fonts.
	 */
	private function is_using_google_fonts() {
		// Check theme mods or options to determine if Google Fonts are enabled.
		$use_google_fonts = get_theme_mod( 'aqualuxe_use_google_fonts', true );
		
		return apply_filters( 'aqualuxe_use_google_fonts', $use_google_fonts );
	}

	/**
	 * Add lazy loading to images in content
	 *
	 * @param string $content The content.
	 * @return string Modified content.
	 */
	public function add_lazy_loading_to_images( $content ) {
		// Skip if content is empty.
		if ( empty( $content ) ) {
			return $content;
		}
		
		// Skip if this is an admin page or feed.
		if ( is_admin() || is_feed() ) {
			return $content;
		}
		
		// Skip if this is a REST API request.
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return $content;
		}
		
		// Don't lazy load if the content already has lazy loading attributes.
		if ( strpos( $content, 'loading="lazy"' ) !== false ) {
			return $content;
		}
		
		// Add loading="lazy" to img tags that don't already have it.
		$content = preg_replace( '/<img(.*?)>/i', '<img$1 loading="lazy">', $content );
		
		return $content;
	}
}

// Initialize the class.
AquaLuxe_Assets::get_instance();