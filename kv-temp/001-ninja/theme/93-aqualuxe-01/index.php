<?php
/**
 * Main template file for AquaLuxe theme
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package AquaLuxe
 * @version 1.0.0
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<main id="main" class="site-main" role="main" aria-label="<?php esc_attr_e('Main content', 'aqualuxe'); ?>">
    <div class="container mx-auto px-4 py-8">
        
        <?php if (have_posts()) : ?>
            
            <div class="posts-grid grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                
                <?php while (have_posts()) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300'); ?> itemscope itemtype="https://schema.org/Article">
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>" class="block">
                                    <?php 
                                    the_post_thumbnail('aqualuxe-gallery', [
                                        'class' => 'w-full h-48 object-cover',
                                        'alt' => get_the_title(),
                                        'loading' => 'lazy'
                                    ]); 
                                    ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content p-6">
                            
                            <header class="post-header mb-4">
                                <h2 class="post-title text-xl font-semibold mb-2" itemprop="headline">
                                    <a href="<?php the_permalink(); ?>" class="text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
                                <div class="post-meta text-sm text-gray-600 dark:text-gray-400 flex flex-wrap gap-4">
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                        <span class="sr-only"><?php esc_html_e('Published on', 'aqualuxe'); ?></span>
                                        <?php echo esc_html(get_the_date()); ?>
                                    </time>
                                    
                                    <span class="post-author" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                        <?php esc_html_e('By', 'aqualuxe'); ?> 
                                        <span itemprop="name"><?php the_author(); ?></span>
                                    </span>
                                    
                                    <?php if (has_category()) : ?>
                                        <span class="post-categories">
                                            <?php the_category(', '); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </header>
                            
                            <div class="post-excerpt text-gray-700 dark:text-gray-300 mb-4" itemprop="description">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <footer class="post-footer">
                                <a href="<?php the_permalink(); ?>" class="read-more-btn inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <span class="sr-only"><?php echo esc_html(get_the_title()); ?></span>
                                </a>
                            </footer>
                            
                        </div>
                        
                    </article>
                    
                <?php endwhile; ?>
                
            </div>
            
            <?php
            // Pagination
            the_posts_pagination([
                'mid_size' => 2,
                'prev_text' => __('Previous', 'aqualuxe'),
                'next_text' => __('Next', 'aqualuxe'),
                'class' => 'pagination mt-8 flex justify-center'
            ]);
            ?>
            
        <?php else : ?>
            
            <div class="no-posts text-center py-12">
                <h1 class="text-2xl font-semibold mb-4 text-gray-900 dark:text-white">
                    <?php esc_html_e('Nothing Found', 'aqualuxe'); ?>
                </h1>
                
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    <?php esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'aqualuxe'); ?>
                </p>
                
                <div class="search-form-container max-w-md mx-auto">
                    <?php get_search_form(); ?>
                </div>
            </div>
            
        <?php endif; ?>
        
    </div>
</main>

<?php
get_sidebar();
get_footer();
