<?php
/**
 * Multilingual module functions
 *
 * @package AquaLuxe
 * @subpackage Modules
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get current language
 *
 * @return string Current language code
 */
function aqualuxe_multilingual_get_current_language() {
    $language = 'en';
    
    // Check if WPML is active
    if (defined('ICL_LANGUAGE_CODE')) {
        $language = ICL_LANGUAGE_CODE;
    }
    
    // Check if Polylang is active
    if (function_exists('pll_current_language')) {
        $current = pll_current_language();
        if ($current) {
            $language = $current;
        }
    }
    
    // Check for custom language implementation
    $custom_language = aqualuxe_get_module_option('multilingual', 'current_language', '');
    if ($custom_language) {
        $language = $custom_language;
    }
    
    return apply_filters('aqualuxe_multilingual_current_language', $language);
}

/**
 * Get default language
 *
 * @return string Default language code
 */
function aqualuxe_multilingual_get_default_language() {
    $language = 'en';
    
    // Check if WPML is active
    if (defined('ICL_LANGUAGE_CODE')) {
        $language = apply_filters('wpml_default_language', 'en');
    }
    
    // Check if Polylang is active
    if (function_exists('pll_default_language')) {
        $default = pll_default_language();
        if ($default) {
            $language = $default;
        }
    }
    
    // Check for custom language implementation
    $custom_language = aqualuxe_get_module_option('multilingual', 'default_language', '');
    if ($custom_language) {
        $language = $custom_language;
    }
    
    return apply_filters('aqualuxe_multilingual_default_language', $language);
}

/**
 * Get available languages
 *
 * @return array List of available languages
 */
function aqualuxe_multilingual_get_languages() {
    $languages = array(
        'en' => array(
            'code' => 'en',
            'name' => __('English', 'aqualuxe'),
            'native_name' => 'English',
            'flag' => get_template_directory_uri() . '/modules/multilingual/assets/images/flags/en.png',
            'url' => home_url(),
        ),
    );
    
    // Check if WPML is active
    if (function_exists('icl_get_languages')) {
        $wpml_languages = icl_get_languages('skip_missing=0');
        
        if (!empty($wpml_languages)) {
            $languages = array();
            
            foreach ($wpml_languages as $code => $language) {
                $languages[$code] = array(
                    'code' => $code,
                    'name' => $language['translated_name'],
                    'native_name' => $language['native_name'],
                    'flag' => $language['country_flag_url'],
                    'url' => $language['url'],
                );
            }
        }
    }
    
    // Check if Polylang is active
    if (function_exists('pll_the_languages')) {
        $pll_languages = pll_the_languages(array('raw' => 1));
        
        if (!empty($pll_languages)) {
            $languages = array();
            
            foreach ($pll_languages as $code => $language) {
                $languages[$code] = array(
                    'code' => $code,
                    'name' => $language['name'],
                    'native_name' => $language['name'],
                    'flag' => $language['flag'],
                    'url' => $language['url'],
                );
            }
        }
    }
    
    // Check for custom language implementation
    $custom_languages = aqualuxe_get_module_option('multilingual', 'languages', array());
    if (!empty($custom_languages)) {
        $languages = $custom_languages;
    }
    
    return apply_filters('aqualuxe_multilingual_languages', $languages);
}

/**
 * Get language switcher
 *
 * @param array $args Language switcher arguments
 * @return string Language switcher HTML
 */
function aqualuxe_multilingual_get_language_switcher($args = array()) {
    $defaults = array(
        'show_flags' => true,
        'show_names' => true,
        'dropdown' => true,
        'echo' => false,
    );
    
    $args = wp_parse_args($args, $defaults);
    $languages = aqualuxe_multilingual_get_languages();
    $current_language = aqualuxe_multilingual_get_current_language();
    
    // If only one language, don't show switcher
    if (count($languages) <= 1) {
        return '';
    }
    
    $output = '';
    
    if ($args['dropdown']) {
        $output .= '<div class="language-switcher dropdown">';
        $output .= '<button class="language-switcher-toggle" aria-expanded="false">';
        
        if (isset($languages[$current_language])) {
            if ($args['show_flags'] && !empty($languages[$current_language]['flag'])) {
                $output .= '<img src="' . esc_url($languages[$current_language]['flag']) . '" alt="' . esc_attr($languages[$current_language]['name']) . '" width="18" height="12">';
            }
            
            if ($args['show_names']) {
                $output .= '<span class="language-name">' . esc_html($languages[$current_language]['name']) . '</span>';
            }
        }
        
        $output .= '<span class="dropdown-arrow"></span>';
        $output .= '</button>';
        
        $output .= '<ul class="language-switcher-dropdown">';
        
        foreach ($languages as $code => $language) {
            $active_class = ($code === $current_language) ? ' active' : '';
            
            $output .= '<li class="language-item' . $active_class . '">';
            $output .= '<a href="' . esc_url($language['url']) . '" lang="' . esc_attr($code) . '">';
            
            if ($args['show_flags'] && !empty($language['flag'])) {
                $output .= '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" width="18" height="12">';
            }
            
            if ($args['show_names']) {
                $output .= '<span class="language-name">' . esc_html($language['name']) . '</span>';
            }
            
            $output .= '</a>';
            $output .= '</li>';
        }
        
        $output .= '</ul>';
        $output .= '</div>';
    } else {
        $output .= '<ul class="language-switcher-list">';
        
        foreach ($languages as $code => $language) {
            $active_class = ($code === $current_language) ? ' active' : '';
            
            $output .= '<li class="language-item' . $active_class . '">';
            $output .= '<a href="' . esc_url($language['url']) . '" lang="' . esc_attr($code) . '">';
            
            if ($args['show_flags'] && !empty($language['flag'])) {
                $output .= '<img src="' . esc_url($language['flag']) . '" alt="' . esc_attr($language['name']) . '" width="18" height="12">';
            }
            
            if ($args['show_names']) {
                $output .= '<span class="language-name">' . esc_html($language['name']) . '</span>';
            }
            
            $output .= '</a>';
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
 * Display language switcher
 *
 * @param array $args Language switcher arguments
 */
function aqualuxe_multilingual_language_switcher($args = array()) {
    echo aqualuxe_multilingual_get_language_switcher($args);
}

/**
 * Get translated string
 *
 * @param string $string String to translate
 * @param string $domain Text domain
 * @param string $language Language code (optional)
 * @return string Translated string
 */
function aqualuxe_multilingual_translate($string, $domain = 'aqualuxe', $language = '') {
    if (empty($language)) {
        $language = aqualuxe_multilingual_get_current_language();
    }
    
    // Check if WPML is active
    if (function_exists('icl_t')) {
        return icl_t($domain, $string, $string);
    }
    
    // Check if Polylang is active
    if (function_exists('pll__')) {
        return pll__($string);
    }
    
    // Check for custom translations
    $translations = aqualuxe_get_module_option('multilingual', 'translations', array());
    
    if (isset($translations[$language][$domain][$string])) {
        return $translations[$language][$domain][$string];
    }
    
    return $string;
}

/**
 * Register string for translation
 *
 * @param string $string String to register
 * @param string $name String name
 * @param string $domain Text domain
 */
function aqualuxe_multilingual_register_string($string, $name, $domain = 'aqualuxe') {
    // Check if WPML is active
    if (function_exists('icl_register_string')) {
        icl_register_string($domain, $name, $string);
    }
    
    // Check if Polylang is active
    if (function_exists('pll_register_string')) {
        pll_register_string($name, $string, $domain);
    }
    
    // Register for custom translation system
    $registered_strings = get_option('aqualuxe_multilingual_strings', array());
    
    if (!isset($registered_strings[$domain])) {
        $registered_strings[$domain] = array();
    }
    
    $registered_strings[$domain][$name] = $string;
    
    update_option('aqualuxe_multilingual_strings', $registered_strings);
}

/**
 * Get translated post ID
 *
 * @param int $post_id Post ID
 * @param string $post_type Post type
 * @param string $language Language code (optional)
 * @return int Translated post ID
 */
function aqualuxe_multilingual_get_translated_post_id($post_id, $post_type, $language = '') {
    if (empty($language)) {
        $language = aqualuxe_multilingual_get_current_language();
    }
    
    // Check if WPML is active
    if (function_exists('icl_object_id')) {
        return icl_object_id($post_id, $post_type, true, $language);
    }
    
    // Check if Polylang is active
    if (function_exists('pll_get_post')) {
        $translated_id = pll_get_post($post_id, $language);
        return $translated_id ? $translated_id : $post_id;
    }
    
    return $post_id;
}

/**
 * Get translated term ID
 *
 * @param int $term_id Term ID
 * @param string $taxonomy Taxonomy
 * @param string $language Language code (optional)
 * @return int Translated term ID
 */
function aqualuxe_multilingual_get_translated_term_id($term_id, $taxonomy, $language = '') {
    if (empty($language)) {
        $language = aqualuxe_multilingual_get_current_language();
    }
    
    // Check if WPML is active
    if (function_exists('icl_object_id')) {
        return icl_object_id($term_id, $taxonomy, true, $language);
    }
    
    // Check if Polylang is active
    if (function_exists('pll_get_term')) {
        $translated_id = pll_get_term($term_id, $language);
        return $translated_id ? $translated_id : $term_id;
    }
    
    return $term_id;
}

/**
 * Get language direction
 *
 * @param string $language Language code (optional)
 * @return string Language direction (ltr or rtl)
 */
function aqualuxe_multilingual_get_language_direction($language = '') {
    if (empty($language)) {
        $language = aqualuxe_multilingual_get_current_language();
    }
    
    $rtl_languages = array('ar', 'he', 'fa', 'ur');
    
    // Check if WPML is active
    if (defined('ICL_LANGUAGE_CODE') && function_exists('icl_get_languages')) {
        $languages = icl_get_languages('skip_missing=0');
        
        if (isset($languages[$language]['direction'])) {
            return $languages[$language]['direction'];
        }
    }
    
    // Check if language is RTL
    if (in_array($language, $rtl_languages)) {
        return 'rtl';
    }
    
    return 'ltr';
}

/**
 * Check if current language is RTL
 *
 * @return bool True if current language is RTL
 */
function aqualuxe_multilingual_is_rtl() {
    return aqualuxe_multilingual_get_language_direction() === 'rtl';
}

/**
 * Get language text direction HTML attribute
 *
 * @param string $language Language code (optional)
 * @return string HTML dir attribute
 */
function aqualuxe_multilingual_get_language_direction_attr($language = '') {
    return 'dir="' . aqualuxe_multilingual_get_language_direction($language) . '"';
}

/**
 * Get language HTML attributes
 *
 * @param string $language Language code (optional)
 * @return string HTML language attributes
 */
function aqualuxe_multilingual_get_language_attributes($language = '') {
    if (empty($language)) {
        $language = aqualuxe_multilingual_get_current_language();
    }
    
    $direction = aqualuxe_multilingual_get_language_direction($language);
    
    return 'lang="' . esc_attr($language) . '" dir="' . esc_attr($direction) . '"';
}