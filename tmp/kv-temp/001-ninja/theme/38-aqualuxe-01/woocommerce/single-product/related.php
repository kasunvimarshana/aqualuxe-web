<?php
/**
 * Related Products
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( $related_products ) : ?>

    <section class="related products">
        <div class="section-header">
            <h2><?php esc_html_e( 'Related products', 'aqualuxe' ); ?></h2>
            <?php if ( count( $related_products ) > 4 ) : ?>
                <div class="slider-controls">
                    <button class="slider-arrow slider-arrow-prev" aria-label="<?php esc_attr_e( 'Previous related products', 'aqualuxe' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                    </button>
                    <button class="slider-arrow slider-arrow-next" aria-label="<?php esc_attr_e( 'Next related products', 'aqualuxe' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-slider related-products-slider">
            <div class="slides-container">
                <?php woocommerce_product_loop_start(); ?>

                    <?php foreach ( $related_products as $related_product ) : ?>

                        <?php
                        $post_object = get_post( $related_product->get_id() );

                        setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

                        wc_get_template_part( 'content', 'product' );
                        ?>

                    <?php endforeach; ?>

                <?php woocommerce_product_loop_end(); ?>
            </div>
        </div>
    </section>

<?php endif;

wp_reset_postdata();