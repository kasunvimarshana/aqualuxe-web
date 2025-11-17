<?php
/**
 * Template Tags
 *
 * Custom template tags for this theme.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('aqualuxe_posted_on')) :
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
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">' . $time_string . '</a>'
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
            '<span class="author vcard"><a class="url fn n hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
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
                <?php the_post_thumbnail('aqualuxe-featured-large', array('class' => 'w-full h-auto rounded-lg shadow-md')); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail('aqualuxe-featured-medium', array(
                    'class' => 'w-full h-auto rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200',
                    'alt'   => the_title_attribute(array('echo' => false))
                ));
                ?>
            </a>

            <?php
        endif; // End is_singular().
    }
endif;

if (!function_exists('aqualuxe_mobile_menu_fallback')) :
    /**
     * Fallback function for mobile menu when no menu is assigned.
     */
    function aqualuxe_mobile_menu_fallback() {
        echo '<ul class="space-y-2">';
        wp_list_pages(array(
            'title_li' => '',
            'walker'   => new AquaLuxe_Mobile_Walker_Page(),
        ));
        echo '</ul>';
    }
endif;

if (!function_exists('aqualuxe_fonts_url')) :
    /**
     * Register Google Fonts
     */
    function aqualuxe_fonts_url() {
        $fonts_url = '';
        $families  = array();

        /*
         * Translators: If there are characters in your language that are not
         * supported by Inter, translate this to 'off'. Do not translate
         * into your own language.
         */
        if ('off' !== _x('on', 'Inter font: on or off', 'aqualuxe')) {
            $families[] = 'Inter:wght@300;400;500;600;700';
        }

        /*
         * Translators: If there are characters in your language that are not
         * supported by Playfair Display, translate this to 'off'. Do not translate
         * into your own language.
         */
        if ('off' !== _x('on', 'Playfair Display font: on or off', 'aqualuxe')) {
            $families[] = 'Playfair Display:wght@400;500;600;700';
        }

        if (in_array('on', array(_x('on', 'Inter font: on or off', 'aqualuxe'), _x('on', 'Playfair Display font: on or off', 'aqualuxe')))) {
            $query_args = array(
                'family'  => urlencode(implode('|', $families)),
                'subset'  => urlencode('latin,latin-ext'),
                'display' => urlencode('swap'),
            );

            $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css2');
        }

        return esc_url_raw($fonts_url);
    }
endif;

if (!function_exists('aqualuxe_excerpt_more')) :
    /**
     * Replaces "[...]" (appended to automatically generated excerpts) with ... and
     * a 'Continue reading' link.
     */
    function aqualuxe_excerpt_more($link) {
        if (is_admin()) {
            return $link;
        }

        $link = sprintf(
            '<p class="link-more mt-4"><a href="%1$s" class="more-link inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors duration-200">%2$s <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></a></p>',
            esc_url(get_permalink(get_the_ID())),
            sprintf(
                /* translators: %s: Name of current post */
                __('Continue reading %s', 'aqualuxe'),
                '<span class="screen-reader-text">' . get_the_title(get_the_ID()) . '</span>'
            )
        );
        return ' &hellip; ' . $link;
    }
    add_filter('excerpt_more', 'aqualuxe_excerpt_more');
endif;

if (!function_exists('aqualuxe_breadcrumbs')) :
    /**
     * Display breadcrumbs navigation
     */
    function aqualuxe_breadcrumbs() {
        // Skip breadcrumbs on homepage
        if (is_front_page()) {
            return;
        }

        $breadcrumbs = array();
        $breadcrumbs[] = array(
            'title' => __('Home', 'aqualuxe'),
            'url'   => home_url('/')
        );

        if (is_category() || is_single()) {
            $category = get_the_category();
            if ($category) {
                foreach ($category as $cat) {
                    $breadcrumbs[] = array(
                        'title' => $cat->cat_name,
                        'url'   => get_category_link($cat->term_id)
                    );
                }
            }
        }

        if (is_single()) {
            $breadcrumbs[] = array(
                'title' => get_the_title(),
                'url'   => ''
            );
        }

        if (is_page()) {
            $breadcrumbs[] = array(
                'title' => get_the_title(),
                'url'   => ''
            );
        }

        if (is_tag()) {
            $breadcrumbs[] = array(
                'title' => single_tag_title('', false),
                'url'   => ''
            );
        }

        if (is_author()) {
            $breadcrumbs[] = array(
                'title' => get_the_author(),
                'url'   => ''
            );
        }

        if (is_404()) {
            $breadcrumbs[] = array(
                'title' => __('404 Error', 'aqualuxe'),
                'url'   => ''
            );
        }

        if (count($breadcrumbs) > 1) {
            echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
            echo '<ol class="flex flex-wrap items-center space-x-2 text-sm text-secondary-600 dark:text-secondary-400">';

            foreach ($breadcrumbs as $index => $breadcrumb) {
                echo '<li class="flex items-center">';
                
                if ($index > 0) {
                    echo '<svg class="w-4 h-4 mx-2 text-secondary-400 dark:text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>';
                    echo '</svg>';
                }

                if ($breadcrumb['url']) {
                    echo '<a href="' . esc_url($breadcrumb['url']) . '" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">' . esc_html($breadcrumb['title']) . '</a>';
                } else {
                    echo '<span class="text-secondary-900 dark:text-secondary-100 font-medium">' . esc_html($breadcrumb['title']) . '</span>';
                }
                
                echo '</li>';
            }

            echo '</ol>';
            echo '</nav>';
        }
    }
endif;