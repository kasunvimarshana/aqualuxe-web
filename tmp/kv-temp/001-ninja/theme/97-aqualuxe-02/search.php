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
<div class="bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <main id="primary" class="site-main">

            <?php if ( have_posts() ) : ?>

                <header class="page-header mb-12 border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                        <?php
                        /* translators: %s: search query. */
                        printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-blue-600 dark:text-blue-400">' . get_search_query() . '</span>' );
                        ?>
                    </h1>
                </header><!-- .page-header -->

                <div class="space-y-12">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();

                    /**
                     * Run the loop for the search to output the results.
                     * If you want to overload this in a child theme then include a file
                     * called content-search.php and that will be used instead.
                     */
                    get_template_part( 'templates/content', 'search' );

                endwhile;
                ?>
                </div>
                <?php

                the_posts_navigation(
                    array(
                        'prev_text' => '<span class="text-lg">&larr;</span> ' . esc_html__( 'Older posts', 'aqualuxe' ),
                        'next_text' => esc_html__( 'Newer posts', 'aqualuxe' ) . ' <span class="text-lg">&rarr;</span>',
                        'screen_reader_text' => esc_html__( 'Posts navigation', 'aqualuxe' ),
                        'class' => 'my-12 flex justify-between text-lg font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200',
                    )
                );

            else :

                get_template_part( 'templates/content', 'none' );

            endif;
            ?>

        </main><!-- #main -->
    </div>
</div>
<?php
get_footer();
