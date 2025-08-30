<?php
/**
 * AquaLuxe Theme Customizer - WooCommerce Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add WooCommerce settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_woocommerce($wp_customize) {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    // Add WooCommerce section
    $wp_customize->add_section('aqualuxe_woocommerce', array(
        'title'    => __('WooCommerce', 'aqualuxe'),
        'priority' => 100,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Shop Layout
    $wp_customize->add_setting('aqualuxe_shop_layout', array(
        'default'           => 'grid',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_shop_layout', array(
        'label'       => __('Shop Layout', 'aqualuxe'),
        'description' => __('Choose the layout for the shop page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            'grid'     => __('Grid', 'aqualuxe'),
            'list'     => __('List', 'aqualuxe'),
            'masonry'  => __('Masonry', 'aqualuxe'),
        ),
    ));
    
    // Shop Columns
    $wp_customize->add_setting('aqualuxe_shop_columns', array(
        'default'           => 3,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_shop_columns', array(
        'label'       => __('Shop Columns', 'aqualuxe'),
        'description' => __('Number of product columns on shop page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 6,
            'step' => 1,
        ),
    ));
    
    // Products Per Page
    $wp_customize->add_setting('aqualuxe_products_per_page', array(
        'default'           => 12,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_products_per_page', array(
        'label'       => __('Products Per Page', 'aqualuxe'),
        'description' => __('Number of products to display per page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 100,
            'step' => 1,
        ),
    ));
    
    // Shop Sidebar
    $wp_customize->add_setting('aqualuxe_shop_sidebar', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_shop_sidebar', array(
        'label'       => __('Show Shop Sidebar', 'aqualuxe'),
        'description' => __('Display sidebar on shop pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Shop Sidebar Position
    $wp_customize->add_setting('aqualuxe_shop_sidebar_position', array(
        'default'           => 'right',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_shop_sidebar_position', array(
        'label'       => __('Shop Sidebar Position', 'aqualuxe'),
        'description' => __('Choose the position for the shop sidebar.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            'left'  => __('Left', 'aqualuxe'),
            'right' => __('Right', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_shop_sidebar', true);
        },
    ));
    
    // Product Layout
    $wp_customize->add_setting('aqualuxe_product_layout', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_product_layout', array(
        'label'       => __('Single Product Layout', 'aqualuxe'),
        'description' => __('Choose the layout for single product pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            'default'     => __('Default', 'aqualuxe'),
            'full-width'  => __('Full Width', 'aqualuxe'),
            'stacked'     => __('Stacked', 'aqualuxe'),
            'gallery-left' => __('Gallery Left', 'aqualuxe'),
            'gallery-right' => __('Gallery Right', 'aqualuxe'),
        ),
    ));
    
    // Product Sidebar
    $wp_customize->add_setting('aqualuxe_product_sidebar', array(
        'default'           => false,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_product_sidebar', array(
        'label'       => __('Show Product Sidebar', 'aqualuxe'),
        'description' => __('Display sidebar on single product pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Product Gallery Style
    $wp_customize->add_setting('aqualuxe_product_gallery_style', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_product_gallery_style', array(
        'label'       => __('Product Gallery Style', 'aqualuxe'),
        'description' => __('Choose the style for product galleries.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            'default'     => __('Default', 'aqualuxe'),
            'horizontal'  => __('Horizontal Thumbnails', 'aqualuxe'),
            'vertical'    => __('Vertical Thumbnails', 'aqualuxe'),
            'grid'        => __('Grid Gallery', 'aqualuxe'),
            'sticky'      => __('Sticky Gallery', 'aqualuxe'),
        ),
    ));
    
    // Product Card Style
    $wp_customize->add_setting('aqualuxe_product_card_style', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_product_card_style', array(
        'label'       => __('Product Card Style', 'aqualuxe'),
        'description' => __('Choose the style for product cards in shop pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            'default'     => __('Default', 'aqualuxe'),
            'minimal'     => __('Minimal', 'aqualuxe'),
            'bordered'    => __('Bordered', 'aqualuxe'),
            'shadow'      => __('Shadow', 'aqualuxe'),
            'overlay'     => __('Info Overlay', 'aqualuxe'),
        ),
    ));
    
    // Show Product Rating
    $wp_customize->add_setting('aqualuxe_product_show_rating', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_product_show_rating', array(
        'label'       => __('Show Product Rating', 'aqualuxe'),
        'description' => __('Display product rating on product cards.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Show Product Price
    $wp_customize->add_setting('aqualuxe_product_show_price', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_product_show_price', array(
        'label'       => __('Show Product Price', 'aqualuxe'),
        'description' => __('Display product price on product cards.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Show Product Category
    $wp_customize->add_setting('aqualuxe_product_show_category', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_product_show_category', array(
        'label'       => __('Show Product Category', 'aqualuxe'),
        'description' => __('Display product category on product cards.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Show Quick View
    $wp_customize->add_setting('aqualuxe_product_show_quick_view', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_product_show_quick_view', array(
        'label'       => __('Show Quick View', 'aqualuxe'),
        'description' => __('Display quick view button on product cards.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Show Wishlist
    $wp_customize->add_setting('aqualuxe_product_show_wishlist', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_product_show_wishlist', array(
        'label'       => __('Show Wishlist', 'aqualuxe'),
        'description' => __('Display wishlist button on product cards.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Show Compare
    $wp_customize->add_setting('aqualuxe_product_show_compare', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_product_show_compare', array(
        'label'       => __('Show Compare', 'aqualuxe'),
        'description' => __('Display compare button on product cards.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Sale Badge Style
    $wp_customize->add_setting('aqualuxe_sale_badge_style', array(
        'default'           => 'circle',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_sale_badge_style', array(
        'label'       => __('Sale Badge Style', 'aqualuxe'),
        'description' => __('Choose the style for sale badges.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            'circle'     => __('Circle', 'aqualuxe'),
            'square'     => __('Square', 'aqualuxe'),
            'rounded'    => __('Rounded', 'aqualuxe'),
            'ribbon'     => __('Ribbon', 'aqualuxe'),
            'minimal'    => __('Minimal', 'aqualuxe'),
        ),
    ));
    
    // Sale Badge Text
    $wp_customize->add_setting('aqualuxe_sale_badge_text', array(
        'default'           => __('Sale!', 'aqualuxe'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_sale_badge_text', array(
        'label'       => __('Sale Badge Text', 'aqualuxe'),
        'description' => __('Text to display on sale badges.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'text',
    ));
    
    // Show Percentage
    $wp_customize->add_setting('aqualuxe_sale_badge_percentage', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_sale_badge_percentage', array(
        'label'       => __('Show Discount Percentage', 'aqualuxe'),
        'description' => __('Display discount percentage on sale badges.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Sale Badge Color
    $wp_customize->add_setting('aqualuxe_sale_badge_color', array(
        'default'           => '#e2401c',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_sale_badge_color', array(
        'label'       => __('Sale Badge Color', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
    )));
    
    // Out of Stock Badge
    $wp_customize->add_setting('aqualuxe_show_out_of_stock_badge', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_out_of_stock_badge', array(
        'label'       => __('Show Out of Stock Badge', 'aqualuxe'),
        'description' => __('Display out of stock badge on product cards.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Out of Stock Badge Text
    $wp_customize->add_setting('aqualuxe_out_of_stock_badge_text', array(
        'default'           => __('Out of Stock', 'aqualuxe'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_out_of_stock_badge_text', array(
        'label'       => __('Out of Stock Badge Text', 'aqualuxe'),
        'description' => __('Text to display on out of stock badges.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'text',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_out_of_stock_badge', true);
        },
    ));
    
    // Out of Stock Badge Color
    $wp_customize->add_setting('aqualuxe_out_of_stock_badge_color', array(
        'default'           => '#888888',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_out_of_stock_badge_color', array(
        'label'       => __('Out of Stock Badge Color', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_out_of_stock_badge', true);
        },
    )));
    
    // New Badge
    $wp_customize->add_setting('aqualuxe_show_new_badge', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_new_badge', array(
        'label'       => __('Show New Badge', 'aqualuxe'),
        'description' => __('Display new badge on recently added products.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // New Badge Duration
    $wp_customize->add_setting('aqualuxe_new_badge_duration', array(
        'default'           => 7,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_new_badge_duration', array(
        'label'       => __('New Badge Duration (days)', 'aqualuxe'),
        'description' => __('Number of days to display new badge after product creation.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 30,
            'step' => 1,
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_new_badge', true);
        },
    ));
    
    // New Badge Text
    $wp_customize->add_setting('aqualuxe_new_badge_text', array(
        'default'           => __('New!', 'aqualuxe'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_new_badge_text', array(
        'label'       => __('New Badge Text', 'aqualuxe'),
        'description' => __('Text to display on new badges.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'text',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_new_badge', true);
        },
    ));
    
    // New Badge Color
    $wp_customize->add_setting('aqualuxe_new_badge_color', array(
        'default'           => '#6d9c3f',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_new_badge_color', array(
        'label'       => __('New Badge Color', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_new_badge', true);
        },
    )));
    
    // Featured Badge
    $wp_customize->add_setting('aqualuxe_show_featured_badge', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_featured_badge', array(
        'label'       => __('Show Featured Badge', 'aqualuxe'),
        'description' => __('Display featured badge on featured products.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Featured Badge Text
    $wp_customize->add_setting('aqualuxe_featured_badge_text', array(
        'default'           => __('Featured', 'aqualuxe'),
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_featured_badge_text', array(
        'label'       => __('Featured Badge Text', 'aqualuxe'),
        'description' => __('Text to display on featured badges.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'text',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_featured_badge', true);
        },
    ));
    
    // Featured Badge Color
    $wp_customize->add_setting('aqualuxe_featured_badge_color', array(
        'default'           => '#4e00c2',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_featured_badge_color', array(
        'label'       => __('Featured Badge Color', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_featured_badge', true);
        },
    )));
    
    // Cart Layout
    $wp_customize->add_setting('aqualuxe_cart_layout', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_cart_layout', array(
        'label'       => __('Cart Page Layout', 'aqualuxe'),
        'description' => __('Choose the layout for the cart page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            'default'     => __('Default', 'aqualuxe'),
            'modern'      => __('Modern', 'aqualuxe'),
            'compact'     => __('Compact', 'aqualuxe'),
            'two-column'  => __('Two Column', 'aqualuxe'),
        ),
    ));
    
    // Checkout Layout
    $wp_customize->add_setting('aqualuxe_checkout_layout', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_checkout_layout', array(
        'label'       => __('Checkout Page Layout', 'aqualuxe'),
        'description' => __('Choose the layout for the checkout page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            'default'     => __('Default', 'aqualuxe'),
            'modern'      => __('Modern', 'aqualuxe'),
            'compact'     => __('Compact', 'aqualuxe'),
            'two-column'  => __('Two Column', 'aqualuxe'),
            'multi-step'  => __('Multi-Step', 'aqualuxe'),
        ),
    ));
    
    // Show Cross-Sells
    $wp_customize->add_setting('aqualuxe_show_cross_sells', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_cross_sells', array(
        'label'       => __('Show Cross-Sells', 'aqualuxe'),
        'description' => __('Display cross-sells on cart page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Show Related Products
    $wp_customize->add_setting('aqualuxe_show_related_products', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_related_products', array(
        'label'       => __('Show Related Products', 'aqualuxe'),
        'description' => __('Display related products on single product pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Related Products Count
    $wp_customize->add_setting('aqualuxe_related_products_count', array(
        'default'           => 4,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_related_products_count', array(
        'label'       => __('Related Products Count', 'aqualuxe'),
        'description' => __('Number of related products to display.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 12,
            'step' => 1,
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_related_products', true);
        },
    ));
    
    // Show Upsells
    $wp_customize->add_setting('aqualuxe_show_upsells', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_upsells', array(
        'label'       => __('Show Upsells', 'aqualuxe'),
        'description' => __('Display upsell products on single product pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Upsells Count
    $wp_customize->add_setting('aqualuxe_upsells_count', array(
        'default'           => 4,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_upsells_count', array(
        'label'       => __('Upsells Count', 'aqualuxe'),
        'description' => __('Number of upsell products to display.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 12,
            'step' => 1,
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_upsells', true);
        },
    ));
    
    // Show Product Tabs
    $wp_customize->add_setting('aqualuxe_show_product_tabs', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_product_tabs', array(
        'label'       => __('Show Product Tabs', 'aqualuxe'),
        'description' => __('Display tabs on single product pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Product Tabs Style
    $wp_customize->add_setting('aqualuxe_product_tabs_style', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_product_tabs_style', array(
        'label'       => __('Product Tabs Style', 'aqualuxe'),
        'description' => __('Choose the style for product tabs.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            'default'     => __('Default', 'aqualuxe'),
            'vertical'    => __('Vertical', 'aqualuxe'),
            'accordion'   => __('Accordion', 'aqualuxe'),
            'toggle'      => __('Toggle', 'aqualuxe'),
            'minimal'     => __('Minimal', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_product_tabs', true);
        },
    ));
    
    // Show Stock Status
    $wp_customize->add_setting('aqualuxe_show_stock_status', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_stock_status', array(
        'label'       => __('Show Stock Status', 'aqualuxe'),
        'description' => __('Display stock status on single product pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Show SKU
    $wp_customize->add_setting('aqualuxe_show_sku', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_sku', array(
        'label'       => __('Show SKU', 'aqualuxe'),
        'description' => __('Display SKU on single product pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Show Categories
    $wp_customize->add_setting('aqualuxe_show_product_categories', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_product_categories', array(
        'label'       => __('Show Categories', 'aqualuxe'),
        'description' => __('Display categories on single product pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Show Tags
    $wp_customize->add_setting('aqualuxe_show_product_tags', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_product_tags', array(
        'label'       => __('Show Tags', 'aqualuxe'),
        'description' => __('Display tags on single product pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Show Breadcrumbs
    $wp_customize->add_setting('aqualuxe_show_product_breadcrumbs', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_product_breadcrumbs', array(
        'label'       => __('Show Breadcrumbs', 'aqualuxe'),
        'description' => __('Display breadcrumbs on WooCommerce pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Breadcrumbs Style
    $wp_customize->add_setting('aqualuxe_product_breadcrumbs_style', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_product_breadcrumbs_style', array(
        'label'       => __('Breadcrumbs Style', 'aqualuxe'),
        'description' => __('Choose the style for breadcrumbs.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            'default'     => __('Default', 'aqualuxe'),
            'minimal'     => __('Minimal', 'aqualuxe'),
            'centered'    => __('Centered', 'aqualuxe'),
            'background'  => __('With Background', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_product_breadcrumbs', true);
        },
    ));
    
    // Shop Page Title
    $wp_customize->add_setting('aqualuxe_show_shop_title', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_shop_title', array(
        'label'       => __('Show Shop Title', 'aqualuxe'),
        'description' => __('Display title on shop page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Shop Page Description
    $wp_customize->add_setting('aqualuxe_show_shop_description', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_shop_description', array(
        'label'       => __('Show Shop Description', 'aqualuxe'),
        'description' => __('Display description on shop page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Show Result Count
    $wp_customize->add_setting('aqualuxe_show_result_count', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_result_count', array(
        'label'       => __('Show Result Count', 'aqualuxe'),
        'description' => __('Display result count on shop page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Show Catalog Ordering
    $wp_customize->add_setting('aqualuxe_show_catalog_ordering', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_catalog_ordering', array(
        'label'       => __('Show Catalog Ordering', 'aqualuxe'),
        'description' => __('Display catalog ordering on shop page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Show Product Filters
    $wp_customize->add_setting('aqualuxe_show_product_filters', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_product_filters', array(
        'label'       => __('Show Product Filters', 'aqualuxe'),
        'description' => __('Display product filters on shop page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    // Product Filters Style
    $wp_customize->add_setting('aqualuxe_product_filters_style', array(
        'default'           => 'sidebar',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_product_filters_style', array(
        'label'       => __('Product Filters Style', 'aqualuxe'),
        'description' => __('Choose the style for product filters.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            'sidebar'     => __('Sidebar', 'aqualuxe'),
            'horizontal'  => __('Horizontal', 'aqualuxe'),
            'dropdown'    => __('Dropdown', 'aqualuxe'),
            'offcanvas'   => __('Off-Canvas', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_product_filters', true);
        },
    ));
}

// Add the WooCommerce section to the customizer
add_action('customize_register', 'aqualuxe_customize_register_woocommerce');

/**
 * Add WooCommerce CSS to the head.
 */
function aqualuxe_woocommerce_css() {
    // Check if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $shop_columns = get_theme_mod('aqualuxe_shop_columns', 3);
    $product_card_style = get_theme_mod('aqualuxe_product_card_style', 'default');
    $sale_badge_style = get_theme_mod('aqualuxe_sale_badge_style', 'circle');
    $sale_badge_text = get_theme_mod('aqualuxe_sale_badge_text', __('Sale!', 'aqualuxe'));
    $sale_badge_color = get_theme_mod('aqualuxe_sale_badge_color', '#e2401c');
    $show_out_of_stock_badge = get_theme_mod('aqualuxe_show_out_of_stock_badge', true);
    $out_of_stock_badge_text = get_theme_mod('aqualuxe_out_of_stock_badge_text', __('Out of Stock', 'aqualuxe'));
    $out_of_stock_badge_color = get_theme_mod('aqualuxe_out_of_stock_badge_color', '#888888');
    $show_new_badge = get_theme_mod('aqualuxe_show_new_badge', true);
    $new_badge_text = get_theme_mod('aqualuxe_new_badge_text', __('New!', 'aqualuxe'));
    $new_badge_color = get_theme_mod('aqualuxe_new_badge_color', '#6d9c3f');
    $show_featured_badge = get_theme_mod('aqualuxe_show_featured_badge', true);
    $featured_badge_text = get_theme_mod('aqualuxe_featured_badge_text', __('Featured', 'aqualuxe'));
    $featured_badge_color = get_theme_mod('aqualuxe_featured_badge_color', '#4e00c2');
    $product_tabs_style = get_theme_mod('aqualuxe_product_tabs_style', 'default');
    $product_breadcrumbs_style = get_theme_mod('aqualuxe_product_breadcrumbs_style', 'default');
    $product_filters_style = get_theme_mod('aqualuxe_product_filters_style', 'sidebar');
    
    ?>
    <style type="text/css">
        /* Shop Columns */
        .woocommerce ul.products {
            display: grid;
            grid-template-columns: repeat(<?php echo esc_attr($shop_columns); ?>, 1fr);
            grid-gap: 30px;
        }
        
        @media (max-width: 991px) {
            .woocommerce ul.products {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 575px) {
            .woocommerce ul.products {
                grid-template-columns: 1fr;
            }
        }
        
        /* Product Card Styles */
        <?php if ($product_card_style === 'minimal') : ?>
        .woocommerce ul.products li.product {
            text-align: center;
            border: none;
            box-shadow: none;
            padding: 0;
        }
        
        .woocommerce ul.products li.product .button {
            display: inline-block;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }
        
        .woocommerce ul.products li.product:hover .button {
            opacity: 1;
            transform: translateY(0);
        }
        <?php elseif ($product_card_style === 'bordered') : ?>
        .woocommerce ul.products li.product {
            border: 1px solid #eee;
            border-radius: 5px;
            padding: 15px;
            transition: all 0.3s ease;
        }
        
        .woocommerce ul.products li.product:hover {
            border-color: #ddd;
        }
        <?php elseif ($product_card_style === 'shadow') : ?>
        .woocommerce ul.products li.product {
            border: none;
            border-radius: 5px;
            padding: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .woocommerce ul.products li.product:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            transform: translateY(-5px);
        }
        <?php elseif ($product_card_style === 'overlay') : ?>
        .woocommerce ul.products li.product {
            position: relative;
            overflow: hidden;
        }
        
        .woocommerce ul.products li.product .product-details {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 15px;
            transform: translateY(100%);
            transition: all 0.3s ease;
        }
        
        .woocommerce ul.products li.product:hover .product-details {
            transform: translateY(0);
        }
        <?php endif; ?>
        
        /* Sale Badge Styles */
        <?php if ($sale_badge_style === 'circle') : ?>
        .woocommerce span.onsale {
            min-height: 3.236em;
            min-width: 3.236em;
            padding: 0;
            font-size: 1em;
            font-weight: 700;
            position: absolute;
            text-align: center;
            line-height: 3.236;
            top: -0.5em;
            left: -0.5em;
            margin: 0;
            border-radius: 100%;
            background-color: <?php echo esc_attr($sale_badge_color); ?>;
            color: #fff;
            z-index: 9;
        }
        <?php elseif ($sale_badge_style === 'square') : ?>
        .woocommerce span.onsale {
            min-height: 3.236em;
            min-width: 3.236em;
            padding: 0;
            font-size: 1em;
            font-weight: 700;
            position: absolute;
            text-align: center;
            line-height: 3.236;
            top: 0;
            left: 0;
            margin: 0;
            border-radius: 0;
            background-color: <?php echo esc_attr($sale_badge_color); ?>;
            color: #fff;
            z-index: 9;
        }
        <?php elseif ($sale_badge_style === 'rounded') : ?>
        .woocommerce span.onsale {
            min-height: 2em;
            min-width: auto;
            padding: 0.5em 1em;
            font-size: 0.857em;
            font-weight: 700;
            position: absolute;
            text-align: center;
            line-height: 1.5;
            top: 1em;
            left: 1em;
            margin: 0;
            border-radius: 3px;
            background-color: <?php echo esc_attr($sale_badge_color); ?>;
            color: #fff;
            z-index: 9;
        }
        <?php elseif ($sale_badge_style === 'ribbon') : ?>
        .woocommerce span.onsale {
            min-height: 2em;
            min-width: auto;
            padding: 0.5em 1em;
            font-size: 0.857em;
            font-weight: 700;
            position: absolute;
            text-align: center;
            line-height: 1.5;
            top: 1em;
            left: -0.5em;
            margin: 0;
            border-radius: 0;
            background-color: <?php echo esc_attr($sale_badge_color); ?>;
            color: #fff;
            z-index: 9;
        }
        
        .woocommerce span.onsale:before {
            content: '';
            position: absolute;
            bottom: -0.5em;
            left: 0;
            border-width: 0.5em 0.5em 0 0;
            border-style: solid;
            border-color: <?php echo esc_attr($sale_badge_color); ?> transparent transparent transparent;
            filter: brightness(70%);
        }
        <?php elseif ($sale_badge_style === 'minimal') : ?>
        .woocommerce span.onsale {
            min-height: auto;
            min-width: auto;
            padding: 0.25em 0.5em;
            font-size: 0.857em;
            font-weight: 400;
            position: absolute;
            text-align: center;
            line-height: 1.5;
            top: 1em;
            left: 1em;
            margin: 0;
            border-radius: 2px;
            background-color: <?php echo esc_attr($sale_badge_color); ?>;
            color: #fff;
            z-index: 9;
        }
        <?php endif; ?>
        
        /* Out of Stock Badge */
        <?php if ($show_out_of_stock_badge) : ?>
        .woocommerce ul.products li.product .outofstock-badge {
            min-height: auto;
            min-width: auto;
            padding: 0.5em 1em;
            font-size: 0.857em;
            font-weight: 700;
            position: absolute;
            text-align: center;
            line-height: 1.5;
            top: 1em;
            right: 1em;
            margin: 0;
            border-radius: 3px;
            background-color: <?php echo esc_attr($out_of_stock_badge_color); ?>;
            color: #fff;
            z-index: 9;
        }
        <?php endif; ?>
        
        /* New Badge */
        <?php if ($show_new_badge) : ?>
        .woocommerce ul.products li.product .new-badge {
            min-height: auto;
            min-width: auto;
            padding: 0.5em 1em;
            font-size: 0.857em;
            font-weight: 700;
            position: absolute;
            text-align: center;
            line-height: 1.5;
            top: 1em;
            <?php if ($show_out_of_stock_badge) : ?>
            right: 1em;
            margin-top: 3em;
            <?php else : ?>
            right: 1em;
            <?php endif; ?>
            border-radius: 3px;
            background-color: <?php echo esc_attr($new_badge_color); ?>;
            color: #fff;
            z-index: 9;
        }
        <?php endif; ?>
        
        /* Featured Badge */
        <?php if ($show_featured_badge) : ?>
        .woocommerce ul.products li.product .featured-badge {
            min-height: auto;
            min-width: auto;
            padding: 0.5em 1em;
            font-size: 0.857em;
            font-weight: 700;
            position: absolute;
            text-align: center;
            line-height: 1.5;
            top: 1em;
            <?php if ($show_new_badge && $show_out_of_stock_badge) : ?>
            right: 1em;
            margin-top: 6em;
            <?php elseif ($show_new_badge || $show_out_of_stock_badge) : ?>
            right: 1em;
            margin-top: 3em;
            <?php else : ?>
            right: 1em;
            <?php endif; ?>
            border-radius: 3px;
            background-color: <?php echo esc_attr($featured_badge_color); ?>;
            color: #fff;
            z-index: 9;
        }
        <?php endif; ?>
        
        /* Product Tabs Styles */
        <?php if ($product_tabs_style === 'vertical') : ?>
        .woocommerce div.product .woocommerce-tabs {
            display: flex;
            flex-wrap: wrap;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs {
            flex: 0 0 200px;
            margin: 0;
            padding: 0;
            border-right: 1px solid #d3ced2;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs li {
            display: block;
            margin: 0;
            border: none;
            background-color: transparent;
            border-radius: 0;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs li a {
            display: block;
            padding: 10px 15px;
            font-weight: 600;
            color: #6d6d6d;
            text-decoration: none;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs li.active {
            background-color: #f8f8f8;
            border-left: 3px solid <?php echo esc_attr($sale_badge_color); ?>;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs li.active a {
            color: #333;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs::before,
        .woocommerce div.product .woocommerce-tabs ul.tabs li::before,
        .woocommerce div.product .woocommerce-tabs ul.tabs li::after {
            display: none;
        }
        
        .woocommerce div.product .woocommerce-tabs .panel {
            flex: 1;
            padding: 20px;
            margin: 0;
            border: none;
        }
        <?php elseif ($product_tabs_style === 'accordion') : ?>
        .woocommerce div.product .woocommerce-tabs ul.tabs {
            display: none;
        }
        
        .woocommerce div.product .woocommerce-tabs .panel {
            display: none;
            padding: 15px;
            border: 1px solid #d3ced2;
            margin-bottom: 10px;
        }
        
        .woocommerce div.product .woocommerce-tabs .panel.active {
            display: block;
        }
        
        .woocommerce div.product .woocommerce-tabs .accordion-heading {
            display: block;
            padding: 10px 15px;
            background-color: #f8f8f8;
            border: 1px solid #d3ced2;
            margin-bottom: 5px;
            font-weight: 600;
            cursor: pointer;
            position: relative;
        }
        
        .woocommerce div.product .woocommerce-tabs .accordion-heading:after {
            content: '+';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .woocommerce div.product .woocommerce-tabs .accordion-heading.active:after {
            content: '-';
        }
        <?php elseif ($product_tabs_style === 'toggle') : ?>
        .woocommerce div.product .woocommerce-tabs ul.tabs {
            display: flex;
            flex-wrap: wrap;
            padding: 0;
            margin: 0 0 20px;
            border-bottom: 1px solid #d3ced2;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs li {
            background-color: transparent;
            border: none;
            border-radius: 0;
            margin: 0 30px 0 0;
            padding: 0 0 10px;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs li a {
            padding: 0;
            font-weight: 600;
            color: #6d6d6d;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs li.active {
            background-color: transparent;
            border-bottom: 3px solid <?php echo esc_attr($sale_badge_color); ?>;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs li.active a {
            color: #333;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs::before,
        .woocommerce div.product .woocommerce-tabs ul.tabs li::before,
        .woocommerce div.product .woocommerce-tabs ul.tabs li::after {
            display: none;
        }
        <?php elseif ($product_tabs_style === 'minimal') : ?>
        .woocommerce div.product .woocommerce-tabs ul.tabs {
            display: flex;
            flex-wrap: wrap;
            padding: 0;
            margin: 0 0 20px;
            border-bottom: 1px solid #d3ced2;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs li {
            background-color: transparent;
            border: none;
            border-radius: 0;
            margin: 0 20px 0 0;
            padding: 0 0 10px;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs li a {
            padding: 0;
            font-weight: 400;
            color: #6d6d6d;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs li.active {
            background-color: transparent;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs li.active a {
            color: #333;
            font-weight: 600;
        }
        
        .woocommerce div.product .woocommerce-tabs ul.tabs::before,
        .woocommerce div.product .woocommerce-tabs ul.tabs li::before,
        .woocommerce div.product .woocommerce-tabs ul.tabs li::after {
            display: none;
        }
        <?php endif; ?>
        
        /* Breadcrumbs Styles */
        <?php if ($product_breadcrumbs_style === 'minimal') : ?>
        .woocommerce .woocommerce-breadcrumb {
            margin: 0 0 1em;
            padding: 0;
            font-size: 0.85em;
            color: #767676;
        }
        
        .woocommerce .woocommerce-breadcrumb a {
            color: #333;
        }
        <?php elseif ($product_breadcrumbs_style === 'centered') : ?>
        .woocommerce .woocommerce-breadcrumb {
            margin: 0 0 2em;
            padding: 1em 0;
            font-size: 0.85em;
            color: #767676;
            text-align: center;
        }
        
        .woocommerce .woocommerce-breadcrumb a {
            color: #333;
        }
        <?php elseif ($product_breadcrumbs_style === 'background') : ?>
        .woocommerce .woocommerce-breadcrumb {
            margin: 0 0 2em;
            padding: 1em;
            font-size: 0.85em;
            color: #767676;
            background-color: #f8f8f8;
            border-radius: 3px;
        }
        
        .woocommerce .woocommerce-breadcrumb a {
            color: #333;
        }
        <?php endif; ?>
        
        /* Product Filters Styles */
        <?php if ($product_filters_style === 'horizontal') : ?>
        .woocommerce .widget-area {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f8f8f8;
            border-radius: 3px;
        }
        
        .woocommerce .widget-area .widget {
            flex: 1 1 200px;
            margin: 0 15px 15px 0;
            padding: 0;
            border: none;
        }
        
        .woocommerce .widget-area .widget h3 {
            font-size: 1em;
            margin-bottom: 10px;
        }
        <?php elseif ($product_filters_style === 'dropdown') : ?>
        .woocommerce .widget-area {
            margin-bottom: 30px;
        }
        
        .woocommerce .widget-area .widget {
            margin: 0 0 15px;
            padding: 0;
            border: none;
        }
        
        .woocommerce .widget-area .widget h3 {
            font-size: 1em;
            margin: 0;
            padding: 10px 15px;
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 3px;
            cursor: pointer;
            position: relative;
        }
        
        .woocommerce .widget-area .widget h3:after {
            content: '+';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .woocommerce .widget-area .widget.active h3:after {
            content: '-';
        }
        
        .woocommerce .widget-area .widget .widget-content {
            display: none;
            padding: 15px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 3px 3px;
        }
        
        .woocommerce .widget-area .widget.active .widget-content {
            display: block;
        }
        <?php elseif ($product_filters_style === 'offcanvas') : ?>
        .woocommerce .widget-area {
            position: fixed;
            top: 0;
            left: -300px;
            width: 300px;
            height: 100%;
            background-color: #fff;
            z-index: 9999;
            padding: 20px;
            overflow-y: auto;
            transition: all 0.3s ease;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .woocommerce .widget-area.active {
            left: 0;
        }
        
        .woocommerce .widget-area .widget {
            margin: 0 0 20px;
            padding: 0;
            border: none;
        }
        
        .woocommerce .widget-area .widget h3 {
            font-size: 1em;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .woocommerce .filter-toggle {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 15px;
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 3px;
            cursor: pointer;
        }
        
        .woocommerce .filter-close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5em;
            cursor: pointer;
        }
        
        .woocommerce .filter-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9998;
        }
        
        .woocommerce .filter-overlay.active {
            display: block;
        }
        <?php endif; ?>
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_woocommerce_css');

/**
 * Set number of products per page.
 *
 * @param int $products_per_page Number of products per page.
 * @return int Modified number of products per page.
 */
function aqualuxe_products_per_page($products_per_page) {
    return get_theme_mod('aqualuxe_products_per_page', 12);
}
add_filter('loop_shop_per_page', 'aqualuxe_products_per_page', 20);

/**
 * Add badges to product thumbnails.
 */
function aqualuxe_product_badges() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Sale badge is handled by WooCommerce
    
    // Out of Stock badge
    if (get_theme_mod('aqualuxe_show_out_of_stock_badge', true) && !$product->is_in_stock()) {
        $out_of_stock_text = get_theme_mod('aqualuxe_out_of_stock_badge_text', __('Out of Stock', 'aqualuxe'));
        echo '<span class="outofstock-badge">' . esc_html($out_of_stock_text) . '</span>';
    }
    
    // New badge
    if (get_theme_mod('aqualuxe_show_new_badge', true)) {
        $new_badge_duration = get_theme_mod('aqualuxe_new_badge_duration', 7);
        $new_badge_text = get_theme_mod('aqualuxe_new_badge_text', __('New!', 'aqualuxe'));
        
        $post_date = get_the_time('U');
        $current_date = current_time('timestamp');
        $days_difference = floor(($current_date - $post_date) / (60 * 60 * 24));
        
        if ($days_difference < $new_badge_duration) {
            echo '<span class="new-badge">' . esc_html($new_badge_text) . '</span>';
        }
    }
    
    // Featured badge
    if (get_theme_mod('aqualuxe_show_featured_badge', true) && $product->is_featured()) {
        $featured_badge_text = get_theme_mod('aqualuxe_featured_badge_text', __('Featured', 'aqualuxe'));
        echo '<span class="featured-badge">' . esc_html($featured_badge_text) . '</span>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_product_badges', 10);

/**
 * Modify sale flash.
 *
 * @param string $html Sale flash HTML.
 * @param WP_Post $post Post object.
 * @param WC_Product $product Product object.
 * @return string Modified sale flash HTML.
 */
function aqualuxe_sale_flash($html, $post, $product) {
    if (!$product->is_on_sale()) {
        return $html;
    }
    
    $sale_text = get_theme_mod('aqualuxe_sale_badge_text', __('Sale!', 'aqualuxe'));
    
    if (get_theme_mod('aqualuxe_sale_badge_percentage', true) && $product->get_type() === 'simple') {
        $regular_price = (float) $product->get_regular_price();
        $sale_price = (float) $product->get_sale_price();
        
        if ($regular_price > 0) {
            $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
            $sale_text = sprintf(__('%d%%', 'aqualuxe'), $percentage);
        }
    }
    
    return '<span class="onsale">' . esc_html($sale_text) . '</span>';
}
add_filter('woocommerce_sale_flash', 'aqualuxe_sale_flash', 10, 3);

/**
 * Modify product tabs.
 *
 * @param array $tabs Product tabs.
 * @return array Modified product tabs.
 */
function aqualuxe_product_tabs($tabs) {
    if (!get_theme_mod('aqualuxe_show_product_tabs', true)) {
        return array();
    }
    
    return $tabs;
}
add_filter('woocommerce_product_tabs', 'aqualuxe_product_tabs', 98);

/**
 * Add accordion headings for product tabs.
 */
function aqualuxe_product_tabs_accordion() {
    if (get_theme_mod('aqualuxe_product_tabs_style', 'default') !== 'accordion') {
        return;
    }
    
    $tabs = apply_filters('woocommerce_product_tabs', array());
    
    if (!empty($tabs)) {
        echo '<div class="accordion-tabs">';
        
        foreach ($tabs as $key => $tab) {
            echo '<h3 class="accordion-heading" data-tab="' . esc_attr($key) . '">' . esc_html($tab['title']) . '</h3>';
            echo '<div class="panel" id="tab-' . esc_attr($key) . '">';
            call_user_func($tab['callback'], $key, $tab);
            echo '</div>';
        }
        
        echo '</div>';
    }
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_product_tabs_accordion', 9);

/**
 * Remove default product tabs if using accordion style.
 */
function aqualuxe_remove_default_product_tabs() {
    if (get_theme_mod('aqualuxe_product_tabs_style', 'default') === 'accordion') {
        remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    }
}
add_action('wp', 'aqualuxe_remove_default_product_tabs');

/**
 * Modify related products arguments.
 *
 * @param array $args Related products arguments.
 * @return array Modified related products arguments.
 */
function aqualuxe_related_products_args($args) {
    if (!get_theme_mod('aqualuxe_show_related_products', true)) {
        return array('posts_per_page' => 0);
    }
    
    $args['posts_per_page'] = get_theme_mod('aqualuxe_related_products_count', 4);
    $args['columns'] = get_theme_mod('aqualuxe_shop_columns', 3);
    
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'aqualuxe_related_products_args');

/**
 * Modify upsell products arguments.
 *
 * @param array $args Upsell products arguments.
 * @return array Modified upsell products arguments.
 */
function aqualuxe_upsell_products_args($args) {
    if (!get_theme_mod('aqualuxe_show_upsells', true)) {
        return array('posts_per_page' => 0);
    }
    
    $args['posts_per_page'] = get_theme_mod('aqualuxe_upsells_count', 4);
    $args['columns'] = get_theme_mod('aqualuxe_shop_columns', 3);
    
    return $args;
}
add_filter('woocommerce_upsell_display_args', 'aqualuxe_upsell_products_args');

/**
 * Modify cross-sell products arguments.
 *
 * @param array $args Cross-sell products arguments.
 * @return array Modified cross-sell products arguments.
 */
function aqualuxe_cross_sell_products_args($args) {
    if (!get_theme_mod('aqualuxe_show_cross_sells', true)) {
        return array('posts_per_page' => 0);
    }
    
    $args['posts_per_page'] = 2;
    $args['columns'] = 2;
    
    return $args;
}
add_filter('woocommerce_cross_sells_args', 'aqualuxe_cross_sell_products_args');

/**
 * Modify product meta visibility.
 */
function aqualuxe_product_meta() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    echo '<div class="product_meta">';
    
    if (get_theme_mod('aqualuxe_show_sku', true) && wc_product_sku_enabled() && ($product->get_sku() || $product->is_type('variable'))) {
        echo '<span class="sku_wrapper">' . esc_html__('SKU:', 'aqualuxe') . ' <span class="sku">' . (($sku = $product->get_sku()) ? $sku : esc_html__('N/A', 'aqualuxe')) . '</span></span>';
    }
    
    if (get_theme_mod('aqualuxe_show_product_categories', true)) {
        echo wc_get_product_category_list($product->get_id(), ', ', '<span class="posted_in">' . _n('Category:', 'Categories:', count($product->get_category_ids()), 'aqualuxe') . ' ', '</span>');
    }
    
    if (get_theme_mod('aqualuxe_show_product_tags', true)) {
        echo wc_get_product_tag_list($product->get_id(), ', ', '<span class="tagged_as">' . _n('Tag:', 'Tags:', count($product->get_tag_ids()), 'aqualuxe') . ' ', '</span>');
    }
    
    echo '</div>';
}
add_action('woocommerce_single_product_summary', 'aqualuxe_product_meta', 40);

/**
 * Remove default product meta.
 */
function aqualuxe_remove_default_product_meta() {
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
}
add_action('wp', 'aqualuxe_remove_default_product_meta');

/**
 * Modify breadcrumbs.
 *
 * @param array $args Breadcrumbs arguments.
 * @return array Modified breadcrumbs arguments.
 */
function aqualuxe_woocommerce_breadcrumbs($args) {
    if (!get_theme_mod('aqualuxe_show_product_breadcrumbs', true)) {
        $args['wrap_before'] = '<nav class="woocommerce-breadcrumb" style="display:none;">';
    }
    
    return $args;
}
add_filter('woocommerce_breadcrumb_defaults', 'aqualuxe_woocommerce_breadcrumbs');

/**
 * Modify shop page title.
 *
 * @param bool $show Show shop page title.
 * @return bool Modified show shop page title.
 */
function aqualuxe_show_page_title($show) {
    if (is_shop() && !get_theme_mod('aqualuxe_show_shop_title', true)) {
        return false;
    }
    
    return $show;
}
add_filter('woocommerce_show_page_title', 'aqualuxe_show_page_title');

/**
 * Modify shop page description.
 */
function aqualuxe_shop_description() {
    if (!get_theme_mod('aqualuxe_show_shop_description', true)) {
        remove_action('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10);
        remove_action('woocommerce_archive_description', 'woocommerce_product_archive_description', 10);
    }
}
add_action('wp', 'aqualuxe_shop_description');

/**
 * Modify result count.
 */
function aqualuxe_result_count() {
    if (!get_theme_mod('aqualuxe_show_result_count', true)) {
        remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
    }
}
add_action('wp', 'aqualuxe_result_count');

/**
 * Modify catalog ordering.
 */
function aqualuxe_catalog_ordering() {
    if (!get_theme_mod('aqualuxe_show_catalog_ordering', true)) {
        remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
    }
}
add_action('wp', 'aqualuxe_catalog_ordering');

/**
 * Enqueue WooCommerce scripts.
 */
function aqualuxe_woocommerce_scripts() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    wp_enqueue_script('aqualuxe-woocommerce', AQUALUXE_ASSETS_URI . 'js/woocommerce.js', array('jquery'), AQUALUXE_VERSION, true);
    
    wp_localize_script('aqualuxe-woocommerce', 'aqualuxeWooCommerce', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('aqualuxe-woocommerce'),
        'i18n'    => array(
            'quickView' => __('Quick View', 'aqualuxe'),
            'addToCart' => __('Add to Cart', 'aqualuxe'),
            'viewCart'  => __('View Cart', 'aqualuxe'),
        ),
    ));
}
add_action('wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts');

/**
 * Quick view AJAX handler.
 */
function aqualuxe_quick_view() {
    if (!isset($_POST['product_id']) || !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-woocommerce')) {
        wp_die();
    }
    
    $product_id = absint($_POST['product_id']);
    
    if (!$product_id) {
        wp_die();
    }
    
    global $post, $product;
    
    $post = get_post($product_id);
    $product = wc_get_product($product_id);
    
    if (!$post || !$product) {
        wp_die();
    }
    
    setup_postdata($post);
    
    ob_start();
    
    echo '<div class="quick-view-content">';
    echo '<div class="quick-view-image">';
    echo woocommerce_get_product_thumbnail('medium');
    echo '</div>';
    echo '<div class="quick-view-details">';
    echo '<h2 class="product_title">' . get_the_title() . '</h2>';
    echo '<div class="price">' . $product->get_price_html() . '</div>';
    echo '<div class="description">' . wp_trim_words($product->get_short_description(), 30) . '</div>';
    echo '<div class="actions">';
    woocommerce_template_loop_add_to_cart();
    echo '<a href="' . get_permalink() . '" class="button view-details">' . __('View Details', 'aqualuxe') . '</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    
    $output = ob_get_clean();
    
    wp_reset_postdata();
    
    echo $output;
    wp_die();
}
add_action('wp_ajax_aqualuxe_quick_view', 'aqualuxe_quick_view');
add_action('wp_ajax_nopriv_aqualuxe_quick_view', 'aqualuxe_quick_view');

/**
 * Add quick view button.
 */
function aqualuxe_add_quick_view_button() {
    if (!get_theme_mod('aqualuxe_product_show_quick_view', true)) {
        return;
    }
    
    global $product;
    
    echo '<a href="#" class="quick-view" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<span class="quick-view-icon"></span>';
    echo '<span class="screen-reader-text">' . esc_html__('Quick View', 'aqualuxe') . '</span>';
    echo '</a>';
}
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_add_quick_view_button', 15);

/**
 * Add wishlist button.
 */
function aqualuxe_wishlist_button() {
    if (!get_theme_mod('aqualuxe_product_show_wishlist', true)) {
        return;
    }
    
    global $product;
    
    echo '<a href="#" class="wishlist-button" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<span class="wishlist-icon"></span>';
    echo '<span class="screen-reader-text">' . esc_html__('Add to Wishlist', 'aqualuxe') . '</span>';
    echo '</a>';
}
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_wishlist_button', 20);

/**
 * Add compare button.
 */
function aqualuxe_compare_button() {
    if (!get_theme_mod('aqualuxe_product_show_compare', true)) {
        return;
    }
    
    global $product;
    
    echo '<a href="#" class="compare-button" data-product-id="' . esc_attr($product->get_id()) . '">';
    echo '<span class="compare-icon"></span>';
    echo '<span class="screen-reader-text">' . esc_html__('Compare', 'aqualuxe') . '</span>';
    echo '</a>';
}
add_action('woocommerce_before_shop_loop_item_title', 'aqualuxe_compare_button', 25);

/**
 * Add product filters toggle button.
 */
function aqualuxe_product_filters_toggle() {
    if (!get_theme_mod('aqualuxe_show_product_filters', true) || get_theme_mod('aqualuxe_product_filters_style', 'sidebar') !== 'offcanvas') {
        return;
    }
    
    echo '<div class="filter-toggle">' . esc_html__('Filter Products', 'aqualuxe') . '</div>';
    echo '<div class="filter-overlay"></div>';
    echo '<div class="filter-close">&times;</div>';
}
add_action('woocommerce_before_shop_loop', 'aqualuxe_product_filters_toggle', 15);