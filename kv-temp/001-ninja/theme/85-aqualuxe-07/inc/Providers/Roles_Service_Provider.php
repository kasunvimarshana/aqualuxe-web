<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Roles_Service_Provider
{
    public function register(Container $c): void
    {
        add_action('after_switch_theme', [$this, 'setup_roles']);
    }

    public function boot(Container $c): void {}

    public function setup_roles(): void
    {
        // Example granular permissions
        add_role('vendor', __('Vendor', 'aqualuxe'), [
            'read' => true,
            'edit_listings' => true,
            'publish_listings' => true,
        ]);
    }
}
