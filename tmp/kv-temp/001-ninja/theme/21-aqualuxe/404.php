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
        <div class="flex flex-col items-center justify-center text-center">
            <div class="error-404 not-found">
                <div class="error-image mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" width="180" height="180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-primary-500 dark:text-primary-400 mx-auto">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                        <circle cx="12" cy="12" r="10" stroke-dasharray="40" stroke-dashoffset="0" class="animate-pulse"></circle>
                    </svg>
                </div>

                <header class="page-header mb-8">
                    <h1 class="page-title text-4xl md:text-5xl font-display font-bold text-dark-800 dark:text-white mb-4">
                        <?php esc_html_e('404', 'aqualuxe'); ?>
                    </h1>
                    <h2 class="text-2xl md:text-3xl font-bold text-dark-700 dark:text-dark-100 mb-4">
                        <?php esc_html_e('Page Not Found', 'aqualuxe'); ?>
                    </h2>
                    <p class="text-lg text-dark-600 dark:text-dark-200 max-w-2xl mx-auto">
                        <?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'aqualuxe'); ?>
                    </p>
                </header><!-- .page-header -->

                <div class="page-content">
                    <div class="search-form-container max-w-md mx-auto mb-12">
                        <?php get_search_form(); ?>
                    </div>

                    <div class="error-suggestions grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                        <div class="suggestion-box bg-white dark:bg-dark-700 rounded-xl shadow-soft p-6">
                            <h3 class="text-xl font-bold text-dark-800 dark:text-white mb-4">
                                <?php esc_html_e('Recent Posts', 'aqualuxe'); ?>
                            </h3>
                            <ul class="space-y-3">
                                <?php
                                $recent_posts = wp_get_recent_posts(array(
                                    'numberposts' => 5,
                                    'post_status' => 'publish'
                                ));
                                
                                foreach ($recent_posts as $post) :
                                ?>
                                    <li>
                                        <a href="<?php echo get_permalink($post['ID']); ?>" class="flex items-center text-dark-700 dark:text-dark-200 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                                <path d="M9 18l6-6-6-6"></path>
                                            </svg>
                                            <?php echo $post['post_title']; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="suggestion-box bg-white dark:bg-dark-700 rounded-xl shadow-soft p-6">
                            <h3 class="text-xl font-bold text-dark-800 dark:text-white mb-4">
                                <?php esc_html_e('Popular Categories', 'aqualuxe'); ?>
                            </h3>
                            <ul class="space-y-3">
                                <?php
                                $categories = get_categories(array(
                                    'orderby' => 'count',
                                    'order' => 'DESC',
                                    'number' => 5
                                ));
                                
                                foreach ($categories as $category) :
                                ?>
                                    <li>
                                        <a href="<?php echo get_category_link($category->term_id); ?>" class="flex items-center text-dark-700 dark:text-dark-200 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                            </svg>
                                            <?php echo $category->name; ?>
                                            <span class="ml-auto text-sm text-dark-500 dark:text-dark-400">(<?php echo $category->count; ?>)</span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <div class="back-home text-center mt-12">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            <?php esc_html_e('Back to Homepage', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div><!-- .page-content -->
            </div><!-- .error-404 -->
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();