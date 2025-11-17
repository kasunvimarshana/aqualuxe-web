<?php
/**
 * Register widget areas
 *
 * @package AquaLuxe
 */

/**
 * Register widget areas.
 */
function aqualuxe_widgets_init() {
    // Main Sidebar
    register_sidebar(
        array(
            'name'          => esc_html__('Sidebar', 'aqualuxe'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here to appear in your sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    // Shop Sidebar
    register_sidebar(
        array(
            'name'          => esc_html__('Shop Sidebar', 'aqualuxe'),
            'id'            => 'shop-sidebar',
            'description'   => esc_html__('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    // Footer Widget Areas
    $footer_widget_areas = apply_filters('aqualuxe_footer_widget_areas', 4);

    for ($i = 1; $i <= $footer_widget_areas; $i++) {
        register_sidebar(
            array(
                'name'          => sprintf(esc_html__('Footer %d', 'aqualuxe'), $i),
                'id'            => 'footer-' . $i,
                'description'   => sprintf(esc_html__('Add widgets here to appear in footer column %d.', 'aqualuxe'), $i),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            )
        );
    }

    // Top Bar Widget Area
    register_sidebar(
        array(
            'name'          => esc_html__('Top Bar', 'aqualuxe'),
            'id'            => 'top-bar',
            'description'   => esc_html__('Add widgets here to appear in the top bar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<span class="widget-title">',
            'after_title'   => '</span>',
        )
    );

    // Header Widget Area
    register_sidebar(
        array(
            'name'          => esc_html__('Header', 'aqualuxe'),
            'id'            => 'header',
            'description'   => esc_html__('Add widgets here to appear in the header.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<span class="widget-title">',
            'after_title'   => '</span>',
        )
    );

    // WooCommerce Product Filters
    if (class_exists('WooCommerce')) {
        register_sidebar(
            array(
                'name'          => esc_html__('Product Filters', 'aqualuxe'),
                'id'            => 'product-filters',
                'description'   => esc_html__('Add widgets here to appear in the product filters area.', 'aqualuxe'),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            )
        );
    }
}
add_action('widgets_init', 'aqualuxe_widgets_init');