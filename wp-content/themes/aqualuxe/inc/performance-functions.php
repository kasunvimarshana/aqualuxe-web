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
// Performance monitoring function moved to core/functions/enqueue-scripts.php to avoid duplication

// Resource hints function moved to core/functions/enqueue-scripts.php to avoid duplication

// Critical CSS function moved to core/functions/enqueue-scripts.php to avoid duplication

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
    
    // Query string removal is handled in security.php to avoid duplication
}
add_action('init', 'aqualuxe_optimize_database_queries');

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