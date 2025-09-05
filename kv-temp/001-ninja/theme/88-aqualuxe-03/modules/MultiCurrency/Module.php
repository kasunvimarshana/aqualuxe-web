<?php
namespace AquaLuxe\Modules\MultiCurrency;

use AquaLuxe\Core\Contracts\Module as ModuleContract;

class Module implements ModuleContract {
    private array $rates = [
        'USD' => 1.00,
        'EUR' => 0.92,
        'GBP' => 0.78,
        'LKR' => 300.00,
    ];
    private string $default = 'USD';

    public function boot(): void {
        if ( ! class_exists( '\\WooCommerce' ) ) { return; }
        \add_filter( 'woocommerce_currency', [ $this, 'currency' ] );
        \add_filter( 'woocommerce_product_get_price', [ $this, 'convert_price' ], 10, 2 );
        \add_filter( 'woocommerce_product_get_regular_price', [ $this, 'convert_price' ], 10, 2 );
        \add_filter( 'woocommerce_product_get_sale_price', [ $this, 'convert_price' ], 10, 2 );
        \add_action( 'init', [ $this, 'capture_pref' ] );
    }

    public function capture_pref(): void {
    $q = isset( $_GET['currency'] ) ? strtoupper( sanitize_text_field( \function_exists('wp_unslash') ? \call_user_func('wp_unslash', $_GET['currency']) : $_GET['currency'] ) ) : '';
        if ( $q && isset( $this->rates[ $q ] ) ) {
            $month = \defined('MONTH_IN_SECONDS') ? \constant('MONTH_IN_SECONDS') : 30 * 24 * 3600;
            $cpath = \defined('COOKIEPATH') ? \constant('COOKIEPATH') : '/';
            $cdomain = \defined('COOKIE_DOMAIN') ? \constant('COOKIE_DOMAIN') : '';
            \setcookie( 'aqlx_currency', $q, time() + $month, $cpath, $cdomain, \function_exists('is_ssl') ? \call_user_func('is_ssl') : false, true );
            $_COOKIE['aqlx_currency'] = $q;
        }
    }

    public function currency( $currency ) {
    $pref = isset( $_COOKIE['aqlx_currency'] ) ? sanitize_text_field( \function_exists('wp_unslash') ? \call_user_func('wp_unslash', $_COOKIE['aqlx_currency'] ) : $_COOKIE['aqlx_currency'] ) : $this->default;
        return isset( $this->rates[ $pref ] ) ? $pref : $currency;
    }

    public function convert_price( $price, $product ) {
        $base = $this->rates[ $this->default ];
    $pref = isset( $_COOKIE['aqlx_currency'] ) ? sanitize_text_field( \function_exists('wp_unslash') ? \call_user_func('wp_unslash', $_COOKIE['aqlx_currency'] ) : $_COOKIE['aqlx_currency'] ) : $this->default;
        $rate = $this->rates[ $pref ] ?? $base;
        if ( $base <= 0 ) { return $price; }
        $converted = (float) $price * ( $rate / $base );
        return round( $converted, 2 );
    }
}
