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

    <header class="page-header bg-primary-600 dark:bg-primary-800 text-white py-12 md:py-16">
        <div class="container mx-auto px-4">
            <h1 class="page-title text-3xl md:text-4xl font-display font-bold mb-4">
                <?php
                /* translators: %s: search query. */
                printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span class="search-query">' . get_search_query() . '</span>');
                ?>
            </h1>
            <div class="search-form-container max-w-lg">
                <?php get_search_form(); ?>
            </div>
        </div>
    </header><!-- .page-header -->

    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 <?php echo is_active_sidebar('sidebar-1') ? 'lg:grid-cols-3 xl:grid-cols-4 gap-8' : ''; ?>">
            <div class="<?php echo is_active_sidebar('sidebar-1') ? 'lg:col-span-2 xl:col-span-3' : ''; ?>">
                <?php if (have_posts()) : ?>
                    <div class="search-results-count mb-8 text-dark-600 dark:text-dark-300">
                        <?php
                        $found_posts = $wp_query->found_posts;
                        printf(
                            /* translators: %d: number of search results */
                            _n(
                                'Found %d result for your search.',
                                'Found %d results for your search.',
                                $found_posts,
                                'aqualuxe'
                            ),
                            $found_posts
                        );
                        ?>
                    </div>

                    <div class="search-results-list space-y-8">
                        <?php
                        /* Start the Loop */
                        while (have_posts()) :
                            the_post();

                            /**
                             * Run the loop for the search to output the results.
                             * If you want to overload this in a child theme then include a file
                             * called content-search.php and that will be used instead.
                             */
                            ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class('search-result-item bg-white dark:bg-dark-700 rounded-xl shadow-soft overflow-hidden transition-all duration-300 hover:shadow-water'); ?>>
                                <div class="grid grid-cols-1 md:grid-cols-4">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="search-thumbnail md:col-span-1">
                                            <a href="<?php the_permalink(); ?>" class="block h-full">
                                                <?php the_post_thumbnail('medium', array('class' => 'w-full h-full object-cover')); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="search-content p-6 <?php echo has_post_thumbnail() ? 'md:col-span-3' : 'md:col-span-4'; ?>">
                                        <header class="entry-header mb-3">
                                            <?php the_title(sprintf('<h2 class="entry-title text-xl font-bold text-dark-800 dark:text-white mb-2"><a href="%s" rel="bookmark" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">', esc_url(get_permalink())), '</a></h2>'); ?>
                                            
                                            <div class="entry-meta flex flex-wrap items-center text-sm text-dark-500 dark:text-dark-300">
                                                <?php
                                                // Post type
                                                $post_type = get_post_type();
                                                $post_type_obj = get_post_type_object($post_type);
                                                if ($post_type_obj) :
                                                ?>
                                                    <span class="post-type flex items-center mr-4">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                            <polyline points="14 2 14 8 20 8"></polyline>
                                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                                            <polyline points="10 9 9 9 8 9"></polyline>
                                                        </svg>
                                                        <?php echo esc_html($post_type_obj->labels->singular_name); ?>
                                                    </span>
                                                <?php endif; ?>
                                                
                                                <span class="post-date flex items-center mr-4">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                                    </svg>
                                                    <?php echo get_the_date(); ?>
                                                </span>
                                                
                                                <?php if ('post' === get_post_type() && has_category()) : ?>
                                                    <span class="post-categories flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1">
                                                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                                        </svg>
                                                        <?php the_category(', '); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div><!-- .entry-meta -->
                                        </header><!-- .entry-header -->

                                        <div class="entry-summary text-dark-600 dark:text-dark-200">
                                            <?php the_excerpt(); ?>
                                        </div><!-- .entry-summary -->

                                        <footer class="entry-footer mt-4">
                                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 font-medium hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                                                <?php 
                                                if ('product' === get_post_type()) {
                                                    esc_html_e('View Product', 'aqualuxe');
                                                } elseif ('page' === get_post_type()) {
                                                    esc_html_e('View Page', 'aqualuxe');
                                                } else {
                                                    esc_html_e('Read More', 'aqualuxe');
                                                }
                                                ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2">
                                                    <path d="M5 12h14"></path>
                                                    <path d="M12 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </footer><!-- .entry-footer -->
                                    </div>
                                </div>
                            </article><!-- #post-<?php the_ID(); ?> -->
                            <?php
                        endwhile;
                        ?>
                    </div>

                    <div class="pagination-container mt-12">
                        <?php
                        the_posts_pagination(array(
                            'mid_size'  => 2,
                            'prev_text' => sprintf(
                                '<span class="nav-prev-text">%s</span> %s',
                                __('Previous', 'aqualuxe'),
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block w-4 h-4"><path d="M15 18l-6-6 6-6"/></svg>'
                            ),
                            'next_text' => sprintf(
                                '<span class="nav-next-text">%s</span> %s',
                                __('Next', 'aqualuxe'),
                                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline-block w-4 h-4"><path d="M9 18l6-6-6-6"/></svg>'
                            ),
                        ));
                        ?>
                    </div>

                <?php else : ?>

                    <div class="no-results bg-white dark:bg-dark-700 rounded-xl shadow-soft p-8 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-primary-500 dark:text-primary-400 mx-auto mb-6">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            <line x1="11" y1="8" x2="11" y2="14"></line>
                            <line x1="8" y1="11" x2="14" y2="11"></line>
                        </svg>
                        
                        <h2 class="text-2xl font-bold text-dark-800 dark:text-white mb-4">
                            <?php esc_html_e('Nothing Found', 'aqualuxe'); ?>
                        </h2>
                        
                        <div class="text-dark-600 dark:text-dark-200 mb-6">
                            <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe'); ?></p>
                        </div>
                        
                        <div class="search-suggestions">
                            <h3 class="text-lg font-bold text-dark-700 dark:text-dark-100 mb-3">
                                <?php esc_html_e('Search Suggestions:', 'aqualuxe'); ?>
                            </h3>
                            <ul class="text-dark-600 dark:text-dark-200 mb-6">
                                <li><?php esc_html_e('Check your spelling.', 'aqualuxe'); ?></li>
                                <li><?php esc_html_e('Try more general keywords.', 'aqualuxe'); ?></li>
                                <li><?php esc_html_e('Try different keywords.', 'aqualuxe'); ?></li>
                                <li><?php esc_html_e('Try fewer keywords.', 'aqualuxe'); ?></li>
                            </ul>
                        </div>
                        
                        <div class="search-form-container max-w-md mx-auto">
                            <?php get_search_form(); ?>
                        </div>
                    </div>

                <?php endif; ?>
            </div>

            <?php if (is_active_sidebar('sidebar-1')) : ?>
                <div class="sidebar-container">
                    <?php get_sidebar(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</main><!-- #main -->

<?php
get_footer();