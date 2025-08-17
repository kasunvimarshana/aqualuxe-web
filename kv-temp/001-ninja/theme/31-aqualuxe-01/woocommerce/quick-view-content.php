<?php
/**
 * Quick View Content Template
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;
?>
<div class="quick-view-product">
	<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
		<div class="quick-view-images">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="quick-view-main-image mb-2">
					<?php echo get_the_post_thumbnail( $post->ID, 'woocommerce_single', array( 'class' => 'w-full h-auto rounded-lg' ) ); ?>
				</div>
				
				<?php
				$attachment_ids = $product->get_gallery_image_ids();
				if ( $attachment_ids ) : ?>
					<div class="quick-view-thumbs grid grid-cols-4 gap-2">
						<div class="quick-view-thumb">
							<?php echo get_the_post_thumbnail( $post->ID, 'thumbnail', array( 'class' => 'w-full h-auto rounded-md cursor-pointer' ) ); ?>
						</div>
						<?php
						foreach ( $attachment_ids as $attachment_id ) {
							echo wp_get_attachment_image( $attachment_id, 'thumbnail', false, array( 'class' => 'w-full h-auto rounded-md cursor-pointer' ) );
						}
						?>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<div class="quick-view-main-image mb-2">
					<?php echo wc_placeholder_img( 'woocommerce_single' ); ?>
				</div>
			<?php endif; ?>
		</div>
		
		<div class="quick-view-summary">
			<h2 class="product-title text-2xl font-serif font-medium mb-2"><?php the_title(); ?></h2>
			
			<div class="product-price text-xl font-bold text-primary-600 dark:text-primary-400 mb-4">
				<?php echo $product->get_price_html(); ?>
			</div>
			
			<?php if ( $product->get_rating_count() > 0 ) : ?>
				<div class="product-rating mb-4">
					<?php woocommerce_template_loop_rating(); ?>
					<a href="<?php echo esc_url( get_permalink() ); ?>#reviews" class="text-sm text-dark-500 dark:text-dark-300 ml-2">
						(<?php printf( _n( '%s review', '%s reviews', $product->get_review_count(), 'aqualuxe' ), esc_html( $product->get_review_count() ) ); ?>)
					</a>
				</div>
			<?php endif; ?>
			
			<div class="product-short-description mb-6">
				<?php woocommerce_template_single_excerpt(); ?>
			</div>
			
			<?php if ( $product->is_in_stock() ) : ?>
				<div class="product-add-to-cart">
					<?php woocommerce_template_single_add_to_cart(); ?>
				</div>
			<?php else : ?>
				<div class="product-out-of-stock bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-300 p-3 rounded-md mb-4">
					<?php esc_html_e( 'This product is currently out of stock and unavailable.', 'aqualuxe' ); ?>
				</div>
			<?php endif; ?>
			
			<div class="product-meta mt-6 pt-6 border-t border-gray-200 dark:border-dark-600">
				<?php if ( wc_product_sku_enabled() && $product->get_sku() ) : ?>
					<div class="product-sku mb-2">
						<span class="font-medium"><?php esc_html_e( 'SKU:', 'aqualuxe' ); ?></span> 
						<span class="text-dark-600 dark:text-dark-300"><?php echo esc_html( $product->get_sku() ); ?></span>
					</div>
				<?php endif; ?>
				
				<?php if ( $product->get_category_ids() ) : ?>
					<div class="product-categories mb-2">
						<span class="font-medium"><?php esc_html_e( 'Categories:', 'aqualuxe' ); ?></span> 
						<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="text-dark-600 dark:text-dark-300">', '</span>' ); ?>
					</div>
				<?php endif; ?>
				
				<?php if ( $product->get_tag_ids() ) : ?>
					<div class="product-tags">
						<span class="font-medium"><?php esc_html_e( 'Tags:', 'aqualuxe' ); ?></span> 
						<?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="text-dark-600 dark:text-dark-300">', '</span>' ); ?>
					</div>
				<?php endif; ?>
			</div>
			
			<div class="product-actions mt-6">
				<a href="<?php the_permalink(); ?>" class="btn btn-outline w-full text-center">
					<?php esc_html_e( 'View Full Details', 'aqualuxe' ); ?>
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
					</svg>
				</a>
			</div>
		</div>
	</div>
</div>