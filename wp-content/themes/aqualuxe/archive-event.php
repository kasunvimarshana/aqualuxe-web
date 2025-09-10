<?php
/**
 * The template for displaying archive pages for the 'event' post type.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aqualuxe
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main py-12">

        <div class="container mx-auto px-4">
            <?php if (have_posts()) : ?>

                <header class="page-header mb-12 text-center">
                    <?php
                    the_archive_title('<h1 class="page-title text-4xl font-bold text-gray-800">', '</h1>');
                    the_archive_description('<div class="archive-description text-lg text-gray-600 mt-2">', '</div>');
                    ?>
                </header><!-- .page-header -->

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();

                        /*
                         * Include the Post-Type-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                         */
                        get_template_part('templates/parts/content', get_post_type());
                    endwhile;
                    ?>
                </div>

                <?php
                the_posts_navigation();

            else :

                get_template_part('templates/parts/content', 'none');

            endif;
            ?>
        </div><!-- .container -->

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
