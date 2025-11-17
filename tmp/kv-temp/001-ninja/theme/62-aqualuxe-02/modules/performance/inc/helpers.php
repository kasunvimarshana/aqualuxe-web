<?php
/**
 * AquaLuxe Performance Helper Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/Performance
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Get cache directory
 *
 * @return string
 */
function aqualuxe_performance_get_cache_dir() {
    // Get upload directory
    $upload_dir = wp_upload_dir();
    
    // Set cache directory
    $cache_dir = $upload_dir['basedir'] . '/aqualuxe-cache';
    
    // Create directory if it doesn't exist
    if ( ! file_exists( $cache_dir ) ) {
        wp_mkdir_p( $cache_dir );
        
        // Create .htaccess file
        $htaccess = "Options -Indexes\n";
        $htaccess .= "<IfModule mod_expires.c>\n";
        $htaccess .= "ExpiresActive On\n";
        $htaccess .= "ExpiresByType text/html &quot;access plus 0 seconds&quot;\n";
        $htaccess .= "</IfModule>\n";
        
        // Save .htaccess file
        file_put_contents( $cache_dir . '/.htaccess', $htaccess );
        
        // Create index.php file
        file_put_contents( $cache_dir . '/index.php', '<?php // Silence is golden' );
    }
    
    return $cache_dir;
}

/**
 * Get cache URL
 *
 * @return string
 */
function aqualuxe_performance_get_cache_url() {
    // Get upload directory
    $upload_dir = wp_upload_dir();
    
    // Set cache URL
    $cache_url = $upload_dir['baseurl'] . '/aqualuxe-cache';
    
    return $cache_url;
}

/**
 * Get cache file path
 *
 * @param string $url URL
 * @return string
 */
function aqualuxe_performance_get_cache_file_path( $url ) {
    // Get cache directory
    $cache_dir = aqualuxe_performance_get_cache_dir();
    
    // Generate cache key
    $cache_key = md5( $url );
    
    // Set cache file path
    $cache_file = $cache_dir . '/' . $cache_key . '.html';
    
    return $cache_file;
}

/**
 * Check if URL should be cached
 *
 * @param string $url URL
 * @return bool
 */
function aqualuxe_performance_should_cache_url( $url ) {
    // Don't cache admin pages
    if ( is_admin() ) {
        return false;
    }
    
    // Don't cache search pages
    if ( is_search() ) {
        return false;
    }
    
    // Don't cache 404 pages
    if ( is_404() ) {
        return false;
    }
    
    // Don't cache feed pages
    if ( is_feed() ) {
        return false;
    }
    
    // Don't cache if user is logged in
    if ( is_user_logged_in() ) {
        return false;
    }
    
    // Don't cache if POST request
    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
        return false;
    }
    
    // Don't cache if query string
    if ( ! empty( $_GET ) ) {
        return false;
    }
    
    // Don't cache if WooCommerce cart or checkout
    if ( function_exists( 'is_woocommerce' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
        return false;
    }
    
    return true;
}

/**
 * Check if file is CSS
 *
 * @param string $file File path
 * @return bool
 */
function aqualuxe_performance_is_css( $file ) {
    // Get file extension
    $extension = pathinfo( $file, PATHINFO_EXTENSION );
    
    // Check if CSS
    return $extension === 'css';
}

/**
 * Check if file is JavaScript
 *
 * @param string $file File path
 * @return bool
 */
function aqualuxe_performance_is_js( $file ) {
    // Get file extension
    $extension = pathinfo( $file, PATHINFO_EXTENSION );
    
    // Check if JavaScript
    return $extension === 'js';
}

/**
 * Check if file is image
 *
 * @param string $file File path
 * @return bool
 */
function aqualuxe_performance_is_image( $file ) {
    // Get file extension
    $extension = pathinfo( $file, PATHINFO_EXTENSION );
    
    // Check if image
    return in_array( $extension, array( 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg' ), true );
}

/**
 * Check if file is font
 *
 * @param string $file File path
 * @return bool
 */
function aqualuxe_performance_is_font( $file ) {
    // Get file extension
    $extension = pathinfo( $file, PATHINFO_EXTENSION );
    
    // Check if font
    return in_array( $extension, array( 'woff', 'woff2', 'ttf', 'otf', 'eot' ), true );
}

/**
 * Check if file is video
 *
 * @param string $file File path
 * @return bool
 */
function aqualuxe_performance_is_video( $file ) {
    // Get file extension
    $extension = pathinfo( $file, PATHINFO_EXTENSION );
    
    // Check if video
    return in_array( $extension, array( 'mp4', 'webm', 'ogg' ), true );
}

/**
 * Check if file is audio
 *
 * @param string $file File path
 * @return bool
 */
function aqualuxe_performance_is_audio( $file ) {
    // Get file extension
    $extension = pathinfo( $file, PATHINFO_EXTENSION );
    
    // Check if audio
    return in_array( $extension, array( 'mp3', 'wav', 'ogg' ), true );
}

/**
 * Check if file is document
 *
 * @param string $file File path
 * @return bool
 */
function aqualuxe_performance_is_document( $file ) {
    // Get file extension
    $extension = pathinfo( $file, PATHINFO_EXTENSION );
    
    // Check if document
    return in_array( $extension, array( 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx' ), true );
}

/**
 * Check if file is external
 *
 * @param string $file File path
 * @return bool
 */
function aqualuxe_performance_is_external( $file ) {
    // Check if external
    return strpos( $file, 'http' ) === 0 && strpos( $file, site_url() ) !== 0;
}

/**
 * Get file contents
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_contents( $file ) {
    // Check if file is external
    if ( aqualuxe_performance_is_external( $file ) ) {
        // Get file contents
        $response = wp_remote_get( $file );
        
        // Check if response is valid
        if ( is_wp_error( $response ) ) {
            return '';
        }
        
        // Get response body
        $contents = wp_remote_retrieve_body( $response );
    } else {
        // Get file path
        $file_path = ABSPATH . str_replace( site_url(), '', $file );
        
        // Check if file exists
        if ( ! file_exists( $file_path ) ) {
            return '';
        }
        
        // Get file contents
        $contents = file_get_contents( $file_path );
    }
    
    return $contents;
}

/**
 * Get file size
 *
 * @param string $file File path
 * @return int
 */
function aqualuxe_performance_get_file_size( $file ) {
    // Check if file is external
    if ( aqualuxe_performance_is_external( $file ) ) {
        // Get file headers
        $response = wp_remote_head( $file );
        
        // Check if response is valid
        if ( is_wp_error( $response ) ) {
            return 0;
        }
        
        // Get content length
        $size = wp_remote_retrieve_header( $response, 'content-length' );
        
        // Check if size is valid
        if ( ! $size ) {
            return 0;
        }
    } else {
        // Get file path
        $file_path = ABSPATH . str_replace( site_url(), '', $file );
        
        // Check if file exists
        if ( ! file_exists( $file_path ) ) {
            return 0;
        }
        
        // Get file size
        $size = filesize( $file_path );
    }
    
    return (int) $size;
}

/**
 * Get file modified time
 *
 * @param string $file File path
 * @return int
 */
function aqualuxe_performance_get_file_modified_time( $file ) {
    // Check if file is external
    if ( aqualuxe_performance_is_external( $file ) ) {
        // Get file headers
        $response = wp_remote_head( $file );
        
        // Check if response is valid
        if ( is_wp_error( $response ) ) {
            return 0;
        }
        
        // Get last modified
        $modified = wp_remote_retrieve_header( $response, 'last-modified' );
        
        // Check if modified is valid
        if ( ! $modified ) {
            return 0;
        }
        
        // Convert to timestamp
        $modified = strtotime( $modified );
    } else {
        // Get file path
        $file_path = ABSPATH . str_replace( site_url(), '', $file );
        
        // Check if file exists
        if ( ! file_exists( $file_path ) ) {
            return 0;
        }
        
        // Get file modified time
        $modified = filemtime( $file_path );
    }
    
    return (int) $modified;
}

/**
 * Get file type
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_type( $file ) {
    // Check if CSS
    if ( aqualuxe_performance_is_css( $file ) ) {
        return 'css';
    }
    
    // Check if JavaScript
    if ( aqualuxe_performance_is_js( $file ) ) {
        return 'js';
    }
    
    // Check if image
    if ( aqualuxe_performance_is_image( $file ) ) {
        return 'image';
    }
    
    // Check if font
    if ( aqualuxe_performance_is_font( $file ) ) {
        return 'font';
    }
    
    // Check if video
    if ( aqualuxe_performance_is_video( $file ) ) {
        return 'video';
    }
    
    // Check if audio
    if ( aqualuxe_performance_is_audio( $file ) ) {
        return 'audio';
    }
    
    // Check if document
    if ( aqualuxe_performance_is_document( $file ) ) {
        return 'document';
    }
    
    return 'other';
}

/**
 * Get file MIME type
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_mime_type( $file ) {
    // Get file type
    $type = aqualuxe_performance_get_file_type( $file );
    
    // Get file extension
    $extension = pathinfo( $file, PATHINFO_EXTENSION );
    
    // Set MIME type
    switch ( $type ) {
        case 'css':
            return 'text/css';
        case 'js':
            return 'application/javascript';
        case 'image':
            switch ( $extension ) {
                case 'jpg':
                case 'jpeg':
                    return 'image/jpeg';
                case 'png':
                    return 'image/png';
                case 'gif':
                    return 'image/gif';
                case 'webp':
                    return 'image/webp';
                case 'svg':
                    return 'image/svg+xml';
                default:
                    return 'image/jpeg';
            }
        case 'font':
            switch ( $extension ) {
                case 'woff':
                    return 'font/woff';
                case 'woff2':
                    return 'font/woff2';
                case 'ttf':
                    return 'font/ttf';
                case 'otf':
                    return 'font/otf';
                case 'eot':
                    return 'application/vnd.ms-fontobject';
                default:
                    return 'font/woff2';
            }
        case 'video':
            switch ( $extension ) {
                case 'mp4':
                    return 'video/mp4';
                case 'webm':
                    return 'video/webm';
                case 'ogg':
                    return 'video/ogg';
                default:
                    return 'video/mp4';
            }
        case 'audio':
            switch ( $extension ) {
                case 'mp3':
                    return 'audio/mpeg';
                case 'wav':
                    return 'audio/wav';
                case 'ogg':
                    return 'audio/ogg';
                default:
                    return 'audio/mpeg';
            }
        case 'document':
            switch ( $extension ) {
                case 'pdf':
                    return 'application/pdf';
                case 'doc':
                    return 'application/msword';
                case 'docx':
                    return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                case 'xls':
                    return 'application/vnd.ms-excel';
                case 'xlsx':
                    return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                case 'ppt':
                    return 'application/vnd.ms-powerpoint';
                case 'pptx':
                    return 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
                default:
                    return 'application/pdf';
            }
        default:
            return 'text/html';
    }
}

/**
 * Get file preload type
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_preload_type( $file ) {
    // Get file type
    $type = aqualuxe_performance_get_file_type( $file );
    
    // Set preload type
    switch ( $type ) {
        case 'css':
            return 'style';
        case 'js':
            return 'script';
        case 'image':
            return 'image';
        case 'font':
            return 'font';
        case 'video':
            return 'video';
        case 'audio':
            return 'audio';
        case 'document':
            return 'document';
        default:
            return 'fetch';
    }
}

/**
 * Get file crossorigin attribute
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_crossorigin( $file ) {
    // Check if file is external
    if ( aqualuxe_performance_is_external( $file ) ) {
        return ' crossorigin="anonymous"';
    }
    
    return '';
}

/**
 * Get file integrity attribute
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_integrity( $file ) {
    // Get file contents
    $contents = aqualuxe_performance_get_file_contents( $file );
    
    // Check if contents are valid
    if ( ! $contents ) {
        return '';
    }
    
    // Generate hash
    $hash = hash( 'sha384', $contents, true );
    
    // Encode hash
    $hash = base64_encode( $hash );
    
    return ' integrity="sha384-' . $hash . '"';
}

/**
 * Get file URL
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_url( $file ) {
    // Check if file is external
    if ( aqualuxe_performance_is_external( $file ) ) {
        return $file;
    }
    
    // Get file path
    $file_path = ABSPATH . str_replace( site_url(), '', $file );
    
    // Check if file exists
    if ( ! file_exists( $file_path ) ) {
        return $file;
    }
    
    // Get file URL
    $url = site_url( str_replace( ABSPATH, '', $file_path ) );
    
    return $url;
}

/**
 * Get file path
 *
 * @param string $file File URL
 * @return string
 */
function aqualuxe_performance_get_file_path( $file ) {
    // Check if file is external
    if ( aqualuxe_performance_is_external( $file ) ) {
        return $file;
    }
    
    // Get file path
    $file_path = ABSPATH . str_replace( site_url(), '', $file );
    
    return $file_path;
}

/**
 * Get file name
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_name( $file ) {
    // Get file name
    $name = basename( $file );
    
    return $name;
}

/**
 * Get file extension
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_extension( $file ) {
    // Get file extension
    $extension = pathinfo( $file, PATHINFO_EXTENSION );
    
    return $extension;
}

/**
 * Get file directory
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_directory( $file ) {
    // Get file directory
    $directory = dirname( $file );
    
    return $directory;
}

/**
 * Get file base name
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_base_name( $file ) {
    // Get file base name
    $base_name = pathinfo( $file, PATHINFO_FILENAME );
    
    return $base_name;
}

/**
 * Get file version
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_version( $file ) {
    // Get file modified time
    $modified = aqualuxe_performance_get_file_modified_time( $file );
    
    // Check if modified is valid
    if ( ! $modified ) {
        return '';
    }
    
    return $modified;
}

/**
 * Get file version query
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_version_query( $file ) {
    // Get file version
    $version = aqualuxe_performance_get_file_version( $file );
    
    // Check if version is valid
    if ( ! $version ) {
        return '';
    }
    
    return '?ver=' . $version;
}

/**
 * Get file URL with version
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_url_with_version( $file ) {
    // Get file URL
    $url = aqualuxe_performance_get_file_url( $file );
    
    // Get file version query
    $version = aqualuxe_performance_get_file_version_query( $file );
    
    return $url . $version;
}

/**
 * Get file hash
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_hash( $file ) {
    // Get file contents
    $contents = aqualuxe_performance_get_file_contents( $file );
    
    // Check if contents are valid
    if ( ! $contents ) {
        return '';
    }
    
    // Generate hash
    $hash = md5( $contents );
    
    return $hash;
}

/**
 * Get file cache key
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_cache_key( $file ) {
    // Get file hash
    $hash = aqualuxe_performance_get_file_hash( $file );
    
    // Check if hash is valid
    if ( ! $hash ) {
        return '';
    }
    
    // Get file base name
    $base_name = aqualuxe_performance_get_file_base_name( $file );
    
    return $base_name . '-' . $hash;
}

/**
 * Get file cache path
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_cache_path( $file ) {
    // Get cache directory
    $cache_dir = aqualuxe_performance_get_cache_dir();
    
    // Get file cache key
    $cache_key = aqualuxe_performance_get_file_cache_key( $file );
    
    // Check if cache key is valid
    if ( ! $cache_key ) {
        return '';
    }
    
    // Get file extension
    $extension = aqualuxe_performance_get_file_extension( $file );
    
    // Set cache file path
    $cache_file = $cache_dir . '/' . $cache_key . '.' . $extension;
    
    return $cache_file;
}

/**
 * Get file cache URL
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_file_cache_url( $file ) {
    // Get cache URL
    $cache_url = aqualuxe_performance_get_cache_url();
    
    // Get file cache key
    $cache_key = aqualuxe_performance_get_file_cache_key( $file );
    
    // Check if cache key is valid
    if ( ! $cache_key ) {
        return '';
    }
    
    // Get file extension
    $extension = aqualuxe_performance_get_file_extension( $file );
    
    // Set cache file URL
    $cache_url = $cache_url . '/' . $cache_key . '.' . $extension;
    
    return $cache_url;
}

/**
 * Check if file is cached
 *
 * @param string $file File path
 * @return bool
 */
function aqualuxe_performance_is_file_cached( $file ) {
    // Get file cache path
    $cache_path = aqualuxe_performance_get_file_cache_path( $file );
    
    // Check if cache path is valid
    if ( ! $cache_path ) {
        return false;
    }
    
    // Check if cache file exists
    return file_exists( $cache_path );
}

/**
 * Cache file
 *
 * @param string $file File path
 * @param string $contents File contents
 * @return bool
 */
function aqualuxe_performance_cache_file( $file, $contents ) {
    // Get file cache path
    $cache_path = aqualuxe_performance_get_file_cache_path( $file );
    
    // Check if cache path is valid
    if ( ! $cache_path ) {
        return false;
    }
    
    // Save file
    $result = file_put_contents( $cache_path, $contents );
    
    return $result !== false;
}

/**
 * Get cached file contents
 *
 * @param string $file File path
 * @return string
 */
function aqualuxe_performance_get_cached_file_contents( $file ) {
    // Get file cache path
    $cache_path = aqualuxe_performance_get_file_cache_path( $file );
    
    // Check if cache path is valid
    if ( ! $cache_path ) {
        return '';
    }
    
    // Check if cache file exists
    if ( ! file_exists( $cache_path ) ) {
        return '';
    }
    
    // Get file contents
    $contents = file_get_contents( $cache_path );
    
    return $contents;
}

/**
 * Delete cached file
 *
 * @param string $file File path
 * @return bool
 */
function aqualuxe_performance_delete_cached_file( $file ) {
    // Get file cache path
    $cache_path = aqualuxe_performance_get_file_cache_path( $file );
    
    // Check if cache path is valid
    if ( ! $cache_path ) {
        return false;
    }
    
    // Check if cache file exists
    if ( ! file_exists( $cache_path ) ) {
        return true;
    }
    
    // Delete file
    $result = unlink( $cache_path );
    
    return $result;
}

/**
 * Clear cache
 *
 * @return bool|WP_Error
 */
function aqualuxe_performance_clear_cache() {
    // Get cache directory
    $cache_dir = aqualuxe_performance_get_cache_dir();
    
    // Check if cache directory exists
    if ( ! file_exists( $cache_dir ) ) {
        return true;
    }
    
    // Get all files in cache directory
    $files = glob( $cache_dir . '/*' );
    
    // Check if files exist
    if ( ! $files ) {
        return true;
    }
    
    // Delete files
    foreach ( $files as $file ) {
        // Skip .htaccess and index.php
        if ( basename( $file ) === '.htaccess' || basename( $file ) === 'index.php' ) {
            continue;
        }
        
        // Delete file
        if ( ! unlink( $file ) ) {
            return new WP_Error( 'delete_failed', __( 'Failed to delete file: ', 'aqualuxe' ) . $file );
        }
    }
    
    return true;
}

/**
 * Get performance settings
 *
 * @param string $key Setting key
 * @param mixed $default Default value
 * @return mixed
 */
function aqualuxe_performance_get_setting( $key, $default = null ) {
    // Get module
    $module = AquaLuxe_Module_Loader::instance()->get_module( 'performance' );
    
    // Check if module exists
    if ( ! $module ) {
        return $default;
    }
    
    // Get setting
    return $module->get_setting( $key, $default );
}

/**
 * Update performance setting
 *
 * @param string $key Setting key
 * @param mixed $value Setting value
 * @return void
 */
function aqualuxe_performance_update_setting( $key, $value ) {
    // Get module
    $module = AquaLuxe_Module_Loader::instance()->get_module( 'performance' );
    
    // Check if module exists
    if ( ! $module ) {
        return;
    }
    
    // Update setting
    $module->update_setting( $key, $value );
}

/**
 * Get performance option
 *
 * @param string $key Option key
 * @param mixed $default Default value
 * @return mixed
 */
function aqualuxe_performance_get_option( $key, $default = null ) {
    // Get option
    $option = get_option( 'aqualuxe_performance_' . $key, $default );
    
    return $option;
}

/**
 * Update performance option
 *
 * @param string $key Option key
 * @param mixed $value Option value
 * @return bool
 */
function aqualuxe_performance_update_option( $key, $value ) {
    // Update option
    $result = update_option( 'aqualuxe_performance_' . $key, $value );
    
    return $result;
}

/**
 * Delete performance option
 *
 * @param string $key Option key
 * @return bool
 */
function aqualuxe_performance_delete_option( $key ) {
    // Delete option
    $result = delete_option( 'aqualuxe_performance_' . $key );
    
    return $result;
}

/**
 * Get performance transient
 *
 * @param string $key Transient key
 * @return mixed
 */
function aqualuxe_performance_get_transient( $key ) {
    // Get transient
    $transient = get_transient( 'aqualuxe_performance_' . $key );
    
    return $transient;
}

/**
 * Set performance transient
 *
 * @param string $key Transient key
 * @param mixed $value Transient value
 * @param int $expiration Expiration in seconds
 * @return bool
 */
function aqualuxe_performance_set_transient( $key, $value, $expiration = 0 ) {
    // Set transient
    $result = set_transient( 'aqualuxe_performance_' . $key, $value, $expiration );
    
    return $result;
}

/**
 * Delete performance transient
 *
 * @param string $key Transient key
 * @return bool
 */
function aqualuxe_performance_delete_transient( $key ) {
    // Delete transient
    $result = delete_transient( 'aqualuxe_performance_' . $key );
    
    return $result;
}

/**
 * Get performance cache
 *
 * @param string $key Cache key
 * @return mixed
 */
function aqualuxe_performance_get_cache( $key ) {
    // Get cache
    $cache = wp_cache_get( $key, 'aqualuxe_performance' );
    
    return $cache;
}

/**
 * Set performance cache
 *
 * @param string $key Cache key
 * @param mixed $value Cache value
 * @param int $expiration Expiration in seconds
 * @return bool
 */
function aqualuxe_performance_set_cache( $key, $value, $expiration = 0 ) {
    // Set cache
    $result = wp_cache_set( $key, $value, 'aqualuxe_performance', $expiration );
    
    return $result;
}

/**
 * Delete performance cache
 *
 * @param string $key Cache key
 * @return bool
 */
function aqualuxe_performance_delete_cache( $key ) {
    // Delete cache
    $result = wp_cache_delete( $key, 'aqualuxe_performance' );
    
    return $result;
}

/**
 * Flush performance cache
 *
 * @return bool
 */
function aqualuxe_performance_flush_cache() {
    // Flush cache
    $result = wp_cache_flush();
    
    return $result;
}

/**
 * Get performance log directory
 *
 * @return string
 */
function aqualuxe_performance_get_log_dir() {
    // Get upload directory
    $upload_dir = wp_upload_dir();
    
    // Set log directory
    $log_dir = $upload_dir['basedir'] . '/aqualuxe-logs';
    
    // Create directory if it doesn't exist
    if ( ! file_exists( $log_dir ) ) {
        wp_mkdir_p( $log_dir );
        
        // Create .htaccess file
        $htaccess = "Options -Indexes\n";
        $htaccess .= "Deny from all\n";
        
        // Save .htaccess file
        file_put_contents( $log_dir . '/.htaccess', $htaccess );
        
        // Create index.php file
        file_put_contents( $log_dir . '/index.php', '<?php // Silence is golden' );
    }
    
    return $log_dir;
}

/**
 * Get performance log file
 *
 * @param string $name Log name
 * @return string
 */
function aqualuxe_performance_get_log_file( $name ) {
    // Get log directory
    $log_dir = aqualuxe_performance_get_log_dir();
    
    // Set log file
    $log_file = $log_dir . '/' . $name . '.log';
    
    return $log_file;
}

/**
 * Write to performance log
 *
 * @param string $name Log name
 * @param string $message Log message
 * @return bool
 */
function aqualuxe_performance_write_log( $name, $message ) {
    // Get log file
    $log_file = aqualuxe_performance_get_log_file( $name );
    
    // Get current date and time
    $date = date( 'Y-m-d H:i:s' );
    
    // Format message
    $message = '[' . $date . '] ' . $message . PHP_EOL;
    
    // Write to log
    $result = file_put_contents( $log_file, $message, FILE_APPEND );
    
    return $result !== false;
}

/**
 * Get performance log
 *
 * @param string $name Log name
 * @return string
 */
function aqualuxe_performance_get_log( $name ) {
    // Get log file
    $log_file = aqualuxe_performance_get_log_file( $name );
    
    // Check if log file exists
    if ( ! file_exists( $log_file ) ) {
        return '';
    }
    
    // Get log contents
    $log = file_get_contents( $log_file );
    
    return $log;
}

/**
 * Clear performance log
 *
 * @param string $name Log name
 * @return bool
 */
function aqualuxe_performance_clear_log( $name ) {
    // Get log file
    $log_file = aqualuxe_performance_get_log_file( $name );
    
    // Check if log file exists
    if ( ! file_exists( $log_file ) ) {
        return true;
    }
    
    // Clear log
    $result = file_put_contents( $log_file, '' );
    
    return $result !== false;
}

/**
 * Delete performance log
 *
 * @param string $name Log name
 * @return bool
 */
function aqualuxe_performance_delete_log( $name ) {
    // Get log file
    $log_file = aqualuxe_performance_get_log_file( $name );
    
    // Check if log file exists
    if ( ! file_exists( $log_file ) ) {
        return true;
    }
    
    // Delete log
    $result = unlink( $log_file );
    
    return $result;
}

/**
 * Get performance logs
 *
 * @return array
 */
function aqualuxe_performance_get_logs() {
    // Get log directory
    $log_dir = aqualuxe_performance_get_log_dir();
    
    // Get all log files
    $files = glob( $log_dir . '/*.log' );
    
    // Check if files exist
    if ( ! $files ) {
        return array();
    }
    
    // Format logs
    $logs = array();
    
    foreach ( $files as $file ) {
        // Get log name
        $name = basename( $file, '.log' );
        
        // Get log size
        $size = filesize( $file );
        
        // Get log modified time
        $modified = filemtime( $file );
        
        // Add log
        $logs[ $name ] = array(
            'name' => $name,
            'file' => $file,
            'size' => $size,
            'modified' => $modified,
        );
    }
    
    return $logs;
}

/**
 * Clear all performance logs
 *
 * @return bool
 */
function aqualuxe_performance_clear_all_logs() {
    // Get logs
    $logs = aqualuxe_performance_get_logs();
    
    // Check if logs exist
    if ( ! $logs ) {
        return true;
    }
    
    // Clear logs
    foreach ( $logs as $log ) {
        // Clear log
        aqualuxe_performance_clear_log( $log['name'] );
    }
    
    return true;
}

/**
 * Delete all performance logs
 *
 * @return bool
 */
function aqualuxe_performance_delete_all_logs() {
    // Get logs
    $logs = aqualuxe_performance_get_logs();
    
    // Check if logs exist
    if ( ! $logs ) {
        return true;
    }
    
    // Delete logs
    foreach ( $logs as $log ) {
        // Delete log
        aqualuxe_performance_delete_log( $log['name'] );
    }
    
    return true;
}

/**
 * Get performance debug mode
 *
 * @return bool
 */
function aqualuxe_performance_is_debug_mode() {
    // Check if debug mode is enabled
    $debug_mode = aqualuxe_performance_get_option( 'debug_mode', false );
    
    return $debug_mode;
}

/**
 * Enable performance debug mode
 *
 * @return bool
 */
function aqualuxe_performance_enable_debug_mode() {
    // Enable debug mode
    $result = aqualuxe_performance_update_option( 'debug_mode', true );
    
    return $result;
}

/**
 * Disable performance debug mode
 *
 * @return bool
 */
function aqualuxe_performance_disable_debug_mode() {
    // Disable debug mode
    $result = aqualuxe_performance_update_option( 'debug_mode', false );
    
    return $result;
}

/**
 * Debug log
 *
 * @param string $message Log message
 * @return bool
 */
function aqualuxe_performance_debug_log( $message ) {
    // Check if debug mode is enabled
    if ( ! aqualuxe_performance_is_debug_mode() ) {
        return false;
    }
    
    // Write to log
    $result = aqualuxe_performance_write_log( 'debug', $message );
    
    return $result;
}

/**
 * Error log
 *
 * @param string $message Log message
 * @return bool
 */
function aqualuxe_performance_error_log( $message ) {
    // Write to log
    $result = aqualuxe_performance_write_log( 'error', $message );
    
    return $result;
}

/**
 * Get performance stats
 *
 * @return array
 */
function aqualuxe_performance_get_stats() {
    // Get stats
    $stats = aqualuxe_performance_get_option( 'stats', array() );
    
    return $stats;
}

/**
 * Update performance stats
 *
 * @param array $stats Stats
 * @return bool
 */
function aqualuxe_performance_update_stats( $stats ) {
    // Update stats
    $result = aqualuxe_performance_update_option( 'stats', $stats );
    
    return $result;
}

/**
 * Clear performance stats
 *
 * @return bool
 */
function aqualuxe_performance_clear_stats() {
    // Clear stats
    $result = aqualuxe_performance_update_option( 'stats', array() );
    
    return $result;
}

/**
 * Track performance stat
 *
 * @param string $key Stat key
 * @param mixed $value Stat value
 * @return bool
 */
function aqualuxe_performance_track_stat( $key, $value ) {
    // Get stats
    $stats = aqualuxe_performance_get_stats();
    
    // Add stat
    $stats[ $key ] = $value;
    
    // Update stats
    $result = aqualuxe_performance_update_stats( $stats );
    
    return $result;
}

/**
 * Get performance stat
 *
 * @param string $key Stat key
 * @param mixed $default Default value
 * @return mixed
 */
function aqualuxe_performance_get_stat( $key, $default = null ) {
    // Get stats
    $stats = aqualuxe_performance_get_stats();
    
    // Check if stat exists
    if ( ! isset( $stats[ $key ] ) ) {
        return $default;
    }
    
    return $stats[ $key ];
}

/**
 * Delete performance stat
 *
 * @param string $key Stat key
 * @return bool
 */
function aqualuxe_performance_delete_stat( $key ) {
    // Get stats
    $stats = aqualuxe_performance_get_stats();
    
    // Check if stat exists
    if ( ! isset( $stats[ $key ] ) ) {
        return true;
    }
    
    // Delete stat
    unset( $stats[ $key ] );
    
    // Update stats
    $result = aqualuxe_performance_update_stats( $stats );
    
    return $result;
}

/**
 * Get performance timer
 *
 * @param string $key Timer key
 * @return float
 */
function aqualuxe_performance_get_timer( $key ) {
    // Get timer
    $timer = aqualuxe_performance_get_stat( 'timer_' . $key );
    
    // Check if timer exists
    if ( $timer === null ) {
        return 0;
    }
    
    return $timer;
}

/**
 * Start performance timer
 *
 * @param string $key Timer key
 * @return bool
 */
function aqualuxe_performance_start_timer( $key ) {
    // Start timer
    $result = aqualuxe_performance_track_stat( 'timer_start_' . $key, microtime( true ) );
    
    return $result;
}

/**
 * Stop performance timer
 *
 * @param string $key Timer key
 * @return bool
 */
function aqualuxe_performance_stop_timer( $key ) {
    // Get start time
    $start = aqualuxe_performance_get_stat( 'timer_start_' . $key );
    
    // Check if timer exists
    if ( $start === null ) {
        return false;
    }
    
    // Calculate time
    $time = microtime( true ) - $start;
    
    // Track time
    $result = aqualuxe_performance_track_stat( 'timer_' . $key, $time );
    
    // Delete start time
    aqualuxe_performance_delete_stat( 'timer_start_' . $key );
    
    return $result;
}

/**
 * Get performance counter
 *
 * @param string $key Counter key
 * @return int
 */
function aqualuxe_performance_get_counter( $key ) {
    // Get counter
    $counter = aqualuxe_performance_get_stat( 'counter_' . $key );
    
    // Check if counter exists
    if ( $counter === null ) {
        return 0;
    }
    
    return $counter;
}

/**
 * Increment performance counter
 *
 * @param string $key Counter key
 * @param int $increment Increment value
 * @return bool
 */
function aqualuxe_performance_increment_counter( $key, $increment = 1 ) {
    // Get counter
    $counter = aqualuxe_performance_get_counter( $key );
    
    // Increment counter
    $counter += $increment;
    
    // Track counter
    $result = aqualuxe_performance_track_stat( 'counter_' . $key, $counter );
    
    return $result;
}

/**
 * Reset performance counter
 *
 * @param string $key Counter key
 * @return bool
 */
function aqualuxe_performance_reset_counter( $key ) {
    // Reset counter
    $result = aqualuxe_performance_track_stat( 'counter_' . $key, 0 );
    
    return $result;
}

/**
 * Get performance memory usage
 *
 * @param bool $real_usage Real usage
 * @return int
 */
function aqualuxe_performance_get_memory_usage( $real_usage = false ) {
    // Get memory usage
    $memory = memory_get_usage( $real_usage );
    
    return $memory;
}

/**
 * Get performance memory peak usage
 *
 * @param bool $real_usage Real usage
 * @return int
 */
function aqualuxe_performance_get_memory_peak_usage( $real_usage = false ) {
    // Get memory peak usage
    $memory = memory_get_peak_usage( $real_usage );
    
    return $memory;
}

/**
 * Format performance memory
 *
 * @param int $memory Memory in bytes
 * @return string
 */
function aqualuxe_performance_format_memory( $memory ) {
    // Format memory
    $unit = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
    
    return @round( $memory / pow( 1024, ( $i = floor( log( $memory, 1024 ) ) ) ), 2 ) . ' ' . $unit[ $i ];
}

/**
 * Format performance time
 *
 * @param float $time Time in seconds
 * @return string
 */
function aqualuxe_performance_format_time( $time ) {
    // Format time
    if ( $time < 0.001 ) {
        return round( $time * 1000000 ) . ' µs';
    } elseif ( $time < 1 ) {
        return round( $time * 1000, 2 ) . ' ms';
    } else {
        return round( $time, 2 ) . ' s';
    }
}

/**
 * Get performance report
 *
 * @return array
 */
function aqualuxe_performance_get_report() {
    // Get stats
    $stats = aqualuxe_performance_get_stats();
    
    // Get memory usage
    $memory = aqualuxe_performance_get_memory_usage();
    
    // Get memory peak usage
    $memory_peak = aqualuxe_performance_get_memory_peak_usage();
    
    // Get timers
    $timers = array();
    
    foreach ( $stats as $key => $value ) {
        // Check if timer
        if ( strpos( $key, 'timer_' ) === 0 && strpos( $key, 'timer_start_' ) !== 0 ) {
            // Get timer name
            $name = str_replace( 'timer_', '', $key );
            
            // Add timer
            $timers[ $name ] = $value;
        }
    }
    
    // Get counters
    $counters = array();
    
    foreach ( $stats as $key => $value ) {
        // Check if counter
        if ( strpos( $key, 'counter_' ) === 0 ) {
            // Get counter name
            $name = str_replace( 'counter_', '', $key );
            
            // Add counter
            $counters[ $name ] = $value;
        }
    }
    
    // Build report
    $report = array(
        'memory' => array(
            'usage' => $memory,
            'usage_formatted' => aqualuxe_performance_format_memory( $memory ),
            'peak' => $memory_peak,
            'peak_formatted' => aqualuxe_performance_format_memory( $memory_peak ),
        ),
        'timers' => $timers,
        'counters' => $counters,
    );
    
    return $report;
}

/**
 * Get performance report HTML
 *
 * @return string
 */
function aqualuxe_performance_get_report_html() {
    // Get report
    $report = aqualuxe_performance_get_report();
    
    // Build HTML
    $html = '<div class="aqualuxe-performance-report">';
    
    // Add memory
    $html .= '<div class="aqualuxe-performance-report-section">';
    $html .= '<h3>' . __( 'Memory', 'aqualuxe' ) . '</h3>';
    $html .= '<table class="aqualuxe-performance-report-table">';
    $html .= '<tr>';
    $html .= '<th>' . __( 'Usage', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . $report['memory']['usage_formatted'] . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'Peak', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . $report['memory']['peak_formatted'] . '</td>';
    $html .= '</tr>';
    $html .= '</table>';
    $html .= '</div>';
    
    // Add timers
    if ( ! empty( $report['timers'] ) ) {
        $html .= '<div class="aqualuxe-performance-report-section">';
        $html .= '<h3>' . __( 'Timers', 'aqualuxe' ) . '</h3>';
        $html .= '<table class="aqualuxe-performance-report-table">';
        
        foreach ( $report['timers'] as $name => $time ) {
            $html .= '<tr>';
            $html .= '<th>' . $name . '</th>';
            $html .= '<td>' . aqualuxe_performance_format_time( $time ) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        $html .= '</div>';
    }
    
    // Add counters
    if ( ! empty( $report['counters'] ) ) {
        $html .= '<div class="aqualuxe-performance-report-section">';
        $html .= '<h3>' . __( 'Counters', 'aqualuxe' ) . '</h3>';
        $html .= '<table class="aqualuxe-performance-report-table">';
        
        foreach ( $report['counters'] as $name => $count ) {
            $html .= '<tr>';
            $html .= '<th>' . $name . '</th>';
            $html .= '<td>' . $count . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Display performance report
 *
 * @return void
 */
function aqualuxe_performance_display_report() {
    // Check if debug mode is enabled
    if ( ! aqualuxe_performance_is_debug_mode() ) {
        return;
    }
    
    // Get report HTML
    $html = aqualuxe_performance_get_report_html();
    
    // Display report
    echo $html;
}

/**
 * Get performance test result
 *
 * @return array
 */
function aqualuxe_performance_get_test_result() {
    // Get test result
    $result = aqualuxe_performance_get_option( 'test_result', array() );
    
    return $result;
}

/**
 * Update performance test result
 *
 * @param array $result Test result
 * @return bool
 */
function aqualuxe_performance_update_test_result( $result ) {
    // Update test result
    $result = aqualuxe_performance_update_option( 'test_result', $result );
    
    return $result;
}

/**
 * Clear performance test result
 *
 * @return bool
 */
function aqualuxe_performance_clear_test_result() {
    // Clear test result
    $result = aqualuxe_performance_update_option( 'test_result', array() );
    
    return $result;
}

/**
 * Run performance test
 *
 * @return array
 */
function aqualuxe_performance_run_test() {
    // Start timer
    aqualuxe_performance_start_timer( 'test' );
    
    // Get home URL
    $url = home_url();
    
    // Get response
    $response = wp_remote_get( $url );
    
    // Stop timer
    aqualuxe_performance_stop_timer( 'test' );
    
    // Get time
    $time = aqualuxe_performance_get_timer( 'test' );
    
    // Check if response is valid
    if ( is_wp_error( $response ) ) {
        // Build result
        $result = array(
            'success' => false,
            'message' => $response->get_error_message(),
            'time' => $time,
            'time_formatted' => aqualuxe_performance_format_time( $time ),
            'date' => time(),
        );
    } else {
        // Get response code
        $code = wp_remote_retrieve_response_code( $response );
        
        // Get response body
        $body = wp_remote_retrieve_body( $response );
        
        // Get response size
        $size = strlen( $body );
        
        // Build result
        $result = array(
            'success' => $code === 200,
            'code' => $code,
            'size' => $size,
            'size_formatted' => aqualuxe_performance_format_memory( $size ),
            'time' => $time,
            'time_formatted' => aqualuxe_performance_format_time( $time ),
            'date' => time(),
        );
    }
    
    // Update test result
    aqualuxe_performance_update_test_result( $result );
    
    return $result;
}

/**
 * Get performance score
 *
 * @return int
 */
function aqualuxe_performance_get_score() {
    // Get test result
    $result = aqualuxe_performance_get_test_result();
    
    // Check if result exists
    if ( empty( $result ) || ! isset( $result['time'] ) ) {
        return 0;
    }
    
    // Calculate score
    $score = 100;
    
    // Deduct points for load time
    if ( $result['time'] > 1 ) {
        $score -= min( 50, ( $result['time'] - 1 ) * 50 );
    }
    
    // Deduct points for size
    if ( isset( $result['size'] ) && $result['size'] > 100000 ) {
        $score -= min( 25, ( $result['size'] - 100000 ) / 100000 * 25 );
    }
    
    // Ensure score is between 0 and 100
    $score = max( 0, min( 100, $score ) );
    
    return (int) $score;
}

/**
 * Get performance grade
 *
 * @return string
 */
function aqualuxe_performance_get_grade() {
    // Get score
    $score = aqualuxe_performance_get_score();
    
    // Get grade
    if ( $score >= 90 ) {
        return 'A';
    } elseif ( $score >= 80 ) {
        return 'B';
    } elseif ( $score >= 70 ) {
        return 'C';
    } elseif ( $score >= 60 ) {
        return 'D';
    } else {
        return 'F';
    }
}

/**
 * Get performance recommendations
 *
 * @return array
 */
function aqualuxe_performance_get_recommendations() {
    // Get test result
    $result = aqualuxe_performance_get_test_result();
    
    // Check if result exists
    if ( empty( $result ) ) {
        return array();
    }
    
    // Initialize recommendations
    $recommendations = array();
    
    // Check load time
    if ( isset( $result['time'] ) && $result['time'] > 1 ) {
        $recommendations[] = __( 'Your page load time is too high. Consider enabling caching and minification.', 'aqualuxe' );
    }
    
    // Check size
    if ( isset( $result['size'] ) && $result['size'] > 100000 ) {
        $recommendations[] = __( 'Your page size is too large. Consider optimizing images and minifying resources.', 'aqualuxe' );
    }
    
    // Check if caching is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_caching', true ) ) {
        $recommendations[] = __( 'Enable page caching to improve load times for repeat visitors.', 'aqualuxe' );
    }
    
    // Check if browser caching is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_browser_caching', true ) ) {
        $recommendations[] = __( 'Enable browser caching to improve load times for repeat visitors.', 'aqualuxe' );
    }
    
    // Check if GZIP compression is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_gzip', true ) ) {
        $recommendations[] = __( 'Enable GZIP compression to reduce file sizes.', 'aqualuxe' );
    }
    
    // Check if minification is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_minification', true ) ) {
        $recommendations[] = __( 'Enable minification to reduce file sizes.', 'aqualuxe' );
    }
    
    // Check if lazy loading is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_lazy_loading', true ) ) {
        $recommendations[] = __( 'Enable lazy loading to defer loading of non-critical resources.', 'aqualuxe' );
    }
    
    // Check if critical CSS is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_critical_css', true ) ) {
        $recommendations[] = __( 'Enable critical CSS to improve above-the-fold rendering.', 'aqualuxe' );
    }
    
    // Check if preloading is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_preloading', true ) ) {
        $recommendations[] = __( 'Enable preloading to improve resource loading.', 'aqualuxe' );
    }
    
    return $recommendations;
}