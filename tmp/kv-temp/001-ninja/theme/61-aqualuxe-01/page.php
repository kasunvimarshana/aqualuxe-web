<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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

            // Fire the page before hook
            do_action( 'aqualuxe_page_before', get_the_ID() );
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php
                    /**
                     * Functions hooked into aqualuxe_page_header action
                     *
                     * @hooked aqualuxe_page_title - 10
                     */
                    do_action( 'aqualuxe_page_header', get_the_ID() );
                    ?>
                </header><!-- .entry-header -->

                <div class="entry-content">
                    <?php
                    /**
                     * Functions hooked into aqualuxe_page_content action
                     *
                     * @hooked aqualuxe_page_content - 10
                     */
                    do_action( 'aqualuxe_page_content', get_the_ID() );
                    ?>
                </div><!-- .entry-content -->

                <footer class="entry-footer">
                    <?php
                    /**
                     * Functions hooked into aqualuxe_page_footer action
                     *
                     * @hooked aqualuxe_page_comments - 10
                     */
                    do_action( 'aqualuxe_page_footer', get_the_ID() );
                    ?>
                </footer><!-- .entry-footer -->
            </article><!-- #post-<?php the_ID(); ?> -->

            <?php
            // Fire the page after hook
            do_action( 'aqualuxe_page_after', get_the_ID() );

        endwhile; // End of the loop.
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_sidebar();
get_footer();