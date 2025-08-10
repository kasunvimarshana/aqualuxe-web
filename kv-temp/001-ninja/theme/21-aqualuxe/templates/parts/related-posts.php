<?php
/**
 * Template part for displaying related posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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
    
    // Query related posts
    $related_args = array(
        'category__in'      => $category_ids,
        'post__not_in'      => array(get_the_ID()),
        'posts_per_page'    => 3,
        'ignore_sticky_posts' => 1,
        'orderby'           => 'rand',
    );
    
    $related_query = new WP_Query($related_args);
    
    // Only show if we have related posts
    if ($related_query->have_posts()) :
?>
        <div class="related-posts mt-12 p-6 md:p-8 bg-white dark:bg-dark-700 rounded-xl shadow-soft">
            <h3 class="related-title text-xl font-bold text-dark-800 dark:text-white mb-6">
                <?php echo esc_html__('Related Posts', 'aqualuxe'); ?>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                    <article class="related-post">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="block mb-3 overflow-hidden rounded-lg">
                                <?php the_post_thumbnail('medium', array('class' => 'w-full h-auto transition-transform duration-300 hover:scale-105')); ?>
                            </a>
                        <?php endif; ?>
                        
                        <h4 class="entry-title text-base font-bold text-dark-800 dark:text-white mb-2">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                <?php the_title(); ?>
                            </a>
                        </h4>
                        
                        <div class="entry-meta text-sm text-dark-500 dark:text-dark-300">
                            <span class="post-date">
                                <?php echo get_the_date(); ?>
                            </span>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
<?php
    endif;
    wp_reset_postdata();
}