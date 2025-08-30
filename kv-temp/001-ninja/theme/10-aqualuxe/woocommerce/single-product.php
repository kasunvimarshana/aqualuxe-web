<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     AquaLuxe
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action( 'woocommerce_before_main_content' );

?>
<div class="product-header-wrapper">
    <?php while ( have_posts() ) : ?>
        <?php the_post(); ?>
        <h1 class="product-title-header"><?php the_title(); ?></h1>
        <?php 
        // Display breadcrumb navigation
        woocommerce_breadcrumb();
        ?>
    <?php endwhile; ?>
</div>
<?php

while ( have_posts() ) :
	the_post();

	/**
	 * Hook: woocommerce_single_product_summary.
	 */
	wc_get_template_part( 'content', 'single-product' );

endwhile; // end of the loop.

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );