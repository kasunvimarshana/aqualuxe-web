<?php
/**
 * AquaLuxe Performance Caching Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/Performance
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Cache page
 *
 * @return void
 */
function aqualuxe_performance_cache_page() {
    // Check if caching is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_caching', true ) ) {
        return;
    }
    
    // Get current URL
    $url = aqualuxe_performance_get_current_url();
    
    // Check if URL should be cached
    if ( ! aqualuxe_performance_should_cache_url( $url ) ) {
        return;
    }
    
    // Get cache file path
    $cache_file = aqualuxe_performance_get_cache_file_path( $url );
    
    // Check if cache file exists and is not expired
    if ( file_exists( $cache_file ) ) {
        // Get cache expiration
        $expiration = aqualuxe_performance_get_option( 'cache_expiration', 86400 );
        
        // Check if cache is expired
        if ( time() - filemtime( $cache_file ) < $expiration ) {
            // Serve cached page
            aqualuxe_performance_serve_cached_page( $cache_file );
        }
    }
    
    // Start output buffering
    ob_start( 'aqualuxe_performance_cache_page_callback' );
}

/**
 * Cache page callback
 *
 * @param string $buffer Buffer
 * @return string
 */
function aqualuxe_performance_cache_page_callback( $buffer ) {
    // Check if buffer is empty
    if ( empty( $buffer ) ) {
        return $buffer;
    }
    
    // Get current URL
    $url = aqualuxe_performance_get_current_url();
    
    // Check if URL should be cached
    if ( ! aqualuxe_performance_should_cache_url( $url ) ) {
        return $buffer;
    }
    
    // Get cache file path
    $cache_file = aqualuxe_performance_get_cache_file_path( $url );
    
    // Add cache comment
    $buffer .= "\n<!-- Page cached by AquaLuxe Performance on " . date( 'Y-m-d H:i:s' ) . " -->";
    
    // Save cache
    file_put_contents( $cache_file, $buffer );
    
    return $buffer;
}

/**
 * Serve cached page
 *
 * @param string $cache_file Cache file path
 * @return void
 */
function aqualuxe_performance_serve_cached_page( $cache_file ) {
    // Check if file exists
    if ( ! file_exists( $cache_file ) ) {
        return;
    }
    
    // Get file contents
    $contents = file_get_contents( $cache_file );
    
    // Check if contents are valid
    if ( ! $contents ) {
        return;
    }
    
    // Set headers
    header( 'Content-Type: text/html; charset=UTF-8' );
    header( 'X-AquaLuxe-Cached: true' );
    
    // Output contents
    echo $contents;
    
    // Exit
    exit;
}

/**
 * Get current URL
 *
 * @return string
 */
function aqualuxe_performance_get_current_url() {
    // Get protocol
    $protocol = ( isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on' ) ? 'https' : 'http';
    
    // Get host
    $host = $_SERVER['HTTP_HOST'];
    
    // Get request URI
    $uri = $_SERVER['REQUEST_URI'];
    
    // Build URL
    $url = $protocol . '://' . $host . $uri;
    
    return $url;
}

/**
 * Enable browser caching
 *
 * @return void
 */
function aqualuxe_performance_browser_caching() {
    // Check if browser caching is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_browser_caching', true ) ) {
        return;
    }
    
    // Get file type
    $file_type = aqualuxe_performance_get_file_type_from_request();
    
    // Set cache control header based on file type
    switch ( $file_type ) {
        case 'css':
        case 'js':
        case 'image':
        case 'font':
            // Cache for 1 week
            header( 'Cache-Control: public, max-age=604800' );
            break;
        case 'video':
        case 'audio':
            // Cache for 1 day
            header( 'Cache-Control: public, max-age=86400' );
            break;
        case 'document':
            // Cache for 1 hour
            header( 'Cache-Control: public, max-age=3600' );
            break;
        default:
            // No cache for HTML
            header( 'Cache-Control: no-cache, no-store, must-revalidate' );
            header( 'Pragma: no-cache' );
            header( 'Expires: 0' );
            break;
    }
}

/**
 * Get file type from request
 *
 * @return string
 */
function aqualuxe_performance_get_file_type_from_request() {
    // Get request URI
    $uri = $_SERVER['REQUEST_URI'];
    
    // Get file extension
    $extension = pathinfo( $uri, PATHINFO_EXTENSION );
    
    // Check file type
    if ( in_array( $extension, array( 'css' ), true ) ) {
        return 'css';
    } elseif ( in_array( $extension, array( 'js' ), true ) ) {
        return 'js';
    } elseif ( in_array( $extension, array( 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg' ), true ) ) {
        return 'image';
    } elseif ( in_array( $extension, array( 'woff', 'woff2', 'ttf', 'otf', 'eot' ), true ) ) {
        return 'font';
    } elseif ( in_array( $extension, array( 'mp4', 'webm', 'ogg' ), true ) ) {
        return 'video';
    } elseif ( in_array( $extension, array( 'mp3', 'wav', 'ogg' ), true ) ) {
        return 'audio';
    } elseif ( in_array( $extension, array( 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx' ), true ) ) {
        return 'document';
    } else {
        return 'html';
    }
}

/**
 * Enable GZIP compression
 *
 * @return void
 */
function aqualuxe_performance_enable_gzip() {
    // Check if GZIP compression is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_gzip', true ) ) {
        return;
    }
    
    // Check if GZIP is already enabled
    if ( ini_get( 'zlib.output_compression' ) ) {
        return;
    }
    
    // Enable GZIP compression
    ini_set( 'zlib.output_compression', 1 );
}

/**
 * Add browser caching to .htaccess
 *
 * @return bool|WP_Error
 */
function aqualuxe_performance_add_browser_caching_to_htaccess() {
    // Check if browser caching is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_browser_caching', true ) ) {
        return false;
    }
    
    // Get .htaccess file path
    $htaccess_file = ABSPATH . '.htaccess';
    
    // Check if file exists
    if ( ! file_exists( $htaccess_file ) ) {
        return new WP_Error( 'htaccess_not_found', __( '.htaccess file not found.', 'aqualuxe' ) );
    }
    
    // Check if file is writable
    if ( ! is_writable( $htaccess_file ) ) {
        return new WP_Error( 'htaccess_not_writable', __( '.htaccess file is not writable.', 'aqualuxe' ) );
    }
    
    // Get file contents
    $contents = file_get_contents( $htaccess_file );
    
    // Check if browser caching is already added
    if ( strpos( $contents, '# BEGIN AquaLuxe Browser Caching' ) !== false ) {
        return true;
    }
    
    // Browser caching rules
    $rules = "\n# BEGIN AquaLuxe Browser Caching\n";
    $rules .= "<IfModule mod_expires.c>\n";
    $rules .= "ExpiresActive On\n";
    $rules .= "ExpiresByType text/css &quot;access plus 1 week&quot;\n";
    $rules .= "ExpiresByType text/javascript &quot;access plus 1 week&quot;\n";
    $rules .= "ExpiresByType application/javascript &quot;access plus 1 week&quot;\n";
    $rules .= "ExpiresByType image/jpg &quot;access plus 1 week&quot;\n";
    $rules .= "ExpiresByType image/jpeg &quot;access plus 1 week&quot;\n";
    $rules .= "ExpiresByType image/png &quot;access plus 1 week&quot;\n";
    $rules .= "ExpiresByType image/gif &quot;access plus 1 week&quot;\n";
    $rules .= "ExpiresByType image/webp &quot;access plus 1 week&quot;\n";
    $rules .= "ExpiresByType image/svg+xml &quot;access plus 1 week&quot;\n";
    $rules .= "ExpiresByType font/woff &quot;access plus 1 week&quot;\n";
    $rules .= "ExpiresByType font/woff2 &quot;access plus 1 week&quot;\n";
    $rules .= "ExpiresByType font/ttf &quot;access plus 1 week&quot;\n";
    $rules .= "ExpiresByType font/otf &quot;access plus 1 week&quot;\n";
    $rules .= "ExpiresByType application/vnd.ms-fontobject &quot;access plus 1 week&quot;\n";
    $rules .= "ExpiresByType video/mp4 &quot;access plus 1 day&quot;\n";
    $rules .= "ExpiresByType video/webm &quot;access plus 1 day&quot;\n";
    $rules .= "ExpiresByType video/ogg &quot;access plus 1 day&quot;\n";
    $rules .= "ExpiresByType audio/mpeg &quot;access plus 1 day&quot;\n";
    $rules .= "ExpiresByType audio/wav &quot;access plus 1 day&quot;\n";
    $rules .= "ExpiresByType audio/ogg &quot;access plus 1 day&quot;\n";
    $rules .= "ExpiresByType application/pdf &quot;access plus 1 hour&quot;\n";
    $rules .= "ExpiresByType application/msword &quot;access plus 1 hour&quot;\n";
    $rules .= "ExpiresByType application/vnd.openxmlformats-officedocument.wordprocessingml.document &quot;access plus 1 hour&quot;\n";
    $rules .= "ExpiresByType application/vnd.ms-excel &quot;access plus 1 hour&quot;\n";
    $rules .= "ExpiresByType application/vnd.openxmlformats-officedocument.spreadsheetml.sheet &quot;access plus 1 hour&quot;\n";
    $rules .= "ExpiresByType application/vnd.ms-powerpoint &quot;access plus 1 hour&quot;\n";
    $rules .= "ExpiresByType application/vnd.openxmlformats-officedocument.presentationml.presentation &quot;access plus 1 hour&quot;\n";
    $rules .= "</IfModule>\n";
    $rules .= "# END AquaLuxe Browser Caching\n";
    
    // Add rules to .htaccess
    $contents .= $rules;
    
    // Save file
    $result = file_put_contents( $htaccess_file, $contents );
    
    return $result !== false;
}

/**
 * Remove browser caching from .htaccess
 *
 * @return bool|WP_Error
 */
function aqualuxe_performance_remove_browser_caching_from_htaccess() {
    // Get .htaccess file path
    $htaccess_file = ABSPATH . '.htaccess';
    
    // Check if file exists
    if ( ! file_exists( $htaccess_file ) ) {
        return new WP_Error( 'htaccess_not_found', __( '.htaccess file not found.', 'aqualuxe' ) );
    }
    
    // Check if file is writable
    if ( ! is_writable( $htaccess_file ) ) {
        return new WP_Error( 'htaccess_not_writable', __( '.htaccess file is not writable.', 'aqualuxe' ) );
    }
    
    // Get file contents
    $contents = file_get_contents( $htaccess_file );
    
    // Check if browser caching is added
    if ( strpos( $contents, '# BEGIN AquaLuxe Browser Caching' ) === false ) {
        return true;
    }
    
    // Remove browser caching rules
    $pattern = '/\s*# BEGIN AquaLuxe Browser Caching.*?# END AquaLuxe Browser Caching\s*/s';
    $contents = preg_replace( $pattern, '', $contents );
    
    // Save file
    $result = file_put_contents( $htaccess_file, $contents );
    
    return $result !== false;
}

/**
 * Add GZIP compression to .htaccess
 *
 * @return bool|WP_Error
 */
function aqualuxe_performance_add_gzip_to_htaccess() {
    // Check if GZIP compression is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_gzip', true ) ) {
        return false;
    }
    
    // Get .htaccess file path
    $htaccess_file = ABSPATH . '.htaccess';
    
    // Check if file exists
    if ( ! file_exists( $htaccess_file ) ) {
        return new WP_Error( 'htaccess_not_found', __( '.htaccess file not found.', 'aqualuxe' ) );
    }
    
    // Check if file is writable
    if ( ! is_writable( $htaccess_file ) ) {
        return new WP_Error( 'htaccess_not_writable', __( '.htaccess file is not writable.', 'aqualuxe' ) );
    }
    
    // Get file contents
    $contents = file_get_contents( $htaccess_file );
    
    // Check if GZIP compression is already added
    if ( strpos( $contents, '# BEGIN AquaLuxe GZIP Compression' ) !== false ) {
        return true;
    }
    
    // GZIP compression rules
    $rules = "\n# BEGIN AquaLuxe GZIP Compression\n";
    $rules .= "<IfModule mod_deflate.c>\n";
    $rules .= "AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript application/x-javascript application/json application/xml application/rss+xml application/atom+xml application/vnd.ms-fontobject application/x-font-ttf font/opentype font/ttf font/eot font/otf image/svg+xml\n";
    $rules .= "</IfModule>\n";
    $rules .= "# END AquaLuxe GZIP Compression\n";
    
    // Add rules to .htaccess
    $contents .= $rules;
    
    // Save file
    $result = file_put_contents( $htaccess_file, $contents );
    
    return $result !== false;
}

/**
 * Remove GZIP compression from .htaccess
 *
 * @return bool|WP_Error
 */
function aqualuxe_performance_remove_gzip_from_htaccess() {
    // Get .htaccess file path
    $htaccess_file = ABSPATH . '.htaccess';
    
    // Check if file exists
    if ( ! file_exists( $htaccess_file ) ) {
        return new WP_Error( 'htaccess_not_found', __( '.htaccess file not found.', 'aqualuxe' ) );
    }
    
    // Check if file is writable
    if ( ! is_writable( $htaccess_file ) ) {
        return new WP_Error( 'htaccess_not_writable', __( '.htaccess file is not writable.', 'aqualuxe' ) );
    }
    
    // Get file contents
    $contents = file_get_contents( $htaccess_file );
    
    // Check if GZIP compression is added
    if ( strpos( $contents, '# BEGIN AquaLuxe GZIP Compression' ) === false ) {
        return true;
    }
    
    // Remove GZIP compression rules
    $pattern = '/\s*# BEGIN AquaLuxe GZIP Compression.*?# END AquaLuxe GZIP Compression\s*/s';
    $contents = preg_replace( $pattern, '', $contents );
    
    // Save file
    $result = file_put_contents( $htaccess_file, $contents );
    
    return $result !== false;
}

/**
 * Update .htaccess file
 *
 * @return bool|WP_Error
 */
function aqualuxe_performance_update_htaccess() {
    // Check if browser caching is enabled
    if ( aqualuxe_performance_get_option( 'enable_browser_caching', true ) ) {
        // Add browser caching to .htaccess
        $result = aqualuxe_performance_add_browser_caching_to_htaccess();
        
        // Check if there was an error
        if ( is_wp_error( $result ) ) {
            return $result;
        }
    } else {
        // Remove browser caching from .htaccess
        $result = aqualuxe_performance_remove_browser_caching_from_htaccess();
        
        // Check if there was an error
        if ( is_wp_error( $result ) ) {
            return $result;
        }
    }
    
    // Check if GZIP compression is enabled
    if ( aqualuxe_performance_get_option( 'enable_gzip', true ) ) {
        // Add GZIP compression to .htaccess
        $result = aqualuxe_performance_add_gzip_to_htaccess();
        
        // Check if there was an error
        if ( is_wp_error( $result ) ) {
            return $result;
        }
    } else {
        // Remove GZIP compression from .htaccess
        $result = aqualuxe_performance_remove_gzip_from_htaccess();
        
        // Check if there was an error
        if ( is_wp_error( $result ) ) {
            return $result;
        }
    }
    
    return true;
}

/**
 * Purge cache for URL
 *
 * @param string $url URL
 * @return bool
 */
function aqualuxe_performance_purge_cache_for_url( $url ) {
    // Get cache file path
    $cache_file = aqualuxe_performance_get_cache_file_path( $url );
    
    // Check if cache file exists
    if ( ! file_exists( $cache_file ) ) {
        return true;
    }
    
    // Delete cache file
    $result = unlink( $cache_file );
    
    return $result;
}

/**
 * Purge cache for post
 *
 * @param int $post_id Post ID
 * @return bool
 */
function aqualuxe_performance_purge_cache_for_post( $post_id ) {
    // Get post URL
    $url = get_permalink( $post_id );
    
    // Purge cache for URL
    $result = aqualuxe_performance_purge_cache_for_url( $url );
    
    return $result;
}

/**
 * Purge cache for term
 *
 * @param int $term_id Term ID
 * @param string $taxonomy Taxonomy
 * @return bool
 */
function aqualuxe_performance_purge_cache_for_term( $term_id, $taxonomy ) {
    // Get term URL
    $url = get_term_link( $term_id, $taxonomy );
    
    // Check if URL is valid
    if ( is_wp_error( $url ) ) {
        return false;
    }
    
    // Purge cache for URL
    $result = aqualuxe_performance_purge_cache_for_url( $url );
    
    return $result;
}

/**
 * Purge cache for author
 *
 * @param int $author_id Author ID
 * @return bool
 */
function aqualuxe_performance_purge_cache_for_author( $author_id ) {
    // Get author URL
    $url = get_author_posts_url( $author_id );
    
    // Purge cache for URL
    $result = aqualuxe_performance_purge_cache_for_url( $url );
    
    return $result;
}

/**
 * Purge cache for home
 *
 * @return bool
 */
function aqualuxe_performance_purge_cache_for_home() {
    // Get home URL
    $url = home_url();
    
    // Purge cache for URL
    $result = aqualuxe_performance_purge_cache_for_url( $url );
    
    return $result;
}

/**
 * Purge cache for search
 *
 * @param string $query Search query
 * @return bool
 */
function aqualuxe_performance_purge_cache_for_search( $query ) {
    // Get search URL
    $url = get_search_link( $query );
    
    // Purge cache for URL
    $result = aqualuxe_performance_purge_cache_for_url( $url );
    
    return $result;
}

/**
 * Purge cache for date
 *
 * @param int $year Year
 * @param int $month Month
 * @param int $day Day
 * @return bool
 */
function aqualuxe_performance_purge_cache_for_date( $year, $month = 0, $day = 0 ) {
    // Get date URL
    if ( $day > 0 ) {
        $url = get_day_link( $year, $month, $day );
    } elseif ( $month > 0 ) {
        $url = get_month_link( $year, $month );
    } else {
        $url = get_year_link( $year );
    }
    
    // Purge cache for URL
    $result = aqualuxe_performance_purge_cache_for_url( $url );
    
    return $result;
}

/**
 * Purge cache for post type archive
 *
 * @param string $post_type Post type
 * @return bool
 */
function aqualuxe_performance_purge_cache_for_post_type_archive( $post_type ) {
    // Get post type archive URL
    $url = get_post_type_archive_link( $post_type );
    
    // Check if URL is valid
    if ( ! $url ) {
        return false;
    }
    
    // Purge cache for URL
    $result = aqualuxe_performance_purge_cache_for_url( $url );
    
    return $result;
}

/**
 * Purge cache for feed
 *
 * @param string $feed Feed name
 * @return bool
 */
function aqualuxe_performance_purge_cache_for_feed( $feed = '' ) {
    // Get feed URL
    $url = get_feed_link( $feed );
    
    // Purge cache for URL
    $result = aqualuxe_performance_purge_cache_for_url( $url );
    
    return $result;
}

/**
 * Purge cache for comment feed
 *
 * @param int $post_id Post ID
 * @return bool
 */
function aqualuxe_performance_purge_cache_for_comment_feed( $post_id ) {
    // Get comment feed URL
    $url = get_post_comments_feed_link( $post_id );
    
    // Check if URL is valid
    if ( ! $url ) {
        return false;
    }
    
    // Purge cache for URL
    $result = aqualuxe_performance_purge_cache_for_url( $url );
    
    return $result;
}

/**
 * Purge cache for all
 *
 * @return bool
 */
function aqualuxe_performance_purge_cache_for_all() {
    // Clear cache
    $result = aqualuxe_performance_clear_cache();
    
    return $result;
}

/**
 * Register cache purge hooks
 *
 * @return void
 */
function aqualuxe_performance_register_cache_purge_hooks() {
    // Check if caching is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_caching', true ) ) {
        return;
    }
    
    // Post hooks
    add_action( 'save_post', 'aqualuxe_performance_purge_cache_for_post' );
    add_action( 'deleted_post', 'aqualuxe_performance_purge_cache_for_post' );
    add_action( 'transition_post_status', 'aqualuxe_performance_purge_cache_for_post_status', 10, 3 );
    
    // Term hooks
    add_action( 'edited_term', 'aqualuxe_performance_purge_cache_for_term', 10, 2 );
    add_action( 'delete_term', 'aqualuxe_performance_purge_cache_for_term', 10, 2 );
    
    // Comment hooks
    add_action( 'comment_post', 'aqualuxe_performance_purge_cache_for_comment', 10, 2 );
    add_action( 'edit_comment', 'aqualuxe_performance_purge_cache_for_comment' );
    add_action( 'delete_comment', 'aqualuxe_performance_purge_cache_for_comment' );
    
    // User hooks
    add_action( 'user_register', 'aqualuxe_performance_purge_cache_for_author' );
    add_action( 'profile_update', 'aqualuxe_performance_purge_cache_for_author' );
    add_action( 'delete_user', 'aqualuxe_performance_purge_cache_for_author' );
    
    // Theme hooks
    add_action( 'switch_theme', 'aqualuxe_performance_purge_cache_for_all' );
    add_action( 'customize_save_after', 'aqualuxe_performance_purge_cache_for_all' );
    
    // Plugin hooks
    add_action( 'activated_plugin', 'aqualuxe_performance_purge_cache_for_all' );
    add_action( 'deactivated_plugin', 'aqualuxe_performance_purge_cache_for_all' );
    
    // Widget hooks
    add_action( 'update_option_sidebars_widgets', 'aqualuxe_performance_purge_cache_for_all' );
    
    // Menu hooks
    add_action( 'wp_update_nav_menu', 'aqualuxe_performance_purge_cache_for_all' );
    
    // Option hooks
    add_action( 'update_option', 'aqualuxe_performance_purge_cache_for_option', 10, 3 );
}

/**
 * Purge cache for post status
 *
 * @param string $new_status New status
 * @param string $old_status Old status
 * @param WP_Post $post Post object
 * @return void
 */
function aqualuxe_performance_purge_cache_for_post_status( $new_status, $old_status, $post ) {
    // Check if status changed
    if ( $new_status === $old_status ) {
        return;
    }
    
    // Purge cache for post
    aqualuxe_performance_purge_cache_for_post( $post->ID );
    
    // Purge cache for home
    aqualuxe_performance_purge_cache_for_home();
    
    // Purge cache for post type archive
    aqualuxe_performance_purge_cache_for_post_type_archive( $post->post_type );
    
    // Purge cache for feed
    aqualuxe_performance_purge_cache_for_feed();
    
    // Purge cache for comment feed
    aqualuxe_performance_purge_cache_for_comment_feed( $post->ID );
    
    // Purge cache for author
    aqualuxe_performance_purge_cache_for_author( $post->post_author );
    
    // Purge cache for terms
    $taxonomies = get_object_taxonomies( $post->post_type );
    
    foreach ( $taxonomies as $taxonomy ) {
        $terms = get_the_terms( $post->ID, $taxonomy );
        
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                aqualuxe_performance_purge_cache_for_term( $term->term_id, $taxonomy );
            }
        }
    }
    
    // Purge cache for date
    $year = get_the_time( 'Y', $post->ID );
    $month = get_the_time( 'm', $post->ID );
    $day = get_the_time( 'd', $post->ID );
    
    aqualuxe_performance_purge_cache_for_date( $year, $month, $day );
    aqualuxe_performance_purge_cache_for_date( $year, $month );
    aqualuxe_performance_purge_cache_for_date( $year );
}

/**
 * Purge cache for comment
 *
 * @param int $comment_id Comment ID
 * @param int $comment_approved Comment approved
 * @return void
 */
function aqualuxe_performance_purge_cache_for_comment( $comment_id, $comment_approved = 0 ) {
    // Get comment
    $comment = get_comment( $comment_id );
    
    // Check if comment exists
    if ( ! $comment ) {
        return;
    }
    
    // Purge cache for post
    aqualuxe_performance_purge_cache_for_post( $comment->comment_post_ID );
    
    // Purge cache for comment feed
    aqualuxe_performance_purge_cache_for_comment_feed( $comment->comment_post_ID );
}

/**
 * Purge cache for option
 *
 * @param string $option Option name
 * @param mixed $old_value Old value
 * @param mixed $value New value
 * @return void
 */
function aqualuxe_performance_purge_cache_for_option( $option, $old_value, $value ) {
    // Check if option is relevant
    $relevant_options = array(
        'blogname',
        'blogdescription',
        'posts_per_page',
        'permalink_structure',
        'rewrite_rules',
        'category_base',
        'tag_base',
    );
    
    if ( in_array( $option, $relevant_options, true ) ) {
        // Purge cache for all
        aqualuxe_performance_purge_cache_for_all();
    }
}

/**
 * Get object cache stats
 *
 * @return array
 */
function aqualuxe_performance_get_object_cache_stats() {
    global $wp_object_cache;
    
    // Check if object cache is available
    if ( ! $wp_object_cache || ! method_exists( $wp_object_cache, 'stats' ) ) {
        return array();
    }
    
    // Get stats
    $stats = $wp_object_cache->stats();
    
    return $stats;
}

/**
 * Get object cache hit ratio
 *
 * @return float
 */
function aqualuxe_performance_get_object_cache_hit_ratio() {
    // Get stats
    $stats = aqualuxe_performance_get_object_cache_stats();
    
    // Check if stats are available
    if ( empty( $stats ) || ! isset( $stats['hits'] ) || ! isset( $stats['misses'] ) ) {
        return 0;
    }
    
    // Calculate hit ratio
    $total = $stats['hits'] + $stats['misses'];
    
    if ( $total === 0 ) {
        return 0;
    }
    
    $ratio = $stats['hits'] / $total;
    
    return $ratio;
}

/**
 * Get object cache hit ratio formatted
 *
 * @return string
 */
function aqualuxe_performance_get_object_cache_hit_ratio_formatted() {
    // Get hit ratio
    $ratio = aqualuxe_performance_get_object_cache_hit_ratio();
    
    // Format ratio
    $formatted = number_format( $ratio * 100, 2 ) . '%';
    
    return $formatted;
}

/**
 * Get object cache size
 *
 * @return int
 */
function aqualuxe_performance_get_object_cache_size() {
    global $wp_object_cache;
    
    // Check if object cache is available
    if ( ! $wp_object_cache || ! property_exists( $wp_object_cache, 'cache' ) ) {
        return 0;
    }
    
    // Get cache
    $cache = $wp_object_cache->cache;
    
    // Calculate size
    $size = strlen( serialize( $cache ) );
    
    return $size;
}

/**
 * Get object cache size formatted
 *
 * @return string
 */
function aqualuxe_performance_get_object_cache_size_formatted() {
    // Get size
    $size = aqualuxe_performance_get_object_cache_size();
    
    // Format size
    $formatted = aqualuxe_performance_format_memory( $size );
    
    return $formatted;
}

/**
 * Flush object cache
 *
 * @return bool
 */
function aqualuxe_performance_flush_object_cache() {
    // Flush cache
    $result = wp_cache_flush();
    
    return $result;
}

/**
 * Get database cache stats
 *
 * @return array
 */
function aqualuxe_performance_get_database_cache_stats() {
    global $wpdb;
    
    // Check if database is available
    if ( ! $wpdb ) {
        return array();
    }
    
    // Get stats
    $stats = array(
        'queries' => $wpdb->num_queries,
        'time' => $wpdb->time_spent,
    );
    
    return $stats;
}

/**
 * Get database cache queries
 *
 * @return int
 */
function aqualuxe_performance_get_database_cache_queries() {
    // Get stats
    $stats = aqualuxe_performance_get_database_cache_stats();
    
    // Check if stats are available
    if ( empty( $stats ) || ! isset( $stats['queries'] ) ) {
        return 0;
    }
    
    return $stats['queries'];
}

/**
 * Get database cache time
 *
 * @return float
 */
function aqualuxe_performance_get_database_cache_time() {
    // Get stats
    $stats = aqualuxe_performance_get_database_cache_stats();
    
    // Check if stats are available
    if ( empty( $stats ) || ! isset( $stats['time'] ) ) {
        return 0;
    }
    
    return $stats['time'];
}

/**
 * Get database cache time formatted
 *
 * @return string
 */
function aqualuxe_performance_get_database_cache_time_formatted() {
    // Get time
    $time = aqualuxe_performance_get_database_cache_time();
    
    // Format time
    $formatted = aqualuxe_performance_format_time( $time );
    
    return $formatted;
}

/**
 * Get database cache average query time
 *
 * @return float
 */
function aqualuxe_performance_get_database_cache_average_query_time() {
    // Get stats
    $stats = aqualuxe_performance_get_database_cache_stats();
    
    // Check if stats are available
    if ( empty( $stats ) || ! isset( $stats['queries'] ) || ! isset( $stats['time'] ) ) {
        return 0;
    }
    
    // Calculate average query time
    if ( $stats['queries'] === 0 ) {
        return 0;
    }
    
    $average = $stats['time'] / $stats['queries'];
    
    return $average;
}

/**
 * Get database cache average query time formatted
 *
 * @return string
 */
function aqualuxe_performance_get_database_cache_average_query_time_formatted() {
    // Get average query time
    $average = aqualuxe_performance_get_database_cache_average_query_time();
    
    // Format average query time
    $formatted = aqualuxe_performance_format_time( $average );
    
    return $formatted;
}

/**
 * Get cache status
 *
 * @return array
 */
function aqualuxe_performance_get_cache_status() {
    // Get cache directory
    $cache_dir = aqualuxe_performance_get_cache_dir();
    
    // Get all files in cache directory
    $files = glob( $cache_dir . '/*' );
    
    // Check if files exist
    if ( ! $files ) {
        $files = array();
    }
    
    // Count files
    $count = count( $files );
    
    // Calculate size
    $size = 0;
    
    foreach ( $files as $file ) {
        // Skip .htaccess and index.php
        if ( basename( $file ) === '.htaccess' || basename( $file ) === 'index.php' ) {
            continue;
        }
        
        // Add file size
        $size += filesize( $file );
    }
    
    // Get object cache stats
    $object_cache_stats = aqualuxe_performance_get_object_cache_stats();
    
    // Get database cache stats
    $database_cache_stats = aqualuxe_performance_get_database_cache_stats();
    
    // Build status
    $status = array(
        'enabled' => aqualuxe_performance_get_option( 'enable_caching', true ),
        'files' => $count,
        'size' => $size,
        'size_formatted' => aqualuxe_performance_format_memory( $size ),
        'object_cache' => $object_cache_stats,
        'database_cache' => $database_cache_stats,
    );
    
    return $status;
}

/**
 * Get cache status HTML
 *
 * @return string
 */
function aqualuxe_performance_get_cache_status_html() {
    // Get cache status
    $status = aqualuxe_performance_get_cache_status();
    
    // Build HTML
    $html = '<div class="aqualuxe-cache-status">';
    
    // Add status
    $html .= '<div class="aqualuxe-cache-status-section">';
    $html .= '<h3>' . __( 'Cache Status', 'aqualuxe' ) . '</h3>';
    $html .= '<table class="aqualuxe-cache-status-table">';
    $html .= '<tr>';
    $html .= '<th>' . __( 'Enabled', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . ( $status['enabled'] ? __( 'Yes', 'aqualuxe' ) : __( 'No', 'aqualuxe' ) ) . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'Files', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . $status['files'] . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'Size', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . $status['size_formatted'] . '</td>';
    $html .= '</tr>';
    $html .= '</table>';
    $html .= '</div>';
    
    // Add object cache
    if ( ! empty( $status['object_cache'] ) ) {
        $html .= '<div class="aqualuxe-cache-status-section">';
        $html .= '<h3>' . __( 'Object Cache', 'aqualuxe' ) . '</h3>';
        $html .= '<table class="aqualuxe-cache-status-table">';
        
        foreach ( $status['object_cache'] as $key => $value ) {
            $html .= '<tr>';
            $html .= '<th>' . ucfirst( $key ) . '</th>';
            $html .= '<td>' . $value . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        $html .= '</div>';
    }
    
    // Add database cache
    if ( ! empty( $status['database_cache'] ) ) {
        $html .= '<div class="aqualuxe-cache-status-section">';
        $html .= '<h3>' . __( 'Database Cache', 'aqualuxe' ) . '</h3>';
        $html .= '<table class="aqualuxe-cache-status-table">';
        
        foreach ( $status['database_cache'] as $key => $value ) {
            $html .= '<tr>';
            $html .= '<th>' . ucfirst( $key ) . '</th>';
            $html .= '<td>' . $value . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Display cache status
 *
 * @return void
 */
function aqualuxe_performance_display_cache_status() {
    // Get cache status HTML
    $html = aqualuxe_performance_get_cache_status_html();
    
    // Display cache status
    echo $html;
}