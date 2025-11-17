<?php

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_end', 10 );

function aqualuxe_woocommerce_wrapper_start() {
    echo '<div id="primary" class="site-main flex-grow">';
}

function aqualuxe_woocommerce_wrapper_end() {
    echo '</div>';
}

// Move breadcrumbs
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
add_action( 'woocommerce_before_shop_loop', 'woocommerce_breadcrumb', 10 );

// Wrap shop loop opening
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_shop_loop_header_start', 20 );
function aqualuxe_shop_loop_header_start() {
    echo '<div class="flex justify-between items-center mb-8">';
}

// Wrap shop loop closing
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_shop_loop_header_end', 40 );
function aqualuxe_shop_loop_header_end() {
    echo '</div>';
}

// Remove default styling
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Change add to cart button text
add_filter( 'woocommerce_product_add_to_cart_text', 'aqualuxe_add_to_cart_text' );
function aqualuxe_add_to_cart_text() {
    return __( 'Add to Cart', 'aqualuxe' );
}

// Add custom classes to add to cart button
add_filter( 'woocommerce_loop_add_to_cart_link', 'aqualuxe_add_to_cart_class', 10, 2 );
function aqualuxe_add_to_cart_class( $link, $product ) {
    $link = str_replace( 'class="button', 'class="button bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out m-4 block text-center', $link );
    return $link;
}

// Single Product Page
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

add_action( 'woocommerce_before_single_product_summary', 'aqualuxe_show_product_images', 20 );
function aqualuxe_show_product_images() {
    wc_get_template( 'single-product/product-image.php' );
}

add_filter( 'woocommerce_single_product_image_gallery_classes', function( $classes ) {
    $classes = array_diff( $classes, array( 'woocommerce-product-gallery--with-images' ) );
    return $classes;
} );

// Customize single product summary
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// Customize product tabs
add_filter( 'woocommerce_product_tabs', 'aqualuxe_product_tabs' );
function aqualuxe_product_tabs( $tabs ) {
    if ( isset( $tabs['description'] ) ) {
        $tabs['description']['callback'] = 'aqualuxe_product_description_tab';
    }
    return $tabs;
}

function aqualuxe_product_description_tab() {
    wc_get_template( 'single-product/tabs/description.php' );
}

// Cart Page
add_action( 'woocommerce_cart_actions', 'aqualuxe_cart_actions_class' );
function aqualuxe_cart_actions_class() {
    echo '<div class="flex justify-between items-center mt-4">';
}

add_action( 'woocommerce_after_cart_table', 'aqualuxe_cart_actions_close_div', 1 );
function aqualuxe_cart_actions_close_div() {
    echo '</div>';
}

add_filter( 'woocommerce_cart_item_remove_link', 'aqualuxe_cart_item_remove_link_class', 10, 2 );
function aqualuxe_cart_item_remove_link_class( $link, $cart_item_key ) {
    $link = str_replace( 'class="remove"', 'class="remove text-red-500 hover:text-red-700"', $link );
    return $link;
}

// Checkout Page
add_filter( 'woocommerce_checkout_fields', 'aqualuxe_checkout_fields' );
function aqualuxe_checkout_fields( $fields ) {
    foreach ( $fields as $category => $fieldset ) {
        foreach ( $fieldset as $field_key => $field ) {
            $fields[$category][$field_key]['input_class'][] = 'mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white';
        }
    }
    return $fields;
}
