<?php
/**
 * Template part for displaying individual product card
 *
 * @package AquaLuxe
 */

if (!aqualuxe_is_woocommerce_active()) {
    return;
}

global $product;

if (!$product) {
    return;
}
?>

<div class="product-card bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden group hover:shadow-lg transition-shadow duration-300" itemscope itemtype="https://schema.org/Product">
    
    <!-- Product Image -->
    <div class="product-image relative overflow-hidden bg-gray-100 dark:bg-gray-700">
        <a href="<?php the_permalink(); ?>" class="block aspect-square">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('aqualuxe-product', array(
                    'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300',
                    'loading' => 'lazy',
                    'itemprop' => 'image'
                )); ?>
            <?php else : ?>
                <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-600">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            <?php endif; ?>
        </a>
        
        <!-- Product Badges -->
        <div class="product-badges absolute top-2 left-2 flex flex-col space-y-1">
            <?php if ($product->is_on_sale()) : ?>
                <span class="sale-badge bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                    <?php esc_html_e('Sale!', 'aqualuxe'); ?>
                </span>
            <?php endif; ?>
            
            <?php if (!$product->is_in_stock()) : ?>
                <span class="out-of-stock-badge bg-gray-500 text-white text-xs font-bold px-2 py-1 rounded">
                    <?php esc_html_e('Out of Stock', 'aqualuxe'); ?>
                </span>
            <?php endif; ?>
            
            <?php if ($product->is_featured()) : ?>
                <span class="featured-badge bg-primary-500 text-white text-xs font-bold px-2 py-1 rounded">
                    <?php esc_html_e('Featured', 'aqualuxe'); ?>
                </span>
            <?php endif; ?>
        </div>
        
        <!-- Quick Actions -->
        <div class="product-actions absolute top-2 right-2 flex flex-col space-y-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            
            <!-- Wishlist -->
            <?php if (function_exists('aqualuxe_wishlist_button')) : ?>
                <?php aqualuxe_wishlist_button($product->get_id()); ?>
            <?php endif; ?>
            
            <!-- Quick View -->
            <button 
                class="quick-view-btn p-2 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900 rounded-full shadow-md transition-colors duration-200"
                data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                aria-label="<?php esc_attr_e('Quick view', 'aqualuxe'); ?>"
                title="<?php esc_attr_e('Quick view', 'aqualuxe'); ?>"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </button>
            
            <!-- Compare -->
            <?php if (function_exists('aqualuxe_compare_button')) : ?>
                <?php aqualuxe_compare_button($product->get_id()); ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Product Info -->
    <div class="product-info p-4">
        
        <!-- Product Categories -->
        <?php
        $product_cats = wp_get_post_terms($product->get_id(), 'product_cat');
        if (!empty($product_cats) && !is_wp_error($product_cats)) :
            ?>
            <div class="product-categories mb-2">
                <?php foreach (array_slice($product_cats, 0, 2) as $cat) : ?>
                    <a href="<?php echo esc_url(get_term_link($cat)); ?>" 
                       class="inline-block text-xs text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 mr-2">
                        <?php echo esc_html($cat->name); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php
        endif;
        ?>
        
        <!-- Product Title -->
        <h3 class="product-title text-lg font-semibold text-gray-900 dark:text-white mb-2 leading-tight" itemprop="name">
            <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">
                <?php the_title(); ?>
            </a>
        </h3>
        
        <!-- Product Rating -->
        <?php if (wc_review_ratings_enabled()) : ?>
            <div class="product-rating mb-2 flex items-center">
                <?php woocommerce_template_loop_rating(); ?>
            </div>
        <?php endif; ?>
        
        <!-- Product Price -->
        <div class="product-price mb-3" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
            <?php woocommerce_template_loop_price(); ?>
            <meta itemprop="price" content="<?php echo esc_attr($product->get_price()); ?>">
            <meta itemprop="priceCurrency" content="<?php echo esc_attr(get_woocommerce_currency()); ?>">
            <?php if ($product->is_in_stock()) : ?>
                <link itemprop="availability" href="https://schema.org/InStock">
            <?php else : ?>
                <link itemprop="availability" href="https://schema.org/OutOfStock">
            <?php endif; ?>
        </div>
        
        <!-- Product Description -->
        <?php if (has_excerpt()) : ?>
            <div class="product-excerpt text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                <?php the_excerpt(); ?>
            </div>
        <?php endif; ?>
        
        <!-- Add to Cart Button -->
        <div class="product-cart-button">
            <?php if ($product->is_type('simple') && $product->is_in_stock()) : ?>
                <form class="cart" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
                    <button type="submit" 
                            name="add-to-cart" 
                            value="<?php echo esc_attr($product->get_id()); ?>" 
                            class="btn btn-primary w-full add-to-cart-btn"
                            data-product_id="<?php echo esc_attr($product->get_id()); ?>"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5L21 21"></path>
                        </svg>
                        <?php echo esc_html($product->add_to_cart_text()); ?>
                    </button>
                </form>
            <?php else : ?>
                <a href="<?php the_permalink(); ?>" class="btn btn-outline w-full">
                    <?php echo esc_html($product->add_to_cart_text()); ?>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Schema.org Product Data -->
    <div style="display: none;" itemprop="manufacturer" itemscope itemtype="https://schema.org/Organization">
        <meta itemprop="name" content="<?php bloginfo('name'); ?>">
    </div>
</div>