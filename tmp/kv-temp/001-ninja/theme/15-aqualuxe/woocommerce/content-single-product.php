<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}

// Get product display options
$layout_style = get_theme_mod('aqualuxe_product_layout', 'standard');
$enable_sticky_info = get_theme_mod('aqualuxe_enable_sticky_product_info', true);
$enable_image_zoom = get_theme_mod('aqualuxe_enable_product_image_zoom', true);
$enable_image_lightbox = get_theme_mod('aqualuxe_enable_product_image_lightbox', true);
$show_sku = get_theme_mod('aqualuxe_show_product_sku', true);
$show_categories = get_theme_mod('aqualuxe_show_product_categories', true);
$show_tags = get_theme_mod('aqualuxe_show_product_tags', true);
$show_stock = get_theme_mod('aqualuxe_show_product_stock', true);
$show_brand = get_theme_mod('aqualuxe_show_product_brand', true);
$show_social_share = get_theme_mod('aqualuxe_show_product_social_share', true);
$show_specs = get_theme_mod('aqualuxe_show_product_specs', true);
$show_shipping = get_theme_mod('aqualuxe_show_product_shipping', true);

// Set product info classes
$product_info_class = $enable_sticky_info ? 'lg:sticky lg:top-24' : '';
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>
    <div class="product-main bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden mb-8">
        <div class="flex flex-col md:flex-row">
            <div class="product-gallery md:w-1/2">
                <?php
                /**
                 * Hook: woocommerce_before_single_product_summary.
                 *
                 * @hooked woocommerce_show_product_sale_flash - 10
                 * @hooked woocommerce_show_product_images - 20
                 */
                do_action('woocommerce_before_single_product_summary');
                ?>
            </div>

            <div class="product-summary md:w-1/2 p-6 md:p-8">
                <div class="<?php echo esc_attr($product_info_class); ?>">
                    <?php
                    /**
                     * Hook: woocommerce_single_product_summary.
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
                    ?>
                    <h1 class="product_title entry-title text-2xl md:text-3xl font-bold mb-2"><?php the_title(); ?></h1>
                    
                    <?php if ($show_brand && function_exists('the_terms')) : ?>
                        <div class="product-brand mb-2">
                            <?php the_terms(get_the_ID(), 'product_brand', '<span class="text-sm text-gray-600 dark:text-gray-400">' . esc_html__('Brand: ', 'aqualuxe') . '</span>'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="product-rating-price flex flex-wrap items-center justify-between mb-4">
                        <div class="product-rating">
                            <?php woocommerce_template_single_rating(); ?>
                        </div>
                        
                        <div class="product-price text-xl font-bold text-primary-600 dark:text-primary-400">
                            <?php woocommerce_template_single_price(); ?>
                        </div>
                    </div>
                    
                    <?php if ($show_stock) : ?>
                        <div class="product-stock mb-4">
                            <?php if ($product->is_in_stock()) : ?>
                                <span class="in-stock text-sm text-green-600 dark:text-green-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <?php esc_html_e('In Stock', 'aqualuxe'); ?>
                                    
                                    <?php if ($product->managing_stock()) : ?>
                                        <span class="stock-quantity">(<?php echo esc_html($product->get_stock_quantity()); ?> <?php esc_html_e('available', 'aqualuxe'); ?>)</span>
                                    <?php endif; ?>
                                </span>
                            <?php else : ?>
                                <span class="out-of-stock text-sm text-red-600 dark:text-red-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    <?php esc_html_e('Out of Stock', 'aqualuxe'); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="product-short-description mb-6 text-gray-600 dark:text-gray-300">
                        <?php woocommerce_template_single_excerpt(); ?>
                    </div>
                    
                    <div class="product-add-to-cart mb-6">
                        <?php woocommerce_template_single_add_to_cart(); ?>
                    </div>
                    
                    <?php if ($show_shipping) : ?>
                        <div class="product-shipping mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-md">
                            <h3 class="text-lg font-bold mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                                <?php esc_html_e('Shipping Information', 'aqualuxe'); ?>
                            </h3>
                            
                            <ul class="text-sm space-y-2">
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <?php esc_html_e('Free shipping on orders over $50', 'aqualuxe'); ?>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <?php esc_html_e('Estimated delivery: 2-5 business days', 'aqualuxe'); ?>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 mt-0.5 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <?php esc_html_e('International shipping available', 'aqualuxe'); ?>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <div class="product-meta text-sm text-gray-600 dark:text-gray-400 space-y-2 mb-6">
                        <?php if ($show_sku && $product->get_sku()) : ?>
                            <div class="product-sku">
                                <span class="font-medium"><?php esc_html_e('SKU:', 'aqualuxe'); ?></span> <?php echo esc_html($product->get_sku()); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($show_categories) : ?>
                            <div class="product-categories">
                                <?php echo wc_get_product_category_list($product->get_id(), ', ', '<span class="font-medium">' . esc_html__('Categories:', 'aqualuxe') . '</span> '); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($show_tags) : ?>
                            <div class="product-tags">
                                <?php echo wc_get_product_tag_list($product->get_id(), ', ', '<span class="font-medium">' . esc_html__('Tags:', 'aqualuxe') . '</span> '); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($show_social_share) : ?>
                        <div class="product-share border-t border-gray-200 dark:border-gray-600 pt-4">
                            <h3 class="text-sm font-medium mb-2"><?php esc_html_e('Share This Product:', 'aqualuxe'); ?></h3>
                            <div class="flex space-x-3">
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400">
                                    <span class="sr-only"><?php esc_html_e('Share on Facebook', 'aqualuxe'); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                    </svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>&url=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400">
                                    <span class="sr-only"><?php esc_html_e('Share on Twitter', 'aqualuxe'); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                    </svg>
                                </a>
                                <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&media=<?php echo urlencode(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>&description=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400">
                                    <span class="sr-only"><?php esc_html_e('Pin on Pinterest', 'aqualuxe'); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0c-6.627 0-12 5.373-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z"/>
                                    </svg>
                                </a>
                                <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>" class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400">
                                    <span class="sr-only"><?php esc_html_e('Share via Email', 'aqualuxe'); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="product-tabs bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden mb-8">
        <div x-data="{ activeTab: 'description' }">
            <div class="product-tabs-nav border-b border-gray-200 dark:border-gray-600">
                <div class="flex overflow-x-auto">
                    <button 
                        @click="activeTab = 'description'" 
                        :class="{ 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400': activeTab === 'description', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-500': activeTab !== 'description' }"
                        class="py-4 px-6 border-b-2 font-medium text-sm md:text-base whitespace-nowrap focus:outline-none"
                    >
                        <?php esc_html_e('Description', 'aqualuxe'); ?>
                    </button>
                    
                    <?php if ($show_specs) : ?>
                    <button 
                        @click="activeTab = 'specifications'" 
                        :class="{ 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400': activeTab === 'specifications', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-500': activeTab !== 'specifications' }"
                        class="py-4 px-6 border-b-2 font-medium text-sm md:text-base whitespace-nowrap focus:outline-none"
                    >
                        <?php esc_html_e('Specifications', 'aqualuxe'); ?>
                    </button>
                    <?php endif; ?>
                    
                    <button 
                        @click="activeTab = 'reviews'" 
                        :class="{ 'border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400': activeTab === 'reviews', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300 dark:hover:border-gray-500': activeTab !== 'reviews' }"
                        class="py-4 px-6 border-b-2 font-medium text-sm md:text-base whitespace-nowrap focus:outline-none"
                    >
                        <?php esc_html_e('Reviews', 'aqualuxe'); ?> (<?php echo $product->get_review_count(); ?>)
                    </button>
                </div>
            </div>
            
            <div class="product-tabs-content p-6">
                <div x-show="activeTab === 'description'" class="product-description prose dark:prose-invert max-w-none">
                    <?php the_content(); ?>
                </div>
                
                <?php if ($show_specs) : ?>
                <div x-show="activeTab === 'specifications'" class="product-specifications" x-cloak>
                    <?php
                    $attributes = $product->get_attributes();
                    if ($attributes) :
                    ?>
                        <table class="w-full text-left border-collapse">
                            <tbody>
                                <?php foreach ($attributes as $attribute) : ?>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="py-3 pr-4 w-1/3 font-medium"><?php echo wc_attribute_label($attribute->get_name()); ?></th>
                                        <td class="py-3 text-gray-600 dark:text-gray-300">
                                            <?php
                                            if ($attribute->is_taxonomy()) {
                                                $values = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'names'));
                                                echo apply_filters('woocommerce_attribute', wpautop(wptexturize(implode(', ', $values))), $attribute, $values);
                                            } else {
                                                $values = $attribute->get_options();
                                                echo apply_filters('woocommerce_attribute', wpautop(wptexturize(implode(', ', $values))), $attribute, $values);
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                
                                <?php if ($product->get_weight()) : ?>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="py-3 pr-4 w-1/3 font-medium"><?php esc_html_e('Weight', 'aqualuxe'); ?></th>
                                        <td class="py-3 text-gray-600 dark:text-gray-300"><?php echo esc_html($product->get_weight()); ?> <?php echo esc_html(get_option('woocommerce_weight_unit')); ?></td>
                                    </tr>
                                <?php endif; ?>
                                
                                <?php if ($product->get_dimensions()) : ?>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="py-3 pr-4 w-1/3 font-medium"><?php esc_html_e('Dimensions', 'aqualuxe'); ?></th>
                                        <td class="py-3 text-gray-600 dark:text-gray-300"><?php echo esc_html($product->get_dimensions()); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p class="text-gray-600 dark:text-gray-300"><?php esc_html_e('No specifications available for this product.', 'aqualuxe'); ?></p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <div x-show="activeTab === 'reviews'" class="product-reviews" x-cloak>
                    <?php
                    // Display product reviews
                    comments_template();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php
    /**
     * Hook: woocommerce_after_single_product_summary.
     *
     * @hooked woocommerce_output_product_data_tabs - 10
     * @hooked woocommerce_upsell_display - 15
     * @hooked woocommerce_output_related_products - 20
     */
    do_action('woocommerce_after_single_product_summary');
    ?>
</div>

<?php do_action('woocommerce_after_single_product'); ?>