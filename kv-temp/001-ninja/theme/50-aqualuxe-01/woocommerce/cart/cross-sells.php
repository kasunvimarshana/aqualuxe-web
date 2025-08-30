<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( $cross_sells ) : ?>

	<div class="cross-sells">
		<h2><?php esc_html_e( 'You may also like', 'aqualuxe' ); ?></h2>

		<div class="cross-sells-slider">
			<?php foreach ( $cross_sells as $cross_sell ) : ?>
				<?php
					$post_object = get_post( $cross_sell->get_id() );
					setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
				?>
				<div class="cross-sell-item">
					<div class="cross-sell-inner">
						<div class="cross-sell-image">
							<a href="<?php echo esc_url( get_permalink() ); ?>">
								<?php echo $cross_sell->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</a>
						</div>
						
						<div class="cross-sell-details">
							<h3 class="cross-sell-title">
								<a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( $cross_sell->get_name() ); ?></a>
							</h3>
							
							<div class="cross-sell-price">
								<?php echo $cross_sell->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
							
							<div class="cross-sell-actions">
								<?php
								echo apply_filters(
									'woocommerce_loop_add_to_cart_link', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									sprintf(
										'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
										esc_url( $cross_sell->add_to_cart_url() ),
										esc_attr( 1 ),
										esc_attr( 'button add_to_cart_button' ),
										'data-product_id="' . esc_attr( $cross_sell->get_id() ) . '" data-product_sku="' . esc_attr( $cross_sell->get_sku() ) . '"',
										esc_html( $cross_sell->add_to_cart_text() )
									),
									$cross_sell
								);
								?>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
endif;

wp_reset_postdata();