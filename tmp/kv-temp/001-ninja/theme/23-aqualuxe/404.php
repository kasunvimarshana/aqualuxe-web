<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="container mx-auto px-4 py-16 md:py-24">
    <div class="max-w-3xl mx-auto text-center">
        <div class="error-404 not-found">
            <header class="page-header mb-8">
                <h1 class="page-title text-5xl md:text-6xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                    <?php esc_html_e( '404', 'aqualuxe' ); ?>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-400">
                    <?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?>
                </p>
            </header><!-- .page-header -->

            <div class="page-content prose dark:prose-invert max-w-none">
                <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>

                <div class="search-form-container my-8">
                    <?php get_search_form(); ?>
                </div>

                <div class="error-suggestions grid md:grid-cols-2 gap-8 mt-12">
                    <div class="recent-posts">
                        <h2 class="text-xl font-bold mb-4"><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
                        <ul class="space-y-2">
                            <?php
                            wp_get_archives( array(
                                'type'      => 'postbypost',
                                'limit'     => 5,
                                'format'    => 'html',
                                'before'    => '<li class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">',
                                'after'     => '</li>',
                            ) );
                            ?>
                        </ul>
                    </div>

                    <div class="categories">
                        <h2 class="text-xl font-bold mb-4"><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
                        <ul class="space-y-2">
                            <?php
                            wp_list_categories( array(
                                'orderby'    => 'count',
                                'order'      => 'DESC',
                                'show_count' => 1,
                                'title_li'   => '',
                                'number'     => 5,
                                'before'     => '<li class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">',
                                'after'      => '</li>',
                            ) );
                            ?>
                        </ul>
                    </div>
                </div>

                <div class="back-home text-center mt-12">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <?php esc_html_e( 'Back to Homepage', 'aqualuxe' ); ?>
                    </a>
                </div>
            </div><!-- .page-content -->
        </div><!-- .error-404 -->
    </div>
</main><!-- #main -->

<?php
get_footer();