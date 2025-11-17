<?php
/**
 * Template part for displaying the featured post on the Blog page
 *
 * @package AquaLuxe
 */

// Get the most recent post with the "featured" tag or category
$args = array(
    'posts_per_page' => 1,
    'post_status' => 'publish',
    'tax_query' => array(
        array(
            'taxonomy' => 'post_tag',
            'field'    => 'slug',
            'terms'    => 'featured',
        ),
    ),
);

$featured_query = new WP_Query( $args );

// If no posts with featured tag, get the most recent post
if ( ! $featured_query->have_posts() ) {
    $args = array(
        'posts_per_page' => 1,
        'post_status' => 'publish',
    );
    $featured_query = new WP_Query( $args );
}

if ( $featured_query->have_posts() ) :
    while ( $featured_query->have_posts() ) : $featured_query->the_post();
?>

<article class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg">
    <div class="relative">
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="aspect-w-16 aspect-h-9">
                <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-full object-cover' ) ); ?>
            </div>
        <?php else : ?>
            <div class="aspect-w-16 aspect-h-9 bg-blue-100 dark:bg-blue-900">
                <div class="flex items-center justify-center h-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-blue-300 dark:text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="absolute top-4 left-4">
            <span class="bg-teal-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                Featured
            </span>
        </div>
    </div>
    
    <div class="p-6">
        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-3">
            <span class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <?php echo get_the_date(); ?>
            </span>
            <span class="mx-3">•</span>
            <span class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <?php the_author(); ?>
            </span>
            <span class="mx-3">•</span>
            <span class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
                <?php comments_number( '0 Comments', '1 Comment', '% Comments' ); ?>
            </span>
        </div>
        
        <h2 class="text-2xl md:text-3xl font-bold mb-4 text-gray-800 dark:text-white">
            <a href="<?php the_permalink(); ?>" class="hover:text-teal-600 dark:hover:text-teal-400 transition duration-300">
                <?php the_title(); ?>
            </a>
        </h2>
        
        <div class="text-gray-600 dark:text-gray-300 mb-6">
            <?php the_excerpt(); ?>
        </div>
        
        <div class="flex items-center justify-between">
            <div class="flex flex-wrap gap-2">
                <?php
                $categories = get_the_category();
                if ( ! empty( $categories ) ) :
                    foreach ( $categories as $category ) :
                ?>
                    <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="text-xs bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800 transition duration-300">
                        <?php echo esc_html( $category->name ); ?>
                    </a>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
            
            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-teal-600 dark:text-teal-400 font-medium hover:text-teal-700 dark:hover:text-teal-300 transition duration-300">
                <span>Read More</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>
</article>

<?php
    endwhile;
    wp_reset_postdata();
endif;
?>