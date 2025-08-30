<?php
/**
 * Lazy Loading Handler
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
 * Lazy Loading Class
 *
 * Handles the implementation of lazy loading for images and iframes
 * to improve page load performance.
 *
 * @since 1.1.0
 */
class Lazy_Loading {

	/**
	 * Skip lazy loading for these image classes.
	 *
	 * @var array
	 */
	private $skip_classes = [];

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		$this->skip_classes = [
			'no-lazy',
			'skip-lazy',
			'custom-logo',
			'avatar',
		];

		// Add user-defined skip classes.
		$user_skip_classes = get_theme_mod( 'aqualuxe_lazy_load_skip_classes', '' );
		if ( ! empty( $user_skip_classes ) ) {
			$user_classes = explode( ',', $user_skip_classes );
			foreach ( $user_classes as $class ) {
				$this->skip_classes[] = trim( $class );
			}
		}
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Skip if lazy loading is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_lazy_loading', true ) ) {
			return;
		}

		// Skip if WordPress 5.5+ (which has native lazy loading).
		if ( function_exists( 'wp_lazy_loading_enabled' ) && wp_lazy_loading_enabled( 'img', 'the_content' ) ) {
			// Only add the JavaScript for native lazy loading support.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			return;
		}

		// Add lazy loading to content.
		add_filter( 'the_content', array( $this, 'add_lazy_loading_to_content' ), 99 );
		add_filter( 'post_thumbnail_html', array( $this, 'add_lazy_loading_to_post_thumbnail' ), 10, 5 );
		add_filter( 'get_avatar', array( $this, 'add_lazy_loading_to_avatar' ), 10, 6 );
		add_filter( 'widget_text', array( $this, 'add_lazy_loading_to_content' ), 99 );
		add_filter( 'woocommerce_short_description', array( $this, 'add_lazy_loading_to_content' ), 99 );
		add_filter( 'woocommerce_product_get_image', array( $this, 'add_lazy_loading_to_woocommerce_product_image' ), 10, 6 );

		// Enqueue scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Enqueue scripts for lazy loading.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// Register and enqueue the lazy loading script.
		wp_register_script(
			'aqualuxe-lazy-loading',
			AQUALUXE_URI . '/assets/js/lazy-loading.js',
			array(),
			AQUALUXE_VERSION,
			true
		);

		wp_enqueue_script( 'aqualuxe-lazy-loading' );

		// Add inline script for lazy loading options.
		$options = array(
			'threshold' => get_theme_mod( 'aqualuxe_lazy_load_threshold', 0 ),
			'skipClasses' => $this->skip_classes,
		);

		wp_localize_script( 'aqualuxe-lazy-loading', 'aqualuxeLazyLoadOptions', $options );
	}

	/**
	 * Add lazy loading to content.
	 *
	 * @param string $content The content.
	 * @return string Modified content.
	 */
	public function add_lazy_loading_to_content( $content ) {
		// Skip if content is empty.
		if ( empty( $content ) ) {
			return $content;
		}

		// Skip if lazy loading is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_lazy_loading', true ) ) {
			return $content;
		}

		// Add lazy loading to images.
		$content = $this->add_lazy_loading_to_images( $content );

		// Add lazy loading to iframes.
		$content = $this->add_lazy_loading_to_iframes( $content );

		return $content;
	}

	/**
	 * Add lazy loading to images in content.
	 *
	 * @param string $content The content.
	 * @return string Modified content.
	 */
	private function add_lazy_loading_to_images( $content ) {
		// Skip if content doesn't have any images.
		if ( false === strpos( $content, '<img' ) ) {
			return $content;
		}

		// Create a DOM document from the content.
		$dom = new \DOMDocument();
		
		// Suppress errors from malformed HTML.
		libxml_use_internal_errors( true );
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );
		libxml_clear_errors();

		// Get all images.
		$images = $dom->getElementsByTagName( 'img' );

		// Loop through images and add lazy loading.
		foreach ( $images as $image ) {
			// Skip if image already has loading attribute.
			if ( $image->hasAttribute( 'loading' ) ) {
				continue;
			}

			// Skip if image has a class that should be skipped.
			if ( $image->hasAttribute( 'class' ) ) {
				$classes = explode( ' ', $image->getAttribute( 'class' ) );
				$skip = false;
				foreach ( $classes as $class ) {
					if ( in_array( $class, $this->skip_classes, true ) ) {
						$skip = true;
						break;
					}
				}
				if ( $skip ) {
					continue;
				}
			}

			// Add loading attribute.
			$image->setAttribute( 'loading', 'lazy' );

			// Add data-src attribute if not already present.
			if ( ! $image->hasAttribute( 'data-src' ) && $image->hasAttribute( 'src' ) ) {
				$image->setAttribute( 'data-src', $image->getAttribute( 'src' ) );
			}

			// Add data-srcset attribute if not already present.
			if ( ! $image->hasAttribute( 'data-srcset' ) && $image->hasAttribute( 'srcset' ) ) {
				$image->setAttribute( 'data-srcset', $image->getAttribute( 'srcset' ) );
			}

			// Add class for JavaScript fallback.
			if ( $image->hasAttribute( 'class' ) ) {
				$image->setAttribute( 'class', $image->getAttribute( 'class' ) . ' aqualuxe-lazy-load' );
			} else {
				$image->setAttribute( 'class', 'aqualuxe-lazy-load' );
			}
		}

		// Get the modified content.
		$content = $dom->saveHTML( $dom->documentElement );

		// Remove doctype, html, and body tags.
		$content = preg_replace( '~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $content );

		return $content;
	}

	/**
	 * Add lazy loading to iframes in content.
	 *
	 * @param string $content The content.
	 * @return string Modified content.
	 */
	private function add_lazy_loading_to_iframes( $content ) {
		// Skip if content doesn't have any iframes.
		if ( false === strpos( $content, '<iframe' ) ) {
			return $content;
		}

		// Create a DOM document from the content.
		$dom = new \DOMDocument();
		
		// Suppress errors from malformed HTML.
		libxml_use_internal_errors( true );
		$dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ) );
		libxml_clear_errors();

		// Get all iframes.
		$iframes = $dom->getElementsByTagName( 'iframe' );

		// Loop through iframes and add lazy loading.
		foreach ( $iframes as $iframe ) {
			// Skip if iframe already has loading attribute.
			if ( $iframe->hasAttribute( 'loading' ) ) {
				continue;
			}

			// Skip if iframe has a class that should be skipped.
			if ( $iframe->hasAttribute( 'class' ) ) {
				$classes = explode( ' ', $iframe->getAttribute( 'class' ) );
				$skip = false;
				foreach ( $classes as $class ) {
					if ( in_array( $class, $this->skip_classes, true ) ) {
						$skip = true;
						break;
					}
				}
				if ( $skip ) {
					continue;
				}
			}

			// Add loading attribute.
			$iframe->setAttribute( 'loading', 'lazy' );

			// Add data-src attribute if not already present.
			if ( ! $iframe->hasAttribute( 'data-src' ) && $iframe->hasAttribute( 'src' ) ) {
				$iframe->setAttribute( 'data-src', $iframe->getAttribute( 'src' ) );
			}

			// Add class for JavaScript fallback.
			if ( $iframe->hasAttribute( 'class' ) ) {
				$iframe->setAttribute( 'class', $iframe->getAttribute( 'class' ) . ' aqualuxe-lazy-load' );
			} else {
				$iframe->setAttribute( 'class', 'aqualuxe-lazy-load' );
			}
		}

		// Get the modified content.
		$content = $dom->saveHTML( $dom->documentElement );

		// Remove doctype, html, and body tags.
		$content = preg_replace( '~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $content );

		return $content;
	}

	/**
	 * Add lazy loading to post thumbnail.
	 *
	 * @param string       $html              The post thumbnail HTML.
	 * @param int          $post_id           The post ID.
	 * @param int          $post_thumbnail_id The post thumbnail ID.
	 * @param string|array $size              The post thumbnail size.
	 * @param string       $attr              The post thumbnail attributes.
	 * @return string Modified HTML.
	 */
	public function add_lazy_loading_to_post_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
		// Skip if lazy loading is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_lazy_loading', true ) ) {
			return $html;
		}

		// Skip if HTML is empty.
		if ( empty( $html ) ) {
			return $html;
		}

		// Skip if HTML already has loading attribute.
		if ( strpos( $html, 'loading=' ) !== false ) {
			return $html;
		}

		// Skip if HTML has a class that should be skipped.
		foreach ( $this->skip_classes as $class ) {
			if ( strpos( $html, 'class="' . $class ) !== false || strpos( $html, 'class="' . $class . ' ' ) !== false || strpos( $html, ' ' . $class . ' ' ) !== false || strpos( $html, ' ' . $class . '"' ) !== false ) {
				return $html;
			}
		}

		// Add loading attribute.
		$html = str_replace( '<img', '<img loading="lazy" class="aqualuxe-lazy-load"', $html );

		return $html;
	}

	/**
	 * Add lazy loading to avatar.
	 *
	 * @param string $avatar      The avatar HTML.
	 * @param mixed  $id_or_email The user ID, email address, or comment object.
	 * @param int    $size        The avatar size in pixels.
	 * @param string $default     The default avatar URL.
	 * @param string $alt         The alt text.
	 * @param array  $args        Additional arguments.
	 * @return string Modified HTML.
	 */
	public function add_lazy_loading_to_avatar( $avatar, $id_or_email, $size, $default, $alt, $args ) {
		// Skip if lazy loading is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_lazy_loading', true ) ) {
			return $avatar;
		}

		// Skip if avatar is empty.
		if ( empty( $avatar ) ) {
			return $avatar;
		}

		// Skip if avatar already has loading attribute.
		if ( strpos( $avatar, 'loading=' ) !== false ) {
			return $avatar;
		}

		// Skip if avatar has a class that should be skipped.
		foreach ( $this->skip_classes as $class ) {
			if ( strpos( $avatar, 'class="' . $class ) !== false || strpos( $avatar, 'class="' . $class . ' ' ) !== false || strpos( $avatar, ' ' . $class . ' ' ) !== false || strpos( $avatar, ' ' . $class . '"' ) !== false ) {
				return $avatar;
			}
		}

		// Add loading attribute.
		$avatar = str_replace( '<img', '<img loading="lazy" class="aqualuxe-lazy-load"', $avatar );

		return $avatar;
	}

	/**
	 * Add lazy loading to WooCommerce product image.
	 *
	 * @param string $image   The product image HTML.
	 * @param object $product The product object.
	 * @param string $size    The image size.
	 * @param array  $attr    The image attributes.
	 * @param bool   $placeholder Whether to use placeholder image.
	 * @param string $image_src   The image source.
	 * @return string Modified HTML.
	 */
	public function add_lazy_loading_to_woocommerce_product_image( $image, $product, $size, $attr, $placeholder, $image_src ) {
		// Skip if lazy loading is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_lazy_loading', true ) ) {
			return $image;
		}

		// Skip if image is empty.
		if ( empty( $image ) ) {
			return $image;
		}

		// Skip if image already has loading attribute.
		if ( strpos( $image, 'loading=' ) !== false ) {
			return $image;
		}

		// Skip if image has a class that should be skipped.
		foreach ( $this->skip_classes as $class ) {
			if ( strpos( $image, 'class="' . $class ) !== false || strpos( $image, 'class="' . $class . ' ' ) !== false || strpos( $image, ' ' . $class . ' ' ) !== false || strpos( $image, ' ' . $class . '"' ) !== false ) {
				return $image;
			}
		}

		// Add loading attribute.
		$image = str_replace( '<img', '<img loading="lazy" class="aqualuxe-lazy-load"', $image );

		return $image;
	}
}