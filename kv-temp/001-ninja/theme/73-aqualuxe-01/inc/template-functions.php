<?php
/**
 * Template Functions
 * 
 * Core template functions for the AquaLuxe theme
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SEO Functions
 */

/**
 * Get meta description for current page
 */
function aqualuxe_get_meta_description() {
    if (is_singular()) {
        $post = get_post();
        if ($post && !empty($post->post_excerpt)) {
            return wp_strip_all_tags($post->post_excerpt);
        } elseif ($post && !empty($post->post_content)) {
            return wp_trim_words(wp_strip_all_tags($post->post_content), 20);
        }
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        if ($term && !empty($term->description)) {
            return wp_strip_all_tags($term->description);
        }
    } elseif (is_author()) {
        $author = get_queried_object();
        if ($author) {
            return sprintf(esc_html__('Author archive for %s', 'aqualuxe'), $author->display_name);
        }
    } elseif (is_home()) {
        return get_bloginfo('description');
    }
    
    return get_bloginfo('description');
}

/**
 * Get meta keywords for current page
 */
function aqualuxe_get_meta_keywords() {
    $keywords = [];
    
    if (is_singular()) {
        $post_id = get_the_ID();
        $tags = get_the_tags($post_id);
        if ($tags) {
            foreach ($tags as $tag) {
                $keywords[] = $tag->name;
            }
        }
        
        $categories = get_the_category($post_id);
        if ($categories) {
            foreach ($categories as $category) {
                $keywords[] = $category->name;
            }
        }
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        if ($term) {
            $keywords[] = $term->name;
        }
    }
    
    // Add default keywords
    $default_keywords = [
        'aquarium',
        'fish',
        'aquatic',
        'luxury',
        'premium',
        'ornamental fish',
        'aquascaping',
        'marine aquarium',
        'freshwater aquarium'
    ];
    
    $keywords = array_merge($keywords, $default_keywords);
    
    return implode(', ', array_unique($keywords));
}

/**
 * Get Open Graph title
 */
function aqualuxe_get_og_title() {
    if (is_singular()) {
        return get_the_title();
    } elseif (is_category()) {
        return sprintf(esc_html__('Category: %s', 'aqualuxe'), single_cat_title('', false));
    } elseif (is_tag()) {
        return sprintf(esc_html__('Tag: %s', 'aqualuxe'), single_tag_title('', false));
    } elseif (is_author()) {
        return sprintf(esc_html__('Author: %s', 'aqualuxe'), get_the_author());
    } elseif (is_year()) {
        return sprintf(esc_html__('Year: %s', 'aqualuxe'), get_the_date('Y'));
    } elseif (is_month()) {
        return sprintf(esc_html__('Month: %s', 'aqualuxe'), get_the_date('F Y'));
    } elseif (is_day()) {
        return sprintf(esc_html__('Day: %s', 'aqualuxe'), get_the_date());
    } elseif (is_search()) {
        return sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), get_search_query());
    } elseif (is_404()) {
        return esc_html__('Page Not Found', 'aqualuxe');
    }
    
    return get_bloginfo('name') . ' - ' . get_bloginfo('description');
}

/**
 * Get Open Graph type
 */
function aqualuxe_get_og_type() {
    if (is_singular('post')) {
        return 'article';
    } elseif (is_singular('product')) {
        return 'product';
    } elseif (is_singular()) {
        return 'website';
    }
    
    return 'website';
}

/**
 * Get canonical URL
 */
function aqualuxe_get_canonical_url() {
    global $wp;
    return home_url(add_query_arg([], $wp->request));
}

/**
 * Get Open Graph image
 */
function aqualuxe_get_og_image() {
    if (is_singular() && has_post_thumbnail()) {
        return get_the_post_thumbnail_url(null, 'large');
    }
    
    $site_logo = get_theme_mod('custom_logo');
    if ($site_logo) {
        $logo_url = wp_get_attachment_image_url($site_logo, 'large');
        if ($logo_url) {
            return $logo_url;
        }
    }
    
    // Default fallback image
    return AQUALUXE_THEME_URI . '/assets/src/images/og-default.jpg';
}

/**
 * Get schema markup for current page
 */
function aqualuxe_get_schema_markup() {
    $schema = [
        '@context' => 'https://schema.org',
    ];
    
    if (is_singular('post')) {
        $post = get_post();
        $schema['@type'] = 'BlogPosting';
        $schema['headline'] = get_the_title();
        $schema['description'] = aqualuxe_get_meta_description();
        $schema['datePublished'] = get_the_date('c');
        $schema['dateModified'] = get_the_modified_date('c');
        $schema['author'] = [
            '@type' => 'Person',
            'name' => get_the_author_meta('display_name'),
        ];
        $schema['publisher'] = [
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => aqualuxe_get_og_image(),
            ],
        ];
        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(null, 'large');
        }
    } elseif (is_singular('product') && class_exists('WooCommerce')) {
        global $product;
        if ($product) {
            $schema['@type'] = 'Product';
            $schema['name'] = $product->get_name();
            $schema['description'] = wp_strip_all_tags($product->get_description());
            $schema['sku'] = $product->get_sku();
            $schema['offers'] = [
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            ];
            if ($product->get_image_id()) {
                $schema['image'] = wp_get_attachment_url($product->get_image_id());
            }
        }
    } elseif (is_singular('aqualuxe_service')) {
        $post = get_post();
        $schema['@type'] = 'Service';
        $schema['name'] = get_the_title();
        $schema['description'] = aqualuxe_get_meta_description();
        $schema['provider'] = [
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
        ];
    } elseif (is_home() || is_front_page()) {
        $schema['@type'] = 'WebSite';
        $schema['name'] = get_bloginfo('name');
        $schema['description'] = get_bloginfo('description');
        $schema['url'] = home_url();
        $schema['potentialAction'] = [
            '@type' => 'SearchAction',
            'target' => home_url('/?s={search_term_string}'),
            'query-input' => 'required name=search_term_string',
        ];
    } else {
        $schema['@type'] = 'WebPage';
        $schema['name'] = aqualuxe_get_og_title();
        $schema['description'] = aqualuxe_get_meta_description();
    }
    
    return $schema;
}

/**
 * Navigation Functions
 */

/**
 * Default menu fallback
 */
function aqualuxe_default_menu() {
    echo '<ul id="primary-menu" class="menu flex items-center space-x-8">';
    echo '<li><a href="' . esc_url(home_url('/')) . '" class="hover:text-primary-600 transition-colors">' . esc_html__('Home', 'aqualuxe') . '</a></li>';
    
    if (aqualuxe_is_woocommerce_active()) {
        echo '<li><a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="hover:text-primary-600 transition-colors">' . esc_html__('Shop', 'aqualuxe') . '</a></li>';
    }
    
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('services'))) . '" class="hover:text-primary-600 transition-colors">' . esc_html__('Services', 'aqualuxe') . '</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('about'))) . '" class="hover:text-primary-600 transition-colors">' . esc_html__('About', 'aqualuxe') . '</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '" class="hover:text-primary-600 transition-colors">' . esc_html__('Blog', 'aqualuxe') . '</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('contact'))) . '" class="hover:text-primary-600 transition-colors">' . esc_html__('Contact', 'aqualuxe') . '</a></li>';
    echo '</ul>';
}

/**
 * Default mobile menu fallback
 */
function aqualuxe_default_mobile_menu() {
    echo '<ul id="mobile-menu" class="menu flex flex-col space-y-2">';
    echo '<li><a href="' . esc_url(home_url('/')) . '" class="block py-2 hover:text-primary-600 transition-colors">' . esc_html__('Home', 'aqualuxe') . '</a></li>';
    
    if (aqualuxe_is_woocommerce_active()) {
        echo '<li><a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="block py-2 hover:text-primary-600 transition-colors">' . esc_html__('Shop', 'aqualuxe') . '</a></li>';
    }
    
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('services'))) . '" class="block py-2 hover:text-primary-600 transition-colors">' . esc_html__('Services', 'aqualuxe') . '</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('about'))) . '" class="block py-2 hover:text-primary-600 transition-colors">' . esc_html__('About', 'aqualuxe') . '</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '" class="block py-2 hover:text-primary-600 transition-colors">' . esc_html__('Blog', 'aqualuxe') . '</a></li>';
    echo '<li><a href="' . esc_url(get_permalink(get_page_by_path('contact'))) . '" class="block py-2 hover:text-primary-600 transition-colors">' . esc_html__('Contact', 'aqualuxe') . '</a></li>';
    echo '</ul>';
}

/**
 * Breadcrumb navigation
 */
function aqualuxe_breadcrumbs() {
    if (is_front_page()) {
        return;
    }
    
    $separator = '<svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"></path></svg>';
    
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
    echo '<ol class="flex items-center space-x-1 text-sm">';
    echo '<li><a href="' . esc_url(home_url('/')) . '" class="text-primary-600 hover:text-primary-700">' . esc_html__('Home', 'aqualuxe') . '</a></li>';
    
    if (is_category() || is_single()) {
        echo '<li>' . $separator . '</li>';
        
        if (is_single()) {
            $category = get_the_category();
            if ($category) {
                echo '<li><a href="' . esc_url(get_category_link($category[0]->term_id)) . '" class="text-primary-600 hover:text-primary-700">' . esc_html($category[0]->name) . '</a></li>';
                echo '<li>' . $separator . '</li>';
            }
            echo '<li class="text-gray-600">' . esc_html(get_the_title()) . '</li>';
        } else {
            echo '<li class="text-gray-600">' . esc_html(single_cat_title('', false)) . '</li>';
        }
    } elseif (is_page()) {
        echo '<li>' . $separator . '</li>';
        echo '<li class="text-gray-600">' . esc_html(get_the_title()) . '</li>';
    } elseif (is_search()) {
        echo '<li>' . $separator . '</li>';
        echo '<li class="text-gray-600">' . sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), get_search_query()) . '</li>';
    } elseif (is_tag()) {
        echo '<li>' . $separator . '</li>';
        echo '<li class="text-gray-600">' . sprintf(esc_html__('Tag: %s', 'aqualuxe'), single_tag_title('', false)) . '</li>';
    } elseif (is_author()) {
        echo '<li>' . $separator . '</li>';
        echo '<li class="text-gray-600">' . sprintf(esc_html__('Author: %s', 'aqualuxe'), get_the_author()) . '</li>';
    } elseif (is_day()) {
        echo '<li>' . $separator . '</li>';
        echo '<li class="text-gray-600">' . sprintf(esc_html__('Archive for %s', 'aqualuxe'), get_the_date()) . '</li>';
    } elseif (is_month()) {
        echo '<li>' . $separator . '</li>';
        echo '<li class="text-gray-600">' . sprintf(esc_html__('Archive for %s', 'aqualuxe'), get_the_date('F Y')) . '</li>';
    } elseif (is_year()) {
        echo '<li>' . $separator . '</li>';
        echo '<li class="text-gray-600">' . sprintf(esc_html__('Archive for %s', 'aqualuxe'), get_the_date('Y')) . '</li>';
    } elseif (is_404()) {
        echo '<li>' . $separator . '</li>';
        echo '<li class="text-gray-600">' . esc_html__('Page Not Found', 'aqualuxe') . '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * Pagination Functions
 */

/**
 * Custom pagination
 */
function aqualuxe_pagination($query = null) {
    global $wp_query;
    
    if (!$query) {
        $query = $wp_query;
    }
    
    $total_pages = $query->max_num_pages;
    
    if ($total_pages <= 1) {
        return;
    }
    
    $current_page = max(1, get_query_var('paged'));
    
    echo '<nav class="pagination flex items-center justify-center space-x-2 mt-8" aria-label="' . esc_attr__('Pagination', 'aqualuxe') . '">';
    
    // Previous page link
    if ($current_page > 1) {
        echo '<a href="' . esc_url(get_pagenum_link($current_page - 1)) . '" class="pagination-link px-3 py-2 bg-white dark:bg-dark-800 border border-gray-300 dark:border-dark-600 rounded hover:bg-gray-50 dark:hover:bg-dark-700 transition-colors">';
        echo '<span class="sr-only">' . esc_html__('Previous page', 'aqualuxe') . '</span>';
        echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>';
        echo '</a>';
    }
    
    // Page numbers
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page) {
            echo '<span class="pagination-current px-3 py-2 bg-primary-600 text-white rounded">' . $i . '</span>';
        } else {
            echo '<a href="' . esc_url(get_pagenum_link($i)) . '" class="pagination-link px-3 py-2 bg-white dark:bg-dark-800 border border-gray-300 dark:border-dark-600 rounded hover:bg-gray-50 dark:hover:bg-dark-700 transition-colors">' . $i . '</a>';
        }
    }
    
    // Next page link
    if ($current_page < $total_pages) {
        echo '<a href="' . esc_url(get_pagenum_link($current_page + 1)) . '" class="pagination-link px-3 py-2 bg-white dark:bg-dark-800 border border-gray-300 dark:border-dark-600 rounded hover:bg-gray-50 dark:hover:bg-dark-700 transition-colors">';
        echo '<span class="sr-only">' . esc_html__('Next page', 'aqualuxe') . '</span>';
        echo '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
        echo '</a>';
    }
    
    echo '</nav>';
}

/**
 * Content Functions
 */

/**
 * Get reading time estimate
 */
function aqualuxe_get_reading_time($content = '') {
    if (empty($content)) {
        $content = get_the_content();
    }
    
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Assuming 200 words per minute
    
    return $reading_time;
}

/**
 * Get post meta display
 */
function aqualuxe_post_meta() {
    echo '<div class="post-meta flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">';
    
    // Author
    echo '<span class="author flex items-center space-x-1">';
    echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>';
    echo '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" class="hover:text-primary-600">' . esc_html(get_the_author()) . '</a>';
    echo '</span>';
    
    // Date
    echo '<span class="date flex items-center space-x-1">';
    echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
    echo '<time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>';
    echo '</span>';
    
    // Reading time
    $reading_time = aqualuxe_get_reading_time();
    if ($reading_time > 0) {
        echo '<span class="reading-time flex items-center space-x-1">';
        echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
        echo '<span>' . sprintf(_n('%d min read', '%d mins read', $reading_time, 'aqualuxe'), $reading_time) . '</span>';
        echo '</span>';
    }
    
    // Comments
    if (comments_open() || get_comments_number()) {
        echo '<span class="comments flex items-center space-x-1">';
        echo '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>';
        echo '<a href="' . esc_url(get_comments_link()) . '" class="hover:text-primary-600">';
        echo esc_html(sprintf(_n('%d Comment', '%d Comments', get_comments_number(), 'aqualuxe'), get_comments_number()));
        echo '</a>';
        echo '</span>';
    }
    
    echo '</div>';
}

/**
 * Social sharing buttons
 */
function aqualuxe_social_sharing() {
    if (!get_theme_mod('aqualuxe_social_sharing', true)) {
        return;
    }
    
    $url = urlencode(get_permalink());
    $title = urlencode(get_the_title());
    $excerpt = urlencode(wp_trim_words(get_the_excerpt(), 20));
    
    echo '<div class="social-sharing">';
    echo '<h4 class="text-sm font-semibold mb-3">' . esc_html__('Share this:', 'aqualuxe') . '</h4>';
    echo '<div class="flex items-center space-x-3">';
    
    // Facebook
    echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '" target="_blank" rel="noopener" class="share-facebook p-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors" aria-label="' . esc_attr__('Share on Facebook', 'aqualuxe') . '">';
    echo '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>';
    echo '</a>';
    
    // Twitter
    echo '<a href="https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title . '" target="_blank" rel="noopener" class="share-twitter p-2 bg-blue-400 text-white rounded-full hover:bg-blue-500 transition-colors" aria-label="' . esc_attr__('Share on Twitter', 'aqualuxe') . '">';
    echo '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>';
    echo '</a>';
    
    // LinkedIn
    echo '<a href="https://www.linkedin.com/sharing/share-offsite/?url=' . $url . '" target="_blank" rel="noopener" class="share-linkedin p-2 bg-blue-700 text-white rounded-full hover:bg-blue-800 transition-colors" aria-label="' . esc_attr__('Share on LinkedIn', 'aqualuxe') . '">';
    echo '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>';
    echo '</a>';
    
    // Pinterest
    if (has_post_thumbnail()) {
        $image = urlencode(get_the_post_thumbnail_url());
        echo '<a href="https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . $image . '&description=' . $title . '" target="_blank" rel="noopener" class="share-pinterest p-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors" aria-label="' . esc_attr__('Share on Pinterest', 'aqualuxe') . '">';
        echo '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.174.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.749-1.378 0 0-.599 2.282-.744 2.840-.282 1.084-1.064 2.456-1.549 3.235C9.584 23.815 10.77 24.001 12.017 24.001c6.624 0 11.99-5.367 11.99-12.014C24.007 5.36 18.641.001 12.017.001z"/></svg>';
        echo '</a>';
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Language switcher (if multilingual module is active)
 */
function aqualuxe_language_switcher() {
    // Will be implemented in multilingual module
    return;
}

/**
 * Related posts
 */
function aqualuxe_related_posts($post_id = null, $limit = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $categories = get_the_category($post_id);
    if (empty($categories)) {
        return;
    }
    
    $category_ids = array_map(function($cat) {
        return $cat->term_id;
    }, $categories);
    
    $related_posts = get_posts([
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'post__not_in' => [$post_id],
        'category__in' => $category_ids,
        'orderby' => 'rand',
        'meta_key' => '_thumbnail_id',
    ]);
    
    if (empty($related_posts)) {
        return;
    }
    
    echo '<div class="related-posts mt-12">';
    echo '<h3 class="text-2xl font-bold mb-6">' . esc_html__('Related Posts', 'aqualuxe') . '</h3>';
    echo '<div class="grid md:grid-cols-3 gap-6">';
    
    foreach ($related_posts as $post) {
        setup_postdata($post);
        echo '<article class="related-post card p-6">';
        
        if (has_post_thumbnail()) {
            echo '<div class="post-thumbnail mb-4">';
            echo '<a href="' . esc_url(get_permalink()) . '">';
            echo get_the_post_thumbnail(null, 'aqualuxe-blog-thumb', ['class' => 'w-full h-48 object-cover rounded-lg']);
            echo '</a>';
            echo '</div>';
        }
        
        echo '<h4 class="text-lg font-semibold mb-2">';
        echo '<a href="' . esc_url(get_permalink()) . '" class="hover:text-primary-600 transition-colors">' . esc_html(get_the_title()) . '</a>';
        echo '</h4>';
        
        echo '<p class="text-gray-600 dark:text-gray-400 text-sm">' . esc_html(wp_trim_words(get_the_excerpt(), 15)) . '</p>';
        
        echo '</article>';
    }
    
    echo '</div>';
    echo '</div>';
    
    wp_reset_postdata();
}

/**
 * Utility Functions
 */

/**
 * Check if current page is AquaLuxe specific page
 */
function aqualuxe_is_special_page($page_type = '') {
    $special_pages = ['wholesale', 'export', 'trade-in', 'services', 'events'];
    
    if ($page_type && in_array($page_type, $special_pages)) {
        return is_page($page_type);
    }
    
    foreach ($special_pages as $page) {
        if (is_page($page)) {
            return true;
        }
    }
    
    return false;
}

/**
 * Get theme option with default fallback
 */
function aqualuxe_option($option, $default = null) {
    $theme_options = get_option('aqualuxe_theme_options', []);
    return isset($theme_options[$option]) ? $theme_options[$option] : $default;
}

/**
 * Sanitize theme option
 */
function aqualuxe_sanitize_option($option, $value) {
    switch ($option) {
        case 'primary_color':
        case 'secondary_color':
        case 'accent_color':
            return sanitize_hex_color($value);
            
        case 'logo':
            return absint($value);
            
        case 'social_links':
            return array_map('esc_url_raw', (array) $value);
            
        default:
            return sanitize_text_field($value);
    }
}
