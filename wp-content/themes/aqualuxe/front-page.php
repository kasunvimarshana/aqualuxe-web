<?php
get_header();
?>
<main id="primary" class="site-main" role="main">
  <section class="hero relative overflow-hidden">
    <div class="container mx-auto px-4 py-20 text-center">
      <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight">
        <?php echo esc_html(get_theme_mod('blogname', get_bloginfo('name'))); ?>
      </h1>
      <p class="mt-4 text-lg opacity-80"><?php esc_html_e('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></p>
      <div class="mt-8 flex justify-center gap-4">
        <a class="btn btn-primary" href="<?php echo esc_url(home_url('/shop')); ?>"><?php esc_html_e('Shop Now', 'aqualuxe'); ?></a>
        <a class="btn btn-secondary" href="<?php echo esc_url(home_url('/services')); ?>"><?php esc_html_e('Book a Service', 'aqualuxe'); ?></a>
      </div>
    </div>
  </section>

  <section class="featured container mx-auto px-4 py-16" aria-labelledby="featured-heading">
    <h2 id="featured-heading" class="text-2xl font-semibold mb-6"><?php esc_html_e('Featured', 'aqualuxe'); ?></h2>
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
	<?php
	if ( function_exists( 'wc_get_products' ) ) {
		$products = wc_get_products( array( 'status' => 'publish', 'limit' => 6 ) );
		foreach ( $products as $product ) {
			$product_link = get_permalink( $product->get_id() );
			echo '<a class="card block border border-slate-200 dark:border-slate-800 rounded-lg overflow-hidden" href="' . esc_url( $product_link ) . '">';
			echo wp_kses_post( $product->get_image( 'medium' ) );
			echo '<div class="p-4"><div class="font-semibold">' . esc_html( $product->get_name() ) . '</div>';
			echo '<div class="opacity-80">' . wp_kses_post( $product->get_price_html() ) . '</div></div></a>';
		}
	} else {
		$q = new WP_Query( array( 'posts_per_page' => 6 ) );
		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				echo '<a class="card block border border-slate-200 dark:border-slate-800 rounded-lg p-4" href="' . esc_url( get_permalink() ) . '"><div class="font-semibold">' . esc_html( get_the_title() ) . '</div></a>';
			}
			wp_reset_postdata();
		}
	}
	?>
    </div>
  </section>

  <section class="newsletter bg-slate-50 dark:bg-slate-900 py-16" aria-labelledby="newsletter-heading">
    <div class="container mx-auto px-4">
      <h2 id="newsletter-heading" class="text-2xl font-semibold mb-2"><?php esc_html_e('Stay in the current', 'aqualuxe'); ?></h2>
      <p class="opacity-80 mb-6"><?php esc_html_e('Subscribe to our newsletter for rare arrivals and care tips.', 'aqualuxe'); ?></p>
      <form method="post" action="<?php echo esc_url(home_url('/')); ?>" class="flex gap-2 max-w-xl">
        <label for="nl-email" class="sr-only"><?php esc_html_e('Email address', 'aqualuxe'); ?></label>
        <input id="nl-email" name="email" type="email" required class="flex-1 rounded border px-3 py-2" placeholder="you@example.com" />
        <button class="btn btn-primary"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
      </form>
    </div>
	</section>
</main>
<?php
get_footer();
