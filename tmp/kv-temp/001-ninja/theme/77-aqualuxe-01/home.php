<?php get_header(); ?>
<?php aqualuxe_get_partial('sections/home-hero'); ?>
<section class="container mx-auto px-4 py-12">
  <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Featured Products', 'aqualuxe'); ?></h2>
  <?php if (aqualuxe_is_wc_active()) { echo do_shortcode('[products limit="8" columns="4" visibility="featured"]'); } else { echo '<p>' . esc_html__('WooCommerce inactive. Add your featured content here.', 'aqualuxe') . '</p>'; } ?>
</section>
<section class="container mx-auto px-4 py-12">
  <h2 class="text-2xl font-bold mb-4"><?php esc_html_e('Testimonials', 'aqualuxe'); ?></h2>
  <div class="grid md:grid-cols-3 gap-6">
    <blockquote class="p-6 rounded bg-slate-100 dark:bg-slate-900">“<?php esc_html_e('Stunning quality and service.', 'aqualuxe'); ?>”</blockquote>
    <blockquote class="p-6 rounded bg-slate-100 dark:bg-slate-900">“<?php esc_html_e('Best rare fish selection.', 'aqualuxe'); ?>”</blockquote>
    <blockquote class="p-6 rounded bg-slate-100 dark:bg-slate-900">“<?php esc_html_e('Fast export handling.', 'aqualuxe'); ?>”</blockquote>
  </div>
</section>
<?php get_footer(); ?>
