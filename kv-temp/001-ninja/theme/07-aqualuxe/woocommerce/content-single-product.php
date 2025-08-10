<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'bg-white dark:bg-dark-800 rounded-lg shadow-medium overflow-hidden', $product ); ?>>
	<div class="product-main grid grid-cols-1 lg:grid-cols-2 gap-8 p-6">
		<div class="product-gallery-wrapper">
			<?php
			/**
			 * Hook: woocommerce_before_single_product_summary.
			 *
			 * @hooked woocommerce_show_product_sale_flash - 10
			 * @hooked woocommerce_show_product_images - 20
			 */
			do_action( 'woocommerce_before_single_product_summary' );
			?>
		</div>

		<div class="product-summary-wrapper">
			<div class="summary entry-summary">
				<?php
				/**
				 * Hook: woocommerce_single_product_summary.
				 *
				 * @hooked woocommerce_template_single_title - 5
				 * @hooked woocommerce_template_single_rating - 10
				 * @hooked woocommerce_template_single_price - 10
				 * @hooked woocommerce_template_single_excerpt - 20
				 * @hooked woocommerce_template_single_add_to_cart - 30
				 * @hooked woocommerce_template_single_meta - 40
				 * @hooked woocommerce_template_single_sharing - 50
				 * @hooked WC_Structured_Data::generate_product_data() - 60
				 */
				do_action( 'woocommerce_single_product_summary' );
				?>
			</div>
		</div>
	</div>

	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>

<script>
	// Custom script for product gallery
	document.addEventListener('DOMContentLoaded', function() {
		// Initialize product gallery features
		if (typeof jQuery !== 'undefined') {
			(function($) {
				// Add zoom effect to product images
				$('.woocommerce-product-gallery__image a').zoom({
					url: function() {
						return $(this).find('img').attr('data-large_image');
					},
					touch: false
				});
				
				// Add quick view functionality
				$('.quick-view-button').on('click', function(e) {
					e.preventDefault();
					var productId = $(this).data('product-id');
					
					// Show loading overlay
					$('body').append('<div class="quick-view-overlay fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"><div class="quick-view-loader text-white"><svg class="animate-spin h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div></div>');
					
					// AJAX call to get product data
					$.ajax({
						url: wc_add_to_cart_params.ajax_url,
						type: 'POST',
						data: {
							action: 'aqualuxe_quick_view',
							product_id: productId,
							security: aqualuxe_quick_view.nonce
						},
						success: function(response) {
							// Remove loading overlay
							$('.quick-view-overlay').remove();
							
							// Show quick view modal
							$('body').append(response);
							
							// Initialize product gallery in quick view
							$('.quick-view-modal .woocommerce-product-gallery').each(function() {
								$(this).wc_product_gallery();
							});
							
							// Close modal on click outside
							$('.quick-view-modal-overlay').on('click', function(e) {
								if ($(e.target).hasClass('quick-view-modal-overlay')) {
									$('.quick-view-modal-overlay').remove();
								}
							});
							
							// Close modal on close button click
							$('.quick-view-close').on('click', function() {
								$('.quick-view-modal-overlay').remove();
							});
						}
					});
				});
				
				// Add wishlist functionality
				$('.wishlist-button').on('click', function(e) {
					e.preventDefault();
					var productId = $(this).data('product-id');
					
					// Toggle wishlist button state
					$(this).toggleClass('active');
					
					if ($(this).hasClass('active')) {
						$(this).find('svg').attr('fill', 'currentColor');
						$(this).attr('title', aqualuxe_wishlist.remove_text);
					} else {
						$(this).find('svg').attr('fill', 'none');
						$(this).attr('title', aqualuxe_wishlist.add_text);
					}
					
					// AJAX call to update wishlist
					$.ajax({
						url: wc_add_to_cart_params.ajax_url,
						type: 'POST',
						data: {
							action: 'aqualuxe_update_wishlist',
							product_id: productId,
							security: aqualuxe_wishlist.nonce
						},
						success: function(response) {
							// Update wishlist count
							$('.wishlist-count').text(response.count);
						}
					});
				});
			})(jQuery);
		}
	});
</script>

<style>
	/* Custom styles for single product */
	.woocommerce div.product div.images .woocommerce-product-gallery__wrapper {
		border-radius: 0.5rem;
		overflow: hidden;
	}
	
	.woocommerce div.product div.images .flex-control-thumbs {
		margin-top: 1rem;
		display: flex;
		flex-wrap: wrap;
		gap: 0.5rem;
	}
	
	.woocommerce div.product div.images .flex-control-thumbs li {
		width: calc(25% - 0.375rem);
		margin: 0;
	}
	
	.woocommerce div.product div.images .flex-control-thumbs li img {
		border-radius: 0.25rem;
		border: 1px solid transparent;
		transition: all 0.3s ease;
	}
	
	.woocommerce div.product div.images .flex-control-thumbs li img.flex-active,
	.woocommerce div.product div.images .flex-control-thumbs li img:hover {
		border-color: var(--color-primary, #0ea5e9);
	}
	
	.woocommerce div.product .product_title {
		font-size: 1.875rem;
		font-weight: 700;
		font-family: var(--font-serif, serif);
		color: #1f2937;
		margin-bottom: 0.5rem;
	}
	
	.dark .woocommerce div.product .product_title {
		color: #f3f4f6;
	}
	
	.woocommerce div.product p.price,
	.woocommerce div.product span.price {
		color: var(--color-primary, #0ea5e9);
		font-size: 1.5rem;
		font-weight: 700;
		margin: 1rem 0;
	}
	
	.dark .woocommerce div.product p.price,
	.dark .woocommerce div.product span.price {
		color: var(--color-primary-light, #38bdf8);
	}
	
	.woocommerce div.product .woocommerce-product-rating {
		margin-bottom: 1rem;
	}
	
	.woocommerce div.product .woocommerce-product-rating .star-rating {
		color: var(--color-accent, #eab308);
	}
	
	.woocommerce div.product div.summary {
		margin-bottom: 2rem;
	}
	
	.woocommerce div.product .woocommerce-product-details__short-description {
		margin-bottom: 1.5rem;
		color: #4b5563;
	}
	
	.dark .woocommerce div.product .woocommerce-product-details__short-description {
		color: #9ca3af;
	}
	
	.woocommerce div.product form.cart {
		margin-bottom: 1.5rem;
		padding-bottom: 1.5rem;
		border-bottom: 1px solid #e5e7eb;
	}
	
	.dark .woocommerce div.product form.cart {
		border-color: #374151;
	}
	
	.woocommerce div.product form.cart div.quantity {
		margin-right: 1rem;
	}
	
	.woocommerce div.product form.cart .button {
		padding: 0.75rem 1.5rem;
		font-weight: 500;
	}
	
	.woocommerce div.product .product_meta {
		font-size: 0.875rem;
		color: #6b7280;
	}
	
	.dark .woocommerce div.product .product_meta {
		color: #9ca3af;
	}
	
	.woocommerce div.product .product_meta a {
		color: var(--color-primary, #0ea5e9);
	}
	
	.woocommerce div.product .product_meta a:hover {
		color: var(--color-primary-dark, #0284c7);
	}
	
	.dark .woocommerce div.product .product_meta a {
		color: var(--color-primary-light, #38bdf8);
	}
	
	.dark .woocommerce div.product .product_meta a:hover {
		color: var(--color-primary, #0ea5e9);
	}
	
	/* Tabs styling */
	.woocommerce div.product .woocommerce-tabs {
		margin-top: 2rem;
		padding: 0 1.5rem 1.5rem;
	}
	
	.woocommerce div.product .woocommerce-tabs ul.tabs {
		padding: 0;
		margin: 0 0 1.5rem;
		border-bottom: 1px solid #e5e7eb;
	}
	
	.dark .woocommerce div.product .woocommerce-tabs ul.tabs {
		border-color: #374151;
	}
	
	.woocommerce div.product .woocommerce-tabs ul.tabs::before {
		border: none;
	}
	
	.woocommerce div.product .woocommerce-tabs ul.tabs li {
		background: transparent;
		border: none;
		border-radius: 0;
		margin: 0 1.5rem 0 0;
		padding: 0 0 0.75rem;
	}
	
	.woocommerce div.product .woocommerce-tabs ul.tabs li::before,
	.woocommerce div.product .woocommerce-tabs ul.tabs li::after {
		display: none;
	}
	
	.woocommerce div.product .woocommerce-tabs ul.tabs li a {
		font-weight: 500;
		color: #6b7280;
		padding: 0;
		transition: color 0.3s;
	}
	
	.dark .woocommerce div.product .woocommerce-tabs ul.tabs li a {
		color: #9ca3af;
	}
	
	.woocommerce div.product .woocommerce-tabs ul.tabs li a:hover {
		color: #1f2937;
	}
	
	.dark .woocommerce div.product .woocommerce-tabs ul.tabs li a:hover {
		color: #f3f4f6;
	}
	
	.woocommerce div.product .woocommerce-tabs ul.tabs li.active {
		background: transparent;
		border-bottom: 2px solid var(--color-primary, #0ea5e9);
		margin-bottom: -1px;
	}
	
	.woocommerce div.product .woocommerce-tabs ul.tabs li.active a {
		color: var(--color-primary, #0ea5e9);
	}
	
	.dark .woocommerce div.product .woocommerce-tabs ul.tabs li.active a {
		color: var(--color-primary-light, #38bdf8);
	}
	
	.woocommerce div.product .woocommerce-tabs .panel {
		margin: 0;
		padding: 0;
	}
	
	.woocommerce div.product .woocommerce-tabs .panel h2:first-child {
		font-size: 1.25rem;
		font-weight: 600;
		margin-bottom: 1rem;
	}
	
	/* Related products styling */
	.related.products,
	.upsells.products {
		margin-top: 3rem;
		padding-top: 2rem;
		border-top: 1px solid #e5e7eb;
	}
	
	.dark .related.products,
	.dark .upsells.products {
		border-color: #374151;
	}
	
	.related.products > h2,
	.upsells.products > h2 {
		font-size: 1.5rem;
		font-weight: 700;
		font-family: var(--font-serif, serif);
		margin-bottom: 1.5rem;
		color: #1f2937;
	}
	
	.dark .related.products > h2,
	.dark .upsells.products > h2 {
		color: #f3f4f6;
	}
	
	/* Quantity input styling */
	.woocommerce .quantity .qty {
		width: 5rem;
		padding: 0.5rem;
		border: 1px solid #d1d5db;
		border-radius: 0.375rem;
	}
	
	.dark .woocommerce .quantity .qty {
		background-color: #1f2937;
		border-color: #374151;
		color: #e5e7eb;
	}
	
	/* Product features styling */
	.product-features {
		margin-top: 2rem;
		padding-top: 1.5rem;
		border-top: 1px solid #e5e7eb;
	}
	
	.dark .product-features {
		border-color: #374151;
	}
	
	/* Product share styling */
	.product-share {
		margin-top: 1.5rem;
		padding-top: 1.5rem;
		border-top: 1px solid #e5e7eb;
	}
	
	.dark .product-share {
		border-color: #374151;
	}
	
	/* Wishlist button styling */
	.wishlist-button.active svg {
		fill: currentColor;
		color: #ef4444;
	}
	
	.dark .wishlist-button.active svg {
		color: #f87171;
	}
</style>