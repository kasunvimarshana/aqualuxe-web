<?php
/**
 * WooCommerce Template
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<div class="site-content woocommerce-page">
    <div class="container">
        
        <main id="main" class="site-main" role="main">
            
            <?php woocommerce_content(); ?>
            
        </main>
        
        <?php
        /**
         * Hook: aqualuxe_woocommerce_sidebar
         * 
         * @hooked aqualuxe_woocommerce_get_sidebar - 10
         */
        do_action('aqualuxe_woocommerce_sidebar');
        ?>
        
    </div>
</div>

<?php get_footer(); ?>
