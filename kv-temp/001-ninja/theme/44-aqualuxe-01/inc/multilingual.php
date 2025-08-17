<?php
/**
 * Multilingual support for AquaLuxe theme
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
    // Load text domain
    load_theme_textdomain('aqualuxe', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'aqualuxe_multilingual_init');

/**
 * Get current language code
 *
 * @return string Current language code
 */
function aqualuxe_get_current_language() {
    $language = 'en'; // Default language
    
    // Check if WPML is active
    if (aqualuxe_is_wpml_active()) {
        $language = apply_filters('wpml_current_language', null);
    }
    
    // Check if Polylang is active
    elseif (aqualuxe_is_polylang_active()) {
        $language = pll_current_language();
    }
    
    return $language;
}

/**
 * Get language switcher HTML
 *
 * @param array $args Arguments for the language switcher
 * @return string Language switcher HTML
 */
function aqualuxe_get_language_switcher($args = array()) {
    $defaults = array(
        'dropdown'      => false,
        'show_flags'    => true,
        'show_names'    => true,
        'display_names' => 'native',
        'classes'       => 'aqualuxe-language-switcher',
    );
    
    $args = wp_parse_args($args, $defaults);
    $output = '';
    
    // Check if WPML is active
    if (aqualuxe_is_wpml_active()) {
        $output = aqualuxe_get_wpml_language_switcher($args);
    }
    
    // Check if Polylang is active
    elseif (aqualuxe_is_polylang_active()) {
        $output = aqualuxe_get_polylang_language_switcher($args);
    }
    
    return $output;
}

/**
 * Get WPML language switcher HTML
 *
 * @param array $args Arguments for the language switcher
 * @return string Language switcher HTML
 */
function aqualuxe_get_wpml_language_switcher($args) {
    $output = '';
    
    if (function_exists('icl_get_languages')) {
        $languages = apply_filters('wpml_active_languages', null, array(
            'skip_missing' => 0,
        ));
        
        if (!empty($languages)) {
            $current_language = aqualuxe_get_current_language();
            
            if ($args['dropdown']) {
                $output .= '<div class="' . esc_attr($args['classes']) . ' dropdown">';
                $output .= '<div class="current-language dropdown-toggle" data-toggle="dropdown">';
                
                if (isset($languages[$current_language])) {
                    if ($args['show_flags']) {
                        $output .= '<img src="' . esc_url($languages[$current_language]['country_flag_url']) . '" alt="' . esc_attr($languages[$current_language]['language_code']) . '" width="18" height="12">';
                    }
                    
                    if ($args['show_names']) {
                        $output .= '<span>' . esc_html($languages[$current_language]['native_name']) . '</span>';
                    }
                    
                    $output .= '<i class="fas fa-chevron-down"></i>';
                }
                
                $output .= '</div>';
                $output .= '<ul class="dropdown-menu">';
                
                foreach ($languages as $language) {
                    $output .= '<li' . ($language['active'] ? ' class="active"' : '') . '>';
                    $output .= '<a href="' . esc_url($language['url']) . '">';
                    
                    if ($args['show_flags']) {
                        $output .= '<img src="' . esc_url($language['country_flag_url']) . '" alt="' . esc_attr($language['language_code']) . '" width="18" height="12">';
                    }
                    
                    if ($args['show_names']) {
                        if ($args['display_names'] === 'native') {
                            $output .= '<span>' . esc_html($language['native_name']) . '</span>';
                        } else {
                            $output .= '<span>' . esc_html($language['translated_name']) . '</span>';
                        }
                    }
                    
                    $output .= '</a>';
                    $output .= '</li>';
                }
                
                $output .= '</ul>';
                $output .= '</div>';
            } else {
                $output .= '<ul class="' . esc_attr($args['classes']) . '">';
                
                foreach ($languages as $language) {
                    $output .= '<li' . ($language['active'] ? ' class="active"' : '') . '>';
                    $output .= '<a href="' . esc_url($language['url']) . '">';
                    
                    if ($args['show_flags']) {
                        $output .= '<img src="' . esc_url($language['country_flag_url']) . '" alt="' . esc_attr($language['language_code']) . '" width="18" height="12">';
                    }
                    
                    if ($args['show_names']) {
                        if ($args['display_names'] === 'native') {
                            $output .= '<span>' . esc_html($language['native_name']) . '</span>';
                        } else {
                            $output .= '<span>' . esc_html($language['translated_name']) . '</span>';
                        }
                    }
                    
                    $output .= '</a>';
                    $output .= '</li>';
                }
                
                $output .= '</ul>';
            }
        }
    }
    
    return $output;
}

/**
 * Get Polylang language switcher HTML
 *
 * @param array $args Arguments for the language switcher
 * @return string Language switcher HTML
 */
function aqualuxe_get_polylang_language_switcher($args) {
    $output = '';
    
    if (function_exists('pll_the_languages')) {
        $languages = pll_the_languages(array(
            'raw'                => 1,
            'hide_if_empty'      => 0,
            'hide_if_no_translation' => 0,
            'hide_current'       => 0,
            'display_names_as'   => $args['display_names'],
            'show_flags'         => $args['show_flags'],
            'show_names'         => $args['show_names'],
        ));
        
        if (!empty($languages)) {
            $current_language = aqualuxe_get_current_language();
            
            if ($args['dropdown']) {
                $output .= '<div class="' . esc_attr($args['classes']) . ' dropdown">';
                $output .= '<div class="current-language dropdown-toggle" data-toggle="dropdown">';
                
                foreach ($languages as $language) {
                    if ($language['current_lang']) {
                        if ($args['show_flags'] && isset($language['flag'])) {
                            $output .= $language['flag'];
                        }
                        
                        if ($args['show_names']) {
                            $output .= '<span>' . esc_html($language['name']) . '</span>';
                        }
                        
                        $output .= '<i class="fas fa-chevron-down"></i>';
                        break;
                    }
                }
                
                $output .= '</div>';
                $output .= '<ul class="dropdown-menu">';
                
                foreach ($languages as $language) {
                    $output .= '<li' . ($language['current_lang'] ? ' class="active"' : '') . '>';
                    $output .= '<a href="' . esc_url($language['url']) . '">';
                    
                    if ($args['show_flags'] && isset($language['flag'])) {
                        $output .= $language['flag'];
                    }
                    
                    if ($args['show_names']) {
                        $output .= '<span>' . esc_html($language['name']) . '</span>';
                    }
                    
                    $output .= '</a>';
                    $output .= '</li>';
                }
                
                $output .= '</ul>';
                $output .= '</div>';
            } else {
                $output .= '<ul class="' . esc_attr($args['classes']) . '">';
                
                foreach ($languages as $language) {
                    $output .= '<li' . ($language['current_lang'] ? ' class="active"' : '') . '>';
                    $output .= '<a href="' . esc_url($language['url']) . '">';
                    
                    if ($args['show_flags'] && isset($language['flag'])) {
                        $output .= $language['flag'];
                    }
                    
                    if ($args['show_names']) {
                        $output .= '<span>' . esc_html($language['name']) . '</span>';
                    }
                    
                    $output .= '</a>';
                    $output .= '</li>';
                }
                
                $output .= '</ul>';
            }
        }
    }
    
    return $output;
}

/**
 * Get translated post ID
 *
 * @param int    $post_id   Post ID
 * @param string $language  Language code
 * @return int Translated post ID
 */
function aqualuxe_get_translated_post_id($post_id, $language = '') {
    $translated_post_id = $post_id;
    
    // If no language specified, use current language
    if (empty($language)) {
        $language = aqualuxe_get_current_language();
    }
    
    // Check if WPML is active
    if (aqualuxe_is_wpml_active()) {
        $translated_post_id = apply_filters('wpml_object_id', $post_id, get_post_type($post_id), true, $language);
    }
    
    // Check if Polylang is active
    elseif (aqualuxe_is_polylang_active() && function_exists('pll_get_post')) {
        $temp_id = pll_get_post($post_id, $language);
        if ($temp_id) {
            $translated_post_id = $temp_id;
        }
    }
    
    return $translated_post_id;
}

/**
 * Get translated term ID
 *
 * @param int    $term_id   Term ID
 * @param string $taxonomy  Taxonomy name
 * @param string $language  Language code
 * @return int Translated term ID
 */
function aqualuxe_get_translated_term_id($term_id, $taxonomy, $language = '') {
    $translated_term_id = $term_id;
    
    // If no language specified, use current language
    if (empty($language)) {
        $language = aqualuxe_get_current_language();
    }
    
    // Check if WPML is active
    if (aqualuxe_is_wpml_active()) {
        $translated_term_id = apply_filters('wpml_object_id', $term_id, $taxonomy, true, $language);
    }
    
    // Check if Polylang is active
    elseif (aqualuxe_is_polylang_active() && function_exists('pll_get_term')) {
        $temp_id = pll_get_term($term_id, $language);
        if ($temp_id) {
            $translated_term_id = $temp_id;
        }
    }
    
    return $translated_term_id;
}

/**
 * Register strings for translation
 *
 * @param string $context Context for the string
 * @param string $name    Name for the string
 * @param string $value   Value of the string
 */
function aqualuxe_register_string($context, $name, $value) {
    // Check if WPML is active
    if (aqualuxe_is_wpml_active() && function_exists('icl_register_string')) {
        icl_register_string($context, $name, $value);
    }
    
    // Check if Polylang is active
    elseif (aqualuxe_is_polylang_active() && function_exists('pll_register_string')) {
        pll_register_string($name, $value, $context);
    }
}

/**
 * Translate registered string
 *
 * @param string $context Context for the string
 * @param string $name    Name for the string
 * @param string $value   Value of the string
 * @return string Translated string
 */
function aqualuxe_translate_string($context, $name, $value) {
    // Check if WPML is active
    if (aqualuxe_is_wpml_active() && function_exists('icl_t')) {
        return icl_t($context, $name, $value);
    }
    
    // Check if Polylang is active
    elseif (aqualuxe_is_polylang_active() && function_exists('pll__')) {
        aqualuxe_register_string($context, $name, $value);
        return pll__($value);
    }
    
    return $value;
}

/**
 * Register theme strings for translation
 */
function aqualuxe_register_theme_strings() {
    // Register customizer strings
    $customizer_options = array(
        'aqualuxe_header_phone'        => __('Header Phone Number', 'aqualuxe'),
        'aqualuxe_header_email'        => __('Header Email Address', 'aqualuxe'),
        'aqualuxe_footer_copyright'    => __('Footer Copyright Text', 'aqualuxe'),
        'aqualuxe_footer_address'      => __('Footer Address', 'aqualuxe'),
        'aqualuxe_footer_phone'        => __('Footer Phone Number', 'aqualuxe'),
        'aqualuxe_footer_email'        => __('Footer Email Address', 'aqualuxe'),
    );
    
    foreach ($customizer_options as $option => $label) {
        $value = get_theme_mod($option, '');
        if (!empty($value)) {
            aqualuxe_register_string('Theme Customizer', $option, $value);
        }
    }
    
    // Register widget strings
    global $wp_registered_widgets;
    if (!empty($wp_registered_widgets)) {
        foreach ($wp_registered_widgets as $widget) {
            if (isset($widget['callback'][0]) && is_object($widget['callback'][0])) {
                $widget_obj = $widget['callback'][0];
                $widget_options = get_option($widget_obj->option_name);
                
                if (!empty($widget_options)) {
                    foreach ($widget_options as $instance_id => $instance) {
                        if (is_array($instance)) {
                            foreach ($instance as $field => $value) {
                                if (is_string($value) && !empty($value)) {
                                    aqualuxe_register_string('Widget', $widget_obj->id_base . '-' . $instance_id . '-' . $field, $value);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
add_action('after_setup_theme', 'aqualuxe_register_theme_strings');

/**
 * Add language switcher to main menu
 *
 * @param string $items Menu items HTML
 * @param object $args  Menu arguments
 * @return string Modified menu items HTML
 */
function aqualuxe_add_language_switcher_to_menu($items, $args) {
    // Check if multilingual is active
    if (!aqualuxe_is_multilingual_active()) {
        return $items;
    }
    
    // Check if this is the primary menu
    if ($args->theme_location === 'primary') {
        // Get language switcher
        $language_switcher = aqualuxe_get_language_switcher(array(
            'dropdown'   => true,
            'show_flags' => true,
            'show_names' => true,
            'classes'    => 'menu-item-language-switcher',
        ));
        
        // Add language switcher to menu
        if (!empty($language_switcher)) {
            $items .= '<li class="menu-item menu-item-language-switcher">' . $language_switcher . '</li>';
        }
    }
    
    return $items;
}
add_filter('wp_nav_menu_items', 'aqualuxe_add_language_switcher_to_menu', 10, 2);

/**
 * Add hreflang links to head
 */
function aqualuxe_add_hreflang_links() {
    // Check if multilingual is active
    if (!aqualuxe_is_multilingual_active()) {
        return;
    }
    
    // Check if WPML is active
    if (aqualuxe_is_wpml_active()) {
        // WPML adds hreflang links automatically
        return;
    }
    
    // Check if Polylang is active
    if (aqualuxe_is_polylang_active() && function_exists('pll_languages_list') && function_exists('pll_current_language')) {
        $languages = pll_languages_list(array('fields' => 'slug'));
        $current_language = pll_current_language();
        $post_id = get_the_ID();
        
        if (!$post_id) {
            return;
        }
        
        foreach ($languages as $language) {
            $url = get_permalink(aqualuxe_get_translated_post_id($post_id, $language));
            if ($url) {
                echo '<link rel="alternate" hreflang="' . esc_attr($language) . '" href="' . esc_url($url) . '" />' . "\n";
            }
        }
        
        // Add x-default hreflang
        $default_language = pll_default_language();
        $default_url = get_permalink(aqualuxe_get_translated_post_id($post_id, $default_language));
        if ($default_url) {
            echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($default_url) . '" />' . "\n";
        }
    }
}
add_action('wp_head', 'aqualuxe_add_hreflang_links');