<?php
/**
 * WebP Support Handler
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
 * WebP Support Class
 *
 * Handles the implementation of WebP image support to improve page load performance.
 * WebP is a modern image format that provides superior lossless and lossy compression for images on the web.
 *
 * @since 1.1.0
 */
class WebP_Support {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// Check if WebP is supported by the server.
		$this->check_webp_support();
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Skip if WebP support is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_webp', true ) ) {
			return;
		}

		// Add WebP support to WordPress.
		add_filter( 'wp_get_attachment_image_src', array( $this, 'get_attachment_image_webp' ), 10, 4 );
		add_filter( 'wp_calculate_image_srcset', array( $this, 'calculate_image_srcset_webp' ), 10, 5 );
		add_filter( 'the_content', array( $this, 'replace_images_with_webp' ), 99 );
		add_filter( 'post_thumbnail_html', array( $this, 'replace_images_with_webp' ), 99 );
		add_filter( 'widget_text', array( $this, 'replace_images_with_webp' ), 99 );
		add_filter( 'get_avatar', array( $this, 'replace_images_with_webp' ), 99 );

		// Add WebP support to WooCommerce.
		if ( class_exists( 'WooCommerce' ) ) {
			add_filter( 'woocommerce_product_get_image', array( $this, 'replace_images_with_webp' ), 99 );
			add_filter( 'woocommerce_single_product_image_thumbnail_html', array( $this, 'replace_images_with_webp' ), 99 );
		}

		// Add WebP support to admin.
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add WebP support to image uploads.
		add_filter( 'wp_handle_upload', array( $this, 'handle_upload_webp' ), 10, 2 );
	}

	/**
	 * Check if WebP is supported by the server.
	 *
	 * @return bool True if WebP is supported.
	 */
	private function check_webp_support() {
		// Check if GD is available.
		if ( function_exists( 'imagewebp' ) && function_exists( 'imagecreatefromjpeg' ) ) {
			return true;
		}

		// Check if Imagick is available.
		if ( class_exists( 'Imagick' ) ) {
			$formats = ( new \Imagick() )->queryFormats();
			if ( in_array( 'WEBP', $formats, true ) ) {
				return true;
			}
		}

		// Check if cwebp is available.
		$cwebp = $this->get_cwebp_path();
		if ( $cwebp ) {
			return true;
		}

		// WebP is not supported.
		return false;
	}

	/**
	 * Get the path to cwebp binary.
	 *
	 * @return string|bool Path to cwebp binary or false if not found.
	 */
	private function get_cwebp_path() {
		// Check if cwebp path is already set.
		$cwebp_path = get_option( 'aqualuxe_cwebp_path', '' );
		if ( ! empty( $cwebp_path ) && file_exists( $cwebp_path ) && is_executable( $cwebp_path ) ) {
			return $cwebp_path;
		}

		// Try to find cwebp in common locations.
		$common_paths = array(
			'/usr/bin/cwebp',
			'/usr/local/bin/cwebp',
			'/opt/local/bin/cwebp',
		);

		foreach ( $common_paths as $path ) {
			if ( file_exists( $path ) && is_executable( $path ) ) {
				update_option( 'aqualuxe_cwebp_path', $path );
				return $path;
			}
		}

		// Try to find cwebp using which command.
		$output = array();
		$return_var = 0;
		exec( 'which cwebp', $output, $return_var );
		if ( $return_var === 0 && ! empty( $output[0] ) ) {
			$path = $output[0];
			if ( file_exists( $path ) && is_executable( $path ) ) {
				update_option( 'aqualuxe_cwebp_path', $path );
				return $path;
			}
		}

		return false;
	}

	/**
	 * Get WebP version of an image.
	 *
	 * @param array        $image Image data.
	 * @param int          $attachment_id Attachment ID.
	 * @param string|array $size Image size.
	 * @param bool         $icon Whether the image should be treated as an icon.
	 * @return array Modified image data.
	 */
	public function get_attachment_image_webp( $image, $attachment_id, $size, $icon ) {
		// Skip if WebP support is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_webp', true ) ) {
			return $image;
		}

		// Skip if image is not found.
		if ( ! $image ) {
			return $image;
		}

		// Skip if browser doesn't support WebP.
		if ( ! $this->browser_supports_webp() ) {
			return $image;
		}

		// Get WebP version of the image.
		$webp_url = $this->get_webp_url( $image[0] );
		if ( $webp_url ) {
			$image[0] = $webp_url;
		}

		return $image;
	}

	/**
	 * Calculate image srcset with WebP support.
	 *
	 * @param array  $sources Sources for the image.
	 * @param array  $size_array Array of width and height values.
	 * @param string $image_src URL to the image.
	 * @param array  $image_meta Meta data for the image.
	 * @param int    $attachment_id Attachment ID.
	 * @return array Modified sources.
	 */
	public function calculate_image_srcset_webp( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
		// Skip if WebP support is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_webp', true ) ) {
			return $sources;
		}

		// Skip if sources are empty.
		if ( empty( $sources ) ) {
			return $sources;
		}

		// Skip if browser doesn't support WebP.
		if ( ! $this->browser_supports_webp() ) {
			return $sources;
		}

		// Loop through sources and replace with WebP version.
		foreach ( $sources as &$source ) {
			$webp_url = $this->get_webp_url( $source['url'] );
			if ( $webp_url ) {
				$source['url'] = $webp_url;
			}
		}

		return $sources;
	}

	/**
	 * Replace images with WebP version in content.
	 *
	 * @param string $content The content.
	 * @return string Modified content.
	 */
	public function replace_images_with_webp( $content ) {
		// Skip if WebP support is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_webp', true ) ) {
			return $content;
		}

		// Skip if content is empty.
		if ( empty( $content ) ) {
			return $content;
		}

		// Skip if browser doesn't support WebP.
		if ( ! $this->browser_supports_webp() ) {
			return $content;
		}

		// Skip if content doesn't have any images.
		if ( false === strpos( $content, '<img' ) ) {
			return $content;
		}

		// Create a DOM document from the content.
		$dom = new \DOMDocument();
		
		// Suppress errors from malformed HTML.
		libxml_use_internal_errors( true );
		$dom->loadHTML( '<?xml encoding="UTF-8">' . htmlspecialchars_decode( htmlentities( $content, ENT_QUOTES, 'UTF-8', false ) ) );
		libxml_clear_errors();

		// Get all images.
		$images = $dom->getElementsByTagName( 'img' );

		// Loop through images and replace with WebP version.
		foreach ( $images as $image ) {
			// Skip if image doesn't have src attribute.
			if ( ! $image->hasAttribute( 'src' ) ) {
				continue;
			}

			// Get WebP version of the image.
			$webp_url = $this->get_webp_url( $image->getAttribute( 'src' ) );
			if ( $webp_url ) {
				$image->setAttribute( 'src', $webp_url );
			}

			// Replace srcset with WebP version.
			if ( $image->hasAttribute( 'srcset' ) ) {
				$srcset = $image->getAttribute( 'srcset' );
				$srcset_array = explode( ',', $srcset );
				$new_srcset = array();

				foreach ( $srcset_array as $src_item ) {
					$src_item = trim( $src_item );
					$src_parts = preg_split( '/\s+/', $src_item, 2 );
					$src_url = $src_parts[0];
					$src_descriptor = isset( $src_parts[1] ) ? ' ' . $src_parts[1] : '';

					$webp_url = $this->get_webp_url( $src_url );
					if ( $webp_url ) {
						$new_srcset[] = $webp_url . $src_descriptor;
					} else {
						$new_srcset[] = $src_item;
					}
				}

				$image->setAttribute( 'srcset', implode( ', ', $new_srcset ) );
			}
		}

		// Get the modified content.
		$content = $dom->saveHTML( $dom->documentElement );

		// Remove doctype, html, and body tags.
		$content = preg_replace( '~<(?:!DOCTYPE|/?(?:html|head|body))[^>]*>\s*~i', '', $content );

		return $content;
	}

	/**
	 * Handle upload of images and convert to WebP.
	 *
	 * @param array  $file Uploaded file data.
	 * @param string $context Upload context.
	 * @return array Modified file data.
	 */
	public function handle_upload_webp( $file, $context ) {
		// Skip if WebP support is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_webp', true ) ) {
			return $file;
		}

		// Skip if file is not an image.
		if ( ! preg_match( '!\.(jpg|jpeg|png|gif)$!i', $file['file'] ) ) {
			return $file;
		}

		// Skip if auto-convert is disabled.
		if ( ! get_theme_mod( 'aqualuxe_webp_auto_convert', false ) ) {
			return $file;
		}

		// Convert image to WebP.
		$webp_file = $this->convert_to_webp( $file['file'] );
		if ( $webp_file ) {
			// Add WebP file to the upload directory.
			$uploads = wp_upload_dir();
			$relative_path = str_replace( $uploads['basedir'], '', $file['file'] );
			$webp_relative_path = str_replace( $uploads['basedir'], '', $webp_file );
			
			// Add WebP file to the attachment metadata.
			add_filter( 'wp_update_attachment_metadata', function( $metadata, $attachment_id ) use ( $webp_relative_path ) {
				$metadata['webp'] = $webp_relative_path;
				return $metadata;
			}, 10, 2 );
		}

		return $file;
	}

	/**
	 * Convert an image to WebP.
	 *
	 * @param string $file Path to the image file.
	 * @return string|bool Path to the WebP file or false if conversion failed.
	 */
	private function convert_to_webp( $file ) {
		// Skip if file doesn't exist.
		if ( ! file_exists( $file ) ) {
			return false;
		}

		// Get WebP file path.
		$webp_file = preg_replace( '!\.(jpg|jpeg|png|gif)$!i', '.webp', $file );

		// Skip if WebP file already exists.
		if ( file_exists( $webp_file ) ) {
			return $webp_file;
		}

		// Get file extension.
		$extension = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );

		// Convert image to WebP.
		if ( function_exists( 'imagewebp' ) ) {
			// Use GD library.
			switch ( $extension ) {
				case 'jpg':
				case 'jpeg':
					$image = imagecreatefromjpeg( $file );
					break;
				case 'png':
					$image = imagecreatefrompng( $file );
					// Set alpha blending mode.
					imagepalettetotruecolor( $image );
					imagealphablending( $image, true );
					imagesavealpha( $image, true );
					break;
				case 'gif':
					$image = imagecreatefromgif( $file );
					break;
				default:
					return false;
			}

			// Save WebP image.
			$quality = get_theme_mod( 'aqualuxe_webp_quality', 80 );
			$result = imagewebp( $image, $webp_file, $quality );
			imagedestroy( $image );

			if ( $result ) {
				return $webp_file;
			}
		} elseif ( class_exists( 'Imagick' ) ) {
			// Use Imagick library.
			try {
				$imagick = new \Imagick( $file );
				$imagick->setImageFormat( 'WEBP' );
				$quality = get_theme_mod( 'aqualuxe_webp_quality', 80 );
				$imagick->setImageCompressionQuality( $quality );
				$result = $imagick->writeImage( $webp_file );
				$imagick->clear();

				if ( $result ) {
					return $webp_file;
				}
			} catch ( \Exception $e ) {
				// Conversion failed.
				return false;
			}
		} else {
			// Use cwebp binary.
			$cwebp = $this->get_cwebp_path();
			if ( $cwebp ) {
				$quality = get_theme_mod( 'aqualuxe_webp_quality', 80 );
				$command = escapeshellcmd( $cwebp ) . ' -q ' . escapeshellarg( $quality ) . ' ' . escapeshellarg( $file ) . ' -o ' . escapeshellarg( $webp_file );
				exec( $command, $output, $return_var );

				if ( $return_var === 0 && file_exists( $webp_file ) ) {
					return $webp_file;
				}
			}
		}

		return false;
	}

	/**
	 * Get WebP URL for an image.
	 *
	 * @param string $url URL to the image.
	 * @return string|bool WebP URL or false if not found.
	 */
	private function get_webp_url( $url ) {
		// Skip if URL is empty.
		if ( empty( $url ) ) {
			return false;
		}

		// Skip if URL is not from this site.
		if ( strpos( $url, site_url() ) === false ) {
			return false;
		}

		// Skip if URL is not an image.
		if ( ! preg_match( '!\.(jpg|jpeg|png|gif)!i', $url ) ) {
			return false;
		}

		// Get WebP URL.
		$webp_url = preg_replace( '!\.(jpg|jpeg|png|gif)!i', '.webp', $url );

		// Check if WebP file exists.
		$uploads = wp_upload_dir();
		$file_path = str_replace( $uploads['baseurl'], $uploads['basedir'], $url );
		$webp_path = str_replace( $uploads['baseurl'], $uploads['basedir'], $webp_url );

		if ( file_exists( $webp_path ) ) {
			return $webp_url;
		}

		// Try to convert the image to WebP.
		if ( file_exists( $file_path ) ) {
			$webp_file = $this->convert_to_webp( $file_path );
			if ( $webp_file ) {
				return $webp_url;
			}
		}

		return false;
	}

	/**
	 * Check if the browser supports WebP.
	 *
	 * @return bool True if the browser supports WebP.
	 */
	private function browser_supports_webp() {
		// Check if browser support is cached.
		$supports_webp = wp_cache_get( 'aqualuxe_browser_supports_webp' );
		if ( $supports_webp !== false ) {
			return $supports_webp;
		}

		// Check Accept header.
		$supports_webp = false;
		if ( isset( $_SERVER['HTTP_ACCEPT'] ) ) {
			$accept = sanitize_text_field( wp_unslash( $_SERVER['HTTP_ACCEPT'] ) );
			if ( strpos( $accept, 'image/webp' ) !== false ) {
				$supports_webp = true;
			}
		}

		// Check User-Agent header.
		if ( ! $supports_webp && isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$user_agent = sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
			
			// Chrome 9+, Opera 12+, Firefox 65+
			if ( preg_match( '/(Chrome\/[9-9][0-9]|Chrome\/[1-9][0-9][0-9]|Opera\/[1-9][0-9]|Firefox\/6[5-9]|Firefox\/[7-9][0-9])/i', $user_agent ) ) {
				$supports_webp = true;
			}
			
			// Edge 18+
			if ( preg_match( '/Edge\/[1-9][0-9]/i', $user_agent ) ) {
				$supports_webp = true;
			}
			
			// Safari 14+
			if ( preg_match( '/Version\/1[4-9]/i', $user_agent ) && preg_match( '/Safari/i', $user_agent ) ) {
				$supports_webp = true;
			}
		}

		// Cache the result.
		wp_cache_set( 'aqualuxe_browser_supports_webp', $supports_webp );

		return $supports_webp;
	}

	/**
	 * Add admin menu for WebP support.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_submenu_page(
			'themes.php',
			__( 'WebP Support', 'aqualuxe' ),
			__( 'WebP Support', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-webp-support',
			array( $this, 'admin_page' )
		);
	}

	/**
	 * Register settings for WebP support.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting( 'aqualuxe_webp_support', 'aqualuxe_cwebp_path' );
	}

	/**
	 * Admin page for WebP support.
	 *
	 * @return void
	 */
	public function admin_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'WebP Support', 'aqualuxe' ); ?></h1>
			<p><?php esc_html_e( 'WebP is a modern image format that provides superior lossless and lossy compression for images on the web. Using WebP, webmasters and web developers can create smaller, richer images that make the web faster.', 'aqualuxe' ); ?></p>
			
			<h2><?php esc_html_e( 'WebP Support Status', 'aqualuxe' ); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e( 'GD Library Support', 'aqualuxe' ); ?></th>
					<td>
						<?php if ( function_exists( 'imagewebp' ) && function_exists( 'imagecreatefromjpeg' ) ) : ?>
							<span style="color: green;"><?php esc_html_e( 'Available', 'aqualuxe' ); ?></span>
						<?php else : ?>
							<span style="color: red;"><?php esc_html_e( 'Not Available', 'aqualuxe' ); ?></span>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Imagick Support', 'aqualuxe' ); ?></th>
					<td>
						<?php if ( class_exists( 'Imagick' ) ) : ?>
							<?php
							$formats = ( new \Imagick() )->queryFormats();
							if ( in_array( 'WEBP', $formats, true ) ) :
							?>
								<span style="color: green;"><?php esc_html_e( 'Available', 'aqualuxe' ); ?></span>
							<?php else : ?>
								<span style="color: red;"><?php esc_html_e( 'WebP format not supported', 'aqualuxe' ); ?></span>
							<?php endif; ?>
						<?php else : ?>
							<span style="color: red;"><?php esc_html_e( 'Not Available', 'aqualuxe' ); ?></span>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'cwebp Binary', 'aqualuxe' ); ?></th>
					<td>
						<?php $cwebp = $this->get_cwebp_path(); ?>
						<?php if ( $cwebp ) : ?>
							<span style="color: green;"><?php echo esc_html( $cwebp ); ?></span>
						<?php else : ?>
							<span style="color: red;"><?php esc_html_e( 'Not Found', 'aqualuxe' ); ?></span>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Browser Support', 'aqualuxe' ); ?></th>
					<td>
						<?php if ( $this->browser_supports_webp() ) : ?>
							<span style="color: green;"><?php esc_html_e( 'Your browser supports WebP', 'aqualuxe' ); ?></span>
						<?php else : ?>
							<span style="color: red;"><?php esc_html_e( 'Your browser does not support WebP', 'aqualuxe' ); ?></span>
						<?php endif; ?>
					</td>
				</tr>
			</table>
			
			<form method="post" action="options.php">
				<?php settings_fields( 'aqualuxe_webp_support' ); ?>
				<?php do_settings_sections( 'aqualuxe_webp_support' ); ?>
				
				<h2><?php esc_html_e( 'WebP Settings', 'aqualuxe' ); ?></h2>
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'cwebp Path', 'aqualuxe' ); ?></th>
						<td>
							<input type="text" name="aqualuxe_cwebp_path" value="<?php echo esc_attr( get_option( 'aqualuxe_cwebp_path', '' ) ); ?>" class="regular-text" />
							<p class="description"><?php esc_html_e( 'Path to cwebp binary. Leave empty to auto-detect.', 'aqualuxe' ); ?></p>
						</td>
					</tr>
				</table>
				
				<?php submit_button(); ?>
			</form>
			
			<h2><?php esc_html_e( 'WebP Conversion', 'aqualuxe' ); ?></h2>
			<p><?php esc_html_e( 'You can convert existing images to WebP format using the button below.', 'aqualuxe' ); ?></p>
			<p><?php esc_html_e( 'This will scan your media library and convert all images to WebP format. This process may take a while depending on the number of images in your media library.', 'aqualuxe' ); ?></p>
			<p><button id="convert-to-webp" class="button button-primary"><?php esc_html_e( 'Convert Images to WebP', 'aqualuxe' ); ?></button></p>
			<div id="conversion-progress" style="display: none;">
				<p><?php esc_html_e( 'Converting images...', 'aqualuxe' ); ?></p>
				<div class="progress-bar">
					<div class="progress-bar-inner" style="width: 0%;"></div>
				</div>
				<p><span id="conversion-status">0</span> / <span id="conversion-total">0</span> <?php esc_html_e( 'images converted', 'aqualuxe' ); ?></p>
			</div>
			
			<script>
				jQuery(document).ready(function($) {
					$('#convert-to-webp').on('click', function() {
						// Show progress bar.
						$('#conversion-progress').show();
						
						// Get total number of images.
						$.ajax({
							url: ajaxurl,
							type: 'POST',
							data: {
								action: 'aqualuxe_get_total_images',
								nonce: '<?php echo esc_js( wp_create_nonce( 'aqualuxe_webp_conversion' ) ); ?>'
							},
							success: function(response) {
								if (response.success) {
									$('#conversion-total').text(response.data.total);
									convertImages(0, response.data.total);
								} else {
									alert(response.data.message);
								}
							},
							error: function() {
								alert('<?php esc_html_e( 'An error occurred while getting the total number of images.', 'aqualuxe' ); ?>');
							}
						});
					});
					
					function convertImages(offset, total) {
						$.ajax({
							url: ajaxurl,
							type: 'POST',
							data: {
								action: 'aqualuxe_convert_images_to_webp',
								nonce: '<?php echo esc_js( wp_create_nonce( 'aqualuxe_webp_conversion' ) ); ?>',
								offset: offset,
								limit: 10
							},
							success: function(response) {
								if (response.success) {
									// Update progress.
									var converted = offset + response.data.converted;
									$('#conversion-status').text(converted);
									$('.progress-bar-inner').css('width', (converted / total * 100) + '%');
									
									// Continue if there are more images to convert.
									if (converted < total) {
										convertImages(converted, total);
									} else {
										alert('<?php esc_html_e( 'All images have been converted to WebP format.', 'aqualuxe' ); ?>');
									}
								} else {
									alert(response.data.message);
								}
							},
							error: function() {
								alert('<?php esc_html_e( 'An error occurred while converting images.', 'aqualuxe' ); ?>');
							}
						});
					}
				});
			</script>
			
			<style>
				.progress-bar {
					width: 100%;
					height: 20px;
					background-color: #f0f0f0;
					border-radius: 4px;
					margin-top: 10px;
					margin-bottom: 10px;
				}
				.progress-bar-inner {
					height: 100%;
					background-color: #0073aa;
					border-radius: 4px;
					transition: width 0.3s ease;
				}
			</style>
		</div>
		<?php
	}
}