<?php
/**
 * Helper functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get theme option
 */
function aqualuxe_get_option($option, $default = '') {
    return get_theme_mod($option, $default);
}

/**
 * Check if WooCommerce is active
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Check if module is enabled
 */
function aqualuxe_is_module_enabled($module) {
    return get_theme_mod('aqualuxe_module_' . str_replace('-', '_', $module), true);
}

/**
 * Get primary color
 */
function aqualuxe_get_primary_color() {
    return get_theme_mod('aqualuxe_primary_color', '#14b8a6');
}

/**
 * Get secondary color
 */
function aqualuxe_get_secondary_color() {
    return get_theme_mod('aqualuxe_secondary_color', '#a855f7');
}

/**
 * Get accent color
 */
function aqualuxe_get_accent_color() {
    return get_theme_mod('aqualuxe_accent_color', '#0ea5e9');
}

/**
 * Get container classes
 */
function aqualuxe_get_container_classes() {
    $classes = ['container', 'mx-auto', 'px-4'];
    
    $container_width = get_theme_mod('aqualuxe_container_width', 1200);
    if ($container_width !== 1200) {
        $classes[] = 'max-w-' . $container_width;
    }
    
    return implode(' ', $classes);
}

/**
 * Get sidebar position
 */
function aqualuxe_get_sidebar_position() {
    return get_theme_mod('aqualuxe_sidebar_position', 'right');
}

/**
 * Check if sidebar should be displayed
 */
function aqualuxe_has_sidebar() {
    if (is_page_template('page-full-width.php')) {
        return false;
    }
    
    if (aqualuxe_is_woocommerce_active() && (is_shop() || is_product_category() || is_product_tag())) {
        return is_active_sidebar('sidebar-shop');
    }
    
    return aqualuxe_get_sidebar_position() !== 'none' && is_active_sidebar('sidebar-1');
}

/**
 * Get content classes based on sidebar
 */
function aqualuxe_get_content_classes() {
    $classes = [];
    
    if (aqualuxe_has_sidebar()) {
        $classes[] = 'lg:w-2/3';
        
        if (aqualuxe_get_sidebar_position() === 'left') {
            $classes[] = 'lg:order-2';
        }
    } else {
        $classes[] = 'w-full';
    }
    
    $classes[] = 'px-4';
    
    return implode(' ', $classes);
}

/**
 * Get sidebar classes
 */
function aqualuxe_get_sidebar_classes() {
    $classes = ['lg:w-1/3', 'px-4'];
    
    if (aqualuxe_get_sidebar_position() === 'left') {
        $classes[] = 'lg:order-1';
    }
    
    return implode(' ', $classes);
}

/**
 * Get hero background image
 */
function aqualuxe_get_hero_background() {
    $background_id = get_theme_mod('aqualuxe_hero_background');
    
    if ($background_id) {
        return wp_get_attachment_image_url($background_id, 'full');
    }
    
    return '';
}

/**
 * Get social media links
 */
function aqualuxe_get_social_links() {
    $social_networks = [
        'facebook' => 'Facebook',
        'twitter' => 'Twitter',
        'instagram' => 'Instagram',
        'youtube' => 'YouTube',
        'linkedin' => 'LinkedIn',
    ];
    
    $links = [];
    
    foreach ($social_networks as $network => $label) {
        $url = get_theme_mod("aqualuxe_social_{$network}");
        if ($url) {
            $links[$network] = [
                'url' => $url,
                'label' => $label,
            ];
        }
    }
    
    return $links;
}

/**
 * Get social media icon
 */
function aqualuxe_get_social_icon($network) {
    $icons = [
        'facebook' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
        'twitter' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>',
        'instagram' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.596-3.205-1.533-.756-.937-1.178-2.171-1.178-3.455s.422-2.518 1.178-3.455c.757-.937 1.908-1.533 3.205-1.533s2.448.596 3.205 1.533c.756.937 1.178 2.171 1.178 3.455s-.422 2.518-1.178 3.455c-.757.937-1.908 1.533-3.205 1.533zm7.138 0c-1.297 0-2.448-.596-3.205-1.533-.756-.937-1.178-2.171-1.178-3.455s.422-2.518 1.178-3.455c.757-.937 1.908-1.533 3.205-1.533s2.448.596 3.205 1.533c.756.937 1.178 2.171 1.178 3.455s-.422 2.518-1.178 3.455c-.757.937-1.908 1.533-3.205 1.533z"/></svg>',
        'youtube' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
        'linkedin' => '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
    ];
    
    return isset($icons[$network]) ? $icons[$network] : '';
}

/**
 * Sanitize checkbox
 */
function aqualuxe_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Sanitize select
 */
function aqualuxe_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Get post thumbnail with fallback
 */
function aqualuxe_get_post_thumbnail($post_id = null, $size = 'large', $attr = []) {
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size, $attr);
    }
    
    // Fallback placeholder
    $placeholder_url = AQUALUXE_ASSETS_URI . '/dist/images/placeholder.jpg';
    $alt_text = get_the_title($post_id) ?: esc_html__('No image available', 'aqualuxe');
    
    return sprintf(
        '<img src="%s" alt="%s" class="wp-post-image %s">',
        esc_url($placeholder_url),
        esc_attr($alt_text),
        esc_attr(implode(' ', $attr))
    );
}

/**
 * Get reading time
 */
function aqualuxe_get_reading_time($post_id = null) {
    $post = get_post($post_id);
    $word_count = str_word_count(strip_tags($post->post_content));
    $reading_time = ceil($word_count / 200); // Average reading speed
    
    return sprintf(
        _n('%d min read', '%d min read', $reading_time, 'aqualuxe'),
        $reading_time
    );
}

/**
 * Get excerpt with custom length
 */
function aqualuxe_get_excerpt($post_id = null, $length = 30) {
    $post = get_post($post_id);
    
    if ($post->post_excerpt) {
        return wp_trim_words($post->post_excerpt, $length);
    }
    
    return wp_trim_words($post->post_content, $length);
}

/**
 * Get breadcrumbs
 */
function aqualuxe_get_breadcrumbs() {
    if (is_home() || is_front_page()) {
        return '';
    }
    
    $breadcrumbs = [];
    $breadcrumbs[] = '<a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'aqualuxe') . '</a>';
    
    if (is_category()) {
        $breadcrumbs[] = '<span>' . single_cat_title('', false) . '</span>';
    } elseif (is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $breadcrumbs[] = '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
        }
        $breadcrumbs[] = '<span>' . get_the_title() . '</span>';
    } elseif (is_page()) {
        $breadcrumbs[] = '<span>' . get_the_title() . '</span>';
    } elseif (is_tag()) {
        $breadcrumbs[] = '<span>' . single_tag_title('', false) . '</span>';
    } elseif (is_author()) {
        $breadcrumbs[] = '<span>' . get_the_author() . '</span>';
    } elseif (is_search()) {
        $breadcrumbs[] = '<span>' . sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), get_search_query()) . '</span>';
    } elseif (is_404()) {
        $breadcrumbs[] = '<span>' . esc_html__('Page Not Found', 'aqualuxe') . '</span>';
    }
    
    return '<nav class="breadcrumbs text-sm text-gray-600 mb-6">' . implode(' <span class="separator">/</span> ', $breadcrumbs) . '</nav>';
}

/**
 * Get archive title
 */
function aqualuxe_get_archive_title() {
    if (is_category()) {
        return single_cat_title('', false);
    } elseif (is_tag()) {
        return single_tag_title('', false);
    } elseif (is_author()) {
        return get_the_author();
    } elseif (is_date()) {
        if (is_year()) {
            return get_the_date('Y');
        } elseif (is_month()) {
            return get_the_date('F Y');
        } elseif (is_day()) {
            return get_the_date();
        }
    } elseif (is_search()) {
        return sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), get_search_query());
    } elseif (is_home()) {
        return esc_html__('Blog', 'aqualuxe');
    }
    
    return get_the_archive_title();
}

/**
 * Get archive description
 */
function aqualuxe_get_archive_description() {
    if (is_category() || is_tag() || is_tax()) {
        return term_description();
    } elseif (is_author()) {
        return get_the_author_meta('description');
    }
    
    return get_the_archive_description();
}

/**
 * Display pagination
 */
function aqualuxe_pagination() {
    $pagination = paginate_links([
        'type' => 'array',
        'prev_text' => esc_html__('Previous', 'aqualuxe'),
        'next_text' => esc_html__('Next', 'aqualuxe'),
    ]);
    
    if ($pagination) {
        echo '<nav class="pagination flex justify-center space-x-2 mt-8">';
        foreach ($pagination as $link) {
            echo '<div class="page-link">' . $link . '</div>';
        }
        echo '</nav>';
    }
}

/**
 * Get featured image background style
 */
function aqualuxe_get_featured_image_bg($post_id = null) {
    if (has_post_thumbnail($post_id)) {
        $image_url = get_the_post_thumbnail_url($post_id, 'full');
        return sprintf('background-image: url(%s);', esc_url($image_url));
    }
    
    return '';
}
