<?php
/**
 * AquaLuxe Widgets Integration
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register and load enhanced widgets
 */
function aqualuxe_load_enhanced_widgets() {
    // Load widget files
    require_once AQUALUXE_DIR . '/inc/widgets/recent-posts-enhanced.php';
    require_once AQUALUXE_DIR . '/inc/widgets/social-links-enhanced.php';
    require_once AQUALUXE_DIR . '/inc/widgets/mini-cart.php';
    
    // Load original widgets for backward compatibility
    require_once AQUALUXE_DIR . '/inc/widgets/recent-posts.php';
    require_once AQUALUXE_DIR . '/inc/widgets/social-links.php';
}
add_action('widgets_init', 'aqualuxe_load_enhanced_widgets', 5);

/**
 * Register widget areas
 */
function aqualuxe_register_widget_areas() {
    // Primary Sidebar
    register_sidebar(array(
        'name'          => __('Primary Sidebar', 'aqualuxe'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Footer Widget Areas
    $footer_widget_areas = apply_filters('aqualuxe_footer_widget_areas', 4);
    
    for ($i = 1; $i <= $footer_widget_areas; $i++) {
        register_sidebar(array(
            'name'          => sprintf(__('Footer %d', 'aqualuxe'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(__('Add widgets here to appear in footer column %d.', 'aqualuxe'), $i),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ));
    }
    
    // Shop Sidebar
    if (class_exists('WooCommerce')) {
        register_sidebar(array(
            'name'          => __('Shop Sidebar', 'aqualuxe'),
            'id'            => 'shop-sidebar',
            'description'   => __('Add widgets here to appear in your shop sidebar.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
        
        // Product Filters
        register_sidebar(array(
            'name'          => __('Product Filters', 'aqualuxe'),
            'id'            => 'product-filters',
            'description'   => __('Add widgets here to appear in the product filters area.', 'aqualuxe'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ));
    }
    
    // Header Widget Area
    register_sidebar(array(
        'name'          => __('Header Area', 'aqualuxe'),
        'id'            => 'header-area',
        'description'   => __('Add widgets here to appear in your header area.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Top Bar Widget Areas
    register_sidebar(array(
        'name'          => __('Top Bar Left', 'aqualuxe'),
        'id'            => 'top-bar-left',
        'description'   => __('Add widgets here to appear in the left side of the top bar.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="widget-title">',
        'after_title'   => '</span>',
    ));
    
    register_sidebar(array(
        'name'          => __('Top Bar Right', 'aqualuxe'),
        'id'            => 'top-bar-right',
        'description'   => __('Add widgets here to appear in the right side of the top bar.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="widget-title">',
        'after_title'   => '</span>',
    ));
    
    // Off-Canvas Menu Widget Area
    register_sidebar(array(
        'name'          => __('Off-Canvas Menu', 'aqualuxe'),
        'id'            => 'off-canvas-menu',
        'description'   => __('Add widgets here to appear in the off-canvas menu area.', 'aqualuxe'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'aqualuxe_register_widget_areas');

/**
 * Enqueue widget styles and scripts
 */
function aqualuxe_enqueue_widget_assets() {
    // Enqueue widget styles
    wp_enqueue_style(
        'aqualuxe-widgets-style',
        AQUALUXE_URI . '/assets/css/widgets.css',
        array(),
        AQUALUXE_VERSION
    );
    
    // Enqueue widget scripts
    wp_enqueue_script(
        'aqualuxe-widgets-script',
        AQUALUXE_URI . '/assets/js/widgets.js',
        array('jquery'),
        AQUALUXE_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_widget_assets');

/**
 * Create combined CSS file for all widgets
 */
function aqualuxe_create_combined_widget_css() {
    // Get widget CSS files
    $css_files = array(
        AQUALUXE_DIR . '/assets/css/widgets/recent-posts.css',
        AQUALUXE_DIR . '/assets/css/widgets/social-links.css',
        AQUALUXE_DIR . '/assets/css/widgets/mini-cart.css',
    );
    
    // Create combined CSS content
    $combined_css = "/**\n * AquaLuxe Combined Widget Styles\n * Auto-generated file\n */\n\n";
    
    foreach ($css_files as $file) {
        if (file_exists($file)) {
            $combined_css .= "/* " . basename($file) . " */\n";
            $combined_css .= file_get_contents($file) . "\n\n";
        }
    }
    
    // Write combined CSS file
    $combined_file = AQUALUXE_DIR . '/assets/css/widgets.css';
    file_put_contents($combined_file, $combined_css);
}

/**
 * Create combined JS file for all widgets
 */
function aqualuxe_create_combined_widget_js() {
    // Get widget JS files
    $js_files = array(
        AQUALUXE_DIR . '/assets/js/widgets/mini-cart.js',
    );
    
    // Create combined JS content
    $combined_js = "/**\n * AquaLuxe Combined Widget Scripts\n * Auto-generated file\n */\n\n";
    
    foreach ($js_files as $file) {
        if (file_exists($file)) {
            $combined_js .= "/* " . basename($file) . " */\n";
            $combined_js .= file_get_contents($file) . "\n\n";
        }
    }
    
    // Write combined JS file
    $combined_file = AQUALUXE_DIR . '/assets/js/widgets.js';
    file_put_contents($combined_file, $combined_js);
}

/**
 * Generate combined widget assets on theme activation
 */
function aqualuxe_generate_widget_assets() {
    aqualuxe_create_combined_widget_css();
    aqualuxe_create_combined_widget_js();
}
add_action('after_switch_theme', 'aqualuxe_generate_widget_assets');

/**
 * Add widget options to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_widget_customizer_options($wp_customize) {
    // Add section for widget options
    $wp_customize->add_section('aqualuxe_widget_options', array(
        'title'    => __('Widget Options', 'aqualuxe'),
        'priority' => 120,
    ));
    
    // Recent Posts Widget Options
    $wp_customize->add_setting('aqualuxe_recent_posts_default_layout', array(
        'default'           => 'list',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_recent_posts_default_layout', array(
        'label'    => __('Default Recent Posts Layout', 'aqualuxe'),
        'section'  => 'aqualuxe_widget_options',
        'type'     => 'select',
        'choices'  => array(
            'list'     => __('List', 'aqualuxe'),
            'grid'     => __('Grid', 'aqualuxe'),
            'compact'  => __('Compact', 'aqualuxe'),
            'featured' => __('Featured', 'aqualuxe'),
        ),
    ));
    
    // Social Links Widget Options
    $wp_customize->add_setting('aqualuxe_social_links_default_style', array(
        'default'           => 'filled',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_social_links_default_style', array(
        'label'    => __('Default Social Links Style', 'aqualuxe'),
        'section'  => 'aqualuxe_widget_options',
        'type'     => 'select',
        'choices'  => array(
            'filled'  => __('Filled', 'aqualuxe'),
            'outline' => __('Outline', 'aqualuxe'),
            'minimal' => __('Minimal', 'aqualuxe'),
        ),
    ));
    
    // Mini Cart Widget Options
    if (class_exists('WooCommerce')) {
        $wp_customize->add_setting('aqualuxe_mini_cart_default_style', array(
            'default'           => 'dropdown',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        
        $wp_customize->add_control('aqualuxe_mini_cart_default_style', array(
            'label'    => __('Default Mini Cart Style', 'aqualuxe'),
            'section'  => 'aqualuxe_widget_options',
            'type'     => 'select',
            'choices'  => array(
                'dropdown' => __('Dropdown', 'aqualuxe'),
                'sidebar'  => __('Sidebar', 'aqualuxe'),
                'popup'    => __('Popup', 'aqualuxe'),
            ),
        ));
    }
}
add_action('customize_register', 'aqualuxe_widget_customizer_options');