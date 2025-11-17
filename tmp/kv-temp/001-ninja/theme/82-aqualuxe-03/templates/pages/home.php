<?php /** Template Name: Home (AquaLuxe) */ ?>
<?php
// Build a whitelist of available local GLB models to avoid 404s in reef mode
$child_dir = get_stylesheet_directory();
$parent_dir = get_template_directory();
$models_dirs = [
	$child_dir . '/assets/dist/models',
	$child_dir . '/assets/src/models', // child fallback if used
	$parent_dir . '/assets/dist/models',
	$parent_dir . '/assets/src/models', // parent theme (default)
];
$wl = [];
foreach ($models_dirs as $dir) {
	if (is_dir($dir)) {
		$files = glob($dir . '/*.glb') ?: [];
		foreach ($files as $f) { $wl[basename($f)] = true; }
	}
}
$reef_wl_attr = esc_attr(implode(',', array_keys($wl)));
?>
<main id="primary" class="site-main" role="main">
	<section class="hero relative overflow-hidden" aria-label="Aquatic hero">
	<div id="alx-hero-canvas" class="relative z-0 w-full h-[60vh] md:h-[80vh]" data-ambient="on" data-reef="on" data-reef-placeholders="off" data-reef-post="bloom" data-reef-whitelist="<?php echo $reef_wl_attr; ?>"></div>
	<div class="container mx-auto px-4 absolute inset-0 z-40 flex items-center justify-center text-center pointer-events-none alx-hero-overlay" style="z-index:50;">
			<div class="pointer-events-auto">
			<div class="text-white drop-shadow-lg">
				<h1 class="text-4xl md:text-6xl font-bold"><?php echo esc_html__( 'Bringing elegance to aquatic life – globally', 'aqualuxe' ); ?></h1>
				<p class="mt-4 md:text-lg opacity-90"><?php echo esc_html__( 'Premium livestock, plants, equipment & services', 'aqualuxe' ); ?></p>
				<div class="mt-6 flex items-center justify-center gap-4">
					<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/shop' ) ); ?>"><?php esc_html_e( 'Shop Now', 'aqualuxe' ); ?></a>
					<a class="btn btn-ghost" href="<?php echo esc_url( home_url( '/services' ) ); ?>"><?php esc_html_e( 'Book a Service', 'aqualuxe' ); ?></a>
				</div>
				</div>
			</div>
		</div>
	</section>
		<section class="container mx-auto px-4 py-12" aria-labelledby="viewer">
			<h2 id="viewer" class="text-2xl md:text-3xl font-semibold mb-4"><?php esc_html_e( 'Interactive Model', 'aqualuxe' ); ?></h2>
				<?php $poster = file_exists( get_stylesheet_directory() . '/assets/dist/images/poster.jpg') ? get_stylesheet_directory_uri() . '/assets/dist/images/poster.jpg' : get_template_directory_uri() . '/assets/src/images/hero-fallback.svg'; ?>
				<div class="alx-glb-viewer aspect-[16/9] w-full bg-black/40 rounded relative" data-glb="zebra-clownfish-1528.glb" data-poster="<?php echo esc_url( $poster ); ?>"></div>
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
