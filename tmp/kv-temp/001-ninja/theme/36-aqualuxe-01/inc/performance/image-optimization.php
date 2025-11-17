<?php
/**
 * Image Optimization Functions
 *
 * Functions for optimizing images in the theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add responsive image support with srcset and sizes attributes.
 */
function aqualuxe_responsive_images_setup() {
	// Add theme support for responsive images.
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	
	// Define custom image sizes.
	add_image_size( 'aqualuxe-hero', 1920, 800, true );
	add_image_size( 'aqualuxe-featured', 1200, 675, true );
	add_image_size( 'aqualuxe-card', 600, 400, true );
	add_image_size( 'aqualuxe-thumbnail', 300, 300, true );
	add_image_size( 'aqualuxe-logo', 250, 100, false );
}
add_action( 'after_setup_theme', 'aqualuxe_responsive_images_setup' );

/**
 * Add custom image sizes to media library dropdown.
 *
 * @param array $sizes Array of image sizes.
 * @return array Modified array of image sizes.
 */
function aqualuxe_custom_image_sizes_names( $sizes ) {
	return array_merge( $sizes, array(
		'aqualuxe-hero'      => __( 'Hero Image', 'aqualuxe' ),
		'aqualuxe-featured'  => __( 'Featured Image', 'aqualuxe' ),
		'aqualuxe-card'      => __( 'Card Image', 'aqualuxe' ),
		'aqualuxe-thumbnail' => __( 'Thumbnail Square', 'aqualuxe' ),
		'aqualuxe-logo'      => __( 'Logo Size', 'aqualuxe' ),
	) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes_names' );

/**
 * Implement lazy loading for images.
 *
 * @param array $attr Array of attribute values for the image markup.
 * @param WP_Post $attachment Image attachment post.
 * @param string|array $size Requested size.
 * @return array Modified array of attribute values.
 */
function aqualuxe_lazy_load_attributes( $attr, $attachment, $size ) {
	// Skip lazy loading for specific image sizes or classes.
	$skip_lazy_classes = array( 'no-lazy', 'skip-lazy', 'hero-image' );
	
	// Check if image already has a class that should skip lazy loading.
	if ( isset( $attr['class'] ) ) {
		$classes = explode( ' ', $attr['class'] );
		foreach ( $skip_lazy_classes as $skip_class ) {
			if ( in_array( $skip_class, $classes, true ) ) {
				return $attr;
			}
		}
	}
	
	// Add loading="lazy" attribute for images.
	// Note: WordPress 5.5+ adds this automatically, but we add it for backward compatibility.
	$attr['loading'] = 'lazy';
	
	// Add decoding="async" attribute for images.
	$attr['decoding'] = 'async';
	
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_lazy_load_attributes', 10, 3 );

/**
 * Add image dimensions to img tags that are missing them.
 *
 * @param string $content The content to be filtered.
 * @return string Modified content.
 */
function aqualuxe_add_image_dimensions( $content ) {
	// Skip if admin or feed.
	if ( is_admin() || is_feed() ) {
		return $content;
	}
	
	// Skip if content is empty.
	if ( empty( $content ) ) {
		return $content;
	}
	
	// Find all img tags without width and height attributes.
	$content = preg_replace_callback( '/<img((?![^>]*width=)[^>]*)>/i', 'aqualuxe_add_image_dimensions_callback', $content );
	
	return $content;
}
add_filter( 'the_content', 'aqualuxe_add_image_dimensions', 15 );

/**
 * Callback function for adding image dimensions.
 *
 * @param array $matches Array of matches from preg_replace_callback.
 * @return string Modified image tag.
 */
function aqualuxe_add_image_dimensions_callback( $matches ) {
	$image_tag = $matches[0];
	$image_attributes = $matches[1];
	
	// Extract src attribute.
	preg_match( '/src=["\'](.*?)["\']/i', $image_attributes, $src_matches );
	
	if ( ! empty( $src_matches[1] ) ) {
		$image_src = $src_matches[1];
		
		// Get image dimensions.
		$image_path = parse_url( $image_src, PHP_URL_PATH );
		$upload_dir = wp_upload_dir();
		$image_file = $upload_dir['basedir'] . $image_path;
		
		if ( file_exists( $image_file ) ) {
			$image_size = getimagesize( $image_file );
			
			if ( $image_size ) {
				$width = $image_size[0];
				$height = $image_size[1];
				
				// Add width and height attributes.
				$image_tag = str_replace( '<img', '<img width="' . $width . '" height="' . $height . '"', $image_tag );
			}
		}
	}
	
	return $image_tag;
}

/**
 * Optimize image quality for JPEG images.
 *
 * @param array $compression_settings Image compression settings.
 * @return array Modified compression settings.
 */
function aqualuxe_jpeg_quality( $compression_settings ) {
	// Set JPEG quality (0-100).
	$compression_settings['quality'] = 82;
	
	return $compression_settings;
}
add_filter( 'jpeg_quality', 'aqualuxe_jpeg_quality' );
add_filter( 'wp_editor_set_quality', 'aqualuxe_jpeg_quality' );

/**
 * Add WebP support for image uploads.
 *
 * @param array $mime_types Array of allowed mime types.
 * @return array Modified array of allowed mime types.
 */
function aqualuxe_add_webp_mime_type( $mime_types ) {
	$mime_types['webp'] = 'image/webp';
	
	return $mime_types;
}
add_filter( 'upload_mimes', 'aqualuxe_add_webp_mime_type' );

/**
 * Generate WebP versions of uploaded images.
 *
 * @param array $metadata Image metadata.
 * @param int $attachment_id Attachment ID.
 * @return array Modified image metadata.
 */
function aqualuxe_generate_webp_images( $metadata, $attachment_id ) {
	// Skip if not an image.
	if ( ! isset( $metadata['file'] ) || ! isset( $metadata['sizes'] ) ) {
		return $metadata;
	}
	
	// Get file path.
	$file = get_attached_file( $attachment_id );
	$path_parts = pathinfo( $file );
	$file_dir = $path_parts['dirname'];
	
	// Skip if already a WebP image.
	if ( isset( $path_parts['extension'] ) && 'webp' === strtolower( $path_parts['extension'] ) ) {
		return $metadata;
	}
	
	// Check if GD supports WebP.
	if ( ! function_exists( 'imagewebp' ) ) {
		return $metadata;
	}
	
	// Generate WebP version for each size.
	foreach ( $metadata['sizes'] as $size => $size_data ) {
		// Get image path.
		$image_path = $file_dir . '/' . $size_data['file'];
		
		// Skip if file doesn't exist.
		if ( ! file_exists( $image_path ) ) {
			continue;
		}
		
		// Get image extension.
		$path_parts = pathinfo( $image_path );
		$extension = isset( $path_parts['extension'] ) ? strtolower( $path_parts['extension'] ) : '';
		
		// Skip if not a supported image type.
		if ( ! in_array( $extension, array( 'jpg', 'jpeg', 'png' ), true ) ) {
			continue;
		}
		
		// Create WebP file path.
		$webp_path = $path_parts['dirname'] . '/' . $path_parts['filename'] . '.webp';
		
		// Load image based on type.
		switch ( $extension ) {
			case 'jpg':
			case 'jpeg':
				$image = imagecreatefromjpeg( $image_path );
				break;
			case 'png':
				$image = imagecreatefrompng( $image_path );
				// Handle transparency.
				imagepalettetotruecolor( $image );
				imagealphablending( $image, true );
				imagesavealpha( $image, true );
				break;
			default:
				$image = false;
		}
		
		// Skip if image couldn't be loaded.
		if ( ! $image ) {
			continue;
		}
		
		// Save WebP version.
		imagewebp( $image, $webp_path, 80 );
		
		// Free memory.
		imagedestroy( $image );
	}
	
	return $metadata;
}
// Uncomment this line if you want to generate WebP versions of uploaded images.
// add_filter( 'wp_generate_attachment_metadata', 'aqualuxe_generate_webp_images', 10, 2 );

/**
 * Add picture element with WebP support.
 *
 * @param string $html Image HTML.
 * @param int $attachment_id Attachment ID.
 * @param string $size Image size.
 * @param bool $icon Whether the image should be treated as an icon.
 * @param array $attr Array of attributes.
 * @return string Modified image HTML.
 */
function aqualuxe_add_picture_element( $html, $attachment_id, $size, $icon, $attr ) {
	// Skip if admin or feed.
	if ( is_admin() || is_feed() ) {
		return $html;
	}
	
	// Skip if attachment ID is not valid.
	if ( ! $attachment_id ) {
		return $html;
	}
	
	// Get file path.
	$file = get_attached_file( $attachment_id );
	$path_parts = pathinfo( $file );
	
	// Skip if already a WebP image.
	if ( isset( $path_parts['extension'] ) && 'webp' === strtolower( $path_parts['extension'] ) ) {
		return $html;
	}
	
	// Get image metadata.
	$metadata = wp_get_attachment_metadata( $attachment_id );
	
	// Skip if metadata is not valid.
	if ( ! isset( $metadata['file'] ) || ! isset( $metadata['sizes'] ) ) {
		return $html;
	}
	
	// Get image size data.
	$size_data = isset( $metadata['sizes'][ $size ] ) ? $metadata['sizes'][ $size ] : null;
	
	// Skip if size data is not valid.
	if ( ! $size_data ) {
		return $html;
	}
	
	// Get image URL.
	$image_url = wp_get_attachment_image_url( $attachment_id, $size );
	
	// Skip if URL is not valid.
	if ( ! $image_url ) {
		return $html;
	}
	
	// Create WebP URL.
	$webp_url = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $image_url );
	
	// Check if WebP file exists.
	$webp_path = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $file );
	
	if ( ! file_exists( $webp_path ) ) {
		return $html;
	}
	
	// Extract attributes from HTML.
	preg_match( '/<img([^>]+)>/i', $html, $matches );
	
	if ( ! isset( $matches[1] ) ) {
		return $html;
	}
	
	$img_attr = $matches[1];
	
	// Create picture element.
	$picture = '<picture>';
	$picture .= '<source srcset="' . esc_url( $webp_url ) . '" type="image/webp">';
	$picture .= '<img' . $img_attr . '>';
	$picture .= '</picture>';
	
	return $picture;
}
// Uncomment this line if you want to add picture element with WebP support.
// add_filter( 'wp_get_attachment_image', 'aqualuxe_add_picture_element', 10, 5 );

/**
 * Add image dimensions to content images.
 *
 * @param string $content The content to be filtered.
 * @return string Modified content.
 */
function aqualuxe_add_image_dimensions_to_content( $content ) {
	// Skip if admin or feed.
	if ( is_admin() || is_feed() ) {
		return $content;
	}
	
	// Skip if content is empty.
	if ( empty( $content ) ) {
		return $content;
	}
	
	// Find all img tags.
	$pattern = '/<img[^>]+>/i';
	$content = preg_replace_callback( $pattern, 'aqualuxe_process_image_tag', $content );
	
	return $content;
}
add_filter( 'the_content', 'aqualuxe_add_image_dimensions_to_content', 15 );

/**
 * Process image tag to add dimensions and loading attributes.
 *
 * @param array $matches Array of matches from preg_replace_callback.
 * @return string Modified image tag.
 */
function aqualuxe_process_image_tag( $matches ) {
	$img_tag = $matches[0];
	
	// Skip if already has loading attribute.
	if ( strpos( $img_tag, 'loading=' ) !== false ) {
		return $img_tag;
	}
	
	// Add loading="lazy" attribute.
	$img_tag = str_replace( '<img', '<img loading="lazy"', $img_tag );
	
	// Skip if already has decoding attribute.
	if ( strpos( $img_tag, 'decoding=' ) !== false ) {
		return $img_tag;
	}
	
	// Add decoding="async" attribute.
	$img_tag = str_replace( '<img', '<img decoding="async"', $img_tag );
	
	// Extract src attribute.
	preg_match( '/src=["\'](.*?)["\']/i', $img_tag, $src_matches );
	
	if ( ! empty( $src_matches[1] ) ) {
		$src = $src_matches[1];
		
		// Skip if already has width and height attributes.
		if ( strpos( $img_tag, 'width=' ) !== false && strpos( $img_tag, 'height=' ) !== false ) {
			return $img_tag;
		}
		
		// Get image dimensions.
		$upload_dir = wp_upload_dir();
		$src_path = str_replace( $upload_dir['baseurl'], $upload_dir['basedir'], $src );
		
		if ( file_exists( $src_path ) ) {
			$dimensions = getimagesize( $src_path );
			
			if ( $dimensions ) {
				$width = $dimensions[0];
				$height = $dimensions[1];
				
				// Add width and height attributes.
				$img_tag = str_replace( '<img', '<img width="' . $width . '" height="' . $height . '"', $img_tag );
			}
		}
	}
	
	return $img_tag;
}

/**
 * Add support for SVG uploads.
 *
 * @param array $mime_types Array of allowed mime types.
 * @return array Modified array of allowed mime types.
 */
function aqualuxe_add_svg_mime_type( $mime_types ) {
	$mime_types['svg'] = 'image/svg+xml';
	
	return $mime_types;
}
add_filter( 'upload_mimes', 'aqualuxe_add_svg_mime_type' );

/**
 * Fix SVG file upload issues.
 *
 * @param array $data Array of data for the uploaded file.
 * @param array $file Array of file data.
 * @return array Modified array of data.
 */
function aqualuxe_fix_svg_upload( $data, $file, $filename, $mimes ) {
	// Check if file is SVG.
	if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
		return $data;
	}
	
	$filetype = wp_check_filetype( $filename, $mimes );
	
	if ( 'svg' === $filetype['ext'] ) {
		$data['ext'] = 'svg';
		$data['type'] = 'image/svg+xml';
	}
	
	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'aqualuxe_fix_svg_upload', 10, 4 );

/**
 * Add SVG dimensions to media library.
 *
 * @param array $response Array of prepared attachment data.
 * @param WP_Post $attachment Attachment object.
 * @param array $meta Array of attachment meta data.
 * @return array Modified array of prepared attachment data.
 */
function aqualuxe_svg_dimensions( $response, $attachment, $meta ) {
	if ( 'image/svg+xml' === $response['mime'] ) {
		// Get SVG file path.
		$file = get_attached_file( $attachment->ID );
		
		if ( file_exists( $file ) ) {
			// Read SVG file.
			$svg = file_get_contents( $file );
			
			// Get SVG dimensions.
			preg_match( '/<svg[^>]+viewBox=["\'](.*?)["\']/i', $svg, $viewbox );
			
			if ( ! empty( $viewbox[1] ) ) {
				$viewbox = explode( ' ', $viewbox[1] );
				
				if ( count( $viewbox ) === 4 ) {
					$response['width'] = (int) $viewbox[2];
					$response['height'] = (int) $viewbox[3];
				}
			} else {
				preg_match( '/<svg[^>]+width=["\'](.*?)["\']/i', $svg, $width );
				preg_match( '/<svg[^>]+height=["\'](.*?)["\']/i', $svg, $height );
				
				if ( ! empty( $width[1] ) && ! empty( $height[1] ) ) {
					$response['width'] = (int) $width[1];
					$response['height'] = (int) $height[1];
				}
			}
		}
	}
	
	return $response;
}
add_filter( 'wp_prepare_attachment_for_js', 'aqualuxe_svg_dimensions', 10, 3 );

/**
 * Add image optimization settings to the media settings page.
 */
function aqualuxe_image_optimization_settings() {
	// Add settings section.
	add_settings_section(
		'aqualuxe_image_optimization_section',
		__( 'Image Optimization', 'aqualuxe' ),
		'aqualuxe_image_optimization_section_callback',
		'media'
	);
	
	// Add settings fields.
	add_settings_field(
		'aqualuxe_jpeg_quality',
		__( 'JPEG Quality', 'aqualuxe' ),
		'aqualuxe_jpeg_quality_callback',
		'media',
		'aqualuxe_image_optimization_section'
	);
	
	add_settings_field(
		'aqualuxe_generate_webp',
		__( 'Generate WebP Images', 'aqualuxe' ),
		'aqualuxe_generate_webp_callback',
		'media',
		'aqualuxe_image_optimization_section'
	);
	
	// Register settings.
	register_setting( 'media', 'aqualuxe_jpeg_quality', 'intval' );
	register_setting( 'media', 'aqualuxe_generate_webp', 'boolval' );
}
add_action( 'admin_init', 'aqualuxe_image_optimization_settings' );

/**
 * Settings section callback.
 */
function aqualuxe_image_optimization_section_callback() {
	echo '<p>' . esc_html__( 'Configure image optimization settings for better performance.', 'aqualuxe' ) . '</p>';
}

/**
 * JPEG quality setting callback.
 */
function aqualuxe_jpeg_quality_callback() {
	$quality = get_option( 'aqualuxe_jpeg_quality', 82 );
	echo '<input type="number" name="aqualuxe_jpeg_quality" value="' . esc_attr( $quality ) . '" min="0" max="100" step="1" class="small-text" /> ';
	echo '<p class="description">' . esc_html__( 'Set the quality of JPEG images (0-100). Higher values mean better quality but larger file size. Default is 82.', 'aqualuxe' ) . '</p>';
}

/**
 * Generate WebP setting callback.
 */
function aqualuxe_generate_webp_callback() {
	$generate_webp = get_option( 'aqualuxe_generate_webp', false );
	echo '<input type="checkbox" name="aqualuxe_generate_webp" value="1" ' . checked( $generate_webp, true, false ) . ' /> ';
	echo '<p class="description">' . esc_html__( 'Generate WebP versions of uploaded images for better performance. Requires GD library with WebP support.', 'aqualuxe' ) . '</p>';
}

/**
 * Get JPEG quality from settings.
 *
 * @return int JPEG quality.
 */
function aqualuxe_get_jpeg_quality() {
	return get_option( 'aqualuxe_jpeg_quality', 82 );
}
add_filter( 'jpeg_quality', 'aqualuxe_get_jpeg_quality' );
add_filter( 'wp_editor_set_quality', 'aqualuxe_get_jpeg_quality' );

/**
 * Check if WebP generation is enabled.
 *
 * @return bool Whether WebP generation is enabled.
 */
function aqualuxe_is_webp_enabled() {
	return get_option( 'aqualuxe_generate_webp', false );
}

/**
 * Generate WebP images if enabled.
 *
 * @param array $metadata Image metadata.
 * @param int $attachment_id Attachment ID.
 * @return array Modified image metadata.
 */
function aqualuxe_maybe_generate_webp_images( $metadata, $attachment_id ) {
	if ( aqualuxe_is_webp_enabled() ) {
		return aqualuxe_generate_webp_images( $metadata, $attachment_id );
	}
	
	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'aqualuxe_maybe_generate_webp_images', 10, 2 );