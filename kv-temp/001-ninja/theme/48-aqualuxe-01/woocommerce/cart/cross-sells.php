<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( $cross_sells ) : ?>

    <div class="cross-sells mt-12">
        <div class="section-header mb-8">
            <h2 class="text-2xl md:text-3xl font-serif font-bold mb-2"><?php esc_html_e( 'You may also like', 'aqualuxe' ); ?></h2>
            <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Products that complement your cart items', 'aqualuxe' ); ?></p>
        </div>

        <?php woocommerce_product_loop_start(); ?>

            <?php foreach ( $cross_sells as $cross_sell ) : ?>

                <?php
                    $post_object = get_post( $cross_sell->get_id() );

                    setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

                    wc_get_template_part( 'content', 'product' );
                ?>

            <?php endforeach; ?>

        <?php woocommerce_product_loop_end(); ?>

    </div>
    <?php
endif;

wp_reset_postdata();