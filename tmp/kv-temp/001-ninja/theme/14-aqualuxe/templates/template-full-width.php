<?php
/**
 * Template Name: Full Width Page
 *
 * This is the template that displays a page without sidebar.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main full-width">

    <?php
    while (have_posts()) :
        the_post();
    ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php if (!get_field('hide_title')) : ?>
                <header class="entry-header bg-gradient-to-r from-blue-900 to-teal-800 text-white py-20">
                    <div class="container mx-auto px-4">
                        <h1 class="entry-title text-4xl md:text-5xl font-bold">
                            <?php the_title(); ?>
                        </h1>
                        <?php if (get_field('page_subtitle')) : ?>
                            <p class="text-xl text-blue-100 mt-4">
                                <?php echo esc_html(get_field('page_subtitle')); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </header><!-- .entry-header -->
            <?php endif; ?>

            <?php if (has_post_thumbnail() && !get_field('hide_featured_image')) : ?>
                <div class="post-thumbnail">
                    <?php the_post_thumbnail('full', array('class' => 'w-full h-auto')); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content">
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

            <?php if (get_edit_post_link() && !get_field('hide_edit_link')) : ?>
                <footer class="entry-footer container mx-auto px-4 py-4">
                    <?php
                    edit_post_link(
                        sprintf(
                            wp_kses(
                                /* translators: %s: Name of current post. Only visible to screen readers */
                                __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                                array(
                                    'span' => array(
                                        'class' => array(),
                                    ),
                                )
                            ),
                            wp_kses_post(get_the_title())
                        ),
                        '<span class="edit-link text-sm text-gray-600 dark:text-gray-400">',
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
        ?>

    <?php endwhile; // End of the loop. ?>

</main><!-- #main -->

<?php
get_footer();