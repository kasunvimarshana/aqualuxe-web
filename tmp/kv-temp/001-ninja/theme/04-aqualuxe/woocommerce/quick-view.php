<?php
/**
 * Quick view template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

global $product;
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('product-quick-view', $product); ?>>
    <div class="quick-view-content">
        <div class="quick-view-image">
            <?php
            if (has_post_thumbnail()) {
                $image_id = get_post_thumbnail_id();
                $image_url = wp_get_attachment_image_url($image_id, 'full');
                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                
                echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" />';
                
                // Display product badges
                if ($product->is_on_sale()) {
                    echo apply_filters('woocommerce_sale_flash', '<span class="onsale">' . esc_html__('Sale!', 'aqualuxe') . '</span>', $post, $product);
                }
                
                if ($product->is_featured()) {
                    echo '<span class="featured-badge">' . esc_html__('Featured', 'aqualuxe') . '</span>';
                }
                
                // New badge
                $newness_days = 30;
                $created = strtotime($product->get_date_created());
                if ((time() - (60 * 60 * 24 * $newness_days)) < $created) {
                    echo '<span class="new-badge">' . esc_html__('New', 'aqualuxe') . '</span>';
                }
                
                // Out of stock badge
                if (!$product->is_in_stock()) {
                    echo '<span class="out-of-stock-badge">' . esc_html__('Out of Stock', 'aqualuxe') . '</span>';
                }
            }
            ?>
        </div>
        
        <div class="quick-view-summary">
            <h2 class="product_title"><?php the_title(); ?></h2>
            
            <div class="price-rating">
                <div class="price"><?php echo $product->get_price_html(); ?></div>
                
                <?php if ($product->get_rating_count() > 0) : ?>
                    <div class="rating">
                        <?php wc_get_template('single-product/rating.php'); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="quick-view-description">
                <?php echo apply_filters('woocommerce_short_description', $product->get_short_description()); ?>
            </div>
            
            <?php if ($product->is_in_stock()) : ?>
                <div class="quick-view-add-to-cart">
                    <?php
                    if ($product->is_type('simple')) {
                        echo '<form class="cart" method="post" enctype="multipart/form-data">';
                        
                        // Quantity
                        woocommerce_quantity_input(array(
                            'min_value'   => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                            'max_value'   => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                            'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(),
                        ));
                        
                        // Add to cart button
                        echo '<button type="submit" name="add-to-cart" value="' . esc_attr($product->get_id()) . '" class="single_add_to_cart_button button alt">' . esc_html($product->single_add_to_cart_text()) . '</button>';
                        
                        echo '</form>';
                    } else {
                        echo '<a href="' . esc_url($product->get_permalink()) . '" class="button">' . esc_html__('View Product', 'aqualuxe') . '</a>';
                    }
                    ?>
                </div>
            <?php else : ?>
                <div class="quick-view-out-of-stock">
                    <p class="stock out-of-stock"><?php esc_html_e('This product is currently out of stock and unavailable.', 'aqualuxe'); ?></p>
                </div>
            <?php endif; ?>
            
            <div class="quick-view-meta">
                <?php if ($product->get_sku()) : ?>
                    <div class="product-sku">
                        <span class="label"><?php esc_html_e('SKU:', 'aqualuxe'); ?></span>
                        <span class="value"><?php echo esc_html($product->get_sku()); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($product->get_category_ids()) : ?>
                    <div class="product-categories">
                        <span class="label"><?php esc_html_e('Categories:', 'aqualuxe'); ?></span>
                        <span class="value"><?php echo wc_get_product_category_list($product->get_id(), ', '); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($product->get_tag_ids()) : ?>
                    <div class="product-tags">
                        <span class="label"><?php esc_html_e('Tags:', 'aqualuxe'); ?></span>
                        <span class="value"><?php echo wc_get_product_tag_list($product->get_id(), ', '); ?></span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="quick-view-actions">
                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="view-details-button">
                    <?php esc_html_e('View Full Details', 'aqualuxe'); ?>
                    <i class="fas fa-arrow-right"></i>
                </a>
                
                <div class="quick-view-buttons">
                    <a href="#" class="wishlist-button" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        <i class="fas fa-heart"></i>
                        <span><?php esc_html_e('Add to Wishlist', 'aqualuxe'); ?></span>
                    </a>
                    
                    <a href="#" class="compare-button" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        <i class="fas fa-sync-alt"></i>
                        <span><?php esc_html_e('Compare', 'aqualuxe'); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>