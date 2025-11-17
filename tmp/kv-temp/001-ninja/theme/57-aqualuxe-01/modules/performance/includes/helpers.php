<?php
/**
 * Performance Helper Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/Performance
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Check if lazy loading is enabled
 *
 * @return bool
 */
function aqualuxe_is_lazy_loading_enabled() {
    $performance_module = aqualuxe_get_module( 'performance' );
    
    if ( $performance_module && isset( $performance_module->settings['enable_lazy_loading'] ) ) {
        return $performance_module->settings['enable_lazy_loading'];
    }
    
    return false;
}

/**
 * Check if minification is enabled
 *
 * @return bool
 */
function aqualuxe_is_minification_enabled() {
    $performance_module = aqualuxe_get_module( 'performance' );
    
    if ( $performance_module && isset( $performance_module->settings['enable_minification'] ) ) {
        return $performance_module->settings['enable_minification'];
    }
    
    return false;
}

/**
 * Check if caching is enabled
 *
 * @return bool
 */
function aqualuxe_is_caching_enabled() {
    $performance_module = aqualuxe_get_module( 'performance' );
    
    if ( $performance_module && isset( $performance_module->settings['enable_caching'] ) ) {
        return $performance_module->settings['enable_caching'];
    }
    
    return false;
}

/**
 * Check if browser caching is enabled
 *
 * @return bool
 */
function aqualuxe_is_browser_caching_enabled() {
    $performance_module = aqualuxe_get_module( 'performance' );
    
    if ( $performance_module && isset( $performance_module->settings['enable_browser_caching'] ) ) {
        return $performance_module->settings['enable_browser_caching'];
    }
    
    return false;
}

/**
 * Check if critical CSS is enabled
 *
 * @return bool
 */
function aqualuxe_is_critical_css_enabled() {
    $performance_module = aqualuxe_get_module( 'performance' );
    
    if ( $performance_module && isset( $performance_module->settings['enable_critical_css'] ) ) {
        return $performance_module->settings['enable_critical_css'];
    }
    
    return false;
}

/**
 * Check if WebP support is enabled
 *
 * @return bool
 */
function aqualuxe_is_webp_support_enabled() {
    $performance_module = aqualuxe_get_module( 'performance' );
    
    if ( $performance_module && isset( $performance_module->settings['enable_webp_support'] ) ) {
        return $performance_module->settings['enable_webp_support'];
    }
    
    return false;
}

/**
 * Check if preloading is enabled
 *
 * @return bool
 */
function aqualuxe_is_preloading_enabled() {
    $performance_module = aqualuxe_get_module( 'performance' );
    
    if ( $performance_module && isset( $performance_module->settings['enable_preloading'] ) ) {
        return $performance_module->settings['enable_preloading'];
    }
    
    return false;
}

/**
 * Check if JavaScript deferring is enabled
 *
 * @return bool
 */
function aqualuxe_is_js_defer_enabled() {
    $performance_module = aqualuxe_get_module( 'performance' );
    
    if ( $performance_module && isset( $performance_module->settings['defer_js'] ) ) {
        return $performance_module->settings['defer_js'];
    }
    
    return false;
}

/**
 * Check if CSS async loading is enabled
 *
 * @return bool
 */
function aqualuxe_is_css_async_enabled() {
    $performance_module = aqualuxe_get_module( 'performance' );
    
    if ( $performance_module && isset( $performance_module->settings['async_css'] ) ) {
        return $performance_module->settings['async_css'];
    }
    
    return false;
}

/**
 * Add lazy loading attributes to HTML
 *
 * @param string $html HTML to process.
 * @return string
 */
function aqualuxe_add_lazy_loading( $html ) {
    if ( ! aqualuxe_is_lazy_loading_enabled() ) {
        return $html;
    }
    
    $performance_module = aqualuxe_get_module( 'performance' );
    
    if ( $performance_module ) {
        return $performance_module->add_lazy_loading_to_content( $html );
    }
    
    return $html;
}

/**
 * Get critical CSS
 *
 * @return string
 */
function aqualuxe_get_critical_css() {
    if ( ! aqualuxe_is_critical_css_enabled() ) {
        return '';
    }
    
    $critical_css_file = AQUALUXE_THEME_DIR . 'modules/performance/assets/css/critical.css';
    
    if ( file_exists( $critical_css_file ) ) {
        return file_get_contents( $critical_css_file );
    }
    
    return '';
}

/**
 * Convert image URL to WebP if available
 *
 * @param string $url Image URL.
 * @return string
 */
function aqualuxe_convert_to_webp( $url ) {
    if ( ! aqualuxe_is_webp_support_enabled() ) {
        return $url;
    }
    
    // Check if browser supports WebP
    if ( ! isset( $_SERVER['HTTP_ACCEPT'] ) || strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) === false ) {
        return $url;
    }
    
    // Skip if already WebP
    if ( strpos( $url, '.webp' ) !== false ) {
        return $url;
    }
    
    // Convert URL to WebP
    $webp_url = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $url );
    $webp_path = str_replace( site_url(), ABSPATH, $webp_url );
    
    if ( file_exists( $webp_path ) ) {
        return $webp_url;
    }
    
    return $url;
}

/**
 * Get browser caching rules
 *
 * @return string
 */
function aqualuxe_get_browser_caching_rules() {
    return "
# AquaLuxe Browser Caching Rules
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg &quot;access plus 1 year&quot;
    ExpiresByType image/jpeg &quot;access plus 1 year&quot;
    ExpiresByType image/gif &quot;access plus 1 year&quot;
    ExpiresByType image/png &quot;access plus 1 year&quot;
    ExpiresByType image/webp &quot;access plus 1 year&quot;
    ExpiresByType image/svg+xml &quot;access plus 1 year&quot;
    ExpiresByType image/x-icon &quot;access plus 1 year&quot;
    ExpiresByType video/mp4 &quot;access plus 1 year&quot;
    ExpiresByType video/webm &quot;access plus 1 year&quot;
    ExpiresByType text/css &quot;access plus 1 month&quot;
    ExpiresByType text/javascript &quot;access plus 1 month&quot;
    ExpiresByType application/javascript &quot;access plus 1 month&quot;
    ExpiresByType application/pdf &quot;access plus 1 month&quot;
    ExpiresByType application/x-font-woff &quot;access plus 1 year&quot;
    ExpiresByType application/font-woff2 &quot;access plus 1 year&quot;
    ExpiresByType font/woff &quot;access plus 1 year&quot;
    ExpiresByType font/woff2 &quot;access plus 1 year&quot;
</IfModule>

<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE text/javascript
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
    AddOutputFilterByType DEFLATE application/x-font-ttf
    AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
    AddOutputFilterByType DEFLATE font/opentype font/ttf font/eot font/otf
</IfModule>
# End AquaLuxe Browser Caching Rules
";
}

/**
 * Get preload resources
 *
 * @return array
 */
function aqualuxe_get_preload_resources() {
    $performance_module = aqualuxe_get_module( 'performance' );
    
    if ( $performance_module && isset( $performance_module->settings['preload_resources'] ) ) {
        return $performance_module->settings['preload_resources'];
    }
    
    return array();
}

/**
 * Add preload resource
 *
 * @param string $url Resource URL.
 * @param string $type Resource type (font, style, script, image).
 * @return bool
 */
function aqualuxe_add_preload_resource( $url, $type ) {
    $performance_module = aqualuxe_get_module( 'performance' );
    
    if ( ! $performance_module ) {
        return false;
    }
    
    $preload_resources = $performance_module->settings['preload_resources'];
    
    // Check if resource already exists
    foreach ( $preload_resources as $resource ) {
        if ( $resource['url'] === $url ) {
            return false;
        }
    }
    
    // Add new resource
    $preload_resources[] = array(
        'url' => $url,
        'type' => $type,
    );
    
    // Update settings
    $performance_module->settings['preload_resources'] = $preload_resources;
    $performance_module->update_settings( $performance_module->settings );
    
    return true;
}

/**
 * Remove preload resource
 *
 * @param string $url Resource URL.
 * @return bool
 */
function aqualuxe_remove_preload_resource( $url ) {
    $performance_module = aqualuxe_get_module( 'performance' );
    
    if ( ! $performance_module ) {
        return false;
    }
    
    $preload_resources = $performance_module->settings['preload_resources'];
    
    // Find and remove resource
    foreach ( $preload_resources as $key => $resource ) {
        if ( $resource['url'] === $url ) {
            unset( $preload_resources[ $key ] );
            
            // Reindex array
            $preload_resources = array_values( $preload_resources );
            
            // Update settings
            $performance_module->settings['preload_resources'] = $preload_resources;
            $performance_module->update_settings( $performance_module->settings );
            
            return true;
        }
    }
    
    return false;
}

/**
 * Get page load time
 *
 * @return float
 */
function aqualuxe_get_page_load_time() {
    global $timestart;
    
    return microtime( true ) - $timestart;
}

/**
 * Add page load time to footer
 *
 * @return void
 */
function aqualuxe_add_page_load_time() {
    if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
        $load_time = aqualuxe_get_page_load_time();
        echo '<!-- Page generated in ' . number_format( $load_time, 3 ) . ' seconds -->';
    }
}
add_action( 'wp_footer', 'aqualuxe_add_page_load_time', 999 );

/**
 * Check if browser supports WebP
 *
 * @return bool
 */
function aqualuxe_browser_supports_webp() {
    if ( isset( $_SERVER['HTTP_ACCEPT'] ) && strpos( $_SERVER['HTTP_ACCEPT'], 'image/webp' ) !== false ) {
        return true;
    }
    
    return false;
}

/**
 * Generate WebP version of an image
 *
 * @param string $file Image file path.
 * @return bool
 */
function aqualuxe_generate_webp( $file ) {
    // Skip if WebP support is not enabled
    if ( ! aqualuxe_is_webp_support_enabled() ) {
        return false;
    }
    
    // Skip if file doesn't exist
    if ( ! file_exists( $file ) ) {
        return false;
    }
    
    // Skip if not an image
    $mime_type = wp_check_filetype( $file );
    if ( ! in_array( $mime_type['ext'], array( 'jpg', 'jpeg', 'png' ), true ) ) {
        return false;
    }
    
    // Generate WebP filename
    $webp_file = preg_replace( '/\.(jpg|jpeg|png)$/i', '.webp', $file );
    
    // Skip if WebP already exists and is newer than the original
    if ( file_exists( $webp_file ) && filemtime( $webp_file ) >= filemtime( $file ) ) {
        return true;
    }
    
    // Check if GD is available with WebP support
    if ( function_exists( 'imagewebp' ) ) {
        $image = false;
        
        // Create image resource based on file type
        switch ( $mime_type['ext'] ) {
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg( $file );
                break;
            case 'png':
                $image = imagecreatefrompng( $file );
                
                // Handle transparency
                imagepalettetotruecolor( $image );
                imagealphablending( $image, true );
                imagesavealpha( $image, true );
                break;
        }
        
        // Generate WebP
        if ( $image ) {
            $result = imagewebp( $image, $webp_file, 80 );
            imagedestroy( $image );
            
            return $result;
        }
    }
    
    return false;
}

/**
 * Generate WebP versions for all images in the media library
 *
 * @return int Number of WebP images generated
 */
function aqualuxe_generate_all_webp_images() {
    // Skip if WebP support is not enabled
    if ( ! aqualuxe_is_webp_support_enabled() ) {
        return 0;
    }
    
    $count = 0;
    
    // Get all image attachments
    $args = array(
        'post_type' => 'attachment',
        'post_mime_type' => array( 'image/jpeg', 'image/png' ),
        'post_status' => 'inherit',
        'posts_per_page' => -1,
    );
    
    $query = new WP_Query( $args );
    
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            
            $file = get_attached_file( get_the_ID() );
            
            if ( aqualuxe_generate_webp( $file ) ) {
                $count++;
            }
        }
        
        wp_reset_postdata();
    }
    
    return $count;
}

/**
 * Generate WebP version when an image is uploaded
 *
 * @param array $metadata Attachment metadata.
 * @param int   $attachment_id Attachment ID.
 * @return array
 */
function aqualuxe_generate_webp_on_upload( $metadata, $attachment_id ) {
    // Skip if WebP support is not enabled
    if ( ! aqualuxe_is_webp_support_enabled() ) {
        return $metadata;
    }
    
    // Skip if not an image
    if ( ! isset( $metadata['file'] ) || ! in_array( $metadata['mime_type'], array( 'image/jpeg', 'image/png' ), true ) ) {
        return $metadata;
    }
    
    // Generate WebP for main file
    $file = get_attached_file( $attachment_id );
    aqualuxe_generate_webp( $file );
    
    // Generate WebP for each size
    if ( isset( $metadata['sizes'] ) && is_array( $metadata['sizes'] ) ) {
        $upload_dir = wp_upload_dir();
        $base_dir = trailingslashit( $upload_dir['basedir'] );
        $path_parts = pathinfo( $metadata['file'] );
        $base_path = trailingslashit( $base_dir . $path_parts['dirname'] );
        
        foreach ( $metadata['sizes'] as $size => $size_data ) {
            $size_file = $base_path . $size_data['file'];
            aqualuxe_generate_webp( $size_file );
        }
    }
    
    return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'aqualuxe_generate_webp_on_upload', 10, 2 );