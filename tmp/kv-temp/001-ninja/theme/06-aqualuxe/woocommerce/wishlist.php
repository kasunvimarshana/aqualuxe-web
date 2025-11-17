<?php
/**
 * Wishlist template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/wishlist.php.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Get wishlist items.
$wishlist_items = array();

if ( is_user_logged_in() ) {
    $user_id = get_current_user_id();
    $wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );
    
    if ( is_array( $wishlist ) ) {
        $wishlist_items = $wishlist;
    }
} else if ( isset( $_COOKIE['aqualuxe_wishlist'] ) ) {
    $wishlist = json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true );
    
    if ( is_array( $wishlist ) ) {
        $wishlist_items = $wishlist;
    }
}

// Get wishlist products.
$wishlist_products = array();

if ( ! empty( $wishlist_items ) ) {
    foreach ( $wishlist_items as $product_id ) {
        $product = wc_get_product( $product_id );
        
        if ( $product ) {
            $wishlist_products[] = $product;
        }
    }
}

// Get page title.
$page_title = get_theme_mod( 'aqualuxe_wishlist_title', __( 'My Wishlist', 'aqualuxe' ) );
?>

<div class="aqualuxe-wishlist">
    <h1 class="aqualuxe-wishlist-title"><?php echo esc_html( $page_title ); ?></h1>
    
    <?php if ( ! empty( $wishlist_products ) ) : ?>
        <div class="aqualuxe-wishlist-products">
            <table class="aqualuxe-wishlist-table">
                <thead>
                    <tr>
                        <th class="product-remove">&nbsp;</th>
                        <th class="product-thumbnail">&nbsp;</th>
                        <th class="product-name"><?php esc_html_e( 'Product', 'aqualuxe' ); ?></th>
                        <th class="product-price"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></th>
                        <th class="product-stock"><?php esc_html_e( 'Stock', 'aqualuxe' ); ?></th>
                        <th class="product-actions">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $wishlist_products as $product ) : ?>
                        <tr>
                            <td class="product-remove">
                                <a href="#" class="aqualuxe-wishlist-remove" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">&times;</a>
                            </td>
                            <td class="product-thumbnail">
                                <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                                    <?php echo $product->get_image( 'woocommerce_thumbnail' ); ?>
                                </a>
                            </td>
                            <td class="product-name">
                                <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
                                    <?php echo esc_html( $product->get_name() ); ?>
                                </a>
                            </td>
                            <td class="product-price">
                                <?php echo $product->get_price_html(); ?>
                            </td>
                            <td class="product-stock">
                                <?php
                                if ( $product->is_in_stock() ) {
                                    echo '<span class="in-stock">' . esc_html__( 'In Stock', 'aqualuxe' ) . '</span>';
                                } else {
                                    echo '<span class="out-of-stock">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
                                }
                                ?>
                            </td>
                            <td class="product-actions">
                                <?php
                                if ( $product->is_in_stock() ) {
                                    echo '<a href="' . esc_url( $product->add_to_cart_url() ) . '" class="button add_to_cart_button">' . esc_html__( 'Add to Cart', 'aqualuxe' ) . '</a>';
                                } else {
                                    echo '<a href="' . esc_url( $product->get_permalink() ) . '" class="button">' . esc_html__( 'Read More', 'aqualuxe' ) . '</a>';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="aqualuxe-wishlist-actions">
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button continue-shopping"><?php esc_html_e( 'Continue Shopping', 'aqualuxe' ); ?></a>
            <a href="#" class="button clear-wishlist"><?php esc_html_e( 'Clear Wishlist', 'aqualuxe' ); ?></a>
        </div>
    <?php else : ?>
        <div class="aqualuxe-wishlist-empty">
            <p><?php esc_html_e( 'Your wishlist is empty.', 'aqualuxe' ); ?></p>
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button"><?php esc_html_e( 'Go to Shop', 'aqualuxe' ); ?></a>
        </div>
    <?php endif; ?>
</div>

<script>
    (function($) {
        'use strict';
        
        // Remove item from wishlist
        $('.aqualuxe-wishlist-remove').on('click', function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var productId = $this.data('product-id');
            var $row = $this.closest('tr');
            
            $row.addClass('removing');
            
            $.ajax({
                url: aqualuxeWooCommerce.ajaxUrl,
                data: {
                    action: 'aqualuxe_wishlist_remove',
                    product_id: productId,
                    nonce: aqualuxeWooCommerce.nonce
                },
                type: 'POST',
                success: function(response) {
                    if (response.success) {
                        $row.fadeOut(300, function() {
                            $row.remove();
                            
                            // Check if wishlist is empty
                            if ($('.aqualuxe-wishlist-table tbody tr').length === 0) {
                                $('.aqualuxe-wishlist-products, .aqualuxe-wishlist-actions').remove();
                                $('.aqualuxe-wishlist').append('<div class="aqualuxe-wishlist-empty"><p>' + aqualuxeWooCommerce.i18n.emptyWishlist + '</p><a href="' + aqualuxeWooCommerce.shopUrl + '" class="button">' + aqualuxeWooCommerce.i18n.goToShop + '</a></div>');
                            }
                        });
                    }
                }
            });
        });
        
        // Clear wishlist
        $('.clear-wishlist').on('click', function(e) {
            e.preventDefault();
            
            if (confirm(aqualuxeWooCommerce.i18n.confirmClearWishlist)) {
                $.ajax({
                    url: aqualuxeWooCommerce.ajaxUrl,
                    data: {
                        action: 'aqualuxe_wishlist_clear',
                        nonce: aqualuxeWooCommerce.nonce
                    },
                    type: 'POST',
                    success: function(response) {
                        if (response.success) {
                            $('.aqualuxe-wishlist-products, .aqualuxe-wishlist-actions').remove();
                            $('.aqualuxe-wishlist').append('<div class="aqualuxe-wishlist-empty"><p>' + aqualuxeWooCommerce.i18n.emptyWishlist + '</p><a href="' + aqualuxeWooCommerce.shopUrl + '" class="button">' + aqualuxeWooCommerce.i18n.goToShop + '</a></div>');
                        }
                    }
                });
            }
        });
    })(jQuery);
</script>