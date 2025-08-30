<?php
/**
 * The Template for displaying all single products
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

/**
 * Hook: aqualuxe_before_single_product.
 */
do_action( 'aqualuxe_before_single_product' );

?>
<div class="single-product-container">
    <?php while ( have_posts() ) : ?>
        <?php the_post(); ?>
        
        <?php wc_get_template_part( 'content', 'single-product' ); ?>
        
    <?php endwhile; // end of the loop. ?>
</div>

<?php
/**
 * Hook: aqualuxe_after_single_product.
 */
do_action( 'aqualuxe_after_single_product' );

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

/**
 * Hook: aqualuxe_single_product_recently_viewed.
 */
do_action( 'aqualuxe_single_product_recently_viewed' );

/**
 * Hook: aqualuxe_single_product_featured_products.
 */
do_action( 'aqualuxe_single_product_featured_products' );

get_footer( 'shop' );