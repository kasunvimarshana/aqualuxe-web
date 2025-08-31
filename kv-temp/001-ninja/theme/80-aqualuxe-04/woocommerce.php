<?php
/** WooCommerce wrapper template */
get_header(); ?>
<div class="container mx-auto px-4 py-8 grid gap-8 md:grid-cols-[2fr_1fr]">
  <main id="content" tabindex="-1">
  <?php if (class_exists('WooCommerce')) { call_user_func('woocommerce_content'); } else { echo '<p>' . esc_html__('WooCommerce is not active.', 'aqualuxe') . '</p>'; } ?>
  </main>
  <aside>
    <?php dynamic_sidebar('sidebar-1'); ?>
  </aside>
</div>
<?php get_footer();