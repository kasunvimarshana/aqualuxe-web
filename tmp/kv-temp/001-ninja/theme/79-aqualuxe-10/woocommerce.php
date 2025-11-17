<?php
/** WooCommerce wrapper template */
get_header(); ?>
<div class="container max-w-7xl mx-auto px-4 py-8">
  <?php if (function_exists('woocommerce_content')) { call_user_func('woocommerce_content'); } else { echo '<p>'.esc_html__('WooCommerce is not active.', 'aqualuxe').'</p>'; } ?>
</div>
<?php get_footer(); ?>
