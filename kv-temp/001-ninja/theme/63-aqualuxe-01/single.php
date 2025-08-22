<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();
?>

<div class="container">
    <div class="row">
        <div id="primary" class="<?php echo esc_attr(aqualuxe_get_content_column_classes()); ?>">
            <main id="main" class="site-main" role="main">

            <?php
            while (have_posts()) :
                the_post();

                get_template_part('templates/content', get_post_type());

                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;

            endwhile; // End of the loop.
            ?>

            </main><!-- #main -->
        </div><!-- #primary -->

        <?php if (aqualuxe_has_sidebar()) : ?>
            <aside id="secondary" class="<?php echo esc_attr(aqualuxe_get_sidebar_column_classes()); ?>">
                <?php get_sidebar(); ?>
            </aside><!-- #secondary -->
        <?php endif; ?>
    </div><!-- .row -->
</div><!-- .container -->

<?php
get_footer();