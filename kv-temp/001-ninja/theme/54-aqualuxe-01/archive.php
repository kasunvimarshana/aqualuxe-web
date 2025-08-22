<?php
/**
 * The template for displaying archive pages
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

            <?php if (have_posts()) : ?>

                <header class="page-header mb-8">
                    <?php
                    the_archive_title('<h1 class="page-title text-3xl font-bold mb-4">', '</h1>');
                    the_archive_description('<div class="archive-description prose dark:prose-invert max-w-none">', '</div>');
                    ?>
                </header><!-- .page-header -->

                <?php
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