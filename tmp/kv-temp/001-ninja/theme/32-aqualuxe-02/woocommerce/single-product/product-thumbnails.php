<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$attachment_ids = $product->get_gallery_image_ids();

if ( $attachment_ids && $product->get_image_id() ) {
	echo '<div class="product-thumbnails grid grid-cols-4 gap-2 mt-2">';
	
	// Add main product image as first thumbnail
	$main_image_id = $product->get_image_id();
	$main_image_html = wc_get_gallery_image_html( $main_image_id, true );
	$main_image_html = str_replace( 'woocommerce-product-gallery__image', 'woocommerce-product-gallery__image product-thumbnail active', $main_image_html );
	echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $main_image_html, $main_image_id );
	
	foreach ( $attachment_ids as $attachment_id ) {
		$thumbnail_html = wc_get_gallery_image_html( $attachment_id );
		$thumbnail_html = str_replace( 'woocommerce-product-gallery__image', 'woocommerce-product-gallery__image product-thumbnail', $thumbnail_html );
		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $thumbnail_html, $attachment_id );
	}
	
	echo '</div>';
}