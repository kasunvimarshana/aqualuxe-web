<?php
/**
 * WooCommerce compatibility and enhancements
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe WooCommerce setup
 */
function aqualuxe_woocommerce_setup() {
    add_theme_support( 'woocommerce', array(
        'thumbnail_image_width' => 300,
        'single_image_width'    => 600,
        'product_grid'          => array(
            'default_rows'    => 4,
            'min_rows'        => 2,
            'max_rows'        => 8,
            'default_columns' => 4,
            'min_columns'     => 2,
            'max_columns'     => 5,
        ),
    ) );

    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'aqualuxe_woocommerce_setup' );

/**
 * Remove default WooCommerce wrapper
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

/**
 * Add custom WooCommerce wrapper
 */
function aqualuxe_woocommerce_wrapper_start() {
    echo '<div id="primary" class="content-area"><main id="main" class="site-main" role="main">';
}
add_action( 'woocommerce_before_main_content', 'aqualuxe_woocommerce_wrapper_start', 10 );

function aqualuxe_woocommerce_wrapper_end() {
    echo '</main></div>';
}
add_action( 'woocommerce_after_main_content', 'aqualuxe_woocommerce_wrapper_end', 10 );

/**
 * Disable WooCommerce default sidebar
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/**
 * Update cart contents count via AJAX
 */
function aqualuxe_woocommerce_header_add_to_cart_fragment( $fragments ) {
    global $woocommerce;

    ob_start();
    ?>
    <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
    <?php
    $fragments['.cart-count'] = ob_get_clean();

    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'aqualuxe_woocommerce_header_add_to_cart_fragment' );

/**
 * Customize WooCommerce loop product structure
 */
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_link_open', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'aqualuxe_woocommerce_template_loop_product_link_close', 5 );

function aqualuxe_woocommerce_template_loop_product_link_open() {
    global $product;
    echo '<a href="' . get_the_permalink() . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">';
}

function aqualuxe_woocommerce_template_loop_product_link_close() {
    echo '</a>';
}

function aqualuxe_woocommerce_template_loop_product_title() {
    echo '<h2 class="woocommerce-loop-product__title">' . get_the_title() . '</h2>';
}

/**
 * Add product badges
 */
function aqualuxe_woocommerce_show_product_loop_sale_flash() {
    global $product;

    if ( $product->is_on_sale() ) {
        $percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
        echo '<span class="onsale">-' . $percentage . '%</span>';
    }
}
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'aqualuxe_woocommerce_show_product_loop_sale_flash', 10 );

/**
 * Add quick view button
 */
function aqualuxe_add_quick_view_button() {
    global $product;
    echo '<button class="quick-view-button" data-product-id="' . $product->get_id() . '" data-quick-view>' . esc_html__( 'Quick View', 'aqualuxe' ) . '</button>';
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_add_quick_view_button', 15 );

/**
 * Add wishlist button
 */
function aqualuxe_add_wishlist_button() {
    global $product;
    
    if ( ! is_user_logged_in() ) {
        return;
    }

    $user_id = get_current_user_id();
    $wishlist = get_user_meta( $user_id, '_aqualuxe_wishlist', true );
    $in_wishlist = is_array( $wishlist ) && in_array( $product->get_id(), $wishlist );
    
    echo '<button class="wishlist-button' . ( $in_wishlist ? ' is-active' : '' ) . '" data-product-id="' . $product->get_id() . '" data-wishlist-toggle>' . esc_html__( 'Add to Wishlist', 'aqualuxe' ) . '</button>';
}
add_action( 'woocommerce_after_shop_loop_item', 'aqualuxe_add_wishlist_button', 20 );

/**
 * Customize shop pagination
 */
function aqualuxe_woocommerce_pagination_args( $args ) {
    $args['prev_text'] = esc_html__( 'Previous', 'aqualuxe' );
    $args['next_text'] = esc_html__( 'Next', 'aqualuxe' );
    return $args;
}
add_filter( 'woocommerce_pagination_args', 'aqualuxe_woocommerce_pagination_args' );

/**
 * Change number of products per row
 */
function aqualuxe_woocommerce_loop_columns() {
    return 4; // Change to desired number of columns
}
add_filter( 'loop_shop_columns', 'aqualuxe_woocommerce_loop_columns' );

/**
 * Customize single product tabs
 */
function aqualuxe_woocommerce_product_tabs( $tabs ) {
    // Rename the description tab
    $tabs['description']['title'] = esc_html__( 'Product Details', 'aqualuxe' );
    
    // Rename the reviews tab
    if ( isset( $tabs['reviews'] ) ) {
        $tabs['reviews']['title'] = esc_html__( 'Customer Reviews', 'aqualuxe' );
    }
    
    // Add custom tab
    $tabs['care_instructions'] = array(
        'title'    => esc_html__( 'Care Instructions', 'aqualuxe' ),
        'priority' => 25,
        'callback' => 'aqualuxe_care_instructions_tab_content'
    );

    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'aqualuxe_woocommerce_product_tabs' );

/**
 * Care instructions tab content
 */
function aqualuxe_care_instructions_tab_content() {
    global $product;
    
    $care_instructions = get_post_meta( $product->get_id(), '_care_instructions', true );
    
    if ( $care_instructions ) {
        echo '<h2>' . esc_html__( 'Care Instructions', 'aqualuxe' ) . '</h2>';
        echo '<div class="care-instructions">' . wp_kses_post( $care_instructions ) . '</div>';
    } else {
        echo '<p>' . esc_html__( 'No care instructions available for this product.', 'aqualuxe' ) . '</p>';
    }
}

/**
 * Add related products section
 */
function aqualuxe_output_related_products() {
    $output = array(
        'posts_per_page' => 4,
        'columns'        => 4,
        'orderby'        => 'rand',
    );
    
    woocommerce_output_related_products( $output );
}

/**
 * Customize checkout fields
 */
function aqualuxe_woocommerce_checkout_fields( $fields ) {
    // Add custom field
    $fields['billing']['billing_aquarium_size'] = array(
        'label'       => esc_html__( 'Aquarium Size (Gallons)', 'aqualuxe' ),
        'placeholder' => esc_html__( 'Enter your aquarium size', 'aqualuxe' ),
        'required'    => false,
        'clear'       => true,
        'type'        => 'number',
        'custom_attributes' => array(
            'min' => '1',
            'max' => '10000',
        ),
    );

    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'aqualuxe_woocommerce_checkout_fields' );

/**
 * Save custom checkout field
 */
function aqualuxe_save_checkout_field( $order_id ) {
    if ( ! empty( $_POST['billing_aquarium_size'] ) ) {
        update_post_meta( $order_id, 'billing_aquarium_size', sanitize_text_field( $_POST['billing_aquarium_size'] ) );
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'aqualuxe_save_checkout_field' );

/**
 * Display custom field in order admin
 */
function aqualuxe_display_checkout_field_admin( $order ) {
    $aquarium_size = get_post_meta( $order->get_id(), 'billing_aquarium_size', true );
    if ( $aquarium_size ) {
        echo '<p><strong>' . esc_html__( 'Aquarium Size:', 'aqualuxe' ) . '</strong> ' . esc_html( $aquarium_size ) . ' gallons</p>';
    }
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'aqualuxe_display_checkout_field_admin' );

/**
 * Add custom product data tabs in admin
 */
function aqualuxe_add_product_data_tabs( $tabs ) {
    $tabs['aqualuxe_care'] = array(
        'label'    => esc_html__( 'Care Instructions', 'aqualuxe' ),
        'target'   => 'aqualuxe_care_data',
        'class'    => array( 'hide_if_grouped' ),
        'priority' => 25,
    );
    
    return $tabs;
}
add_filter( 'woocommerce_product_data_tabs', 'aqualuxe_add_product_data_tabs' );

/**
 * Add custom product data panels
 */
function aqualuxe_add_product_data_panels() {
    global $woocommerce, $post;
    ?>
    <div id="aqualuxe_care_data" class="panel woocommerce_options_panel">
        <?php
        woocommerce_wp_textarea_input( array(
            'id'          => '_care_instructions',
            'label'       => esc_html__( 'Care Instructions', 'aqualuxe' ),
            'placeholder' => esc_html__( 'Enter care instructions for this product', 'aqualuxe' ),
            'description' => esc_html__( 'These instructions will be displayed in the product tabs.', 'aqualuxe' ),
            'rows'        => 6,
        ) );
        
        woocommerce_wp_select( array(
            'id'          => '_difficulty_level',
            'label'       => esc_html__( 'Difficulty Level', 'aqualuxe' ),
            'options'     => array(
                ''           => esc_html__( 'Select difficulty', 'aqualuxe' ),
                'beginner'   => esc_html__( 'Beginner', 'aqualuxe' ),
                'intermediate' => esc_html__( 'Intermediate', 'aqualuxe' ),
                'advanced'   => esc_html__( 'Advanced', 'aqualuxe' ),
                'expert'     => esc_html__( 'Expert', 'aqualuxe' ),
            ),
            'desc_tip'    => true,
            'description' => esc_html__( 'Select the care difficulty level for this product.', 'aqualuxe' ),
        ) );
        ?>
    </div>
    <?php
}
add_action( 'woocommerce_product_data_panels', 'aqualuxe_add_product_data_panels' );

/**
 * Save custom product data
 */
function aqualuxe_save_product_data( $post_id ) {
    $care_instructions = isset( $_POST['_care_instructions'] ) ? wp_kses_post( $_POST['_care_instructions'] ) : '';
    $difficulty_level = isset( $_POST['_difficulty_level'] ) ? sanitize_text_field( $_POST['_difficulty_level'] ) : '';
    
    update_post_meta( $post_id, '_care_instructions', $care_instructions );
    update_post_meta( $post_id, '_difficulty_level', $difficulty_level );
}
add_action( 'woocommerce_process_product_meta', 'aqualuxe_save_product_data' );