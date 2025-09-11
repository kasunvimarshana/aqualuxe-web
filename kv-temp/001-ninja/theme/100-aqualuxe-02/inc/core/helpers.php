<?php
/**
 * Helper functions for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Prints HTML with meta information for the current post-date/time.
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
 * Determines whether blog/site has more than one category.
 *
 * @return bool
 */
function aqualuxe_categorized_blog() {
    $category_count = get_transient('aqualuxe_categories');

    if (false === $category_count) {
        // Create an array of all the categories that are attached to posts.
        $categories = get_categories(array(
            'fields'     => 'ids',
            'hide_empty' => 1,
            // We only need to know if there is more than one category.
            'number'     => 2,
        ));

        // Count the number of categories that are attached to the posts.
        $category_count = count($categories);

        set_transient('aqualuxe_categories', $category_count);
    }

    // Allow the cached value to be filtered.
    return apply_filters('aqualuxe_categorized_blog', $category_count > 1);
}

/**
 * Flush out the transients used in aqualuxe_categorized_blog.
 */
function aqualuxe_category_transient_flusher() {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Like, beat it. Dig?
    delete_transient('aqualuxe_categories');
}
add_action('edit_category', 'aqualuxe_category_transient_flusher');
add_action('save_post', 'aqualuxe_category_transient_flusher');

/**
 * Get excerpt with custom length
 */
function aqualuxe_get_excerpt($limit = 55, $more = '...') {
    $excerpt = get_the_excerpt();
    if (!$excerpt) {
        $excerpt = get_the_content();
    }
    
    $excerpt = strip_tags($excerpt);
    $excerpt = wp_trim_words($excerpt, $limit, $more);
    
    return $excerpt;
}

/**
 * Custom excerpt length
 */
function aqualuxe_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length', 999);

/**
 * Custom excerpt more
 */
function aqualuxe_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Get reading time for post
 */
function aqualuxe_get_reading_time($post_id = null) {
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
function aqualuxe_reading_time($post_id = null) {
    $reading_time = aqualuxe_get_reading_time($post_id);
    
    if ($reading_time == 1) {
        echo sprintf(esc_html__('%d minute read', 'aqualuxe'), $reading_time);
    } else {
        echo sprintf(esc_html__('%d minutes read', 'aqualuxe'), $reading_time);
    }
}

/**
 * Get social share links
 */
function aqualuxe_get_social_share_links($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $url = get_permalink($post_id);
    $title = get_the_title($post_id);
    $excerpt = aqualuxe_get_excerpt(25);
    
    $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url);
    $twitter_url = 'https://twitter.com/intent/tweet?url=' . urlencode($url) . '&text=' . urlencode($title);
    $linkedin_url = 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($url);
    $pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . urlencode($url) . '&description=' . urlencode($title);
    
    return array(
        'facebook' => $facebook_url,
        'twitter' => $twitter_url,
        'linkedin' => $linkedin_url,
        'pinterest' => $pinterest_url,
    );
}

/**
 * Display social share buttons
 */
function aqualuxe_social_share_buttons($post_id = null) {
    $links = aqualuxe_get_social_share_links($post_id);
    
    echo '<div class="social-share">';
    echo '<span class="share-label">' . esc_html__('Share:', 'aqualuxe') . '</span>';
    
    foreach ($links as $platform => $url) {
        printf(
            '<a href="%s" target="_blank" rel="noopener" class="share-link share-%s" aria-label="%s">
                <i class="icon-%s" aria-hidden="true"></i>
            </a>',
            esc_url($url),
            esc_attr($platform),
            esc_attr(sprintf(__('Share on %s', 'aqualuxe'), ucfirst($platform))),
            esc_attr($platform)
        );
    }
    
    echo '</div>';
}

/**
 * Get responsive image sizes
 */
function aqualuxe_get_responsive_image($attachment_id, $size = 'full', $class = '') {
    if (!$attachment_id) {
        return '';
    }
    
    $image = wp_get_attachment_image(
        $attachment_id,
        $size,
        false,
        array(
            'class' => $class,
            'loading' => 'lazy',
        )
    );
    
    return $image;
}

/**
 * Check if page is using page builder
 */
function aqualuxe_is_page_builder() {
    // Check for popular page builders
    if (class_exists('Elementor\Plugin')) {
        return \Elementor\Plugin::$instance->documents->get(get_the_ID())->is_built_with_elementor();
    }
    
    if (function_exists('vc_is_page')) {
        return vc_is_page();
    }
    
    return false;
}

/**
 * Get theme customizer value
 */
function aqualuxe_get_customizer_value($setting, $default = '') {
    return get_theme_mod($setting, $default);
}