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
    get_template_part('template-parts/components/page-header');
    ?>

    <div class="container">
        <div class="row">
            <?php
            // Determine layout
            $layout = aqualuxe_get_page_layout();
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
                <?php
                while (have_posts()) :
                    the_post();

                    get_template_part('template-parts/content/content', 'page');

                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;

                endwhile; // End of the loop.
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