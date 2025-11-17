<?php
/**
 * General template tags
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

        echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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

        echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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

            <div class="post-thumbnail">
                <?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
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
                        'class' => 'img-fluid',
                    )
                );
                ?>
            </a>

            <?php
        endif; // End is_singular().
    }
endif;

if (!function_exists('aqualuxe_comment')) :
    /**
     * Template for comments and pingbacks.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @param WP_Comment $comment Comment to display.
     * @param array      $args    Arguments passed to wp_list_comments().
     * @param int        $depth   Depth of the current comment.
     */
    function aqualuxe_comment($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment;

        switch ($comment->comment_type):
            case 'pingback':
            case 'trackback':
                ?>
                <li class="post pingback">
                    <p><?php esc_html_e('Pingback:', 'aqualuxe'); ?> <?php comment_author_link(); ?><?php edit_comment_link(esc_html__('Edit', 'aqualuxe'), '<span class="edit-link">', '</span>'); ?></p>
                <?php
                break;
            default:
                ?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                    <article id="comment-<?php comment_ID(); ?>" class="comment">
                        <div class="comment-meta">
                            <div class="comment-author vcard">
                                <?php
                                $avatar_size = 60;
                                if ('0' !== $comment->comment_parent) {
                                    $avatar_size = 40;
                                }

                                echo get_avatar($comment, $avatar_size);

                                /* translators: 1: comment author, 2: date and time */
                                printf(
                                    '<cite class="fn">%1$s</cite>',
                                    get_comment_author_link()
                                );
                                ?>
                            </div><!-- .comment-author -->

                            <div class="comment-metadata">
                                <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                                    <time datetime="<?php comment_time('c'); ?>">
                                        <?php
                                        /* translators: 1: date, 2: time */
                                        printf(esc_html__('%1$s at %2$s', 'aqualuxe'), get_comment_date(), get_comment_time());
                                        ?>
                                    </time>
                                </a>
                                <?php edit_comment_link(esc_html__('Edit', 'aqualuxe'), '<span class="edit-link">', '</span>'); ?>
                            </div><!-- .comment-metadata -->

                            <?php if ('0' === $comment->comment_approved) : ?>
                                <p class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'aqualuxe'); ?></p>
                            <?php endif; ?>
                        </div><!-- .comment-meta -->

                        <div class="comment-content">
                            <?php comment_text(); ?>
                        </div><!-- .comment-content -->

                        <div class="reply">
                            <?php
                            comment_reply_link(
                                array_merge(
                                    $args,
                                    array(
                                        'add_below' => 'comment',
                                        'depth'     => $depth,
                                        'max_depth' => $args['max_depth'],
                                    )
                                )
                            );
                            ?>
                        </div><!-- .reply -->
                    </article><!-- #comment-## -->
                <?php
                break;
        endswitch;
    }
endif;

if (!function_exists('aqualuxe_get_svg')) :
    /**
     * Return SVG markup.
     *
     * @param array $args {
     *     Parameters needed to display an SVG.
     *
     *     @type string $icon  Required SVG icon filename.
     *     @type string $title Optional SVG title.
     *     @type string $desc  Optional SVG description.
     * }
     * @return string SVG markup.
     */
    function aqualuxe_get_svg($args = array()) {
        // Make sure $args are an array.
        if (empty($args)) {
            return esc_html__('Please define default parameters in the function call.', 'aqualuxe');
        }

        // Define an icon.
        if (false === array_key_exists('icon', $args)) {
            return esc_html__('Please define an SVG icon filename.', 'aqualuxe');
        }

        // Set defaults.
        $defaults = array(
            'icon'     => '',
            'title'    => '',
            'desc'     => '',
            'fallback' => false,
        );

        // Parse args.
        $args = wp_parse_args($args, $defaults);

        // Set aria hidden.
        $aria_hidden = ' aria-hidden="true"';

        // Set ARIA.
        $aria_labelledby = '';

        /*
         * AquaLuxe doesn't use the SVG title or description attributes; non-decorative icons are described with .screen-reader-text.
         *
         * However, child themes can use the title and description to add information to non-decorative SVG icons to improve accessibility.
         *
         * Example 1 with title: <?php echo aqualuxe_get_svg( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ) ) ); ?>
         *
         * Example 2 with title and description: <?php echo aqualuxe_get_svg( array( 'icon' => 'arrow-right', 'title' => __( 'This is the title', 'textdomain' ), 'desc' => __( 'This is the description', 'textdomain' ) ) ); ?>
         *
         * See https://www.paciellogroup.com/blog/2013/12/using-aria-enhance-svg-accessibility/.
         */
        if ($args['title']) {
            $aria_hidden     = '';
            $unique_id       = uniqid();
            $aria_labelledby = ' aria-labelledby="title-' . $unique_id . '"';

            if ($args['desc']) {
                $aria_labelledby = ' aria-labelledby="title-' . $unique_id . ' desc-' . $unique_id . '"';
            }
        }

        // Begin SVG markup.
        $svg = '<svg class="icon icon-' . esc_attr($args['icon']) . '"' . $aria_hidden . $aria_labelledby . ' role="img">';

        // Display the title.
        if ($args['title']) {
            $svg .= '<title id="title-' . $unique_id . '">' . esc_html($args['title']) . '</title>';

            // Display the desc only if the title is already set.
            if ($args['desc']) {
                $svg .= '<desc id="desc-' . $unique_id . '">' . esc_html($args['desc']) . '</desc>';
            }
        }

        /*
         * Display the icon.
         *
         * The whitespace around `<use>` is intentional - it is a work around to a keyboard navigation bug in Safari 10.
         *
         * See https://core.trac.wordpress.org/ticket/38387.
         */
        $svg .= ' <use href="#icon-' . esc_html($args['icon']) . '" xlink:href="#icon-' . esc_html($args['icon']) . '"></use> ';

        // Add some markup to use as a fallback for browsers that do not support SVGs.
        if ($args['fallback']) {
            $svg .= '<span class="svg-fallback icon-' . esc_attr($args['icon']) . '"></span>';
        }

        $svg .= '</svg>';

        return $svg;
    }
endif;

if (!function_exists('aqualuxe_get_icon_svg')) :
    /**
     * Get SVG icon by name
     *
     * @param string $icon Icon name.
     * @param array  $args Icon arguments.
     * @return string SVG icon.
     */
    function aqualuxe_get_icon_svg($icon, $args = array()) {
        $args['icon'] = $icon;
        return aqualuxe_get_svg($args);
    }
endif;

if (!function_exists('aqualuxe_the_excerpt')) :
    /**
     * Displays the excerpt with custom length.
     *
     * @param int $length Optional. Excerpt length. Default 55.
     * @param string $more Optional. More string. Default '...'.
     */
    function aqualuxe_the_excerpt($length = 55, $more = '...') {
        $excerpt = get_the_excerpt();
        
        if (!$excerpt) {
            $excerpt = get_the_content();
            $excerpt = strip_shortcodes($excerpt);
            $excerpt = excerpt_remove_blocks($excerpt);
            $excerpt = strip_tags($excerpt);
        }
        
        $words = explode(' ', $excerpt, $length + 1);
        
        if (count($words) > $length) {
            array_pop($words);
            $excerpt = implode(' ', $words);
            $excerpt .= $more;
        } else {
            $excerpt = implode(' ', $words);
        }
        
        echo wp_kses_post($excerpt);
    }
endif;

if (!function_exists('aqualuxe_get_the_excerpt')) :
    /**
     * Gets the excerpt with custom length.
     *
     * @param int $length Optional. Excerpt length. Default 55.
     * @param string $more Optional. More string. Default '...'.
     * @return string The excerpt.
     */
    function aqualuxe_get_the_excerpt($length = 55, $more = '...') {
        $excerpt = get_the_excerpt();
        
        if (!$excerpt) {
            $excerpt = get_the_content();
            $excerpt = strip_shortcodes($excerpt);
            $excerpt = excerpt_remove_blocks($excerpt);
            $excerpt = strip_tags($excerpt);
        }
        
        $words = explode(' ', $excerpt, $length + 1);
        
        if (count($words) > $length) {
            array_pop($words);
            $excerpt = implode(' ', $words);
            $excerpt .= $more;
        } else {
            $excerpt = implode(' ', $words);
        }
        
        return wp_kses_post($excerpt);
    }
endif;

if (!function_exists('aqualuxe_get_post_meta')) :
    /**
     * Get post meta.
     */
    function aqualuxe_get_post_meta() {
        // Posted on.
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

        // Byline.
        $byline = sprintf(
            /* translators: %s: post author. */
            esc_html_x('by %s', 'post author', 'aqualuxe'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );

        // Categories.
        $categories_list = get_the_category_list(esc_html__(', ', 'aqualuxe'));
        if ($categories_list) {
            /* translators: 1: list of categories. */
            $categories = sprintf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'aqualuxe') . '</span>', $categories_list);
        } else {
            $categories = '';
        }

        // Comments.
        if (!post_password_required() && (comments_open() || get_comments_number())) {
            $comments = '<span class="comments-link">';
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
            $comments .= '</span>';
        } else {
            $comments = '';
        }

        // Edit link.
        $edit_link = '';
        if (current_user_can('edit_post', get_the_ID())) {
            $edit_link = sprintf(
                '<span class="edit-link"><a href="%s">%s</a></span>',
                esc_url(get_edit_post_link()),
                esc_html__('Edit', 'aqualuxe')
            );
        }

        return array(
            'posted_on' => $posted_on,
            'byline'    => $byline,
            'categories' => $categories,
            'comments'  => $comments,
            'edit_link' => $edit_link,
        );
    }
endif;

if (!function_exists('aqualuxe_get_post_thumbnail_url')) :
    /**
     * Get post thumbnail URL.
     *
     * @param string $size Optional. Image size. Default 'post-thumbnail'.
     * @return string|false Post thumbnail URL or false if no thumbnail is available.
     */
    function aqualuxe_get_post_thumbnail_url($size = 'post-thumbnail') {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return false;
        }

        return get_the_post_thumbnail_url(get_the_ID(), $size);
    }
endif;

if (!function_exists('aqualuxe_get_post_thumbnail_data')) :
    /**
     * Get post thumbnail data.
     *
     * @param string $size Optional. Image size. Default 'post-thumbnail'.
     * @return array|false Post thumbnail data or false if no thumbnail is available.
     */
    function aqualuxe_get_post_thumbnail_data($size = 'post-thumbnail') {
        if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
            return false;
        }

        $thumbnail_id = get_post_thumbnail_id(get_the_ID());
        $thumbnail = wp_get_attachment_image_src($thumbnail_id, $size);

        if (!$thumbnail) {
            return false;
        }

        return array(
            'url'    => $thumbnail[0],
            'width'  => $thumbnail[1],
            'height' => $thumbnail[2],
            'alt'    => get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true),
        );
    }
endif;

if (!function_exists('aqualuxe_get_related_posts')) :
    /**
     * Get related posts.
     *
     * @param int   $post_id Optional. Post ID. Default current post ID.
     * @param int   $number Optional. Number of posts to get. Default 3.
     * @param array $args Optional. WP_Query arguments. Default empty array.
     * @return WP_Query Related posts query.
     */
    function aqualuxe_get_related_posts($post_id = 0, $number = 3, $args = array()) {
        $post_id = $post_id ? $post_id : get_the_ID();
        $categories = get_the_category($post_id);
        
        if (empty($categories)) {
            return new WP_Query();
        }
        
        $category_ids = array();
        foreach ($categories as $category) {
            $category_ids[] = $category->term_id;
        }
        
        $default_args = array(
            'category__in'        => $category_ids,
            'post__not_in'        => array($post_id),
            'posts_per_page'      => $number,
            'ignore_sticky_posts' => 1,
        );
        
        $args = wp_parse_args($args, $default_args);
        
        return new WP_Query($args);
    }
endif;

if (!function_exists('aqualuxe_get_post_views')) :
    /**
     * Get post views.
     *
     * @param int $post_id Optional. Post ID. Default current post ID.
     * @return int Post views.
     */
    function aqualuxe_get_post_views($post_id = 0) {
        $post_id = $post_id ? $post_id : get_the_ID();
        $count_key = 'aqualuxe_post_views_count';
        $count = get_post_meta($post_id, $count_key, true);
        
        if ($count === '') {
            delete_post_meta($post_id, $count_key);
            add_post_meta($post_id, $count_key, '0');
            return 0;
        }
        
        return (int) $count;
    }
endif;

if (!function_exists('aqualuxe_set_post_views')) :
    /**
     * Set post views.
     *
     * @param int $post_id Optional. Post ID. Default current post ID.
     */
    function aqualuxe_set_post_views($post_id = 0) {
        $post_id = $post_id ? $post_id : get_the_ID();
        $count_key = 'aqualuxe_post_views_count';
        $count = get_post_meta($post_id, $count_key, true);
        
        if ($count === '') {
            delete_post_meta($post_id, $count_key);
            add_post_meta($post_id, $count_key, '1');
        } else {
            $count++;
            update_post_meta($post_id, $count_key, $count);
        }
    }
endif;

if (!function_exists('aqualuxe_get_reading_time')) :
    /**
     * Get reading time.
     *
     * @param int $post_id Optional. Post ID. Default current post ID.
     * @param int $words_per_minute Optional. Words per minute. Default 200.
     * @return int Reading time in minutes.
     */
    function aqualuxe_get_reading_time($post_id = 0, $words_per_minute = 200) {
        $post_id = $post_id ? $post_id : get_the_ID();
        $content = get_post_field('post_content', $post_id);
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / $words_per_minute);
        
        return max(1, $reading_time);
    }
endif;

if (!function_exists('aqualuxe_get_social_share_links')) :
    /**
     * Get social share links.
     *
     * @param int $post_id Optional. Post ID. Default current post ID.
     * @return array Social share links.
     */
    function aqualuxe_get_social_share_links($post_id = 0) {
        $post_id = $post_id ? $post_id : get_the_ID();
        $url = urlencode(get_permalink($post_id));
        $title = urlencode(get_the_title($post_id));
        $thumbnail = has_post_thumbnail($post_id) ? urlencode(get_the_post_thumbnail_url($post_id, 'full')) : '';
        
        return array(
            'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
            'twitter' => 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title,
            'linkedin' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title,
            'pinterest' => $thumbnail ? 'https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . $thumbnail . '&description=' . $title : '',
            'email' => 'mailto:?subject=' . $title . '&body=' . esc_html__('Check out this article:', 'aqualuxe') . ' ' . $url,
        );
    }
endif;

if (!function_exists('aqualuxe_get_post_author_box')) :
    /**
     * Get post author box.
     *
     * @param int $post_id Optional. Post ID. Default current post ID.
     * @return string|false Author box HTML or false if no author description.
     */
    function aqualuxe_get_post_author_box($post_id = 0) {
        $post_id = $post_id ? $post_id : get_the_ID();
        $author_id = get_post_field('post_author', $post_id);
        $author_description = get_the_author_meta('description', $author_id);
        
        if (!$author_description) {
            return false;
        }
        
        $author_name = get_the_author_meta('display_name', $author_id);
        $author_url = get_author_posts_url($author_id);
        $author_avatar = get_avatar($author_id, 100);
        
        $output = '<div class="author-box">';
        $output .= '<div class="author-avatar">' . $author_avatar . '</div>';
        $output .= '<div class="author-info">';
        $output .= '<h3 class="author-name">' . esc_html__('About', 'aqualuxe') . ' ' . esc_html($author_name) . '</h3>';
        $output .= '<div class="author-description">' . wpautop($author_description) . '</div>';
        $output .= '<a href="' . esc_url($author_url) . '" class="author-link">' . sprintf(esc_html__('View all posts by %s', 'aqualuxe'), esc_html($author_name)) . '</a>';
        $output .= '</div>';
        $output .= '</div>';
        
        return $output;
    }
endif;

if (!function_exists('aqualuxe_get_post_navigation')) :
    /**
     * Get post navigation.
     *
     * @param int $post_id Optional. Post ID. Default current post ID.
     * @return string Post navigation HTML.
     */
    function aqualuxe_get_post_navigation($post_id = 0) {
        $post_id = $post_id ? $post_id : get_the_ID();
        
        $prev_post = get_previous_post();
        $next_post = get_next_post();
        
        $output = '<nav class="post-navigation" aria-label="' . esc_attr__('Post navigation', 'aqualuxe') . '">';
        $output .= '<div class="post-navigation-inner">';
        
        if ($prev_post) {
            $output .= '<div class="post-navigation-prev">';
            $output .= '<a href="' . esc_url(get_permalink($prev_post)) . '" rel="prev">';
            $output .= '<span class="post-navigation-label">' . esc_html__('Previous', 'aqualuxe') . '</span>';
            $output .= '<span class="post-navigation-title">' . esc_html(get_the_title($prev_post)) . '</span>';
            $output .= '</a>';
            $output .= '</div>';
        }
        
        if ($next_post) {
            $output .= '<div class="post-navigation-next">';
            $output .= '<a href="' . esc_url(get_permalink($next_post)) . '" rel="next">';
            $output .= '<span class="post-navigation-label">' . esc_html__('Next', 'aqualuxe') . '</span>';
            $output .= '<span class="post-navigation-title">' . esc_html(get_the_title($next_post)) . '</span>';
            $output .= '</a>';
            $output .= '</div>';
        }
        
        $output .= '</div>';
        $output .= '</nav>';
        
        return $output;
    }
endif;

if (!function_exists('aqualuxe_get_breadcrumbs')) :
    /**
     * Get breadcrumbs.
     *
     * @return string Breadcrumbs HTML.
     */
    function aqualuxe_get_breadcrumbs() {
        $home_label = esc_html__('Home', 'aqualuxe');
        $separator = '<span class="breadcrumb-separator">/</span>';
        
        $output = '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">';
        $output .= '<a href="' . esc_url(home_url('/')) . '">' . $home_label . '</a>';
        
        if (is_category() || is_single()) {
            $output .= $separator;
            
            if (is_single()) {
                $categories = get_the_category();
                if (!empty($categories)) {
                    $output .= '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
                    $output .= $separator;
                }
                $output .= '<span class="current">' . get_the_title() . '</span>';
            } else {
                $output .= '<span class="current">' . single_cat_title('', false) . '</span>';
            }
        } elseif (is_page()) {
            $output .= $separator;
            $output .= '<span class="current">' . get_the_title() . '</span>';
        } elseif (is_search()) {
            $output .= $separator;
            $output .= '<span class="current">' . sprintf(esc_html__('Search Results for: %s', 'aqualuxe'), get_search_query()) . '</span>';
        } elseif (is_404()) {
            $output .= $separator;
            $output .= '<span class="current">' . esc_html__('Page Not Found', 'aqualuxe') . '</span>';
        } elseif (is_home()) {
            $output .= $separator;
            $output .= '<span class="current">' . esc_html__('Blog', 'aqualuxe') . '</span>';
        } elseif (is_archive()) {
            $output .= $separator;
            $output .= '<span class="current">' . get_the_archive_title() . '</span>';
        }
        
        $output .= '</nav>';
        
        return $output;
    }
endif;