<?php
/**
 * Template Helper Functions
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display posts pagination
 */
function aqualuxe_posts_pagination() {
    global $wp_query;

    if ($wp_query->max_num_pages <= 1) return;

    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
    $max = intval($wp_query->max_num_pages);

    // Don't show the navigation if there's only one page
    if ($max < 2) return;

    echo '<nav class="pagination flex justify-center items-center space-x-2 my-8" role="navigation" aria-label="' . esc_attr__('Posts navigation', 'aqualuxe') . '">';
    
    $big = 999999999; // need an unlikely integer
    
    $args = [
        'base'         => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format'       => '?paged=%#%',
        'current'      => $paged,
        'total'        => $max,
        'prev_text'    => esc_html__('← Previous', 'aqualuxe'),
        'next_text'    => esc_html__('Next →', 'aqualuxe'),
        'type'         => 'array',
        'end_size'     => 3,
        'mid_size'     => 3,
    ];

    $links = paginate_links($args);

    if ($links) {
        foreach ($links as $link) {
            echo '<div class="page-number">' . $link . '</div>';
        }
    }

    echo '</nav>';
}

/**
 * Display navigation for single posts
 */
function aqualuxe_post_navigation() {
    $previous = get_previous_post_link(
        '<div class="nav-previous">%link</div>',
        '<span class="meta-nav">' . esc_html__('Previous Post', 'aqualuxe') . '</span><span class="post-title">%title</span>'
    );
    
    $next = get_next_post_link(
        '<div class="nav-next">%link</div>',
        '<span class="meta-nav">' . esc_html__('Next Post', 'aqualuxe') . '</span><span class="post-title">%title</span>'
    );

    if ($previous || $next) {
        echo '<nav class="post-navigation flex justify-between items-center py-8 border-t border-gray-200 dark:border-gray-700" role="navigation" aria-label="' . esc_attr__('Post navigation', 'aqualuxe') . '">';
        echo '<div class="nav-links flex justify-between w-full">';
        
        if ($previous) {
            echo $previous;
        } else {
            echo '<div></div>';
        }
        
        if ($next) {
            echo $next;
        } else {
            echo '<div></div>';
        }
        
        echo '</div>';
        echo '</nav>';
    }
}

/**
 * Get social media icon SVG
 */
function aqualuxe_get_social_icon($platform, $class = 'w-5 h-5') {
    $icons = [
        'facebook' => '<svg class="' . esc_attr($class) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
        'twitter' => '<svg class="' . esc_attr($class) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>',
        'instagram' => '<svg class="' . esc_attr($class) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987 6.62 0 11.987-5.367 11.987-11.987C24.014 5.367 18.637.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.596-3.185-1.534-.425-.54-.679-1.222-.679-1.963 0-.741.254-1.423.679-1.963.737-.938 1.888-1.534 3.185-1.534 1.297 0 2.448.596 3.185 1.534.425.54.679 1.222.679 1.963 0 .741-.254 1.423-.679 1.963-.737.938-1.888 1.534-3.185 1.534zm7.718-6.928H14.45c-.878 0-1.674-.352-2.25-.923-.576-.57-.923-1.358-.923-2.226V5.193c0-.868.347-1.655.923-2.226.576-.57 1.372-.923 2.25-.923h1.717c.878 0 1.674.352 2.25.923.576.57.923 1.358.923 2.226v1.718c0 .868-.347 1.655-.923 2.226-.576.57-1.372.923-2.25.923z"/></svg>',
        'linkedin' => '<svg class="' . esc_attr($class) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
        'youtube' => '<svg class="' . esc_attr($class) . '" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
    ];

    return isset($icons[$platform]) ? $icons[$platform] : '';
}

/**
 * Get breadcrumb navigation
 */
function aqualuxe_breadcrumbs() {
    if (is_home() || is_front_page()) return;

    $breadcrumbs = [];
    $breadcrumbs[] = '<a href="' . esc_url(home_url('/')) . '" class="breadcrumb-home">' . esc_html__('Home', 'aqualuxe') . '</a>';

    if (is_category() || is_single()) {
        $category = get_the_category();
        if ($category) {
            $breadcrumbs[] = '<a href="' . esc_url(get_category_link($category[0]->term_id)) . '">' . esc_html($category[0]->name) . '</a>';
        }
    }

    if (is_single()) {
        $breadcrumbs[] = '<span class="breadcrumb-current">' . get_the_title() . '</span>';
    } elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        $ancestors = array_reverse($ancestors);
        
        foreach ($ancestors as $ancestor) {
            $breadcrumbs[] = '<a href="' . esc_url(get_permalink($ancestor)) . '">' . get_the_title($ancestor) . '</a>';
        }
        
        $breadcrumbs[] = '<span class="breadcrumb-current">' . get_the_title() . '</span>';
    } elseif (is_category()) {
        $breadcrumbs[] = '<span class="breadcrumb-current">' . single_cat_title('', false) . '</span>';
    } elseif (is_tag()) {
        $breadcrumbs[] = '<span class="breadcrumb-current">' . single_tag_title('', false) . '</span>';
    } elseif (is_archive()) {
        $breadcrumbs[] = '<span class="breadcrumb-current">' . get_the_archive_title() . '</span>';
    } elseif (is_search()) {
        $breadcrumbs[] = '<span class="breadcrumb-current">' . sprintf(esc_html__('Search Results for "%s"', 'aqualuxe'), get_search_query()) . '</span>';
    } elseif (is_404()) {
        $breadcrumbs[] = '<span class="breadcrumb-current">' . esc_html__('Page Not Found', 'aqualuxe') . '</span>';
    }

    if (!empty($breadcrumbs)) {
        echo '<nav class="breadcrumbs text-sm text-gray-600 dark:text-gray-400 mb-6" aria-label="' . esc_attr__('Breadcrumb navigation', 'aqualuxe') . '">';
        echo '<ol class="flex items-center space-x-2">';
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            echo '<li class="breadcrumb-item">';
            if ($index > 0) {
                echo '<span class="breadcrumb-separator mx-2">/</span>';
            }
            echo $breadcrumb;
            echo '</li>';
        }
        
        echo '</ol>';
        echo '</nav>';
    }
}

/**
 * Custom post meta display
 */
function aqualuxe_post_meta($show_author = true, $show_date = true, $show_categories = true, $show_comments = true) {
    $meta_items = [];

    if ($show_author) {
        $meta_items[] = sprintf(
            '<span class="meta-author"><a href="%s" class="author-link">%s</a></span>',
            esc_url(get_author_posts_url(get_the_author_meta('ID'))),
            esc_html(get_the_author())
        );
    }

    if ($show_date) {
        $meta_items[] = sprintf(
            '<time class="meta-date" datetime="%s">%s</time>',
            esc_attr(get_the_date('c')),
            esc_html(get_the_date())
        );
    }

    if ($show_categories && has_category()) {
        $categories = get_the_category_list(', ');
        $meta_items[] = '<span class="meta-categories">' . $categories . '</span>';
    }

    if ($show_comments && comments_open() && get_comments_number() > 0) {
        $comments_link = sprintf(
            '<a href="%s">%s</a>',
            esc_url(get_comments_link()),
            sprintf(
                _n('%s comment', '%s comments', get_comments_number(), 'aqualuxe'),
                number_format_i18n(get_comments_number())
            )
        );
        $meta_items[] = '<span class="meta-comments">' . $comments_link . '</span>';
    }

    if (!empty($meta_items)) {
        echo '<div class="entry-meta flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-gray-400">';
        echo implode('<span class="meta-separator">•</span>', $meta_items);
        echo '</div>';
    }
}

/**
 * Custom excerpt with read more link
 */
function aqualuxe_custom_excerpt($length = 30, $more_text = null) {
    if (!$more_text) {
        $more_text = esc_html__('Read More', 'aqualuxe');
    }

    $excerpt = get_the_excerpt();
    if (empty($excerpt)) {
        $excerpt = get_the_content();
    }

    $excerpt = wp_trim_words($excerpt, $length, '...');
    $read_more = '<a href="' . esc_url(get_permalink()) . '" class="read-more-link inline-flex items-center mt-2 text-primary-600 hover:text-primary-700 font-medium">' . $more_text . ' <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></a>';

    return $excerpt . $read_more;
}

/**
 * Get related posts
 */
function aqualuxe_get_related_posts($post_id = null, $limit = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $categories = wp_get_post_categories($post_id);
    if (empty($categories)) {
        return [];
    }

    $args = [
        'category__in'   => $categories,
        'post__not_in'   => [$post_id],
        'posts_per_page' => $limit,
        'orderby'        => 'rand',
        'post_status'    => 'publish',
    ];

    $related_posts = get_posts($args);
    
    if (count($related_posts) < $limit) {
        // Fill with recent posts if not enough related posts
        $recent_args = [
            'post__not_in'   => array_merge([$post_id], wp_list_pluck($related_posts, 'ID')),
            'posts_per_page' => $limit - count($related_posts),
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ];
        
        $recent_posts = get_posts($recent_args);
        $related_posts = array_merge($related_posts, $recent_posts);
    }

    return $related_posts;
}

/**
 * Display related posts
 */
function aqualuxe_related_posts($post_id = null, $limit = 3) {
    $related_posts = aqualuxe_get_related_posts($post_id, $limit);
    
    if (empty($related_posts)) {
        return;
    }

    echo '<div class="related-posts mt-12">';
    echo '<h3 class="text-2xl font-bold mb-6">' . esc_html__('Related Posts', 'aqualuxe') . '</h3>';
    echo '<div class="grid grid-cols-1 md:grid-cols-' . min(count($related_posts), 3) . ' gap-6">';
    
    foreach ($related_posts as $post) {
        setup_postdata($post);
        ?>
        <article class="related-post card">
            <?php if (has_post_thumbnail($post->ID)) : ?>
                <div class="post-thumbnail mb-4">
                    <a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                        <?php echo get_the_post_thumbnail($post->ID, 'aqualuxe-medium', ['class' => 'w-full h-48 object-cover rounded-lg']); ?>
                    </a>
                </div>
            <?php endif; ?>
            
            <div class="post-content">
                <h4 class="post-title text-lg font-semibold mb-2">
                    <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="hover:text-primary-600 transition-colors">
                        <?php echo get_the_title($post->ID); ?>
                    </a>
                </h4>
                
                <div class="post-excerpt text-gray-600 dark:text-gray-400 text-sm">
                    <?php echo wp_trim_words(get_the_excerpt($post->ID), 15); ?>
                </div>
                
                <div class="post-meta mt-3 text-xs text-gray-500 dark:text-gray-500">
                    <?php echo get_the_date('', $post->ID); ?>
                </div>
            </div>
        </article>
        <?php
    }
    
    echo '</div>';
    echo '</div>';
    
    wp_reset_postdata();
}

/**
 * Fallback menu for primary navigation
 */
function aqualuxe_fallback_menu() {
    echo '<ul class="flex items-center space-x-8">';
    
    $pages = get_pages(['sort_column' => 'menu_order', 'number' => 5]);
    foreach ($pages as $page) {
        echo '<li><a href="' . esc_url(get_permalink($page->ID)) . '" class="nav-link">' . esc_html($page->post_title) . '</a></li>';
    }
    
    if (class_exists('WooCommerce')) {
        echo '<li><a href="' . esc_url(wc_get_page_permalink('shop')) . '" class="nav-link">' . esc_html__('Shop', 'aqualuxe') . '</a></li>';
    }
    
    echo '</ul>';
}

/**
 * Mobile fallback menu
 */
function aqualuxe_mobile_fallback_menu() {
    echo '<ul class="mobile-menu-list space-y-2">';
    
    $pages = get_pages(['sort_column' => 'menu_order']);
    foreach ($pages as $page) {
        echo '<li><a href="' . esc_url(get_permalink($page->ID)) . '" class="block py-2 px-4 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded">' . esc_html($page->post_title) . '</a></li>';
    }
    
    echo '</ul>';
}

/**
 * Get estimated reading time
 */
function aqualuxe_get_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute

    return $reading_time;
}

/**
 * Display reading time
 */
function aqualuxe_reading_time($post_id = null) {
    $reading_time = aqualuxe_get_reading_time($post_id);
    
    printf(
        '<span class="reading-time text-sm text-gray-500 dark:text-gray-400">%s</span>',
        sprintf(_n('%d min read', '%d min read', $reading_time, 'aqualuxe'), $reading_time)
    );
}

/**
 * Check if dark mode is enabled
 */
function aqualuxe_is_dark_mode_enabled() {
    return get_theme_mod('aqualuxe_dark_mode_enabled', true);
}

/**
 * Get theme color
 */
function aqualuxe_get_theme_color($color_name) {
    $colors = [
        'primary' => get_theme_mod('aqualuxe_primary_color', '#06b6d4'),
        'secondary' => get_theme_mod('aqualuxe_secondary_color', '#d946ef'),
        'aqua' => '#22d3ee',
        'luxe' => '#eab308',
        'coral' => '#ef4444',
        'kelp' => '#22c55e',
    ];

    return isset($colors[$color_name]) ? $colors[$color_name] : '';
}

/**
 * Generate inline CSS for theme colors
 */
function aqualuxe_inline_css() {
    $primary_color = aqualuxe_get_theme_color('primary');
    $secondary_color = aqualuxe_get_theme_color('secondary');

    $css = ":root {
        --aqualuxe-primary: {$primary_color};
        --aqualuxe-secondary: {$secondary_color};
    }";

    return $css;
}
