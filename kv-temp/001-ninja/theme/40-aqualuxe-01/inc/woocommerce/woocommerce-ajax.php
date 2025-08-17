<?php
/**
 * WooCommerce AJAX handlers
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Quick view AJAX handler
 */
function aqualuxe_quick_view_ajax() {
    // Check nonce
    if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'aqualuxe_quick_view_nonce' ) ) {
        wp_send_json_error( array( 'message' => __( 'Invalid security token.', 'aqualuxe' ) ) );
    }

    // Check product ID
    if ( ! isset( $_POST['product_id'] ) || empty( $_POST['product_id'] ) ) {
        wp_send_json_error( array( 'message' => __( 'No product ID provided.', 'aqualuxe' ) ) );
    }

    $product_id = absint( $_POST['product_id'] );
    $product = wc_get_product( $product_id );

    if ( ! $product ) {
        wp_send_json_error( array( 'message' => __( 'Product not found.', 'aqualuxe' ) ) );
    }

    // Start output buffering to capture the product content
    ob_start();
    ?>
    <div class="quick-view-product">
        <div class="quick-view-product-image">
            <?php
            // Product gallery
            $attachment_ids = $product->get_gallery_image_ids();
            $post_thumbnail_id = $product->get_image_id();
            $has_gallery = count( $attachment_ids ) > 0;
            
            if ( $post_thumbnail_id ) {
                $html = wc_get_gallery_image_html( $post_thumbnail_id, true );
                echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );
            } else {
                echo '<div class="woocommerce-product-gallery__image--placeholder">';
                echo sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'aqualuxe' ) );
                echo '</div>';
            }

            if ( $has_gallery ) {
                echo '<div class="quick-view-thumbnails">';
                foreach ( $attachment_ids as $attachment_id ) {
                    echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id ), $attachment_id );
                }
                echo '</div>';
            }
            ?>
        </div>

        <div class="quick-view-product-summary">
            <?php
            // Product categories
            echo wc_get_product_category_list( $product->get_id(), ', ', '<div class="product-categories">', '</div>' );
            
            // Product title
            echo '<h2 class="product_title">' . esc_html( $product->get_name() ) . '</h2>';
            
            // Product rating
            if ( wc_review_ratings_enabled() ) {
                echo wc_get_rating_html( $product->get_average_rating() );
            }
            
            // Product price
            echo '<div class="product-price">' . $product->get_price_html() . '</div>';
            
            // Product short description
            if ( $product->get_short_description() ) {
                echo '<div class="product-short-description">' . wp_kses_post( $product->get_short_description() ) . '</div>';
            }
            
            // Add to cart form
            echo '<div class="product-add-to-cart">';
            woocommerce_template_single_add_to_cart();
            echo '</div>';
            
            // Product meta
            echo '<div class="product-meta">';
            
            // SKU
            if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) {
                echo '<span class="sku_wrapper">' . esc_html__( 'SKU:', 'aqualuxe' ) . ' <span class="sku">' . ( $product->get_sku() ? esc_html( $product->get_sku() ) : esc_html__( 'N/A', 'aqualuxe' ) ) . '</span></span>';
            }
            
            // Stock status
            echo '<div class="product-stock-status">';
            echo '<span class="stock-status-label">' . esc_html__( 'Availability:', 'aqualuxe' ) . '</span> ';
            
            if ( $product->is_in_stock() ) {
                echo '<span class="stock-status in-stock">' . esc_html__( 'In Stock', 'aqualuxe' ) . '</span>';
            } else {
                echo '<span class="stock-status out-of-stock">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
            }
            echo '</div>';
            
            echo '</div>'; // End product meta
            
            // View full details link
            echo '<div class="view-full-details">';
            echo '<a href="' . esc_url( $product->get_permalink() ) . '" class="button">' . esc_html__( 'View Full Details', 'aqualuxe' ) . '</a>';
            echo '</div>';
            ?>
        </div>
    </div>
    <?php
    $html = ob_get_clean();

    wp_send_json_success( array(
        'html' => $html,
        'product_id' => $product_id,
        'product_title' => $product->get_name(),
    ) );
}
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view_ajax' );

/**
 * Add quick view nonce to localized script data
 *
 * @param array $data Script data
 * @return array Modified script data
 */
function aqualuxe_add_quick_view_nonce( $data ) {
    $data['quickViewNonce'] = wp_create_nonce( 'aqualuxe_quick_view_nonce' );
    return $data;
}
add_filter( 'aqualuxe_localize_script_data', 'aqualuxe_add_quick_view_nonce' );