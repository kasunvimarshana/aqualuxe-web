<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
    return;
}

global $product;

// Get gallery settings from theme customizer
$gallery_style = get_theme_mod( 'aqualuxe_product_gallery_style', 'horizontal' );
$zoom_enabled = get_theme_mod( 'aqualuxe_product_gallery_zoom', true );
$lightbox_enabled = get_theme_mod( 'aqualuxe_product_gallery_lightbox', true );

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
    'woocommerce_single_product_image_gallery_classes',
    array(
        'woocommerce-product-gallery',
        'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
        'woocommerce-product-gallery--columns-' . absint( $columns ),
        'images',
        'aqualuxe-product-gallery',
        'aqualuxe-product-gallery-style-' . $gallery_style,
    )
);

if ( $zoom_enabled ) {
    $wrapper_classes[] = 'aqualuxe-product-gallery-zoom-enabled';
}

if ( $lightbox_enabled ) {
    $wrapper_classes[] = 'aqualuxe-product-gallery-lightbox-enabled';
}

?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
    <figure class="woocommerce-product-gallery__wrapper aqualuxe-gallery-wrapper">
        <?php
        if ( $post_thumbnail_id ) {
            $html = wc_get_gallery_image_html( $post_thumbnail_id, true );
        } else {
            $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
            $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'aqualuxe' ) );
            $html .= '</div>';
        }

        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped

        // Additional gallery images
        $attachment_ids = $product->get_gallery_image_ids();
        
        if ( $attachment_ids && $post_thumbnail_id ) {
            foreach ( $attachment_ids as $attachment_id ) {
                echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id ), $attachment_id ); // phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
            }
        }
        ?>
    </figure>
    
    <?php if ( $attachment_ids && count( $attachment_ids ) > 0 && in_array( $gallery_style, array( 'horizontal', 'vertical', 'grid' ), true ) ) : ?>
    <div class="aqualuxe-product-gallery-thumbnails aqualuxe-thumbnails-style-<?php echo esc_attr( $gallery_style ); ?>">
        <?php if ( $post_thumbnail_id ) : ?>
            <div class="aqualuxe-gallery-thumbnail" data-slide="0">
                <?php echo wp_get_attachment_image( $post_thumbnail_id, 'thumbnail' ); ?>
            </div>
        <?php endif; ?>
        
        <?php 
        if ( $attachment_ids ) {
            $slide_index = 1;
            foreach ( $attachment_ids as $attachment_id ) {
                echo '<div class="aqualuxe-gallery-thumbnail" data-slide="' . esc_attr( $slide_index ) . '">';
                echo wp_get_attachment_image( $attachment_id, 'thumbnail' );
                echo '</div>';
                $slide_index++;
            }
        }
        ?>
    </div>
    <?php endif; ?>
    
    <?php if ( $gallery_style === 'slider' ) : ?>
    <div class="aqualuxe-gallery-navigation">
        <button class="aqualuxe-gallery-prev">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            <span class="screen-reader-text"><?php esc_html_e( 'Previous', 'aqualuxe' ); ?></span>
        </button>
        <button class="aqualuxe-gallery-next">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
            <span class="screen-reader-text"><?php esc_html_e( 'Next', 'aqualuxe' ); ?></span>
        </button>
    </div>
    <?php endif; ?>
    
    <?php if ( $lightbox_enabled ) : ?>
    <a href="#" class="aqualuxe-gallery-fullscreen">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg>
        <span class="screen-reader-text"><?php esc_html_e( 'View fullscreen', 'aqualuxe' ); ?></span>
    </a>
    <?php endif; ?>
</div>