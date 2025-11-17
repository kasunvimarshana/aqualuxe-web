<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Prints HTML with meta information for the current post-date/time.
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

    echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the current author.
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
            <?php the_post_thumbnail('aqualuxe-featured', array('class' => 'rounded-xl')); ?>
        </div><!-- .post-thumbnail -->
    <?php else : ?>
        <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php
            the_post_thumbnail(
                'aqualuxe-blog',
                array(
                    'alt' => the_title_attribute(
                        array(
                            'echo' => false,
                        )
                    ),
                    'class' => 'rounded-xl',
                )
            );
            ?>
        </a>
        <?php
    endif; // End is_singular().
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function aqualuxe_posted_on_by() {
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
        '<a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a>'
    );

    echo '<div class="post-meta text-sm text-gray-600 dark:text-gray-400 mb-4">';
    echo '<span class="posted-on">' . $posted_on . '</span> ';
    echo '<span class="byline">' . $byline . '</span>';
    echo '</div>';
}

/**
 * Prints HTML with meta information for the categories.
 */
function aqualuxe_post_categories() {
    /* translators: used between list items, there is a space after the comma */
    $categories_list = get_the_category_list(esc_html__(', ', 'aqualuxe'));
    if ($categories_list) {
        echo '<div class="post-categories mb-4">';
        echo '<span class="sr-only">' . esc_html__('Categories:', 'aqualuxe') . '</span>';
        echo '<span class="inline-flex flex-wrap gap-2">' . $categories_list . '</span>';
        echo '</div>';
    }
}

/**
 * Prints HTML with meta information for the tags.
 */
function aqualuxe_post_tags() {
    /* translators: used between list items, there is a space after the comma */
    $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'aqualuxe'));
    if ($tags_list) {
        echo '<div class="post-tags mt-6">';
        echo '<span class="font-medium mr-2">' . esc_html__('Tags:', 'aqualuxe') . '</span>';
        echo '<span class="inline-flex flex-wrap gap-2">' . $tags_list . '</span>';
        echo '</div>';
    }
}

/**
 * Prints comments link.
 */
function aqualuxe_comments_link() {
    if (!post_password_required() && (comments_open() || get_comments_number())) {
        echo '<div class="comments-link mt-4">';
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
        echo '</div>';
    }
}

/**
 * Prints edit post link.
 */
function aqualuxe_edit_post_link() {
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
        '<div class="edit-link mt-4">',
        '</div>'
    );
}

/**
 * Prints post navigation.
 */
function aqualuxe_post_navigation() {
    the_post_navigation(
        array(
            'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
            'class' => 'post-navigation flex flex-col md:flex-row justify-between mt-8 py-6 border-t border-b border-gray-200 dark:border-gray-700',
        )
    );
}

/**
 * Prints posts pagination.
 */
function aqualuxe_posts_pagination() {
    the_posts_pagination(
        array(
            'mid_size' => 2,
            'prev_text' => '<span class="nav-prev-text">' . esc_html__('Previous', 'aqualuxe') . '</span>',
            'next_text' => '<span class="nav-next-text">' . esc_html__('Next', 'aqualuxe') . '</span>',
            'class' => 'pagination flex justify-center mt-8',
        )
    );
}

/**
 * Prints the site logo.
 */
function aqualuxe_site_logo() {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');

    if (has_custom_logo()) {
        echo '<a href="' . esc_url(home_url('/')) . '" class="site-logo" rel="home">';
        echo '<img src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '" class="h-10 w-auto">';
        echo '</a>';
    } else {
        echo '<a href="' . esc_url(home_url('/')) . '" class="site-title text-2xl font-bold" rel="home">' . get_bloginfo('name') . '</a>';
        
        $description = get_bloginfo('description', 'display');
        if ($description || is_customize_preview()) {
            echo '<p class="site-description text-sm">' . $description . '</p>';
        }
    }
}

/**
 * Prints the breadcrumbs.
 */
function aqualuxe_breadcrumbs() {
    // Check if breadcrumbs are enabled
    if (!aqualuxe_get_option('aqualuxe_enable_breadcrumbs', true)) {
        return;
    }

    // Check if we're on a WooCommerce page and WooCommerce is active
    if (function_exists('is_woocommerce') && (is_woocommerce() || is_cart() || is_checkout())) {
        if (function_exists('woocommerce_breadcrumb')) {
            woocommerce_breadcrumb(
                array(
                    'wrap_before' => '<nav class="woocommerce-breadcrumb breadcrumbs py-3 text-sm" itemprop="breadcrumb">',
                    'wrap_after' => '</nav>',
                    'before' => '<span class="breadcrumb-item">',
                    'after' => '</span>',
                    'delimiter' => '<span class="breadcrumb-separator mx-2">/</span>',
                )
            );
        }
        return;
    }

    // Regular breadcrumbs for non-WooCommerce pages
    echo '<nav class="breadcrumbs py-3 text-sm" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
    echo '<ol class="flex flex-wrap items-center">';

    // Home
    echo '<li class="breadcrumb-item">';
    echo '<a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'aqualuxe') . '</a>';
    echo '</li>';
    echo '<li class="breadcrumb-separator mx-2">/</li>';

    // Category, archive, single post, page
    if (is_category() || is_archive() || is_single() || is_page()) {
        if (is_category()) {
            $category = get_category(get_query_var('cat'));
            echo '<li class="breadcrumb-item">' . esc_html($category->name) . '</li>';
        } elseif (is_archive()) {
            echo '<li class="breadcrumb-item">' . get_the_archive_title() . '</li>';
        } elseif (is_single()) {
            $categories = get_the_category();
            if (!empty($categories)) {
                echo '<li class="breadcrumb-item">';
                echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
                echo '</li>';
                echo '<li class="breadcrumb-separator mx-2">/</li>';
            }
            echo '<li class="breadcrumb-item">' . get_the_title() . '</li>';
        } elseif (is_page()) {
            // Check if the page has a parent
            if ($post->post_parent) {
                $ancestors = get_post_ancestors($post->ID);
                $ancestors = array_reverse($ancestors);

                foreach ($ancestors as $ancestor) {
                    echo '<li class="breadcrumb-item">';
                    echo '<a href="' . esc_url(get_permalink($ancestor)) . '">' . get_the_title($ancestor) . '</a>';
                    echo '</li>';
                    echo '<li class="breadcrumb-separator mx-2">/</li>';
                }
            }
            echo '<li class="breadcrumb-item">' . get_the_title() . '</li>';
        }
    } elseif (is_search()) {
        echo '<li class="breadcrumb-item">' . esc_html__('Search Results', 'aqualuxe') . '</li>';
    } elseif (is_404()) {
        echo '<li class="breadcrumb-item">' . esc_html__('404 Not Found', 'aqualuxe') . '</li>';
    }

    echo '</ol>';
    echo '</nav>';
}

/**
 * Prints the social icons.
 */
function aqualuxe_social_icons() {
    $social_links = array(
        'facebook' => aqualuxe_get_option('aqualuxe_facebook_url', ''),
        'twitter' => aqualuxe_get_option('aqualuxe_twitter_url', ''),
        'instagram' => aqualuxe_get_option('aqualuxe_instagram_url', ''),
        'youtube' => aqualuxe_get_option('aqualuxe_youtube_url', ''),
        'linkedin' => aqualuxe_get_option('aqualuxe_linkedin_url', ''),
        'pinterest' => aqualuxe_get_option('aqualuxe_pinterest_url', ''),
    );

    // Check if any social links are set
    $has_links = false;
    foreach ($social_links as $link) {
        if (!empty($link)) {
            $has_links = true;
            break;
        }
    }

    if (!$has_links) {
        return;
    }

    echo '<div class="social-icons flex space-x-4">';

    foreach ($social_links as $network => $url) {
        if (!empty($url)) {
            echo '<a href="' . esc_url($url) . '" class="social-icon ' . esc_attr($network) . '" target="_blank" rel="noopener noreferrer">';
            echo '<span class="sr-only">' . esc_html(ucfirst($network)) . '</span>';
            
            // SVG icons for each network
            switch ($network) {
                case 'facebook':
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>';
                    break;
                case 'twitter':
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/></svg>';
                    break;
                case 'instagram':
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/></svg>';
                    break;
                case 'youtube':
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>';
                    break;
                case 'linkedin':
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>';
                    break;
                case 'pinterest':
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>';
                    break;
            }
            
            echo '</a>';
        }
    }

    echo '</div>';
}

/**
 * Prints the language switcher.
 */
function aqualuxe_language_switcher() {
    // Check if multilingual support is enabled
    if (!aqualuxe_is_multilingual_enabled()) {
        return;
    }

    // Get available languages
    $languages = aqualuxe_get_languages();

    // Get current language
    $current_lang = 'en'; // Default to English

    // If WPML is active, get current language
    if (defined('ICL_LANGUAGE_CODE')) {
        $current_lang = ICL_LANGUAGE_CODE;
    }

    // If Polylang is active, get current language
    if (function_exists('pll_current_language')) {
        $current_lang = pll_current_language();
    }

    echo '<div class="language-switcher relative">';
    echo '<button type="button" class="lang-dropdown-button flex items-center" id="language-menu-button" aria-expanded="false" aria-haspopup="true">';
    
    // Display current language flag
    if (isset($languages[$current_lang])) {
        echo '<img src="' . esc_url(AQUALUXE_URI . '/assets/images/flags/' . $languages[$current_lang]['flag']) . '" alt="' . esc_attr($languages[$current_lang]['name']) . '" class="w-5 h-5 rounded-full mr-2">';
        echo '<span>' . esc_html($languages[$current_lang]['name']) . '</span>';
    } else {
        echo '<span>' . esc_html__('Language', 'aqualuxe') . '</span>';
    }
    
    echo '<svg class="ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>';
    echo '</button>';

    echo '<div class="lang-dropdown-items hidden absolute z-10 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-dark-700 ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="language-menu-button" tabindex="-1">';
    
    foreach ($languages as $code => $lang) {
        $active_class = ($code === $current_lang) ? ' bg-gray-100 dark:bg-dark-600' : '';
        
        echo '<a href="#" class="lang-dropdown-item' . esc_attr($active_class) . '" role="menuitem" tabindex="-1" data-lang="' . esc_attr($code) . '">';
        echo '<div class="flex items-center">';
        echo '<img src="' . esc_url(AQUALUXE_URI . '/assets/images/flags/' . $lang['flag']) . '" alt="' . esc_attr($lang['name']) . '" class="w-5 h-5 rounded-full mr-2">';
        echo '<span>' . esc_html($lang['name']) . '</span>';
        echo '</div>';
        echo '</a>';
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Prints the dark mode toggle.
 */
function aqualuxe_dark_mode_toggle() {
    // Check if dark mode is enabled
    if (!aqualuxe_is_dark_mode_enabled()) {
        return;
    }

    echo '<div class="dark-mode-toggle-wrapper ml-4">';
    echo '<button type="button" id="dark-mode-toggle" class="dark-mode-toggle" role="switch" aria-checked="false">';
    echo '<span class="sr-only">' . esc_html__('Toggle dark mode', 'aqualuxe') . '</span>';
    echo '<span class="dark-mode-toggle-knob"></span>';
    echo '</button>';
    echo '</div>';
}

/**
 * Prints the mobile menu toggle.
 */
function aqualuxe_mobile_menu_toggle() {
    echo '<button type="button" id="mobile-menu-button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-dark-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500" aria-expanded="false">';
    echo '<span class="sr-only">' . esc_html__('Open main menu', 'aqualuxe') . '</span>';
    
    // Icon when menu is closed
    echo '<svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
    echo '</svg>';
    
    // Icon when menu is open
    echo '<svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
    echo '</svg>';
    
    echo '</button>';
}

/**
 * Prints the search toggle.
 */
function aqualuxe_search_toggle() {
    echo '<button type="button" id="search-toggle" class="search-toggle inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-dark-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500">';
    echo '<span class="sr-only">' . esc_html__('Search', 'aqualuxe') . '</span>';
    echo '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />';
    echo '</svg>';
    echo '</button>';
}

/**
 * Prints the search form.
 */
function aqualuxe_search_form() {
    echo '<div id="search-form" class="search-form hidden absolute top-full right-0 mt-2 w-72 p-4 bg-white dark:bg-dark-700 rounded-lg shadow-lg z-10">';
    get_search_form();
    echo '</div>';
}

/**
 * Prints the cart icon.
 */
function aqualuxe_cart_icon() {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="cart-icon inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-dark-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 relative">';
    echo '<span class="sr-only">' . esc_html__('View cart', 'aqualuxe') . '</span>';
    echo '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />';
    echo '</svg>';
    
    $cart_count = WC()->cart->get_cart_contents_count();
    if ($cart_count > 0) {
        echo '<span class="cart-count absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-primary-600 rounded-full">' . esc_html($cart_count) . '</span>';
    }
    
    echo '</a>';
}

/**
 * Prints the wishlist icon.
 */
function aqualuxe_wishlist_icon() {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    // Check if wishlist is enabled
    if (!aqualuxe_get_option('aqualuxe_enable_wishlist', true)) {
        return;
    }

    $wishlist_url = aqualuxe_get_option('aqualuxe_wishlist_page', '#');
    if (is_numeric($wishlist_url)) {
        $wishlist_url = get_permalink($wishlist_url);
    }

    echo '<a href="' . esc_url($wishlist_url) . '" class="wishlist-icon inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-dark-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500 relative">';
    echo '<span class="sr-only">' . esc_html__('View wishlist', 'aqualuxe') . '</span>';
    echo '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />';
    echo '</svg>';
    
    $wishlist_count = 0;
    if (function_exists('aqualuxe_get_wishlist_count')) {
        $wishlist_count = aqualuxe_get_wishlist_count();
    }
    
    if ($wishlist_count > 0) {
        echo '<span class="wishlist-count absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-accent-600 rounded-full">' . esc_html($wishlist_count) . '</span>';
    }
    
    echo '</a>';
}

/**
 * Prints the account icon.
 */
function aqualuxe_account_icon() {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    $account_url = get_permalink(get_option('woocommerce_myaccount_page_id'));

    echo '<a href="' . esc_url($account_url) . '" class="account-icon inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-dark-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500">';
    echo '<span class="sr-only">' . esc_html__('My account', 'aqualuxe') . '</span>';
    echo '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />';
    echo '</svg>';
    echo '</a>';
}

/**
 * Prints the page header.
 */
function aqualuxe_page_header() {
    if (is_front_page()) {
        return;
    }

    echo '<header class="page-header py-8 md:py-12 bg-gray-50 dark:bg-dark-800">';
    echo '<div class="container mx-auto px-4">';
    
    if (is_singular()) {
        echo '<h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-bold mb-4">' . get_the_title() . '</h1>';
    } elseif (is_archive()) {
        echo '<h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-bold mb-4">' . get_the_archive_title() . '</h1>';
        
        if (get_the_archive_description()) {
            echo '<div class="archive-description text-lg text-gray-600 dark:text-gray-400">' . get_the_archive_description() . '</div>';
        }
    } elseif (is_search()) {
        echo '<h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-bold mb-4">';
        /* translators: %s: search query. */
        printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
        echo '</h1>';
    } elseif (is_404()) {
        echo '<h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-bold mb-4">' . esc_html__('Oops! That page can&rsquo;t be found.', 'aqualuxe') . '</h1>';
    }
    
    // Breadcrumbs
    aqualuxe_breadcrumbs();
    
    echo '</div>';
    echo '</header>';
}

/**
 * Prints the hero section.
 */
function aqualuxe_hero_section() {
    // Check if hero section is enabled
    if (!aqualuxe_get_option('aqualuxe_enable_hero', true)) {
        return;
    }

    // Check if we're on the front page
    if (!is_front_page()) {
        return;
    }

    // Get hero settings
    $hero_title = aqualuxe_get_option('aqualuxe_hero_title', esc_html__('Welcome to AquaLuxe', 'aqualuxe'));
    $hero_subtitle = aqualuxe_get_option('aqualuxe_hero_subtitle', esc_html__('Premium Ornamental Fish for Collectors and Enthusiasts', 'aqualuxe'));
    $hero_text = aqualuxe_get_option('aqualuxe_hero_text', esc_html__('Discover our exclusive collection of rare and exotic ornamental fish, sourced from sustainable farms around the world.', 'aqualuxe'));
    $hero_button_text = aqualuxe_get_option('aqualuxe_hero_button_text', esc_html__('Shop Now', 'aqualuxe'));
    $hero_button_url = aqualuxe_get_option('aqualuxe_hero_button_url', '#');
    $hero_button_2_text = aqualuxe_get_option('aqualuxe_hero_button_2_text', esc_html__('Learn More', 'aqualuxe'));
    $hero_button_2_url = aqualuxe_get_option('aqualuxe_hero_button_2_url', '#');
    $hero_image = aqualuxe_get_option('aqualuxe_hero_image', '');
    $hero_height = aqualuxe_get_option('aqualuxe_hero_height', 600);
    $hero_overlay_opacity = aqualuxe_get_option('aqualuxe_hero_overlay_opacity', 0.5);

    // Get hero image URL
    $hero_image_url = '';
    if ($hero_image) {
        $hero_image_url = wp_get_attachment_image_url($hero_image, 'full');
    }

    echo '<section class="hero-section relative flex items-center" style="height: ' . esc_attr($hero_height) . 'px;">';
    
    // Hero background
    if ($hero_image_url) {
        echo '<div class="hero-background absolute inset-0 bg-cover bg-center" style="background-image: url(' . esc_url($hero_image_url) . ');"></div>';
        echo '<div class="hero-overlay absolute inset-0 bg-black" style="opacity: ' . esc_attr($hero_overlay_opacity) . ';"></div>';
    } else {
        echo '<div class="hero-background absolute inset-0 bg-gradient-primary"></div>';
    }
    
    // Hero content
    echo '<div class="container mx-auto px-4 relative z-10">';
    echo '<div class="max-w-3xl text-white">';
    
    if ($hero_title) {
        echo '<h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 text-shadow-lg">' . esc_html($hero_title) . '</h1>';
    }
    
    if ($hero_subtitle) {
        echo '<h2 class="text-xl md:text-2xl lg:text-3xl font-medium mb-6 text-shadow">' . esc_html($hero_subtitle) . '</h2>';
    }
    
    if ($hero_text) {
        echo '<p class="text-lg mb-8 text-shadow max-w-2xl">' . esc_html($hero_text) . '</p>';
    }
    
    echo '<div class="flex flex-wrap gap-4">';
    
    if ($hero_button_text && $hero_button_url) {
        echo '<a href="' . esc_url($hero_button_url) . '" class="btn-primary">' . esc_html($hero_button_text) . '</a>';
    }
    
    if ($hero_button_2_text && $hero_button_2_url) {
        echo '<a href="' . esc_url($hero_button_2_url) . '" class="btn-outline border-white text-white hover:bg-white hover:text-primary-600">' . esc_html($hero_button_2_text) . '</a>';
    }
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</section>';
}

/**
 * Prints the featured products section.
 */
function aqualuxe_featured_products() {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    // Check if featured products section is enabled
    if (!aqualuxe_get_option('aqualuxe_enable_featured_products', true)) {
        return;
    }

    // Check if we're on the front page
    if (!is_front_page()) {
        return;
    }

    // Get featured products settings
    $title = aqualuxe_get_option('aqualuxe_featured_products_title', esc_html__('Featured Products', 'aqualuxe'));
    $subtitle = aqualuxe_get_option('aqualuxe_featured_products_subtitle', esc_html__('Our most exclusive and sought-after specimens', 'aqualuxe'));
    $count = aqualuxe_get_option('aqualuxe_featured_products_count', 4);

    echo '<section class="featured-products py-16 bg-white dark:bg-dark-800">';
    echo '<div class="container mx-auto px-4">';
    
    // Section header
    echo '<div class="section-header text-center mb-12">';
    
    if ($title) {
        echo '<h2 class="text-3xl md:text-4xl font-bold mb-4">' . esc_html($title) . '</h2>';
    }
    
    if ($subtitle) {
        echo '<p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">' . esc_html($subtitle) . '</p>';
    }
    
    echo '</div>';
    
    // Featured products
    echo '<div class="featured-products-grid">';
    
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $count,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => 'featured',
                'operator' => 'IN',
            ),
        ),
    );
    
    $products = new WP_Query($args);
    
    if ($products->have_posts()) {
        woocommerce_product_loop_start();
        
        while ($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
        
        woocommerce_product_loop_end();
        wp_reset_postdata();
    } else {
        echo '<p class="text-center">' . esc_html__('No featured products found.', 'aqualuxe') . '</p>';
    }
    
    echo '</div>';
    
    // View all button
    echo '<div class="text-center mt-12">';
    echo '<a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '" class="btn-primary">' . esc_html__('View All Products', 'aqualuxe') . '</a>';
    echo '</div>';
    
    echo '</div>';
    echo '</section>';
}

/**
 * Prints the testimonials section.
 */
function aqualuxe_testimonials() {
    // Check if testimonials section is enabled
    if (!aqualuxe_get_option('aqualuxe_enable_testimonials', true)) {
        return;
    }

    // Check if we're on the front page
    if (!is_front_page()) {
        return;
    }

    // Get testimonials settings
    $title = aqualuxe_get_option('aqualuxe_testimonials_title', esc_html__('What Our Customers Say', 'aqualuxe'));
    $subtitle = aqualuxe_get_option('aqualuxe_testimonials_subtitle', esc_html__('Hear from our satisfied collectors and enthusiasts', 'aqualuxe'));
    
    // Get testimonials
    $testimonials = array();
    
    for ($i = 1; $i <= 3; $i++) {
        $name = aqualuxe_get_option('aqualuxe_testimonial_' . $i . '_name', '');
        $role = aqualuxe_get_option('aqualuxe_testimonial_' . $i . '_role', '');
        $content = aqualuxe_get_option('aqualuxe_testimonial_' . $i . '_content', '');
        $image = aqualuxe_get_option('aqualuxe_testimonial_' . $i . '_image', '');
        
        if ($name && $content) {
            $testimonials[] = array(
                'name' => $name,
                'role' => $role,
                'content' => $content,
                'image' => $image,
            );
        }
    }
    
    if (empty($testimonials)) {
        // Add default testimonials
        $testimonials = array(
            array(
                'name' => 'John Smith',
                'role' => 'Aquarium Enthusiast',
                'content' => 'The rare Asian Arowana I purchased from AquaLuxe is the centerpiece of my collection. The fish arrived in perfect health, and the customer service was exceptional.',
                'image' => '',
            ),
            array(
                'name' => 'Emily Johnson',
                'role' => 'Professional Breeder',
                'content' => 'As a professional breeder, I demand the highest quality specimens. AquaLuxe consistently delivers healthy, genetically superior fish that thrive in my breeding program.',
                'image' => '',
            ),
            array(
                'name' => 'Michael Chen',
                'role' => 'Collector',
                'content' => 'I\'ve been collecting exotic fish for over 20 years, and AquaLuxe offers the most impressive selection I\'ve seen. Their rare Japanese Koi varieties are simply stunning.',
                'image' => '',
            ),
        );
    }

    echo '<section class="testimonials py-16 bg-gray-50 dark:bg-dark-900">';
    echo '<div class="container mx-auto px-4">';
    
    // Section header
    echo '<div class="section-header text-center mb-12">';
    
    if ($title) {
        echo '<h2 class="text-3xl md:text-4xl font-bold mb-4">' . esc_html($title) . '</h2>';
    }
    
    if ($subtitle) {
        echo '<p class="text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">' . esc_html($subtitle) . '</p>';
    }
    
    echo '</div>';
    
    // Testimonials grid
    echo '<div class="testimonials-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">';
    
    foreach ($testimonials as $testimonial) {
        echo '<div class="testimonial card p-6">';
        
        // Testimonial content
        echo '<div class="testimonial-content mb-6">';
        echo '<svg class="h-8 w-8 text-primary-500 mb-4" fill="currentColor" viewBox="0 0 32 32" aria-hidden="true">';
        echo '<path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z" />';
        echo '</svg>';
        echo '<p class="text-gray-600 dark:text-gray-400">' . esc_html($testimonial['content']) . '</p>';
        echo '</div>';
        
        // Testimonial author
        echo '<div class="testimonial-author flex items-center">';
        
        if (!empty($testimonial['image'])) {
            $image_url = wp_get_attachment_image_url($testimonial['image'], 'thumbnail');
            if ($image_url) {
                echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($testimonial['name']) . '" class="h-12 w-12 rounded-full mr-4">';
            } else {
                echo '<div class="h-12 w-12 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center mr-4">';
                echo '<span class="text-primary-600 dark:text-primary-400 font-bold text-xl">' . esc_html(substr($testimonial['name'], 0, 1)) . '</span>';
                echo '</div>';
            }
        } else {
            echo '<div class="h-12 w-12 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center mr-4">';
            echo '<span class="text-primary-600 dark:text-primary-400 font-bold text-xl">' . esc_html(substr($testimonial['name'], 0, 1)) . '</span>';
            echo '</div>';
        }
        
        echo '<div>';
        echo '<h4 class="font-medium">' . esc_html($testimonial['name']) . '</h4>';
        
        if (!empty($testimonial['role'])) {
            echo '<p class="text-sm text-gray-500 dark:text-gray-400">' . esc_html($testimonial['role']) . '</p>';
        }
        
        echo '</div>';
        echo '</div>';
        
        echo '</div>';
    }
    
    echo '</div>';
    
    echo '</div>';
    echo '</section>';
}

/**
 * Prints the newsletter section.
 */
function aqualuxe_newsletter() {
    // Check if newsletter section is enabled
    if (!aqualuxe_get_option('aqualuxe_enable_newsletter', true)) {
        return;
    }

    // Check if we're on the front page
    if (!is_front_page()) {
        return;
    }

    // Get newsletter settings
    $title = aqualuxe_get_option('aqualuxe_newsletter_title', esc_html__('Subscribe to Our Newsletter', 'aqualuxe'));
    $subtitle = aqualuxe_get_option('aqualuxe_newsletter_subtitle', esc_html__('Stay updated with our latest arrivals, breeding success stories, and exclusive offers', 'aqualuxe'));
    $background = aqualuxe_get_option('aqualuxe_newsletter_background', '');
    
    // Get background image URL
    $background_url = '';
    if ($background) {
        $background_url = wp_get_attachment_image_url($background, 'full');
    }

    echo '<section class="newsletter py-16 relative bg-primary-900 text-white overflow-hidden">';
    
    // Background image
    if ($background_url) {
        echo '<div class="absolute inset-0 bg-cover bg-center opacity-20" style="background-image: url(' . esc_url($background_url) . ');"></div>';
    }
    
    echo '<div class="container mx-auto px-4 relative z-10">';
    
    // Section header
    echo '<div class="section-header text-center mb-8">';
    
    if ($title) {
        echo '<h2 class="text-3xl md:text-4xl font-bold mb-4">' . esc_html($title) . '</h2>';
    }
    
    if ($subtitle) {
        echo '<p class="text-lg text-gray-200 max-w-3xl mx-auto">' . esc_html($subtitle) . '</p>';
    }
    
    echo '</div>';
    
    // Newsletter form
    echo '<div class="newsletter-form max-w-lg mx-auto">';
    echo '<form class="flex flex-col md:flex-row gap-4">';
    echo '<input type="email" class="form-input flex-grow" placeholder="' . esc_attr__('Your email address', 'aqualuxe') . '" required>';
    echo '<button type="submit" class="btn-gold whitespace-nowrap">' . esc_html__('Subscribe', 'aqualuxe') . '</button>';
    echo '</form>';
    echo '<p class="text-sm text-gray-300 mt-4 text-center">' . esc_html__('We respect your privacy. Unsubscribe at any time.', 'aqualuxe') . '</p>';
    echo '</div>';
    
    echo '</div>';
    echo '</section>';
}

/**
 * Prints the footer widgets.
 */
function aqualuxe_footer_widgets() {
    // Get footer columns
    $footer_columns = aqualuxe_get_option('aqualuxe_footer_columns', 4);
    
    // Check if any footer widget area is active
    $has_active_sidebar = false;
    for ($i = 1; $i <= $footer_columns; $i++) {
        if (is_active_sidebar('footer-' . $i)) {
            $has_active_sidebar = true;
            break;
        }
    }
    
    if (!$has_active_sidebar) {
        return;
    }

    echo '<div class="footer-widgets py-12 bg-gray-100 dark:bg-dark-800">';
    echo '<div class="container mx-auto px-4">';
    echo '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-' . esc_attr($footer_columns) . ' gap-8">';
    
    for ($i = 1; $i <= $footer_columns; $i++) {
        echo '<div class="footer-widget">';
        dynamic_sidebar('footer-' . $i);
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

/**
 * Prints the footer bottom.
 */
function aqualuxe_footer_bottom() {
    // Get footer settings
    $copyright = aqualuxe_get_option('aqualuxe_footer_copyright', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.');

    echo '<div class="footer-bottom py-6 bg-gray-800 dark:bg-dark-900 text-white">';
    echo '<div class="container mx-auto px-4">';
    echo '<div class="flex flex-col md:flex-row justify-between items-center">';
    
    // Copyright
    echo '<div class="copyright mb-4 md:mb-0">';
    echo wp_kses_post($copyright);
    echo '</div>';
    
    // Footer menu
    if (has_nav_menu('footer')) {
        wp_nav_menu(array(
            'theme_location' => 'footer',
            'menu_class' => 'footer-menu flex flex-wrap gap-4 text-sm',
            'container' => false,
            'depth' => 1,
        ));
    }
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

/**
 * Prints the back to top button.
 */
function aqualuxe_back_to_top() {
    // Check if back to top button is enabled
    if (!aqualuxe_get_option('aqualuxe_enable_back_to_top', true)) {
        return;
    }

    echo '<button id="back-to-top" class="back-to-top fixed bottom-8 right-8 z-50 p-2 rounded-full bg-primary-600 text-white shadow-lg transform translate-y-20 opacity-0 transition-all duration-300">';
    echo '<span class="sr-only">' . esc_html__('Back to top', 'aqualuxe') . '</span>';
    echo '<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">';
    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />';
    echo '</svg>';
    echo '</button>';
}