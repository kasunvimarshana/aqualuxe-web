<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}

// Get product layout from theme customizer
$product_layout = get_theme_mod( 'aqualuxe_product_layout', 'standard' );
$gallery_style = get_theme_mod( 'aqualuxe_product_gallery_style', 'horizontal' );
$sticky_summary = get_theme_mod( 'aqualuxe_product_sticky_summary', true );
$show_related = get_theme_mod( 'aqualuxe_product_related', true );
$show_upsells = get_theme_mod( 'aqualuxe_product_upsells', true );
$show_recently_viewed = get_theme_mod( 'aqualuxe_product_recently_viewed', true );

$classes = array(
    'product',
    'aqualuxe-product-layout-' . $product_layout,
    'aqualuxe-product-gallery-' . $gallery_style,
);

if ( $sticky_summary ) {
    $classes[] = 'aqualuxe-product-sticky-summary';
}

?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( $classes, $product ); ?>>

    <div class="aqualuxe-product-container">
        <div class="aqualuxe-product-gallery-column">
            <?php
            /**
             * Hook: woocommerce_before_single_product_summary.
             *
             * @hooked woocommerce_show_product_sale_flash - 10
             * @hooked woocommerce_show_product_images - 20
             */
            do_action( 'woocommerce_before_single_product_summary' );
            ?>
        </div>

        <div class="aqualuxe-product-summary-column">
            <div class="summary entry-summary">
                <?php
                /**
                 * Hook: woocommerce_single_product_summary.
                 *
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_rating - 10
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked WC_Structured_Data::generate_product_data() - 60
                 */
                do_action( 'woocommerce_single_product_summary' );
                ?>

                <?php
                /**
                 * Hook: aqualuxe_after_single_product_summary.
                 *
                 * @hooked aqualuxe_product_size_guide - 10
                 * @hooked aqualuxe_product_delivery_info - 20
                 * @hooked aqualuxe_product_social_share - 30
                 */
                do_action( 'aqualuxe_after_single_product_summary' );
                ?>
            </div>
        </div>
    </div>

    <?php
    /**
     * Hook: woocommerce_after_single_product_summary.
     *
     * @hooked woocommerce_output_product_data_tabs - 10
     * @hooked woocommerce_upsell_display - 15
     * @hooked woocommerce_output_related_products - 20
     */
    do_action( 'woocommerce_after_single_product_summary' );
    ?>

    <?php if ( $show_recently_viewed ) : ?>
    <section class="aqualuxe-recently-viewed-products">
        <h2><?php esc_html_e( 'Recently Viewed', 'aqualuxe' ); ?></h2>
        <?php
        // Display recently viewed products
        if ( function_exists( 'aqualuxe_recently_viewed_products' ) ) {
            aqualuxe_recently_viewed_products();
        }
        ?>
    </section>
    <?php endif; ?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>