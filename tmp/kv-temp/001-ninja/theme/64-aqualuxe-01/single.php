<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();

// Get display options
$show_tags = aqualuxe_get_option( 'blog_show_tags', true );
$show_author_bio = aqualuxe_get_option( 'blog_show_author_bio', true );
$show_related_posts = aqualuxe_get_option( 'blog_show_related_posts', true );
$show_social_sharing = aqualuxe_get_option( 'blog_show_social_sharing', true );
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while ( have_posts() ) :
            the_post();

            get_template_part( 'templates/content/content', get_post_type() );

            // Show tags if enabled
            if ( $show_tags && has_tag() ) :
                ?>
                <div class="entry-tags">
                    <span class="tags-title"><?php esc_html_e( 'Tags:', 'aqualuxe' ); ?></span>
                    <?php the_tags( '<span class="tag-links">', ', ', '</span>' ); ?>
                </div>
                <?php
            endif;

            // Show social sharing if enabled
            if ( $show_social_sharing ) :
                aqualuxe_social_sharing();
            endif;

            // Show author bio if enabled
            if ( $show_author_bio ) :
                $author_id = get_the_author_meta( 'ID' );
                $author_bio = get_the_author_meta( 'description' );
                
                if ( $author_bio ) :
                    ?>
                    <div class="author-bio">
                        <div class="author-avatar">
                            <?php echo get_avatar( $author_id, 96 ); ?>
                        </div>
                        <div class="author-content">
                            <h3 class="author-name">
                                <?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?>
                            </h3>
                            <div class="author-description">
                                <?php echo wpautop( $author_bio ); ?>
                            </div>
                            <a class="author-link" href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>">
                                <?php
                                printf(
                                    /* translators: %s: author name */
                                    esc_html__( 'View all posts by %s', 'aqualuxe' ),
                                    esc_html( get_the_author_meta( 'display_name' ) )
                                );
                                ?>
                            </a>
                        </div>
                    </div>
                    <?php
                endif;
            endif;

            // Show post navigation
            the_post_navigation(
                [
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
                ]
            );

            // Show related posts if enabled
            if ( $show_related_posts ) :
                $related_posts = aqualuxe_get_related_posts();
                
                if ( $related_posts->have_posts() ) :
                    ?>
                    <div class="related-posts">
                        <h2 class="related-posts-title"><?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?></h2>
                        <div class="related-posts-container">
                            <?php
                            while ( $related_posts->have_posts() ) :
                                $related_posts->the_post();
                                ?>
                                <article class="related-post">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <div class="related-post-thumbnail">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail( 'medium' ); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <div class="related-post-content">
                                        <h3 class="related-post-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        <div class="related-post-meta">
                                            <span class="related-post-date">
                                                <?php echo get_the_date(); ?>
                                            </span>
                                        </div>
                                    </div>
                                </article>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                    <?php
                endif;
            endif;

            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();