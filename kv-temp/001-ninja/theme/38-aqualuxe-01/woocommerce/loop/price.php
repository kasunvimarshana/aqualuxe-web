<?php
/**
 * Loop Price
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
    <div class="price-wrapper">
        <span class="price"><?php echo $price_html; ?></span>
        
        <?php if ( $product->is_on_sale() ) : ?>
            <?php
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            
            if ( $regular_price && $sale_price ) {
                $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                
                if ( $percentage > 0 ) {
                    echo '<span class="discount-badge">-' . esc_html( $percentage ) . '%</span>';
                }
            }
            ?>
        <?php endif; ?>
    </div>
<?php endif; ?>