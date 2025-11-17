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

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-16">
        <section class="error-404 not-found text-center max-w-2xl mx-auto">
            <div class="error-image mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32 mx-auto text-primary" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                </svg>
            </div>
            
            <header class="page-header mb-8">
                <h1 class="page-title text-4xl md:text-5xl font-bold mb-4"><?php esc_html_e( '404', 'aqualuxe' ); ?></h1>
                <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></p>
            </header><!-- .page-header -->

            <div class="page-content prose max-w-none">
                <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?></p>

                <div class="search-form-container my-8">
                    <?php get_search_form(); ?>
                </div>

                <div class="error-404-widgets grid grid-cols-1 md:grid-cols-2 gap-8 text-left mt-12">
                    <div class="widget">
                        <h2 class="widget-title text-xl font-bold mb-4"><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
                        <ul class="space-y-2">
                            <?php
                            wp_get_archives( array(
                                'type'      => 'postbypost',
                                'limit'     => 5,
                                'format'    => 'html',
                            ) );
                            ?>
                        </ul>
                    </div>

                    <div class="widget">
                        <h2 class="widget-title text-xl font-bold mb-4"><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h2>
                        <ul class="space-y-2">
                            <?php
                            wp_list_categories( array(
                                'orderby'    => 'count',
                                'order'      => 'DESC',
                                'show_count' => 1,
                                'title_li'   => '',
                                'number'     => 5,
                            ) );
                            ?>
                        </ul>
                    </div>
                </div>

                <div class="back-home text-center mt-12">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <?php esc_html_e( 'Back to Home', 'aqualuxe' ); ?>
                    </a>
                </div>
            </div><!-- .page-content -->
        </section><!-- .error-404 -->
    </div>
</main><!-- #main -->

<?php
get_footer();