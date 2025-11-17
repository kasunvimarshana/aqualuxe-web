<?php
/**
 * Helper functions for the AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the dark mode class
 *
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_dark_mode_class' ) ) {
	function aqualuxe_get_dark_mode_class() {
		// Check if dark mode is enabled in the customizer
		if ( ! get_theme_mod( 'aqualuxe_enable_dark_mode', true ) ) {
			return '';
		}
		
		// Check for saved user preference in cookie
		if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) ) {
			return $_COOKIE['aqualuxe_dark_mode'] === '1' ? 'dark' : '';
		}
		
		// Check default mode from the customizer
		$default_mode = get_theme_mod( 'aqualuxe_dark_mode_default', 'auto' );
		
		if ( $default_mode === 'dark' ) {
			return 'dark';
		}
		
		return '';
	}
}

/**
 * Check if a post has a featured image
 *
 * @param int $post_id Post ID.
 * @return bool
 */
if ( ! function_exists( 'aqualuxe_has_featured_image' ) ) {
	function aqualuxe_has_featured_image( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		
		return has_post_thumbnail( $post_id );
	}
}

/**
 * Get the featured image URL
 *
 * @param int    $post_id Post ID.
 * @param string $size    Image size.
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_featured_image_url' ) ) {
	function aqualuxe_get_featured_image_url( $post_id = null, $size = 'full' ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		
		if ( has_post_thumbnail( $post_id ) ) {
			$image_id = get_post_thumbnail_id( $post_id );
			$image    = wp_get_attachment_image_src( $image_id, $size );
			
			if ( $image ) {
				return $image[0];
			}
		}
		
		return '';
	}
}

/**
 * Get the featured image srcset
 *
 * @param int    $post_id Post ID.
 * @param string $size    Image size.
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_featured_image_srcset' ) ) {
	function aqualuxe_get_featured_image_srcset( $post_id = null, $size = 'full' ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		
		if ( has_post_thumbnail( $post_id ) ) {
			$image_id = get_post_thumbnail_id( $post_id );
			$srcset   = wp_get_attachment_image_srcset( $image_id, $size );
			
			return $srcset;
		}
		
		return '';
	}
}

/**
 * Get the featured image sizes
 *
 * @param int    $post_id Post ID.
 * @param string $size    Image size.
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_featured_image_sizes' ) ) {
	function aqualuxe_get_featured_image_sizes( $post_id = null, $size = 'full' ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		
		if ( has_post_thumbnail( $post_id ) ) {
			$image_id = get_post_thumbnail_id( $post_id );
			$sizes    = wp_get_attachment_image_sizes( $image_id, $size );
			
			return $sizes;
		}
		
		return '';
	}
}

/**
 * Get the post excerpt
 *
 * @param int $post_id Post ID.
 * @param int $length  Excerpt length.
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_excerpt' ) ) {
	function aqualuxe_get_excerpt( $post_id = null, $length = 55 ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		
		$post = get_post( $post_id );
		
		if ( ! $post ) {
			return '';
		}
		
		if ( has_excerpt( $post_id ) ) {
			return get_the_excerpt( $post_id );
		}
		
		$content = get_the_content( '', false, $post_id );
		$content = strip_shortcodes( $content );
		$content = excerpt_remove_blocks( $content );
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		$excerpt = wp_trim_words( $content, $length, '&hellip;' );
		
		return $excerpt;
	}
}

/**
 * Get the post author
 *
 * @param int $post_id Post ID.
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_author' ) ) {
	function aqualuxe_get_author( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		
		$post = get_post( $post_id );
		
		if ( ! $post ) {
			return '';
		}
		
		$author_id = $post->post_author;
		$author    = get_the_author_meta( 'display_name', $author_id );
		
		return $author;
	}
}

/**
 * Get the post date
 *
 * @param int    $post_id Post ID.
 * @param string $format  Date format.
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_date' ) ) {
	function aqualuxe_get_date( $post_id = null, $format = '' ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		
		$post = get_post( $post_id );
		
		if ( ! $post ) {
			return '';
		}
		
		if ( ! $format ) {
			$format = get_option( 'date_format' );
		}
		
		$date = get_the_date( $format, $post_id );
		
		return $date;
	}
}

/**
 * Get the post categories
 *
 * @param int $post_id Post ID.
 * @return array
 */
if ( ! function_exists( 'aqualuxe_get_categories' ) ) {
	function aqualuxe_get_categories( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		
		$categories = get_the_category( $post_id );
		
		return $categories;
	}
}

/**
 * Get the post tags
 *
 * @param int $post_id Post ID.
 * @return array
 */
if ( ! function_exists( 'aqualuxe_get_tags' ) ) {
	function aqualuxe_get_tags( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		
		$tags = get_the_tags( $post_id );
		
		return $tags;
	}
}

/**
 * Get the post comments count
 *
 * @param int $post_id Post ID.
 * @return int
 */
if ( ! function_exists( 'aqualuxe_get_comments_count' ) ) {
	function aqualuxe_get_comments_count( $post_id = null ) {
		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}
		
		$comments_count = get_comments_number( $post_id );
		
		return $comments_count;
	}
}

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
if ( ! function_exists( 'aqualuxe_is_woocommerce_active' ) ) {
	function aqualuxe_is_woocommerce_active() {
		return class_exists( 'WooCommerce' );
	}
}

/**
 * Check if a page is a WooCommerce page
 *
 * @return bool
 */
if ( ! function_exists( 'aqualuxe_is_woocommerce_page' ) ) {
	function aqualuxe_is_woocommerce_page() {
		if ( ! aqualuxe_is_woocommerce_active() ) {
			return false;
		}
		
		return ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() );
	}
}

/**
 * Get social media links
 *
 * @return array
 */
if ( ! function_exists( 'aqualuxe_get_social_links' ) ) {
	function aqualuxe_get_social_links() {
		$social_links = array(
			'facebook'  => get_theme_mod( 'aqualuxe_facebook_url', '' ),
			'twitter'   => get_theme_mod( 'aqualuxe_twitter_url', '' ),
			'instagram' => get_theme_mod( 'aqualuxe_instagram_url', '' ),
			'linkedin'  => get_theme_mod( 'aqualuxe_linkedin_url', '' ),
			'youtube'   => get_theme_mod( 'aqualuxe_youtube_url', '' ),
			'pinterest' => get_theme_mod( 'aqualuxe_pinterest_url', '' ),
		);
		
		// Remove empty links
		$social_links = array_filter( $social_links );
		
		return $social_links;
	}
}

/**
 * Get the site logo
 *
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_logo' ) ) {
	function aqualuxe_get_logo() {
		$logo_id = get_theme_mod( 'custom_logo' );
		
		if ( $logo_id ) {
			$logo = wp_get_attachment_image( $logo_id, 'full', false, array(
				'class'    => 'custom-logo',
				'loading'  => 'eager',
				'itemprop' => 'logo',
			) );
			
			return $logo;
		}
		
		return '';
	}
}

/**
 * Get the site favicon
 *
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_favicon' ) ) {
	function aqualuxe_get_favicon() {
		$favicon_id = get_theme_mod( 'site_icon' );
		
		if ( $favicon_id ) {
			$favicon = wp_get_attachment_image_url( $favicon_id, 'full' );
			
			return $favicon;
		}
		
		return '';
	}
}

/**
 * Get the site title
 *
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_site_title' ) ) {
	function aqualuxe_get_site_title() {
		return get_bloginfo( 'name' );
	}
}

/**
 * Get the site description
 *
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_site_description' ) ) {
	function aqualuxe_get_site_description() {
		return get_bloginfo( 'description' );
	}
}

/**
 * Get the site URL
 *
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_site_url' ) ) {
	function aqualuxe_get_site_url() {
		return home_url( '/' );
	}
}

/**
 * Get the current year
 *
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_current_year' ) ) {
	function aqualuxe_get_current_year() {
		return date( 'Y' );
	}
}

/**
 * Get the copyright text
 *
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_copyright_text' ) ) {
	function aqualuxe_get_copyright_text() {
		$copyright_text = get_theme_mod( 'aqualuxe_copyright_text', '© ' . aqualuxe_get_current_year() . ' ' . aqualuxe_get_site_title() . '. All rights reserved.' );
		
		return $copyright_text;
	}
}

/**
 * Get the footer address
 *
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_footer_address' ) ) {
	function aqualuxe_get_footer_address() {
		$footer_address = get_theme_mod( 'aqualuxe_footer_address', '123 Aquarium Street, Ocean City, CA 90210' );
		
		return $footer_address;
	}
}

/**
 * Get the footer phone
 *
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_footer_phone' ) ) {
	function aqualuxe_get_footer_phone() {
		$footer_phone = get_theme_mod( 'aqualuxe_footer_phone', '+1 (555) 123-4567' );
		
		return $footer_phone;
	}
}

/**
 * Get the footer email
 *
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_footer_email' ) ) {
	function aqualuxe_get_footer_email() {
		$footer_email = get_theme_mod( 'aqualuxe_footer_email', 'info@aqualuxe.example.com' );
		
		return $footer_email;
	}
}

/**
 * Get the header phone
 *
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_header_phone' ) ) {
	function aqualuxe_get_header_phone() {
		$header_phone = get_theme_mod( 'aqualuxe_header_phone', '+1 (555) 123-4567' );
		
		return $header_phone;
	}
}

/**
 * Get the header email
 *
 * @return string
 */
if ( ! function_exists( 'aqualuxe_get_header_email' ) ) {
	function aqualuxe_get_header_email() {
		$header_email = get_theme_mod( 'aqualuxe_header_email', 'info@aqualuxe.example.com' );
		
		return $header_email;
	}
}

/**
 * Sanitize dark mode default
 *
 * @param string $input The input to sanitize.
 * @return string
 */
if ( ! function_exists( 'aqualuxe_sanitize_dark_mode_default' ) ) {
	function aqualuxe_sanitize_dark_mode_default( $input ) {
		$valid = array( 'light', 'dark', 'auto' );
		
		if ( in_array( $input, $valid, true ) ) {
			return $input;
		}
		
		return 'auto';
	}
}

/**
 * Sanitize multilingual style
 *
 * @param string $input The input to sanitize.
 * @return string
 */
if ( ! function_exists( 'aqualuxe_sanitize_multilingual_style' ) ) {
	function aqualuxe_sanitize_multilingual_style( $input ) {
		$valid = array( 'dropdown', 'horizontal', 'flags' );
		
		if ( in_array( $input, $valid, true ) ) {
			return $input;
		}
		
		return 'dropdown';
	}
}

/**
 * Sanitize HTML
 *
 * @param string $input The input to sanitize.
 * @return string
 */
if ( ! function_exists( 'aqualuxe_sanitize_html' ) ) {
	function aqualuxe_sanitize_html( $input ) {
		return wp_kses_post( $input );
	}
}

/**
 * Sanitize URL
 *
 * @param string $input The input to sanitize.
 * @return string
 */
if ( ! function_exists( 'aqualuxe_sanitize_url' ) ) {
	function aqualuxe_sanitize_url( $input ) {
		return esc_url_raw( $input );
	}
}

/**
 * Sanitize number
 *
 * @param int $input The input to sanitize.
 * @return int
 */
if ( ! function_exists( 'aqualuxe_sanitize_number' ) ) {
	function aqualuxe_sanitize_number( $input ) {
		return absint( $input );
	}
}

/**
 * Sanitize float
 *
 * @param float $input The input to sanitize.
 * @return float
 */
if ( ! function_exists( 'aqualuxe_sanitize_float' ) ) {
	function aqualuxe_sanitize_float( $input ) {
		return floatval( $input );
	}
}

/**
 * Sanitize rgba color
 *
 * @param string $color The color to sanitize.
 * @return string
 */
if ( ! function_exists( 'aqualuxe_sanitize_rgba_color' ) ) {
	function aqualuxe_sanitize_rgba_color( $color ) {
		if ( empty( $color ) || is_array( $color ) ) {
			return 'rgba(0,0,0,0)';
		}
		
		// If string does not start with 'rgba', then treat as hex.
		// sanitize the hex color and finally convert hex to rgba.
		if ( false === strpos( $color, 'rgba' ) ) {
			return sanitize_hex_color( $color );
		}
		
		// By now we know the string is formatted as an rgba color so we need to further sanitize it.
		$color = str_replace( ' ', '', $color );
		sscanf( $color, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
		
		return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
	}
}