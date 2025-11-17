<?php
/**
 * Single Product Template
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<div class="site-content single-product-page">
    <div class="container">
        
        <main id="main" class="site-main" role="main">
            
            <?php while (have_posts()) : the_post(); ?>
                
                <div class="product-wrapper">
                    <?php wc_get_template_part('content', 'single-product'); ?>
                </div>
                
            <?php endwhile; ?>
            
        </main>
        
    </div>
</div>

<?php get_footer(); ?>
