<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package AquaLuxe
 */

get_header();
?>

<div class="container">
    <div class="row">
        <div id="primary" class="<?php echo esc_attr(aqualuxe_get_content_column_classes()); ?>">
            <main id="main" class="site-main" role="main">

            <?php if (have_posts()) : ?>

                <header class="page-header">
                    <h1 class="page-title">
                        <?php
                        /* translators: %s: search query. */
                        printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
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