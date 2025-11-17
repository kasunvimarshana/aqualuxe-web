<?php
/** Front page template with immersive hero */
get_header(); ?>
<main id="primary" class="site-main" role="main">
  <section class="hero relative" aria-label="Underwater hero">
    <canvas id="aqlx-hero" class="block w-full" aria-hidden="true"></canvas>
    <div class="container relative" style="margin-top:-60vh;">
      <h1 class="text-4xl md:text-6xl font-bold text-white drop-shadow">Bringing elegance to aquatic life – globally.</h1>
      <p class="mt-4 text-white/90 max-w-prose">Discover rare species, premium equipment, and expert services.</p>
      <p class="mt-6"><a class="btn btn-primary" href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">Shop Now</a></p>
    </div>
  </section>
  <section class="container py-12">
    <header><h2 class="text-2xl font-semibold">Featured</h2></header>
    <?php if ( function_exists( 'is_woocommerce_activated' ) && is_woocommerce_activated() ) : ?>
      <?php // Placeholder for products grid via WC shortcode ?>
      <?php echo do_shortcode('[products limit="8" columns="4" visibility="featured"]'); // phpcs:ignore WordPress.Security.EscapeOutput ?>
    <?php else : ?>
      <div class="posts"><?php while ( have_posts() ) : the_post(); get_template_part('template-parts/content'); endwhile; ?></div>
    <?php endif; ?>
  </section>
</main>
<?php
wp_enqueue_script('aqualuxe-hero', function_exists('aqlx_asset') ? aqlx_asset('js/hero.js') : get_template_directory_uri() . '/assets/dist/js/hero.js', [ 'aqualuxe-app' ], AQUALUXE_VERSION, true);
wp_add_inline_script('aqualuxe-hero', 'if(window.aqlx_hero_init){window.aqlx_hero_init()}');
get_footer();
