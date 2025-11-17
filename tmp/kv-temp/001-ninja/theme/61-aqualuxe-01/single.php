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
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        while ( have_posts() ) :
            the_post();

            // Fire the post before hook
            do_action( 'aqualuxe_post_before', get_the_ID() );
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php
                    /**
                     * Functions hooked into aqualuxe_post_header action
                     *
                     * @hooked aqualuxe_post_title - 10
                     * @hooked aqualuxe_post_meta - 20
                     */
                    do_action( 'aqualuxe_post_header', get_the_ID() );
                    ?>
                </header><!-- .entry-header -->

                <div class="entry-content">
                    <?php
                    /**
                     * Functions hooked into aqualuxe_post_content action
                     *
                     * @hooked aqualuxe_post_content - 10
                     */
                    do_action( 'aqualuxe_post_content', get_the_ID() );
                    ?>
                </div><!-- .entry-content -->

                <footer class="entry-footer">
                    <?php
                    /**
                     * Functions hooked into aqualuxe_post_footer action
                     *
                     * @hooked aqualuxe_post_tags - 10
                     * @hooked aqualuxe_post_author - 20
                     * @hooked aqualuxe_post_comments - 30
                     */
                    do_action( 'aqualuxe_post_footer', get_the_ID() );
                    ?>
                </footer><!-- .entry-footer -->
            </article><!-- #post-<?php the_ID(); ?> -->

            <?php
            // Fire the post after hook
            do_action( 'aqualuxe_post_after', get_the_ID() );

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