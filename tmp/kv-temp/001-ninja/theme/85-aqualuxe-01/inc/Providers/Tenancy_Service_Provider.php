<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Tenancy_Service_Provider
{
    public function register(Container $c): void
    {
        // Per-site options helper for multisite/tenancy
        $c->set('tenant.options', (object) [
            'get' => function (string $key, $default = null) {
                if (is_multisite()) {
                    return get_blog_option(get_current_blog_id(), $key, $default);
                }
                return get_option($key, $default);
            },
        ]);
    }

    public function boot(Container $c): void {}
}
