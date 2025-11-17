<?php
/**
 * WooCommerce specific functions and configurations
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce setup function.
 */
function aqualuxe_woocommerce_setup() {
    // Add product gallery support
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // Declare WooCommerce support
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 400,
        'single_image_width'    => 800,
        'product_grid'          => array(
            'default_rows'    => 3,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ),
    ) );
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );

/**
 * Remove default WooCommerce styles.
 */
function aqualuxe_dequeue_woocommerce_styles( $enqueue_styles ) {
    return array();
}
add_filter( 'woocommerce_enqueue_styles', 'aqualuxe_dequeue_woocommerce_styles' );

/**
 * Add custom classes to product elements
 */
function aqualuxe_woocommerce_product_classes( $classes, $class, $product_id ) {
    if ( is_product() ) {
        $classes[] = 'aqualuxe-product';
    }
    return $classes;
}
add_filter( 'woocommerce_post_class', 'aqualuxe_woocommerce_product_classes', 10, 3 );

/**
 * Modify WooCommerce templates
 */
function aqualuxe_woocommerce_template_hooks() {
    // Remove default wrappers
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

    // Add custom wrappers
    add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_start', 10 );
    add_action( 'woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_end', 10 );

    // Customize shop page
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
    add_action( 'woocommerce_before_shop_loop', 'aqualuxe_woocommerce_shop_header', 20 );

    // Customize single product
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
    add_action( 'woocommerce_single_product_summary', 'aqualuxe_woocommerce_single_product_header', 5 );

    // Customize cart
    add_action( 'woocommerce_before_cart', 'aqualuxe_woocommerce_cart_header', 10 );
    
    // Customize checkout
    add_action( 'woocommerce_before_checkout_form', 'aqualuxe_woocommerce_checkout_header', 5 );
}
add_action( 'init', 'aqualuxe_woocommerce_template_hooks' );

/**
 * Custom wrapper start for WooCommerce pages
 */
function aqualuxe_woocommerce_wrapper_start() {
    echo '<main id="primary" class="site-main">';
    echo '<div class="container mx-auto px-4 py-8">';
}

/**
 * Custom wrapper end for WooCommerce pages
 */
function aqualuxe_woocommerce_wrapper_end() {
    echo '</div>';
    echo '</main>';
}

/**
 * Custom shop header
 */
function aqualuxe_woocommerce_shop_header() {
    echo '<div class="aqualuxe-shop-header flex flex-wrap items-center justify-between mb-6">';
    echo '<div class="aqualuxe-shop-results">';
    woocommerce_result_count();
    echo '</div>';
    echo '<div class="aqualuxe-shop-ordering">';
    woocommerce_catalog_ordering();
    echo '</div>';
    echo '</div>';
}

/**
 * Custom single product header
 */
function aqualuxe_woocommerce_single_product_header() {
    echo '<div class="aqualuxe-product-header mb-4">';
    woocommerce_template_single_title();
    woocommerce_template_single_rating();
    echo '</div>';
}

/**
 * Custom cart header
 */
function aqualuxe_woocommerce_cart_header() {
    echo '<div class="aqualuxe-cart-header mb-8 text-center">';
    echo '<h1 class="text-3xl md:text-4xl font-serif font-bold mb-2">' . esc_html__( 'Your Shopping Cart', 'aqualuxe' ) . '</h1>';
    echo '<p class="text-gray-600 dark:text-gray-400">' . esc_html__( 'Review your items and proceed to checkout', 'aqualuxe' ) . '</p>';
    echo '</div>';
}

/**
 * Custom checkout header
 */
function aqualuxe_woocommerce_checkout_header() {
    echo '<div class="aqualuxe-checkout-header mb-8 text-center">';
    echo '<h1 class="text-3xl md:text-4xl font-serif font-bold mb-2">' . esc_html__( 'Checkout', 'aqualuxe' ) . '</h1>';
    echo '<p class="text-gray-600 dark:text-gray-400">' . esc_html__( 'Complete your purchase by providing your details below', 'aqualuxe' ) . '</p>';
    echo '</div>';
}

/**
 * Add custom styles to checkout fields
 */
function aqualuxe_woocommerce_checkout_fields( $fields ) {
    // Add custom classes to all input fields
    foreach ( $fields as $section => $section_fields ) {
        foreach ( $section_fields as $key => $field ) {
            $fields[$section][$key]['class'][] = 'aqualuxe-form-input';
            $fields[$section][$key]['input_class'][] = 'w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-dark-light dark:text-white';
            $fields[$section][$key]['label_class'][] = 'text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block';
        }
    }
    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'aqualuxe_woocommerce_checkout_fields' );

/**
 * Customize the order review heading in checkout
 */
function aqualuxe_woocommerce_order_review_heading() {
    return esc_html__( 'Your Order Summary', 'aqualuxe' );
}
add_filter( 'woocommerce_order_review_heading', 'aqualuxe_woocommerce_order_review_heading' );

/**
 * Customize the payment heading in checkout
 */
function aqualuxe_woocommerce_checkout_payment_heading() {
    return esc_html__( 'Payment Method', 'aqualuxe' );
}
add_filter( 'woocommerce_checkout_payment_heading', 'aqualuxe_woocommerce_checkout_payment_heading' );

/**
 * Add custom classes to payment methods
 */
function aqualuxe_woocommerce_payment_methods_classes( $classes ) {
    $classes[] = 'aqualuxe-payment-methods';
    return $classes;
}
add_filter( 'woocommerce_payment_methods_classes', 'aqualuxe_woocommerce_payment_methods_classes' );

/**
 * Customize the proceed to checkout button
 */
function aqualuxe_woocommerce_proceed_to_checkout_button() {
    echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="checkout-button button alt wc-forward w-full py-3 px-6 bg-primary hover:bg-primary-dark text-white text-center rounded-lg transition-colors text-lg font-medium">';
    echo esc_html__( 'Proceed to Checkout', 'aqualuxe' );
    echo '</a>';
}
remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
add_action( 'woocommerce_proceed_to_checkout', 'aqualuxe_woocommerce_proceed_to_checkout_button', 20 );

/**
 * Customize the place order button
 */
function aqualuxe_woocommerce_order_button_html( $button ) {
    $button = str_replace( 'class="button', 'class="button w-full py-3 px-6 bg-primary hover:bg-primary-dark text-white text-center rounded-lg transition-colors text-lg font-medium', $button );
    return $button;
}
add_filter( 'woocommerce_order_button_html', 'aqualuxe_woocommerce_order_button_html' );

/**
 * Add AJAX cart fragments
 */
function aqualuxe_woocommerce_cart_fragments( $fragments ) {
    ob_start();
    $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    ?>
    <span class="mini-cart-count absolute -top-1 -right-1 w-5 h-5 flex items-center justify-center bg-accent text-dark text-xs font-bold rounded-full">
        <?php echo esc_html( $cart_count ); ?>
    </span>
    <?php
    $fragments['.mini-cart-count'] = ob_get_clean();
    
    ob_start();
    ?>
    <span class="mini-cart-count-text text-sm">
        <?php
        echo sprintf(
            /* translators: %d: number of items in cart */
            _n( '%d item', '%d items', $cart_count, 'aqualuxe' ),
            $cart_count
        );
        ?>
    </span>
    <?php
    $fragments['.mini-cart-count-text'] = ob_get_clean();
    
    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_cart_fragments' );