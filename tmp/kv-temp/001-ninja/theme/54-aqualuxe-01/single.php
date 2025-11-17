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

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-wrap -mx-4">
        <main id="primary" class="site-main w-full lg:w-2/3 px-4">

            <?php
            while (have_posts()) :
                the_post();

                get_template_part('templates/content', get_post_type());

                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;

                // Display social share buttons
                aqualuxe_social_share();

                // Display related posts if enabled in theme settings
                if (get_theme_mod('aqualuxe_show_related_posts', true)) {
                    aqualuxe_related_posts();
                }

                // Post navigation
                the_post_navigation(
                    [
                        'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
                        'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
                        'class' => 'post-navigation mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-wrap justify-between',
                    ]
                );

            endwhile; // End of the loop.
            ?>

        </main><!-- #primary -->

        <?php get_sidebar(); ?>
    </div>
</div>

<?php
get_footer();