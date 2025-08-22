<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function aqualuxe_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if (get_the_time('U') !== get_the_modified_time('U')) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
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

    $byline = sprintf(
        /* translators: %s: post author. */
        esc_html_x('by %s', 'post author', 'aqualuxe'),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the categories, tags and comments.
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
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function aqualuxe_post_thumbnail() {
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
        return;
    }

    if (is_singular()) :
        ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail('aqualuxe-featured', array('class' => 'rounded-lg shadow-lg')); ?>
        </div><!-- .post-thumbnail -->
    <?php else : ?>
        <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php
            the_post_thumbnail(
                'aqualuxe-thumbnail',
                array(
                    'alt' => the_title_attribute(
                        array(
                            'echo' => false,
                        )
                    ),
                    'class' => 'rounded-lg shadow transition-transform duration-300 hover:scale-105',
                )
            );
            ?>
        </a>
        <?php
    endif; // End is_singular().
}

/**
 * Prints HTML with the comment count for the current post.
 */
function aqualuxe_comment_count() {
    if (!post_password_required() && (comments_open() || get_comments_number())) {
        echo '<span class="comments-link">';
        /* translators: %s: Name of current post. Only visible to screen readers. */
        comments_popup_link(
            sprintf(
                __('Leave a comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe'),
                get_the_title()
            )
        );
        echo '</span>';
    }
}

/**
 * Displays the navigation to next/previous post, when applicable.
 */
function aqualuxe_post_navigation() {
    the_post_navigation(
        array(
            'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
        )
    );
}

/**
 * Displays the navigation to next/previous set of posts, when applicable.
 */
function aqualuxe_posts_navigation() {
    the_posts_pagination(
        array(
            'mid_size' => 2,
            'prev_text' => sprintf(
                '%s <span class="nav-prev-text">%s</span>',
                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                __('Newer posts', 'aqualuxe')
            ),
            'next_text' => sprintf(
                '<span class="nav-next-text">%s</span> %s',
                __('Older posts', 'aqualuxe'),
                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
            ),
        )
    );
}

/**
 * Displays SVG icons in social links menu.
 *
 * @param string   $item_output The menu item's starting HTML output.
 * @param WP_Post  $item        Menu item data object.
 * @param int      $depth       Depth of the menu. Used for padding.
 * @param stdClass $args        An object of wp_nav_menu() arguments.
 * @return string The menu item output with social icon.
 */
function aqualuxe_nav_menu_social_icons($item_output, $item, $depth, $args) {
    // Change SVG icon inside social links menu if there is supported URL.
    if ('social' === $args->theme_location) {
        $svg = aqualuxe_get_social_icon_svg(esc_url($item->url), 24);
        if (!empty($svg)) {
            $item_output = str_replace($args->link_before, $svg, $item_output);
        }
    }

    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'aqualuxe_nav_menu_social_icons', 10, 4);

/**
 * Returns an SVG icon for a given social link URL.
 *
 * @param string $url   Social link URL.
 * @param int    $size  Icon size in pixels.
 * @return string SVG icon HTML.
 */
function aqualuxe_get_social_icon_svg($url, $size = 24) {
    $social_icons = array(
        'facebook.com'    => array(
            'name' => 'Facebook',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
        ),
        'twitter.com'     => array(
            'name' => 'Twitter',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>',
        ),
        'instagram.com'   => array(
            'name' => 'Instagram',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
        ),
        'linkedin.com'    => array(
            'name' => 'LinkedIn',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-linkedin"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
        ),
        'youtube.com'     => array(
            'name' => 'YouTube',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-youtube"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>',
        ),
        'pinterest.com'   => array(
            'name' => 'Pinterest',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pinterest"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path></svg>',
        ),
    );

    // Return empty string if URL is empty.
    if ('' === $url) {
        return '';
    }

    // Return empty string if URL doesn't match any of the social networks.
    $domain = parse_url($url, PHP_URL_HOST);
    if (!$domain) {
        return '';
    }

    // Remove 'www.' from the beginning of the domain.
    $domain = preg_replace('/^www\./', '', $domain);

    // Check if the domain matches any of the social networks.
    foreach ($social_icons as $social_domain => $social_icon) {
        if (strpos($domain, $social_domain) !== false) {
            return sprintf(
                '<span class="social-icon social-icon-%s">%s</span>',
                esc_attr($social_icon['name']),
                $social_icon['icon']
            );
        }
    }

    // Return empty string if no match is found.
    return '';
}

/**
 * Displays the site logo, either text or image.
 *
 * @param array $args Arguments for displaying the site logo either as an image or text.
 */
function aqualuxe_site_logo($args = array()) {
    $logo = get_custom_logo();
    $site_title = get_bloginfo('name');
    $site_description = get_bloginfo('description', 'display');
    $classes = array('site-logo');

    $defaults = array(
        'logo'             => '%1$s<span class="screen-reader-text">%2$s</span>',
        'logo_class'       => 'site-logo',
        'title'            => '<a href="%1$s" class="site-title">%2$s</a>',
        'title_class'      => 'site-title',
        'home_wrap'        => '<h1 class="%1$s">%2$s</h1>',
        'single_wrap'      => '<div class="%1$s">%2$s</div>',
        'condition'        => (is_front_page() || is_home()) && !is_page(),
    );

    $args = wp_parse_args($args, $defaults);

    /**
     * Filters the arguments for the site logo.
     *
     * @param array $args     Parsed arguments.
     * @param array $defaults Default arguments.
     */
    $args = apply_filters('aqualuxe_site_logo_args', $args, $defaults);

    if (has_custom_logo()) {
        $classes[] = 'has-logo';
        $logo = sprintf(
            $args['logo'],
            $logo,
            esc_html($site_title)
        );
    } else {
        $classes[] = 'no-logo';
        $logo = sprintf(
            $args['title'],
            esc_url(home_url('/')),
            esc_html($site_title)
        );
    }

    $classes = array_map('sanitize_html_class', $classes);
    $class_attr = implode(' ', $classes);

    $html = sprintf(
        $args['condition'] ? $args['home_wrap'] : $args['single_wrap'],
        $class_attr,
        $logo
    );

    /**
     * Filters the HTML for the site logo.
     *
     * @param string $html    The HTML for the site logo.
     * @param array  $args    Parsed arguments.
     * @param array  $classes Array of class names for the site logo container.
     */
    echo apply_filters('aqualuxe_site_logo_html', $html, $args, $classes);
}

/**
 * Displays the site description.
 */
function aqualuxe_site_description() {
    $site_description = get_bloginfo('description', 'display');

    if ($site_description) {
        echo '<p class="site-description">' . $site_description . '</p>';
    }
}

/**
 * Displays the mobile menu toggle button.
 */
function aqualuxe_mobile_menu_toggle() {
    ?>
    <button id="mobile-menu-toggle" class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false">
        <span class="toggle-bar"></span>
        <span class="toggle-bar"></span>
        <span class="toggle-bar"></span>
        <span class="screen-reader-text"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>
    </button>
    <?php
}

/**
 * Displays the search toggle button.
 */
function aqualuxe_search_toggle() {
    ?>
    <button id="search-toggle" class="search-toggle" aria-expanded="false" aria-controls="search-container">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
        <span class="screen-reader-text"><?php esc_html_e('Search', 'aqualuxe'); ?></span>
    </button>
    <?php
}

/**
 * Displays the search form.
 */
function aqualuxe_search_form() {
    ?>
    <div id="search-container" class="search-container" aria-hidden="true">
        <div class="search-inner">
            <?php get_search_form(); ?>
        </div>
    </div>
    <?php
}

/**
 * Displays the dark mode toggle button.
 */
function aqualuxe_dark_mode_toggle() {
    // Only display if dark mode module is active
    $active_modules = get_option('aqualuxe_active_modules', array());
    if (!isset($active_modules['dark-mode']) || !$active_modules['dark-mode']) {
        return;
    }
    ?>
    <button id="dark-mode-toggle" class="dark-mode-toggle" aria-pressed="false">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
        <span class="screen-reader-text"><?php esc_html_e('Toggle Dark Mode', 'aqualuxe'); ?></span>
    </button>
    <?php
}

/**
 * Displays the breadcrumbs.
 */
function aqualuxe_breadcrumbs() {
    // Skip on the front page
    if (is_front_page()) {
        return;
    }

    $breadcrumbs = array();
    $breadcrumbs[] = array(
        'text' => __('Home', 'aqualuxe'),
        'url' => home_url('/'),
    );

    if (is_category() || is_single()) {
        if (is_single()) {
            // Get post categories
            $categories = get_the_category();
            if (!empty($categories)) {
                $category = $categories[0];
                $breadcrumbs[] = array(
                    'text' => $category->name,
                    'url' => get_category_link($category->term_id),
                );
            }
            // Add post title
            $breadcrumbs[] = array(
                'text' => get_the_title(),
                'url' => '',
            );
        } else {
            // Category archive
            $breadcrumbs[] = array(
                'text' => single_cat_title('', false),
                'url' => '',
            );
        }
    } elseif (is_page()) {
        // Check if page has parent
        if ($post->post_parent) {
            $ancestors = get_post_ancestors($post->ID);
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor) {
                $breadcrumbs[] = array(
                    'text' => get_the_title($ancestor),
                    'url' => get_permalink($ancestor),
                );
            }
        }
        // Add current page
        $breadcrumbs[] = array(
            'text' => get_the_title(),
            'url' => '',
        );
    } elseif (is_tag()) {
        $breadcrumbs[] = array(
            'text' => single_tag_title('', false),
            'url' => '',
        );
    } elseif (is_author()) {
        $breadcrumbs[] = array(
            'text' => get_the_author(),
            'url' => '',
        );
    } elseif (is_year()) {
        $breadcrumbs[] = array(
            'text' => get_the_date('Y'),
            'url' => '',
        );
    } elseif (is_month()) {
        $breadcrumbs[] = array(
            'text' => get_the_date('F Y'),
            'url' => '',
        );
    } elseif (is_day()) {
        $breadcrumbs[] = array(
            'text' => get_the_date(),
            'url' => '',
        );
    } elseif (is_search()) {
        $breadcrumbs[] = array(
            'text' => sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query()),
            'url' => '',
        );
    } elseif (is_404()) {
        $breadcrumbs[] = array(
            'text' => __('Page Not Found', 'aqualuxe'),
            'url' => '',
        );
    }

    // WooCommerce support
    if (class_exists('WooCommerce')) {
        if (is_shop()) {
            $breadcrumbs[] = array(
                'text' => __('Shop', 'aqualuxe'),
                'url' => '',
            );
        } elseif (is_product_category()) {
            $breadcrumbs[] = array(
                'text' => __('Shop', 'aqualuxe'),
                'url' => get_permalink(wc_get_page_id('shop')),
            );
            $breadcrumbs[] = array(
                'text' => single_term_title('', false),
                'url' => '',
            );
        } elseif (is_product()) {
            $breadcrumbs[] = array(
                'text' => __('Shop', 'aqualuxe'),
                'url' => get_permalink(wc_get_page_id('shop')),
            );
            $terms = get_the_terms(get_the_ID(), 'product_cat');
            if ($terms && !is_wp_error($terms)) {
                $breadcrumbs[] = array(
                    'text' => $terms[0]->name,
                    'url' => get_term_link($terms[0]),
                );
            }
            $breadcrumbs[] = array(
                'text' => get_the_title(),
                'url' => '',
            );
        }
    }

    // Output breadcrumbs
    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">';
    echo '<ol class="breadcrumbs-list">';
    
    $count = count($breadcrumbs);
    foreach ($breadcrumbs as $index => $breadcrumb) {
        echo '<li class="breadcrumbs-item">';
        if (!empty($breadcrumb['url']) && $index < $count - 1) {
            echo '<a href="' . esc_url($breadcrumb['url']) . '">' . esc_html($breadcrumb['text']) . '</a>';
        } else {
            echo '<span aria-current="page">' . esc_html($breadcrumb['text']) . '</span>';
        }
        if ($index < $count - 1) {
            echo '<span class="breadcrumbs-separator">/</span>';
        }
        echo '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * Displays the featured image with proper markup.
 */
function aqualuxe_featured_image() {
    if (has_post_thumbnail()) {
        ?>
        <figure class="featured-image">
            <?php the_post_thumbnail('aqualuxe-featured', array('class' => 'rounded-lg shadow-lg')); ?>
            <?php if (get_the_post_thumbnail_caption()) : ?>
                <figcaption class="featured-image-caption"><?php the_post_thumbnail_caption(); ?></figcaption>
            <?php endif; ?>
        </figure>
        <?php
    }
}

/**
 * Displays the post author bio.
 */
function aqualuxe_author_bio() {
    if (!is_single()) {
        return;
    }

    $author_id = get_the_author_meta('ID');
    $author_name = get_the_author_meta('display_name');
    $author_description = get_the_author_meta('description');
    $author_url = get_author_posts_url($author_id);
    $author_avatar = get_avatar($author_id, 96, '', $author_name, array('class' => 'rounded-full'));

    if (empty($author_description)) {
        return;
    }
    ?>
    <div class="author-bio">
        <div class="author-bio-header">
            <div class="author-avatar">
                <?php echo $author_avatar; ?>
            </div>
            <div class="author-info">
                <h2 class="author-title">
                    <a href="<?php echo esc_url($author_url); ?>" class="author-link">
                        <?php echo esc_html($author_name); ?>
                    </a>
                </h2>
            </div>
        </div>
        <div class="author-description">
            <?php echo wpautop($author_description); ?>
            <a href="<?php echo esc_url($author_url); ?>" class="author-link">
                <?php
                printf(
                    /* translators: %s: Author name. */
                    __('View all posts by %s', 'aqualuxe'),
                    esc_html($author_name)
                );
                ?>
            </a>
        </div>
    </div>
    <?php
}

/**
 * Displays related posts.
 */
function aqualuxe_related_posts() {
    if (!is_single()) {
        return;
    }

    $post_id = get_the_ID();
    $categories = get_the_category($post_id);
    
    if (empty($categories)) {
        return;
    }

    $category_ids = array();
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'post__not_in' => array($post_id),
        'category__in' => $category_ids,
        'orderby' => 'rand',
    );

    $related_posts = new WP_Query($args);

    if ($related_posts->have_posts()) {
        ?>
        <div class="related-posts">
            <h2 class="related-posts-title"><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h2>
            <div class="related-posts-grid">
                <?php
                while ($related_posts->have_posts()) {
                    $related_posts->the_post();
                    ?>
                    <article class="related-post">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="related-post-thumbnail">
                                <?php the_post_thumbnail('aqualuxe-thumbnail'); ?>
                            </a>
                        <?php endif; ?>
                        <div class="related-post-content">
                            <h3 class="related-post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <div class="related-post-meta">
                                <?php echo get_the_date(); ?>
                            </div>
                        </div>
                    </article>
                    <?php
                }
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Displays social sharing buttons.
 */
function aqualuxe_social_sharing() {
    if (!is_singular()) {
        return;
    }

    $post_url = urlencode(get_permalink());
    $post_title = urlencode(get_the_title());
    $post_thumbnail = urlencode(get_the_post_thumbnail_url(get_the_ID(), 'full'));

    $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url;
    $twitter_url = 'https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title;
    $linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title;
    $pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&media=' . $post_thumbnail . '&description=' . $post_title;
    $email_url = 'mailto:?subject=' . $post_title . '&body=' . $post_url;
    ?>
    <div class="social-sharing">
        <h3 class="social-sharing-title"><?php esc_html_e('Share This', 'aqualuxe'); ?></h3>
        <ul class="social-sharing-list">
            <li class="social-sharing-item">
                <a href="<?php echo esc_url($facebook_url); ?>" target="_blank" rel="noopener noreferrer" class="social-sharing-link facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                    <span class="screen-reader-text"><?php esc_html_e('Share on Facebook', 'aqualuxe'); ?></span>
                </a>
            </li>
            <li class="social-sharing-item">
                <a href="<?php echo esc_url($twitter_url); ?>" target="_blank" rel="noopener noreferrer" class="social-sharing-link twitter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                    <span class="screen-reader-text"><?php esc_html_e('Share on Twitter', 'aqualuxe'); ?></span>
                </a>
            </li>
            <li class="social-sharing-item">
                <a href="<?php echo esc_url($linkedin_url); ?>" target="_blank" rel="noopener noreferrer" class="social-sharing-link linkedin">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-linkedin"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                    <span class="screen-reader-text"><?php esc_html_e('Share on LinkedIn', 'aqualuxe'); ?></span>
                </a>
            </li>
            <li class="social-sharing-item">
                <a href="<?php echo esc_url($pinterest_url); ?>" target="_blank" rel="noopener noreferrer" class="social-sharing-link pinterest">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pinterest"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path></svg>
                    <span class="screen-reader-text"><?php esc_html_e('Share on Pinterest', 'aqualuxe'); ?></span>
                </a>
            </li>
            <li class="social-sharing-item">
                <a href="<?php echo esc_url($email_url); ?>" class="social-sharing-link email">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <span class="screen-reader-text"><?php esc_html_e('Share via Email', 'aqualuxe'); ?></span>
                </a>
            </li>
        </ul>
    </div>
    <?php
}

/**
 * Displays a call to action section.
 *
 * @param array $args Arguments for the call to action section.
 */
function aqualuxe_call_to_action($args = array()) {
    $defaults = array(
        'title' => __('Ready to Get Started?', 'aqualuxe'),
        'description' => __('Contact us today to learn more about our services and how we can help you.', 'aqualuxe'),
        'button_text' => __('Contact Us', 'aqualuxe'),
        'button_url' => site_url('/contact/'),
        'background_color' => 'primary',
    );

    $args = wp_parse_args($args, $defaults);
    
    $background_class = 'bg-' . $args['background_color'];
    ?>
    <section class="call-to-action <?php echo esc_attr($background_class); ?>">
        <div class="container">
            <div class="call-to-action-content">
                <h2 class="call-to-action-title"><?php echo esc_html($args['title']); ?></h2>
                <p class="call-to-action-description"><?php echo esc_html($args['description']); ?></p>
                <a href="<?php echo esc_url($args['button_url']); ?>" class="button button-large"><?php echo esc_html($args['button_text']); ?></a>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Displays a newsletter signup form.
 */
function aqualuxe_newsletter_signup() {
    ?>
    <div class="newsletter-signup">
        <h3 class="newsletter-title"><?php esc_html_e('Subscribe to Our Newsletter', 'aqualuxe'); ?></h3>
        <p class="newsletter-description"><?php esc_html_e('Stay updated with our latest products, services, and aquatic care tips.', 'aqualuxe'); ?></p>
        <form class="newsletter-form" action="#" method="post">
            <div class="newsletter-form-fields">
                <input type="email" name="email" placeholder="<?php esc_attr_e('Your Email Address', 'aqualuxe'); ?>" required>
                <button type="submit" class="button"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
            </div>
            <div class="newsletter-privacy">
                <label>
                    <input type="checkbox" name="privacy" required>
                    <?php
                    printf(
                        /* translators: %s: Privacy policy URL. */
                        __('I agree to the <a href="%s">privacy policy</a>', 'aqualuxe'),
                        esc_url(get_privacy_policy_url())
                    );
                    ?>
                </label>
            </div>
        </form>
    </div>
    <?php
}

/**
 * Displays a testimonial.
 *
 * @param int|WP_Post $post Post ID or post object.
 */
function aqualuxe_testimonial($post) {
    $post = get_post($post);
    if (!$post) {
        return;
    }

    $author = get_post_meta($post->ID, '_aqualuxe_testimonial_author', true);
    $position = get_post_meta($post->ID, '_aqualuxe_testimonial_position', true);
    $company = get_post_meta($post->ID, '_aqualuxe_testimonial_company', true);
    $rating = get_post_meta($post->ID, '_aqualuxe_testimonial_rating', true);
    ?>
    <div class="testimonial">
        <div class="testimonial-content">
            <?php if ($rating) : ?>
                <div class="testimonial-rating">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $rating) {
                            echo '<span class="star star-filled">★</span>';
                        } else {
                            echo '<span class="star">☆</span>';
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>
            <blockquote class="testimonial-text">
                <?php echo wpautop(get_the_content(null, false, $post)); ?>
            </blockquote>
        </div>
        <div class="testimonial-meta">
            <?php if (has_post_thumbnail($post)) : ?>
                <div class="testimonial-image">
                    <?php echo get_the_post_thumbnail($post, 'thumbnail', array('class' => 'rounded-full')); ?>
                </div>
            <?php endif; ?>
            <div class="testimonial-info">
                <?php if ($author) : ?>
                    <cite class="testimonial-author"><?php echo esc_html($author); ?></cite>
                <?php endif; ?>
                <?php if ($position || $company) : ?>
                    <p class="testimonial-position">
                        <?php
                        if ($position && $company) {
                            echo esc_html($position) . ', ' . esc_html($company);
                        } elseif ($position) {
                            echo esc_html($position);
                        } elseif ($company) {
                            echo esc_html($company);
                        }
                        ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Displays a service.
 *
 * @param int|WP_Post $post Post ID or post object.
 */
function aqualuxe_service($post) {
    $post = get_post($post);
    if (!$post) {
        return;
    }

    $price = get_post_meta($post->ID, '_aqualuxe_service_price', true);
    $duration = get_post_meta($post->ID, '_aqualuxe_service_duration', true);
    $icon = get_post_meta($post->ID, '_aqualuxe_service_icon', true);
    $featured = get_post_meta($post->ID, '_aqualuxe_service_featured', true);
    
    $classes = array('service');
    if ($featured === 'yes') {
        $classes[] = 'service-featured';
    }
    ?>
    <div class="<?php echo esc_attr(implode(' ', $classes)); ?>">
        <?php if ($icon) : ?>
            <div class="service-icon">
                <i class="<?php echo esc_attr($icon); ?>"></i>
            </div>
        <?php endif; ?>
        <div class="service-content">
            <h3 class="service-title">
                <a href="<?php echo esc_url(get_permalink($post)); ?>"><?php echo esc_html(get_the_title($post)); ?></a>
            </h3>
            <div class="service-excerpt">
                <?php echo wpautop(get_the_excerpt($post)); ?>
            </div>
            <?php if ($price || $duration) : ?>
                <div class="service-meta">
                    <?php if ($price) : ?>
                        <span class="service-price"><?php echo esc_html($price); ?></span>
                    <?php endif; ?>
                    <?php if ($duration) : ?>
                        <span class="service-duration"><?php echo esc_html($duration); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <a href="<?php echo esc_url(get_permalink($post)); ?>" class="service-link"><?php esc_html_e('Learn More', 'aqualuxe'); ?></a>
        </div>
    </div>
    <?php
}

/**
 * Displays a team member.
 *
 * @param int|WP_Post $post Post ID or post object.
 */
function aqualuxe_team_member($post) {
    $post = get_post($post);
    if (!$post) {
        return;
    }

    $position = get_post_meta($post->ID, '_aqualuxe_team_position', true);
    $email = get_post_meta($post->ID, '_aqualuxe_team_email', true);
    $phone = get_post_meta($post->ID, '_aqualuxe_team_phone', true);
    $social = get_post_meta($post->ID, '_aqualuxe_team_social', true);
    
    if ($social) {
        $social = json_decode($social, true);
    }
    ?>
    <div class="team-member">
        <?php if (has_post_thumbnail($post)) : ?>
            <div class="team-member-image">
                <?php echo get_the_post_thumbnail($post, 'medium', array('class' => 'rounded-lg')); ?>
            </div>
        <?php endif; ?>
        <div class="team-member-content">
            <h3 class="team-member-name"><?php echo esc_html(get_the_title($post)); ?></h3>
            <?php if ($position) : ?>
                <p class="team-member-position"><?php echo esc_html($position); ?></p>
            <?php endif; ?>
            <div class="team-member-bio">
                <?php echo wpautop(get_the_excerpt($post)); ?>
            </div>
            <div class="team-member-contact">
                <?php if ($email) : ?>
                    <a href="mailto:<?php echo esc_attr($email); ?>" class="team-member-email">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        <span class="screen-reader-text"><?php esc_html_e('Email', 'aqualuxe'); ?></span>
                    </a>
                <?php endif; ?>
                <?php if ($phone) : ?>
                    <a href="tel:<?php echo esc_attr($phone); ?>" class="team-member-phone">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        <span class="screen-reader-text"><?php esc_html_e('Phone', 'aqualuxe'); ?></span>
                    </a>
                <?php endif; ?>
            </div>
            <?php if ($social && is_array($social)) : ?>
                <div class="team-member-social">
                    <?php foreach ($social as $platform => $url) : ?>
                        <a href="<?php echo esc_url($url); ?>" class="team-member-social-link <?php echo esc_attr($platform); ?>" target="_blank" rel="noopener noreferrer">
                            <?php echo aqualuxe_get_social_icon_svg($url); ?>
                            <span class="screen-reader-text"><?php echo esc_html(ucfirst($platform)); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Displays a FAQ item.
 *
 * @param int|WP_Post $post Post ID or post object.
 */
function aqualuxe_faq_item($post) {
    $post = get_post($post);
    if (!$post) {
        return;
    }
    
    $id = 'faq-' . $post->ID;
    ?>
    <div class="faq-item">
        <h3 class="faq-question">
            <button class="faq-question-button" aria-expanded="false" aria-controls="<?php echo esc_attr($id); ?>">
                <?php echo esc_html(get_the_title($post)); ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </button>
        </h3>
        <div id="<?php echo esc_attr($id); ?>" class="faq-answer" hidden>
            <?php echo wpautop(get_the_content(null, false, $post)); ?>
        </div>
    </div>
    <?php
}

/**
 * Displays a product category grid.
 *
 * @param array $args Arguments for the product category grid.
 */
function aqualuxe_product_category_grid($args = array()) {
    // Only proceed if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    $defaults = array(
        'number' => 4,
        'columns' => 4,
        'hide_empty' => true,
        'parent' => 0,
        'orderby' => 'name',
        'order' => 'ASC',
    );

    $args = wp_parse_args($args, $defaults);
    
    $categories = get_terms('product_cat', $args);
    
    if (empty($categories) || is_wp_error($categories)) {
        return;
    }
    
    $column_class = 'columns-' . $args['columns'];
    ?>
    <div class="product-categories <?php echo esc_attr($column_class); ?>">
        <?php foreach ($categories as $category) : ?>
            <?php
            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
            $image = $thumbnail_id ? wp_get_attachment_image_src($thumbnail_id, 'medium') : '';
            $image_url = $image ? $image[0] : wc_placeholder_img_src();
            ?>
            <div class="product-category">
                <a href="<?php echo esc_url(get_term_link($category)); ?>" class="product-category-link">
                    <div class="product-category-image">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>" class="rounded-lg">
                    </div>
                    <div class="product-category-content">
                        <h3 class="product-category-title"><?php echo esc_html($category->name); ?></h3>
                        <?php if ($category->count > 0) : ?>
                            <span class="product-category-count">
                                <?php
                                printf(
                                    /* translators: %d: Product count. */
                                    _n('%d product', '%d products', $category->count, 'aqualuxe'),
                                    $category->count
                                );
                                ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Displays featured products.
 *
 * @param array $args Arguments for the featured products.
 */
function aqualuxe_featured_products($args = array()) {
    // Only proceed if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    $defaults = array(
        'limit' => 4,
        'columns' => 4,
        'orderby' => 'date',
        'order' => 'DESC',
        'visibility' => 'featured',
    );

    $args = wp_parse_args($args, $defaults);
    
    $shortcode_args = array(
        'limit' => $args['limit'],
        'columns' => $args['columns'],
        'orderby' => $args['orderby'],
        'order' => $args['order'],
        'visibility' => $args['visibility'],
    );
    
    $shortcode_atts = '';
    foreach ($shortcode_args as $key => $value) {
        $shortcode_atts .= ' ' . $key . '="' . esc_attr($value) . '"';
    }
    
    echo do_shortcode('[products' . $shortcode_atts . ']');
}

/**
 * Displays a contact form.
 */
function aqualuxe_contact_form() {
    // Check if Contact Form 7 is active
    if (function_exists('wpcf7_contact_form_tag_func')) {
        echo do_shortcode('[contact-form-7 id="' . aqualuxe_get_contact_form_id() . '" title="Contact Form"]');
    } else {
        // Fallback contact form
        ?>
        <form class="contact-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
            <div class="form-row">
                <div class="form-group">
                    <label for="name"><?php esc_html_e('Name', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email"><?php esc_html_e('Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>
            <div class="form-group">
                <label for="subject"><?php esc_html_e('Subject', 'aqualuxe'); ?> <span class="required">*</span></label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="message"><?php esc_html_e('Message', 'aqualuxe'); ?> <span class="required">*</span></label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <input type="checkbox" id="privacy" name="privacy" required>
                <label for="privacy">
                    <?php
                    printf(
                        /* translators: %s: Privacy policy URL. */
                        __('I have read and agree to the <a href="%s">privacy policy</a>', 'aqualuxe'),
                        esc_url(get_privacy_policy_url())
                    );
                    ?>
                </label>
            </div>
            <div class="form-actions">
                <?php wp_nonce_field('aqualuxe_contact_form', 'aqualuxe_contact_nonce'); ?>
                <input type="hidden" name="action" value="aqualuxe_contact_form">
                <button type="submit" class="button"><?php esc_html_e('Send Message', 'aqualuxe'); ?></button>
            </div>
        </form>
        <?php
    }
}

/**
 * Get the contact form ID.
 *
 * @return int Contact form ID.
 */
function aqualuxe_get_contact_form_id() {
    // Get the contact form ID from theme options
    $contact_form_id = get_theme_mod('aqualuxe_contact_form_id', 0);
    
    // If no ID is set, try to find the first contact form
    if (!$contact_form_id && function_exists('wpcf7_get_contact_forms')) {
        $forms = wpcf7_get_contact_forms();
        if (!empty($forms)) {
            $first_form = reset($forms);
            $contact_form_id = $first_form->id();
        }
    }
    
    return $contact_form_id;
}

/**
 * Displays a Google Map.
 *
 * @param array $args Arguments for the Google Map.
 */
function aqualuxe_google_map($args = array()) {
    $defaults = array(
        'address' => '',
        'lat' => '',
        'lng' => '',
        'zoom' => 15,
        'height' => '400px',
        'api_key' => '',
    );

    $args = wp_parse_args($args, $defaults);
    
    // Get API key from theme options if not provided
    if (empty($args['api_key'])) {
        $args['api_key'] = get_theme_mod('aqualuxe_google_maps_api_key', '');
    }
    
    // If no API key, display a message
    if (empty($args['api_key'])) {
        echo '<div class="google-map-placeholder" style="height: ' . esc_attr($args['height']) . '; background-color: #f5f5f5; display: flex; align-items: center; justify-content: center; text-align: center; padding: 20px;">';
        echo '<p>' . esc_html__('Please set a Google Maps API key in the theme options to display the map.', 'aqualuxe') . '</p>';
        echo '</div>';
        return;
    }
    
    // If no coordinates but address is provided, use the address
    if ((empty($args['lat']) || empty($args['lng'])) && !empty($args['address'])) {
        $map_url = 'https://www.google.com/maps/embed/v1/place?key=' . urlencode($args['api_key']) . '&q=' . urlencode($args['address']) . '&zoom=' . intval($args['zoom']);
    } else {
        // Use coordinates
        $map_url = 'https://www.google.com/maps/embed/v1/view?key=' . urlencode($args['api_key']) . '&center=' . floatval($args['lat']) . ',' . floatval($args['lng']) . '&zoom=' . intval($args['zoom']);
    }
    ?>
    <div class="google-map">
        <iframe
            width="100%"
            height="<?php echo esc_attr($args['height']); ?>"
            style="border:0"
            loading="lazy"
            allowfullscreen
            src="<?php echo esc_url($map_url); ?>">
        </iframe>
    </div>
    <?php
}

/**
 * Displays a language switcher.
 */
function aqualuxe_language_switcher() {
    // Only display if multilingual module is active
    $active_modules = get_option('aqualuxe_active_modules', array());
    if (!isset($active_modules['multilingual']) || !$active_modules['multilingual']) {
        return;
    }
    
    // Check if WPML is active
    if (function_exists('icl_get_languages')) {
        $languages = icl_get_languages('skip_missing=0&orderby=code');
        if (!empty($languages)) {
            echo '<div class="language-switcher">';
            echo '<ul class="language-switcher-list">';
            foreach ($languages as $language) {
                $class = $language['active'] ? 'language-switcher-item active' : 'language-switcher-item';
                echo '<li class="' . esc_attr($class) . '">';
                echo '<a href="' . esc_url($language['url']) . '">';
                if ($language['country_flag_url']) {
                    echo '<img src="' . esc_url($language['country_flag_url']) . '" alt="' . esc_attr($language['language_code']) . '" width="18" height="12">';
                }
                echo '<span>' . esc_html($language['native_name']) . '</span>';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    } else {
        // Fallback language switcher
        echo '<div class="language-switcher">';
        echo '<button class="language-switcher-toggle">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>';
        echo '<span>' . esc_html__('EN', 'aqualuxe') . '</span>';
        echo '</button>';
        echo '<ul class="language-switcher-dropdown">';
        echo '<li><a href="#" class="active">English</a></li>';
        echo '<li><a href="#">Français</a></li>';
        echo '<li><a href="#">Español</a></li>';
        echo '<li><a href="#">Deutsch</a></li>';
        echo '</ul>';
        echo '</div>';
    }
}

/**
 * Displays a currency switcher.
 */
function aqualuxe_currency_switcher() {
    // Only proceed if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    // Check if WooCommerce Multilingual is active
    if (function_exists('wcml_get_woocommerce_currencies_options')) {
        $currencies = wcml_get_woocommerce_currencies_options();
        $current_currency = wcml_get_client_currency();
        
        if (!empty($currencies)) {
            echo '<div class="currency-switcher">';
            echo '<ul class="currency-switcher-list">';
            foreach ($currencies as $code => $currency) {
                $class = ($code === $current_currency) ? 'currency-switcher-item active' : 'currency-switcher-item';
                echo '<li class="' . esc_attr($class) . '">';
                echo '<a href="' . esc_url(add_query_arg('currency', $code)) . '">';
                echo '<span>' . esc_html($code) . '</span>';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    } else {
        // Fallback currency switcher
        echo '<div class="currency-switcher">';
        echo '<button class="currency-switcher-toggle">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>';
        echo '<span>' . esc_html__('USD', 'aqualuxe') . '</span>';
        echo '</button>';
        echo '<ul class="currency-switcher-dropdown">';
        echo '<li><a href="#" class="active">USD</a></li>';
        echo '<li><a href="#">EUR</a></li>';
        echo '<li><a href="#">GBP</a></li>';
        echo '<li><a href="#">JPY</a></li>';
        echo '</ul>';
        echo '</div>';
    }
}

/**
 * Displays a mini cart.
 */
function aqualuxe_mini_cart() {
    // Only proceed if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    $cart_url = wc_get_cart_url();
    ?>
    <div class="mini-cart">
        <a href="<?php echo esc_url($cart_url); ?>" class="mini-cart-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            <span class="mini-cart-count"><?php echo esc_html($cart_count); ?></span>
        </a>
        <div class="mini-cart-dropdown">
            <div class="widget_shopping_cart_content">
                <?php woocommerce_mini_cart(); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Displays a wishlist link.
 */
function aqualuxe_wishlist_link() {
    // Only proceed if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    // Check if YITH WooCommerce Wishlist is active
    if (function_exists('YITH_WCWL')) {
        $wishlist_url = YITH_WCWL()->get_wishlist_url();
        $wishlist_count = YITH_WCWL()->count_products();
    } else {
        // Fallback wishlist URL
        $wishlist_url = '#';
        $wishlist_count = 0;
    }
    ?>
    <div class="wishlist">
        <a href="<?php echo esc_url($wishlist_url); ?>" class="wishlist-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
            <span class="wishlist-count"><?php echo esc_html($wishlist_count); ?></span>
        </a>
    </div>
    <?php
}

/**
 * Displays a user account link.
 */
function aqualuxe_account_link() {
    // Only proceed if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    $account_url = wc_get_account_endpoint_url('dashboard');
    $login_url = wc_get_account_endpoint_url('dashboard');
    $logout_url = wp_logout_url(home_url());
    ?>
    <div class="account">
        <a href="<?php echo esc_url($account_url); ?>" class="account-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span class="screen-reader-text"><?php esc_html_e('My Account', 'aqualuxe'); ?></span>
        </a>
        <?php if (is_user_logged_in()) : ?>
            <div class="account-dropdown">
                <ul class="account-dropdown-list">
                    <li><a href="<?php echo esc_url($account_url); ?>"><?php esc_html_e('Dashboard', 'aqualuxe'); ?></a></li>
                    <li><a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>"><?php esc_html_e('Orders', 'aqualuxe'); ?></a></li>
                    <li><a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-address')); ?>"><?php esc_html_e('Addresses', 'aqualuxe'); ?></a></li>
                    <li><a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>"><?php esc_html_e('Account details', 'aqualuxe'); ?></a></li>
                    <li><a href="<?php echo esc_url($logout_url); ?>"><?php esc_html_e('Logout', 'aqualuxe'); ?></a></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    <?php
}