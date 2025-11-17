<?php
/**
 * AquaLuxe Performance Lazy Loading Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/Performance
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Lazy load images
 *
 * @param string $content Content
 * @return string
 */
function aqualuxe_performance_lazy_load_images( $content ) {
    // Check if lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_lazy_loading', true ) ) {
        return $content;
    }
    
    // Check if image lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'lazy_load_images', true ) ) {
        return $content;
    }
    
    // Check if content is empty
    if ( empty( $content ) ) {
        return $content;
    }
    
    // Check if content has images
    if ( strpos( $content, '<img' ) === false ) {
        return $content;
    }
    
    // Replace images with lazy loading
    $content = preg_replace_callback( '/<img([^>]+)>/i', 'aqualuxe_performance_lazy_load_images_callback', $content );
    
    return $content;
}

/**
 * Lazy load images callback
 *
 * @param array $matches Matches
 * @return string
 */
function aqualuxe_performance_lazy_load_images_callback( $matches ) {
    // Get image tag
    $image = $matches[0];
    
    // Check if image already has loading attribute
    if ( strpos( $image, 'loading=' ) !== false ) {
        return $image;
    }
    
    // Check if image already has data-src attribute
    if ( strpos( $image, 'data-src=' ) !== false ) {
        return $image;
    }
    
    // Check if image is in a feed
    if ( is_feed() ) {
        return $image;
    }
    
    // Check if image is in admin
    if ( is_admin() ) {
        return $image;
    }
    
    // Check if image is in a widget
    global $wp_current_filter;
    if ( in_array( 'get_the_excerpt', $wp_current_filter, true ) ) {
        return $image;
    }
    
    // Add loading attribute
    $image = str_replace( '<img', '<img loading="lazy"', $image );
    
    return $image;
}

/**
 * Lazy load iframes
 *
 * @param string $content Content
 * @return string
 */
function aqualuxe_performance_lazy_load_iframes( $content ) {
    // Check if lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_lazy_loading', true ) ) {
        return $content;
    }
    
    // Check if iframe lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'lazy_load_iframes', true ) ) {
        return $content;
    }
    
    // Check if content is empty
    if ( empty( $content ) ) {
        return $content;
    }
    
    // Check if content has iframes
    if ( strpos( $content, '<iframe' ) === false ) {
        return $content;
    }
    
    // Replace iframes with lazy loading
    $content = preg_replace_callback( '/<iframe([^>]+)>/i', 'aqualuxe_performance_lazy_load_iframes_callback', $content );
    
    return $content;
}

/**
 * Lazy load iframes callback
 *
 * @param array $matches Matches
 * @return string
 */
function aqualuxe_performance_lazy_load_iframes_callback( $matches ) {
    // Get iframe tag
    $iframe = $matches[0];
    
    // Check if iframe already has loading attribute
    if ( strpos( $iframe, 'loading=' ) !== false ) {
        return $iframe;
    }
    
    // Check if iframe already has data-src attribute
    if ( strpos( $iframe, 'data-src=' ) !== false ) {
        return $iframe;
    }
    
    // Check if iframe is in a feed
    if ( is_feed() ) {
        return $iframe;
    }
    
    // Check if iframe is in admin
    if ( is_admin() ) {
        return $iframe;
    }
    
    // Check if iframe is in a widget
    global $wp_current_filter;
    if ( in_array( 'get_the_excerpt', $wp_current_filter, true ) ) {
        return $iframe;
    }
    
    // Add loading attribute
    $iframe = str_replace( '<iframe', '<iframe loading="lazy"', $iframe );
    
    return $iframe;
}

/**
 * Lazy load videos
 *
 * @param string $content Content
 * @return string
 */
function aqualuxe_performance_lazy_load_videos( $content ) {
    // Check if lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_lazy_loading', true ) ) {
        return $content;
    }
    
    // Check if video lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'lazy_load_videos', true ) ) {
        return $content;
    }
    
    // Check if content is empty
    if ( empty( $content ) ) {
        return $content;
    }
    
    // Check if content has videos
    if ( strpos( $content, '<video' ) === false ) {
        return $content;
    }
    
    // Replace videos with lazy loading
    $content = preg_replace_callback( '/<video([^>]+)>/i', 'aqualuxe_performance_lazy_load_videos_callback', $content );
    
    return $content;
}

/**
 * Lazy load videos callback
 *
 * @param array $matches Matches
 * @return string
 */
function aqualuxe_performance_lazy_load_videos_callback( $matches ) {
    // Get video tag
    $video = $matches[0];
    
    // Check if video already has preload attribute
    if ( strpos( $video, 'preload=' ) !== false ) {
        return $video;
    }
    
    // Check if video already has data-src attribute
    if ( strpos( $video, 'data-src=' ) !== false ) {
        return $video;
    }
    
    // Check if video is in a feed
    if ( is_feed() ) {
        return $video;
    }
    
    // Check if video is in admin
    if ( is_admin() ) {
        return $video;
    }
    
    // Check if video is in a widget
    global $wp_current_filter;
    if ( in_array( 'get_the_excerpt', $wp_current_filter, true ) ) {
        return $video;
    }
    
    // Add preload attribute
    $video = str_replace( '<video', '<video preload="none"', $video );
    
    return $video;
}

/**
 * Enqueue lazy load script
 *
 * @return void
 */
function aqualuxe_performance_enqueue_lazy_load_script() {
    // Check if lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_lazy_loading', true ) ) {
        return;
    }
    
    // Check if any lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'lazy_load_images', true ) && ! aqualuxe_performance_get_option( 'lazy_load_iframes', true ) && ! aqualuxe_performance_get_option( 'lazy_load_videos', true ) ) {
        return;
    }
    
    // Enqueue script
    wp_enqueue_script( 'aqualuxe-lazy-load', aqualuxe_performance_get_module_url() . 'assets/js/lazy-load.js', array(), aqualuxe_performance_get_module_version(), true );
    
    // Localize script
    wp_localize_script( 'aqualuxe-lazy-load', 'aqualuxeLazyLoad', array(
        'images' => aqualuxe_performance_get_option( 'lazy_load_images', true ),
        'iframes' => aqualuxe_performance_get_option( 'lazy_load_iframes', true ),
        'videos' => aqualuxe_performance_get_option( 'lazy_load_videos', true ),
    ) );
}

/**
 * Get module URL
 *
 * @return string
 */
function aqualuxe_performance_get_module_url() {
    // Get module
    $module = AquaLuxe_Module_Loader::instance()->get_module( 'performance' );
    
    // Check if module exists
    if ( ! $module ) {
        return '';
    }
    
    // Get module URI
    return $module->get_module_uri();
}

/**
 * Get module version
 *
 * @return string
 */
function aqualuxe_performance_get_module_version() {
    // Get module
    $module = AquaLuxe_Module_Loader::instance()->get_module( 'performance' );
    
    // Check if module exists
    if ( ! $module ) {
        return '';
    }
    
    // Get module version
    return $module->get_module_version();
}

/**
 * Add lazy loading to featured image
 *
 * @param string $html HTML
 * @param int $post_id Post ID
 * @param int $post_thumbnail_id Post thumbnail ID
 * @param string $size Size
 * @param array $attr Attributes
 * @return string
 */
function aqualuxe_performance_lazy_load_featured_image( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
    // Check if lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_lazy_loading', true ) ) {
        return $html;
    }
    
    // Check if image lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'lazy_load_images', true ) ) {
        return $html;
    }
    
    // Check if HTML is empty
    if ( empty( $html ) ) {
        return $html;
    }
    
    // Check if HTML has image
    if ( strpos( $html, '<img' ) === false ) {
        return $html;
    }
    
    // Check if image already has loading attribute
    if ( strpos( $html, 'loading=' ) !== false ) {
        return $html;
    }
    
    // Check if image already has data-src attribute
    if ( strpos( $html, 'data-src=' ) !== false ) {
        return $html;
    }
    
    // Check if image is in a feed
    if ( is_feed() ) {
        return $html;
    }
    
    // Check if image is in admin
    if ( is_admin() ) {
        return $html;
    }
    
    // Add loading attribute
    $html = str_replace( '<img', '<img loading="lazy"', $html );
    
    return $html;
}

/**
 * Add lazy loading to avatar
 *
 * @param string $avatar Avatar
 * @param mixed $id_or_email ID or email
 * @param int $size Size
 * @param string $default Default
 * @param string $alt Alt
 * @return string
 */
function aqualuxe_performance_lazy_load_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
    // Check if lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_lazy_loading', true ) ) {
        return $avatar;
    }
    
    // Check if image lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'lazy_load_images', true ) ) {
        return $avatar;
    }
    
    // Check if avatar is empty
    if ( empty( $avatar ) ) {
        return $avatar;
    }
    
    // Check if avatar has image
    if ( strpos( $avatar, '<img' ) === false ) {
        return $avatar;
    }
    
    // Check if image already has loading attribute
    if ( strpos( $avatar, 'loading=' ) !== false ) {
        return $avatar;
    }
    
    // Check if image already has data-src attribute
    if ( strpos( $avatar, 'data-src=' ) !== false ) {
        return $avatar;
    }
    
    // Check if image is in a feed
    if ( is_feed() ) {
        return $avatar;
    }
    
    // Check if image is in admin
    if ( is_admin() ) {
        return $avatar;
    }
    
    // Add loading attribute
    $avatar = str_replace( '<img', '<img loading="lazy"', $avatar );
    
    return $avatar;
}

/**
 * Add lazy loading to gallery
 *
 * @param string $content Content
 * @return string
 */
function aqualuxe_performance_lazy_load_gallery( $content ) {
    // Check if lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_lazy_loading', true ) ) {
        return $content;
    }
    
    // Check if image lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'lazy_load_images', true ) ) {
        return $content;
    }
    
    // Check if content is empty
    if ( empty( $content ) ) {
        return $content;
    }
    
    // Check if content has gallery
    if ( strpos( $content, 'gallery' ) === false ) {
        return $content;
    }
    
    // Check if content has images
    if ( strpos( $content, '<img' ) === false ) {
        return $content;
    }
    
    // Replace images with lazy loading
    $content = preg_replace_callback( '/<img([^>]+)>/i', 'aqualuxe_performance_lazy_load_images_callback', $content );
    
    return $content;
}

/**
 * Add lazy loading to widgets
 *
 * @param string $content Content
 * @return string
 */
function aqualuxe_performance_lazy_load_widgets( $content ) {
    // Check if lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_lazy_loading', true ) ) {
        return $content;
    }
    
    // Check if content is empty
    if ( empty( $content ) ) {
        return $content;
    }
    
    // Check if content has images
    if ( strpos( $content, '<img' ) !== false && aqualuxe_performance_get_option( 'lazy_load_images', true ) ) {
        // Replace images with lazy loading
        $content = preg_replace_callback( '/<img([^>]+)>/i', 'aqualuxe_performance_lazy_load_images_callback', $content );
    }
    
    // Check if content has iframes
    if ( strpos( $content, '<iframe' ) !== false && aqualuxe_performance_get_option( 'lazy_load_iframes', true ) ) {
        // Replace iframes with lazy loading
        $content = preg_replace_callback( '/<iframe([^>]+)>/i', 'aqualuxe_performance_lazy_load_iframes_callback', $content );
    }
    
    // Check if content has videos
    if ( strpos( $content, '<video' ) !== false && aqualuxe_performance_get_option( 'lazy_load_videos', true ) ) {
        // Replace videos with lazy loading
        $content = preg_replace_callback( '/<video([^>]+)>/i', 'aqualuxe_performance_lazy_load_videos_callback', $content );
    }
    
    return $content;
}

/**
 * Add lazy loading to comments
 *
 * @param string $content Content
 * @return string
 */
function aqualuxe_performance_lazy_load_comments( $content ) {
    // Check if lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_lazy_loading', true ) ) {
        return $content;
    }
    
    // Check if content is empty
    if ( empty( $content ) ) {
        return $content;
    }
    
    // Check if content has images
    if ( strpos( $content, '<img' ) !== false && aqualuxe_performance_get_option( 'lazy_load_images', true ) ) {
        // Replace images with lazy loading
        $content = preg_replace_callback( '/<img([^>]+)>/i', 'aqualuxe_performance_lazy_load_images_callback', $content );
    }
    
    // Check if content has iframes
    if ( strpos( $content, '<iframe' ) !== false && aqualuxe_performance_get_option( 'lazy_load_iframes', true ) ) {
        // Replace iframes with lazy loading
        $content = preg_replace_callback( '/<iframe([^>]+)>/i', 'aqualuxe_performance_lazy_load_iframes_callback', $content );
    }
    
    // Check if content has videos
    if ( strpos( $content, '<video' ) !== false && aqualuxe_performance_get_option( 'lazy_load_videos', true ) ) {
        // Replace videos with lazy loading
        $content = preg_replace_callback( '/<video([^>]+)>/i', 'aqualuxe_performance_lazy_load_videos_callback', $content );
    }
    
    return $content;
}

/**
 * Register lazy loading hooks
 *
 * @return void
 */
function aqualuxe_performance_register_lazy_loading_hooks() {
    // Check if lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_lazy_loading', true ) ) {
        return;
    }
    
    // Check if any lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'lazy_load_images', true ) && ! aqualuxe_performance_get_option( 'lazy_load_iframes', true ) && ! aqualuxe_performance_get_option( 'lazy_load_videos', true ) ) {
        return;
    }
    
    // Enqueue script
    add_action( 'wp_enqueue_scripts', 'aqualuxe_performance_enqueue_lazy_load_script' );
    
    // Image lazy loading
    if ( aqualuxe_performance_get_option( 'lazy_load_images', true ) ) {
        add_filter( 'the_content', 'aqualuxe_performance_lazy_load_images', 99 );
        add_filter( 'post_thumbnail_html', 'aqualuxe_performance_lazy_load_featured_image', 10, 5 );
        add_filter( 'get_avatar', 'aqualuxe_performance_lazy_load_avatar', 10, 5 );
        add_filter( 'widget_text', 'aqualuxe_performance_lazy_load_images', 99 );
        add_filter( 'widget_text_content', 'aqualuxe_performance_lazy_load_images', 99 );
        add_filter( 'widget_custom_html_content', 'aqualuxe_performance_lazy_load_images', 99 );
        add_filter( 'post_gallery', 'aqualuxe_performance_lazy_load_gallery', 99 );
        add_filter( 'comment_text', 'aqualuxe_performance_lazy_load_images', 99 );
    }
    
    // Iframe lazy loading
    if ( aqualuxe_performance_get_option( 'lazy_load_iframes', true ) ) {
        add_filter( 'the_content', 'aqualuxe_performance_lazy_load_iframes', 99 );
        add_filter( 'widget_text', 'aqualuxe_performance_lazy_load_iframes', 99 );
        add_filter( 'widget_text_content', 'aqualuxe_performance_lazy_load_iframes', 99 );
        add_filter( 'widget_custom_html_content', 'aqualuxe_performance_lazy_load_iframes', 99 );
        add_filter( 'comment_text', 'aqualuxe_performance_lazy_load_iframes', 99 );
    }
    
    // Video lazy loading
    if ( aqualuxe_performance_get_option( 'lazy_load_videos', true ) ) {
        add_filter( 'the_content', 'aqualuxe_performance_lazy_load_videos', 99 );
        add_filter( 'widget_text', 'aqualuxe_performance_lazy_load_videos', 99 );
        add_filter( 'widget_text_content', 'aqualuxe_performance_lazy_load_videos', 99 );
        add_filter( 'widget_custom_html_content', 'aqualuxe_performance_lazy_load_videos', 99 );
        add_filter( 'comment_text', 'aqualuxe_performance_lazy_load_videos', 99 );
    }
}

/**
 * Create lazy load script
 *
 * @return bool|WP_Error
 */
function aqualuxe_performance_create_lazy_load_script() {
    // Get module directory
    $module_dir = aqualuxe_performance_get_module_dir();
    
    // Check if module directory exists
    if ( ! $module_dir ) {
        return new WP_Error( 'module_dir_not_found', __( 'Module directory not found.', 'aqualuxe' ) );
    }
    
    // Set script path
    $script_path = $module_dir . 'assets/js/lazy-load.js';
    
    // Check if script directory exists
    if ( ! file_exists( dirname( $script_path ) ) ) {
        // Create script directory
        wp_mkdir_p( dirname( $script_path ) );
    }
    
    // Script content
    $script = <<<'EOT'
/**
 * AquaLuxe Performance Lazy Loading Script
 *
 * @package AquaLuxe
 * @subpackage Modules/Performance
 * @since 1.0.0
 */

(function() {
    'use strict';
    
    // Check if IntersectionObserver is supported
    if ( ! ('IntersectionObserver' in window) ) {
        // IntersectionObserver not supported
        return;
    }
    
    // Get lazy load options
    var options = window.aqualuxeLazyLoad || {};
    
    // Create IntersectionObserver
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            // Check if element is intersecting
            if ( entry.isIntersecting ) {
                // Get element
                var element = entry.target;
                
                // Check element type
                if ( element.tagName === 'IMG' ) {
                    // Image element
                    if ( element.dataset.src ) {
                        // Set src attribute
                        element.src = element.dataset.src;
                        
                        // Remove data-src attribute
                        element.removeAttribute('data-src');
                    }
                    
                    if ( element.dataset.srcset ) {
                        // Set srcset attribute
                        element.srcset = element.dataset.srcset;
                        
                        // Remove data-srcset attribute
                        element.removeAttribute('data-srcset');
                    }
                } else if ( element.tagName === 'IFRAME' ) {
                    // Iframe element
                    if ( element.dataset.src ) {
                        // Set src attribute
                        element.src = element.dataset.src;
                        
                        // Remove data-src attribute
                        element.removeAttribute('data-src');
                    }
                } else if ( element.tagName === 'VIDEO' ) {
                    // Video element
                    if ( element.dataset.src ) {
                        // Set src attribute
                        element.src = element.dataset.src;
                        
                        // Remove data-src attribute
                        element.removeAttribute('data-src');
                    }
                    
                    // Get source elements
                    var sources = element.querySelectorAll('source');
                    
                    // Loop through source elements
                    for ( var i = 0; i < sources.length; i++ ) {
                        // Get source element
                        var source = sources[i];
                        
                        if ( source.dataset.src ) {
                            // Set src attribute
                            source.src = source.dataset.src;
                            
                            // Remove data-src attribute
                            source.removeAttribute('data-src');
                        }
                    }
                    
                    // Load video
                    element.load();
                }
                
                // Stop observing element
                observer.unobserve(element);
            }
        });
    }, {
        rootMargin: '200px',
    });
    
    // Initialize lazy loading
    function initLazyLoading() {
        // Check if images should be lazy loaded
        if ( options.images ) {
            // Get all images with loading="lazy" attribute
            var images = document.querySelectorAll('img[loading="lazy"]');
            
            // Loop through images
            for ( var i = 0; i < images.length; i++ ) {
                // Get image
                var image = images[i];
                
                // Check if image has src attribute
                if ( image.src ) {
                    // Set data-src attribute
                    image.dataset.src = image.src;
                    
                    // Set src attribute to placeholder
                    image.src = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E';
                }
                
                // Check if image has srcset attribute
                if ( image.srcset ) {
                    // Set data-srcset attribute
                    image.dataset.srcset = image.srcset;
                    
                    // Remove srcset attribute
                    image.removeAttribute('srcset');
                }
                
                // Observe image
                observer.observe(image);
            }
        }
        
        // Check if iframes should be lazy loaded
        if ( options.iframes ) {
            // Get all iframes with loading="lazy" attribute
            var iframes = document.querySelectorAll('iframe[loading="lazy"]');
            
            // Loop through iframes
            for ( var i = 0; i < iframes.length; i++ ) {
                // Get iframe
                var iframe = iframes[i];
                
                // Check if iframe has src attribute
                if ( iframe.src ) {
                    // Set data-src attribute
                    iframe.dataset.src = iframe.src;
                    
                    // Remove src attribute
                    iframe.removeAttribute('src');
                }
                
                // Observe iframe
                observer.observe(iframe);
            }
        }
        
        // Check if videos should be lazy loaded
        if ( options.videos ) {
            // Get all videos with preload="none" attribute
            var videos = document.querySelectorAll('video[preload="none"]');
            
            // Loop through videos
            for ( var i = 0; i < videos.length; i++ ) {
                // Get video
                var video = videos[i];
                
                // Check if video has src attribute
                if ( video.src ) {
                    // Set data-src attribute
                    video.dataset.src = video.src;
                    
                    // Remove src attribute
                    video.removeAttribute('src');
                }
                
                // Get source elements
                var sources = video.querySelectorAll('source');
                
                // Loop through source elements
                for ( var j = 0; j < sources.length; j++ ) {
                    // Get source element
                    var source = sources[j];
                    
                    // Check if source has src attribute
                    if ( source.src ) {
                        // Set data-src attribute
                        source.dataset.src = source.src;
                        
                        // Remove src attribute
                        source.removeAttribute('src');
                    }
                }
                
                // Observe video
                observer.observe(video);
            }
        }
    }
    
    // Initialize lazy loading when DOM is ready
    if ( document.readyState === 'loading' ) {
        // Document still loading
        document.addEventListener('DOMContentLoaded', initLazyLoading);
    } else {
        // Document already loaded
        initLazyLoading();
    }
})();
EOT;
    
    // Save script
    $result = file_put_contents( $script_path, $script );
    
    return $result !== false;
}

/**
 * Get module directory
 *
 * @return string
 */
function aqualuxe_performance_get_module_dir() {
    // Get module
    $module = AquaLuxe_Module_Loader::instance()->get_module( 'performance' );
    
    // Check if module exists
    if ( ! $module ) {
        return '';
    }
    
    // Get module directory
    return $module->get_module_dir();
}

/**
 * Get lazy loading status
 *
 * @return array
 */
function aqualuxe_performance_get_lazy_loading_status() {
    // Build status
    $status = array(
        'enabled' => aqualuxe_performance_get_option( 'enable_lazy_loading', true ),
        'images' => aqualuxe_performance_get_option( 'lazy_load_images', true ),
        'iframes' => aqualuxe_performance_get_option( 'lazy_load_iframes', true ),
        'videos' => aqualuxe_performance_get_option( 'lazy_load_videos', true ),
    );
    
    return $status;
}

/**
 * Get lazy loading status HTML
 *
 * @return string
 */
function aqualuxe_performance_get_lazy_loading_status_html() {
    // Get lazy loading status
    $status = aqualuxe_performance_get_lazy_loading_status();
    
    // Build HTML
    $html = '<div class="aqualuxe-lazy-loading-status">';
    
    // Add status
    $html .= '<div class="aqualuxe-lazy-loading-status-section">';
    $html .= '<h3>' . __( 'Lazy Loading Status', 'aqualuxe' ) . '</h3>';
    $html .= '<table class="aqualuxe-lazy-loading-status-table">';
    $html .= '<tr>';
    $html .= '<th>' . __( 'Enabled', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . ( $status['enabled'] ? __( 'Yes', 'aqualuxe' ) : __( 'No', 'aqualuxe' ) ) . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'Images', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . ( $status['images'] ? __( 'Enabled', 'aqualuxe' ) : __( 'Disabled', 'aqualuxe' ) ) . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'iFrames', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . ( $status['iframes'] ? __( 'Enabled', 'aqualuxe' ) : __( 'Disabled', 'aqualuxe' ) ) . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'Videos', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . ( $status['videos'] ? __( 'Enabled', 'aqualuxe' ) : __( 'Disabled', 'aqualuxe' ) ) . '</td>';
    $html .= '</tr>';
    $html .= '</table>';
    $html .= '</div>';
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Display lazy loading status
 *
 * @return void
 */
function aqualuxe_performance_display_lazy_loading_status() {
    // Get lazy loading status HTML
    $html = aqualuxe_performance_get_lazy_loading_status_html();
    
    // Display lazy loading status
    echo $html;
}