<?php
/**
 * Template part for displaying the WooCommerce quick view content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Only show if WooCommerce is active
if (!aqualuxe_is_woocommerce_active()) {
    return;
}

global $product;

// Make sure the global product is set
if (!is_a($product, 'WC_Product')) {
    return;
}
?>

<div class="quick-view-content">
    <div class="flex flex-col md:flex-row -mx-4">
        <div class="quick-view-images w-full md:w-1/2 px-4">
            <?php
            // Product gallery
            $attachment_ids = $product->get_gallery_image_ids();
            $post_thumbnail_id = $product->get_image_id();
            
            if ($post_thumbnail_id) {
                $html = wc_get_gallery_image_html($post_thumbnail_id, true);
                echo '<div class="quick-view-main-image mb-4">' . $html . '</div>';
            }
            
            if ($attachment_ids && $product->get_image_id()) {
                echo '<div class="quick-view-thumbnails grid grid-cols-4 gap-2">';
                
                // Add the featured image first
                echo '<div class="quick-view-thumbnail cursor-pointer border-2 border-primary-600">';
                echo wp_get_attachment_image($post_thumbnail_id, 'thumbnail', false, ['class' => 'w-full h-auto']);
                echo '</div>';
                
                // Add gallery images
                $count = 1;
                foreach ($attachment_ids as $attachment_id) {
                    if ($count >= 4) {
                        break; // Only show up to 3 gallery images
                    }
                    
                    echo '<div class="quick-view-thumbnail cursor-pointer border-2 border-transparent hover:border-primary-600">';
                    echo wp_get_attachment_image($attachment_id, 'thumbnail', false, ['class' => 'w-full h-auto']);
                    echo '</div>';
                    
                    $count++;
                }
                
                echo '</div>';
            }
            ?>
        </div>
        
        <div class="quick-view-summary w-full md:w-1/2 px-4 mt-6 md:mt-0">
            <h1 class="product_title entry-title text-2xl font-bold mb-2"><?php the_title(); ?></h1>
            
            <div class="quick-view-price mb-4">
                <?php echo $product->get_price_html(); ?>
            </div>
            
            <?php if ($product->get_rating_count() > 0) : ?>
                <div class="quick-view-rating mb-4">
                    <?php echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count()); ?>
                    <span class="quick-view-review-count text-sm text-gray-600 dark:text-gray-400 ml-2">
                        <?php
                        printf(
                            _n('(%s review)', '(%s reviews)', $product->get_review_count(), 'aqualuxe'),
                            esc_html($product->get_review_count())
                        );
                        ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <div class="quick-view-excerpt mb-6">
                <?php echo apply_filters('woocommerce_short_description', $product->get_short_description()); ?>
            </div>
            
            <?php
            // Display stock status
            if ($product->is_in_stock()) {
                echo '<div class="quick-view-stock text-green-600 dark:text-green-400 mb-4">' . esc_html__('In Stock', 'aqualuxe') . '</div>';
            } else {
                echo '<div class="quick-view-stock text-red-600 dark:text-red-400 mb-4">' . esc_html__('Out of Stock', 'aqualuxe') . '</div>';
            }
            ?>
            
            <?php
            // Add to cart form
            if ($product->is_in_stock()) {
                echo '<div class="quick-view-add-to-cart">';
                
                if ($product->is_type('variable')) {
                    echo '<div class="quick-view-variable-product mb-4">';
                    echo '<p class="text-sm text-gray-600 dark:text-gray-400">' . esc_html__('This product has multiple options. Please visit the product page to select options and add to cart.', 'aqualuxe') . '</p>';
                    echo '</div>';
                    
                    echo '<a href="' . esc_url($product->get_permalink()) . '" class="quick-view-view-product bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md inline-flex items-center transition-colors">';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
                    echo '</svg>';
                    echo esc_html__('View Product', 'aqualuxe');
                    echo '</a>';
                } else {
                    echo '<form class="cart" action="' . esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())) . '" method="post" enctype="multipart/form-data">';
                    
                    if ($product->is_purchasable()) {
                        echo woocommerce_quantity_input(
                            [
                                'min_value' => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                                'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                                'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(),
                            ],
                            $product,
                            false
                        );
                        
                        echo '<button type="submit" name="add-to-cart" value="' . esc_attr($product->get_id()) . '" class="quick-view-add-to-cart-button bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-md inline-flex items-center transition-colors mt-4">';
                        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
                        echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />';
                        echo '</svg>';
                        echo esc_html($product->single_add_to_cart_text());
                        echo '</button>';
                    }
                    
                    echo '</form>';
                }
                
                echo '</div>';
            }
            ?>
            
            <div class="quick-view-meta mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <?php if ($product->get_sku()) : ?>
                    <div class="quick-view-sku mb-2">
                        <span class="font-medium"><?php esc_html_e('SKU:', 'aqualuxe'); ?></span>
                        <span><?php echo esc_html($product->get_sku()); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($product->get_category_ids()) : ?>
                    <div class="quick-view-categories mb-2">
                        <span class="font-medium"><?php esc_html_e('Categories:', 'aqualuxe'); ?></span>
                        <span><?php echo wc_get_product_category_list($product->get_id(), ', '); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($product->get_tag_ids()) : ?>
                    <div class="quick-view-tags">
                        <span class="font-medium"><?php esc_html_e('Tags:', 'aqualuxe'); ?></span>
                        <span><?php echo wc_get_product_tag_list($product->get_id(), ', '); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="quick-view-actions mt-6">
                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="quick-view-view-details text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 inline-flex items-center transition-colors">
                    <?php esc_html_e('View Full Details', 'aqualuxe'); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>