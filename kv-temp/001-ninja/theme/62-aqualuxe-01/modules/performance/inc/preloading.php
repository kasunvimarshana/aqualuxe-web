<?php
/**
 * AquaLuxe Performance Module - Preloading Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/Performance
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Add preload links to head
 *
 * @return void
 */
function aqualuxe_performance_add_preload_links() {
    // Skip if preloading is not enabled
    if ( ! get_option( 'aqualuxe_performance_enable_preloading', true ) ) {
        return;
    }
    
    // Preload fonts
    aqualuxe_performance_preload_fonts();
    
    // Preload assets
    aqualuxe_performance_preload_assets();
}

/**
 * Preload fonts
 *
 * @return void
 */
function aqualuxe_performance_preload_fonts() {
    // Get fonts to preload
    $fonts = get_option( 'aqualuxe_performance_preload_fonts', '' );
    
    // Check if fonts are empty
    if ( empty( $fonts ) ) {
        return;
    }
    
    // Split fonts by line
    $fonts = explode( "\n", $fonts );
    
    // Output preload links
    foreach ( $fonts as $font ) {
        // Skip if font is empty
        $font = trim( $font );
        if ( empty( $font ) ) {
            continue;
        }
        
        // Determine font type
        $font_type = aqualuxe_performance_get_font_type( $font );
        
        // Output preload link
        echo '<link rel="preload" href="' . esc_url( $font ) . '" as="font" type="' . esc_attr( $font_type ) . '" crossorigin="anonymous">' . "\n";
    }
}

/**
 * Get font type from URL
 *
 * @param string $url Font URL
 * @return string Font MIME type
 */
function aqualuxe_performance_get_font_type( $url ) {
    // Get file extension
    $extension = pathinfo( $url, PATHINFO_EXTENSION );
    
    // Determine font type
    switch ( $extension ) {
        case 'woff2':
            return 'font/woff2';
        case 'woff':
            return 'font/woff';
        case 'ttf':
            return 'font/ttf';
        case 'eot':
            return 'application/vnd.ms-fontobject';
        case 'otf':
            return 'font/otf';
        case 'svg':
            return 'image/svg+xml';
        default:
            return 'font/woff2';
    }
}

/**
 * Preload assets
 *
 * @return void
 */
function aqualuxe_performance_preload_assets() {
    // Get assets to preload
    $assets = get_option( 'aqualuxe_performance_preload_assets', '' );
    
    // Check if assets are empty
    if ( empty( $assets ) ) {
        return;
    }
    
    // Split assets by line
    $assets = explode( "\n", $assets );
    
    // Output preload links
    foreach ( $assets as $asset ) {
        // Skip if asset is empty
        $asset = trim( $asset );
        if ( empty( $asset ) ) {
            continue;
        }
        
        // Determine asset type
        $asset_type = aqualuxe_performance_get_asset_type( $asset );
        
        // Output preload link
        echo '<link rel="preload" href="' . esc_url( $asset ) . '" as="' . esc_attr( $asset_type ) . '">' . "\n";
    }
}

/**
 * Get asset type from URL
 *
 * @param string $url Asset URL
 * @return string Asset type
 */
function aqualuxe_performance_get_asset_type( $url ) {
    // Get file extension
    $extension = pathinfo( $url, PATHINFO_EXTENSION );
    
    // Determine asset type
    switch ( $extension ) {
        case 'css':
            return 'style';
        case 'js':
            return 'script';
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
        case 'webp':
        case 'svg':
            return 'image';
        case 'mp4':
        case 'webm':
        case 'ogv':
            return 'video';
        case 'mp3':
        case 'ogg':
        case 'wav':
            return 'audio';
        case 'woff':
        case 'woff2':
        case 'ttf':
        case 'eot':
        case 'otf':
            return 'font';
        default:
            return 'fetch';
    }
}

/**
 * Add DNS prefetch
 *
 * @return void
 */
function aqualuxe_performance_add_dns_prefetch() {
    // Skip if preloading is not enabled
    if ( ! get_option( 'aqualuxe_performance_enable_preloading', true ) ) {
        return;
    }
    
    // Get domains to prefetch
    $domains = get_option( 'aqualuxe_performance_dns_prefetch', '' );
    
    // Check if domains are empty
    if ( empty( $domains ) ) {
        return;
    }
    
    // Split domains by line
    $domains = explode( "\n", $domains );
    
    // Output DNS prefetch links
    foreach ( $domains as $domain ) {
        // Skip if domain is empty
        $domain = trim( $domain );
        if ( empty( $domain ) ) {
            continue;
        }
        
        // Output DNS prefetch link
        echo '<link rel="dns-prefetch" href="' . esc_url( $domain ) . '">' . "\n";
    }
}

/**
 * Add resource hints
 *
 * @param array $hints Resource hints
 * @param string $relation_type Relation type
 * @return array Modified resource hints
 */
function aqualuxe_performance_resource_hints( $hints, $relation_type ) {
    // Skip if preloading is not enabled
    if ( ! get_option( 'aqualuxe_performance_enable_preloading', true ) ) {
        return $hints;
    }
    
    // Add DNS prefetch
    if ( 'dns-prefetch' === $relation_type ) {
        // Get domains to prefetch
        $domains = get_option( 'aqualuxe_performance_dns_prefetch', '' );
        
        // Check if domains are empty
        if ( ! empty( $domains ) ) {
            // Split domains by line
            $domains = explode( "\n", $domains );
            
            // Add domains to hints
            foreach ( $domains as $domain ) {
                // Skip if domain is empty
                $domain = trim( $domain );
                if ( empty( $domain ) ) {
                    continue;
                }
                
                // Add domain to hints
                $hints[] = $domain;
            }
        }
    }
    
    // Add preconnect
    if ( 'preconnect' === $relation_type ) {
        // Add common domains
        $hints[] = 'https://fonts.googleapis.com';
        $hints[] = 'https://fonts.gstatic.com';
        $hints[] = 'https://ajax.googleapis.com';
        $hints[] = 'https://cdnjs.cloudflare.com';
    }
    
    return $hints;
}
add_filter( 'wp_resource_hints', 'aqualuxe_performance_resource_hints', 10, 2 );

/**
 * Add preload header
 *
 * @param array $headers HTTP headers
 * @return array Modified HTTP headers
 */
function aqualuxe_performance_preload_header( $headers ) {
    // Skip if preloading is not enabled
    if ( ! get_option( 'aqualuxe_performance_enable_preloading', true ) ) {
        return $headers;
    }
    
    // Get assets to preload
    $assets = get_option( 'aqualuxe_performance_preload_assets', '' );
    
    // Check if assets are empty
    if ( empty( $assets ) ) {
        return $headers;
    }
    
    // Split assets by line
    $assets = explode( "\n", $assets );
    
    // Initialize preload header
    $preload_header = '';
    
    // Build preload header
    foreach ( $assets as $asset ) {
        // Skip if asset is empty
        $asset = trim( $asset );
        if ( empty( $asset ) ) {
            continue;
        }
        
        // Determine asset type
        $asset_type = aqualuxe_performance_get_asset_type( $asset );
        
        // Add to preload header
        $preload_header .= '<' . esc_url( $asset ) . '>; rel=preload; as=' . esc_attr( $asset_type ) . ', ';
    }
    
    // Remove trailing comma and space
    $preload_header = rtrim( $preload_header, ', ' );
    
    // Add preload header
    if ( ! empty( $preload_header ) ) {
        $headers['Link'] = $preload_header;
    }
    
    return $headers;
}
add_filter( 'wp_headers', 'aqualuxe_performance_preload_header' );

/**
 * Detect critical resources
 *
 * @return array Critical resources
 */
function aqualuxe_performance_detect_critical_resources() {
    // Get home URL
    $home_url = home_url();
    
    // Get critical resources from home page
    $response = wp_remote_get( $home_url );
    
    // Check for errors
    if ( is_wp_error( $response ) ) {
        return array();
    }
    
    // Get response body
    $html = wp_remote_retrieve_body( $response );
    
    // Check if HTML is empty
    if ( empty( $html ) ) {
        return array();
    }
    
    // Initialize critical resources
    $critical_resources = array(
        'fonts' => array(),
        'assets' => array(),
        'domains' => array(),
    );
    
    // Create a new DOMDocument
    $doc = new DOMDocument();
    
    // Suppress errors for malformed HTML
    libxml_use_internal_errors( true );
    
    // Load HTML
    $doc->loadHTML( $html );
    
    // Clear errors
    libxml_clear_errors();
    
    // Get all link tags
    $links = $doc->getElementsByTagName( 'link' );
    
    // Extract resources from link tags
    foreach ( $links as $link ) {
        // Check if it's a stylesheet
        if ( $link->hasAttribute( 'rel' ) && strtolower( $link->getAttribute( 'rel' ) ) === 'stylesheet' ) {
            // Get href attribute
            $href = $link->getAttribute( 'href' );
            
            // Skip if href is empty
            if ( empty( $href ) ) {
                continue;
            }
            
            // Add to critical assets
            $critical_resources['assets'][] = $href;
            
            // Add domain to critical domains
            $domain = parse_url( $href, PHP_URL_HOST );
            if ( ! empty( $domain ) ) {
                $critical_resources['domains'][] = 'https://' . $domain;
            }
        }
        
        // Check if it's a font
        if ( $link->hasAttribute( 'rel' ) && strtolower( $link->getAttribute( 'rel' ) ) === 'preload' && $link->hasAttribute( 'as' ) && strtolower( $link->getAttribute( 'as' ) ) === 'font' ) {
            // Get href attribute
            $href = $link->getAttribute( 'href' );
            
            // Skip if href is empty
            if ( empty( $href ) ) {
                continue;
            }
            
            // Add to critical fonts
            $critical_resources['fonts'][] = $href;
            
            // Add domain to critical domains
            $domain = parse_url( $href, PHP_URL_HOST );
            if ( ! empty( $domain ) ) {
                $critical_resources['domains'][] = 'https://' . $domain;
            }
        }
    }
    
    // Get all script tags
    $scripts = $doc->getElementsByTagName( 'script' );
    
    // Extract resources from script tags
    foreach ( $scripts as $script ) {
        // Get src attribute
        $src = $script->getAttribute( 'src' );
        
        // Skip if src is empty
        if ( empty( $src ) ) {
            continue;
        }
        
        // Add to critical assets
        $critical_resources['assets'][] = $src;
        
        // Add domain to critical domains
        $domain = parse_url( $src, PHP_URL_HOST );
        if ( ! empty( $domain ) ) {
            $critical_resources['domains'][] = 'https://' . $domain;
        }
    }
    
    // Get all image tags
    $images = $doc->getElementsByTagName( 'img' );
    
    // Extract resources from image tags
    foreach ( $images as $image ) {
        // Get src attribute
        $src = $image->getAttribute( 'src' );
        
        // Skip if src is empty
        if ( empty( $src ) ) {
            continue;
        }
        
        // Add to critical assets
        $critical_resources['assets'][] = $src;
        
        // Add domain to critical domains
        $domain = parse_url( $src, PHP_URL_HOST );
        if ( ! empty( $domain ) ) {
            $critical_resources['domains'][] = 'https://' . $domain;
        }
    }
    
    // Remove duplicates
    $critical_resources['fonts'] = array_unique( $critical_resources['fonts'] );
    $critical_resources['assets'] = array_unique( $critical_resources['assets'] );
    $critical_resources['domains'] = array_unique( $critical_resources['domains'] );
    
    return $critical_resources;
}

/**
 * Auto-detect and set critical resources
 *
 * @return void
 */
function aqualuxe_performance_auto_detect_critical_resources() {
    // Detect critical resources
    $critical_resources = aqualuxe_performance_detect_critical_resources();
    
    // Set critical fonts
    if ( ! empty( $critical_resources['fonts'] ) ) {
        update_option( 'aqualuxe_performance_preload_fonts', implode( "\n", $critical_resources['fonts'] ) );
    }
    
    // Set critical assets
    if ( ! empty( $critical_resources['assets'] ) ) {
        update_option( 'aqualuxe_performance_preload_assets', implode( "\n", $critical_resources['assets'] ) );
    }
    
    // Set critical domains
    if ( ! empty( $critical_resources['domains'] ) ) {
        update_option( 'aqualuxe_performance_dns_prefetch', implode( "\n", $critical_resources['domains'] ) );
    }
}