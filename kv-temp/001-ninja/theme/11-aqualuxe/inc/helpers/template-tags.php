<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 * @since 1.0.0
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

        echo '<span class="posted-on"><i class="fas fa-calendar-alt"></i> ' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

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

        echo '<span class="byline"><i class="fas fa-user"></i> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

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
                printf( '<span class="cat-links"><i class="fas fa-folder-open"></i> ' . esc_html__( 'Posted in %1$s', 'aqualuxe' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
            if ( $tags_list ) {
                /* translators: 1: list of tags. */
                printf( '<span class="tags-links"><i class="fas fa-tags"></i> ' . esc_html__( 'Tagged %1$s', 'aqualuxe' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }

        if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
            echo '<span class="comments-link"><i class="fas fa-comments"></i> ';
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
            '<span class="edit-link"><i class="fas fa-edit"></i> ',
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
                <?php the_post_thumbnail( 'aqualuxe-featured-image', array( 'class' => 'img-fluid' ) ); ?>
            </div><!-- .post-thumbnail -->

        <?php else : ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                the_post_thumbnail(
                    'aqualuxe-blog-thumbnail',
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

if ( ! function_exists( 'aqualuxe_get_post_views' ) ) :
    /**
     * Get post views count
     *
     * @param int $post_id Post ID.
     * @return int
     */
    function aqualuxe_get_post_views( $post_id ) {
        $count_key = 'post_views_count';
        $count     = get_post_meta( $post_id, $count_key, true );
        
        if ( '' === $count ) {
            delete_post_meta( $post_id, $count_key );
            add_post_meta( $post_id, $count_key, '0' );
            return 0;
        }
        
        return (int) $count;
    }
endif;

if ( ! function_exists( 'aqualuxe_set_post_views' ) ) :
    /**
     * Set post views count
     *
     * @param int $post_id Post ID.
     */
    function aqualuxe_set_post_views( $post_id ) {
        $count_key = 'post_views_count';
        $count     = get_post_meta( $post_id, $count_key, true );
        
        if ( '' === $count ) {
            delete_post_meta( $post_id, $count_key );
            add_post_meta( $post_id, $count_key, '1' );
        } else {
            $count++;
            update_post_meta( $post_id, $count_key, $count );
        }
    }
endif;

if ( ! function_exists( 'aqualuxe_display_post_views' ) ) :
    /**
     * Display post views count
     */
    function aqualuxe_display_post_views() {
        $post_id = get_the_ID();
        $count   = aqualuxe_get_post_views( $post_id );
        
        echo '<span class="post-views"><i class="fas fa-eye"></i> ' . esc_html( sprintf( _n( '%s view', '%s views', $count, 'aqualuxe' ), number_format_i18n( $count ) ) ) . '</span>';
    }
endif;

if ( ! function_exists( 'aqualuxe_reading_time' ) ) :
    /**
     * Calculate and display estimated reading time
     */
    function aqualuxe_reading_time() {
        $content = get_post_field( 'post_content', get_the_ID() );
        $word_count = str_word_count( strip_tags( $content ) );
        $reading_time = ceil( $word_count / 200 ); // Assuming 200 words per minute reading speed
        
        if ( $reading_time < 1 ) {
            $reading_time = 1;
        }
        
        echo '<span class="reading-time"><i class="fas fa-clock"></i> ' . esc_html( sprintf( _n( '%s min read', '%s min read', $reading_time, 'aqualuxe' ), number_format_i18n( $reading_time ) ) ) . '</span>';
    }
endif;

if ( ! function_exists( 'aqualuxe_social_share' ) ) :
    /**
     * Display social sharing buttons
     */
    function aqualuxe_social_share() {
        $post_url = urlencode( get_permalink() );
        $post_title = urlencode( get_the_title() );
        
        $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url;
        $twitter_url = 'https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title;
        $linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title;
        $pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&description=' . $post_title;
        
        if ( has_post_thumbnail() ) {
            $pinterest_url .= '&media=' . urlencode( get_the_post_thumbnail_url( get_the_ID(), 'full' ) );
        }
        
        echo '<div class="social-share">';
        echo '<span class="share-title">' . esc_html__( 'Share:', 'aqualuxe' ) . '</span>';
        echo '<a href="' . esc_url( $facebook_url ) . '" target="_blank" rel="noopener noreferrer" class="share-facebook" title="' . esc_attr__( 'Share on Facebook', 'aqualuxe' ) . '"><i class="fab fa-facebook-f"></i></a>';
        echo '<a href="' . esc_url( $twitter_url ) . '" target="_blank" rel="noopener noreferrer" class="share-twitter" title="' . esc_attr__( 'Share on Twitter', 'aqualuxe' ) . '"><i class="fab fa-twitter"></i></a>';
        echo '<a href="' . esc_url( $linkedin_url ) . '" target="_blank" rel="noopener noreferrer" class="share-linkedin" title="' . esc_attr__( 'Share on LinkedIn', 'aqualuxe' ) . '"><i class="fab fa-linkedin-in"></i></a>';
        echo '<a href="' . esc_url( $pinterest_url ) . '" target="_blank" rel="noopener noreferrer" class="share-pinterest" title="' . esc_attr__( 'Share on Pinterest', 'aqualuxe' ) . '"><i class="fab fa-pinterest-p"></i></a>';
        echo '</div>';
    }
endif;

if ( ! function_exists( 'aqualuxe_related_posts' ) ) :
    /**
     * Display related posts
     */
    function aqualuxe_related_posts() {
        $post_id = get_the_ID();
        $cat_ids = wp_get_post_categories( $post_id );
        
        if ( empty( $cat_ids ) ) {
            return;
        }
        
        $args = array(
            'category__in'        => $cat_ids,
            'post__not_in'        => array( $post_id ),
            'posts_per_page'      => 3,
            'ignore_sticky_posts' => 1,
        );
        
        $related_query = new WP_Query( $args );
        
        if ( $related_query->have_posts() ) :
            ?>
            <div class="related-posts">
                <h3 class="related-posts-title"><?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?></h3>
                <div class="row">
                    <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
                        <div class="col-md-4">
                            <article class="related-post">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a href="<?php the_permalink(); ?>" class="related-post-thumbnail">
                                        <?php the_post_thumbnail( 'aqualuxe-blog-thumbnail', array( 'class' => 'img-fluid' ) ); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="related-post-content">
                                    <h4 class="related-post-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h4>
                                    <div class="related-post-meta">
                                        <span class="related-post-date">
                                            <i class="fas fa-calendar-alt"></i>
                                            <?php echo esc_html( get_the_date() ); ?>
                                        </span>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php
            wp_reset_postdata();
        endif;
    }
endif;

if ( ! function_exists( 'aqualuxe_author_bio' ) ) :
    /**
     * Display author bio
     */
    function aqualuxe_author_bio() {
        if ( get_the_author_meta( 'description' ) ) :
            ?>
            <div class="author-bio">
                <div class="author-avatar">
                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 100, '', '', array( 'class' => 'img-fluid rounded-circle' ) ); ?>
                </div>
                <div class="author-info">
                    <h3 class="author-name">
                        <?php echo esc_html( get_the_author() ); ?>
                    </h3>
                    <div class="author-description">
                        <?php echo wpautop( get_the_author_meta( 'description' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="author-link">
                        <?php
                        /* translators: %s: Author name */
                        printf( esc_html__( 'View all posts by %s', 'aqualuxe' ), esc_html( get_the_author() ) );
                        ?>
                        <i class="fas fa-long-arrow-alt-right"></i>
                    </a>
                </div>
            </div>
            <?php
        endif;
    }
endif;

if ( ! function_exists( 'aqualuxe_pagination' ) ) :
    /**
     * Custom pagination function
     */
    function aqualuxe_pagination() {
        global $wp_query;
        
        if ( $wp_query->max_num_pages <= 1 ) {
            return;
        }
        
        $big = 999999999; // need an unlikely integer
        
        $pages = paginate_links(
            array(
                'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                'format'    => '?paged=%#%',
                'current'   => max( 1, get_query_var( 'paged' ) ),
                'total'     => $wp_query->max_num_pages,
                'type'      => 'array',
                'prev_text' => '<i class="fas fa-chevron-left"></i><span class="screen-reader-text">' . esc_html__( 'Previous page', 'aqualuxe' ) . '</span>',
                'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'aqualuxe' ) . '</span><i class="fas fa-chevron-right"></i>',
                'mid_size'  => 2,
                'end_size'  => 1,
            )
        );
        
        if ( is_array( $pages ) ) {
            echo '<nav class="aqualuxe-pagination" aria-label="' . esc_attr__( 'Posts navigation', 'aqualuxe' ) . '">';
            echo '<ul class="pagination">';
            
            foreach ( $pages as $page ) {
                $active = strpos( $page, 'current' ) !== false ? ' active' : '';
                echo '<li class="page-item' . esc_attr( $active ) . '">';
                echo str_replace( 'page-numbers', 'page-link', $page );
                echo '</li>';
            }
            
            echo '</ul>';
            echo '</nav>';
        }
    }
endif;