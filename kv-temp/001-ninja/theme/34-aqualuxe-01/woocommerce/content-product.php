<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}

// Get product data
$product_id = $product->get_id();
$thumbnail_id = $product->get_image_id();
$gallery_images = $product->get_gallery_image_ids();
$first_gallery_image = !empty($gallery_images) ? wp_get_attachment_image_src($gallery_images[0], 'woocommerce_thumbnail') : '';
$average_rating = $product->get_average_rating();
$review_count = $product->get_review_count();
$price_html = $product->get_price_html();
$is_on_sale = $product->is_on_sale();
$is_featured = $product->is_featured();
$is_in_stock = $product->is_in_stock();
?>

<li <?php wc_product_class('product-card', $product); ?>>
    <div class="product-card__inner">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action('woocommerce_before_shop_loop_item');
        ?>

        <div class="product-card__image">
            <a href="<?php the_permalink(); ?>" class="product-card__image-link">
                <?php
                /**
                 * Hook: woocommerce_before_shop_loop_item_title.
                 *
                 * @hooked woocommerce_show_product_loop_sale_flash - 10
                 * @hooked woocommerce_template_loop_product_thumbnail - 10
                 */
                do_action('woocommerce_before_shop_loop_item_title');

                // Add secondary image for hover effect
                if ($first_gallery_image) {
                    echo '<img class="product-card__image-hover" src="' . esc_url($first_gallery_image[0]) . '" alt="' . esc_attr(get_the_title()) . '" />';
                }
                ?>
            </a>

            <?php if ($is_on_sale) : ?>
                <span class="product-card__badge product-card__badge--sale"><?php esc_html_e('Sale', 'aqualuxe'); ?></span>
            <?php endif; ?>

            <?php if ($is_featured) : ?>
                <span class="product-card__badge product-card__badge--featured"><?php esc_html_e('Featured', 'aqualuxe'); ?></span>
            <?php endif; ?>

            <?php if (!$is_in_stock) : ?>
                <span class="product-card__badge product-card__badge--out-of-stock"><?php esc_html_e('Out of Stock', 'aqualuxe'); ?></span>
            <?php endif; ?>

            <div class="product-card__actions">
                <?php
                /**
                 * Hook: aqualuxe_product_card_actions.
                 *
                 * @hooked aqualuxe_quick_view_button - 10
                 * @hooked aqualuxe_wishlist_button - 20
                 * @hooked aqualuxe_compare_button - 30
                 */
                do_action('aqualuxe_product_card_actions', $product_id);
                ?>
            </div>
        </div>

        <div class="product-card__content">
            <?php if ($average_rating > 0) : ?>
                <div class="product-card__rating">
                    <?php echo wc_get_rating_html($average_rating); ?>
                    <?php if ($review_count > 0) : ?>
                        <span class="product-card__rating-count">(<?php echo esc_html($review_count); ?>)</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <h2 class="product-card__title">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>

            <?php if ($price_html) : ?>
                <div class="product-card__price">
                    <?php echo $price_html; ?>
                </div>
            <?php endif; ?>

            <div class="product-card__description">
                <?php echo wp_trim_words($product->get_short_description(), 10); ?>
            </div>

            <div class="product-card__add-to-cart">
                <?php
                /**
                 * Hook: woocommerce_after_shop_loop_item.
                 *
                 * @hooked woocommerce_template_loop_add_to_cart - 10
                 */
                do_action('woocommerce_after_shop_loop_item');
                ?>
            </div>
        </div>
    </div>
</li>