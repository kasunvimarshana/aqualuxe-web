<?php
/**
 * Template for displaying single posts
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container mx-auto px-4 py-8">
        
        <?php while (have_posts()) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail mb-8">
                        <?php the_post_thumbnail('aqualuxe-featured', ['class' => 'w-full h-64 md:h-96 object-cover rounded-lg']); ?>
                    </div>
                <?php endif; ?>
                
                <header class="post-header mb-8">
                    <h1 class="post-title text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        <?php the_title(); ?>
                    </h1>
                    
                    <div class="post-meta flex flex-wrap items-center gap-4 text-gray-600 dark:text-gray-400">
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
                        
                        <?php if (get_the_category()) : ?>
                            <span class="categories">
                                <?php _e('in', 'aqualuxe'); ?> 
                                <?php the_category(', '); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if (comments_open() || get_comments_number()) : ?>
                            <span class="comments">
                                <a href="#comments" class="text-primary-600 hover:text-primary-700">
                                    <?php comments_number(__('No Comments', 'aqualuxe'), __('1 Comment', 'aqualuxe'), __('% Comments', 'aqualuxe')); ?>
                                </a>
                            </span>
                        <?php endif; ?>
                    </div>
                </header>
                
                <div class="post-content prose max-w-none">
                    <?php the_content(); ?>
                    
                    <?php
                    wp_link_pages([
                        'before' => '<div class="page-links">' . __('Pages:', 'aqualuxe'),
                        'after'  => '</div>',
                    ]);
                    ?>
                </div>
                
                <?php if (get_the_tags()) : ?>
                    <footer class="post-footer mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <div class="post-tags">
                            <span class="tags-label font-medium text-gray-700 dark:text-gray-300 mr-2">
                                <?php _e('Tags:', 'aqualuxe'); ?>
                            </span>
                            <?php the_tags('', ', ', ''); ?>
                        </div>
                    </footer>
                <?php endif; ?>
                
            </article>
            
            <?php
            // Author bio
            if (get_the_author_meta('description')) :
            ?>
                <div class="author-bio bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mt-8">
                    <h3 class="text-xl font-semibold mb-4"><?php _e('About the Author', 'aqualuxe'); ?></h3>
                    <div class="flex items-start space-x-4">
                        <div class="author-avatar">
                            <?php echo get_avatar(get_the_author_meta('ID'), 80, '', '', ['class' => 'rounded-full']); ?>
                        </div>
                        <div class="author-info flex-1">
                            <h4 class="author-name text-lg font-medium mb-2">
                                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="text-primary-600 hover:text-primary-700">
                                    <?php the_author(); ?>
                                </a>
                            </h4>
                            <div class="author-description text-gray-700 dark:text-gray-300">
                                <?php echo wpautop(get_the_author_meta('description')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php
            // Post navigation
            the_post_navigation([
                'prev_text' => '<span class="nav-subtitle">' . __('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . __('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
                'class'     => 'post-navigation mt-8 pt-8 border-t border-gray-200 dark:border-gray-700',
            ]);
            ?>
            
            <?php
            // Related posts
            $related_posts = get_posts([
                'category__in' => wp_get_post_categories($post->ID),
                'numberposts'  => 3,
                'post__not_in' => [$post->ID],
            ]);
            
            if ($related_posts) :
            ?>
                <div class="related-posts mt-12">
                    <h3 class="text-2xl font-semibold mb-6"><?php _e('Related Posts', 'aqualuxe'); ?></h3>
                    <div class="grid gap-6 md:grid-cols-3">
                        <?php foreach ($related_posts as $related_post) : ?>
                            <article class="related-post bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                                <?php if (has_post_thumbnail($related_post->ID)) : ?>
                                    <div class="post-thumbnail">
                                        <a href="<?php echo get_permalink($related_post->ID); ?>">
                                            <?php echo get_the_post_thumbnail($related_post->ID, 'aqualuxe-card', ['class' => 'w-full h-48 object-cover']); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <div class="post-content p-4">
                                    <h4 class="post-title text-lg font-medium mb-2">
                                        <a href="<?php echo get_permalink($related_post->ID); ?>" class="text-gray-900 dark:text-white hover:text-primary-600 transition-colors duration-300">
                                            <?php echo get_the_title($related_post->ID); ?>
                                        </a>
                                    </h4>
                                    <div class="post-excerpt text-gray-600 dark:text-gray-400 text-sm">
                                        <?php echo wp_trim_words(get_the_excerpt($related_post->ID), 15); ?>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
        <?php endwhile; ?>
        
        <?php
        // Comments
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        ?>
        
    </div>
</main>

<?php
get_sidebar();
get_footer();