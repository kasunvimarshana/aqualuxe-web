<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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
            if (have_posts()) :

                if (is_home() && !is_front_page()) :
                    ?>
                    <header>
                        <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                    </header>
                    <?php
                endif;

                /* Start the Loop */
                while (have_posts()) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    get_template_part('templates/content', get_post_type());

                endwhile;

                aqualuxe_pagination();

            else :

                get_template_part('templates/content', 'none');

            endif;
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