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

<div class="bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <main id="primary" class="site-main">
            <section class="error-404 not-found text-center py-20 sm:py-32">
                <div class="max-w-md mx-auto">
                    <header class="page-header mb-8">
                        <h1 class="text-9xl font-extrabold text-blue-600 dark:text-blue-400 tracking-tighter">404</h1>
                        <p class="mt-4 text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100 tracking-tight"><?php esc_html_e( 'Oops! Page not found.', 'aqualuxe' ); ?></p>
                    </header><!-- .page-header -->

                    <div class="page-content text-gray-600 dark:text-gray-300">
                        <p class="mb-8"><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe' ); ?></p>
                        <?php get_search_form(); ?>
                        <div class="mt-10">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-block bg-blue-600 text-white font-semibold px-6 py-3 rounded-md hover:bg-blue-700 transition-colors duration-300">
                                <?php esc_html_e( 'Go to Homepage', 'aqualuxe' ); ?>
                            </a>
                        </div>
                    </div><!-- .page-content -->
                </div>
            </section><!-- .error-404 -->
        </main><!-- #main -->
    </div>
</div>

<?php
get_footer();

