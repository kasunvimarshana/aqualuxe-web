<?php
/**
 * The template for displaying Care Guide single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main py-12">
    <div class="container-fluid">
        <?php
        // Breadcrumbs
        if (function_exists('aqualuxe_breadcrumbs')) :
            aqualuxe_breadcrumbs();
        endif;

        while (have_posts()) :
            the_post();
            
            // Get care guide meta
            $difficulty = get_post_meta(get_the_ID(), 'difficulty', true);
            $time_required = get_post_meta(get_the_ID(), 'time_required', true);
            $frequency = get_post_meta(get_the_ID(), 'frequency', true);
            $materials_needed = get_post_meta(get_the_ID(), 'materials_needed', true);
            $estimated_cost = get_post_meta(get_the_ID(), 'estimated_cost', true);
            $video_url = get_post_meta(get_the_ID(), 'video_url', true);
            
            // Get taxonomy terms
            $guide_categories = get_the_terms(get_the_ID(), 'guide_category');
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden'); ?>>
                <div class="care-guide-header relative">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="care-guide-image h-80">
                            <?php the_post_thumbnail('full', ['class' => 'w-full h-full object-cover']); ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="care-guide-title-container absolute bottom-0 left-0 right-0 p-6 md:p-8 text-white">
                        <?php if ($guide_categories && !is_wp_error($guide_categories)) : ?>
                            <div class="guide-categories mb-3">
                                <?php foreach ($guide_categories as $category) : ?>
                                    <a href="<?php echo esc_url(get_term_link($category)); ?>" class="bg-primary text-white text-sm px-3 py-1 rounded-full mr-2 hover:bg-primary-dark transition-colors duration-300">
                                        <?php echo esc_html($category->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <h1 class="entry-title text-3xl md:text-4xl font-bold mb-4"><?php the_title(); ?></h1>
                        
                        <div class="care-guide-meta flex flex-wrap gap-4">
                            <?php if ($difficulty) : ?>
                                <span class="bg-accent text-primary-dark px-3 py-1 rounded-full text-sm flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <?php 
                                    $difficulty_labels = array(
                                        '1' => esc_html__('Very Easy', 'aqualuxe'),
                                        '2' => esc_html__('Easy', 'aqualuxe'),
                                        '3' => esc_html__('Moderate', 'aqualuxe'),
                                        '4' => esc_html__('Difficult', 'aqualuxe'),
                                        '5' => esc_html__('Very Difficult', 'aqualuxe'),
                                    );
                                    echo isset($difficulty_labels[$difficulty]) ? $difficulty_labels[$difficulty] : esc_html($difficulty);
                                    ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ($time_required) : ?>
                                <span class="bg-secondary text-white px-3 py-1 rounded-full text-sm flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <?php echo esc_html($time_required); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ($frequency) : ?>
                                <span class="bg-primary-light text-primary-dark px-3 py-1 rounded-full text-sm flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <?php echo esc_html($frequency); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="care-guide-content p-6 md:p-8">
                    <?php if ($materials_needed || $estimated_cost) : ?>
                        <div class="care-guide-specs grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 bg-gray-50 dark:bg-gray-800 p-6 rounded-xl">
                            <?php if ($materials_needed) : ?>
                                <div class="spec-item">
                                    <h3 class="text-lg font-bold mb-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <?php esc_html_e('Materials Needed', 'aqualuxe'); ?>
                                    </h3>
                                    <div class="materials-list">
                                        <?php
                                        // Convert string to array if it's a comma-separated list
                                        if (strpos($materials_needed, ',') !== false) {
                                            $materials = explode(',', $materials_needed);
                                            echo '<ul class="list-disc pl-5 space-y-1">';
                                            foreach ($materials as $material) {
                                                echo '<li>' . esc_html(trim($material)) . '</li>';
                                            }
                                            echo '</ul>';
                                        } else {
                                            echo '<p>' . esc_html($materials_needed) . '</p>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($estimated_cost) : ?>
                                <div class="spec-item">
                                    <h3 class="text-lg font-bold mb-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <?php esc_html_e('Estimated Cost', 'aqualuxe'); ?>
                                    </h3>
                                    <p class="text-lg font-medium"><?php echo esc_html($estimated_cost); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($video_url) : ?>
                        <div class="care-guide-video mb-8">
                            <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Video Tutorial', 'aqualuxe'); ?></h2>
                            <div class="video-container relative pt-[56.25%]">
                                <?php
                                // Extract video ID from YouTube URL
                                $video_id = '';
                                if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $video_url, $matches)) {
                                    $video_id = $matches[1];
                                } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $video_url, $matches)) {
                                    $video_id = $matches[1];
                                }
                                
                                if ($video_id) {
                                    echo '<iframe class="absolute inset-0 w-full h-full rounded-lg" src="https://www.youtube.com/embed/' . esc_attr($video_id) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                                } else {
                                    echo '<div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4 text-center">' . esc_html__('Video URL format not supported.', 'aqualuxe') . '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="care-guide-description prose prose-lg dark:prose-invert max-w-none mb-8">
                        <?php the_content(); ?>
                    </div>

                    <?php
                    // Display related products if WooCommerce is active
                    if (class_exists('WooCommerce')) :
                        $related_product_ids = get_post_meta(get_the_ID(), 'related_products', true);
                        if ($related_product_ids) :
                            $related_product_ids = explode(',', $related_product_ids);
                            $args = array(
                                'post_type' => 'product',
                                'post__in' => $related_product_ids,
                                'posts_per_page' => -1,
                            );
                            $related_products = new WP_Query($args);
                            
                            if ($related_products->have_posts()) :
                            ?>
                            <div class="related-products mb-8">
                                <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Recommended Products', 'aqualuxe'); ?></h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                    <?php while ($related_products->have_posts()) : $related_products->the_post(); ?>
                                        <div class="product-card bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <div class="product-image">
                                                    <a href="<?php the_permalink(); ?>">
                                                        <?php the_post_thumbnail('woocommerce_thumbnail', ['class' => 'w-full h-48 object-cover']); ?>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="product-content p-4">
                                                <h3 class="product-title text-lg font-bold mb-2">
                                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors duration-300">
                                                        <?php the_title(); ?>
                                                    </a>
                                                </h3>
                                                
                                                <div class="product-price text-primary font-medium mb-3">
                                                    <?php
                                                    $product = wc_get_product(get_the_ID());
                                                    echo $product->get_price_html();
                                                    ?>
                                                </div>
                                                
                                                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="add-to-cart-button bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md text-sm transition-colors duration-300 inline-block">
                                                    <?php echo esc_html($product->is_purchasable() && $product->is_in_stock() ? __('Add to cart', 'aqualuxe') : __('Read more', 'aqualuxe')); ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                            <?php
                            wp_reset_postdata();
                            endif;
                        endif;
                    endif;
                    ?>

                    <?php
                    // Display related fish species if available
                    $related_species_ids = get_post_meta(get_the_ID(), 'related_species', true);
                    if ($related_species_ids) :
                        $related_species_ids = explode(',', $related_species_ids);
                        $args = array(
                            'post_type' => 'fish-species',
                            'post__in' => $related_species_ids,
                            'posts_per_page' => -1,
                        );
                        $related_species = new WP_Query($args);
                        
                        if ($related_species->have_posts()) :
                        ?>
                        <div class="related-species mb-8">
                            <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Related Fish Species', 'aqualuxe'); ?></h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php while ($related_species->have_posts()) : $related_species->the_post(); ?>
                                    <div class="species-card bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="species-image">
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover']); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="species-content p-4">
                                            <h3 class="species-title text-lg font-bold mb-2">
                                                <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors duration-300">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h3>
                                            
                                            <?php
                                            $scientific_name = get_post_meta(get_the_ID(), 'scientific_name', true);
                                            if ($scientific_name) :
                                            ?>
                                                <p class="scientific-name text-gray-600 dark:text-gray-400 italic mb-3"><?php echo esc_html($scientific_name); ?></p>
                                            <?php endif; ?>
                                            
                                            <div class="species-excerpt text-gray-600 dark:text-gray-300 mb-3">
                                                <?php the_excerpt(); ?>
                                            </div>
                                            
                                            <a href="<?php the_permalink(); ?>" class="text-primary hover:text-primary-dark font-medium transition-colors duration-300 flex items-center">
                                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                        <?php
                        wp_reset_postdata();
                        endif;
                    endif;
                    ?>

                    <div class="care-guide-taxonomy mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <?php if ($guide_categories && !is_wp_error($guide_categories)) : ?>
                            <div class="taxonomy-item mb-4">
                                <span class="text-gray-600 dark:text-gray-400 mr-2"><?php esc_html_e('Categories:', 'aqualuxe'); ?></span>
                                <?php
                                foreach ($guide_categories as $index => $category) {
                                    echo '<a href="' . esc_url(get_term_link($category)) . '" class="text-primary hover:text-primary-dark transition-colors duration-300">' . esc_html($category->name) . '</a>';
                                    if ($index < count($guide_categories) - 1) {
                                        echo ', ';
                                    }
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        $tags = get_the_tags();
                        if ($tags) :
                        ?>
                            <div class="taxonomy-item">
                                <span class="text-gray-600 dark:text-gray-400 mr-2"><?php esc_html_e('Tags:', 'aqualuxe'); ?></span>
                                <?php
                                foreach ($tags as $index => $tag) {
                                    echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="text-primary hover:text-primary-dark transition-colors duration-300">' . esc_html($tag->name) . '</a>';
                                    if ($index < count($tags) - 1) {
                                        echo ', ';
                                    }
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="care-guide-navigation mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row justify-between">
                            <div class="prev-post mb-4 sm:mb-0">
                                <?php
                                $prev_post = get_previous_post();
                                if (!empty($prev_post)) :
                                    ?>
                                    <span class="block text-sm text-gray-600 dark:text-gray-400 mb-1"><?php esc_html_e('Previous Guide', 'aqualuxe'); ?></span>
                                    <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="text-primary hover:text-primary-dark transition-colors duration-300 font-medium">
                                        <?php echo esc_html(get_the_title($prev_post->ID)); ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <div class="next-post text-right">
                                <?php
                                $next_post = get_next_post();
                                if (!empty($next_post)) :
                                    ?>
                                    <span class="block text-sm text-gray-600 dark:text-gray-400 mb-1"><?php esc_html_e('Next Guide', 'aqualuxe'); ?></span>
                                    <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="text-primary hover:text-primary-dark transition-colors duration-300 font-medium">
                                        <?php echo esc_html(get_the_title($next_post->ID)); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </article><!-- #post-<?php the_ID(); ?> -->

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>
    </div>
</main><!-- #main -->

<?php
get_footer();