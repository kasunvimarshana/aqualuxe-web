<?php
/**
 * Wishlist
 *
 * Shows the wishlist in the My Account section.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/wishlist.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

// Get wishlist and products from template args
$wishlist = isset( $wishlist ) ? $wishlist : [];
$products = isset( $products ) ? $products : [];
?>

<div class="woocommerce-wishlist">
    <h2><?php esc_html_e( 'My Wishlist', 'aqualuxe' ); ?></h2>
    
    <?php if ( ! empty( $products ) ) : ?>
        <table class="shop_table shop_table_responsive wishlist_table">
            <thead>
                <tr>
                    <th class="product-remove"><span class="screen-reader-text"><?php esc_html_e( 'Remove item', 'aqualuxe' ); ?></span></th>
                    <th class="product-thumbnail"><span class="screen-reader-text"><?php esc_html_e( 'Thumbnail', 'aqualuxe' ); ?></span></th>
                    <th class="product-name"><?php esc_html_e( 'Product', 'aqualuxe' ); ?></th>
                    <th class="product-price"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></th>
                    <th class="product-stock-status"><?php esc_html_e( 'Stock Status', 'aqualuxe' ); ?></th>
                    <th class="product-actions"><?php esc_html_e( 'Actions', 'aqualuxe' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $products as $product ) : ?>
                    <tr class="wishlist-item">
                        <td class="product-remove" data-title="<?php esc_attr_e( 'Remove', 'aqualuxe' ); ?>">
                            <button class="wishlist-button in-wishlist" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
                                <?php echo aqualuxe_get_icon( 'close' ); ?>
                            </button>
                        </td>
                        <td class="product-thumbnail" data-title="<?php esc_attr_e( 'Thumbnail', 'aqualuxe' ); ?>">
                            <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                                <?php echo $product->get_image( 'thumbnail' ); ?>
                            </a>
                        </td>
                        <td class="product-name" data-title="<?php esc_attr_e( 'Product', 'aqualuxe' ); ?>">
                            <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                                <?php echo esc_html( $product->get_name() ); ?>
                            </a>
                        </td>
                        <td class="product-price" data-title="<?php esc_attr_e( 'Price', 'aqualuxe' ); ?>">
                            <?php echo $product->get_price_html(); ?>
                        </td>
                        <td class="product-stock-status" data-title="<?php esc_attr_e( 'Stock Status', 'aqualuxe' ); ?>">
                            <?php if ( $product->is_in_stock() ) : ?>
                                <span class="in-stock"><?php esc_html_e( 'In Stock', 'aqualuxe' ); ?></span>
                            <?php else : ?>
                                <span class="out-of-stock"><?php esc_html_e( 'Out of Stock', 'aqualuxe' ); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="product-actions" data-title="<?php esc_attr_e( 'Actions', 'aqualuxe' ); ?>">
                            <?php
                            if ( $product->is_in_stock() ) {
                                echo apply_filters(
                                    'woocommerce_loop_add_to_cart_link',
                                    sprintf(
                                        '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                        esc_url( $product->add_to_cart_url() ),
                                        esc_attr( 1 ),
                                        esc_attr( 'button add_to_cart_button' ),
                                        wc_implode_html_attributes( array( 'data-product_id' => $product->get_id() ) ),
                                        esc_html( $product->add_to_cart_text() )
                                    ),
                                    $product
                                );
                            } else {
                                echo '<a href="' . esc_url( $product->get_permalink() ) . '" class="button">' . esc_html__( 'Read More', 'aqualuxe' ) . '</a>';
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="wishlist-actions">
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button">
                <?php esc_html_e( 'Continue Shopping', 'aqualuxe' ); ?>
            </a>
            <button class="button clear-wishlist">
                <?php esc_html_e( 'Clear Wishlist', 'aqualuxe' ); ?>
            </button>
        </div>
    <?php else : ?>
        <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
            <p><?php esc_html_e( 'Your wishlist is currently empty.', 'aqualuxe' ); ?></p>
            <a class="button" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
                <?php esc_html_e( 'Browse products', 'aqualuxe' ); ?>
            </a>
        </div>
    <?php endif; ?>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Clear wishlist
        $('.clear-wishlist').on('click', function(e) {
            e.preventDefault();
            
            if (confirm('<?php esc_html_e( 'Are you sure you want to clear your wishlist?', 'aqualuxe' ); ?>')) {
                // Get all product IDs
                const productIds = [];
                $('.wishlist-item').each(function() {
                    const productId = $(this).find('.wishlist-button').data('product-id');
                    productIds.push(productId);
                });
                
                // Remove each product from wishlist
                const removePromises = productIds.map(function(productId) {
                    return new Promise(function(resolve) {
                        $.ajax({
                            url: aqualuxeWooCommerce.ajaxUrl,
                            type: 'POST',
                            data: {
                                action: 'aqualuxe_wishlist',
                                product_id: productId,
                                nonce: aqualuxeWooCommerce.nonce
                            },
                            success: function() {
                                resolve();
                            },
                            error: function() {
                                resolve();
                            }
                        });
                    });
                });
                
                // Reload page after all products are removed
                Promise.all(removePromises).then(function() {
                    window.location.reload();
                });
            }
        });
    });
</script>