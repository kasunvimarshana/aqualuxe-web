<?php
/**
 * Font Loading System for AquaLuxe Theme
 *
 * Handles proper loading of fonts with fallbacks
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue Google Fonts with proper fallbacks
 */
function aqualuxe_enqueue_fonts() {
    // Get font settings from theme customizer
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Montserrat');
    $body_font = get_theme_mod('aqualuxe_body_font', 'Open Sans');
    
    // Define available fonts and their fallbacks
    $available_fonts = array(
        'Montserrat' => array(
            'weights' => '400,500,600,700',
            'fallbacks' => 'sans-serif',
        ),
        'Open Sans' => array(
            'weights' => '400,400i,600,700',
            'fallbacks' => 'sans-serif',
        ),
        'Roboto' => array(
            'weights' => '400,400i,500,700',
            'fallbacks' => 'sans-serif',
        ),
        'Lato' => array(
            'weights' => '400,400i,700,900',
            'fallbacks' => 'sans-serif',
        ),
        'Poppins' => array(
            'weights' => '400,500,600,700',
            'fallbacks' => 'sans-serif',
        ),
        'Playfair Display' => array(
            'weights' => '400,400i,700,900',
            'fallbacks' => 'serif',
        ),
        'Merriweather' => array(
            'weights' => '400,400i,700,900',
            'fallbacks' => 'serif',
        ),
        'Source Sans Pro' => array(
            'weights' => '400,400i,600,700',
            'fallbacks' => 'sans-serif',
        ),
        'Nunito' => array(
            'weights' => '400,600,700',
            'fallbacks' => 'sans-serif',
        ),
    );
    
    // Prepare font families array for Google Fonts
    $font_families = array();
    $font_display = 'swap';
    
    // Add heading font if it's in our available fonts list
    if (isset($available_fonts[$heading_font])) {
        $font_families[] = $heading_font . ':' . $available_fonts[$heading_font]['weights'];
    }
    
    // Add body font if it's different from heading font and in our available fonts list
    if ($body_font !== $heading_font && isset($available_fonts[$body_font])) {
        $font_families[] = $body_font . ':' . $available_fonts[$body_font]['weights'];
    }
    
    // If we have fonts to load, create the Google Fonts URL
    if (!empty($font_families)) {
        $fonts_url = add_query_arg(array(
            'family' => urlencode(implode('|', $font_families)),
            'display' => $font_display,
        ), 'https://fonts.googleapis.com/css');
        
        // Enqueue the Google Fonts
        wp_enqueue_style('aqualuxe-google-fonts', $fonts_url, array(), AQUALUXE_VERSION);
    }
    
    // Add font CSS variables
    $heading_font_fallbacks = isset($available_fonts[$heading_font]) ? $available_fonts[$heading_font]['fallbacks'] : 'sans-serif';
    $body_font_fallbacks = isset($available_fonts[$body_font]) ? $available_fonts[$body_font]['fallbacks'] : 'sans-serif';
    
    $font_css = "
        :root {
            --heading-font: '{$heading_font}', {$heading_font_fallbacks};
            --body-font: '{$body_font}', {$body_font_fallbacks};
        }
    ";
    
    wp_add_inline_style('aqualuxe-google-fonts', $font_css);
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_fonts', 5);
add_action('enqueue_block_editor_assets', 'aqualuxe_enqueue_fonts', 5);

/**
 * Add preconnect for Google Fonts
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        // Add Google Fonts domain
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }
    
    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);

/**
 * Add font options to the customizer
 */
function aqualuxe_font_customizer_options($wp_customize) {
    // Font options are already added in customizer.php
    // This function is a placeholder for any additional font-specific customizer options
}
add_action('customize_register', 'aqualuxe_font_customizer_options');

/**
 * Get available font options
 * 
 * @return array Array of available fonts
 */
function aqualuxe_get_available_fonts() {
    return array(
        'Montserrat'    => 'Montserrat',
        'Roboto'        => 'Roboto',
        'Open Sans'     => 'Open Sans',
        'Lato'          => 'Lato',
        'Poppins'       => 'Poppins',
        'Playfair Display' => 'Playfair Display',
        'Merriweather' => 'Merriweather',
        'Source Sans Pro' => 'Source Sans Pro',
        'Nunito'        => 'Nunito',
    );
}

/**
 * Get system font fallbacks
 * 
 * @return array Array of system font fallbacks
 */
function aqualuxe_get_system_fonts() {
    return array(
        'sans-serif' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif',
        'serif' => 'Georgia, Times, "Times New Roman", serif',
        'monospace' => 'Monaco, Consolas, "Andale Mono", "DejaVu Sans Mono", monospace',
    );
}

/**
 * Load local font files if available
 * This is a fallback mechanism if Google Fonts are blocked
 */
function aqualuxe_load_local_fonts() {
    // Check if local fonts directory exists
    $fonts_dir = get_template_directory() . '/assets/fonts';
    if (!is_dir($fonts_dir)) {
        return;
    }
    
    // Get font settings from theme customizer
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Montserrat');
    $body_font = get_theme_mod('aqualuxe_body_font', 'Open Sans');
    
    // Define local font files if they exist
    $local_fonts = array();
    
    // Check for heading font files
    $heading_font_path = $fonts_dir . '/' . sanitize_title($heading_font);
    if (is_dir($heading_font_path)) {
        $local_fonts[$heading_font] = array(
            'regular' => file_exists($heading_font_path . '/regular.woff2') ? $heading_font_path . '/regular.woff2' : null,
            'italic' => file_exists($heading_font_path . '/italic.woff2') ? $heading_font_path . '/italic.woff2' : null,
            'bold' => file_exists($heading_font_path . '/bold.woff2') ? $heading_font_path . '/bold.woff2' : null,
            'bold_italic' => file_exists($heading_font_path . '/bold-italic.woff2') ? $heading_font_path . '/bold-italic.woff2' : null,
        );
    }
    
    // Check for body font files if different from heading font
    if ($body_font !== $heading_font) {
        $body_font_path = $fonts_dir . '/' . sanitize_title($body_font);
        if (is_dir($body_font_path)) {
            $local_fonts[$body_font] = array(
                'regular' => file_exists($body_font_path . '/regular.woff2') ? $body_font_path . '/regular.woff2' : null,
                'italic' => file_exists($body_font_path . '/italic.woff2') ? $body_font_path . '/italic.woff2' : null,
                'bold' => file_exists($body_font_path . '/bold.woff2') ? $body_font_path . '/bold.woff2' : null,
                'bold_italic' => file_exists($body_font_path . '/bold-italic.woff2') ? $body_font_path . '/bold-italic.woff2' : null,
            );
        }
    }
    
    // If we have local fonts, generate @font-face declarations
    if (!empty($local_fonts)) {
        $font_face_css = '';
        
        foreach ($local_fonts as $font_name => $font_files) {
            if ($font_files['regular']) {
                $font_url = get_template_directory_uri() . '/assets/fonts/' . sanitize_title($font_name) . '/regular.woff2';
                $font_face_css .= "
                    @font-face {
                        font-family: '{$font_name}';
                        font-style: normal;
                        font-weight: 400;
                        font-display: swap;
                        src: local('{$font_name}'), url('{$font_url}') format('woff2');
                    }
                ";
            }
            
            if ($font_files['italic']) {
                $font_url = get_template_directory_uri() . '/assets/fonts/' . sanitize_title($font_name) . '/italic.woff2';
                $font_face_css .= "
                    @font-face {
                        font-family: '{$font_name}';
                        font-style: italic;
                        font-weight: 400;
                        font-display: swap;
                        src: local('{$font_name} Italic'), url('{$font_url}') format('woff2');
                    }
                ";
            }
            
            if ($font_files['bold']) {
                $font_url = get_template_directory_uri() . '/assets/fonts/' . sanitize_title($font_name) . '/bold.woff2';
                $font_face_css .= "
                    @font-face {
                        font-family: '{$font_name}';
                        font-style: normal;
                        font-weight: 700;
                        font-display: swap;
                        src: local('{$font_name} Bold'), url('{$font_url}') format('woff2');
                    }
                ";
            }
            
            if ($font_files['bold_italic']) {
                $font_url = get_template_directory_uri() . '/assets/fonts/' . sanitize_title($font_name) . '/bold-italic.woff2';
                $font_face_css .= "
                    @font-face {
                        font-family: '{$font_name}';
                        font-style: italic;
                        font-weight: 700;
                        font-display: swap;
                        src: local('{$font_name} Bold Italic'), url('{$font_url}') format('woff2');
                    }
                ";
            }
        }
        
        // Add the font-face declarations to the head
        if (!empty($font_face_css)) {
            wp_add_inline_style('aqualuxe-style', $font_face_css);
        }
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_load_local_fonts', 15);