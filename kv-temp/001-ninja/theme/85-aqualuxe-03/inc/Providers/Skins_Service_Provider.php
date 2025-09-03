<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Skins_Service_Provider
{
    public function register(Container $c): void
    {
        add_filter('body_class', function (array $classes) {
            $skin = apply_filters('aqualuxe_active_skin', 'default');
            $classes[] = 'skin-' . sanitize_html_class($skin);
            return $classes;
        });
    }

    public function boot(Container $c): void {}
}
