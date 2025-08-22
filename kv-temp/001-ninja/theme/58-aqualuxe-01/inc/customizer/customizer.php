<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register($wp_customize) {
    // Add selective refresh support
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'aqualuxe_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'aqualuxe_customize_partial_blogdescription',
            )
        );
    }

    // Load customizer sections
    require_once AQUALUXE_DIR . '/inc/customizer/sections/general.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/header.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/footer.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/typography.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/colors.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/layout.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/blog.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/social.php';
    require_once AQUALUXE_DIR . '/inc/customizer/sections/integrations.php';
    
    // Load WooCommerce customizer section if WooCommerce is active
    if (class_exists('WooCommerce')) {
        require_once AQUALUXE_DIR . '/inc/customizer/sections/woocommerce.php';
    }
    
    // Load modules customizer section
    require_once AQUALUXE_DIR . '/inc/customizer/sections/modules.php';
}
add_action('customize_register', 'aqualuxe_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function aqualuxe_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script('aqualuxe-customizer', aqualuxe_asset('js/customizer.js'), array('customize-preview'), AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Enqueue customizer controls scripts and styles.
 */
function aqualuxe_customize_controls_enqueue_scripts() {
    wp_enqueue_style('aqualuxe-customizer-controls', aqualuxe_asset('css/customizer-controls.css'), array(), AQUALUXE_VERSION);
    wp_enqueue_script('aqualuxe-customizer-controls', aqualuxe_asset('js/customizer-controls.js'), array('jquery', 'customize-controls'), AQUALUXE_VERSION, true);
}
add_action('customize_controls_enqueue_scripts', 'aqualuxe_customize_controls_enqueue_scripts');

/**
 * Generate CSS from customizer settings
 */
function aqualuxe_customizer_css() {
    // Get theme options
    $options = get_option('aqualuxe_options', array());
    
    // Default colors
    $primary_color = isset($options['primary_color']) ? $options['primary_color'] : '#0077b6';
    $secondary_color = isset($options['secondary_color']) ? $options['secondary_color'] : '#00b4d8';
    $accent_color = isset($options['accent_color']) ? $options['accent_color'] : '#90e0ef';
    $text_color = isset($options['text_color']) ? $options['text_color'] : '#333333';
    $heading_color = isset($options['heading_color']) ? $options['heading_color'] : '#222222';
    $background_color = isset($options['background_color']) ? $options['background_color'] : '#ffffff';
    $dark_background_color = isset($options['dark_background_color']) ? $options['dark_background_color'] : '#121212';
    $dark_text_color = isset($options['dark_text_color']) ? $options['dark_text_color'] : '#e0e0e0';
    
    // Typography
    $body_font_family = isset($options['body_font_family']) ? $options['body_font_family'] : 'Montserrat, sans-serif';
    $heading_font_family = isset($options['heading_font_family']) ? $options['heading_font_family'] : 'Playfair Display, serif';
    $body_font_size = isset($options['body_font_size']) ? $options['body_font_size'] : '16';
    $line_height = isset($options['line_height']) ? $options['line_height'] : '1.6';
    $heading_line_height = isset($options['heading_line_height']) ? $options['heading_line_height'] : '1.2';
    $body_font_weight = isset($options['body_font_weight']) ? $options['body_font_weight'] : '400';
    $heading_font_weight = isset($options['heading_font_weight']) ? $options['heading_font_weight'] : '600';
    
    // Layout
    $container_width = isset($options['container_width']) ? $options['container_width'] : '1280px';
    $content_width = isset($options['content_width']) ? $options['content_width'] : '800';
    $sidebar_width = isset($options['sidebar_width']) ? $options['sidebar_width'] : '300';
    $content_padding = isset($options['content_padding']) ? $options['content_padding'] : '40';
    
    // Footer
    $footer_background = isset($options['footer_background']) ? $options['footer_background'] : '#0a1a2a';
    $footer_text_color = isset($options['footer_text_color']) ? $options['footer_text_color'] : '#ffffff';
    
    // Generate CSS
    $css = ':root {
        --color-primary: ' . $primary_color . ';
        --color-primary-light: ' . aqualuxe_adjust_brightness($primary_color, 20) . ';
        --color-primary-dark: ' . aqualuxe_adjust_brightness($primary_color, -20) . ';
        --color-secondary: ' . $secondary_color . ';
        --color-secondary-light: ' . aqualuxe_adjust_brightness($secondary_color, 20) . ';
        --color-secondary-dark: ' . aqualuxe_adjust_brightness($secondary_color, -20) . ';
        --color-accent: ' . $accent_color . ';
        --color-accent-light: ' . aqualuxe_adjust_brightness($accent_color, 20) . ';
        --color-accent-dark: ' . aqualuxe_adjust_brightness($accent_color, -20) . ';
        --color-text: ' . $text_color . ';
        --color-heading: ' . $heading_color . ';
        --color-background: ' . $background_color . ';
        --color-dark-primary: ' . $accent_color . ';
        --color-dark-secondary: ' . $secondary_color . ';
        --color-dark-accent: ' . $primary_color . ';
        --color-dark-background: ' . $dark_background_color . ';
        --color-dark-text: ' . $dark_text_color . ';
        --color-dark-heading: #ffffff;
        --color-success: #4CAF50;
        --color-warning: #FFC107;
        --color-error: #F44336;
        --color-info: #2196F3;
        --font-family: ' . $body_font_family . ';
        --heading-font: ' . $heading_font_family . ';
        --container-width: ' . $container_width . ';
        --content-width: ' . $content_width . 'px;
        --sidebar-width: ' . $sidebar_width . 'px;
        --content-padding: ' . $content_padding . 'px;
    }
    
    body {
        font-family: var(--font-family);
        font-size: ' . $body_font_size . 'px;
        line-height: ' . $line_height . ';
        font-weight: ' . $body_font_weight . ';
        color: var(--color-text);
        background-color: var(--color-background);
    }
    
    h1, h2, h3, h4, h5, h6 {
        font-family: var(--heading-font);
        line-height: ' . $heading_line_height . ';
        font-weight: ' . $heading_font_weight . ';
        color: var(--color-heading);
    }
    
    .container {
        max-width: var(--container-width);
    }
    
    .content-area {
        width: calc(100% - var(--sidebar-width) - 30px);
        padding: var(--content-padding);
    }
    
    .sidebar {
        width: var(--sidebar-width);
    }
    
    .site-footer {
        background-color: ' . $footer_background . ';
        color: ' . $footer_text_color . ';
    }
    
    .dark-mode {
        --color-text: var(--color-dark-text);
        --color-heading: var(--color-dark-heading);
        --color-background: var(--color-dark-background);
    }
    
    a {
        color: var(--color-primary);
    }
    
    a:hover {
        color: var(--color-primary-dark);
    }
    
    .button, button, input[type="button"], input[type="reset"], input[type="submit"] {
        background-color: var(--color-primary);
        color: #ffffff;
    }
    
    .button:hover, button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover {
        background-color: var(--color-primary-dark);
    }
    
    .button-secondary {
        background-color: var(--color-secondary);
    }
    
    .button-secondary:hover {
        background-color: var(--color-secondary-dark);
    }
    
    .button-accent {
        background-color: var(--color-accent);
    }
    
    .button-accent:hover {
        background-color: var(--color-accent-dark);
    }';
    
    // Add custom CSS from theme options
    $custom_css = get_theme_mod('aqualuxe_custom_css', '');
    if (!empty($custom_css)) {
        $css .= "\n\n/* Custom CSS */\n" . $custom_css;
    }
    
    return $css;
}

/**
 * Output customizer CSS
 */
function aqualuxe_output_customizer_css() {
    echo '<style id="aqualuxe-customizer-css">' . aqualuxe_customizer_css() . '</style>';
}
add_action('wp_head', 'aqualuxe_output_customizer_css');

/**
 * Adjust color brightness
 *
 * @param string $hex Hex color code
 * @param int $steps Steps to adjust brightness (positive for lighter, negative for darker)
 * @return string Adjusted hex color
 */
function aqualuxe_adjust_brightness($hex, $steps) {
    // Remove # if present
    $hex = ltrim($hex, '#');
    
    // Convert to RGB
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    
    // Adjust brightness
    $r = max(0, min(255, $r + $steps));
    $g = max(0, min(255, $g + $steps));
    $b = max(0, min(255, $b + $steps));
    
    // Convert back to hex
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}