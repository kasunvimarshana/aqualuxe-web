<?php
/**
 * WooCommerce Customizer Section
 *
 * @package AquaLuxe
 */

/**
 * Add WooCommerce settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_woocommerce($wp_customize) {
    // Add WooCommerce section
    $wp_customize->add_section(
        'aqualuxe_woocommerce',
        array(
            'title'    => esc_html__('WooCommerce Settings', 'aqualuxe'),
            'priority' => 110,
        )
    );

    // Shop Layout
    $wp_customize->add_setting(
        'aqualuxe_shop_layout',
        array(
            'default'           => 'grid',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_layout',
        array(
            'label'   => esc_html__('Shop Layout', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'select',
            'choices' => array(
                'grid'    => esc_html__('Grid', 'aqualuxe'),
                'list'    => esc_html__('List', 'aqualuxe'),
                'masonry' => esc_html__('Masonry', 'aqualuxe'),
            ),
        )
    );

    // Products Per Row
    $wp_customize->add_setting(
        'aqualuxe_products_per_row',
        array(
            'default'           => '3',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_products_per_row',
        array(
            'label'       => esc_html__('Products Per Row', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 6,
                'step' => 1,
            ),
        )
    );

    // Products Per Page
    $wp_customize->add_setting(
        'aqualuxe_products_per_page',
        array(
            'default'           => '12',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_products_per_page',
        array(
            'label'       => esc_html__('Products Per Page', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 100,
                'step' => 1,
            ),
        )
    );

    // Related Products
    $wp_customize->add_setting(
        'aqualuxe_related_products',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_related_products',
        array(
            'label'   => esc_html__('Show Related Products', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Related Products Count
    $wp_customize->add_setting(
        'aqualuxe_related_products_count',
        array(
            'default'           => '4',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_related_products_count',
        array(
            'label'       => esc_html__('Related Products Count', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 12,
                'step' => 1,
            ),
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_related_products', true);
            },
        )
    );

    // Upsells
    $wp_customize->add_setting(
        'aqualuxe_upsells',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_upsells',
        array(
            'label'   => esc_html__('Show Upsells', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Cross-sells
    $wp_customize->add_setting(
        'aqualuxe_cross_sells',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_cross_sells',
        array(
            'label'   => esc_html__('Show Cross-sells', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Product Gallery Zoom
    $wp_customize->add_setting(
        'aqualuxe_product_gallery_zoom',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_gallery_zoom',
        array(
            'label'   => esc_html__('Enable Product Gallery Zoom', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Product Gallery Lightbox
    $wp_customize->add_setting(
        'aqualuxe_product_gallery_lightbox',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_gallery_lightbox',
        array(
            'label'   => esc_html__('Enable Product Gallery Lightbox', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Product Gallery Slider
    $wp_customize->add_setting(
        'aqualuxe_product_gallery_slider',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_gallery_slider',
        array(
            'label'   => esc_html__('Enable Product Gallery Slider', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Product Image Hover Effect
    $wp_customize->add_setting(
        'aqualuxe_product_image_hover',
        array(
            'default'           => 'zoom',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_image_hover',
        array(
            'label'   => esc_html__('Product Image Hover Effect', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'select',
            'choices' => array(
                'none'         => esc_html__('None', 'aqualuxe'),
                'zoom'         => esc_html__('Zoom', 'aqualuxe'),
                'fade'         => esc_html__('Fade', 'aqualuxe'),
                'flip'         => esc_html__('Flip', 'aqualuxe'),
                'second_image' => esc_html__('Second Image', 'aqualuxe'),
            ),
        )
    );

    // Product Card Style
    $wp_customize->add_setting(
        'aqualuxe_product_card_style',
        array(
            'default'           => 'standard',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_card_style',
        array(
            'label'   => esc_html__('Product Card Style', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'select',
            'choices' => array(
                'standard'   => esc_html__('Standard', 'aqualuxe'),
                'minimal'    => esc_html__('Minimal', 'aqualuxe'),
                'bordered'   => esc_html__('Bordered', 'aqualuxe'),
                'boxed'      => esc_html__('Boxed', 'aqualuxe'),
                'overlay'    => esc_html__('Overlay', 'aqualuxe'),
            ),
        )
    );

    // Sale Badge Style
    $wp_customize->add_setting(
        'aqualuxe_sale_badge_style',
        array(
            'default'           => 'circle',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sale_badge_style',
        array(
            'label'   => esc_html__('Sale Badge Style', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'select',
            'choices' => array(
                'circle'    => esc_html__('Circle', 'aqualuxe'),
                'square'    => esc_html__('Square', 'aqualuxe'),
                'rounded'   => esc_html__('Rounded', 'aqualuxe'),
                'ribbon'    => esc_html__('Ribbon', 'aqualuxe'),
                'tag'       => esc_html__('Tag', 'aqualuxe'),
            ),
        )
    );

    // Sale Badge Text
    $wp_customize->add_setting(
        'aqualuxe_sale_badge_text',
        array(
            'default'           => esc_html__('Sale!', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sale_badge_text',
        array(
            'label'   => esc_html__('Sale Badge Text', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'text',
        )
    );

    // Sale Badge Color
    $wp_customize->add_setting(
        'aqualuxe_sale_badge_color',
        array(
            'default'           => '#e2401c',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_sale_badge_color',
            array(
                'label'   => esc_html__('Sale Badge Color', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce',
            )
        )
    );

    // New Badge
    $wp_customize->add_setting(
        'aqualuxe_new_badge',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_new_badge',
        array(
            'label'   => esc_html__('Show New Product Badge', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // New Badge Duration
    $wp_customize->add_setting(
        'aqualuxe_new_badge_duration',
        array(
            'default'           => '7',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_new_badge_duration',
        array(
            'label'       => esc_html__('New Badge Duration (days)', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 30,
                'step' => 1,
            ),
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_new_badge', true);
            },
        )
    );

    // New Badge Text
    $wp_customize->add_setting(
        'aqualuxe_new_badge_text',
        array(
            'default'           => esc_html__('New!', 'aqualuxe'),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_new_badge_text',
        array(
            'label'   => esc_html__('New Badge Text', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'text',
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_new_badge', true);
            },
        )
    );

    // New Badge Color
    $wp_customize->add_setting(
        'aqualuxe_new_badge_color',
        array(
            'default'           => '#0073aa',
            'sanitize_callback' => 'aqualuxe_sanitize_hex_color',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_new_badge_color',
            array(
                'label'   => esc_html__('New Badge Color', 'aqualuxe'),
                'section' => 'aqualuxe_woocommerce',
                'active_callback' => function() {
                    return get_theme_mod('aqualuxe_new_badge', true);
                },
            )
        )
    );

    // Quick View
    $wp_customize->add_setting(
        'aqualuxe_quick_view',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_quick_view',
        array(
            'label'   => esc_html__('Enable Quick View', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Wishlist
    $wp_customize->add_setting(
        'aqualuxe_enable_wishlist',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_wishlist',
        array(
            'label'   => esc_html__('Enable Wishlist', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Compare
    $wp_customize->add_setting(
        'aqualuxe_enable_compare',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_compare',
        array(
            'label'   => esc_html__('Enable Compare', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Ajax Add to Cart
    $wp_customize->add_setting(
        'aqualuxe_ajax_add_to_cart',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_ajax_add_to_cart',
        array(
            'label'   => esc_html__('Enable Ajax Add to Cart', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Mini Cart
    $wp_customize->add_setting(
        'aqualuxe_enable_mini_cart',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_mini_cart',
        array(
            'label'   => esc_html__('Enable Mini Cart', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Mini Cart Style
    $wp_customize->add_setting(
        'aqualuxe_mini_cart_style',
        array(
            'default'           => 'dropdown',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_mini_cart_style',
        array(
            'label'   => esc_html__('Mini Cart Style', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'select',
            'choices' => array(
                'dropdown' => esc_html__('Dropdown', 'aqualuxe'),
                'offcanvas' => esc_html__('Off-canvas', 'aqualuxe'),
                'popup' => esc_html__('Popup', 'aqualuxe'),
            ),
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_enable_mini_cart', true);
            },
        )
    );

    // Product Filters
    $wp_customize->add_setting(
        'aqualuxe_product_filters',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_filters',
        array(
            'label'   => esc_html__('Enable Product Filters', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Product Filters Style
    $wp_customize->add_setting(
        'aqualuxe_product_filters_style',
        array(
            'default'           => 'sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_filters_style',
        array(
            'label'   => esc_html__('Product Filters Style', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'select',
            'choices' => array(
                'sidebar' => esc_html__('Sidebar', 'aqualuxe'),
                'horizontal' => esc_html__('Horizontal', 'aqualuxe'),
                'offcanvas' => esc_html__('Off-canvas', 'aqualuxe'),
                'dropdown' => esc_html__('Dropdown', 'aqualuxe'),
            ),
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_product_filters', true);
            },
        )
    );

    // Catalog Mode
    $wp_customize->add_setting(
        'aqualuxe_catalog_mode',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_catalog_mode',
        array(
            'label'       => esc_html__('Enable Catalog Mode', 'aqualuxe'),
            'description' => esc_html__('Hide the purchase functionality and prices.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'checkbox',
        )
    );

    // Product Tabs Style
    $wp_customize->add_setting(
        'aqualuxe_product_tabs_style',
        array(
            'default'           => 'horizontal',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_tabs_style',
        array(
            'label'   => esc_html__('Product Tabs Style', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'select',
            'choices' => array(
                'horizontal' => esc_html__('Horizontal', 'aqualuxe'),
                'vertical' => esc_html__('Vertical', 'aqualuxe'),
                'accordion' => esc_html__('Accordion', 'aqualuxe'),
                'sections' => esc_html__('Sections', 'aqualuxe'),
            ),
        )
    );

    // Sticky Add to Cart
    $wp_customize->add_setting(
        'aqualuxe_sticky_add_to_cart',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sticky_add_to_cart',
        array(
            'label'   => esc_html__('Enable Sticky Add to Cart', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Checkout Layout
    $wp_customize->add_setting(
        'aqualuxe_checkout_layout',
        array(
            'default'           => 'standard',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_checkout_layout',
        array(
            'label'   => esc_html__('Checkout Layout', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'select',
            'choices' => array(
                'standard' => esc_html__('Standard', 'aqualuxe'),
                'two-column' => esc_html__('Two Column', 'aqualuxe'),
                'one-column' => esc_html__('One Column', 'aqualuxe'),
                'multistep' => esc_html__('Multi-step', 'aqualuxe'),
            ),
        )
    );

    // Distraction Free Checkout
    $wp_customize->add_setting(
        'aqualuxe_distraction_free_checkout',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_distraction_free_checkout',
        array(
            'label'       => esc_html__('Enable Distraction Free Checkout', 'aqualuxe'),
            'description' => esc_html__('Remove header and footer from checkout page.', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'checkbox',
        )
    );

    // Order Tracking
    $wp_customize->add_setting(
        'aqualuxe_order_tracking',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_order_tracking',
        array(
            'label'   => esc_html__('Enable Order Tracking', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Product Recommendations
    $wp_customize->add_setting(
        'aqualuxe_product_recommendations',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_recommendations',
        array(
            'label'   => esc_html__('Enable Product Recommendations', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Recently Viewed Products
    $wp_customize->add_setting(
        'aqualuxe_recently_viewed_products',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_recently_viewed_products',
        array(
            'label'   => esc_html__('Enable Recently Viewed Products', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Recently Viewed Products Count
    $wp_customize->add_setting(
        'aqualuxe_recently_viewed_products_count',
        array(
            'default'           => '4',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_recently_viewed_products_count',
        array(
            'label'       => esc_html__('Recently Viewed Products Count', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 12,
                'step' => 1,
            ),
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_recently_viewed_products', true);
            },
        )
    );

    // Product Quantity Input Style
    $wp_customize->add_setting(
        'aqualuxe_quantity_input_style',
        array(
            'default'           => 'buttons',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_quantity_input_style',
        array(
            'label'   => esc_html__('Quantity Input Style', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'select',
            'choices' => array(
                'default' => esc_html__('Default', 'aqualuxe'),
                'buttons' => esc_html__('With Buttons', 'aqualuxe'),
                'arrows' => esc_html__('With Arrows', 'aqualuxe'),
                'dropdown' => esc_html__('Dropdown', 'aqualuxe'),
            ),
        )
    );

    // Product Ratings
    $wp_customize->add_setting(
        'aqualuxe_product_ratings',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_ratings',
        array(
            'label'   => esc_html__('Show Product Ratings', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Product Stock Status
    $wp_customize->add_setting(
        'aqualuxe_product_stock_status',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_stock_status',
        array(
            'label'   => esc_html__('Show Product Stock Status', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Product SKU
    $wp_customize->add_setting(
        'aqualuxe_product_sku',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_sku',
        array(
            'label'   => esc_html__('Show Product SKU', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Product Categories
    $wp_customize->add_setting(
        'aqualuxe_product_categories',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_categories',
        array(
            'label'   => esc_html__('Show Product Categories', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Product Tags
    $wp_customize->add_setting(
        'aqualuxe_product_tags',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_tags',
        array(
            'label'   => esc_html__('Show Product Tags', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Shop Page Title
    $wp_customize->add_setting(
        'aqualuxe_shop_page_title',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_page_title',
        array(
            'label'   => esc_html__('Show Shop Page Title', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Shop Page Description
    $wp_customize->add_setting(
        'aqualuxe_shop_page_description',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_page_description',
        array(
            'label'   => esc_html__('Show Shop Page Description', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Category Display
    $wp_customize->add_setting(
        'aqualuxe_category_display',
        array(
            'default'           => 'both',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_category_display',
        array(
            'label'   => esc_html__('Category Display', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'select',
            'choices' => array(
                'both' => esc_html__('Image and Name', 'aqualuxe'),
                'image' => esc_html__('Image Only', 'aqualuxe'),
                'name' => esc_html__('Name Only', 'aqualuxe'),
            ),
        )
    );

    // Category Image Size
    $wp_customize->add_setting(
        'aqualuxe_category_image_size',
        array(
            'default'           => 'medium',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_category_image_size',
        array(
            'label'   => esc_html__('Category Image Size', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'select',
            'choices' => array(
                'thumbnail' => esc_html__('Thumbnail', 'aqualuxe'),
                'medium' => esc_html__('Medium', 'aqualuxe'),
                'large' => esc_html__('Large', 'aqualuxe'),
                'full' => esc_html__('Full', 'aqualuxe'),
            ),
            'active_callback' => function() {
                return get_theme_mod('aqualuxe_category_display', 'both') !== 'name';
            },
        )
    );

    // Category Count
    $wp_customize->add_setting(
        'aqualuxe_category_count',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_category_count',
        array(
            'label'   => esc_html__('Show Category Count', 'aqualuxe'),
            'section' => 'aqualuxe_woocommerce',
            'type'    => 'checkbox',
        )
    );

    // Shop Columns (Mobile)
    $wp_customize->add_setting(
        'aqualuxe_shop_columns_mobile',
        array(
            'default'           => '1',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_columns_mobile',
        array(
            'label'       => esc_html__('Shop Columns (Mobile)', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 2,
                'step' => 1,
            ),
        )
    );

    // Shop Columns (Tablet)
    $wp_customize->add_setting(
        'aqualuxe_shop_columns_tablet',
        array(
            'default'           => '2',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_columns_tablet',
        array(
            'label'       => esc_html__('Shop Columns (Tablet)', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 4,
                'step' => 1,
            ),
        )
    );

    // Shop Columns (Desktop)
    $wp_customize->add_setting(
        'aqualuxe_shop_columns_desktop',
        array(
            'default'           => '3',
            'sanitize_callback' => 'absint',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_columns_desktop',
        array(
            'label'       => esc_html__('Shop Columns (Desktop)', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 6,
                'step' => 1,
            ),
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_register_woocommerce');