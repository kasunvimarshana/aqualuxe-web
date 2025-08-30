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
    <div class="container mx-auto px-4 py-16 md:py-24">
        <section class="error-404 not-found text-center">
            <div class="max-w-3xl mx-auto">
                <div class="error-404-image mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32 mx-auto text-primary" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                
                <header class="page-header mb-8">
                    <h1 class="page-title text-6xl md:text-8xl font-bold mb-4">404</h1>
                    <h2 class="text-2xl md:text-3xl font-semibold mb-4"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h2>
                    <div class="text-gray-600 dark:text-gray-400 text-lg mb-8">
                        <?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe' ); ?>
                    </div>
                </header><!-- .page-header -->

                <div class="page-content">
                    <?php get_search_form(); ?>

                    <div class="error-404-actions mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="error-404-recent bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                            <h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h3>
                            <ul class="space-y-3">
                                <?php
                                wp_get_archives( array(
                                    'type'      => 'postbypost',
                                    'limit'     => 5,
                                    'format'    => 'html',
                                    'before'    => '<li class="hover:text-primary transition-colors duration-300">',
                                    'after'     => '</li>',
                                ) );
                                ?>
                            </ul>
                        </div>

                        <div class="error-404-categories bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                            <h3 class="text-xl font-bold mb-4"><?php esc_html_e( 'Most Used Categories', 'aqualuxe' ); ?></h3>
                            <ul class="space-y-3">
                                <?php
                                wp_list_categories( array(
                                    'orderby'    => 'count',
                                    'order'      => 'DESC',
                                    'show_count' => 1,
                                    'title_li'   => '',
                                    'number'     => 5,
                                    'before'     => '<li class="hover:text-primary transition-colors duration-300">',
                                    'after'      => '</li>',
                                ) );
                                ?>
                            </ul>
                        </div>
                    </div>

                    <div class="error-404-home-button mt-12">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-block bg-primary hover:bg-primary-dark text-white font-semibold py-3 px-6 rounded-md transition-colors duration-300">
                            <?php esc_html_e( 'Back to Homepage', 'aqualuxe' ); ?>
                        </a>
                    </div>
                </div><!-- .page-content -->
            </div>
        </section><!-- .error-404 -->
    </div>
</main><!-- #main -->

<?php
get_footer();