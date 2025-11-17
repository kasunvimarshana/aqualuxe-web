<?php
/**
 * Theme Helper Functions
 *
 * @package AquaLuxe
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if WooCommerce is active
 */
function aqualuxe_is_woocommerce_active() {
	return class_exists( 'WooCommerce' );
}

/**
 * Get theme option with fallback
 */
function aqualuxe_get_option( $option, $default = '' ) {
	return get_theme_mod( "aqualuxe_{$option}", $default );
}

/**
 * Pagination function
 */
function aqualuxe_pagination() {
	global $wp_query;
	
	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}
	
	$big = 999999999; // need an unlikely integer
	
	$pagination = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var( 'paged' ) ),
		'total' => $wp_query->max_num_pages,
		'type' => 'array',
		'prev_text' => '<span class="sr-only">' . __( 'Previous page', 'aqualuxe' ) . '</span><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>',
		'next_text' => '<span class="sr-only">' . __( 'Next page', 'aqualuxe' ) . '</span><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>',
	) );
	
	if ( ! empty( $pagination ) ) {
		echo '<nav class="pagination-wrapper mt-12" aria-label="' . esc_attr__( 'Posts navigation', 'aqualuxe' ) . '">';
		echo '<ul class="pagination flex items-center justify-center space-x-1">';
		
		foreach ( $pagination as $key => $page_link ) {
			$classes = array( 'page-item' );
			
			if ( strpos( $page_link, 'current' ) !== false ) {
				$classes[] = 'active';
			}
			
			echo '<li class="' . esc_attr( implode( ' ', $classes ) ) . '">' . $page_link . '</li>';
		}
		
		echo '</ul>';
		echo '</nav>';
	}
}

/**
 * Get social media icons
 */
function aqualuxe_social_icons() {
	$social_links = array(
		'facebook' => aqualuxe_get_option( 'facebook_url' ),
		'twitter' => aqualuxe_get_option( 'twitter_url' ),
		'instagram' => aqualuxe_get_option( 'instagram_url' ),
		'youtube' => aqualuxe_get_option( 'youtube_url' ),
		'linkedin' => aqualuxe_get_option( 'linkedin_url' ),
	);
	
	$output = '<div class="social-links flex space-x-4">';
	
	foreach ( $social_links as $platform => $url ) {
		if ( ! empty( $url ) ) {
			$output .= sprintf(
				'<a href="%s" target="_blank" rel="noopener noreferrer" class="social-link social-link-%s text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors duration-200" aria-label="%s">%s</a>',
				esc_url( $url ),
				esc_attr( $platform ),
				esc_attr( sprintf( __( 'Follow us on %s', 'aqualuxe' ), ucfirst( $platform ) ) ),
				aqualuxe_get_social_icon( $platform )
			);
		}
	}
	
	$output .= '</div>';
	
	return $output;
}

/**
 * Get SVG icon for social platforms
 */
function aqualuxe_get_social_icon( $platform ) {
	$icons = array(
		'facebook' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
		'twitter' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>',
		'instagram' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
		'youtube' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
		'linkedin' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
	);
	
	return isset( $icons[ $platform ] ) ? $icons[ $platform ] : '';
}

/**
 * Get breadcrumbs
 */
function aqualuxe_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}
	
	$output = '<nav class="breadcrumbs text-sm text-gray-600 dark:text-gray-400 mb-6" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
	$output .= '<ol class="flex items-center space-x-2">';
	
	// Home link
	$output .= '<li><a href="' . esc_url( home_url() ) . '" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">' . __( 'Home', 'aqualuxe' ) . '</a></li>';
	$output .= '<li><span class="text-gray-400 dark:text-gray-600">/</span></li>';
	
	if ( is_category() || is_single() ) {
		if ( is_single() ) {
			$category = get_the_category();
			if ( ! empty( $category ) ) {
				$output .= '<li><a href="' . esc_url( get_category_link( $category[0]->term_id ) ) . '" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">' . esc_html( $category[0]->name ) . '</a></li>';
				$output .= '<li><span class="text-gray-400 dark:text-gray-600">/</span></li>';
			}
		}
	} elseif ( is_page() ) {
		$ancestors = get_post_ancestors( get_the_ID() );
		if ( $ancestors ) {
			$ancestors = array_reverse( $ancestors );
			foreach ( $ancestors as $ancestor ) {
				$output .= '<li><a href="' . esc_url( get_permalink( $ancestor ) ) . '" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">' . esc_html( get_the_title( $ancestor ) ) . '</a></li>';
				$output .= '<li><span class="text-gray-400 dark:text-gray-600">/</span></li>';
			}
		}
	}
	
	// Current page
	$output .= '<li><span class="text-gray-900 dark:text-white font-medium">' . esc_html( get_the_title() ) . '</span></li>';
	
	$output .= '</ol>';
	$output .= '</nav>';
	
	return $output;
}

/**
 * Display reading time
 */
function aqualuxe_reading_time( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	
	$content = get_post_field( 'post_content', $post_id );
	$word_count = str_word_count( wp_strip_all_tags( $content ) );
	$reading_time = ceil( $word_count / 200 ); // Average reading speed is 200 words per minute
	
	return sprintf(
		'<span class="reading-time text-sm text-gray-600 dark:text-gray-400">%s</span>',
		sprintf( _n( '%d min read', '%d min read', $reading_time, 'aqualuxe' ), $reading_time )
	);
}

/**
 * Custom excerpt function
 */
function aqualuxe_custom_excerpt( $limit = 20, $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	
	$excerpt = get_post_field( 'post_excerpt', $post_id );
	
	if ( empty( $excerpt ) ) {
		$content = get_post_field( 'post_content', $post_id );
		$excerpt = wp_strip_all_tags( $content );
	}
	
	return wp_trim_words( $excerpt, $limit );
}

/**
 * Dark mode toggle
 */
function aqualuxe_dark_mode_toggle() {
	return '<button 
		type="button" 
		class="dark-mode-toggle p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors duration-200" 
		aria-label="' . esc_attr__( 'Toggle dark mode', 'aqualuxe' ) . '"
		x-data="{ dark: localStorage.getItem(\'darkMode\') === \'true\' || (!localStorage.getItem(\'darkMode\') && window.matchMedia(\'(prefers-color-scheme: dark)\').matches) }"
		x-init="
			$watch(\'dark\', val => {
				localStorage.setItem(\'darkMode\', val);
				if (val) {
					document.documentElement.classList.add(\'dark\');
				} else {
					document.documentElement.classList.remove(\'dark\');
				}
			});
			if (dark) {
				document.documentElement.classList.add(\'dark\');
			}
		"
		@click="dark = !dark"
	>
		<svg x-show="!dark" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
			<path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path>
		</svg>
		<svg x-show="dark" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
			<path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
		</svg>
	</button>';
}