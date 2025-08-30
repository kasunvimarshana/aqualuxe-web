<?php
/**
 * Multi-currency support for AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Initialize multi-currency support
 */
function aqualuxe_multi_currency_init() {
    // Check if WooCommerce is active
    if ( class_exists( 'WooCommerce' ) ) {
        // Add support for WooCommerce Multi-currency
        add_action( 'after_setup_theme', 'aqualuxe_woocommerce_multi_currency_compatibility' );
        
        // Add currency switcher to header
        add_action( 'wp_footer', 'aqualuxe_currency_switcher_modal' );
        
        // Add AJAX handler for currency switching
        add_action( 'wp_ajax_aqualuxe_switch_currency', 'aqualuxe_ajax_switch_currency' );
        add_action( 'wp_ajax_nopriv_aqualuxe_switch_currency', 'aqualuxe_ajax_switch_currency' );
    }
}
add_action( 'init', 'aqualuxe_multi_currency_init' );

/**
 * Add WooCommerce Multi-currency compatibility
 */
function aqualuxe_woocommerce_multi_currency_compatibility() {
    // Check for popular multi-currency plugins and add compatibility
    if ( class_exists( 'WCML_Multi_Currency' ) ) {
        // WooCommerce Multilingual compatibility
        add_filter( 'aqualuxe_get_currency', 'aqualuxe_wcml_get_currency' );
        add_filter( 'aqualuxe_get_currencies', 'aqualuxe_wcml_get_currencies' );
        add_filter( 'aqualuxe_get_currency_symbol', 'aqualuxe_wcml_get_currency_symbol' );
    } elseif ( class_exists( 'WOOCS' ) ) {
        // WooCommerce Currency Switcher compatibility
        add_filter( 'aqualuxe_get_currency', 'aqualuxe_woocs_get_currency' );
        add_filter( 'aqualuxe_get_currencies', 'aqualuxe_woocs_get_currencies' );
        add_filter( 'aqualuxe_get_currency_symbol', 'aqualuxe_woocs_get_currency_symbol' );
    } elseif ( class_exists( 'WC_Aelia_CurrencySwitcher' ) ) {
        // Aelia Currency Switcher compatibility
        add_filter( 'aqualuxe_get_currency', 'aqualuxe_aelia_get_currency' );
        add_filter( 'aqualuxe_get_currencies', 'aqualuxe_aelia_get_currencies' );
        add_filter( 'aqualuxe_get_currency_symbol', 'aqualuxe_aelia_get_currency_symbol' );
    } else {
        // Default WooCommerce currency (no multi-currency)
        add_filter( 'aqualuxe_get_currency', 'aqualuxe_default_get_currency' );
        add_filter( 'aqualuxe_get_currencies', 'aqualuxe_default_get_currencies' );
        add_filter( 'aqualuxe_get_currency_symbol', 'aqualuxe_default_get_currency_symbol' );
    }
}

/**
 * Get current currency
 *
 * @return string
 */
function aqualuxe_get_currency() {
    return apply_filters( 'aqualuxe_get_currency', get_woocommerce_currency() );
}

/**
 * Get available currencies
 *
 * @return array
 */
function aqualuxe_get_currencies() {
    return apply_filters( 'aqualuxe_get_currencies', array( get_woocommerce_currency() => get_woocommerce_currency_symbol() ) );
}

/**
 * Get currency symbol
 *
 * @param string $currency Currency code.
 * @return string
 */
function aqualuxe_get_currency_symbol( $currency = '' ) {
    if ( empty( $currency ) ) {
        $currency = aqualuxe_get_currency();
    }
    return apply_filters( 'aqualuxe_get_currency_symbol', get_woocommerce_currency_symbol( $currency ), $currency );
}

/**
 * Format price with currency
 *
 * @param float  $price Price.
 * @param string $currency Currency code.
 * @return string
 */
function aqualuxe_format_price( $price, $currency = '' ) {
    if ( empty( $currency ) ) {
        $currency = aqualuxe_get_currency();
    }
    
    $symbol = aqualuxe_get_currency_symbol( $currency );
    $price_format = get_option( 'woocommerce_currency_pos' );
    
    switch ( $price_format ) {
        case 'left':
            return $symbol . $price;
        case 'right':
            return $price . $symbol;
        case 'left_space':
            return $symbol . ' ' . $price;
        case 'right_space':
            return $price . ' ' . $symbol;
        default:
            return $symbol . $price;
    }
}

/**
 * Convert price to different currency
 *
 * @param float  $price Price.
 * @param string $from_currency From currency.
 * @param string $to_currency To currency.
 * @return float
 */
function aqualuxe_convert_price( $price, $from_currency = '', $to_currency = '' ) {
    if ( empty( $from_currency ) ) {
        $from_currency = get_woocommerce_currency();
    }
    
    if ( empty( $to_currency ) ) {
        $to_currency = aqualuxe_get_currency();
    }
    
    // If currencies are the same, no conversion needed
    if ( $from_currency === $to_currency ) {
        return $price;
    }
    
    // Apply filter for currency conversion
    return apply_filters( 'aqualuxe_convert_price', $price, $from_currency, $to_currency );
}

/**
 * Default get currency (no multi-currency)
 *
 * @return string
 */
function aqualuxe_default_get_currency() {
    return get_woocommerce_currency();
}

/**
 * Default get currencies (no multi-currency)
 *
 * @return array
 */
function aqualuxe_default_get_currencies() {
    $currency = get_woocommerce_currency();
    $symbol = get_woocommerce_currency_symbol();
    
    // Get all available currencies from WooCommerce
    $currencies = get_woocommerce_currencies();
    $available_currencies = array();
    
    // Use only a subset of currencies for demo purposes
    $demo_currencies = array( 'USD', 'EUR', 'GBP', 'JPY', 'CAD', 'AUD' );
    
    foreach ( $demo_currencies as $currency_code ) {
        if ( isset( $currencies[ $currency_code ] ) ) {
            $available_currencies[ $currency_code ] = get_woocommerce_currency_symbol( $currency_code );
        }
    }
    
    return $available_currencies;
}

/**
 * Default get currency symbol (no multi-currency)
 *
 * @param string $currency Currency code.
 * @return string
 */
function aqualuxe_default_get_currency_symbol( $currency ) {
    return get_woocommerce_currency_symbol( $currency );
}

/**
 * WCML get currency
 *
 * @return string
 */
function aqualuxe_wcml_get_currency() {
    global $woocommerce_wpml;
    
    if ( isset( $woocommerce_wpml->multi_currency ) ) {
        return $woocommerce_wpml->multi_currency->get_client_currency();
    }
    
    return get_woocommerce_currency();
}

/**
 * WCML get currencies
 *
 * @return array
 */
function aqualuxe_wcml_get_currencies() {
    global $woocommerce_wpml;
    
    if ( isset( $woocommerce_wpml->multi_currency ) ) {
        $currencies = $woocommerce_wpml->multi_currency->get_currencies();
        $available_currencies = array();
        
        foreach ( $currencies as $code => $currency ) {
            $available_currencies[ $code ] = $currency['symbol'];
        }
        
        return $available_currencies;
    }
    
    return aqualuxe_default_get_currencies();
}

/**
 * WCML get currency symbol
 *
 * @param string $currency Currency code.
 * @return string
 */
function aqualuxe_wcml_get_currency_symbol( $currency ) {
    global $woocommerce_wpml;
    
    if ( isset( $woocommerce_wpml->multi_currency ) ) {
        $currencies = $woocommerce_wpml->multi_currency->get_currencies();
        
        if ( isset( $currencies[ $currency ] ) ) {
            return $currencies[ $currency ]['symbol'];
        }
    }
    
    return get_woocommerce_currency_symbol( $currency );
}

/**
 * WOOCS get currency
 *
 * @return string
 */
function aqualuxe_woocs_get_currency() {
    global $WOOCS;
    
    if ( isset( $WOOCS ) ) {
        return $WOOCS->current_currency;
    }
    
    return get_woocommerce_currency();
}

/**
 * WOOCS get currencies
 *
 * @return array
 */
function aqualuxe_woocs_get_currencies() {
    global $WOOCS;
    
    if ( isset( $WOOCS ) ) {
        $currencies = $WOOCS->get_currencies();
        $available_currencies = array();
        
        foreach ( $currencies as $code => $currency ) {
            $available_currencies[ $code ] = $currency['symbol'];
        }
        
        return $available_currencies;
    }
    
    return aqualuxe_default_get_currencies();
}

/**
 * WOOCS get currency symbol
 *
 * @param string $currency Currency code.
 * @return string
 */
function aqualuxe_woocs_get_currency_symbol( $currency ) {
    global $WOOCS;
    
    if ( isset( $WOOCS ) ) {
        $currencies = $WOOCS->get_currencies();
        
        if ( isset( $currencies[ $currency ] ) ) {
            return $currencies[ $currency ]['symbol'];
        }
    }
    
    return get_woocommerce_currency_symbol( $currency );
}

/**
 * Aelia get currency
 *
 * @return string
 */
function aqualuxe_aelia_get_currency() {
    if ( function_exists( 'get_woocommerce_currency' ) ) {
        return apply_filters( 'wc_aelia_cs_selected_currency', get_woocommerce_currency() );
    }
    
    return get_woocommerce_currency();
}

/**
 * Aelia get currencies
 *
 * @return array
 */
function aqualuxe_aelia_get_currencies() {
    if ( class_exists( 'WC_Aelia_CurrencySwitcher' ) ) {
        $currency_switcher = WC_Aelia_CurrencySwitcher::instance();
        $enabled_currencies = $currency_switcher->enabled_currencies();
        $available_currencies = array();
        
        foreach ( $enabled_currencies as $currency_code ) {
            $available_currencies[ $currency_code ] = get_woocommerce_currency_symbol( $currency_code );
        }
        
        return $available_currencies;
    }
    
    return aqualuxe_default_get_currencies();
}

/**
 * Aelia get currency symbol
 *
 * @param string $currency Currency code.
 * @return string
 */
function aqualuxe_aelia_get_currency_symbol( $currency ) {
    return get_woocommerce_currency_symbol( $currency );
}

/**
 * AJAX handler for currency switching
 */
function aqualuxe_ajax_switch_currency() {
    if ( ! isset( $_POST['currency'] ) || ! isset( $_POST['nonce'] ) ) {
        wp_send_json_error( array( 'message' => __( 'Invalid request', 'aqualuxe' ) ) );
    }
    
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe-currency-switch' ) ) {
        wp_send_json_error( array( 'message' => __( 'Security check failed', 'aqualuxe' ) ) );
    }
    
    $currency = sanitize_text_field( wp_unslash( $_POST['currency'] ) );
    $currencies = aqualuxe_get_currencies();
    
    if ( ! isset( $currencies[ $currency ] ) ) {
        wp_send_json_error( array( 'message' => __( 'Invalid currency', 'aqualuxe' ) ) );
    }
    
    // Handle currency switching based on the active multi-currency plugin
    if ( class_exists( 'WCML_Multi_Currency' ) ) {
        // WooCommerce Multilingual
        global $woocommerce_wpml;
        if ( isset( $woocommerce_wpml->multi_currency ) ) {
            $woocommerce_wpml->multi_currency->set_client_currency( $currency );
        }
    } elseif ( class_exists( 'WOOCS' ) ) {
        // WooCommerce Currency Switcher
        global $WOOCS;
        if ( isset( $WOOCS ) ) {
            $WOOCS->set_currency( $currency );
        }
    } elseif ( class_exists( 'WC_Aelia_CurrencySwitcher' ) ) {
        // Aelia Currency Switcher
        WC()->session->set( 'aelia_cs_selected_currency', $currency );
    } else {
        // Default WooCommerce (no multi-currency)
        // Just set a cookie for demo purposes
        setcookie( 'aqualuxe_currency', $currency, time() + MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
    }
    
    wp_send_json_success( array(
        'message' => sprintf(
            /* translators: %s: currency code */
            __( 'Currency switched to %s', 'aqualuxe' ),
            $currency
        ),
        'currency' => $currency,
        'symbol' => aqualuxe_get_currency_symbol( $currency ),
    ) );
}

/**
 * Currency switcher modal
 */
function aqualuxe_currency_switcher_modal() {
    $currencies = aqualuxe_get_currencies();
    $current_currency = aqualuxe_get_currency();
    
    if ( count( $currencies ) <= 1 ) {
        return;
    }
    ?>
    <div id="currency-switcher-modal" class="currency-switcher-modal" style="display: none;">
        <div class="currency-switcher-overlay"></div>
        <div class="currency-switcher-content">
            <div class="currency-switcher-header">
                <h3><?php esc_html_e( 'Select Currency', 'aqualuxe' ); ?></h3>
                <button class="currency-switcher-close" aria-label="<?php esc_attr_e( 'Close', 'aqualuxe' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                        <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <div class="currency-switcher-body">
                <ul class="currency-list">
                    <?php foreach ( $currencies as $code => $symbol ) : ?>
                        <li class="currency-item<?php echo $code === $current_currency ? ' active' : ''; ?>" data-currency="<?php echo esc_attr( $code ); ?>">
                            <span class="currency-symbol"><?php echo esc_html( $symbol ); ?></span>
                            <span class="currency-code"><?php echo esc_html( $code ); ?></span>
                            <span class="currency-name"><?php echo esc_html( aqualuxe_get_currency_name( $code ) ); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <script>
    (function() {
        document.addEventListener('DOMContentLoaded', function() {
            // Currency switcher functionality
            var currencySwitcherModal = document.getElementById('currency-switcher-modal');
            var currencySwitcherToggle = document.querySelector('.currency-switcher .current-currency');
            var currencySwitcherClose = document.querySelector('.currency-switcher-close');
            var currencyItems = document.querySelectorAll('.currency-item');
            
            if (currencySwitcherToggle) {
                currencySwitcherToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    currencySwitcherModal.style.display = 'block';
                });
            }
            
            if (currencySwitcherClose) {
                currencySwitcherClose.addEventListener('click', function() {
                    currencySwitcherModal.style.display = 'none';
                });
            }
            
            // Close modal when clicking on overlay
            if (currencySwitcherModal) {
                currencySwitcherModal.querySelector('.currency-switcher-overlay').addEventListener('click', function() {
                    currencySwitcherModal.style.display = 'none';
                });
            }
            
            // Currency switching
            currencyItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    var currency = this.getAttribute('data-currency');
                    
                    // Send AJAX request to switch currency
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            var response = JSON.parse(xhr.responseText);
                            
                            if (response.success) {
                                // Reload page to apply currency change
                                window.location.reload();
                            }
                        }
                    };
                    
                    xhr.send('action=aqualuxe_switch_currency&currency=' + currency + '&nonce=<?php echo esc_js( wp_create_nonce( 'aqualuxe-currency-switch' ) ); ?>');
                });
            });
        });
    })();
    </script>
    <?php
}

/**
 * Get currency name
 *
 * @param string $currency_code Currency code.
 * @return string
 */
function aqualuxe_get_currency_name( $currency_code ) {
    $currency_names = array(
        'USD' => __( 'US Dollar', 'aqualuxe' ),
        'EUR' => __( 'Euro', 'aqualuxe' ),
        'GBP' => __( 'British Pound', 'aqualuxe' ),
        'JPY' => __( 'Japanese Yen', 'aqualuxe' ),
        'CAD' => __( 'Canadian Dollar', 'aqualuxe' ),
        'AUD' => __( 'Australian Dollar', 'aqualuxe' ),
        'CHF' => __( 'Swiss Franc', 'aqualuxe' ),
        'CNY' => __( 'Chinese Yuan', 'aqualuxe' ),
        'INR' => __( 'Indian Rupee', 'aqualuxe' ),
        'BRL' => __( 'Brazilian Real', 'aqualuxe' ),
        'RUB' => __( 'Russian Ruble', 'aqualuxe' ),
        'KRW' => __( 'South Korean Won', 'aqualuxe' ),
        'SGD' => __( 'Singapore Dollar', 'aqualuxe' ),
        'NZD' => __( 'New Zealand Dollar', 'aqualuxe' ),
        'MXN' => __( 'Mexican Peso', 'aqualuxe' ),
        'HKD' => __( 'Hong Kong Dollar', 'aqualuxe' ),
        'NOK' => __( 'Norwegian Krone', 'aqualuxe' ),
        'SEK' => __( 'Swedish Krona', 'aqualuxe' ),
        'DKK' => __( 'Danish Krone', 'aqualuxe' ),
        'PLN' => __( 'Polish Zloty', 'aqualuxe' ),
    );
    
    return isset( $currency_names[ $currency_code ] ) ? $currency_names[ $currency_code ] : $currency_code;
}