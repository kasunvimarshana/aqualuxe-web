<?php
/**
 * Template Functions
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get page layout
 */
function aqualuxe_get_page_layout()
{
    $layout = get_theme_mod('aqualuxe_default_layout', 'full-width');
    
    if (is_singular()) {
        $post_layout = get_post_meta(get_the_ID(), '_aqualuxe_page_layout', true);
        if ($post_layout) {
            $layout = $post_layout;
        }
    }
    
    return apply_filters('aqualuxe_page_layout', $layout);
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs()
{
    if (!get_theme_mod('aqualuxe_show_breadcrumbs', true)) {
        return;
    }

    $separator = '<span class="breadcrumb-separator mx-2 text-gray-400">/</span>';
    $breadcrumbs = [];

    // Home
    $breadcrumbs[] = '<a href="' . esc_url(home_url('/')) . '" class="text-primary-600 hover:text-primary-700">' . esc_html__('Home', 'aqualuxe') . '</a>';

    if (is_category()) {
        $category = get_queried_object();
        if ($category->parent) {
            $parent = get_category($category->parent);
            $breadcrumbs[] = '<a href="' . esc_url(get_category_link($parent->term_id)) . '" class="text-primary-600 hover:text-primary-700">' . esc_html($parent->name) . '</a>';
        }
        $breadcrumbs[] = '<span class="text-gray-600">' . esc_html($category->name) . '</span>';
    } elseif (is_tag()) {
        $tag = get_queried_object();
        $breadcrumbs[] = '<span class="text-gray-600">' . esc_html($tag->name) . '</span>';
    } elseif (is_author()) {
        $author = get_queried_object();
        $breadcrumbs[] = '<span class="text-gray-600">' . esc_html__('Author: ', 'aqualuxe') . esc_html($author->display_name) . '</span>';
    } elseif (is_date()) {
        if (is_year()) {
            $breadcrumbs[] = '<span class="text-gray-600">' . get_the_date('Y') . '</span>';
        } elseif (is_month()) {
            $breadcrumbs[] = '<a href="' . esc_url(get_year_link(get_the_date('Y'))) . '" class="text-primary-600 hover:text-primary-700">' . get_the_date('Y') . '</a>';
            $breadcrumbs[] = '<span class="text-gray-600">' . get_the_date('F') . '</span>';
        } elseif (is_day()) {
            $breadcrumbs[] = '<a href="' . esc_url(get_year_link(get_the_date('Y'))) . '" class="text-primary-600 hover:text-primary-700">' . get_the_date('Y') . '</a>';
            $breadcrumbs[] = '<a href="' . esc_url(get_month_link(get_the_date('Y'), get_the_date('m'))) . '" class="text-primary-600 hover:text-primary-700">' . get_the_date('F') . '</a>';
            $breadcrumbs[] = '<span class="text-gray-600">' . get_the_date('d') . '</span>';
        }
    } elseif (is_search()) {
        $breadcrumbs[] = '<span class="text-gray-600">' . esc_html__('Search Results', 'aqualuxe') . '</span>';
    } elseif (is_404()) {
        $breadcrumbs[] = '<span class="text-gray-600">' . esc_html__('Page Not Found', 'aqualuxe') . '</span>';
    } elseif (is_singular()) {
        $post = get_queried_object();
        
        if ($post->post_type !== 'page' && $post->post_type !== 'post') {
            $post_type_object = get_post_type_object($post->post_type);
            if ($post_type_object->has_archive) {
                $breadcrumbs[] = '<a href="' . esc_url(get_post_type_archive_link($post->post_type)) . '" class="text-primary-600 hover:text-primary-700">' . esc_html($post_type_object->labels->name) . '</a>';
            }
        }
        
        if ($post->post_type === 'post') {
            $categories = get_the_category();
            if (!empty($categories)) {
                $category = $categories[0];
                $breadcrumbs[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="text-primary-600 hover:text-primary-700">' . esc_html($category->name) . '</a>';
            }
        }
        
        $breadcrumbs[] = '<span class="text-gray-600">' . esc_html(get_the_title()) . '</span>';
    } elseif (is_post_type_archive()) {
        $post_type_object = get_queried_object();
        $breadcrumbs[] = '<span class="text-gray-600">' . esc_html($post_type_object->labels->name) . '</span>';
    }

    if (!empty($breadcrumbs)) {
        echo '<nav class="breadcrumbs text-sm mb-6" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
        echo '<ol class="flex items-center flex-wrap">';
        foreach ($breadcrumbs as $index => $breadcrumb) {
            echo '<li>';
            if ($index > 0) {
                echo $separator;
            }
            echo $breadcrumb;
            echo '</li>';
        }
        echo '</ol>';
        echo '</nav>';
    }
}

/**
 * Display post meta
 */
function aqualuxe_post_meta($options = [])
{
    $defaults = [
        'show_date' => true,
        'show_author' => true,
        'show_categories' => true,
        'show_comments' => true,
        'show_tags' => false,
    ];
    
    $options = wp_parse_args($options, $defaults);
    
    $meta_items = [];
    
    if ($options['show_date']) {
        $meta_items[] = '<time datetime="' . esc_attr(get_the_date('c')) . '" class="published">' . esc_html(get_the_date()) . '</time>';
    }
    
    if ($options['show_author']) {
        $meta_items[] = '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" class="author">' . esc_html(get_the_author()) . '</a>';
    }
    
    if ($options['show_categories'] && has_category()) {
        $categories = get_the_category_list(', ');
        $meta_items[] = '<span class="categories">' . $categories . '</span>';
    }
    
    if ($options['show_comments'] && (comments_open() || get_comments_number())) {
        $comments_number = get_comments_number();
        if ($comments_number == 0) {
            $comments_text = esc_html__('No comments', 'aqualuxe');
        } elseif ($comments_number == 1) {
            $comments_text = esc_html__('1 comment', 'aqualuxe');
        } else {
            $comments_text = sprintf(esc_html__('%s comments', 'aqualuxe'), number_format_i18n($comments_number));
        }
        $meta_items[] = '<a href="' . esc_url(get_comments_link()) . '" class="comments">' . $comments_text . '</a>';
    }
    
    if ($options['show_tags'] && has_tag()) {
        $tags = get_the_tag_list('', ', ');
        $meta_items[] = '<span class="tags">' . esc_html__('Tags: ', 'aqualuxe') . $tags . '</span>';
    }
    
    if (!empty($meta_items)) {
        echo '<div class="post-meta text-sm text-gray-600 space-x-4">';
        echo implode(' <span class="separator">•</span> ', $meta_items);
        echo '</div>';
    }
}

/**
 * Display social sharing buttons
 */
function aqualuxe_social_share($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post_url = urlencode(get_permalink($post_id));
    $post_title = urlencode(get_the_title($post_id));
    $post_excerpt = urlencode(wp_trim_words(get_the_excerpt($post_id), 20));
    
    $social_networks = [
        'twitter' => [
            'url' => 'https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title,
            'label' => esc_html__('Share on Twitter', 'aqualuxe'),
            'icon' => '<path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>',
            'color' => 'bg-blue-500 hover:bg-blue-600'
        ],
        'facebook' => [
            'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url,
            'label' => esc_html__('Share on Facebook', 'aqualuxe'),
            'icon' => '<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>',
            'color' => 'bg-blue-600 hover:bg-blue-700'
        ],
        'linkedin' => [
            'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $post_url,
            'label' => esc_html__('Share on LinkedIn', 'aqualuxe'),
            'icon' => '<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>',
            'color' => 'bg-blue-700 hover:bg-blue-800'
        ],
        'email' => [
            'url' => 'mailto:?subject=' . $post_title . '&body=' . $post_excerpt . '...' . urlencode(' Read more: ') . $post_url,
            'label' => esc_html__('Share via Email', 'aqualuxe'),
            'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
            'color' => 'bg-gray-500 hover:bg-gray-600',
            'stroke' => true
        ]
    ];
    
    $enabled_networks = get_theme_mod('aqualuxe_social_share_networks', array_keys($social_networks));
    
    if (!empty($enabled_networks)) {
        echo '<div class="social-share">';
        echo '<h4 class="text-sm font-medium text-gray-900 mb-3">' . esc_html__('Share this post', 'aqualuxe') . '</h4>';
        echo '<div class="flex space-x-2">';
        
        foreach ($enabled_networks as $network) {
            if (isset($social_networks[$network])) {
                $data = $social_networks[$network];
                $stroke_fill = isset($data['stroke']) && $data['stroke'] ? 'stroke' : 'fill';
                
                echo '<a href="' . esc_url($data['url']) . '" target="_blank" rel="noopener noreferrer" ';
                echo 'class="flex items-center justify-center w-10 h-10 ' . esc_attr($data['color']) . ' text-white rounded-full transition-colors" ';
                echo 'aria-label="' . esc_attr($data['label']) . '">';
                echo '<svg class="w-5 h-5" ' . $stroke_fill . '="currentColor" viewBox="0 0 24 24">';
                echo $data['icon'];
                echo '</svg>';
                echo '</a>';
            }
        }
        
        echo '</div>';
        echo '</div>';
    }
}

/**
 * Get reading time estimate
 */
function aqualuxe_reading_time($post_id = null)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Assuming 200 words per minute
    
    return $reading_time;
}

/**
 * Display reading time
 */
function aqualuxe_display_reading_time($post_id = null)
{
    $reading_time = aqualuxe_reading_time($post_id);
    
    if ($reading_time > 0) {
        echo '<span class="reading-time text-sm text-gray-600">';
        printf(esc_html(_n('%d min read', '%d min read', $reading_time, 'aqualuxe')), $reading_time);
        echo '</span>';
    }
}

/**
 * Get related posts
 */
function aqualuxe_get_related_posts($post_id = null, $limit = 3)
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    // Try to get posts from the same categories
    $categories = wp_get_post_categories($post_id);
    
    if (!empty($categories)) {
        $related_posts = get_posts([
            'post_type' => get_post_type($post_id),
            'numberposts' => $limit,
            'post__not_in' => [$post_id],
            'category__in' => $categories,
            'meta_key' => '_thumbnail_id'
        ]);
        
        if (count($related_posts) < $limit) {
            // If not enough posts, get more from the same post type
            $additional_posts = get_posts([
                'post_type' => get_post_type($post_id),
                'numberposts' => $limit - count($related_posts),
                'post__not_in' => array_merge([$post_id], wp_list_pluck($related_posts, 'ID')),
                'orderby' => 'rand'
            ]);
            
            $related_posts = array_merge($related_posts, $additional_posts);
        }
        
        return $related_posts;
    }
    
    return [];
}

/**
 * Display pagination
 */
function aqualuxe_pagination($args = [])
{
    $defaults = [
        'mid_size' => 2,
        'prev_text' => '<svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>' . esc_html__('Previous', 'aqualuxe'),
        'next_text' => esc_html__('Next', 'aqualuxe') . '<svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
        'class' => 'pagination flex justify-center items-center space-x-2 mt-12',
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    echo '<nav class="' . esc_attr($args['class']) . '" aria-label="' . esc_attr__('Posts pagination', 'aqualuxe') . '">';
    echo paginate_links($args);
    echo '</nav>';
}

/**
 * Get archive title
 */
function aqualuxe_get_archive_title()
{
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = sprintf(esc_html__('Posts by %s', 'aqualuxe'), get_the_author());
    } elseif (is_year()) {
        $title = sprintf(esc_html__('Year: %s', 'aqualuxe'), get_the_date('Y'));
    } elseif (is_month()) {
        $title = sprintf(esc_html__('Month: %s', 'aqualuxe'), get_the_date('F Y'));
    } elseif (is_day()) {
        $title = sprintf(esc_html__('Day: %s', 'aqualuxe'), get_the_date('F j, Y'));
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } else {
        $title = esc_html__('Archives', 'aqualuxe');
    }
    
    return apply_filters('aqualuxe_archive_title', $title);
}

/**
 * Get archive description
 */
function aqualuxe_get_archive_description()
{
    $description = '';
    
    if (is_category() || is_tag() || is_tax()) {
        $description = term_description();
    } elseif (is_author()) {
        $description = get_the_author_meta('description');
    } elseif (is_post_type_archive()) {
        $post_type = get_query_var('post_type');
        if (is_array($post_type)) {
            $post_type = reset($post_type);
        }
        $post_type_object = get_post_type_object($post_type);
        if ($post_type_object) {
            $description = $post_type_object->description;
        }
    }
    
    return apply_filters('aqualuxe_archive_description', $description);
}

/**
 * Display featured image with overlay
 */
function aqualuxe_featured_image_overlay($post_id = null, $size = 'large')
{
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (has_post_thumbnail($post_id)) {
        echo '<div class="featured-image-overlay relative overflow-hidden">';
        echo get_the_post_thumbnail($post_id, $size, ['class' => 'w-full h-auto']);
        echo '<div class="overlay absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>';
        echo '</div>';
    }
}

/**
 * Get logo HTML
 */
function aqualuxe_get_logo()
{
    if (has_custom_logo()) {
        return get_custom_logo();
    }
    
    $site_name = get_bloginfo('name');
    $tagline = get_bloginfo('description');
    
    $logo_html = '<div class="site-branding">';
    $logo_html .= '<h1 class="site-title text-2xl font-bold">';
    $logo_html .= '<a href="' . esc_url(home_url('/')) . '" rel="home" class="text-primary-800 hover:text-primary-600 transition-colors">';
    $logo_html .= esc_html($site_name);
    $logo_html .= '</a>';
    $logo_html .= '</h1>';
    
    if ($tagline) {
        $logo_html .= '<p class="site-description text-sm text-gray-600">' . esc_html($tagline) . '</p>';
    }
    
    $logo_html .= '</div>';
    
    return $logo_html;
}

/**
 * Display schema markup
 */
function aqualuxe_schema_markup($type = 'WebSite')
{
    if (!get_option('aqualuxe_enable_schema', true)) {
        return;
    }
    
    $schema = [];
    
    switch ($type) {
        case 'WebSite':
            $schema = [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => get_bloginfo('name'),
                'description' => get_bloginfo('description'),
                'url' => home_url('/'),
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => home_url('/?s={search_term_string}'),
                    'query-input' => 'required name=search_term_string'
                ]
            ];
            break;
            
        case 'Article':
            if (is_singular('post')) {
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'headline' => get_the_title(),
                    'description' => get_the_excerpt(),
                    'url' => get_permalink(),
                    'datePublished' => get_the_date('c'),
                    'dateModified' => get_the_modified_date('c'),
                    'author' => [
                        '@type' => 'Person',
                        'name' => get_the_author()
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => get_bloginfo('name'),
                        'url' => home_url('/')
                    ]
                ];
                
                if (has_post_thumbnail()) {
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
                    if ($image) {
                        $schema['image'] = $image[0];
                    }
                }
            }
            break;
            
        case 'Organization':
            $schema = [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url('/'),
                'description' => get_bloginfo('description')
            ];
            
            if (has_custom_logo()) {
                $logo_id = get_theme_mod('custom_logo');
                $logo_url = wp_get_attachment_image_url($logo_id, 'full');
                if ($logo_url) {
                    $schema['logo'] = $logo_url;
                }
            }
            break;
    }
    
    if (!empty($schema)) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
    }
}