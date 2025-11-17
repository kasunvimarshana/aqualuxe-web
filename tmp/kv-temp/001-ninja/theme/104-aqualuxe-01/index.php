<?php
/**
 * Main template file
 * 
 * @package AquaLuxe
 */

get_header(); ?>

<main id="main-content" class="site-main" role="main">
    
    <!-- Breadcrumbs -->
    <?php aqualuxe_get_template_part('components/breadcrumbs'); ?>
    
    <div class="container mx-auto px-4 py-8">
        
        <?php if (have_posts()) : ?>
            
            <!-- Page Header -->
            <?php if (is_home() && !is_front_page()) : ?>
                <header class="page-header text-center mb-12">
                    <h1 class="page-title text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        <?php echo get_the_title(get_option('page_for_posts')); ?>
                    </h1>
                    <?php
                    $blog_description = get_post_field('post_content', get_option('page_for_posts'));
                    if (!empty($blog_description)) :
                        ?>
                        <div class="page-description text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                            <?php echo wp_kses_post($blog_description); ?>
                        </div>
                        <?php
                    endif;
                    ?>
                </header>
            <?php elseif (is_archive()) : ?>
                <header class="page-header text-center mb-12">
                    <h1 class="page-title text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        <?php the_archive_title(); ?>
                    </h1>
                    <?php if (get_the_archive_description()) : ?>
                        <div class="page-description text-lg text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                            <?php the_archive_description(); ?>
                        </div>
                    <?php endif; ?>
                </header>
            <?php elseif (is_search()) : ?>
                <header class="page-header text-center mb-12">
                    <h1 class="page-title text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        <?php 
                        /* translators: %s: search query */
                        printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span class="text-primary-600">' . get_search_query() . '</span>');
                        ?>
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-300">
                        <?php 
                        /* translators: %d: number of search results */
                        printf(
                            esc_html(_n(
                                'Found %d result for your search.',
                                'Found %d results for your search.',
                                $wp_query->found_posts,
                                'aqualuxe'
                            )),
                            number_format_i18n($wp_query->found_posts)
                        );
                        ?>
                    </p>
                </header>
            <?php endif; ?>
            
            <!-- Posts Grid -->
            <div class="posts-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <?php while (have_posts()) : the_post(); ?>
                    
                    <!-- Use the post content template part -->
                    <?php aqualuxe_get_template_part('content/post-content'); ?>
                    
                <?php endwhile; ?>
                
            </div>
            
            <!-- Pagination -->
            <?php aqualuxe_get_template_part('components/pagination'); ?>
            
        <?php else : ?>
            
            <!-- No Posts Found -->
            <div class="no-posts-found text-center py-16">
                <div class="max-w-md mx-auto">
                    <svg class="w-24 h-24 mx-auto mb-6 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                        <?php 
                        if (is_search()) {
                            esc_html_e('No search results found', 'aqualuxe');
                        } else {
                            esc_html_e('No posts found', 'aqualuxe');
                        }
                        ?>
                    </h2>
                    
                    <p class="text-gray-600 dark:text-gray-300 mb-6">
                        <?php 
                        if (is_search()) {
                            esc_html_e('Sorry, no posts matched your search. Please try again with different keywords.', 'aqualuxe');
                        } else {
                            esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe');
                        }
                        ?>
                    </p>
                    
                    <!-- Search Form -->
                    <?php aqualuxe_get_template_part('components/search-form', null, [
                        'placeholder' => __('Search posts...', 'aqualuxe'),
                        'form_class' => 'search-form max-w-md mx-auto',
                    ]); ?>
                    
                    <?php if (is_search()) : ?>
                        <div class="mt-8">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                                <?php esc_html_e('Go to Homepage', 'aqualuxe'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
        <?php endif; ?>
        
    </div>
</main>

<?php get_footer(); ?>
