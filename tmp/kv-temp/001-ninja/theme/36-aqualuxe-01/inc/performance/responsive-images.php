<?php
/**
 * Responsive Images Implementation
 *
 * Functions for implementing responsive images with srcset and sizes attributes.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if we should add responsive image attributes.
 *
 * @return bool Whether to add responsive image attributes.
 */
function aqualuxe_should_use_responsive_images() {
	// Don't add responsive image attributes in admin.
	if ( is_admin() ) {
		return false;
	}

	// Check if responsive images are enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_responsive_images', true ) ) {
		return false;
	}

	return true;
}

/**
 * Add custom image sizes for responsive images.
 */
function aqualuxe_add_responsive_image_sizes() {
	// Add custom image sizes for responsive images.
	add_image_size( 'aqualuxe-small', 400, 9999 ); // 400px wide, unlimited height
	add_image_size( 'aqualuxe-medium', 800, 9999 ); // 800px wide, unlimited height
	add_image_size( 'aqualuxe-large', 1200, 9999 ); // 1200px wide, unlimited height
	add_image_size( 'aqualuxe-xlarge', 1600, 9999 ); // 1600px wide, unlimited height
	add_image_size( 'aqualuxe-xxlarge', 2000, 9999 ); // 2000px wide, unlimited height

	// Add custom image sizes with fixed aspect ratios.
	add_image_size( 'aqualuxe-hero-small', 640, 360, true ); // 16:9 aspect ratio
	add_image_size( 'aqualuxe-hero-medium', 960, 540, true ); // 16:9 aspect ratio
	add_image_size( 'aqualuxe-hero-large', 1280, 720, true ); // 16:9 aspect ratio
	add_image_size( 'aqualuxe-hero-xlarge', 1600, 900, true ); // 16:9 aspect ratio
	add_image_size( 'aqualuxe-hero-xxlarge', 1920, 1080, true ); // 16:9 aspect ratio

	// Add custom image sizes for cards.
	add_image_size( 'aqualuxe-card-small', 400, 300, true ); // 4:3 aspect ratio
	add_image_size( 'aqualuxe-card-medium', 600, 450, true ); // 4:3 aspect ratio
	add_image_size( 'aqualuxe-card-large', 800, 600, true ); // 4:3 aspect ratio

	// Add custom image sizes for featured images.
	add_image_size( 'aqualuxe-featured-small', 640, 360, true ); // 16:9 aspect ratio
	add_image_size( 'aqualuxe-featured-medium', 960, 540, true ); // 16:9 aspect ratio
	add_image_size( 'aqualuxe-featured-large', 1280, 720, true ); // 16:9 aspect ratio
}
add_action( 'after_setup_theme', 'aqualuxe_add_responsive_image_sizes' );

/**
 * Add custom image sizes to media library.
 *
 * @param array $sizes Array of image sizes.
 * @return array Modified array of image sizes.
 */
function aqualuxe_add_custom_image_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'aqualuxe-small' => __( 'Small (400px)', 'aqualuxe' ),
		'aqualuxe-medium' => __( 'Medium (800px)', 'aqualuxe' ),
		'aqualuxe-large' => __( 'Large (1200px)', 'aqualuxe' ),
		'aqualuxe-xlarge' => __( 'Extra Large (1600px)', 'aqualuxe' ),
		'aqualuxe-xxlarge' => __( 'XX Large (2000px)', 'aqualuxe' ),
		'aqualuxe-hero-large' => __( 'Hero (1280x720)', 'aqualuxe' ),
		'aqualuxe-card-medium' => __( 'Card (600x450)', 'aqualuxe' ),
		'aqualuxe-featured-large' => __( 'Featured (1280x720)', 'aqualuxe' ),
	) );
}
add_filter( 'image_size_names_choose', 'aqualuxe_add_custom_image_sizes' );

/**
 * Add responsive image attributes to images.
 *
 * @param array        $attr       Array of attribute values for the image markup.
 * @param WP_Post      $attachment Image attachment post.
 * @param string|array $size       Requested size.
 * @return array Modified array of attribute values.
 */
function aqualuxe_add_responsive_image_attributes( $attr, $attachment, $size ) {
	// Check if we should add responsive image attributes.
	if ( ! aqualuxe_should_use_responsive_images() ) {
		return $attr;
	}

	// Don't add responsive image attributes if the image already has srcset.
	if ( isset( $attr['srcset'] ) ) {
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
	$full_url = wp_get_attachment_url( $attachment->ID );
	$srcset[] = $full_url . ' ' . $image_meta['width'] . 'w';

	// Add the other sizes.
	foreach ( $image_meta['sizes'] as $size_name => $size_data ) {
		$size_url = wp_get_attachment_image_url( $attachment->ID, $size_name );
		$srcset[] = $size_url . ' ' . $size_data['width'] . 'w';
	}

	// Add the srcset attribute.
	$attr['srcset'] = implode( ', ', $srcset );

	// Add the sizes attribute based on the context.
	if ( is_singular() && has_post_thumbnail() && $attachment->ID === get_post_thumbnail_id() ) {
		// Featured image on single post/page.
		$attr['sizes'] = '(min-width: 1200px) 1200px, 100vw';
	} elseif ( strpos( $attr['class'], 'wp-post-image' ) !== false ) {
		// Featured image on archive pages.
		$attr['sizes'] = '(min-width: 768px) 768px, 100vw';
	} elseif ( strpos( $attr['class'], 'card-img' ) !== false ) {
		// Card images.
		$attr['sizes'] = '(min-width: 1200px) 400px, (min-width: 992px) 320px, (min-width: 768px) 240px, 100vw';
	} elseif ( strpos( $attr['class'], 'hero-img' ) !== false ) {
		// Hero images.
		$attr['sizes'] = '100vw';
	} else {
		// Default sizes attribute.
		$attr['sizes'] = '(max-width: ' . $width . 'px) 100vw, ' . $width . 'px';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_add_responsive_image_attributes', 10, 3 );

/**
 * Add responsive image attributes to content images.
 *
 * @param string $content The content to be filtered.
 * @return string The filtered content.
 */
function aqualuxe_add_responsive_image_attributes_to_content( $content ) {
	// Check if we should add responsive image attributes.
	if ( ! aqualuxe_should_use_responsive_images() ) {
		return $content;
	}

	// Don't add responsive image attributes if the content has already been processed.
	if ( false !== strpos( $content, 'srcset=' ) ) {
		return $content;
	}

	// Find all <img> tags.
	if ( preg_match_all( '/<img\s[^>]+>/', $content, $matches ) ) {
		$selected_images = array();

		foreach ( $matches[0] as $image ) {
			// Don't add responsive image attributes if the image already has srcset.
			if ( false !== strpos( $image, 'srcset=' ) ) {
				continue;
			}

			// Don't add responsive image attributes if the image has the 'no-responsive' class.
			if ( false !== strpos( $image, 'class="' ) && false !== strpos( $image, 'no-responsive' ) ) {
				continue;
			}

			// Don't add responsive image attributes if the image has the 'skip-responsive' class.
			if ( false !== strpos( $image, 'class="' ) && false !== strpos( $image, 'skip-responsive' ) ) {
				continue;
			}

			// Add the image to the selected images array.
			$selected_images[] = $image;
		}

		// Loop through the selected images.
		foreach ( $selected_images as $selected_image ) {
			// Get the image ID.
			preg_match( '/wp-image-([0-9]+)/i', $selected_image, $id_matches );
			
			if ( ! isset( $id_matches[1] ) ) {
				continue;
			}
			
			$image_id = $id_matches[1];

			// Get the image metadata.
			$image_meta = wp_get_attachment_metadata( $image_id );

			if ( ! $image_meta ) {
				continue;
			}

			// Get the image width.
			preg_match( '/width="([0-9]+)"/i', $selected_image, $width_matches );
			
			if ( ! isset( $width_matches[1] ) ) {
				continue;
			}
			
			$width = $width_matches[1];

			// Build the srcset attribute.
			$srcset = array();

			// Add the full size.
			$full_url = wp_get_attachment_url( $image_id );
			$srcset[] = $full_url . ' ' . $image_meta['width'] . 'w';

			// Add the other sizes.
			foreach ( $image_meta['sizes'] as $size_name => $size_data ) {
				$size_url = wp_get_attachment_image_url( $image_id, $size_name );
				$srcset[] = $size_url . ' ' . $size_data['width'] . 'w';
			}

			// Add the srcset attribute.
			$replacement_image = str_replace( '<img', '<img srcset="' . implode( ', ', $srcset ) . '"', $selected_image );

			// Add the sizes attribute.
			$replacement_image = str_replace( '<img', '<img sizes="(max-width: ' . $width . 'px) 100vw, ' . $width . 'px"', $replacement_image );

			// Replace the image in the content.
			$content = str_replace( $selected_image, $replacement_image, $content );
		}
	}

	return $content;
}
add_filter( 'the_content', 'aqualuxe_add_responsive_image_attributes_to_content', 99 );

/**
 * Add WebP support.
 */
function aqualuxe_webp_support() {
	// Check if WebP support is enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_webp_support', true ) ) {
		return;
	}

	// Add WebP to the list of allowed mime types.
	add_filter( 'upload_mimes', function( $mimes ) {
		$mimes['webp'] = 'image/webp';
		return $mimes;
	} );

	// Add WebP to the list of allowed image types.
	add_filter( 'file_is_displayable_image', function( $result, $path ) {
		if ( $result === false ) {
			$info = @getimagesize( $path );
			if ( $info && $info[2] === IMAGETYPE_WEBP ) {
				$result = true;
			}
		}
		return $result;
	}, 10, 2 );
}
add_action( 'init', 'aqualuxe_webp_support' );

/**
 * Generate WebP versions of uploaded images.
 *
 * @param array $metadata An array of attachment meta data.
 * @param int   $attachment_id Current attachment ID.
 * @return array Modified array of attachment meta data.
 */
function aqualuxe_generate_webp_images( $metadata, $attachment_id ) {
	// Check if WebP generation is enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_generate_webp', false ) ) {
		return $metadata;
	}

	// Check if the uploaded file is an image.
	if ( ! isset( $metadata['file'] ) || ! isset( $metadata['sizes'] ) ) {
		return $metadata;
	}

	// Check if GD or Imagick is available.
	if ( ! function_exists( 'imagewebp' ) && ! class_exists( 'Imagick' ) ) {
		return $metadata;
	}

	// Get the upload directory.
	$upload_dir = wp_upload_dir();
	$upload_path = $upload_dir['basedir'] . '/' . dirname( $metadata['file'] ) . '/';

	// Get the file extension.
	$file_extension = strtolower( pathinfo( $metadata['file'], PATHINFO_EXTENSION ) );

	// Only process JPEG and PNG images.
	if ( ! in_array( $file_extension, array( 'jpg', 'jpeg', 'png' ) ) ) {
		return $metadata;
	}

	// Process the original image.
	$original_file = $upload_dir['basedir'] . '/' . $metadata['file'];
	$webp_file = $upload_path . pathinfo( basename( $metadata['file'] ), PATHINFO_FILENAME ) . '.webp';

	// Generate WebP version of the original image.
	aqualuxe_convert_to_webp( $original_file, $webp_file );

	// Process each image size.
	foreach ( $metadata['sizes'] as $size => $size_data ) {
		// Get the file path.
		$file = $upload_path . $size_data['file'];
		$webp_file = $upload_path . pathinfo( $size_data['file'], PATHINFO_FILENAME ) . '.webp';

		// Generate WebP version of the image size.
		aqualuxe_convert_to_webp( $file, $webp_file );
	}

	return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'aqualuxe_generate_webp_images', 10, 2 );

/**
 * Convert an image to WebP format.
 *
 * @param string $source_file Path to the source image file.
 * @param string $destination_file Path to the destination WebP file.
 * @return bool Whether the conversion was successful.
 */
function aqualuxe_convert_to_webp( $source_file, $destination_file ) {
	// Get the file extension.
	$file_extension = strtolower( pathinfo( $source_file, PATHINFO_EXTENSION ) );

	// Only process JPEG and PNG images.
	if ( ! in_array( $file_extension, array( 'jpg', 'jpeg', 'png' ) ) ) {
		return false;
	}

	// Check if Imagick is available.
	if ( class_exists( 'Imagick' ) ) {
		try {
			// Create Imagick instance.
			$imagick = new Imagick( $source_file );
			
			// Set WebP compression quality.
			$imagick->setImageCompressionQuality( 80 );
			
			// Convert to WebP.
			$imagick->setImageFormat( 'webp' );
			
			// Write the WebP file.
			$imagick->writeImage( $destination_file );
			
			// Free resources.
			$imagick->clear();
			$imagick->destroy();
			
			return true;
		} catch ( Exception $e ) {
			// Fallback to GD if Imagick fails.
		}
	}

	// Check if GD is available.
	if ( function_exists( 'imagewebp' ) ) {
		// Create image resource.
		$image = false;
		
		if ( $file_extension === 'png' ) {
			$image = imagecreatefrompng( $source_file );
		} else {
			$image = imagecreatefromjpeg( $source_file );
		}
		
		// Check if image resource was created.
		if ( $image === false ) {
			return false;
		}
		
		// Convert to WebP.
		$result = imagewebp( $image, $destination_file, 80 );
		
		// Free resources.
		imagedestroy( $image );
		
		return $result;
	}

	return false;
}

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
	// Check if WebP support is enabled in customizer.
	if ( ! get_theme_mod( 'aqualuxe_webp_support', true ) ) {
		return $result;
	}

	// Check if the file is a WebP image.
	if ( 'image/webp' === $real_mime ) {
		// Allow WebP uploads.
		$result['ext'] = 'webp';
		$result['type'] = 'image/webp';
	}

	return $result;
}
add_filter( 'wp_check_filetype_and_ext', 'aqualuxe_webp_file_types', 10, 5 );

/**
 * Add responsive images settings to the customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_responsive_images_customizer( $wp_customize ) {
	// Add responsive images section.
	$wp_customize->add_section(
		'aqualuxe_responsive_images',
		array(
			'title' => __( 'Responsive Images', 'aqualuxe' ),
			'priority' => 120,
			'panel' => 'aqualuxe_performance',
		)
	);

	// Add responsive images setting.
	$wp_customize->add_setting(
		'aqualuxe_responsive_images',
		array(
			'default' => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	// Add responsive images control.
	$wp_customize->add_control(
		'aqualuxe_responsive_images',
		array(
			'label' => __( 'Enable responsive images', 'aqualuxe' ),
			'description' => __( 'Add srcset and sizes attributes to images for better performance on different devices.', 'aqualuxe' ),
			'section' => 'aqualuxe_responsive_images',
			'type' => 'checkbox',
		)
	);

	// Add WebP support setting.
	$wp_customize->add_setting(
		'aqualuxe_webp_support',
		array(
			'default' => true,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	// Add WebP support control.
	$wp_customize->add_control(
		'aqualuxe_webp_support',
		array(
			'label' => __( 'Enable WebP support', 'aqualuxe' ),
			'description' => __( 'Allow WebP image uploads and display in the media library.', 'aqualuxe' ),
			'section' => 'aqualuxe_responsive_images',
			'type' => 'checkbox',
		)
	);

	// Add WebP generation setting.
	$wp_customize->add_setting(
		'aqualuxe_generate_webp',
		array(
			'default' => false,
			'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
		)
	);

	// Add WebP generation control.
	$wp_customize->add_control(
		'aqualuxe_generate_webp',
		array(
			'label' => __( 'Generate WebP images', 'aqualuxe' ),
			'description' => __( 'Automatically generate WebP versions of uploaded JPEG and PNG images.', 'aqualuxe' ),
			'section' => 'aqualuxe_responsive_images',
			'type' => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'aqualuxe_responsive_images_customizer' );

/**
 * Sanitize checkbox values.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true === $checked ) ? true : false );
}