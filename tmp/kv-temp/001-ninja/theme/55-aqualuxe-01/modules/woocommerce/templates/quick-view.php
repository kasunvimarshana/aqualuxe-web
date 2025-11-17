<?php
/**
 * Quick View Template
 *
 * @package AquaLuxe
 * @subpackage Modules/WooCommerce
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

global $product;

// Make sure the product is set
if (!isset($product) || !$product) {
    return;
}
?>

<div class="quick-view-product">
    <div class="quick-view-product-inner">
        <div class="quick-view-product-image">
            <div class="woocommerce-product-gallery">
                <?php
                if (has_post_thumbnail($product->get_id())) {
                    $attachment_id = get_post_thumbnail_id($product->get_id());
                    $full_size_image = wp_get_attachment_image_src($attachment_id, 'full');
                    $thumbnail = wp_get_attachment_image_src($attachment_id, 'woocommerce_thumbnail');
                    
                    echo '<div class="woocommerce-product-gallery__image">';
                    echo '<a href="' . esc_url($full_size_image[0]) . '">';
                    echo wp_get_attachment_image($attachment_id, 'woocommerce_single', false, array(
                        'title' => get_post_field('post_title', $attachment_id),
                        'data-caption' => get_post_field('post_excerpt', $attachment_id),
                        'data-src' => $full_size_image[0],
                        'data-large_image' => $full_size_image[0],
                        'data-large_image_width' => $full_size_image[1],
                        'data-large_image_height' => $full_size_image[2],
                        'class' => 'wp-post-image',
                    ));
                    echo '</a>';
                    echo '</div>';
                    
                    // Gallery thumbnails
                    $attachment_ids = $product->get_gallery_image_ids();
                    
                    if ($attachment_ids && count($attachment_ids) > 0) {
                        echo '<div class="quick-view-thumbnails">';
                        
                        // Add main image to thumbnails
                        echo '<div class="quick-view-thumbnail active">';
                        echo wp_get_attachment_image($attachment_id, 'woocommerce_gallery_thumbnail');
                        echo '</div>';
                        
                        // Add gallery images to thumbnails
                        foreach ($attachment_ids as $gallery_attachment_id) {
                            echo '<div class="quick-view-thumbnail">';
                            echo wp_get_attachment_image($gallery_attachment_id, 'woocommerce_gallery_thumbnail');
                            echo '</div>';
                        }
                        
                        echo '</div>';
                    }
                } else {
                    echo '<div class="woocommerce-product-gallery__image--placeholder">';
                    echo wc_placeholder_img('woocommerce_single');
                    echo '</div>';
                }
                ?>
            </div>
        </div>
        
        <div class="quick-view-product-summary">
            <h2 class="product_title"><?php echo esc_html($product->get_name()); ?></h2>
            
            <?php if ($product->get_rating_count() > 0) : ?>
                <div class="woocommerce-product-rating">
                    <?php echo wc_get_rating_html($product->get_average_rating(), $product->get_rating_count()); ?>
                    <a href="<?php echo esc_url(get_permalink($product->get_id())) . '#reviews'; ?>" class="woocommerce-review-link" rel="nofollow">
                        (<?php printf(_n('%s customer review', '%s customer reviews', $product->get_review_count(), 'aqualuxe'), '<span class="count">' . esc_html($product->get_review_count()) . '</span>'); ?>)
                    </a>
                </div>
            <?php endif; ?>
            
            <div class="price-wrapper">
                <p class="<?php echo esc_attr(apply_filters('woocommerce_product_price_class', 'price')); ?>">
                    <?php echo $product->get_price_html(); ?>
                </p>
            </div>
            
            <div class="woocommerce-product-details__short-description">
                <?php echo apply_filters('woocommerce_short_description', $product->get_short_description()); ?>
            </div>
            
            <?php
            // Add to cart form
            woocommerce_template_single_add_to_cart();
            ?>
            
            <div class="quick-view-product-meta">
                <?php if ($product->get_sku()) : ?>
                    <div class="product-sku">
                        <?php esc_html_e('SKU:', 'aqualuxe'); ?> <span><?php echo esc_html($product->get_sku()); ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if ($product->get_category_ids()) : ?>
                    <div class="product-categories">
                        <?php esc_html_e('Categories:', 'aqualuxe'); ?> <?php echo wc_get_product_category_list($product->get_id(), ', '); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($product->get_tag_ids()) : ?>
                    <div class="product-tags">
                        <?php esc_html_e('Tags:', 'aqualuxe'); ?> <?php echo wc_get_product_tag_list($product->get_id(), ', '); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="quick-view-actions">
                <a href="<?php echo esc_url(get_permalink($product->get_id())); ?>" class="button view-product-details">
                    <?php esc_html_e('View Full Details', 'aqualuxe'); ?>
                </a>
                
                <?php
                // Get module
                $module = $GLOBALS['aqualuxe_theme']->modules['woocommerce'];
                
                // Wishlist button
                if ($module->get_option('wishlist', true)) {
                    // Get wishlist
                    $wishlist = $module->get_wishlist();
                    
                    // Check if product is in wishlist
                    $in_wishlist = in_array($product->get_id(), $wishlist);
                    ?>
                    <a href="#" class="wishlist-button <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12.001 4.529c2.349-2.109 5.979-2.039 8.242.228 2.262 2.268 2.34 5.88.236 8.236l-8.48 8.492-8.478-8.492c-2.104-2.356-2.025-5.974.236-8.236 2.265-2.264 5.888-2.34 8.244-.228z"/></svg>
                        <span><?php echo $in_wishlist ? esc_html__('Remove from Wishlist', 'aqualuxe') : esc_html__('Add to Wishlist', 'aqualuxe'); ?></span>
                    </a>
                    <?php
                }
                
                // Compare button
                if ($module->get_option('compare', true)) {
                    // Get compare
                    $compare = $module->get_compare();
                    
                    // Check if product is in compare
                    $in_compare = in_array($product->get_id(), $compare);
                    ?>
                    <a href="#" class="compare-button <?php echo $in_compare ? 'in-compare' : ''; ?>" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M16 16v-4l5 5-5 5v-4H4v-2h12zM8 2v3.999L20 6v2H8v4L3 7l5-5z"/></svg>
                        <span><?php echo $in_compare ? esc_html__('Remove from Compare', 'aqualuxe') : esc_html__('Add to Compare', 'aqualuxe'); ?></span>
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>