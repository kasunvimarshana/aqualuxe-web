<?php
/** Hero section partial */
$bg = get_theme_mod('aqualuxe_primary_color', '#0ea5e9');
?>
<section class="relative overflow-hidden">
	<div class="container mx-auto px-4 py-16 text-center">
		<h1 class="text-4xl md:text-5xl font-extrabold mb-4" style="letter-spacing:-0.02em;">Bringing elegance to aquatic life – globally.</h1>
		<p class="text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto">Premium ornamental fish, aquatic plants, and luxury aquariums — crafted for discerning collectors and professionals.</p>
		<a href="<?php echo esc_url( home_url('/shop') ); ?>" class="btn mt-6" style="background: <?php echo esc_attr($bg); ?>;">Shop the Collection</a>
	</div>
	<svg aria-hidden="true" focusable="false" class="absolute inset-0 -z-10 opacity-30 dark:opacity-20" viewBox="0 0 1200 400">
		<defs>
			<linearGradient id="alxGrad" x1="0" x2="0" y1="0" y2="1">
				<stop offset="0%" stop-color="#0ea5e9" />
				<stop offset="100%" stop-color="#0369a1" />
			</linearGradient>
		</defs>
		<path d="M0,200 C200,100 400,300 600,200 C800,100 1000,300 1200,200 L1200,400 L0,400 Z" fill="url(#alxGrad)" />
	</svg>
</section>
