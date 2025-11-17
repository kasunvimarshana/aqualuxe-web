<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

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
                <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto rounded-lg' ) ); ?>
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
                        'class' => 'w-full h-auto rounded-lg hover:opacity-90 transition-opacity',
                    )
                );
                ?>
            </a>
            <?php
        endif; // End is_singular().
    }
endif;

if ( ! function_exists( 'aqualuxe_comment_avatar' ) ) :
    /**
     * Returns the HTML markup for the current comment author's avatar.
     *
     * @param array $args Arguments passed to get_avatar.
     * @return string The avatar HTML markup.
     */
    function aqualuxe_comment_avatar( $args = array() ) {
        $defaults = array(
            'size'    => 60,
            'class'   => 'rounded-full',
            'alt'     => '',
        );
        $args = wp_parse_args( $args, $defaults );

        return get_avatar( get_comment_author_email(), $args['size'], '', $args['alt'], $args );
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

if ( ! function_exists( 'aqualuxe_the_posts_navigation' ) ) :
    /**
     * Display navigation to next/previous set of posts when applicable.
     */
    function aqualuxe_the_posts_navigation() {
        the_posts_navigation(
            array(
                'mid_size'           => 2,
                'prev_text'          => sprintf(
                    '%s <span class="nav-prev-text">%s</span>',
                    '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>',
                    __( 'Older posts', 'aqualuxe' )
                ),
                'next_text'          => sprintf(
                    '<span class="nav-next-text">%s</span> %s',
                    __( 'Newer posts', 'aqualuxe' ),
                    '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>'
                ),
                'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page ', 'aqualuxe' ) . '</span>',
            )
        );
    }
endif;

if ( ! function_exists( 'aqualuxe_the_post_navigation' ) ) :
    /**
     * Display navigation to next/previous post when applicable.
     */
    function aqualuxe_the_post_navigation() {
        the_post_navigation(
            array(
                'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next Post', 'aqualuxe' ) . '</span> ' .
                    '<span class="screen-reader-text">' . __( 'Next post:', 'aqualuxe' ) . '</span> <br/>' .
                    '<span class="post-title">%title</span>',
                'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous Post', 'aqualuxe' ) . '</span> ' .
                    '<span class="screen-reader-text">' . __( 'Previous post:', 'aqualuxe' ) . '</span> <br/>' .
                    '<span class="post-title">%title</span>',
            )
        );
    }
endif;

if ( ! function_exists( 'aqualuxe_entry_meta' ) ) :
    /**
     * Display entry meta information.
     */
    function aqualuxe_entry_meta() {
        if ( 'post' === get_post_type() ) {
            echo '<div class="entry-meta flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">';
            
            // Posted date
            echo '<span class="posted-date flex items-center">';
            echo '<svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>';
            aqualuxe_posted_on();
            echo '</span>';
            
            // Author
            echo '<span class="posted-by flex items-center">';
            echo '<svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>';
            aqualuxe_posted_by();
            echo '</span>';
            
            // Categories
            $categories_list = get_the_category_list( ', ' );
            if ( $categories_list ) {
                echo '<span class="categories-list flex items-center">';
                echo '<svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>';
                echo $categories_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '</span>';
            }
            
            // Reading time
            echo '<span class="reading-time flex items-center">';
            echo '<svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>';
            echo esc_html( aqualuxe_get_reading_time() );
            echo '</span>';
            
            // Comments
            if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
                echo '<span class="comments-link flex items-center">';
                echo '<svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path></svg>';
                comments_popup_link( __( 'Leave a comment', 'aqualuxe' ), __( '1 Comment', 'aqualuxe' ), __( '% Comments', 'aqualuxe' ) );
                echo '</span>';
            }
            
            echo '</div>';
        }
    }
endif;

if ( ! function_exists( 'aqualuxe_post_tags' ) ) :
    /**
     * Display post tags.
     */
    function aqualuxe_post_tags() {
        if ( 'post' === get_post_type() ) {
            $tags_list = get_the_tag_list( '', ' ' );
            if ( $tags_list ) {
                echo '<div class="post-tags mt-6">';
                echo '<span class="tags-label text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">' . esc_html__( 'Tags:', 'aqualuxe' ) . '</span>';
                echo $tags_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                echo '</div>';
            }
        }
    }
endif;

if ( ! function_exists( 'aqualuxe_author_bio' ) ) :
    /**
     * Display author bio box.
     */
    function aqualuxe_author_bio() {
        if ( is_single() && ! empty( get_the_author_meta( 'description' ) ) ) {
            ?>
            <div class="author-bio bg-gray-50 dark:bg-gray-800 p-6 rounded-lg mt-8">
                <div class="author-avatar float-left mr-4 mb-4">
                    <?php echo get_avatar( get_the_author_meta( 'user_email' ), 80, '', '', array( 'class' => 'rounded-full' ) ); ?>
                </div>
                <div class="author-info">
                    <h3 class="author-name text-xl font-bold mb-2">
                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="text-gray-900 dark:text-white hover:text-aqua-600 dark:hover:text-aqua-400">
                            <?php echo esc_html( get_the_author() ); ?>
                        </a>
                    </h3>
                    <div class="author-description text-gray-600 dark:text-gray-400 leading-relaxed">
                        <?php echo wp_kses_post( get_the_author_meta( 'description' ) ); ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <?php
        }
    }
endif;

if ( ! function_exists( 'aqualuxe_related_posts' ) ) :
    /**
     * Display related posts.
     */
    function aqualuxe_related_posts() {
        if ( ! is_single() ) {
            return;
        }
        
        $related_posts = new WP_Query( array(
            'post_type'      => 'post',
            'posts_per_page' => 3,
            'post__not_in'   => array( get_the_ID() ),
            'category__in'   => wp_get_post_categories( get_the_ID() ),
            'orderby'        => 'rand',
        ) );
        
        if ( $related_posts->have_posts() ) :
            ?>
            <div class="related-posts mt-12">
                <h3 class="related-title text-2xl font-bold mb-6"><?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?></h3>
                <div class="related-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    while ( $related_posts->have_posts() ) :
                        $related_posts->the_post();
                        ?>
                        <div class="related-post">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="post-thumbnail mb-3">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover rounded-lg' ) ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <h4 class="post-title text-lg font-semibold mb-2">
                                <a href="<?php the_permalink(); ?>" class="text-gray-900 dark:text-white hover:text-aqua-600 dark:hover:text-aqua-400">
                                    <?php the_title(); ?>
                                </a>
                            </h4>
                            <div class="post-excerpt text-gray-600 dark:text-gray-400 text-sm">
                                <?php echo esc_html( wp_trim_words( get_the_excerpt(), 15 ) ); ?>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    ?>
                </div>
            </div>
            <?php
            wp_reset_postdata();
        endif;
    }
endif;