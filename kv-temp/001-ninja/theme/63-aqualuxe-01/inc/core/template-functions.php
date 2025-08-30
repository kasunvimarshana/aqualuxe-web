<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    // Add class if WooCommerce is active
    if (class_exists('WooCommerce')) {
        $classes[] = 'woocommerce-active';
        
        // Add specific WooCommerce page classes
        if (is_shop()) {
            $classes[] = 'woocommerce-shop';
        }
        
        if (is_product()) {
            $classes[] = 'woocommerce-product';
        }
        
        if (is_cart()) {
            $classes[] = 'woocommerce-cart';
        }
        
        if (is_checkout()) {
            $classes[] = 'woocommerce-checkout';
        }
        
        if (is_account_page()) {
            $classes[] = 'woocommerce-account';
        }
    } else {
        $classes[] = 'woocommerce-inactive';
    }

    // Add dark mode class if enabled
    if (aqualuxe_is_dark_mode()) {
        $classes[] = 'dark-mode';
    }

    // Add class for the current language if multilingual
    if (function_exists('pll_current_language')) {
        $classes[] = 'lang-' . pll_current_language();
    } elseif (defined('ICL_LANGUAGE_CODE')) {
        $classes[] = 'lang-' . ICL_LANGUAGE_CODE;
    }

    // Add class for the header layout
    $header_layout = get_theme_mod('aqualuxe_header_layout', 'default');
    $classes[] = 'header-layout-' . $header_layout;

    // Add class for the footer layout
    $footer_layout = get_theme_mod('aqualuxe_footer_layout', 'default');
    $classes[] = 'footer-layout-' . $footer_layout;

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');

/**
 * Check if dark mode is enabled
 *
 * @return bool
 */
function aqualuxe_is_dark_mode() {
    // Check if user has a preference stored in cookie
    if (isset($_COOKIE['aqualuxe_dark_mode']) && $_COOKIE['aqualuxe_dark_mode'] === 'true') {
        return true;
    }

    // Check if user has a preference stored in user meta (if logged in)
    if (is_user_logged_in()) {
        $user_preference = get_user_meta(get_current_user_id(), 'aqualuxe_dark_mode', true);
        if ($user_preference === 'true') {
            return true;
        }
    }

    // Check theme default setting
    $default_mode = get_theme_mod('aqualuxe_default_color_scheme', 'light');
    if ($default_mode === 'dark') {
        return true;
    }

    return false;
}

/**
 * Get header layout
 *
 * @return string
 */
function aqualuxe_get_header_layout() {
    return get_theme_mod('aqualuxe_header_layout', 'default');
}

/**
 * Get footer layout
 *
 * @return string
 */
function aqualuxe_get_footer_layout() {
    return get_theme_mod('aqualuxe_footer_layout', 'default');
}

/**
 * Get theme color scheme
 *
 * @return string
 */
function aqualuxe_get_color_scheme() {
    return aqualuxe_is_dark_mode() ? 'dark' : 'light';
}

/**
 * Add schema.org structured data
 *
 * @param array $data The structured data.
 * @return array
 */
function aqualuxe_get_structured_data($data = []) {
    $schema = [
        '@context' => 'https://schema.org',
    ];

    if (is_singular('post')) {
        $schema['@type'] = 'Article';
        $schema['headline'] = get_the_title();
        $schema['datePublished'] = get_the_date('c');
        $schema['dateModified'] = get_the_modified_date('c');
        
        // Add featured image if available
        if (has_post_thumbnail()) {
            $schema['image'] = [
                '@type' => 'ImageObject',
                'url' => get_the_post_thumbnail_url(null, 'full'),
            ];
        }
        
        // Add author information
        $schema['author'] = [
            '@type' => 'Person',
            'name' => get_the_author(),
        ];
        
        // Add publisher information
        $schema['publisher'] = [
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => aqualuxe_get_logo_url(),
            ],
        ];
    } elseif (is_page()) {
        $schema['@type'] = 'WebPage';
        $schema['name'] = get_the_title();
        $schema['description'] = get_the_excerpt();
        
        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(null, 'full');
        }
    } elseif (is_singular('product') && class_exists('WooCommerce')) {
        global $product;
        
        if (!$product) {
            return $schema;
        }
        
        $schema['@type'] = 'Product';
        $schema['name'] = $product->get_name();
        $schema['description'] = $product->get_short_description() ? $product->get_short_description() : $product->get_description();
        $schema['sku'] = $product->get_sku();
        
        // Add product image
        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(null, 'full');
        }
        
        // Add price data
        $schema['offers'] = [
            '@type' => 'Offer',
            'price' => $product->get_price(),
            'priceCurrency' => get_woocommerce_currency(),
            'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'url' => get_permalink(),
        ];
        
        // Add review data if available
        if ($product->get_review_count()) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $product->get_average_rating(),
                'reviewCount' => $product->get_review_count(),
            ];
        }
    }

    // Merge with any passed data
    $schema = array_merge($schema, $data);

    return $schema;
}

/**
 * Output structured data
 *
 * @param array $data Additional structured data.
 */
function aqualuxe_structured_data($data = []) {
    $structured_data = aqualuxe_get_structured_data($data);
    
    if (!empty($structured_data)) {
        echo '<script type="application/ld+json">' . wp_json_encode($structured_data) . '</script>';
    }
}

/**
 * Get logo URL
 *
 * @return string
 */
function aqualuxe_get_logo_url() {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo_url = '';
    
    if ($custom_logo_id) {
        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
    }
    
    return $logo_url;
}

/**
 * Get breadcrumbs
 *
 * @return string
 */
function aqualuxe_get_breadcrumbs() {
    if (class_exists('WooCommerce') && function_exists('woocommerce_breadcrumb')) {
        woocommerce_breadcrumb();
        return;
    }
    
    // Custom breadcrumbs implementation
    if (!is_front_page()) {
        echo '<nav class="aqualuxe-breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
        echo '<ol class="breadcrumb">';
        
        // Home link
        echo '<li class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'aqualuxe') . '</a></li>';
        
        // Category or single post
        if (is_category() || is_single()) {
            if (is_single()) {
                $categories = get_the_category();
                if (!empty($categories)) {
                    echo '<li class="breadcrumb-item"><a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a></li>';
                }
                echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
            } else {
                echo '<li class="breadcrumb-item active" aria-current="page">' . single_cat_title('', false) . '</li>';
            }
        } elseif (is_page()) {
            // Pages
            echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
        } elseif (is_search()) {
            // Search results
            echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__('Search results for: ', 'aqualuxe') . get_search_query() . '</li>';
        } elseif (is_tag()) {
            // Tags
            echo '<li class="breadcrumb-item active" aria-current="page">' . single_tag_title('', false) . '</li>';
        } elseif (is_author()) {
            // Author archives
            echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_author() . '</li>';
        } elseif (is_archive()) {
            // Archives
            echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_archive_title() . '</li>';
        }
        
        echo '</ol>';
        echo '</nav>';
    }
}

/**
 * Get social media links
 *
 * @return array
 */
function aqualuxe_get_social_links() {
    $social_links = [];
    
    $networks = [
        'facebook' => [
            'label' => __('Facebook', 'aqualuxe'),
            'icon' => 'fab fa-facebook-f',
        ],
        'twitter' => [
            'label' => __('Twitter', 'aqualuxe'),
            'icon' => 'fab fa-twitter',
        ],
        'instagram' => [
            'label' => __('Instagram', 'aqualuxe'),
            'icon' => 'fab fa-instagram',
        ],
        'youtube' => [
            'label' => __('YouTube', 'aqualuxe'),
            'icon' => 'fab fa-youtube',
        ],
        'linkedin' => [
            'label' => __('LinkedIn', 'aqualuxe'),
            'icon' => 'fab fa-linkedin-in',
        ],
        'pinterest' => [
            'label' => __('Pinterest', 'aqualuxe'),
            'icon' => 'fab fa-pinterest-p',
        ],
    ];
    
    foreach ($networks as $network => $data) {
        $url = get_theme_mod('aqualuxe_social_' . $network, '');
        
        if (!empty($url)) {
            $social_links[$network] = [
                'url' => $url,
                'label' => $data['label'],
                'icon' => $data['icon'],
            ];
        }
    }
    
    return apply_filters('aqualuxe_social_links', $social_links);
}

/**
 * Display social media links
 */
function aqualuxe_social_links() {
    $social_links = aqualuxe_get_social_links();
    
    if (!empty($social_links)) {
        echo '<div class="aqualuxe-social-links">';
        foreach ($social_links as $network => $data) {
            echo '<a href="' . esc_url($data['url']) . '" target="_blank" rel="noopener noreferrer" class="social-link ' . esc_attr($network) . '" aria-label="' . esc_attr($data['label']) . '">';
            echo '<i class="' . esc_attr($data['icon']) . '" aria-hidden="true"></i>';
            echo '</a>';
        }
        echo '</div>';
    }
}

/**
 * Get related posts
 *
 * @param int $post_id The post ID.
 * @param int $number_posts Number of posts to return.
 * @return WP_Query
 */
function aqualuxe_get_related_posts($post_id, $number_posts = 3) {
    $args = [
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $number_posts,
        'post__not_in' => [$post_id],
    ];
    
    // Get current post categories
    $categories = get_the_category($post_id);
    
    if (!empty($categories)) {
        $category_ids = [];
        
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }
        
        $args['category__in'] = $category_ids;
    }
    
    return new WP_Query($args);
}

/**
 * Get post excerpt
 *
 * @param int $length Excerpt length.
 * @return string
 */
function aqualuxe_get_excerpt($length = 55) {
    global $post;
    
    // Check if the post has an excerpt
    if (has_excerpt()) {
        return wp_trim_words(get_the_excerpt(), $length, '...');
    }
    
    // If no excerpt, generate one from the content
    $content = get_the_content();
    $content = strip_shortcodes($content);
    $content = excerpt_remove_blocks($content);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    
    return wp_trim_words($content, $length, '...');
}

/**
 * Get post author with schema markup
 *
 * @return string
 */
function aqualuxe_get_post_author() {
    $author_id = get_the_author_meta('ID');
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_url = get_author_posts_url($author_id);
    
    $output = '<span class="post-author" itemprop="author" itemscope itemtype="https://schema.org/Person">';
    $output .= '<a href="' . esc_url($author_url) . '" itemprop="url"><span itemprop="name">' . esc_html($author_name) . '</span></a>';
    $output .= '</span>';
    
    return $output;
}

/**
 * Get post date with schema markup
 *
 * @param string $format Date format.
 * @return string
 */
function aqualuxe_get_post_date($format = '') {
    if (empty($format)) {
        $format = get_option('date_format');
    }
    
    $output = '<span class="post-date">';
    $output .= '<time datetime="' . esc_attr(get_the_date('c')) . '" itemprop="datePublished">' . esc_html(get_the_date($format)) . '</time>';
    $output .= '</span>';
    
    return $output;
}

/**
 * Get post categories
 *
 * @return string
 */
function aqualuxe_get_post_categories() {
    $categories = get_the_category();
    
    if (empty($categories)) {
        return '';
    }
    
    $output = '<span class="post-categories">';
    
    foreach ($categories as $category) {
        $output .= '<a href="' . esc_url(get_category_link($category->term_id)) . '" rel="category">' . esc_html($category->name) . '</a>';
    }
    
    $output .= '</span>';
    
    return $output;
}

/**
 * Get post tags
 *
 * @return string
 */
function aqualuxe_get_post_tags() {
    $tags = get_the_tags();
    
    if (empty($tags)) {
        return '';
    }
    
    $output = '<span class="post-tags">';
    
    foreach ($tags as $tag) {
        $output .= '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" rel="tag">' . esc_html($tag->name) . '</a>';
    }
    
    $output .= '</span>';
    
    return $output;
}

/**
 * Get post comments count
 *
 * @return string
 */
function aqualuxe_get_comments_count() {
    $comments_count = get_comments_number();
    
    if (!comments_open()) {
        return '';
    }
    
    $output = '<span class="post-comments">';
    $output .= '<a href="' . esc_url(get_comments_link()) . '">';
    
    if ($comments_count == 0) {
        $output .= esc_html__('No Comments', 'aqualuxe');
    } elseif ($comments_count == 1) {
        $output .= esc_html__('1 Comment', 'aqualuxe');
    } else {
        $output .= sprintf(
            /* translators: %s: comment count */
            esc_html(_n('%s Comment', '%s Comments', $comments_count, 'aqualuxe')),
            esc_html(number_format_i18n($comments_count))
        );
    }
    
    $output .= '</a>';
    $output .= '</span>';
    
    return $output;
}

/**
 * Get post meta (author, date, categories, comments)
 *
 * @return string
 */
function aqualuxe_get_post_meta() {
    $output = '<div class="post-meta">';
    $output .= aqualuxe_get_post_author();
    $output .= aqualuxe_get_post_date();
    $output .= aqualuxe_get_post_categories();
    $output .= aqualuxe_get_comments_count();
    $output .= '</div>';
    
    return $output;
}

/**
 * Get pagination
 *
 * @return string
 */
function aqualuxe_get_pagination() {
    global $wp_query;
    
    if ($wp_query->max_num_pages <= 1) {
        return '';
    }
    
    $big = 999999999; // need an unlikely integer
    
    $pages = paginate_links([
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'type' => 'array',
        'prev_text' => '<span aria-hidden="true">&laquo;</span><span class="sr-only">' . __('Previous', 'aqualuxe') . '</span>',
        'next_text' => '<span aria-hidden="true">&raquo;</span><span class="sr-only">' . __('Next', 'aqualuxe') . '</span>',
    ]);
    
    if (is_array($pages)) {
        $output = '<nav class="aqualuxe-pagination" aria-label="' . esc_attr__('Pagination', 'aqualuxe') . '">';
        $output .= '<ul class="pagination">';
        
        foreach ($pages as $page) {
            $output .= '<li class="page-item' . (strpos($page, 'current') !== false ? ' active' : '') . '">';
            $output .= str_replace('page-numbers', 'page-link', $page);
            $output .= '</li>';
        }
        
        $output .= '</ul>';
        $output .= '</nav>';
        
        return $output;
    }
    
    return '';
}

/**
 * Display pagination
 */
function aqualuxe_pagination() {
    echo aqualuxe_get_pagination();
}

/**
 * Get post navigation
 *
 * @return string
 */
function aqualuxe_get_post_navigation() {
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if (empty($prev_post) && empty($next_post)) {
        return '';
    }
    
    $output = '<nav class="aqualuxe-post-navigation" aria-label="' . esc_attr__('Post Navigation', 'aqualuxe') . '">';
    $output .= '<div class="post-navigation-links">';
    
    if (!empty($prev_post)) {
        $output .= '<div class="post-navigation-prev">';
        $output .= '<a href="' . esc_url(get_permalink($prev_post)) . '" rel="prev">';
        $output .= '<span class="post-navigation-label">' . esc_html__('Previous Post', 'aqualuxe') . '</span>';
        $output .= '<span class="post-navigation-title">' . esc_html(get_the_title($prev_post)) . '</span>';
        $output .= '</a>';
        $output .= '</div>';
    }
    
    if (!empty($next_post)) {
        $output .= '<div class="post-navigation-next">';
        $output .= '<a href="' . esc_url(get_permalink($next_post)) . '" rel="next">';
        $output .= '<span class="post-navigation-label">' . esc_html__('Next Post', 'aqualuxe') . '</span>';
        $output .= '<span class="post-navigation-title">' . esc_html(get_the_title($next_post)) . '</span>';
        $output .= '</a>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    $output .= '</nav>';
    
    return $output;
}

/**
 * Display post navigation
 */
function aqualuxe_post_navigation() {
    echo aqualuxe_get_post_navigation();
}

/**
 * Get Open Graph meta tags
 *
 * @return string
 */
function aqualuxe_get_open_graph_tags() {
    $output = '';
    
    // Default values
    $og_title = get_bloginfo('name');
    $og_description = get_bloginfo('description');
    $og_url = home_url('/');
    $og_type = 'website';
    $og_image = aqualuxe_get_logo_url();
    
    // Override defaults based on current page
    if (is_singular()) {
        $og_title = get_the_title();
        $og_description = aqualuxe_get_excerpt(25);
        $og_url = get_permalink();
        $og_type = is_single() ? 'article' : 'website';
        
        if (has_post_thumbnail()) {
            $og_image = get_the_post_thumbnail_url(null, 'large');
        }
    } elseif (is_archive()) {
        $og_title = get_the_archive_title();
        $og_description = get_the_archive_description();
        $og_url = get_permalink();
    } elseif (is_search()) {
        $og_title = sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query());
        $og_description = get_bloginfo('description');
        $og_url = get_search_link();
    }
    
    // Build Open Graph tags
    $output .= '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\n";
    $output .= '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\n";
    $output .= '<meta property="og:url" content="' . esc_url($og_url) . '" />' . "\n";
    $output .= '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
    
    if ($og_image) {
        $output .= '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
    
    $output .= '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
    
    // Add Twitter Card tags
    $output .= '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    $output .= '<meta name="twitter:title" content="' . esc_attr($og_title) . '" />' . "\n";
    $output .= '<meta name="twitter:description" content="' . esc_attr($og_description) . '" />' . "\n";
    
    if ($og_image) {
        $output .= '<meta name="twitter:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
    
    return $output;
}

/**
 * Output Open Graph meta tags
 */
function aqualuxe_open_graph_tags() {
    echo aqualuxe_get_open_graph_tags();
}
add_action('wp_head', 'aqualuxe_open_graph_tags', 5);