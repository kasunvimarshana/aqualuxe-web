<?php
/**
 * Quick view template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/quick-view.php.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $post, $product;

// Set the global product variable.
$product = wc_get_product( $product_id );

if ( ! $product ) {
    return;
}

// Set the global post variable.
$post = get_post( $product_id );
setup_postdata( $post );
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'aqualuxe-quick-view-product', $product ); ?>>
    <div class="aqualuxe-quick-view-product-inner">
        <div class="aqualuxe-quick-view-product-image">
            <?php
            // Product image.
            if ( has_post_thumbnail() ) {
                echo woocommerce_get_product_thumbnail( 'woocommerce_single' );
            } else {
                echo '<img src="' . esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ) . '" alt="' . esc_attr__( 'Placeholder', 'aqualuxe' ) . '" />';
            }

            // Sale flash.
            woocommerce_show_product_sale_flash();
            ?>
        </div>

        <div class="aqualuxe-quick-view-product-content">
            <h2 class="product_title entry-title"><?php the_title(); ?></h2>

            <?php
            // Product subtitle if available.
            $subtitle = get_post_meta( get_the_ID(), '_aqualuxe_product_subtitle', true );
            if ( $subtitle ) {
                echo '<h3 class="product-subtitle">' . esc_html( $subtitle ) . '</h3>';
            }
            ?>

            <div class="aqualuxe-quick-view-product-price">
                <?php woocommerce_template_single_price(); ?>
            </div>

            <div class="aqualuxe-quick-view-product-rating">
                <?php woocommerce_template_single_rating(); ?>
            </div>

            <div class="aqualuxe-quick-view-product-excerpt">
                <?php woocommerce_template_single_excerpt(); ?>
            </div>

            <div class="aqualuxe-quick-view-product-add-to-cart">
                <?php woocommerce_template_single_add_to_cart(); ?>
            </div>

            <div class="aqualuxe-quick-view-product-meta">
                <?php woocommerce_template_single_meta(); ?>
            </div>

            <div class="aqualuxe-quick-view-product-actions">
                <a href="<?php the_permalink(); ?>" class="button view-details"><?php esc_html_e( 'View Details', 'aqualuxe' ); ?></a>
                
                <?php
                // Wishlist button.
                if ( get_theme_mod( 'aqualuxe_wishlist', true ) ) {
                    $in_wishlist = false;
                    
                    if ( is_user_logged_in() ) {
                        $user_id = get_current_user_id();
                        $wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );
                        
                        if ( is_array( $wishlist ) ) {
                            $in_wishlist = in_array( get_the_ID(), $wishlist );
                        }
                    } else if ( isset( $_COOKIE['aqualuxe_wishlist'] ) ) {
                        $wishlist = json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true );
                        
                        if ( is_array( $wishlist ) ) {
                            $in_wishlist = in_array( get_the_ID(), $wishlist );
                        }
                    }
                    
                    $class = $in_wishlist ? 'aqualuxe-wishlist-button in-wishlist' : 'aqualuxe-wishlist-button';
                    $text = $in_wishlist ? esc_html__( 'In Wishlist', 'aqualuxe' ) : esc_html__( 'Add to Wishlist', 'aqualuxe' );
                    
                    echo '<a href="#" class="' . esc_attr( $class ) . '" data-product-id="' . esc_attr( get_the_ID() ) . '">' . esc_html( $text ) . '</a>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
// Reset post data.
wp_reset_postdata();
?>