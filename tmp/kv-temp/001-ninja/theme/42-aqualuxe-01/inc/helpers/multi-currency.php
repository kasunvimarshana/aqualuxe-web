<?php
/**
 * Multi-currency support for AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Check if WooCommerce Multi-Currency is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_multicurrency_active() {
    return class_exists( 'WOOMC\MultiCurrency' );
}

/**
 * Check if WPML Currency is active
 *
 * @return bool
 */
function aqualuxe_is_wpml_currency_active() {
    return class_exists( 'WCML_Multi_Currency' );
}

/**
 * Check if Currency Switcher for WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_wcs_currency_active() {
    return class_exists( 'WOOCS' );
}

/**
 * Check if any multi-currency plugin is active
 *
 * @return bool
 */
function aqualuxe_is_multi_currency() {
    return aqualuxe_is_woocommerce_multicurrency_active() || aqualuxe_is_wpml_currency_active() || aqualuxe_is_wcs_currency_active();
}

/**
 * Get current currency code
 *
 * @return string
 */
function aqualuxe_get_current_currency() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return get_option( 'woocommerce_currency', 'USD' );
    }

    if ( aqualuxe_is_woocommerce_multicurrency_active() ) {
        return WOOMC\MultiCurrency::getInstance()->get_current_currency();
    } elseif ( aqualuxe_is_wpml_currency_active() ) {
        global $woocommerce_wpml;
        if ( isset( $woocommerce_wpml->multi_currency ) ) {
            return $woocommerce_wpml->multi_currency->get_client_currency();
        }
    } elseif ( aqualuxe_is_wcs_currency_active() ) {
        global $WOOCS;
        if ( isset( $WOOCS ) ) {
            return $WOOCS->current_currency;
        }
    }
    
    return get_woocommerce_currency();
}

/**
 * Get default currency code
 *
 * @return string
 */
function aqualuxe_get_default_currency() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return get_option( 'woocommerce_currency', 'USD' );
    }

    if ( aqualuxe_is_woocommerce_multicurrency_active() ) {
        return get_option( 'woocommerce_currency', 'USD' );
    } elseif ( aqualuxe_is_wpml_currency_active() ) {
        global $woocommerce_wpml;
        if ( isset( $woocommerce_wpml->multi_currency ) ) {
            return $woocommerce_wpml->multi_currency->get_default_currency();
        }
    } elseif ( aqualuxe_is_wcs_currency_active() ) {
        global $WOOCS;
        if ( isset( $WOOCS ) ) {
            return $WOOCS->default_currency;
        }
    }
    
    return get_woocommerce_currency();
}

/**
 * Get available currencies
 *
 * @return array
 */
function aqualuxe_get_available_currencies() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return array( get_option( 'woocommerce_currency', 'USD' ) );
    }

    if ( aqualuxe_is_woocommerce_multicurrency_active() ) {
        return WOOMC\MultiCurrency::getInstance()->get_currencies();
    } elseif ( aqualuxe_is_wpml_currency_active() ) {
        global $woocommerce_wpml;
        if ( isset( $woocommerce_wpml->multi_currency ) ) {
            return $woocommerce_wpml->multi_currency->get_currencies();
        }
    } elseif ( aqualuxe_is_wcs_currency_active() ) {
        global $WOOCS;
        if ( isset( $WOOCS ) ) {
            return $WOOCS->get_currencies();
        }
    }
    
    return array( get_woocommerce_currency() );
}

/**
 * Get currency symbol
 *
 * @param string $currency Currency code
 * @return string
 */
function aqualuxe_get_currency_symbol( $currency = '' ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return '$';
    }

    if ( ! $currency ) {
        $currency = aqualuxe_get_current_currency();
    }
    
    return get_woocommerce_currency_symbol( $currency );
}

/**
 * Format price in current currency
 *
 * @param float  $price Price to format
 * @param string $currency Currency code
 * @return string
 */
function aqualuxe_format_price( $price, $currency = '' ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return '$' . number_format( $price, 2 );
    }

    if ( ! $currency ) {
        $currency = aqualuxe_get_current_currency();
    }
    
    if ( aqualuxe_is_woocommerce_multicurrency_active() ) {
        return WOOMC\MultiCurrency::getInstance()->format_price( $price, $currency );
    } elseif ( aqualuxe_is_wpml_currency_active() ) {
        global $woocommerce_wpml;
        if ( isset( $woocommerce_wpml->multi_currency ) ) {
            return $woocommerce_wpml->multi_currency->formatted_price( $price, $currency );
        }
    } elseif ( aqualuxe_is_wcs_currency_active() ) {
        global $WOOCS;
        if ( isset( $WOOCS ) ) {
            return $WOOCS->wc_price( $price, array( 'currency' => $currency ) );
        }
    }
    
    return wc_price( $price, array( 'currency' => $currency ) );
}

/**
 * Convert price to current currency
 *
 * @param float  $price Price to convert
 * @param string $from_currency Source currency code
 * @param string $to_currency Target currency code
 * @return float
 */
function aqualuxe_convert_price( $price, $from_currency = '', $to_currency = '' ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return $price;
    }

    if ( ! $from_currency ) {
        $from_currency = aqualuxe_get_default_currency();
    }
    
    if ( ! $to_currency ) {
        $to_currency = aqualuxe_get_current_currency();
    }
    
    if ( $from_currency === $to_currency ) {
        return $price;
    }
    
    if ( aqualuxe_is_woocommerce_multicurrency_active() ) {
        return WOOMC\MultiCurrency::getInstance()->convert_price_to_currency( $price, $to_currency );
    } elseif ( aqualuxe_is_wpml_currency_active() ) {
        global $woocommerce_wpml;
        if ( isset( $woocommerce_wpml->multi_currency ) ) {
            return $woocommerce_wpml->multi_currency->convert_price_amount( $price, $to_currency );
        }
    } elseif ( aqualuxe_is_wcs_currency_active() ) {
        global $WOOCS;
        if ( isset( $WOOCS ) ) {
            return $WOOCS->woocs_exchange_value( $price, $WOOCS->get_currencies()[$to_currency]['rate'] );
        }
    }
    
    return $price;
}

/**
 * Get currency exchange rate
 *
 * @param string $from_currency Source currency code
 * @param string $to_currency Target currency code
 * @return float
 */
function aqualuxe_get_exchange_rate( $from_currency = '', $to_currency = '' ) {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return 1;
    }

    if ( ! $from_currency ) {
        $from_currency = aqualuxe_get_default_currency();
    }
    
    if ( ! $to_currency ) {
        $to_currency = aqualuxe_get_current_currency();
    }
    
    if ( $from_currency === $to_currency ) {
        return 1;
    }
    
    if ( aqualuxe_is_woocommerce_multicurrency_active() ) {
        $currencies = WOOMC\MultiCurrency::getInstance()->get_currencies();
        if ( isset( $currencies[$to_currency] ) ) {
            return $currencies[$to_currency]['rate'];
        }
    } elseif ( aqualuxe_is_wpml_currency_active() ) {
        global $woocommerce_wpml;
        if ( isset( $woocommerce_wpml->multi_currency ) ) {
            $currencies = $woocommerce_wpml->multi_currency->get_currencies();
            if ( isset( $currencies[$to_currency] ) ) {
                return $currencies[$to_currency]['rate'];
            }
        }
    } elseif ( aqualuxe_is_wcs_currency_active() ) {
        global $WOOCS;
        if ( isset( $WOOCS ) ) {
            $currencies = $WOOCS->get_currencies();
            if ( isset( $currencies[$to_currency] ) ) {
                return $currencies[$to_currency]['rate'];
            }
        }
    }
    
    return 1;
}

/**
 * Get currency name
 *
 * @param string $currency Currency code
 * @return string
 */
function aqualuxe_get_currency_name( $currency ) {
    $currency_names = array(
        'USD' => esc_html__( 'US Dollar', 'aqualuxe' ),
        'EUR' => esc_html__( 'Euro', 'aqualuxe' ),
        'GBP' => esc_html__( 'British Pound', 'aqualuxe' ),
        'AUD' => esc_html__( 'Australian Dollar', 'aqualuxe' ),
        'CAD' => esc_html__( 'Canadian Dollar', 'aqualuxe' ),
        'JPY' => esc_html__( 'Japanese Yen', 'aqualuxe' ),
        'CNY' => esc_html__( 'Chinese Yuan', 'aqualuxe' ),
        'INR' => esc_html__( 'Indian Rupee', 'aqualuxe' ),
        'BRL' => esc_html__( 'Brazilian Real', 'aqualuxe' ),
        'RUB' => esc_html__( 'Russian Ruble', 'aqualuxe' ),
        'KRW' => esc_html__( 'South Korean Won', 'aqualuxe' ),
        'SGD' => esc_html__( 'Singapore Dollar', 'aqualuxe' ),
        'NZD' => esc_html__( 'New Zealand Dollar', 'aqualuxe' ),
        'MXN' => esc_html__( 'Mexican Peso', 'aqualuxe' ),
        'IDR' => esc_html__( 'Indonesian Rupiah', 'aqualuxe' ),
        'PHP' => esc_html__( 'Philippine Peso', 'aqualuxe' ),
        'MYR' => esc_html__( 'Malaysian Ringgit', 'aqualuxe' ),
        'THB' => esc_html__( 'Thai Baht', 'aqualuxe' ),
        'VND' => esc_html__( 'Vietnamese Dong', 'aqualuxe' ),
        'SEK' => esc_html__( 'Swedish Krona', 'aqualuxe' ),
        'NOK' => esc_html__( 'Norwegian Krone', 'aqualuxe' ),
        'DKK' => esc_html__( 'Danish Krone', 'aqualuxe' ),
        'CHF' => esc_html__( 'Swiss Franc', 'aqualuxe' ),
        'CZK' => esc_html__( 'Czech Koruna', 'aqualuxe' ),
        'PLN' => esc_html__( 'Polish Złoty', 'aqualuxe' ),
        'HUF' => esc_html__( 'Hungarian Forint', 'aqualuxe' ),
        'ILS' => esc_html__( 'Israeli New Shekel', 'aqualuxe' ),
        'AED' => esc_html__( 'United Arab Emirates Dirham', 'aqualuxe' ),
        'SAR' => esc_html__( 'Saudi Riyal', 'aqualuxe' ),
        'ZAR' => esc_html__( 'South African Rand', 'aqualuxe' ),
        'TRY' => esc_html__( 'Turkish Lira', 'aqualuxe' ),
        'HKD' => esc_html__( 'Hong Kong Dollar', 'aqualuxe' ),
    );
    
    return isset( $currency_names[$currency] ) ? $currency_names[$currency] : $currency;
}

/**
 * Add currency switcher to top bar
 */
function aqualuxe_currency_switcher() {
    if ( ! aqualuxe_is_multi_currency() ) {
        return;
    }
    
    $current_currency = aqualuxe_get_current_currency();
    $currencies = aqualuxe_get_available_currencies();
    
    if ( empty( $currencies ) || count( $currencies ) <= 1 ) {
        return;
    }
    
    echo '<div class="currency-switcher relative ml-4">';
    
    // Current currency
    echo '<button class="currency-switcher-toggle flex items-center text-sm focus:outline-none" aria-label="' . esc_attr__( 'Currency Switcher', 'aqualuxe' ) . '">';
    echo esc_html( aqualuxe_get_currency_symbol( $current_currency ) . ' ' . $current_currency );
    echo '<i class="fas fa-chevron-down ml-1 text-xs"></i>';
    echo '</button>';
    
    // Currency dropdown
    echo '<div class="currency-switcher-dropdown absolute right-0 top-full mt-1 bg-white shadow-lg rounded-md py-2 hidden z-50 min-w-[120px]">';
    
    if ( aqualuxe_is_woocommerce_multicurrency_active() ) {
        foreach ( $currencies as $code => $currency ) {
            $active_class = $code === $current_currency ? 'bg-gray-100' : '';
            $url = add_query_arg( 'currency', $code, remove_query_arg( 'currency' ) );
            
            echo '<a href="' . esc_url( $url ) . '" class="flex items-center justify-between px-4 py-2 text-sm hover:bg-gray-100 ' . esc_attr( $active_class ) . '">';
            echo '<span>' . esc_html( $code ) . '</span>';
            echo '<span>' . esc_html( aqualuxe_get_currency_symbol( $code ) ) . '</span>';
            echo '</a>';
        }
    } elseif ( aqualuxe_is_wpml_currency_active() ) {
        foreach ( $currencies as $code => $currency ) {
            $active_class = $code === $current_currency ? 'bg-gray-100' : '';
            $url = add_query_arg( 'currency', $code, remove_query_arg( 'currency' ) );
            
            echo '<a href="' . esc_url( $url ) . '" class="flex items-center justify-between px-4 py-2 text-sm hover:bg-gray-100 ' . esc_attr( $active_class ) . '">';
            echo '<span>' . esc_html( $code ) . '</span>';
            echo '<span>' . esc_html( aqualuxe_get_currency_symbol( $code ) ) . '</span>';
            echo '</a>';
        }
    } elseif ( aqualuxe_is_wcs_currency_active() ) {
        global $WOOCS;
        
        foreach ( $currencies as $code => $currency ) {
            $active_class = $code === $current_currency ? 'bg-gray-100' : '';
            $url = add_query_arg( 'currency', $code, remove_query_arg( 'currency' ) );
            
            echo '<a href="' . esc_url( $url ) . '" class="flex items-center justify-between px-4 py-2 text-sm hover:bg-gray-100 ' . esc_attr( $active_class ) . '">';
            echo '<span>' . esc_html( $code ) . '</span>';
            echo '<span>' . esc_html( $currency['symbol'] ) . '</span>';
            echo '</a>';
        }
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Add currency switcher to mobile menu
 */
function aqualuxe_mobile_currency_switcher() {
    if ( ! aqualuxe_is_multi_currency() ) {
        return;
    }
    
    $current_currency = aqualuxe_get_current_currency();
    $currencies = aqualuxe_get_available_currencies();
    
    if ( empty( $currencies ) || count( $currencies ) <= 1 ) {
        return;
    }
    
    echo '<div class="mobile-currency-switcher mt-6 pt-6 border-t border-gray-200">';
    echo '<h3 class="text-sm font-bold text-gray-500 mb-4">' . esc_html__( 'Currency', 'aqualuxe' ) . '</h3>';
    echo '<div class="currency-options flex flex-wrap gap-2">';
    
    if ( aqualuxe_is_woocommerce_multicurrency_active() ) {
        foreach ( $currencies as $code => $currency ) {
            $active_class = $code === $current_currency ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-800';
            $url = add_query_arg( 'currency', $code, remove_query_arg( 'currency' ) );
            
            echo '<a href="' . esc_url( $url ) . '" class="flex items-center px-3 py-1 rounded-full text-sm ' . esc_attr( $active_class ) . '">';
            echo esc_html( aqualuxe_get_currency_symbol( $code ) . ' ' . $code );
            echo '</a>';
        }
    } elseif ( aqualuxe_is_wpml_currency_active() ) {
        foreach ( $currencies as $code => $currency ) {
            $active_class = $code === $current_currency ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-800';
            $url = add_query_arg( 'currency', $code, remove_query_arg( 'currency' ) );
            
            echo '<a href="' . esc_url( $url ) . '" class="flex items-center px-3 py-1 rounded-full text-sm ' . esc_attr( $active_class ) . '">';
            echo esc_html( aqualuxe_get_currency_symbol( $code ) . ' ' . $code );
            echo '</a>';
        }
    } elseif ( aqualuxe_is_wcs_currency_active() ) {
        global $WOOCS;
        
        foreach ( $currencies as $code => $currency ) {
            $active_class = $code === $current_currency ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-800';
            $url = add_query_arg( 'currency', $code, remove_query_arg( 'currency' ) );
            
            echo '<a href="' . esc_url( $url ) . '" class="flex items-center px-3 py-1 rounded-full text-sm ' . esc_attr( $active_class ) . '">';
            echo esc_html( $currency['symbol'] . ' ' . $code );
            echo '</a>';
        }
    }
    
    echo '</div>';
    echo '</div>';
}
add_action( 'aqualuxe_after_mobile_menu', 'aqualuxe_mobile_currency_switcher', 15 );

/**
 * Add currency switcher to footer
 */
function aqualuxe_footer_currency_switcher() {
    if ( ! aqualuxe_is_multi_currency() || ! get_theme_mod( 'aqualuxe_footer_currency_switcher', true ) ) {
        return;
    }
    
    $current_currency = aqualuxe_get_current_currency();
    $currencies = aqualuxe_get_available_currencies();
    
    if ( empty( $currencies ) || count( $currencies ) <= 1 ) {
        return;
    }
    
    echo '<div class="footer-currency-switcher mt-4">';
    echo '<div class="currency-options flex flex-wrap gap-2 justify-center md:justify-end">';
    
    if ( aqualuxe_is_woocommerce_multicurrency_active() ) {
        foreach ( $currencies as $code => $currency ) {
            $active_class = $code === $current_currency ? 'bg-primary-700 text-white' : 'bg-primary-800 text-gray-300 hover:bg-primary-700 hover:text-white';
            $url = add_query_arg( 'currency', $code, remove_query_arg( 'currency' ) );
            
            echo '<a href="' . esc_url( $url ) . '" class="flex items-center px-3 py-1 rounded-full text-xs transition-colors ' . esc_attr( $active_class ) . '">';
            echo esc_html( aqualuxe_get_currency_symbol( $code ) . ' ' . $code );
            echo '</a>';
        }
    } elseif ( aqualuxe_is_wpml_currency_active() ) {
        foreach ( $currencies as $code => $currency ) {
            $active_class = $code === $current_currency ? 'bg-primary-700 text-white' : 'bg-primary-800 text-gray-300 hover:bg-primary-700 hover:text-white';
            $url = add_query_arg( 'currency', $code, remove_query_arg( 'currency' ) );
            
            echo '<a href="' . esc_url( $url ) . '" class="flex items-center px-3 py-1 rounded-full text-xs transition-colors ' . esc_attr( $active_class ) . '">';
            echo esc_html( aqualuxe_get_currency_symbol( $code ) . ' ' . $code );
            echo '</a>';
        }
    } elseif ( aqualuxe_is_wcs_currency_active() ) {
        global $WOOCS;
        
        foreach ( $currencies as $code => $currency ) {
            $active_class = $code === $current_currency ? 'bg-primary-700 text-white' : 'bg-primary-800 text-gray-300 hover:bg-primary-700 hover:text-white';
            $url = add_query_arg( 'currency', $code, remove_query_arg( 'currency' ) );
            
            echo '<a href="' . esc_url( $url ) . '" class="flex items-center px-3 py-1 rounded-full text-xs transition-colors ' . esc_attr( $active_class ) . '">';
            echo esc_html( $currency['symbol'] . ' ' . $code );
            echo '</a>';
        }
    }
    
    echo '</div>';
    echo '</div>';
}
add_action( 'aqualuxe_after_copyright', 'aqualuxe_footer_currency_switcher', 15 );

/**
 * Add currency switcher to customizer
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object
 */
function aqualuxe_currency_switcher_customizer( $wp_customize ) {
    if ( ! aqualuxe_is_multi_currency() ) {
        return;
    }
    
    $wp_customize->add_setting(
        'aqualuxe_footer_currency_switcher',
        array(
            'default'           => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'aqualuxe_footer_currency_switcher',
        array(
            'label'       => esc_html__( 'Show Currency Switcher in Footer', 'aqualuxe' ),
            'description' => esc_html__( 'Display currency switcher in the footer', 'aqualuxe' ),
            'section'     => 'aqualuxe_footer_options',
            'type'        => 'checkbox',
        )
    );
}
add_action( 'customize_register', 'aqualuxe_currency_switcher_customizer' );

/**
 * Add currency information to AJAX requests
 *
 * @param array $data AJAX data
 * @return array
 */
function aqualuxe_add_currency_to_ajax( $data ) {
    if ( aqualuxe_is_multi_currency() ) {
        $data['currency'] = aqualuxe_get_current_currency();
    }
    
    return $data;
}
add_filter( 'aqualuxe_localize_script_data', 'aqualuxe_add_currency_to_ajax' );

/**
 * Add currency parameter to AJAX URL
 *
 * @param string $url AJAX URL
 * @return string
 */
function aqualuxe_add_currency_to_ajax_url( $url ) {
    if ( aqualuxe_is_multi_currency() ) {
        $url = add_query_arg( 'currency', aqualuxe_get_current_currency(), $url );
    }
    
    return $url;
}
add_filter( 'aqualuxe_ajax_url', 'aqualuxe_add_currency_to_ajax_url' );

/**
 * Display price in multiple currencies
 *
 * @param float $price Price to display
 * @param array $args Display arguments
 * @return string
 */
function aqualuxe_multi_currency_price( $price, $args = array() ) {
    if ( ! aqualuxe_is_multi_currency() ) {
        return wc_price( $price, $args );
    }
    
    $defaults = array(
        'show_default' => true,
        'currencies'   => array(),
        'wrapper'      => 'div',
        'wrapper_class' => 'multi-currency-price',
        'separator'    => ' / ',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $current_currency = aqualuxe_get_current_currency();
    $default_currency = aqualuxe_get_default_currency();
    $currencies = empty( $args['currencies'] ) ? array_keys( aqualuxe_get_available_currencies() ) : $args['currencies'];
    
    // Always include current currency
    if ( ! in_array( $current_currency, $currencies, true ) ) {
        $currencies[] = $current_currency;
    }
    
    // Always include default currency if show_default is true
    if ( $args['show_default'] && ! in_array( $default_currency, $currencies, true ) ) {
        $currencies[] = $default_currency;
    }
    
    // Remove duplicates and reindex
    $currencies = array_values( array_unique( $currencies ) );
    
    // Move current currency to first position
    if ( ( $key = array_search( $current_currency, $currencies, true ) ) !== false ) {
        unset( $currencies[$key] );
        array_unshift( $currencies, $current_currency );
    }
    
    // Limit to 3 currencies
    $currencies = array_slice( $currencies, 0, 3 );
    
    $output = '';
    
    foreach ( $currencies as $currency ) {
        $converted_price = aqualuxe_convert_price( $price, $default_currency, $currency );
        $formatted_price = aqualuxe_format_price( $converted_price, $currency );
        
        if ( $output ) {
            $output .= $args['separator'];
        }
        
        $output .= $formatted_price;
    }
    
    if ( $args['wrapper'] ) {
        $output = '<' . esc_attr( $args['wrapper'] ) . ' class="' . esc_attr( $args['wrapper_class'] ) . '">' . $output . '</' . esc_attr( $args['wrapper'] ) . '>';
    }
    
    return $output;
}

/**
 * Display price in multiple currencies with tooltip
 *
 * @param float $price Price to display
 * @param array $args Display arguments
 * @return string
 */
function aqualuxe_multi_currency_price_tooltip( $price, $args = array() ) {
    if ( ! aqualuxe_is_multi_currency() ) {
        return wc_price( $price, $args );
    }
    
    $defaults = array(
        'currencies'   => array(),
        'tooltip_class' => 'multi-currency-tooltip',
    );
    
    $args = wp_parse_args( $args, $defaults );
    
    $current_currency = aqualuxe_get_current_currency();
    $default_currency = aqualuxe_get_default_currency();
    $currencies = empty( $args['currencies'] ) ? array_keys( aqualuxe_get_available_currencies() ) : $args['currencies'];
    
    // Remove current currency from tooltip currencies
    if ( ( $key = array_search( $current_currency, $currencies, true ) ) !== false ) {
        unset( $currencies[$key] );
    }
    
    // Reindex array
    $currencies = array_values( $currencies );
    
    // If no other currencies, just return the price
    if ( empty( $currencies ) ) {
        return wc_price( $price, $args );
    }
    
    $tooltip_content = '';
    
    foreach ( $currencies as $currency ) {
        $converted_price = aqualuxe_convert_price( $price, $default_currency, $currency );
        $formatted_price = aqualuxe_format_price( $converted_price, $currency );
        
        if ( $tooltip_content ) {
            $tooltip_content .= '<br>';
        }
        
        $tooltip_content .= $formatted_price . ' (' . esc_html( aqualuxe_get_currency_name( $currency ) ) . ')';
    }
    
    $output = '<span class="multi-currency-price-wrapper">';
    $output .= wc_price( $price, $args );
    $output .= '<span class="' . esc_attr( $args['tooltip_class'] ) . '">' . $tooltip_content . '</span>';
    $output .= '</span>';
    
    return $output;
}

/**
 * Add currency class to body
 *
 * @param array $classes Body classes
 * @return array
 */
function aqualuxe_add_currency_body_class( $classes ) {
    if ( aqualuxe_is_multi_currency() ) {
        $current_currency = aqualuxe_get_current_currency();
        $classes[] = 'currency-' . strtolower( $current_currency );
    }
    
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_add_currency_body_class' );

/**
 * Add currency meta tag to head
 */
function aqualuxe_add_currency_meta() {
    if ( aqualuxe_is_multi_currency() ) {
        $current_currency = aqualuxe_get_current_currency();
        echo '<meta name="currency" content="' . esc_attr( $current_currency ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'aqualuxe_add_currency_meta' );

/**
 * Add currency information to product structured data
 *
 * @param array      $markup Structured data markup
 * @param WC_Product $product Product object
 * @return array
 */
function aqualuxe_add_currency_to_structured_data( $markup, $product ) {
    if ( ! aqualuxe_is_multi_currency() || ! isset( $markup['offers'] ) ) {
        return $markup;
    }
    
    $current_currency = aqualuxe_get_current_currency();
    
    if ( isset( $markup['offers']['priceCurrency'] ) ) {
        $markup['offers']['priceCurrency'] = $current_currency;
    }
    
    return $markup;
}
add_filter( 'woocommerce_structured_data_product', 'aqualuxe_add_currency_to_structured_data', 10, 2 );

/**
 * Add currency information to order emails
 *
 * @param WC_Order $order Order object
 */
function aqualuxe_add_currency_to_order_email( $order ) {
    if ( ! aqualuxe_is_multi_currency() ) {
        return;
    }
    
    $order_currency = $order->get_currency();
    $current_currency = aqualuxe_get_current_currency();
    
    if ( $order_currency !== $current_currency ) {
        $default_currency = aqualuxe_get_default_currency();
        $order_total = $order->get_total();
        $converted_total = aqualuxe_convert_price( $order_total, $order_currency, $current_currency );
        
        echo '<p class="currency-conversion-note">';
        printf(
            /* translators: %1$s: Order currency and total, %2$s: Current currency and converted total */
            esc_html__( 'Order placed in %1$s. Current value in your currency: %2$s', 'aqualuxe' ),
            esc_html( aqualuxe_format_price( $order_total, $order_currency ) ),
            esc_html( aqualuxe_format_price( $converted_total, $current_currency ) )
        );
        echo '</p>';
    }
}
add_action( 'woocommerce_email_before_order_table', 'aqualuxe_add_currency_to_order_email', 10, 1 );
add_action( 'woocommerce_order_details_before_order_table', 'aqualuxe_add_currency_to_order_email', 10, 1 );

/**
 * Add currency information to order meta
 *
 * @param int $order_id Order ID
 */
function aqualuxe_add_currency_to_order_meta( $order_id ) {
    if ( ! aqualuxe_is_multi_currency() ) {
        return;
    }
    
    $current_currency = aqualuxe_get_current_currency();
    $default_currency = aqualuxe_get_default_currency();
    
    if ( $current_currency !== $default_currency ) {
        $exchange_rate = aqualuxe_get_exchange_rate( $default_currency, $current_currency );
        
        update_post_meta( $order_id, '_aqualuxe_order_currency', $current_currency );
        update_post_meta( $order_id, '_aqualuxe_order_exchange_rate', $exchange_rate );
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'aqualuxe_add_currency_to_order_meta' );

/**
 * Display currency information in order admin
 *
 * @param int $order_id Order ID
 */
function aqualuxe_display_currency_in_order_admin( $order_id ) {
    $order_currency = get_post_meta( $order_id, '_aqualuxe_order_currency', true );
    $exchange_rate = get_post_meta( $order_id, '_aqualuxe_order_exchange_rate', true );
    
    if ( $order_currency && $exchange_rate ) {
        echo '<p class="form-field form-field-wide">';
        printf(
            /* translators: %1$s: Order currency, %2$s: Exchange rate */
            esc_html__( 'Order Currency: %1$s (Exchange Rate: %2$s)', 'aqualuxe' ),
            '<strong>' . esc_html( $order_currency ) . '</strong>',
            '<strong>' . esc_html( $exchange_rate ) . '</strong>'
        );
        echo '</p>';
    }
}
add_action( 'woocommerce_admin_order_data_after_order_details', 'aqualuxe_display_currency_in_order_admin' );