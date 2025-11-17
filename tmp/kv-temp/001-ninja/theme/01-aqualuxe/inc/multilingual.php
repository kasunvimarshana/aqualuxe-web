<?php
/**
 * Multilingual Support for AquaLuxe Theme
 *
 * This file contains functions and hooks for multilingual support.
 * It's designed to work with WPML and Polylang plugins.
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Check if a multilingual plugin is active
 *
 * @return bool True if a supported multilingual plugin is active
 */
function aqualuxe_is_multilingual() {
    // Check for WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        return true;
    }
    
    // Check for Polylang
    if (function_exists('pll_current_language')) {
        return true;
    }
    
    return false;
}

/**
 * Get current language code
 *
 * @return string Current language code
 */
function aqualuxe_get_current_language() {
    // WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        return apply_filters('wpml_current_language', null);
    }
    
    // Polylang
    if (function_exists('pll_current_language')) {
        return pll_current_language();
    }
    
    // Default to WordPress locale
    return substr(get_locale(), 0, 2);
}

/**
 * Get language name from code
 *
 * @param string $code Language code
 * @return string Language name
 */
function aqualuxe_get_language_name($code) {
    // WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        global $sitepress;
        $languages = $sitepress->get_active_languages();
        return isset($languages[$code]['display_name']) ? $languages[$code]['display_name'] : $code;
    }
    
    // Polylang
    if (function_exists('pll_languages_list')) {
        $languages = pll_languages_list(array('fields' => 'name'));
        $codes = pll_languages_list();
        $index = array_search($code, $codes);
        return ($index !== false && isset($languages[$index])) ? $languages[$index] : $code;
    }
    
    return $code;
}

/**
 * Get available languages
 *
 * @return array Array of language codes and names
 */
function aqualuxe_get_languages() {
    $languages = array();
    
    // WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        global $sitepress;
        $wpml_languages = $sitepress->get_active_languages();
        
        foreach ($wpml_languages as $code => $language) {
            $languages[$code] = array(
                'code' => $code,
                'name' => $language['display_name'],
                'native_name' => $language['native_name'],
                'flag' => $sitepress->get_flag_url($code),
                'url' => apply_filters('wpml_permalink', home_url('/'), $code),
                'active' => (ICL_LANGUAGE_CODE === $code),
            );
        }
        
        return $languages;
    }
    
    // Polylang
    if (function_exists('pll_languages_list')) {
        $codes = pll_languages_list();
        $names = pll_languages_list(array('fields' => 'name'));
        $flags = pll_languages_list(array('fields' => 'flag'));
        $urls = pll_languages_list(array('fields' => 'url'));
        $current = pll_current_language();
        
        foreach ($codes as $index => $code) {
            $languages[$code] = array(
                'code' => $code,
                'name' => isset($names[$index]) ? $names[$index] : $code,
                'native_name' => isset($names[$index]) ? $names[$index] : $code,
                'flag' => isset($flags[$index]) ? $flags[$index] : '',
                'url' => isset($urls[$index]) ? $urls[$index] : home_url('/'),
                'active' => ($current === $code),
            );
        }
        
        return $languages;
    }
    
    // Default language only
    $languages[substr(get_locale(), 0, 2)] = array(
        'code' => substr(get_locale(), 0, 2),
        'name' => get_locale(),
        'native_name' => get_locale(),
        'flag' => '',
        'url' => home_url('/'),
        'active' => true,
    );
    
    return $languages;
}

/**
 * Get translated post ID
 *
 * @param int    $post_id   Post ID
 * @param string $lang_code Language code
 * @return int Translated post ID
 */
function aqualuxe_get_translated_post_id($post_id, $lang_code = '') {
    if (empty($lang_code)) {
        $lang_code = aqualuxe_get_current_language();
    }
    
    // WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        return apply_filters('wpml_object_id', $post_id, get_post_type($post_id), true, $lang_code);
    }
    
    // Polylang
    if (function_exists('pll_get_post')) {
        $translation = pll_get_post($post_id, $lang_code);
        return $translation ? $translation : $post_id;
    }
    
    return $post_id;
}

/**
 * Get translated term ID
 *
 * @param int    $term_id   Term ID
 * @param string $taxonomy  Taxonomy name
 * @param string $lang_code Language code
 * @return int Translated term ID
 */
function aqualuxe_get_translated_term_id($term_id, $taxonomy, $lang_code = '') {
    if (empty($lang_code)) {
        $lang_code = aqualuxe_get_current_language();
    }
    
    // WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        return apply_filters('wpml_object_id', $term_id, $taxonomy, true, $lang_code);
    }
    
    // Polylang
    if (function_exists('pll_get_term')) {
        $translation = pll_get_term($term_id, $lang_code);
        return $translation ? $translation : $term_id;
    }
    
    return $term_id;
}

/**
 * Register strings for translation
 *
 * @param string $context Context for the strings
 * @param array  $strings Array of strings to register
 * @return void
 */
function aqualuxe_register_strings($context, $strings) {
    // WPML String Translation
    if (function_exists('icl_register_string')) {
        foreach ($strings as $name => $value) {
            icl_register_string($context, $name, $value);
        }
    }
    
    // Polylang
    if (function_exists('pll_register_string')) {
        foreach ($strings as $name => $value) {
            pll_register_string($name, $value, $context);
        }
    }
}

/**
 * Translate registered string
 *
 * @param string $string  String to translate
 * @param string $name    Name of the string
 * @param string $context Context for the string
 * @return string Translated string
 */
function aqualuxe_translate_string($string, $name, $context = 'AquaLuxe') {
    // WPML String Translation
    if (function_exists('icl_t')) {
        return icl_t($context, $name, $string);
    }
    
    // Polylang
    if (function_exists('pll__')) {
        return pll__($string);
    }
    
    return $string;
}

/**
 * Display language switcher
 *
 * @param array $args Arguments for the language switcher
 * @return void
 */
function aqualuxe_language_switcher($args = array()) {
    $defaults = array(
        'show_flags' => true,
        'show_names' => true,
        'dropdown' => false,
        'echo' => true,
    );
    
    $args = wp_parse_args($args, $defaults);
    $languages = aqualuxe_get_languages();
    $current_language = aqualuxe_get_current_language();
    
    if (empty($languages)) {
        return '';
    }
    
    $output = '';
    
    if ($args['dropdown']) {
        $output .= '<div class="language-switcher-dropdown relative" x-data="{ open: false }">';
        $output .= '<button @click="open = !open" @click.away="open = false" class="language-switcher-button flex items-center space-x-2 focus:outline-none">';
        
        if ($args['show_flags'] && !empty($languages[$current_language]['flag'])) {
            $output .= '<img src="' . esc_url($languages[$current_language]['flag']) . '" alt="' . esc_attr($languages[$current_language]['name']) . '" class="w-5 h-auto" />';
        }
        
        if ($args['show_names']) {
            $output .= '<span>' . esc_html($languages[$current_language]['name']) . '</span>';
        }
        
        $output .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>';
        $output .= '</button>';
        
        $output .= '<div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="language-switcher-dropdown-menu absolute right-0 mt-2 py-2 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg z-50">';
        
        foreach ($languages as $code => $language) {
            if ($language['active']) {
                continue;
            }
            
            $output .= '<a href="' . esc_url($language['url']) . '" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 flex items-center">';
            
            if ($args['show_flags'] && !empty($language['flag'])) {
                $output .= '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" class="w-5 h-auto mr-2" />';
            }
            
            if ($args['show_names']) {
                $output .= esc_html($language['name']);
            }
            
            $output .= '</a>';
        }
        
        $output .= '</div>';
        $output .= '</div>';
    } else {
        $output .= '<ul class="language-switcher-list flex space-x-4">';
        
        foreach ($languages as $code => $language) {
            $item_class = $language['active'] ? 'language-item active' : 'language-item';
            $link_class = $language['active'] ? 'text-primary-600 dark:text-primary-400 font-medium' : 'hover:text-primary-600 dark:hover:text-primary-400';
            
            $output .= '<li class="' . esc_attr($item_class) . '">';
            
            if (!$language['active']) {
                $output .= '<a href="' . esc_url($language['url']) . '" class="' . esc_attr($link_class) . ' flex items-center">';
            } else {
                $output .= '<span class="' . esc_attr($link_class) . ' flex items-center">';
            }
            
            if ($args['show_flags'] && !empty($language['flag'])) {
                $output .= '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" class="w-5 h-auto mr-2" />';
            }
            
            if ($args['show_names']) {
                $output .= esc_html($language['name']);
            }
            
            if (!$language['active']) {
                $output .= '</a>';
            } else {
                $output .= '</span>';
            }
            
            $output .= '</li>';
        }
        
        $output .= '</ul>';
    }
    
    if ($args['echo']) {
        echo $output;
    }
    
    return $output;
}

/**
 * Register theme strings for translation
 */
function aqualuxe_register_theme_strings() {
    // Only register strings if a multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    // Theme options strings
    $strings = array(
        'footer_copyright' => get_theme_mod('aqualuxe_copyright_text', '© {year} {site_name}. All rights reserved.'),
        'footer_about' => get_theme_mod('aqualuxe_footer_about', 'Premium aquarium products and services for fish enthusiasts around the world.'),
        'newsletter_title' => get_theme_mod('aqualuxe_newsletter_title', 'Subscribe to Our Newsletter'),
        'newsletter_text' => get_theme_mod('aqualuxe_newsletter_text', 'Stay updated with our latest products, special offers, and aquarium care tips.'),
        'shipping_text' => get_theme_mod('aqualuxe_shipping_text', 'We ship worldwide with express and standard options available.'),
        'blog_title' => get_theme_mod('aqualuxe_blog_title', 'Blog'),
        'blog_description' => get_theme_mod('aqualuxe_blog_description', ''),
    );
    
    aqualuxe_register_strings('AquaLuxe Theme Options', $strings);
}
add_action('after_setup_theme', 'aqualuxe_register_theme_strings');

/**
 * Add language switcher to top bar
 */
function aqualuxe_add_language_switcher_to_topbar() {
    // Only add language switcher if a multilingual plugin is active
    if (!aqualuxe_is_multilingual() || !get_theme_mod('aqualuxe_enable_language_switcher', true)) {
        return;
    }
    
    echo '<div class="language-switcher ml-4">';
    aqualuxe_language_switcher(array(
        'show_flags' => get_theme_mod('aqualuxe_show_language_flags', true),
        'show_names' => get_theme_mod('aqualuxe_show_language_names', true),
        'dropdown' => get_theme_mod('aqualuxe_language_switcher_dropdown', true),
    ));
    echo '</div>';
}
add_action('aqualuxe_top_bar_right', 'aqualuxe_add_language_switcher_to_topbar', 20);

/**
 * Add language switcher to mobile menu
 */
function aqualuxe_add_language_switcher_to_mobile_menu() {
    // Only add language switcher if a multilingual plugin is active
    if (!aqualuxe_is_multilingual() || !get_theme_mod('aqualuxe_enable_language_switcher_mobile', true)) {
        return;
    }
    
    echo '<div class="language-switcher-mobile mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">';
    echo '<h4 class="text-sm font-medium mb-2">' . esc_html__('Language', 'aqualuxe') . '</h4>';
    aqualuxe_language_switcher(array(
        'show_flags' => true,
        'show_names' => true,
        'dropdown' => false,
    ));
    echo '</div>';
}
add_action('aqualuxe_after_mobile_menu', 'aqualuxe_add_language_switcher_to_mobile_menu');

/**
 * Add multilingual support to customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_multilingual_customizer($wp_customize) {
    // Only add multilingual settings if a multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    // Add Multilingual section
    $wp_customize->add_section('aqualuxe_multilingual', array(
        'title'    => __('Multilingual Settings', 'aqualuxe'),
        'priority' => 90,
    ));
    
    // Language Switcher Enable
    $wp_customize->add_setting('aqualuxe_enable_language_switcher', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_enable_language_switcher', array(
        'label'    => __('Show Language Switcher in Top Bar', 'aqualuxe'),
        'section'  => 'aqualuxe_multilingual',
        'type'     => 'checkbox',
    ));
    
    // Language Switcher Mobile
    $wp_customize->add_setting('aqualuxe_enable_language_switcher_mobile', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_enable_language_switcher_mobile', array(
        'label'    => __('Show Language Switcher in Mobile Menu', 'aqualuxe'),
        'section'  => 'aqualuxe_multilingual',
        'type'     => 'checkbox',
    ));
    
    // Show Language Flags
    $wp_customize->add_setting('aqualuxe_show_language_flags', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_language_flags', array(
        'label'    => __('Show Language Flags', 'aqualuxe'),
        'section'  => 'aqualuxe_multilingual',
        'type'     => 'checkbox',
    ));
    
    // Show Language Names
    $wp_customize->add_setting('aqualuxe_show_language_names', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_show_language_names', array(
        'label'    => __('Show Language Names', 'aqualuxe'),
        'section'  => 'aqualuxe_multilingual',
        'type'     => 'checkbox',
    ));
    
    // Dropdown Language Switcher
    $wp_customize->add_setting('aqualuxe_language_switcher_dropdown', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_language_switcher_dropdown', array(
        'label'    => __('Use Dropdown Language Switcher', 'aqualuxe'),
        'section'  => 'aqualuxe_multilingual',
        'type'     => 'checkbox',
    ));
}
add_action('customize_register', 'aqualuxe_multilingual_customizer');

/**
 * Add multilingual support to WooCommerce
 */
function aqualuxe_woocommerce_multilingual_support() {
    // Only add multilingual support if a multilingual plugin is active
    if (!aqualuxe_is_multilingual() || !class_exists('WooCommerce')) {
        return;
    }
    
    // Register WooCommerce strings for translation
    $strings = array(
        'shop_page_title' => get_theme_mod('aqualuxe_shop_title', 'Shop'),
        'shop_page_description' => get_theme_mod('aqualuxe_shop_description', ''),
        'cart_empty_text' => get_theme_mod('aqualuxe_cart_empty_text', 'Your cart is currently empty.'),
        'cart_empty_button_text' => get_theme_mod('aqualuxe_cart_empty_button_text', 'Return to Shop'),
    );
    
    aqualuxe_register_strings('AquaLuxe WooCommerce', $strings);
}
add_action('after_setup_theme', 'aqualuxe_woocommerce_multilingual_support');

/**
 * Add hreflang links to head
 */
function aqualuxe_add_hreflang_links() {
    // Only add hreflang links if a multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    $languages = aqualuxe_get_languages();
    
    foreach ($languages as $code => $language) {
        echo '<link rel="alternate" hreflang="' . esc_attr($code) . '" href="' . esc_url($language['url']) . '" />' . "\n";
    }
    
    // Add x-default hreflang
    $default_lang = defined('ICL_LANGUAGE_CODE') ? apply_filters('wpml_default_language', null) : '';
    if ($default_lang && isset($languages[$default_lang])) {
        echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($languages[$default_lang]['url']) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_hreflang_links');