<?php
/**
 * Template part for displaying related posts
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get current post ID
$post_id = get_the_ID();

// Get current post categories
$categories = get_the_category( $post_id );

if ( ! $categories ) {
    return;
}

// Get category IDs
$category_ids = wp_list_pluck( $categories, 'term_id' );

// Query related posts
$related_args = array(
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => 3,
    'category__in'        => $category_ids,
    'post__not_in'        => array( $post_id ),
    'ignore_sticky_posts' => 1,
    'orderby'             => 'rand',
);

$related_query = new WP_Query( $related_args );

// If no related posts, try getting recent posts
if ( ! $related_query->have_posts() ) {
    $recent_args = array(
        'post_type'           => 'post',
        'post_status'         => 'publish',
        'posts_per_page'      => 3,
        'post__not_in'        => array( $post_id ),
        'ignore_sticky_posts' => 1,
    );
    
    $related_query = new WP_Query( $recent_args );
}

// Return if no posts found
if ( ! $related_query->have_posts() ) {
    return;
}
?>

<div class="related-posts max-w-4xl mx-auto mt-12 pt-12 border-t border-gray-200 dark:border-dark-700">
    <h2 class="text-2xl font-serif font-bold text-dark-900 dark:text-white mb-8">
        <?php esc_html_e( 'You might also like', 'aqualuxe' ); ?>
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
            <article class="card overflow-hidden h-full flex flex-col">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-thumbnail">
                        <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                            <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover' ) ); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <div class="entry-content p-6 flex-grow">
                    <header class="entry-header mb-2">
                        <?php the_title( '<h3 class="entry-title text-lg font-serif font-bold text-dark-900 dark:text-white"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">', '</a></h3>' ); ?>
                        
                        <div class="entry-meta text-xs text-dark-500 dark:text-dark-400 mt-1">
                            <?php aqualuxe_posted_on(); ?>
                        </div>
                    </header>
                    
                    <div class="entry-summary text-sm text-dark-600 dark:text-dark-300">
                        <?php the_excerpt(); ?>
                    </div>
                </div>
                
                <footer class="entry-footer px-6 pb-6 mt-auto">
                    <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-sm text-primary-600 dark:text-primary-400 font-medium hover:underline">
                        <?php esc_html_e( 'Read more', 'aqualuxe' ); ?>
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </footer>
            </article>
        <?php endwhile; ?>
    </div>
</div>

<?php
// Restore original post data
wp_reset_postdata();