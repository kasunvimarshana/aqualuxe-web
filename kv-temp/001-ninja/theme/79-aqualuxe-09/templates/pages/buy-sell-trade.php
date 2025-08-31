<?php
/*
Template Name: Buy, Sell & Trade
*/
get_header(); ?>
<div class="container max-w-7xl mx-auto px-4 py-8">
  <header class="mb-8">
    <h1 class="text-3xl font-semibold mb-3"><?php esc_html_e('Buy, Sell & Trade', 'aqualuxe'); ?></h1>
    <p class="opacity-80"><?php esc_html_e('Trade-ins, auctions and community programs to grow the hobby responsibly.', 'aqualuxe'); ?></p>
  </header>
  <section class="grid md:grid-cols-3 gap-6 mb-10">
    <div class="p-4 border rounded">
      <h2 class="font-semibold mb-2"><?php esc_html_e('Sell to AquaLuxe', 'aqualuxe'); ?></h2>
      <p class="mb-2"><?php esc_html_e('Breeders, hobbyists and collectors can submit stock for buy-back or export.', 'aqualuxe'); ?></p>
      <a href="#" class="text-sm underline"><?php esc_html_e('Submit a listing', 'aqualuxe'); ?></a>
    </div>
    <div class="p-4 border rounded">
      <h2 class="font-semibold mb-2"><?php esc_html_e('Trade-In Program', 'aqualuxe'); ?></h2>
      <p class="mb-2"><?php esc_html_e('Swap eligible livestock or plants for store credit or cash.', 'aqualuxe'); ?></p>
      <a href="#" class="text-sm underline"><?php esc_html_e('View guidelines', 'aqualuxe'); ?></a>
    </div>
    <div class="p-4 border rounded">
      <h2 class="font-semibold mb-2"><?php esc_html_e('Auctions & Bidding', 'aqualuxe'); ?></h2>
      <p class="mb-2"><?php esc_html_e('Timed listings and livestream events for premium specimens.', 'aqualuxe'); ?></p>
      <a href="#" class="text-sm underline"><?php esc_html_e('See upcoming auctions', 'aqualuxe'); ?></a>
    </div>
  </section>
</div>
<?php get_footer();
