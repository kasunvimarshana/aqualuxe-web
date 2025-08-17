<?php
/**
 * Wishlist Share Template
 *
 * This template is used to display the shared wishlist content.
 *
 * @package AquaLuxe
 * @since 1.1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get wishlist.
$wishlist = isset( $wishlist ) ? $wishlist : array();

// Get user ID.
$user_id = isset( $user_id ) ? $user_id : 0;

// Get user.
$user = get_user_by( 'id', $user_id );

// Get user name.
$user_name = $user ? $user->display_name : '';

// Get wishlist style.
$wishlist_style = get_theme_mod( 'aqualuxe_wishlist_share_style', 'grid' );

// Get wishlist columns.
$wishlist_columns = get_theme_mod( 'aqualuxe_wishlist_share_columns', 4 );

// Get page title.
$page_title = sprintf( __( '%s\'s Wishlist', 'aqualuxe' ), $user_name );

// Set page title.
add_filter( 'pre_get_document_title', function() use ( $page_title ) {
	return $page_title;
} );

get_header();
?>

<div class="wishlist-share-content">
	<div class="wishlist-share-header">
		<h1 class="page-title"><?php echo esc_html( $page_title ); ?></h1>
	</div>
	
	<?php if ( empty( $wishlist ) ) : ?>
		<div class="wishlist-share-empty">
			<p><?php esc_html_e( 'This wishlist is empty.', 'aqualuxe' ); ?></p>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button"><?php esc_html_e( 'Browse products', 'aqualuxe' ); ?></a>
		</div>
	<?php else : ?>
		<div class="wishlist-share-products layout-<?php echo esc_attr( $wishlist_style ); ?> columns-<?php echo esc_attr( $wishlist_columns ); ?>">
			<?php
			// Get products.
			$args = array(
				'post_type'      => 'product',
				'post__in'       => $wishlist,
				'posts_per_page' => -1,
				'orderby'        => 'post__in',
				'post_status'    => 'publish',
			);
			
			$products = new WP_Query( $args );
			
			if ( $products->have_posts() ) {
				while ( $products->have_posts() ) {
					$products->the_post();
					global $product;
					
					// Output product.
					?>
					<div class="wishlist-share-product">
						<div class="wishlist-share-product-image">
							<a href="<?php echo esc_url( get_permalink() ); ?>">
								<?php echo $product->get_image( 'woocommerce_thumbnail' ); ?>
							</a>
						</div>
						
						<div class="wishlist-share-product-details">
							<h3 class="wishlist-share-product-title">
								<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
							</h3>
							
							<div class="wishlist-share-product-price">
								<?php echo $product->get_price_html(); ?>
							</div>
							
							<?php if ( get_theme_mod( 'aqualuxe_wishlist_share_show_rating', true ) ) : ?>
								<div class="wishlist-share-product-rating">
									<?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
								</div>
							<?php endif; ?>
							
							<?php if ( get_theme_mod( 'aqualuxe_wishlist_share_show_stock', true ) ) : ?>
								<div class="wishlist-share-product-stock">
									<?php echo wc_get_stock_html( $product ); ?>
								</div>
							<?php endif; ?>
							
							<div class="wishlist-share-product-buttons">
								<div class="wishlist-share-product-cart">
									<?php woocommerce_template_loop_add_to_cart(); ?>
								</div>
								
								<?php if ( is_user_logged_in() ) : ?>
									<div class="wishlist-share-product-add-to-wishlist">
										<?php
										// Check if product is in user's wishlist.
										$user_wishlist = get_user_meta( get_current_user_id(), 'aqualuxe_wishlist', true );
										
										if ( ! $user_wishlist ) {
											$user_wishlist = array();
										}
										
										$in_wishlist = in_array( $product->get_id(), $user_wishlist, true );
										?>
										
										<a href="#" class="button wishlist-add<?php echo $in_wishlist ? ' in-wishlist' : ''; ?>" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
											<i class="fa<?php echo $in_wishlist ? 's' : 'r'; ?> fa-heart"></i>
											<span>
												<?php
												if ( $in_wishlist ) {
													esc_html_e( 'Added to wishlist', 'aqualuxe' );
												} else {
													esc_html_e( 'Add to wishlist', 'aqualuxe' );
												}
												?>
											</span>
										</a>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php
				}
				
				wp_reset_postdata();
			}
			?>
		</div>
		
		<div class="wishlist-share-actions">
			<?php if ( is_user_logged_in() ) : ?>
				<a href="#" class="button wishlist-share-add-all-to-wishlist">
					<i class="fas fa-heart"></i>
					<span><?php esc_html_e( 'Add all to my wishlist', 'aqualuxe' ); ?></span>
				</a>
			<?php endif; ?>
			
			<a href="#" class="button wishlist-share-add-all-to-cart">
				<i class="fas fa-shopping-cart"></i>
				<span><?php esc_html_e( 'Add all to cart', 'aqualuxe' ); ?></span>
			</a>
		</div>
	<?php endif; ?>
</div>

<style>
	.wishlist-share-content {
		max-width: 1200px;
		margin: 0 auto;
		padding: 2em 0;
	}
	
	.wishlist-share-header {
		margin-bottom: 2em;
		text-align: center;
	}
	
	.wishlist-share-empty {
		text-align: center;
		padding: 3em;
		background-color: #f9fafb;
		border-radius: 0.5em;
	}
	
	.wishlist-share-empty p {
		margin-bottom: 1em;
		font-size: 1.2em;
	}
	
	.wishlist-share-products {
		display: grid;
		grid-gap: 2em;
	}
	
	.wishlist-share-products.layout-grid.columns-2 {
		grid-template-columns: repeat(2, 1fr);
	}
	
	.wishlist-share-products.layout-grid.columns-3 {
		grid-template-columns: repeat(3, 1fr);
	}
	
	.wishlist-share-products.layout-grid.columns-4 {
		grid-template-columns: repeat(4, 1fr);
	}
	
	.wishlist-share-product {
		border: 1px solid #e5e7eb;
		border-radius: 0.5em;
		overflow: hidden;
		transition: all 0.3s ease;
	}
	
	.wishlist-share-product:hover {
		box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
	}
	
	.wishlist-share-product-image {
		position: relative;
	}
	
	.wishlist-share-product-image img {
		width: 100%;
		height: auto;
		display: block;
	}
	
	.wishlist-share-product-details {
		padding: 1.5em;
	}
	
	.wishlist-share-product-title {
		font-size: 1.2em;
		margin-bottom: 0.5em;
	}
	
	.wishlist-share-product-title a {
		text-decoration: none;
		color: inherit;
	}
	
	.wishlist-share-product-price {
		font-size: 1.1em;
		font-weight: 600;
		margin-bottom: 0.5em;
	}
	
	.wishlist-share-product-rating {
		margin-bottom: 0.5em;
	}
	
	.wishlist-share-product-stock {
		margin-bottom: 1em;
	}
	
	.wishlist-share-product-buttons {
		display: flex;
		flex-wrap: wrap;
		gap: 0.5em;
	}
	
	.wishlist-share-actions {
		margin-top: 2em;
		display: flex;
		justify-content: center;
		gap: 1em;
	}
	
	@media (max-width: 992px) {
		.wishlist-share-products.layout-grid.columns-4 {
			grid-template-columns: repeat(3, 1fr);
		}
	}
	
	@media (max-width: 768px) {
		.wishlist-share-products.layout-grid.columns-3,
		.wishlist-share-products.layout-grid.columns-4 {
			grid-template-columns: repeat(2, 1fr);
		}
	}
	
	@media (max-width: 576px) {
		.wishlist-share-products.layout-grid.columns-2,
		.wishlist-share-products.layout-grid.columns-3,
		.wishlist-share-products.layout-grid.columns-4 {
			grid-template-columns: 1fr;
		}
		
		.wishlist-share-actions {
			flex-direction: column;
		}
	}
</style>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		// Add to wishlist.
		$('.wishlist-add').on('click', function(e) {
			e.preventDefault();
			
			var $button = $(this);
			var product_id = $button.data('product-id');
			
			$.ajax({
				type: 'POST',
				url: wc_add_to_cart_params.ajax_url,
				data: {
					action: 'aqualuxe_wishlist_add',
					product_id: product_id,
					nonce: aqualuxeWooCommerce.nonce
				},
				beforeSend: function() {
					$button.addClass('loading');
				},
				success: function(response) {
					if (response.success) {
						if (response.data.in_wishlist) {
							$button.addClass('in-wishlist');
							$button.find('i').removeClass('far').addClass('fas');
							$button.find('span').text(aqualuxeWooCommerce.wishlistAddedText);
						} else {
							$button.removeClass('in-wishlist');
							$button.find('i').removeClass('fas').addClass('far');
							$button.find('span').text(aqualuxeWooCommerce.wishlistAddText);
						}
					}
				},
				complete: function() {
					$button.removeClass('loading');
				}
			});
		});
		
		// Add all to wishlist.
		$('.wishlist-share-add-all-to-wishlist').on('click', function(e) {
			e.preventDefault();
			
			var $button = $(this);
			var products = [];
			
			// Get all products.
			$('.wishlist-share-product').each(function() {
				var product_id = $(this).find('.wishlist-add').data('product-id');
				products.push(product_id);
			});
			
			// Add products to wishlist.
			$.ajax({
				type: 'POST',
				url: wc_add_to_cart_params.ajax_url,
				data: {
					action: 'aqualuxe_wishlist_add_all',
					products: products,
					nonce: aqualuxeWooCommerce.nonce
				},
				beforeSend: function() {
					$button.addClass('loading');
				},
				success: function(response) {
					if (response.success) {
						// Update wishlist buttons.
						$('.wishlist-add').each(function() {
							$(this).addClass('in-wishlist');
							$(this).find('i').removeClass('far').addClass('fas');
							$(this).find('span').text(aqualuxeWooCommerce.wishlistAddedText);
						});
						
						// Show notice.
						$('body').append('<div class="woocommerce-message" role="alert">' + response.data.message + '</div>');
						
						// Remove notice after 3 seconds.
						setTimeout(function() {
							$('.woocommerce-message').fadeOut(300, function() {
								$(this).remove();
							});
						}, 3000);
					}
				},
				complete: function() {
					$button.removeClass('loading');
				}
			});
		});
		
		// Add all to cart.
		$('.wishlist-share-add-all-to-cart').on('click', function(e) {
			e.preventDefault();
			
			var $button = $(this);
			var products = [];
			
			// Get all products.
			$('.wishlist-share-product').each(function() {
				var product_id = $(this).find('.wishlist-add').data('product-id');
				products.push(product_id);
			});
			
			// Add products to cart.
			$.ajax({
				type: 'POST',
				url: wc_add_to_cart_params.ajax_url,
				data: {
					action: 'aqualuxe_wishlist_add_all_to_cart',
					products: products,
					nonce: aqualuxeWooCommerce.nonce
				},
				beforeSend: function() {
					$button.addClass('loading');
				},
				success: function(response) {
					if (response.success) {
						// Update cart fragments.
						$(document.body).trigger('wc_fragment_refresh');
						
						// Show notice.
						$(document.body).trigger('wc_add_to_cart_message', [products, true]);
					}
				},
				complete: function() {
					$button.removeClass('loading');
				}
			});
		});
	});
</script>

<?php
get_footer();