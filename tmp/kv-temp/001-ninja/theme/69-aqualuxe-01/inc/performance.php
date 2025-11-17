<?php
/**
 * AquaLuxe Performance Optimizations
 * - Enables lazy loading
 * - Disables emoji scripts
 * - Optimizes asset loading
 */
// Lazy loading for images is native in WP 5.5+
// Remove WP emoji scripts
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
// Defer JS loading (example)
add_filter('script_loader_tag', function($tag, $handle) {
    if (is_admin()) return $tag;
    if (strpos($tag, 'aqualuxe-') !== false) {
        return str_replace(' src', ' defer src', $tag);
    }
    return $tag;
}, 10, 2);
