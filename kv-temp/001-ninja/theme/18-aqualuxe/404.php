<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main py-12">
    <div class="container-fluid">
        <section class="error-404 not-found bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none p-6 md:p-8 text-center">
            <div class="max-w-3xl mx-auto">
                <div class="error-image mb-8">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/404-fish.svg'); ?>" alt="<?php esc_attr_e('404 Error', 'aqualuxe'); ?>" class="max-w-full h-auto mx-auto" width="400" height="300">
                </div>

                <header class="page-header mb-6">
                    <h1 class="page-title text-4xl md:text-5xl font-bold mb-4"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h1>
                    <p class="text-xl text-gray-600 dark:text-gray-300"><?php esc_html_e('It looks like you\'ve ventured into uncharted waters.', 'aqualuxe'); ?></p>
                </header><!-- .page-header -->

                <div class="page-content prose dark:prose-invert mx-auto">
                    <p><?php esc_html_e('The page you were looking for doesn\'t exist, no longer exists, or has been moved to a different location. Don\'t worry, you can find your way back to shore using the options below.', 'aqualuxe'); ?></p>

                    <div class="error-actions mt-8 flex flex-col md:flex-row justify-center space-y-4 md:space-y-0 md:space-x-4">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <?php esc_html_e('Back to Home', 'aqualuxe'); ?>
                        </a>
                        
                        <?php if (class_exists('WooCommerce')) : ?>
                            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <?php esc_html_e('Visit Shop', 'aqualuxe'); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="search-form-container mt-8 max-w-lg mx-auto">
                        <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Search for something else', 'aqualuxe'); ?></h3>
                        <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                            <div class="flex">
                                <input type="search" class="w-full rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:ring-primary focus:border-primary" placeholder="<?php echo esc_attr_x('Search...', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                                <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-6 rounded-r-md transition-colors duration-300">
                                    <?php esc_html_e('Search', 'aqualuxe'); ?>
                                </button>
                            </div>
                        </form>
                    </div>

                    <?php
                    // Display popular posts if available
                    if (function_exists('aqualuxe_popular_posts')) :
                        echo '<div class="popular-posts mt-12">';
                        echo '<h3 class="text-xl font-bold mb-6">' . esc_html__('Popular Posts', 'aqualuxe') . '</h3>';
                        aqualuxe_popular_posts(4);
                        echo '</div>';
                    endif;
                    ?>
                </div><!-- .page-content -->
            </div>
        </section><!-- .error-404 -->
    </div>
</main><!-- #main -->

<?php
get_footer();