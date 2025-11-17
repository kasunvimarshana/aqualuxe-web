<?php
/**
 * AquaLuxe Dark Mode Module Helper Functions
 *
 * @package AquaLuxe
 * @subpackage Modules/Dark_Mode
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Check if dark mode is active
 *
 * @return bool
 */
function aqualuxe_is_dark_mode_active() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'dark-mode' );
    
    if ( $module ) {
        return true;
    }
    
    return false;
}

/**
 * Get dark mode default setting
 *
 * @return string 'light' or 'dark'
 */
function aqualuxe_get_dark_mode_default() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'dark-mode' );
    
    if ( $module ) {
        return $module->get_setting( 'default_mode', 'light' );
    }
    
    return 'light';
}

/**
 * Check if auto detect is enabled
 *
 * @return bool
 */
function aqualuxe_is_dark_mode_auto_detect() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'dark-mode' );
    
    if ( $module ) {
        return $module->get_setting( 'auto_detect', true );
    }
    
    return true;
}

/**
 * Get dark mode toggle style
 *
 * @param string $location Location (header, footer, mobile)
 * @return string
 */
function aqualuxe_get_dark_mode_toggle_style( $location = 'header' ) {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'dark-mode' );
    
    if ( ! $module ) {
        return 'switch';
    }
    
    // Get style based on location
    if ( $location === 'footer' ) {
        return $module->get_setting( 'toggle_style_footer', 'switch' );
    } elseif ( $location === 'mobile' ) {
        return $module->get_setting( 'toggle_style_mobile', 'switch' );
    }
    
    return $module->get_setting( 'toggle_style', 'switch' );
}

/**
 * Check if dark mode toggle should be shown in location
 *
 * @param string $location Location (header, footer, mobile)
 * @return bool
 */
function aqualuxe_show_dark_mode_toggle( $location = 'header' ) {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'dark-mode' );
    
    if ( ! $module ) {
        return false;
    }
    
    // Check based on location
    if ( $location === 'header' ) {
        return $module->get_setting( 'show_in_header', true );
    } elseif ( $location === 'footer' ) {
        return $module->get_setting( 'show_in_footer', false );
    } elseif ( $location === 'mobile' ) {
        return $module->get_setting( 'show_in_mobile', true );
    }
    
    return false;
}

/**
 * Check if dark mode preference should be saved
 *
 * @return bool
 */
function aqualuxe_save_dark_mode_preference() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'dark-mode' );
    
    if ( $module ) {
        return $module->get_setting( 'save_preference', true );
    }
    
    return true;
}

/**
 * Get dark mode cookie duration
 *
 * @return int
 */
function aqualuxe_get_dark_mode_cookie_duration() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'dark-mode' );
    
    if ( $module ) {
        return $module->get_setting( 'cookie_duration', 30 );
    }
    
    return 30;
}

/**
 * Get dark mode custom colors
 *
 * @return array
 */
function aqualuxe_get_dark_mode_colors() {
    // Get module instance
    $module = AquaLuxe_Module_Loader::instance()->get_active_module( 'dark-mode' );
    
    if ( $module ) {
        return $module->get_custom_colors();
    }
    
    return array(
        'background' => '#121212',
        'text' => '#ffffff',
        'link' => '#4dabf7',
        'border' => '#333333',
    );
}

/**
 * Get dark mode CSS variables
 *
 * @return string
 */
function aqualuxe_get_dark_mode_css_variables() {
    // Get colors
    $colors = aqualuxe_get_dark_mode_colors();
    
    // Build CSS variables
    $css = ':root {' . "\n";
    $css .= '  --dark-mode-bg: ' . esc_attr( $colors['background'] ) . ';' . "\n";
    $css .= '  --dark-mode-text: ' . esc_attr( $colors['text'] ) . ';' . "\n";
    $css .= '  --dark-mode-link: ' . esc_attr( $colors['link'] ) . ';' . "\n";
    $css .= '  --dark-mode-border: ' . esc_attr( $colors['border'] ) . ';' . "\n";
    $css .= '}';
    
    return $css;
}

/**
 * Add dark mode toggle to menu
 *
 * @param string $items Menu items
 * @param object $args Menu arguments
 * @return string
 */
function aqualuxe_add_dark_mode_toggle_to_menu( $items, $args ) {
    // Check if dark mode is active
    if ( ! aqualuxe_is_dark_mode_active() ) {
        return $items;
    }
    
    // Check if menu location is primary
    if ( ! isset( $args->theme_location ) || $args->theme_location !== 'primary' ) {
        return $items;
    }
    
    // Get toggle style
    $style = aqualuxe_get_dark_mode_toggle_style( 'header' );
    
    // Get toggle HTML
    ob_start();
    aqualuxe_get_template_part( 'template-parts/dark-mode-toggle', $style, array(
        'location' => 'menu',
    ) );
    $toggle = ob_get_clean();
    
    // Add toggle to menu
    $items .= '<li class="menu-item menu-item-dark-mode-toggle">' . $toggle . '</li>';
    
    return $items;
}
add_filter( 'wp_nav_menu_items', 'aqualuxe_add_dark_mode_toggle_to_menu', 10, 2 );

/**
 * Add dark mode body class
 *
 * @param array $classes Body classes
 * @return array
 */
function aqualuxe_add_dark_mode_body_class( $classes ) {
    // Check if dark mode is active
    if ( ! aqualuxe_is_dark_mode_active() ) {
        return $classes;
    }
    
    // Add class if default mode is dark
    if ( aqualuxe_get_dark_mode_default() === 'dark' ) {
        $classes[] = 'dark-mode';
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_add_dark_mode_body_class' );

/**
 * Add dark mode CSS variables to head
 *
 * @return void
 */
function aqualuxe_add_dark_mode_css_variables() {
    // Check if dark mode is active
    if ( ! aqualuxe_is_dark_mode_active() ) {
        return;
    }
    
    // Get CSS variables
    $css = aqualuxe_get_dark_mode_css_variables();
    
    // Output CSS
    echo '<style id="aqualuxe-dark-mode-variables">' . $css . '</style>';
}
add_action( 'wp_head', 'aqualuxe_add_dark_mode_css_variables' );

/**
 * Add dark mode meta tags
 *
 * @return void
 */
function aqualuxe_add_dark_mode_meta_tags() {
    // Check if dark mode is active
    if ( ! aqualuxe_is_dark_mode_active() ) {
        return;
    }
    
    // Get default mode
    $default_mode = aqualuxe_get_dark_mode_default();
    
    // Add color scheme meta tag
    echo '<meta name="color-scheme" content="light dark">' . "\n";
    
    // Add theme-color meta tag
    $colors = aqualuxe_get_dark_mode_colors();
    echo '<meta name="theme-color" content="' . esc_attr( $colors['background'] ) . '" media="(prefers-color-scheme: dark)">' . "\n";
}
add_action( 'wp_head', 'aqualuxe_add_dark_mode_meta_tags' );