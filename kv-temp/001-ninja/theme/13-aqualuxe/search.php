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

<main id="primary" class="site-main container mx-auto px-4 py-8">

    <?php if (have_posts()) : ?>

        <header class="page-header mb-8">
            <h1 class="page-title text-3xl font-bold text-primary dark:text-primary-dark">
                <?php
                /* translators: %s: search query. */
                printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span class="text-gray-900 dark:text-white">' . get_search_query() . '</span>');
                ?>
            </h1>
            <div class="mt-6">
                <?php get_search_form(); ?>
            </div>
        </header><!-- .page-header -->

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
        the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => sprintf(
                '%s <span class="nav-prev-text">%s</span>',
                '<svg class="w-4 h-4 inline-block" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>',
                __('Previous', 'aqualuxe')
            ),
            'next_text' => sprintf(
                '<span class="nav-next-text">%s</span> %s',
                __('Next', 'aqualuxe'),
                '<svg class="w-4 h-4 inline-block" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>'
            ),
            'class' => 'mt-8 flex justify-center',
        ));

    else :

        get_template_part('templates/content', 'none');

    endif;
    ?>

</main><!-- #main -->

<?php
get_sidebar();
get_footer();