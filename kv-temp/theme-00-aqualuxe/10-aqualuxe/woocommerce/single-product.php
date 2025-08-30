<?php
/**
 * The Template for displaying all single products
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop'); ?>

<main id="primary" class="site-main">

  <?php
  while (have_posts()) :
    the_post();
    
    // Load the single product content
    wc_get_template_part('content', 'single-product');
    
  endwhile; // End of the loop.
  ?>

</main><!-- #main -->

<?php
get_footer('shop');