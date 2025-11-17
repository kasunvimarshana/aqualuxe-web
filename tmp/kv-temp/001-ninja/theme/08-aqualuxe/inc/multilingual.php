<?php
/**
 * Multilingual Support for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Check if a multilingual plugin is active
 */
function aqualuxe_is_multilingual() {
    // Check for WPML
    if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
        return 'wpml';
    }
    
    // Check for Polylang
    if ( function_exists( 'pll_current_language' ) ) {
        return 'polylang';
    }
    
    // Check for TranslatePress
    if ( class_exists( 'TRP_Translate_Press' ) ) {
        return 'translatepress';
    }
    
    // Check for Weglot
    if ( function_exists( 'weglot_get_current_language' ) ) {
        return 'weglot';
    }
    
    return false;
}

/**
 * Get current language
 */
function aqualuxe_get_current_language() {
    $multilingual = aqualuxe_is_multilingual();
    
    switch ( $multilingual ) {
        case 'wpml':
            return apply_filters( 'wpml_current_language', null );
        
        case 'polylang':
            return pll_current_language();
        
        case 'translatepress':
            global $TRP_LANGUAGE;
            return $TRP_LANGUAGE;
        
        case 'weglot':
            return weglot_get_current_language();
        
        default:
            return get_locale();
    }
}

/**
 * Get default language
 */
function aqualuxe_get_default_language() {
    $multilingual = aqualuxe_is_multilingual();
    
    switch ( $multilingual ) {
        case 'wpml':
            return apply_filters( 'wpml_default_language', null );
        
        case 'polylang':
            return pll_default_language();
        
        case 'translatepress':
            $settings = get_option( 'trp_settings', array() );
            return isset( $settings['default-language'] ) ? $settings['default-language'] : 'en_US';
        
        case 'weglot':
            return weglot_get_original_language();
        
        default:
            return get_locale();
    }
}

/**
 * Get available languages
 */
function aqualuxe_get_available_languages() {
    $multilingual = aqualuxe_is_multilingual();
    
    switch ( $multilingual ) {
        case 'wpml':
            return apply_filters( 'wpml_active_languages', array() );
        
        case 'polylang':
            return pll_languages_list( array( 'fields' => 'slug' ) );
        
        case 'translatepress':
            $settings = get_option( 'trp_settings', array() );
            return isset( $settings['translation-languages'] ) ? $settings['translation-languages'] : array( 'en_US' );
        
        case 'weglot':
            return weglot_get_destination_languages();
        
        default:
            return array( get_locale() );
    }
}

/**
 * Get language name
 */
function aqualuxe_get_language_name( $language_code ) {
    $multilingual = aqualuxe_is_multilingual();
    
    switch ( $multilingual ) {
        case 'wpml':
            $languages = apply_filters( 'wpml_active_languages', array() );
            return isset( $languages[ $language_code ]['native_name'] ) ? $languages[ $language_code ]['native_name'] : $language_code;
        
        case 'polylang':
            if ( function_exists( 'pll_languages_list' ) ) {
                $languages = pll_languages_list( array( 'fields' => 'name' ) );
                $slugs = pll_languages_list( array( 'fields' => 'slug' ) );
                $key = array_search( $language_code, $slugs, true );
                return false !== $key && isset( $languages[ $key ] ) ? $languages[ $key ] : $language_code;
            }
            return $language_code;
        
        case 'translatepress':
            $trp = TRP_Translate_Press::get_trp_instance();
            $trp_languages = $trp->get_component( 'languages' );
            $language_names = $trp_languages->get_language_names( array( $language_code ) );
            return isset( $language_names[ $language_code ] ) ? $language_names[ $language_code ] : $language_code;
        
        case 'weglot':
            $language_entry = weglot_get_language_from_code( $language_code );
            return $language_entry ? $language_entry->getLocalName() : $language_code;
        
        default:
            $languages = get_available_languages();
            $locale_data = include WP_LANG_DIR . '/languages/locale-info.php';
            
            if ( isset( $locale_data[ $language_code ]['native_name'] ) ) {
                return $locale_data[ $language_code ]['native_name'];
            }
            
            return $language_code;
    }
}

/**
 * Get language URL
 */
function aqualuxe_get_language_url( $language_code ) {
    $multilingual = aqualuxe_is_multilingual();
    
    switch ( $multilingual ) {
        case 'wpml':
            return apply_filters( 'wpml_permalink', home_url(), $language_code );
        
        case 'polylang':
            return pll_home_url( $language_code );
        
        case 'translatepress':
            $trp = TRP_Translate_Press::get_trp_instance();
            $url_converter = $trp->get_component( 'url_converter' );
            return $url_converter->get_url_for_language( $language_code, home_url() );
        
        case 'weglot':
            return weglot_get_url_for_language( $language_code );
        
        default:
            return home_url();
    }
}

/**
 * Get translated post ID
 */
function aqualuxe_get_translated_post_id( $post_id, $language_code = '' ) {
    if ( empty( $language_code ) ) {
        $language_code = aqualuxe_get_current_language();
    }
    
    $multilingual = aqualuxe_is_multilingual();
    
    switch ( $multilingual ) {
        case 'wpml':
            return apply_filters( 'wpml_object_id', $post_id, get_post_type( $post_id ), true, $language_code );
        
        case 'polylang':
            return pll_get_post( $post_id, $language_code );
        
        case 'translatepress':
        case 'weglot':
        default:
            return $post_id;
    }
}

/**
 * Get translated term ID
 */
function aqualuxe_get_translated_term_id( $term_id, $taxonomy, $language_code = '' ) {
    if ( empty( $language_code ) ) {
        $language_code = aqualuxe_get_current_language();
    }
    
    $multilingual = aqualuxe_is_multilingual();
    
    switch ( $multilingual ) {
        case 'wpml':
            return apply_filters( 'wpml_object_id', $term_id, $taxonomy, true, $language_code );
        
        case 'polylang':
            return pll_get_term( $term_id, $language_code );
        
        case 'translatepress':
        case 'weglot':
        default:
            return $term_id;
    }
}

/**
 * Register strings for translation
 */
function aqualuxe_register_string( $context, $name, $value ) {
    $multilingual = aqualuxe_is_multilingual();
    
    switch ( $multilingual ) {
        case 'wpml':
            do_action( 'wpml_register_single_string', $context, $name, $value );
            break;
        
        case 'polylang':
            if ( function_exists( 'pll_register_string' ) ) {
                pll_register_string( $name, $value, $context );
            }
            break;
        
        case 'translatepress':
            // TranslatePress automatically detects strings, no registration needed
            break;
        
        case 'weglot':
            // Weglot automatically detects strings, no registration needed
            break;
    }
}

/**
 * Translate registered string
 */
function aqualuxe_translate_string( $context, $name, $value, $language_code = '' ) {
    if ( empty( $language_code ) ) {
        $language_code = aqualuxe_get_current_language();
    }
    
    $multilingual = aqualuxe_is_multilingual();
    
    switch ( $multilingual ) {
        case 'wpml':
            return apply_filters( 'wpml_translate_single_string', $value, $context, $name, $language_code );
        
        case 'polylang':
            if ( function_exists( 'pll__' ) ) {
                return pll__( $value );
            }
            return $value;
        
        case 'translatepress':
        case 'weglot':
        default:
            return $value;
    }
}

/**
 * Register theme strings for translation
 */
function aqualuxe_register_theme_strings() {
    // Register theme options
    $options = get_option( 'aqualuxe_theme_options', array() );
    
    if ( ! empty( $options ) ) {
        // Header
        if ( isset( $options['header_button_text'] ) ) {
            aqualuxe_register_string( 'AquaLuxe Theme', 'Header Button Text', $options['header_button_text'] );
        }
        
        // Footer
        if ( isset( $options['footer_copyright'] ) ) {
            aqualuxe_register_string( 'AquaLuxe Theme', 'Footer Copyright', $options['footer_copyright'] );
        }
        
        // Custom Code
        if ( isset( $options['header_code'] ) ) {
            aqualuxe_register_string( 'AquaLuxe Theme', 'Header Code', $options['header_code'] );
        }
        
        if ( isset( $options['footer_code'] ) ) {
            aqualuxe_register_string( 'AquaLuxe Theme', 'Footer Code', $options['footer_code'] );
        }
    }
}
add_action( 'after_setup_theme', 'aqualuxe_register_theme_strings' );

/**
 * Add language switcher to header
 */
function aqualuxe_language_switcher() {
    $multilingual = aqualuxe_is_multilingual();
    
    if ( ! $multilingual ) {
        return;
    }
    
    $current_language = aqualuxe_get_current_language();
    $languages = aqualuxe_get_available_languages();
    
    if ( empty( $languages ) || count( $languages ) <= 1 ) {
        return;
    }
    
    echo '<div class="language-switcher relative">';
    echo '<button class="language-switcher-toggle flex items-center text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300" aria-expanded="false">';
    
    // Display current language
    switch ( $multilingual ) {
        case 'wpml':
            $current_language_data = apply_filters( 'wpml_current_language', null );
            $current_language_name = $languages[ $current_language_data ]['native_name'];
            $current_language_flag = $languages[ $current_language_data ]['country_flag_url'];
            
            if ( $current_language_flag ) {
                echo '<img src="' . esc_url( $current_language_flag ) . '" alt="' . esc_attr( $current_language_name ) . '" class="w-4 h-4 mr-1">';
            }
            
            echo esc_html( $current_language_name );
            break;
        
        case 'polylang':
            $current_language_name = aqualuxe_get_language_name( $current_language );
            echo esc_html( $current_language_name );
            break;
        
        case 'translatepress':
        case 'weglot':
        default:
            $current_language_name = aqualuxe_get_language_name( $current_language );
            echo esc_html( $current_language_name );
            break;
    }
    
    echo '<svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>';
    echo '</button>';
    
    echo '<div class="language-switcher-dropdown absolute right-0 mt-2 py-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 hidden">';
    
    // Display language options
    switch ( $multilingual ) {
        case 'wpml':
            foreach ( $languages as $code => $language ) {
                $active_class = $code === $current_language ? ' font-semibold text-primary-600 dark:text-primary-400' : '';
                
                echo '<a href="' . esc_url( $language['url'] ) . '" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' . esc_attr( $active_class ) . '">';
                
                if ( $language['country_flag_url'] ) {
                    echo '<img src="' . esc_url( $language['country_flag_url'] ) . '" alt="' . esc_attr( $language['native_name'] ) . '" class="w-4 h-4 inline-block mr-1">';
                }
                
                echo esc_html( $language['native_name'] );
                echo '</a>';
            }
            break;
        
        case 'polylang':
            foreach ( $languages as $code ) {
                $name = aqualuxe_get_language_name( $code );
                $url = aqualuxe_get_language_url( $code );
                $active_class = $code === $current_language ? ' font-semibold text-primary-600 dark:text-primary-400' : '';
                
                echo '<a href="' . esc_url( $url ) . '" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' . esc_attr( $active_class ) . '">';
                echo esc_html( $name );
                echo '</a>';
            }
            break;
        
        case 'translatepress':
        case 'weglot':
        default:
            foreach ( $languages as $code ) {
                $name = aqualuxe_get_language_name( $code );
                $url = aqualuxe_get_language_url( $code );
                $active_class = $code === $current_language ? ' font-semibold text-primary-600 dark:text-primary-400' : '';
                
                echo '<a href="' . esc_url( $url ) . '" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' . esc_attr( $active_class ) . '">';
                echo esc_html( $name );
                echo '</a>';
            }
            break;
    }
    
    echo '</div>';
    echo '</div>';
    
    // Add JavaScript for dropdown toggle
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const languageToggle = document.querySelector('.language-switcher-toggle');
            const languageDropdown = document.querySelector('.language-switcher-dropdown');
            
            if (languageToggle && languageDropdown) {
                languageToggle.addEventListener('click', function() {
                    const expanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !expanded);
                    languageDropdown.classList.toggle('hidden');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!event.target.closest('.language-switcher')) {
                        languageToggle.setAttribute('aria-expanded', 'false');
                        languageDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
    <?php
}

/**
 * Add hreflang tags to head
 */
function aqualuxe_add_hreflang_tags() {
    $multilingual = aqualuxe_is_multilingual();
    
    if ( ! $multilingual ) {
        return;
    }
    
    $languages = aqualuxe_get_available_languages();
    
    if ( empty( $languages ) || count( $languages ) <= 1 ) {
        return;
    }
    
    $current_url = home_url( add_query_arg( null, null ) );
    
    switch ( $multilingual ) {
        case 'wpml':
            // WPML adds hreflang tags automatically
            break;
        
        case 'polylang':
            // Polylang adds hreflang tags automatically
            break;
        
        case 'translatepress':
            // TranslatePress adds hreflang tags automatically
            break;
        
        case 'weglot':
            // Weglot adds hreflang tags automatically
            break;
        
        default:
            foreach ( $languages as $language ) {
                $language_url = aqualuxe_get_language_url( $language );
                echo '<link rel="alternate" hreflang="' . esc_attr( $language ) . '" href="' . esc_url( $language_url ) . '" />' . "\n";
            }
            break;
    }
}
add_action( 'wp_head', 'aqualuxe_add_hreflang_tags' );

/**
 * Filter menu items for current language
 */
function aqualuxe_filter_menu_items( $items, $args ) {
    $multilingual = aqualuxe_is_multilingual();
    
    if ( ! $multilingual ) {
        return $items;
    }
    
    $current_language = aqualuxe_get_current_language();
    
    switch ( $multilingual ) {
        case 'wpml':
        case 'polylang':
            // WPML and Polylang filter menu items automatically
            return $items;
        
        case 'translatepress':
        case 'weglot':
        default:
            // No filtering needed for TranslatePress and Weglot
            return $items;
    }
}
add_filter( 'wp_nav_menu_objects', 'aqualuxe_filter_menu_items', 10, 2 );

/**
 * Filter widgets for current language
 */
function aqualuxe_filter_widgets( $instance, $widget, $args ) {
    $multilingual = aqualuxe_is_multilingual();
    
    if ( ! $multilingual ) {
        return $instance;
    }
    
    $current_language = aqualuxe_get_current_language();
    
    switch ( $multilingual ) {
        case 'wpml':
        case 'polylang':
            // WPML and Polylang filter widgets automatically
            return $instance;
        
        case 'translatepress':
        case 'weglot':
        default:
            // No filtering needed for TranslatePress and Weglot
            return $instance;
    }
}
add_filter( 'widget_display_callback', 'aqualuxe_filter_widgets', 10, 3 );

/**
 * Add language class to body
 */
function aqualuxe_language_body_class( $classes ) {
    $multilingual = aqualuxe_is_multilingual();
    
    if ( ! $multilingual ) {
        return $classes;
    }
    
    $current_language = aqualuxe_get_current_language();
    $classes[] = 'lang-' . $current_language;
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_language_body_class' );

/**
 * Add RTL support for languages that read right-to-left
 */
function aqualuxe_rtl_support() {
    $multilingual = aqualuxe_is_multilingual();
    
    if ( ! $multilingual ) {
        return;
    }
    
    $current_language = aqualuxe_get_current_language();
    $rtl_languages = array( 'ar', 'fa', 'he', 'ur' ); // Arabic, Persian, Hebrew, Urdu
    
    // Check if current language is RTL
    $is_rtl = in_array( substr( $current_language, 0, 2 ), $rtl_languages, true );
    
    if ( $is_rtl ) {
        // Add RTL stylesheet
        wp_enqueue_style( 'aqualuxe-rtl', AQUALUXE_URI . 'assets/css/rtl.css', array( 'aqualuxe-style' ), AQUALUXE_VERSION );
    }
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_rtl_support' );

/**
 * Add language switcher to mobile menu
 */
function aqualuxe_mobile_language_switcher() {
    $multilingual = aqualuxe_is_multilingual();
    
    if ( ! $multilingual ) {
        return;
    }
    
    $current_language = aqualuxe_get_current_language();
    $languages = aqualuxe_get_available_languages();
    
    if ( empty( $languages ) || count( $languages ) <= 1 ) {
        return;
    }
    
    echo '<div class="mobile-language-switcher py-2 mt-4 border-t border-gray-200 dark:border-gray-700">';
    echo '<div class="px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400">' . esc_html__( 'Language', 'aqualuxe' ) . '</div>';
    
    // Display language options
    switch ( $multilingual ) {
        case 'wpml':
            foreach ( $languages as $code => $language ) {
                $active_class = $code === $current_language ? ' font-semibold text-primary-600 dark:text-primary-400' : '';
                
                echo '<a href="' . esc_url( $language['url'] ) . '" class="block px-4 py-2 text-base text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' . esc_attr( $active_class ) . '">';
                
                if ( $language['country_flag_url'] ) {
                    echo '<img src="' . esc_url( $language['country_flag_url'] ) . '" alt="' . esc_attr( $language['native_name'] ) . '" class="w-4 h-4 inline-block mr-1">';
                }
                
                echo esc_html( $language['native_name'] );
                echo '</a>';
            }
            break;
        
        case 'polylang':
            foreach ( $languages as $code ) {
                $name = aqualuxe_get_language_name( $code );
                $url = aqualuxe_get_language_url( $code );
                $active_class = $code === $current_language ? ' font-semibold text-primary-600 dark:text-primary-400' : '';
                
                echo '<a href="' . esc_url( $url ) . '" class="block px-4 py-2 text-base text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' . esc_attr( $active_class ) . '">';
                echo esc_html( $name );
                echo '</a>';
            }
            break;
        
        case 'translatepress':
        case 'weglot':
        default:
            foreach ( $languages as $code ) {
                $name = aqualuxe_get_language_name( $code );
                $url = aqualuxe_get_language_url( $code );
                $active_class = $code === $current_language ? ' font-semibold text-primary-600 dark:text-primary-400' : '';
                
                echo '<a href="' . esc_url( $url ) . '" class="block px-4 py-2 text-base text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' . esc_attr( $active_class ) . '">';
                echo esc_html( $name );
                echo '</a>';
            }
            break;
    }
    
    echo '</div>';
}