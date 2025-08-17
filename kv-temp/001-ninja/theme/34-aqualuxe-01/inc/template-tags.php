<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 * @since 1.0.0
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
                printf('<span class="tags-links ml-4">' . esc_html__('Tagged %1$s', 'aqualuxe') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }

        if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link ml-4">';
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
            '<span class="edit-link ml-4">',
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
                <?php the_post_thumbnail('full', ['class' => 'rounded w-full h-auto']); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="post-thumbnail block mb-4 overflow-hidden rounded" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    'post-thumbnail',
                    array(
                        'alt' => the_title_attribute(
                            array(
                                'echo' => false,
                            )
                        ),
                        'class' => 'w-full h-auto hover:scale-105 transition-transform duration-300',
                    )
                );
                ?>
            </a>

        <?php
        endif; // End is_singular().
    }
endif;

if (!function_exists('aqualuxe_get_svg')) :
    /**
     * Output an SVG icon.
     *
     * @param string $icon The icon name.
     * @param array  $args {
     *     Optional. Arguments to configure the SVG.
     *
     *     @type string $class Additional classes for the SVG.
     *     @type string $size  Size of the SVG icon.
     * }
     */
    function aqualuxe_get_svg($icon, $args = array()) {
        $defaults = array(
            'class' => '',
            'size'  => '24',
        );

        $args = wp_parse_args($args, $defaults);

        $class = $args['class'] ? ' class="' . esc_attr($args['class']) . '"' : '';
        $size  = $args['size'] ? ' width="' . esc_attr($args['size']) . '" height="' . esc_attr($args['size']) . '"' : '';

        $svg = '';

        switch ($icon) {
            case 'search':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>';
                break;
            case 'menu':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>';
                break;
            case 'close':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
                break;
            case 'cart':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>';
                break;
            case 'user':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>';
                break;
            case 'heart':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>';
                break;
            case 'chevron-down':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>';
                break;
            case 'chevron-right':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>';
                break;
            case 'chevron-left':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>';
                break;
            case 'mail':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>';
                break;
            case 'phone':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>';
                break;
            case 'location':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>';
                break;
            case 'facebook':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>';
                break;
            case 'twitter':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>';
                break;
            case 'instagram':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>';
                break;
            case 'youtube':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>';
                break;
            case 'linkedin':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>';
                break;
            case 'pinterest':
                $svg = '<svg' . $class . $size . ' xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" fill-rule="evenodd" clip-rule="evenodd"/></svg>';
                break;
        }

        return $svg;
    }
endif;

if (!function_exists('aqualuxe_the_svg')) :
    /**
     * Echo an SVG icon.
     *
     * @param string $icon The icon name.
     * @param array  $args Optional. Arguments to configure the SVG.
     */
    function aqualuxe_the_svg($icon, $args = array()) {
        echo aqualuxe_get_svg($icon, $args); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if (!function_exists('aqualuxe_breadcrumbs')) :
    /**
     * Display breadcrumbs for the current page.
     */
    function aqualuxe_breadcrumbs() {
        // Skip on the home page
        if (is_front_page()) {
            return;
        }

        echo '<nav class="breadcrumbs py-4" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
        echo '<ol class="flex flex-wrap items-center text-sm">';

        // Home page
        echo '<li class="breadcrumb-item">';
        echo '<a href="' . esc_url(home_url('/')) . '" class="hover:text-primary transition-colors">' . esc_html__('Home', 'aqualuxe') . '</a>';
        echo '</li>';
        echo '<li class="mx-2">/</li>';

        // Category, tag, author, date archives
        if (is_category() || is_tag() || is_author() || is_date()) {
            if (is_category()) {
                $title = single_cat_title('', false);
                echo '<li class="breadcrumb-item">' . esc_html($title) . '</li>';
            } elseif (is_tag()) {
                $title = single_tag_title('', false);
                echo '<li class="breadcrumb-item">' . esc_html($title) . '</li>';
            } elseif (is_author()) {
                $author = get_the_author();
                echo '<li class="breadcrumb-item">' . esc_html($author) . '</li>';
            } elseif (is_date()) {
                if (is_day()) {
                    echo '<li class="breadcrumb-item">' . esc_html(get_the_date()) . '</li>';
                } elseif (is_month()) {
                    echo '<li class="breadcrumb-item">' . esc_html(get_the_date('F Y')) . '</li>';
                } elseif (is_year()) {
                    echo '<li class="breadcrumb-item">' . esc_html(get_the_date('Y')) . '</li>';
                }
            }
        } elseif (is_search()) {
            // Search results
            echo '<li class="breadcrumb-item">' . esc_html__('Search Results', 'aqualuxe') . '</li>';
        } elseif (is_404()) {
            // 404 page
            echo '<li class="breadcrumb-item">' . esc_html__('Page Not Found', 'aqualuxe') . '</li>';
        } elseif (is_singular('post')) {
            // Single post
            $categories = get_the_category();
            if (!empty($categories)) {
                echo '<li class="breadcrumb-item">';
                echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="hover:text-primary transition-colors">' . esc_html($categories[0]->name) . '</a>';
                echo '</li>';
                echo '<li class="mx-2">/</li>';
            }
            echo '<li class="breadcrumb-item">' . esc_html(get_the_title()) . '</li>';
        } elseif (is_singular('page')) {
            // Single page
            $ancestors = get_post_ancestors(get_the_ID());
            if (!empty($ancestors)) {
                $ancestors = array_reverse($ancestors);
                foreach ($ancestors as $ancestor) {
                    echo '<li class="breadcrumb-item">';
                    echo '<a href="' . esc_url(get_permalink($ancestor)) . '" class="hover:text-primary transition-colors">' . esc_html(get_the_title($ancestor)) . '</a>';
                    echo '</li>';
                    echo '<li class="mx-2">/</li>';
                }
            }
            echo '<li class="breadcrumb-item">' . esc_html(get_the_title()) . '</li>';
        } elseif (is_singular()) {
            // Other singular
            $post_type = get_post_type();
            $post_type_object = get_post_type_object($post_type);
            if ($post_type_object) {
                echo '<li class="breadcrumb-item">';
                echo '<a href="' . esc_url(get_post_type_archive_link($post_type)) . '" class="hover:text-primary transition-colors">' . esc_html($post_type_object->labels->name) . '</a>';
                echo '</li>';
                echo '<li class="mx-2">/</li>';
            }
            echo '<li class="breadcrumb-item">' . esc_html(get_the_title()) . '</li>';
        } elseif (is_post_type_archive()) {
            // Post type archive
            $post_type = get_post_type();
            $post_type_object = get_post_type_object($post_type);
            if ($post_type_object) {
                echo '<li class="breadcrumb-item">' . esc_html($post_type_object->labels->name) . '</li>';
            }
        }

        echo '</ol>';
        echo '</nav>';
    }
endif;

if (!function_exists('aqualuxe_pagination')) :
    /**
     * Display pagination for archive pages.
     */
    function aqualuxe_pagination() {
        global $wp_query;

        if ($wp_query->max_num_pages <= 1) {
            return;
        }

        $big = 999999999; // need an unlikely integer

        $links = paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $wp_query->max_num_pages,
            'type' => 'array',
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
        ));

        if (!empty($links)) {
            echo '<nav class="pagination flex justify-center mt-8" aria-label="' . esc_attr__('Pagination', 'aqualuxe') . '">';
            echo '<ul class="flex flex-wrap">';

            foreach ($links as $link) {
                echo '<li class="mx-1">';
                echo str_replace('page-numbers', 'page-numbers px-3 py-2 border rounded inline-block hover:bg-primary hover:text-white transition-colors', $link);
                echo '</li>';
            }

            echo '</ul>';
            echo '</nav>';
        }
    }
endif;

if (!function_exists('aqualuxe_social_links')) :
    /**
     * Display social media links.
     */
    function aqualuxe_social_links() {
        $social_links = array(
            'facebook' => get_theme_mod('aqualuxe_facebook_url', '#'),
            'twitter' => get_theme_mod('aqualuxe_twitter_url', '#'),
            'instagram' => get_theme_mod('aqualuxe_instagram_url', '#'),
            'linkedin' => get_theme_mod('aqualuxe_linkedin_url', '#'),
            'youtube' => get_theme_mod('aqualuxe_youtube_url', '#'),
            'pinterest' => get_theme_mod('aqualuxe_pinterest_url', '#'),
        );

        echo '<div class="social-links flex space-x-4">';
        
        foreach ($social_links as $network => $url) {
            if (!empty($url) && $url !== '#') {
                echo '<a href="' . esc_url($url) . '" class="text-gray-600 hover:text-primary transition-colors" target="_blank" rel="noopener noreferrer">';
                echo '<span class="screen-reader-text">' . esc_html(ucfirst($network)) . '</span>';
                aqualuxe_the_svg($network, array('size' => '20'));
                echo '</a>';
            }
        }
        
        echo '</div>';
    }
endif;