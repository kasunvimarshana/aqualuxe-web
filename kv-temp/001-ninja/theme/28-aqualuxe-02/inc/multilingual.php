<?php
/**
 * Multilingual Support for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Initialize multilingual support
 */
function aqualuxe_multilingual_init() {
    // Check if WPML is active
    if ( function_exists( 'icl_object_id' ) ) {
        // Add WPML compatibility
        add_action( 'after_setup_theme', 'aqualuxe_wpml_compatibility' );
    }
    
    // Check if Polylang is active
    if ( function_exists( 'pll_the_languages' ) ) {
        // Add Polylang compatibility
        add_action( 'after_setup_theme', 'aqualuxe_polylang_compatibility' );
    }
    
    // Register theme textdomain
    load_theme_textdomain( 'aqualuxe', AQUALUXE_DIR . 'languages' );
}
add_action( 'init', 'aqualuxe_multilingual_init', 1 );

/**
 * Add WPML compatibility
 */
function aqualuxe_wpml_compatibility() {
    // Register strings for translation
    if ( function_exists( 'icl_register_string' ) ) {
        // Register theme mod strings
        $theme_mods = array(
            'aqualuxe_phone_number'   => __( 'Phone Number', 'aqualuxe' ),
            'aqualuxe_email'          => __( 'Email Address', 'aqualuxe' ),
            'aqualuxe_address'        => __( 'Address', 'aqualuxe' ),
            'aqualuxe_footer_text'    => __( 'Footer Text', 'aqualuxe' ),
            'aqualuxe_shipping_content' => __( 'Shipping Content', 'aqualuxe' ),
        );
        
        foreach ( $theme_mods as $key => $name ) {
            $value = get_theme_mod( $key );
            if ( $value ) {
                icl_register_string( 'Theme Mod', $name, $value );
            }
        }
    }
}

/**
 * Add Polylang compatibility
 */
function aqualuxe_polylang_compatibility() {
    // Register strings for translation
    if ( function_exists( 'pll_register_string' ) ) {
        // Register theme mod strings
        $theme_mods = array(
            'aqualuxe_phone_number'   => __( 'Phone Number', 'aqualuxe' ),
            'aqualuxe_email'          => __( 'Email Address', 'aqualuxe' ),
            'aqualuxe_address'        => __( 'Address', 'aqualuxe' ),
            'aqualuxe_footer_text'    => __( 'Footer Text', 'aqualuxe' ),
            'aqualuxe_shipping_content' => __( 'Shipping Content', 'aqualuxe' ),
        );
        
        foreach ( $theme_mods as $key => $name ) {
            $value = get_theme_mod( $key );
            if ( $value ) {
                pll_register_string( $name, $value, 'AquaLuxe Theme' );
            }
        }
    }
}

/**
 * Translate theme mod string
 */
function aqualuxe_translate_theme_mod( $key ) {
    $value = get_theme_mod( $key );
    
    if ( ! $value ) {
        return '';
    }
    
    // Translate with WPML
    if ( function_exists( 'icl_t' ) ) {
        $translated_labels = array(
            'aqualuxe_phone_number'   => __( 'Phone Number', 'aqualuxe' ),
            'aqualuxe_email'          => __( 'Email Address', 'aqualuxe' ),
            'aqualuxe_address'        => __( 'Address', 'aqualuxe' ),
            'aqualuxe_footer_text'    => __( 'Footer Text', 'aqualuxe' ),
            'aqualuxe_shipping_content' => __( 'Shipping Content', 'aqualuxe' ),
        );
        
        if ( isset( $translated_labels[$key] ) ) {
            return icl_t( 'Theme Mod', $translated_labels[$key], $value );
        }
    }
    
    // Translate with Polylang
    if ( function_exists( 'pll__' ) ) {
        $translated_labels = array(
            'aqualuxe_phone_number'   => __( 'Phone Number', 'aqualuxe' ),
            'aqualuxe_email'          => __( 'Email Address', 'aqualuxe' ),
            'aqualuxe_address'        => __( 'Address', 'aqualuxe' ),
            'aqualuxe_footer_text'    => __( 'Footer Text', 'aqualuxe' ),
            'aqualuxe_shipping_content' => __( 'Shipping Content', 'aqualuxe' ),
        );
        
        if ( isset( $translated_labels[$key] ) ) {
            return pll__( $value );
        }
    }
    
    return $value;
}

/**
 * Get translated post ID
 */
function aqualuxe_get_translated_post_id( $post_id, $post_type = 'post' ) {
    // WPML
    if ( function_exists( 'icl_object_id' ) ) {
        return icl_object_id( $post_id, $post_type, true );
    }
    
    // Polylang
    if ( function_exists( 'pll_get_post' ) ) {
        $translated_id = pll_get_post( $post_id );
        return $translated_id ? $translated_id : $post_id;
    }
    
    return $post_id;
}

/**
 * Get translated term ID
 */
function aqualuxe_get_translated_term_id( $term_id, $taxonomy ) {
    // WPML
    if ( function_exists( 'icl_object_id' ) ) {
        return icl_object_id( $term_id, $taxonomy, true );
    }
    
    // Polylang
    if ( function_exists( 'pll_get_term' ) ) {
        $translated_id = pll_get_term( $term_id );
        return $translated_id ? $translated_id : $term_id;
    }
    
    return $term_id;
}

/**
 * Add language switcher to header
 */
function aqualuxe_language_switcher() {
    // WPML language switcher
    if ( function_exists( 'icl_object_id' ) ) {
        do_action( 'wpml_add_language_selector' );
    }
    
    // Polylang language switcher
    if ( function_exists( 'pll_the_languages' ) ) {
        echo '<div class="language-switcher">';
        pll_the_languages( array(
            'dropdown'       => 1,
            'show_names'     => 1,
            'display_names_as' => 'name',
            'hide_if_empty'  => 0,
        ) );
        echo '</div>';
    }
}

/**
 * Filter WooCommerce product query by language
 */
function aqualuxe_filter_woocommerce_products_by_language( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_post_type_archive( 'product' ) || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) ) ) {
        // WPML
        if ( function_exists( 'icl_object_id' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
            $query->set( 'suppress_filters', false );
        }
        
        // Polylang
        if ( function_exists( 'pll_current_language' ) ) {
            $query->set( 'lang', pll_current_language() );
        }
    }
    
    return $query;
}
add_filter( 'pre_get_posts', 'aqualuxe_filter_woocommerce_products_by_language' );

/**
 * Filter custom post types query by language
 */
function aqualuxe_filter_custom_post_types_by_language( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        $post_types = array( 'service', 'event', 'testimonial', 'team', 'project', 'faq' );
        
        if ( is_post_type_archive( $post_types ) || is_tax( array( 'service_category', 'event_category', 'project_category', 'team_category', 'faq_category' ) ) ) {
            // WPML
            if ( function_exists( 'icl_object_id' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
                $query->set( 'suppress_filters', false );
            }
            
            // Polylang
            if ( function_exists( 'pll_current_language' ) ) {
                $query->set( 'lang', pll_current_language() );
            }
        }
    }
    
    return $query;
}
add_filter( 'pre_get_posts', 'aqualuxe_filter_custom_post_types_by_language' );

/**
 * Register custom post types and taxonomies with WPML
 */
function aqualuxe_register_wpml_strings() {
    if ( function_exists( 'icl_object_id' ) ) {
        // Register custom post types
        if ( function_exists( 'icl_register_string' ) ) {
            $post_types = array(
                'service'     => array(
                    'name'          => __( 'Service', 'aqualuxe' ),
                    'singular_name' => __( 'Service', 'aqualuxe' ),
                    'plural_name'   => __( 'Services', 'aqualuxe' ),
                ),
                'event'       => array(
                    'name'          => __( 'Event', 'aqualuxe' ),
                    'singular_name' => __( 'Event', 'aqualuxe' ),
                    'plural_name'   => __( 'Events', 'aqualuxe' ),
                ),
                'testimonial' => array(
                    'name'          => __( 'Testimonial', 'aqualuxe' ),
                    'singular_name' => __( 'Testimonial', 'aqualuxe' ),
                    'plural_name'   => __( 'Testimonials', 'aqualuxe' ),
                ),
                'team'        => array(
                    'name'          => __( 'Team Member', 'aqualuxe' ),
                    'singular_name' => __( 'Team Member', 'aqualuxe' ),
                    'plural_name'   => __( 'Team Members', 'aqualuxe' ),
                ),
                'project'     => array(
                    'name'          => __( 'Project', 'aqualuxe' ),
                    'singular_name' => __( 'Project', 'aqualuxe' ),
                    'plural_name'   => __( 'Projects', 'aqualuxe' ),
                ),
                'faq'         => array(
                    'name'          => __( 'FAQ', 'aqualuxe' ),
                    'singular_name' => __( 'FAQ', 'aqualuxe' ),
                    'plural_name'   => __( 'FAQs', 'aqualuxe' ),
                ),
            );
            
            foreach ( $post_types as $post_type => $labels ) {
                icl_register_string( 'AquaLuxe CPT', $post_type . '_name', $labels['name'] );
                icl_register_string( 'AquaLuxe CPT', $post_type . '_singular_name', $labels['singular_name'] );
                icl_register_string( 'AquaLuxe CPT', $post_type . '_plural_name', $labels['plural_name'] );
            }
            
            // Register taxonomies
            $taxonomies = array(
                'service_category' => array(
                    'name'          => __( 'Service Category', 'aqualuxe' ),
                    'singular_name' => __( 'Service Category', 'aqualuxe' ),
                    'plural_name'   => __( 'Service Categories', 'aqualuxe' ),
                ),
                'service_tag'      => array(
                    'name'          => __( 'Service Tag', 'aqualuxe' ),
                    'singular_name' => __( 'Service Tag', 'aqualuxe' ),
                    'plural_name'   => __( 'Service Tags', 'aqualuxe' ),
                ),
                'event_category'   => array(
                    'name'          => __( 'Event Category', 'aqualuxe' ),
                    'singular_name' => __( 'Event Category', 'aqualuxe' ),
                    'plural_name'   => __( 'Event Categories', 'aqualuxe' ),
                ),
                'event_tag'        => array(
                    'name'          => __( 'Event Tag', 'aqualuxe' ),
                    'singular_name' => __( 'Event Tag', 'aqualuxe' ),
                    'plural_name'   => __( 'Event Tags', 'aqualuxe' ),
                ),
                'project_category' => array(
                    'name'          => __( 'Project Category', 'aqualuxe' ),
                    'singular_name' => __( 'Project Category', 'aqualuxe' ),
                    'plural_name'   => __( 'Project Categories', 'aqualuxe' ),
                ),
                'project_tag'      => array(
                    'name'          => __( 'Project Tag', 'aqualuxe' ),
                    'singular_name' => __( 'Project Tag', 'aqualuxe' ),
                    'plural_name'   => __( 'Project Tags', 'aqualuxe' ),
                ),
                'team_category'    => array(
                    'name'          => __( 'Team Category', 'aqualuxe' ),
                    'singular_name' => __( 'Team Category', 'aqualuxe' ),
                    'plural_name'   => __( 'Team Categories', 'aqualuxe' ),
                ),
                'faq_category'     => array(
                    'name'          => __( 'FAQ Category', 'aqualuxe' ),
                    'singular_name' => __( 'FAQ Category', 'aqualuxe' ),
                    'plural_name'   => __( 'FAQ Categories', 'aqualuxe' ),
                ),
            );
            
            foreach ( $taxonomies as $taxonomy => $labels ) {
                icl_register_string( 'AquaLuxe Taxonomy', $taxonomy . '_name', $labels['name'] );
                icl_register_string( 'AquaLuxe Taxonomy', $taxonomy . '_singular_name', $labels['singular_name'] );
                icl_register_string( 'AquaLuxe Taxonomy', $taxonomy . '_plural_name', $labels['plural_name'] );
            }
        }
    }
}
add_action( 'init', 'aqualuxe_register_wpml_strings', 20 );

/**
 * Register custom post types and taxonomies with Polylang
 */
function aqualuxe_register_polylang_strings() {
    if ( function_exists( 'pll_register_string' ) ) {
        // Register custom post types
        $post_types = array(
            'service'     => array(
                'name'          => __( 'Service', 'aqualuxe' ),
                'singular_name' => __( 'Service', 'aqualuxe' ),
                'plural_name'   => __( 'Services', 'aqualuxe' ),
            ),
            'event'       => array(
                'name'          => __( 'Event', 'aqualuxe' ),
                'singular_name' => __( 'Event', 'aqualuxe' ),
                'plural_name'   => __( 'Events', 'aqualuxe' ),
            ),
            'testimonial' => array(
                'name'          => __( 'Testimonial', 'aqualuxe' ),
                'singular_name' => __( 'Testimonial', 'aqualuxe' ),
                'plural_name'   => __( 'Testimonials', 'aqualuxe' ),
            ),
            'team'        => array(
                'name'          => __( 'Team Member', 'aqualuxe' ),
                'singular_name' => __( 'Team Member', 'aqualuxe' ),
                'plural_name'   => __( 'Team Members', 'aqualuxe' ),
            ),
            'project'     => array(
                'name'          => __( 'Project', 'aqualuxe' ),
                'singular_name' => __( 'Project', 'aqualuxe' ),
                'plural_name'   => __( 'Projects', 'aqualuxe' ),
            ),
            'faq'         => array(
                'name'          => __( 'FAQ', 'aqualuxe' ),
                'singular_name' => __( 'FAQ', 'aqualuxe' ),
                'plural_name'   => __( 'FAQs', 'aqualuxe' ),
            ),
        );
        
        foreach ( $post_types as $post_type => $labels ) {
            pll_register_string( $post_type . '_name', $labels['name'], 'AquaLuxe CPT' );
            pll_register_string( $post_type . '_singular_name', $labels['singular_name'], 'AquaLuxe CPT' );
            pll_register_string( $post_type . '_plural_name', $labels['plural_name'], 'AquaLuxe CPT' );
        }
        
        // Register taxonomies
        $taxonomies = array(
            'service_category' => array(
                'name'          => __( 'Service Category', 'aqualuxe' ),
                'singular_name' => __( 'Service Category', 'aqualuxe' ),
                'plural_name'   => __( 'Service Categories', 'aqualuxe' ),
            ),
            'service_tag'      => array(
                'name'          => __( 'Service Tag', 'aqualuxe' ),
                'singular_name' => __( 'Service Tag', 'aqualuxe' ),
                'plural_name'   => __( 'Service Tags', 'aqualuxe' ),
            ),
            'event_category'   => array(
                'name'          => __( 'Event Category', 'aqualuxe' ),
                'singular_name' => __( 'Event Category', 'aqualuxe' ),
                'plural_name'   => __( 'Event Categories', 'aqualuxe' ),
            ),
            'event_tag'        => array(
                'name'          => __( 'Event Tag', 'aqualuxe' ),
                'singular_name' => __( 'Event Tag', 'aqualuxe' ),
                'plural_name'   => __( 'Event Tags', 'aqualuxe' ),
            ),
            'project_category' => array(
                'name'          => __( 'Project Category', 'aqualuxe' ),
                'singular_name' => __( 'Project Category', 'aqualuxe' ),
                'plural_name'   => __( 'Project Categories', 'aqualuxe' ),
            ),
            'project_tag'      => array(
                'name'          => __( 'Project Tag', 'aqualuxe' ),
                'singular_name' => __( 'Project Tag', 'aqualuxe' ),
                'plural_name'   => __( 'Project Tags', 'aqualuxe' ),
            ),
            'team_category'    => array(
                'name'          => __( 'Team Category', 'aqualuxe' ),
                'singular_name' => __( 'Team Category', 'aqualuxe' ),
                'plural_name'   => __( 'Team Categories', 'aqualuxe' ),
            ),
            'faq_category'     => array(
                'name'          => __( 'FAQ Category', 'aqualuxe' ),
                'singular_name' => __( 'FAQ Category', 'aqualuxe' ),
                'plural_name'   => __( 'FAQ Categories', 'aqualuxe' ),
            ),
        );
        
        foreach ( $taxonomies as $taxonomy => $labels ) {
            pll_register_string( $taxonomy . '_name', $labels['name'], 'AquaLuxe Taxonomy' );
            pll_register_string( $taxonomy . '_singular_name', $labels['singular_name'], 'AquaLuxe Taxonomy' );
            pll_register_string( $taxonomy . '_plural_name', $labels['plural_name'], 'AquaLuxe Taxonomy' );
        }
    }
}
add_action( 'init', 'aqualuxe_register_polylang_strings', 20 );

/**
 * Make custom post types translatable in Polylang
 */
function aqualuxe_polylang_register_post_types() {
    if ( function_exists( 'pll_register_post_type' ) ) {
        pll_register_post_type( 'service' );
        pll_register_post_type( 'event' );
        pll_register_post_type( 'testimonial' );
        pll_register_post_type( 'team' );
        pll_register_post_type( 'project' );
        pll_register_post_type( 'faq' );
    }
    
    if ( function_exists( 'pll_register_taxonomy' ) ) {
        pll_register_taxonomy( 'service_category' );
        pll_register_taxonomy( 'service_tag' );
        pll_register_taxonomy( 'event_category' );
        pll_register_taxonomy( 'event_tag' );
        pll_register_taxonomy( 'project_category' );
        pll_register_taxonomy( 'project_tag' );
        pll_register_taxonomy( 'team_category' );
        pll_register_taxonomy( 'faq_category' );
    }
}
add_action( 'init', 'aqualuxe_polylang_register_post_types', 5 );

/**
 * Add language switcher to mobile menu
 */
function aqualuxe_add_language_switcher_to_mobile_menu( $items, $args ) {
    if ( $args->theme_location == 'primary' && wp_is_mobile() ) {
        $language_switcher = '';
        
        // WPML language switcher
        if ( function_exists( 'icl_object_id' ) ) {
            ob_start();
            do_action( 'wpml_add_language_selector' );
            $language_switcher = ob_get_clean();
        }
        
        // Polylang language switcher
        if ( function_exists( 'pll_the_languages' ) ) {
            ob_start();
            echo '<div class="mobile-language-switcher">';
            pll_the_languages( array(
                'dropdown'       => 0,
                'show_names'     => 1,
                'display_names_as' => 'name',
                'hide_if_empty'  => 0,
            ) );
            echo '</div>';
            $language_switcher = ob_get_clean();
        }
        
        if ( $language_switcher ) {
            $items .= '<li class="menu-item menu-item-language">' . $language_switcher . '</li>';
        }
    }
    
    return $items;
}
add_filter( 'wp_nav_menu_items', 'aqualuxe_add_language_switcher_to_mobile_menu', 10, 2 );

/**
 * Add hreflang links to head
 */
function aqualuxe_add_hreflang_links() {
    // WPML
    if ( function_exists( 'icl_object_id' ) && defined( 'ICL_LANGUAGE_CODE' ) ) {
        // WPML already adds hreflang links
        return;
    }
    
    // Polylang
    if ( function_exists( 'pll_languages_list' ) && function_exists( 'pll_current_language' ) ) {
        $languages = pll_languages_list( array( 'fields' => 'slug' ) );
        $current_lang = pll_current_language();
        
        foreach ( $languages as $lang ) {
            if ( $lang === $current_lang ) {
                echo '<link rel="alternate" hreflang="' . esc_attr( $lang ) . '" href="' . esc_url( pll_home_url( $lang ) ) . '" />' . "\n";
            } else {
                echo '<link rel="alternate" hreflang="' . esc_attr( $lang ) . '" href="' . esc_url( pll_home_url( $lang ) ) . '" />' . "\n";
            }
        }
        
        // Add x-default hreflang
        $default_lang = pll_default_language();
        echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( pll_home_url( $default_lang ) ) . '" />' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_add_hreflang_links' );

/**
 * Add language information to the body class
 */
function aqualuxe_add_language_body_class( $classes ) {
    // WPML
    if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
        $classes[] = 'lang-' . ICL_LANGUAGE_CODE;
    }
    
    // Polylang
    if ( function_exists( 'pll_current_language' ) ) {
        $classes[] = 'lang-' . pll_current_language();
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_add_language_body_class' );

/**
 * Add RTL support for languages that read right-to-left
 */
function aqualuxe_add_rtl_support() {
    // WPML
    if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
        $rtl_languages = array( 'ar', 'he', 'fa', 'ur' ); // Arabic, Hebrew, Persian, Urdu
        
        if ( in_array( ICL_LANGUAGE_CODE, $rtl_languages ) ) {
            add_filter( 'body_class', function( $classes ) {
                $classes[] = 'rtl';
                return $classes;
            } );
        }
    }
    
    // Polylang
    if ( function_exists( 'pll_current_language' ) ) {
        $rtl_languages = array( 'ar', 'he', 'fa', 'ur' ); // Arabic, Hebrew, Persian, Urdu
        $current_lang = pll_current_language();
        
        if ( in_array( $current_lang, $rtl_languages ) ) {
            add_filter( 'body_class', function( $classes ) {
                $classes[] = 'rtl';
                return $classes;
            } );
        }
    }
}
add_action( 'init', 'aqualuxe_add_rtl_support' );

/**
 * Add language parameter to AJAX requests
 */
function aqualuxe_add_language_to_ajax() {
    // WPML
    if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
        ?>
        <script type="text/javascript">
            (function($) {
                $(document).ready(function() {
                    // Add language to AJAX requests
                    $(document).ajaxSend(function(event, jqxhr, settings) {
                        if (settings.url.indexOf('admin-ajax.php') !== -1) {
                            settings.url += (settings.url.indexOf('?') !== -1 ? '&' : '?') + 'lang=<?php echo ICL_LANGUAGE_CODE; ?>';
                        }
                    });
                });
            })(jQuery);
        </script>
        <?php
    }
    
    // Polylang
    if ( function_exists( 'pll_current_language' ) ) {
        $current_lang = pll_current_language();
        ?>
        <script type="text/javascript">
            (function($) {
                $(document).ready(function() {
                    // Add language to AJAX requests
                    $(document).ajaxSend(function(event, jqxhr, settings) {
                        if (settings.url.indexOf('admin-ajax.php') !== -1) {
                            settings.url += (settings.url.indexOf('?') !== -1 ? '&' : '?') + 'lang=<?php echo $current_lang; ?>';
                        }
                    });
                });
            })(jQuery);
        </script>
        <?php
    }
}
add_action( 'wp_footer', 'aqualuxe_add_language_to_ajax' );

/**
 * Add language parameter to URLs
 */
function aqualuxe_add_language_to_url( $url ) {
    // WPML
    if ( defined( 'ICL_LANGUAGE_CODE' ) && function_exists( 'icl_object_id' ) ) {
        return apply_filters( 'wpml_permalink', $url );
    }
    
    // Polylang
    if ( function_exists( 'pll_current_language' ) && function_exists( 'pll_home_url' ) ) {
        $current_lang = pll_current_language();
        
        if ( $current_lang ) {
            $home_url = pll_home_url( $current_lang );
            $site_url = site_url();
            
            if ( strpos( $url, $site_url ) === 0 ) {
                $path = substr( $url, strlen( $site_url ) );
                $url = $home_url . ltrim( $path, '/' );
            }
        }
    }
    
    return $url;
}
add_filter( 'aqualuxe_url', 'aqualuxe_add_language_to_url' );

/**
 * Add language switcher to footer
 */
function aqualuxe_footer_language_switcher() {
    echo '<div class="footer-language-switcher">';
    aqualuxe_language_switcher();
    echo '</div>';
}
add_action( 'aqualuxe_footer_widgets_end', 'aqualuxe_footer_language_switcher' );