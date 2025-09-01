<?php if (!defined('ABSPATH')) exit; get_header(); ?>
<section class="relative overflow-hidden" aria-label="Hero">
  <canvas id="ax-hero-canvas" class="block w-full h-[60vh] md:h-[80vh]"></canvas>
  <div class="absolute inset-0 pointer-events-none" aria-hidden="true"></div>
  <div class="absolute inset-0 flex items-center justify-center text-center p-6">
    <div>
      <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight">Bringing elegance to aquatic life – globally</h1>
      <p class="mt-4 text-lg opacity-90 max-w-2xl mx-auto">Premium ornamental fish, plants, bespoke aquariums, and professional services.</p>
      <div class="mt-6 flex justify-center gap-4">
        <a href="<?php echo esc_url( (aqualuxe_is_woocommerce_active() && function_exists('wc_get_page_id')) ? get_permalink(wc_get_page_id('shop')) : home_url('/blog/') ); ?>" class="bg-cyan-600 text-white px-6 py-3 rounded shadow hover:bg-cyan-700 transition">Shop Now</a>
        <a href="<?php echo esc_url( home_url('/services/') ); ?>" class="border border-cyan-600 text-cyan-600 px-6 py-3 rounded hover:bg-cyan-50 dark:hover:bg-slate-800 transition">Book a Service</a>
      </div>
    </div>
  </div>
</section>
<section class="mx-auto px-4 <?php echo esc_attr(get_theme_mod('aqualuxe_container_width','max-w-7xl')); ?> py-12 grid gap-8">
  <h2 class="text-2xl font-bold">Featured</h2>
  <div id="ax-featured" class="grid grid-cols-2 md:grid-cols-4 gap-6"></div>
</section>
<?php get_footer(); ?>
