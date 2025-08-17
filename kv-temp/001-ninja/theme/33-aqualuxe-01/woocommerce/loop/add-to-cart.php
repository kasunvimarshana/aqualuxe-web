<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package AquaLuxe
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;

// Get add to cart button settings from theme customizer
$button_style = get_theme_mod( 'aqualuxe_product_button_style', 'default' );
$button_icon = get_theme_mod( 'aqualuxe_product_button_icon', true );
$button_animation = get_theme_mod( 'aqualuxe_product_button_animation', 'fade' );
$button_text_simple = get_theme_mod( 'aqualuxe_product_button_text_simple', __( 'Add to cart', 'aqualuxe' ) );
$button_text_variable = get_theme_mod( 'aqualuxe_product_button_text_variable', __( 'Select options', 'aqualuxe' ) );
$button_text_grouped = get_theme_mod( 'aqualuxe_product_button_text_grouped', __( 'View products', 'aqualuxe' ) );
$button_text_external = get_theme_mod( 'aqualuxe_product_button_text_external', __( 'Buy now', 'aqualuxe' ) );

// Override default WooCommerce text with customizer settings
add_filter( 'woocommerce_product_add_to_cart_text', function( $text, $product_obj ) use ( $button_text_simple, $button_text_variable, $button_text_grouped, $button_text_external ) {
    if ( $product_obj->is_type( 'simple' ) && $product_obj->is_purchasable() && $product_obj->is_in_stock() ) {
        return $button_text_simple;
    } elseif ( $product_obj->is_type( 'variable' ) ) {
        return $button_text_variable;
    } elseif ( $product_obj->is_type( 'grouped' ) ) {
        return $button_text_grouped;
    } elseif ( $product_obj->is_type( 'external' ) ) {
        return $button_text_external;
    }
    return $text;
}, 10, 2 );

// Get standard WooCommerce button args
$args = array(
    'class' => implode(
        ' ',
        array_filter(
            array(
                'button',
                'product_type_' . $product->get_type(),
                $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
                'aqualuxe-button',
                'aqualuxe-button-style-' . $button_style,
                'aqualuxe-button-animation-' . $button_animation,
            )
        )
    ),
);

// Add custom icon if enabled
if ( $button_icon ) {
    $icon_html = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>';
    
    // For variable products, use a different icon
    if ( $product->is_type( 'variable' ) ) {
        $icon_html = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>';
    }
    // For external products, use a different icon
    elseif ( $product->is_type( 'external' ) ) {
        $icon_html = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>';
    }
    
    // Add icon to the button text
    add_filter( 'woocommerce_loop_add_to_cart_link', function( $html, $product, $args ) use ( $icon_html ) {
        return str_replace( '>', '>' . $icon_html, $html );
    }, 10, 3 );
}

echo apply_filters(
    'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
    sprintf(
        '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
        esc_url( $product->add_to_cart_url() ),
        esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
        esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
        isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
        esc_html( $product->add_to_cart_text() )
    ),
    $product,
    $args
);

// Remove our filter after use to avoid affecting other buttons
remove_all_filters( 'woocommerce_loop_add_to_cart_link', 10 );
remove_all_filters( 'woocommerce_product_add_to_cart_text', 10 );