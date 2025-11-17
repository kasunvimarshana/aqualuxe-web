<?php
/**
 * Lazy Loading Implementation
 *
 * Functions for implementing lazy loading for images and iframes.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if we should add lazy loading.
 *
 * @return bool Whether to add lazy loading.
 */
function aqualuxe_should_lazy_load() {
	// Don't lazy load in admin.
	if ( is_admin() ) {
		return false;
	}

	// Don't lazy load on AMP pages.
	if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
		return false;
	}

	// Check if lazy loading is enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_lazy_loading', true ) ) {
		return false;
	}

	return true;
}

/**
 * Add lazy loading attributes to images.
 *
 * @param string $content The content to be filtered.
 * @return string The filtered content.
 */
function aqualuxe_add_image_placeholders( $content ) {
	// Check if we should add lazy loading.
	if ( ! aqualuxe_should_lazy_load() ) {
		return $content;
	}

	// Don't lazy load if the content has already been processed.
	if ( false !== strpos( $content, 'data-src' ) ) {
		return $content;
	}

	// Find all <img> tags.
	if ( preg_match_all( '/<img\s[^>]+>/', $content, $matches ) ) {
		$selected_images = array();

		foreach ( $matches[0] as $image ) {
			// Don't lazy load images that have the 'no-lazy' class.
			if ( false !== strpos( $image, 'class="' ) && false !== strpos( $image, 'no-lazy' ) ) {
				continue;
			}

			// Don't lazy load images that have the 'skip-lazy' class.
			if ( false !== strpos( $image, 'class="' ) && false !== strpos( $image, 'skip-lazy' ) ) {
				continue;
			}

			// Don't lazy load images that have the 'loading' attribute.
			if ( false !== strpos( $image, 'loading=' ) ) {
				continue;
			}

			// Add the image to the selected images array.
			$selected_images[] = $image;
		}

		// Loop through the selected images.
		foreach ( $selected_images as $selected_image ) {
			// Add loading="lazy" attribute.
			$replacement_image = str_replace( '<img', '<img loading="lazy"', $selected_image );

			// Add decoding="async" attribute.
			$replacement_image = str_replace( '<img', '<img decoding="async"', $replacement_image );

			// Replace the image in the content.
			$content = str_replace( $selected_image, $replacement_image, $content );
		}
	}

	return $content;
}
add_filter( 'the_content', 'aqualuxe_add_image_placeholders', 99 );
add_filter( 'post_thumbnail_html', 'aqualuxe_add_image_placeholders', 99 );
add_filter( 'get_avatar', 'aqualuxe_add_image_placeholders', 99 );
add_filter( 'widget_text', 'aqualuxe_add_image_placeholders', 99 );
add_filter( 'get_image_tag', 'aqualuxe_add_image_placeholders', 99 );

/**
 * Add lazy loading attributes to iframes.
 *
 * @param string $content The content to be filtered.
 * @return string The filtered content.
 */
function aqualuxe_add_iframe_placeholders( $content ) {
	// Check if we should add lazy loading.
	if ( ! aqualuxe_should_lazy_load() ) {
		return $content;
	}

	// Don't lazy load if the content has already been processed.
	if ( false !== strpos( $content, 'data-src' ) ) {
		return $content;
	}

	// Find all <iframe> tags.
	if ( preg_match_all( '/<iframe\s[^>]+>/', $content, $matches ) ) {
		$selected_iframes = array();

		foreach ( $matches[0] as $iframe ) {
			// Don't lazy load iframes that have the 'no-lazy' class.
			if ( false !== strpos( $iframe, 'class="' ) && false !== strpos( $iframe, 'no-lazy' ) ) {
				continue;
			}

			// Don't lazy load iframes that have the 'skip-lazy' class.
			if ( false !== strpos( $iframe, 'class="' ) && false !== strpos( $iframe, 'skip-lazy' ) ) {
				continue;
			}

			// Don't lazy load iframes that have the 'loading' attribute.
			if ( false !== strpos( $iframe, 'loading=' ) ) {
				continue;
			}

			// Add the iframe to the selected iframes array.
			$selected_iframes[] = $iframe;
		}

		// Loop through the selected iframes.
		foreach ( $selected_iframes as $selected_iframe ) {
			// Add loading="lazy" attribute.
			$replacement_iframe = str_replace( '<iframe', '<iframe loading="lazy"', $selected_iframe );

			// Replace the iframe in the content.
			$content = str_replace( $selected_iframe, $replacement_iframe, $content );
		}
	}

	return $content;
}
add_filter( 'the_content', 'aqualuxe_add_iframe_placeholders', 99 );
add_filter( 'widget_text', 'aqualuxe_add_iframe_placeholders', 99 );

/**
 * Add lazy loading attributes to featured images.
 *
 * @param array        $attr       Array of attribute values for the image markup.
 * @param WP_Post      $attachment Image attachment post.
 * @param string|array $size       Requested size.
 * @return array Modified array of attribute values.
 */
function aqualuxe_lazy_load_featured_images( $attr, $attachment, $size ) {
	// Check if we should add lazy loading.
	if ( ! aqualuxe_should_lazy_load() ) {
		return $attr;
	}

	// Don't lazy load if the image already has the loading attribute.
	if ( isset( $attr['loading'] ) ) {
		return $attr;
	}

	// Add loading="lazy" attribute.
	$attr['loading'] = 'lazy';

	// Add decoding="async" attribute.
	$attr['decoding'] = 'async';

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_lazy_load_featured_images', 10, 3 );

/**
 * Add responsive image attributes.
 *
 * @param array        $attr       Array of attribute values for the image markup.
 * @param WP_Post      $attachment Image attachment post.
 * @param string|array $size       Requested size.
 * @return array Modified array of attribute values.
 */
function aqualuxe_responsive_image_attributes( $attr, $attachment, $size ) {
	// Check if responsive images are enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_responsive_images', true ) ) {
		return $attr;
	}

	// Get attachment metadata.
	$image_meta = wp_get_attachment_metadata( $attachment->ID );

	// Check if we have the metadata.
	if ( ! $image_meta ) {
		return $attr;
	}

	// Check if we have the sizes.
	if ( ! isset( $image_meta['sizes'] ) ) {
		return $attr;
	}

	// Get the width of the current size.
	$width = 0;
	if ( is_array( $size ) ) {
		$width = $size[0];
	} elseif ( isset( $image_meta['sizes'][ $size ] ) ) {
		$width = $image_meta['sizes'][ $size ]['width'];
	} elseif ( isset( $image_meta['width'] ) ) {
		$width = $image_meta['width'];
	}

	// If we don't have a width, return.
	if ( ! $width ) {
		return $attr;
	}

	// Build the srcset attribute.
	$srcset = array();
	$sizes = array();

	// Add the full size.
	$srcset[] = $image_meta['file'] . ' ' . $image_meta['width'] . 'w';

	// Add the other sizes.
	foreach ( $image_meta['sizes'] as $size_name => $size_data ) {
		$srcset[] = $size_data['file'] . ' ' . $size_data['width'] . 'w';
	}

	// Add the srcset attribute.
	$attr['srcset'] = implode( ', ', $srcset );

	// Add the sizes attribute.
	$attr['sizes'] = '(max-width: ' . $width . 'px) 100vw, ' . $width . 'px';

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_responsive_image_attributes', 10, 3 );

/**
 * Add custom image sizes.
 */
function aqualuxe_add_image_sizes() {
	// Add custom image sizes.
	add_image_size( 'aqualuxe-featured', 1200, 675, true );
	add_image_size( 'aqualuxe-card', 600, 400, true );
	add_image_size( 'aqualuxe-thumbnail', 300, 200, true );
	add_image_size( 'aqualuxe-hero', 1920, 1080, true );
}
add_action( 'after_setup_theme', 'aqualuxe_add_image_sizes' );

/**
 * Add custom image sizes to media library.
 *
 * @param array $sizes Array of image sizes.
 * @return array Modified array of image sizes.
 */
function aqualuxe_custom_image_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'aqualuxe-featured' => __( 'Featured Image', 'aqualuxe' ),
		'aqualuxe-card' => __( 'Card Image', 'aqualuxe' ),
		'aqualuxe-thumbnail' => __( 'Thumbnail Image', 'aqualuxe' ),
		'aqualuxe-hero' => __( 'Hero Image', 'aqualuxe' ),
	) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );

/**
 * Add WebP support.
 */
function aqualuxe_webp_upload_mimes( $existing_mimes ) {
	// Add WebP to the list of allowed mime types.
	$existing_mimes['webp'] = 'image/webp';

	return $existing_mimes;
}
add_filter( 'mime_types', 'aqualuxe_webp_upload_mimes' );

/**
 * Enable WebP uploads.
 *
 * @param array $types Array of allowed mime types.
 * @return array Modified array of allowed mime types.
 */
function aqualuxe_webp_is_displayable( $types, $mime = '' ) {
	// Add WebP to the list of allowed mime types.
	if ( 'image/webp' === $mime ) {
		$types[] = 'webp';
	}

	return $types;
}
add_filter( 'file_is_displayable_image', 'aqualuxe_webp_is_displayable', 10, 2 );

/**
 * Add WebP support to the media library.
 *
 * @param array $result Array of file type data.
 * @param string $file Full path to the file.
 * @param string $filename The name of the file.
 * @param array $mimes Array of allowed mime types.
 * @param string $real_mime Real mime type of the file.
 * @return array Modified array of file type data.
 */
function aqualuxe_webp_file_types( $result, $file, $filename, $mimes, $real_mime ) {
	// Check if the file is a WebP image.
	if ( 'image/webp' === $real_mime ) {
		// Allow WebP uploads.
		$result['ext'] = 'webp';
		$result['type'] = 'image/webp';
	}

	return $result;
}
add_filter( 'wp_check_filetype_and_ext', 'aqualuxe_webp_file_types', 10, 5 );