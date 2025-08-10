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

    // Add theme options panel
    $wp_customize->add_panel(
        'aqualuxe_theme_options',
        array(
            'title'       => __('AquaLuxe Theme Options', 'aqualuxe'),
            'description' => __('Customize your AquaLuxe theme settings', 'aqualuxe'),
            'priority'    => 130,
        )
    );

    // Add sections to the panel
    aqualuxe_customize_general_section($wp_customize);
    aqualuxe_customize_header_section($wp_customize);
    aqualuxe_customize_footer_section($wp_customize);
    aqualuxe_customize_colors_section($wp_customize);
    aqualuxe_customize_typography_section($wp_customize);
    aqualuxe_customize_layout_section($wp_customize);
    aqualuxe_customize_blog_section($wp_customize);
    aqualuxe_customize_woocommerce_section($wp_customize);
    aqualuxe_customize_social_section($wp_customize);
    aqualuxe_customize_performance_section($wp_customize);
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
 * General Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_general_section($wp_customize) {
    // Add General section
    $wp_customize->add_section(
        'aqualuxe_general_section',
        array(
            'title'       => __('General Settings', 'aqualuxe'),
            'description' => __('Configure general theme settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 10,
        )
    );

    // Dark Mode Setting
    $wp_customize->add_setting(
        'aqualuxe_enable_dark_mode',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_dark_mode',
        array(
            'label'       => __('Enable Dark Mode Toggle', 'aqualuxe'),
            'description' => __('Allow users to switch between light and dark mode', 'aqualuxe'),
            'section'     => 'aqualuxe_general_section',
            'type'        => 'checkbox',
        )
    );

    // Default Color Scheme
    $wp_customize->add_setting(
        'aqualuxe_default_color_scheme',
        array(
            'default'           => 'light',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_default_color_scheme',
        array(
            'label'       => __('Default Color Scheme', 'aqualuxe'),
            'description' => __('Choose the default color scheme for your site', 'aqualuxe'),
            'section'     => 'aqualuxe_general_section',
            'type'        => 'select',
            'choices'     => array(
                'light' => __('Light', 'aqualuxe'),
                'dark'  => __('Dark', 'aqualuxe'),
            ),
        )
    );

    // Multilingual Support
    $wp_customize->add_setting(
        'aqualuxe_enable_multilingual',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_multilingual',
        array(
            'label'       => __('Enable Multilingual Support', 'aqualuxe'),
            'description' => __('Show language switcher if WPML or Polylang is active', 'aqualuxe'),
            'section'     => 'aqualuxe_general_section',
            'type'        => 'checkbox',
        )
    );

    // Container Width
    $wp_customize->add_setting(
        'aqualuxe_container_width',
        array(
            'default'           => '1280',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_container_width',
        array(
            'label'       => __('Container Width (px)', 'aqualuxe'),
            'description' => __('Set the maximum width of the content container', 'aqualuxe'),
            'section'     => 'aqualuxe_general_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 960,
                'max'  => 1920,
                'step' => 10,
            ),
        )
    );

    // Breadcrumbs
    $wp_customize->add_setting(
        'aqualuxe_enable_breadcrumbs',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_breadcrumbs',
        array(
            'label'       => __('Enable Breadcrumbs', 'aqualuxe'),
            'description' => __('Show breadcrumb navigation on pages', 'aqualuxe'),
            'section'     => 'aqualuxe_general_section',
            'type'        => 'checkbox',
        )
    );

    // Back to Top Button
    $wp_customize->add_setting(
        'aqualuxe_enable_back_to_top',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_back_to_top',
        array(
            'label'       => __('Enable Back to Top Button', 'aqualuxe'),
            'description' => __('Show a button to scroll back to the top of the page', 'aqualuxe'),
            'section'     => 'aqualuxe_general_section',
            'type'        => 'checkbox',
        )
    );
}

/**
 * Header Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_header_section($wp_customize) {
    // Add Header section
    $wp_customize->add_section(
        'aqualuxe_header_section',
        array(
            'title'       => __('Header Settings', 'aqualuxe'),
            'description' => __('Configure header layout and elements', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 20,
        )
    );

    // Header Layout
    $wp_customize->add_setting(
        'aqualuxe_header_layout',
        array(
            'default'           => 'standard',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_layout',
        array(
            'label'       => __('Header Layout', 'aqualuxe'),
            'description' => __('Choose the layout for your site header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_section',
            'type'        => 'select',
            'choices'     => array(
                'standard'    => __('Standard', 'aqualuxe'),
                'centered'    => __('Centered', 'aqualuxe'),
                'split'       => __('Split Menu', 'aqualuxe'),
                'transparent' => __('Transparent', 'aqualuxe'),
                'minimal'     => __('Minimal', 'aqualuxe'),
            ),
        )
    );

    // Sticky Header
    $wp_customize->add_setting(
        'aqualuxe_sticky_header',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_sticky_header',
        array(
            'label'       => __('Enable Sticky Header', 'aqualuxe'),
            'description' => __('Keep the header visible when scrolling down', 'aqualuxe'),
            'section'     => 'aqualuxe_header_section',
            'type'        => 'checkbox',
        )
    );

    // Header Top Bar
    $wp_customize->add_setting(
        'aqualuxe_header_top_bar',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_top_bar',
        array(
            'label'       => __('Enable Top Bar', 'aqualuxe'),
            'description' => __('Show a top bar above the main header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_section',
            'type'        => 'checkbox',
        )
    );

    // Top Bar Content
    $wp_customize->add_setting(
        'aqualuxe_top_bar_content',
        array(
            'default'           => __('Welcome to AquaLuxe - Bringing elegance to aquatic life – globally.', 'aqualuxe'),
            'sanitize_callback' => 'aqualuxe_sanitize_html',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_top_bar_content',
        array(
            'label'       => __('Top Bar Content', 'aqualuxe'),
            'description' => __('Add text or HTML for the top bar', 'aqualuxe'),
            'section'     => 'aqualuxe_header_section',
            'type'        => 'textarea',
        )
    );

    // Show Search in Header
    $wp_customize->add_setting(
        'aqualuxe_header_search',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_search',
        array(
            'label'       => __('Show Search in Header', 'aqualuxe'),
            'description' => __('Display a search icon in the header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_section',
            'type'        => 'checkbox',
        )
    );

    // Show Cart in Header
    $wp_customize->add_setting(
        'aqualuxe_header_cart',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_cart',
        array(
            'label'       => __('Show Cart in Header', 'aqualuxe'),
            'description' => __('Display a shopping cart icon in the header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_section',
            'type'        => 'checkbox',
        )
    );

    // Show Account in Header
    $wp_customize->add_setting(
        'aqualuxe_header_account',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_account',
        array(
            'label'       => __('Show Account in Header', 'aqualuxe'),
            'description' => __('Display a user account icon in the header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_section',
            'type'        => 'checkbox',
        )
    );

    // Show Wishlist in Header
    $wp_customize->add_setting(
        'aqualuxe_header_wishlist',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_header_wishlist',
        array(
            'label'       => __('Show Wishlist in Header', 'aqualuxe'),
            'description' => __('Display a wishlist icon in the header', 'aqualuxe'),
            'section'     => 'aqualuxe_header_section',
            'type'        => 'checkbox',
        )
    );

    // Mobile Menu Style
    $wp_customize->add_setting(
        'aqualuxe_mobile_menu_style',
        array(
            'default'           => 'drawer',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_mobile_menu_style',
        array(
            'label'       => __('Mobile Menu Style', 'aqualuxe'),
            'description' => __('Choose how the mobile menu appears', 'aqualuxe'),
            'section'     => 'aqualuxe_header_section',
            'type'        => 'select',
            'choices'     => array(
                'drawer'   => __('Side Drawer', 'aqualuxe'),
                'dropdown' => __('Dropdown', 'aqualuxe'),
                'fullscreen' => __('Fullscreen Overlay', 'aqualuxe'),
            ),
        )
    );
}

/**
 * Footer Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_footer_section($wp_customize) {
    // Add Footer section
    $wp_customize->add_section(
        'aqualuxe_footer_section',
        array(
            'title'       => __('Footer Settings', 'aqualuxe'),
            'description' => __('Configure footer layout and content', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 30,
        )
    );

    // Footer Layout
    $wp_customize->add_setting(
        'aqualuxe_footer_layout',
        array(
            'default'           => '4-columns',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_layout',
        array(
            'label'       => __('Footer Widget Layout', 'aqualuxe'),
            'description' => __('Choose the layout for footer widgets', 'aqualuxe'),
            'section'     => 'aqualuxe_footer_section',
            'type'        => 'select',
            'choices'     => array(
                '1-column'  => __('1 Column', 'aqualuxe'),
                '2-columns' => __('2 Columns', 'aqualuxe'),
                '3-columns' => __('3 Columns', 'aqualuxe'),
                '4-columns' => __('4 Columns', 'aqualuxe'),
                'custom'    => __('Custom Layout', 'aqualuxe'),
            ),
        )
    );

    // Footer Logo
    $wp_customize->add_setting(
        'aqualuxe_footer_logo',
        array(
            'default'           => '',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Media_Control(
            $wp_customize,
            'aqualuxe_footer_logo',
            array(
                'label'       => __('Footer Logo', 'aqualuxe'),
                'description' => __('Upload a logo for the footer (optional)', 'aqualuxe'),
                'section'     => 'aqualuxe_footer_section',
                'mime_type'   => 'image',
            )
        )
    );

    // Copyright Text
    $wp_customize->add_setting(
        'aqualuxe_copyright_text',
        array(
            'default'           => sprintf(__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'), date('Y')),
            'sanitize_callback' => 'aqualuxe_sanitize_html',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_copyright_text',
        array(
            'label'       => __('Copyright Text', 'aqualuxe'),
            'description' => __('Enter your copyright text. Use %s for the current year.', 'aqualuxe'),
            'section'     => 'aqualuxe_footer_section',
            'type'        => 'textarea',
        )
    );

    // Payment Icons
    $wp_customize->add_setting(
        'aqualuxe_show_payment_icons',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_payment_icons',
        array(
            'label'       => __('Show Payment Icons', 'aqualuxe'),
            'description' => __('Display payment method icons in the footer', 'aqualuxe'),
            'section'     => 'aqualuxe_footer_section',
            'type'        => 'checkbox',
        )
    );

    // Newsletter Form
    $wp_customize->add_setting(
        'aqualuxe_footer_newsletter',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_newsletter',
        array(
            'label'       => __('Show Newsletter Form', 'aqualuxe'),
            'description' => __('Display a newsletter signup form in the footer', 'aqualuxe'),
            'section'     => 'aqualuxe_footer_section',
            'type'        => 'checkbox',
        )
    );

    // Newsletter Shortcode
    $wp_customize->add_setting(
        'aqualuxe_newsletter_shortcode',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_newsletter_shortcode',
        array(
            'label'       => __('Newsletter Form Shortcode', 'aqualuxe'),
            'description' => __('Enter the shortcode for your newsletter form plugin', 'aqualuxe'),
            'section'     => 'aqualuxe_footer_section',
            'type'        => 'text',
        )
    );
}

/**
 * Colors Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_colors_section($wp_customize) {
    // Add Colors section
    $wp_customize->add_section(
        'aqualuxe_colors_section',
        array(
            'title'       => __('Colors', 'aqualuxe'),
            'description' => __('Customize theme colors', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 40,
        )
    );

    // Primary Color
    $wp_customize->add_setting(
        'aqualuxe_primary_color',
        array(
            'default'           => '#0077b6',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label'       => __('Primary Color', 'aqualuxe'),
                'description' => __('Main brand color used for buttons, links, and accents', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_section',
            )
        )
    );

    // Secondary Color
    $wp_customize->add_setting(
        'aqualuxe_secondary_color',
        array(
            'default'           => '#00b4d8',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_secondary_color',
            array(
                'label'       => __('Secondary Color', 'aqualuxe'),
                'description' => __('Secondary brand color for contrast and variety', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_section',
            )
        )
    );

    // Accent Color
    $wp_customize->add_setting(
        'aqualuxe_accent_color',
        array(
            'default'           => '#90e0ef',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_accent_color',
            array(
                'label'       => __('Accent Color', 'aqualuxe'),
                'description' => __('Used for highlights and subtle accents', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_section',
            )
        )
    );

    // Text Color (Light Mode)
    $wp_customize->add_setting(
        'aqualuxe_text_color_light',
        array(
            'default'           => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_text_color_light',
            array(
                'label'       => __('Text Color (Light Mode)', 'aqualuxe'),
                'description' => __('Main text color in light mode', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_section',
            )
        )
    );

    // Background Color (Light Mode)
    $wp_customize->add_setting(
        'aqualuxe_background_color_light',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_background_color_light',
            array(
                'label'       => __('Background Color (Light Mode)', 'aqualuxe'),
                'description' => __('Main background color in light mode', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_section',
            )
        )
    );

    // Text Color (Dark Mode)
    $wp_customize->add_setting(
        'aqualuxe_text_color_dark',
        array(
            'default'           => '#e0e0e0',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_text_color_dark',
            array(
                'label'       => __('Text Color (Dark Mode)', 'aqualuxe'),
                'description' => __('Main text color in dark mode', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_section',
            )
        )
    );

    // Background Color (Dark Mode)
    $wp_customize->add_setting(
        'aqualuxe_background_color_dark',
        array(
            'default'           => '#121212',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_background_color_dark',
            array(
                'label'       => __('Background Color (Dark Mode)', 'aqualuxe'),
                'description' => __('Main background color in dark mode', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_section',
            )
        )
    );

    // Header Background Color
    $wp_customize->add_setting(
        'aqualuxe_header_background_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_header_background_color',
            array(
                'label'       => __('Header Background Color', 'aqualuxe'),
                'description' => __('Background color for the header', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_section',
            )
        )
    );

    // Footer Background Color
    $wp_customize->add_setting(
        'aqualuxe_footer_background_color',
        array(
            'default'           => '#f8f9fa',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_footer_background_color',
            array(
                'label'       => __('Footer Background Color', 'aqualuxe'),
                'description' => __('Background color for the footer', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_section',
            )
        )
    );

    // Button Background Color
    $wp_customize->add_setting(
        'aqualuxe_button_background_color',
        array(
            'default'           => '#0077b6',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_button_background_color',
            array(
                'label'       => __('Button Background Color', 'aqualuxe'),
                'description' => __('Background color for buttons', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_section',
            )
        )
    );

    // Button Text Color
    $wp_customize->add_setting(
        'aqualuxe_button_text_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_button_text_color',
            array(
                'label'       => __('Button Text Color', 'aqualuxe'),
                'description' => __('Text color for buttons', 'aqualuxe'),
                'section'     => 'aqualuxe_colors_section',
            )
        )
    );
}

/**
 * Typography Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_typography_section($wp_customize) {
    // Add Typography section
    $wp_customize->add_section(
        'aqualuxe_typography_section',
        array(
            'title'       => __('Typography', 'aqualuxe'),
            'description' => __('Customize fonts and typography', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 50,
        )
    );

    // Body Font Family
    $wp_customize->add_setting(
        'aqualuxe_body_font_family',
        array(
            'default'           => 'Inter, sans-serif',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font_family',
        array(
            'label'       => __('Body Font Family', 'aqualuxe'),
            'description' => __('Font family for body text', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_section',
            'type'        => 'select',
            'choices'     => array(
                'Inter, sans-serif'                => __('Inter (Default)', 'aqualuxe'),
                'Roboto, sans-serif'               => __('Roboto', 'aqualuxe'),
                'Open Sans, sans-serif'            => __('Open Sans', 'aqualuxe'),
                'Lato, sans-serif'                 => __('Lato', 'aqualuxe'),
                'Montserrat, sans-serif'           => __('Montserrat', 'aqualuxe'),
                'Poppins, sans-serif'              => __('Poppins', 'aqualuxe'),
                'Raleway, sans-serif'              => __('Raleway', 'aqualuxe'),
                'Nunito, sans-serif'               => __('Nunito', 'aqualuxe'),
                'Playfair Display, serif'          => __('Playfair Display', 'aqualuxe'),
                'Merriweather, serif'              => __('Merriweather', 'aqualuxe'),
                'Lora, serif'                      => __('Lora', 'aqualuxe'),
                'Source Sans Pro, sans-serif'      => __('Source Sans Pro', 'aqualuxe'),
                'Noto Sans, sans-serif'            => __('Noto Sans', 'aqualuxe'),
                'Noto Serif, serif'                => __('Noto Serif', 'aqualuxe'),
                'Quicksand, sans-serif'            => __('Quicksand', 'aqualuxe'),
                'Work Sans, sans-serif'            => __('Work Sans', 'aqualuxe'),
                'Mulish, sans-serif'               => __('Mulish', 'aqualuxe'),
                'Rubik, sans-serif'                => __('Rubik', 'aqualuxe'),
                'Karla, sans-serif'                => __('Karla', 'aqualuxe'),
            ),
        )
    );

    // Heading Font Family
    $wp_customize->add_setting(
        'aqualuxe_heading_font_family',
        array(
            'default'           => 'Montserrat, sans-serif',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font_family',
        array(
            'label'       => __('Heading Font Family', 'aqualuxe'),
            'description' => __('Font family for headings', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_section',
            'type'        => 'select',
            'choices'     => array(
                'Montserrat, sans-serif'           => __('Montserrat (Default)', 'aqualuxe'),
                'Inter, sans-serif'                => __('Inter', 'aqualuxe'),
                'Roboto, sans-serif'               => __('Roboto', 'aqualuxe'),
                'Open Sans, sans-serif'            => __('Open Sans', 'aqualuxe'),
                'Lato, sans-serif'                 => __('Lato', 'aqualuxe'),
                'Poppins, sans-serif'              => __('Poppins', 'aqualuxe'),
                'Raleway, sans-serif'              => __('Raleway', 'aqualuxe'),
                'Nunito, sans-serif'               => __('Nunito', 'aqualuxe'),
                'Playfair Display, serif'          => __('Playfair Display', 'aqualuxe'),
                'Merriweather, serif'              => __('Merriweather', 'aqualuxe'),
                'Lora, serif'                      => __('Lora', 'aqualuxe'),
                'Source Sans Pro, sans-serif'      => __('Source Sans Pro', 'aqualuxe'),
                'Noto Sans, sans-serif'            => __('Noto Sans', 'aqualuxe'),
                'Noto Serif, serif'                => __('Noto Serif', 'aqualuxe'),
                'Quicksand, sans-serif'            => __('Quicksand', 'aqualuxe'),
                'Work Sans, sans-serif'            => __('Work Sans', 'aqualuxe'),
                'Mulish, sans-serif'               => __('Mulish', 'aqualuxe'),
                'Rubik, sans-serif'                => __('Rubik', 'aqualuxe'),
                'Karla, sans-serif'                => __('Karla', 'aqualuxe'),
            ),
        )
    );

    // Body Font Size
    $wp_customize->add_setting(
        'aqualuxe_body_font_size',
        array(
            'default'           => '16',
            'sanitize_callback' => 'absint',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_body_font_size',
        array(
            'label'       => __('Body Font Size (px)', 'aqualuxe'),
            'description' => __('Base font size for body text', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 12,
                'max'  => 24,
                'step' => 1,
            ),
        )
    );

    // Line Height
    $wp_customize->add_setting(
        'aqualuxe_line_height',
        array(
            'default'           => '1.6',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_line_height',
        array(
            'label'       => __('Line Height', 'aqualuxe'),
            'description' => __('Base line height for body text', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_section',
            'type'        => 'select',
            'choices'     => array(
                '1.2' => __('Tight (1.2)', 'aqualuxe'),
                '1.4' => __('Compact (1.4)', 'aqualuxe'),
                '1.6' => __('Normal (1.6)', 'aqualuxe'),
                '1.8' => __('Relaxed (1.8)', 'aqualuxe'),
                '2.0' => __('Loose (2.0)', 'aqualuxe'),
            ),
        )
    );

    // Heading Line Height
    $wp_customize->add_setting(
        'aqualuxe_heading_line_height',
        array(
            'default'           => '1.2',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_line_height',
        array(
            'label'       => __('Heading Line Height', 'aqualuxe'),
            'description' => __('Line height for headings', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_section',
            'type'        => 'select',
            'choices'     => array(
                '1.0' => __('Extra Tight (1.0)', 'aqualuxe'),
                '1.1' => __('Very Tight (1.1)', 'aqualuxe'),
                '1.2' => __('Tight (1.2)', 'aqualuxe'),
                '1.3' => __('Compact (1.3)', 'aqualuxe'),
                '1.4' => __('Normal (1.4)', 'aqualuxe'),
                '1.5' => __('Relaxed (1.5)', 'aqualuxe'),
            ),
        )
    );

    // Font Weight
    $wp_customize->add_setting(
        'aqualuxe_font_weight',
        array(
            'default'           => '400',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_font_weight',
        array(
            'label'       => __('Body Font Weight', 'aqualuxe'),
            'description' => __('Font weight for body text', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_section',
            'type'        => 'select',
            'choices'     => array(
                '300' => __('Light (300)', 'aqualuxe'),
                '400' => __('Regular (400)', 'aqualuxe'),
                '500' => __('Medium (500)', 'aqualuxe'),
                '600' => __('Semi-Bold (600)', 'aqualuxe'),
                '700' => __('Bold (700)', 'aqualuxe'),
            ),
        )
    );

    // Heading Font Weight
    $wp_customize->add_setting(
        'aqualuxe_heading_font_weight',
        array(
            'default'           => '600',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'postMessage',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_heading_font_weight',
        array(
            'label'       => __('Heading Font Weight', 'aqualuxe'),
            'description' => __('Font weight for headings', 'aqualuxe'),
            'section'     => 'aqualuxe_typography_section',
            'type'        => 'select',
            'choices'     => array(
                '400' => __('Regular (400)', 'aqualuxe'),
                '500' => __('Medium (500)', 'aqualuxe'),
                '600' => __('Semi-Bold (600)', 'aqualuxe'),
                '700' => __('Bold (700)', 'aqualuxe'),
                '800' => __('Extra-Bold (800)', 'aqualuxe'),
                '900' => __('Black (900)', 'aqualuxe'),
            ),
        )
    );
}

/**
 * Layout Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_layout_section($wp_customize) {
    // Add Layout section
    $wp_customize->add_section(
        'aqualuxe_layout_section',
        array(
            'title'       => __('Layout Settings', 'aqualuxe'),
            'description' => __('Configure layout options', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 60,
        )
    );

    // Content Layout
    $wp_customize->add_setting(
        'aqualuxe_content_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_content_layout',
        array(
            'label'       => __('Content Layout', 'aqualuxe'),
            'description' => __('Choose the default layout for pages and posts', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
                'full-width'    => __('Full Width', 'aqualuxe'),
            ),
        )
    );

    // Shop Layout
    $wp_customize->add_setting(
        'aqualuxe_shop_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_shop_layout',
        array(
            'label'       => __('Shop Layout', 'aqualuxe'),
            'description' => __('Choose the layout for shop pages', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
                'full-width'    => __('Full Width', 'aqualuxe'),
            ),
        )
    );

    // Single Product Layout
    $wp_customize->add_setting(
        'aqualuxe_product_layout',
        array(
            'default'           => 'no-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_layout',
        array(
            'label'       => __('Single Product Layout', 'aqualuxe'),
            'description' => __('Choose the layout for single product pages', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
                'full-width'    => __('Full Width', 'aqualuxe'),
            ),
        )
    );

    // Blog Layout
    $wp_customize->add_setting(
        'aqualuxe_blog_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_layout',
        array(
            'label'       => __('Blog Layout', 'aqualuxe'),
            'description' => __('Choose the layout for blog pages', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
                'full-width'    => __('Full Width', 'aqualuxe'),
            ),
        )
    );

    // Single Post Layout
    $wp_customize->add_setting(
        'aqualuxe_post_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_post_layout',
        array(
            'label'       => __('Single Post Layout', 'aqualuxe'),
            'description' => __('Choose the layout for single post pages', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
                'full-width'    => __('Full Width', 'aqualuxe'),
            ),
        )
    );

    // Page Layout
    $wp_customize->add_setting(
        'aqualuxe_page_layout',
        array(
            'default'           => 'no-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_page_layout',
        array(
            'label'       => __('Page Layout', 'aqualuxe'),
            'description' => __('Choose the layout for pages', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
                'full-width'    => __('Full Width', 'aqualuxe'),
            ),
        )
    );

    // Archive Layout
    $wp_customize->add_setting(
        'aqualuxe_archive_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_archive_layout',
        array(
            'label'       => __('Archive Layout', 'aqualuxe'),
            'description' => __('Choose the layout for archive pages', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
                'full-width'    => __('Full Width', 'aqualuxe'),
            ),
        )
    );

    // Search Layout
    $wp_customize->add_setting(
        'aqualuxe_search_layout',
        array(
            'default'           => 'right-sidebar',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_search_layout',
        array(
            'label'       => __('Search Layout', 'aqualuxe'),
            'description' => __('Choose the layout for search results pages', 'aqualuxe'),
            'section'     => 'aqualuxe_layout_section',
            'type'        => 'select',
            'choices'     => array(
                'right-sidebar' => __('Right Sidebar', 'aqualuxe'),
                'left-sidebar'  => __('Left Sidebar', 'aqualuxe'),
                'no-sidebar'    => __('No Sidebar', 'aqualuxe'),
                'full-width'    => __('Full Width', 'aqualuxe'),
            ),
        )
    );
}

/**
 * Blog Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_blog_section($wp_customize) {
    // Add Blog section
    $wp_customize->add_section(
        'aqualuxe_blog_section',
        array(
            'title'       => __('Blog Settings', 'aqualuxe'),
            'description' => __('Configure blog options', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 70,
        )
    );

    // Blog Style
    $wp_customize->add_setting(
        'aqualuxe_blog_style',
        array(
            'default'           => 'grid',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_style',
        array(
            'label'       => __('Blog Style', 'aqualuxe'),
            'description' => __('Choose the style for blog listings', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'select',
            'choices'     => array(
                'grid'     => __('Grid', 'aqualuxe'),
                'list'     => __('List', 'aqualuxe'),
                'masonry'  => __('Masonry', 'aqualuxe'),
                'standard' => __('Standard', 'aqualuxe'),
            ),
        )
    );

    // Posts Per Page
    $wp_customize->add_setting(
        'aqualuxe_posts_per_page',
        array(
            'default'           => '9',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_posts_per_page',
        array(
            'label'       => __('Posts Per Page', 'aqualuxe'),
            'description' => __('Number of posts to display per page', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 50,
                'step' => 1,
            ),
        )
    );

    // Grid Columns
    $wp_customize->add_setting(
        'aqualuxe_blog_columns',
        array(
            'default'           => '3',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_blog_columns',
        array(
            'label'       => __('Grid/Masonry Columns', 'aqualuxe'),
            'description' => __('Number of columns for grid or masonry layout', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'select',
            'choices'     => array(
                '2' => __('2 Columns', 'aqualuxe'),
                '3' => __('3 Columns', 'aqualuxe'),
                '4' => __('4 Columns', 'aqualuxe'),
            ),
        )
    );

    // Excerpt Length
    $wp_customize->add_setting(
        'aqualuxe_excerpt_length',
        array(
            'default'           => '30',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_excerpt_length',
        array(
            'label'       => __('Excerpt Length', 'aqualuxe'),
            'description' => __('Number of words in post excerpts', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 10,
                'max'  => 100,
                'step' => 5,
            ),
        )
    );

    // Show Featured Image
    $wp_customize->add_setting(
        'aqualuxe_show_featured_image',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_featured_image',
        array(
            'label'       => __('Show Featured Image', 'aqualuxe'),
            'description' => __('Display featured image on single posts', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'checkbox',
        )
    );

    // Show Post Meta
    $wp_customize->add_setting(
        'aqualuxe_show_post_meta',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_meta',
        array(
            'label'       => __('Show Post Meta', 'aqualuxe'),
            'description' => __('Display post date, author, categories, etc.', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'checkbox',
        )
    );

    // Show Author Bio
    $wp_customize->add_setting(
        'aqualuxe_show_author_bio',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_author_bio',
        array(
            'label'       => __('Show Author Bio', 'aqualuxe'),
            'description' => __('Display author biography on single posts', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'checkbox',
        )
    );

    // Show Related Posts
    $wp_customize->add_setting(
        'aqualuxe_show_related_posts',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_related_posts',
        array(
            'label'       => __('Show Related Posts', 'aqualuxe'),
            'description' => __('Display related posts on single posts', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'checkbox',
        )
    );

    // Related Posts Count
    $wp_customize->add_setting(
        'aqualuxe_related_posts_count',
        array(
            'default'           => '3',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_related_posts_count',
        array(
            'label'       => __('Related Posts Count', 'aqualuxe'),
            'description' => __('Number of related posts to display', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 12,
                'step' => 1,
            ),
        )
    );

    // Show Post Navigation
    $wp_customize->add_setting(
        'aqualuxe_show_post_navigation',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_post_navigation',
        array(
            'label'       => __('Show Post Navigation', 'aqualuxe'),
            'description' => __('Display previous/next post navigation', 'aqualuxe'),
            'section'     => 'aqualuxe_blog_section',
            'type'        => 'checkbox',
        )
    );
}

/**
 * WooCommerce Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_woocommerce_section($wp_customize) {
    // Add WooCommerce section
    $wp_customize->add_section(
        'aqualuxe_woocommerce_section',
        array(
            'title'       => __('WooCommerce Settings', 'aqualuxe'),
            'description' => __('Configure WooCommerce options', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 80,
        )
    );

    // Products Per Page
    $wp_customize->add_setting(
        'aqualuxe_products_per_page',
        array(
            'default'           => '12',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_products_per_page',
        array(
            'label'       => __('Products Per Page', 'aqualuxe'),
            'description' => __('Number of products to display per page', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 100,
                'step' => 1,
            ),
        )
    );

    // Product Columns
    $wp_customize->add_setting(
        'aqualuxe_product_columns',
        array(
            'default'           => '4',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_product_columns',
        array(
            'label'       => __('Product Columns', 'aqualuxe'),
            'description' => __('Number of columns for product grid', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'select',
            'choices'     => array(
                '2' => __('2 Columns', 'aqualuxe'),
                '3' => __('3 Columns', 'aqualuxe'),
                '4' => __('4 Columns', 'aqualuxe'),
                '5' => __('5 Columns', 'aqualuxe'),
                '6' => __('6 Columns', 'aqualuxe'),
            ),
        )
    );

    // Related Products Count
    $wp_customize->add_setting(
        'aqualuxe_related_products_count',
        array(
            'default'           => '4',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_related_products_count',
        array(
            'label'       => __('Related Products Count', 'aqualuxe'),
            'description' => __('Number of related products to display', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'number',
            'input_attrs' => array(
                'min'  => 1,
                'max'  => 12,
                'step' => 1,
            ),
        )
    );

    // Related Products Columns
    $wp_customize->add_setting(
        'aqualuxe_related_products_columns',
        array(
            'default'           => '4',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_related_products_columns',
        array(
            'label'       => __('Related Products Columns', 'aqualuxe'),
            'description' => __('Number of columns for related products', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'select',
            'choices'     => array(
                '2' => __('2 Columns', 'aqualuxe'),
                '3' => __('3 Columns', 'aqualuxe'),
                '4' => __('4 Columns', 'aqualuxe'),
                '5' => __('5 Columns', 'aqualuxe'),
            ),
        )
    );

    // Enable Quick View
    $wp_customize->add_setting(
        'aqualuxe_enable_quick_view',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_quick_view',
        array(
            'label'       => __('Enable Quick View', 'aqualuxe'),
            'description' => __('Show quick view button on products', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Wishlist
    $wp_customize->add_setting(
        'aqualuxe_enable_wishlist',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_wishlist',
        array(
            'label'       => __('Enable Wishlist', 'aqualuxe'),
            'description' => __('Show wishlist button on products', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Product Zoom
    $wp_customize->add_setting(
        'aqualuxe_enable_product_zoom',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_product_zoom',
        array(
            'label'       => __('Enable Product Zoom', 'aqualuxe'),
            'description' => __('Allow zooming product images on hover', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Product Gallery Lightbox
    $wp_customize->add_setting(
        'aqualuxe_enable_product_lightbox',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_product_lightbox',
        array(
            'label'       => __('Enable Product Gallery Lightbox', 'aqualuxe'),
            'description' => __('Open product images in lightbox', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Product Gallery Slider
    $wp_customize->add_setting(
        'aqualuxe_enable_product_slider',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_product_slider',
        array(
            'label'       => __('Enable Product Gallery Slider', 'aqualuxe'),
            'description' => __('Show product images in a slider', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Ajax Add to Cart
    $wp_customize->add_setting(
        'aqualuxe_enable_ajax_add_to_cart',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_ajax_add_to_cart',
        array(
            'label'       => __('Enable Ajax Add to Cart', 'aqualuxe'),
            'description' => __('Add products to cart without page reload', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Cart Fragments
    $wp_customize->add_setting(
        'aqualuxe_enable_cart_fragments',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_cart_fragments',
        array(
            'label'       => __('Enable Cart Fragments', 'aqualuxe'),
            'description' => __('Update cart totals without page reload', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Show Product Categories
    $wp_customize->add_setting(
        'aqualuxe_show_product_categories',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_product_categories',
        array(
            'label'       => __('Show Product Categories', 'aqualuxe'),
            'description' => __('Display product categories on product cards', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Show Product Ratings
    $wp_customize->add_setting(
        'aqualuxe_show_product_ratings',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_product_ratings',
        array(
            'label'       => __('Show Product Ratings', 'aqualuxe'),
            'description' => __('Display product ratings on product cards', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Show Product Price
    $wp_customize->add_setting(
        'aqualuxe_show_product_price',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_product_price',
        array(
            'label'       => __('Show Product Price', 'aqualuxe'),
            'description' => __('Display product price on product cards', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Multi-Currency
    $wp_customize->add_setting(
        'aqualuxe_enable_multi_currency',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_multi_currency',
        array(
            'label'       => __('Enable Multi-Currency Support', 'aqualuxe'),
            'description' => __('Support for multiple currencies (requires compatible plugin)', 'aqualuxe'),
            'section'     => 'aqualuxe_woocommerce_section',
            'type'        => 'checkbox',
        )
    );
}

/**
 * Social Media Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_social_section($wp_customize) {
    // Add Social Media section
    $wp_customize->add_section(
        'aqualuxe_social_section',
        array(
            'title'       => __('Social Media', 'aqualuxe'),
            'description' => __('Configure social media links', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 90,
        )
    );

    // Facebook URL
    $wp_customize->add_setting(
        'aqualuxe_facebook_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_facebook_url',
        array(
            'label'       => __('Facebook URL', 'aqualuxe'),
            'description' => __('Enter your Facebook page URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
        )
    );

    // Twitter URL
    $wp_customize->add_setting(
        'aqualuxe_twitter_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_twitter_url',
        array(
            'label'       => __('Twitter URL', 'aqualuxe'),
            'description' => __('Enter your Twitter profile URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
        )
    );

    // Instagram URL
    $wp_customize->add_setting(
        'aqualuxe_instagram_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_instagram_url',
        array(
            'label'       => __('Instagram URL', 'aqualuxe'),
            'description' => __('Enter your Instagram profile URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
        )
    );

    // YouTube URL
    $wp_customize->add_setting(
        'aqualuxe_youtube_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_youtube_url',
        array(
            'label'       => __('YouTube URL', 'aqualuxe'),
            'description' => __('Enter your YouTube channel URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
        )
    );

    // Pinterest URL
    $wp_customize->add_setting(
        'aqualuxe_pinterest_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_pinterest_url',
        array(
            'label'       => __('Pinterest URL', 'aqualuxe'),
            'description' => __('Enter your Pinterest profile URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
        )
    );

    // LinkedIn URL
    $wp_customize->add_setting(
        'aqualuxe_linkedin_url',
        array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_linkedin_url',
        array(
            'label'       => __('LinkedIn URL', 'aqualuxe'),
            'description' => __('Enter your LinkedIn profile URL', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'url',
        )
    );

    // Twitter Username (without @)
    $wp_customize->add_setting(
        'aqualuxe_twitter_username',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_twitter_username',
        array(
            'label'       => __('Twitter Username', 'aqualuxe'),
            'description' => __('Enter your Twitter username without the @ symbol (for Twitter cards)', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'text',
        )
    );

    // Show Social Icons in Header
    $wp_customize->add_setting(
        'aqualuxe_show_header_social',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_header_social',
        array(
            'label'       => __('Show Social Icons in Header', 'aqualuxe'),
            'description' => __('Display social media icons in the header', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'checkbox',
        )
    );

    // Show Social Icons in Footer
    $wp_customize->add_setting(
        'aqualuxe_show_footer_social',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_show_footer_social',
        array(
            'label'       => __('Show Social Icons in Footer', 'aqualuxe'),
            'description' => __('Display social media icons in the footer', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'checkbox',
        )
    );

    // Social Sharing on Posts
    $wp_customize->add_setting(
        'aqualuxe_enable_social_sharing',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_social_sharing',
        array(
            'label'       => __('Enable Social Sharing', 'aqualuxe'),
            'description' => __('Show social sharing buttons on posts and products', 'aqualuxe'),
            'section'     => 'aqualuxe_social_section',
            'type'        => 'checkbox',
        )
    );
}

/**
 * Performance Settings Section
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_performance_section($wp_customize) {
    // Add Performance section
    $wp_customize->add_section(
        'aqualuxe_performance_section',
        array(
            'title'       => __('Performance', 'aqualuxe'),
            'description' => __('Configure performance options', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 100,
        )
    );

    // Enable Lazy Loading
    $wp_customize->add_setting(
        'aqualuxe_enable_lazy_loading',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_lazy_loading',
        array(
            'label'       => __('Enable Lazy Loading', 'aqualuxe'),
            'description' => __('Lazy load images for better performance', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Minification
    $wp_customize->add_setting(
        'aqualuxe_enable_minification',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_minification',
        array(
            'label'       => __('Enable Minification', 'aqualuxe'),
            'description' => __('Minify CSS and JavaScript files', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Preloading
    $wp_customize->add_setting(
        'aqualuxe_enable_preloading',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_preloading',
        array(
            'label'       => __('Enable Preloading', 'aqualuxe'),
            'description' => __('Preload critical assets', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Prefetching
    $wp_customize->add_setting(
        'aqualuxe_enable_prefetching',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_prefetching',
        array(
            'label'       => __('Enable Prefetching', 'aqualuxe'),
            'description' => __('Prefetch links for faster navigation', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Critical CSS
    $wp_customize->add_setting(
        'aqualuxe_enable_critical_css',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_critical_css',
        array(
            'label'       => __('Enable Critical CSS', 'aqualuxe'),
            'description' => __('Inline critical CSS for faster rendering', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Responsive Images
    $wp_customize->add_setting(
        'aqualuxe_enable_responsive_images',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_responsive_images',
        array(
            'label'       => __('Enable Responsive Images', 'aqualuxe'),
            'description' => __('Use srcset and sizes attributes for images', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_section',
            'type'        => 'checkbox',
        )
    );

    // Enable WebP Images
    $wp_customize->add_setting(
        'aqualuxe_enable_webp',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_webp',
        array(
            'label'       => __('Enable WebP Images', 'aqualuxe'),
            'description' => __('Use WebP image format when supported', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Defer JavaScript
    $wp_customize->add_setting(
        'aqualuxe_enable_defer_js',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_defer_js',
        array(
            'label'       => __('Enable Defer JavaScript', 'aqualuxe'),
            'description' => __('Defer non-critical JavaScript', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Async CSS
    $wp_customize->add_setting(
        'aqualuxe_enable_async_css',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_enable_async_css',
        array(
            'label'       => __('Enable Async CSS', 'aqualuxe'),
            'description' => __('Load non-critical CSS asynchronously', 'aqualuxe'),
            'section'     => 'aqualuxe_performance_section',
            'type'        => 'checkbox',
        )
    );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function aqualuxe_customize_preview_js() {
    wp_enqueue_script('aqualuxe-customizer', AQUALUXE_URI . 'assets/js/customizer.js', array('customize-preview'), AQUALUXE_VERSION, true);
}
add_action('customize_preview_init', 'aqualuxe_customize_preview_js');

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (isset($checked) && true === $checked) ? true : false;
}

/**
 * Sanitize select.
 *
 * @param string $input The selected value.
 * @param object $setting The setting object.
 * @return string The sanitized value.
 */
function aqualuxe_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize HTML content with allowed tags.
 *
 * @param string $content The content to sanitize.
 * @return string The sanitized content.
 */
function aqualuxe_sanitize_html($content) {
    $allowed_html = array(
        'a' => array(
            'href' => array(),
            'title' => array(),
            'target' => array(),
            'rel' => array(),
            'class' => array(),
        ),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'span' => array(
            'class' => array(),
        ),
        'i' => array(
            'class' => array(),
        ),
    );
    
    return wp_kses($content, $allowed_html);
}