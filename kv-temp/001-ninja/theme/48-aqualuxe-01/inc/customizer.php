<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Include the main customizer file
require_once get_template_directory() . '/inc/customizer/customizer.php';

// Include additional customizer files
require_once get_template_directory() . '/inc/customizer/social-media.php';
require_once get_template_directory() . '/inc/customizer/header-footer.php';
require_once get_template_directory() . '/inc/customizer/custom-css.php';

/**
 * Get theme option
 *
 * @param string $option Option name
 * @param mixed $default Default value
 * @return mixed Option value
 */
function aqualuxe_get_option($option, $default = '') {
    $options = get_option('aqualuxe_options');
    return isset($options[$option]) ? $options[$option] : $default;
}

/**
 * Generate dynamic CSS based on customizer settings
 */
function aqualuxe_dynamic_css() {
    // Get customizer options
    $primary_color = aqualuxe_get_option('primary_color', '#0077B6');
    $secondary_color = aqualuxe_get_option('secondary_color', '#CAF0F8');
    $accent_color = aqualuxe_get_option('accent_color', '#FFD700');
    $text_color = aqualuxe_get_option('text_color', '#333333');
    $heading_color = aqualuxe_get_option('heading_color', '#222222');
    $link_color = aqualuxe_get_option('link_color', '#0077B6');
    $link_hover_color = aqualuxe_get_option('link_hover_color', '#03045E');
    $button_color = aqualuxe_get_option('button_color', '#0077B6');
    $button_text_color = aqualuxe_get_option('button_text_color', '#FFFFFF');
    $button_hover_color = aqualuxe_get_option('button_hover_color', '#03045E');
    $button_hover_text_color = aqualuxe_get_option('button_hover_text_color', '#FFFFFF');
    $header_bg_color = aqualuxe_get_option('header_bg_color', '#FFFFFF');
    $footer_bg_color = aqualuxe_get_option('footer_bg_color', '#F8F9FA');
    
    // Dark mode colors
    $dark_bg_color = aqualuxe_get_option('dark_bg_color', '#121212');
    $dark_text_color = aqualuxe_get_option('dark_text_color', '#E0E0E0');
    $dark_heading_color = aqualuxe_get_option('dark_heading_color', '#FFFFFF');
    $dark_link_color = aqualuxe_get_option('dark_link_color', '#90E0EF');
    $dark_link_hover_color = aqualuxe_get_option('dark_link_hover_color', '#CAF0F8');
    $dark_header_bg_color = aqualuxe_get_option('dark_header_bg_color', '#1A1A1A');
    $dark_footer_bg_color = aqualuxe_get_option('dark_footer_bg_color', '#1A1A1A');
    
    // Typography
    $body_font_family = aqualuxe_get_option('body_font_family', 'Montserrat, sans-serif');
    $heading_font_family = aqualuxe_get_option('heading_font_family', 'Playfair Display, serif');
    $body_font_size = aqualuxe_get_option('body_font_size', '16px');
    $body_line_height = aqualuxe_get_option('body_line_height', '1.6');
    $heading_line_height = aqualuxe_get_option('heading_line_height', '1.2');
    $h1_font_size = aqualuxe_get_option('h1_font_size', '2.5rem');
    $h2_font_size = aqualuxe_get_option('h2_font_size', '2rem');
    $h3_font_size = aqualuxe_get_option('h3_font_size', '1.75rem');
    $h4_font_size = aqualuxe_get_option('h4_font_size', '1.5rem');
    $h5_font_size = aqualuxe_get_option('h5_font_size', '1.25rem');
    $h6_font_size = aqualuxe_get_option('h6_font_size', '1rem');
    $body_font_weight = aqualuxe_get_option('body_font_weight', '400');
    $heading_font_weight = aqualuxe_get_option('heading_font_weight', '700');
    
    // Layout
    $container_width = aqualuxe_get_option('container_width', '1200px');
    
    // Start output buffer
    ob_start();
    ?>
    <style id="aqualuxe-dynamic-css">
        :root {
            --primary-color: <?php echo esc_attr($primary_color); ?>;
            --primary-color-dark: <?php echo esc_attr(aqualuxe_adjust_brightness($primary_color, -20)); ?>;
            --primary-color-light: <?php echo esc_attr(aqualuxe_adjust_brightness($primary_color, 20)); ?>;
            --secondary-color: <?php echo esc_attr($secondary_color); ?>;
            --accent-color: <?php echo esc_attr($accent_color); ?>;
            --text-color: <?php echo esc_attr($text_color); ?>;
            --heading-color: <?php echo esc_attr($heading_color); ?>;
            --link-color: <?php echo esc_attr($link_color); ?>;
            --link-hover-color: <?php echo esc_attr($link_hover_color); ?>;
            --button-color: <?php echo esc_attr($button_color); ?>;
            --button-text-color: <?php echo esc_attr($button_text_color); ?>;
            --button-hover-color: <?php echo esc_attr($button_hover_color); ?>;
            --button-hover-text-color: <?php echo esc_attr($button_hover_text_color); ?>;
            --header-bg-color: <?php echo esc_attr($header_bg_color); ?>;
            --footer-bg-color: <?php echo esc_attr($footer_bg_color); ?>;
            --container-width: <?php echo esc_attr($container_width); ?>;
            --body-font-family: <?php echo esc_attr($body_font_family); ?>;
            --heading-font-family: <?php echo esc_attr($heading_font_family); ?>;
            --body-font-size: <?php echo esc_attr($body_font_size); ?>;
            --body-line-height: <?php echo esc_attr($body_line_height); ?>;
            --heading-line-height: <?php echo esc_attr($heading_line_height); ?>;
            --h1-font-size: <?php echo esc_attr($h1_font_size); ?>;
            --h2-font-size: <?php echo esc_attr($h2_font_size); ?>;
            --h3-font-size: <?php echo esc_attr($h3_font_size); ?>;
            --h4-font-size: <?php echo esc_attr($h4_font_size); ?>;
            --h5-font-size: <?php echo esc_attr($h5_font_size); ?>;
            --h6-font-size: <?php echo esc_attr($h6_font_size); ?>;
            --body-font-weight: <?php echo esc_attr($body_font_weight); ?>;
            --heading-font-weight: <?php echo esc_attr($heading_font_weight); ?>;
        }
        
        .dark {
            --text-color: <?php echo esc_attr($dark_text_color); ?>;
            --heading-color: <?php echo esc_attr($dark_heading_color); ?>;
            --link-color: <?php echo esc_attr($dark_link_color); ?>;
            --link-hover-color: <?php echo esc_attr($dark_link_hover_color); ?>;
            --header-bg-color: <?php echo esc_attr($dark_header_bg_color); ?>;
            --footer-bg-color: <?php echo esc_attr($dark_footer_bg_color); ?>;
            --bg-color: <?php echo esc_attr($dark_bg_color); ?>;
        }
        
        /* Base Styles */
        body {
            font-family: var(--body-font-family);
            font-size: var(--body-font-size);
            line-height: var(--body-line-height);
            font-weight: var(--body-font-weight);
            color: var(--text-color);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--heading-font-family);
            line-height: var(--heading-line-height);
            font-weight: var(--heading-font-weight);
            color: var(--heading-color);
        }
        
        h1 { font-size: var(--h1-font-size); }
        h2 { font-size: var(--h2-font-size); }
        h3 { font-size: var(--h3-font-size); }
        h4 { font-size: var(--h4-font-size); }
        h5 { font-size: var(--h5-font-size); }
        h6 { font-size: var(--h6-font-size); }
        
        a {
            color: var(--link-color);
            transition: color 0.3s ease;
        }
        
        a:hover, a:focus {
            color: var(--link-hover-color);
        }
        
        .container {
            max-width: var(--container-width);
        }
        
        /* Button Styles */
        .button, button, input[type="button"], input[type="submit"] {
            background-color: var(--button-color);
            color: var(--button-text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        .button:hover, button:hover, input[type="button"]:hover, input[type="submit"]:hover {
            background-color: var(--button-hover-color);
            color: var(--button-hover-text-color);
        }
        
        /* Header Styles */
        .site-header {
            background-color: var(--header-bg-color);
        }
        
        /* Footer Styles */
        .site-footer {
            background-color: var(--footer-bg-color);
        }
        
        /* Primary Color Elements */
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .border-primary {
            border-color: var(--primary-color) !important;
        }
        
        /* Secondary Color Elements */
        .bg-secondary {
            background-color: var(--secondary-color) !important;
        }
        
        .text-secondary {
            color: var(--secondary-color) !important;
        }
        
        .border-secondary {
            border-color: var(--secondary-color) !important;
        }
        
        /* Accent Color Elements */
        .bg-accent {
            background-color: var(--accent-color) !important;
        }
        
        .text-accent {
            color: var(--accent-color) !important;
        }
        
        .border-accent {
            border-color: var(--accent-color) !important;
        }
    </style>
    <?php
    
    // Get output buffer
    $css = ob_get_clean();
    
    // Output CSS
    echo $css; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action('wp_head', 'aqualuxe_dynamic_css', 100);

/**
 * Helper function to adjust brightness of a color
 *
 * @param string $hex Hex color code
 * @param int $steps Steps to adjust brightness (negative for darker, positive for lighter)
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
    return '#' . sprintf('%02x%02x%02x', $r, $g, $b);
}