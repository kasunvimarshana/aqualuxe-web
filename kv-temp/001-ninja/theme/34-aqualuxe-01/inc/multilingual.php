<?php
/**
 * Multilingual functionality for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Check if a multilingual plugin is active
 *
 * @return bool True if a multilingual plugin is active, false otherwise
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
    
    // Check for TranslatePress
    if (class_exists('TRP_Translate_Press')) {
        return true;
    }
    
    // Check for qTranslate-X
    if (function_exists('qtranxf_getLanguage')) {
        return true;
    }
    
    // Check for Weglot
    if (function_exists('weglot_get_current_language')) {
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
    
    // TranslatePress
    if (class_exists('TRP_Translate_Press')) {
        global $TRP_LANGUAGE;
        if (!empty($TRP_LANGUAGE)) {
            return $TRP_LANGUAGE;
        }
    }
    
    // qTranslate-X
    if (function_exists('qtranxf_getLanguage')) {
        return qtranxf_getLanguage();
    }
    
    // Weglot
    if (function_exists('weglot_get_current_language')) {
        return weglot_get_current_language();
    }
    
    // Default to WordPress locale
    return get_locale();
}

/**
 * Get available languages
 *
 * @return array Available languages
 */
function aqualuxe_get_available_languages() {
    $languages = array();
    
    // WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        global $sitepress;
        if ($sitepress) {
            $languages = apply_filters('wpml_active_languages', null, array('skip_missing' => 0));
        }
    }
    
    // Polylang
    elseif (function_exists('pll_languages_list')) {
        $pll_languages = pll_languages_list(array('fields' => 'slug'));
        $pll_names = pll_languages_list(array('fields' => 'name'));
        
        if (!empty($pll_languages) && !empty($pll_names)) {
            foreach ($pll_languages as $key => $code) {
                $languages[$code] = array(
                    'code' => $code,
                    'native_name' => isset($pll_names[$key]) ? $pll_names[$key] : $code,
                    'flag' => '',
                );
            }
        }
    }
    
    // TranslatePress
    elseif (class_exists('TRP_Translate_Press')) {
        $trp = TRP_Translate_Press::get_trp_instance();
        $trp_settings = $trp->get_component('settings');
        if ($trp_settings) {
            $settings = $trp_settings->get_settings();
            if (isset($settings['publish-languages'])) {
                foreach ($settings['publish-languages'] as $code) {
                    $languages[$code] = array(
                        'code' => $code,
                        'native_name' => isset($settings['language-names'][$code]) ? $settings['language-names'][$code] : $code,
                        'flag' => '',
                    );
                }
            }
        }
    }
    
    // qTranslate-X
    elseif (function_exists('qtranxf_getSortedLanguages')) {
        global $q_config;
        $qtranslate_languages = qtranxf_getSortedLanguages();
        
        if (!empty($qtranslate_languages)) {
            foreach ($qtranslate_languages as $code) {
                $languages[$code] = array(
                    'code' => $code,
                    'native_name' => isset($q_config['language_name'][$code]) ? $q_config['language_name'][$code] : $code,
                    'flag' => '',
                );
            }
        }
    }
    
    // Weglot
    elseif (function_exists('weglot_get_languages_available')) {
        $weglot_languages = weglot_get_languages_available();
        
        if (!empty($weglot_languages)) {
            foreach ($weglot_languages as $code => $name) {
                $languages[$code] = array(
                    'code' => $code,
                    'native_name' => $name,
                    'flag' => '',
                );
            }
        }
    }
    
    // If no multilingual plugin is active, return WordPress locales
    if (empty($languages)) {
        $wp_languages = get_available_languages();
        $wp_languages[] = 'en_US'; // Add English (US) as it's not included in the available languages
        
        foreach ($wp_languages as $locale) {
            $languages[$locale] = array(
                'code' => $locale,
                'native_name' => $locale,
                'flag' => '',
            );
        }
    }
    
    return $languages;
}

/**
 * Get language switcher HTML
 *
 * @return string Language switcher HTML
 */
function aqualuxe_get_language_switcher() {
    $output = '';
    $current_language = aqualuxe_get_current_language();
    $languages = aqualuxe_get_available_languages();
    
    if (empty($languages)) {
        return $output;
    }
    
    $output .= '<div class="language-switcher relative">';
    $output .= '<button class="language-switcher-toggle flex items-center space-x-1" aria-expanded="false" aria-controls="language-switcher-dropdown">';
    
    // Current language
    if (isset($languages[$current_language])) {
        $output .= '<span class="language-code">' . esc_html(strtoupper($current_language)) . '</span>';
    } else {
        $output .= '<span class="language-code">' . esc_html(strtoupper($current_language)) . '</span>';
    }
    
    $output .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>';
    $output .= '</button>';
    
    // Language dropdown
    $output .= '<div id="language-switcher-dropdown" class="language-switcher-dropdown absolute right-0 mt-2 py-2 w-40 bg-white rounded-md shadow-lg z-50 hidden dark:bg-gray-800">';
    
    // WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        foreach ($languages as $code => $language) {
            $class = ($code === $current_language) ? 'active' : '';
            $output .= '<a href="' . esc_url($language['url']) . '" class="language-item block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 ' . esc_attr($class) . '">';
            
            if (!empty($language['country_flag_url'])) {
                $output .= '<img src="' . esc_url($language['country_flag_url']) . '" alt="' . esc_attr($language['native_name']) . '" class="inline-block mr-2 w-4 h-auto">';
            }
            
            $output .= esc_html($language['native_name']);
            $output .= '</a>';
        }
    }
    
    // Polylang
    elseif (function_exists('pll_the_languages')) {
        $pll_languages = pll_the_languages(array('raw' => 1));
        
        if (!empty($pll_languages)) {
            foreach ($pll_languages as $code => $language) {
                $class = ($language['current_lang']) ? 'active' : '';
                $output .= '<a href="' . esc_url($language['url']) . '" class="language-item block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 ' . esc_attr($class) . '">';
                
                if (!empty($language['flag'])) {
                    $output .= $language['flag'];
                }
                
                $output .= esc_html($language['name']);
                $output .= '</a>';
            }
        }
    }
    
    // TranslatePress
    elseif (class_exists('TRP_Translate_Press')) {
        $trp = TRP_Translate_Press::get_trp_instance();
        $trp_languages = $trp->get_component('languages');
        $trp_settings = $trp->get_component('settings');
        
        if ($trp_languages && $trp_settings) {
            $settings = $trp_settings->get_settings();
            $languages_to_display = isset($settings['publish-languages']) ? $settings['publish-languages'] : array();
            
            if (!empty($languages_to_display)) {
                foreach ($languages_to_display as $code) {
                    $language_name = isset($settings['language-names'][$code]) ? $settings['language-names'][$code] : $code;
                    $class = ($code === $current_language) ? 'active' : '';
                    $url = add_query_arg('trp-edit-translation', 'true', trp_add_language_to_url(get_permalink(), $code));
                    
                    $output .= '<a href="' . esc_url($url) . '" class="language-item block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 ' . esc_attr($class) . '">';
                    $output .= esc_html($language_name);
                    $output .= '</a>';
                }
            }
        }
    }
    
    // qTranslate-X
    elseif (function_exists('qtranxf_getSortedLanguages')) {
        global $q_config;
        $qtranslate_languages = qtranxf_getSortedLanguages();
        
        if (!empty($qtranslate_languages)) {
            foreach ($qtranslate_languages as $code) {
                $class = ($code === $current_language) ? 'active' : '';
                $url = qtranxf_convertURL('', $code);
                
                $output .= '<a href="' . esc_url($url) . '" class="language-item block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 ' . esc_attr($class) . '">';
                
                if (!empty($q_config['flag'][$code])) {
                    $flag_url = content_url('plugins/qtranslate-x/flags/' . $q_config['flag'][$code]);
                    $output .= '<img src="' . esc_url($flag_url) . '" alt="' . esc_attr($q_config['language_name'][$code]) . '" class="inline-block mr-2 w-4 h-auto">';
                }
                
                $output .= esc_html($q_config['language_name'][$code]);
                $output .= '</a>';
            }
        }
    }
    
    // Weglot
    elseif (function_exists('weglot_get_languages_available') && function_exists('weglot_get_current_language')) {
        $weglot_languages = weglot_get_languages_available();
        
        if (!empty($weglot_languages)) {
            foreach ($weglot_languages as $code => $name) {
                $class = ($code === $current_language) ? 'active' : '';
                $url = weglot_get_current_language() !== $code ? weglot_create_url($code) : '';
                
                $output .= '<a href="' . esc_url($url) . '" class="language-item block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 ' . esc_attr($class) . '">';
                $output .= esc_html($name);
                $output .= '</a>';
            }
        }
    }
    
    // If no multilingual plugin is active, show a message
    else {
        $output .= '<div class="language-item block px-4 py-2 text-sm">';
        $output .= esc_html__('No multilingual plugin detected', 'aqualuxe');
        $output .= '</div>';
    }
    
    $output .= '</div>'; // .language-switcher-dropdown
    $output .= '</div>'; // .language-switcher
    
    // Add JavaScript for dropdown toggle
    $output .= '
    <script>
    (function() {
        document.addEventListener("DOMContentLoaded", function() {
            var toggle = document.querySelector(".language-switcher-toggle");
            var dropdown = document.querySelector(".language-switcher-dropdown");
            
            if (toggle && dropdown) {
                toggle.addEventListener("click", function(e) {
                    e.preventDefault();
                    dropdown.classList.toggle("hidden");
                    toggle.setAttribute("aria-expanded", dropdown.classList.contains("hidden") ? "false" : "true");
                });
                
                document.addEventListener("click", function(e) {
                    if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.add("hidden");
                        toggle.setAttribute("aria-expanded", "false");
                    }
                });
            }
        });
    })();
    </script>
    ';
    
    return $output;
}

/**
 * Display language switcher
 */
function aqualuxe_language_switcher() {
    echo aqualuxe_get_language_switcher();
}

/**
 * Add language switcher to header
 */
function aqualuxe_add_language_switcher_to_header() {
    if (aqualuxe_is_multilingual()) {
        ?>
        <div class="header-language-switcher ml-4">
            <?php aqualuxe_language_switcher(); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_header_right', 'aqualuxe_add_language_switcher_to_header');

/**
 * Add language switcher to mobile menu
 */
function aqualuxe_add_language_switcher_to_mobile_menu() {
    if (aqualuxe_is_multilingual()) {
        ?>
        <div class="mobile-language-switcher py-4 border-t border-gray-200 dark:border-gray-700 mt-4">
            <div class="px-4 mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                <?php esc_html_e('Language', 'aqualuxe'); ?>
            </div>
            <?php
            $current_language = aqualuxe_get_current_language();
            $languages = aqualuxe_get_available_languages();
            
            if (!empty($languages)) {
                echo '<div class="mobile-language-list">';
                
                // WPML
                if (defined('ICL_SITEPRESS_VERSION')) {
                    foreach ($languages as $code => $language) {
                        $class = ($code === $current_language) ? 'active bg-gray-100 dark:bg-gray-700' : '';
                        echo '<a href="' . esc_url($language['url']) . '" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 ' . esc_attr($class) . '">';
                        
                        if (!empty($language['country_flag_url'])) {
                            echo '<img src="' . esc_url($language['country_flag_url']) . '" alt="' . esc_attr($language['native_name']) . '" class="inline-block mr-2 w-4 h-auto">';
                        }
                        
                        echo esc_html($language['native_name']);
                        echo '</a>';
                    }
                }
                
                // Polylang
                elseif (function_exists('pll_the_languages')) {
                    $pll_languages = pll_the_languages(array('raw' => 1));
                    
                    if (!empty($pll_languages)) {
                        foreach ($pll_languages as $code => $language) {
                            $class = ($language['current_lang']) ? 'active bg-gray-100 dark:bg-gray-700' : '';
                            echo '<a href="' . esc_url($language['url']) . '" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 ' . esc_attr($class) . '">';
                            
                            if (!empty($language['flag'])) {
                                echo $language['flag'];
                            }
                            
                            echo esc_html($language['name']);
                            echo '</a>';
                        }
                    }
                }
                
                // TranslatePress
                elseif (class_exists('TRP_Translate_Press')) {
                    $trp = TRP_Translate_Press::get_trp_instance();
                    $trp_languages = $trp->get_component('languages');
                    $trp_settings = $trp->get_component('settings');
                    
                    if ($trp_languages && $trp_settings) {
                        $settings = $trp_settings->get_settings();
                        $languages_to_display = isset($settings['publish-languages']) ? $settings['publish-languages'] : array();
                        
                        if (!empty($languages_to_display)) {
                            foreach ($languages_to_display as $code) {
                                $language_name = isset($settings['language-names'][$code]) ? $settings['language-names'][$code] : $code;
                                $class = ($code === $current_language) ? 'active bg-gray-100 dark:bg-gray-700' : '';
                                $url = add_query_arg('trp-edit-translation', 'true', trp_add_language_to_url(get_permalink(), $code));
                                
                                echo '<a href="' . esc_url($url) . '" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 ' . esc_attr($class) . '">';
                                echo esc_html($language_name);
                                echo '</a>';
                            }
                        }
                    }
                }
                
                // qTranslate-X
                elseif (function_exists('qtranxf_getSortedLanguages')) {
                    global $q_config;
                    $qtranslate_languages = qtranxf_getSortedLanguages();
                    
                    if (!empty($qtranslate_languages)) {
                        foreach ($qtranslate_languages as $code) {
                            $class = ($code === $current_language) ? 'active bg-gray-100 dark:bg-gray-700' : '';
                            $url = qtranxf_convertURL('', $code);
                            
                            echo '<a href="' . esc_url($url) . '" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 ' . esc_attr($class) . '">';
                            
                            if (!empty($q_config['flag'][$code])) {
                                $flag_url = content_url('plugins/qtranslate-x/flags/' . $q_config['flag'][$code]);
                                echo '<img src="' . esc_url($flag_url) . '" alt="' . esc_attr($q_config['language_name'][$code]) . '" class="inline-block mr-2 w-4 h-auto">';
                            }
                            
                            echo esc_html($q_config['language_name'][$code]);
                            echo '</a>';
                        }
                    }
                }
                
                // Weglot
                elseif (function_exists('weglot_get_languages_available') && function_exists('weglot_get_current_language')) {
                    $weglot_languages = weglot_get_languages_available();
                    
                    if (!empty($weglot_languages)) {
                        foreach ($weglot_languages as $code => $name) {
                            $class = ($code === $current_language) ? 'active bg-gray-100 dark:bg-gray-700' : '';
                            $url = weglot_get_current_language() !== $code ? weglot_create_url($code) : '';
                            
                            echo '<a href="' . esc_url($url) . '" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 ' . esc_attr($class) . '">';
                            echo esc_html($name);
                            echo '</a>';
                        }
                    }
                }
                
                echo '</div>';
            }
            ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_after_mobile_menu', 'aqualuxe_add_language_switcher_to_mobile_menu');

/**
 * Add language switcher to footer
 */
function aqualuxe_add_language_switcher_to_footer() {
    if (aqualuxe_is_multilingual()) {
        ?>
        <div class="footer-language-switcher mt-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                <?php esc_html_e('Language', 'aqualuxe'); ?>
            </div>
            <?php aqualuxe_language_switcher(); ?>
        </div>
        <?php
    }
}
add_action('aqualuxe_footer_bottom', 'aqualuxe_add_language_switcher_to_footer');

/**
 * Add hreflang tags to head
 */
function aqualuxe_add_hreflang_tags() {
    // Skip if no multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    // WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        // WPML adds hreflang tags automatically
        return;
    }
    
    // Polylang
    if (function_exists('pll_languages_list') && function_exists('pll_current_language')) {
        $languages = pll_languages_list(array('fields' => 'slug'));
        $current_language = pll_current_language();
        
        if (!empty($languages)) {
            foreach ($languages as $language) {
                $url = pll_home_url($language);
                
                if (is_singular()) {
                    $post_id = pll_get_post(get_the_ID(), $language);
                    if ($post_id) {
                        $url = get_permalink($post_id);
                    }
                } elseif (is_category() || is_tag() || is_tax()) {
                    $term_id = pll_get_term(get_queried_object_id(), $language);
                    if ($term_id) {
                        $url = get_term_link($term_id);
                    }
                }
                
                echo '<link rel="alternate" hreflang="' . esc_attr($language) . '" href="' . esc_url($url) . '" />' . "\n";
            }
            
            // Add x-default hreflang
            $default_language = pll_default_language();
            $default_url = pll_home_url($default_language);
            
            echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($default_url) . '" />' . "\n";
        }
    }
    
    // TranslatePress
    elseif (class_exists('TRP_Translate_Press')) {
        // TranslatePress adds hreflang tags automatically
        return;
    }
    
    // qTranslate-X
    elseif (function_exists('qtranxf_getSortedLanguages')) {
        global $q_config;
        $languages = qtranxf_getSortedLanguages();
        
        if (!empty($languages)) {
            foreach ($languages as $language) {
                $url = qtranxf_convertURL(get_permalink(), $language);
                echo '<link rel="alternate" hreflang="' . esc_attr($language) . '" href="' . esc_url($url) . '" />' . "\n";
            }
            
            // Add x-default hreflang
            $default_language = $q_config['default_language'];
            $default_url = qtranxf_convertURL(get_permalink(), $default_language);
            echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($default_url) . '" />' . "\n";
        }
    }
    
    // Weglot
    elseif (function_exists('weglot_get_languages_available') && function_exists('weglot_get_current_language')) {
        // Weglot adds hreflang tags automatically
        return;
    }
}
add_action('wp_head', 'aqualuxe_add_hreflang_tags');

/**
 * Add language switcher to customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_multilingual_customizer($wp_customize) {
    // Add Multilingual Section
    $wp_customize->add_section(
        'aqualuxe_multilingual_section',
        array(
            'title'       => __('Multilingual', 'aqualuxe'),
            'description' => __('Customize multilingual settings', 'aqualuxe'),
            'panel'       => 'aqualuxe_theme_options',
            'priority'    => 70,
        )
    );

    // Add Language Switcher Position Option
    $wp_customize->add_setting(
        'aqualuxe_language_switcher_position',
        array(
            'default'           => 'header',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_language_switcher_position',
        array(
            'label'       => __('Language Switcher Position', 'aqualuxe'),
            'description' => __('Choose where to display the language switcher', 'aqualuxe'),
            'section'     => 'aqualuxe_multilingual_section',
            'type'        => 'select',
            'choices'     => array(
                'header' => __('Header', 'aqualuxe'),
                'footer' => __('Footer', 'aqualuxe'),
                'both'   => __('Both Header and Footer', 'aqualuxe'),
                'none'   => __('None', 'aqualuxe'),
            ),
        )
    );

    // Add Language Switcher Style Option
    $wp_customize->add_setting(
        'aqualuxe_language_switcher_style',
        array(
            'default'           => 'dropdown',
            'sanitize_callback' => 'aqualuxe_sanitize_select',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_language_switcher_style',
        array(
            'label'       => __('Language Switcher Style', 'aqualuxe'),
            'description' => __('Choose the style of the language switcher', 'aqualuxe'),
            'section'     => 'aqualuxe_multilingual_section',
            'type'        => 'select',
            'choices'     => array(
                'dropdown' => __('Dropdown', 'aqualuxe'),
                'list'     => __('Horizontal List', 'aqualuxe'),
                'flags'    => __('Flags Only', 'aqualuxe'),
            ),
        )
    );

    // Add Show Flags Option
    $wp_customize->add_setting(
        'aqualuxe_language_switcher_show_flags',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_language_switcher_show_flags',
        array(
            'label'       => __('Show Flags', 'aqualuxe'),
            'description' => __('Display country flags in the language switcher', 'aqualuxe'),
            'section'     => 'aqualuxe_multilingual_section',
            'type'        => 'checkbox',
        )
    );

    // Add Show Language Names Option
    $wp_customize->add_setting(
        'aqualuxe_language_switcher_show_names',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_language_switcher_show_names',
        array(
            'label'       => __('Show Language Names', 'aqualuxe'),
            'description' => __('Display language names in the language switcher', 'aqualuxe'),
            'section'     => 'aqualuxe_multilingual_section',
            'type'        => 'checkbox',
        )
    );

    // Add Show Language Codes Option
    $wp_customize->add_setting(
        'aqualuxe_language_switcher_show_codes',
        array(
            'default'           => false,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_language_switcher_show_codes',
        array(
            'label'       => __('Show Language Codes', 'aqualuxe'),
            'description' => __('Display language codes in the language switcher', 'aqualuxe'),
            'section'     => 'aqualuxe_multilingual_section',
            'type'        => 'checkbox',
        )
    );
}
add_action('customize_register', 'aqualuxe_multilingual_customizer');

/**
 * Filter language switcher display based on customizer settings
 */
function aqualuxe_filter_language_switcher_display() {
    $position = get_theme_mod('aqualuxe_language_switcher_position', 'header');
    
    if ($position === 'none') {
        remove_action('aqualuxe_header_right', 'aqualuxe_add_language_switcher_to_header');
        remove_action('aqualuxe_footer_bottom', 'aqualuxe_add_language_switcher_to_footer');
    } elseif ($position === 'footer') {
        remove_action('aqualuxe_header_right', 'aqualuxe_add_language_switcher_to_header');
    } elseif ($position === 'header') {
        remove_action('aqualuxe_footer_bottom', 'aqualuxe_add_language_switcher_to_footer');
    }
}
add_action('wp', 'aqualuxe_filter_language_switcher_display');

/**
 * Add multilingual support for custom post types and taxonomies
 */
function aqualuxe_multilingual_post_types() {
    // Skip if no multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    // WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        // Register custom post types for translation
        if (function_exists('icl_object_id')) {
            // Service post type
            do_action('wpml_register_single_string', 'aqualuxe', 'service_post_type_name', __('Services', 'aqualuxe'));
            do_action('wpml_register_single_string', 'aqualuxe', 'service_post_type_singular_name', __('Service', 'aqualuxe'));
            
            // Testimonial post type
            do_action('wpml_register_single_string', 'aqualuxe', 'testimonial_post_type_name', __('Testimonials', 'aqualuxe'));
            do_action('wpml_register_single_string', 'aqualuxe', 'testimonial_post_type_singular_name', __('Testimonial', 'aqualuxe'));
            
            // Team post type
            do_action('wpml_register_single_string', 'aqualuxe', 'team_post_type_name', __('Team Members', 'aqualuxe'));
            do_action('wpml_register_single_string', 'aqualuxe', 'team_post_type_singular_name', __('Team Member', 'aqualuxe'));
            
            // FAQ post type
            do_action('wpml_register_single_string', 'aqualuxe', 'faq_post_type_name', __('FAQs', 'aqualuxe'));
            do_action('wpml_register_single_string', 'aqualuxe', 'faq_post_type_singular_name', __('FAQ', 'aqualuxe'));
        }
    }
    
    // Polylang
    elseif (function_exists('pll_register_string')) {
        // Register custom post types for translation
        pll_register_string('service_post_type_name', __('Services', 'aqualuxe'), 'aqualuxe');
        pll_register_string('service_post_type_singular_name', __('Service', 'aqualuxe'), 'aqualuxe');
        
        pll_register_string('testimonial_post_type_name', __('Testimonials', 'aqualuxe'), 'aqualuxe');
        pll_register_string('testimonial_post_type_singular_name', __('Testimonial', 'aqualuxe'), 'aqualuxe');
        
        pll_register_string('team_post_type_name', __('Team Members', 'aqualuxe'), 'aqualuxe');
        pll_register_string('team_post_type_singular_name', __('Team Member', 'aqualuxe'), 'aqualuxe');
        
        pll_register_string('faq_post_type_name', __('FAQs', 'aqualuxe'), 'aqualuxe');
        pll_register_string('faq_post_type_singular_name', __('FAQ', 'aqualuxe'), 'aqualuxe');
    }
}
add_action('init', 'aqualuxe_multilingual_post_types');

/**
 * Add multilingual support for theme options
 */
function aqualuxe_multilingual_theme_options() {
    // Skip if no multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    // WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        // Register theme options for translation
        if (function_exists('icl_object_id')) {
            // Copyright text
            $copyright_text = get_theme_mod('aqualuxe_copyright_text', sprintf(__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'), date('Y')));
            do_action('wpml_register_single_string', 'aqualuxe', 'copyright_text', $copyright_text);
            
            // Shipping content
            $shipping_content = get_theme_mod('aqualuxe_shipping_content', '');
            if (!empty($shipping_content)) {
                do_action('wpml_register_single_string', 'aqualuxe', 'shipping_content', $shipping_content);
            }
        }
    }
    
    // Polylang
    elseif (function_exists('pll_register_string')) {
        // Register theme options for translation
        $copyright_text = get_theme_mod('aqualuxe_copyright_text', sprintf(__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'), date('Y')));
        pll_register_string('copyright_text', $copyright_text, 'aqualuxe');
        
        $shipping_content = get_theme_mod('aqualuxe_shipping_content', '');
        if (!empty($shipping_content)) {
            pll_register_string('shipping_content', $shipping_content, 'aqualuxe');
        }
    }
}
add_action('init', 'aqualuxe_multilingual_theme_options');

/**
 * Translate strings in the theme
 *
 * @param string $string The string to translate
 * @param string $domain The text domain
 * @return string The translated string
 */
function aqualuxe_translate_string($string, $domain = 'aqualuxe') {
    // Skip if no multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return $string;
    }
    
    // WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        if (function_exists('icl_t')) {
            return icl_t($domain, $string, $string);
        }
    }
    
    // Polylang
    elseif (function_exists('pll__')) {
        return pll__($string);
    }
    
    return $string;
}

/**
 * Add RTL support
 */
function aqualuxe_rtl_support() {
    // Get current language
    $current_language = aqualuxe_get_current_language();
    
    // RTL languages
    $rtl_languages = array(
        'ar', // Arabic
        'fa', // Persian
        'he', // Hebrew
        'ur', // Urdu
    );
    
    // Check if current language is RTL
    $is_rtl = in_array($current_language, $rtl_languages);
    
    // Add RTL class to body if needed
    if ($is_rtl) {
        add_filter('body_class', function($classes) {
            $classes[] = 'rtl';
            return $classes;
        });
    }
}
add_action('wp', 'aqualuxe_rtl_support');

/**
 * Add RTL stylesheet
 */
function aqualuxe_rtl_stylesheet() {
    // Get current language
    $current_language = aqualuxe_get_current_language();
    
    // RTL languages
    $rtl_languages = array(
        'ar', // Arabic
        'fa', // Persian
        'he', // Hebrew
        'ur', // Urdu
    );
    
    // Check if current language is RTL
    $is_rtl = in_array($current_language, $rtl_languages);
    
    // Enqueue RTL stylesheet if needed
    if ($is_rtl) {
        wp_enqueue_style('aqualuxe-rtl', AQUALUXE_ASSETS_URI . 'css/rtl.css', array('aqualuxe-main'), AQUALUXE_VERSION);
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_rtl_stylesheet');

/**
 * Add language direction attribute to html tag
 */
function aqualuxe_language_direction() {
    // Get current language
    $current_language = aqualuxe_get_current_language();
    
    // RTL languages
    $rtl_languages = array(
        'ar', // Arabic
        'fa', // Persian
        'he', // Hebrew
        'ur', // Urdu
    );
    
    // Check if current language is RTL
    $is_rtl = in_array($current_language, $rtl_languages);
    
    // Add dir attribute to html tag
    $dir = $is_rtl ? 'rtl' : 'ltr';
    
    return $dir;
}

/**
 * Filter html tag attributes
 */
function aqualuxe_html_tag_attributes($output) {
    $dir = aqualuxe_language_direction();
    
    if (!empty($dir)) {
        $output .= ' dir="' . esc_attr($dir) . '"';
    }
    
    return $output;
}
add_filter('language_attributes', 'aqualuxe_html_tag_attributes');

/**
 * Add multilingual support for custom fields
 */
function aqualuxe_multilingual_custom_fields() {
    // Skip if no multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    // WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        // Register custom fields for translation
        if (function_exists('icl_object_id')) {
            // Team custom fields
            do_action('wpml_register_single_string', 'aqualuxe', 'team_position', get_post_meta(get_the_ID(), '_aqualuxe_team_position', true));
            
            // Service custom fields
            do_action('wpml_register_single_string', 'aqualuxe', 'service_price', get_post_meta(get_the_ID(), '_aqualuxe_service_price', true));
            do_action('wpml_register_single_string', 'aqualuxe', 'service_duration', get_post_meta(get_the_ID(), '_aqualuxe_service_duration', true));
            
            // Testimonial custom fields
            do_action('wpml_register_single_string', 'aqualuxe', 'testimonial_company', get_post_meta(get_the_ID(), '_aqualuxe_testimonial_company', true));
            do_action('wpml_register_single_string', 'aqualuxe', 'testimonial_position', get_post_meta(get_the_ID(), '_aqualuxe_testimonial_position', true));
        }
    }
    
    // Polylang
    elseif (function_exists('pll_register_string')) {
        // Register custom fields for translation
        pll_register_string('team_position', get_post_meta(get_the_ID(), '_aqualuxe_team_position', true), 'aqualuxe');
        pll_register_string('service_price', get_post_meta(get_the_ID(), '_aqualuxe_service_price', true), 'aqualuxe');
        pll_register_string('service_duration', get_post_meta(get_the_ID(), '_aqualuxe_service_duration', true), 'aqualuxe');
        pll_register_string('testimonial_company', get_post_meta(get_the_ID(), '_aqualuxe_testimonial_company', true), 'aqualuxe');
        pll_register_string('testimonial_position', get_post_meta(get_the_ID(), '_aqualuxe_testimonial_position', true), 'aqualuxe');
    }
}
add_action('wp', 'aqualuxe_multilingual_custom_fields');

/**
 * Add multilingual support for menu items
 */
function aqualuxe_multilingual_menu_items($items) {
    // Skip if no multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return $items;
    }
    
    foreach ($items as $item) {
        // Skip items that don't have a title
        if (empty($item->title)) {
            continue;
        }
        
        // WPML
        if (defined('ICL_SITEPRESS_VERSION')) {
            if (function_exists('icl_t')) {
                $item->title = icl_t('aqualuxe', 'menu_item_' . $item->ID, $item->title);
            }
        }
        
        // Polylang
        elseif (function_exists('pll__')) {
            $item->title = pll__($item->title);
        }
    }
    
    return $items;
}
add_filter('wp_nav_menu_objects', 'aqualuxe_multilingual_menu_items');

/**
 * Register menu items for translation
 */
function aqualuxe_register_menu_items_for_translation() {
    // Skip if no multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return;
    }
    
    // Get all menu locations
    $locations = get_nav_menu_locations();
    
    foreach ($locations as $location => $menu_id) {
        if ($menu_id) {
            $menu_items = wp_get_nav_menu_items($menu_id);
            
            if ($menu_items) {
                foreach ($menu_items as $item) {
                    // WPML
                    if (defined('ICL_SITEPRESS_VERSION')) {
                        if (function_exists('icl_object_id')) {
                            do_action('wpml_register_single_string', 'aqualuxe', 'menu_item_' . $item->ID, $item->title);
                        }
                    }
                    
                    // Polylang
                    elseif (function_exists('pll_register_string')) {
                        pll_register_string('menu_item_' . $item->ID, $item->title, 'aqualuxe');
                    }
                }
            }
        }
    }
}
add_action('init', 'aqualuxe_register_menu_items_for_translation');

/**
 * Add language switcher to WooCommerce account navigation
 */
function aqualuxe_woocommerce_account_language_switcher() {
    if (aqualuxe_is_multilingual() && class_exists('WooCommerce')) {
        ?>
        <div class="woocommerce-account-language-switcher mb-6">
            <h3 class="text-lg font-bold mb-2"><?php esc_html_e('Language', 'aqualuxe'); ?></h3>
            <?php aqualuxe_language_switcher(); ?>
        </div>
        <?php
    }
}
add_action('woocommerce_before_account_navigation', 'aqualuxe_woocommerce_account_language_switcher');

/**
 * Add language switcher to WooCommerce checkout
 */
function aqualuxe_woocommerce_checkout_language_switcher() {
    if (aqualuxe_is_multilingual() && class_exists('WooCommerce')) {
        ?>
        <div class="woocommerce-checkout-language-switcher mb-6">
            <h3 class="text-lg font-bold mb-2"><?php esc_html_e('Change Language', 'aqualuxe'); ?></h3>
            <?php aqualuxe_language_switcher(); ?>
        </div>
        <?php
    }
}
add_action('woocommerce_before_checkout_form', 'aqualuxe_woocommerce_checkout_language_switcher', 5);

/**
 * Add language parameter to AJAX URLs
 */
function aqualuxe_multilingual_ajax_url($url) {
    // Skip if no multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return $url;
    }
    
    $current_language = aqualuxe_get_current_language();
    
    // WPML
    if (defined('ICL_SITEPRESS_VERSION')) {
        return add_query_arg('lang', $current_language, $url);
    }
    
    // Polylang
    elseif (function_exists('pll_current_language')) {
        return add_query_arg('lang', $current_language, $url);
    }
    
    return $url;
}
add_filter('admin_url', 'aqualuxe_multilingual_ajax_url');

/**
 * Add language parameter to localized script data
 */
function aqualuxe_multilingual_localize_script($data) {
    // Skip if no multilingual plugin is active
    if (!aqualuxe_is_multilingual()) {
        return $data;
    }
    
    $current_language = aqualuxe_get_current_language();
    
    $data['language'] = $current_language;
    
    return $data;
}
add_filter('aqualuxeData', 'aqualuxe_multilingual_localize_script');