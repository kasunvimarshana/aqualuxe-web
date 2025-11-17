<?php
/**
 * The template for displaying product quick view
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
	exit;
}

global $product;

// Ensure we have a product
if (empty($product)) {
	return;
}

?>

<div class="quick-view-product">
	<div class="quick-view-images">
		<?php
		// Display product gallery
		$attachment_ids = $product->get_gallery_image_ids();
		
		if ($attachment_ids) {
			echo '<div class="quick-view-gallery">';
			foreach ($attachment_ids as $attachment_id) {
				$image_url = wp_get_attachment_image_url($attachment_id, 'woocommerce_single');
				echo '<div class="quick-view-image">';
				echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($product->get_name()) . '">';
				echo '</div>';
			}
			echo '</div>';
		} else {
			// Fallback to featured image
			echo '<div class="quick-view-image">';
			echo get_the_post_thumbnail($product->get_id(), 'woocommerce_single');
			echo '</div>';
		}
		?>
	</div>
	
	<div class="quick-view-summary">
		<h2 class="product_title entry-title"><?php echo esc_html($product->get_name()); ?></h2>
		
		<?php if ($product->get_rating_count() > 0) : ?>
			<div class="quick-view-rating">
				<?php echo wc_get_rating_html($product->get_average_rating()); ?>
			</div>
		<?php endif; ?>
		
		<div class="price">
			<?php echo $product->get_price_html(); ?>
		</div>
		
		<div class="quick-view-description">
			<?php echo wp_kses_post($product->get_short_description()); ?>
		</div>
		
		<?php if ($product->is_type('simple')) : ?>
			<div class="quick-view-add-to-cart">
				<?php
				woocommerce_quantity_input(
					array(
						'min_value'   => '1',
						'max_value'   => $product->get_max_purchase_quantity(),
						'input_value' => '1',
					)
				);
				?>
				<button type="submit" class="single_add_to_cart_button button alt ajax_add_to_cart" 
						data-product_id="<?php echo esc_attr($product->get_id()); ?>" 
						data-quantity="1">
					<?php echo esc_html($product->single_add_to_cart_text()); ?>
				</button>
			</div>
		<?php endif; ?>
		
		<div class="quick-view-meta">
			<?php if ($product->get_sku()) : ?>
				<span class="sku_wrapper"><?php esc_html_e('SKU:', 'aqualuxe'); ?> <span class="sku"><?php echo esc_html($product->get_sku()); ?></span></span>
			<?php endif; ?>
			
			<?php if ($product->get_categories()) : ?>
				<span class="posted_in"><?php echo wc_get_product_category_list($product->get_id(), ', ', '<span class="posted_in">' . _n('Category:', 'Categories:', count($product->get_category_ids()), 'aqualuxe') . ' ', '</span>'); ?></span>
			<?php endif; ?>
		</div>
	</div>
</div>