<?php /** Template Name: Home (AquaLuxe) */ ?>
<main id="primary" class="site-main" role="main">
    <section class="hero relative overflow-hidden" aria-label="Aquatic hero">
	<div id="alx-hero-canvas" class="w-full h-[60vh] md:h-[80vh]" data-ambient="off"></div>
		<div class="container mx-auto px-4 absolute inset-0 flex items-center justify-center text-center">
			<div class="text-white drop-shadow-lg">
				<h1 class="text-4xl md:text-6xl font-bold"><?php echo esc_html__( 'Bringing elegance to aquatic life – globally', 'aqualuxe' ); ?></h1>
				<p class="mt-4 md:text-lg opacity-90"><?php echo esc_html__( 'Premium livestock, plants, equipment & services', 'aqualuxe' ); ?></p>
				<div class="mt-6 flex items-center justify-center gap-4">
					<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/shop' ) ); ?>"><?php esc_html_e( 'Shop Now', 'aqualuxe' ); ?></a>
					<a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/services' ) ); ?>"><?php esc_html_e( 'Book a Service', 'aqualuxe' ); ?></a>
				</div>
			</div>
		</div>
	</section>
	<section class="container mx-auto px-4 py-12" aria-labelledby="featured">
		<h2 id="featured" class="text-2xl md:text-3xl font-semibold mb-6"><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></h2>
		<?php if ( function_exists( 'woocommerce_product_loop_start' ) && class_exists( 'WooCommerce' ) ) : ?>
			<?php echo do_shortcode('[products limit="8" columns="4" visibility="featured"]'); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'WooCommerce is not active. Showcase featured services or posts here.', 'aqualuxe' ); ?></p>
		<?php endif; ?>
	</section>
</main>
