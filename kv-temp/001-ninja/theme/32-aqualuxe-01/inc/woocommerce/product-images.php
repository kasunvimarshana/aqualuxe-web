<?php
/**
 * AquaLuxe Product Image Optimization
 *
 * Optimizes product images for better performance
 *
 * @package AquaLuxe
 * @since 1.2.0
 */

namespace AquaLuxe\WooCommerce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product Image Optimization Class
 *
 * Handles product image optimization functionality.
 *
 * @since 1.2.0
 */
class Product_Images {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// Check if WooCommerce is active.
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		// Register hooks.
		$this->register_hooks();
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Add lazy loading to product images.
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'add_lazy_loading' ), 10, 3 );

		// Add WebP support for product images.
		add_filter( 'wp_calculate_image_srcset', array( $this, 'add_webp_srcset' ), 10, 5 );

		// Add image preloading for featured products.
		add_action( 'wp_head', array( $this, 'preload_featured_product_images' ), 5 );

		// Add responsive image sizes.
		add_filter( 'woocommerce_get_image_size_thumbnail', array( $this, 'customize_thumbnail_image_size' ) );
		add_filter( 'woocommerce_get_image_size_single', array( $this, 'customize_single_image_size' ) );
		add_filter( 'woocommerce_get_image_size_gallery_thumbnail', array( $this, 'customize_gallery_thumbnail_image_size' ) );

		// Add custom image sizes for responsive images.
		add_action( 'after_setup_theme', array( $this, 'add_custom_image_sizes' ) );

		// Add image quality filter.
		add_filter( 'jpeg_quality', array( $this, 'customize_image_quality' ) );
		add_filter( 'wp_editor_set_quality', array( $this, 'customize_image_quality' ) );

		// Add LQIP (Low Quality Image Placeholder) support.
		add_filter( 'woocommerce_product_get_image', array( $this, 'add_lqip_support' ), 10, 6 );

		// Add image dimensions to prevent layout shifts.
		add_filter( 'woocommerce_product_get_image', array( $this, 'add_image_dimensions' ), 20, 6 );

		// Optimize gallery images.
		add_filter( 'woocommerce_single_product_image_gallery_classes', array( $this, 'add_gallery_classes' ) );
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'add_gallery_preloading' ), 5 );

		// Add custom image sizes to srcset.
		add_filter( 'wp_calculate_image_sizes', array( $this, 'customize_image_sizes' ), 10, 5 );

		// Enqueue scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Add lazy loading to product images.
	 *
	 * @param array        $attr       Attributes for the image markup.
	 * @param WP_Post      $attachment Image attachment post.
	 * @param string|array $size       Requested size.
	 * @return array
	 */
	public function add_lazy_loading( $attr, $attachment, $size ) {
		// Skip lazy loading for admin.
		if ( is_admin() ) {
			return $attr;
		}

		// Skip lazy loading for specific image sizes.
		$skip_sizes = array( 'woocommerce_thumbnail', 'woocommerce_gallery_thumbnail' );
		if ( in_array( $size, $skip_sizes, true ) ) {
			return $attr;
		}

		// Add lazy loading attribute.
		$attr['loading'] = 'lazy';

		// Add data-src attribute for custom lazy loading.
		if ( isset( $attr['src'] ) ) {
			$attr['data-src'] = $attr['src'];
		}

		// Add data-srcset attribute for custom lazy loading.
		if ( isset( $attr['srcset'] ) ) {
			$attr['data-srcset'] = $attr['srcset'];
		}

		// Add placeholder image for custom lazy loading.
		if ( get_theme_mod( 'aqualuxe_use_custom_lazy_loading', false ) ) {
			$placeholder = $this->get_placeholder_image( $attachment->ID, $size );
			$attr['src'] = $placeholder;
			unset( $attr['srcset'] );
			$attr['class'] = isset( $attr['class'] ) ? $attr['class'] . ' aqualuxe-lazy-image' : 'aqualuxe-lazy-image';
		}

		return $attr;
	}

	/**
	 * Get placeholder image for lazy loading.
	 *
	 * @param int          $attachment_id Attachment ID.
	 * @param string|array $size          Requested size.
	 * @return string
	 */
	private function get_placeholder_image( $attachment_id, $size ) {
		// Get image dimensions.
		$dimensions = $this->get_image_dimensions( $attachment_id, $size );
		$width = $dimensions['width'];
		$height = $dimensions['height'];

		// Create SVG placeholder.
		$placeholder = 'data:image/svg+xml;charset=utf-8,%3Csvg xmlns%3D\'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg\' viewBox%3D\'0 0 ' . $width . ' ' . $height . '\'%2F%3E';

		return $placeholder;
	}

	/**
	 * Get image dimensions.
	 *
	 * @param int          $attachment_id Attachment ID.
	 * @param string|array $size          Requested size.
	 * @return array
	 */
	private function get_image_dimensions( $attachment_id, $size ) {
		$dimensions = array(
			'width'  => 300,
			'height' => 300,
		);

		// Get image dimensions from attachment metadata.
		$metadata = wp_get_attachment_metadata( $attachment_id );
		if ( is_array( $metadata ) ) {
			if ( is_array( $size ) && isset( $size[0] ) && isset( $size[1] ) ) {
				$dimensions['width'] = $size[0];
				$dimensions['height'] = $size[1];
			} elseif ( is_string( $size ) && isset( $metadata['sizes'][ $size ] ) ) {
				$dimensions['width'] = $metadata['sizes'][ $size ]['width'];
				$dimensions['height'] = $metadata['sizes'][ $size ]['height'];
			} elseif ( isset( $metadata['width'] ) && isset( $metadata['height'] ) ) {
				$dimensions['width'] = $metadata['width'];
				$dimensions['height'] = $metadata['height'];
			}
		}

		return $dimensions;
	}

	/**
	 * Add WebP srcset for product images.
	 *
	 * @param array  $sources       Sources for the image srcset.
	 * @param array  $size_array    Array of width and height values.
	 * @param string $image_src     URL of the image.
	 * @param array  $image_meta    Meta data for the image.
	 * @param int    $attachment_id Attachment ID.
	 * @return array
	 */
	public function add_webp_srcset( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
		// Skip if WebP support is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_webp_support', true ) ) {
			return $sources;
		}

		// Check if browser supports WebP.
		if ( ! $this->browser_supports_webp() ) {
			return $sources;
		}

		// Add WebP sources.
		foreach ( $sources as $width => $source ) {
			$webp_url = $this->get_webp_url( $source['url'] );
			if ( $webp_url && $this->webp_exists( $webp_url ) ) {
				$sources[ $width ]['url'] = $webp_url;
			}
		}

		return $sources;
	}

	/**
	 * Check if browser supports WebP.
	 *
	 * @return bool
	 */
	private function browser_supports_webp() {
		// Check Accept header.
		if ( isset( $_SERVER['HTTP_ACCEPT'] ) && strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) !== false ) {
			return true;
		}

		// Check User-Agent header.
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$user_agent = $_SERVER['HTTP_USER_AGENT'];

			// Chrome 9+, Opera 12+, Firefox 65+.
			if ( preg_match( '/Chrome\/([0-9]+)/', $user_agent, $matches ) && $matches[1] >= 9 ) {
				return true;
			}

			if ( preg_match( '/Opera\/([0-9]+)/', $user_agent, $matches ) && $matches[1] >= 12 ) {
				return true;
			}

			if ( preg_match( '/Firefox\/([0-9]+)/', $user_agent, $matches ) && $matches[1] >= 65 ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get WebP URL for an image.
	 *
	 * @param string $url Image URL.
	 * @return string|false
	 */
	private function get_webp_url( $url ) {
		// Check if URL is valid.
		if ( ! $url ) {
			return false;
		}

		// Check if URL is already a WebP image.
		if ( strpos( $url, '.webp' ) !== false ) {
			return $url;
		}

		// Replace file extension with .webp.
		$webp_url = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $url );

		return $webp_url;
	}

	/**
	 * Check if WebP image exists.
	 *
	 * @param string $url WebP image URL.
	 * @return bool
	 */
	private function webp_exists( $url ) {
		// Convert URL to file path.
		$file_path = $this->url_to_path( $url );

		// Check if file exists.
		if ( $file_path && file_exists( $file_path ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Convert URL to file path.
	 *
	 * @param string $url Image URL.
	 * @return string|false
	 */
	private function url_to_path( $url ) {
		// Get upload directory info.
		$upload_dir = wp_upload_dir();

		// Check if URL is in upload directory.
		if ( strpos( $url, $upload_dir['baseurl'] ) !== false ) {
			return str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $url );
		}

		return false;
	}

	/**
	 * Preload featured product images.
	 *
	 * @return void
	 */
	public function preload_featured_product_images() {
		// Skip if not on shop page.
		if ( ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
			return;
		}

		// Skip if preloading is disabled.
		if ( ! get_theme_mod( 'aqualuxe_preload_featured_images', true ) ) {
			return;
		}

		// Get featured products.
		$featured_products = $this->get_featured_products();

		// Preload featured product images.
		foreach ( $featured_products as $product ) {
			$image_id = $product->get_image_id();
			if ( $image_id ) {
				$image_url = wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' );
				if ( $image_url ) {
					echo '<link rel="preload" href="' . esc_url( $image_url ) . '" as="image">';
				}
			}
		}
	}

	/**
	 * Get featured products.
	 *
	 * @param int $limit Number of products to get.
	 * @return array
	 */
	private function get_featured_products( $limit = 4 ) {
		$args = array(
			'featured'   => true,
			'visibility' => 'catalog',
			'limit'      => $limit,
			'orderby'    => 'date',
			'order'      => 'DESC',
			'return'     => 'objects',
		);

		return wc_get_products( $args );
	}

	/**
	 * Customize thumbnail image size.
	 *
	 * @param array $size Image size.
	 * @return array
	 */
	public function customize_thumbnail_image_size( $size ) {
		$size['width'] = get_theme_mod( 'aqualuxe_product_thumbnail_width', 300 );
		$size['height'] = get_theme_mod( 'aqualuxe_product_thumbnail_height', 300 );
		$size['crop'] = get_theme_mod( 'aqualuxe_product_thumbnail_crop', 1 ) ? true : false;

		return $size;
	}

	/**
	 * Customize single image size.
	 *
	 * @param array $size Image size.
	 * @return array
	 */
	public function customize_single_image_size( $size ) {
		$size['width'] = get_theme_mod( 'aqualuxe_product_single_width', 600 );
		$size['height'] = get_theme_mod( 'aqualuxe_product_single_height', 600 );
		$size['crop'] = get_theme_mod( 'aqualuxe_product_single_crop', 0 ) ? true : false;

		return $size;
	}

	/**
	 * Customize gallery thumbnail image size.
	 *
	 * @param array $size Image size.
	 * @return array
	 */
	public function customize_gallery_thumbnail_image_size( $size ) {
		$size['width'] = get_theme_mod( 'aqualuxe_product_gallery_thumbnail_width', 100 );
		$size['height'] = get_theme_mod( 'aqualuxe_product_gallery_thumbnail_height', 100 );
		$size['crop'] = get_theme_mod( 'aqualuxe_product_gallery_thumbnail_crop', 1 ) ? true : false;

		return $size;
	}

	/**
	 * Add custom image sizes for responsive images.
	 *
	 * @return void
	 */
	public function add_custom_image_sizes() {
		// Add custom image sizes.
		add_image_size( 'woocommerce_thumbnail_small', 150, 150, true );
		add_image_size( 'woocommerce_thumbnail_medium', 300, 300, true );
		add_image_size( 'woocommerce_thumbnail_large', 450, 450, true );
		add_image_size( 'woocommerce_single_small', 300, 300, false );
		add_image_size( 'woocommerce_single_medium', 600, 600, false );
		add_image_size( 'woocommerce_single_large', 900, 900, false );
	}

	/**
	 * Customize image quality.
	 *
	 * @param int $quality Image quality.
	 * @return int
	 */
	public function customize_image_quality( $quality ) {
		return get_theme_mod( 'aqualuxe_image_quality', 82 );
	}

	/**
	 * Add LQIP (Low Quality Image Placeholder) support.
	 *
	 * @param string $html    Image HTML.
	 * @param object $product Product object.
	 * @param string $size    Image size.
	 * @param array  $attr    Image attributes.
	 * @param bool   $placeholder Use placeholder image.
	 * @param string $image   Image URL.
	 * @return string
	 */
	public function add_lqip_support( $html, $product, $size, $attr, $placeholder, $image ) {
		// Skip if LQIP is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_lqip', false ) ) {
			return $html;
		}

		// Skip if product has no image.
		if ( ! $product->get_image_id() ) {
			return $html;
		}

		// Get image ID.
		$image_id = $product->get_image_id();

		// Get LQIP.
		$lqip = $this->get_lqip( $image_id );

		// Add LQIP to image HTML.
		if ( $lqip ) {
			$html = str_replace( 'src="', 'src="' . esc_url( $lqip ) . '" data-src="', $html );
			$html = str_replace( 'srcset="', 'data-srcset="', $html );
			$html = str_replace( 'class="', 'class="aqualuxe-lqip ', $html );
		}

		return $html;
	}

	/**
	 * Get LQIP (Low Quality Image Placeholder) for an image.
	 *
	 * @param int $image_id Image ID.
	 * @return string|false
	 */
	private function get_lqip( $image_id ) {
		// Get image URL.
		$image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );

		// Check if image URL is valid.
		if ( ! $image_url ) {
			return false;
		}

		// Get image dimensions.
		$dimensions = $this->get_image_dimensions( $image_id, 'thumbnail' );
		$width = $dimensions['width'];
		$height = $dimensions['height'];

		// Create SVG placeholder.
		$lqip = 'data:image/svg+xml;charset=utf-8,%3Csvg xmlns%3D\'http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg\' viewBox%3D\'0 0 ' . $width . ' ' . $height . '\'%2F%3E';

		return $lqip;
	}

	/**
	 * Add image dimensions to prevent layout shifts.
	 *
	 * @param string $html    Image HTML.
	 * @param object $product Product object.
	 * @param string $size    Image size.
	 * @param array  $attr    Image attributes.
	 * @param bool   $placeholder Use placeholder image.
	 * @param string $image   Image URL.
	 * @return string
	 */
	public function add_image_dimensions( $html, $product, $size, $attr, $placeholder, $image ) {
		// Skip if product has no image.
		if ( ! $product->get_image_id() ) {
			return $html;
		}

		// Get image ID.
		$image_id = $product->get_image_id();

		// Get image dimensions.
		$dimensions = $this->get_image_dimensions( $image_id, $size );
		$width = $dimensions['width'];
		$height = $dimensions['height'];

		// Add width and height attributes if not present.
		if ( strpos( $html, 'width=' ) === false && strpos( $html, 'height=' ) === false ) {
			$html = str_replace( '<img ', '<img width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '" ', $html );
		}

		return $html;
	}

	/**
	 * Add gallery classes.
	 *
	 * @param array $classes Gallery classes.
	 * @return array
	 */
	public function add_gallery_classes( $classes ) {
		// Add custom gallery classes.
		$classes[] = 'aqualuxe-product-gallery';
		$classes[] = 'aqualuxe-product-gallery-' . get_theme_mod( 'aqualuxe_product_gallery_style', 'horizontal' );

		return $classes;
	}

	/**
	 * Add gallery preloading.
	 *
	 * @return void
	 */
	public function add_gallery_preloading() {
		// Skip if not on product page.
		if ( ! is_product() ) {
			return;
		}

		// Skip if preloading is disabled.
		if ( ! get_theme_mod( 'aqualuxe_preload_gallery_images', true ) ) {
			return;
		}

		// Get product.
		global $product;

		// Skip if product has no gallery images.
		if ( ! $product->get_gallery_image_ids() ) {
			return;
		}

		// Get gallery image IDs.
		$gallery_image_ids = $product->get_gallery_image_ids();

		// Limit to 3 images.
		$gallery_image_ids = array_slice( $gallery_image_ids, 0, 3 );

		// Preload gallery images.
		foreach ( $gallery_image_ids as $image_id ) {
			$image_url = wp_get_attachment_image_url( $image_id, 'woocommerce_single' );
			if ( $image_url ) {
				echo '<link rel="preload" href="' . esc_url( $image_url ) . '" as="image">';
			}
		}
	}

	/**
	 * Customize image sizes.
	 *
	 * @param string       $sizes         Sizes attribute.
	 * @param array|string $size          Requested size.
	 * @param string       $image_src     Image URL.
	 * @param array        $image_meta    Image meta data.
	 * @param int          $attachment_id Attachment ID.
	 * @return string
	 */
	public function customize_image_sizes( $sizes, $size, $image_src, $image_meta, $attachment_id ) {
		// Customize sizes for product images.
		if ( is_product() ) {
			if ( $size === 'woocommerce_single' ) {
				$sizes = '(max-width: 600px) 100vw, (max-width: 900px) 600px, 900px';
			}
		} elseif ( is_shop() || is_product_category() || is_product_tag() ) {
			if ( $size === 'woocommerce_thumbnail' ) {
				$sizes = '(max-width: 480px) 100vw, (max-width: 768px) 50vw, (max-width: 1024px) 33.3vw, 25vw';
			}
		}

		return $sizes;
	}

	/**
	 * Enqueue scripts.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		// Enqueue custom lazy loading script.
		if ( get_theme_mod( 'aqualuxe_use_custom_lazy_loading', false ) ) {
			wp_enqueue_script(
				'aqualuxe-lazy-load',
				AQUALUXE_URI . '/assets/js/lazy-load.js',
				array( 'jquery' ),
				AQUALUXE_VERSION,
				true
			);
		}

		// Enqueue LQIP script.
		if ( get_theme_mod( 'aqualuxe_enable_lqip', false ) ) {
			wp_enqueue_script(
				'aqualuxe-lqip',
				AQUALUXE_URI . '/assets/js/lqip.js',
				array( 'jquery' ),
				AQUALUXE_VERSION,
				true
			);
		}
	}
}