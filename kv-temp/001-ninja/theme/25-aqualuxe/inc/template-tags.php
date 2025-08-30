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
                <?php the_post_thumbnail('full', array('class' => 'rounded-lg w-full h-auto')); ?>
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
                        'class' => 'rounded-lg w-full h-auto',
                        'loading' => 'lazy',
                    )
                );
                ?>
            </a>

            <?php
        endif; // End is_singular().
    }
endif;

if (!function_exists('aqualuxe_the_breadcrumb')) :
    /**
     * Display breadcrumbs for posts and pages
     */
    function aqualuxe_the_breadcrumb() {
        // Skip on the home/front page
        if (is_front_page()) {
            return;
        }

        echo '<nav class="breadcrumb py-2 text-sm" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
        echo '<ol class="flex flex-wrap items-center space-x-2">';
        
        // Home link
        echo '<li><a href="' . esc_url(home_url('/')) . '" rel="home">' . esc_html__('Home', 'aqualuxe') . '</a></li>';
        echo '<li class="separator mx-1">/</li>';

        if (is_category() || is_single()) {
            if (is_single()) {
                // Get categories
                $categories = get_the_category();
                if (!empty($categories)) {
                    echo '<li><a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a></li>';
                    echo '<li class="separator mx-1">/</li>';
                }
                // The post title
                echo '<li class="current" aria-current="page">' . esc_html(get_the_title()) . '</li>';
            } else {
                // Just display the category name
                echo '<li class="current" aria-current="page">' . esc_html(single_cat_title('', false)) . '</li>';
            }
        } elseif (is_page()) {
            // Check if the page has a parent
            if ($post->post_parent) {
                $ancestors = get_post_ancestors($post->ID);
                $ancestors = array_reverse($ancestors);
                
                foreach ($ancestors as $ancestor) {
                    echo '<li><a href="' . esc_url(get_permalink($ancestor)) . '">' . esc_html(get_the_title($ancestor)) . '</a></li>';
                    echo '<li class="separator mx-1">/</li>';
                }
            }
            // Current page
            echo '<li class="current" aria-current="page">' . esc_html(get_the_title()) . '</li>';
        } elseif (is_tag()) {
            // Tag archive
            echo '<li class="current" aria-current="page">' . esc_html(single_tag_title('', false)) . '</li>';
        } elseif (is_author()) {
            // Author archive
            echo '<li class="current" aria-current="page">' . esc_html(get_the_author()) . '</li>';
        } elseif (is_year()) {
            // Year archive
            echo '<li class="current" aria-current="page">' . esc_html(get_the_date('Y')) . '</li>';
        } elseif (is_month()) {
            // Month archive
            echo '<li class="current" aria-current="page">' . esc_html(get_the_date('F Y')) . '</li>';
        } elseif (is_day()) {
            // Day archive
            echo '<li class="current" aria-current="page">' . esc_html(get_the_date('F j, Y')) . '</li>';
        } elseif (is_post_type_archive()) {
            // Post type archive
            echo '<li class="current" aria-current="page">' . esc_html(post_type_archive_title('', false)) . '</li>';
        } elseif (is_tax()) {
            // Taxonomy archive
            $term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
            echo '<li class="current" aria-current="page">' . esc_html($term->name) . '</li>';
        } elseif (is_search()) {
            // Search results
            echo '<li class="current" aria-current="page">' . esc_html__('Search results for: ', 'aqualuxe') . esc_html(get_search_query()) . '</li>';
        } elseif (is_404()) {
            // 404 page
            echo '<li class="current" aria-current="page">' . esc_html__('Page not found', 'aqualuxe') . '</li>';
        }

        echo '</ol>';
        echo '</nav>';
    }
endif;

if (!function_exists('aqualuxe_pagination')) :
    /**
     * Display pagination for archive pages
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
            'prev_text' => '<span aria-hidden="true">&laquo;</span><span class="sr-only">' . __('Previous', 'aqualuxe') . '</span>',
            'next_text' => '<span aria-hidden="true">&raquo;</span><span class="sr-only">' . __('Next', 'aqualuxe') . '</span>',
            'type' => 'array',
            'end_size' => 3,
            'mid_size' => 3,
        ));
        
        if (!empty($links)) {
            echo '<nav class="pagination" aria-label="' . esc_attr__('Pagination', 'aqualuxe') . '">';
            echo '<ul class="flex flex-wrap justify-center space-x-1">';
            
            foreach ($links as $link) {
                // Add classes to the pagination links
                $link = str_replace('page-numbers', 'page-numbers px-4 py-2 border rounded', $link);
                $link = str_replace('current', 'current bg-primary text-white', $link);
                
                echo '<li>' . $link . '</li>';
            }
            
            echo '</ul>';
            echo '</nav>';
        }
    }
endif;

if (!function_exists('aqualuxe_social_links')) :
    /**
     * Display social media links
     */
    function aqualuxe_social_links() {
        $social_links = array(
            'facebook' => array(
                'label' => __('Facebook', 'aqualuxe'),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
                'url' => get_theme_mod('aqualuxe_facebook_url', ''),
            ),
            'twitter' => array(
                'label' => __('Twitter', 'aqualuxe'),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>',
                'url' => get_theme_mod('aqualuxe_twitter_url', ''),
            ),
            'instagram' => array(
                'label' => __('Instagram', 'aqualuxe'),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>',
                'url' => get_theme_mod('aqualuxe_instagram_url', ''),
            ),
            'youtube' => array(
                'label' => __('YouTube', 'aqualuxe'),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
                'url' => get_theme_mod('aqualuxe_youtube_url', ''),
            ),
            'pinterest' => array(
                'label' => __('Pinterest', 'aqualuxe'),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>',
                'url' => get_theme_mod('aqualuxe_pinterest_url', ''),
            ),
            'linkedin' => array(
                'label' => __('LinkedIn', 'aqualuxe'),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="fill-current"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
                'url' => get_theme_mod('aqualuxe_linkedin_url', ''),
            ),
        );
        
        echo '<div class="social-links flex space-x-4">';
        
        foreach ($social_links as $network => $data) {
            if (!empty($data['url'])) {
                echo '<a href="' . esc_url($data['url']) . '" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-primary transition-colors duration-300" aria-label="' . esc_attr($data['label']) . '">';
                echo $data['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '</a>';
            }
        }
        
        echo '</div>';
    }
endif;

if (!function_exists('aqualuxe_dark_mode_toggle')) :
    /**
     * Display dark mode toggle button
     */
    function aqualuxe_dark_mode_toggle() {
        ?>
        <button id="dark-mode-toggle" class="dark-mode-toggle p-2 rounded-full focus:outline-none" aria-label="<?php esc_attr_e('Toggle dark mode', 'aqualuxe'); ?>">
            <!-- Sun icon for dark mode (show when in dark mode) -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 dark:block hidden" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
            </svg>
            <!-- Moon icon for light mode (show when in light mode) -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 dark:hidden block" viewBox="0 0 20 20" fill="currentColor">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
            </svg>
        </button>
        <?php
    }
endif;

if (!function_exists('aqualuxe_language_switcher')) :
    /**
     * Display language switcher if WPML or Polylang is active
     */
    function aqualuxe_language_switcher() {
        // Check if WPML is active
        if (function_exists('icl_get_languages')) {
            $languages = icl_get_languages('skip_missing=0&orderby=code');
            
            if (!empty($languages)) {
                echo '<div class="language-switcher">';
                echo '<div class="current-language flex items-center cursor-pointer">';
                echo '<span>' . esc_html(ICL_LANGUAGE_NAME) . '</span>';
                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>';
                echo '</div>';
                echo '<ul class="language-dropdown absolute hidden bg-white shadow-md rounded-md mt-1 py-1 z-50">';
                
                foreach ($languages as $lang) {
                    $class = $lang['active'] ? 'active' : '';
                    echo '<li class="' . esc_attr($class) . '">';
                    echo '<a href="' . esc_url($lang['url']) . '" class="block px-4 py-2 hover:bg-gray-100">';
                    echo esc_html($lang['native_name']);
                    echo '</a>';
                    echo '</li>';
                }
                
                echo '</ul>';
                echo '</div>';
            }
        }
        
        // Check if Polylang is active
        if (function_exists('pll_the_languages')) {
            $args = array(
                'dropdown' => 0,
                'show_names' => 1,
                'display_names_as' => 'name',
                'hide_if_empty' => 0,
            );
            
            echo '<div class="language-switcher">';
            echo '<div class="current-language flex items-center cursor-pointer">';
            echo '<span>' . esc_html(pll_current_language('name')) . '</span>';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>';
            echo '</div>';
            echo '<ul class="language-dropdown absolute hidden bg-white shadow-md rounded-md mt-1 py-1 z-50">';
            pll_the_languages($args);
            echo '</ul>';
            echo '</div>';
        }
    }
endif;