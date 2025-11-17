<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main container mx-auto px-4 py-16">

    <section class="error-404 not-found text-center max-w-2xl mx-auto">
        <div class="error-image mb-8">
            <svg class="w-48 h-48 mx-auto text-primary dark:text-primary-dark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor">
                <path d="M256 0C114.6 0 0 114.6 0 256s114.6 256 256 256 256-114.6 256-256S397.4 0 256 0zm0 464c-114.7 0-208-93.31-208-208S141.3 48 256 48s208 93.31 208 208-93.3 208-208 208zm-80-248c17.67 0 32-14.33 32-32s-14.33-32-32-32-32 14.33-32 32 14.33 32 32 32zm160 0c17.67 0 32-14.33 32-32s-14.33-32-32-32-32 14.33-32 32 14.33 32 32 32zm-80 140c53.02 0 99.21-33.55 117-80H139c17.8 46.45 63.99 80 117 80z"/>
            </svg>
        </div>

        <header class="page-header mb-8">
            <h1 class="page-title text-4xl font-bold text-gray-900 dark:text-white mb-4"><?php esc_html_e('Oops! Page Not Found', 'aqualuxe'); ?></h1>
            <p class="text-xl text-gray-600 dark:text-gray-400"><?php esc_html_e('It looks like nothing was found at this location.', 'aqualuxe'); ?></p>
        </header><!-- .page-header -->

        <div class="page-content prose dark:prose-invert max-w-none mb-8">
            <p><?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable. Here are some helpful links to get you back on track:', 'aqualuxe'); ?></p>
        </div><!-- .page-content -->

        <div class="error-actions flex flex-col md:flex-row justify-center gap-4 mb-12">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-dark transition-colors duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <?php esc_html_e('Back to Home', 'aqualuxe'); ?>
            </a>
            <?php if (class_exists('WooCommerce')) : ?>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-primary-dark transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <?php esc_html_e('Visit Shop', 'aqualuxe'); ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="search-form-container max-w-md mx-auto">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><?php esc_html_e('Search Our Site', 'aqualuxe'); ?></h2>
            <?php get_search_form(); ?>
        </div>

    </section><!-- .error-404 -->

</main><!-- #main -->

<?php
get_footer();