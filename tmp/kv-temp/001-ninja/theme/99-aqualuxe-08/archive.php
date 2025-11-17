<?php
/**
 * The template for displaying archive pages
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php if (have_posts()) : ?>

        <header class="page-header">
            <div class="container">
                <?php
                the_archive_title('<h1 class="page-title">', '</h1>');
                the_archive_description('<div class="archive-description">', '</div>');
                ?>
            </div>
        </header><!-- .page-header -->

        <div class="container">
            <div class="content-area">
                <div class="posts-container">
                    <?php
                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();

                        /*
                         * Include the Post-Type-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                         */
                        get_template_part('templates/content', get_post_type());

                    endwhile;
                    ?>
                </div>

                <?php
                the_posts_navigation(array(
                    'prev_text' => esc_html__('Older posts', 'aqualuxe'),
                    'next_text' => esc_html__('Newer posts', 'aqualuxe'),
                ));
                ?>
            </div>

            <?php get_sidebar(); ?>
        </div>

    <?php else : ?>

        <div class="container">
            <div class="content-area">
                <?php get_template_part('templates/content', 'none'); ?>
            </div>
        </div>

    <?php endif; ?>

</main><!-- #primary -->

<?php
get_footer();