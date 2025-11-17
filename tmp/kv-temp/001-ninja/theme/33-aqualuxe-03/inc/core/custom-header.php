<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
 * <?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses aqualuxe_header_style()
 */
function aqualuxe_custom_header_setup() {
	add_theme_support(
		'custom-header',
		apply_filters(
			'aqualuxe_custom_header_args',
			array(
				'default-image'      => '',
				'default-text-color' => '000000',
				'width'              => 1920,
				'height'             => 400,
				'flex-height'        => true,
				'flex-width'         => true,
				'wp-head-callback'   => 'aqualuxe_header_style',
			)
		)
	);
}
add_action( 'after_setup_theme', 'aqualuxe_custom_header_setup' );

if ( ! function_exists( 'aqualuxe_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see aqualuxe_custom_header_setup().
	 */
	function aqualuxe_header_style() {
		$header_text_color = get_header_textcolor();

		/*
		 * If no custom options for text are set, let's bail.
		 * get_header_textcolor() options: Any hex value, 'blank' to hide text. Default: add_theme_support( 'custom-header' ).
		 */
		if ( get_theme_support( 'custom-header', 'default-text-color' ) === $header_text_color ) {
			return;
		}

		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
		<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
			?>
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
				}
			<?php
			// If the user has set a custom color for the text use that.
		else :
			?>
			.site-title a,
			.site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
		<?php endif; ?>
		</style>
		<?php
	}
endif;

/**
 * Get header image URL.
 *
 * @return string
 */
function aqualuxe_get_header_image_url() {
	$header_image = get_header_image();
	
	if ( ! $header_image ) {
		return '';
	}
	
	return $header_image;
}

/**
 * Get header video URL.
 *
 * @return string
 */
function aqualuxe_get_header_video_url() {
	$header_video = get_header_video_url();
	
	if ( ! $header_video ) {
		return '';
	}
	
	return $header_video;
}

/**
 * Check if header video is active.
 *
 * @return bool
 */
function aqualuxe_has_header_video() {
	return has_header_video();
}

/**
 * Get custom header markup.
 *
 * @return string
 */
function aqualuxe_get_custom_header() {
	$header_image = aqualuxe_get_header_image_url();
	$header_video = aqualuxe_get_header_video_url();
	
	if ( ! $header_image && ! $header_video ) {
		return '';
	}
	
	$html = '<div class="custom-header">';
	
	if ( $header_video ) {
		$html .= wp_video_shortcode( array(
			'src'      => $header_video,
			'poster'   => $header_image,
			'loop'     => true,
			'autoplay' => true,
			'muted'    => true,
			'width'    => 1920,
			'height'   => 400,
			'class'    => 'custom-header-video',
		) );
	} elseif ( $header_image ) {
		$html .= '<img src="' . esc_url( $header_image ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="custom-header-image">';
	}
	
	$html .= '</div>';
	
	return $html;
}

/**
 * Display custom header.
 */
function aqualuxe_custom_header() {
	echo aqualuxe_get_custom_header(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}