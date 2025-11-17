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
 * @since 1.0.0
 */

get_header();
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex flex-wrap -mx-4">
        <main id="primary" class="site-main w-full lg:w-2/3 px-4">
            <?php
            if (have_posts()) :
                if (is_home() && !is_front_page()) :
                    ?>
                    <header>
                        <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                    </header>
                    <?php
                endif;

                // Get blog layout
                $blog_layout = get_theme_mod('aqualuxe_blog_layout', 'grid');
                $blog_columns = get_theme_mod('aqualuxe_blog_columns', '3');
                
                if ($blog_layout === 'grid') :
                    echo '<div class="aqualuxe-blog-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-' . esc_attr($blog_columns) . ' gap-6">';
                endif;

                /* Start the Loop */
                while (have_posts()) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     * If you want to override this in a child theme, then include a file
                     * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                     */
                    if ($blog_layout === 'grid') {
                        get_template_part('templates/content', 'grid');
                    } elseif ($blog_layout === 'list') {
                        get_template_part('templates/content', 'list');
                    } else {
                        get_template_part('templates/content', get_post_type());
                    }

                endwhile;

                if ($blog_layout === 'grid') :
                    echo '</div>';
                endif;

                aqualuxe_pagination();

            else :
                get_template_part('templates/content', 'none');
            endif;
            ?>
        </main><!-- #primary -->

        <?php get_sidebar(); ?>
    </div>
</div>

<?php
get_footer();