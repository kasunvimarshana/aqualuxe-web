<?php
/** WC product loop override: add wishlist + quick view hooks */
if ( ! defined( 'ABSPATH' ) ) { exit; }

global $product;
?>
<li <?php wc_product_class( 'border rounded overflow-hidden', $product ); ?> itemscope itemtype="https://schema.org/Product">
	<a href="<?php the_permalink(); ?>" class="block" aria-label="<?php the_title_attribute(); ?>">
		<?php woocommerce_template_loop_product_thumbnail(); ?>
		<h2 class="p-3 text-base font-semibold" itemprop="name"><?php woocommerce_template_loop_product_title(); ?></h2>
	</a>
	<div class="px-3 pb-3 flex items-center justify-between gap-2">
		<?php woocommerce_template_loop_price(); ?>
		<div class="flex items-center gap-2">
			<a href="#" data-alx-wishlist data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" class="text-sm">❤</a>
			<a href="#" data-alx-quick-view data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" class="text-sm"><?php esc_html_e( 'Quick View', 'aqualuxe' ); ?></a>
		</div>
	</div>
	<?php woocommerce_template_loop_add_to_cart(); ?>
</li>
