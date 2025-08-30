<?php
/**
 * Lazy Loading Implementation
 *
 * @package AquaLuxe
 * @subpackage Modules/Performance
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add lazy loading to images in content
 *
 * @param string $content Post content.
 * @return string
 */
function aqualuxe_performance_lazy_load_content( $content ) {
    if ( ! aqualuxe_is_lazy_loading_enabled() || is_admin() || is_feed() ) {
        return $content;
    }

    // Replace images with lazy loading attributes
    $content = preg_replace_callback(
        '/<img([^>]+)>/i',
        'aqualuxe_performance_lazy_load_image_callback',
        $content
    );

    // Replace iframes with lazy loading attributes
    $content = preg_replace_callback(
        '/<iframe([^>]+)>/i',
        'aqualuxe_performance_lazy_load_iframe_callback',
        $content
    );

    return $content;
}
add_filter( 'the_content', 'aqualuxe_performance_lazy_load_content', 99 );
add_filter( 'widget_text_content', 'aqualuxe_performance_lazy_load_content', 99 );

/**
 * Callback function for lazy loading images
 *
 * @param array $matches Regex matches.
 * @return string
 */
function aqualuxe_performance_lazy_load_image_callback( $matches ) {
    $image = $matches[0];
    $attributes = $matches[1];

    // Skip if already has lazy loading attributes
    if ( strpos( $attributes, 'data-src' ) !== false || strpos( $attributes, 'loading="lazy"' ) !== false ) {
        return $image;
    }

    // Skip if has skip-lazy class
    if ( strpos( $attributes, 'class="' ) !== false && strpos( $attributes, 'skip-lazy' ) !== false ) {
        return $image;
    }

    // Extract src attribute
    preg_match( '/src=(["\'])(.*?)\1/', $attributes, $src_matches );
    if ( empty( $src_matches ) ) {
        return $image;
    }

    $src = $src_matches[2];

    // Create placeholder
    $placeholder = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E';

    // Extract width and height if available
    $width = 1;
    $height = 1;
    
    if ( preg_match( '/width=(["\'])(.*?)\1/', $attributes, $width_matches ) ) {
        $width = $width_matches[2];
    }
    
    if ( preg_match( '/height=(["\'])(.*?)\1/', $attributes, $height_matches ) ) {
        $height = $height_matches[2];
    }
    
    // Create better placeholder with correct aspect ratio
    if ( $width > 1 && $height > 1 ) {
        $placeholder = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 ' . $width . ' ' . $height . '\'%3E%3C/svg%3E';
    }

    // Add lazy loading class
    if ( strpos( $attributes, 'class="' ) !== false ) {
        $attributes = preg_replace( '/class="(.*?)"/', 'class="$1 lazy-load"', $attributes );
    } else {
        $attributes .= ' class="lazy-load"';
    }

    // Replace src with data-src and add placeholder
    $new_attributes = str_replace(
        'src="' . $src . '"',
        'src="' . $placeholder . '" data-src="' . $src . '"',
        $attributes
    );

    // Add loading="lazy" for native lazy loading
    if ( strpos( $new_attributes, 'loading=' ) === false ) {
        $new_attributes .= ' loading="lazy"';
    }

    // Add noscript fallback
    $new_image = '<img' . $new_attributes . '><noscript><img src="' . $src . '" ' . $attributes . '></noscript>';

    return $new_image;
}

/**
 * Callback function for lazy loading iframes
 *
 * @param array $matches Regex matches.
 * @return string
 */
function aqualuxe_performance_lazy_load_iframe_callback( $matches ) {
    $iframe = $matches[0];
    $attributes = $matches[1];

    // Skip if already has lazy loading attributes
    if ( strpos( $attributes, 'data-src' ) !== false || strpos( $attributes, 'loading="lazy"' ) !== false ) {
        return $iframe;
    }

    // Skip if has skip-lazy class
    if ( strpos( $attributes, 'class="' ) !== false && strpos( $attributes, 'skip-lazy' ) !== false ) {
        return $iframe;
    }

    // Extract src attribute
    preg_match( '/src=(["\'])(.*?)\1/', $attributes, $src_matches );
    if ( empty( $src_matches ) ) {
        return $iframe;
    }

    $src = $src_matches[2];

    // Add lazy loading class
    if ( strpos( $attributes, 'class="' ) !== false ) {
        $attributes = preg_replace( '/class="(.*?)"/', 'class="$1 lazy-load"', $attributes );
    } else {
        $attributes .= ' class="lazy-load"';
    }

    // Replace src with data-src
    $new_attributes = str_replace(
        'src="' . $src . '"',
        'data-src="' . $src . '"',
        $attributes
    );

    // Add loading="lazy" for native lazy loading
    if ( strpos( $new_attributes, 'loading=' ) === false ) {
        $new_attributes .= ' loading="lazy"';
    }

    // Add noscript fallback
    $new_iframe = '<iframe' . $new_attributes . '></iframe><noscript><iframe src="' . $src . '" ' . $attributes . '></iframe></noscript>';

    return $new_iframe;
}

/**
 * Add lazy loading to post thumbnails
 *
 * @param string $html Post thumbnail HTML.
 * @param int    $post_id Post ID.
 * @param int    $post_thumbnail_id Post thumbnail ID.
 * @param string $size Size.
 * @param array  $attr Attributes.
 * @return string
 */
function aqualuxe_performance_lazy_load_post_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
    if ( ! aqualuxe_is_lazy_loading_enabled() || is_admin() || is_feed() ) {
        return $html;
    }

    return aqualuxe_performance_lazy_load_content( $html );
}
add_filter( 'post_thumbnail_html', 'aqualuxe_performance_lazy_load_post_thumbnail', 10, 5 );

/**
 * Add lazy loading to attachment images
 *
 * @param array  $attr Attributes.
 * @param object $attachment Attachment.
 * @param string $size Size.
 * @return array
 */
function aqualuxe_performance_lazy_load_attachment_image( $attr, $attachment, $size ) {
    if ( ! aqualuxe_is_lazy_loading_enabled() || is_admin() || is_feed() ) {
        return $attr;
    }

    // Skip if already has lazy loading attributes
    if ( isset( $attr['data-src'] ) || ( isset( $attr['loading'] ) && $attr['loading'] === 'lazy' ) ) {
        return $attr;
    }

    // Skip if has skip-lazy class
    if ( isset( $attr['class'] ) && strpos( $attr['class'], 'skip-lazy' ) !== false ) {
        return $attr;
    }

    // Add lazy loading attributes
    $attr['data-src'] = $attr['src'];
    
    // Create placeholder
    $placeholder = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E';
    
    // Use better placeholder with correct aspect ratio if width and height are available
    if ( isset( $attr['width'] ) && isset( $attr['height'] ) && $attr['width'] > 1 && $attr['height'] > 1 ) {
        $placeholder = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 ' . $attr['width'] . ' ' . $attr['height'] . '\'%3E%3C/svg%3E';
    }
    
    $attr['src'] = $placeholder;
    $attr['class'] = isset( $attr['class'] ) ? $attr['class'] . ' lazy-load' : 'lazy-load';
    $attr['loading'] = 'lazy';

    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_performance_lazy_load_attachment_image', 10, 3 );

/**
 * Add lazy loading to avatar
 *
 * @param string $avatar Avatar HTML.
 * @param mixed  $id_or_email User ID or email.
 * @param int    $size Size.
 * @param string $default Default avatar.
 * @param string $alt Alternative text.
 * @return string
 */
function aqualuxe_performance_lazy_load_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
    if ( ! aqualuxe_is_lazy_loading_enabled() || is_admin() || is_feed() ) {
        return $avatar;
    }

    return aqualuxe_performance_lazy_load_content( $avatar );
}
add_filter( 'get_avatar', 'aqualuxe_performance_lazy_load_avatar', 10, 5 );

/**
 * Add lazy loading to gallery
 *
 * @param string $content Gallery HTML.
 * @return string
 */
function aqualuxe_performance_lazy_load_gallery( $content ) {
    if ( ! aqualuxe_is_lazy_loading_enabled() || is_admin() || is_feed() ) {
        return $content;
    }

    return aqualuxe_performance_lazy_load_content( $content );
}
add_filter( 'gallery_style', 'aqualuxe_performance_lazy_load_gallery' );

/**
 * Enqueue lazy loading script
 *
 * @return void
 */
function aqualuxe_performance_enqueue_lazy_loading_script() {
    if ( ! aqualuxe_is_lazy_loading_enabled() || is_admin() || is_feed() ) {
        return;
    }

    wp_enqueue_script(
        'aqualuxe-lazy-loading',
        AQUALUXE_THEME_URI . 'modules/performance/assets/js/lazy-loading.js',
        array( 'jquery' ),
        AQUALUXE_VERSION,
        true
    );

    wp_localize_script(
        'aqualuxe-lazy-loading',
        'aqualuxeLazyLoading',
        array(
            'threshold' => 200,
            'fadeIn' => true,
            'fadeInDuration' => 400,
        )
    );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_performance_enqueue_lazy_loading_script' );