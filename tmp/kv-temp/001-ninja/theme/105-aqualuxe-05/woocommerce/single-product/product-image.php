<?php
/**
 * Single Product Summary
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if (!function_exists('wc_get_gallery_image_html')) {
    return;
}

global $product;

$columns           = apply_filters('woocommerce_product_thumbnails_columns', 4);
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
    'woocommerce_single_product_image_gallery_classes',
    array(
        'woocommerce-product-gallery',
        'woocommerce-product-gallery--' . ($post_thumbnail_id ? 'with-images' : 'without-images'),
        'woocommerce-product-gallery--columns-' . absint($columns),
        'images',
    )
);
?>
<div class="<?php echo esc_attr(implode(' ', array_map('sanitize_html_class', $wrapper_classes))); ?>" data-columns="<?php echo esc_attr($columns); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
    
    <!-- Product Image Badges -->
    <div class="product-badges absolute top-4 left-4 z-10 space-y-2">
        <?php
        // Sale badge
        if ($product->is_on_sale()) {
            $percentage = '';
            if ($product->get_regular_price() && $product->get_sale_price()) {
                $percentage = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
                $percentage = '-' . $percentage . '%';
            }
            echo '<span class="sale-badge bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">';
            echo $percentage ? esc_html($percentage) : esc_html__('Sale', 'aqualuxe');
            echo '</span>';
        }

        // New badge
        $created = $product->get_date_created();
        if ($created && $created->getTimestamp() > strtotime('-30 days')) {
            echo '<span class="new-badge bg-green-600 text-white px-3 py-1 rounded-full text-sm font-semibold">';
            echo esc_html__('New', 'aqualuxe');
            echo '</span>';
        }

        // Featured badge
        if ($product->is_featured()) {
            echo '<span class="featured-badge bg-amber-600 text-white px-3 py-1 rounded-full text-sm font-semibold">';
            echo esc_html__('Featured', 'aqualuxe');
            echo '</span>';
        }

        // Stock status badges
        if (!$product->is_in_stock()) {
            echo '<span class="stock-badge bg-gray-600 text-white px-3 py-1 rounded-full text-sm font-semibold">';
            echo esc_html__('Out of Stock', 'aqualuxe');
            echo '</span>';
        } elseif ($product->get_stock_quantity() && $product->get_stock_quantity() <= 5) {
            echo '<span class="low-stock-badge bg-orange-600 text-white px-3 py-1 rounded-full text-sm font-semibold">';
            echo esc_html__('Low Stock', 'aqualuxe');
            echo '</span>';
        }
        ?>
    </div>

    <!-- Product Actions Overlay -->
    <div class="product-actions-overlay absolute top-4 right-4 z-10 space-y-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
        
        <!-- Wishlist Button -->
        <?php if (is_user_logged_in()) : ?>
            <?php
            $wishlist = get_user_meta(get_current_user_id(), 'aqualuxe_wishlist', true);
            $wishlist = is_array($wishlist) ? $wishlist : array();
            $in_wishlist = in_array($product->get_id(), $wishlist);
            ?>
            <button type="button" 
                class="wishlist-btn bg-white dark:bg-gray-800 text-gray-800 dark:text-white p-3 rounded-full shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors block <?php echo $in_wishlist ? 'text-red-600' : ''; ?>"
                data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                data-in-wishlist="<?php echo esc_attr($in_wishlist ? 'true' : 'false'); ?>"
                title="<?php esc_attr_e($in_wishlist ? 'Remove from wishlist' : 'Add to wishlist', 'aqualuxe'); ?>">
                <svg class="w-5 h-5" fill="<?php echo $in_wishlist ? 'currentColor' : 'none'; ?>" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        <?php endif; ?>

        <!-- Share Button -->
        <button type="button" 
            class="share-btn bg-white dark:bg-gray-800 text-gray-800 dark:text-white p-3 rounded-full shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors block"
            data-product-url="<?php echo esc_url(get_permalink()); ?>"
            title="<?php esc_attr_e('Share this product', 'aqualuxe'); ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
            </svg>
        </button>

        <!-- Zoom Button -->
        <button type="button" 
            class="zoom-btn bg-white dark:bg-gray-800 text-gray-800 dark:text-white p-3 rounded-full shadow-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors block"
            title="<?php esc_attr_e('Zoom image', 'aqualuxe'); ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
            </svg>
        </button>
    </div>

    <figure class="woocommerce-product-gallery__wrapper group">
        <?php
        if ($post_thumbnail_id) {
            $html = wc_get_gallery_image_html($post_thumbnail_id, true);
        } else {
            $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
            $html .= sprintf('<img src="%s" alt="%s" class="wp-post-image" />', esc_url(wc_placeholder_img_src('woocommerce_single')), esc_html__('Awaiting product image', 'aqualuxe'));
            $html .= '</div>';
        }

        echo apply_filters('woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
        
        // Gallery thumbnails
        do_action('woocommerce_product_thumbnails');
        ?>
    </figure>
    
    <!-- Product Info Overlay for mobile -->
    <div class="product-info-overlay lg:hidden absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
        <h3 class="text-white font-semibold text-lg"><?php echo esc_html($product->get_name()); ?></h3>
        <p class="text-white opacity-90"><?php echo wp_kses_post($product->get_price_html()); ?></p>
    </div>
</div>