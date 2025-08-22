<?php
/**
 * AquaLuxe Template Helpers
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get template part with variables
 *
 * @param string $slug Template slug
 * @param string $name Template name
 * @param array $args Template arguments
 * @return void
 */
function aqualuxe_get_template_part($slug, $name = null, $args = []) {
    // Extract args to make them available in the template
    if (!empty($args) && is_array($args)) {
        extract($args);
    }
    
    // Look for template in theme directory
    $template = '';
    
    // Look in theme/templates directory first
    if ($name) {
        $template = locate_template(['templates/' . $slug . '-' . $name . '.php']);
    }
    
    // If not found, look for the slug
    if (!$template) {
        $template = locate_template(['templates/' . $slug . '.php']);
    }
    
    // Allow plugins/themes to override template
    $template = apply_filters('aqualuxe_get_template_part', $template, $slug, $name, $args);
    
    // If template is found, include it
    if ($template) {
        include $template;
    }
}

/**
 * Get module template part
 *
 * @param string $module Module name
 * @param string $slug Template slug
 * @param string $name Template name
 * @param array $args Template arguments
 * @return void
 */
function aqualuxe_get_module_template_part($module, $slug, $name = null, $args = []) {
    if (!aqualuxe_is_module_active($module)) {
        return;
    }
    
    // Extract args to make them available in the template
    if (!empty($args) && is_array($args)) {
        extract($args);
    }
    
    // Look for template in module directory
    $template = '';
    $module_dir = AQUALUXE_MODULES_DIR . $module . '/templates/';
    
    // Look in module/templates directory
    if ($name) {
        if (file_exists($module_dir . $slug . '-' . $name . '.php')) {
            $template = $module_dir . $slug . '-' . $name . '.php';
        }
    }
    
    // If not found, look for the slug
    if (!$template && file_exists($module_dir . $slug . '.php')) {
        $template = $module_dir . $slug . '.php';
    }
    
    // Allow plugins/themes to override template
    $template = apply_filters('aqualuxe_get_module_template_part', $template, $module, $slug, $name, $args);
    
    // If template is found, include it
    if ($template) {
        include $template;
    }
}

/**
 * Get WooCommerce template part
 *
 * @param string $slug Template slug
 * @param string $name Template name
 * @param array $args Template arguments
 * @return void
 */
function aqualuxe_get_woocommerce_template_part($slug, $name = null, $args = []) {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    // Extract args to make them available in the template
    if (!empty($args) && is_array($args)) {
        extract($args);
    }
    
    // Look for template in theme/woocommerce directory
    $template = '';
    
    // Look in theme/woocommerce directory first
    if ($name) {
        $template = locate_template(['woocommerce/' . $slug . '-' . $name . '.php']);
    }
    
    // If not found, look for the slug
    if (!$template) {
        $template = locate_template(['woocommerce/' . $slug . '.php']);
    }
    
    // Allow plugins/themes to override template
    $template = apply_filters('aqualuxe_get_woocommerce_template_part', $template, $slug, $name, $args);
    
    // If template is found, include it
    if ($template) {
        include $template;
    } else {
        // Fallback to WooCommerce template
        wc_get_template_part($slug, $name);
    }
}

/**
 * Get page title
 *
 * @return string
 */
function aqualuxe_get_page_title() {
    if (is_home()) {
        if (get_option('page_for_posts', true)) {
            return get_the_title(get_option('page_for_posts', true));
        } else {
            return esc_html__('Latest Posts', 'aqualuxe');
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
        return sprintf(
            /* translators: %s: search query */
            esc_html__('Search Results for: %s', 'aqualuxe'),
            '<span>' . get_search_query() . '</span>'
        );
    } elseif (is_404()) {
        return esc_html__('Page Not Found', 'aqualuxe');
    } elseif (is_singular()) {
        return get_the_title();
    }
    
    return get_bloginfo('name');
}

/**
 * Get breadcrumbs
 *
 * @return array
 */
function aqualuxe_get_breadcrumbs() {
    $breadcrumbs = [];
    
    // Home
    $breadcrumbs[] = [
        'title' => esc_html__('Home', 'aqualuxe'),
        'url' => home_url('/'),
    ];
    
    if (is_home()) {
        // Blog
        if (get_option('page_for_posts', true)) {
            $breadcrumbs[] = [
                'title' => get_the_title(get_option('page_for_posts', true)),
                'url' => get_permalink(get_option('page_for_posts', true)),
            ];
        } else {
            $breadcrumbs[] = [
                'title' => esc_html__('Blog', 'aqualuxe'),
                'url' => '',
            ];
        }
    } elseif (is_category() || is_tag() || is_tax()) {
        // Category, Tag, or Custom Taxonomy
        $term = get_queried_object();
        
        if (is_category()) {
            $breadcrumbs[] = [
                'title' => esc_html__('Blog', 'aqualuxe'),
                'url' => get_permalink(get_option('page_for_posts', true)),
            ];
        }
        
        $breadcrumbs[] = [
            'title' => $term->name,
            'url' => '',
        ];
    } elseif (is_singular('post')) {
        // Single Post
        $breadcrumbs[] = [
            'title' => esc_html__('Blog', 'aqualuxe'),
            'url' => get_permalink(get_option('page_for_posts', true)),
        ];
        
        $categories = get_the_category();
        if (!empty($categories)) {
            $category = $categories[0];
            $breadcrumbs[] = [
                'title' => $category->name,
                'url' => get_category_link($category->term_id),
            ];
        }
        
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => '',
        ];
    } elseif (is_singular('page')) {
        // Page
        global $post;
        
        if ($post->post_parent) {
            $parent_id = $post->post_parent;
            $parents = [];
            
            while ($parent_id) {
                $parent = get_post($parent_id);
                $parents[] = [
                    'title' => get_the_title($parent_id),
                    'url' => get_permalink($parent_id),
                ];
                $parent_id = $parent->post_parent;
            }
            
            $parents = array_reverse($parents);
            
            foreach ($parents as $parent) {
                $breadcrumbs[] = $parent;
            }
        }
        
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => '',
        ];
    } elseif (is_singular()) {
        // Custom Post Type
        $post_type = get_post_type();
        $post_type_obj = get_post_type_object($post_type);
        
        if ($post_type_obj) {
            $breadcrumbs[] = [
                'title' => $post_type_obj->labels->name,
                'url' => get_post_type_archive_link($post_type),
            ];
        }
        
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => '',
        ];
    } elseif (is_post_type_archive()) {
        // Custom Post Type Archive
        $post_type = get_post_type();
        $post_type_obj = get_post_type_object($post_type);
        
        if ($post_type_obj) {
            $breadcrumbs[] = [
                'title' => $post_type_obj->labels->name,
                'url' => '',
            ];
        }
    } elseif (is_author()) {
        // Author Archive
        $breadcrumbs[] = [
            'title' => esc_html__('Author: ', 'aqualuxe') . get_the_author(),
            'url' => '',
        ];
    } elseif (is_date()) {
        // Date Archive
        $breadcrumbs[] = [
            'title' => esc_html__('Archives', 'aqualuxe'),
            'url' => '',
        ];
    } elseif (is_search()) {
        // Search Results
        $breadcrumbs[] = [
            'title' => sprintf(
                /* translators: %s: search query */
                esc_html__('Search Results for: %s', 'aqualuxe'),
                get_search_query()
            ),
            'url' => '',
        ];
    } elseif (is_404()) {
        // 404 Page
        $breadcrumbs[] = [
            'title' => esc_html__('Page Not Found', 'aqualuxe'),
            'url' => '',
        ];
    }
    
    return apply_filters('aqualuxe_breadcrumbs', $breadcrumbs);
}

/**
 * Display breadcrumbs
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_breadcrumbs($args = []) {
    $defaults = [
        'wrapper_class' => 'aqualuxe-breadcrumbs',
        'separator' => '/',
        'before' => '<nav class="aqualuxe-breadcrumbs-nav">',
        'after' => '</nav>',
        'show_on_home' => false,
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    // Don't show on home page if disabled
    if (is_front_page() && !$args['show_on_home']) {
        return;
    }
    
    $breadcrumbs = aqualuxe_get_breadcrumbs();
    
    if (empty($breadcrumbs)) {
        return;
    }
    
    echo $args['before'];
    
    echo '<ol class="' . esc_attr($args['wrapper_class']) . '">';
    
    $count = count($breadcrumbs);
    $i = 1;
    
    foreach ($breadcrumbs as $breadcrumb) {
        echo '<li class="aqualuxe-breadcrumb-item">';
        
        if (!empty($breadcrumb['url']) && $i < $count) {
            echo '<a href="' . esc_url($breadcrumb['url']) . '">' . esc_html($breadcrumb['title']) . '</a>';
        } else {
            echo '<span>' . esc_html($breadcrumb['title']) . '</span>';
        }
        
        echo '</li>';
        
        if ($i < $count) {
            echo '<li class="aqualuxe-breadcrumb-separator">' . esc_html($args['separator']) . '</li>';
        }
        
        $i++;
    }
    
    echo '</ol>';
    
    echo $args['after'];
}

/**
 * Get post thumbnail with fallback
 *
 * @param int|WP_Post $post Post ID or post object
 * @param string $size Image size
 * @param array $attr Image attributes
 * @return string
 */
function aqualuxe_get_post_thumbnail($post = null, $size = 'post-thumbnail', $attr = []) {
    $post = get_post($post);
    
    if (!$post) {
        return '';
    }
    
    if (has_post_thumbnail($post)) {
        return get_the_post_thumbnail($post, $size, $attr);
    }
    
    // Fallback image
    $placeholder = AQUALUXE_ASSETS_URI . 'images/placeholder.jpg';
    
    $attr = wp_parse_args($attr, [
        'class' => 'aqualuxe-placeholder',
        'alt' => get_the_title($post),
    ]);
    
    $attr_string = '';
    
    foreach ($attr as $name => $value) {
        $attr_string .= ' ' . $name . '="' . esc_attr($value) . '"';
    }
    
    return '<img src="' . esc_url($placeholder) . '"' . $attr_string . '>';
}

/**
 * Display post thumbnail with fallback
 *
 * @param int|WP_Post $post Post ID or post object
 * @param string $size Image size
 * @param array $attr Image attributes
 * @return void
 */
function aqualuxe_post_thumbnail($post = null, $size = 'post-thumbnail', $attr = []) {
    echo aqualuxe_get_post_thumbnail($post, $size, $attr);
}

/**
 * Get post excerpt
 *
 * @param int|WP_Post $post Post ID or post object
 * @param int $length Excerpt length
 * @param string $more Read more text
 * @return string
 */
function aqualuxe_get_excerpt($post = null, $length = 55, $more = '&hellip;') {
    $post = get_post($post);
    
    if (!$post) {
        return '';
    }
    
    if (has_excerpt($post)) {
        return get_the_excerpt($post);
    }
    
    $excerpt = get_the_content('', false, $post);
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = excerpt_remove_blocks($excerpt);
    $excerpt = wp_strip_all_tags($excerpt);
    $excerpt = wp_trim_words($excerpt, $length, $more);
    
    return $excerpt;
}

/**
 * Display post excerpt
 *
 * @param int|WP_Post $post Post ID or post object
 * @param int $length Excerpt length
 * @param string $more Read more text
 * @return void
 */
function aqualuxe_excerpt($post = null, $length = 55, $more = '&hellip;') {
    echo aqualuxe_get_excerpt($post, $length, $more);
}

/**
 * Get pagination
 *
 * @param array $args Arguments
 * @return string
 */
function aqualuxe_get_pagination($args = []) {
    global $wp_query;
    
    $defaults = [
        'total' => $wp_query->max_num_pages,
        'current' => max(1, get_query_var('paged')),
        'prev_text' => '&larr; ' . esc_html__('Previous', 'aqualuxe'),
        'next_text' => esc_html__('Next', 'aqualuxe') . ' &rarr;',
        'type' => 'array',
        'end_size' => 3,
        'mid_size' => 3,
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    $links = paginate_links($args);
    
    if (!$links) {
        return '';
    }
    
    $output = '<nav class="aqualuxe-pagination">';
    $output .= '<ul class="aqualuxe-pagination-list">';
    
    foreach ($links as $link) {
        $output .= '<li class="aqualuxe-pagination-item">' . $link . '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</nav>';
    
    return $output;
}

/**
 * Display pagination
 *
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_pagination($args = []) {
    echo aqualuxe_get_pagination($args);
}

/**
 * Get social share links
 *
 * @param int|WP_Post $post Post ID or post object
 * @return array
 */
function aqualuxe_get_social_share_links($post = null) {
    $post = get_post($post);
    
    if (!$post) {
        return [];
    }
    
    $permalink = get_permalink($post);
    $title = get_the_title($post);
    
    $links = [
        'facebook' => [
            'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($permalink),
            'label' => esc_html__('Share on Facebook', 'aqualuxe'),
            'icon' => 'facebook',
        ],
        'twitter' => [
            'url' => 'https://twitter.com/intent/tweet?url=' . urlencode($permalink) . '&text=' . urlencode($title),
            'label' => esc_html__('Share on Twitter', 'aqualuxe'),
            'icon' => 'twitter',
        ],
        'linkedin' => [
            'url' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($permalink) . '&title=' . urlencode($title),
            'label' => esc_html__('Share on LinkedIn', 'aqualuxe'),
            'icon' => 'linkedin',
        ],
        'pinterest' => [
            'url' => 'https://pinterest.com/pin/create/button/?url=' . urlencode($permalink) . '&description=' . urlencode($title),
            'label' => esc_html__('Share on Pinterest', 'aqualuxe'),
            'icon' => 'pinterest',
        ],
        'email' => [
            'url' => 'mailto:?subject=' . urlencode($title) . '&body=' . urlencode($permalink),
            'label' => esc_html__('Share via Email', 'aqualuxe'),
            'icon' => 'email',
        ],
    ];
    
    return apply_filters('aqualuxe_social_share_links', $links, $post);
}

/**
 * Display social share links
 *
 * @param int|WP_Post $post Post ID or post object
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_social_share_links($post = null, $args = []) {
    $defaults = [
        'wrapper_class' => 'aqualuxe-social-share',
        'before' => '<div class="aqualuxe-social-share-wrapper">',
        'after' => '</div>',
        'before_title' => '<h3 class="aqualuxe-social-share-title">',
        'after_title' => '</h3>',
        'title' => esc_html__('Share this', 'aqualuxe'),
        'show_labels' => false,
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    $links = aqualuxe_get_social_share_links($post);
    
    if (empty($links)) {
        return;
    }
    
    echo $args['before'];
    
    if ($args['title']) {
        echo $args['before_title'] . esc_html($args['title']) . $args['after_title'];
    }
    
    echo '<ul class="' . esc_attr($args['wrapper_class']) . '">';
    
    foreach ($links as $network => $link) {
        echo '<li class="aqualuxe-social-share-item aqualuxe-social-share-' . esc_attr($network) . '">';
        echo '<a href="' . esc_url($link['url']) . '" target="_blank" rel="noopener noreferrer">';
        
        if (isset($link['icon'])) {
            aqualuxe_svg($link['icon'], [
                'class' => 'aqualuxe-social-share-icon',
                'aria_hidden' => !$args['show_labels'],
            ]);
        }
        
        if ($args['show_labels']) {
            echo '<span class="aqualuxe-social-share-label">' . esc_html($link['label']) . '</span>';
        } else {
            echo '<span class="screen-reader-text">' . esc_html($link['label']) . '</span>';
        }
        
        echo '</a>';
        echo '</li>';
    }
    
    echo '</ul>';
    
    echo $args['after'];
}

/**
 * Get related posts
 *
 * @param int|WP_Post $post Post ID or post object
 * @param int $posts_per_page Number of posts to show
 * @param array $args Arguments
 * @return WP_Query
 */
function aqualuxe_get_related_posts($post = null, $posts_per_page = 3, $args = []) {
    $post = get_post($post);
    
    if (!$post) {
        return new WP_Query();
    }
    
    $defaults = [
        'post_type' => $post->post_type,
        'post__not_in' => [$post->ID],
        'posts_per_page' => $posts_per_page,
        'ignore_sticky_posts' => true,
    ];
    
    // Get taxonomy terms
    $taxonomies = get_object_taxonomies($post->post_type);
    $terms = [];
    
    foreach ($taxonomies as $taxonomy) {
        $post_terms = wp_get_post_terms($post->ID, $taxonomy, ['fields' => 'ids']);
        
        if (!empty($post_terms) && !is_wp_error($post_terms)) {
            $terms[$taxonomy] = $post_terms;
        }
    }
    
    // If we have terms, add tax query
    if (!empty($terms)) {
        $tax_query = [];
        
        foreach ($terms as $taxonomy => $ids) {
            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => $ids,
            ];
        }
        
        if (count($tax_query) > 1) {
            $tax_query['relation'] = 'OR';
        }
        
        $defaults['tax_query'] = $tax_query;
    }
    
    $args = wp_parse_args($args, $defaults);
    
    return new WP_Query($args);
}

/**
 * Display related posts
 *
 * @param int|WP_Post $post Post ID or post object
 * @param int $posts_per_page Number of posts to show
 * @param array $args Arguments
 * @return void
 */
function aqualuxe_related_posts($post = null, $posts_per_page = 3, $args = []) {
    $query = aqualuxe_get_related_posts($post, $posts_per_page, $args);
    
    if (!$query->have_posts()) {
        return;
    }
    
    aqualuxe_get_template_part('related-posts', null, [
        'query' => $query,
    ]);
}