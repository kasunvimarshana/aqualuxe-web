<?php
/**
 * AquaLuxe Theme Image Optimizations
 *
 * Handles various image optimizations for the theme.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Add image sizes.
 */
function aqualuxe_add_image_sizes() {
	// Add custom image sizes.
	add_image_size( 'aqualuxe-featured', 1600, 900, true );
	add_image_size( 'aqualuxe-card', 600, 400, true );
	add_image_size( 'aqualuxe-thumbnail', 300, 300, true );
	add_image_size( 'aqualuxe-square', 800, 800, true );
	add_image_size( 'aqualuxe-portrait', 600, 900, true );
	add_image_size( 'aqualuxe-landscape', 900, 600, true );
	add_image_size( 'aqualuxe-hero', 1920, 1080, true );
	add_image_size( 'aqualuxe-slider', 1200, 600, true );
}
add_action( 'after_setup_theme', 'aqualuxe_add_image_sizes' );

/**
 * Add custom image sizes to media library.
 *
 * @param array $sizes Image sizes.
 * @return array Modified image sizes.
 */
function aqualuxe_custom_image_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'aqualuxe-featured'   => __( 'Featured Image', 'aqualuxe' ),
		'aqualuxe-card'       => __( 'Card Image', 'aqualuxe' ),
		'aqualuxe-thumbnail'  => __( 'Thumbnail Image', 'aqualuxe' ),
		'aqualuxe-square'     => __( 'Square Image', 'aqualuxe' ),
		'aqualuxe-portrait'   => __( 'Portrait Image', 'aqualuxe' ),
		'aqualuxe-landscape'  => __( 'Landscape Image', 'aqualuxe' ),
		'aqualuxe-hero'       => __( 'Hero Image', 'aqualuxe' ),
		'aqualuxe-slider'     => __( 'Slider Image', 'aqualuxe' ),
	) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_custom_image_sizes' );

/**
 * Optimize JPEG quality.
 *
 * @param int $quality JPEG quality.
 * @return int Modified JPEG quality.
 */
function aqualuxe_jpeg_quality( $quality ) {
	// Check if JPEG quality optimization is enabled in theme options.
	$optimize_jpeg_quality = get_theme_mod( 'aqualuxe_optimize_jpeg_quality', true );
	
	if ( ! $optimize_jpeg_quality ) {
		return $quality;
	}
	
	// Get the JPEG quality from theme options.
	$jpeg_quality = get_theme_mod( 'aqualuxe_jpeg_quality', 82 );
	
	return $jpeg_quality;
}
add_filter( 'jpeg_quality', 'aqualuxe_jpeg_quality' );
add_filter( 'wp_editor_set_quality', 'aqualuxe_jpeg_quality' );

/**
 * Add responsive image attributes.
 *
 * @param array $attr Image attributes.
 * @return array Modified image attributes.
 */
function aqualuxe_responsive_image_attributes( $attr ) {
	// Check if responsive images are enabled in theme options.
	$enable_responsive_images = get_theme_mod( 'aqualuxe_enable_responsive_images', true );
	
	if ( ! $enable_responsive_images ) {
		return $attr;
	}
	
	// Add loading attribute if it doesn't exist.
	if ( ! isset( $attr['loading'] ) ) {
		$attr['loading'] = 'lazy';
	}
	
	// Add decoding attribute if it doesn't exist.
	if ( ! isset( $attr['decoding'] ) ) {
		$attr['decoding'] = 'async';
	}
	
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_responsive_image_attributes' );

/**
 * Add WebP support.
 */
function aqualuxe_webp_upload_mimes( $mimes ) {
	// Check if WebP support is enabled in theme options.
	$enable_webp = get_theme_mod( 'aqualuxe_enable_webp', true );
	
	if ( ! $enable_webp ) {
		return $mimes;
	}
	
	// Add WebP to allowed mime types.
	$mimes['webp'] = 'image/webp';
	
	return $mimes;
}
add_filter( 'upload_mimes', 'aqualuxe_webp_upload_mimes' );

/**
 * Add WebP to allowed image types.
 *
 * @param array $types Allowed image types.
 * @return array Modified allowed image types.
 */
function aqualuxe_webp_is_displayable( $types, $mime ) {
	// Check if WebP support is enabled in theme options.
	$enable_webp = get_theme_mod( 'aqualuxe_enable_webp', true );
	
	if ( ! $enable_webp ) {
		return $types;
	}
	
	// Add WebP to allowed image types.
	if ( 'image/webp' === $mime ) {
		$types[] = 'browser';
	}
	
	return $types;
}
add_filter( 'file_is_displayable_image', 'aqualuxe_webp_is_displayable', 10, 2 );

/**
 * Add SVG support.
 */
function aqualuxe_svg_upload_mimes( $mimes ) {
	// Check if SVG support is enabled in theme options.
	$enable_svg = get_theme_mod( 'aqualuxe_enable_svg', true );
	
	if ( ! $enable_svg ) {
		return $mimes;
	}
	
	// Add SVG to allowed mime types.
	$mimes['svg'] = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
	
	return $mimes;
}
add_filter( 'upload_mimes', 'aqualuxe_svg_upload_mimes' );

/**
 * Fix SVG dimensions.
 *
 * @param array $response Response data.
 * @param object $attachment Attachment object.
 * @param array $meta Attachment meta.
 * @return array Modified response data.
 */
function aqualuxe_svg_dimensions( $response, $attachment, $meta ) {
	// Check if SVG support is enabled in theme options.
	$enable_svg = get_theme_mod( 'aqualuxe_enable_svg', true );
	
	if ( ! $enable_svg ) {
		return $response;
	}
	
	// Check if the attachment is an SVG.
	if ( 'image/svg+xml' === $response['mime'] ) {
		// Get the SVG file path.
		$svg_file_path = get_attached_file( $attachment->ID );
		
		// Check if the file exists.
		if ( ! file_exists( $svg_file_path ) ) {
			return $response;
		}
		
		// Get the SVG dimensions.
		$svg = simplexml_load_file( $svg_file_path );
		
		if ( $svg ) {
			$attributes = $svg->attributes();
			$viewbox = (string) $attributes->viewBox;
			
			if ( $viewbox ) {
				$viewbox_array = explode( ' ', $viewbox );
				
				if ( 4 === count( $viewbox_array ) ) {
					$width = (int) $viewbox_array[2];
					$height = (int) $viewbox_array[3];
					
					if ( $width && $height ) {
						$response['width'] = $width;
						$response['height'] = $height;
					}
				}
			} elseif ( isset( $attributes->width ) && isset( $attributes->height ) ) {
				$width = (int) $attributes->width;
				$height = (int) $attributes->height;
				
				if ( $width && $height ) {
					$response['width'] = $width;
					$response['height'] = $height;
				}
			}
		}
	}
	
	return $response;
}
add_filter( 'wp_prepare_attachment_for_js', 'aqualuxe_svg_dimensions', 10, 3 );

/**
 * Sanitize SVG uploads.
 *
 * @param array $file File data.
 * @return array Modified file data.
 */
function aqualuxe_sanitize_svg_uploads( $file ) {
	// Check if SVG support is enabled in theme options.
	$enable_svg = get_theme_mod( 'aqualuxe_enable_svg', true );
	
	if ( ! $enable_svg ) {
		return $file;
	}
	
	// Check if the file is an SVG.
	if ( 'image/svg+xml' === $file['type'] ) {
		// Check if the file exists.
		if ( ! file_exists( $file['tmp_name'] ) ) {
			return $file;
		}
		
		// Get the SVG file contents.
		$svg_content = file_get_contents( $file['tmp_name'] );
		
		// Check if the SVG contains any script tags.
		if ( preg_match( '/<script/i', $svg_content ) ) {
			$file['error'] = __( 'Sorry, SVG files containing scripts are not allowed.', 'aqualuxe' );
			return $file;
		}
		
		// Check if the SVG contains any event handlers.
		if ( preg_match( '/on\w+=/i', $svg_content ) ) {
			$file['error'] = __( 'Sorry, SVG files containing event handlers are not allowed.', 'aqualuxe' );
			return $file;
		}
	}
	
	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'aqualuxe_sanitize_svg_uploads' );

/**
 * Add image optimization settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_image_optimization_customizer( $wp_customize ) {
	// Add image optimization section.
	$wp_customize->add_section( 'aqualuxe_image_optimization', array(
		'title'    => __( 'Image Optimization', 'aqualuxe' ),
		'priority' => 201,
	) );
	
	// Optimize JPEG quality.
	$wp_customize->add_setting( 'aqualuxe_optimize_jpeg_quality', array(
		'default'           => true,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_optimize_jpeg_quality', array(
		'label'    => __( 'Optimize JPEG Quality', 'aqualuxe' ),
		'section'  => 'aqualuxe_image_optimization',
		'type'     => 'checkbox',
		'priority' => 10,
	) );
	
	// JPEG quality.
	$wp_customize->add_setting( 'aqualuxe_jpeg_quality', array(
		'default'           => 82,
		'sanitize_callback' => 'absint',
	) );
	
	$wp_customize->add_control( 'aqualuxe_jpeg_quality', array(
		'label'       => __( 'JPEG Quality', 'aqualuxe' ),
		'description' => __( 'Lower values = smaller file size but lower quality. Default is 82.', 'aqualuxe' ),
		'section'     => 'aqualuxe_image_optimization',
		'type'        => 'range',
		'input_attrs' => array(
			'min'  => 50,
			'max'  => 100,
			'step' => 1,
		),
		'priority'    => 20,
	) );
	
	// Enable responsive images.
	$wp_customize->add_setting( 'aqualuxe_enable_responsive_images', array(
		'default'           => true,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_enable_responsive_images', array(
		'label'    => __( 'Enable Responsive Images', 'aqualuxe' ),
		'section'  => 'aqualuxe_image_optimization',
		'type'     => 'checkbox',
		'priority' => 30,
	) );
	
	// Enable WebP support.
	$wp_customize->add_setting( 'aqualuxe_enable_webp', array(
		'default'           => true,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_enable_webp', array(
		'label'    => __( 'Enable WebP Support', 'aqualuxe' ),
		'section'  => 'aqualuxe_image_optimization',
		'type'     => 'checkbox',
		'priority' => 40,
	) );
	
	// Enable SVG support.
	$wp_customize->add_setting( 'aqualuxe_enable_svg', array(
		'default'           => true,
		'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
	) );
	
	$wp_customize->add_control( 'aqualuxe_enable_svg', array(
		'label'       => __( 'Enable SVG Support', 'aqualuxe' ),
		'description' => __( 'SVG files are sanitized to remove potentially malicious code.', 'aqualuxe' ),
		'section'     => 'aqualuxe_image_optimization',
		'type'        => 'checkbox',
		'priority'    => 50,
	) );
	
	// Image sizes to generate.
	$wp_customize->add_setting( 'aqualuxe_image_sizes', array(
		'default'           => array( 'thumbnail', 'medium', 'medium_large', 'large', 'aqualuxe-featured', 'aqualuxe-card', 'aqualuxe-thumbnail' ),
		'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
	) );
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'aqualuxe_image_sizes', array(
		'label'       => __( 'Image Sizes to Generate', 'aqualuxe' ),
		'description' => __( 'Select which image sizes should be generated when uploading images.', 'aqualuxe' ),
		'section'     => 'aqualuxe_image_optimization',
		'type'        => 'select',
		'multiple'    => true,
		'choices'     => array(
			'thumbnail'          => __( 'Thumbnail', 'aqualuxe' ),
			'medium'             => __( 'Medium', 'aqualuxe' ),
			'medium_large'       => __( 'Medium Large', 'aqualuxe' ),
			'large'              => __( 'Large', 'aqualuxe' ),
			'aqualuxe-featured'  => __( 'Featured Image', 'aqualuxe' ),
			'aqualuxe-card'      => __( 'Card Image', 'aqualuxe' ),
			'aqualuxe-thumbnail' => __( 'Thumbnail Image', 'aqualuxe' ),
			'aqualuxe-square'    => __( 'Square Image', 'aqualuxe' ),
			'aqualuxe-portrait'  => __( 'Portrait Image', 'aqualuxe' ),
			'aqualuxe-landscape' => __( 'Landscape Image', 'aqualuxe' ),
			'aqualuxe-hero'      => __( 'Hero Image', 'aqualuxe' ),
			'aqualuxe-slider'    => __( 'Slider Image', 'aqualuxe' ),
		),
		'priority'    => 60,
	) ) );
}
add_action( 'customize_register', 'aqualuxe_image_optimization_customizer' );

/**
 * Sanitize multi-select.
 *
 * @param array $values Values to sanitize.
 * @return array Sanitized values.
 */
function aqualuxe_sanitize_multi_select( $values ) {
	$multi_values = ! is_array( $values ) ? explode( ',', $values ) : $values;
	
	return ! empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
}

/**
 * Filter intermediate image sizes.
 *
 * @param array $sizes Image sizes.
 * @return array Modified image sizes.
 */
function aqualuxe_filter_image_sizes( $sizes ) {
	// Get the image sizes to generate from theme options.
	$image_sizes = get_theme_mod( 'aqualuxe_image_sizes', array( 'thumbnail', 'medium', 'medium_large', 'large', 'aqualuxe-featured', 'aqualuxe-card', 'aqualuxe-thumbnail' ) );
	
	// If no image sizes are selected, return all sizes.
	if ( empty( $image_sizes ) ) {
		return $sizes;
	}
	
	// Filter the sizes.
	$filtered_sizes = array();
	
	foreach ( $sizes as $size ) {
		if ( in_array( $size, $image_sizes, true ) ) {
			$filtered_sizes[] = $size;
		}
	}
	
	return $filtered_sizes;
}
add_filter( 'intermediate_image_sizes', 'aqualuxe_filter_image_sizes' );

/**
 * Add picture element support for WebP.
 *
 * @param string $html Image HTML.
 * @param int $attachment_id Attachment ID.
 * @param string $size Image size.
 * @param bool $icon Whether the image should be treated as an icon.
 * @param array $attr Image attributes.
 * @return string Modified image HTML.
 */
function aqualuxe_webp_picture_element( $html, $attachment_id, $size, $icon, $attr ) {
	// Check if WebP support is enabled in theme options.
	$enable_webp = get_theme_mod( 'aqualuxe_enable_webp', true );
	
	if ( ! $enable_webp ) {
		return $html;
	}
	
	// Check if the attachment is an image.
	if ( ! wp_attachment_is_image( $attachment_id ) ) {
		return $html;
	}
	
	// Get the attachment mime type.
	$mime_type = get_post_mime_type( $attachment_id );
	
	// Only process JPEG and PNG images.
	if ( 'image/jpeg' !== $mime_type && 'image/png' !== $mime_type ) {
		return $html;
	}
	
	// Get the attachment metadata.
	$metadata = wp_get_attachment_metadata( $attachment_id );
	
	// Check if the metadata exists.
	if ( ! $metadata ) {
		return $html;
	}
	
	// Get the file path.
	$file_path = get_attached_file( $attachment_id );
	
	// Check if the file exists.
	if ( ! file_exists( $file_path ) ) {
		return $html;
	}
	
	// Get the WebP file path.
	$webp_file_path = preg_replace( '/\.(jpe?g|png)$/i', '.webp', $file_path );
	
	// Check if the WebP file exists.
	if ( ! file_exists( $webp_file_path ) ) {
		// Try to create the WebP file.
		if ( function_exists( 'imagewebp' ) ) {
			// Load the image based on its type.
			if ( 'image/jpeg' === $mime_type ) {
				$image = imagecreatefromjpeg( $file_path );
			} else {
				$image = imagecreatefrompng( $file_path );
			}
			
			// Check if the image was loaded successfully.
			if ( ! $image ) {
				return $html;
			}
			
			// Create the WebP file.
			imagewebp( $image, $webp_file_path, 80 );
			
			// Free up memory.
			imagedestroy( $image );
		} else {
			// If the WebP file doesn't exist and we can't create it, return the original HTML.
			return $html;
		}
	}
	
	// Get the WebP URL.
	$webp_url = preg_replace( '/\.(jpe?g|png)$/i', '.webp', wp_get_attachment_url( $attachment_id ) );
	
	// Get the image dimensions.
	$width = isset( $metadata['width'] ) ? $metadata['width'] : '';
	$height = isset( $metadata['height'] ) ? $metadata['height'] : '';
	
	// Get the image alt text.
	$alt = isset( $attr['alt'] ) ? $attr['alt'] : '';
	
	// Get the image class.
	$class = isset( $attr['class'] ) ? $attr['class'] : '';
	
	// Get the image loading attribute.
	$loading = isset( $attr['loading'] ) ? $attr['loading'] : 'lazy';
	
	// Get the image decoding attribute.
	$decoding = isset( $attr['decoding'] ) ? $attr['decoding'] : 'async';
	
	// Create the picture element.
	$picture_html = '<picture>';
	$picture_html .= '<source srcset="' . esc_url( $webp_url ) . '" type="image/webp">';
	$picture_html .= '<img src="' . esc_url( wp_get_attachment_url( $attachment_id ) ) . '" alt="' . esc_attr( $alt ) . '" class="' . esc_attr( $class ) . '" width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '" loading="' . esc_attr( $loading ) . '" decoding="' . esc_attr( $decoding ) . '">';
	$picture_html .= '</picture>';
	
	return $picture_html;
}
add_filter( 'wp_get_attachment_image', 'aqualuxe_webp_picture_element', 10, 5 );

/**
 * Add image dimensions to img tags.
 *
 * @param string $html Image HTML.
 * @return string Modified image HTML.
 */
function aqualuxe_add_image_dimensions( $html ) {
	// Check if responsive images are enabled in theme options.
	$enable_responsive_images = get_theme_mod( 'aqualuxe_enable_responsive_images', true );
	
	if ( ! $enable_responsive_images ) {
		return $html;
	}
	
	// Check if the HTML contains an img tag.
	if ( strpos( $html, '<img' ) === false ) {
		return $html;
	}
	
	// Check if the img tag already has width and height attributes.
	if ( strpos( $html, 'width=' ) !== false && strpos( $html, 'height=' ) !== false ) {
		return $html;
	}
	
	// Extract the src attribute.
	preg_match( '/src=["\'](.*?)["\']/i', $html, $matches );
	
	if ( ! isset( $matches[1] ) ) {
		return $html;
	}
	
	$src = $matches[1];
	
	// Get the image dimensions.
	$dimensions = getimagesize( $src );
	
	if ( ! $dimensions ) {
		return $html;
	}
	
	$width = $dimensions[0];
	$height = $dimensions[1];
	
	// Add the width and height attributes.
	$html = str_replace( '<img', '<img width="' . $width . '" height="' . $height . '"', $html );
	
	return $html;
}
add_filter( 'the_content', 'aqualuxe_add_image_dimensions', 100 );
add_filter( 'post_thumbnail_html', 'aqualuxe_add_image_dimensions', 100 );
add_filter( 'get_avatar', 'aqualuxe_add_image_dimensions', 100 );