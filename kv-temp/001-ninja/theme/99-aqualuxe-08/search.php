<?php
/**
 * The template for displaying search results pages
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php if (have_posts()) : ?>

        <header class="page-header">
            <div class="container">
                <h1 class="page-title">
                    <?php
                    /* translators: %s: search query. */
                    printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');
                    ?>
                </h1>
            </div>
        </header><!-- .page-header -->

        <div class="container">
            <div class="content-area">
                <div class="posts-container">
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
                    ?>
                </div>

                <?php
                the_posts_navigation(array(
                    'prev_text' => esc_html__('Older posts', 'aqualuxe'),
                    'next_text' => esc_html__('Newer posts', 'aqualuxe'),
                ));
                ?>
            </div>

            <?php get_sidebar(); ?>
        </div>

    <?php else : ?>

        <div class="container">
            <div class="content-area">
                <?php get_template_part('templates/content', 'none'); ?>
            </div>
        </div>

    <?php endif; ?>

</main><!-- #primary -->

<?php
get_footer();