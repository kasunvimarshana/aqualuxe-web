<?php
/**
 * Template Functions
 *
 * Helper functions for template rendering
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display posted on date
 */
function aqualuxe_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf($time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date()),
        esc_attr(get_the_modified_date(DATE_W3C)),
        esc_html(get_the_modified_date())
    );

    $posted_on = sprintf(
        /* translators: %s: post date. */
        esc_html_x('Posted on %s', 'post date', 'aqualuxe'),
        '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display posted by author
 */
function aqualuxe_posted_by() {
    $byline = sprintf(
        /* translators: %s: post author. */
        esc_html_x('by %s', 'post author', 'aqualuxe'),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );

    echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display entry footer information
 */
function aqualuxe_entry_footer() {
    // Hide category and tag text for pages.
    if ('post' === get_post_type()) {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list(esc_html__(', ', 'aqualuxe'));
        if ($categories_list) {
            /* translators: 1: list of categories. */
            printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'aqualuxe') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'aqualuxe'));
        if ($tags_list) {
            /* translators: 1: list of tags. */
            printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'aqualuxe') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
        echo '<span class="comments-link">';
        comments_popup_link(
            sprintf(
                wp_kses(
                    /* translators: %s: post title */
                    __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            )
        );
        echo '</span>';
    }

    edit_post_link(
        sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
            ),
            wp_kses_post(get_the_title())
        ),
        '<span class="edit-link">',
        '</span>'
    );
}

/**
 * Get theme option with default fallback
 *
 * @param string $option Option name
 * @param mixed $default Default value
 * @return mixed
 */
function aqualuxe_get_option($option, $default = null) {
    $theme_options = get_option('aqualuxe_theme_options', array());
    return isset($theme_options[$option]) ? $theme_options[$option] : $default;
}

/**
 * Site branding display with enhanced template part system
 */
function aqualuxe_site_branding() {
    get_template_part('template-parts/header/site-branding');
}

/**
 * Primary navigation display with enhanced template part system
 */
function aqualuxe_primary_navigation() {
    get_template_part('template-parts/navigation/primary-nav');
}

/**
 * Get footer navigation menu
 */
function aqualuxe_footer_navigation() {
    wp_nav_menu(array(
        'theme_location'  => 'footer',
        'menu_id'         => 'footer-menu',
        'menu_class'      => 'footer-navigation flex flex-wrap space-x-6',
        'container'       => 'nav',
        'container_class' => 'footer-navigation-container',
        'depth'           => 1,
        'fallback_cb'     => false,
    ));
}

/**
 * Fallback menu for primary navigation
 */
function aqualuxe_fallback_menu() {
    echo '<nav class="main-navigation">';
    echo '<ul class="primary-navigation flex space-x-6">';
    
    wp_list_pages(array(
        'title_li' => '',
        'echo'     => true,
        'depth'    => 1,
    ));
    
    echo '</ul>';
    echo '</nav>';
}

/**
 * Get breadcrumb navigation
 */
function aqualuxe_breadcrumb() {
    if (is_front_page()) {
        return;
    }

    $breadcrumb = array();
    $breadcrumb[] = array(
        'title' => __('Home', 'aqualuxe'),
        'url'   => home_url('/'),
    );

    if (is_category() || is_single()) {
        $category = get_the_category();
        if ($category) {
            foreach ($category as $cat) {
                $breadcrumb[] = array(
                    'title' => $cat->name,
                    'url'   => get_category_link($cat->term_id),
                );
            }
        }
    }

    if (is_single()) {
        $breadcrumb[] = array(
            'title' => get_the_title(),
            'url'   => '',
        );
    }

    if (is_page()) {
        $breadcrumb[] = array(
            'title' => get_the_title(),
            'url'   => '',
        );
    }

    if (is_tag()) {
        $breadcrumb[] = array(
            'title' => single_tag_title('', false),
            'url'   => '',
        );
    }

    if (is_archive()) {
        $breadcrumb[] = array(
            'title' => get_the_archive_title(),
            'url'   => '',
        );
    }

    if (is_search()) {
        $breadcrumb[] = array(
            'title' => sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query()),
            'url'   => '',
        );
    }

    if (is_404()) {
        $breadcrumb[] = array(
            'title' => __('404 Error', 'aqualuxe'),
            'url'   => '',
        );
    }

    if (!empty($breadcrumb)) {
        echo '<nav class="breadcrumb" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
        echo '<ol class="breadcrumb-list flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">';
        
        foreach ($breadcrumb as $index => $item) {
            $is_last = ($index === count($breadcrumb) - 1);
            
            echo '<li class="breadcrumb-item">';
            
            if (!$is_last && !empty($item['url'])) {
                echo '<a href="' . esc_url($item['url']) . '" class="hover:text-primary-600">' . esc_html($item['title']) . '</a>';
            } else {
                echo '<span' . ($is_last ? ' aria-current="page"' : '') . '>' . esc_html($item['title']) . '</span>';
            }
            
            if (!$is_last) {
                echo '<span class="breadcrumb-separator ml-2 mr-2">/</span>';
            }
            
            echo '</li>';
        }
        
        echo '</ol>';
        echo '</nav>';
    }
}

/**
 * Get pagination
 */
function aqualuxe_pagination() {
    global $wp_query;

    $big = 999999999; // need an unlikely integer

    $paginate_links = paginate_links(array(
        'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format'    => '?paged=%#%',
        'current'   => max(1, get_query_var('paged')),
        'total'     => $wp_query->max_num_pages,
        'prev_text' => __('&laquo; Previous', 'aqualuxe'),
        'next_text' => __('Next &raquo;', 'aqualuxe'),
        'type'      => 'array',
    ));

    if ($paginate_links) {
        echo '<nav class="pagination" aria-label="' . esc_attr__('Posts pagination', 'aqualuxe') . '">';
        echo '<ul class="pagination-list flex items-center justify-center space-x-2">';
        
        foreach ($paginate_links as $link) {
            echo '<li class="pagination-item">' . $link . '</li>';
        }
        
        echo '</ul>';
        echo '</nav>';
    }
}

/**
 * Get related posts
 */
function aqualuxe_related_posts($post_id = null, $limit = 3) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $categories = wp_get_post_categories($post_id);
    
    if (empty($categories)) {
        return;
    }

    $args = array(
        'category__in'   => $categories,
        'post__not_in'   => array($post_id),
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'orderby'        => 'rand',
    );

    $related_posts = new WP_Query($args);

    if ($related_posts->have_posts()) {
        echo '<div class="related-posts section-padding-sm">';
        echo '<div class="container mx-auto px-4">';
        echo '<h3 class="text-2xl font-bold mb-8 text-center">' . esc_html__('Related Posts', 'aqualuxe') . '</h3>';
        echo '<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">';
        
        while ($related_posts->have_posts()) {
            $related_posts->the_post();
            
            echo '<article class="related-post card">';
            
            if (has_post_thumbnail()) {
                echo '<div class="post-thumbnail mb-4">';
                echo '<a href="' . esc_url(get_permalink()) . '">';
                the_post_thumbnail('aqualuxe-thumbnail', array('class' => 'w-full h-48 object-cover rounded-lg'));
                echo '</a>';
                echo '</div>';
            }
            
            echo '<h4 class="post-title text-lg font-semibold mb-2">';
            echo '<a href="' . esc_url(get_permalink()) . '" class="hover:text-primary-600">' . esc_html(get_the_title()) . '</a>';
            echo '</h4>';
            
            echo '<div class="post-excerpt text-gray-600 dark:text-gray-400">';
            echo wp_trim_words(get_the_excerpt(), 15, '...');
            echo '</div>';
            
            echo '</article>';
        }
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
        
        wp_reset_postdata();
    }
}

/**
 * Get social sharing buttons
 */
function aqualuxe_social_sharing() {
    $post_url = urlencode(get_permalink());
    $post_title = urlencode(get_the_title());
    
    echo '<div class="social-sharing">';
    echo '<h4 class="text-lg font-semibold mb-4">' . esc_html__('Share this post', 'aqualuxe') . '</h4>';
    echo '<div class="social-buttons flex space-x-3">';
    
    // Facebook
    echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $post_url . '" target="_blank" rel="noopener" class="social-button facebook bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">';
    echo esc_html__('Facebook', 'aqualuxe');
    echo '</a>';
    
    // Twitter
    echo '<a href="https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title . '" target="_blank" rel="noopener" class="social-button twitter bg-blue-400 text-white px-4 py-2 rounded hover:bg-blue-500">';
    echo esc_html__('Twitter', 'aqualuxe');
    echo '</a>';
    
    // LinkedIn
    echo '<a href="https://www.linkedin.com/sharing/share-offsite/?url=' . $post_url . '" target="_blank" rel="noopener" class="social-button linkedin bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">';
    echo esc_html__('LinkedIn', 'aqualuxe');
    echo '</a>';
    
    // Pinterest
    echo '<a href="https://pinterest.com/pin/create/button/?url=' . $post_url . '&description=' . $post_title . '" target="_blank" rel="noopener" class="social-button pinterest bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">';
    echo esc_html__('Pinterest', 'aqualuxe');
    echo '</a>';
    
    echo '</div>';
    echo '</div>';
}

/**
 * Get theme color scheme classes
 */
function aqualuxe_color_scheme_class() {
    $color_scheme = get_theme_mod('aqualuxe_color_scheme', 'blue');
    return 'color-scheme-' . sanitize_html_class($color_scheme);
}

/**
 * Check if dark mode is enabled
 */
function aqualuxe_is_dark_mode() {
    // Check cookie or user preference
    if (isset($_COOKIE['aqualuxe_dark_mode'])) {
        return $_COOKIE['aqualuxe_dark_mode'] === 'enabled';
    }
    
    // Default to system preference
    return false;
}

/**
 * Get reading time for post
 */
function aqualuxe_reading_time($post_id = null) {
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
function aqualuxe_display_reading_time() {
    $reading_time = aqualuxe_reading_time();
    
    if ($reading_time > 0) {
        printf(
            '<span class="reading-time text-sm text-gray-600 dark:text-gray-400">%s</span>',
            sprintf(
                /* translators: %d: reading time in minutes */
                _n('%d min read', '%d min read', $reading_time, 'aqualuxe'),
                $reading_time
            )
        );
    }
}

/**
 * Enhanced template part loader with context support
 */
function aqualuxe_get_template_part($slug, $name = '', $args = array()) {
    $templates = array();
    
    if ($name) {
        $templates[] = "{$slug}-{$name}.php";
    }
    $templates[] = "{$slug}.php";
    
    // Look for template parts in theme directory
    $located = locate_template($templates);
    
    if ($located) {
        // Extract variables for template
        if (!empty($args) && is_array($args)) {
            extract($args, EXTR_SKIP);
        }
        
        include $located;
    }
}

/**
 * Enhanced breadcrumb navigation with schema markup
 */
function aqualuxe_breadcrumbs() {
    if (is_front_page()) {
        return;
    }
    
    $separator = ' <span class="separator text-gray-400 mx-2" aria-hidden="true">/</span> ';
    $home_title = esc_html__('Home', 'aqualuxe');
    
    echo '<nav class="breadcrumbs py-4" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
    echo '<ol class="breadcrumb-list flex items-center text-sm text-gray-600 dark:text-gray-300" itemscope itemtype="https://schema.org/BreadcrumbList">';
    
    $position = 1;
    
    // Home link
    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    echo '<a href="' . esc_url(home_url('/')) . '" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors" itemprop="item">';
    echo '<span itemprop="name">' . $home_title . '</span>';
    echo '</a>';
    echo '<meta itemprop="position" content="' . $position++ . '" />';
    echo '</li>';
    
    if (is_category() || is_single()) {
        echo $separator;
        if (is_single()) {
            $category = get_the_category();
            if ($category) {
                echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                echo '<a href="' . esc_url(get_category_link($category[0]->term_id)) . '" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors" itemprop="item">';
                echo '<span itemprop="name">' . esc_html($category[0]->name) . '</span>';
                echo '</a>';
                echo '<meta itemprop="position" content="' . $position++ . '" />';
                echo '</li>';
                echo $separator;
            }
            echo '<li class="breadcrumb-item current" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<span itemprop="name">' . esc_html(get_the_title()) . '</span>';
            echo '<meta itemprop="position" content="' . $position++ . '" />';
            echo '</li>';
        } else {
            echo '<li class="breadcrumb-item current" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<span itemprop="name">' . esc_html(single_cat_title('', false)) . '</span>';
            echo '<meta itemprop="position" content="' . $position++ . '" />';
            echo '</li>';
        }
    } elseif (is_page()) {
        $parent_id = wp_get_post_parent_id(get_the_ID());
        if ($parent_id) {
            $parents = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $parents[] = $page;
                $parent_id = $page->post_parent;
            }
            $parents = array_reverse($parents);
            
            foreach ($parents as $parent) {
                echo $separator;
                echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                echo '<a href="' . esc_url(get_permalink($parent->ID)) . '" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors" itemprop="item">';
                echo '<span itemprop="name">' . esc_html($parent->post_title) . '</span>';
                echo '</a>';
                echo '<meta itemprop="position" content="' . $position++ . '" />';
                echo '</li>';
            }
        }
        echo $separator;
        echo '<li class="breadcrumb-item current" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html(get_the_title()) . '</span>';
        echo '<meta itemprop="position" content="' . $position++ . '" />';
        echo '</li>';
    } elseif (is_search()) {
        echo $separator;
        echo '<li class="breadcrumb-item current" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html__('Search Results', 'aqualuxe') . '</span>';
        echo '<meta itemprop="position" content="' . $position++ . '" />';
        echo '</li>';
    } elseif (is_archive()) {
        echo $separator;
        echo '<li class="breadcrumb-item current" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html(get_the_archive_title()) . '</span>';
        echo '<meta itemprop="position" content="' . $position++ . '" />';
        echo '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}