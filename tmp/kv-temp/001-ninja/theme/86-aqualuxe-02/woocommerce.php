<?php
defined('ABSPATH') || exit;

if (function_exists('get_header')) { call_user_func('get_header'); }
?>

<main id="primary" class="site-main container mx-auto px-4 py-8">
  <?php
  if (function_exists('woocommerce_content')) {
    call_user_func('woocommerce_content');
  } else {
    $msg = function_exists('esc_html__') ? call_user_func('esc_html__', 'WooCommerce is not active.', 'aqualuxe') : 'WooCommerce is not active.';
    echo '<p class="text-slate-600">' . $msg . '</p>';
  }
  ?>
</main>

<?php if (function_exists('get_footer')) { call_user_func('get_footer'); }
