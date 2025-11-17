<?php
/**
 * Quick View Product Template
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;

if ( ! $product ) {
    return;
}
?>

<div class="quick-view-product" id="quick-view-<?php echo esc_attr( $product->get_id() ); ?>">
    <div class="quick-view-content grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Product Image -->
        <div class="quick-view-image">
            <?php
            if ( has_post_thumbnail( $product->get_id() ) ) {
                echo get_the_post_thumbnail( $product->get_id(), 'woocommerce_single', array( 'class' => 'w-full h-auto rounded-lg' ) );
            } else {
                echo wc_placeholder_img( 'woocommerce_single', 'w-full h-auto rounded-lg' );
            }
            ?>
        </div>

        <!-- Product Details -->
        <div class="quick-view-details">
            <h2 class="product-title text-2xl font-bold text-gray-900 dark:text-white mb-4">
                <?php echo esc_html( $product->get_name() ); ?>
            </h2>

            <div class="price text-2xl font-semibold text-primary-600 dark:text-primary-400 mb-4">
                <?php echo $product->get_price_html(); ?>
            </div>

            <?php if ( $product->get_short_description() ) : ?>
                <div class="short-description text-gray-600 dark:text-gray-400 mb-6">
                    <?php echo wp_kses_post( $product->get_short_description() ); ?>
                </div>
            <?php endif; ?>

            <?php if ( $product->is_type( 'simple' ) ) : ?>
                <form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
                    <?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

                    <?php if ( ! $product->is_sold_individually() ) : ?>
                        <div class="quantity-controls flex items-center space-x-4 mb-4">
                            <label for="quantity" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                <?php esc_html_e( 'Quantity', 'aqualuxe' ); ?>
                            </label>
                            <?php
                            woocommerce_quantity_input( array(
                                'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
                                'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
                                'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(),
                            ) );
                            ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt ajax-add-to-cart bg-primary-600 text-white px-6 py-3 rounded-lg hover:bg-primary-700 transition-colors duration-200 w-full">
                        <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
                    </button>

                    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
                </form>
            <?php endif; ?>

            <div class="product-meta mt-6 text-sm text-gray-600 dark:text-gray-400">
                <?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
                    <span class="sku_wrapper block mb-2">
                        <strong><?php esc_html_e( 'SKU:', 'aqualuxe' ); ?></strong>
                        <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'aqualuxe' ); ?></span>
                    </span>
                <?php endif; ?>

                <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in block mb-2"><strong>' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'aqualuxe' ) . '</strong> ', '</span>' ); ?>

                <?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as block"><strong>' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'aqualuxe' ) . '</strong> ', '</span>' ); ?>
            </div>

            <div class="view-full-details mt-6">
                <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="view-details-link text-primary-600 hover:text-primary-700 font-medium">
                    <?php esc_html_e( 'View Full Details', 'aqualuxe' ); ?>
                </a>
            </div>
        </div>
    </div>
</div>