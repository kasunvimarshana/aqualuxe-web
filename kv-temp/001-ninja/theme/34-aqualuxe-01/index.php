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
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-wrap -mx-4">
            <div class="w-full lg:w-2/3 px-4">
                <?php
                if (have_posts()) :
                    // Start the Loop
                    while (have_posts()) :
                        the_post();

                        /*
                         * Include the Post-Type-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                         */
                        get_template_part('template-parts/content', get_post_type());

                    endwhile;

                    // Previous/next page navigation
                    the_posts_pagination(
                        array(
                            'prev_text' => '<span class="screen-reader-text">' . __('Previous page', 'aqualuxe') . '</span>',
                            'next_text' => '<span class="screen-reader-text">' . __('Next page', 'aqualuxe') . '</span>',
                            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'aqualuxe') . ' </span>',
                        )
                    );

                else :
                    // If no content, include the "No posts found" template
                    get_template_part('template-parts/content', 'none');
                endif;
                ?>
            </div>
            <div class="w-full lg:w-1/3 px-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main><!-- #primary -->

<?php
get_footer();