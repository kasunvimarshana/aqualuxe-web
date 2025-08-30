<?php
/**
 * Single Product Template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header(); ?>

<div class="container">
    <main id="main" class="site-main single-product-main" role="main">
        
        <?php aqualuxe_breadcrumbs(); ?>
        
        <?php while (have_posts()) : the_post(); ?>
            
            <div class="single-product-wrapper">
                <?php woocommerce_content(); ?>
            </div>
            
        <?php endwhile; ?>
        
    </main>
</div>

<?php get_footer(); ?>
