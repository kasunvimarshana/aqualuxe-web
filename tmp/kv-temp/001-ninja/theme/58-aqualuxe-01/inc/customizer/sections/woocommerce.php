<?php
/**
 * AquaLuxe Theme Customizer - WooCommerce Settings
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add WooCommerce settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_woocommerce_settings($wp_customize) {
    // Only add these settings if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    // WooCommerce Section
    $wp_customize->add_section(
        'aqualuxe_woocommerce_section',
        array(
            'title'       => __('WooCommerce Settings', 'aqualuxe'),
            'description' => __('Configure WooCommerce specific settings.', 'aqualuxe'),
            'priority'    => 80,
        )
    );

    // General WooCommerce Heading
    $wp_customize->add_setting(
        'aqualuxe_woocommerce_general_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_woocommerce_general_heading',
            array(
                'label'   => __('General Shop Settings', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_section',
            )
        )
    );

    // Shop Page Layout
    $wp_customize->add_setting(
        'aqualuxe_options[shop_layout]',
        array(
            'default'           => 'right-sidebar',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[shop_layout]',
        array(
            'label'       => __('Shop Page Layout', 'aqualuxe'),
            'description' => __('Choose the layout for the main shop page.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
                'full-width'    => __('Full Width', 'aqualuxe'),
            ),
        )
    );

    // Product Page Layout
    $wp_customize->add_setting(
        'aqualuxe_options[product_layout]',
        array(
            'default'           => 'no-sidebar',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[product_layout]',
        array(
            'label'       => __('Product Page Layout', 'aqualuxe'),
            'description' => __('Choose the layout for single product pages.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
                'full-width'    => __('Full Width', 'aqualuxe'),
            ),
        )
    );

    // Products Per Page
    $wp_customize->add_setting(
        'aqualuxe_options[products_per_page]',
        array(
            'default'           => 12,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[products_per_page]',
        array(
            'label'       => __('Products Per Page', 'aqualuxe'),
            'description' => __('Number of products to display per page.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 4,
                'max'  => 48,
                'step' => 4,
            ),
        )
    );

    // Shop Columns
    $wp_customize->add_setting(
        'aqualuxe_options[shop_columns]',
        array(
            'default'           => 4,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[shop_columns]',
        array(
            'label'       => __('Shop Columns', 'aqualuxe'),
            'description' => __('Number of columns in product grids.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'select',
            'choices'     => array(
                '2' => __('2 Columns', 'aqualuxe'),
                '3' => __('3 Columns', 'aqualuxe'),
                '4' => __('4 Columns', 'aqualuxe'),
                '5' => __('5 Columns', 'aqualuxe'),
            ),
        )
    );

    // Related Products
    $wp_customize->add_setting(
        'aqualuxe_options[related_products]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[related_products]',
        array(
            'label'       => __('Show Related Products', 'aqualuxe'),
            'description' => __('Display related products on single product pages.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Related Products Count
    $wp_customize->add_setting(
        'aqualuxe_options[related_products_count]',
        array(
            'default'           => 4,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[related_products_count]',
        array(
            'label'       => __('Related Products Count', 'aqualuxe'),
            'description' => __('Number of related products to display.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 12,
                'step' => 1,
            ),
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['related_products']) ? $options['related_products'] : true;
            },
        )
    );

    // Product Gallery Heading
    $wp_customize->add_setting(
        'aqualuxe_woocommerce_gallery_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_woocommerce_gallery_heading',
            array(
                'label'   => __('Product Gallery', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_section',
            )
        )
    );

    // Product Gallery Style
    $wp_customize->add_setting(
        'aqualuxe_options[product_gallery_style]',
        array(
            'default'           => 'horizontal-slider',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[product_gallery_style]',
        array(
            'label'       => __('Product Gallery Style', 'aqualuxe'),
            'description' => __('Choose the style for product galleries.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'select',
            'choices'     => array(
                'default'           => __('Default WooCommerce', 'aqualuxe'),
                'horizontal-slider' => __('Horizontal Slider', 'aqualuxe'),
                'vertical-slider'   => __('Vertical Slider', 'aqualuxe'),
                'grid'              => __('Grid', 'aqualuxe'),
            ),
        )
    );

    // Enable Zoom
    $wp_customize->add_setting(
        'aqualuxe_options[product_zoom]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[product_zoom]',
        array(
            'label'       => __('Enable Image Zoom', 'aqualuxe'),
            'description' => __('Allow customers to zoom in on product images.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Lightbox
    $wp_customize->add_setting(
        'aqualuxe_options[product_lightbox]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[product_lightbox]',
        array(
            'label'       => __('Enable Lightbox', 'aqualuxe'),
            'description' => __('Allow customers to view full-size images in a lightbox.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Product Cards Heading
    $wp_customize->add_setting(
        'aqualuxe_woocommerce_cards_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_woocommerce_cards_heading',
            array(
                'label'   => __('Product Cards', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_section',
            )
        )
    );

    // Product Card Style
    $wp_customize->add_setting(
        'aqualuxe_options[product_card_style]',
        array(
            'default'           => 'standard',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[product_card_style]',
        array(
            'label'       => __('Product Card Style', 'aqualuxe'),
            'description' => __('Choose the style for product cards in grids.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'select',
            'choices'     => array(
                'standard'  => __('Standard', 'aqualuxe'),
                'minimal'   => __('Minimal', 'aqualuxe'),
                'bordered'  => __('Bordered', 'aqualuxe'),
                'shadow'    => __('Shadow', 'aqualuxe'),
                'overlay'   => __('Image Overlay', 'aqualuxe'),
            ),
        )
    );

    // Quick View
    $wp_customize->add_setting(
        'aqualuxe_options[product_quick_view]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[product_quick_view]',
        array(
            'label'       => __('Enable Quick View', 'aqualuxe'),
            'description' => __('Add quick view functionality to product cards.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Show Rating
    $wp_customize->add_setting(
        'aqualuxe_options[product_card_rating]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[product_card_rating]',
        array(
            'label'       => __('Show Product Rating', 'aqualuxe'),
            'description' => __('Display star ratings on product cards.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Sale Badge Style
    $wp_customize->add_setting(
        'aqualuxe_options[sale_badge_style]',
        array(
            'default'           => 'circle',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[sale_badge_style]',
        array(
            'label'       => __('Sale Badge Style', 'aqualuxe'),
            'description' => __('Choose the style for sale badges.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'select',
            'choices'     => array(
                'circle'    => __('Circle', 'aqualuxe'),
                'square'    => __('Square', 'aqualuxe'),
                'ribbon'    => __('Ribbon', 'aqualuxe'),
                'tag'       => __('Tag', 'aqualuxe'),
                'minimal'   => __('Minimal', 'aqualuxe'),
            ),
        )
    );

    // Sale Badge Text
    $wp_customize->add_setting(
        'aqualuxe_options[sale_badge_text]',
        array(
            'default'           => __('Sale!', 'aqualuxe'),
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[sale_badge_text]',
        array(
            'label'       => __('Sale Badge Text', 'aqualuxe'),
            'description' => __('Text to display on sale badges.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'text',
        )
    );

    // Show Percentage
    $wp_customize->add_setting(
        'aqualuxe_options[sale_percentage]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[sale_percentage]',
        array(
            'label'       => __('Show Discount Percentage', 'aqualuxe'),
            'description' => __('Display the discount percentage on sale badges.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Cart & Checkout Heading
    $wp_customize->add_setting(
        'aqualuxe_woocommerce_cart_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_woocommerce_cart_heading',
            array(
                'label'   => __('Cart & Checkout', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce_section',
            )
        )
    );

    // Ajax Add to Cart
    $wp_customize->add_setting(
        'aqualuxe_options[ajax_add_to_cart]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[ajax_add_to_cart]',
        array(
            'label'       => __('Enable AJAX Add to Cart', 'aqualuxe'),
            'description' => __('Add products to cart without page refresh.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Mini Cart Style
    $wp_customize->add_setting(
        'aqualuxe_options[mini_cart_style]',
        array(
            'default'           => 'dropdown',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[mini_cart_style]',
        array(
            'label'       => __('Mini Cart Style', 'aqualuxe'),
            'description' => __('Choose the style for the mini cart.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'select',
            'choices'     => array(
                'dropdown' => __('Dropdown', 'aqualuxe'),
                'offcanvas' => __('Off-Canvas', 'aqualuxe'),
                'popup'    => __('Popup', 'aqualuxe'),
            ),
        )
    );

    // Sticky Add to Cart
    $wp_customize->add_setting(
        'aqualuxe_options[sticky_add_to_cart]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[sticky_add_to_cart]',
        array(
            'label'       => __('Sticky Add to Cart', 'aqualuxe'),
            'description' => __('Show sticky add to cart bar when scrolling on product pages.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Cross-Sells
    $wp_customize->add_setting(
        'aqualuxe_options[cross_sells]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[cross_sells]',
        array(
            'label'       => __('Show Cross-Sells', 'aqualuxe'),
            'description' => __('Display cross-sell products on cart page.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // One-Page Checkout
    $wp_customize->add_setting(
        'aqualuxe_options[one_page_checkout]',
        array(
            'default'           => false,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[one_page_checkout]',
        array(
            'label'       => __('One-Page Checkout', 'aqualuxe'),
            'description' => __('Enable one-page checkout experience.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Distraction-Free Checkout
    $wp_customize->add_setting(
        'aqualuxe_options[distraction_free_checkout]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[distraction_free_checkout]',
        array(
            'label'       => __('Distraction-Free Checkout', 'aqualuxe'),
            'description' => __('Simplify checkout page by removing distractions.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Checkout Layout
    $wp_customize->add_setting(
        'aqualuxe_options[checkout_layout]',
        array(
            'default'           => 'two-column',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[checkout_layout]',
        array(
            'label'       => __('Checkout Layout', 'aqualuxe'),
            'description' => __('Choose the layout for the checkout page.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'select',
            'choices'     => array(
                'default'     => __('Default', 'aqualuxe'),
                'two-column'  => __('Two Column', 'aqualuxe'),
                'multi-step'  => __('Multi-Step', 'aqualuxe'),
            ),
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_woocommerce_settings');