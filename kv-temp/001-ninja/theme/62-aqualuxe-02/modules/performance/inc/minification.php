<?php
/**
 * AquaLuxe Performance Minification Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/Performance
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Minify HTML
 *
 * @return void
 */
function aqualuxe_performance_minify_html() {
    // Check if minification is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_minification', true ) ) {
        return;
    }
    
    // Check if HTML minification is enabled
    if ( ! aqualuxe_performance_get_option( 'minify_html', true ) ) {
        return;
    }
    
    // Start output buffering
    ob_start( 'aqualuxe_performance_minify_html_callback' );
}

/**
 * Minify HTML callback
 *
 * @param string $buffer Buffer
 * @return string
 */
function aqualuxe_performance_minify_html_callback( $buffer ) {
    // Check if buffer is empty
    if ( empty( $buffer ) ) {
        return $buffer;
    }
    
    // Check if buffer is valid HTML
    if ( strpos( $buffer, '<html' ) === false ) {
        return $buffer;
    }
    
    // Minify HTML
    $buffer = aqualuxe_performance_do_minify_html( $buffer );
    
    return $buffer;
}

/**
 * Do minify HTML
 *
 * @param string $html HTML
 * @return string
 */
function aqualuxe_performance_do_minify_html( $html ) {
    // Remove comments
    $html = preg_replace( '/<!--(?!<!)[^\[>].*?-->/s', '', $html );
    
    // Remove whitespace
    $html = preg_replace( '/\s+/', ' ', $html );
    
    // Remove whitespace between tags
    $html = preg_replace( '/>\s+</', '><', $html );
    
    // Remove whitespace at the beginning of tags
    $html = preg_replace( '/\s+>/', '>', $html );
    
    // Remove whitespace at the end of tags
    $html = preg_replace( '/<\s+/', '<', $html );
    
    // Remove line breaks
    $html = preg_replace( '/\r\n|\r|\n/', '', $html );
    
    return $html;
}

/**
 * Minify CSS
 *
 * @param string $css CSS
 * @return string
 */
function aqualuxe_performance_minify_css( $css ) {
    // Check if CSS is empty
    if ( empty( $css ) ) {
        return $css;
    }
    
    // Remove comments
    $css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
    
    // Remove whitespace
    $css = preg_replace( '/\s+/', ' ', $css );
    
    // Remove whitespace before and after special characters
    $css = preg_replace( '/\s*({|}|,|:|;)\s*/', '$1', $css );
    
    // Remove unnecessary semicolons
    $css = preg_replace( '/;}/', '}', $css );
    
    // Remove line breaks
    $css = preg_replace( '/\r\n|\r|\n/', '', $css );
    
    return $css;
}

/**
 * Minify JavaScript
 *
 * @param string $js JavaScript
 * @return string
 */
function aqualuxe_performance_minify_js( $js ) {
    // Check if JavaScript is empty
    if ( empty( $js ) ) {
        return $js;
    }
    
    // Remove comments
    $js = preg_replace( '/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/', '', $js );
    
    // Remove whitespace
    $js = preg_replace( '/\s+/', ' ', $js );
    
    // Remove whitespace before and after special characters
    $js = preg_replace( '/\s*({|}|,|:|;|=|\+|\-|\*|\/|\?|\||\&|\|)\s*/', '$1', $js );
    
    // Remove line breaks
    $js = preg_replace( '/\r\n|\r|\n/', '', $js );
    
    return $js;
}

/**
 * Minify CSS source
 *
 * @param string $src Source URL
 * @param string $handle Handle
 * @return string
 */
function aqualuxe_performance_minify_css_src( $src, $handle ) {
    // Check if minification is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_minification', true ) ) {
        return $src;
    }
    
    // Check if CSS minification is enabled
    if ( ! aqualuxe_performance_get_option( 'minify_css', true ) ) {
        return $src;
    }
    
    // Check if source is valid
    if ( empty( $src ) ) {
        return $src;
    }
    
    // Check if source is CSS
    if ( ! aqualuxe_performance_is_css( $src ) ) {
        return $src;
    }
    
    // Check if source is external
    if ( aqualuxe_performance_is_external( $src ) ) {
        return $src;
    }
    
    // Check if source is already minified
    if ( strpos( $src, '.min.css' ) !== false ) {
        return $src;
    }
    
    // Get file path
    $file_path = aqualuxe_performance_get_file_path( $src );
    
    // Check if file exists
    if ( ! file_exists( $file_path ) ) {
        return $src;
    }
    
    // Get file contents
    $css = file_get_contents( $file_path );
    
    // Check if file contents are valid
    if ( ! $css ) {
        return $src;
    }
    
    // Minify CSS
    $minified = aqualuxe_performance_minify_css( $css );
    
    // Get cache directory
    $cache_dir = aqualuxe_performance_get_cache_dir();
    
    // Get file name
    $file_name = aqualuxe_performance_get_file_base_name( $src );
    
    // Get file hash
    $file_hash = md5( $minified );
    
    // Set cache file path
    $cache_file = $cache_dir . '/' . $file_name . '-' . $file_hash . '.min.css';
    
    // Save minified CSS
    file_put_contents( $cache_file, $minified );
    
    // Get cache URL
    $cache_url = aqualuxe_performance_get_cache_url();
    
    // Set minified URL
    $minified_url = $cache_url . '/' . basename( $cache_file );
    
    return $minified_url;
}

/**
 * Minify JavaScript source
 *
 * @param string $src Source URL
 * @param string $handle Handle
 * @return string
 */
function aqualuxe_performance_minify_js_src( $src, $handle ) {
    // Check if minification is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_minification', true ) ) {
        return $src;
    }
    
    // Check if JavaScript minification is enabled
    if ( ! aqualuxe_performance_get_option( 'minify_js', true ) ) {
        return $src;
    }
    
    // Check if source is valid
    if ( empty( $src ) ) {
        return $src;
    }
    
    // Check if source is JavaScript
    if ( ! aqualuxe_performance_is_js( $src ) ) {
        return $src;
    }
    
    // Check if source is external
    if ( aqualuxe_performance_is_external( $src ) ) {
        return $src;
    }
    
    // Check if source is already minified
    if ( strpos( $src, '.min.js' ) !== false ) {
        return $src;
    }
    
    // Get file path
    $file_path = aqualuxe_performance_get_file_path( $src );
    
    // Check if file exists
    if ( ! file_exists( $file_path ) ) {
        return $src;
    }
    
    // Get file contents
    $js = file_get_contents( $file_path );
    
    // Check if file contents are valid
    if ( ! $js ) {
        return $src;
    }
    
    // Minify JavaScript
    $minified = aqualuxe_performance_minify_js( $js );
    
    // Get cache directory
    $cache_dir = aqualuxe_performance_get_cache_dir();
    
    // Get file name
    $file_name = aqualuxe_performance_get_file_base_name( $src );
    
    // Get file hash
    $file_hash = md5( $minified );
    
    // Set cache file path
    $cache_file = $cache_dir . '/' . $file_name . '-' . $file_hash . '.min.js';
    
    // Save minified JavaScript
    file_put_contents( $cache_file, $minified );
    
    // Get cache URL
    $cache_url = aqualuxe_performance_get_cache_url();
    
    // Set minified URL
    $minified_url = $cache_url . '/' . basename( $cache_file );
    
    return $minified_url;
}

/**
 * Combine CSS
 *
 * @return void
 */
function aqualuxe_performance_combine_css() {
    // Check if minification is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_minification', true ) ) {
        return;
    }
    
    // Check if CSS combining is enabled
    if ( ! aqualuxe_performance_get_option( 'combine_css', true ) ) {
        return;
    }
    
    // Get all enqueued styles
    global $wp_styles;
    
    // Check if styles are available
    if ( ! $wp_styles ) {
        return;
    }
    
    // Get all enqueued styles
    $styles = $wp_styles->queue;
    
    // Check if styles are available
    if ( empty( $styles ) ) {
        return;
    }
    
    // Initialize combined CSS
    $combined_css = '';
    
    // Initialize handles to dequeue
    $handles_to_dequeue = array();
    
    // Loop through styles
    foreach ( $styles as $handle ) {
        // Get style
        $style = $wp_styles->registered[ $handle ];
        
        // Check if style has source
        if ( ! isset( $style->src ) || empty( $style->src ) ) {
            continue;
        }
        
        // Get source URL
        $src = $style->src;
        
        // Add domain if source is relative
        if ( strpos( $src, '//' ) === 0 ) {
            $src = 'https:' . $src;
        } elseif ( strpos( $src, '/' ) === 0 ) {
            $src = site_url( $src );
        }
        
        // Check if source is CSS
        if ( ! aqualuxe_performance_is_css( $src ) ) {
            continue;
        }
        
        // Check if source is external
        if ( aqualuxe_performance_is_external( $src ) ) {
            continue;
        }
        
        // Get file path
        $file_path = aqualuxe_performance_get_file_path( $src );
        
        // Check if file exists
        if ( ! file_exists( $file_path ) ) {
            continue;
        }
        
        // Get file contents
        $css = file_get_contents( $file_path );
        
        // Check if file contents are valid
        if ( ! $css ) {
            continue;
        }
        
        // Minify CSS
        $css = aqualuxe_performance_minify_css( $css );
        
        // Add CSS to combined CSS
        $combined_css .= "/* {$handle} */\n{$css}\n\n";
        
        // Add handle to dequeue
        $handles_to_dequeue[] = $handle;
    }
    
    // Check if combined CSS is empty
    if ( empty( $combined_css ) ) {
        return;
    }
    
    // Get cache directory
    $cache_dir = aqualuxe_performance_get_cache_dir();
    
    // Get file hash
    $file_hash = md5( $combined_css );
    
    // Set cache file path
    $cache_file = $cache_dir . '/combined-' . $file_hash . '.css';
    
    // Save combined CSS
    file_put_contents( $cache_file, $combined_css );
    
    // Get cache URL
    $cache_url = aqualuxe_performance_get_cache_url();
    
    // Set combined URL
    $combined_url = $cache_url . '/' . basename( $cache_file );
    
    // Dequeue styles
    foreach ( $handles_to_dequeue as $handle ) {
        wp_dequeue_style( $handle );
    }
    
    // Enqueue combined CSS
    wp_enqueue_style( 'aqualuxe-combined-css', $combined_url, array(), null );
}

/**
 * Combine JavaScript
 *
 * @return void
 */
function aqualuxe_performance_combine_js() {
    // Check if minification is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_minification', true ) ) {
        return;
    }
    
    // Check if JavaScript combining is enabled
    if ( ! aqualuxe_performance_get_option( 'combine_js', true ) ) {
        return;
    }
    
    // Get all enqueued scripts
    global $wp_scripts;
    
    // Check if scripts are available
    if ( ! $wp_scripts ) {
        return;
    }
    
    // Get all enqueued scripts
    $scripts = $wp_scripts->queue;
    
    // Check if scripts are available
    if ( empty( $scripts ) ) {
        return;
    }
    
    // Initialize combined JavaScript
    $combined_js = '';
    
    // Initialize handles to dequeue
    $handles_to_dequeue = array();
    
    // Loop through scripts
    foreach ( $scripts as $handle ) {
        // Get script
        $script = $wp_scripts->registered[ $handle ];
        
        // Check if script has source
        if ( ! isset( $script->src ) || empty( $script->src ) ) {
            continue;
        }
        
        // Get source URL
        $src = $script->src;
        
        // Add domain if source is relative
        if ( strpos( $src, '//' ) === 0 ) {
            $src = 'https:' . $src;
        } elseif ( strpos( $src, '/' ) === 0 ) {
            $src = site_url( $src );
        }
        
        // Check if source is JavaScript
        if ( ! aqualuxe_performance_is_js( $src ) ) {
            continue;
        }
        
        // Check if source is external
        if ( aqualuxe_performance_is_external( $src ) ) {
            continue;
        }
        
        // Get file path
        $file_path = aqualuxe_performance_get_file_path( $src );
        
        // Check if file exists
        if ( ! file_exists( $file_path ) ) {
            continue;
        }
        
        // Get file contents
        $js = file_get_contents( $file_path );
        
        // Check if file contents are valid
        if ( ! $js ) {
            continue;
        }
        
        // Minify JavaScript
        $js = aqualuxe_performance_minify_js( $js );
        
        // Add JavaScript to combined JavaScript
        $combined_js .= "/* {$handle} */\n{$js}\n\n";
        
        // Add handle to dequeue
        $handles_to_dequeue[] = $handle;
    }
    
    // Check if combined JavaScript is empty
    if ( empty( $combined_js ) ) {
        return;
    }
    
    // Get cache directory
    $cache_dir = aqualuxe_performance_get_cache_dir();
    
    // Get file hash
    $file_hash = md5( $combined_js );
    
    // Set cache file path
    $cache_file = $cache_dir . '/combined-' . $file_hash . '.js';
    
    // Save combined JavaScript
    file_put_contents( $cache_file, $combined_js );
    
    // Get cache URL
    $cache_url = aqualuxe_performance_get_cache_url();
    
    // Set combined URL
    $combined_url = $cache_url . '/' . basename( $cache_file );
    
    // Dequeue scripts
    foreach ( $handles_to_dequeue as $handle ) {
        wp_dequeue_script( $handle );
    }
    
    // Enqueue combined JavaScript
    wp_enqueue_script( 'aqualuxe-combined-js', $combined_url, array(), null, true );
}

/**
 * Minify inline CSS
 *
 * @param string $css CSS
 * @return string
 */
function aqualuxe_performance_minify_inline_css( $css ) {
    // Check if minification is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_minification', true ) ) {
        return $css;
    }
    
    // Check if CSS minification is enabled
    if ( ! aqualuxe_performance_get_option( 'minify_css', true ) ) {
        return $css;
    }
    
    // Minify CSS
    $css = aqualuxe_performance_minify_css( $css );
    
    return $css;
}

/**
 * Minify inline JavaScript
 *
 * @param string $js JavaScript
 * @return string
 */
function aqualuxe_performance_minify_inline_js( $js ) {
    // Check if minification is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_minification', true ) ) {
        return $js;
    }
    
    // Check if JavaScript minification is enabled
    if ( ! aqualuxe_performance_get_option( 'minify_js', true ) ) {
        return $js;
    }
    
    // Minify JavaScript
    $js = aqualuxe_performance_minify_js( $js );
    
    return $js;
}

/**
 * Minify style tag
 *
 * @param string $tag Style tag
 * @param string $handle Handle
 * @param string $href HREF
 * @param string $media Media
 * @return string
 */
function aqualuxe_performance_minify_style_tag( $tag, $handle, $href, $media ) {
    // Check if minification is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_minification', true ) ) {
        return $tag;
    }
    
    // Check if CSS minification is enabled
    if ( ! aqualuxe_performance_get_option( 'minify_css', true ) ) {
        return $tag;
    }
    
    // Check if tag is valid
    if ( empty( $tag ) ) {
        return $tag;
    }
    
    // Minify tag
    $tag = preg_replace( '/\s+/', ' ', $tag );
    $tag = preg_replace( '/\s*\/>\s*/', '/>', $tag );
    
    return $tag;
}

/**
 * Minify script tag
 *
 * @param string $tag Script tag
 * @param string $handle Handle
 * @param string $src Source
 * @return string
 */
function aqualuxe_performance_minify_script_tag( $tag, $handle, $src ) {
    // Check if minification is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_minification', true ) ) {
        return $tag;
    }
    
    // Check if JavaScript minification is enabled
    if ( ! aqualuxe_performance_get_option( 'minify_js', true ) ) {
        return $tag;
    }
    
    // Check if tag is valid
    if ( empty( $tag ) ) {
        return $tag;
    }
    
    // Minify tag
    $tag = preg_replace( '/\s+/', ' ', $tag );
    $tag = preg_replace( '/\s*><\/script>\s*/', '></script>', $tag );
    
    return $tag;
}

/**
 * Register minification hooks
 *
 * @return void
 */
function aqualuxe_performance_register_minification_hooks() {
    // Check if minification is enabled
    if ( ! aqualuxe_performance_get_option( 'enable_minification', true ) ) {
        return;
    }
    
    // HTML minification
    if ( aqualuxe_performance_get_option( 'minify_html', true ) ) {
        add_action( 'template_redirect', 'aqualuxe_performance_minify_html', 10 );
    }
    
    // CSS minification
    if ( aqualuxe_performance_get_option( 'minify_css', true ) ) {
        add_filter( 'style_loader_src', 'aqualuxe_performance_minify_css_src', 10, 2 );
        add_filter( 'style_loader_tag', 'aqualuxe_performance_minify_style_tag', 10, 4 );
        add_filter( 'wp_head', 'aqualuxe_performance_minify_inline_css', 10 );
    }
    
    // JavaScript minification
    if ( aqualuxe_performance_get_option( 'minify_js', true ) ) {
        add_filter( 'script_loader_src', 'aqualuxe_performance_minify_js_src', 10, 2 );
        add_filter( 'script_loader_tag', 'aqualuxe_performance_minify_script_tag', 10, 3 );
        add_filter( 'wp_head', 'aqualuxe_performance_minify_inline_js', 10 );
        add_filter( 'wp_footer', 'aqualuxe_performance_minify_inline_js', 10 );
    }
    
    // CSS combining
    if ( aqualuxe_performance_get_option( 'combine_css', true ) ) {
        add_action( 'wp_enqueue_scripts', 'aqualuxe_performance_combine_css', 9999 );
    }
    
    // JavaScript combining
    if ( aqualuxe_performance_get_option( 'combine_js', true ) ) {
        add_action( 'wp_enqueue_scripts', 'aqualuxe_performance_combine_js', 9999 );
    }
}

/**
 * Get minification status
 *
 * @return array
 */
function aqualuxe_performance_get_minification_status() {
    // Get cache directory
    $cache_dir = aqualuxe_performance_get_cache_dir();
    
    // Get all files in cache directory
    $files = glob( $cache_dir . '/*' );
    
    // Check if files exist
    if ( ! $files ) {
        $files = array();
    }
    
    // Count CSS files
    $css_files = 0;
    $css_size = 0;
    
    // Count JavaScript files
    $js_files = 0;
    $js_size = 0;
    
    // Count combined files
    $combined_files = 0;
    $combined_size = 0;
    
    // Loop through files
    foreach ( $files as $file ) {
        // Skip .htaccess and index.php
        if ( basename( $file ) === '.htaccess' || basename( $file ) === 'index.php' ) {
            continue;
        }
        
        // Check if file is CSS
        if ( strpos( $file, '.css' ) !== false ) {
            // Check if file is combined
            if ( strpos( $file, 'combined-' ) !== false ) {
                $combined_files++;
                $combined_size += filesize( $file );
            } else {
                $css_files++;
                $css_size += filesize( $file );
            }
        }
        
        // Check if file is JavaScript
        if ( strpos( $file, '.js' ) !== false ) {
            // Check if file is combined
            if ( strpos( $file, 'combined-' ) !== false ) {
                $combined_files++;
                $combined_size += filesize( $file );
            } else {
                $js_files++;
                $js_size += filesize( $file );
            }
        }
    }
    
    // Build status
    $status = array(
        'enabled' => aqualuxe_performance_get_option( 'enable_minification', true ),
        'html' => aqualuxe_performance_get_option( 'minify_html', true ),
        'css' => aqualuxe_performance_get_option( 'minify_css', true ),
        'js' => aqualuxe_performance_get_option( 'minify_js', true ),
        'combine_css' => aqualuxe_performance_get_option( 'combine_css', true ),
        'combine_js' => aqualuxe_performance_get_option( 'combine_js', true ),
        'css_files' => $css_files,
        'css_size' => $css_size,
        'css_size_formatted' => aqualuxe_performance_format_memory( $css_size ),
        'js_files' => $js_files,
        'js_size' => $js_size,
        'js_size_formatted' => aqualuxe_performance_format_memory( $js_size ),
        'combined_files' => $combined_files,
        'combined_size' => $combined_size,
        'combined_size_formatted' => aqualuxe_performance_format_memory( $combined_size ),
    );
    
    return $status;
}

/**
 * Get minification status HTML
 *
 * @return string
 */
function aqualuxe_performance_get_minification_status_html() {
    // Get minification status
    $status = aqualuxe_performance_get_minification_status();
    
    // Build HTML
    $html = '<div class="aqualuxe-minification-status">';
    
    // Add status
    $html .= '<div class="aqualuxe-minification-status-section">';
    $html .= '<h3>' . __( 'Minification Status', 'aqualuxe' ) . '</h3>';
    $html .= '<table class="aqualuxe-minification-status-table">';
    $html .= '<tr>';
    $html .= '<th>' . __( 'Enabled', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . ( $status['enabled'] ? __( 'Yes', 'aqualuxe' ) : __( 'No', 'aqualuxe' ) ) . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'HTML Minification', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . ( $status['html'] ? __( 'Enabled', 'aqualuxe' ) : __( 'Disabled', 'aqualuxe' ) ) . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'CSS Minification', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . ( $status['css'] ? __( 'Enabled', 'aqualuxe' ) : __( 'Disabled', 'aqualuxe' ) ) . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'JavaScript Minification', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . ( $status['js'] ? __( 'Enabled', 'aqualuxe' ) : __( 'Disabled', 'aqualuxe' ) ) . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'CSS Combining', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . ( $status['combine_css'] ? __( 'Enabled', 'aqualuxe' ) : __( 'Disabled', 'aqualuxe' ) ) . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'JavaScript Combining', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . ( $status['combine_js'] ? __( 'Enabled', 'aqualuxe' ) : __( 'Disabled', 'aqualuxe' ) ) . '</td>';
    $html .= '</tr>';
    $html .= '</table>';
    $html .= '</div>';
    
    // Add files
    $html .= '<div class="aqualuxe-minification-status-section">';
    $html .= '<h3>' . __( 'Minified Files', 'aqualuxe' ) . '</h3>';
    $html .= '<table class="aqualuxe-minification-status-table">';
    $html .= '<tr>';
    $html .= '<th>' . __( 'CSS Files', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . $status['css_files'] . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'CSS Size', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . $status['css_size_formatted'] . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'JavaScript Files', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . $status['js_files'] . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'JavaScript Size', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . $status['js_size_formatted'] . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'Combined Files', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . $status['combined_files'] . '</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<th>' . __( 'Combined Size', 'aqualuxe' ) . '</th>';
    $html .= '<td>' . $status['combined_size_formatted'] . '</td>';
    $html .= '</tr>';
    $html .= '</table>';
    $html .= '</div>';
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Display minification status
 *
 * @return void
 */
function aqualuxe_performance_display_minification_status() {
    // Get minification status HTML
    $html = aqualuxe_performance_get_minification_status_html();
    
    // Display minification status
    echo $html;
}