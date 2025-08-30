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
                <?php the_post_thumbnail('aqualuxe-featured', array(
                    'alt' => the_title_attribute(array(
                        'echo' => false,
                    )),
                    'class' => 'featured-image',
                )); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    'post-thumbnail',
                    array(
                        'alt' => the_title_attribute(array(
                            'echo' => false,
                        )),
                        'class' => 'post-thumbnail-image',
                    )
                );
                ?>
            </a>

            <?php
        endif; // End is_singular().
    }
endif;

if (!function_exists('aqualuxe_entry_meta')) :
    /**
     * Prints HTML with meta information for the current post.
     */
    function aqualuxe_entry_meta() {
        // Posted on
        aqualuxe_posted_on();
        
        // Posted by
        aqualuxe_posted_by();
        
        // Comments link
        if (!post_password_required() && (comments_open() || get_comments_number())) {
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
        
        // Edit link
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

if (!function_exists('aqualuxe_post_categories')) :
    /**
     * Prints HTML with the categories of the current post.
     */
    function aqualuxe_post_categories() {
        /* translators: used between list items, there is a space after the comma */
        $categories_list = get_the_category_list(esc_html__(', ', 'aqualuxe'));
        if ($categories_list) {
            /* translators: 1: list of categories. */
            printf('<div class="cat-links">' . esc_html__('Categories: %1$s', 'aqualuxe') . '</div>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }
endif;

if (!function_exists('aqualuxe_post_tags')) :
    /**
     * Prints HTML with the tags of the current post.
     */
    function aqualuxe_post_tags() {
        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'aqualuxe'));
        if ($tags_list) {
            /* translators: 1: list of tags. */
            printf('<div class="tags-links">' . esc_html__('Tags: %1$s', 'aqualuxe') . '</div>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }
endif;

if (!function_exists('aqualuxe_comment_avatar')) :
    /**
     * Returns the HTML for a comment avatar.
     */
    function aqualuxe_comment_avatar($comment) {
        $avatar_size = 60;
        if ('0' === $comment->comment_parent) {
            $avatar_size = 100;
        }
        
        return get_avatar($comment, $avatar_size);
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
        
        switch ($comment->comment_type) :
            case 'pingback':
            case 'trackback':
                ?>
                <li class="post pingback">
                    <p><?php esc_html_e('Pingback:', 'aqualuxe'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(esc_html__('Edit', 'aqualuxe'), '<span class="edit-link">', '</span>'); ?></p>
                <?php
                break;
            default:
                ?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                    <article id="comment-<?php comment_ID(); ?>" class="comment-body">
                        <footer class="comment-meta">
                            <div class="comment-author vcard">
                                <?php echo aqualuxe_comment_avatar($comment); ?>
                                <?php printf('<cite class="fn">%s</cite>', get_comment_author_link()); ?>
                            </div><!-- .comment-author -->

                            <div class="comment-metadata">
                                <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                                    <time datetime="<?php comment_time('c'); ?>">
                                        <?php printf(esc_html_x('%1$s at %2$s', '1: date, 2: time', 'aqualuxe'), get_comment_date(), get_comment_time()); ?>
                                    </time>
                                </a>
                                <?php edit_comment_link(esc_html__('Edit', 'aqualuxe'), '<span class="edit-link">', '</span>'); ?>
                            </div><!-- .comment-metadata -->

                            <?php if ('0' === $comment->comment_approved) : ?>
                                <p class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'aqualuxe'); ?></p>
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
                                        'add_below' => 'comment',
                                        'depth'     => $depth,
                                        'max_depth' => $args['max_depth'],
                                    )
                                )
                            );
                            ?>
                        </div><!-- .reply -->
                    </article><!-- .comment-body -->
                <?php
                break;
        endswitch;
    }
endif;

if (!function_exists('aqualuxe_post_navigation')) :
    /**
     * Display navigation to next/previous post when applicable.
     */
    function aqualuxe_post_navigation() {
        the_post_navigation(
            array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
            )
        );
    }
endif;

if (!function_exists('aqualuxe_posts_pagination')) :
    /**
     * Display pagination for archive pages.
     */
    function aqualuxe_posts_pagination() {
        the_posts_pagination(
            array(
                'mid_size'  => 2,
                'prev_text' => sprintf(
                    '%s <span class="nav-prev-text">%s</span>',
                    '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    esc_html__('Newer posts', 'aqualuxe')
                ),
                'next_text' => sprintf(
                    '<span class="nav-next-text">%s</span> %s',
                    esc_html__('Older posts', 'aqualuxe'),
                    '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                ),
            )
        );
    }
endif;

if (!function_exists('aqualuxe_breadcrumbs')) :
    /**
     * Display breadcrumbs.
     */
    function aqualuxe_breadcrumbs() {
        // Check if breadcrumbs are enabled in the customizer
        if (!get_theme_mod('aqualuxe_enable_breadcrumbs', true)) {
            return;
        }
        
        // Home page
        echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
        echo '<span class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'aqualuxe') . '</a></span>';
        
        if (is_category() || is_single()) {
            echo '<span class="breadcrumb-separator">/</span>';
            
            // If single post, display category first
            if (is_single()) {
                $categories = get_the_category();
                if (!empty($categories)) {
                    echo '<span class="breadcrumb-item"><a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a></span>';
                    echo '<span class="breadcrumb-separator">/</span>';
                }
                
                // Then display post title
                echo '<span class="breadcrumb-item current">' . get_the_title() . '</span>';
            } else {
                // Category archive
                echo '<span class="breadcrumb-item current">' . single_cat_title('', false) . '</span>';
            }
        } elseif (is_page()) {
            echo '<span class="breadcrumb-separator">/</span>';
            
            // Check if the page has a parent
            if ($post->post_parent) {
                $ancestors = get_post_ancestors($post->ID);
                $ancestors = array_reverse($ancestors);
                
                foreach ($ancestors as $ancestor) {
                    echo '<span class="breadcrumb-item"><a href="' . esc_url(get_permalink($ancestor)) . '">' . get_the_title($ancestor) . '</a></span>';
                    echo '<span class="breadcrumb-separator">/</span>';
                }
            }
            
            // Current page
            echo '<span class="breadcrumb-item current">' . get_the_title() . '</span>';
        } elseif (is_tag()) {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . single_tag_title('', false) . '</span>';
        } elseif (is_author()) {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . get_the_author() . '</span>';
        } elseif (is_year()) {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . get_the_date('Y') . '</span>';
        } elseif (is_month()) {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . get_the_date('F Y') . '</span>';
        } elseif (is_day()) {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . get_the_date() . '</span>';
        } elseif (is_search()) {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . esc_html__('Search results for', 'aqualuxe') . ' "' . get_search_query() . '"</span>';
        } elseif (is_404()) {
            echo '<span class="breadcrumb-separator">/</span>';
            echo '<span class="breadcrumb-item current">' . esc_html__('Page not found', 'aqualuxe') . '</span>';
        }
        
        // WooCommerce support
        if (class_exists('WooCommerce') && is_woocommerce()) {
            if (is_shop()) {
                echo '<span class="breadcrumb-separator">/</span>';
                echo '<span class="breadcrumb-item current">' . esc_html__('Shop', 'aqualuxe') . '</span>';
            } elseif (is_product_category() || is_product_tag()) {
                echo '<span class="breadcrumb-separator">/</span>';
                echo '<span class="breadcrumb-item"><a href="' . esc_url(wc_get_page_permalink('shop')) . '">' . esc_html__('Shop', 'aqualuxe') . '</a></span>';
                echo '<span class="breadcrumb-separator">/</span>';
                echo '<span class="breadcrumb-item current">' . single_term_title('', false) . '</span>';
            } elseif (is_product()) {
                echo '<span class="breadcrumb-separator">/</span>';
                echo '<span class="breadcrumb-item"><a href="' . esc_url(wc_get_page_permalink('shop')) . '">' . esc_html__('Shop', 'aqualuxe') . '</a></span>';
                
                // Get product categories
                $terms = get_the_terms(get_the_ID(), 'product_cat');
                if (!empty($terms)) {
                    echo '<span class="breadcrumb-separator">/</span>';
                    echo '<span class="breadcrumb-item"><a href="' . esc_url(get_term_link($terms[0])) . '">' . esc_html($terms[0]->name) . '</a></span>';
                }
                
                echo '<span class="breadcrumb-separator">/</span>';
                echo '<span class="breadcrumb-item current">' . get_the_title() . '</span>';
            }
        }
        
        echo '</nav>';
    }
endif;

if (!function_exists('aqualuxe_social_links')) :
    /**
     * Display social links.
     */
    function aqualuxe_social_links() {
        $social_links = array(
            'facebook' => array(
                'label' => esc_html__('Facebook', 'aqualuxe'),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>',
            ),
            'twitter' => array(
                'label' => esc_html__('Twitter', 'aqualuxe'),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>',
            ),
            'instagram' => array(
                'label' => esc_html__('Instagram', 'aqualuxe'),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>',
            ),
            'linkedin' => array(
                'label' => esc_html__('LinkedIn', 'aqualuxe'),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-linkedin"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>',
            ),
            'youtube' => array(
                'label' => esc_html__('YouTube', 'aqualuxe'),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-youtube"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>',
            ),
            'pinterest' => array(
                'label' => esc_html__('Pinterest', 'aqualuxe'),
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pinterest"><path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path></svg>',
            ),
        );
        
        echo '<div class="social-links">';
        
        foreach ($social_links as $network => $data) {
            $url = get_theme_mod('aqualuxe_social_' . $network, '');
            
            if ($url) {
                echo '<a href="' . esc_url($url) . '" class="social-link ' . esc_attr($network) . '" target="_blank" rel="noopener noreferrer">';
                echo '<span class="screen-reader-text">' . $data['label'] . '</span>';
                echo $data['icon'];
                echo '</a>';
            }
        }
        
        echo '</div>';
    }
endif;

if (!function_exists('aqualuxe_dark_mode_toggle')) :
    /**
     * Display dark mode toggle.
     */
    function aqualuxe_dark_mode_toggle() {
        // Check if dark mode is enabled in the customizer
        if (!get_theme_mod('aqualuxe_enable_dark_mode', true)) {
            return;
        }
        
        echo '<button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="' . esc_attr__('Toggle dark mode', 'aqualuxe') . '">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun light-icon"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon dark-icon"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>';
        echo '</button>';
    }
endif;

if (!function_exists('aqualuxe_language_switcher')) :
    /**
     * Display language switcher.
     */
    function aqualuxe_language_switcher() {
        // Check if multilingual support is enabled in the customizer
        if (!get_theme_mod('aqualuxe_enable_multilingual', true)) {
            return;
        }
        
        // Check if WPML is active
        if (function_exists('icl_get_languages')) {
            $languages = icl_get_languages('skip_missing=0&orderby=code');
            
            if (!empty($languages)) {
                echo '<div class="language-switcher wpml-switcher">';
                echo '<ul>';
                
                foreach ($languages as $language) {
                    $class = $language['active'] ? 'active' : '';
                    echo '<li class="' . esc_attr($class) . '">';
                    echo '<a href="' . esc_url($language['url']) . '">';
                    
                    if ($language['country_flag_url']) {
                        echo '<img src="' . esc_url($language['country_flag_url']) . '" alt="' . esc_attr($language['language_code']) . '" width="18" height="12" />';
                    }
                    
                    echo esc_html($language['native_name']);
                    echo '</a>';
                    echo '</li>';
                }
                
                echo '</ul>';
                echo '</div>';
            }
        }
        // Check if Polylang is active
        elseif (function_exists('pll_the_languages')) {
            echo '<div class="language-switcher polylang-switcher">';
            pll_the_languages(array(
                'show_flags' => 1,
                'show_names' => 1,
                'dropdown' => 0,
            ));
            echo '</div>';
        }
    }
endif;