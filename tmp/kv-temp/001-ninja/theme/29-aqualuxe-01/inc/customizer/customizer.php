<?php
/**
 * AquaLuxe Theme Customizer
 *
 * @package AquaLuxe
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
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

    // Add theme options panel
    $wp_customize->add_panel('aqualuxe_theme_options', array(
        'title'    => __('AquaLuxe Theme Options', 'aqualuxe'),
        'priority' => 30,
    ));

    // Add sections to the theme options panel
    aqualuxe_customize_general_section($wp_customize);
    aqualuxe_customize_header_section($wp_customize);
    aqualuxe_customize_footer_section($wp_customize);
    aqualuxe_customize_colors_section($wp_customize);
    aqualuxe_customize_typography_section($wp_customize);
    aqualuxe_customize_layout_section($wp_customize);
    aqualuxe_customize_blog_section($wp_customize);
    aqualuxe_customize_social_section($wp_customize);
    aqualuxe_customize_woocommerce_section($wp_customize);
    aqualuxe_customize_performance_section($wp_customize);
    aqualuxe_customize_advanced_section($wp_customize);
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
    wp_enqueue_script('aqualuxe-customizer', AQUALUXE_URI . '/assets/js/customizer.js', array('customize-preview'), AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * General section
 */
function aqualuxe_customize_general_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_general', array(
        'title'    => __('General Settings', 'aqualuxe'),
        'priority' => 10,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add settings and controls
    $wp_customize->add_setting('aqualuxe_enable_preloader', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_preloader', array(
        'label'       => __('Enable Preloader', 'aqualuxe'),
        'description' => __('Show a preloader animation while the page is loading.', 'aqualuxe'),
        'section'     => 'aqualuxe_general',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_enable_back_to_top', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_back_to_top', array(
        'label'       => __('Enable Back to Top Button', 'aqualuxe'),
        'description' => __('Show a button to scroll back to the top of the page.', 'aqualuxe'),
        'section'     => 'aqualuxe_general',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_container_width', array(
        'default'           => '1200',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_container_width', array(
        'label'       => __('Container Width (px)', 'aqualuxe'),
        'description' => __('Set the maximum width of the content container.', 'aqualuxe'),
        'section'     => 'aqualuxe_general',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 960,
            'max'  => 1920,
            'step' => 10,
        ),
    ));

    $wp_customize->add_setting('aqualuxe_enable_breadcrumbs', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_breadcrumbs', array(
        'label'       => __('Enable Breadcrumbs', 'aqualuxe'),
        'description' => __('Show breadcrumb navigation on pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_general',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_default_featured_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_default_featured_image', array(
        'label'       => __('Default Featured Image', 'aqualuxe'),
        'description' => __('Set a default featured image for posts that don\'t have one.', 'aqualuxe'),
        'section'     => 'aqualuxe_general',
    )));

    $wp_customize->add_setting('aqualuxe_default_opengraph_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_default_opengraph_image', array(
        'label'       => __('Default Social Sharing Image', 'aqualuxe'),
        'description' => __('Set a default image for social media sharing.', 'aqualuxe'),
        'section'     => 'aqualuxe_general',
    )));
}

/**
 * Header section
 */
function aqualuxe_customize_header_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_header', array(
        'title'    => __('Header Settings', 'aqualuxe'),
        'priority' => 20,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add settings and controls
    $wp_customize->add_setting('aqualuxe_header_layout', array(
        'default'           => 'default',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_header_layout', array(
        'label'       => __('Header Layout', 'aqualuxe'),
        'description' => __('Select the layout for the site header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'select',
        'choices'     => array(
            'default'      => __('Default', 'aqualuxe'),
            'centered'     => __('Centered', 'aqualuxe'),
            'transparent'  => __('Transparent', 'aqualuxe'),
            'sticky'       => __('Sticky', 'aqualuxe'),
            'minimal'      => __('Minimal', 'aqualuxe'),
        ),
    ));

    $wp_customize->add_setting('aqualuxe_enable_sticky_header', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_sticky_header', array(
        'label'       => __('Enable Sticky Header', 'aqualuxe'),
        'description' => __('Keep the header visible when scrolling down.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_enable_search', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_search', array(
        'label'       => __('Enable Search in Header', 'aqualuxe'),
        'description' => __('Show a search icon in the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_header_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_header_phone', array(
        'label'       => __('Header Phone Number', 'aqualuxe'),
        'description' => __('Enter a phone number to display in the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('aqualuxe_header_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ));

    $wp_customize->add_control('aqualuxe_header_email', array(
        'label'       => __('Header Email', 'aqualuxe'),
        'description' => __('Enter an email address to display in the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'email',
    ));

    $wp_customize->add_setting('aqualuxe_enable_top_bar', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_top_bar', array(
        'label'       => __('Enable Top Bar', 'aqualuxe'),
        'description' => __('Show a top bar above the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_top_bar_text', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_top_bar_text', array(
        'label'       => __('Top Bar Text', 'aqualuxe'),
        'description' => __('Enter text to display in the top bar.', 'aqualuxe'),
        'section'     => 'aqualuxe_header',
        'type'        => 'textarea',
    ));
}

/**
 * Footer section
 */
function aqualuxe_customize_footer_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_footer', array(
        'title'    => __('Footer Settings', 'aqualuxe'),
        'priority' => 30,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add settings and controls
    $wp_customize->add_setting('aqualuxe_footer_layout', array(
        'default'           => '4-columns',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_footer_layout', array(
        'label'       => __('Footer Layout', 'aqualuxe'),
        'description' => __('Select the layout for the footer widgets.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'select',
        'choices'     => array(
            '1-column'  => __('1 Column', 'aqualuxe'),
            '2-columns' => __('2 Columns', 'aqualuxe'),
            '3-columns' => __('3 Columns', 'aqualuxe'),
            '4-columns' => __('4 Columns', 'aqualuxe'),
            'custom'    => __('Custom', 'aqualuxe'),
        ),
    ));

    $wp_customize->add_setting('aqualuxe_footer_copyright', array(
        'default'           => '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . __('All rights reserved.', 'aqualuxe'),
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_footer_copyright', array(
        'label'       => __('Footer Copyright Text', 'aqualuxe'),
        'description' => __('Enter the copyright text for the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'textarea',
    ));

    $wp_customize->add_setting('aqualuxe_footer_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_footer_logo', array(
        'label'       => __('Footer Logo', 'aqualuxe'),
        'description' => __('Upload a logo for the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
    )));

    $wp_customize->add_setting('aqualuxe_footer_address', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_footer_address', array(
        'label'       => __('Footer Address', 'aqualuxe'),
        'description' => __('Enter the address to display in the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'textarea',
    ));

    $wp_customize->add_setting('aqualuxe_footer_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_footer_phone', array(
        'label'       => __('Footer Phone Number', 'aqualuxe'),
        'description' => __('Enter a phone number to display in the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('aqualuxe_footer_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ));

    $wp_customize->add_control('aqualuxe_footer_email', array(
        'label'       => __('Footer Email', 'aqualuxe'),
        'description' => __('Enter an email address to display in the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'email',
    ));

    $wp_customize->add_setting('aqualuxe_enable_footer_social', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_footer_social', array(
        'label'       => __('Enable Social Icons in Footer', 'aqualuxe'),
        'description' => __('Show social media icons in the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_enable_footer_payment_icons', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_footer_payment_icons', array(
        'label'       => __('Enable Payment Icons in Footer', 'aqualuxe'),
        'description' => __('Show payment method icons in the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_footer_payment_icons', array(
        'default'           => array('visa', 'mastercard', 'amex', 'paypal'),
        'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Multi_Select_Control($wp_customize, 'aqualuxe_footer_payment_icons', array(
        'label'       => __('Payment Icons', 'aqualuxe'),
        'description' => __('Select which payment icons to display in the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_footer',
        'choices'     => array(
            'visa'       => __('Visa', 'aqualuxe'),
            'mastercard' => __('Mastercard', 'aqualuxe'),
            'amex'       => __('American Express', 'aqualuxe'),
            'discover'   => __('Discover', 'aqualuxe'),
            'paypal'     => __('PayPal', 'aqualuxe'),
            'apple-pay'  => __('Apple Pay', 'aqualuxe'),
            'google-pay' => __('Google Pay', 'aqualuxe'),
            'stripe'     => __('Stripe', 'aqualuxe'),
        ),
    )));
}

/**
 * Colors section
 */
function aqualuxe_customize_colors_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_colors', array(
        'title'    => __('Colors', 'aqualuxe'),
        'priority' => 40,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add settings and controls
    $wp_customize->add_setting('aqualuxe_primary_color', array(
        'default'           => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_primary_color', array(
        'label'       => __('Primary Color', 'aqualuxe'),
        'description' => __('The main brand color used throughout the site.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));

    $wp_customize->add_setting('aqualuxe_secondary_color', array(
        'default'           => '#00a0d2',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_secondary_color', array(
        'label'       => __('Secondary Color', 'aqualuxe'),
        'description' => __('The secondary brand color used throughout the site.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));

    $wp_customize->add_setting('aqualuxe_accent_color', array(
        'default'           => '#f7a400',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_accent_color', array(
        'label'       => __('Accent Color', 'aqualuxe'),
        'description' => __('The accent color used for highlights and call-to-actions.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));

    $wp_customize->add_setting('aqualuxe_text_color', array(
        'default'           => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_text_color', array(
        'label'       => __('Text Color', 'aqualuxe'),
        'description' => __('The main text color.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));

    $wp_customize->add_setting('aqualuxe_heading_color', array(
        'default'           => '#222222',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_heading_color', array(
        'label'       => __('Heading Color', 'aqualuxe'),
        'description' => __('The color for headings.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));

    $wp_customize->add_setting('aqualuxe_link_color', array(
        'default'           => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_link_color', array(
        'label'       => __('Link Color', 'aqualuxe'),
        'description' => __('The color for links.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));

    $wp_customize->add_setting('aqualuxe_link_hover_color', array(
        'default'           => '#00a0d2',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_link_hover_color', array(
        'label'       => __('Link Hover Color', 'aqualuxe'),
        'description' => __('The color for links when hovered.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));

    $wp_customize->add_setting('aqualuxe_button_color', array(
        'default'           => '#0073aa',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_color', array(
        'label'       => __('Button Color', 'aqualuxe'),
        'description' => __('The background color for buttons.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));

    $wp_customize->add_setting('aqualuxe_button_text_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_text_color', array(
        'label'       => __('Button Text Color', 'aqualuxe'),
        'description' => __('The text color for buttons.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));

    $wp_customize->add_setting('aqualuxe_button_hover_color', array(
        'default'           => '#00a0d2',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_hover_color', array(
        'label'       => __('Button Hover Color', 'aqualuxe'),
        'description' => __('The background color for buttons when hovered.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));

    $wp_customize->add_setting('aqualuxe_button_hover_text_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'aqualuxe_button_hover_text_color', array(
        'label'       => __('Button Hover Text Color', 'aqualuxe'),
        'description' => __('The text color for buttons when hovered.', 'aqualuxe'),
        'section'     => 'aqualuxe_colors',
    )));
}

/**
 * Typography section
 */
function aqualuxe_customize_typography_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_typography', array(
        'title'    => __('Typography', 'aqualuxe'),
        'priority' => 50,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add settings and controls
    $wp_customize->add_setting('aqualuxe_body_font', array(
        'default'           => 'system-ui',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_body_font', array(
        'label'       => __('Body Font', 'aqualuxe'),
        'description' => __('Select the font for body text.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'select',
        'choices'     => aqualuxe_get_font_choices(),
    ));

    $wp_customize->add_setting('aqualuxe_heading_font', array(
        'default'           => 'system-ui',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_heading_font', array(
        'label'       => __('Heading Font', 'aqualuxe'),
        'description' => __('Select the font for headings.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'select',
        'choices'     => aqualuxe_get_font_choices(),
    ));

    $wp_customize->add_setting('aqualuxe_body_font_size', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_body_font_size', array(
        'label'       => __('Body Font Size (px)', 'aqualuxe'),
        'description' => __('Set the base font size for body text.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 24,
            'step' => 1,
        ),
    ));

    $wp_customize->add_setting('aqualuxe_heading_font_weight', array(
        'default'           => '700',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_heading_font_weight', array(
        'label'       => __('Heading Font Weight', 'aqualuxe'),
        'description' => __('Select the font weight for headings.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'select',
        'choices'     => array(
            '300' => __('Light (300)', 'aqualuxe'),
            '400' => __('Regular (400)', 'aqualuxe'),
            '500' => __('Medium (500)', 'aqualuxe'),
            '600' => __('Semi-Bold (600)', 'aqualuxe'),
            '700' => __('Bold (700)', 'aqualuxe'),
            '800' => __('Extra-Bold (800)', 'aqualuxe'),
            '900' => __('Black (900)', 'aqualuxe'),
        ),
    ));

    $wp_customize->add_setting('aqualuxe_line_height', array(
        'default'           => '1.6',
        'sanitize_callback' => 'aqualuxe_sanitize_float',
    ));

    $wp_customize->add_control('aqualuxe_line_height', array(
        'label'       => __('Line Height', 'aqualuxe'),
        'description' => __('Set the line height for body text.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 2,
            'step' => 0.1,
        ),
    ));

    $wp_customize->add_setting('aqualuxe_enable_custom_fonts', array(
        'default'           => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_custom_fonts', array(
        'label'       => __('Enable Custom Fonts', 'aqualuxe'),
        'description' => __('Enable to use custom fonts via Google Fonts or other providers.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_custom_body_font', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_custom_body_font', array(
        'label'       => __('Custom Body Font', 'aqualuxe'),
        'description' => __('Enter the name of a Google Font or other custom font for body text.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'text',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_custom_fonts', false);
        },
    ));

    $wp_customize->add_setting('aqualuxe_custom_heading_font', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_custom_heading_font', array(
        'label'       => __('Custom Heading Font', 'aqualuxe'),
        'description' => __('Enter the name of a Google Font or other custom font for headings.', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'text',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_custom_fonts', false);
        },
    ));

    $wp_customize->add_setting('aqualuxe_custom_font_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_custom_font_url', array(
        'label'       => __('Custom Font URL', 'aqualuxe'),
        'description' => __('Enter the URL for loading custom fonts (e.g., Google Fonts URL).', 'aqualuxe'),
        'section'     => 'aqualuxe_typography',
        'type'        => 'url',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_custom_fonts', false);
        },
    ));
}

/**
 * Layout section
 */
function aqualuxe_customize_layout_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_layout', array(
        'title'    => __('Layout Settings', 'aqualuxe'),
        'priority' => 60,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add settings and controls
    $wp_customize->add_setting('aqualuxe_site_layout', array(
        'default'           => 'wide',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_site_layout', array(
        'label'       => __('Site Layout', 'aqualuxe'),
        'description' => __('Select the overall layout for the site.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'select',
        'choices'     => array(
            'wide'     => __('Wide', 'aqualuxe'),
            'boxed'    => __('Boxed', 'aqualuxe'),
            'framed'   => __('Framed', 'aqualuxe'),
            'full'     => __('Full Width', 'aqualuxe'),
        ),
    ));

    $wp_customize->add_setting('aqualuxe_sidebar_position', array(
        'default'           => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_sidebar_position', array(
        'label'       => __('Sidebar Position', 'aqualuxe'),
        'description' => __('Select the position for the sidebar.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'select',
        'choices'     => array(
            'right'    => __('Right', 'aqualuxe'),
            'left'     => __('Left', 'aqualuxe'),
            'none'     => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    $wp_customize->add_setting('aqualuxe_page_sidebar_position', array(
        'default'           => 'none',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_page_sidebar_position', array(
        'label'       => __('Page Sidebar Position', 'aqualuxe'),
        'description' => __('Select the position for the sidebar on pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'select',
        'choices'     => array(
            'right'    => __('Right', 'aqualuxe'),
            'left'     => __('Left', 'aqualuxe'),
            'none'     => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    $wp_customize->add_setting('aqualuxe_archive_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_archive_layout', array(
        'label'       => __('Archive Layout', 'aqualuxe'),
        'description' => __('Select the layout for archive pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'select',
        'choices'     => array(
            'grid'     => __('Grid', 'aqualuxe'),
            'list'     => __('List', 'aqualuxe'),
            'masonry'  => __('Masonry', 'aqualuxe'),
            'standard' => __('Standard', 'aqualuxe'),
        ),
    ));

    $wp_customize->add_setting('aqualuxe_archive_columns', array(
        'default'           => '3',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_archive_columns', array(
        'label'       => __('Archive Columns', 'aqualuxe'),
        'description' => __('Select the number of columns for grid and masonry layouts.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'select',
        'choices'     => array(
            '2' => __('2 Columns', 'aqualuxe'),
            '3' => __('3 Columns', 'aqualuxe'),
            '4' => __('4 Columns', 'aqualuxe'),
        ),
        'active_callback' => function() {
            $layout = get_theme_mod('aqualuxe_archive_layout', 'grid');
            return ($layout === 'grid' || $layout === 'masonry');
        },
    ));

    $wp_customize->add_setting('aqualuxe_single_post_layout', array(
        'default'           => 'standard',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_single_post_layout', array(
        'label'       => __('Single Post Layout', 'aqualuxe'),
        'description' => __('Select the layout for single posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_layout',
        'type'        => 'select',
        'choices'     => array(
            'standard'    => __('Standard', 'aqualuxe'),
            'wide'        => __('Wide', 'aqualuxe'),
            'full-width'  => __('Full Width', 'aqualuxe'),
            'narrow'      => __('Narrow', 'aqualuxe'),
        ),
    ));
}

/**
 * Blog section
 */
function aqualuxe_customize_blog_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_blog', array(
        'title'    => __('Blog Settings', 'aqualuxe'),
        'priority' => 70,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add settings and controls
    $wp_customize->add_setting('aqualuxe_blog_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_blog_layout', array(
        'label'       => __('Blog Layout', 'aqualuxe'),
        'description' => __('Select the layout for the blog page.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'select',
        'choices'     => array(
            'grid'     => __('Grid', 'aqualuxe'),
            'list'     => __('List', 'aqualuxe'),
            'masonry'  => __('Masonry', 'aqualuxe'),
            'standard' => __('Standard', 'aqualuxe'),
        ),
    ));

    $wp_customize->add_setting('aqualuxe_blog_columns', array(
        'default'           => '3',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_blog_columns', array(
        'label'       => __('Blog Columns', 'aqualuxe'),
        'description' => __('Select the number of columns for grid and masonry layouts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'select',
        'choices'     => array(
            '2' => __('2 Columns', 'aqualuxe'),
            '3' => __('3 Columns', 'aqualuxe'),
            '4' => __('4 Columns', 'aqualuxe'),
        ),
        'active_callback' => function() {
            $layout = get_theme_mod('aqualuxe_blog_layout', 'grid');
            return ($layout === 'grid' || $layout === 'masonry');
        },
    ));

    $wp_customize->add_setting('aqualuxe_blog_sidebar_position', array(
        'default'           => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_blog_sidebar_position', array(
        'label'       => __('Blog Sidebar Position', 'aqualuxe'),
        'description' => __('Select the position for the sidebar on the blog page.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'select',
        'choices'     => array(
            'right'    => __('Right', 'aqualuxe'),
            'left'     => __('Left', 'aqualuxe'),
            'none'     => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    $wp_customize->add_setting('aqualuxe_excerpt_length', array(
        'default'           => '30',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_excerpt_length', array(
        'label'       => __('Excerpt Length', 'aqualuxe'),
        'description' => __('Set the number of words for post excerpts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 10,
            'max'  => 100,
            'step' => 5,
        ),
    ));

    $wp_customize->add_setting('aqualuxe_read_more_text', array(
        'default'           => __('Read More', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_read_more_text', array(
        'label'       => __('Read More Text', 'aqualuxe'),
        'description' => __('Set the text for the read more link.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'text',
    ));

    $wp_customize->add_setting('aqualuxe_show_post_meta', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_show_post_meta', array(
        'label'       => __('Show Post Meta', 'aqualuxe'),
        'description' => __('Show or hide post meta information (author, date, categories).', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_show_featured_image', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_show_featured_image', array(
        'label'       => __('Show Featured Image', 'aqualuxe'),
        'description' => __('Show or hide featured images on single posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_show_author_bio', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_show_author_bio', array(
        'label'       => __('Show Author Bio', 'aqualuxe'),
        'description' => __('Show or hide author bio on single posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_show_related_posts', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_show_related_posts', array(
        'label'       => __('Show Related Posts', 'aqualuxe'),
        'description' => __('Show or hide related posts on single posts.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_related_posts_count', array(
        'default'           => '3',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_related_posts_count', array(
        'label'       => __('Related Posts Count', 'aqualuxe'),
        'description' => __('Set the number of related posts to display.', 'aqualuxe'),
        'section'     => 'aqualuxe_blog',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 12,
            'step' => 1,
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_show_related_posts', true);
        },
    ));
}

/**
 * Social section
 */
function aqualuxe_customize_social_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_social', array(
        'title'    => __('Social Media', 'aqualuxe'),
        'priority' => 80,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add settings and controls
    $wp_customize->add_setting('aqualuxe_social_facebook', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_social_facebook', array(
        'label'       => __('Facebook URL', 'aqualuxe'),
        'description' => __('Enter your Facebook profile or page URL.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'url',
    ));

    $wp_customize->add_setting('aqualuxe_social_twitter', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_social_twitter', array(
        'label'       => __('Twitter URL', 'aqualuxe'),
        'description' => __('Enter your Twitter profile URL.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'url',
    ));

    $wp_customize->add_setting('aqualuxe_social_instagram', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_social_instagram', array(
        'label'       => __('Instagram URL', 'aqualuxe'),
        'description' => __('Enter your Instagram profile URL.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'url',
    ));

    $wp_customize->add_setting('aqualuxe_social_linkedin', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_social_linkedin', array(
        'label'       => __('LinkedIn URL', 'aqualuxe'),
        'description' => __('Enter your LinkedIn profile URL.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'url',
    ));

    $wp_customize->add_setting('aqualuxe_social_youtube', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_social_youtube', array(
        'label'       => __('YouTube URL', 'aqualuxe'),
        'description' => __('Enter your YouTube channel URL.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'url',
    ));

    $wp_customize->add_setting('aqualuxe_social_pinterest', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_social_pinterest', array(
        'label'       => __('Pinterest URL', 'aqualuxe'),
        'description' => __('Enter your Pinterest profile URL.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'url',
    ));

    $wp_customize->add_setting('aqualuxe_social_tiktok', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('aqualuxe_social_tiktok', array(
        'label'       => __('TikTok URL', 'aqualuxe'),
        'description' => __('Enter your TikTok profile URL.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'url',
    ));

    $wp_customize->add_setting('aqualuxe_enable_social_sharing', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_social_sharing', array(
        'label'       => __('Enable Social Sharing', 'aqualuxe'),
        'description' => __('Show social sharing buttons on posts and pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_social_sharing_networks', array(
        'default'           => array('facebook', 'twitter', 'linkedin', 'pinterest'),
        'sanitize_callback' => 'aqualuxe_sanitize_multi_select',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Multi_Select_Control($wp_customize, 'aqualuxe_social_sharing_networks', array(
        'label'       => __('Social Sharing Networks', 'aqualuxe'),
        'description' => __('Select which social networks to include in the sharing buttons.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'choices'     => array(
            'facebook'  => __('Facebook', 'aqualuxe'),
            'twitter'   => __('Twitter', 'aqualuxe'),
            'linkedin'  => __('LinkedIn', 'aqualuxe'),
            'pinterest' => __('Pinterest', 'aqualuxe'),
            'reddit'    => __('Reddit', 'aqualuxe'),
            'email'     => __('Email', 'aqualuxe'),
            'whatsapp'  => __('WhatsApp', 'aqualuxe'),
            'telegram'  => __('Telegram', 'aqualuxe'),
        ),
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_social_sharing', true);
        },
    )));

    $wp_customize->add_setting('aqualuxe_twitter_username', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_twitter_username', array(
        'label'       => __('Twitter Username', 'aqualuxe'),
        'description' => __('Enter your Twitter username (without @) for Twitter Cards.', 'aqualuxe'),
        'section'     => 'aqualuxe_social',
        'type'        => 'text',
    ));
}

/**
 * WooCommerce section
 */
function aqualuxe_customize_woocommerce_section($wp_customize) {
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return;
    }

    // Add section
    $wp_customize->add_section('aqualuxe_woocommerce', array(
        'title'    => __('WooCommerce Settings', 'aqualuxe'),
        'priority' => 90,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add settings and controls
    $wp_customize->add_setting('aqualuxe_shop_layout', array(
        'default'           => 'grid',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_shop_layout', array(
        'label'       => __('Shop Layout', 'aqualuxe'),
        'description' => __('Select the layout for the shop page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            'grid'     => __('Grid', 'aqualuxe'),
            'list'     => __('List', 'aqualuxe'),
            'masonry'  => __('Masonry', 'aqualuxe'),
        ),
    ));

    $wp_customize->add_setting('aqualuxe_products_per_row', array(
        'default'           => '3',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_products_per_row', array(
        'label'       => __('Products Per Row', 'aqualuxe'),
        'description' => __('Set the number of products per row in the shop.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            '2' => __('2 Products', 'aqualuxe'),
            '3' => __('3 Products', 'aqualuxe'),
            '4' => __('4 Products', 'aqualuxe'),
            '5' => __('5 Products', 'aqualuxe'),
        ),
    ));

    $wp_customize->add_setting('aqualuxe_products_per_page', array(
        'default'           => '12',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_products_per_page', array(
        'label'       => __('Products Per Page', 'aqualuxe'),
        'description' => __('Set the number of products to display per page.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 100,
            'step' => 1,
        ),
    ));

    $wp_customize->add_setting('aqualuxe_shop_sidebar_position', array(
        'default'           => 'right',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_shop_sidebar_position', array(
        'label'       => __('Shop Sidebar Position', 'aqualuxe'),
        'description' => __('Select the position for the sidebar on shop pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            'right'    => __('Right', 'aqualuxe'),
            'left'     => __('Left', 'aqualuxe'),
            'none'     => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    $wp_customize->add_setting('aqualuxe_product_sidebar_position', array(
        'default'           => 'none',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_product_sidebar_position', array(
        'label'       => __('Product Sidebar Position', 'aqualuxe'),
        'description' => __('Select the position for the sidebar on single product pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'select',
        'choices'     => array(
            'right'    => __('Right', 'aqualuxe'),
            'left'     => __('Left', 'aqualuxe'),
            'none'     => __('No Sidebar', 'aqualuxe'),
        ),
    ));

    $wp_customize->add_setting('aqualuxe_enable_quick_view', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_quick_view', array(
        'label'       => __('Enable Quick View', 'aqualuxe'),
        'description' => __('Show a quick view button on product listings.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_enable_wishlist', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_wishlist', array(
        'label'       => __('Enable Wishlist', 'aqualuxe'),
        'description' => __('Show a wishlist button on product listings and single product pages.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_enable_ajax_add_to_cart', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_ajax_add_to_cart', array(
        'label'       => __('Enable AJAX Add to Cart', 'aqualuxe'),
        'description' => __('Add products to cart without page refresh.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_enable_product_zoom', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_product_zoom', array(
        'label'       => __('Enable Product Image Zoom', 'aqualuxe'),
        'description' => __('Enable zoom functionality for product images.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_enable_product_gallery_lightbox', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_product_gallery_lightbox', array(
        'label'       => __('Enable Product Gallery Lightbox', 'aqualuxe'),
        'description' => __('Enable lightbox functionality for product galleries.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_enable_product_gallery_slider', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_product_gallery_slider', array(
        'label'       => __('Enable Product Gallery Slider', 'aqualuxe'),
        'description' => __('Enable slider functionality for product galleries.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));
    
    $wp_customize->add_setting('aqualuxe_enable_advanced_filtering', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_advanced_filtering', array(
        'label'       => __('Enable Advanced Product Filtering', 'aqualuxe'),
        'description' => __('Enable advanced AJAX filtering for products in the shop.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_related_products_count', array(
        'default'           => '4',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_related_products_count', array(
        'label'       => __('Related Products Count', 'aqualuxe'),
        'description' => __('Set the number of related products to display.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 12,
            'step' => 1,
        ),
    ));

    $wp_customize->add_setting('aqualuxe_upsells_count', array(
        'default'           => '4',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_upsells_count', array(
        'label'       => __('Upsells Count', 'aqualuxe'),
        'description' => __('Set the number of upsell products to display.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 12,
            'step' => 1,
        ),
    ));

    $wp_customize->add_setting('aqualuxe_cross_sells_count', array(
        'default'           => '4',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('aqualuxe_cross_sells_count', array(
        'label'       => __('Cross-Sells Count', 'aqualuxe'),
        'description' => __('Set the number of cross-sell products to display.', 'aqualuxe'),
        'section'     => 'aqualuxe_woocommerce',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 12,
            'step' => 1,
        ),
    ));
}

/**
 * Performance section
 */
function aqualuxe_customize_performance_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_performance', array(
        'title'    => __('Performance Settings', 'aqualuxe'),
        'priority' => 100,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add settings and controls
    $wp_customize->add_setting('aqualuxe_enable_lazy_loading', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_lazy_loading', array(
        'label'       => __('Enable Lazy Loading', 'aqualuxe'),
        'description' => __('Lazy load images and iframes to improve page load speed.', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_enable_minification', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_minification', array(
        'label'       => __('Enable CSS/JS Minification', 'aqualuxe'),
        'description' => __('Minify CSS and JavaScript files to reduce file size.', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_enable_critical_css', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_critical_css', array(
        'label'       => __('Enable Critical CSS', 'aqualuxe'),
        'description' => __('Inline critical CSS to improve page render speed.', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_enable_preload', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_preload', array(
        'label'       => __('Enable Resource Preloading', 'aqualuxe'),
        'description' => __('Preload critical resources to improve page load speed.', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_enable_prefetch', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_prefetch', array(
        'label'       => __('Enable DNS Prefetching', 'aqualuxe'),
        'description' => __('Prefetch DNS for external resources to improve page load speed.', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_enable_webp', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_webp', array(
        'label'       => __('Enable WebP Images', 'aqualuxe'),
        'description' => __('Use WebP image format for better compression and faster loading.', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_enable_responsive_images', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_responsive_images', array(
        'label'       => __('Enable Responsive Images', 'aqualuxe'),
        'description' => __('Use responsive images to serve appropriate image sizes based on device.', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_enable_defer_js', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_defer_js', array(
        'label'       => __('Enable JavaScript Deferring', 'aqualuxe'),
        'description' => __('Defer JavaScript loading to improve page render speed.', 'aqualuxe'),
        'section'     => 'aqualuxe_performance',
        'type'        => 'checkbox',
    ));
}

/**
 * Advanced section
 */
function aqualuxe_customize_advanced_section($wp_customize) {
    // Add section
    $wp_customize->add_section('aqualuxe_advanced', array(
        'title'    => __('Advanced Settings', 'aqualuxe'),
        'priority' => 110,
        'panel'    => 'aqualuxe_theme_options',
    ));

    // Add settings and controls
    $wp_customize->add_setting('aqualuxe_custom_css', array(
        'default'           => '',
        'sanitize_callback' => 'wp_strip_all_tags',
    ));

    $wp_customize->add_control('aqualuxe_custom_css', array(
        'label'       => __('Custom CSS', 'aqualuxe'),
        'description' => __('Add custom CSS to customize the theme.', 'aqualuxe'),
        'section'     => 'aqualuxe_advanced',
        'type'        => 'textarea',
    ));

    $wp_customize->add_setting('aqualuxe_header_scripts', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_header_scripts', array(
        'label'       => __('Header Scripts', 'aqualuxe'),
        'description' => __('Add custom scripts to the header.', 'aqualuxe'),
        'section'     => 'aqualuxe_advanced',
        'type'        => 'textarea',
    ));

    $wp_customize->add_setting('aqualuxe_footer_scripts', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_footer_scripts', array(
        'label'       => __('Footer Scripts', 'aqualuxe'),
        'description' => __('Add custom scripts to the footer.', 'aqualuxe'),
        'section'     => 'aqualuxe_advanced',
        'type'        => 'textarea',
    ));

    $wp_customize->add_setting('aqualuxe_google_analytics', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_google_analytics', array(
        'label'       => __('Google Analytics', 'aqualuxe'),
        'description' => __('Add your Google Analytics tracking code.', 'aqualuxe'),
        'section'     => 'aqualuxe_advanced',
        'type'        => 'textarea',
    ));

    $wp_customize->add_setting('aqualuxe_facebook_pixel', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_facebook_pixel', array(
        'label'       => __('Facebook Pixel', 'aqualuxe'),
        'description' => __('Add your Facebook Pixel tracking code.', 'aqualuxe'),
        'section'     => 'aqualuxe_advanced',
        'type'        => 'textarea',
    ));

    $wp_customize->add_setting('aqualuxe_enable_maintenance_mode', array(
        'default'           => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control('aqualuxe_enable_maintenance_mode', array(
        'label'       => __('Enable Maintenance Mode', 'aqualuxe'),
        'description' => __('Show a maintenance mode page to visitors.', 'aqualuxe'),
        'section'     => 'aqualuxe_advanced',
        'type'        => 'checkbox',
    ));

    $wp_customize->add_setting('aqualuxe_maintenance_message', array(
        'default'           => __('We are currently performing maintenance. Please check back soon.', 'aqualuxe'),
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('aqualuxe_maintenance_message', array(
        'label'       => __('Maintenance Message', 'aqualuxe'),
        'description' => __('Message to display during maintenance mode.', 'aqualuxe'),
        'section'     => 'aqualuxe_advanced',
        'type'        => 'textarea',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_maintenance_mode', false);
        },
    ));

    $wp_customize->add_setting('aqualuxe_maintenance_mode_background', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_maintenance_mode_background', array(
        'label'       => __('Maintenance Mode Background', 'aqualuxe'),
        'description' => __('Background image for the maintenance mode page.', 'aqualuxe'),
        'section'     => 'aqualuxe_advanced',
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_maintenance_mode', false);
        },
    )));
}

/**
 * Helper functions for the customizer
 */

/**
 * Sanitize checkbox
 */
function aqualuxe_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Sanitize select
 */
function aqualuxe_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize multi select
 */
function aqualuxe_sanitize_multi_select($input) {
    if (!is_array($input)) {
        return array();
    }
    
    $valid_keys = array();
    
    foreach ($input as $value) {
        if (!empty($value)) {
            $valid_keys[] = sanitize_text_field($value);
        }
    }
    
    return $valid_keys;
}

/**
 * Sanitize float
 */
function aqualuxe_sanitize_float($input) {
    return filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}

/**
 * Get font choices
 */
function aqualuxe_get_font_choices() {
    return array(
        'system-ui' => __('System UI', 'aqualuxe'),
        'arial' => __('Arial', 'aqualuxe'),
        'helvetica' => __('Helvetica', 'aqualuxe'),
        'georgia' => __('Georgia', 'aqualuxe'),
        'times-new-roman' => __('Times New Roman', 'aqualuxe'),
        'verdana' => __('Verdana', 'aqualuxe'),
        'tahoma' => __('Tahoma', 'aqualuxe'),
        'trebuchet-ms' => __('Trebuchet MS', 'aqualuxe'),
        'courier-new' => __('Courier New', 'aqualuxe'),
        'custom' => __('Custom Font', 'aqualuxe'),
    );
}

/**
 * Multi select control class
 */
if (class_exists('WP_Customize_Control')) {
    class AquaLuxe_Customize_Multi_Select_Control extends WP_Customize_Control {
        public $type = 'multi-select';
        
        public function render_content() {
            if (empty($this->choices)) {
                return;
            }
            
            $values = $this->value();
            
            if (!is_array($values)) {
                $values = explode(',', $values);
            }
            ?>
            <label>
                <?php if (!empty($this->label)) : ?>
                    <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <?php endif; ?>
                
                <?php if (!empty($this->description)) : ?>
                    <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
                <?php endif; ?>
                
                <select multiple="multiple" <?php $this->link(); ?>>
                    <?php foreach ($this->choices as $value => $label) : ?>
                        <option value="<?php echo esc_attr($value); ?>" <?php selected(in_array($value, $values), true); ?>>
                            <?php echo esc_html($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <?php
        }
    }
}

/**
 * Generate CSS from customizer settings
 */
function aqualuxe_customizer_css() {
    ?>
    <style type="text/css">
        :root {
            --primary-color: <?php echo esc_attr(get_theme_mod('aqualuxe_primary_color', '#0073aa')); ?>;
            --secondary-color: <?php echo esc_attr(get_theme_mod('aqualuxe_secondary_color', '#00a0d2')); ?>;
            --accent-color: <?php echo esc_attr(get_theme_mod('aqualuxe_accent_color', '#f7a400')); ?>;
            --text-color: <?php echo esc_attr(get_theme_mod('aqualuxe_text_color', '#333333')); ?>;
            --heading-color: <?php echo esc_attr(get_theme_mod('aqualuxe_heading_color', '#222222')); ?>;
            --link-color: <?php echo esc_attr(get_theme_mod('aqualuxe_link_color', '#0073aa')); ?>;
            --link-hover-color: <?php echo esc_attr(get_theme_mod('aqualuxe_link_hover_color', '#00a0d2')); ?>;
            --button-color: <?php echo esc_attr(get_theme_mod('aqualuxe_button_color', '#0073aa')); ?>;
            --button-text-color: <?php echo esc_attr(get_theme_mod('aqualuxe_button_text_color', '#ffffff')); ?>;
            --button-hover-color: <?php echo esc_attr(get_theme_mod('aqualuxe_button_hover_color', '#00a0d2')); ?>;
            --button-hover-text-color: <?php echo esc_attr(get_theme_mod('aqualuxe_button_hover_text_color', '#ffffff')); ?>;
            --container-width: <?php echo esc_attr(get_theme_mod('aqualuxe_container_width', '1200')); ?>px;
            --body-font-size: <?php echo esc_attr(get_theme_mod('aqualuxe_body_font_size', '16')); ?>px;
            --line-height: <?php echo esc_attr(get_theme_mod('aqualuxe_line_height', '1.6')); ?>;
        }
        
        body {
            font-family: <?php echo aqualuxe_get_font_family('body'); ?>;
            font-size: var(--body-font-size);
            line-height: var(--line-height);
            color: var(--text-color);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: <?php echo aqualuxe_get_font_family('heading'); ?>;
            font-weight: <?php echo esc_attr(get_theme_mod('aqualuxe_heading_font_weight', '700')); ?>;
            color: var(--heading-color);
        }
        
        a {
            color: var(--link-color);
        }
        
        a:hover, a:focus {
            color: var(--link-hover-color);
        }
        
        .button, button, input[type="button"], input[type="reset"], input[type="submit"] {
            background-color: var(--button-color);
            color: var(--button-text-color);
        }
        
        .button:hover, button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover {
            background-color: var(--button-hover-color);
            color: var(--button-hover-text-color);
        }
        
        .container {
            max-width: var(--container-width);
        }
        
        <?php if (get_theme_mod('aqualuxe_site_layout', 'wide') === 'boxed') : ?>
            body {
                background-color: #f5f5f5;
            }
            
            .site {
                max-width: var(--container-width);
                margin: 0 auto;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_site_layout', 'wide') === 'framed') : ?>
            body {
                background-color: #f5f5f5;
                padding: 20px;
            }
            
            .site {
                max-width: var(--container-width);
                margin: 0 auto;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_header_layout', 'default') === 'transparent') : ?>
            .site-header {
                position: absolute;
                width: 100%;
                z-index: 999;
                background-color: transparent;
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_header_layout', 'default') === 'sticky' || get_theme_mod('aqualuxe_enable_sticky_header', true)) : ?>
            .site-header {
                position: sticky;
                top: 0;
                z-index: 999;
                background-color: #fff;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            
            .admin-bar .site-header {
                top: 32px;
            }
            
            @media screen and (max-width: 782px) {
                .admin-bar .site-header {
                    top: 46px;
                }
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_header_layout', 'default') === 'centered') : ?>
            .site-header .site-branding {
                text-align: center;
                float: none;
                margin: 0 auto;
            }
            
            .site-header .main-navigation {
                text-align: center;
                float: none;
                margin: 0 auto;
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_header_layout', 'default') === 'minimal') : ?>
            .site-header {
                padding: 10px 0;
            }
            
            .site-header .site-branding {
                float: left;
            }
            
            .site-header .main-navigation {
                float: right;
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_enable_top_bar', true)) : ?>
            .top-bar {
                background-color: var(--primary-color);
                color: #fff;
                padding: 5px 0;
            }
            
            .top-bar a {
                color: #fff;
            }
            
            .top-bar a:hover {
                color: rgba(255, 255, 255, 0.8);
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_footer_layout', '4-columns') === '1-column') : ?>
            .footer-widgets .footer-widget {
                width: 100%;
                float: none;
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_footer_layout', '4-columns') === '2-columns') : ?>
            .footer-widgets .footer-widget {
                width: 48%;
                float: left;
                margin-right: 4%;
            }
            
            .footer-widgets .footer-widget:nth-child(2n) {
                margin-right: 0;
            }
            
            .footer-widgets .footer-widget:nth-child(2n+1) {
                clear: left;
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_footer_layout', '4-columns') === '3-columns') : ?>
            .footer-widgets .footer-widget {
                width: 30.66%;
                float: left;
                margin-right: 4%;
            }
            
            .footer-widgets .footer-widget:nth-child(3n) {
                margin-right: 0;
            }
            
            .footer-widgets .footer-widget:nth-child(3n+1) {
                clear: left;
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_footer_layout', '4-columns') === '4-columns') : ?>
            .footer-widgets .footer-widget {
                width: 22%;
                float: left;
                margin-right: 4%;
            }
            
            .footer-widgets .footer-widget:nth-child(4n) {
                margin-right: 0;
            }
            
            .footer-widgets .footer-widget:nth-child(4n+1) {
                clear: left;
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_blog_layout', 'grid') === 'grid') : ?>
            .blog .posts-grid {
                display: grid;
                grid-template-columns: repeat(<?php echo esc_attr(get_theme_mod('aqualuxe_blog_columns', '3')); ?>, 1fr);
                grid-gap: 30px;
            }
            
            @media screen and (max-width: 768px) {
                .blog .posts-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }
            
            @media screen and (max-width: 480px) {
                .blog .posts-grid {
                    grid-template-columns: 1fr;
                }
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_blog_layout', 'grid') === 'masonry') : ?>
            .blog .posts-masonry {
                column-count: <?php echo esc_attr(get_theme_mod('aqualuxe_blog_columns', '3')); ?>;
                column-gap: 30px;
            }
            
            .blog .posts-masonry .post {
                display: inline-block;
                width: 100%;
                margin-bottom: 30px;
            }
            
            @media screen and (max-width: 768px) {
                .blog .posts-masonry {
                    column-count: 2;
                }
            }
            
            @media screen and (max-width: 480px) {
                .blog .posts-masonry {
                    column-count: 1;
                }
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_blog_layout', 'grid') === 'list') : ?>
            .blog .posts-list .post {
                margin-bottom: 30px;
                padding-bottom: 30px;
                border-bottom: 1px solid #eee;
            }
            
            .blog .posts-list .post-thumbnail {
                float: left;
                width: 30%;
                margin-right: 30px;
            }
            
            .blog .posts-list .post-content {
                float: left;
                width: calc(70% - 30px);
            }
            
            @media screen and (max-width: 768px) {
                .blog .posts-list .post-thumbnail,
                .blog .posts-list .post-content {
                    float: none;
                    width: 100%;
                    margin-right: 0;
                }
                
                .blog .posts-list .post-thumbnail {
                    margin-bottom: 20px;
                }
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_single_post_layout', 'standard') === 'wide') : ?>
            .single-post .entry-content {
                max-width: 900px;
                margin-left: auto;
                margin-right: auto;
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_single_post_layout', 'standard') === 'full-width') : ?>
            .single-post .entry-content {
                max-width: 100%;
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_single_post_layout', 'standard') === 'narrow') : ?>
            .single-post .entry-content {
                max-width: 700px;
                margin-left: auto;
                margin-right: auto;
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_sidebar_position', 'right') === 'left') : ?>
            .content-area {
                float: right;
                margin: 0 0 0 -25%;
                width: 100%;
            }
            
            .site-main {
                margin: 0 0 0 25%;
            }
            
            .site-content .widget-area {
                float: left;
                overflow: hidden;
                width: 25%;
            }
        <?php endif; ?>
        
        <?php if (get_theme_mod('aqualuxe_sidebar_position', 'right') === 'none') : ?>
            .content-area {
                float: none;
                margin: 0;
                width: 100%;
            }
            
            .site-main {
                margin: 0;
            }
            
            .site-content .widget-area {
                display: none;
            }
        <?php endif; ?>
        
        <?php if (class_exists('WooCommerce')) : ?>
            <?php if (get_theme_mod('aqualuxe_shop_layout', 'grid') === 'grid') : ?>
                .woocommerce ul.products {
                    display: grid;
                    grid-template-columns: repeat(<?php echo esc_attr(get_theme_mod('aqualuxe_products_per_row', '3')); ?>, 1fr);
                    grid-gap: 30px;
                }
                
                @media screen and (max-width: 768px) {
                    .woocommerce ul.products {
                        grid-template-columns: repeat(2, 1fr);
                    }
                }
                
                @media screen and (max-width: 480px) {
                    .woocommerce ul.products {
                        grid-template-columns: 1fr;
                    }
                }
            <?php endif; ?>
            
            <?php if (get_theme_mod('aqualuxe_shop_layout', 'grid') === 'list') : ?>
                .woocommerce ul.products li.product {
                    width: 100%;
                    float: none;
                    margin-right: 0;
                    margin-bottom: 30px;
                    padding-bottom: 30px;
                    border-bottom: 1px solid #eee;
                }
                
                .woocommerce ul.products li.product .woocommerce-loop-product__link {
                    display: flex;
                    align-items: center;
                }
                
                .woocommerce ul.products li.product .woocommerce-loop-product__title {
                    padding-top: 0;
                }
                
                .woocommerce ul.products li.product img {
                    width: 30%;
                    float: left;
                    margin-right: 30px;
                }
                
                .woocommerce ul.products li.product .product-content {
                    float: left;
                    width: calc(70% - 30px);
                }
                
                @media screen and (max-width: 768px) {
                    .woocommerce ul.products li.product img,
                    .woocommerce ul.products li.product .product-content {
                        float: none;
                        width: 100%;
                        margin-right: 0;
                    }
                    
                    .woocommerce ul.products li.product img {
                        margin-bottom: 20px;
                    }
                    
                    .woocommerce ul.products li.product .woocommerce-loop-product__link {
                        display: block;
                    }
                }
            <?php endif; ?>
            
            <?php if (get_theme_mod('aqualuxe_shop_layout', 'grid') === 'masonry') : ?>
                .woocommerce ul.products {
                    column-count: <?php echo esc_attr(get_theme_mod('aqualuxe_products_per_row', '3')); ?>;
                    column-gap: 30px;
                }
                
                .woocommerce ul.products li.product {
                    display: inline-block;
                    width: 100%;
                    margin-bottom: 30px;
                    float: none;
                }
                
                @media screen and (max-width: 768px) {
                    .woocommerce ul.products {
                        column-count: 2;
                    }
                }
                
                @media screen and (max-width: 480px) {
                    .woocommerce ul.products {
                        column-count: 1;
                    }
                }
            <?php endif; ?>
            
            <?php if (get_theme_mod('aqualuxe_shop_sidebar_position', 'right') === 'left') : ?>
                .woocommerce .content-area {
                    float: right;
                    margin: 0 0 0 -25%;
                    width: 100%;
                }
                
                .woocommerce .site-main {
                    margin: 0 0 0 25%;
                }
                
                .woocommerce .widget-area {
                    float: left;
                    overflow: hidden;
                    width: 25%;
                }
            <?php endif; ?>
            
            <?php if (get_theme_mod('aqualuxe_shop_sidebar_position', 'right') === 'none') : ?>
                .woocommerce .content-area {
                    float: none;
                    margin: 0;
                    width: 100%;
                }
                
                .woocommerce .site-main {
                    margin: 0;
                }
                
                .woocommerce .widget-area {
                    display: none;
                }
            <?php endif; ?>
            
            <?php if (get_theme_mod('aqualuxe_product_sidebar_position', 'none') === 'right') : ?>
                .single-product .content-area {
                    float: left;
                    margin: 0 -25% 0 0;
                    width: 100%;
                }
                
                .single-product .site-main {
                    margin: 0 25% 0 0;
                }
                
                .single-product .widget-area {
                    float: right;
                    overflow: hidden;
                    width: 25%;
                }
            <?php endif; ?>
            
            <?php if (get_theme_mod('aqualuxe_product_sidebar_position', 'none') === 'left') : ?>
                .single-product .content-area {
                    float: right;
                    margin: 0 0 0 -25%;
                    width: 100%;
                }
                
                .single-product .site-main {
                    margin: 0 0 0 25%;
                }
                
                .single-product .widget-area {
                    float: left;
                    overflow: hidden;
                    width: 25%;
                }
            <?php endif; ?>
            
            <?php if (get_theme_mod('aqualuxe_product_sidebar_position', 'none') === 'none') : ?>
                .single-product .content-area {
                    float: none;
                    margin: 0;
                    width: 100%;
                }
                
                .single-product .site-main {
                    margin: 0;
                }
                
                .single-product .widget-area {
                    display: none;
                }
            <?php endif; ?>
        <?php endif; ?>
        
        <?php echo wp_strip_all_tags(get_theme_mod('aqualuxe_custom_css', '')); ?>
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_customizer_css');

/**
 * Get font family
 */
function aqualuxe_get_font_family($type) {
    $enable_custom_fonts = get_theme_mod('aqualuxe_enable_custom_fonts', false);
    
    if ($enable_custom_fonts) {
        if ($type === 'body') {
            $custom_font = get_theme_mod('aqualuxe_custom_body_font', '');
            
            if (!empty($custom_font)) {
                return "'" . $custom_font . "', sans-serif";
            }
        } elseif ($type === 'heading') {
            $custom_font = get_theme_mod('aqualuxe_custom_heading_font', '');
            
            if (!empty($custom_font)) {
                return "'" . $custom_font . "', sans-serif";
            }
        }
    }
    
    $font = ($type === 'body') ? get_theme_mod('aqualuxe_body_font', 'system-ui') : get_theme_mod('aqualuxe_heading_font', 'system-ui');
    
    switch ($font) {
        case 'system-ui':
            return 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif';
        case 'arial':
            return 'Arial, sans-serif';
        case 'helvetica':
            return 'Helvetica, Arial, sans-serif';
        case 'georgia':
            return 'Georgia, serif';
        case 'times-new-roman':
            return '"Times New Roman", Times, serif';
        case 'verdana':
            return 'Verdana, Geneva, sans-serif';
        case 'tahoma':
            return 'Tahoma, Geneva, sans-serif';
        case 'trebuchet-ms':
            return '"Trebuchet MS", Helvetica, sans-serif';
        case 'courier-new':
            return '"Courier New", Courier, monospace';
        default:
            return 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif';
    }
}

/**
 * Enqueue Google Fonts
 */
function aqualuxe_enqueue_google_fonts() {
    $enable_custom_fonts = get_theme_mod('aqualuxe_enable_custom_fonts', false);
    
    if ($enable_custom_fonts) {
        $custom_font_url = get_theme_mod('aqualuxe_custom_font_url', '');
        
        if (!empty($custom_font_url)) {
            wp_enqueue_style('aqualuxe-google-fonts', $custom_font_url, array(), AQUALUXE_VERSION);
        }
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_google_fonts');

/**
 * Add custom scripts to header
 */
function aqualuxe_header_scripts() {
    $header_scripts = get_theme_mod('aqualuxe_header_scripts', '');
    
    if (!empty($header_scripts)) {
        echo wp_kses_post($header_scripts);
    }
    
    $google_analytics = get_theme_mod('aqualuxe_google_analytics', '');
    
    if (!empty($google_analytics)) {
        echo wp_kses_post($google_analytics);
    }
    
    $facebook_pixel = get_theme_mod('aqualuxe_facebook_pixel', '');
    
    if (!empty($facebook_pixel)) {
        echo wp_kses_post($facebook_pixel);
    }
}
add_action('wp_head', 'aqualuxe_header_scripts');

/**
 * Add custom scripts to footer
 */
function aqualuxe_footer_scripts() {
    $footer_scripts = get_theme_mod('aqualuxe_footer_scripts', '');
    
    if (!empty($footer_scripts)) {
        echo wp_kses_post($footer_scripts);
    }
}
add_action('wp_footer', 'aqualuxe_footer_scripts');

/**
 * Maintenance mode
 */
function aqualuxe_maintenance_mode() {
    $enable_maintenance_mode = get_theme_mod('aqualuxe_enable_maintenance_mode', false);
    
    if ($enable_maintenance_mode && !current_user_can('edit_themes') && !is_admin() && !is_login_page()) {
        $maintenance_message = get_theme_mod('aqualuxe_maintenance_message', __('We are currently performing maintenance. Please check back soon.', 'aqualuxe'));
        $maintenance_background = get_theme_mod('aqualuxe_maintenance_mode_background', '');
        
        wp_die(
            '<h1>' . esc_html__('Maintenance Mode', 'aqualuxe') . '</h1>' .
            '<p>' . wp_kses_post($maintenance_message) . '</p>',
            esc_html__('Maintenance Mode', 'aqualuxe'),
            array(
                'response' => 503,
                'back_link' => false,
            )
        );
    }
}
add_action('template_redirect', 'aqualuxe_maintenance_mode');

/**
 * Check if current page is login page
 */
function is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}