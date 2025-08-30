<?php
/**
 * Minification Implementation
 *
 * @package AquaLuxe
 * @subpackage Modules/Performance
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Enqueue minified assets
 *
 * @return void
 */
function aqualuxe_performance_enqueue_minified_assets() {
    if ( ! aqualuxe_is_minification_enabled() || is_admin() ) {
        return;
    }

    // Use minified versions of assets in production
    if ( ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ) {
        // Replace non-minified assets with minified versions
        global $wp_scripts, $wp_styles;

        // Scripts
        if ( $wp_scripts ) {
            foreach ( $wp_scripts->registered as $handle => $script ) {
                if ( strpos( $script->src, '.min.js' ) === false && strpos( $script->src, AQUALUXE_THEME_URI ) !== false ) {
                    $min_src = str_replace( '.js', '.min.js', $script->src );
                    if ( file_exists( str_replace( AQUALUXE_THEME_URI, AQUALUXE_THEME_DIR, $min_src ) ) ) {
                        $script->src = $min_src;
                    }
                }
            }
        }

        // Styles
        if ( $wp_styles ) {
            foreach ( $wp_styles->registered as $handle => $style ) {
                if ( strpos( $style->src, '.min.css' ) === false && strpos( $style->src, AQUALUXE_THEME_URI ) !== false ) {
                    $min_src = str_replace( '.css', '.min.css', $style->src );
                    if ( file_exists( str_replace( AQUALUXE_THEME_URI, AQUALUXE_THEME_DIR, $min_src ) ) ) {
                        $style->src = $min_src;
                    }
                }
            }
        }
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_performance_enqueue_minified_assets', 999 );

/**
 * Remove version query string from assets
 *
 * @param string $src Asset URL.
 * @param string $handle Asset handle.
 * @return string
 */
function aqualuxe_performance_remove_version_query_string( $src, $handle ) {
    if ( ! aqualuxe_is_minification_enabled() || is_admin() ) {
        return $src;
    }

    // Skip for essential scripts that require versioning
    $skip_handles = array( 'jquery', 'jquery-core', 'jquery-migrate' );
    if ( in_array( $handle, $skip_handles, true ) ) {
        return $src;
    }

    if ( strpos( $src, 'ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    
    return $src;
}
add_filter( 'style_loader_src', 'aqualuxe_performance_remove_version_query_string', 10, 2 );
add_filter( 'script_loader_src', 'aqualuxe_performance_remove_version_query_string', 10, 2 );

/**
 * Minify HTML output
 *
 * @param string $buffer HTML content.
 * @return string
 */
function aqualuxe_performance_minify_html( $buffer ) {
    if ( ! aqualuxe_is_minification_enabled() || is_admin() || is_feed() ) {
        return $buffer;
    }

    // Skip if buffer is empty
    if ( empty( $buffer ) ) {
        return $buffer;
    }

    // Skip minification for specific pages or conditions
    if ( is_customize_preview() || is_user_logged_in() ) {
        return $buffer;
    }

    // Preserve pre, textarea, script and style tags
    $tags_to_preserve = array( 'pre', 'textarea', 'script', 'style' );
    $placeholders = array();

    foreach ( $tags_to_preserve as $tag ) {
        $pattern = '/<' . $tag . '.*?>.*?<\/' . $tag . '>/is';
        preg_match_all( $pattern, $buffer, $matches );

        if ( ! empty( $matches[0] ) ) {
            foreach ( $matches[0] as $i => $match ) {
                $placeholder = '<!--' . $tag . '-placeholder-' . $i . '-->';
                $placeholders[ $placeholder ] = $match;
                $buffer = str_replace( $match, $placeholder, $buffer );
            }
        }
    }

    // Remove comments (but keep conditional comments)
    $buffer = preg_replace( '/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $buffer );

    // Remove whitespace
    $buffer = preg_replace( '/\s+/', ' ', $buffer );
    $buffer = preg_replace( '/\s*<\s*/', '<', $buffer );
    $buffer = preg_replace( '/\s*>\s*/', '>', $buffer );
    $buffer = preg_replace( '/\s*=\s*/', '=', $buffer );

    // Restore preserved tags
    foreach ( $placeholders as $placeholder => $original ) {
        $buffer = str_replace( $placeholder, $original, $buffer );
    }

    return $buffer;
}

/**
 * Start output buffering for HTML minification
 *
 * @return void
 */
function aqualuxe_performance_start_html_minification() {
    if ( ! aqualuxe_is_minification_enabled() || is_admin() || is_feed() ) {
        return;
    }

    // Skip for specific pages or conditions
    if ( is_customize_preview() || is_user_logged_in() ) {
        return;
    }

    ob_start( 'aqualuxe_performance_minify_html' );
}
add_action( 'template_redirect', 'aqualuxe_performance_start_html_minification', 1 );

/**
 * End output buffering for HTML minification
 *
 * @return void
 */
function aqualuxe_performance_end_html_minification() {
    if ( ! aqualuxe_is_minification_enabled() || is_admin() || is_feed() ) {
        return;
    }

    // Skip for specific pages or conditions
    if ( is_customize_preview() || is_user_logged_in() ) {
        return;
    }

    if ( ob_get_length() ) {
        ob_end_flush();
    }
}
add_action( 'shutdown', 'aqualuxe_performance_end_html_minification', 0 );

/**
 * Minify inline CSS
 *
 * @param string $css CSS content.
 * @return string
 */
function aqualuxe_performance_minify_css( $css ) {
    if ( ! aqualuxe_is_minification_enabled() || is_admin() ) {
        return $css;
    }

    // Skip if CSS is empty
    if ( empty( $css ) ) {
        return $css;
    }

    // Remove comments
    $css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );

    // Remove whitespace
    $css = preg_replace( '/\s+/', ' ', $css );
    $css = preg_replace( '/\s*{\s*/', '{', $css );
    $css = preg_replace( '/\s*}\s*/', '}', $css );
    $css = preg_replace( '/\s*:\s*/', ':', $css );
    $css = preg_replace( '/\s*;\s*/', ';', $css );
    $css = preg_replace( '/\s*,\s*/', ',', $css );

    // Remove trailing semicolon
    $css = preg_replace( '/;}/', '}', $css );

    return trim( $css );
}

/**
 * Minify inline JavaScript
 *
 * @param string $js JavaScript content.
 * @return string
 */
function aqualuxe_performance_minify_js( $js ) {
    if ( ! aqualuxe_is_minification_enabled() || is_admin() ) {
        return $js;
    }

    // Skip if JS is empty
    if ( empty( $js ) ) {
        return $js;
    }

    // Skip if already minified
    if ( strpos( $js, '.min.js' ) !== false ) {
        return $js;
    }

    // Basic minification (remove comments and whitespace)
    $js = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $js );
    $js = preg_replace( '/\/\/.*?(\r\n|\n|$)/', '', $js );
    $js = preg_replace( '/\s+/', ' ', $js );

    return trim( $js );
}

/**
 * Minify inline CSS in style tags
 *
 * @param string $content Content.
 * @return string
 */
function aqualuxe_performance_minify_inline_styles( $content ) {
    if ( ! aqualuxe_is_minification_enabled() || is_admin() || is_feed() ) {
        return $content;
    }

    // Skip for specific pages or conditions
    if ( is_customize_preview() || is_user_logged_in() ) {
        return $content;
    }

    // Find and minify inline styles
    $content = preg_replace_callback(
        '/<style[^>]*>(.*?)<\/style>/is',
        function( $matches ) {
            return '<style>' . aqualuxe_performance_minify_css( $matches[1] ) . '</style>';
        },
        $content
    );

    return $content;
}
add_filter( 'the_content', 'aqualuxe_performance_minify_inline_styles', 100 );

/**
 * Minify inline JavaScript in script tags
 *
 * @param string $content Content.
 * @return string
 */
function aqualuxe_performance_minify_inline_scripts( $content ) {
    if ( ! aqualuxe_is_minification_enabled() || is_admin() || is_feed() ) {
        return $content;
    }

    // Skip for specific pages or conditions
    if ( is_customize_preview() || is_user_logged_in() ) {
        return $content;
    }

    // Find and minify inline scripts
    $content = preg_replace_callback(
        '/<script[^>]*>(.*?)<\/script>/is',
        function( $matches ) {
            // Skip if has src attribute
            if ( strpos( $matches[0], 'src=' ) !== false ) {
                return $matches[0];
            }
            
            return '<script>' . aqualuxe_performance_minify_js( $matches[1] ) . '</script>';
        },
        $content
    );

    return $content;
}
add_filter( 'the_content', 'aqualuxe_performance_minify_inline_scripts', 100 );

/**
 * Add defer attribute to scripts
 *
 * @param string $tag Script tag.
 * @param string $handle Script handle.
 * @param string $src Script source.
 * @return string
 */
function aqualuxe_performance_defer_scripts( $tag, $handle, $src ) {
    if ( ! aqualuxe_is_js_defer_enabled() || is_admin() ) {
        return $tag;
    }

    // Skip jQuery and other essential scripts
    $skip_handles = array( 'jquery', 'jquery-core', 'jquery-migrate' );
    if ( in_array( $handle, $skip_handles, true ) ) {
        return $tag;
    }

    // Skip scripts that already have defer or async attribute
    if ( strpos( $tag, 'defer' ) !== false || strpos( $tag, 'async' ) !== false ) {
        return $tag;
    }

    // Add defer attribute
    $tag = str_replace( ' src', ' defer src', $tag );

    return $tag;
}
add_filter( 'script_loader_tag', 'aqualuxe_performance_defer_scripts', 10, 3 );

/**
 * Add async attribute to CSS
 *
 * @param string $html HTML.
 * @param string $handle Handle.
 * @param string $href HREF.
 * @param string $media Media.
 * @return string
 */
function aqualuxe_performance_async_css( $html, $handle, $href, $media ) {
    if ( ! aqualuxe_is_css_async_enabled() || is_admin() ) {
        return $html;
    }

    // Skip critical CSS
    if ( $handle === 'aqualuxe-critical-css' ) {
        return $html;
    }

    // Skip essential styles
    $skip_handles = array( 'wp-block-library', 'wp-admin', 'admin-bar' );
    if ( in_array( $handle, $skip_handles, true ) ) {
        return $html;
    }

    // Add async attribute
    $html = str_replace( "media='$media'", "media='print' onload=&quot;this.media='$media'&quot;", $html );

    return $html;
}
add_filter( 'style_loader_tag', 'aqualuxe_performance_async_css', 10, 4 );

/**
 * Combine and minify CSS files
 *
 * @return void
 */
function aqualuxe_performance_combine_css() {
    if ( ! aqualuxe_is_minification_enabled() || is_admin() || is_customize_preview() || is_user_logged_in() ) {
        return;
    }

    // This is a simplified example. In a real theme, you would use a proper build process.
    // For now, we'll just create a combined CSS file if it doesn't exist.
    $combined_css_file = AQUALUXE_THEME_DIR . 'assets/dist/css/combined.min.css';
    $combined_css_url = AQUALUXE_ASSETS_URI . 'css/combined.min.css';

    // Check if combined CSS file exists and is newer than source files
    $css_files = array(
        AQUALUXE_THEME_DIR . 'assets/dist/css/style.css',
        AQUALUXE_THEME_DIR . 'assets/dist/css/woocommerce.css',
        AQUALUXE_THEME_DIR . 'modules/dark-mode/assets/css/dark-mode.css',
        AQUALUXE_THEME_DIR . 'modules/multilingual/assets/css/language-switcher.css',
    );

    $needs_update = false;

    if ( ! file_exists( $combined_css_file ) ) {
        $needs_update = true;
    } else {
        $combined_time = filemtime( $combined_css_file );
        
        foreach ( $css_files as $css_file ) {
            if ( file_exists( $css_file ) && filemtime( $css_file ) > $combined_time ) {
                $needs_update = true;
                break;
            }
        }
    }

    if ( $needs_update ) {
        $combined_css = '';
        
        foreach ( $css_files as $css_file ) {
            if ( file_exists( $css_file ) ) {
                $css = file_get_contents( $css_file );
                $combined_css .= aqualuxe_performance_minify_css( $css ) . "\n";
            }
        }
        
        // Create directory if it doesn't exist
        $dir = dirname( $combined_css_file );
        if ( ! file_exists( $dir ) ) {
            wp_mkdir_p( $dir );
        }
        
        // Save combined CSS
        file_put_contents( $combined_css_file, $combined_css );
    }

    // Deregister individual CSS files and register combined CSS
    global $wp_styles;
    
    if ( $wp_styles ) {
        foreach ( $wp_styles->registered as $handle => $style ) {
            // Skip essential styles
            $skip_handles = array( 'wp-block-library', 'wp-admin', 'admin-bar' );
            if ( in_array( $handle, $skip_handles, true ) ) {
                continue;
            }
            
            // Skip external styles
            if ( strpos( $style->src, AQUALUXE_THEME_URI ) === false ) {
                continue;
            }
            
            wp_deregister_style( $handle );
        }
        
        // Register combined CSS
        wp_register_style( 'aqualuxe-combined', $combined_css_url, array(), AQUALUXE_VERSION );
        wp_enqueue_style( 'aqualuxe-combined' );
    }
}
// Uncomment the following line to enable CSS combining (not recommended for development)
// add_action( 'wp_enqueue_scripts', 'aqualuxe_performance_combine_css', 9999 );

/**
 * Combine and minify JavaScript files
 *
 * @return void
 */
function aqualuxe_performance_combine_js() {
    if ( ! aqualuxe_is_minification_enabled() || is_admin() || is_customize_preview() || is_user_logged_in() ) {
        return;
    }

    // This is a simplified example. In a real theme, you would use a proper build process.
    // For now, we'll just create a combined JS file if it doesn't exist.
    $combined_js_file = AQUALUXE_THEME_DIR . 'assets/dist/js/combined.min.js';
    $combined_js_url = AQUALUXE_ASSETS_URI . 'js/combined.min.js';

    // Check if combined JS file exists and is newer than source files
    $js_files = array(
        AQUALUXE_THEME_DIR . 'assets/dist/js/navigation.js',
        AQUALUXE_THEME_DIR . 'assets/dist/js/skip-link-focus-fix.js',
        AQUALUXE_THEME_DIR . 'modules/dark-mode/assets/js/dark-mode.js',
        AQUALUXE_THEME_DIR . 'modules/multilingual/assets/js/language-switcher.js',
        AQUALUXE_THEME_DIR . 'modules/performance/assets/js/lazy-loading.js',
    );

    $needs_update = false;

    if ( ! file_exists( $combined_js_file ) ) {
        $needs_update = true;
    } else {
        $combined_time = filemtime( $combined_js_file );
        
        foreach ( $js_files as $js_file ) {
            if ( file_exists( $js_file ) && filemtime( $js_file ) > $combined_time ) {
                $needs_update = true;
                break;
            }
        }
    }

    if ( $needs_update ) {
        $combined_js = '';
        
        foreach ( $js_files as $js_file ) {
            if ( file_exists( $js_file ) ) {
                $js = file_get_contents( $js_file );
                $combined_js .= aqualuxe_performance_minify_js( $js ) . ";\n";
            }
        }
        
        // Create directory if it doesn't exist
        $dir = dirname( $combined_js_file );
        if ( ! file_exists( $dir ) ) {
            wp_mkdir_p( $dir );
        }
        
        // Save combined JS
        file_put_contents( $combined_js_file, $combined_js );
    }

    // Deregister individual JS files and register combined JS
    global $wp_scripts;
    
    if ( $wp_scripts ) {
        foreach ( $wp_scripts->registered as $handle => $script ) {
            // Skip jQuery and other essential scripts
            $skip_handles = array( 'jquery', 'jquery-core', 'jquery-migrate', 'wp-admin', 'admin-bar' );
            if ( in_array( $handle, $skip_handles, true ) ) {
                continue;
            }
            
            // Skip external scripts
            if ( strpos( $script->src, AQUALUXE_THEME_URI ) === false ) {
                continue;
            }
            
            wp_deregister_script( $handle );
        }
        
        // Register combined JS
        wp_register_script( 'aqualuxe-combined', $combined_js_url, array( 'jquery' ), AQUALUXE_VERSION, true );
        wp_enqueue_script( 'aqualuxe-combined' );
    }
}
// Uncomment the following line to enable JS combining (not recommended for development)
// add_action( 'wp_enqueue_scripts', 'aqualuxe_performance_combine_js', 9999 );