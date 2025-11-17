<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Currency_Service_Provider
{
    public function register(Container $c): void
    {
        $c->set('currency', (object) [
            'code' => function () {
                return apply_filters('aqualuxe_currency_code', 'USD');
            },
            'symbol' => function () {
                return apply_filters('aqualuxe_currency_symbol', '$');
            },
            'format' => function (float $amount): string {
                $symbol = apply_filters('aqualuxe_currency_symbol', '$');
                $formatted = number_format_i18n($amount, 2);
                return apply_filters('aqualuxe_price_format', $symbol . $formatted, $amount, $symbol);
            },
        ]);
    }

    public function boot(Container $c): void {}
}
