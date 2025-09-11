<?php
/**
 * Helper Functions
 *
 * @package AquaLuxe\Core
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get theme option with default fallback
 */
function aqualuxe_get_option($option, $default = '') {
    return get_option('aqualuxe_' . $option, $default);
}

/**
 * Check if WooCommerce is active
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Get asset URL with cache busting
 */
function aqualuxe_asset($path) {
    static $manifest = null;
    
    if ($manifest === null) {
        $manifest_path = AQUALUXE_THEME_DIR . '/assets/mix-manifest.json';
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
        } else {
            $manifest = array();
        }
    }
    
    $path = '/' . ltrim($path, '/');
    $versioned_path = isset($manifest[$path]) ? $manifest[$path] : $path;
    
    return AQUALUXE_ASSETS_URI . $versioned_path;
}

/**
 * Get template part with data
 */
function aqualuxe_get_template_part($template, $data = array()) {
    if (!empty($data)) {
        extract($data);
    }
    
    $template_file = AQUALUXE_THEME_DIR . '/templates/' . $template . '.php';
    if (file_exists($template_file)) {
        include $template_file;
    }
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    if (is_front_page()) {
        return;
    }
    
    $breadcrumbs = array();
    $breadcrumbs[] = '<a href="' . esc_url(home_url()) . '">' . __('Home', 'aqualuxe') . '</a>';
    
    if (is_category() || is_single()) {
        $category = get_the_category();
        if ($category) {
            $breadcrumbs[] = '<a href="' . esc_url(get_category_link($category[0]->term_id)) . '">' . esc_html($category[0]->name) . '</a>';
        }
    }
    
    if (is_single()) {
        $breadcrumbs[] = '<span>' . get_the_title() . '</span>';
    } elseif (is_page()) {
        $breadcrumbs[] = '<span>' . get_the_title() . '</span>';
    } elseif (is_category()) {
        $breadcrumbs[] = '<span>' . single_cat_title('', false) . '</span>';
    } elseif (is_tag()) {
        $breadcrumbs[] = '<span>' . single_tag_title('', false) . '</span>';
    } elseif (is_archive()) {
        $breadcrumbs[] = '<span>' . get_the_archive_title() . '</span>';
    } elseif (is_search()) {
        $breadcrumbs[] = '<span>' . sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query()) . '</span>';
    } elseif (is_404()) {
        $breadcrumbs[] = '<span>' . __('404 Error', 'aqualuxe') . '</span>';
    }
    
    if (!empty($breadcrumbs)) {
        echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
        echo '<ol class="breadcrumb-list">';
        foreach ($breadcrumbs as $breadcrumb) {
            echo '<li class="breadcrumb-item">' . $breadcrumb . '</li>';
        }
        echo '</ol>';
        echo '</nav>';
    }
}

/**
 * Get post reading time
 */
function aqualuxe_get_reading_time($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute
    
    return $reading_time;
}

/**
 * Display reading time
 */
function aqualuxe_reading_time($post_id = null) {
    $reading_time = aqualuxe_get_reading_time($post_id);
    
    if ($reading_time === 1) {
        printf(__('%d minute read', 'aqualuxe'), $reading_time);
    } else {
        printf(__('%d minutes read', 'aqualuxe'), $reading_time);
    }
}

/**
 * Get social share links
 */
function aqualuxe_get_social_share_links($post_id = null) {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    $url = get_permalink($post_id);
    $title = get_the_title($post_id);
    $excerpt = get_the_excerpt($post_id);
    
    $share_links = array(
        'facebook' => array(
            'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url),
            'label' => __('Share on Facebook', 'aqualuxe'),
            'icon' => 'facebook'
        ),
        'twitter' => array(
            'url' => 'https://twitter.com/intent/tweet?url=' . urlencode($url) . '&text=' . urlencode($title),
            'label' => __('Share on Twitter', 'aqualuxe'),
            'icon' => 'twitter'
        ),
        'linkedin' => array(
            'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($url),
            'label' => __('Share on LinkedIn', 'aqualuxe'),
            'icon' => 'linkedin'
        ),
        'pinterest' => array(
            'url' => 'https://pinterest.com/pin/create/button/?url=' . urlencode($url) . '&description=' . urlencode($excerpt),
            'label' => __('Pin on Pinterest', 'aqualuxe'),
            'icon' => 'pinterest'
        ),
        'email' => array(
            'url' => 'mailto:?subject=' . urlencode($title) . '&body=' . urlencode($url),
            'label' => __('Share via Email', 'aqualuxe'),
            'icon' => 'mail'
        )
    );
    
    return apply_filters('aqualuxe_social_share_links', $share_links, $post_id);
}

/**
 * Display social share buttons
 */
function aqualuxe_social_share_buttons($post_id = null) {
    $share_links = aqualuxe_get_social_share_links($post_id);
    
    if (!empty($share_links)) {
        echo '<div class="social-share">';
        echo '<span class="social-share-label">' . __('Share:', 'aqualuxe') . '</span>';
        echo '<ul class="social-share-list">';
        
        foreach ($share_links as $network => $link) {
            echo '<li class="social-share-item">';
            echo '<a href="' . esc_url($link['url']) . '" target="_blank" rel="noopener noreferrer" class="social-share-link social-share-' . esc_attr($network) . '" aria-label="' . esc_attr($link['label']) . '">';
            echo '<i class="icon-' . esc_attr($link['icon']) . '" aria-hidden="true"></i>';
            echo '<span class="sr-only">' . esc_html($link['label']) . '</span>';
            echo '</a>';
            echo '</li>';
        }
        
        echo '</ul>';
        echo '</div>';
    }
}

/**
 * Get excerpt with custom length
 */
function aqualuxe_get_excerpt($post_id = null, $length = 25, $more = '...') {
    if (!$post_id) {
        global $post;
        $post_id = $post->ID;
    }
    
    $excerpt = get_the_excerpt($post_id);
    
    if (!$excerpt) {
        $content = get_post_field('post_content', $post_id);
        $excerpt = wp_trim_words(strip_tags($content), $length, $more);
    }
    
    return $excerpt;
}

/**
 * Sanitize and validate data
 */
function aqualuxe_sanitize_data($data, $type = 'text') {
    switch ($type) {
        case 'email':
            return sanitize_email($data);
        case 'url':
            return esc_url_raw($data);
        case 'int':
            return intval($data);
        case 'float':
            return floatval($data);
        case 'html':
            return wp_kses_post($data);
        case 'textarea':
            return sanitize_textarea_field($data);
        case 'bool':
            return (bool) $data;
        default:
            return sanitize_text_field($data);
    }
}

/**
 * Check if current page is AquaLuxe custom post type
 */
function aqualuxe_is_custom_post_type($post_type = null) {
    $aqualuxe_post_types = array(
        'aqualuxe_service',
        'aqualuxe_event',
        'aqualuxe_testimonial'
    );
    
    if ($post_type) {
        return in_array($post_type, $aqualuxe_post_types);
    }
    
    return in_array(get_post_type(), $aqualuxe_post_types);
}

/**
 * Get theme color palette
 */
function aqualuxe_get_color_palette() {
    return array(
        'primary' => get_theme_mod('primary_color', '#0ea5e9'),
        'secondary' => get_theme_mod('secondary_color', '#64748b'),
        'accent' => get_theme_mod('accent_color', '#06b6d4'),
        'success' => get_theme_mod('success_color', '#10b981'),
        'warning' => get_theme_mod('warning_color', '#f59e0b'),
        'error' => get_theme_mod('error_color', '#ef4444'),
        'dark' => get_theme_mod('dark_color', '#1e293b'),
        'light' => get_theme_mod('light_color', '#f8fafc'),
    );
}

/**
 * Get responsive image with lazy loading
 */
function aqualuxe_get_responsive_image($attachment_id, $size = 'full', $class = '') {
    if (!$attachment_id) {
        return '';
    }
    
    $image = wp_get_attachment_image(
        $attachment_id,
        $size,
        false,
        array(
            'class' => $class,
            'loading' => 'lazy',
            'decoding' => 'async'
        )
    );
    
    return $image;
}

/**
 * Check if current request is AJAX
 */
function aqualuxe_is_ajax_request() {
    return defined('DOING_AJAX') && DOING_AJAX;
}

/**
 * Get current URL
 */
function aqualuxe_get_current_url() {
    $protocol = is_ssl() ? 'https://' : 'http://';
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}