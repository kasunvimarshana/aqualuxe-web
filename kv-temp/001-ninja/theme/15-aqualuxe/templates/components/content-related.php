<?php
/**
 * Template part for displaying related posts
 *
 * @package AquaLuxe
 */

// Get current post categories
$categories = get_the_category();

if ($categories) {
    $category_ids = array();
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }

    $args = array(
        'category__in'      => $category_ids,
        'post__not_in'      => array(get_the_ID()),
        'posts_per_page'    => 3,
        'ignore_sticky_posts' => 1,
    );

    $related_posts = new WP_Query($args);

    if ($related_posts->have_posts()) :
?>
        <div class="related-posts mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-2xl font-bold mb-6"><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h3>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
                    <article class="related-post bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="block">
                                <?php the_post_thumbnail('aqualuxe-card', array('class' => 'w-full h-48 object-cover')); ?>
                            </a>
                        <?php endif; ?>
                        
                        <div class="p-5">
                            <h4 class="text-lg font-bold mb-2">
                                <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                    <?php the_title(); ?>
                                </a>
                            </h4>
                            
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                <span class="post-date">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <?php echo get_the_date(); ?>
                                </span>
                            </div>
                            
                            <div class="related-excerpt text-gray-600 dark:text-gray-300 text-sm mb-3">
                                <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-sm text-primary-600 dark:text-primary-400 hover:underline">
                                <?php esc_html_e('Read More', 'aqualuxe'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
<?php
    endif;
    wp_reset_postdata();
}