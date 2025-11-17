<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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
        <div class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-2/3 lg:pr-8">
                <?php if (have_posts()) : ?>

                    <header class="page-header mb-8 bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none p-6">
                        <h1 class="page-title text-3xl md:text-4xl font-bold mb-4">
                            <?php
                            /* translators: %s: search query. */
                            printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span class="text-primary">' . get_search_query() . '</span>');
                            ?>
                        </h1>

                        <!-- Search form -->
                        <div class="search-form-container mt-6">
                            <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                                <div class="flex">
                                    <input type="search" class="w-full rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:ring-primary focus:border-primary" placeholder="<?php echo esc_attr_x('Search...', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                                    <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-6 rounded-r-md transition-colors duration-300">
                                        <?php esc_html_e('Search', 'aqualuxe'); ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </header><!-- .page-header -->

                    <div class="search-results">
                        <?php
                        /* Start the Loop */
                        while (have_posts()) :
                            the_post();

                            /**
                             * Run the loop for the search to output the results.
                             * If you want to overload this in a child theme then include a file
                             * called content-search.php and that will be used instead.
                             */
                            get_template_part('template-parts/content', 'search');

                        endwhile;
                        ?>
                    </div>

                    <div class="pagination mt-8">
                        <?php
                        the_posts_pagination(
                            array(
                                'mid_size'  => 2,
                                'prev_text' => sprintf(
                                    '<span class="nav-prev-text">%s</span> %s',
                                    esc_html__('Previous', 'aqualuxe'),
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>'
                                ),
                                'next_text' => sprintf(
                                    '<span class="nav-next-text">%s</span> %s',
                                    esc_html__('Next', 'aqualuxe'),
                                    '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>'
                                ),
                            )
                        );
                        ?>
                    </div>

                <?php else : ?>

                    <div class="no-results bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none p-6 md:p-8">
                        <header class="page-header mb-6">
                            <h1 class="page-title text-3xl md:text-4xl font-bold mb-4">
                                <?php esc_html_e('Nothing Found', 'aqualuxe'); ?>
                            </h1>
                        </header><!-- .page-header -->

                        <div class="page-content prose dark:prose-invert">
                            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe'); ?></p>
                            
                            <div class="search-form-container mt-6">
                                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                                    <div class="flex">
                                        <input type="search" class="w-full rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 focus:ring-primary focus:border-primary" placeholder="<?php echo esc_attr_x('Search...', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                                        <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-6 rounded-r-md transition-colors duration-300">
                                            <?php esc_html_e('Search', 'aqualuxe'); ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div><!-- .page-content -->
                    </div>

                <?php endif; ?>
            </div>

            <div class="w-full lg:w-1/3 mt-8 lg:mt-0">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();