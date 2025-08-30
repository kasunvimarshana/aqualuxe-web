<?php
/**
 * Multilingual support for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Initialize multilingual support
 */
function aqualuxe_multilingual_init() {
    // Load text domain
    load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');
    
    // Register theme for translation
    if (function_exists('pll_register_string')) {
        aqualuxe_register_polylang_strings();
    }
    
    // Add language switcher to menu
    if (aqualuxe_get_option('language_switcher_in_menu', false)) {
        add_filter('wp_nav_menu_items', 'aqualuxe_add_language_switcher_to_menu', 10, 2);
    }
    
    // Add language switcher to mobile menu
    if (aqualuxe_get_option('language_switcher_in_mobile_menu', true)) {
        add_action('aqualuxe_mobile_menu_extras', 'aqualuxe_language_switcher', 10);
    }
    
    // Add language switcher to footer
    if (aqualuxe_get_option('language_switcher_in_footer', false)) {
        add_action('aqualuxe_footer_bottom', 'aqualuxe_language_switcher', 15);
    }
}
add_action('after_setup_theme', 'aqualuxe_multilingual_init');

/**
 * Register strings for translation with Polylang
 */
function aqualuxe_register_polylang_strings() {
    // Theme options
    $options = [
        'copyright_text' => __('Copyright Text', 'aqualuxe'),
        'newsletter_title' => __('Newsletter Title', 'aqualuxe'),
        'newsletter_description' => __('Newsletter Description', 'aqualuxe'),
        'newsletter_button_text' => __('Newsletter Button Text', 'aqualuxe'),
    ];
    
    foreach ($options as $option => $name) {
        $value = aqualuxe_get_option($option, '');
        if ($value) {
            pll_register_string('aqualuxe_' . $option, $value, 'AquaLuxe Theme');
        }
    }
    
    // Footer text
    $copyright_text = aqualuxe_get_option('copyright_text', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . __('All rights reserved.', 'aqualuxe'));
    pll_register_string('aqualuxe_copyright', $copyright_text, 'AquaLuxe Theme');
    
    // Contact information
    $contact_fields = [
        'contact_address' => __('Address', 'aqualuxe'),
        'contact_phone' => __('Phone', 'aqualuxe'),
        'contact_email' => __('Email', 'aqualuxe'),
        'contact_hours' => __('Working Hours', 'aqualuxe'),
    ];
    
    foreach ($contact_fields as $field => $name) {
        $value = aqualuxe_get_option($field, '');
        if ($value) {
            pll_register_string('aqualuxe_' . $field, $value, 'AquaLuxe Theme');
        }
    }
    
    // Custom labels
    $custom_labels = [
        'search_placeholder' => __('Search placeholder', 'aqualuxe'),
        'read_more_text' => __('Read more text', 'aqualuxe'),
        'related_posts_title' => __('Related posts title', 'aqualuxe'),
    ];
    
    foreach ($custom_labels as $label => $name) {
        $value = aqualuxe_get_option($label, '');
        if ($value) {
            pll_register_string('aqualuxe_' . $label, $value, 'AquaLuxe Theme');
        }
    }
}

/**
 * Add language switcher to menu
 *
 * @param string $items Menu items
 * @param object $args Menu arguments
 * @return string
 */
function aqualuxe_add_language_switcher_to_menu($items, $args) {
    if ($args->theme_location == 'primary') {
        ob_start();
        aqualuxe_language_switcher();
        $language_switcher = ob_get_clean();
        
        if ($language_switcher) {
            $items .= '<li class="menu-item menu-item-language-switcher">' . $language_switcher . '</li>';
        }
    }
    
    return $items;
}

/**
 * Get translated option
 *
 * @param string $option_name Option name
 * @param mixed $default Default value
 * @return mixed
 */
function aqualuxe_get_translated_option($option_name, $default = '') {
    $value = aqualuxe_get_option($option_name, $default);
    
    // Translate with Polylang if available
    if (function_exists('pll__')) {
        $value = pll__($value);
    }
    
    // Translate with WPML if available
    if (function_exists('icl_t')) {
        $value = icl_t('AquaLuxe Theme', 'aqualuxe_' . $option_name, $value);
    }
    
    return $value;
}

/**
 * Get translated string
 *
 * @param string $string String to translate
 * @param string $domain Text domain
 * @return string
 */
function aqualuxe_translate_string($string, $domain = 'aqualuxe') {
    // Translate with WordPress
    $translated = __($string, $domain);
    
    // Translate with Polylang if available
    if (function_exists('pll__')) {
        $translated = pll__($translated);
    }
    
    // Translate with WPML if available
    if (function_exists('icl_t')) {
        $translated = icl_t($domain, $string, $translated);
    }
    
    return $translated;
}

/**
 * Get current language information
 *
 * @return array
 */
function aqualuxe_get_current_language_info() {
    $language_info = [
        'code' => 'en',
        'name' => 'English',
        'flag' => '',
        'is_rtl' => false,
    ];
    
    // Check for WPML
    if (defined('ICL_LANGUAGE_CODE')) {
        $language_info['code'] = ICL_LANGUAGE_CODE;
        
        global $sitepress;
        if ($sitepress) {
            $language_details = $sitepress->get_language_details(ICL_LANGUAGE_CODE);
            $language_info['name'] = $language_details['display_name'];
            $language_info['is_rtl'] = $sitepress->is_rtl(ICL_LANGUAGE_CODE);
        }
    }
    
    // Check for Polylang
    if (function_exists('pll_current_language') && function_exists('pll_languages_list')) {
        $language_info['code'] = pll_current_language();
        
        $languages = pll_languages_list(['fields' => '']);
        foreach ($languages as $language) {
            if ($language->slug === $language_info['code']) {
                $language_info['name'] = $language->name;
                $language_info['flag'] = $language->flag_url;
                $language_info['is_rtl'] = $language->is_rtl;
                break;
            }
        }
    }
    
    return $language_info;
}

/**
 * Get available languages
 *
 * @return array
 */
function aqualuxe_get_available_languages() {
    $languages = [];
    
    // Check for WPML
    if (defined('ICL_LANGUAGE_CODE') && function_exists('icl_get_languages')) {
        $wpml_languages = icl_get_languages('skip_missing=0');
        
        if (!empty($wpml_languages)) {
            foreach ($wpml_languages as $code => $language) {
                $languages[$code] = [
                    'code' => $code,
                    'name' => $language['native_name'],
                    'url' => $language['url'],
                    'flag' => $language['country_flag_url'],
                    'active' => $language['active'],
                ];
            }
        }
    }
    
    // Check for Polylang
    if (function_exists('pll_languages_list') && function_exists('pll_the_languages')) {
        $pll_languages = pll_languages_list(['fields' => '']);
        $current_language = pll_current_language();
        
        if (!empty($pll_languages)) {
            foreach ($pll_languages as $language) {
                $languages[$language->slug] = [
                    'code' => $language->slug,
                    'name' => $language->name,
                    'url' => $language->url,
                    'flag' => $language->flag_url,
                    'active' => ($language->slug === $current_language),
                ];
            }
        }
    }
    
    return $languages;
}

/**
 * Check if current language is RTL
 *
 * @return bool
 */
function aqualuxe_is_rtl_language() {
    $language_info = aqualuxe_get_current_language_info();
    return $language_info['is_rtl'];
}

/**
 * Add RTL class to body if current language is RTL
 *
 * @param array $classes Body classes
 * @return array
 */
function aqualuxe_rtl_body_class($classes) {
    if (aqualuxe_is_rtl_language()) {
        $classes[] = 'rtl';
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_rtl_body_class');

/**
 * Load RTL stylesheet if current language is RTL
 */
function aqualuxe_load_rtl_stylesheet() {
    if (aqualuxe_is_rtl_language()) {
        wp_enqueue_style('aqualuxe-rtl', get_template_directory_uri() . '/assets/dist/css/rtl.css', ['aqualuxe-main'], AQUALUXE_VERSION);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_load_rtl_stylesheet', 20);

/**
 * Add hreflang links to head
 */
function aqualuxe_add_hreflang_links() {
    $languages = aqualuxe_get_available_languages();
    
    if (!empty($languages)) {
        foreach ($languages as $language) {
            echo '<link rel="alternate" hreflang="' . esc_attr($language['code']) . '" href="' . esc_url($language['url']) . '" />' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_add_hreflang_links');

/**
 * Add language attributes to html tag
 *
 * @param string $output Language attributes
 * @return string
 */
function aqualuxe_language_attributes($output) {
    $language_info = aqualuxe_get_current_language_info();
    
    if ($language_info['is_rtl']) {
        $output .= ' dir="rtl"';
    }
    
    return $output;
}
add_filter('language_attributes', 'aqualuxe_language_attributes');

/**
 * Register theme for WPML String Translation
 */
function aqualuxe_wpml_register_strings() {
    if (function_exists('icl_register_string')) {
        // Theme options
        $options = [
            'copyright_text' => __('Copyright Text', 'aqualuxe'),
            'newsletter_title' => __('Newsletter Title', 'aqualuxe'),
            'newsletter_description' => __('Newsletter Description', 'aqualuxe'),
            'newsletter_button_text' => __('Newsletter Button Text', 'aqualuxe'),
        ];
        
        foreach ($options as $option => $name) {
            $value = aqualuxe_get_option($option, '');
            if ($value) {
                icl_register_string('AquaLuxe Theme', 'aqualuxe_' . $option, $value);
            }
        }
        
        // Footer text
        $copyright_text = aqualuxe_get_option('copyright_text', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . __('All rights reserved.', 'aqualuxe'));
        icl_register_string('AquaLuxe Theme', 'aqualuxe_copyright', $copyright_text);
        
        // Contact information
        $contact_fields = [
            'contact_address' => __('Address', 'aqualuxe'),
            'contact_phone' => __('Phone', 'aqualuxe'),
            'contact_email' => __('Email', 'aqualuxe'),
            'contact_hours' => __('Working Hours', 'aqualuxe'),
        ];
        
        foreach ($contact_fields as $field => $name) {
            $value = aqualuxe_get_option($field, '');
            if ($value) {
                icl_register_string('AquaLuxe Theme', 'aqualuxe_' . $field, $value);
            }
        }
        
        // Custom labels
        $custom_labels = [
            'search_placeholder' => __('Search placeholder', 'aqualuxe'),
            'read_more_text' => __('Read more text', 'aqualuxe'),
            'related_posts_title' => __('Related posts title', 'aqualuxe'),
        ];
        
        foreach ($custom_labels as $label => $name) {
            $value = aqualuxe_get_option($label, '');
            if ($value) {
                icl_register_string('AquaLuxe Theme', 'aqualuxe_' . $label, $value);
            }
        }
    }
}
add_action('after_setup_theme', 'aqualuxe_wpml_register_strings');

/**
 * Filter menu locations for WPML
 */
function aqualuxe_wpml_nav_menu_args($args) {
    if (function_exists('icl_object_id') && isset($args['theme_location'])) {
        $theme_location = $args['theme_location'];
        $locations = get_nav_menu_locations();
        
        if (isset($locations[$theme_location])) {
            $menu_id = $locations[$theme_location];
            $menu_id = icl_object_id($menu_id, 'nav_menu');
            
            if ($menu_id) {
                $args['menu'] = $menu_id;
            }
        }
    }
    
    return $args;
}
add_filter('wp_nav_menu_args', 'aqualuxe_wpml_nav_menu_args');

/**
 * Add language code to body class
 *
 * @param array $classes Body classes
 * @return array
 */
function aqualuxe_language_body_class($classes) {
    $language_info = aqualuxe_get_current_language_info();
    $classes[] = 'lang-' . $language_info['code'];
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_language_body_class');

/**
 * Add multilingual support to customizer
 *
 * @param WP_Customize_Manager $wp_customize Customizer object
 */
function aqualuxe_multilingual_customizer($wp_customize) {
    // Get available languages
    $languages = aqualuxe_get_available_languages();
    
    if (count($languages) <= 1) {
        return;
    }
    
    // Add language switcher to customizer
    $wp_customize->add_section('aqualuxe_multilingual', [
        'title' => __('Multilingual Settings', 'aqualuxe'),
        'priority' => 30,
    ]);
    
    // Language switcher position
    $wp_customize->add_setting('aqualuxe_options[language_switcher_in_menu]', [
        'default' => false,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_language_switcher_in_menu', [
        'label' => __('Show language switcher in main menu', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'settings' => 'aqualuxe_options[language_switcher_in_menu]',
        'type' => 'checkbox',
    ]);
    
    $wp_customize->add_setting('aqualuxe_options[language_switcher_in_mobile_menu]', [
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_language_switcher_in_mobile_menu', [
        'label' => __('Show language switcher in mobile menu', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'settings' => 'aqualuxe_options[language_switcher_in_mobile_menu]',
        'type' => 'checkbox',
    ]);
    
    $wp_customize->add_setting('aqualuxe_options[language_switcher_in_footer]', [
        'default' => false,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_language_switcher_in_footer', [
        'label' => __('Show language switcher in footer', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'settings' => 'aqualuxe_options[language_switcher_in_footer]',
        'type' => 'checkbox',
    ]);
    
    // Language switcher style
    $wp_customize->add_setting('aqualuxe_options[language_switcher_style]', [
        'default' => 'dropdown',
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ]);
    
    $wp_customize->add_control('aqualuxe_language_switcher_style', [
        'label' => __('Language switcher style', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'settings' => 'aqualuxe_options[language_switcher_style]',
        'type' => 'select',
        'choices' => [
            'dropdown' => __('Dropdown', 'aqualuxe'),
            'list' => __('Horizontal list', 'aqualuxe'),
            'flags' => __('Flags only', 'aqualuxe'),
        ],
    ]);
    
    // Show flags
    $wp_customize->add_setting('aqualuxe_options[language_switcher_show_flags]', [
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_language_switcher_show_flags', [
        'label' => __('Show language flags', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'settings' => 'aqualuxe_options[language_switcher_show_flags]',
        'type' => 'checkbox',
    ]);
    
    // Show language names
    $wp_customize->add_setting('aqualuxe_options[language_switcher_show_names]', [
        'default' => true,
        'type' => 'option',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ]);
    
    $wp_customize->add_control('aqualuxe_language_switcher_show_names', [
        'label' => __('Show language names', 'aqualuxe'),
        'section' => 'aqualuxe_multilingual',
        'settings' => 'aqualuxe_options[language_switcher_show_names]',
        'type' => 'checkbox',
    ]);
}
add_action('customize_register', 'aqualuxe_multilingual_customizer');

/**
 * Sanitize checkbox
 *
 * @param bool $input Checkbox value
 * @return bool
 */
function aqualuxe_sanitize_checkbox($input) {
    return (isset($input) && true == $input) ? true : false;
}

/**
 * Sanitize select
 *
 * @param string $input Select value
 * @param WP_Customize_Setting $setting Setting object
 * @return string
 */
function aqualuxe_sanitize_select($input, $setting) {
    // Get the list of choices from the control associated with the setting
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    // Return input if valid or return default if not
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}