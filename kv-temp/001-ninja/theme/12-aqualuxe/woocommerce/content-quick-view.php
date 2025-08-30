<?php
/**
 * Quick view content.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>
<div class="quick-view-content">
    <div class="quick-view-close"></div>
    <div class="product">
        <div class="quick-view-images">
            <?php
            // Product Image
            echo '<div class="quick-view-image-main">';
            echo wp_kses_post( $product->get_image( 'large' ) );
            echo '</div>';
            
            // Product Gallery
            $attachment_ids = $product->get_gallery_image_ids();
            
            if ( $attachment_ids && count( $attachment_ids ) > 0 ) {
                echo '<div class="quick-view-thumbnails">';
                foreach ( $attachment_ids as $attachment_id ) {
                    echo '<div class="quick-view-thumbnail">';
                    echo wp_get_attachment_image( $attachment_id, 'thumbnail' );
                    echo '</div>';
                }
                echo '</div>';
            }
            ?>
        </div>
        
        <div class="quick-view-summary">
            <h2 class="product_title"><?php echo esc_html( $product->get_name() ); ?></h2>
            
            <?php
            // Rating
            if ( wc_review_ratings_enabled() ) {
                echo wc_get_rating_html( $product->get_average_rating() );
            }
            
            // Price
            echo '<div class="price">' . $product->get_price_html() . '</div>';
            
            // Short Description
            if ( $product->get_short_description() ) {
                echo '<div class="woocommerce-product-details__short-description">';
                echo wp_kses_post( $product->get_short_description() );
                echo '</div>';
            }
            
            // Add to cart form
            echo '<div class="quick-view-add-to-cart">';
            woocommerce_template_single_add_to_cart();
            echo '</div>';
            
            // Custom fields
            $delivery_time = get_post_meta( $product->get_id(), '_aqualuxe_delivery_time', true );
            $size = get_post_meta( $product->get_id(), '_aqualuxe_size', true );
            $diet = get_post_meta( $product->get_id(), '_aqualuxe_diet', true );
            $temperament = get_post_meta( $product->get_id(), '_aqualuxe_temperament', true );
            
            if ( $delivery_time || $size || $diet || $temperament ) {
                echo '<div class="product-meta">';
                
                if ( $delivery_time ) {
                    echo '<div class="product-meta-item delivery-time">';
                    echo '<span class="label">' . esc_html__( 'Delivery Time:', 'aqualuxe' ) . '</span> ';
                    echo '<span class="value">' . esc_html( $delivery_time ) . '</span>';
                    echo '</div>';
                }
                
                if ( $size ) {
                    echo '<div class="product-meta-item size">';
                    echo '<span class="label">' . esc_html__( 'Size:', 'aqualuxe' ) . '</span> ';
                    echo '<span class="value">' . esc_html( $size ) . '</span>';
                    echo '</div>';
                }
                
                if ( $diet ) {
                    echo '<div class="product-meta-item diet">';
                    echo '<span class="label">' . esc_html__( 'Diet:', 'aqualuxe' ) . '</span> ';
                    echo '<span class="value">' . esc_html( $diet ) . '</span>';
                    echo '</div>';
                }
                
                if ( $temperament ) {
                    echo '<div class="product-meta-item temperament">';
                    echo '<span class="label">' . esc_html__( 'Temperament:', 'aqualuxe' ) . '</span> ';
                    echo '<span class="value">' . esc_html( $temperament ) . '</span>';
                    echo '</div>';
                }
                
                echo '</div>';
            }
            ?>
            
            <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="view-full-details"><?php esc_html_e( 'View Full Details', 'aqualuxe' ); ?></a>
        </div>
    </div>
</div>