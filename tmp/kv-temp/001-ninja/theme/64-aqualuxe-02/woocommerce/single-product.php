<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 1.6.4
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

// Custom hook before product content
do_action( 'aqualuxe_before_product_content' );
?>

<div class="product-content-area">
    <div class="product-main">
        <?php while ( have_posts() ) : ?>
            <?php the_post(); ?>

            <?php wc_get_template_part( 'content', 'single-product' ); ?>

        <?php endwhile; // end of the loop. ?>
    </div>

    <?php if ( is_active_sidebar( 'product-sidebar' ) ) : ?>
        <div class="product-sidebar">
            <?php
            /**
             * Hook: aqualuxe_product_sidebar.
             *
             * @hooked dynamic_sidebar - 10
             */
            do_action( 'aqualuxe_product_sidebar' );
            ?>
        </div>
    <?php endif; ?>
</div>

<?php
// Custom hook after product content
do_action( 'aqualuxe_after_product_content' );

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