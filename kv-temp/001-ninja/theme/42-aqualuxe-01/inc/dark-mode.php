<?php
/**
 * AquaLuxe Dark Mode Functions
 *
 * Functions for handling dark mode functionality
 *
 * @package AquaLuxe
 */

/**
 * Enqueue dark mode scripts and styles
 */
function aqualuxe_dark_mode_scripts() {
    // Enqueue dark mode script
    wp_enqueue_script(
        'aqualuxe-dark-mode',
        get_template_directory_uri() . '/assets/dist/js/dark-mode.js',
        array( 'jquery' ),
        AQUALUXE_VERSION,
        true
    );

    // Pass dark mode settings to script
    $dark_mode_settings = array(
        'defaultMode' => get_theme_mod( 'aqualuxe_default_mode', 'light' ),
        'autoMode'    => get_theme_mod( 'aqualuxe_auto_dark_mode', true ),
        'primaryColor' => get_theme_mod( 'aqualuxe_dark_mode_primary_color', '#0ea5e9' ),
        'bgColor'     => get_theme_mod( 'aqualuxe_dark_mode_bg_color', '#121212' ),
        'textColor'   => get_theme_mod( 'aqualuxe_dark_mode_text_color', '#e5e5e5' ),
    );

    // Add dark mode settings to existing aqualuxeData object or create new one
    wp_localize_script(
        'aqualuxe-dark-mode',
        'aqualuxeData',
        array(
            'darkMode' => $dark_mode_settings,
        )
    );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_dark_mode_scripts' );

/**
 * Add dark mode toggle to the appropriate location based on customizer settings
 */
function aqualuxe_dark_mode_toggle() {
    // Check if dark mode is enabled
    $enable_dark_mode = get_theme_mod( 'aqualuxe_enable_dark_mode', true );
    
    if ( ! $enable_dark_mode ) {
        return;
    }

    // Get toggle position
    $toggle_position = get_theme_mod( 'aqualuxe_dark_mode_toggle_position', 'top-bar' );
    
    // Create the toggle HTML
    $toggle_html = '<div class="dark-mode-toggle in-' . esc_attr( $toggle_position ) . '">
        <button id="dark-mode-toggle" class="text-white hover:text-primary-300 transition-colors" aria-label="' . esc_attr__( 'Toggle Dark Mode', 'aqualuxe' ) . '">
            <span class="dark-mode-icon">
                <i class="fas fa-moon"></i>
            </span>
            <span class="light-mode-icon hidden">
                <i class="fas fa-sun"></i>
            </span>
        </button>
    </div>';
    
    // Return the toggle HTML
    return $toggle_html;
}

/**
 * Add dark mode toggle to the top bar
 */
function aqualuxe_add_dark_mode_toggle_to_top_bar() {
    $toggle_position = get_theme_mod( 'aqualuxe_dark_mode_toggle_position', 'top-bar' );
    
    if ( 'top-bar' === $toggle_position ) {
        echo aqualuxe_dark_mode_toggle();
    }
}
add_action( 'aqualuxe_top_bar_right', 'aqualuxe_add_dark_mode_toggle_to_top_bar' );

/**
 * Add dark mode toggle to the header
 */
function aqualuxe_add_dark_mode_toggle_to_header() {
    $toggle_position = get_theme_mod( 'aqualuxe_dark_mode_toggle_position', 'top-bar' );
    
    if ( 'header' === $toggle_position ) {
        echo aqualuxe_dark_mode_toggle();
    }
}
add_action( 'aqualuxe_header_actions', 'aqualuxe_add_dark_mode_toggle_to_header' );

/**
 * Add dark mode toggle to the footer
 */
function aqualuxe_add_dark_mode_toggle_to_footer() {
    $toggle_position = get_theme_mod( 'aqualuxe_dark_mode_toggle_position', 'top-bar' );
    
    if ( 'footer' === $toggle_position ) {
        echo aqualuxe_dark_mode_toggle();
    }
}
add_action( 'aqualuxe_footer_copyright', 'aqualuxe_add_dark_mode_toggle_to_footer' );

/**
 * Add floating dark mode toggle
 */
function aqualuxe_add_floating_dark_mode_toggle() {
    $toggle_position = get_theme_mod( 'aqualuxe_dark_mode_toggle_position', 'top-bar' );
    
    if ( 'floating' === $toggle_position ) {
        echo aqualuxe_dark_mode_toggle();
    }
}
add_action( 'wp_footer', 'aqualuxe_add_floating_dark_mode_toggle' );

/**
 * Add body class for dark mode
 */
function aqualuxe_dark_mode_body_class( $classes ) {
    $default_mode = get_theme_mod( 'aqualuxe_default_mode', 'light' );
    $auto_dark_mode = get_theme_mod( 'aqualuxe_auto_dark_mode', true );
    
    // Add auto-dark-mode class if enabled
    if ( $auto_dark_mode ) {
        $classes[] = 'auto-dark-mode';
    }
    
    // Add default-dark class if dark is the default
    if ( 'dark' === $default_mode ) {
        $classes[] = 'default-dark';
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_dark_mode_body_class' );

/**
 * Add dark mode CSS variables to head
 */
function aqualuxe_dark_mode_css_variables() {
    $primary_color = get_theme_mod( 'aqualuxe_dark_mode_primary_color', '#0ea5e9' );
    $bg_color = get_theme_mod( 'aqualuxe_dark_mode_bg_color', '#121212' );
    $text_color = get_theme_mod( 'aqualuxe_dark_mode_text_color', '#e5e5e5' );
    
    $css = "
    :root {
        --dark-mode-primary: {$primary_color};
        --dark-mode-bg: {$bg_color};
        --dark-mode-text: {$text_color};
    }";
    
    echo '<style id="aqualuxe-dark-mode-css">' . $css . '</style>';
}
add_action( 'wp_head', 'aqualuxe_dark_mode_css_variables' );