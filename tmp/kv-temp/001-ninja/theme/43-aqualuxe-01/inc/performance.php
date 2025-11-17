<?php
/**
 * Performance Optimization Functions
 *
 * @package AquaLuxe
 */

/**
 * Implement lazy loading for images.
 *
 * @param string $content The content to be filtered.
 * @return string The filtered content.
 */
function aqualuxe_lazy_load_images($content) {
    // Don't lazy load if the content is in the admin or a feed
    if (is_admin() || is_feed()) {
        return $content;
    }

    // Don't lazy load if AMP is active
    if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
        return $content;
    }

    // Replace img tags with lazy loading attributes
    $content = preg_replace_callback('/<img([^>]+)>/i', 'aqualuxe_lazy_load_image_callback', $content);

    return $content;
}
add_filter('the_content', 'aqualuxe_lazy_load_images', 99);
add_filter('post_thumbnail_html', 'aqualuxe_lazy_load_images', 99);
add_filter('widget_text', 'aqualuxe_lazy_load_images', 99);

/**
 * Callback function for lazy loading images.
 *
 * @param array $matches The regex matches.
 * @return string The modified img tag.
 */
function aqualuxe_lazy_load_image_callback($matches) {
    $img_tag = $matches[0];
    $img_attr = $matches[1];

    // Skip if the image already has loading attribute
    if (strpos($img_attr, 'loading=') !== false) {
        return $img_tag;
    }

    // Skip if the image has a skip-lazy class
    if (strpos($img_attr, 'class="') !== false) {
        $classes = preg_match('/class="([^"]+)"/i', $img_attr, $class_matches);
        if ($classes && strpos($class_matches[1], 'skip-lazy') !== false) {
            return $img_tag;
        }
    }

    // Add loading="lazy" attribute
    $img_tag = str_replace('<img', '<img loading="lazy"', $img_tag);

    // Add lazy class
    if (strpos($img_attr, 'class="') !== false) {
        $img_tag = preg_replace('/class="([^"]+)"/i', 'class="$1 lazy"', $img_tag);
    } else {
        $img_tag = str_replace('<img', '<img class="lazy"', $img_tag);
    }

    // Add data-src attribute if not already present
    if (strpos($img_attr, 'data-src=') === false && preg_match('/src="([^"]+)"/i', $img_attr, $src_matches)) {
        $img_tag = str_replace('src="' . $src_matches[1] . '"', 'src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E" data-src="' . $src_matches[1] . '"', $img_tag);
    }

    // Add data-srcset attribute if not already present
    if (strpos($img_attr, 'data-srcset=') === false && preg_match('/srcset="([^"]+)"/i', $img_attr, $srcset_matches)) {
        $img_tag = str_replace('srcset="' . $srcset_matches[1] . '"', 'data-srcset="' . $srcset_matches[1] . '"', $img_tag);
    }

    return $img_tag;
}

/**
 * Implement lazy loading for background images.
 *
 * @param string $html The HTML to be filtered.
 * @return string The filtered HTML.
 */
function aqualuxe_lazy_load_background_images($html) {
    // Don't lazy load if the content is in the admin or a feed
    if (is_admin() || is_feed()) {
        return $html;
    }

    // Don't lazy load if AMP is active
    if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
        return $html;
    }

    // Find elements with inline background-image style
    $html = preg_replace_callback('/<([a-z]+)([^>]*?style=["\'][^"\']*?background-image:\s*url\(["\']?([^"\'()]+)["\']?\)[^>]*?)>/i', 'aqualuxe_lazy_load_background_callback', $html);

    return $html;
}
add_filter('the_content', 'aqualuxe_lazy_load_background_images', 99);
add_filter('widget_text', 'aqualuxe_lazy_load_background_images', 99);

/**
 * Callback function for lazy loading background images.
 *
 * @param array $matches The regex matches.
 * @return string The modified element tag.
 */
function aqualuxe_lazy_load_background_callback($matches) {
    $tag = $matches[1];
    $attributes = $matches[2];
    $image_url = $matches[3];

    // Skip if the element already has data-bg attribute
    if (strpos($attributes, 'data-bg=') !== false) {
        return "<{$tag}{$attributes}>";
    }

    // Skip if the element has a skip-lazy class
    if (strpos($attributes, 'class="') !== false) {
        $classes = preg_match('/class="([^"]+)"/i', $attributes, $class_matches);
        if ($classes && strpos($class_matches[1], 'skip-lazy') !== false) {
            return "<{$tag}{$attributes}>";
        }
    }

    // Add lazy-background class
    if (strpos($attributes, 'class="') !== false) {
        $attributes = preg_replace('/class="([^"]+)"/i', 'class="$1 lazy-background"', $attributes);
    } else {
        $attributes .= ' class="lazy-background"';
    }

    // Remove the background-image style and add data-background attribute
    $attributes = preg_replace('/style=(["\'])(.*?)background-image:\s*url\(["\']?([^"\'()]+)["\']?\)(.*?)\1/i', 'style=$1$2$4$1 data-background="' . $image_url . '"', $attributes);

    return "<{$tag}{$attributes}>";
}

/**
 * Add preload for critical resources.
 */
function aqualuxe_preload_critical_resources() {
    // Preload critical CSS
    echo '<link rel="preload" href="' . esc_url(get_template_directory_uri() . '/assets/dist/css/critical.css') . '" as="style">';
    
    // Preload logo
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        $logo_info = wp_get_attachment_image_src($custom_logo_id, 'full');
        if ($logo_info) {
            echo '<link rel="preload" href="' . esc_url($logo_info[0]) . '" as="image">';
        }
    }
    
    // Preload hero image on homepage
    if (is_front_page()) {
        $hero_image_id = get_theme_mod('aqualuxe_hero_image');
        if ($hero_image_id) {
            $hero_image = wp_get_attachment_image_src($hero_image_id, 'full');
            if ($hero_image) {
                echo '<link rel="preload" href="' . esc_url($hero_image[0]) . '" as="image">';
            }
        }
    }
    
    // Preload fonts
    echo '<link rel="preload" href="' . esc_url(get_template_directory_uri() . '/assets/dist/fonts/playfair-display-v30-latin-regular.woff2') . '" as="font" type="font/woff2" crossorigin>';
    echo '<link rel="preload" href="' . esc_url(get_template_directory_uri() . '/assets/dist/fonts/montserrat-v25-latin-regular.woff2') . '" as="font" type="font/woff2" crossorigin>';
}
add_action('wp_head', 'aqualuxe_preload_critical_resources', 1);

/**
 * Add critical CSS inline.
 */
function aqualuxe_add_critical_css() {
    $critical_css_path = get_template_directory() . '/assets/dist/css/critical.css';
    
    if (file_exists($critical_css_path)) {
        $critical_css = file_get_contents($critical_css_path);
        if ($critical_css) {
            echo '<style id="aqualuxe-critical-css">' . $critical_css . '</style>';
        }
    }
}
add_action('wp_head', 'aqualuxe_add_critical_css', 1);

/**
 * Defer non-critical CSS.
 */
function aqualuxe_defer_non_critical_css($tag, $handle, $src) {
    // List of critical CSS handles that should not be deferred
    $critical_css = array('aqualuxe-critical-css');
    
    if (in_array($handle, $critical_css)) {
        return $tag;
    }
    
    // Don't defer in admin
    if (is_admin()) {
        return $tag;
    }
    
    // Add onload attribute to link tag
    $tag = str_replace("rel='stylesheet'", "rel='preload' as='style' onload=&quot;this.onload=null;this.rel='stylesheet'&quot;", $tag);
    
    // Add fallback for browsers that don't support preload
    $tag .= "<noscript><link rel='stylesheet' href='" . esc_url($src) . "'></noscript>";
    
    return $tag;
}
add_filter('style_loader_tag', 'aqualuxe_defer_non_critical_css', 10, 3);

/**
 * Defer non-critical JavaScript.
 */
function aqualuxe_defer_js($tag, $handle, $src) {
    // List of scripts that should not be deferred
    $no_defer = array('jquery', 'jquery-core', 'jquery-migrate');
    
    if (in_array($handle, $no_defer)) {
        return $tag;
    }
    
    // Don't defer in admin
    if (is_admin()) {
        return $tag;
    }
    
    // Add defer attribute to script tag
    if (strpos($tag, 'defer') === false) {
        $tag = str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_defer_js', 10, 3);

/**
 * Remove query strings from static resources.
 */
function aqualuxe_remove_script_version($src) {
    // Don't remove query strings in admin
    if (is_admin()) {
        return $src;
    }
    
    // Remove query string from static resources
    $parts = explode('?', $src);
    return $parts[0];
}
add_filter('script_loader_src', 'aqualuxe_remove_script_version', 15);
add_filter('style_loader_src', 'aqualuxe_remove_script_version', 15);

/**
 * Set browser cache expiration.
 */
function aqualuxe_browser_cache_headers() {
    if (is_admin()) {
        return;
    }
    
    // Get file extension
    $file = $_SERVER['REQUEST_URI'];
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    
    // Set cache expiration based on file type
    switch ($ext) {
        case 'css':
        case 'js':
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'webp':
        case 'svg':
        case 'ico':
        case 'woff':
        case 'woff2':
        case 'ttf':
        case 'eot':
            // Cache for 1 year
            header('Cache-Control: public, max-age=31536000');
            break;
            
        default:
            // Cache for 1 day
            header('Cache-Control: public, max-age=86400');
            break;
    }
}
add_action('send_headers', 'aqualuxe_browser_cache_headers');

/**
 * Optimize database queries.
 */
function aqualuxe_optimize_queries() {
    // Disable post revisions
    if (!defined('WP_POST_REVISIONS')) {
        define('WP_POST_REVISIONS', 3);
    }
    
    // Disable auto-save
    if (!defined('AUTOSAVE_INTERVAL')) {
        define('AUTOSAVE_INTERVAL', 160);
    }
    
    // Disable WordPress emojis
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    
    // Disable embeds
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    
    // Remove REST API links
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('template_redirect', 'rest_output_link_header', 11);
    
    // Remove wlwmanifest link
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Remove RSD link
    remove_action('wp_head', 'rsd_link');
    
    // Remove WordPress version
    remove_action('wp_head', 'wp_generator');
    
    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
}
add_action('init', 'aqualuxe_optimize_queries');

/**
 * Disable unnecessary features.
 */
function aqualuxe_disable_features() {
    // Disable XML-RPC
    add_filter('xmlrpc_enabled', '__return_false');
    
    // Disable X-Pingback
    add_filter('wp_headers', function($headers) {
        unset($headers['X-Pingback']);
        return $headers;
    });
    
    // Disable pingbacks
    add_filter('xmlrpc_methods', function($methods) {
        unset($methods['pingback.ping']);
        return $methods;
    });
}
add_action('init', 'aqualuxe_disable_features');

/**
 * Add image dimensions to img tags.
 */
function aqualuxe_add_image_dimensions($content) {
    // Don't process in admin or feeds
    if (is_admin() || is_feed()) {
        return $content;
    }
    
    // Find img tags without width and height attributes
    $content = preg_replace_callback('/<img([^>]+)>/i', function($matches) {
        $img_tag = $matches[0];
        $img_attr = $matches[1];
        
        // Skip if the image already has width and height attributes
        if (strpos($img_attr, 'width=') !== false && strpos($img_attr, 'height=') !== false) {
            return $img_tag;
        }
        
        // Get image source
        if (preg_match('/src="([^"]+)"/i', $img_attr, $src_matches)) {
            $src = $src_matches[1];
            
            // Get image dimensions
            $upload_dir = wp_upload_dir();
            $upload_url = $upload_dir['baseurl'];
            
            if (strpos($src, $upload_url) === 0) {
                $file_path = str_replace($upload_url, $upload_dir['basedir'], $src);
                
                if (file_exists($file_path)) {
                    $dimensions = getimagesize($file_path);
                    
                    if ($dimensions) {
                        $width = $dimensions[0];
                        $height = $dimensions[1];
                        
                        // Add width and height attributes
                        $img_tag = str_replace('<img', '<img width="' . $width . '" height="' . $height . '"', $img_tag);
                    }
                }
            }
        }
        
        return $img_tag;
    }, $content);
    
    return $content;
}
add_filter('the_content', 'aqualuxe_add_image_dimensions', 100);
add_filter('post_thumbnail_html', 'aqualuxe_add_image_dimensions', 100);
add_filter('widget_text', 'aqualuxe_add_image_dimensions', 100);

/**
 * Add DNS prefetch for external domains.
 */
function aqualuxe_dns_prefetch() {
    echo '<meta http-equiv="x-dns-prefetch-control" content="on">';
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">';
    echo '<link rel="dns-prefetch" href="//ajax.googleapis.com">';
    echo '<link rel="dns-prefetch" href="//s.w.org">';
    echo '<link rel="dns-prefetch" href="//www.google-analytics.com">';
    echo '<link rel="dns-prefetch" href="//www.googletagmanager.com">';
}
add_action('wp_head', 'aqualuxe_dns_prefetch', 0);

/**
 * Add preconnect for external domains.
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
            'crossorigin',
        );
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);

/**
 * Generate critical CSS.
 */
function aqualuxe_generate_critical_css() {
    // This function would typically be called during theme activation or settings update
    // It would use a tool like Critical or CriticalCSS to extract critical CSS
    // For now, we'll just create a placeholder function
    
    // Path to critical CSS file
    $critical_css_path = get_template_directory() . '/assets/dist/css/critical.css';
    
    // Create directory if it doesn't exist
    $dir = dirname($critical_css_path);
    if (!file_exists($dir)) {
        wp_mkdir_p($dir);
    }
    
    // Sample critical CSS content
    $critical_css = "/* Critical CSS */
    :root {
        --primary-color: #0073aa;
        --secondary-color: #005177;
        --accent-color: #00a0d2;
        --dark-color: #111111;
        --light-color: #f8f9fa;
        --white-color: #ffffff;
    }
    
    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen-Sans, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
        color: var(--dark-color);
        margin: 0;
        padding: 0;
        font-size: 16px;
        line-height: 1.6;
    }
    
    .site-header {
        background-color: var(--white-color);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        position: relative;
        z-index: 100;
    }
    
    .site-branding {
        padding: 1rem;
    }
    
    .custom-logo {
        max-height: 60px;
        width: auto;
    }
    
    .main-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .main-navigation ul {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .main-navigation li {
        position: relative;
    }
    
    .main-navigation a {
        display: block;
        padding: 1rem;
        color: var(--dark-color);
        text-decoration: none;
    }
    
    .main-navigation a:hover {
        color: var(--primary-color);
    }
    
    .hero-section {
        background-color: var(--light-color);
        padding: 4rem 2rem;
        text-align: center;
    }
    
    .hero-title {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        font-family: 'Playfair Display', serif;
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        font-family: 'Montserrat', sans-serif;
    }
    
    .button {
        display: inline-block;
        background-color: var(--primary-color);
        color: var(--white-color);
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }
    
    .button:hover {
        background-color: var(--secondary-color);
        color: var(--white-color);
    }
    
    @media (max-width: 768px) {
        .main-navigation ul {
            display: none;
        }
    }";
    
    // Write critical CSS to file
    file_put_contents($critical_css_path, $critical_css);
}

/**
 * Add async/defer attributes to enqueued scripts.
 */
function aqualuxe_script_loader_tag($tag, $handle) {
    // Add async attribute to specific scripts
    $async_scripts = array(
        'aqualuxe-analytics',
        'aqualuxe-tracking',
    );
    
    if (in_array($handle, $async_scripts)) {
        return str_replace(' src', ' async src', $tag);
    }
    
    // Add defer attribute to specific scripts
    $defer_scripts = array(
        'aqualuxe-script',
        'aqualuxe-woocommerce',
    );
    
    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_script_loader_tag', 10, 2);

/**
 * Minify HTML output.
 */
function aqualuxe_minify_html($html) {
    // Don't minify in admin or if debugging
    if (is_admin() || (defined('WP_DEBUG') && WP_DEBUG)) {
        return $html;
    }
    
    // Remove comments (except IE conditional comments)
    $html = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $html);
    
    // Remove whitespace
    $html = preg_replace('/\s+/', ' ', $html);
    $html = preg_replace('/\s*(<\/?(div|p|h[1-6]|section|article|aside|nav|header|footer|main)[^>]*>)\s*/i', '$1', $html);
    $html = preg_replace('/\s*(<\/?(ul|ol|li|table|tr|td|th)[^>]*>)\s*/i', '$1', $html);
    
    // Remove whitespace around block elements
    $html = preg_replace('/>\s+</', '><', $html);
    
    return $html;
}
add_filter('final_output', 'aqualuxe_minify_html', 999);

/**
 * Enable output buffering for HTML minification.
 */
function aqualuxe_output_buffer_start() {
    // Don't buffer in admin
    if (is_admin()) {
        return;
    }
    
    ob_start('aqualuxe_output_buffer_callback');
}
add_action('template_redirect', 'aqualuxe_output_buffer_start', 0);

/**
 * Output buffer callback.
 */
function aqualuxe_output_buffer_callback($buffer) {
    // Apply filters to the final output
    $buffer = apply_filters('final_output', $buffer);
    return $buffer;
}

/**
 * End output buffering.
 */
function aqualuxe_output_buffer_end() {
    // Don't buffer in admin
    if (is_admin()) {
        return;
    }
    
    if (ob_get_length()) {
        ob_end_flush();
    }
}
add_action('shutdown', 'aqualuxe_output_buffer_end', 0);

/**
 * Add theme support for responsive embeds.
 */
function aqualuxe_responsive_embeds() {
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'aqualuxe_responsive_embeds');

/**
 * Make embeds responsive.
 */
function aqualuxe_responsive_embed($html) {
    return '<div class="responsive-embed">' . $html . '</div>';
}
add_filter('embed_oembed_html', 'aqualuxe_responsive_embed', 10, 1);

/**
 * Add theme support for post thumbnails.
 */
function aqualuxe_post_thumbnails() {
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'aqualuxe_post_thumbnails');

/**
 * Add custom image sizes.
 */
function aqualuxe_image_sizes() {
    // Add featured image size
    add_image_size('aqualuxe-featured', 1200, 600, true);
    
    // Add thumbnail size
    add_image_size('aqualuxe-thumbnail', 400, 300, true);
    
    // Add square size
    add_image_size('aqualuxe-square', 600, 600, true);
    
    // Add product gallery size
    add_image_size('aqualuxe-product-gallery', 800, 800, true);
}
add_action('after_setup_theme', 'aqualuxe_image_sizes');

/**
 * Add custom image sizes to media library.
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'aqualuxe-featured' => __('Featured Image', 'aqualuxe'),
        'aqualuxe-thumbnail' => __('Thumbnail', 'aqualuxe'),
        'aqualuxe-square' => __('Square', 'aqualuxe'),
        'aqualuxe-product-gallery' => __('Product Gallery', 'aqualuxe'),
    ));
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Optimize images.
 */
function aqualuxe_optimize_image($image_data) {
    // Don't optimize in admin
    if (is_admin()) {
        return $image_data;
    }
    
    // Check if we have the image editor
    $editor = wp_get_image_editor($image_data['file']);
    
    if (!is_wp_error($editor)) {
        // Set quality
        $editor->set_quality(85);
        
        // Save the image
        $editor->save($image_data['file']);
    }
    
    return $image_data;
}
add_filter('wp_generate_attachment_metadata', 'aqualuxe_optimize_image');

/**
 * Add WebP support.
 */
function aqualuxe_webp_upload_mimes($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'aqualuxe_webp_upload_mimes');

/**
 * Generate WebP versions of uploaded images.
 */
function aqualuxe_generate_webp($metadata, $attachment_id) {
    // Check if we have the image editor
    $file = get_attached_file($attachment_id);
    $editor = wp_get_image_editor($file);
    
    if (!is_wp_error($editor) && function_exists('imagewebp')) {
        // Get file info
        $info = pathinfo($file);
        $dir = $info['dirname'];
        $ext = $info['extension'];
        
        // Only convert jpg and png
        if (in_array(strtolower($ext), array('jpg', 'jpeg', 'png'))) {
            // Generate WebP version
            $webp_file = $dir . '/' . $info['filename'] . '.webp';
            $editor->save($webp_file, 'image/webp');
            
            // Generate WebP versions for all sizes
            if (isset($metadata['sizes']) && is_array($metadata['sizes'])) {
                foreach ($metadata['sizes'] as $size => $size_info) {
                    $size_file = $dir . '/' . $size_info['file'];
                    $size_editor = wp_get_image_editor($size_file);
                    
                    if (!is_wp_error($size_editor)) {
                        $size_webp_file = $dir . '/' . pathinfo($size_info['file'], PATHINFO_FILENAME) . '.webp';
                        $size_editor->save($size_webp_file, 'image/webp');
                    }
                }
            }
        }
    }
    
    return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'aqualuxe_generate_webp', 10, 2);

/**
 * Use WebP images in content if supported.
 */
function aqualuxe_use_webp_images($content) {
    // Don't modify in admin
    if (is_admin()) {
        return $content;
    }
    
    // Check if browser supports WebP
    $webp_support = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false;
    
    if ($webp_support) {
        // Replace image URLs with WebP versions
        $content = preg_replace_callback('/<img([^>]+)src=([\'"])([^\'"]+(\.jpg|\.jpeg|\.png))([\'"])([^>]*)>/i', function($matches) {
            $img_tag = $matches[0];
            $img_attr = $matches[1];
            $quote = $matches[2];
            $img_url = $matches[3];
            $img_ext = $matches[4];
            $end_quote = $matches[5];
            $remaining = $matches[6];
            
            // Generate WebP URL
            $webp_url = str_replace($img_ext, '.webp', $img_url);
            
            // Check if WebP file exists
            $upload_dir = wp_upload_dir();
            $webp_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $webp_url);
            
            if (file_exists($webp_path)) {
                // Add srcset with WebP
                if (strpos($img_tag, 'srcset=') === false) {
                    $img_tag = str_replace('<img', '<img srcset="' . $webp_url . ' 1x" ', $img_tag);
                } else {
                    // Replace srcset entries
                    $img_tag = preg_replace_callback('/srcset=([\'"])([^\'"]+)([\'"])/i', function($srcset_matches) {
                        $srcset_attr = $srcset_matches[0];
                        $srcset_quote = $srcset_matches[1];
                        $srcset_value = $srcset_matches[2];
                        $srcset_end_quote = $srcset_matches[3];
                        
                        // Replace each entry in srcset
                        $srcset_entries = explode(',', $srcset_value);
                        $new_entries = array();
                        
                        foreach ($srcset_entries as $entry) {
                            $parts = explode(' ', trim($entry));
                            $url = $parts[0];
                            $descriptor = isset($parts[1]) ? ' ' . $parts[1] : '';
                            
                            // Generate WebP URL
                            $webp_url = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $url);
                            
                            // Check if WebP file exists
                            $upload_dir = wp_upload_dir();
                            $webp_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $webp_url);
                            
                            if (file_exists($webp_path)) {
                                $new_entries[] = $webp_url . $descriptor;
                            } else {
                                $new_entries[] = $url . $descriptor;
                            }
                        }
                        
                        return 'srcset=' . $srcset_quote . implode(', ', $new_entries) . $srcset_end_quote;
                    }, $img_tag);
                }
            }
            
            return $img_tag;
        }, $content);
    }
    
    return $content;
}
add_filter('the_content', 'aqualuxe_use_webp_images', 100);
add_filter('post_thumbnail_html', 'aqualuxe_use_webp_images', 100);
add_filter('widget_text', 'aqualuxe_use_webp_images', 100);

/**
 * Add theme support for HTML5.
 */
function aqualuxe_html5_support() {
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
}
add_action('after_setup_theme', 'aqualuxe_html5_support');

/**
 * Add theme support for title tag.
 */
function aqualuxe_title_tag_support() {
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'aqualuxe_title_tag_support');

/**
 * Add theme support for custom logo.
 */
function aqualuxe_custom_logo_support() {
    add_theme_support('custom-logo', array(
        'height' => 100,
        'width' => 400,
        'flex-height' => true,
        'flex-width' => true,
    ));
}
add_action('after_setup_theme', 'aqualuxe_custom_logo_support');

/**
 * Add theme support for automatic feed links.
 */
function aqualuxe_automatic_feed_links() {
    add_theme_support('automatic-feed-links');
}
add_action('after_setup_theme', 'aqualuxe_automatic_feed_links');

/**
 * Add theme support for custom background.
 */
function aqualuxe_custom_background() {
    add_theme_support('custom-background', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ));
}
add_action('after_setup_theme', 'aqualuxe_custom_background');

/**
 * Add theme support for custom header.
 */
function aqualuxe_custom_header() {
    add_theme_support('custom-header', array(
        'default-image' => '',
        'width' => 1200,
        'height' => 400,
        'flex-height' => true,
        'flex-width' => true,
        'default-text-color' => '000000',
        'header-text' => true,
        'uploads' => true,
    ));
}
add_action('after_setup_theme', 'aqualuxe_custom_header');

/**
 * Add theme support for selective refresh widgets.
 */
function aqualuxe_selective_refresh_widgets() {
    add_theme_support('customize-selective-refresh-widgets');
}
add_action('after_setup_theme', 'aqualuxe_selective_refresh_widgets');

/**
 * Add theme support for wide and full alignments.
 */
function aqualuxe_align_wide() {
    add_theme_support('align-wide');
}
add_action('after_setup_theme', 'aqualuxe_align_wide');

/**
 * Add theme support for editor styles.
 */
function aqualuxe_editor_styles() {
    add_theme_support('editor-styles');
    add_editor_style('assets/dist/css/editor.css');
}
add_action('after_setup_theme', 'aqualuxe_editor_styles');

/**
 * Add theme support for block styles.
 */
function aqualuxe_block_styles() {
    add_theme_support('wp-block-styles');
}
add_action('after_setup_theme', 'aqualuxe_block_styles');

/**
 * Add theme support for responsive embeds.
 */
function aqualuxe_responsive_embeds_support() {
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'aqualuxe_responsive_embeds_support');

/**
 * Add theme support for custom line height.
 */
function aqualuxe_custom_line_height() {
    add_theme_support('custom-line-height');
}
add_action('after_setup_theme', 'aqualuxe_custom_line_height');

/**
 * Add theme support for custom spacing.
 */
function aqualuxe_custom_spacing() {
    add_theme_support('custom-spacing');
}
add_action('after_setup_theme', 'aqualuxe_custom_spacing');

/**
 * Add theme support for custom units.
 */
function aqualuxe_custom_units() {
    add_theme_support('custom-units');
}
add_action('after_setup_theme', 'aqualuxe_custom_units');

/**
 * Add theme support for editor color palette.
 */
function aqualuxe_editor_color_palette() {
    add_theme_support('editor-color-palette', array(
        array(
            'name' => __('Primary', 'aqualuxe'),
            'slug' => 'primary',
            'color' => '#0073aa',
        ),
        array(
            'name' => __('Secondary', 'aqualuxe'),
            'slug' => 'secondary',
            'color' => '#005177',
        ),
        array(
            'name' => __('Accent', 'aqualuxe'),
            'slug' => 'accent',
            'color' => '#00a0d2',
        ),
        array(
            'name' => __('Dark', 'aqualuxe'),
            'slug' => 'dark',
            'color' => '#111111',
        ),
        array(
            'name' => __('Light', 'aqualuxe'),
            'slug' => 'light',
            'color' => '#f8f9fa',
        ),
        array(
            'name' => __('White', 'aqualuxe'),
            'slug' => 'white',
            'color' => '#ffffff',
        ),
    ));
}
add_action('after_setup_theme', 'aqualuxe_editor_color_palette');

/**
 * Add theme support for editor font sizes.
 */
function aqualuxe_editor_font_sizes() {
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => __('Small', 'aqualuxe'),
            'slug' => 'small',
            'size' => 14,
        ),
        array(
            'name' => __('Normal', 'aqualuxe'),
            'slug' => 'normal',
            'size' => 16,
        ),
        array(
            'name' => __('Medium', 'aqualuxe'),
            'slug' => 'medium',
            'size' => 18,
        ),
        array(
            'name' => __('Large', 'aqualuxe'),
            'slug' => 'large',
            'size' => 24,
        ),
        array(
            'name' => __('Extra Large', 'aqualuxe'),
            'slug' => 'extra-large',
            'size' => 32,
        ),
    ));
}
add_action('after_setup_theme', 'aqualuxe_editor_font_sizes');

/**
 * Add theme support for custom gradients.
 */
function aqualuxe_custom_gradients() {
    add_theme_support('editor-gradient-presets', array(
        array(
            'name' => __('Primary to Secondary', 'aqualuxe'),
            'slug' => 'primary-to-secondary',
            'gradient' => 'linear-gradient(135deg, #0073aa 0%, #005177 100%)',
        ),
        array(
            'name' => __('Light to White', 'aqualuxe'),
            'slug' => 'light-to-white',
            'gradient' => 'linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%)',
        ),
        array(
            'name' => __('Dark to Black', 'aqualuxe'),
            'slug' => 'dark-to-black',
            'gradient' => 'linear-gradient(135deg, #111111 0%, #000000 100%)',
        ),
    ));
}
add_action('after_setup_theme', 'aqualuxe_custom_gradients');

/**
 * Add theme support for custom duotone.
 */
function aqualuxe_custom_duotone() {
    add_theme_support('editor-duotone-presets', array(
        array(
            'name' => __('Primary and White', 'aqualuxe'),
            'slug' => 'primary-white',
            'colors' => array('#0073aa', '#ffffff'),
        ),
        array(
            'name' => __('Secondary and White', 'aqualuxe'),
            'slug' => 'secondary-white',
            'colors' => array('#005177', '#ffffff'),
        ),
        array(
            'name' => __('Dark and Light', 'aqualuxe'),
            'slug' => 'dark-light',
            'colors' => array('#111111', '#f8f9fa'),
        ),
    ));
}
add_action('after_setup_theme', 'aqualuxe_custom_duotone');

/**
 * Add theme support for custom font sizes.
 */
function aqualuxe_custom_font_sizes() {
    add_theme_support('custom-font-sizes');
}
add_action('after_setup_theme', 'aqualuxe_custom_font_sizes');

/**
 * Add theme support for custom colors.
 */
function aqualuxe_custom_colors() {
    add_theme_support('custom-colors');
}
add_action('after_setup_theme', 'aqualuxe_custom_colors');

/**
 * Add theme support for custom gradients.
 */
function aqualuxe_custom_gradient_support() {
    add_theme_support('custom-gradients');
}
add_action('after_setup_theme', 'aqualuxe_custom_gradient_support');

/**
 * Add theme support for custom duotone.
 */
function aqualuxe_custom_duotone_support() {
    add_theme_support('custom-duotone');
}
add_action('after_setup_theme', 'aqualuxe_custom_duotone_support');

/**
 * Add theme support for custom link colors.
 */
function aqualuxe_custom_link_colors() {
    add_theme_support('link-color');
}
add_action('after_setup_theme', 'aqualuxe_custom_link_colors');

/**
 * Add theme support for custom padding.
 */
function aqualuxe_custom_padding() {
    add_theme_support('custom-padding');
}
add_action('after_setup_theme', 'aqualuxe_custom_padding');

/**
 * Add theme support for custom margin.
 */
function aqualuxe_custom_margin() {
    add_theme_support('custom-margin');
}
add_action('after_setup_theme', 'aqualuxe_custom_margin');

/**
 * Add theme support for custom typography.
 */
function aqualuxe_custom_typography() {
    add_theme_support('custom-typography');
}
add_action('after_setup_theme', 'aqualuxe_custom_typography');

/**
 * Add theme support for custom layout.
 */
function aqualuxe_custom_layout() {
    add_theme_support('custom-layout');
}
add_action('after_setup_theme', 'aqualuxe_custom_layout');

/**
 * Add theme support for custom spacing.
 */
function aqualuxe_custom_spacing_support() {
    add_theme_support('custom-spacing');
}
add_action('after_setup_theme', 'aqualuxe_custom_spacing_support');

/**
 * Add theme support for custom units.
 */
function aqualuxe_custom_units_support() {
    add_theme_support('custom-units', array('px', 'em', 'rem', '%', 'vh', 'vw'));
}
add_action('after_setup_theme', 'aqualuxe_custom_units_support');

/**
 * Add theme support for appearance tools.
 */
function aqualuxe_appearance_tools() {
    add_theme_support('appearance-tools');
}
add_action('after_setup_theme', 'aqualuxe_appearance_tools');

/**
 * Add theme support for block templates.
 */
function aqualuxe_block_templates() {
    add_theme_support('block-templates');
}
add_action('after_setup_theme', 'aqualuxe_block_templates');

/**
 * Add theme support for block template parts.
 */
function aqualuxe_block_template_parts() {
    add_theme_support('block-template-parts');
}
add_action('after_setup_theme', 'aqualuxe_block_template_parts');

/**
 * Add theme support for post formats.
 */
function aqualuxe_post_formats() {
    add_theme_support('post-formats', array(
        'aside',
        'gallery',
        'link',
        'image',
        'quote',
        'status',
        'video',
        'audio',
        'chat',
    ));
}
add_action('after_setup_theme', 'aqualuxe_post_formats');

/**
 * Add theme support for post thumbnails.
 */
function aqualuxe_post_thumbnails_support() {
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'aqualuxe_post_thumbnails_support');

/**
 * Add theme support for starter content.
 */
function aqualuxe_starter_content() {
    add_theme_support('starter-content', array(
        'widgets' => array(
            'sidebar-1' => array(
                'search',
                'recent-posts',
                'recent-comments',
                'archives',
                'categories',
                'meta',
            ),
            'footer-1' => array(
                'text_about',
            ),
            'footer-2' => array(
                'recent-posts',
            ),
            'footer-3' => array(
                'recent-comments',
            ),
            'footer-4' => array(
                'meta',
            ),
        ),
        'posts' => array(
            'home' => array(
                'post_type' => 'page',
                'post_title' => 'Home',
                'post_content' => '',
                'template' => 'templates/homepage.php',
            ),
            'about' => array(
                'post_type' => 'page',
                'post_title' => 'About',
                'post_content' => '',
            ),
            'contact' => array(
                'post_type' => 'page',
                'post_title' => 'Contact',
                'post_content' => '',
            ),
            'blog' => array(
                'post_type' => 'page',
                'post_title' => 'Blog',
                'post_content' => '',
            ),
        ),
        'options' => array(
            'show_on_front' => 'page',
            'page_on_front' => '{{home}}',
            'page_for_posts' => '{{blog}}',
        ),
        'theme_mods' => array(
            'custom_logo' => '{{custom_logo}}',
            'header_text' => false,
        ),
        'nav_menus' => array(
            'primary' => array(
                'name' => __('Primary Menu', 'aqualuxe'),
                'items' => array(
                    'link_home',
                    'page_about',
                    'page_blog',
                    'page_contact',
                ),
            ),
            'footer' => array(
                'name' => __('Footer Menu', 'aqualuxe'),
                'items' => array(
                    'link_home',
                    'page_about',
                    'page_blog',
                    'page_contact',
                ),
            ),
        ),
    ));
}
add_action('after_setup_theme', 'aqualuxe_starter_content');

/**
 * Add theme support for widgets.
 */
function aqualuxe_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar', 'aqualuxe'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer 1', 'aqualuxe'),
        'id' => 'footer-1',
        'description' => __('Add footer widgets here.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer 2', 'aqualuxe'),
        'id' => 'footer-2',
        'description' => __('Add footer widgets here.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer 3', 'aqualuxe'),
        'id' => 'footer-3',
        'description' => __('Add footer widgets here.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer 4', 'aqualuxe'),
        'id' => 'footer-4',
        'description' => __('Add footer widgets here.', 'aqualuxe'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    
    // Shop sidebar (only register if WooCommerce is active)
    if (class_exists('WooCommerce')) {
        register_sidebar(array(
            'name' => __('Shop Sidebar', 'aqualuxe'),
            'id' => 'shop-sidebar',
            'description' => __('Add shop widgets here.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        ));
    }
}
add_action('widgets_init', 'aqualuxe_widgets_init');

/**
 * Add theme support for menus.
 */
function aqualuxe_menus() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'aqualuxe'),
        'secondary' => __('Secondary Menu', 'aqualuxe'),
        'footer' => __('Footer Menu', 'aqualuxe'),
        'mobile' => __('Mobile Menu', 'aqualuxe'),
    ));
}
add_action('after_setup_theme', 'aqualuxe_menus');

/**
 * Add theme support for content width.
 */
function aqualuxe_content_width() {
    $GLOBALS['content_width'] = apply_filters('aqualuxe_content_width', 1200);
}
add_action('after_setup_theme', 'aqualuxe_content_width', 0);

/**
 * Add theme support for languages.
 */
function aqualuxe_languages() {
    load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'aqualuxe_languages');

/**
 * Add theme support for block styles.
 */
function aqualuxe_block_styles_support() {
    add_theme_support('wp-block-styles');
}
add_action('after_setup_theme', 'aqualuxe_block_styles_support');

/**
 * Add theme support for editor styles.
 */
function aqualuxe_editor_styles_support() {
    add_theme_support('editor-styles');
    add_editor_style('assets/dist/css/editor.css');
}
add_action('after_setup_theme', 'aqualuxe_editor_styles_support');

/**
 * Add theme support for responsive embeds.
 */
function aqualuxe_responsive_embeds_support_init() {
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'aqualuxe_responsive_embeds_support_init');

/**
 * Add theme support for automatic feed links.
 */
function aqualuxe_automatic_feed_links_support() {
    add_theme_support('automatic-feed-links');
}
add_action('after_setup_theme', 'aqualuxe_automatic_feed_links_support');

/**
 * Add theme support for title tag.
 */
function aqualuxe_title_tag_support_init() {
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'aqualuxe_title_tag_support_init');

/**
 * Add theme support for HTML5.
 */
function aqualuxe_html5_support_init() {
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
}
add_action('after_setup_theme', 'aqualuxe_html5_support_init');

/**
 * Add theme support for custom logo.
 */
function aqualuxe_custom_logo_support_init() {
    add_theme_support('custom-logo', array(
        'height' => 100,
        'width' => 400,
        'flex-height' => true,
        'flex-width' => true,
    ));
}
add_action('after_setup_theme', 'aqualuxe_custom_logo_support_init');