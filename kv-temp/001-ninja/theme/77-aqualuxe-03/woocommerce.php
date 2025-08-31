<?php
/**
 * WooCommerce fallback wrapper.
 */
if (!defined('ABSPATH')) exit;
get_header('shop');
  if (function_exists('woocommerce_content')) {
    call_user_func('woocommerce_content');
  } else {
    echo '<div class="container mx-auto px-4 py-10"><p>' . esc_html__('WooCommerce is not active.', 'aqualuxe') . '</p></div>';
  }
get_footer('shop');
