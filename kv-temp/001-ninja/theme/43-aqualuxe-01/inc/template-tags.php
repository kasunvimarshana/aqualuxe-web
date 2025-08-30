<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 */

if ( ! function_exists( 'aqualuxe_posted_on' ) ) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function aqualuxe_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated hidden" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr( get_the_date( DATE_W3C ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( DATE_W3C ) ),
            esc_html( get_the_modified_date() )
        );

        $posted_on = sprintf(
            /* translators: %s: post date. */
            esc_html_x( 'Posted on %s', 'post date', 'aqualuxe' ),
            '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
        );

        echo '<span class="posted-on flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>' . $time_string . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'aqualuxe_posted_by' ) ) :
    /**
     * Prints HTML with meta information for the current author.
     */
    function aqualuxe_posted_by() {
        $byline = sprintf(
            /* translators: %s: post author. */
            esc_html_x( 'by %s', 'post author', 'aqualuxe' ),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
        );

        echo '<span class="byline flex items-center ml-4"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>' . esc_html( get_the_author() ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'aqualuxe_entry_footer' ) ) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function aqualuxe_entry_footer() {
        // Hide category and tag text for pages.
        if ( 'post' === get_post_type() ) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
            if ( $categories_list ) {
                /* translators: 1: list of categories. */
                printf( '<div class="cat-links mb-2"><span class="sr-only">%1$s</span>%2$s</div>', esc_html__( 'Posted in', 'aqualuxe' ), $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
            if ( $tags_list ) {
                /* translators: 1: list of tags. */
                printf( '<div class="tags-links flex flex-wrap gap-2 mt-4">%1$s</div>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }

        if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: post title */
                        __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post( get_the_title() )
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __( 'Edit <span class="screen-reader-text">%s</span>', 'aqualuxe' ),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post( get_the_title() )
            ),
            '<span class="edit-link mt-4 inline-block text-sm text-gray-500 dark:text-gray-400">',
            '</span>'
        );
    }
endif;

if ( ! function_exists( 'aqualuxe_post_thumbnail' ) ) :
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function aqualuxe_post_thumbnail() {
        if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
            return;
        }

        if ( is_singular() ) :
            ?>

            <div class="post-thumbnail mb-8">
                <?php the_post_thumbnail( 'full', array( 'class' => 'rounded-lg w-full h-auto' ) ); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="post-thumbnail block mb-4" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    'post-thumbnail',
                    array(
                        'class' => 'rounded-lg w-full h-auto',
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
endif;

if ( ! function_exists( 'aqualuxe_comment_count' ) ) :
    /**
     * Prints HTML with the comment count for the current post.
     */
    function aqualuxe_comment_count() {
        if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link flex items-center ml-4">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>';
            
            $comment_count = get_comments_number();
            if ( '1' === $comment_count ) {
                printf(
                    /* translators: 1: title. */
                    esc_html__( '1 Comment', 'aqualuxe' )
                );
            } else {
                printf(
                    /* translators: 1: comment count number. */
                    esc_html( _nx( '%1$s Comment', '%1$s Comments', $comment_count, 'comments title', 'aqualuxe' ) ),
                    number_format_i18n( $comment_count )
                );
            }
            
            echo '</span>';
        }
    }
endif;

if ( ! function_exists( 'aqualuxe_post_meta' ) ) :
    /**
     * Prints HTML with meta information for the current post.
     */
    function aqualuxe_post_meta() {
        // Hide meta information for pages.
        if ( 'post' !== get_post_type() ) {
            return;
        }

        echo '<div class="entry-meta flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400">';
        aqualuxe_posted_on();
        aqualuxe_posted_by();
        aqualuxe_comment_count();
        echo '</div>';
    }
endif;

if ( ! function_exists( 'aqualuxe_pagination' ) ) :
    /**
     * Display pagination.
     *
     * @param array $args Pagination arguments.
     */
    function aqualuxe_pagination( $args = array() ) {
        $defaults = array(
            'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg> ' . __( 'Previous', 'aqualuxe' ),
            'next_text' => __( 'Next', 'aqualuxe' ) . ' <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
            'class'     => 'pagination flex flex-wrap justify-center mt-8',
        );

        $args = wp_parse_args( $args, apply_filters( 'aqualuxe_pagination_args', $defaults ) );

        $links = paginate_links( array(
            'prev_text' => $args['prev_text'],
            'next_text' => $args['next_text'],
            'type'      => 'array',
        ) );

        if ( ! $links ) {
            return;
        }

        echo '<nav class="' . esc_attr( $args['class'] ) . '" aria-label="' . esc_attr__( 'Pagination', 'aqualuxe' ) . '">';
        echo '<ul class="pagination-list flex flex-wrap gap-2">';

        foreach ( $links as $link ) {
            $active_class = strpos( $link, 'current' ) !== false ? 'bg-primary text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700';
            $link = str_replace( 'page-numbers', 'page-numbers inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium ' . $active_class, $link );
            echo '<li class="pagination-item">' . $link . '</li>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }

        echo '</ul>';
        echo '</nav>';
    }
endif;

if ( ! function_exists( 'aqualuxe_post_navigation' ) ) :
    /**
     * Display post navigation.
     */
    function aqualuxe_post_navigation() {
        the_post_navigation(
            array(
                'prev_text' => '<div class="post-nav-label text-sm text-gray-600 dark:text-gray-400 mb-1">' . esc_html__( 'Previous Post', 'aqualuxe' ) . '</div><span class="post-nav-title font-medium">%title</span>',
                'next_text' => '<div class="post-nav-label text-sm text-gray-600 dark:text-gray-400 mb-1">' . esc_html__( 'Next Post', 'aqualuxe' ) . '</div><span class="post-nav-title font-medium">%title</span>',
            )
        );
    }
endif;

if ( ! function_exists( 'aqualuxe_breadcrumbs' ) ) :
    /**
     * Display breadcrumbs.
     */
    function aqualuxe_breadcrumbs() {
        // Check if breadcrumbs are enabled in customizer
        $show_breadcrumbs = get_theme_mod( 'aqualuxe_show_breadcrumbs', true );
        
        if ( ! $show_breadcrumbs ) {
            return;
        }

        // If Yoast SEO or Rank Math breadcrumbs are available, use those
        if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<div class="breadcrumbs py-3 text-sm">', '</div>' );
            return;
        }

        if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
            rank_math_the_breadcrumbs();
            return;
        }

        // Otherwise, use our custom breadcrumbs
        $home_text = __( 'Home', 'aqualuxe' );
        $separator = '<svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>';
        
        echo '<div class="breadcrumbs py-3 text-sm flex items-center flex-wrap">';
        
        // Home link
        echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="home-link flex items-center hover:text-primary">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>';
        echo esc_html( $home_text );
        echo '</a>';
        
        // Separator
        echo $separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        
        if ( is_category() || is_single() ) {
            if ( is_category() ) {
                echo '<span class="breadcrumb-current">' . single_cat_title( '', false ) . '</span>';
            } elseif ( is_single() ) {
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                    echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" class="hover:text-primary">' . esc_html( $categories[0]->name ) . '</a>';
                    echo $separator; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                echo '<span class="breadcrumb-current">' . get_the_title() . '</span>';
            }
        } elseif ( is_page() ) {
            echo '<span class="breadcrumb-current">' . get_the_title() . '</span>';
        } elseif ( is_search() ) {
            echo '<span class="breadcrumb-current">' . esc_html__( 'Search Results for: ', 'aqualuxe' ) . get_search_query() . '</span>';
        } elseif ( is_tag() ) {
            echo '<span class="breadcrumb-current">' . single_tag_title( '', false ) . '</span>';
        } elseif ( is_author() ) {
            echo '<span class="breadcrumb-current">' . get_the_author() . '</span>';
        } elseif ( is_archive() ) {
            if ( is_day() ) {
                echo '<span class="breadcrumb-current">' . get_the_date() . '</span>';
            } elseif ( is_month() ) {
                echo '<span class="breadcrumb-current">' . get_the_date( 'F Y' ) . '</span>';
            } elseif ( is_year() ) {
                echo '<span class="breadcrumb-current">' . get_the_date( 'Y' ) . '</span>';
            } else {
                echo '<span class="breadcrumb-current">' . esc_html__( 'Archives', 'aqualuxe' ) . '</span>';
            }
        }
        
        echo '</div>';
    }
endif;

if ( ! function_exists( 'aqualuxe_entry_title' ) ) :
    /**
     * Display the entry title.
     */
    function aqualuxe_entry_title() {
        if ( is_singular() ) :
            the_title( '<h1 class="entry-title text-3xl md:text-4xl font-bold mb-4">', '</h1>' );
        else :
            the_title( '<h2 class="entry-title text-2xl font-bold mb-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-primary">', '</a></h2>' );
        endif;
    }
endif;

if ( ! function_exists( 'aqualuxe_page_title' ) ) :
    /**
     * Display the page title.
     */
    function aqualuxe_page_title() {
        if ( is_home() && ! is_front_page() ) {
            $page_for_posts = get_option( 'page_for_posts' );
            echo '<h1 class="page-title text-3xl md:text-4xl font-bold mb-6">' . get_the_title( $page_for_posts ) . '</h1>';
        } elseif ( is_archive() ) {
            the_archive_title( '<h1 class="page-title text-3xl md:text-4xl font-bold mb-6">', '</h1>' );
            the_archive_description( '<div class="archive-description mb-6">', '</div>' );
        } elseif ( is_search() ) {
            echo '<h1 class="page-title text-3xl md:text-4xl font-bold mb-6">';
            /* translators: %s: search query. */
            printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
            echo '</h1>';
        } elseif ( is_404() ) {
            echo '<h1 class="page-title text-3xl md:text-4xl font-bold mb-6">' . esc_html__( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ) . '</h1>';
        }
    }
endif;

if ( ! function_exists( 'aqualuxe_site_branding' ) ) :
    /**
     * Display the site branding.
     */
    function aqualuxe_site_branding() {
        ?>
        <div class="site-branding flex items-center">
            <?php
            if ( has_custom_logo() ) :
                the_custom_logo();
            else :
                ?>
                <div class="site-title-wrapper">
                    <?php
                    if ( is_front_page() && is_home() ) :
                        ?>
                        <h1 class="site-title text-xl font-bold"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                        <?php
                    else :
                        ?>
                        <p class="site-title text-xl font-bold"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                        <?php
                    endif;
                    
                    $aqualuxe_description = get_bloginfo( 'description', 'display' );
                    if ( $aqualuxe_description || is_customize_preview() ) :
                        ?>
                        <p class="site-description text-sm text-gray-600 dark:text-gray-400"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div><!-- .site-branding -->
        <?php
    }
endif;

if ( ! function_exists( 'aqualuxe_social_links' ) ) :
    /**
     * Display social links.
     */
    function aqualuxe_social_links() {
        $social_links = array(
            'facebook'  => array(
                'label' => __( 'Facebook', 'aqualuxe' ),
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>',
            ),
            'twitter'   => array(
                'label' => __( 'Twitter', 'aqualuxe' ),
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>',
            ),
            'instagram' => array(
                'label' => __( 'Instagram', 'aqualuxe' ),
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
            ),
            'linkedin'  => array(
                'label' => __( 'LinkedIn', 'aqualuxe' ),
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>',
            ),
            'youtube'   => array(
                'label' => __( 'YouTube', 'aqualuxe' ),
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>',
            ),
            'pinterest' => array(
                'label' => __( 'Pinterest', 'aqualuxe' ),
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" fill-rule="evenodd" clip-rule="evenodd"/></svg>',
            ),
        );

        $output = '<div class="social-links flex space-x-4">';
        
        foreach ( $social_links as $network => $data ) {
            $url = get_theme_mod( 'aqualuxe_social_' . $network, '' );
            
            if ( ! empty( $url ) ) {
                $output .= sprintf(
                    '<a href="%1$s" class="social-link %2$s-link text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary" target="_blank" rel="noopener noreferrer" aria-label="%3$s">%4$s</a>',
                    esc_url( $url ),
                    esc_attr( $network ),
                    esc_attr( $data['label'] ),
                    $data['icon'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                );
            }
        }
        
        $output .= '</div>';
        
        echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'aqualuxe_footer_info' ) ) :
    /**
     * Display footer info.
     */
    function aqualuxe_footer_info() {
        $copyright_text = get_theme_mod( 'aqualuxe_copyright_text', '&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '. ' . __( 'All rights reserved.', 'aqualuxe' ) );
        
        if ( ! empty( $copyright_text ) ) {
            echo '<div class="footer-info">';
            echo wp_kses_post( $copyright_text );
            echo '</div>';
        }
    }
endif;

if ( ! function_exists( 'aqualuxe_footer_menu' ) ) :
    /**
     * Display footer menu.
     */
    function aqualuxe_footer_menu() {
        if ( has_nav_menu( 'footer' ) ) {
            wp_nav_menu(
                array(
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'container'      => false,
                    'menu_class'     => 'footer-menu flex flex-wrap',
                    'depth'          => 1,
                )
            );
        }
    }
endif;

if ( ! function_exists( 'aqualuxe_mobile_menu' ) ) :
    /**
     * Display mobile menu.
     */
    function aqualuxe_mobile_menu() {
        if ( has_nav_menu( 'mobile' ) ) {
            wp_nav_menu(
                array(
                    'theme_location' => 'mobile',
                    'menu_id'        => 'mobile-menu',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu',
                    'depth'          => 3,
                )
            );
        } else {
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'mobile-menu',
                    'container'      => false,
                    'menu_class'     => 'mobile-menu',
                    'depth'          => 3,
                )
            );
        }
    }
endif;

if ( ! function_exists( 'aqualuxe_get_svg' ) ) :
    /**
     * Get SVG icon.
     *
     * @param string $icon Icon name.
     * @return string
     */
    function aqualuxe_get_svg( $icon ) {
        $icons = array(
            'search' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>',
            'menu'   => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>',
            'close'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>',
            'cart'   => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>',
            'user'   => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
            'heart'  => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>',
            'sun'    => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>',
            'moon'   => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>',
        );

        if ( isset( $icons[ $icon ] ) ) {
            return $icons[ $icon ];
        }

        return '';
    }
endif;