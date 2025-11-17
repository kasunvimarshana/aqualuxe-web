<?php
/**
 * AquaLuxe Theme Customizer - Header Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add header settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_header($wp_customize) {
    // Add Header section
    $wp_customize->add_section('aqualuxe_header', array(
        'title'    => __('Header', 'aqualuxe'),
        'priority' => 60,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Header Layout
    $wp_customize->add_setting('aqualuxe_header_layout', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_header_layout', array(
        'label'       => __('Header Layout', 'aqualuxe'),
        'description' => __('Choose the layout for your header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'select',
        'choices'     => array(
            'default'     => __('Default', 'aqualuxe'),
            'centered'    => __('Centered', 'aqualuxe'),
            'transparent' => __('Transparent', 'aqualuxe'),
            'split'       => __('Split Menu', 'aqualuxe'),
            'minimal'     => __('Minimal', 'aqualuxe'),
        ),
    ));
    
    // Sticky Header
    $wp_customize->add_setting('aqualuxe_header_sticky', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_header_sticky', array(
        'label'       => __('Sticky Header', 'aqualuxe'),
        'description' => __('Enable sticky header that stays at the top when scrolling.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
    ));
    
    // Sticky Header Style
    $wp_customize->add_setting('aqualuxe_header_sticky_style', array(
        'default'           => 'fade',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_header_sticky_style', array(
        'label'       => __('Sticky Header Style', 'aqualuxe'),
        'description' => __('Choose the style for the sticky header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'select',
        'choices'     => array(
            'fade'    => __('Fade In', 'aqualuxe'),
            'slide'   => __('Slide Down', 'aqualuxe'),
            'shrink'  => __('Shrink', 'aqualuxe'),
            'fixed'   => __('Fixed', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_header_sticky', true);
        },
    ));
    
    // Header Height
    $wp_customize->add_setting('aqualuxe_header_height', array(
        'default'           => 80,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_header_height', array(
        'label'       => __('Header Height (px)', 'aqualuxe'),
        'description' => __('Set the height of the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 50,
            'max'  => 200,
            'step' => 1,
        ),
    ));
    
    // Mobile Header Height
    $wp_customize->add_setting('aqualuxe_mobile_header_height', array(
        'default'           => 60,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_mobile_header_height', array(
        'label'       => __('Mobile Header Height (px)', 'aqualuxe'),
        'description' => __('Set the height of the header on mobile devices.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 40,
            'max'  => 150,
            'step' => 1,
        ),
    ));
    
    // Header Padding
    $wp_customize->add_setting('aqualuxe_header_padding', array(
        'default'           => 20,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_header_padding', array(
        'label'       => __('Header Padding (px)', 'aqualuxe'),
        'description' => __('Set the padding for the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ),
    ));
    
    // Show Search
    $wp_customize->add_setting('aqualuxe_header_show_search', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_header_show_search', array(
        'label'       => __('Show Search', 'aqualuxe'),
        'description' => __('Display search icon in the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
    ));
    
    // Show Cart
    $wp_customize->add_setting('aqualuxe_header_show_cart', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_header_show_cart', array(
        'label'       => __('Show Cart', 'aqualuxe'),
        'description' => __('Display cart icon in the header (requires WooCommerce).', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
        'active_callback' => 'aqualuxe_is_woocommerce_active',
    ));
    
    // Show Account
    $wp_customize->add_setting('aqualuxe_header_show_account', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_header_show_account', array(
        'label'       => __('Show Account', 'aqualuxe'),
        'description' => __('Display account icon in the header (requires WooCommerce).', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
        'active_callback' => 'aqualuxe_is_woocommerce_active',
    ));
    
    // Show Wishlist
    $wp_customize->add_setting('aqualuxe_header_show_wishlist', array(
        'default'           => true,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_header_show_wishlist', array(
        'label'       => __('Show Wishlist', 'aqualuxe'),
        'description' => __('Display wishlist icon in the header (requires WooCommerce).', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
        'active_callback' => 'aqualuxe_is_woocommerce_active',
    ));
    
    // Show Social Icons
    $wp_customize->add_setting('aqualuxe_header_show_social', array(
        'default'           => false,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_header_show_social', array(
        'label'       => __('Show Social Icons', 'aqualuxe'),
        'description' => __('Display social icons in the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
    ));
    
    // Top Bar
    $wp_customize->add_setting('aqualuxe_header_show_topbar', array(
        'default'           => false,
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_header_show_topbar', array(
        'label'       => __('Show Top Bar', 'aqualuxe'),
        'description' => __('Display a top bar above the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
    ));
    
    // Top Bar Content
    $wp_customize->add_setting('aqualuxe_topbar_content_left', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('aqualuxe_topbar_content_left', array(
        'label'       => __('Top Bar Left Content', 'aqualuxe'),
        'description' => __('Content for the left side of the top bar. Supports HTML and shortcodes.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'textarea',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_header_show_topbar', false);
        },
    ));
    
    $wp_customize->add_setting('aqualuxe_topbar_content_right', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'wp_kses_post',
    ));
    
    $wp_customize->add_control('aqualuxe_topbar_content_right', array(
        'label'       => __('Top Bar Right Content', 'aqualuxe'),
        'description' => __('Content for the right side of the top bar. Supports HTML and shortcodes.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'textarea',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_header_show_topbar', false);
        },
    ));
    
    // Top Bar Background Color
    $wp_customize->add_setting('aqualuxe_topbar_background_color', array(
        'default'           => '#f5f5f5',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_topbar_background_color', array(
        'label'       => __('Top Bar Background Color', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_header_show_topbar', false);
        },
    )));
    
    // Top Bar Text Color
    $wp_customize->add_setting('aqualuxe_topbar_text_color', array(
        'default'           => '#333333',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_topbar_text_color', array(
        'label'       => __('Top Bar Text Color', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_header_show_topbar', false);
        },
    )));
    
    // Header Background Color
    $wp_customize->add_setting('aqualuxe_header_background_color', array(
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_header_background_color', array(
        'label'       => __('Header Background Color', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
    )));
    
    // Header Text Color
    $wp_customize->add_setting('aqualuxe_header_text_color', array(
        'default'           => '#333333',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_header_text_color', array(
        'label'       => __('Header Text Color', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
    )));
    
    // Transparent Header Text Color
    $wp_customize->add_setting('aqualuxe_transparent_header_text_color', array(
        'default'           => '#ffffff',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_transparent_header_text_color', array(
        'label'       => __('Transparent Header Text Color', 'aqualuxe'),
        'description' => __('Text color for transparent header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_header_layout', 'default') === 'transparent';
        },
    )));
    
    // Transparent Header Scheme
    $wp_customize->add_setting('aqualuxe_transparent_header_scheme', array(
        'default'           => 'auto',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_transparent_header_scheme', array(
        'label'       => __('Transparent Header Scheme', 'aqualuxe'),
        'description' => __('Choose the color scheme for transparent header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'select',
        'choices'     => array(
            'auto'  => __('Auto (Based on Background)', 'aqualuxe'),
            'light' => __('Light (Dark Text)', 'aqualuxe'),
            'dark'  => __('Dark (Light Text)', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_header_layout', 'default') === 'transparent';
        },
    ));
    
    // Navigation Style
    $wp_customize->add_setting('aqualuxe_navigation_style', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_navigation_style', array(
        'label'       => __('Navigation Style', 'aqualuxe'),
        'description' => __('Choose the style for the main navigation.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'select',
        'choices'     => array(
            'default'    => __('Default', 'aqualuxe'),
            'underline'  => __('Underline', 'aqualuxe'),
            'overline'   => __('Overline', 'aqualuxe'),
            'bordered'   => __('Bordered', 'aqualuxe'),
            'button'     => __('Button', 'aqualuxe'),
        ),
    ));
    
    // Mobile Menu Style
    $wp_customize->add_setting('aqualuxe_mobile_menu_style', array(
        'default'           => 'slide',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_mobile_menu_style', array(
        'label'       => __('Mobile Menu Style', 'aqualuxe'),
        'description' => __('Choose the style for the mobile menu.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'select',
        'choices'     => array(
            'slide'    => __('Slide In', 'aqualuxe'),
            'dropdown' => __('Dropdown', 'aqualuxe'),
            'fullscreen' => __('Fullscreen', 'aqualuxe'),
        ),
    ));
    
    // Mobile Menu Position
    $wp_customize->add_setting('aqualuxe_mobile_menu_position', array(
        'default'           => 'right',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_mobile_menu_position', array(
        'label'       => __('Mobile Menu Position', 'aqualuxe'),
        'description' => __('Choose the position for the slide-in mobile menu.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'select',
        'choices'     => array(
            'left'  => __('Left', 'aqualuxe'),
            'right' => __('Right', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_mobile_menu_style', 'slide') === 'slide';
        },
    ));
    
    // Mobile Menu Breakpoint
    $wp_customize->add_setting('aqualuxe_mobile_menu_breakpoint', array(
        'default'           => 992,
        'transport'         => 'refresh',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_mobile_menu_breakpoint', array(
        'label'       => __('Mobile Menu Breakpoint (px)', 'aqualuxe'),
        'description' => __('Set the breakpoint for switching to mobile menu.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 480,
            'max'  => 1200,
            'step' => 1,
        ),
    ));
}

// Add the header section to the customizer
add_action('customize_register', 'aqualuxe_customize_register_header');

/**
 * Add header CSS to the head.
 */
function aqualuxe_header_css() {
    $header_height = get_theme_mod('aqualuxe_header_height', 80);
    $mobile_header_height = get_theme_mod('aqualuxe_mobile_header_height', 60);
    $header_padding = get_theme_mod('aqualuxe_header_padding', 20);
    $header_background_color = get_theme_mod('aqualuxe_header_background_color', '#ffffff');
    $header_text_color = get_theme_mod('aqualuxe_header_text_color', '#333333');
    $transparent_header_text_color = get_theme_mod('aqualuxe_transparent_header_text_color', '#ffffff');
    $topbar_background_color = get_theme_mod('aqualuxe_topbar_background_color', '#f5f5f5');
    $topbar_text_color = get_theme_mod('aqualuxe_topbar_text_color', '#333333');
    $mobile_menu_breakpoint = get_theme_mod('aqualuxe_mobile_menu_breakpoint', 992);
    $navigation_style = get_theme_mod('aqualuxe_navigation_style', 'default');
    $mobile_menu_position = get_theme_mod('aqualuxe_mobile_menu_position', 'right');
    
    ?>
    <style type="text/css">
        :root {
            --aqualuxe-header-height: <?php echo esc_attr($header_height); ?>px;
            --aqualuxe-mobile-header-height: <?php echo esc_attr($mobile_header_height); ?>px;
            --aqualuxe-header-padding: <?php echo esc_attr($header_padding); ?>px;
            --aqualuxe-header-background-color: <?php echo esc_attr($header_background_color); ?>;
            --aqualuxe-header-text-color: <?php echo esc_attr($header_text_color); ?>;
            --aqualuxe-transparent-header-text-color: <?php echo esc_attr($transparent_header_text_color); ?>;
            --aqualuxe-topbar-background-color: <?php echo esc_attr($topbar_background_color); ?>;
            --aqualuxe-topbar-text-color: <?php echo esc_attr($topbar_text_color); ?>;
            --aqualuxe-mobile-menu-breakpoint: <?php echo esc_attr($mobile_menu_breakpoint); ?>px;
        }
        
        /* Header */
        .site-header {
            height: var(--aqualuxe-header-height);
            padding: 0 var(--aqualuxe-header-padding);
            background-color: var(--aqualuxe-header-background-color);
            color: var(--aqualuxe-header-text-color);
        }
        
        @media (max-width: <?php echo esc_attr($mobile_menu_breakpoint - 1); ?>px) {
            .site-header {
                height: var(--aqualuxe-mobile-header-height);
            }
        }
        
        /* Transparent Header */
        .transparent-header {
            background-color: transparent;
            position: absolute;
            width: 100%;
            z-index: 100;
        }
        
        .transparent-header.scheme-dark {
            color: var(--aqualuxe-transparent-header-text-color);
        }
        
        .transparent-header.scheme-light {
            color: var(--aqualuxe-header-text-color);
        }
        
        .transparent-header.scrolled {
            background-color: var(--aqualuxe-header-background-color);
            position: fixed;
            top: 0;
            color: var(--aqualuxe-header-text-color);
        }
        
        /* Sticky Header */
        .sticky-header.is-sticky {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
            background-color: var(--aqualuxe-header-background-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            animation: stickyHeaderFadeIn 0.3s ease;
        }
        
        @keyframes stickyHeaderFadeIn {
            from {
                opacity: 0;
                transform: translateY(-100%);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Top Bar */
        .top-bar {
            background-color: var(--aqualuxe-topbar-background-color);
            color: var(--aqualuxe-topbar-text-color);
            padding: 8px var(--aqualuxe-header-padding);
            font-size: 0.875rem;
        }
        
        .top-bar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* Navigation Styles */
        <?php if ($navigation_style === 'underline') : ?>
        .main-navigation .menu > li > a {
            position: relative;
        }
        
        .main-navigation .menu > li > a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: currentColor;
            transition: width 0.3s ease;
        }
        
        .main-navigation .menu > li:hover > a::after,
        .main-navigation .menu > li.current-menu-item > a::after {
            width: 100%;
        }
        <?php elseif ($navigation_style === 'overline') : ?>
        .main-navigation .menu > li > a {
            position: relative;
        }
        
        .main-navigation .menu > li > a::before {
            content: '';
            position: absolute;
            top: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: currentColor;
            transition: width 0.3s ease;
        }
        
        .main-navigation .menu > li:hover > a::before,
        .main-navigation .menu > li.current-menu-item > a::before {
            width: 100%;
        }
        <?php elseif ($navigation_style === 'bordered') : ?>
        .main-navigation .menu > li > a {
            border: 1px solid transparent;
            padding: 5px 10px;
            border-radius: 3px;
            transition: border-color 0.3s ease;
        }
        
        .main-navigation .menu > li:hover > a,
        .main-navigation .menu > li.current-menu-item > a {
            border-color: currentColor;
        }
        <?php elseif ($navigation_style === 'button') : ?>
        .main-navigation .menu > li > a {
            padding: 5px 15px;
            border-radius: 3px;
            transition: all 0.3s ease;
        }
        
        .main-navigation .menu > li:hover > a,
        .main-navigation .menu > li.current-menu-item > a {
            background-color: var(--aqualuxe-primary-color);
            color: #ffffff;
        }
        <?php endif; ?>
        
        /* Mobile Menu */
        @media (max-width: <?php echo esc_attr($mobile_menu_breakpoint - 1); ?>px) {
            .main-navigation {
                display: none;
                position: fixed;
                top: 0;
                <?php echo $mobile_menu_position === 'left' ? 'left: 0;' : 'right: 0;'; ?>
                width: 300px;
                height: 100%;
                background-color: var(--aqualuxe-header-background-color);
                z-index: 9999;
                overflow-y: auto;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                padding: 60px 20px 20px;
                transform: translateX(<?php echo $mobile_menu_position === 'left' ? '-100%' : '100%'; ?>);
                transition: transform 0.3s ease;
            }
            
            .main-navigation.active {
                display: block;
                transform: translateX(0);
            }
            
            .menu-toggle {
                display: block;
            }
            
            .main-navigation .menu {
                flex-direction: column;
            }
            
            .main-navigation .menu > li {
                margin: 0;
                padding: 10px 0;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }
            
            .main-navigation .menu > li > a {
                padding: 5px 0;
            }
            
            .main-navigation .sub-menu {
                position: static;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                background-color: transparent;
                padding-left: 15px;
                display: none;
            }
            
            .menu-item-has-children > a {
                position: relative;
            }
            
            .sub-menu-toggle {
                position: absolute;
                right: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 24px;
                height: 24px;
                cursor: pointer;
                display: inline-block;
            }
            
            .sub-menu-toggle::before,
            .sub-menu-toggle::after {
                content: '';
                position: absolute;
                background-color: currentColor;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }
            
            .sub-menu-toggle::before {
                width: 12px;
                height: 2px;
            }
            
            .sub-menu-toggle::after {
                width: 2px;
                height: 12px;
                transition: transform 0.3s ease;
            }
            
            .sub-menu-open > a > .sub-menu-toggle::after {
                transform: translate(-50%, -50%) rotate(90deg);
            }
            
            .sub-menu-open > .sub-menu {
                display: block;
            }
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_header_css');

/**
 * Check if WooCommerce is active.
 *
 * @return bool Whether WooCommerce is active.
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}

/**
 * Display the header based on the selected layout.
 */
function aqualuxe_display_header() {
    $header_layout = get_theme_mod('aqualuxe_header_layout', 'default');
    $show_topbar = get_theme_mod('aqualuxe_header_show_topbar', false);
    
    if ($show_topbar) {
        aqualuxe_display_topbar();
    }
    
    if ($header_layout === 'default') {
        get_template_part('templates/partials/header', 'default');
    } elseif ($header_layout === 'centered') {
        get_template_part('templates/partials/header', 'centered');
    } elseif ($header_layout === 'transparent') {
        get_template_part('templates/partials/header', 'transparent');
    } elseif ($header_layout === 'split') {
        get_template_part('templates/partials/header', 'split');
    } elseif ($header_layout === 'minimal') {
        get_template_part('templates/partials/header', 'minimal');
    } else {
        get_template_part('templates/partials/header', 'default');
    }
}

/**
 * Display the top bar.
 */
function aqualuxe_display_topbar() {
    $topbar_content_left = get_theme_mod('aqualuxe_topbar_content_left', '');
    $topbar_content_right = get_theme_mod('aqualuxe_topbar_content_right', '');
    
    if (empty($topbar_content_left) && empty($topbar_content_right)) {
        return;
    }
    
    ?>
    <div class="top-bar">
        <div class="<?php echo esc_attr(aqualuxe_get_container_class()); ?>">
            <div class="top-bar-content">
                <div class="top-bar-left">
                    <?php echo do_shortcode(wp_kses_post($topbar_content_left)); ?>
                </div>
                <div class="top-bar-right">
                    <?php echo do_shortcode(wp_kses_post($topbar_content_right)); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Get transparent header scheme.
 *
 * @return string Header scheme.
 */
function aqualuxe_get_transparent_header_scheme() {
    $scheme = get_theme_mod('aqualuxe_transparent_header_scheme', 'auto');
    
    if ($scheme !== 'auto') {
        return $scheme;
    }
    
    // Auto detect based on the page background
    // This is a simplified version, in a real theme you would check the actual page background
    if (is_front_page() || is_home()) {
        return 'dark'; // Assuming dark scheme (light text) for home page
    } elseif (is_singular()) {
        // Check if the post has a dark featured image
        // This is a placeholder, in a real theme you would analyze the featured image
        return 'light'; // Assuming light scheme (dark text) for other pages
    }
    
    return 'light';
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_header_customize_preview_js() {
    wp_add_inline_script('aqualuxe-customizer', '
        // Header height
        wp.customize("aqualuxe_header_height", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-header-height", to + "px");
                $(".site-header").css("height", to + "px");
            });
        });
        
        // Mobile header height
        wp.customize("aqualuxe_mobile_header_height", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-mobile-header-height", to + "px");
            });
        });
        
        // Header padding
        wp.customize("aqualuxe_header_padding", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-header-padding", to + "px");
                $(".site-header").css("padding-left", to + "px");
                $(".site-header").css("padding-right", to + "px");
            });
        });
        
        // Header background color
        wp.customize("aqualuxe_header_background_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-header-background-color", to);
                $(".site-header:not(.transparent-header)").css("background-color", to);
            });
        });
        
        // Header text color
        wp.customize("aqualuxe_header_text_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-header-text-color", to);
                $(".site-header:not(.transparent-header)").css("color", to);
            });
        });
        
        // Top bar background color
        wp.customize("aqualuxe_topbar_background_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-topbar-background-color", to);
                $(".top-bar").css("background-color", to);
            });
        });
        
        // Top bar text color
        wp.customize("aqualuxe_topbar_text_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-topbar-text-color", to);
                $(".top-bar").css("color", to);
            });
        });
    ');
}
add_action('customize_preview_init', 'aqualuxe_header_customize_preview_js', 20);