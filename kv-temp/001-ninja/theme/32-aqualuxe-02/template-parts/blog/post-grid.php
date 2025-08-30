<?php
/**
 * Template part for displaying the post grid on the Blog page
 *
 * @package AquaLuxe
 */

// Get current page and category
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$category = get_query_var( 'cat' );

// Set up the query arguments
$args = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 6,
    'paged'          => $paged,
);

// Add category if one is selected
if ( ! empty( $category ) ) {
    $args['cat'] = $category;
}

// Exclude featured post from the grid if we're on the first page
if ( $paged === 1 ) {
    $featured_args = array(
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'post_tag',
                'field'    => 'slug',
                'terms'    => 'featured',
            ),
        ),
    );
    
    $featured_query = new WP_Query( $featured_args );
    
    if ( $featured_query->have_posts() ) {
        $featured_post_id = $featured_query->posts[0]->ID;
        $args['post__not_in'] = array( $featured_post_id );
    }
    
    wp_reset_postdata();
}

$query = new WP_Query( $args );

if ( $query->have_posts() ) :
?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
    
    <article class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-sm transition-transform duration-300 hover:shadow-md hover:transform hover:scale-[1.01]">
        <?php if ( has_post_thumbnail() ) : ?>
            <a href="<?php the_permalink(); ?>" class="block aspect-w-16 aspect-h-9">
                <?php the_post_thumbnail( 'medium_large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
            </a>
        <?php else : ?>
            <div class="aspect-w-16 aspect-h-9 bg-blue-50 dark:bg-blue-900/30">
                <div class="flex items-center justify-center h-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-300 dark:text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="p-5">
            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 mb-3">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <?php echo get_the_date(); ?>
                </span>
                <span class="mx-2">•</span>
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    <?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?>
                </span>
            </div>
            
            <h3 class="text-xl font-bold mb-3 text-gray-800 dark:text-white">
                <a href="<?php the_permalink(); ?>" class="hover:text-teal-600 dark:hover:text-teal-400 transition duration-300">
                    <?php the_title(); ?>
                </a>
            </h3>
            
            <div class="text-gray-600 dark:text-gray-300 text-sm mb-4">
                <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
            </div>
            
            <div class="flex items-center justify-between">
                <div class="flex flex-wrap gap-2">
                    <?php
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) :
                        $main_category = $categories[0];
                    ?>
                        <a href="<?php echo esc_url( get_category_link( $main_category->term_id ) ); ?>" class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800 transition duration-300">
                            <?php echo esc_html( $main_category->name ); ?>
                        </a>
                    <?php endif; ?>
                </div>
                
                <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-sm text-teal-600 dark:text-teal-400 font-medium hover:text-teal-700 dark:hover:text-teal-300 transition duration-300">
                    <span>Read</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </article>
    
    <?php endwhile; ?>
</div>

<?php
else :
?>

<div class="bg-white dark:bg-gray-800 rounded-lg p-8 text-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
    </svg>
    <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">No Posts Found</h3>
    <p class="text-gray-600 dark:text-gray-400">We couldn't find any posts matching your criteria.</p>
    <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="inline-block mt-4 bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
        View All Posts
    </a>
</div>

<?php
endif;
wp_reset_postdata();
?>