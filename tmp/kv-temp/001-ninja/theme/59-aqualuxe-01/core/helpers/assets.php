<?php
/**
 * AquaLuxe Assets Helper Functions
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Enqueue style
 *
 * @param string $handle Handle
 * @param string $path Asset path
 * @param array $deps Dependencies
 * @param string $media Media
 */
function aqualuxe_enqueue_style($handle, $path, $deps = [], $media = 'all') {
    AquaLuxe_Assets::enqueue_style($handle, $path, $deps, $media);
}

/**
 * Enqueue script
 *
 * @param string $handle Handle
 * @param string $path Asset path
 * @param array $deps Dependencies
 * @param bool $in_footer In footer
 */
function aqualuxe_enqueue_script($handle, $path, $deps = [], $in_footer = true) {
    AquaLuxe_Assets::enqueue_script($handle, $path, $deps, $in_footer);
}

/**
 * Localize script
 *
 * @param string $handle Handle
 * @param string $object_name Object name
 * @param array $data Data
 */
function aqualuxe_localize_script($handle, $object_name, $data) {
    AquaLuxe_Assets::localize_script($handle, $object_name, $data);
}

/**
 * Register style
 *
 * @param string $handle Handle
 * @param string $path Asset path
 * @param array $deps Dependencies
 * @param string $media Media
 */
function aqualuxe_register_style($handle, $path, $deps = [], $media = 'all') {
    AquaLuxe_Assets::register_style($handle, $path, $deps, $media);
}

/**
 * Register script
 *
 * @param string $handle Handle
 * @param string $path Asset path
 * @param array $deps Dependencies
 * @param bool $in_footer In footer
 */
function aqualuxe_register_script($handle, $path, $deps = [], $in_footer = true) {
    AquaLuxe_Assets::register_script($handle, $path, $deps, $in_footer);
}

/**
 * Get asset URL
 *
 * @param string $path Asset path
 * @return string Asset URL
 */
function aqualuxe_get_asset_url($path) {
    return AquaLuxe_Assets::get_asset_url($path);
}

/**
 * Preload asset
 *
 * @param string $path Asset path
 * @param string $type Asset type
 * @param string $media Media
 */
function aqualuxe_preload($path, $type = 'style', $media = 'all') {
    AquaLuxe_Assets::preload($path, $type, $media);
}

/**
 * Add preconnect
 *
 * @param string $url URL
 * @param bool $crossorigin Crossorigin
 */
function aqualuxe_preconnect($url, $crossorigin = true) {
    AquaLuxe_Assets::preconnect($url, $crossorigin);
}

/**
 * Add DNS prefetch
 *
 * @param string $url URL
 */
function aqualuxe_dns_prefetch($url) {
    AquaLuxe_Assets::dns_prefetch($url);
}

/**
 * Add preload fonts
 *
 * @param string $url Font URL
 * @param string $format Font format
 */
function aqualuxe_preload_font($url, $format = 'woff2') {
    AquaLuxe_Assets::preload_font($url, $format);
}

/**
 * Add inline style
 *
 * @param string $handle Handle
 * @param string $css CSS
 */
function aqualuxe_add_inline_style($handle, $css) {
    AquaLuxe_Assets::add_inline_style($handle, $css);
}

/**
 * Add inline script
 *
 * @param string $handle Handle
 * @param string $js JavaScript
 * @param string $position Position
 */
function aqualuxe_add_inline_script($handle, $js, $position = 'after') {
    AquaLuxe_Assets::add_inline_script($handle, $js, $position);
}

/**
 * Get Google Fonts URL
 *
 * @param array $fonts Fonts
 * @return string
 */
function aqualuxe_get_google_fonts_url($fonts = []) {
    if (empty($fonts)) {
        $fonts = [
            'Playfair Display:400,400i,700,700i,900,900i',
            'Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i',
        ];
    }
    
    $fonts_url = '';
    
    if ($fonts) {
        $fonts_url = add_query_arg([
            'family' => urlencode(implode('|', $fonts)),
            'display' => 'swap',
        ], 'https://fonts.googleapis.com/css');
    }
    
    return $fonts_url;
}

/**
 * Get SVG icon
 *
 * @param string $icon Icon name
 * @param array $args Icon arguments
 * @return string
 */
function aqualuxe_get_svg_icon($icon, $args = []) {
    // Set defaults
    $defaults = [
        'width'  => 24,
        'height' => 24,
        'class'  => '',
        'fill'   => 'currentColor',
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Extract args
    $width  = $args['width'];
    $height = $args['height'];
    $class  = $args['class'] ? ' class="' . esc_attr($args['class']) . '"' : '';
    $fill   = $args['fill'];
    
    // Define icons
    $icons = [
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
        'cart' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>',
        'user' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
        'heart' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>',
        'menu' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>',
        'close' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
        'chevron-down' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>',
        'chevron-up' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>',
        'chevron-left' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>',
        'chevron-right' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>',
        'arrow-left' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
        'arrow-right' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
        'mail' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>',
        'phone' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>',
        'map-pin' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
        'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="' . esc_attr($fill) . '"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>',
        'twitter' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="' . esc_attr($fill) . '"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg>',
        'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="' . esc_attr($fill) . '"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
        'youtube' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="' . esc_attr($fill) . '"><path d="M23.8 7.2s-.2-1.7-1-2.4c-.9-1-1.9-1-2.4-1-3.4-.2-8.4-.2-8.4-.2s-5 0-8.4.2c-.5.1-1.5.1-2.4 1-.7.7-1 2.4-1 2.4S0 9.1 0 11.1v1.8c0 1.9.2 3.9.2 3.9s.2 1.7 1 2.4c.9 1 2.1.9 2.6 1 1.9.2 8.2.2 8.2.2s5 0 8.4-.3c.5-.1 1.5-.1 2.4-1 .7-.7 1-2.4 1-2.4s.2-1.9.2-3.9V11c0-1.9-.2-3.8-.2-3.8zM9.5 15.1V8.4l6.5 3.4-6.5 3.3z"/></svg>',
        'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="' . esc_attr($fill) . '"><path d="M6.5 21.5h-5v-13h5v13zM4 6.5C2.5 6.5 1.5 5.3 1.5 4s1-2.4 2.5-2.4c1.6 0 2.5 1 2.6 2.5 0 1.4-1 2.5-2.6 2.5zm11.5 6c-1 0-2 1-2 2v7h-5v-13h5V10s1.6-1.5 4-1.5c3 0 5 2.2 5 6.3v6.7h-5v-7c0-1-1-2-2-2z"/></svg>',
        'pinterest' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="' . esc_attr($fill) . '"><path d="M12.14.5C5.86.5 2.7 5 2.7 8.75c0 2.27.86 4.3 2.7 5.05.3.12.57 0 .66-.33l.27-1.06c.1-.32.06-.44-.2-.73-.52-.62-.86-1.44-.86-2.6 0-3.33 2.5-6.32 6.5-6.32 3.55 0 5.5 2.17 5.5 5.07 0 3.8-1.7 7.02-4.2 7.02-1.37 0-2.4-1.14-2.07-2.54.4-1.68 1.16-3.48 1.16-4.7 0-1.07-.58-1.98-1.78-1.98-1.4 0-2.55 1.47-2.55 3.42 0 1.25.43 2.1.43 2.1l-1.7 7.2c-.5 2.13-.08 4.75-.04 5.02.02.17.22.2.3.1.14-.18 1.82-2.26 2.4-4.33.16-.58.93-3.63.93-3.63.45.88 1.8 1.65 3.22 1.65 4.25 0 7.13-3.87 7.13-9.05C20.5 4.15 17.18.5 12.14.5z"/></svg>',
        'sun' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>',
        'moon' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>',
        'globe' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>',
        'filter' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>',
        'grid' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>',
        'list' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>',
        'star' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="none" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>',
        'star-fill' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"' . $class . ' viewBox="0 0 24 24" fill="' . esc_attr($fill) . '" stroke="' . esc_attr($fill) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>',
    ];
    
    // Return icon if exists
    if (isset($icons[$icon])) {
        return $icons[$icon];
    }
    
    return '';
}

/**
 * Print SVG icon
 *
 * @param string $icon Icon name
 * @param array $args Icon arguments
 */
function aqualuxe_svg_icon($icon, $args = []) {
    echo aqualuxe_get_svg_icon($icon, $args);
}