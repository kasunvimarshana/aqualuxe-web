<?php
/**
 * Caching Implementation
 *
 * @package AquaLuxe
 * @subpackage Modules/Performance
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Add cache control headers
 *
 * @return void
 */
function aqualuxe_performance_add_cache_control_headers() {
    if ( ! aqualuxe_is_caching_enabled() || is_admin() || is_user_logged_in() ) {
        return;
    }

    // Set cache control headers
    header( 'Cache-Control: public, max-age=86400, s-maxage=86400' );
    header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 86400 ) . ' GMT' );
}
add_action( 'send_headers', 'aqualuxe_performance_add_cache_control_headers' );

/**
 * Add browser caching rules
 *
 * @return void
 */
function aqualuxe_performance_add_browser_caching_rules() {
    if ( ! aqualuxe_is_browser_caching_enabled() ) {
        return;
    }

    // Check if .htaccess exists and is writable
    $htaccess_file = ABSPATH . '.htaccess';
    if ( file_exists( $htaccess_file ) && is_writable( $htaccess_file ) ) {
        $htaccess_content = file_get_contents( $htaccess_file );

        // Check if browser caching rules already exist
        if ( strpos( $htaccess_content, '# AquaLuxe Browser Caching Rules' ) === false ) {
            $browser_caching_rules = aqualuxe_get_browser_caching_rules();
            
            // Add browser caching rules to .htaccess
            $htaccess_content = $browser_caching_rules . $htaccess_content;
            file_put_contents( $htaccess_file, $htaccess_content );
        }
    }
}
add_action( 'admin_init', 'aqualuxe_performance_add_browser_caching_rules' );

/**
 * Remove browser caching rules
 *
 * @return void
 */
function aqualuxe_performance_remove_browser_caching_rules() {
    // Check if .htaccess exists and is writable
    $htaccess_file = ABSPATH . '.htaccess';
    if ( file_exists( $htaccess_file ) && is_writable( $htaccess_file ) ) {
        $htaccess_content = file_get_contents( $htaccess_file );

        // Check if browser caching rules exist
        if ( strpos( $htaccess_content, '# AquaLuxe Browser Caching Rules' ) !== false ) {
            // Remove browser caching rules
            $htaccess_content = preg_replace(
                '/# AquaLuxe Browser Caching Rules.*?# End AquaLuxe Browser Caching Rules\n?/s',
                '',
                $htaccess_content
            );
            
            file_put_contents( $htaccess_file, $htaccess_content );
        }
    }
}

/**
 * Update browser caching rules when settings change
 *
 * @param string $option Option name.
 * @param mixed  $old_value Old value.
 * @param mixed  $value New value.
 * @return void
 */
function aqualuxe_performance_update_browser_caching_rules( $option, $old_value, $value ) {
    if ( $option === 'aqualuxe_performance_enable_browser_caching' ) {
        if ( $value ) {
            aqualuxe_performance_add_browser_caching_rules();
        } else {
            aqualuxe_performance_remove_browser_caching_rules();
        }
    }
}
add_action( 'update_option', 'aqualuxe_performance_update_browser_caching_rules', 10, 3 );

/**
 * Add GZIP compression rules
 *
 * @return void
 */
function aqualuxe_performance_add_gzip_rules() {
    $performance_module = aqualuxe_get_module( 'performance' );
    
    if ( ! $performance_module || ! isset( $performance_module->settings['enable_gzip'] ) || ! $performance_module->settings['enable_gzip'] ) {
        return;
    }

    // Check if .htaccess exists and is writable
    $htaccess_file = ABSPATH . '.htaccess';
    if ( file_exists( $htaccess_file ) && is_writable( $htaccess_file ) ) {
        $htaccess_content = file_get_contents( $htaccess_file );

        // Check if GZIP rules already exist
        if ( strpos( $htaccess_content, '# AquaLuxe GZIP Compression' ) === false ) {
            $gzip_rules = "
# AquaLuxe GZIP Compression
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
# End AquaLuxe GZIP Compression
";
            
            // Add GZIP rules to .htaccess
            $htaccess_content = $gzip_rules . $htaccess_content;
            file_put_contents( $htaccess_file, $htaccess_content );
        }
    }
}
add_action( 'admin_init', 'aqualuxe_performance_add_gzip_rules' );

/**
 * Remove GZIP compression rules
 *
 * @return void
 */
function aqualuxe_performance_remove_gzip_rules() {
    // Check if .htaccess exists and is writable
    $htaccess_file = ABSPATH . '.htaccess';
    if ( file_exists( $htaccess_file ) && is_writable( $htaccess_file ) ) {
        $htaccess_content = file_get_contents( $htaccess_file );

        // Check if GZIP rules exist
        if ( strpos( $htaccess_content, '# AquaLuxe GZIP Compression' ) !== false ) {
            // Remove GZIP rules
            $htaccess_content = preg_replace(
                '/# AquaLuxe GZIP Compression.*?# End AquaLuxe GZIP Compression\n?/s',
                '',
                $htaccess_content
            );
            
            file_put_contents( $htaccess_file, $htaccess_content );
        }
    }
}

/**
 * Update GZIP rules when settings change
 *
 * @param string $option Option name.
 * @param mixed  $old_value Old value.
 * @param mixed  $value New value.
 * @return void
 */
function aqualuxe_performance_update_gzip_rules( $option, $old_value, $value ) {
    if ( $option === 'aqualuxe_performance_enable_gzip' ) {
        if ( $value ) {
            aqualuxe_performance_add_gzip_rules();
        } else {
            aqualuxe_performance_remove_gzip_rules();
        }
    }
}
add_action( 'update_option', 'aqualuxe_performance_update_gzip_rules', 10, 3 );

/**
 * Add DNS prefetch
 *
 * @return void
 */
function aqualuxe_performance_add_dns_prefetch() {
    if ( is_admin() ) {
        return;
    }

    $domains = array(
        'fonts.googleapis.com',
        'fonts.gstatic.com',
        'ajax.googleapis.com',
        'apis.google.com',
        'google-analytics.com',
        'www.google-analytics.com',
        'ssl.google-analytics.com',
        'youtube.com',
        'i.ytimg.com',
        'gravatar.com',
        'secure.gravatar.com',
    );

    foreach ( $domains as $domain ) {
        echo '<link rel="dns-prefetch" href="//' . esc_attr( $domain ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_performance_add_dns_prefetch', 1 );

/**
 * Add resource hints
 *
 * @param array  $hints Resource hints.
 * @param string $relation_type Relation type.
 * @return array
 */
function aqualuxe_performance_add_resource_hints( $hints, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $domains = array(
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com',
        );

        foreach ( $domains as $domain ) {
            $hints[] = array(
                'href' => $domain,
                'crossorigin' => 'anonymous',
            );
        }
    }

    return $hints;
}
add_filter( 'wp_resource_hints', 'aqualuxe_performance_add_resource_hints', 10, 2 );

/**
 * Add preload tags
 *
 * @return void
 */
function aqualuxe_performance_add_preload_tags() {
    if ( ! aqualuxe_is_preloading_enabled() || is_admin() ) {
        return;
    }

    // Preload fonts
    $fonts = array(
        'fonts/roboto-v20-latin-regular.woff2',
        'fonts/roboto-v20-latin-700.woff2',
    );

    foreach ( $fonts as $font ) {
        $font_url = AQUALUXE_ASSETS_URI . 'fonts/' . $font;
        echo '<link rel="preload" href="' . esc_url( $font_url ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
    }

    // Preload critical assets
    $preload_resources = aqualuxe_get_preload_resources();
    if ( ! empty( $preload_resources ) && is_array( $preload_resources ) ) {
        foreach ( $preload_resources as $resource ) {
            if ( isset( $resource['url'] ) && isset( $resource['type'] ) ) {
                echo '<link rel="preload" href="' . esc_url( $resource['url'] ) . '" as="' . esc_attr( $resource['type'] ) . '"';
                if ( $resource['type'] === 'font' ) {
                    echo ' crossorigin';
                }
                echo '>' . "\n";
            }
        }
    }
}
add_action( 'wp_head', 'aqualuxe_performance_add_preload_tags', 1 );

/**
 * Implement page caching
 *
 * @return void
 */
function aqualuxe_performance_page_caching() {
    if ( ! aqualuxe_is_caching_enabled() || is_admin() || is_user_logged_in() ) {
        return;
    }

    // Skip caching for specific pages
    if ( is_search() || is_404() || is_feed() || is_trackback() || is_robots() || is_preview() || post_password_required() ) {
        return;
    }

    // Skip if request method is not GET
    if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || $_SERVER['REQUEST_METHOD'] !== 'GET' ) {
        return;
    }

    // Skip if query string exists
    if ( ! empty( $_GET ) ) {
        return;
    }

    // Get cache directory
    $cache_dir = AQUALUXE_THEME_DIR . 'cache/pages/';
    
    // Create cache directory if it doesn't exist
    if ( ! file_exists( $cache_dir ) ) {
        wp_mkdir_p( $cache_dir );
    }

    // Generate cache key
    $cache_key = md5( $_SERVER['REQUEST_URI'] );
    $cache_file = $cache_dir . $cache_key . '.html';

    // Check if cache file exists and is not expired
    if ( file_exists( $cache_file ) && ( time() - filemtime( $cache_file ) < 86400 ) ) {
        // Serve cached content
        readfile( $cache_file );
        exit;
    }

    // Start output buffering
    ob_start( function( $buffer ) use ( $cache_file ) {
        // Save cache file
        file_put_contents( $cache_file, $buffer );
        
        return $buffer;
    });
}
// Uncomment the following line to enable page caching (not recommended for development)
// add_action( 'template_redirect', 'aqualuxe_performance_page_caching', 1 );

/**
 * Clear page cache
 *
 * @return void
 */
function aqualuxe_performance_clear_page_cache() {
    $cache_dir = AQUALUXE_THEME_DIR . 'cache/pages/';
    
    if ( file_exists( $cache_dir ) ) {
        $files = glob( $cache_dir . '*.html' );
        
        foreach ( $files as $file ) {
            if ( is_file( $file ) ) {
                unlink( $file );
            }
        }
    }
}

/**
 * Clear page cache when content is updated
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post Post object.
 * @return void
 */
function aqualuxe_performance_clear_cache_on_update( $post_id, $post ) {
    // Skip if doing autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Skip if post is a revision
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }

    // Skip if post is not published
    if ( $post->post_status !== 'publish' ) {
        return;
    }

    // Clear page cache
    aqualuxe_performance_clear_page_cache();
}
add_action( 'save_post', 'aqualuxe_performance_clear_cache_on_update', 10, 2 );

/**
 * Clear page cache when a comment is posted
 *
 * @param int $comment_id Comment ID.
 * @return void
 */
function aqualuxe_performance_clear_cache_on_comment( $comment_id ) {
    $comment = get_comment( $comment_id );
    
    if ( $comment && $comment->comment_approved ) {
        aqualuxe_performance_clear_page_cache();
    }
}
add_action( 'wp_insert_comment', 'aqualuxe_performance_clear_cache_on_comment' );

/**
 * Clear page cache when a comment is approved
 *
 * @param string $new_status New status.
 * @param string $old_status Old status.
 * @param object $comment Comment object.
 * @return void
 */
function aqualuxe_performance_clear_cache_on_comment_status( $new_status, $old_status, $comment ) {
    if ( $new_status === 'approved' ) {
        aqualuxe_performance_clear_page_cache();
    }
}
add_action( 'transition_comment_status', 'aqualuxe_performance_clear_cache_on_comment_status', 10, 3 );

/**
 * Clear page cache when theme options are updated
 *
 * @param string $option Option name.
 * @return void
 */
function aqualuxe_performance_clear_cache_on_option_update( $option ) {
    // Clear cache when theme options are updated
    if ( strpos( $option, 'aqualuxe_' ) === 0 || strpos( $option, 'theme_mods_aqualuxe' ) === 0 ) {
        aqualuxe_performance_clear_page_cache();
    }
}
add_action( 'updated_option', 'aqualuxe_performance_clear_cache_on_option_update' );

/**
 * Add cache control to REST API
 *
 * @param WP_REST_Response $response Response object.
 * @return WP_REST_Response
 */
function aqualuxe_performance_rest_api_cache_control( $response ) {
    if ( ! aqualuxe_is_caching_enabled() || is_user_logged_in() ) {
        return $response;
    }

    // Add cache control headers
    $response->header( 'Cache-Control', 'public, max-age=60, s-maxage=60' );
    $response->header( 'Expires', gmdate( 'D, d M Y H:i:s', time() + 60 ) . ' GMT' );

    return $response;
}
add_filter( 'rest_post_dispatch', 'aqualuxe_performance_rest_api_cache_control' );

/**
 * Add cache control to feeds
 *
 * @return void
 */
function aqualuxe_performance_feed_cache_control() {
    if ( ! aqualuxe_is_caching_enabled() || is_user_logged_in() ) {
        return;
    }

    // Add cache control headers
    header( 'Cache-Control: public, max-age=1800, s-maxage=1800' );
    header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 1800 ) . ' GMT' );
}
add_action( 'rss_tag_pre', 'aqualuxe_performance_feed_cache_control' );
add_action( 'rss2_tag_pre', 'aqualuxe_performance_feed_cache_control' );
add_action( 'atom_tag_pre', 'aqualuxe_performance_feed_cache_control' );