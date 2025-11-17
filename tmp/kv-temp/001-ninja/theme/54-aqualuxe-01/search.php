<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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
                    <h1 class="page-title text-3xl font-bold">
                        <?php
                        /* translators: %s: search query. */
                        printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span class="text-primary-600 dark:text-primary-400">' . get_search_query() . '</span>');
                        ?>
                    </h1>
                </header><!-- .page-header -->

                <?php
                /* Start the Loop */
                while (have_posts()) :
                    the_post();

                    /**
                     * Run the loop for the search to output the results.
                     * If you want to overload this in a child theme then include a file
                     * called content-search.php and that will be used instead.
                     */
                    get_template_part('templates/content', 'search');

                endwhile;

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