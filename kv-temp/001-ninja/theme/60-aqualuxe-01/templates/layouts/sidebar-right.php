<?php
/**
 * Template Name: Right Sidebar
 * Template Post Type: post, page, product
 *
 * A template for displaying content with a sidebar on the right
 *
 * @package AquaLuxe
 */

get_header();
?>

<div id="primary" class="content-area right-sidebar">
    <main id="main" class="site-main">
        <div class="<?php echo esc_attr(aqualuxe_get_container_class()); ?>">
            <div class="row">
                <div class="<?php echo esc_attr(aqualuxe_get_content_class('right')); ?>">
                    <?php
                    while (have_posts()) :
                        the_post();

                        if (is_singular('page')) {
                            get_template_part('templates/content/content', 'page');
                        } elseif (is_singular('post')) {
                            get_template_part('templates/content/content', 'single');
                        } elseif (aqualuxe_is_woocommerce_active() && is_singular('product')) {
                            wc_get_template_part('content', 'single-product');
                        } else {
                            get_template_part('templates/content/content', get_post_type());
                        }

                        // If comments are open or we have at least one comment, load up the comment template.
                        if (comments_open() || get_comments_number()) :
                            comments_template();
                        endif;

                    endwhile; // End of the loop.
                    ?>
                </div>
                
                <div class="<?php echo esc_attr(aqualuxe_get_sidebar_class()); ?>">
                    <?php 
                    // Determine which sidebar to display
                    if (aqualuxe_is_woocommerce_active() && (is_woocommerce() || is_singular('product'))) {
                        get_sidebar('shop');
                    } else {
                        get_sidebar();
                    }
                    ?>
                </div>
            </div>
        </div>
    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();