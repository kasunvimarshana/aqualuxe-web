<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Archive Header
    get_template_part('template-parts/components/archive-header');
    ?>

    <div class="container">
        <div class="row">
            <?php
            // Determine layout
            $layout = aqualuxe_get_archive_layout();
            $content_class = 'content-area';
            
            if ($layout === 'left-sidebar') {
                $content_class .= ' col-lg-8 col-lg-push-4';
            } elseif ($layout === 'right-sidebar') {
                $content_class .= ' col-lg-8';
            } else {
                $content_class .= ' col-lg-12';
            }
            ?>

            <div class="<?php echo esc_attr($content_class); ?>">
                <?php if (have_posts()) : ?>
                    <div class="archive-posts">
                        <div class="row">
                            <?php
                            /* Start the Loop */
                            while (have_posts()) :
                                the_post();

                                /*
                                 * Include the Post-Type-specific template for the content.
                                 * If you want to override this in a child theme, then include a file
                                 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                                 */
                                get_template_part('template-parts/content/content', get_post_type());

                            endwhile;
                            ?>
                        </div><!-- .row -->
                    </div><!-- .archive-posts -->

                    <?php
                    // Pagination
                    aqualuxe_pagination();

                else :

                    get_template_part('template-parts/content/content', 'none');

                endif;
                ?>
            </div><!-- .content-area -->

            <?php
            // Display sidebar if layout is not full-width
            if ($layout !== 'full-width') {
                $sidebar_class = 'widget-area';
                
                if ($layout === 'left-sidebar') {
                    $sidebar_class .= ' col-lg-4 col-lg-pull-8';
                } else {
                    $sidebar_class .= ' col-lg-4';
                }
                ?>
                <aside id="secondary" class="<?php echo esc_attr($sidebar_class); ?>">
                    <?php get_sidebar(); ?>
                </aside><!-- .widget-area -->
                <?php
            }
            ?>
        </div><!-- .row -->
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();