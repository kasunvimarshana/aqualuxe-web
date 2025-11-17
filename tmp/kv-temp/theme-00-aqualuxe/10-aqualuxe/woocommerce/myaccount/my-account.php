<?php
/**
 * My Account Page
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_account_navigation'); ?>

<div class="woocommerce-MyAccount-content">
  <?php
  do_action('woocommerce_account_content');
  ?>
</div>