<?php
/**
 * AquaLuxe Customizer WooCommerce Section
 *
 * @package AquaLuxe
 * @subpackage Modules/Customizer
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Register WooCommerce section
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager
 * @return void
 */
function aqualuxe_customizer_register_woocommerce_section( $wp_customize ) {
    // Add section
    $wp_customize->add_section( 'aqualuxe_woocommerce', array(
        'title' => __( 'WooCommerce Settings', 'aqualuxe' ),
        'description' => __( 'Customize the WooCommerce settings.', 'aqualuxe' ),
        'panel' => 'aqualuxe_theme_options',
        'priority' => 90,
    ) );
    
    // Shop layout
    $wp_customize->add_setting( 'aqualuxe_shop_layout', array(
        'default' => 'right-sidebar',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_shop_layout', array(
        'label' => __( 'Shop Layout', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
            'left-sidebar' => __( 'Left Sidebar', 'aqualuxe' ),
            'no-sidebar' => __( 'No Sidebar', 'aqualuxe' ),
        ),
    ) );
    
    // Product layout
    $wp_customize->add_setting( 'aqualuxe_product_layout', array(
        'default' => 'right-sidebar',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_layout', array(
        'label' => __( 'Product Layout', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'right-sidebar' => __( 'Right Sidebar', 'aqualuxe' ),
            'left-sidebar' => __( 'Left Sidebar', 'aqualuxe' ),
            'no-sidebar' => __( 'No Sidebar', 'aqualuxe' ),
        ),
    ) );
    
    // Products per row
    $wp_customize->add_setting( 'aqualuxe_products_per_row', array(
        'default' => '4',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_products_per_row', array(
        'label' => __( 'Products Per Row', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
        ),
    ) );
    
    // Products per page
    $wp_customize->add_setting( 'aqualuxe_products_per_page', array(
        'default' => '12',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_products_per_page', array(
        'label' => __( 'Products Per Page', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 100,
            'step' => 1,
        ),
    ) );
    
    // Related products
    $wp_customize->add_setting( 'aqualuxe_related_products', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_related_products', array(
        'label' => __( 'Show Related Products', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Related products count
    $wp_customize->add_setting( 'aqualuxe_related_products_count', array(
        'default' => '4',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_related_products_count', array(
        'label' => __( 'Related Products Count', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
    ) );
    
    // Related products columns
    $wp_customize->add_setting( 'aqualuxe_related_products_columns', array(
        'default' => '4',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_related_products_columns', array(
        'label' => __( 'Related Products Columns', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
        ),
    ) );
    
    // Upsell products
    $wp_customize->add_setting( 'aqualuxe_upsell_products', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_upsell_products', array(
        'label' => __( 'Show Upsell Products', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Upsell products count
    $wp_customize->add_setting( 'aqualuxe_upsell_products_count', array(
        'default' => '4',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_upsell_products_count', array(
        'label' => __( 'Upsell Products Count', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
    ) );
    
    // Upsell products columns
    $wp_customize->add_setting( 'aqualuxe_upsell_products_columns', array(
        'default' => '4',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_upsell_products_columns', array(
        'label' => __( 'Upsell Products Columns', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
        ),
    ) );
    
    // Cross-sell products
    $wp_customize->add_setting( 'aqualuxe_cross_sell_products', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_cross_sell_products', array(
        'label' => __( 'Show Cross-Sell Products', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Cross-sell products count
    $wp_customize->add_setting( 'aqualuxe_cross_sell_products_count', array(
        'default' => '4',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_cross_sell_products_count', array(
        'label' => __( 'Cross-Sell Products Count', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
    ) );
    
    // Cross-sell products columns
    $wp_customize->add_setting( 'aqualuxe_cross_sell_products_columns', array(
        'default' => '2',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_cross_sell_products_columns', array(
        'label' => __( 'Cross-Sell Products Columns', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
        ),
    ) );
    
    // Product gallery zoom
    $wp_customize->add_setting( 'aqualuxe_product_gallery_zoom', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_gallery_zoom', array(
        'label' => __( 'Enable Product Gallery Zoom', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Product gallery lightbox
    $wp_customize->add_setting( 'aqualuxe_product_gallery_lightbox', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_gallery_lightbox', array(
        'label' => __( 'Enable Product Gallery Lightbox', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Product gallery slider
    $wp_customize->add_setting( 'aqualuxe_product_gallery_slider', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_gallery_slider', array(
        'label' => __( 'Enable Product Gallery Slider', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Product tabs
    $wp_customize->add_setting( 'aqualuxe_product_tabs', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_tabs', array(
        'label' => __( 'Show Product Tabs', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Product tabs layout
    $wp_customize->add_setting( 'aqualuxe_product_tabs_layout', array(
        'default' => 'horizontal',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_tabs_layout', array(
        'label' => __( 'Product Tabs Layout', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'horizontal' => __( 'Horizontal', 'aqualuxe' ),
            'vertical' => __( 'Vertical', 'aqualuxe' ),
            'accordion' => __( 'Accordion', 'aqualuxe' ),
        ),
    ) );
    
    // Product breadcrumbs
    $wp_customize->add_setting( 'aqualuxe_product_breadcrumbs', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_breadcrumbs', array(
        'label' => __( 'Show Product Breadcrumbs', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Product navigation
    $wp_customize->add_setting( 'aqualuxe_product_navigation', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_navigation', array(
        'label' => __( 'Show Product Navigation', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Product meta
    $wp_customize->add_setting( 'aqualuxe_product_meta', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_meta', array(
        'label' => __( 'Show Product Meta', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Product sharing
    $wp_customize->add_setting( 'aqualuxe_product_sharing', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_sharing', array(
        'label' => __( 'Show Product Sharing', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Sale badge
    $wp_customize->add_setting( 'aqualuxe_sale_badge', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_sale_badge', array(
        'label' => __( 'Show Sale Badge', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Sale badge style
    $wp_customize->add_setting( 'aqualuxe_sale_badge_style', array(
        'default' => 'circle',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_sale_badge_style', array(
        'label' => __( 'Sale Badge Style', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'circle' => __( 'Circle', 'aqualuxe' ),
            'square' => __( 'Square', 'aqualuxe' ),
            'ribbon' => __( 'Ribbon', 'aqualuxe' ),
            'tag' => __( 'Tag', 'aqualuxe' ),
        ),
    ) );
    
    // Sale badge text
    $wp_customize->add_setting( 'aqualuxe_sale_badge_text', array(
        'default' => __( 'Sale!', 'aqualuxe' ),
        'sanitize_callback' => 'aqualuxe_sanitize_text',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_sale_badge_text', array(
        'label' => __( 'Sale Badge Text', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'text',
    ) );
    
    // Sale badge color
    $wp_customize->add_setting( 'aqualuxe_sale_badge_color', array(
        'default' => '#77a464',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_sale_badge_color', array(
        'label' => __( 'Sale Badge Color', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
    ) ) );
    
    // Sale badge text color
    $wp_customize->add_setting( 'aqualuxe_sale_badge_text_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_sale_badge_text_color', array(
        'label' => __( 'Sale Badge Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
    ) ) );
    
    // New badge
    $wp_customize->add_setting( 'aqualuxe_new_badge', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_new_badge', array(
        'label' => __( 'Show New Badge', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // New badge days
    $wp_customize->add_setting( 'aqualuxe_new_badge_days', array(
        'default' => '30',
        'sanitize_callback' => 'aqualuxe_sanitize_number',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_new_badge_days', array(
        'label' => __( 'New Badge Days', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 100,
            'step' => 1,
        ),
    ) );
    
    // New badge text
    $wp_customize->add_setting( 'aqualuxe_new_badge_text', array(
        'default' => __( 'New!', 'aqualuxe' ),
        'sanitize_callback' => 'aqualuxe_sanitize_text',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_new_badge_text', array(
        'label' => __( 'New Badge Text', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'text',
    ) );
    
    // New badge color
    $wp_customize->add_setting( 'aqualuxe_new_badge_color', array(
        'default' => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_new_badge_color', array(
        'label' => __( 'New Badge Color', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
    ) ) );
    
    // New badge text color
    $wp_customize->add_setting( 'aqualuxe_new_badge_text_color', array(
        'default' => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ) );
    
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'aqualuxe_new_badge_text_color', array(
        'label' => __( 'New Badge Text Color', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
    ) ) );
    
    // Quick view
    $wp_customize->add_setting( 'aqualuxe_quick_view', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_quick_view', array(
        'label' => __( 'Enable Quick View', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Wishlist
    $wp_customize->add_setting( 'aqualuxe_wishlist', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_wishlist', array(
        'label' => __( 'Enable Wishlist', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Compare
    $wp_customize->add_setting( 'aqualuxe_compare', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_compare', array(
        'label' => __( 'Enable Compare', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Ajax add to cart
    $wp_customize->add_setting( 'aqualuxe_ajax_add_to_cart', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_ajax_add_to_cart', array(
        'label' => __( 'Enable Ajax Add to Cart', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Cart fragments
    $wp_customize->add_setting( 'aqualuxe_cart_fragments', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_cart_fragments', array(
        'label' => __( 'Enable Cart Fragments', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Mini cart
    $wp_customize->add_setting( 'aqualuxe_mini_cart', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_mini_cart', array(
        'label' => __( 'Show Mini Cart', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'checkbox',
    ) );
    
    // Mini cart style
    $wp_customize->add_setting( 'aqualuxe_mini_cart_style', array(
        'default' => 'dropdown',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_mini_cart_style', array(
        'label' => __( 'Mini Cart Style', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'dropdown' => __( 'Dropdown', 'aqualuxe' ),
            'offcanvas' => __( 'Off-Canvas', 'aqualuxe' ),
            'popup' => __( 'Popup', 'aqualuxe' ),
        ),
    ) );
    
    // Checkout layout
    $wp_customize->add_setting( 'aqualuxe_checkout_layout', array(
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_checkout_layout', array(
        'label' => __( 'Checkout Layout', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'default' => __( 'Default', 'aqualuxe' ),
            'one-column' => __( 'One Column', 'aqualuxe' ),
            'two-columns' => __( 'Two Columns', 'aqualuxe' ),
            'multistep' => __( 'Multi-Step', 'aqualuxe' ),
        ),
    ) );
    
    // Product title alignment
    $wp_customize->add_setting( 'aqualuxe_product_title_alignment', array(
        'default' => 'center',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_title_alignment', array(
        'label' => __( 'Product Title Alignment', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => aqualuxe_get_alignment_options(),
    ) );
    
    // Product price alignment
    $wp_customize->add_setting( 'aqualuxe_product_price_alignment', array(
        'default' => 'center',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_price_alignment', array(
        'label' => __( 'Product Price Alignment', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => aqualuxe_get_alignment_options(),
    ) );
    
    // Product rating alignment
    $wp_customize->add_setting( 'aqualuxe_product_rating_alignment', array(
        'default' => 'center',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_rating_alignment', array(
        'label' => __( 'Product Rating Alignment', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => aqualuxe_get_alignment_options(),
    ) );
    
    // Product add to cart alignment
    $wp_customize->add_setting( 'aqualuxe_product_add_to_cart_alignment', array(
        'default' => 'center',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_add_to_cart_alignment', array(
        'label' => __( 'Product Add to Cart Alignment', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => aqualuxe_get_alignment_options(),
    ) );
    
    // Product image hover effect
    $wp_customize->add_setting( 'aqualuxe_product_image_hover', array(
        'default' => 'zoom',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_image_hover', array(
        'label' => __( 'Product Image Hover Effect', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'none' => __( 'None', 'aqualuxe' ),
            'zoom' => __( 'Zoom', 'aqualuxe' ),
            'fade' => __( 'Fade', 'aqualuxe' ),
            'flip' => __( 'Flip', 'aqualuxe' ),
            'swap' => __( 'Swap (Second Image)', 'aqualuxe' ),
        ),
    ) );
    
    // Product hover style
    $wp_customize->add_setting( 'aqualuxe_product_hover_style', array(
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
        'transport' => 'refresh',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_product_hover_style', array(
        'label' => __( 'Product Hover Style', 'aqualuxe' ),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'default' => __( 'Default', 'aqualuxe' ),
            'buttons' => __( 'Show Buttons', 'aqualuxe' ),
            'info' => __( 'Show Info', 'aqualuxe' ),
            'shadow' => __( 'Shadow', 'aqualuxe' ),
            'border' => __( 'Border', 'aqualuxe' ),
        ),
    ) );
}