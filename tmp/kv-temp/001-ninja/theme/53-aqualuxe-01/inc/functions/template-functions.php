<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 *
 * @return void
 */
function aqualuxe_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');

/**
 * Add custom classes to the body
 *
 * @param array $classes Body classes
 * @return array
 */
function aqualuxe_body_classes($classes) {
    // Add a class if there is a custom header
    if (has_header_image()) {
        $classes[] = 'has-header-image';
    }

    // Add a class for the sidebar position
    $sidebar_position = get_theme_mod('sidebar_position', 'right');
    $classes[] = 'sidebar-' . $sidebar_position;

    // Add a class for the header layout
    $header_layout = get_theme_mod('header_layout', 'default');
    $classes[] = 'header-' . $header_layout;

    // Add a class for the blog layout
    if (is_home() || is_archive() || is_search()) {
        $blog_layout = get_theme_mod('blog_layout', 'grid');
        $classes[] = 'blog-' . $blog_layout;
    }

    // Add a class for WooCommerce
    if (class_exists('WooCommerce')) {
        $classes[] = 'woocommerce-active';
    } else {
        $classes[] = 'woocommerce-inactive';
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add custom classes to the post
 *
 * @param array $classes Post classes
 * @return array
 */
function aqualuxe_post_classes($classes) {
    // Add a class for the featured image
    if (has_post_thumbnail()) {
        $classes[] = 'has-post-thumbnail';
    }

    return $classes;
}
add_filter('post_class', 'aqualuxe_post_classes');

/**
 * Add a custom excerpt length
 *
 * @param int $length Excerpt length
 * @return int
 */
function aqualuxe_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Change the excerpt more string
 *
 * @param string $more More string
 * @return string
 */
function aqualuxe_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Get the sidebar position
 *
 * @return string
 */
function aqualuxe_get_sidebar_position() {
    $position = get_theme_mod('sidebar_position', 'right');

    // No sidebar on WooCommerce single product
    if (function_exists('is_product') && is_product()) {
        $position = 'none';
    }

    return apply_filters('aqualuxe_sidebar_position', $position);
}

/**
 * Check if the sidebar should be displayed
 *
 * @return bool
 */
function aqualuxe_has_sidebar() {
    $position = aqualuxe_get_sidebar_position();

    return 'none' !== $position && is_active_sidebar('sidebar-1');
}

/**
 * Get the container class
 *
 * @return string
 */
function aqualuxe_get_container_class() {
    $class = 'container mx-auto px-4';

    return apply_filters('aqualuxe_container_class', $class);
}

/**
 * Get the content class
 *
 * @return string
 */
function aqualuxe_get_content_class() {
    $class = 'site-content';

    if (aqualuxe_has_sidebar()) {
        $position = aqualuxe_get_sidebar_position();
        $class .= ' grid grid-cols-1 lg:grid-cols-12 gap-8';

        if ('left' === $position) {
            $class .= ' lg:flex-row-reverse';
        }
    }

    return apply_filters('aqualuxe_content_class', $class);
}

/**
 * Get the main content class
 *
 * @return string
 */
function aqualuxe_get_main_class() {
    $class = 'site-main';

    if (aqualuxe_has_sidebar()) {
        $class .= ' lg:col-span-8';
    } else {
        $class .= ' w-full';
    }

    return apply_filters('aqualuxe_main_class', $class);
}

/**
 * Get the sidebar class
 *
 * @return string
 */
function aqualuxe_get_sidebar_class() {
    $class = 'widget-area lg:col-span-4';

    return apply_filters('aqualuxe_sidebar_class', $class);
}

/**
 * Check if the current page is a WooCommerce page
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_page() {
    if (function_exists('is_woocommerce') && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
        return true;
    }

    return false;
}

/**
 * Get the page title
 *
 * @return string
 */
function aqualuxe_get_page_title() {
    $title = '';

    if (is_home()) {
        $title = get_theme_mod('blog_title', esc_html__('Blog', 'aqualuxe'));
    } elseif (is_archive()) {
        $title = get_the_archive_title();
    } elseif (is_search()) {
        /* translators: %s: search query */
        $title = sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), get_search_query());
    } elseif (is_404()) {
        $title = esc_html__('Page Not Found', 'aqualuxe');
    } elseif (is_page()) {
        $title = get_the_title();
    } elseif (is_single()) {
        $title = get_the_title();
    }

    return apply_filters('aqualuxe_page_title', $title);
}

/**
 * Get the page subtitle
 *
 * @return string
 */
function aqualuxe_get_page_subtitle() {
    $subtitle = '';

    if (is_archive()) {
        $subtitle = get_the_archive_description();
    } elseif (is_search()) {
        /* translators: %d: number of results */
        $subtitle = sprintf(
            esc_html(_n('%d result found', '%d results found', (int) $GLOBALS['wp_query']->found_posts, 'aqualuxe')),
            (int) $GLOBALS['wp_query']->found_posts
        );
    }

    return apply_filters('aqualuxe_page_subtitle', $subtitle);
}

/**
 * Get the post meta
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_get_post_meta($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $meta = '';

    // Author
    $meta .= sprintf(
        '<span class="post-author">%s <a href="%s">%s</a></span>',
        esc_html__('By', 'aqualuxe'),
        esc_url(get_author_posts_url(get_the_author_meta('ID'))),
        esc_html(get_the_author())
    );

    // Date
    $meta .= sprintf(
        '<span class="post-date">%s <time datetime="%s">%s</time></span>',
        esc_html__('on', 'aqualuxe'),
        esc_attr(get_the_date('c')),
        esc_html(get_the_date())
    );

    // Categories
    $categories = get_the_category();
    if ($categories) {
        $meta .= '<span class="post-categories">' . esc_html__('in', 'aqualuxe');
        $i = 0;
        foreach ($categories as $category) {
            if ($i > 0) {
                $meta .= ', ';
            }
            $meta .= sprintf(
                '<a href="%s">%s</a>',
                esc_url(get_category_link($category->term_id)),
                esc_html($category->name)
            );
            $i++;
        }
        $meta .= '</span>';
    }

    return apply_filters('aqualuxe_post_meta', $meta, $post_id);
}

/**
 * Get the post footer
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_get_post_footer($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $footer = '';

    // Tags
    $tags = get_the_tags();
    if ($tags) {
        $footer .= '<div class="post-tags">';
        $footer .= '<span class="post-tags-title">' . esc_html__('Tags:', 'aqualuxe') . '</span>';
        $i = 0;
        foreach ($tags as $tag) {
            if ($i > 0) {
                $footer .= ', ';
            }
            $footer .= sprintf(
                '<a href="%s">%s</a>',
                esc_url(get_tag_link($tag->term_id)),
                esc_html($tag->name)
            );
            $i++;
        }
        $footer .= '</div>';
    }

    // Comments
    if (comments_open() || get_comments_number()) {
        $footer .= '<div class="post-comments">';
        $footer .= sprintf(
            '<a href="%s">%s</a>',
            esc_url(get_comments_link()),
            esc_html__('Leave a comment', 'aqualuxe')
        );
        $footer .= '</div>';
    }

    return apply_filters('aqualuxe_post_footer', $footer, $post_id);
}

/**
 * Get the post thumbnail
 *
 * @param int    $post_id Post ID
 * @param string $size    Thumbnail size
 * @return string
 */
function aqualuxe_get_post_thumbnail($post_id = null, $size = 'post-thumbnail') {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    if (!has_post_thumbnail($post_id)) {
        return '';
    }

    $thumbnail = '';

    $thumbnail .= '<div class="post-thumbnail">';
    $thumbnail .= sprintf(
        '<a href="%s">%s</a>',
        esc_url(get_permalink()),
        get_the_post_thumbnail($post_id, $size)
    );
    $thumbnail .= '</div>';

    return apply_filters('aqualuxe_post_thumbnail', $thumbnail, $post_id, $size);
}

/**
 * Get the post navigation
 *
 * @return string
 */
function aqualuxe_get_post_navigation() {
    $navigation = '';

    $prev_post = get_previous_post();
    $next_post = get_next_post();

    if ($prev_post || $next_post) {
        $navigation .= '<nav class="post-navigation">';
        $navigation .= '<div class="post-navigation-links">';

        if ($prev_post) {
            $navigation .= sprintf(
                '<div class="post-navigation-prev">%s <a href="%s">%s</a></div>',
                esc_html__('Previous:', 'aqualuxe'),
                esc_url(get_permalink($prev_post)),
                esc_html(get_the_title($prev_post))
            );
        }

        if ($next_post) {
            $navigation .= sprintf(
                '<div class="post-navigation-next">%s <a href="%s">%s</a></div>',
                esc_html__('Next:', 'aqualuxe'),
                esc_url(get_permalink($next_post)),
                esc_html(get_the_title($next_post))
            );
        }

        $navigation .= '</div>';
        $navigation .= '</nav>';
    }

    return apply_filters('aqualuxe_post_navigation', $navigation);
}

/**
 * Get the posts pagination
 *
 * @return string
 */
function aqualuxe_get_posts_pagination() {
    $pagination = '';

    $pagination .= '<div class="posts-pagination">';
    $pagination .= paginate_links([
        'prev_text' => esc_html__('Previous', 'aqualuxe'),
        'next_text' => esc_html__('Next', 'aqualuxe'),
        'mid_size' => 2,
    ]);
    $pagination .= '</div>';

    return apply_filters('aqualuxe_posts_pagination', $pagination);
}

/**
 * Get the breadcrumbs
 *
 * @return string
 */
function aqualuxe_get_breadcrumbs() {
    $breadcrumbs = '';

    if (function_exists('yoast_breadcrumb')) {
        $breadcrumbs = yoast_breadcrumb('<div class="breadcrumbs">', '</div>', false);
    } elseif (function_exists('rank_math_the_breadcrumbs')) {
        $breadcrumbs = rank_math_get_breadcrumbs();
    } else {
        $breadcrumbs = aqualuxe_default_breadcrumbs();
    }

    return apply_filters('aqualuxe_breadcrumbs', $breadcrumbs);
}

/**
 * Get the default breadcrumbs
 *
 * @return string
 */
function aqualuxe_default_breadcrumbs() {
    $breadcrumbs = '';

    $breadcrumbs .= '<div class="breadcrumbs">';
    $breadcrumbs .= '<span class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'aqualuxe') . '</a></span>';

    if (is_home()) {
        $breadcrumbs .= '<span class="breadcrumb-separator">/</span>';
        $breadcrumbs .= '<span class="breadcrumb-item">' . esc_html__('Blog', 'aqualuxe') . '</span>';
    } elseif (is_category()) {
        $breadcrumbs .= '<span class="breadcrumb-separator">/</span>';
        $breadcrumbs .= '<span class="breadcrumb-item">' . single_cat_title('', false) . '</span>';
    } elseif (is_tag()) {
        $breadcrumbs .= '<span class="breadcrumb-separator">/</span>';
        $breadcrumbs .= '<span class="breadcrumb-item">' . single_tag_title('', false) . '</span>';
    } elseif (is_author()) {
        $breadcrumbs .= '<span class="breadcrumb-separator">/</span>';
        $breadcrumbs .= '<span class="breadcrumb-item">' . get_the_author() . '</span>';
    } elseif (is_year()) {
        $breadcrumbs .= '<span class="breadcrumb-separator">/</span>';
        $breadcrumbs .= '<span class="breadcrumb-item">' . get_the_date('Y') . '</span>';
    } elseif (is_month()) {
        $breadcrumbs .= '<span class="breadcrumb-separator">/</span>';
        $breadcrumbs .= '<span class="breadcrumb-item">' . get_the_date('F Y') . '</span>';
    } elseif (is_day()) {
        $breadcrumbs .= '<span class="breadcrumb-separator">/</span>';
        $breadcrumbs .= '<span class="breadcrumb-item">' . get_the_date('F j, Y') . '</span>';
    } elseif (is_post_type_archive()) {
        $breadcrumbs .= '<span class="breadcrumb-separator">/</span>';
        $breadcrumbs .= '<span class="breadcrumb-item">' . post_type_archive_title('', false) . '</span>';
    } elseif (is_tax()) {
        $breadcrumbs .= '<span class="breadcrumb-separator">/</span>';
        $breadcrumbs .= '<span class="breadcrumb-item">' . single_term_title('', false) . '</span>';
    } elseif (is_search()) {
        $breadcrumbs .= '<span class="breadcrumb-separator">/</span>';
        $breadcrumbs .= '<span class="breadcrumb-item">' . esc_html__('Search Results', 'aqualuxe') . '</span>';
    } elseif (is_404()) {
        $breadcrumbs .= '<span class="breadcrumb-separator">/</span>';
        $breadcrumbs .= '<span class="breadcrumb-item">' . esc_html__('Page Not Found', 'aqualuxe') . '</span>';
    } elseif (is_page()) {
        $breadcrumbs .= '<span class="breadcrumb-separator">/</span>';
        $breadcrumbs .= '<span class="breadcrumb-item">' . get_the_title() . '</span>';
    } elseif (is_single()) {
        $post_type = get_post_type();

        if ('post' === $post_type) {
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                $breadcrumbs .= '<span class="breadcrumb-separator">/</span>';
                $breadcrumbs .= '<span class="breadcrumb-item"><a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a></span>';
            }
        } elseif ('product' === $post_type && function_exists('wc_get_product_category_list')) {
            $product_id = get_the_ID();
            $categories = wc_get_product_category_list($product_id);
            if ($categories) {
                $breadcrumbs .= '<span class="breadcrumb-separator">/</span>';
                $breadcrumbs .= '<span class="breadcrumb-item">' . $categories . '</span>';
            }
        }

        $breadcrumbs .= '<span class="breadcrumb-separator">/</span>';
        $breadcrumbs .= '<span class="breadcrumb-item">' . get_the_title() . '</span>';
    }

    $breadcrumbs .= '</div>';

    return $breadcrumbs;
}

/**
 * Get the social links
 *
 * @return string
 */
function aqualuxe_get_social_links() {
    $social_links = '';

    $social_links .= '<div class="social-links">';
    $social_links .= '<ul class="social-links-list">';

    // Facebook
    $facebook_url = get_theme_mod('facebook_url', '');
    if ($facebook_url) {
        $social_links .= sprintf(
            '<li class="social-links-item"><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></li>',
            esc_url($facebook_url),
            esc_html__('Facebook', 'aqualuxe')
        );
    }

    // Twitter
    $twitter_url = get_theme_mod('twitter_url', '');
    if ($twitter_url) {
        $social_links .= sprintf(
            '<li class="social-links-item"><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></li>',
            esc_url($twitter_url),
            esc_html__('Twitter', 'aqualuxe')
        );
    }

    // Instagram
    $instagram_url = get_theme_mod('instagram_url', '');
    if ($instagram_url) {
        $social_links .= sprintf(
            '<li class="social-links-item"><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></li>',
            esc_url($instagram_url),
            esc_html__('Instagram', 'aqualuxe')
        );
    }

    // LinkedIn
    $linkedin_url = get_theme_mod('linkedin_url', '');
    if ($linkedin_url) {
        $social_links .= sprintf(
            '<li class="social-links-item"><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></li>',
            esc_url($linkedin_url),
            esc_html__('LinkedIn', 'aqualuxe')
        );
    }

    // YouTube
    $youtube_url = get_theme_mod('youtube_url', '');
    if ($youtube_url) {
        $social_links .= sprintf(
            '<li class="social-links-item"><a href="%s" target="_blank" rel="noopener noreferrer">%s</a></li>',
            esc_url($youtube_url),
            esc_html__('YouTube', 'aqualuxe')
        );
    }

    $social_links .= '</ul>';
    $social_links .= '</div>';

    return apply_filters('aqualuxe_social_links', $social_links);
}

/**
 * Get the footer text
 *
 * @return string
 */
function aqualuxe_get_footer_text() {
    $footer_text = get_theme_mod('footer_text', sprintf(
        /* translators: %s: Current year and site name */
        esc_html__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'),
        date('Y')
    ));

    return apply_filters('aqualuxe_footer_text', $footer_text);
}

/**
 * Get the footer columns
 *
 * @return int
 */
function aqualuxe_get_footer_columns() {
    $columns = get_theme_mod('footer_columns', 4);

    return apply_filters('aqualuxe_footer_columns', $columns);
}

/**
 * Get the footer column class
 *
 * @return string
 */
function aqualuxe_get_footer_column_class() {
    $columns = aqualuxe_get_footer_columns();
    $class = '';

    switch ($columns) {
        case 1:
            $class = 'w-full';
            break;
        case 2:
            $class = 'w-full md:w-1/2';
            break;
        case 3:
            $class = 'w-full md:w-1/3';
            break;
        case 4:
            $class = 'w-full md:w-1/2 lg:w-1/4';
            break;
        default:
            $class = 'w-full md:w-1/2 lg:w-1/4';
            break;
    }

    return apply_filters('aqualuxe_footer_column_class', $class);
}

/**
 * Get the header layout
 *
 * @return string
 */
function aqualuxe_get_header_layout() {
    $layout = get_theme_mod('header_layout', 'default');

    return apply_filters('aqualuxe_header_layout', $layout);
}

/**
 * Check if the header is sticky
 *
 * @return bool
 */
function aqualuxe_is_sticky_header() {
    $sticky = get_theme_mod('sticky_header', true);

    return apply_filters('aqualuxe_is_sticky_header', $sticky);
}

/**
 * Get the blog layout
 *
 * @return string
 */
function aqualuxe_get_blog_layout() {
    $layout = get_theme_mod('blog_layout', 'grid');

    return apply_filters('aqualuxe_blog_layout', $layout);
}

/**
 * Get the shop columns
 *
 * @return int
 */
function aqualuxe_get_shop_columns() {
    $columns = get_theme_mod('shop_columns', 4);

    return apply_filters('aqualuxe_shop_columns', $columns);
}

/**
 * Get the products per page
 *
 * @return int
 */
function aqualuxe_get_products_per_page() {
    $per_page = get_theme_mod('products_per_page', 12);

    return apply_filters('aqualuxe_products_per_page', $per_page);
}

/**
 * Check if related products should be displayed
 *
 * @return bool
 */
function aqualuxe_show_related_products() {
    $show = get_theme_mod('related_products', true);

    return apply_filters('aqualuxe_show_related_products', $show);
}

/**
 * Get the container width
 *
 * @return int
 */
function aqualuxe_get_container_width() {
    $width = get_theme_mod('container_width', 1280);

    return apply_filters('aqualuxe_container_width', $width);
}

/**
 * Get the primary color
 *
 * @return string
 */
function aqualuxe_get_primary_color() {
    $color = get_theme_mod('primary_color', '#0ea5e9');

    return apply_filters('aqualuxe_primary_color', $color);
}

/**
 * Get the secondary color
 *
 * @return string
 */
function aqualuxe_get_secondary_color() {
    $color = get_theme_mod('secondary_color', '#1e293b');

    return apply_filters('aqualuxe_secondary_color', $color);
}

/**
 * Get the accent color
 *
 * @return string
 */
function aqualuxe_get_accent_color() {
    $color = get_theme_mod('accent_color', '#f59e0b');

    return apply_filters('aqualuxe_accent_color', $color);
}

/**
 * Get the body font
 *
 * @return string
 */
function aqualuxe_get_body_font() {
    $font = get_theme_mod('body_font', 'Inter');

    return apply_filters('aqualuxe_body_font', $font);
}

/**
 * Get the heading font
 *
 * @return string
 */
function aqualuxe_get_heading_font() {
    $font = get_theme_mod('heading_font', 'Playfair Display');

    return apply_filters('aqualuxe_heading_font', $font);
}