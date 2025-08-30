<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop'); ?>

<main id="primary" class="site-main">

  <?php
  if (woocommerce_product_loop()) {
    
    // Display product archive filters
    do_action('woocommerce_before_shop_loop');
    
    // Display products
    woocommerce_product_loop_start();
    
    if (wc_get_loop_prop('total')) {
      while (have_posts()) {
        the_post();
        
        // Load product content template
        wc_get_template_part('content', 'product');
      }
    }
    
    woocommerce_product_loop_end();
    
    // Display pagination
    do_action('woocommerce_after_shop_loop');
    
  } else {
    // Display when no products are found
    do_action('woocommerce_no_products_found');
  }
  ?>

</main><!-- #main -->

<?php
get_footer('shop');