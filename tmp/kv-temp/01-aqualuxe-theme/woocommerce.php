<?php
/**
 * WooCommerce template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<div class="container">
    <main id="main" class="site-main woocommerce-main" role="main">
        
        <?php aqualuxe_breadcrumbs(); ?>
        
        <?php woocommerce_content(); ?>
        
    </main>
</div>

<?php get_footer(); ?>
