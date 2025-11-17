<?php
/**
 * Product content template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>

<div <?php wc_product_class('product-item', $product); ?>>
    <div class="product-inner">
        <!-- Product image -->
        <div class="product-image">
            <a href="<?php the_permalink(); ?>" class="product-link">
                <?php
                /**
                 * Hook: woocommerce_before_shop_loop_item_title.
                 */
                do_action('woocommerce_before_shop_loop_item_title');
                ?>
                
                <!-- Quick view button -->
                <button class="quick-view-btn" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php esc_attr_e('Quick view', 'aqualuxe'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>

                <!-- Product badges -->
                <div class="product-badges">
                    <?php if ($product->is_on_sale()) : ?>
                        <span class="badge sale-badge"><?php esc_html_e('Sale', 'aqualuxe'); ?></span>
                    <?php endif; ?>
                    
                    <?php if ($product->is_featured()) : ?>
                        <span class="badge featured-badge"><?php esc_html_e('Featured', 'aqualuxe'); ?></span>
                    <?php endif; ?>
                    
                    <?php if (!$product->is_in_stock()) : ?>
                        <span class="badge out-of-stock-badge"><?php esc_html_e('Out of Stock', 'aqualuxe'); ?></span>
                    <?php endif; ?>
                </div>
            </a>

            <!-- Product actions overlay -->
            <div class="product-actions">
                <?php if ($product->is_in_stock()) : ?>
                    <!-- Add to cart -->
                    <?php
                    echo apply_filters(
                        'woocommerce_loop_add_to_cart_link',
                        sprintf(
                            '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                            esc_url($product->add_to_cart_url()),
                            esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
                            esc_attr(isset($args['class']) ? $args['class'] : 'button ajax-add-to-cart'),
                            isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
                            esc_html($product->add_to_cart_text())
                        ),
                        $product,
                        $args ?? []
                    );
                    ?>
                <?php endif; ?>

                <!-- Wishlist button -->
                <button class="wishlist-btn" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php esc_attr_e('Add to wishlist', 'aqualuxe'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>

                <!-- Compare button -->
                <button class="compare-btn" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php esc_attr_e('Add to comparison', 'aqualuxe'); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Product info -->
        <div class="product-info">
            <!-- Product category -->
            <?php
            $product_cats = wp_get_post_terms($product->get_id(), 'product_cat');
            if (!empty($product_cats) && !is_wp_error($product_cats)) :
                $first_cat = array_shift($product_cats);
                ?>
                <div class="product-category">
                    <a href="<?php echo esc_url(get_term_link($first_cat)); ?>" class="category-link">
                        <?php echo esc_html($first_cat->name); ?>
                    </a>
                </div>
            <?php endif; ?>

            <!-- Product title -->
            <h3 class="product-title">
                <a href="<?php the_permalink(); ?>">
                    <?php
                    /**
                     * Hook: woocommerce_shop_loop_item_title.
                     */
                    do_action('woocommerce_shop_loop_item_title');
                    ?>
                </a>
            </h3>

            <!-- Product excerpt -->
            <?php if ($product->get_short_description()) : ?>
                <div class="product-excerpt">
                    <?php echo wp_kses_post(wp_trim_words($product->get_short_description(), 15)); ?>
                </div>
            <?php endif; ?>

            <!-- Product rating -->
            <?php if (wc_review_ratings_enabled()) : ?>
                <div class="product-rating">
                    <?php
                    $rating_count = $product->get_rating_count();
                    $average = $product->get_average_rating();
                    
                    if ($rating_count > 0) :
                        echo wc_get_rating_html($average, $rating_count);
                        ?>
                        <span class="rating-count">(<?php echo esc_html($rating_count); ?>)</span>
                    <?php else : ?>
                        <span class="no-rating"><?php esc_html_e('No reviews yet', 'aqualuxe'); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Product price -->
            <div class="product-price">
                <?php
                /**
                 * Hook: woocommerce_after_shop_loop_item_title.
                 */
                do_action('woocommerce_after_shop_loop_item_title');
                ?>
            </div>

            <!-- Stock status -->
            <div class="product-stock">
                <?php if ($product->is_in_stock()) : ?>
                    <?php if ($product->managing_stock() && $product->get_stock_quantity() <= get_option('woocommerce_notify_low_stock_amount')) : ?>
                        <span class="stock low-stock">
                            <?php printf(esc_html__('Only %d left in stock', 'aqualuxe'), $product->get_stock_quantity()); ?>
                        </span>
                    <?php else : ?>
                        <span class="stock in-stock">
                            <?php esc_html_e('In stock', 'aqualuxe'); ?>
                        </span>
                    <?php endif; ?>
                <?php else : ?>
                    <span class="stock out-of-stock">
                        <?php esc_html_e('Out of stock', 'aqualuxe'); ?>
                    </span>
                <?php endif; ?>
            </div>

            <!-- Product meta -->
            <div class="product-meta">
                <?php if ($product->get_sku()) : ?>
                    <span class="sku">
                        <strong><?php esc_html_e('SKU:', 'aqualuxe'); ?></strong> <?php echo esc_html($product->get_sku()); ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
/**
 * Hook: woocommerce_after_shop_loop_item.
 */
do_action('woocommerce_after_shop_loop_item');
?>