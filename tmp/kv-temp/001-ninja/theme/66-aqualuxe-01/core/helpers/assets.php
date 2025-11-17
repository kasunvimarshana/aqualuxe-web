<?php
/**
 * Assets helper functions
 *
 * @package AquaLuxe
 */

/**
 * Get asset URL
 *
 * @param string $path
 * @return string
 */
function aqualuxe_asset_url($path) {
    return AQUALUXE_ASSETS_URI . $path;
}

/**
 * Get dist asset URL
 *
 * @param string $path
 * @return string
 */
function aqualuxe_dist_url($path) {
    return AQUALUXE_DIST_URI . $path;
}

/**
 * Get versioned asset URL
 *
 * @param string $path
 * @return string
 */
function aqualuxe_mix_asset($path) {
    $manifest_path = AQUALUXE_DIR . 'assets/dist/mix-manifest.json';
    
    if (!file_exists($manifest_path)) {
        return aqualuxe_dist_url($path);
    }
    
    $manifest = json_decode(file_get_contents($manifest_path), true);
    $path_with_slash = '/' . ltrim($path, '/');
    
    if (!isset($manifest[$path_with_slash])) {
        return aqualuxe_dist_url($path);
    }
    
    return AQUALUXE_DIST_URI . ltrim($manifest[$path_with_slash], '/');
}

/**
 * Enqueue style with versioning
 *
 * @param string $handle
 * @param string $path
 * @param array $deps
 * @param string $media
 */
function aqualuxe_enqueue_style($handle, $path, $deps = [], $media = 'all') {
    wp_enqueue_style(
        $handle,
        aqualuxe_mix_asset($path),
        $deps,
        null,
        $media
    );
}

/**
 * Enqueue script with versioning
 *
 * @param string $handle
 * @param string $path
 * @param array $deps
 * @param bool $in_footer
 */
function aqualuxe_enqueue_script($handle, $path, $deps = [], $in_footer = true) {
    wp_enqueue_script(
        $handle,
        aqualuxe_mix_asset($path),
        $deps,
        null,
        $in_footer
    );
}

/**
 * Get image URL
 *
 * @param string $path
 * @return string
 */
function aqualuxe_image_url($path) {
    return aqualuxe_asset_url('dist/images/' . $path);
}

/**
 * Get SVG icon
 *
 * @param string $icon
 * @param array $args
 * @return string
 */
function aqualuxe_get_svg_icon($icon, $args = []) {
    // Set defaults
    $defaults = [
        'class' => '',
        'width' => 24,
        'height' => 24,
        'aria-hidden' => 'true',
        'role' => 'img',
        'focusable' => 'false',
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Set aria-hidden to true if not explicitly set
    if (!isset($args['aria-hidden'])) {
        $args['aria-hidden'] = 'true';
    }
    
    // Set role to img if not explicitly set
    if (!isset($args['role'])) {
        $args['role'] = 'img';
    }
    
    // Set focusable to false if not explicitly set
    if (!isset($args['focusable'])) {
        $args['focusable'] = 'false';
    }
    
    // Get icon path
    $icon_path = AQUALUXE_DIR . 'assets/src/images/icons/' . $icon . '.svg';
    
    // Return empty string if icon doesn't exist
    if (!file_exists($icon_path)) {
        return '';
    }
    
    // Get icon content
    $icon_content = file_get_contents($icon_path);
    
    // Add class to svg
    if (!empty($args['class'])) {
        $icon_content = str_replace('<svg', '<svg class="' . esc_attr($args['class']) . '"', $icon_content);
    }
    
    // Add width and height
    $icon_content = str_replace('<svg', '<svg width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '"', $icon_content);
    
    // Add aria-hidden
    $icon_content = str_replace('<svg', '<svg aria-hidden="' . esc_attr($args['aria-hidden']) . '"', $icon_content);
    
    // Add role
    $icon_content = str_replace('<svg', '<svg role="' . esc_attr($args['role']) . '"', $icon_content);
    
    // Add focusable
    $icon_content = str_replace('<svg', '<svg focusable="' . esc_attr($args['focusable']) . '"', $icon_content);
    
    return $icon_content;
}

/**
 * Print SVG icon
 *
 * @param string $icon
 * @param array $args
 */
function aqualuxe_svg_icon($icon, $args = []) {
    echo aqualuxe_get_svg_icon($icon, $args);
}

/**
 * Get inline SVG
 *
 * @param string $path
 * @return string
 */
function aqualuxe_get_inline_svg($path) {
    $svg_path = AQUALUXE_DIR . 'assets/src/images/' . $path;
    
    if (!file_exists($svg_path)) {
        return '';
    }
    
    return file_get_contents($svg_path);
}

/**
 * Print inline SVG
 *
 * @param string $path
 */
function aqualuxe_inline_svg($path) {
    echo aqualuxe_get_inline_svg($path);
}

/**
 * Get image with lazy loading
 *
 * @param int $attachment_id
 * @param string $size
 * @param array $attr
 * @return string
 */
function aqualuxe_get_lazy_image($attachment_id, $size = 'thumbnail', $attr = []) {
    // Set defaults
    $defaults = [
        'class' => '',
        'loading' => 'lazy',
    ];
    
    // Parse args
    $attr = wp_parse_args($attr, $defaults);
    
    // Add lazy loading class
    if (!empty($attr['class'])) {
        $attr['class'] .= ' lazy-load';
    } else {
        $attr['class'] = 'lazy-load';
    }
    
    // Get image
    return wp_get_attachment_image($attachment_id, $size, false, $attr);
}

/**
 * Print image with lazy loading
 *
 * @param int $attachment_id
 * @param string $size
 * @param array $attr
 */
function aqualuxe_lazy_image($attachment_id, $size = 'thumbnail', $attr = []) {
    echo aqualuxe_get_lazy_image($attachment_id, $size, $attr);
}

/**
 * Get image with srcset
 *
 * @param int $attachment_id
 * @param string $size
 * @param array $attr
 * @return string
 */
function aqualuxe_get_responsive_image($attachment_id, $size = 'thumbnail', $attr = []) {
    // Set defaults
    $defaults = [
        'class' => '',
        'loading' => 'lazy',
    ];
    
    // Parse args
    $attr = wp_parse_args($attr, $defaults);
    
    // Add responsive image class
    if (!empty($attr['class'])) {
        $attr['class'] .= ' responsive-image';
    } else {
        $attr['class'] = 'responsive-image';
    }
    
    // Get image
    return wp_get_attachment_image($attachment_id, $size, false, $attr);
}

/**
 * Print image with srcset
 *
 * @param int $attachment_id
 * @param string $size
 * @param array $attr
 */
function aqualuxe_responsive_image($attachment_id, $size = 'thumbnail', $attr = []) {
    echo aqualuxe_get_responsive_image($attachment_id, $size, $attr);
}