<?php
/**
 * Template helper functions
 *
 * @package AquaLuxe
 */

/**
 * Get template part with passed args
 *
 * @param string $slug Template slug
 * @param string $name Template name
 * @param array  $args Template arguments
 */
function aqualuxe_get_template_part($slug, $name = null, $args = array()) {
    if (empty($args)) {
        get_template_part($slug, $name);
        return;
    }

    // Extract args to make them available in the template
    extract($args);

    // Include the template
    include(locate_template("{$slug}-{$name}.php"));
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    // Check if breadcrumbs are enabled
    if (!get_theme_mod('aqualuxe_enable_breadcrumbs', true)) {
        return;
    }

    // Use WooCommerce breadcrumbs if available and on WooCommerce pages
    if (aqualuxe_is_woocommerce_active() && (is_woocommerce() || is_cart() || is_checkout())) {
        woocommerce_breadcrumb(array(
            'wrap_before' => '<nav class="woocommerce-breadcrumb breadcrumbs" itemprop="breadcrumb">',
            'wrap_after'  => '</nav>',
        ));
        return;
    }

    // Custom breadcrumbs implementation
    $home_label = esc_html__('Home', 'aqualuxe');
    $separator = '<span class="breadcrumb-separator">/</span>';
    
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">';
    echo '<a href="' . esc_url(home_url('/')) . '">' . $home_label . '</a>';

    if (is_category() || is_single()) {
        echo $separator;
        
        if (is_single()) {
            $categories = get_the_category();
            if (!empty($categories)) {
                echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
                echo $separator;
            }
            echo '<span class="current">' . get_the_title() . '</span>';
        } else {
            echo '<span class="current">' . single_cat_title('', false) . '</span>';
        }
    } elseif (is_page()) {
        echo $separator;
        echo '<span class="current">' . get_the_title() . '</span>';
    } elseif (is_search()) {
        echo $separator;
        echo '<span class="current">' . sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), get_search_query()) . '</span>';
    } elseif (is_404()) {
        echo $separator;
        echo '<span class="current">' . esc_html__('Page Not Found', 'aqualuxe') . '</span>';
    } elseif (is_home()) {
        echo $separator;
        echo '<span class="current">' . esc_html__('Blog', 'aqualuxe') . '</span>';
    } elseif (is_archive()) {
        echo $separator;
        echo '<span class="current">' . get_the_archive_title() . '</span>';
    }

    echo '</nav>';
}

/**
 * Display pagination
 */
function aqualuxe_pagination() {
    $pagination_type = get_theme_mod('aqualuxe_pagination_type', 'numbered');

    if ($pagination_type === 'numbered') {
        the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => '<span class="screen-reader-text">' . esc_html__('Previous', 'aqualuxe') . '</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>',
            'next_text' => '<span class="screen-reader-text">' . esc_html__('Next', 'aqualuxe') . '</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>',
        ));
    } else {
        the_posts_navigation(array(
            'prev_text' => '<span class="nav-previous-text">' . esc_html__('Older posts', 'aqualuxe') . '</span>',
            'next_text' => '<span class="nav-next-text">' . esc_html__('Newer posts', 'aqualuxe') . '</span>',
        ));
    }
}

/**
 * Display post thumbnail with fallback
 *
 * @param string $size Thumbnail size
 */
function aqualuxe_post_thumbnail($size = 'post-thumbnail') {
    if (post_password_required() || is_attachment()) {
        return;
    }

    if (has_post_thumbnail()) {
        echo '<div class="post-thumbnail">';
        the_post_thumbnail($size, array(
            'class' => 'img-fluid',
            'alt'   => get_the_title(),
        ));
        echo '</div>';
    } else {
        // Fallback image
        $fallback_image = get_theme_mod('aqualuxe_fallback_thumbnail');
        if ($fallback_image) {
            echo '<div class="post-thumbnail">';
            echo '<img src="' . esc_url($fallback_image) . '" alt="' . esc_attr(get_the_title()) . '" class="img-fluid wp-post-image">';
            echo '</div>';
        }
    }
}

/**
 * Display post meta
 */
function aqualuxe_post_meta() {
    // Check if post meta is enabled
    if (!get_theme_mod('aqualuxe_show_post_meta', true)) {
        return;
    }

    echo '<div class="entry-meta">';
    
    // Author
    if (get_theme_mod('aqualuxe_show_post_author', true)) {
        echo '<span class="byline">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" /></svg>';
        echo '<span class="author vcard">';
        echo '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a>';
        echo '</span>';
        echo '</span>';
    }
    
    // Date
    if (get_theme_mod('aqualuxe_show_post_date', true)) {
        echo '<span class="posted-on">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" /></svg>';
        echo '<time class="entry-date published" datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>';
        echo '</span>';
    }
    
    // Categories
    if (get_theme_mod('aqualuxe_show_post_categories', true) && has_category()) {
        echo '<span class="cat-links">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M5.5 3A2.5 2.5 0 003 5.5v2.879a2.5 2.5 0 00.732 1.767l6.5 6.5a2.5 2.5 0 003.536 0l2.878-2.878a2.5 2.5 0 000-3.536l-6.5-6.5A2.5 2.5 0 008.38 3H5.5zM6 7a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>';
        the_category(', ');
        echo '</span>';
    }
    
    // Comments
    if (get_theme_mod('aqualuxe_show_post_comments', true) && comments_open()) {
        echo '<span class="comments-link">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M10 2c-2.236 0-4.43.18-6.57.524C1.993 2.755 1 4.014 1 5.426v5.148c0 1.413.993 2.67 2.43 2.902 1.168.188 2.352.327 3.55.414.28.02.521.18.642.413l1.713 3.293a.75.75 0 001.33 0l1.713-3.293a.783.783 0 01.642-.413 41.102 41.102 0 003.55-.414c1.437-.231 2.43-1.49 2.43-2.902V5.426c0-1.413-.993-2.67-2.43-2.902A41.289 41.289 0 0010 2zM6.75 6a.75.75 0 000 1.5h6.5a.75.75 0 000-1.5h-6.5zm0 2.5a.75.75 0 000 1.5h3.5a.75.75 0 000-1.5h-3.5z" clip-rule="evenodd" /></svg>';
        comments_popup_link(
            esc_html__('Leave a comment', 'aqualuxe'),
            esc_html__('1 Comment', 'aqualuxe'),
            esc_html__('% Comments', 'aqualuxe')
        );
        echo '</span>';
    }
    
    echo '</div>';
}

/**
 * Display post tags
 */
function aqualuxe_post_tags() {
    // Check if post tags are enabled
    if (!get_theme_mod('aqualuxe_show_post_tags', true)) {
        return;
    }

    if (has_tag()) {
        echo '<div class="entry-tags">';
        echo '<span class="tags-title">' . esc_html__('Tags:', 'aqualuxe') . '</span> ';
        the_tags('', ', ', '');
        echo '</div>';
    }
}

/**
 * Display social sharing buttons
 */
function aqualuxe_social_sharing() {
    // Check if social sharing is enabled
    if (!get_theme_mod('aqualuxe_enable_social_sharing', true)) {
        return;
    }

    $post_url = urlencode(get_permalink());
    $post_title = urlencode(get_the_title());
    $post_thumbnail = has_post_thumbnail() ? urlencode(get_the_post_thumbnail_url(get_the_ID(), 'full')) : '';

    echo '<div class="social-sharing">';
    echo '<span class="share-title">' . esc_html__('Share:', 'aqualuxe') . '</span>';
    
    // Facebook
    if (get_theme_mod('aqualuxe_enable_facebook_sharing', true)) {
        echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $post_url . '" target="_blank" rel="noopener noreferrer" class="share-facebook">';
        echo '<span class="screen-reader-text">' . esc_html__('Share on Facebook', 'aqualuxe') . '</span>';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-5 h-5"><path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>';
        echo '</a>';
    }
    
    // Twitter
    if (get_theme_mod('aqualuxe_enable_twitter_sharing', true)) {
        echo '<a href="https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title . '" target="_blank" rel="noopener noreferrer" class="share-twitter">';
        echo '<span class="screen-reader-text">' . esc_html__('Share on Twitter', 'aqualuxe') . '</span>';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5"><path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>';
        echo '</a>';
    }
    
    // LinkedIn
    if (get_theme_mod('aqualuxe_enable_linkedin_sharing', true)) {
        echo '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title . '" target="_blank" rel="noopener noreferrer" class="share-linkedin">';
        echo '<span class="screen-reader-text">' . esc_html__('Share on LinkedIn', 'aqualuxe') . '</span>';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-5 h-5"><path fill="currentColor" d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>';
        echo '</a>';
    }
    
    // Pinterest (only if post has thumbnail)
    if (get_theme_mod('aqualuxe_enable_pinterest_sharing', true) && $post_thumbnail) {
        echo '<a href="https://pinterest.com/pin/create/button/?url=' . $post_url . '&media=' . $post_thumbnail . '&description=' . $post_title . '" target="_blank" rel="noopener noreferrer" class="share-pinterest">';
        echo '<span class="screen-reader-text">' . esc_html__('Share on Pinterest', 'aqualuxe') . '</span>';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-5 h-5"><path fill="currentColor" d="M204 6.5C101.4 6.5 0 74.9 0 185.6 0 256 39.6 296 63.6 296c9.9 0 15.6-27.6 15.6-35.4 0-9.3-23.7-29.1-23.7-67.8 0-80.4 61.2-137.4 140.4-137.4 68.1 0 118.5 38.7 118.5 109.8 0 53.1-21.3 152.7-90.3 152.7-24.9 0-46.2-18-46.2-43.8 0-37.8 26.4-74.4 26.4-113.4 0-66.2-93.9-54.2-93.9 25.8 0 16.8 2.1 35.4 9.6 50.7-13.8 59.4-42 147.9-42 209.1 0 18.9 2.7 37.5 4.5 56.4 3.4 3.8 1.7 3.4 6.9 1.5 50.4-69 48.6-82.5 71.4-172.8 12.3 23.4 44.1 36 69.3 36 106.2 0 153.9-103.5 153.9-196.8C384 71.3 298.2 6.5 204 6.5z"/></svg>';
        echo '</a>';
    }
    
    // Email
    if (get_theme_mod('aqualuxe_enable_email_sharing', true)) {
        echo '<a href="mailto:?subject=' . $post_title . '&body=' . esc_html__('Check out this article:', 'aqualuxe') . ' ' . $post_url . '" class="share-email">';
        echo '<span class="screen-reader-text">' . esc_html__('Share via Email', 'aqualuxe') . '</span>';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5"><path fill="currentColor" d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg>';
        echo '</a>';
    }
    
    echo '</div>';
}

/**
 * Display related posts
 */
function aqualuxe_related_posts() {
    // Check if related posts are enabled
    if (!get_theme_mod('aqualuxe_show_related_posts', true)) {
        return;
    }

    $current_post_id = get_the_ID();
    $categories = get_the_category($current_post_id);
    
    if (empty($categories)) {
        return;
    }
    
    $category_ids = array();
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
    
    $args = array(
        'category__in'        => $category_ids,
        'post__not_in'        => array($current_post_id),
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => 1,
    );
    
    $related_query = new WP_Query($args);
    
    if ($related_query->have_posts()) {
        echo '<div class="related-posts">';
        echo '<h3 class="related-posts-title">' . esc_html__('Related Posts', 'aqualuxe') . '</h3>';
        echo '<div class="related-posts-grid">';
        
        while ($related_query->have_posts()) {
            $related_query->the_post();
            
            echo '<article class="related-post">';
            
            if (has_post_thumbnail()) {
                echo '<a href="' . esc_url(get_permalink()) . '" class="related-post-thumbnail">';
                the_post_thumbnail('aqualuxe-blog-thumbnail', array(
                    'class' => 'img-fluid',
                    'alt'   => get_the_title(),
                ));
                echo '</a>';
            }
            
            echo '<div class="related-post-content">';
            echo '<h4 class="related-post-title"><a href="' . esc_url(get_permalink()) . '">' . get_the_title() . '</a></h4>';
            echo '<div class="related-post-meta">' . get_the_date() . '</div>';
            echo '</div>';
            
            echo '</article>';
        }
        
        echo '</div>';
        echo '</div>';
        
        wp_reset_postdata();
    }
}

/**
 * Display author bio
 */
function aqualuxe_author_bio() {
    // Check if author bio is enabled
    if (!get_theme_mod('aqualuxe_show_author_bio', true)) {
        return;
    }

    if (get_the_author_meta('description')) {
        echo '<div class="author-bio">';
        echo '<div class="author-avatar">';
        echo get_avatar(get_the_author_meta('ID'), 100);
        echo '</div>';
        
        echo '<div class="author-content">';
        echo '<h3 class="author-name">' . esc_html__('About', 'aqualuxe') . ' ' . get_the_author() . '</h3>';
        echo '<div class="author-description">' . wpautop(get_the_author_meta('description')) . '</div>';
        echo '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" class="author-link">' . sprintf(esc_html__('View all posts by %s', 'aqualuxe'), get_the_author()) . '</a>';
        echo '</div>';
        
        echo '</div>';
    }
}

/**
 * Display post navigation
 */
function aqualuxe_post_navigation() {
    // Check if post navigation is enabled
    if (!get_theme_mod('aqualuxe_show_post_navigation', true)) {
        return;
    }

    the_post_navigation(array(
        'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
        'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
    ));
}

/**
 * Display comments
 */
function aqualuxe_comments() {
    // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) {
        comments_template();
    }
}

/**
 * Display page header
 */
function aqualuxe_page_header() {
    // Check if page header is enabled
    if (!get_theme_mod('aqualuxe_show_page_header', true)) {
        return;
    }

    echo '<header class="page-header">';
    
    if (is_singular()) {
        echo '<h1 class="page-title">' . get_the_title() . '</h1>';
    } elseif (is_archive()) {
        the_archive_title('<h1 class="page-title">', '</h1>');
        the_archive_description('<div class="archive-description">', '</div>');
    } elseif (is_search()) {
        echo '<h1 class="page-title">' . sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>') . '</h1>';
    } elseif (is_404()) {
        echo '<h1 class="page-title">' . esc_html__('Oops! That page can&rsquo;t be found.', 'aqualuxe') . '</h1>';
    } elseif (is_home()) {
        echo '<h1 class="page-title">' . esc_html__('Blog', 'aqualuxe') . '</h1>';
    }
    
    // Display breadcrumbs if enabled
    if (get_theme_mod('aqualuxe_enable_breadcrumbs', true)) {
        aqualuxe_breadcrumbs();
    }
    
    echo '</header>';
}

/**
 * Display page title
 */
function aqualuxe_page_title() {
    if (is_singular()) {
        the_title('<h1 class="entry-title">', '</h1>');
    } elseif (is_archive()) {
        the_archive_title('<h1 class="page-title">', '</h1>');
    } elseif (is_search()) {
        echo '<h1 class="page-title">' . sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>') . '</h1>';
    } elseif (is_404()) {
        echo '<h1 class="page-title">' . esc_html__('Oops! That page can&rsquo;t be found.', 'aqualuxe') . '</h1>';
    } elseif (is_home()) {
        echo '<h1 class="page-title">' . esc_html__('Blog', 'aqualuxe') . '</h1>';
    }
}