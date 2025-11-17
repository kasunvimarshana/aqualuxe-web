<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product;

// Get price display settings from theme customizer
$show_regular_price = get_theme_mod( 'aqualuxe_product_regular_price', true );
$show_sale_price = get_theme_mod( 'aqualuxe_product_sale_price', true );
$show_discount_percentage = get_theme_mod( 'aqualuxe_product_discount_percentage', true );
$price_style = get_theme_mod( 'aqualuxe_product_price_style', 'default' );
?>

<?php if ( $price = $product->get_price_html() ) : ?>
    <div class="aqualuxe-price-wrapper aqualuxe-price-style-<?php echo esc_attr( $price_style ); ?>">
        <?php if ( $product->is_on_sale() && $show_discount_percentage ) : ?>
            <?php
            $regular_price = (float) $product->get_regular_price();
            $sale_price = (float) $product->get_sale_price();
            
            if ( $regular_price > 0 ) {
                $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                echo '<span class="aqualuxe-discount-badge">-' . esc_html( $percentage ) . '%</span>';
            }
            ?>
        <?php endif; ?>
        
        <span class="price"><?php echo wp_kses_post( $price ); ?></span>
        
        <?php if ( $product->is_on_sale() && $product->get_regular_price() && $show_regular_price && $show_sale_price ) : ?>
            <div class="aqualuxe-price-details">
                <?php if ( $show_regular_price ) : ?>
                    <span class="aqualuxe-regular-price"><?php echo wp_kses_post( wc_price( $product->get_regular_price() ) ); ?></span>
                <?php endif; ?>
                
                <?php if ( $show_sale_price ) : ?>
                    <span class="aqualuxe-sale-price"><?php echo wp_kses_post( wc_price( $product->get_sale_price() ) ); ?></span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>