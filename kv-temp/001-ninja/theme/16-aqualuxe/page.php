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
        <?php
        // Breadcrumbs
        if (function_exists('aqualuxe_breadcrumbs')) :
            aqualuxe_breadcrumbs();
        endif;

        while (have_posts()) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden'); ?>>
                <?php if (has_post_thumbnail() && !get_post_meta(get_the_ID(), 'aqualuxe_hide_featured_image', true)) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('aqualuxe-featured', ['class' => 'w-full h-auto']); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content p-6 md:p-8">
                    <header class="entry-header mb-6">
                        <?php the_title('<h1 class="entry-title text-3xl md:text-4xl font-bold mb-4">', '</h1>'); ?>
                    </header>

                    <div class="prose prose-lg dark:prose-invert max-w-none">
                        <?php
                        the_content();

                        wp_link_pages(
                            array(
                                'before' => '<div class="page-links mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">' . esc_html__('Pages:', 'aqualuxe'),
                                'after'  => '</div>',
                            )
                        );
                        ?>
                    </div>
                </div>
            </article><!-- #post-<?php the_ID(); ?> -->

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>
    </div>
</main><!-- #main -->

<?php
get_footer();