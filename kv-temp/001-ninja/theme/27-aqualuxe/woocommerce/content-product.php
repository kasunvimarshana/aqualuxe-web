<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
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

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

// Get product attributes
$is_new = get_post_meta( $product->get_id(), '_is_new', true );
$is_exclusive = get_post_meta( $product->get_id(), '_is_exclusive', true );
$is_rare = get_post_meta( $product->get_id(), '_is_rare', true );
$rarity_level = get_post_meta( $product->get_id(), '_rarity_level', true );
$origin = get_post_meta( $product->get_id(), '_origin', true );
$care_level = get_post_meta( $product->get_id(), '_care_level', true );
$tank_size = get_post_meta( $product->get_id(), '_tank_size', true );
$adult_size = get_post_meta( $product->get_id(), '_adult_size', true );
$water_conditions = get_post_meta( $product->get_id(), '_water_conditions', true );
$diet = get_post_meta( $product->get_id(), '_diet', true );
$breeding = get_post_meta( $product->get_id(), '_breeding', true );
$compatibility = get_post_meta( $product->get_id(), '_compatibility', true );
?>
<li <?php wc_product_class( 'product-card transition-all duration-300 hover:-translate-y-1 hover:shadow-medium', $product ); ?>>
	<div class="product-inner h-full flex flex-col">
		<?php
		/**
		 * Hook: woocommerce_before_shop_loop_item.
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item' );
		?>

		<div class="product-image-wrapper relative overflow-hidden">
			<?php
			/**
			 * Hook: woocommerce_before_shop_loop_item_title.
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			do_action( 'woocommerce_before_shop_loop_item_title' );
			?>
			
			<?php if ( $product->is_on_sale() ) : ?>
				<div class="product-badge badge-sale">
					<?php esc_html_e( 'Sale', 'aqualuxe' ); ?>
				</div>
			<?php elseif ( $is_new ) : ?>
				<div class="product-badge badge-new">
					<?php esc_html_e( 'New', 'aqualuxe' ); ?>
				</div>
			<?php elseif ( $is_exclusive ) : ?>
				<div class="product-badge badge-exclusive">
					<?php esc_html_e( 'Exclusive', 'aqualuxe' ); ?>
				</div>
			<?php elseif ( $is_rare ) : ?>
				<div class="product-badge badge-rare">
					<?php esc_html_e( 'Rare', 'aqualuxe' ); ?>
				</div>
			<?php endif; ?>
			
			<div class="product-actions absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-dark-900/80 to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100">
				<div class="flex justify-center space-x-2">
					<?php
					// Quick view button
					echo '<button class="quick-view-button w-10 h-10 rounded-full bg-white text-dark-900 hover:bg-primary-600 hover:text-white transition-colors flex items-center justify-center" data-product-id="' . esc_attr( $product->get_id() ) . '" aria-label="' . esc_attr__( 'Quick view', 'aqualuxe' ) . '">';
					echo '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>';
					echo '</button>';
					
					// Wishlist button if YITH WooCommerce Wishlist is active
					if ( defined( 'YITH_WCWL' ) ) {
						echo do_shortcode( '[yith_wcwl_add_to_wishlist product_id="' . $product->get_id() . '" icon="fa fa-heart-o" label="" already_in_wishslist_text="" browse_wishlist_text=""]' );
					}
					
					// Compare button if YITH WooCommerce Compare is active
					if ( defined( 'YITH_WOOCOMPARE' ) ) {
						echo do_shortcode( '[yith_compare_button product_id="' . $product->get_id() . '" icon="fa fa-refresh" label=""]' );
					}
					?>
				</div>
			</div>
		</div>

		<div class="product-content p-4 flex-grow flex flex-col">
			<?php
			/**
			 * Hook: woocommerce_shop_loop_item_title.
			 *
			 * @hooked woocommerce_template_loop_product_title - 10
			 */
			remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
			do_action( 'woocommerce_shop_loop_item_title' );
			?>
			
			<h2 class="woocommerce-loop-product__title text-lg font-bold mb-2">
				<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
			</h2>
			
			<?php if ( $origin ) : ?>
				<div class="product-origin text-sm mb-2">
					<span class="font-medium"><?php esc_html_e( 'Origin:', 'aqualuxe' ); ?></span> <?php echo esc_html( $origin ); ?>
				</div>
			<?php endif; ?>
			
			<?php if ( $rarity_level ) : ?>
				<div class="product-rarity text-sm mb-2">
					<span class="font-medium"><?php esc_html_e( 'Rarity:', 'aqualuxe' ); ?></span>
					<span class="rarity-stars">
						<?php
						$rarity_stars = min( 5, max( 1, intval( $rarity_level ) ) );
						for ( $i = 1; $i <= 5; $i++ ) {
							if ( $i <= $rarity_stars ) {
								echo '<span class="star filled text-accent-500">★</span>';
							} else {
								echo '<span class="star">☆</span>';
							}
						}
						?>
					</span>
				</div>
			<?php endif; ?>

			<?php
			/**
			 * Hook: woocommerce_after_shop_loop_item_title.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
			?>
			
			<div class="product-meta mt-auto pt-4">
				<?php
				/**
				 * Hook: woocommerce_after_shop_loop_item.
				 *
				 * @hooked woocommerce_template_loop_product_link_close - 5
				 * @hooked woocommerce_template_loop_add_to_cart - 10
				 */
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
				do_action( 'woocommerce_after_shop_loop_item' );
				?>
			</div>
		</div>
		
		<div class="product-details hidden">
			<?php if ( $care_level ) : ?>
				<div class="product-care-level">
					<span class="font-medium"><?php esc_html_e( 'Care Level:', 'aqualuxe' ); ?></span> <?php echo esc_html( $care_level ); ?>
				</div>
			<?php endif; ?>
			
			<?php if ( $tank_size ) : ?>
				<div class="product-tank-size">
					<span class="font-medium"><?php esc_html_e( 'Tank Size:', 'aqualuxe' ); ?></span> <?php echo esc_html( $tank_size ); ?>
				</div>
			<?php endif; ?>
			
			<?php if ( $adult_size ) : ?>
				<div class="product-adult-size">
					<span class="font-medium"><?php esc_html_e( 'Adult Size:', 'aqualuxe' ); ?></span> <?php echo esc_html( $adult_size ); ?>
				</div>
			<?php endif; ?>
			
			<?php if ( $water_conditions ) : ?>
				<div class="product-water-conditions">
					<span class="font-medium"><?php esc_html_e( 'Water Conditions:', 'aqualuxe' ); ?></span> <?php echo esc_html( $water_conditions ); ?>
				</div>
			<?php endif; ?>
			
			<?php if ( $diet ) : ?>
				<div class="product-diet">
					<span class="font-medium"><?php esc_html_e( 'Diet:', 'aqualuxe' ); ?></span> <?php echo esc_html( $diet ); ?>
				</div>
			<?php endif; ?>
			
			<?php if ( $breeding ) : ?>
				<div class="product-breeding">
					<span class="font-medium"><?php esc_html_e( 'Breeding:', 'aqualuxe' ); ?></span> <?php echo esc_html( $breeding ); ?>
				</div>
			<?php endif; ?>
			
			<?php if ( $compatibility ) : ?>
				<div class="product-compatibility">
					<span class="font-medium"><?php esc_html_e( 'Compatibility:', 'aqualuxe' ); ?></span> <?php echo esc_html( $compatibility ); ?>
				</div>
			<?php endif; ?>
			
			<div class="product-description mt-4">
				<?php echo wp_kses_post( $product->get_short_description() ); ?>
			</div>
		</div>
	</div>
</li>