<?php
/**
 * The template for displaying Fish Species single posts
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
            
            // Get fish species meta
            $scientific_name = get_post_meta(get_the_ID(), 'scientific_name', true);
            $origin = get_post_meta(get_the_ID(), 'origin', true);
            $size = get_post_meta(get_the_ID(), 'size', true);
            $lifespan = get_post_meta(get_the_ID(), 'lifespan', true);
            $temperature = get_post_meta(get_the_ID(), 'temperature', true);
            $ph = get_post_meta(get_the_ID(), 'ph', true);
            $diet = get_post_meta(get_the_ID(), 'diet', true);
            $tank_size = get_post_meta(get_the_ID(), 'tank_size', true);
            $difficulty = get_post_meta(get_the_ID(), 'difficulty', true);
            $breeding = get_post_meta(get_the_ID(), 'breeding', true);
            $price_range = get_post_meta(get_the_ID(), 'price_range', true);
            
            // Get taxonomy terms
            $water_types = get_the_terms(get_the_ID(), 'water_type');
            $fish_categories = get_the_terms(get_the_ID(), 'fish_category');
            $fish_origins = get_the_terms(get_the_ID(), 'fish_origin');
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden'); ?>>
                <div class="fish-species-header relative">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="fish-species-image h-96">
                            <?php the_post_thumbnail('full', ['class' => 'w-full h-full object-cover']); ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="fish-species-title-container absolute bottom-0 left-0 right-0 p-6 md:p-8 text-white">
                        <h1 class="entry-title text-3xl md:text-4xl font-bold mb-2"><?php the_title(); ?></h1>
                        
                        <?php if ($scientific_name) : ?>
                            <p class="scientific-name text-xl italic opacity-90 mb-4"><?php echo esc_html($scientific_name); ?></p>
                        <?php endif; ?>
                        
                        <div class="fish-species-meta flex flex-wrap gap-4">
                            <?php if ($water_types && !is_wp_error($water_types)) : ?>
                                <span class="bg-primary text-white px-3 py-1 rounded-full text-sm flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <?php 
                                    $water_type_names = array();
                                    foreach ($water_types as $water_type) {
                                        $water_type_names[] = $water_type->name;
                                    }
                                    echo esc_html(implode(', ', $water_type_names)); 
                                    ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ($difficulty) : ?>
                                <span class="bg-accent text-primary-dark px-3 py-1 rounded-full text-sm flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <?php 
                                    $difficulty_labels = array(
                                        '1' => esc_html__('Beginner', 'aqualuxe'),
                                        '2' => esc_html__('Easy', 'aqualuxe'),
                                        '3' => esc_html__('Moderate', 'aqualuxe'),
                                        '4' => esc_html__('Advanced', 'aqualuxe'),
                                        '5' => esc_html__('Expert', 'aqualuxe'),
                                    );
                                    echo isset($difficulty_labels[$difficulty]) ? $difficulty_labels[$difficulty] : esc_html($difficulty);
                                    ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ($price_range) : ?>
                                <span class="bg-secondary text-white px-3 py-1 rounded-full text-sm flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <?php echo esc_html($price_range); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="fish-species-content p-6 md:p-8">
                    <div class="fish-species-specs grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 bg-gray-50 dark:bg-gray-800 p-6 rounded-xl">
                        <?php if ($size) : ?>
                            <div class="spec-item">
                                <h3 class="text-sm uppercase text-gray-500 dark:text-gray-400 mb-1"><?php esc_html_e('Size', 'aqualuxe'); ?></h3>
                                <p class="text-lg font-medium"><?php echo esc_html($size); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($lifespan) : ?>
                            <div class="spec-item">
                                <h3 class="text-sm uppercase text-gray-500 dark:text-gray-400 mb-1"><?php esc_html_e('Lifespan', 'aqualuxe'); ?></h3>
                                <p class="text-lg font-medium"><?php echo esc_html($lifespan); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($temperature) : ?>
                            <div class="spec-item">
                                <h3 class="text-sm uppercase text-gray-500 dark:text-gray-400 mb-1"><?php esc_html_e('Temperature', 'aqualuxe'); ?></h3>
                                <p class="text-lg font-medium"><?php echo esc_html($temperature); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($ph) : ?>
                            <div class="spec-item">
                                <h3 class="text-sm uppercase text-gray-500 dark:text-gray-400 mb-1"><?php esc_html_e('pH Level', 'aqualuxe'); ?></h3>
                                <p class="text-lg font-medium"><?php echo esc_html($ph); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($diet) : ?>
                            <div class="spec-item">
                                <h3 class="text-sm uppercase text-gray-500 dark:text-gray-400 mb-1"><?php esc_html_e('Diet', 'aqualuxe'); ?></h3>
                                <p class="text-lg font-medium"><?php echo esc_html($diet); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($tank_size) : ?>
                            <div class="spec-item">
                                <h3 class="text-sm uppercase text-gray-500 dark:text-gray-400 mb-1"><?php esc_html_e('Minimum Tank Size', 'aqualuxe'); ?></h3>
                                <p class="text-lg font-medium"><?php echo esc_html($tank_size); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($origin) : ?>
                            <div class="spec-item">
                                <h3 class="text-sm uppercase text-gray-500 dark:text-gray-400 mb-1"><?php esc_html_e('Origin', 'aqualuxe'); ?></h3>
                                <p class="text-lg font-medium"><?php echo esc_html($origin); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($breeding) : ?>
                            <div class="spec-item">
                                <h3 class="text-sm uppercase text-gray-500 dark:text-gray-400 mb-1"><?php esc_html_e('Breeding', 'aqualuxe'); ?></h3>
                                <p class="text-lg font-medium"><?php echo esc_html($breeding); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($fish_categories && !is_wp_error($fish_categories)) : ?>
                            <div class="spec-item">
                                <h3 class="text-sm uppercase text-gray-500 dark:text-gray-400 mb-1"><?php esc_html_e('Categories', 'aqualuxe'); ?></h3>
                                <p class="text-lg font-medium">
                                    <?php 
                                    $category_names = array();
                                    foreach ($fish_categories as $category) {
                                        $category_names[] = $category->name;
                                    }
                                    echo esc_html(implode(', ', $category_names)); 
                                    ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="fish-species-description prose prose-lg dark:prose-invert max-w-none mb-8">
                        <?php the_content(); ?>
                    </div>

                    <?php
                    // Display gallery if available
                    $gallery = get_post_meta(get_the_ID(), 'gallery', true);
                    if ($gallery) :
                        $gallery_ids = explode(',', $gallery);
                        if (!empty($gallery_ids)) :
                        ?>
                        <div class="fish-species-gallery mb-8">
                            <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Gallery', 'aqualuxe'); ?></h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                <?php foreach ($gallery_ids as $image_id) : ?>
                                    <div class="gallery-item">
                                        <?php echo wp_get_attachment_image($image_id, 'medium', false, ['class' => 'w-full h-48 object-cover rounded-lg']); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php
                        endif;
                    endif;
                    ?>

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
                                <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Related Products', 'aqualuxe'); ?></h2>
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

                    <div class="fish-species-taxonomy mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <?php if ($fish_categories && !is_wp_error($fish_categories)) : ?>
                            <div class="taxonomy-item mb-4">
                                <span class="text-gray-600 dark:text-gray-400 mr-2"><?php esc_html_e('Categories:', 'aqualuxe'); ?></span>
                                <?php
                                foreach ($fish_categories as $index => $category) {
                                    echo '<a href="' . esc_url(get_term_link($category)) . '" class="text-primary hover:text-primary-dark transition-colors duration-300">' . esc_html($category->name) . '</a>';
                                    if ($index < count($fish_categories) - 1) {
                                        echo ', ';
                                    }
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($water_types && !is_wp_error($water_types)) : ?>
                            <div class="taxonomy-item mb-4">
                                <span class="text-gray-600 dark:text-gray-400 mr-2"><?php esc_html_e('Water Types:', 'aqualuxe'); ?></span>
                                <?php
                                foreach ($water_types as $index => $water_type) {
                                    echo '<a href="' . esc_url(get_term_link($water_type)) . '" class="text-primary hover:text-primary-dark transition-colors duration-300">' . esc_html($water_type->name) . '</a>';
                                    if ($index < count($water_types) - 1) {
                                        echo ', ';
                                    }
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($fish_origins && !is_wp_error($fish_origins)) : ?>
                            <div class="taxonomy-item">
                                <span class="text-gray-600 dark:text-gray-400 mr-2"><?php esc_html_e('Origins:', 'aqualuxe'); ?></span>
                                <?php
                                foreach ($fish_origins as $index => $origin) {
                                    echo '<a href="' . esc_url(get_term_link($origin)) . '" class="text-primary hover:text-primary-dark transition-colors duration-300">' . esc_html($origin->name) . '</a>';
                                    if ($index < count($fish_origins) - 1) {
                                        echo ', ';
                                    }
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="fish-species-navigation mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row justify-between">
                            <div class="prev-post mb-4 sm:mb-0">
                                <?php
                                $prev_post = get_previous_post();
                                if (!empty($prev_post)) :
                                    ?>
                                    <span class="block text-sm text-gray-600 dark:text-gray-400 mb-1"><?php esc_html_e('Previous Species', 'aqualuxe'); ?></span>
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
                                    <span class="block text-sm text-gray-600 dark:text-gray-400 mb-1"><?php esc_html_e('Next Species', 'aqualuxe'); ?></span>
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