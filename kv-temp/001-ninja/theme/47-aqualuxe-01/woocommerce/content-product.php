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

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

// Get shop layout
$shop_layout = aqualuxe_get_theme_option('aqualuxe_shop_layout', 'grid');
$product_class = 'product-item';

if ($shop_layout === 'list') {
	$product_class .= ' product-item-list';
} elseif ($shop_layout === 'compact') {
	$product_class .= ' product-item-compact';
}
?>
<li <?php wc_product_class($product_class, $product); ?>>
	<div class="product-inner bg-white rounded-lg shadow-sm overflow-hidden transition-shadow hover:shadow-md">
		<?php
		/**
		 * Hook: woocommerce_before_shop_loop_item.
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item' );
		?>

		<div class="product-thumbnail relative overflow-hidden">
			<?php
			/**
			 * Hook: woocommerce_before_shop_loop_item_title.
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
			?>

			<?php if (aqualuxe_get_theme_option('aqualuxe_quick_view', true) || aqualuxe_get_theme_option('aqualuxe_wishlist', true)) : ?>
				<div class="product-actions absolute bottom-0 left-0 right-0 bg-white bg-opacity-90 p-2 flex justify-center space-x-2 transform translate-y-full transition-transform duration-200">
					<?php if (aqualuxe_get_theme_option('aqualuxe_quick_view', true)) : ?>
						<button class="quick-view-button inline-flex items-center justify-center px-3 py-1 text-xs font-medium rounded bg-white text-gray-700 hover:text-primary-600 shadow-sm transition-colors duration-200" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php esc_attr_e('Quick view', 'aqualuxe'); ?>">
							<?php aqualuxe_svg_icon('search', array('class' => 'w-3 h-3 mr-1')); ?>
							<span><?php esc_html_e('Quick View', 'aqualuxe'); ?></span>
						</button>
					<?php endif; ?>

					<?php if (aqualuxe_get_theme_option('aqualuxe_wishlist', true)) : ?>
						<?php
						$in_wishlist = false;
						if (function_exists('aqualuxe_is_product_in_wishlist')) {
							$in_wishlist = aqualuxe_is_product_in_wishlist($product->get_id());
						}
						?>
						<button class="wishlist-button inline-flex items-center justify-center w-8 h-8 rounded-full bg-white text-gray-700 hover:text-primary-600 shadow-sm transition-colors duration-200 <?php echo $in_wishlist ? 'in-wishlist' : ''; ?>" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php echo $in_wishlist ? esc_attr__('Remove from wishlist', 'aqualuxe') : esc_attr__('Add to wishlist', 'aqualuxe'); ?>">
							<?php aqualuxe_svg_icon('heart', array('class' => 'w-4 h-4')); ?>
						</button>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php
			// Product badges
			$badges = array();

			// New badge (products published within the last 7 days)
			$days_as_new = apply_filters('aqualuxe_days_as_new_product', 7);
			$post_date = get_the_time('U');
			$current_date = current_time('timestamp');
			$is_new = ($current_date - $post_date) < ($days_as_new * 24 * 60 * 60);

			if ($is_new) {
				$badges[] = array(
					'text' => __('New', 'aqualuxe'),
					'class' => 'badge-new'
				);
			}

			// Featured badge
			if ($product->is_featured()) {
				$badges[] = array(
					'text' => __('Featured', 'aqualuxe'),
					'class' => 'badge-featured'
				);
			}

			// Out of stock badge
			if (!$product->is_in_stock()) {
				$badges[] = array(
					'text' => __('Out of Stock', 'aqualuxe'),
					'class' => 'badge-out-of-stock'
				);
			}

			// Display badges
			if (!empty($badges)) :
			?>
				<div class="product-badges absolute top-2 right-2 flex flex-col items-end space-y-1 z-10">
					<?php foreach ($badges as $badge) : ?>
						<span class="badge <?php echo esc_attr($badge['class']); ?> text-xs font-bold px-2 py-1 rounded-md">
							<?php echo esc_html($badge['text']); ?>
						</span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="product-details p-4">
			<?php
			// Product category
			$product_cats = get_the_terms($product->get_id(), 'product_cat');
			if ($product_cats && !is_wp_error($product_cats)) :
				$first_cat = reset($product_cats);
			?>
				<div class="product-category text-xs text-gray-500 mb-1">
					<a href="<?php echo esc_url(get_term_link($first_cat)); ?>"><?php echo esc_html($first_cat->name); ?></a>
				</div>
			<?php endif; ?>

			<div class="product-info">
				<?php
				/**
				 * Hook: woocommerce_shop_loop_item_title.
				 *
				 * @hooked woocommerce_template_loop_product_title - 10
				 */
				do_action( 'woocommerce_shop_loop_item_title' );

				/**
				 * Hook: woocommerce_after_shop_loop_item_title.
				 *
				 * @hooked woocommerce_template_loop_rating - 5
				 * @hooked woocommerce_template_loop_price - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item_title' );
				?>
			</div>

			<?php
			// Display vendor information if enabled
			if (aqualuxe_get_theme_option('aqualuxe_vendor_display', false) && function_exists('aqualuxe_get_product_vendor')) :
				$vendor = aqualuxe_get_product_vendor($product->get_id());
				if ($vendor) :
			?>
				<div class="product-vendor text-xs text-gray-500 flex items-center mt-2">
					<?php if (!empty($vendor['logo'])) : ?>
						<img src="<?php echo esc_url($vendor['logo']); ?>" alt="<?php echo esc_attr($vendor['name']); ?>" class="vendor-logo w-4 h-4 rounded-full mr-1">
					<?php endif; ?>
					<?php echo esc_html($vendor['name']); ?>
				</div>
			<?php
				endif;
			endif;
			?>

			<div class="product-meta mt-3 pt-3 border-t border-gray-100 flex justify-between items-center">
				<div class="rating-wrap flex items-center">
					<?php if ($product->get_rating_count()) : ?>
						<div class="star-rating" role="img" aria-label="<?php echo sprintf(__('Rated %s out of 5', 'aqualuxe'), $product->get_average_rating()); ?>">
							<span style="width: <?php echo esc_attr($product->get_average_rating() / 5 * 100); ?>%;">
								<?php echo sprintf(__('Rated %s out of 5', 'aqualuxe'), $product->get_average_rating()); ?>
							</span>
						</div>
						<span class="rating-count text-xs text-gray-500 ml-1">(<?php echo esc_html($product->get_rating_count()); ?>)</span>
					<?php endif; ?>
				</div>

				<?php
				/**
				 * Hook: woocommerce_after_shop_loop_item.
				 *
				 * @hooked woocommerce_template_loop_product_link_close - 5
				 * @hooked woocommerce_template_loop_add_to_cart - 10
				 */
				do_action( 'woocommerce_after_shop_loop_item' );
				?>
			</div>
		</div>
	</div>
</li>