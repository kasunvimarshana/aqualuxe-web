<?php
$tagline = __('Bringing elegance to aquatic life – globally.', 'aqualuxe');
?>
<section class="relative overflow-hidden">
  <div class="container mx-auto px-4 py-20 text-center">
    <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight">Aqua<span class="text-sky-600">Luxe</span></h1>
    <p class="mt-4 text-lg opacity-80"><?php echo esc_html($tagline); ?></p>
    <div class="mt-8 flex justify-center gap-4">
      <a href="<?php echo esc_url(home_url('/shop')); ?>" class="px-6 py-3 bg-sky-600 text-white rounded"><?php esc_html_e('Shop Now', 'aqualuxe'); ?></a>
      <a href="<?php echo esc_url(home_url('/services')); ?>" class="px-6 py-3 border border-slate-300 dark:border-slate-700 rounded"><?php esc_html_e('Book a Service', 'aqualuxe'); ?></a>
    </div>
  </div>
</section>
