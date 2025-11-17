<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4">
        <?php if ( have_posts() ) : ?>
            
            <?php if ( is_home() && ! is_front_page() ) : ?>
                <header class="page-header py-8">
                    <h1 class="page-title text-4xl font-bold text-center text-gray-900 dark:text-white">
                        <?php single_post_title(); ?>
                    </h1>
                </header>
            <?php endif; ?>

            <div class="posts-container grid gap-8 lg:grid-cols-2 xl:grid-cols-3">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    get_template_part( 'templates/content', get_post_type() );

                endwhile;
                ?>
            </div>

            <?php
            the_posts_navigation( array(
                'prev_text'          => __( '&larr; Older posts', 'aqualuxe' ),
                'next_text'          => __( 'Newer posts &rarr;', 'aqualuxe' ),
                'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page ', 'aqualuxe' ) . '</span>',
            ) );

        else :

            get_template_part( 'templates/content', 'none' );

        endif;
        ?>
    </div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer();
?>