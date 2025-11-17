<?php
/**
 * AquaLuxe Theme Customizer - Typography Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add typography settings to the Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_typography($wp_customize) {
    // Add Typography section
    $wp_customize->add_section('aqualuxe_typography', array(
        'title'    => __('Typography', 'aqualuxe'),
        'priority' => 30,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Google Fonts API Key
    $wp_customize->add_setting('aqualuxe_google_fonts_api_key', array(
        'default'           => '',
        'transport'         => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_google_fonts_api_key', array(
        'label'       => __('Google Fonts API Key', 'aqualuxe'),
        'description' => __('Enter your Google Fonts API key to enable the font selector. Get your API key from the <a href="https://developers.google.com/fonts/docs/developer_api" target="_blank">Google Fonts Developer API</a>.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'text',
    ));
    
    // Body Font Family
    $wp_customize->add_setting('aqualuxe_body_font', array(
        'default'           => 'Roboto',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_body_font', array(
        'label'       => __('Body Font', 'aqualuxe'),
        'description' => __('Select the main font for your site content.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'select',
        'choices'     => aqualuxe_get_font_choices(),
    ));
    
    // Heading Font Family
    $wp_customize->add_setting('aqualuxe_heading_font', array(
        'default'           => 'Playfair Display',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_heading_font', array(
        'label'       => __('Heading Font', 'aqualuxe'),
        'description' => __('Select the font for headings.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'select',
        'choices'     => aqualuxe_get_font_choices(),
    ));
    
    // Base Font Size
    $wp_customize->add_setting('aqualuxe_base_font_size', array(
        'default'           => 16,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('aqualuxe_base_font_size', array(
        'label'       => __('Base Font Size (px)', 'aqualuxe'),
        'description' => __('Set the base font size for your site.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 24,
            'step' => 1,
        ),
    ));
    
    // Line Height
    $wp_customize->add_setting('aqualuxe_line_height', array(
        'default'           => 1.6,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_float',
    ));
    
    $wp_customize->add_control('aqualuxe_line_height', array(
        'label'       => __('Line Height', 'aqualuxe'),
        'description' => __('Set the line height for your content.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 2,
            'step' => 0.1,
        ),
    ));
    
    // Heading Line Height
    $wp_customize->add_setting('aqualuxe_heading_line_height', array(
        'default'           => 1.2,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_float',
    ));
    
    $wp_customize->add_control('aqualuxe_heading_line_height', array(
        'label'       => __('Heading Line Height', 'aqualuxe'),
        'description' => __('Set the line height for headings.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 2,
            'step' => 0.1,
        ),
    ));
    
    // Font Weight
    $wp_customize->add_setting('aqualuxe_body_font_weight', array(
        'default'           => '400',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_body_font_weight', array(
        'label'       => __('Body Font Weight', 'aqualuxe'),
        'description' => __('Select the font weight for your body text.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'select',
        'choices'     => array(
            '300' => __('Light (300)', 'aqualuxe'),
            '400' => __('Regular (400)', 'aqualuxe'),
            '500' => __('Medium (500)', 'aqualuxe'),
            '600' => __('Semi-Bold (600)', 'aqualuxe'),
            '700' => __('Bold (700)', 'aqualuxe'),
        ),
    ));
    
    // Heading Font Weight
    $wp_customize->add_setting('aqualuxe_heading_font_weight', array(
        'default'           => '700',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_heading_font_weight', array(
        'label'       => __('Heading Font Weight', 'aqualuxe'),
        'description' => __('Select the font weight for your headings.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'select',
        'choices'     => array(
            '300' => __('Light (300)', 'aqualuxe'),
            '400' => __('Regular (400)', 'aqualuxe'),
            '500' => __('Medium (500)', 'aqualuxe'),
            '600' => __('Semi-Bold (600)', 'aqualuxe'),
            '700' => __('Bold (700)', 'aqualuxe'),
        ),
    ));
    
    // Text Transform
    $wp_customize->add_setting('aqualuxe_heading_text_transform', array(
        'default'           => 'none',
        'transport'         => 'postMessage',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_heading_text_transform', array(
        'label'       => __('Heading Text Transform', 'aqualuxe'),
        'description' => __('Select the text transformation for headings.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'select',
        'choices'     => array(
            'none'      => __('None', 'aqualuxe'),
            'uppercase' => __('UPPERCASE', 'aqualuxe'),
            'lowercase' => __('lowercase', 'aqualuxe'),
            'capitalize' => __('Capitalize', 'aqualuxe'),
        ),
    ));
    
    // Letter Spacing
    $wp_customize->add_setting('aqualuxe_letter_spacing', array(
        'default'           => 0,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_float',
    ));
    
    $wp_customize->add_control('aqualuxe_letter_spacing', array(
        'label'       => __('Letter Spacing (px)', 'aqualuxe'),
        'description' => __('Set the letter spacing for your content.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => -2,
            'max'  => 5,
            'step' => 0.1,
        ),
    ));
    
    // Heading Letter Spacing
    $wp_customize->add_setting('aqualuxe_heading_letter_spacing', array(
        'default'           => 0,
        'transport'         => 'postMessage',
        'sanitize_callback' => 'aqualuxe_sanitize_float',
    ));
    
    $wp_customize->add_control('aqualuxe_heading_letter_spacing', array(
        'label'       => __('Heading Letter Spacing (px)', 'aqualuxe'),
        'description' => __('Set the letter spacing for headings.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => -2,
            'max'  => 5,
            'step' => 0.1,
        ),
    ));
}

// Add the typography section to the customizer
add_action('customize_register', 'aqualuxe_customize_register_typography');

/**
 * Get font choices for the customizer.
 *
 * @return array Array of font choices.
 */
function aqualuxe_get_font_choices() {
    // Default system fonts
    $fonts = array(
        'Arial, sans-serif'                     => 'Arial',
        'Helvetica, sans-serif'                 => 'Helvetica',
        'Georgia, serif'                        => 'Georgia',
        'Tahoma, sans-serif'                    => 'Tahoma',
        'Verdana, sans-serif'                   => 'Verdana',
        'Times New Roman, serif'                => 'Times New Roman',
        'Trebuchet MS, sans-serif'              => 'Trebuchet MS',
        'Palatino Linotype, serif'              => 'Palatino Linotype',
        'Lucida Sans Unicode, sans-serif'       => 'Lucida Sans',
    );
    
    // Add Google Fonts if API key is set
    $api_key = get_theme_mod('aqualuxe_google_fonts_api_key', '');
    
    if (!empty($api_key)) {
        // Get Google Fonts from transient or API
        $google_fonts = get_transient('aqualuxe_google_fonts');
        
        if (false === $google_fonts) {
            $google_fonts_url = add_query_arg(array(
                'key' => $api_key,
                'sort' => 'popularity',
            ), 'https://www.googleapis.com/webfonts/v1/webfonts');
            
            $response = wp_remote_get($google_fonts_url);
            
            if (!is_wp_error($response) && 200 === wp_remote_retrieve_response_code($response)) {
                $body = wp_remote_retrieve_body($response);
                $data = json_decode($body, true);
                
                if (isset($data['items']) && is_array($data['items'])) {
                    $google_fonts = array();
                    
                    foreach ($data['items'] as $font) {
                        $google_fonts[$font['family']] = $font['family'];
                    }
                    
                    // Cache for 24 hours
                    set_transient('aqualuxe_google_fonts', $google_fonts, 24 * HOUR_IN_SECONDS);
                    
                    // Merge with system fonts
                    $fonts = array_merge($google_fonts, $fonts);
                }
            }
        } else {
            // Merge cached Google Fonts with system fonts
            $fonts = array_merge($google_fonts, $fonts);
        }
    }
    
    // Add popular Google Fonts even if API key is not set
    $popular_google_fonts = array(
        'Roboto'             => 'Roboto',
        'Open Sans'          => 'Open Sans',
        'Lato'               => 'Lato',
        'Montserrat'         => 'Montserrat',
        'Poppins'            => 'Poppins',
        'Raleway'            => 'Raleway',
        'Oswald'             => 'Oswald',
        'Playfair Display'   => 'Playfair Display',
        'Merriweather'       => 'Merriweather',
        'Ubuntu'             => 'Ubuntu',
        'Nunito'             => 'Nunito',
        'Rubik'              => 'Rubik',
        'Source Sans Pro'    => 'Source Sans Pro',
        'Quicksand'          => 'Quicksand',
        'Work Sans'          => 'Work Sans',
    );
    
    // Merge popular Google Fonts with other fonts
    $fonts = array_merge($popular_google_fonts, $fonts);
    
    // Sort alphabetically
    asort($fonts);
    
    return $fonts;
}

/**
 * Sanitize float value.
 *
 * @param float $input Float value to sanitize.
 * @return float Sanitized float value.
 */
function aqualuxe_sanitize_float($input) {
    return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

/**
 * Enqueue Google Fonts.
 */
function aqualuxe_enqueue_google_fonts() {
    $body_font = get_theme_mod('aqualuxe_body_font', 'Roboto');
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Playfair Display');
    $body_font_weight = get_theme_mod('aqualuxe_body_font_weight', '400');
    $heading_font_weight = get_theme_mod('aqualuxe_heading_font_weight', '700');
    
    // Check if fonts are Google Fonts (not system fonts)
    $system_fonts = array(
        'Arial', 'Helvetica', 'Georgia', 'Tahoma', 'Verdana', 
        'Times New Roman', 'Trebuchet MS', 'Palatino Linotype', 'Lucida Sans'
    );
    
    $google_fonts = array();
    
    if (!in_array($body_font, $system_fonts)) {
        $google_fonts[$body_font] = array($body_font_weight, '400', '700');
    }
    
    if (!in_array($heading_font, $system_fonts)) {
        $google_fonts[$heading_font] = array($heading_font_weight, '400', '700');
    }
    
    if (!empty($google_fonts)) {
        $font_families = array();
        
        foreach ($google_fonts as $font => $weights) {
            $weights = array_unique($weights);
            $font_families[] = $font . ':' . implode(',', $weights);
        }
        
        $query_args = array(
            'family' => urlencode(implode('|', $font_families)),
            'display' => 'swap',
        );
        
        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
        
        wp_enqueue_style('aqualuxe-google-fonts', $fonts_url, array(), null);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_google_fonts');

/**
 * Add typography CSS to the head.
 */
function aqualuxe_typography_css() {
    $body_font = get_theme_mod('aqualuxe_body_font', 'Roboto');
    $heading_font = get_theme_mod('aqualuxe_heading_font', 'Playfair Display');
    $base_font_size = get_theme_mod('aqualuxe_base_font_size', 16);
    $line_height = get_theme_mod('aqualuxe_line_height', 1.6);
    $heading_line_height = get_theme_mod('aqualuxe_heading_line_height', 1.2);
    $body_font_weight = get_theme_mod('aqualuxe_body_font_weight', '400');
    $heading_font_weight = get_theme_mod('aqualuxe_heading_font_weight', '700');
    $heading_text_transform = get_theme_mod('aqualuxe_heading_text_transform', 'none');
    $letter_spacing = get_theme_mod('aqualuxe_letter_spacing', 0);
    $heading_letter_spacing = get_theme_mod('aqualuxe_heading_letter_spacing', 0);
    
    // Check if fonts are system fonts
    $system_fonts = array(
        'Arial', 'Helvetica', 'Georgia', 'Tahoma', 'Verdana', 
        'Times New Roman', 'Trebuchet MS', 'Palatino Linotype', 'Lucida Sans'
    );
    
    $body_font_css = in_array($body_font, $system_fonts) ? $body_font . ', sans-serif' : '"' . $body_font . '", sans-serif';
    $heading_font_css = in_array($heading_font, $system_fonts) ? $heading_font . ', serif' : '"' . $heading_font . '", serif';
    
    ?>
    <style type="text/css">
        :root {
            --aqualuxe-body-font: <?php echo esc_attr($body_font_css); ?>;
            --aqualuxe-heading-font: <?php echo esc_attr($heading_font_css); ?>;
        }
        
        body {
            font-family: var(--aqualuxe-body-font);
            font-size: <?php echo esc_attr($base_font_size); ?>px;
            line-height: <?php echo esc_attr($line_height); ?>;
            font-weight: <?php echo esc_attr($body_font_weight); ?>;
            letter-spacing: <?php echo esc_attr($letter_spacing); ?>px;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--aqualuxe-heading-font);
            line-height: <?php echo esc_attr($heading_line_height); ?>;
            font-weight: <?php echo esc_attr($heading_font_weight); ?>;
            text-transform: <?php echo esc_attr($heading_text_transform); ?>;
            letter-spacing: <?php echo esc_attr($heading_letter_spacing); ?>px;
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_typography_css');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_typography_customize_preview_js() {
    wp_add_inline_script('aqualuxe-customizer', '
        // Base font size
        wp.customize("aqualuxe_base_font_size", function(value) {
            value.bind(function(to) {
                $("body").css("font-size", to + "px");
            });
        });
        
        // Line height
        wp.customize("aqualuxe_line_height", function(value) {
            value.bind(function(to) {
                $("body").css("line-height", to);
            });
        });
        
        // Heading line height
        wp.customize("aqualuxe_heading_line_height", function(value) {
            value.bind(function(to) {
                $("h1, h2, h3, h4, h5, h6").css("line-height", to);
            });
        });
        
        // Body font weight
        wp.customize("aqualuxe_body_font_weight", function(value) {
            value.bind(function(to) {
                $("body").css("font-weight", to);
            });
        });
        
        // Heading font weight
        wp.customize("aqualuxe_heading_font_weight", function(value) {
            value.bind(function(to) {
                $("h1, h2, h3, h4, h5, h6").css("font-weight", to);
            });
        });
        
        // Heading text transform
        wp.customize("aqualuxe_heading_text_transform", function(value) {
            value.bind(function(to) {
                $("h1, h2, h3, h4, h5, h6").css("text-transform", to);
            });
        });
        
        // Letter spacing
        wp.customize("aqualuxe_letter_spacing", function(value) {
            value.bind(function(to) {
                $("body").css("letter-spacing", to + "px");
            });
        });
        
        // Heading letter spacing
        wp.customize("aqualuxe_heading_letter_spacing", function(value) {
            value.bind(function(to) {
                $("h1, h2, h3, h4, h5, h6").css("letter-spacing", to + "px");
            });
        });
    ');
}
add_action('customize_preview_init', 'aqualuxe_typography_customize_preview_js', 20);