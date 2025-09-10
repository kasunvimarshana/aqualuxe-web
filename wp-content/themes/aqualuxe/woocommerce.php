<?php
/**
 * The base template for all WooCommerce pages.
 *
 * This template serves as the foundation for all WooCommerce-related pages,
 * providing a consistent layout structure. It includes the main content area
 * and a sidebar, wrapped in a responsive grid system.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <div class="container mx-auto py-12 px-4">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <div class="lg:col-span-3">
                    <?php if ( woocommerce_product_loop() ) : ?>
                        <?php
                        /**
                         * Hook: woocommerce_before_shop_loop.
                         *
                         * @hooked woocommerce_output_all_notices - 10
                         * @hooked woocommerce_result_count - 20
                         * @hooked woocommerce_catalog_ordering - 30
                         */
                        do_action( 'woocommerce_before_shop_loop' );
                        ?>

                        <?php
                        woocommerce_product_loop_start();

                        if ( wc_get_loop_prop( 'total' ) ) {
                            while ( have_posts() ) {
                                the_post();
                                wc_get_template_part( 'content', 'product' );
                            }
                        }

                        woocommerce_product_loop_end();
                        ?>

                        <?php
                        /**
                         * Hook: woocommerce_after_shop_loop.
                         *
                         * @hooked woocommerce_pagination - 10
                         */
                        do_action( 'woocommerce_after_shop_loop' );
                        ?>

                    <?php else : ?>
                        <?php
                        /**
                         * Hook: woocommerce_no_products_found.
                         *
                         * @hooked wc_no_products_found - 10
                         */
                        do_action( 'woocommerce_no_products_found' );
                        ?>
                    <?php endif; ?>
                </div>

                <div id="secondary" class="widget-area lg:col-span-1" role="complementary">
                    <?php
                    /**
                     * Hook: woocommerce_sidebar.
                     *
                     * @hooked woocommerce_get_sidebar - 10
                     */
                    do_action( 'woocommerce_sidebar' );
                    ?>
                </div>

            </div>
        </div>
    </main>
</div>

<?php
get_footer( 'shop' );
