<?php
/**
 * The template for displaying archive pages
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
                <?php if (have_posts()) : ?>

                    <header class="page-header mb-8">
                        <?php
                        the_archive_title('<h1 class="page-title text-3xl font-bold">', '</h1>');
                        the_archive_description('<div class="archive-description mt-4 prose">', '</div>');
                        ?>
                    </header><!-- .page-header -->

                    <div class="archive-posts">
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

                    <?php
                    the_posts_pagination(
                        array(
                            'prev_text' => '<span class="screen-reader-text">' . __('Previous page', 'aqualuxe') . '</span>',
                            'next_text' => '<span class="screen-reader-text">' . __('Next page', 'aqualuxe') . '</span>',
                            'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'aqualuxe') . ' </span>',
                        )
                    );

                else :

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