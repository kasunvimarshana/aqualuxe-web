<?php
/**
 * Template functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get asset URL with cache busting
 *
 * @param string $path Asset path
 * @return string Asset URL
 */
function aqualuxe_asset($path) {
    static $manifest = null;
    
    if (is_null($manifest)) {
        $manifest_path = AQUALUXE_DIR . '/assets/dist/mix-manifest.json';
        $manifest = file_exists($manifest_path) ? json_decode(file_get_contents($manifest_path), true) : array();
    }
    
    if (isset($manifest['/' . $path])) {
        return AQUALUXE_ASSETS_URI . $manifest['/' . $path];
    }
    
    return AQUALUXE_ASSETS_URI . '/' . $path;
}

/**
 * Check if WooCommerce is active
 *
 * @return bool True if WooCommerce is active
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Check if a module is active
 *
 * @param string $module Module ID
 * @return bool True if module is active
 */
function aqualuxe_is_module_active($module) {
    $active_modules = get_option('aqualuxe_active_modules', array());
    return isset($active_modules[$module]) && $active_modules[$module];
}

/**
 * Get theme option
 *
 * @param string $option Option name
 * @param mixed $default Default value
 * @return mixed Option value
 */
function aqualuxe_get_option($option, $default = '') {
    $options = get_option('aqualuxe_options', array());
    return isset($options[$option]) ? $options[$option] : $default;
}

/**
 * Get sidebar position
 *
 * @return string Sidebar position
 */
function aqualuxe_get_sidebar_position() {
    $position = get_theme_mod('aqualuxe_sidebar_position', 'right');
    
    if (is_singular()) {
        $meta_position = get_post_meta(get_the_ID(), '_aqualuxe_sidebar_position', true);
        if (!empty($meta_position) && $meta_position !== 'default') {
            $position = $meta_position;
        }
    }
    
    if (aqualuxe_is_woocommerce_active()) {
        if (is_shop() || is_product_category() || is_product_tag()) {
            $position = get_theme_mod('aqualuxe_shop_sidebar', 'right');
        } elseif (is_product()) {
            $position = get_theme_mod('aqualuxe_product_sidebar', 'none');
        }
    }
    
    return $position;
}

/**
 * Get page layout
 *
 * @return string Page layout
 */
function aqualuxe_get_page_layout() {
    $layout = get_theme_mod('aqualuxe_page_layout', 'normal');
    
    if (is_singular()) {
        $meta_layout = get_post_meta(get_the_ID(), '_aqualuxe_page_layout', true);
        if (!empty($meta_layout) && $meta_layout !== 'default') {
            $layout = $meta_layout;
        }
    }
    
    return $layout;
}

/**
 * Get container class
 *
 * @return string Container class
 */
function aqualuxe_get_container_class() {
    $layout = aqualuxe_get_page_layout();
    
    switch ($layout) {
        case 'full-width':
            return 'container-fluid';
        case 'boxed':
            return 'container-boxed';
        default:
            return 'container';
    }
}

/**
 * Get content class
 *
 * @return string Content class
 */
function aqualuxe_get_content_class() {
    $sidebar_position = aqualuxe_get_sidebar_position();
    
    if ($sidebar_position === 'none') {
        return 'content-full';
    }
    
    return 'content-with-sidebar';
}

/**
 * Get header class
 *
 * @return string Header class
 */
function aqualuxe_get_header_class() {
    $classes = array('site-header');
    
    $header_layout = get_theme_mod('aqualuxe_header_layout', 'default');
    $classes[] = 'header-' . $header_layout;
    
    if (get_theme_mod('aqualuxe_sticky_header', true)) {
        $classes[] = 'sticky-header';
    }
    
    return implode(' ', $classes);
}

/**
 * Get footer class
 *
 * @return string Footer class
 */
function aqualuxe_get_footer_class() {
    $classes = array('site-footer');
    
    $footer_columns = get_theme_mod('aqualuxe_footer_columns', 4);
    $classes[] = 'footer-columns-' . $footer_columns;
    
    return implode(' ', $classes);
}

/**
 * Get post class
 *
 * @param string $class Additional class
 * @return string Post class
 */
function aqualuxe_get_post_class($class = '') {
    $classes = array('post-item');
    
    if (!empty($class)) {
        $classes[] = $class;
    }
    
    $blog_layout = get_theme_mod('aqualuxe_blog_layout', 'grid');
    $classes[] = 'post-' . $blog_layout;
    
    return implode(' ', $classes);
}

/**
 * Get excerpt length
 *
 * @return int Excerpt length
 */
function aqualuxe_get_excerpt_length() {
    return get_theme_mod('aqualuxe_excerpt_length', 55);
}

/**
 * Custom excerpt length
 *
 * @param int $length Excerpt length
 * @return int Modified excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return aqualuxe_get_excerpt_length();
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Custom excerpt more
 *
 * @param string $more Excerpt more
 * @return string Modified excerpt more
 */
function aqualuxe_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Custom excerpt
 *
 * @param int $length Excerpt length
 * @param string $more Excerpt more
 * @return string Custom excerpt
 */
function aqualuxe_custom_excerpt($length = 55, $more = '&hellip;') {
    $excerpt = get_the_excerpt();
    $excerpt = wp_trim_words($excerpt, $length, $more);
    return $excerpt;
}

/**
 * Get social links
 *
 * @return array Social links
 */
function aqualuxe_get_social_links() {
    $social_platforms = array(
        'facebook'  => __('Facebook', 'aqualuxe'),
        'twitter'   => __('Twitter', 'aqualuxe'),
        'instagram' => __('Instagram', 'aqualuxe'),
        'linkedin'  => __('LinkedIn', 'aqualuxe'),
        'youtube'   => __('YouTube', 'aqualuxe'),
        'pinterest' => __('Pinterest', 'aqualuxe'),
        'tiktok'    => __('TikTok', 'aqualuxe'),
    );
    
    $social_links = array();
    
    foreach ($social_platforms as $platform => $label) {
        $url = get_theme_mod('aqualuxe_social_' . $platform, '');
        
        if (!empty($url)) {
            $social_links[$platform] = array(
                'label' => $label,
                'url'   => $url,
            );
        }
    }
    
    return $social_links;
}

/**
 * Get social sharing platforms
 *
 * @return array Social sharing platforms
 */
function aqualuxe_get_social_sharing_platforms() {
    $default_platforms = array('facebook', 'twitter', 'linkedin', 'pinterest', 'email');
    $platforms = get_theme_mod('aqualuxe_social_sharing_platforms', $default_platforms);
    
    if (!is_array($platforms)) {
        $platforms = explode(',', $platforms);
    }
    
    return $platforms;
}

/**
 * Get social sharing links
 *
 * @param int $post_id Post ID
 * @return array Social sharing links
 */
function aqualuxe_get_social_sharing_links($post_id = 0) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post_url = urlencode(get_permalink($post_id));
    $post_title = urlencode(get_the_title($post_id));
    $post_thumbnail = urlencode(get_the_post_thumbnail_url($post_id, 'full'));
    
    $sharing_links = array(
        'facebook' => array(
            'label' => __('Facebook', 'aqualuxe'),
            'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url,
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
        ),
        'twitter' => array(
            'label' => __('Twitter', 'aqualuxe'),
            'url'   => 'https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title,
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>',
        ),
        'linkedin' => array(
            'label' => __('LinkedIn', 'aqualuxe'),
            'url'   => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title,
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-linkedin"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
        ),
        'pinterest' => array(
            'label' => __('Pinterest', 'aqualuxe'),
            'url'   => 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&media=' . $post_thumbnail . '&description=' . $post_title,
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pinterest"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path></svg>',
        ),
        'reddit' => array(
            'label' => __('Reddit', 'aqualuxe'),
            'url'   => 'https://www.reddit.com/submit?url=' . $post_url . '&title=' . $post_title,
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>',
        ),
        'tumblr' => array(
            'label' => __('Tumblr', 'aqualuxe'),
            'url'   => 'https://www.tumblr.com/share/link?url=' . $post_url . '&name=' . $post_title,
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bookmark"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>',
        ),
        'whatsapp' => array(
            'label' => __('WhatsApp', 'aqualuxe'),
            'url'   => 'https://api.whatsapp.com/send?text=' . $post_title . ' ' . $post_url,
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>',
        ),
        'telegram' => array(
            'label' => __('Telegram', 'aqualuxe'),
            'url'   => 'https://t.me/share/url?url=' . $post_url . '&text=' . $post_title,
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-send"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>',
        ),
        'email' => array(
            'label' => __('Email', 'aqualuxe'),
            'url'   => 'mailto:?subject=' . $post_title . '&body=' . $post_url,
            'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>',
        ),
    );
    
    $platforms = aqualuxe_get_social_sharing_platforms();
    $links = array();
    
    foreach ($platforms as $platform) {
        if (isset($sharing_links[$platform])) {
            $links[$platform] = $sharing_links[$platform];
        }
    }
    
    return $links;
}

/**
 * Get related posts
 *
 * @param int $post_id Post ID
 * @param int $number Number of posts
 * @return array Related posts
 */
function aqualuxe_get_related_posts($post_id = 0, $number = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category($post_id);
    
    if (empty($categories)) {
        return array();
    }
    
    $category_ids = array();
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
    
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $number,
        'post__not_in' => array($post_id),
        'category__in' => $category_ids,
        'orderby' => 'rand',
    );
    
    $related_posts = new WP_Query($args);
    
    return $related_posts->posts;
}

/**
 * Get testimonials
 *
 * @param int $number Number of testimonials
 * @param string $orderby Order by
 * @param string $order Order
 * @return array Testimonials
 */
function aqualuxe_get_testimonials($number = 3, $orderby = 'date', $order = 'DESC') {
    $args = array(
        'post_type' => 'aqualuxe_testimonial',
        'post_status' => 'publish',
        'posts_per_page' => $number,
        'orderby' => $orderby,
        'order' => $order,
    );
    
    $testimonials = new WP_Query($args);
    
    return $testimonials->posts;
}

/**
 * Get services
 *
 * @param int $number Number of services
 * @param string $orderby Order by
 * @param string $order Order
 * @param bool $featured Featured only
 * @return array Services
 */
function aqualuxe_get_services($number = -1, $orderby = 'menu_order', $order = 'ASC', $featured = false) {
    $args = array(
        'post_type' => 'aqualuxe_service',
        'post_status' => 'publish',
        'posts_per_page' => $number,
        'orderby' => $orderby,
        'order' => $order,
    );
    
    if ($featured) {
        $args['meta_query'] = array(
            array(
                'key' => '_aqualuxe_service_featured',
                'value' => 'yes',
                'compare' => '=',
            ),
        );
    }
    
    $services = new WP_Query($args);
    
    return $services->posts;
}

/**
 * Get team members
 *
 * @param int $number Number of team members
 * @param string $orderby Order by
 * @param string $order Order
 * @param string $department Department
 * @return array Team members
 */
function aqualuxe_get_team_members($number = -1, $orderby = 'menu_order', $order = 'ASC', $department = '') {
    $args = array(
        'post_type' => 'aqualuxe_team',
        'post_status' => 'publish',
        'posts_per_page' => $number,
        'orderby' => $orderby,
        'order' => $order,
    );
    
    if (!empty($department)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'aqualuxe_team_dept',
                'field' => 'slug',
                'terms' => $department,
            ),
        );
    }
    
    $team_members = new WP_Query($args);
    
    return $team_members->posts;
}

/**
 * Get FAQs
 *
 * @param int $number Number of FAQs
 * @param string $orderby Order by
 * @param string $order Order
 * @param string $category Category
 * @return array FAQs
 */
function aqualuxe_get_faqs($number = -1, $orderby = 'menu_order', $order = 'ASC', $category = '') {
    $args = array(
        'post_type' => 'aqualuxe_faq',
        'post_status' => 'publish',
        'posts_per_page' => $number,
        'orderby' => $orderby,
        'order' => $order,
    );
    
    if (!empty($category)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'aqualuxe_faq_cat',
                'field' => 'slug',
                'terms' => $category,
            ),
        );
    }
    
    $faqs = new WP_Query($args);
    
    return $faqs->posts;
}

/**
 * Get contact form ID
 *
 * @return int Contact form ID
 */
function aqualuxe_get_contact_form_id() {
    // Get the contact form ID from theme options
    $contact_form_id = get_theme_mod('aqualuxe_contact_form_id', 0);
    
    // If no ID is set, try to find the first contact form
    if (!$contact_form_id && function_exists('wpcf7_get_contact_forms')) {
        $forms = wpcf7_get_contact_forms();
        if (!empty($forms)) {
            $first_form = reset($forms);
            $contact_form_id = $first_form->id();
        }
    }
    
    return $contact_form_id;
}

/**
 * Get Google Maps API key
 *
 * @return string Google Maps API key
 */
function aqualuxe_get_google_maps_api_key() {
    return get_theme_mod('aqualuxe_google_maps_api_key', '');
}

/**
 * Get Google Analytics ID
 *
 * @return string Google Analytics ID
 */
function aqualuxe_get_google_analytics_id() {
    return get_theme_mod('aqualuxe_google_analytics_id', '');
}

/**
 * Get Facebook Pixel ID
 *
 * @return string Facebook Pixel ID
 */
function aqualuxe_get_facebook_pixel_id() {
    return get_theme_mod('aqualuxe_facebook_pixel_id', '');
}

/**
 * Get custom CSS
 *
 * @return string Custom CSS
 */
function aqualuxe_get_custom_css() {
    return get_theme_mod('aqualuxe_custom_css', '');
}

/**
 * Get custom JavaScript
 *
 * @return string Custom JavaScript
 */
function aqualuxe_get_custom_js() {
    return get_theme_mod('aqualuxe_custom_js', '');
}

/**
 * Get copyright text
 *
 * @return string Copyright text
 */
function aqualuxe_get_copyright_text() {
    $default = '© ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . __('All rights reserved.', 'aqualuxe');
    return get_theme_mod('aqualuxe_copyright_text', $default);
}

/**
 * Get footer logo
 *
 * @return string Footer logo URL
 */
function aqualuxe_get_footer_logo() {
    return get_theme_mod('aqualuxe_footer_logo', '');
}

/**
 * Get footer columns
 *
 * @return int Footer columns
 */
function aqualuxe_get_footer_columns() {
    return get_theme_mod('aqualuxe_footer_columns', 4);
}

/**
 * Get newsletter shortcode
 *
 * @return string Newsletter shortcode
 */
function aqualuxe_get_newsletter_shortcode() {
    return get_theme_mod('aqualuxe_newsletter_shortcode', '');
}

/**
 * Check if dark mode is enabled
 *
 * @return bool True if dark mode is enabled
 */
function aqualuxe_is_dark_mode_enabled() {
    return aqualuxe_is_module_active('dark-mode');
}

/**
 * Check if multilingual is enabled
 *
 * @return bool True if multilingual is enabled
 */
function aqualuxe_is_multilingual_enabled() {
    return aqualuxe_is_module_active('multilingual');
}

/**
 * Get current language
 *
 * @return string Current language
 */
function aqualuxe_get_current_language() {
    if (function_exists('icl_get_languages')) {
        global $sitepress;
        return $sitepress->get_current_language();
    }
    
    return get_locale();
}

/**
 * Get available languages
 *
 * @return array Available languages
 */
function aqualuxe_get_available_languages() {
    if (function_exists('icl_get_languages')) {
        return icl_get_languages('skip_missing=0&orderby=code');
    }
    
    return array();
}

/**
 * Get current currency
 *
 * @return string Current currency
 */
function aqualuxe_get_current_currency() {
    if (function_exists('get_woocommerce_currency')) {
        return get_woocommerce_currency();
    }
    
    return 'USD';
}

/**
 * Get available currencies
 *
 * @return array Available currencies
 */
function aqualuxe_get_available_currencies() {
    if (function_exists('get_woocommerce_currencies')) {
        return get_woocommerce_currencies();
    }
    
    return array();
}

/**
 * Get page title
 *
 * @return string Page title
 */
function aqualuxe_get_page_title() {
    if (is_home()) {
        if (get_option('page_for_posts', true)) {
            return get_the_title(get_option('page_for_posts', true));
        } else {
            return __('Latest Posts', 'aqualuxe');
        }
    } elseif (is_archive()) {
        if (is_post_type_archive()) {
            return post_type_archive_title('', false);
        } elseif (is_tax() || is_category() || is_tag()) {
            return single_term_title('', false);
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
        }
    } elseif (is_search()) {
        return sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query());
    } elseif (is_404()) {
        return __('Page Not Found', 'aqualuxe');
    } elseif (is_singular()) {
        return get_the_title();
    }
    
    return get_the_title();
}

/**
 * Get page description
 *
 * @return string Page description
 */
function aqualuxe_get_page_description() {
    if (is_home()) {
        if (get_option('page_for_posts', true)) {
            return get_post_field('post_excerpt', get_option('page_for_posts', true));
        }
    } elseif (is_archive()) {
        if (is_tax() || is_category() || is_tag()) {
            return term_description();
        } elseif (is_author()) {
            return get_the_author_meta('description');
        }
    } elseif (is_singular()) {
        return get_the_excerpt();
    }
    
    return '';
}

/**
 * Get page featured image
 *
 * @return string Page featured image URL
 */
function aqualuxe_get_page_featured_image() {
    if (is_singular() && has_post_thumbnail()) {
        return get_the_post_thumbnail_url(get_the_ID(), 'full');
    } elseif (is_home() && get_option('page_for_posts', true)) {
        return get_the_post_thumbnail_url(get_option('page_for_posts', true), 'full');
    }
    
    return '';
}

/**
 * Get breadcrumbs
 *
 * @return array Breadcrumbs
 */
function aqualuxe_get_breadcrumbs() {
    $breadcrumbs = array();
    $breadcrumbs[] = array(
        'text' => __('Home', 'aqualuxe'),
        'url' => home_url('/'),
    );
    
    if (is_home()) {
        $breadcrumbs[] = array(
            'text' => __('Blog', 'aqualuxe'),
            'url' => '',
        );
    } elseif (is_category() || is_single()) {
        if (is_single()) {
            // Get post categories
            $categories = get_the_category();
            if (!empty($categories)) {
                $category = $categories[0];
                $breadcrumbs[] = array(
                    'text' => $category->name,
                    'url' => get_category_link($category->term_id),
                );
            }
            // Add post title
            $breadcrumbs[] = array(
                'text' => get_the_title(),
                'url' => '',
            );
        } else {
            // Category archive
            $breadcrumbs[] = array(
                'text' => single_cat_title('', false),
                'url' => '',
            );
        }
    } elseif (is_page()) {
        // Check if page has parent
        if ($post->post_parent) {
            $ancestors = get_post_ancestors($post->ID);
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor) {
                $breadcrumbs[] = array(
                    'text' => get_the_title($ancestor),
                    'url' => get_permalink($ancestor),
                );
            }
        }
        // Add current page
        $breadcrumbs[] = array(
            'text' => get_the_title(),
            'url' => '',
        );
    } elseif (is_tag()) {
        $breadcrumbs[] = array(
            'text' => single_tag_title('', false),
            'url' => '',
        );
    } elseif (is_author()) {
        $breadcrumbs[] = array(
            'text' => get_the_author(),
            'url' => '',
        );
    } elseif (is_year()) {
        $breadcrumbs[] = array(
            'text' => get_the_date('Y'),
            'url' => '',
        );
    } elseif (is_month()) {
        $breadcrumbs[] = array(
            'text' => get_the_date('F Y'),
            'url' => '',
        );
    } elseif (is_day()) {
        $breadcrumbs[] = array(
            'text' => get_the_date(),
            'url' => '',
        );
    } elseif (is_search()) {
        $breadcrumbs[] = array(
            'text' => sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query()),
            'url' => '',
        );
    } elseif (is_404()) {
        $breadcrumbs[] = array(
            'text' => __('Page Not Found', 'aqualuxe'),
            'url' => '',
        );
    }
    
    // WooCommerce support
    if (aqualuxe_is_woocommerce_active()) {
        if (is_shop()) {
            $breadcrumbs[] = array(
                'text' => __('Shop', 'aqualuxe'),
                'url' => '',
            );
        } elseif (is_product_category()) {
            $breadcrumbs[] = array(
                'text' => __('Shop', 'aqualuxe'),
                'url' => get_permalink(wc_get_page_id('shop')),
            );
            $breadcrumbs[] = array(
                'text' => single_term_title('', false),
                'url' => '',
            );
        } elseif (is_product()) {
            $breadcrumbs[] = array(
                'text' => __('Shop', 'aqualuxe'),
                'url' => get_permalink(wc_get_page_id('shop')),
            );
            $terms = get_the_terms(get_the_ID(), 'product_cat');
            if ($terms && !is_wp_error($terms)) {
                $breadcrumbs[] = array(
                    'text' => $terms[0]->name,
                    'url' => get_term_link($terms[0]),
                );
            }
            $breadcrumbs[] = array(
                'text' => get_the_title(),
                'url' => '',
            );
        }
    }
    
    return $breadcrumbs;
}

/**
 * Get post navigation
 *
 * @return array Post navigation
 */
function aqualuxe_get_post_navigation() {
    $navigation = array();
    
    $prev_post = get_previous_post();
    if (!empty($prev_post)) {
        $navigation['prev'] = array(
            'title' => $prev_post->post_title,
            'url' => get_permalink($prev_post),
        );
    }
    
    $next_post = get_next_post();
    if (!empty($next_post)) {
        $navigation['next'] = array(
            'title' => $next_post->post_title,
            'url' => get_permalink($next_post),
        );
    }
    
    return $navigation;
}

/**
 * Get pagination
 *
 * @param object $query Query object
 * @return array Pagination
 */
function aqualuxe_get_pagination($query = null) {
    global $wp_query;
    
    if ($query === null) {
        $query = $wp_query;
    }
    
    $big = 999999999;
    
    $pagination = paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $query->max_num_pages,
        'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left"><polyline points="15 18 9 12 15 6"></polyline></svg><span class="screen-reader-text">' . esc_html__('Previous', 'aqualuxe') . '</span>',
        'next_text' => '<span class="screen-reader-text">' . esc_html__('Next', 'aqualuxe') . '</span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>',
        'type' => 'array',
    ));
    
    return $pagination;
}

/**
 * Get post meta
 *
 * @param int $post_id Post ID
 * @param string $key Meta key
 * @param bool $single Single value
 * @return mixed Post meta
 */
function aqualuxe_get_post_meta($post_id, $key, $single = true) {
    return get_post_meta($post_id, '_aqualuxe_' . $key, $single);
}

/**
 * Update post meta
 *
 * @param int $post_id Post ID
 * @param string $key Meta key
 * @param mixed $value Meta value
 * @return int|bool Meta ID if the key didn't exist, true on successful update, false on failure
 */
function aqualuxe_update_post_meta($post_id, $key, $value) {
    return update_post_meta($post_id, '_aqualuxe_' . $key, $value);
}

/**
 * Delete post meta
 *
 * @param int $post_id Post ID
 * @param string $key Meta key
 * @param mixed $value Meta value
 * @return bool True on success, false on failure
 */
function aqualuxe_delete_post_meta($post_id, $key, $value = '') {
    return delete_post_meta($post_id, '_aqualuxe_' . $key, $value);
}

/**
 * Get theme option
 *
 * @param string $key Option key
 * @param mixed $default Default value
 * @return mixed Option value
 */
function aqualuxe_get_theme_option($key, $default = '') {
    $options = get_option('aqualuxe_options', array());
    return isset($options[$key]) ? $options[$key] : $default;
}

/**
 * Update theme option
 *
 * @param string $key Option key
 * @param mixed $value Option value
 * @return bool True on success, false on failure
 */
function aqualuxe_update_theme_option($key, $value) {
    $options = get_option('aqualuxe_options', array());
    $options[$key] = $value;
    return update_option('aqualuxe_options', $options);
}

/**
 * Delete theme option
 *
 * @param string $key Option key
 * @return bool True on success, false on failure
 */
function aqualuxe_delete_theme_option($key) {
    $options = get_option('aqualuxe_options', array());
    if (isset($options[$key])) {
        unset($options[$key]);
        return update_option('aqualuxe_options', $options);
    }
    return false;
}

/**
 * Get module option
 *
 * @param string $module Module ID
 * @param string $key Option key
 * @param mixed $default Default value
 * @return mixed Option value
 */
function aqualuxe_get_module_option($module, $key, $default = '') {
    $options = get_option('aqualuxe_module_' . $module, array());
    return isset($options[$key]) ? $options[$key] : $default;
}

/**
 * Update module option
 *
 * @param string $module Module ID
 * @param string $key Option key
 * @param mixed $value Option value
 * @return bool True on success, false on failure
 */
function aqualuxe_update_module_option($module, $key, $value) {
    $options = get_option('aqualuxe_module_' . $module, array());
    $options[$key] = $value;
    return update_option('aqualuxe_module_' . $module, $options);
}

/**
 * Delete module option
 *
 * @param string $module Module ID
 * @param string $key Option key
 * @return bool True on success, false on failure
 */
function aqualuxe_delete_module_option($module, $key) {
    $options = get_option('aqualuxe_module_' . $module, array());
    if (isset($options[$key])) {
        unset($options[$key]);
        return update_option('aqualuxe_module_' . $module, $options);
    }
    return false;
}

/**
 * Get image URL by ID
 *
 * @param int $attachment_id Attachment ID
 * @param string $size Image size
 * @return string Image URL
 */
function aqualuxe_get_image_url($attachment_id, $size = 'full') {
    $image = wp_get_attachment_image_src($attachment_id, $size);
    return $image ? $image[0] : '';
}

/**
 * Get image alt by ID
 *
 * @param int $attachment_id Attachment ID
 * @return string Image alt
 */
function aqualuxe_get_image_alt($attachment_id) {
    return get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
}

/**
 * Get image caption by ID
 *
 * @param int $attachment_id Attachment ID
 * @return string Image caption
 */
function aqualuxe_get_image_caption($attachment_id) {
    $attachment = get_post($attachment_id);
    return $attachment ? $attachment->post_excerpt : '';
}

/**
 * Get image title by ID
 *
 * @param int $attachment_id Attachment ID
 * @return string Image title
 */
function aqualuxe_get_image_title($attachment_id) {
    $attachment = get_post($attachment_id);
    return $attachment ? $attachment->post_title : '';
}

/**
 * Get image description by ID
 *
 * @param int $attachment_id Attachment ID
 * @return string Image description
 */
function aqualuxe_get_image_description($attachment_id) {
    $attachment = get_post($attachment_id);
    return $attachment ? $attachment->post_content : '';
}

/**
 * Get image dimensions by ID
 *
 * @param int $attachment_id Attachment ID
 * @param string $size Image size
 * @return array Image dimensions
 */
function aqualuxe_get_image_dimensions($attachment_id, $size = 'full') {
    $image = wp_get_attachment_image_src($attachment_id, $size);
    return $image ? array('width' => $image[1], 'height' => $image[2]) : array('width' => 0, 'height' => 0);
}

/**
 * Get image HTML by ID
 *
 * @param int $attachment_id Attachment ID
 * @param string $size Image size
 * @param array $attr Image attributes
 * @return string Image HTML
 */
function aqualuxe_get_image_html($attachment_id, $size = 'full', $attr = array()) {
    return wp_get_attachment_image($attachment_id, $size, false, $attr);
}

/**
 * Get SVG icon
 *
 * @param string $icon Icon name
 * @param array $args Icon arguments
 * @return string SVG icon
 */
function aqualuxe_get_svg_icon($icon, $args = array()) {
    $defaults = array(
        'width' => 24,
        'height' => 24,
        'class' => '',
    );
    
    $args = wp_parse_args($args, $defaults);
    
    $svg_icons = array(
        'arrow-right' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right ' . esc_attr($args['class']) . '"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
        'arrow-left' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left ' . esc_attr($args['class']) . '"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
        'chevron-right' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right ' . esc_attr($args['class']) . '"><polyline points="9 18 15 12 9 6"></polyline></svg>',
        'chevron-left' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-left ' . esc_attr($args['class']) . '"><polyline points="15 18 9 12 15 6"></polyline></svg>',
        'chevron-up' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-up ' . esc_attr($args['class']) . '"><polyline points="18 15 12 9 6 15"></polyline></svg>',
        'chevron-down' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down ' . esc_attr($args['class']) . '"><polyline points="6 9 12 15 18 9"></polyline></svg>',
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search ' . esc_attr($args['class']) . '"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
        'user' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user ' . esc_attr($args['class']) . '"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
        'shopping-cart' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart ' . esc_attr($args['class']) . '"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>',
        'heart' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart ' . esc_attr($args['class']) . '"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>',
        'menu' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu ' . esc_attr($args['class']) . '"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>',
        'x' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x ' . esc_attr($args['class']) . '"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
        'mail' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail ' . esc_attr($args['class']) . '"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>',
        'phone' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone ' . esc_attr($args['class']) . '"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>',
        'map-pin' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin ' . esc_attr($args['class']) . '"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
        'calendar' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar ' . esc_attr($args['class']) . '"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
        'clock' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock ' . esc_attr($args['class']) . '"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
        'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook ' . esc_attr($args['class']) . '"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
        'twitter' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter ' . esc_attr($args['class']) . '"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>',
        'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram ' . esc_attr($args['class']) . '"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
        'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-linkedin ' . esc_attr($args['class']) . '"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
        'youtube' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-youtube ' . esc_attr($args['class']) . '"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>',
        'pinterest' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pinterest ' . esc_attr($args['class']) . '"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path></svg>',
        'sun' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun ' . esc_attr($args['class']) . '"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>',
        'moon' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon ' . esc_attr($args['class']) . '"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>',
        'globe' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe ' . esc_attr($args['class']) . '"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>',
        'dollar-sign' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['width']) . '" height="' . esc_attr($args['height']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign ' . esc_attr($args['class']) . '"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
    );
    
    return isset($svg_icons[$icon]) ? $svg_icons[$icon] : '';
}