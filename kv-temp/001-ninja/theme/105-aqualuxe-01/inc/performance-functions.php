<?php
/**
 * Performance Optimization Functions
 *
 * Core Web Vitals and performance enhancement functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Optimize Core Web Vitals
 */
function aqualuxe_optimize_core_web_vitals() {
    // Preload critical resources
    add_action('wp_head', 'aqualuxe_preload_critical_resources', 1);
    
    // Optimize images for better CLS
    add_filter('wp_get_attachment_image_attributes', 'aqualuxe_add_image_dimensions', 10, 3);
    
    // Defer non-critical JavaScript
    if (!is_admin()) {
        add_filter('script_loader_tag', 'aqualuxe_defer_non_critical_scripts', 10, 3);
    }
    
    // Optimize CSS delivery
    add_filter('style_loader_tag', 'aqualuxe_optimize_css_delivery', 10, 4);
    
    // Remove unused default styles
    add_action('wp_enqueue_scripts', 'aqualuxe_remove_unused_styles', 100);
}
add_action('init', 'aqualuxe_optimize_core_web_vitals');

/**
 * Preload critical resources
 */
function aqualuxe_preload_critical_resources() {
    // Preload hero image for LCP improvement
    if (is_front_page()) {
        $hero_image = get_theme_mod('aqualuxe_hero_image');
        if ($hero_image) {
            echo '<link rel="preload" as="image" href="' . esc_url($hero_image) . '">' . "\n";
        }
    }
    
    // Preload featured image for posts
    if (is_single() && has_post_thumbnail()) {
        $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
        echo '<link rel="preload" as="image" href="' . esc_url($featured_image) . '">' . "\n";
    }
    
    // Preload product images for WooCommerce
    if (function_exists('is_product') && is_product()) {
        global $product;
        if ($product && $product->get_image_id()) {
            $product_image = wp_get_attachment_image_url($product->get_image_id(), 'large');
            echo '<link rel="preload" as="image" href="' . esc_url($product_image) . '">' . "\n";
        }
    }
}

/**
 * Add image dimensions to prevent CLS
 */
function aqualuxe_add_image_dimensions($attr, $attachment, $size) {
    // Only add dimensions if not already set
    if (empty($attr['width']) || empty($attr['height'])) {
        $image_meta = wp_get_attachment_metadata($attachment->ID);
        
        if ($image_meta && isset($image_meta['width'], $image_meta['height'])) {
            $attr['width'] = $image_meta['width'];
            $attr['height'] = $image_meta['height'];
        }
    }
    
    // Add loading attribute for better performance
    if (empty($attr['loading'])) {
        $attr['loading'] = 'lazy';
    }
    
    // Add decoding attribute
    if (empty($attr['decoding'])) {
        $attr['decoding'] = 'async';
    }
    
    return $attr;
}

/**
 * Defer non-critical JavaScript
 */
function aqualuxe_defer_non_critical_scripts($tag, $handle, $src) {
    // Critical scripts that should not be deferred
    $critical_scripts = apply_filters('aqualuxe_critical_scripts', array(
        'jquery-core',
        'jquery',
        'aqualuxe-critical',
    ));
    
    // Don't defer critical scripts or admin scripts
    if (in_array($handle, $critical_scripts) || is_admin()) {
        return $tag;
    }
    
    // Add defer attribute to non-critical scripts
    if (strpos($tag, 'defer') === false) {
        $tag = str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}

/**
 * Optimize CSS delivery
 */
function aqualuxe_optimize_css_delivery($html, $handle, $href, $media) {
    // Critical CSS handles that should load immediately
    $critical_css = apply_filters('aqualuxe_critical_css_handles', array(
        'aqualuxe-critical',
        'aqualuxe-inline',
    ));
    
    // Don't optimize critical CSS
    if (in_array($handle, $critical_css)) {
        return $html;
    }
    
    // Load non-critical CSS asynchronously
    if ($media === 'all') {
        // Use media="print" trick to load CSS asynchronously
        $html = str_replace("media='all'", "media='print' onload=\"this.media='all'\"", $html);
        
        // Add noscript fallback
        $noscript = '<noscript>' . str_replace("media='print' onload=\"this.media='all'\"", "media='all'", $html) . '</noscript>';
        $html .= $noscript;
    }
    
    return $html;
}

/**
 * Remove unused default WordPress styles
 */
function aqualuxe_remove_unused_styles() {
    // Remove styles that might not be needed
    $styles_to_remove = apply_filters('aqualuxe_styles_to_remove', array(
        'wp-block-library-theme', // Block editor theme styles
        'classic-theme-styles',    // Classic theme styles
    ));
    
    foreach ($styles_to_remove as $style) {
        if (wp_style_is($style, 'enqueued')) {
            wp_dequeue_style($style);
        }
    }
}

/**
 * Enable output buffering for HTML optimization
 */
function aqualuxe_start_output_buffering() {
    if (!is_admin()) {
        ob_start('aqualuxe_optimize_html_output');
    }
}
add_action('template_redirect', 'aqualuxe_start_output_buffering');

/**
 * Optimize HTML output
 */
function aqualuxe_optimize_html_output($html) {
    // Only optimize on frontend
    if (is_admin() || !get_option('aqualuxe_optimize_html', true)) {
        return $html;
    }
    
    // Remove unnecessary whitespace (but preserve pre and textarea content)
    $html = preg_replace_callback(
        '/(<pre[^>]*>.*?<\/pre>|<textarea[^>]*>.*?<\/textarea>)|(\s+)/s',
        function($matches) {
            return isset($matches[1]) ? $matches[1] : ' ';
        },
        $html
    );
    
    // Remove HTML comments (except conditional comments)
    $html = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $html);
    
    // Remove empty lines
    $html = preg_replace('/^\s*[\r\n]/m', '', $html);
    
    return trim($html);
}

/**
 * Add performance monitoring script
 */
function aqualuxe_add_performance_monitoring() {
    if (!WP_DEBUG || !get_option('aqualuxe_performance_monitoring', false)) {
        return;
    }
    
    ?>
    <script>
    // Core Web Vitals monitoring
    (function() {
        'use strict';
        
        // Performance observer for Web Vitals
        function observeWebVitals() {
            if (!('PerformanceObserver' in window)) return;
            
            // Largest Contentful Paint (LCP)
            new PerformanceObserver((list) => {
                const entries = list.getEntries();
                const lastEntry = entries[entries.length - 1];
                console.log('LCP:', Math.round(lastEntry.startTime), 'ms');
                
                // Send to analytics if available
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'web_vitals', {
                        event_category: 'Performance',
                        event_label: 'LCP',
                        value: Math.round(lastEntry.startTime)
                    });
                }
            }).observe({entryTypes: ['largest-contentful-paint']});
            
            // First Input Delay (FID)
            new PerformanceObserver((list) => {
                for (const entry of list.getEntries()) {
                    const fid = entry.processingStart - entry.startTime;
                    console.log('FID:', Math.round(fid), 'ms');
                    
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'web_vitals', {
                            event_category: 'Performance',
                            event_label: 'FID',
                            value: Math.round(fid)
                        });
                    }
                }
            }).observe({entryTypes: ['first-input']});
            
            // Cumulative Layout Shift (CLS)
            let clsValue = 0;
            new PerformanceObserver((list) => {
                for (const entry of list.getEntries()) {
                    if (!entry.hadRecentInput) {
                        clsValue += entry.value;
                    }
                }
                console.log('CLS:', clsValue.toFixed(4));
                
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'web_vitals', {
                        event_category: 'Performance',
                        event_label: 'CLS',
                        value: Math.round(clsValue * 1000)
                    });
                }
            }).observe({entryTypes: ['layout-shift']});
        }
        
        // Run when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', observeWebVitals);
        } else {
            observeWebVitals();
        }
    })();
    </script>
    <?php
}
add_action('wp_footer', 'aqualuxe_add_performance_monitoring');

/**
 * Implement resource hints
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    switch ($relation_type) {
        case 'dns-prefetch':
            // Add domains for external resources
            $domains = apply_filters('aqualuxe_dns_prefetch_domains', array(
                '//fonts.googleapis.com',
                '//fonts.gstatic.com',
                '//api.aqualuxe.com', // Custom API if available
            ));
            $urls = array_merge($urls, $domains);
            break;
            
        case 'preconnect':
            // Preconnect to critical third-party origins
            $origins = apply_filters('aqualuxe_preconnect_origins', array(
                'https://fonts.gstatic.com',
            ));
            $urls = array_merge($urls, $origins);
            break;
            
        case 'prefetch':
            // Prefetch likely next pages
            if (is_single()) {
                // Prefetch related posts
                $related_posts = get_posts(array(
                    'category__in' => wp_get_post_categories(get_the_ID()),
                    'numberposts' => 3,
                    'post__not_in' => array(get_the_ID()),
                ));
                
                foreach ($related_posts as $post) {
                    $urls[] = get_permalink($post->ID);
                }
            }
            break;
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);

/**
 * Add critical CSS inline
 */
function aqualuxe_inline_critical_css() {
    $critical_css_file = AQUALUXE_THEME_DIR . '/assets/dist/css/critical.css';
    
    if (file_exists($critical_css_file) && get_option('aqualuxe_inline_critical_css', true)) {
        $critical_css = file_get_contents($critical_css_file);
        
        if ($critical_css) {
            echo '<style id="aqualuxe-critical-css">';
            echo $critical_css;
            echo '</style>' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_inline_critical_css', 8);

/**
 * Optimize database queries and remove unnecessary actions
 * Note: Some actions consolidated to avoid duplication with performance-functions.php
 */
function aqualuxe_optimize_database_queries() {
    // Disable emoji detection (consolidated here to avoid duplication)
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    
    // Remove query strings from static resources
    if (!is_admin()) {
        add_filter('script_loader_src', 'aqualuxe_remove_script_version', 15, 1);
        add_filter('style_loader_src', 'aqualuxe_remove_script_version', 15, 1);
    }
}
add_action('init', 'aqualuxe_optimize_database_queries');

/**
 * Remove query strings from static resources
 */
function aqualuxe_remove_script_version($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

/**
 * Add service worker for caching
 */
function aqualuxe_add_service_worker() {
    if (get_option('aqualuxe_enable_service_worker', false)) {
        ?>
        <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('<?php echo esc_url(AQUALUXE_ASSETS_URI . '/js/service-worker.js'); ?>')
                .then(function(registration) {
                    <?php if (WP_DEBUG) : ?>
                    console.log('Service Worker registered successfully:', registration.scope);
                    <?php endif; ?>
                })
                .catch(function(error) {
                    <?php if (WP_DEBUG) : ?>
                    console.log('Service Worker registration failed:', error);
                    <?php endif; ?>
                });
            });
        }
        </script>
        <?php
    }
}
add_action('wp_footer', 'aqualuxe_add_service_worker');

/**
 * Image optimization filters
 */
function aqualuxe_optimize_images() {
    // Add WebP support detection
    add_action('wp_head', 'aqualuxe_webp_detection', 1);
    
    // Optimize image loading
    add_filter('wp_get_attachment_image_attributes', 'aqualuxe_optimize_image_attributes', 10, 3);
}
add_action('init', 'aqualuxe_optimize_images');

/**
 * Add WebP detection script
 */
function aqualuxe_webp_detection() {
    ?>
    <script>
    (function() {
        var webP = new Image();
        webP.onload = webP.onerror = function() {
            document.documentElement.classList.add(webP.height == 2 ? 'webp' : 'no-webp');
        };
        webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
    })();
    </script>
    <?php
}

/**
 * Optimize image attributes for better performance
 */
function aqualuxe_optimize_image_attributes($attr, $attachment, $size) {
    // Add fetchpriority for above-the-fold images
    if (empty($attr['fetchpriority'])) {
        // Prioritize hero images and featured images
        if (is_front_page() || (is_single() && has_post_thumbnail())) {
            $attr['fetchpriority'] = 'high';
        }
    }
    
    return $attr;
}

/**
 * Performance settings page integration
 */
function aqualuxe_register_performance_settings() {
    register_setting('aqualuxe_performance', 'aqualuxe_optimize_html');
    register_setting('aqualuxe_performance', 'aqualuxe_inline_critical_css');
    register_setting('aqualuxe_performance', 'aqualuxe_enable_service_worker');
    register_setting('aqualuxe_performance', 'aqualuxe_performance_monitoring');
}
add_action('admin_init', 'aqualuxe_register_performance_settings');