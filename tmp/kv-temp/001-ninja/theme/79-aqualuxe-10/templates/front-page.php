<?php /* Template Name: Front Page */
get_header(); ?>

<article id="frontpage" class="ocean-gradient text-slate-900 dark:text-slate-100">
	<!-- HERO: immersive + dynamic waves + WebGL enhancement -->
	<section class="relative min-h-[72vh] flex items-end pb-12" aria-labelledby="hero-title">
		<canvas id="aqlx-hero-3d" class="absolute inset-0 w-full h-full" aria-label="Interactive underwater scene" role="img"></canvas>
		<svg id="aqlx-waves" class="absolute inset-x-0 bottom-0 w-full h-40" viewBox="0 0 1200 160" preserveAspectRatio="none" aria-hidden="true">
			<path fill="currentColor" class="text-sky-300/60 dark:text-slate-700" d="M0,80 C150,120 300,40 450,80 C600,120 750,60 900,80 C1050,100 1125,90 1200,100 L1200,160 L0,160 Z"></path>
			<path fill="currentColor" class="text-sky-400/50 dark:text-slate-600" d="M0,100 C150,140 300,60 450,100 C600,140 750,80 900,100 C1050,120 1125,110 1200,120 L1200,160 L0,160 Z"></path>
			<path fill="currentColor" class="text-sky-500/40 dark:text-slate-500" d="M0,120 C150,160 300,80 450,120 C600,160 750,100 900,120 C1050,140 1125,130 1200,140 L1200,160 L0,160 Z"></path>
		</svg>
		<div class="container relative z-10 max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-8 items-end">
			<div class="py-10">
				<h1 id="hero-title" class="text-4xl md:text-6xl font-extrabold drop-shadow leading-tight">Dive into a next‑generation aquatic experience.</h1>
				<p class="mt-4 max-w-xl opacity-90">Smooth, captivating animations flow like water—crafted to engage and convert with every click. AquaLuxe blends elegant storytelling with seamless interactivity.</p>
				<div class="mt-6 flex flex-wrap gap-3">
					<?php
					$shop_link = '#'; $sid = absint(get_option('woocommerce_shop_page_id')); if ($sid > 0) { $shop_link = get_permalink($sid); }
					if ($shop_link === '#') { $archive = function_exists('get_post_type_archive_link') ? get_post_type_archive_link('product') : false; $shop_link = $archive ? $archive : home_url('/'); }
					?>
					<a href="<?php echo esc_url($shop_link); ?>" data-cta="hero_shop" class="px-6 py-3 rounded bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900 hover-lift">Shop Now</a>
					<a href="#discover" data-cta="hero_discover" class="px-6 py-3 rounded border hover-lift">Discover</a>
					<button id="aqlx-audio-toggle" data-cta="hero_audio" class="px-6 py-3 rounded border hover-lift" aria-pressed="false" aria-label="Toggle ambient ocean audio">Audio: Off</button>
				</div>
				<p class="sr-only">Tip: Use mouse or keyboard arrows to navigate the 3D ocean. Press Space in the mini‑game to drop food.</p>
			</div>
			<div class="hidden md:block justify-self-end pb-2" aria-hidden="true">
				<div class="w-80 h-56 rounded-xl border backdrop-blur bg-white/30 dark:bg-slate-800/30 grid place-items-center">
					<span class="text-slate-700/80 dark:text-slate-200/80 text-sm">Live ocean preview</span>
				</div>
			</div>
		</div>
	</section>

	<section id="discover" class="container max-w-7xl mx-auto px-4 py-14" data-reveal>
		<div class="grid md:grid-cols-3 gap-6">
			<article class="p-5 border rounded hover-lift"><h3 class="font-semibold">Wellbeing & Quarantine</h3><p class="opacity-80 text-sm mt-2">Export‑ready stock conditioned under best‑practice protocols.</p></article>
			<article class="p-5 border rounded hover-lift"><h3 class="font-semibold">Aquascape Design</h3><p class="opacity-80 text-sm mt-2">From nano tanks to commercial walls—design, build, and maintain.</p></article>
			<article class="p-5 border rounded hover-lift"><h3 class="font-semibold">Sustainable Sourcing</h3><p class="opacity-80 text-sm mt-2">Traceable breeder networks and habitat‑friendly practices.</p></article>
		</div>
	</section>

	<section class="bg-white/70 dark:bg-slate-900/40 py-12" aria-labelledby="metrics-title" data-reveal>
		<div class="container max-w-7xl mx-auto px-4">
			<h2 id="metrics-title" class="text-2xl font-semibold">Water Quality & Impact</h2>
			<p class="opacity-80 mb-6">Live or sample metrics—temperature, pH, ammonia—rendered accessibly.</p>
			<div id="aqlx-d3-chart" class="relative w-full h-72 rounded border overflow-hidden" role="img" aria-label="Water quality metrics chart">
				<svg viewBox="0 0 600 240" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
					<rect x="0" y="0" width="600" height="240" fill="rgba(56,189,248,0.10)"/>
					<path d="M0,160 C60,140 120,155 180,145 C240,135 300,150 360,140 C420,130 480,148 540,138 C570,133 585,136 600,132" fill="none" stroke="#0072B2" stroke-width="3"/>
				</svg>
			</div>
		</div>
	</section>

	<section class="container max-w-7xl mx-auto px-4 py-12" data-reveal>
		<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
			<div class="aspect-[4/3] rounded-xl border bg-gradient-to-br from-sky-100 to-white dark:from-slate-700 dark:to-slate-900"></div>
			<div class="aspect-[4/3] rounded-xl border bg-gradient-to-br from-sky-50 to-white dark:from-slate-800 dark:to-slate-900"></div>
			<div class="aspect-[4/3] rounded-xl border bg-gradient-to-br from-sky-100 to-white dark:from-slate-700 dark:to-slate-900"></div>
			<div class="aspect-[4/3] rounded-xl border bg-gradient-to-br from-sky-50 to-white dark:from-slate-800 dark:to-slate-900"></div>
			<div class="aspect-[4/3] rounded-xl border bg-gradient-to-br from-sky-100 to-white dark:from-slate-700 dark:to-slate-900"></div>
			<div class="aspect-[4/3] rounded-xl border bg-gradient-to-br from-sky-50 to-white dark:from-slate-800 dark:to-slate-900"></div>
		</div>
	</section>

	<section class="container max-w-7xl mx-auto px-4 py-12" aria-labelledby="game-title" data-reveal>
		<h2 id="game-title" class="text-2xl font-semibold">Mini‑game: Feed the Fish</h2>
		<p class="opacity-80 mb-3">Click or press Space to drop food; keep the school healthy to increase your score.</p>
		<div id="aqlx-minigame" class="relative h-48 rounded border bg-gradient-to-b from-sky-100 to-sky-300 dark:from-slate-700 dark:to-slate-900" tabindex="0" role="application" aria-label="Feed the fish mini game">
			<div class="absolute inset-0 grid place-items-center text-slate-700 dark:text-slate-200">Loading…</div>
		</div>
	</section>

	<section class="bg-white/70 dark:bg-slate-900/40 py-12" aria-labelledby="reco-title" data-reveal>
		<div class="container max-w-7xl mx-auto px-4">
			<h2 id="reco-title" class="text-2xl font-semibold">Recommended for You</h2>
			<div id="aqlx-recos" class="mt-6 grid sm:grid-cols-2 md:grid-cols-3 gap-6" aria-live="polite" aria-busy="false">
				<?php
				$items=[]; if (post_type_exists('product')) { $pq=new WP_Query(['post_type'=>'product','posts_per_page'=>3,'no_found_rows'=>true,'post_status'=>'publish']); if($pq->have_posts()){ while($pq->have_posts()){ $pq->the_post(); $items[]=get_the_ID(); } wp_reset_postdata(); } }
				$pq2=new WP_Query(['post_type'=>'post','posts_per_page'=>max(0,6-count($items)),'no_found_rows'=>true,'post_status'=>'publish']); if($pq2->have_posts()){ while($pq2->have_posts()){ $pq2->the_post(); $items[]=get_the_ID(); } wp_reset_postdata(); }
				if ($items) { foreach ($items as $post_id) { $post=get_post($post_id); if(! $post) continue; $title=get_the_title($post); $link=get_permalink($post); $excerpt=wp_trim_words(wp_strip_all_tags(get_the_excerpt($post)),24); $thumb_html=''; if (has_post_thumbnail($post)) { $thumb_html=get_the_post_thumbnail($post,'medium',['class'=>'w-full h-36 object-cover rounded mb-3','loading'=>'lazy','decoding'=>'async','alt'=>esc_attr($title)]);} $price_html=''; if (is_callable('wc_get_product') && get_post_type($post)==='product'){ $prod=call_user_func('wc_get_product',$post_id); if($prod){ $price_html='<div class="mt-2 text-sm font-medium">'.esc_html(wp_strip_all_tags($prod->get_price_html())).'</div>'; } } ?>
					<article class="p-4 border rounded hover-lift">
						<?php echo $thumb_html; ?>
						<h3 class="font-semibold mb-2"><a class="hover:underline" href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h3>
						<p class="text-sm opacity-80"><?php echo esc_html($excerpt); ?></p>
						<?php echo $price_html; ?>
					</article>
				<?php } } else { ?><div class="opacity-70">Loading recommendations…</div><?php } ?>
			</div>
		</div>
	</section>

	<section class="container max-w-7xl mx-auto px-4 py-14" data-reveal>
		<div class="rounded-xl border p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-4 bg-white/70 dark:bg-slate-900/40">
			<div><h2 class="text-xl font-semibold">Join the Current</h2><p class="opacity-80 text-sm mt-1">Get product drops, aquascape tips, and export windows.</p></div>
			<form class="flex w-full md:w-auto gap-2" action="#" method="post" novalidate>
				<label class="sr-only" for="aqlx-email">Email</label>
				<input id="aqlx-email" name="email" type="email" required class="flex-1 md:w-72 px-3 py-2 rounded border" placeholder="you@example.com" />
				<button type="submit" data-cta="newsletter_subscribe" class="px-5 py-2 rounded bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900 hover-lift">Subscribe</button>
			</form>
		</div>
	</section>

	<script type="application/ld+json">
	{"@context":"https://schema.org","@type":"Organization","name":"AquaLuxe","url":"<?php echo esc_url(home_url('/')); ?>","sameAs":["<?php echo esc_url(home_url('/')); ?>"],"logo":"<?php echo esc_url(get_site_icon_url()); ?>"}
	</script>
</article>

<?php get_footer(); ?>
