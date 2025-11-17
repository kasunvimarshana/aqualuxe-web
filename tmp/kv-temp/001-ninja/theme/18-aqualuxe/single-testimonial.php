<?php
/**
 * The template for displaying Testimonial single posts
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
            
            // Get testimonial meta
            $rating = get_post_meta(get_the_ID(), 'rating', true);
            $position = get_post_meta(get_the_ID(), 'position', true);
            $company = get_post_meta(get_the_ID(), 'company', true);
            $location = get_post_meta(get_the_ID(), 'location', true);
            $date = get_post_meta(get_the_ID(), 'date', true);
            $video_url = get_post_meta(get_the_ID(), 'video_url', true);
            $product_id = get_post_meta(get_the_ID(), 'product_id', true);
            $service_id = get_post_meta(get_the_ID(), 'service_id', true);
            
            // Get taxonomy terms
            $testimonial_categories = get_the_terms(get_the_ID(), 'testimonial_category');
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden'); ?>>
                <div class="testimonial-content p-6 md:p-8">
                    <div class="flex flex-col md:flex-row">
                        <div class="testimonial-main md:w-2/3 md:pr-8">
                            <header class="entry-header mb-6">
                                <h1 class="entry-title text-3xl md:text-4xl font-bold mb-4"><?php the_title(); ?></h1>
                                
                                <?php if ($rating) : ?>
                                    <div class="testimonial-rating flex mb-4">
                                        <?php
                                        $rating = min(5, max(1, intval($rating)));
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-accent" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>';
                                            } else {
                                                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-300 dark:text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>';
                                            }
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($testimonial_categories && !is_wp_error($testimonial_categories)) : ?>
                                    <div class="testimonial-categories mb-4">
                                        <?php foreach ($testimonial_categories as $category) : ?>
                                            <span class="bg-primary-light text-primary-dark text-sm px-3 py-1 rounded-full mr-2">
                                                <?php echo esc_html($category->name); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </header>

                            <div class="testimonial-quote mb-8">
                                <div class="quote-icon text-primary opacity-20 mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M13 14.725c0-5.141 3.892-10.519 10-11.725l.984 2.126c-2.215.835-4.163 3.742-4.38 5.746 2.491.392 4.396 2.547 4.396 5.149 0 3.182-2.584 4.979-5.199 4.979-3.015 0-5.801-2.305-5.801-6.275zm-13 0c0-5.141 3.892-10.519 10-11.725l.984 2.126c-2.215.835-4.163 3.742-4.38 5.746 2.491.392 4.396 2.547 4.396 5.149 0 3.182-2.584 4.979-5.199 4.979-3.015 0-5.801-2.305-5.801-6.275z"/>
                                    </svg>
                                </div>
                                
                                <div class="testimonial-text prose prose-lg dark:prose-invert max-w-none mb-6 text-xl italic">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                            
                            <?php if ($video_url) : ?>
                                <div class="testimonial-video mb-8">
                                    <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Video Testimonial', 'aqualuxe'); ?></h2>
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
                            
                            <?php
                            // Display related product if available
                            if ($product_id && class_exists('WooCommerce')) :
                                $product = wc_get_product($product_id);
                                if ($product) :
                                ?>
                                <div class="related-product mb-8">
                                    <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Related Product', 'aqualuxe'); ?></h2>
                                    <div class="product-card bg-gray-50 dark:bg-gray-800 rounded-xl overflow-hidden">
                                        <div class="flex flex-col md:flex-row">
                                            <div class="product-image md:w-1/3">
                                                <?php if ($product->get_image_id()) : ?>
                                                    <a href="<?php echo esc_url(get_permalink($product_id)); ?>">
                                                        <?php echo wp_get_attachment_image($product->get_image_id(), 'medium', false, ['class' => 'w-full h-full object-cover']); ?>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="product-content p-6 md:w-2/3">
                                                <h3 class="product-title text-xl font-bold mb-2">
                                                    <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="hover:text-primary transition-colors duration-300">
                                                        <?php echo esc_html($product->get_name()); ?>
                                                    </a>
                                                </h3>
                                                
                                                <div class="product-price text-primary font-medium mb-3">
                                                    <?php echo $product->get_price_html(); ?>
                                                </div>
                                                
                                                <div class="product-excerpt text-gray-600 dark:text-gray-300 mb-4">
                                                    <?php echo wp_kses_post($product->get_short_description()); ?>
                                                </div>
                                                
                                                <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="btn btn-primary">
                                                    <?php echo esc_html($product->is_purchasable() && $product->is_in_stock() ? __('Add to cart', 'aqualuxe') : __('Read more', 'aqualuxe')); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php
                            // Display related service if available
                            if ($service_id) :
                                $service = get_post($service_id);
                                if ($service && $service->post_type === 'service') :
                                ?>
                                <div class="related-service mb-8">
                                    <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Related Service', 'aqualuxe'); ?></h2>
                                    <div class="service-card bg-gray-50 dark:bg-gray-800 rounded-xl overflow-hidden">
                                        <div class="flex flex-col md:flex-row">
                                            <div class="service-image md:w-1/3">
                                                <?php if (has_post_thumbnail($service_id)) : ?>
                                                    <a href="<?php echo esc_url(get_permalink($service_id)); ?>">
                                                        <?php echo get_the_post_thumbnail($service_id, 'medium', ['class' => 'w-full h-full object-cover']); ?>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="service-content p-6 md:w-2/3">
                                                <h3 class="service-title text-xl font-bold mb-2">
                                                    <a href="<?php echo esc_url(get_permalink($service_id)); ?>" class="hover:text-primary transition-colors duration-300">
                                                        <?php echo esc_html(get_the_title($service_id)); ?>
                                                    </a>
                                                </h3>
                                                
                                                <div class="service-excerpt text-gray-600 dark:text-gray-300 mb-4">
                                                    <?php echo wp_kses_post(get_the_excerpt($service_id)); ?>
                                                </div>
                                                
                                                <a href="<?php echo esc_url(get_permalink($service_id)); ?>" class="btn btn-primary">
                                                    <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="testimonial-sidebar md:w-1/3">
                            <div class="testimonial-author bg-gray-50 dark:bg-gray-800 rounded-xl p-6 mb-6">
                                <div class="author-info flex items-center mb-4">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="author-image mr-4">
                                            <?php the_post_thumbnail('thumbnail', ['class' => 'w-16 h-16 rounded-full']); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div>
                                        <h3 class="author-name font-bold text-lg"><?php the_title(); ?></h3>
                                        <?php if ($position || $company) : ?>
                                            <p class="author-title text-gray-600 dark:text-gray-400">
                                                <?php 
                                                if ($position) {
                                                    echo esc_html($position);
                                                    if ($company) {
                                                        echo ', ' . esc_html($company);
                                                    }
                                                } elseif ($company) {
                                                    echo esc_html($company);
                                                }
                                                ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php if ($location || $date) : ?>
                                    <div class="author-details space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                        <?php if ($location) : ?>
                                            <div class="author-location flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                <?php echo esc_html($location); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($date) : ?>
                                            <div class="testimonial-date flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <?php echo esc_html($date); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php
                            // Display more testimonials
                            $args = array(
                                'post_type' => 'testimonial',
                                'posts_per_page' => 3,
                                'post__not_in' => array(get_the_ID()),
                                'orderby' => 'rand',
                            );
                            
                            // If the current testimonial has categories, prioritize testimonials from the same categories
                            if ($testimonial_categories && !is_wp_error($testimonial_categories)) {
                                $category_ids = wp_list_pluck($testimonial_categories, 'term_id');
                                $args['tax_query'] = array(
                                    array(
                                        'taxonomy' => 'testimonial_category',
                                        'field' => 'term_id',
                                        'terms' => $category_ids,
                                    ),
                                );
                            }
                            
                            $more_testimonials = new WP_Query($args);
                            
                            if ($more_testimonials->have_posts()) :
                            ?>
                                <div class="more-testimonials bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
                                    <h3 class="text-xl font-bold mb-4"><?php esc_html_e('More Testimonials', 'aqualuxe'); ?></h3>
                                    
                                    <div class="testimonials-list space-y-6">
                                        <?php while ($more_testimonials->have_posts()) : $more_testimonials->the_post(); ?>
                                            <div class="testimonial-item border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0 last:pb-0">
                                                <?php
                                                $item_rating = get_post_meta(get_the_ID(), 'rating', true);
                                                if ($item_rating) :
                                                ?>
                                                    <div class="testimonial-rating flex mb-2">
                                                        <?php
                                                        $item_rating = min(5, max(1, intval($item_rating)));
                                                        for ($i = 1; $i <= 5; $i++) {
                                                            if ($i <= $item_rating) {
                                                                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-accent" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>';
                                                            } else {
                                                                echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300 dark:text-gray-600" viewBox="0 0 20 20" fill="currentColor">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>';
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div class="testimonial-excerpt text-sm mb-2">
                                                    <?php echo wp_trim_words(get_the_content(), 20, '...'); ?>
                                                </div>
                                                
                                                <div class="testimonial-author flex items-center">
                                                    <?php if (has_post_thumbnail()) : ?>
                                                        <div class="author-image mr-3">
                                                            <?php the_post_thumbnail('thumbnail', ['class' => 'w-8 h-8 rounded-full']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <div>
                                                        <h4 class="author-name font-medium text-sm">
                                                            <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors duration-300">
                                                                <?php the_title(); ?>
                                                            </a>
                                                        </h4>
                                                        <?php
                                                        $item_position = get_post_meta(get_the_ID(), 'position', true);
                                                        $item_company = get_post_meta(get_the_ID(), 'company', true);
                                                        if ($item_position || $item_company) :
                                                        ?>
                                                            <p class="author-title text-xs text-gray-600 dark:text-gray-400">
                                                                <?php 
                                                                if ($item_position) {
                                                                    echo esc_html($item_position);
                                                                    if ($item_company) {
                                                                        echo ', ' . esc_html($item_company);
                                                                    }
                                                                } elseif ($item_company) {
                                                                    echo esc_html($item_company);
                                                                }
                                                                ?>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                    
                                    <div class="mt-4 text-center">
                                        <a href="<?php echo esc_url(get_post_type_archive_link('testimonial')); ?>" class="text-primary hover:text-primary-dark font-medium transition-colors duration-300 flex items-center justify-center">
                                            <?php esc_html_e('View All Testimonials', 'aqualuxe'); ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            <?php
                            wp_reset_postdata();
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </article><!-- #post-<?php the_ID(); ?> -->

        <?php endwhile; // End of the loop. ?>
    </div>
</main><!-- #main -->

<?php
get_footer();