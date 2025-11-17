<?php
/**
 * AquaLuxe Theme Customizer - Module Settings
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add module settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_module_settings($wp_customize) {
    // Modules Section
    $wp_customize->add_section(
        'aqualuxe_modules_section',
        array(
            'title'       => __('Modules', 'aqualuxe'),
            'description' => __('Enable or disable theme modules and configure their settings.', 'aqualuxe'),
            'priority'    => 90,
        )
    );

    // Dark Mode Heading
    $wp_customize->add_setting(
        'aqualuxe_dark_mode_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_dark_mode_heading',
            array(
                'label'   => __('Dark Mode', 'aqualuxe'),
                'section' => 'aqualuxe_modules_section',
            )
        )
    );

    // Enable Dark Mode
    $wp_customize->add_setting(
        'aqualuxe_options[enable_dark_mode]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_dark_mode]',
        array(
            'label'       => __('Enable Dark Mode', 'aqualuxe'),
            'description' => __('Allow users to switch between light and dark color schemes.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // Dark Mode Default
    $wp_customize->add_setting(
        'aqualuxe_options[dark_mode_default]',
        array(
            'default'           => 'light',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[dark_mode_default]',
        array(
            'label'       => __('Default Mode', 'aqualuxe'),
            'description' => __('Choose the default color scheme.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'select',
            'choices'     => array(
                'light'    => __('Light', 'aqualuxe'),
                'dark'     => __('Dark', 'aqualuxe'),
                'auto'     => __('Auto (Follow System Preference)', 'aqualuxe'),
            ),
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['enable_dark_mode']) ? $options['enable_dark_mode'] : true;
            },
        )
    );

    // Dark Mode Toggle Style
    $wp_customize->add_setting(
        'aqualuxe_options[dark_mode_toggle_style]',
        array(
            'default'           => 'icon',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[dark_mode_toggle_style]',
        array(
            'label'       => __('Toggle Style', 'aqualuxe'),
            'description' => __('Choose the style for the dark mode toggle.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'select',
            'choices'     => array(
                'icon'     => __('Icon Only', 'aqualuxe'),
                'switch'   => __('Switch', 'aqualuxe'),
                'button'   => __('Button', 'aqualuxe'),
                'text'     => __('Text', 'aqualuxe'),
            ),
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['enable_dark_mode']) ? $options['enable_dark_mode'] : true;
            },
        )
    );

    // Dark Mode Toggle Position
    $wp_customize->add_setting(
        'aqualuxe_options[dark_mode_toggle_position]',
        array(
            'default'           => 'header',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[dark_mode_toggle_position]',
        array(
            'label'       => __('Toggle Position', 'aqualuxe'),
            'description' => __('Choose where to display the dark mode toggle.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'select',
            'choices'     => array(
                'header'   => __('Header', 'aqualuxe'),
                'footer'   => __('Footer', 'aqualuxe'),
                'both'     => __('Both Header and Footer', 'aqualuxe'),
                'floating' => __('Floating Button', 'aqualuxe'),
            ),
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['enable_dark_mode']) ? $options['enable_dark_mode'] : true;
            },
        )
    );

    // Multilingual Heading
    $wp_customize->add_setting(
        'aqualuxe_multilingual_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_multilingual_heading',
            array(
                'label'   => __('Multilingual Support', 'aqualuxe'),
                'section' => 'aqualuxe_modules_section',
            )
        )
    );

    // Enable Language Switcher
    $wp_customize->add_setting(
        'aqualuxe_options[enable_language_switcher]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_language_switcher]',
        array(
            'label'       => __('Enable Language Switcher', 'aqualuxe'),
            'description' => __('Display language switcher in the header (requires WPML or Polylang).', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // Language Switcher Style
    $wp_customize->add_setting(
        'aqualuxe_options[language_switcher_style]',
        array(
            'default'           => 'dropdown',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[language_switcher_style]',
        array(
            'label'       => __('Language Switcher Style', 'aqualuxe'),
            'description' => __('Choose the style for the language switcher.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'select',
            'choices'     => array(
                'dropdown'  => __('Dropdown', 'aqualuxe'),
                'inline'    => __('Inline', 'aqualuxe'),
                'flags'     => __('Flags Only', 'aqualuxe'),
                'full'      => __('Flags with Text', 'aqualuxe'),
            ),
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['enable_language_switcher']) ? $options['enable_language_switcher'] : true;
            },
        )
    );

    // Show Flags
    $wp_customize->add_setting(
        'aqualuxe_options[language_switcher_flags]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[language_switcher_flags]',
        array(
            'label'       => __('Show Language Flags', 'aqualuxe'),
            'description' => __('Display country flags in the language switcher.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['enable_language_switcher']) ? $options['enable_language_switcher'] : true;
            },
        )
    );

    // Performance Heading
    $wp_customize->add_setting(
        'aqualuxe_performance_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_performance_heading',
            array(
                'label'   => __('Performance Optimization', 'aqualuxe'),
                'section' => 'aqualuxe_modules_section',
            )
        )
    );

    // Enable Lazy Loading
    $wp_customize->add_setting(
        'aqualuxe_options[enable_lazy_loading]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_lazy_loading]',
        array(
            'label'       => __('Enable Lazy Loading', 'aqualuxe'),
            'description' => __('Lazy load images and iframes for better performance.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Asset Minification
    $wp_customize->add_setting(
        'aqualuxe_options[enable_minification]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_minification]',
        array(
            'label'       => __('Enable Asset Minification', 'aqualuxe'),
            'description' => __('Minify CSS and JavaScript files for faster loading.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Critical CSS
    $wp_customize->add_setting(
        'aqualuxe_options[enable_critical_css]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_critical_css]',
        array(
            'label'       => __('Enable Critical CSS', 'aqualuxe'),
            'description' => __('Inline critical CSS for faster initial rendering.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Browser Caching
    $wp_customize->add_setting(
        'aqualuxe_options[enable_browser_caching]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_browser_caching]',
        array(
            'label'       => __('Enable Browser Caching', 'aqualuxe'),
            'description' => __('Set browser caching headers for static assets.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // SEO Heading
    $wp_customize->add_setting(
        'aqualuxe_seo_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_seo_heading',
            array(
                'label'   => __('SEO', 'aqualuxe'),
                'section' => 'aqualuxe_modules_section',
            )
        )
    );

    // Enable Schema Markup
    $wp_customize->add_setting(
        'aqualuxe_options[enable_schema_markup]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_schema_markup]',
        array(
            'label'       => __('Enable Schema Markup', 'aqualuxe'),
            'description' => __('Add structured data markup for better SEO.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Breadcrumbs
    $wp_customize->add_setting(
        'aqualuxe_options[enable_breadcrumbs]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_breadcrumbs]',
        array(
            'label'       => __('Enable Breadcrumbs', 'aqualuxe'),
            'description' => __('Display breadcrumb navigation on pages and posts.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Meta Tags
    $wp_customize->add_setting(
        'aqualuxe_options[enable_meta_tags]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_meta_tags]',
        array(
            'label'       => __('Enable Meta Tags', 'aqualuxe'),
            'description' => __('Add meta description and keywords to pages and posts.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Open Graph
    $wp_customize->add_setting(
        'aqualuxe_options[enable_open_graph]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_open_graph]',
        array(
            'label'       => __('Enable Open Graph', 'aqualuxe'),
            'description' => __('Add Open Graph meta tags for better social sharing.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Twitter Cards
    $wp_customize->add_setting(
        'aqualuxe_options[enable_twitter_cards]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_twitter_cards]',
        array(
            'label'       => __('Enable Twitter Cards', 'aqualuxe'),
            'description' => __('Add Twitter Card meta tags for better Twitter sharing.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // Additional Modules Heading
    $wp_customize->add_setting(
        'aqualuxe_additional_modules_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new AquaLuxe_Customize_Heading_Control(
            $wp_customize,
            'aqualuxe_additional_modules_heading',
            array(
                'label'   => __('Additional Modules', 'aqualuxe'),
                'section' => 'aqualuxe_modules_section',
            )
        )
    );

    // Enable Back to Top
    $wp_customize->add_setting(
        'aqualuxe_options[enable_back_to_top]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_back_to_top]',
        array(
            'label'       => __('Enable Back to Top', 'aqualuxe'),
            'description' => __('Display a back to top button on long pages.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Cookie Notice
    $wp_customize->add_setting(
        'aqualuxe_options[enable_cookie_notice]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_cookie_notice]',
        array(
            'label'       => __('Enable Cookie Notice', 'aqualuxe'),
            'description' => __('Display a cookie consent notice for GDPR compliance.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // Cookie Notice Text
    $wp_customize->add_setting(
        'aqualuxe_options[cookie_notice_text]',
        array(
            'default'           => __('This website uses cookies to ensure you get the best experience on our website.', 'aqualuxe'),
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[cookie_notice_text]',
        array(
            'label'       => __('Cookie Notice Text', 'aqualuxe'),
            'description' => __('Text to display in the cookie notice.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'textarea',
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['enable_cookie_notice']) ? $options['enable_cookie_notice'] : true;
            },
        )
    );

    // Enable Preloader
    $wp_customize->add_setting(
        'aqualuxe_options[enable_preloader]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_preloader]',
        array(
            'label'       => __('Enable Preloader', 'aqualuxe'),
            'description' => __('Display a loading animation while the page loads.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // Preloader Style
    $wp_customize->add_setting(
        'aqualuxe_options[preloader_style]',
        array(
            'default'           => 'wave',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[preloader_style]',
        array(
            'label'       => __('Preloader Style', 'aqualuxe'),
            'description' => __('Choose the style for the preloader animation.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'select',
            'choices'     => array(
                'spinner'  => __('Spinner', 'aqualuxe'),
                'wave'     => __('Wave', 'aqualuxe'),
                'dots'     => __('Dots', 'aqualuxe'),
                'pulse'    => __('Pulse', 'aqualuxe'),
                'logo'     => __('Logo Animation', 'aqualuxe'),
            ),
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['enable_preloader']) ? $options['enable_preloader'] : true;
            },
        )
    );

    // Enable Smooth Scroll
    $wp_customize->add_setting(
        'aqualuxe_options[enable_smooth_scroll]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_smooth_scroll]',
        array(
            'label'       => __('Enable Smooth Scroll', 'aqualuxe'),
            'description' => __('Enable smooth scrolling for a better user experience.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // Enable Animations
    $wp_customize->add_setting(
        'aqualuxe_options[enable_animations]',
        array(
            'default'           => true,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[enable_animations]',
        array(
            'label'       => __('Enable Animations', 'aqualuxe'),
            'description' => __('Enable scroll-based animations for page elements.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'checkbox',
        )
    );

    // Animation Style
    $wp_customize->add_setting(
        'aqualuxe_options[animation_style]',
        array(
            'default'           => 'fade',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'transport'         => 'refresh',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_options[animation_style]',
        array(
            'label'       => __('Animation Style', 'aqualuxe'),
            'description' => __('Choose the default animation style.', 'aqualuxe'),
            'section'     => 'aqualuxe_modules_section',
            'type'        => 'select',
            'choices'     => array(
                'fade'      => __('Fade In', 'aqualuxe'),
                'slide-up'  => __('Slide Up', 'aqualuxe'),
                'slide-down' => __('Slide Down', 'aqualuxe'),
                'slide-left' => __('Slide Left', 'aqualuxe'),
                'slide-right' => __('Slide Right', 'aqualuxe'),
                'zoom-in'   => __('Zoom In', 'aqualuxe'),
                'zoom-out'  => __('Zoom Out', 'aqualuxe'),
                'flip'      => __('Flip', 'aqualuxe'),
            ),
            'active_callback' => function() {
                $options = get_option('aqualuxe_options', array());
                return isset($options['enable_animations']) ? $options['enable_animations'] : true;
            },
        )
    );
}
add_action('customize_register', 'aqualuxe_customize_module_settings');