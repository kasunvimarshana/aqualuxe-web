<?php
/**
 * The template for displaying product content within loops
 *
 * @package AquaLuxe
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>
<li <?php wc_product_class( 'bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-shadow duration-300 hover:shadow-lg', $product ); ?>>
    <div class="product-inner p-4">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action( 'woocommerce_before_shop_loop_item' );
        ?>

        <div class="product-thumbnail relative overflow-hidden group mb-4">
            <?php
            /**
             * Hook: woocommerce_before_shop_loop_item_title.
             *
             * @hooked woocommerce_show_product_loop_sale_flash - 10
             * @hooked woocommerce_template_loop_product_thumbnail - 10
             */
            do_action( 'woocommerce_before_shop_loop_item_title' );
            ?>
            
            <div class="product-actions absolute bottom-0 left-0 w-full p-4 bg-gradient-to-t from-black to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex space-x-2">
                <?php if ( get_theme_mod( 'aqualuxe_quick_view', true ) ) : ?>
                    <button class="quick-view-button bg-white hover:bg-gray-100 text-gray-800 text-xs font-bold py-2 px-3 rounded" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
                        <?php esc_html_e( 'Quick View', 'aqualuxe' ); ?>
                    </button>
                <?php endif; ?>
                
                <?php if ( get_theme_mod( 'aqualuxe_wishlist', true ) ) : ?>
                    <button class="add-to-wishlist bg-white hover:bg-gray-100 text-gray-800 text-xs font-bold py-2 px-3 rounded" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                    </button>
                <?php endif; ?>
            </div>
        </div>

        <div class="product-details">
            <h2 class="woocommerce-loop-product__title text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </h2>

            <?php
            /**
             * Hook: woocommerce_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_product_title - 10
             */
            do_action( 'woocommerce_shop_loop_item_title' );

            /**
             * Hook: woocommerce_after_shop_loop_item_title.
             *
             * @hooked woocommerce_template_loop_rating - 5
             * @hooked woocommerce_template_loop_price - 10
             */
            do_action( 'woocommerce_after_shop_loop_item_title' );
            ?>

            <div class="product-meta flex items-center justify-between mt-3">
                <?php
                /**
                 * Hook: woocommerce_after_shop_loop_item.
                 *
                 * @hooked woocommerce_template_loop_product_link_close - 5
                 * @hooked woocommerce_template_loop_add_to_cart - 10
                 */
                do_action( 'woocommerce_after_shop_loop_item' );
                ?>
                
                <?php if ( $product->get_average_rating() ) : ?>
                    <div class="product-rating flex items-center">
                        <div class="star-rating" role="img" aria-label="<?php echo sprintf( esc_attr__( 'Rated %s out of 5', 'aqualuxe' ), $product->get_average_rating() ); ?>">
                            <?php echo wc_get_star_rating_html( $product->get_average_rating() ); ?>
                        </div>
                        <span class="rating-count text-xs text-gray-500 dark:text-gray-400 ml-1">
                            (<?php echo esc_html( $product->get_review_count() ); ?>)
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</li>