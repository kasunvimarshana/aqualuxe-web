<?php
/**
 * AquaLuxe Performance Module - Critical CSS Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/Performance
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Generate critical CSS for a page
 *
 * @return string|WP_Error Critical CSS or error
 */
function aqualuxe_performance_generate_critical_css() {
    // Get home URL
    $home_url = home_url();
    
    // Get critical CSS from home page
    $response = wp_remote_get( $home_url );
    
    // Check for errors
    if ( is_wp_error( $response ) ) {
        return $response;
    }
    
    // Get response body
    $html = wp_remote_retrieve_body( $response );
    
    // Check if HTML is empty
    if ( empty( $html ) ) {
        return new WP_Error( 'empty_html', __( 'Empty HTML response.', 'aqualuxe' ) );
    }
    
    // Extract all CSS from the page
    $css = aqualuxe_performance_extract_css_from_html( $html );
    
    // Extract critical CSS
    $critical_css = aqualuxe_performance_extract_critical_css( $css );
    
    // Save critical CSS
    update_option( 'aqualuxe_performance_critical_css', $critical_css );
    
    return $critical_css;
}

/**
 * Extract CSS from HTML
 *
 * @param string $html HTML content
 * @return string CSS content
 */
function aqualuxe_performance_extract_css_from_html( $html ) {
    $css = '';
    
    // Create a new DOMDocument
    $doc = new DOMDocument();
    
    // Suppress errors for malformed HTML
    libxml_use_internal_errors( true );
    
    // Load HTML
    $doc->loadHTML( $html );
    
    // Clear errors
    libxml_clear_errors();
    
    // Get all style tags
    $styles = $doc->getElementsByTagName( 'style' );
    
    // Extract CSS from style tags
    foreach ( $styles as $style ) {
        $css .= $style->nodeValue . "\n";
    }
    
    // Get all link tags
    $links = $doc->getElementsByTagName( 'link' );
    
    // Extract CSS from link tags
    foreach ( $links as $link ) {
        // Check if it's a stylesheet
        if ( $link->hasAttribute( 'rel' ) && strtolower( $link->getAttribute( 'rel' ) ) === 'stylesheet' ) {
            // Get href attribute
            $href = $link->getAttribute( 'href' );
            
            // Skip if href is empty
            if ( empty( $href ) ) {
                continue;
            }
            
            // Make URL absolute if it's relative
            if ( strpos( $href, 'http' ) !== 0 ) {
                // Check if it starts with //
                if ( strpos( $href, '//' ) === 0 ) {
                    $href = 'https:' . $href;
                } elseif ( strpos( $href, '/' ) === 0 ) {
                    // Absolute path
                    $href = home_url( $href );
                } else {
                    // Relative path
                    $href = home_url( '/' . $href );
                }
            }
            
            // Get CSS content
            $response = wp_remote_get( $href );
            
            // Check for errors
            if ( ! is_wp_error( $response ) ) {
                // Get response body
                $css_content = wp_remote_retrieve_body( $response );
                
                // Add to CSS
                $css .= $css_content . "\n";
            }
        }
    }
    
    return $css;
}

/**
 * Extract critical CSS from full CSS
 *
 * @param string $css Full CSS content
 * @return string Critical CSS
 */
function aqualuxe_performance_extract_critical_css( $css ) {
    // Define critical selectors (above the fold)
    $critical_selectors = array(
        'body',
        'html',
        'header',
        '.site-header',
        '.main-navigation',
        '.menu',
        '.hero',
        '.banner',
        '.slider',
        '.carousel',
        '.site-branding',
        '.logo',
        'h1',
        'h2',
        '.entry-title',
        '.page-title',
        '.container',
        '.wrapper',
        '.content-area',
        '.site-content',
        '.main-content',
        '.entry-content p:first-child',
        '.entry-content h2:first-child',
        '.entry-content img:first-child',
        '.widget-area',
        '.sidebar',
        '.primary-sidebar',
        '.button',
        '.btn',
        '.cta',
        'a.button',
        'a.btn',
        'a.cta',
        '.nav-menu',
        '.nav-links',
        '.pagination',
        '.post-navigation',
        '.site-navigation',
        '.main-navigation',
        '.menu-toggle',
        '.search-form',
        '.search-field',
        '.search-submit',
        '.site-search',
        '.mobile-menu',
        '.mobile-nav',
        '.navbar',
        '.navbar-toggle',
        '.navbar-header',
        '.navbar-brand',
        '.navbar-nav',
        '.nav-collapse',
        '.collapse',
        '.dropdown',
        '.dropdown-menu',
        '.dropdown-toggle',
        '.dropdown-content',
        '.dropdown-header',
        '.dropdown-item',
        '.dropdown-divider',
        '.dropdown-footer',
        '.dropdown-submenu',
        '.dropdown-toggle',
        '.dropdown-menu-right',
        '.dropdown-menu-left',
        '.dropdown-backdrop',
        '.open',
        '.active',
        '.current',
        '.current-menu-item',
        '.current-page-item',
        '.current-menu-ancestor',
        '.current-page-ancestor',
        '.current-menu-parent',
        '.current-page-parent',
        '.current_page_item',
        '.current_page_parent',
        '.current_page_ancestor',
        '.menu-item',
        '.menu-item-has-children',
        '.sub-menu',
        '.children',
        '.page_item',
        '.page_item_has_children',
    );
    
    // Initialize critical CSS
    $critical_css = '';
    
    // Parse CSS
    $parsed_css = aqualuxe_performance_parse_css( $css );
    
    // Extract critical CSS
    foreach ( $parsed_css as $rule ) {
        // Check if selector is critical
        $is_critical = false;
        
        foreach ( $critical_selectors as $critical_selector ) {
            if ( strpos( $rule['selector'], $critical_selector ) !== false ) {
                $is_critical = true;
                break;
            }
        }
        
        // Add to critical CSS if it's critical
        if ( $is_critical ) {
            $critical_css .= $rule['selector'] . '{' . $rule['declaration'] . '}';
        }
    }
    
    // Minify critical CSS
    $critical_css = aqualuxe_performance_minify_css_string( $critical_css );
    
    return $critical_css;
}

/**
 * Parse CSS into rules
 *
 * @param string $css CSS content
 * @return array Parsed CSS rules
 */
function aqualuxe_performance_parse_css( $css ) {
    // Remove comments
    $css = preg_replace( '/\/\*.*?\*\//s', '', $css );
    
    // Parse CSS
    $rules = array();
    $matches = array();
    
    // Match selectors and declarations
    preg_match_all( '/([^{]+){([^}]+)}/s', $css, $matches, PREG_SET_ORDER );
    
    // Process matches
    foreach ( $matches as $match ) {
        // Skip if match is empty
        if ( empty( $match[1] ) || empty( $match[2] ) ) {
            continue;
        }
        
        // Add rule
        $rules[] = array(
            'selector' => trim( $match[1] ),
            'declaration' => trim( $match[2] ),
        );
    }
    
    return $rules;
}

/**
 * Minify CSS string
 *
 * @param string $css CSS content
 * @return string Minified CSS
 */
function aqualuxe_performance_minify_css_string( $css ) {
    // Remove comments
    $css = preg_replace( '/\/\*.*?\*\//s', '', $css );
    
    // Remove whitespace
    $css = preg_replace( '/\s+/', ' ', $css );
    
    // Remove spaces before and after brackets
    $css = preg_replace( '/\s*{\s*/', '{', $css );
    $css = preg_replace( '/\s*}\s*/', '}', $css );
    
    // Remove spaces before and after colons
    $css = preg_replace( '/\s*:\s*/', ':', $css );
    
    // Remove spaces before and after semicolons
    $css = preg_replace( '/\s*;\s*/', ';', $css );
    
    // Remove spaces before and after commas
    $css = preg_replace( '/\s*,\s*/', ',', $css );
    
    // Remove trailing semicolons
    $css = preg_replace( '/;}/', '}', $css );
    
    // Remove leading and trailing whitespace
    $css = trim( $css );
    
    return $css;
}

/**
 * Add critical CSS to head
 *
 * @return void
 */
function aqualuxe_performance_add_critical_css() {
    // Get critical CSS
    $critical_css = get_option( 'aqualuxe_performance_critical_css', '' );
    
    // Check if critical CSS is empty
    if ( empty( $critical_css ) ) {
        return;
    }
    
    // Output critical CSS
    echo '<style id="aqualuxe-critical-css">' . $critical_css . '</style>';
}

/**
 * Defer non-critical CSS
 *
 * @param string $tag The link tag for the enqueued style
 * @param string $handle The style's registered handle
 * @param string $href The stylesheet's source URL
 * @param string $media The stylesheet's media attribute
 * @return string Modified link tag
 */
function aqualuxe_performance_defer_non_critical_css( $tag, $handle, $href, $media ) {
    // Skip if it's a critical stylesheet
    if ( in_array( $handle, array( 'aqualuxe-critical-css' ), true ) ) {
        return $tag;
    }
    
    // Skip if critical CSS is not enabled
    if ( ! get_option( 'aqualuxe_performance_enable_critical_css', true ) ) {
        return $tag;
    }
    
    // Skip if critical CSS is empty
    $critical_css = get_option( 'aqualuxe_performance_critical_css', '' );
    if ( empty( $critical_css ) ) {
        return $tag;
    }
    
    // Add preload attribute
    $tag = str_replace( "rel='stylesheet'", "rel='preload' as='style' onload=&quot;this.onload=null;this.rel='stylesheet'&quot;", $tag );
    
    // Add noscript fallback
    $tag .= "<noscript><link rel='stylesheet' href='" . esc_url( $href ) . "' media='" . esc_attr( $media ) . "'></noscript>";
    
    return $tag;
}