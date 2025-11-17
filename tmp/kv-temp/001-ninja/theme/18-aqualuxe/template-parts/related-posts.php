<?php
/**
 * Template part for displaying related posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get current post categories
$categories = get_the_category();

if ($categories) {
    $category_ids = array();
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }

    // Query related posts
    $related_args = array(
        'category__in'        => $category_ids,
        'post__not_in'        => array(get_the_ID()),
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => 1,
    );

    $related_query = new WP_Query($related_args);

    if ($related_query->have_posts()) :
    ?>
        <div class="related-posts mt-8 bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none p-6 md:p-8">
            <h3 class="text-2xl font-bold mb-6"><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                    <article class="related-post transition-transform duration-300 hover:-translate-y-1">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail mb-3">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover rounded-lg']); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <h4 class="text-lg font-bold mb-2">
                            <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors duration-300">
                                <?php the_title(); ?>
                            </a>
                        </h4>
                        
                        <div class="post-meta text-sm text-gray-600 dark:text-gray-400">
                            <?php echo get_the_date(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    <?php
    endif;
    wp_reset_postdata();
}