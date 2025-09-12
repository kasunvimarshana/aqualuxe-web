<?php
/**
 * WooCommerce Product Content Template
 * 
 * Used in shop loops to display individual products
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;

// Ensure visibility
if (empty($product) || !$product->is_visible()) {
    return;
}

?>
<div <?php wc_product_class('product-card bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300', $product); ?>>
    
    <!-- Product Image -->
    <div class="product-image relative group">
        <a href="<?php the_permalink(); ?>" class="block relative overflow-hidden">
            <?php
            /**
             * Product thumbnail
             */
            echo woocommerce_get_product_thumbnail('aqualuxe-product');
            ?>
            
            <!-- Sale Badge -->
            <?php if ($product->is_on_sale()) : ?>
                <div class="sale-badge absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                    <?php
                    if ($product->get_type() === 'variable') {
                        echo esc_html__('Sale!', 'aqualuxe');
                    } else {
                        $regular_price = $product->get_regular_price();
                        $sale_price = $product->get_sale_price();
                        if ($regular_price && $sale_price) {
                            $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                            echo sprintf(__('-%d%%', 'aqualuxe'), $discount);
                        } else {
                            echo esc_html__('Sale!', 'aqualuxe');
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>
            
            <!-- Product Actions Overlay -->
            <div class="product-actions absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                <div class="flex space-x-2">
                    
                    <!-- Quick View -->
                    <button class="quick-view-btn bg-white text-gray-800 p-2 rounded-full hover:bg-primary-600 hover:text-white transition-colors" 
                            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                            title="<?php esc_attr_e('Quick View', 'aqualuxe'); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                    
                    <!-- Add to Wishlist -->
                    <button class="wishlist-btn bg-white text-gray-800 p-2 rounded-full hover:bg-red-500 hover:text-white transition-colors" 
                            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                            title="<?php esc_attr_e('Add to Wishlist', 'aqualuxe'); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </button>
                    
                    <!-- Compare -->
                    <button class="compare-btn bg-white text-gray-800 p-2 rounded-full hover:bg-blue-500 hover:text-white transition-colors" 
                            data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                            title="<?php esc_attr_e('Add to Compare', 'aqualuxe'); ?>">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </button>
                    
                </div>
            </div>
        </a>
    </div>
    
    <!-- Product Info -->
    <div class="product-info p-4">
        
        <!-- Product Categories -->
        <?php
        $product_cats = wp_get_post_terms($product->get_id(), 'product_cat');
        if (!empty($product_cats) && !is_wp_error($product_cats)) :
        ?>
            <div class="product-categories mb-2">
                <?php foreach ($product_cats as $cat) : ?>
                    <a href="<?php echo esc_url(get_term_link($cat)); ?>" 
                       class="inline-block text-xs text-primary-600 hover:text-primary-800 mr-2">
                        <?php echo esc_html($cat->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Product Title -->
        <h3 class="product-title">
            <a href="<?php the_permalink(); ?>" 
               class="text-lg font-semibold text-gray-900 dark:text-white hover:text-primary-600 transition-colors line-clamp-2">
                <?php the_title(); ?>
            </a>
        </h3>
        
        <!-- Product Rating -->
        <?php if (wc_review_ratings_enabled()) : ?>
            <div class="product-rating mb-2">
                <?php echo wc_get_rating_html($product->get_average_rating()); ?>
                <span class="rating-count text-sm text-gray-600 dark:text-gray-400 ml-1">
                    (<?php echo esc_html($product->get_review_count()); ?>)
                </span>
            </div>
        <?php endif; ?>
        
        <!-- Product Excerpt -->
        <div class="product-excerpt text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
            <?php echo wp_trim_words($product->get_short_description(), 15); ?>
        </div>
        
        <!-- Product Attributes -->
        <?php
        $attributes = $product->get_attributes();
        if (!empty($attributes)) :
            $displayed_attributes = array_slice($attributes, 0, 2); // Show first 2 attributes
        ?>
            <div class="product-attributes mb-3">
                <?php foreach ($displayed_attributes as $attribute) : ?>
                    <?php
                    if ($attribute->is_taxonomy()) {
                        $values = wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']);
                        $values_string = implode(', ', $values);
                    } else {
                        $values_string = $attribute->get_options();
                        if (is_array($values_string)) {
                            $values_string = implode(', ', $values_string);
                        }
                    }
                    ?>
                    <div class="attribute-item text-xs text-gray-500 dark:text-gray-400">
                        <span class="attribute-label font-medium"><?php echo wc_attribute_label($attribute->get_name()); ?>:</span>
                        <span class="attribute-value"><?php echo esc_html($values_string); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Product Price -->
        <div class="product-price mb-4">
            <span class="price text-lg font-bold text-primary-600">
                <?php echo $product->get_price_html(); ?>
            </span>
        </div>
        
        <!-- Stock Status -->
        <div class="stock-status mb-4">
            <?php if ($product->is_in_stock()) : ?>
                <span class="in-stock text-sm text-green-600 dark:text-green-400">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <?php esc_html_e('In Stock', 'aqualuxe'); ?>
                    
                    <?php if ($product->managing_stock()) : ?>
                        <span class="stock-quantity text-gray-500">
                            (<?php echo esc_html($product->get_stock_quantity()); ?> <?php esc_html_e('available', 'aqualuxe'); ?>)
                        </span>
                    <?php endif; ?>
                </span>
            <?php else : ?>
                <span class="out-of-stock text-sm text-red-600 dark:text-red-400">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <?php esc_html_e('Out of Stock', 'aqualuxe'); ?>
                </span>
            <?php endif; ?>
        </div>
        
        <!-- Add to Cart -->
        <div class="add-to-cart">
            <?php
            /**
             * Add to cart button
             */
            woocommerce_template_loop_add_to_cart();
            ?>
        </div>
        
    </div>
    
</div>