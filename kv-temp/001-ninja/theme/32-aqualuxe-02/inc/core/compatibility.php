<?php
/**
 * AquaLuxe Compatibility Functions
 *
 * Functions that ensure backward compatibility with older versions of WordPress.
 *
 * @package AquaLuxe
 * @since 1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes_compat( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes_compat' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header_compat() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'aqualuxe_pingback_header_compat' );

/**
 * Backward compatibility for wp_body_open function.
 */
if ( ! function_exists( 'wp_body_open' ) ) {
	/**
	 * Shim for wp_body_open, ensuring backward compatibility with versions of WordPress older than 5.2.
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * Backward compatibility for get_the_archive_title.
 */
if ( ! function_exists( 'aqualuxe_get_the_archive_title' ) ) {
	/**
	 * Get the archive title.
	 *
	 * @return string
	 */
	function aqualuxe_get_the_archive_title() {
		if ( is_category() ) {
			/* translators: Category archive title. %s: Category name */
			$title = sprintf( __( 'Category: %s', 'aqualuxe' ), single_cat_title( '', false ) );
		} elseif ( is_tag() ) {
			/* translators: Tag archive title. %s: Tag name */
			$title = sprintf( __( 'Tag: %s', 'aqualuxe' ), single_tag_title( '', false ) );
		} elseif ( is_author() ) {
			/* translators: Author archive title. %s: Author name */
			$title = sprintf( __( 'Author: %s', 'aqualuxe' ), '<span class="vcard">' . get_the_author() . '</span>' );
		} elseif ( is_year() ) {
			/* translators: Yearly archive title. %s: Year */
			$title = sprintf( __( 'Year: %s', 'aqualuxe' ), get_the_date( _x( 'Y', 'yearly archives date format', 'aqualuxe' ) ) );
		} elseif ( is_month() ) {
			/* translators: Monthly archive title. %s: Month name and year */
			$title = sprintf( __( 'Month: %s', 'aqualuxe' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'aqualuxe' ) ) );
		} elseif ( is_day() ) {
			/* translators: Daily archive title. %s: Date */
			$title = sprintf( __( 'Day: %s', 'aqualuxe' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'aqualuxe' ) ) );
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = _x( 'Asides', 'post format archive title', 'aqualuxe' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title', 'aqualuxe' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title', 'aqualuxe' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title', 'aqualuxe' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title', 'aqualuxe' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title', 'aqualuxe' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title', 'aqualuxe' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title', 'aqualuxe' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title', 'aqualuxe' );
			}
		} elseif ( is_post_type_archive() ) {
			/* translators: Post type archive title. %s: Post type name */
			$title = sprintf( __( 'Archives: %s', 'aqualuxe' ), post_type_archive_title( '', false ) );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			/* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
			$title = sprintf( __( '%1$s: %2$s', 'aqualuxe' ), $tax->labels->singular_name, single_term_title( '', false ) );
		} else {
			$title = __( 'Archives', 'aqualuxe' );
		}

		/**
		 * Filters the archive title.
		 *
		 * @param string $title Archive title to be displayed.
		 */
		return apply_filters( 'aqualuxe_get_the_archive_title', $title );
	}
}

/**
 * Backward compatibility for get_the_archive_description.
 */
if ( ! function_exists( 'aqualuxe_get_the_archive_description' ) ) {
	/**
	 * Get the archive description.
	 *
	 * @return string
	 */
	function aqualuxe_get_the_archive_description() {
		/**
		 * Filters the archive description.
		 *
		 * @param string $description Archive description to be displayed.
		 */
		return apply_filters( 'aqualuxe_get_the_archive_description', get_the_archive_description() );
	}
}

/**
 * Backward compatibility for wp_get_attachment_image_srcset.
 */
if ( ! function_exists( 'aqualuxe_get_attachment_image_srcset' ) ) {
	/**
	 * Get the srcset attribute for an image.
	 *
	 * @param int   $attachment_id Image attachment ID.
	 * @param array $size          Image size.
	 * @return string
	 */
	function aqualuxe_get_attachment_image_srcset( $attachment_id, $size ) {
		if ( function_exists( 'wp_get_attachment_image_srcset' ) ) {
			return wp_get_attachment_image_srcset( $attachment_id, $size );
		}

		return '';
	}
}

/**
 * Backward compatibility for wp_get_attachment_image_sizes.
 */
if ( ! function_exists( 'aqualuxe_get_attachment_image_sizes' ) ) {
	/**
	 * Get the sizes attribute for an image.
	 *
	 * @param int   $attachment_id Image attachment ID.
	 * @param array $size          Image size.
	 * @return string
	 */
	function aqualuxe_get_attachment_image_sizes( $attachment_id, $size ) {
		if ( function_exists( 'wp_get_attachment_image_sizes' ) ) {
			return wp_get_attachment_image_sizes( $attachment_id, $size );
		}

		return '';
	}
}

/**
 * Backward compatibility for wp_filter_content_tags.
 */
if ( ! function_exists( 'aqualuxe_filter_content_tags' ) ) {
	/**
	 * Filter content tags.
	 *
	 * @param string $content Content to filter.
	 * @return string
	 */
	function aqualuxe_filter_content_tags( $content ) {
		if ( function_exists( 'wp_filter_content_tags' ) ) {
			return wp_filter_content_tags( $content );
		} elseif ( function_exists( 'wp_make_content_images_responsive' ) ) {
			return wp_make_content_images_responsive( $content );
		}

		return $content;
	}
}

/**
 * Backward compatibility for wp_get_script_polyfill.
 */
if ( ! function_exists( 'aqualuxe_get_script_polyfill' ) ) {
	/**
	 * Get script polyfill.
	 *
	 * @param array  $features Features to test.
	 * @param string $prefix   Prefix for the polyfill URL.
	 * @return string
	 */
	function aqualuxe_get_script_polyfill( $features, $prefix = '' ) {
		if ( function_exists( 'wp_get_script_polyfill' ) ) {
			return wp_get_script_polyfill( $features, $prefix );
		}

		return '';
	}
}

/**
 * Backward compatibility for wp_get_global_styles.
 */
if ( ! function_exists( 'aqualuxe_get_global_styles' ) ) {
	/**
	 * Get global styles.
	 *
	 * @param array $path Path to the global styles.
	 * @return array
	 */
	function aqualuxe_get_global_styles( $path = array() ) {
		if ( function_exists( 'wp_get_global_styles' ) ) {
			return wp_get_global_styles( $path );
		}

		return array();
	}
}

/**
 * Backward compatibility for wp_get_global_settings.
 */
if ( ! function_exists( 'aqualuxe_get_global_settings' ) ) {
	/**
	 * Get global settings.
	 *
	 * @param array $path Path to the global settings.
	 * @return array
	 */
	function aqualuxe_get_global_settings( $path = array() ) {
		if ( function_exists( 'wp_get_global_settings' ) ) {
			return wp_get_global_settings( $path );
		}

		return array();
	}
}

/**
 * Backward compatibility for wp_enqueue_block_style.
 */
if ( ! function_exists( 'aqualuxe_enqueue_block_style' ) ) {
	/**
	 * Enqueue block style.
	 *
	 * @param string $block_name Block name.
	 * @param array  $args       Arguments.
	 * @return void
	 */
	function aqualuxe_enqueue_block_style( $block_name, $args ) {
		if ( function_exists( 'wp_enqueue_block_style' ) ) {
			wp_enqueue_block_style( $block_name, $args );
		} else {
			wp_enqueue_style(
				$args['handle'],
				$args['src'],
				$args['deps'],
				$args['ver'],
				$args['media']
			);
		}
	}
}