<?php
/**
 * Multilingual Customizer Settings
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Add multilingual settings to the Customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_customize_register_multilingual($wp_customize) {
    // Add Multilingual section
    $wp_customize->add_section('aqualuxe_multilingual', array(
        'title' => esc_html__('Multilingual Settings', 'aqualuxe'),
        'priority' => 100,
    ));

    // Enable Multilingual Support
    $wp_customize->add_setting('aqualuxe_enable_multilingual', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_multilingual', array(
        'label' => esc_html__('Enable Multilingual Support', 'aqualuxe'),
        'description' => esc_html__('Enable language switcher and multilingual features.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 10,
    )));

    // Language Switcher Position
    $wp_customize->add_setting('aqualuxe_language_switcher_position', array(
        'default' => 'header',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_language_switcher_position', array(
        'label' => esc_html__('Language Switcher Position', 'aqualuxe'),
        'description' => esc_html__('Select where to display the language switcher.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'type' => 'select',
        'choices' => array(
            'header' => esc_html__('Header', 'aqualuxe'),
            'top_bar' => esc_html__('Top Bar', 'aqualuxe'),
            'footer' => esc_html__('Footer', 'aqualuxe'),
            'menu' => esc_html__('Primary Menu', 'aqualuxe'),
            'sidebar' => esc_html__('Sidebar', 'aqualuxe'),
        ),
        'priority' => 20,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true);
        },
    ));

    // Language Switcher Style
    $wp_customize->add_setting('aqualuxe_language_switcher_style', array(
        'default' => 'dropdown',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_language_switcher_style', array(
        'label' => esc_html__('Language Switcher Style', 'aqualuxe'),
        'description' => esc_html__('Select the style for the language switcher.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'type' => 'select',
        'choices' => array(
            'dropdown' => esc_html__('Dropdown', 'aqualuxe'),
            'list' => esc_html__('Horizontal List', 'aqualuxe'),
            'flags' => esc_html__('Flags Only', 'aqualuxe'),
            'flags_name' => esc_html__('Flags with Names', 'aqualuxe'),
        ),
        'priority' => 30,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true);
        },
    ));

    // Show Flags
    $wp_customize->add_setting('aqualuxe_show_language_flags', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_language_flags', array(
        'label' => esc_html__('Show Language Flags', 'aqualuxe'),
        'description' => esc_html__('Display flags next to language names.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 40,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true) && 
                   (get_theme_mod('aqualuxe_language_switcher_style', 'dropdown') === 'dropdown' || 
                    get_theme_mod('aqualuxe_language_switcher_style', 'dropdown') === 'list');
        },
    )));

    // Show Language Names
    $wp_customize->add_setting('aqualuxe_show_language_names', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_language_names', array(
        'label' => esc_html__('Show Language Names', 'aqualuxe'),
        'description' => esc_html__('Display language names in the switcher.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 50,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true) && 
                   get_theme_mod('aqualuxe_language_switcher_style', 'dropdown') === 'flags';
        },
    )));

    // Show Current Language
    $wp_customize->add_setting('aqualuxe_show_current_language', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_current_language', array(
        'label' => esc_html__('Show Current Language', 'aqualuxe'),
        'description' => esc_html__('Display the current language in the switcher.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 60,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true);
        },
    )));

    // Languages Heading
    $wp_customize->add_setting('aqualuxe_languages_heading', array(
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Heading($wp_customize, 'aqualuxe_languages_heading', array(
        'label' => esc_html__('Available Languages', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 70,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true);
        },
    )));

    // English Language
    $wp_customize->add_setting('aqualuxe_enable_english', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_english', array(
        'label' => esc_html__('English', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 80,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true);
        },
    )));

    // French Language
    $wp_customize->add_setting('aqualuxe_enable_french', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_french', array(
        'label' => esc_html__('French', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 90,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true);
        },
    )));

    // German Language
    $wp_customize->add_setting('aqualuxe_enable_german', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_german', array(
        'label' => esc_html__('German', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 100,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true);
        },
    )));

    // Spanish Language
    $wp_customize->add_setting('aqualuxe_enable_spanish', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_spanish', array(
        'label' => esc_html__('Spanish', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 110,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true);
        },
    )));

    // Italian Language
    $wp_customize->add_setting('aqualuxe_enable_italian', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_italian', array(
        'label' => esc_html__('Italian', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 120,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true);
        },
    )));

    // Japanese Language
    $wp_customize->add_setting('aqualuxe_enable_japanese', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_japanese', array(
        'label' => esc_html__('Japanese', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 130,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true);
        },
    )));

    // Chinese Language
    $wp_customize->add_setting('aqualuxe_enable_chinese', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_chinese', array(
        'label' => esc_html__('Chinese', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 140,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true);
        },
    )));

    // Default Language
    $wp_customize->add_setting('aqualuxe_default_language', array(
        'default' => 'en',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('aqualuxe_default_language', array(
        'label' => esc_html__('Default Language', 'aqualuxe'),
        'description' => esc_html__('Select the default language for the site.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'type' => 'select',
        'choices' => array(
            'en' => esc_html__('English', 'aqualuxe'),
            'fr' => esc_html__('French', 'aqualuxe'),
            'de' => esc_html__('German', 'aqualuxe'),
            'es' => esc_html__('Spanish', 'aqualuxe'),
            'it' => esc_html__('Italian', 'aqualuxe'),
            'ja' => esc_html__('Japanese', 'aqualuxe'),
            'zh' => esc_html__('Chinese', 'aqualuxe'),
        ),
        'priority' => 150,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true);
        },
    ));

    // RTL Support
    $wp_customize->add_setting('aqualuxe_enable_rtl_support', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_enable_rtl_support', array(
        'label' => esc_html__('Enable RTL Support', 'aqualuxe'),
        'description' => esc_html__('Enable support for right-to-left languages.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 160,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true);
        },
    )));

    // Translation Plugin Integration
    $wp_customize->add_setting('aqualuxe_translation_plugin', array(
        'default' => 'polylang',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));

    $wp_customize->add_control('aqualuxe_translation_plugin', array(
        'label' => esc_html__('Translation Plugin', 'aqualuxe'),
        'description' => esc_html__('Select the translation plugin to integrate with.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'type' => 'select',
        'choices' => array(
            'none' => esc_html__('None (Use Theme Translation)', 'aqualuxe'),
            'polylang' => esc_html__('Polylang', 'aqualuxe'),
            'wpml' => esc_html__('WPML', 'aqualuxe'),
            'gtranslate' => esc_html__('GTranslate', 'aqualuxe'),
        ),
        'priority' => 170,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true);
        },
    ));

    // Auto Redirect Based on Browser Language
    $wp_customize->add_setting('aqualuxe_auto_redirect_language', array(
        'default' => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_auto_redirect_language', array(
        'label' => esc_html__('Auto Redirect Based on Browser Language', 'aqualuxe'),
        'description' => esc_html__('Automatically redirect visitors to their preferred language based on browser settings.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 180,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true);
        },
    )));

    // Show Language in URL
    $wp_customize->add_setting('aqualuxe_show_language_in_url', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_show_language_in_url', array(
        'label' => esc_html__('Show Language in URL', 'aqualuxe'),
        'description' => esc_html__('Display language code in URL (e.g., /en/, /fr/).', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 190,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true) && 
                   get_theme_mod('aqualuxe_translation_plugin', 'polylang') !== 'none';
        },
    )));

    // Hide Default Language in URL
    $wp_customize->add_setting('aqualuxe_hide_default_language_in_url', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_hide_default_language_in_url', array(
        'label' => esc_html__('Hide Default Language in URL', 'aqualuxe'),
        'description' => esc_html__('Hide the language code in URL for the default language.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 200,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true) && 
                   get_theme_mod('aqualuxe_translation_plugin', 'polylang') !== 'none' &&
                   get_theme_mod('aqualuxe_show_language_in_url', true);
        },
    )));

    // Translate Custom Post Types
    $wp_customize->add_setting('aqualuxe_translate_custom_post_types', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_translate_custom_post_types', array(
        'label' => esc_html__('Translate Custom Post Types', 'aqualuxe'),
        'description' => esc_html__('Enable translation for custom post types.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 210,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true) && 
                   get_theme_mod('aqualuxe_translation_plugin', 'polylang') !== 'none';
        },
    )));

    // Translate Taxonomies
    $wp_customize->add_setting('aqualuxe_translate_taxonomies', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_translate_taxonomies', array(
        'label' => esc_html__('Translate Taxonomies', 'aqualuxe'),
        'description' => esc_html__('Enable translation for taxonomies.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 220,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true) && 
                   get_theme_mod('aqualuxe_translation_plugin', 'polylang') !== 'none';
        },
    )));

    // Translate Slugs
    $wp_customize->add_setting('aqualuxe_translate_slugs', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));

    $wp_customize->add_control(new AquaLuxe_Customize_Control_Toggle($wp_customize, 'aqualuxe_translate_slugs', array(
        'label' => esc_html__('Translate Slugs', 'aqualuxe'),
        'description' => esc_html__('Enable translation for URL slugs.', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'priority' => 230,
        'active_callback' => function() {
            return get_theme_mod('aqualuxe_enable_multilingual', true) && 
                   get_theme_mod('aqualuxe_translation_plugin', 'polylang') !== 'none';
        },
    )));
}
add_action('customize_register', 'aqualuxe_customize_register_multilingual');