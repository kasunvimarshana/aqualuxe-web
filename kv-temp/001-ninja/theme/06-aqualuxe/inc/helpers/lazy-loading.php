<?php
/**
 * Lazy loading functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Check if lazy loading is enabled
 *
 * @return bool True if lazy loading is enabled, false otherwise.
 */
function aqualuxe_is_lazy_loading_enabled() {
    return get_theme_mod( 'aqualuxe_lazy_loading', true );
}

/**
 * Add lazy loading attributes to images
 *
 * @param string $content Post content.
 * @return string Modified content.
 */
function aqualuxe_add_lazy_loading_to_content_images( $content ) {
    // Check if lazy loading is enabled
    if ( ! aqualuxe_is_lazy_loading_enabled() ) {
        return $content;
    }
    
    // Don't lazy load images in admin or feeds
    if ( is_admin() || is_feed() ) {
        return $content;
    }
    
    // Don't lazy load if the page is being printed
    if ( isset( $_GET['print'] ) && $_GET['print'] === 'true' ) {
        return $content;
    }
    
    // Don't lazy load AMP pages
    if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
        return $content;
    }
    
    // Replace image tags with lazy loading attributes
    $content = preg_replace_callback( '/<img([^>]+)>/i', 'aqualuxe_lazy_load_image_callback', $content );
    
    return $content;
}
add_filter( 'the_content', 'aqualuxe_add_lazy_loading_to_content_images', 99 );
add_filter( 'widget_text_content', 'aqualuxe_add_lazy_loading_to_content_images', 99 );
add_filter( 'post_thumbnail_html', 'aqualuxe_add_lazy_loading_to_content_images', 99 );
add_filter( 'get_avatar', 'aqualuxe_add_lazy_loading_to_content_images', 99 );

/**
 * Callback function for lazy loading images
 *
 * @param array $matches Regex matches.
 * @return string Modified image tag.
 */
function aqualuxe_lazy_load_image_callback( $matches ) {
    $image_tag = $matches[0];
    $image_attributes = $matches[1];
    
    // Skip if image already has loading attribute
    if ( strpos( $image_attributes, 'loading=' ) !== false ) {
        return $image_tag;
    }
    
    // Skip if image has skip-lazy class
    if ( strpos( $image_attributes, 'class="skip-lazy' ) !== false || strpos( $image_attributes, 'class="no-lazy' ) !== false ) {
        return $image_tag;
    }
    
    // Skip if image is in a noscript tag
    if ( isset( $GLOBALS['in_noscript'] ) && $GLOBALS['in_noscript'] ) {
        return $image_tag;
    }
    
    // Add loading="lazy" attribute
    $image_tag = str_replace( '<img', '<img loading="lazy"', $image_tag );
    
    // Extract src attribute
    preg_match( '/src=["\'](.*?)["\']/i', $image_tag, $src_matches );
    
    if ( ! empty( $src_matches ) ) {
        $src = $src_matches[1];
        
        // Add data-src attribute
        $image_tag = str_replace( 'src="' . $src . '"', 'src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E" data-src="' . $src . '"', $image_tag );
        
        // Add class for JavaScript lazy loading
        if ( strpos( $image_tag, 'class="' ) !== false ) {
            $image_tag = str_replace( 'class="', 'class="lazy ', $image_tag );
        } else {
            $image_tag = str_replace( '<img', '<img class="lazy"', $image_tag );
        }
    }
    
    return $image_tag;
}

/**
 * Add lazy loading attributes to background images
 *
 * @param string $tag HTML tag.
 * @param string $handle Script handle.
 * @param string $src Script source.
 * @return string Modified tag.
 */
function aqualuxe_add_lazy_loading_script( $tag, $handle, $src ) {
    // Check if lazy loading is enabled
    if ( ! aqualuxe_is_lazy_loading_enabled() ) {
        return $tag;
    }
    
    // Add lazy loading script
    if ( $handle === 'aqualuxe-script' ) {
        $lazy_script = "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var lazyImages = [].slice.call(document.querySelectorAll('img.lazy'));
                    var lazyBackgrounds = [].slice.call(document.querySelectorAll('.lazy-background'));
                    
                    if ('IntersectionObserver' in window) {
                        let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                            entries.forEach(function(entry) {
                                if (entry.isIntersecting) {
                                    let lazyImage = entry.target;
                                    if (lazyImage.dataset.src) {
                                        lazyImage.src = lazyImage.dataset.src;
                                        lazyImage.removeAttribute('data-src');
                                    }
                                    if (lazyImage.dataset.srcset) {
                                        lazyImage.srcset = lazyImage.dataset.srcset;
                                        lazyImage.removeAttribute('data-srcset');
                                    }
                                    lazyImage.classList.remove('lazy');
                                    lazyImageObserver.unobserve(lazyImage);
                                }
                            });
                        });
                        
                        lazyImages.forEach(function(lazyImage) {
                            lazyImageObserver.observe(lazyImage);
                        });
                        
                        let lazyBackgroundObserver = new IntersectionObserver(function(entries, observer) {
                            entries.forEach(function(entry) {
                                if (entry.isIntersecting) {
                                    let lazyBackground = entry.target;
                                    if (lazyBackground.dataset.background) {
                                        lazyBackground.style.backgroundImage = 'url(' + lazyBackground.dataset.background + ')';
                                        lazyBackground.classList.remove('lazy-background');
                                        lazyBackgroundObserver.unobserve(lazyBackground);
                                    }
                                }
                            });
                        });
                        
                        lazyBackgrounds.forEach(function(lazyBackground) {
                            lazyBackgroundObserver.observe(lazyBackground);
                        });
                    } else {
                        // Fallback for browsers that don't support IntersectionObserver
                        var lazyLoadThrottleTimeout;
                        
                        function lazyLoad() {
                            if (lazyLoadThrottleTimeout) {
                                clearTimeout(lazyLoadThrottleTimeout);
                            }
                            
                            lazyLoadThrottleTimeout = setTimeout(function() {
                                var scrollTop = window.pageYOffset;
                                
                                lazyImages.forEach(function(lazyImage) {
                                    if (lazyImage.offsetTop < (window.innerHeight + scrollTop)) {
                                        if (lazyImage.dataset.src) {
                                            lazyImage.src = lazyImage.dataset.src;
                                            lazyImage.removeAttribute('data-src');
                                        }
                                        if (lazyImage.dataset.srcset) {
                                            lazyImage.srcset = lazyImage.dataset.srcset;
                                            lazyImage.removeAttribute('data-srcset');
                                        }
                                        lazyImage.classList.remove('lazy');
                                    }
                                });
                                
                                lazyBackgrounds.forEach(function(lazyBackground) {
                                    if (lazyBackground.offsetTop < (window.innerHeight + scrollTop)) {
                                        if (lazyBackground.dataset.background) {
                                            lazyBackground.style.backgroundImage = 'url(' + lazyBackground.dataset.background + ')';
                                            lazyBackground.classList.remove('lazy-background');
                                        }
                                    }
                                });
                                
                                if (lazyImages.length === 0 && lazyBackgrounds.length === 0) {
                                    document.removeEventListener('scroll', lazyLoad);
                                    window.removeEventListener('resize', lazyLoad);
                                    window.removeEventListener('orientationchange', lazyLoad);
                                }
                            }, 20);
                        }
                        
                        document.addEventListener('scroll', lazyLoad);
                        window.addEventListener('resize', lazyLoad);
                        window.addEventListener('orientationchange', lazyLoad);
                        lazyLoad();
                    }
                });
            </script>
        ";
        
        return $tag . $lazy_script;
    }
    
    return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_add_lazy_loading_script', 10, 3 );

/**
 * Add lazy loading attributes to WooCommerce product images
 *
 * @param array $attributes Image attributes.
 * @return array Modified attributes.
 */
function aqualuxe_add_lazy_loading_to_woocommerce_images( $attributes ) {
    // Check if lazy loading is enabled
    if ( ! aqualuxe_is_lazy_loading_enabled() ) {
        return $attributes;
    }
    
    // Don't lazy load images in admin or feeds
    if ( is_admin() || is_feed() ) {
        return $attributes;
    }
    
    // Don't lazy load if the page is being printed
    if ( isset( $_GET['print'] ) && $_GET['print'] === 'true' ) {
        return $attributes;
    }
    
    // Don't lazy load AMP pages
    if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
        return $attributes;
    }
    
    // Skip if image already has loading attribute
    if ( isset( $attributes['loading'] ) ) {
        return $attributes;
    }
    
    // Skip if image has skip-lazy class
    if ( isset( $attributes['class'] ) && ( strpos( $attributes['class'], 'skip-lazy' ) !== false || strpos( $attributes['class'], 'no-lazy' ) !== false ) ) {
        return $attributes;
    }
    
    // Add loading="lazy" attribute
    $attributes['loading'] = 'lazy';
    
    // Add class for JavaScript lazy loading
    if ( isset( $attributes['class'] ) ) {
        $attributes['class'] .= ' lazy';
    } else {
        $attributes['class'] = 'lazy';
    }
    
    // Add data-src attribute
    if ( isset( $attributes['src'] ) ) {
        $attributes['data-src'] = $attributes['src'];
        $attributes['src'] = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E';
    }
    
    // Add data-srcset attribute
    if ( isset( $attributes['srcset'] ) ) {
        $attributes['data-srcset'] = $attributes['srcset'];
        unset( $attributes['srcset'] );
    }
    
    return $attributes;
}
add_filter( 'wp_get_attachment_image_attributes', 'aqualuxe_add_lazy_loading_to_woocommerce_images', 99 );

/**
 * Add lazy loading CSS
 */
function aqualuxe_add_lazy_loading_css() {
    // Check if lazy loading is enabled
    if ( ! aqualuxe_is_lazy_loading_enabled() ) {
        return;
    }
    
    // Add lazy loading CSS
    $css = '
        /* Lazy Loading Styles */
        .lazy {
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        
        .lazy.loaded {
            opacity: 1;
        }
        
        .lazy-background {
            background-image: none !important;
            background-color: #f5f5f5;
        }
    ';
    
    wp_add_inline_style( 'aqualuxe-style', $css );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_add_lazy_loading_css', 99 );

/**
 * Add noscript fallback for lazy loaded images
 *
 * @param string $content Post content.
 * @return string Modified content.
 */
function aqualuxe_add_noscript_fallback( $content ) {
    // Check if lazy loading is enabled
    if ( ! aqualuxe_is_lazy_loading_enabled() ) {
        return $content;
    }
    
    // Don't add noscript fallback in admin or feeds
    if ( is_admin() || is_feed() ) {
        return $content;
    }
    
    // Don't add noscript fallback if the page is being printed
    if ( isset( $_GET['print'] ) && $_GET['print'] === 'true' ) {
        return $content;
    }
    
    // Don't add noscript fallback for AMP pages
    if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
        return $content;
    }
    
    // Add noscript fallback
    $content = preg_replace_callback( '/<img([^>]+)class="([^"]*?)lazy([^"]*?)"([^>]+)>/i', 'aqualuxe_noscript_fallback_callback', $content );
    
    return $content;
}
add_filter( 'the_content', 'aqualuxe_add_noscript_fallback', 100 );
add_filter( 'widget_text_content', 'aqualuxe_add_noscript_fallback', 100 );
add_filter( 'post_thumbnail_html', 'aqualuxe_add_noscript_fallback', 100 );
add_filter( 'get_avatar', 'aqualuxe_add_noscript_fallback', 100 );

/**
 * Callback function for noscript fallback
 *
 * @param array $matches Regex matches.
 * @return string Modified image tag with noscript fallback.
 */
function aqualuxe_noscript_fallback_callback( $matches ) {
    $image_tag = $matches[0];
    
    // Extract data-src attribute
    preg_match( '/data-src=["\'](.*?)["\']/i', $image_tag, $src_matches );
    
    if ( ! empty( $src_matches ) ) {
        $data_src = $src_matches[1];
        
        // Create noscript fallback
        $noscript_tag = str_replace( 'data-src="' . $data_src . '"', 'src="' . $data_src . '"', $image_tag );
        $noscript_tag = str_replace( 'class="', 'class="skip-lazy ', $noscript_tag );
        $noscript_tag = str_replace( 'src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E"', '', $noscript_tag );
        
        // Set global flag to prevent recursive processing
        $GLOBALS['in_noscript'] = true;
        $noscript_html = '<noscript>' . $noscript_tag . '</noscript>';
        unset( $GLOBALS['in_noscript'] );
        
        return $image_tag . $noscript_html;
    }
    
    return $image_tag;
}