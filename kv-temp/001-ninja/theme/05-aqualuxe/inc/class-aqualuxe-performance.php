<?php
/**
 * AquaLuxe Performance Optimization
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Performance Optimization Class
 */
class AquaLuxe_Performance {
	/**
	 * Constructor
	 */
	public function __construct() {
		// Asset minification
		add_filter( 'style_loader_src', array( $this, 'remove_version_query_arg' ), 10, 2 );
		add_filter( 'script_loader_src', array( $this, 'remove_version_query_arg' ), 10, 2 );
		
		// Minify HTML output
		add_action( 'template_redirect', array( $this, 'buffer_start' ) );
		add_action( 'shutdown', array( $this, 'buffer_end' ) );
		
		// Image optimization
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_image_loading_attribute' ), 10, 3 );
		add_filter( 'the_content', array( $this, 'add_image_loading_attribute_to_content_images' ) );
		
		// Disable emoji scripts
		add_action( 'init', array( $this, 'disable_emojis' ) );
		
		// Disable jQuery Migrate
		add_action( 'wp_default_scripts', array( $this, 'remove_jquery_migrate' ) );
		
		// Optimize WooCommerce scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'optimize_woocommerce_scripts' ), 99 );
		
		// Add critical CSS
		add_action( 'wp_head', array( $this, 'add_critical_css' ), 1 );
		
		// Add preload for fonts
		add_action( 'wp_head', array( $this, 'add_preload_fonts' ), 1 );
		
		// Add defer to non-critical scripts
		add_filter( 'script_loader_tag', array( $this, 'add_defer_attribute' ), 10, 2 );
		
		// Add browser caching headers
		add_action( 'send_headers', array( $this, 'add_browser_caching_headers' ) );
	}

	/**
	 * Remove version query arg from styles and scripts
	 *
	 * @param string $src Source URL.
	 * @param string $handle Handle.
	 * @return string
	 */
	public function remove_version_query_arg( $src, $handle ) {
		// Don't remove version for admin scripts
		if ( is_admin() ) {
			return $src;
		}
		
		// Remove version query arg
		return remove_query_arg( 'ver', $src );
	}

	/**
	 * Start output buffer
	 */
	public function buffer_start() {
		// Don't minify HTML in admin or when debugging
		if ( is_admin() || ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ) {
			return;
		}
		
		// Start output buffer
		ob_start( array( $this, 'minify_html' ) );
	}

	/**
	 * End output buffer
	 */
	public function buffer_end() {
		// Don't minify HTML in admin or when debugging
		if ( is_admin() || ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ) {
			return;
		}
		
		// End output buffer
		if ( ob_get_length() ) {
			ob_end_flush();
		}
	}

	/**
	 * Minify HTML
	 *
	 * @param string $html HTML content.
	 * @return string
	 */
	public function minify_html( $html ) {
		// Skip if HTML is empty
		if ( empty( $html ) ) {
			return $html;
		}
		
		// Create minified version
		$minified = $html;
		
		// Remove comments (but keep IE conditional comments)
		$minified = preg_replace( '/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $minified );
		
		// Remove whitespace
		$minified = preg_replace( '/\s+/s', ' ', $minified );
		
		// Remove whitespace between tags
		$minified = preg_replace( '/>\s+</s', '><', $minified );
		
		// Remove whitespace at the start of tags
		$minified = preg_replace( '/\s+>/s', '>', $minified );
		
		// Remove whitespace at the end of tags
		$minified = preg_replace( '/<\s+/s', '<', $minified );
		
		return $minified;
	}

	/**
	 * Add loading attribute to images
	 *
	 * @param array $attributes Image attributes.
	 * @param WP_Post $attachment Attachment post.
	 * @param string|array $size Image size.
	 * @return array
	 */
	public function add_image_loading_attribute( $attributes, $attachment, $size ) {
		// Skip if image is in admin
		if ( is_admin() ) {
			return $attributes;
		}
		
		// Add loading attribute
		$attributes['loading'] = 'lazy';
		
		return $attributes;
	}

	/**
	 * Add loading attribute to content images
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	public function add_image_loading_attribute_to_content_images( $content ) {
		// Skip if content is empty
		if ( empty( $content ) ) {
			return $content;
		}
		
		// Add loading attribute to images
		$content = preg_replace( '/<img(.*?)>/i', '<img$1 loading="lazy">', $content );
		
		return $content;
	}

	/**
	 * Disable emojis
	 */
	public function disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		
		// Remove emoji from TinyMCE
		add_filter( 'tiny_mce_plugins', array( $this, 'disable_emojis_tinymce' ) );
		
		// Remove emoji DNS prefetch
		add_filter( 'emoji_svg_url', '__return_false' );
	}

	/**
	 * Disable emojis in TinyMCE
	 *
	 * @param array $plugins TinyMCE plugins.
	 * @return array
	 */
	public function disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		}
		
		return $plugins;
	}

	/**
	 * Remove jQuery Migrate
	 *
	 * @param WP_Scripts $scripts WP_Scripts object.
	 */
	public function remove_jquery_migrate( $scripts ) {
		// Skip if in admin
		if ( is_admin() ) {
			return;
		}
		
		// Remove jQuery Migrate
		if ( isset( $scripts->registered['jquery'] ) ) {
			$script = $scripts->registered['jquery'];
			
			if ( $script->deps ) {
				$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
			}
		}
	}

	/**
	 * Optimize WooCommerce scripts
	 */
	public function optimize_woocommerce_scripts() {
		// Skip if WooCommerce is not active
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		
		// Remove WooCommerce scripts and styles from non-WooCommerce pages
		if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() && ! is_account_page() ) {
			wp_dequeue_style( 'woocommerce-general' );
			wp_dequeue_style( 'woocommerce-layout' );
			wp_dequeue_style( 'woocommerce-smallscreen' );
			wp_dequeue_style( 'woocommerce_frontend_styles' );
			wp_dequeue_style( 'woocommerce_fancybox_styles' );
			wp_dequeue_style( 'woocommerce_chosen_styles' );
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			wp_dequeue_script( 'wc_price_slider' );
			wp_dequeue_script( 'wc-single-product' );
			wp_dequeue_script( 'wc-add-to-cart' );
			wp_dequeue_script( 'wc-cart-fragments' );
			wp_dequeue_script( 'wc-checkout' );
			wp_dequeue_script( 'wc-add-to-cart-variation' );
			wp_dequeue_script( 'wc-single-product' );
			wp_dequeue_script( 'wc-cart' );
			wp_dequeue_script( 'wc-chosen' );
			wp_dequeue_script( 'woocommerce' );
			wp_dequeue_script( 'prettyPhoto' );
			wp_dequeue_script( 'prettyPhoto-init' );
			wp_dequeue_script( 'jquery-blockui' );
			wp_dequeue_script( 'jquery-placeholder' );
			wp_dequeue_script( 'fancybox' );
			wp_dequeue_script( 'jqueryui' );
		}
	}

	/**
	 * Add critical CSS
	 */
	public function add_critical_css() {
		// Skip if in admin
		if ( is_admin() ) {
			return;
		}
		
		// Get critical CSS
		$critical_css = $this->get_critical_css();
		
		// Output critical CSS
		if ( ! empty( $critical_css ) ) {
			echo '<style id="aqualuxe-critical-css">' . $this->minify_css( $critical_css ) . '</style>';
		}
	}

	/**
	 * Get critical CSS
	 *
	 * @return string
	 */
	private function get_critical_css() {
		$css = '
			/* Critical CSS for above-the-fold content */
			:root {
				--aqualuxe-primary-color: #0073aa;
				--aqualuxe-secondary-color: #00a0d2;
			}
			
			body {
				margin: 0;
				padding: 0;
				font-family: "Poppins", sans-serif;
				font-size: 16px;
				line-height: 1.6;
				color: #333;
				background-color: #fff;
			}
			
			.container {
				width: 100%;
				max-width: 1200px;
				margin: 0 auto;
				padding: 0 15px;
			}
			
			.site-header {
				background-color: #fff;
				box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
				position: relative;
				z-index: 100;
			}
			
			.site-header-inner {
				display: flex;
				align-items: center;
				justify-content: space-between;
				padding: 20px 0;
			}
			
			.site-branding {
				display: flex;
				align-items: center;
			}
			
			.site-title {
				margin: 0;
				font-size: 24px;
				font-weight: 700;
				line-height: 1.2;
			}
			
			.site-title a {
				color: #333;
				text-decoration: none;
			}
			
			.site-description {
				margin: 5px 0 0;
				font-size: 14px;
				color: #666;
			}
			
			.main-navigation {
				display: flex;
				align-items: center;
			}
			
			.menu {
				display: flex;
				list-style: none;
				margin: 0;
				padding: 0;
			}
			
			.menu li {
				position: relative;
				margin: 0 10px;
			}
			
			.menu li a {
				display: block;
				padding: 10px;
				color: #333;
				text-decoration: none;
				font-weight: 500;
				transition: color 0.3s;
			}
			
			.menu li a:hover,
			.menu li.current-menu-item > a {
				color: var(--aqualuxe-primary-color);
			}
			
			.mobile-menu-toggle {
				display: none;
				background: none;
				border: none;
				padding: 10px;
				cursor: pointer;
				color: #333;
				font-size: 24px;
			}
			
			@media (max-width: 768px) {
				.main-navigation .menu {
					display: none;
				}
				
				.mobile-menu-toggle {
					display: block;
				}
			}
			
			/* WooCommerce critical styles */
			.woocommerce ul.products {
				display: grid;
				grid-template-columns: repeat(4, 1fr);
				grid-gap: 30px;
				list-style: none;
				margin: 0;
				padding: 0;
			}
			
			@media (max-width: 992px) {
				.woocommerce ul.products {
					grid-template-columns: repeat(3, 1fr);
				}
			}
			
			@media (max-width: 768px) {
				.woocommerce ul.products {
					grid-template-columns: repeat(2, 1fr);
				}
			}
			
			@media (max-width: 576px) {
				.woocommerce ul.products {
					grid-template-columns: 1fr;
				}
			}
			
			.woocommerce ul.products li.product {
				margin: 0;
				padding: 0;
				position: relative;
			}
			
			.woocommerce ul.products li.product .product-inner {
				background-color: #fff;
				border-radius: 4px;
				box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
				overflow: hidden;
				transition: box-shadow 0.3s;
			}
			
			.woocommerce ul.products li.product .product-inner:hover {
				box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
			}
			
			.woocommerce ul.products li.product .woocommerce-loop-product__title {
				padding: 15px;
				margin: 0;
				font-size: 16px;
				font-weight: 500;
				color: #333;
			}
			
			.woocommerce ul.products li.product .price {
				padding: 0 15px 15px;
				margin: 0;
				font-size: 18px;
				font-weight: 600;
				color: var(--aqualuxe-primary-color);
			}
			
			.woocommerce ul.products li.product .button {
				display: inline-block;
				background-color: var(--aqualuxe-primary-color);
				color: #fff;
				padding: 10px 20px;
				border-radius: 4px;
				text-decoration: none;
				font-weight: 500;
				transition: background-color 0.3s;
				margin: 15px;
			}
			
			.woocommerce ul.products li.product .button:hover {
				background-color: var(--aqualuxe-secondary-color);
			}
		';
		
		return $css;
	}

	/**
	 * Minify CSS
	 *
	 * @param string $css CSS to minify.
	 * @return string
	 */
	private function minify_css( $css ) {
		// Remove comments
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
		
		// Remove space after colons
		$css = str_replace( ': ', ':', $css );
		
		// Remove whitespace
		$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
		
		return $css;
	}

	/**
	 * Add preload for fonts
	 */
	public function add_preload_fonts() {
		// Skip if in admin
		if ( is_admin() ) {
			return;
		}
		
		// Preload Google Fonts
		echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
		echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
		echo '<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600&display=swap">';
	}

	/**
	 * Add defer attribute to non-critical scripts
	 *
	 * @param string $tag Script tag.
	 * @param string $handle Script handle.
	 * @return string
	 */
	public function add_defer_attribute( $tag, $handle ) {
		// Skip if in admin
		if ( is_admin() ) {
			return $tag;
		}
		
		// List of scripts to defer
		$defer_scripts = array(
			'aqualuxe-script',
			'aqualuxe-woocommerce',
			'aqualuxe-currency-switcher',
		);
		
		// Add defer attribute
		if ( in_array( $handle, $defer_scripts, true ) ) {
			return str_replace( ' src', ' defer src', $tag );
		}
		
		return $tag;
	}

	/**
	 * Add browser caching headers
	 */
	public function add_browser_caching_headers() {
		// Skip if in admin
		if ( is_admin() ) {
			return;
		}
		
		// Get file extension
		$file_extension = pathinfo( $_SERVER['REQUEST_URI'], PATHINFO_EXTENSION );
		
		// Set cache time based on file extension
		$cache_time = 0;
		
		switch ( $file_extension ) {
			case 'css':
			case 'js':
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
			case 'webp':
			case 'svg':
			case 'ico':
			case 'woff':
			case 'woff2':
			case 'ttf':
			case 'eot':
				$cache_time = 31536000; // 1 year
				break;
			default:
				$cache_time = 86400; // 1 day
				break;
		}
		
		// Set cache headers
		if ( $cache_time > 0 ) {
			header( 'Cache-Control: public, max-age=' . $cache_time );
			header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $cache_time ) . ' GMT' );
		}
	}
}

// Initialize the class
new AquaLuxe_Performance();