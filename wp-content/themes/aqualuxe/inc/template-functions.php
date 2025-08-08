<?php
/**
 * Template Functions - Luxury Ornamental Fish Theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('aqualuxe_posted_on')) {
    /**
     * Prints HTML with meta information for the current post-date/time.
     *
     * @since 1.0.0
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
}

if (!function_exists('aqualuxe_posted_by')) {
    /**
     * Prints HTML with meta information for the current author.
     *
     * @since 1.0.0
     */
    function aqualuxe_posted_by() {
        $byline = sprintf(
            /* translators: %s: post author. */
            esc_html_x('by %s', 'post author', 'aqualuxe'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}

if (!function_exists('aqualuxe_entry_footer')) {
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     *
     * @since 1.0.0
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
}

if (!function_exists('aqualuxe_post_thumbnail')) {
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     *
     * @since 1.0.0
     */
    function aqualuxe_post_thumbnail() {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return;
        }

        if (is_singular()) :
            ?>

            <div class="post-thumbnail">
                <?php the_post_thumbnail(); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    'post-thumbnail',
                    array(
                        'alt' => the_title_attribute(
                            array(
                                'echo' => false,
                            )
                        ),
                    )
                );
                ?>
            </a>

            <?php
        endif; // End is_singular().
    }
}

if (!function_exists('aqualuxe_comment_list')) {
    /**
     * Template for comments and pingbacks.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @since 1.0.0
     */
    function aqualuxe_comment_list($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;

        if ('pingback' == $comment->comment_type || 'trackback' == $comment->comment_type) : ?>

            <li id="comment-<?php comment_ID(); ?>" <?php comment_class('media'); ?>>
                <div class="comment-body">
                    <?php _e('Pingback:', 'aqualuxe'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(__('Edit', 'aqualuxe'), '<span class="edit-link">', '</span>'); ?>
                </div>

        <?php else : ?>

            <li id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?>>
                <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
                    <footer class="comment-meta">
                        <div class="comment-author vcard">
                            <?php if (0 != $args['avatar_size']) echo get_avatar($comment, $args['avatar_size']); ?>
                            <?php printf(__('%s <span class="says">says:</span>', 'aqualuxe'), sprintf('<b class="fn">%s</b>', get_comment_author_link())); ?>
                        </div><!-- .comment-author -->

                        <div class="comment-metadata">
                            <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                                <time datetime="<?php comment_time('c'); ?>">
                                    <?php printf(_x('%1$s at %2$s', '1: date, 2: time', 'aqualuxe'), get_comment_date(), get_comment_time()); ?>
                                </time>
                            </a>
                            <?php edit_comment_link(__('Edit', 'aqualuxe'), '<span class="edit-link">', '</span>'); ?>
                        </div><!-- .comment-metadata -->

                        <?php if ('0' == $comment->comment_approved) : ?>
                            <p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'aqualuxe'); ?></p>
                        <?php endif; ?>
                    </footer><!-- .comment-meta -->

                    <div class="comment-content">
                        <?php comment_text(); ?>
                    </div><!-- .comment-content -->

                    <div class="reply">
                        <?php
                        comment_reply_link(
                            array_merge(
                                $args,
                                array(
                                    'add_below' => 'div-comment',
                                    'depth'     => $depth,
                                    'max_depth' => $args['max_depth'],
                                    'before'    => '<div class="reply-button">',
                                    'after'     => '</div>',
                                )
                            )
                        );
                        ?>
                    </div><!-- .reply -->
                </article><!-- .comment-body -->

        <?php
        endif;
    }
}

if (!function_exists('aqualuxe_excerpt_more')) {
    /**
     * Replaces "[...]" (appended to automatically generated excerpts) with ... and
     * a 'Continue reading' link.
     *
     * @since 1.0.0
     *
     * @return string 'Continue reading' link prepended with an ellipsis.
     */
    function aqualuxe_excerpt_more($more) {
        if (!is_admin()) {
            $more = sprintf(
                '<a class="more-link" href="%1$s">%2$s</a>',
                esc_url(get_permalink(get_the_ID())),
                /* translators: %s: Name of current post */
                sprintf(__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe'), get_the_title(get_the_ID()))
            );
        }
        return $more;
    }
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

if (!function_exists('aqualuxe_body_classes')) {
    /**
     * Adds custom classes to the array of body classes.
     *
     * @since 1.0.0
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

        // Adds a class for sticky header.
        if (get_theme_mod('aqualuxe_sticky_header', true)) {
            $classes[] = 'aqualuxe-sticky-header';
        }

        return $classes;
    }
}
add_filter('body_class', 'aqualuxe_body_classes');

if (!function_exists('aqualuxe_pingback_header')) {
    /**
     * Add a pingback url auto-discovery header for single posts, pages, or attachments.
     *
     * @since 1.0.0
     */
    function aqualuxe_pingback_header() {
        if (is_singular() && pings_open()) {
            printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
        }
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');