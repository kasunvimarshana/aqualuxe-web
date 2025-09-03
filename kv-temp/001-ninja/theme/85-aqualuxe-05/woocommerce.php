<?php
/**
 * WooCommerce wrapper template
 * Ensures consistent header/footer and layout for Woo pages.
 *
 * @package Aqualuxe
 */

if (!defined('ABSPATH')) { exit; }

get_header();
?>
<main id="primary" class="site-main container mx-auto px-4 py-8">
  <?php if (function_exists('woocommerce_content')) { woocommerce_content(); } else { echo '<p>WooCommerce is not active.</p>'; } ?>
</main>
<?php get_footer();
