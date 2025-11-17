<?php
/**
 * WooCommerce Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add WooCommerce settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_woocommerce($wp_customize) {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    // Add WooCommerce section
    $wp_customize->add_section('aqualuxe_woocommerce', array(
        'title' => esc_html__('WooCommerce Settings', 'aqualuxe'),
        'priority' => 130,
    ));

    // Shop Layout
    $wp_customize->add_setting('aqualuxe_woocommerce_shop_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_woocommerce_shop_heading', array(
        'label' => esc_html__('Shop Layout', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 10,
    )));

    // Shop Layout
    $wp_customize->add_setting('aqualuxe_shop_layout', array(
        'default' => 'grid',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Radio_Image($wp_customize, 'aqualuxe_shop_layout', array(
        'label' => esc_html__('Shop Layout', 'aqualuxe'),
        'description' => esc_html__('Select the layout for the shop page.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 20,
        'choices' => array(
            'grid' => array(
                'label' => esc_html__('Grid', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/shop-grid.png',
            ),
            'list' => array(
                'label' => esc_html__('List', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/shop-list.png',
            ),
            'masonry' => array(
                'label' => esc_html__('Masonry', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/shop-masonry.png',
            ),
        ),
    )));

    // Shop Sidebar Position
    $wp_customize->add_setting('aqualuxe_shop_sidebar', array(
        'default' => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Radio_Image($wp_customize, 'aqualuxe_shop_sidebar', array(
        'label' => esc_html__('Shop Sidebar Position', 'aqualuxe'),
        'description' => esc_html__('Select the sidebar position for the shop page.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 30,
        'choices' => array(
            'right' => array(
                'label' => esc_html__('Right', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/sidebar-right.png',
            ),
            'left' => array(
                'label' => esc_html__('Left', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/sidebar-left.png',
            ),
            'none' => array(
                'label' => esc_html__('None', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/sidebar-none.png',
            ),
        ),
    )));

    // Product Columns
    $wp_customize->add_setting('aqualuxe_product_columns', array(
        'default' => 3,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_product_columns', array(
        'label' => esc_html__('Product Columns', 'aqualuxe'),
        'description' => esc_html__('Select the number of product columns for the shop page.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            2 => esc_html__('2 Columns', 'aqualuxe'),
            3 => esc_html__('3 Columns', 'aqualuxe'),
            4 => esc_html__('4 Columns', 'aqualuxe'),
            5 => esc_html__('5 Columns', 'aqualuxe'),
        ),
        'priority' => 40,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_shop_layout', 'grid') !== 'list';
        },
    ));

    // Products Per Page
    $wp_customize->add_setting('aqualuxe_products_per_page', array(
        'default' => 12,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_products_per_page', array(
        'label' => esc_html__('Products Per Page', 'aqualuxe'),
        'description' => esc_html__('Set the number of products to display per page.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 100,
            'step' => 1,
        ),
        'priority' => 50,
    ));

    // Product Card Style
    $wp_customize->add_setting('aqualuxe_product_card_style', array(
        'default' => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Radio_Image($wp_customize, 'aqualuxe_product_card_style', array(
        'label' => esc_html__('Product Card Style', 'aqualuxe'),
        'description' => esc_html__('Select the style for product cards.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 60,
        'choices' => array(
            'default' => array(
                'label' => esc_html__('Default', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/product-card-default.png',
            ),
            'minimal' => array(
                'label' => esc_html__('Minimal', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/product-card-minimal.png',
            ),
            'bordered' => array(
                'label' => esc_html__('Bordered', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/product-card-bordered.png',
            ),
            'shadow' => array(
                'label' => esc_html__('Shadow', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/product-card-shadow.png',
            ),
        ),
    )));

    // Product Image Hover Effect
    $wp_customize->add_setting('aqualuxe_product_image_hover', array(
        'default' => 'zoom',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_product_image_hover', array(
        'label' => esc_html__('Product Image Hover Effect', 'aqualuxe'),
        'description' => esc_html__('Select the hover effect for product images.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'none' => esc_html__('None', 'aqualuxe'),
            'zoom' => esc_html__('Zoom', 'aqualuxe'),
            'fade' => esc_html__('Fade', 'aqualuxe'),
            'flip' => esc_html__('Flip', 'aqualuxe'),
            'gallery' => esc_html__('Gallery', 'aqualuxe'),
        ),
        'priority' => 70,
    ));

    // Show Product Rating
    $wp_customize->add_setting('aqualuxe_show_product_rating', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_product_rating', array(
        'label' => esc_html__('Show Product Rating', 'aqualuxe'),
        'description' => esc_html__('Display product rating stars on product cards.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 80,
    )));

    // Show Product Price
    $wp_customize->add_setting('aqualuxe_show_product_price', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_product_price', array(
        'label' => esc_html__('Show Product Price', 'aqualuxe'),
        'description' => esc_html__('Display product price on product cards.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 90,
    )));

    // Show Add to Cart Button
    $wp_customize->add_setting('aqualuxe_show_add_to_cart', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_add_to_cart', array(
        'label' => esc_html__('Show Add to Cart Button', 'aqualuxe'),
        'description' => esc_html__('Display add to cart button on product cards.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 100,
    )));

    // Add to Cart Button Style
    $wp_customize->add_setting('aqualuxe_add_to_cart_style', array(
        'default' => 'button',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_add_to_cart_style', array(
        'label' => esc_html__('Add to Cart Button Style', 'aqualuxe'),
        'description' => esc_html__('Select the style for add to cart buttons.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'button' => esc_html__('Button', 'aqualuxe'),
            'icon' => esc_html__('Icon Only', 'aqualuxe'),
            'text' => esc_html__('Text Only', 'aqualuxe'),
            'overlay' => esc_html__('Overlay', 'aqualuxe'),
        ),
        'priority' => 110,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_add_to_cart', true);
        },
    ));

    // Sale Badge Style
    $wp_customize->add_setting('aqualuxe_sale_badge_style', array(
        'default' => 'circle',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_sale_badge_style', array(
        'label' => esc_html__('Sale Badge Style', 'aqualuxe'),
        'description' => esc_html__('Select the style for sale badges.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'circle' => esc_html__('Circle', 'aqualuxe'),
            'square' => esc_html__('Square', 'aqualuxe'),
            'ribbon' => esc_html__('Ribbon', 'aqualuxe'),
            'tag' => esc_html__('Tag', 'aqualuxe'),
        ),
        'priority' => 120,
    ));

    // Sale Badge Text
    $wp_customize->add_setting('aqualuxe_sale_badge_text', array(
        'default' => esc_html__('Sale!', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_sale_badge_text', array(
        'label' => esc_html__('Sale Badge Text', 'aqualuxe'),
        'description' => esc_html__('Text to display on sale badges.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'text',
        'priority' => 130,
    ));

    // Show Percentage on Sale Badge
    $wp_customize->add_setting('aqualuxe_show_sale_percentage', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_sale_percentage', array(
        'label' => esc_html__('Show Percentage on Sale Badge', 'aqualuxe'),
        'description' => esc_html__('Display discount percentage on sale badges.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 140,
    )));

    // Single Product
    $wp_customize->add_setting('aqualuxe_woocommerce_single_product_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_woocommerce_single_product_heading', array(
        'label' => esc_html__('Single Product', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 150,
    )));

    // Single Product Layout
    $wp_customize->add_setting('aqualuxe_single_product_layout', array(
        'default' => 'standard',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Radio_Image($wp_customize, 'aqualuxe_single_product_layout', array(
        'label' => esc_html__('Single Product Layout', 'aqualuxe'),
        'description' => esc_html__('Select the layout for single product pages.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 160,
        'choices' => array(
            'standard' => array(
                'label' => esc_html__('Standard', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/product-layout-standard.png',
            ),
            'wide' => array(
                'label' => esc_html__('Wide', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/product-layout-wide.png',
            ),
            'full' => array(
                'label' => esc_html__('Full Width', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/product-layout-full.png',
            ),
            'sticky' => array(
                'label' => esc_html__('Sticky Details', 'aqualuxe'),
                'image' => AQUALUXE_URI . '/assets/images/customizer/product-layout-sticky.png',
            ),
        ),
    )));

    // Product Gallery Style
    $wp_customize->add_setting('aqualuxe_product_gallery_style', array(
        'default' => 'horizontal',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_product_gallery_style', array(
        'label' => esc_html__('Product Gallery Style', 'aqualuxe'),
        'description' => esc_html__('Select the style for product galleries.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'horizontal' => esc_html__('Horizontal Thumbnails', 'aqualuxe'),
            'vertical' => esc_html__('Vertical Thumbnails', 'aqualuxe'),
            'grid' => esc_html__('Grid', 'aqualuxe'),
            'slider' => esc_html__('Slider Only', 'aqualuxe'),
        ),
        'priority' => 170,
    ));

    // Enable Image Zoom
    $wp_customize->add_setting('aqualuxe_enable_image_zoom', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_image_zoom', array(
        'label' => esc_html__('Enable Image Zoom', 'aqualuxe'),
        'description' => esc_html__('Enable zoom effect on product images.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 180,
    )));

    // Enable Image Lightbox
    $wp_customize->add_setting('aqualuxe_enable_image_lightbox', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_image_lightbox', array(
        'label' => esc_html__('Enable Image Lightbox', 'aqualuxe'),
        'description' => esc_html__('Enable lightbox for product images.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 190,
    )));

    // Product Tabs Style
    $wp_customize->add_setting('aqualuxe_product_tabs_style', array(
        'default' => 'horizontal',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_product_tabs_style', array(
        'label' => esc_html__('Product Tabs Style', 'aqualuxe'),
        'description' => esc_html__('Select the style for product tabs.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'horizontal' => esc_html__('Horizontal Tabs', 'aqualuxe'),
            'vertical' => esc_html__('Vertical Tabs', 'aqualuxe'),
            'accordion' => esc_html__('Accordion', 'aqualuxe'),
            'sections' => esc_html__('Sections', 'aqualuxe'),
        ),
        'priority' => 200,
    ));

    // Show Related Products
    $wp_customize->add_setting('aqualuxe_show_related_products', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_related_products', array(
        'label' => esc_html__('Show Related Products', 'aqualuxe'),
        'description' => esc_html__('Display related products on single product pages.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 210,
    )));

    // Related Products Count
    $wp_customize->add_setting('aqualuxe_related_products_count', array(
        'default' => 4,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_related_products_count', array(
        'label' => esc_html__('Related Products Count', 'aqualuxe'),
        'description' => esc_html__('Set the number of related products to display.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
        'priority' => 220,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_related_products', true);
        },
    ));

    // Show Upsells
    $wp_customize->add_setting('aqualuxe_show_upsells', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_upsells', array(
        'label' => esc_html__('Show Upsells', 'aqualuxe'),
        'description' => esc_html__('Display upsell products on single product pages.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 230,
    )));

    // Upsells Count
    $wp_customize->add_setting('aqualuxe_upsells_count', array(
        'default' => 4,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_upsells_count', array(
        'label' => esc_html__('Upsells Count', 'aqualuxe'),
        'description' => esc_html__('Set the number of upsell products to display.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
        'priority' => 240,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_upsells', true);
        },
    ));

    // Show Cross-Sells
    $wp_customize->add_setting('aqualuxe_show_cross_sells', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_cross_sells', array(
        'label' => esc_html__('Show Cross-Sells', 'aqualuxe'),
        'description' => esc_html__('Display cross-sell products on the cart page.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 250,
    )));

    // Cross-Sells Count
    $wp_customize->add_setting('aqualuxe_cross_sells_count', array(
        'default' => 4,
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_cross_sells_count', array(
        'label' => esc_html__('Cross-Sells Count', 'aqualuxe'),
        'description' => esc_html__('Set the number of cross-sell products to display.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
        'priority' => 260,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_cross_sells', true);
        },
    ));

    // Enhanced Features
    $wp_customize->add_setting('aqualuxe_woocommerce_enhanced_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_woocommerce_enhanced_heading', array(
        'label' => esc_html__('Enhanced Features', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 270,
    )));

    // Enable Quick View
    $wp_customize->add_setting('aqualuxe_enable_quick_view', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_quick_view', array(
        'label' => esc_html__('Enable Quick View', 'aqualuxe'),
        'description' => esc_html__('Enable quick view feature for products.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 280,
    )));

    // Enable Wishlist
    $wp_customize->add_setting('aqualuxe_enable_wishlist', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_wishlist', array(
        'label' => esc_html__('Enable Wishlist', 'aqualuxe'),
        'description' => esc_html__('Enable wishlist feature for products.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 290,
    )));

    // Wishlist Page
    $wp_customize->add_setting('aqualuxe_wishlist_page', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_wishlist_page', array(
        'label' => esc_html__('Wishlist Page', 'aqualuxe'),
        'description' => esc_html__('Select the page to use as the wishlist page.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'dropdown-pages',
        'priority' => 300,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_wishlist', true);
        },
    ));

    // Enable AJAX Add to Cart
    $wp_customize->add_setting('aqualuxe_enable_ajax_add_to_cart', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_ajax_add_to_cart', array(
        'label' => esc_html__('Enable AJAX Add to Cart', 'aqualuxe'),
        'description' => esc_html__('Enable AJAX add to cart functionality.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 310,
    )));

    // Enable Product Filter
    $wp_customize->add_setting('aqualuxe_enable_product_filter', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_product_filter', array(
        'label' => esc_html__('Enable Product Filter', 'aqualuxe'),
        'description' => esc_html__('Enable advanced product filtering.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 320,
    )));

    // Filter Position
    $wp_customize->add_setting('aqualuxe_filter_position', array(
        'default' => 'sidebar',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_filter_position', array(
        'label' => esc_html__('Filter Position', 'aqualuxe'),
        'description' => esc_html__('Select the position for product filters.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'sidebar' => esc_html__('Sidebar', 'aqualuxe'),
            'top' => esc_html__('Above Products', 'aqualuxe'),
            'offcanvas' => esc_html__('Off-Canvas', 'aqualuxe'),
        ),
        'priority' => 330,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_product_filter', true);
        },
    ));

    // Enable Infinite Scroll
    $wp_customize->add_setting('aqualuxe_enable_infinite_scroll', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_infinite_scroll', array(
        'label' => esc_html__('Enable Infinite Scroll', 'aqualuxe'),
        'description' => esc_html__('Enable infinite scroll for product listings.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 340,
    )));

    // Infinite Scroll Type
    $wp_customize->add_setting('aqualuxe_infinite_scroll_type', array(
        'default' => 'button',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_infinite_scroll_type', array(
        'label' => esc_html__('Infinite Scroll Type', 'aqualuxe'),
        'description' => esc_html__('Select the type of infinite scroll.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'button' => esc_html__('Load More Button', 'aqualuxe'),
            'scroll' => esc_html__('Automatic on Scroll', 'aqualuxe'),
        ),
        'priority' => 350,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_infinite_scroll', false);
        },
    ));

    // Cart & Checkout
    $wp_customize->add_setting('aqualuxe_woocommerce_cart_checkout_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_woocommerce_cart_checkout_heading', array(
        'label' => esc_html__('Cart & Checkout', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 360,
    )));

    // Cart Layout
    $wp_customize->add_setting('aqualuxe_cart_layout', array(
        'default' => 'standard',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_cart_layout', array(
        'label' => esc_html__('Cart Layout', 'aqualuxe'),
        'description' => esc_html__('Select the layout for the cart page.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'standard' => esc_html__('Standard', 'aqualuxe'),
            'modern' => esc_html__('Modern', 'aqualuxe'),
            'minimal' => esc_html__('Minimal', 'aqualuxe'),
        ),
        'priority' => 370,
    ));

    // Checkout Layout
    $wp_customize->add_setting('aqualuxe_checkout_layout', array(
        'default' => 'standard',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_checkout_layout', array(
        'label' => esc_html__('Checkout Layout', 'aqualuxe'),
        'description' => esc_html__('Select the layout for the checkout page.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'standard' => esc_html__('Standard', 'aqualuxe'),
            'modern' => esc_html__('Modern', 'aqualuxe'),
            'multistep' => esc_html__('Multi-Step', 'aqualuxe'),
        ),
        'priority' => 380,
    ));

    // Enable Mini Cart
    $wp_customize->add_setting('aqualuxe_enable_mini_cart', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_mini_cart', array(
        'label' => esc_html__('Enable Mini Cart', 'aqualuxe'),
        'description' => esc_html__('Enable mini cart dropdown in the header.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 390,
    )));

    // Mini Cart Style
    $wp_customize->add_setting('aqualuxe_mini_cart_style', array(
        'default' => 'dropdown',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_mini_cart_style', array(
        'label' => esc_html__('Mini Cart Style', 'aqualuxe'),
        'description' => esc_html__('Select the style for the mini cart.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'dropdown' => esc_html__('Dropdown', 'aqualuxe'),
            'offcanvas' => esc_html__('Off-Canvas', 'aqualuxe'),
            'popup' => esc_html__('Popup', 'aqualuxe'),
        ),
        'priority' => 400,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_mini_cart', true);
        },
    ));

    // Enable Order Tracking
    $wp_customize->add_setting('aqualuxe_enable_order_tracking', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_order_tracking', array(
        'label' => esc_html__('Enable Order Tracking', 'aqualuxe'),
        'description' => esc_html__('Enable order tracking functionality.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 410,
    )));

    // Order Tracking Page
    $wp_customize->add_setting('aqualuxe_order_tracking_page', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_order_tracking_page', array(
        'label' => esc_html__('Order Tracking Page', 'aqualuxe'),
        'description' => esc_html__('Select the page to use as the order tracking page.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'dropdown-pages',
        'priority' => 420,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_order_tracking', true);
        },
    ));

    // Enable Multi-Currency
    $wp_customize->add_setting('aqualuxe_enable_multi_currency', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_multi_currency', array(
        'label' => esc_html__('Enable Multi-Currency', 'aqualuxe'),
        'description' => esc_html__('Enable multi-currency support.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'priority' => 430,
    )));

    // Currency Switcher Position
    $wp_customize->add_setting('aqualuxe_currency_switcher_position', array(
        'default' => 'header',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_currency_switcher_position', array(
        'label' => esc_html__('Currency Switcher Position', 'aqualuxe'),
        'description' => esc_html__('Select where to display the currency switcher.', 'aqualuxe'),
        'section' => 'aqualuxe_woocommerce',
        'type' => 'select',
        'choices' => array(
            'header' => esc_html__('Header', 'aqualuxe'),
            'top_bar' => esc_html__('Top Bar', 'aqualuxe'),
            'footer' => esc_html__('Footer', 'aqualuxe'),
            'product' => esc_html__('Product Page', 'aqualuxe'),
        ),
        'priority' => 440,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multi_currency', false);
        },
    ));
}
add_action('customize_register', 'aqualuxe_customize_register_woocommerce');