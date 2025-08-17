<?php
/**
 * Single Product Price
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

?>
<div class="product-price-wrapper">
    <p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>">
        <?php echo $product->get_price_html(); ?>
    </p>
    
    <?php if ( $product->is_on_sale() ) : ?>
        <?php
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        
        if ( $regular_price && $sale_price ) {
            $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
            
            if ( $percentage > 0 ) {
                echo '<span class="discount-badge">-' . esc_html( $percentage ) . '%</span>';
            }
            
            // Calculate savings
            $savings = $regular_price - $sale_price;
            
            if ( $savings > 0 ) {
                echo '<div class="price-savings">';
                echo '<span class="savings-text">' . esc_html__( 'You save:', 'aqualuxe' ) . '</span> ';
                echo '<span class="savings-amount">' . wc_price( $savings ) . '</span>';
                echo '</div>';
            }
        }
        ?>
    <?php endif; ?>
    
    <?php
    // Show tax information if needed
    if ( wc_tax_enabled() && ! $product->is_taxable() ) {
        echo '<div class="tax-info">' . esc_html__( 'Tax exempt', 'aqualuxe' ) . '</div>';
    } elseif ( wc_tax_enabled() && $product->is_taxable() ) {
        $tax_suffix = apply_filters( 'aqualuxe_product_tax_suffix', __( 'inc. tax', 'aqualuxe' ) );
        echo '<div class="tax-info">' . esc_html( $tax_suffix ) . '</div>';
    }
    
    // Show availability
    echo wc_get_stock_html( $product );
    
    // Show shipping information if needed
    if ( $product->needs_shipping() ) {
        $shipping_text = apply_filters( 'aqualuxe_product_shipping_text', __( 'Shipping calculated at checkout', 'aqualuxe' ) );
        echo '<div class="shipping-info">' . esc_html( $shipping_text ) . '</div>';
    }
    ?>
</div>