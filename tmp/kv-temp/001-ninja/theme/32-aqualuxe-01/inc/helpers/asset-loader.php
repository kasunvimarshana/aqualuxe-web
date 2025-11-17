<?php
/**
 * Asset loading helper functions
 * 
 * @package AquaLuxe
 * @subpackage Helpers
 * @since 1.3.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the versioned asset URL
 *
 * @param string $path Asset path relative to theme root
 * @return string Versioned asset URL
 */
function aqualuxe_asset($path) {
    static $manifest = null;
    
    if (is_null($manifest)) {
        $manifestPath = get_template_directory() . '/assets/assets-manifest.json';
        
        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
        } else {
            $manifest = [];
        }
    }
    
    $path = ltrim($path, '/');
    
    if (isset($manifest[$path])) {
        return get_template_directory_uri() . '/' . $manifest[$path];
    }
    
    return get_template_directory_uri() . '/' . $path;
}

/**
 * Print the versioned asset URL
 *
 * @param string $path Asset path relative to theme root
 * @return void
 */
function aqualuxe_asset_url($path) {
    echo esc_url(aqualuxe_asset($path));
}

/**
 * Get critical CSS for a specific template
 *
 * @param string $template Template name (home, blog, shop, product)
 * @return string Critical CSS content
 */
function aqualuxe_get_critical_css($template) {
    $critical_css_path = get_template_directory() . '/assets/css/critical/' . $template . '.css';
    
    if (file_exists($critical_css_path)) {
        return file_get_contents($critical_css_path);
    }
    
    return '';
}

/**
 * Print critical CSS inline
 *
 * @param string $template Template name (home, blog, shop, product)
 * @return void
 */
function aqualuxe_critical_css($template) {
    $critical_css = aqualuxe_get_critical_css($template);
    
    if (!empty($critical_css)) {
        echo '<style id="aqualuxe-critical-css">' . $critical_css . '</style>';
    }
}

/**
 * Preload key assets
 *
 * @param array $assets Array of assets to preload
 * @return void
 */
function aqualuxe_preload_assets($assets = []) {
    $default_assets = [
        'fonts/main-font.woff2' => 'font',
        'css/main.css' => 'style',
        'js/app.js' => 'script'
    ];
    
    $assets = array_merge($default_assets, $assets);
    
    foreach ($assets as $path => $type) {
        $as = '';
        switch ($type) {
            case 'font':
                $as = 'font';
                $crossorigin = ' crossorigin';
                $type = 'font/woff2';
                break;
            case 'style':
                $as = 'style';
                $crossorigin = '';
                $type = '';
                break;
            case 'script':
                $as = 'script';
                $crossorigin = '';
                $type = '';
                break;
            case 'image':
                $as = 'image';
                $crossorigin = '';
                $type = '';
                break;
        }
        
        if (!empty($as)) {
            $url = aqualuxe_asset($path);
            $type_attr = !empty($type) ? ' type="' . $type . '"' : '';
            echo '<link rel="preload" href="' . esc_url($url) . '" as="' . esc_attr($as) . '"' . $crossorigin . $type_attr . '>' . "\n";
        }
    }
}

/**
 * Enqueue scripts with versioning
 *
 * @param string $handle Script handle
 * @param string $path Script path relative to theme root
 * @param array $deps Script dependencies
 * @param boolean $in_footer Whether to enqueue in footer
 * @return void
 */
function aqualuxe_enqueue_script($handle, $path, $deps = [], $in_footer = true) {
    wp_enqueue_script(
        $handle,
        aqualuxe_asset($path),
        $deps,
        null, // Version is handled by the asset manifest
        $in_footer
    );
}

/**
 * Enqueue styles with versioning
 *
 * @param string $handle Style handle
 * @param string $path Style path relative to theme root
 * @param array $deps Style dependencies
 * @param string $media Media type
 * @return void
 */
function aqualuxe_enqueue_style($handle, $path, $deps = [], $media = 'all') {
    wp_enqueue_style(
        $handle,
        aqualuxe_asset($path),
        $deps,
        null, // Version is handled by the asset manifest
        $media
    );
}

/**
 * Get image with WebP support
 *
 * @param string $path Image path relative to theme root
 * @param string $alt Alt text
 * @param array $attr Additional attributes
 * @return string HTML img tag with WebP support
 */
function aqualuxe_get_image($path, $alt = '', $attr = []) {
    $url = aqualuxe_asset($path);
    $webp_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $path);
    $webp_url = aqualuxe_asset($webp_path);
    
    $default_attr = [
        'alt' => $alt,
        'loading' => 'lazy',
        'decoding' => 'async'
    ];
    
    $attr = array_merge($default_attr, $attr);
    
    $attr_str = '';
    foreach ($attr as $key => $value) {
        $attr_str .= ' ' . $key . '="' . esc_attr($value) . '"';
    }
    
    $output = '<picture>';
    $output .= '<source srcset="' . esc_url($webp_url) . '" type="image/webp">';
    $output .= '<img src="' . esc_url($url) . '"' . $attr_str . '>';
    $output .= '</picture>';
    
    return $output;
}

/**
 * Print image with WebP support
 *
 * @param string $path Image path relative to theme root
 * @param string $alt Alt text
 * @param array $attr Additional attributes
 * @return void
 */
function aqualuxe_image($path, $alt = '', $attr = []) {
    echo aqualuxe_get_image($path, $alt, $attr);
}

/**
 * Get SVG icon from sprite
 *
 * @param string $icon Icon name
 * @param array $attr Additional attributes
 * @return string SVG icon HTML
 */
function aqualuxe_get_icon($icon, $attr = []) {
    $default_attr = [
        'class' => 'icon icon-' . $icon,
        'aria-hidden' => 'true',
        'focusable' => 'false'
    ];
    
    $attr = array_merge($default_attr, $attr);
    
    $attr_str = '';
    foreach ($attr as $key => $value) {
        $attr_str .= ' ' . $key . '="' . esc_attr($value) . '"';
    }
    
    return '<svg' . $attr_str . '><use xlink:href="' . esc_url(aqualuxe_asset('assets/images/sprite.svg')) . '#icon-' . esc_attr($icon) . '"></use></svg>';
}

/**
 * Print SVG icon from sprite
 *
 * @param string $icon Icon name
 * @param array $attr Additional attributes
 * @return void
 */
function aqualuxe_icon($icon, $attr = []) {
    echo aqualuxe_get_icon($icon, $attr);
}