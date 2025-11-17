<?php
/*
Template Name: Wholesale & B2B
*/
get_header(); ?>
<div class="container max-w-7xl mx-auto px-4 py-8">
  <header class="mb-8">
    <h1 class="text-3xl font-semibold mb-3"><?php esc_html_e('Wholesale & B2B', 'aqualuxe'); ?></h1>
    <p class="opacity-80"><?php esc_html_e('Bulk supply for retailers, designers, hospitality and public spaces.', 'aqualuxe'); ?></p>
  </header>
  <section class="grid md:grid-cols-2 gap-6 mb-10">
    <div class="p-4 border rounded">
      <h2 class="font-semibold mb-2"><?php esc_html_e('Who We Serve', 'aqualuxe'); ?></h2>
      <ul class="list-disc pl-5 space-y-1">
        <li><?php esc_html_e('Pet stores and aquarium retailers', 'aqualuxe'); ?></li>
        <li><?php esc_html_e('Hotels, offices and interior designers', 'aqualuxe'); ?></li>
        <li><?php esc_html_e('Public aquariums and zoos', 'aqualuxe'); ?></li>
        <li><?php esc_html_e('Exporters, importers and distributors', 'aqualuxe'); ?></li>
      </ul>
    </div>
    <div class="p-4 border rounded">
      <h2 class="font-semibold mb-2"><?php esc_html_e('Benefits', 'aqualuxe'); ?></h2>
      <ul class="list-disc pl-5 space-y-1">
        <li><?php esc_html_e('Bulk pricing and contract supply', 'aqualuxe'); ?></li>
        <li><?php esc_html_e('Quarantined, export-ready stock', 'aqualuxe'); ?></li>
        <li><?php esc_html_e('Logistics assistance and documentation', 'aqualuxe'); ?></li>
        <li><?php esc_html_e('Dedicated account support', 'aqualuxe'); ?></li>
      </ul>
    </div>
  </section>
  <section class="mb-10">
    <h2 class="font-semibold mb-3"><?php esc_html_e('Catalog & Onboarding', 'aqualuxe'); ?></h2>
    <p class="mb-3"><?php esc_html_e('Browse our wholesale catalog and request your B2B account to access negotiated pricing and availability.', 'aqualuxe'); ?></p>
    <div class="flex flex-wrap gap-3">
      <a href="#" class="px-4 py-2 rounded border hover-lift"><?php esc_html_e('Download Catalog', 'aqualuxe'); ?></a>
      <a href="#" class="px-4 py-2 rounded bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900 hover:opacity-95"><?php esc_html_e('Apply for Wholesale Account', 'aqualuxe'); ?></a>
    </div>
  </section>
</div>
<?php get_footer();
