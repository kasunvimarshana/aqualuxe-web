<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main py-12">
    <div class="container-fluid">
        <div class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-2/3 lg:pr-8">
                <?php if (have_posts()) : ?>

                    <header class="page-header mb-8 bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none p-6">
                        <?php
                        // Breadcrumbs
                        if (function_exists('aqualuxe_breadcrumbs')) :
                            aqualuxe_breadcrumbs();
                        endif;
                        
                        the_archive_title('<h1 class="page-title text-3xl md:text-4xl font-bold mb-4">', '</h1>');
                        the_archive_description('<div class="archive-description prose dark:prose-invert">', '</div>');
                        ?>
                    </header><!-- .page-header -->

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <?php
                        /* Start the Loop */
                        while (have_posts()) :
                            the_post();

                            /*
                             * Include the Post-Type-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                             */
                            get_template_part('template-parts/content', get_post_type());

                        endwhile;
                        ?>
                    </div>

                    <div class="pagination mt-8">
                        <?php
                        the_posts_pagination(
                            array(
                                'mid_size'  => 2,
                                'prev_text' => sprintf(
                                    '<span class="nav-prev-text">%s</span> %s',
                                    esc_html__('Previous', 'aqualuxe'),
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>'
                                ),
                                'next_text' => sprintf(
                                    '<span class="nav-next-text">%s</span> %s',
                                    esc_html__('Next', 'aqualuxe'),
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>'
                                ),
                            )
                        );
                        ?>
                    </div>

                <?php else :

                    get_template_part('template-parts/content', 'none');

                endif;
                ?>
            </div>

            <div class="w-full lg:w-1/3 mt-8 lg:mt-0">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();