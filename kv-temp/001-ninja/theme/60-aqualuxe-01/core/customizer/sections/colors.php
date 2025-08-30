<?php
/**
 * AquaLuxe Theme Customizer - Colors Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add color settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_colors($wp_customize) {
    // Add Colors section
    $wp_customize->add_section('aqualuxe_colors', array(
        'title'    => __('Colors', 'aqualuxe'),
        'priority' => 40,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Primary Color
    $wp_customize->add_setting('aqualuxe_primary_color', array(
        'default'           => '#0073aa',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
        'label'       => __('Primary Color', 'aqualuxe'),
        'description' => __('The main color used throughout the site.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Secondary Color
    $wp_customize->add_setting('aqualuxe_secondary_color', array(
        'default'           => '#23282d',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
        'label'       => __('Secondary Color', 'aqualuxe'),
        'description' => __('The secondary color used throughout the site.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Accent Color
    $wp_customize->add_setting('aqualuxe_accent_color', array(
        'default'           => '#00a0d2',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', array(
        'label'       => __('Accent Color', 'aqualuxe'),
        'description' => __('The accent color used for highlights and call-to-actions.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Text Color
    $wp_customize->add_setting('aqualuxe_text_color', array(
        'default'           => '#333333',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_text_color', array(
        'label'       => __('Text Color', 'aqualuxe'),
        'description' => __('The main text color.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Heading Color
    $wp_customize->add_setting('aqualuxe_heading_color', array(
        'default'           => '#222222',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_heading_color', array(
        'label'       => __('Heading Color', 'aqualuxe'),
        'description' => __('The color for headings.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Link Color
    $wp_customize->add_setting('aqualuxe_link_color', array(
        'default'           => '#0073aa',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_link_color', array(
        'label'       => __('Link Color', 'aqualuxe'),
        'description' => __('The color for links.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Link Hover Color
    $wp_customize->add_setting('aqualuxe_link_hover_color', array(
        'default'           => '#00a0d2',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_link_hover_color', array(
        'label'       => __('Link Hover Color', 'aqualuxe'),
        'description' => __('The color for links when hovered.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Background Color
    $wp_customize->add_setting('aqualuxe_background_color', array(
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_background_color', array(
        'label'       => __('Background Color', 'aqualuxe'),
        'description' => __('The main background color.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Content Background Color
    $wp_customize->add_setting('aqualuxe_content_background_color', array(
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_content_background_color', array(
        'label'       => __('Content Background Color', 'aqualuxe'),
        'description' => __('The background color for content areas.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Header Background Color
    $wp_customize->add_setting('aqualuxe_header_background_color', array(
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_header_background_color', array(
        'label'       => __('Header Background Color', 'aqualuxe'),
        'description' => __('The background color for the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Header Text Color
    $wp_customize->add_setting('aqualuxe_header_text_color', array(
        'default'           => '#333333',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_header_text_color', array(
        'label'       => __('Header Text Color', 'aqualuxe'),
        'description' => __('The text color for the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Footer Background Color
    $wp_customize->add_setting('aqualuxe_footer_background_color', array(
        'default'           => '#23282d',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_background_color', array(
        'label'       => __('Footer Background Color', 'aqualuxe'),
        'description' => __('The background color for the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Footer Text Color
    $wp_customize->add_setting('aqualuxe_footer_text_color', array(
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_footer_text_color', array(
        'label'       => __('Footer Text Color', 'aqualuxe'),
        'description' => __('The text color for the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Button Background Color
    $wp_customize->add_setting('aqualuxe_button_background_color', array(
        'default'           => '#0073aa',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_background_color', array(
        'label'       => __('Button Background Color', 'aqualuxe'),
        'description' => __('The background color for buttons.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Button Text Color
    $wp_customize->add_setting('aqualuxe_button_text_color', array(
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_text_color', array(
        'label'       => __('Button Text Color', 'aqualuxe'),
        'description' => __('The text color for buttons.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Button Hover Background Color
    $wp_customize->add_setting('aqualuxe_button_hover_background_color', array(
        'default'           => '#00a0d2',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_hover_background_color', array(
        'label'       => __('Button Hover Background Color', 'aqualuxe'),
        'description' => __('The background color for buttons when hovered.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Button Hover Text Color
    $wp_customize->add_setting('aqualuxe_button_hover_text_color', array(
        'default'           => '#ffffff',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_hover_text_color', array(
        'label'       => __('Button Hover Text Color', 'aqualuxe'),
        'description' => __('The text color for buttons when hovered.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
    
    // Color Scheme
    $wp_customize->add_setting('aqualuxe_color_scheme', array(
        'default'           => 'default',
        'transport'         => 'refresh',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_color_scheme', array(
        'label'       => __('Color Scheme', 'aqualuxe'),
        'description' => __('Select a predefined color scheme or customize your own colors.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
        'type'        => 'select',
        'choices'     => array(
            'default'    => __('Default', 'aqualuxe'),
            'light'      => __('Light', 'aqualuxe'),
            'dark'       => __('Dark', 'aqualuxe'),
            'blue'       => __('Blue', 'aqualuxe'),
            'green'      => __('Green', 'aqualuxe'),
            'purple'     => __('Purple', 'aqualuxe'),
            'orange'     => __('Orange', 'aqualuxe'),
            'red'        => __('Red', 'aqualuxe'),
            'custom'     => __('Custom', 'aqualuxe'),
        ),
    )));
}

// Add the colors section to the customizer
add_action('customize_register', 'aqualuxe_customize_register_colors');

/**
 * Sanitize select field.
 *
 * @param string $input Select field value.
 * @param object $setting Setting object.
 * @return string Sanitized value.
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Get the list of choices from the control associated with the setting
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    // Return input if valid or return default if not
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Apply color scheme when the setting is changed.
 */
function aqualuxe_customize_color_scheme() {
    $color_scheme = get_theme_mod('aqualuxe_color_scheme', 'default');
    
    // Only apply predefined schemes if not set to custom
    if ($color_scheme !== 'custom') {
        $schemes = aqualuxe_get_color_schemes();
        
        if (isset($schemes[$color_scheme])) {
            $scheme = $schemes[$color_scheme];
            
            // Set each color from the scheme
            foreach ($scheme as $setting => $color) {
                set_theme_mod($setting, $color);
            }
        }
    }
}
add_action('customize_save_after', 'aqualuxe_customize_color_scheme');

/**
 * Get predefined color schemes.
 *
 * @return array Array of color schemes.
 */
function aqualuxe_get_color_schemes() {
    return array(
        'default' => array(
            'aqualuxe_primary_color'                => '#0073aa',
            'aqualuxe_secondary_color'              => '#23282d',
            'aqualuxe_accent_color'                 => '#00a0d2',
            'aqualuxe_text_color'                   => '#333333',
            'aqualuxe_heading_color'                => '#222222',
            'aqualuxe_link_color'                   => '#0073aa',
            'aqualuxe_link_hover_color'             => '#00a0d2',
            'aqualuxe_background_color'             => '#ffffff',
            'aqualuxe_content_background_color'     => '#ffffff',
            'aqualuxe_header_background_color'      => '#ffffff',
            'aqualuxe_header_text_color'            => '#333333',
            'aqualuxe_footer_background_color'      => '#23282d',
            'aqualuxe_footer_text_color'            => '#ffffff',
            'aqualuxe_button_background_color'      => '#0073aa',
            'aqualuxe_button_text_color'            => '#ffffff',
            'aqualuxe_button_hover_background_color' => '#00a0d2',
            'aqualuxe_button_hover_text_color'      => '#ffffff',
        ),
        'light' => array(
            'aqualuxe_primary_color'                => '#0073aa',
            'aqualuxe_secondary_color'              => '#23282d',
            'aqualuxe_accent_color'                 => '#00a0d2',
            'aqualuxe_text_color'                   => '#333333',
            'aqualuxe_heading_color'                => '#222222',
            'aqualuxe_link_color'                   => '#0073aa',
            'aqualuxe_link_hover_color'             => '#00a0d2',
            'aqualuxe_background_color'             => '#f8f9fa',
            'aqualuxe_content_background_color'     => '#ffffff',
            'aqualuxe_header_background_color'      => '#ffffff',
            'aqualuxe_header_text_color'            => '#333333',
            'aqualuxe_footer_background_color'      => '#f8f9fa',
            'aqualuxe_footer_text_color'            => '#333333',
            'aqualuxe_button_background_color'      => '#0073aa',
            'aqualuxe_button_text_color'            => '#ffffff',
            'aqualuxe_button_hover_background_color' => '#00a0d2',
            'aqualuxe_button_hover_text_color'      => '#ffffff',
        ),
        'dark' => array(
            'aqualuxe_primary_color'                => '#00a0d2',
            'aqualuxe_secondary_color'              => '#0073aa',
            'aqualuxe_accent_color'                 => '#00c1f2',
            'aqualuxe_text_color'                   => '#f8f9fa',
            'aqualuxe_heading_color'                => '#ffffff',
            'aqualuxe_link_color'                   => '#00a0d2',
            'aqualuxe_link_hover_color'             => '#00c1f2',
            'aqualuxe_background_color'             => '#121212',
            'aqualuxe_content_background_color'     => '#1e1e1e',
            'aqualuxe_header_background_color'      => '#1e1e1e',
            'aqualuxe_header_text_color'            => '#f8f9fa',
            'aqualuxe_footer_background_color'      => '#121212',
            'aqualuxe_footer_text_color'            => '#f8f9fa',
            'aqualuxe_button_background_color'      => '#00a0d2',
            'aqualuxe_button_text_color'            => '#ffffff',
            'aqualuxe_button_hover_background_color' => '#00c1f2',
            'aqualuxe_button_hover_text_color'      => '#ffffff',
        ),
        'blue' => array(
            'aqualuxe_primary_color'                => '#1e73be',
            'aqualuxe_secondary_color'              => '#0c3d63',
            'aqualuxe_accent_color'                 => '#4a9ced',
            'aqualuxe_text_color'                   => '#333333',
            'aqualuxe_heading_color'                => '#0c3d63',
            'aqualuxe_link_color'                   => '#1e73be',
            'aqualuxe_link_hover_color'             => '#4a9ced',
            'aqualuxe_background_color'             => '#f5f9fd',
            'aqualuxe_content_background_color'     => '#ffffff',
            'aqualuxe_header_background_color'      => '#ffffff',
            'aqualuxe_header_text_color'            => '#0c3d63',
            'aqualuxe_footer_background_color'      => '#0c3d63',
            'aqualuxe_footer_text_color'            => '#ffffff',
            'aqualuxe_button_background_color'      => '#1e73be',
            'aqualuxe_button_text_color'            => '#ffffff',
            'aqualuxe_button_hover_background_color' => '#4a9ced',
            'aqualuxe_button_hover_text_color'      => '#ffffff',
        ),
        'green' => array(
            'aqualuxe_primary_color'                => '#2e7d32',
            'aqualuxe_secondary_color'              => '#1b5e20',
            'aqualuxe_accent_color'                 => '#4caf50',
            'aqualuxe_text_color'                   => '#333333',
            'aqualuxe_heading_color'                => '#1b5e20',
            'aqualuxe_link_color'                   => '#2e7d32',
            'aqualuxe_link_hover_color'             => '#4caf50',
            'aqualuxe_background_color'             => '#f1f8e9',
            'aqualuxe_content_background_color'     => '#ffffff',
            'aqualuxe_header_background_color'      => '#ffffff',
            'aqualuxe_header_text_color'            => '#1b5e20',
            'aqualuxe_footer_background_color'      => '#1b5e20',
            'aqualuxe_footer_text_color'            => '#ffffff',
            'aqualuxe_button_background_color'      => '#2e7d32',
            'aqualuxe_button_text_color'            => '#ffffff',
            'aqualuxe_button_hover_background_color' => '#4caf50',
            'aqualuxe_button_hover_text_color'      => '#ffffff',
        ),
        'purple' => array(
            'aqualuxe_primary_color'                => '#6a1b9a',
            'aqualuxe_secondary_color'              => '#4a148c',
            'aqualuxe_accent_color'                 => '#9c27b0',
            'aqualuxe_text_color'                   => '#333333',
            'aqualuxe_heading_color'                => '#4a148c',
            'aqualuxe_link_color'                   => '#6a1b9a',
            'aqualuxe_link_hover_color'             => '#9c27b0',
            'aqualuxe_background_color'             => '#f3e5f5',
            'aqualuxe_content_background_color'     => '#ffffff',
            'aqualuxe_header_background_color'      => '#ffffff',
            'aqualuxe_header_text_color'            => '#4a148c',
            'aqualuxe_footer_background_color'      => '#4a148c',
            'aqualuxe_footer_text_color'            => '#ffffff',
            'aqualuxe_button_background_color'      => '#6a1b9a',
            'aqualuxe_button_text_color'            => '#ffffff',
            'aqualuxe_button_hover_background_color' => '#9c27b0',
            'aqualuxe_button_hover_text_color'      => '#ffffff',
        ),
        'orange' => array(
            'aqualuxe_primary_color'                => '#e65100',
            'aqualuxe_secondary_color'              => '#bf360c',
            'aqualuxe_accent_color'                 => '#ff9800',
            'aqualuxe_text_color'                   => '#333333',
            'aqualuxe_heading_color'                => '#bf360c',
            'aqualuxe_link_color'                   => '#e65100',
            'aqualuxe_link_hover_color'             => '#ff9800',
            'aqualuxe_background_color'             => '#fff3e0',
            'aqualuxe_content_background_color'     => '#ffffff',
            'aqualuxe_header_background_color'      => '#ffffff',
            'aqualuxe_header_text_color'            => '#bf360c',
            'aqualuxe_footer_background_color'      => '#bf360c',
            'aqualuxe_footer_text_color'            => '#ffffff',
            'aqualuxe_button_background_color'      => '#e65100',
            'aqualuxe_button_text_color'            => '#ffffff',
            'aqualuxe_button_hover_background_color' => '#ff9800',
            'aqualuxe_button_hover_text_color'      => '#ffffff',
        ),
        'red' => array(
            'aqualuxe_primary_color'                => '#c62828',
            'aqualuxe_secondary_color'              => '#b71c1c',
            'aqualuxe_accent_color'                 => '#f44336',
            'aqualuxe_text_color'                   => '#333333',
            'aqualuxe_heading_color'                => '#b71c1c',
            'aqualuxe_link_color'                   => '#c62828',
            'aqualuxe_link_hover_color'             => '#f44336',
            'aqualuxe_background_color'             => '#ffebee',
            'aqualuxe_content_background_color'     => '#ffffff',
            'aqualuxe_header_background_color'      => '#ffffff',
            'aqualuxe_header_text_color'            => '#b71c1c',
            'aqualuxe_footer_background_color'      => '#b71c1c',
            'aqualuxe_footer_text_color'            => '#ffffff',
            'aqualuxe_button_background_color'      => '#c62828',
            'aqualuxe_button_text_color'            => '#ffffff',
            'aqualuxe_button_hover_background_color' => '#f44336',
            'aqualuxe_button_hover_text_color'      => '#ffffff',
        ),
    );
}

/**
 * Add colors CSS to the head.
 */
function aqualuxe_colors_css() {
    $primary_color = get_theme_mod('aqualuxe_primary_color', '#0073aa');
    $secondary_color = get_theme_mod('aqualuxe_secondary_color', '#23282d');
    $accent_color = get_theme_mod('aqualuxe_accent_color', '#00a0d2');
    $text_color = get_theme_mod('aqualuxe_text_color', '#333333');
    $heading_color = get_theme_mod('aqualuxe_heading_color', '#222222');
    $link_color = get_theme_mod('aqualuxe_link_color', '#0073aa');
    $link_hover_color = get_theme_mod('aqualuxe_link_hover_color', '#00a0d2');
    $background_color = get_theme_mod('aqualuxe_background_color', '#ffffff');
    $content_background_color = get_theme_mod('aqualuxe_content_background_color', '#ffffff');
    $header_background_color = get_theme_mod('aqualuxe_header_background_color', '#ffffff');
    $header_text_color = get_theme_mod('aqualuxe_header_text_color', '#333333');
    $footer_background_color = get_theme_mod('aqualuxe_footer_background_color', '#23282d');
    $footer_text_color = get_theme_mod('aqualuxe_footer_text_color', '#ffffff');
    $button_background_color = get_theme_mod('aqualuxe_button_background_color', '#0073aa');
    $button_text_color = get_theme_mod('aqualuxe_button_text_color', '#ffffff');
    $button_hover_background_color = get_theme_mod('aqualuxe_button_hover_background_color', '#00a0d2');
    $button_hover_text_color = get_theme_mod('aqualuxe_button_hover_text_color', '#ffffff');
    
    ?>
    <style type="text/css">
        :root {
            --aqualuxe-primary-color: <?php echo esc_attr($primary_color); ?>;
            --aqualuxe-secondary-color: <?php echo esc_attr($secondary_color); ?>;
            --aqualuxe-accent-color: <?php echo esc_attr($accent_color); ?>;
            --aqualuxe-text-color: <?php echo esc_attr($text_color); ?>;
            --aqualuxe-heading-color: <?php echo esc_attr($heading_color); ?>;
            --aqualuxe-link-color: <?php echo esc_attr($link_color); ?>;
            --aqualuxe-link-hover-color: <?php echo esc_attr($link_hover_color); ?>;
            --aqualuxe-background-color: <?php echo esc_attr($background_color); ?>;
            --aqualuxe-content-background-color: <?php echo esc_attr($content_background_color); ?>;
            --aqualuxe-header-background-color: <?php echo esc_attr($header_background_color); ?>;
            --aqualuxe-header-text-color: <?php echo esc_attr($header_text_color); ?>;
            --aqualuxe-footer-background-color: <?php echo esc_attr($footer_background_color); ?>;
            --aqualuxe-footer-text-color: <?php echo esc_attr($footer_text_color); ?>;
            --aqualuxe-button-background-color: <?php echo esc_attr($button_background_color); ?>;
            --aqualuxe-button-text-color: <?php echo esc_attr($button_text_color); ?>;
            --aqualuxe-button-hover-background-color: <?php echo esc_attr($button_hover_background_color); ?>;
            --aqualuxe-button-hover-text-color: <?php echo esc_attr($button_hover_text_color); ?>;
        }
        
        body {
            color: var(--aqualuxe-text-color);
            background-color: var(--aqualuxe-background-color);
        }
        
        h1, h2, h3, h4, h5, h6 {
            color: var(--aqualuxe-heading-color);
        }
        
        a {
            color: var(--aqualuxe-link-color);
        }
        
        a:hover, a:focus {
            color: var(--aqualuxe-link-hover-color);
        }
        
        .site-content {
            background-color: var(--aqualuxe-content-background-color);
        }
        
        .site-header {
            background-color: var(--aqualuxe-header-background-color);
            color: var(--aqualuxe-header-text-color);
        }
        
        .site-footer {
            background-color: var(--aqualuxe-footer-background-color);
            color: var(--aqualuxe-footer-text-color);
        }
        
        .button, button, input[type="button"], input[type="reset"], input[type="submit"] {
            background-color: var(--aqualuxe-button-background-color);
            color: var(--aqualuxe-button-text-color);
        }
        
        .button:hover, button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover {
            background-color: var(--aqualuxe-button-hover-background-color);
            color: var(--aqualuxe-button-hover-text-color);
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_colors_css');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_colors_customize_preview_js() {
    wp_add_inline_script('aqualuxe-customizer', '
        // Primary color
        wp.customize("aqualuxe_primary_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-primary-color", to);
            });
        });
        
        // Secondary color
        wp.customize("aqualuxe_secondary_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-secondary-color", to);
            });
        });
        
        // Accent color
        wp.customize("aqualuxe_accent_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-accent-color", to);
            });
        });
        
        // Text color
        wp.customize("aqualuxe_text_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-text-color", to);
                $("body").css("color", to);
            });
        });
        
        // Heading color
        wp.customize("aqualuxe_heading_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-heading-color", to);
                $("h1, h2, h3, h4, h5, h6").css("color", to);
            });
        });
        
        // Link color
        wp.customize("aqualuxe_link_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-link-color", to);
                $("a").css("color", to);
            });
        });
        
        // Link hover color
        wp.customize("aqualuxe_link_hover_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-link-hover-color", to);
                // Cannot preview hover state
            });
        });
        
        // Background color
        wp.customize("aqualuxe_background_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-background-color", to);
                $("body").css("background-color", to);
            });
        });
        
        // Content background color
        wp.customize("aqualuxe_content_background_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-content-background-color", to);
                $(".site-content").css("background-color", to);
            });
        });
        
        // Header background color
        wp.customize("aqualuxe_header_background_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-header-background-color", to);
                $(".site-header").css("background-color", to);
            });
        });
        
        // Header text color
        wp.customize("aqualuxe_header_text_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-header-text-color", to);
                $(".site-header").css("color", to);
            });
        });
        
        // Footer background color
        wp.customize("aqualuxe_footer_background_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-footer-background-color", to);
                $(".site-footer").css("background-color", to);
            });
        });
        
        // Footer text color
        wp.customize("aqualuxe_footer_text_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-footer-text-color", to);
                $(".site-footer").css("color", to);
            });
        });
        
        // Button background color
        wp.customize("aqualuxe_button_background_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-button-background-color", to);
                $(".button, button, input[type=\&quot;button\&quot;], input[type=\&quot;reset\&quot;], input[type=\&quot;submit\&quot;]").css("background-color", to);
            });
        });
        
        // Button text color
        wp.customize("aqualuxe_button_text_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-button-text-color", to);
                $(".button, button, input[type=\&quot;button\&quot;], input[type=\&quot;reset\&quot;], input[type=\&quot;submit\&quot;]").css("color", to);
            });
        });
        
        // Button hover background color
        wp.customize("aqualuxe_button_hover_background_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-button-hover-background-color", to);
                // Cannot preview hover state
            });
        });
        
        // Button hover text color
        wp.customize("aqualuxe_button_hover_text_color", function(value) {
            value.bind(function(to) {
                $(":root").css("--aqualuxe-button-hover-text-color", to);
                // Cannot preview hover state
            });
        });
    ');
}
add_action('customize_preview_init', 'aqualuxe_colors_customize_preview_js', 20);