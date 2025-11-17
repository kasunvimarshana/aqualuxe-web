<?php
/**
 * Lazy loading implementation
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * AquaLuxe Lazy Loading Class
 */
class AquaLuxe_Lazy_Loading {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add lazy loading to images in content
		add_filter( 'the_content', array( $this, 'add_lazy_loading_to_content' ), 99 );
		
		// Add lazy loading to post thumbnails
		add_filter( 'post_thumbnail_html', array( $this, 'add_lazy_loading_to_post_thumbnail' ), 10, 5 );
		
		// Add lazy loading to images in widgets
		add_filter( 'widget_text', array( $this, 'add_lazy_loading_to_content' ), 99 );
		
		// Add lazy loading to avatar
		add_filter( 'get_avatar', array( $this, 'add_lazy_loading_to_avatar' ), 10 );
		
		// Add lazy loading to WooCommerce product images
		if ( class_exists( 'WooCommerce' ) ) {
			add_filter( 'woocommerce_single_product_image_thumbnail_html', array( $this, 'add_lazy_loading_to_woocommerce_images' ), 10 );
			add_filter( 'woocommerce_product_get_image', array( $this, 'add_lazy_loading_to_woocommerce_images' ), 10 );
		}
		
		// Add lazy loading script
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_lazy_loading_script' ) );
		
		// Add lazy loading CSS
		add_action( 'wp_head', array( $this, 'add_lazy_loading_css' ), 99 );
	}

	/**
	 * Add lazy loading to content images
	 *
	 * @param string $content The content.
	 * @return string
	 */
	public function add_lazy_loading_to_content( $content ) {
		// Skip if admin or feed
		if ( is_admin() || is_feed() ) {
			return $content;
		}

		// Skip if AMP
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			return $content;
		}

		// Skip if content is empty
		if ( empty( $content ) ) {
			return $content;
		}

		// Replace images with lazy loading
		$content = preg_replace_callback( '/<img([^>]+)>/i', array( $this, 'add_lazy_loading_to_image' ), $content );

		// Replace iframes with lazy loading
		$content = preg_replace_callback( '/<iframe([^>]+)>/i', array( $this, 'add_lazy_loading_to_iframe' ), $content );

		return $content;
	}

	/**
	 * Add lazy loading to image
	 *
	 * @param array $matches The regex matches.
	 * @return string
	 */
	public function add_lazy_loading_to_image( $matches ) {
		$image_html = $matches[0];
		$image_attr = $matches[1];

		// Skip if already has loading attribute
		if ( strpos( $image_attr, 'loading=' ) !== false ) {
			return $image_html;
		}

		// Skip if already has data-src attribute
		if ( strpos( $image_attr, 'data-src=' ) !== false ) {
			return $image_html;
		}

		// Skip if image is in noscript tag
		if ( isset( $this->in_noscript ) && $this->in_noscript ) {
			return $image_html;
		}

		// Get src attribute
		preg_match( '/src=(["\'])(.*?)\1/', $image_attr, $src_matches );
		if ( empty( $src_matches ) ) {
			return $image_html;
		}

		$src = $src_matches[2];

		// Skip if src is data URI
		if ( strpos( $src, 'data:image' ) === 0 ) {
			return $image_html;
		}

		// Skip if src is blank placeholder
		if ( strpos( $src, 'blank.png' ) !== false || strpos( $src, 'placeholder.png' ) !== false ) {
			return $image_html;
		}

		// Get width and height attributes
		$width = '';
		$height = '';
		if ( preg_match( '/width=(["\'])(.*?)\1/', $image_attr, $width_matches ) ) {
			$width = $width_matches[2];
		}
		if ( preg_match( '/height=(["\'])(.*?)\1/', $image_attr, $height_matches ) ) {
			$height = $height_matches[2];
		}

		// Create placeholder
		$placeholder = $this->get_placeholder_image( $width, $height );

		// Replace src with data-src and add loading="lazy"
		$image_html = str_replace( 'src=', 'data-src=', $image_html );
		$image_html = str_replace( '<img', '<img src="' . $placeholder . '" loading="lazy"', $image_html );

		// Add noscript fallback
		$image_html .= '<noscript>' . $matches[0] . '</noscript>';

		return $image_html;
	}

	/**
	 * Add lazy loading to iframe
	 *
	 * @param array $matches The regex matches.
	 * @return string
	 */
	public function add_lazy_loading_to_iframe( $matches ) {
		$iframe_html = $matches[0];
		$iframe_attr = $matches[1];

		// Skip if already has loading attribute
		if ( strpos( $iframe_attr, 'loading=' ) !== false ) {
			return $iframe_html;
		}

		// Skip if already has data-src attribute
		if ( strpos( $iframe_attr, 'data-src=' ) !== false ) {
			return $iframe_html;
		}

		// Skip if iframe is in noscript tag
		if ( isset( $this->in_noscript ) && $this->in_noscript ) {
			return $iframe_html;
		}

		// Get src attribute
		preg_match( '/src=(["\'])(.*?)\1/', $iframe_attr, $src_matches );
		if ( empty( $src_matches ) ) {
			return $iframe_html;
		}

		$src = $src_matches[2];

		// Replace src with data-src and add loading="lazy"
		$iframe_html = str_replace( 'src=', 'data-src=', $iframe_html );
		$iframe_html = str_replace( '<iframe', '<iframe src="about:blank" loading="lazy"', $iframe_html );

		// Add noscript fallback
		$iframe_html .= '<noscript>' . $matches[0] . '</noscript>';

		return $iframe_html;
	}

	/**
	 * Add lazy loading to post thumbnail
	 *
	 * @param string $html The HTML.
	 * @param int    $post_id The post ID.
	 * @param int    $post_thumbnail_id The post thumbnail ID.
	 * @param string $size The size.
	 * @param array  $attr The attributes.
	 * @return string
	 */
	public function add_lazy_loading_to_post_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
		// Skip if admin or feed
		if ( is_admin() || is_feed() ) {
			return $html;
		}

		// Skip if AMP
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			return $html;
		}

		// Skip if already has loading attribute
		if ( strpos( $html, 'loading=' ) !== false ) {
			return $html;
		}

		// Skip if already has data-src attribute
		if ( strpos( $html, 'data-src=' ) !== false ) {
			return $html;
		}

		// Get image dimensions
		$width = '';
		$height = '';
		if ( preg_match( '/width=(["\'])(.*?)\1/', $html, $width_matches ) ) {
			$width = $width_matches[2];
		}
		if ( preg_match( '/height=(["\'])(.*?)\1/', $html, $height_matches ) ) {
			$height = $height_matches[2];
		}

		// Create placeholder
		$placeholder = $this->get_placeholder_image( $width, $height );

		// Replace src with data-src and add loading="lazy"
		$html = str_replace( 'src=', 'data-src=', $html );
		$html = str_replace( '<img', '<img src="' . $placeholder . '" loading="lazy"', $html );

		// Add noscript fallback
		$html .= '<noscript>' . $html . '</noscript>';

		return $html;
	}

	/**
	 * Add lazy loading to avatar
	 *
	 * @param string $avatar The avatar HTML.
	 * @return string
	 */
	public function add_lazy_loading_to_avatar( $avatar ) {
		// Skip if admin or feed
		if ( is_admin() || is_feed() ) {
			return $avatar;
		}

		// Skip if AMP
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			return $avatar;
		}

		// Skip if already has loading attribute
		if ( strpos( $avatar, 'loading=' ) !== false ) {
			return $avatar;
		}

		// Skip if already has data-src attribute
		if ( strpos( $avatar, 'data-src=' ) !== false ) {
			return $avatar;
		}

		// Get image dimensions
		$width = '';
		$height = '';
		if ( preg_match( '/width=(["\'])(.*?)\1/', $avatar, $width_matches ) ) {
			$width = $width_matches[2];
		}
		if ( preg_match( '/height=(["\'])(.*?)\1/', $avatar, $height_matches ) ) {
			$height = $height_matches[2];
		}

		// Create placeholder
		$placeholder = $this->get_placeholder_image( $width, $height );

		// Replace src with data-src and add loading="lazy"
		$avatar = str_replace( 'src=', 'data-src=', $avatar );
		$avatar = str_replace( '<img', '<img src="' . $placeholder . '" loading="lazy"', $avatar );

		// Add noscript fallback
		$avatar .= '<noscript>' . $avatar . '</noscript>';

		return $avatar;
	}

	/**
	 * Add lazy loading to WooCommerce images
	 *
	 * @param string $html The HTML.
	 * @return string
	 */
	public function add_lazy_loading_to_woocommerce_images( $html ) {
		// Skip if admin or feed
		if ( is_admin() || is_feed() ) {
			return $html;
		}

		// Skip if AMP
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			return $html;
		}

		// Skip if already has loading attribute
		if ( strpos( $html, 'loading=' ) !== false ) {
			return $html;
		}

		// Skip if already has data-src attribute
		if ( strpos( $html, 'data-src=' ) !== false ) {
			return $html;
		}

		// Get image dimensions
		$width = '';
		$height = '';
		if ( preg_match( '/width=(["\'])(.*?)\1/', $html, $width_matches ) ) {
			$width = $width_matches[2];
		}
		if ( preg_match( '/height=(["\'])(.*?)\1/', $html, $height_matches ) ) {
			$height = $height_matches[2];
		}

		// Create placeholder
		$placeholder = $this->get_placeholder_image( $width, $height );

		// Replace src with data-src and add loading="lazy"
		$html = str_replace( 'src=', 'data-src=', $html );
		$html = str_replace( '<img', '<img src="' . $placeholder . '" loading="lazy"', $html );

		// Add noscript fallback
		$html .= '<noscript>' . $html . '</noscript>';

		return $html;
	}

	/**
	 * Get placeholder image
	 *
	 * @param string $width The width.
	 * @param string $height The height.
	 * @return string
	 */
	private function get_placeholder_image( $width = '', $height = '' ) {
		// Default dimensions
		$width = ! empty( $width ) ? $width : '100%';
		$height = ! empty( $height ) ? $height : 'auto';

		// Create SVG placeholder
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 1 1" preserveAspectRatio="none"><rect width="1" height="1" fill="#f0f0f0"/></svg>';
		
		// Convert SVG to data URI
		$placeholder = 'data:image/svg+xml;base64,' . base64_encode( $svg );

		return $placeholder;
	}

	/**
	 * Enqueue lazy loading script
	 */
	public function enqueue_lazy_loading_script() {
		// Skip if admin or feed
		if ( is_admin() || is_feed() ) {
			return;
		}

		// Skip if AMP
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			return;
		}

		// Register and enqueue lazy loading script
		wp_register_script(
			'aqualuxe-lazy-loading',
			get_template_directory_uri() . '/assets/js/lazy-loading.js',
			array(),
			AQUALUXE_VERSION,
			true
		);
		wp_enqueue_script( 'aqualuxe-lazy-loading' );
	}

	/**
	 * Add lazy loading CSS
	 */
	public function add_lazy_loading_css() {
		// Skip if admin or feed
		if ( is_admin() || is_feed() ) {
			return;
		}

		// Skip if AMP
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			return;
		}

		// Output CSS
		echo '<style>
			/* Lazy Loading Styles */
			.lazy-loading-placeholder {
				background-color: #f0f0f0;
				position: relative;
				overflow: hidden;
			}
			.lazy-loading-placeholder::before {
				content: "";
				display: block;
				position: absolute;
				top: 0;
				left: -100%;
				width: 50%;
				height: 100%;
				background: linear-gradient(to right, transparent 0%, rgba(255, 255, 255, 0.3) 50%, transparent 100%);
				animation: lazy-loading-animation 1.5s infinite;
			}
			@keyframes lazy-loading-animation {
				0% {
					left: -100%;
				}
				100% {
					left: 150%;
				}
			}
			img[loading="lazy"] {
				opacity: 0;
				transition: opacity 0.3s;
			}
			img.loaded {
				opacity: 1;
			}
		</style>';
	}
}

// Initialize the Lazy Loading class
new AquaLuxe_Lazy_Loading();

/**
 * Create lazy loading JavaScript file
 */
function aqualuxe_create_lazy_loading_js() {
	// Create directory if it doesn't exist
	$js_dir = get_template_directory() . '/assets/js';
	if ( ! file_exists( $js_dir ) ) {
		wp_mkdir_p( $js_dir );
	}

	// Create lazy loading JS file
	$js_file = $js_dir . '/lazy-loading.js';
	if ( ! file_exists( $js_file ) ) {
		$js_content = '/**
 * Lazy Loading Script
 *
 * @package AquaLuxe
 */

(function() {
	"use strict";

	// IntersectionObserver support check
	if (\'IntersectionObserver\' in window) {
		// Create observer
		const lazyImageObserver = new IntersectionObserver(function(entries, observer) {
			entries.forEach(function(entry) {
				if (entry.isIntersecting) {
					const lazyImage = entry.target;
					
					// Load image
					if (lazyImage.dataset.src) {
						lazyImage.src = lazyImage.dataset.src;
						lazyImage.removeAttribute(\'data-src\');
					}
					
					// Load srcset
					if (lazyImage.dataset.srcset) {
						lazyImage.srcset = lazyImage.dataset.srcset;
						lazyImage.removeAttribute(\'data-srcset\');
					}
					
					// Add loaded class
					lazyImage.classList.add(\'loaded\');
					
					// Stop observing this element
					lazyImageObserver.unobserve(lazyImage);
				}
			});
		}, {
			rootMargin: "200px 0px" // Load images 200px before they appear in viewport
		});

		// Observe all images with data-src attribute
		const lazyImages = document.querySelectorAll(\'img[data-src]\');
		lazyImages.forEach(function(lazyImage) {
			// Add placeholder class
			lazyImage.parentNode.classList.add(\'lazy-loading-placeholder\');
			
			// Observe image
			lazyImageObserver.observe(lazyImage);
		});

		// Handle iframes
		const lazyIframes = document.querySelectorAll(\'iframe[data-src]\');
		lazyIframes.forEach(function(lazyIframe) {
			lazyImageObserver.observe(lazyIframe);
		});
	} else {
		// Fallback for browsers without IntersectionObserver support
		const lazyImages = document.querySelectorAll(\'img[data-src]\');
		lazyImages.forEach(function(lazyImage) {
			lazyImage.src = lazyImage.dataset.src;
			if (lazyImage.dataset.srcset) {
				lazyImage.srcset = lazyImage.dataset.srcset;
			}
			lazyImage.classList.add(\'loaded\');
		});

		const lazyIframes = document.querySelectorAll(\'iframe[data-src]\');
		lazyIframes.forEach(function(lazyIframe) {
			lazyIframe.src = lazyIframe.dataset.src;
		});
	}
})();';

		// Write content to file
		file_put_contents( $js_file, $js_content );
	}
}
add_action( 'after_setup_theme', 'aqualuxe_create_lazy_loading_js' );