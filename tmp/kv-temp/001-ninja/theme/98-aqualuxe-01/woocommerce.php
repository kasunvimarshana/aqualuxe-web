<?php
/** WooCommerce wrapper template */
get_header(); ?>
<div class="container mx-auto px-4 py-8">
	<?php if ( function_exists( 'is_woocommerce' ) ) : ?>
		<?php woocommerce_content(); ?>
	<?php else : ?>
		<p><?php esc_html_e('WooCommerce is not active.', 'aqualuxe'); ?></p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
