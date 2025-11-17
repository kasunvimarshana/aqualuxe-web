<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @since   1.0.0
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
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
    <div class="product-main">
        <div class="product-gallery-wrapper">
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

        <div class="product-summary-wrapper">
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
                 * @hooked aqualuxe_display_custom_fields - 25 (custom function)
                 */
                do_action( 'woocommerce_single_product_summary' );
                ?>
                
                <div class="product-actions-extra">
                    <?php
                    // Wishlist Button
                    echo '<a href="#" class="add-to-wishlist" data-product-id="' . esc_attr( $product->get_id() ) . '"><i class="far fa-heart"></i> ' . esc_html__( 'Add to Wishlist', 'aqualuxe' ) . '</a>';
                    
                    // Compare Button
                    echo '<a href="#" class="add-to-compare" data-product-id="' . esc_attr( $product->get_id() ) . '"><i class="fas fa-exchange-alt"></i> ' . esc_html__( 'Add to Compare', 'aqualuxe' ) . '</a>';
                    
                    // Share Button
                    echo '<a href="#" class="share-product"><i class="fas fa-share-alt"></i> ' . esc_html__( 'Share', 'aqualuxe' ) . '</a>';
                    ?>
                    
                    <div class="social-share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( get_permalink() ); ?>" target="_blank" class="facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo esc_url( get_permalink() ); ?>&text=<?php echo esc_attr( get_the_title() ); ?>" target="_blank" class="twitter"><i class="fab fa-twitter"></i></a>
                        <a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url( get_permalink() ); ?>&media=<?php echo esc_url( get_the_post_thumbnail_url( $product->get_id(), 'full' ) ); ?>&description=<?php echo esc_attr( get_the_title() ); ?>" target="_blank" class="pinterest"><i class="fab fa-pinterest-p"></i></a>
                        <a href="mailto:?subject=<?php echo esc_attr( get_the_title() ); ?>&body=<?php echo esc_url( get_permalink() ); ?>" class="email"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
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
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>