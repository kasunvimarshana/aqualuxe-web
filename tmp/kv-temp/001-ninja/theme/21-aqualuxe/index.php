<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
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
        <?php if (have_posts()) : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php
                // Start the Loop
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
            // Previous/next page navigation
            the_posts_pagination(array(
                'prev_text' => '<span class="screen-reader-text">' . __('Previous page', 'aqualuxe') . '</span>',
                'next_text' => '<span class="screen-reader-text">' . __('Next page', 'aqualuxe') . '</span>',
                'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'aqualuxe') . ' </span>',
            ));
            ?>

        <?php else : ?>
            <?php get_template_part('templates/parts/content', 'none'); ?>
        <?php endif; ?>
    </div>
</main><!-- #primary -->

<?php
get_sidebar();
get_footer();