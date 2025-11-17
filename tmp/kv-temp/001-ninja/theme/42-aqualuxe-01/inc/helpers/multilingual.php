<?php
/**
 * Multilingual support for AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Check if WPML is active
 *
 * @return bool
 */
function aqualuxe_is_wpml_active() {
    return defined( 'ICL_SITEPRESS_VERSION' );
}

/**
 * Check if Polylang is active
 *
 * @return bool
 */
function aqualuxe_is_polylang_active() {
    return function_exists( 'pll_current_language' );
}

/**
 * Check if TranslatePress is active
 *
 * @return bool
 */
function aqualuxe_is_translatepress_active() {
    return defined( 'TRP_PLUGIN_VERSION' );
}

/**
 * Check if any multilingual plugin is active
 *
 * @return bool
 */
function aqualuxe_is_multilingual() {
    return aqualuxe_is_wpml_active() || aqualuxe_is_polylang_active() || aqualuxe_is_translatepress_active();
}

/**
 * Get current language code
 *
 * @return string
 */
function aqualuxe_get_current_language() {
    if ( aqualuxe_is_wpml_active() ) {
        return apply_filters( 'wpml_current_language', null );
    } elseif ( aqualuxe_is_polylang_active() ) {
        return pll_current_language();
    } elseif ( aqualuxe_is_translatepress_active() && function_exists( 'trp_get_languages' ) ) {
        $trp = TRP_Translate_Press::get_trp_instance();
        $trp_settings = $trp->get_component( 'settings' );
        $settings = $trp_settings->get_settings();
        
        global $TRP_LANGUAGE;
        if ( ! empty( $TRP_LANGUAGE ) ) {
            return $TRP_LANGUAGE;
        }
        
        return $settings['default-language'];
    }
    
    return get_locale();
}

/**
 * Get default language code
 *
 * @return string
 */
function aqualuxe_get_default_language() {
    if ( aqualuxe_is_wpml_active() ) {
        return apply_filters( 'wpml_default_language', null );
    } elseif ( aqualuxe_is_polylang_active() ) {
        return pll_default_language();
    } elseif ( aqualuxe_is_translatepress_active() && function_exists( 'trp_get_languages' ) ) {
        $trp = TRP_Translate_Press::get_trp_instance();
        $trp_settings = $trp->get_component( 'settings' );
        $settings = $trp_settings->get_settings();
        
        return $settings['default-language'];
    }
    
    return get_locale();
}

/**
 * Get available languages
 *
 * @return array
 */
function aqualuxe_get_available_languages() {
    if ( aqualuxe_is_wpml_active() ) {
        return apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) );
    } elseif ( aqualuxe_is_polylang_active() ) {
        return pll_languages_list( array( 'fields' => '' ) );
    } elseif ( aqualuxe_is_translatepress_active() && function_exists( 'trp_get_languages' ) ) {
        $trp = TRP_Translate_Press::get_trp_instance();
        $trp_settings = $trp->get_component( 'settings' );
        $settings = $trp_settings->get_settings();
        
        return $settings['translation-languages'];
    }
    
    return array( get_locale() );
}

/**
 * Get language name from code
 *
 * @param string $code Language code
 * @return string
 */
function aqualuxe_get_language_name( $code ) {
    if ( aqualuxe_is_wpml_active() ) {
        $languages = apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) );
        return isset( $languages[$code]['native_name'] ) ? $languages[$code]['native_name'] : $code;
    } elseif ( aqualuxe_is_polylang_active() ) {
        $languages = pll_languages_list( array( 'fields' => '' ) );
        foreach ( $languages as $language ) {
            if ( $language->slug === $code ) {
                return $language->name;
            }
        }
        return $code;
    } elseif ( aqualuxe_is_translatepress_active() ) {
        $trp = TRP_Translate_Press::get_trp_instance();
        $trp_languages = $trp->get_component( 'languages' );
        $language_names = $trp_languages->get_language_names( array( $code ) );
        
        return isset( $language_names[$code] ) ? $language_names[$code] : $code;
    }
    
    return $code;
}

/**
 * Get language URL
 *
 * @param string $code Language code
 * @return string
 */
function aqualuxe_get_language_url( $code ) {
    if ( aqualuxe_is_wpml_active() ) {
        return apply_filters( 'wpml_permalink', get_permalink(), $code );
    } elseif ( aqualuxe_is_polylang_active() ) {
        return pll_home_url( $code );
    } elseif ( aqualuxe_is_translatepress_active() ) {
        $trp = TRP_Translate_Press::get_trp_instance();
        $url_converter = $trp->get_component( 'url_converter' );
        
        return $url_converter->get_url_for_language( $code, get_permalink() );
    }
    
    return home_url();
}

/**
 * Get language flag URL
 *
 * @param string $code Language code
 * @return string
 */
function aqualuxe_get_language_flag_url( $code ) {
    if ( aqualuxe_is_wpml_active() ) {
        $languages = apply_filters( 'wpml_active_languages', null, array( 'skip_missing' => 0 ) );
        return isset( $languages[$code]['country_flag_url'] ) ? $languages[$code]['country_flag_url'] : '';
    } elseif ( aqualuxe_is_polylang_active() ) {
        $languages = pll_languages_list( array( 'fields' => '' ) );
        foreach ( $languages as $language ) {
            if ( $language->slug === $code ) {
                return $language->flag_url;
            }
        }
        return '';
    } elseif ( aqualuxe_is_translatepress_active() ) {
        return AQUALUXE_URI . 'assets/dist/images/flags/' . $code . '.png';
    }
    
    return '';
}

/**
 * Translate string
 *
 * @param string $string String to translate
 * @param string $domain Text domain
 * @param string $name String name for WPML
 * @return string
 */
function aqualuxe_translate_string( $string, $domain = 'aqualuxe', $name = '' ) {
    if ( aqualuxe_is_wpml_active() ) {
        return apply_filters( 'wpml_translate_single_string', $string, $domain, $name ? $name : $string );
    } elseif ( aqualuxe_is_polylang_active() && function_exists( 'pll__' ) ) {
        return pll__( $string );
    } elseif ( aqualuxe_is_translatepress_active() ) {
        return $string; // TranslatePress automatically translates strings
    }
    
    return $string;
}

/**
 * Register string for translation
 *
 * @param string $string String to register
 * @param string $domain Text domain
 * @param string $name String name for WPML
 */
function aqualuxe_register_string( $string, $domain = 'aqualuxe', $name = '' ) {
    if ( aqualuxe_is_wpml_active() ) {
        do_action( 'wpml_register_single_string', $domain, $name ? $name : $string, $string );
    } elseif ( aqualuxe_is_polylang_active() && function_exists( 'pll_register_string' ) ) {
        pll_register_string( $name ? $name : $string, $string, $domain );
    }
    // TranslatePress doesn't require string registration
}

/**
 * Get translated post ID
 *
 * @param int    $post_id Post ID
 * @param string $language Language code
 * @return int
 */
function aqualuxe_get_translated_post_id( $post_id, $language = '' ) {
    if ( ! $language ) {
        $language = aqualuxe_get_current_language();
    }
    
    if ( aqualuxe_is_wpml_active() ) {
        return apply_filters( 'wpml_object_id', $post_id, get_post_type( $post_id ), true, $language );
    } elseif ( aqualuxe_is_polylang_active() ) {
        return pll_get_post( $post_id, $language );
    }
    
    return $post_id;
}

/**
 * Get translated term ID
 *
 * @param int    $term_id Term ID
 * @param string $taxonomy Taxonomy name
 * @param string $language Language code
 * @return int
 */
function aqualuxe_get_translated_term_id( $term_id, $taxonomy, $language = '' ) {
    if ( ! $language ) {
        $language = aqualuxe_get_current_language();
    }
    
    if ( aqualuxe_is_wpml_active() ) {
        return apply_filters( 'wpml_object_id', $term_id, $taxonomy, true, $language );
    } elseif ( aqualuxe_is_polylang_active() ) {
        return pll_get_term( $term_id, $language );
    }
    
    return $term_id;
}

/**
 * Add language switcher to top bar
 */
function aqualuxe_language_switcher() {
    if ( ! aqualuxe_is_multilingual() ) {
        return;
    }
    
    $current_language = aqualuxe_get_current_language();
    $languages = aqualuxe_get_available_languages();
    
    if ( empty( $languages ) || count( $languages ) <= 1 ) {
        return;
    }
    
    echo '<div class="language-switcher relative">';
    
    // Current language
    echo '<button class="language-switcher-toggle flex items-center text-sm focus:outline-none" aria-label="' . esc_attr__( 'Language Switcher', 'aqualuxe' ) . '">';
    
    if ( aqualuxe_get_language_flag_url( $current_language ) ) {
        echo '<img src="' . esc_url( aqualuxe_get_language_flag_url( $current_language ) ) . '" alt="' . esc_attr( aqualuxe_get_language_name( $current_language ) ) . '" class="w-4 h-4 mr-1">';
    }
    
    echo esc_html( aqualuxe_get_language_name( $current_language ) );
    echo '<i class="fas fa-chevron-down ml-1 text-xs"></i>';
    echo '</button>';
    
    // Language dropdown
    echo '<div class="language-switcher-dropdown absolute right-0 top-full mt-1 bg-white shadow-lg rounded-md py-2 hidden z-50 min-w-[120px]">';
    
    if ( aqualuxe_is_wpml_active() ) {
        foreach ( $languages as $code => $language ) {
            $active_class = $code === $current_language ? 'bg-gray-100' : '';
            
            echo '<a href="' . esc_url( $language['url'] ) . '" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100 ' . esc_attr( $active_class ) . '">';
            
            if ( ! empty( $language['country_flag_url'] ) ) {
                echo '<img src="' . esc_url( $language['country_flag_url'] ) . '" alt="' . esc_attr( $language['native_name'] ) . '" class="w-4 h-4 mr-2">';
            }
            
            echo esc_html( $language['native_name'] );
            echo '</a>';
        }
    } elseif ( aqualuxe_is_polylang_active() ) {
        foreach ( $languages as $language ) {
            $active_class = $language->slug === $current_language ? 'bg-gray-100' : '';
            
            echo '<a href="' . esc_url( $language->home_url ) . '" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100 ' . esc_attr( $active_class ) . '">';
            
            if ( ! empty( $language->flag_url ) ) {
                echo '<img src="' . esc_url( $language->flag_url ) . '" alt="' . esc_attr( $language->name ) . '" class="w-4 h-4 mr-2">';
            }
            
            echo esc_html( $language->name );
            echo '</a>';
        }
    } elseif ( aqualuxe_is_translatepress_active() ) {
        $trp = TRP_Translate_Press::get_trp_instance();
        $trp_languages = $trp->get_component( 'languages' );
        $language_names = $trp_languages->get_language_names( $languages );
        
        foreach ( $languages as $code ) {
            $active_class = $code === $current_language ? 'bg-gray-100' : '';
            $language_name = isset( $language_names[$code] ) ? $language_names[$code] : $code;
            
            echo '<a href="' . esc_url( aqualuxe_get_language_url( $code ) ) . '" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100 ' . esc_attr( $active_class ) . '">';
            
            if ( aqualuxe_get_language_flag_url( $code ) ) {
                echo '<img src="' . esc_url( aqualuxe_get_language_flag_url( $code ) ) . '" alt="' . esc_attr( $language_name ) . '" class="w-4 h-4 mr-2">';
            }
            
            echo esc_html( $language_name );
            echo '</a>';
        }
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Register strings for translation
 */
function aqualuxe_register_strings_for_translation() {
    // Register theme mod strings
    $theme_mods = array(
        'aqualuxe_phone_number' => esc_html__( 'Phone Number', 'aqualuxe' ),
        'aqualuxe_email' => esc_html__( 'Email Address', 'aqualuxe' ),
        'aqualuxe_address' => esc_html__( 'Address', 'aqualuxe' ),
        'aqualuxe_footer_about' => esc_html__( 'Footer About Text', 'aqualuxe' ),
        'aqualuxe_copyright_text' => esc_html__( 'Copyright Text', 'aqualuxe' ),
        'aqualuxe_maintenance_message' => esc_html__( 'Maintenance Message', 'aqualuxe' ),
    );
    
    foreach ( $theme_mods as $mod => $name ) {
        $value = get_theme_mod( $mod );
        if ( $value ) {
            aqualuxe_register_string( $value, 'aqualuxe', $name );
        }
    }
}
add_action( 'after_setup_theme', 'aqualuxe_register_strings_for_translation' );

/**
 * Filter theme mods for translation
 *
 * @param mixed  $value The theme mod value
 * @param string $name  The theme mod name
 * @return mixed
 */
function aqualuxe_translate_theme_mod( $value, $name ) {
    if ( ! aqualuxe_is_multilingual() ) {
        return $value;
    }
    
    $translatable_mods = array(
        'aqualuxe_phone_number',
        'aqualuxe_email',
        'aqualuxe_address',
        'aqualuxe_footer_about',
        'aqualuxe_copyright_text',
        'aqualuxe_maintenance_message',
    );
    
    if ( in_array( $name, $translatable_mods, true ) && is_string( $value ) ) {
        return aqualuxe_translate_string( $value, 'aqualuxe', $name );
    }
    
    return $value;
}
add_filter( 'theme_mod_aqualuxe_phone_number', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_email', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_address', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_footer_about', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_copyright_text', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_maintenance_message', 'aqualuxe_translate_theme_mod', 10, 2 );

/**
 * Add hreflang links to head
 */
function aqualuxe_add_hreflang_links() {
    if ( ! aqualuxe_is_multilingual() ) {
        return;
    }
    
    $current_language = aqualuxe_get_current_language();
    $languages = aqualuxe_get_available_languages();
    
    if ( empty( $languages ) || count( $languages ) <= 1 ) {
        return;
    }
    
    if ( aqualuxe_is_wpml_active() ) {
        // WPML handles hreflang automatically
        return;
    } elseif ( aqualuxe_is_polylang_active() ) {
        // Polylang handles hreflang automatically
        return;
    } elseif ( aqualuxe_is_translatepress_active() ) {
        // TranslatePress handles hreflang automatically
        return;
    }
    
    // Fallback implementation if needed
    $default_language = aqualuxe_get_default_language();
    
    foreach ( $languages as $code ) {
        $url = aqualuxe_get_language_url( $code );
        $hreflang = $code === $default_language ? 'x-default' : $code;
        
        echo '<link rel="alternate" hreflang="' . esc_attr( $hreflang ) . '" href="' . esc_url( $url ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_add_hreflang_links' );

/**
 * Add language class to body
 *
 * @param array $classes Body classes
 * @return array
 */
function aqualuxe_add_language_body_class( $classes ) {
    if ( aqualuxe_is_multilingual() ) {
        $current_language = aqualuxe_get_current_language();
        $classes[] = 'lang-' . $current_language;
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_add_language_body_class' );

/**
 * Filter menu locations for WPML
 *
 * @param int    $menu_id Menu ID
 * @param string $location Menu location
 * @return int
 */
function aqualuxe_wpml_menu_filter( $menu_id, $location ) {
    if ( ! aqualuxe_is_wpml_active() ) {
        return $menu_id;
    }
    
    $current_language = aqualuxe_get_current_language();
    $default_language = aqualuxe_get_default_language();
    
    if ( $current_language === $default_language ) {
        return $menu_id;
    }
    
    $translated_menu_id = apply_filters( 'wpml_object_id', $menu_id, 'nav_menu', false, $current_language );
    
    return $translated_menu_id ? $translated_menu_id : $menu_id;
}
add_filter( 'wp_nav_menu_args', function( $args ) {
    if ( ! aqualuxe_is_wpml_active() || empty( $args['theme_location'] ) ) {
        return $args;
    }
    
    $menu_id = $args['menu'];
    
    if ( ! $menu_id && ! empty( $args['theme_location'] ) ) {
        $locations = get_nav_menu_locations();
        $menu_id = isset( $locations[ $args['theme_location'] ] ) ? $locations[ $args['theme_location'] ] : 0;
    }
    
    if ( $menu_id ) {
        $translated_menu_id = aqualuxe_wpml_menu_filter( $menu_id, $args['theme_location'] );
        $args['menu'] = $translated_menu_id;
    }
    
    return $args;
});

/**
 * Add language information to AJAX requests
 *
 * @param array $data AJAX data
 * @return array
 */
function aqualuxe_add_language_to_ajax( $data ) {
    if ( aqualuxe_is_multilingual() ) {
        $data['language'] = aqualuxe_get_current_language();
    }
    
    return $data;
}
add_filter( 'aqualuxe_localize_script_data', 'aqualuxe_add_language_to_ajax' );

/**
 * Add language parameter to AJAX URL
 *
 * @param string $url AJAX URL
 * @return string
 */
function aqualuxe_add_language_to_ajax_url( $url ) {
    if ( aqualuxe_is_multilingual() ) {
        $url = add_query_arg( 'lang', aqualuxe_get_current_language(), $url );
    }
    
    return $url;
}
add_filter( 'aqualuxe_ajax_url', 'aqualuxe_add_language_to_ajax_url' );

/**
 * Filter search query by language
 *
 * @param WP_Query $query The query object
 * @return WP_Query
 */
function aqualuxe_filter_search_by_language( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
        if ( aqualuxe_is_wpml_active() ) {
            // WPML handles this automatically
        } elseif ( aqualuxe_is_polylang_active() ) {
            // Polylang handles this automatically
        } elseif ( aqualuxe_is_translatepress_active() ) {
            // TranslatePress doesn't need this
        }
    }
    
    return $query;
}
add_action( 'pre_get_posts', 'aqualuxe_filter_search_by_language' );

/**
 * Add language switcher to mobile menu
 */
function aqualuxe_mobile_language_switcher() {
    if ( ! aqualuxe_is_multilingual() ) {
        return;
    }
    
    $current_language = aqualuxe_get_current_language();
    $languages = aqualuxe_get_available_languages();
    
    if ( empty( $languages ) || count( $languages ) <= 1 ) {
        return;
    }
    
    echo '<div class="mobile-language-switcher mt-6 pt-6 border-t border-gray-200">';
    echo '<h3 class="text-sm font-bold text-gray-500 mb-4">' . esc_html__( 'Language', 'aqualuxe' ) . '</h3>';
    echo '<div class="language-options flex flex-wrap gap-2">';
    
    if ( aqualuxe_is_wpml_active() ) {
        foreach ( $languages as $code => $language ) {
            $active_class = $code === $current_language ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-800';
            
            echo '<a href="' . esc_url( $language['url'] ) . '" class="flex items-center px-3 py-1 rounded-full text-sm ' . esc_attr( $active_class ) . '">';
            
            if ( ! empty( $language['country_flag_url'] ) ) {
                echo '<img src="' . esc_url( $language['country_flag_url'] ) . '" alt="' . esc_attr( $language['native_name'] ) . '" class="w-4 h-4 mr-1">';
            }
            
            echo esc_html( $language['native_name'] );
            echo '</a>';
        }
    } elseif ( aqualuxe_is_polylang_active() ) {
        foreach ( $languages as $language ) {
            $active_class = $language->slug === $current_language ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-800';
            
            echo '<a href="' . esc_url( $language->home_url ) . '" class="flex items-center px-3 py-1 rounded-full text-sm ' . esc_attr( $active_class ) . '">';
            
            if ( ! empty( $language->flag_url ) ) {
                echo '<img src="' . esc_url( $language->flag_url ) . '" alt="' . esc_attr( $language->name ) . '" class="w-4 h-4 mr-1">';
            }
            
            echo esc_html( $language->name );
            echo '</a>';
        }
    } elseif ( aqualuxe_is_translatepress_active() ) {
        $trp = TRP_Translate_Press::get_trp_instance();
        $trp_languages = $trp->get_component( 'languages' );
        $language_names = $trp_languages->get_language_names( $languages );
        
        foreach ( $languages as $code ) {
            $active_class = $code === $current_language ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-800';
            $language_name = isset( $language_names[$code] ) ? $language_names[$code] : $code;
            
            echo '<a href="' . esc_url( aqualuxe_get_language_url( $code ) ) . '" class="flex items-center px-3 py-1 rounded-full text-sm ' . esc_attr( $active_class ) . '">';
            
            if ( aqualuxe_get_language_flag_url( $code ) ) {
                echo '<img src="' . esc_url( aqualuxe_get_language_flag_url( $code ) ) . '" alt="' . esc_attr( $language_name ) . '" class="w-4 h-4 mr-1">';
            }
            
            echo esc_html( $language_name );
            echo '</a>';
        }
    }
    
    echo '</div>';
    echo '</div>';
}
add_action( 'aqualuxe_after_mobile_menu', 'aqualuxe_mobile_language_switcher' );

/**
 * Add language switcher to footer
 */
function aqualuxe_footer_language_switcher() {
    if ( ! aqualuxe_is_multilingual() || ! get_theme_mod( 'aqualuxe_footer_language_switcher', true ) ) {
        return;
    }
    
    $current_language = aqualuxe_get_current_language();
    $languages = aqualuxe_get_available_languages();
    
    if ( empty( $languages ) || count( $languages ) <= 1 ) {
        return;
    }
    
    echo '<div class="footer-language-switcher mt-4">';
    echo '<div class="language-options flex flex-wrap gap-2 justify-center md:justify-end">';
    
    if ( aqualuxe_is_wpml_active() ) {
        foreach ( $languages as $code => $language ) {
            $active_class = $code === $current_language ? 'bg-primary-700 text-white' : 'bg-primary-800 text-gray-300 hover:bg-primary-700 hover:text-white';
            
            echo '<a href="' . esc_url( $language['url'] ) . '" class="flex items-center px-3 py-1 rounded-full text-xs transition-colors ' . esc_attr( $active_class ) . '">';
            
            if ( ! empty( $language['country_flag_url'] ) ) {
                echo '<img src="' . esc_url( $language['country_flag_url'] ) . '" alt="' . esc_attr( $language['native_name'] ) . '" class="w-3 h-3 mr-1">';
            }
            
            echo esc_html( $language['native_name'] );
            echo '</a>';
        }
    } elseif ( aqualuxe_is_polylang_active() ) {
        foreach ( $languages as $language ) {
            $active_class = $language->slug === $current_language ? 'bg-primary-700 text-white' : 'bg-primary-800 text-gray-300 hover:bg-primary-700 hover:text-white';
            
            echo '<a href="' . esc_url( $language->home_url ) . '" class="flex items-center px-3 py-1 rounded-full text-xs transition-colors ' . esc_attr( $active_class ) . '">';
            
            if ( ! empty( $language->flag_url ) ) {
                echo '<img src="' . esc_url( $language->flag_url ) . '" alt="' . esc_attr( $language->name ) . '" class="w-3 h-3 mr-1">';
            }
            
            echo esc_html( $language->name );
            echo '</a>';
        }
    } elseif ( aqualuxe_is_translatepress_active() ) {
        $trp = TRP_Translate_Press::get_trp_instance();
        $trp_languages = $trp->get_component( 'languages' );
        $language_names = $trp_languages->get_language_names( $languages );
        
        foreach ( $languages as $code ) {
            $active_class = $code === $current_language ? 'bg-primary-700 text-white' : 'bg-primary-800 text-gray-300 hover:bg-primary-700 hover:text-white';
            $language_name = isset( $language_names[$code] ) ? $language_names[$code] : $code;
            
            echo '<a href="' . esc_url( aqualuxe_get_language_url( $code ) ) . '" class="flex items-center px-3 py-1 rounded-full text-xs transition-colors ' . esc_attr( $active_class ) . '">';
            
            if ( aqualuxe_get_language_flag_url( $code ) ) {
                echo '<img src="' . esc_url( aqualuxe_get_language_flag_url( $code ) ) . '" alt="' . esc_attr( $language_name ) . '" class="w-3 h-3 mr-1">';
            }
            
            echo esc_html( $language_name );
            echo '</a>';
        }
    }
    
    echo '</div>';
    echo '</div>';
}
add_action( 'aqualuxe_after_copyright', 'aqualuxe_footer_language_switcher' );

/**
 * Add language switcher to customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object
 */
function aqualuxe_language_switcher_customizer( $wp_customize ) {
    if ( ! aqualuxe_is_multilingual() ) {
        return;
    }
    
    $wp_customize->add_setting(
        'aqualuxe_footer_language_switcher',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_language_switcher',
        array(
            'label'       => esc_html__( 'Show Language Switcher in Footer', 'aqualuxe' ),
            'description' => esc_html__( 'Display language switcher in the footer', 'aqualuxe' ),
            'section'     => 'aqualuxe_footer_options',
            'type'        => 'checkbox',
        )
    );
}
add_action( 'customize_register', 'aqualuxe_language_switcher_customizer' );