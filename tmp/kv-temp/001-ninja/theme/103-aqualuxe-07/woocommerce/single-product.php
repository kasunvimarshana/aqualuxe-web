<?php
/**
 * Single Product Template
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop');

/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action('woocommerce_before_main_content');
?>

<div class="single-product-wrapper">
    <div class="container mx-auto px-4 py-8">
        
        <?php while (have_posts()) : ?>
            <?php the_post(); ?>
            
            <div id="product-<?php the_ID(); ?>" <?php wc_product_class('single-product-content', $product); ?>>
                
                <!-- Product Navigation -->
                <nav class="product-navigation mb-6" aria-label="<?php esc_attr_e('Product Navigation', 'aqualuxe'); ?>">
                    <div class="flex justify-between items-center">
                        
                        <!-- Previous/Next Product -->
                        <div class="product-nav-links flex space-x-4">
                            <?php
                            $prev_product = get_previous_post(true, '', 'product_cat');
                            $next_product = get_next_post(true, '', 'product_cat');
                            
                            if ($prev_product) :
                            ?>
                                <a href="<?php echo get_permalink($prev_product->ID); ?>" class="prev-product flex items-center text-neutral-600 hover:text-primary-600 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                    <span><?php esc_html_e('Previous', 'aqualuxe'); ?></span>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($next_product) : ?>
                                <a href="<?php echo get_permalink($next_product->ID); ?>" class="next-product flex items-center text-neutral-600 hover:text-primary-600 transition-colors">
                                    <span><?php esc_html_e('Next', 'aqualuxe'); ?></span>
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Back to Shop -->
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="back-to-shop text-neutral-600 hover:text-primary-600 transition-colors">
                            <?php esc_html_e('← Back to Shop', 'aqualuxe'); ?>
                        </a>
                        
                    </div>
                </nav>
                
                <!-- Product Main Content -->
                <div class="product-main grid gap-8 lg:grid-cols-2">
                    
                    <!-- Product Images -->
                    <div class="product-images">
                        <?php
                        /**
                         * woocommerce_before_single_product_summary hook.
                         *
                         * @hooked woocommerce_show_product_sale_flash - 10
                         * @hooked woocommerce_show_product_images - 20
                         */
                        do_action('woocommerce_before_single_product_summary');
                        ?>
                        
                        <!-- Image Gallery Thumbnails Enhancement -->
                        <div class="product-image-extras mt-4">
                            <?php if ($product->get_gallery_image_ids()) : ?>
                                <div class="image-count text-sm text-neutral-600 dark:text-neutral-300">
                                    <?php
                                    $gallery_count = count($product->get_gallery_image_ids()) + 1; // +1 for main image
                                    printf(
                                        /* translators: %d: number of images */
                                        _n('%d Image', '%d Images', $gallery_count, 'aqualuxe'),
                                        $gallery_count
                                    );
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Product Summary -->
                    <div class="product-summary">
                        <?php
                        /**
                         * woocommerce_single_product_summary hook.
                         *
                         * @hooked woocommerce_template_single_title - 5
                         * @hooked woocommerce_template_single_rating - 10
                         * @hooked woocommerce_template_single_price - 10
                         * @hooked woocommerce_template_single_excerpt - 20
                         * @hooked woocommerce_template_single_add_to_cart - 30
                         * @hooked woocommerce_template_single_meta - 40
                         * @hooked woocommerce_template_single_sharing - 50
                         * @hooked WC_Structured_Data::generate_product_data() - 60
                         */
                        do_action('woocommerce_single_product_summary');
                        ?>
                        
                        <!-- Custom Product Features -->
                        <div class="product-features mt-8">
                            
                            <!-- Care Level (for aquatic products) -->
                            <?php if ($care_level = get_post_meta(get_the_ID(), '_aqualuxe_care_level', true)) : ?>
                                <div class="feature-item mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-primary-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="font-medium"><?php esc_html_e('Care Level:', 'aqualuxe'); ?></span>
                                        <span class="ml-2 text-neutral-600 dark:text-neutral-300"><?php echo esc_html($care_level); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Water Type -->
                            <?php if ($water_type = get_post_meta(get_the_ID(), '_aqualuxe_water_type', true)) : ?>
                                <div class="feature-item mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                        </svg>
                                        <span class="font-medium"><?php esc_html_e('Water Type:', 'aqualuxe'); ?></span>
                                        <span class="ml-2 text-neutral-600 dark:text-neutral-300"><?php echo esc_html($water_type); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Temperament -->
                            <?php if ($temperament = get_post_meta(get_the_ID(), '_aqualuxe_temperament', true)) : ?>
                                <div class="feature-item mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <span class="font-medium"><?php esc_html_e('Temperament:', 'aqualuxe'); ?></span>
                                        <span class="ml-2 text-neutral-600 dark:text-neutral-300"><?php echo esc_html($temperament); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Origin -->
                            <?php if ($origin = get_post_meta(get_the_ID(), '_aqualuxe_origin', true)) : ?>
                                <div class="feature-item mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="font-medium"><?php esc_html_e('Origin:', 'aqualuxe'); ?></span>
                                        <span class="ml-2 text-neutral-600 dark:text-neutral-300"><?php echo esc_html($origin); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                        </div>
                        
                        <!-- Shipping & Returns -->
                        <div class="shipping-info bg-neutral-50 dark:bg-neutral-800 rounded-lg p-6 mt-8">
                            <h3 class="font-semibold mb-4"><?php esc_html_e('Shipping & Returns', 'aqualuxe'); ?></h3>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm"><?php esc_html_e('Free shipping on orders over $50', 'aqualuxe'); ?></span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm"><?php esc_html_e('30-day money-back guarantee', 'aqualuxe'); ?></span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm"><?php esc_html_e('Live arrival guarantee', 'aqualuxe'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Trust Badges -->
                        <div class="trust-badges flex items-center justify-start space-x-4 mt-8">
                            <div class="badge flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                <span class="text-sm font-medium"><?php esc_html_e('Secure Payment', 'aqualuxe'); ?></span>
                            </div>
                            <div class="badge flex items-center">
                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span class="text-sm font-medium"><?php esc_html_e('Fast Delivery', 'aqualuxe'); ?></span>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                
                <!-- Product Tabs & Details -->
                <div class="product-details mt-12">
                    <?php
                    /**
                     * woocommerce_after_single_product_summary hook.
                     *
                     * @hooked woocommerce_output_product_data_tabs - 10
                     * @hooked woocommerce_upsell_display - 15
                     * @hooked woocommerce_output_related_products - 20
                     */
                    do_action('woocommerce_after_single_product_summary');
                    ?>
                </div>
                
            </div>
            
        <?php endwhile; // end of the loop. ?>
        
    </div>
</div>

<?php
/**
 * woocommerce_after_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');

get_footer('shop');
?>