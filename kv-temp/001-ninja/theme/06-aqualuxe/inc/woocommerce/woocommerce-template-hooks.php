<?php
/**
 * AquaLuxe WooCommerce Template Hooks
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Shop Page Hooks
 */

// Remove default WooCommerce wrappers
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Remove default WooCommerce sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Shop page header
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_product_breadcrumb', 5 );
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_woocommerce_product_search', 5 );
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_woocommerce_product_filter', 10 );
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_woocommerce_product_view_switcher', 15 );
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_woocommerce_product_sorting', 20 );
add_action( 'woocommerce_before_shop_loop', 'aqualuxe_woocommerce_product_results_count', 25 );

// Shop page footer
add_action( 'woocommerce_after_shop_loop', 'aqualuxe_woocommerce_product_pagination', 10 );

/**
 * Product Loop Hooks
 */

// Remove default WooCommerce product title
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

// Product wrapper
add_action( 'woocommerce_before_shop_loop_item', 'aqualuxe_woocommerce_product_wrapper_start', 5 );
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_woocommerce_product_wrapper_end', 15 );

// Product image wrapper
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_product_image_wrapper_start', 5 );
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_product_image_wrapper_end', 15 );

// Product title
add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_title', 10 );

// Product categories
add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_product_categories', 5 );

// Product rating
add_action( 'woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_product_rating', 5 );

/**
 * Single Product Hooks
 */

// Remove default WooCommerce product title
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );

// Product title
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_template_single_title', 5 );

// Product categories
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_categories', 6 );

// Product short description
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_short_description', 20 );

// Product meta
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_meta', 40 );

// Product sharing
add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_product_sharing', 50 );

// Product tabs
add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs', 10 );

// Product additional information
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_additional_information', 15 );

// Product specifications
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_specifications', 20 );

// Product features
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_features', 25 );

// Product dimensions
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_dimensions', 30 );

// Product weight
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_weight', 35 );

// Product shipping information
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_shipping_info', 40 );

// Product care instructions
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_care_instructions', 45 );

// Product guarantee
add_action( 'woocommerce_after_single_product_summary', 'aqualuxe_woocommerce_product_guarantee', 50 );

/**
 * Cart Hooks
 */

// Cart wrapper
add_action( 'woocommerce_before_cart', 'aqualuxe_woocommerce_cart_wrapper_start', 5 );
add_action( 'woocommerce_after_cart', 'aqualuxe_woocommerce_cart_wrapper_end', 15 );

/**
 * Checkout Hooks
 */

// Checkout wrapper
add_action( 'woocommerce_checkout_before_customer_details', 'aqualuxe_woocommerce_checkout_wrapper_start', 5 );
add_action( 'woocommerce_checkout_after_customer_details', 'aqualuxe_woocommerce_checkout_customer_details_end', 15 );
add_action( 'woocommerce_checkout_after_order_review', 'aqualuxe_woocommerce_checkout_wrapper_end', 15 );

/**
 * Account Hooks
 */

// Add wishlist endpoint
add_action( 'init', 'aqualuxe_woocommerce_add_wishlist_endpoint' );
function aqualuxe_woocommerce_add_wishlist_endpoint() {
    if ( get_theme_mod( 'aqualuxe_wishlist', true ) ) {
        add_rewrite_endpoint( 'wishlist', EP_ROOT | EP_PAGES );
    }
}

// Add wishlist query var
add_filter( 'query_vars', 'aqualuxe_woocommerce_wishlist_query_vars' );
function aqualuxe_woocommerce_wishlist_query_vars( $vars ) {
    if ( get_theme_mod( 'aqualuxe_wishlist', true ) ) {
        $vars[] = 'wishlist';
    }
    return $vars;
}

// Add wishlist to account menu
add_filter( 'woocommerce_account_menu_items', 'aqualuxe_woocommerce_wishlist_account_menu_item' );
function aqualuxe_woocommerce_wishlist_account_menu_item( $items ) {
    if ( get_theme_mod( 'aqualuxe_wishlist', true ) ) {
        $items['wishlist'] = __( 'Wishlist', 'aqualuxe' );
    }
    return $items;
}

// Add wishlist content
add_action( 'woocommerce_account_wishlist_endpoint', 'aqualuxe_woocommerce_wishlist_content' );
function aqualuxe_woocommerce_wishlist_content() {
    if ( ! get_theme_mod( 'aqualuxe_wishlist', true ) ) {
        return;
    }
    
    // Get wishlist
    $wishlist = array();
    
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );
        
        if ( ! is_array( $wishlist ) ) {
            $wishlist = array();
        }
    } else {
        $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();
        
        if ( ! is_array( $wishlist ) ) {
            $wishlist = array();
        }
    }
    
    // Display wishlist
    if ( ! empty( $wishlist ) ) {
        echo '<div class="aqualuxe-wishlist">';
        echo '<h2>' . esc_html__( 'My Wishlist', 'aqualuxe' ) . '</h2>';
        
        echo '<ul class="products columns-4">';
        
        foreach ( $wishlist as $product_id ) {
            $product = wc_get_product( $product_id );
            
            if ( $product && $product->is_visible() ) {
                echo '<li class="product">';
                echo '<div class="product-inner">';
                
                // Product image
                echo '<div class="product-image-wrapper">';
                echo '<a href="' . esc_url( get_permalink( $product_id ) ) . '">';
                echo $product->get_image( 'woocommerce_thumbnail' );
                echo '</a>';
                echo '<div class="product-hover-overlay">';
                echo '<div class="product-hover-buttons">';
                echo '<a href="' . esc_url( $product->add_to_cart_url() ) . '" class="button add_to_cart_button">' . esc_html__( 'Add to Cart', 'aqualuxe' ) . '</a>';
                echo '<a href="#" class="aqualuxe-wishlist-remove" data-product-id="' . esc_attr( $product_id ) . '">' . esc_html__( 'Remove', 'aqualuxe' ) . '</a>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                
                // Product title
                echo '<h2 class="woocommerce-loop-product__title"><a href="' . esc_url( get_permalink( $product_id ) ) . '">' . esc_html( $product->get_name() ) . '</a></h2>';
                
                // Product price
                echo '<div class="product-price">';
                echo $product->get_price_html();
                echo '</div>';
                
                echo '</div>';
                echo '</li>';
            }
        }
        
        echo '</ul>';
        echo '</div>';
    } else {
        echo '<div class="aqualuxe-wishlist-empty">';
        echo '<p>' . esc_html__( 'Your wishlist is empty.', 'aqualuxe' ) . '</p>';
        echo '<a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" class="button">' . esc_html__( 'Go to Shop', 'aqualuxe' ) . '</a>';
        echo '</div>';
    }
}

/**
 * Header Hooks
 */

// Add mini cart to header
add_action( 'aqualuxe_header_icons', 'aqualuxe_woocommerce_mini_cart', 10 );

// Add account menu to header
add_action( 'aqualuxe_header_icons', 'aqualuxe_woocommerce_account_menu', 15 );

// Add wishlist menu to header
add_action( 'aqualuxe_header_icons', 'aqualuxe_woocommerce_wishlist_menu', 20 );

/**
 * Footer Hooks
 */

// Add currency switcher to footer
add_action( 'aqualuxe_footer_widgets', 'aqualuxe_woocommerce_currency_switcher', 15 );

/**
 * AJAX Hooks
 */

// AJAX add to cart
add_action( 'wp_ajax_aqualuxe_add_to_cart', 'aqualuxe_woocommerce_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_aqualuxe_add_to_cart', 'aqualuxe_woocommerce_ajax_add_to_cart' );
function aqualuxe_woocommerce_ajax_add_to_cart() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ) );
    }
    
    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    
    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID', 'aqualuxe' ) ) );
    }
    
    $quantity = isset( $_POST['quantity'] ) ? absint( $_POST['quantity'] ) : 1;
    $variation_id = isset( $_POST['variation_id'] ) ? absint( $_POST['variation_id'] ) : 0;
    $variation = array();
    
    if ( isset( $_POST['variation'] ) && is_array( $_POST['variation'] ) ) {
        foreach ( $_POST['variation'] as $key => $value ) {
            $variation[ sanitize_text_field( $key ) ] = sanitize_text_field( $value );
        }
    }
    
    $cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
    
    if ( $cart_item_key ) {
        WC_AJAX::get_refreshed_fragments();
    } else {
        wp_send_json_error( array( 'message' => esc_html__( 'Error adding product to cart', 'aqualuxe' ) ) );
    }
}

// AJAX quick view
add_action( 'wp_ajax_aqualuxe_quick_view', 'aqualuxe_woocommerce_ajax_quick_view' );
add_action( 'wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_woocommerce_ajax_quick_view' );
function aqualuxe_woocommerce_ajax_quick_view() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ) );
    }
    
    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    
    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID', 'aqualuxe' ) ) );
    }
    
    $product = wc_get_product( $product_id );
    
    if ( ! $product ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Product not found', 'aqualuxe' ) ) );
    }
    
    ob_start();
    include AQUALUXE_DIR . 'woocommerce/quick-view.php';
    $html = ob_get_clean();
    
    wp_send_json_success( array( 'html' => $html ) );
}

// AJAX wishlist add
add_action( 'wp_ajax_aqualuxe_wishlist_add', 'aqualuxe_woocommerce_ajax_wishlist_add' );
add_action( 'wp_ajax_nopriv_aqualuxe_wishlist_add', 'aqualuxe_woocommerce_ajax_wishlist_add' );
function aqualuxe_woocommerce_ajax_wishlist_add() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ) );
    }
    
    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    
    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID', 'aqualuxe' ) ) );
    }
    
    $wishlist = array();
    
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );
        
        if ( ! is_array( $wishlist ) ) {
            $wishlist = array();
        }
        
        if ( ! in_array( $product_id, $wishlist, true ) ) {
            $wishlist[] = $product_id;
            update_user_meta( $user_id, 'aqualuxe_wishlist', $wishlist );
        }
    } else {
        $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();
        
        if ( ! is_array( $wishlist ) ) {
            $wishlist = array();
        }
        
        if ( ! in_array( $product_id, $wishlist, true ) ) {
            $wishlist[] = $product_id;
            setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
        }
    }
    
    wp_send_json_success( array( 'message' => esc_html__( 'Product added to wishlist', 'aqualuxe' ) ) );
}

// AJAX wishlist remove
add_action( 'wp_ajax_aqualuxe_wishlist_remove', 'aqualuxe_woocommerce_ajax_wishlist_remove' );
add_action( 'wp_ajax_nopriv_aqualuxe_wishlist_remove', 'aqualuxe_woocommerce_ajax_wishlist_remove' );
function aqualuxe_woocommerce_ajax_wishlist_remove() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ) );
    }
    
    $product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
    
    if ( ! $product_id ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid product ID', 'aqualuxe' ) ) );
    }
    
    $wishlist = array();
    
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $wishlist = get_user_meta( $user_id, 'aqualuxe_wishlist', true );
        
        if ( ! is_array( $wishlist ) ) {
            $wishlist = array();
        }
        
        $wishlist = array_diff( $wishlist, array( $product_id ) );
        update_user_meta( $user_id, 'aqualuxe_wishlist', $wishlist );
    } else {
        $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : array();
        
        if ( ! is_array( $wishlist ) ) {
            $wishlist = array();
        }
        
        $wishlist = array_diff( $wishlist, array( $product_id ) );
        setcookie( 'aqualuxe_wishlist', json_encode( $wishlist ), time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    }
    
    wp_send_json_success( array( 'message' => esc_html__( 'Product removed from wishlist', 'aqualuxe' ) ) );
}

// AJAX currency switch
add_action( 'wp_ajax_aqualuxe_currency_switch', 'aqualuxe_woocommerce_ajax_currency_switch' );
add_action( 'wp_ajax_nopriv_aqualuxe_currency_switch', 'aqualuxe_woocommerce_ajax_currency_switch' );
function aqualuxe_woocommerce_ajax_currency_switch() {
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-woocommerce-nonce' ) ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce', 'aqualuxe' ) ) );
    }
    
    $currency = isset( $_POST['currency'] ) ? sanitize_text_field( wp_unslash( $_POST['currency'] ) ) : '';
    
    if ( ! $currency ) {
        wp_send_json_error( array( 'message' => esc_html__( 'Invalid currency', 'aqualuxe' ) ) );
    }
    
    setcookie( 'aqualuxe_currency', $currency, time() + ( 30 * DAY_IN_SECONDS ), COOKIEPATH, COOKIE_DOMAIN );
    
    wp_send_json_success( array( 'message' => esc_html__( 'Currency switched', 'aqualuxe' ) ) );
}

/**
 * Filter Hooks
 */

// Set number of products per row
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );
function aqualuxe_woocommerce_loop_columns() {
    return get_theme_mod( 'aqualuxe_shop_columns', 4 );
}

// Set number of products per page
add_filter( 'loop_shop_per_page', 'aqualuxe_woocommerce_products_per_page' );
function aqualuxe_woocommerce_products_per_page() {
    return get_theme_mod( 'aqualuxe_shop_products_per_page', 12 );
}

// Set related products args
add_filter( 'woocommerce_output_related_products_args', 'aqualuxe_woocommerce_related_products_args' );
function aqualuxe_woocommerce_related_products_args( $args ) {
    $args['posts_per_page'] = get_theme_mod( 'aqualuxe_related_products_count', 4 );
    $args['columns'] = get_theme_mod( 'aqualuxe_related_products_columns', 4 );
    
    return $args;
}

// Set upsell products args
add_filter( 'woocommerce_upsell_display_args', 'aqualuxe_woocommerce_upsell_products_args' );
function aqualuxe_woocommerce_upsell_products_args( $args ) {
    $args['posts_per_page'] = get_theme_mod( 'aqualuxe_upsell_products_count', 4 );
    $args['columns'] = get_theme_mod( 'aqualuxe_upsell_products_columns', 4 );
    
    return $args;
}

// Set cross-sell products args
add_filter( 'woocommerce_cross_sells_columns', 'aqualuxe_woocommerce_cross_sells_columns' );
function aqualuxe_woocommerce_cross_sells_columns() {
    return get_theme_mod( 'aqualuxe_cross_sells_columns', 2 );
}

add_filter( 'woocommerce_cross_sells_total', 'aqualuxe_woocommerce_cross_sells_total' );
function aqualuxe_woocommerce_cross_sells_total() {
    return get_theme_mod( 'aqualuxe_cross_sells_count', 2 );
}

// Add custom product tabs
add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_custom_product_tabs' );
function aqualuxe_woocommerce_custom_product_tabs( $tabs ) {
    // Add care instructions tab
    if ( get_theme_mod( 'aqualuxe_product_care_tab', true ) ) {
        $tabs['care'] = array(
            'title'    => esc_html__( 'Care Instructions', 'aqualuxe' ),
            'priority' => 30,
            'callback' => 'aqualuxe_woocommerce_product_care_tab_content',
        );
    }
    
    // Add shipping tab
    if ( get_theme_mod( 'aqualuxe_product_shipping_tab', true ) ) {
        $tabs['shipping'] = array(
            'title'    => esc_html__( 'Shipping & Returns', 'aqualuxe' ),
            'priority' => 40,
            'callback' => 'aqualuxe_woocommerce_product_shipping_tab_content',
        );
    }
    
    return $tabs;
}

// Care instructions tab content
function aqualuxe_woocommerce_product_care_tab_content() {
    global $product;
    
    $care_instructions = get_post_meta( $product->get_id(), '_aqualuxe_care_instructions', true );
    
    if ( $care_instructions ) {
        echo wp_kses_post( wpautop( $care_instructions ) );
    } else {
        echo wp_kses_post( wpautop( get_theme_mod( 'aqualuxe_default_care_instructions', esc_html__( 'Care instructions for this product.', 'aqualuxe' ) ) ) );
    }
}

// Shipping tab content
function aqualuxe_woocommerce_product_shipping_tab_content() {
    global $product;
    
    $shipping_info = get_post_meta( $product->get_id(), '_aqualuxe_shipping_info', true );
    
    if ( $shipping_info ) {
        echo wp_kses_post( wpautop( $shipping_info ) );
    } else {
        echo wp_kses_post( wpautop( get_theme_mod( 'aqualuxe_default_shipping_info', esc_html__( 'Shipping and returns information for this product.', 'aqualuxe' ) ) ) );
    }
}

// Change currency
add_filter( 'woocommerce_currency', 'aqualuxe_woocommerce_change_currency' );
function aqualuxe_woocommerce_change_currency( $currency ) {
    if ( get_theme_mod( 'aqualuxe_multi_currency', false ) && isset( $_COOKIE['aqualuxe_currency'] ) ) {
        $currency = sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_currency'] ) );
    }
    
    return $currency;
}

// Change currency symbol
add_filter( 'woocommerce_currency_symbol', 'aqualuxe_woocommerce_change_currency_symbol', 10, 2 );
function aqualuxe_woocommerce_change_currency_symbol( $symbol, $currency ) {
    if ( get_theme_mod( 'aqualuxe_multi_currency', false ) && isset( $_COOKIE['aqualuxe_currency'] ) ) {
        $currency = sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_currency'] ) );
        $symbol = get_woocommerce_currency_symbol( $currency );
    }
    
    return $symbol;
}

// Optimize international shipping rates
add_filter( 'woocommerce_package_rates', 'aqualuxe_woocommerce_international_shipping_rates', 10, 2 );
function aqualuxe_woocommerce_international_shipping_rates( $rates, $package ) {
    if ( ! get_theme_mod( 'aqualuxe_international_shipping', false ) ) {
        return $rates;
    }
    
    // Get customer country
    $customer_country = $package['destination']['country'];
    
    // Get store country
    $store_country = WC()->countries->get_base_country();
    
    // Check if international shipping
    if ( $customer_country !== $store_country ) {
        // Get international shipping zones
        $shipping_zones = aqualuxe_woocommerce_get_international_shipping_zones();
        
        // Find the zone for the customer country
        $customer_zone = 'rest_of_world';
        
        foreach ( $shipping_zones as $zone_id => $zone_data ) {
            if ( in_array( $customer_country, $zone_data['countries'], true ) ) {
                $customer_zone = $zone_id;
                break;
            }
        }
        
        // Apply zone-specific rates
        foreach ( $rates as $rate_id => $rate ) {
            if ( isset( $shipping_zones[ $customer_zone ]['rate_adjustment'] ) ) {
                $adjustment = $shipping_zones[ $customer_zone ]['rate_adjustment'];
                $rates[ $rate_id ]->cost *= $adjustment;
            }
        }
    }
    
    return $rates;
}

// Get international shipping zones
function aqualuxe_woocommerce_get_international_shipping_zones() {
    return array(
        'europe' => array(
            'name'           => esc_html__( 'Europe', 'aqualuxe' ),
            'countries'      => WC()->countries->get_european_union_countries(),
            'rate_adjustment' => 1.0,
        ),
        'north_america' => array(
            'name'           => esc_html__( 'North America', 'aqualuxe' ),
            'countries'      => array( 'US', 'CA', 'MX' ),
            'rate_adjustment' => 1.2,
        ),
        'asia_pacific' => array(
            'name'           => esc_html__( 'Asia Pacific', 'aqualuxe' ),
            'countries'      => array( 'AU', 'NZ', 'JP', 'SG', 'KR', 'CN', 'HK', 'TW' ),
            'rate_adjustment' => 1.5,
        ),
        'rest_of_world' => array(
            'name'           => esc_html__( 'Rest of World', 'aqualuxe' ),
            'countries'      => array(),
            'rate_adjustment' => 2.0,
        ),
    );
}

// Add shipping notice
add_action( 'woocommerce_review_order_before_shipping', 'aqualuxe_woocommerce_add_shipping_notice' );
function aqualuxe_woocommerce_add_shipping_notice() {
    if ( ! get_theme_mod( 'aqualuxe_international_shipping', false ) ) {
        return;
    }
    
    // Get customer country
    $customer_country = WC()->customer->get_shipping_country();
    
    // Get store country
    $store_country = WC()->countries->get_base_country();
    
    // Check if international shipping
    if ( $customer_country !== $store_country ) {
        // Get international shipping zones
        $shipping_zones = aqualuxe_woocommerce_get_international_shipping_zones();
        
        // Find the zone for the customer country
        $customer_zone = 'rest_of_world';
        
        foreach ( $shipping_zones as $zone_id => $zone_data ) {
            if ( in_array( $customer_country, $zone_data['countries'], true ) ) {
                $customer_zone = $zone_id;
                break;
            }
        }
        
        // Display notice
        echo '<div class="aqualuxe-shipping-notice">';
        echo '<p>' . esc_html__( 'International shipping rates apply. Please allow additional time for delivery.', 'aqualuxe' ) . '</p>';
        
        // Display estimated delivery time
        $delivery_times = array(
            'europe'        => esc_html__( '5-7 business days', 'aqualuxe' ),
            'north_america' => esc_html__( '7-10 business days', 'aqualuxe' ),
            'asia_pacific'  => esc_html__( '10-14 business days', 'aqualuxe' ),
            'rest_of_world' => esc_html__( '14-21 business days', 'aqualuxe' ),
        );
        
        if ( isset( $delivery_times[ $customer_zone ] ) ) {
            echo '<p>' . esc_html__( 'Estimated delivery time: ', 'aqualuxe' ) . esc_html( $delivery_times[ $customer_zone ] ) . '</p>';
        }
        
        echo '</div>';
    }
}