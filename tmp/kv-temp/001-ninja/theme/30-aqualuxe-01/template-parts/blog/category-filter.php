<?php
/**
 * Template part for displaying the category filter on the Blog page
 *
 * @package AquaLuxe
 */

// Get all categories with posts
$categories = get_categories( array(
    'orderby' => 'name',
    'order'   => 'ASC',
    'hide_empty' => true,
) );

// Get current category if any
$current_cat = get_query_var( 'cat' );
?>

<div class="category-filter">
    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Browse by Category</h3>
    
    <div class="flex flex-wrap gap-2">
        <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="<?php echo empty( $current_cat ) ? 'bg-teal-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'; ?> px-4 py-2 rounded-lg text-sm font-medium transition duration-300">
            All
        </a>
        
        <?php foreach ( $categories as $category ) : ?>
            <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="<?php echo $current_cat == $category->term_id ? 'bg-teal-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'; ?> px-4 py-2 rounded-lg text-sm font-medium transition duration-300">
                <?php echo esc_html( $category->name ); ?>
                <span class="ml-1 text-xs">(<?php echo esc_html( $category->count ); ?>)</span>
            </a>
        <?php endforeach; ?>
    </div>
    
    <div class="mt-6 border-b border-gray-200 dark:border-gray-700"></div>
</div>