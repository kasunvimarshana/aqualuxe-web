<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Multilingual_Service_Provider
{
    public function register(Container $c): void
    {
        // Text domain is loaded in functions.php; here we add helpers/compat.
        $c->set('i18n.locale', (object) [
            'current' => function () {
                if (function_exists('pll_current_language')) {
                    return pll_current_language('locale');
                }
                return get_locale();
            },
        ]);
    }

    public function boot(Container $c): void {}
}
