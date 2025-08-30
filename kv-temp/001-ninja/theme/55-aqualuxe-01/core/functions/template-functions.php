<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    // Add a class if WooCommerce is active
    if (aqualuxe_is_woocommerce_active()) {
        $classes[] = 'woocommerce-active';
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');

/**
 * Adds custom classes to the array of post classes.
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function aqualuxe_post_classes($classes) {
    // Add a class for featured image
    if (has_post_thumbnail()) {
        $classes[] = 'has-thumbnail';
    } else {
        $classes[] = 'no-thumbnail';
    }

    return $classes;
}
add_filter('post_class', 'aqualuxe_post_classes');

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
 * Get the post thumbnail with responsive image sizes
 *
 * @param string $size The image size.
 * @param array  $attr Optional. Attributes for the image markup.
 * @return string
 */
function aqualuxe_get_post_thumbnail($size = 'post-thumbnail', $attr = array()) {
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
        return '';
    }

    if (!isset($attr['sizes'])) {
        $attr['sizes'] = '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw';
    }

    return get_the_post_thumbnail(null, $size, $attr);
}

/**
 * Display the post thumbnail with responsive image sizes
 *
 * @param string $size The image size.
 * @param array  $attr Optional. Attributes for the image markup.
 */
function aqualuxe_post_thumbnail($size = 'post-thumbnail', $attr = array()) {
    echo aqualuxe_get_post_thumbnail($size, $attr); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get the post excerpt with custom length
 *
 * @param int $length The excerpt length.
 * @return string
 */
function aqualuxe_get_excerpt($length = 55) {
    $excerpt = get_the_excerpt();
    
    if (!$excerpt) {
        $excerpt = get_the_content();
    }
    
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $excerpt = substr($excerpt, 0, $length);
    $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
    $excerpt = trim(preg_replace('/\s+/', ' ', $excerpt));
    $excerpt = $excerpt . '...';
    
    return $excerpt;
}

/**
 * Display the post excerpt with custom length
 *
 * @param int $length The excerpt length.
 */
function aqualuxe_excerpt($length = 55) {
    echo esc_html(aqualuxe_get_excerpt($length));
}

/**
 * Get the post author avatar
 *
 * @param int $size The avatar size in pixels.
 * @return string
 */
function aqualuxe_get_author_avatar($size = 40) {
    return get_avatar(get_the_author_meta('ID'), $size, '', get_the_author_meta('display_name'), array('class' => 'author-avatar'));
}

/**
 * Display the post author avatar
 *
 * @param int $size The avatar size in pixels.
 */
function aqualuxe_author_avatar($size = 40) {
    echo aqualuxe_get_author_avatar($size); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get the post reading time
 *
 * @return string
 */
function aqualuxe_get_reading_time() {
    $content = get_post_field('post_content', get_the_ID());
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Assuming 200 words per minute reading speed
    
    if ($reading_time < 1) {
        $reading_time = 1;
    }
    
    /* translators: %d: Reading time in minutes */
    return sprintf(_n('%d min read', '%d min read', $reading_time, 'aqualuxe'), $reading_time);
}

/**
 * Display the post reading time
 */
function aqualuxe_reading_time() {
    echo esc_html(aqualuxe_get_reading_time());
}

/**
 * Get the post share links
 *
 * @return string
 */
function aqualuxe_get_share_links() {
    $post_url = urlencode(get_permalink());
    $post_title = urlencode(get_the_title());
    
    $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url;
    $twitter_url = 'https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title;
    $linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title;
    $pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&media=' . urlencode(get_the_post_thumbnail_url()) . '&description=' . $post_title;
    
    $output = '<div class="share-links">';
    $output .= '<span class="share-title">' . esc_html__('Share:', 'aqualuxe') . '</span>';
    $output .= '<a href="' . esc_url($facebook_url) . '" target="_blank" rel="noopener noreferrer" class="share-link share-facebook" aria-label="' . esc_attr__('Share on Facebook', 'aqualuxe') . '">';
    $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>';
    $output .= '</a>';
    $output .= '<a href="' . esc_url($twitter_url) . '" target="_blank" rel="noopener noreferrer" class="share-link share-twitter" aria-label="' . esc_attr__('Share on Twitter', 'aqualuxe') . '">';
    $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M22.162 5.656a8.384 8.384 0 0 1-2.402.658A4.196 4.196 0 0 0 21.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 0 0-7.126 3.814 11.874 11.874 0 0 1-8.62-4.37 4.168 4.168 0 0 0-.566 2.103c0 1.45.738 2.731 1.86 3.481a4.168 4.168 0 0 1-1.894-.523v.052a4.185 4.185 0 0 0 3.355 4.101 4.21 4.21 0 0 1-1.89.072A4.185 4.185 0 0 0 7.97 16.65a8.394 8.394 0 0 1-6.191 1.732 11.83 11.83 0 0 0 6.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 0 0 2.087-2.165z"/></svg>';
    $output .= '</a>';
    $output .= '<a href="' . esc_url($linkedin_url) . '" target="_blank" rel="noopener noreferrer" class="share-link share-linkedin" aria-label="' . esc_attr__('Share on LinkedIn', 'aqualuxe') . '">';
    $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M6.94 5a2 2 0 1 1-4-.002 2 2 0 0 1 4 .002zM7 8.48H3V21h4V8.48zm6.32 0H9.34V21h3.94v-6.57c0-3.66 4.77-4 4.77 0V21H22v-7.93c0-6.17-7.06-5.94-8.72-2.91l.04-1.68z"/></svg>';
    $output .= '</a>';
    $output .= '<a href="' . esc_url($pinterest_url) . '" target="_blank" rel="noopener noreferrer" class="share-link share-pinterest" aria-label="' . esc_attr__('Share on Pinterest', 'aqualuxe') . '">';
    $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.37 2.094A10.003 10.003 0 0 0 8.002 21.17a7.757 7.757 0 0 1 .163-2.293c.185-.839 1.296-5.463 1.296-5.463a3.739 3.739 0 0 1-.324-1.577c0-1.485.857-2.593 1.923-2.593a1.334 1.334 0 0 1 1.342 1.508c0 .9-.578 2.262-.88 3.54a1.544 1.544 0 0 0 1.575 1.923c1.898 0 3.17-2.431 3.17-5.301 0-2.2-1.457-3.848-4.143-3.848a4.746 4.746 0 0 0-4.93 4.794 2.96 2.96 0 0 0 .648 1.97.48.48 0 0 1 .162.554c-.46.184-.162.623-.208.784a.354.354 0 0 1-.51.254c-1.384-.554-2.036-2.077-2.036-3.816 0-2.847 2.384-6.255 7.154-6.255 3.796 0 6.32 2.777 6.32 5.747 0 3.909-2.177 6.848-5.394 6.848a2.861 2.861 0 0 1-2.454-1.246s-.578 2.316-.692 2.754a8.026 8.026 0 0 1-1.019 2.131c.923.28 1.882.42 2.846.416a9.988 9.988 0 0 0 9.996-10.003 10.002 10.002 0 0 0-8.635-9.903z"/></svg>';
    $output .= '</a>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Display the post share links
 */
function aqualuxe_share_links() {
    echo aqualuxe_get_share_links(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get related posts
 *
 * @param int $post_id The post ID.
 * @param int $number_posts The number of posts to return.
 * @return WP_Query
 */
function aqualuxe_get_related_posts($post_id = 0, $number_posts = 3) {
    $post_id = $post_id ? $post_id : get_the_ID();
    
    $categories = get_the_category($post_id);
    $category_ids = array();
    
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
    
    if (empty($category_ids)) {
        return new WP_Query();
    }
    
    $args = array(
        'category__in' => $category_ids,
        'post__not_in' => array($post_id),
        'posts_per_page' => $number_posts,
        'ignore_sticky_posts' => 1,
    );
    
    return new WP_Query($args);
}

/**
 * Display related posts
 *
 * @param int $post_id The post ID.
 * @param int $number_posts The number of posts to return.
 */
function aqualuxe_related_posts($post_id = 0, $number_posts = 3) {
    $related_query = aqualuxe_get_related_posts($post_id, $number_posts);
    
    if ($related_query->have_posts()) :
        ?>
        <div class="related-posts">
            <h3 class="related-posts-title"><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h3>
            <div class="related-posts-grid">
                <?php
                while ($related_query->have_posts()) :
                    $related_query->the_post();
                    ?>
                    <article class="related-post">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="related-post-thumbnail">
                                <?php the_post_thumbnail('aqualuxe-thumbnail'); ?>
                            </a>
                        <?php endif; ?>
                        <div class="related-post-content">
                            <h4 class="related-post-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h4>
                            <div class="related-post-meta">
                                <?php echo get_the_date(); ?>
                            </div>
                        </div>
                    </article>
                <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <?php
    endif;
}

/**
 * Get breadcrumbs
 *
 * @return string
 */
function aqualuxe_get_breadcrumbs() {
    if (is_front_page()) {
        return '';
    }
    
    $output = '<div class="breadcrumbs">';
    $output .= '<span class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'aqualuxe') . '</a></span>';
    
    if (is_category() || is_single()) {
        $output .= '<span class="breadcrumb-separator">/</span>';
        
        if (is_single()) {
            $categories = get_the_category();
            if (!empty($categories)) {
                $output .= '<span class="breadcrumb-item"><a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a></span>';
                $output .= '<span class="breadcrumb-separator">/</span>';
            }
            $output .= '<span class="breadcrumb-item breadcrumb-current">' . get_the_title() . '</span>';
        } else {
            $output .= '<span class="breadcrumb-item breadcrumb-current">' . single_cat_title('', false) . '</span>';
        }
    } elseif (is_page()) {
        $output .= '<span class="breadcrumb-separator">/</span>';
        $output .= '<span class="breadcrumb-item breadcrumb-current">' . get_the_title() . '</span>';
    } elseif (is_search()) {
        $output .= '<span class="breadcrumb-separator">/</span>';
        $output .= '<span class="breadcrumb-item breadcrumb-current">' . esc_html__('Search Results for: ', 'aqualuxe') . get_search_query() . '</span>';
    } elseif (is_tag()) {
        $output .= '<span class="breadcrumb-separator">/</span>';
        $output .= '<span class="breadcrumb-item breadcrumb-current">' . single_tag_title('', false) . '</span>';
    } elseif (is_author()) {
        $output .= '<span class="breadcrumb-separator">/</span>';
        $output .= '<span class="breadcrumb-item breadcrumb-current">' . get_the_author() . '</span>';
    } elseif (is_archive()) {
        $output .= '<span class="breadcrumb-separator">/</span>';
        $output .= '<span class="breadcrumb-item breadcrumb-current">' . get_the_archive_title() . '</span>';
    } elseif (is_404()) {
        $output .= '<span class="breadcrumb-separator">/</span>';
        $output .= '<span class="breadcrumb-item breadcrumb-current">' . esc_html__('404 Not Found', 'aqualuxe') . '</span>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    echo aqualuxe_get_breadcrumbs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}