<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container mx-auto px-4 py-8">
        
        <?php if (have_posts()) : ?>
            
            <div class="posts-grid grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <?php while (have_posts()) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-card bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300'); ?>>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>" class="block">
                                    <?php the_post_thumbnail('aqualuxe-card', ['class' => 'w-full h-48 object-cover']); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content p-6">
                            <header class="post-header mb-4">
                                <h2 class="post-title text-xl font-semibold text-gray-900 mb-2">
                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors duration-300">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
                                <div class="post-meta text-sm text-gray-600">
                                    <time datetime="<?php echo get_the_date('c'); ?>" class="published">
                                        <?php echo get_the_date(); ?>
                                    </time>
                                    
                                    <?php if (get_the_author()) : ?>
                                        <span class="author">
                                            <?php _e('by', 'aqualuxe'); ?> 
                                            <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="text-primary-600 hover:text-primary-700">
                                                <?php the_author(); ?>
                                            </a>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </header>
                            
                            <div class="post-excerpt text-gray-700 mb-4">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <footer class="post-footer">
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                                    <?php _e('Read More', 'aqualuxe'); ?>
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
            ]);
            ?>
            
        <?php else : ?>
            
            <div class="no-posts text-center py-12">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">
                    <?php _e('Nothing Found', 'aqualuxe'); ?>
                </h2>
                <p class="text-gray-600 mb-6">
                    <?php _e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'aqualuxe'); ?>
                </p>
                <?php get_search_form(); ?>
            </div>
            
        <?php endif; ?>
        
    </div>
</main>

<?php get_sidebar(); ?>
<?php get_footer();