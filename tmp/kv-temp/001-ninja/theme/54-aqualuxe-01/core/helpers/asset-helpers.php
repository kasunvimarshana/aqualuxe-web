<?php
/**
 * AquaLuxe Asset Helpers
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get the mix manifest
 *
 * @return array
 */
function aqualuxe_get_mix_manifest() {
    $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
    
    if (file_exists($manifest_path)) {
        return json_decode(file_get_contents($manifest_path), true);
    }
    
    return [];
}

/**
 * Get the mix asset path
 *
 * @param string $path Asset path
 * @param array $manifest Mix manifest
 * @return string
 */
function aqualuxe_mix($path, $manifest = null) {
    if (is_null($manifest)) {
        $manifest = aqualuxe_get_mix_manifest();
    }
    
    $path = '/' . ltrim($path, '/');
    
    if (isset($manifest[$path])) {
        return AQUALUXE_ASSETS_URI . ltrim($manifest[$path], '/');
    }
    
    return AQUALUXE_ASSETS_URI . ltrim($path, '/');
}

/**
 * Get the asset version
 *
 * @param string $path Asset path
 * @return string
 */
function aqualuxe_asset_version($path) {
    $file_path = AQUALUXE_DIR . 'assets/dist/' . ltrim($path, '/');
    
    if (file_exists($file_path)) {
        return filemtime($file_path);
    }
    
    return AQUALUXE_VERSION;
}

/**
 * Enqueue module asset
 *
 * @param string $module Module name
 * @param string $handle Asset handle
 * @param string $path Asset path
 * @param array $deps Dependencies
 * @param string $version Asset version
 * @param bool $in_footer Whether to enqueue in footer
 * @return void
 */
function aqualuxe_enqueue_module_script($module, $handle, $path, $deps = [], $version = null, $in_footer = true) {
    if (!aqualuxe_is_module_active($module)) {
        return;
    }
    
    $full_handle = 'aqualuxe-' . $module . '-' . $handle;
    $full_path = AQUALUXE_MODULES_DIR . $module . '/assets/' . ltrim($path, '/');
    $full_url = AQUALUXE_URI . 'modules/' . $module . '/assets/' . ltrim($path, '/');
    
    if (is_null($version) && file_exists($full_path)) {
        $version = filemtime($full_path);
    } else {
        $version = $version ?: AQUALUXE_VERSION;
    }
    
    wp_enqueue_script($full_handle, $full_url, $deps, $version, $in_footer);
}

/**
 * Enqueue module style
 *
 * @param string $module Module name
 * @param string $handle Asset handle
 * @param string $path Asset path
 * @param array $deps Dependencies
 * @param string $version Asset version
 * @param string $media Media
 * @return void
 */
function aqualuxe_enqueue_module_style($module, $handle, $path, $deps = [], $version = null, $media = 'all') {
    if (!aqualuxe_is_module_active($module)) {
        return;
    }
    
    $full_handle = 'aqualuxe-' . $module . '-' . $handle;
    $full_path = AQUALUXE_MODULES_DIR . $module . '/assets/' . ltrim($path, '/');
    $full_url = AQUALUXE_URI . 'modules/' . $module . '/assets/' . ltrim($path, '/');
    
    if (is_null($version) && file_exists($full_path)) {
        $version = filemtime($full_path);
    } else {
        $version = $version ?: AQUALUXE_VERSION;
    }
    
    wp_enqueue_style($full_handle, $full_url, $deps, $version, $media);
}

/**
 * Localize module script
 *
 * @param string $module Module name
 * @param string $handle Asset handle
 * @param string $object_name Object name
 * @param array $data Data
 * @return void
 */
function aqualuxe_localize_module_script($module, $handle, $object_name, $data) {
    if (!aqualuxe_is_module_active($module)) {
        return;
    }
    
    $full_handle = 'aqualuxe-' . $module . '-' . $handle;
    
    wp_localize_script($full_handle, $object_name, $data);
}

/**
 * Get inline SVG
 *
 * @param string $filename SVG filename
 * @param array $args Arguments
 * @return string
 */
function aqualuxe_get_svg($filename, $args = []) {
    $defaults = [
        'class' => '',
        'width' => '',
        'height' => '',
        'title' => '',
        'desc' => '',
        'aria_hidden' => true,
        'fallback' => false,
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    // Define SVG directory path
    $svg_path = AQUALUXE_DIR . 'assets/dist/images/svg/' . $filename . '.svg';
    
    // Check if file exists
    if (!file_exists($svg_path)) {
        return '';
    }
    
    // Get the SVG file content
    $svg_content = file_get_contents($svg_path);
    
    // Add class to SVG
    if ($args['class']) {
        $svg_content = preg_replace('/^<svg /', '<svg class="' . esc_attr($args['class']) . '" ', $svg_content);
    }
    
    // Add width and height
    if ($args['width']) {
        $svg_content = preg_replace('/width="[^"]*"/', 'width="' . esc_attr($args['width']) . '"', $svg_content);
    }
    
    if ($args['height']) {
        $svg_content = preg_replace('/height="[^"]*"/', 'height="' . esc_attr($args['height']) . '"', $svg_content);
    }
    
    // Add title
    if ($args['title']) {
        $title_tag = '<title>' . esc_html($args['title']) . '</title>';
        $svg_content = preg_replace('/<svg ([^>]*)>/', '<svg $1>' . $title_tag, $svg_content);
    }
    
    // Add description
    if ($args['desc']) {
        $desc_tag = '<desc>' . esc_html($args['desc']) . '</desc>';
        $svg_content = preg_replace('/<svg ([^>]*)>/', '<svg $1>' . $desc_tag, $svg_content);
    }
    
    // Add aria-hidden
    if ($args['aria_hidden']) {
        $svg_content = preg_replace('/^<svg /', '<svg aria-hidden="true" ', $svg_content);
    }
    
    return $svg_content;
}

/**
 * Print inline SVG
 *
 * @param string $filename SVG filename
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_svg($filename, $args = []) {
    echo aqualuxe_get_svg($filename, $args);
}