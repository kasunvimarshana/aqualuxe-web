<?php
/**
 * AquaLuxe Template Helper Functions
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Get template part
 *
 * @param string $slug Template slug
 * @param string $name Template name
 * @param array $args Template arguments
 */
function aqualuxe_get_template_part($slug, $name = null, $args = []) {
    // Extract args to make them available in the template
    if (!empty($args) && is_array($args)) {
        extract($args);
    }
    
    // Look for template in theme directory
    $template = locate_template([
        "{$slug}-{$name}.php",
        "templates/{$slug}-{$name}.php",
        "templates/parts/{$slug}-{$name}.php",
    ]);
    
    // If template not found in theme, check in modules
    if (!$template && aqualuxe_is_module_active($slug)) {
        $module = aqualuxe_get_module($slug);
        $module_template = $module->get_dir() . "templates/{$name}.php";
        
        if (file_exists($module_template)) {
            $template = $module_template;
        }
    }
    
    // Allow plugins/themes to override template
    $template = apply_filters('aqualuxe_get_template_part', $template, $slug, $name, $args);
    
    // If template exists, include it
    if ($template) {
        include $template;
    }
}

/**
 * Get template
 *
 * @param string $template_name Template name
 * @param array $args Template arguments
 * @param string $template_path Template path
 * @param string $default_path Default path
 */
function aqualuxe_get_template($template_name, $args = [], $template_path = '', $default_path = '') {
    // Extract args to make them available in the template
    if (!empty($args) && is_array($args)) {
        extract($args);
    }
    
    // Set default path if not provided
    if (!$default_path) {
        $default_path = AQUALUXE_TEMPLATES_DIR;
    }
    
    // Look for template in theme directory
    $template = locate_template([
        trailingslashit($template_path) . $template_name,
        $template_name,
    ]);
    
    // Get default template if not found in theme
    if (!$template) {
        $template = $default_path . $template_name;
    }
    
    // Allow plugins/themes to override template
    $template = apply_filters('aqualuxe_get_template', $template, $template_name, $args, $template_path, $default_path);
    
    // If template exists, include it
    if (file_exists($template)) {
        include $template;
    }
}

/**
 * Get WooCommerce template
 *
 * @param string $template_name Template name
 * @param array $args Template arguments
 * @param string $template_path Template path
 * @param string $default_path Default path
 */
function aqualuxe_get_woocommerce_template($template_name, $args = [], $template_path = '', $default_path = '') {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    // Set default path if not provided
    if (!$default_path) {
        $default_path = AQUALUXE_DIR . 'woocommerce/';
    }
    
    // Get WooCommerce template
    wc_get_template($template_name, $args, $template_path, $default_path);
}

/**
 * Get header
 *
 * @param string $name Header name
 * @param array $args Header arguments
 */
function aqualuxe_get_header($name = null, $args = []) {
    // Set header name
    $name = $name ? $name : 'default';
    
    // Get header template
    aqualuxe_get_template_part('header', $name, $args);
}

/**
 * Get footer
 *
 * @param string $name Footer name
 * @param array $args Footer arguments
 */
function aqualuxe_get_footer($name = null, $args = []) {
    // Set footer name
    $name = $name ? $name : 'default';
    
    // Get footer template
    aqualuxe_get_template_part('footer', $name, $args);
}

/**
 * Get sidebar
 *
 * @param string $name Sidebar name
 * @param array $args Sidebar arguments
 */
function aqualuxe_get_sidebar($name = null, $args = []) {
    // Set sidebar name
    $name = $name ? $name : 'default';
    
    // Get sidebar template
    aqualuxe_get_template_part('sidebar', $name, $args);
}

/**
 * Get page title
 */
function aqualuxe_page_title() {
    // Check if WooCommerce is active and we're on a WooCommerce page
    if (aqualuxe_is_woocommerce_active() && is_woocommerce()) {
        woocommerce_page_title();
        return;
    }
    
    // Archive
    if (is_archive()) {
        if (is_category()) {
            /* translators: %s: category name */
            printf(esc_html__('Category: %s', 'aqualuxe'), single_cat_title('', false));
        } elseif (is_tag()) {
            /* translators: %s: tag name */
            printf(esc_html__('Tag: %s', 'aqualuxe'), single_tag_title('', false));
        } elseif (is_author()) {
            /* translators: %s: author name */
            printf(esc_html__('Author: %s', 'aqualuxe'), get_the_author());
        } elseif (is_year()) {
            /* translators: %s: year */
            printf(esc_html__('Year: %s', 'aqualuxe'), get_the_date(_x('Y', 'yearly archives date format', 'aqualuxe')));
        } elseif (is_month()) {
            /* translators: %s: month */
            printf(esc_html__('Month: %s', 'aqualuxe'), get_the_date(_x('F Y', 'monthly archives date format', 'aqualuxe')));
        } elseif (is_day()) {
            /* translators: %s: day */
            printf(esc_html__('Day: %s', 'aqualuxe'), get_the_date(_x('F j, Y', 'daily archives date format', 'aqualuxe')));
        } elseif (is_post_type_archive()) {
            post_type_archive_title();
        } elseif (is_tax()) {
            single_term_title();
        } else {
            esc_html_e('Archives', 'aqualuxe');
        }
    } elseif (is_search()) {
        /* translators: %s: search query */
        printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
    } elseif (is_404()) {
        esc_html_e('Page Not Found', 'aqualuxe');
    } elseif (is_home()) {
        if (is_front_page()) {
            esc_html_e('Latest Posts', 'aqualuxe');
        } else {
            single_post_title();
        }
    } else {
        the_title();
    }
}

/**
 * Get breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    // Check if WooCommerce is active and we're on a WooCommerce page
    if (aqualuxe_is_woocommerce_active() && is_woocommerce()) {
        woocommerce_breadcrumb();
        return;
    }
    
    // Get breadcrumbs template
    aqualuxe_get_template_part('breadcrumbs', 'default');
}

/**
 * Get pagination
 */
function aqualuxe_pagination() {
    // Check if WooCommerce is active and we're on a WooCommerce page
    if (aqualuxe_is_woocommerce_active() && is_woocommerce()) {
        woocommerce_pagination();
        return;
    }
    
    // Get pagination template
    aqualuxe_get_template_part('pagination', 'default');
}

/**
 * Get post meta
 *
 * @param int $post_id Post ID
 * @param string $key Meta key
 * @param bool $single Return single value
 * @return mixed
 */
function aqualuxe_get_post_meta($post_id, $key, $single = true) {
    return get_post_meta($post_id, $key, $single);
}

/**
 * Get term meta
 *
 * @param int $term_id Term ID
 * @param string $key Meta key
 * @param bool $single Return single value
 * @return mixed
 */
function aqualuxe_get_term_meta($term_id, $key, $single = true) {
    return get_term_meta($term_id, $key, $single);
}

/**
 * Get user meta
 *
 * @param int $user_id User ID
 * @param string $key Meta key
 * @param bool $single Return single value
 * @return mixed
 */
function aqualuxe_get_user_meta($user_id, $key, $single = true) {
    return get_user_meta($user_id, $key, $single);
}

/**
 * Get option
 *
 * @param string $option Option name
 * @param mixed $default Default value
 * @return mixed
 */
function aqualuxe_get_option($option, $default = false) {
    return get_option($option, $default);
}

/**
 * Get image URL
 *
 * @param int $attachment_id Attachment ID
 * @param string $size Image size
 * @return string
 */
function aqualuxe_get_image_url($attachment_id, $size = 'full') {
    $image = wp_get_attachment_image_src($attachment_id, $size);
    return $image ? $image[0] : '';
}

/**
 * Get image alt
 *
 * @param int $attachment_id Attachment ID
 * @return string
 */
function aqualuxe_get_image_alt($attachment_id) {
    return get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
}

/**
 * Get image caption
 *
 * @param int $attachment_id Attachment ID
 * @return string
 */
function aqualuxe_get_image_caption($attachment_id) {
    $attachment = get_post($attachment_id);
    return $attachment ? $attachment->post_excerpt : '';
}

/**
 * Get image description
 *
 * @param int $attachment_id Attachment ID
 * @return string
 */
function aqualuxe_get_image_description($attachment_id) {
    $attachment = get_post($attachment_id);
    return $attachment ? $attachment->post_content : '';
}

/**
 * Get image title
 *
 * @param int $attachment_id Attachment ID
 * @return string
 */
function aqualuxe_get_image_title($attachment_id) {
    $attachment = get_post($attachment_id);
    return $attachment ? $attachment->post_title : '';
}

/**
 * Get post thumbnail URL
 *
 * @param int $post_id Post ID
 * @param string $size Image size
 * @return string
 */
function aqualuxe_get_post_thumbnail_url($post_id = null, $size = 'full') {
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, $size);
    }
    
    return '';
}

/**
 * Get post excerpt
 *
 * @param int $post_id Post ID
 * @param int $length Excerpt length
 * @param string $more More text
 * @return string
 */
function aqualuxe_get_excerpt($post_id = null, $length = 55, $more = '&hellip;') {
    $post = get_post($post_id);
    
    if (!$post) {
        return '';
    }
    
    if (has_excerpt($post->ID)) {
        return wp_trim_words(get_the_excerpt($post->ID), $length, $more);
    }
    
    return wp_trim_words(strip_shortcodes($post->post_content), $length, $more);
}

/**
 * Get post author
 *
 * @param int $post_id Post ID
 * @return WP_User
 */
function aqualuxe_get_post_author($post_id = null) {
    $post = get_post($post_id);
    
    if (!$post) {
        return null;
    }
    
    return get_user_by('id', $post->post_author);
}

/**
 * Get post date
 *
 * @param int $post_id Post ID
 * @param string $format Date format
 * @return string
 */
function aqualuxe_get_post_date($post_id = null, $format = '') {
    $post = get_post($post_id);
    
    if (!$post) {
        return '';
    }
    
    if (!$format) {
        $format = get_option('date_format');
    }
    
    return get_the_date($format, $post->ID);
}

/**
 * Get post modified date
 *
 * @param int $post_id Post ID
 * @param string $format Date format
 * @return string
 */
function aqualuxe_get_post_modified_date($post_id = null, $format = '') {
    $post = get_post($post_id);
    
    if (!$post) {
        return '';
    }
    
    if (!$format) {
        $format = get_option('date_format');
    }
    
    return get_the_modified_date($format, $post->ID);
}

/**
 * Get post categories
 *
 * @param int $post_id Post ID
 * @return array
 */
function aqualuxe_get_post_categories($post_id = null) {
    $post = get_post($post_id);
    
    if (!$post) {
        return [];
    }
    
    return get_the_category($post->ID);
}

/**
 * Get post tags
 *
 * @param int $post_id Post ID
 * @return array
 */
function aqualuxe_get_post_tags($post_id = null) {
    $post = get_post($post_id);
    
    if (!$post) {
        return [];
    }
    
    return get_the_tags($post->ID);
}

/**
 * Get post comments count
 *
 * @param int $post_id Post ID
 * @return int
 */
function aqualuxe_get_post_comments_count($post_id = null) {
    $post = get_post($post_id);
    
    if (!$post) {
        return 0;
    }
    
    return get_comments_number($post->ID);
}

/**
 * Get post views count
 *
 * @param int $post_id Post ID
 * @return int
 */
function aqualuxe_get_post_views_count($post_id = null) {
    $post = get_post($post_id);
    
    if (!$post) {
        return 0;
    }
    
    $count = get_post_meta($post->ID, '_aqualuxe_views_count', true);
    
    return $count ? absint($count) : 0;
}

/**
 * Set post views count
 *
 * @param int $post_id Post ID
 * @param int $count Count
 */
function aqualuxe_set_post_views_count($post_id = null, $count = 0) {
    $post = get_post($post_id);
    
    if (!$post) {
        return;
    }
    
    update_post_meta($post->ID, '_aqualuxe_views_count', absint($count));
}

/**
 * Increment post views count
 *
 * @param int $post_id Post ID
 */
function aqualuxe_increment_post_views_count($post_id = null) {
    $post = get_post($post_id);
    
    if (!$post) {
        return;
    }
    
    $count = aqualuxe_get_post_views_count($post->ID);
    $count++;
    
    aqualuxe_set_post_views_count($post->ID, $count);
}

/**
 * Get related posts
 *
 * @param int $post_id Post ID
 * @param int $count Number of posts
 * @return array
 */
function aqualuxe_get_related_posts($post_id = null, $count = 3) {
    $post = get_post($post_id);
    
    if (!$post) {
        return [];
    }
    
    $args = [
        'post_type'      => $post->post_type,
        'post_status'    => 'publish',
        'posts_per_page' => $count,
        'post__not_in'   => [$post->ID],
    ];
    
    // Get related by category
    $categories = get_the_category($post->ID);
    
    if (!empty($categories)) {
        $category_ids = [];
        
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }
        
        $args['category__in'] = $category_ids;
    }
    
    return get_posts($args);
}

/**
 * Get social share links
 *
 * @param int $post_id Post ID
 * @return array
 */
function aqualuxe_get_social_share_links($post_id = null) {
    $post = get_post($post_id);
    
    if (!$post) {
        return [];
    }
    
    $url = get_permalink($post->ID);
    $title = get_the_title($post->ID);
    $thumbnail = aqualuxe_get_post_thumbnail_url($post->ID, 'large');
    
    $links = [
        'facebook' => [
            'url'  => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url),
            'icon' => 'fab fa-facebook-f',
            'name' => __('Facebook', 'aqualuxe'),
        ],
        'twitter' => [
            'url'  => 'https://twitter.com/intent/tweet?url=' . urlencode($url) . '&text=' . urlencode($title),
            'icon' => 'fab fa-twitter',
            'name' => __('Twitter', 'aqualuxe'),
        ],
        'linkedin' => [
            'url'  => 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($url) . '&title=' . urlencode($title),
            'icon' => 'fab fa-linkedin-in',
            'name' => __('LinkedIn', 'aqualuxe'),
        ],
        'pinterest' => [
            'url'  => 'https://pinterest.com/pin/create/button/?url=' . urlencode($url) . '&media=' . urlencode($thumbnail) . '&description=' . urlencode($title),
            'icon' => 'fab fa-pinterest-p',
            'name' => __('Pinterest', 'aqualuxe'),
        ],
        'email' => [
            'url'  => 'mailto:?subject=' . urlencode($title) . '&body=' . urlencode($url),
            'icon' => 'fas fa-envelope',
            'name' => __('Email', 'aqualuxe'),
        ],
    ];
    
    return apply_filters('aqualuxe_social_share_links', $links, $post->ID);
}