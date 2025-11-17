    public static function enqueue_assets() {
        wp_enqueue_style(
            'aqualuxe-multicurrency',
            AQUALUXE_URI . 'assets/dist/css/multicurrency.css',
            [],
            AQUALUXE_VERSION
        );
        wp_enqueue_script(
            'aqualuxe-multicurrency',
            AQUALUXE_URI . 'assets/dist/js/multicurrency.js',
            [],
            AQUALUXE_VERSION,
            true
        );
    }
<?php
/**
 * Multicurrency Module Loader
 *
 * @package AquaLuxe\Modules\Multicurrency
 */
namespace AquaLuxe\Modules\Multicurrency;

if ( ! defined( 'ABSPATH' ) ) exit;

class Loader {
    /**
     * Currencies and rates (filterable)
     * @return array
     */
    public static function get_currencies() {
        $currencies = [
            'USD' => [ 'symbol' => '$', 'rate' => 1 ],
            'EUR' => [ 'symbol' => '€', 'rate' => 0.92 ],
            'GBP' => [ 'symbol' => '£', 'rate' => 0.78 ],
        ];
        return apply_filters( 'aqualuxe_multicurrency_currencies', $currencies );
    }

    /**
     * Country to currency mapping (filterable)
     * @return array
     */
    public static function get_country_currency_map() {
        $map = [
            'GB' => 'GBP',
            'DE' => 'EUR',
            'FR' => 'EUR',
            'IT' => 'EUR',
            // Add more as needed
        ];
        return apply_filters( 'aqualuxe_multicurrency_country_map', $map );
    }

    public static function init() {
        add_action( 'init', [ __CLASS__, 'handle_switch' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
        add_action( 'woocommerce_product_get_price', [ __CLASS__, 'convert_price' ], 20, 2 );
        add_action( 'woocommerce_product_get_regular_price', [ __CLASS__, 'convert_price' ], 20, 2 );
        add_action( 'woocommerce_product_get_sale_price', [ __CLASS__, 'convert_price' ], 20, 2 );
        add_filter( 'woocommerce_currency_symbol', [ __CLASS__, 'currency_symbol' ], 10, 2 );
        add_action( 'wp_footer', [ __CLASS__, 'currency_switcher' ] );
    }

    public static function get_current_currency() {
        $currencies = self::get_currencies();
        if ( isset( $_COOKIE['aqualuxe_currency'] ) && isset( $currencies[ $_COOKIE['aqualuxe_currency'] ] ) ) {
            return $_COOKIE['aqualuxe_currency'];
        }
        // Geolocation-based default currency
        $country = self::detect_country();
        $map = self::get_country_currency_map();
        if ( $country && isset($map[$country]) && isset($currencies[$map[$country]]) ) {
            return $map[$country];
        }
        // Fallback to first currency (usually USD)
        $codes = array_keys($currencies);
        return $codes ? $codes[0] : 'USD';
    }

    // Simple IP-based country detection (uses free ipinfo.io API)
    public static function detect_country() {
        if ( isset($_SERVER['HTTP_CLIENT_IP']) ) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        }
        if ( empty($ip) || $ip === '127.0.0.1' || $ip === '::1' ) return '';
        $country = get_transient('aqualuxe_geo_country_' . md5($ip));
        if ( $country ) return $country;
        $response = wp_remote_get('https://ipinfo.io/' . $ip . '/country', ['timeout' => 2]);
        if ( is_wp_error($response) ) return '';
        $body = trim(wp_remote_retrieve_body($response));
        if ( preg_match('/^[A-Z]{2}$/', $body) ) {
            set_transient('aqualuxe_geo_country_' . md5($ip), $body, 24 * HOUR_IN_SECONDS);
            return $body;
        }
        return '';
    }

    public static function handle_switch() {
        $currencies = self::get_currencies();
        if ( isset( $_GET['aqualuxe_currency'] ) && isset( $currencies[ $_GET['aqualuxe_currency'] ] ) ) {
            setcookie( 'aqualuxe_currency', $_GET['aqualuxe_currency'], time() + 30 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
            $_COOKIE['aqualuxe_currency'] = $_GET['aqualuxe_currency'];
        }
    }

    public static function convert_price( $price, $product ) {
        $currencies = self::get_currencies();
        $currency = self::get_current_currency();
        $rate = $currencies[ $currency ]['rate' ] ?? 1;
        return round( $price * $rate, wc_get_price_decimals() );
    }

    public static function currency_symbol( $symbol, $currency ) {
        $currencies = self::get_currencies();
        if ( isset( $currencies[ $currency ] ) ) {
            return $currencies[ $currency ]['symbol' ];
        }
        return $symbol;
    }

    public static function currency_switcher() {
        $currencies = self::get_currencies();
        $current = self::get_current_currency();
        echo '<form class="aqualuxe-currency-switcher" method="get" style="margin:2rem 0;text-align:center;">';
        foreach ( $currencies as $code => $data ) {
            echo '<button type="submit" name="aqualuxe_currency" value="' . esc_attr( $code ) . '"' . ( $current === $code ? ' style="font-weight:bold;"' : '' ) . '>';
            echo esc_html( $data['symbol'] . ' ' . $code );
            echo '</button> ';
        }
        echo '</form>';
    }
}

Loader::init();
