<?php
/**
 * Template functions for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom classes to body
 *
 * @param array $classes Body classes
 * @return array
 */
function aqualuxe_body_classes($classes) {
    // Add dark mode class if enabled
    if (aqualuxe_is_dark_mode()) {
        $classes[] = 'dark';
    }
    
    // Add class for pages with sidebar
    if (is_active_sidebar('sidebar-1') && (is_singular('post') || is_archive() || is_home())) {
        $classes[] = 'has-sidebar';
    }
    
    // Add class for WooCommerce pages with sidebar
    if (aqualuxe_is_woocommerce_active() && is_active_sidebar('sidebar-shop') && (is_shop() || is_product_category() || is_product_tag())) {
        $classes[] = 'has-shop-sidebar';
    }
    
    // Add class for pages with transparent header
    if (is_page() && get_post_meta(get_the_ID(), 'aqualuxe_transparent_header', true)) {
        $classes[] = 'has-transparent-header';
    }
    
    // Add class for pages with hero section
    if (is_page() && get_post_meta(get_the_ID(), 'aqualuxe_hero_section', true)) {
        $classes[] = 'has-hero-section';
    }
    
    // Add class for multilingual site
    if (function_exists('icl_get_languages') || function_exists('pll_languages_list')) {
        $classes[] = 'multilingual-site';
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add custom classes to post
 *
 * @param array $classes Post classes
 * @return array
 */
function aqualuxe_post_classes($classes) {
    // Add card class for posts in archive
    if (is_archive() || is_home() || is_search()) {
        $classes[] = 'card';
    }
    
    return $classes;
}
add_filter('post_class', 'aqualuxe_post_classes');

/**
 * Modify excerpt length
 *
 * @param int $length Excerpt length
 * @return int
 */
function aqualuxe_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Modify excerpt more
 *
 * @param string $more Excerpt more
 * @return string
 */
function aqualuxe_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add schema markup to HTML tag
 *
 * @param string $output HTML output
 * @return string
 */
function aqualuxe_add_schema_markup($output) {
    return str_replace('<html', '<html itemscope itemtype="https://schema.org/WebPage"', $output);
}
add_filter('language_attributes', 'aqualuxe_add_schema_markup');

/**
 * Add Open Graph meta tags
 */
function aqualuxe_add_opengraph_tags() {
    // Skip if Yoast SEO or similar plugins are active
    if (defined('WPSEO_VERSION') || class_exists('All_in_One_SEO_Pack')) {
        return;
    }
    
    global $post;
    
    // Default values
    $og_title = get_bloginfo('name');
    $og_description = get_bloginfo('description');
    $og_url = home_url('/');
    $og_image = aqualuxe_get_option('og_default_image');
    $og_type = 'website';
    
    // Single post or page
    if (is_singular()) {
        $og_title = get_the_title();
        $og_url = get_permalink();
        $og_type = is_single() ? 'article' : 'website';
        
        // Get excerpt
        $excerpt = get_the_excerpt();
        if (!empty($excerpt)) {
            $og_description = wp_strip_all_tags($excerpt);
        }
        
        // Get featured image
        if (has_post_thumbnail()) {
            $og_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
        }
    }
    
    // Output meta tags
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($og_url) . '" />' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
    
    if ($og_image) {
        echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
    
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
    
    // Twitter Card
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($og_description) . '" />' . "\n";
    
    if ($og_image) {
        echo '<meta name="twitter:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
    
    // Twitter site username
    $twitter_username = aqualuxe_get_option('twitter_username');
    if ($twitter_username) {
        echo '<meta name="twitter:site" content="@' . esc_attr($twitter_username) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_opengraph_tags', 5);

/**
 * Add structured data for posts
 */
function aqualuxe_add_structured_data() {
    // Skip if Yoast SEO or similar plugins are active
    if (defined('WPSEO_VERSION') || class_exists('All_in_One_SEO_Pack')) {
        return;
    }
    
    if (is_singular('post')) {
        global $post;
        
        $author_id = $post->post_author;
        $author_name = get_the_author_meta('display_name', $author_id);
        $author_url = get_author_posts_url($author_id);
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => get_the_title(),
            'description' => get_the_excerpt(),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c'),
            'author' => [
                '@type' => 'Person',
                'name' => $author_name,
                'url' => $author_url,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => aqualuxe_get_option('logo'),
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => get_permalink(),
            ],
        ];
        
        // Add featured image
        if (has_post_thumbnail()) {
            $schema['image'] = [
                '@type' => 'ImageObject',
                'url' => get_the_post_thumbnail_url(null, 'full'),
                'width' => 1200,
                'height' => 630,
            ];
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
    
    if (is_singular('product') && aqualuxe_is_woocommerce_active()) {
        global $product;
        
        if (!$product) {
            return;
        }
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->get_name(),
            'description' => wp_strip_all_tags($product->get_short_description() ? $product->get_short_description() : $product->get_description()),
            'sku' => $product->get_sku(),
            'offers' => [
                '@type' => 'Offer',
                'price' => $product->get_price(),
                'priceCurrency' => get_woocommerce_currency(),
                'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => get_permalink(),
            ],
        ];
        
        // Add product image
        if (has_post_thumbnail()) {
            $schema['image'] = get_the_post_thumbnail_url(null, 'full');
        }
        
        // Add product brand
        $brands = wp_get_post_terms($product->get_id(), 'product_brand');
        if (!empty($brands) && !is_wp_error($brands)) {
            $schema['brand'] = [
                '@type' => 'Brand',
                'name' => $brands[0]->name,
            ];
        }
        
        // Add product reviews
        $reviews = aqualuxe_get_product_reviews($product->get_id());
        if (!empty($reviews)) {
            $schema['review'] = [];
            
            foreach ($reviews as $review) {
                $rating = get_comment_meta($review->comment_ID, 'rating', true);
                
                if ($rating) {
                    $schema['review'][] = [
                        '@type' => 'Review',
                        'reviewRating' => [
                            '@type' => 'Rating',
                            'ratingValue' => $rating,
                            'bestRating' => '5',
                        ],
                        'author' => [
                            '@type' => 'Person',
                            'name' => $review->comment_author,
                        ],
                        'reviewBody' => $review->comment_content,
                    ];
                }
            }
            
            // Add aggregate rating
            if ($product->get_rating_count() > 0) {
                $schema['aggregateRating'] = [
                    '@type' => 'AggregateRating',
                    'ratingValue' => $product->get_average_rating(),
                    'reviewCount' => $product->get_review_count(),
                ];
            }
        }
        
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_structured_data', 10);

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Image sizes
 * @return array
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, [
        'aqualuxe-featured' => __('Featured Image', 'aqualuxe'),
        'aqualuxe-card' => __('Card Image', 'aqualuxe'),
        'aqualuxe-thumbnail' => __('Thumbnail', 'aqualuxe'),
    ]);
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Add async/defer attributes to scripts
 *
 * @param string $tag Script tag
 * @param string $handle Script handle
 * @return string
 */
function aqualuxe_script_loader_tag($tag, $handle) {
    $scripts_to_async = ['aqualuxe-scripts'];
    $scripts_to_defer = ['aqualuxe-scripts'];
    
    if (in_array($handle, $scripts_to_async)) {
        $tag = str_replace(' src', ' async src', $tag);
    }
    
    if (in_array($handle, $scripts_to_defer)) {
        $tag = str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'aqualuxe_script_loader_tag', 10, 2);

/**
 * Add preconnect for Google Fonts
 *
 * @param array $urls URLs to print for resource hints
 * @param string $relation_type Relation type
 * @return array
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = [
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        ];
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);

/**
 * Add custom classes to menu items
 *
 * @param array $classes Menu item classes
 * @param object $item Menu item
 * @param object $args Menu args
 * @return array
 */
function aqualuxe_menu_item_classes($classes, $item, $args) {
    // Add Tailwind classes to menu items
    if ('primary' === $args->theme_location) {
        $classes[] = 'px-4 py-2 text-gray-800 dark:text-white hover:text-primary dark:hover:text-primary-light transition-colors duration-300';
    }
    
    if ('footer' === $args->theme_location) {
        $classes[] = 'mb-2 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary-light transition-colors duration-300';
    }
    
    if ('social' === $args->theme_location) {
        $classes[] = 'inline-block mx-2 text-gray-600 dark:text-gray-300 hover:text-primary dark:hover:text-primary-light transition-colors duration-300';
    }
    
    return $classes;
}
add_filter('nav_menu_css_class', 'aqualuxe_menu_item_classes', 10, 3);

/**
 * Add custom classes to menu link attributes
 *
 * @param array $atts Menu link attributes
 * @param object $item Menu item
 * @param object $args Menu args
 * @return array
 */
function aqualuxe_menu_link_attributes($atts, $item, $args) {
    // Add Tailwind classes to menu links
    if ('primary' === $args->theme_location) {
        $atts['class'] = 'block';
    }
    
    return $atts;
}
add_filter('nav_menu_link_attributes', 'aqualuxe_menu_link_attributes', 10, 3);

/**
 * Add custom classes to submenu
 *
 * @param string $classes Submenu classes
 * @return string
 */
function aqualuxe_submenu_classes($classes) {
    return $classes . ' bg-white dark:bg-dark-card shadow-lg rounded-md py-2 mt-2';
}
add_filter('nav_menu_submenu_css_class', 'aqualuxe_submenu_classes');

/**
 * Add custom classes to comment form fields
 *
 * @param array $fields Comment form fields
 * @return array
 */
function aqualuxe_comment_form_fields($fields) {
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ($req ? " aria-required='true'" : '');
    
    $fields['author'] = '<div class="comment-form-author mb-4">' .
        '<label for="author" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . __('Name', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label> ' .
        '<input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245"' . $aria_req . ' class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" />' .
        '</div>';
    
    $fields['email'] = '<div class="comment-form-email mb-4">' .
        '<label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . __('Email', 'aqualuxe') . ($req ? ' <span class="required">*</span>' : '') . '</label> ' .
        '<input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . ' class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" />' .
        '</div>';
    
    $fields['url'] = '<div class="comment-form-url mb-4">' .
        '<label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . __('Website', 'aqualuxe') . '</label>' .
        '<input id="url" name="url" type="url" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" />' .
        '</div>';
    
    return $fields;
}
add_filter('comment_form_default_fields', 'aqualuxe_comment_form_fields');

/**
 * Add custom classes to comment form
 *
 * @param array $defaults Comment form defaults
 * @return array
 */
function aqualuxe_comment_form_defaults($defaults) {
    $defaults['comment_field'] = '<div class="comment-form-comment mb-4">' .
        '<label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">' . _x('Comment', 'noun', 'aqualuxe') . ' <span class="required">*</span></label>' .
        '<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required="required" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"></textarea>' .
        '</div>';
    
    $defaults['class_submit'] = 'btn btn-primary';
    $defaults['submit_button'] = '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s</button>';
    
    return $defaults;
}
add_filter('comment_form_defaults', 'aqualuxe_comment_form_defaults');

/**
 * Add custom classes to pagination
 *
 * @param string $template Pagination template
 * @return string
 */
function aqualuxe_pagination_template($template) {
    $template = '
    <nav class="navigation %1$s" aria-label="%4$s">
        <h2 class="screen-reader-text">%2$s</h2>
        <div class="nav-links flex justify-center items-center space-x-2 my-8">%3$s</div>
    </nav>';
    
    return $template;
}
add_filter('navigation_markup_template', 'aqualuxe_pagination_template');

/**
 * Add custom classes to pagination links
 *
 * @param string $output Pagination output
 * @return string
 */
function aqualuxe_pagination_links($output) {
    // Add classes to pagination links
    $output = str_replace('page-numbers', 'page-numbers inline-flex items-center justify-center w-10 h-10 border border-gray-300 dark:border-gray-700 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-300', $output);
    $output = str_replace('current', 'current bg-primary text-white border-primary hover:bg-primary-dark', $output);
    
    return $output;
}
add_filter('paginate_links', 'aqualuxe_pagination_links');

/**
 * Add custom classes to search form
 *
 * @param string $form Search form HTML
 * @return string
 */
function aqualuxe_search_form($form) {
    $form = '<form role="search" method="get" class="search-form relative" action="' . esc_url(home_url('/')) . '">
        <label class="screen-reader-text">' . _x('Search for:', 'label', 'aqualuxe') . '</label>
        <input type="search" class="search-field w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 pl-10" placeholder="' . esc_attr_x('Search &hellip;', 'placeholder', 'aqualuxe') . '" value="' . get_search_query() . '" name="s" />
        <button type="submit" class="search-submit absolute left-0 top-0 mt-2 ml-3 text-gray-500 dark:text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span class="screen-reader-text">' . _x('Search', 'submit button', 'aqualuxe') . '</span>
        </button>
    </form>';
    
    return $form;
}
add_filter('get_search_form', 'aqualuxe_search_form');

/**
 * Add custom classes to password form
 *
 * @param string $output Password form HTML
 * @return string
 */
function aqualuxe_password_form($output) {
    $output = str_replace('class="post-password-form"', 'class="post-password-form p-6 bg-gray-100 dark:bg-gray-800 rounded-lg"', $output);
    $output = str_replace('<input name="post_password" type="password"', '<input name="post_password" type="password" class="mt-1 block rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"', $output);
    $output = str_replace('<input type="submit"', '<input type="submit" class="mt-4 btn btn-primary"', $output);
    
    return $output;
}
add_filter('the_password_form', 'aqualuxe_password_form');

/**
 * Add custom classes to gallery
 *
 * @param string $gallery Gallery HTML
 * @param array $attr Gallery attributes
 * @return string
 */
function aqualuxe_gallery_style($gallery, $attr) {
    return str_replace('gallery ', 'gallery grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 ', $gallery);
}
add_filter('post_gallery', 'aqualuxe_gallery_style', 10, 2);

/**
 * Add lazy loading to images
 *
 * @param string $content Post content
 * @return string
 */
function aqualuxe_add_lazy_loading($content) {
    // Skip if WordPress 5.5+ (which has native lazy loading)
    if (function_exists('wp_lazy_loading_enabled')) {
        return $content;
    }
    
    // Add loading="lazy" to images
    return preg_replace_callback('/<img ([^>]+)>/i', function($matches) {
        // Skip if already has loading attribute
        if (strpos($matches[1], 'loading=') !== false) {
            return $matches[0];
        }
        
        // Add loading="lazy" attribute
        return '<img ' . $matches[1] . ' loading="lazy">';
    }, $content);
}
add_filter('the_content', 'aqualuxe_add_lazy_loading');

/**
 * Add custom classes to post navigation
 *
 * @param string $template Post navigation template
 * @return string
 */
function aqualuxe_post_navigation_template($template) {
    return '
    <nav class="navigation post-navigation" aria-label="' . esc_attr__('Posts', 'aqualuxe') . '">
        <h2 class="screen-reader-text">' . __('Post navigation', 'aqualuxe') . '</h2>
        <div class="nav-links flex flex-wrap justify-between mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
            <div class="nav-previous w-full md:w-1/2 md:pr-4 mb-4 md:mb-0">%1$s</div>
            <div class="nav-next w-full md:w-1/2 md:pl-4 text-right">%2$s</div>
        </div>
    </nav>';
}
add_filter('navigation_markup_template', 'aqualuxe_post_navigation_template', 10, 2);

/**
 * Add custom classes to post navigation links
 *
 * @param string $output Post navigation link
 * @param string $format Link format
 * @return string
 */
function aqualuxe_post_link_attributes($output, $format) {
    $class = 'prev' === $format ? 'nav-previous-link' : 'nav-next-link';
    $output = str_replace('<a href=', '<a class="' . $class . ' text-primary hover:text-primary-dark transition-colors duration-300" href=', $output);
    
    return $output;
}
add_filter('previous_post_link', 'aqualuxe_post_link_attributes', 10, 2);
add_filter('next_post_link', 'aqualuxe_post_link_attributes', 10, 2);

/**
 * Add custom classes to archive title
 *
 * @param string $title Archive title
 * @return string
 */
function aqualuxe_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = get_the_author();
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    }
    
    return $title;
}
add_filter('get_the_archive_title', 'aqualuxe_archive_title');

/**
 * Add custom classes to archive description
 *
 * @param string $description Archive description
 * @return string
 */
function aqualuxe_archive_description($description) {
    if (!empty($description)) {
        $description = '<div class="archive-description mt-4 text-gray-600 dark:text-gray-400">' . $description . '</div>';
    }
    
    return $description;
}
add_filter('get_the_archive_description', 'aqualuxe_archive_description');

/**
 * Add custom classes to widgets
 *
 * @param array $params Widget parameters
 * @return array
 */
function aqualuxe_widget_params($params) {
    $params[0]['before_widget'] = str_replace('class="widget', 'class="widget mb-8', $params[0]['before_widget']);
    
    return $params;
}
add_filter('dynamic_sidebar_params', 'aqualuxe_widget_params');

/**
 * Add custom classes to tag cloud
 *
 * @param array $args Tag cloud args
 * @return array
 */
function aqualuxe_tag_cloud_args($args) {
    $args['smallest'] = 12;
    $args['largest'] = 18;
    $args['unit'] = 'px';
    $args['format'] = 'flat';
    
    return $args;
}
add_filter('widget_tag_cloud_args', 'aqualuxe_tag_cloud_args');

/**
 * Add custom classes to tag cloud links
 *
 * @param string $return Tag cloud HTML
 * @return string
 */
function aqualuxe_tag_cloud_filter($return) {
    return str_replace('<a', '<a class="inline-block bg-gray-100 dark:bg-gray-800 hover:bg-primary hover:text-white dark:hover:bg-primary dark:hover:text-white px-3 py-1 rounded-md mb-2 mr-2 transition-colors duration-300"', $return);
}
add_filter('wp_tag_cloud', 'aqualuxe_tag_cloud_filter');

/**
 * Add custom classes to calendar
 *
 * @param string $calendar Calendar HTML
 * @return string
 */
function aqualuxe_calendar_filter($calendar) {
    $calendar = str_replace('class="wp-calendar', 'class="wp-calendar w-full text-center', $calendar);
    $calendar = str_replace('<caption>', '<caption class="text-lg font-bold mb-2">', $calendar);
    $calendar = str_replace('<thead>', '<thead class="bg-gray-100 dark:bg-gray-800">', $calendar);
    $calendar = str_replace('<tbody>', '<tbody class="text-center">', $calendar);
    
    return $calendar;
}
add_filter('get_calendar', 'aqualuxe_calendar_filter');

/**
 * Add custom classes to comment reply link
 *
 * @param string $link Comment reply link
 * @return string
 */
function aqualuxe_comment_reply_link($link) {
    return str_replace('comment-reply-link', 'comment-reply-link text-sm text-primary hover:text-primary-dark transition-colors duration-300', $link);
}
add_filter('comment_reply_link', 'aqualuxe_comment_reply_link');

/**
 * Add custom classes to comment edit link
 *
 * @param string $link Comment edit link
 * @return string
 */
function aqualuxe_comment_edit_link($link) {
    return str_replace('comment-edit-link', 'comment-edit-link text-sm text-primary hover:text-primary-dark transition-colors duration-300 ml-2', $link);
}
add_filter('edit_comment_link', 'aqualuxe_comment_edit_link');

/**
 * Add custom classes to post edit link
 *
 * @param string $link Post edit link
 * @return string
 */
function aqualuxe_post_edit_link($link) {
    return str_replace('post-edit-link', 'post-edit-link text-sm text-primary hover:text-primary-dark transition-colors duration-300', $link);
}
add_filter('edit_post_link', 'aqualuxe_post_edit_link');

/**
 * Add custom classes to page links
 *
 * @param string $output Page links
 * @return string
 */
function aqualuxe_wp_link_pages_link($output) {
    return '<span class="page-link bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-md">' . $output . '</span>';
}
add_filter('wp_link_pages_link', 'aqualuxe_wp_link_pages_link');

/**
 * Add custom classes to page links separator
 *
 * @param array $args Page links args
 * @return array
 */
function aqualuxe_wp_link_pages_args($args) {
    $args['before'] = '<div class="page-links mt-4 pt-4 border-t border-gray-200 dark:border-gray-700"><span class="page-links-title font-bold mr-2">' . __('Pages:', 'aqualuxe') . '</span>';
    $args['after'] = '</div>';
    $args['separator'] = '<span class="mx-1"></span>';
    
    return $args;
}
add_filter('wp_link_pages_args', 'aqualuxe_wp_link_pages_args');

/**
 * Add custom classes to the_content
 *
 * @param string $content Post content
 * @return string
 */
function aqualuxe_content_filter($content) {
    // Add classes to tables
    $content = str_replace('<table', '<table class="w-full border-collapse mb-4"', $content);
    $content = str_replace('<thead', '<thead class="bg-gray-100 dark:bg-gray-800"', $content);
    $content = str_replace('<th', '<th class="border border-gray-300 dark:border-gray-700 px-4 py-2 text-left"', $content);
    $content = str_replace('<td', '<td class="border border-gray-300 dark:border-gray-700 px-4 py-2"', $content);
    
    // Add classes to blockquotes
    $content = str_replace('<blockquote', '<blockquote class="border-l-4 border-primary pl-4 italic my-4"', $content);
    
    // Add classes to code blocks
    $content = str_replace('<pre', '<pre class="bg-gray-100 dark:bg-gray-800 p-4 rounded-md overflow-x-auto my-4"', $content);
    
    return $content;
}
add_filter('the_content', 'aqualuxe_content_filter');

/**
 * Add custom classes to embeds
 *
 * @param string $html Embed HTML
 * @return string
 */
function aqualuxe_embed_html($html) {
    return '<div class="embed-container relative overflow-hidden mb-4 pt-[56.25%]">' . $html . '</div>';
}
add_filter('embed_oembed_html', 'aqualuxe_embed_html', 10, 3);

/**
 * Add custom classes to embeds
 *
 * @param string $cache Embed cache
 * @param string $url Embed URL
 * @param array $attr Embed attributes
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_embed_oembed_html($cache, $url, $attr, $post_id) {
    $classes = 'absolute top-0 left-0 w-full h-full';
    
    // Add classes to iframes
    $cache = preg_replace('/<iframe/', '<iframe class="' . esc_attr($classes) . '"', $cache);
    
    return $cache;
}
add_filter('embed_oembed_html', 'aqualuxe_embed_oembed_html', 99, 4);