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
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
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

        echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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

        echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
                printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'aqualuxe' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
            if ( $tags_list ) {
                /* translators: 1: list of tags. */
                printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'aqualuxe' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
            '<span class="edit-link">',
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

            <div class="post-thumbnail">
                <?php the_post_thumbnail( 'full', array( 'class' => 'rounded-lg' ) ); ?>
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
                        'class' => 'rounded-lg',
                    )
                );
                ?>
            </a>

            <?php
        endif; // End is_singular().
    }
endif;

if ( ! function_exists( 'aqualuxe_comment_callback' ) ) :
    /**
     * Template for comments and pingbacks.
     *
     * Used as a callback by wp_list_comments() for displaying the comments.
     *
     * @param object $comment Comment to display.
     * @param array  $args    Arguments passed to wp_list_comments().
     * @param int    $depth   Depth of the current comment.
     */
    function aqualuxe_comment_callback( $comment, $args, $depth ) {
        $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
        ?>
        <<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent', $comment ); ?>>
            <article id="div-comment-<?php comment_ID(); ?>" class="comment-body bg-gray-50 dark:bg-gray-800 p-6 rounded-lg mb-6">
                <div class="comment-meta mb-4">
                    <div class="flex items-center">
                        <div class="comment-author vcard mr-4">
                            <?php
                            if ( 0 !== $args['avatar_size'] ) {
                                echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-full' ) );
                            }
                            ?>
                        </div><!-- .comment-author -->

                        <div>
                            <?php
                            printf(
                                '<h4 class="fn font-bold">%s</h4>',
                                get_comment_author_link( $comment )
                            );
                            ?>
                            <div class="comment-metadata text-sm text-gray-600 dark:text-gray-400">
                                <a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
                                    <time datetime="<?php comment_time( 'c' ); ?>">
                                        <?php
                                        /* translators: 1: comment date, 2: comment time */
                                        printf( esc_html__( '%1$s at %2$s', 'aqualuxe' ), esc_html( get_comment_date( '', $comment ) ), esc_html( get_comment_time() ) );
                                        ?>
                                    </time>
                                </a>
                                <?php edit_comment_link( esc_html__( 'Edit', 'aqualuxe' ), '<span class="edit-link ml-2">', '</span>' ); ?>
                            </div><!-- .comment-metadata -->
                        </div>
                    </div>

                    <?php if ( '0' === $comment->comment_approved ) : ?>
                    <p class="comment-awaiting-moderation mt-2 text-yellow-600 dark:text-yellow-400"><?php esc_html_e( 'Your comment is awaiting moderation.', 'aqualuxe' ); ?></p>
                    <?php endif; ?>
                </div><!-- .comment-meta -->

                <div class="comment-content prose dark:prose-invert max-w-none">
                    <?php comment_text(); ?>
                </div><!-- .comment-content -->

                <?php
                comment_reply_link(
                    array_merge(
                        $args,
                        array(
                            'add_below' => 'div-comment',
                            'depth'     => $depth,
                            'max_depth' => $args['max_depth'],
                            'before'    => '<div class="reply mt-4">',
                            'after'     => '</div>',
                        )
                    )
                );
                ?>
            </article><!-- .comment-body -->
        <?php
    }
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
    /**
     * Shim for sites older than 5.2.
     *
     * @link https://core.trac.wordpress.org/ticket/12563
     */
    function wp_body_open() {
        do_action( 'wp_body_open' );
    }
endif;

if ( ! function_exists( 'aqualuxe_get_social_links' ) ) :
    /**
     * Get social links.
     *
     * @return array
     */
    function aqualuxe_get_social_links() {
        $social_links = array(
            'facebook'  => array(
                'label' => esc_html__( 'Facebook', 'aqualuxe' ),
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>',
            ),
            'twitter'   => array(
                'label' => esc_html__( 'Twitter', 'aqualuxe' ),
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M22.162 5.656a8.384 8.384 0 0 1-2.402.658A4.196 4.196 0 0 0 21.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 0 0-7.126 3.814 11.874 11.874 0 0 1-8.62-4.37 4.168 4.168 0 0 0-.566 2.103c0 1.45.738 2.731 1.86 3.481a4.168 4.168 0 0 1-1.894-.523v.052a4.185 4.185 0 0 0 3.355 4.101 4.21 4.21 0 0 1-1.89.072A4.185 4.185 0 0 0 7.97 16.65a8.394 8.394 0 0 1-6.191 1.732 11.83 11.83 0 0 0 6.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 0 0 2.087-2.165z"/></svg>',
            ),
            'instagram' => array(
                'label' => esc_html__( 'Instagram', 'aqualuxe' ),
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153a4.908 4.908 0 0 1 1.153 1.772c.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 0 1-1.153 1.772 4.915 4.915 0 0 1-1.772 1.153c-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 0 1-1.772-1.153 4.904 4.904 0 0 1-1.153-1.772c-.248-.637-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 0 1 1.153-1.772A4.897 4.897 0 0 1 5.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2zm0 1.802c-2.67 0-2.986.01-4.04.059-.976.045-1.505.207-1.858.344-.466.182-.8.398-1.15.748-.35.35-.566.684-.748 1.15-.137.353-.3.882-.344 1.857-.048 1.055-.058 1.37-.058 4.041 0 2.67.01 2.986.058 4.04.045.977.207 1.505.344 1.858.182.466.399.8.748 1.15.35.35.684.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058 2.67 0 2.987-.01 4.04-.058.977-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.684.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041 0-2.67-.01-2.986-.058-4.04-.045-.977-.207-1.505-.344-1.858a3.097 3.097 0 0 0-.748-1.15 3.098 3.098 0 0 0-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.055-.048-1.37-.058-4.041-.058zm0 3.063a5.135 5.135 0 1 1 0 10.27 5.135 5.135 0 0 1 0-10.27zm0 1.802a3.333 3.333 0 1 0 0 6.666 3.333 3.333 0 0 0 0-6.666zm6.538-3.11a1.2 1.2 0 1 1-2.4 0 1.2 1.2 0 0 1 2.4 0z"/></svg>',
            ),
            'linkedin'  => array(
                'label' => esc_html__( 'LinkedIn', 'aqualuxe' ),
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M6.94 5a2 2 0 1 1-4-.002 2 2 0 0 1 4 .002zM7 8.48H3V21h4V8.48zm6.32 0H9.34V21h3.94v-6.57c0-3.66 4.77-4 4.77 0V21H22v-7.93c0-6.17-7.06-5.94-8.72-2.91l.04-1.68z"/></svg>',
            ),
            'youtube'   => array(
                'label' => esc_html__( 'YouTube', 'aqualuxe' ),
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M21.543 6.498C22 8.28 22 12 22 12s0 3.72-.457 5.502c-.254.985-.997 1.76-1.938 2.022C17.896 20 12 20 12 20s-5.893 0-7.605-.476c-.945-.266-1.687-1.04-1.938-2.022C2 15.72 2 12 2 12s0-3.72.457-5.502c.254-.985.997-1.76 1.938-2.022C6.107 4 12 4 12 4s5.896 0 7.605.476c.945.266 1.687 1.04 1.938 2.022zM10 15.5l6-3.5-6-3.5v7z"/></svg>',
            ),
            'pinterest' => array(
                'label' => esc_html__( 'Pinterest', 'aqualuxe' ),
                'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.37 2.094A10.003 10.003 0 0 0 8.002 21.17a7.757 7.757 0 0 1 .163-2.293c.185-.839 1.296-5.463 1.296-5.463a3.739 3.739 0 0 1-.324-1.577c0-1.485.857-2.593 1.923-2.593a1.334 1.334 0 0 1 1.342 1.508c0 .9-.578 2.262-.88 3.54a1.544 1.544 0 0 0 1.575 1.923c1.898 0 3.17-2.431 3.17-5.301 0-2.2-1.457-3.848-4.143-3.848a4.746 4.746 0 0 0-4.93 4.794 2.96 2.96 0 0 0 .648 1.97.48.48 0 0 1 .162.554c-.46.184-.162.623-.208.784a.354.354 0 0 1-.51.254c-1.384-.554-2.036-2.077-2.036-3.816 0-2.847 2.384-6.255 7.154-6.255 3.796 0 6.32 2.777 6.32 5.747 0 3.909-2.177 6.848-5.394 6.848a2.861 2.861 0 0 1-2.454-1.246s-.578 2.316-.692 2.754a8.026 8.026 0 0 1-1.019 2.131c.923.28 1.882.42 2.846.416a9.988 9.988 0 0 0 9.996-10.003 10.002 10.002 0 0 0-8.635-9.903z"/></svg>',
            ),
        );

        return apply_filters( 'aqualuxe_social_links', $social_links );
    }
endif;

if ( ! function_exists( 'aqualuxe_get_social_link_url' ) ) :
    /**
     * Get social link URL.
     *
     * @param string $network Social network.
     * @return string
     */
    function aqualuxe_get_social_link_url( $network ) {
        $url = '';

        switch ( $network ) {
            case 'facebook':
                $url = get_theme_mod( 'facebook_url', '' );
                break;
            case 'twitter':
                $url = get_theme_mod( 'twitter_url', '' );
                break;
            case 'instagram':
                $url = get_theme_mod( 'instagram_url', '' );
                break;
            case 'linkedin':
                $url = get_theme_mod( 'linkedin_url', '' );
                break;
            case 'youtube':
                $url = get_theme_mod( 'youtube_url', '' );
                break;
            case 'pinterest':
                $url = get_theme_mod( 'pinterest_url', '' );
                break;
        }

        return $url;
    }
endif;

if ( ! function_exists( 'aqualuxe_social_links' ) ) :
    /**
     * Display social links.
     *
     * @param array $args Arguments.
     */
    function aqualuxe_social_links( $args = array() ) {
        $defaults = array(
            'container'       => 'div',
            'container_class' => 'social-links',
            'link_class'      => 'social-link',
            'icon_class'      => 'social-icon',
            'networks'        => array( 'facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'pinterest' ),
        );

        $args = wp_parse_args( $args, $defaults );

        $social_links = aqualuxe_get_social_links();
        $output       = '';

        foreach ( $args['networks'] as $network ) {
            if ( ! isset( $social_links[ $network ] ) ) {
                continue;
            }

            $url = aqualuxe_get_social_link_url( $network );

            if ( ! $url ) {
                continue;
            }

            $output .= sprintf(
                '<a href="%1$s" class="%2$s %2$s-%3$s" target="_blank" rel="noopener noreferrer">
                    <span class="%4$s">%5$s</span>
                    <span class="screen-reader-text">%6$s</span>
                </a>',
                esc_url( $url ),
                esc_attr( $args['link_class'] ),
                esc_attr( $network ),
                esc_attr( $args['icon_class'] ),
                $social_links[ $network ]['icon'],
                esc_html( $social_links[ $network ]['label'] )
            );
        }

        if ( ! $output ) {
            return;
        }

        printf(
            '<%1$s class="%2$s">%3$s</%1$s>',
            esc_html( $args['container'] ),
            esc_attr( $args['container_class'] ),
            $output // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        );
    }
endif;

if ( ! function_exists( 'aqualuxe_get_related_posts' ) ) :
    /**
     * Get related posts.
     *
     * @param int   $post_id Post ID.
     * @param int   $number  Number of posts.
     * @param array $args    Arguments.
     * @return WP_Query
     */
    function aqualuxe_get_related_posts( $post_id, $number = 3, $args = array() ) {
        $defaults = array(
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => $number,
            'post__not_in'   => array( $post_id ),
            'orderby'        => 'rand',
        );

        $args = wp_parse_args( $args, $defaults );

        // Get post categories.
        $categories = get_the_category( $post_id );
        if ( $categories ) {
            $category_ids = array();
            foreach ( $categories as $category ) {
                $category_ids[] = $category->term_id;
            }
            $args['category__in'] = $category_ids;
        }

        // Get post tags.
        $tags = get_the_tags( $post_id );
        if ( $tags ) {
            $tag_ids = array();
            foreach ( $tags as $tag ) {
                $tag_ids[] = $tag->term_id;
            }
            $args['tag__in'] = $tag_ids;
        }

        return new WP_Query( $args );
    }
endif;

if ( ! function_exists( 'aqualuxe_pagination' ) ) :
    /**
     * Display pagination.
     *
     * @param array $args Arguments.
     */
    function aqualuxe_pagination( $args = array() ) {
        $defaults = array(
            'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10.828 12l4.95 4.95-1.414 1.414L8 12l6.364-6.364 1.414 1.414z"/></svg>' . esc_html__( 'Previous', 'aqualuxe' ),
            'next_text' => esc_html__( 'Next', 'aqualuxe' ) . '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg>',
            'class'     => 'pagination',
        );

        $args = wp_parse_args( $args, $defaults );

        the_posts_pagination( $args );
    }
endif;

if ( ! function_exists( 'aqualuxe_breadcrumbs' ) ) :
    /**
     * Display breadcrumbs.
     *
     * @param array $args Arguments.
     */
    function aqualuxe_breadcrumbs( $args = array() ) {
        $defaults = array(
            'container'     => 'nav',
            'container_id'  => 'breadcrumbs',
            'container_class' => 'breadcrumbs',
            'item_class'    => 'breadcrumb-item',
            'separator'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg>',
            'home_text'     => esc_html__( 'Home', 'aqualuxe' ),
            'show_on_home'  => false,
            'show_on_404'   => false,
            'show_current'  => true,
            'before_current' => '<span class="current">',
            'after_current' => '</span>',
        );

        $args = wp_parse_args( $args, $defaults );

        // Return if on front page.
        if ( is_front_page() && ! $args['show_on_home'] ) {
            return;
        }

        // Return if on 404 page.
        if ( is_404() && ! $args['show_on_404'] ) {
            return;
        }

        $output = '';

        // Home link.
        $output .= sprintf(
            '<li class="%1$s"><a href="%2$s">%3$s</a></li>',
            esc_attr( $args['item_class'] ),
            esc_url( home_url( '/' ) ),
            esc_html( $args['home_text'] )
        );

        if ( is_home() && ! is_front_page() ) {
            // Blog page.
            $blog_page_id = get_option( 'page_for_posts' );
            $output      .= sprintf(
                '<li class="%1$s">%2$s</li>',
                esc_attr( $args['item_class'] ),
                $args['before_current'] . get_the_title( $blog_page_id ) . $args['after_current']
            );
        } elseif ( is_category() ) {
            // Category archive.
            $category = get_queried_object();
            if ( $category->parent ) {
                $parent_categories = array();
                $parent_id         = $category->parent;
                while ( $parent_id ) {
                    $parent_category  = get_term( $parent_id, 'category' );
                    $parent_categories[] = sprintf(
                        '<li class="%1$s"><a href="%2$s">%3$s</a></li>',
                        esc_attr( $args['item_class'] ),
                        esc_url( get_category_link( $parent_category->term_id ) ),
                        esc_html( $parent_category->name )
                    );
                    $parent_id = $parent_category->parent;
                }
                $output .= implode( '', array_reverse( $parent_categories ) );
            }
            $output .= sprintf(
                '<li class="%1$s">%2$s</li>',
                esc_attr( $args['item_class'] ),
                $args['before_current'] . single_cat_title( '', false ) . $args['after_current']
            );
        } elseif ( is_tag() ) {
            // Tag archive.
            $output .= sprintf(
                '<li class="%1$s">%2$s</li>',
                esc_attr( $args['item_class'] ),
                $args['before_current'] . single_tag_title( '', false ) . $args['after_current']
            );
        } elseif ( is_author() ) {
            // Author archive.
            $output .= sprintf(
                '<li class="%1$s">%2$s</li>',
                esc_attr( $args['item_class'] ),
                $args['before_current'] . get_the_author() . $args['after_current']
            );
        } elseif ( is_year() ) {
            // Year archive.
            $output .= sprintf(
                '<li class="%1$s">%2$s</li>',
                esc_attr( $args['item_class'] ),
                $args['before_current'] . get_the_date( 'Y' ) . $args['after_current']
            );
        } elseif ( is_month() ) {
            // Month archive.
            $output .= sprintf(
                '<li class="%1$s"><a href="%2$s">%3$s</a></li>',
                esc_attr( $args['item_class'] ),
                esc_url( get_year_link( get_the_date( 'Y' ) ) ),
                esc_html( get_the_date( 'Y' ) )
            );
            $output .= sprintf(
                '<li class="%1$s">%2$s</li>',
                esc_attr( $args['item_class'] ),
                $args['before_current'] . get_the_date( 'F' ) . $args['after_current']
            );
        } elseif ( is_day() ) {
            // Day archive.
            $output .= sprintf(
                '<li class="%1$s"><a href="%2$s">%3$s</a></li>',
                esc_attr( $args['item_class'] ),
                esc_url( get_year_link( get_the_date( 'Y' ) ) ),
                esc_html( get_the_date( 'Y' ) )
            );
            $output .= sprintf(
                '<li class="%1$s"><a href="%2$s">%3$s</a></li>',
                esc_attr( $args['item_class'] ),
                esc_url( get_month_link( get_the_date( 'Y' ), get_the_date( 'm' ) ) ),
                esc_html( get_the_date( 'F' ) )
            );
            $output .= sprintf(
                '<li class="%1$s">%2$s</li>',
                esc_attr( $args['item_class'] ),
                $args['before_current'] . get_the_date( 'j' ) . $args['after_current']
            );
        } elseif ( is_tax() ) {
            // Custom taxonomy archive.
            $term = get_queried_object();
            if ( $term->parent ) {
                $parent_terms = array();
                $parent_id    = $term->parent;
                while ( $parent_id ) {
                    $parent_term   = get_term( $parent_id, $term->taxonomy );
                    $parent_terms[] = sprintf(
                        '<li class="%1$s"><a href="%2$s">%3$s</a></li>',
                        esc_attr( $args['item_class'] ),
                        esc_url( get_term_link( $parent_term ) ),
                        esc_html( $parent_term->name )
                    );
                    $parent_id = $parent_term->parent;
                }
                $output .= implode( '', array_reverse( $parent_terms ) );
            }
            $output .= sprintf(
                '<li class="%1$s">%2$s</li>',
                esc_attr( $args['item_class'] ),
                $args['before_current'] . single_term_title( '', false ) . $args['after_current']
            );
        } elseif ( is_search() ) {
            // Search results.
            $output .= sprintf(
                '<li class="%1$s">%2$s</li>',
                esc_attr( $args['item_class'] ),
                $args['before_current'] . esc_html__( 'Search results for', 'aqualuxe' ) . ' "' . get_search_query() . '"' . $args['after_current']
            );
        } elseif ( is_404() ) {
            // 404 page.
            $output .= sprintf(
                '<li class="%1$s">%2$s</li>',
                esc_attr( $args['item_class'] ),
                $args['before_current'] . esc_html__( 'Page not found', 'aqualuxe' ) . $args['after_current']
            );
        } elseif ( is_singular() ) {
            // Single post/page/CPT.
            if ( 'post' === get_post_type() ) {
                // Single post.
                $categories = get_the_category();
                if ( $categories ) {
                    $category = $categories[0];
                    if ( $category->parent ) {
                        $parent_categories = array();
                        $parent_id         = $category->parent;
                        while ( $parent_id ) {
                            $parent_category  = get_term( $parent_id, 'category' );
                            $parent_categories[] = sprintf(
                                '<li class="%1$s"><a href="%2$s">%3$s</a></li>',
                                esc_attr( $args['item_class'] ),
                                esc_url( get_category_link( $parent_category->term_id ) ),
                                esc_html( $parent_category->name )
                            );
                            $parent_id = $parent_category->parent;
                        }
                        $output .= implode( '', array_reverse( $parent_categories ) );
                    }
                    $output .= sprintf(
                        '<li class="%1$s"><a href="%2$s">%3$s</a></li>',
                        esc_attr( $args['item_class'] ),
                        esc_url( get_category_link( $category->term_id ) ),
                        esc_html( $category->name )
                    );
                }
            } elseif ( 'page' === get_post_type() && ! is_front_page() ) {
                // Single page.
                $parent_id = wp_get_post_parent_id( get_the_ID() );
                if ( $parent_id ) {
                    $parent_pages = array();
                    while ( $parent_id ) {
                        $parent_page   = get_post( $parent_id );
                        $parent_pages[] = sprintf(
                            '<li class="%1$s"><a href="%2$s">%3$s</a></li>',
                            esc_attr( $args['item_class'] ),
                            esc_url( get_permalink( $parent_page->ID ) ),
                            esc_html( get_the_title( $parent_page->ID ) )
                        );
                        $parent_id = wp_get_post_parent_id( $parent_page->ID );
                    }
                    $output .= implode( '', array_reverse( $parent_pages ) );
                }
            } elseif ( 'product' === get_post_type() && class_exists( 'WooCommerce' ) ) {
                // Single product.
                $shop_page_id = wc_get_page_id( 'shop' );
                if ( $shop_page_id ) {
                    $output .= sprintf(
                        '<li class="%1$s"><a href="%2$s">%3$s</a></li>',
                        esc_attr( $args['item_class'] ),
                        esc_url( get_permalink( $shop_page_id ) ),
                        esc_html( get_the_title( $shop_page_id ) )
                    );
                }
                $terms = wc_get_product_terms(
                    get_the_ID(),
                    'product_cat',
                    array(
                        'orderby' => 'parent',
                        'order'   => 'DESC',
                    )
                );
                if ( $terms ) {
                    $main_term = $terms[0];
                    if ( $main_term->parent ) {
                        $parent_terms = array();
                        $parent_id    = $main_term->parent;
                        while ( $parent_id ) {
                            $parent_term   = get_term( $parent_id, 'product_cat' );
                            $parent_terms[] = sprintf(
                                '<li class="%1$s"><a href="%2$s">%3$s</a></li>',
                                esc_attr( $args['item_class'] ),
                                esc_url( get_term_link( $parent_term ) ),
                                esc_html( $parent_term->name )
                            );
                            $parent_id = $parent_term->parent;
                        }
                        $output .= implode( '', array_reverse( $parent_terms ) );
                    }
                    $output .= sprintf(
                        '<li class="%1$s"><a href="%2$s">%3$s</a></li>',
                        esc_attr( $args['item_class'] ),
                        esc_url( get_term_link( $main_term ) ),
                        esc_html( $main_term->name )
                    );
                }
            }

            if ( $args['show_current'] ) {
                $output .= sprintf(
                    '<li class="%1$s">%2$s</li>',
                    esc_attr( $args['item_class'] ),
                    $args['before_current'] . get_the_title() . $args['after_current']
                );
            }
        }

        if ( $output ) {
            printf(
                '<%1$s id="%2$s" class="%3$s"><ol>%4$s</ol></%1$s>',
                esc_html( $args['container'] ),
                esc_attr( $args['container_id'] ),
                esc_attr( $args['container_class'] ),
                $output // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            );
        }
    }
endif;