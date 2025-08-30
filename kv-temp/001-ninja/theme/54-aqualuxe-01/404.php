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

<div class="container mx-auto px-4 py-16">
    <div class="flex flex-wrap -mx-4">
        <main id="primary" class="site-main w-full px-4">

            <section class="error-404 not-found text-center">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 md:p-16">
                    <header class="page-header mb-8">
                        <h1 class="page-title text-5xl md:text-7xl font-bold text-primary-600 dark:text-primary-400 mb-4">404</h1>
                        <h2 class="text-2xl md:text-3xl font-semibold mb-4"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'aqualuxe'); ?></h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400"><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe'); ?></p>
                    </header><!-- .page-header -->

                    <div class="page-content">
                        <div class="max-w-md mx-auto mb-8">
                            <?php get_search_form(); ?>
                        </div>

                        <div class="flex flex-col md:flex-row justify-center gap-8">
                            <div class="w-full md:w-1/2">
                                <h3 class="text-xl font-semibold mb-4"><?php esc_html_e('Recent Posts', 'aqualuxe'); ?></h3>
                                <ul class="space-y-2">
                                    <?php
                                    $recent_posts = wp_get_recent_posts([
                                        'numberposts' => 5,
                                        'post_status' => 'publish',
                                    ]);
                                    
                                    foreach ($recent_posts as $post) :
                                    ?>
                                        <li>
                                            <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                                <?php echo esc_html(get_the_title($post['ID'])); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <div class="w-full md:w-1/2">
                                <h3 class="text-xl font-semibold mb-4"><?php esc_html_e('Popular Categories', 'aqualuxe'); ?></h3>
                                <ul class="space-y-2">
                                    <?php
                                    wp_list_categories([
                                        'orderby' => 'count',
                                        'order' => 'DESC',
                                        'show_count' => true,
                                        'title_li' => '',
                                        'number' => 5,
                                    ]);
                                    ?>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-12">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 px-6 rounded-md transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <?php esc_html_e('Back to Homepage', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div><!-- .page-content -->
                </div>
            </section><!-- .error-404 -->

        </main><!-- #primary -->
    </div>
</div>

<?php
get_footer();