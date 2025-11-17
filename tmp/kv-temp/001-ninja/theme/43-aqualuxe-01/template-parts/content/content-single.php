<?php
/**
 * Template part for displaying single post content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header mb-8">
        <?php the_title( '<h1 class="entry-title text-3xl md:text-4xl font-bold">', '</h1>' ); ?>
        
        <div class="entry-meta flex flex-wrap items-center text-sm text-gray-600 dark:text-gray-400 mt-4">
            <?php
            aqualuxe_posted_on();
            aqualuxe_posted_by();
            ?>
            
            <span class="comments-link ml-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <?php comments_popup_link(
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
                ); ?>
            </span>
        </div><!-- .entry-meta -->
    </header><!-- .entry-header -->

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail mb-8">
            <?php the_post_thumbnail( 'large', array( 'class' => 'rounded-lg w-full h-auto' ) ); ?>
            <?php if ( wp_get_attachment_caption( get_post_thumbnail_id() ) ) : ?>
                <div class="post-thumbnail-caption text-sm text-gray-600 dark:text-gray-400 mt-2 italic">
                    <?php echo wp_kses_post( wp_get_attachment_caption( get_post_thumbnail_id() ) ); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="entry-content prose max-w-none">
        <?php
        the_content(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe' ),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post( get_the_title() )
            )
        );

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
                'after'  => '</div>',
            )
        );
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
        <?php aqualuxe_entry_footer(); ?>
        
        <?php if ( function_exists( 'aqualuxe_social_sharing' ) ) : ?>
            <div class="social-sharing mt-6">
                <h3 class="text-lg font-bold mb-3"><?php esc_html_e( 'Share This Post', 'aqualuxe' ); ?></h3>
                <?php aqualuxe_social_sharing(); ?>
            </div>
        <?php endif; ?>
        
        <?php
        // Author bio
        if ( get_theme_mod( 'aqualuxe_show_author_bio', true ) && get_the_author_meta( 'description' ) ) :
            get_template_part( 'template-parts/content/author-bio' );
        endif;
        ?>
        
        <?php
        // Related posts
        if ( function_exists( 'aqualuxe_related_posts' ) && get_theme_mod( 'aqualuxe_show_related_posts', true ) ) :
            aqualuxe_related_posts();
        endif;
        ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->