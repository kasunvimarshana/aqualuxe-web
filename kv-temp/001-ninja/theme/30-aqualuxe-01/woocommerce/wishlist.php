<?php
/**
 * Wishlist template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/wishlist.php.
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

// Get wishlist products
$wishlist = isset($wishlist) ? $wishlist : array();
$products = isset($products) ? $products : array();
?>

<div class="aqualuxe-wishlist">
    <h2><?php esc_html_e('My Wishlist', 'aqualuxe'); ?></h2>
    
    <?php if (!empty($products)) : ?>
        <div class="aqualuxe-wishlist-products">
            <table class="aqualuxe-wishlist-table">
                <thead>
                    <tr>
                        <th class="product-remove">&nbsp;</th>
                        <th class="product-thumbnail">&nbsp;</th>
                        <th class="product-name"><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                        <th class="product-price"><?php esc_html_e('Price', 'aqualuxe'); ?></th>
                        <th class="product-stock"><?php esc_html_e('Stock', 'aqualuxe'); ?></th>
                        <th class="product-actions">&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) : ?>
                        <tr class="aqualuxe-wishlist-item" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                            <td class="product-remove">
                                <a href="#" class="remove-from-wishlist" data-product-id="<?php echo esc_attr($product->get_id()); ?>" aria-label="<?php esc_attr_e('Remove this product', 'aqualuxe'); ?>">×</a>
                            </td>
                            <td class="product-thumbnail">
                                <?php echo $product->get_image('thumbnail'); ?>
                            </td>
                            <td class="product-name">
                                <a href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a>
                            </td>
                            <td class="product-price">
                                <?php echo $product->get_price_html(); ?>
                            </td>
                            <td class="product-stock">
                                <?php if ($product->is_in_stock()) : ?>
                                    <span class="in-stock"><?php esc_html_e('In stock', 'aqualuxe'); ?></span>
                                <?php else : ?>
                                    <span class="out-of-stock"><?php esc_html_e('Out of stock', 'aqualuxe'); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="product-actions">
                                <?php if ($product->is_in_stock()) : ?>
                                    <?php echo apply_filters('woocommerce_loop_add_to_cart_link',
                                        sprintf('<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                            esc_url($product->add_to_cart_url()),
                                            esc_attr(1),
                                            esc_attr('button add_to_cart_button'),
                                            'data-product_id="' . esc_attr($product->get_id()) . '" data-product_sku="' . esc_attr($product->get_sku()) . '"',
                                            esc_html__('Add to cart', 'aqualuxe')
                                        ),
                                    $product); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <div class="aqualuxe-wishlist-empty">
            <p><?php esc_html_e('Your wishlist is empty.', 'aqualuxe'); ?></p>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button"><?php esc_html_e('Browse products', 'aqualuxe'); ?></a>
        </div>
    <?php endif; ?>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Remove from wishlist
        $('.remove-from-wishlist').on('click', function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var productId = $this.data('product-id');
            
            $.ajax({
                url: aqualuxeWooCommerce.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_add_to_wishlist',
                    nonce: aqualuxeWooCommerce.nonce,
                    product_id: productId
                },
                success: function(response) {
                    if (response.success) {
                        $this.closest('tr').fadeOut(300, function() {
                            $(this).remove();
                            
                            // Check if wishlist is empty
                            if ($('.aqualuxe-wishlist-item').length === 0) {
                                $('.aqualuxe-wishlist-products').replaceWith(
                                    '<div class="aqualuxe-wishlist-empty">' +
                                    '<p><?php esc_html_e('Your wishlist is empty.', 'aqualuxe'); ?></p>' +
                                    '<a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button"><?php esc_html_e('Browse products', 'aqualuxe'); ?></a>' +
                                    '</div>'
                                );
                            }
                        });
                    }
                }
            });
        });
    });
</script>

<style>
    .aqualuxe-wishlist {
        margin-bottom: 2em;
    }
    
    .aqualuxe-wishlist-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.5em;
    }
    
    .aqualuxe-wishlist-table th,
    .aqualuxe-wishlist-table td {
        padding: 1em;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
    }
    
    .aqualuxe-wishlist-table th {
        font-weight: 600;
        background-color: #f9fafb;
    }
    
    .aqualuxe-wishlist-table .product-remove {
        width: 32px;
        text-align: center;
    }
    
    .aqualuxe-wishlist-table .product-thumbnail {
        width: 80px;
    }
    
    .aqualuxe-wishlist-table .product-thumbnail img {
        width: 60px;
        height: auto;
        display: block;
    }
    
    .aqualuxe-wishlist-table .product-name {
        width: 40%;
    }
    
    .aqualuxe-wishlist-table .product-price {
        width: 15%;
    }
    
    .aqualuxe-wishlist-table .product-stock {
        width: 15%;
    }
    
    .aqualuxe-wishlist-table .product-actions {
        width: 15%;
        text-align: right;
    }
    
    .aqualuxe-wishlist-table .in-stock {
        color: #10b981;
    }
    
    .aqualuxe-wishlist-table .out-of-stock {
        color: #ef4444;
    }
    
    .aqualuxe-wishlist-empty {
        text-align: center;
        padding: 2em;
        background-color: #f9fafb;
        border-radius: 0.5em;
    }
    
    .aqualuxe-wishlist-empty p {
        margin-bottom: 1em;
    }
    
    .remove-from-wishlist {
        display: block;
        font-size: 1.5em;
        height: 1em;
        width: 1em;
        text-align: center;
        line-height: 1;
        border-radius: 100%;
        color: #6b7280;
        text-decoration: none;
        font-weight: 700;
        border: 0;
    }
    
    .remove-from-wishlist:hover {
        color: #ef4444;
        background-color: #fee2e2;
    }
    
    @media (max-width: 768px) {
        .aqualuxe-wishlist-table {
            display: block;
        }
        
        .aqualuxe-wishlist-table thead {
            display: none;
        }
        
        .aqualuxe-wishlist-table tbody {
            display: block;
        }
        
        .aqualuxe-wishlist-table tr {
            display: block;
            margin-bottom: 1.5em;
            border: 1px solid #e5e7eb;
            border-radius: 0.5em;
            overflow: hidden;
        }
        
        .aqualuxe-wishlist-table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75em 1em;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .aqualuxe-wishlist-table td:before {
            content: attr(data-title);
            font-weight: 600;
            margin-right: 1em;
        }
        
        .aqualuxe-wishlist-table td:last-child {
            border-bottom: 0;
        }
        
        .aqualuxe-wishlist-table .product-remove {
            width: 100%;
            text-align: right;
        }
        
        .aqualuxe-wishlist-table .product-thumbnail {
            width: 100%;
        }
        
        .aqualuxe-wishlist-table .product-name {
            width: 100%;
        }
        
        .aqualuxe-wishlist-table .product-price {
            width: 100%;
        }
        
        .aqualuxe-wishlist-table .product-stock {
            width: 100%;
        }
        
        .aqualuxe-wishlist-table .product-actions {
            width: 100%;
            text-align: left;
        }
    }
</style>