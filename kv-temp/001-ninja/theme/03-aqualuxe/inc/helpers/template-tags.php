<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

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
                <?php the_post_thumbnail( 'aqualuxe-featured-image', array( 'class' => 'featured-image' ) ); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    'aqualuxe-featured-image',
                    array(
                        'alt' => the_title_attribute(
                            array(
                                'echo' => false,
                            )
                        ),
                        'class' => 'featured-image',
                    )
                );
                ?>
            </a>

            <?php
        endif; // End is_singular().
    }
endif;

if ( ! function_exists( 'aqualuxe_entry_meta' ) ) :
    /**
     * Prints HTML with meta information for the current post.
     */
    function aqualuxe_entry_meta() {
        // Hide meta information on pages.
        if ( 'post' !== get_post_type() ) {
            return;
        }

        // Get the author name; wrap it in a link.
        $byline = sprintf(
            /* translators: %s: post author */
            __( 'by %s', 'aqualuxe' ),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . get_the_author() . '</a></span>'
        );

        // Finally, let's write all of this to the page.
        echo '<div class="entry-meta">';
        
        // Posted on
        echo '<span class="posted-on"><i class="fa fa-calendar"></i> ';
        echo '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . esc_html( get_the_date() ) . '</a>';
        echo '</span>';
        
        // Author
        echo '<span class="byline"><i class="fa fa-user"></i> ' . $byline . '</span>';
        
        // Comments
        if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link"><i class="fa fa-comment"></i> ';
            comments_popup_link(
                __( 'Leave a comment', 'aqualuxe' ),
                __( '1 Comment', 'aqualuxe' ),
                __( '% Comments', 'aqualuxe' )
            );
            echo '</span>';
        }
        
        // Categories
        $categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
        if ( $categories_list ) {
            echo '<span class="cat-links"><i class="fa fa-folder"></i> ' . $categories_list . '</span>';
        }
        
        echo '</div><!-- .entry-meta -->';
    }
endif;

if ( ! function_exists( 'aqualuxe_entry_footer_meta' ) ) :
    /**
     * Prints HTML with meta information for the tags.
     */
    function aqualuxe_entry_footer_meta() {
        // Hide meta information on pages.
        if ( 'post' !== get_post_type() ) {
            return;
        }

        // Tags
        $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
        if ( $tags_list ) {
            echo '<div class="entry-footer-meta">';
            echo '<span class="tags-links"><i class="fa fa-tags"></i> ' . $tags_list . '</span>';
            echo '</div><!-- .entry-footer-meta -->';
        }
    }
endif;

if ( ! function_exists( 'aqualuxe_post_navigation' ) ) :
    /**
     * Display navigation to next/previous post when applicable.
     */
    function aqualuxe_post_navigation() {
        if ( ! get_theme_mod( 'aqualuxe_post_navigation', true ) ) {
            return;
        }
        
        $prev_post = get_previous_post();
        $next_post = get_next_post();

        if ( ! $prev_post && ! $next_post ) {
            return;
        }

        echo '<nav class="navigation post-navigation" role="navigation">';
        echo '<h2 class="screen-reader-text">' . esc_html__( 'Post navigation', 'aqualuxe' ) . '</h2>';
        echo '<div class="nav-links">';

        if ( $prev_post ) {
            echo '<div class="nav-previous">';
            echo '<a href="' . esc_url( get_permalink( $prev_post->ID ) ) . '" rel="prev">';
            echo '<div class="nav-title-icon-wrapper"><i class="fa fa-angle-left"></i></div>';
            echo '<span class="nav-subtitle">' . esc_html__( 'Previous Post', 'aqualuxe' ) . '</span>';
            echo '<span class="nav-title">' . esc_html( get_the_title( $prev_post->ID ) ) . '</span>';
            echo '</a>';
            echo '</div>';
        }

        if ( $next_post ) {
            echo '<div class="nav-next">';
            echo '<a href="' . esc_url( get_permalink( $next_post->ID ) ) . '" rel="next">';
            echo '<span class="nav-subtitle">' . esc_html__( 'Next Post', 'aqualuxe' ) . '</span>';
            echo '<span class="nav-title">' . esc_html( get_the_title( $next_post->ID ) ) . '</span>';
            echo '<div class="nav-title-icon-wrapper"><i class="fa fa-angle-right"></i></div>';
            echo '</a>';
            echo '</div>';
        }

        echo '</div><!-- .nav-links -->';
        echo '</nav><!-- .navigation -->';
    }
endif;

if ( ! function_exists( 'aqualuxe_pagination' ) ) :
    /**
     * Display pagination for archive pages.
     */
    function aqualuxe_pagination() {
        $pagination_type = get_theme_mod( 'aqualuxe_pagination_type', 'numbered' );

        if ( $pagination_type === 'numbered' ) {
            // Numbered pagination
            the_posts_pagination(
                array(
                    'mid_size'           => 2,
                    'prev_text'          => '<i class="fa fa-angle-left"></i><span class="screen-reader-text">' . esc_html__( 'Previous', 'aqualuxe' ) . '</span>',
                    'next_text'          => '<span class="screen-reader-text">' . esc_html__( 'Next', 'aqualuxe' ) . '</span><i class="fa fa-angle-right"></i>',
                    'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'aqualuxe' ) . ' </span>',
                )
            );
        } else {
            // Prev/Next pagination
            the_posts_navigation(
                array(
                    'prev_text' => '<i class="fa fa-angle-left"></i> ' . esc_html__( 'Older Posts', 'aqualuxe' ),
                    'next_text' => esc_html__( 'Newer Posts', 'aqualuxe' ) . ' <i class="fa fa-angle-right"></i>',
                )
            );
        }
    }
endif;

if ( ! function_exists( 'aqualuxe_related_posts' ) ) :
    /**
     * Display related posts.
     */
    function aqualuxe_related_posts() {
        if ( ! get_theme_mod( 'aqualuxe_related_posts', true ) ) {
            return;
        }

        $related_posts_count = get_theme_mod( 'aqualuxe_related_posts_count', 3 );
        $related_posts_query = aqualuxe_get_related_posts( get_the_ID(), $related_posts_count );

        if ( $related_posts_query->have_posts() ) :
            ?>
            <div class="related-posts">
                <h3 class="related-posts-title"><?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?></h3>
                <div class="row">
                    <?php
                    while ( $related_posts_query->have_posts() ) :
                        $related_posts_query->the_post();
                        ?>
                        <div class="col-md-4">
                            <article <?php post_class( 'related-post' ); ?>>
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                                        <?php
                                        the_post_thumbnail(
                                            'medium',
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
                                <?php endif; ?>

                                <header class="entry-header">
                                    <?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>
                                </header><!-- .entry-header -->

                                <div class="entry-meta">
                                    <?php echo esc_html( get_the_date() ); ?>
                                </div><!-- .entry-meta -->
                            </article><!-- #post-<?php the_ID(); ?> -->
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </div><!-- .related-posts -->
            <?php
        endif;
    }
endif;

if ( ! function_exists( 'aqualuxe_author_bio' ) ) :
    /**
     * Display author bio.
     */
    function aqualuxe_author_bio() {
        if ( ! get_theme_mod( 'aqualuxe_author_bio', true ) ) {
            return;
        }

        if ( ! is_single() ) {
            return;
        }

        $author_id = get_the_author_meta( 'ID' );
        $author_bio = get_the_author_meta( 'description' );

        if ( ! $author_bio ) {
            return;
        }
        ?>
        <div class="author-bio">
            <div class="author-avatar">
                <?php echo get_avatar( $author_id, 100 ); ?>
            </div>
            <div class="author-info">
                <h3 class="author-title">
                    <?php
                    printf(
                        /* translators: %s: Author name */
                        esc_html__( 'About %s', 'aqualuxe' ),
                        esc_html( get_the_author() )
                    );
                    ?>
                </h3>
                <div class="author-description">
                    <?php echo wpautop( $author_bio ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </div>
                <a class="author-link" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" rel="author">
                    <?php
                    printf(
                        /* translators: %s: Author name */
                        esc_html__( 'View all posts by %s', 'aqualuxe' ),
                        esc_html( get_the_author() )
                    );
                    ?>
                </a>
            </div>
        </div><!-- .author-bio -->
        <?php
    }
endif;

if ( ! function_exists( 'aqualuxe_post_thumbnail_caption' ) ) :
    /**
     * Display post thumbnail caption.
     */
    function aqualuxe_post_thumbnail_caption() {
        if ( ! has_post_thumbnail() ) {
            return;
        }

        $caption = get_the_post_thumbnail_caption();

        if ( ! $caption ) {
            return;
        }
        ?>
        <div class="post-thumbnail-caption">
            <?php echo esc_html( $caption ); ?>
        </div>
        <?php
    }
endif;

if ( ! function_exists( 'aqualuxe_breadcrumbs' ) ) :
    /**
     * Display breadcrumbs.
     */
    function aqualuxe_breadcrumbs() {
        if ( ! get_theme_mod( 'aqualuxe_breadcrumbs', true ) ) {
            return;
        }

        echo aqualuxe_get_breadcrumbs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'aqualuxe_social_links' ) ) :
    /**
     * Display social links.
     */
    function aqualuxe_social_links() {
        echo aqualuxe_get_social_links(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'aqualuxe_footer_widgets' ) ) :
    /**
     * Display footer widgets.
     */
    function aqualuxe_footer_widgets() {
        echo aqualuxe_get_footer_widgets(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'aqualuxe_footer_copyright' ) ) :
    /**
     * Display footer copyright.
     */
    function aqualuxe_footer_copyright() {
        echo '<div class="site-info">';
        echo wp_kses_post( aqualuxe_get_copyright_text() );
        echo '</div>';
    }
endif;

if ( ! function_exists( 'aqualuxe_header_logo' ) ) :
    /**
     * Display header logo.
     */
    function aqualuxe_header_logo() {
        echo '<div class="site-branding">';
        echo '<a href="' . esc_url( home_url( '/' ) ) . '" rel="home">';
        echo aqualuxe_get_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '</a>';
        echo '</div>';
    }
endif;

if ( ! function_exists( 'aqualuxe_primary_navigation' ) ) :
    /**
     * Display primary navigation.
     */
    function aqualuxe_primary_navigation() {
        echo aqualuxe_get_primary_menu(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'aqualuxe_secondary_navigation' ) ) :
    /**
     * Display secondary navigation.
     */
    function aqualuxe_secondary_navigation() {
        echo aqualuxe_get_secondary_menu(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'aqualuxe_footer_navigation' ) ) :
    /**
     * Display footer navigation.
     */
    function aqualuxe_footer_navigation() {
        echo aqualuxe_get_footer_menu(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'aqualuxe_header_search' ) ) :
    /**
     * Display header search.
     */
    function aqualuxe_header_search() {
        if ( ! get_theme_mod( 'aqualuxe_header_search', true ) ) {
            return;
        }

        echo '<div class="header-search">';
        echo aqualuxe_get_search_toggle(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<div class="header-search-form">';
        echo aqualuxe_get_search_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '</div>';
        echo '</div>';
    }
endif;

if ( ! function_exists( 'aqualuxe_mobile_menu' ) ) :
    /**
     * Display mobile menu.
     */
    function aqualuxe_mobile_menu() {
        echo '<div class="mobile-menu">';
        echo aqualuxe_get_mobile_menu_toggle(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '</div>';
    }
endif;

if ( ! function_exists( 'aqualuxe_header_icons' ) ) :
    /**
     * Display header icons.
     */
    function aqualuxe_header_icons() {
        echo '<div class="header-icons">';
        do_action( 'aqualuxe_header_icons' );
        echo '</div>';
    }
endif;

if ( ! function_exists( 'aqualuxe_page_header' ) ) :
    /**
     * Display page header.
     */
    function aqualuxe_page_header() {
        echo aqualuxe_get_page_header(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'aqualuxe_back_to_top' ) ) :
    /**
     * Display back to top button.
     */
    function aqualuxe_back_to_top() {
        echo aqualuxe_get_back_to_top(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'aqualuxe_excerpt' ) ) :
    /**
     * Display post excerpt.
     *
     * @param int $length Excerpt length.
     */
    function aqualuxe_excerpt( $length = 55 ) {
        echo aqualuxe_get_post_excerpt( $length ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'aqualuxe_read_more' ) ) :
    /**
     * Display read more link.
     */
    function aqualuxe_read_more() {
        echo '<div class="read-more-link">';
        echo '<a href="' . esc_url( get_permalink() ) . '" class="more-link">' . esc_html__( 'Read More', 'aqualuxe' ) . ' <i class="fa fa-angle-right"></i></a>';
        echo '</div>';
    }
endif;

if ( ! function_exists( 'aqualuxe_post_meta' ) ) :
    /**
     * Display post meta.
     */
    function aqualuxe_post_meta() {
        echo aqualuxe_get_post_meta(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'aqualuxe_post_categories' ) ) :
    /**
     * Display post categories.
     */
    function aqualuxe_post_categories() {
        echo aqualuxe_get_post_categories(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if ( ! function_exists( 'aqualuxe_post_tags' ) ) :
    /**
     * Display post tags.
     */
    function aqualuxe_post_tags() {
        echo aqualuxe_get_post_tags(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;