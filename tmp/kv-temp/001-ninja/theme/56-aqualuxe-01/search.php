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

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-12">
        <?php if ( have_posts() ) : ?>

            <header class="page-header mb-8">
                <h1 class="page-title text-3xl md:text-4xl font-bold mb-4">
                    <?php
                    /* translators: %s: search query. */
                    printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-primary">' . get_search_query() . '</span>' );
                    ?>
                </h1>
                
                <?php
                // Display search form
                get_search_form();
                ?>
            </header><!-- .page-header -->

            <div class="search-results-count mb-8 text-gray-600 dark:text-gray-400">
                <?php
                global $wp_query;
                printf(
                    /* translators: %d: number of search results */
                    _n(
                        'Found %d result for your search.',
                        'Found %d results for your search.',
                        $wp_query->found_posts,
                        'aqualuxe'
                    ),
                    $wp_query->found_posts
                );
                ?>
            </div>

            <div class="search-results-grid grid grid-cols-1 gap-6">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();

                    /**
                     * Run the loop for the search to output the results.
                     * If you want to overload this in a child theme then include a file
                     * called content-search.php and that will be used instead.
                     */
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 transition-all duration-300 hover:shadow-xl'); ?>>
                        <header class="entry-header mb-4">
                            <?php the_title( sprintf( '<h2 class="entry-title text-xl md:text-2xl font-bold mb-2"><a href="%s" rel="bookmark" class="hover:text-primary transition-colors duration-300">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

                            <?php if ( 'post' === get_post_type() ) : ?>
                                <div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
                                    <?php
                                    echo '<span class="date mr-4">';
                                    echo '<span class="inline-block mr-1">';
                                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />';
                                    echo '</svg>';
                                    echo '</span>';
                                    echo '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">';
                                    echo esc_html( get_the_date() );
                                    echo '</time>';
                                    echo '</span>';
                                    
                                    echo '<span class="post-type">';
                                    echo '<span class="inline-block mr-1">';
                                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />';
                                    echo '</svg>';
                                    echo '</span>';
                                    echo esc_html__( 'Blog Post', 'aqualuxe' );
                                    echo '</span>';
                                    ?>
                                </div><!-- .entry-meta -->
                            <?php else : ?>
                                <div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
                                    <?php
                                    echo '<span class="post-type">';
                                    echo '<span class="inline-block mr-1">';
                                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />';
                                    echo '</svg>';
                                    echo '</span>';
                                    echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name );
                                    echo '</span>';
                                    ?>
                                </div>
                            <?php endif; ?>
                        </header><!-- .entry-header -->

                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="entry-thumbnail mb-4">
                                <a href="<?php the_permalink(); ?>" class="block">
                                    <img src="<?php the_post_thumbnail_url( 'medium' ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-48 object-cover rounded-lg">
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="entry-summary mb-4 text-gray-600 dark:text-gray-400">
                            <?php the_excerpt(); ?>
                        </div><!-- .entry-summary -->

                        <footer class="entry-footer">
                            <a href="<?php the_permalink(); ?>" class="inline-block bg-primary hover:bg-primary-dark text-white font-semibold py-2 px-4 rounded-md transition-colors duration-300">
                                <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                            </a>
                        </footer><!-- .entry-footer -->
                    </article><!-- #post-<?php the_ID(); ?> -->
                    <?php
                endwhile;
                ?>
            </div>

            <div class="pagination-container mt-12">
                <?php
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>',
                    'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
                ) );
                ?>
            </div>

        <?php else : ?>

            <header class="page-header mb-8">
                <h1 class="page-title text-3xl md:text-4xl font-bold mb-4">
                    <?php
                    /* translators: %s: search query. */
                    printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span class="text-primary">' . get_search_query() . '</span>' );
                    ?>
                </h1>
            </header><!-- .page-header -->

            <div class="no-results bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                
                <h2 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Nothing Found', 'aqualuxe' ); ?></h2>
                
                <div class="text-gray-600 dark:text-gray-400 mb-6">
                    <?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe' ); ?>
                </div>
                
                <?php get_search_form(); ?>
            </div>

        <?php endif; ?>
    </div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer();