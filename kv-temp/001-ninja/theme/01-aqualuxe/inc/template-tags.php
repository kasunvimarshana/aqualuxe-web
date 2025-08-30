<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 */

if (!function_exists('aqualuxe_posted_on')) :
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

        echo '<span class="posted-on text-dark-light dark:text-light-dark">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if (!function_exists('aqualuxe_posted_by')) :
    /**
     * Prints HTML with meta information for the current author.
     */
    function aqualuxe_posted_by() {
        $byline = sprintf(
            /* translators: %s: post author. */
            esc_html_x('by %s', 'post author', 'aqualuxe'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );

        echo '<span class="byline text-dark-light dark:text-light-dark"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if (!function_exists('aqualuxe_entry_footer')) :
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
                printf('<span class="cat-links text-dark-light dark:text-light-dark">' . esc_html__('Posted in %1$s', 'aqualuxe') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'aqualuxe'));
            if ($tags_list) {
                /* translators: 1: list of tags. */
                printf('<span class="tags-links text-dark-light dark:text-light-dark">' . esc_html__('Tagged %1$s', 'aqualuxe') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }

        if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link text-dark-light dark:text-light-dark">';
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
            '<span class="edit-link text-dark-light dark:text-light-dark">',
            '</span>'
        );
    }
endif;

if (!function_exists('aqualuxe_post_thumbnail')) :
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
            <div class="post-thumbnail mb-8">
                <?php the_post_thumbnail('aqualuxe-featured', array('class' => 'rounded-lg w-full h-auto')); ?>
            </div><!-- .post-thumbnail -->
        <?php else : ?>
            <a class="post-thumbnail block mb-4 overflow-hidden rounded-lg" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    'aqualuxe-card',
                    array(
                        'class' => 'w-full h-auto transition-transform duration-300 hover:scale-105',
                        'alt' => the_title_attribute(array('echo' => false)),
                    )
                );
                ?>
            </a>
            <?php
        endif; // End is_singular().
    }
endif;

if (!function_exists('aqualuxe_comment_avatar')) :
    /**
     * Returns the HTML markup to generate a user avatar.
     */
    function aqualuxe_get_comment_avatar($comment) {
        $avatar = get_avatar($comment, 60, '', '', array('class' => 'rounded-full'));
        return $avatar;
    }
endif;

if (!function_exists('aqualuxe_comment')) :
    /**
     * Template for comments and pingbacks.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     */
    function aqualuxe_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;
        switch ($comment->comment_type):
            case 'pingback':
            case 'trackback':
                ?>
                <li class="pingback">
                    <div class="comment-body">
                        <p><?php esc_html_e('Pingback:', 'aqualuxe'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(esc_html__('Edit', 'aqualuxe'), '<span class="edit-link">', '</span>'); ?></p>
                    </div>
                </li>
                <?php
                break;
            default:
                ?>
                <li id="comment-<?php comment_ID(); ?>" <?php comment_class('comment-item mb-6'); ?>>
                    <article id="div-comment-<?php comment_ID(); ?>" class="comment-body p-6 bg-white dark:bg-dark-light rounded-lg shadow-soft">
                        <div class="comment-meta flex items-center mb-4">
                            <div class="comment-author vcard mr-4">
                                <?php echo aqualuxe_get_comment_avatar($comment); ?>
                            </div><!-- .comment-author -->
                            <div>
                                <h5 class="font-bold text-dark dark:text-light">
                                    <?php comment_author_link(); ?>
                                </h5>
                                <div class="comment-metadata text-sm text-dark-light dark:text-light-dark">
                                    <time datetime="<?php comment_time('c'); ?>">
                                        <?php
                                        /* translators: 1: comment date, 2: comment time */
                                        printf(esc_html__('%1$s at %2$s', 'aqualuxe'), get_comment_date(), get_comment_time());
                                        ?>
                                    </time>
                                    <?php edit_comment_link(esc_html__('Edit', 'aqualuxe'), '<span class="edit-link ml-2">', '</span>'); ?>
                                </div><!-- .comment-metadata -->
                            </div>
                        </div><!-- .comment-meta -->

                        <div class="comment-content prose dark:prose-invert">
                            <?php comment_text(); ?>
                        </div><!-- .comment-content -->

                        <?php if ('0' == $comment->comment_approved) : ?>
                            <p class="comment-awaiting-moderation text-sm italic mt-2"><?php esc_html_e('Your comment is awaiting moderation.', 'aqualuxe'); ?></p>
                        <?php endif; ?>

                        <div class="reply mt-4">
                            <?php
                            comment_reply_link(
                                array_merge(
                                    $args,
                                    array(
                                        'add_below' => 'div-comment',
                                        'depth'     => $depth,
                                        'max_depth' => $args['max_depth'],
                                        'before'    => '<div class="reply-link">',
                                        'after'     => '</div>',
                                    )
                                )
                            );
                            ?>
                        </div><!-- .reply -->
                    </article><!-- .comment-body -->
                </li><!-- #comment-## -->
                <?php
                break;
        endswitch;
    }
endif;

if (!function_exists('aqualuxe_get_svg')) :
    /**
     * Output and Get Theme SVG.
     *
     * @param string $svg_name The name of the icon.
     * @param string $group The group the icon belongs to.
     * @param string $color Color code.
     */
    function aqualuxe_get_svg($svg_name, $group = 'ui', $color = '') {
        // SVG markup.
        $svg = '';
        
        switch ($svg_name) {
            case 'search':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>';
                break;
            case 'cart':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>';
                break;
            case 'user':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>';
                break;
            case 'menu':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>';
                break;
            case 'close':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
                break;
            case 'chevron-down':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>';
                break;
            case 'chevron-right':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>';
                break;
            case 'facebook':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>';
                break;
            case 'instagram':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>';
                break;
            case 'twitter':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>';
                break;
            case 'youtube':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-youtube"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>';
                break;
            case 'mail':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>';
                break;
            case 'phone':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>';
                break;
            case 'map-pin':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>';
                break;
            case 'heart':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>';
                break;
            case 'star':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
                break;
            case 'sun':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>';
                break;
            case 'moon':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>';
                break;
            case 'fish':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10c-1.5 0-3 .5-3 2s1.5 2 3 2 3-.5 3-2-1.5-2-3-2z"/><path d="M18 14c-5 0-11-2-11-7C7 2 2 2 2 2c0 5.5 3 8 7 8-5 0-7 2.5-7 8 0 0 5 0 5-5 0 5 6 7 11 7 4 0 8-1 8-7s-4-7-8-7z"/><circle cx="18" cy="12" r="1"/></svg>';
                break;
            case 'plant':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c-5 0-8-2-8-5 0-5 8-5 8-10 0 5 8 5 8 10 0 3-3 5-8 5z"/><path d="M12 22V12"/></svg>';
                break;
            case 'droplet':
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-droplet"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path></svg>';
                break;
        }

        if ($svg) {
            if ($color) {
                $svg = str_replace('stroke="currentColor"', 'stroke="' . esc_attr($color) . '"', $svg);
            }
            return $svg;
        }

        return '';
    }
endif;

if (!function_exists('aqualuxe_the_svg')) :
    /**
     * Echo the SVG markup.
     *
     * @param string $svg_name The name of the icon.
     * @param string $group The group the icon belongs to.
     * @param string $color Color code.
     */
    function aqualuxe_the_svg($svg_name, $group = 'ui', $color = '') {
        echo aqualuxe_get_svg($svg_name, $group, $color); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if (!function_exists('aqualuxe_get_related_posts')) :
    /**
     * Get related posts based on post tags.
     *
     * @param int   $post_id      The post ID.
     * @param int   $related_count Number of related posts to return.
     * @param array $args         Additional arguments for the query.
     * @return WP_Query
     */
    function aqualuxe_get_related_posts($post_id, $related_count = 3, $args = array()) {
        $args = wp_parse_args(
            $args,
            array(
                'orderby' => 'rand',
                'return'  => 'query',
            )
        );

        $post_type = get_post_type($post_id);
        $tags      = wp_get_post_terms($post_id, 'post_tag', array('fields' => 'ids'));
        $cats      = wp_get_post_terms($post_id, 'category', array('fields' => 'ids'));

        // Return empty object if no tags or categories
        if (empty($tags) && empty($cats)) {
            return new WP_Query();
        }

        $query_args = array(
            'post_type'      => $post_type,
            'posts_per_page' => $related_count,
            'post_status'    => 'publish',
            'post__not_in'   => array($post_id),
            'orderby'        => $args['orderby'],
            'tax_query'      => array(
                'relation' => 'OR',
            ),
        );

        if (!empty($tags)) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'post_tag',
                'field'    => 'term_id',
                'terms'    => $tags,
            );
        }

        if (!empty($cats)) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $cats,
            );
        }

        if ('ids' === $args['return']) {
            $query_args['fields'] = 'ids';
        }

        $query = new WP_Query($query_args);

        return $query;
    }
endif;

if (!function_exists('aqualuxe_get_page_title')) :
    /**
     * Get the page title for different types of pages.
     *
     * @return string
     */
    function aqualuxe_get_page_title() {
        $title = '';

        if (is_home()) {
            $title = get_the_title(get_option('page_for_posts', true));
        } elseif (is_archive()) {
            $title = get_the_archive_title();
        } elseif (is_search()) {
            /* translators: %s: search query. */
            $title = sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
        } elseif (is_404()) {
            $title = esc_html__('Page Not Found', 'aqualuxe');
        } elseif (is_page()) {
            $title = get_the_title();
        } elseif (is_singular('post')) {
            $title = get_the_title();
        } elseif (is_singular('aqualuxe_service')) {
            $title = esc_html__('Service Details', 'aqualuxe');
        } elseif (is_singular('aqualuxe_event')) {
            $title = esc_html__('Event Details', 'aqualuxe');
        } elseif (is_singular('product')) {
            $title = get_the_title();
        } else {
            $title = get_the_title();
        }

        return $title;
    }
endif;

if (!function_exists('aqualuxe_get_excerpt')) :
    /**
     * Get custom excerpt.
     *
     * @param int $limit Word limit for excerpt.
     * @return string
     */
    function aqualuxe_get_excerpt($limit = 25) {
        $excerpt = '';
        
        if (has_excerpt()) {
            $excerpt = get_the_excerpt();
        } else {
            $excerpt = get_the_content();
        }
        
        $excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
        $excerpt = strip_tags($excerpt);
        $excerpt = substr($excerpt, 0, $limit);
        $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
        $excerpt = trim($excerpt);
        $excerpt = $excerpt . '...';
        
        return $excerpt;
    }
endif;

if (!function_exists('aqualuxe_pagination')) :
    /**
     * Display pagination.
     *
     * @param array $args Arguments for pagination.
     */
    function aqualuxe_pagination($args = array()) {
        $defaults = array(
            'total'     => '',
            'current'   => max(1, get_query_var('paged')),
            'prev_text' => '<span aria-hidden="true">&laquo;</span><span class="sr-only">' . esc_html__('Previous', 'aqualuxe') . '</span>',
            'next_text' => '<span aria-hidden="true">&raquo;</span><span class="sr-only">' . esc_html__('Next', 'aqualuxe') . '</span>',
        );

        $args = wp_parse_args($args, $defaults);

        $links = paginate_links(
            array(
                'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                'format'    => '?paged=%#%',
                'current'   => $args['current'],
                'total'     => $args['total'],
                'prev_text' => $args['prev_text'],
                'next_text' => $args['next_text'],
                'type'      => 'array',
                'end_size'  => 2,
                'mid_size'  => 1,
            )
        );

        if (!empty($links)) :
            echo '<nav aria-label="' . esc_attr__('Page navigation', 'aqualuxe') . '">';
            echo '<ul class="pagination flex flex-wrap justify-center gap-2">';
            
            foreach ($links as $key => $link) {
                $active_class = strpos($link, 'current') !== false ? ' bg-primary text-white' : ' bg-light dark:bg-dark-light text-dark dark:text-light hover:bg-primary hover:text-white';
                echo '<li class="page-item">';
                echo str_replace(
                    'page-numbers',
                    'page-link inline-flex items-center justify-center w-10 h-10 rounded-md transition-colors duration-200' . $active_class,
                    $link
                );
                echo '</li>';
            }
            
            echo '</ul>';
            echo '</nav>';
        endif;
    }
endif;