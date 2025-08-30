<?php
/**
 * Template part for displaying search results
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('search-post bg-white dark:bg-dark-light rounded-lg shadow-soft overflow-hidden mb-6 transition-transform duration-300 hover:-translate-y-1'); ?>>
    <div class="post-content p-6">
        <header class="post-header mb-4">
            <h2 class="post-title text-xl font-serif font-bold mb-2">
                <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors"><?php the_title(); ?></a>
            </h2>
            
            <div class="post-meta text-sm text-gray-600 dark:text-gray-400 flex flex-wrap gap-4">
                <span class="post-type">
                    <i class="fas fa-file mr-1"></i>
                    <?php echo get_post_type_object(get_post_type())->labels->singular_name; ?>
                </span>
                
                <span class="post-date">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    <?php echo get_the_date(); ?>
                </span>
                
                <?php if ( 'post' === get_post_type() ) : ?>
                    <span class="post-author">
                        <i class="fas fa-user mr-1"></i>
                        <?php the_author(); ?>
                    </span>
                <?php endif; ?>
            </div>
        </header>
        
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="post-thumbnail mb-4">
                <a href="<?php the_permalink(); ?>" class="block overflow-hidden">
                    <?php the_post_thumbnail('thumbnail', array('class' => 'w-full h-auto transition-transform duration-500 hover:scale-105')); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <div class="post-excerpt prose dark:prose-invert max-w-none mb-4">
            <?php the_excerpt(); ?>
        </div>
        
        <div class="post-footer">
            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary hover:text-primary-dark font-medium transition-colors">
                <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</article>