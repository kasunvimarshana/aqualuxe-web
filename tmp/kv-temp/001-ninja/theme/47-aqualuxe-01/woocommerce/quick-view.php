<?php
/**
 * Quick View Template
 *
 * This template displays the product quick view modal.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Make sure we have a product
if (!$product) {
    return;
}
?>

<div id="quick-view-modal" class="quick-view-modal">
    <div class="modal-container">
        <div class="modal-content">
            <button class="modal-close" aria-label="<?php esc_attr_e('Close quick view', 'aqualuxe'); ?>">
                <?php aqualuxe_svg_icon('close', array('class' => 'w-6 h-6')); ?>
            </button>

            <div class="product-quick-view">
                <div class="product-quick-view-gallery">
                    <?php
                    // Display product images
                    $attachment_ids = $product->get_gallery_image_ids();
                    $featured_image_id = $product->get_image_id();
                    
                    if ($featured_image_id) {
                        $image_ids = array_merge(array($featured_image_id), $attachment_ids);
                    } else {
                        $image_ids = $attachment_ids;
                    }
                    
                    if (!empty($image_ids)) :
                    ?>
                        <div class="quick-view-gallery-main">
                            <?php
                            foreach ($image_ids as $image_id) :
                                $image_url = wp_get_attachment_image_url($image_id, 'medium');
                                $image_full_url = wp_get_attachment_image_url($image_id, 'full');
                                $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                            ?>
                                <div class="quick-view-gallery-item">
                                    <img src="<?php echo esc_url($image_url); ?>" data-full-img="<?php echo esc_url($image_full_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="quick-view-image">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <?php if (count($image_ids) > 1) : ?>
                            <div class="quick-view-gallery-thumbs">
                                <?php
                                foreach ($image_ids as $image_id) :
                                    $thumb_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                                    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                                ?>
                                    <div class="quick-view-thumb-item">
                                        <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="quick-view-thumb">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="quick-view-gallery-main">
                            <div class="quick-view-gallery-item">
                                <img src="<?php echo esc_url(wc_placeholder_img_src('medium')); ?>" alt="<?php esc_attr_e('Placeholder', 'aqualuxe'); ?>" class="quick-view-image">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="product-quick-view-summary">
                    <h2 class="product-title text-xl font-bold mb-2"><?php echo esc_html($product->get_name()); ?></h2>
                    
                    <?php if ($product->get_rating_count()) : ?>
                        <div class="product-rating flex items-center mb-3">
                            <div class="star-rating" role="img" aria-label="<?php echo sprintf(__('Rated %s out of 5', 'aqualuxe'), $product->get_average_rating()); ?>">
                                <span style="width: <?php echo esc_attr($product->get_average_rating() / 5 * 100); ?>%;">
                                    <?php echo sprintf(__('Rated %s out of 5', 'aqualuxe'), $product->get_average_rating()); ?>
                                </span>
                            </div>
                            <span class="rating-count text-sm text-gray-600 ml-2">(<?php echo esc_html($product->get_rating_count()); ?>)</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="product-price mb-3">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    
                    <div class="product-description mb-4">
                        <?php echo wp_kses_post($product->get_short_description()); ?>
                    </div>
                    
                    <?php
                    // Display product availability
                    if (function_exists('aqualuxe_product_availability')) :
                        $availability = aqualuxe_product_availability($product->get_id());
                        if ($availability) :
                    ?>
                        <div class="product-availability mb-4">
                            <div class="flex items-center">
                                <?php if ($availability['status'] === 'in_stock') : ?>
                                    <?php aqualuxe_svg_icon('check-circle', array('class' => 'w-5 h-5 mr-2 text-green-600')); ?>
                                    <span class="availability-text text-sm text-green-600"><?php echo esc_html($availability['text']); ?></span>
                                <?php elseif ($availability['status'] === 'low_stock') : ?>
                                    <?php aqualuxe_svg_icon('alert-circle', array('class' => 'w-5 h-5 mr-2 text-orange-600')); ?>
                                    <span class="availability-text text-sm text-orange-600"><?php echo esc_html($availability['text']); ?></span>
                                <?php else : ?>
                                    <?php aqualuxe_svg_icon('x-circle', array('class' => 'w-5 h-5 mr-2 text-red-600')); ?>
                                    <span class="availability-text text-sm text-red-600"><?php echo esc_html($availability['text']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php
                        endif;
                    endif;
                    ?>
                    
                    <div class="product-add-to-cart">
                        <?php woocommerce_template_single_add_to_cart(); ?>
                    </div>
                    
                    <div class="product-meta mt-4 pt-4 border-t border-gray-200">
                        <?php
                        // SKU
                        if ($product->get_sku()) :
                        ?>
                            <div class="product-sku text-sm mb-1">
                                <span class="meta-label font-medium"><?php esc_html_e('SKU:', 'aqualuxe'); ?></span>
                                <span class="meta-value"><?php echo esc_html($product->get_sku()); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        // Categories
                        $categories = get_the_terms($product->get_id(), 'product_cat');
                        if ($categories && !is_wp_error($categories)) :
                            $category_links = array();
                            foreach ($categories as $category) {
                                $category_links[] = '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
                            }
                        ?>
                            <div class="product-categories text-sm mb-1">
                                <span class="meta-label font-medium"><?php esc_html_e('Category:', 'aqualuxe'); ?></span>
                                <span class="meta-value"><?php echo implode(', ', $category_links); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-actions mt-4 flex items-center space-x-4">
                        <a href="<?php echo esc_url($product->get_permalink()); ?>" class="view-details-btn bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                            <?php esc_html_e('View Full Details', 'aqualuxe'); ?>
                        </a>
                        
                        <?php if (aqualuxe_get_theme_option('aqualuxe_wishlist', true)) : ?>
                            <?php
                            $in_wishlist = false;
                            if (function_exists('aqualuxe_is_product_in_wishlist')) {
                                $in_wishlist = aqualuxe_is_product_in_wishlist($product->get_id());
                            }
                            ?>
                            <button class="wishlist-button inline-flex items-center justify-center w-10 h-10 rounded-full bg-white text-gray-700 hover:text-primary-600 shadow-sm transition-colors duration-200 <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php echo $in_wishlist ? esc_attr__('Remove from wishlist', 'aqualuxe') : esc_attr__('Add to wishlist', 'aqualuxe'); ?>">
                                <?php aqualuxe_svg_icon('heart', array('class' => 'w-5 h-5')); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>