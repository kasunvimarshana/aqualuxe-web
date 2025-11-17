<?php
/**
 * The template for displaying FAQ single posts
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
            
            // Get FAQ meta
            $short_answer = get_post_meta(get_the_ID(), 'short_answer', true);
            $related_faqs = get_post_meta(get_the_ID(), 'related_faqs', true);
            $video_url = get_post_meta(get_the_ID(), 'video_url', true);
            
            // Get taxonomy terms
            $faq_categories = get_the_terms(get_the_ID(), 'faq_category');
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden'); ?>>
                <div class="faq-content p-6 md:p-8">
                    <div class="flex flex-col lg:flex-row">
                        <div class="faq-main lg:w-2/3 lg:pr-8">
                            <header class="entry-header mb-6">
                                <?php if ($faq_categories && !is_wp_error($faq_categories)) : ?>
                                    <div class="faq-categories mb-4">
                                        <?php foreach ($faq_categories as $category) : ?>
                                            <a href="<?php echo esc_url(get_term_link($category)); ?>" class="bg-primary-light text-primary-dark text-sm px-3 py-1 rounded-full mr-2 hover:bg-primary hover:text-white transition-colors duration-300">
                                                <?php echo esc_html($category->name); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <h1 class="entry-title text-3xl md:text-4xl font-bold mb-4"><?php the_title(); ?></h1>
                            </header>

                            <?php if ($short_answer) : ?>
                                <div class="faq-short-answer bg-gray-50 dark:bg-gray-800 rounded-xl p-6 mb-8">
                                    <h2 class="text-xl font-bold mb-3"><?php esc_html_e('Quick Answer', 'aqualuxe'); ?></h2>
                                    <p class="text-lg"><?php echo esc_html($short_answer); ?></p>
                                </div>
                            <?php endif; ?>

                            <div class="faq-detailed-answer prose prose-lg dark:prose-invert max-w-none mb-8">
                                <?php the_content(); ?>
                            </div>
                            
                            <?php if ($video_url) : ?>
                                <div class="faq-video mb-8">
                                    <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Video Explanation', 'aqualuxe'); ?></h2>
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
                            // Display related products if WooCommerce is active
                            $related_product_ids = get_post_meta(get_the_ID(), 'related_products', true);
                            if ($related_product_ids && class_exists('WooCommerce')) :
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
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        <?php while ($related_products->have_posts()) : $related_products->the_post(); ?>
                                            <div class="product-card bg-gray-50 dark:bg-gray-800 rounded-xl overflow-hidden transition-transform duration-300 hover:-translate-y-1">
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
                            ?>
                            
                            <?php
                            // Display related FAQs if available
                            if ($related_faqs) :
                                $related_faq_ids = explode(',', $related_faqs);
                                $args = array(
                                    'post_type' => 'faq',
                                    'post__in' => $related_faq_ids,
                                    'posts_per_page' => -1,
                                    'post__not_in' => array(get_the_ID()),
                                );
                                $related_faqs_query = new WP_Query($args);
                                
                                if ($related_faqs_query->have_posts()) :
                                ?>
                                <div class="related-faqs mb-8">
                                    <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Related Questions', 'aqualuxe'); ?></h2>
                                    <div class="space-y-4">
                                        <?php while ($related_faqs_query->have_posts()) : $related_faqs_query->the_post(); ?>
                                            <div class="faq-item bg-gray-50 dark:bg-gray-800 rounded-xl p-6 transition-transform duration-300 hover:-translate-y-1">
                                                <h3 class="text-lg font-bold mb-2">
                                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors duration-300">
                                                        <?php the_title(); ?>
                                                    </a>
                                                </h3>
                                                
                                                <?php
                                                $item_short_answer = get_post_meta(get_the_ID(), 'short_answer', true);
                                                if ($item_short_answer) :
                                                ?>
                                                    <p class="text-gray-600 dark:text-gray-300 mb-3"><?php echo wp_kses_post(wp_trim_words($item_short_answer, 20, '...')); ?></p>
                                                <?php else : ?>
                                                    <div class="text-gray-600 dark:text-gray-300 mb-3"><?php echo wp_kses_post(wp_trim_words(get_the_content(), 20, '...')); ?></div>
                                                <?php endif; ?>
                                                
                                                <a href="<?php the_permalink(); ?>" class="text-primary hover:text-primary-dark font-medium transition-colors duration-300 flex items-center text-sm">
                                                    <?php esc_html_e('Read Full Answer', 'aqualuxe'); ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </a>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                                <?php
                                wp_reset_postdata();
                                endif;
                            endif;
                            ?>
                            
                            <div class="faq-navigation mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex flex-col sm:flex-row justify-between">
                                    <div class="prev-post mb-4 sm:mb-0">
                                        <?php
                                        $prev_post = get_previous_post();
                                        if (!empty($prev_post)) :
                                            ?>
                                            <span class="block text-sm text-gray-600 dark:text-gray-400 mb-1"><?php esc_html_e('Previous Question', 'aqualuxe'); ?></span>
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
                                            <span class="block text-sm text-gray-600 dark:text-gray-400 mb-1"><?php esc_html_e('Next Question', 'aqualuxe'); ?></span>
                                            <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="text-primary hover:text-primary-dark transition-colors duration-300 font-medium">
                                                <?php echo esc_html(get_the_title($next_post->ID)); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="faq-sidebar lg:w-1/3 mt-8 lg:mt-0">
                            <div class="sticky top-24">
                                <div class="search-box bg-gray-50 dark:bg-gray-800 rounded-xl p-6 mb-6">
                                    <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Search FAQs', 'aqualuxe'); ?></h3>
                                    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                                        <div class="flex">
                                            <input type="search" class="w-full rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-primary focus:border-primary" placeholder="<?php echo esc_attr_x('Search FAQs...', 'placeholder', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                                            <input type="hidden" name="post_type" value="faq" />
                                            <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-4 rounded-r-md transition-colors duration-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                                <?php if ($faq_categories && !is_wp_error($faq_categories)) : ?>
                                    <div class="faq-categories-box bg-gray-50 dark:bg-gray-800 rounded-xl p-6 mb-6">
                                        <h3 class="text-xl font-bold mb-4"><?php esc_html_e('FAQ Categories', 'aqualuxe'); ?></h3>
                                        <ul class="space-y-2">
                                            <?php
                                            $all_categories = get_terms(array(
                                                'taxonomy' => 'faq_category',
                                                'hide_empty' => true,
                                            ));
                                            
                                            if (!is_wp_error($all_categories)) {
                                                foreach ($all_categories as $category) {
                                                    echo '<li>';
                                                    echo '<a href="' . esc_url(get_term_link($category)) . '" class="flex items-center hover:text-primary transition-colors duration-300">';
                                                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-primary" viewBox="0 0 20 20" fill="currentColor">';
                                                    echo '<path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />';
                                                    echo '</svg>';
                                                    echo esc_html($category->name);
                                                    echo ' <span class="ml-1 text-gray-500 dark:text-gray-400">(' . esc_html($category->count) . ')</span>';
                                                    echo '</a>';
                                                    echo '</li>';
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="popular-faqs-box bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
                                    <h3 class="text-xl font-bold mb-4"><?php esc_html_e('Popular Questions', 'aqualuxe'); ?></h3>
                                    <?php
                                    $popular_faqs = new WP_Query(array(
                                        'post_type' => 'faq',
                                        'posts_per_page' => 5,
                                        'meta_key' => 'faq_views',
                                        'orderby' => 'meta_value_num',
                                        'order' => 'DESC',
                                        'post__not_in' => array(get_the_ID()),
                                    ));
                                    
                                    if ($popular_faqs->have_posts()) :
                                    ?>
                                        <ul class="space-y-4">
                                            <?php while ($popular_faqs->have_posts()) : $popular_faqs->the_post(); ?>
                                                <li>
                                                    <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors duration-300">
                                                        <h4 class="font-medium mb-1"><?php the_title(); ?></h4>
                                                    </a>
                                                    <?php
                                                    $pop_short_answer = get_post_meta(get_the_ID(), 'short_answer', true);
                                                    if ($pop_short_answer) :
                                                    ?>
                                                        <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo wp_kses_post(wp_trim_words($pop_short_answer, 10, '...')); ?></p>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endwhile; ?>
                                        </ul>
                                    <?php
                                    else :
                                        echo '<p>' . esc_html__('No FAQs found.', 'aqualuxe') . '</p>';
                                    endif;
                                    wp_reset_postdata();
                                    ?>
                                </div>
                                
                                <?php if (get_theme_mod('aqualuxe_faq_cta_title') && get_theme_mod('aqualuxe_faq_cta_text')) : ?>
                                    <div class="faq-cta-box bg-primary text-white rounded-xl p-6 mt-6">
                                        <h3 class="text-xl font-bold mb-2"><?php echo esc_html(get_theme_mod('aqualuxe_faq_cta_title')); ?></h3>
                                        <p class="mb-4"><?php echo esc_html(get_theme_mod('aqualuxe_faq_cta_text')); ?></p>
                                        <?php if (get_theme_mod('aqualuxe_faq_cta_button_text') && get_theme_mod('aqualuxe_faq_cta_button_url')) : ?>
                                            <a href="<?php echo esc_url(get_theme_mod('aqualuxe_faq_cta_button_url')); ?>" class="btn btn-accent w-full text-center">
                                                <?php echo esc_html(get_theme_mod('aqualuxe_faq_cta_button_text')); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </article><!-- #post-<?php the_ID(); ?> -->

        <?php endwhile; // End of the loop. ?>
    </div>
</main><!-- #main -->

<?php
get_footer();