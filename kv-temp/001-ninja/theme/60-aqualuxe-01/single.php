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

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <div class="<?php echo esc_attr(aqualuxe_get_container_class()); ?>">
            <div class="row">
                <div class="<?php echo esc_attr(aqualuxe_get_content_class()); ?>">
                    <?php
                    while (have_posts()) :
                        the_post();

                        get_template_part('template-parts/content', 'single');

                        // If comments are open or we have at least one comment, load up the comment template.
                        if (comments_open() || get_comments_number()) :
                            comments_template();
                        endif;

                    endwhile; // End of the loop.
                    ?>
                </div>
                
                <?php if (aqualuxe_has_sidebar()) : ?>
                    <div class="<?php echo esc_attr(aqualuxe_get_sidebar_class()); ?>">
                        <?php get_sidebar(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();