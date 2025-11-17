<?php
/*
Template Name: Buy, Sell & Trade
*/
if (function_exists('get_header')) { call_user_func('get_header'); }
?>
<main id="primary" class="container mx-auto px-4 py-10">
  <header class="mb-8"><h1 class="text-3xl font-bold"><?php if (function_exists('the_title')) { call_user_func('the_title'); } ?></h1></header>
  <section class="prose max-w-none mb-10"><?php if (function_exists('the_content')) { call_user_func('the_content'); } ?></section>
  <section class="mb-12">
    <h2 class="text-2xl font-semibold mb-4"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__', 'Submit a Trade-In', 'aqualuxe') : 'Submit a Trade-In'; ?></h2>
    <?php echo function_exists('do_shortcode') ? call_user_func('do_shortcode', '[aqualuxe_tradein_form]') : ''; ?>
  </section>
  <section class="mt-12">
    <h2 class="text-2xl font-semibold mb-4"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__', 'Popular Services', 'aqualuxe') : 'Popular Services'; ?></h2>
    <?php echo function_exists('do_shortcode') ? call_user_func('do_shortcode', '[aqualuxe_services grid="3" count="6"]') : ''; ?>
  </section>
  <section class="mt-12">
    <h2 class="text-2xl font-semibold mb-4"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__', 'Upcoming Events', 'aqualuxe') : 'Upcoming Events'; ?></h2>
    <?php echo function_exists('do_shortcode') ? call_user_func('do_shortcode', '[aqualuxe_upcoming_events count="5"]') : ''; ?>
  </section>
</main>
<?php if (function_exists('get_footer')) { call_user_func('get_footer'); }
