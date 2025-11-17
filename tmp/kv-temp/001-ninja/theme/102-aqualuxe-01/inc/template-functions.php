<?php
/**
 * Template functions for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Get HTML classes for the html element.
 *
 * @return string
 */
function aqualuxe_get_html_classes() {
    $classes = array();
    
    // Add dark mode class if enabled
    if ( aqualuxe_is_dark_mode_enabled() ) {
        $classes[] = 'dark';
    }
    
    // Add reduced motion class if enabled
    if ( aqualuxe_prefers_reduced_motion() ) {
        $classes[] = 'motion-reduce';
    }
    
    return implode( ' ', $classes );
}

/**
 * Check if dark mode is enabled.
 *
 * @return bool
 */
function aqualuxe_is_dark_mode_enabled() {
    // Check for user preference cookie
    if ( isset( $_COOKIE['aqualuxe-dark-mode'] ) ) {
        return $_COOKIE['aqualuxe-dark-mode'] === 'true';
    }
    
    // Check for system preference (can be implemented via JS)
    return false;
}

/**
 * Check if user prefers reduced motion.
 *
 * @return bool
 */
function aqualuxe_prefers_reduced_motion() {
    return isset( $_COOKIE['aqualuxe-reduced-motion'] ) && $_COOKIE['aqualuxe-reduced-motion'] === 'true';
}

/**
 * Check if top bar should be displayed.
 *
 * @return bool
 */
function aqualuxe_has_top_bar() {
    return get_theme_mod( 'aqualuxe_enable_top_bar', true );
}

/**
 * Display top bar left content.
 */
function aqualuxe_top_bar_left() {
    $content = get_theme_mod( 'aqualuxe_top_bar_left', '' );
    
    if ( $content ) {
        echo wp_kses_post( $content );
    } else {
        // Default content
        $phone = get_theme_mod( 'aqualuxe_phone', '' );
        $email = get_theme_mod( 'aqualuxe_email', '' );
        
        if ( $phone ) {
            echo '<span class="contact-info phone mr-4">';
            echo '<i class="icon-phone mr-1"></i>';
            echo '<a href="tel:' . esc_attr( $phone ) . '" class="text-gray-600 dark:text-gray-400 hover:text-aqua-600">';
            echo esc_html( $phone );
            echo '</a>';
            echo '</span>';
        }
        
        if ( $email ) {
            echo '<span class="contact-info email">';
            echo '<i class="icon-email mr-1"></i>';
            echo '<a href="mailto:' . esc_attr( $email ) . '" class="text-gray-600 dark:text-gray-400 hover:text-aqua-600">';
            echo esc_html( $email );
            echo '</a>';
            echo '</span>';
        }
    }
}

/**
 * Display top bar right content.
 */
function aqualuxe_top_bar_right() {
    $content = get_theme_mod( 'aqualuxe_top_bar_right', '' );
    
    if ( $content ) {
        echo wp_kses_post( $content );
    } else {
        // Default content - social links
        aqualuxe_social_links( 'small' );
    }
}

/**
 * Display social links.
 *
 * @param string $size Size of icons (small, medium, large).
 */
function aqualuxe_social_links( $size = 'medium' ) {
    $social_links = array(
        'facebook'  => get_theme_mod( 'aqualuxe_facebook_url', '' ),
        'twitter'   => get_theme_mod( 'aqualuxe_twitter_url', '' ),
        'instagram' => get_theme_mod( 'aqualuxe_instagram_url', '' ),
        'linkedin'  => get_theme_mod( 'aqualuxe_linkedin_url', '' ),
        'youtube'   => get_theme_mod( 'aqualuxe_youtube_url', '' ),
    );
    
    $size_classes = array(
        'small'  => 'w-4 h-4',
        'medium' => 'w-5 h-5',
        'large'  => 'w-6 h-6',
    );
    
    $icon_size = isset( $size_classes[ $size ] ) ? $size_classes[ $size ] : $size_classes['medium'];
    
    if ( array_filter( $social_links ) ) {
        echo '<div class="social-links flex space-x-3">';
        
        foreach ( $social_links as $platform => $url ) {
            if ( $url ) {
                echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" class="text-gray-600 dark:text-gray-400 hover:text-aqua-600 dark:hover:text-aqua-400 transition-colors" aria-label="' . esc_attr( ucfirst( $platform ) ) . '">';
                echo aqualuxe_get_social_icon( $platform, $icon_size );
                echo '</a>';
            }
        }
        
        echo '</div>';
    }
}

/**
 * Get social media icon SVG.
 *
 * @param string $platform Social media platform.
 * @param string $size Icon size classes.
 * @return string
 */
function aqualuxe_get_social_icon( $platform, $size = 'w-5 h-5' ) {
    $icons = array(
        'facebook' => '<svg class="' . $size . '" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
        'twitter' => '<svg class="' . $size . '" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>',
        'instagram' => '<svg class="' . $size . '" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.542 12.015c0-1.92 1.554-3.475 3.475-3.475s3.475 1.555 3.475 3.475-1.555 3.475-3.475 3.475-3.475-1.555-3.475-3.475zm8.815-4.86c-.464 0-.84-.376-.84-.84s.376-.84.84-.84.84.376.84.84-.376.84-.84.84zm-5.34 1.47a2.685 2.685 0 100 5.37 2.685 2.685 0 000-5.37z"/></svg>',
        'linkedin' => '<svg class="' . $size . '" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
        'youtube' => '<svg class="' . $size . '" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
    );
    
    return isset( $icons[ $platform ] ) ? $icons[ $platform ] : '';
}

/**
 * Primary menu fallback.
 */
function aqualuxe_primary_menu_fallback() {
    if ( current_user_can( 'manage_options' ) ) {
        echo '<ul class="flex space-x-8">';
        echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '" class="text-gray-700 dark:text-gray-300 hover:text-aqua-600">' . esc_html__( 'Set up Primary Menu', 'aqualuxe' ) . '</a></li>';
        echo '</ul>';
    }
}

/**
 * Mobile menu fallback.
 */
function aqualuxe_mobile_menu_fallback() {
    if ( current_user_can( 'manage_options' ) ) {
        echo '<ul class="space-y-2">';
        echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '" class="block py-2 text-gray-700 dark:text-gray-300">' . esc_html__( 'Set up Mobile Menu', 'aqualuxe' ) . '</a></li>';
        echo '</ul>';
    }
}

/**
 * Get reading time for a post.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function aqualuxe_get_reading_time( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    $content = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( wp_strip_all_tags( $content ) );
    $reading_time = ceil( $word_count / 200 ); // Average reading speed: 200 words per minute
    
    if ( $reading_time < 1 ) {
        $reading_time = 1;
    }
    
    return sprintf(
        /* translators: %d: Reading time in minutes */
        _n( '%d min read', '%d min read', $reading_time, 'aqualuxe' ),
        $reading_time
    );
}

/**
 * Display breadcrumbs.
 */
function aqualuxe_breadcrumbs() {
    if ( ! get_theme_mod( 'aqualuxe_enable_breadcrumbs', true ) ) {
        return;
    }
    
    // Skip breadcrumbs on home page
    if ( is_front_page() ) {
        return;
    }
    
    $separator = '<span class="separator mx-2 text-gray-400">/</span>';
    $home_title = esc_html__( 'Home', 'aqualuxe' );
    
    echo '<nav class="breadcrumbs py-4 text-sm" aria-label="' . esc_attr__( 'Breadcrumb Navigation', 'aqualuxe' ) . '">';
    echo '<div class="container mx-auto px-4">';
    echo '<ol class="flex items-center space-x-1">';
    
    // Home link
    echo '<li>';
    echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="text-aqua-600 hover:text-aqua-700">' . $home_title . '</a>';
    echo '</li>';
    
    if ( is_category() || is_single() ) {
        echo '<li>' . $separator . '</li>';
        
        if ( is_single() ) {
            $categories = get_the_category();
            if ( $categories ) {
                $category = $categories[0];
                echo '<li>';
                echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="text-aqua-600 hover:text-aqua-700">' . esc_html( $category->name ) . '</a>';
                echo '</li>';
                echo '<li>' . $separator . '</li>';
            }
            
            echo '<li class="text-gray-600">' . esc_html( get_the_title() ) . '</li>';
        } else {
            echo '<li class="text-gray-600">' . esc_html( single_cat_title( '', false ) ) . '</li>';
        }
    } elseif ( is_page() ) {
        echo '<li>' . $separator . '</li>';
        echo '<li class="text-gray-600">' . esc_html( get_the_title() ) . '</li>';
    } elseif ( is_search() ) {
        echo '<li>' . $separator . '</li>';
        echo '<li class="text-gray-600">' . esc_html__( 'Search Results', 'aqualuxe' ) . '</li>';
    } elseif ( is_404() ) {
        echo '<li>' . $separator . '</li>';
        echo '<li class="text-gray-600">' . esc_html__( '404 - Page Not Found', 'aqualuxe' ) . '</li>';
    }
    
    echo '</ol>';
    echo '</div>';
    echo '</nav>';
}

/**
 * Get featured image with responsive sizes.
 *
 * @param string $size Image size.
 * @param array  $attrs Additional attributes.
 * @return string
 */
function aqualuxe_get_responsive_image( $size = 'large', $attrs = array() ) {
    if ( ! has_post_thumbnail() ) {
        return '';
    }
    
    $default_attrs = array(
        'class' => 'w-full h-auto object-cover',
        'loading' => 'lazy',
    );
    
    $attrs = wp_parse_args( $attrs, $default_attrs );
    
    return get_the_post_thumbnail( null, $size, $attrs );
}

/**
 * Truncate text with proper word boundaries.
 *
 * @param string $text Text to truncate.
 * @param int    $length Maximum length.
 * @param string $suffix Suffix to append.
 * @return string
 */
function aqualuxe_truncate_text( $text, $length = 150, $suffix = '...' ) {
    if ( strlen( $text ) <= $length ) {
        return $text;
    }
    
    $text = substr( $text, 0, $length );
    $text = substr( $text, 0, strrpos( $text, ' ' ) );
    
    return $text . $suffix;
}

/**
 * Get theme version for cache busting.
 *
 * @return string
 */
function aqualuxe_get_theme_version() {
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        return time();
    }
    
    return AQUALUXE_VERSION;
}