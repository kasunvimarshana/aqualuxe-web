<?php
/*
Template Name: Export
*/
get_header(); ?>
<div class="container max-w-7xl mx-auto px-4 py-8">
  <header class="mb-8">
    <h1 class="text-3xl font-semibold mb-3"><?php esc_html_e('Global Export', 'aqualuxe'); ?></h1>
    <p class="opacity-80"><?php esc_html_e('Certified shipments with quarantine and compliance support.', 'aqualuxe'); ?></p>
  </header>
  <section class="grid md:grid-cols-3 gap-6 mb-10">
    <div class="p-4 border rounded">
      <h2 class="font-semibold mb-2"><?php esc_html_e('Countries We Export To', 'aqualuxe'); ?></h2>
      <p class="text-sm opacity-80"><?php esc_html_e('Europe, Asia, Middle East and more. Reach out for lane availability.', 'aqualuxe'); ?></p>
    </div>
    <div class="p-4 border rounded">
      <h2 class="font-semibold mb-2"><?php esc_html_e('Compliance & Docs', 'aqualuxe'); ?></h2>
      <p class="text-sm opacity-80"><?php esc_html_e('Licensing, health certificates, CITES (where applicable), airway bills.', 'aqualuxe'); ?></p>
    </div>
    <div class="p-4 border rounded">
      <h2 class="font-semibold mb-2"><?php esc_html_e('Export Order Process', 'aqualuxe'); ?></h2>
      <ol class="list-decimal pl-5 space-y-1 text-sm">
        <li><?php esc_html_e('Quote & availability confirmation', 'aqualuxe'); ?></li>
        <li><?php esc_html_e('Quarantine & conditioning', 'aqualuxe'); ?></li>
        <li><?php esc_html_e('Packing & documentation', 'aqualuxe'); ?></li>
        <li><?php esc_html_e('Dispatch & tracking', 'aqualuxe'); ?></li>
      </ol>
    </div>
  </section>
  <div class="flex flex-wrap gap-3">
    <a href="#" class="px-4 py-2 rounded border hover-lift"><?php esc_html_e('Request a Quote', 'aqualuxe'); ?></a>
    <a href="#" class="px-4 py-2 rounded bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900 hover:opacity-95"><?php esc_html_e('Book a Consultation', 'aqualuxe'); ?></a>
  </div>
</div>
<?php get_footer();
