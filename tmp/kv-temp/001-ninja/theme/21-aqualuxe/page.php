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
                <?php
                while (have_posts()) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-dark-700 rounded-xl shadow-soft p-6 md:p-8'); ?>>
                        <?php if (has_post_thumbnail() && !get_theme_mod('page_hide_featured_image', false)) : ?>
                            <div class="featured-image mb-8 -mx-6 md:-mx-8 -mt-6 md:-mt-8 rounded-t-xl overflow-hidden">
                                <?php the_post_thumbnail('full', array('class' => 'w-full h-auto')); ?>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content prose prose-lg dark:prose-invert max-w-none">
                            <?php
                            the_content();

                            wp_link_pages(
                                array(
                                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                                    'after'  => '</div>',
                                )
                            );
                            ?>
                        </div><!-- .entry-content -->

                        <?php if (get_edit_post_link()) : ?>
                            <footer class="entry-footer mt-8 pt-6 border-t border-gray-200 dark:border-dark-600">
                                <?php
                                edit_post_link(
                                    sprintf(
                                        wp_kses(
                                            /* translators: %s: Name of current post. Only visible to screen readers */
                                            __('Edit <span class="sr-only">%s</span>', 'aqualuxe'),
                                            array(
                                                'span' => array(
                                                    'class' => array(),
                                                ),
                                            )
                                        ),
                                        wp_kses_post(get_the_title())
                                    ),
                                    '<span class="edit-link inline-flex items-center text-sm text-dark-500 dark:text-dark-400 hover:text-primary-600 dark:hover:text-primary-400">',
                                    '</span>'
                                );
                                ?>
                            </footer><!-- .entry-footer -->
                        <?php endif; ?>
                    </article><!-- #post-<?php the_ID(); ?> -->

                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;

                endwhile; // End of the loop.
                ?>
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