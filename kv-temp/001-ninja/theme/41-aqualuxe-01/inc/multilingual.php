<?php
/**
 * AquaLuxe Multilingual Support
 *
 * @package AquaLuxe
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize multilingual support
 */
function aqualuxe_multilingual_init() {
    // Load theme textdomain
    load_theme_textdomain('aqualuxe', AQUALUXE_DIR . 'languages');
    
    // WPML compatibility
    add_action('after_setup_theme', 'aqualuxe_wpml_compatibility');
    
    // Polylang compatibility
    add_action('after_setup_theme', 'aqualuxe_polylang_compatibility');
    
    // Register strings for translation
    add_action('after_setup_theme', 'aqualuxe_register_strings_for_translation');
    
    // Filter menu locations for WPML
    add_filter('wpml_ls_language_switcher_slot', 'aqualuxe_wpml_ls_language_switcher_slot');
}
add_action('after_setup_theme', 'aqualuxe_multilingual_init');

/**
 * WPML compatibility
 */
function aqualuxe_wpml_compatibility() {
    // Check if WPML is active
    if (defined('ICL_SITEPRESS_VERSION')) {
        // Add WPML language switcher styles
        add_action('wp_enqueue_scripts', 'aqualuxe_wpml_styles');
        
        // Add WPML language switcher to header
        if (aqualuxe_get_option('aqualuxe_header_show_language_switcher', true)) {
            add_action('aqualuxe_header_extras', 'aqualuxe_wpml_language_switcher');
        }
        
        // Add WPML language switcher to footer
        add_action('aqualuxe_footer_extras', 'aqualuxe_wpml_language_switcher_footer');
        
        // Add WPML language switcher to mobile menu
        add_action('aqualuxe_mobile_menu_extras', 'aqualuxe_wpml_language_switcher_mobile');
    }
}

/**
 * Polylang compatibility
 */
function aqualuxe_polylang_compatibility() {
    // Check if Polylang is active
    if (function_exists('pll_current_language')) {
        // Add Polylang language switcher to header
        if (aqualuxe_get_option('aqualuxe_header_show_language_switcher', true)) {
            add_action('aqualuxe_header_extras', 'aqualuxe_polylang_language_switcher');
        }
        
        // Add Polylang language switcher to footer
        add_action('aqualuxe_footer_extras', 'aqualuxe_polylang_language_switcher_footer');
        
        // Add Polylang language switcher to mobile menu
        add_action('aqualuxe_mobile_menu_extras', 'aqualuxe_polylang_language_switcher_mobile');
    }
}

/**
 * Register strings for translation
 */
function aqualuxe_register_strings_for_translation() {
    // Check if WPML String Translation is active
    if (function_exists('icl_register_string')) {
        // Register theme options for translation
        $options = [
            'aqualuxe_footer_copyright' => aqualuxe_get_option('aqualuxe_footer_copyright', sprintf(
                /* translators: %1$s: Current year, %2$s: Site name */
                __('&copy; %1$s %2$s. All rights reserved.', 'aqualuxe'),
                date('Y'),
                get_bloginfo('name')
            )),
            'aqualuxe_blog_read_more_text' => aqualuxe_get_option('aqualuxe_blog_read_more_text', __('Read More', 'aqualuxe')),
        ];
        
        foreach ($options as $name => $value) {
            icl_register_string('AquaLuxe Theme', $name, $value);
        }
    }
    
    // Check if Polylang is active
    if (function_exists('pll_register_string')) {
        // Register theme options for translation
        $options = [
            'aqualuxe_footer_copyright' => aqualuxe_get_option('aqualuxe_footer_copyright', sprintf(
                /* translators: %1$s: Current year, %2$s: Site name */
                __('&copy; %1$s %2$s. All rights reserved.', 'aqualuxe'),
                date('Y'),
                get_bloginfo('name')
            )),
            'aqualuxe_blog_read_more_text' => aqualuxe_get_option('aqualuxe_blog_read_more_text', __('Read More', 'aqualuxe')),
        ];
        
        foreach ($options as $name => $value) {
            pll_register_string($name, $value, 'AquaLuxe Theme');
        }
    }
}

/**
 * WPML language switcher
 */
function aqualuxe_wpml_language_switcher() {
    // Check if WPML is active
    if (defined('ICL_SITEPRESS_VERSION')) {
        echo '<div class="header-language-switcher">';
        do_action('wpml_add_language_selector');
        echo '</div>';
    }
}

/**
 * WPML language switcher in footer
 */
function aqualuxe_wpml_language_switcher_footer() {
    // Check if WPML is active
    if (defined('ICL_SITEPRESS_VERSION')) {
        echo '<div class="footer-language-switcher">';
        do_action('wpml_add_language_selector');
        echo '</div>';
    }
}

/**
 * WPML language switcher in mobile menu
 */
function aqualuxe_wpml_language_switcher_mobile() {
    // Check if WPML is active
    if (defined('ICL_SITEPRESS_VERSION')) {
        echo '<div class="mobile-language-switcher">';
        do_action('wpml_add_language_selector');
        echo '</div>';
    }
}

/**
 * WPML language switcher styles
 */
function aqualuxe_wpml_styles() {
    // Check if WPML is active
    if (defined('ICL_SITEPRESS_VERSION')) {
        wp_enqueue_style('wpml-language-switcher', AQUALUXE_ASSETS_URI . 'css/wpml-language-switcher.css', [], AQUALUXE_VERSION);
    }
}

/**
 * Filter WPML language switcher slot
 *
 * @param array $slot Language switcher slot
 * @return array
 */
function aqualuxe_wpml_ls_language_switcher_slot($slot) {
    // Add custom classes to language switcher
    $slot['css_classes'] = 'wpml-ls-slot-aqualuxe ' . $slot['css_classes'];
    
    return $slot;
}

/**
 * Polylang language switcher
 */
function aqualuxe_polylang_language_switcher() {
    // Check if Polylang is active
    if (function_exists('pll_the_languages')) {
        echo '<div class="header-language-switcher">';
        pll_the_languages([
            'show_flags' => 1,
            'show_names' => 1,
            'dropdown' => 0,
        ]);
        echo '</div>';
    }
}

/**
 * Polylang language switcher in footer
 */
function aqualuxe_polylang_language_switcher_footer() {
    // Check if Polylang is active
    if (function_exists('pll_the_languages')) {
        echo '<div class="footer-language-switcher">';
        pll_the_languages([
            'show_flags' => 1,
            'show_names' => 1,
            'dropdown' => 0,
        ]);
        echo '</div>';
    }
}

/**
 * Polylang language switcher in mobile menu
 */
function aqualuxe_polylang_language_switcher_mobile() {
    // Check if Polylang is active
    if (function_exists('pll_the_languages')) {
        echo '<div class="mobile-language-switcher">';
        pll_the_languages([
            'show_flags' => 1,
            'show_names' => 1,
            'dropdown' => 0,
        ]);
        echo '</div>';
    }
}

/**
 * Get translated option
 *
 * @param string $option Option name
 * @param mixed $default Default value
 * @return mixed
 */
function aqualuxe_get_translated_option($option, $default = '') {
    $value = aqualuxe_get_option($option, $default);
    
    // Check if WPML String Translation is active
    if (function_exists('icl_t')) {
        $value = icl_t('AquaLuxe Theme', $option, $value);
    }
    
    // Check if Polylang is active
    if (function_exists('pll__')) {
        $value = pll__($value);
    }
    
    return $value;
}

/**
 * Get current language
 *
 * @return string
 */
function aqualuxe_get_current_language() {
    // WPML
    if (defined('ICL_LANGUAGE_CODE')) {
        return ICL_LANGUAGE_CODE;
    }
    
    // Polylang
    if (function_exists('pll_current_language')) {
        return pll_current_language();
    }
    
    // Default
    return get_locale();
}

/**
 * Get languages
 *
 * @return array
 */
function aqualuxe_get_languages() {
    $languages = [];
    
    // WPML
    if (function_exists('icl_get_languages')) {
        $languages = icl_get_languages('skip_missing=0');
    }
    
    // Polylang
    elseif (function_exists('pll_languages_list')) {
        $langs = pll_languages_list(['fields' => '']);
        
        foreach ($langs as $lang) {
            $languages[$lang->slug] = [
                'code' => $lang->slug,
                'id' => $lang->term_id,
                'native_name' => $lang->name,
                'translated_name' => $lang->name,
                'country_flag_url' => $lang->flag_url,
                'active' => $lang->slug === pll_current_language(),
                'url' => $lang->url,
            ];
        }
    }
    
    return $languages;
}

/**
 * Get language switcher
 *
 * @param array $args Language switcher arguments
 * @return string
 */
function aqualuxe_get_language_switcher($args = []) {
    // Set defaults
    $defaults = [
        'container' => 'div',
        'container_class' => 'language-switcher',
        'echo' => false,
    ];
    
    // Parse args
    $args = wp_parse_args($args, $defaults);
    
    // Get languages
    $languages = aqualuxe_get_languages();
    
    if (empty($languages)) {
        return '';
    }
    
    // Build output
    $output = '';
    
    $container_class = $args['container_class'] ? ' class="' . esc_attr($args['container_class']) . '"' : '';
    
    $output = '<' . esc_html($args['container']) . $container_class . '>';
    $output .= '<ul class="language-switcher-list">';
    
    foreach ($languages as $language) {
        $class = $language['active'] ? ' class="language-switcher-item active"' : ' class="language-switcher-item"';
        
        $output .= '<li' . $class . '>';
        $output .= '<a href="' . esc_url($language['url']) . '" lang="' . esc_attr($language['code']) . '">';
        
        if (isset($language['country_flag_url']) && $language['country_flag_url']) {
            $output .= '<img src="' . esc_url($language['country_flag_url']) . '" alt="' . esc_attr($language['code']) . '" width="18" height="12">';
        }
        
        $output .= esc_html($language['native_name']);
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</' . esc_html($args['container']) . '>';
    
    if ($args['echo']) {
        echo $output;
    }
    
    return $output;
}