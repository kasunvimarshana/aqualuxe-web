<?php /* Template Name: Front Page */
get_header(); ?>

<article id="frontpage" class="ocean-gradient text-slate-900 dark:text-slate-100">
	<!-- HERO: WebGL canvas mounted by frontpage.js; progressively enhanced -->
	<section class="relative min-h-[60vh] md:min-h-[70vh] flex items-center" aria-labelledby="hero-title">
		<canvas id="aqlx-hero-3d" class="absolute inset-0 w-full h-full" aria-label="Interactive underwater scene" role="img"></canvas>

		<!-- Overlay content for SEO & accessibility (readable without JS) -->
		<div class="container relative z-10 max-w-7xl mx-auto px-4 py-16">
			<h1 id="hero-title" class="text-4xl md:text-6xl font-extrabold drop-shadow">Bringing elegance to aquatic life – globally.</h1>
			<p class="mt-4 max-w-2xl opacity-90">Premium ornamental fish, aquatic plants, custom aquariums, and export services. Explore an interactive ocean journey below.</p>
			  <div class="mt-6 flex flex-wrap gap-3">
				<?php
				// Resolve shop link without referencing WooCommerce functions directly.
				$shop_link = '#';
				$sid = absint(get_option('woocommerce_shop_page_id'));
				if ($sid > 0) {
					$shop_link = get_permalink($sid);
				}
				if ($shop_link === '#') {
					$archive = function_exists('get_post_type_archive_link') ? get_post_type_archive_link('product') : false;
					$shop_link = $archive ? $archive : home_url('/');
				}
				?>
				<a href="<?php echo esc_url($shop_link); ?>" class="px-5 py-3 rounded bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900">Shop Now</a>
				<a href="#story" class="px-5 py-3 rounded border">Start the Journey</a>
				<button id="aqlx-audio-toggle" class="px-5 py-3 rounded border" aria-pressed="false" aria-label="Toggle ambient ocean audio">Audio: Off</button>
			</div>
			<p class="sr-only">Tip: Use mouse or keyboard arrows to navigate the 3D ocean. Press P to pause animations.</p>
		</div>

		<!-- No WebGL fallback -->
		<noscript>
			<div class="container relative z-10 max-w-7xl mx-auto px-4 py-8">
				<p class="text-sm opacity-80">JavaScript is disabled. You are seeing a simplified version of the AquaLuxe homepage.</p>
			</div>
		</noscript>
	</section>

	<!-- STORY: narrative sections progressively revealed with scroll-triggered animations -->
	<section id="story" class="container max-w-7xl mx-auto px-4 py-12 space-y-16" data-reveal>
		<div class="grid md:grid-cols-2 gap-8 items-center">
			<div>
				<h2 class="text-3xl font-semibold">From murky to crystal-clear</h2>
				<p class="mt-3 opacity-80">Follow a guided journey from polluted waters to pristine oceans. Learn how AquaLuxe sourcing, quarantine, and husbandry raise the bar for welfare and sustainability.</p>
			</div>
			<div class="rounded border p-4" aria-hidden="true">
				<div class="h-40 bg-gradient-to-b from-sky-200/70 to-sky-500/30 dark:from-slate-700/60 dark:to-slate-800/60 rounded"></div>
			</div>
		</div>

		<div class="grid md:grid-cols-3 gap-6">
			<div class="p-4 border rounded"><h3 class="font-semibold">Quarantine & Health</h3><p class="opacity-80 text-sm mt-2">Export-ready stock conditioned under best-practice protocols.</p></div>
			<div class="p-4 border rounded"><h3 class="font-semibold">Aquascape Design</h3><p class="opacity-80 text-sm mt-2">From nano tanks to commercial feature walls, fully managed.</p></div>
			<div class="p-4 border rounded"><h3 class="font-semibold">Sustainable Sourcing</h3><p class="opacity-80 text-sm mt-2">Traceable breeder networks and habitat-friendly practices.</p></div>
		</div>
	</section>

	<!-- DATA: D3 chart placeholder; hydrated client-side with sample or live metrics -->
	<section class="bg-white/70 dark:bg-slate-900/40 py-12" aria-labelledby="metrics-title" data-reveal>
		<div class="container max-w-7xl mx-auto px-4">
			<h2 id="metrics-title" class="text-2xl font-semibold">Water Quality & Impact</h2>
			<p class="opacity-80 mb-6">Live or sample metrics visualized—temperature, pH, ammonia, and impact analytics.</p>
			<div id="aqlx-d3-chart" class="w-full h-64 rounded border overflow-hidden" role="img" aria-label="Water quality metrics chart">
				<!-- Server-rendered fallback sparkline (replaced by D3 when JS is available) -->
				<svg viewBox="0 0 600 200" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
					<defs>
						<linearGradient id="tempGrad" x1="0" x2="0" y1="0" y2="1">
							<stop offset="0%" stop-color="#38bdf8" stop-opacity="0.6"/>
							<stop offset="100%" stop-color="#38bdf8" stop-opacity="0.05"/>
						</linearGradient>
					</defs>
					<rect x="0" y="0" width="600" height="200" fill="url(#tempGrad)"/>
					<path d="M0,130 C50,110 100,125 150,115 C200,105 250,120 300,110 C350,100 400,118 450,108 C500,98 550,115 600,105" fill="none" stroke="#0ea5e9" stroke-width="3"/>
				</svg>
			</div>
		</div>
	</section>

	<!-- MINI-GAME: Progressive enhancement; keyboard accessible -->
	<section class="container max-w-7xl mx-auto px-4 py-12" aria-labelledby="game-title" data-reveal>
		<h2 id="game-title" class="text-2xl font-semibold">Mini‑game: Feed the Fish</h2>
		<p class="opacity-80 mb-3">Click or press space to drop food; keep the school healthy to increase your score.</p>
		<div id="aqlx-minigame" class="relative h-48 rounded border bg-gradient-to-b from-sky-100 to-sky-300 dark:from-slate-700 dark:to-slate-900" tabindex="0" role="application" aria-label="Feed the fish mini game">
			<div class="absolute inset-0 grid place-items-center text-slate-700 dark:text-slate-200">Loading…</div>
		</div>
	</section>

	<!-- RECOMMENDATIONS: filled via REST; graceful fallback to recent posts/products -->
	<section class="bg-white/70 dark:bg-slate-900/40 py-12" aria-labelledby="reco-title" data-reveal>
		<div class="container max-w-7xl mx-auto px-4">
			<h2 id="reco-title" class="text-2xl font-semibold">Recommended for You</h2>
			<div id="aqlx-recos" class="mt-6 grid sm:grid-cols-2 md:grid-cols-3 gap-6" aria-live="polite" aria-busy="false">
				<?php
				// Server-rendered fallback items (replaced by JS hydration when available)
				$items = [];
				if (post_type_exists('product')) {
					$pq = new WP_Query([
						'post_type' => 'product',
						'posts_per_page' => 3,
						'no_found_rows' => true,
						'post_status' => 'publish',
					]);
					if ($pq->have_posts()) { while ($pq->have_posts()) { $pq->the_post(); $items[] = get_the_ID(); } wp_reset_postdata(); }
				}
				$pq2 = new WP_Query([
					'post_type' => 'post',
					'posts_per_page' => max(0, 6 - count($items)),
					'no_found_rows' => true,
					'post_status' => 'publish',
				]);
				if ($pq2->have_posts()) { while ($pq2->have_posts()) { $pq2->the_post(); $items[] = get_the_ID(); } wp_reset_postdata(); }

				if ($items) {
					foreach ($items as $post_id) {
						$post = get_post($post_id);
						if (! $post) { continue; }
						$title = get_the_title($post);
						$link  = get_permalink($post);
						$excerpt = wp_trim_words(wp_strip_all_tags(get_the_excerpt($post)), 24);
						$thumb_html = '';
						if (has_post_thumbnail($post)) {
							$thumb_html = get_the_post_thumbnail($post, 'medium', [
								'class' => 'w-full h-36 object-cover rounded mb-3',
								'loading' => 'lazy',
								'decoding' => 'async',
								'alt' => esc_attr($title),
							]);
						}
						$price_html = '';
						if (is_callable('wc_get_product') && get_post_type($post) === 'product') {
							$prod = call_user_func('wc_get_product', $post_id);
							if ($prod) { $price_html = '<div class="mt-2 text-sm font-medium">' . esc_html(wp_strip_all_tags($prod->get_price_html())) . '</div>'; }
						}
						?>
						<article class="p-4 border rounded hover-lift">
							<?php echo $thumb_html; ?>
							<h3 class="font-semibold mb-2"><a class="hover:underline" href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h3>
							<p class="text-sm opacity-80"><?php echo esc_html($excerpt); ?></p>
							<?php echo $price_html; ?>
						</article>
						<?php
					}
				} else {
					?>
					<div class="opacity-70">Loading recommendations…</div>
					<?php
				}
				?>
			</div>
		</div>
	</section>

	<!-- TESTIMONIALS: basic loop from posts as placeholder -->
	<section class="container max-w-7xl mx-auto px-4 py-12" aria-labelledby="testimonials-title">
		<h2 id="testimonials-title" class="text-2xl font-semibold">What enthusiasts say</h2>
		<div class="mt-6 grid md:grid-cols-3 gap-6">
			<?php
			$q = new WP_Query(['post_type' => 'post', 'posts_per_page' => 3]);
			if ($q->have_posts()): while ($q->have_posts()): $q->the_post(); ?>
				<article class="p-4 border rounded">
					<h3 class="font-semibold mb-2"><?php the_title(); ?></h3>
					<p class="text-sm opacity-80"><?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_excerpt()), 24)); ?></p>
				</article>
			<?php endwhile; wp_reset_postdata(); else: ?>
				<p class="opacity-70"><?php esc_html_e('Testimonials will appear here.', 'aqualuxe'); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<!-- JSON-LD structured data for the brand and products (minimal example) -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "Organization",
		"name": "AquaLuxe",
		"url": "<?php echo esc_url(home_url('/')); ?>",
		"sameAs": ["<?php echo esc_url(home_url('/')); ?>"],
		"logo": "<?php echo esc_url(get_site_icon_url()); ?>"
	}
	</script>
</article>

<?php get_footer(); ?>
