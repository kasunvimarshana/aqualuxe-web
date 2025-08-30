<?php
/**
 * The template for displaying tag pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    // Page Header
    get_template_part('templates/parts/page-header');
    ?>

    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 <?php echo is_active_sidebar('sidebar-1') ? 'lg:grid-cols-3 xl:grid-cols-4 gap-8' : ''; ?>">
            <div class="<?php echo is_active_sidebar('sidebar-1') ? 'lg:col-span-2 xl:col-span-3' : ''; ?>">
                <?php if (have_posts()) : ?>
                    <div class="tag-description mb-8 prose dark:prose-invert max-w-none">
                        <?php the_archive_description(); ?>
                    </div>

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
                            get_template_part('templates/parts/content', get_post_type());

                        endwhile;
                        ?>
                    </div>

                    <div class="pagination-container mt-12">
                        <?php
                        the_posts_pagination(array(
                            'mid_size'  => 2,
                            'prev_text' => sprintf(
                                '<span class="nav-prev-text">%s</span> %s',
                                __('Previous', 'aqualuxe'),
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block w-4 h-4"><path d="M15 18l-6-6 6-6"/></svg>'
                            ),
                            'next_text' => sprintf(
                                '<span class="nav-next-text">%s</span> %s',
                                __('Next', 'aqualuxe'),
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block w-4 h-4"><path d="M9 18l6-6-6-6"/></svg>'
                            ),
                        ));
                        ?>
                    </div>

                <?php else : ?>

                    <?php get_template_part('templates/parts/content', 'none'); ?>

                <?php endif; ?>
            </div>

            <?php if (is_active_sidebar('sidebar-1')) : ?>
                <div class="sidebar-container">
                    <?php get_sidebar(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</main><!-- #main -->

<?php
get_footer();