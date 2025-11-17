<?php
namespace Aqualuxe\Modules\Commerce;

defined('ABSPATH') || exit;

final class CurrencyModule {
    public static function register(): void {
        \add_action( 'init', [ __CLASS__, 'bootstrap' ] );
    }

    public static function bootstrap(): void {
        // Allow sites to set currency via filter or option.
        $currency = \apply_filters( 'aqlx/currency/code', get_option( 'aqlx_currency', 'USD' ) );
        \define( 'AQUALUXE_CURRENCY', is_string( $currency ) ? $currency : 'USD' );
    }

    public static function format( float $amount, ?string $currency = null ): string {
        $currency = $currency ?: ( defined( 'AQUALUXE_CURRENCY' ) ? AQUALUXE_CURRENCY : 'USD' );
        $symbol = match ( $currency ) {
            'EUR' => '€',
            'GBP' => '£',
            'LKR' => 'Rs',
            'JPY' => '¥',
            default => '$',
        };
        return sprintf( '%s%s', $symbol, number_format_i18n( $amount, 2 ) );
    }
}
