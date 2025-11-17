<?php
/**
 * Helper functions for the theme
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Sanitize HTML content with allowed tags
 *
 * @param string $content Content to sanitize
 * @return string Sanitized content
 */
function aqualuxe_sanitize_html($content) {
    $allowed_html = array(
        'a' => array(
            'href' => array(),
            'title' => array(),
            'class' => array(),
            'target' => array(),
            'rel' => array(),
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'span' => array(
            'class' => array(),
        ),
        'p' => array(
            'class' => array(),
        ),
        'ul' => array(
            'class' => array(),
        ),
        'li' => array(
            'class' => array(),
        ),
        'i' => array(
            'class' => array(),
        ),
        'div' => array(
            'class' => array(),
        ),
    );

    return wp_kses($content, $allowed_html);
}

/**
 * Get current tenant ID for multitenant setup
 *
 * @return int Tenant ID
 */
function aqualuxe_get_tenant_id() {
    // Default to 1 if no tenant system is in place
    $tenant_id = 1;
    
    // Filter to allow custom tenant ID retrieval
    return apply_filters('aqualuxe_tenant_id', $tenant_id);
}

/**
 * Get current language code for multilingual setup
 *
 * @return string Language code
 */
function aqualuxe_get_current_language() {
    $language = 'en'; // Default language
    
    // Check if WPML is active
    if (defined('ICL_LANGUAGE_CODE')) {
        $language = ICL_LANGUAGE_CODE;
    }
    
    // Check if Polylang is active
    if (function_exists('pll_current_language')) {
        $language = pll_current_language();
    }
    
    return apply_filters('aqualuxe_current_language', $language);
}

/**
 * Get current currency code for multi-currency setup
 *
 * @return string Currency code
 */
function aqualuxe_get_current_currency() {
    $currency = 'USD'; // Default currency
    
    // Check if WooCommerce is active
    if (aqualuxe_is_woocommerce_active()) {
        $currency = get_woocommerce_currency();
    }
    
    return apply_filters('aqualuxe_current_currency', $currency);
}

/**
 * Check if dark mode is enabled
 *
 * @return bool True if dark mode is enabled
 */
function aqualuxe_is_dark_mode() {
    // Check user preference from cookie
    $dark_mode = isset($_COOKIE['aqualuxe_dark_mode']) ? $_COOKIE['aqualuxe_dark_mode'] === 'true' : false;
    
    // Allow theme to override
    return apply_filters('aqualuxe_is_dark_mode', $dark_mode);
}

/**
 * Get theme option with default fallback
 *
 * @param string $option_name Option name
 * @param mixed $default Default value
 * @return mixed Option value
 */
function aqualuxe_get_option($option_name, $default = '') {
    $options = get_theme_mod('aqualuxe_options', array());
    
    if (isset($options[$option_name])) {
        return $options[$option_name];
    }
    
    return get_theme_mod($option_name, $default);
}

/**
 * Get page ID by template
 *
 * @param string $template Template file name
 * @return int|bool Page ID or false if not found
 */
function aqualuxe_get_page_by_template($template) {
    $pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => $template
    ));

    if (!empty($pages)) {
        return $pages[0]->ID;
    }
    
    return false;
}

/**
 * Check if current page is a WooCommerce page
 *
 * @return bool True if WooCommerce page
 */
function aqualuxe_is_woocommerce_page() {
    if (!aqualuxe_is_woocommerce_active()) {
        return false;
    }
    
    return (is_woocommerce() || is_cart() || is_checkout() || is_account_page());
}

/**
 * Get SVG icon
 *
 * @param string $icon Icon name
 * @param array $args Icon arguments
 * @return string SVG markup
 */
function aqualuxe_get_icon($icon, $args = array()) {
    // Default arguments
    $defaults = array(
        'class' => '',
        'size' => 24,
    );
    
    $args = wp_parse_args($args, $defaults);
    
    // SVG markup
    $svg = '';
    
    // Icons library
    $icons = array(
        'cart' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>',
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
        'user' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>',
        'heart' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>',
        'menu' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>',
        'close' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
        'chevron-down' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>',
        'chevron-up' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>',
        'sun' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>',
        'moon' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>',
        'globe' => '<svg xmlns="http://www.w3.org/2000/svg" width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>',
    );
    
    // Return icon if exists
    if (array_key_exists($icon, $icons)) {
        $svg = $icons[$icon];
        
        if (!empty($args['class'])) {
            $svg = str_replace('<svg', '<svg class="' . esc_attr($args['class']) . '"', $svg);
        }
    }
    
    return $svg;
}

/**
 * Get post thumbnail with fallback
 *
 * @param int $post_id Post ID
 * @param string $size Image size
 * @return string Image HTML
 */
function aqualuxe_get_post_thumbnail($post_id = null, $size = 'thumbnail') {
    if (null === $post_id) {
        $post_id = get_the_ID();
    }
    
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size);
    }
    
    // Fallback image
    $fallback_url = AQUALUXE_URI . 'assets/dist/images/placeholder.jpg';
    $fallback_url = apply_filters('aqualuxe_thumbnail_fallback', $fallback_url, $post_id, $size);
    
    return '<img src="' . esc_url($fallback_url) . '" alt="' . esc_attr(get_the_title($post_id)) . '" class="wp-post-image" />';
}

/**
 * Get excerpt with custom length
 *
 * @param int $length Excerpt length
 * @param int $post_id Post ID
 * @return string Excerpt
 */
function aqualuxe_get_excerpt($length = 55, $post_id = null) {
    if (null === $post_id) {
        $post_id = get_the_ID();
    }
    
    $post = get_post($post_id);
    
    if (empty($post)) {
        return '';
    }
    
    if (has_excerpt($post_id)) {
        return wp_trim_words(get_the_excerpt($post_id), $length, '...');
    }
    
    return wp_trim_words(wp_strip_all_tags(strip_shortcodes($post->post_content)), $length, '...');
}

/**
 * Get related posts
 *
 * @param int $post_id Post ID
 * @param int $count Number of posts
 * @return array Related posts
 */
function aqualuxe_get_related_posts($post_id = null, $count = 3) {
    if (null === $post_id) {
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
        'category__in' => $category_ids,
        'post__not_in' => array($post_id),
        'posts_per_page' => $count,
        'ignore_sticky_posts' => 1,
    );
    
    return get_posts($args);
}

/**
 * Get social share links
 *
 * @param int $post_id Post ID
 * @return array Social share links
 */
function aqualuxe_get_social_share_links($post_id = null) {
    if (null === $post_id) {
        $post_id = get_the_ID();
    }
    
    $permalink = get_permalink($post_id);
    $title = get_the_title($post_id);
    
    $share_links = array(
        'facebook' => array(
            'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($permalink),
            'label' => __('Share on Facebook', 'aqualuxe'),
        ),
        'twitter' => array(
            'url' => 'https://twitter.com/intent/tweet?url=' . urlencode($permalink) . '&text=' . urlencode($title),
            'label' => __('Share on Twitter', 'aqualuxe'),
        ),
        'linkedin' => array(
            'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($permalink),
            'label' => __('Share on LinkedIn', 'aqualuxe'),
        ),
        'pinterest' => array(
            'url' => 'https://pinterest.com/pin/create/button/?url=' . urlencode($permalink) . '&description=' . urlencode($title),
            'label' => __('Share on Pinterest', 'aqualuxe'),
        ),
        'email' => array(
            'url' => 'mailto:?subject=' . urlencode($title) . '&body=' . urlencode($permalink),
            'label' => __('Share via Email', 'aqualuxe'),
        ),
    );
    
    return apply_filters('aqualuxe_social_share_links', $share_links, $post_id);
}

/**
 * Get breadcrumbs
 *
 * @return array Breadcrumbs
 */
function aqualuxe_get_breadcrumbs() {
    $breadcrumbs = array();
    
    // Home
    $breadcrumbs[] = array(
        'title' => __('Home', 'aqualuxe'),
        'url' => home_url('/'),
    );
    
    // WooCommerce
    if (aqualuxe_is_woocommerce_active() && is_woocommerce()) {
        $shop_page_id = wc_get_page_id('shop');
        
        if ($shop_page_id > 0 && !is_shop()) {
            $breadcrumbs[] = array(
                'title' => get_the_title($shop_page_id),
                'url' => get_permalink($shop_page_id),
            );
        }
        
        if (is_product_category() || is_product_tag()) {
            $current_term = get_queried_object();
            
            if ($current_term && !is_wp_error($current_term)) {
                $breadcrumbs[] = array(
                    'title' => $current_term->name,
                    'url' => get_term_link($current_term),
                );
            }
        } elseif (is_product()) {
            global $post;
            
            $terms = wc_get_product_terms(
                $post->ID,
                'product_cat',
                array(
                    'orderby' => 'parent',
                    'order' => 'DESC',
                )
            );
            
            if ($terms) {
                $main_term = apply_filters('aqualuxe_product_main_category', $terms[0], $terms, $post->ID);
                
                $breadcrumbs[] = array(
                    'title' => $main_term->name,
                    'url' => get_term_link($main_term),
                );
            }
            
            $breadcrumbs[] = array(
                'title' => get_the_title(),
                'url' => '',
            );
        } elseif (is_shop()) {
            $breadcrumbs[] = array(
                'title' => get_the_title($shop_page_id),
                'url' => '',
            );
        }
    } elseif (is_category() || is_tag() || is_tax()) {
        $current_term = get_queried_object();
        
        if ($current_term && !is_wp_error($current_term)) {
            $breadcrumbs[] = array(
                'title' => $current_term->name,
                'url' => get_term_link($current_term),
            );
        }
    } elseif (is_singular()) {
        if (is_singular('post')) {
            $breadcrumbs[] = array(
                'title' => __('Blog', 'aqualuxe'),
                'url' => get_post_type_archive_link('post'),
            );
        }
        
        $breadcrumbs[] = array(
            'title' => get_the_title(),
            'url' => '',
        );
    } elseif (is_post_type_archive()) {
        $breadcrumbs[] = array(
            'title' => post_type_archive_title('', false),
            'url' => '',
        );
    } elseif (is_search()) {
        $breadcrumbs[] = array(
            'title' => sprintf(__('Search results for: %s', 'aqualuxe'), get_search_query()),
            'url' => '',
        );
    } elseif (is_404()) {
        $breadcrumbs[] = array(
            'title' => __('Page not found', 'aqualuxe'),
            'url' => '',
        );
    }
    
    return apply_filters('aqualuxe_breadcrumbs', $breadcrumbs);
}