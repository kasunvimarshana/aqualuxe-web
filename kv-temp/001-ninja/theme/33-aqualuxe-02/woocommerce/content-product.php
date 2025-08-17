<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

// Get customizer settings
$show_rating = get_theme_mod( 'aqualuxe_product_rating', true );
$show_price = get_theme_mod( 'aqualuxe_product_price', true );
$show_add_to_cart = get_theme_mod( 'aqualuxe_product_add_to_cart', true );
$show_quick_view = get_theme_mod( 'aqualuxe_product_quick_view', true );
$show_wishlist = get_theme_mod( 'aqualuxe_product_wishlist', true );
$product_hover_style = get_theme_mod( 'aqualuxe_product_hover_style', 'zoom' );

// Get product view mode (grid or list)
$view_mode = isset( $_COOKIE['aqualuxe_shop_view'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_shop_view'] ) ) : 'grid';
$product_classes = array(
    'aqualuxe-product',
    'aqualuxe-product-' . $view_mode,
    'aqualuxe-product-hover-' . $product_hover_style,
);

?>
<li <?php wc_product_class( $product_classes, $product ); ?>>
    <div class="aqualuxe-product-inner">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         *
         * @hooked woocommerce_template_loop_product_link_open - 10
         */
        do_action( 'woocommerce_before_shop_loop_item' );
        ?>

        <div class="aqualuxe-product-thumbnail">
            <?php
            /**
             * Hook: woocommerce_before_shop_loop_item_title.
             *
             * @hooked woocommerce_show_product_loop_sale_flash - 10
             * @hooked woocommerce_template_loop_product_thumbnail - 10
             */
            do_action( 'woocommerce_before_shop_loop_item_title' );
            
            // Product actions (quick view, wishlist, etc.)
            if ( $show_quick_view || $show_wishlist ) :
            ?>
            <div class="aqualuxe-product-actions">
                <?php if ( $show_quick_view ) : ?>
                <div class="aqualuxe-quick-view">
                    <a href="#" class="aqualuxe-quick-view-btn" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php esc_attr_e( 'Quick view', 'aqualuxe' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <span><?php esc_html_e( 'Quick View', 'aqualuxe' ); ?></span>
                    </a>
                </div>
                <?php endif; ?>
                
                <?php if ( $show_wishlist ) : ?>
                <div class="aqualuxe-wishlist">
                    <a href="#" class="aqualuxe-wishlist-btn" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="<?php esc_attr_e( 'Add to wishlist', 'aqualuxe' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
                        <span><?php esc_html_e( 'Wishlist', 'aqualuxe' ); ?></span>
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="aqualuxe-product-content">
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
            if ( $show_rating ) {
                woocommerce_template_loop_rating();
            }
            
            if ( $show_price ) {
                woocommerce_template_loop_price();
            }
            
            // Product excerpt in list view
            if ( 'list' === $view_mode && $product->get_short_description() ) :
            ?>
            <div class="aqualuxe-product-excerpt">
                <?php echo wp_kses_post( $product->get_short_description() ); ?>
            </div>
            <?php endif; ?>
            
            <?php if ( $show_add_to_cart ) : ?>
            <div class="aqualuxe-product-add-to-cart">
                <?php woocommerce_template_loop_add_to_cart(); ?>
            </div>
            <?php endif; ?>
            
            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item.
             *
             * @hooked woocommerce_template_loop_product_link_close - 5
             * @hooked woocommerce_template_loop_add_to_cart - 10
             */
            do_action( 'woocommerce_after_shop_loop_item' );
            ?>
        </div>
    </div>
</li>