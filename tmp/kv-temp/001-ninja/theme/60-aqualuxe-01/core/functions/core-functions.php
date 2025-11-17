<?php
/**
 * AquaLuxe Core Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Get theme option
 *
 * @param string $option_name Option name
 * @param mixed  $default     Default value
 * @return mixed Option value
 */
function aqualuxe_get_option($option_name, $default = '') {
    return get_theme_mod($option_name, $default);
}

/**
 * Get theme logo
 *
 * @param string $size Logo size
 * @return string Logo HTML
 */
function aqualuxe_get_logo($size = 'full') {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo = '';
    
    if ($custom_logo_id) {
        $logo_attributes = wp_get_attachment_image_src($custom_logo_id, $size);
        
        if ($logo_attributes) {
            $logo = sprintf(
                '<a href="%1$s" class="custom-logo-link" rel="home" itemprop="url">%2$s</a>',
                esc_url(home_url('/')),
                wp_get_attachment_image(
                    $custom_logo_id,
                    $size,
                    false,
                    [
                        'class'    => 'custom-logo',
                        'itemprop' => 'logo',
                        'alt'      => get_bloginfo('name', 'display'),
                    ]
                )
            );
        }
    }
    
    if (!$logo) {
        $logo = sprintf(
            '<a href="%1$s" class="site-title" rel="home" itemprop="url">%2$s</a>',
            esc_url(home_url('/')),
            esc_html(get_bloginfo('name'))
        );
    }
    
    return $logo;
}

/**
 * Get page title
 *
 * @return string Page title
 */
function aqualuxe_get_page_title() {
    $title = '';
    
    if (is_home()) {
        $title = get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe'));
    } elseif (is_archive()) {
        $title = get_the_archive_title();
    } elseif (is_search()) {
        /* translators: %s: search query */
        $title = sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query());
    } elseif (is_404()) {
        $title = __('Page Not Found', 'aqualuxe');
    } elseif (is_page()) {
        $title = get_the_title();
    } elseif (is_single()) {
        $title = get_the_title();
    }
    
    // WooCommerce titles
    if (aqualuxe_is_woocommerce_active()) {
        if (is_shop()) {
            $title = woocommerce_page_title(false);
        } elseif (is_product_category() || is_product_tag()) {
            $title = single_term_title('', false);
        }
    }
    
    return apply_filters('aqualuxe_page_title', $title);
}

/**
 * Get breadcrumbs
 *
 * @return string Breadcrumbs HTML
 */
function aqualuxe_get_breadcrumbs() {
    // Use WooCommerce breadcrumbs if WooCommerce is active and we're on a WooCommerce page
    if (aqualuxe_is_woocommerce_active() && is_woocommerce()) {
        ob_start();
        woocommerce_breadcrumb();
        return ob_get_clean();
    }
    
    // Custom breadcrumbs for non-WooCommerce pages
    $breadcrumbs = [];
    $breadcrumbs[] = [
        'title' => __('Home', 'aqualuxe'),
        'url'   => home_url('/'),
    ];
    
    if (is_home()) {
        $breadcrumbs[] = [
            'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
            'url'   => '',
        ];
    } elseif (is_category()) {
        $breadcrumbs[] = [
            'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
            'url'   => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => single_cat_title('', false),
            'url'   => '',
        ];
    } elseif (is_tag()) {
        $breadcrumbs[] = [
            'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
            'url'   => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => single_tag_title('', false),
            'url'   => '',
        ];
    } elseif (is_author()) {
        $breadcrumbs[] = [
            'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
            'url'   => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_author(),
            'url'   => '',
        ];
    } elseif (is_year()) {
        $breadcrumbs[] = [
            'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
            'url'   => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('Y'),
            'url'   => '',
        ];
    } elseif (is_month()) {
        $breadcrumbs[] = [
            'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
            'url'   => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('Y'),
            'url'   => get_year_link(get_the_date('Y')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('F'),
            'url'   => '',
        ];
    } elseif (is_day()) {
        $breadcrumbs[] = [
            'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
            'url'   => get_permalink(get_option('page_for_posts')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('Y'),
            'url'   => get_year_link(get_the_date('Y')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('F'),
            'url'   => get_month_link(get_the_date('Y'), get_the_date('m')),
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('j'),
            'url'   => '',
        ];
    } elseif (is_single()) {
        if (get_post_type() === 'post') {
            $breadcrumbs[] = [
                'title' => get_theme_mod('aqualuxe_blog_title', __('Blog', 'aqualuxe')),
                'url'   => get_permalink(get_option('page_for_posts')),
            ];
            
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                $breadcrumbs[] = [
                    'title' => $category->name,
                    'url'   => get_category_link($category->term_id),
                ];
            }
        } else {
            $post_type = get_post_type_object(get_post_type());
            if ($post_type) {
                $breadcrumbs[] = [
                    'title' => $post_type->labels->name,
                    'url'   => get_post_type_archive_link(get_post_type()),
                ];
            }
        }
        
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url'   => '',
        ];
    } elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        if ($ancestors) {
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor) {
                $breadcrumbs[] = [
                    'title' => get_the_title($ancestor),
                    'url'   => get_permalink($ancestor),
                ];
            }
        }
        
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url'   => '',
        ];
    } elseif (is_search()) {
        $breadcrumbs[] = [
            'title' => sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query()),
            'url'   => '',
        ];
    } elseif (is_404()) {
        $breadcrumbs[] = [
            'title' => __('Page Not Found', 'aqualuxe'),
            'url'   => '',
        ];
    }
    
    // Build breadcrumbs HTML
    $html = '<nav class="aqualuxe-breadcrumbs" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">';
    $html .= '<ol class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
    
    foreach ($breadcrumbs as $index => $breadcrumb) {
        $html .= '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
        
        if (!empty($breadcrumb['url'])) {
            $html .= '<a href="' . esc_url($breadcrumb['url']) . '" itemprop="item">';
            $html .= '<span itemprop="name">' . esc_html($breadcrumb['title']) . '</span>';
            $html .= '</a>';
        } else {
            $html .= '<span itemprop="name">' . esc_html($breadcrumb['title']) . '</span>';
        }
        
        $html .= '<meta itemprop="position" content="' . esc_attr($index + 1) . '" />';
        $html .= '</li>';
        
        if ($index < count($breadcrumbs) - 1) {
            $html .= '<li class="breadcrumb-separator">/</li>';
        }
    }
    
    $html .= '</ol>';
    $html .= '</nav>';
    
    return apply_filters('aqualuxe_breadcrumbs', $html);
}

/**
 * Get social links
 *
 * @return array Social links
 */
function aqualuxe_get_social_links() {
    $social_links = [];
    
    $facebook = get_theme_mod('aqualuxe_facebook');
    if ($facebook) {
        $social_links['facebook'] = [
            'url'   => $facebook,
            'label' => __('Facebook', 'aqualuxe'),
            'icon'  => 'fab fa-facebook-f',
        ];
    }
    
    $twitter = get_theme_mod('aqualuxe_twitter');
    if ($twitter) {
        $social_links['twitter'] = [
            'url'   => $twitter,
            'label' => __('Twitter', 'aqualuxe'),
            'icon'  => 'fab fa-twitter',
        ];
    }
    
    $instagram = get_theme_mod('aqualuxe_instagram');
    if ($instagram) {
        $social_links['instagram'] = [
            'url'   => $instagram,
            'label' => __('Instagram', 'aqualuxe'),
            'icon'  => 'fab fa-instagram',
        ];
    }
    
    $youtube = get_theme_mod('aqualuxe_youtube');
    if ($youtube) {
        $social_links['youtube'] = [
            'url'   => $youtube,
            'label' => __('YouTube', 'aqualuxe'),
            'icon'  => 'fab fa-youtube',
        ];
    }
    
    $linkedin = get_theme_mod('aqualuxe_linkedin');
    if ($linkedin) {
        $social_links['linkedin'] = [
            'url'   => $linkedin,
            'label' => __('LinkedIn', 'aqualuxe'),
            'icon'  => 'fab fa-linkedin-in',
        ];
    }
    
    $pinterest = get_theme_mod('aqualuxe_pinterest');
    if ($pinterest) {
        $social_links['pinterest'] = [
            'url'   => $pinterest,
            'label' => __('Pinterest', 'aqualuxe'),
            'icon'  => 'fab fa-pinterest-p',
        ];
    }
    
    return apply_filters('aqualuxe_social_links', $social_links);
}

/**
 * Get contact info
 *
 * @return array Contact info
 */
function aqualuxe_get_contact_info() {
    $contact_info = [];
    
    $phone = get_theme_mod('aqualuxe_phone');
    if ($phone) {
        $contact_info['phone'] = [
            'value' => $phone,
            'label' => __('Phone', 'aqualuxe'),
            'icon'  => 'fas fa-phone',
            'url'   => 'tel:' . preg_replace('/[^0-9+]/', '', $phone),
        ];
    }
    
    $email = get_theme_mod('aqualuxe_email');
    if ($email) {
        $contact_info['email'] = [
            'value' => $email,
            'label' => __('Email', 'aqualuxe'),
            'icon'  => 'fas fa-envelope',
            'url'   => 'mailto:' . $email,
        ];
    }
    
    $address = get_theme_mod('aqualuxe_address');
    if ($address) {
        $contact_info['address'] = [
            'value' => $address,
            'label' => __('Address', 'aqualuxe'),
            'icon'  => 'fas fa-map-marker-alt',
            'url'   => get_theme_mod('aqualuxe_google_maps'),
        ];
    }
    
    $hours = get_theme_mod('aqualuxe_hours');
    if ($hours) {
        $contact_info['hours'] = [
            'value' => $hours,
            'label' => __('Business Hours', 'aqualuxe'),
            'icon'  => 'fas fa-clock',
            'url'   => '',
        ];
    }
    
    return apply_filters('aqualuxe_contact_info', $contact_info);
}

/**
 * Get footer copyright text
 *
 * @return string Footer copyright text
 */
function aqualuxe_get_copyright_text() {
    $copyright = get_theme_mod('aqualuxe_copyright');
    
    if (!$copyright) {
        $copyright = sprintf(
            /* translators: %1$s: Current year, %2$s: Site name */
            __('© %1$s %2$s. All rights reserved.', 'aqualuxe'),
            date('Y'),
            get_bloginfo('name')
        );
    }
    
    return apply_filters('aqualuxe_copyright_text', $copyright);
}

/**
 * Get footer credits
 *
 * @return string Footer credits
 */
function aqualuxe_get_footer_credits() {
    $credits = get_theme_mod('aqualuxe_footer_credits');
    
    if (!$credits) {
        $credits = sprintf(
            /* translators: %1$s: Theme name, %2$s: Theme author */
            __('Powered by %1$s theme by %2$s.', 'aqualuxe'),
            'AquaLuxe',
            '<a href="https://ninjatech.ai" target="_blank" rel="noopener noreferrer">NinjaTech AI</a>'
        );
    }
    
    return apply_filters('aqualuxe_footer_credits', $credits);
}

/**
 * Check if page has sidebar
 *
 * @return bool
 */
function aqualuxe_has_sidebar() {
    // Default sidebar status
    $has_sidebar = true;
    
    // Check if sidebar is disabled for specific page
    if (is_singular()) {
        $sidebar_disabled = get_post_meta(get_the_ID(), '_aqualuxe_disable_sidebar', true);
        
        if ($sidebar_disabled) {
            $has_sidebar = false;
        }
    }
    
    // Check if sidebar is disabled for specific page template
    if (is_page_template('templates/full-width.php') || is_page_template('templates/no-sidebar.php')) {
        $has_sidebar = false;
    }
    
    // Check if sidebar is disabled for WooCommerce pages
    if (aqualuxe_is_woocommerce_active()) {
        if (is_product()) {
            $has_sidebar = get_theme_mod('aqualuxe_product_sidebar', false);
        } elseif (is_shop() || is_product_category() || is_product_tag()) {
            $has_sidebar = get_theme_mod('aqualuxe_shop_sidebar', true);
        } elseif (is_cart() || is_checkout() || is_account_page()) {
            $has_sidebar = false;
        }
    }
    
    return apply_filters('aqualuxe_has_sidebar', $has_sidebar);
}

/**
 * Get sidebar position
 *
 * @return string Sidebar position (left or right)
 */
function aqualuxe_get_sidebar_position() {
    $position = get_theme_mod('aqualuxe_sidebar_position', 'right');
    
    // Check if sidebar position is overridden for specific page
    if (is_singular()) {
        $page_sidebar_position = get_post_meta(get_the_ID(), '_aqualuxe_sidebar_position', true);
        
        if ($page_sidebar_position && $page_sidebar_position !== 'default') {
            $position = $page_sidebar_position;
        }
    }
    
    // Check if sidebar position is overridden for WooCommerce pages
    if (aqualuxe_is_woocommerce_active()) {
        if (is_shop() || is_product_category() || is_product_tag()) {
            $position = get_theme_mod('aqualuxe_shop_sidebar_position', 'left');
        }
    }
    
    return apply_filters('aqualuxe_sidebar_position', $position);
}

/**
 * Get page layout
 *
 * @return string Page layout (full-width, boxed, or contained)
 */
function aqualuxe_get_page_layout() {
    $layout = get_theme_mod('aqualuxe_page_layout', 'contained');
    
    // Check if layout is overridden for specific page
    if (is_singular()) {
        $page_layout = get_post_meta(get_the_ID(), '_aqualuxe_page_layout', true);
        
        if ($page_layout && $page_layout !== 'default') {
            $layout = $page_layout;
        }
    }
    
    // Check if layout is overridden for specific page template
    if (is_page_template('templates/full-width.php')) {
        $layout = 'full-width';
    }
    
    return apply_filters('aqualuxe_page_layout', $layout);
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
            $container_class = 'container-fluid';
            break;
        case 'boxed':
            $container_class = 'container boxed';
            break;
        case 'contained':
        default:
            $container_class = 'container';
            break;
    }
    
    return apply_filters('aqualuxe_container_class', $container_class);
}

/**
 * Get content class
 *
 * @return string Content class
 */
function aqualuxe_get_content_class() {
    $has_sidebar = aqualuxe_has_sidebar();
    $sidebar_position = aqualuxe_get_sidebar_position();
    
    if ($has_sidebar) {
        $content_class = 'col-lg-9';
        
        if ($sidebar_position === 'left') {
            $content_class .= ' order-lg-2';
        }
    } else {
        $content_class = 'col-lg-12';
    }
    
    return apply_filters('aqualuxe_content_class', $content_class);
}

/**
 * Get sidebar class
 *
 * @return string Sidebar class
 */
function aqualuxe_get_sidebar_class() {
    $sidebar_position = aqualuxe_get_sidebar_position();
    $sidebar_class = 'col-lg-3';
    
    if ($sidebar_position === 'left') {
        $sidebar_class .= ' order-lg-1';
    }
    
    return apply_filters('aqualuxe_sidebar_class', $sidebar_class);
}

/**
 * Get header layout
 *
 * @return string Header layout
 */
function aqualuxe_get_header_layout() {
    return get_theme_mod('aqualuxe_header_layout', 'default');
}

/**
 * Get footer layout
 *
 * @return string Footer layout
 */
function aqualuxe_get_footer_layout() {
    return get_theme_mod('aqualuxe_footer_layout', 'default');
}

/**
 * Get blog layout
 *
 * @return string Blog layout
 */
function aqualuxe_get_blog_layout() {
    return get_theme_mod('aqualuxe_blog_layout', 'standard');
}

/**
 * Get shop layout
 *
 * @return string Shop layout
 */
function aqualuxe_get_shop_layout() {
    return get_theme_mod('aqualuxe_shop_layout', 'grid');
}

/**
 * Get product layout
 *
 * @return string Product layout
 */
function aqualuxe_get_product_layout() {
    return get_theme_mod('aqualuxe_product_layout', 'standard');
}

/**
 * Get theme color mode
 *
 * @return string Color mode (light or dark)
 */
function aqualuxe_get_color_mode() {
    return get_theme_mod('aqualuxe_color_mode', 'light');
}

/**
 * Check if dark mode is enabled
 *
 * @return bool
 */
function aqualuxe_is_dark_mode() {
    return aqualuxe_get_color_mode() === 'dark';
}

/**
 * Get body class
 *
 * @return string Body class
 */
function aqualuxe_get_body_class() {
    $classes = [];
    
    // Add color mode class
    $classes[] = 'aqualuxe-' . aqualuxe_get_color_mode() . '-mode';
    
    // Add page layout class
    $classes[] = 'aqualuxe-layout-' . aqualuxe_get_page_layout();
    
    // Add header layout class
    $classes[] = 'aqualuxe-header-' . aqualuxe_get_header_layout();
    
    // Add footer layout class
    $classes[] = 'aqualuxe-footer-' . aqualuxe_get_footer_layout();
    
    // Add sidebar position class
    if (aqualuxe_has_sidebar()) {
        $classes[] = 'aqualuxe-sidebar-' . aqualuxe_get_sidebar_position();
    } else {
        $classes[] = 'aqualuxe-no-sidebar';
    }
    
    // Add blog layout class
    if (is_home() || is_archive() || is_search()) {
        $classes[] = 'aqualuxe-blog-' . aqualuxe_get_blog_layout();
    }
    
    // Add WooCommerce classes
    if (aqualuxe_is_woocommerce_active()) {
        if (is_shop() || is_product_category() || is_product_tag()) {
            $classes[] = 'aqualuxe-shop-' . aqualuxe_get_shop_layout();
        } elseif (is_product()) {
            $classes[] = 'aqualuxe-product-' . aqualuxe_get_product_layout();
        }
    }
    
    return implode(' ', $classes);
}